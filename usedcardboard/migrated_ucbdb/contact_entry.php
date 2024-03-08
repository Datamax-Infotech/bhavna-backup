<?php  
require ("inc/header_session.php");

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

?>


<?php 

if ($_GET["action"] == 'process') 
{

$processing = "Posted";

$regtoday = date("Ymd");
$today = date("F j, Y, g:i a");
$realtoday = date("F j, Y, g:i a");

$arrInsert = $_POST;

$Query = "INSERT INTO ucb_contact SET added_on=now(), ";

foreach($arrInsert as $key=>$value)
{
$Query.= "$key='$value', ";
}

$Query = substr($Query, 0, -2);
// echo $Query;
db_query($Query, db() );
$insert_id = tep_db_insert_id();


if ($_POST['first_name'] == '') {
$update_it = "UPDATE ucb_contact SET first_name = 'Contact Submission' WHERE id = " . $insert_id;
db_query($update_it, db() );
}

/*

$orders_id = $_POST[order_no];
$customers_email_address = $_POST["email"];

					
$check_qry = "SELECT * FROM orders WHERE orders_id = " . $orders_id;
$check_qry_row = array_shift(db_query($check_qry));
$check_order = $check_qry_row["orders_id"];  
$check_email = $check_qry_row["customers_email_address"];  
$orders_id = $check_order; 

if ($check_order != '')
{
$regtoday = date("Ymd");
$today = date("F j, Y, g:i a");
$realtoday = date("F j, Y, g:i a");
$assigned_by = $_POST["assign_by"];
$empe = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['employee'] . "'";
$emperesult = db_query($empe,db() );
while ($rowempe = array_shift($emperesult)) 
{
$assigned_to_email = $rowempe["email"];
$assigned_to = $rowempe["initials"];
}

$issue = 'Attention';
$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$commqryrw = array_shift(db_query($commqry));
$comm_type = $commqryrw["id"];  

$output = "<b>" . $issue . "</b>.  This order has been assigned to " . $assigned_to . " by " . $assigned_by . " on " . $realtoday;
$output .= "<br>";
$output .= "The Information provided in as follows:";
$output .= "<br>";
$output .= "First Name: " . $_POST[first_name];
$output .= "<br>";
$output .= "Last Name: " . $_POST[last_name];
$output .= "<br>";
if ($_POST[title] != '') {
$output .= "Title: " . $_POST[title];
$output .= "<br>";
}
if ($_POST[company] != '') {
$output .= "Company: " . $_POST[company];
$output .= "<br>";
}
if ($_POST[industry] != '') {
$output .= "Industry: " . $_POST[industry];
$output .= "<br>";
}
$output .= "Address one: " . $_POST[address1];
$output .= "<br>";
if ($_POST[address2] != '') {
$output .= "Address two: " . $_POST[address2];
$output .= "<br>";
}
$output .= "City: " . $_POST[city];
$output .= "<br>";
$output .= "State: " . $_POST[state];
$output .= "<br>";
$output .= "Zip: " . $_POST[zip];
$output .= "<br>";
$output .= "Phone 1: : " . $_POST[phone1];
$output .= "<br>";
if ($_POST[phone2] != '') {
$output .= "Phone 2: " . $_POST[phone2];
$output .= "<br>";
}
$output .= "Email: " . $_POST[email];
$output .= "<br>";
$output .= "Comments: " . $_POST[help];
$output .= "<br>";
$output .= "How Hear: " . $_POST[information];
$output .= "<br>";

$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $output . "','" . $regtoday . "','" . $assigned_by . "')";
$result3 = db_query($sql3);


$ins_sql = "INSERT INTO ucbdb_issue (orders_id, issue, assigned_to, assigned_by, when_assigned) VALUES ( '" . $orders_id . "','" . $issue . "','" . $assigned_to . "','" . $assigned_by . "','" . $today . "')";
db_query($ins_sql); 


	$str_email = "The following Order has been assigned to you:\n\n";
	$str_email.= "Order ID:  ".$orders_id." \n";
	$str_email.= "\n\n";
	$str_email.= "You may click the link below to view the order.\n\n";
	$str_email.= "http://b2c.usedcardboardboxes.com/orders.php?id=" . $orders_id . "&proc=View";
					
	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

//	mail($assigned_to_email,"Urgent - Order With Problem",$str_email,$mailheadersadmin);
	mail("mdewan@tivex.com","Urgent - Order With Problem",$str_email,$mailheadersadmin);
		
	$str_email = "Hi David:\n\n";
	$str_email = "The following contact record was assigned to " . $assigned_to . " with a Problem Status:\n\n";
	$str_email.= "Order ID:  ".$orders_id." \n";
	$str_email.= "\n\n";
	$str_email.= "You may click the link below to view the order.\n\n";
	$str_email.= "http://b2c.usedcardboardboxes.com/orders.php?id=" . $orders_id . "&proc=View";
					
	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

//	mail("davidkrasnow@usedcardboardboxes.com","Order With Problem Assigned to " . $assigned_to,$str_email,$mailheadersadmin);
//	mail("mdewan@tivex.com","Order With Problem Assigned to " . $assigned_to,$str_email,$mailheadersadmin);		
	
}
else
{


*/

$regtoday = date("Ymd");
$today = date("F j, Y, g:i a");
$realtoday = date("F j, Y, g:i a");
$assigned_by = $_POST["assign_by"];
$empe = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['employee'] . "'";
$emperesult = db_query($empe,db() );
while ($rowempe = array_shift($emperesult)) 
{
$assigned_to_email = $rowempe["email"];
$assigned_to = $rowempe["initials"];
}

$issue = $_POST["status"];

if ($issue == 'Attention')
{

	$str_email = "The following Contact Issue has been assigned to you:\n\n";
	$str_email.= "\n\n";
	$str_email.= "You may click the link below to view the order.\n\n";
	$str_email.= "http://b2c.usedcardboardboxes.com/contact_status_drill.php?id=" . $insert_id . "&proc=View";
					
	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

	mail($assigned_to_email,"Urgent - New Contact Record Assigned to You",$str_email,$mailheadersadmin);
//	mail("mdewan@tivex.com","Urgent - New Contact Record Assigned to You",$str_email,$mailheadersadmin);
		
	$str_email = "Hi David:\n\n";
	$str_email = "The following contact record was assigned to " . $assigned_to . " with a Issue Status:\n\n";
	$str_email.= "\n\n";
	$str_email.= "You may click the link below to view the record.\n\n";
	$str_email.= "http://b2c.usedcardboardboxes.com/contact_status_drill.php?id=" . $insert_id . "&proc=View";
					
	$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

	mail("davidkrasnow@usedcardboardboxes.com","Contact Record Assigned to " . $assigned_to,$str_email,$mailheadersadmin);
//	mail("mdewan@tivex.com","Contact Record Assigned to " . $assigned_to,$str_email,$mailheadersadmin);		
}

}

/* } */



?>





<!DOCTYPE html>

<html>
<head>
	<title>DASH - Contact Entry</title>
<script type="text/javascript">
function changeOpt(theForm, selval)
{
  //theForm.elements["height"].style.display=(theForm.type_id.options[theForm.type_id.selectedIndex].value=="ccc")? 'block':'none'
		if(selval == "tml")
		{
			eval("document.getElementById('tblcell').style.display=\"\"");
			eval("document.getElementById('tblcell2').style.display=\"\"");
		}
		else {
			eval("document.getElementById('tblcell').style.display=\"none\"");
			eval("document.getElementById('tblcell2').style.display=\"none\"");			
		}

}

window.onload=function() {
  changeOpt(document.forms[0]);
}
</script>	

</head>


<body>
<div>
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">
<?php 
$arr_howhear = array('Apartment Manager', 'Campaign Sign', 'Craigslist', 'Flyer', 'Friend', 'Other', 'Postcard/Mailer', 'Radio', 'Realtor', 'School', 'Search Engine', 'TV', 'UCB Sales Rep');
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";

	// $_GET VARIABLES
	foreach($_GET as $a=>$b){$$a=$b;} 

	// $_POST VARIABLES
	foreach($_POST as $a=>$b){$$a=$b;} 
/*------------------------------------------------
END GLOBALS OFF SUPPORT
------------------------------------------------*/

echo "<Font Face='arial' size='2'>";

/*---------------------------------------------------------------------------------
TURN DEBUG ON - THIS ALLOWS YOU TO VIEW ALL SQL STATEMENTS AS THEY ARE EXECUTED
$sql_debug_mode=0 --> OFF (NO SQL STATEMENTS WILL BE SHOWN)
$sql_debug_mode=1 --> ON (SQL STATEMENTS WILL BE SHOWN)
---------------------------------------------------------------------------------*/
$sql_debug_mode=0;

error_reporting(E_WARNING|E_PARSE);

//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
$thispage	= $SCRIPT_NAME; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...

$allowedit		= "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
$allowaddnew	= "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
$allowdelete	= "no"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS

$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

//if (get_magic_quotes_gpc()) { $addslash="no"; } //AUTO-ADD SLASHES IF MAGIC QUOTES IS OFF
$addslash="yes";
?> 

<?php  

//echo "<br><a href=\"index.php\">Home</a> ";
echo " <a href=\"javascript: history.go(-1)\">Back</a><br><br>";

if ($processing == "Posted")
{
echo "<font face=arial size=2><strong>Record Posted and Email Notifications have been sent.<br><br></strong></font>";
}

?>


<script language="JavaScript">
<!--
function FormCheck()
{
if (document.ContactForm.employee.value == "" 
)
{
alert("You must assign this record to an employee.  Please try again.  Thank you.");
return false;
}
}
//--></SCRIPT>


<form name="ContactForm" method="post" action="contact_entry.php?action=process" name="ContactForm" onSubmit="return FormCheck()">
<table>
<tr>
<td valign="top">
<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Inquiry Details</font>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Existing Order</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><a href="orders.php?posting=yes&searchcrit=&B1=Find">Click Here</a> to Search Existing Orders<br></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Contact Type</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><select name="type_id" onChange="changeOpt(this.form, this.value)">
		<option value="mb_ny">Moving Box Order Not Yet Placed
<option value="spbox_not">Shipping Box Order Not Yet Placed 
<option value="spbox_rdy">Shipping Box Order Already Placed 
<option value="tml">Testimonial
<option value="ptn">Partnering Opportunities
<option value="inv_rel">Investor Relations
<option value="med_inq">Media Inquiries
<option value="box_req">Box Rescue
<option value="other">Other
		
		</select> 
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">First Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="first_name" type="text" id="first_name"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Last Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="last_name" type="text" id="last_name"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Title</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="title" type="text" id="title"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Company Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="company" type="text" id="company"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Industry</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="industry" type="text" id="industry"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Address</td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="address1" type="text" id="address1"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">Address 2</td>
		<td align="left" height="10" style="width: 235px" class="style1"><input name="address2" type="text" id="address2"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">City</td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="city" type="text" id="city"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">State</td>
		<td align="left" height="10" style="width: 235px" class="style1"><input name="state" type="text" id="state"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Zip</td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="zip" type="text" id="zip"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="19" style="width: 100px" class="style1">Phone</td>
		<td align="left" height="19" style="width: 235px" class="style1"><input name="phone1" type="text" id="phone1"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="19" style="width: 100px" class="style1">Phone 2</td>
		<td align="left" height="19" style="width: 235px" class="style1"><input name="phone2" type="text" id="phone2"></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">E-mail</td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="email" type="text" id="email"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Website</td>
		<td align="left" height="13" style="width: 235px" class="style1"><input name="website" type="text" id="website"></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">How Hear</td>
		<td align="left" height="13" style="width: 235px" class="style1"><select name="infomation">
	   <option value=""> please select </option>
		<?php 
		foreach($arr_howhear as $ind_hear)
		{
		?>
		<option value="<?php echo $ind_hear?>"><?php echo $ind_hear?></option>
		<?php 
		}
		?>
		</select></td>
	</tr>


	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Other Comments</td>
		<td align="left" height="13" style="width: 235px" class="style1"><textarea name="help" cols="20" rows="5" id="help"></textarea></td>
	</tr>

		<tr bgColor="#e4e4e4" id="tblcell" style="display:none">
		<td height="13" style="width: 100px" class="style1">Testimonial</td>
		<td align="left" height="13" style="width: 235px" class="style1"><textarea name="experiance" cols="20" rows="5" id="experiance"></textarea></td>
	</tr>
		<tr bgColor="#e4e4e4" id="tblcell2" style="display:none">
		<td height="13" style="width: 100px" class="style1">Testimonial Permission</td>
		<td align="left" height="13" style="width: 235px" class="style1"><select name="have_permission" size="1" id="have_permission">
		  <option value="No">No</option>
		  <option value="Yes">Yes</option>
		  </select></td>
	</tr>

			<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1"></td>
		<td align="left" height="13" style="width: 235px" class="style1"><br><br><input type="submit" value="Submit this Record"</td>
	</tr>
	</table>
</td>
<td valign="top">
<!-- <form action="addcontactstatus.php" method="post"> -->


<table cellSpacing="1" cellPadding="1" border="0" width="250">

<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Status</span>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13"  class="style1">
		Status</td>
		<td align="left" height="13" style="width: 197px" class="style1">
		<select name="status">

		<option value="Attention" selected>Attention</option>
		<option value="OK">OK</option>
		</select>
		
		</td>
	</tr>

<!--	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Assignment</span>
		</td>
	</tr> -->
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 189px">
		<span class="style1">Assigned To</span><font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> </font></td>
				<td align="left" height="13" style="width: 197px" class="style1">
<select name="employee">
<?php 
	$sqlgetemp = "SELECT * FROM ucbdb_employees ORDER BY Initials ASC";
	$ressqlgetemp = db_query($sqlgetemp,db() );
	echo "<option></option>";
	while ($myrowselemp = array_shift($ressqlgetemp)) {
	?>
		<option value="<?php  echo $myrowselemp["initials"]; ?>"><?php  echo $myrowselemp["initials"]; ?></option>
	<?php  
	} 
 ?>
</select></td>	</tr>
<!--	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" style="width: 189px" class="style1">
</td>
 
	</tr> -->
	</table>
		<!-- <input type="hidden" value="<?php  echo $id ?>" name="contact_id" /> -->
		<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="assign_by" />		
 

 <!--
<br>
<br>
<form action="addcontactorder.php" method="post">
<table cellSpacing="1" cellPadding="1" border="0" width="250">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Transfer to Order</span>
		</td>
	</tr>
<?php  if ($_GET['notice'] == 1) {
?>
	<tr align="middle">
		<td bgColor="red" colSpan="2">
		<span class="style1">INVALID ORDER ID</span>
		</td>
	</tr>
<?php  } ?>
	<tr bgColor="#e4e4e4">
		<td height="13" width="40%" class="style1">
		Order ID</td>
		<td align="left" height="13" style="width: 197px" class="style1">
		<input type="text" name="orders_id" size="15">
		
		</td>
	</tr>
 
	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" "style="width: 189px" class="style1">
		<input type="submit" value="Transfer"></td>
 
	</tr>
	</table>
		<input type="hidden" value="<?php  echo $id ?>" name="id" />
		<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />				
 
</form>
-->
</td>
<td colspan="3" valign="top">


<!-- <form method="post" encType="multipart/form-data" action="addcontactcrm.php"> -->

<!--
	<table cellSpacing="1" cellPadding="1" width="100%" border="0">
		<tr align="middle">
			<td bgColor="#c0cdda" colspan="4">
			<font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER 
			LOG</font><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			</font></td>
		</tr>
		
		<tr vAlign="top">
			<td bgColor="#e4e4e4" style="width: 106px" class="style1"><select size="1" name="comm_type">
<?php  

$sql1 = "SELECT * FROM ucbdb_customer_log_config ORDER BY rank";
$result1 = db_query($sql1,db() );
while ($myrowsel1 = array_shift($result1)) {
?>
<option value="<?php  echo $myrowsel1["id"]; ?>"><?php  echo $myrowsel1["comm_type"]; ?></option>
<?php  } ?>
			</select>&nbsp;</td>
			<td bgColor="#e4e4e4" style="width: 288px" class="style1">
			<textarea name="message" style="width: 361px; height: 41px;"></textarea></td>
			<td bgColor="#e4e4e4">&nbsp;</td>
			<td align="middle" bgColor="#e4e4e4" rowSpan="2" style="width: 76px" class="style1">
			</td>
		</tr>
		<tr>
			<td bgColor="#e4e4e4" colSpan="3" class="style1">
			<input type="file" size="50" name="file" /></td>
		</tr>
	</table>

		
-->		
		
		
</td></tr>


</table>

</form>
</div>


</body>
</html>
