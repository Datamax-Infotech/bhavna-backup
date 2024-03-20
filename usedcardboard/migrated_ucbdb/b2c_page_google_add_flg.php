<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
db();
if ($_REQUEST["flg"] == 'off')
{
	$dt_view_qry = "UPDATE tblvariable SET variablevalue = 'off' WHERE variablename='b2c_page_google_add_flg'";
	$dt_view_res = db_query($dt_view_qry);
}else{
	$dt_view_qry = "UPDATE tblvariable SET variablevalue = 'on' WHERE variablename='b2c_page_google_add_flg'";
	$dt_view_res = db_query($dt_view_qry);
}
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>"; exit;

?>