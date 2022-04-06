<?php
$CalificacionesModel = new \Calificaciones\model\CalificacionesModel();
$CalificacionesEntity = new \Calificaciones\entity\CalificacionesEntity();
$Tokenizer = new \Franky\Haxor\Tokenizer();
$MyPaginacion = new \Franky\Core\paginacion();   

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"calificaciones_calificaciones.createdAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');
    
$alias = ['createdAt' => "calificaciones_calificaciones.createdAt"];
if(isset($alias[$MyRequest->getRequest('por')]))
{
    $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$CalificacionesModel->setPage($MyPaginacion->getPage());
$CalificacionesModel->setTampag($MyPaginacion->getTampageDefault());
$CalificacionesModel->setOrdensql($orden." ".$MyPaginacion->getOrden());

$CalificacionesEntity->tabla($tabla);
$CalificacionesEntity->aprovado(1);
$CalificacionesEntity->status(1);
$CalificacionesModel->setBusca($busca_b);
$CalificacionesModel->setCampoItem($campo_item);
$CalificacionesModel->setTablaItem($tabla);
$CalificacionesModel->setCampoItemId($campo_item_id);
$result	 = $CalificacionesModel->getFullData($CalificacionesEntity->getArrayCopy());
$MyPaginacion->setTotal($CalificacionesModel->getTotal());
$lista_admin_data = array();


if($CalificacionesModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CalificacionesModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
        
        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "calificacion" => calificaciones_getStarsHTML($registro['calificacion']),
                "nombre" => (!empty($registro['nombre_guest']) ? $registro['nombre_guest'] : $registro['nombre']),
                "id" => $Tokenizer->token('calificaciones',$registro["id"]),
                "callback" => $Tokenizer->token('calificaciones',$MyRequest->getURI()),
                "nuevo_estado"  => ($registro["status_admin"] == 1 ?"desactivar" : "activar"),
        ));


        $iRow++;
    }
}
$title_grid = _calificaciones("Calificaciones y comentarios");
$class_grid = "calificaciones";
$error_grid = _calificaciones("No hay calificaciones y/o comentarios registrados");
$deleteFunction = "Calificaciones_StatusAdminCalificacion";

$frm_constante_link = "";
$MyFrankyMonster->setPHPFile(PROJECT_DIR."/modulos/calificaciones/diseno/admin/calificaciones/lista_admin.phtml");

$titulo_columnas_grid = array("createdAt" => _("Fecha"),'item' => _("Item"), "nombre" =>  _("Nombre"),"titulo" => _("Titulo"),"calificacion" => _("Calificacion"));
$value_columnas_grid = array("createdAt" ,'item', "nombre","titulo","calificacion");
$css_columnas_grid = array("createdAt" => "w-xxxx-1" ,'item' => "w-xxxx-3" ,"nombre" => "w-xxxx-2", "titulo" => "w-xxxx-3", "calificacion" => "w-xxxx-1");


$permisos_grid = $permisos_grid;

$MyFiltrosForm = new \Base\Form\filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
