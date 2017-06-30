@extends('cliente.misPedidos.index')
@section('tablaPreciosDistintos')

<table class="table" id="tablaPreciosDistintos"> 
    <thead>
        <tr>            
            <th><p><strong>Cod. Art&iacute;culo</strong></p></th>
            <th><p><strong>Descripci&oacute;n</strong></p></th>
            <th><p><strong>F&aacute;brica</strong></p></th>
            <th><p><strong>Cant</strong></p></th>
            <th><p><strong>Precio</strong></p></th>
            <th><p><strong>Precio Actual</strong></p></th>    
            <th><p><strong>&nbsp;</strong></p></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($artsPrecioDistinto as $articulo)
            <tr>
                <td>{{ $articulo->codArticulo }}</td>
                <td>{{ $articulo->descripcion }}</td>
                <td>{{ $articulo->fabrica }}</td>
                <td>{{ $articulo->cant }}</td>
                <td>{{ $articulo->precio }}</td>                
                <td>{{ $articulo->precioLista }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>  
</table>


@endsection
