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

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

// Category name, required gm level
$mainmenu[0] = array($lang['MENU_HOME'],		-1);
$mainmenu[1] = array($lang['MENU_MYACCOUNT'],	 0);
$mainmenu[2] = array($lang['MENU_MYCHARS'],		 0);
$mainmenu[3] = array($lang['MENU_RANKING'],		-1);
$mainmenu[4] = array($lang['MENU_INFORMATION'],	-1);
$mainmenu[5] = array($lang['MENU_PROBLEMS'],	 0);
$mainmenu[6] = array('Logs',					$CONFIG['cp_admin']);
$mainmenu[7] = array('Administration',			$CONFIG['cp_admin']);

// Page name, page link, category id
$submenu[] = array($lang['MENU_MESSAGE'],		'motd.php',				0);
$submenu[] = array($lang['MENU_CHANGEPASS'],	'password.php',			1);
$submenu[] = array($lang['MENU_CHANGEMAIL'],	'changemail.php',		1);
$submenu[] = array($lang['MENU_TRANFMONEY'],	'money.php',			$CONFIG_money_transfer?2:-1);
$submenu[] = array($lang['MENU_CHANGESLOT'],	'slot.php',				$CONFIG_set_slot?2:-1);
$submenu[] = array($lang['MENU_MARRIAGE'],		'marriage.php',			2);
$submenu[] = array($lang['MENU_PLAYERLADDER'],	'ladder.php',			3);
$submenu[] = array($lang['MENU_GUILDLADDER'],	'guild.php',			3);
$submenu[] = array($lang['MENU_ZENYLADDER'],	'top100zeny.php',		3);
$submenu[] = array($lang['MENU_WHOSONLINE'],	'whoisonline.php',		4);
$submenu[] = array('Features',					'feature.php',			4);
$submenu[] = array('Customizations',			'custom.php',			4);
//$submenu[] = array($lang['MENU_ABOUT'],			'about.php',			4);
$submenu[] = array($lang['MENU_RESETPOS'],		'position.php',			$CONFIG_reset_enable?5:-1);
$submenu[] = array($lang['MENU_RESETLOOK'],		'resetlook.php',		$CONFIG_reset_look?5:-1);
//$submenu[] = array($lang['MENU_LINKS'],			'links.php',			0);
$submenu[] = array('Downloads',					'downloads.php',		0);
$submenu[] = array('Atcommand Logs',			'logatcommand.php',		6);
$submenu[] = array('Cash Logs',					'logcash.php',			6);
$submenu[] = array('Char Logs',					'logchar.php',			6);
$submenu[] = array('Dead Branch Logs',			'logbranch.php',		6);
$submenu[] = array('Item Logs',					'logitems.php',			6);
$submenu[] = array('Login Logs',				'loglogin.php',			6);
$submenu[] = array('MVP Logs',					'logmvp.php',			6);
$submenu[] = array('NPC Logs',					'lognpc.php',			6);
$submenu[] = array('Zeny Logs',					'logzeny.php',			6);
$submenu[] = array('Accounts',					'adminaccounts.php',	7);
$submenu[] = array('Chars',						'adminchars.php',		7);
//$submenu[] = array('Bans/Blocks',				'',						7);
$submenu[] = array('Applications',				'admin-application.php',	7);


$pos = 0;
$menu = 'var mainmenu = new Array(';
$sub  = 'var submenu = new Array("", "", -1';

foreach ($mainmenu as $i => $mainmenudata) {
	if ($mainmenudata[1] < 0 || (isset($_SESSION[$CONFIG_name.'level']) && $_SESSION[$CONFIG_name.'level'] >= $mainmenudata[1])) {
		if ($pos > 0)
			$menu = $menu.', ';
		$menu = $menu."\"".$mainmenudata[0].'"';
		foreach ($submenu as $j => $submenudata) {
			if ($submenudata[2] == $i) {
				$sub = $sub.', "'.$submenudata[0].'"'.', "'.$submenudata[1].'", '.$pos;
			}
		}
		$pos++;
	}
}

$menu = $menu.');';
$sub  = $sub.');';

echo $menu."\n";
echo $sub."\n";

?>
function main_menu() {
	var the_menu = " | ";

	for (i = 0; i < mainmenu.length; i++)
		the_menu = the_menu + "<span class=\"link\" onClick=\"return sub_menu(" + i + ");\">" + mainmenu[i] + "</span> | ";

	document.getElementById('main_menu').innerHTML = the_menu;
	document.getElementById('sub_menu').innerHTML = " ";

	return false;
}

function sub_menu(index) {
	var the_menu = " | ";
	
	for (i = 0; i < submenu.length; i = i + 3) {
		if (submenu[i + 2] == index)
		the_menu = the_menu + "<span class=\"link\" onClick=\"return LINK_ajax('" + submenu[i + 1] + "','main_div');\">" + submenu[i] + "</span> | ";
	}

	document.getElementById('sub_menu').innerHTML = the_menu;

	return false;
}

main_menu();
<?php
//fim();
?>
