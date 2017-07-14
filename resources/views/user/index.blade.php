@extends('layouts.app')
@section('content')

<table class="table" id="tablaListadoClientes"> 
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>COD. CLIENTE</strong></p></th>
            <th><p class="tituloTabla"><strong>NOMBRE</strong></p></th>
            <th><p class="tituloTabla"><strong>EMAIL CONFIRMADO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>Modificar EMAIL</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo"/></th>
            <th><input type="text" placeholder="Buscar Nombre"/></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection