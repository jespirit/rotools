
<?php
$info = array(1, 2, 3);
$sample = array(
	array("abc", 123),
	array('def', 456),
	);
	
$sample[0][2] = "more";
$sample[1][2] = "even more";
$sample[1][3] = "cool";
	
foreach ($info as $v) {
	print $v ." ";
}
print "<br />";

foreach (array_keys($sample) as $key) {
	foreach ($sample[$key] as $val) {
		print $val ." ";
	}
	print "<br />";
}

?>