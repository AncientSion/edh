<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(

                'debug' => '/server/debug.php',
                'manager' => '/server/manager.php',
                'dbmanager' => '/server/dbManager.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }


       // if(class_exists("Omega", false)) {
       //     echo  ("yes!");
      //  } else ("no!");
    }
);