@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.users.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.users.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('email', 'Email*', ['class' => 'control-label']) !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('password', 'Password*', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('roles', 'Roles*', ['class' => 'control-label']) !!}
                    {!! Form::select('roles[]', $roles, old('roles'), ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('roles'))
                        <p class="help-block">
                            {{ $errors->first('roles') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">

                <div class="col-xs-12 form-group">
                    {!! Form::label('province', 'ខេត្ត', ['class' => 'control-label']) !!}
                    {!! Form::select('provinces[]', $provinces, old('provinces'), ['class' => 'form-control select2','id'=>'province']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('provinces'))
                        <p class="help-block">
                            {{ $errors->first('province') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('sites', 'Sites*', ['class' => 'control-label']) !!}
                    {!! Form::select('sites[]', $sites, old('sites'), ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => '','id'=>'sites']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('sites'))
                        <p class="help-block">
                            {{ $errors->first('sites') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop


@section('javascript') 

<script>

        var allSites =  {!! $allsites !!} ;
        $("#province").change(function(e){
          
          $("#sites").empty();
          var i = 1;
          allSites.forEach( function( site)
          {
              if (site.province == $("#province :selected").text())
              {
                var option = document.createElement('option');
                option.text = site.site;
                option.value = i;
                $("#sites").append("<option>" + site.site + "</option>");
                i++;

              }else if ( $("#province :selected").text() == "")
              {
                var option = document.createElement('option');
                option.text = site.site;
                option.value = i;
                $("#sites").append("<option value="+ i +">" + site.site + "</option>");
                i++;
              }
          });

            $("#sites").prepend("<option value=0> * </option>");    
          //  alert ($("#province :selected").text());
        });

</script>
    
@endsection