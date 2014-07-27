<?php
/*
Ceres Control Panel

This is a control panel program for eAthena and other Athena SQL based servers
Copyright (C) 2005 by Beowulf and Dekamaster

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

extension_loaded('mysqli')
	or die ("Mysqli extension not loaded. Please verify your PHP configuration.");

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

$_SESSION[$CONFIG_name.'castles'] = readcastles();
$_SESSION[$CONFIG_name.'jobs'] = readjobs();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>
			<?php echo htmlformat($CONFIG_name); ?> - Control Panel
		</title>
		<link rel="stylesheet" type="text/css" href="./ceres.css">

		<script type="text/javascript" language="javascript" src="ceres.js"></script>
	</head>

	<body>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	
	<!-- CeresCP Header -->
	<div id="header"></div>
	
	<!-- CeresCP Menu -->
	<div id="main_menu"></div>
	<div id="menu_load" style="position:absolute; top:0px; left:0px; visibility:hidden;"></div>
	
	<!-- CeresCP Loading Image -->
	<div id="load_div" style="position:absolute; top:161px; left:790px; height:30px width:25px; visibility:hidden; background-color:#000000; color:#FFFFFF"><img src="images/loading.gif" alt="Loading..."></div>
	
	<!-- CeresCP Sub Menu -->
	<div id="sub_menu"></div>

	<!-- CeresCP Content -->
	<div id="main_content">
		<div id="main_div"></div>
		<!-- CeresCP Sidebar -->
		<div id="sidebar">
			<div id="login_div"></div>
			<div id="new_div"></div>
			<div id="status_div"></div>
			<div id="selectlang_div"></div>
		</div>
	</div>
	
	<!-- CeresCP Footer -->
	<div id="footer">
			<font color="#FFFFFF">
				Copyright © 2005-2012
				<span style="cursor:pointer" class="copyright_link" onClick="window.open('http://cerescp.sourceforge.net/');">
					Ceres Control Panel</span> by Beowulf and Dekamaster
					<BR>
					Powered by <span style="cursor:pointer" onClick="window.open('http://en.wikipedia.org/wiki/KISS_principle');">
					<img src="images/kiss.png" alt="Keep It Simple Stupid! Technology" border="0" align=bottom>
				</span> <span style="cursor:pointer" onClick="window.open('http://validator.w3.org/check?uri='+document.URL);">
					<img src="http://cerescp.sourceforge.net/ceres/img.php?<?echo $qwty?>" alt="w3c" style="visibility:hidden">
					<img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="15" width="43">
				</span>
			</font>
	</div>
	<script type="text/javascript">
			load_menu();
			LINK_ajax('motd.php', 'main_div');
			LINK_ajax('login.php', 'login_div');
			login_hide(2);
			server_status()
			LINK_ajax('selectlang.php', 'selectlang_div');
	</script>
	</body>
</html>

<?php
fim();
?>