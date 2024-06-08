<?
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

if (isset($_REQUEST["param1"])) {
	if ($_REQUEST["param1"] != "") {
		$sql="Select companyid, loginid from clientdashboard_usermaster where loginid = '" . $_REQUEST["param1"] ."'";
		$result = db_query($sql, db());
		$rec_found = "no"; $companyid = "";
		while ($rq = array_shift($result)) {
			$rec_found = "yes";
			$companyid = $rq["companyid"];
			$loginid = $rq["loginid"];
		}
		
		if ($rec_found == "yes"){
			$date_of_expiry = time() + 6100 ;
			setcookie("client_dash_companyid", $companyid, $date_of_expiry);
			setcookie("loginid", $loginid, $date_of_expiry);
			
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"client_dashboard.php?compnewid=" . urlencode(encrypt_password($companyid)) . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=client_dashboard.php?compnewid=" . urlencode(encrypt_password($companyid)) . "\" />";
			echo "</noscript>"; exit;

			//redirect("https://boomerang.usedcardboardboxes.com/client_dashboard.php?compnewid=". urlencode(encrypt_password($companyid)));
		}
			
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>UsedCardboardBoxes B2B Customer Portal - Login</title>

<?php
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>	

	<script language="javascript">
		function chkchgpwd(){
			if (document.getElementById("emailid").value == "")
			{
				alert("Please enter the Email address.");
				return false;
			}
			
			document.frmforgotpwdfrm.submit();
		}
		
		function callmailto(){
			var ele = document.getElementById("btnmailto");
			ele.click();
		}
		
		function chkLoginFrm(){
			var myusername = document.getElementById("myusername");
			var mypassword = document.getElementById("mypassword");
			if ( myusername.value == "") {
				alert("Please enter the User ID.");
				myusername.focus();
				return false;
			}else if (mypassword.value == "") {
				alert("Please enter Password.");
				mypassword.focus();
				return false;
			}else{
				document.form1.submit();
			}			
		}
		
		
	</script>
</head>

<body>


<?

$block_user = "no";
db();

//$sql = "Insert into clientdashboard_user_log (userid, login_datetime, ipaddress, client_device_info) values( 0, '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '" . str_replace("'", "\'" , $_SERVER['HTTP_USER_AGENT']) . "')";
//$result = db_query($sql );

$email_send ="no";
if (isset($_REQUEST["hd_forgotpwd_upd"])) {
	if ($_REQUEST["hd_forgotpwd_upd"] == "yes") {

		$sql="Select * from clientdashboard_usermaster where client_email = '" . $_REQUEST["emailid"] ."'";
		$result = db_query($sql);
		$rec_found = "no"; $tempstr = "";
		while ($rq = array_shift($result)) {
			if ($rq["client_email"] != "") {
				$rec_found = "yes";
				$tempstr .= "User name: " . $rq["user_name"] . "<br/>Password: " . $rq["password"] . "<br/><br/>";
			}
		}
		
		if ($rec_found == "no") 
		{?>
			<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
				<tr>
					<td>
						<form name="form1" method="post" action="client_checklogin.php">
							<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
								<tr>
									<td colspan="2" align="center" ><span class="TBL_COL_HDR_NOSHADE">Forgot Password</span></td>
								</tr>
								<tr><td colspan="2"><hr></td></tr>
								<tr>
									<td colspan="2"><span class="TBL_COL_HDR_NOSHADE"><font color=red>Entered email address not found in our database. Please check. </font><br/></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>			
			
		<? exit;}else {
			$email_send ="yes";
			//$emlstatus = sendemail_attachment(null, "", $_REQUEST["emailid"], "", "", "admin@usedcardboardboxes.com", "Admin UCB","", "UCB Dashboard - password details" , "UCB Dashboard login details are as follows:<br/><br/>" . $tempstr . "<br/><br/>UCB Dashboard : <a href='http://boomerang.usedcardboardboxes.com'>boomerang.usedcardboardboxes.com</a>");
			$emlstatus = sendemail_php_function(null, '', $_REQUEST["emailid"], "", "", "ucbemail@usedcardboardboxes.com", "UCB Admin Team", "admin@UsedCardboardBoxes.com", "UCB Dashboard - password details", "UCB Dashboard login details are as follows:<br/><br/>" . $tempstr . "<br/><br/>UCB Dashboard : <a href='http://boomerang.usedcardboardboxes.com'>boomerang.usedcardboardboxes.com</a>"); 
		}
	}
}

?>

<?
if (isset($_REQUEST["hd_forgot_pwd"])) {
	if ($_REQUEST["hd_forgot_pwd"] == "yes") {?>
		
	<form name="frmforgotpwdfrm" action="index.php" method="Post" >
		<input type="hidden" name="hd_forgotpwd_upd" id="hd_forgotpwd_upd" value="yes"/>
		<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
			<tr>
				<td>
					<form name="form1" method="post" action="client_checklogin.php">
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
							<tr>
								<td colspan="2" align="center" ><span class="TBL_COL_HDR_NOSHADE">Forgot Password</span></td>
							</tr>
							<tr><td colspan="2"><hr></td></tr>
							<tr>
								<td colspan="2"><span class="TBL_COL_HDR_NOSHADE">Please enter the email address. <br/><br/><font color=red>We will check the email address in our database and if found then we will send the login details in the email address.</font></span></td>
							</tr>
							<tr>
								<td ><span class="TBL_COL_HDR_NOSHADE">Email address:</span></td>
								<td width="200"><input type="text" name="emailid" id="emailid" size="30"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type="button" class="button" name="btn_forgotpwd" onclick="chkchgpwd()" value="Send Email"></td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>	
	</form> 
	
	<? exit;}
	
}
?>

	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" 
	style="BORDER-RIGHT: gray 1px solid;BORDER-TOP: gray 1px solid; BORDER-LEFT: gray 1px solid;BORDER-BOTTOM: gray 1px solid;">

		<tr>
			<td>
				<form name="form1" method="post" action="client_checklogin.php">
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
							<td colspan="5" align="center" ><img src="images/ucb-logo.jpg" style="margin-top:8px; margin-bottom:10px;" /></td>
						</tr>
						<tr>
							<td colspan="5" align="center" ><span class="TBL_COL_HDR_NOSHADE2">Log on to UCB Client Portal</span></td>
						</tr>
						<tr>
							<td colspan="5" align="center" style="height:10px;" ></td>
						</tr>
						<?php 
						if ($_REQUEST["action"] =="no") { 
							//echo "<tr><td align=\"center\" colspan=\"5\"><span style='color:red;' class=\"TBL_COL_HDR_NOSHADE\">Incorrect User name or Password</span></td></tr>"; 
							$user_log_attempt = "";
							$resAttemptDt = db_query("SELECT attempt FROM clientdashboard_user_log_attempt WHERE user_name = '".$_REQUEST["name"] ."' AND login_datetime = '" . date("Y-m-d") . "' ", db());
							while ($rowAttemptDt = array_shift($resAttemptDt)){
								$user_log_attempt = $rowAttemptDt["attempt"];
								if ($user_log_attempt <= 3) { 
									$user_log_attempt = 3 - $user_log_attempt;
								}else { $user_log_attempt = 0; }	
							}
							
							echo "<tr><td align='center' colspan='5'><span style='color:red;' class='TBL_COL_HDR_NOSHADE'>Incorrect User name or Password. "; 
							if ($user_log_attempt != "") { 
								echo "You are left with " . $_REQUEST["clientdashboard_user_log_attempt"] . " attempt."; 
							}	
							elseif ($user_log_attempt == 0) { 
								$block_user = "yes";
								echo "You are left with no more attempts, try after 20 mins."; 
							}	
							echo "</span></td></tr>"; 
						}
						
						if ($block_user == "no") {
						?>
							<tr>
								<td width="50">&nbsp;</td>
								<td width="50"><span class="TBL_COL_HDR_NOSHADE">Email</span></td>
								<td width="6"><span style="font-weight:bold; color:black;">:</span></td>
								<td width="140"><input name="myusername" type="text" id="myusername" size="19"></td>
								<td width="60">&nbsp;</td>
							</tr>
							<tr>
								<td >&nbsp;</td>
								<td><span class="TBL_COL_HDR_NOSHADE">Password</span></td>
								<td><span style="font-weight:bold;">:</span></td>
								<td><input name="mypassword" type="password" id="mypassword" size="19"></td>
								<td >&nbsp;</td>
							</tr>
							<tr>
								<td width="50">&nbsp;</td>
								<td width="50"></td>
								<td width="6"></td>
								<td width="140" align="right"><input type="submit" class="BUTTON1" name="Submit" value="Submit" onclick="return chkLoginFrm()"></td>
								<td width="60">&nbsp;</td>
							</tr>
						<? }?>	
					</table>
				</form>
			</td>
		</tr>
	</table>

	<? if ($block_user == "no") { ?>
		<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" 
		style="BORDER-RIGHT: gray 1px solid;BORDER-LEFT: gray 1px solid;BORDER-BOTTOM: gray 1px solid; height:20px;">
			<tr>
				<td>
				<form name="form1" method="post" action="index.php">
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
					<tr>
						<td style="height:10px;"></td>
					</tr>
					<? if ($email_send == "yes") { ?>
						<tr>
							<td ><span class="TBL_COL_HDR_NOSHADE">We have send the UCB dashboard login details in your email address, please check your email box. <br/><br/></td>
						</tr>
					<? } ?>				
					<tr>
						<td align="center">
							<input type="hidden" name="hd_forgot_pwd" id="hd_forgot_pwd" value="yes"/>
							<input type="submit" class="button" name="btn_forgotpwd" value="Forgot Password?">
						</td>
					</tr>
				</table>
				</form>	

				</td>
			</tr>
		</table>
	<? } ?>

	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" 
	style="BORDER-RIGHT: gray 1px solid;BORDER-LEFT: gray 1px solid;BORDER-BOTTOM: gray 1px solid;">
		<tr>
			<td>
			<form name="form1" method="post" action="index.php">
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
				<tr>
					<td style="height:10px;"></td>
				</tr>
				
				<tr>
					<td align="center" class="TBL_COL_HDR_NOSHADE">
						Don't have a UCB Client Portal?
					</td>
				</tr>
				<tr>
					<td style="height:2px;"></td>
				</tr>
				<tr>
					<td align="center">
						<input type="submit" class="button" name="btn_forgotpwd" value="Click Here to Contact Us" onclick="callmailto()">
						<a class="contactbtn" id="btnmailto" style="display:none;" href="mailto:info@usedcardboardboxes.com?Subject=Interested in Learning about UCB's Client Portals" target="_top">
						Click Here to Contact Us</a>
					</td>
				</tr>
				
			</table>
			</form>	

			</td>
		</tr>
	</table>
	
</body>
</html>
