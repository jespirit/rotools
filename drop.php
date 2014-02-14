<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Monster Drop Simulator</title>
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
		<h1>Monster Drop Simulator</h1>
		<form name='dropsim' method='post' action='drop.php'>
			<h2>Ingredients</h2>
			<table>
				<tr>
					<td># of Monsters</td>
					<td><input type='text' name='count'></td>
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


// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$i;
$j;
	
$pc_dex	= 105;
$mob_dex = 55;	//baba yaga 48;	// myst case
$lv = 10;
	
$droprate = 3;
$rate = sprintf("%d", ($pc_dex - $mob_dex)/2 + $lv*6 + 4);
$steal = true;
	
// name,dropper,count,steal,total
$drops = array(
	array("Candy", 				340, 	0, 0, 0),
	array("Candy Cane", 		90, 	0, 0, 0),
	array("Piece of Cake", 		800, 	0, 0, 0),
	array("Old Blue Box", 		20, 	0, 0, 0),
	array("Pearl", 				150, 	0, 0, 0),
	array("2-Carat Diamond", 	5, 		0, 0, 0),
	array("Zargon", 			1500, 	0, 0, 0),
	array("Myst Case Card", 	1, 		0, 0, 0),
);

$drops = array(
	array("Milk", 				1500, 	0, 0, 0),
	array("Piece of Cake", 		1500, 	0, 0, 0),
	array("Bread", 				1500, 	0, 0, 0),
	array("Radiant Wisdom", 	10, 	0, 0, 0),
	array("Worn-out Magic Scroll",	1000, 	0, 0, 0),
	array("Pellet", 			150, 	0, 0, 0),
	array("Pestle", 			5000, 	0, 0, 0),
);	
	
$count = $_POST["count"];
$isnumeric = "^[0-9]+$";

// Fields must be positive integers only

if (!preg_match("/$isnumeric/", $count)) {
	print "Number of monsters must be a positive integer.<br />";
	exit();
}
else if ($count <= 0) {
	print "Please enter an amount greater than 0.<br />";
	exit();
}

// Adjust rates
foreach ($drops as &$item) {
	$item[1] *= $droprate;
}

for ($i=0; $i<$count; $i++) {
	$steal = false;
	// attempt to steal until successful
	while (!$steal) {
		for ($j=0; $j<count($drops) && !$steal; $j++) {
			if (rand(0,9999) < $drops[$j][1] * $rate/100) {
				$drops[$j][3]++;
				$drops[$j][4]++;
				$steal = true;
			}
		}
	}
	
	foreach ($drops as &$item) {
		if (rand(0,9999)<$item[1]) {
			$item[2]++;
			$item[4]++;
		}
	}
}

/*
for ($i=0; $i<count($drops); $i++) {
	print "{$drops[$i][0]} : {$drops[$i][2]} <br />";
}*/

print "<table>";
print "<tr><td>Monsters</td><td>$count</td></tr>";
print "<th>&nbsp</th><th>Drop</th><th>Steal</th><th>Total</th>";
	  
// BUG: Does not display the last item in the array,
// instead replaces it with the second last item.
// Note: There is a preceding foreach loop that uses a reference.
// FIX: Use $key=>$value alternative
foreach ($drops as $key=>$value) {
	print "<tr><td>$value[0]</td><td>$value[2]</td><td>$value[3]</td><td>$value[4]</td></tr>";
}

print "</table>";

?>