<?php

require_once('Genero.php');
require_once('Artista.php');
require_once('Pelicula.php');
require_once('funciones.php');

class DB {

    /**
     * Objeto que almacenará la base de datos PDO
     * @var type PDO Object
     */
    private $dwes;

    /**
     * Constructor de la clase DB
     * @throws Exception Si hay un error se lanza una excepción
     */
    public function __construct() {
        try {
            
         /*
            $serv = "localhost";
            $base = "peliculas";
            $usu = "root";
            $pas = "";
         */
            
            $serv = "mysql.hostinger.es";
            $base = "u869509894_pelic";
            $usu = "u869509894_dwes";
            $pas = "D7gJxvP9A7SHxWlTK2";            
           
            
            // Creamos un array de configuración para la conexion PDO a la base de 
            // datos
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            // Creamos la cadena de conexión con la base de datos
            $dsn = "mysql:host=$serv;dbname=$base";
            // Finalmente creamos el objeto PDO para la base de datos
            $this->dwes = new PDO($dsn, $usu, $pas, $opc);
            $this->dwes->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Método que nos permite realizar consultas a la base de datos
     * @param string $sql Sentencia sql a ejecutar
     * @return array Resultado de la consulta
     * @throws Exception Lanzamos una excepción si se produce un error
     */
    private function ejecutaConsulta($sql) {
        try {
            // Comprobamos si el objeto se ha creado correctamente
            if (isset($this->dwes)) {
                // De ser así, realizamos la consulta
                $resultado = $this->dwes->query($sql);
                // Devolvemos el resultado
                return $resultado;
            }
        } catch (Exception $ex) {
            // Si se produce un error, lanzamos una excepción
            throw $ex;
        }
    }

    /**
     * Función que permite ejecutar una consulta transaccionalmente
     * @param string $sql La cadena sql a ejecutar
     * @param array $datos Los parámetros de la consulta
     * @return array El resultado de la consulta
     * @throws Exception Si hay un error se lanza una excepción
     */
    private function ejecutaConsultaTransaccion($sql, array $datos) {

        try {
            // Preaparamos una sentencia para la insercción del 
            // fichero en la tabla documentos            
            $stmt = $this->dwes->prepare($sql);

            // Creamos un contador para ir asignando valores a la sentencia
            $cont = 1;

            // Iteramos por el array
            foreach ($datos as $key) {

                // Verificamos si el valor es un recurso y si este recurso 
                // es de tipo stream, el cual habra que pasarlo como un campo 
                // BLOB. Despues vamos asignando los valores del array a cada 
                // posición de la sentencia. 
                if (gettype($key) === "resource" && get_resource_type($key) === "stream") {

                    // Asignamos el valor del fichero, especificando 
                    // que se trata de un fichero tipo BLOB, para que 
                    // modifique la información guardada en formato 
                    // stream en la base de datos adaptandolo en el 
                    // proceso
                    $stmt->bindValue($cont, $key, PDO::PARAM_LOB);
                } else {

                    // Si no es un recurso el valor, lo asignamos sin parámetros
                    $stmt->bindValue($cont, $key);
                }

                // Aumentamos el contador
                $cont++;
            }

            // Devolvemos el resultado
            return $stmt->execute();
        } catch (Exception $ex) {
            // Si se produce una excepción la lanzamos para que se ocupe de ella 
            // la función que haya invocado a esta
            throw $ex;
        }
    }

    /**
     * Funcion para añadir generos en la base de datos
     * @param string $nombreCategoria Tipo de genero a introducir en la base de datos
     * @return string devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaGeneros($nombreCategoria) {
        $sql = "INSERT INTO genero";
        $sql .= " VALUES (0, '$nombreCategoria')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para añadir artistas en la base de datos
     * @param string $nombreArtista Nombre del artista a introducir en la tabla artista
     * @param string $apellidosArtista Apellidos del artista a introducir en la tabla artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaArtistas($nombreArtista, $apellidosArtista) {
        $sql = "INSERT INTO artista";
        $sql .= " VALUES (0, '$nombreArtista', '$apellidosArtista')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para añadir peliculas en la base de datos
     * @param string $nombrePelicula Nombre de la pelicula a introducir en la tabla pelicula
     * @param string $anyo Año en el que se estrenó la pelicula a introducir en la tabla pelicula
     * @param string $idGenero Id de genero de la película referente a la tabla genero
     * @param string $sinopsis Sinopsis de la pelicula a introducir en la tabla pelicula
     * @param string $duracion Duracion de la pelicula a introducir en la tabla pelicula
     * @return string devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaPeliculas($nombrePelicula, $anyo, $idGenero, $sinopsis, $duracion) {
        $sql = "INSERT INTO pelicula";
        $sql .= " VALUES (0, '$nombrePelicula', '$anyo', '$idGenero', '$sinopsis', '$duracion' )";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para relacionar los actores con las peliculas en la base de datos
     * @param string $idPelicula Id de la pelicula de la tabla pelicula
     * @param string $idArtista Id del artista de la de la tabla artista
     * @return string devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaPeliculaActor($idPelicula, $idArtista) {
        $sql = "INSERT INTO pelicula_actor";
        $sql .= " VALUES (0, '$idPelicula', '$idArtista')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para relacionar los directores con las peliculas en la base de datos
     * @param string $idPelicula Id de la pelicula de la tabla pelicula
     * @param string $idArtista Id del artista de la de la tabla artista
     * @return string devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaPeliculaDirector($idPelicula, $idArtista) {
        $sql = "INSERT INTO pelicula_director";
        $sql .= " VALUES (0, '$idPelicula', '$idArtista')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para relacionar los guionistas con las peliculas en la base de datos
     * @param string $idPelicula Id de la pelicula referenciando la tabla pelicula
     * @param string $idArtista Id del artista referenciando la tabla artista
     * @return string devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function altaPeliculaGuionista($idPelicula, $idArtista) {
        $sql = "INSERT INTO pelicula_guionista";
        $sql .= " VALUES (0, '$idPelicula', '$idArtista')";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para eliminar registros de la tabla pelicula_actor
     * @param string $idPelicula El id de la pelicula
     * @param string $idArtista El id del artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function bajaPeliculaActor($idPelicula, $idArtista) {
        $sql = "DELETE FROM pelicula_actor";
        $sql .= " WHERE id_pelicula=$idPelicula and id_artista=$idArtista";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para eliminar registros de la tabla pelicula_director
     * @param string $idPelicula El id de la pelicula
     * @param string $idArtista El id del artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function bajaPeliculaDirector($idPelicula, $idArtista) {
        $sql = "DELETE FROM pelicula_director";
        $sql .= " WHERE id_pelicula=$idPelicula and id_artista=$idArtista";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para eliminar registros de la tabla pelicula_guionista
     * @param string $idPelicula El id de la pelicula
     * @param string $idArtista El id del artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function bajaPeliculaGuionista($idPelicula, $idArtista) {
        $sql = "DELETE FROM pelicula_guionista";
        $sql .= " WHERE id_pelicula=$idPelicula and id_artista=$idArtista";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Funcion para eliminar un registro de la rabla pelicula
     * @param string $idPelicula El id de la película
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function bajaPelicula($idPelicula) {
        $sql = "DELETE FROM pelicula";
        $sql .= " WHERE id_pelicula=$idPelicula";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Función para eliminar un registro de la tabla artista
     * @param string $idArtista El id del artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function bajaArtista($idArtista) {
        $sql = "DELETE FROM artista";
        $sql .= " WHERE id_artista=$idArtista";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Función para modificar un artista
     * @param string $idArtista El id del artista
     * @param string $nombre El nombre del artista
     * @param string $apellidos Los apellidos del artista
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function modificarArtista($idArtista, $nombre, $apellidos) {
        $sql = "UPDATE artista SET nombre='$nombre', apellidos='$apellidos'";
        $sql .= " WHERE id_artista=$idArtista";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /**
     * Función que nos permite modificar una pelicula
     * @param string $idPelicula El id de la pelicula
     * @param string $nombre El nombre de la pelicula
     * @param string $anyo El año de la pelicula
     * @param string $sinopsis La sinopsis de la pelicula
     * @param string $duracion La duración de la pelicula
     * @return int devuelve 0 si va todo correctamente o por lo contrario un mensaje de error
     */
    public function modificarPelicula($idPelicula, $nombre, $anyo, $sinopsis, $duracion) {
        $sql = "UPDATE pelicula SET nombre='$nombre', anyo='$anyo', sinopsis='$sinopsis', duracion='$duracion'";
        $sql .= " WHERE id_pelicula=$idPelicula";
        $resultado = self::ejecutaConsulta($sql);
        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            return $this->dwes->errorInfo()[2];
        }
    }

    /*
     * Función que nos permite modificar el contenido de una pelicula dandola de baja para posteriormente darla de alta
     * @param Pelicula $pelicula La información de la película a modificar en un objeto Pelicula
     * @return int Devuelve 0 si va todo correctamente, -1 si se produce una excepción, 
     * -2 si no se puede eliminar la película antes de insertarla con los datos nuevos, 
     * -3 si se produce un error al insertar la película
     */

    public function modificarPeliculaEx(Pelicula $pelicula) {
        try {
            // Iniciamos una transacción
            $this->dwes->beginTransaction();

            // Damos de baja la película y comprobamos si se ha borrado 
            // correctamente de la base de datos
            if ($this->bajaPelicula($pelicula->getId_pelicula()) === 0) {

                // Añadimos la película                
                $resultado = $this->altaPelicula($pelicula);

                // Comprobamos que se ha dado de alta correctamente comprobando 
                // el id que ha devuelto la función y comprobando que es mayor que 0
                if ($resultado > 0) {
                    // Si todo es correcto, devolvemos el resultado del alta de la película, 
                    // que corresponde al nuevo id de la misma
                    return $resultado;
                } else {
                    // Devolvemos -3 como código de error. En este caso no se 
                    // hace rollback de la transacción pq se encarga de ello 
                    // la función altaPelicula.
                    return -3;
                }
            } else {
                // Si no se puede eliminar la película, hacemos rollback de la transacción
                $this->dwes->rollBack();

                // Devolvemos -2 como código de error
                return -2;
            }
        } catch (Exception $ex) {

            // Si se produce una excepción, hacemos rollback de la transacción
            $this->dwes->rollBack();

            // Devolvemos -1 como código de error
            return -1;
        }
    }

    /**
     * Funcion para mostrar todos los registros de la tabla genero
     * @return array Un array de objetos Genero
     */
    public function listaGeneros() {
        $sql = "SELECT * FROM genero;";
        $resultado = self::ejecutaConsulta($sql);
        $generos = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $generos[] = new Genero($row);
                $row = $resultado->fetch();
            }
        }

        return $generos;
    }

    /**
     * Función que nos permite recuperar la información de un género
     * @param string $id_genero El id del genero que queremos recuperar
     * @return Genero El objeto genero recuperado con el id correspondiente
     */
    public function listaGenero($id_genero) {
        $sql = "SELECT * FROM genero WHERE id_genero =" . $id_genero . ";";
        $resultado = self::ejecutaConsulta($sql);

        if ($resultado) {
            $row = $resultado->fetch();
        }

        return new Genero($row);
    }

    /**
     * Funcion para listar todos los artistas de la tabla artista
     * @return array Un array de objetos Artista
     */
    public function listaArtistas() {
        $sql = "SELECT * FROM artista;";
        $resultado = self::ejecutaConsulta($sql);
        $artistas = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {
                $artistas[] = new Artista($row);
                $row = $resultado->fetch();
            }
        }

        return $artistas;
    }

    /**
     * Función que nos permite recuperar un artista relacionado con una película dependiendo de su puesto en la misma
     * @param string $id_pelicula El id de la pelicula
     * @param string $puesto El puesto que realiza el artista en la pelicula: 0 actor, 1 director, 2 guionista
     * @return \Artista Un array de objetos Artista con la información requerida
     */
    public function listaArtistasPelicula($id_pelicula, $puesto) {
        
        // Seleccionamos una consulta u otra dependiendo del puesto del cual queremos recuperar los artistas
        switch ($puesto) {
            case 0: {
                    $sql = "SELECT id_artista FROM pelicula_actor WHERE id_pelicula=" . $id_pelicula . ";";
                    break;
                }
            case 1: {
                    $sql = "SELECT id_artista FROM pelicula_director WHERE id_pelicula=" . $id_pelicula . ";";
                    break;
                }
            case 2: {
                    $sql = "SELECT id_artista FROM pelicula_guionista WHERE id_pelicula=" . $id_pelicula . ";";
                    break;
                }
            default: {
                    break;
                }
        }

        // Realizamos la cunsulta para traer los ids de los artistas
        $resultado = self::ejecutaConsulta($sql);

        // Creamos un array para almacenar el resultado
        $artistas = array();

        // Comprobamos si hemos obtenido un resultado
        if ($resultado) {

            // Recuperamos la primera fila
            $row = $resultado->fetch();

            // He iteramos mientras el valor de la fila recuperada no sea nulo
            while ($row != null) {

                // Usamos el id del artista de la iteración para recuperar la información del artista
                $artista = $this->listaArtista($row['id_artista']);

                // Con esa información creamos un nuevo objeto artista y lo añadimos al array de resultados
                $artistas[] = $artista;

                // Recuperamos la siguiente fila del resultado de la base de datos
                $row = $resultado->fetch();
            }
        }

        // Devolvemos el array de artistas
        return $artistas;
    }

    /**
     * Función para listar un artista basado en su id
     * @param string $id_artista El id del artista a seleccionar
     * @return Artista El artista seleccionado
     */
    public function listaArtista($id_artista) {
        $sql = "SELECT * FROM artista WHERE id_artista =" . $id_artista . ";";
        $resultado = self::ejecutaConsulta($sql);

        if ($resultado) {
            $row = $resultado->fetch();
        }

        return new Artista($row);
    }

    /**
     * Función que nos permite recupera la información de una pelicula basada en su id
     * @param string $id_pelicula El id de la pelicula a recuperar
     * @return Pelicula El objeto pelicula con la información de la pelicula
     */
    public function listaPelicula($id_pelicula) {
        $sql = "SELECT * FROM pelicula WHERE id_pelicula =" . $id_pelicula . ";";
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos si hay resultado
        if ($resultado) {

            // Si lo hay, recuperamos el primer valor del resultado y lo almacenamos
            $row = $resultado->fetch();

            // Comprobamos si el valor almacenado es correcto
            if ($row) {

                // Si lo es, recuperamos el género a partir del su id
                $genero = $this->listaGenero($row['id_genero']);

                // Comprobamos si el genero recuperado es correcto
                if ($genero) {
                    // Si lo es, eliminamos el elemento id_genero del array
                    unset($row[3]);

                    // Y le añadimos el genero recuperado
                    $row['genero'] = $genero;

                    // Creamos un array y recuperamos los actores relacionados con la pelicula
                    $array = array();

                    // Recuperamos el array de actores relacionados con la película
                    $array = $this->listaArtistasPelicula($id_pelicula, '0');

                    $row['actores'] = $array;

                    // Recuperamos el array de directores relacionados con la película
                    $array = $this->listaArtistasPelicula($id_pelicula, '1');

                    $row['directores'] = $array;

                    // Recuperamos el array de guionistas relacionados con la película
                    $array = $this->listaArtistasPelicula($id_pelicula, '2');

                    $row['guionistas'] = $array;
                } else {
                    // Si no es correcto devolvemos null
                    return NULL;
                }
            } else {
                // Si no es correcto devolvemos null
                return NULL;
            }
        }
       
        return new Pelicula($row['id_pelicula'], $row['nombre'], $row['anyo'], $row['genero'], $row['sinopsis'], $row['duracion'], $row['directores'], $row['guionistas'], $row['actores']);
    }

    /**
     * Función que nos permite validar el usuario y contraseña de un usuario
     * @param string $nombre El nombre de usuario
     * @param string $contrasena La contraseña asociada al usuario
     * @return boolean True si el usuario y la contraseña son correctos
     */
    public function verificaCliente($nombre, $contrasena) {
        $sql = "SELECT usuario FROM usuarios ";
        $sql .= "WHERE usuario='$nombre' ";
        $sql .= "AND contrasena='" . md5($contrasena) . "';";
        $resultado = self::ejecutaConsulta($sql);
        $verificado = false;

        if (isset($resultado)) {
            $fila = $resultado->fetch();
            if ($fila !== false) {
                $verificado = true;
            }
        }
        return $verificado;
    }

    /**
     * Función que nos permite dar de alta una película
     * @param Pelicula $pelicula El objeto Película con toda la información sobre la misma
     * @return int Devuelve 0 si va todo correctamente, -1 si se produce una excepción, 
     * -2 si no se puede eliminar la película antes de insertarla con los datos nuevos, 
     * -3 si se produce un error al insertar la película
     */
    public function altaPelicula(Pelicula $pelicula) {

        try {

            // Comprobamos si no estamos en una transacción, para evitar 
            // intentar una transacción dos veces si la función se invoca 
            // desde modificarPeliculaEx
            if (!$this->dwes->inTransaction()) {
                // Si no es así, iniciamos una transacción
                $this->dwes->beginTransaction();
            }

            // Creamos la consulta sql que usaremos para introducir los valores
            $sql = "INSERT INTO pelicula VALUES (?, ?, ?, ?, ?, ?)";

            // Creamos un array con los datos que pasaremos a cada una de los simbolos de interrogación en orden 
            $datos = ['id_pelicula' => 0, 'nombre' => $pelicula->getNombre(), 'anyo' => $pelicula->getAnyo(), 'id_genero' => $pelicula->getGenero()->getId_genero(), 'sinopsis' => $pelicula->getSinopsis(), 'duracion' => $pelicula->getDuracion()];

            // Ejecutamos la consulta de forma transaccional y almacenamos el resultado
            $resultado = $this->ejecutaConsultaTransaccion($sql, $datos);

            // Comprobamos si el resultado es correcto, para continuar haciendo operaciones
            if ($resultado) {

                // Recuperamos el último id insertado en la base de datos, que se corresponde con el id de la película que acabamos de almacenar
                $id_pelicula = $this->dwes->lastInsertId();

                // Recuperamos el array de actores
                $actores = corregirArrayStdClass($pelicula->getActores());                

                
                
                
                // Iteramos por el array de actores
                for ($index = 0; $index < count($actores); $index++) {

                    // Creamos la cadena de insercción de actores y el array de datos a pasar a la función de ejecutar transacciones
                    $sql = "INSERT INTO pelicula_actor VALUES (?, ?, ?)";
                    $datos = ['id_pelicula_actor' => 0, 'id_pelicula' => $id_pelicula, 'id_artista' => $actores[$index]->getId_artista()];

                    // Realizamos la inserccio´n de forma transaccional y almacenamos el resultado en una variable
                    $insercion = $this->ejecutaConsultaTransaccion($sql, $datos);

                    // Comprobamos que la insercción se haya realizado correctamente
                    if (!$insercion) {
                        // Si la inserccion no es correcta, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos                        
                        $this->dwes->rollBack();
                        // Devolvemos un mensaje de error
                        return -3;
                    }
                }

                $directores = corregirArrayStdClass($pelicula->getDirectores());

                for ($index = 0; $index < count($directores); $index++) {

                    // Creamos la cadena de insercción de directores y el array de datos a pasar a la función de ejecutar transacciones
                    $sql = "INSERT INTO pelicula_director VALUES (?, ?, ?)";
                    $datos = ['id_pelicula_director' => 0, 'id_pelicula' => $id_pelicula, 'id_artista' => $directores[$index]->getId_artista()];

                    // Realizamos la inserccio´n de forma transaccional y almacenamos el resultado en una variable
                    $insercion = $this->ejecutaConsultaTransaccion($sql, $datos);

                    // Comprobamos que la insercción se haya realizado correctamente
                    if (!$insercion) {
                        // Si la inserccion no es correcta, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
                        $this->dwes->rollBack();

                        // Devolvemos un mensaje de error
                        return -4;
                    }
                }

                $guionistas = corregirArrayStdClass($pelicula->getGuionistas());

                for ($index = 0; $index < count($guionistas); $index++) {

                    // Creamos la cadena de insercción de guionistas y el array de datos a pasar a la función de ejecutar transacciones
                    $sql = "INSERT INTO pelicula_guionista VALUES (?, ?, ?)";
                    $datos = ['id_pelicula_guionista' => 0, 'id_pelicula' => $id_pelicula, 'id_artista' => $guionistas[$index]->getId_artista()];

                    // Realizamos la inserccio´n de forma transaccional y almacenamos el resultado en una variable
                    $insercion = $this->ejecutaConsultaTransaccion($sql, $datos);

                    // Comprobamos que la insercción se haya realizado correctamente
                    if (!$insercion) {
                        // Si la inserccion no es correcta, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
                        $this->dwes->rollBack();

                        // Devolvemos un dígito negativo como mensaje de error
                        return -5;
                    }
                }

                // Si no se ha producido ningún error durante las transacciones para almacenar la película y 
                // los integrantes de la misma realizamos un commit para hacer permanentes los datos 
                // almacenados en la base de datos
                $this->dwes->commit();

                // Devolvemos el id de la película almacenada en la base de datos
                return $id_pelicula;
            } else {

                // Si el resultado no es correcto, hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
                $this->dwes->rollBack();

                // Devolvemos un dígito negativo como mensaje de error
                return -2;
            }
        } catch (Exception $ex) {

            // Si se produce una excepción hacemos un rollback a las trasacciones con el fin de no modificar la base de datos
            $this->dwes->rollBack();

            // Devolvemos un dígito negativo como mensaje de error
            return -1;
        }
    }

    /**
     * Funcion para mostrar todos los registros de la tabla genero
     * @return array Un array de objetos Pelicula
     */
    public function listaPeliculas() {
        $sql = "SELECT id_pelicula FROM pelicula ORDER BY nombre;";
        $resultado = self::ejecutaConsulta($sql);
        $peliculas = array();

        if ($resultado) {
            // Añadimos un elemento por cada producto obtenido
            $row = $resultado->fetch();
            while ($row != null) {

                $peli = $this->listaPelicula($row['id_pelicula']);


                $peliculas[] = $peli;

                $row = $resultado->fetch();
            }
        }

        return $peliculas;
    }
}
