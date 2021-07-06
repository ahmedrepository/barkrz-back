<?php $__env->startSection('meta'); ?>
    <title> barkrz/pets </title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-top: 100px">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Pets </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Breed</th>
                            <th>Address</th>
                            <th>Birth Year</th>
                            <th>Weight</th>
                            <th>Medical Condition</th>
                            <th>Neutered</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($pet['name']); ?></td>
                                <td><?php echo e($pet['gender']); ?></td>
                                <td><?php echo e($pet['breed']); ?></td>
                                <td><?php echo e($pet['address']); ?></td>
                                <td><?php echo e($pet['age']); ?></td>
                                <td><?php echo e($pet['weight'].' lbs'); ?></td>
                                <td><?php echo e($pet['medicalCondition']); ?></td>
                                <td>
                                    <?php if($pet['gender'] == 'Male'): ?>
                                        <?php if($pet['neutered'] == '1'): ?>
                                            Neutered
                                        <?php else: ?>
                                            Not Neutered
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($pet['neutered'] == '1'): ?>
                                            Spayed
                                        <?php else: ?>
                                            Not Spayed
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td><a href="<?php echo e(route('pets.view',['id'=>$pet['id']])); ?>" class="btn btn-primary">View</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>