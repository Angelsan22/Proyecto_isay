/**
 * MACUIN — create-order.js
 * Lógica del carrito y búsqueda de productos
 */

const cart = {};

/* ── Carrito ── */
function addToCart(id, name, price) {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1 };
    }
    renderCart();
    pulseCartBadge();
}

function removeFromCart(id) {
    delete cart[id];
    renderCart();
}

function changeQty(id, delta) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function renderCart() {
    const list    = document.getElementById('cart-list');
    const empty   = document.getElementById('cart-empty');
    const footer  = document.getElementById('cart-footer');
    const countEl = document.getElementById('cart-count');

    const items      = Object.entries(cart);
    const totalItems = items.reduce((sum, [, v]) => sum + v.qty, 0);

    countEl.textContent   = totalItems;
    countEl.style.display = totalItems ? 'flex' : 'none';

    if (!items.length) {
        list.innerHTML        = '';
        empty.style.display   = 'flex';
        footer.style.display  = 'none';
        return;
    }

    empty.style.display  = 'none';
    footer.style.display = 'block';

    let subtotal = 0;

    list.innerHTML = items.map(([id, item]) => {
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;
        return `
        <li class="cart-item">
            <div class="cart-item-info">
                <span class="cart-item-name">${item.name}</span>
                <span class="cart-item-price">$${item.price.toFixed(2)} c/u</span>
            </div>
            <div class="cart-item-controls">
                <button onclick="changeQty(${id}, -1)"><i class="fa-solid fa-minus"></i></button>
                <span>${item.qty}</span>
                <button onclick="changeQty(${id}, 1)"><i class="fa-solid fa-plus"></i></button>
                <button class="cart-remove" onclick="removeFromCart(${id})"><i class="fa-solid fa-trash"></i></button>
            </div>
            <span class="cart-item-total">$${lineTotal.toFixed(2)}</span>
        </li>`;
    }).join('');

    document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('cart-total').textContent    = '$' + (subtotal + 25).toFixed(2);
}

/* ── Drawer ── */
function openCart() {
    document.getElementById('cart-drawer').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeCart() {
    document.getElementById('cart-drawer').classList.remove('open');
    document.getElementById('cart-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

function pulseCartBadge() {
    const badge = document.getElementById('cart-count');
    badge.classList.remove('pulse');
    void badge.offsetWidth; // reflow para reiniciar animación
    badge.classList.add('pulse');
    setTimeout(() => badge.classList.remove('pulse'), 400);
}

/* ── Envío del formulario ── */
function prepareSubmit() {
    document.getElementById('cart-data-input').value = JSON.stringify(cart);
}

/* ── Búsqueda ── */
function initSearch() {
    const input      = document.getElementById('search-input');
    const countLabel = document.getElementById('product-count');

    if (!input) return;

    input.addEventListener('input', function () {
        const query   = this.value.toLowerCase().trim();
        let   visible = 0;

        document.querySelectorAll('.product-card').forEach(card => {
            const match = card.dataset.name.includes(query) || card.dataset.sku.includes(query);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        countLabel.textContent = visible + ' producto' + (visible !== 1 ? 's' : '');
    });

    // Atajo de teclado ⌘K / Ctrl+K
    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            input.focus();
            input.select();
        }
    });
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('btn-open-cart')
        ?.addEventListener('click', openCart);

    document.getElementById('cart-overlay')
        ?.addEventListener('click', closeCart);

    // Cerrar con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeCart();
    });

    initSearch();
});