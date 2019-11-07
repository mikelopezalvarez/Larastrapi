<?php
namespace App\Http\Controllers\Octapi\zGenwareLandingPage;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\zGenwareLandingPage\Products;

class ProductsController extends Controller
{



		public function get(Request $request){ 
			return Products::get(); 
		} 


		public function find(Request $request){ 
			return Products::find($request->id); 
		} 


		public function delete(Request $request){ 
			$e = Products::find($request->id); 
			return $e->delete(); 
		} 


		public function update(Request $request){ 
			$e = Products::find($request->id); 
			$e->name = $request->name; 
			$result = $e->save(); 
			return $result; 
		} 


		public function create(Request $request){ 
			$e = new Products; 
			$e->name = $request->name; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
		} 

} 
