<?php $__env->startSection('section_title'); ?>

<h2 class="text-primary"> <?php echo app('translator')->get('client/questions.title'); ?> - <?php echo e($site); ?> </h2>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
<div class="row cardbox">
    <div class="col-md-12 section">
        <h3 class="text-primary"><?php echo app('translator')->get('client/questions.section_1A'); ?> </h3>
            <form method="POST" action="<?php echo e(url('client/' . $token . '/section1a')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="locale" name="locale" value="<?php echo e($locale); ?>"/>
            <input type="hidden" id="_uri" name="_uri" value="<?php echo e($uuid); ?>"/>
                
                    <div class="col-md-12 question">
                        <h6> <?php echo app('translator')->get('client/questions.q1a'); ?>  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_1" class="form-check-input" value="1" required/><?php echo app('translator')->get('client/answers.q1a_1'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_2" class="form-check-input" value="2" required/><?php echo app('translator')->get('client/answers.q1a_2'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_3" class="form-check-input" value="3" required/><?php echo app('translator')->get('client/answers.q1a_3'); ?> </label>
                            </div> 
                            <div class="form-check ">     
                                <label><input type="radio" name="q1a"  id="q1a_98" class="form-check-input" value="98" required/><?php echo app('translator')->get('client/answers.q1a_98'); ?> </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q1a-->
               
                    <div class="col-md-12 question">
                        <h6> <?php echo app('translator')->get('client/questions.q2a'); ?>  </h6>
                        <div class="form-group">
                           <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_1" value="1" required><?php echo app('translator')->get('client/answers.q1a_1'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_2" value="2" required><?php echo app('translator')->get('client/answers.q1a_2'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_3" value="3" required><?php echo app('translator')->get('client/answers.q1a_3'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_98" value="98" required><?php echo app('translator')->get('client/answers.q1a_98'); ?> </label>
                            </div>
                        </div>
                    </div>
                <!-- div question q2a-->
        
                    <div class="col-md-12 question" >
                        <h6> <?php echo app('translator')->get('client/questions.q3a'); ?>  </h6>
                        <div class="form-group">
                          
                            <div class="form-check ">
                            <label><input type="radio" name="q3a" class="form-check-input" id="q3a_1" value="1" required><?php echo app('translator')->get('client/answers.q1a_1'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_2" value="2" required><?php echo app('translator')->get('client/answers.q1a_2'); ?> </label>
                            </div> 
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_3" value="3" required><?php echo app('translator')->get('client/answers.q1a_3'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_98" value="98" required><?php echo app('translator')->get('client/answers.q1a_98'); ?> </label>
                            </div>
                            
                        </div>
                    </div>
                    <!-- div question q3a-->
               
                    <div class="col-md-12 question">
                        <h6> <?php echo app('translator')->get('client/questions.q4a'); ?>  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_1" value="1" required><?php echo app('translator')->get('client/answers.q1a_1'); ?> </label>
                            </div >
                            <div class="form-check ">
                            <label><input type="radio" name="q4a" class="form-check-input" id="q4a_2" value="2" required><?php echo app('translator')->get('client/answers.q1a_2'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_3" value="3" required><?php echo app('translator')->get('client/answers.q1a_3'); ?> </label>
                            </div >
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_98" value="98" required><?php echo app('translator')->get('client/answers.q1a_98'); ?> </label>
                            </div>
                        
                        </div>
                    </div>
                
                    <!-- div question q4a-->
        
                
                    <div class="col-md-12 question">
                        <h6> <?php echo app('translator')->get('client/questions.q5a'); ?>  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_1" value="1" required><?php echo app('translator')->get('client/answers.q1a_1'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_2" value="2" required><?php echo app('translator')->get('client/answers.q1a_2'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_3" value="3" required><?php echo app('translator')->get('client/answers.q1a_3'); ?> </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q4a_98" value="98" required><?php echo app('translator')->get('client/answers.q1a_98'); ?> </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q4a-->
        
                <div class="form-group ">
                    <div class="col-md-12">
                        <a href="#"><button class="btn btn-primary"> <?php echo app('translator')->get('pagination.next'); ?>  </button></a>
                    </div>
                </div>
            </form>

        
    </div>
</div>

      
 
<?php $__env->stopSection(); ?>

<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\psf\resources\views/questionnaire/client/section1.blade.php ENDPATH**/ ?>