<?php $__env->startSection('title', 'Mis pedidos'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4" style="max-width:800px;">

        <h2 class="fw-bold mb-4">Mis Pedidos</h2>
        <hr>

        <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">

                <?php $__currentLoopData = $pedido->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            <img src="<?php echo e($detalle->autoparte->imagen_url ?? 'https://placehold.co/60x60?text=Item'); ?>"
                                 class="rounded" style="width:60px; height:60px; object-fit:contain;" alt="">
                        </div>
                        <div class="col">
                            <p class="text-muted small mb-0">
                                Fecha: <?php echo e($pedido->created_at->format('d F, Y')); ?>

                                <span class="float-end">Pedido #<?php echo e($pedido->id); ?></span>
                            </p>
                            <p class="fw-semibold mb-0"><?php echo e($detalle->autoparte->nombre); ?></p>
                            <p class="text-muted small mb-0">
                                Cantidad: <?php echo e($detalle->cantidad ?? 1); ?> &nbsp;|&nbsp;
                                Total: $<?php echo e(number_format($detalle->subtotal, 2)); ?> USD
                            </p>
                        </div>
                        <div class="col-auto">
                            <?php if($pedido->estado === 'entregado'): ?>
                                <span class="badge bg-success px-3 py-2">Entregado</span>
                            <?php elseif($pedido->estado === 'en_camino'): ?>
                                <span class="badge bg-warning text-dark px-3 py-2">En Camino</span>
                            <?php elseif($pedido->estado === 'confirmado'): ?>
                                <span class="badge bg-info text-dark px-3 py-2">Confirmado</span>
                            <?php else: ?>
                                <span class="badge bg-secondary px-3 py-2">Pendiente</span>
                            <?php endif; ?>
                            <i class="bi bi-chevron-right ms-1 text-muted"></i>
                        </div>
                    </div>
                    <?php if(!$loop->last): ?><hr class="my-2"><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="d-flex gap-2 mt-2">
                    <a href="<?php echo e(route('cliente.pedidos.show', $pedido->id)); ?>"
                       class="btn text-white fw-bold" style="background:#E8671B; border:none;">
                        Ver Detalles
                    </a>
                    <button type="button" class="btn btn-outline-secondary fw-bold"
                            onclick="alert('Funcionalidad próximamente')">
                        Factura en PDF
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clientes.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto_ISAY\Proyecto_isay\laravel\Proyecto_isay_laravel\resources\views/clientes/pedidos/index.blade.php ENDPATH**/ ?>