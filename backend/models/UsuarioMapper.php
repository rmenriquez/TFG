<?php

require_once(__DIR__."/../PDOConnection.php");

class UsuarioMapper{

    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    public function guardar($usuario){
        $stmt = $this->db->prepare("INSERT INTO usuario (id_usuario, nombre, usuario, password, n_cli_bodas, 
            n_cli_bautizos, n_cli_comuniones, n_cli_otros) VALUES  (0,?,?,?,?,?,?,?)");
        $stmt->execute(array($usuario->getNombre(), $usuario->getUsuario(), $usuario->getPassword(), $usuario->getNCliBodas(),
            $usuario->getNCliBautizos(), $usuario->getNCliComuniones(), $usuario->getNCliOtros()));
    }

    public function usuarioExiste($usuario) {
        $stmt = $this->db->prepare("SELECT count(usuario) FROM user where usuario=?");
        $stmt->execute(array($usuario));

        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }

    public function esUsuarioValido($usuario, $password) {
        $stmt = $this->db->prepare("SELECT count(usuario) FROM user where usuario=? and password=?");
        $stmt->execute(array($usuario, $password));
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
    }
}

