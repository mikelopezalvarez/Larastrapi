<?php

namespace App\Http\Controllers\Administration\Classes;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;
use Illuminate\Filesystem\Filesystem;

class MikeRoute
{
    public $table;
    public $tableName;

    public $appNamePath;

    private $header;

    public $routes;

    public $controllerMethods = [
        ["name" => "get", "type" => "get"],
        ["name" => "find", "type" => "post"],
        ["name" => "delete", "type" => "post"],
        ["name" => "update", "type" => "post"],
        ["name" => "create", "type" => "post"]
    ];

    private $footer;

    private $document;

    private $modelName;


   

    public function __construct($appNamePath, $modelName, $table){

        $this->appNamePath = $appNamePath;
        $this->table = $table;
        $this->modelName = $modelName;


        $this->header = '<?php' . PHP_EOL;
        
        $this->footer = PHP_EOL . '?>';

       

    }

    

    private function addRoute($content){

        $this->routes .= PHP_EOL . $content . PHP_EOL;
        

    }

   
   
    

    // Method to create file 
    public function save(){

       

        foreach ($this->controllerMethods as $item) {
            $this::addRoute("Route::".$item["type"]."('".$this->appNamePath."/".$this->modelName."/".$item["name"]."','Octapi\\".$this->appNamePath."\\".$this->modelName."Controller@".$item["name"]."'); ");
        }

        $this->document = $this->header;

        $this->document.= $this->routes;

        $this->document.= $this->footer;
        
      

        // Return a boolean to process completed
        return Storage::disk('routes')->put($this->appNamePath.'/'.$this->modelName.".php",  $this->document);

    }

    
}
