<?php
    // file:    mysql_CreateTable.php
    // purpose: to demonstrate how to connect to a MySQL database
    //          using PHP's mysql.* (NOT mysqli.*) series of functions
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

    NOTES:
    To INSERT a record into the table use:

    INSERT INTO lotto_649_winning_nums
    (ddate, ball1, ball2, ball3, ball4, ball5, ball6, bonus)
    VALUES ('1982-6-12', 03, 11, 12, 14, 41, 43, 13);
    */

    $id = "int322_113b09";
    $dbase = $id; // YOUR int322_111[ab] ID HERE...

    //                    host:       user:               passwd:
    $dbi = mysql_connect("localhost", $id, "L0rdZ3rius");

    // YOUR int322_113[ab]nn ID GOES HERE...
    mysql_select_db($id);

    if(mysql_error( )) {
        printf("connect failed: %s\n", mysql_errno( ));
        exit( );
    }
    else {
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
            
        mysql_close($dbi);
    }
?>
