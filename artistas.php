<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './include/Artista.php';
require_once './include/funciones.php';
require_once './include/DB.php';

// Recuperamos la información de la sesión
session_start();

// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) {
    die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}

if (isset($_SESSION['actores'])) {
    unset($_SESSION['actores']);
}
$cliente = new DB();

$mensaje = '';
$nombre_artista = '';
$apellidos_artista = '';

// Comprobamos si se ha enviado por post un id
if (isset($_POST['id'])) {

    // Si es así, lo asignamos a una variable
    $id = $_POST['id'];
} else {

    // En caso contrario lo anulamos
    $id = "";
}

// Verificamos si la operación a realizar es una petición de modificación. Esto se producirá cuando por post tengamos un id y se ha pulsado 
// el botón realizar una modificación, pero nos e ha pulsado el botón de grabar.
if (isset($_POST['Modificar']) && isset($_POST['id']) && !isset($_POST['nombre']) && !isset($_POST['apellidos'])) {

    // Si es así, le asignamos el texto Modificar a la variable $boton
    $boton = "Modificar";
} else {
    // Si no es el caso, le asignamos Añadir
    $boton = "Añadir";
}


// Comprobamos si ya se ha pulsado el botón de Añadir
if (isset($_POST['Añadir'])) {

    // Verificamos que se hayan introducido nombre y apellidos
    if (empty($_POST['nombre']) || empty($_POST['apellidos'])) {

        // Si no es así, mostraremos un mensaje de error
        $mensaje = "Debe de introducir un nombre y un apellidos";
    } else {
        // Realizamos el alta del artista usando el servicio y comprobamos el exito de la acción
        if ($cliente->altaArtistas($_POST['nombre'], $_POST['apellidos']) === 0) {

            // Si es correcto, mostraremos un mensaje
            $mensaje = "correcto";
        } else {
            // Si la acción no ha sido correcta mostramos un mensaje
            $mensaje = "Se ha producido un error a dar de alta el artista";
        }
    }
}

// Comprobamos si se ha pulsado el botón de modificar
if (isset($_POST['Modificar'])) {

    // Comprobamos si se ha psado un id
    if (!empty($_POST['id'])) {

        // Si se ha psado, comprobamos si no se ha psado nombre y apellidos
        if (!isset($_POST['nombre']) && !isset($_POST['apellidos'])) {

            // Si se ha pasado un id, pero no el nombre y el apellidos, 
            // tenemos que realizar una petición al servicio para recuperar la información del mismo,
            $artista = $cliente->listaArtista($_POST['id']);

            // Comprobamos si la operación se ha realizado correctamente
            if ($artista) {

                // Si es así, almacenamos el nombre y los apellidos del artista 
                // en dos variables
                $nombre_artista = $artista->getNombre();
                $apellidos_artista = $artista->getApellidos();

                // Modificamos el valor de la variable botón para que cambie el nombre del mismo
                $boton = 'Modificar';
            } else {
                // Si la acción no ha sido correcta mostramos un mensaje
                $mensaje = "No se ha podido recupear la información del artista a modificar";
            }
        } else {
            // Si tenemos un id, un nombre y unos apellidos, es una petición de actualización de datos.
            // Realizamos una petición al servidor para modificar los datos del artista comprobando el 
            // resultado de la acción
            if ($cliente->modificarArtista($_POST['id'], $_POST['nombre'], $_POST['apellidos']) === 0) {
                // Si todo es correcto, mostramos un mensaje
                $mensaje = 'Correcto';
            } else {
                // Si la acción no ha sido correcta mostramos un mensaje
                $mensaje = 'No se ha podido modificar el artista';
            }
        }
    } else {
        // Si no tenemos id, mostramos un mensaje
        $mensaje = 'No se ha podido identificar el id del artista a modificar';
    }
}

// Comprobamos si se ha pulsado el botón de eliminar
if (isset($_POST['Eliminar'])) {
    // Verificamos que se haya enviado el id del artista a eliminar
    if (!empty($_POST['id'])) {

        // Realizamos una petición al servidor para eliminar el artista con el 
        // id especificado y verificamos el resultado de la operación
        if ($cliente->bajaArtista($_POST['id']) === 0) {
            // Si todo es correcto, mostramos un mensaje
            $mensaje = "Se ha eliminado el artista de forma correcta";
        } else {
            // Si la acción no ha sido correcta mostramos un mensaje
            $mensaje = 'Se ha producido un error durante la baja del artista';
        }
    } else {
        // Si no tenemos id, mostramos un mensaje
        $mensaje = 'No se ha podido identificar el id del artista a borrar';
    }
}
?>
<!DOCTYPE HTML>
<html>

    <head>
        <title>Inicio</title>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="styles/main.css" />
    </head>

    <body>
        <div id="doc" class="yui-t7">
            <div id="hd">
                <div id="header"></div>
                <?php require_once './menu.php'; ?>
            </div> 

            <div id="bd">
                <div id="yui-main">

                    <div class="yui-b">
                        <div class="contentArtistas">


                            <form action='artistas.php' method='post' class="form-style-9">
                                <ul>
                                    <li>
                                        <input type="text" name="nombre" class="field-style field-split align-left" placeholder="Nombre" id="nombre" value="<?php echo $nombre_artista ?>">

                                        <input type="text" name="apellidos" class="field-style field-split align-right" placeholder="Apellidos" id="apellidos" value="<?php echo $apellidos_artista ?>"></input>
                                    </li>
                                    <li>
                                        <input type='submit' name='<?php echo $boton ?>' value='<?php echo $boton ?>'/>
                                    </li>
                                    <input type='hidden' id='id' name='id' value='<?php echo $id ?>' />
                                    <div><span class='error'><?php echo $mensaje; ?></span></div>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="yui-b">
                    <div id="secondary" class="scroll">                     
                        <div class="form-style-9">
                            <ul>
                                <li>
                                    <table class="tabla">
                                        <tr>
                                            <td>Nombre</td>
                                            <td>Apellidos</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                        // Recuperamos el listado de todos los artistas
                                        $array = corregirArrayStdClass($cliente->listaArtistas());
                                        

                                        // Recorremos el array de artistas
                                        foreach ($array as $valor) {

                                            // Para cada iteración creamos una fila de la tabla
                                            echo '<tr>';

                                            // Además creamos una celda que contenga el nombre del artista
                                            echo '<td>';
                                            echo $valor->getNombre();
                                            echo '</td>';

                                            // Creamos otra celda para los apellidos del artista
                                            echo '<td>';
                                            echo $valor->getApellidos();
                                            echo '</td>';

                                            // Creamos una celda más para que contenga el formulario con el botón de modificar artista
                                            echo '<td>';
                                            echo "<form action='artistas.php' method='post'>";
                                            echo "<input type='submit' name='Modificar' value='Modificar'/>";

                                            // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                            echo "<input type='hidden' id='id' name='id' value='" . $valor->getId_artista() . "' />";
                                            echo "</form>";
                                            echo '</td>';

                                            // Creamos una celda más para que contenga el formulario con el botón de eliminar artista
                                            echo '<td>';
                                            echo "<form action='artistas.php' method='post'>";
                                            echo "<input type='submit' name='Eliminar' value='Eliminar'/>";

                                            // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                            echo "<input type='hidden' id='id' name='id' value='" . $valor->getId_artista() . "' />";
                                            echo "</form>";
                                            echo '</td>';

                                            // Cerramos la fila de la iteración
                                            echo '</tr>';
                                        }
                                        ?>
                                </li>
                                </table>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </body>

</html>