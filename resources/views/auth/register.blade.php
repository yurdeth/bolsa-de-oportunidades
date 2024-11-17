<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Fuente Libre Baskerville -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="register-container d-flex flex-column align-items-center justify-content-center">
        <!-- Logo y Título -->
        <img src="{{ asset('img/Logo_Nuevo.png') }}" alt="Logo Facultad" class="logo-facultad mb-4">
        <h2 class="text-center mb-2">Registrar una Cuenta</h2>
        <p class="text-center text-muted">Complete los campos para crear su cuenta</p>

        <!-- Formulario -->
        <form method="POST" action="{{ route('register') }}" class="register-card">
            @csrf
            <div class="form-group mb-4">
                <label for="name" class="form-label">Nombre Completo</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Ingrese su nombre completo" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Ingrese su correo" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Cree una contraseña" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirme su contraseña" required>
            </div>

            <button type="submit" class="btn btn-danger btn-block rounded-pill">Registrar</button>

            <div class="text-center mt-3">
                <p>¿Ya tienes cuenta? <a href="{{ route('iniciarSesion') }}" class="text-danger">Iniciar Sesión</a></p>
            </div>
        </form>
    </div>
</body>
</html>
