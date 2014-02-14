<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Alchemist Ranking</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<style type="text/css">
		
	</style>
</head>

<body>
<div id="wrapper">
	<div id="header">
		<h1>Ragnarok Tools</h1>
	</div>
	
	<div id="left">
		<div class="block">
			<h2>Links</h2>
			<ul>
				<li><a href="/index.html">Home</a></li>
				<li><a href="/exp.html">Base/Job Experience Calculator</a></li>
				<li><a href="/php/alchemist.php">Alchemist Ranking</a></li>
				<li><a href="/php/refine.php">Refine Simulator</a></li>
				<li><a href="/php/equip.php">Equipment Calculator</a></li>
				<li><a href="/php/whiteslim.php">White Slim Potion</a></li>
				<li><a href="/php/acid_demo.php">Acid Demonstration</a></li>
			</ul>
		</div>
	</div>

	<div id="main">
		<h2>Alchemist Ranking</h2>
		<form name='alch' method='post' action='alchemist.php'>
			<table>
				<tr>
					<td>Success Chance (%):</td>
					<td><input type='text' name='per' /></td>
				</tr>
				<tr>
					<td>Amount:</td>
					<td><input type='text' name='qty' /></td>
				</tr>
			</table>

			<input type='submit' name='submit' value='Submit' />
		</form>
	</div>
	
	<div id="footer">
		<p>Custom Website 2012-2012</p>
	</div>
</div>
</body>
</html>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$qty = $_POST["qty"];
$per = $_POST["per"];
$nherbs = 0;    // # of herbs

$fame = 0;
$pots = 0;
$counter = 0;

$pointrate = 0;

$MAX_VALUE = 1000000;	// 1mil max

// 0-100 | 000.00-100.00
$isdecimal = "^[0-9]{1,3}$|^[0-9]{1,3}.[0-9]{1,2}$";

// validate fields

if (!preg_match("/$isdecimal/", $per) || $per > 100.00) {
	print "Error. ";
	print "Success chance must be in format ###.## and in range 0.00-100.00<br />";
	exit();
}
else if ($qty == 0 || $qty < 0) {
	print "Error. ";
	print "Amount cannot be 0. And it must be greater than 0.<br />";
	exit();
}
else if (!($qty <= $MAX_VALUE)) {
	print "Error. ";
	print "Integer overflow. Amount must be less than/equal to ". number_format($MAX_VALUE) ."<br />";
	exit();
}

// remove decimal
$per *= 100;

for ($i=0; $i<$qty; $i++)
{
    $nherbs++; // # of white herbs consumed
    if (rand(1, 10000) <= $per)
    { // Success
        $pots++;
        //Add fame as needed.
        switch (++$counter) {
            case 3:
                $fame+=1; // Success to prepare 3 Condensed Potions in a row
                break;
            case 5:
                $fame+=3; // Success to prepare 5 Condensed Potions in a row
                break;
            case 7:
                $fame+=10; // Success to prepare 7 Condensed Potions in a row
                break;
            case 10:
                $fame+=50; // Success to prepare 10 Condensed Potions in a row
                $counter = 0;
                break;
            default:
                break;
        }
    } 
	else { //Failure
        $counter = 0;
    }
}

$pointrate = sprintf("%.2f", $fame/$qty);

print "<table border='1'>".
      "<tr>".
      "   <td>Potions Made</td>".
      "   <td>". number_format($pots) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Fame Points</td>".
      "   <td>". number_format($fame) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Herbs Used</td>".
      "   <td>". number_format($nherbs) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Success Rate (%)</td>".
      "   <td>". number_format($per/100, 2) ."</td>".
      "</tr>".
	  "<tr>".
      "   <td>Fame Points/Herb</td>".
      "   <td>". $pointrate ."</td>".
      "</tr>".
	  "</table>";

?>