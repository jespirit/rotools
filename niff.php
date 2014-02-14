<html>
<body>

<title>Niflheim Loot</title>

<h1>Niflheim Loot</h1>

<form name="loot" action="niff.php" method="post">
<table border='0' cellspacing='2'>
    <tr>
        <td><img src="images/7209.gif" alt="Helm of Dullahan" width="24" height="24" /></td> 
        <td>Helm of Dullahan:</td>
        <td><input type="text" name="helms" /></td>
    </tr>
    <tr>
        <td><img src="images/7210.gif" alt="Armor Piece of Dullahan" width="24" height="24" /> </td>
        <td>Armor Piece of Dullahan: </td>
        <td><input type="text" name="armors"/> </td>
    </tr>
    <tr>
        <td><img src="images/2505.gif" alt="Manteau" width="24" height="24" /> </td>
        <td>Manteau: </td>
        <td><input type="text" name="manteaus" /> </td>
    </tr>
    <tr>
        <td><img src="images/7216.gif" alt="Red Muffler" width="24" height="24" /> </td>
        <td>Red Muffler: </td>
        <td><input type="text" name="mufflers" /> </td>
    </tr>
    <tr>
        <td><img src="images/7221.gif" alt="Tangled Chains" width="24" height="24" /> </td>
        <td>Tangled Chains: </td>
        <td><input type="text" name="chains" /> </td>
    </tr>
    <tr>
        <td><img src="images/2508.gif" alt="Ragmuffin" width="24" height="24" /> </td>
        <td>Ragmuffin Manteau: </td>
        <td><input type="text" name="rags" /> </td></tr></td>
    <tr>
        <td><img src="images/7212.gif" alt="Hung Doll" width="24" height="24" /> </td>
        <td>Hung Doll: </td>
        <td><input type="text" name="dolls" /> </td>
    </tr>
    <tr>
        <td><img src="images/7218.gif" alt="Decomposed Rope" width="24" height="24" /> </td>
        <td>Decomposed Rope: </td>
        <td><input type="text" name="ropes" /> </td>
    </tr>

    <tr>
        <td><img src="images/7215.gif" alt="Broken Needle" width="24" height="24" /> </td>
        <td>Broken Needle: </td>
        <td><input type="text" name="needles" /> </td>
    </tr>
    <tr>
        <td><img src="images/7217.gif" alt="Spool" width="24" height="24" /> </td>
        <td>Spool: </td>
        <td><input type="text" name="spools" /> </td>
    </tr>
    <tr>
        <td><img src="images/7213.gif" alt="Needle Packet" width="24" height="24" /> </td>
        <td>Needle Packet: </td>
        <td><input type="text" name="packets" /> </td>
    </tr>
    <tr>
        <td><img src="images/7220.gif" alt="Ectoplasm" width="24" height="24" /> </td>
        <td>Ectoplasm: </td>
        <td><input type="text" name="ectos" /> </td>
    </tr>
    
<!--<img src="images/7209.gif" alt="Pumpkin Lantern" width="24" height="24" /> Pumpkin Lantern: <input type="text" name="pumpkins" /> </td></tr> -->
    <tr>
        <td><img src="images/7205.gif" alt="Piece of Black Cloth" width="24" height="24" /> </td>
        <td>Piece of Black Cloth: </td>
        <td><input type="text" name="cloths" /> </td>
    </tr>

</table>

<br />
<input type="submit" value="Evaluate" />
</form>

<?php

$helms = $_POST["helms"];
$armors = $_POST["armors"];
$mufflers = $_POST["mufflers"];
$chains = $_POST["chains"];
$cloths = $_POST["cloths"];
$ectos = $_POST["ectos"];
$gnarls = $_POST["gnarls"];
$spools = $_POST["spools"];
$ropes = $_POST["ropes"];

$packets = $_POST["packets"];
$needles = $_POST["needles"];

$manteaus = $_POST["manteaus"];
$rags = $_POST["rags"];

$total = 0;

$skilllvl = 10;
$overcharge = array(7, 9, 11, 13, 15, 17, 19, 21, 23, 24);
$keys = array("helms", "armors", "manteaus", 
              "mufflers", "chains", "rags",
              "dolls", "ropes", "gnarls", "rubys",
              "needles", "spools", "packets", "ectos",
              "cloths"
              );
             
             
# can also create a mult-dimensional array
# ie "helms" => array($_POST["helms"], "Helm of Dullahan"),
$data = array(
            "helms" => array($_POST["helms"], "Helm of Dullahan", "images/7209.gif", 675),
            "armors" => array($_POST["armors"], "Armor Piece of Dullahan", "images/7210.gif", 395),
            "manteaus" => array($_POST["manteaus"], "Manteau", "images/2505.gif", 16000),
            
            "mufflers" => array($_POST["mufflers"], "Red Muffler", "images/7216.gif", 330),
            "chains" => array($_POST["chains"], "Tangled Chains", "images/7221.gif", 370),
            "rags" => array($_POST["rags"], "Ragmuffin Manteau", "images/2508.gif", 28000),
            
            "dolls" => array($_POST["dolls"], "Hung Doll", "images/7212.gif", 510),
            "ropes" => array($_POST["ropes"], "Decomposed Rope", "images/7218.gif", 195),
            "gnarls" => array($_POST["gnarls"], "Wooden Gnarl", "images/7222.gif", 234),
            "rubys" => array($_POST["rubys"], "Cursed Ruby", "images/724.gif", 300),
            
            "needles" => array($_POST["needles"], "Broken Needle", "images/7215.gif", 345),
            "spools" => array($_POST["spools"], "Spool", "images/7217.gif", 212),
            "packets" => array($_POST["packets"], "Needle Packet", "images/7213.gif", 416),
            "ectos" => array($_POST["ectos"], "Ectoplasm", "images/7220.gif", 161),
            
            "cloths" => array($_POST["cloths"], "Piece of Black Cloth", "images/7205.gif", 263),
        );
        

print "<table border='1' cellpadding='10' >" .
      "<tr>" .
      "<th>Item</th><th>Amount</th><th>Charge</th><th>Zeny</th>" .
      "</tr>";

foreach ($keys as $k=>$v) {
    $value = sprintf("%d", $data[$v][3] * (100 + $overcharge[$skilllvl-1]) / 100);  # overcharge value
    $zeny = $value * $data[$v][0];   # amount * overcharge value
    $total += $zeny;
    
    $amount = ($data[$v][0] > 0) ? $data[$v][0] : '&nbsp;'; # show empty cell?
    $zenyfm = "&nbsp;";
    
    if ($zeny > 0)
        $zenyfm = get_currency($zeny);

    print "<tr>" .
          "<td><img src='" . $data[$v][2] . "' alt='" . $data[$v][1] . " width='24' height='24' /></td>" .
          "<td>" . $amount . "</td>" .
          "<td>$value</td>" .
          "<td>$zenyfm</td>" .
          "</tr>";
}

print "<tr>" .
      "<td>Total</td>" .
      "<td colspan='3' align='right'>" . get_currency($total) . "</td>" .
      "</tr>";

print "</table>";

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

</body>
</html>