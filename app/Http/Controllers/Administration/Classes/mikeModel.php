<?php

namespace App\Http\Controllers\Administration\Classes;



use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\App;

class MikeModel
{
    public $table;
    public $tableName;

    public $appNamePath;

    private $header;

    public $properties;

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
        $this->header .= 'namespace App\Components\Octapi\Models' . '\\' . $this->appNamePath . ';' . PHP_EOL . PHP_EOL;
        $this->header .= 'use Illuminate\Database\Eloquent\Model; ' . PHP_EOL;
        // Name of class
        $this->header .= 'class '.$this->modelName.' extends Model' . PHP_EOL;
        $this->header .= '{'. PHP_EOL . PHP_EOL . PHP_EOL;

        $this->footer = '} ' . PHP_EOL;

       

    }

    

    public function appendProperties($content){

        $this->properties .= PHP_EOL . $content . PHP_EOL;
        

    }
    // Method to append methods
    public function appendMethod($content){

        $this->methods .= PHP_EOL . $content . PHP_EOL;
        

    }

    public function setTable($name){

        $this::appendProperties(  "\t" . 'protected $table = "'.$name.'"; ' );


    }

    public function setFields(){

        $func = "\t" . 'protected $fillable = ["id"';

        foreach ($this->table["fields"] as $item) {

            $func .= ',"' . $item['name'] . '"';

        }

        $func .= '];';

        $this::appendProperties( $func );


    }


    public function scaffolding(){

        $this::setTable($this->tableName);

        $this::setFields();

        $this::save();

    }


   
    

    // Method to create file 
    public function save(){

        $this->document = $this->header;

        $this->document.= $this->properties;

        $this->document.= $this->methods;

        $this->document.= $this->footer;
        
      

        // Return a boolean to process completed
        return Storage::disk('models')->put($this->appNamePath.'/'.$this->modelName.".php",  $this->document);

    }

    
}
