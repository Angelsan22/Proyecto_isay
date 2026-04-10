<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $pedido->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #fff;
        }

        /* ---- Header ---- */
        .header {
            background: #1a1a1a;
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-name {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -1px;
        }
        .brand-name span { color: #E8671B; }
        .brand-sub { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 4px; }

        .invoice-label {
            text-align: right;
        }
        .invoice-label .title {
            font-size: 28px;
            font-weight: 900;
            color: #E8671B;
            letter-spacing: -1px;
        }
        .invoice-label .num {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
        }

        /* ---- Orange stripe ---- */
        .stripe {
            height: 5px;
            background: #E8671B;
        }

        /* ---- Body ---- */
        .body { padding: 35px 40px; }

        /* Info grid */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-col {
            display: table-cell;
            width: 33%;
            vertical-align: top;
            padding-right: 20px;
        }
        .info-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            display: block;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-entregado { background: #d1fae5; color: #065f46; }
        .status-en_camino  { background: #fef3c7; color: #92400e; }
        .status-confirmado { background: #dbeafe; color: #1e40af; }
        .status-pendiente  { background: #f1f5f9; color: #475569; }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 25px 0;
        }

        /* Items table */
        .items-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #E8671B;
            margin-bottom: 15px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items thead tr {
            background: #f8fafc;
            border-radius: 8px;
        }
        table.items thead th {
            padding: 12px 15px;
            text-align: left;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        table.items thead th:last-child { text-align: right; }
        table.items tbody td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-weight: 600;
        }
        table.items tbody td:last-child { text-align: right; font-weight: 800; }
        table.items tbody tr:last-child td { border-bottom: none; }

        /* Total row */
        .total-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }
        .total-spacer  { display: table-cell; width: 60%; }
        .total-box {
            display: table-cell;
            width: 40%;
            background: #fff8f5;
            border: 2px solid #E8671B;
            border-radius: 12px;
            padding: 20px 25px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .total-key, .total-val {
            display: table-cell;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
        }
        .total-val { text-align: right; }
        .total-row.grand .total-key,
        .total-row.grand .total-val {
            font-size: 16px;
            font-weight: 900;
            color: #E8671B;
            border-top: 1px solid #fde5d4;
            padding-top: 10px;
            margin-top: 4px;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 40px;
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        .footer-left  { display: table-cell; vertical-align: middle; font-size: 11px; color: #94a3b8; }
        .footer-right { display: table-cell; vertical-align: middle; text-align: right; font-size: 11px; color: #94a3b8; }
        .footer-right span { color: #E8671B; font-weight: 700; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div>
            <div class="brand-name">Venta &amp; Refacciones <span>Maccuin</span></div>
            <div class="brand-sub">Automóviles &amp; Refacciones · RFC: VRM-2024-MX</div>
        </div>
        <div class="invoice-label">
            <div class="title">FACTURA</div>
            <div class="num">Folio #INV-{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
    <div class="stripe"></div>

    <div class="body">

        <!-- Info Row -->
        <div class="info-grid">
            <div class="info-col">
                <span class="info-label">Número de Pedido</span>
                <div class="info-value">#{{ $pedido->id }}</div>
            </div>
            <div class="info-col">
                <span class="info-label">Fecha de Emisión</span>
                <div class="info-value">{{ now()->format('d M, Y') }}</div>
            </div>
            <div class="info-col">
                <span class="info-label">Estado del Pedido</span>
                @php
                    $stClass = match($pedido->estado) {
                        'entregado' => 'status-entregado',
                        'en_camino' => 'status-en_camino',
                        'confirmado'=> 'status-confirmado',
                        default     => 'status-pendiente',
                    };
                    $stLabel = match($pedido->estado) {
                        'entregado' => 'Entregado',
                        'en_camino' => 'En Camino',
                        'confirmado'=> 'Confirmado',
                        default     => 'Pendiente',
                    };
                @endphp
                <span class="status-badge {{ $stClass }}">{{ $stLabel }}</span>
            </div>
        </div>

        <hr class="divider">

        <!-- Items -->
        <div class="items-title">Artículos del Pedido</div>

        <table class="items">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->detalles as $i => $detalle)
                    @php
                        $cant    = $detalle->cantidad ?? 1;
                        $sub     = $detalle->subtotal ?? 0;
                        $unitPx  = $cant > 0 ? $sub / $cant : $sub;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $detalle->autoparte->nombre ?? 'Autoparte' }}</td>
                        <td>{{ $cant }}</td>
                        <td>${{ number_format($unitPx, 2) }} MXN</td>
                        <td>${{ number_format($sub, 2) }} MXN</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="total-section">
            <div class="total-spacer"></div>
            <div class="total-box">
                @php $total = $pedido->detalles->sum('subtotal'); @endphp
                <div class="total-row">
                    <div class="total-key">Subtotal</div>
                    <div class="total-val">${{ number_format($total, 2) }}</div>
                </div>
                <div class="total-row">
                    <div class="total-key">IVA (16%)</div>
                    <div class="total-val">${{ number_format($total * 0.16, 2) }}</div>
                </div>
                <div class="total-row grand">
                    <div class="total-key">TOTAL</div>
                    <div class="total-val">${{ number_format($total * 1.16, 2) }} MXN</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-left">
            Este documento es un comprobante de compra generado electrónicamente.<br>
            Conserva este documento para cualquier aclaración posterior.
        </div>
        <div class="footer-right">
            Venta &amp; Refacciones <span>Maccuin</span><br>
            {{ now()->format('Y') }} · Todos los derechos reservados
        </div>
    </div>

</body>
</html>
