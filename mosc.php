<html>
<body>

<title>Moscovia Loot</title>

<h1>Moscovia Loot</h1>

<form name="loot" action="mosc.php" method="post">
<table border='0' cellspacing='2'>
    <tr>
        <td><img src="/images/747.gif" alt="Crystal Mirror" width="24" height="24" /></td> 
        <td>Crystal Mirror:</td>
        <td><input type="text" name="mirrors" /></td>
    </tr>
    <tr>
        <td><img src="/images/748.gif" alt="Witherless Rose" width="24" height="24" /> </td>
        <td>Witherless Rose: </td>
        <td><input type="text" name="roses"/> </td>
    </tr>
	<tr>
        <td><img src="/images/510.gif" alt="Blue Herb" width="24" height="24" /> </td>
        <td>Blue Herb: </td>
        <td><input type="text" name="bherbs"/> </td>
    </tr>
	<tr>
		<td>Success Chance (%)</td>
		<td><input type="text" name="per" value="99.00"/></td>
	</tr>
</table>
    
<br />
<input type="submit" name='submit' value="Evaluate" />

</form>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$per = $_POST["per"];

$isdecimal = "^([0-9]{1,3}|[0-9]{1,3}\\.[0-9]{1,2})$";

if (!preg_match("/$isdecimal/", $per) || $per > 100.00) {
	print "Error. ";
	print "Success chance must be in format ###.## and in range 0.00-100.00<br />";
	exit();
}

$total = 0;
$skilllvl = 10;
$overcharge = array(7, 9, 11, 13, 15, 17, 19, 21, 23, 24);
$data = array(
            "mirrors" => array($_POST["mirrors"], "Crystal Mirror", "/images/747.gif", 7500),
            "roses"   => array($_POST["roses"], "Witherless Rose", "/images/748.gif", 27500),
			"bherbs"  => array($_POST["bherbs"], "Blue Herb", "/images/510.gif", 2500),	// 3100 - (319 + 7 + 6)
                                                                                        // 3100 - 332
                                                                                        // 2768
        );
        
print "<table border='1' cellpadding='10' >" .
      "<tr>" .
      "<th>Item</th><th>Amount</th><th>Charge</th><th>Zeny</th>" .
      "</tr>";

foreach ($data as $k=>$item) {
    // round value with sprintf
    $value = sprintf("%d", $item[3] * (100 + $overcharge[$skilllvl-1]) / 100);  // overcharge value
    $value -= ($k == "bherbs") ? ($value*(100-$per)/100 + 332) : 0;  // factor in brewing chance deduction
    $zeny = $item[0] * $value;  // amount * final value
    $total += $zeny;
    
    $amount = ($item[0] > 0) ? $item[0] : '&nbsp;'; # show empty cell?
    $zenyfm = "&nbsp;";
    
    if ($zeny > 0)
        $zenyfm = number_format($zeny);
    
    print "<tr>" .
          "<td><img src='" . $item[2] . "' alt='" . $item[1] . "' width='24' height='24' /></td>" .
          "<td>$amount</td>" .
          "<td>". (($k=="bherbs")?"2500*1.24-332=":"") . number_format($value) ."</td>" .
          "<td>$zenyfm</td>" .
          "</tr>";
}

print "<tr>" .
      "<td>Total</td>" .
      "<td colspan='3' align='right'>" . number_format($total) . "</td>" .
      "</tr>";

print "</table>";

?>

</body>
</html>