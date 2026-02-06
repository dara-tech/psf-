@extends('includes/header')
@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop

@section('content')
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary">@lang('client/questions.section_1C') </h3>
        <h4 class="text-primary">@lang('client/questions.part1c') </h4>
        
        <form action=<?php echo('/client/'. $token.'/section1c') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
            <div class="col-md-12 section">
                <h6> @lang('client/questions.q1c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q1c" id="q1c_1" value="1" required>@lang('client/answers.q1c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q1c" id="q1c_2" value="2" required>@lang('client/answers.q1c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q1c" id="q1c_3" value="3" required>@lang('client/answers.q1c_3') </label>
                    </div>
                   
                </div>
            </div>
      
        <!-- div question q1a-->
      
            <div class="col-md-12 section">
                <h6> @lang('client/questions.q2c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q2c" id="q2c_1" value="1" required>@lang('client/answers.q2c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q2c" id="q2c_2" value="2" required>@lang('client/answers.q2c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q2c" id="q2c_3" value="3" required>@lang('client/answers.q2c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q2c" id="q2c_4" value="4" required>@lang('client/answers.q2c_4') </label>
                    </div>
                
                </div>
            </div>
   
        <!-- div question q2a-->
            <div class="col-md-12 section">
                <h6> @lang('client/questions.q3c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_1" id="q3c_1" value="1" onclick='q3cRequiredCheck()' required>@lang('client/answers.q3c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_2" id="q3c_2" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_3" id="q3c_3" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_4" id="q3c_4" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_5" id="q3c_5" value="1" onclick='q3cRequiredCheck()' > @lang('client/answers.q3c_5') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_6" id="q3c_6" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_6') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_7" id="q3c_7" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_7') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="checkbox" class="form-check-input" name="q3c_8" id="q3c_8" value="1" onclick='q3cRequiredCheck()' >@lang('client/answers.q3c_8') </label>
                    </div>
                
                
                </div>
            </div>
      
        <!-- div question q3a-->
           <div class="col-md-12 ">
                <h6> @lang('client/questions.q4c')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q4c" id="q4c_1" value="1" required>@lang('client/answers.q4c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q4c" value="2" required>@lang('client/answers.q4c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q4c" value="3" required>@lang('client/answers.q4c_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q4c" value="4" required>@lang('client/answers.q4c_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q4c" value="5" required>@lang('client/answers.q4c_5') </label>
                    </div>
                </div>
            </div>
       
        <!-- div question q4a-->
            <div class="row">
                <div class="col-md-12 section">
                    <a href="#"><button class="btn btn-primary"> @lang('pagination.next')  </button></a>
                </div>
            </div>
        </form>
        
    </div>
</div>



<script>

function q3cRequiredCheck() {

    ans1=document.getElementById("q3c_1");
    ans2=document.getElementById("q3c_2");
    ans3=document.getElementById("q3c_3");
    ans4=document.getElementById("q3c_4");
    ans5=document.getElementById("q3c_5");
    ans6=document.getElementById("q3c_6");
    ans7=document.getElementById("q3c_7");
    ans8=document.getElementById("q3c_8");
    
    

    var atLeastOneChecked=false;

    if (ans1.checked === true | ans2.checked === true | ans3.checked === true | ans4.checked === true | ans5.checked === true | ans6.checked === true | ans7.checked === true | ans8.checked === true) 
    {

        atLeastOneChecked=true;

    }
   
        if (  atLeastOneChecked===true)
        ans1.required = false;
    
}

</script>



 
@stop
