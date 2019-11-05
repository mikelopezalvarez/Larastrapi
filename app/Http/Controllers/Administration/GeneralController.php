<?php

namespace App\Http\Controllers\Administration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;

use Illuminate\Support\Facades\Session;

class GeneralController extends Controller
{
    

    public function getSelectedApp(){

        if(Session::get('appId') != null){
            return response()->json(['success' => true, "id" => Session::get('appId')]);
        }else{
            return response()->json(['success' => false]);
        }

    }

    public function setApp(Request $request){

        Session::put('appId', $request->id);

    }

    public function getObjectById(Request $request){

        $res = App::find($request->id);

        return $res->structure;

    }
}
