<?php
error_reporting(E_WARNING | E_PARSE);
$thispage	= "reason_list_update_priority.php"; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
$addslash = "yes";
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
$id = decrypt_url($_REQUEST['id']);
$prank = $_REQUEST['prank'];
db();
$sql = "UPDATE ucbdb_reason_code SET rank = rank + 1 WHERE rank >= $prank";
db_query($sql);
$sql = "UPDATE ucbdb_reason_code SET rank=$prank WHERE id = $id";
db_query($sql);
$prank = 0;
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: reason_list.php?posting=yes');
