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
            <th><p class="tituloTabla text-center"><strong>PEDIR</strong></p></th>
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



@section('datosPedido')
    @if(!empty($pedido))
        <div class="panel panel-default col-lg-7" >
            <p>N&uacute;mero de pedido: {{ $pedido->nroPedido }} </p>
            <p>Cantidad de art&iacute;culos: {{ $pedido->cantArticulos }} </p>
            <p>Total: {{ $pedido->totalAPagar }} </p>    
        </div> 
        <div class="col-lg-1"> </div>
        <div class = "col-lg-3">
                @if ($detallePedido) 
                <a href = "{{ url('pedido/' . $pedido->id ) }}" type='button' class='btn btn-primary btn-sm '>Agregar Art&iacute;culos</a> <br><br>
                @else
                <a href = "{{ url('detalle/' . $pedido->id) }}" type='button' class='btn btn-primary btn-sm '>Ver pedido</a>
                @endif
        </div> 
    @endif
@endsection
