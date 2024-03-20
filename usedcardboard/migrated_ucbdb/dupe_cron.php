<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$dupqry = "SELECT * FROM (SELECT count( tracking_number ) AS total, orders_id FROM orders_active_export  GROUP BY tracking_number ORDER BY `total` DESC) as A WHERE A.total > 1 ORDER BY orders_id DESC";
$dupqryres = db_query($dupqry);
$dupqryresnum = tep_db_num_rows($dupqryres);
$dupqrycurcount = "SELECT DISTINCT number, mailflag FROM orders_active_dupes";
$dupqrycurcountres = db_query($dupqrycurcount);
$dupqrycurcountrescount = 0;
$dupqrycurcountrescountflag = 0;
while ($dupqrycurcountrescnt = array_shift($dupqrycurcountres)) {
    $dupqrycurcountrescount = $dupqrycurcountrescnt["number"];
    $dupqrycurcountrescountflag = $dupqrycurcountrescnt["mailflag"];
}

$value = $dupqryresnum - $dupqrycurcountrescount;
if (($value != 0) && ($dupqrycurcountrescountflag != 1)) {
    $sql32 = "UPDATE orders_active_dupes SET mailflag = 2";
    $result32 = db_query($sql32);

    header('Location: http://b2c.usedcardboardboxes.com/dupe_cron_mail.php?number=' . $value);
    exit;
}
