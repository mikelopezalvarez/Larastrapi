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

    public $prefix;

    private $footer;

    private $document;

    private $modelName;

    private $relations = [];

    private $belong = [];




   

    public function __construct($appNamePath, $modelName, $table, $prefix, $relations = 0){

        $this->appNamePath = $appNamePath;
        $this->table = $table;
        $this->tableName = $table["name"];
        $this->modelName = $modelName;
        $this->prefix = $prefix;

        if($relations != 0){
            foreach ($relations as $item) {

                if($this->modelName == $item['table1']){
                    $this->relations[] = $item;
                }

                if($this->modelName == $item['table2']){
                    $this->belong[] = $item;
                }
    
            }
        }


        $this->header = '<?php' . PHP_EOL;
        // Include the neccesary class
        $this->header .= 'namespace App\Octapi' . '\\' . $this->appNamePath . ';' . PHP_EOL . PHP_EOL;
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

        $this::appendProperties(  "\t" . 'protected $table = "'.$this->prefix.'_'.$name.'"; ' );


    }

    public function setFields(){

        $func = "\t" . 'protected $fillable = ["id"';

        foreach ($this->table["fields"] as $item) {

            $func .= ',"' . $item['name'] . '"';

        }

        $func .= '];';

        $this::appendProperties( $func );


    }

    public function setRelations(){

        $func = "";

        foreach ($this->relations as $item) {

            $table2 = $item["table2"];
            $field = $item["field"];
            $path = 'App\Octapi\\'.  $this->appNamePath . '\\' .$table2;

            if($item["relationship"] == 1){

                $func .= "\t" . 'public function '.$table2.'(){' . PHP_EOL;

                $func .= "\t\t" . 'return $this->hasOne("'.$path.'", "'.$field.'");' . PHP_EOL;
            
                $func .= "\t" . '}' . PHP_EOL;

            }else{

                $func .= "\t" . 'public function '.$table2.'(){' . PHP_EOL;


                $func .= "\t\t" . 'return $this->hasMany("'.$path.'", "'.$field.'");' . PHP_EOL;
        
                $func .= "\t" . '}' . PHP_EOL;

            }

        }


        $this::appendMethod( $func );


    }


    public function setBelong(){

        $func = "";

        foreach ($this->belong as $item) {

            $table1 = $item["table1"];
            $field = $item["field"];
            $path = 'App\Octapi\\'.  $this->appNamePath . '\\' .$table1;

            //if($item["relationship"] == 1){

                $func .= "\t" . 'public function '.$table1.'(){' . PHP_EOL;

                $func .= "\t\t" . 'return $this->belongsTo("'.$path.'", "'.$field.'");' . PHP_EOL;
            
                $func .= "\t" . '}' . PHP_EOL;

            //}

        }


        $this::appendMethod( $func );


    }


    public function scaffolding(){


        $this::save();

    }


   
    

    // Method to create file 
    public function save(){

        $this::setTable($this->modelName);

        $this::setFields();

        $this::setRelations();

        $this::setBelong();

        $this->document = $this->header;

        $this->document.= $this->properties;

        $this->document.= $this->methods;

        $this->document.= $this->footer;
        
      

        // Return a boolean to process completed
        return Storage::disk('models')->put($this->appNamePath.'/'.$this->modelName.".php",  $this->document);

    }

    
}
