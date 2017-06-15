@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArtCambioPrecio"> 
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>COD. ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>CANT</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRECIO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRECIO ACTUAL</strong></p></th>    
            <th><p class="tituloTabla text-center"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<div id="idPedido" data-field-id="{{ $pedido->id }}" ></div>
@endsection


@section('datosPedido')
    <p>N&uacute;mero de pedido: {{ $pedido->nroPedido }} </p>
    <p>Cantidad de art&iacute;culos: {{ $pedido->cantArticulos }} </p>
    <p>Total: {{ $pedido->totalAPagar }} </p>   
@endsection