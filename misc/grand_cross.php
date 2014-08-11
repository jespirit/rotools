<?php

$max = 10;
$hp = 15000;
$prev = $hp;
$prev2 = 0;
$delta2 = 0;

print "<table border='1' cellpadding='10' >" .
      "<tr>" .
      "<th>x</th><th>HP</th><th>Delta</th><th>Delta2</th>" .
      "</tr>";
      
print "<tr>" .
      "<td>0</td>" .
      "<td>$hp</td>" .
      "<td>0</td>" .
      "<td>0</td>" .
      "</tr>";
      
for ($x=1; $x<=$max; $x++) {
    $line = "<tr>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "<td>%d</td>" .
            "</tr>\n";
            
    $hp = $hp * 80/100;
    $delta = $prev - $hp;
    $delta2 = $prev2 - $delta;
    $prev = $hp;
    $prev2 = $delta;
    printf($line, $x, $hp, $delta, $delta2);
}
      
print "</table>";

?>