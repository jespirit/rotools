<?php
	$id = "ragnarok";
	$pass = "Fish";
	$dbase = "ragnarok"; // name of "schema" in MySQL not database instance

	//                    host:       user:    	passwd:
	$dbi = mysql_connect("localhost",  $id, 		$pass);

	// YOUR int322_111[ab]nn ID GOES HERE...
	mysql_select_db($dbase);
	
	$monsterid = $_GET['q'];
	$drops = array( );

	if(mysql_error()) {
		printf("connect failed: %s\n", mysql_errno( ));
		exit();
	}
	else {
		$table1 = "mob_db";
		$sql = "SELECT * FROM $table1 WHERE ID='$monsterid';";
		$result = mysql_query($sql, $dbi);
		$line = mysql_fetch_array($result, MYSQL_ASSOC);

		$drops = array(
			//    0                       1                         2          3           4
			//    drop id,                drop %,                   drop name, drop count, drop price
			array('id'=>$line['Drop1id'], 'per'=>$line['Drop1per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop2id'], 'per'=>$line['Drop2per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop3id'], 'per'=>$line['Drop3per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop4id'], 'per'=>$line['Drop4per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop5id'], 'per'=>$line['Drop5per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop6id'], 'per'=>$line['Drop6per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop7id'], 'per'=>$line['Drop7per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop8id'], 'per'=>$line['Drop8per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['Drop9id'], 'per'=>$line['Drop9per'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
			array('id'=>$line['DropCardid'], 'per'=>$line['DropCardper'], 'adj'=>0, 'sper'=>0, 'name'=>"", 'count'=>0, 'sell'=>0),
		);
		
		
		print "<table>";
		
		$MAX_ITEM_DROP = 10;
		
		print "<tr>";
		for ($x=0; $x<$MAX_ITEM_DROP; $x++) {
			printf("<th>Slot %d</th><th>%%</th>", $x+1);
		}
		print "</tr>";
		
		$x = 0;
		
		print "<tr>";
		foreach ($drops as $drop) {	// note the '&' used to modify the variable
			$query = sprintf("SELECT * FROM item_db WHERE id='%d'", $drop['id']);
			$result2 = mysql_query($query);
			$line2 = mysql_fetch_array($result2, MYSQL_ASSOC);
			
			printf("<td><input type='text' name='%s' maxlength='6' size='6' value='%s' /></td>", 
				sprintf("Drop%did", $x+1), $drop['id']);
			printf("<td><input type='text' name='%s' maxlength='5' size='5' value='%d' /></td>", 
				sprintf("Drop%dper", $x+1), $drop['per']);
				
			$x++;
		}
		print "</tr>";
		
		print "</table>";
	
		mysql_close($dbi);
	}
?>