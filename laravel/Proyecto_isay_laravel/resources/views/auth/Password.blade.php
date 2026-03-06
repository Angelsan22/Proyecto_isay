<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Recuperar Contraseña</title>
    
    <!-- Fonts e Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Urbanist:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Unificado para todas las interfaces de Auth -->
    <link rel="stylesheet" href="{{ asset('css/macuin.css') }}">
</head>
<body>

    <div class="container-fluid">
        
        <!-- Panel Izquierdo: Formulario de Recuperación -->
        <main class="form-panel">
            <header class="brand-header">
                <div class="logo-text">MACUIN <i class="fa-solid fa-car-side"></i></div>
                <a href="{{ route('login') }}" class="nav-link-top">INICIAR SESIÓN</a>
            </header>

            <div class="form-container">
                <h1 class="form-title">Recuperar Contraseña</h1>

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <!-- Campo Correo -->
                    <div class="form-group">
                        <label class="label-text">Correo</label>
                        <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" 
                               placeholder="hello@reallygreatsite.com" value="{{ old('email') }}" required autofocus>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <!-- Código de Verificación -->
                    <div class="form-group">
                        <label class="label-text">Código de verificación</label>
                        <input type="text" name="code" class="form-input" placeholder="**********" required>
                    </div>

                    <!-- Grid de nuevas contraseñas -->
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="label-text">Contraseña nueva</label>
                            <input type="password" name="password" class="form-input @error('password') is-invalid @enderror" 
                                   placeholder="**********" required>
                        </div>
                        <div class="form-group">
                            <label class="label-text">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-input" 
                                   placeholder="**********" required>
                        </div>
                    </div>
                    
                    @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror

                    <button type="submit" class="btn-orange">
                        Cambiar contraseña
                    </button>

                    <div class="footer-link">
                        o <a href="{{ route('login') }}">Regresar a inicio</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Panel Derecho: Imagen y Frase (Solicitado) -->
        <aside class="image-panel-right">
            <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?q=80&w=1974&auto=format&fit=crop" alt="Reparación de motor">
            <div class="image-overlay">
                <div class="overlay-text">
                    Recupera el control,<br>vuelve a la pista.
                </div>
            </div>
        </aside>

    </div>

</body>
</html>