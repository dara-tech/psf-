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

use App\Models\PatientPSF;
use App\Models\Site;
use App\Models\Objects\ProviderSatisfaction;


class Dashboard_V3Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     private $colorList = ['#003f5c','#374c80','#7a5195','#bc5090','#ef5675','#ff764a','#ffa600',
      '#8CBA90',
      '#669900','#336600',
       
      '#D2E4D4',
      '#CAE1FF','#C9AF94',
      '#E2DDB5','#E0EEEE',
      '#F3E88E','#F4A460',
      '#B7C8B6','#B6AFA9',
      '#EEDC82','#EEB4B4',
      '#F0A804','#EEE8CD',
      '#C5C1AA','#C0FF3E',
      '#D0A9AA','#CFB53B',
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

    public $locale ="kh";
    public function __construct()
    {
     //   $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

     

    public function home()
    {
      
      $role = Auth::user()->roles()->first();
      $platformchart = null;
      $satisfactorywithproviderchart = null;
      $participationchart = null;
      $satisfactorywithservicechart= null;
      $kpchart=null;
      $filtersites ="";

     
     $sites = $this->getUserSites();
   
     $filtersites=$sites;
    
      $provinces=[];
      $filterKp="";
      $filterProvince="";
      $filterAge="";
  
      return view ('Reports.DB_v3')
                                  ->with('sites', $sites)
                                  ->with ('provinces',$this->getProvinces())
                                  ->with('kps',$this->getKPs())
                                  ->with('ages',$this->getAgeRanges())
                                  ->with('filterKp',$filterKp)
                                  ->with('filterProvince',$filterProvince)
                                  ->with('filterAge',$filterAge)
                                  ->with('filtersites',$filtersites)
                                  ->with('allsites', $this->getAllSitesAndProvinces())
                                  ->with('filterPeriod',null)
                                  ->with('isFiscalYear', false)
                                  ->with ('period', $this->getListPeriodReport())
                                  ->with ('byMonth', false);
    }


    public function getProvinces()
    {
      return BaseController::getProvinces();
    }
    private function getKPs()
    {
      
      return BaseController::getKPs();
    }

    private function getAgeRanges()
    {
      return BaseController::getAgeRanges();
    }
   
    private function getUserSites()
    {
       return BaseController::getUserSites();
    

    }

    private function getAllSitesAndProvinces()
    {
      return BaseController::getAllSitesAndProvinces();
    }
   
    public function index(Request $request)
    {
      
    
      $platformchart = null;
      $satisfactorywithproviderchart = null;
      $participationchart = null;
      $satisfactorywithservicechart= null;
      $kpchart=null;
      
      $isFiscalYear= ($request->period!=null)? $request->isFiscalYear:false;
     
      //return "isfyear ". $isFiscalYear . " - " . $request->isFiscalYear;
      $filtersites =[];

      $sites = $this->getUserSites();
     
      if ($request->period !=null)
      {
        //--check if all site selected
        if ($request->sites !=null)
        {
            $allsite = array_filter($request->sites,function($site)
            {
                return ($site === "*");
            });
            // end check all site selected

            if (sizeof($allsite)==0)
            {
              $filtersites = $request->sites;
            }else{
              $filtersites = $sites;
            }
        }else
        {
          $filtersites = $sites;
        
        }
   


      }/// end of filter check
       

      $filterKp = ($request->kps !=null)?$this->getKPs()[$request->kps[0]]:"";
      $filterProvince = ($request->provinces !=null)?$this->getProvinces()[$request->provinces[0]]:[];
      $filterAge = ($request->ages !=null)?$this->getAgeRanges()[$request->ages[0]]:"";
      
      return view ('Reports.DB_V3')
      ->with('sites', $sites)
      ->with ('provinces',$this->getProvinces())
      ->with('kps',$this->getKPs())
      ->with('ages',$this->getAgeRanges())
      ->with('filterKp',$filterKp)
      ->with('filterProvince',$filterProvince)
      ->with('filterAge',$filterAge)
      ->with('filtersites',$filtersites)
      ->with('allsites', $this->getAllSitesAndProvinces())
      ->with('filterPeriod',$request->period)
      ->with('isFiscalYear',$isFiscalYear)
      ->with ('period', $this->getListPeriodReport())
      ->with ('byMonth', $request->byMonth);
     

      
    }
    public function getMonthReport($startdate, $enddate)
    {

       return BaseController:: getMonthReport($startdate, $enddate);
    }

    private function getListPeriodReport()
    {
      return BaseController::getListPeriodReport();
    }

    private function covertDate2Quarter($d, $isFiscalYear, $byMonth=false)
    {
        return BaseController::covertDate2Quarter($d, $isFiscalYear, $byMonth);
    }

    
    public function getReportMonthByQuarter($period, $isFiscalYear)
    {
        return BaseController::getReportMonthByQuarter($period, $isFiscalYear);
    }

    public function getDataSet($period,$isFiscalYear,$filtersites,$filterKp,$filterProvince,$filterAge)
    {

      if ($period == null) return null; // fix error

      $where = [];

      $reportMonths = $this->getReportMonthByQuarter( $period,$isFiscalYear);
      

      if ($filterKp !=null)
      {
       
          $kp ="";
          if ($filterKp =='បុរសស្រឡាញ់បុរស (MSM)'|| $filterKp=="MSM")
          {
            $kp = "MSM";
          }else if ($filterKp =='ក្រុមប្លែងភេទ (TG)' || $filterKp=="TG")
          {
            $kp = "TG";
          }else if ($filterKp =='ស្រ្តីបំរើសេវាកំសាន្ត (EW)' || $filterKp=="EW")
          {
            $kp = "FEW";
          }else if ($filterKp =='ប្រជាជនទូទៅ (GP)' || $filterKp=="GP")
          {
            $kp = "GP";
          }else if ($filterKp =='អ្នកប្រើប្រាស់គ្រឿងញៀណ (PWID)' || $filterKp=="PWID")
          {
            $kp = "PWID";
          }else if ($filterKp =='មិនអាចកំណត់បាន' || $filterKp=="UNDEFINED")
          {
            $kp = "INDET";
          }

          array_push ($where,["KP", "=", $kp]);
      }
     
          if ($filterAge !=null)
          {
            $age=0;

            if ($filterAge == 'តិច​ជាង ១៨ ឆ្នាំ' || $filterAge =="< 18") 
            {
                $age =1;
            }else if ($filterAge == '១៩ ដល់​  ២៤ ​ឆ្នាំ' || $filterAge =="19 to​ 24")
            {
                $age =2;
            }else if ($filterAge == '២៥ ដល់ ៣៥ ឆ្នំា' || $filterAge =="25 to 35")
            {
                $age =3;
            }else if ($filterAge == '៣៦ ដល់ ៤៥ ឆ្នាំ' || $filterAge =="36 to 45")
            {
                $age =4;
            }else if ($filterAge == 'អាយុច្រើនជាង ៤៥ ឆ្នាំ' || $filterAge =="> 45")
            {
                $age =5;
            }
          
            array_push ($where,["Q7C", "=", $age]);
          }


          $locale = session()->get('locale');
          if ($locale == "en")
          {
            $site = "sitename";
            $province = "province";
          }else{
            $site = "site";
            $province = "province_kh";
          }
    
          
           if ($filterProvince !=null && $filterProvince !="")
           {
               array_push ($where,[$province, "=", $filterProvince]);
           }

           if ($filtersites !=null && $filtersites !="")
           {
              // $data = DB::table('view_psf_patient_v4')
              $data = DB::table('tbl_psf_patient_v4') 
              ->select("*") 
              ->where($where)
              ->wherein('month', $reportMonths)
              ->wherein($site,$filtersites)
              ->orderBy('start')
              ->get();


           }else
           {
                // $data = DB::table('view_psf_patient_v4')
                $data = DB::table('tbl_psf_patient_v4') 
                ->select("*") 
                ->wherein('month', $reportMonths)
                ->where($where)
                ->orderBy('start')
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
            
              foreach($dataset as $data)
              {
                $datevalue = date_format(date_create($data->START),'F-y');
        
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
                {    
                    $total++;
                    if ($data->ACKNOWLEDGE == 1)
                    {
                        $AgreeCount++;
                    }else
                    {
                      $DisagreeCount++;
                    }
                }
              }
          
              if ( ($total>0))
              {
                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
                  }

                
                  $TotalCount =(object) array('label'=>'ចូលរួម','data'=>"".$AgreeCount);
                  array_push($agreed,$TotalCount);
                  
                  $chart->dataset("ចូលរួម", 'bar',array_column($agreed, 'data'))
                  ->color($this->colorList[1])
                  ->backgroundcolor($this->colorList[1]);

                  $TotalCount =(object) array('label'=>'មិនចូលរួម','data'=>"".$DisagreeCount);
                  array_push($disagreed,$TotalCount);
                
                  $chart->dataset("មិនចូលរួម", 'bar',array_column($disagreed, 'data'))
                      ->color($this->colorList[2])
                      ->backgroundcolor($this->colorList[2]);

                      $chart->labels($QList);
            
              }
          }
        
        $chart->displayLegend(false);
        $chart->title("ចំនួនអ្នកចូលរួម",24,'black');
        $chart->options(['legend'=>['display'=>true, 'position'=>'bottom','labels'=>['fontColor'=>'black']  ],
                          'tooltips' => ['enabled'=>true],
                          'scales'=> ['yAxes'=>[['stacked'=>true],['ticks'=>['fontColor'=>'black','display'=>false]]],'xAxes'=>[['stacked'=>true],['ticks'=>['fontColor'=>'black','display'=>false]]]],
                          'plugins' => '{datalabels:{ display:true, color:\'red\' }} ', 
                                             
                          ]);


                          
         
     return $chart;

      }
      

      public function SummaryParticipationByPlatformChartByQ_V3($dataset, $filtersites, $monthReport,$isFiscalYear,$byMonth=false)
      {
  
          $data= array();
        
          $ODKCount=0;
          $OnlineCount =0;
          $chart = new SampleChart;
          $reportQ = "";
         
          $QList = array();
          $onlineArr=array();
          $odkArr=array();

        
          $title = trans("patient.chart1_title");
          $label =  trans("patient.chart1_legend1");
        
          for($i=0; $i <count($monthReport);$i++) 
          {
  
              if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
              {
                $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);
                array_push($QList,$reportQ);
              }
    
              $total=0;
              $ODKCount=0;
              $OnlineCount =0;
      
             
              foreach($dataset as $data)
              {
                $datevalue = date_format(date_create($data->START),'F-y');
        
                if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
                {  
                  $total++;
                  if ($data->ACKNOWLEDGE == 1)
                  {

                    if ($data->META_INSTANCE_ID != null)
                    {
                        $ODKCount++;
                    }else                    
                    {
                        $OnlineCount++;  
                    }
                  }
                 
                }
              }
       
              if ( $total>=0)
              {
                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
                  }

                
                  $TotalCount =(object) array('label'=>$label.' QR Code','data'=>"".$OnlineCount);
                  array_push($onlineArr,$TotalCount);
                  
               

                  $TotalCount =(object) array('label'=>$label.' Tablet','data'=>"".$ODKCount);
                  array_push($odkArr,$TotalCount);
                
                  
                 
            
              }
          }
       
      
          $chart->dataset($label." Tablet", 'bar',array_column($odkArr, 'data'))
          ->color($this->colorList[2])
          ->backgroundcolor($this->colorList[2]);

          $chart->dataset($label." QR Code", 'bar',array_column($onlineArr, 'data'))
          ->color($this->colorList[5])
          ->backgroundcolor($this->colorList[5]);
      
       
          $chart->labels($QList);
       
        
        $chart->displayLegend(false);
        $chart->title($title,24,'black');
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
                size:25,
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
      

      public function SatisfactionWithProviderChart_V3($dataset,$filtersites, $monthReport, $isFiscalYear,$byMonth=false)
      {

        $data;
        $chart = new SampleChart;
        $reportQ='' ;
        $total=0;

       
        for($i=0; $i <count($monthReport);$i++) 
        {

          if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
          {
             $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);
             $providerByMonth = new ProviderSatisfaction;
          }

          $total=0;
          foreach ($dataset as $data)
          {
            $datevalue = date_format(date_create($data->START),'F-y');
           
         
            if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
            {    
              $total++;
                if($data->Q1A !=null) 
                {
                  $providerByMonth->overall++;
                    if ($data->Q1A==3)
                    {
                      $providerByMonth->overallSatisfaction++;
                    }
                }

                if($data->Q2A !=null) 
                {
                  $providerByMonth->receptionist++;
                    if ($data->Q2A==3)
                    {
                      $providerByMonth->receptionistSatisfaction++;
                    }
                }

                if($data->Q3A !=null) 
                {
                  $providerByMonth->counselor++;
                    if ($data->Q3A==3)
                    {
                      $providerByMonth->counselorSatisfaction++;
                    }
                }

                if($data->Q4A !=null) 
                {
                  $providerByMonth->doctor++;
                    if ($data->Q4A==3)
                    {
                      $providerByMonth->doctorSatisfaction++;
                    }
                }

                if($data->Q5A !=null) 
                {
                  $providerByMonth->pharmacist++;
                    if ($data->Q5A==3)
                    {
                      $providerByMonth->pharmacistSatisfaction++;
                    }
                }

            }
          }

          if (count($dataset) > 0)
          {

            if ( $i+1 < count($monthReport))
            {
                if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
            }



            $providerByMonth->overall = ($providerByMonth->overall ==0) ? 1 : $providerByMonth->overall;
            $providerByMonth->receptionist = ($providerByMonth->receptionist ==0) ? 1 : $providerByMonth->receptionist;
            $providerByMonth->counselor = ($providerByMonth->counselor ==0)? 1 : $providerByMonth->counselor;
            $providerByMonth->doctor = ($providerByMonth->doctor ==0)? 1 : $providerByMonth->doctor;
            $providerByMonth->pharmacist = ($providerByMonth->pharmacist ==0)? 1 : $providerByMonth->pharmacist;

            $data = array();

            $TotalCount =(object) array('label'=>trans("patient.chart2_legend1"),'data'=>"".round($providerByMonth->overallSatisfaction/$providerByMonth->overall * 100 ) );
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>trans("patient.chart2_legend2"),'data'=>"".round($providerByMonth->receptionistSatisfaction/$providerByMonth->receptionist * 100 ) );
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>trans("patient.chart2_legend3"),'data'=>"".round($providerByMonth->counselorSatisfaction/$providerByMonth->counselor * 100 ));
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>trans("patient.chart2_legend4"),'data'=>"".round($providerByMonth->doctorSatisfaction/$providerByMonth->doctor * 100 ));
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>trans("patient.chart2_legend5"),'data'=>"".round($providerByMonth->pharmacistSatisfaction/$providerByMonth->pharmacist * 100 ));
            array_push($data,$TotalCount);


            $chart->labels(array_column($data,'label'));
            $chart->dataset($reportQ , 'bar', array_column($data,'data'))
                ->color($this->colorList[$i])
                ->backgroundcolor($this->colorList[$i]);
         
          }

        }
        

        if (is_array($data))
        {
          $title =trans("patient.chart2_title");
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
                  size:25,
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
          $chart->title(trans("patient.chart2_title"),24,'black');    

          return $chart;
        }else
        {
          return null;
        }
          
       
      }

    public function SummaryKPChart_V3($dataset,$filtersites)
    {

        $numofTG=0;
        $numofMSM=0;
        $numofFEW=0;
        $numofPWID=0;
        $numofGP=0;
        $numofIndet =0;

        $total=0;

        foreach ($dataset as $data)
        {

            if ( $data->Q8C ==3 && $data->Q11C==0) //self-idenfied as tg & no use drug
            {
              $numofTG++;
            }elseif( $data->Q8C==1 && $data->Q9C_1==1 and $data->Q11C==0) // male + sex with male + no use drug
            {
              $numofMSM++;
            }elseif ($data->Q8C==2 && $data->Q10C==1 && $data->Q11C==0) //female + sex exchange for money + no use drug
            {
               $numofFEW++;
            }elseif( (( $data->Q8C==1 && $data->Q9C_2==1) || ($data->Q8C==2 && $data->Q10C==0) )  &&  $data->Q11C==1 ) //pwid
            {
                $numofPWID++;
            }elseif ( (($data->Q8C==1 && $data->Q9C_2==1) || ($data->Q8C==2 && $data->Q10C==0) ) && $data->Q11C==0) // male sex with female + female no sex in exchange for money + No drug
            {
              $numofGP++;
            }else{

              $numofIndet++;
            }
            $total++;
        }


        if ( $total >0)
        {
            $data = array();

            $TotalCount =(object) array('label'=>'MSM','data'=>"".$numofMSM);
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>'TG','data'=>"".$numofTG);
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>'EW','data'=>"".$numofFEW);
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>'PWID','data'=>"".$numofPWID);
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>'GP','data'=>"".$numofGP);
            array_push($data,$TotalCount);
            $TotalCount =(object) array('label'=>'INDET','data'=>"".$numofIndet);
            array_push($data,$TotalCount);


            $chart = new SampleChart;
            $chart->labels(array_column($data,'label'));
            $chart->dataset('Participants by KP Status', 'bar', array_column($data,'data'))
                ->color($this->colorList)
                ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);

            $chart->options(['legend'=>['display'=>true, 'position'=>'bottom','labels'=>['fontColor'=>'black']  ],
            'scales'=> ['yAxes'=>[['ticks'=>['fontColor'=>'black']]],'xAxes'=>[['ticks'=>['fontColor'=>'black']]]]
          ]);
          
            $chart->title("Participants by KP Status",14,'black');


              return $chart;
        }
        return null;

    }
  

    public function SatisfactionWithServiceChart_V3($dataset,$filtersites, $monthReport,$isFiscalYear,$byMonth=false)
    {
        $anc=0;
        $sti=0;
        $tb=0;
        $lab=0;
        $psycho=0;

        $ancSatisfaction=0;
        $stiSatisfaction=0;
        $tbSatisfaction=0;
        $labSatisfaction=0;
        $psychoSatisfaction=0;
        $total=0;

        
        $chart = new SampleChart;

        $reportQ = "";
        for($i=0; $i <count($monthReport);$i++) 
        {
          	
              if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
              {
                $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);
                $anc=0;
                $sti=0;
                $tb=0;
                $lab=0;
                $psycho=0;
        
                $ancSatisfaction=0;
                $stiSatisfaction=0;
                $tbSatisfaction=0;
                $labSatisfaction=0;
                $psychoSatisfaction=0;
              }

             foreach( $dataset as $data)
             {
                     $datevalue = date_format(date_create($data->START),'F-y');
           
                  
                     if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
                      { 
                        $total++;

                                  if($data->Q1B !=4) 
                                  {
                                      $anc++;
                                      if ($data->Q1B==3)
                                      {
                                        $ancSatisfaction++;
                                      }
                                  }

                                  if($data->Q2B !=4) 
                                  {
                                      $sti++;
                                      if ($data->Q2B==3)
                                      {
                                        $stiSatisfaction++;
                                      }
                                  }

                                  if($data->Q3B !=4) 
                                  {
                                      $lab++;
                                      if ($data->Q3B==3)
                                      {
                                        $labSatisfaction++;
                                      }
                                  }

                                  if($data->Q4B !=4) 
                                  {
                                      $tb++;
                                      if ($data->Q4B==3)
                                      {
                                        $tbSatisfaction++;
                                      }
                                  }

                                  if($data->Q5B !=4) 
                                  {
                                      $psycho++;
                                      if ($data->Q5B==3)
                                      {
                                        $psychoSatisfaction++;
                                      }
                                  }

                      }//is date
                }//each dataset




                if ( $total >0)
                {

                      if ( $i+1 < count($monthReport))
                      {
                          if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
                      }


                      $anc=($anc==0)? 1 : $anc;
                      $sti= ($sti ==0)? 1 : $sti;
                      $tb= ($tb==0)? 1 : $tb;
                      $lab= ($lab==0)? 1 : $lab;
                      $psycho= ($psycho==0)?1:$psycho;

                      $data = array();
                      $TotalCount =(object) array('label'=>[trans('patient.chart4_legend1_1'),trans('patient.chart4_legend1_2')],'data'=>"". round($ancSatisfaction/$anc *100));
                      array_push($data,$TotalCount);
                      $TotalCount =(object) array('label'=>[trans('patient.chart4_legend2')],'data'=>"". round($stiSatisfaction/$sti *100));
                      array_push($data,$TotalCount);
                      $TotalCount =(object) array('label'=>[trans('patient.chart4_legend3')],'data'=>"". round($labSatisfaction/$lab *100));
                      array_push($data,$TotalCount);
                      $TotalCount =(object) array('label'=>[trans('patient.chart4_legend4')] ,'data'=>"".round($tbSatisfaction/$tb *100));
                      array_push($data,$TotalCount);
                      $TotalCount =(object) array('label'=>[trans('patient.chart4_legend5')],'data'=>"". round($psychoSatisfaction/$psycho *100));
                      array_push($data,$TotalCount);
                  
        
                    
                      $chart->dataset($reportQ, 'bar', array_column($data,'data'))
                          ->color($this->colorList[$i])
                          ->backgroundcolor($this->colorList[$i]);
                   
                }
        }// month report

        if (is_array($data))
        {
          $chart->labels(array_column($data,'label'));
          $chart->displayLegend(false);
          $chart->title(trans('patient.chart4_title'),24,'black');
          $title =trans("patient.chart4_title");
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
                  size:25,
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
        }else
        {
          return null;
        }

    }


    public function PatientSatisfaction_V3($dataset, $filtersites, $monthReport,$isFiscalYear,$byMonth=false)
    {

        $waitingTime=0;
        $workingHours=0;
        $equalCounseling=0;
        $staffGossip=0;
        $privacy=0;
      
        $waitingTimeSatisfaction=0;
        $workingHoursSatisfaction=0;
        $equalCounselingSatisfaction=0;
        $staffGossipSatisfaction=0;
        $privacySatisfaction=0;

        $chart = new SampleChart;

        $reportQ = "";


        for($i=0; $i <count($monthReport);$i++) 
        {
              if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
              {
                $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);
                
                $waitingTime=0;
                $workingHours=0;
                $equalCounseling=0;
                $staffGossip=0;
                $privacy=0;
              
                $waitingTimeSatisfaction=0;
                $workingHoursSatisfaction=0;
                $equalCounselingSatisfaction=0;
                $staffGossipSatisfaction=0;
                $privacySatisfaction=0;
              }
                  

             foreach( $dataset as $data)
             {
                     $datevalue = date_format(date_create($data->START),'F-y');
           
                  
                      if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) ==  $reportQ)
                      { 


                          if ($data->Q6A != null)
                          {
                            $waitingTime++;
                            if($data->Q6A == 1)
                            { $waitingTimeSatisfaction++ ;}
                            
                          }
              
                          if ($data->Q7A != null)
                          {
                            $workingHours++;
                            if($data->Q7A == 1)
                            { $workingHoursSatisfaction++ ;}
                            
                          }
              
                          if ($data->Q8A != null)
                          {
                            $equalCounseling++;
                            if($data->Q8A == 1)
                            { $equalCounselingSatisfaction++ ;}
                            
                          }
                          
                          if ($data->Q9A != null)
                          {
                            $staffGossip++;
                            if($data->Q9A == 1)
                            { $staffGossipSatisfaction++ ;}
                            
                          }
              
                            
                          if ($data->Q10A != null)
                          {
                            $privacy++;
                            if($data->Q10A == 1)
                            { $privacySatisfaction++ ;}
                            
                          }
              
                      }// is date

            }//each dataset
              
              if (count($dataset) >0)
              {

                    if ( $i+1 < count($monthReport))
                    {
                        if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
                    }
      

                  $waitingTime= ($waitingTime ==0 ) ? 1 : $waitingTime;
                  $workingHours= ($workingHours ==0)? 1 : $workingHours ;
                  $equalCounseling= ($equalCounseling ==0)? 1: $equalCounseling;
                  $staffGossip= ($staffGossip==0)? 1 : $staffGossip;
                  $privacy= ($privacy==0)?1 : $privacy;

                  $data = array();
      
                  $TotalCount =(object) array('label'=>[trans("patient.chart3_legend1_1"),trans("patient.chart3_legend1_2")],'data'=>"".round($waitingTimeSatisfaction/$waitingTime * 100 ) );
                  array_push($data,$TotalCount);
                  $TotalCount =(object) array('label'=>[trans("patient.chart3_legend2_1"),trans("patient.chart3_legend2_2")],'data'=>"".round($workingHoursSatisfaction/$workingHours * 100 ) );
                  array_push($data,$TotalCount);
                  $TotalCount =(object) array('label'=>[trans("patient.chart3_legend3_1"),trans("patient.chart3_legend3_2")],'data'=>"".round($equalCounselingSatisfaction/$equalCounseling * 100 ));
                  array_push($data,$TotalCount);
               //   $TotalCount =(object) array('label'=>[trans("patient.chart3_legend4_1"),trans("patient.chart3_legend4_2")],'data'=>"".round($staffGossipSatisfaction/$staffGossip * 100 ));
                 // array_push($data,$TotalCount);
                  $TotalCount =(object) array('label'=>[trans("patient.chart3_legend5_1"),trans("patient.chart3_legend5_2"),trans("patient.chart3_legend5_3")],'data'=>"".round($privacySatisfaction/$privacy * 100 ));
                  array_push($data,$TotalCount);
                 
                  $chart->dataset($reportQ, 'bar', array_column($data,'data'))
                      ->color($this->colorList[$i])
                      ->backgroundcolor($this->colorList[$i]);
                
              }

        
        }// each report monthg
        
        if (is_array($data))
        {
       
          $chart->labels(array_column($data,'label'));
          $chart->displayLegend(false);
          $chart->title(trans("patient.chart3_title"),24,'black');
          $title =trans("patient.chart3_title");
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
                  size:25,
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
        }else
        {
          return null;
        }
    
    }
    

    public function ProviderAttitudeChart_V3($dataset, $filtersites, $monthReport,$isFiscalYear,$byMonth=false)
    {
        $feelSafe=0;
        $feelRespected=0;
        $EasyInfo=0;

        $feelSafeSatisfaction=0;
        $feelRespectedSatisfaction=0;
        $EasyInfoSatisfaction=0;

        $chart = new SampleChart;

        $reportQ="";

        for($i=0; $i <count($monthReport);$i++) 
        {
              if ($reportQ != $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth))
              {
                $reportQ = $this->covertDate2Quarter($monthReport[$i],$isFiscalYear,$byMonth);

                
                  $feelSafe=0;
                  $feelRespected=0;
                  $EasyInfo=0;

                  $feelSafeSatisfaction=0;
                  $feelRespectedSatisfaction=0;
                  $EasyInfoSatisfaction=0;
              }
            
            
             foreach( $dataset as $data)
             {
                     $datevalue = date_format(date_create($data->START),'F-y');
           
                  
                    if ($this->covertDate2Quarter($datevalue,$isFiscalYear,$byMonth) == $reportQ)
                    { 

                          if ($data->Q5C1 !=null)
                          {
                            $feelSafe++;

                            if ($data->Q5C1 ==1)
                            {
                              $feelSafeSatisfaction++;
                            }
                          }
                      
                          if ($data->Q5C2 !=null)
                          {
                            $feelRespected++;

                            if ($data->Q5C2 ==1)
                            {
                              $feelRespectedSatisfaction++;
                            }
                          }
                      
                          if ($data->Q5C3 !=null)
                          {
                            $EasyInfo++;

                            if ($data->Q5C3 ==1)
                            {
                              $EasyInfoSatisfaction++;
                            }
                          }
                      }// is datea

                }//each dataset



                if (count($dataset) >0)
                {

                  if ( $i+1 < count($monthReport))
                  {
                      if( $reportQ == $this->covertDate2Quarter($monthReport[$i+1],$isFiscalYear,$byMonth)) continue;
                  }
        

                  
                  $feelSafe=($feelSafe ==0 )? 1 : $feelSafe;
                  $feelRespected= ($feelRespected==0) ? 1 : $feelRespected;
                  $EasyInfo= ($EasyInfo ==0 ) ? 1 : $EasyInfo;


                  $data = array();
        
                  $TotalCount =(object) array('label'=>[trans("patient.chart5_legend1_1"),trans("patient.chart5_legend1_2")],'data'=>"".round($feelSafeSatisfaction/$feelSafe * 100 ) );
                  array_push($data,$TotalCount);
                  $TotalCount =(object) array('label'=>trans("patient.chart5_legend2"),'data'=>"".round($feelRespectedSatisfaction/$feelRespected * 100 ) );
                  array_push($data,$TotalCount);
                  $TotalCount =(object) array('label'=>[trans("patient.chart5_legend3_1"),trans("patient.chart5_legend3_2"),trans("patient.chart5_legend3_3")],'data'=>"".round($EasyInfoSatisfaction/$EasyInfo * 100 ));
                  array_push($data,$TotalCount);
          
                  $chart->labels(array_column($data,'label'));
                  $chart->type('bar');
                  $chart->dataset($reportQ, 'bar', array_column($data,'data'))
                      ->color($this->colorList[$i])
                      ->backgroundcolor($this->colorList[$i]);
                
                }

        }// monthreport

      
        if (is_array($data))
        {
          $chart->displayLegend(false);
          $chart->title(trans("patient.chart5_title"),24,'black');
          $title =trans("patient.chart2_title");
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
                  size:25,
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
        }else
        {
          return null;
        }

    }

    public function PatientComments_V3($dataset, $filtersites, $monthReport)
    {
        $reduceWaitingtime=0; //2
        $moreFriendlyProvider=0;//3
        $staffPresent=0;//4
        $staffFocusOnPatient=0;//5
        $cleanWaitingRoom=0;//6
        $cleanToilet=0;//7
        $serviceEvery6month=0;//8

        $total=0;


        foreach( $dataset as $data)
        {
            if ($data->Q6C_1 ==0) // 1 means no comment
            {
              $total++;

              if ($data->Q6C_2 == 1)
              {
                $reduceWaitingtime++;
              }if ($data->Q6C_3 == 1)
              {
                $moreFriendlyProvider++;
              }if ($data->Q6C_4 == 1)
              {
                $staffPresent++;
              }elseif ($data->Q6C_5 == 1)
              {
                $staffFocusOnPatient++;
              }if ($data->Q6C_6 == 1)
              {
                $cleanWaitingRoom++;
              }if ($data->Q6C_7 == 1)
              {
                $cleanToilet++;
              }if ($data->Q6C_8 == 1)
              {
                $serviceEvery6month++;
              }

            }

        }//each dateaset

        if ($total >0)
        {

          $data = array();

          $TotalCount =(object) array('label'=>trans("patient.chart6_legend1"),'data'=>"".round($reduceWaitingtime/$total * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend2_1"),trans("patient.chart6_legend2_2")],'data'=>"".round($moreFriendlyProvider/$total * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend3_1"),trans("patient.chart6_legend3_2")],'data'=>"".round($staffPresent/$total * 100 ));
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend4_1"),trans("patient.chart6_legend4_2")],'data'=>"".round($staffFocusOnPatient/$total * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend5_1"), trans("patient.chart6_legend5_2")],'data'=>"".round($cleanWaitingRoom/$total * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend6_1"),trans("patient.chart6_legend6_2")],'data'=>"".round($cleanToilet/$total * 100 ));
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>[trans("patient.chart6_legend7_1"),trans("patient.chart6_legend7_2")],'data'=>"".round($serviceEvery6month/$total * 100 ));
          array_push($data,$TotalCount);
         


          $chart = new SampleChart;
          $chart->labels(array_column($data,'label'));
          $chart->dataset(trans("patient.chart6_title"), 'bar', array_column($data,'data'))
              ->color($this->colorList[0])
              ->backgroundcolor($this->colorList[0]);
          $chart->displayLegend(false);
          $chart->title(trans("patient.chart6_title"),24,'black');
          $title =trans("patient.chart6_title");
          $chart->options(['indexAxis'=>'y',
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
                  size:25,
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

        return null;

    }



    
}
