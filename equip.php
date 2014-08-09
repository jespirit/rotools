<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

print <<<EOF
<h1>Equipment Refinery</h1>
<form id='equip_form' name='equip_form' onsubmit="return GET_ajax('equip.php', 'equip_div', 'equip_form');">
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
EOF;
			foreach (range(1,70) as $n) {
				printf("<option value='%d'>%d</option>", $n, $n);
			}
			
print <<<EOF
			</select>
		</td>
	</tr>
</table>

<input type="submit" name="submit" value="Submit" />
</form>

<div id="equip_div"></div>
EOF;

exit();
}

// 5 x 11 refine success rate table
$percentrefinery = 
    array(
        //       +1   +2   +3   +4   +5   +6   +7   +8  +9  +10
        array(5, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),	// Armor
        array(8, 100, 100, 100, 100, 100, 100, 100, 60, 40, 20),	// Level 1
        array(7, 100, 100, 100, 100, 100, 100, 60,  40, 20, 20),	// Level 2
        array(6, 100, 100, 100, 100, 100, 60,  50,  20, 20, 20),	// Level 3
        array(5, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),	// Level 4
    );
   
$service_fees = array(2000, 50, 200, 5000, 20000);
$names = array("Armor", "Weapon Lv 1", "Weapon Lv 2", "Weapon Lv 3", "Weapon Lv 4");

$base = $GET_base;  // Base price of equipment
$wlvl = $GET_wlvl;  // Weapon level 1-4, or armor
$ori = $GET_ori;  // Market price of oridecon
$jlvl = $GET_jlvl;  // Job level of whitesmith
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

$bonus = sprintf("%d", ($jlvl-50)/2);  // +% from job bonus
if ($jlvl <= 50)
	$bonus = 0;

if ($wlvl == 0)  // refining an armor
    $bonus = 0;

for ($i=5; $i<=10; $i++) {
    //print $percentrefinery[$wlvl][$i] . " += " . $bonus . "<br />";
    $percentrefinery[$wlvl][$i] += $bonus;
    
    // cap the percentages
    if ($percentrefinery[$wlvl][$i] < 0)
        $percentrefinery[$wlvl][$i] = 0;
    else if ($percentrefinery[$wlvl][$i] > 100)
        $percentrefinery[$wlvl][$i] = 100;
}

print_table();

$safe = $percentrefinery[$wlvl][0];
$chances = array();
$denoms = array();

$chances[$safe] = $percentrefinery[$wlvl][$safe];
$denoms[$safe] = 100;

// Calculate the chances of refining an equipment from safe limit to +10
// +5 and above chances, [60, 60*40, 60*40*40, 60*40*40*20, 60*40*40*20*20, 60*40*40*20*20*10]
foreach (range($safe+1, 10) as $v) {
    $chances[$v] = $percentrefinery[$wlvl][$v] * $chances[$v-1];
	$denoms[$v] = pow(100, $v-$safe+1);  // 100^2, 100^3
}

print "
	<table border='1' cellpadding='2'>
		<caption>Info</caption>
		<tr>
			<td>Base Price:</td>
			<td>". number_format($base) ."</td></tr>
		<tr>
			<td>Ori/Elu Price:</td>
			<td>". number_format($ori) ."</td></tr>
		<tr>
			<td>Weapon Lvl:</td>
			<td>$wlvl</td></tr>
		<tr>
			<td>Job Lvl:</td>
			<td>$jlvl</td></tr>
	</table>";

print "
		<table border='1' cellpadding='2'>
		<caption>Refine Rates</caption>
		<tr>
		   <td>Refine</td><td>Percent (%)</td><td>1 in x</td><td>Selling price (no fees)</td>
		   <td># of Oridecons/Eluniums</td>
		   <td>Total Cost = equipment cost + item cost + service fees</td>
		</tr>";
	  
for ($i=$safe; $i<=10; $i++) {
    $per = $chances[$i] / $denoms[$i];
    $chance = sprintf("%.2f",$denoms[$i] / $chances[$i]);
	$dchance = sprintf("%d", $chance);	// integer form of $chance, rounded down
    $selling =  $base / $per;	// selling price of equipment, also cost of buying x amount of equips
    
    //$selling = sprintf("%f", $selling);
	
	$ori_num = $i * $dchance;	// just use the maximum amount of oris expected to be used
	$itemcost = $i * $ori * $dchance;	// estimated total cost of items used to refine
	$fee = $i * (($wlvl == 0) ? $service_fees[$wlvl] : 0) * $dchance;	// total service fee
	$total = $selling + $itemcost + $fee;
    
    print "<tr>
           <td>+$i</td>
           <td>". $per*100 ."%</td>".    //  ratio as a percentage
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
	global $wlvl;
    
    //$x = $percentrefinery[0][4];
    
    // Note: To print the values within an array in a print statement,
    // you should not enclose it in double quotes
    // BAD: print "$arrayname[$index]";
    // GOOD: print $arrayname[$index];
        
    //print_r($percentrefinery);
        
    print "<table border='1' cellpadding='2'>
		   <caption>Refine Success Rates (%)</caption>
		   <th>Equipment</th>";
    for ($i=1; $i<=10; $i++)
        print "<th>+$i</th>";
        
    for ($i=0; $i<=4; $i++) {
		if ($i == $wlvl) {
			print "<tr style='background-color: #ffff0f'>";
		} else {
			print "<tr>";
		}
		print "<td>". $names[$i] ."</td>";
        for ($j=1; $j<=10; $j++) {
            print "<td>" . $percentrefinery[$i][$j] . "</td>";
        }
        print "</tr>";
    }
    print "</table>";
}

?>