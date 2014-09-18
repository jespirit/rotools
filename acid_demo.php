<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

include('forms/ad-form.php');

exit();
}

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
		  
$data = array(
		  // [total # ingredients to purchase, # of ingredients per TA3, 
		  //  name of ingredient, cost of ingredient, how many in possession]
          array(1, 200, "Empty Bottle",     6,   $GET_n1),
		  array(0, 0,   "Alcohol",          0,   $GET_n2),
		  array(1, 50,  "Fabric",           306, $GET_n3),
		  array(1, 200, "Medicine Bowl",    8,   $GET_n4),
		  
		  array(1, 50,  "Immortal Heart",   374, $GET_n5),	// 4
		  
		  array(1, 500, "Stem",             46,  $GET_n6),	// 5
		  array(1, 100, "Empty Test Tube",  3,   $GET_n7),	// 6
		  array(1, 500, "Poison Spore",     114, $GET_n8),	// 7
		);
		
$adnum = $GET_ad_num;
$per = $GET_per;
$ad_set = $GET_ad_set ? $GET_ad_set : 0;  // default to zero
$iheart = $GET_iheart;
$isnumeric = "^[0-9]+$";
$isdecimal = "^([0-9]{1,3}|[0-9]{1,3}.[0-9]{1,2})$";  // 0-100, 000.00-100.00

$skilllvl = $GET_discountlvl;
$discount = array(0, 7, 9, 11, 13, 15, 17, 19, 21, 23, 24);

// cost of ingredients for alcohol
$alcohol_ing = array(6, 46, 3, 114, 8);
$alcohol_amt = array(1,  5, 1,   5, 1);

$rem = 0;	// remainder of 50
$total_cost = 0;
$total_spend = 0;

$totals = array(
	"fb" => array(1, 1),
	"ab" => array(1, 1),
	);
    
// empty= "", 0, 0.0, "0", NULL, FALSE, array(), $var
// numeric= 0, 0.0, "0"
function nothing($x) {  // returns true if $x= "", NULL, FALSE, array(), $var
    return empty($x) && !is_numeric($x);
}

// validate the input fields

if (!preg_match("/$isnumeric/", $adnum)) {
	print "Number of AD Bottles must be a positive integer.<br />";
	exit();
}
else if ($adnum <= 0) {
	print "Please enter an amount greater than 0.<br />";
	exit();
}
else if (nothing($per) || !preg_match("/$isdecimal/", $per) || $per > 100) {
	print "Please enter a valid percentage for success chance ##.## in range 0.00-100.00<br />";
	exit();
}
else if (nothing($iheart) || !preg_match("/$isdecimal/", $iheart)) {
	print "The cost of Immortal heart must be a positive integer<br/>";
	exit();
}
else if (!preg_match("/$isnumeric/", $ad_set)) {
    print "Please enter a correct value for AD set.<br />";
    exit();
}

$data[4][3] = $iheart;  // Immortal Heart is no longer bought at npc

foreach ($data as &$val) {
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
unset($val);

// number of times to cast Twilight Alchemy III to reach the supposed AD #
$count = (int)($adnum / 50);
$rem = $adnum % 50;

// now calculate how many to buy for each ingredient
for ($i=0; $i<count($data); $i++) {
	$data[$i][0] = $count * $data[$i][1];  // twilight count * # of ingredients per twilight
	// apply discount
	if ($i != 4) {
		$data[$i][3] = sprintf("%d", $data[$i][3] * (100 - $discount[$skilllvl]) / 100);
	}
	$cost = $data[$i][0] * $data[$i][3];  // cost = final total * price
	$total_cost += $cost;
	
	// Calculate the cost to buy the ingredients you need
	$bought = $data[$i][4];
	$tobuy = $data[$i][0] - $data[$i][4];
	$total_spend += ($tobuy > 0) ? ($tobuy*$data[$i][3]) : 0;
}

// calculate the total cost to make alcohol 
for ($i=0; $i<count($alcohol_ing); $i++) {
	$price = (int)($alcohol_ing[$i] * (100 - $discount[$skilllvl]) / 100) * $alcohol_amt[$i];
	$data[1][3] += $price;
}

//$total_cost += $data[1][0] * $data[1][3];  // += total amount of alcohol * cost per alcohol

/* can't permanently assign values to the array */
/*
foreach ($data as $v) {
	print "before: $v[0] ";
	$v[0] = $count * $v[1];
	print "after: $v[0] <br />";
}*/

//$data[0][0] = 999;

/*
print "Results: ";
foreach ($data as $v) {
	print $v[0] ." ";
}*/

format2table();

make_bottles();

function format2table() {
	foreach ($GLOBALS as $key => $val) { global $$key; }
	
	print "
		<table class='acid'>
			<tr>
				<td>Attempts/AD:</td>
				<td>$count</td>
				<td>". number_format($count*50) ."</td></tr>
			<tr>
			  <td></td>
			  <td>Cost</td>
			  <td>Total Amount</td>
			  <td>Amount To Buy</td>
			  <td>Total Cost</td>
			  <td>Total Spend</td></tr>";
			
	foreach ($data as &$v) {
		$bought = $v[4];  // how many of the ingredient you have
		$tobuy = $v[0] - $v[4];  // how many more of it you need
		if ($tobuy < 0) {
			$v[4] = $tobuy * -1;  // ingredients carry over
			$tobuy = 0;
		}
		else {
			$v[4] = 0;
		}
			
		print "<tr>
			   <td>$v[2]</td>
			   <td>". number_format($v[3]) ."</td>
			   <td>". number_format($v[0]) ."</td>
			   <td> (". number_format($bought) .") ". number_format($tobuy) ."</td>
			   <td>". number_format($v[0]*$v[3]) ."</td>
			   <td>". number_format($tobuy * $v[3]) ."</td>
			   </tr>";
	}

	/* The actual costs of making the AD is total_cost + alcohol_50 - alcohol_sold
	   where alcohol_50 accounts for the 50 alcohol you need to make to begin alchemy
	   and alcohol_sold is the zeny gained by selling the total remaining alcohol
	   which effectively reduces the actual cost of the AD.
	   
	   From there you subtract the result from total_sales to get profit.
	   
	   alcohol_sold = (50 + 50x) * 248, where x is the number of alchemy attempts
	   
	   profit = total_sales - (total_cost + alcohol_50 - alcohol_sold)
	   
	 */

	$total_sales = $count * 50 * $ad_set;
	$alcohol_50 = 50 * 612;
	$alcohol_sold = (50 + 50 * $count) * 248;
	$operating_cost = $total_cost + $alcohol_50 - $alcohol_sold;
	$profit = $total_sales - $operating_cost;

	format2row("Cost to make:", $operating_cost);
	print "<tr><td>Formula:</td><td colspan='4'>".
		number_format($total_cost) ." + ". number_format($alcohol_50) ." - ". number_format($alcohol_sold) ."</td></tr>";
	format2row("Total Sales:", $total_sales);
	format2row("To spend:", $total_spend);
	format2row("Profit:", $profit);
	print "</table>";
}

function format2row($caption, $amount) {
print "<tr>
       <td>$caption</td><td colspan='3'>&nbsp</td>
	   <td>". number_format($amount) ."</td>
	   </tr>";
}

function make_bottles()
{
	foreach ($GLOBALS as $key => $val) { global $$key; }
	
	$total_cost = 0;

	/// Calculate how many ingredients you need to buy to make any remaining
	/// AD bottles ie. 1020 % 50 = 20

	for ($i=0; $i<5; $i++) {
		$data[$i][0] = $rem;
	}

	// empty bottle + medicine bowl is doubled in amount
	$data[0][0] = $data[3][0] = $rem * 2;

	// calculate the cost of any remaining AD
	for ($i=0; $i<5; $i++) {
		$cost = $data[$i][0] * $data[$i][3];
		$total_cost += $cost;
	}

	print "
		<table class='acid' style='float: left;'>
			<tr>
				<td>AD (Leftover):</td>
				<td>$rem</td></tr>
			<tr>
				<td>&nbsp</td>
				<td>Cost</td>
				<td>Total Amount</td>
				<td>Amount To Buy</td>
				<td>Total Cost</td>
			</tr>";
		  
		  
	for ($i=0; $i<5; $i++) {
		$tobuy = $data[$i][0] - $data[$i][4];  // = how many to buy in total - how many you already have
		// $tobuy can be a negative number which means you have excess in ingredients
		// from twilight that can be used to create any remaining AD
		if ($tobuy < 0)
			$tobuy = 0;
			
		print "
			<tr>
				<td>{$data[$i][2]}</td>
				<td>". number_format($data[$i][3]) ."</td>".  // cost per ingredient
				"<td>". number_format($data[$i][0]) ."</td>
				<td> (". number_format($data[$i][4]) .") ". number_format($tobuy) ."</td>
				<td>". number_format($data[$i][0]*$data[$i][3]) ."</td>
				</tr>";
	}

	print "
		<tr>
			<td>Total (-$discount[$skilllvl]%):</td>
			<td colspan='3'>&nbsp</td>
			<td>". number_format($total_cost) ."</td></tr>
		<tr>
			<td>Total (- Alcohol):</td>
			<td colspan='3'>&nbsp</td>
			<td>". number_format($total_cost - $data[1][0] * $data[1][3]) ."</td>
		</tr>
		</table>";

	if ($per == 0)
		$per = 100;
	$per *= 100;

	// get total cost for 1 fire bottle (empty bottle + alcohol + fabric + medicine bowl)
	$totals["fb"][0] = $data[0][3] + $data[1][3] + $data[2][3] + $data[3][3];
	$totals["fb"][1] = (int)($totals["fb"][0] * 10000 / $per);  // calculate adjusted selling price

	print "
		<table class='acid' style='float: left;'>
		<tr>
		  <th>Fire Bottle</th>
		  <td>Cost</td>
		</tr>";
	for ($x=0; $x<4; $x++) {
		print "
		<tr>
			<td>{$data[$x][2]}</td>
			<td>{$data[$x][3]}</td>
		</tr>";
	}
	print "
		<tr>
			<td>Total Price</td>
			<td>{$totals["fb"][0]}</td>
		</tr>
		<tr>
			<td>Adjusted Price</td>
			<td>{$totals["fb"][1]}</td>
		</tr>
		<tr>
			<td>Success Chance (%)</td>
			<td>". sprintf("%.2f", $per/100) ."</td>
		</tr>
		</table>";
		  
	$totals["ab"][0] = $data[0][3] + $data[4][3] + $data[3][3];
	$totals["ab"][1] = (int)($totals["ab"][0] * 10000 / $per);
		  
	print "
		<table class='acid'>".
		  "<tr>
			<th>Acid Bottle</th>
			<td>Cost</td>
		</tr>
		<tr>
			<td>{$data[0][2]}</td>".  // amount of ingredients
			"<td>{$data[0][3]}</td>".  // cost of ingredient
		"</tr>
		<tr>
			<td>{$data[4][2]}</td>
			<td>{$data[4][3]}</td>
		</tr>
		<tr>
			<td>{$data[3][2]}</td>
			<td>{$data[3][3]}</td>
		</tr>
		<tr>
			<td>Total Price</td>
			<td>{$totals["ab"][0]}</td>
		</tr>
		<tr>
			<td>Adjusted Price</td>
			<td>{$totals["ab"][1]}</td>
		</tr>
		<tr>
			<td>Success Chance (%)</td>
			<td>". sprintf("%.2f", $per/100) ."</td>
		</tr>
		</table>";
	}

?>