<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>UCB - Swicth Accounts</title>
</head>
<body>
<?php 
require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");
db();
$myusername = ""; $mypassword = "";
$sql = "Select * FROM clientdashboard_usermaster WHERE loginid= '" . $_REQUEST["loginid"] . "'";
$result = db_query($sql);
while ($rq = array_shift($result)) {
	$myusername = $rq["user_name"];
	$mypassword = $rq["password"];
}

$sql = "Select * FROM clientdashboard_usermaster WHERE user_name='$myusername' and password='$mypassword' and activate_deactivate = 1";
$result = db_query($sql);
$reccnt = 0;
$companyid = 0; 
$loginid = 0;

while ($rq = array_shift($result)) {
	$companyid = $rq["companyid"];
	$loginid = $rq["loginid"];
	$reccnt = $reccnt + 1;
}

if($reccnt > 1)
{
?>
	<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" 
	style="BORDER-RIGHT: gray 1px solid;BORDER-TOP: gray 1px solid; BORDER-LEFT: gray 1px solid;BORDER-BOTTOM: gray 1px solid;">

		<tr>
			<td>
				<form name="form1" method="post" action="client_dashboard.php">
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
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
								<?php
									foreach ($mgarray as $MGArraytmp) {
										$nickname = $MGArraytmp["nickname"];
										$sel_str = "";
										if ($MGArraytmp["companyid"] == $_REQUEST["client_companyid"]) { $sel_str = " selected ";}
										echo "<option value='" .$MGArraytmp["companyid"] . "'" . $sel_str .">" . $nickname . "</option>";
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
}
?>
</body>
</html>
