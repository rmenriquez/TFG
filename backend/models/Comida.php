<?php
class Comida{
    private $id_comida;
    private $titulo;
    private $descripcion;
    private $imagen;
    private $restaurante;

    public function __construct($id_comida=NULL, $titulo=NULL, $descripcion=NULL, $imagen=NULL, $restaurante=NULL)
    {
        $this->id_comida = $id_comida;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
        $this->restaurante = $restaurante;
    }

    /**
     * @return null
     */
    public function getIdComida()
    {
        return $this->id_comida;
    }

    /**
     * @param null $id_comida
     */
    public function setIdComida($id_comida)
    {
        $this->id_comida = $id_comida;
    }

    /**
     * @return null
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param null $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }


    /**
     * @return null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param null $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return null
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param null $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @return null
     */
    public function getRestaurante()
    {
        return $this->restaurante;
    }

    /**
     * @param null $restaurante
     */
    public function setRestaurante($restaurante)
    {
        $this->restaurante = $restaurante;
    }



}


