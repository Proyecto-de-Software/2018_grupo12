<?php

namespace App\Models;

class Paciente{
  private $id;
  private $apellido;
  private $nombre;
  private $fecha_nac;
  private $lugar_nac;
  private $localidad_id;
  private $partido_id;
  private $region_sanitaria_id;
  private $domicilio;
  private $genero_id;
  private $tiene_documento;
  private $tipo_doc_id;
  private $numero;
  private $tel;
  private $nro_historia_clinica;
  private $nro_carpeta;
  private $obra_social_id;
  private $borrado;
  private $nombreObraSocial;
  private $nombreTipoDocumento;

  public function __construct($id,$apellido,$nombre,$fecha_nac,$lugar_nac,$localidad_id,$partido_id,$region_sanitaria_id,
  $domicilio,$genero_id,$tiene_documento,$tipo_doc_id,$numero,$tel,$nro_historia_clinica,$nro_carpeta,$obra_social_id,$borrado){
    $this ->id=$id;
    $this ->apellido=$apellido;
    $this ->nombre=$nombre;
    $this ->fecha_nac=$fecha_nac;
    $this ->lugar_nac=$lugar_nac;
    $this ->localidad_id=$localidad_id;
    $this ->partido_id=$partido_id;
    $this ->region_sanitaria_id=$region_sanitaria_id;
    $this ->domicilio=$domicilio;
    $this ->genero_id=$genero_id;
    $this ->tiene_documento=$tiene_documento;
    $this ->tipo_doc_id=$tipo_doc_id;
    $this ->numero=$numero;
    $this ->tel=$tel;
    $this ->nro_historia_clinica=$nro_historia_clinica;
    $this ->nro_carpeta=$nro_carpeta;
    $this ->obra_social_id=$obra_social_id;
    $this ->borrado=$borrado;
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
   * Get the value of apellido
   */
  public function getApellido()
  {
    return $this->apellido;
  }

  /**
   * Set the value of apellido
   *
   * @return  self
   */
  public function setApellido($apellido)
  {
    $this->apellido = $apellido;

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
   * Get the value of fecha_nac
   */
  public function getFecha_nac()
  {
    return $this->fecha_nac;
  }

  /**
   * Set the value of fecha_nac
   *
   * @return  self
   */
  public function setFecha_nac($fecha_nac)
  {
    $this->fecha_nac = $fecha_nac;

    return $this;
  }

  /**
   * Get the value of lugar_nac
   */
  public function getLugar_nac()
  {
    return $this->lugar_nac;
  }

  /**
   * Set the value of lugar_nac
   *
   * @return  self
   */
  public function setLugar_nac($lugar_nac)
  {
    $this->lugar_nac = $lugar_nac;

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

  /**
   * Get the value of domicilio
   */
  public function getDomicilio()
  {
    return $this->domicilio;
  }

  /**
   * Set the value of domicilio
   *
   * @return  self
   */
  public function setDomicilio($domicilio)
  {
    $this->domicilio = $domicilio;

    return $this;
  }

  /**
   * Get the value of genero_id
   */
  public function getGenero_id()
  {
    return $this->genero_id;
  }

  /**
   * Set the value of genero_id
   *
   * @return  self
   */
  public function setGenero_id($genero_id)
  {
    $this->genero_id = $genero_id;

    return $this;
  }

  /**
   * Get the value of tiene_documento
   */
  public function getTiene_documento()
  {
    return $this->tiene_documento;
  }

  /**
   * Set the value of tiene_documento
   *
   * @return  self
   */
  public function setTiene_documento($tiene_documento)
  {
    $this->tiene_documento = $tiene_documento;

    return $this;
  }

  /**
   * Get the value of tipo_doc_id
   */
  public function getTipo_doc_id()
  {
    return $this->tipo_doc_id;
  }

  /**
   * Set the value of tipo_doc_id
   *
   * @return  self
   */
  public function setTipo_doc_id($tipo_doc_id)
  {
    $this->tipo_doc_id = $tipo_doc_id;

    return $this;
  }

  /**
   * Get the value of numero
   */
  public function getNumero()
  {
    return $this->numero;
  }

  /**
   * Set the value of numero
   *
   * @return  self
   */
  public function setNumero($numero)
  {
    $this->numero = $numero;

    return $this;
  }

  /**
   * Get the value of tel
   */
  public function getTel()
  {
    return $this->tel;
  }

  /**
   * Set the value of tel
   *
   * @return  self
   */
  public function setTel($tel)
  {
    $this->tel = $tel;

    return $this;
  }

  /**
   * Get the value of nro_historia_clinica
   */
  public function getNro_historia_clinica()
  {
    return $this->nro_historia_clinica;
  }

  /**
   * Set the value of nro_historia_clinica
   *
   * @return  self
   */
  public function setNro_historia_clinica($nro_historia_clinica)
  {
    $this->nro_historia_clinica = $nro_historia_clinica;

    return $this;
  }

  /**
   * Get the value of nro_carpeta
   */
  public function getNro_carpeta()
  {
    return $this->nro_carpeta;
  }

  /**
   * Set the value of nro_carpeta
   *
   * @return  self
   */
  public function setNro_carpeta($nro_carpeta)
  {
    $this->nro_carpeta = $nro_carpeta;

    return $this;
  }

  /**
   * Get the value of obra_social_id
   */
  public function getObra_social_id()
  {
    return $this->obra_social_id;
  }

  /**
   * Set the value of obra_social_id
   *
   * @return  self
   */
  public function setObra_social_id($obra_social_id)
  {
    $this->obra_social_id = $obra_social_id;

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

  /**
   * Get the value of nombreObraSocial
   */
  public function getNombreObraSocial()
  {
    return $this->nombreObraSocial;
  }

  /**
   * Set the value of nombreObraSocial
   *
   * @return  self
   */
  public function setNombreObraSocial($nombreObraSocial)
  {
    $this->nombreObraSocial = $nombreObraSocial;

    return $this;
  }

  /**
   * Get the value of nombreTipoDocumento
   */
  public function getNombreTipoDocumento()
  {
    return $this->nombreTipoDocumento;
  }

  /**
   * Set the value of nombreTipoDocumento
   *
   * @return  self
   */
  public function setNombreTipoDocumento($nombreTipoDocumento)
  {
    $this->nombreTipoDocumento = $nombreTipoDocumento;

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
}
