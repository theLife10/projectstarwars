<?php

    class EvalExprException extends Exception {
        public function __construct($m) {
            echo "Parsing Exception: ". $m ; 
        }   
    }
?>