<?php

//provides variables guide (main puzzle data) and guideTest (simplified data for testing)
require('input.php');

function evaluateTurn($opponentMove, $result){

  // each opponent move mapped to the points result for the corresponding action
  $comparisons = [
    'A' => ['Z' => 2, 'X' => 3, 'Y' => 1],
    'B' => ['Z' => 3, 'X' => 1, 'Y' => 2],
    'C' => ['Z' => 1, 'X' => 2, 'Y' => 3],
  ];

  return $comparisons[$opponentMove][$result];
}

function computeOutcomeArray(){

  $opponentMoves = ['A','B','C'];
  $results     = ['X','Y','Z'];
  $selfPoints    = ['X' => 0,'Y' => 3,'Z' => 6];
  $possibilities = [];

  foreach($opponentMoves as $op){
    foreach($results as $res){
      $key = "$op $res";
      $value = evaluateTurn($op, $res) + $selfPoints[$res];
      $possibilities[$key] = [$value];
    }
  }

  return $possibilities;
}

function solvePuzzle($guide){

    $outcomeKey = computeOutcomeArray();
    $turns = explode(PHP_EOL, $guide);
    $totalPoints = 0;

    foreach($turns as $turn){
      $totalPoints += $outcomeKey[$turn][0];
    }

    echo 'The total score for this guide is ' . $totalPoints;
}

solvePuzzle($guide);