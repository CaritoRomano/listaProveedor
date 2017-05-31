@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-lg-12">
        <h2>Lista de precios</h2>
        <p>Febrero 2017</p>
    </div>

    <div class="col-lg-12">    
    {!! Form::open(['route' => 'home', 'method' => 'GET', 'class' => 'navbar-form navbar-left', 'role' => 'search']) !!}
        <div class="form-group">
            {!! Form::text('descrip', null, ['class' => 'form-control', 'placeholder' => 'Buscar']) !!}            
        </div>
        <button type="submit" class="btn btn-default">Buscar</button>
    {!! Form::close() !!}
    </div>

    <div class = "col-lg-12">
        @yield('tablaArt')
    </div>
    

</div>


@endsection
