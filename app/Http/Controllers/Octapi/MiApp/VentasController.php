<?php
namespace App\Http\Controllers\Octapi; 

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\MiApp\Ventas

class VentasController extends Controller
{



		public function get(){ 
			return Ventas::get(); 
		} 


		public function find(Request $request){ 
			return Ventas::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = Ventas::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = Ventas::find($request->id); 
			$e->name = $request->name; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new Ventas; 
			$e->name = $request->name; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
