

<?php $__env->startSection('section_title'); ?>

<h1 class="text-primary"> <?php echo app('translator')->get($parent . '/questions.title'); ?> </h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
             <div class="col-lg-12 header "> 
                 <h1 class="text-primary"> <?php echo app('translator')->get('client/questions.thank'); ?> <h1>
             </div>
         </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\psf\resources\views/thank.blade.php ENDPATH**/ ?>