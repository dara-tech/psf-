@php $locale = (session()->get('locale') == "")?"kh":session()->get('locale'); App::SetLocale($locale); @endphp
@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')

    <h3 class="page-title">@lang('global.hfs.title')</h3>
   
    <div class="panel panel-default">
   
        <div class="panel-heading">
            Filter
        </div>

        <div class="panel-body table-responsive">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.hfs.table']]) !!}
            <div class="row input-daterange">

                <div class="col-xs-4 form-group">
                {!! Form::label('startdate', 'Start Date', ['class' => 'control-label']) !!}
                {!! Form::date('startdate', old('startdate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '' ]) !!}
                <p class="help-block"></p>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('startdate') }}
                    </p>
                @endif
                </div>
                <div class="col-xs-4 form-group">
                {!! Form::label('endate', 'End Date', ['class' => 'control-label']) !!}
                {!! Form::date('enddate', old('endate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '' ]) !!}
                <p class="help-block"></p>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('enddate') }}
                    </p>
                @endif
                </div>

                <div class="col-xs-4 form-group">
                {!! Form::label('site', 'Sites', ['class' => 'control-label']) !!}
                {!! Form::select('sites[]', $sites, old('sites'), ['class' => 'form-control select2','multiple' => 'multiple', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('sites'))
                    <p class="help-block">
                        {{ $errors->first('site') }}
                    </p>
                @endif
                </div>
                
                
            </div>
           
        {!! Form::submit("Filter", ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}

        <br> 
        
        </div>

   </div>


    <div class="panel panel-default">
   
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
             <table class="table table-bordered table-striped {{ count($data) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                            <th style="text-align:center;" hidden><input type="checkbox" id="select-all" /></th><!-- datatable nature required this column-->
                            <th>Start</th>
                            <th>Dept</th>
                            <th>Intro</th>
                            <th>E1</th>
                            <th>E2</th>
                            <th>E3</th>
                            <th>E4</th>
                            <th>E5</th>
                            <th>E6</th>
                            <th>SIMSERIAL</th>
                            <th>USERNAME</th>
                            <th>DEVICEID</th>
                            <th>_URI</th>
                            <th>site</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($data) > 0)
                        @foreach ($data as $row)
                            <tr data-entry-id=0>
                                <td hidden></td> <!-- datatable nature required this column-->
                                <td> {{ $row->START}}</td>
                                <td> {{ $row->DEPT}}</td>
                                <td> {{ $row->INTRO}}</td>
                                <td> {{ $row->E1}}</td>
                                <td> {{ $row->E2}}</td>
                                <td> {{ $row->E3}}</td>
                                <td> {{ $row->E4}}</td>
                                <td> {{ $row->E5}}</td>
                                <td> {{ $row->E6}}</td>
                                <td> {{ $row->SIMSERIAL}}</td>
                                <td> {{ $row->USERNAME}}</td>
                                <td> {{ $row->DEVICEID}}</td>
                                <td> {{ $row->_URI}}</td>
                                <td> {{ $row->site}}</td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    
@endsection