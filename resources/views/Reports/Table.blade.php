@php $locale = (session()->get('locale') == "")?"kh":session()->get('locale'); App::SetLocale($locale); @endphp
@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')

    <h3 class="page-title">@lang('global.reporting.patient_title')</h3>
   
    <div class="panel panel-default">
   
        <div class="panel-heading">
            @lang("global.app_filter")
        </div>

        <div class="panel-body table-responsive">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.reporting.table']]) !!}
            <div class="row input-daterange">

                <div class="col-xs-4 form-group">
                {!! Form::label('startdate', trans("global.app_startdate"), ['class' => 'control-label']) !!}
                {!! Form::date('startdate', old('startdate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '' ]) !!}
                <p class="help-block"></p>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('startdate') }}
                    </p>
                @endif
                </div>
                <div class="col-xs-4 form-group">
                {!! Form::label('endate', trans("global.app_enddate"), ['class' => 'control-label']) !!}
                {!! Form::date('enddate', old('endate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '' ]) !!}
                <p class="help-block"></p>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('enddate') }}
                    </p>
                @endif
                </div>

                <div class="col-xs-4 form-group">
                {!! Form::label('site', trans("global.sites.title"), ['class' => 'control-label']) !!}
                {!! Form::select('sites[]', $sites, old('sites'), ['class' => 'form-control select2','multiple' => 'multiple', 'required' => '']) !!}
                <p class="help-block"></p>
                @if($errors->has('sites'))
                    <p class="help-block">
                        {{ $errors->first('site') }}
                    </p>
                @endif
                </div>
                
                
            </div>
           
        {!! Form::submit(trans("global.app_view"), ['class' => 'btn btn-danger']) !!}
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
                           <th>ACKNOWLEDGE</th>
                            <th>Q1A</th>
                            <th>Q2A</th>
                            <th>Q3A</th>
                            <th>Q4A</th>
                            <th>Q5A</th>
                            <th>Q6A</th>
                            <th>Q7A</th>
                            <th>Q8A</th>
                            <th>Q9A</th>
                            <th>Q10A</th>
                            <th>Q1B</th>
                            <th>Q2B</th>
                            <th>Q3B</th>
                            <th>Q5B</th>
                            <th>Q4B</th>
                            <th>Q1C</th>
                            <th>Q2C</th>
                            <th>Q3C_1</th>
                            <th>Q3C_2</th>
                            <th>Q3C_3</th>
                            <th>Q3C_4</th>
                            <th>Q3C_5</th>
                            <th>Q3C_6</th>
                            <th>Q3C_7</th>
                            <th>Q3C_8</th>
                            <th>Q4C</th>
                            <th>Q5C1</th>
                            <th>Q5C2</th>
                            <th>Q5C3</th>
                            <th>Q6C_1</th>
                            <th>Q6C_2</th>
                            <th>Q6C_3</th>
                            <th>Q6C_4</th>
                            <th>Q6C_5</th>
                            <th>Q6C_6</th>
                            <th>Q6C_7</th>
                            <th>Q6C_8</th>
                            <th>Q7C</th>
                            <th>Q8C</th>
                            <th>Q9C_1</th>
                            <th>Q9C_2</th>
                            <th>Q9C_3</th>
                            <th>Q9C_4</th>
                            <th>Q9C_5</th>
                            <th>Q10C</th>
                            <th>Q11C</th>
                            <th>Q12C</th>
                            <th>Q13C</th>
                            <th>Q14C</th>
                            <th>START</th>
                            <th>SIMSERIAL</th>
                            <th>USERNAME</th>
                            <th>META_INSTANCE_ID</th>
                            <th>DEVICEID</th>
                            <th>_URI</th>
                            <th>_IS_COMPLETE</th>
                            <th>_SUBMISSION_DATE</th>
                            <th>_MODEL_VERSION</th>
                            <th>site</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($data) > 0)
                        @foreach ($data as $row)
                            <tr data-entry-id=0>
                                <td hidden></td> <!-- datatable nature required this column-->
                                <td> {{ $row->ACKNOWLEDGE}}</td>
                                <td> {{ $row->Q1A}}</td>
                                <td> {{ $row->Q2A}}</td>
                                <td> {{ $row->Q3A}}</td>
                                <td> {{ $row->Q4A}}</td>
                                <td> {{ $row->Q5A}}</td>
                                <td> {{ $row->Q6A}}</td>
                                <td> {{ $row->Q7A}}</td>
                                <td> {{ $row->Q8A}}</td>
                                <td> {{ $row->Q9A}}</td>
                                <td> {{ $row->Q10A}}</td>
                                <td> {{ $row->Q1B}}</td>
                                <td> {{ $row->Q2B}}</td>
                                <td> {{ $row->Q3B}}</td>
                                <td> {{ $row->Q5B}}</td>
                                <td> {{ $row->Q4B}}</td>
                                <td> {{ $row->Q1C}}</td>
                                <td> {{ $row->Q2C}}</td>
                                <td> {{ $row->Q3C_1}}</td>
                                <td> {{ $row->Q3C_2}}</td>
                                <td> {{ $row->Q3C_3}}</td>
                                <td> {{ $row->Q3C_4}}</td>
                                <td> {{ $row->Q3C_5}}</td>
                                <td> {{ $row->Q3C_6}}</td>
                                <td> {{ $row->Q3C_7}}</td>
                                <td> {{ $row->Q3C_8}}</td>
                                <td> {{ $row->Q4C}}</td>
                                <td> {{ $row->Q5C1}}</td>
                                <td> {{ $row->Q5C2}}</td>
                                <td> {{ $row->Q5C3}}</td>
                                <td> {{ $row->Q6C_1}}</td>
                                <td> {{ $row->Q6C_2}}</td>
                                <td> {{ $row->Q6C_3}}</td>
                                <td> {{ $row->Q6C_4}}</td>
                                <td> {{ $row->Q6C_5}}</td>
                                <td> {{ $row->Q6C_6}}</td>
                                <td> {{ $row->Q6C_7}}</td>
                                <td> {{ $row->Q6C_8}}</td>
                                <td> {{ $row->Q7C}}</td>
                                <td> {{ $row->Q8C}}</td>
                                <td> {{ $row->Q9C_1}}</td>
                                <td> {{ $row->Q9C_2}}</td>
                                <td> {{ $row->Q9C_3}}</td>
                                <td> {{ $row->Q9C_4}}</td>
                                <td> {{ $row->Q9C_5}}</td>
                                <td> {{ $row->Q10C}}</td>
                                <td> {{ $row->Q11C}}</td>
                                <td> {{ $row->Q12C}}</td>
                                <td> {{ $row->Q13C}}</td>
                                <td> {{ $row->Q14C}}</td>
                                <td> {{ $row->START}}</td>
                                <td> {{ $row->SIMSERIAL}}</td>
                                <td> {{ $row->USERNAME}}</td>
                                <td> {{ $row->META_INSTANCE_ID}}</td>
                                <td> {{ $row->DEVICEID}}</td>
                                <td> {{ $row->_URI}}</td>
                                <td> {{ $row->_IS_COMPLETE}}</td>
                                <td> {{ $row->_SUBMISSION_DATE}}</td>
                                <td> {{ $row->_MODEL_VERSION}}</td>
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