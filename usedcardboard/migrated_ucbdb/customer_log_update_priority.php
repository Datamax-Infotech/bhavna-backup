<?php
$thispage	= "customer_log_update_priority.php"; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$prank = $_REQUEST['prank'];
$id = $_REQUEST['id'];
$sql = "UPDATE ucbdb_customer_log_config SET rank = rank + 1 WHERE rank >= $prank";
db_query($sql);
$sql = "UPDATE ucbdb_customer_log_config SET rank=$prank WHERE id = $id";
db_query($sql);
$prank = 0;
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: customer_log.php?posting=yes');
