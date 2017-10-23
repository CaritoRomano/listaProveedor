@extends('cliente.homeCliente')
@section('tablaArt')
<div class = "col-lg-2 col-md-offset-10">
    <a href="{{ url('exportarListaCompleta') }}" type='button' class='exportar_lista_completa btn btn-primary btn-sm'>Exportar Lista</a> 
</div> 

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
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo" id="filtro_cod_art"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n" class="widthFiltro"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica" class="widthFiltro"/></th>
            <th><input type="text" placeholder="Buscar Rubro" id="filtro_rubro" class="widthFiltro"/></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
@endsection