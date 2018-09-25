<?php

class Partido{
    private $id;
    private $nombre;
    private $region_sanitaria_id;

    public function __construct($id,$nombre,$region_sanitaria_id){
        $this -> id =$id;
        $this -> nombre=$nombre;
        $this -> region_sanitaria_id=$region_sanitaria_id;
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
     * Get the value of region_sanitaria_id
     */ 
    public function getRegion_sanitaria_id()
    {
        return $this->region_sanitaria_id;
    }

    /**
     * Set the value of region_sanitaria_id
     *
     * @return  self
     */ 
    public function setRegion_sanitaria_id($region_sanitaria_id)
    {
        $this->region_sanitaria_id = $region_sanitaria_id;

        return $this;
    }
}