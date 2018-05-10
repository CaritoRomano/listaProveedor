@extends('cliente.homeCliente') NO SE USA, ELIMINAR LA CARPETA SIN PROBLEMAS
@section('tablaArt')
    <!-- Observaciones -->
    <br><div class="panel panel-default col-lg-8" ><br>
        {{ Form::open(['id' => 'f-observaciones', 'name' => 'f-observaciones', 'class' => 'form-submit', 'method' => 'POST']) }}
        <input type="hidden" name="idPedido" value= "{{ $pedido->id }}">
        <div class="form-group col-lg-10">
            <div class="col-lg-3">
                {!! Form::label('Observaciones del pedido') !!}
            </div>

            <div class="col-lg-9">
                {!! Form::textarea('observaciones', $pedido['observaciones'], 
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


<div class="panel panel-default col-md-offset-2 col-lg-2" >
    <p>Art&iacute;culos no recibidos <small>&Uacute;ltimo env&iacute;o: {{  date('d/m/Y', strtotime($pedido->ultFechaEnvio))      }}</small></p>
    <a href= "{{ url('pedido/reenviar/' . $pedido->id) }}" type='button' class='btn btn btn-primary col-lg-12'>Reenviar pedido</a> 

    <br><br>
</div>
<br><br><br>
<table class="table" id="tablaArtARecibirCliente">  
    <thead>
        <tr>            
            <th><p class="tituloTabla"><strong>COD. ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
			<th><p class="tituloTabla text-center"><strong>CANT PEDIDA</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>RECIBIR</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo" id="filtro_cod_art"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<div id="idPedidoRecibido" data-field-id="{{ $pedido->id }}" ></div>
@endsection
