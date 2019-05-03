<?php

namespace App\Models;

class UsuarioTieneRol{
   private $usuario_id;
   private $rol_id;
   private $eliminado;


   public function __construct($usuario_id,$rol_id,$eliminado){
       $this -> usuario_id =$usuario_id;
       $this -> rol_id = $rol_id;
       $this -> eliminado =$eliminado;
   }



   /**
    * Get the value of usuario_id
    */
   public function getUsuario_id()
   {
      return $this->usuario_id;
   }

   /**
    * Set the value of usuario_id
    *
    * @return  self
    */
   public function setUsuario_id($usuario_id)
   {
      $this->usuario_id = $usuario_id;

      return $this;
   }

   /**
    * Get the value of rol_id
    */
   public function getRol_id()
   {
      return $this->rol_id;
   }

   /**
    * Set the value of rol_id
    *
    * @return  self
    */
   public function setRol_id($rol_id)
   {
      $this->rol_id = $rol_id;

      return $this;
   }

   /**
    * Get the value of eliminado
    */
   public function getEliminado()
   {
      return $this->eliminado;
   }

   /**
    * Set the value of eliminado
    *
    * @return  self
    */
   public function setEliminado($eliminado)
   {
      $this->eliminado = $eliminado;

      return $this;
   }
}
