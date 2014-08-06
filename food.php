<html>
<head>
	<title>Food</title>
	<link rel="stylesheet" type="text/css" href="css/food.css">
</head>
<body>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<form id="food" name="food">
		<div>Food Type: <select name="FoodType" onchange="calc(); update_list();"></select></div>
		<div>Level of Food: <select name="FoodLv" onchange="calc(); update_list();"></select></div>
		<div>Base Level: <select name="BaseLv" onchange="calc()"></select></div>
		<div>DEX: <input type="text" name="Dex" onchange="calc()"></input></div>
		<div>LUK: <input type="text" name="Luk" onchange="calc()"></input></div>
		<div>Cooking Experience: <input type="text" name="CookExp" onchange="calc()"></input></div>
		<div>Blessing: <input type="checkbox" name="Blessing" onchange="calc()"></input></div>
		<div>Gloria: <input type="checkbox" name="Gloria" onchange="calc()"></input></div>
		<div>Gospel: <input type="checkbox" name="Gospel" onchange="calc()"></input></div>
		
		<div>How many? <input type="text" name="Qty" onchange="update_amounts()"></input></div>
		<div>Kit (Zeny) <input type="text" name="KitCost"></input></div>
		<div><input type="button" onclick="makefood()" value="Make"></input></div>
	</form>
	<div id="output"></div>
	<div id="makefood"></div>
	<script src="/php/script/food.js"></script>
	<script>
		var xmlhttp = new XMLHttpRequest();
		var url = "get-food.php";
		var food;  // global food object

		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				myFunction(xmlhttp.responseText);
			}
		}
		xmlhttp.open("GET", url, true);
		xmlhttp.send();
		
		function myFunction(response) {
			food = JSON.parse(response);
		}
	</script>
</body>
</html>