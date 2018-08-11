@extends('cliente.homeCliente')
@section('tablaArt')
 <br> 
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

@section('datosPedido')
    <div class="col-lg-4">
        <div>
            <p><strong>Fecha de la &uacute;ltima actualizaci&oacute;n de precios:</strong> {{ date('d/m/Y H:i:s', strtotime($ultActualizacionLista['ultActualizacion'])) }}</p>  
            <a class="atoggle">Mostrar f&aacute;bricas afectadas</a> 
                @foreach($fabricasActualizadas as $fabrica)
                    <p class="toggle">
                        {{ $fabrica->fabrica }}
                    </p>             
                @endforeach  
        </div> 
    </div>
    <div class = "col-lg-8">
    <div class = "col-md-offset-8 col-lg-4">
        <div class = "col-lg-6">
            <a href="{{ url('exportarListaCompleta') }}" type='button' class='exportar_lista_completa btn btn-primary btn-sm leftExportar'>Exportar Lista</a> 
        </div> 
        <div class = "col-lg-6">
            <a href="{{ url('exportarListaCompletaDBF') }}" type='button' class='btn btn-primary btn-sm leftExportar'>Lista DBF</a> 
        </div> 
    </div>
    </div>

@endsection