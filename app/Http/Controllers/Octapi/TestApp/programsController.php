<?php
namespace App\Http\Controllers\Octapi\TestApp;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\TestApp\programs;

class programsController extends Controller
{



		public function get(Request $request){ 
			return programs::get(); 
		} 


		public function find(Request $request){ 
			return programs::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = programs::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = programs::find($request->id); 
			$e->name = $request->name; 
			$e->custom = $request->custom; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new programs; 
			$e->name = $request->name; 
			$e->custom = $request->custom; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
