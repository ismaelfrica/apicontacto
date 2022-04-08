<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: PUT");


include("../../../libreria/engine.php");

//recibo los parametros 
$data = json_decode(file_get_contents('php://input'), true);

//instancio la clase
$contacto = new atl_contacto();

//mensaje

//validacion de agregar
validarRegistro($data,'eliminar');