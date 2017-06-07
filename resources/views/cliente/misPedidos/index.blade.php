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
                    <tr>
                        <td>{{ $pedido->nroPedido }}</td>
                        <td>{{ $pedido->estado }}</td>
                        <td>{{ $pedido->cantArticulos }}</td>
                        <td>{{ $pedido->totalAPagar }}</td>
                        @if ( $pedido->estado == "Abierto" )
                            <td> <a href= "{{ url('pedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Agregar Art&iacute;culos</a>   <a href= "{{ url('pedido/cerrarPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Cerrar Pedido</a>  <a href= "{{ url('pedido/anularPedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm'>Anular Pedido</a> </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>  
        </table>
    </div>
</div>
@endsection


 <!-- CERRAR PEDIDO -->

 <!--  FIN CERRAR PEDIDO -->