<?php

namespace App\Http\Controllers\Administration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
//use File;

class DataManagementController extends Controller
{
    
    private $appName = 'Seguros Multiples$&$34534523';

    private $appNamePath;

    private $tables = [
            [
                "name" => "Products",
                "fields" => [],
                "users" => [],
                "active" => true,
                "options" => [ "get" => true, "add" => true, "del" => true, "upd" => true ]
            ],
            [
                "name" => "Users",
                "fields" => [],
                "users" => [],
                "active" => true,
                "options" => [ "get" => true, "add" => true, "del" => true, "upd" => true ]
            ]

    ];

    public function prepareData(Request $request){

       // $dir = 'test/';

        //File:makeDirectory($dir, 0777,true);
        //$res = Storage::disk('controllers')->makeDirectory($dir);

        //dd($this->createAppControllerFolder());

        // dd(Storage::disk('controllers'));

        $this->appNamePath = $this::cleanToControllerName($this->appName);

        $this::createAppRoutesFolder();

        $this::registerRoutes();

        //$this::createController('Articulos');
        //$this::createModel('Articulos');

        //$this::createAppModelFolder();

        //Storage::disk('controllers')->put('SegurosMultiples/file.txt', 'Contents');


    }

    // Method to clean App Name to Remove Special Character and Numbers
    public function cleanToControllerName($name){
        // Remove empty space
        $name = str_replace(' ', '', $name);
        // Remove special character
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        // Return without numbers
        return preg_replace('/[0-9]+/', '', $name);
        
    }
    // Method to Create the App Controller Folder
    private function createAppControllerFolder(){

        // Props represent the name of folder app formatted
        $this->appNamePath = $this::cleanToControllerName($this->appName);

        // Return a boolean to process completed
        return Storage::disk('controllers')->makeDirectory($this->appNamePath);


    }

    // Method to Create the App Controller 
    private function createController($table){
        // Prepare the Class scheme inside the controller
        $contents = '<?php echo "Hello World"; ?>';

        // Filtering file name
        $fileName = $this::cleanToControllerName($table) . 'Controller.php';

        // Return a boolean to process completed
        return Storage::disk('controllers')->put($this->appNamePath.'/'.$fileName, $contents);

    }


    // Method to Create the App Model 
    private function createAppModelFolder(){
         
         // Return a boolean to process completed
         return Storage::disk('models')->makeDirectory($this->appNamePath);

    }

    // Method to Create the App Controller 
    private function createModel($table){
        // Prepare the Class scheme inside the model
        $contents = '<?php echo "Hello World"; ?>';

        // Filtering file name
        $fileName = $this::cleanToControllerName($table) . '.php';

        // Return a boolean to process completed
        return Storage::disk('models')->put($this->appNamePath.'/'.$fileName, $contents);

    }


    private function createAppRoutesFolder(){
        // Return a boolean to process completed
        return Storage::disk('routes')->makeDirectory($this->appNamePath);
    }

    private function registerRoutes(){
        
        foreach ($this->tables as $item) {
            
            // Get clean table name 
            $tableName = $this::cleanToControllerName($item["name"]);
            // Get file name to set controller
            $controllerName = $this::cleanToControllerName($item["name"]) . 'Controller';
            
            // Init var of content
            $contents = "<?php " . PHP_EOL;

            // Append options configuration tables CRUD
            if($item["options"]["get"] == true){
                $fullPath = "/".$this->appNamePath."/".$tableName."/get','Larastrapi\\".$this->appNamePath.'\\'.$controllerName."@get";
                $contents.= " Route::get('".$fullPath."')->name('get'); " . PHP_EOL;
            }
            
            if($item["options"]["add"] == true){
                $fullPath = "/".$this->appNamePath."/".$tableName."/add','Larastrapi\\".$this->appNamePath.'\\'.$controllerName."@add";
                $contents.= " Route::get('".$fullPath."')->name('add'); " . PHP_EOL;
            }

            if($item["options"]["del"] == true){
                $fullPath = "/".$this->appNamePath."/".$tableName."/del','Larastrapi\\".$this->appNamePath.'\\'.$controllerName."@del";
                $contents.= " Route::get('".$fullPath."')->name('del'); " . PHP_EOL;
            }

            if($item["options"]["upd"] == true){
                $fullPath = "/".$this->appNamePath."/".$tableName."/upd','Larastrapi\\".$this->appNamePath.'\\'.$controllerName."@upd";
                $contents.= " Route::get('".$fullPath."')->name('upd'); " . PHP_EOL;
            }

            // Close php file contents
            $contents.= "?>";

            // Filtering file name
            $fileName = $this::cleanToControllerName($item["name"]) . '.php';

            // Create route file (get, add, del, upd)
            $this::createRoute( $fileName, $contents );
            
            // Append this route in the web.php
            $this::appendRoute($fileName);
        
        }

       
        


    }

    // Method to create route file in other file to then append in web.php
    private function createRoute($fileName, $contents){
        
        // Return a boolean to process completed
        return Storage::disk('routes')->put($this->appNamePath.'/'.$fileName, $contents);
        

    }

    // Method append the route in web.php
    private function appendRoute($fileName){

        $contents = ' require_once "Larastrapi/'.$this->appNamePath.'/'.$fileName.'"; ';
        return Storage::disk('routes_append')->append("web.php", $contents);

    }


}
