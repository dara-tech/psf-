@php $locale = (session()->get('locale') == "")?"kh":session()->get('locale'); App::SetLocale($locale); @endphp
@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('header')


<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
Chart.register(ChartDataLabels); // register plugin for datalabels

function showLabel(value, index, values) {
    return value;
}
</script>

@stop

@section('content')
<style>
.mb-4{
    
    margin-top:20px;
}

</style>

    <h3 class="page-title">@lang('global.hfs.title')</h3>
   
    <div class="panel panel-default">
   
        <div class="panel-heading">
            @lang("global.app_filter")
        </div>

        <div class="panel-body table-responsive">
        {!! Form::open(['method' => 'POST', 'route' => ['admin.hfs.dashboard']]) !!}
                <div class="row input-daterange">
                    <div class="col-md-3 col-sm-12 form-group">
                        {!! Form::label('period',trans('admin.when'), ['class' => 'control-label']) !!}
                        {!! Form::select('period[]', $period, old('period'), ['class' => 'form-control select2','id'=>'period', 'multiple'=>'multiple', 'required'=>'']) !!}
                        {!! Form::checkbox('isFiscalYear', $isFiscalYear, old('isFiscalYear'), ['class' => 'form-check-input','id'=>'isFiscalYear',(($isFiscalYear)? 'checked' : '')=> '', 'value' => '1']) !!}
                        {!! Form::label('FiscalYear', 'isFiscalYear', ['class' => 'control-label']) !!}     
                        <p class="help-block"></p>
                        @if($errors->has('period'))
                            <p class="help-block">
                                {{ $errors->first('period') }}
                            </p>
                        @endif
                    </div>
                    
                </div>
                <div class="row input-daterange">
                <div class="col-md-4 col-sm-12 form-group ">
                    {!! Form::label('province', trans('admin.province'), ['class' => 'control-label']) !!}
                    {!! Form::select('provinces[]', $provinces, old('provinces'), ['class' => 'form-control select2','id'=>'province']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('provinces'))
                        <p class="help-block">
                            {{ $errors->first('province') }}
                        </p>
                    @endif
                </div>
                <div class="col-md-4 col-sm-12 form-group">
                    {!! Form::label('site',trans('admin.hospital'), ['class' => 'control-label']) !!}
                    {!! Form::select('sites[]', $sites, old('sites'), ['class' => 'form-control select2','multiple' => 'multiple','id'=>'sites']) !!}
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
                <div class="col-md-12 col-sm-12">
                    <div style="width: 100%;margin: 0 auto; ">
                         @php
                            $DBController = new App\Http\Controllers\HFS_DashboardController();
                            
                           $dataset = $DBController->getDataSet($filterPeriod,$isFiscalYear,$filtersites,$filterProvince);
                           
                            $monthReport =$DBController->getReportMonthByQuarter($filterPeriod,$isFiscalYear);

                            if ($monthReport !=null)
                            {
                                echo '<h1> '. trans('admin.resultdate') .' '. $monthReport[0] . trans('admin.resultdate2') .'  ' .   $monthReport[count($monthReport)-1] . ' </h1>';
                            }
  
                       
                            if ($dataset != null)
                            {
                                if ( count($dataset) == 0)
                                { echo '<h2 style="color:red"> '. trans('admin.noresult')  .' </h2>';}
                            }
                        @endphp

                    </div>
                </div>
            </div><!-- row -->


            <div class="row mb-4">
                <div class="col-md-4 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                               // $chart = $DBController->SummaryParticipationChart($dataset,$filtersites,$monthReport,$isFiscalYear);
                               $chart = $DBController->SummaryParticipationChartByQ_V3($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                                }
                           }
                        
                        @endphp

                    </div>
                </div><!--col-->

                <div class="col-md-8 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                                $chart = $DBController->ParticipantDeptChart($dataset,$filtersites,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                            
                                }
                           }
                        @endphp

                    </div>
                </div><!--col-->


            </div><!-- row -->

            <div class="row mb-4">
                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                                $chart = $DBController->ObservedUnwillingServiceChat($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                                }
                           }
                                
                        @endphp

                    </div>
                </div><!--col-->

                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                            if ($dataset !=null)
                            {
                                $chart = $DBController->ObservedLowQualityServiceChat($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                            
                                }
                            }
                        @endphp

                    </div>
                </div><!--col-->


            </div><!-- row -->


            <div class="row mb-4">
                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           if ($dataset !=null)
                           {
                                $chart = $DBController->DoubleGloveChart($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                        echo $chart->container();
                                        echo $chart->script();
                            
                                }
                           }

                           // echo var_dump($chart);
                        @endphp

                    </div>
                </div><!--col-->

                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                                $chart = $DBController->DrawBloodChart($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                                }
                           }
                     
                        
                        @endphp

                    </div>
                </div><!--col-->


            </div><!-- row -->

            <div class="row mb-4">
                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                                $chart = $DBController->EnoughEquipmentChart($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                        echo $chart->container();
                                        echo $chart->script();
                            
                                }
                           }

                        @endphp

                    </div>
                </div><!--col-->

                <div class="col-md-6 col-sm-12">
                    <div style="width: 100%;margin: 0 auto;  background-color:white">
                         @php
                           
                           if ($dataset !=null)
                           {
                                $chart = $DBController->ServiceQualityChart($dataset,$filtersites,$monthReport,$isFiscalYear);
                                if($chart !=null)
                                {
                                    echo $chart->container();
                                    echo $chart->script();
                                }
                           }
                            
                        
                        @endphp

                    </div>
                </div><!--col-->


            </div><!-- row -->


         </div><!--table-->


        </div>
    </div>

   
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