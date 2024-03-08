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
<?php 

$who = $_GET["term"];
if ($who != 'All') 
{
//$find_it = "SELECT OI.id, OI.orders_id, OI.issue, OI.assigned_to, OI.assigned_by, OI.when_assigned, O.date_purchased, O.customers_name, O.customers_email_address, O.customers_telephone FROM ucbdb_issue OI INNER JOIN orders O on OI.orders_id = O.orders_id WHERE OI.assigned_to = '" . $_REQUEST["term"] . "'  AND OI.issue = 'Attention' ORDER BY O.date_purchased";
}
else
{
//$find_it = "SELECT OI.id, OI.orders_id, OI.issue, OI.assigned_to, OI.assigned_by, OI.when_assigned, O.date_purchased, O.customers_name, O.customers_email_address, O.customers_telephone FROM ucbdb_issue OI INNER JOIN orders O on OI.orders_id = O.orders_id WHERE OI.issue = 'Attention' ORDER BY O.date_purchased";
}
$find_it = "SELECT OI.id, OI.orders_id, OI.issue, OI.assigned_to, OI.assigned_by, OI.when_assigned, O.date_purchased, O.customers_name, O.customers_email_address, O.customers_telephone FROM ucbdb_issue OI INNER JOIN orders O on OI.orders_id = O.orders_id WHERE O.order_issue = 1 ORDER BY O.date_purchased";

$find_it_result = db_query($find_it, db());
$find_it_rows = tep_db_num_rows($find_it_result);
?>


<span class="style2">
<a href="index.php">Home</a></span>
 
<br />
 
<br />
<table cellSpacing="1" cellPadding="1" width="98%" border="0">
	<tr align="middle">
		<td colSpan="9" class="style1">
		<font face="Arial, Helvetica, sans-serif" color="#333333">
		Order Issues</font></td>
	</tr>
	<tr>
		<td style="width: 6%; height: 16px;" class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER ID</font></td>
		<td class="style5" style="width: 6%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER DATE</td>
		<td class="style5" style="width: 6%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Issues DATE</td>
		<td class="style5" style="width: 6%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Issues AGE </td>
		<td style="width: 11%; height: 16px;" class="style9">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		NAME</a></font></td>
		<td class="style5" style="width: 14%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		EMAIL</td>
		<td align="middle" style="width: 11%; height: 16px;" class="style8">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PHONE</td>
		
	</tr>
<?php  
while ($report_data = array_shift($find_it_result)) {
	
	$order_issue_start_date_time = "";
	$find_it_child = "SELECT * from b2c_order_issue where order_id = " . $report_data["orders_id"];
	$find_it_result_child = db_query($find_it_child, db());
	while ($report_data_child = array_shift($find_it_result_child)) {
		$order_issue_start_date_time = $report_data_child["order_issue_start_date_time"];
	}
?>
	<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style3" style="width: 6%; height: 22px;">		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<a href="orders.php?id=<?php  echo $report_data["orders_id"]; ?>&proc=View&searchcrit=&page=0"><?php  echo $report_data["orders_id"]; ?></a></td>
		<td bgColor="#e4e4e4" style="width: 6%; height: 22px;">
		
<?php  
$dp = $report_data["date_purchased"];
$order_date = date("F j Y", strtotime($dp)); 
?>


		
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php  echo $order_date; ?> </font></td>
		<td bgColor="#e4e4e4" style="width: 6%; height: 22px;">
		
<?php  
$dq = $order_issue_start_date_time;
$problem_date = date("F j Y", strtotime($dq)); 
?>


		
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<?php  echo $problem_date; ?> </font></td>		
		<td bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style4" align="center">
<?php  
$dz = strtotime($problem_date) - strtotime($order_date);
// $dz = $problem_date - $order_date;
$factor = 86400;
$difference = ($dz / $factor);
//$date_path = date("F j Y", strtotime($dz)); 
//$difference = $dz;
?>


		
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><div align="center"><?php  echo number_format($difference, 0) ?></div> </font></td>
		<td bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style4">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><div align="center"><?php  echo $report_data["customers_name"]; ?></div> </font></td>

		<td bgColor="#e4e4e4" style="width: 14%; height: 22px;" class="style11">
		<?php  echo $report_data["customers_email_address"]; ?></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style3">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["customers_telephone"]; ?></font></td>

	</tr>
<?php  } ?>
</table>
 



</body>
</html>
