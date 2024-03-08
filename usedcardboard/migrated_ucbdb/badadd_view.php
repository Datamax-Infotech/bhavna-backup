<?php  
require ("inc/header_session.php");
?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - Bad Addresses</title>


<?php 
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>



<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");





?>


</HEAD>
<BODY>

<a href="index.php">Home</a> <br>
<br>

<table cellSpacing="1" cellPadding="1" border="0" width="200" >
	<tr align="middle">
		<td class="style24" style="height: 16px">
		<strong>Order ID</strong></td>
	</tr>

<?php 
$today = date("Ymd"); 

$number = $_GET['number'];

$sql3 = "SELECT * FROM orders WHERE delivery_street_address = '' ORDER BY orders_id DESC LIMIT 0, " . $number ;
$result3 = db_query($sql3,db() );
while($result3data = array_shift($result3))
{
?>

	<tr vAlign="center">
		<td bgColor="#e4e4e4" class="style12" style="width: 200">
		<a href="orders.php?id=<?php  echo $result3data["orders_id"]; ?>&proc=View&searchcrit=&page=0">Order ID : <?php  echo $result3data["orders_id"]; ?></a></td>
	</tr>
	
<?php 

}

?>

</table>

</BODY>
</html>