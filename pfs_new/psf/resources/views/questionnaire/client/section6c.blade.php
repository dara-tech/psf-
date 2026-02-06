@extends('includes/header')
@section('section_title')

<<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop

@section('content')
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary">@lang('client/questions.section_1C') </h3>
        <h4 class="text-primary">@lang('client/questions.part6') </h4>
        
        <form action=<?php echo('/client/'. $token.'/section6c') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
            <div class="col-md-12">
                <h6> @lang('client/questions.q6c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                         <label><input type="CheckBox" class="form-check-input" name="q6c_1" id="q6c_1" value="1" >@lang('client/answers.q6c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_2" id="q6c_2" value="1" >@lang('client/answers.q6c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_3" id="q6c_3" value="1" >@lang('client/answers.q6c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox"  class="form-check-input" name="q6c_4" id="q6c_4" value="1" >@lang('client/answers.q6c_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_5" id="q6c_5" value="1" >@lang('client/answers.q6c_5') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_6" id="q6c_6" value="1" >@lang('client/answers.q6c_6') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_7" id="q6c_7" value="1" >@lang('client/answers.q6c_7') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input" name="q6c_8" id="q6c_8" value="1" >@lang('client/answers.q6c_8') </label>
                    </div>
                </div>
            </div>
        
        <!-- div question q1a-->
       
            <div class="col-md-12">
                <h6> @lang('client/questions.q7c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q7c" id="q7c_1" value="1" required>@lang('client/answers.q7c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q7c" id="q7c_2" value="2" required>@lang('client/answers.q7c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio"class="form-check-input"  name="q7c" id="q7c_3" value="3"required>@lang('client/answers.q7c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q7c" id="q7c_4" value="4" required>@lang('client/answers.q7c_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q7c" id="q7c_5" value="5" required>@lang('client/answers.q7c_5') </label>
                    </div>
                </div>
            </div>
      
        <!-- div question q2a-->
     
            <div class="col-md-12">
                <h6> @lang('client/questions.q8c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q8c" id="q8c_1" value="1" required>@lang('client/answers.q8c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q8c" id="q8c_2" value="2" required>@lang('client/answers.q8c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q8c" id="q8c_3" value="3" required>@lang('client/answers.q8c_3') </label>
                    </div>
                   
                </div>
            </div>
       
        <!-- div question q3a-->
       
            <div class="col-md-12">
                <h6> @lang('client/questions.q9c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input"  name="q9c_1" id="q9c_1" value="1" >@lang('client/answers.q9c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input"  name="q9c_2" id="q9c_2" value="1" >@lang('client/answers.q9c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input"  name="q9c_3" id="q9c_3" value="1" >@lang('client/answers.q9c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input"  name="q9c_4" id="q9c_4" value="1" >@lang('client/answers.q9c_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="CheckBox" class="form-check-input"  name="q9c_5" id="q9c_5" value="1" > @lang('client/answers.q9c_5') </label>  
                    </div>
                </div>
            </div>
       
        <!-- div question q4a-->
       
            <div class="col-md-12">
                <h6> @lang('client/questions.q10c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q10c" id="q10c_0" value="0" required>@lang('client/answers.q10c_0') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q10c" id="q10c_1" value="1" required>@lang('client/answers.q10c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q10c" id="q10c_98" value="98" required>@lang('client/answers.q10c_98') </label>
                    </div>
                
                </div>
            </div>
     
        <!-- div question q4a-->


            <div class="col-md-12">
                <h6> @lang('client/questions.q11c')  </h6>
               <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q11c" id="q11c_0" value="0" required>@lang('client/answers.q10c_0') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q11c" id="q11c_1" value="1" required>@lang('client/answers.q10c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q11c" id="q11c_98" value="98" required>@lang('client/answers.q10c_98') </label>
                    </div>
                </div>
            </div>
     
        <!-- div question q4a-->
        
            <div class="col-md-12">
                <h6> @lang('client/questions.q12c')  </h6>
                <div class="form-group">
                     <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q12c" id="q12c_0" value="0" required>@lang('client/answers.q10c_0') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q12c" id="q12c_1" value="1" required>@lang('client/answers.q10c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q12c" id="q12c_98" value="98" required>@lang('client/answers.q10c_98') </label>
                    </div>
                
                </div>
            </div>
       
        <!-- div question q4a--> 
      
            <div class="col-md-12">
                <h6> @lang('client/questions.q13c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q13c" id="q13c_0" value="0" required>@lang('client/answers.q10c_0') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q13c" id="q13c_1" value="1" required>@lang('client/answers.q10c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input"  name="q13c" id="q13c_98" value="98" required>@lang('client/answers.q10c_98') </label>
                    </div>
                
                </div>
            </div>
   
        <!-- div question q4a-->
        <div class="form-group" >
            <div class="col-md-12">
                <h6> @lang('client/questions.q14c')  </h6>
                    <label><input type="number" name="q14c" disabled></label>
                
                </div>
        </div>
     
        <!-- div question q4a-->
        <div class="form-group">
            <div class="col-md-12">
                <a href="#"><button class="btn btn-primary"> @lang('pagination.submit')  </button></a>
            </div>
        </div>

        </form>

    </div>
</div>

   
<script>
$(document).on('click', '[name="q13c"]', function () {

   $('[name="q14c"]').attr("disabled",($('[name="q13c"]:checked').val() != 1));
   
  
});

    

</script>
 
@stop
