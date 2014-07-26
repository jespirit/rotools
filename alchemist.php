<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Alchemist Ranking</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<style type="text/css">
		//#twilight-chk { display: none }
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
	function toggleMenu(objID) {
		if (!document.getElementById) return;
		var ob = document.getElementById(objID).style;
		ob.display = (ob.display == 'block')?'none':'block';
	}
	
	$(document).ready(function() {
		$("[name='potion']").change(function() {
			var x = $(this).val();
			if (x == 4) {  // white potion
				$("#twilight-chk").css("display", "block");
				$("#twilight").removeAttr("disabled");  // enable checkbox
			}
			else {
				$("#twilight-chk").css("display", "none");
				$("#twilight").attr("disabled", true);  // disable checkbox
			}
		});
	});
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
		<h2>Alchemist Ranking</h2>
		<form name='alch' method='post' action='alchemist.php'>
			<table>
				<tr>
					<td>Success Chance (%):</td>
					<td><input type='text' name='per' /></td>
					<td>Enter a percentage (0-100%)</td>
				</tr>
                <?php 
                    $types = array('Red, Yellow, White Potion', 'Blue Potion', 
                                   'Condensed Red Potion', 'Condensed Yellow Potion', 'Condensed White Potion');
                                   
					for ($i=0; $i<count($types); $i++) {
						if ($i == 0)	// default selected value
							print "<tr><td><input type='radio' name='potion' checked='checked' value='$i' />" . $types[$i] . "</td></tr>";
						else
							print "<tr><td><input type='radio' name='potion' value='$i' />" . $types[$i] . "</td></tr>";
					}
				?>
				<tr>
					<td>Amount:</td>
					<td><input type='text' name='qty' /></td>
					<td>
						<div id='twilight-chk'>
							<input type='checkbox' name='twilight' id='twilight' value='1' disabled='disabled' />Twilight?
						</div>
					</td>
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

$per = $_POST["per"];
$qty = $_POST["qty"];
$potion = $_POST["potion"];
$nherbs = 0;    // # of herbs
$base_per = 0;

$twilight = 0;
$fame = 0;
$pots = 0;
$counter = 0;

$pointrate = 0;

$types = array('Red, Yellow, White Potion', 'Blue Potion', 
               'Condensed Red Potion', 'Condensed Yellow Potion', 'Condensed White Potion');

$MAX_VALUE = 1000000;	// 1mil max

// 0-100 | 000.00-100.00
$isdecimal = "^([0-9]{1,3}|[0-9]{1,3}\\.[0-9]{1,2})$";
$is_numeric = "^[0-9]+$";

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
$base_per = $per;

$rates = array(
    // min, avg, max
    array($per+2010, $per+2500, $per+3000),
    array($per, $per, $per),
    array($per, $per, $per),
    array($per-500, $per-250, $per-10),  // Yellow slim
    array($per-1000, $per-500, $per-10),  // White slim
);

// Apply caps
for ($i=0; $i<5; $i++) {
    for ($j=0; $j<3; $j++) {
        if ($rates[$i][$j] < 1)
            $rates[$i][$j] = 1;
        else if ($rates[$i][$j] > 10000)
            $rates[$i][$j] = 10000;
    }
}

if (isset($_POST["twilight"])) {
	// quantity now represents how many Twilight Alchemys to perform
	$twilight = 200;
}
else
	$twilight = 1;

for ($x=0; $x<$qty; $x++) {
    $per = $base_per;  // Restore the base percent.
    
    // Apply bonuses or penalties.
    switch ($potion) {
        case 0:  // Red, Yellow, White
            $per += rand(1, 100)*10 + 2000;  // +2010 (+20.1%), +3000 (+30%)
            break;
        case 3:  // Condensed Yellow Potion
            $per -= rand(1,50)*10;  // -10 (-0.1%), -500 (-5%)
            break;
        case 4:  // Condensed White Potion
            $per -= rand(1,100)*10;  // -10 (-0.1%), -1000 (-10%)
            break;
        // No penalty
        case 1:  // Blue Potion
        case 2:  // Condensed Red Potion
        default:
            break;
    }

	// the percent chance for twilight alchemy is calculated once for each activation
    for ($i=0; $i<$twilight; $i++)
    {
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
}

$pointrate = sprintf("%.2f", $fame/($qty*$twilight));

print "<table border='1'>".
      "<tr>".
      "   <td>Type</td>".
      "   <td>". $types[$potion] ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Potions Made</td>".
      "   <td>". number_format($pots) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Attempts</td>".
      "   <td>". number_format($qty) ." / ". number_format($twilight) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Fame Points</td>".
      "   <td>". number_format($fame) ."</td>".
      "</tr>".
      "<tr>".
      "   <td>Success Rate (%)</td>".
      "   <td>". sprintf("%.2f%% %.2f%% %.2f%%", $rates[$potion][0]/100, $rates[$potion][1]/100, $rates[$potion][2]/100) ."</td>".
      "</tr>".
	  "<tr>".
      "   <td>Fame Points/Attempts</td>".
      "   <td>". $pointrate ."</td>".
      "</tr>".
	  "</table>";

?>