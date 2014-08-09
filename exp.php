<?php

session_start();
include_once 'config.php'; // loads config variables
include_once 'query.php'; // imports queries
include_once 'functions.php';

if (!isset($GET_frm_name)) {

echo <<<EOF
<script>
(function() {
	$("head").append('
		<style type="text/css">
			#choose-class td { float:none; }
			#main h2 
			{ 	
				/*font-size: 12px;
				font-weight: normal;*/
				color: #fff;
				margin: 0;
				padding: 10px 0 5px 10px;
				background-color: #fc672a;
			}
		</style>'
	);
}());
</script>

<h2>Base Exp Calculator</h2>
<form id="bexp_form" name="bexp_form" onsubmit="return GET_ajax('bexp.php', 'bexp_div', 'bexp_form');">
<table border='0' cellspacing='2'>
	<tr>
		<td>Class:</td>
		<td><input type="radio" name="roclass" value="fjob" checked="checked" />First</td>
		<td><input type="radio" name="roclass" value="sjob" />Second</td>
	</tr>
	<tr>
		<td>Start Level:</td>
		<td><input type="text" name="lvl1" /></td>
		
		<td>Start Exp:</td>
		<td><input type="text" name="exp1" /></td>
		
		<td>Exp Gain:</td>
		<td><input type="text" name="expgain" /></td>
	</tr>
	<tr>
		<td>End Level:</td>
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
		<td><input type='radio' name='rclass' value='0' checked='checked' /> Novice</td>
		<td><input type='radio' name='rclass' value='1' /> Transcendent Novice</td>
	</tr>
	<tr>
		<td><input type='radio' name='rclass' value='2' /> First Class</td>
		<td><input type='radio' name='rclass' value='3' /> Second Class</td>
	</tr>
	<tr>
		<td><input type='radio' name='rclass' value='4' /> Transcendent First Class</td>
		<td><input type='radio' name='rclass' value='5' /> Transcendent Second Class</td>
	</tr>
</table>

<table border='0' cellspacing='2'>
	<tr>
		<td>Start Level:</td>
		<td><input type="text" name="lvl1" /></td>
		
		<td>Start Exp:</td>
		<td><input type="text" name="exp1" /></td>
		
		<td>Exp Gain:</td>
		<td><input type="text" name="expgain" /></td>
	</tr>
	<tr>
		<td>End Level:</td>
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