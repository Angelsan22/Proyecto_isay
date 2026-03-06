    /**
 * MACUIN — orders.js
 * Filtro de pestañas en la lista de pedidos
 */

document.addEventListener('DOMContentLoaded', function () {

    /* ── Filter Tabs ── */
    const tabs = document.querySelectorAll('.filter-tab');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Marcar tab activo
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;

            // Mostrar / ocultar tarjetas
            document.querySelectorAll('.order-card').forEach(card => {
                const show = filter === 'all' || card.dataset.status === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    });

    /* ── Paginación (stub — reemplazar con lógica real o AJAX) ── */
    document.querySelectorAll('.page-num').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.page-num').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // TODO: cargar página correspondiente
        });
    });

});