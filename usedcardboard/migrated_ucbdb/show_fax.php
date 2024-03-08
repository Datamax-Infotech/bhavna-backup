<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
	
ini_set('display_errors', 1); // set to 0 for production version 
error_reporting(E_ALL); 

$sql = "SELECT AttachmentData FROM Attachments WHERE AttachmentName = '$_GET[file]'";
// $sql = "SELECT AttachmentData FROM Attachments WHERE ID = 2";
// $sql = "SELECT AttachmentData FROM Attachments WHERE ID = $_GET[id]";
$result = db_query($sql,db() );		
while ($myrowsel = array_shift($result)) {

header("Content-Type: application/pdf");
echo $myrowsel['AttachmentData'];
exit();
}

?>