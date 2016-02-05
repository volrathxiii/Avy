<?php
define('DOCUMENT_ROOT', dirname(realpath(__FILE__)).'/');

$page_url = $argv[0];
unset($argv[0]);

$parameter = implode(" ", $argv);

//echo $parameter;
include_once 'includes/Response.class.php';
include_once 'includes/Avy.class.php';

/*
$Response = Response::set('TEST', 'SHOULD NOT PRINT');
var_dump($Response);

$Response = Response::set('RESPONSE', 'Hi there how are you!');
var_dump($Response);
*/
$Avy = new Avy($parameter);