<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$orders_id = $_POST['orders_id'];
$today = date("Ymd");
$r_count = $_POST['count'];
for ($i = 0; $i < $r_count; $i++) {
        $sql = "UPDATE orders_active_export SET return_tracking_number = '" . $_POST['return_tracking_number'][$i] . "', return_delivery_service = '" . $_POST['return_delivery_service'][$i] . "', return_status = 2 WHERE id = " . $_POST['id'][$i];
        db_query($sql);
}
echo "<DIV CLASS='SQL_RESULTS'>Record Updated<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: orders.php?id=' . encrypt_url($_POST['orders_id']) . '&proc=View');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"orders.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View\" />";
        echo "</noscript>";
        exit;
} 

