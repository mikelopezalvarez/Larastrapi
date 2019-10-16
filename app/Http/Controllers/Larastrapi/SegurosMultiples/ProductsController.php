<?php
/**
 * Created by PhpStorm.
 * User: darryl
 * Date: 10/8/2017
 * Time: 5:11 PM
 */

namespace App\Http\Controllers\Larastrapi\SegurosMultiples;


class ProductsController 
{
    public function get()
    {
        return view('welcome');
    }

    public function testing()
    {
        dd("Hola");
    }
}