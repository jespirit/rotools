<?php

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
	<script src='script/food.js' type='text/javascript'></script>
	
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