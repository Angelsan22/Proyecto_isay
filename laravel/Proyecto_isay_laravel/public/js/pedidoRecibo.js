/**
 * MACUIN — order-receipt.js
 * Auto-impresión del comprobante de pedido
 */

document.addEventListener('DOMContentLoaded', function () {

    // Si la URL incluye ?print=1, lanza el diálogo de impresión automáticamente
    const params = new URLSearchParams(window.location.search);

    if (params.get('print') === '1') {
        window.addEventListener('load', function () {
            setTimeout(() => window.print(), 300);
        });
    }

    // Botón de impresión manual (si existe en la vista)
    document.getElementById('btn-print')
        ?.addEventListener('click', () => window.print());

});