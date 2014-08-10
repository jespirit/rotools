<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

echo <<<EOF

<h2>Base Exp Calculator</h2>
<form id="bexp_form" name="bexp_form" onsubmit="return GET_ajax('bexp.php', 'bexp_div', 'bexp_form');">
<table border='0' cellspacing='2'>
	<tr>
		<td>Class:</td>
		<td><input type="radio" name="roclass" value="fjob" checked="checked" />First</td>
		<td><input type="radio" name="roclass" value="sjob" />Second</td>
	</tr>
	<tr>
		<td>Start Base Level:</td>
		<td><input type="text" name="lvl1" /></td>
		
		<td>Start Exp:</td>
		<td><input type="text" name="exp1" /></td>
		
		<td>Exp Gain:</td>
		<td><input type="text" name="expgain" /></td>
	</tr>
	<tr>
		<td>End Base Level:</td>
		<td><input type="text" name="lvl2"/> </td>
		
		<td>End Exp:</td>
		<td><input type="text" name="exp2"/></td>
	</tr>
</table>
	
<br />
<input type="submit" name="submit" value="Evaluate" />

</form>

<h2>Job Exp Calculator</h2>

<form id="jexp_form" name="jexp_form" onsubmit="return GET_ajax('jexp.php', 'jexp_div', 'jexp_form');">
<table border='0' cellspacing='2' id="choose-class">
	<tr>
		<td><input type='radio' name='roclass' value='0' checked='checked'></td><td>Novice</td>
		<td><input type='radio' name='roclass' value='1'></td><td>First Class</td>
		<td><input type='radio' name='roclass' value='2'></td><td>Second Class</td>
	</tr>
	<tr>
		<td><input type='radio' name='roclass' value='4'></td><td>Novice High</td>
		<td><input type='radio' name='roclass' value='5'></td><td>Transcendent First Class</td>
		<td><input type='radio' name='roclass' value='6'></td><td>Transcendent Second Class</td>
	</tr>
	<tr>
		<td><input type='radio' name='roclass' value='3'></td><td>Super Novice</td>
		<td><input type='radio' name='roclass' value='7'></td><td>Star Gladiator</td>
		<td><input type='radio' name='roclass' value='8'></td><td>Ninja/Gunslinger</td>
	</tr>
</table>

<table border='0' cellspacing='2'>
	<tr>
		<td>Start Job Level:</td>
		<td><input type="text" name="lvl1" /></td>
		
		<td>Start Exp:</td>
		<td><input type="text" name="exp1" /></td>
		
		<td>Exp Gain:</td>
		<td><input type="text" name="expgain" /></td>
	</tr>
	<tr>
		<td>End Job Level:</td>
		<td><input type="text" name="lvl2"/> </td>
		
		<td>End Exp:</td>
		<td><input type="text" name="exp2"/></td>
	</tr>
</table>
	
<br />
<input type="submit" value="Evaluate" />
</form>		
<div id='bexp_div'></div>
<div id='jexp_div'></div>
EOF;
}