<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Equipment Refinery</title>
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
		<h1>Equipment Refinery</h1>
		<form name='eqinfo' method='post' action='equip.php'>

		<table>
			<tr>
				<td>Base Price:</td>
				<td><input type='text' name='base' /></td>
			</tr>
			<tr>
				<td>Oridecon/Elunium Price:</td>
				<td><input type='text' name='ori' /></td>
			</tr>
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

		<input type='submit' name="submit" value='Submit' />

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
        array(0, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),	// Armor
        array(0, 100, 100, 100, 100, 100, 100, 100, 60, 40, 20),	// Level 1
        array(0, 100, 100, 100, 100, 100, 100, 60,  40, 20, 20),	// Level 2
        array(0, 100, 100, 100, 100, 100, 60,  50,  20, 20, 20),	// Level 3
        array(0, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),	// Level 4
    );
   
$service_fees = array(2000, 50, 200, 5000, 20000);
$names = array("Armor", "Weapons Lv 1", "Weapons Lv 2", "Weapons Lv 3", "Weapons Lv 4");
    

$base = $_POST["base"];  // Base price of equipment
$wlvl = $_POST["wlvl"];  // Weapon level 1-4, or armor
$ori = $_POST["ori"];  // Market price of oridecon
$jlvl = $_POST["jlvl"];  // Job level of whitesmith
$eqrefine = 0;
$fee = 0;
$itemcost = 0;
$total = 0;

$isnumeric = "^[0-9]+$";

// validate form fields here
if (!preg_match("/$isnumeric/", $base)) {
    print "Error. Base price is not a positive integer. <br />";
    exit();
} else if (!preg_match("/$isnumeric/", $ori)) {
    print "Error. Oridecon Price is not a positive integer. <br />";
    exit();
}

$bonus = ($jlvl-50)/2;     // +% from job bonus
$bonus = sprintf("%d", $bonus); // just get as integer
if ($jlvl <= 50)
	$bonus = 0;

if ($wlvl == 0)
    $bonus = 0;     // refining an armor
    
//print_table();

for ($i=5; $i<=10; $i++) {
    //print $percentrefinery[$wlvl][$i] . " += " . $bonus . "<br />";
    $percentrefinery[$wlvl][$i] += $bonus;
    
    // cap the percentages
    if ($percentrefinery[$wlvl][$i] < 0)
        $percentrefinery[$wlvl][$i] = 0;
    else if ($percentrefinery[$wlvl][$i] > 100)
        $percentrefinery[$wlvl][$i] = 100;
}

//print "Applying bonuses $bonus";
print_table();

// +5 and above chances, [60, 60*40, 60*40*40, 60*40*40*20, 60*40*40*20*20, 60*40*40*20*20*10]
$chances = array(0, 100, 100, 100, 100, $percentrefinery[$wlvl][5], 1, 1, 1, 1, 1);
$denoms =  array(0, 1, 1, 1, 1, 100, pow(100, 2), pow(100, 3), pow(100, 4), pow(100, 5), pow(100, 6));

// Calculate the chances of refining an equipment from +5 to +10
foreach (range(6, 10) as $v) {
    $chances[$v] = $percentrefinery[$wlvl][$v] * $chances[$v-1];
}

printf("Base Price: %d Ori/Elu Price: %d Weapon Lvl: %d Job Lvl: %d <br />",
	   $base, $ori, $wlvl, $jlvl);

print "<br />";
print "<table border='1' cellpadding='2'>";
print "<caption>Chance</caption>";
print "<tr>".
      "<td>Refine</td><td>x / y</td><td>Percent (%)</td><td>1 in x</td><td>Selling price (no fees)</td>".
	  "<td># of Oridecons/Eluniums</td>".
	  "<td>Total Cost = equipment cost + item cost + service fees</td>".
	  "</tr>";
	  
for ($i=5; $i<=10; $i++) {
    $per = $chances[$i] / $denoms[$i];
    $chance = sprintf("%.2f",$denoms[$i] / $chances[$i]);
	$dchance = sprintf("%d", $chance);	// integer form of $chance, rounded down
    $selling =  $base / $per;	// selling price of equipment, also cost of buying x amount of equips
    
    //$selling = sprintf("%f", $selling);
	
	$ori_num = $i * $dchance;	// just use the maximum amount of oris expected to be used
	$itemcost = $i * $ori * $dchance;	// estimated total cost of items used to refine
	$fee = $i * (($wlvl == 0) ? $service_fees[$wlvl] : 0) * $dchance;	// total service fee
	$total = $selling + $itemcost + $fee;
    
    print "<tr>".
          "<td>+$i</td>".
          "<td>$chances[$i] / $denoms[$i]</td>".    // x / y
          "<td>". $per*100 ."%</td>".    //  ratio as a percentage
          "<td>1 in $chance</td>".     // 1 out of x chances
          "<td>". number_format($selling) ."</td>".
		  "<td>". $ori_num ."</td>".
		  "<td>". number_format($total)
		  ." = ". number_format($selling) ." + ". number_format($itemcost) ." + ". number_format($fee) ."</td>".
          "</tr>";
          
    //print "$chances[$i] / $denoms[$i] = " . $chances[$i] * 100 / $denoms[$i] . "% <br />";
}
print "</table>";

    
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
    // BAD: print "$arrayname[$index]";
    // GOOD: print $arrayname[$index];
        
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