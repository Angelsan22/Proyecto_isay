<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Iniciar Sesión</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Urbanist:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vinculación al CSS de Login -->
    <link rel="stylesheet" href="{{ asset('css/macuin.css') }}">
</head>
<body>

    <div class="container-fluid">
        <!-- Panel Izquierdo: Visual -->
        <div class="image-panel">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=2070&auto=format&fit=crop" alt="Coche deportivo">
            <div class="image-overlay">
                <div class="slogan-text">
                    Pasión por el Motor.<br>
                    Precisión en cada Pieza.
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Formulario de Login -->
        <div class="form-panel">
            <div class="form-container">
                <h1 class="form-title">Tu Garaje Personal</h1>

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <div class="input-icon-wrapper">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" class="form-input" placeholder="Correo Electrónico" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="password" class="form-input" placeholder="Contraseña" required>
                        </div>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" style="color: var(--text-light); font-size: 0.875rem;">Recordar sesión</label>
                    </div>

                    <button type="submit" class="btn-primary-macuin">
                        Acceder al Taller
                    </button>

                    <div class="auth-links">
                        <!-- Ruta vinculada al PasswordController -->
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        <a href="{{ route('register') }}" class="link-highlight">Crear una cuenta</a>
                    </div>

                    <div class="footer-brand">
                        <i class="fa-solid fa-gear"></i> Venta & Refacciones
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>