<?php

class Localidad{
    private $id;
    private $nombre;
    private $coordenadas;
    private $partido_id;

    public function __construct($id,$nombre,$coordenadas,$partido_id){
        $this -> id =$id;
        $this -> nombre=$nombre;
        $this -> coordenadas=$coordenadas;
        $this -> partido_id=$partido_id;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of partido_id
     */ 
    public function getPartido_id()
    {
        return $this->partido_id;
    }

    /**
     * Set the value of partido_id
     *
     * @return  self
     */ 
    public function setPartido_id($partido_id)
    {
        $this->partido_id = $partido_id;

        return $this;
    }

    /**
     * Get the value of coordenadas
     */ 
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * Set the value of coordenadas
     *
     * @return  self
     */ 
    public function setCoordenadas($coordenadas)
    {
        $this->coordenadas = $coordenadas;

        return $this;
    }
}