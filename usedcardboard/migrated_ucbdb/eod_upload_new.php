<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DASH - End of Day Entry</title>
<style type="text/css"> 
.style1 {
	font-size: xx-small;
	background-color: #FF9933;
}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	text-align: center;
	background-color: #e4e4e4;
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
.style4 {
	text-align: left;
}
.style7 {
	background-color: #99FF99;
}
.style8 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style9 {
	text-align: center;
	background-color: #99FF99;
}
.style10 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: xx-small;
}
.style11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: xx-small;
	text-align: center;
}
</style>


<script>
	function getorderno()
	{
		
		//document.getElementById("warehouse_name").value
		
	}
</script>

</head>

<body>



<font face="Arial, Helvetica, sans-serif" color="#333333" size="2">
<a href="index.php">Home</a></span><br>
<br />


<?php 



?>
<form action="addeod_new.php" method="post" encType="multipart/form-data" name="rptSearch">


  
<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr align="middle">
		<td colSpan="3" class="style1"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		UPDATE END OF DAY ORDER</td>
	</tr>
	<tr>
		<td class="style5" style="width: 7%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Warehouse</td>
		<td class="style5" style="width: 7%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Order Number</td>		
		<td class="style5" style="width: 24%; height: 16px;"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Date</td>
	</tr>
	


	
	<tr vAlign="center">
		<td bgColor="#e4e4e4" style="width: 7%" class="style10">
		<select name="warehouse_name" id="warehouse_name" onchange="getorderno()">
			<?php  
			$sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
			$result2 = db_query($sql2,db() );

			echo "<option></option>";
			while ($myrowsel2 = array_shift($result2)) {
				?>
				<option value="<?php  echo $myrowsel2["id"]; ?>"><?php  echo $myrowsel2["distribution_center"]; ?></option>
			<?php  
			} ?>
		</select>
</td>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
<script LANGUAGE="JavaScript">
	var cal1xx = new CalendarPopup();
	cal1xx.showNavigationDropdowns();
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
</script>
		<td bgColor="#e4e4e4" class="style5" style="width: 24%">
			<input size="20 "type="text" style="width: 66px" name="order_no">
		</td>
		<td bgColor="#e4e4e4" style="width: 7%" class="style10">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Date: <input type="text" name="eod_date" size="11" value="<?php  echo date('m/d/y'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.search_date,'anchor1xx','MM/dd/yy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="style6" align="center">		
			<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		<input type="submit" value="Submit EOD">
		</td>
	</tr>
</table>
</form>

  <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
  
<br />
<br />
<br />

<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr align="middle">
		<td bgColor="#ffcccc" colSpan="3" class="style1"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		END OF DAYS SUBMITTED TODAY <?php  echo date('m/d/y'); ?></td>
	</tr>
	<tr>
		<td bgColor="#d9f2ff" class="style5" style="width: 13%; height: 16px;">
		Warehouse</td>
		<td bgColor="#d9f2ff" class="style5" style="width: 24%; height: 16px;">
		Order Number</td>
		<td class="style5" bgColor="#d9f2ff" style="width: 20%; height: 16px;" class="style2">
		Date</td>
	</tr>
<?php 
$sql3 = "SELECT * FROM ucbdb_endofday WHERE substr(import_date,1,8) = '" . date('m/d/y') . "'";
$result3 = db_query($sql3,db() );
while ($myrowsel3 = array_shift($result3)) {	
?>
	<tr vAlign="center">
		<td bgColor="#e4e4e4" style="width: 13%" class="style10">
		<?php  echo $myrowsel3["warehouse_name"] ?></td>
		<td align="middle" bgColor="#e4e4e4" class="style10" style="width: 24%">
		<?php  echo $myrowsel3["order_no"] ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style10">
		<?php  echo $myrowsel3["search_date"] ?></td>
	</tr>
<?php  } ?>
	</table>



</body>

</html>
