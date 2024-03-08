<?php
/*
File Name: checklogin.php
Page created By: Amarendra Singh
Page created On: 16-July-2023
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date        	By            Description
======================================================================================================
16-July-2023	Amarendra		File to check the login crediantials in ucbdb folder. 
======================================================================================================
*/
ini_set("display_errors", "1");
error_reporting(E_ALL);
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

ob_start();

// username and password sent from form here the username is email after change. 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 
// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

$tbl_name="ucbdb_employees";

db();
$time_diff_val_indetail = "";
//and user_block_time > NOW() - INTERVAL 10 MINUTE)
$sql="Select user_block_time from $tbl_name WHERE email=? and status = 'Active' and user_block = 1";
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
	
	if ($time_diff_val > 10)
	{	
		$sql_upd = "Update $tbl_name set user_block=0, user_block_time=NULL WHERE email=? and status = 'Active'";
		//echo $sql_upd . "<br>";
		$result_upd = db_query($sql_upd, array("s"), array($myusername));
	}
}

$rec_found = "no"; $email_found = "no"; $first_time_loging_flg = 0;
$sql="SELECT * FROM $tbl_name WHERE email=? and status = 'Active' and user_block = 0";
$result=db_query($sql, array("s"), array($myusername));
while ($rq = array_shift($result)) {
	$email_found = "yes";
	if ($mypassword == $rq["password"]){
		//echo "Rec found <br>";		
		$rec_found = "yes";
		$username = $rq["name"];
		$employee = $rq["initials"];
		$employeeid = $rq["id"];
		//$b2b_id = $rq["b2b_id"];
		//$views = $rq["views"];
		$ublock = $rq["user_block"];
		$first_time_loging_flg = $rq["first_time_loging_flg"];
		break;
	}
}

if ($rec_found == "yes") {
	
	$sql = "Update ucbdb_employees_log_attempt set attempt = 0 WHERE user_name = '".$myusername."'";
	$result = db_query($sql , db());
	if($ublock == 1){
		$q1 = "UPDATE $tbl_name SET user_block=0, user_block_time=NULL WHERE id=$employeeid";
		db_query($q1, db());
	}
	$_SESSION['employee'] = $employee;
	$date_of_expiry = time() + 600000 ;
	
	setcookie( "userloggedin", $myusername, $date_of_expiry );
	setcookie( "userinitials", $employee, $date_of_expiry );
	
	
	
	if ($first_time_loging_flg == 1){
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"change_password.php?firsttime_flg=1" . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=change_password.php?firsttime_flg=1". "\" />";
		echo "</noscript>"; exit;
	}

	
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php" . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php". "\" />";
		echo "</noscript>"; exit;
}else {
	
	$user_found = "no"; $user_block_time = "";
	$sql = "SELECT * FROM $tbl_name WHERE email='$myusername' AND status = 'Active' ";
	$result = db_query($sql , db());
	while ($rq = array_shift($result)) {
		$user_found = "yes";
		$user_block = $rq["user_block"];
		$user_block_time = $rq["user_block_time"];
	}
	
	if ($user_found == "yes"){
		
		$resAttemptDt = db_query("SELECT attempt FROM ucbdb_employees_log_attempt WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		$rowAttemptDt = array_shift($resAttemptDt);
		if(!empty($rowAttemptDt)){
			$attempt = $rowAttemptDt['attempt'] + 1;
			db_query("UPDATE ucbdb_employees_log_attempt SET attempt = '".$attempt."' WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		}else{
			$attempt = 1;
			$sql = "INSERT INTO ucbdb_employees_log_attempt (user_name, password, login_datetime, ipaddress, attempt) values( '".$myusername."', '".$mypassword."', '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '".$attempt."')";
			$result = db_query($sql , db());
		}
		
		if($user_block == 0){
			
			if($attempt >= 3){
				db_query("UPDATE $tbl_name SET user_block = 1, user_block_time  = '".date("Y-m-d H:i:s") ."' WHERE email = '".$myusername."' ", db());
			}
			$attempt = 3 - $attempt;
			
			$time_fin = new DateTime();
			$interval2 = new DateInterval('PT11M');
			$time_fin->add($interval2);
			
			$time_diff_val_indetail = $time_fin->format("H:i:s");
			
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"login.php?action=no&user_attempt=$attempt&diff_in_time=$time_diff_val_indetail&user_email=$myusername" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=login.php?action=no&user_attempt=$attempt&diff_in_time=$time_diff_val_indetail&user_email=$myusername" . "\" />";
			echo "</noscript>"; exit;
		
		}else{
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"login.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&user_email=$myusername" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=login.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&user_email=$myusername" . "\" />";
			echo "</noscript>"; exit;

		}
	}else{

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"login.php?user_email=" . $myusername . "&action=no" . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=login.php?user_email=" . $myusername . "&action=no" . "\" />";
		echo "</noscript>"; exit;		
	}
}
?>
