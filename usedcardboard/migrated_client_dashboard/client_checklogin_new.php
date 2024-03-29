<?php
/*
File Name: client_checklogin_new.php
Page created By: Amarendra Singh
Page created On: 17-Feb-2022
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date           By            Description
======================================================================================================
17-Feb-2022		Amarendra		File to check the login crediantials in client_dash folder. The username is 
								now change to email. 
======================================================================================================
*/

require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");

ob_start();

ini_set("display_errors", "1");
error_reporting(E_ALL);

// username and password sent from form here the username is email after change. 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

db();
$time_diff_val_indetail = "";
//and user_block_time > NOW() - INTERVAL 10 MINUTE)
$sql="Select user_block_time from clientdashboard_usermaster WHERE client_email=? and activate_deactivate = 1 and user_block = 1";
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
		$sql_upd = "Update clientdashboard_usermaster set user_block=0, user_block_time=NULL WHERE client_email=? and activate_deactivate = 1";
		//echo $sql_upd . "<br>";
		$result_upd = db_query($sql_upd, array("s"), array($myusername));
	}
}

$rec_found = "no"; $email_found = "no"; $first_time_loging_flg = 0;
$sql="SELECT * FROM clientdashboard_usermaster WHERE client_email=? and activate_deactivate = 1 and user_block = 0";
$result=db_query($sql, array("s"), array($myusername));

$reccnt = 0;
$companyid = 0; 
$loginid = 0;

while ($rq = array_shift($result)) {
	$email_found = "yes";
	if ($mypassword == $rq["password"]){
		$rec_found = "yes";
		$companyid = $rq["companyid"];
		$loginid = $rq["loginid"];
		$reccnt = $reccnt + 1;
	}
}

if ($rec_found == "yes") {
	
	$sql = "UPDATE clientdashboard_user_log_attempt SET attempt = 1 WHERE user_name = '".$myusername."'";
	$result = db_query($sql );

	$sql = "Insert into clientdashboard_user_log (userid, login_datetime, ipaddress, client_device_info) values( $loginid, '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '" . str_replace("'", "\'" , $_SERVER['HTTP_USER_AGENT']) . "')";
	$result = db_query($sql );

	$date_of_expiry = time() + 6000 ;
	setcookie("client_dash_companyid", $companyid, $date_of_expiry);
	setcookie("loginid", $loginid, $date_of_expiry );
	
	if ($reccnt > 1){
	?>
		<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" 
		style="BORDER-RIGHT: gray 1px solid;BORDER-TOP: gray 1px solid; BORDER-LEFT: gray 1px solid;BORDER-BOTTOM: gray 1px solid;">

			<tr>
				<td>
					<form name="form1" method="post" action="client_dashboard.php">
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
							<tr>
								<td colspan="5" align="center" ><img src="images/ucb-logo.jpg" style="margin-top:8px; margin-bottom:10px;" /></td>
							</tr>
							<tr>
								<td colspan="5" align="center" ><span class="TBL_COL_HDR_NOSHADE2">Select Company from list</span></td>
							</tr>
							<tr>
								<td colspan="5" align="center" style="height:10px;" ></td>
							</tr>

							<tr>
								<td width="50">&nbsp;</td>
								<td width="50"><span class="TBL_COL_HDR_NOSHADE">Company</span></td>
								<td width="6"><span style="font-weight:bold; color:black;">:</span></td>
								<td width="140">
									<?php
										$mgarray = array();
										$sql = "Select companyid from clientdashboard_usermaster where client_email='$myusername' and password='$mypassword' and activate_deactivate = 1";
										$result = db_query($sql);
										while ($rq = array_shift($result)) {
											$nickname = get_nickname_val('', $rq["companyid"]);
											$mgarray[] = array('companyid' => $rq["companyid"], 'nickname' => $nickname);
										}
										
										$MGArraysort_I = array();
										foreach ($mgarray as $MGArraytmp) {
											$MGArraysort_I[] = $MGArraytmp['nickname'];
											
										}
										array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$mgarray); 
										
									?>
									<select id="companyid_login" name="companyid_login">
										<?php
											foreach ($mgarray as $MGArraytmp) {
												echo "<option value='" . $MGArraytmp["companyid"] . "'>" . $MGArraytmp["nickname"] . "</option>";
											}
										?>	
									</select>
									<input type="hidden" name="hdmultiple_acc_flg" id="hdmultiple_acc_flg" value="<?php echo $reccnt;?>">
								</td>
								<td width="60">&nbsp;</td>
							</tr>
							<tr>
								<td width="50">&nbsp;</td>
								<td width="50"></td>
								<td width="6"></td>
								<td width="140" align="right"><input type="submit" class="BUTTON1" name="Submit" value="Submit"></td>
								<td width="60">&nbsp;</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>
	<?php	
	}else{
		//redirect("https://boomerang.usedcardboardboxes.com/client_dashboard.php?compnewid=". urlencode(encrypt_password($companyid)));
		redirect("client_dashboard.php?compnewid=". urlencode(encrypt_password($companyid)));
	}	
	
	
		
}else { // if record found else start
	
	$user_found = "no"; $user_block_time = "";
	$sql = "SELECT * FROM clientdashboard_usermaster WHERE client_email='$myusername' AND activate_deactivate = 1";
	$result = db_query($sql );
	while ($rq = array_shift($result)) {
		$user_found = "yes";
		$user_block = $rq["user_block"];
		$user_block_time = $rq["user_block_time"];
	}
	
	if ($user_found == "yes"){
		
		$resAttemptDt = db_query("SELECT attempt FROM clientdashboard_user_log_attempt WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ");
		$rowAttemptDt = array_shift($resAttemptDt);
		if(!empty($rowAttemptDt)){
			$attempt = $rowAttemptDt['attempt'] + 1;
			db_query("UPDATE clientdashboard_user_log_attempt SET attempt = '".$attempt."' WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ");
		}else{
			$attempt = 1;
			$sql = "INSERT INTO clientdashboard_user_log_attempt (user_name, password, login_datetime, ipaddress, attempt) values( '".$myusername."', '".$mypassword."', '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '".$attempt."')";
			$result = db_query($sql );
		}
		
		$time_fin = new DateTime();
		$interval2 = new DateInterval('PT11M');
		$time_fin->add($interval2);		
		$time_diff_val_indetail = $time_fin->format("H:i:s");
		
		if($user_block == 0){
			
			if($attempt >= 3){
				db_query("UPDATE clientdashboard_usermaster SET user_block = 1, user_block_time  = '".date("Y-m-d H:i:s") ."' WHERE client_email = '".$myusername."' ");
			}
			$attempt = 3 - $attempt;
			
			$encryptUserName = $myusername;
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"index.php?action=no&clientdashboard_user_log_attempt=$attempt&diff_in_time=$time_diff_val_indetail&name=$encryptUserName\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no&clientdashboard_user_log_attempt=$attempt&diff_in_time=$time_diff_val_indetail&name=$encryptUserName\" />";
			echo "</noscript>"; exit;
			
		}else{
						
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"index.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&name=$myusername" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no&user_block=yes&diff_in_time=$time_diff_val_indetail&name=$myusername" . "\" />";
			echo "</noscript>"; exit;

		}
	}else{

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php?action=no\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no\" />";
		echo "</noscript>"; exit;		
	}
}
?>
