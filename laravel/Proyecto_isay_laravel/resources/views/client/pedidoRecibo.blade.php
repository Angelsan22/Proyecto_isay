{{-- resources/views/client/pedido-recibo.blade.php --}}
{{-- Esta vista se renderiza como PDF con DomPDF o Browsershot --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pedido — MACUIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <style>
        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange:      #f05a24;
            --text:        #111827;
            --text-muted:  #6b7280;
            --border:      #e5e7eb;
            --bg-light:    #f9fafb;
            --font-d:      'Barlow Condensed', sans-serif;
            --font-b:      'DM Sans', sans-serif;
        }

        body {
            font-family: var(--font-b);
            background: #fff;
            color: var(--text);
            font-size: 13px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .receipt {
            max-width: 680px;
            margin: 0 auto;
            padding: 48px 48px 36px;
            background: #fff;
        }

        /* ── Header ── */
        .receipt-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--border);
            margin-bottom: 28px;
        }
        .receipt-logo {
            font-family: var(--font-d);
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: .05em;
            color: var(--text);
            line-height: 1;
        }
        .receipt-logo span { color: var(--orange); }
        .receipt-company-sub {
            font-size: .7rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .receipt-title-block {
            text-align: right;
        }
        .receipt-title {
            font-family: var(--font-d);
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: .04em;
            color: var(--text);
            text-transform: uppercase;
        }
        .receipt-number {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .receipt-number strong {
            color: var(--orange);
            font-weight: 700;
        }

        /* ── Info Block ── */
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }
        .info-block-title {
            font-family: var(--font-d);
            font-size: .85rem;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
        }
        .info-row {
            display: flex;
            gap: 6px;
            margin-bottom: 5px;
            font-size: .82rem;
        }
        .info-row .label {
            color: var(--text-muted);
            min-width: 90px;
        }
        .info-row .value {
            font-weight: 600;
            color: var(--text);
        }

        /* ── Items Table ── */
        .items-title {
            font-family: var(--font-d);
            font-size: .85rem;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead tr {
            background: var(--bg-light);
            border-radius: 6px;
        }
        th {
            padding: 9px 12px;
            text-align: left;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }
        th:last-child, td:last-child { text-align: right; }
        td {
            padding: 11px 12px;
            font-size: .84rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        tbody tr:last-child td { border-bottom: 2px solid var(--border); }
        tbody tr:hover { background: var(--bg-light); }
        td.td-qty { color: var(--text-muted); text-align: center; }
        td.td-price, td.td-total { font-family: var(--font-d); font-size: .95rem; font-weight: 700; letter-spacing: .02em; }
        td.td-total { color: var(--text); }

        /* ── Totals ── */
        .totals-block {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .totals-table {
            width: 260px;
        }
        .totals-table td {
            border: none;
            padding: 5px 0;
        }
        .totals-table .t-label {
            color: var(--text-muted);
            font-size: .82rem;
        }
        .totals-table .t-value {
            text-align: right;
            font-weight: 600;
            font-size: .84rem;
        }
        .totals-table .t-grand .t-label {
            font-family: var(--font-d);
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: .04em;
        }
        .totals-table .t-grand .t-value {
            font-family: var(--font-d);
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--orange);
        }
        .totals-table .t-divider td {
            padding-top: 8px;
            border-top: 2px solid var(--border);
        }

        /* ── Footer ── */
        .receipt-footer {
            border-top: 1px solid var(--border);
            padding-top: 18px;
            text-align: center;
        }
        .receipt-footer p {
            font-size: .75rem;
            color: var(--text-muted);
            line-height: 1.8;
        }
        .receipt-footer strong { color: var(--text); }

        /* Orange accent line top */
        .receipt::before {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), #fbb25a);
            border-radius: 4px;
            margin-bottom: 36px;
        }

        @media print {
            body { font-size: 12px; }
            .receipt { padding: 24px; }
        }
    </style>
</head>
<body>
<div class="receipt">

    <!-- Header -->
    <div class="receipt-header">
        <div>
            <div class="receipt-logo">MAC<span>UIN</span></div>
            <p class="receipt-company-sub">Refacciones Automotrices</p>
        </div>
        <div class="receipt-title-block">
            <div class="receipt-title">Comprobante de Pedido</div>
            <p class="receipt-number">No. de Pedido: <strong>[FLSK-2026-0001]</strong></p>
        </div>
    </div>

    <!-- Info -->
    <div class="receipt-info">
        <div>
            <div class="info-block-title">Información del Pedido</div>
            <div class="info-row"><span class="label">Fecha</span><span class="value">01/02/2026</span></div>
            <div class="info-row"><span class="label">Estado</span><span class="value">Confirmado</span></div>
            <div class="info-row"><span class="label">Método Envío</span><span class="value">Mensajería Express</span></div>
        </div>
        <div>
            <div class="info-block-title">Datos del Cliente</div>
            <div class="info-row"><span class="label">Cliente</span><span class="value">{{ $order->user->name ?? '[Juan Pérez]' }}</span></div>
            <div class="info-row"><span class="label">Email</span><span class="value">{{ $order->user->email ?? '[juan.perez@email.com]' }}</span></div>
            <div class="info-row"><span class="label">Teléfono</span><span class="value">{{ $order->user->phone ?? '—' }}</span></div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="items-title">Detalle de Artículos</div>
    <table>
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Descripción</th>
                <th>SKU</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $items = [
                ['qty'=>1,'name'=>'Aceite Sintético 5W-30','sku'=>'ACT-5W30','unit'=>450.00],
                ['qty'=>4,'name'=>'Bujías de Iridio NGK','sku'=>'BUJ-NGK','unit'=>150.00],
                ['qty'=>1,'name'=>'Filtro de Aceite Mann-Filter','sku'=>'FLT-001','unit'=>280.00],
                ['qty'=>2,'name'=>'Pastillas Freno Delanteras','sku'=>'FRN-045','unit'=>650.00],
            ];
            @endphp

            @foreach($items as $item)
            <tr>
                <td class="td-qty">{{ $item['qty'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td style="color:var(--text-muted);font-size:.78rem;">{{ $item['sku'] }}</td>
                <td class="td-price">${{ number_format($item['unit'],2) }}</td>
                <td class="td-total">${{ number_format($item['unit'] * $item['qty'],2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-block">
        <table class="totals-table">
            <tr>
                <td class="t-label">Subtotal</td>
                <td class="t-value">$2,880.00</td>
            </tr>
            <tr>
                <td class="t-label">Envío</td>
                <td class="t-value">$150.00</td>
            </tr>
            <tr class="t-divider">
                <td></td><td></td>
            </tr>
            <tr class="t-grand">
                <td class="t-label">TOTAL GENERAL</td>
                <td class="t-value">$3,030.00</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="receipt-footer">
        <p>
            Gracias por tu compra. Vuelve pronto.<br>
            <strong>MACUIN — Refacciones Automotrices</strong> &nbsp;·&nbsp; macuin.com<br>
            <span style="font-size:.7rem;">Precios en MXN. Generado automáticamente el 01/02/2026.</span>
        </p>
    </div>

</div>

<script src="{{ asset('js/pedidoRecibo.js') }}"></script>
</body>
</html>