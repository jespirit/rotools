<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

function read_exp()
{
	global $jexp;
	global $lvls;
	
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

	while (!feof($fp)) {
		$line = fgets($fp, 1024);
		if (preg_match("/^\/\/|^\s*$/", $line))
			continue;
		$data = explode(",", $line);
		
		//print "$line<br>";
		//print "$data[0], $data[1], $data[2]<br>";
		
		$maxlv = $data[0];  // max job level
		$group = $data[1];
		$type = $data[2];
		$total_exp = 0;
		
		if ($type == 1) {  // job exp
			$lvls[$group] = $maxlv;
			for ($i=0; $i<$maxlv-1; $i++) {
				$jexp[$group][$i][1] = $total_exp;
				$jexp[$group][$i][2] = $data[$i+3];
				$total_exp += $data[$i+3];
			}
			$jexp[$group][$maxlv-1][1] = $total_exp;
			$jexp[$group][$maxlv-1][2] = 0;
		}
	}
}

read_exp();

//$lvls = array(10, 10, 50, 50, 70, 70);

$x = 0;

// 0-100 | 000.00-100.00
$short_decimal = "/^([0-9]{1,3}|[0-9]{1,3}\\.[0-9]{1})$/";
$is_numeric = "/^[0-9]+$/";

$gain = 0;
$lvl1 = $GET_lvl1 + 0;
$lvl2 = $GET_lvl2 + 0;
$exp1 = $GET_exp1 * 1;
$exp2 = $GET_exp2 * 1;
$roclass = $GET_roclass;

$expgain = $GET_expgain + 0;
$lvl3 = 0;
$exp3 = 0.0;

$valid = 1;

// validate form fields
if (!preg_match("$is_numeric", $lvl1)) {
	print "Error. Start Job Level must be a positive integer.<br>";
	$valid = 0;
}
else if (!($lvl1 >= 1 && $lvl1 <= $lvls[$roclass])) {
    $valid = 0;
    print "Error <br />";
    print "Start Job Level must be in range 1-". $lvls[$roclass] ."<br />";
}
else if (!preg_match("$is_numeric", $lvl2)) {
	print "Error. End Job Level must be a positive integer.<br>";
	$valid = 0;
}
else if (!($lvl2 >= 1 && $lvl2 <= $lvls[$roclass])) {
    $valid = 0;
    print "Error <br />";
    print "End Job Level must be in range 1-". $lvls[$roclass] ."<br>";
}
else if ($lvl2 < $lvl1) {
	print "Error. End Job Level must be greater than or equal to Start Job Level<br>";
	$valid = 0;
}
else if (!preg_match($short_decimal, $exp1)) {
	print "Error. Start Exp must be a decimal in the form of XX.X";
	$valid = 0;
}
else if (!($exp1 >= 0.0 && $exp1 <= 100.0)) {
    $valid = 0;
    print "Error <br />";
    print "Start Exp must be in range 0-100 <br />";
}
else if (!preg_match($short_decimal, $exp2)) {
	print "Error. Start Exp must be a decimal in the form of XX.X";
	$valid = 0;
}
else if (!($exp2 >= 0.0 && $exp2 <= 100.0)) {
    $valid = 0;
    print "Error <br />";
    print "End Exp must be in range 0-100 <br />";
}

if (!$valid) {
    exit();
}

// form data is valid

// calculate the amount of exp gained
// = exp2 - exp1 - (% at lvl1) + (% at lvl2)
$gain = ($jexp[$roclass][$lvl2-1][1] - $jexp[$roclass][$lvl1-1][1])
	- ($jexp[$roclass][$lvl1-1][2] * $exp1/100)
	+ ($jexp[$roclass][$lvl2-1][2] * $exp2/100);

$gain = sprintf("%d", $gain);  // get in integer format

printf("(%s - %s) - %s + %s <br />", 
	number_format($jexp[$roclass][$lvl2-1][1]),
	number_format($jexp[$roclass][$lvl1-1][1]),
	number_format($jexp[$roclass][$lvl1-1][2] * $exp1/100),
	number_format($jexp[$roclass][$lvl2-1][2] * $exp2/100));
print "You have gained " . number_format($gain) . " job experience. <br />";

// Calculate your level and % with 'expgain'
$texp = sprintf("%d", $jexp[$roclass][$lvl1-1][1]
	+ ($jexp[$roclass][$lvl1-1][2] * $exp1/100) + $expgain);
print "Total exp amount: ". number_format($texp) ."<br />";

for ($i=$lvls[$roclass]; $i>0; $i--) {
	if ($texp >= $jexp[$roclass][$i-1][1])
		break;
}

$lvl3 = $i;

if ($i == $lvls[$roclass])
	$exp3 = 100.0;
else
	$exp3 = ($texp - $jexp[$roclass][$i-1][1]) * 100 / $jexp[$roclass][$i-1][2];  // get % at lvl3

$exp3 = sprintf("%.1f", $exp3);  // format to 3 decimal places

print "From Level $lvl1 at $exp1%, with a gain of " . number_format($expgain) . " experience, " .
	  "You would be at Level $lvl3 with $exp3% <br />";

?>