<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './include/Genero.php';
require_once './include/Artista.php';
require_once './include/Pelicula.php';
require_once './include/funciones.php';
require_once './include/DB.php';
// Recuperamos la información de la sesión
session_start();

// Y comprobamos que el usuario se haya autentificado
if (!isset($_SESSION['usuario'])) {
    die("Error - debe <a href='login.php'>identificarse</a>.<br />");
}
$cliente = new DB();


// Creamos un array para almacenar los actores otro para directores y otro para guionistas
$actores = array();
$directores = array();
$guionistas = array();

// Inicializamos las variables para mostrar información en pantalla
$id_pelicula = '';
$nombre = '';
$anyo = '';
$duracion = '';
$sinopsis = '';
$genero = '';

$textoBoton = 'Añadir película';


// Comprobamos si en sesión tenemos almacenada información de la pelicula
// Comprobamos si hay actores
if (isset($_SESSION['actores'])) {

    // Si es así, la copiamos al array local
    $actores = $_SESSION['actores'];
}
// Comprobamos si hay directores
if (isset($_SESSION['directores'])) {
    // Si es así, la copiamos al array local
    $directores = $_SESSION['directores'];
}

// Comprobamos si hay guionistas
if (isset($_SESSION['guionistas'])) {
    // Si es así, la copiamos al array local
    $guionistas = $_SESSION['guionistas'];
}

// Almacenamos el nombre recuperado de POST
if (isset($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
}

// Almacenamos el año recuperado de POST
if (isset($_POST['anyo'])) {
    $anyo = $_POST['anyo'];
}

// Almacenamos la duración recuperado de POST
if (isset($_POST['duracion'])) {
    $duracion = $_POST['duracion'];
}

// Almacenamos la sinopsis recuperado de POST
if (isset($_POST['sinopsis'])) {
    $sinopsis = $_POST['sinopsis'];
}

// Almacenamos el genero recuperado de POST
if (isset($_POST['genero'])) {
    $genero = $_POST['genero'];
}

// Almacenamos el id de la película recuperado de POST
if (isset($_POST['id_pelicula'])) {
    $id_pelicula = $_POST['id_pelicula'];
}

// Comprobamos si tenemos información del  texto del botón en sesión
if (isset($_SESSION['textoBoton'])) {

    // Si es así, la almacenamos en la variable local
    $textoBoton = $_SESSION['textoBoton'];
}


// Comprobamos si se está añadiendo un actor
if (isset($_POST['añadirActor'])) {

    // Declaramos una variable de control para insertar el actor
    $insertar = TRUE;

    // Iteramos por el array de actores para comprobar si el actor ya ha sido añadido
    for ($index = 0; $index < count($actores); $index++) {

        // Comprobamos si el id que se nos ha pedido añadir ya se encuentra en el array
        if ($actores[$index]->getId_artista() === $_POST['añadirActor']) {
            // Si es así, modificamos el valor de la variable de control
            $insertar = FALSE;
        }
    }

    // Comprobamos si es necesario insertar el registro
    if ($insertar) {
        // Si es así, recuperamos el actor y lo copiamos al array
        $actores[] = $cliente->listaArtista($_POST['añadirActor']);

        // Actualizamos la información de actores en sesión
        $_SESSION['actores'] = $actores;
    }

    // Eliminamos la información de añadirActor de las variables POST
    unset($_POST['añadirActor']);
}

// Comprobamos si se está eliminando un actor
if (isset($_POST['eliminarActor'])) {

    // Iteramos por el array de actores
    for ($index = 0; $index < count($actores); $index++) {

        // Comprobamos si el id de alguno de los actores es igual al que se nos ha pasdo por POST para eliminarlo
        if ($actores[$index]->getId_artista() === $_POST['eliminarActor']) {
            // Si es así, lo eliminamos del array
            array_splice($actores, $index, 1);

            // Actualizamos la información de los actores en sesión
            $_SESSION['actores'] = $actores;

            // Eliminamos la información de eliminarActor de las variables POST
            unset($_POST['eliminarActor']);

            // Forzamos la salida del bucle, puesto que solo puede haber un actor con ese id;
            break;
        }
    }
}

// Comprobamos si se está añadiendo un director
if (isset($_POST['añadirDirector'])) {

    // Declaramos una variable de control para insertar el director
    $insertar = TRUE;

    // Iteramos por el array de directores para comprobar si el director ya ha sido añadido
    for ($index = 0; $index < count($directores); $index++) {

        // Comprobamos si el id que se nos ha pedido añadir ya se encuentra en el array
        if ($directores[$index]->getId_artista() === $_POST['añadirDirector']) {
            // Si es así, modificamos el valor de la variable de control
            $insertar = FALSE;
        }
    }

    // Comprobamos si es necesario insertar el registro
    if ($insertar) {
        // Si es así, recuperamos el director y lo copiamos al array
        $directores[] = $cliente->listaArtista($_POST['añadirDirector']);

        // Actualizamos la información de directores en sesión
        $_SESSION['directores'] = $directores;
    }

    // Eliminamos la información de añadirDirector de las variables POST
    unset($_POST['añadirDirector']);
}

// Comprobamos si se está eliminando un director
if (isset($_POST['eliminarDirector'])) {

    // Iteramos por el array de actores
    for ($index = 0; $index < count($directores); $index++) {

        // Comprobamos si el id de alguno de los actores es igual al que se nos ha pasdo por POST para eliminarlo
        if ($directores[$index]->getId_artista() === $_POST['eliminarDirector']) {
            // Si es así, lo eliminamos del array
            array_splice($directores, $index, 1);

            // Actualizamos la información de los actores en sesión
            $_SESSION['directores'] = $directores;

            // Eliminamos la información de eliminarDirector de las variables POST
            unset($_POST['eliminarDirector']);

            // Forzamos la salida del bucle, puesto que solo puede haber un director con ese id;
            break;
        }
    }
}

// Comprobamos si se está añadiendo un guionista
if (isset($_POST['añadirGuionista'])) {

    // Declaramos una variable de control para insertar el guionista
    $insertar = TRUE;

    // Iteramos por el array de directores para comprobar si el guionista ya ha sido añadido
    for ($index = 0; $index < count($guionistas); $index++) {

        // Comprobamos si el id que se nos ha pedido añadir ya se encuentra en el array
        if ($guionistas[$index]->getId_artista() === $_POST['añadirGuionista']) {
            // Si es así, modificamos el valor de la variable de control
            $insertar = FALSE;
        }
    }

    // Comprobamos si es necesario insertar el registro
    if ($insertar) {
        // Si es así, recuperamos el actor y lo copiamos al array
        $guionistas[] = $cliente->listaArtista($_POST['añadirGuionista']);

        // Actualizamos la información de actores en sesión
        $_SESSION['guionistas'] = $guionistas;
    }

    // Eliminamos la información de añadirActor de las variables POST
    unset($_POST['añadirGuionista']);
}

// Comprobamos si se está eliminando un guionista
if (isset($_POST['eliminarGuionista'])) {

    // Iteramos por el array de actores
    for ($index = 0; $index < count($guionistas); $index++) {

        // Comprobamos si el id de alguno de los actores es igual al que se nos ha pasdo por POST para eliminarlo
        if ($guionistas[$index]->getId_artista() === $_POST['eliminarGuionista']) {
            // Si es así, lo eliminamos del array
            array_splice($guionistas, $index, 1);

            // Actualizamos la información de los actores en sesión
            $_SESSION['guionistas'] = $directores;

            // Eliminamos la información de eliminarDirector de las variables POST
            unset($_POST['eliminarGuionista']);

            // Forzamos la salida del bucle, puesto que solo puede haber un director con ese id;
            break;
        }
    }
}

// Comprobamos si hay una petición para añadir una pelicula
if (isset($_POST['añadirPelicula'])) {

    
   
    
    // Comprobamos si en la información de POSt viene un id_pelicula. De ser así, 
    // tratamos con una modificación de datos, en caso contrario con un alta
    if ($_POST['id_pelicula'] === "") {
        // Recuperamos el objeto género
        $genero = $cliente->listaGenero($_POST['genero']);

        // Creamos un objeto película para enviar al servicio
        $pelicula = new Pelicula('0', $_POST['nombre'], $_POST['anyo'], $genero, $_POST['sinopsis'], $_POST['duracion'], $directores, $guionistas, $actores);

        // Comprobamos que el objeto se ha creado bien
        if ($pelicula) {
            // Comprobamos si el alta de la película se ha realizado correctamente
            if ($cliente->altaPelicula($pelicula) > 0) {
                // Si la operación se ha creado correctamente, mostramos un mensaje
                $mensaje = 'La pelicula se ha dado de alta correctamente';
            } else {
                // Si se ha producido un error mostramos un mensaje
                $mensaje = 'Se ha producido un error al dar de alta la película';
            }
        }
    } else {

        // Recuperamos el objeto género
        $genero = $cliente->listaGenero($_POST['genero']);

        // Creamos un objeto película para enviar al servicio
        $pelicula = new Pelicula($_POST['id_pelicula'], $_POST['nombre'], $_POST['anyo'], $genero, $_POST['sinopsis'], $_POST['duracion'], $directores, $guionistas, $actores);

        $cliente->modificarPeliculaEx($pelicula);
    }
    // Eliminamos la referencia a los actores, los directores y guionistas de la sesión
    unset($_SESSION['actores']);
    unset($_SESSION['directores']);
    unset($_SESSION['guionistas']);

    // Eliminamos la referencia al texto del botón de la sesión
    unset($_SESSION['textoBoton']);

    // Inicializamos a 0 los arrays de actores, directores y guionistas
    $actores = array();
    $directores = array();
    $guionistas = array();

    // Limpiamos los valores de las variables
    $id_pelicula = '';
    $nombre = '';
    $anyo = '';
    $duracion = '';
    $sinopsis = '';
    $genero = '0';

    $textoBoton = 'Añadir película';
}

// Comprobamos si se ha pulsado el botón de eliminar
if (isset($_POST['eliminarPelicula'])) {
    // Verificamos que se haya enviado el id de la película a eliminar
    if (!empty($_POST['id'])) {

        // Realizamos una petición al servidor para eliminar la película con el 
        // id especificado y verificamos el resultado de la operación
        if ($cliente->bajaPelicula($_POST['id']) === 0) {
            // Si todo es correcto, mostramos un mensaje
            $mensaje = "Se ha eliminado la película de forma correcta";
        } else {
            // Si la acción no ha sido correcta mostramos un mensaje
            $mensaje = 'Se ha producido un error durante la baja de la película';
        }
    } else {
        // Si no tenemos id, mostramos un mensaje
        $mensaje = 'No se ha podido identificar el id de la película a borrar';
    }
}

// Comprobamos si se ha pulsado el botón de eliminar
if (isset($_POST['modificarPelicula'])) {
    // Verificamos que se haya enviado el id de la película a eliminar
    if (!empty($_POST['id'])) {

        


        // Realizamos una petición al servidor para recuperar la información de 
        // la película con id especificado
        $pelicula = $cliente->listaPelicula($_POST['id']);

        // Verificamos el resultado de la operación
        if ($pelicula) {


            // Si todo es correcto, volcamos a las variables la información de 
            // la película con el fin de que se rellenen los campos de la página 
            // con esta información
            $id_pelicula = $pelicula->getId_pelicula();

            $nombre = $pelicula->getNombre();
            $anyo = $pelicula->getAnyo();
            $duracion = $pelicula->getDuracion();
            $sinopsis = $pelicula->getSinopsis();

            $genero = $pelicula->getGenero()->getId_genero();

            $actores = corregirArrayStdClass($pelicula->getActores());
            $directores = corregirArrayStdClass($pelicula->getDirectores());
            $guionistas = corregirArrayStdClass($pelicula->getGuionistas());

            // Actualizamos la información de los integrantes de la pelicula en sesión
            $_SESSION['actores'] = $actores;
            $_SESSION['directores'] = $directores;
            $_SESSION['guionistas'] = $guionistas;

            $_SESSION['textoBoton'] = 'Modificar película';

            $textoBoton = $_SESSION['textoBoton'];
        } else {
            // Si la acción no ha sido correcta mostramos un mensaje
            $mensaje = 'No se ha podido recuperar la información de la pelicula';
        }
    } else {
        // Si no tenemos id, mostramos un mensaje
        $mensaje = 'No se ha podido identificar el id de la película a modificar';
    }
}
?>

<!DOCTYPE HTML>
<html>

    <head>
        <title>Películas</title>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="styles/main.css" />
        <script src="scripts.js" type="text/javascript"></script>
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
                        <div class="content" class="scroll">

                            <div class="form-style-9">
                                <form action="index.php" id="formDatosBasicos" method="post">                                
                                    <ul>
                                        <li>
                                            <input type="hidden" id="id_pelicula" name="id_pelicula" value="<?php echo $id_pelicula ?>" />
                                            <input type="text" id='nombre' name="nombre" class="field-style field-split align-left" placeholder="Nombre" value="<?php echo $nombre ?>" />
                                            <input type="number" id="anyo" name="anyo" class="field-style field-split align-right" placeholder="Año" value="<?php echo $anyo ?>" />

                                        </li>
                                        <li>
                                            <input type="number" id="duracion" name="duracion" class="field-style field-split align-left" placeholder="Duración" value="<?php echo $duracion ?>" />
                                            <select class="field-style field-split align-right" name="genero" id="genero">
                                                <?php
                                                $resultado = corregirArrayStdClass($cliente->listaGeneros());

                                                foreach ($resultado as $value) {
                                                    echo "<option value='" . $value->getId_genero();

                                                    if ($value->getId_genero() === $genero) {
                                                        echo "' selected>";
                                                    } else {
                                                        echo "'>";
                                                    }


                                                    echo $value->getTipo();

                                                    echo "</option>";
                                                }
                                                ?>
                                            </select>
                                        </li>

                                        <li>
                                            <textarea name="sinopsis" class="field-style" placeholder="Sinopsis" id="sinopsis"><?php echo $sinopsis ?></textarea>
                                        </li>                                        
                                    </ul>
                                </form>
                                <ul>
                                    <li>
                                        <form action="index.php" id='formAñadirActor' method="post">
                                            <select class="field-style field-split align-left" name="añadirActor">
                                                <?php
// Recuperamos el listado de todos los artistas
                                                $resultado = corregirArrayStdClass($cliente->listaArtistas());

// Iteramos por el array de objetos Artista
                                                foreach ($resultado as $value) {

                                                    // Comprobamos que el objeto Artista de la iteración no se 
                                                    // encuentre dentro del array de actores añadidos
                                                    if (!in_array($value, $actores, FALSE)) {

                                                        // Si es así, añadimos un elemento option con los valores del artista
                                                        echo "<option value='" . $value->getId_artista() . "'>";
                                                        echo $value->getNombre() . ' ';
                                                        echo $value->getApellidos() . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="button" id='añadirActor' value="Añadir actor" class="separacion-izquierda"/>
                                        </form>
                                    </li>

                                    <li>
                                        <table class="tabla">
                                            <tr>
                                                <td>Nombre</td>
                                                <td>Apellidos</td>
                                                <td></td>
                                            </tr>

                                            <?php
// Recorremos el array de actores
                                            foreach ($actores as $actor) {

                                                // Para cada iteración creamos una fila de la tabla
                                                echo '<tr>';

                                                // Además creamos una celda que contenga el nombre del artista
                                                echo '<td>';
                                                echo $actor->getNombre();
                                                echo '</td>';

                                                // Creamos otra celda para los apellidos del artista
                                                echo '<td>';
                                                echo $actor->getApellidos();
                                                echo '</td>';

                                                echo '<td>';
                                                echo "<form action='index.php' method='post'>";
                                                echo "<input type='button' class='boton-pequeño align-right' id='eliminarActor" . $actor->getId_artista() . "' name='Eliminar' value='Eliminar'/>";

                                                // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                                echo "<input type='hidden' id='id' name='eliminarActor' class='' value='" . $actor->getId_artista() . "' />";
                                                echo "</form>";
                                                echo '</td>';

                                                // Cerramos la fila de la iteración
                                                echo '</tr>';
                                            }
                                            ?>                                                

                                        </table>
                                    </li>
                                    <li>
                                        <form action="index.php" id='formAñadirDirector' method="post">
                                            <select class="field-style field-split align-left" name="añadirDirector">
                                                <?php
// Recuperamos el listado de todos los artistas
                                                $resultado = corregirArrayStdClass($cliente->listaArtistas());

// Iteramos por el array de objetos Artista
                                                foreach ($resultado as $value) {

                                                    // Comprobamos que el objeto Artista de la iteración no se 
                                                    // encuentre dentro del array de directores añadidos
                                                    if (!in_array($value, $directores, FALSE)) {

                                                        // Si es así, añadimos un elemento option con los valores del artista
                                                        echo "<option value='" . $value->getId_artista() . "'>";
                                                        echo $value->getNombre() . ' ';
                                                        echo $value->getApellidos() . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="button" id='añadirDirector' value="Añadir director" class="separacion-izquierda"/>
                                        </form>
                                    </li>

                                    <li>
                                        <table class="tabla">
                                            <tr>
                                                <td>Nombre</td>
                                                <td>Apellidos</td>
                                                <td></td>
                                            </tr>

                                            <?php
// Recorremos el array de directores
                                            foreach ($directores as $director) {

                                                // Para cada iteración creamos una fila de la tabla
                                                echo '<tr>';

                                                // Además creamos una celda que contenga el nombre del artista
                                                echo '<td>';
                                                echo $director->getNombre();
                                                echo '</td>';

                                                // Creamos otra celda para los apellidos del artista
                                                echo '<td>';
                                                echo $director->getApellidos();
                                                echo '</td>';

                                                echo '<td>';
                                                echo "<form action='index.php' method='post'>";
                                                echo "<input type='button' class='boton-pequeño align-right' id='eliminarDirector" . $director->getId_artista() . "' name='Eliminar' value='Eliminar'/>";

                                                // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                                echo "<input type='hidden' id='id' name='eliminarDirector' value='" . $director->getId_artista() . "' />";
                                                echo "</form>";
                                                echo '</td>';

                                                // Cerramos la fila de la iteración
                                                echo '</tr>';
                                            }
                                            ?>
                                        </table>
                                    </li>
                                    <li>
                                        <form action="index.php" id='formAñadirGuionista' method="post">
                                            <select class="field-style field-split align-left" name="añadirGuionista">
                                                <?php
// Recuperamos el listado de todos los artistas
                                                $resultado = corregirArrayStdClass($cliente->listaArtistas());

// Iteramos por el array de objetos Artista
                                                foreach ($resultado as $value) {

                                                    // Comprobamos que el objeto Artista de la iteración no se 
                                                    // encuentre dentro del array de guionistas añadidos
                                                    if (!in_array($value, $guionistas, FALSE)) {

                                                        // Si es así, añadimos un elemento option con los valores del artista
                                                        echo "<option value='" . $value->getId_artista() . "'>";
                                                        echo $value->getNombre() . ' ';
                                                        echo $value->getApellidos() . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="button" id='añadirGuionista' value="Añadir guionista" class="separacion-izquierda"/>
                                        </form>
                                    </li>

                                    <li>
                                        <table class="tabla">
                                            <tr>
                                                <td>Nombre</td>
                                                <td>Apellidos</td>
                                                <td></td>
                                            </tr>

                                            <?php
// Recorremos el array de directores
                                            foreach ($guionistas as $guionista) {

                                                // Para cada iteración creamos una fila de la tabla
                                                echo '<tr>';

                                                // Además creamos una celda que contenga el nombre del artista
                                                echo '<td>';
                                                echo $guionista->getNombre();
                                                echo '</td>';

                                                // Creamos otra celda para los apellidos del artista
                                                echo '<td>';
                                                echo $guionista->getApellidos();
                                                echo '</td>';

                                                echo '<td>';
                                                echo "<form action='index.php' method='post'>";
                                                echo "<input type='button' class='boton-pequeño align-right' id='eliminarGuionista" . $guionista->getId_artista() . "' name='Eliminar' value='Eliminar'/>";

                                                // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                                echo "<input type='hidden' id='id' name='eliminarGuionista' value='" . $guionista->getId_artista() . "' />";
                                                echo "</form>";
                                                echo '</td>';

                                                // Cerramos la fila de la iteración
                                                echo '</tr>';
                                            }
                                            ?>
                                        </table>
                                    </li>
                                    <li>
                                        <input type="submit" value="<?php echo $textoBoton ?>" name="añadirPelicula" form="formDatosBasicos" class="align-right"/>
                                    </li>
                                </ul>
                            </div>

                            <div class="form-style-9">
                                <ul>
                                    <li>
                                        <table class="tabla">
                                            <tr>
                                                <td>Nombre</td>
                                                <td>Año</td>
                                                <td>Genero</td>
                                                <td>Duración</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
// Recuperamos el listado de todas las peliculas


                                            $array = corregirArrayStdClass($cliente->listaPeliculas());

// Recorremos el array de peliculas
                                            foreach ($array as $valor) {

                                                // Para cada iteración creamos una fila de la tabla
                                                echo '<tr>';

                                                // Además creamos una celda que contenga el nombre de la pelicula
                                                echo '<td>';
                                                echo $valor->getNombre();
                                                echo '</td>';

                                                // Creamos otra celda para el año
                                                echo '<td>';
                                                echo $valor->getAnyo();
                                                echo '</td>';

                                                // Creamos otra celda para el tipo de genero
                                                echo '<td>';
                                                echo $valor->getGenero()->getTipo();
                                                echo '</td>';

                                                // Creamos otra celda para la duracion
                                                echo '<td>';
                                                echo $valor->getDuracion();
                                                echo '</td>';

                                                // Creamos una celda más para que contenga el formulario con el botón de modificar artista
                                                echo '<td>';
                                                echo "<form action='index.php' method='post'>";
                                                echo "<input type='submit' name='modificarPelicula' value='Modificar'/>";

                                                // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                                echo "<input type='hidden' id='id' name='id' value='" . $valor->getId_pelicula() . "' />";
                                                echo "</form>";
                                                echo '</td>';

                                                // Creamos una celda más para que contenga el formulario con el botón de eliminar artista
                                                echo '<td>';
                                                echo "<form action='index.php' method='post'>";
                                                echo "<input type='submit' name='eliminarPelicula' value='Eliminar'/>";

                                                // Incluimos un campo oculto para que almacene la id del artista en la fila y se envíe al hacer submit
                                                echo "<input type='hidden' id='id' name='id' value='" . $valor->getId_pelicula() . "' />";
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

                </body>
                </html>