@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-12">
        <h2>Lista de precios</h2>
        <p>Febrero 2017</p>
    </div>

    <div class="col-lg-8">  
        @if ($detallePedido) 
        <div id="f_mod_cant_art_pedido">
            {!! Form::open(['route' => ['pedidoDet.update', $pedido->id ], 'method' => 'PUT', 'class' => 'navbar-form navbar-left form-archivo', 'id' => 'modif_cant']) !!}
                 {{ csrf_field() }}

    	    <div class="form-group"> 
    	      	{{ Form::hidden('codProveedor', '', array('id' => 'codProveedorPedido')) }}
    	       	{{ Form::hidden('codArticulo', '', array('id' => 'codArticuloPedido')) }}
    	        {!! Form::text('descrip', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'descripPedido']) !!}  
    	        {!! Form::label('cantidad', 'cantidad') !!}          
    	        {!! Form::number('cant', 1, ['class' => 'form-control', 'id' => 'cantPedido']) !!}     
    	    </div>

    	    <button type="submit" class="btn btn-default">Modificar</button>
    	    {!! Form::close() !!}
        </div> 
        <div id="mensaje_modif" class="col-lg-10 right"> </div>

        @else
        <div id="f_agregar_art_pedido">
            {!! Form::open(['route' => ['pedidoDet.store', $pedido->id], 'method' => 'POST', 'class' => 'navbar-form navbar-left form-archivo', 'id' => 'pedir']) !!}
            {{ csrf_field() }}
            
            <div class="form-group">
                {{ Form::hidden('codProveedor', '', array('id' => 'codProveedor')) }}
                {{ Form::hidden('codArticulo', '', array('id' => 'codArticulo')) }}
                {!! Form::text('descrip', null, ['class' => 'form-control', 'placeholder' => '', 'id' => 'descrip']) !!}  
                {!! Form::label('cantidad', 'cantidad') !!}          
                {!! Form::number('cant', 1, ['class' => 'form-control', 'id' => 'cant']) !!}     
            </div>

            <button type="submit" class="btn btn-default">Pedir</button>
            {!! Form::close() !!}
        </div>     
            <div id="mensaje_pedir" class="col-lg-10 right"> </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <div class="panel panel-default col-lg-7" id="datosPedido">
            @yield('datosPedido')
        </div> <div class="col-lg-1"> </div>
        <div class = "col-lg-3">
            @if ($detallePedido) 
            <a href = "{{ url('pedido/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm '>Agregar Art&iacute;culos</a>
            @else
            <a href = "{{ url('detalle/' . $pedido->id ) }}" type='button' class='btn btn-default btn-sm '>Ver Art&iacute;culos</a>
            @endunless
        </div> 
        
    </div>
        

    <div class = "col-lg-12">
        @yield('tablaArt')
    </div>
    
    <!--form eliminar art pedido-->
    @if ($detallePedido) 
        {{ Form::open(["id" => "form_eliminar_art_pedido" ]) }}
            {{ Form::hidden('id', '', array('id' => 'idDetalle')) }}
            {{ Form::hidden('idPedido', '', array('id' => 'idPedido')) }}
            <!-- Modal --> 
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalEliminarLabel">Eliminar Usuario</h4>
                        </div>
                        <div class="modal-body"> ¿Está seguro de eliminar el articulo?<strong data-name=""></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="b_elim_art_pedido" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Modal -->           
        </form>    
    {{ Form::close() }}  
    @endif
</div>


@endsection
