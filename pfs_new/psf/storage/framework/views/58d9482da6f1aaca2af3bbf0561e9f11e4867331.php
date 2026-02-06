<?php $__env->startSection('section_title'); ?>

<h2 class="text-primary"> <?php echo app('translator')->get('client/questions.title'); ?> - <?php echo e($site); ?> </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary"><?php echo app('translator')->get('client/questions.section_1A1'); ?> </h3>
       
        <form action=<?php echo('/client/'. $token.'/section1a1') ?> method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="locale" name="locale" value="<?php echo e($locale); ?>"/>
            <input type="hidden" id="_uri" name="_uri" value="<?php echo e($uuid); ?>"/>
            <div class="col-md-12 section">
                <h6> <?php echo app('translator')->get('client/questions.q6a'); ?>  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_1"  class="form-check-input" value="1" required><?php echo app('translator')->get('client/answers.q6a_1'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_2" class="form-check-input" value="2" required><?php echo app('translator')->get('client/answers.q6a_2'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_98" class="form-check-input" value="98" required><?php echo app('translator')->get('client/answers.q6a_98'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_99" class="form-check-input" value="99" required><?php echo app('translator')->get('client/answers.q6a_99'); ?> </label>
                    </div>
                </div>
            </div>
            <!-- div question q1a-->
        
            <div class="col-md-12">
                <h6> <?php echo app('translator')->get('client/questions.q7a'); ?>  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_1" class="form-check-input" value="1" required><?php echo app('translator')->get('client/answers.q6a_1'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_2" class="form-check-input" value="2" required><?php echo app('translator')->get('client/answers.q6a_2'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_98" class="form-check-input" value="98" required><?php echo app('translator')->get('client/answers.q6a_98'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_99" class="form-check-input" value="99" required><?php echo app('translator')->get('client/answers.q6a_99'); ?> </label>
                    </div>
                </div>
            </div>
      
        <!-- div question q2a-->
  
            <div class="col-md-12">
                <h6> <?php echo app('translator')->get('client/questions.q8a'); ?>  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_1" class="form-check-input" value="1" required><?php echo app('translator')->get('client/answers.q6a_1'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_2" class="form-check-input" value="2" required><?php echo app('translator')->get('client/answers.q6a_2'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_98" class="form-check-input" value="98" required><?php echo app('translator')->get('client/answers.q6a_98'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_99" class="form-check-input" value="99" required><?php echo app('translator')->get('client/answers.q6a_99'); ?> </label>
                    </div>
                </div>
            </div>
            <!-- div question q3a-->
     
            <div class="col-md-12" hidden>
                 <!-- avoid error on backend--> <input type="hidden" name="q9a" id="q9a_0" value="0"/>  
                <h6> <?php echo app('translator')->get('client/questions.q9a'); ?>  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_1" class="form-check-input" value="1" ><?php echo app('translator')->get('client/answers.q6a_1'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_2" class="form-check-input" value="2" ><?php echo app('translator')->get('client/answers.q6a_2'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_98" class="form-check-input" value="98" ><?php echo app('translator')->get('client/answers.q6a_98'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_99" class="form-check-input" value="99" ><?php echo app('translator')->get('client/answers.q6a_99'); ?> </label>
                    </div>
                   
                </div>
            </div>
             <!-- div question q4a-->
            <div class="col-md-12">
                <h6> <?php echo app('translator')->get('client/questions.q10a'); ?>  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_1" class="form-check-input" value="1" required><?php echo app('translator')->get('client/answers.q6a_1'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_2" class="form-check-input" value="2" required><?php echo app('translator')->get('client/answers.q6a_2'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_98" class="form-check-input" value="98" required><?php echo app('translator')->get('client/answers.q6a_98'); ?> </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_99" class="form-check-input" value="99" required><?php echo app('translator')->get('client/answers.q6a_99'); ?> </label>
                    </div>
                   
                </div>
            </div>
             <!-- div question q4a-->
        <div class="row">
            <div class="col-md-12">
                <a href="#"><button class="btn btn-primary"> <?php echo app('translator')->get('pagination.next'); ?>  </button></a>
            </div>
        </div>

        </form>
        
    </div>
</div>








 
<?php $__env->stopSection(); ?>

<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\psf\resources\views/questionnaire/client/section1a1.blade.php ENDPATH**/ ?>