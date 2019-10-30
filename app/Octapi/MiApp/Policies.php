<?php
namespace App\Octapi\MiApp;

use Illuminate\Database\Eloquent\Model; 
class Policies extends Model
{



	protected $table = "Policies"; 

	protected $fillable = ["id","name","number","eff_date"];
} 
