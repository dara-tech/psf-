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



class HFSController extends Controller
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
     */
    public function index(Request $request)
    {

     
      
      $data=[];
      $role = Auth::user()->roles()->first();

      if ($role->id==1)
      {
        $sites = Site::get()->pluck('site', 'site');
      

      }else{
        $sites = Auth::user()->sites()->get()->pluck('site', 'site');
       
      }

      
      $sites->prepend('*','*');
     
      if (empty($request->startdate) && empty($request->endate))
      {
        $data = HFS::all();
      }else{

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

        $data = HFS::whereBetween('start',[$startdate,$enddate])
                          ->wherein('site',$filtersites)
                          ->get();
      }
       
    
       return view('HFS.Table', compact('data','sites'));     
      
    }
    
    
}
