<?php 


$projects  = scandir(dirname(__FILE__) . '/Octapi');

foreach ($projects as $f) {

    if($f != "." && $f != ".."){
       
        $folders  = scandir(dirname(__FILE__) . '/Octapi/'.$f);


        foreach ($folders as $item) {
            $ext = pathinfo($item, PATHINFO_EXTENSION);
            if($ext == "php"){
                include "Octapi/" .$f. "/".$item;
            }     
        }


    }
}



