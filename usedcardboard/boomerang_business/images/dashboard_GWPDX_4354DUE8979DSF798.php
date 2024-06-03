<? 
//require ("inc/header_session.php");
?>

<?
require ("inc/database.php");

$locations = array(647,648,650,651); // This is an array of all the locations
$locations_string = "(647,648,650,651)"; // This is an array of all the locations but as a string for easy SQL searches
$title = "Goodwill Industries of the Columbia Willamette - Dashboard"; // The title
$initials = "GW Portland"; // This is what it shows in Loops as to who uploaded the PO
$returnurl = "dashboard_GWPDX_4354DUE8979DSF798.php"; // This is the url of the current page
$logo = "gicw.png";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>

	<title><?=$title;?></title>

	
	
	
<style type="text/css">
.style7 {
	font-size: x-small;
	font-family: Arial, Helvetica, sans-serif;
	color: #333333;
	background-color: #FFCC66;
}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	text-align: center;
	background-color: #99FF99;
}
.style6 {
	text-align: center;
	background-color: #99FF99;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
}
.style3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
}
.style8 {
	text-align: left;
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
}
.style11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: center;
}
.style10 {
	text-align: left;
}
.style12 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: right;
}
.style12center {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: center;
}
.style12right {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: right;
}
.style12left {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	text-align: left;
}
.style13 {
	font-family: Arial, Helvetica, sans-serif;
}
.style14 {
	font-size: x-small;
}
.style15 {
	font-size: x-small;
}
.style16 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style17 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
	background-color: #99FF99;
}
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 12px; 
color : #000000; 
font-weight: normal; 
}
</style>	


</head>
<script language="JavaScript">
function FormCheck()
{
	if (document.BOLForm.trailer_no.value == "" |
		document.BOLForm.dock.value =="" |
		document.BOLForm.fullname.value =="")
	{
		alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");
		return false;
	}
}
</SCRIPT>	 
<script type="text/javascript"> 
function update_cart()
{
  var x
  var total=0
  var order_total
  for (x=1; x<=10; x++)
  {
    item_total=document.getElementById("weight_"+x)
    total = total + item_total.value * 1
  }
  order_total=document.getElementById("order_total")
  document.getElementById("order_total").value=total.toFixed(0)
 }
</script>
<body>




<!---- TABLE TO FORMAT ----------->
<table width="1340">
	<tr>
		<td width="450">
			<img src="images/<?=$logo;?>">
		</td>
		<td align=center colspan="3">
			<font face="Ariel" size="5">
			<b>UsedCardboardBoxes.com<br></b>
			Dashboard Report for:<br>
			<b><i><?=$title;?></i></b>
			</i>
		</td>
		<td  align="right" >
			<img src="new_interface_help.gif">
		</td>
	</tr>
	<tr><td width="450"></td></tr>
<tr><td width="450">&nbsp;</td></tr>
</table>
<table>
<tr><td valign="top">
<!-----------------------------UPLOAD Signed Quotes / POs ------------------------------------------->

<form action="addpodashboard.php" method="post" encType="multipart/form-data">
<input type="hidden" name="rec_type" value="Supplier"/>		
<input type="hidden" value="<?=$initials;?>" name="employee" />	
<input type="hidden" value="<?=$returnurl;?>" name="returnurl" />
 
		
	
<table cellSpacing="1" cellPadding="1" border="0" id="table14">
 
	<tr align="middle">
		<td colspan="3"class="style7" style="height: 16px"><strong>UPLOAD PURCHASE ORDER</strong> </td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td bgColor="#e4e4e4" class="style12" >
		Location</td>
		
 
		<td bgColor="#e4e4e4" class="style12left" >
		
	
<Font size='1' Face="arial">
 
		<select name="warehouse_id">
<?

foreach ($locations as $location) {
$sqlqry = "SELECT `warehouse_name` FROM`loop_warehouse` WHERE id = " . $location;

$res = mysql_query($sqlqry,db());
$row = mysql_fetch_array($res);
    echo "<option value='" . $location . "'>" . $row["warehouse_name"] . "</option>";
}

?>

		</select></font></td>
 
	
	</tr>		
	<tr bgColor="#e4e4e4">
		<td bgColor="#e4e4e4" class="style12" >
		Purchase Order: </td>
		
 
		<td bgColor="#e4e4e4" class="style12" >
		
	
<Font size='1' Face="arial">
 
		<input type=file name="file" size="32"></font></td>
 
	
	</tr>
 
	<tr bgColor="#e4e4e4">
		<td bgColor="#e4e4e4" class="style12center" colspan=2>
		<input type=submit value="Upload">
	
 
 
		</td>
	</tr>
 
	
	</table>
 
</form> 


</td>
<td width="36">
	&nbsp;
</td>
<td valign="top" >
<!--------------- PENDING SHIPMENT TABLE ---------------->
<table cellSpacing="1" cellPadding="1" border="0" >

	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>VIEW PENDING SHIPMENTS</b></td>
	</tr>
	<tr>
		<td style="width: 150" class="style17" align="center">
			<b>Date Uploaded</b></td>
		<td class="style17" align="center">
			<b>Location</b></td>
		<td style="width: 150" class="style17" align="center">
			<b>PO/Quote</b></td>
	</tr>		

	
<?php
$query = "SELECT loop_transaction_buyer.start_date AS A, loop_transaction_buyer.po_file AS B, loop_warehouse.warehouse_name AS C from loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.shipped=0 AND loop_transaction_buyer.warehouse_id IN " . $locations_string;

$res = mysql_query($query,db());
while($row = mysql_fetch_array($res))
{

	?>
		<tr vAlign="center">
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<? echo date('m-d-Y', strtotime($row["A"])); ?></td>
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<? echo $row["C"]; ?>
			</td>
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<a href="http://www.usedcardboardboxes.com/ucbloop/po/<?= $row["B"]; ?>" target=_blank>VIEW PO/QUOTE</a>
			</td>
		</tr>
<?
}
?>
</table>
<!--------------- END PENDING SHIPMENT TABLE ---------------->
<br>

<!--------------- LATEST SHIPMENT TABLE ---------------->
<table cellSpacing="1" cellPadding="1" border="0" >

	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>VIEW LATEST SHIPMENTS</b></td>
	</tr>
	<tr>
		<td style="width: 100" class="style17" align="center">
			<b>Date Shipped</b></td>
		<td style="width: 100" class="style17" align="center">
			<b>Location</b></td>
		<td style="width: 100" class="style17" align="center">
			<b>Count</b></td>
		<td style="width: 100" class="style17" align="center">
			<b>BOL</b></td>

		<td align="middle" style="width: 100px" class="style16" align="center">
			<b>View Details</b>
		</td>
	</tr>		

	
<?php
$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_files.file_name AS F FROM loop_bol_tracking INNER JOIN loop_bol_files ON loop_bol_tracking.trans_rec_id = loop_bol_files.trans_rec_id WHERE loop_bol_tracking.warehouse_id IN " . $locations_string . " GROUP BY loop_bol_tracking.trans_rec_id ORDER BY loop_bol_tracking.id  DESC";

$res = mysql_query($query,db());
while($row = mysql_fetch_array($res))
{

	?>
		<tr vAlign="center">
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<? echo date('m-d-Y', strtotime($row["B"])); ?></td>
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<? echo number_format($row["A"],0); ?>
			</td>
			<td bgColor="#e4e4e4" class="style3"  align="center">	
				<a href="http://www.usedcardboardboxes.com/ucbloop/bol/<?= $row["F"]; ?>" target=_blank>VIEW BOL</a>
			</td>
			<td bgColor="#e4e4e4" class="style3">	
				<p align="center">	
				<a href="<?=$returnurl;?>?SHIPMENT=<?=$row["C"] ?>" >View Details</a></td>
			</td>
		</tr>
<?
}
?>
</table>
<!--------------- END SHIPMENT TABLE ---------------->
<br>
<?
if ($_REQUEST["SHIPMENT"]>0)
{
$dt_view_qry = "SELECT * FROM loop_bol_tracking WHERE warehouse_id IN " . $locations_string . " AND trans_rec_id = " . $_REQUEST["SHIPMENT"];
$dt_view_res = mysql_query($dt_view_qry,db() );

$dt_view_trl_row = mysql_fetch_array($dt_view_res)
?>
<table cellSpacing="1" cellPadding="1" border="0" width="489">
    <tr align="middle">
      <td class="style7" colspan="2" style="height: 16px"><strong>SHIPMENT 
		DETAILS FOR <?=$dt_view_trl_row["bol_pickupdate"]?></strong></td>
    </tr>
    <tr vAlign="center">
      <td bgColor="#e4e4e4" width="400" class="style17" >
		<p style="text-align: center"><strong>Box Description</strong></td>
      <td bgColor="#e4e4e4" class="style17" width="82" >
		<p style="text-align: center"><strong>Count</strong></td>
    </tr>
<?
$gb = 0;
$bb = 0;
$gbw = 0;
$vob = 0;


$dt_view_qry = "SELECT * FROM loop_bol_tracking INNER JOIN loop_boxes ON loop_bol_tracking.box_id = loop_boxes.id WHERE loop_bol_tracking.warehouse_id IN " . $locations_string . " AND loop_bol_tracking.trans_rec_id = " . $_REQUEST["SHIPMENT"];
$dt_view_res = mysql_query($dt_view_qry,db() );

while ($dt_view_row = mysql_fetch_array($dt_view_res)) {

	if ($dt_view_row["qty"] > 0) 
	{
?>
		<tr>
      <td bgColor="#e4e4e4" class="style12left" >
      <? echo $dt_view_row["blength"];?> <? echo $dt_view_row["blength_frac"];?> 
		x
      <? echo $dt_view_row["bwidth"];?> <? echo $dt_view_row["bwidth_frac"];?> x 
      <? echo $dt_view_row["bdepth"];?> <? echo $dt_view_row["bdepth_frac"];?>
      <? echo $dt_view_row["bdescription"];?></td>
      <td bgColor="#e4e4e4" class="style12right" width="82" ><? echo number_format($dt_view_row["qty"],0);?></td>
	  </tr>
	
	
<? 
	$gb += $dt_view_row["qty"];

	}
	} ?>	

		<tr>
      <td bgColor="#e4e4e4" class="style12" >&nbsp;</td>
      <td bgColor="#e4e4e4" class="style12right" width="82" ><strong><? echo number_format($gb,0);?></strong></td>
	  </tr>

		

</table>


<? } ?>





<!--------------- BEGIN IN PROCESS TABLE ---------------->
<!--------------- END IN PROCESS TABLE ---------------->

<br>


</td><td width="26">
	&nbsp;
</td><td valign="top" width="292">

<form name="rptSearch" action="<?=$returnurl;?>" method="GET" target="_blank">
<span class="style2">


<span class="style13"><span class="style15">

<table cellSpacing="1" cellPadding="1" border="0" width="550">

	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>VIEW HISTORICAL SHIPMENTS (will appear in a new window)</b></td>
	</tr>
	<tr align="middle">
		<td colSpan="10" class="style17">



<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
<script LANGUAGE="JavaScript">
	var cal1xx = new CalendarPopup("listdiv");
	cal1xx.showNavigationDropdowns();
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
</script>
<?
$start_date = isset($_REQUEST["start_date"])?strtotime($_REQUEST["start_date"]):strtotime(date('m/d/Y'));
$end_date = isset($_REQUEST["end_date"])?strtotime($_REQUEST["end_date"]):strtotime(date('m/d/Y'));
?>
	

<input type="text" name="start_date" size="11" value="<?=(isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "")?date('m/d/Y', $start_date):date('m/01/Y')?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a> <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and: 
<input type="text" name="end_date" size="11" value="<?=(isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "")?date('m/d/Y', $end_date):date('m/d/Y')?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
</td></tr>
<tr><td bgColor="#e4e4e4" class="style12center">
&nbsp; <input type="submit" value="Search">
  <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
</td></tr></table>
<input type="hidden" name="action" value="run">
</form>

<!------------------ END PROCESSED TRAILERS -------------------->

</td>
</tr></table>














</body>
</html>