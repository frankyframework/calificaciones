<?php

function _calificaciones($txt)
{
    return dgettext("calificaciones",$txt);
}

function getFrmCalificacion($seccion,$tabla,$id)
{
    if(getCoreConfig($seccion.'/calificaciones/enabled') == 0)
    {
        return '';
    }
    global $MyMetatag;
    global $MySession;
    global $MyRequest;
    global $MyFrankyMonster;
    $uiCommand = $MyFrankyMonster->getUiCommand($MyFrankyMonster->MySeccion());
  
    if (is_array($uiCommand[3])) {
        if (!in_array('jquery-validate',$uiCommand[3])) 
        {
            $MyMetatag->setJs("/public/jquery/jquery-validate/js/jquery.validate.min.js");
            $MyMetatag->setCss("/public/jquery/jquery-validate/css/validate.css");
        }     
    }
    else{
        $MyMetatag->setJs("/public/jquery/jquery-validate/js/jquery.validate.min.js");
        $MyMetatag->setCss("/public/jquery/jquery-validate/css/validate.css");
    }
    $CalificacionesForm = new \Calificaciones\Form\CalificacionesForm('frmreview');
    $Tokenizer = new Franky\Haxor\Tokenizer;

    $CalificacionesForm->setAtributoInput('callback','value',$Tokenizer->token("calificaciones", $MyRequest->getURI()));
    $CalificacionesForm->setAtributoInput('seccion','value',$Tokenizer->token("calificaciones", $tabla));
    $CalificacionesForm->setAtributoInput('seccion_config','value',$Tokenizer->token("calificaciones", $seccion));
    $CalificacionesForm->setAtributoInput('id_item','value',$Tokenizer->token("calificaciones", $id));
    $MyMetatag->setJs("/public/plugins/calificacion/rating.js");
    $MyMetatag->setCss("/public/plugins/calificacion/rating.css"); 
    
    return render(PROJECT_DIR.'/modulos/calificaciones/diseno/frm.calificaciones.phtml',
    [
        'MySession' => $MySession,
        'CalificacionesForm' => $CalificacionesForm,
        'seccion' => $seccion

    ]);   
}


function calificaciones_completarTareas()
{
    global $MySession;
    global $MyRequest;
    global $MyFlashMessage;
    global $MyMessageAlert;
    $eventos_pendientes = $MySession->GetVar('calificaciones_eventos_pendientes');


    if(isset($eventos_pendientes['calificaciones']))
    {
        $seccion_config = $eventos_pendientes['seccion_config'];
        $CalificacionesModel = new \Calificaciones\model\CalificacionesModel();
        $CalificacionesgeneralesModel =  new \Calificaciones\model\CalificacionesgeneralesModel();
        $CalificacionesusersModel = new \Calificaciones\model\CalificacionesusersModel();
        
        $CalificacionesEntity = new \Calificaciones\entity\CalificacionesEntity($eventos_pendientes['calificaciones']);
        $CalificacionesusersEntity = new Calificaciones\entity\CalificacionesusersEntity();
        $CalificacionesgeneralesEntity = new Calificaciones\entity\CalificacionesgeneralesEntity();
        
        $CalificacionesEntity->createdAt(date('Y-m-d H:i:s'));
        $CalificacionesEntity->status(1);
        $CalificacionesEntity->status_admin(1);
        $CalificacionesEntity->aprovado((getCoreConfig($seccion_config.'/calificaciones/moderado') == 1 ? 0 : 1));
        
        $CalificacionesgeneralesEntity->tabla($CalificacionesEntity->tabla());
        $CalificacionesgeneralesEntity->id_item($CalificacionesEntity->id_item());
       

        $result = $CalificacionesModel->save($CalificacionesEntity->getArrayCopy());
        if($result == REGISTRO_SUCCESS)
        {
            $id = $CalificacionesModel->getUltimoId();

            $CalificacionesEntity->exchangeArray([]);
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
            $CalificacionesusersEntity->id_calificacion($id);
            $CalificacionesusersEntity->id_user($MySession->GetVar('id'));
            $CalificacionesusersModel->save($CalificacionesusersEntity->getArrayCopy());      
            if(getCoreConfig($seccion_config.'/calificaciones/moderado') == 1):
                $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("calificacion_calificacion_moderada_guardada"));
            else:
                $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("calificacion_calificacion_guardada"));
            endif;
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
        }

    }

    $MySession->UnsetVar('calificaciones_eventos_pendientes');

}


function calificaciones_getStarsHTML($cal)
{
	if ($cal >= 5.0)
	{
		$cal= 5.0;
	}
	if ($cal <= 0)
	{
		$cal= 0;
	}

        $alt = $cal;
        $html = "<div class=\"rating\">";
	for($x=1; $x <= 5; $x++)
	{
		if($cal <= 0)
		{
			$html .= "<i class='icon icon-estrella-llena _alpha'></i>";
		}
		elseif($cal > 0 && $cal < .4)
		{
			$html .= "<i class='icon icon-estrella-llena _alpha'></i>";
			$cal = 0;
		}
                elseif($cal == .5)
		{
			$html .= "<i class='icon icon-estrella-llena'></i>";
			$cal = 0;
		}
                elseif($cal >.5 && $cal < 1)
		{
			$html .= "<i class='icon icon-estrella-llena'></i>";
			$cal = 0;
		}
                elseif($cal >= 1)
		{
			$html .= "<i class='icon icon-estrella-llena'></i>";
			$cal--;
		}
	}

	return $html.'</div>';
}


?>