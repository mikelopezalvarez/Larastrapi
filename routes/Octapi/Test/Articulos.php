<?php

Route::get('api/Test/Articulos/get','Octapi\Test\ArticulosController@get'); 

Route::post('api/Test/Articulos/find','Octapi\Test\ArticulosController@find'); 

Route::post('api/Test/Articulos/delete','Octapi\Test\ArticulosController@delete'); 

Route::post('api/Test/Articulos/update','Octapi\Test\ArticulosController@update'); 

Route::post('api/Test/Articulos/create','Octapi\Test\ArticulosController@create'); 

?>