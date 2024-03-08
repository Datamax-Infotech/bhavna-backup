<?php  
require ("inc/header_session.php");
?>

<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

$sql2 = "DELETE FROM " . $_GET["table_name"] . " WHERE id = " . $_GET["id"];
$result2 = db_query($sql2,db() );
echo  $_SERVER['HTTP_REFERER'];
redirect($_SERVER['HTTP_REFERER']);
?>