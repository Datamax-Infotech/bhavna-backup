<?php
$thispage	= "item_list_update_priority.php"; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
$addslash = "yes";
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$id = decrypt_url($_REQUEST['id']);
$prank = $_REQUEST['rank'];
$sql = "UPDATE ucbdb_items SET rank = rank + 1 WHERE rank >= $prank";
db_query($sql);
$sql = "UPDATE ucbdb_items SET rank=$prank WHERE id = $id";
db_query($sql);
$prank = 0;
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: item_list.php?posting=yes');
