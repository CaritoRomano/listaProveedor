@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArticulosCliente">
    <thead>
        <tr>            
            <th>Cod. Proveedor</th>
            <th>Cod. Articulo</th>
            <th>Descripci&oacute;n</th>
            <th>Precio</th>
            <th>Pedir</th>
        </tr>
    </thead>
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