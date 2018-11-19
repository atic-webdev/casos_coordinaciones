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
#MySQL
# require("conn/conexion_mysql.php");
#SQLServer
require("conn/conexion.php");

if  (  (isset($_POST['txtUsuario']))  AND  (isset($_POST['txtClave']))      )
{
	$usuario = $_POST['txtUsuario'];
	$password = $_POST['txtClave'];	
	
	

	$conn = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
	$sql= " SELECT * FROM   Teleoperador where teleoperador_user = '".$usuario."' ";
	$getResults= sqlsrv_query($conn, $sql); 
	$row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
	
	if ($row['teleoperador_password'] == $password)	
		{
			$errorLogin = false;
			$usuario = $_POST['txtUsuario'];
			$nombre = $row['Teleoperador_Descripcion'];
			$perfil = $row['teleoperador_perfil'];
			$correo = $row['teleoperador_email'];

			$cookie_name = "usuario"; $cookie_value =  $usuario;	setcookie($cookie_name, $cookie_value, time() + 3600, "/");	
			$cookie_name = "nombre";  $cookie_value =  $nombre;		setcookie($cookie_name, $cookie_value, time() + 3600, "/");	
			$cookie_name = "perfil";  $cookie_value =  $perfil;		setcookie($cookie_name, $cookie_value, time() + 3600, "/");	
			$cookie_name = "correo";  $cookie_value =  $correo;		setcookie($cookie_name, $cookie_value, time() + 3600, "/");	

			if ($perfil == "Agente")	{header("Location:agente.php"); }
			if ($perfil == "Supervisor")	{header("Location:supervisor.php"); }
		}
	else
		{
			$errorLogin = true;
			$cookie_name = "usuario"; $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
			$cookie_name = "nombre";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
			$cookie_name = "perfil";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
			$cookie_name = "correo";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
		
		}
}
else
{
	$errorLogin = false;	
	# limpio cookies
	$cookie_name = "usuario"; $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
	$cookie_name = "nombre";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
	$cookie_name = "perfil";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
	$cookie_name = "correo";  $cookie_value =  "";		setcookie($cookie_name, $cookie_value, time() - 3600, "/");	
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Login - Casos Especiales Coordinaciones</title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- font fa -->
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">  
  <!-- CSS & JS-->
  <link rel="stylesheet" href="css/bootstrap.min.css"> 
  <link rel="stylesheet" href="css/style.css">  
  <script src="jquery/3.3.1/jquery.min.js"></script>  
  <script src="js/bootstrap.min.js"></script>
  <script src="js/funciones.js"></script>
	
</head>
<body>
<hr/>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<!--  -->
			<div class="card-header">
				<h3>Iniciar Sesión</h3>				
			</div>
			<!--  -->
			<div class="card-body">
				<form class="m-t" role="form" action="index.php" method="POST">
					<div class="input-group form-group">							
						<div class="input-group-prepend">
							<span class="input-group-text"><i style="font-size:26px" class="fa">&#xf007;</i></span>
						</div>
						<input type="text" class="form-control" placeholder="username" id="txtUsuario" name="txtUsuario" required autofocus>					
					</div>
					<br>	
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i style="font-size:22px" class="fa">&#xf084;</i></span> 
						</div>
						<input type="password" class="form-control" placeholder="password" id="txtClave" name="txtClave" required>
					</div>
						
					<div class="form-group">
						<input type="submit" value="Login" class="btn float-left login_btn">
						<input type="reset" value="Cancel" class="btn float-right reset_btn">
					</div>
				</form>		
			</div>
			<!--  -->
			<div class="card-footer">				
					<div class="d-flex justify-content-center">						
						<?php if ($errorLogin == true)  { ?>
						<div class="alert alert-danger  alert-dismissible ">
							<button type="button" class="close btn-sm" data-dismiss="alert">&times;</button>
							 Usuario  o password <strong>Incorrecto!</strong>
						</div>
						<?php }else{ ?>
							<div class="alert alert-info alert-dismissible ">
							<button type="button" class="close  btn-sm" data-dismiss="alert">&times;</button>
							  <strong>Bienvenido!</strong>
						</div>
						<?php } ?>
					</div>
			</div>
			
		</div>
	</div>	
</div>
<hr/>
</body>
</html>