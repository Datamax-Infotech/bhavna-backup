<!-- 
File Name: login.php
Page created By: Amarendra Singh
Page created On: 16-July-2023
Last Modified On: 
Last Modified By: Amarendra Singh
Change History:
Date        	By           		Description
======================================================================================================
16-July-2023	Amarendra Singh		This file is created for the new login page have login form look is chnage.  
									The user email id will be used as userid here. 
======================================================================================================
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>UCB Loop System - Login</title>

	<link rel='stylesheet' type='text/css' href='one_style.css'>
<script>
	function chkchgpwd(){
		if (document.getElementById("emailid").value == ""){
			alert("Please enter the Email address.");
			return false;
		}
		
		document.frmforgotpwdfrm.submit();
	}

	function chkfrmsubmit(){
		if (document.getElementById("myusername").value == ""){
			alert("Please enter the Email address.");
			return false;
		}
		
		if (document.getElementById("mypassword").value == ""){
			alert("Please enter the Password.");
			return false;
		}
	}

	function callmailto(){
		var ele = document.getElementById("btnmailto");
		ele.click();
	}
</script>
</head>

<body>
<?php 
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

$block_user = "no";
$email_send ="no";

if (isset($_REQUEST["hd_forgotpwd_upd"])) {
	if ($_REQUEST["hd_forgotpwd_upd"] == "yes") {

		$sql="SELECT * FROM ucbdb_employees WHERE email = '" . $_REQUEST["emailid"] ."'";
		$result = db_query($sql , db());
		$rec_found = "no"; $tempstr = "";
		while ($rq = array_shift($result)) {
			if ($rq["email"] != "") {
				$rec_found = "yes";
				$tempstr .= "User name: " . $rq["email"] . "<br/>Password: " . $rq["password"] . "<br/><br/>";
			}
		}
		
		if ($rec_found == "no") 
		{?>
			<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
				<tr>
					<td>
						<form name="form1" method="post" action="">
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
			
		<?php exit;}else {
			$email_send ="yes";
			//$emlstatus = sendemail_attachment(null, "", $_REQUEST["emailid"], "", "", "admin@usedcardboardboxes.com", "Admin UCB","", "UCB Dashboard - password details" , "UCB Dashboard login details are as follows:<br/><br/>" . $tempstr . "<br/><br/>UCB Dashboard : <a href='http://www.usedcardboardboxes.co/supplier_dash'>www.usedcardboardboxes.co/supplier_dash</a>");
			$emlstatus = sendemail_php_function(null, '', $_REQUEST["emailid"], "", "", "ucbemail@usedcardboardboxes.com", "UCB Admin Team", "admin@UsedCardboardBoxes.com", "UCB Dashboard - password details", "UCB Dashboard login details are as follows:<br/><br/>" . $tempstr . "<br/><br/>UCB Loops : <a href='https://loops.usedcardboardboxes.com/login.php'>loops.usedcardboardboxes.com/login</a>"); 
		}
	}
}


if (isset($_REQUEST["hd_forgot_pwd"])) {
	if ($_REQUEST["hd_forgot_pwd"] == "yes") {?>
		
	<form name="frmforgotpwdfrm" action="login.php" method="Post" >
		<input type="hidden" name="hd_forgotpwd_upd" id="hd_forgotpwd_upd" value="yes"/>
		<table width="310" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
			<tr>
				<td>
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
				</td>
			</tr>
		</table>	
	</form> 
	
	<?php exit;}
	
}
?>

<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<?php 
	if ($_REQUEST["action"] =="no" && isset($_REQUEST["user_block"])){
		echo "<tr><td align=\"center\" colspan=\"3\"><br><br><span class=\"TBL_COL_HDR_NOSHADE\">THE USER ". $_REQUEST["name"]." 
		IS LOCKED. PLEASE TRY AFTER 10 MINUTES (STARTING FROM LOCK TIME).<br><br>TRY AFTER " . $_REQUEST["diff_in_time"] . " CT. CURRENT SERVER TIME: ". date("H:i:s") . "</span><br><br></td></tr>"; 
	}else if($_REQUEST["action"] =="no" && isset($_REQUEST["user_attempt"])){
		echo "<tr><td align=\"center\" colspan=\"3\"><br><br><span class=\"TBL_COL_HDR_NOSHADE\">";
		if ($_REQUEST["user_attempt"]>0) {
			if ($_REQUEST["user_attempt"] == 1) {
				echo "INCORRECT PASSWORD. ONE ATTEMPT IS LEFT.";
			}	
			if ($_REQUEST["user_attempt"] == 2) {
				echo "INCORRECT PASSWORD. TWO ATTEMPTS ARE LEFT.";
			}	
			if ($_REQUEST["user_attempt"] > 2) {
				echo "INCORRECT PASSWORD. THREE ATTEMPTS ARE LEFT.";
			}	
		}else{
			echo "USER LOCKED. PLEASE TRY AFTER 10 MINUTES (STARTING FROM LOCK TIME).<br><br>TRY AFTER " . $_REQUEST["diff_in_time"] . " CT. CURRENT SERVER TIME: ". date("H:i:s"); 
		}
		echo "</span> <br> <br> </td></tr>"; 
	}else if($_REQUEST["action"] =="no"){
		echo "<tr><td align=\"center\" colspan=\"3\"><br><br><span class=\"TBL_COL_HDR_NOSHADE\"><font color=red>INCORRECT EMAIL ADDRESS OR PASSWORD</font></span><br><br></td></tr>"; 
	}
	?>

	<tr>
		<form name="form1" method="post" action="checklogin.php" onsubmit="return chkfrmsubmit();">
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
					<tr>
						<td colspan="4" align="center" ><img src="images/ucb-logo.png" width="100" height="100"/></td>
					</tr>
					<tr><td colspan="4" align="center" ><span class="TBL_COL_HDR_NOSHADE">LOG ON TO UCB</span> </td></tr>
					<!-- <tr><td colspan="4"><hr></td></tr>
					<tr>
						<td colspan="4"><span class="TBL_COL_HDR_NOSHADE">Member Login </span></td>
					</tr>
					-->
					<tr>
						<td width="100"><span class="TBL_COL_HDR_NOSHADE">User Email:</span></td>
						<td width="15">:</td>
						<td width="140"><input name="myusername" type="text" id="myusername" size="28" value="<?php if ($_REQUEST["user_email"] !='') { echo $_REQUEST["user_email"];}?>"></td>
						<td width="20">&nbsp;</td>
					</tr>
					<tr>
						<td><span class="TBL_COL_HDR_NOSHADE">Password</span></td>
						<td>:</td>
						<td><input name="mypassword" type="password" id="mypassword"  size="28"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="right">
							<input type="submit" class="button" name="Submit" value="Submit">
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>
<?php //if ($block_user == "no") { ?>
	<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" 
	style="BORDER-RIGHT:#CCCCCC 1px solid;BORDER-LEFT:#CCCCCC 1px solid;">
		<tr>
			<td>
			<form name="form1" method="post" action="login.php">
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
				<tr>
					<td style="height:10px;"></td>
				</tr>
				<?php if ($email_send == "yes") { ?>
					<tr>
						<td ><span class="TBL_COL_HDR_NOSHADE">We have send the UCB Loops login details in your email address, please check your email box. <br/><br/></td>
					</tr>
				<?php } ?>				
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
<?php //} ?>

<table width="350" border="0" align="center" cellpadding="0" cellspacing="1" style="BORDER: #CCCCCC 1px solid;">
	<tr>
		<td>
			
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
					<tr>
						<td style="height:10px;"></td>
					</tr>
					
					<tr>
						<td align="center" class="TBL_COL_HDR_NOSHADE">
							Trouble logging in?
						</td>
					</tr>
					<tr>
						<td style="height:2px;"></td>
					</tr>
					<tr>
						<td align="center">
							<input type="submit" class="button" name="btn_forgotpwd" value="Contact Tech Support" onclick="callmailto()">
							<a class="contactbtn" id="btnmailto" style="display:none;" href="mailto:info@usedcardboardboxes.com?Subject=To Open a new Account for loop" target="_top">
							Contact Tech Support</a>
						</td>
					</tr>					
				</table>
			
		</td>
	</tr>
</table>

</body>
</html>
