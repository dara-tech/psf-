<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{
    //
    public function index()
    {

      $tables = DB::select("SELECT TABLE_NAME
      FROM information_schema.views where Table_SCHEMA = '". DB::getDatabaseName() ."'");
 
        return view('admin.tables.index',compact('tables'));
    }

    public function update(UpdateRolesRequest $request, $id)
    {
        return 'update';
    }

    public function show($id)
    {
      return "show " . $id;
    }

    public function create()
    {
      return "create";
    }
    public function store()
    {
      return view ('home');
    }

}
