<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

if (isset($_REQUEST["fromeml"]))
{
	$dt_view_qry = "UPDATE email_config SET from_email = '" . $_REQUEST["fromeml"] . "', to_email = '" . $_REQUEST["toeml"] . "', cc_email = '" . $_REQUEST["ccmeml"] . "' WHERE unqid= " . $_REQUEST["emailgrp"];
	$dt_view_res = db_query($dt_view_qry, db());
}

	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>"; exit;

?>




