<?php
include_once('Token.php');
include_once('Tokenizer.php');
include_once('TokenType.php');


    class Tokenizer 
    {
        private $e = array();
        private $i;
        public $currentChar;

        public function __construct($s)
        {
            $this->e = str_split($s);
            $this->i = 0;
        }
        public function nextToken(){
            
            while($this->i < count($this->e) && (strpos("\n\t\r",$this->e[$this->i]) !== false )){
                $this->i++;
            }

            if($this->i >= count($this->e)){
                return new Token(TokenType::EOF,"");
            }

            $inputString = "";
            while ( $this->i < count($this->e) && (strpos("0123456789", $this->e[$this->i]) !== false)) {
                $inputString .= $this->e[$this->i++];
            }
            
            if("" !== $inputString){
                return new Token(TokenType::INT,$inputString);
            }

            switch($this->e[$this->i++]){
                case '[' :
                return new Token(TokenType::LSQUAREBRACKET,"[");
                
                case ']':
                return new Token(TokenType::RSQUAREBRACKET,"]");

                case '(':
                return new Token(TokenType::LPAREN,"(");

                case ')':
                return new Token(TokenType::RPAREN,")");

                case '+':
                return new Token(TokenType::PLUS,"+");

                case '-':
                return new Token(TokenType::MINUS,"-");
                
                case '.':
                return new Token(TokenType::CONCAT,".");

                default:
                return new Token(TokenType::OTHER,"");
            }
        }
    }
?>