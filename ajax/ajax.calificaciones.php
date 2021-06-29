<?php
function ajax_calificaciones_getPendientesRevisar($permiso,$tabla,$seccion)
{
    global $MySession;
    global $MyAccessList;
    $CalificacionesModel = new \Calificaciones\model\CalificacionesModel;
    $CalificacionesEntity = new \Calificaciones\entity\CalificacionesEntity;
    $respuesta = null;
  
    if($MyAccessList->MeDasChancePasar($permiso))
    {
             
            $CalificacionesEntity->tabla($tabla);
            $CalificacionesEntity->aprovado(0);
            $CalificacionesModel->setTampag(100000);
            if($CalificacionesModel->getData($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
            {
                $respuesta['solicitudes_'.$seccion] = render(PROJECT_DIR.'/modulos/calificaciones/diseno/admin/pendientes.phtml',['count' => $CalificacionesModel->getTotal()]);
            }
              
    }

    return $respuesta;
}

function Calificaciones_AprovarCalificacion($id,$status)
{
    global $MySession;
    $CalificacionesModel =  new \Calificaciones\model\CalificacionesModel();
    $CalificacionesEntity =  new \Calificaciones\entity\CalificacionesEntity();
    $CalificacionesgeneralesModel = new \Calificaciones\model\CalificacionesgeneralesModel();
    $CalificacionesgeneralesEntity = new \Calificaciones\entity\CalificacionesgeneralesEntity();
    
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;
   
    if($status == 1)
    {
        $CalificacionesEntity->id(addslashes($Tokenizer->decode($id)));
        $CalificacionesEntity->aprovado($status);

        $result = $CalificacionesModel->save($CalificacionesEntity->getArrayCopy());
        $CalificacionesModel->getData($CalificacionesEntity->getArrayCopy());
        $registro = $CalificacionesModel->getRows();

        $CalificacionesEntity->exchangeArray([]);
        $CalificacionesEntity->status(1);
        $CalificacionesEntity->status_admin(1);
        $CalificacionesEntity->aprovado(1);
        $CalificacionesEntity->id_item($registro['id_item']);
        $CalificacionesEntity->tabla($registro['tabla']);
        $CalificacionesModel->setTampag(1000000000000000);
        $total = 0;
        if($CalificacionesModel->getData($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            while($_registro = $CalificacionesModel->getRows())
            {
                $total += $_registro['calificacion'];
            }
            $total = $total/$CalificacionesModel->getTotal();
        }

        
        
        $CalificacionesgeneralesEntity->tabla($CalificacionesEntity->tabla());
        $CalificacionesgeneralesEntity->id_item($CalificacionesEntity->id_item());
        $resultgeneral = $CalificacionesgeneralesModel->getData($CalificacionesgeneralesEntity->getArrayCopy());

        $CalificacionesgeneralesEntity->calificacion(round($total));

        
        if($resultgeneral == REGISTRO_SUCCESS)
        {
            $CalificacionesgeneralesModel->update($CalificacionesgeneralesEntity->getArrayCopy());      
        }
        else{
            $CalificacionesgeneralesModel->save($CalificacionesgeneralesEntity->getArrayCopy());      
        }
    }
    else{
        $result = $CalificacionesModel->delete(addslashes($Tokenizer->decode($id)));
    }
    if($result != REGISTRO_SUCCESS)
    {
            $respuesta["message"] = $MyMessageAlert->Message("calificacion_status_aprovado");
            $respuesta["error"] = true;
    }

    return $respuesta;
}

function Calificaciones_StatusCalificacion($id,$status)
{
    global $MySession;
    $CalificacionesModel =  new \Calificaciones\model\CalificacionesModel();
    $CalificacionesEntity =  new \Calificaciones\entity\CalificacionesEntity();
    $CalificacionesgeneralesModel = new \Calificaciones\model\CalificacionesgeneralesModel();
    $CalificacionesgeneralesEntity = new \Calificaciones\entity\CalificacionesgeneralesEntity();
    
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyMessageAlert;

    $respuesta = null;

  
    $CalificacionesEntity->id(addslashes($Tokenizer->decode($id)));
    $CalificacionesEntity->status($status);

    if($CalificacionesModel->save($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $CalificacionesModel->getData($CalificacionesEntity->getArrayCopy());
        $registro = $CalificacionesModel->getRows();
        $CalificacionesEntity->exchangeArray([]);
        $CalificacionesEntity->status(1);
        $CalificacionesEntity->status_admin(1);
        $CalificacionesEntity->aprovado(1);
        $CalificacionesEntity->id_item($registro['id_item']);
        $CalificacionesEntity->tabla($registro['tabla']);
        $CalificacionesModel->setTampag(1000000000000000);
        $total = 0;
        if($CalificacionesModel->getData($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            while($_registro = $CalificacionesModel->getRows())
            {
                $total += $_registro['calificacion'];
            }
            $total = $total/$CalificacionesModel->getTotal();
        }

        $CalificacionesgeneralesEntity->tabla($CalificacionesEntity->tabla());
        $CalificacionesgeneralesEntity->id_item($CalificacionesEntity->id_item());
        $resultgeneral = $CalificacionesgeneralesModel->getData($CalificacionesgeneralesEntity->getArrayCopy());

        $CalificacionesgeneralesEntity->calificacion(round($total));

        
        if($resultgeneral == REGISTRO_SUCCESS)
        {
            $CalificacionesgeneralesModel->update($CalificacionesgeneralesEntity->getArrayCopy());      
        }
        else{
            $CalificacionesgeneralesModel->save($CalificacionesgeneralesEntity->getArrayCopy());      
        }

    }
    else
    {
          $respuesta["message"] = $MyMessageAlert->Message("calificaciones_calificacion_error_delete");
          $respuesta["error"] = true;
    }
    
    


    return $respuesta;
}


function Calificaciones_StatusAdminCalificacion($id,$status)
{
    global $MySession;
    $CalificacionesModel =  new \Calificaciones\model\CalificacionesModel();
    $CalificacionesEntity =  new \Calificaciones\entity\CalificacionesEntity();
    $CalificacionesgeneralesModel = new \Calificaciones\model\CalificacionesgeneralesModel();
    $CalificacionesgeneralesEntity = new \Calificaciones\entity\CalificacionesgeneralesEntity();
    
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyMessageAlert;

    $respuesta = null;

  
    $CalificacionesEntity->id(addslashes($Tokenizer->decode($id)));
    $CalificacionesEntity->status_admin($status);

    if($CalificacionesModel->save($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $CalificacionesModel->getData($CalificacionesEntity->getArrayCopy());
        $registro = $CalificacionesModel->getRows();
        $CalificacionesEntity->exchangeArray([]);
        $CalificacionesEntity->status(1);
        $CalificacionesEntity->status_admin(1);
        $CalificacionesEntity->aprovado(1);
        $CalificacionesEntity->id_item($registro['id_item']);
        $CalificacionesEntity->tabla($registro['tabla']);
        $CalificacionesModel->setTampag(1000000000000000);
        $total = 0;
        if($CalificacionesModel->getData($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            while($_registro = $CalificacionesModel->getRows())
            {
                $total += $_registro['calificacion'];
            }
            $total = $total/$CalificacionesModel->getTotal();
        }

        $CalificacionesgeneralesEntity->tabla($CalificacionesEntity->tabla());
        $CalificacionesgeneralesEntity->id_item($CalificacionesEntity->id_item());
        $resultgeneral = $CalificacionesgeneralesModel->getData($CalificacionesgeneralesEntity->getArrayCopy());

        $CalificacionesgeneralesEntity->calificacion(round($total));

        
        if($resultgeneral == REGISTRO_SUCCESS)
        {
            $CalificacionesgeneralesModel->update($CalificacionesgeneralesEntity->getArrayCopy());      
        }
        else{
            $CalificacionesgeneralesModel->save($CalificacionesgeneralesEntity->getArrayCopy());      
        }

    }
    else
    {
          $respuesta["message"] = $MyMessageAlert->Message("calificaciones_calificacion_error_delete");
          $respuesta["error"] = true;
    }
    
    


    return $respuesta;
}

$MyAjax->register("ajax_calificaciones_getPendientesRevisar");
$MyAjax->register("Calificaciones_AprovarCalificacion");
$MyAjax->register("Calificaciones_StatusCalificacion");
$MyAjax->register("Calificaciones_StatusAdminCalificacion");