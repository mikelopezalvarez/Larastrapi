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


   

    public function __construct($appNamePath, $modelName, $table){

        $this->appNamePath = $appNamePath;
        $this->table = $table;
        $this->tableName = $table["name"];
        $this->modelName = $modelName;


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

        $methods = new MethodsInterface($this->modelName, $this->table);

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


   

    private function get(){
        
        $func = '';

        $func .= "\t\t" . 'public function get(){ ' . PHP_EOL;

        $func .= "\t\t\t" . 'return '.$this->modelName.'::get(); ' . PHP_EOL;

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    private function find(){
        
        $func = '';

        $func .= "\t\t" . 'public function find(Request $request){ ' . PHP_EOL;

        $func .= "\t\t\t" . 'return '.$this->modelName.'::find($request->id); ' . PHP_EOL;

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    private function delete(){
        
        $func = '';

        $func .= "\t\t" . 'public function delete(Request $request){ ' . PHP_EOL;

        $func .= "\t\t\t" . '$e = '.$this->modelName.'::find($request->id); ' . PHP_EOL;
        $func .= "\t\t\t" . 'return $e->delete(); ' . PHP_EOL;

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    private function update(){
        
        $func = '';

        $func .= "\t\t" . 'public function update(Request $request){ ' . PHP_EOL;
        
        $func .= "\t\t\t" . '$e = '.$this->modelName.'::find($request->id); ' . PHP_EOL;

        foreach ($this->table["fields"] as $item) {

            $func .= "\t\t\t" . '$e->'.$item['name'].' = $request->'.$item['name'].'; ' . PHP_EOL;

        }
        $func .= "\t\t\t" . '$result = $e->save(); ' . PHP_EOL;
        $func .= "\t\t\t" . 'return $result; ' . PHP_EOL;
        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;

    }

    private function create(){
        
        $func = '';

        $func .= "\t\t" . 'public function create(Request $request){ ' . PHP_EOL;
        
        $func .= "\t\t\t" . '$e = new '.$this->modelName.'; ' . PHP_EOL;

        foreach ($this->table["fields"] as $item) {

            $func .= "\t\t\t" . '$e->'.$item['name'].' = $request->'.$item['name'].'; ' . PHP_EOL;

        }

        $func .= "\t\t\t" . 'if($e->save()){ ' . PHP_EOL;
        $func .= "\t\t\t\t" . 'return response()->json(["success" => true, "id" => $e->id]); ' . PHP_EOL;
        $func .= "\t\t\t" . '}else{ ' . PHP_EOL;
        $func .= "\t\t\t\t" . 'return response()->json(["success" => false]); ' . PHP_EOL;
        $func .= "\t\t\t" . '} ' . PHP_EOL;
        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;

    }

    

    

    
}
