<?php
include "db.php";

$sql = "SELECT * FROM S";
$result = mq($sql);

echo "<table width='300' border='1' cellpadding='0' cellspacing='0'>\n";
foreach ($result as $row) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "<td>" . ($item !== NULL ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
?>
