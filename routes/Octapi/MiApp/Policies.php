<?php

Route::get('MiApp/Policies/get','Octapi\MiApp\PoliciesController@get'); 

Route::post('MiApp/Policies/find','Octapi\MiApp\PoliciesController@find'); 

Route::post('MiApp/Policies/delete','Octapi\MiApp\PoliciesController@delete'); 

Route::post('MiApp/Policies/update','Octapi\MiApp\PoliciesController@update'); 

Route::post('MiApp/Policies/create','Octapi\MiApp\PoliciesController@create'); 

?>