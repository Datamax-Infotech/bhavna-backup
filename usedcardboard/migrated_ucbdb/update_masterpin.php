<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

if (isset($_REQUEST["txtmaterpin"]))
{
	$dt_view_qry = "UPDATE tblvariable SET variablevalue = " . $_REQUEST["txtmaterpin"] . " WHERE variablename='master_pin'";
	$dt_view_res = db_query($dt_view_qry, db());
}

	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>"; exit;

?>



