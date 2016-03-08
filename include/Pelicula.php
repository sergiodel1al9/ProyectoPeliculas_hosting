<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'Genero.php';

/**
 * Description of Pelicula
 *
 * @author Sergio
 */
class Pelicula {

    public $id_pelicula;
    private $nombre;
    private $anyo;
    private $genero;
    private $sinopsis;
    private $duracion;
    private $directores = array();
    private $guionistas = array();
    private $actores = array();

    /**
     * 
     * @param type $id_pelicula
     * @param type $nombre
     * @param type $anyo
     * @param Genero $genero
     * @param type $sinopsis
     * @param type $duracion
     * @param type $directores
     * @param type $guionistas
     * @param type $actores
     */
    function __construct($id_pelicula, $nombre, $anyo, Genero $genero, $sinopsis, $duracion, $directores, $guionistas, $actores) {
        $this->id_pelicula = $id_pelicula;
        $this->nombre = $nombre;
        $this->anyo = $anyo;
        $this->genero = $genero;
        $this->sinopsis = $sinopsis;
        $this->duracion = $duracion;
        $this->directores = $directores;
        $this->guionistas = $guionistas;
        $this->actores = $actores;
    }
    
    public function getId_pelicula() {
        return $this->id_pelicula;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getAnyo() {
        return $this->anyo;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getSinopsis() {
        return $this->sinopsis;
    }

    public function getDuracion() {
        return $this->duracion;
    }

    public function getDirectores() {
        return $this->directores;
    }

    public function getGuionistas() {
        return $this->guionistas;
    }

    public function getActores() {
        return $this->actores;
    }

    public function setId_pelicula($id_pelicula) {
        $this->id_pelicula = $id_pelicula;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setAnyo($anyo) {
        $this->anyo = $anyo;
    }

    public function setGenero($genero) {
        $this->genero = $genero;
    }

    public function setSinopsis($sinopsis) {
        $this->sinopsis = $sinopsis;
    }

    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }

    public function setDirectores($directores) {
        $this->directores = $directores;
    }

    public function setGuionistas($guionistas) {
        $this->guionistas = $guionistas;
    }

    public function setActores($actores) {
        $this->actores = $actores;
    }

}