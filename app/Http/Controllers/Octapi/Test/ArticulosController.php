<?php
namespace App\Http\Controllers\Octapi\Test;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\Test\Articulos;

class ArticulosController extends Controller
{



		public function get(Request $request){ 
			return Articulos::get(); 
		} 


		public function find(Request $request){ 
			return Articulos::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = Articulos::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = Articulos::find($request->id); 
			$e->name = $request->name; 
			$e->descp = $request->descp; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new Articulos; 
			$e->name = $request->name; 
			$e->descp = $request->descp; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
