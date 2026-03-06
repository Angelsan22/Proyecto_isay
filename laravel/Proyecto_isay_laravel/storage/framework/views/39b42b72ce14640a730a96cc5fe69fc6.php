<?php $__env->startSection('title', 'Crear Pedido'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4" style="max-width:700px;">

        
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3">Agregar Productos</h5>
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Buscar por nombre o SKU...">
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-3">
                <?php $__currentLoopData = $autopartes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <div class="card text-center border shadow-sm h-100 p-2">
                            <img src="<?php echo e($ap->imagen_url); ?>" style="height:80px; object-fit:contain;" class="mb-1" alt="<?php echo e($ap->nombre); ?>">
                            <p class="small fw-semibold mb-0" style="font-size:.75rem;"><?php echo e($ap->nombre); ?></p>
                            <p class="small text-muted mb-1">$<?php echo e(number_format($ap->precio, 2)); ?></p>
                            <button class="btn btn-sm text-white mt-auto" style="background:#E8671B; border:none; font-size:.75rem;"
                                    onclick="agregarItem('<?php echo e($ap->nombre); ?>', <?php echo e($ap->precio); ?>)">
                                + Agregar
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3">Confirmar Pedido</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="fw-semibold mb-2">Tu Carrito</p>
                    <div id="lista-carrito" class="small text-muted"><em>Sin productos aún</em></div>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless small mb-0">
                        <tr><td>Subtotal:</td><td class="text-end fw-semibold" id="txt-subtotal">$0.00</td></tr>
                        <tr><td>Envío Estimado:</td><td class="text-end">$25.00</td></tr>
                        <tr class="fw-bold border-top"><td>Total:</td><td class="text-end" id="txt-total">$25.00</td></tr>
                    </table>
                </div>
            </div>
            <hr>
            <button type="button" class="btn w-100 fw-bold text-white"
                    style="background:#2563eb; border-radius:.5rem; padding:.75rem;"
                    onclick="alert('Funcionalidad próximamente')">
                Confirmar y Pagar
            </button>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        let carrito = [];
        const ENVIO = 25;

        function agregarItem(nombre, precio) {
            const existe = carrito.find(i => i.nombre === nombre);
            if (existe) { existe.cantidad++; }
            else { carrito.push({ nombre, precio, cantidad: 1 }); }
            renderCarrito();
        }

        function renderCarrito() {
            const lista    = document.getElementById('lista-carrito');
            const subtotal = carrito.reduce((s, i) => s + i.precio * i.cantidad, 0);
            lista.innerHTML = carrito.length
                ? carrito.map(i => `<div>${i.cantidad}x ${i.nombre} - $${(i.precio * i.cantidad).toFixed(2)}</div>`).join('')
                : '<em>Sin productos aún</em>';
            document.getElementById('txt-subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('txt-total').textContent    = `$${(subtotal + ENVIO).toFixed(2)}`;
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('clientes.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto_ISAY\Proyecto_isay\laravel\Proyecto_isay_laravel\resources\views/clientes/pedidos/crear.blade.php ENDPATH**/ ?>