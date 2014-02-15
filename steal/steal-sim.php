<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Steal Simulator</title>
	<style type='text/css'>
		table { border: 1px solid; }
		td { border: 1px solid; }
	</style>
</head>
<body>
<form name='stealform' method='post' action='steal-sim.php'>
	<table>
		<tr>
			<td>DEX</td>
			<td><input type='text' name='dex' /></td>
			<td>Item Drop Rate</td>
			<td><input type='text' name='droprate' /></td>
			<td>Number of Monsters</td>
			<td><input type='text' name='num_monsters' /></td>
		</tr>
		<tr>
			<td>Steal</td>
			<td>
				<select name='steallvl'>
				<?php
					foreach (range(1,10) as $x) {
						if ($x == 10)
							printf("<option value='%d' selected='selected'>%d</option>", $x, $x);
						else
							printf("<option value='%d'>%d</option>", $x, $x);
					}
				?>
				</select>
			</td>
			<td>Monster</td>
			<td>
			<select name='monsterid'>
			<?php
			
				$id = "ragnarok_user";
				$pass = "L0rdZ3rius";
				$dbase = "ragnarok"; // name of "schema" in MySQL not database instance

				//                    host:       user:    	passwd:
				$dbi = mysql_connect("localhost",  $id, 		$pass);

				// YOUR int322_111[ab]nn ID GOES HERE...
				mysql_select_db($dbase);

				if(mysql_error()) {
					printf("connect failed: %s\n", mysql_errno( ));
					exit();
				}
				else {
					$table1 = "mob_db";
					$sql = "SELECT * FROM $table1 ORDER BY iname ASC;";
					$result = mysql_query($sql, $dbi);
					while($line = mysql_fetch_array($result, MYSQL_NUM)) {
						printf("<option value='%d'>%s</option>", $line[0], $line[3]);
					}
					mysql_close($dbi);
				}
			?>
			</select>
			</td>
			<td>Merchant Overcharge</td>
			<td>
				<select name='overchargelvl'>
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
	<input type='submit' value='Submit' name='submit' />
</form>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();
	
$MAX_STEAL_DROP = 7;
$monsterid = $_POST["monsterid"];

$droprate = $_POST["droprate"];
$steallvl = $_POST["steallvl"];
$overchargelvl = $_POST["overchargelvl"];
$dex = $_POST["dex"];
$total = 0;

$overcharge = array(0, 7, 9, 11, 13, 15, 17, 19, 21, 23, 24);

$num_monsters = $_POST["num_monsters"];
$rate = 0;
$monster_dex = 0;
$steal_attempts = 0;
$rate = 0;

$drops = array( );

// Validate fields

$isnumeric = "^[0-9]+$";
$isdecimal = "^[0-9]{1,3}$|^[0-9]{1,3}.[0-9]{1,2}$";

if (!preg_match("/$isnumeric/", $dex) || $dex < 0) {
	print "DEX cannot be a negative value.<br />";
	exit();
}
else if (!preg_match("/$isnumeric/", $droprate) || $droprate <= 0) {
	print "Item Drop Rate must be a positive integer.<br />";
	exit();
}
else if (!preg_match("/$isnumeric/", $num_monsters) || $num_monsters <= 0) {
	print "Number of monsters cannot be less than or equal to 0.<br />";
	exit();
}
else if (!preg_match("/$isnumeric/", $dex) || $dex < 0) {
	print "DEX cannot be a negative value.<br />";
	exit();
}

$i = 0;
$table1 = "mob_db";
	
$id = "ragnarok_user";
$pass = "L0rdZ3rius";
$dbase = "ragnarok"; // name of "schema" in MySQL not database instance

//                    host:       user:    	passwd:
$dbi = mysql_connect("localhost",  $id, 		$pass);

// YOUR int322_111[ab]nn ID GOES HERE...
mysql_select_db($dbase);

if(mysql_error()) {
    printf("connect failed: %s\n", mysql_errno( ));
    exit();
}
else {

	$sql = "SELECT * FROM $table1 WHERE ID='$monsterid';";
	$result = mysql_query($sql, $dbi);
	
	print "<pre>";
	print "mysql>$sql\n";
	
	$line = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$monster_dex = $line["DEX"];	// Monster's DEX
	
	// Base skill success chance
	$rate = ($dex - $monster_dex)/2 + $steallvl*6 + 4;

	if ($rate < 1)
		$rate = 0;
	
	$drops = array(
			//    0                       1                         2          3           4
			//    drop id,                drop %,                   drop name, drop count, drop price
			array('id'=>$line['Drop1id'], 'per'=>$line['Drop1per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop2id'], 'per'=>$line['Drop2per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop3id'], 'per'=>$line['Drop3per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop4id'], 'per'=>$line['Drop4per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop5id'], 'per'=>$line['Drop5per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop6id'], 'per'=>$line['Drop6per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop7id'], 'per'=>$line['Drop7per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop8id'], 'per'=>$line['Drop8per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop9id'], 'per'=>$line['Drop9per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['DropCardid'], 'per'=>$line['DropCardper'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
		);
		
	$total_chance = 1;
	$i = 0;
	
	// Get the name of the drop and store in '$drops' array.
	foreach ($drops as &$drop) {	// note the '&' used to modify the variable
		if ($drop['id'] > 0) {
			$query = sprintf("SELECT * FROM item_db WHERE id='%d'", $drop['id']);
			$result2 = mysql_query($query);
			$line2 = mysql_fetch_array($result2, MYSQL_ASSOC);
			$drop['name'] = $line2['name_japanese'];
			$drop['sell'] = floor($line2['price_buy']/2 * (100+$overcharge[$overchargelvl]) / 100);
			// Modify drop rate.
			$drop['per'] *= $droprate;
			if ($drop['per'] > 10000)	// Limit to 100%
				$drop['per'] = 10000;
				
            // The drop rate is reduced by the player's steal rate.
			$drop['adj'] = floor($drop['per'] * $rate/100);
			
			/* To calculate the percentage to steal a drop, for instance, to steal
			   a Crystal Mirror in the 6th slot from a Mavka would require unsuccessful
			   attempts from slots 1-5.
			   
			   (100-x1)/100 * (100-x2)/100 * (100-x3)/100 etc...
			   
			   where x is the percentage of a drop from slot 1 onward (needs to be 10000-x1/10000)
			 */
			 
            // only allowed to steal up to the 7th slot
			if ($i >= $MAX_STEAL_DROP)
				continue;
			
			// Calculate the steal percent or 'sper' the percentage is calculated
            // later as when it needs to be displayed by multiplying by 100
			$drop['sper'] = ($total_chance * $drop['adj']/100) / pow(100, $i+1);
            // total_chance is the accumulated dividend across items (100-x1) * (100-x2) * (100-x3) ...
			$total_chance *= (($drop['adj'] < 10000) ? 10000-$drop['adj'] : 10000)/100;
			
			printf("chance=%f / adj=%f\n", $total_chance, pow(100, $i+1));
			
			//printf("%d %.2f%% %.3f%% %s %d\n", $drop['id'], $drop['per']/100, $drop['sper']*100, $drop['name'], $drop['sell']);
			
			$i++;
		}
	}
	// Note: When referenced, the $value still refers to the last element in the array.
	// You have to destroy the reference with unset().
	unset($drop);
		
	// Calculate the average total over $N runs
	$N = 100;
	$final_total = 0;
	$total_avg = 0;
	
	set_time_limit(0);
	
	for ($n=0; $n<$N; $n++) {
		$steal_attempts = 0;
		
		// Reset count total for each item.
		foreach ($drops as $key=>&$drop) {
			if ($drop['id'] > 0)
				$drop['count'] = 0;
		}
		unset($drop);
		
		// Steal an item from x amount of monsters.
		for ($count = 0; $count < $num_monsters; ) {
			// Try dropping an item in order from first to last possible slot.
			for ($i = 0; $i < $MAX_STEAL_DROP; $i++) {
				if ($drops[$i]['id'] > 0 && rand(0,10000-1) < $drops[$i]['adj'])
					break;
			}
			
			$steal_attempts++;	// Count the number of attempts.
			if ($i == $MAX_STEAL_DROP)
				continue;
				
			$count++;
			$drops[$i]['count']++;
		}
		
		$total = 0;
		
		foreach ($drops as $key=>$drop) {
			if ($drop['id'] > 0) {
				$total += $drop['count'] * $drop['sell'];
			}
		}
		
		$final_total += $total;
	}
	
	$total_avg = floor($final_total/$N);
	
	printf("Runs: %d, Total Monsters: %d\n", $N, $num_monsters);
	printf("Total Average: %s\n", number_format($total_avg));
	
	printf("Player's DEX: %d\n", $dex);
	printf("Monster's DEX: %d\n", $monster_dex);
	printf("Steal Skill Chance: %d%%\n", $rate);
	printf("Steal Attempts: %d", $steal_attempts);
	
	print "<table>".
	      "<tr>".
		  "<th>Name</th><th>Drop Chance (%)</th><th>Adjusted %</th><th>Steal %</th><th># Total</th><th>Sell</th>";
	foreach ($drops as $key=>$drop) {
		if (!($drop['id'] > 0))
			continue;

		printf("<tr>");
		printf("<td>%s</td><td>%.2f%%</td><td>%.2f%%</td><td>%.3f%%</td><td>%d</td><td>%s</td>", 
			$drop['name'], $drop['per']/100, $drop['adj']/100, $drop['sper']*100, $drop['count'], number_format($drop['sell']));
		printf("</td></tr>");
	}
	printf("<tr><td>Total</td><td>%s</td></tr>", number_format($total));
	print "</table>";
	
	print "</pre>";

	mysql_close($dbi);
}
?>
</body>

</html>