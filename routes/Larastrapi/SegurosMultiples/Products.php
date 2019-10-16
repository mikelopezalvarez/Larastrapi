<?php 
 Route::get('/SegurosMultiples/Products/get','Larastrapi\SegurosMultiples\ProductsController@get')->name('get'); 
 Route::get('/SegurosMultiples/Products/add','Larastrapi\SegurosMultiples\ProductsController@add')->name('add'); 
 Route::get('/SegurosMultiples/Products/del','Larastrapi\SegurosMultiples\ProductsController@del')->name('del'); 
 Route::get('/SegurosMultiples/Products/upd','Larastrapi\SegurosMultiples\ProductsController@upd')->name('upd'); 
?>