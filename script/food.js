
/*
var foodopt = document.calcForm.FoodLv;

for (var i=1; i<=10; i++) {
var opt = document.createElement("option");
opt.value = i;
opt.innerHTML = ""+i;
foodopt.appendChild(opt);
}
*/

// 4 kits: min,avg,max
var make_per = [[0,0,0],[0,0,0],[0,0,0],[0,0,0]];
var make_limit = 10000;

function getRandomInt(min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}

function nan2zero(val) {
    if (isNaN(val))
        return 0;
    else
        return val;
}

function float2int(val) {
    return val | 0;
}

/* 	Format a number using the thousands separator.
 */
function format_num(num){
	if (isNaN(num)) return;
	
	var str = "";
	var x = new Array();
	if(num < 0){
		num = num * -1;
		str += "-";
	}
	for(var i=0;Math.floor(num / 1000) != 0;i++){
		var w = (num % 1000);
		if(w == 0){
			x[i] = ",000";
		}else if(w < 10){
			x[i] = ",00" + w;
		}else if(w < 100){
			x[i] = ",0" + w;
		}else{
			x[i] = "," + w;
		}
		num = Math.floor(num / 1000);
	}
	x[i] = num;
	while(i>=0){
		str += x[i];
		i--;
	}
	return str;
}

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
    
    // Show table and food ingredients
    calc();
    update_list();
}

function update_amounts() {
with(document.food) {
	var qty = parseInt(Qty.value);
	var food_type = FoodType.value;
	var food_level = parseInt(FoodLv.value);
	var num_ing = food[food_type][food_level-1]["ing"].length;
    
    qty = (/^[0-9]+/.test(qty)) ? qty : 0;
    if (qty > make_limit)
        qty = make_limit;
	
	var i, amount;
	for (i=0; i<num_ing; i++) {
		amount = food[food_type][food_level-1]["ing"][i]["amount"];
		$("#ingredients #ing"+i + " td span").text("= " + (amount*qty));
	}
}}

function update_list() {
	var form = document.getElementById("food");
	var food_type = form.FoodType.value;
	var food_level = parseInt(form.FoodLv.value);
	
	var i, ing;
	var ing_field;
	var str = "<table id='ingredients'>";
	
	str += "<tr><td colspan='2'>" + food[food_type][food_level-1]["food"][1] + "</td></tr>";
	for (i=0; i<food[food_type][food_level-1].ing.length; i++) {
		ing_field = "field" + i;
		ing = food[food_type][food_level-1].ing[i];
		str += 
		"<tr id='ing"+i+"'> \
			<td>" + ing['id'] + "</td> \
			<td>" + ing['name'] + "</td> \
			<td>" + ing['amount'] + "</td> \
			<td><span></span</td> \
			<td><input type='text' name='"+ing_field+"' value='"+ ing['price'] + "'></td> \
		</tr>";
	}
	// TODO: Save price of ingredients for later use.
	str += "</table>";
	
	$("#food").find("#ingredients").remove();
	$("#food").append(str);
}

function calc() {
with(document.getElementById("food")){
	var cooking_set = new Array(11,12,13,14,15);
	var kits = ["Outdoor", "Home", "Professional", "Royal"];
	var kit_type = $("input[name=KitType]:checked").val();
	var food_type = FoodType.value;
	var food_level = parseInt(FoodLv.value);
	var base_level = parseInt(BaseLv.value);
	var dex = parseInt(Dex.value);
	var luk = parseInt(Luk.value);
	var num_ing = food[food_type][food_level-1]["ing"].length;
	var cook_exp = parseInt(CookExp.value);
	var bless_check = Blessing.checked;
	var gloria_check = Gloria.checked;
	var gospel_check = Gospel.checked;
	var out = document.getElementById("output");
	var str = "	<table border='1'> \
				<tr> \
					<th>Cooking Kit</td> \
					<th>Min</td> \
					<th>Avg</td> \
					<th>Max</td> \
				</tr>";
                
    food_level = nan2zero(food_level);
    base_level = nan2zero(base_level);
    dex = nan2zero(dex);
    luk = nan2zero(luk);
    cook_exp = nan2zero(cook_exp);
	
	if (bless_check)
		dex += 10;
	if (gloria_check)
		luk += 30;
	
	if (gospel_check) {
		dex += 20;
		luk += 20;
	}
	
	var i,j;
    var max_variation = 30 + 5*(Math.floor(cook_exp/400)) - (6 + Math.floor(cook_exp/80)) - 1;
	for (i=0; i<4; i++) {
		make_per[i][0] = 1200 * (cooking_set[i] - 10)
					  + 20 * (base_level + 1)
				      + 20 * (dex + 1)
				      + 100 * (0 + 6 + Math.floor(cook_exp/80))  // rand=0
					  - 400 * (food_level+10 - 11 + 1)
					  - 10 * (100 - luk + 1)
					  - 500 * (num_ing - 1)
					  - 100 * 4;  // rand=4
        make_per[i][1] = 1200 * (cooking_set[i] - 10)
					  + 20 * (base_level + 1)
				      + 20 * (dex + 1)
				      + 100 * (max_variation/2 + 6 + Math.floor(cook_exp/80))  // max variation halved
					  - 400 * (food_level+10 - 11 + 1)
					  - 10 * (100 - luk + 1)
					  - 500 * (num_ing - 1)
					  - 100 * 2;  // rand=2
		make_per[i][2] = 1200 * (cooking_set[i] - 10)
					  + 20 * (base_level + 1)
				      + 20 * (dex + 1)
				      + 100 * (max_variation + 6 + Math.floor(cook_exp/80))  // max variation
					  - 400 * (food_level+10 - 11 + 1)
					  - 10 * (100 - luk + 1)
					  - 500 * (num_ing - 1)
					  - 100 * 1;  // rand=1
		
		for (j=0; j<3; j++) {
			if (make_per[i][j] > 10000)
				make_per[i][j] = 10000;
		}
		
		//make_per[i][1] = Math.floor((make_per[i][0] + make_per[i][2])/2);
		
		if (i == kit_type)  // Highlight the row in yellow
			str += "<tr style='background-color: #ffff0f'><td>" + kits[i] + "</td>";
		else
			str += "<tr><td>" + kits[i] + "</td>";
			
		for (j=0; j<3; j++) {
			str += "<td>" + (make_per[i][j]/100).toFixed(2) + "%</td>";
		}
		str += "</tr>";
	}
	str += "</table>";
	out.innerHTML = str;
}}

function makefood() {
with(document.food){
	var cooking_set = new Array(11,12,13,14,15);
	var kits = ["Outdoor", "Home", "Professional", "Royal"];
	//var stats = ["str", "agi", "vit", "int", "dex", "luk"];
	var stat = FoodType.value;
	var food_level = parseInt(FoodLv.value);
	var base_level = parseInt(BaseLv.value);
	var dex = parseInt(Dex.value);
	var luk = parseInt(Luk.value);
	//var ing_count = parseInt(IngCount.value);
	var num_ing = food[stat][food_level-1]["ing"].length;
	var cook_exp = parseInt(CookExp.value);
	var qty = parseInt(Qty.value);
	var gospel_check = Gospel.checked;
	var kit_type = $("input[name=KitType]:checked").val();
	var kit_cost = parseInt(KitCost.value);
	var per;
	var out = document.getElementById("makefood");
	var str = "";
    
    food_level = nan2zero(food_level);
    base_level = nan2zero(base_level);
    dex = nan2zero(dex);
    luk = nan2zero(luk);
    cook_exp = nan2zero(cook_exp);
    kit_cost = nan2zero(kit_cost);
	
	qty = (/^[0-9]+/.test(qty)) ? qty : 0;
    if (qty > make_limit)
        qty = make_limit;
	
	if (gospel_check) {
		dex += 20;
		luk += 20;
	}
	
	var field;
	var i,total=0,success=0,failed=0;
	var chance, price;
	var sell;
	
	total = kit_cost;
	for (i=0; i<num_ing; i++) {
		field = "field" + i;
		price = parseInt(document.food[field].value);
		total += price * food[stat][food_level-1]["ing"][i]["amount"];
	}
	
	sell = float2int(total / (make_per[kit_type][1] / 10000));
	
	str += "Total (1): " + format_num(total) + "<br/>"
		+ "Selling Price: " + format_num(sell) + "<br/>";
	
	for (i=0; i<qty; i++) {
		per = 1200 * (cooking_set[kit_type] - 10)
			+ 20 * (base_level + 1)
			+ 20 * (dex + 1)
			+ 100 * (getRandomInt(0,23) + 6 + Math.floor(cook_exp/80))
			- 400 * (food_level+10 - 11 + 1)
			- 10 * (100 - luk + 1)
			- 500 * (num_ing - 1)
			- 100 * getRandomInt(1,4);
		
		if (getRandomInt(0,10000-1)<per) {
			success++;
			str += "Success! You made Level " + food_level + " " + stat + "<br/>";
		}
		else {
			failed++;
			str += "Failed!<br/>";
		}
	}
	
	str += "You spent a total of " + format_num(total*qty) + "z<br/>"
		+  "You made " + success + " stat food which costs you " + format_num(total*success) + "<br/>"
		+  "You failed to create " + failed + " which costs you " + format_num(total*failed) + "<br/>";
		
	chance = success / qty;
	str += "You need to charge " + format_num(Math.floor(total / chance)) + " to make back the money you spent<br/>";
		
	out.innerHTML = str;
}}