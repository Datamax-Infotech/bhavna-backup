<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
$orders_id = decrypt_url($_GET['orders_id']);
$today = date("Ymd");
db();
$sql = "UPDATE orders_active_export SET chargeback = 2 WHERE id = " . $_GET['id'];
db_query($sql);
echo "<DIV CLASS='SQL_RESULTS'>Record Updated<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: orders.php?id=' . encrypt_url($orders_id)  . '&proc=View');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"orders.php?id=" . encrypt_url($orders_id)  . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . encrypt_url($orders_id) . "&proc=View\" />";
        echo "</noscript>";
        exit;
}
