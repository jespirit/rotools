<?php

$wlvl = $_GET["wlvl"];
if ($wlvl == 0)
	exit();
$limits = array(-1, 5, 8, 7, 6, 5);
$safe = $limits[$wlvl];

echo '
<table>
<tr>';

foreach (range($safe, 10) as $x) {
	print "<td style='text-align: center;'>+$x</td>";
}
echo '
	</tr>
	<tr>';
	
foreach (range($safe, 10) as $n) {
	if ($n == $safe)	// default selected value
		print "<td class='select-upgrade'><input type='radio' name='rtarget' checked='checked' value='$n'></td>";
	else
		print "<td class='select-upgrade'><input type='radio' name='rtarget' value='$n'></td>";
}

echo '
</tr>
</table>';

?>