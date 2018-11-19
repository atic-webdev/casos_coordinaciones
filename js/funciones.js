// Funciones javascript

function popup(url,ancho,alto) 
{
	var posicion_x; 
	var posicion_y; 
	posicion_x=(screen.width/2)-(ancho/2); 
	posicion_y=(screen.height/2)-(alto/2); 
	window.open(url,'', 'width='+ancho+',height='+alto+',menubar=0,toolbar=0,directories=0,scrollbars=no,resizable=no,left='+posicion_x+',top='+posicion_y+''); 				
}


function checkParameters()
{
	var username = $.trim($("#username").val());
	var password = $.trim($("#password").val());
	var databasename = $.trim($("#databasename").val());
	var hostname = $.trim($("#hostname").val());
	
	if (username == ""){
		alert("Por favor, ingrese el username");return false;
	}
	else if (password == ""){
		alert("Por favor, ingrese el sql-password.");return false;
	}
	else if (databasename == ""){
		alert("Por favor, ingrese el sql-database-name.");return false;
	}
	else if (hostname == ""){
		alert("Por favor, ingrese el hostname.");return false;
	}
	else if($("#restore").is(':checked')){
		var filename = $(".restoreFile").val();
		if(filename == ""){
			alert("Por favor, escoja un archivo.");return false;
		}
		else{
			var valid_extensions = /(\.db|\.sql)$/;   
			if (!valid_extensions.test(filename)){ 
				alert('Formato de archivo Invalido.');return false;
			}                   
		}
	}
	else{
		return true;
	}
}

	
function showHideBkup(id){
	if (id == "backup"){
		$(".backupRadio").show();
		$(".restoreFile").hide();
	}
	else{
		$(".backupRadio").hide();
		$(".restoreFile").show();
	}
}

	
function showHideRadio(id){
	if (id == "seccionA"){   // id del objeto id="seccionA"
		$(".grupoA").show(); //  <div class="grupoA">
		$(".grupoB").hide(); //  <div class="grupoB">
	}
	else{
		$(".grupoA").hide(); //  <div class="grupoA">
		$(".grupoB").show(); //  <div class="grupoB">
	}
}
		
	
	
function valida_num(e)
{
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8)
	{
        return true;
    }
    
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}