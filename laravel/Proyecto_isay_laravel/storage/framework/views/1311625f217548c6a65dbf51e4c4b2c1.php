<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --naranja: #E8671B; }
        * { box-sizing: border-box; }
        body { min-height: 100vh; display: flex; margin: 0; font-family: sans-serif; }
        .panel-imagen {
            flex: 1;
            background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
            url('https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=900') center/cover no-repeat;
            display: flex; flex-direction: column; align-items: flex-start;
            justify-content: flex-end; padding: 3rem;
        }
        .panel-imagen h2 { color: #fff; font-size: 2.2rem; font-weight: 800; line-height: 1.2; }
        .panel-imagen h2 span { color: var(--naranja); }
        .panel-form {
            width: 480px; min-width: 380px;
            display: flex; align-items: center; justify-content: center;
            background: #fff; padding: 3rem 2.5rem;
        }
        .panel-form .inner { width: 100%; max-width: 380px; }
        .btn-naranja { background: var(--naranja); color: #fff; border: none; padding: .8rem; font-size: 1rem; border-radius: .5rem; }
        .btn-naranja:hover { background: #c95510; color: #fff; }
        .form-control { border-radius: .5rem; }
        .input-group-text { background: #fff; border-right: none; }
        .input-group .form-control { border-left: none; }
        a { color: var(--naranja); text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media(max-width:768px){ .panel-imagen{ display:none; } .panel-form{ width:100%; } }
    </style>
</head>
<body>

<div class="panel-imagen">
    <h2>Pasión por el <span>Motor.</span><br>Precisión en cada Pieza.</h2>
    <p class="text-white-50 mt-2 mb-0">Tu plataforma de autopartes de confianza</p>
</div>

<div class="panel-form">
    <div class="inner">
        <p class="text-muted small mb-1"><i class="bi bi-gear"></i> Venta & Refacciones</p>
        <h3 class="fw-bold mb-1">Tu Garaje Personal</h3>
        <p class="text-muted mb-4">Ingresa tus datos para continuar</p>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form action="<?php echo e(route('cliente.login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control"
                       placeholder="Correo Electrónico" value="<?php echo e(old('email')); ?>" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control"
                       placeholder="Contraseña" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember">Recordar sesión</label>
            </div>
            <button type="submit" class="btn btn-naranja w-100 fw-bold">
                Acceder al Taller
            </button>
        </form>

        <div class="text-center mt-3 d-flex flex-column gap-1">
            <a href="<?php echo e(route('cliente.recuperar')); ?>">¿Olvidaste tu contraseña?</a>
            <a href="<?php echo e(route('cliente.registro')); ?>">Crear una cuenta</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\Proyecto_ISAY\Proyecto_isay\laravel\Proyecto_isay_laravel\resources\views/clientes/auth/login.blade.php ENDPATH**/ ?>