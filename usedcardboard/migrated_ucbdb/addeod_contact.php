<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
// $old_today = date("Ymd"); 
// $today = now(); 

$today = date('m/d/y h:i a');
$contact_id = $_POST['contact_id'];
$assigned_to = $_POST['assigned_to'];

$old_today = date("Ymd");
$realtoday = date("m d Y");
$datewtime = date("F j, Y, g:i a");

$search_today = $_POST['search_date'];

$sql = "INSERT INTO ucbdb_endofday (warehouse_name, import_date, search_date, labels_on_report, labels_on_pickup, employee, file_name) VALUES ( '" . $_POST['warehouse_name'] . "','" . $today . "','" . $search_today . "','" . $_POST['labels_on_report'] . "','" . $_POST['labels_on_pickup'] . "','" . $_POST['employee'] . "','" . $_POST["file"] . "')";
echo "<BR>SQL: $sql<BR>";
$result = db_query($sql);
$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$result = db_query($commqry);
$commqryrw = array_shift($result);
$comm_type = $commqryrw["id"];


$output = "<b>" . $_POST['issue'] . "</b>.  This fax has been transferred to an EOD by " . $_POST['employee'] . " on " . $datewtime;

$sql3 = "INSERT INTO ucbdb_contact_crm (contact_id, comm_type, message, message_date, employee) VALUES ( '" . $_POST['contact_id'] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_POST['employee'] . "')";
$result3 = db_query($sql3);

$sql3ud = "UPDATE ucb_contact SET status = 'OK' WHERE id = $contact_id";
$result3ud = db_query($sql3ud);


echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: contact_status_drill.php?id=' . encrypt_url($_POST['orders_id']) . '&proc=View');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"contact_status_drill.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=contact_status_drill.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View\" />";
        echo "</noscript>";
        exit;
} //==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');
