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
	echo "<div class=\"warning-box\" align='center'><br><br><br><br><br><br><h2> Usuario no autenticado  o la sesion expiro! </h2>  <br> </div>\n";
	$MensajeValidacion = "Usuario no autenticado  o la sesion expiro!";
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


# ------------------------- Permisos solo a Supervisor --------------------------------
if ($perfil == "Agente")
{
	echo "<div class=\"warning-box\" align='center'><br><br><br><br><br><br><h2> Usuario No tiene Permisos para este modulo! </h2>  <br> </div>\n";
	$MensajeValidacion = "Usuario No tiene Permisos para este modulo!";
	?>
			<script type="text/javascript">
				window.onload=function()
				{
				 alert('<?php echo $MensajeValidacion; ?>');
				}	
			</script>
			
			<script LANGUAGE="JavaScript">
			var pagina="agente.php"
			function redireccionar() 
			{
				location.href=pagina
			} 
			setTimeout ("redireccionar()", 500);
			</script>
			
			<?php		
	exit;
}
# ------------------------- Permisos solo a Supervisor --------------------------------


###############################################################

# --------------------------Queries-------------------------
$conn1 = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_asig= " SELECT * FROM   casos_especiales WHERE estado != 'Finalizada' ";
$resultados_asig= sqlsrv_query($conn1, $sql_asig); 
$row_asig = sqlsrv_fetch_array($resultados_asig, SQLSRV_FETCH_ASSOC);


$conn2 = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_final= " SELECT * FROM   casos_especiales WHERE estado = 'Finalizada' AND fecha_finalizado >= (getdate()-1) ";
$resultados_final= sqlsrv_query($conn2, $sql_final); 
$row_final = sqlsrv_fetch_array($resultados_final, SQLSRV_FETCH_ASSOC);
#############################################################################
$conn11 = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_asig1= " SELECT * FROM   casos_especiales WHERE estado != 'Finalizada' ";
$resultados_asig1= sqlsrv_query($conn11, $sql_asig1); 
$row_asig1 = sqlsrv_fetch_array($resultados_asig1, SQLSRV_FETCH_ASSOC);


$conn22 = conexion_bd($servidor_bd, $usuario_bd, $password_bd, $basedatos); 
$sql_final2= " SELECT * FROM   casos_especiales WHERE estado = 'Finalizada' AND fecha_finalizado >= (getdate()-1)  ";
$resultados_final2= sqlsrv_query($conn22, $sql_final2); 
$row_final2 = sqlsrv_fetch_array($resultados_final2, SQLSRV_FETCH_ASSOC);
# --------------------------Queries End-------------------------

#  --------------------- Refrescar la pagina cada x Min -------------------------
$pagina = "supervisor.php"; 
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
  <title>Modulo Supervisor - Casos Especiales - </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- font fa -->
  <link href="font-awesome/css/font-awesome.css" rel="stylesheet">  
  <!-- CSS & JS-->
  <link rel="stylesheet" href="css/bootstrap.min.css">  
  <script src="popper/1.14.3/popper.min.js"></script>
  <script src="jquery/3.3.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/funciones.js"></script>
  
  <script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
  </script>
  <!--
<script src="popper/1.14.3/popper.min.js"></script>
<script src="popper/tooltip/tooltip.js"></script>  
<script src="https://unpkg.com/popper.js"></script>
<script src="https://unpkg.com/tooltip.js"></script>
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
					<a href="<?php echo $paginaactual;?>" data-toggle="tooltip" >
					<div id="sombra"><img src="img/uau_logo.jpg" class="rounded" alt="Uno a uno" width="50" height="50"></div> 
					</a>
				</span>
    </div>
    <div class="col-6">
		<h1 align='center'> Supervisor <br> <?php echo $nombre_proyecto; ?> </h1>
    </div>
    <div class="col">
			<span class="float-center">
				<a href="index.php"><i style="font-size:25px" class="fa">&#xf08b;</i><br>Salir </a>
			</span>
		</div>
  </div>

<hr/>
<!-- boton Nuevo  -->
<div class="container" align="center"> 
	<a href="JavaScript:popup('agregar.php',725,600)"  data-toggle="tooltip" title="Nuevo"> <i class="fa fa-plus-circle" style="font-size:48px"></i><br></a>
</div>

<div class="container mt-3">
	<span class="float-center">						
		Autenticado como: <strong> <?php echo $usuario; echo "(".$nombre.")";   ?></strong>  <br><br>
	</span>
	<?php
			if ( ($perfil == "TeamLeader") OR ($perfil == "Supervisor") )
			{
				echo '
				
				<span class="float-center">						
				<a href="agente.php"  data-toggle="tooltip" title="Modulo Agentes"> <i class="fa fa-user" style="font-size:24px"></i><br></a><br>
				</span>
				
				';

			}				
	?>


  <!----------------------------------- Nav tabs -------------------------------->
  <ul class="nav nav-tabs">
	
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#pendientes">Pendientes <i style="font-size:24px" class="fa">&#xf110;</i> </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#finalizadas">Finalizadas <i style="font-size:24px" class="fa">&#xf046;</i> </a>
    </li>    
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">

  
<!-- ********************************TAB pendientes ********************************************  -->  
	<div id="pendientes" class="container tab-pane active"><br>
			
		
	  <!-- -->
		<div class="container">            
		  <div class="table-responsive"> 		    
		  <table class="table table-striped table-hover table-bordered table-sm">
			<thead>
			  <tr>
				<th class="table-primary"># Socio</th>
				<th class="table-primary">Telefonos</th>
				<th class="table-primary">Descripcion</th>
				<th class="table-primary">Fecha Agenda Tigo</th>
				<th class="table-primary">Fecha Creacion</th>	
				<th class="table-primary">Asignado a</th>			
				<th class="table-primary">Estado</th>
				<th class="table-info">Editar</th>
			  </tr>
			</thead>
			<tbody>	
				<?php 
				// validar si hay registros para mostrar en la pagina
				$row_asig1 = sqlsrv_has_rows( $resultados_asig1 );
				if ($row_asig1 === true)			
				{

					do
					{
						?>					  
						<tr>
								<td><a href="http://10.7.57.141/SIGA-TG/servlet/entitymanagercliente?DSP,10,<?php echo $row_asig['socio']; ?>,OrdenSrv" target="_blank">
									<?php echo $row_asig['socio']; ?></a></td>
								<td><?php echo $row_asig['telefonos']; ?></td>
								<td><?php echo $row_asig['descripcion']; ?></td>
								<td><?php echo $row_asig['fecha_agenda']->format('d-M-Y'); ?></td>
								<td><?php echo $row_asig['fecha_creacion']->format('d-M-Y H:i:s'); ?></td>
								<td><?php echo $row_asig['agente_asignado']; ?></td>
								<td><?php echo $row_asig['estado']; ?></td>
								<td><a href="JavaScript:popup('editar.php?editar=<?php echo $row_asig['id']; ?>',725,550)"  data-toggle="tooltip" title="Editar"><i class="fa fa-edit" style="font-size:32px"></i></a></td>					
						</tr>	
				<?php 
						} while ($row_asig = sqlsrv_fetch_array($resultados_asig, SQLSRV_FETCH_ASSOC)) ;	
				} # FIN if  validar si hay registros para mostrar en la pagina
				else
				{
					echo ' <div class="alert alert-warning" role="alert"> No se encuentran Registros de casos <strong>Pendientes</strong>. 	</div> ' ;
				}			
						
				?>	  		  
			</tbody>
		 </table>  		
		 </div> 
		 <?php		
				
			sqlsrv_free_stmt($resultados_asig);  
			sqlsrv_close( $conn1 );
			sqlsrv_free_stmt($resultados_asig1);  
			sqlsrv_close( $conn11 );
		?>
		</div>		
	  <!-- -->	  
	  <br>
	  <!-- -->	  
    </div>
	<!-- ******************************** FIN TAB pendientes ********************************************  -->  
	
	
<!-- ********************************TAB finalizadas ********************************************  -->  
<div id="finalizadas" class="container tab-pane "><br>
     
		<?php
		 
		 ?>
		 <!-- -->
		 <div class="container">            
			 <div class="table-responsive"> 		    
			 <table class="table table-striped table-hover table-bordered table-sm">
			 <thead>
				 <tr>
					<th class="table-dark"># Socio</th>
					<th class="table-dark">Telefonos</th>
					<th class="table-dark">Descripcion</th>
					<th class="table-dark">Fecha Agenda Tigo</th>
					<th class="table-dark">Fecha Creacion</th>	
					<th class="table-dark">Fecha Finalizado</th>
					<th class="table-dark">Finalizado por</th>
					<th class="table-dark"> </th>						 
				 </tr>
			 </thead>
			 <tbody>
			 <?php 
				# validar si hay registros para mostrar en la pagina
				$row_final2 = sqlsrv_has_rows( $resultados_final2 );
				if ($row_final2 == true)			
				{

				do
				{
					?>			  
				 <tr>
				 	<td><a href="http://10.7.57.141/SIGA-TG/servlet/entitymanagercliente?DSP,10,<?php echo $row_final['socio']; ?>,OrdenSrv" target="_blank">
						<?php echo $row_final['socio']; ?></a></td>
					<td><?php echo $row_final['telefonos']; ?></td>
					<td><?php echo $row_final['descripcion']; ?></td>
					<td><?php echo $row_final['fecha_agenda']->format('d-M-Y'); ?></td>
					<td><?php echo $row_final['fecha_creacion']->format('d-M-Y H:i:s'); ?></td>
					<td><?php echo $row_final['fecha_finalizado']->format('d-M-Y H:i:s'); ?></td>
					<td><?php echo $row_final['agente_asignado']; ?></td>
					<td> <a href="#" data-toggle="tooltip" data-placement="left" title="<?php echo $row_final['bitacora']; ?>" ><i class="fa fa-sticky-note-o" style="font-size:28px"></i></a></td>		

				 </tr>
				 <?php } while ($row_final = sqlsrv_fetch_array($resultados_final, SQLSRV_FETCH_ASSOC)); 
				 } # FIN if  validar si hay registros para mostrar en la pagina
				 else
				 {
					 echo '<div class="alert alert-warning" role="alert">No se encuentran Registros de casos <strong>Finalizados</strong>. </div>';
				 }	
				 
				 ?>			  		  
			 </tbody>
			</table>  		
			</div> 
		</div>
			<?php			
			sqlsrv_free_stmt($resultados_final);  
			sqlsrv_close( $conn2 );
			sqlsrv_free_stmt($resultados_final2);  
			sqlsrv_close( $conn22 );
			?>
		 
		 <!-- -->	  
		 <br>
		 <!-- -->
		 
		 </div>
	 <!-- ******************************** FIN TAB finalizadas ********************************************  -->  
	 <?php
	
	$url = "http://".$servidor."/casos_coord/agente.php";
	?>


	<div class="container">
		<span class="float-center">						
			Ultima Actualizacion. : <strong> <?php $ultimaAct = date('d-m-Y H:i:s'); echo $ultimaAct;  ?></strong>  <br><br>
			<?php //echo '<b> Puede ingresar al sistema web mediante el siguiente link y actualizar el estado de la gestión: <a href="'.$url.'" target="_blank"><i class="fa fa-external-link" style="font-size:24px"></i></a> </b> <br>  '; ?>
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
