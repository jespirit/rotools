<?php
    // file:    mysql_FillTable.php
    // purpose: to demonstrate how to connect to a MySQL database
    //          using PHP's mysql.* (NOT mysqli*) series of functions
    //          and insert records into a specific table
    // author:  danny abesdris
    // date:    november 16, 2011

    // ZENIT MYSQL CONNECTION RULES:
    /*
    1. The MySQL host name:        db-mysql
    2. The MySQL account name:     same as your INT322 Zenit account ID
    3. The MySQL account password: same as your *ORIGINAL* INT322 account password.
    4. The MySQL database name:    same as your INT322 Zenit account ID

    To access the mysql> command line on ZENIT USE:

    mysql -h db-mysql -u int322_113[ab]nn -p

    and enter YOUR ORIGINAL int322_113 password, NOT the one you may have
    changed it to!
    */

    $id = "int322_113b09";

    $file = "lotto649.dbase.formatted.txt";      // file holding lotto 649 draw records
    $table = "lotto_649_winning_nums";           // YOU MUST USE THIS TABLE NAME!
    $fp = fopen($file, "r");
	
    if(!$fp) {
        print "Error opening file $file...\n";
        exit( );
    }
    else {
        $i=0;
        $dbase = $id; // YOUR int322_111[ab] ID HERE...

        //                    host:       user:               passwd:
		$dbi = mysql_connect("localhost", $id, "L0rdZ3rius");

        // YOUR int322_111[ab]nn ID GOES HERE...
        mysql_select_db($id);
		
		set_time_limit(0);

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
        $ti = microtime(true);
        $sql = "SELECT * FROM $table;";
        $result = mysql_query($sql, $dbi);
        print "<pre>\n";
        print "mysql>$sql\n";
        printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
        while($line = mysql_fetch_array($result, MYSQL_NUM)) {
            printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s |\n",
            $line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8]);
        }
        $to = microtime(true);
        $diff = $to - $ti;
        printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+\n");
        printf("%d rows in set (%.2lf sec)\n", $i, $diff);
        print "<br />";
        print "</pre>\n";
        mysql_close($dbi);
        fclose($fp);
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
        $i=0;
        $dbase = $id; // YOUR int322_111[ab] ID HERE...

        //                    host:       user:               passwd:
        $dbi = mysql_connect("localhost", $id, "L0rdZ3rius");

        // YOUR int322_111[ab]nn ID GOES HERE...
        mysql_select_db($id);

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
        $ti = microtime(true);
        $sql = "SELECT * FROM $table2;";
        $result = mysql_query($sql, $dbi);
        print "<pre>\n";
        print "mysql>$sql\n";
        printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+-----+\n");
        while($line = mysql_fetch_array($result, MYSQL_NUM)) {
            printf("| %5s | %s | %3s | %3s | %3s | %3s | %3s | %3s | %3s | %3s | \n",
            $line[0], $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7], $line[8], $line[9]);
        }
        $to = microtime(true);
        $diff = $to - $ti;
        printf("+-------+------------+-----+-----+-----+-----+-----+-----+-----+-----+\n");
        printf("%d rows in set (%.2lf sec)\n", $i, $diff);
        print "<br />";
        print "</pre>\n";
        mysql_close($dbi);
        fclose($fp);
    }
?>
