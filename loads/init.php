<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;

include 'util.php';
__bindtextdomain("calificaciones", "calificaciones");


if (function_exists('bind_textdomain_codeset')) 
{
    bind_textdomain_codeset("calificaciones", 'UTF-8');
}

$ObserverManager->addObserver('login_user','calificaciones_completarTareas');

$MyMetatag->setJs("/modulos/calificaciones/web/js/calificaciones.js");
?>