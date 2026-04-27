<?php
    function ClassLoader($class) {
        $namespace="App\\";

        if(strpos($class, $namespace)===0){
            $relative=substr($class, strlen($namespace));
            $file=APP . '/' . str_replace("\\", "/", $relative) . '.php';

            if(file_exists($file)){
                require_once $file;
            }
        }
    }

    spl_autoload_register("ClassLoader");