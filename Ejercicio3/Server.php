<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Calcula
 *
 * @author alumno
 */
class Server {
    function convertirCel ($cel){
        $fah = ($cel*9/5)+32;
        $kel = $cel+273.15;
        $msj = "Grados Celsisus =".$cel."<br/> Grados Fahrengeit = ".$fah."<br/> Grados Kelvin = ".$kel;
        return $msj;
    }
    function convertirFah ($fah){
        $cel = ($fah-32)*5/9;
        $kel = ($fah+459.67)*5/9;
        $msj = "Grados Celsisus =".$cel."<br/> Grados Fahrengeit = ".$fah."<br/> Grados Kelvin = ".$kel;
        return $msj;
    }
    function convertirKel ($kel){
        $cel = $kel-273.15;
        $fah = ($kel*9/5)-459.67;
        $msj = "Grados Celsisus =".$cel."<br/> Grados Fahrengeit = ".$fah."<br/> Grados Kelvin = ".$kel;
        return $msj;
    }
}
