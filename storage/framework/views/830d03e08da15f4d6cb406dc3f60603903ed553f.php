<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(asset('public/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css">
<?php echo $__env->yieldContent('meta'); ?>

</head>
<body >
    <div id="app">
            <?php echo $__env->yieldContent('content'); ?>
    </div>

<!-- Scripts -->
    <script src="<?php echo e(asset('public/js/bootstrap.min.js')); ?>" ></script>
    <script src="<?php echo e(asset('public/js/jquery.min.js')); ?>" ></script>

<?php echo $__env->yieldContent('scripts'); ?>;
</body>
</html>
