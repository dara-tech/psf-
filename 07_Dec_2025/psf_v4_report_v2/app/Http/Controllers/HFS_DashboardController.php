<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Charts\SampleChart;
use App\Register;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

use App\Models\HFS;
use App\Models\Site;


class HFS_DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     //   $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * 
     */


    private $colorList = ['#003f5c','#374c80','#7a5195','#bc5090','#ef5675','#ff764a','#ffa600',
      '#669900',
      '#8CBA90',      
      '#336600',
      '#CD5B45',
      '#F0A804','#EEE8CD',
      '#C5C1AA','#C0FF3E',
      '#D0A9AA','#CFB53B',
      '#EEDC82','#EEB4B4',
      '#F2F2F2','#F08080',
      
      '#D9D9D9','#CCFFCC','#D66F62',
      '#EDC393','#EAEAAE',
      '#F7B3DA','#F7F7F7',
       
      '#D2E4D4',
      '#CAE1FF','#C9AF94',
      '#E2DDB5','#E0EEEE',
      '#F3E88E','#F4A460',
      '#B7C8B6','#B6AFA9',
      
      
      '#EE799F','#EE6A50',
      '#CBCAB6','#CAFF70',
      '#F6A4D5','#F5DEB3',
      
      '#D1E231','#D02090',
      '#FAEBD7', '#F64D54',
      '#DBDBDB', '#CD9B9B',
      '#E79EA9','#E3E3E3',
      '#C76114','#C6E2FF',
      '#E18E2E', '#E0427F',
      '#FF82AB','#FF2400',
      '#FF007F', '#FEE5AC',
      '#FDF5E6',  '#CC99CC',
      '#FBA16C','#FA9A50',
     
      '#DFFFA5','#DDA0DD',
      '#BAAF07','#B9D3EE',
      '#F2F2F2','#F08080',
      '#CD5B45','#CCFFCC',
      '#D9D9D9','#D66F62',
      '#EDC393','#EAEAAE',
      '#F7B3DA','#F7F7F7',
     
      '#FFBBFF','#FFAA00',
      '#FCDC3B' ,'#FCB514',
      '#FC1501','#D2B48C',
      '#E31230','#E0DFDB',
      '#CDC0B0','#CDBE70',
      '#F0E68C','#F0F0F0',
      '#F4F776','#F5785A',
      '#DE85B1','#DD7500',
      '#E47833','#E3A869',
     
      '#DB9370','#DB7093',
      '#C6C3B5','#BEBEBE',
      '#EE30A7','#E9967A',
      

    ];


    public function index(Request $request)
    {
      
      $data=[];
      $role = Auth::user()->roles()->first();
      $startdate= date("Y-m-d", strtotime("-1 months"));
      $enddate= date("Y-m-d");
  

      $sites = $this->getUserSites();
     
       
      $isFiscalYear= ($request->period!=null)? $request->isFiscalYear:false;
     
      $startdate = $request->startdate;
      $enddate = $request->enddate;

      $allsite=[];
      if ($request->sites !=null)
      {
        //--check if all site selected
        $allsite = array_filter($request->sites,function($site)
        {
            return ($site === "*");
        });
        // end check all site selected
      }

      if (sizeof($allsite)==0)
      {
        $filtersites = $request->sites;
      }else{
        $filtersites = $sites;
      }

      $filterProvince=[];

      if ($request->provinces !=null)
      {
        $filterProvince = $this->getProvinces()[$request->provinces[0]];    
      }

       return view('HFS.Dashboard')->with('data', $data)
                                  ->with('sites',$sites)
                                  ->with('startdate',$startdate)
                                  ->with('enddate',$enddate)
                                  ->with('filtersites', $filtersites)
                                  ->with('filterProvince',$filterProvince)
                                  ->with ('provinces',$this->getProvinces())
                                  ->with('filterPeriod',$request->period)
                                  ->with('isFiscalYear',$isFiscalYear)
                                  ->with('allsites', $this->getAllSitesAndProvinces())
                                  ->with ('period', $this->getListPeriodReport());
                                  ;
      
    }
    
    private function getUserSites()
    {
      return BaseController::getUserSites();
    }
    private function getListPeriodReport()
    {
      return BaseController::getListPeriodReport();
    }

    private function getProvinces()
    {
      return BaseController::getProvinces();
    }

    public function getMonthReport($startdate, $enddate)
    {

       return BaseController:: getMonthReport($startdate, $enddate);
    }

    public function getReportMonthByQuarter($period,$isFiscalYear)
    {
      return BaseController::getReportMonthByQuarter($period,$isFiscalYear);
    }
   
    private function covertDate2Quarter($period,$isFiscalYear)
    {
      return BaseController::covertDate2Quarter($period,$isFiscalYear);
    }
    private function getAllSitesAndProvinces()
    {
      return BaseController::getAllSitesAndProvinces();
    }
   
    public function getDataSet($period,$isFiscalYear,$filtersites,$filterProvince)
    {
     
            if ($period == null) return null; // fix error

            if ($filtersites ==null) $filtersites = $this->getUserSites();

            $where = [];

            $reportMonths = $this->getReportMonthByQuarter($period,$isFiscalYear);
            $data=null;
            $locale = session()->get('locale');
            if ($locale == "en")
            {
              $site = "sitename";
              $province = "province";
            }else{
              $site = "site";
              $province = "province_kh";
            }
            if ($filterProvince !=null)
            {
              array_push ($where,[$province, "=", $filterProvince]);
            }

            if ($filtersites !=null)
            {
                $data = DB::table('view_psf_hfs_self_assessment_v4') 
                ->select("*") 
                ->where($where)
                ->whereNotNull('dept')
                ->wherein('month', $reportMonths)
                ->wherein($site,$filtersites)
                ->get();
            }else
            {
                  $data = DB::table('view_psf_hfs_self_assessment_v4') 
                  ->select("*") 
                  ->where($where)
                  ->wherein('month', $reportMonths)
                  ->whereNotNull('dept')
                  ->get();
            }

            
            return $data;
    }

    public function SummaryParticipationChartByQ_V3($dataset, $filtersites, $monthReport,$isFiscalYear,$byMonth=false)
      {
  
          $data= array();
        
          $AgreeCount=0;
          $DisagreeCount =0;
          $chart = new SampleChart;
          $reportQ = "";
         
          $QList = array();
          $agreed=array();
          $disagreed=array();

          for($i=0; $i <count($monthReport);$i++) 
          {
  
              if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
              {
                $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);
                array_push($QList,$reportQ);
              }
    
              $total=0;
              $AgreeCount=0;
              $DisagreeCount=0;
             // $agreed = array();
              foreach($dataset as $data)
              {
                $datevalue = date_format(date_create($data->START),'F-y');
        
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
                {    
                    $total++;
                    if ($data->DEPT != null)
                    {
                        $AgreeCount++;
                    }else
                    {
                      $DisagreeCount++;
                    }
                }
              }
          
             if ( $i+1 < count($monthReport))
            {
                if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
            }

            // $agreed = array();
            $TotalCount =(object) array('label'=>$reportQ,'data'=>"".$AgreeCount);
            array_push($agreed,$TotalCount);
                  
                 
          }
        
          $chart->dataset($reportQ, 'bar',array_column($agreed, 'data'))
          ->color($this->colorList[1])
          ->backgroundcolor($this->colorList);

        $chart->displayLegend(false);
        $chart->title(trans("hfs.chart1_title"),24,'black');
        $title =trans("hfs.chart1_title");
        $chart->options([
          "plugins"=>"{
            legend:{
              display:true,
              position:'bottom',
            },
            title:{
              position:'top',
              text:'".$title."',
              display:true,
              font:{
                size:18,
              },
              color:'black'
            },
            datalabels:{
              display:true,
              font:{
                size:15,
              },
              color:'white'
            }
          }"
              
      ]);

      $chart->labels($QList);
                          
         
     return $chart;

    }
    
    public function SummaryParticipationChart($dataset, $filtersites, $monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load

        $data= array();
       
        $TotalCount =(object) array('label'=>'ចូលរួម','data'=>"".count($dataset));
        array_push($data,$TotalCount);
        

        $chart = new SampleChart;
        $chart->labels(array_column($data, 'label'));
        $chart->dataset(trans("hfs.chart1_title"), 'pie',array_column($data, 'data'))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);

          $chart->displayLegend(false);
          $chart->title(trans("hfs.chart1_title"),24,'black');
          $title =trans("hfs.chart1_title");
          $chart->options([
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:'".$title."',
                display:true,
                font:{
                  size:18,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15,
                },
                color:'white'
              }
            }"
                
        ]);
          return $chart;


    }


    public function ParticipantDeptChart($dataset, $filtersites)
    {

      if (count($dataset)==0) return; // fixed error first load


      $data= array();

      $artDept = 0;
      $ancDept=0;
      $hivStiDept=0;
      $labDept=0;
      $methadoneDept=0;
      $tbDept=0;
      $otherDept=0;
  
        foreach($dataset as $data)
        {

          if ($data->DEPT == 1)
          {
            $artDept++;
          }else  if ($data->DEPT == 2)
          {
            $ancDept++;
          }else  if ($data->DEPT == 3)
          {
            $hivStiDept++;
          }else  if ($data->DEPT == 4)
          {
            $labDept++;
          }else  if ($data->DEPT == 5)
          {
            $methadoneDept++;
          }else  if ($data->DEPT == 6)
          {
            $tbDept++;
          }else  if ($data->DEPT == 99)
          {
            $otherDept++;
          }
        }

        $chart = new SampleChart;
        $data = array();
        if (count($dataset)>0)
        {          
          $TotalCount =(object) array('label'=>trans("hfs.chart2_legend1"),'data'=>"".$artDept);
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("hfs.chart2_legend2_1") ,trans("hfs.chart2_legend2_2")],'data'=>"".$ancDept);
          array_push($data,$TotalCount);
          $TotalCount1 =(object) array('label'=>[trans("hfs.chart2_legend3_1"),trans("hfs.chart2_legend3_2")],'data'=>"".$hivStiDept);
          array_push($data,$TotalCount1);
          $TotalCount =(object) array('label'=>[trans("hfs.chart2_legend4")],'data'=>"".$labDept);
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("hfs.chart2_legend5_1"),trans("hfs.chart2_legend5_2")],'data'=>"".$methadoneDept);
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("hfs.chart2_legend6")],'data'=>"".$tbDept);
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("hfs.chart2_legend7")],'data'=>"".$otherDept);
          array_push($data,$TotalCount);
        }
     
        $chart->labels(array_column($data, 'label'));
        $chart->dataset(trans("hfs.chart2_title"), 'bar', array_column($data, 'data'))
              ->color($this->colorList)
              ->backgroundcolor($this->colorList);
        $chart->displayLegend(false);
        $title =trans("hfs.chart2_title");
        $chart->options([
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:'".$title."',
                display:true,
                font:{
                  size:18,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15                 
                },
                color:'white'               
              }
            }"
                
        ]);

          return $chart; // 

    }


    public function ObservedUnwillingServiceChat($dataset, $filtersites,$monthReport,$isFiscalYear)
    {
      if (count($dataset)==0) return; // fixed error first load
    
      $chart = new SampleChart;

      $observedUnwilling=0;
      $total=0;
      $arrayMonth=array();

      $reportQ="";

      for($i=0; $i <count($monthReport);$i++)
      {
            $observedUnwilling=0;
            $total=0;

            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
              $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }
    
            foreach($dataset as $data)
            {
                $datevalue = date_format(date_create($data->START),'F-y');            
                
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  
                  if ($data->E1 != null)
                  {
                      if ($data->E1 == 1)
                      {
                        $observedUnwilling++;
                      }
                        $total++;
                  }// not null
                }
            }// each data

            
             if($total>0)
              {

                    if ( $i+1 < count($monthReport))
                    {
                        if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                    }
                    
                    $dataArray= array();

                      $TotalCount =(object) array('label'=> trans("hfs.yes").' (%)','data'=>"". round($observedUnwilling / $total * 100,1));
                      array_push($dataArray,$TotalCount);

                      $chart->labels(array_column($dataArray, 'label'));
                      $chart->dataset($reportQ, 'bar', array_column($dataArray, 'data'))
                      ->color($this->colorList[$i])
                      ->backgroundcolor($this->colorList[$i]);

                      array_push($arrayMonth, $monthReport[$i]);
              }
      
      
      }// each month
       
         
      if (isset($dataArray)>0)
      {
         
          $chart->displayLegend(false);
        
         $title ="[\"".trans("hfs.chart3_title_1")."\", \"".trans("hfs.chart3_title_2")."\",\"".trans("hfs.chart3_title_3")."\"]";
          $chart->options([
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:".$title.",
                display:true,
                font:{
                  size:20,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15,
                },
                color:'white'
              }
            }"
                
        ]);
         
          return $chart; // 
      }
          return null;

    }


    public function ObservedLowQualityServiceChat($dataset, $filtersites,$monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load
    
      $chart = new SampleChart;

      $observedLowQuality=0;
      $total=0;
      $arrayMonth=array();

      $reportQ="";

      for($i=0; $i <count($monthReport);$i++)
      {
            $observedLowQuality=0;
            $total=0;

            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
              $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }
    

            foreach($dataset as $data)
            {
                $datevalue = date_format(date_create($data->START),'F-y');
            

                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  
                  if ($data->E2 !=null)
                  {
                    if ($data->E2 == 1)
                    {
                      $observedLowQuality++;
                    }
                      $total++;
                  }// not null
                }
            }// each data

            
             if($total>0)
              {
                 
                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                  }

                  $dataArray= array();

                  $TotalCount =(object) array('label'=>trans("hfs.yes"). ' (%)','data'=>"". round($observedLowQuality / $total * 100,1)  ) ;
                  array_push($dataArray,$TotalCount);

                  $chart->labels(array_column($dataArray, 'label'));
                  $chart->dataset($reportQ, 'bar', array_column($dataArray, 'data'))
                  ->color($this->colorList[$i])
                  ->backgroundcolor($this->colorList[$i]);

                  array_push($arrayMonth, $monthReport[$i]);
              }      
      
      }// each month
       
         
      if (isset($dataArray)>0)
      {
         
          $chart->displayLegend(false);
          $title ="[\"".trans("hfs.chart4_title_1")."\", \"".trans("hfs.chart4_title_2")."\",\"".trans("hfs.chart4_title_3")."\"]";
          $chart->options([
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:".$title.",
                display:true,
                font:{
                  size:18,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15,
                },
                color:'white'
              }
            }"
                
        ]);


          return $chart; // 
      }

            return null;

    }



    public function DoubleGloveChart($dataset, $filtersites,$monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load
    
      $chart = new SampleChart;
      $arrayMonth=array();
      $dataArrayYes= array();
      $dataArrayNo= array();
      $dataArrayDk= array();
      $dataArrayNo= array();
      $dataArray=array();
      $dataArrayNoNeed=array();

      
      $label = [trans("hfs.yes").' (%)', trans("hfs.no").' (%)',trans("hfs.dont_know").' (%)',trans("hfs.na").' (%)'];


      $reportQ = "";

      for($i=0; $i <count($monthReport);$i++)
      {
           
            $doubleYes=0;
            $doubleNo=0;
            $dk=0;
            $noneed=0;
            $total=0;


            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
               $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }
    
            
            foreach($dataset as $data)
            {
              
                $datevalue = date_format(date_create($data->START),'F-y');
            
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  
                  if ($data->E3 !=null)
                  {
                      if ($data->E3 == 1)
                      {
                        $doubleYes++;
                      }else if ($data->E3 == 2)
                      {
                        $doubleNo++;
                      }else if ($data->E3 == 98)
                      {
                        $dk++;
                      }else if ($data->E3 == 99)
                      {
                        $noneed++;
                      }
                    
                      $total++;
                    }
          
                }// monthreport
            }// each data

            
             if($total>0)
              {

                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                  }
              
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round($doubleYes/$total * 100 ,1)  ) ;
                  array_push($dataArrayYes,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round($doubleNo/$total * 100,1)  ) ;
                  array_push($dataArrayNo,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round($dk/$total * 100 ,1)  ) ;
                  array_push($dataArrayDk,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round($noneed/$total * 100,1)  ) ;
                  array_push($dataArrayNoNeed,$TotalCount);
             
                  array_push($arrayMonth, $reportQ);
                
              }            
      
      }// each month
       

         
      if (isset($dataArrayYes)>0)
      {
         
          //   $chart->labels($arrayMonth);
          $chart->dataset(trans("hfs.yes").' (%)', 'bar', array_column($dataArrayYes, 'data'))
          ->color($this->colorList[1])
          ->backgroundcolor($this->colorList[1]);

          $chart->dataset(trans("hfs.no").' (%)', 'bar', array_column($dataArrayNo, 'data'))
          ->color($this->colorList[2])
          ->backgroundcolor($this->colorList[2]);

          $chart->dataset(trans("hfs.dont_know").' (%)', 'bar', array_column($dataArrayDk, 'data'))
          ->color($this->colorList[3])
          ->backgroundcolor($this->colorList[3]);


          $chart->dataset(trans("hfs.na").' (%)', 'bar', array_column($dataArrayNoNeed, 'data'))
          ->color($this->colorList[4])
          ->backgroundcolor($this->colorList[4]);
       
          $chart->labels($arrayMonth);
        
          $title ="[\"".trans("hfs.chart5_title_1")."\", \"".trans("hfs.chart5_title_2")."\"]";
          $chart->options(['indexAxis'=>'y',
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:".$title.",
                display:true,
                font:{
                  size:18,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15,
                },
                color:'white'
              }
            }"
                
        ]);


          return $chart; // 
      }

      return null;

    }


    
    public function DrawBloodChart($dataset, $filtersites,$monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load
    
      $chart = new SampleChart;
      $arrayMonth=array();
      $dataArrayVeryWorries= array();
      $dataArrayLittleWorries= array();
      $dataArrayNotWorries= array();
      $dataArrayNoNeed= array();
      $dataArray=array();
     
      $label = ['បាទ/ចាស (%)','ទេ (%)','មិនដឹង (%)','អត់មាន/ មិនត្រូវការពាក់ស្រោមដៃពីរជាន់ (%)'];

      $reportQ="";

      for($i=0; $i <count($monthReport);$i++)
      {
           
            $veryWorries=0;
            $littleWorries=0;
            $notWorries=0;
            $noneed=0;
            $total=0;

            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
               $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }
        

            foreach($dataset as $data)
            {
              
              
                $datevalue = date_format(date_create($data->START),'F-y');
            
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  

                  if ($data->E4 !=null)
                  {
                      if ($data->E4 == 1)
                      {
                        $veryWorries++;
                      }else if ($data->E4 == 2)
                      {
                        $littleWorries++;
                      }else if ($data->E4 == 3)
                      {
                        $notWorries++;
                      }else if ($data->E4 == 4)
                      {
                        $noneed++;
                      }
                    
                      $total++;
                    }
          
                }// monthreport
            }// each data

            
             if($total>0)
              {

                	
                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                  }
              
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($veryWorries/$total) * 100 ,1)  ) ;
                  array_push($dataArrayVeryWorries,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($littleWorries/$total) * 100,1)  ) ;
                  array_push($dataArrayLittleWorries,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($notWorries/$total )* 100 ,1)  ) ;
                  array_push($dataArrayNotWorries,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($noneed/$total) * 100,1)  ) ;
                  array_push($dataArrayNoNeed,$TotalCount);
             
                  array_push($arrayMonth,$reportQ);
                
              }
            
      
      }// each month
       

         
      if (isset($reportQ)>0)
      {
         
              //   $chart->labels($arrayMonth);
              $chart->dataset(trans("hfs.worried_1").' (%)', 'bar', array_column($dataArrayVeryWorries, 'data'))
              ->color($this->colorList[1])
              ->backgroundcolor($this->colorList[1]);

              $chart->dataset(trans("hfs.worried_2").' (%)', 'bar', array_column($dataArrayLittleWorries, 'data'))
              ->color($this->colorList[2])
              ->backgroundcolor($this->colorList[2]);

              $chart->dataset(trans("hfs.worried_3").' (%)', 'bar', array_column($dataArrayNotWorries, 'data'))
              ->color($this->colorList[3])
              ->backgroundcolor($this->colorList[3]);


                $chart->dataset(trans("hfs.worried_4").' (%)', 'bar', array_column($dataArrayNoNeed, 'data'))
              ->color($this->colorList[4])
              ->backgroundcolor($this->colorList[4]);
       
           $chart->labels($arrayMonth);
         
          $title ="[\"".trans("hfs.chart6_title_1")."\", \"".trans("hfs.chart6_title_2")."\", \"".trans("hfs.chart6_title_3")."\"]";
          $chart->options(['indexAxis'=>'y',
            "plugins"=>"{
              legend:{
                display:true,
                position:'bottom',
              },
              title:{
                position:'top',
                text:".$title.",
                display:true,
                font:{
                  size:18,
                },
                color:'black'
              },
              datalabels:{
                display:true,
                font:{
                  size:15,
                },
                color:'white'
              }
            }"
                
        ]);
          return $chart; // 
      }

            return null;

    }


    public function EnoughEquipmentChart($dataset, $filtersites,$monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load
     
    
      $chart = new SampleChart;
      $arrayMonth=array();
      $dataArraystronglyAgree= array();
      $dataArrayDisagree= array();
      $dataArrayStronglyDisagree= array();
      
      $dataArray=array();
     
      $label = ['បាទ/ចាស (%)','ទេ (%)','មិនដឹង (%)','អត់មាន/ មិនត្រូវការពាក់ស្រោមដៃពីរជាន់ (%)'];

      $reportQ="";

      for($i=0; $i <count($monthReport);$i++)
      {
           
            $stronglyAgree=0;
            $disagree=0;
            $stronglyDisagree=0;
           
            $total=0;

            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
               $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }

            foreach($dataset as $data)
            {
              
                $datevalue = date_format(date_create($data->START),'F-y');
            
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  
                  if ($data->E5 !=null)
                  {
                    if ($data->E5 == 1)
                    {
                      $stronglyAgree++;
                    }else if ($data->E5 == 2)
                    {
                      $disagree++;
                    }else if ($data->E5 == 3)
                    {
                      $stronglyDisagree++;
                    }
                    $total++;
                  }
          
                }// monthreport
            }// each data

            
             if($total>0)
              {
              

                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                  }

                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($stronglyAgree/$total) * 100 ,1)  ) ;
                  array_push($dataArraystronglyAgree,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($disagree/$total) * 100,1)  ) ;
                  array_push($dataArrayDisagree,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($stronglyDisagree/$total )* 100 ,1)  ) ;
                  array_push($dataArrayStronglyDisagree,$TotalCount);
                 
             
                  array_push($arrayMonth, $reportQ);
                
              }
            
      
      }// each month
       

         
      if (isset($stronglyAgree)>0)
      {
         
              //   $chart->labels($arrayMonth);
              $chart->dataset( trans("hfs.strongly_agree"). ' (%)', 'bar', array_column($dataArraystronglyAgree, 'data'))
              ->color($this->colorList[1])
              ->backgroundcolor($this->colorList[1]);

              $chart->dataset(trans("hfs.disagree").' (%)', 'bar', array_column($dataArrayDisagree, 'data'))
              ->color($this->colorList[2])
              ->backgroundcolor($this->colorList[2]);

              $chart->dataset(trans("hfs.strong_disagree").' (%)', 'bar', array_column($dataArrayStronglyDisagree, 'data'))
              ->color($this->colorList[3])
              ->backgroundcolor($this->colorList[3]);


       
           $chart->labels($arrayMonth);
          
           $title ="[\"".trans("hfs.chart7_title_1")."\", \"".trans("hfs.chart7_title_2")."\", \"".trans("hfs.chart7_title_3")."\"]";
            $chart->options(['indexAxis'=>'y',
              "plugins"=>"{
                legend:{
                  display:true,
                  position:'bottom',
                },
                title:{
                  position:'top',
                  text:".$title.",
                  display:true,
                  font:{
                    size:18,
                  },
                  color:'black'
                },
                datalabels:{
                  display:true,
                  font:{
                    size:15,
                  },
                  color:'white'
                }
              }"
                  
          ]);

          return $chart; // 
      }

            return null;

    }

    public function ServiceQualityChart($dataset, $filtersites,$monthReport,$isFiscalYear)
    {

      if (count($dataset)==0) return; // fixed error first load
     
    
      $chart = new SampleChart;
      $arrayMonth=array();
      $dataArrayVeryLow= array();
      $dataArrayLow= array();
      $dataArrayAverage= array();
      $dataArrayHigh=array();
      $dataArrayVeryHigh=array();
      
      $dataArray=array();
     
      $label = ['បាទ/ចាស (%)','ទេ (%)','មិនដឹង (%)','អត់មាន/ មិនត្រូវការពាក់ស្រោមដៃពីរជាន់ (%)'];


      $reportQ = "";
      for($i=0; $i <count($monthReport);$i++)
      {
           
            $veryLow=0;
            $low=0;
            $average=0;
            $high=0;
            $veryHigh=0;
           
            $total=0;

            if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear))
            {
               $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear);
            }

            foreach($dataset as $data)
            {
              
                $datevalue = date_format(date_create($data->START),'F-y');
            
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear) == $reportQ)
                {  
                  if ($data->E6 !=null)
                  {
                    if ($data->E6 == 1)
                    {
                      $veryLow++;
                    }else if ($data->E6 == 2)
                    {
                      $low++;
                    }else if ($data->E6 == 3)
                    {
                      $average++;
                    }else if ($data->E6 == 4)
                    {
                      $high++;
                    }else if ($data->E6 == 5)
                    {
                      $veryHigh++;
                    }
                    $total++;
                  }
          
                }// monthreport
            }// each data

            
             if($total>0)
              {
              
                if ( $i+1 < count($monthReport))
                {
                    if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear)) continue;
                }
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($veryLow/$total) * 100 ,1)  ) ;
                  array_push($dataArrayVeryLow,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($low/$total) * 100,1)  ) ;
                  array_push($dataArrayLow,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($average/$total )* 100 ,1)  ) ;
                  array_push($dataArrayAverage,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($high/$total )* 100 ,1)  ) ;
                  array_push($dataArrayHigh,$TotalCount);
                  $TotalCount =(object) array('label'=>$monthReport[$i],'data'=>"". round(($veryHigh/$total )* 100 ,1)  ) ;
                  array_push($dataArrayVeryHigh,$TotalCount);
                 
             
                  array_push($arrayMonth, $reportQ);
                
              }
            
      
      }// each month
       

         
      if ($total>0)
      {
         
          //   $chart->labels($arrayMonth);
          $chart->dataset(trans("hfs.quality_1").' (%)', 'bar', array_column($dataArrayVeryLow, 'data'))
          ->color($this->colorList[1])
          ->backgroundcolor($this->colorList[1]);

          $chart->dataset(trans("hfs.quality_2").' (%)', 'bar', array_column($dataArrayLow, 'data'))
          ->color($this->colorList[2])
          ->backgroundcolor($this->colorList[2]);

          $chart->dataset(trans("hfs.quality_3").' (%)', 'bar', array_column($dataArrayAverage, 'data'))
          ->color($this->colorList[3])
          ->backgroundcolor($this->colorList[3]);

          $chart->dataset(trans("hfs.quality_4").' (%)', 'bar', array_column($dataArrayHigh, 'data'))
          ->color($this->colorList[4])
          ->backgroundcolor($this->colorList[4]);

          $chart->dataset(trans("hfs.quality_5").' (%)', 'bar', array_column($dataArrayVeryHigh, 'data'))
          ->color($this->colorList[5])
          ->backgroundcolor($this->colorList[5]);


       
          $chart->labels($arrayMonth);
          
            $title ="[\"".trans("hfs.chart8_title_1")."\", \"".trans("hfs.chart8_title_2")."\", \"".trans("hfs.chart8_title_3")."\"]";
            $chart->options([
              "plugins"=>"{
                legend:{
                  display:true,
                  position:'bottom',
                },
                title:{
                  position:'top',
                  text:".$title.",
                  display:true,
                  font:{
                    size:18,
                  },
                  color:'black'
                },
                datalabels:{
                  display:true,
                  font:{
                    size:15,
                  },
                  color:'white'
                }
              }"
                  
          ]);
          return $chart; // 
      }

            return null;

    }

}//class
