<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Fuente Libre Baskerville -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/password-reset.css') }}">
</head>
<body>
    <div class="password-reset-container d-flex flex-column align-items-center justify-content-center">
        <!-- Logo y Título -->
        <img src="{{ asset('img/Logo_Nuevo.png') }}" alt="Logo Facultad" class="logo-facultad mb-4">
        <h2 class="text-center mb-2">Recuperar Contraseña</h2>
        <p class="text-center text-muted">Ingrese su correo electrónico y le enviaremos un enlace para restablecer su contraseña</p>

        <!-- Formulario -->
        <form method="POST" action="{{ route('iniciarSesion') }}" class="password-reset-card">
            @csrf
            <div class="form-group mb-4">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Ingrese su correo" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger btn-block rounded-pill">Enviar Enlace</button>

            <div class="text-center mt-3">
                <p>¿Recordó su contraseña? <a href="{{ route('iniciarSesion') }}" class="text-danger">Iniciar Sesión</a></p>
            </div>
        </form>
    </div>
</body>
</html>
