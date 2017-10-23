@extends('cliente.homeCliente')
@section('tablaArt')

<table class="table" id="tablaArtPedidosCliente"> 
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>COD. ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRECIO</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>IMPORTE</strong></p></th>  
            <th><p class="tituloTabla text-center"><strong>Cantidad Modificar/Eliminar</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo" id="filtro_cod_art"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th></th>
            <th></th>
            <th><div class="aprobado right"> <label id="eliminado"> </label></div> </th>
        </tr>
    </tfoot>
</table>
<div id="idPedido" data-field-id="{{ $infoPedido['id'] }}" ></div>
@endsection


@section('datosPedido')
    @if(!empty($infoPedido))
    <!-- Observaciones -->
    <br><div class="panel panel-default col-lg-8" ><br>
        {{ Form::open(['id' => 'f-observaciones', 'name' => 'f-observaciones', 'class' => 'form-submit', 'method' => 'POST']) }}
        <input type="hidden" name="idPedido" value= "{{ $infoPedido['id'] }}"> 
        <div class="form-group col-lg-10">
            <div class="col-lg-3">
                {!! Form::label('Observaciones del pedido') !!}
            </div>

            <div class="col-lg-9">
                {!! Form::textarea('observaciones', $infoPedido['observaciones'], 
                    array('class'=>'form-control', 
                          'rows' => 2,
                          'placeholder'=>'Ingrese observaciones...')) !!}
            </div>
        </div>
        <div class="form-group col-lg-1">
            {!! Form::submit('Guardar', ['class'=>'btn btn-primary']) !!}
        </div>
        <div class="aprobado right"> <label id="mensajeObs"> </label></div> 
        {{ Form::close() }} 
    </div>
    <!-- Datos pedido -->
    <div class="col-lg-4">
        <div class="panel panel-default col-lg-7" >
            <p><strong>N&uacute;mero de pedido:</strong> {{ $infoPedido['nroPedido'] }} </p>
            <p><strong>Art&iacute;culos:</strong> {{ $infoPedido['cantArticulos'] }} </p>
            <p><strong>Total:</strong> {{ $infoPedido['totalAPagar'] }} </p>    
        </div> 
        <div class="col-lg-1"> </div>
        <div class = "col-lg-3">
            <button type='button' class='cerrar_pedido btn btn-primary btn-sm'>Enviar Pedido</button> 
        </div> 
    </div>   
     
    @endif
@endsection