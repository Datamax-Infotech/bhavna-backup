<?php
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
$currDate = date('Y-m-d H:i:s');
db();
$resUserDt = db_query("SELECT loginid, user_block_time, user_name FROM clientdashboard_usermaster WHERE user_block = 1 and activate_deactivate = 1");
//echo "<pre>"; print_r($resUserDt); echo "</pre>";
while ($rowsUserDt = array_shift($resUserDt)) {
	$start_date = new DateTime($rowsUserDt['user_block_time']);
	$since_start = $start_date->diff(new DateTime($currDate));
	echo $since_start->i . ' minutes<br>';

	if ($since_start->i > '15') {

		//echo "<br />"."UPDATE clientdashboard_usermaster SET user_block = 0, user_block_time = NULL WHERE  loginid ='".$rowsUserDt['loginid']."'";
		db_query("UPDATE clientdashboard_usermaster SET user_block = 0, user_block_time = NULL WHERE  loginid ='" . $rowsUserDt['loginid'] . "'");


		//echo "<br />"."DELETE FROM clientdashboard_user_log_attempt WHERE user_name = '".$rowsUserDt['user_name']."' "."<br />";
		db_query("DELETE FROM clientdashboard_user_log_attempt WHERE user_name = '" . $rowsUserDt['user_name'] . "' ");
	}
}
