<?php  
require ("inc/header_session.php");
?>

<!DOCTYPE html>

<html>
<head>
	<title>DASH - Search Order Issues</title>
</head>

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

<body>

<?php 

echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");



echo "<a href=\"index.php\">Home</a><br><br>";

?>
	
	<form method="POST" action="<?php  echo $thispage; ?>?posting=yes&<?php  echo $pagevars; ?>">
		<font size=1><p><B>Search:</B></font> <input type="text" CLASS='TXT_BOX' name="searchorder" size="20" value="<?php  echo $_REQUEST["searchorder"]; ?>">
		<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search Again" NAME="B1"></P>
	</form>
	
<?php 
	db();
	
	$orders_id = 0;
	$sql = "Select orders_id from ucbdb_crm where message like '%Attention%' and orders_id = ? group by orders_id";
	$result = db_query($sql, array("i"), array($_REQUEST["searchorder"]));
	while($myrowsel = array_shift($result)) {
		$orders_id = $myrowsel["orders_id"];
	}
	
	$find_it = "SELECT OI.id, OI.orders_id, OI.issue, OI.assigned_to, OI.assigned_by, OI.when_assigned, O.date_purchased, O.customers_name, O.customers_email_address, O.customers_telephone FROM ucbdb_issue OI INNER JOIN orders O on OI.orders_id = O.orders_id WHERE OI.orders_id = '$orders_id' ORDER BY O.date_purchased";	
	//echo $find_it;
	$find_it_result = db_query($find_it);
?>

<table cellSpacing="1" cellPadding="1" width="98%" border="0">
	<tr align="middle">
		<td colSpan="9" class="style1">
		<font face="Arial, Helvetica, sans-serif" color="#333333">
		ORDER PROBLEMS</font></td>
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
		PROBLEM DATE</td>
		<td class="style5" style="width: 6%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PROBLEM AGE </td>
		<td style="width: 11%; height: 16px;" class="style5">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		NAME</a></font></td>
		<td class="style5" style="width: 14%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		EMAIL</td>
		<td align="middle" style="width: 11%; height: 16px;" class="style8">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		PHONE</td>
		<td style="width: 6%; height: 16px;" class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<div align="center">ASSIGNED BY</div></font></td>
		<td style="width: 6%; height: 16px;" class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		<div align="center">ASSIGNED TO</div></font></td>
		
	</tr>
<?php  
while ($report_data = array_shift($find_it_result)) {
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
$dq = $report_data["when_assigned"];
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
		<td align="middle" bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style3">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["assigned_by"]; ?></font></td>
		<td align="middle" bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style3">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["assigned_to"]; ?></font></td>

	</tr>
<?php  } ?>
</table>
 
</body>
</html>
