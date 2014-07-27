<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Monster Drop Editor</title>
	<style type='text/css'>
		table { border: 1px solid; }
		td { border: 1px solid; }
	</style>
</head>
<body>
<form name='stealform' method='post' action='monster-drop-edit.php'>
	<table>
		<tr>
			<td>Monster</td>
			<td>
			<select name='monsterid' onchange='displayInfo(this.value)'>
			<?php
			
				$id = "ragnarok";
				$pass = "Fish";
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
					// Monster database contains duplicate monsters (mvp spawns, no spawn monsters)
					
					//$sql = "SELECT * FROM $table1 WHERE STRCMP(SUBSTRING(SPRITE, 1, 2), 'G_') != 0 ORDER BY iname ASC;";
					$sql = "SELECT * FROM $table1 WHERE SPRITE NOT REGEXP '^G_|^E_' ORDER BY iName ASC;";
					$result = mysql_query($sql, $dbi);
					while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
						printf("<option value='%d'>%s (%s)</option>", $line['ID'], $line['iName'], $line['Sprite']);
					}
					mysql_close($dbi);
				}
			?>
			</select>
			</td>
		</tr>
	</table>
	<div id='monster_drops'></div>
	<input type='submit' value='Submit' name='submit' />
</form>

<script type='text/javascript'>
function displayInfo(id) 
{
/*
	if (id=="")
	{
		document.getElementById("txtHint").innerHTML="";
		return;
	}*/
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("monster_drops").innerHTML=xmlhttp.responseText;
			}
		}
	xmlhttp.open("GET","get-monster-drops.php?q="+id,true);
	xmlhttp.send();
}
</script>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();
	
$MAX_ITEM_DROP = 10;
$monsterid = $_POST["monsterid"];
$monster_name = "";

$drops = array(
			//    0                       1                         2          3           4
			//    drop id,                drop %,                   drop name, drop count, drop price
			array('id'=>$_POST['Drop1id'], 'per'=>$_POST['Drop1per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop2id'], 'per'=>$_POST['Drop2per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop3id'], 'per'=>$_POST['Drop3per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop4id'], 'per'=>$_POST['Drop4per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop5id'], 'per'=>$_POST['Drop5per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop6id'], 'per'=>$_POST['Drop6per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop7id'], 'per'=>$_POST['Drop7per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop8id'], 'per'=>$_POST['Drop8per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop9id'], 'per'=>$_POST['Drop9per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$_POST['Drop10id'], 'per'=>$_POST['Drop10per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
		);

// Validate fields

$isnumeric = "^[0-9]+$";
$isdecimal = "^[0-9]{1,3}$|^[0-9]{1,3}.[0-9]{1,2}$";	// 0-100, 000.00-100.00

$i = 0;
foreach ($drops as $drop) {
	if (!preg_match("/$isnumeric/", $drop['id'])) {
		printf("Invalid ID in slot %d\n", $i+1);
		exit( );
	}
	//else if (!preg_match("/$isdecimal/", $drop['per']) || $drop['per'] > 100) {
	else if (!preg_match("/$isnumeric/", $drop['per'])) {
		printf("Percentage has invalid format in slot %d must be ###.##\n", $i+1);
		exit( );
	}
}

$i = 0;
$table1 = "mob_db";
	
$id = "ragnarok";
$pass = "Fish";
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
	
	$line = mysql_fetch_array($result, MYSQL_NUM);
	
	$monster_name = $line[3];
	
	// Absolute path does not work with / a slash only, but works when using $_SERVER['DOCUMENT_ROOT']
	//$file = sprintf("%s\\php\\steal\\sql-script\\monster-%d.sql", $_SERVER['DOCUMENT_ROOT'], $monsterid);
	// Relative path from the current directory work fine.
	$file = sprintf("sql-script/monster-%d.sql", $monsterid);
	$fp = fopen($file, "a");	// append
	
	if (!$fp) {
		print "Failed to open file: " . $file;
		exit( );
	}
	
	$output = "";
	for ($i=0; $i<count($line); $i++) {
		if (is_null($line[$i])) {	// Check for NULL values
			$output = $output . "NULL,";
		} else if (is_number($line[$i])) {
			$output = $output . sprintf("%d,", $line[$i]);
		} else {
			$output = $output . sprintf("'%s',", mysql_escape_string($line[$i]));
		}
	}
	
	// Remove the last comma.
	$pos = strrpos($output, ',');
	if ($pos != false)
		$output = substr($output, 0, $pos);
	
	// Save the record as a REPLACE statement
	$sql_replace = sprintf("REPLACE INTO mob_db VALUES(%s);\n", $output);
	
    // The REPLACE statement can be used to restore the record to a previous state.
	fputs($fp, $sql_replace);
	
	// Overwrite the old values with the new values.
	$update = "";
	
	$x = 0;
	foreach ($drops as $drop) {
		if ($x == $MAX_ITEM_DROP - 1)
			$update = $update . sprintf("DropCardid=%d,DropCardper=%d,", $drop['id'], $drop['per']);
		else
			$update = $update . sprintf("Drop%did=%d,Drop%dper=%d,", $x+1, $drop['id'], $x+1, $drop['per']);
		$x++;
	}
	
	// Remove the last comma.
	$pos = strrpos($update, ',');
	if ($pos != false)
		$update = substr($update, 0, $pos);
		
	$sql_update = "UPDATE mob_db SET " . $update . sprintf(" WHERE ID='%d';", $monsterid);
	
	print "$sql_update\n";
	
	$result = mysql_query($sql_update, $dbi);
	if ($result) {
		print "Successfully updated the monster drops for " . $monster_name;
	} else {
		print "Failed to update the monster drops for " . $monster_name;
	}
	
	fclose($fp);

	mysql_close($dbi);
}

function is_number($str)
{
	$result = false;
	$isinteger = "^[+-]?[0-9]+$";	// positive/negative integer
	$isdecimal = "^[+-]?[0-9]+\.[0-9]+$";	// positive/negative decimal
	
	if (preg_match("/$isinteger/", $str) || preg_match("/$isdecimal/", $str))
		$result = true;
		
	return $result;
}
?>
</body>

</html>