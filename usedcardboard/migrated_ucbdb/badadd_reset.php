<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$today = date("Ymd");
$number = $_GET['number'];
$sql3 = "UPDATE orders_missing_address SET mailflag = 0, number = " . $number . ", time = " . $today;
$result3 = db_query($sql3);
echo "<DIV CLASS='SQL_RESULTS'>Bad Address Updated<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: index.php');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"index.php\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
        echo "</noscript>";
        exit;
} //==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');
