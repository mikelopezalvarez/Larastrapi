<?php


//Data Management Controller
Route::post('/testing','Administration\\DataManagementController@saveAppConfiguration')->name('nada');
Route::post('/createApp','Administration\\DataManagementController@createApp');

//App Controller
Route::get('/app/get','Administration\\AppController@get');
Route::post('/app/getInfoById','Administration\\AppController@getInfoById');
Route::post('/app/getObjectById','Administration\\AppController@getObjectById');









?>