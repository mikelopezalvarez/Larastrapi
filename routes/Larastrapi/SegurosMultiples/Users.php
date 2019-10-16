<?php 
 Route::get('/SegurosMultiples/Users/get','Larastrapi\SegurosMultiples\UsersController@get')->name('get'); 
 Route::get('/SegurosMultiples/Users/add','Larastrapi\SegurosMultiples\UsersController@add')->name('add'); 
 Route::get('/SegurosMultiples/Users/del','Larastrapi\SegurosMultiples\UsersController@del')->name('del'); 
 Route::get('/SegurosMultiples/Users/upd','Larastrapi\SegurosMultiples\UsersController@upd')->name('upd'); 
?>