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
			$("#"+tabla+"").html(data.tabla); //para Admin que no usa DataTables
            $("#datosPedido").html(data.datosPedido); 
            if(nombreForm == "modif_cant"){   
                document.getElementById(campoCant).value = 1;
			    listar_art_pedidos_cliente();
                listar_art_cambio_precio();
            };
            //HACER REFACTORING
            if(nombreForm == "pedir"){
                document.getElementById(campoCant).value = 1;
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
    listar_art_cambio_precio();
    eliminar_art_pedido();

    cerrar();
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
    "sSearch":         "Búsqueda general:",
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
        "responsive": true,
        "autoWidth": false,
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
            {data: 'codArticulo', responsivePriority: 1 },
            {data: 'descripcion', responsivePriority: 1},
            {data: 'fabrica', responsivePriority: 1},
            {data: 'rubro', responsivePriority: 1},
            {data: 'precio', responsivePriority: 1},   
            {defaultContent: "<button type='button' class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-share-alt'></span></button>",
                 responsivePriority: 1},
        ],
        "dom": "<'row' <'form-inline' <'col-sm-1'f>>>"
                     +"<rt>"
                     +"<'row'<'form-inline'"
                     +" <'col-sm-6 col-md-6 col-lg-6'l>"
                     +"<'col-sm-6 col-md-6 col-lg-6'p>>>"

    });

    table.columns().every( function () {
        var that = this; 
        $( 'input', this.footer() ).on('keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });

    configurar_pedir("#tablaArticulosCliente tbody", table);
}

var configurar_pedir = function(tbody, table){
    $(tbody).on("click", "button.pedir", function(){
        var data = table.row($(this).parents("tr")).data(); 
        var descripcion = $('#descrip').val(data.descripcion),
         codFabrica = $('#codFabrica').val(data.codFabrica),
         codArticulo = $('#codArticulo').val(data.codArticulo),
         cant = $('#cant').val(1);

        $("#f_agregar_art_pedido").slideDown("slow"); 
        $("#mensaje_pedir").slideUp("slow"); 
    });
}    

/* FIN DATATABLES CLIENTE */

/* DATATABLES PEDIDOS CLIENTE */
var listar_art_pedidos_cliente = function(){ 
    $("#f_mod_cant_art_pedido").slideUp("slow"); 
    $("#mensaje_modif").slideDown("slow"); 

    var idPedido = $('#idPedido').data("field-id");

    var tablePedidos = $('#tablaArtPedidosCliente').DataTable({ 
        "language": idioma_esp,
        "responsive": true,
        "autoWidth": false,
        "destroy":true,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "method": "POST", 
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            "data": {"id" : idPedido}, 
            "url": "../home/tablaPedidos", 
        },
        "columns": [
            {data: 'codArticulo'},
            {data: 'descripcion', name: 'lista.descripcion'},
            {data: 'fabrica', name: 'lista.fabrica'},
            {data: 'cant'},
            {data: 'precio'}, 
            {data: 'importe', searchable: false},  
            {defaultContent: "<button type='button' class='modif_art_pedido btn btn-default btn-sm'><span class='glyphicon glyphicon-pencil'></span></button> <button type='button' class='elim_art_pedido btn btn-default btn-sm' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-minus'></span></button>"},
        ],
        "dom": "<'row'<'form-inline' <'col-sm-offset-11'B>>>"
                 +"<'row' <'form-inline' <'col-sm-1'f>>>"
                 +"<rt>"
                 +"<'row'<'form-inline'"
                 +" <'col-sm-6 col-md-6 col-lg-6'l>"
                 +"<'col-sm-6 col-md-6 col-lg-6'p>>>",// 'Bfrtip',
        "buttons": [ {
            extend: 'excelHtml5',
            text: 'Excel',//"<i class='fa fa-file-excel-o'></i>",
            titleAttr: 'Excel'
           } ]
    });

    tablePedidos.columns().every( function () {
        var that = this; 
        $('input', this.footer() ).on('keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });

    configurar_mod_art_pedido("#tablaArtPedidosCliente tbody", tablePedidos);
    configurar_elim_art_pedido("#tablaArtPedidosCliente tbody", tablePedidos);
};

var configurar_mod_art_pedido = function(tbody, table){
    $(tbody).on("click", "button.modif_art_pedido", function(){
        var data = table.row($(this).parents("tr")).data(); 
        var descripcion = $('#descripPedido').val(data.descripcion),
         idDetalle = $('#idDetalle').val(data.id),
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
            $("#datosPedido").html(data.datosPedido); 
            listar_art_pedidos_cliente();
        });

    });
};
/*FIN DATATABLES PEDIDOS CLIENTE */


/* CERRAR Y ANULAR PEDIDO */
var cerrar = function(){ 
    $('.cerrar_pedido').on("click", function(e){
        var idPedido = ($(this).parents("tr").attr('id'));
        $.ajax({
            method: "POST", 
            url: 'pedido/cerrarPedido/' + idPedido,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data){
            if(data.muestroModal == 1){
                $("#myModal").modal();
                $("#mod_pedido").attr('href', 'detalle/cambioPrecios/' + idPedido);
                $("#tablaPreciosDistintos").html(data.tabla);
           /* $("#idPedidoModal").attr('data-field-id', idPedido); */
                $("#cont_pedido").attr('href', 'pedido/enviarPedido/' + idPedido);
            }else{
                window.location.href = 'pedido/enviarPedido/' + idPedido;
            }
        });

    });
};
/*var continuarEnvio = function(){ 
    $('.continuar_envio').on("click", function(e){
        var idPedido = $("#idPedidoModal").attr('data-field-id');
        $('#myModal').modal('hide');
        $.ajax({
            method: "GET", 
            url: 'pedido/enviarPedido/' + idPedido,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data){
            
        });

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


/* DATATABLES PEDIDOS CLIENTE SOLO CAMBIO DE PRECIOS*/
var listar_art_cambio_precio = function(){ 
    $("#f_mod_cant_art_pedido").slideUp("slow"); 
    $("#mensaje_modif").slideDown("slow"); 

    var idPedido = $('#idPedido').data("field-id");

    var tableCambioPrecios = $('#tablaArtCambioPrecio').DataTable({ 
        "language": idioma_esp,
        "responsive": true,
        "autoWidth": false,
        "destroy":true,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "method": "POST", 
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, 
            "data": {"id" : idPedido}, 
            "url": "../../home/tablaCambioPrecios", 
        },
        "columns": [
            {data: 'codArticulo'},
            {data: 'descripcion', name: 'lista.descripcion'},
            {data: 'fabrica', name: 'lista.fabrica'},
            {data: 'cant'},
            {data: 'precio'}, 
            {data: 'precioLista', searchable: false},  
            {defaultContent: "<button type='button' class='modif_art_pedido btn btn-default btn-sm'><span class='glyphicon glyphicon-pencil'></span></button> <button type='button' class='elim_art_pedido btn btn-default btn-sm' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-minus'></span></button>"},
        ],
        "dom": "<'row'<'form-inline' <'col-sm-offset-11'B>>>"
                 +"<'row' <'form-inline' <'col-sm-1'f>>>"
                 +"<rt>"
                 +"<'row'<'form-inline'"
                 +" <'col-sm-6 col-md-6 col-lg-6'l>"
                 +"<'col-sm-6 col-md-6 col-lg-6'p>>>",// 'Bfrtip',
        "buttons": [ {
            extend: 'excelHtml5',
            text: 'Excel',//"<i class='fa fa-file-excel-o'></i>",
            titleAttr: 'Excel'
           } ]
    });

    tableCambioPrecios.columns().every( function () {
        var that = this; 
        $('input', this.footer() ).on('keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });

    configurar_mod_art_pedido("#tablaArtCambioPrecio tbody", tableCambioPrecios);
    configurar_elim_art_pedido("#tablaArtCambioPrecio tbody", tableCambioPrecios);
};

/* FIN DATATABLES PEDIDOS CLIENTE SOLO CAMBIO DE PRECIOS*/