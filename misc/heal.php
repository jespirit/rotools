<html>

<head>
	<title>Heal Calculator</title>
</head>

<body>

<h1>Heal Calculator</h1>

<form name='healcalc' method='post' action='/php/heal.php'>
	<table>
		<tr>
			<td>Level:</td>
			<td><input type='text' name='lvl' /></td>
		</tr>
		<tr>
			<td>Heal:</td>
			<td>
				<select name='heallv'>
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
		<tr>
			<td>Meditatio:</td>
			<td>
				<select name='mdlv'>
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

	<input type='submit' name='submit' value='Submit' />
</form>

</body>

</html>

<?php

// heal =
// [(BaseLvl + INT)/8] * (4+8*SkillLv)

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$low = 0;
$high = 4000;   // default
$int = 0;
$x = 0;
$count = 0;

$level = $_POST["lvl"];    // get level from user
$heallv = $_POST["heallv"];
$mdlv = $_POST["mdlv"];

$k = 4+8*$heallv;

while ($k*$x <= $high) {
    $int = 8*$x - $level;
	if ($int <= 0) {
		$x++;
		continue;
	}

	if ($count%10==0) {
		print "<table border='1' cellpadding='1' style='float:left; margin-right:25px;' >" .
		  "<tr>" .
		  "<th>x</th><th>Heal</th><th>Meditatio</th><th>Int</th>" .
		  "</tr>";
	}
		
    $line = "<tr>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "</tr>\n";
            
    printf($line, $x, $k*$x, $k*$x*(100+$mdlv*2)/100, $int);
	
	if ($count%10==9) {
		print "</table>";
	}
    $x++;
	$count++;
}

if ($count%10<9) {
	print "</table>";
}

?>