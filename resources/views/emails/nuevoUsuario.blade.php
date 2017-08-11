<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $usuario->name }}, ha sido registrado en <strong>Repuestos Gonnet</strong> !</h2>
    <p>Por favor confirma tu correo electrónico.</p>
    <p>Para ello simplemente debes hacer click en el siguiente enlace:</p>

    <a href="{{ url('/register/verify/' . $usuario->confirmation_code) }}">
        Clic para confirmar tu email
    </a>

    <p><strong>*La contrase&ntilde;a inicial ser&aacute; {{ $usuario->codCliente }}</strong>. Se recomienda cambiarla al iniciar sesi&oacute;n.</p>
</body>
</html>