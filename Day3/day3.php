<?php

//provides variables rucksacks (main puzzle data) and rucksacksTest (simplified data for testing)
require('input.php');

function divideCompartments($rucksack){

  $length = strlen($rucksack)/2;

  $first  = substr($rucksack, 0, $length);
  $second = substr($rucksack, -$length); 

  return [$first, $second];
}

function findDuplicates($compartments){

  $compare = [];
  $firstCompartment = $compartments[0];
  $secondCompartment = $compartments[1];

  for ($i = 0; $i < strlen($firstCompartment); $i++){
    $compare[$firstCompartment[$i]] = 'true';
  }

  for ($i = 0; $i < strlen($secondCompartment); $i++){
    if(array_key_exists($secondCompartment[$i], $compare)){
      $duplicate = $secondCompartment[$i];
    }
  }

  return $duplicate;
}

function generatePriorities(){

  $lowerCase = range('a', 'z');
  $upperCase = range('A', 'Z');

  $priorities = [];

  foreach($lowerCase as $key => $value){
    $priorities[$value] = $key + 1;
  }

  foreach($upperCase as $key => $value){
    $priorities[$value] = $key + 27;
  }

  return $priorities;
}

function findBadge($group){

  $bags = [];
  $j = 0;

  foreach($group as $bag){
    for ($i = 0; $i < strlen($bag); $i++){
      $bags[$j][$bag[$i]] = 'true';
    }
    $j++;
  }

  $badge = array_intersect_assoc($bags[0], $bags[1], $bags[2]);
  return array_search('true', $badge);
}

function solvePuzzle($rucksacks){

  $sum = 0;
  $i = 1;
  $badgeArray = [];
  $badgeSum = 0;

  $priorities = generatePriorities();

  foreach($rucksacks as $rucksack){
    $duplicate = findDuplicates(divideCompartments($rucksack));
    $sum += $priorities[$duplicate];

    $badgeArray[] = $rucksack;
    if ($i%3 == 0){
      $badge = findBadge($badgeArray);
      $badgeSum += $priorities[$badge];
      $badgeArray = [];
    }
    $i ++;
  }
  
  echo 'The sum of duplicate priorities is ' . $sum;
  echo PHP_EOL;
  echo 'The sum of the badges is ' . $badgeSum;
}

solvePuzzle(explode(PHP_EOL,$rucksacks));