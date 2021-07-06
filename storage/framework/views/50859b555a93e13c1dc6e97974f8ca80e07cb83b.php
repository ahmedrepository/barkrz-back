<?php $__env->startSection('meta'); ?>
    <title> barkrz/pets </title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $qrCode = 'http://www.barkrz.com/a?p='.$pet->identity_code;
    ?>
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-widget widget-user-2" style="background-color: rgb(244 255 219);">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="card-footer" style="padding: 0 0 20px 0;">
                        <div class="pet_image">
                            <img class="img-circle elevation-2" src="<?php echo e($pet->image); ?>" alt="Pet Avatar">
                            <span> <?php echo e($pet->name); ?></span>
                            <span><?php echo e($pet->breed); ?></span>
                        </div>
                        <div class=" pet_info">
                            <div>
                                Gender:
                            </div>
                            <div>
                                <?php echo e($pet->gender); ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Address:
                            </div>
                            <div>
                                <?php echo e($pet->address); ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Birth Year:
                            </div>
                            <div>
                                <?php echo e($pet->age); ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Weight:
                            </div>
                            <div>
                                <?php echo e($pet->weight.' lbs'); ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Medical Condition:
                            </div>
                            <div>
                                <?php echo e($pet->medicalCondition); ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Neutered:
                            </div>
                            <div>
                                <?php if($pet->gender == 'Male'): ?>
                                    <?php if($pet->neutered == '1'): ?>
                                        Neutered
                                    <?php else: ?>
                                        Not Neutered
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($pet->neutered == '1'): ?>
                                        Spayed
                                    <?php else: ?>
                                        Not Spayed
                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="pet_info">
                            <div>
                                Temperament:
                            </div>
                            <div>
                                <?php
                                    $temperament = $pet->temperament;
                                    $temperaments = ['Friendly','With Kids','With Dogs','With Cats','Skittish',
                                                'Aggressive','Calm','Playful'];
                                    $cnt = 0;
                                    for ($i = 0 ; $i < 8; $i ++) {
                                        if ($temperament[$i] == '1') {
                                            if ($cnt == 0) {
                                                echo $temperaments[$i]." ";
                                            }
                                            else {
                                                echo ", ".$temperaments[$i]." ";
                                            }
                                            $cnt += 1;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div style="text-align: center; font-size: 30px; height: 50px" >
                            <span class="mt-auto mb-auto">Owners</span>
                        </div>
                        <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="pet_info">
                            <div>
                                <?php echo e($owner['owner']['name']); ?>:
                            </div>
                            <div style="display: flex; flex-wrap: wrap; flex-direction: column">
                                <?php $__currentLoopData = $owner['phone_numbers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone_number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span><?php echo e($phone_number['phone_number']); ?> &nbsp;&nbsp;</span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div style="text-align: center; font-size: 30px; height: 50px" >
                            <span class="mt-auto mb-auto">Qr Code</span>
                        </div>
                        <div>
                            <div class="visible-print text-center">
                                <?php echo QrCode::size(200)->backgroundColor(244, 255, 219)->generate($qrCode);; ?>

                                <form method="post" action="<?php echo e(route('qr.update')); ?>">
                                    <?php echo e(csrf_field()); ?>

                                    <input type="hidden" name="id" value="<?php echo e($pet->id); ?>">
                                    <span>
                                        <?php echo e($qrCode); ?>

                                    </span>
                                    <input class="form-control ml-auto mr-auto mt-2 mb-2" style="width: 200px" name="qrCode" value="<?php echo e($pet->identity_code); ?>">
                                    <?php if(isset($_REQUEST['error'])): ?>
                                        <div class="text-red">
                                            <strong><?php echo e($_GET['error']); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    <button class="btn btn-primary" type="submit"> Update </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>