<?php

# Assignment Number: 3
# Subject Code and Section: INT322B
# Student Name: Jeffrey Espiritu
# Student Number: 058 316 092
# Instructor Name: Danny Abesdris
# Due Date: Monday December 5th, 2011 by 11:59:59 p.m.
# Date Submitted: Friday, December 16th, 2011
#
# Student Oath:
# "This assignment represents my own work in accordance
#  with Seneca Academic Policy"
#
# Signature: Jeffrey Espiritu, 058 316 092, jespiritu@learn.senecac.on.ca
#

?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>INT322 Assignment 3</title>
</head>
<body>

<form name='assign3' method='post' action='assign3.php'>
	Choose a lottery:
	<select name='lottery'>
		<option value='lotto649' selected='selected'>Lotto 6/49</option>
		<option value='lottomax'>Lotto MAX</option>
	</select>
	
	<table>
		<tr>
			<td>Find winning lottery numbers by date</td>
			<td><input type='radio' name='func' value='1' checked='checked'/></td>
			<td>
				Year:
				<select name='year'>
				<?php
					foreach (range(1982, 2011) as $year) {
						if ($year == 2011)
							printf("<option value='%d' selected='selected'>%d</option>", $year, $year);
						else
							printf("<option value='%d'>%d</option>", $year, $year);
					}
				?>
				</select>
				
				Month:
				<select name='month'>
				<option value='' selected='selected'></option>
				<?php
					foreach (range(1,12) as $month) {
						printf("<option value='%d'>%d</option>", $month, $month);
					}
				?>
				</select>
				
				Day:
				<select name='day'>
				<option value='' selected='selected'></option>
				<?php
					foreach (range(1,31) as $day) {
						printf("<option value='%d'>%d</option>", $day, $day);
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Lottery number sum that fall within a range</td>
			<td><input type='radio' name='func' value='2'/></td>
			<td>
				Min: <input type='text' name='min' />
				Max: <input type='text' name='max' />
			</td>
		</tr>
	
		<tr>
			<td>Find the mean value of a certain ball</td>
			<td><input type='radio' name='func' value='3'/></td>
			<td>
				Ball Number:
				<select name='ballno'>
				<?php
					foreach (range(1,7) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
				<option value='8'>Bonus</option>
				</select>
			</td>
		</tr>
        <tr>
			<td>Check if your lottery numbers has won</td>
			<td><input type='radio' name='func' value='4'/></td>
			<td>
				Pick 1:
				<select name='pick1'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 2:
				<select name='pick2'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 3:
				<select name='pick3'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 4:
				<select name='pick4'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 5:
				<select name='pick5'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 6:
				<select name='pick6'>
				<?php
					foreach (range(1,49) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
                </select>
                
                Pick 7:
				<select name='pick7'>
				<?php
					foreach (range(1,47) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
				</select>
			</td>
		</tr>
	</table>
	
	<input type="submit" value="Submit" name="submit" />
</form>

<?php

// check if submit button was clicked
if (!isset($_POST["submit"]))
	exit();

$id = "int322_113b09";
$pass = "L0rdZ3rius";
$i = 0;
$dbase = $id; // YOUR int322_111[ab] ID HERE...

//                    host:       user:    	passwd:
$dbi = mysql_connect("localhost",  $id, 		$pass);

// YOUR int322_111[ab]nn ID GOES HERE...
mysql_select_db($id);

if(mysql_error( )) {
    printf("connect failed: %s\n", mysql_errno( ));
    exit( );
}
else {
    
	//create_tables();

	// can include year, year+month, or year+month+day
	$date = array($_POST["year"], $_POST["month"], $_POST["day"]);
	$min = $_POST["min"];
	$max = $_POST["max"];
	$option = $_POST["func"];
	$ballno = $_POST["ballno"];

	switch ($option)
	{
		case 1:
			get_by_date($date[0], $date[1], $date[2]);
			break;
		case 2:
			get_by_range($min, $max);
			break;
		case 3:
			get_by_mean($ballno);
			break;
		case 4:
            get_by_pick();
			break;
	}
	
	
	mysql_close($dbi);
}

function get_by_date($year, $month, $day)
{
	// global variables cannot be accessed from within a function unless
	// explicitly declared as global
	global $dbi;	// $dbi now refers to the global variable
	$lottery = $_POST["lottery"];
	$table;
	
	$bydate = 0;	// 0=year, 1=year+month, 2=year+month+day
	
	if (strcmp($lottery, "lotto649") == 0)
		$table = "lotto_649_winning_nums";
	else
		$table = "lotto_MAX_winning_nums";
		
	$sql = "SELECT * FROM $table;";
	$result = mysql_query($sql, $dbi);
	
	if (!empty($day) && empty($month)) {
		printf("Year and day is not allowed.\n");
		exit();
	}
	
	if (!empty($month)) {
		if (empty($day))
			$bydate = 1;	// year+month
		else
			$bydate = 2;	// year+month+day
	}
	
	print "<pre>\n";
	print "mysql>$sql\n";
	printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
	
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$flds = preg_split("/-/", $line[1]);	// split up the date by dashes
												// format YYYY-MM-DD, 1982-6-19
         
        /*
        print "$flds[0] $flds[1] $flds[2] -> $year $month $day <br />";    
        if ($flds[1] == $month)
            print "OK\n";
        else
            print "NOT OK\n";
        */
										
		// Look for several lottery winning entries for a certain date(s).
		if (($bydate == 0 && $flds[0] == $year) ||
		    ($bydate == 1 && $flds[0] == $year && $flds[1] == $month) ||
			($bydate == 2 && $flds[0] == $year && $flds[1] == $month && $flds[2] == $day)) {
			printf("Match found: ");
			printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
				$line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8]);
		}
	}
    
    print "</pre>\n";
}

function get_by_range($min, $max) 
{
	global $dbi;
	$table;
    $lottery = $_POST["lottery"];
	
	if (strcmp($lottery, "lotto649") == 0)
		$table = "lotto_649_winning_nums";
	else
		$table = "lotto_MAX_winning_nums";
	
	$sql = "SELECT * FROM $table;";
	$result = mysql_query($sql, $dbi);
	
	$range = array(21, 279);
	$numeric = "^[0-9]+\$";
	$valid = false;
	$sum = 0;
	
	$lottery = $_POST["lottery"];
	
	if (strcmp($lottery, "lottomax") == 0)
		$range = array(28, 308);
	
	if (empty($min) && !is_numeric($min)) {
		print "minimum not set";
	}
	elseif (empty($max) && !is_numeric($max)) {
		print "maximum not set";
	}
	elseif (!preg_match("/$numeric/", $min)) {
		print "minimum must only be a positive integer";
	}
	elseif (!preg_match("/$numeric/", $max)) {
		print "maximum must only be a positive integer";
	}
	elseif ($min < $range[0]) {
		print "minimum is less than possible lotto minimum $range[0]";
	}
	elseif ($min > $max) {
		print "minimum is greater than max";
	}
	elseif ($max > $range[1]) {
		print "maximum is greater than possible lotto maximum $range[1]";
	}
	else {
		$valid = true;
	}
	
	if (!$valid)
		exit();
        
    print "<pre>\n";
	print "mysql>$sql\n";
	printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
		
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		if (strcmp($lottery, "lotto649") == 0) {
			// get the sum of all the numbers of the lottery
			$sum = $line[2] + $line[3] + $line[4] + $line[5] + $line[6] + $line[7];
			
			if ($sum >= $min && $sum <= $max) {
				printf("Match found: $sum");
				printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
					$line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8]);
			}
		}
		else {	// lotto max
			// get the sum of all the numbers of the lottery
			$sum = $line[2] + $line[3] + $line[4] + $line[5] + $line[6] + $line[7] + $line[8];
			
			if ($sum >= $min && $sum <= $max) {
				printf("Match found: $sum");
				printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
					$line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9]);
			}
		}
	}
    
    print "</pre>\n";
}

function get_by_mean($ballno)
{
    global $dbi;
	$lottery = $_POST["lottery"];
	$total = 0;
	$count = 0;
	$mean = 0.0;
	$balldesc = $ballno;
    
    $table;
	
	if (strcmp($lottery, "lotto649") == 0)
		$table = "lotto_649_winning_nums";
	else
		$table = "lotto_MAX_winning_nums";
	
	$sql = "SELECT * FROM $table;";
	$result = mysql_query($sql, $dbi);
	
	// indices for balln is
	// 2,3,4,5,6,7,8,9 of $line
	
	if ($ballno == "8")
		$balldesc = "bonus";
	
	if (strcmp($lottery, "lotto649") == 0) {
		if ($ballno == "7") {
			printf("Wrong, there is no seventh ball for Lotto 6/49\n");
			exit();
		}
		else if ($ballno == "8") {	// set the bonus ball index within range
			$ballno = 7;
		}
	}
    
    print "<pre>\n";
	print "mysql>$sql\n";
	printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
		
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		$total += $line[1+$ballno];
		$count++;
	}
	
    print "$total $count\n";
    
	$mean = sprintf("%.4f", $total/$count);	// calculate mean, format to 4 decimal places
	
	if (strcmp($balldesc, "bonus") == 0)
		print "Mean value for the bonus ball is $mean\n";
	else
		print "Mean value for ball $ballno is $mean\n";
        
    print "</pre>\n";
}

function get_by_pick()
{
    global $dbi;
	$table;
    
    $lottery = $_POST["lottery"];
	
	if (strcmp($lottery, "lotto649") == 0)
		$table = "lotto_649_winning_nums";
	else
		$table = "lotto_MAX_winning_nums";
	
	$sql = "SELECT * FROM $table;";
	$result = mysql_query($sql, $dbi);
    
    $pick = array($_POST["pick1"], $_POST["pick2"], $_POST["pick3"], 
                  $_POST["pick4"], $_POST["pick5"], $_POST["pick6"], 
                  $_POST["pick7"]);
                     
    $valid = true;
    
    if (strcmp($lottery, "lotto649") == 0) {
        array_pop($pick);
        $valid = validate_sequence($pick);
    } else {
        $valid = validate_sequence($pick);
    }
        
    if (!$valid) {
        print "Wrong. You cannot repeat the same number for the lottery. <br />";
        exit();
    }
    
    sort($pick);
    
    print "<pre>\n";
	print "mysql>$sql\n";
	printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
		
	while ($line = mysql_fetch_array($result, MYSQL_NUM)) {
		if (strcmp($lottery, "lotto649") == 0) {
            $nums = array($line[2], $line[3], $line[4], $line[5], $line[6], $line[7]);
            sort($nums);
            
            /*
            foreach ($nums as $v) {
                print "$v ";
            }
            
            print "\n";
            */
            
			if ($pick[0] == $nums[0] && $pick[1] == $nums[1] && $pick[2] == $nums[2] &&
                $pick[3] == $nums[3] && $pick[4] == $nums[4] && $pick[5] == $nums[5]) {
				printf("Match found: ");
				printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
					$line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8]);
			}
		}
		else {	// lotto max
            $nums = array($line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8]);
            sort($nums);
            
			if ($pick[0] == $nums[0] && $pick[1] == $nums[1] && $pick[2] == $nums[2] &&
                $pick[3] == $nums[3] && $pick[4] == $nums[4] && $pick[5] == $nums[5] &&
                $pick[6] == $nums[6]) {
				printf("Match found: ");
				printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
					$line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9]);
			}
		}
	}
    
    print "</pre>\n";
}       

function validate_sequence($seq)
{
    $valid = true;
    
    foreach ($seq as $v) {
        print "$v ";
    }
    
    print "<br />";
    
    for ($i=0; $i<count($seq)-1 && $valid; $i++) {
        for ($j=$i+1; $j<count($seq) && $valid; $j++) {
            print "$seq[$i] == $seq[$j] <br />";
            if ($seq[$i] == $seq[$j]) {
                $valid = false;
            }
        }
    }
    
    return $valid;
}

function create_tables()
{
	// There is a default time limit of 30 seconds
	set_time_limit(0);	// Execute the script without a time limit
	
	printf("Host information: %s<br />\n", mysql_get_host_info($dbi));
    $tblNm = "lotto_649_winning_nums"; // YOU MUST USE THIS TABLE NAME!

    $sql = "CREATE TABLE $tblNm (
      id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
      ddate DATE NOT NULL UNIQUE,
      ball1 TINYINT UNSIGNED NOT NULL,
      ball2 TINYINT UNSIGNED NOT NULL,
      ball3 TINYINT UNSIGNED NOT NULL,
      ball4 TINYINT UNSIGNED NOT NULL,
      ball5 TINYINT UNSIGNED NOT NULL,
      ball6 TINYINT UNSIGNED NOT NULL,
      bonus TINYINT UNSIGNED NOT NULL)";
    $result = mysql_query($sql, $dbi);

    if(mysql_query("DESCRIBE $tblNm", $dbi) != FALSE) { // for DESCRIBE, mysql_query will return FALSE
                           // if table was not created
        echo "Table '$tblNm' successfully created...<br />\n";
        $sql = "DESCRIBE $tblNm";
        $result = mysql_query($sql, $dbi);
        $rows = mysql_num_rows($result);
        print "<pre>rows in query is: $rows<br />\n";

        while($line = mysql_fetch_array($result, MYSQL_NUM)) {
            foreach($line as $k=>$v) {
               printf("%s ", $v);
            }
            print "<br />";
        }
        print "</pre>\n";
        mysql_free_result($result); // free memory from previous query
    }
    else {
        printf("Could not create Table: %s Error: %s\n", $tblNm, mysql_error( ));
    }
    
    // Create the second table for Lotto Max
    $tblNm2 = "lotto_MAX_winning_nums"; // YOU MUST USE THIS TABLE NAME!

    $sql = "CREATE TABLE $tblNm2 (
      id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
      ddate DATE NOT NULL UNIQUE,
      ball1 TINYINT UNSIGNED NOT NULL,
      ball2 TINYINT UNSIGNED NOT NULL,
      ball3 TINYINT UNSIGNED NOT NULL,
      ball4 TINYINT UNSIGNED NOT NULL,
      ball5 TINYINT UNSIGNED NOT NULL,
      ball6 TINYINT UNSIGNED NOT NULL,
      ball7 TINYINT UNSIGNED NOT NULL,
      bonus TINYINT UNSIGNED NOT NULL)";
    $result = mysql_query($sql, $dbi);

    if(mysql_query("DESCRIBE $tblNm", $dbi) != FALSE) { // for DESCRIBE, mysql_query will return FALSE
                           // if table was not created
        echo "Table '$tblNm2' successfully created...<br />\n";
        $sql = "DESCRIBE $tblNm2";
        $result = mysql_query($sql, $dbi);
        $rows = mysql_num_rows($result);
        print "<pre>rows in query is: $rows<br />\n";

        while($line = mysql_fetch_array($result, MYSQL_NUM)) {
            foreach($line as $k=>$v) {
               printf("%s ", $v);
            }
            print "<br />";
        }
        print "</pre>\n";
        mysql_free_result($result); // free memory from previous query
    }
    else {
        printf("Could not create Table: %s Error: %s\n", $tblNm, mysql_error( ));
    }
    
    $file = "lotto649.dbase.formatted.txt";      // file holding lotto 649 draw records
    $table = "lotto_649_winning_nums";           // YOU MUST USE THIS TABLE NAME!
    $fp = fopen($file, "r");
    if(!$fp) {
        print "Error opening file $file...\n";
        exit( );
    }
    else {
        while(!feof($fp)) {
            $line = fgets($fp, 121);
            if($line != "") {
                $flds = preg_split("/\s+/", $line);  # format to change: 6/19/1982 -> 1982-6-19
                                 # split on multiple spaces
                $dflds = split('/', $flds[0]);
                $sqldate = sprintf("%s-%02d-%02d", $dflds[2], $dflds[0], $dflds[1]);  // 2 digit fields required

                $sql = "INSERT INTO $table" .
                   "(ddate, ball1, ball2, ball3, ball4, ball5, ball6, bonus)" .
                   "VALUES ('$sqldate', $flds[1], $flds[2], $flds[3], $flds[4], $flds[5], $flds[6], $flds[7]);";
                   // single quotes around date field required
                $result = mysql_query($sql, $dbi);
                $i++;
            }
        }
        
        // Populate the second table for Lotto Max

        $months = array("January", "February", "March", "April", "May", "June",
                   "July", "August", "September", "October", "November", "December");
                   
        $file2 = "lottomax.dbase.formatted.txt";      // file holding lotto 649 draw records
        $table2 = "lotto_MAX_winning_nums";           // YOU MUST USE THIS TABLE NAME!
        $fp = fopen($file2, "r");
        if(!$fp) {
            print "Error opening file $file...\n";
            exit( );
        }
        else {
            while(!feof($fp)) {
                $line = fgets($fp, 121);
                if($line != "") {
                    $flds = preg_split("/\s+/", $line);  # format change to November 4 2011 -> 2011/11/4
                    $month = 0;

                    // Get index for month (1-12).
                    foreach ($months as $k=>$v) {
                        if ($flds[0] == $v) {
                            $month = $k+1;
                            break;
                        }
                    }
                                     # split on multiple spaces
                    //$dflds = split('/', $flds[0]);
                    $sqldate = sprintf("%s-%02d-%02d", $flds[2], $month, $flds[1]);  // 2 digit fields required

                    $sql = "INSERT INTO $table2" .
                       "(ddate, ball1, ball2, ball3, ball4, ball5, ball6, ball7, bonus)" .
                       "VALUES ('$sqldate', $flds[3], $flds[4], $flds[5], $flds[6], $flds[7], $flds[8], $flds[9], $flds[10]);";
                       // single quotes around date field required
                    $result = mysql_query($sql, $dbi);
                    $i++;
                }
            }
        }
    }
}
?>

</body>
</html>