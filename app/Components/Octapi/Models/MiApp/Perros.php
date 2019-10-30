<?php
namespace App\Components\Octapi\Models\MiApp;

use Illuminate\Database\Eloquent\Model; 
class Perros extends Model
{



	protected $table = "Perros"; 

	protected $fillable = ["id","name","last_name"];
} 
