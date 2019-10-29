<?php

namespace App\Http\Controllers\Administration\Classes;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;

class MikeMigration 
{
    
    private $header;

    private $up;

    private $down;

    private $endUp;

    private $footer;

    private $document;

    public $prefixFileName;

    public $migrationName;

    public function __construct($prefixFileName){

        $this->prefixFileName = $prefixFileName;
        

        $this->header = '<?php' . PHP_EOL;
        // Include the neccesary class
        $this->header .= 'use Illuminate\Support\Facades\Schema; ' . PHP_EOL;
        $this->header .= 'use Illuminate\Database\Schema\Blueprint;' . PHP_EOL;
        $this->header .= 'use Illuminate\Database\Migrations\Migration;' . PHP_EOL . PHP_EOL;
        // Name of class
        $this->header .= 'class '. $this->prefixFileName.' extends Migration' . PHP_EOL;
        $this->header .= '{'. PHP_EOL . PHP_EOL . PHP_EOL;

        $this->header .= "\t" . 'public function up(){'. PHP_EOL . PHP_EOL;

        $this->up = '';
        $this->down = '';

        $this->endUp = "\t" . '}'. PHP_EOL . PHP_EOL . PHP_EOL;
        $this->endUp .=  "\t" . 'public function down(){'. PHP_EOL . PHP_EOL ;


        $this->footer =  "\t" . '}'. PHP_EOL . PHP_EOL . PHP_EOL;
        $this->footer .= '}';

    }
    // Method to append up function
    public function up($content){

        $this->up .= PHP_EOL . $content . PHP_EOL;
        

    }

    // Method to append down function
    public function down($content){

        $this->down .= PHP_EOL . $content . PHP_EOL;

    }

    // Method to create file migration database/migrations/
    public function save(){

        // Save the name of migration in prop (0000_00_00_000000_prefix.php)
        $this->migrationName = date('Y_m_d_'.Carbon::now()->format('His'),time()) . "_". $this->prefixFileName .".php";

        $this->document = $this->header;

        $this->document.= $this->up;
        $this->document.= $this->endUp;
        $this->document.= $this->down;
        $this->document.= $this->footer;

        return Storage::disk('migration')->put($this->migrationName, $this->document);

    }

    // Method to run migration
    public function run(){
        return Artisan::call('migrate');
    }
}
