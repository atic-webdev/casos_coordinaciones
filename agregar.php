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


# --------------------------Queries-------------------------
#dropdown combo agentes
$conn_cbo_agente = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_cbo_agente = " select teleoperador_user, Teleoperador_Descripcion, teleoperador_email, Teleoperador_Descripcion from Teleoperador where teleoperador_perfil = 'Agente' OR  teleoperador_perfil = 'TeamLeader' AND teleoperador_perfil != 'null' ";
$resultados_cbo_agente= sqlsrv_query($conn_cbo_agente, $sql_cbo_agente); 
if ($resultados_cbo_agente == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 
//$row_agente = sqlsrv_fetch_array($resultados_agente, SQLSRV_FETCH_ASSOC);


# --------------------------Queries End-------------------------

if ( (isset($_GET['txtSocio']))    ) #AND  (isset($_POST['txtTelefonos']))  AND  (isset($_POST['txtDescripcion'])) AND  (isset($_POST['txtFechaAgendaT']))
{
	
	$procesado = true;

	$conn_insert = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
	$sql_insert  = " INSERT INTO casos_especiales (socio, telefonos, descripcion, fecha_agenda, fecha_creacion,  agente_asignado, estado)  
		VALUES ('".$_GET['txtSocio']."','".$_GET['txtTelefonos']."','".$_GET['txtDescripcion']."','".$_GET['txtFechaAgendaT']."',getdate(),'".$_GET['txtAsignado']."','Asignada')			
					";
	$resultados_insert = sqlsrv_query($conn_insert , $sql_insert ); 
	if ($resultados_insert  == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 

	$conn_agente = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
	$sql_agente = " select teleoperador_user, Teleoperador_Descripcion, teleoperador_email, Teleoperador_Descripcion from Teleoperador where teleoperador_user = '".$_GET['txtAsignado']."'  ";
	$resultados_agente= sqlsrv_query($conn_agente, $sql_agente); 
	if ($resultados_agente == FALSE) die(FormatErrors(sqlsrv_errors())); 	//Error handling 
	$row_agente = sqlsrv_fetch_array($resultados_agente, SQLSRV_FETCH_ASSOC);

	# envio de correo al agente

	$url = "http://".$servidor."/casos_coord/agente.php";
	############## Envio del formulario por email  ################
	header("Content-Type: text/html;charset=utf-8");
	$asunto = "Nuevo Caso Especial Asignado - Socio ".$_GET['txtSocio']." ";	

	$comentarios = "Estimado (a): ".$row_agente['Teleoperador_Descripcion']." <br> Un nuevo caso especial para coordinar ha sido asignado a usted. ";
	
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
	 <u>Telefonos:</u> '.$_GET['txtTelefonos'].'  <br> 
	 <u>Comentarios:</u> '.$_GET['txtDescripcion'].' <br>
	 <u>Fecha Agenda Tigo:</u> '.$_GET['txtFechaAgendaT'].' <br>
	 <u>Agente:</u> '.$row_agente['Teleoperador_Descripcion'].' <br>
	 <b> Puede ingresar <a href="'.$url.'" target="_blank"> -> AQUI <- </a> al sistema web mediante el siguiente <a href="'.$url.'" target="_blank"> link </a>y actualizar el estado de la gestión. </b><br>
	</p> 
	<hr />
	</body> 
	</html> 
	'; 					

	$de = 'sistemasweb@unoauno.net';
	$para = $row_agente['teleoperador_email'];	
	$copia = 'coordinacion_supervision@unoauno.net'; 
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
  <title>Agregar - Casos Especiales - </title>
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
		<h3 align='center'> Agregar Nuevo Registro </h3>
	</div>
	<hr/> <!-- ___________________________________________________________________ -->

	<div class="container mt-3">
		<?php
		if ($procesado == false)
			{
			?>
			<div class="container">			
				<form action="agregar.php">
					
					<div class="form-group">
						<label for="txtSocio">Socio:</label>
						<input type="text" class="form-control form-control-sm" id="txtSocio" placeholder="# Socio" name="txtSocio" required>
					</div>					
					
					<div class="form-group">
						<label for="txtTelefonos">Telefonos:</label>
						<input type="text" class="form-control form-control-sm" id="txtTelefonos" placeholder="# Telefonos" name="txtTelefonos">
					</div>
					
					<div class="form-group">
						<label for="txtDescripcion">Descripcion:</label>
						<textarea class="form-control form-control-sm" rows="2" id="txtDescripcion" name="txtDescripcion"></textarea>
					</div>				
					
					<div class="form-group">
						<label for="txtFechaAgendaT">Fecha Agenda Tigo:</label>
						<input type="date" class="form-control form-control-sm" id="txtFechaAgendaT" placeholder="# Telefonos" 
						name="txtFechaAgendaT" value="<?php $fechaAgenda = date("Y-m-d"); echo $fechaAgenda;  ?>">
					</div>
					

					<div class="form-group ">
					<label for="txtAsignado" >Agente</label>						
							<select class="form-control form-control-sm" id="txtAsignado" name="txtAsignado" required>
								<option value="" selected>Seleccione un agente..</option>
								<?php while ($row_cbo_agente = sqlsrv_fetch_array($resultados_cbo_agente, SQLSRV_FETCH_ASSOC)) { 
								echo "<OPTION VALUE=".$row_cbo_agente['teleoperador_user']."> ".$row_cbo_agente['Teleoperador_Descripcion']." </OPTION>";
								}  
								sqlsrv_free_stmt($resultados_cbo_agente); 
								sqlsrv_close( $conn_cbo_agente );	?>			
							</select>
					</div>
					
					
					<button type="submit" class="btn btn-primary">Agregar</button>
				</form>
			</div>
			
			<?php
			}
			else
			{
				echo '
				<div class="alert alert-success  alert-dismissible ">
					<button type="button" class="close btn-sm" data-dismiss="alert">&times;</button>
						Registro agregado correctamente. Puede cerrar la ventana.</strong>
				</div>
				';

				echo '<br>
				<div class="alert alert-info ">				
						Socio:<strong> '.$_GET['txtSocio'].' </strong>
					<br>Telefonos:<strong> '.$_GET['txtTelefonos'].' </strong>
					<br> Descripcion:<strong>'.$_GET['txtDescripcion'].'</strong>
					<br>Fecha:<strong> '.$_GET['txtFechaAgendaT'].'</strong>
					<br>Agente: <strong>'.$_GET['txtAsignado'].'</strong>
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
