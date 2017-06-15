@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-10">
        <h2>Pedidos</h2>
    </div>
    <div class="col-lg-10"> </div>
    <div class="col-lg-2">
        <a href= "{{ url('pedido/create') }}" type='button' class='btn btn-default btn-sm'>Nuevo Pedido</a>
    </div>

    <div class="col-lg-12">
        <table class="table">
            <thead>
                <tr>            
                    <th>Nro Pedido</th>
                    <th>Estado</th>
                    <th>Art&iacute;culos</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr id="{{ $pedido->id }}">
                        <td>{{ $pedido->nroPedido }}</td>
                        <td>{{ $pedido->estado }}</td>
                        <td>{{ $pedido->cantArticulos }}</td>
                        <td>{{ $pedido->totalAPagar }}</td>
                        @if ( $pedido->estado == "Abierto" )
                            <td> <a href= "{{ url('pedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Agregar Art&iacute;culos</a>  

                             <!-- <a href= "{{ url('pedido/cerrarPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Cerrar Pedido</a>  -->
                             <button type='button' class='cerrar_pedido btn btn-default btn-sm'>Cerrar Pedido</button>

                            <a href= "{{ url('pedido/anularPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Anular Pedido</a> </td> 
                        @endif
                    </tr>
                @endforeach
            </tbody>  
        </table>
    </div>
</div>



<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

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
            <div class="modal-footer">
                <td> <a href= "{{ url('pedido/' . $idPedido ) }}" type='button' class='btn btn-default btn-sm' id = 'ModPedido'>Modificar Art&iacute;culos</a>   
                <button>Continuar con el pedido y ENVIAR</button>
            </div>
        </div>
    </div>
</div>

@endsection


 <!-- CERRAR PEDIDO -->

 <!--  FIN CERRAR PEDIDO -->