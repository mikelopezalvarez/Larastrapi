<?php
namespace App\Http\Controllers\Octapi\Test;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\Test\Productos;

class ProductosController extends Controller
{



		public function get(Request $request){ 
			return Productos::get(); 
		} 


		public function find(Request $request){ 
			return Productos::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = Productos::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = Productos::find($request->id); 
			$e->nombre = $request->nombre; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new Productos; 
			$e->nombre = $request->nombre; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
