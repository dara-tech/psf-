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



class Dashboard_V2Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     private $colorList = [

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

      //2020-10-01/ 2020-10-08
      $startdate= date("Y-m-d", strtotime("-1 months"));
      $enddate= date("Y-m-d");
  
     $sites = $this->getUserSites();

      
      $sites->prepend('*','*');

      $filtersites=$sites;
    
        return view ('Reports.DB_v2')->with('startdate',$startdate)
                                  ->with('enddate',$enddate)
                                  ->with('sites', $sites)

                                  ->with('filtersites',$filtersites);
    
    }


    private function getUserSites()
    {
      $role = Auth::user()->roles()->first();
      if ($role->id==1)
      {
        $sites = Site::get()->pluck('site', 'site');
      
      }else{
        $sites = Auth::user()->sites()->get()->pluck('site', 'site');
      
        
        if ($sites->get("*") == "*")
        {
          $sites = Site::get()->pluck('site', 'site');

          
        }
      }

      return $sites;
    

    }
   
    public function index(Request $request)
    {
      
    
      $platformchart = null;
      $satisfactorywithproviderchart = null;
      $participationchart = null;
      $satisfactorywithservicechart= null;
      $kpchart=null;
      
      $filtersites =[];

      $sites = $this->getUserSites();
     

      if (!(empty($request->startdate) && empty($request->endate)))
      {
     
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        
        
        //--check if all site selected
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
   

      }else{
       
        $startdate  = date('yyyy-mm-dd');
        $enddate  = date('yyyy-mm-dd');
      }/// end of filter check
       
    
      
      return view ('Reports.DB_v2')->with('startdate',$startdate)
      ->with('enddate',$enddate)
      ->with('sites', $sites)

      ->with('filtersites',$filtersites);

     
   
    }

    public function getDataSet($startdate, $enddate, $filtersites)
    {
   
      $data = DB::table('view_psf_patient_v4') 
                            ->select("*") 
                            ->wherein('site',$filtersites)
                            ->whereBetween('start',[$startdate,$enddate])
                           

                            ->get();

        return $data;
    }



    public function SummaryBySite_V2($dataset, $filtersites)
    {

      $data= array();

      $sites =  $dataset->pluck('site');

        foreach($filtersites as $filtersite)
        {
            $count=0;
             foreach($sites as $site)
             {
               if ($site == $filtersite)
               {
                  $count++;
               }
             }

             if ($count>0)
             {
                $TotalCount =(object) array('label'=>$filtersite,'data'=>"".$count);
                array_push($data,$TotalCount);
             }
            

         //$data->append($TotalCount);
        }

      //  return array_column($data, 'data');
        $chart = new SampleChart;
        $chart->labels(array_column($data, 'label'));
        $chart->dataset('Participation by Platforms', 'bar', array_column($data, 'data'))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Participation by Health Facilities");


            return $chart;

    }
   


    public function SummaryByPlatform_V2($dataset, $filtersites)
    {

        $data= array();

        $platforms =['ODK','Online'];
        $devices =  $dataset->pluck('META_INSTANCE_ID');

      
        $ODKCount=0;
        $OnlineCount =0;

       
        foreach($devices as $device)
        {
          if ($device == null)
          {
              $OnlineCount++;
          }else
          {
            $ODKCount++;
          }
        }

        $TotalCount =(object) array('label'=>'ODK','data'=>"".$ODKCount);
        array_push($data,$TotalCount);
        $TotalCount =(object) array('label'=>'Online','data'=>"".$OnlineCount);
        array_push($data,$TotalCount);
      
        $chart = new SampleChart;
        $chart->labels(array_column($data, 'label'));
        $chart->dataset('Participation by Platforms', 'bar',array_column($data, 'data'))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Participation by Platforms");


            return $chart;

      }
      
      public function SummaryParticipationChart_V2($dataset, $filtersites)
      {
  
          $data= array();
          $ACKNOWLEDGES =  $dataset->pluck('ACKNOWLEDGE');
  
        
          $AgreeCount=0;
          $DisagreeCount =0;
  
         
          foreach($ACKNOWLEDGES as $ACKNOWLEDGE)
          {
            if ($ACKNOWLEDGE == 1)
            {
                $AgreeCount++;
            }else
            {
              $DisagreeCount++;
            }
          }

          $TotalCount =(object) array('label'=>'Agreed','data'=>"".$AgreeCount);
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>'Disagreed','data'=>"".$DisagreeCount);
          array_push($data,$TotalCount);


        $chart = new SampleChart;
        $chart->labels(array_column($data, 'label'));
        $chart->dataset('Total Number of Participation', 'bar',array_column($data, 'data'))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);

            $chart->displayLegend(false);
            $chart->title("Total Number of Participation");

            return $chart;

      }

      public function SatisfactionWithProviderChart_V2($dataset,$filtersites)
      {
         $overall=0;
         $receptionist=0;
         $counselor=0;
         $doctor=0;
         $pharmacist=0;

         $overallSatisfaction=0;
         $receptionistSatisfaction=0;
         $counselorSatisfaction=0;
         $doctorSatisfaction=0;
         $pharmacistSatisfaction=0;


         foreach ($dataset as $data)
         {
              if($data->Q1A !=null) 
              {
                  $overall++;
                  if ($data->Q1A==3)
                  {
                    $overallSatisfaction++;
                  }
              }

              if($data->Q2A !=null) 
              {
                  $receptionist++;
                  if ($data->Q2A==3)
                  {
                    $receptionistSatisfaction++;
                  }
              }

              if($data->Q3A !=null) 
              {
                  $counselor++;
                  if ($data->Q3A==3)
                  {
                    $counselorSatisfaction++;
                  }
              }

              if($data->Q4A !=null) 
              {
                  $doctor++;
                  if ($data->Q4A==3)
                  {
                    $doctorSatisfaction++;
                  }
              }

              if($data->Q5A !=null) 
              {
                  $pharmacist++;
                  if ($data->Q5A==3)
                  {
                    $pharmacistSatisfaction++;
                  }
              }

        }

        if ($overall>0)
        {

          $data = array();

          $TotalCount =(object) array('label'=>'Overall','data'=>"".round($overallSatisfaction/$overall * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>'Receptionist','data'=>"".round($receptionistSatisfaction/$receptionist * 100 ) );
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>'Counselor','data'=>"".round($counselorSatisfaction/$counselor * 100 ));
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>'Doctor','data'=>"".round($doctorSatisfaction/$doctor * 100 ));
          array_push($data,$TotalCount);
          $TotalCount =(object) array('label'=>'Pharmacist','data'=>"".round($pharmacistSatisfaction/$pharmacist * 100 ));
          array_push($data,$TotalCount);



          $chart = new SampleChart;
          $chart->labels(array_column($data,'label'));
          $chart->dataset('Satisfaction with Provider (%)', 'bar', array_column($data,'data'))
              ->color($this->colorList)
              ->backgroundcolor($this->colorList);
          $chart->displayLegend(false);
          $chart->title("Satisfaction with Provider (%)");


          return $chart;
        }
        return null;
        
      }

    public function SummaryKPChart_V2($dataset,$filtersites)
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
           if($data->ACKNOWLEDGE==1)
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
            }elseif( (( $data->Q8C==1 && $data->Q9C_2==1) || ($data->Q8C==2 && $data->Q10C==0) )  &&  $data->Q11C==1 ) //male sex with female
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
            $chart->title("Participants by KP Status");


              return $chart;
        }
        return null;

    }
  

    public function SatisfactionWithServiceChart_V2($dataset,$filtersites)
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


        foreach( $dataset as $data)
        {

          if($data->ACKNOWLEDGE==1)
          {
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
            }
        }

        if ( $anc >0)

        {
              $data = array();

              $TotalCount =(object) array('label'=>'ANCs','data'=>"". round($ancSatisfaction/$anc *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'STIs','data'=>"". round($stiSatisfaction/$sti *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'LAB','data'=>"". round($labSatisfaction/$lab *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'TB','data'=>"".round($tbSatisfaction/$tb *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'Psycho-Counseling','data'=>"". round($psychoSatisfaction/$psycho *100));
              array_push($data,$TotalCount);
          

              $chart = new SampleChart;
              $chart->labels(array_column($data,'label'));
              $chart->dataset('Satisfaction with HIV-Related Services (%)', 'bar', array_column($data,'data'))
                  ->color($this->colorList)
                  ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Satisfaction with HIV-Related Services (%)");


                return $chart;
        }
        return null;

    }




    public function KeyIssuesChart_V2($dataset,$filtersites)
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


        foreach( $dataset as $data)
        {


             

        }

        if ( $anc >0)

        {
              $data = array();

              $TotalCount =(object) array('label'=>'ANCs','data'=>"". round($ancSatisfaction/$anc *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'STIs','data'=>"". round($stiSatisfaction/$sti *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'LAB','data'=>"". round($labSatisfaction/$lab *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'TB','data'=>"".round($tbSatisfaction/$tb *100));
              array_push($data,$TotalCount);
              $TotalCount =(object) array('label'=>'Psycho-Counseling','data'=>"". round($psychoSatisfaction/$psycho *100));
              array_push($data,$TotalCount);
          

              $chart = new SampleChart;
              $chart->labels(array_column($data,'label'));
              $chart->dataset('Satisfaction with HIV-Related Services (%)', 'bar', array_column($data,'data'))
                  ->color($this->colorList)
                  ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Satisfaction with HIV-Related Services (%)");


                return $chart;
        }
        return null;

    }





}
