<?php
    class Token{
        public $type;
        public $value;

        public function __construct($theType, $theValue=""){
            $this->type = $theType;
            $this->value = $theValue;
        }
    }
?>