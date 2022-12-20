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

  // mapping locations of all pixels so that I don't have to loop through to find and transform in the move functions
  if($index === 0){
    $leftSide  = [[$startRow, 3]];
    $rightSide = [[$startRow, 6]];
    $bottom    = [[$startRow, 3],   [$startRow, 4],   [$startRow, 5],   [$startRow, 6]];
    $whole     = [[$startRow, 3],   [$startRow, 4],   [$startRow, 5],   [$startRow, 6]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  } else if($index === 1){
    $leftSide  = [[$startRow, 4],   [$startRow+1, 3], [$startRow+2, 4]];
    $rightSide = [[$startRow, 4],   [$startRow+1, 5], [$startRow+2, 4]];
    $bottom    = [[$startRow+1, 3], [$startRow, 4],   [$startRow+1, 5]];
    $whole     = [[$startRow, 4],   [$startRow+1, 3], [$startRow+1, 4], [$startRow+1, 5],[$startRow+2, 4]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  } else if($index === 2){
    $leftSide  = [[$startRow, 3],   [$startRow+1, 5], [$startRow+2, 5]];
    $rightSide = [[$startRow, 5],   [$startRow+1, 5], [$startRow+2, 5]];
    $bottom    = [[$startRow, 3],   [$startRow, 4],   [$startRow, 5]];
    $whole     = [[$startRow, 3],   [$startRow, 4],   [$startRow, 5],   [$startRow+1, 5], [$startRow+2, 5]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  } else if($index === 3){
    $leftSide  = [[$startRow, 3],   [$startRow+1, 3], [$startRow+2, 3], [$startRow+3, 3]];
    $rightSide = [[$startRow, 3],   [$startRow+1, 3], [$startRow+2, 3], [$startRow+3, 3]];
    $bottom    = [[$startRow, 3]];
    $whole     = [[$startRow, 3],   [$startRow+1, 3], [$startRow+2, 3], [$startRow+3, 3]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  } else if($index === 4){
    $leftSide  = [[$startRow, 3],   [$startRow+1, 3]];
    $rightSide = [[$startRow, 4],   [$startRow+1, 4]];
    $bottom    = [[$startRow, 3],   [$startRow, 4]];
    $whole     = [[$startRow, 3],   [$startRow, 4], [$startRow+1, 3], [$startRow+1, 4]];
    $edges = [$leftSide, $rightSide, $bottom, $whole];
  }

  foreach($shape as $row){
    $chamber[$startRow] = $row;
    $startRow++;
  }

  

  return [$chamber, $edges];
}

function canMoveRight($chamber, $shapeLocations){

  $canMove = true;

  foreach($shapeLocations[1] as $edge){
    if($chamber[$edge[0]][$edge[1]+1] === '#' or $chamber[$edge[0]][$edge[1]+1] === '|'){
      $canMove = false;
    }
  }

  return $canMove;
}

function processMoveRight($chamber, $shapeLocations){

  foreach($shapeLocations[3] as $pixel){
    $chamber[$pixel[0]][$pixel[1]] = '.';
    $chamber[$pixel[0]][$pixel[1]+1] = '@';
  }

  return $chamber;
}

function canMoveLeft($chamber, $shapeLocations){

  $canMove = true;

  foreach($shapeLocations[0] as $edge){
    if($chamber[$edge[0]][$edge[1]-1] === '#' or $chamber[$edge[0]][$edge[1]-1] === '|'){
      $canMove = false;
    }
  }
  return $canMove;
}

function processMoveLeft($chamber, $shapeLocations){

  foreach($shapeLocations[3] as $pixel){
    $chamber[$pixel[0]][$pixel[1]] = '.';
    $chamber[$pixel[0]][$pixel[1]-1] = '@';
  }

  return $chamber;
}

function canFall($chamber, $shapeLocations){

  $canFall = true;
  foreach($shapeLocations[2] as $bottom){
    if($chamber[$bottom[0]-1][$bottom[1]] === '#' or $chamber[$bottom[0]-1][$bottom[1]] === '-'){
      $canFall = false;
    }
  }

  return $canFall;
}

function processFall($chamber, $shapeLocations){

  foreach($shapeLocations[3] as $pixel){
    $chamber[$pixel[0]][$pixel[1]] = '.';
    $chamber[$pixel[0]-1][$pixel[1]] = '@';
  }

  return $chamber;
}

function comeToRest($chamber, $shapeLocations){

  // print_r($shapeLocations);

  foreach($shapeLocations[3] as $pixel){
    $chamber[$pixel[0]][$pixel[1]] = '#';
  }

  return $chamber;
}

function locationLeft($shapeLocations){

  $i = 0;
  
  foreach($shapeLocations as $shape){
    $j = 0;
    foreach($shape as $pixel){
      $shapeLocations[$i][$j][1] = $pixel[1]-1;
      $j++;
    }
    $i++;
  }
  return $shapeLocations;
}

function locationRight($shapeLocations){

  $i = 0;
  
  foreach($shapeLocations as $shape){
    $j = 0;
    foreach($shape as $pixel){
      $shapeLocations[$i][$j][1] = $pixel[1]+1;
      $j++;
    }
    $i++;
  }
  return $shapeLocations;
}

function locationDown($shapeLocations){

  $i = 0;
  
  foreach($shapeLocations as $shape){
    $j = 0;
    foreach($shape as $pixel){
      $shapeLocations[$i][$j][0] = $pixel[0]-1;
      $j++;
    }
    $i++;
  }
  return $shapeLocations;
}

function processMoves($chamber, $input, $counter, $shapeLocations){

  $process = true;
  $size = strlen($input);

  while($process){
    $moveDirection = $input[$counter%$size];
    if($moveDirection === '<' and canMoveLeft($chamber, $shapeLocations)){
      $chamber = processMoveLeft($chamber, $shapeLocations); 
      $shapeLocations = locationLeft($shapeLocations);
    } 
    if($moveDirection === '>' and canMoveRight($chamber, $shapeLocations)){
      $chamber = processMoveRight($chamber, $shapeLocations);
      $shapeLocations = locationRight($shapeLocations);
    }
    // renderChamber($chamber);

    if (canFall($chamber, $shapeLocations)){
      $chamber = processfall($chamber, $shapeLocations);
      $shapeLocations = locationDown($shapeLocations);
    } else {
      $chamber = comeToRest($chamber, $shapeLocations);
      $process = false;
    }
    // renderChamber($chamber);
    $counter++;
  }
  return [$chamber, $counter];
}

function showEdges($chamber, $edges){

  $tempChamber = $chamber;

  foreach( $edges[0] as $edge){
    $tempChamber[$edge[0]][$edge[1]] = '&';
  }
  echo 'highlighting left side';
  renderChamber($tempChamber);
  $tempChamber = $chamber;
  foreach( $edges[1] as $edge){
    $tempChamber[$edge[0]][$edge[1]] = '&';
  }
  echo 'highlighting right side';
  renderChamber($tempChamber);
  $tempChamber = $chamber;
  foreach( $edges[2] as $edge){
    $tempChamber[$edge[0]][$edge[1]] = '&';
  }
  echo 'highlighting bottom';
  renderChamber($tempChamber);
  $tempChamber = $chamber;
  foreach( $edges[3] as $edge){
    $tempChamber[$edge[0]][$edge[1]] = '&';
  }
  echo 'highlighting all';
  renderChamber($tempChamber);
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

  $processLoop = true;
  $loopCounter = 0;
  $counter = 0;

  while($processLoop){
    $shapeIndex = $loopCounter%5;
    $shapeAdded = addShape($shapes[$shapeIndex], $chamber, $shapeIndex);
    $chamber = $shapeAdded[0];
    $shapeLocations = $shapeAdded[1];

    $results = processMoves($chamber, $input, $counter, $shapeLocations);
    $chamber = $results[0];
    $counter = $results[1];
    
    $loopCounter++;
    echo 'on shape ' . number_format($loopCounter) . PHP_EOL;
    
    if($loopCounter >= 20220){
      break;
    }
  }
  
  echo 'The tower height is ' . findTowerHeight($chamber);
  return $chamber;
}


$chamber = solvePuzzle($input);
$height = findTowerHeight($chamber);
renderChamber($chamber);

//   $chamber2 = [];
//   $chamber2[0] = ['+','-','-','-','-','-','-','-','+'];
//   $chamber2[1] = ['|','#','.','.','.','.','.','.','|'];
//   $chamber2[2] = ['|','.','#','.','.','.','.','.','|'];
//   $chamber2[3] = ['|','.','.','#','#','#','.','.','|'];
//   $chamber2[4] = ['|','.','.','#','.','.','.','.','|'];
//   $chamber2[5] = ['|','.','#','.','.','.','.','.','|'];
//   $chamber2[6] = ['|','#','#','#','.','.','.','.','|'];
//   $chamber2[7] = ['|','#','.','.','.','.','.','.','|'];
//   $chamber2[8] = ['|','.','#','.','.','.','.','.','|'];
//   $chamber2[9] = ['|','.','.','#','#','#','.','.','|'];
//   $chamber2[10] = ['|','.','.','#','.','.','.','.','|'];
//   $chamber2[11] = ['|','.','#','.','.','.','.','.','|'];
//   $chamber2[12] = ['|','#','#','#','.','.','.','.','|'];

// $chunkSize = 4;


// renderChamber($chamber2);
// $chamber = $chamber2;
$height = findTowerHeight($chamber);

for ($t=3; $t < $height; $t++) { 
  $match = 'true';
  for ($i=$height; $i >= 0 ; $i--) { 
    for ($j=0; $j < $t; $j++) { 
      if ($chamber[$i-$j] !== $chamber[$i-$t-$j]){
        $match = 'false';
      }
    }
    if ($match === 'true'){
      echo 'is there a match for chunk of ' . $t .'? ' . $match . PHP_EOL;
    }
    
    break;
  }
}
