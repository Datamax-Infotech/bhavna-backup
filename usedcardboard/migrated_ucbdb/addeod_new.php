<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$today = date('m/d/y h:i a');

$eod_date = $_POST["eod_date"];

$tablename = "";
$warehouse_name = "";
$sql2 = "SELECT * FROM ucbdb_warehouse where id = " . $_POST["warehouse_name"];
$result2 = db_query($sql2, db());
while ($myrowsel2 = array_shift($result2)) {
        $tablename = $myrowsel2["tablename"];
        $warehouse_name = $myrowsel2["distribution_center"];
}

if ($_POST["order_no"] != "") {
        $sql = "Update $tablename set eod_flag = 1, eod_date = '" . date("Y-m-d", strtotime($_POST["eod_date"])) . "' where orders_id = " . $_POST["order_no"];
        $result = db_query($sql, db());
}

$sql = "INSERT INTO ucbdb_endofday (warehouse_name, import_date, search_date, labels_on_report, labels_on_pickup, employee, file_name, order_no) VALUES ( '" . $warehouse_name . "','" . $today . "','" . $eod_date . "','" . $_POST['labels_on_report'] . "','','" . $_POST["employee"] . "','','" . $_POST["order_no"] . "')";
$result = db_query($sql, db());

if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: eod_upload_new.php');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"eod_upload_new.php";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=eod_upload_new.php\" />";
        echo "</noscript>";
        exit;
} //==== End -- Redirect
