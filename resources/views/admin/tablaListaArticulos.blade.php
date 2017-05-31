@extends('admin.homeAdmin')
@section('tablaArt')
<table class="table">
    <thead>
        <tr>
            <th>Cod. Proveedor</th>
            <th>Cod. Articulo</th>
            <th>Descripci&oacute;n</th>
            <th>Precio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($articulosLista as $articulo)
            <tr>
                <td>{{ $articulo->codProveedor }}</td>
                <td>{{ $articulo->codArticulo }}</td>
                <td>{{ $articulo->descripcion }}</td>
                <td>{{ $articulo->precio }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $articulosLista->render() }}
@endsection
