<?php
namespace App\Model;
class Institucion{
    private $id;
    private $nombre;
    private $director;
    private $direccion;
    private $telefono;
    private $localidad_id;
    private $tipo_institucion_id;

    public function __construct($id,$nombre,$director,$direccion,$telefono,$localidad_id,$tipo_institucion_id){
       $this->id=$id;
       $this->nombre=$nombre;
       $this->director=$director;
       $this->direccion=$direccion;
       $this->telefono=$telefono;
       $this->localidad_id=$localidad_id;
       $this->tipo_institucion_id=$tipo_institucion_id;
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
     * Get the value of director
     */ 
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set the value of director
     *
     * @return  self
     */ 
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get the value of direccion
     */ 
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */ 
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of telefono
     */ 
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */ 
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of localidad_id
     */ 
    public function getLocalidad_id()
    {
        return $this->localidad_id;
    }

    /**
     * Set the value of localidad_id
     *
     * @return  self
     */ 
    public function setLocalidad_id($localidad_id)
    {
        $this->localidad_id = $localidad_id;

        return $this;
    }

    /**
     * Get the value of tipo_institucion_id
     */ 
    public function getTipo_institucion_id()
    {
        return $this->tipo_institucion_id;
    }

    /**
     * Set the value of tipo_institucion_id
     *
     * @return  self
     */ 
    public function setTipo_institucion_id($tipo_institucion_id)
    {
        $this->tipo_institucion_id = $tipo_institucion_id;

        return $this;
    }
}