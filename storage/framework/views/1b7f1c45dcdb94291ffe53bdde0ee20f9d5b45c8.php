<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->yieldContent('meta'); ?>

    <!-- Styles -->
    <link href="<?php echo e(asset('public/css/app.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('public/css/adminlte.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('public/css/@fontawesome/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <?php echo $__env->yieldContent('style'); ?>
    <!-- Fonts -->
    
    
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper" id="app">
        <?php echo $__env->make('partials.navbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('partials.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('public/js/app.js')); ?>" ></script>
    <script src="<?php echo e(asset('public/js/adminlte.js')); ?>" ></script>
    <script>
        let KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };

    </script>
    <?php echo $__env->yieldContent('scripts'); ?>;
</body>
</html>
