@extends('admin.homeAdmin')
@section('tablaArt')
<table class="table table-striped table-bordered">
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
                <td>{{ $articulo->codFabrica }}</td>
                <td>{{ $articulo->codArticulo }}</td>
                <td>{{ $articulo->descripcion }}</td>
                <td>{{ $articulo->precio }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $articulosLista->render() }}
@endsection
