@extends('includes/header')
@section('section_title')

<h2 class="text-primary"> @lang('provider/questions.title') - {{$site}} </h2>
@stop


@section('content')
  
<div class="row cardbox">
    <div class="col-md-12 section">
        <h3 class="text-primary">@lang('provider/questions.section1') </h3>
            <form action=<?php echo('/provider/'. $token.'/section1') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
                
                    <div class="col-md-12 question">
                        <h6> @lang('provider/questions.dept')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="dept"  id="d1_1" class="form-check-input" value="1" required/>@lang('provider/answers.d1_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="dept"  id="d1_2" class="form-check-input" value="2" required/>@lang('provider/answers.d1_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="dept"  id="d1_3" class="form-check-input" value="3" required/>@lang('provider/answers.d1_3') </label>
                            </div> 
                            <div class="form-check ">     
                                <label><input type="radio" name="dept"  id="d1_4" class="form-check-input" value="4" required/>@lang('provider/answers.d1_4') </label>
                            </div>
                            <div class="form-check ">     
                                <label><input type="radio" name="dept"  id="d1_5" class="form-check-input" value="5" required/>@lang('provider/answers.d1_5') </label>
                            </div>
                            <div class="form-check ">     
                                <label><input type="radio" name="dept"  id="d1_6" class="form-check-input" value="6" required/>@lang('provider/answers.d1_6') </label>
                            </div>
                            <div class="form-check ">     
                                <label><input type="radio" name="dept"  id="d1_7" class="form-check-input" value="99" required/>@lang('provider/answers.d1_7') </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q1a-->
                    <h3>@lang('provider/questions.section2') </h3><br>
                    <div class="col-md-12 question">
                        <h6> @lang('provider/questions.e1')  </h6>
                        <div class="form-group">
                           <div class="form-check ">
                                <label><input type="radio" name="e1" class="form-check-input" id="e1_1" value="1" required>@lang('provider/answers.e1_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e1" class="form-check-input" id="e1_2" value="2" required>@lang('provider/answers.e1_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e1" class="form-check-input" id="e1_3" value="98" required>@lang('provider/answers.e1_3') </label>
                            </div>
                          
                        </div>
                    </div>
                <!-- div question q2a-->
        
                    <div class="col-md-12 question" >
                        <h6> @lang('provider/questions.e2')  </h6>
                        <div class="form-group">
                          
                            <div class="form-check ">
                            <label><input type="radio" name="e2" class="form-check-input" id="e2_1" value="1" required>@lang('provider/answers.e2_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e2" class="form-check-input" id="e2_2" value="2" required>@lang('provider/answers.e2_2') </label>
                            </div> 
                            <div class="form-check ">
                                <label><input type="radio" name="e2" class="form-check-input" id="e2_3" value="98" required>@lang('provider/answers.e2_3') </label>
                            </div>
                           
                            
                        </div>
                    </div>
                    <!-- div question q3a-->
               
                    <div class="col-md-12 question">
                        <h6> @lang('provider/questions.e3')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="e3" class="form-check-input" id="e3_1" value="1" required>@lang('provider/answers.e3_1') </label>
                            </div >
                            <div class="form-check ">
                            <label><input type="radio" name="e3" class="form-check-input" id="e3_2" value="2" required>@lang('provider/answers.e3_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e3" class="form-check-input" id="e3_3" value="98" required>@lang('provider/answers.e3_3') </label>
                            </div >
                            <div class="form-check ">
                                <label><input type="radio" name="e3" class="form-check-input" id="e3_4" value="99" required>@lang('provider/answers.e3_4') </label>
                            </div>
                        
                        </div>
                    </div>
                
                    <!-- div question q4a-->
                    <div class="col-md-12 question">
                        <h6> @lang('provider/questions.e4')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="e4" class="form-check-input" id="e4_1" value="1" required>@lang('provider/answers.e4_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e4" class="form-check-input" id="e4_2" value="2" required>@lang('provider/answers.e4_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e4" class="form-check-input" id="e4_3" value="3" required>@lang('provider/answers.e4_3') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e4" class="form-check-input" id="e4_4" value="4" required>@lang('provider/answers.e4_4') </label>
                            </div>
                        </div>
                    </div>
                    <!-- div question q4a-->

                     <!-- div question q4a-->
                     <div class="col-md-12 question">
                        <h6> @lang('provider/questions.e5')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="e5" class="form-check-input" id="e5_1" value="1" required>@lang('provider/answers.e5_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e5" class="form-check-input" id="e5_2" value="2" required>@lang('provider/answers.e5_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e5" class="form-check-input" id="e5_3" value="3" required>@lang('provider/answers.e5_3') </label>
                            </div>
                           
                        </div>
                    </div>
                    <!-- div question q4a-->
                    <!-- div question q4a-->
                    <div class="col-md-12 question">
                        <h6> @lang('provider/questions.e6')  </h6>
                        <div class="form-group">
                            <div class="form-check ">
                                <label><input type="radio" name="e6" class="form-check-input" id="e6_1" value="1" required>@lang('provider/answers.e6_1') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e6" class="form-check-input" id="e6_2" value="2" required>@lang('provider/answers.e6_2') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e6" class="form-check-input" id="e6_3" value="3" required>@lang('provider/answers.e6_3') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e6" class="form-check-input" id="e6_4" value="4" required>@lang('provider/answers.e6_4') </label>
                            </div>
                            <div class="form-check ">
                                <label><input type="radio" name="e6" class="form-check-input" id="e6_5" value="5" required>@lang('provider/answers.e6_5') </label>
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
