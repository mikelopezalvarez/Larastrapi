<?php
use Illuminate\Support\Facades\Schema; 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class qtkznmrccb extends Migration
{


	public function up(){


		if (!Schema::hasTable('gzwtn_programs')) { 
			Schema::create("gzwtn_programs", function (Blueprint $t) {
				$t->engine = "InnoDB"; 
				$t->increments("id"); 
				$t->string("name")->nullable(); 
				$t->integer("custom")->nullable(); 
			});
		} else {
			Schema::table("gzwtn_programs", function (Blueprint $t) {
				$t->string("name")->nullable()->change(); 
				$t->integer("custom")->nullable()->change(); 
			});
		} 


	}


	public function down(){


		Schema::dropIfExists("gzwtn_programs"); 
	}


}