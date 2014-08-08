<?php

$file = "food.json";
#$fp = fopen($file, "r");
$food_json = file_get_contents("$file");

if (!$food_json) {
	print "Error: reading file contents of $file ...\n";
	exit();
}

# must remove CRLF when parsing the file as JSON
$food_json = preg_replace('/\r\n|\n/', '', $food_json);

echo $food_json;
?>