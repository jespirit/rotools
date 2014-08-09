<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

echo <<<EOF
<h1>Refine Rate Simulator</h1>
<form id='refine_form' name='refine_form' onsubmit="return GET_ajax('refine.php', 'refine_div', 'refine_form');">
	<table>
		<tr><td>Equipment Name:</td><td><input type='text' name='eqname' /></td></tr>
		<tr><td>Market Price:</td><td><input type='text' name='eqprice' /></td></tr>
		<tr><td>Oridecon/Elunium Price:</td><td><input type='text' name='eluprice' /></td></tr>
		<tr>
			<td>Weapon Level:</td>
			<td>
				<select name='wlvl' onchange="LINK_ajax('ajax/update-equip-refine.php?wlvl='+this.value, 'equip-refine', '');">
				<option value='0'>-</option>
				<option value='1'>Armor</option>
EOF;
			foreach (range(1, 4) as $x) {
				printf("<option value='%d'>%d</option>", $x+1, $x);
			}
echo <<<EOF
				</select>
			</td>
		</tr>
		<tr>
			<td>Job Level:</td>
			<td>
				<select name='jlvl'>
EOF;
					foreach (range(1,70) as $x) {
						printf("<option value='%d'>%d</option>", $x, $x);
					}
echo <<<EOF
				</select>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td>Upgrade:</td>
			<td id='equip-refine'></td>
		</tr>
	</table>
	<input type='submit' name='submit' value='Refine' />
</form>
<div id='refine_div'></div>

EOF;

exit();
}

// 5x11
$percentrefinery = 
    array(
        //       +1   +2   +3   +4   +5   +6   +7   +8  +9  +10
        array(4, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),
        array(7, 100, 100, 100, 100, 100, 100, 100, 60, 40, 20),
        array(6, 100, 100, 100, 100, 100, 100, 60,  40, 20, 20),
        array(5, 100, 100, 100, 100, 100, 60,  50,  20, 20, 20),
        array(4, 100, 100, 100, 100, 60,  40,  40,  20, 20, 10),
    );
   
$service_fees = array(2000, 50, 200, 5000, 20000);
$names = array("Armor", "Weapon Lv 1", "Weapon Lv 2", "Weapon Lv 3", "Weapon Lv 4");
$items = array("Elunium", "Phracon", "Emveretarcon", "Oridecon", "Oridecon");

$wlvl = $GET_wlvl;
$jlvl = $GET_jlvl;

$eqname = $GET_eqname;
$eqprice = $GET_eqprice;
$rtarget = $GET_rtarget;
$eluprice = $GET_eluprice;

$alpha = "^[a-zA-Z_ \[\]0-9()]+$";  // space, square brackets are allowed
                                 // ie. Guard[1], Shoes[1]
$integer_only = "^[0-9]+$";

// validate form fields here
$valid = true;

if (empty($eqname)) {
    print "You did not set an equipment name.\n";
    $valid = false;
} else if (!preg_match("/$alpha/", $eqname)) {
    print "You are only allowed to use the following characters: / a-zA-Z0-9[]()/\n";
    $valid = false;
} else if (nothing($eqprice)) {
    print "You left the equipment price empty\n";
    $valid = false;
} else if (!preg_match("/$integer_only/", $eqprice)) {
    print "Price must be a positive integer.\n";
    $valid = false;
} else if (nothing($eluprice)) {
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

if ($wlvl == 0) {
	print "Error. Please select a weapon level.<br />";
	exit();
}

$wlvl--;

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

$count = 0;
$elu_num = 0;
$equip_refine = $percentrefinery[$wlvl][0];
$service_cost = 0;
$num_equips = 1;
$total = 0;

DEFINE('MAX_REFINE', 10);

print_table();

do
{
    if (rand(0, 99) < $percentrefinery[$wlvl][$equip_refine+1]) {
        $equip_refine++;
        print "Success. You made a +$equip_refine $eqname <br />";
    }
    else {
        print "Failure. You broke it at +$equip_refine <br />";
        $equip_refine = $percentrefinery[$wlvl][0];
        $num_equips++;
    }
    
    $count++;
    $elu_num++;
    $service_cost += $service_fees[$wlvl];  // 2000z for each armor upgrade
    
} while ($equip_refine < MAX_REFINE && $equip_refine != $rtarget);

$equipcost = $eqprice * $num_equips;
$itemcost = $elu_num * $eluprice;
$total = $equipcost + $itemcost + $service_cost;

print "Great. It took you only $num_equips $eqname's to make a +$rtarget $eqname <br />";
print "You've used up $elu_num $items[$wlvl] and spent \$". number_format($total) ."<br />";
print "Total Cost = ". number_format($equipcost) ." + ". number_format($itemcost) ." + ". number_format($service_cost) ."<br />";

// empty= "", 0, 0.0, "0", NULL, FALSE, array(), $var
// numeric= 0, 0.0, "0"
function nothing($x) {  // returns true if $x= "", NULL, FALSE, array(), $var
    return empty($x) && !is_numeric($x);
}

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