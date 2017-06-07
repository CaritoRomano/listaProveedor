/* TABLA ARTICULOS ADMIN*/
$(document).on("submit", ".form-archivo", function(e){
	e.preventDefault();
	var formulario=$(this);
	var nombreForm=$(this).attr("id");

	if(nombreForm=="f-cargar-lista"){   //Admin
                            var miurl="actualizarLista"; 
                            var divResult="notif-carga-excel";
                            var campoVacio = "archivo";
                            var tabla="tablaArticulosAdmin"}
    if(nombreForm=="pedir"){            //Cliente
                            var miurl=formulario.attr('action');
                            var divResult="mensaje_pedir";
                            var campoVacio = "descrip";
                            var campoCant = "cant";
                            var datosPedido = "datosPedido"}
    if(nombreForm=="modif_cant"){       //Cliente
                            var miurl=formulario.attr('action');
                            var divResult="mensaje_modif";
                            var campoVacio = "descripPedido";
                            var campoCant = "cantPedido";
                            var datosPedido = "datosPedido"}                      
	//datos cargados en el formulario
	var formData = new FormData($("#"+nombreForm+"")[0]);
	
	//peticion ajax
	$.ajax({
        type: 'POST',
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
		url: miurl,
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
			document.getElementById(campoVacio).value = "";
            document.getElementById(campoCant).value = 1;
			$("#"+tabla+"").html(data.tabla); //para Admin que no usa DataTables
            $("#datosPedido").html(data.datosPedido); 
            if(nombreForm == "modif_cant"){   
			    listar_art_pedidos_cliente();
            };
            //HACER REFACTORING
            if(nombreForm == "pedir"){
                $("#f_agregar_art_pedido").slideUp();
                $("#mensaje_pedir").slideDown("slow");  
            }
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
/* FIN TABLA ARTICULOS ADMIN*/

/* DATATABLES CLIENTE*/
$(document).ready(function(){
    listar_art_cliente();
    listar_art_pedidos_cliente();
    eliminar_art_pedido();
}); 

var idioma_esp = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ artículos",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando artículos del _START_ al _END_ de un total de _TOTAL_ artículos",
    "sInfoEmpty":      "Mostrando artículos del 0 al 0 de un total de 0 artículos",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ artículos)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
}
//todos los arituculos disponibles para pedir
var listar_art_cliente = function(){ 
    $("#f_agregar_art_pedido").slideUp("slow"); 

    var table = $('#tablaArticulosCliente').DataTable({
        "language": idioma_esp,
        "destroy":true,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "method": "POST", 
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "url": "../home/tabla", 
        },
        "columns": [
            {data: 'codProveedor'},
            {data: 'codArticulo'},
            {data: 'descripcion'},
            {data: 'precio'},   
            {defaultContent: "<button type='button' class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-share-alt'></span></button>"},
        ]
    });

    configurar_pedir("#tablaArticulosCliente tbody", table);
}

var configurar_pedir = function(tbody, table){
    $(tbody).on("click", "button.pedir", function(){
        var data = table.row($(this).parents("tr")).data(); 
        var descripcion = $('#descrip').val(data.descripcion),
         codProveedor = $('#codProveedor').val(data.codProveedor),
         codArticulo = $('#codArticulo').val(data.codArticulo),
         cant = $('#cant').val(1);

        $("#f_agregar_art_pedido").slideDown("slow"); 
        $("#mensaje_pedir").slideUp("slow"); 
    });
}    

/* FIN DATATABLES CLIENTE */

/* DATATABLES PEDIDOS CLIENTE */
var configurar_mod_art_pedido = function(tbody, table){
    $(tbody).on("click", "button.modif_art_pedido", function(){
        var data = table.row($(this).parents("tr")).data(); 
        var descripcion = $('#descripPedido').val(data.descripcion),
         codProveedor = $('#codProveedorPedido').val(data.codProveedor),
         codArticulo = $('#codArticuloPedido').val(data.codArticulo),
         cant = $('#cantPedido').val(data.cant);

        $("#f_mod_cant_art_pedido").slideDown("slow");
        $("#mensaje_modif").slideUp("slow"); 
    });
} 

var configurar_elim_art_pedido = function(tbody, table){
    $(tbody).on("click", "button.elim_art_pedido", function(){
        var data = table.row($(this).parents("tr")).data();
        var idDetalle = $('#idDetalle').val(data.id),
            idPedido = $('#idPedido').val(data.idPedido)
    });
} 

var eliminar_art_pedido = function(){
    $('#b_elim_art_pedido').on("click", function(){
        var idDetalle = $("#idDetalle").val(),
            idPedido = $("#idPedido").val();
        $.ajax({
            method: "DELETE", 
            url: '../eliminarPedido/' + idPedido + '/' + idDetalle,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data){
            $("#mensaje_modif").html(data.mensaje);
            listar_art_pedidos_cliente(); 
            $("#datosPedido").html(data.datosPedido); 
        });

    });
}
  /*  function listar_art_pedidos_cliente($id){
        $('#tablaArtPedidosCliente').DataTable({ 
        "language": idioma_esp,
        "destroy":true,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "method": "POST", 
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            "data": {"id" : $id }, 
            "url": "../home/tablaPedidos", 
        },
        "columns": [
            {data: 'codProveedor'},
            {data: 'codArticulo'},
            {data: 'cant'},
            {data: 'precio'},   
            {defaultContent: "<button type='button' class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-pencil'></span></button>"}, 
            {defaultContent: "<button type='button' class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-minus'></span></button>"},
        ]
    });
}
 FIN DATATABLES PEDIDOS CLIENTE */


/* CERRAR Y ANULAR PEDIDO 
function cerrarPedido(idPedido){ //no terminado
    $.ajax({
        method: "POST", 
        url: 'cerrarPedido/' + idPedido,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function(data){
        $("#mensaje_modif").html(data.mensaje);
    });
};

function anularPedido(idPedido){
    $.ajax({
        method: "POST", 
        url: 'anularPedido/' + idPedido,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    }).done(function(data){
        $("#mensaje_modif").html(data.mensaje);
    });
};  FIN CERRAR Y ANULAR PEDIDO */