<?php

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {

  // $this->get('psf/getTableList','Api/PSFController@getTableList');
  Route::get('/getTableList', 'HIVSTController@getTableList');
  Route::get('/{table}/getFields', 'HIVSTController@getFieldList');
  Route::get('/{table}/getData', 'HIVSTController@getData');

});
