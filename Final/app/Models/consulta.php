<?php

namespace App\Models;

class Consulta{
    private $id;
    private $paciente_id;
    private $fecha;
    private $motivo_id;
    private $derivacion_id;
    private $articulacion_con_instituciones;
    private $internacion;
    private $diagnostico;
    private $observaciones;
    private $tratamiento_farmacologico_id;
    private $acompanamiento_id;
    private $borrado;

    public function __construct($id,$paciente_id,$fecha,$motivo_id,$derivacion_id,$articulacion_con_instituciones,
    $internacion,$diagnostico,$observaciones,$tratamiento_farmacologico_id,$acompanamiento_id,$borrado){
        $this -> id=$id;
        $this-> paciente_id=$paciente_id;
        $this -> fecha=$fecha;
        $this -> motivo_id=$motivo_id;
        $this -> derivacion_id=$derivacion_id;
        $this -> articulacion_con_instituciones=$articulacion_con_instituciones;
        $this -> internacion = $internacion;
        $this -> diagnostico=$diagnostico;
        $this -> observaciones =$observaciones;
        $this -> tratamiento_farmacologico_id=$tratamiento_farmacologico_id;
        $this -> acompanamiento_id=$acompanamiento_id;
        $this -> borrado=$borrado;
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
     * Get the value of paciente_id
     */
    public function getPaciente_id()
    {
        return $this->paciente_id;
    }

    /**
     * Set the value of paciente_id
     *
     * @return  self
     */
    public function setPaciente_id($paciente_id)
    {
        $this->paciente_id = $paciente_id;

        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     *
     * @return  self
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get the value of motivo_id
     */
    public function getMotivo_id()
    {
        return $this->motivo_id;
    }

    /**
     * Set the value of motivo_id
     *
     * @return  self
     */
    public function setMotivo_id($motivo_id)
    {
        $this->motivo_id = $motivo_id;

        return $this;
    }

    /**
     * Get the value of derivacion_id
     */
    public function getDerivacion_id()
    {
        return $this->derivacion_id;
    }

    /**
     * Set the value of derivacion_id
     *
     * @return  self
     */
    public function setDerivacion_id($derivacion_id)
    {
        $this->derivacion_id = $derivacion_id;

        return $this;
    }

    /**
     * Get the value of articulacion_con_institucion
     */
    public function getArticulacion_con_institucion()
    {
        return $this->articulacion_con_institucion;
    }

    /**
     * Set the value of articulacion_con_institucion
     *
     * @return  self
     */
    public function setArticulacion_con_institucion($articulacion_con_instituciones)
    {
        $this->articulacion_con_institucion = $articulacion_con_instituciones;

        return $this;
    }

    /**
     * Get the value of internacion
     */
    public function getInternacion()
    {
        return $this->internacion;
    }

    /**
     * Set the value of internacion
     *
     * @return  self
     */
    public function setInternacion($internacion)
    {
        $this->internacion = $internacion;

        return $this;
    }

    /**
     * Get the value of diagnostico
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set the value of diagnostico
     *
     * @return  self
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get the value of observaciones
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set the value of observaciones
     *
     * @return  self
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get the value of tratamiento_farmacologico_id
     */
    public function getTratamiento_farmacologico_id()
    {
        return $this->tratamiento_farmacologico_id;
    }

    /**
     * Set the value of tratamiento_farmacologico_id
     *
     * @return  self
     */
    public function setTratamiento_farmacologico_id($tratamiento_farmacologico_id)
    {
        $this->tratamiento_farmacologico_id = $tratamiento_farmacologico_id;

        return $this;
    }

    /**
     * Get the value of acompanamiento_id
     */
    public function getAcompanamiento_id()
    {
        return $this->acompanamiento_id;
    }

    /**
     * Set the value of acompanamiento_id
     *
     * @return  self
     */
    public function setAcompanamiento_id($acompanamiento_id)
    {
        $this->acompanamiento_id = $acompanamiento_id;

        return $this;
    }

    /**
     * Get the value of borrado
     */
    public function getBorrado()
    {
        return $this->borrado;
    }

    /**
     * Set the value of borrado
     *
     * @return  self
     */
    public function setBorrado($borrado)
    {
        $this->borrado = $borrado;

        return $this;
    }
}
