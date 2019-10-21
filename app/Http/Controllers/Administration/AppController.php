<?php

namespace App\Http\Controllers\Administration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;

class AppController extends Controller
{
    

    public function get(){

        return App::get();

    }

    public function getInfoById(Request $request){

        return App::find($request->id);

    }

    public function getObjectById(Request $request){

        $res = App::find($request->id);

        return $res->structure;

    }
}
