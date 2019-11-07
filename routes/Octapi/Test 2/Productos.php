<?php

Route::get('api/Test 2/Productos/get','Octapi\Test 2\ProductosController@get'); 

Route::post('api/Test 2/Productos/find','Octapi\Test 2\ProductosController@find'); 

Route::post('api/Test 2/Productos/delete','Octapi\Test 2\ProductosController@delete'); 

Route::post('api/Test 2/Productos/update','Octapi\Test 2\ProductosController@update'); 

Route::post('api/Test 2/Productos/create','Octapi\Test 2\ProductosController@create'); 

?>