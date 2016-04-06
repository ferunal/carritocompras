<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexion
 *
 * @author lchacon
 */
class Conexion {

    //put your code here<?php

    var $BaseDatos;
    var $Servidor;
    var $Usuario;
    var $Clave;
    var $Conexion_ID;
    var $Consulta_ID;
    var $Errno = 0;
    var $Error = "";

    //Constructor de la clase Conexion
    function Conexion() {
        $this->BaseDatos = "bdtutorial";
        $this->Servidor = "localhost";
        $this->Usuario = "root";
        $this->Clave = "";
    }

    //Metodo para conectarnos a la base de datos
    function conectar() {
        $this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);
        if (!$this->Conexion_ID) {
            $this->Error = "Ha fallado la conexion.";
            return 0;
        }

        if (!mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {
            $this->Error = "Imposible abrir " . $this->BaseDatos;
            return 0;
        }
        return $this->Conexion_ID;
    }

}


