<?php
namespace Calificaciones\entity;


class CalificacionesguestEntity
{
    private $id_calificacion;
    private $nombre;
    private $email;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id_calificacion = (isset($data["id_calificacion"]) ? $data["id_calificacion"] : null);
        $this->nombre = (isset($data["nombre"]) ? $data["nombre"] : null);
        $this->email = (isset($data["email"]) ? $data["email"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
            "nombre" => array("valor" => $this->nombre,"required"),
            "email" => array("valor" => $this->email,"required","email"),);
    }

    public function id_calificacion($id_calificacion = null){ if($id_calificacion !== null){ $this->id_calificacion=$id_calificacion; }else{ return $this->id_calificacion; } }

    public function nombre($nombre = null){ if($nombre !== null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function email($email = null){ if($email !== null){ $this->email=$email; }else{ return $this->email; } }



}
?>
