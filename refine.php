<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Refine Rate Simulator</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
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
		<h1>Refine Rate Simulator</h1>
		<form name='rrsim' action='refine.php' method='post'>
			<table>
				<tr><td>Equipment Name:</td><td><input type='text' name='eqname' /></td></tr>
				<tr><td>Market Price:</td><td><input type='text' name='eqprice' /></td></tr>
				<tr><td>Oridecon/Elunium Price:</td><td><input type='text' name='eluprice' /></td></tr>
				<tr>
					<td>Weapon Level:</td>
					<td>
						<select name='wlvl'>
						<option value='0'>Armor</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='4'>4</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Job Level:</td>
					<td>
						<select name='jlvl'>
						<?php
							foreach (range(1,70) as $n) {
								printf("<option value='%d'>%d</option>", $n, $n);
							}
						?>
						</select>
					</td>
				</tr>
			</table>

			<table>
				<tr>
					<td>Upgrade:</td>
				<?php 
					foreach (range(5, 10) as $n) {
						if ($n == 5)	// default selected value
							print "<td><input type='radio' name='rtarget' checked='checked' value='$n' /> +$n |</td>";
						else
							print "<td><input type='radio' name='rtarget' value='$n' /> +$n |</td>";
					}
				?>
				</tr>
			</table>
			<input type='submit' name="submit" value='Refine' />
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

// 5x11
$percentrefinery = 
    array(
        //       +1   +2   +3   +4   +5   +6   +7   +8  +9  +10
        array(0, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),
        array(0, 100, 100, 100, 100, 100, 100, 100, 60, 40, 20),
        array(0, 100, 100, 100, 100, 100, 100, 60,  40, 20, 20),
        array(0, 100, 100, 100, 100, 100, 60,  50,  20, 20, 20),
        array(0, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),
    );
   
$service_fees = array(2000, 50, 200, 5000, 20000);
$names = array("Armor", "Weapons Lv 1", "Weapons Lv 2", "Weapons Lv 3", "Weapons Lv 4");
$items = array("Elunium", "Phracon", "Emveretarcon", "Oridecon", "Oridecon");

$wlvl = $_POST["wlvl"];
$jlvl = $_POST["jlvl"];

$eqname = $_POST["eqname"];
$eqprice = $_POST["eqprice"];
$rtarget = $_POST["rtarget"];
$eluprice = $_POST["eluprice"];

$alpha = "^[a-zA-Z \[\]0-9]+$";  // space, square brackets are allowed
                                 // ie. Guard[1], Shoes[1]
$integer_only = "^[0-9]+$";

$bonus = ($jlvl-50)/2;     // +% from job bonus
//$bonus = sprintf("%d", $bonus); // just get as integer

if ($wlvl == 0)
    $bonus = 0;     // refining an armor

// Apply job level bonus
for ($i=5; $i<=10; $i++) {
    //print $percentrefinery[$wlvl][$i] . " += " . $bonus . "<br />";
    $percentrefinery[$wlvl][$i] += $bonus;
    
    // cap the percentages
    if ($percentrefinery[$wlvl][$i] < 0)
        $percentrefinery[$wlvl][$i] = 0;
    else if ($percentrefinery[$wlvl][$i] > 100)
        $percentrefinery[$wlvl][$i] = 100;
}

// validate form fields here
$valid = true;

if (empty($eqname)) {
    print "You did not set an equipment name.\n";
    $valid = false;
} else if (!preg_match("/$alpha/", $eqname)) {
    print "You are only allowed to use letters of the alphabet a-z\n";
    $valid = false;
} else if (empty($eqprice) && !is_numeric($eqprice)) {
    print "You left the equipment price empty\n";
    $valid = false;
} else if (!preg_match("/$integer_only/", $eqprice)) {
    print "Price must be a positive integer.\n";
    $valid = false;
} else if (empty($eluprice) && !is_numeric($eluprice)) {
    print "You left the equipment price empty\n";
    print "Price must be a positive integer.\n";
    $valid = false;
} else if (!preg_match("/$integer_only/", $eluprice)) {
    print "Price must be a positive integer.\n";
    $valid = false;
}

if (!$valid) {
    exit();
}

$count = 0;
$elu_num = 0;
$equip_refine = 0;
$service_cost = 0;
$num_equips = 1;
$total = 0;

$MAX_REFINE = 10;

print_table();

do
{
    if (rand(0, 99) < $percentrefinery[$wlvl][$equip_refine+1]) {
        $equip_refine++;
        print "Success. You made a +$equip_refine $eqname <br />";
    }
    else {
        print "Failure. You broke it at +$equip_refine <br />";
        $equip_refine = 0;
        $num_equips++;
    }
    
    $count++;
    $elu_num++;
    $service_cost += $service_fees[$wlvl];  // 2000z for each armor upgrade
    
} while ($equip_refine < $MAX_REFINE && $equip_refine != $rtarget);

$equipcost = $eqprice * $num_equips;
$itemcost = $elu_num * $eluprice;
$total = $equipcost + $itemcost + $service_cost;

print "Great. It took you only $num_equips $eqname's to make a +$rtarget $eqname <br />";
print "You've used up $elu_num $items[$wlvl] and spent \$". number_format($total) ."<br />";
print "Total Cost = ". number_format($equipcost) ." + ". number_format($itemcost) ." + ". number_format($service_cost) ."<br />";

function print_table()
{
    // Note: Remember, in PHP variables that may seem global are not global.
    // Within a function's local scope, any variables declared outside
    // are not visible, you have to declare them with the global keyword.
    global $percentrefinery;    // now refer to the file-scope array
    global $names;
    
    //$x = $percentrefinery[0][4];
    
    // Note: To print the values within an array in a print statement,
    // you should not enclose it in double quotes
    // BAD: print "$x";
    // GOOD: print $x;
        
    //print_r($percentrefinery);
        
    print "<table border='1' cellpadding='2'>";
    print "<caption>Refine Success Rates (%)</caption>";
    print "<th>Equipment</th>";
    for ($i=1; $i<=10; $i++)
        print "<th>+$i</th>";
        
    for ($i=0; $i<=4; $i++) {
        print "<tr>";
        print "<td>". $names[$i] ."</td>";
        for ($j=1; $j<=10; $j++) {
            print "<td>" . $percentrefinery[$i][$j] . "</td>";
        }
        print "</tr>";
    }
    print "</table>";
}

?>