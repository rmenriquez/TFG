<?php
/**
 * Created by PhpStorm.
 * User: RaquelMarcos
 * Date: 12/12/2018
 * Time: 12:50
 */

require_once(__DIR__."/../PDOConnection.php");

class ComidaMapper{
    private $db;

    public function __construct()
    {
        $this->db = PDOConnection::getInstance();
    }

    public function guardar(Comida $comida){
        $stmt = $this->db->prepare("INSERT INTO comida (id_comida, titulo, descripcion, imagen, restaurante) VALUES (0,? ,? ,? ,?)");
        $stmt->execute(array($comida->getTitulo(), $comida->getDescripcion(), $comida->getImagen(), $comida->getRestaurante()));
    }

    public function buscarTodas(Comida $comida){
        $stmt = $this->db->prepare("SELECT c.id_comida, titulo, descripcion, imagen, a.nombre_alergeno
                                                FROM comida_alergeno AS ca
                                                INNER JOIN comida AS c ON ca.id_comida = c.id_comida
                                                INNER JOIN usuario AS u ON u.id_usuario = c.restaurante
                                                AND c.restaurante = ?
                                                INNER JOIN alergeno AS a ON a.id_alergeno = ca.id_alergeno");
        $stmt->execute(array($comida->setRestaurante()));
    }

    public function actualizar(Comida $comida){
        $stmt = $this->db->prepare("UPDATE comida set titulo = ?, descripcion = ?, imagen = ?, restaurante ?");
        $stmt->execute(array($comida->getTitulo(), $comida->getDescripcion(), $comida->getImagen(), $comida->getRestaurante()));
    }


}