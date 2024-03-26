<?php

$thispage	= "warehouse_list_update_priority.php"; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
$addslash = "yes";
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$prank = $_REQUEST['prank'];
$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
$sql = "UPDATE ucbdb_warehouse SET rank = rank + 1 WHERE rank >= $prank";
db_query($sql);
$sql = "UPDATE ucbdb_warehouse SET rank=$prank WHERE id = $id";
db_query($sql);
$prank = 0;
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: warehouse_list.php?posting=yes');
