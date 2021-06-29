<?php
namespace Calificaciones\model;

class CalificacionesModel  extends \Franky\Database\Mysql\objectOperations
{
    private $campos;
    private $campo_item;
    private $campo_item_id;
    private $tabla_item;
    private $busca;
    private $userData;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('calificaciones_calificaciones');
    }
    
    
    function setUserData($data){
        $this->userData = $this->optimizeEntity($data);
        
    }
    function setCampos($campos)
    {
        $this->campos = $campos;
    }

    function setCampoItem($item)
    {
        $this->campo_item = $item;
    }
    function setCampoItemId($item_id)
    {
        $this->campo_item_id = $item_id;
    }

    function setTablaItem($tabla)
    {
        $this->tabla_item = $tabla;
    }
    function setBusca($busca)
    {
        $this->busca = $busca;
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["calificaciones_calificaciones.id",
            "calificaciones_calificaciones.id_item",
            "calificaciones_calificaciones.tabla",
            "calificaciones_calificaciones.createdAt",
            "calificaciones_calificaciones.updateAt",
            "calificaciones_calificaciones.status",
            "calificaciones_calificaciones.status_admin",
            "calificaciones_calificaciones.aprovado",
            "calificaciones_calificaciones.calificacion",
            "calificaciones_calificaciones.titulo",
            "calificaciones_calificaciones.comentario"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("calificaciones_calificaciones.".$k,$v,'=');
        }

        return $this->getColeccion($campos);

    }

    function getFullData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["calificaciones_calificaciones.id",
            "calificaciones_calificaciones.id_item",
            "calificaciones_calificaciones.tabla",
            "calificaciones_calificaciones.createdAt",
            "calificaciones_calificaciones.updateAt",
            "calificaciones_calificaciones.status",
            "calificaciones_calificaciones.status_admin",
            "calificaciones_calificaciones.aprovado",
            "calificaciones_calificaciones.calificacion",
            "calificaciones_calificaciones.titulo",
            "calificaciones_calificaciones.comentario",
            $this->tabla_item.'.'.$this->campo_item.' as item',
            "calificaciones_guest.nombre as nombre_guest",
            "calificaciones_guest.email",
            "users.nombre"
        ];

        
        foreach($data as $k => $v)
        {
            $this->where()->addAnd("calificaciones_calificaciones.".$k,$v,'=');
        }

        if(!empty( $this->userData ))
        {
            foreach($this->userData as $k => $v)
            {
                $this->where()->addAnd("calificaciones_users.".$k,$v,'=');
            }
        }
         if(!empty($this->busca) )
        {

            $this->where()->concat("AND (");
           
                $this->where()->addOr('calificaciones_calificaciones.titulo',"%$this->busca%",'like');
                $this->where()->addOr($this->tabla_item.'.'.$this->campo_item,"%$this->busca%",'like');
                $this->where()->addOr('calificaciones_calificaciones.comentario',"%$this->busca%",'like');
                $this->where()->concat(')');

        }
        $this->from()->addInner($this->tabla_item,'calificaciones_calificaciones.id_item',$this->tabla_item.'.'.$this->campo_item_id);
        $this->from()->addLeft("calificaciones_guest",'calificaciones_calificaciones.id','calificaciones_guest.id_calificacion');
        $this->from()->addLeft("calificaciones_users",'calificaciones_calificaciones.id','calificaciones_users.id_calificacion');
        $this->from()->addLeft("users",'calificaciones_users.id_user','users.id');

        return $this->getColeccion($campos);

    }

    private function optimizeEntity($array)
    {
        foreach ($array as $k => $v )
        {
            if (!isset($v)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    public function save($data)
    {
        $data = $this->optimizeEntity($data);


    	if (isset($data['id']))
    	{
            $this->where()->addAnd('id',$data['id'],'=');

            return $this->editarRegistro($data);
    	}
    	else {

            return $this->guardarRegistro($data);
    	}

    }

    public function delete($id)
    {
        $this->where()->addAnd('id',$id,'=');
        return $this->eliminarRegistro();
    }
}
?>
