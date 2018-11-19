<?php
# FileName="conexion_mysql.php"
# Type="MYSQL"
$hostname_conexion = '10.255.254.90';
$username_conexion = 'sysadmin';
$password_conexion = 'c3por2d2';
$database_conexion = 'db_casos_coord';	

$conn = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conexion);
mysql_query("SET NAMES 'utf8'");
?>


