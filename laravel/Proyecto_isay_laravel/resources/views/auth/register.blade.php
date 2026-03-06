<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MACUIN | Crear Cuenta</title>
    
    <!-- Fonts e Iconos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Urbanist:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Archivo CSS Único -->
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
</head>
<body>

    <div class="container-fluid">
        
        <!-- Panel Izquierdo: Formulario -->
        <main class="form-panel">
            <header class="brand-header">
                <div class="logo-text">MACUIN <i class="fa-solid fa-car-side"></i></div>
                <a href="{{ route('login') }}" class="nav-link-top">INICIAR SESIÓN</a>
            </header>

            <div class="form-container">
                <h1 class="form-title">Crear Cuenta</h1>

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-grid">
                        <!-- RF01: Captura de Nombre -->
                        <div class="form-group">
                            <label class="label-text">Nombre</label>
                            <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" 
                                   placeholder="Nombre de usuario" value="{{ old('name') }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="label-text">Apellidos</label>
                            <input type="text" name="last_name" class="form-input" 
                                   placeholder="Apellidos" value="{{ old('last_name') }}" required>
                        </div>

                        <!-- RF01: Captura de Correo -->
                        <div class="form-group full-width">
                            <label class="label-text">Correo</label>
                            <input type="email" name="email" class="form-input @error('email') is-invalid @enderror" 
                                   placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <!-- RF01: Captura de Contraseña -->
                        <div class="form-group">
                            <label class="label-text">Contraseña</label>
                            <input type="password" name="password" class="form-input @error('password') is-invalid @enderror" 
                                   placeholder="********" required>
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <!-- RF03: Confirmación de Contraseña -->
                        <div class="form-group">
                            <label class="label-text">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-input" 
                                   placeholder="********" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Crear cuenta
                    </button>

                    <div class="footer-link">
                        o <a href="{{ route('login') }}">Iniciar Sesión</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Panel Derecho: Imagen y Frase -->
        <aside class="image-panel-right">
            <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1966&auto=format&fit=crop" alt="Coche deportivo de lujo">
            <div class="image-overlay">
                <div class="overlay-text">
                    La excelencia empieza<br>bajo el capó.
                </div>
            </div>
        </aside>

    </div>

</body>
</html>