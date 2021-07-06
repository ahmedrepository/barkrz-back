<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Users </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>e-Mail</th>
                                <th>User</th>
                                <th>Membership</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($user['email']); ?></td>
                                    <td><?php echo e($user['name']); ?></td>
                                    <td><?php if($user['membership_plan'] == 1): ?>
                                            Monthly Membership
                                        <?php elseif($user['membership_plan'] == 2): ?>
                                            Yearly Membership
                                        <?php else: ?>
                                            No Membership
                                        <?php endif; ?>
                                    </td>
                                    <td> <a href="<?php echo e(route('pets.my-pets',['user_id'=>$user['id']])); ?>" class="btn btn-primary">My Pets</a> </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>