/* TABLA ARTICULOS */
$(document).on("submit", ".form-archivo", function(e){
	e.preventDefault();
	var formulario=$(this);
	var nombreForm=$(this).attr("id");

	if(nombreForm=="f-cargar-lista"){ var miurl="actualizarLista"; var divResult="notif-carga-excel"}
	//informacion del formulario
	var formData = new FormData($("#"+nombreForm+"")[0]);
	
	//peticion ajax
	$.ajax({
		url: miurl,
		type: 'POST',

		//Form data, datos del formulario
		data: formData,
		//necesario para subir archivos via ajax
		cache: false,
		contentType: false,
		processData: false,
		//mientras enviamos el archivo
		beforeSend: function(){
			$("#"+divResult+"").html($("#cargador").html());
		},
		//una vez finalizado correctamente
		success: function(data){
			$("#"+divResult+"").html(data.mensaje);
			document.getElementById('archivo').value = "";
			$("#tablaArticulos"+"").html(data.tabla); 
			//$("#algo").attr('src', $("#algo").attr('src') + '?' + Math.random());
		},
		//si ha ocurrido un error
		error: function(data){
			var errors = data.responseJSON;
                if (errors) {
                    $.each(errors, function (i) {
                        console.log(errors[i]);
                    });
                }
			alert("No se pudo cargar");
		}
	});
});
/* FIN TABLA ARTICULOS */


/* MENU LATERAL CLIENTE */

/* FIN MENU LATERAL CLIENTE */