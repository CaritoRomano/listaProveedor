@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-10">
        <h2>Pedidos</h2>
    </div>

    @if($mensajeEnviado)
    <div class='col-lg-12'>
        <div class='aprobado col-lg-4'>
            <label> El pedido ha sido enviado con &eacute;xito. </label>
        </div>
    </div>
    @endif
    <div class="col-lg-10"> </div>
    <div class="col-lg-2">
       
    </div>

    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr>            
                    <th>Nro Pedido</th>
                    <th>Estado</th>
                    <th>Fecha &Uacute;timo Pedido</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr id="{{ $pedido->id }}">
                        <td>{{ $pedido->nroPedido }}</td>
                        <td>{{ $pedido->estado }}</td>
                        <td>@if (!is_null($pedido->ultFechaEnvio)) {{ date('d/m/Y', strtotime($pedido->ultFechaEnvio)) }} @endif</td>
                        @if ( $pedido->estado == "Nuevo" )
                            <td> <a href= "{{ url('pedido/lista' ) }}" type='button' class='btn btn-default btn-sm'>Agregar Art&iacute;culos</a>  
                            <a href= "{{ url('detalle/' . $pedido->id) }}" type='button' class='btn btn-default btn-sm'>Modificar/Ver Pedido</a>  
                             <button type='button' class='cerrar_pedido btn btn-default btn-sm'>Enviar Pedido</button>

                            <a href= "{{ url('pedido/anularPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Anular Pedido</a> </td> 
                        @endif 
                        @if ( ($pedido->estado == "Enviado") || ($pedido->estado == "Reenviado") )
                            <td> <a href= "{{ url('pedido/recibir/' . $pedido->id) }}" type='button' class='btn btn-default btn-sm'>Recibir Art&iacute;culos</a>  
                            <a href= "{{ url('pedido/finalizarPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Finalizar Pedido</a> </td> 
                        @endif
                    </tr>
                @endforeach
            </tbody>  
        </table>
    </div>
</div>


<!-- MODAL -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog  modal-lg">
        
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4> Los siguientes art&iacute;culos han actualizado su precio desde el d&iacute;a que usted los seleccion&oacute;:</h4>
            </div>
            <div class="modal-body">
                @yield('tablaPreciosDistintos')
            </div>
            <div id="idPedidoModal" data-field-id="" ></div>
            <div class="modal-footer">
                <td> <a href= "" type='button' class='btn btn-default btn-sm' id = 'mod_pedido'>Modificar Art&iacute;culos</a>   
                <a href= "" type='button' class='btn btn-primary btn-sm' id = 'cont_pedido'>Continuar con el pedido y ENVIAR</a>   
                </td>                 
            </div>
        </div>
    </div>
</div>

@endsection


 <!-- CERRAR PEDIDO -->

 <!--  FIN CERRAR PEDIDO -->