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
	
	<!--  Header -->
	<div id="header"></div>
	
	<!--  Menu -->
	<div id="main_menu"></div>
	<div id="menu_load" style="position:absolute; top:0px; left:0px; visibility:hidden;"></div>
	
	<!--  Loading Image -->
	<div id="load_div" style="position:absolute; top:161px; left:790px; height:30px width:25px; visibility:hidden; background-color:#000000; color:#FFFFFF"><img src="images/loading.gif" alt="Loading..."></div>
	
	<!--  Sub Menu -->
	<div id="sub_menu"></div>

	<!--  Content -->
	<div id="main_content">
		<div id="main_div"></div>
	</div>
	
	<!--  Footer -->
	<div id="footer">
		<font color="#FFFFFF">
			Copyright © 2014
		</font>
	</div>
	<script type="text/javascript">
		load_menu();
		LINK_ajax('motd.php', 'main_div');
	</script>
	</body>
</html>

<?php
fim();
?>