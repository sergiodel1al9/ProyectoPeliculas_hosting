<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Artista
 *
 * @author Sergio
 */
class Artista {

    private $id_artista;
    private $nombre;
    private $apellidos;

    function __construct($row) {
        $this->id_artista = $row['id_artista'];
        $this->nombre = $row['nombre'];
        $this->apellidos = $row['apellidos'];
    }

    public function getId_artista() {
        return $this->id_artista;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function setId_artista($id_artista) {
        $this->id_artista = $id_artista;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    public function mostrarNombreArtista()
    {
        return $this->nombre . ' ' . $this->apellidos;
        
    }
    
}
