<?php 
require ("inc/header_session.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>DASH - Contact Search Results</title>
</head>


<body>
<?php
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

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


?> 

<?php 
if ($proc == "View") {


echo "<br><a href=\"index.php\">Home</a> ";
echo " <a href=\"javascript: history.go(-1)\">Back</a><br><br>";

?>
<!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br> -->

<?php
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
 <?php } //END OF IF ALLOW ADDNEW 

if ($proc == "View") {


$sql = "SELECT * FROM ucb_contact WHERE id='$id'";
$result = db_query($sql,db() );		
while ($myrowsel = array_shift($result)) {
$id = $myrowsel["id"];
$type_id = $myrowsel["type_id"];
$first_name = $myrowsel["first_name"];
$last_name = $myrowsel["last_name"];
$title = $myrowsel["title"];
$company = $myrowsel["company"];
$industry = $myrowsel["industry"];
$address1 = $myrowsel["address1"];
$address2 = $myrowsel["address2"];
$city = $myrowsel["city"];
$state = $myrowsel["state"];
$zip = $myrowsel["zip"];
$phone1 = $myrowsel["phone1"];
$phone2 = $myrowsel["phone2"];
$email = $myrowsel["email"];
$website = $myrowsel["website"];
$order_no = $myrowsel["order_no"];
$choose = $myrowsel["choose"];
$ccheck = $myrowsel["ccheck"];
$infomation = $myrowsel["infomation"];
$help = $myrowsel["help"];
$experience = $myrowsel["experience"];
$mail_lists = $myrowsel["mail_lists"];
$comments = $myrowsel["comments"];
$sel_service = $myrowsel["sel_service"];
$experiance = $myrowsel["experiance"];
$is_export = $myrowsel["is_export"];
$added_on = $myrowsel["added_on"];
$have_permission = $myrowsel["have_permission"];

switch($type_id){
	case 'mb_ny':
	$stat = "Moving Box Order Not Yet Placed";
	BREAK;
	case 'spbox_not':
	$stat = "Shipping Box Order Not Yet Placed";
	BREAK;
	case 'spbox_rdy':
	$stat = "Shipping Box Order Already Placed ";
	BREAK;
	case 'tml':
	$stat = "Testimonial";
	BREAK;
	case 'ptn':
	$stat = "Partnering Opportunities";
	BREAK;
	case 'inv_rel':
	$stat = "Investor Relations";
	BREAK;
	case 'med_inq':
	$stat = "Media Inquiries";
	BREAK;
	case 'box_req':
	$stat = "Box Rescue";
	BREAK;
	case 'other':
	$stat = "Other";
	BREAK;
	case 'Voicemail':
	$stat = "Voicemail";
	BREAK;
	case 'Fax':
	$stat = "Fax Message";
	BREAK;				
}
?>
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
		<td height="13" style="width: 100px" class="style1">Inquiry Date</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo substr($added_on, 0, 10); ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td style="width: 100px; height: 13px;" class="style1">Time</td>
		<td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo substr($added_on, 11, 19); ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td style="width: 100px; height: 13px;" class="style1">Type</td>
		<td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $stat; ?></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">First Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $first_name; ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Last Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $last_name; ?></td>
	</tr>
	<?php if ($title != '') { ?>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Title</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $title; ?></td>
	</tr>
	<?php } ?>
	<?php if ($company != '') { ?>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Company Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $company; ?></td>
	</tr>
	<?php } ?>
	<?php if ($industry != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Industry</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $industry; ?></td>
	</tr>
	<?php } ?>
	<?php if ($address1 != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Address</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $address1; ?></td>
	</tr>
	<?php } ?>
	<?php if ($address2 != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">Address 2</td>
		<td align="left" height="10" style="width: 235px" class="style1"><?php echo $address2; ?></td>
	</tr>
	<?php } ?>
	<?php if ($city != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">City</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $city; ?></td>
	</tr>
	<?php } ?>
	<?php if ($state != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">State</td>
		<td align="left" height="10" style="width: 235px" class="style1"><?php echo $state; ?></td>
	</tr>
	<?php } ?>
	<?php if ($zip != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Zip</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $zip; ?></td>
	</tr>
	<?php } ?>

	<?php if ($phone1 != '') { ?>			
	<tr bgColor="#e4e4e4">
	<?php if ($stat == 'Fax Message'){ ?>
		<td height="19" style="width: 100px" class="style1">Fax</td>
	<?php } else { ?>
		<td height="19" style="width: 100px" class="style1">Phone</td>	
	<?php } ?>
	    <td align="left" height="19" style="width: 235px" class="style1"><?php echo $phone1; ?></td>
	</tr>
	<?php } ?>

	<?php if ($phone2 != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="19" style="width: 100px" class="style1">Phone 2</td>
		<td align="left" height="19" style="width: 235px" class="style1"><?php echo $phone2; ?></td>
	</tr>	
	<?php } ?>
	<?php if ($email != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">E-mail</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $email; ?></td>
	</tr>
	<?php } ?>
	<?php if ($website != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Website</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $website; ?></td>
	</tr>
	<?php } ?>
	<?php if ($infomation != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">How Hear</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $infomation; ?></td>
	</tr>
	<?php } ?>	
	<?php if ($order_no != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Order No.</td>
		<td align="left" height="13" style="width: 235px" class="style1"><a href="orders.php?id=<?php echo $order_no; ?>&proc=View&searchcrit=&page=0"><?php echo $order_no; ?></a></td>
	</tr>
	<?php } ?>

<?php
$voicemail = substr($help, 0, 17);
?>
	<?php if ($help != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1"><?php if ($voicemail == 'https://webrouter') { echo 'Voicemail'; } else { echo 'Help'; } ?></td></font></td>
		<td align="left" height="13" style="width: 235px" class="style1">
		<?php if ($voicemail == 'https://webrouter') 
			{ 
			echo "<a href=\"" . $help . "\" target=\"_blank\">" . $help . "</a>";
			} 
			else 
			{ 
				if ($stat == 'Fax Message')
					{ 
					//echo "<a href=\"faxes/" . $help . "\">View This Fax</a>";
					$sqlfax = "SELECT AttachmentData FROM Attachments WHERE AttachmentName = '$help'";
					$resultfax = db_query($sqlfax,db() );		
					$con_roblem_you_result_rows = tep_db_num_rows($resultfax);
						if ($con_roblem_you_result_rows > 0) { 
						echo "<a href=\"show_fax.php?file=" . $help . "\">View This Fax</a>";
						}
						if ($con_roblem_you_result_rows == 0) { 
						echo "<a href=\"faxes/" . $help . "\">View This Fax</a>"; 
						} 
					} 
				elseif ($stat == 'Voicemail') 
				{
					$sqlfax = "SELECT AttachmentData FROM Attachments WHERE AttachmentName = '$help'";
					$resultfax = db_query($sqlfax,db() );		
					$con_roblem_you_result_rows = tep_db_num_rows($resultfax);
						if ($con_roblem_you_result_rows > 0) { 
						echo "<a href=\"show_file.php?file=" . $help . "\">Listen to the Voicemail</a>";
						}
				} else { 
				echo $help; 
				} 
			} ?>
</td>
	</tr>
	<?php } ?>
	
	
	
	<?php if ($comments != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Other Comments</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $comments; ?></td>
	</tr>
	<?php } ?>
	<?php if ($experience != '') { ?>		
		<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Testimonial</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $experience; ?></td>
	</tr>
		<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Testimonial Permission</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php echo $have_permission; ?></td>
	</tr>
	<?php } ?>
	</table>
	
	
	
	
	
		
	
	
	
	
	
	
	
	
	
</td>
<td valign="top">
<form action="addcontactstatus.php" method="post">
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
		<select name="issue">

<?php
$sqlissue = "SELECT * FROM ucb_contact WHERE id = " . $id;
$resissue = db_query($sqlissue,db() );
$resissuecount = tep_db_num_rows($resissue);
if ($resissuecount == 0) {
?>
		<option value="OK">OK</option>
		<option value="Attention">Attention</option>

<?php } else 
{
while ($myresissue = array_shift($resissue)) {
if ($myresissue["status"] == 'Attention') {
?>
<option valie="Attention">Attention</option>
<option value="OK">OK</option>		
<?php } 
if ($myresissue["status"] != 'Attention') {
?>
		<option value="OK">OK</option>
		<option value="Attention">Attention</option>
<?php 
}
}
}
?>
		</select>
		
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 189px">
		<span class="style1">Assigned To</span><font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> </font></td>
				<td align="left" height="13" style="width: 197px" class="style1">
<select name="assigned_to">
<?php
$sqlissue1 = "SELECT * FROM ucb_contact WHERE id = " . $id . " AND status = 'Attention'";
$resissue1 = db_query($sqlissue1,db() );
$resissue1count = tep_db_num_rows($resissue1);

if ($resissue1count == 0) {
	$sqlgetemp = "SELECT * FROM ucbdb_employees ORDER BY Initials ASC";
	$ressqlgetemp = db_query($sqlgetemp,db() );
	echo "<option></option>";
	while ($myrowselemp = array_shift($ressqlgetemp)) {
	?>
		<option value="<?php echo $myrowselemp["initials"]; ?>"><?php echo $myrowselemp["initials"]; ?></option>
	<?php 
	} 
}
else 
{
	while ($myresissue1 = array_shift($resissue1)) {
	?>
	<option value="<?php echo $myresissue1["employee"]; ?>"><?php echo $myresissue1["employee"]; ?></option>
	<?php 
	} 
	?>
	<option></option>
	<?php
$sqlgetemp = "SELECT * FROM ucbdb_employees ORDER BY Initials ASC";
$ressqlgetemp = db_query($sqlgetemp,db() );
while ($myrowselemp = array_shift($ressqlgetemp)) {
?>
<option value="<?php echo $myrowselemp["initials"]; ?>"><?php echo $myrowselemp["initials"]; ?></option>
<?php }
} ?>
</select></td>	</tr>
	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" "style="width: 189px" class="style1">
		<input type="submit" value="Update"></td>
 
	</tr>
	</table>
		<input type="hidden" value="<?php echo $id ?>" name="contact_id" />
		<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />		
 
</form>
 
<br>
<br>
<form action="addcontactorder.php" method="post">
<table cellSpacing="1" cellPadding="1" border="0" width="250">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Transfer to Order</span>
		</td>
	</tr>
<?php if ($_GET['notice'] == 1) {
?>
	<tr align="middle">
		<td bgColor="red" colSpan="2">
		<span class="style1">INVALID ORDER ID</span>
		</td>
	</tr>
<?php } ?>
	<tr bgColor="#e4e4e4">
		<td height="13" width="40%" class="style1">
		Order ID</td>
		<td align="left" height="13" style="width: 197px" class="style1">
		<input type="text" name="orders_id" size="15">
		
		</td>
	</tr>
 
	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" style='width: 189px' class="style1">
		<input type="submit" value="Transfer"></td>
 
	</tr>
	</table>
		<input type="hidden" value="<?php echo $id ?>" name="id" />
		<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />				
 
</form>
</td>
<td colspan="3" valign="top">
<form method="post" encType="multipart/form-data" action="addcontactcrm.php">
	<table cellSpacing="1" cellPadding="1" width="100%" border="0">
		<tr align="middle">
			<td bgColor="#c0cdda" colspan="4">
			<font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER 
			LOG</font><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			</font></td>
		</tr>
		<input type="hidden" value="<?php echo $id ?>" name="contact_id" />
		<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />		
		
		<tr vAlign="top">
			<td bgColor="#e4e4e4" style="width: 106px" class="style1"><select size="1" name="comm_type">
<?php 

$sql1 = "SELECT * FROM ucbdb_customer_log_config ORDER BY rank";
$result1 = db_query($sql1,db() );
while ($myrowsel1 = array_shift($result1)) {
?>
<option value="<?php echo $myrowsel1["id"]; ?>"><?php echo $myrowsel1["comm_type"]; ?></option>
<?php } ?>
			</select>&nbsp;</td>
			<td bgColor="#e4e4e4" style="width: 288px" class="style1">
			<textarea name="message" style="width: 361px; height: 41px;"></textarea></td>
			<td bgColor="#e4e4e4">&nbsp;</td>
			<td align="middle" bgColor="#e4e4e4" rowSpan="2" style="width: 76px" class="style1">
			<input type="submit" value="Add" /></td>
		</tr>
		<tr>
			<td bgColor="#e4e4e4" colSpan="3" class="style1">
			<input type="file" size="50" name="file" /></td>
		</tr>
	</table>
</form>

<table cellSpacing="1" cellPadding="1" width="100%" border="0">
		<tr align="middle">
			<td bgColor="#c0cdda" colspan="6">
			<font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER 
			LOG HOSTORY</font><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			</font></td>
		</tr>
<tr bgColor="#e4e4e4">
<td class="style1">Date</td>
<td class="style1">Image</td>
<td class="style1">Type</td>
<td class="style1">Notes</td>
<td class="style1">Employee</td>
<td class="style1">File Link</td>
</tr>				
<?php
$sql7 = "SELECT * FROM ucbdb_contact_crm WHERE contact_id = " . $id . " ORDER BY message_date DESC, id DESC ";
$result7 = db_query($sql7,db() );
while ($myrowsel7 = array_shift($result7)) {
$the_log_date = $myrowsel7["message_date"];

$yearz = substr("$the_log_date", 0, 4);
$monthz = substr("$the_log_date", 4, 2);
$dayz = substr("$the_log_date", 6, 2); 
?>


<tr bgColor="#e4e4e4">
<td class="style1"><?php echo $monthz . "/" . $dayz . "/" . $yearz; ?></td>
<td class="style1"><?php 
$qry2 = "SELECT icon_file, comm_type FROM ucbdb_customer_log_config WHERE id = '" . $myrowsel7["comm_type"] . "'";
$result2 = db_query($qry2,db() );
while ($myrowsel2 = array_shift($result2)) { ?>
<img src="images/<?php echo $myrowsel2["icon_file"]; ?>" alt="" border="0"></td>
<td class="style1"><?php echo $myrowsel2["comm_type"]; ?></td>
<?php } ?>
<td class="style1"><?php echo $myrowsel7["message"]; ?></td>
<td class="style1"><?php echo $myrowsel7["employee"]; ?></td>
<td class="style1"><?php if ($myrowsel7["file_name"] != '') 
{
echo "<a href='files/" . $myrowsel7["file_name"] . "'>File</a>";
}
?></td>
</tr>

<?php } ?>

</table>
		
		
		
		
		
		
</td></tr>


</table>




<?php
if ($stat == 'Fax Message')
{ 
?>



	<form action="addeod_contact.php" method="post" encType="multipart/form-data" name="rptSearch">
<input type="hidden" name="processing" value="yes">
<input type="hidden" name="file" value="<?php echo $help; ?>">
<input type="hidden" value="<?php echo $id ?>" name="contact_id" />
<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />		
  
<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr align="middle">
		<td colSpan="4" bgColor="#c0cdda"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		UPLOAD
		END OF DAY</td>
	</tr>
	<tr>
		<td bgColor="#c0cdda" style="width: 7%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Warehouse</td>
		<td bgColor="#c0cdda" style="width: 25%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Date</td>		
		<td bgColor="#c0cdda" style="width: 24%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		# of LABELS ON EOD REPORT</td>
		<td bgColor="#c0cdda" style="width: 24%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		#
		UPS PICKED UP</td>
	</tr>
	


	
	<tr vAlign="center">
		<td bgColor="#e4e4e4" style="width: 7%" class="style10">
		<select name="warehouse_name"><option>Please Select
<?php 

$sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {
?>
<option value="<?php echo $myrowsel2["distribution_center"]; ?>"><?php echo $myrowsel2["distribution_center"]; ?></option>
<?php } ?>
</select></td>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
<script LANGUAGE="JavaScript">
//	var cal1xx = new CalendarPopup("listdiv");
	var cal1xx = new CalendarPopup();
	cal1xx.showNavigationDropdowns();
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
</script>
		<td bgColor="#e4e4e4" style="width: 7%" class="style10"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><input type="text" name="search_date" size="11" value="<?php echo date('m/d/y'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.search_date,'anchor1xx','MM/dd/yy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a></td>
		<td align="middle" bgColor="#e4e4e4" class="style5" style="width: 24%">
		<input size="20 "type="text" style="width: 66px" name="labels_on_report"></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style5">
		<input type="text" size="5" style="width: 55px" name="labels_on_pickup"></td>
	</tr>

	<tr>
	<td colspan="3" class="style6">		<input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />		<input type="submit" value="Submit EOD"></td>
	</tr>
</table>
</form>

  <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
  
<br />
<br />
<br />
	
	

<?php
$sqlfax = "SELECT AttachmentData FROM Attachments WHERE AttachmentName = '$help'";
$resultfax = db_query($sqlfax,db() );		
$con_roblem_you_result_rows = tep_db_num_rows($resultfax);
if ($con_roblem_you_result_rows > 0) { 
?>
<IFRAME SRC="show_fax.php?id=<?php echo $id; ?>&file=<?php echo $help; ?>" WIDTH=1121 HEIGHT=1000></IFRAME>
<?php } ?>

<?php if ($con_roblem_you_result_rows == 0) { ?>
<IFRAME SRC="faxes/<?php echo $help; ?>" WIDTH=1121 HEIGHT=1000>
If you can see this, your browser doesn't understand IFRAME.  However, we'll still <A HREF="faxes/<?php echo $help; ?>">link</A> you to the file.
</IFRAME>
<?php } ?>




<?php
} 
?>


<?php
} //IF RESULT

} //END OF PROC VIEW

}// END IF PROC = "VIEW"
?>






</body>
</html>
