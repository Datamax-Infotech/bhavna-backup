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
	<title>DASH - Reports - Time Clock</title>

	
	
	
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

<?php 
// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>


<BR>


<form name="rptSearch" action="report_timeclock.php" method="GET">
<input type="hidden" name="action" value="run">
<span class="style2">
<a href="index.php">Home</a></span><br>
<br>

<span class="style13"><span class="style15">

<br /><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
Find 
<select name="worker"><option>Please Select</option>
<?php  

$sql3 = "SELECT * FROM loop_workers";
$result3 = db_query($sql3,db() );
while ($myrowsel3 = array_shift($result3)) {
?>
<option value="<?php  echo $myrowsel3["id"]; ?>" <?php  if ($myrowsel3["id"]==$_REQUEST["worker"]) echo "selected"; ?>><?php  echo $myrowsel3["name"]; ?></option>
<?php  } ?>
</select>from
</span><span class="style14"><span class="style15">




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

<br></span></span></span>

<?php 

if ($_GET["action"] == 'run') {
$start_date = date('Y-m-d', $start_date);
$end_date = date('Y-m-d', $end_date + 86400);

if ($start_date > $end_date) {
echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
}

?>

<?php 


// echo $query_clicks;
?>

<table cellSpacing="1" cellPadding="1" width="800" border="0">
	<tr align="middle">
		<td colSpan="10" class="style7">
		TIMECLOCK REPORT FOR: 

<?php 
$query = "SELECT * FROM `loop_workers` WHERE id = " . $_REQUEST["worker"] ;
$res = db_query($query,db());
$row = array_shift($res);
echo "<b>".$row["name"]."</b>";
?>
</td>
	</tr>
	<tr>
		<td class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		DATE</font></td>
		<td class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		TIME IN</font></td>
		<td class="style5" >
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		TIME OUT</td>
		<td align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		AMOUNT</td>
		<td align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		EDIT</td>
		<td align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		NOTES</td>
	</tr>
	
<?php 
$query = "SELECT *, TIMEDIFF(time_out,time_in) AS A, DATE_FORMAT(time_in, '%W, %M %d, %Y') AS D, TIME(time_in) AS T_I, TIME(time_out) AS T_O FROM loop_timeclock WHERE worker_id = " . $_REQUEST["worker"] . " ";
if($_GET["start_date"] != "")
{
 $query .= " AND time_in BETWEEN '$start_date'";
}
if($_GET["end_date"] != "")
{
 $query .= " AND '$end_date'";
}
// echo $query;
$res = db_query($query,db());
while($row = array_shift($res))
{

?>


		<tr vAlign="center">
			<td bgColor="#e4e4e4" class="style3" style="height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["D"]; ?></td>
			<td bgColor="#e4e4e4" class="style3" style="height: 22px;" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["T_I"]; ?></td>
			<td bgColor="#e4e4e4" style="height: 22px;" class="style3" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["T_O"]; ?></td>

			<td bgColor="#e4e4e4" style="height: 22px;" class="style3" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["A"];?></td>
			<td bgColor="#e4e4e4" style="height: 22px;" class="style3" align=center><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<a href="report_timeclock.php?worker=<?php echo $_REQUEST["worker"];?>&action=run&edit=true&id=<?php  echo $row["id"];?>&start_date=<?php echo $_REQUEST["start_date"];?>&end_date=<?php echo $_REQUEST["end_date"];?>">Edit</a></td>
			<td bgColor="#e4e4e4" style="height: 22px;" class="style3" align=left><font size=1><?php  if ($row["time_in_old"] != "0000-00-00 00:00:00") echo $row["time_in_old"];?> <?php   if ($row["time_out_old"] != "0000-00-00 00:00:00") echo $row["time_out_old"];?> <?php  echo $row["notes"];?></font></td>
		
		</tr>
<?php 
 
}
$query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(time_out,time_in)))) AS ADT FROM loop_timeclock WHERE worker_id = " . $_REQUEST["worker"] . " ";
if($_GET["start_date"] != "")
{
 $query .= " AND time_in BETWEEN '$start_date'";
}
if($_GET["end_date"] != "")
{
 $query .= " AND '$end_date'";
}
$res = db_query($query,db());
while($row = array_shift($res))
{
?>
<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		 </td>
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $total_orders; ?></td>
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		Total Hours</td>
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;" align=right><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		<?php  echo $row["ADT"]; ?></td>
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		 </td>
		<td bgColor="#e4e4e4" class="style3" style="height: 22px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
		 </td>
	</tr>
	

</table>


<?php  
}
}
?>
<br><br><br>
<?php 

if ($_REQUEST["edit"]=="true")
{
$query = "SELECT * FROM loop_timeclock WHERE id = ".$_REQUEST["id"];
$res = db_query($query,db());
while($row = array_shift($res))
{
?>
<form name="rptSearch2" action="report_timeclock.php" method="GET">
<input type="hidden" name="action" value="update">
<input type="hidden" name="edit" value="true">
<input type="hidden" name="timeclockid" value="<?php echo $_REQUEST["id"]?>">
<input type="hidden" name="time_in_old" value="<?php echo $row["time_in"]?>">
<input type="hidden" name="time_out_old" value="<?php echo $row["time_out"]?>">
<table>
<tr align="middle">
		<td colSpan="10" class="style7">
		UPDATE TIMESHEET </td>
</tr>
<tr>
<td bgColor="#e4e4e4">Employee</td><td bgColor="#e4e4e4">Time In</td><td bgColor="#e4e4e4">Time Out</td><td bgColor="#e4e4e4">New Time In</td><td bgColor="#e4e4e4">New Time Out</td><td bgColor="#e4e4e4">Notes</td>
</tr>

<tr>
<td bgColor="#e4e4e4"><select name="worker"><option>Please Select</option>
<?php  

$sql3 = "SELECT * FROM loop_workers";
$result3 = db_query($sql3,db() );
while ($myrowsel3 = array_shift($result3)) {
?>
<option value="<?php  echo $myrowsel3["id"]; ?>" <?php  if ($myrowsel3["id"]==$_REQUEST["worker"]) echo "selected"; ?>><?php  echo $myrowsel3["name"]; ?></option>
<?php  } ?>
</select></td><td bgColor="#e4e4e4"><?php echo $row["time_in"];?></td><td bgColor="#e4e4e4"><?php echo $row["time_out"];?></td><td bgColor="#e4e4e4"><input name=new_time_in value="<?php echo $row["time_in"];?>"></td><td bgColor="#e4e4e4"><input name=new_time_out value="<?php echo $row["time_out"];?>"></td><td bgColor="#e4e4e4"><input size=25 name=notes value="<?php echo $row["notes"];?>"></td>
</tr>
<tr>
<td bgColor="#e4e4e4" colspan=10 align=center>
<input type=submit value="Update">
</td>
</tr>
</table>
</form>

<?php 

}
}
if ($_REQUEST["action"]=="update")
{
$sql3 = "UPDATE loop_timeclock SET time_in = '" . $_REQUEST["new_time_in"] . "', time_out = '" . $_REQUEST["new_time_out"] . "', time_in_old = '" . $_REQUEST["time_in_old"] . "', time_out_old = '" . $_REQUEST["time_out_old"] . "', worker_id = '" . $_REQUEST["worker"] . "', notes = '" . $_REQUEST["notes"] . "' WHERE id = " . $_REQUEST["timeclockid"];
$result3 = db_query($sql3,db() );
$myrowsel3 = array_shift($result3);
;

		$message_123 = "The following change was made to the timeclock: ";
		$message_123 .= "<br><br>Worker ID: " . $_REQUEST["worker"] . "\n\n";
		$message_123 .= "<br><br>Transaction ID: " . $_REQUEST["timeclockid"] . "\n\n";
		$message_123 .= "<br><br>Old Time In: " . $_REQUEST["time_in_old"] . "\n\n";
		$message_123 .= "<br><br>New Time In: " . $_REQUEST["new_time_in"] . "\n\n";
		$message_123 .= "<br><br>Old Time Out: " . $_REQUEST["time_out_old"] . "\n\n";
		$message_123 .= "<br><br>New Time Out: " . $_REQUEST["new_time_out"] . "\n\n";
		$message_123 .= "<br><br>Notes: " . $_REQUEST["notes"] . "\n\n";
		$message_123 .= "<br><br><a href=\"http://b2c.usedcardboardboxes.com/report_timeclock.php?action=run&worker=" . $_REQUEST["worker"] . "&start_date=&end_date=\">Check </a>\n";
		$headers_123  = "MIME-Version: 1.0\r\n"; 
		$headers_123 .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		$headers_123 .= "From: UCB Website <no-reply@usedcardboardboxes.com>\r\n"; 
		$to_123 = "davidkrasnow@usedcardboardboxes.com";
//		$to_123 = "mdewan@tivex.com";
		mail($to_123, 'TIME CLOCK EDIT', $message_123, $headers_123); 


 

}


?>



</body>
</html>
