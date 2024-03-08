<?php  
require ("inc/header_session.php");
?>


<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");



$orders_id = $_GET['orders_id'];

$today = date("Ymd"); 

$sql = "UPDATE orders_active_export SET chargeback = 2 WHERE id = " . $_GET['id'];
db_query($sql,db() );

/*
echo $orders_id;
echo "<BR><BR>";
echo $sql;
exit;
*/

/* 
$sql32 = "INSERT INTO ucbdb_credits (orders_id, item_name, warehouse, reason_code, quantity, chargeback, total, employee, pending, notes,  credit_date, auth_trans_id, cc_number, cc_expires) VALUES ( '" . $_POST[orders_id] . "','','" . $_POST[warehouse_other] . "','','','" . $_POST[chargeback_other] . "','" . $other_amt . "','" . $_POST[employee] . "','" . $pending . "','" . $other . "','" . $today . "','" . $auth_trans_id . "','" . $cc_number . "','" . $cc_expires . "')";
db_query($sql32,db() );


$sql2 = "SELECT replace(GROUP_CONCAT(concat(item_name, ' ', warehouse, ' ', notes, ' ', reason_code, ' ', chargeback, ' ' ,format(total, 2))), ',', '<br>') as dt FROM ucbdb_credits WHERE orders_id = " . $_POST[orders_id] . " AND total > 0 AND pending = 'crm'";
$rw = array_shift(db_query($sql2));
$output .= "Pending Credit<br>";
$output .= $rw["dt"];
$output .= "<br>";
$output .= "Total: " . $orders_total;
$output .= "<br>";


$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$commqryrw = array_shift(db_query($commqry));
$comm_type = $commqryrw["id"];  

$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $_POST[orders_id] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
$result3 = db_query($sql3,db() );

$prndnew = "Pending";

$sql_updcrm3 = "UPDATE ucbdb_credits SET pending = '$prndnew' WHERE pending = 'crm'";
$resultupdcrm3 = db_query($sql_updcrm3,db() );

*/

echo "<DIV CLASS='SQL_RESULTS'>Record Updated<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: orders.php?id=' . $orders_id . '&proc=View'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"orders.php?id=" . $orders_id . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $orders_id . "&proc=View\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');
?>