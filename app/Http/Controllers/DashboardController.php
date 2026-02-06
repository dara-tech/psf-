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



class DashboardController extends Controller
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

      
      $sites->prepend('*','*');

      $filtersites=$sites;

       //--- only allowed only when filter Chart Generator
  /*     $platformchart = $this->SummaryByPlatform($startdate,$enddate,$filtersites);
       $satisfactorywithproviderchart = $this->SatisfactionWithProviderChart($startdate,$enddate,$filtersites);
       $participationchart = $this->SummaryParticipationChart($startdate,$enddate,$filtersites);
       $kpchart = $this->SummaryKPChart($startdate,$enddate,$filtersites);
       $satisfactorywithservicechart = $this->SatisfactionWithServiceChart($startdate,$enddate,$filtersites);
*/
     
   //   return view('Reports.dashboard', compact('platformchart','satisfactorywithproviderchart','participationchart','kpchart','satisfactorywithservicechart','sites'));     
     
        return view ('Reports.DB')->with('startdate',$startdate)
                                  ->with('enddate',$enddate)
                                  ->with('sites', $sites)

                                  ->with('filtersites',$filtersites);
    
    }

   
    public function index(Request $request)
    {

     
      
      
      $role = Auth::user()->roles()->first();
      $platformchart = null;
      $satisfactorywithproviderchart = null;
      $participationchart = null;
      $satisfactorywithservicechart= null;
      $kpchart=null;



      if ($role->id==1)
      {
        $sites = Site::get()->pluck('site', 'site');
      

      }else{
        $sites = Auth::user()->sites()->get()->pluck('site', 'site');
       
      }

      
      $sites->prepend('*','*');
        //2020-10-01/ 2020-10-08
        $startdate= date("Y-m-d", strtotime("-1 months"));
        $enddate= date("Y-m-d");
     
        $filtersites =[];

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
   
        
        //--- only allowed only when filter Chart Generator
     /*   $platformchart = $this->SummaryByPlatform($startdate,$enddate,$filtersites);
        $satisfactorywithproviderchart = $this->SatisfactionWithProviderChart($startdate,$enddate,$filtersites);
        $participationchart = $this->SummaryParticipationChart($startdate,$enddate,$filtersites);
        $kpchart = $this->SummaryKPChart($startdate,$enddate,$filtersites);
        $satisfactorywithservicechart = $this->SatisfactionWithServiceChart($startdate,$enddate,$filtersites);

     */


      }/// end of filter check
       
      
      // return view('Reports.dashboard', compact('platformchart','satisfactorywithproviderchart','participationchart','kpchart','satisfactorywithservicechart','sites'));     
      
      return view ('Reports.DB')->with('startdate',$startdate)
      ->with('enddate',$enddate)
      ->with('sites', $sites)
      ->with('filtersites',$filtersites);
    }


    public function SummaryBySite($startdate, $enddate, $filtersites)
    {
   
      $data = DB::table('view_psf_patient_v4')
                            ->select([
                                DB::raw('count(*) as data, 
                                    site  
                                    '),
                            ])
                            ->wherein('site',$filtersites)
                            ->whereBetween('start',[$startdate,$enddate])
                            ->groupBy('site')

                            ->get();

       

        $chart = new SampleChart;
        $chart->labels($data->pluck("site"));
        $chart->dataset('Participation by Platforms', 'bar', $data->pluck("data"))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Participation by Health Facilities");


            return $chart;
    }


    public function SummaryByPlatform($startdate, $enddate, $filtersites)
    {
   
      $data = DB::table('view_psf_patient_v4')
                            ->select([
                                DB::raw('count(*) as data, 

                                case 
                                when META_INSTANCE_ID is null then "Online"
                                else "ODK"
                                end as label'),
                            ])
                            ->wherein('site',$filtersites)
                            ->whereBetween('start',[$startdate,$enddate])
                            ->groupBy('label')

                            ->get();

        $chart = new SampleChart;
        $chart->labels($data->pluck("label"));
        $chart->dataset('Participation by Platforms', 'bar', $data->pluck("data"))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
            $chart->displayLegend(false);
            $chart->title("Participation by Platforms");


            return $chart;
    }




    public function SummaryParticipationChart($startdate, $enddate, $filtersites)
    {
      $data = DB::table('view_psf_patient_v4')
                            ->select([
                                DB::raw('count(*) as data, 

                                case 
                                when ACKNOWLEDGE =1 then "Aggreed"
                                else "Disaggreed"
                                end as label'),
                            ])
                            ->wherein('site',$filtersites)
                            ->whereBetween('start',[$startdate,$enddate])
                            ->groupBy('label')

                            ->get();


        $chart = new SampleChart;
        $chart->labels($data->pluck("label"));
        $chart->dataset('Total Number of Participation', 'bar', $data->pluck("data"))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);

            $chart->displayLegend(false);
            $chart->title("Total Number of Participation");

            return $chart;
    }

   
     //---- satisfaction with providers

    public function SatisfactionWithProviderChart($startdate,$enddate,$filtersites)
    {



      $overall = $this->OverallSatisfaction($startdate,$enddate,$filtersites);
      $receptionist = $this->ReceptionistSatisfaction($startdate,$enddate,$filtersites);
      $couselor = $this->CounselorSatisfaction($startdate,$enddate,$filtersites);
      $doctor = $this->DoctorSatisfaction($startdate,$enddate,$filtersites);
      $pharmacist = $this->PharmacistSatisfaction($startdate,$enddate,$filtersites);



      $data = [];

      $data = array_merge($overall,$receptionist,$couselor,$doctor,$pharmacist);

        $chart = new SampleChart;
        $chart->labels(array_keys($data));
        $chart->dataset('Satisfaction with Provider (%)', 'bar', array_values($data))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
        $chart->displayLegend(false);
        $chart->title("Satisfaction with Provider (%)");


          return $chart;

    }


   

    public function OverallSatisfaction($startdate,$enddate,$filtersites)
    {


      $satisfied = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q1A",3)
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $total = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->whereNotNull("Q1A")
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
      $data = ["Overall" => $perc ];

       return $data;

    }

    public function ReceptionistSatisfaction($startdate,$enddate,$filtersites)
    {


      $satisfied = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q2A",3)
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $total = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->whereNotNull("Q2A")
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
      $data = ["Receptionist" => $perc ];

       return $data;

    }

    public function CounselorSatisfaction($startdate,$enddate,$filtersites)
    {


      $satisfied = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q3A",3)
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $total = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->whereNotNull("Q3A")
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
      $data = ["Counselor" => $perc ];

       return $data;

    }


    public function DoctorSatisfaction($startdate,$enddate,$filtersites)
    {


      $satisfied = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q4A",3)
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $total = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->whereNotNull("Q4A")
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
      $data = ["Doctor" => $perc ];

       return $data;

    }

    public function PharmacistSatisfaction($startdate,$enddate,$filtersites)
    {


      $satisfied = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q5A",3)
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $total = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->whereNotNull("Q5A")
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              
                              ->first();


      $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
      $data = ["Pharmacist" => $perc ];

       return $data;

    }


    //----- Participation by KP status
    public function NumOfTG($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q8C",3) //self-idenfied as tg
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where ('Q11c',0) // no use drug
                              
                              ->first();


      $data = ["TG" => $kp->data ];

       return $data;

    }

    public function NumOfMSM($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q8C",1) //male
                              ->where("Q9C_1",1) //sex with male
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where ('Q11c',0) // no use drug
                              
                              ->first();


      $data = ["MSM" => $kp->data ];

       return $data;

    }

    public function NumOfEW($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->where("Q8C",2)//female
                              ->where("Q10C",1) //sex exchange for money
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where ('Q11c',0) // no use drug
                              
                              ->first();


      $data = ["FEW" => $kp->data ];

       return $data;

    }

    public function NumOfPWID($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                             
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where(function ($query) {
                                $query->whereRaw('(( Q8C =1 and Q9C_2=1) or (Q8C=2 and Q10C=0) )  ') // male sex with female
                                       ->where ('Q11c',1); // use drug
                               })
                              
                              ->first();


      $data = ["PWID/PWUD" => $kp->data ];

       return $data;

    }


    public function NumOfGP($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where(function ($query) {
                                $query->whereRaw('(Q8C =1 and Q9C_2=1)') // male sex with female
                                      ->orWhereRaw('Q8C=2 and Q10C=0') // female no sex in exchange for money
                                      ->whereRaw('Q11c =0 ');
                                      
                               })       
                              
                              ->first();


      $data = ["GP" => $kp->data ];

       return $data;

    }

    public function NumOfINDET($startdate,$enddate,$filtersites)
    {


      $kp = DB::table('view_psf_patient_v4')
                              ->select([
                                  DB::raw('count(*) as data'),
                              ])
                              ->wherein('site',$filtersites)
                              ->whereBetween('start',[$startdate,$enddate])
                              ->where(function ($query) {
                                $query->whereRaw('Q8C =1 and (Q9C_4=1 or Q9c_5=1)') // male and doesn't disclose sexual partner
                                      ->orWhereRaw('Q8C=2 and Q10C=98') // female and doesnt'disclose sex exchange for money
                                      ->orWhereRaw(' ((Q8C =1 and Q9C_2=1 ) or (Q8C=2 and Q10C=0) )  and  Q11c=98'); // doesn't disclose use drug
                                     
                               })
                              
                              ->first();


      $data = ["INDET" => $kp->data ];

       return $data;

    }

    
    public function SummaryKPChart($startdate,$enddate,$filtersites)
    {



      $tg = $this->NumOfTG($startdate,$enddate,$filtersites);
      $msm = $this->NumOfMSM($startdate,$enddate,$filtersites);
      $few = $this->NumOfEW($startdate,$enddate,$filtersites);
      $pwid = $this->NumOfPWID($startdate,$enddate,$filtersites);
      $gp = $this->NumOfGP($startdate,$enddate,$filtersites);
      $indet = $this->NumOfINDET($startdate,$enddate,$filtersites);
     
      
      $data = [];

      $data = array_merge($tg,$msm,$few,$pwid,$gp,$indet);

        $chart = new SampleChart;
        $chart->labels(array_keys($data));
        $chart->dataset('Participants by KP Status', 'bar', array_values($data))
            ->color($this->colorList)
            ->backgroundcolor($this->colorList);
        $chart->displayLegend(false);
        $chart->title("Participants by KP Status");


          return $chart;

    }


///--- satisfaction with service
   

     public function SatisfactionWithServiceChart($startdate,$enddate,$filtersites)
     {
 
 
 
       $anc = $this->ANCSatisfaction($startdate,$enddate,$filtersites);
       $sti = $this->STISatisfaction($startdate,$enddate,$filtersites);
       $tb = $this->TBSatisfaction($startdate,$enddate,$filtersites);
       $lab = $this->LABSatisfaction($startdate,$enddate,$filtersites);
       $psycho = $this->PsychoCounselingSatisfaction($startdate,$enddate,$filtersites);
 
 
 
       $data = [];
 
       $data = array_merge($anc,$sti,$lab,$tb,$psycho);
 
         $chart = new SampleChart;
         $chart->labels(array_keys($data));
         $chart->dataset('Satisfaction with HIV-Related Services (%)', 'bar', array_values($data))
             ->color($this->colorList)
             ->backgroundcolor($this->colorList);
        $chart->displayLegend(false);
        $chart->title("Satisfaction with HIV-Related Services (%)");
 
 
           return $chart;
 
     }
 
 
    
 
     public function ANCSatisfaction($startdate,$enddate,$filtersites)
     {
 
 
       $satisfied = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->where("Q1B",3)
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $total = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->whereRaw("Q1B !=4") // 4 means not received service
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
      
       $perc =  ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
       
       $data = ["RH & ANC" => $perc ];
 
        return $data;
 
     }
 
     public function STISatisfaction($startdate,$enddate,$filtersites)
     {
 
 
       $satisfied = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->where("Q2B",3)
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $total = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->whereRaw("Q2B !=4") 
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
       $data = ["STIs" => $perc ];
 
        return $data;
 
     }
 
     public function LabSatisfaction($startdate,$enddate,$filtersites)
     {
 
 
       $satisfied = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->where("Q3B",3)
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $total = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->whereRaw("Q3B !=4") // 4 means not received service
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
       $data = ["Lab" => $perc ];
 
        return $data;
 
     }
 
 
     public function TBSatisfaction($startdate,$enddate,$filtersites)
     {
 
 
       $satisfied = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->where("Q4B",3)
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $total = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->whereRaw("Q4B !=4") // 4 means not received service
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
       $data = ["TBs" => $perc ];
 
        return $data;
 
     }
 
     public function PsychoCounselingSatisfaction($startdate,$enddate,$filtersites)
     {
 
 
       $satisfied = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->where("Q5B",3)
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $total = DB::table('view_psf_patient_v4')
                               ->select([
                                   DB::raw('count(*) as data'),
                               ])
                               ->whereRaw("Q5B  !=4") // 4 means not received service
                               ->wherein('site',$filtersites)
                               ->whereBetween('start',[$startdate,$enddate])
                               
                               ->first();
 
 
       $perc = ($total->data==0)? 0 : round(($satisfied->data * 100) / $total->data,2);
       $data = ["Psycho-Counseling" => $perc ];
 
        return $data;
 
     }


}
