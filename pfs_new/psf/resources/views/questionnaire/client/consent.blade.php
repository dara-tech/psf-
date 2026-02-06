@section('section_title')

<h2 class="text-primary"> @lang('client/questions.title') - {{$site}} </h2>
@stop

<div class="row cardbox">
    <div class="col-md-12 section">
        <h3 class="text-primary">@lang('client/questions.section_1A')  </h3>
        
        <form action=<?php echo('/client/'. $token.'/consent') ?> method="POST">
            @csrf
            <input type="hidden" id="locale" name="locale" value="{{$locale}}"/>
            <input type="hidden" id="_uri" name="_uri" value="{{$uuid}}"/>
        
            <div class="col-md-12 section">
                <p> @lang('client/questions.acknowledge')  </p>
                <div class="form-group">
                    <div class="form-check">
                        <label><input type="radio" class="form-check-input" name="consent" id="consent1" value="1" required>@lang('client/answers.yesno_1') </label>
                       
                    </div>
                    <div class="form-check">
                        <label><input type="radio" class="form-check-input" name="consent" id="consent2" value="2" required>@lang('client/answers.yesno_2')</label>
                    </div>
                </div>
            </div>
       
            <div class="form-group">
                <div class="col-md-12 section">
                    <a href="#"><button class="btn btn-primary"> @lang('pagination.next')  </button></a>
                </div>
            </div>
        </form>
    </div>
</div>