<?php

namespace App\Http\Controllers\Administration\Classes;





class MethodsInterface
{
    

    
    /*
    Array
    To add other functionality into controller, must add a row with method name, type, and function in blank
    The you must add a method in this Class
    */
    public $interface = [
        ["name" => 'get', "type" => "get", "function" => '' ],
        ["name" => 'find', "type" => "post", "function" => '' ],
        ["name" => 'delete', "type" => "post", "function" => '' ],
        ["name" => 'update', "type" => "post", "function" => '' ],
        ["name" => 'create', "type" => "post", "function" => '' ],
    ];

    private $tableName;
    private $table;
    private $security;
    private $token;

    public function __construct($tableName, $table, $security = false, $token = ''){

        $this->tableName = $tableName;
        $this->table = $table;
        $this->security = $security;
        $this->token = $token;


        for($i = 0; $i < count($this->interface); $i++){
            $func = $this->interface[$i]["name"];
            $this->interface[$i]["function"] = eval('return $this::' . $this->interface[$i]["name"] . '();');
        }

    }


    public function get(){
        
        $func = '';

        $func .= "\t\t" . 'public function get(Request $request){ ' . PHP_EOL;
        
        if($this->security){
            $func .= "\t\t\t" . 'if($request->token != "'.$this->token.'"){ ' . PHP_EOL;
            $func .= "\t\t\t" . 'return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); ' . PHP_EOL;
            $func .= "\t\t\t" . '} else { ' . PHP_EOL;
        }

        $func .= "\t\t\t" . 'return '.$this->tableName.'::get(); ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . '} ' . PHP_EOL;
        }

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    public function find(){
        
        $func = '';

        $func .= "\t\t" . 'public function find(Request $request){ ' . PHP_EOL;
        if($this->security){
            $func .= "\t\t\t" . 'if($request->token != "'.$this->token.'"){ ' . PHP_EOL;
            $func .= "\t\t\t" . 'return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); ' . PHP_EOL;
            $func .= "\t\t\t" . '} else { ' . PHP_EOL;
        }

        $func .= "\t\t\t" . 'return '.$this->tableName.'::find($request->id); ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . '} ' . PHP_EOL;
        }

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    public function delete(){
        
        $func = '';

        $func .= "\t\t" . 'public function delete(Request $request){ ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . 'if($request->token != "'.$this->token.'"){ ' . PHP_EOL;
            $func .= "\t\t\t" . 'return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); ' . PHP_EOL;
            $func .= "\t\t\t" . '} else { ' . PHP_EOL;
        }

        $func .= "\t\t\t" . '$e = '.$this->tableName.'::find($request->id); ' . PHP_EOL;
        $func .= "\t\t\t" . 'return $e->delete(); ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . '} ' . PHP_EOL;
        }

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;


    }

    public function update(){
        
        $func = '';

        $func .= "\t\t" . 'public function update(Request $request){ ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . 'if($request->token != "'.$this->token.'"){ ' . PHP_EOL;
            $func .= "\t\t\t" . 'return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); ' . PHP_EOL;
            $func .= "\t\t\t" . '} else { ' . PHP_EOL;
        }
        
        $func .= "\t\t\t" . '$e = '.$this->tableName.'::find($request->id); ' . PHP_EOL;

        foreach ($this->table["fields"] as $item) {

            $func .= "\t\t\t" . '$e->'.$item['name'].' = $request->'.$item['name'].'; ' . PHP_EOL;

        }
        $func .= "\t\t\t" . '$result = $e->save(); ' . PHP_EOL;
        $func .= "\t\t\t" . 'return $result; ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . '} ' . PHP_EOL;
        }

        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;

    }

    public function create(){
        
        $func = '';

        $func .= "\t\t" . 'public function create(Request $request){ ' . PHP_EOL;

        if($this->security){
            $func .= "\t\t\t" . 'if($request->token != "'.$this->token.'"){ ' . PHP_EOL;
            $func .= "\t\t\t" . 'return response("Token Access Denied", 200)->header("Content-Type", "text/plain"); ' . PHP_EOL;
            $func .= "\t\t\t" . '} else { ' . PHP_EOL;
        }
        
        $func .= "\t\t\t" . '$e = new '.$this->tableName.'; ' . PHP_EOL;

        foreach ($this->table["fields"] as $item) {

            $func .= "\t\t\t" . '$e->'.$item['name'].' = $request->'.$item['name'].'; ' . PHP_EOL;

        }

        $func .= "\t\t\t" . 'if($e->save()){ ' . PHP_EOL;
        $func .= "\t\t\t\t" . 'return response()->json(["success" => true, "id" => $e->id]); ' . PHP_EOL;
        $func .= "\t\t\t" . '}else{ ' . PHP_EOL;
        $func .= "\t\t\t\t" . 'return response()->json(["success" => false]); ' . PHP_EOL;
        $func .= "\t\t\t" . '} ' . PHP_EOL;


        if($this->security){
            $func .= "\t\t\t" . '} ' . PHP_EOL;
        }
        
        $func .= "\t\t" . '} ' . PHP_EOL;

        return $func;

    }

    
}
