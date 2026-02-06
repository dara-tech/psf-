@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.sites.title')</h3>
    
    {!! Form::model($site, ['method' => 'PUT', 'route' => ['admin.sites.update', $site->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_edit')
        </div>

        <div class="panel-body">
        <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('username', 'User Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('username'))
                        <p class="help-block">
                            {{ $errors->first('username') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('sitecode', 'Site Code*', ['class' => 'control-label']) !!}
                    {!! Form::text('sitecode', old('sitecode'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('sitecode'))
                        <p class="help-block">
                            {{ $errors->first('sitecode') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('province', 'Province Eng*', ['class' => 'control-label']) !!}
                    {!! Form::text('province', old('province'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('province'))
                        <p class="help-block">
                            {{ $errors->first('province') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('province_kh', 'Province KH*', ['class' => 'control-label']) !!}
                    {!! Form::text('province_kh', old('province_kh'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('province_kh'))
                        <p class="help-block">
                            {{ $errors->first('province_kh') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('sitename', 'Site Eng*', ['class' => 'control-label']) !!}
                    {!! Form::text('sitename', old('sitename'), ['class' => 'form-control', 'placeholder' => '', 'required' => '' ]) !!}
                    <p class="help-block"></p>
                    @if($errors->has('sitename'))
                        <p class="help-block">
                            {{ $errors->first('siename') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('site', 'Site KH*', ['class' => 'control-label']) !!}
                    {!! Form::text('site', old('site'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('site'))
                        <p class="help-block">
                            {{ $errors->first('site') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

