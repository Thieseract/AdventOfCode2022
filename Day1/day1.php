<?php

//provides variables rations (main puzzle data) and rationsTest (simple data for testing)
require('input.php');

function solvePuzzle($rations){

  $mostCalories = 0;

  // divide up the rations into an array with each elf's rations as an entry
  $inventoryPerElf = explode(PHP_EOL.PHP_EOL, $rations);

  // loop through each elf's inventory, adding up the contents, and compare to current highest count
  foreach($inventoryPerElf as $elf){

    $snackTotal = 0;

    // divide up each elf's rations into individual items and total them
    $snacks = explode(PHP_EOL, $elf);
    foreach($snacks as $snack){
      $snackTotal += $snack;
    }

    if($snackTotal > $mostCalories){
      $mostCalories = $snackTotal;
    }
  }
  return $mostCalories;
}

$solution = solvePuzzle($rations);
echo $solution;
