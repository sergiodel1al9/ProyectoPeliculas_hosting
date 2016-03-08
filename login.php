<?php
    $mensaje = '';
require_once './include/DB.php';
$cliente = new DB();

// Comprobamos si ya se ha enviado el formulario
if (isset($_POST['enviar'])) {

    if (empty($_POST['usuario']) || empty($_POST['password'])) {

        $mensaje = "Debes introducir un nombre de usuario y una contraseña";
    } else {

        // Comprobamos las credenciales con la base de datos
        if ($cliente->verificaCliente($_POST['usuario'], $_POST['password'])) {

            session_start();
            $_SESSION['usuario'] = $_POST['usuario'];
            header("Location: index.php");
        } else {

            // Si las credenciales no son válidas, se vuelven a pedir
            $mensaje = "Usuario o contraseña no válidos!";
        }
    }
}
?>
<!DOCTYPE>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Películas</title>
        <link href="styles/login.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div id='login'>
            <form action='login.php' method='post'>
                <fieldset >
                    <legend>Login</legend>
                    <div><span class='error'><?php echo $mensaje; ?></span></div>
                    <div class='campo'>
                        <label for='usuario' >Usuario:</label><br/>
                        <input type='text' name='usuario' id='usuario' maxlength="50" /><br/>
                    </div>
                    <div class='campo'>
                        <label for='password' >Contraseña:</label><br/>
                        <input type='password' name='password' id='password' maxlength="50" /><br/>
                    </div>

                    <div class='campo'>
                        <input type='submit' name='enviar' value='Enviar' />
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>