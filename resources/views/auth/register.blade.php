@extends('layouts.app')

@section('content')
<br> <br>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2"> 
        @if(!empty($email))
                <div class="aprobado right"> <label> Se ha enviado un email a: {{ $email }} con el link de confirmaci&oacute;n.</label></div>
        @endif            
        </div>
        <div class="col-md-8 col-md-offset-2">    
            <div class="panel panel-default">
                <div class="panel-heading">Registrar Usuario</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('codCliente') ? ' has-error' : '' }}">
                            <label for="codCliente" class="col-md-4 control-label">C&oacute;digo de cliente</label>

                            <div class="col-md-6">
                                <input id="codCliente" type="text" class="form-control" name="codCliente" required>

                                @if ($errors->has('codCliente'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('codCliente') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 

                <!--        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Contraseña</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>  --> 

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="right"> <label> La contrase&ntilde;a inicial ser&aacute; el c&oacute;digo de cliente</label></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
