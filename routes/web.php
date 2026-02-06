<?php

Route::get('/', function () { return redirect('/home'); });

//

Route::get('/lang/{locale}', function ($locale) { 


    if ( ($locale != "kh") && ($locale != "en") || ($locale == "") || ($locale == null))
    {
        $locale = "kh";

    }

    App::SetLocale($locale);

    session(['locale' => $locale]);

    //return App::GetLocale();

    return redirect(session()->previousUrl()); 



 });


//

// Authentication Routes...
$this->get('/login', 'Auth\LoginController@showLoginForm')->name('auth.login');
$this->post('/login', 'Auth\LoginController@login')->name('auth.login');
$this->post('/logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('/change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('/change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('/password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

$this->get('/dashboard', 'ReportingController@showDashboard');
Route::group(['middleware' => ['auth'], 'prefix' => '/', 'as' => 'admin.'], function () {
    Route::get('/home', 'Dashboard_V3Controller@home');
    Route::resource('/permissions', 'Admin\PermissionsController');
    Route::post('/permissions_mass_destroy', ['uses' => 'Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);
    Route::resource('/roles', 'Admin\RolesController');
    Route::post('/roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('/users', 'Admin\UsersController');
    Route::post('/users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::post('/resetpwd/{id}', ['uses' => 'Admin\UsersController@resetpwd', 'as' => 'users.resetpwd']);
    Route::resource('/tables','Admin\TableController');
    Route::resource('/sites','Admin\SitesController');
    Route::post('/sites_mass_destroy', ['uses' => 'Admin\SitesController@massDestroy', 'as' => 'sites.mass_destroy']);
    Route::resource('/reporting', 'ReportingController');
    Route::post('/reporting', ['uses' => 'ReportingController@index', 'as' => 'reporting.table']);
    Route::resource('/dashboard', 'Dashboard_V3Controller');
    Route::post('/dashboard', ['uses' => 'Dashboard_V3Controller@index', 'as' => 'reporting.dashboard']);
    Route::resource('/hfs', 'HFSController');
    Route::post('/hfs', ['uses' => 'HFSController@index', 'as' => 'hfs.table']);
    Route::resource('/hfs_dashboard', 'HFS_DashboardController');
    Route::post('/hfs_dashboard', ['uses' => 'HFS_DashboardController@index', 'as' => 'hfs.dashboard']);

    Route::resource('/admin_dashboard', 'Dashboard_V2Controller');
    Route::post('/admin_dashboard', ['uses' => 'Dashboard_V2Controller@index', 'as' => 'admin.dashboard']);

});
