<?php

require_once('Token.php');
require_once('Tokenizer.php');
require_once('TokenType.php');
require_once('EvalExprException.php');

     $currentToken;
    $t;
     $oneIndent="  ";
     $EOL = PHP_EOL;

    $inputSource = "http://localhost/project1/fall19Testing.txt";

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
echo $header.$EOL;
$currentToken = $t->nextToken();
$exp_num = 0;

while($currentToken->type != TokenType::EOF){
  echo "expression " . ++$exp_num . $EOL;

  try{
    $result = evalCompleteExpr();
    echo "Expression Result ". $EOL;
    echo $result . $EOL ; 
  }
  catch(EvalSectionException $ex){
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
echo $footer . $EOL ;

function evalCompleteExpr(){
  global $currentToken,$oneIndent,$t,$result;
  if($currentToken->type != TokeType::LSQUAREBRACKET){
    throw new EvalExprException("The expression must be proceeded \"[ \"");
  }
  echo "[".$EOL;

  $currentToken = $t->nextToken();
  $result = evalExpression($oneIndent);

  if($currentToken->type != TokenType::RSQUAREBRACKET){
    throw new EvalExprException("The expression must be proceeded \"] \"");
  }
  echo "]".$EOL;

  $currentToken = $t->nextToken();
  return $result;
}

function evalExpression($indent){
  global $currentToken, $oneIndent, $t, $result;
  $result = evalTerm(indent);
        while ($currentToken->type == TokenType::PLUS
                || $currentToken->type == TokenType::MINUS) {
            switch ($currentToken->type) {
                case TokenType::PLUS:
                    echo $indent."+";
                    $currentToken = $t->nextToken();
                    $result += evalTerm($indent);
                    break;
                case TokenType::MINUS:
                    $currentToken = $t->nextToken();
                    echo $indent."-";
                    $result = abs($result-evalTerm($indent));
                    break;
            }
        }
        return $result;
}

  function evalTerm($indent) {
    global $currentToken, $oneIndent, $t, $result;
  // <term> : <factor> ( '.' <factor> )*
   $result = evalFactor(indent);
  while ($currentToken->type == TokenType::CONCAT) {
      $currentToken = $t->nextToken();
      echo $indent.".";
      $result = computeConcat($result,evalFactor($indent));           
  }
  return $result;
}

function computeConcat($a,$b){
  if ($a==0) return $b;
  if ($b==0) return $a*10;
  return (int)$b+$a*(round(pow(10, strlen((string)$b))));  
}

 function evalFactor($indent) {
  // <factor> : INT | '(' <expression> ')'
  global $currentToken, $oneIndent, $t, $result;
   $result;
  if ($currentToken->type == TokenType::INT){
      $result = intval($currentToken->value);
      echo $indent.$result;
      $currentToken = $t->nextToken();
      return $result;
  }
  if ($currentToken->type != TokenType::LPAREN)
      throw new EvalExprException("Integer or Left parenthesis expected");
  echo $indent."(";
  $currentToken = $t->nextToken();
  $result = evalExpression($oneIndent.$indent);
  if ($currentToken->type != TokenType::RPAREN)
      throw new EvalExprException("Right parenthesis expected");
  echo $indent.")";
  $currentToken = $t->nextToken();
  return $result;
}




?>