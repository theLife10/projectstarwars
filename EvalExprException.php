<?php
include_once 'Fall19.php';
    class EvalExprException extends Exception {
        public function __construct($m) {
            echo Fall19::$EOL . "Parsing Exception: ". $m .Fall19::$EOL; 
        }   
    }
?>