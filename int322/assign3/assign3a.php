<html>
<head>
	<title>INT322 Assignment 3</title>
</head>
<body>

<form name='assign3' method='post' action='assign3a.php'>
	Choose a lottery:
	<select name='lottery'>
		<option value='lotto649' selected='selected'>Lotto 6/49</option>
		<option value='lottomax'>Lotto MAX</option>
	</select>
	
	<table>
		<tr>
			<td>Find winning lottery numbers by date</td>
			<td><input type='radio' name='func' value='1'/></td>
			<td>
				Year:
				<select name='year'>
				<?php
					foreach (range(1982, 2011) as $year) {
						if ($year == 2011)
							printf("<option value='%d' selected='selected'>%d</option>", $year, $year);
						else
							printf("<option value='%d'>%d</option>", $year, $year);
					}
				?>
				</select>
				
				Month:
				<select name='month'>
				<option value='' selected='selected'></option>
				<?php
					foreach (range(1,12) as $month) {
						printf("<option value='%d'>%d</option>", $month, $month);
					}
				?>
				</select>
				
				Day:
				<select name='day'>
				<option value='' selected='selected'></option>
				<?php
					foreach (range(1,31) as $day) {
						printf("<option value='%d'>%d</option>", $day, $day);
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Lottery number sum that fall within a range</td>
			<td><input type='radio' name='func' value='2'/></td>
			<td>
				Min: <input type='text' name='min' />
				Max: <input type='text' name='max' />
			</td>
		</tr>
	
		<tr>
			<td>Find the mean value of a certain ball</td>
			<td><input type='radio' name='func' value='3'/></td>
			<td>
				Ball Number:
				<select name='ballno'>
				<?php
					foreach (range(1,7) as $n) {
						printf("<option value='%d'>%d</option>", $n, $n);
					}
				?>
				<option value='8'>Bonus</option>
				</select>
			</td>
		</tr>
	</table>
	
	<input type="submit" value="Submit" />
</form>

</body>
</html>