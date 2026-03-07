/**
 * MACUIN — crearPedido.js
 * Lógica del catálogo: carrito inline, búsqueda y filtros por categoría.
 */

/* ══════════════════════════════════════════
   ESTADO
══════════════════════════════════════════ */
const cart = {}; // { [id]: { name, price, qty, img } }

/* ══════════════════════════════════════════
   CARRITO — Operaciones
══════════════════════════════════════════ */
function addToCart(id, name, price, img = '') {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { name, price: parseFloat(price), qty: 1, img };
    }
    renderCart();
    updateAddButtons();
}

function removeFromCart(id) {
    delete cart[id];
    renderCart();
    updateAddButtons();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) {
        delete cart[id];
    }
    renderCart();
    updateAddButtons();
}

function clearCart() {
    Object.keys(cart).forEach(k => delete cart[k]);
    renderCart();
    updateAddButtons();
}

/* ══════════════════════════════════════════
   CARRITO — Render
══════════════════════════════════════════ */
function renderCart() {
    const linesList     = document.getElementById('cart-lines');
    const confirmSection = document.getElementById('confirm-section');
    const subtotalEl    = document.getElementById('summary-subtotal');
    const totalEl       = document.getElementById('summary-total');

    const items = Object.entries(cart);

    if (!items.length) {
        confirmSection.style.display = 'none';
        return;
    }

    // Mostrar sección de confirmación con animación suave
    if (confirmSection.style.display === 'none') {
        confirmSection.style.display = '';
        confirmSection.style.animation = 'fadeUp 0.38s ease both';
        // Scroll suave hacia la sección
        setTimeout(() => {
            confirmSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 60);
    }

    let subtotal = 0;

    linesList.innerHTML = items.map(([id, item]) => {
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;

        const imgTag = item.img
            ? `<img src="${item.img}" alt="${item.name}" class="cart-line-img" loading="lazy">`
            : `<div class="cart-line-img" style="display:flex;align-items:center;justify-content:center;color:var(--text-dim)"><i class="fa-solid fa-image"></i></div>`;

        return `
        <li class="cart-line">
            ${imgTag}
            <div class="cart-line-info">
                <span class="cart-line-name">${item.name}</span>
                <span class="cart-line-unit">$${item.price.toFixed(2)} c/u</span>
            </div>
            <div class="cart-line-controls">
                <button class="ctrl-btn" onclick="changeQty(${id}, -1)"><i class="fa-solid fa-minus"></i></button>
                <span class="ctrl-qty">${item.qty}</span>
                <button class="ctrl-btn" onclick="changeQty(${id}, 1)"><i class="fa-solid fa-plus"></i></button>
            </div>
            <span class="cart-line-total">$${lineTotal.toFixed(2)}</span>
            <button class="cart-line-remove" onclick="removeFromCart(${id})" title="Eliminar">
                <i class="fa-solid fa-trash"></i>
            </button>
        </li>`;
    }).join('');

    const shipping = 25;
    subtotalEl.textContent = '$' + subtotal.toFixed(2);
    totalEl.textContent    = '$' + (subtotal + shipping).toFixed(2);
}

/* ══════════════════════════════════════════
   BOTONES ADD — estado visual
══════════════════════════════════════════ */
function updateAddButtons() {
    document.querySelectorAll('.btn-add').forEach(btn => {
        const id = btn.dataset.id;
        if (cart[id]) {
            btn.classList.add('in-cart');
            btn.innerHTML = `<i class="fa-solid fa-check"></i> ${cart[id].qty} en carrito`;
        } else {
            btn.classList.remove('in-cart');
            btn.innerHTML = `<i class="fa-solid fa-plus"></i> Agregar`;
        }
    });
}

/* ══════════════════════════════════════════
   SUBMIT — serializar carrito
══════════════════════════════════════════ */
function prepareSubmit() {
    const input = document.getElementById('cart-data-input');
    if (input) input.value = JSON.stringify(cart);
}

/* ══════════════════════════════════════════
   BÚSQUEDA
══════════════════════════════════════════ */
function initSearch() {
    const input      = document.getElementById('search-input');
    const countLabel = document.getElementById('product-count');
    if (!input) return;

    input.addEventListener('input', () => applyFilters());

    // Atajo Ctrl/Cmd + K
    document.addEventListener('keydown', e => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            input.focus();
            input.select();
        }
    });
}

/* ══════════════════════════════════════════
   FILTRO POR CATEGORÍA
══════════════════════════════════════════ */
function initCategoryFilters() {
    document.querySelectorAll('.cat-filter').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.cat-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            applyFilters();
        });
    });
}

/* ══════════════════════════════════════════
   FILTROS COMBINADOS
══════════════════════════════════════════ */
function applyFilters() {
    const query      = (document.getElementById('search-input')?.value || '').toLowerCase().trim();
    const activeCat  = document.querySelector('.cat-filter.active')?.dataset.cat || 'all';
    const countLabel = document.getElementById('product-count');

    let visible = 0;

    document.querySelectorAll('.product-card').forEach(card => {
        const matchSearch = !query || card.dataset.search.includes(query);
        const matchCat    = activeCat === 'all' || card.dataset.cat === activeCat;
        const show        = matchSearch && matchCat;

        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    if (countLabel) {
        countLabel.textContent = visible + (visible === 1 ? ' producto' : ' productos');
    }
}

/* ══════════════════════════════════════════
   INIT
══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {

    // Bind botones "Agregar"
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', () => {
            const card  = btn.closest('.product-card');
            const img   = card?.querySelector('.product-img')?.src || '';
            addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price, img);
        });
    });

    // Vaciar carrito
    document.getElementById('btn-clear-cart')?.addEventListener('click', clearCart);

    // Antes de enviar el form
    document.getElementById('btn-confirm')?.addEventListener('click', prepareSubmit);

    // Búsqueda y filtros
    initSearch();
    initCategoryFilters();

    // Render inicial (por si viene con datos)
    renderCart();
});