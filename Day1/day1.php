<?php

//provides variables rations (main puzzle data) and rationsTest (simple data for testing)
require('input.php');

function solvePuzzle($rations){

  // divide up the rations into an array with each elf's rations as an entry
  $inventoryPerElf = explode(PHP_EOL.PHP_EOL, $rations);

  $caloriesPerElf = [];
  $totalCaloriesTop3 = 0;
  $mostCalories = 0;

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

    $caloriesPerElf[] = $snackTotal;
  }

  // sorting array from highest value to lowest value
  rsort($caloriesPerElf);

  // add calories from the top 3 elfs
  for ($i=0; $i < 3; $i++) { 
    $totalCaloriesTop3 += $caloriesPerElf[$i];
  }

  echo 'The Elf with the most total calories is carrying ' . $mostCalories . PHP_EOL;
  echo 'The top 3 elves are carrying a total of ' . $totalCaloriesTop3;

  return;
}

solvePuzzle($rations);

