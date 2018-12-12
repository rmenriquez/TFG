<?php

class Usuario{

    private $id_usuario;
    private $nombre;
    private $usuario;
    private $password;
    private $n_cli_bodas;
    private $n_cli_bautizos;
    private $n_cli_comuniones;
    private $n_cli_otros;

    public function __construct($id_usuario=NULL, $nombre=NULL,$usuario=NULL, $password=NULL, $n_cli_bodas=NULL, $n_cli_bautizos=NULL, $n_cli_comuniones=NULL, $n_cli_otros=NULL){
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->n_cli_bodas = $n_cli_bodas;
        $this->n_cli_bautizos = $n_cli_bautizos;
        $this->n_cli_comuniones = $n_cli_comuniones;
        $this->n_cli_otros = $n_cli_otros;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param int $id
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $title
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param string $content
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param date $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Gets the author of this note
     *
     * @return User The author of this note
     */
    public function getNCliBodas() {
        return $this->n_cli_bodas;
    }


    public function setNCliBodas($n_cli_bodas) {
        $this->n_cli_bodas = $n_cli_bodas;
    }

    public function getNCliBautizos() {
        return $this->n_cli_bautizos;
    }


    public function setNCliBautizos($n_cli_bautizos) {
        $this->n_cli_bautizos = $n_cli_bautizos;
    }

    public function getNCliComuniones() {
        return $this->n_cli_comuniones;
    }


    public function setNCliComuniones($n_cli_comuniones) {
        $this->n_cli_comuniones = $n_cli_comuniones;
    }

    public function getNCliOtros() {
        return $this->n_cli_otros;
    }

    public function setNCliOtros($n_cli_otros) {
        $this->n_cli_otros = $n_cli_otros;
    }


    /**
     * Checks if the current user instance is valid
     * for being registered in the database
     *
     * @throws ValidationException if the instance is
     * not valid
     *
     * @return void
     */
    /*
    public function checkIsValidForRegister() {
        $errors = array();
        if (strlen($this->nombre) < 5) {
            $errors["nombre"] = "Name must be at least 5 characters length";

        }
        if (strlen($this->usuario) < 5) {
            $errors["usuario"] = "User must be at least 5 characters length";
        }
        if (strlen($this->password) < 5) {
            $errors["password"] = "Password must be at least 5 characters length";
        }
        if (!preg_match('/\d/', $this->n_cli_bodas)) {
            $errors["n_cli_bodas"] = "Number of wedding's waiters must be at least 0";
        }if (!preg_match('/\d/',$this->n_cli_bautizos)) {
            $errors["n_cli_bautizos"] = "Number of christening's waiters must be at least 0";
        }if (!preg_match('/\d/',$this->n_cli_comuniones)) {
            $errors["n_cli_comuniones"] = "Number of communion's waiters must be at least 0";
        }if (!preg_match('/\d/',$this->n_cli_otros)) {
            $errors["n_cli_otros"] = "Number of other's waiters must be at least 0";
        }
        if (sizeof($errors)>0){
            throw new ValidationException($errors, "user is not valid");
        }
    }
    */

}

