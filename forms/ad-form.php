<script type="text/javascript">
function toggleMenu(objID) {
	if (!document.getElementById) return;
	var ob = document.getElementById(objID).style;
	ob.display = (ob.display == 'block')?'none':'block';
}
</script>

<h1>Acid Demonstration Bottles</h1>
<form id='acid_demo' name='acid_demo' onsubmit="return GET_ajax('acid_demo.php', 'acid_div', 'acid_demo');">
	<h2>Ingredients</h2>
	<table border='1'>
		<tr>
			<td>Acid Demonstration Bottles:</td>
			<td><input type='text' name='ad_num' /></td>
		</tr>
		<tr>
			<td>Success Chance (%):</td>
			<td><input type='text' name='per' /></td>
		</tr>
		<tr>
			<td>Immortal Heart</td>
			<td><input type='text' name='iheart'></td>
		<tr>
			<td>AD Set:</td>
			<td><input type='text' name='ad_set' /></td>
		</tr>
		<tr>
			<td>Merchant Discount Level:</td>
			<td>
				<select name='discountlvl'>
				<?php
					foreach (range(0,10) as $n) {
						if ($n == 10)
							printf("<option value='%d' selected='selected'>%d</option>", $n, $n);
						else
							printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
				</select>
			</td>
		</tr>
			<td>
				<div class="mH" onclick="toggleMenu('in-stock')">+ Enter Amounts</div>
				<table id="in-stock">
				<?php
					$items = array("Empty Bottle", "Alcohol", "Fabric", "Medicine Bowl", "Immortal Heart", 
						"Stem", "Empty Test Tube", "Poison Spore");
					
					$x = 0;
					foreach ($items as $v) {
						$x++;
						print "<tr><td>$v</td><td><input type='text' name='n$x'></td></tr>";
					}
				?>
				</table>
			</td>
		</tr>
	</table>

	<input type='submit' name='submit' value='Submit' />
</form>
<div>
<table id='bottle-ingredients' class="clear" >
	<tr>
		<td>Alcohol</td>
		<td>
			<ul class='ingredients'>
				<li>Empty Bottle x 1</li>
				<li>Empty Test Tube x 1</li>
				<li>Stem x 5</li>
				<li>Poison Spore x 5</li>
				<li>Medicine Bowl x 1</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td>Bottle Grenade</td>
		<td>
			<ul class='ingredients'>
				<li>Empty Bottle x 1</li>
				<li>Alcohol x 1</li>
				<li>Fabric x 1</li>
				<li>Medicine Bowl x 1</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td>Acid Bottle</td>
		<td>
			<ul class='ingredients'>
				<li>Empty Bottle x 1</li>
				<li>Immortal Heart x 1</li>
				<li>Medicine Bowl x 1</li>
			</ul>
		</td>
	</tr>
</table>
</div>

<div id='acid_div'></div>