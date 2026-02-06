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



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
       
      $user = Auth::user();
      $role = $user->roles()->first();


      $startdate= "2019-0-01";
      $enddate= date("YYYY-MM-dd");
      $sites = "*";

      return redirect()->route( 'admin.reporting.dashboard' )->with( [ 'startdate' => $startdate,'enddate'=>$enddate,'sites'=>$sites ] );
     // return  Redirect::route('reporting.dashboard, $startdate,$enddate,$sites');

     
    }
    
}
