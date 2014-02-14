<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
    <title>White Slim Potion</title>
	<script src='/jscript/whites.js' type='text/javascript'></script>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<style type="text/css">
		div.clear { clear:both; }
		tr.orange { background-color: orange; }
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
		<h1>White Slim Potion</h1>
		<form name='whiteslim' method='post' action='whiteslim.php'>
			<table>
				<tr>
					<td>Witched Starsand:</td>
					<td><input type='text' name='wstarsand'/></td>
				</tr>
				<tr>
					<td>Success Chance (Average %):</td>
					<td><input type='text' name='schance'/></td>
				</tr>
				<tr>
					<td colspan='3'>
						<table>
							<tr><td>Select Source:</td></tr>
							<tr>
								<td>Herb: <input type='radio' name='source' id='source' value='wherb' checked='checked' onchange='check_source(0)' /></td>
								<td>Potion: <input type='radio' name='source' id='source' value='wpotion' onchange='check_source(1)' /></td>
							</tr>
							<tr>
								<td>White Herb: <input type='text' name='wherb' id='wherb' value='700' /></td>
								<td>White Potion: <input type='text' name='wpotion' id='wpotion' value='1200' disabled='disabled' /></td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
					<td>Merchant Discount Level</td>
					<td>
						<select name='discountlvl'>
						<?php
							foreach (range(0,10) as $n) {
								if ($n == 10)
									printf("<option value='%d' selected='selected'>%d</option>", $n, $n);
								else
									printf("<option value='%d'>%d</option>", $n, $n);
							}
						?>
						</select>
					</td>
				</tr>
			</table>

			<input type='submit' name="submit" value='Submit' />
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

/*
Creates 100 Alcohol, 50 Acid Bottle and 50 Bottle Grenade. 
Each cast requires 50 Fabric, 50 Empty Bottle and enough materials to craft the produced items. 
If there are not enough ingredients to create them all at once, the skill will fail.
Check the Alcohol Creation Guide, Acid Bottle Creation Guide and Bottle Grenade Creation Guide for the necessary ingredients.
Note: You need a Taekwon Boy on your party to this skill to work. 


White Potion:
White Herb x 1
Empty Bottle x 1
+ Medicine Bowl x 1

Condensed White Potion:
White Potion x 1
Witched Starsand x 1
Empty Test Tube x 1
+ Medicine Bowl x 1

*/

$info = array(
		  // name of ingredient, zeny
		  "ws"  => array("Witched Starsand", (int)$_POST["wstarsand"]),
          "epb" => array("Empty Potion Bottle", 10),
		  "mb"  => array("Medicine Bowl", 8),
		  "wp"  => array("White Potion", (isset($_POST["wpotion"])?(int)$_POST["wpotion"]:1200)),
		  "ett" => array("Empty Test Tube", 3),
		  );
			  
$skilllvl = $_POST["discountlvl"];
$discount = array(0, 7, 9, 11, 13, 15, 17, 19, 21, 23, 24);
$source = $_POST["source"];
$wherb = (isset($_POST["wherb"])?(int)$_POST["wherb"]:700);
$wstarsand = $_POST["wstarsand"];
$schance = $_POST["schance"];
$total_cost = 0;
$adjusted_cost = 0;

// disabled fields are invalid
//$wpotion = (isset($_POST["wpotion"])?(int)$_POST["wpotion"]:1200);

$isnumeric = "^[0-9]+$";
$isdecimal = "^[0-9]{1,3}$|^[0-9]{1,3}.[0-9]{1,2}$";
//$isdecimal = "^[0-9]{1,3}$";	// 000-100
//$isdecimal = "^[0-9]{1,3}.[0-9]{1,2}$";	// 000.00 - 100.00

// fields must be positive integers only
if (!preg_match("/$isnumeric/", $wstarsand)) {
	print "Witched Starsand must be a positive integer only<br />";
	exit();
}
else if (!preg_match("/$isdecimal/", $schance) || $schance > 100) {
	print "Success Chance % must be in format ###.## and in range 0-100% <br />";
	exit();
}

// remove decimal
$schance *= 100;

// adjust prices based on discount %
// access keys only
foreach (array_keys($info) as $key) {
	if ($key == "ws") continue;
	$info[$key][1] = sprintf("%d", $info[$key][1] * (100 - $discount[$skilllvl]) / 100);
}

/*
print "% = $schance<br />";
print "Results: ";
foreach (array_keys($info) as $key) {
	print $info[$key][1] ." ";
}*/

// calculate totals
if ($source == "wherb") {
	$info["wp"][1] = $wherb + $info["epb"][1] + $info["mb"][1];
}

$total_cost = $info["wp"][1] + $wstarsand + $info["ett"][1] + $info["mb"][1];

$adjusted_cost = sprintf("%d", $total_cost * 10000 / $schance);

$schance = sprintf("%.2f", $schance/100);
		   
if ($source == "wherb") {
print <<<END
<table border='1' style="float:left">
	<tr>
		<th colspan='2'>White Potion</th>
	</tr>
	<tr>
		<td>White Herb</td>
		<td>$wherb</td>
	</tr>
	<tr>
		<td>Empty Potion Bottle</td>
		<td>{$info['epb'][1]}</td>
	</tr>
	<tr>
		<td>Medicine Bowl</td>
		<td>{$info['mb'][1]}</td>
	</tr>
	<tr class="orange">
		<td>Total</td>
		<td>{$info['wp'][1]}</td>
	</tr>
</table>
END;
}

$output = number_format($total_cost);

print <<<END
<table border='1' style="float:left">
	<tr>
		<th colspan='2'>White Slim Potion</th>
	</tr>
	<tr>
		<td>White Potion</td>
		<td>{$info['wp'][1]}</td>
	</tr>
	<tr>
		<td>Witched Starsand</td>
		<td>{$info['ws'][1]}</td>
	</tr>
	<tr>
		<td>Empty Test Tube</td>
		<td>{$info['ett'][1]}</td>
	</tr>
	<tr>
		<td>Medicine Bowl</td>
		<td>{$info['mb'][1]}</td>
	</tr>
	<tr class="orange">
		<td>Total</td>
		<td>$output</td>
	</tr>
</table>
END;

$output = number_format($adjusted_cost);

print <<<END
<table border='1'>
	<th colspan='2'>Adjustments</th>
	<tr>
		<td>Success Chance (%):</td>
		<td>$schance</td>
	</tr>
	<tr class="orange">
		<td>Adjusted Price:</td>
		<td>$output</td>
	</tr>
</table>
END;
/*
print <<<END
<div class="clear"></div>
<p>This is a paragraph</p>
<p>Not really sure how floated elements that precede this one affect elements after</p>
END;
*/

?>