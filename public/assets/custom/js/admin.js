var tableAdmin;
var idAnt = 0;
var pathRoot = '/listaProveedor/public/index.php/';
$(document).ready(function(){
    listar_art_admin();
    listar_art_cliente();
    listar_art_pedidos_cliente();
    config_elim_art_pedido();
    listar_art_a_recibir_cliente();
    listar_clientes();

    enviar();
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

/* DATATABLES CLIENTE*/
//todos los articulos disponibles para pedir
var listar_art_cliente = function(){ 
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
            "url": pathRoot + "home/tabla", 
        },
        "columns": [
            {data: 'codArticulo'},
            {data: 'descripcion'},
            {data: 'fabrica'},
            {data: 'rubro'},
            {data: 'precio'},   
            /*{defaultContent: "<button type='button' class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-share-alt'></span></button>"},
            */
            {render: function ( data, type, row ) {
                return "<input id='cant_pedida_" + row.id + "' type='number' value='1' class='col-lg-8 enter_pedir_art widthPedir'> <button type='button' id='cant_pedir_" + row.id + "'class='pedir btn btn-default btn-sm'><span class='glyphicon glyphicon-share-alt'></span></button><div class='aprobado right'><label id='mensaje_" + row.id + "'></label></div>";
             }},
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 5, width: 130 },
            { responsivePriority: 1, targets: 0, width: 100 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: 2 },
            { responsivePriority: 5, targets: 3 },
            { responsivePriority: 6, targets: 4, width: 35 },
        ],
        "dom": "<'row' <'form-inline' <'col-sm-1'f>>>"
                     +"<rt>"
                     +"<'row'<'form-inline'"
                     +" <'col-sm-6 col-md-6 col-lg-6'l>"
                     +"<'col-sm-6 col-md-6 col-lg-6'p>>>"

    });
    //seteo el foco en el filtro codArticulo
    $("#filtro_cod_art").focus();

    //para invitados oculto la columna Pedir
    if (window.location.pathname == pathRoot + 'lista'){
        table.column(5).visible( false );
    }

    table.columns().every( function () {
        var that = this; 
        $( 'input', this.footer() ).on('keyup change', function (e) {
            if (e.keyCode == 46) { /*DELETE limpia el input*/
                $(this).val('');  
            }
            if ( that.search() !== this.value ) { 
                that                                      //busqueda
                    .search( "^"+this.value, true, false )
                    .draw();
                if( $("#filtro_rubro").val() !== ''){  //si filtra por rubro, ordeno por descripcion 
                    table.order( [ 1, 'asc' ] )
                        .draw();
                }else{
                    table.order( [ 0, 'asc' ] )
                        .draw();
                }
            }
        } );
    });
    
    pedir("#tablaArticulosCliente tbody", table);
    enter_input_pedir("#tablaArticulosCliente tbody", table);
}

var pedir = function(tbody, table){
    $(tbody).on("click", "button.pedir", function(e){
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data(),
            id = data.id,
            cantidadPedida = Number($("#cant_pedida_" + data.id).val());
        console.log(data);
        //valido que sea entero y positivo 
        if(Number.isInteger(cantidadPedida) && Math.sign(cantidadPedida) == 1){  
            $(this).parents("tr").css('background-color', "#bfbdc1");//"#f38a8a");  
            $.ajax({
                method: "POST", 
                url: '../pedidoDet', /*store*/
                data: {"codFabrica" : data.codFabrica, "codArticulo" : data.codArticulo, "cant" : cantidadPedida},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data){
                //si ya esta cargado el articulo en el pedido
                if(data.muestroModal == 1) { 
                    $("#idPedidoArtRep").attr('data-field-id', data.datosPedido.id)
                    $("#modalArtRepetido").modal();
                }else{
                    $("#datosPedido").html(data.datosPedido);
                    $("#mensaje_" + idAnt).html("");
                    $("#mensaje_" + id).html("Agregado al pedido");
                    idAnt = id;

                    //document.getElementById("cant_pedida_" + id).value= 1;
                }
            });
        }
    });

    //Modal Articulo Repetido redirecciona a Modificar Pedido
    $('#b_mod_pedido').on("click", function(){
        window.location.href = '../detalle/' + $("#idPedidoArtRep").attr('data-field-id');
    });
}    

var enter_input_pedir= function(tbody, table){
    $(tbody).on("keyup", "input.enter_pedir_art", function(e){
        var data = table.row($(this).parents("tr")).data();
        if (e.keyCode == 13) { /*Enter recibe el articulo*/
            document.getElementById("cant_pedir_" + data.id).click();  
        }
    });
}
/* FIN DATATABLES CLIENTE */

/* DATATABLES PEDIDOS CLIENTE */
var listar_art_pedidos_cliente = function(){ 
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
            {data: 'codArticulo', name: 'pedidoDet.codArticulo'},
            {data: 'descripcion', name: 'lista.descripcion'},
            {data: 'fabrica', name: 'lista.fabrica'},
            {data: 'precio', name: 'lista.precio'}, 
            {data: 'importe', searchable: false},  
            //{defaultContent: "<button type='button' class='modif_art_pedido btn btn-default btn-sm'><span class='glyphicon glyphicon-pencil'></span></button> <button type='button' class='elim_art_pedido btn btn-default btn-sm' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-minus'></span></button>"},
            {render: function ( data, type, row ) {
                return "<input id='cant_modif_" + row.id + "' type='number' value='" + row.cant + "' class='col-lg-6 enter_modif_art'> <button type='button' id='modificar_" + row.id + "' class='modif_art_pedido btn btn-default'><span class='glyphicon glyphicon-share-alt'></span></button> <button type='button' class='elim_art_pedido btn btn-default' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-minus'></span></button><div class='aprobado right'><label id='mensaje_" + row.id + "'></label></div>";
             }},
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 5, width: 130 },
            { responsivePriority: 1, targets: 0, width: 100 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: 2 },
            { responsivePriority: 5, targets: 3 },
            { responsivePriority: 6, targets: 4},
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

    //seteo el foco en el filtro codArticulo
    $("#filtro_cod_art").focus();

    tablePedidos.columns().every( function () {
        var that = this; 
        $('input', this.footer() ).on('keyup change', function (e) {
            if (e.keyCode == 46) { /*DELETE limpia el input*/
                $(this).val('');  
            }
            if ( that.search() !== this.value ) {
                that
                    .search( "^"+this.value, true, false )
                    .draw();  
            }
        } );
    });

    //elimina articulo
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
            if (data.ultimo) {
                window.location.href = pathRoot + 'pedido/lista';
            }else{
                tablePedidos.ajax.reload( function ( json ) {
                    $("#datosPedido").html(data.datosPedido);
                    $("#eliminado").html("Art&iacute;culo eliminado del pedido"); 
                //document.getElementById("cant_modif_" + id).value= 1;
                } );
            }
        });

    });

    modificar("#tablaArtPedidosCliente tbody", tablePedidos);
    enter_input_modificar("#tablaArtPedidosCliente tbody", tablePedidos);
    config_elim_art_pedido("#tablaArtPedidosCliente tbody", tablePedidos);
};

var modificar = function(tbody, table){
    $(tbody).on("click", "button.modif_art_pedido", function(e){
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data(),
            id = data.id,
            cantidadPedida = Number($("#cant_modif_" + data.id).val());
        //valido que sea entero y positivo 
        if(Number.isInteger(cantidadPedida) && Math.sign(cantidadPedida) == 1){  
            $.ajax({
                method: "PUT", 
                url: '../pedidoDet', /*update*/
                data: {"idPedido" : data.idPedido, "idDetalle" : data.idDetalle, "codFabrica" : data.codFabrica, "codArticulo" : data.codArticulo, "cant" : cantidadPedida},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data){                
                table.ajax.reload( function ( json ) {
                    $("#datosPedido").html(data.datosPedido);
                    $("#eliminado").html("");
                    $( "#mensaje_" + id).html("Modificado"); 
                //document.getElementById("cant_modif_" + id).value= 1;
                } );
            });
        }
    });
} 

var enter_input_modificar = function(tbody, table){
    $(tbody).on("keyup", "input.enter_modif_art", function(e){
        var data = table.row($(this).parents("tr")).data();
        if (e.keyCode == 13) { /*Enter recibe el articulo*/
            document.getElementById("modificar_" + data.id).click();  
        }
    });
}

var config_elim_art_pedido = function(tbody, table){
    $(tbody).on("click", "button.elim_art_pedido", function(){
        var data = table.row($(this).parents("tr")).data();
        var idDetalle = $('#idDetalle').val(data.idDetalle),
            idPedido = $('#idPedido').val(data.idPedido)
    });
} 
/*FIN DATATABLES PEDIDOS CLIENTE */


/* ENVIAR Y ANULAR PEDIDO */
var enviar = function(){ 
    /*'pedido/cerrarPedido/' + idPedido no se utiliza mas.
    Siempre redirecciona a 'pedido/enviarPedido/' + idPedido;*/
    $('.cerrar_pedido').on("click", function(e){
        var idPedido = ($(this).parents("tr").attr('id'));
        $.ajax({
            method: "POST", 
            url: 'pedido/cerrarPedido/' + idPedido,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function(data){
                //MODIFICAR MODAL PARA CONFIRMACION
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
/*
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


/*DATATABLES RECIBIR PEDIDO CLIENTE */
var listar_art_a_recibir_cliente = function(){ 
    var idPedido = $('#idPedidoRecibido').data("field-id");

    var tableRecibir = $('#tablaArtARecibirCliente').DataTable({ 
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
            "url": "../../pedido/recibir", 
        },
        "columns": [
            {data: 'codArticulo', name: 'pedidoDet.codArticulo'},
            {data: 'descripcion', name: 'lista.descripcion'},
            {data: 'fabrica', name: 'lista.fabrica'},
            {data: 'cant'},   
            {render: function ( data, type, row ) {
                return "<input id='cant_recibida_" + row.id + "' type='number' value='" + row.cantFaltante + "' class='col-lg-8 enter_recibir_art widthPedir'> <button type='button' id='cant_recibir_" + row.id + "'class='recibir_art btn btn-default btn-sm'><span class='glyphicon glyphicon-ok'></span></button><div class='aprobado right'><label id='mensaje_" + row.id + "'></label></div>";
             }},
        ],
        "columnDefs": [
            { responsivePriority: 1, targets: 4, width: 130 },
            { responsivePriority: 1, targets: 0, width: 100 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: 2 },
            { responsivePriority: 5, targets: 3 },
        ],
        "dom":  "<'row' <'form-inline' <'col-sm-1'f>>>"
                 +"<rt>"
                 +"<'row'<'form-inline'"
                 +" <'col-sm-6 col-md-6 col-lg-6'l>"
                 +"<'col-sm-6 col-md-6 col-lg-6'p>>>",// 'Bfrtip',
    });

    //seteo el foco en el filtro codArticulo
    $("#filtro_cod_art").focus();

    tableRecibir.columns().every( function () {
        var that = this; 
        $('input', this.footer() ).on('keyup change', function (e) {
            if (e.keyCode == 46) { /*DELETE limpia el input*/
                $(this).val('');  
            }
            if ( that.search() !== this.value ) {
                that
                    .search( "^"+this.value, true, false )
                    .draw();  
            }
        } );
    });
    recibir_art_pedido("#tablaArtARecibirCliente tbody", tableRecibir);
    enter_input_cant("#tablaArtARecibirCliente tbody", tableRecibir);
};

var recibir_art_pedido = function(tbody, table){
    $(tbody).on("click", "button.recibir_art", function(e){
        e.preventDefault();
        var data = table.row($(this).parents("tr")).data(),
            idPedido = data.idPedido,
            id = data.id,
            cantidadRecibida = Number($("#cant_recibida_" + data.id).val());
        //valido que sea entero y positivo 
        if(Number.isInteger(cantidadRecibida) && Math.sign(cantidadRecibida) == 1){    
            $.ajax({
                method: "POST", 
                url: '../../pedido/recibirCant',
                data: {"idPedido" : data.idPedido, "idDetalle" : data.idDetalle, "cantRecibida" : cantidadRecibida},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data){
                if(data.finalizado == 0){ 
                    if(data.cantFaltante > 0){ //si falta recibir cant de este articulo, actualizo valor input
                        document.getElementById("cant_recibida_" + id).value=String(data.cantFaltante);
                        $( "#mensaje_" + id).html("Se recibieron " + String(cantidadRecibida));
                    }else{ //oculto la fila
                        table.row($(this).parents("tr")).remove().draw();
                    }
                }else{  //no hay mas articulos para recibir en este pedido
                    window.location.href = '../../pedido/finalizarPedido/' + idPedido;    
                } 
            });
        }
    });
} 

var enter_input_cant = function(tbody, table){
    $(tbody).on("keyup", "input.enter_recibir_art", function(e){
        var data = table.row($(this).parents("tr")).data();
        if (e.keyCode == 13) { /*Enter recibe el articulo*/
            document.getElementById("cant_recibir_" + data.id).click();  
        }
    });
}
/*FIN DATATABLES RECIBIR PEDIDO CLIENTE */

/* FORMULARIOS SUBMIT*/
$(document).on("submit", ".form-submit", function(e){
    e.preventDefault();
    var formulario=$(this);
    var nombreForm=$(this).attr("id");

    if(nombreForm=="f-observaciones"){  //Cliente pedido
                            var miurl=pathRoot + "guardarObservaciones"; 
                            var divResult="mensajeObs"}  
    if(nombreForm=="f-cargar-lista"){   //Admin
                            var miurl="actualizarLista"; 
                            var divResult="notif-carga-excel";
                            var campoVacio = "archivo";}                   
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
            if(nombreForm=="f-cargar-lista"){   //Admin
                tableAdmin.ajax.reload(null,false);
            }
        },
        //si ha ocurrido un error
        error: function(data){
             console.log(data);
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
/* FIN SUBMIT FORMULARIOS*/


/*ADMIN LISTA ARTICULOS DATATABLE*/
var listar_art_admin = function(){ 
     tableAdmin = $('#tablaArticulosAdmin').DataTable({
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
            "url": "home/tabla", 
        },
        "columns": [
            {data: 'codArticulo'},
            {data: 'descripcion'},
            {data: 'fabrica'},
            {data: 'rubro'},
            {data: 'precio'}
        ],
        "dom": "<'row' <'form-inline' <'col-sm-1'f>>>"
                     +"<rt>"
                     +"<'row'<'form-inline'"
                     +" <'col-sm-6 col-md-6 col-lg-6'l>"
                     +"<'col-sm-6 col-md-6 col-lg-6'p>>>"
    });

    //seteo el foco en el filtro codArticulo
    $("#filtro_cod_art").focus();

    tableAdmin.columns().every( function () {
        var that = this; 
        $( 'input', this.footer() ).on('keyup change', function (e) {
            if (e.keyCode == 46) { /*DELETE limpia el input*/
                $(this).val('');  
            }
            if ( that.search() !== this.value ) { 
                that                                      //busqueda
                    .search( "^"+this.value, true, false )
                    .draw();
                if( $("#filtro_rubro").val() !== ''){  //si filtra por rubro, ordeno por descripcion 
                    tableAdmin.order( [ 1, 'asc' ] )
                        .draw();
                }else{
                    tableAdmin.order( [ 0, 'asc' ] )
                        .draw();
                }
            }
        } );
    });

}
/*ADMIN LISTA ARTICULOS DATATABLE*/

/* ADMIN LISTADO CLIENTES */
var listar_clientes = function(){ 
    var tableClientes = $('#tablaListadoClientes').DataTable({ 
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
            "url": "clientes", 
        },
        "columns": [
            {data: 'codCliente'},
            {data: 'name'},
            {render: function ( data, type, row ) {
                return row.confirmed === 1 ? "Si" : "No";
            }},
            {render: function ( data, type, row ) {
                return "<input id='cliente_modif_" + row.id + "' type='email' value='" + row.email + "' class='col-lg-8 enter_modif_cliente'> <button type='button' id='modif_" + row.id + "' class='modif_cliente btn btn-default boton_angosto'><span class='glyphicon glyphicon-share-alt'></span></button>";
            }},
        ],
        "dom": "<'row'<'form-inline' <'col-sm-offset-11'B>>>"
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

    tableClientes.columns().every( function () {
        var that = this; 
        $('input', this.footer() ).on('keyup change', function (e) {
            if (e.keyCode == 46) { /*DELETE limpia el input*/
                $(this).val('');  
            }
            if ( that.search() !== this.value ) {
                that
                    .search( "^"+this.value, true, false )
                    .draw();  
            }
        } );
    });

    modificar_cliente("#tablaListadoClientes tbody", tableClientes);
    enter_input_modificar_cliente("#tablaListadoClientes tbody", tableClientes);
};

var modificar_cliente = function(tbody, table){
    $(tbody).on("click", "button.modif_cliente", function(e){
        e.preventDefault();
        rowActual = $(this).parents("tr");
        var data = table.row($(this).parents("tr")).data(),
            id = data.id,
            emailNuevo = $("#cliente_modif_" + data.id).val();   
        //valido
        validacion_email = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
        if((emailNuevo == "") || (!validacion_email.test(emailNuevo))){
                $("#cliente_modif_" + data.id).focus();
            //$('.msg').text('Email no válido').addClass('msg_error').animate({ 'right' : '140px' }, 300);
        }else{
            $.ajax({
                method: "PUT", 
                url: 'user/' + id, /*update*/
                data: {"data" : data, "emailNuevo" : emailNuevo},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            }).done(function(data){
                table.ajax.reload(null,false);
            });
        }
    });
} 

var enter_input_modificar_cliente = function(tbody, table){
    $(tbody).on("keyup", "input.enter_modif_cliente", function(e){
        var data = table.row($(this).parents("tr")).data();
        if (e.keyCode == 13) { /*Enter recibe el articulo*/
            document.getElementById("modif_" + data.id).click();  
        }
    });
}
/*FIN ADMIN LISTADO CLIENTES */