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
$servidor = $_SERVER['HTTP_HOST'];

#MySQL
# require("conn/conexion_mysql.php");
#SQLServer
require("conn/conexion.php");
require('info.php');

// session_start();
$cookie_u= "usuario";
$cookie_n= "nombre";
$cookie_p= "perfil";
$cookie_c= "correo";
#asigno los valores de las cookies a las variables 
$usuario = $_COOKIE[$cookie_u];	
$nombre  = $_COOKIE[$cookie_n];
$perfil  = $_COOKIE[$cookie_p];	
$correo  = $_COOKIE[$cookie_c];


# ------------------------- Autenticacion / Sesion expirada  --------------------------------
if ($usuario == "")
{
	echo "<div class=\"warning-box\" align='center'><br><br><br><br><br><br><h2> Usuario no Autenticado  o la Sesion Expiro! </h2>  <br> </div>\n";
	$MensajeValidacion = "Usuario no Autenticado  o la Sesion Expiro!";
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
# ------------------------- Autenticacion / Sesion expirada  --------------------------------


# --------------------------Queries-------------------------
$conn_agente = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_agente= " SELECT * FROM   casos_especiales where estado = 'Asignada' AND agente_asignado = '".$usuario."' ";
$resultados_agente= sqlsrv_query($conn_agente, $sql_agente); 
$row_agente = sqlsrv_fetch_array($resultados_agente, SQLSRV_FETCH_ASSOC);


# --------------------------Queries End-------------------------

#  --------------------- Refrescar la pagina cada x Min -------------------------
$pagina = "agente.php"; 
?>	
<!-- Refrescar pagina cada: mil milisegundos = 0.10 segundos = 1/10 min-->	
<script LANGUAGE="JavaScript">
	var pagina="<?php echo $pagina; ?>"
	minutos=5
	var tiempo = (60000*minutos)
	function redireccionar() 
	{
		location.href=pagina
	} 
	setTimeout ("redireccionar()", tiempo);
</script>			
<?php	
#  --------------------- Refrescar la pagina cada x Min -------------------------


###############################################################
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title>Modulo Agente - Casos Especiales - </title>
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
  
	<style>
		#sombra {
				border: 0px solid;
				padding: 2px;
				box-shadow: 5px 8px 18px #888888;
		}
		#sombratexto {				
				text-shadow: 2px 2px 1px  #888888;
		}
	</style>
</head>
<body>

<hr/> <!-- ___________________________________________________________________ -->

<div class="row">
    <div class="col">
				<span class="float-right" class="bg-primary text-white">
					<a href="<?php echo $paginaactual;?>" data-toggle="tooltip" title="Casos Especiales Coordinaciones">
					<div id="sombra"><img src="img/uau_logo.jpg" class="rounded" alt="Uno a uno" width="50" height="50"></div> 
					</a>
				</span>
    </div>
    <div class="col-6">
			<h1 align='center'> Agente <br> <?php echo $nombre_proyecto; ?> </h1>		
    </div>
    <div class="col">
			<span class="float-center">
				<a href="index.php"> <i style="font-size:25px" class="fa">&#xf08b;</i><br>Salir </a>
			</span>
		</div>
  </div>

<hr/>


<div class="container mt-3">
	<span class="float-center">						
		Autenticado como: <strong>   (<?php echo $usuario.")";   ?></strong>  <br><br>
	</span>
	<?php
			if ( ($perfil == "TeamLeader") OR ($perfil == "Supervisor") )
			{
				echo '
				
				<span class="float-center">						
				<a href="supervisor.php"  data-toggle="tooltip" title="Modulo Supervisor"> <i class="fa fa-user-circle" style="font-size:24px"></i><br></a><br>
				</span>
				
				';

			}				
	?>
	
  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#agentes"><i style="font-size:26px" class="fa">&#xf007;</i> <?php echo $nombre; ?> </a>
    </li>     
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
  
<!-- ********************************TAB agentes ********************************************  -->  
	<div id="agentes" class="container tab-pane active"><br>
		 <?php
		 # validar si hay registros para mostrar en la pagina
		 $rows = sqlsrv_has_rows( $resultados_agente );
		 if ($rows === true)			
			{
		 ?>
      
	  <!-- -->
		<div class="container">            
		  <div class="table-responsive"> 		    
		  <table class="table table-striped table-hover table-bordered table-sm">
			<thead>
			  <tr>
				<th class="table-primary"># Socio</th>
				<th class="table-info">Teléfonos</th>
				<th class="table-primary">Descripción</th>
				<th class="table-info">Fecha Agenda Tigo</th>
				<th class="table-primary">Fecha Creación</th>	
				<th class="table-info">Asignado a</th>			
				<th class="table-primary">Estado</th>
				<th class="table-info"> </th>
			  </tr>
			</thead>
				<tbody>	
					<?php 
					do
					{
						?>		  
					<tr>
						<td><a href="http://10.7.57.141/SIGA-TG/servlet/entitymanagercliente?DSP,10,<?php echo $row_agente['socio']; ?>,OrdenSrv" target="_blank">
							<?php echo $row_agente['socio']; ?></a></td>
						<td><?php echo $row_agente['telefonos']; ?></td>
						<td><?php echo $row_agente['descripcion']; ?></td>
						<td><?php echo $row_agente['fecha_agenda']->format('d-M-Y'); ?></td>
						<td><?php echo $row_agente['fecha_creacion']->format('d-M-Y H:i:s'); ?></td>
						<td><?php echo $row_agente['agente_asignado']; ?></td>
						<td><?php echo $row_agente['estado']; ?></td>
						<td><a href="JavaScript:popup('finalizar.php?finalizar=<?php echo $row_agente['id']; ?>',725,550)"  data-toggle="tooltip" title="Finalizar"><i class="fa fa-check-square-o" style="font-size:32px"></i></a></td>					
					</tr>	
					<?php } while ($row_agente = sqlsrv_fetch_array($resultados_agente, SQLSRV_FETCH_ASSOC)) ?>	  		  
				</tbody>

		 </table>  		

		 </div> 
		 <?php
		} # FIN if  validar si hay registros para mostrar en la pagina
		else
		{
			echo '
			<div class="alert alert-warning" role="alert">
				No se encuentran registros asignados a: <strong> '.$nombre .'</strong><br> <u> Consulte con su supervisor o encargado.</u>
			</div>
		';
		}	
		sqlsrv_free_stmt($resultados_agente);  
		sqlsrv_close( $conn_agente );

		?>
		</div>
	  <!-- -->	  
	  <br>
	  <!-- -->
	  
    </div>
	<!-- ******************************** FIN TAB agentes ********************************************  -->  	
	<div class="container">
		<span class="float-center">						
			Ultima Actualizacion. : <strong> <?php $ultimaAct = date('d-m-Y H:i:s'); echo $ultimaAct;  ?></strong>  <br><br>
		</span>
	</div>
<hr/>

<!-- *********************** footer ****************************  -->
<div class="row">
    <div class="col">
				
    </div>
    <div class="col-6">	
			<span class="float-center">	
				<div id="sombratexto"><b> <?php echo $compania." - ".$departamento." - ";   ?> </b> Version: <?php echo $version;   ?>  © 2018. </div>
			</span>
    </div>
    <div class="col">
			<span class="float-right">
			<a href="JavaScript:popup('about.php',725,550)"  data-toggle="tooltip" title="Acerca de.."><i class="fa fa-info-circle" style="font-size:32px"></i></a>
			</span>
		</div>
  </div>

<!-- *********************** footer ****************************  -->
</body>
</html>
