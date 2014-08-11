<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

function read_exp()
{
	global $exp;
	
	$file = "exp.txt";
	$fp = fopen($file, "r");
	if (!$fp) {
		print "Error. Failed to open file $file.<br>";
		exit();
	}
	
	$maxlv;
	$group;
	$type;
	$total_exp;

	$groups = array("fjob", "sjob");

	while (!feof($fp)) {
		$line = fgets($fp, 1024);
		if (preg_match("/^\/\/|^\s*$/", $line))
			continue;
			
		$data = explode(",", $line);
		
		//print "$line<br>";
		//print "$data[0], $data[1], $data[2]<br>";
		
		$maxlv = $data[0];
		$type = $data[2];
		$total_exp = 0;
		
		if ($type == 0) {  // base exp
			$group = $groups[$data[1]];
			for ($i=0; $i<$maxlv-1; $i++) {
				$exp[$group][$i][1] = $total_exp;
				$exp[$group][$i][2] = $data[$i+3];
				$total_exp += $data[$i+3];
			}
			$exp[$group][$maxlv-1][1] = $total_exp;
			$exp[$group][$maxlv-1][2] = 0;
		}
	}
}

read_exp();

$x = 0;

$gain = 0.0;
$lvl1 = $GET_lvl1;
$lvl2 = $GET_lvl2;
$exp1 = $GET_exp1 + 0;  // convert to number
$exp2 = $GET_exp2 + 0;
$roclass = $GET_roclass;

$expgain = (int)($GET_expgain + 0);  // to number and truncate
$lvl3 = 0;
$exp3 = 0.0;

// 00-99 or 00.0-99.0 is permitted, but not 100 or 100.0
$exp_format = "^([0-9][0-9]?|[0-9][0-9]?\.[0-9])$";

$valid = 1;

// validate form fields
if (!($lvl1 >= 1 && $lvl1 <= 99)) {
    $valid = 0;
    print "Error. Start Level must be in range 1-99 <br />";
}
else if (!($lvl2 >= 1 && $lvl2 <= 99)) {
    $valid = 0;
    print "Error. End Level must be in range 1-99 <br />";
}
else if ($lvl2 < $lvl1) {
	$valid = 0;
	print "Error. End Level must be greater than or equal to Start Level<br>";
}
else if (!preg_match("/$exp_format/", $exp1)) {  // empty field is allowed
    $valid = 0;
    print "Error. Start Exp has invalid format, 00.0-99.9 or 0-99 <br />";
}
else if (!($exp1 >= 0.0 && $exp1 < 100.0)) {
    $valid = 0;
    print "Error. Start Exp must be in range 0-100 <br />";
}
else if (!preg_match("/$exp_format/", $exp2)) {
    $valid = 0;
    print "Error. End Exp has invalid format, 00.0-99.9 or 0-99 <br />";
}
else if (!($exp2 >= 0.0 && $exp2 < 100.0)) {
    $valid = 0;
    print "Error. End Exp must be in range 0-100 <br />";
}
else if (!($gain >= 0)) {
    $valid = 0;
    print "Error. Exp gain must be a positive integer <br />";
}


if (!$valid) {
    print "ERROR! <br />";
}
else {
        
    // form data is valid

    
    // calculate the amount of exp gained from level x to y
    // = exp2 - exp1 - (% at lvl1) + (% at lvl2)
    
    $total1 = $exp[$roclass][$lvl1-1][1];
    $total2 = $exp[$roclass][$lvl2-1][1];
    $next1 = $exp[$roclass][$lvl1-1][2];
    $next2 = $exp[$roclass][$lvl2-1][2];
	
	$gain = ($total2 - $total1) - ($next1 * $exp1/100) + ($next2 * $exp2/100);
    $gain = sprintf("%d", $gain);

	printf("(%s - %s) - %s + %s <br />", number_format($total2), number_format($total1), 
		number_format($next1 * $exp1/100), number_format($next2 * $exp2/100));
    print "You have gained " . number_format($gain) . " base experience. <br />";
	
	// Calculate the level and % when given x amount of experience points.

	$texp = sprintf("%d", $total1 + ($next1 * $exp1/100) + $expgain);
	print "Total exp amount: ". number_format($texp) ."<br>";
	
    // loop through the exp totals in reverse until we've found the level
    // with the correct amount of exp
	$i = 99;
	for (; $i>1; $i--) {  // no need to check level 1, condition would be always true
		if ($texp >= $exp[$roclass][$i-1][1])
			break;
	}
	
	$lvl3 = $i;
	
	if ($i == 99)	// avoid division by zero
		$exp3 = 100.0;  // $texp is implicitly capped here
	else
        // calculate how much % of experience you would earn at the final level
		$exp3 = ($texp - $exp[$roclass][$i-1][1]) * 100 / $exp[$roclass][$i-1][2];    // get % at lvl3
    
    $exp3 = sprintf("%.1f", $exp3); // format to 1 decimal places
    
    print "From Level $lvl1 at $exp1%, with a gain of " . number_format($expgain) . " experience, " .
          "You would be at Level $lvl3 with $exp3% <br />";
    
    /*
    for ($x=0; $x<99; $x++) {
        printf("%d %d %d %d <br />", $exp[$roclass][$x][0], $exp[$roclass][$x][1], $exp[$roclass][$x][2], $exp[$roclass][$x][3]);
    }*/

}

?>