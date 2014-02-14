<?php

$id = "ragnarok";
$pass = "ragnarok";
$dbase = "ragnarok";

$file = "results.txt";
$table = "mob_db";

$str = "";
$result;

$i=0;
$count = 0;

$tofile = true;
$fp = NULL;

$LIMIT = 100;

// open a file for saving the results
$fp = fopen($file, "w");
if (!$fp) {
	$tofile = false;
	print "Error opening file $file...\n";
}

//                    host:       user:               passwd:
$dbi = mysql_connect("localhost", $id, $pass);
if (!$dbi) {
    die("Not connected : " . mysql_error());
}

// YOUR int322_111[ab]nn ID GOES HERE...
$db_selected = mysql_select_db($dbase);
if (!$db_selected) {
    die ('Can\'t use $dbase : ' . mysql_error());
}

$sql = "SELECT * FROM $table";
$result = mysql_query($sql, $dbi);

print "Retrieving all rows from $table ...<br />";

// mob_db 38+ drops

// ERROR: following line does not produce any results
// while ($line = mysql_fetch_array($result, MYSQL_NUM) && $count < $LIMIT
// FIX: assignment to $line should be contained in brackets
while (($line = mysql_fetch_array($result, MYSQL_NUM)) && $count < $LIMIT) {
	$count++;
	
	$str = "";
	
	for ($i=0; $i<count($line); $i++) {
		if ($line[$i] != NULL)
			$str = $str . "$line[$i] ";
	}
	
	echo "DROP1ID[ $line[38] ] DROP1PER[  $line[39] ] <br />";
	
	$str .= "\n";
	
	if ($fp)
		fputs($fp, $str);
	else
		print "$count : $str <br />";
}

mysql_close($dbi);
fclose($fp);
	
?>