<?php
namespace App\Octapi\Test 2;

use Illuminate\Database\Eloquent\Model; 
class Productos extends Model
{



	protected $table = "Productos"; 

	protected $fillable = ["id","nombre"];
} 
