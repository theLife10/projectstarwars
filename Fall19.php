<?php

include_once('Token.php');
include_once('Tokenizer.php');
include_once('TokenType.php');
include_once('EvalExprException.php');

     $currentToken;
    $t;
     $oneIndent="  ";
     $EOL = PHP_EOL;

    $inputSource = "http://localhost/project1/fall19Testing.txt";
    
    //$inputSource = "http://cssrvlab01.utep.edu/classes/cs5339/jdgarcia9/test.txt";

    $output = file($inputSource);

    $header = "<html>" . $EOL
            . "  <head>" . $EOL
            . "    <title>CS 4339/5339 PHP assignment</title>" . $EOL
            . "  </head>" . $EOL
            . "  <body>" . $EOL
            . "    <pre>";
    $footer = "    </pre>" . $EOL
            . "  </body>" . $EOL
            . "</html>";

  $inputFile="";

  foreach ($output as $inputLine) {
    $inputFile .= $inputLine . PHP_EOL;
}
$t = new Tokenizer($inputFile);
echo $header.PHP_EOL;
$currentToken = $t->nextToken();
$exp_num = 0;

while($currentToken->type != TokenType::EOF){
  echo "expression " . ++$exp_num . PHP_EOL;

  try{
    $result = evalCompleteExpr();
    echo "Expression Result ". PHP_EOL;
    echo $result . PHP_EOL ; 
  }
  catch(EvalExprException $ex){
      while ($currentToken -> type != TokenType::RSQUAREBRACKET &&
    $currentToken->type != TokenType::LSQUAREBRACKET &&
    $currentToken->type != TokenType::EOF){
      $currentToken = $t->nextToken();

    }
    
    if($currentToken->type == TokenType::RSQUAREBRACKET){
      $currentToken=$t->nextToken();
    }
  }
}
echo $footer . PHP_EOL ;

function evalCompleteExpr(){
  global $currentToken,$oneIndent,$t,$result;
  if($currentToken->type != TokenType::LSQUAREBRACKET){
      throw new EvalExprException("The expression must be proceeded \"[\"");
  }
  echo "[".PHP_EOL;

  $currentToken = $t->nextToken();
  $result = evalExpression($oneIndent);

  if($currentToken->type != TokenType::RSQUAREBRACKET){
    throw new EvalExprException("The expression must be proceeded \"] \"");
  }
  echo "]".PHP_EOL;

  $currentToken = $t->nextToken();
  return $result;
}

function evalExpression($indent){
  global $currentToken, $oneIndent, $t, $result;
  $result = evalTerm($indent);
        while ($currentToken->type == TokenType::PLUS
                || $currentToken->type == TokenType::MINUS) {
            switch ($currentToken->type) {
                case TokenType::PLUS:
                    echo $indent."+".PHP_EOL;
                    $currentToken = $t->nextToken();
                    $result += evalTerm($indent)-2;
                    break;
                case TokenType::MINUS:
                    $currentToken = $t->nextToken();
                    echo $indent."-".PHP_EOL;
                    $result = abs($result-evalTerm($indent))+2;
                    break;
            }
        }
        return $result;
}

  function evalTerm($indent) {
    global $currentToken, $oneIndent, $t, $result;
  // <term> : <factor> ( '.' <factor> )*
   $result = evalFactor($indent);
  while ($currentToken->type == TokenType::CONCAT) {
      $currentToken = $t->nextToken();
      echo $indent.".".PHP_EOL;
      $result = computeConcat($result,evalFactor($indent));           
  }
  return $result;
}

function computeConcat($a,$b){
  if ($a==0) return $b;
  if ($b==0) return $a*10;
  return $b+($a*(round(pow(10, strlen(strval($b))))));  
}

 function evalFactor($indent) {
  // <factor> : INT | '(' <expression> ')'
  global $currentToken, $oneIndent, $t, $result;
   
  if ($currentToken->type == TokenType::INT){
      $result = intval($currentToken->value);
      echo $indent.$result.PHP_EOL;
      $currentToken = $t->nextToken();
      return $result;
  }
  if ($currentToken->type != TokenType::LPAREN)
      throw new EvalExprException("Integer or Left parenthesis expected");
  echo $indent."(".PHP_EOL;
  $currentToken = $t->nextToken();
  $result = evalExpression($oneIndent.$indent);
  if ($currentToken->type != TokenType::RPAREN)
      throw new EvalExprException("Right parenthesis expected");
  echo $indent.")".PHP_EOL;
  $currentToken = $t->nextToken();
  return $result;
}




?>