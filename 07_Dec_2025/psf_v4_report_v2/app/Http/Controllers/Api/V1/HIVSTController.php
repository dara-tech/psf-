<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;

class HIVSTController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      //  $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableList()
    {
       
      $tables = DB::select("SELECT TABLE_NAME
      FROM information_schema.views where Table_SCHEMA = '". DB::getDatabaseName() ."'");

      $html = '';
      foreach ($tables as $table)
      {
        $html .= '<option value="' . $table->TABLE_NAME .'">' . $table->TABLE_NAME . '</option>';
      }
      
      //return env('DB_DATABASE');
       return $html;


    }

    public function getFieldList($table)
    {

   //  return $table;
       
     $tables = DB::select("SELECT `COLUMN_NAME` 
                                FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                                WHERE `TABLE_SCHEMA`='odk_prod' 
                                AND `TABLE_NAME`='$table'");

      $html = '';
      foreach ($tables as $table)
      {
        $html .= '<option value="' . $table->COLUMN_NAME .'">' . $table->COLUMN_NAME . '</option>';
      }
      
      //return env('DB_DATABASE');
       return $html;


    }


    public function getData($table)
    {

      $data = DB::select("SELECT * FROM $table  ");

      return $data;
    }
    
}
