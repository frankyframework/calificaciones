<?php
namespace Calificaciones\entity;


class CalificacionesgeneralesEntity
{
    private $id_item;
    private $calificacion;
    private $tabla;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id_item = (isset($data["id_item"]) ? $data["id_item"] : null);
        $this->calificacion = (isset($data["calificacion"]) ? $data["calificacion"] : null);
        $this->tabla = (isset($data["tabla"]) ? $data["tabla"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    

    public function id_item($id_item = null){ if($id_item !== null){ $this->id_item=$id_item; }else{ return $this->id_item; } }

    public function calificacion($calificacion = null){ if($calificacion !== null){ $this->calificacion=$calificacion; }else{ return $this->calificacion; } }

    public function tabla($tabla = null){ if($tabla !== null){ $this->tabla=$tabla; }else{ return $this->tabla; } }

}
?>
