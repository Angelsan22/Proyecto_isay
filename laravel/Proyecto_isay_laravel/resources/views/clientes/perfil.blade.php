@extends('clientes.layout')
@section('title', 'Mi Perfil — Maccuin')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--naranja) 0%, #d45a15 100%);
        border-radius: 28px;
        padding: 40px;
        color: white;
        text-align: center;
        box-shadow: 0 10px 30px rgba(232, 103, 27, 0.25);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .profile-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        top: -100px;
        right: -50px;
        border-radius: 50%;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border: 4px solid rgba(255,255,255,0.2);
    }
    .profile-avatar i {
        font-size: 3rem;
        color: var(--naranja);
    }
    .profile-name {
        font-size: 1.8rem;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 5px;
    }
    .profile-email {
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .form-control-macuin {
        background: var(--bg-body);
        border: 2px solid var(--border-color);
        padding: 14px 18px;
        border-radius: 14px;
        font-weight: 500;
        color: var(--text-main);
        transition: all 0.3s ease;
    }
    .form-control-macuin:focus {
        background: var(--bg-card);
        border-color: var(--naranja);
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.1);
        color: var(--text-main) !important;
        outline: none;
    }
    .form-control-macuin::placeholder {
        color: var(--text-muted);
        opacity: 0.65;
    }
    .form-control-macuin:disabled {
        background: rgba(128, 128, 128, 0.08) !important;
        color: var(--text-muted) !important;
        border-color: rgba(128, 128, 128, 0.15) !important;
        cursor: not-allowed;
    }
    .form-label-macuin {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-main);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width: 800px;">
    
    <div class="profile-header fade-in-up">
        <div class="profile-avatar">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="profile-name">{{ $user->name }} {{ $user->apellidos }}</div>
        <div class="profile-email"><i class="bi bi-envelope-fill me-2"></i>{{ $user->email }}</div>
    </div>

    <div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
        <!-- Información de Cuenta -->
        <div class="col-md-6">
            <div class="card card-macuin h-100 border-0 p-4">
                <h4 class="fw-black mb-4 d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;border-radius:12px;background:var(--naranja-soft);display:flex;align-items:center;justify-content:center;color:var(--naranja);">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    Tus Datos
                </h4>
                
                <form action="{{ route('cliente.perfil.datos') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label-macuin">Nombre</label>
                            <input type="text" name="name" class="form-control form-control-macuin" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label-macuin">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control form-control-macuin" value="{{ old('apellidos', $user->apellidos) }}" required>
                            @error('apellidos')
                                <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-macuin">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-control-macuin" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-naranja w-100 py-3" style="font-size:0.95rem; background: var(--bg-body); border: 2px solid var(--naranja); color: var(--naranja);">
                        <i class="bi bi-save me-2"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <!-- Cambio de Contraseña -->
        <div class="col-md-6">
            <div class="card card-macuin h-100 border-0 p-4">
                <h4 class="fw-black mb-4 d-flex align-items-center gap-2">
                    <div style="width:40px;height:40px;border-radius:12px;background:var(--naranja-soft);display:flex;align-items:center;justify-content:center;color:var(--naranja);">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    Seguridad
                </h4>

                <form action="{{ route('cliente.perfil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label-macuin">Contraseña Actual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 position-absolute" style="z-index: 10; padding: 15px 20px;">
                                <i class="bi bi-key-fill text-muted"></i>
                            </span>
                            <input type="password" name="current_password" class="form-control form-control-macuin ps-5" placeholder="Ingresa tu contraseña actual" required>
                        </div>
                        @error('current_password')
                            <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-macuin">Nueva Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control form-control-macuin" placeholder="Mínimo 8 caracteres" required minlength="8">
                        @error('password')
                            <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-macuin">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control form-control-macuin" placeholder="Repite tu nueva contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-naranja w-100 py-3" style="font-size:0.95rem;">
                        Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
