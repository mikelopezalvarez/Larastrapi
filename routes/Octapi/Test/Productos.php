<?php

Route::get('api/Test/Productos/get','Octapi\Test\ProductosController@get'); 

Route::post('api/Test/Productos/find','Octapi\Test\ProductosController@find'); 

Route::post('api/Test/Productos/delete','Octapi\Test\ProductosController@delete'); 

Route::post('api/Test/Productos/update','Octapi\Test\ProductosController@update'); 

Route::post('api/Test/Productos/create','Octapi\Test\ProductosController@create'); 

?>