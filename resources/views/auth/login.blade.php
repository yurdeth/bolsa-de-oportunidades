<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Fuente Libre Baskerville -->
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container d-flex flex-column align-items-center justify-content-center">
        <!-- Logo y Título -->
        <img src="{{ asset('img/Logo_Nuevo.png') }}" alt="Logo Facultad" class="logo-facultad mb-4">
        <h2 class="text-center mb-2">Bienvenido a Bolsa de Oportunidades</h2>


        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="login-card">
            @csrf
            <div class="form-group mb-4">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div class="input-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Ingrese su correo" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Ingrese su contraseña" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group d-flex justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <a href="{{ route('request-password-reset') }}" class="text-danger">¿Ha olvidado tu contraseña?</a>
            </div>

            <button type="submit" class="btn btn-danger btn-block rounded-pill">Ingresar</button>

            <div class="text-center mt-3">
                <p>¿No estás registrado? <a href="{{ route('register') }}" class="text-danger">Regístrate Aquí</a></p>
            </div>
        </form>
    </div>

    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
