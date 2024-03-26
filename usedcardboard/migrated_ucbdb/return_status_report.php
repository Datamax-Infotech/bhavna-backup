<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - Order Issues</title>
<style type="text/css"> 
.style1 {
	font-size: xx-small;
	background-color: #FF9933;
}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
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

</head>

<body>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
<div class="main_data_css">
<?php 

$returns = "SELECT * FROM orders_active_export INNER JOIN orders ON orders_active_export.orders_id = orders.orders_id WHERE orders_active_export.return_tracking_number != ''";
$returnsres = db_query($returns);
$returnsresnum = tep_db_num_rows($returnsres);
?>


<span class="style2">
<!-- <a href="javascript: history.go(-1)">Back</a> --> <!--<a href="index.php">Home</a>--></span>
 
 
<br />
<table cellSpacing="1" cellPadding="1" width="700" border="0">
	<tr align="middle">
		<td colSpan="7" class="style1">
		<font face="Arial, Helvetica, sans-serif" color="#333333">
		PENDING RETURNS</font></td>
	</tr>
	<tr>
		<td style="width: 6%; height: 16px;" class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER ID</font></td>
		<td class="style5" style="width: 6%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER DATE</td>
		<td style="width: 11%; height: 16px;" class="style9">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		NAME</a></font></td>
		<td class="style5" style="width: 14%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		EMAIL</td>
		<td align="middle" style="width: 11%; height: 16px;" class="style8">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PHONE</td>
</td>
		
	</tr>
<?php  
while ($report_data = array_shift($returnsres)) {
?>
	<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style3" style="width: 6%; height: 22px;">		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<a href="orders.php?id=<?php  echo encrypt_url($report_data["orders_id"]); ?>&proc=View&searchcrit=&page=0"><?php  echo $report_data["orders_id"]; ?></a></td>
		<td bgColor="#e4e4e4" style="width: 6%; height: 22px;">
		
<?php  
$dp = $report_data["date_purchased"];
$order_date = date("F j Y", strtotime($dp)); 
?>


		
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php  echo $order_date; ?> </font></td>
				<td bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style4">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><div align="center"><?php  echo $report_data["customers_name"]; ?></div> </font></td>

		<td bgColor="#e4e4e4" style="width: 14%; height: 22px;" class="style11">
		<?php  echo $report_data["customers_email_address"]; ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style3">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["customers_telephone"]; ?></font></td>


	</tr>
<?php  } ?>
</table>
 

	</div>

</body>
</html>
