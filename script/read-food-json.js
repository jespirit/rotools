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