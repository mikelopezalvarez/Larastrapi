<?php

namespace App\Http\Controllers\Administration;

// Other Libraries
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

// My Libraries
use App\Http\Controllers\Administration\Classes\MikeMigration;
use App\Http\Controllers\Administration\Classes\MikeController;
use App\Http\Controllers\Administration\Classes\MikeModel;
use App\Http\Controllers\Administration\Classes\MikeRoute;



//Models
use App\App;

class DataManagementController extends Controller
{
    /*
        @array
    */
    private $beforeApp;
    /*
        @array
    */
    private $beforeTabComp;
    /*
        @array
    */
    private $afterTabComp;
    /*
        @array
    */
    private $removedTables;
    /*
        @array
    */
    private $createTables;
    /*
        @array
    */
    private $app;
    /*
        @string
    */
    private $appName;
    /*
        @string
    */
    private $appNamePath;
    /*
        @integer
    */
    private $appActive;
    /*
        @string
    */
    private $appSecurity;
    /*
        @string
    */
    private $appToken;
    /*
        @integer
    */
    private $id;
    /*
        @array
    */
    private $tables;
    /*
        @string
    */
    private $migrationName;
    /*
        @array
    */
    private $modifiedTableFields;
    /*
        @array
    */
    private $json;
    /*
        @array
    */
    private $droppedTables;
     /*
        @array
    */
    private $newTables;
     /*
        @array
    */
    private $changedTables;
     /*
        @array
    */
    private $droppedFields;
     /*
        @array
    */
    private $renamedFields;
     /*
        @array
    */
    private $renamedTables;

    public function saveAppConfiguration(Request $request){

        // Get and decode json data
        $data = $request->data;
        // Storage app name
        $this->appName = $data["name"]; 
        // Storage folder name
        $this->appNamePath = $data["alias"]; 
        // Storage app prefix
        $this->appPrefix = $data["prefix"]; 
        // Get app id
        $this->id = $request->id;
        // Storage all arrays elements to run the save config
        $this->app = $request->app;
        $this->beforeApp = $request->beforeApp;
        $this->json = $request->json;


        $this->droppedTables = $request->droppedTables;     // Tables Array
        $this->newTables = $request->newTables;             // Tables Array
        $this->changedTables = $request->changedTables;     // Tables Array
        $this->droppedFields = $request->droppedFields;     // [{name,type}]
        $this->renamedFields = $request->renamedFields;     // [{tableName,oldName,newName}]
        $this->renamedTables = $request->renamedTables;     // [{oldName,newName}]

       
        
        // Create Migration to drop old tables and create new tables
        $this::dropAndCreateTables();
        // Create Migration to editing existing tables
        $this::changedExistingTables();
        // Create Migration to drop all columns of all tables
        $this::droppedFields();
        // Create Migration to renamed old fields and tables
        $this::renamedTablesElements();

        
        // Update app structure and then run the migrations created
        if($this::updAppTable() > 0){
            Artisan::call('migrate');
        }

        // Remove Old Controllers
        $removeControllers =  Storage::disk('controllers')->allFiles($this->appNamePath);
        Storage::disk('controllers')->delete($removeControllers);
        // Remove Old Models
        $removeModels =  Storage::disk('models')->allFiles($this->appNamePath);
        Storage::disk('models')->delete($removeModels);
        // Remove Old Routes
        $removeRoutes =  Storage::disk('routes')->allFiles($this->appNamePath);
        Storage::disk('routes')->delete($removeRoutes);

        
        foreach ($this->app["tables"] as $item) {

            $controller = new MikeController( $this->appNamePath, $this::cleanToName($item["name"]), $item , $this->app["security"]["active"], $this->app["security"]["token"] );
            $controller->save();

            $method = new MikeModel( $this->appNamePath, $this::cleanToName($item["name"]), $item, $this->appPrefix, $this->app["relations"] );
            $method->save();
            
            $route = new MikeRoute( $this->appNamePath, $this::cleanToName($item["name"]), $item );
            $route->save();
        
        }
       



    }

    // Method to Drop and Create Tables Migration
    private function dropAndCreateTables(){
        
        $migration = new MikeMigration($this::genPrefix(10));
        $save = 0;

        // Prepare Dropped Tables
        if($this->droppedTables){
            $save++;
            foreach($this->droppedTables as $item){

                $migration->up("\t\t" . 'Schema::dropIfExists("'.$this->appPrefix.'_'.$item["name"].'"); ');

                $migration->down($this::prepareTableBlueprint($item));

                
                // Delete Model
                Storage::disk('models')->delete($this->appNamePath.'/'.$item["name"].'.php');
                // Delete Controller
                Storage::disk('controllers')->delete($this->appNamePath.'/'.$item["name"].'Controller.php');
                // Delete Routes
                //...

            }
        }
        // Prepare New Tables 
        if($this->newTables){
            $save++;
            foreach($this->newTables as $item){


                $migration->up($this::prepareTableBlueprint($item));

                $migration->down("\t\t" . 'Schema::dropIfExists("'.$this->appPrefix.'_'.$item["name"].'"); ');

            }
        }
        // If Exist Dropped pr New Tables 
        if($save > 0){
            $migration->save();
        }
        


    }

    private function changedExistingTables(){

        if($this->changedTables){


            $migration = new MikeMigration($this::genPrefix(10));

            foreach($this->changedTables as $item){

                $migration->up($this::prepareTableBlueprint($item));

                $migration->down("\t\t" . 'Schema::dropIfExists("'.$this->appPrefix.'_'.$item["name"].'"); ');
    
            }


            $migration->save();


        }
        
    }

    private function droppedFields(){

        $migration = new MikeMigration($this::genPrefix(10));

        if($this->droppedFields){

        
            foreach($this->droppedFields as $item){


                $migration->up($this::prepareBlueprintDroppedColumn($item, 'up'));

                $migration->down($this::prepareBlueprintDroppedColumn($item, 'down'));

            }

            $migration->save();

        }
        
    }



    private function renamedTablesElements(){

        $migration = new MikeMigration($this::genPrefix(10));
        $save = 0;
        foreach($this->renamedFields as $item){
            $save++;
            $migration->up($this::prepareBlueprintFieldRename($item, 'up'));

            $migration->down($this::prepareBlueprintFieldRename($item, 'down'));

        }

        foreach($this->renamedTables as $item){

            $save++;
            $migration->up("\t\t" . 'Schema::rename("'.$this->appPrefix.'_'.$item["oldName"].'", "'.$this->appPrefix.'_'.$item["newName"].'"); ');

            $migration->down("\t\t" . 'Schema::rename("'.$this->appPrefix.'_'.$item["newName"].'", "'.$this->appPrefix.'_'.$item["oldName"].'"); ');

        }

        if($save > 0){
            $migration->save();
        }

    }

    private function prepareBlueprintDroppedColumn($item, $type){

        $tableName = $this::cleanToName($item["tableName"]);
        $name = $this::cleanToName($item["field"]["name"]);
        $fieldType = $item["field"]["type"];

        if($type == 'up'){ 

            $res = "\t\t\t" . 'Schema::table("'.$this->appPrefix.'_'.$tableName.'", function(Blueprint $t){ ' . PHP_EOL;
            $res .= "\t\t\t\t" . '$t->dropColumn("'.$name.'"); ' . PHP_EOL;
            $res .= "\t\t\t" . '}); ' . PHP_EOL . PHP_EOL;
    

        }else{


            $res = "\t\t\t" . 'Schema::table("'.$this->appPrefix.'_'.$tableName.'", function(Blueprint $t){ ' . PHP_EOL;
            $res .= "\t\t\t\t" . '$t->'.$fieldType.'("'.$name.'")->nullable(); ' . PHP_EOL;
            $res .= "\t\t\t" . '}); ' . PHP_EOL . PHP_EOL;
           

        }

        return $res;
 

    }

    private function prepareBlueprintFieldRename($item, $type){

        $tableName = $this::cleanToName($item["tableName"]);
        $oldName = $this::cleanToName($item["oldName"]);
        $newName = $this::cleanToName($item["newName"]);

        if($type == 'up'){


            $res = "\t\t\t" . 'Schema::table("'.$this->appPrefix.'_'.$tableName.'", function(Blueprint $t){ ' . PHP_EOL;
            $res .= "\t\t\t\t" . '$t->renameColumn("'.$oldName.'", "'.$newName.'"); ' . PHP_EOL;
            $res .= "\t\t\t" . '}); ' . PHP_EOL . PHP_EOL;
    
         

        }else{


            $res = "\t\t\t" . 'Schema::table("'.$this->appPrefix.'_'.$tableName.'", function(Blueprint $t){ ' . PHP_EOL;
            $res .= "\t\t\t\t" . '$t->renameColumn("'.$newName.'", "'.$oldName.'"); ' . PHP_EOL;
            $res .= "\t\t\t" . '}); ' . PHP_EOL . PHP_EOL;
    

        }

        return $res;
 

    }



    
    // Method to Update App Table
    private function updAppTable(){
        
        $app = App::find($this->id);
        $app->public = (($this->app["active"]) ? 1 : 0);
        $app->security = (($this->app["security"]["active"]) ? 1 : 0);
        $app->token = $this->app["security"]["token"];
        $app->structure =  json_encode($this->json);

        $result = $app->save();

        return $result;
    }

    

    private function genPrefix($len = 5) {
        $word = array_merge(range('a', 'z'), range('a', 'z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }

    // Method to Create App
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


    private function prepareTableBlueprint($table){

        // Get the name table
        $tableName = $this::cleanToName($table["name"]);

        $res = "\t\t" . "if (!Schema::hasTable('".$this->appPrefix."_".$tableName."')) { " . PHP_EOL;
            $res .= "\t\t\t" . 'Schema::create("'.$this->appPrefix.'_'.$tableName.'", function (Blueprint $t) {' . PHP_EOL;
        
                $res .= "\t\t\t\t" . '$t->engine = "InnoDB"; ' . PHP_EOL;
                $res .= "\t\t\t\t" . '$t->increments("id"); ' . PHP_EOL;

        // Aqui tengo que mirar si existen las columnas y si el tipo es igual, no las agrego la migracion.....
        
        // Create table
        foreach ($table["fields"] as $item) {

            $res .= "\t\t\t\t" . '$t->'.$item['type'].'("'.$this::cleanToName($item['name']).'")->nullable(); ' . PHP_EOL;

        }
        $res.= "\t\t\t" . '});'. PHP_EOL;
        
        $res.= "\t\t" .  '} else {' . PHP_EOL;

            $res .="\t\t\t" . 'Schema::table("'.$this->appPrefix.'_'.$tableName.'", function (Blueprint $t) {' . PHP_EOL;
        // Update table
        foreach ($table["fields"] as $item) {

            $res .= "\t\t\t\t" . '$t->'.$item['type'].'("'.$this::cleanToName($item['name']).'")->nullable()->change(); ' . PHP_EOL;
        
        }
        $res.= "\t\t\t" .'});'. PHP_EOL;

        $res.="\t\t" . '} ' . PHP_EOL . PHP_EOL;

        return $res;



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
