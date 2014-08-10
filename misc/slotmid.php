<html>
<head>
    <title>Slotted Mid Headgear</title>
<body>

<h1>Slotted Mids</h1>
<form name='slotmid' method='post' action='slotmid.php'>
	<h2>Ingredients</h2>
    <table>
		<tr>
            <td>2x Blue Transparent Plate</td>
            <td><input type='text' name='btp' value='400000' /></td>
			<td>Total: </td>
        </tr>
		<tr>
            <td>2x Green Transparent Plate</td>
            <td><input type='text' name='gtp' value='400000' /></td>
			<td>Total: </td>
        </tr>
		<tr>
            <td>2x Red Transparent Plate</td>
            <td><input type='text' name='rtp' value='400000' /></td>
			<td>Total: </td>
        </tr>
		<tr>
            <td>2x Orange Transparent Plate</td>
            <td><input type='text' name='otp' value='400000' /></td>
			<td>Total: </td>
        </tr>
        <tr>
            <td>5x Old Blue Box</td>
            <td><input type='text' name='obb' value='70000' /></td>
			<td>Total: </td>
        </tr>
        <tr>
            <td>25x Crystal Mirror</td>
            <td><input type='text' name='cmr' value='10000' /></td>
			<td>Total: </td>
        </tr>
		<tr>
            <td>30x Steel</td>
            <td><input type='text' name='steel' value='6000' /></td>
			<td>Total: </td>
        </tr>
		<tr>
            <td>200x Crystal Fragment</td>
            <td><input type='text' name='cf' value='5000' /></td>
			<td>Total: </td>
        </tr>
    </table>

    <input type='submit' name='submit' value='Submit' />
</form>

</body>
</html>

<?php

// 8 ingredients
// - 4 coloured transparent plates (2x each)
// - old blue box
// - crystal mirror
// - steel
// - crystal fragment

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$values = array($_POST["btp"], $_POST["gtp"], $_POST["rtp"], $_POST["otp"],
                $_POST["obb"], $_POST["cmr"], $_POST["steel"], $_POST["cf"]
			   );
			
$names = array("Blue Transparent Plate", "Green Transparent Plate", 
               "Red Transparent Plate", "Orange Transparent Plate",
               "Old Blue Box", "Crystal Mirror", "Steel", "Crystal Fragment"
			  );
			  
$base_zeny = 1500000;
$amounts = array(2, 2, 2, 2, 10, 25, 30, 200);
$totals = array();

$total = $base_zeny;
			  
$isnumeric = "^[0-9]+$";

// fields must be positive integers only

$i=0;
foreach ($values as $v) {
	if (!preg_match("/$isnumeric/", $v)) {
		print "Error! ". $names[$i] ." must be a positive integer.<br />";
		exit();
	}
	$i++;	// oops, have to increment outside the if statement
}

$i=0;
foreach ($values as $v) {
	$totals[$i] = $v * $amounts[$i];
	$total += $totals[$i];
	$i++;
	//print $names[$i++] ." = ". $v ."<br />";
}

print "<table border='1'>";
	  
$i=0;
foreach ($values as $v) {
	print "<tr>".
	      "<td>$names[$i]</td>".
	      "<td>". number_format($v * $amounts[$i]) ."</td>".
		  "<td>". number_format($v) ."</td>".
		  "</tr>";
	$i++;
}

print "<tr>".
      "<td>Total:</td>".
	  "<td>". number_format($total) ."</td>".
	  "</tr>".
      "</table>";

?>