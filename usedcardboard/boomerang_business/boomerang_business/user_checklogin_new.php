<?php
/*
File Name: user_checklogin_new.php
Page created By: Bhavna Patidar
Page created On: 17-Feb-2022
Last Modified On: 
Last Modified By: Bhavna Patidar
Change History:
Date           By            Description
======================================================================================================
17-Feb-2022		Bhavna		File to check the login crediantials in client_dash folder. The username is 
								now change to email. 
======================================================================================================
*/
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

//ob_start();

ini_set("display_errors", "1");
error_reporting(E_ALL);

// username and password sent from form here the username is email after change. 
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

db();
$time_diff_val_indetail = "";
//and user_block_time > NOW() - INTERVAL 10 MINUTE)
$sql = "Select user_block_time from boomerang_usermaster WHERE user_email=? and activate_deactivate = 1 and user_block = 1";
$result = db_query($sql, array("s"), array($myusername));
while ($rq = array_shift($result)) {
	$time1 = new DateTime($rq["user_block_time"]);
	$time2 = new DateTime(date('Y-m-d h:i:s'));
	$interval = $time1->diff($time2);
	$time_diff_val = $interval->i;

	$time_fin = new DateTime($rq["user_block_time"]);
	$interval2 = new DateInterval('PT11M');
	$time_fin->add($interval2);

	$time_diff_val_indetail = $time_fin->format("H:i:s");

	//echo $rq["user_block_time"] . " " . date('Y-m-d h:i:s');
	//echo $time_diff_val;

	if ($time_diff_val > 10) {
		$sql_upd = "Update boomerang_usermaster set user_block=0, user_block_time=NULL WHERE user_email=? and activate_deactivate = 1";
		//echo $sql_upd . "<br>";
		$result_upd = db_query($sql_upd, array("s"), array($myusername));
	}
}

$rec_found = "no";
$email_found = "no";
$first_time_loging_flg = 0;
$sql = "SELECT loginid,password FROM boomerang_usermaster WHERE user_email=? and activate_deactivate = 1 and user_block = 0";
$result = db_query($sql, array("s"), array($myusername));

$reccnt = 0;
$loginid = 0;

while ($rq = array_shift($result)) {
	$email_found = "yes";
	if ($mypassword == base64_decode($rq["password"])) {
		$rec_found = "yes";
		$loginid = $rq["loginid"];
		$reccnt = $reccnt + 1;
	}
}
if ($rec_found == "yes") {

	$sql = "UPDATE boomerang_user_log_attempt SET attempt = 1 WHERE user_name = '" . $myusername . "'";
	$result = db_query($sql, db());

	$sql = "Insert into boomerang_user_log (userid, login_datetime, ipaddress, client_device_info) values( $loginid, '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '" . str_replace("'", "\'", $_SERVER['HTTP_USER_AGENT']) . "')";
	$result = db_query($sql);

	$date_of_expiry = time() + 6000;
	setcookie("loginid", $loginid, $date_of_expiry);
	redirect("home.php");
} else { // if record found else start

	$user_found = "no";
	$user_block_time = "";
	$sql = "SELECT * FROM boomerang_usermaster WHERE user_email='$myusername' AND activate_deactivate = 1";
	$result = db_query($sql, db());
	while ($rq = array_shift($result)) {
		$user_found = "yes";
		$user_block = $rq["user_block"];
		$user_block_time = $rq["user_block_time"];
	}

	if ($user_found == "yes") {

		$resAttemptDt = db_query("SELECT attempt FROM boomerang_user_log_attempt WHERE user_name = '" . $myusername . "' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		$rowAttemptDt = array_shift($resAttemptDt);
		if (!empty($rowAttemptDt)) {
			$attempt = $rowAttemptDt['attempt'] + 1;
			db_query("UPDATE boomerang_user_log_attempt SET attempt = '" . $attempt . "' WHERE user_name = '" . $myusername . "' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		} else {
			$attempt = 1;
			$sql = "INSERT INTO boomerang_user_log_attempt (user_name, password, login_datetime, ipaddress, attempt) values( '" . $myusername . "', '" . base64_encode($mypassword) . "', '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '" . $attempt . "')";
			$result = db_query($sql, db());
		}

		$time_fin = new DateTime();
		$interval2 = new DateInterval('PT11M');
		$time_fin->add($interval2);
		$time_diff_val_indetail = $time_fin->format("H:i:s");

		if ($user_block == 0) {

			if ($attempt >= 3) {
				db_query("UPDATE boomerang_usermaster SET user_block = 1, user_block_time  = '" . date("Y-m-d H:i:s") . "' WHERE user_email = '" . $myusername . "' ", db());
			}
			$attempt = 3 - $attempt;

			$encryptUserName = $myusername;
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"index.php?action=no&boomerang_user_log_attempt=$attempt&diff_in_time=$time_diff_val_indetail&name=$encryptUserName\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no&boomerang_user_log_attempt=$attempt&diff_in_time=$time_diff_val_indetail&name=$encryptUserName\" />";
			echo "</noscript>";
			exit;
		} else {

			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"index.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&name=$myusername" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&name=$myusername" . "\" />";
			echo "</noscript>";
			exit;
		}
	} else {

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php?action=no\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no\" />";
		echo "</noscript>";
		exit;
	}
}
