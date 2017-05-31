@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-6">
        <h2>Lista de precios</h2>
        <p>Febrero 2017</p>
    </div>
    <div class="col-lg-6 paddingTop30">
        <form id="f-cargar-lista" name="f-cargar-lista" action="actualizarLista" method="POST" class="form-archivo" enctype="multipart/form-data">
            {{ csrf_field() }}   
            
            <div class="form-group col-lg-8"> 
                <input name="archivo" id="archivo" type="file" class="form-control" required/>          
            </div>        
                
            <button type="submit" class="btn btn-primary right col-lg-4">Actualizar lista</button>                
        </form>


      <!--  <a href="{{ route('index') }}" type="button" class="btn btn-primary right">Actualizar lista</a>
        <div class="progress col-lg-12">
            <div class="progress-bar" role="progressbar" style="width:50%;">                                    
            </div>            
        </div>-->
    </div>
    <div id="notif-carga-excel" class="col-lg-3 right"> </div>
    <!-- cargador gif -->
    <div style="display: none;" id="cargador">
        <br>
            <label>Actualizando los datos...</label>
            <img src="{{ asset('images/cargador/cargando.gif') }}" align="middle" alt="cargador"> &nbsp;
        <br>
    </div>

    {!! Form::open(['route' => 'home', 'method' => 'GET', 'class' => 'navbar-form navbar-left', 'role' => 'search']) !!}
        <div class="form-group">
            {!! Form::text('descrip', null, ['class' => 'form-control', 'placeholder' => 'Buscar']) !!}            
        </div>
        <button type="submit" class="btn btn-default">Buscar</button>
    {!! Form::close() !!}

    <div id="tablaArticulos">
        @yield('tablaArt')
    </div>
    

</div>


@endsection
