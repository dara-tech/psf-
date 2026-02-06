<?php $__env->startSection('section_title'); ?>

<h2 class="text-primary"> <?php echo app('translator')->get('client/questions.title'); ?> - <?php echo e($site); ?> </h2>
<?php $__env->stopSection(); ?>

<div class="row cardbox">
    <div class="col-md-12 section">
        <h3 class="text-primary"><?php echo app('translator')->get('client/questions.section_1A'); ?>  </h3>
        
        <form action=<?php echo('/client/'. $token.'/consent') ?> method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="locale" name="locale" value="<?php echo e($locale); ?>"/>
            <input type="hidden" id="_uri" name="_uri" value="<?php echo e($uuid); ?>"/>
        
            <div class="col-md-12 section">
                <p> <?php echo app('translator')->get('client/questions.acknowledge'); ?>  </p>
                <div class="form-group">
                    <div class="form-check">
                        <label><input type="radio" class="form-check-input" name="consent" id="consent1" value="1" required><?php echo app('translator')->get('client/answers.yesno_1'); ?> </label>
                       
                    </div>
                    <div class="form-check">
                        <label><input type="radio" class="form-check-input" name="consent" id="consent2" value="2" required><?php echo app('translator')->get('client/answers.yesno_2'); ?></label>
                    </div>
                </div>
            </div>
       
            <div class="form-group">
                <div class="col-md-12 section">
                    <a href="#"><button class="btn btn-primary"> <?php echo app('translator')->get('pagination.next'); ?>  </button></a>
                </div>
            </div>
        </form>
    </div>
</div><?php /**PATH /Users/cheolsovandara/Documents/D/Developments/2026/psf_v4_report_v2/pfs_new/psf/resources/views/questionnaire/client/consent.blade.php ENDPATH**/ ?>