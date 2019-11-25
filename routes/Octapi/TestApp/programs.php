<?php

Route::get('api/TestApp/programs/get','Octapi\TestApp\programsController@get'); 

Route::post('api/TestApp/programs/find','Octapi\TestApp\programsController@find'); 

Route::post('api/TestApp/programs/delete','Octapi\TestApp\programsController@delete'); 

Route::post('api/TestApp/programs/update','Octapi\TestApp\programsController@update'); 

Route::post('api/TestApp/programs/create','Octapi\TestApp\programsController@create'); 

?>