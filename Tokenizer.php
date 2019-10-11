<?php
include_once 'TokenType.php';
include_once 'Token.php';
    class Tokenizer 
    {
        private $e = array();
        private $i;
        public $currentChar;

        public function __construct($s)
        {
            $this->e = $this->s.str_split($s);
            $this->i = 0;
        }
        public function nextToken(){
            
            while($this->i < count($this->e) && strpos("\n\t\r",$this->e[$this->i]) >= 0){
                $this->i++;
            }

            if($this->i >= count($this->e)){
                return new Token(TokenType::EOF,"");
            }

            $inputString = "";
            while ( $this->i < count($this->e) && strpos("0123456789", $this->e[$this->i]) >=0) {
                $inputString += $this->e[$this->i++];
            }
            
            if(!"" == $inputString){
                return new Token(TokenType::EOF,"");
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