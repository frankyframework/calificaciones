<?php
if ($MyRequest->isAjax()) {

    $callback	= $MyRequest->getRequest('callback');
    $filters = $MyRequest->getRequest('filters');
    $dataPost = json_decode(stripslashes($filters),true);
    $dataPost = $dataPost['rules'];
    $requestFranky = [];
    $request = [];
    foreach($dataPost as $data) {
      
      $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
      
    }
    $CalificacionesModel = new \Calificaciones\model\CalificacionesModel();
    $CalificacionesEntity = new \Calificaciones\entity\CalificacionesEntity($request);
    $Tokenizer = new \Franky\Haxor\Tokenizer();

    $alias = ['createdAt' => "calificaciones_calificaciones.createdAt"];
    if(isset($alias[$MyRequest->getRequest('sidx')]))
    {
        $sortInput = $alias[$MyRequest->getRequest('sidx')];
    }
    else{
        $sortInput  = (!empty($MyRequest->getRequest('sidx',"calificaciones_calificaciones.createdAt")) ? : "calificaciones_calificaciones.createdAt");
    }
    if(!empty($request['item'])) {
        $CalificacionesModel->setItemData([$campo_item => $request['item']]);
    }
    $CalificacionesModel->setPage($MyRequest->getRequest('page',1));
    $CalificacionesModel->setTampag($MyRequest->getRequest('rows',12));
    $CalificacionesModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));
    $CalificacionesEntity->tabla($tabla);
    $CalificacionesEntity->aprovado(1);
    $CalificacionesEntity->status(1);
    $CalificacionesModel->setCampoItem($campo_item);
    $CalificacionesModel->setTablaItem($tabla);
    $CalificacionesModel->setCampoItemId($campo_item_id);
    $result	 = $CalificacionesModel->getFullData($CalificacionesEntity->getArrayCopy());
    $dataRows = ["rows" => [], "total" => ceil($CalificacionesModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $CalificacionesModel->getTotal()];


    if($CalificacionesModel->getTotal() > 0)
    {

        while($registro = $CalificacionesModel->getRows())
        {
            $registro = array_filter($registro, function($llave) {
                    return !is_numeric($llave);
            }, ARRAY_FILTER_USE_KEY);


            $dataRows['rows'][] = array_merge($registro,array(
                    "calificacion" => calificaciones_getStarsHTML($registro['calificacion']),
                    "id" => $Tokenizer->token('calificaciones',$registro["id"]),
                    "status"  => ($registro["status_admin"] == 1 ?"desactivar" : "activar"),
            ));
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
} else {
    $MyMetatag->setJs("/public/plugins/jqGrid/js/jquery.jqGrid.js");
    $MyMetatag->setJs("/public/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
    $MyMetatag->setCSS("/public/plugins/jqGrid/css/ui.jqgrid.css");
    $MyFrankyMonster->setPHPFile(PROJECT_DIR."/modulos/calificaciones/diseno/admin/calificaciones/lista_admin.phtml");
    $deleteFunction = "Calificaciones_StatusAdminCalificacion";
}