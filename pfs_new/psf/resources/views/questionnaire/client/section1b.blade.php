@extends('includes/header')
@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop


@section('content')
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary">@lang('client/questions.section_1B') </h3>
        <h4 class="text-primary">@lang('client/questions.part1b') </h4>
        <form action=<?php echo('/client/'. $token.'/section1b') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
        
            <div class="col-md-12 section">
                <h6> @lang('client/questions.q1b')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" name="q1b" class="form-check-input" value="1" required>@lang('client/answers.q1b_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q1b" class="form-check-input" value="2" required>@lang('client/answers.q1b_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q1b" class="form-check-input" value="3" required>@lang('client/answers.q1b_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q1b" class="form-check-input" value="4" required>@lang('client/answers.q1b_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q1b" class="form-check-input" value="98" required>@lang('client/answers.q1b_98') </label>
                    </div>
                   
                </div>
            </div>
    
            <!-- div question q1a-->
      
            <div class="col-md-12 question">
                <h6> @lang('client/questions.q2b')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" name="q2b" class="form-check-input" value="1" required>@lang('client/answers.q1b_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q2b" class="form-check-input" value="2" required>@lang('client/answers.q1b_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q2b" class="form-check-input" value="3" required>@lang('client/answers.q1b_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q2b" class="form-check-input" value="4" required>@lang('client/answers.q1b_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q2b" class="form-check-input" value="98" required>@lang('client/answers.q1b_98') </label>
                    </div>
                </div>
            </div>
        
        <!-- div question q2a-->
            <div class="col-md-12 question">
                <h6> @lang('client/questions.q3b')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" name="q3b" class="form-check-input" value="1" required>@lang('client/answers.q1b_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q3b" class="form-check-input" value="2" required>@lang('client/answers.q1b_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q3b" class="form-check-input" value="3" required>@lang('client/answers.q1b_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q3b" class="form-check-input" value="4" required>@lang('client/answers.q1b_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q3b" class="form-check-input" value="98" required>@lang('client/answers.q1b_98') </label>
                    </div>
                </div>
            </div>
       
        <!-- div question q3a-->
    
            <div class="col-md-12 question">
                <h6> @lang('client/questions.q4b')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" name="q4b" class="form-check-input" value="1" required> @lang('client/answers.q1b_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q4b" class="form-check-input" value="2" required>@lang('client/answers.q1b_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q4b" class="form-check-input" value="3" required>@lang('client/answers.q1b_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q4b" class="form-check-input" value="4" required>@lang('client/answers.q1b_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q4b" class="form-check-input" value="98" required>@lang('client/answers.q1b_98') </label>
                    </div>
                   
                </div>
            </div>

            <div class="col-md-12 question">
                <h6> @lang('client/questions.q5b')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" name="q5b" class="form-check-input" value="1" required> @lang('client/answers.q1b_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q5b" class="form-check-input" value="2" required>@lang('client/answers.q1b_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q5b" class="form-check-input" value="3" required>@lang('client/answers.q1b_3') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q5b" class="form-check-input" value="4" required>@lang('client/answers.q1b_4') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" name="q5b" class="form-check-input" value="98" required>@lang('client/answers.q1b_98') </label>
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
