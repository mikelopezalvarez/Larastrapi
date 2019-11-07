<?php
namespace App\Octapi\Test;

use Illuminate\Database\Eloquent\Model; 
class Productos extends Model
{



	protected $table = "Productos"; 

	protected $fillable = ["id","nombre"];
} 
