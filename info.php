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
/*
require('conn/conexion_mysql.php');

mysql_select_db($database_conexion, $conn);
$query_param = "SELECT * FROM parametros" ;
$param = mysql_query($query_param, $conn) or die(mysql_error()."  Error de consulta");
$row_param = mysql_fetch_assoc($param);
$total_rows_param = mysql_num_rows($param);

$compania = $row_param['compania'];
$departamento = $row_param['departamento'];
$url = $row_param['url'];
$website = $row_param['website'];
$nombre_proyecto = $row_param['nombre_proyecto'];
$version = $row_param['version'];

*/

$compania = "Uno a Uno";
$departamento = "CoordinacionesTigo";
$url = "http://www.unoauno.net";
$website = "www.unoauno.net";
$nombre_proyecto = "Casos Especiales";
$version = "1.0";

?>

