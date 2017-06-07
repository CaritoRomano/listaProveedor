@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArtPedidosCliente"> 
    <thead>
        <tr>            
            <th>Cod. Proveedor</th>
            <th>Cod. Articulo</th>
            <!-- <th>Descripci&oacute;n</th>  -->
            <th>Cantidad</th>
            <th>Precio Unit</th>
            <th></th>
        </tr>
    </thead>
   <!-- <tbody>
        @foreach ($articulosPedidos as $articulo)
            <tr>
                <td>{{ $articulo->codProveedor }}</td>
                <td>{{ $articulo->codArticulo }}</td>
                <td>{{ $articulo->cant }}</td>
                <td>{{ $articulo->precio }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>  -->
</table>
@endsection


@section('datosPedido')
    <p>N&uacute;mero de pedido: {{ $pedido->nroPedido }} </p>
    <p>Cantidad de art&iacute;culos: {{ $pedido->cantArticulos }} </p>
    <p>Total: {{ $pedido->totalAPagar }} </p>   
@endsection

@push('scripts')
<script type="text/javascript">
    var listar_art_pedidos_cliente = function(){ 
        $("#f_mod_cant_art_pedido").slideUp("slow"); 
        $("#mensaje_modif").slideDown("slow"); 

        var tablePedidos = $('#tablaArtPedidosCliente').DataTable({ 
            "language": idioma_esp,
            "destroy":true,
            "processing": true,
            "serverSide": true,
            "ajax":{
                "method": "POST", 
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, 
                "data": {"id" : "{{ $pedido->id }}"}, 
                "url": "../home/tablaPedidos", 
            },
            "columns": [
                {data: 'codProveedor'},
                {data: 'codArticulo'},
                {data: 'cant'},
                {data: 'precio'},   
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

        configurar_mod_art_pedido("#tablaArtPedidosCliente tbody", tablePedidos);
        configurar_elim_art_pedido("#tablaArtPedidosCliente tbody", tablePedidos);
    };
</script>
@endpush