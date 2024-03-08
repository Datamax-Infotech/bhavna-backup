<?php  
require ("inc/header_session.php");
?>

<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - Reports - Credits</title>

	
	
	
<style type="text/css">
.style7 {
	font-size: xx-small;
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
.style13 {
	font-family: Arial, Helvetica, sans-serif;
}
.style14 {
	font-size: x-small;
}
.style15 {
	font-size: small;
}
.style16 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style17 {
	background-color: #99FF99;
}
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 10px; 
color : #000000; 
font-weight: normal; 
}
</style>	


</head>

<body>
<div>
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">
<?php 
// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>


<BR>


<form name="rptSearch" action="report_credit.php" method="GET">
<input type="hidden" name="action" value="run">
<!--<span class="style2">
<a href="index.php">Home</a></span><br>-->
<br>

<span class="style13"><span class="style15">

<br /><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
Find Credits from
</span><span class="style14"><span class="style15">


<select name="warehouse">
<option value="A"<?php echo ($warehouse == 'A')?' selected':''?>>Any Warehouse</option>
<?php  

$sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {

?>
<option value="<?php  echo $myrowsel2["distribution_center"]; ?>"><?php  echo $myrowsel2["distribution_center"]; ?></option>
<?php  } ?>
</select>
<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">with&nbsp;
<select name="reason_code">
<option value="A"<?php echo ($reason_code == 'A')?' selected':''?>>Any Reason</option>
<?php  

$sql3 = "SELECT * FROM ucbdb_reason_code ORDER BY rank";
$result3 = db_query($sql3,db() );
while ($myrowsel3 = array_shift($result3)) {
?>
<option value="<?php  echo $myrowsel3["reason"]; ?>"><?php  echo $myrowsel3["reason"]; ?></option>
<?php  } ?>
</select>, show only&nbsp;
<select name="chargeback">
<option value="A" selected>Any Chargeback</option>
<option value="Yes">Only Chargebacks</option>
<option value="No">Not Chargebacks</option>
</select>

<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">that were 
<select name="pending">
<option value="A"<?php echo ($pending == 'A')?' selected':''?>>Any Status</option>
<option value="Pending"<?php echo ($pending == 'Pending')?' selected':''?>>Pending</option>
<option value="Processed"<?php echo ($pending == 'Processed')?' selected':''?>>Processed</option>
<option value="Denied"<?php echo ($pending == 'Denied')?' selected':''?>>Denied</option>
</select>


<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
<script LANGUAGE="JavaScript">
	var cal1xx = new CalendarPopup("listdiv");
	cal1xx.showNavigationDropdowns();
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
</script>
<?php 
$start_date = isset($_GET["start_date"])?strtotime($_GET["start_date"]):strtotime(date('m/d/Y'));
$end_date = isset($_GET["end_date"])?strtotime($_GET["end_date"]):strtotime(date('m/d/Y'));
?>
	
<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from: <input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "")?date('m/d/Y', $start_date):""?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a> <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to: <input type="text" name="end_date" size="11" value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "")?date('m/d/Y', $end_date):""?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>


&nbsp; <input type="submit" value="Search"></form>
  <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

<br></span></span></span><br>
<br />

<?php 

if ($_GET["action"] == 'run') {
$start_date = date('Ymd', $start_date);
$end_date = date('Ymd', $end_date);

if ($start_date > $end_date) {
echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
}

?>




<table cellSpacing="1" cellPadding="1" width="98%" border="0">
	<tr align="middle">
		<td colSpan="10" class="style7">
		CREDIT REPORT</td>
	</tr>
	<tr>
		<td style="width: 7%" class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		DATE</font></td>
		<td style="width: 7%" class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER ID</font></td>
		<td class="style5" style="width: 9%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		ITEM</td>
		<td style="width: 5%" class="style6">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		DISTRIBUTION CENTER</font></td>
		<td class="style5" style="width: 12%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		REASON</td>
		<td align="middle" style="width: 5%" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		CHARGEBACK</td>
		<td align="middle" style="width: 11%" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		QUANTITY</td>
		<td align="middle" class="style16" style="width: 50%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		NOTES</td>
		<td align="middle" style="width: 28%" class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PROCESS</font></td>
		<td align="middle" style="width: 5%" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		AMOUNT</td>
	</tr>
	
<?php 
$query = "SELECT * FROM ucbdb_credits WHERE total > 0";
if($reason_code != 'A')
{
	$query.= " AND reason_code='$reason_code'";
}
if($warehouse != 'A')
{
	$query.= " AND warehouse='$warehouse'";
}
if($chargeback != 'A')
{
	$query.= " AND chargeback='$chargeback'";
}
if($pending != 'A')
{
	$query.= " AND pending='$pending'";
}
if($_GET["start_date"] != "")
{
	$query.= " AND credit_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$query.= " AND credit_date<='$end_date'";
}
$res = db_query($query,db());
while($row = array_shift($res))
{
?>	
	
	<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo date('m-d-Y', strtotime($row["credit_date"])); ?></td>
		<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<a href="orders.php?id=<?php  echo $row["orders_id"]; ?>&proc=View&"><?php  echo $row["orders_id"]; ?></a></td>
		<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["item_name"]; ?></td>
		<td bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["warehouse"]; ?></td>
		<td bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["reason_code"]; ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["chargeback"]; ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["quantity"]; ?></td>
		<td bgColor="#e4e4e4" class="style8" style="width: 50%; height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["notes"]; ?></td>
		<td bgColor="#e4e4e4" style="width: 28%; height: 22px;" class="style11"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["pending"]; ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["total"]; ?></td>
	</tr>

<?php  
}
?>

	

</table>


<?php  
}
?>
	</div>


</body>
</html>
