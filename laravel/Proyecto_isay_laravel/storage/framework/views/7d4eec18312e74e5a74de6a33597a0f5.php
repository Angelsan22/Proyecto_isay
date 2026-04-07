<?php $__env->startSection('title', 'Catálogo de Autopartes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">

        <h1 class="fw-bold text-uppercase text-center mb-4">Catálogo de Autopartes</h1>

        
        <form method="GET" action="<?php echo e(route('cliente.catalogo.index')); ?>" class="mb-4">
            <div class="row g-3 align-items-start">
                <div class="col-md-2">
                    <p class="text-muted text-uppercase small fw-bold mb-1">Categoría</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoria" id="cat-all" value=""
                            <?php echo e(!request('categoria') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="cat-all">Todas</label>
                    </div>
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="categoria"
                                   id="cat-<?php echo e($cat->id); ?>" value="<?php echo e($cat->id); ?>"
                                <?php echo e(request('categoria') == $cat->id ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="cat-<?php echo e($cat->id); ?>"><?php echo e($cat->nombre); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="col-md-2">
                    <p class="text-muted text-uppercase small fw-bold mb-1">Marca</p>
                    <?php $__currentLoopData = $marcas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="marca"
                                   id="marca-<?php echo e($m->id); ?>" value="<?php echo e($m->id); ?>"
                                <?php echo e(request('marca') == $m->id ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="marca-<?php echo e($m->id); ?>"><?php echo e($m->nombre); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="col-md-8 d-flex align-items-end gap-2 mt-4">
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Buscar por nombre..." value="<?php echo e(request('buscar')); ?>">
                    <button type="submit" class="btn btn-dark px-4 fw-bold text-nowrap">Aplicar filtros</button>
                    <a href="<?php echo e(route('cliente.catalogo.index')); ?>" class="btn btn-outline-secondary text-nowrap">Limpiar</a>
                </div>
            </div>
        </form>

        
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php $__empty_1 = true; $__currentLoopData = $autopartes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="<?php echo e($ap->imagen_url ?? 'https://placehold.co/200x200?text=Sin+imagen'); ?>"
                             class="card-img-top p-3" style="height:200px; object-fit:contain;" alt="<?php echo e($ap->nombre); ?>">
                        <div class="card-body">
                            <h6 class="fw-bold"><?php echo e($ap->nombre); ?></h6>
                            <p class="text-muted small mb-1">SKU: <?php echo e($ap->sku); ?></p>
                            <p class="fw-semibold mb-0">$<?php echo e(number_format($ap->precio, 2)); ?> USD</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <a href="<?php echo e(route('cliente.catalogo.show', $ap->id)); ?>"
                               class="btn btn-naranja w-100 text-white fw-bold text-uppercase"
                               style="background:#E8671B; border:none;">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-search fs-1"></i>
                    <p class="mt-2">No se encontraron autopartes.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('clientes.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/clientes/catalogo/index.blade.php ENDPATH**/ ?>