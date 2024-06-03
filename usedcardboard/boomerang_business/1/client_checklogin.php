<? 
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

db();

$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

$sql = "SELECT * FROM clientdashboard_usermaster WHERE user_name='$myusername' AND BINARY password='$mypassword' AND activate_deactivate = 1";
$result = db_query($sql);

$reccnt = 0;
$companyid = 0; 
$loginid = 0;

while ($rq = array_shift($result)) {
	$companyid = $rq["companyid"];
	$loginid = $rq["loginid"];
	$reccnt = $reccnt + 1;
}

if($reccnt >= 1) {
	$sql = "UPDATE clientdashboard_user_log_attempt SET attempt = 1 WHERE user_name = '".$myusername."'";
	$result = db_query($sql , db());

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
									<?
										$mgarray = array();
										$sql = "Select companyid from clientdashboard_usermaster where user_name='$myusername' and password='$mypassword' and activate_deactivate = 1";
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
										<?
											foreach ($mgarray as $MGArraytmp) {
												echo "<option value='" . $MGArraytmp["companyid"] . "'>" . $MGArraytmp["nickname"] . "</option>";
											}
										?>	
									</select>
									<input type="hidden" name="hdmultiple_acc_flg" id="hdmultiple_acc_flg" value="<? echo $reccnt;?>">
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
	<?	
	}else{
		redirect("https://boomerang.usedcardboardboxes.com/client_dashboard.php?compnewid=". urlencode(encrypt_password($companyid)));
	}	
	
	/*
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"client_dashboard.php?compnewid=". $companyid . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=client_dashboard.php?compnewid=". $companyid . "\" />";
	echo "</noscript>"; exit;
	*/
}
else {

	$user_nm_exist = "no";
	$sql = "SELECT * FROM clientdashboard_usermaster WHERE user_name='$myusername' AND activate_deactivate = 1 AND user_block = 0";
	$result = db_query($sql , db());
	while ($rq = array_shift($result)) {
		$user_nm_exist = "yes";
	}
	
	if ($user_nm_exist == "yes"){
		$resAttemptDt = db_query("SELECT attempt FROM clientdashboard_user_log_attempt WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		$rowAttemptDt = array_shift($resAttemptDt);
		if(!empty($rowAttemptDt)){
			$attempt = $rowAttemptDt['attempt'] + 1;
			db_query("UPDATE clientdashboard_user_log_attempt SET attempt = '".$attempt."' WHERE user_name = '".$myusername."' AND login_datetime = '" . date("Y-m-d") . "' ", db());
		}else{
			$attempt = 1;
			$sql = "INSERT INTO clientdashboard_user_log_attempt (user_name, password, login_datetime, ipaddress, attempt) values( '".$myusername."', '".$mypassword."', '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '".$attempt."')";
			$result = db_query($sql , db());
		}
		
		if($attempt > 3){
			db_query("UPDATE clientdashboard_usermaster SET user_block = 1, user_block_time  = '".date("Y-m-d H:i:s") ."' WHERE user_name = '".$myusername."' ", db());
		}
		$attempt = 3 - $attempt;
		
		//$encryptUserName = encrypt_password($myusername);
		$encryptUserName = $myusername;
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php?action=no&clientdashboard_user_log_attempt=$attempt&name=$encryptUserName\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php?action=no&clientdashboard_user_log_attempt=$attempt&name=$encryptUserName\" />";
		echo "</noscript>"; exit;
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
