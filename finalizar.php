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

// obtener datos del equipo como ip, host, servidor y pagina actual.

$nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']); 
$ipaddress = $_SERVER['REMOTE_ADDR']; 
$paginaactual = $_SERVER['PHP_SELF'];
$servidor = $_SERVER['HTTP_HOST'];

#MySQL
# require("conn/conexion_mysql.php");
#SQLServer
require("conn/conexion.php");
require('info.php');
require('class/class.phpmailer.php');
require('class/class.smtp.php');
require('class/PHPMailerAutoload.php');
require('config.php');

//session_start();
$cookie_u= "usuario";
$cookie_n= "nombre";
$cookie_p= "perfil";
$cookie_c= "correo";
#asigno los valores de las cookies a las variables 
$usuario = $_COOKIE[$cookie_u];	
$nombre  = $_COOKIE[$cookie_n];
$perfil  = $_COOKIE[$cookie_p];	
$correo  = $_COOKIE[$cookie_c];


if ($usuario == "")
{
	echo "<div class=\"warning-box\" align='center'><br><br><br><br><br><br><h2> Usuario no autenticado! </h2>  <br> </div>\n";
	$MensajeValidacion = "Usuario no autenticado!";
	?>
			<script type="text/javascript">
				window.onload=function()
				{
				 alert('<?php echo $MensajeValidacion; ?>');
				}	
			</script>
			
			<script LANGUAGE="JavaScript">
			var pagina="index.php"
			function redireccionar() 
			{
				location.href=pagina
			} 
			setTimeout ("redireccionar()", 500);
			</script>
			
			<?php		
	exit;
}



if ( (isset($_GET['txtID']))    ) 
{
	$registro = $_GET['txtID'];
	$procesado = true;

	$conn_update = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
	$sql_update  = " UPDATE casos_especiales SET  bitacora = '".$_GET['txtBitacora']."', fecha_finalizado = getdate(), estado = 'Finalizada'  WHERE id = '".$registro."' AND agente_asignado = '".$usuario."'   ";
	#echo "<br> SQL update: ".$sql_update ;
	$resultados_update = sqlsrv_query($conn_update , $sql_update ); 
	if ($resultados_update  == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 

	$conn_agente = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
	$sql_agente = " select teleoperador_user, Teleoperador_Descripcion, teleoperador_email, Teleoperador_Descripcion from Teleoperador where teleoperador_user = '".$_GET['txtAsignado']."'  ";
	$resultados_agente= sqlsrv_query($conn_agente, $sql_agente); 
	if ($resultados_agente == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 
	$row_agente = sqlsrv_fetch_array($resultados_agente, SQLSRV_FETCH_ASSOC);


	# envio de correo al agente

	$url = "http://".$servidor."/casos_coord/agente.php";
	############## Envio del formulario por email  ################
	header("Content-Type: text/html;charset=utf-8");	
	$asunto = "Finalizado: Caso Especial del socio - ".$_GET['txtSocio']." ";
	$comentarios = "El caso ha sido Finalizado. ";
	
	# $errormsj = printErrors($erroresSQL);
	$mensaje = ' 
	<html> 
	<head> 
	   <title>'.$asunto.' </title> 
	</head> 
	<body> 
	<h1>'.$asunto.'</h1> 
	<hr />
	<p> 
	'.$comentarios.' <br> <br> <br>
	 <u>Socio:</u> '.$_GET['txtSocio'].' <br> 	 
	 <u>Fecha Finalizado:</u> '.$_GET['txtFechaFinalizado'].' <br>
	 <u>Agente:</u> '.$row_agente['Teleoperador_Descripcion'].' <br>
	 <u>Bitacora:</u> '.$_GET['txtBitacora'].' <br>
	 <br>
	</p> 
	<hr />
	</body> 
	</html> 
	'; 					


	$de = 'sistemasweb@unoauno.net';	
	$para = 'coordinacion_supervision@unoauno.net'; 
	$copia = $row_agente['teleoperador_email'];	
	$copiaoculta = '';	
		
	$NombreEnvio = "Casos Especiales Coordinaciones";
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->CharSet = 'UTF-8';
	$mail->Host = $servidor; // SMTP a utilizar. Por ej. smtp.elserver.com
	$mail->Username = $usuario; // Correo completo a utilizar
	$mail->Password = $clave; // Contraseña
	$mail->Port = $puerto; // Puerto a utilizar
	$mail->From = $de; // Desde donde enviamos (Para mostrar)
	$mail->FromName = $NombreEnvio;
	$mail->AddAddress($para); // Esta es la dirección a donde enviamos
	if (trim($copia) != "")
	{
		$mail->AddCC($copia); // Copia
	}
	if (trim($copiaoculta) != "")
	{
		$mail->AddBCC($copiaoculta); // Copia oculta
	}
	$mail->IsHTML(true); // El correo se envía como HTML
	$mail->Subject = $asunto; // Este es el titulo del email.

	$mail->Body = $mensaje; // Mensaje a enviar 
	#$mail->AltBody = 'Asunto: '.$asunto.' \r\n  Comentarios: '.$comentarios.' \r\n  Solicitante: '.$nombre ; // Texto sin html
	#$mail->AddAttachment("imagenes/imagen.jpg", "imagen.jpg");
	$mail->CharSet = 'UTF-8';
	$exito = $mail->Send(); // Envía el correo.

	if($exito){
	//echo 'El correo fue enviado correctamente.';
	}else{
	echo 'Hubo un inconveniente. Contacta a un administrador.';
	}
	############## FIN Envio del formulario por email  ################ 


}
else{ $procesado = false; }


//////////////////////////////////////////////////////////////////////////

function FormatErrors( $errors ) 
{ /* Display errors. */ 
	echo "Error information: <br/>"; 
	foreach ( $errors as $error ) 
	{ 
		echo "SQLSTATE: ".$error['SQLSTATE']."<br/>"; 
		echo "Code: ".$error['code']."<br/>";
		echo "Message: ".$error['message']."<br/>"; 
		echo " - " . '<a href="javascript:history.back()">Volver</a>';
	}
}	

//////////////////////////////////////////////////////////////////////////

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <title>Finalizar - Casos Especiales - </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- font fa -->
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">  
  <!-- CSS & JS-->
	<link rel="stylesheet" href="css/bootstrap.min.css">  
  <script src="jquery/3.3.1/jquery.min.js"></script>
  <script src="popper/1.14.3/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/funciones.js"></script>
  
  <!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> 
  -->  
</head>
<body>
	<div align="right">
		<button type="button" class="btn btn-outline-primary btn-sm" onclick="cerraryrecargar();" title="Cerrar"><i style="font-size:25px" class="fa">&#xf2d3;</i></button>
		
	</div>

	<div jumbotron text-center class="bg-dark text-white">
		<h3 align='center'> Finalizar Caso Especial </h3>
	</div>
	<hr/> <!-- ___________________________________________________________________ -->

	<div class="container mt-3">
		<?php
		if ($procesado == false)
			{				
				if  (isset($_GET['finalizar']))
				{ $id_busqueda = $_GET['finalizar']; 
					# --------------------------Queries-------------------------
					$conn = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
					$sql = " select * from casos_especiales WHERE id = ".$id_busqueda."  AND  agente_asignado = '".$usuario."' ";
					#echo "<br> SQLver datos: ".$sql ;
					$resultados= sqlsrv_query($conn, $sql); 
					if ($resultados == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 
					$row = sqlsrv_fetch_array($resultados, SQLSRV_FETCH_ASSOC);
				}
			?>
			<div class="container">			
				<form action="finalizar.php">
					
					<div class="form-group">
					<!-- <label for="txtID">ID:</label> -->
						<input type="hidden" class="form-control form-control-sm" id="txtID" value="<?php echo $row['id']; ?>" placeholder="<?php echo $row['id']; ?>" name="txtID">
						<input type="hidden" class="form-control form-control-sm" id="txtSocio" value="<?php echo $row['socio'];?>" placeholder="<?php echo $row['socio']; ?>" name="txtSocio" >
						<input type="hidden" class="form-control form-control-sm" id="txtAsignado" value="<?php echo $row['agente_asignado']; ?>" placeholder="<?php echo $row['agente_asignado']; ?>" name="txtAsignado">
						<input type="hidden" class="form-control form-control-sm" id="txtFechaFinalizado" value="<?php  echo date('Y-m-d H:i:s'); ?>" placeholder="<?php echo date('Y-m-d H:i:s'); ?>" name="txtFechaFinalizado">
					</div>

					<div class="form-group">
						<label for="txtSocio1">Socio:</label>
						<input type="text" class="form-control form-control-sm" id="txtSocio1" value="<?php echo $row['socio'];?>" placeholder="<?php echo $row['socio']; ?>" name="txtSocio1" readonly>						
					</div>					
					
					<div class="form-group ">
					<label for="txtAsignado1" >Agente:</label>						
					<input type="text" class="form-control form-control-sm" id="txtAsignado1" value="<?php echo $row['agente_asignado']; ?>" placeholder="<?php echo $row['agente_asignado']; ?>" name="txtAsignado1" readonly>					
					</div>
					
					<div class="form-group">
						<label for="txtBitacora">Bitacora:</label>
						<textarea class="form-control form-control-sm" rows="3" id="txtBitacora" name="txtBitacora" required ></textarea>
					</div>		

					<button type="submit" class="btn btn-primary">Finalizar</button>
				</form>
			</div>
			
			<?php
			}
			else
			{
				echo '
				<div class="alert alert-success  alert-dismissible ">
					<button type="button" class="close btn-sm" data-dismiss="alert">&times;</button>
						Registro FInalizado correctamente. Puede cerrar la ventana.</strong>
				</div>
				';

				echo '<br>
					<div class="alert alert-info ">				
						Socio:<strong> '.$_GET['txtSocio'].' </strong>
						<br>Agente: <strong>'.$_GET['txtAsignado'].'.</strong>
						<br>Bitacora: <strong>'.$_GET['txtBitacora'].'.</strong>
						<br>Fecha Finalizado: <strong>'.$_GET['txtFechaFinalizado'].'.</strong>
					</div>
				';
				# -----------------------  Envio de correo --------------------


				# -----------------------  Envio de correo --------------------
			}
		?>
	</div>
	<hr/> <!-- ___________________________________________________________________ -->

	<script type="text/javascript"> 	
		function cerraryrecargar() 
		{ 
			window.opener.document.location.reload();
			self.close();			
		} 
	</script> 
</body>
</html>
