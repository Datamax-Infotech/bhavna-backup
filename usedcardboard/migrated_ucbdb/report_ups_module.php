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
	<title>DASH - Reports - UPS Status</title>

	
	
	
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


<form name="rptSearch" action="report_ups_module.php" method="GET">
<input type="hidden" name="action" value="run">
<!--<span class="style2">
<a href="index.php">Home</a></span><br>-->
<br>

<span class="style13"><span class="style15">

<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
UPS Delivery Report
	</font></span><span class="style14"><span class="style15">\




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

?>


<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr align="middle">
		<td colSpan="10" class="style7">
		UPS STATUS REPORT</td>
	</tr>
	<tr align="middle">
		<td colSpan="10">
		</td>
	</tr>	








<?php 

$start_date = date('Ymd', $start_date);
$end_date = date('Ymd', $end_date + 86400);

if ($start_date > $end_date) { echo "<font size=20>The start date comes after the end date.  This could never happen in reality.  Please try again.<br><br></font>"; exit; }

$mod_sql = "SELECT name FROM module WHERE name != 'NoProcess'";
$mod_res = db_query($mod_sql,db());
while($mod_row = array_shift($mod_res))
{

?>


	<tr>
		<td class="style7" colspan="6" align="center">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Module Type: <?php  echo $mod_row['name']; ?></td>
	</tr>

	<tr>
		<td style="width: 20%" class="style5">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		WAREHOUSE</font></td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		MANIFEST</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		IN TRANSIT</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		EXCEPTION</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		DELIVERED</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		TOTAL</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		TOTAL NEW</td>

	</tr>



<?php 

$war_sql = "SELECT * FROM warehouse";
$war_res = db_query($war_sql,db());
while($war_row = array_shift($war_res))
{
$warehouse = $war_row['name'];


$sql_1 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND module_name = '" . $mod_row['name'] . "' AND (status = 'Manifest Pickup' OR fedex_status = 'OC')";
if($_GET["start_date"] != "")
{
	$sql_1.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_1.= " AND print_date<='$end_date'";
}//echo $sql_1 . "<br>";
$sql_1_res = db_query($sql_1,db());
$sql_1_rows = tep_db_num_rows($sql_1_res);

$sql_2 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND module_name = '" . $mod_row['name'] . "' AND (status = 'In Transit' OR (fedex_status !='DE' AND fedex_status !='DL' AND fedex_status != 'OC' AND fedex_status !='')) ";
if($_GET["start_date"] != "")
{
	$sql_2.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_2.= " AND print_date<='$end_date'";
}
$sql_2_res = db_query($sql_2,db());
$sql_2_rows = tep_db_num_rows($sql_2_res);

$sql_3 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND module_name = '" . $mod_row['name'] . "' AND (status = 'Exception' OR fedex_status = 'DE') ";
if($_GET["start_date"] != "")
{
	$sql_3.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_3.= " AND print_date<='$end_date'";
}
$sql_3_res = db_query($sql_3,db());
$sql_3_rows = tep_db_num_rows($sql_3_res);

$sql_4 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND module_name = '" . $mod_row['name'] . "' AND (status = 'Delivered' OR setignore = 1 OR fedex_status = 'DL') ";
if($_GET["start_date"] != "")
{
	$sql_4.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_4.= " AND print_date<='$end_date'";
}
$sql_4_res = db_query($sql_4,db());
$sql_4_rows = tep_db_num_rows($sql_4_res);


$sql_5 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND module_name = '" . $mod_row['name'] . "'  ";
if($_GET["start_date"] != "")
{
	$sql_5.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_5.= " AND print_date<='$end_date'";
}
$sql_5_res = db_query($sql_5,db());
$sql_5_rows = tep_db_num_rows($sql_5_res);



?>


	<tr>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php  echo $warehouse; ?></font></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<a href="report_ups_status_drill.php?warehouse_id=<?php  echo $war_row['warehouse_id']; ?>&module_name=<?php  echo $mod_row['name']; ?>&status=Manifest Pickup<?php  if ($_GET['start_date'] != '') { echo "&start_date=" . $start_date; } ?><?php  if ($_GET['end_date'] != '') { echo "&end_date=" . $end_date; } ?>"><?php  echo $sql_1_rows; ?></a></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<a href="report_ups_status_drill.php?warehouse_id=<?php  echo $war_row['warehouse_id']; ?>&module_name=<?php  echo $mod_row['name']; ?>&status=In Transit<?php  if ($_GET['start_date'] != '') { echo "&start_date=" . $start_date; } ?><?php  if ($_GET['end_date'] != '') { echo "&end_date=" . $end_date; } ?>"><?php  echo $sql_2_rows; ?></a></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<a href="report_ups_status_drill.php?warehouse_id=<?php  echo $war_row['warehouse_id']; ?>&module_name=<?php  echo $mod_row['name']; ?>&status=Exception<?php  if ($_GET['start_date'] != '') { echo "&start_date=" . $start_date; } ?><?php  if ($_GET['end_date'] != '') { echo "&end_date=" . $end_date; } ?>"><?php  echo $sql_3_rows; ?></a></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<a href="report_ups_status_drill.php?warehouse_id=<?php  echo $war_row['warehouse_id']; ?>&module_name=<?php  echo $mod_row['name']; ?>&status=Delivered<?php  if ($_GET['start_date'] != '') { echo "&start_date=" . $start_date; } ?><?php  if ($_GET['end_date'] != '') { echo "&end_date=" . $end_date; } ?>"><?php  echo $sql_4_rows; ?></a></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo ($sql_1_rows+$sql_2_rows+$sql_3_rows+$sql_4_rows); ?></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo ($sql_5_rows); ?></td>

	</tr>







<?php  } ?>

<?php  } ?>

</table>

<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr align="middle">
		<td colSpan="10" class="style7">
		TOTALS</td>
	</tr>
	<tr>
		<td style="width: 20%" class="style5">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		WAREHOUSE</font></td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		MANIFEST</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		IN TRANSIT</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		EXCEPTION</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		DELIVERED</td>
		<td class="style5" style="width: 20%">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		TOTAL</td>

	</tr>

<?php 

$war_sql = "SELECT * FROM warehouse";
$war_res = db_query($war_sql,db());
while($war_row = array_shift($war_res))
{
$warehouse = $war_row['name'];


$sql_1 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND (status = 'Manifest Pickup' OR fedex_status = 'OC')";
if($_GET["start_date"] != "")
{
	$sql_1.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_1.= " AND print_date<='$end_date'";
}
$sql_1_res = db_query($sql_1,db());
$sql_1_rows = tep_db_num_rows($sql_1_res);

$sql_2 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND (status = 'In Transit' OR (fedex_status !='DE' AND fedex_status !='DL' AND fedex_status != 'OC' AND fedex_status !='')) ";
if($_GET["start_date"] != "")
{
	$sql_2.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_2.= " AND print_date<='$end_date'";
}
$sql_2_res = db_query($sql_2,db());
$sql_2_rows = tep_db_num_rows($sql_2_res);

$sql_3 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND (status = 'Exception' OR fedex_status = 'DE') ";
if($_GET["start_date"] != "")
{
	$sql_3.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_3.= " AND print_date<='$end_date'";
}
$sql_3_res = db_query($sql_3,db());
$sql_3_rows = tep_db_num_rows($sql_3_res);

$sql_4 = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $war_row['warehouse_id'] . " AND (status = 'Delivered' OR fedex_status = 'DL') ";
if($_GET["start_date"] != "")
{
	$sql_4.= " AND print_date>='$start_date'";
}
if($_GET["end_date"] != "")
{
	$sql_4.= " AND print_date<='$end_date'";
}
$sql_4_res = db_query($sql_4,db());
$sql_4_rows = tep_db_num_rows($sql_4_res);

?>


	<tr>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php  echo $warehouse; ?></font></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo $sql_1_rows; ?></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo $sql_2_rows; ?></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		\<?php  echo $sql_3_rows; ?></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo $sql_4_rows; ?></td>
		<td class="style11">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		<?php  echo ($sql_1_rows+$sql_2_rows+$sql_3_rows+$sql_4_rows); ?></td>


	</tr>




<?php  } ?>





<?php  } ?>
	</div>
</body>
</html>
