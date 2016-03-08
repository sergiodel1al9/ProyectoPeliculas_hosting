<?php
/**
 * Función que nos permite convertir en arrays objetos devueltos de forma erronea
 * @param Object $valor El objeto a convertir en array
 * @return array El array con el objeto en el
 */
function corregirArrayStdClass($valor) {
    if (is_array($valor)) {
        return $valor;
    } else {
        $salida = array();
        $salida[] = $valor;

        return $salida;
    }
}
