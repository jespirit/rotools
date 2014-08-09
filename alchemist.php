<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

echo <<<EOF
<script>
function toggleMenu(objID) {
	if (!document.getElementById) return;
	var obj = document.getElementById(objID).style;
	obj.display = (obj.display == 'block')?'none':'block';
}

// wrap in parens for the function to be treated as an expression and not a declaration
(function() {
	$("#twilight-chk").css("display", "none");
	$("#twilight").attr("disabled", true);  // disable checkbox
	
	$("[name='potion']").change(function() {
		var x = $(this).val();
		if (x == 4) {  // white potion
			$("#twilight-chk").css("display", "block");
			$("#twilight").removeAttr("disabled");  // enable checkbox
		}
		else {
			$("#twilight-chk").css("display", "none");
			$("#twilight").attr("disabled", true);  // disable checkbox
			$("#twilight").attr("checked", false);
		}
	});
}());
</script>
	
<h2>Alchemist Ranking</h2>
<form id="alchemist" name="alchemist" onsubmit="return GET_ajax('alchemist.php', 'alchemist_div', 'alchemist');">
	<table>
		<tr>
			<td>Job Level:</td>
			<td><input type='text' name='jlvl'></td></tr>
		<tr>
			<td>DEX:</td>
			<td><input type='text' name='dex'></td></tr>
		<tr>
			<td>INT:</td>
			<td><input type='text' name='int'></td></tr>
		<tr>
			<td>LUK:</td>
			<td><input type='text' name='luk'></td></tr>
		<tr>
			<td>Blessing:</td>
			<td><input type='checkbox' name='blessing'></td></tr>
		<tr>
			<td>Gloria:</td>
			<td><input type='checkbox' name='gloria'></td></tr>
		<tr>
			<td>Gospel:</td>
			<td><input type='checkbox' name='gospel'></td></tr>
EOF;
		print "
				<tr>
				<td>Learning Potion Skill</td>
				<td>
					<select name='lpot_lv'>";
		for ($i=1; $i<=10; $i++) {
			print "<option value='$i'>Lv $i</option>";
		}
		print "
				</select>
				</td>
				</tr>";
			   
		print "
				<tr>
				<td>Pharmacy Skill</td>
				<td>
					<select name='pharm_lv'>";
		for ($i=1; $i<=10; $i++) {
			print "<option value='$i'>Lv $i</option>";
		}
		print "
				</select>
				</td>
				</tr>";
			   
		print "
				<tr>
				<td>Homunculus Level</td>
				<td>
					<select name='hom_lv'>";
		for ($i=1; $i<=99; $i++) {
			print "<option value='$i'>Lv $i</option>";
		}
		print "
				</select>
				</td>
				</tr>";
		
		$types = array('Red, Yellow, White Potion', 'Blue Potion',
					   'Condensed Red Potion', 'Condensed Yellow Potion', 'Condensed White Potion');
					   
		for ($i=0; $i<count($types); $i++) {
			if ($i == 0)	// default selected value
				print "<tr><td><input type='radio' name='potion' checked='checked' value='$i' />" . $types[$i] . "</td></tr>";
			else
				print "<tr><td><input type='radio' name='potion' value='$i' />" . $types[$i] . "</td></tr>";
		}
			
echo <<<EOF
		<tr>
			<td>Amount:</td>
			<td><input type='text' name='qty' /></td>
			<td>
				<div id='twilight-chk'>
					<input type='checkbox' name='twilight' id='twilight' value='1' />Twilight?
				</div>
			</td>
		</tr>
	</table>

	<input type='submit' name='submit' value='Submit' />
</form>

<div id='alchemist_div'></div>
EOF;

exit();
}

$jlvl = $GET_jlvl;
$dex = $GET_dex;
$int = $GET_int;
$luk = $GET_luk;
$lpot_lv = $GET_lpot_lv;
$pharm_lv = $GET_pharm_lv;
$hom_lv = $GET_hom_lv;

$blessing = $GET_blessing;
$gloria = $GET_gloria;
$gospel = $GET_gospel;

if ($blessing) {
	$dex += 10;
	$int += 10;
}

if ($gloria)
	$luk += 30;

if ($gospel) {
	$dex += 20;
	$int += 20;
	$luk += 20;
}
	
$per = $lpot_lv*50 + $pharm_lv*300 + $jlvl*20 + ($int/2)*10 + $dex*10 + $luk*10
	+ (5 + 5*$hom_lv);

$qty = $GET_qty;
$potion = $GET_potion;
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

if (!preg_match("/$isdecimal/", $dex)) {
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

if ($GET_twilight) {
	// quantity now represents how many Twilight Alchemys to perform
	$twilight = 200;
}
else
	$twilight = 1;

for ($x=0; $x<$qty; $x++) {
    
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

echo '
<table border="1">
	<tr>
		<td>Type</td>
		<td>'. $types[$potion] .'</td>
	</tr>
	<tr>
		<td>Potions Made</td>
		<td>'. number_format($pots) .'</td>
	</tr>
	<tr>
		<td>Attempts</td>
		<td>'. number_format($qty) / number_format($twilight) .'</td>
	</tr>
	<tr>
		<td>Fame Points</td>
		<td>'. number_format($fame) .'</td>
	</tr>
	<tr>
		<td>Success Rate (%)</td>
		<td>'. sprintf("%.2f%% %.2f%% %.2f%%", $rates[$potion][0]/100, $rates[$potion][1]/100, $rates[$potion][2]/100) .'</td>
	</tr>
	<tr>
		<td>Fame Points/Attempts</td>
		<td>'. $pointrate .'</td>
	</tr>
</table>';

?>