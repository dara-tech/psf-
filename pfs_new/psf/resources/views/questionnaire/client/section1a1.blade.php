@extends('includes/header')

@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop

@section('content')
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary">@lang('client/questions.section_1A1') </h3>
       
        <form action=<?php echo('/client/'. $token.'/section1a1') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
            <div class="col-md-12 section">
                <h6> @lang('client/questions.q6a')  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_1"  class="form-check-input" value="1" required>@lang('client/answers.q6a_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_2" class="form-check-input" value="2" required>@lang('client/answers.q6a_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_98" class="form-check-input" value="98" required>@lang('client/answers.q6a_98') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q6a" id="q6a_99" class="form-check-input" value="99" required>@lang('client/answers.q6a_99') </label>
                    </div>
                </div>
            </div>
            <!-- div question q1a-->
        
            <div class="col-md-12">
                <h6> @lang('client/questions.q7a')  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_1" class="form-check-input" value="1" required>@lang('client/answers.q6a_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_2" class="form-check-input" value="2" required>@lang('client/answers.q6a_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_98" class="form-check-input" value="98" required>@lang('client/answers.q6a_98') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q7a" id="q7a_99" class="form-check-input" value="99" required>@lang('client/answers.q6a_99') </label>
                    </div>
                </div>
            </div>
      
        <!-- div question q2a-->
  
            <div class="col-md-12">
                <h6> @lang('client/questions.q8a')  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_1" class="form-check-input" value="1" required>@lang('client/answers.q6a_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_2" class="form-check-input" value="2" required>@lang('client/answers.q6a_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_98" class="form-check-input" value="98" required>@lang('client/answers.q6a_98') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q8a" id="q8a_99" class="form-check-input" value="99" required>@lang('client/answers.q6a_99') </label>
                    </div>
                </div>
            </div>
            <!-- div question q3a-->
     
            <div class="col-md-12" hidden>
                 <!-- avoid error on backend--> <input type="hidden" name="q9a" id="q9a_0" value="0"/>  
                <h6> @lang('client/questions.q9a')  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_1" class="form-check-input" value="1" >@lang('client/answers.q6a_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_2" class="form-check-input" value="2" >@lang('client/answers.q6a_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_98" class="form-check-input" value="98" >@lang('client/answers.q6a_98') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q9a" id="q9a_99" class="form-check-input" value="99" >@lang('client/answers.q6a_99') </label>
                    </div>
                   
                </div>
            </div>
             <!-- div question q4a-->
            <div class="col-md-12">
                <h6> @lang('client/questions.q10a')  </h6>
                <div class="form-group" >
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_1" class="form-check-input" value="1" required>@lang('client/answers.q6a_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_2" class="form-check-input" value="2" required>@lang('client/answers.q6a_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_98" class="form-check-input" value="98" required>@lang('client/answers.q6a_98') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q10a" id="q10a_99" class="form-check-input" value="99" required>@lang('client/answers.q6a_99') </label>
                    </div>
                   
                </div>
            </div>
             <!-- div question q4a-->
        <div class="row">
            <div class="col-md-12">
                <a href="#"><button class="btn btn-primary"> @lang('pagination.next')  </button></a>
            </div>
        </div>

        </form>
        
    </div>
</div>








 
@stop
