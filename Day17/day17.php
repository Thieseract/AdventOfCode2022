<?php

error_reporting(E_ALL ^ E_WARNING); 
//provides variables input (main puzzle data) and inputTest (simplified data for testing)
require('input.php');

function renderChamber($chamber){

  echo PHP_EOL;
  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=0; $j < 9 ; $j++){
      echo $chamber[$i][$j];
    }
    echo PHP_EOL;
  }
}

function findCurrentBottom($chamber){

  $currentbottom = 0;

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=0; $j < 9 ; $j++){
      if($chamber[$i][$j] === '-' or $chamber[$i][$j] === '#'){
        $currentbottom = $i;
        break 2;
      }
    }
  }

  return $currentbottom;
}

function addShape($shape, $chamber, $index){

  $startRow = findCurrentBottom($chamber) + 4;

  foreach($shape as $row){
    $chamber[$startRow] = $row;
    $startRow++;
  }

  if($index === 0){
    $leftSide  = [[$startRow, 3]];
    $rightSide = [[$startRow, 6]];
    $bottom    = [[$startRow, 3], [$startRow, 4], [$startRow, 5], [$startRow, 6]];
    $whole     = [[$startRow, 3], [$startRow, 4], [$startRow, 5], [$startRow, 6]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  }

  return $chamber;
}

function canMoveRight($chamber){

  $canMove = true;

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    foreach($chamber[$i] as $index => $pixel){
      if($pixel === '@'){
        if($chamber[$i][$index+1] === '|' or $chamber[$i][$index+1] === '#'){
          $canMove = false;
        }
      }
    }
  }
  return $canMove;
}

function processMoveRight($chamber){

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=7; $j > 0; $j--) { 
      if($chamber[$i][$j] === '@'){
        $chamber[$i][$j+1] = '@';
        if($chamber[$i][$j-1] !== '@'){
          $chamber[$i][$j] = '.';
        }
      }
    }
  }

  return $chamber;
}

function moveRight($chamber){

  if(canMoveRight($chamber)){
    return processMoveRight($chamber);
  } else {
    return $chamber;
  }
}

function canMoveLeft($chamber){

  $canMove = true;

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    foreach($chamber[$i] as $index => $pixel){
      if($pixel === '@'){
        if($chamber[$i][$index-1] === '|' or $chamber[$i][$index-1] === '#'){
          $canMove = false;
        }
      }
    }
  }
  return $canMove;
}

function processMoveLeft($chamber){

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=1; $j < 8; $j++) { 
      if($chamber[$i][$j] === '@'){
        $chamber[$i][$j-1] = '@';
        if($chamber[$i][$j+1] !== '@'){
          $chamber[$i][$j] = '.';
        }
      }
    }
  }

  return $chamber;
}

function moveLeft($chamber){

  if(canMoveLeft($chamber)){
    return processMoveLeft($chamber);
  } else {
    return $chamber;
  }
}

function canFall($chamber){

  $canFall = true;

  $height = sizeof($chamber) - 1;
  for ($i=0; $i <= $height ; $i++) {
    foreach ($chamber[$i] as $index => $pixel) {
      if ($pixel === '@') {
        if ($chamber[$i-1][$index] === '#' or $chamber[$i-1][$index] === '-') {
          $canFall = false;
        }
      }
    }
  }
  return $canFall;
}

function processFall($chamber){

  $height = sizeof($chamber) - 1;
  for ($i=0; $i <= $height ; $i++) { 
    for ($j=1; $j < 8; $j++) { 
      if($chamber[$i][$j] === '@'){
        $chamber[$i-1][$j] = '@';
        if($chamber[$i+1][$j] !== '@'){
          $chamber[$i][$j] = '.';
        }
      }
    }
  }

  return $chamber;
}

function comeToRest($chamber){

  $height = sizeof($chamber) - 1;
  for ($i=0; $i <= $height ; $i++) { 
    for ($j=1; $j < 8; $j++) { 
      if($chamber[$i][$j] === '@'){
        $chamber[$i][$j] = '#';
      }
    }
  }
  return $chamber;
}

function fall($chamber){

  if(canFall($chamber)){
    return processFall($chamber);
  } else {
    return false;
  }
}

function processMoves($chamber, $input, $counter){

  $process = true;
  $size = strlen($input);

  while($process){
    $moveDirection = $input[$counter%$size];
    if($moveDirection === '<'){
      $chamber = moveLeft($chamber); 
    } elseif($moveDirection === '>'){
      $chamber = moveRight($chamber);
    }
    // renderChamber($chamber);

    if (!canFall($chamber)){
      $chamber = comeToRest($chamber);
      $process = false;
    } else {
      $chamber = fall($chamber);
    }
    // renderChamber($chamber);
    $counter++;
  }
  return [$chamber, $counter];
}

function findTowerHeight($chamber){

  $height = sizeof($chamber) - 1;
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=1; $j < 8; $j++) { 
      if($chamber[$i][$j] === '#'){
        return $i;
        break 2;
      }
    }
  }
}

function solvePuzzle($input){

  $shapes = [];
  $shapes[0][0] = ['|','.','.','@','@','@','@','.','|'];
  $shapes[0][1] = ['|','.','.','.','.','.','.','.','|'];
  $shapes[0][2] = ['|','.','.','.','.','.','.','.','|'];
  $shapes[0][3] = ['|','.','.','.','.','.','.','.','|'];
  

  $shapes[1][0] = ['|','.','.','.','@','.','.','.','|'];
  $shapes[1][1] = ['|','.','.','@','@','@','.','.','|'];
  $shapes[1][2] = ['|','.','.','.','@','.','.','.','|'];
  $shapes[1][3] = ['|','.','.','.','.','.','.','.','|'];

  
  $shapes[2][0] = ['|','.','.','@','@','@','.','.','|'];
  $shapes[2][1] = ['|','.','.','.','.','@','.','.','|'];
  $shapes[2][2] = ['|','.','.','.','.','@','.','.','|'];
  $shapes[2][3] = ['|','.','.','.','.','.','.','.','|'];

  $shapes[3][3] = ['|','.','.','@','.','.','.','.','|'];
  $shapes[3][2] = ['|','.','.','@','.','.','.','.','|'];
  $shapes[3][1] = ['|','.','.','@','.','.','.','.','|'];
  $shapes[3][0] = ['|','.','.','@','.','.','.','.','|'];

  $shapes[4][3] = ['|','.','.','@','@','.','.','.','|'];
  $shapes[4][2] = ['|','.','.','@','@','.','.','.','|'];
  $shapes[4][1] = ['|','.','.','.','.','.','.','.','|'];
  $shapes[4][0] = ['|','.','.','.','.','.','.','.','|'];

  $chamber = [];
  $chamber[0] = ['+','-','-','-','-','-','-','-','+'];
  $chamber[1] = ['|','.','.','.','.','.','.','.','|'];
  $chamber[2] = ['|','.','.','.','.','.','.','.','|'];
  $chamber[3] = ['|','.','.','.','.','.','.','.','|'];
  $chamber[4] = ['|','.','.','.','.','.','.','.','|'];
  $chamber[5] = ['|','.','.','.','.','.','.','.','|'];
  $chamber[6] = ['|','.','.','.','.','.','.','.','|'];

  // renderChamber($chamber);

  $processLoop = true;
  $loopCounter = 0;
  $counter = 0;
  $shapeIndex = $loopCounter%5;

  while($processLoop){
    $shapeIndex = $loopCounter%5;
    $chamber = addShape($shapes[$shapeIndex], $chamber, $shapeIndex);
    // renderChamber($chamber);
    $results = processMoves($chamber, $input, $counter);
    $chamber = $results[0];
    $counter = $results[1];
    // renderChamber($chamber);
    $loopCounter++;
    echo 'on shape ' . number_format($loopCounter) . PHP_EOL;
    if($loopCounter == 2022){
      break;
    }
  }
  // renderChamber($chamber);
  echo 'The tower height is ' . findTowerHeight($chamber);

}
$time1 = time();
solvePuzzle($input);
$time2 = time();

$timeTaken = $time2-$time1;
$timePer = $timeTaken/2022;
$seconds = $timePer * 1000000000000;
$mins = $seconds/60;
$hours = $mins/60;
echo 'time to do a trillion is ' . $hours;