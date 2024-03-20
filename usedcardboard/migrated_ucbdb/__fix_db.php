<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

db();
$sqlas = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_total'";
$resultas = db_query($sqlas);
while ($myrowselas = array_shift($resultas)) {
        $new_value = number_format($myrowselas['value'], 2);
        $sql = "UPDATE orders_total SET text = '<b>$" . $new_value . "</b>' WHERE orders_total_id = " . $myrowselas['orders_total_id'];
        echo $sql;
        $result = db_query($sql);
}

echo "<br><br>";
echo "<br><br>";
echo "<br><br>";

$sqlas2 = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_subtotal'";
$resultas2 = db_query($sqlas2);
while ($myrowselas2 = array_shift($resultas2)) {
        $new_value2 = number_format($myrowselas2['value'], 2);
        $sql2 = "UPDATE orders_total SET text = '$" . $new_value2 . "' WHERE orders_total_id = " . $myrowselas2['orders_total_id'];
        echo $sql2;
        $result2 = db_query($sql2);
}


echo "<br><br>";
echo "<br><br>";
echo "<br><br>";


$sqlas3 = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_tax'";
$resultas3 = db_query($sqlas3);
while ($myrowselas3 = array_shift($resultas3)) {
        $new_value3 = number_format($myrowselas3['value'], 2);
        $sql3 = "UPDATE orders_total SET text = '$" . $new_value3 . "' WHERE orders_total_id = " . $myrowselas3['orders_total_id'];
        echo $sql3;
        $result3 = db_query($sql3);
}

