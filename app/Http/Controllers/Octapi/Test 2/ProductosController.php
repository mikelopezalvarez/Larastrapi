<?php
namespace App\Http\Controllers\Octapi\Test 2;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon; 

use App\Octapi\Test 2\Productos;

class ProductosController extends Controller
{



		public function get(Request $request){ 
			if($request->token != "KiiMVQfkKSUp4k31YDktZD9IOdbcTdAc"){ 
			return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); 
			} else { 
			return Productos::get(); 
			} 
		} 


		public function find(Request $request){ 
			if($request->token != "KiiMVQfkKSUp4k31YDktZD9IOdbcTdAc"){ 
			return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); 
			} else { 
			return Productos::find($request->id); 
			} 
		} 


		public function delete(Request $request){ 
			if($request->token != "KiiMVQfkKSUp4k31YDktZD9IOdbcTdAc"){ 
			return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); 
			} else { 
			$e = Productos::find($request->id); 
			return $e->delete(); 
			} 
		} 


		public function update(Request $request){ 
			if($request->token != "KiiMVQfkKSUp4k31YDktZD9IOdbcTdAc"){ 
			return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); 
			} else { 
			$e = Productos::find($request->id); 
			$e->nombre = $request->nombre; 
			$result = $e->save(); 
			return $result; 
			} 
		} 


		public function create(Request $request){ 
			if($request->token != "KiiMVQfkKSUp4k31YDktZD9IOdbcTdAc"){ 
			return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); 
			} else { 
			$e = new Productos; 
			$e->nombre = $request->nombre; 
			if($e->save()){ 
				return response()->json(["success" => true, "id" => $e->id]); 
			}else{ 
				return response()->json(["success" => false]); 
			} 
			} 
		} 

} 
