<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Coupon Code </h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('coupon.save')); ?>" method="post">
                            <?php echo e(csrf_field()); ?>

                            <label for="beta">Barkrz BETA</label>
                            <input type="text" name="beta" value="<?php echo e($beta); ?>" class="form-control" />
                            <label for="fam" class="mt-2">Barkrz FAM</label>
                            <input type="text" name="fam" value="<?php echo e($fam); ?>"  class="form-control" />
                            <input type="submit" name="SAVE" value="Save" class="btn btn-primary mt-3"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>