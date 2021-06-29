<?php
namespace Calificaciones\entity;


class CalificacionesusersEntity
{
    private $id_calificacion;
    private $id_user;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id_calificacion = (isset($data["id_calificacion"]) ? $data["id_calificacion"] : null);
        $this->id_user = (isset($data["id_user"]) ? $data["id_user"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function id_calificacion($id_calificacion = null){ if($id_calificacion !== null){ $this->id_calificacion=$id_calificacion; }else{ return $this->id_calificacion; } }
    public function id_user($id_user = null){ if($id_user !== null){ $this->id_user=$id_user; }else{ return $this->id_user; } }
}
?>
