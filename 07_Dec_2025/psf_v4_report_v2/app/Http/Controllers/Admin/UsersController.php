<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use App\Models\Site;
use  App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $users = User::all();

     //   return $users;
       
        return view('admin.users.index', compact('users'));

    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $roles = Role::get()->pluck('name', 'name');

        $sites = Site::get()->pluck('site', 'site');

      
//        return view('admin.users.create', compact('roles','sites'));

        return view('admin.users.create')
                ->with('roles',$roles)
                ->with('sites',$sites)
                ->with('allsites', $this->getAllSitesAndProvinces())
                ->with ('provinces',$this->getProvinces());
    }


    private function getUserSites()
    {
       return BaseController::getUserSites();
    

    }

    private function getAllSitesAndProvinces()
    {
      return BaseController::getAllSitesAndProvinces();
    }

    public function getProvinces()
    {
      return BaseController::getProvinces();
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {

     
       
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $user = User::create($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        $sites = $request->input('sites') ? $request->input('sites') : [];
        $user->belong2Site($sites);

        $user->password = $request->get('new_password');
        $user->save();

        return redirect()->route('admin.users.index');

       
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');
        $sites = Site::get()->pluck('site', 'site');

        $user = User::findOrFail($id);

        $userSites = $this->getUserSites();
        //return $user;

        //return view('admin.users.edit', compact('user', 'roles','sites'));

        return view('admin.users.edit')
        ->with('roles',$roles)
        ->with('sites',$sites)
        ->with('usersites',$userSites)
        ->with('user',$user)
        ->with('allsites', $this->getAllSitesAndProvinces())
        ->with ('provinces',$this->getProvinces());

    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

    //    return $request;

       
        $user = User::findOrFail($id);
        
        $request->password = $user->password;
       
        $user->update($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);
        $sites = $request->input('sites')?$request->input('sites') :[];
        $user->belong2Site($sites);

        
        
        
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function resetpwd( Request $request,$id)
    {

        $user = User::findOrFail($request->id);


        $user->password = "12345678";//Hash::make("12345678");
       
        $user->save();
        
       return $this->index();
    }

}
