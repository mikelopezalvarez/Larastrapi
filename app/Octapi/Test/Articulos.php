<?php
namespace App\Octapi\Test;

use Illuminate\Database\Eloquent\Model; 
class Articulos extends Model
{



	protected $table = "Articulos"; 

	protected $fillable = ["id","name","descp"];
} 
