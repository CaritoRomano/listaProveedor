@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArticulos">
    <thead>
        <tr>
            <th>Cod. Proveedor</th>
            <th>Cod. Articulo</th>
            <th>Descripci&oacute;n</th>
            <th>Precio</th>
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
