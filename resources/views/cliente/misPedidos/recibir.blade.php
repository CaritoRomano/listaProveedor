@extends('cliente.homeCliente')
@section('tablaArt')
<!-- Observaciones -->
<br><div class="panel panel-default col-lg-8" ><br>
    {{ Form::open(['id' => 'f-observacionesReenvio', 'name' => 'f-observacionesReenvio', 'class' => 'form-submit', 'method' => 'POST']) }}
    <!-- <input type="hidden" name="idPedido" value= ""> -->
    <div class="form-group col-lg-10">
        <div class="col-lg-3">
            {!! Form::label('Observaciones') !!}
        </div>

        <div class="col-lg-9">
            {!! Form::textarea('observaciones', $reenvio['observaciones'], 
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
    <p>Art&iacute;culos no recibidos <small>&Uacute;ltimo reenv&iacute;o: {{  date('d/m/Y', strtotime($reenvio->ultimaFecha))      }}</small></p>
    <button type="button" id="reenviarArticulos" class="btn btn-primary col-lg-12">Reenviar Art&iacute;culos</button>
    <p> <small>*Hasta fecha seleccionada</small></p>
</div>
<br><br><br>

<div class="col-md-offset-10 " >
    <p>Pendientes a la fecha: </p> 
    <div class="form-group">
        <div class='input-group date' id='datepicker'>
            <input type="date" class="form-control" id='inputdatepicker' value="{{  date('d-m-Y')  }}">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>

<table class="table" id="tablaArtARecibirCliente">  
    <thead>
        <tr>   
            <th><p class="tituloTabla"><strong>COD. ART&Iacute;CULO</strong></p></th>
            <th><p class="tituloTabla"><strong>DESCRIPCI&Oacute;N</strong></p></th>
            <th><p class="tituloTabla"><strong>F&Aacute;BRICA</strong></p></th>
			<th><p class="tituloTabla text-center"><strong>CANT PEDIDA</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>RECIBIR PENDIENTES</strong></p></th>
            <th><p class="tituloTabla text-center"><strong>PRIMER / &Uacute;LTIMO PEDIDO</strong></p></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th><input type="text" placeholder="Buscar Cod. Art&iacute;culo" id="filtro_cod_art"/></th>
            <th><input type="text" placeholder="Buscar Descripci&oacute;n"/></th>
            <th><input type="text" placeholder="Buscar F&aacute;brica"/></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

@endsection
