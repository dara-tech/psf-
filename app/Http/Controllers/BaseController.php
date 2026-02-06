<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Charts\SampleChart;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;


class BaseController extends Controller
{


    public static function getProvinces()
    {

      $locale = session()->get('locale');

      if ($locale == "en")
      {
          $field = "province";
      }else
      {
        $field = "province_kh";
      }

      $provinces = DB::table('tbl_sites')
      ->select($field)
      ->distinct()
      ->orderBy($field,'asc')
      ->pluck($field);


      $provinces->prepend("");
      return $provinces;
    }

    public static function getKPs()
    {
      
      $locale = session()->get('locale');
      if ($locale == "en")
      {
        $KPs =['','MSM', 'TG', 'EW', 'GP', 'PWID','UNDEFINED'];
      }
      else{
         $KPs =['','បុរសស្រឡាញ់បុរស (MSM)', 'ក្រុមប្លែងភេទ (TG)', 'ស្រ្តីបំរើសេវាកំសាន្ត (EW)', 'ប្រជាជនទូទៅ (GP)', 'អ្នកប្រើប្រាស់គ្រឿងញៀណ (PWID)','មិនអាចកំណត់បាន'];
       }
      
      return $KPs;
    }

    public static function getAgeRanges()
    {
      $locale = session()->get('locale');
      if ($locale == "en")
      {
        $Ages =['','< 18','19 to​ 24','25 to 35','36 to 45','> 45'];
      }else
      {
        $Ages =['','តិច​ជាង ១៨ ឆ្នាំ','១៩ ដល់​  ២៤ ​ឆ្នាំ','២៥ ដល់ ៣៥ ឆ្នំា','៣៦ ដល់ ៤៥ ឆ្នាំ','អាយុច្រើនជាង ៤៥ ឆ្នាំ'];
      }
     
     

      return $Ages;
    }

    public static function getUserSites()
    {
      $role = Auth::user()->roles()->first();
     
      $locale = session()->get('locale');
      if ($locale == "en")
      {
          $field = "sitename";
      }else
      {
        $field = "site";
      }
     
      if ($role->id==1)
      {
        $sites = Site::get()->pluck($field, $field);
      
      }else{
        $sites = Auth::user()->sites()->get()->pluck($field, $field);
      
        
        if ($sites->get("*") == "*")
        {
          $sites = Site::get()->pluck($field, $field);

        }
      }

      $sites->prepend('*','*');
      return $sites;
    

    }


    public static function getAllSitesAndProvinces()
    {

      $role = Auth::user()->roles()->first();

      $locale = session()->get('locale');
      if ($locale == "en")
      {
        $site = "sitename";
        $field = "province";
      }else{
        $site = "site";
        $field = "province_kh";
      }

      if ($role->id==1)
      {
        $sites = Site::get([$site . " as site",  $field.' as province']);
      
      }else{
        $sites = Auth::user()->sites()->get([$site ." as site" ,$field. ' as province']);
      

        if (count($sites)>0)
        {
            if ($sites[0]->site == "*") // if user has access to all site permission
          {
            $sites = Site::get([$site ." as site",$field.' as province']);


          }
        }
      }

      return $sites;
     // return Site::get(['site','province_kh as province']);
    }

    public static function getListPeriodReport()
    {
      $dateValue = date_create('2020-01');
      $endDate =  date_add(date_create(date("Y-m")),date_interval_create_from_date_string('3 month'));
    

      $data=[];

      while ($dateValue <$endDate)
      {

        $qNum = (int)date_format($dateValue,"m");
        $data += ["Q". (round($qNum/3) + 1). "-".date_format($dateValue,"Y") => "Q". (round($qNum/3) + 1). "-".date_format($dateValue,"Y")];
        $dateValue = date_add($dateValue,date_interval_create_from_date_string('3 month'));
      }
      
      return $data;
    }
   

    public static function getMonthReport($startdate, $enddate)
    {

      $months = array();
      $datevalue = date_create(date_format(date_create($startdate),'Y-m'));
      $interval =  date_diff($datevalue,date_create($enddate))->format("%m") ;

      for($i=0; $i<= $interval ; $i++)
      {
        array_push( $months,date_format($datevalue,'F-y'));

        $datevalue = date_add($datevalue,date_interval_create_from_date_string('1 month'));
      }

      return $months;
    }


    public static function covertDate2Quarter($d, $isFiscalYear, $byMonth=false)
    {

    
      // break down by month
      if ($byMonth == true) {return  $d;} 


      // break down by Quarter
      $dateValue = date_create($d);

      $m = (int)date_format($dateValue,"m");

      $arr = explode("-",$d);
      $year= (int) $arr[1];
      $reportMonth;
      
      if ($isFiscalYear)
      {
           if ( $m>9)
           {
             $qNum = 1;
         
             $year = $year+1;

           }else if ($m <4)
           {
             $qNum=2;
           }else if ($m<7)
           {
             $qNum=3;
           }else if ($m<10)
           {
             $qNum=4;
           }
           

      }else
      {

            if ($m <4)
           {
             $qNum=1;
           }else if ($m<7)
           {
             $qNum=2;
           }else if ($m<10)
           {
             $qNum=3;
           }else if ( $m>9)
           {
             $qNum = 4;
           }
      }

      return "Q". $qNum. "-". $year ;
    }


    public static function getReportMonthByQuarter($period, $isFiscalYear)
    {

      if ($period == null) return;
     
        $data=[];

        
        for($i=0; $i<count($period); $i++)
        {
              //$months=date ('y-m');
              $month;
              $arr = explode("-",$period[$i]);

              if($arr[0] == "Q1")
              {
                $months = date_create(($isFiscalYear==true?$arr[1]-1 : $arr[1])."-". ($isFiscalYear==true? "10" : "01"));
              }else  if($arr[0] == "Q2")
              {
                $months = date_create($arr[1]."-". ($isFiscalYear==true? "01" : "04"));
              }else  if($arr[0] == "Q3")
              {
                $months = date_create($arr[1]."-". ($isFiscalYear==true? "04" : "07"));
              }else  if($arr[0] == "Q4")
              {
                $months = date_create($arr[1]."-". ($isFiscalYear==true? "07" : "10"));
              }


              for ($j=0; $j<3; $j++)
              {
                array_push($data, date_format($months,"F-y"));
                
                date_add($months,date_interval_create_from_date_string('1 month'));

              }
            }

        if (count($data) >0 )
        {
          return $data;
        }else
        {
          return null;
        }
        
    }



}