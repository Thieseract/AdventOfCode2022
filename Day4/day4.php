<?php

//provides variables input (main puzzle data) and inputTest (simplified data for testing)
require('input.php');

function partA($input){

  $contains = 0;
  foreach($input as $row){
    $sets = explode(',', $row);
    $i = 0;
    $pairs = [];
    foreach($sets as $set){
      $range = explode('-', $set);
      $pairs[$i] = $range;
      $i++;
    }
    if($pairs[0][0] <= $pairs[1][0] and $pairs[0][1] >= $pairs[1][1]){
      $contains++;
    } else if ($pairs[1][0] <= $pairs[0][0] and $pairs[1][1] >= $pairs[0][1]){
      $contains++;
    }
  }
  
  echo 'The number of pairs that fully contain the other is ' . $contains;
}

function partB($input){

  $overlap = 0;
  foreach($input as $row){
    $sets = explode(',', $row);
    $i = 0;
    $pairs = [];
    foreach($sets as $set){
      $range = explode('-', $set);
      $pairs[$i] = $range;
      $i++;
    }
    $setA1 = $pairs[0][0];
    $setA2 = $pairs[0][1];
    $setB1 = $pairs[1][0];
    $setB2 = $pairs[1][1];

    if($setA2 >= $setB1 and $setA1 <= $setB2){
      $overlap++;
    }
  }
  echo 'The number of pairs that overlap the other is ' . $overlap;
}

function solvePuzzle($input){

  partA(explode(PHP_EOL, $input));
  partB(explode(PHP_EOL, $input));

}

solvePuzzle($input);