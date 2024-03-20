<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$sql2 = "DELETE FROM " . decrypt_url($_GET["table_name"]) . " WHERE id = " . decrypt_url($_GET["id"]);
$result2 = db_query($sql2);
echo  $_SERVER['HTTP_REFERER'];
redirect($_SERVER['HTTP_REFERER']);
