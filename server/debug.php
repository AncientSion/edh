
<?php

    class Debug {

        public static $file;

        static function open(){
            static::$file = fopen(__DIR__."/edh.log", "a");
            //static::log("open");
        }  

        static function close(){
            //static::log("close");
            fclose(static::$file);
        }

        static function db(){
            return array("root", 147147);
        }

        static function log($string){
            //file_put_contents('/tmp/fire.log', $string."\n", FILE_APPEND);
            fwrite(static::$file, $string."\n");
        }

        static function trace(){
            ob_start(); 
            debug_print_backtrace(); 
            $trace = ob_get_contents(); 
            ob_end_clean(); 

            // Remove first item from backtrace as it's this function which 
            // is redundant. 
            $trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1); 

            // Renumber backtrace items. 
            //$trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace); 

        	static::log($trace);
        }
    }
?>