<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

// Category name, required gm level
$mainmenu[0] = array("HOME",					-1);
$mainmenu[1] = array("FOOD",	 				 0);
$mainmenu[2] = array("ALCHEMIST",				 0);
$mainmenu[3] = array("EQUIPMENT",				 0);
$mainmenu[4] = array($lang['MENU_INFORMATION'],	-1);
$mainmenu[5] = array($lang['MENU_PROBLEMS'],	 0);
$mainmenu[6] = array('Logs',					$CONFIG['cp_admin']);
$mainmenu[7] = array('Administration',			$CONFIG['cp_admin']);

// Page name, page link, category id
$submenu[] = array("Food",		'food.php',				1);
$submenu[] = array("Alchemist",	'alchemist.php',		2);
$submenu[] = array("Equipment",	'equip.php',			3);
$submenu[] = array("Refine Simulator",	'refine.php',	3);

$pos = 0;
$menu = 'var mainmenu = new Array(';
$sub  = 'var submenu = new Array("", "", -1';

foreach ($mainmenu as $i => $mainmenudata) {
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
