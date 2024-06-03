<?php
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

if ((isset($_REQUEST["existing"])) && ($_REQUEST["existing"] = "new")) {
	//
	
	//
	$res1 = db_query("SELECT * FROM clientdashboard_usermaster WHERE user_name='".$_REQUEST["clientdash_username"]."'"  , db()); 
	while($fetch_data1=array_shift($res1)) {
		$newpassword = $fetch_data1["password"];
	}	
	//
	$strQuery = "Insert into clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) values (" . $_REQUEST["hidden_companyid"] . ", '";
		$strQuery .= $_REQUEST["clientdash_username"] . "', '" . $newpassword . "', '" . $_REQUEST["clientdash_username"] . "', 1)";
	$result = db_query($strQuery, db());
	//
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
	echo "</noscript>"; exit;
}
else{

	$res = db_query("Select user_name from clientdashboard_usermaster where user_name = '" . $_REQUEST["clientdash_username"]. "'"  , db()); 
	$rec_found = "no";
	while($fetch_data=array_shift($res))
	{
		$rec_found = "yes";

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&usrnm=" . $_REQUEST["clientdash_username"] . "&duprec=yes\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "&usrnm=" . $_REQUEST["clientdash_username"] . "&duprec=yes\" />";
		echo "</noscript>"; exit;
	}
	if ($rec_found = "no") {
		$strQuery = "Insert into clientdashboard_usermaster (companyid, user_name, password, client_email, activate_deactivate) values (" . $_REQUEST["hidden_companyid"] . ", '";
		$strQuery .= $_REQUEST["clientdash_username"] . "', '" . $_REQUEST["clientdash_pwd"] . "', '" . $_REQUEST["clientdash_username"] . "', 1)";

		$result = db_query($strQuery, db());
		
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=clientdashboard_setup.php?ID=" . $_REQUEST["hidden_companyid"] . "\" />";
		echo "</noscript>"; exit;
	}

}

?>