@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-6">
        <h2>Lista de precios</h2>
        <p></p>
    </div>
    <div class="col-lg-6 paddingTop30">
        <form id="f-cargar-lista" name="f-cargar-lista" action="actualizarLista" method="POST" class="form-submit" enctype="multipart/form-data">
            {{ csrf_field() }}   
            <div class="panel panel-default col-lg-12">
            <p><strong>Seleccione un archivo .csv</strong></p>
            <div class="form-group col-lg-9"> 
                <input name="archivo" id="archivo" type="file" class="" required/>
            </div>        
                
            <button type="submit" class="btn btn-primary col-lg-3">Actualizar lista</button>              
            </div>  
        </form>
    </div>
    <div id="notif-carga-excel" class="col-lg-3 right"> </div>
    
    <!-- cargador gif -->
    <div style="display: none;" id="cargador">
        <br>
            <label>Actualizando los datos...</label>
            <img src="{{ asset('images/cargador/cargando.gif') }}" align="middle" alt="cargador"> &nbsp;
        <br>
    </div>

    <div>
        @yield('tablaArt')
    </div>
    

</div>
@endsection