<?php

Route::get('api/zGenwareLandingPage/Products/get','Octapi\zGenwareLandingPage\ProductsController@get'); 

Route::post('api/zGenwareLandingPage/Products/find','Octapi\zGenwareLandingPage\ProductsController@find'); 

Route::post('api/zGenwareLandingPage/Products/delete','Octapi\zGenwareLandingPage\ProductsController@delete'); 

Route::post('api/zGenwareLandingPage/Products/update','Octapi\zGenwareLandingPage\ProductsController@update'); 

Route::post('api/zGenwareLandingPage/Products/create','Octapi\zGenwareLandingPage\ProductsController@create'); 

?>