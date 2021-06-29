<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;
include 'lca.php';
include 'util.php';
bindtextdomain("calificaciones", PROJECT_DIR ."/modulos/calificaciones/locale");


if (function_exists('bind_textdomain_codeset')) 
{
    bind_textdomain_codeset("calificaciones", 'UTF-8');
}

$ObserverManager->addObserver('login_user','calificaciones_completarTareas');

$MyMetatag->setJs("/modulos/calificaciones/web/js/calificaciones.js");
?>