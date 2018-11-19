
<?php
#######################################################################
# Sistema de Casos Especiales
# Proyecto: Coordinaciones Tigo
# Servidor: SQLServer 2008; PHP 5.3.28, IIS 8.5
# Desarrollado por: Allan Campos , para Uno a Uno Mercadeo de C.A.
# Release: 01-noviembre-2018. 
# Ultima modificacion: 02-noviembre-2018 16:00
#######################################################################
?> 
<?php

	$nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']); 
	$ipaddress = $_SERVER['REMOTE_ADDR']; 
	$paginaactual = $_SERVER['PHP_SELF'];
	$webserver = $_SERVER['HTTP_HOST'];


	$servidor_bd = "10.255.255.240";
	$basedatos = "unoAunoCoordinacionesTigo";
	$usuario_bd = "sistemas";
	$password_bd = "Key\$uauno";




function conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos)
{	
	if ($basedatos!="") {$connectionInfo = array( "Database"=>$basedatos, "UID"=>$usuario_bd, "PWD"=>$password_bd, "CharacterSet" => "UTF-8");}
	else  {$connectionInfo = array( "UID"=>$usuario_bd, "PWD"=>$password_bd, "CharacterSet" => "UTF-8");}

	$conn = sqlsrv_connect( $servidor_bd, $connectionInfo);
	
	return $conn;
}

?>