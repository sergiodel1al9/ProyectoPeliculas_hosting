<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Genero
 *
 * @author Sergio
 */
class Genero {

    private $id_genero;
    private $tipo;

    function __construct($row) {
        $this->id_genero = $row['id_genero'];
        $this->tipo = $row['tipo'];
    }

    public function getId_genero() {
        return $this->id_genero;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setId_genero($id_genero) {
        $this->id_genero = $id_genero;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}
