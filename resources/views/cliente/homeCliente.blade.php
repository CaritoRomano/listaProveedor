@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="col-lg-12">
        <div class="col-lg-4">
        <h3>Lista de precios</h3>
        <p></p></div>
        <div class="col-lg-8">
        <h2 class="col-lg-7 col-md-offset-5"> {{ $subtitulo }} </h2></div>
    </div>

    <div class="col-lg-8">  
        
        
    </div>
    
    <!--div datos pedido-->
    <div class="col-lg-12" id="datosPedido">
        @yield('datosPedido')
    </div>
    <!--fin div datos pedido-->
        

    <div class = "col-lg-12">
        @yield('tablaArt')
    </div>
    
    <!--form eliminar art pedido-->
    @if ($detallePedido) 
        {{ Form::open(["id" => "form_eliminar_art_pedido" ]) }}
            {{ Form::hidden('id', '', array('id' => 'idDetalle')) }}
            {{ Form::hidden('idPedido', '', array('id' => 'idPedido')) }}
            <!-- Modal Eliminar Articulo --> 
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalEliminarLabel">Eliminar Art&iacute;culo</h4>
                        </div>
                        <div class="modal-body"> ¿Est&aacute; seguro de eliminar el art&iacute;culo?<strong data-name=""></strong>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="b_elim_art_pedido" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Fin Modal Eliminar Articulo -->           
        </form>    
    {{ Form::close() }}  
    @endif

   
    <!-- Modal Articulo Repetido -->
    <div class="modal fade" id="modalArtRepetido" tabindex="-1" role="dialog" aria-labelledby="modalArtRepetidoLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalArtRepetidoLabel">Art&iacute;culo Repetido</h4>
                </div>
                <div class="modal-body">El art&iacute;culo se encuentra cargado en el pedido actual, puede modificar su cantidad o eliminarlo desde "Modificar Pedido". <strong data-name=""></strong>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b_mod_pedido" class="btn btn-primary" data-dismiss="modal">Modificar Pedido</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div> 
        <div id="idPedidoArtRep" data-field-id="" ></div>
    </div>
   
    <!-- Fin Modal Articulo Repetido --> 
</div>


@endsection
