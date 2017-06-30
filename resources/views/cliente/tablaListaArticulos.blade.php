@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArticulosCliente">  
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>COD. ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
            <th><p class="tituloTabla"><strong>RUBRO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRECIO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PEDIR</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th><input type="text" placeholder="Buscar Rubro"/></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
  <!--  <tbody>
        @foreach ($articulosLista as $articulo)
            <tr>
                <td>{{ $articulo->codProveedor }}</td>
                <td>{{ $articulo->codArticulo }}</td>
                <td>{{ $articulo->descripcion }}</td>
                <td>{{ $articulo->precio }}</td>
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