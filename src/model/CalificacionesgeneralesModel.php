<?php
namespace Calificaciones\model;

class CalificacionesgeneralesModel  extends \Franky\Database\Mysql\objectOperations
{

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('calificaciones_generales');
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id_item","calificacion","tabla"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("calificaciones_generales.".$k,$v,'=');
        }

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
        return $this->guardarRegistro($data);
    }
    public function update($data)
    {
        $data = $this->optimizeEntity($data);
            $this->where()->addAnd('id_item',$data['id_item'],'=');
            $this->where()->addAnd('tabla',$data['tabla'],'=');
            return $this->editarRegistro($data);
    	

    }
}
?>
