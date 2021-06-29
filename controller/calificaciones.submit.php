<?php
use Franky\Core\validaciones; 
use Calificaciones\model\CalificacionesModel;
use Calificaciones\model\CalificacionesgeneralesModel;
use Calificaciones\model\CalificacionesusersModel;
use Calificaciones\model\CalificacionesguestModel;
use Calificaciones\entity\CalificacionesEntity;
use Calificaciones\entity\CalificacionesgeneralesEntity;
use Calificaciones\entity\CalificacionesusersEntity;
use Calificaciones\entity\CalificacionesguestEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CalificacionesModel = new CalificacionesModel();
$CalificacionesgeneralesModel = new CalificacionesgeneralesModel();
$CalificacionesusersModel = new CalificacionesusersModel();
$CalificacionesguestModel = new CalificacionesguestModel();

$CalificacionesEntity = new CalificacionesEntity($MyRequest->getRequest());
$CalificacionesguestEntity = new CalificacionesguestEntity($MyRequest->getRequest());
$CalificacionesusersEntity = new CalificacionesusersEntity();
$CalificacionesgeneralesEntity = new CalificacionesgeneralesEntity();


$calificacion       = $MyRequest->getRequest('calificacion');
$seccion       = $Tokenizer->decode($MyRequest->getRequest('seccion'));
$seccion_config       = $Tokenizer->decode($MyRequest->getRequest('seccion_config'));

$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$id_item = $Tokenizer->decode($MyRequest->getRequest('id_item'));
$CalificacionesEntity->id_item($id_item);
$CalificacionesEntity->tabla($seccion);
if(empty($calificacion))
{
    $calificacion = 1;
}

$error = false;

$validaciones =  new validaciones();

if(getCoreConfig($seccion_config.'/calificaciones/tipo') == 'calificacion')
{
    $CalificacionesEntity->calificacion($calificacion);
    $rules = $CalificacionesEntity->setValidationCalificacion();
}
if(getCoreConfig($seccion_config.'/calificaciones/tipo') == 'comentario'){
    $rules = $CalificacionesEntity->setValidationComentario();
}
if(getCoreConfig($seccion_config.'/calificaciones/tipo') == 'calificacion-comentario'){
    $CalificacionesEntity->calificacion($calificacion);
    $rules = $CalificacionesEntity->setValidationCalificacionComentario();
}

$valid = $validaciones->validRules($rules);
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MySession->LoggedIn() && getCoreConfig($seccion_config.'/calificaciones/guest') == 1):
    $valid = $validaciones->validRules($CalificacionesguestEntity->setValidation());
    if(!$valid)
    {
        $MyFlashMessage->setMsg("error",$validaciones->getMsg());
        $error = true;
    }
endif;

if(empty($seccion_config))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
    $error = true;
}


if($error == false)        
{
    if(!$MySession->LoggedIn() && getCoreConfig($seccion_config.'/calificaciones/guest') == 0):
        //pendiente.
        $MySession->SetVar('calificaciones_eventos_pendientes',[
            'calificaciones' => $CalificacionesEntity->getArrayCopy(),'seccion_config' => $seccion_config
        ]);
        $MyRequest->redirect(LOGIN.'?callback='.$callback);
    endif;
    
    $CalificacionesEntity->createdAt(date('Y-m-d H:i:s'));
    $CalificacionesEntity->status(1);
    $CalificacionesEntity->status_admin(1);
    $CalificacionesEntity->aprovado((getCoreConfig($seccion_config.'/calificaciones/moderado') == 1 ? 0 : 1));
      
    $result = $CalificacionesModel->save($CalificacionesEntity->getArrayCopy());
    if($result == REGISTRO_SUCCESS)
    {
        if(getCoreConfig($seccion_config.'/calificaciones/moderado') == 1):
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("calificaciones_calificacion_moderada_guardada"));
        else:
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("calificaciones_calificacion_guardada"));
        endif;
        $id = $CalificacionesModel->getUltimoId();

        $CalificacionesEntity->exchangeArray([]);
        $CalificacionesEntity->tabla($seccion);
        $CalificacionesEntity->id_item($id_item);
        $CalificacionesEntity->status(1);
        $CalificacionesEntity->status_admin(1);
        $CalificacionesEntity->aprovado(1);
        $CalificacionesModel->setTampag(1000000000000000);
        $total = 0;
        if($CalificacionesModel->getData($CalificacionesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            while($registro = $CalificacionesModel->getRows())
            {
                $total += $registro['calificacion'];
            }
            $total = $total/$CalificacionesModel->getTotal();
        }

        
        $CalificacionesgeneralesEntity->tabla($seccion);
        $CalificacionesgeneralesEntity->id_item($id_item);  
        $resultgeneral = $CalificacionesgeneralesModel->getData($CalificacionesgeneralesEntity->getArrayCopy());
        $CalificacionesgeneralesEntity->calificacion(round($total));


        if($resultgeneral == REGISTRO_SUCCESS)
        {
            $CalificacionesgeneralesModel->update($CalificacionesgeneralesEntity->getArrayCopy());      
        }
        else{
            $CalificacionesgeneralesModel->save($CalificacionesgeneralesEntity->getArrayCopy());      
        }
        if(!$MySession->LoggedIn())
        {
            $CalificacionesguestEntity->id_calificacion($id);
            $CalificacionesguestModel->save($CalificacionesguestEntity->getArrayCopy());
        }
        else
        {
            $CalificacionesusersEntity->id_calificacion($id);
            $CalificacionesusersEntity->id_user($MySession->GetVar('id'));
            $CalificacionesusersModel->save($CalificacionesusersEntity->getArrayCopy());      
        }
        $location = $callback;

    }
    elseif($result == REGISTRO_ERROR)
    {

       
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
       
        $location = $MyRequest->getReferer();
    }
    else
    {
        $MyFlashMessage->setMsg("error",$result);
        $location = $MyRequest->getReferer();
    }
}
else
{
    $location = $MyRequest->getReferer();
}

$MyRequest->redirect($location);
?>