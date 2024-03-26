<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<?php
$processing = "";
if ($_GET["action"] == 'process') {
	$regtoday = date("Ymd");
	$today = date("F j, Y, g:i a");
	$realtoday = date("F j, Y, g:i a");
	$arrInsert = $_POST;
	$Query = "INSERT INTO ucb_contact SET added_on=now(), ";
	foreach ($arrInsert as $key => $value) {
		$Query .= "$key='$value', ";
	}

	$Query = substr($Query, 0, -2);
	// echo $Query;
	db_query($Query);
	$insert_id = tep_db_insert_id();
	$regtoday = date("Ymd");
	$today = date("F j, Y, g:i a");
	$realtoday = date("F j, Y, g:i a");
	$assigned_by = $_POST["assign_by"];
	$empe = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['employee'] . "'";
	$emperesult = db_query($empe);
	$assigned_to_email = "";
	$assigned_to = "";
	while ($rowempe = array_shift($emperesult)) {
		$assigned_to_email = $rowempe["email"];
		$assigned_to = $rowempe["initials"];
	}
	$issue = $_POST["status"];
	$str_email = "The following Technical Support Issue has been assigned to you:\n\n";
	$str_email .= "You may click the link below to view the order.\n\n";
	$str_email .= "http://b2c.usedcardboardboxes.com/contact_status_drill.php?id=" . encrypt_url($insert_id) . "&proc=View";
	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

	mail($assigned_to_email, "Urgent - New Technical Support Request Assigned to You", $str_email, $mailheadersadmin);
	//	mail("mdewan@tivex.com","Urgent - New Technical Support Request Assigned to You",$str_email,$mailheadersadmin);

	$str_email = "Hi David:\n\n";
	$str_email = "The following technical support request record was assigned to " . $assigned_to . " with a Issue Status:\n\n";
	$str_email .= "You may click the link below to view the record.\n\n";
	$str_email .= "http://b2c.usedcardboardboxes.com/contact_status_drill.php?id=" . encrypt_url($insert_id) . "&proc=View";

	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

	//	mail("davidkrasnow@usedcardboardboxes.com","Help - Technical Issue Record Assigned to " . $assigned_to,$str_email,$mailheadersadmin);
	mail("mdewan@tivex.com", "Help - Technical Issue Record Assigned to " . $assigned_to, $str_email, $mailheadersadmin);
	$processing = "Posted";
}
/* } */
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Contact Entry</title>
	<script type="text/javascript">
		function changeOpt(theForm, selval) {
			//theForm.elements["height"].style.display=(theForm.type_id.options[theForm.type_id.selectedIndex].value=="ccc")? 'block':'none'
			if (selval == "tml") {
				eval("document.getElementById('tblcell').style.display=\"\"");
				eval("document.getElementById('tblcell2').style.display=\"\"");
			} else {
				eval("document.getElementById('tblcell').style.display=\"none\"");
				eval("document.getElementById('tblcell2').style.display=\"none\"");
			}
		}
		window.onload = function() {
			changeOpt(document.forms[0]);
		}
	</script>
</head>

<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= 'help_entry.php'; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	$allowedit		= "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "no"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
	$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.
	$addslash = "yes";
	?>
	<?php
	echo "<br><a href=\"index.php\">Home</a> ";
	echo " <a href=\"javascript: history.go(-1)\">Back</a><br><br>";
	if ($processing == "Posted") {
		echo "<font face=arial size=2><strong>Record Posted and Email Notifications have been sent.<br><br></strong></font>";
	}
	?>
	<form name="ContactForm" method="post" action="help_entry.php?action=process">
		<table>
			<tr>
				<td valign="top">
					<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
						<tr align="middle">
							<td bgColor="#c0cdda" colSpan="2">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									Help - Technical Issue Request
								</font>
							</td>
						</tr>
						<input type="hidden" name="type_id" value="other">
						<input type="hidden" name="first_name" value="Technical">
						<input type="hidden" name="last_name" value="Support">
						<input type="hidden" name="company" value="UsedCardboardBoxes.com">
						<input type="hidden" name="phone1" value="3237242500">
						<input type="hidden" name="status" value="Attention">
						<input type="hidden" name="is_export" value="2">
						<input type="hidden" name="employee" value="MD">
						<tr bgColor="#e4e4e4">
							<td height="13" style="width: 100px" class="style1">Describe the Issue</td>
							<td align="left" height="13" style="width: 235px" class="style1"><textarea name="help" cols="20" rows="5" id="help"></textarea></td>
						</tr>
						<tr bgColor="#e4e4e4">
							<td height="13" style="width: 100px" class="style1"></td>
							<td align="left" height="13" style="width: 235px" class="style1"><br><br><input type="submit" value="Submit this Record" </td>
						</tr>
					</table>
				</td>
				<td valign="top">

				</td>
				<td colspan="3" valign="top">
				</td>
			</tr>
		</table>
	</form>
</body>

</html>