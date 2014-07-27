<?php
/*
Ceres Control Panel

This is a control pannel program for Athena and Freya
Copyright (C) 2005 by Beowulf and Nightroad

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

To contact any of the authors about special permissions send
an e-mail to cerescp@gmail.com
*/

//include_once './language/language.php';
include_once 'classes.php';

if (isset($_SESSION[$CONFIG_name.'SERVER'])) {
	if (strcmp($_SESSION[$CONFIG_name.'SERVER'], $CONFIG_name)) {
		session_destroy();
		die();
	}
} else {
	$_SESSION[$CONFIG_name.'SERVER'] = $CONFIG_name;
}

$mysql = new QueryClass($CONFIG_rag_serv, $CONFIG_rag_user, $CONFIG_rag_pass, $CONFIG_rag_db, 
	$CONFIG_cp_serv, $CONFIG_cp_user, $CONFIG_cp_pass, $CONFIG_cp_db, 
	$CONFIG_log_db);

function readcastles() {
	global $lang;
	$handle = fopen("./db/castles.txt", "rt")
		or die(htmlformat($lang['TXT_ERROR']));
	while ($line = fgets($handle, 1024)) {
		if (($line[0] == '/' && $line[1] == '/') || $line[0] == "\0" || $line[0] == "\n" || $line[0] == "\r")
			continue;
		$job = sscanf($line, "%d %s");
		if (isset($job[0]) && isset($job[1])) {
			for($i = 1; isset($job[1][$i]); $i++)
				if ($job[1][$i] == '_') $job[1][$i] = ' ';
			$resp[$job[0]] = $job[1];
		}
	}	
	fclose($handle);
	return $resp;
}

function is_woe() {
	global $CONFIG_woe_time;
	$wdaynow = date('w');
	$wtimenow = date('Hi');
	$week_day = array (
		0  => 'sun',
		1  => 'mon',
		2  => 'tue',
		3  => 'wed',
		4  => 'thu',
		5  => 'fri',
		6  => 'sat'
	);

	$woe_times = explode(";", $CONFIG_woe_time);
	for ($i = 0; isset($woe_times[$i]); $i++) {
		$woe_times[$i] = str_replace("(", ",", $woe_times[$i]);
		$woe_times[$i] = str_replace(")", "", $woe_times[$i]);
		$woe_times[$i] = str_replace(" ", "", $woe_times[$i]);
		$woe_times[$i] = explode(",", $woe_times[$i]);
		if (!isset($woe_times[$i][2]))
			continue;

		if (strcasecmp($woe_times[$i][0], $week_day[$wdaynow]) == 0) {
			if (($wtimenow > $woe_times[$i][1]) && ($wtimenow < $woe_times[$i][2]))
				return TRUE;
		}
	}

	return FALSE;
}

function ret_woe_times() {
	global $CONFIG_woe_time, $lang;

	$week_day = array (
		'sun' => $lang['SUNDAY'],
		'mon' => $lang['MONDAY'],
		'tue' => $lang['TUESDAY'],
		'wed' => $lang['WEDNSDAY'],
		'thu' => $lang['THURSDAY'],
		'fri' => $lang['FRIDAY'],
		'sat' => $lang['SATURDAY']
	);

	$woe_times = explode(";", $CONFIG_woe_time);
	for ($i = 0; isset($woe_times[$i]); $i++) {
		$woe_times[$i] = str_replace('(', ',', $woe_times[$i]);
		$woe_times[$i] = str_replace(')', '', $woe_times[$i]);
		$woe_times[$i] = str_replace(' ', '', $woe_times[$i]);
		$woe_times[$i] = explode(',', $woe_times[$i]);
		if (!isset($woe_times[$i][2]))
			continue;

		$day = $week_day[$woe_times[$i][0]];
		$start = $woe_times[$i][1];
		$end = $woe_times[$i][2];
		echo '<tr><td align="right">'.$day.'</td><td>&nbsp;</td><td align="left">'.$start.' - '.$end.'</td></tr>';
	}
}

function readitems() {
	$resp[] = 'unknown';
	if (!($handle = fopen('./db/item_db.txt', 'rt')))
		return $resp;
	while ($line = fgets($handle, 1024)) {
		if (($line[0] == '/' && $line[1] == '/') || $line[0] == "\0" || $line[0] == "\n" || $line[0] == "\r")
			continue;
		$item = explode(',', $line, 4);
		if (isset($item[0]) && isset($item[2])) {
			$resp[$item[0]] = $item[2];
		}
	}	
	$resp[0] = ' ';
	fclose($handle);
	return $resp;
}

function readjobs() {
	global $lang;

	$resp[] = 'unknown';
	$handle = fopen('./db/jobs.txt', 'rt')
		or die(htmlformat($lang['TXT_ERROR']));
	while ($line = fgets($handle, 1024)) {
		if (($line[0] == '/' && $line[1] == '/') || $line[0] == "\0" || $line[0] == "\n" || $line[0] == "\r")
			continue;
		$job = sscanf($line, '%s %d');
		if (isset($job[0]) && isset($job[1])) {
			for($i = 1; isset($job[0][$i]); $i++)
				if ($job[0][$i] == '_') $job[0][$i] = ' ';
			$resp[$job[1]] = $job[0];
		}
	}	
	fclose($handle);
	return $resp;
}

function htmlformat($string) {
	$resp = '';
	for ($i = 0; isset($string[$i]) && ord($string[$i]) > 0; $i++)
		$resp .= '&#'.ord($string[$i]).';';
	return $resp;
}

function moneyformat($string) {
	$string = trim($string);
	$return = '';
	$len = strlen($string) - 1;

	for ($i = 0; $i < strlen($string); $i++) {
		if ($i > 0 && $i % 3 == 0)
			$return = ','.$return;
		$return = $string[$len - $i].$return;
	}

	return $return;
}

function inject($string) {
	$permitido = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890.@$&-_/§*°ºª'; //dicionario de palavras permitidas
	for ($i=0; $i<strlen($string); $i++) {
		if (strpos($permitido, substr($string, $i, 1)) === FALSE) return TRUE;
	}
	return FALSE;
}

function notnumber($string) {
	$permitido = '1234567890'; //numeros
	for ($i=0; $i<strlen($string); $i++) {
		if (strpos($permitido, substr($string, $i, 1)) === FALSE) return TRUE;
	}
	return FALSE;
}

function thepass($string) {
	global $lang;

	$string = trim($string);

	$numero = 0;
	for ($i = 0; isset($string[$i]); $i++) {
		if (!notnumber($string[$i]))
			$numero++;
	}

	if ($numero < 2)
		return TRUE;
	if ($numero == strlen($string))
		return TRUE;
	if ((strlen($string) - $numero) < 2)
		return TRUE;

	$handle = fopen('./db/passdict.txt', 'rt')
		or die(htmlformat($lang['TXT_ERROR']));
	while ($line = fgets($handle, 1024)) {
		if (($line[0] == '/' && $line[1] == '/') || $line[0] == "\0" || $line[0] == "\n" || $line[0] == "\r")
			continue;
		if (strcmp(trim($string), trim($line)) === 0) {
			fclose($handle);
			return TRUE;
		}
	}	
	fclose($handle);

	return FALSE;
}

function truedate($day, $month, $year) {
	$diames = array (
		1  => 31,
		2  => 28,
		3  => 31,
		4  => 30,
		5  => 31,
		6  => 30,
		7  => 31,
		8  => 31,
		9  => 30,
		10 => 31,
		11 => 30,
		12 => 31,
	);
	if (($year % 4) === 0)
		$diames[2] = 29;

	if ($day > $diames[$month])
		return 0;

	return mktime(0, 0, 0, $month, $day, $year);
}

function is_online() {
	global $CONFIG_name, $lang;

	if (empty($_SESSION[$CONFIG_name.'account_id'])) 
		redir('motd.php', 'main_div', htmlformat($lang['NEED_TO_LOGIN_F']));

	$log_account = $_SESSION[$CONFIG_name.'account_id'];

	$stmt = prepare_query(IS_ONLINE, 0, 'i', $log_account);
	$result = execute_query($stmt, 'is_online');

	$row = $result->fetch_row();

	return $row[0];
}

function online_count() {
	$stmt = prepare_query(GET_ONLINE);
	$result = execute_query($stmt, 'online_count', 0);

	$row = $result->fetch_row();

	return $row[0];
}

function check_ban() {
	$stmt = prepare_query(CHECK_BAN, 0, 's', $_SERVER['REMOTE_ADDR']);
	$result = execute_query($stmt, 'check_ban', 1);

	if ($result !== FALSE && $result->num_rows) {
		while ($line = $result->fetch_row()) {
			if (($line[2] == 5 || $line[1] > 0) && (time() - $line[0]) < (86400 * 2)) //2 dias
				return 1;
		}
	}

	return 0;
}

function forger($hint, $lint) {
	if ($hint<0)
		$hint = 0xFFFF+1+$hint;
	$result = ($hint)|($lint << 0x10);
	return $result;
}
function petegg($hint) {
	return forger($hint, 0);
}


/**
 * Returns an array of references in $arr.
 * Necessary for when calling some methods, such as mysqli_bind_param which
 * requires references to be passed.
 *
 * @arr array The array of elements to be passed
 */
function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

function prepare_query($query, $database = 0, $types = '' /*, ...*/) {
	global $mysql;
	
	if (func_num_args() == 1)  // only $query passed
		$arr = [$query, 0];
	else
		$arr = func_get_args();

	// calls $mysql->Prepare(...[])
	$stmt = call_user_func_array(array($mysql, "Prepare"), $arr);
	return $stmt;
}


function execute_query($stmt, $source = 'none.php', $save_report = 1) {
	global $mysql;

	if ($save_report) {
		if ($query = $mysql->Interpolate()) {  // get SQL query as string for logging
			add_query_entry($source, $query);
		}
	}

	// TODO: Return TRUE on non-SELECT queries.
	if ($result = $mysql->Query($stmt)) {
		return $result;
	}
	return FALSE;
}

function add_query_entry($source, $log_query) {
	global $CONFIG_name, $CONFIG_cp_db, $CONFIG_rag_serv, $CONFIG_rag_user, $CONFIG_rag_pass;
	if (!empty($_SESSION[$CONFIG_name.'account_id'])) 
		$log_account = $_SESSION[$CONFIG_name.'account_id'];
	else
		$log_account = 0;
	$log_ip = $_SERVER['REMOTE_ADDR'];
	$log_query = addslashes($log_query);
	$stmt = prepare_query(ADD_QUERY_ENTRY, 1, 'isss', $log_account, $log_ip, $source, $log_query);

	execute_query($stmt, 'none.php', 0);
}

// o retorno eh em compara?o binaria
// ($var & 1) - se TRUE login online
// ($var & 2) - se TRUE char  online
// ($var & 4) - se TRUE map   online
function server_status() {
	global $CONFIG_accip,$CONFIG_accport,$CONFIG_charip,$CONFIG_charport,$CONFIG_mapip,$CONFIG_mapport;

	$stmt = prepare_query(CHECK_STATUS, 1);
	$result = execute_query($stmt, 'server_status', 0);
	if (!($line = $result->fetch_row())) {
		$stmt = prepare_query(INSERT_STATUS, 1);
		$result = execute_query($stmt, 'server_status', 0);
		$line[0] = 0;
	}
	$retorno = 0;
	if ($line[2] > 300 || $line[1] < 7) {
		$acc = @fsockopen ($CONFIG_accip, $CONFIG_accport, $errno, $errstr, 1);
		$char = @fsockopen ($CONFIG_charip, $CONFIG_charport, $errno, $errstr, 1);
		$map = @fsockopen ($CONFIG_mapip, $CONFIG_mapport, $errno, $errstr, 1);
		if ($acc > 1) $retorno += 1;
		if ($char > 1) $retorno += 2;
		if ($map > 1) $retorno += 4;
		$stmt = prepare_query(UPDATE_STATUS, 1, 'i', $retorno);
		$result = execute_query($stmt, "server_status", 0);
	}
	else {
		$retorno = $line[1];
	}
	return $retorno;
}

function redir($page, $div, $msg) {
	caption('Status');
	echo '
	<table class="maintable">
		<tr>
			<td>
				<span class="link" onClick="return LINK_ajax(\''.$page.'\',\''.$div.'\')"><b>'.$msg.'</span>
			</td>
		</tr>
	</table>';
	fim();
}

function alert($alertmsg) {
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	$alertmsg = strtr ($alertmsg, $trans_tbl);

	echo 'ALERT|'.utf8_encode($alertmsg).'|ENDALERT';
	fim();
}

function fim() {
	global $mysql;
	$mysql->finish();
	exit(0);
}

function caption($s) {
	print '<h3 class="title">'.$s.'</h3>';
}

function read_maildef($file) {
	global $lang;
	$handle = fopen('./language/mail/'.$file.'.txt', 'rt')
		or die(htmlformat($lang['TXT_ERROR']));
	$maildef='';
	while ($line = fgets($handle, 1024)) {
		if ($line[0] == '/' && $line[1] == '/')
			continue;
		$maildef .= $line;
	}
	fclose($handle);
	return $maildef;
}

function erro_de_login($i = 0) {
	session_destroy();
	setcookie('login_pass', '', time() - 3600);
	setcookie('userid', '', time() - 3600);
	session_start();
	echo '<script type="text/javascript">
		LINK_ajax(\'login.php\', \'login_div\');';
	if (!$i)
		echo 'LINK_ajax(\'motd.php\',\'main_div\');';
	echo '</script>';
}

function print_items($result) {
	global $items;
	echo '
		<table class="maintable" style="width: 750px">
		<tr>
			<th style="width: 25px;"></th>
			<th align="center">Item</th>
			<th align="center" style="width: 50px;">Amount</th>
			<th align="center" style="width: 50px;">Refine</th>
			<th align="center" style="width: 100px;">Card0</th>
			<th align="center" style="width: 100px;">Card1</th>
			<th align="center" style="width: 100px;">Card2</th>
			<th align="center" style="width: 100px;">Card3</th>
		</tr>
	';
	while ($item = $result->fetch_row()) {
		echo '
			<tr>
				<td align="center">'.($item[7]?'Eq.':'').'</td>
				<td align="center">
		';
		if (isset($items[$item[0]]))
			echo $items[$item[0]];
		else
			echo $item[0];
		echo '
				</td>
				<td align="center">'.$item[1].'</td>
				<td align="center">'.$item[6].'</td>
		';

		if ($item[2] == 254) {
			$stmt = prepare_query(GET_CHARNAME, 0, 'si', forger($item[4], $item[5]));
			$result2 = execute_query($stmt, 'admincharinfo.php');
			$result2->fetch_row();

			if ($result2->num_rows)
				$chname = htmlformat($result2->row(0));
			else
				$chname = '<i class="disabled">Unknown</i>';

			echo '
				<td align="center">signed</td>
				<td align="center">'.$chname.' ('.forger($item[4], $item[5]).')</td>
				<td align="center"></td>
				<td align="center"></td>';
		}
		else if ($item[2] == 255) {
			$stmt = prepare_query(GET_CHARNAME, 0, 'si', forger($item[4], $item[5]));
			$result2 = execute_query($stmt, 'admincharinfo.php');
			$result2->fetch_row();

			if ($result2->num_rows)
				$chname = htmlformat($result2->row(0));
			else
				$chname = '<i class="disabled">Unknown</i>';

			echo '
				<td align="center">forged</td>
				<td align="center">'.$chname.' ('.forger($item[4], $item[5]).')</td>
				<td align="center"></td>
				<td align="center"></td>';
		}
		else if ($item[2] == -256) {
			$stmt = prepare_query(GET_PETNAME, 0, 'i', petegg($item[3]));
			$result2 = execute_query($stmt, 'admincharinfo.php');
			$row = $result2->fetch_row();
			
			if ($result2->num_rows)
				$petname = htmlformat($row[0]);
			else
				$petname = '<i class="disabled">Unknown</i>';
			echo '
				<td align="center">Pet</td>
				<td align="center">'.$petname.' ('.petegg($item[3]).')</td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>';
		}
		else {
			echo '
			<td align="center">'.((isset($items[$item[2]]))?$items[$item[2]]:$item[2]).'</td>
			<td align="center">'.((isset($items[$item[3]]))?$items[$item[3]]:$item[3]).'</td>
			<td align="center">'.((isset($items[$item[4]]))?$items[$item[4]]:$item[4]).'</td>
			<td align="center">'.((isset($items[$item[5]]))?$items[$item[5]]:$item[5]).'</td>';
		}
		echo '
		</tr>';
	}
	echo '</table>';
}

?>
