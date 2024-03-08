<?php 
require ("inc/header_session.php");
?>


<?php
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


	
// Variables Passed for Auth.Net Credit Processing	
	


$sqlas = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_total'";
$resultas = db_query($sqlas,db() );
while ($myrowselas = array_shift($resultas)) {
$new_value = number_format($myrowselas['value'], 2);
$sql = "UPDATE orders_total SET text = '<b>$" . $new_value . "</b>' WHERE orders_total_id = " . $myrowselas['orders_total_id'];
echo $sql;
$result = db_query($sql,db() );
}

echo "<br><br>";
echo "<br><br>";
echo "<br><br>";

$sqlas2 = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_subtotal'";
$resultas2 = db_query($sqlas2,db() );
while ($myrowselas2 = array_shift($resultas2)) {
$new_value2 = number_format($myrowselas2['value'], 2);
$sql2 = "UPDATE orders_total SET text = '$" . $new_value2 . "' WHERE orders_total_id = " . $myrowselas2['orders_total_id'];
echo $sql2;
$result2 = db_query($sql2,db() );
}


echo "<br><br>";
echo "<br><br>";
echo "<br><br>";


$sqlas3 = "SELECT * FROM orders_total WHERE text = '' AND class = 'ot_tax'";
$resultas3 = db_query($sqlas3,db() );
while ($myrowselas3 = array_shift($resultas3)) {
$new_value3 = number_format($myrowselas3['value'], 2);
$sql3 = "UPDATE orders_total SET text = '$" . $new_value3 . "' WHERE orders_total_id = " . $myrowselas3['orders_total_id'];
echo $sql3;
$result3 = db_query($sql3,db() );
}



/*
echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"orders.php?id=" . $_POST[orders_id] . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $_POST[orders_id] . "&proc=View\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');

*/
?>