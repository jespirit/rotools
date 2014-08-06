
/*
var foodopt = document.calcForm.FoodLv;

for (var i=1; i<=10; i++) {
var opt = document.createElement("option");
opt.value = i;
opt.innerHTML = ""+i;
foodopt.appendChild(opt);
}
*/

with (document.food) {
	var i;
	var types = ["str", "agi", "vit", "int", "dex", "luk"];
	
	for (i=0; i<types.length; i++) {
		FoodType.options[i] = new Option(types[i], types[i]);
	}
	for (i=1; i<=10; i++) {
		FoodLv.options[i-1] = new Option(i, i);
	}
	for (i=1; i<=99; i++) {
		BaseLv.options[i-1] = new Option(i, i);
	}
}

function calc() {
with(document.getElementById("food")){
	var cooking_set = new Array(11,12,13,14,15);
	var kits = ["Outdoor", "Home", "Professional", "Royal"];
	var food_type = FoodType.value;
	var food_level = parseInt(FoodLv.value);
	var base_level = parseInt(BaseLv.value);
	var dex = parseInt(Dex.value);
	var luk = parseInt(Luk.value);
	var ing_count = parseInt(IngCount.value);
	var cook_exp = parseInt(CookExp.value);
	var bless_check = Blessing.checked;
	var gloria_check = Gloria.checked;
	var gospel_check = Gospel.checked;
	var make_per = [0,0,0];  // min,avg,max
	var out = document.getElementById("output");
	var str = "<table border='1'>";
	
	if (bless_check)
		dex += 10;
	if (gloria_check)
		luk += 30;
	
	if (gospel_check) {
		dex += 20;
		luk += 20;
	}
	
	var i,j;
	for (i=0; i<4; i++) {
		make_per[0] = 1200 * (cooking_set[i] - 10)
					  + 20 * (base_level + 1)
				      + 20 * (dex + 1)
				      + 100 * (0 + 6 + Math.floor(cook_exp/80))  // rand=0
					  - 400 * (food_level+10 - 11 + 1)
					  - 10 * (100 - luk + 1)
					  - 500 * (ing_count - 1)
					  - 100 * 4;  // rand=4
		make_per[2] = 1200 * (cooking_set[i] - 10)
					  + 20 * (base_level + 1)
				      + 20 * (dex + 1)
				      + 100 * (23 + 6 + Math.floor(cook_exp/80))  // rand=23
					  - 400 * (food_level+10 - 11 + 1)
					  - 10 * (100 - luk + 1)
					  - 500 * (ing_count - 1)
					  - 100 * 1;  // rand=1
		
		for (j=0; j<3; j++) {
			if (make_per[j] > 10000)
				make_per[j] = 10000;
		}
		
		make_per[1] = Math.floor((make_per[0] + make_per[2])/2);
		
		str += "<tr><td>" + kits[i] + " Cooking Kit</td>";
		for (j=0; j<3; j++) {
			str += "<td>" + (make_per[j]/100).toFixed(2) + "%</td>";
		}
		str += "</tr>";
	}
	str += "</table>";
	
	str += "<table>";
	var i, ing;
	for (i=0; i<food[food_type][food_level-1].ing.length; i++) {
		ing = food[food_type][food_level-1].ing[i];
		str += 
		"<tr> \
			<td>" + ing['id'] + "<td> \
			<td>" + ing['name'] + "<td> \
			<td>" + ing['amount'] + "<td> \
		</tr>";
	}
	str += "</table>";
	out.innerHTML = str;
}}