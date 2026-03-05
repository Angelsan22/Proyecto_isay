<?php $__env->startSection('title', 'Seguimiento del Pedido'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4" style="max-width:900px;">
        <a href="<?php echo e(route('cliente.pedidos.show', $pedido->id)); ?>" class="btn btn-outline-secondary btn-sm mb-3">
            ← Detalle del Pedido
        </a>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="row g-4">

                
                <div class="col-md-5 border-end">
                    <h4 class="fw-bold text-uppercase mb-4">Estatus de Tu Pedido</h4>

                    <?php
                        $pasos = [
                            ['key' => 'confirmado', 'label' => 'Pedido Confirmado', 'icon' => 'bi-car-front'],
                            ['key' => 'en_camino',  'label' => 'En Camino',         'icon' => 'bi-truck'],
                            ['key' => 'entregado',  'label' => 'Entregado',         'icon' => 'bi-house-door'],
                        ];
                        $orden = ['pendiente' => -1, 'confirmado' => 0, 'en_camino' => 1, 'entregado' => 2];
                        $actual = $orden[$pedido->estado] ?? -1;
                    ?>

                    <?php $__currentLoopData = $pasos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $activo = ($orden[$paso['key']] ?? -1) <= $actual; $current = $paso['key'] === $pedido->estado; ?>
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-flex flex-column align-items-center" style="width:44px;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                     style="width:44px; height:44px; background:<?php echo e($activo ? '#E8671B' : '#dee2e6'); ?>; flex-shrink:0;">
                                    <i class="bi <?php echo e($paso['icon']); ?>"></i>
                                </div>
                                <?php if(!$loop->last): ?>
                                    <div style="width:3px; height:36px; background:<?php echo e($activo ? '#E8671B' : '#dee2e6'); ?>;"></div>
                                <?php endif; ?>
                            </div>
                            <div class="pb-3 pt-1">
                                <p class="fw-semibold mb-0" style="<?php echo e($current ? 'color:#E8671B' : ''); ?>">
                                    <?php echo e($paso['label']); ?>

                                </p>
                                <?php if($current && isset($pedido->fecha_entrega_estimada)): ?>
                                    <p class="text-muted small mb-0">
                                        ENTREGA ESTIMADA: <?php echo e(\Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d/m/Y')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="col-md-7">
                    <h4 class="fw-bold text-uppercase mb-4">Detalle del Pedido</h4>

                    <p class="small text-muted mb-1">Número de Pedido: <strong>#<?php echo e($pedido->id); ?></strong></p>
                    <p class="small text-muted mb-1">Fecha de Compra: <strong><?php echo e($pedido->created_at->format('d/m/Y')); ?></strong></p>
                    <p class="small text-muted mb-3">Método de Envío: <strong><?php echo e($pedido->metodo_envio ?? 'Mensajería Express'); ?></strong></p>

                    <?php $__currentLoopData = $pedido->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-tools text-muted"></i>
                            <span class="small"><?php echo e($detalle->autoparte->nombre); ?> (x<?php echo e($detalle->cantidad ?? 1); ?>)</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <hr class="my-3">

                    <a href="<?php echo e(route('cliente.catalogo.index')); ?>"
                       class="btn text-white fw-bold w-100 text-uppercase mb-2"
                       style="background:#E8671B; border:none;">
                        Ver Detalle Completo
                    </a>
                    <p class="text-center text-muted small">
                        ¿Necesitas ayuda? <a href="#" style="color:#E8671B;">Contáctanos</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clientes.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Proyecto_ISAY\Proyecto_isay\laravel\Proyecto_isay_laravel\resources\views/clientes/pedidos/seguimiento.blade.php ENDPATH**/ ?>