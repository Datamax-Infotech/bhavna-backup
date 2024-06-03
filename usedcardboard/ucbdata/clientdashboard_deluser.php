<?php
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

if($_REQUEST['useraction_flg'] == 1){
	$strQuery = "DELETE FROM clientdashboard_usermaster WHERE loginid = " . $_REQUEST["loginid"];
	$result = db_query($strQuery, db());
}

if($_REQUEST['useraction_flg'] == 2){
	$res1 = db_query("SELECT user_name FROM clientdashboard_usermaster WHERE loginid = " . $_REQUEST["loginid"], db()); 
	while($fetch_data1=array_shift($res1)) {
		//echo $fetch_data1["loginid"]."<br>";
		$strQuery = "DELETE FROM clientdashboard_usermaster WHERE user_name = '" . $fetch_data1["user_name"] . "'";
		$result = db_query($strQuery, db());
	}
}

	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"clientdashboard_setup.php?ID=". $_REQUEST["companyid"] . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=".$_REQUEST["companyid"] . "\" />";
	echo "</noscript>"; exit;

?>


