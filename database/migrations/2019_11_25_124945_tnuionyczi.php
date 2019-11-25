<?php
use Illuminate\Support\Facades\Schema; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class tnuionyczi extends Migration
{


	public function up(){


			Schema::table("gzwtn_programs", function(Blueprint $t){ 
				$t->renameColumn("name", "foods"); 
			}); 


	}


	public function down(){


			Schema::table("gzwtn_programs", function(Blueprint $t){ 
				$t->renameColumn("foods", "name"); 
			}); 


	}


}