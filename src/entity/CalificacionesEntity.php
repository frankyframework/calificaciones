<?php
namespace Calificaciones\entity;


class CalificacionesEntity
{
    private $id;
    private $id_item;
    private $tabla;
    private $createdAt;
    private $updateAt;
    private $status;
    private $status_admin;
    private $aprovado;
    private $calificacion;
    private $titulo;
    private $comentario;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->id_item = (isset($data["id_item"]) ? $data["id_item"] : null);
        $this->tabla = (isset($data["tabla"]) ? $data["tabla"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->status_admin = (isset($data["status_admin"]) ? $data["status_admin"] : null);
        $this->aprovado = (isset($data["aprovado"]) ? $data["aprovado"] : null);
        $this->calificacion = (isset($data["calificacion"]) ? $data["calificacion"] : null);
        $this->titulo = (isset($data["titulo"]) ? $data["titulo"] : null);
        $this->comentario = (isset($data["comentario"]) ? $data["comentario"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidationCalificacion()
    {
        return array( 
            "id_item" => array("valor" => $this->id_item,"required"),
            "tabla" => array("valor" => $this->tabla,"required"),
            "calificacion" => array("valor" => $this->calificacion,"required"),
           );
    }
    public function setValidationComentario()
    {
        return array( 
            "id_item" => array("valor" => $this->id_item,"required"),
            "tabla" => array("valor" => $this->tabla,"required"),
            "titulo" => array("valor" => $this->titulo,"required"),
            "comentario" => array("valor" => $this->comentario,"required"));
    }
    public function setValidationCalificacionComentario()
    {
        return array( 
            "id_item" => array("valor" => $this->id_item,"required"),
            "tabla" => array("valor" => $this->tabla,"required"),
            "calificacion" => array("valor" => $this->calificacion,"required"),
            "titulo" => array("valor" => $this->titulo,"required"),
            "comentario" => array("valor" => $this->comentario,"required"),);
    }

    

    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function id_item($id_item = null){ if($id_item !== null){ $this->id_item=$id_item; }else{ return $this->id_item; } }

    public function tabla($tabla = null){ if($tabla !== null){ $this->tabla=$tabla; }else{ return $this->tabla; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function status_admin($status_admin = null){ if($status_admin !== null){ $this->status_admin=$status_admin; }else{ return $this->status_admin; } }

    public function aprovado($aprovado = null){ if($aprovado !== null){ $this->aprovado=$aprovado; }else{ return $this->aprovado; } }

    public function calificacion($calificacion = null){ if($calificacion !== null){ $this->calificacion=$calificacion; }else{ return $this->calificacion; } }

    public function titulo($titulo = null){ if($titulo !== null){ $this->titulo=$titulo; }else{ return $this->titulo; } }

    public function comentario($comentario = null){ if($comentario !== null){ $this->comentario=$comentario; }else{ return $this->comentario; } }



}
?>
