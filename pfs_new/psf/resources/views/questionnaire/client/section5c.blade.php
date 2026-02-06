@extends('includes/header')
@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop

@section('content')
  
<div class="row cardbox">
    <div class="col-md-12">
        <h3 class="text-primary">@lang('client/questions.section_1C') </h3>
        <h4 class="text-primary">@lang('client/questions.part5c1') </h4>
       
        <form action=<?php echo('/client/'. $token.'/section5c') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
          <div class="col-md-12 section">
                <h6> @lang('client/questions.q5c1')  </h6>
                <div class="form-group ">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c1" id="q5c1_1" value="1" required>@lang('client/answers.q5c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c1" id="q5c1_2" value="2" required>@lang('client/answers.q5c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c1" id="q5c1_99" value="99" required>@lang('client/answers.q5c_99') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c1" id="q5c1_98" value="98" required>@lang('client/answers.q5c_98') </label>
                    </div>
                   
                </div>
            </div>
       
        <!-- div question q1a-->
       
            <div class="col-md-12">
                <h6> @lang('client/questions.q5c2')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c2" id="q5c2_1" value="1" required>@lang('client/answers.q5c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c2" id="q5c2_2" value="2" required>@lang('client/answers.q5c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c2" id="q5c2_99" value="99" required>@lang('client/answers.q5c_99') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c2" id="q5c2_98" value="98" required>@lang('client/answers.q5c_98') </label>
                    </div>
                   
                </div>
            </div>
        
        <!-- div question q2a-->
        
       
            <div class="col-md-12">
                <h6> @lang('client/questions.q5c3')  </h6>
                <div class="form-group">
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c3" id="q5c3_1" value="1" required>@lang('client/answers.q5c_1') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c3" id="q5c3_2" value="2" required>@lang('client/answers.q5c_2') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c3" id="q5c3_99" value="99" required>@lang('client/answers.q5c_99') </label>
                    </div>
                    <div class="form-check ">
                        <label><input type="radio" class="form-check-input" name="q5c3" id="q5c3_98" value="98" required>@lang('client/answers.q5c_98') </label>
                    </div>
                   
                </div>
            </div>
     

            <div class="row">
                <div class="col-md-12">
                    <a href="#"><button class="btn btn-primary"> @lang('pagination.next')  </button></a>
                </div>
            </div>
        </form>
                
    </div>
</div>

@stop
