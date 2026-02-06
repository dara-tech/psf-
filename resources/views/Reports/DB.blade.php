@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<style>
.mb-4{
    
    margin-top:20px;
}

</style>

    <h3 class="page-title">PSF Dashboard</h3>
   
    <div class="panel panel-default">
   
        <div class="panel-heading">
            Filter
        </div>

        <div class="panel-body table-responsive">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.reporting.dashboard']]) !!}
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

    <div class="panel panel-default ">
   
        <div class="panel-heading">
            Dashboard
        </div>

        <div class="panel-body table-responsive">
            
             <div class="row mb-4">
                <div class="col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                            $DBController = new App\Http\Controllers\DashboardController();
                            
                            $chart = $DBController->SummaryBySite($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                      
                        @endphp

                    </div>
                </div>
            </div><!--- row -->


            <div class="row mb-4">
                <div class="col-sm-6">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                        @php
                            
                            $chart = $DBController->SummaryByPlatform($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                        
                        @endphp
                       
                     </div>
                </div> <!-- col -->

                <div class="col-sm-6">
                    <div style="width:100%;margin: 0 auto; background-color:white">
                        @php
                            $chart = $DBController->SummaryParticipationChart($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                        @endphp
    
                    </div>

                </div> <!-- col -->
            </div> <!-- row -->
        
            <div class="row mb-4">
                <div class="col-sm-6">
                    <div style="width: 100%;margin: 0 auto; background-color:white">
                        @php
                            $chart = $DBController->SummaryKPChart($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                        @endphp
                 </div>

                 </div> <!-- col -->

                <div class="col-sm-6">
                    <div style="width: 100%;margin: 0 auto; background-color:white">
                         @php
                            $chart = $DBController->SatisfactionWithProviderChart($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                        @endphp
     
                    </div>
                    
                </div> <!-- col -->
                    
            </div><!--row-->

            <div class="row mb-4">
                 <div class="col-sm-6">
                    <div style="width: 100%;margin: 0 auto; background-color:white">
                       @php
                            $chart = $DBController->SatisfactionWithServiceChart($startdate,$enddate,$filtersites);
                            echo $chart->container();
                            echo $chart->script();
                        @endphp
      
                    </div>

                 </div> <!-- col -->
             </div> <!-- row -->
         </div><!--table-->


        </div>
    </div>

   
@stop

@section('javascript') 
    
@endsection