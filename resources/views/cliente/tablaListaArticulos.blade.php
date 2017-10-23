@extends('cliente.homeCliente')
@section('tablaArt')
<table class="table" id="tablaArticulosCliente">  
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>C&Oacute;DIGO ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
            <th><p class="tituloTabla"><strong>RUBRO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRECIO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PEDIR</strong></p></th>
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
    @if(!empty($infoPedido))
    <div class="col-lg-4 col-md-offset-8">
        <div class="panel panel-default col-lg-7" >
            <p><strong>N&uacute;mero de pedido:</strong> {{ $infoPedido['nroPedido'] }} </p>
            <p><strong>Art&iacute;culos:</strong> {{ $infoPedido['cantArticulos'] }} </p>
            <p><strong>Total:</strong> {{ $infoPedido['totalAPagar'] }} </p>    
        </div> 
        <div class="col-lg-1"> </div>
        <div class = "col-lg-5">
                <a href = "{{ url('detalle/' . $infoPedido['id']) }}" type='button' class='btn btn-primary btn-sm '>Ver pedido</a>
        </div> 
        <div class = "col-lg-5">
            <a href="{{ url('exportarListaCompleta') }}" type='button' class='exportar_lista_completa btn btn-primary btn-sm' id='topExportar'>Exportar Lista</a> 
        </div> 
    </div>
    @else 
    <div class="col-lg-4 col-md-offset-8">
        <div class = "col-lg-2 col-md-offset-6">
            <a href="{{ url('exportarListaCompleta') }}" type='button' class='exportar_lista_completa btn btn-primary btn-sm' id='topExportar'>Exportar Lista</a>    
        </div> 
    </div>

    @endif

@endsection