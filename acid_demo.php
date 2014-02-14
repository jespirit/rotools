<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Acid Demonstration</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<style type="text/css">
		table.txtr td { text-align: right; }
		.mH { color:#60c; cursor:pointer;  font-weight:bold; border-top:1px solid #300; }
		#stuff { display: none;
	</style>
	<script language="javascript" type="text/javascript">
	function toggleMenu(objID) {
		if (!document.getElementById) return;
		var ob = document.getElementById(objID).style;
		ob.display = (ob.display == 'block')?'none':'block';
	}
	</script>
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
		<h1>Acid Demonstration Bottles</h1>
		<form name='alchemy' method='post' action='acid_demo.php'>
			<h2>Ingredients</h2>
			<table border='1'>
				<tr>
					<td>Acid Demonstration Bottles:</td>
					<td><input type='text' name='adnum' /></td>
				</tr>
				<tr>
					<td>Success Chance (%):</td>
					<td><input type='text' name='per' /></td>
				</tr>
				<tr>
					<td>Merchant Discount Level:</td>
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
					<td>
						<div class="mH" onclick="toggleMenu('stuff')">+ Enter Amounts</div>
						<table id="stuff">
							<tr>
								<td>Empty Bottle:</td>
								<td><input type="text" name="n1" /></td>
							</tr>
							<tr>
								<td>Alcohol:</td>
								<td><input type="text" name="n2" /></td>
							</tr>
							<tr>
								<td>Fabric:</td>
								<td><input type="text" name="n3" /></td>
							</tr>
							<tr>
								<td>Medicine Bowl:</td>
								<td><input type="text" name="n4" /></td>
							</tr>
							
							<tr>
								<td>Immortal Heart:</td>
								<td><input type="text" name="n5" /></td>
							</tr>
							<tr>
								<td>Stem:</td>
								<td><input type="text" name="n6" /></td>
							</tr>
							<tr>
								<td>Empty Test Tube:</td>
								<td><input type="text" name="n7" /></td>
							</tr>
							<tr>
								<td>Poison Spore:</td>
								<td><input type="text" name="n8" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<input type='submit' name='submit' value='Submit' />
		</form>
	</div>

	<div id="footer">
		Custom Website 2012-2012
	</div>
</div>

</body>
</html>

<?php

/*
Creates 100 Alcohol, 50 Acid Bottle and 50 Bottle Grenade. 
Each cast requires 50 Fabric, 50 Empty Bottle and enough materials to craft the produced items. 
If there are not enough ingredients to create them all at once, the skill will fail.
Check the Alcohol Creation Guide, Acid Bottle Creation Guide and Bottle Grenade Creation Guide for the necessary ingredients.
Note: You need a Taekwon Boy on your party to this skill to work. 

Fire Bottle:
Empty Bottle x 1
Alcohol x 1
Fabric x 1
+ Medicine Bowl x 1

Acid Bottle:
Empty Bottle x 1
Immortal Heart x 1
+ Medicine Bowl x 1

Alcohol
Empty Bottle x 1
Stem x 5
Empty Test Tube x 1
Poison Spore x 5
+ Medicine Bowl x 1
*/

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();
		  
$info = array(
		  // total # ingredients to purchase, # of ingredients per TA3, name of ingredient, cost of ingredient
          array(1, 200, "Empty Bottle",     6,   $_POST["n1"]),
		  array(1, 50,  "Alcohol",          0,   $_POST["n2"]),
		  array(1, 50,  "Fabric",           306, $_POST["n3"]),
		  array(1, 200, "Medicine Bowl",    8,   $_POST["n4"]),
		  
		  array(1, 50,  "Immortal Heart",   374, $_POST["n5"]),	// 4
		  
		  array(1, 500, "Stem",             46,  $_POST["n6"]),			// 5
		  array(1, 100, "Empty Test Tube",  3,   $_POST["n7"]),	// 6
		  array(1, 500, "Poison Spore",     114, $_POST["n8"]),	// 7
		);
		
$adnum = $_POST["adnum"];
$per = $_POST["per"];
			  
$isnumeric = "^[0-9]+$";
$isdecimal = "^[0-9]{1,3}$|^[0-9]{1,3}.[0-9]{1,2}$";

$skilllvl = $_POST["discountlvl"];
$discount = array(0, 7, 9, 11, 13, 15, 17, 19, 21, 23, 24);

// cost of ingredients for alcohol
$alcohol_ing = array(6, 46, 3, 114, 8);
$alcohol_amt = array(1,  5, 1,   5, 1);

$rem = 0;	// remainder of 50
$total_cost = 0;

$totals = array(
	"fb" => array(1, 1),
	"ab" => array(1, 1),
	);

// fields must be positive integers only

if (!preg_match("/$isnumeric/", $adnum)) {
	print "Number of AD Bottles must be a positive integer.<br />";
	exit();
}
else if ($adnum <= 0) {
	print "Please enter an amount greater than 0.<br />";
	exit();
}

if (!(empty($per) && !is_numeric($per)) && (!preg_match("/$isdecimal/", $per) || $per > 100)) {
	print "Please enter a valid percentage for success chance ##.## in range 0.00-100.00<br />";
	exit();
}

foreach ($info as &$val) {
	$val[4] *= 1;	// treat as integer
	if (!preg_match("/$isnumeric/", $val[4])) {
		print "Number of {$val[2]} must be a positive integer.<br />";
		exit();
	}
	else if ($val[4] < 0) {
		print "Please enter an amount greater than or equal to 0.<br />";
		exit();
	}
}

// number of times to cast Twilight Alchemy III to reach the supposed AD #
$count = (int)($adnum / 50);
$rem = $adnum % 50;

// now calculate how many to buy for each ingredient
for ($i=0; $i<count($info); $i++) {
	$info[$i][0] = $count * $info[$i][1];
	// apply discount
	$info[$i][3] = sprintf("%d", $info[$i][3] * (100 - $discount[$skilllvl]) / 100);
	$cost = $info[$i][0] * $info[$i][3];
	$total_cost += $cost;
}

// calculate the total cost to make alcohol 
for ($i=0; $i<count($alcohol_ing); $i++) {
	$price = (int)($alcohol_ing[$i] * (100 - $discount[$skilllvl]) / 100) * $alcohol_amt[$i];
	$info[1][3] += $price;
}

$total_cost += $info[1][0] * $info[1][3];

/* can't permanently assign values to the array */
/*
foreach ($info as $v) {
	print "before: $v[0] ";
	$v[0] = $count * $v[1];
	print "after: $v[0] <br />";
}*/

//$info[0][0] = 999;

/*
print "Results: ";
foreach ($info as $v) {
	print $v[0] ." ";
}*/

format2table($info, $count, $discount, $skilllvl, $total_cost);

$total_cost = 0;

for ($i=0; $i<5; $i++) {
	$info[$i][0] = $rem;
}

// empty bottle + medicine bowl is doubled in amount
$info[0][0] = $info[3][0] = $rem * 2;

// calculate the cost of any remaining AD
for ($i=0; $i<5; $i++) {
	$cost = $info[$i][0] * $info[$i][3];
	$total_cost += $cost;
}

print "<table class='txtr' border='1' style='float:left; margin-left: 50px;'>".
      "<tr>".
		  "<td>AD (Leftover):</td>".
		  "<td>$rem</td>".
	  "</tr>".
	  "<tr>".
		  "<td>&nbsp</td>".
		  "<td>Cost</td>".
		  "<td>Total Amount</td>".
		  "<td>Amount To Buy</td>".
		  "<td>Total Cost</td>".
	  "</tr>";
	  
	  
for ($i=0; $i<5; $i++) {
	$tobuy = $info[$i][0] - $info[$i][4];
	if ($tobuy < 0)
		$tobuy = 0;
		
	print "<tr>".
	      "<td>{$info[$i][2]}</td>".
		  "<td>". number_format($info[$i][3]) ."</td>".
		  "<td>". number_format($info[$i][0]) ."</td>".
		  "<td> (". number_format($info[$i][4]) .") ". number_format($tobuy) ."</td>".
		  "<td>". number_format($info[$i][0]*$info[$i][3]) ."</td>".
		  "</tr>";
}

print "<tr>".
      "<td>Total (-$discount[$skilllvl]%):</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>". number_format($total_cost) ."</td>".
	  "</tr>".
	  "<tr>".
      "<td>Total (- Alcohol):</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>". number_format($total_cost - $info[1][0] * $info[1][3]) ."</td>".
	  "</tr>".
      "</table>";
      "</table>";

if ($per == 0)
	$per = 100;
$per *= 100;
$totals["fb"][0] = $info[0][3] + $info[1][3] + $info[2][3] + $info[3][3];
$totals["fb"][1] = (int)($totals["fb"][0] * 10000 / $per);
	  
print "<table class='txtr' border='1' style='float:left; margin-left: 100px;'>".
	  "<tr>".
		  "<th>Fire Bottle</th>".
		  "<td>Cost</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[0][2]}</td>".
		  "<td>{$info[0][3]}</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[1][2]}</td>".
		  "<td>{$info[1][3]}</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[2][2]}</td>".
		  "<td>{$info[2][3]}</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[3][2]}</td>".
		  "<td>{$info[3][3]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Total Price</td>".
		  "<td>{$totals["fb"][0]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Adjusted Price</td>".
		  "<td>{$totals["fb"][1]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Success Chance (%)</td>".
		  "<td>". sprintf("%.2f", $per/100) ."</td>".
	  "</tr>".
	  "</table>";
	  
$totals["ab"][0] = $info[0][3] + $info[4][3] + $info[3][3];
$totals["ab"][1] = (int)($totals["ab"][0] * 10000 / $per);
	  
print "<table class='txtr' border='1' style='float:left; margin-left: 10px;'>".
	  "<tr>".
		  "<th>Acid Bottle</th>".
		  "<td>Cost</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[0][2]}</td>".
		  "<td>{$info[0][3]}</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[4][2]}</td>".
		  "<td>{$info[4][3]}</td>".
	  "</tr>".
	  "<tr>".
	      "<td>{$info[3][2]}</td>".
		  "<td>{$info[3][3]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Total Price</td>".
		  "<td>{$totals["ab"][0]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Adjusted Price</td>".
		  "<td>{$totals["ab"][1]}</td>".
	  "</tr>".
	  "<tr>".
		  "<td>Success Chance (%)</td>".
		  "<td>". sprintf("%.2f", $per/100) ."</td>".
	  "</tr>".
	  "</table>";

function format2table(&$info, $count, $discount, $skilllvl, $total_cost) {
print "<table class='txtr' border='1' style='float:left; margin-left: 200px;'>".
      "<tr>".
		  "<td>Attempts/AD:</td>".
		  "<td>$count</td>".
		  "<td>". number_format($count*50) ."</td>".
	  "</tr>".
	  "<tr>".
		  "<td>&nbsp</td>".
		  "<td>Cost</td>".
		  "<td>Total Amount</td>".
		  "<td>Amount To Buy</td>".
		  "<td>Total Cost</td>".
	  "</tr>";
	  
	  
foreach ($info as &$v) {
	$bought = $v[4];
	$tobuy = $v[0] - $v[4];
	if ($tobuy < 0) {
		$v[4] = $tobuy * -1;
		$tobuy = 0;
	}
	else {
		$v[4] = 0;
	}
		
	print "<tr>".
	      "<td>$v[2]</td>".
		  "<td>". number_format($v[3]) ."</td>".
		  "<td>". number_format($v[0]) ."</td>".
		  "<td> (". number_format($bought) .") ". number_format($tobuy) ."</td>".
		  "<td>". number_format($v[0]*$v[3]) ."</td>".
		  "</tr>";
}

print "<tr>".
      "<td>Total (-$discount[$skilllvl]%):</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>". number_format($total_cost) ."</td>".
	  "</tr>".
	  "<tr>".
      "<td>Total (- Alcohol):</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>&nbsp</td>".
	  "<td>". number_format($total_cost - $info[1][0] * $info[1][3]) ."</td>".
	  "</tr>".
      "</table>";
}

?>