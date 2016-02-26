<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuario
 *
 * @author alumno
 */
class Usuario {
    
    private $nombre = "DWES"; //const
    private $pass = "abc123"; //const
    private $usuario;
    private $email;
    private $fnac;
    private $edad;
    public static $num_Usuarios;

    public function getnombre() {return $this->nombre; }
    public function getpass() {return $this->pass; }
    public function getusuario() {return $this->usuario; }
    public function getemail() {return $this->email; }
    public function getfnac() {return $this->fnac; }
    public function getedad() {return $this->edad; }
    
    public function __construct() {
        $this->nombre = $nombre;
        $this->pass = $pass;
        $this->usuario = $usuario;
        $this->email = $email;
        $this->fnac = $fnac;
        $this->edad = $edad;
    }
    
    public function validar($nom, $pass){
        require_once "claseBD.php";
        $conexion = claseBD::connectPDO($dns,$username,$password,$options);
        
        $values = ["*"=>""];
        $conditions = ["nombre"=>$nom, "contrasena"=>md5($pass)];
        $sql = createSelect("usuarios", $values, $conditions);
        $resultado = execSelect($con, $sql, $conditions);
        
        return $resultado;
        
    }
    
    public function insertarBD($nom, $pass, $usuario, $email, $fnac, $edad){

        require_once "claseBD.php";
        $conexion = claseBD::connectPDO($dns,$username,$password,$options);
        
        $values = ["nombre"=>$nom, "contrasena"=>md5($pass), "usuario"=>$usuario, "email"=>$email, "fnac"=>$fnac, "edad"=>$edad];
        $sql = createInsert("usuarios", $values);
        $count  = execInsert($con, $sql, $values);
        return $count;
        
    }
}
