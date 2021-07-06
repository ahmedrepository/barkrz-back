<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-top: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Users </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>email</th>
                                    <th>user</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>xxx</td>
                                <td>xxx</td>
                            </tr>
                            <tr>
                                <td>xxx</td>
                                <td>xxx</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>