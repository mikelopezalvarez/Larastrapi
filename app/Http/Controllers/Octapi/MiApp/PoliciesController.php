<?php
namespace App\Http\Controllers\Octapi\MiApp;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\MiApp\Policies;

class PoliciesController extends Controller
{



		public function get(){ 
			return Policies::get(); 
		} 


		public function find(Request $request){ 
			return Policies::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = Policies::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = Policies::find($request->id); 
			$e->name = $request->name; 
			$e->number = $request->number; 
			$e->eff_date = $request->eff_date; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new Policies; 
			$e->name = $request->name; 
			$e->number = $request->number; 
			$e->eff_date = $request->eff_date; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
