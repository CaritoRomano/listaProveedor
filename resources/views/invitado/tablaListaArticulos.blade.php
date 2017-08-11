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
            <th></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th><input type="text" placeholder="Buscar Rubro" id="filtro_rubro"/></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection