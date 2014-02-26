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
</table>
    
<br />
<input type="submit" name='submit' value="Evaluate" />

</form>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$total = 0;

$skilllvl = 10;
$overcharge = array(7, 9, 11, 13, 15, 17, 19, 21, 23, 24);

$keys = array("mirrors", "roses", "bherbs");

$data = array(
            "mirrors" => array($_POST["mirrors"], "Crystal Mirror", "/images/747.gif", 7500),
            "roses"   => array($_POST["roses"], "Witherless Rose", "/images/748.gif", 27500),
			"bherbs"  => array($_POST["bherbs"], "Blue Herb", "/images/510.gif", 3100),	// 3100 - (319 + 10 + 6)
                                                                                        // 3100 - 335
                                                                                        // 2765
        );
        
print "<table border='1' cellpadding='10' >" .
      "<tr>" .
      "<th>Item</th><th>Amount</th><th>Charge</th><th>Zeny</th>" .
      "</tr>";

foreach ($keys as $k=>$v) {
    # round value with sprintf
    $value = sprintf("%d", $data[$v][3] * (100 + $overcharge[$skilllvl-1]) / 100);  # overcharge value
    $value -= ($v == "bherbs") ? 335 : 0;
    $zeny = $value * $data[$v][0];   # amount * overcharge value
    $total += $zeny;
    
    $amount = ($data[$v][0] > 0) ? $data[$v][0] : '&nbsp;'; # show empty cell?
    $zenyfm = "&nbsp;";
    
    if ($zeny > 0)
        $zenyfm = number_format($zeny);    # format_zeny($zeny);
    
    print "<tr>" .
          "<td><img src='" . $data[$v][2] . "' alt='" . $data[$v][1] . "' width='24' height='24' /></td>" .
          "<td>$amount</td>" .
          "<td>". number_format($value) ."</td>" .
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