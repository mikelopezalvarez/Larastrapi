<?php

namespace App\Http\Controllers\Administration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

//Models
use App\App;

class DataManagementController extends Controller
{
    private $beforeApp;

    private $beforeTabComp;

    private $afterTabComp;

    private $removedTables;

    private $createTables;

    private $app;
    
    private $appName = 'Seguros Multiples$&$34534523';
    
    private $appNamePath;
    
    private $appActive;

    private $appSecurity;

    private $appToken;

    private $id;

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

    private $migrationName;

    public function saveAppConfiguration(Request $request){

        // Get and decode json data
        $data = $request->data;
        $this->appName = $data["name"]; //Save App Name
        $this->appNamePath = $data["alias"]; //Save Folder Name

        $this->id = $request->id;

        $this->app = $request->app;

        $this->beforeApp = $request->beforeApp;

        // Get tables to remove
        $this::getTablesToDrop();
        // Get tables to create
        $this::getTablesToCreate();

        // Counting to know if create migration or not
        $migration = 0;

        if($this->removedTables){
            $migration++;
        }

        if($this->createTables){
            $migration++;
        }

        // If tables dont have changes 
        if($migration > 0){
            // Create migration
            if($this::createMigration() == true){
            Artisan::call('migrate');
            }
        }

        $this::updAppTable();



        // // Get and decode json data
        // $app = json_decode($request->app, true);
        // dd($app);
        // $beforeApp = json_decode($request->beforeApp, true);

        // // Populate app data
        // $this->app = $app;

        // // This is the before configuration to compare and remake or update the migration
        // $this->beforeApp = $beforeApp;

        // //$this->appName = $app["name"];

        // //$this->appNamePath = $this::cleanToName($app["name"]);

        // $this->appActive = $app["name"];

        // $this->appSecurity = $app["security"]["active"];

        // $this->appToken = $app["security"]["token"];

        // $this->app["tables"] = $app["tables"];





        
       // return Artisan::call('migrate');
       // return Artisan::call('make:migration create_products_table ');

        // Call the Scalffolding Methods to Create all DB Laravel Elements
        //$this::scalffolding();
        //$this::createMigration();
        //if($this::createMigration() == true){
           // Artisan::call('migrate');
        //}

        // $this::getTablesToDrop();

        // $this::getTablesToCreate();

        // $this::createMigration();




    }

    private function updAppTable(){
        
        $app = App::find($this->id);
        $app->public = (($this->app["active"]) ? 1 : 0);
        $app->security = (($this->app["security"]["active"]) ? 1 : 0);
        $app->token = $this->app["security"]["token"];
        $app->structure =  json_encode($this->app);

        $app->save();
    }

    

    private function genPrefix($len = 5) {
        $word = array_merge(range('a', 'z'), range('a', 'z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    public function createApp(Request $request){

        // Get and decode json data
        $data = $request->data;

        // Formating table name
        $this->appNamePath = $this::cleanToName($data["name"]);

        // Create Controller Folder
        if($this::createAppControllerFolder() != true){
            return response('Error in Controller Folder Creation', 200)
                  ->header('Content-Type', 'text/plain');
        }
        // Create Model Folder
        if($this::createAppModelFolder() != true){
            return response('Error in Model Folder Creation', 200)
                  ->header('Content-Type', 'text/plain');
        }
        // Create Routes Folder
        if($this::createAppRoutesFolder() != true){
            return response('Error in Routes Folder Creation', 200)
                  ->header('Content-Type', 'text/plain');
        }

        //Instance App model
        $app = new App;

        //Fill the fields
        $app->name = $data["name"];
        $app->alias = $this->appNamePath;
        $app->prefix =  $this::genPrefix(5);
        $app->app_description = $data["descrip"];
        $app->public = (($data["public"]) ? 1 : 0);
        $app->security = (($data["security"]["active"]) ? 1 : 0);
        $app->token = $data["security"]["token"];
        $app->structure =  json_encode($request->data);
        $app->active = 1;
        $app->users = 1;
        
        // Save app in database
        if($app->save()){
            return response()->json(['success' => true, "id" => $app->id]);
        }else{
            return response()->json(['success' => false]);
        }

        
        

    }

    private function createAppScalffolding(){

        // Get all tables to Drop
        $this::getTablesToDrop();

        // Get all tables to Create
        $this::getTablesToCreate();

        // Make:Migration
        if($this::createMigration() == true){
            Artisan::call('migrate');
        }

        // Make:Controllers
        if($this::createAppControllerFolder() == true){
            foreach ($this->app["tables"] as $item) {
                $this::createController($item);
            }
        }

        // Make:Models
        if($this::createAppModelFolder() == true){
            foreach ($this->app["tables"] as $item) {
                $this::createModel($item);
            }
        }

        // Make:Routes
        if($this::createAppRoutesFolder() == true){
            $this::registerRoutes();
        }

    }

    private function createMigration(){
        
        $dropIfExist = '';

        // Init var to create migration file content
        $res = '<?php' . PHP_EOL;
        // Include the neccesary class
        $res .= 'use Illuminate\Support\Facades\Schema; ' . PHP_EOL;
        $res .= 'use Illuminate\Database\Schema\Blueprint;' . PHP_EOL;
        $res .= 'use Illuminate\Database\Migrations\Migration;' . PHP_EOL . PHP_EOL;
        // Name of class
        $res .= 'class AutomaticProcessor extends Migration' . PHP_EOL;
        $res .= '{'. PHP_EOL . PHP_EOL . PHP_EOL;

        $res .= "\t" . 'public function up(){'. PHP_EOL . PHP_EOL;

        // Verify if exist table to removed to add drop in up method of migration
        if ($this->removedTables) {
            $res .= "\t\t" . '// All Tables to Remove' . PHP_EOL;
            foreach ($this->removedTables as $item) {
                $res .= "\t\t" . 'Schema::dropIfExists("'.$item.'");' . PHP_EOL;
            }
        }

        $res .= PHP_EOL;
        $res .= "\t\t" . '// All Tables to Create' . PHP_EOL;
        // Create the new tables in up method of migration
        if ($this->createTables) {
            // Foreach to prepare each table
            foreach ($this->createTables as $item) {

                $res .= $this::prepareTableBlueprint($item);

                $dropIfExist .= "\t\t" . 'Schema::dropIfExists("'.$this::cleanToName($item["name"]).'");' . PHP_EOL;

            }
        }
        
        $res .= "\t" . '}'. PHP_EOL . PHP_EOL . PHP_EOL;
        $res .=  "\t" . 'public function down(){'. PHP_EOL . PHP_EOL ;
        
        $res .= $dropIfExist . PHP_EOL;

        $res .=  "\t" . '}'. PHP_EOL . PHP_EOL . PHP_EOL;
        $res .= '}';

        // Save the name of migration in prop (0000_00_00_000000_automatic_processor.php)
        $this->migrationName = date('Y_m_d_'.Carbon::now()->format('His'),time()) . "_automatic_processor.php";

        // Create migration in database/migrations
        return Storage::disk('migration')->put($this->migrationName, $res);
        
        //dd(Storage::disk("migration")->get($this->migrationName));
        
    } 


    private function prepareTableBlueprint($table){

        // Get the name table
        $tableName = $this::cleanToName($table["name"]);

        $res = "\t\t" . "if (!Schema::hasTable('".$tableName."')) { " . PHP_EOL;
            $res .= "\t\t\t" . 'Schema::create("'.$tableName.'", function (Blueprint $t) {' . PHP_EOL;
        
                $res .= "\t\t\t\t" . '$t->engine = "InnoDB"; ' . PHP_EOL;
                $res .= "\t\t\t\t" . '$t->increments("id"); ' . PHP_EOL;

        // Aqui tengo que mirar si existen las columnas y si el tipo es igual, no las agrego la migracion.....
        
        // Create table
        foreach ($table["fields"] as $item) {

            $res .= "\t\t\t\t" . '$t->'.$item['type'].'("'.$this::cleanToName($item['name']).'")->nullable(); ' . PHP_EOL;

        }
        $res.= "\t\t\t" . '});'. PHP_EOL;
        
        $res.= "\t\t" .  '} else {' . PHP_EOL;

            $res .="\t\t\t" . 'Schema::table("'.$tableName.'", function (Blueprint $t) {' . PHP_EOL;
        // Update table
        foreach ($table["fields"] as $item) {

            $res .= "\t\t\t\t" . '$t->'.$item['type'].'("'.$this::cleanToName($item['name']).'")->nullable()->change(); ' . PHP_EOL;
        
        }
        $res.= "\t\t\t" .'});'. PHP_EOL;

        $res.="\t\t" . '} ' . PHP_EOL . PHP_EOL;

        return $res;



    }
    // Method to Get All New Tables Compared with Before Configuration
    private function getTablesToCreate(){

        // if($this->beforeApp["tables"]){
            // Foreach to store all table name of before config
            foreach ($this->beforeApp["tables"] as $item) {

                $beforeTabComp[] = $item["name"];

            }
            
            // Foreach to store all table name of new config
            foreach ($this->app["tables"] as $item) {

                $afterTabComp[] = $item["name"];
                

            }
           

            // Store the array with new tables names
            $newTablesNames = array_diff($afterTabComp,$beforeTabComp);
            
        

            // Foreach to get the detail of all new tables
            foreach ($newTablesNames as $item) {

                $this->createTables[] = $this::getTableDetail($item);


            }

            // Return new tables info
            return $this->createTables;

        // }else{

        //     $this->createTables = $this->app["tables"];
        //     return $this->createTables;
        // }

    }

    private function getFieldsToCreate(){

        if($this->beforeApp["tables"]){
            // Foreach to store all table name of before config
            foreach ($this->beforeApp["tables"] as $item) {

                $beforeTabComp[] = $item["name"];

            }
            // Foreach to store all table name of new config
            foreach ($this->app["tables"] as $item) {

                $afterTabComp[] = $item["name"];

            }

            // Store the array with new tables names
            $newTablesNames = array_diff($afterTabComp,$beforeTabComp);

            

        

            // Foreach to get the detail of all new tables
            foreach ($newTablesNames as $item) {

                $this->createTables[] = $this::getTableDetail($item);


            }

            // Return new tables info
            return $this->createTables;

        }else{

            $this->createTables = $this->app["tables"];
            return $this->createTables;
        }

        


    }

    //Method to Get All Array Table by Name of Table
    private function getTableDetail($name){


        foreach ($this->app["tables"] as $item) {

            if($item["name"] == $name){
                return $item;
            }

        }

   

    }

    private function ifTableIsExistInNewApp($name){


        foreach ($this->app["tables"] as $item) {

            if ($item["name"] == $name){
                return true;
            }else{
                return false;
            }

        }

    }

    private function getTablesToDrop(){

        if($this->beforeApp["tables"]){
            // Foreach to store all table name of before config
            foreach ($this->beforeApp["tables"] as $item) {

                if($this::ifTableIsExistInNewApp($item["name"]) == false){
                    $this->removedTables[] = $this::cleanToName($item["name"]);
                }

            }

            // Return removed tables info
            return  $this->removedTables;
        }else{

            $this->removedTables = [];

            return $this->removedTables;
        }

    }

    // Method to clean App Name to Remove Special Character and Numbers
    public function cleanToName($name){
        // Remove empty space
        $name = str_replace(' ', '', $name);
        // Remove special character
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        // Return without numbers
        return preg_replace('/[0-9]+/', '', $name);
        
    }
    // Method to Create the App Controller Folder
    private function createAppControllerFolder(){

        // Return a boolean to process completed
        return Storage::disk('controllers')->makeDirectory($this->appNamePath);


    }

    // Method to Create the App Controller 
    private function createController($table){

        // Filtering file name
        $fileName = $this::cleanToName($table["name"]) . 'Controller.php';

        // Prepare the Class scheme inside the controller
        $contents = '<?php '.$fileName.' ?>';


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

        // Filtering file name
        $fileName = $this::cleanToName($table["name"]) . '.php';
        
        // Prepare the Class scheme inside the model
        $contents = '<?php echo "Hello World"; ?>';

        // Return a boolean to process completed
        return Storage::disk('models')->put($this->appNamePath.'/'.$fileName, $contents);

    }


    private function createAppRoutesFolder(){
        // Return a boolean to process completed
        return Storage::disk('routes')->makeDirectory($this->appNamePath);
    }

    private function registerRoutes(){
        
        foreach ($this->app["tables"] as $item) {
            
            // Get clean table name 
            $tableName = $this::cleanToName($item["name"]);
            // Get file name to set controller
            $controllerName = $this::cleanToName($item["name"]) . 'Controller';
            
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
            $fileName = $this::cleanToName($item["name"]) . '.php';

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
