@extends('includes/header')
@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop


@section('content')
  
<div class="row cardbox">
    <div class="col-md-12 section">
        <h3 class="text-primary">@lang('client/questions.section_1A') </h3>
            <form action=<?php echo('/client/'. $token.'/section1a') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
                
                    <div class="col-md-12 question">
                        <h6> @lang('client/questions.q1a')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_1" class="form-check-input" value="1" required/>@lang('client/answers.q1a_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_2" class="form-check-input" value="2" required/>@lang('client/answers.q1a_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q1a"  id="q1a_3" class="form-check-input" value="3" required/>@lang('client/answers.q1a_3') </label>
                            </div> 
                            <div class="form-check ">     
                                <label><input type="radio" name="q1a"  id="q1a_98" class="form-check-input" value="98" required/>@lang('client/answers.q1a_98') </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q1a-->
               
                    <div class="col-md-12 question">
                        <h6> @lang('client/questions.q2a')  </h6>
                        <div class="form-group">
                           <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_1" value="1" required>@lang('client/answers.q1a_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_2" value="2" required>@lang('client/answers.q1a_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_3" value="3" required>@lang('client/answers.q1a_3') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q2a" class="form-check-input" id="q2a_98" value="98" required>@lang('client/answers.q1a_98') </label>
                            </div>
                        </div>
                    </div>
                <!-- div question q2a-->
        
                    <div class="col-md-12 question" >
                        <h6> @lang('client/questions.q3a')  </h6>
                        <div class="form-group">
                          
                            <div class="form-check ">
                            <label><input type="radio" name="q3a" class="form-check-input" id="q3a_1" value="1" required>@lang('client/answers.q1a_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_2" value="2" required>@lang('client/answers.q1a_2') </label>
                            </div> 
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_3" value="3" required>@lang('client/answers.q1a_3') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q3a" class="form-check-input" id="q3a_98" value="98" required>@lang('client/answers.q1a_98') </label>
                            </div>
                            
                        </div>
                    </div>
                    <!-- div question q3a-->
               
                    <div class="col-md-12 question">
                        <h6> @lang('client/questions.q4a')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_1" value="1" required>@lang('client/answers.q1a_1') </label>
                            </div >
                            <div class="form-check ">
                            <label><input type="radio" name="q4a" class="form-check-input" id="q4a_2" value="2" required>@lang('client/answers.q1a_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_3" value="3" required>@lang('client/answers.q1a_3') </label>
                            </div >
                            <div class="form-check ">
                                <label><input type="radio" name="q4a" class="form-check-input" id="q4a_98" value="98" required>@lang('client/answers.q1a_98') </label>
                            </div>
                        
                        </div>
                    </div>
                
                    <!-- div question q4a-->
        
                
                    <div class="col-md-12 question">
                        <h6> @lang('client/questions.q5a')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_1" value="1" required>@lang('client/answers.q1a_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_2" value="2" required>@lang('client/answers.q1a_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q5a_3" value="3" required>@lang('client/answers.q1a_3') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="q5a" class="form-check-input" id="q4a_98" value="98" required>@lang('client/answers.q1a_98') </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q4a-->
        
                <div class="form-group ">
                    <div class="col-md-12">
                        <a href="#"><button class="btn btn-primary"> @lang('pagination.next')  </button></a>
                    </div>
                </div>
            </form>

        
    </div>
</div>

      
 
@stop
