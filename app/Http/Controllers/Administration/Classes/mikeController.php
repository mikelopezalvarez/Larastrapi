<?php

namespace App\Http\Controllers\Administration\Classes;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;

use App\Http\Controllers\Administration\Classes\MethodsInterface;

class MikeController 
{
    public $table;
    public $tableName;

    public $appNamePath;

    private $header;

    public $methods;

    private $footer;

    private $document;

    private $modelName;

    private $security;
    private $token;

    private $relations = [];

    private $relationString;


   

    public function __construct($appNamePath, $modelName, $table, $security, $token, $relations = 0){

        $this->appNamePath = $appNamePath;
        $this->table = $table;
        $this->tableName = $table["name"];
        $this->modelName = $modelName;

        $this->security = $security;
        $this->token = $token;

        $this->relationString = '';

        if($relations != 0){
            foreach ($relations as $item) {

                if($this->modelName == $item['table1']){
                    $this->relations[] = $item;
                    $this->relationString .= '"'.$item['table2'].'",';

                }
    
            }
        }



        $this->header = '<?php' . PHP_EOL;
        // Include the neccesary class
        $this->header .= 'namespace App\Http\Controllers\Octapi' . '\\' . $this->appNamePath . ";" . PHP_EOL . PHP_EOL;
        $this->header .= 'use Illuminate\Http\Request; ' . PHP_EOL;
        $this->header .= 'use App\Http\Controllers\Controller; ' . PHP_EOL;
        $this->header .= 'use Carbon\Carbon; ' . PHP_EOL . PHP_EOL;
        $this->header .= 'use App\Octapi' . '\\' . $this->appNamePath . '\\' . $this->modelName . ";". PHP_EOL . PHP_EOL;
        // Name of class
        $this->header .= 'class '.$this->modelName.'Controller extends Controller' . PHP_EOL;
        $this->header .= '{'. PHP_EOL . PHP_EOL . PHP_EOL;

        $this->footer = '} ' . PHP_EOL;

       

    }
    // Method to append methods
    public function appendMethod($content){

        $this->methods .= PHP_EOL . $content . PHP_EOL;
        

    }

    // Method to create file 
    public function save(){

        $methods = new MethodsInterface($this->modelName, $this->table, $this->security, $this->token, rtrim($this->relationString, ','));

        foreach ($methods->interface as $item){
        
            $this::appendMethod($item["function"]);
        }

        $this->document = $this->header;

        $this->document.= $this->methods;

        $this->document.= $this->footer;
        
      

        // Return a boolean to process completed
        return Storage::disk('controllers')->put($this->appNamePath.'/'.$this->tableName."Controller.php",  $this->document);

    }


    public function scaffolding(){

        $methods = new MethodsInterface($this->modelName, $this->table);

        foreach ($methods->interface as $item){
        
            $this::appendMethod($item["function"]);
        }

        $this::save();

    }


   

    

    

    
}
