<html>
<head>
	<title>Attack Speed</title>
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
		<h2>Alchemist Ranking</h2>
		<form name='aspd_form' method='post' action='aspd.php'>
			<table>
				<tr>
					<td>Weapon Delay:</td>
					<td><input type='text' name='wpdelay' /></td>
				</tr>
				<tr>
					<td>AGI:</td>
					<td><input type='text' name='agi' /></td>
				</tr>
				<tr>
					<td>DEX:</td>
					<td><input type='text' name='dex' /></td>
				</tr>
				<tr>
					<td>Potion</td>
					<td>
						<ul>
							<li><input type='radio' name='potion' value='0' />Concentration Potion</li>
							<li><input type='radio' name='potion' value='1' />Awakening Potion</li>
							<li><input type='radio' name='potion' value='2' />Berserk Potion</li>
						</ul>
					</td>
				<tr>
					<td>Cards</td>
					<td><input type='text' name='card' /></td>
				</tr>
			</table>

			<input type='submit' name='submit' value='Submit' />
		</form>
	</div>
	
	<div id="footer">
		<p>Custom Website 2012-2014</p>
	</div>
</div>
</body>
</html>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();
	
$wpdelay = $_POST["wpdelay"];
$agi = $_POST["agi"];
$dex = $_POST["dex"];
$potion = $_POST["potion"];
$card = $_POST["card"];

$speed_pots = array(100, 150, 200);
$aspd_rate = $speed_pots[$potion] + $card*10;

// weapon_delay * (1 - (4*agi + dex)/1000)
$amotion = $wpdelay;
$amotion -= floor($amotion * (4*$agi + $dex)/1000);
$amotion = floor($amotion * (1000-$aspd_rate)/1000);
$aspd = (2000-$amotion)/10;

print "weapon delay: $wpdelay"."<br />".
	  "AGI: $agi"."<br />".
	  "DEX: $dex"."<br />".
	  "Potion: $potion"."<br />".
	  "Card: $card"."<br />".
	  "Aspd Rate: $aspd_rate"."<br />";

print "amotion=$amotion"."<br />".
	  "aspd=" . floor($aspd);
?>