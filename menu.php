<link rel="stylesheet" type="text/css" href="styles/menu.css" />
<nav id="menu">
    <ul>
        <li><a href="index.php"><span>Películas</span></a></li>
        <li><a href="artistas.php"><span>Artistas</span></a></li>                    
    </ul>
    <ul>
        <form action='logoff.php' method='post'>
            <input type='submit' name='desconectar' value='Desconectar usuario <?php echo $_SESSION['usuario']; ?>'/>
        </form>
    </ul>

</nav>