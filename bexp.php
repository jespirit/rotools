<?php

# TODO: Read Base exp from file instead of hard-coded

// first job exp
$exp = array(
	"fjob" => array(
			// lvl, exp, exp2nextlvl
            array(1, 	0, 	9,  0),
            array(2, 	9, 	16, 	77.8),
            array(3, 	25, 	25, 	56.3),
            array(4, 	50, 	36, 	44.0),
            array(5, 	86, 	77, 	113.9),
            array(6,	163,	112,	45.5),
            array(7,	275,	153,	36.6),
            array(8,	428,	200,	30.7),
            array(9,	628,	253,	26.5),
            array(10,	881,	320,	26.5),
            array(11,	1201,	385,	20.3),
            array(12,	1586,	490,	27.3),
            array(13,	2076,	585,	19.4),
            array(14,	2661,	700,	19.7),
            array(15,	3361,	830,	18.6),
            array(16,	4191,	970,	16.9),
            array(17,	5161,	1120,	15.5),
            array(18,	6281,	1260,	12.5),
            array(19,	7541,	1420,	12.7),
            array(20,	8961,	1620,	14.1),
            array(21,	10581,	1860,	14.8),
            array(22,	12441,	1990,	7.0 ),
            array(23,	14431,	2240,	12.6 ),
            array(24,	16671,	2504,	11.8 ),
            array(25,	19175,	2950,	17.8 ),
            array(26,	22125,	3426,	16.1 ),
            array(27,	25551,	3934,	14.8 ),
            array(28,	29485,	4474,	13.7 ),
            array(29,	33959,	6889,	54.0 ),
            array(30,	40848,	7995,	16.1 ),
            array(31,	48843,	9174,	14.7 ),
            array(32,	58017,	10425,	13.6 ),
            array(33,	68442,	11748,	12.7 ),
            array(34,	80190,	13967,	18.9 ),
            array(35,	94157,	15775,	12.9 ),
            array(36,	109932,	17678,	12.1 ),
            array(37,	127610,	19677,	11.3 ),
            array(38,	147287,	21773,	10.7 ),
            array(39,	169060,	30543,	40.3 ),
            array(40,	199603,	34212,	12.0 ),
            array(41,	233815,	38065,	11.3 ),
            array(42,	271880,	42102,	10.6 ),
            array(43,	313982,	46323,	10.0 ),
            array(44,	360305,	53026,	14.5 ),
            array(45,	413331,	58419,	10.2 ),
            array(46,	471750,	64041,	9.6 ),
            array(47,	535791,	69892,	9.1 ),
            array(48,	605683,	75973,	8.7 ),
            array(49,	681656,	102468,	34.9 ),
            array(50,	784124,	115254,	12.5 ),
            array(51,	899378,	128692,	11.7 ),
            array(52,	1028070,	142784,	11.0), 
            array(53,	1170854,	157528,	10.3 ),
            array(54,	1328382,	178184,	13.1 ),
            array(55,	1506566,	196300,	10.2 ),
            array(56,	1702866,	215198,	9.6 ),
            array(57,	1918064,	234879,	9.1 ),
            array(58,	2152943,	255341,	8.7 ),
            array(59,	2408284,	330188,	29.3 ),
            array(60,	2738472,	365914,	10.8 ),
            array(61,	3104386,	403224,	10.2 ),
            array(62,	3507610,	442116,	9.6 ),
            array(63,	3949726,	482590,	9.2 ),
            array(64,	4432316,	536948,	11.3 ),
            array(65,	4969264,	585191,	9.0 ),
            array(66,	5554455,	635278,	8.6 ),
            array(67,	6189733,	687211,	8.2 ),
            array(68,	6876944,	740988,	7.8 ),
            array(69,	7617932,	925400,	24.9 ),
            array(70,	8543332,	1473746,	59.3), 
            array(71,	10017078,	1594058,	8.2 ),
            array(72,	11611136,	1718928,	7.8 ),
            array(73,	13330064,	1848355,	7.5 ),
            array(74,	15178419,	1982340,	7.2 ),
            array(75,	17160759,	2230113,	12.5 ),
            array(76,	19390872,	2386162,	7.0 ),
            array(77,	21777034,	2547417,	6.8 ),
            array(78,	24324451,	2713878,	6.5 ),
            array(79,	27038329,	3206160,	18.1 ),
            array(80,	30244489,	3681024,	14.8 ),
            array(81,	33925513,	4022472,	9.3 ),
            array(82,	37947985,	4377024,	8.8 ),
            array(83,	42325009,	4744680,	8.4 ),
            array(84,	47069689,	5125440,	8.0 ),
            array(85,	52195129,	5767272,	12.5 ),
            array(86,	57962401,	6204000,	7.6 ),
            array(87,	64166401,	6655464,	7.3 ),
            array(88,	70821865,	7121664,	7.0 ),
            array(89,	77943529,	7602600,	6.8 ),
            array(90,	85546129,	9738720,	28.1 ),
            array(91,	95284849,	11649960,	19.6 ),
            array(92,	106934809,	13643520,	17.1 ),
            array(93,	120578329,	18339300,	34.4 ),
            array(94,	138917629,	23836800,	30.0 ),
            array(95,	162754429,	35658000,	49.6 ),
            array(96,	198412429,	48687000,	36.5 ),
            array(97,	247099429,	58135000,	19.4 ),
            array(98,	305234429,	99999998,	72.0 ),
            array(99,	405234427,	0,          0),
		),
          
// second job exp          
	"sjob" => array(
            array(1,	0,	10,	0),
            array(2,	10,	18,	80.0 ),
            array(3,	28,	28,	55.6 ),
            array(4,	56,	40,	42.9 ),
            array(5,	96,	85,	112.5 ),
            array(6,	181,	123,	44.7 ),
            array(7,	304,	168,	36.6 ),
            array(8,	472,	220,	31.0 ),
            array(9,	692,	278,	26.4 ),
            array(10,	970,	400,	43.9 ),
            array(11,	1370,	481,	20.3 ),
            array(12,	1851,	613,	27.4 ),
            array(13,	2464,	731,	19.2 ),
            array(14,	3195,	875,	19.7 ),
            array(15,	4070,	1038,	18.6 ),
            array(16,	5108,	1213,	16.9 ),
            array(17,	6321,	1400,	15.4 ),
            array(18,	7721,	1575,	12.5 ),
            array(19,	9296,	1775,	12.7 ),
            array(20,	11071,	2268,	27.8 ),
            array(21,	13339,	2604,	14.8 ),
            array(22,	15943,	2786,	7.0 ),
            array(23,	18729,	3136,	12.6 ),
            array(24,	21865,	3506,	11.8 ),
            array(25,	25371,	4130,	17.8 ),
            array(26,	29501,	4796,	16.1 ),
            array(27,	34297,	5508,	14.8 ),
            array(28,	39805,	6264,	13.7 ),
            array(29,	46069,	9645,	54.0 ),
            array(30,	55714,	12392,	28.5 ),
            array(31,	68106,	14220,	14.8 ),
            array(32,	82326,	16159,	13.6 ),
            array(33,	98485,	18209,	12.7 ),
            array(34,	116694,	21649,	18.9 ),
            array(35,	138343,	24451,	12.9 ),
            array(36,	162794,	27401,	12.1 ),
            array(37,	190195,	30499,	11.3 ),
            array(38,	220694,	33748,	10.7 ),
            array(39,	254442,	47342,	40.3 ),
            array(40,	301784,	58160,	22.9 ),
            array(41,	359944,	64711,	11.3 ),
            array(42,	424655,	71573,	10.6 ),
            array(43,	496228,	78749,	10.0 ),
            array(44,	574977,	90144,	14.5 ),
            array(45,	665121,	99312,	10.2 ),
            array(46,	764433,	108870,	9.6 ),
            array(47,	873303,	118816,	9.1 ),
            array(48,	992119,	129154,	8.7 ),
            array(49,	1121273,	174196,	34.9 ),
            array(50,	1295469,	213220,	22.4 ),
            array(51,	1508689,	238080,	11.7 ),
            array(52,	1746769,	264150,	11.0 ),
            array(53,	2010919,	291427,	10.3 ),
            array(54,	2302346,	329640,	13.1 ),
            array(55,	2631986,	363155,	10.2 ),
            array(56,	2995141,	398116,	9.6 ),
            array(57,	3393257,	434526,	9.1 ),
            array(58,	3827783,	472381,	8.7 ),
            array(59,	4300164,	610848,	29.3 ),
            array(60,	4911012,	731828,	19.8 ),
            array(61,	5642840,	806448,	10.2 ),
            array(62,	6449288,	884232,	9.6 ),
            array(63,	7333520,	965180,	9.2 ),
            array(64,	8298700,	1073896,	11.3 ),
            array(65,	9372596,	1170382,	9.0 ),
            array(66,	10542978,	1270556,	8.6 ),
            array(67,	11813534,	1374422,	8.2 ),
            array(68,	13187956,	1481976,	7.8 ),
            array(69,	14669932,	1850800,	24.9 ),
            array(70,	16520732,	3389616,	83.1 ),
            array(71,	19910348,	3666333,	8.2 ),
            array(72,	23576681,	3953534,	7.8 ),
            array(73,	27530215,	4251217,	7.5 ),
            array(74,	31781432,	4559382,	7.2 ),
            array(75,	36340814,	5129260,	12.5 ),
            array(76,	41470074,	5488173,	7.0 ),
            array(77,	46958247,	5859059,	6.8 ),
            array(78,	52817306,	6241919,	6.5 ),
            array(79,	59059225,	7374168,	18.1 ),
            array(80,	66433393,	9570662,	29.8 ),
            array(81,	76004055,	10458427,	9.3 ),
            array(82,	86462482,	11380262,	8.8 ),
            array(83,	97842744,	12336168,	8.4 ),
            array(84,	110178912,	13326144,	8.0 ),
            array(85,	123505056,	14994907,	12.5 ),
            array(86,	138499963,	16130400,	7.6 ),
            array(87,	154630363,	17304200,	7.3 ),
            array(88,	171934563,	18516326,	7.0 ),
            array(89,	190450889,	19766760,	6.8 ),
            array(90,	210217649,	29216160,	47.8 ),
            array(91,	239433809,	34949880,	19.6 ),
            array(92,	274383689,	40930560,	17.1 ),
            array(93,	315314249,	55017900,	34.4 ),
            array(94,	370332149,	71510400,	30.0 ),
            array(95,	441842549,	106974000,	49.6 ),
            array(96,	548816549,	146061000,	36.5 ),
            array(97,	694877549,	174405000,	19.4 ),
            array(98,	869282549,	343210000,	96.8 ),
            array(99,	1212492549,	0,          0),
        ),
);
        
$x = 0;

$gain = 0.0;
$lvl1 = $_POST["lvl1"];
$lvl2 = $_POST["lvl2"];
$exp1 = $_POST["exp1"] * 1;  // convert to number
$exp2 = $_POST["exp2"] * 1;
$roclass = $_POST["roclass"];

$expgain = (int)($_POST["expgain"] * 1);  // to number and truncate
$lvl3 = 0;
$exp3 = 0.0;

// 00-99 or 00.0-99.0 is permitted, but not 100 or 100.0
$exp_format = "^([0-9][0-9]?|[0-9][0-9]?\.[0-9])$";

$valid = 1;

// validate form fields
if (!($lvl1 >= 1 && $lvl1 <= 99)) {
    $valid = 0;
    print "Error <br />";
    print "Start Level must be in range 1-99 <br />";
}
else if (!($lvl2 >= 1 && $lvl2 <= 99 && $lvl2 >= $lvl1)) {
    $valid = 0;
    print "Error <br />";
    print "End Level must be in range 1-99 AND equal or greater than Start Level <br />";
}
else if (!preg_match("/$exp_format/", $exp1)) {  // empty field is allowed
    $valid = 0;
    print "Error <br />";
    print "Start Exp has invalid format, 00.0-99.9 or 0-99 <br />";
}
else if (!($exp1 >= 0.0 && $exp1 < 100.0)) {
    $valid = 0;
    print "Error <br />";
    print "Start Exp must be in range 0-100 <br />";
}
else if (!preg_match("/$exp_format/", $exp2)) {
    $valid = 0;
    print "Error <br />";
    print "End Exp has invalid format, 00.0-99.9 or 0-99 <br />";
}
else if (!($exp2 >= 0.0 && $exp2 < 100.0)) {
    $valid = 0;
    print "Error <br />";
    print "End Exp must be in range 0-100 <br />";
}
else if (!($gain >= 0)) {
    $valid = 0;
    print "Error <br />";
    print "Exp gain must be a positive integer <br />";
}


if (!$valid) {
    print "ERROR! <br />";
}
else {
        
    // form data is valid

    
    // calculate the amount of exp gained from level x to y
    // = exp2 - exp1 - (% at lvl1) + (% at lvl2)
    
    $total1 = $exp[$roclass][$lvl1-1][1];
    $total2 = $exp[$roclass][$lvl2-1][1];
    $next1 = $exp[$roclass][$lvl1-1][2];
    $next2 = $exp[$roclass][$lvl2-1][2];
	
	$gain = ($total2 - $total1) - ($next1 * $exp1/100) + ($next2 * $exp2/100);
    $gain = sprintf("%d", $gain);

	printf("(%d - %d) - %d + %d <br />", $total2, $total1, 
		$next1 * $exp1/100, $next2 * $exp2/100);
    print "You have gained " . number_format($gain) . " base experience. <br />";
	
	// Calculate the level and % when given x amount of experience points.

	$texp = $total1 + ($next1 * $exp1/100) + $expgain;
	print "Total exp amount: $texp <br />";
	
    // loop through the exp totals in reverse until we've found the level
    // with the correct amount of exp
	$i = 99;
	for (; $i>1; $i--) {  // no need to check level 1, condition would be always true
		if ($texp >= $exp[$roclass][$i-1][1])
			break;
	}
	
	$lvl3 = $i;
	
	if ($i == 99)	// avoid division by zero
		$exp3 = 100.0;  // $texp is implicitly capped here
	else
        // calculate how much % of experience you would earn at the final level
		$exp3 = ($texp - $exp[$roclass][$i-1][1]) * 100 / $exp[$roclass][$i-1][2];    // get % at lvl3
    
    $exp3 = sprintf("%.1f", $exp3); // format to 1 decimal places
    
    print "From Level $lvl1 at $exp1%, with a gain of " . number_format($expgain) . " experience, " .
          "You would be at Level $lvl3 with $exp3% <br />";
    
    /*
    for ($x=0; $x<99; $x++) {
        printf("%d %d %d %d <br />", $exp[$roclass][$x][0], $exp[$roclass][$x][1], $exp[$roclass][$x][2], $exp[$roclass][$x][3]);
    }*/

}

function get_currency($zeny) {
    $str = "";
    
    $s = strrev($zeny);    # . "";    # convert to string
    
    for ($i=0; $i<strlen($s); $i++) {
        if ($i%3 == 0 && $i != 0)
            $str = $str . ",";
            
        $str = $str . $s[$i];
    }
    
    return strrev($str);
}

?>