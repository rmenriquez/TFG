<?php

include("core/PDOConnection.php");

class login{
    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    public function login(){
        $logindata=json_decode(file_get_contents("php://input"));
        if(sizeof($logindata) != 0){
            $errores = "";

            $usuario = $logindata->usuario;
            $password = $logindata->password;

            $stmt = $this->db->prepare("SELECT count(usuario) FROM usuario WHERE usuario = ? AND password = ?");
            $stmt->execute(array($usuario, $password));
            if($stmt->fetchColumn() > 0){
                return json_encode($stmt);
            }
        }
    }
}
