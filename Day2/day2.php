<?php

//provides variables guide (main puzzle data) and guideTest (simplified data for testing)
require('input.php');

function evaluateTurn($opponentMove, $selfMove){

  $comparisons = [
    'X' => ['A' => 3, 'B' => 0, 'C' => 6],
    'Y' => ['A' => 6, 'B' => 3, 'C' => 0],
    'Z' => ['A' => 0, 'B' => 6, 'C' => 3],
  ];

  return $comparisons[$selfMove][$opponentMove];

}

function computeOutcomeArray(){

  $opponentMoves = ['A','B','C'];
  $selfMoves     = ['X','Y','Z'];
  $selfPoints    = ['X' => 1,'Y' => 2,'Z' => 3];
  $possibilities = [];

  foreach($opponentMoves as $op){
    foreach($selfMoves as $self){
      $key = "$op $self";
      $value = evaluateTurn($op, $self) + $selfPoints[$self];
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
