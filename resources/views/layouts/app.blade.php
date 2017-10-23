<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('head')
    <!-- <title>{{ config('app.name', 'Laravel') }}</title>-->
    <link rel="icon" href="../../favicon.ico">

    <!-- DataTables -->
    <link href="{{ asset('assets/dataTables/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <!-- BUTTONS -->
    <link href="{{ asset('assets/dataTables/buttons/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <!-- RESPONSIVE -->
    <link href="{{ asset('assets/dataTables/responsive/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <!-- FIN DataTables -->
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/custom/css/admin.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <!-- <div id="app"> -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('home') }}"><img src="{{ asset ('/images/images/logorg.png') }}" alt="Logo"></a>
                </div>

                <div class="collapse navbar-collapse" id="navbar">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Pedidos <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">                  
                                    <li><a href="{{ url('pedido') }}">Mis Pedidos</a>                                    
                                    </li>
                                </ul>
                            </li>-->
                        @role('Cliente')
                            <li><a href="{{ url('pedido/lista') }}">Lista de precios</a> </li>
                            <li><a href="{{ url('pedido') }}">Mis Pedidos</a> </li>
                        @endrole
                        @if (Auth::guest())
                            <li><a href="{{ url('lista') }}">Lista de precios</a> </li>
                            <li><a href="{{ url('login') }}">Login</a> </li>
                          <!--  <li><a href="{{ url('/login') }}">Login</a></li>
                           <li><a href="{{ url('/register') }}">Register</a></li> -->
                        @else
                            @role('Administrador')
                            <li> <a href="{{ url('home') }}">Inicio</a> </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Usuarios <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">    
                                    <li><a href="{{ url('/register') }}">Registrar Usuario</a></li> 
                                    <li><a href="{{ url('/user') }}">Listado de Usuarios</a></li> 
                                </ul>
                            </li>
                            @endrole
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">    
                                    <li><a href="{{ url('user/password') }}">Cambiar Contrase&ntilde;a</a></li> 
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Cerrar Sesi&oacute;n
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container paddingBottom">

            @yield('content')

        </div>

        <footer class="footer navbar-fixed-bottom ">
            <div class="container col-md-4 col-md-offset-8">
            <p class="text-muted">Contacto:&nbsp;&nbsp;repuestosgonnetsa@yahoo.com.ar</p>
            </div>
        </footer>

    <!-- </div> id=app-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <!-- <script src="../../dist/js/bootstrap.min.js"></script> -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script> 
    <!-- DataTables -->
    <script src="{{ asset('assets/dataTables/js/jquery.dataTables.min.js') }}"></script> 
    <script src="{{ asset('assets/dataTables/js/dataTables.bootstrap.min.js') }}"></script> 
    <!-- BUTTONS -->
    <script src="{{ asset('assets/dataTables/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/dataTables/buttons/js/buttons.bootstrap.min.js') }}"></script> 
    <script src="{{ asset('assets/dataTables/buttons/js/buttons.html5.min.js') }}"></script>
    <!-- RESPONSIVE -->
    <script src="{{ asset('assets/dataTables/responsive/js/dataTables.responsive.min.js') }}"></script>
    <!-- JSZIP -->
    <script src="{{ asset('assets/JSZip/jszip.min.js') }}"></script> 
    <!-- FIN DataTables -->
    <script src="{{ asset('assets/custom/js/admin.js') }}"></script>
    @stack('scripts') 
</body>
</html>
