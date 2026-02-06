<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', 'LoginController@showLoginPage');


Route::group(['middleware' => ['auth'], 'prefix' => 'Reporting', 'as' => 'Reporting.'], function () {

    Route::get('/','ReportingController@index');

});

Route::get('/', function (){
    abort(404,'Page not found');
}
);

Route::get('/client', function (){
    abort(404,'Page not found');
}
);

Route::get('/provider', function (){
    abort(404,'Page not found');
}
);

Route::get('/client/{token}/{locale?}', 'ClientController@index');
Route::post('/client/{token?}/{index?}', 'ClientController@savePage');
Route::get('/client/{token?}/{locale?}/{uuid?}/{index?}', 'ClientController@showPage');

Route::get('/provider/{token}/{locale?}', 'ProviderController@index');
Route::post('/provider/{token?}/{index?}', 'ProviderController@savePage');
Route::get('/provider/{token?}/{locale?}/{uuid?}/{index?}', 'ProviderController@showPage');




Route::get('{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'kh'])) {
        abort(400);
    }

    App::setLocale($locale);

    //
});


