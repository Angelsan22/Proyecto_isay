@extends('clientes.layout')
@section('title', 'Pago Seguro — Maccuin')

@push('styles')
<style>
    .checkout-container {
        max-width: 1100px;
        margin: 0 auto;
    }
    .checkout-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 40px;
        box-shadow: var(--shadow-soft);
    }
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    .section-header i { color: var(--naranja); font-size: 1.3rem; }
    .section-header h4 { font-weight: 800; margin: 0; font-size: 1.15rem; color: var(--text-main); }

    .form-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }
    .form-control {
        background: var(--bg-body) !important;
        border: 2px solid var(--border-color) !important;
        border-radius: 14px !important;
        padding: 12px 18px !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        transition: 0.3s;
    }
    .form-control:focus {
        border-color: var(--naranja) !important;
        box-shadow: 0 0 0 4px rgba(232, 103, 27, 0.15) !important;
        outline: none;
    }
    .form-control::placeholder { color: var(--text-muted); opacity: 0.5; }

    /* Summary Side */
    .summary-card {
        background: rgba(232, 103, 27, 0.03);
        border: 1px solid rgba(232, 103, 27, 0.1);
        border-radius: 28px;
        padding: 30px;
        position: sticky;
        top: 100px;
    }
    .summary-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .summary-img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: white;
        padding: 5px;
        object-fit: contain;
    }
    .summary-info { flex: 1; }
    .summary-name { font-size: 0.85rem; font-weight: 700; color: var(--text-main); display: block; }
    .summary-qty { font-size: 0.75rem; color: var(--text-muted); }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--text-muted);
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed var(--border-color);
        font-weight: 950;
        font-size: 1.4rem;
        color: var(--naranja);
    }

    .btn-pay {
        background: var(--naranja);
        color: white;
        border: none;
        padding: 18px;
        border-radius: 18px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        box-shadow: 0 10px 25px rgba(232, 103, 27, 0.3);
    }
    .btn-pay:hover {
        background: var(--naranja-hover);
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(232, 103, 27, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container py-5 checkout-container">
    <div class="mb-4">
        <a href="{{ route('cliente.pedidos.crear') }}" class="text-muted fw-bold text-decoration-none">
            <i class="bi bi-arrow-left me-2"></i> Volver al Carrito
        </a>
    </div>

    <h2 class="fw-black mb-5 d-flex align-items-center gap-3" style="font-size: 2.2rem;">
        <i class="bi bi-shield-lock-fill text-success"></i> Pago Seguro
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius: 20px; border: none; background: rgba(239, 68, 68, 0.15); color: #ef4444;">
            <ul class="mb-0 fw-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cliente.pedidos.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-5">
            {{-- Left: Shipping & Payment --}}
            <div class="col-lg-7">
                
                {{-- Shipping Section --}}
                <div class="checkout-card mb-4">
                    <div class="section-header">
                        <i class="bi bi-geo-alt-fill"></i>
                        <h4>Información de Envío</h4>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre_completo" class="form-control" placeholder="Ej. Juan Pérez" value="{{ auth()->user()->nombre }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Dirección de Entrega</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Calle, Número y Colonia" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control" placeholder="Ej. Querétaro" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Código Postal</label>
                            <input type="text" name="cp" class="form-control" placeholder="Ej. 76000" maxlength="5" required>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Teléfono de Contacto</label>
                        <input type="text" name="telefono" class="form-control" placeholder="10 dígitos" maxlength="10" required>
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="checkout-card">
                    <div class="section-header">
                        <i class="bi bi-credit-card-2-back-fill"></i>
                        <h4>Método de Pago</h4>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Número de Tarjeta</label>
                        <div class="position-relative">
                            <input type="text" name="num_tarjeta" id="card_num" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19" value="{{ old('num_tarjeta') }}" required>
                            <i class="bi bi-credit-card position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nombre en la Tarjeta</label>
                        <input type="text" name="nombre_tarjeta" class="form-control" placeholder="COMO APARECE EN LA TARJETA" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-0">
                            <label class="form-label">Expiración</label>
                            <input type="text" name="expiracion" id="card_exp" class="form-control" placeholder="MM/AA" maxlength="5" required>
                        </div>
                        <div class="col-md-6 mb-0">
                            <label class="form-label">CVV</label>
                            <input type="password" name="cvv" id="card_cvv" class="form-control" placeholder="123" maxlength="3" required>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right: Order Summary --}}
            <div class="col-lg-5">
                <div class="summary-card">
                    <div class="section-header" style="border-bottom-color: rgba(232,103,27,0.1);">
                        <i class="bi bi-receipt"></i>
                        <h4>Tu Pedido</h4>
                    </div>

                    <div class="mb-4">
                        @foreach($items as $item)
                        <div class="summary-item">
                            <img src="{{ $item['imagen'] }}" class="summary-img" alt="">
                            <div class="summary-info">
                                <span class="summary-name text-truncate" style="max-width:200px;">{{ $item['nombre'] }}</span>
                                <span class="summary-qty">Cant: {{ $item['cantidad'] }}</span>
                            </div>
                            <div class="fw-bold" style="color:var(--text-main);">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <hr style="opacity:0.1;">

                    <div class="price-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>IVA (16%)</span>
                        <span>${{ number_format($iva, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Costo de Envío</span>
                        <span>$25.00</span>
                    </div>

                    <div class="total-row">
                        <span>Total Final</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>

                    <button type="submit" class="btn btn-pay w-100 mt-4">
                        PAGAR AHORA <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                    </button>

                    <p class="text-center text-muted small mt-3 fw-medium">
                        <i class="bi bi-lock-fill me-1"></i> Transacción cifrada de extremo a extremo
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Validaciones y Máscaras
    document.getElementById('card_num').addEventListener('input', function(e) {
        let v = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let matches = v.match(/\d{4,16}/g);
        let match = matches && matches[0] || '';
        let parts = [];
        for (let i=0, len=match.length; i<len; i+=4) {
            parts.push(match.substring(i, i+4));
        }
        if (parts.length) {
            e.target.value = parts.join(' ');
        } else {
            e.target.value = v;
        }
    });

    document.getElementById('card_exp').addEventListener('input', function(e) {
        let v = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        if (v.length > 2) {
            let m = v.substring(0, 2);
            let a = v.substring(2, 4);
            // Validar meses 01-12
            if (parseInt(m) > 12) m = '12';
            if (parseInt(m) === 0) m = '01';
            e.target.value = m + '/' + a;
        } else {
            e.target.value = v;
        }
    });

    document.getElementById('card_cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/gi, '');
    });
</script>
@endsection
