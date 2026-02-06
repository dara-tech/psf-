
<?php $__env->startSection('section_title'); ?>

<?php $lang= $parent . '/questions.title'; ?>
<h2 class="text-primary"> 
    <?php echo app('translator')->get(''.$lang); ?>
</h2>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<div class="row cardbox">
    
    <div class="col-md-4 section">
        <h2 class="text-primary"> 
            <?php echo app('translator')->get('pagination.province'); ?>
        </h2>
        <div class="form-group" id="province_list1">
            
        </div>
    </div>
    <div class="col-md-4 section">
    <h2 class="text-primary"> 
            
        </h2>
        <div class="form-group" id="province_list2">
            
        </div>
    </div>

  
    <div class="col-md-4 section">
        <h2 class="text-primary"> 
            <?php echo app('translator')->get('pagination.site'); ?>
        </h2>
        <div class="form-group" id="siteList">
            
        </div>
                
    </div>
</div>


<?php $__env->stopSection(); ?>



<?php $__env->startSection('javascript'); ?> 

<script>

        var allSites =  <?php echo $allsites; ?> ;
        var locale = "<?php echo $locale; ?>" ;
        var tokens = <?php echo $tokens; ?>;
        var parent = "<?php echo $parent; ?>";

       
    $("document").ready (function(){
        var old_province  ="";
        var i=0;
        var list = "#province_list1";
        locale= (locale =="")? "kh": locale;

        allSites.forEach( function( site)
        {
           
            if ((site.province !="*") && (  old_province !=site.province))
            {
                if (i>11) list ="#province_list2";
                $(list).append(" <div class='form-check' ><label><input type='radio' class = 'form-check-input' name='province' value='" + site.username  +"'  onclick=radioCheck('"+ (site.province.replace(" ","")) +"') required>" + ((locale =='kh')? site.province_kh : site.province )+ "</input></label></div>");

                old_province = site.province;

                i++;
            }   

          
                
        });

    });

      
    function radioCheck(value)
    {
        var buttonText =  "<?php echo app('translator')->get('pagination.next'); ?>" ;
        $("#siteList").empty();
        allSites.forEach( function( site)
        {

            if (value == site.province.replace(" ",""))
            {
                $("#siteList").append(" <div class='form-check' ><label><input type='radio' class = 'form-check-input' name='site' value='" + site.username  +"'  required>" + ((locale =='kh')? site.site_kh : site.site_en )+ "</input></label></div>");

            }
        });

        $("#siteList").append("  <button class='btn btn-primary' onclick='moveNext()' > " + buttonText  +" </button>");

    }


    function moveNext()
    {
        var value = $("input[type='radio'][name='site']:checked").val();


     

        if (value != null && value !='' && value != undefined)
        {
            tokens.forEach(function (t){

                if (value == t.username)
                {

                    window.location.href = "/" + parent + "/" + t.code + "/" + locale ;
                    
                  //  alert ("/" + parent + "/" + t.code + "/" + locale);
                }
            });
        }

    }
</script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\psf\resources\views/index.blade.php ENDPATH**/ ?>