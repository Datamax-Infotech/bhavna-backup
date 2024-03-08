<?php  
require ("inc/header_session.php");
?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - Duplicate Orders</title>


<?php 
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>



<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");





?>


</HEAD>
<BODY>
<div>
	<?php  include("inc/header.php"); ?>
</div>
	<br>
<div class="main_data_css">

<table cellSpacing="1" cellPadding="1" border="0" width="200" >
	<tr align="middle">
		<td class="style24" style="height: 16px">
		<strong>Order ID</strong></td>
	</tr>

<?php 
$today = date("Ymd"); 

$number = $_GET['number'];

$sql3 = "SELECT * FROM (SELECT count( tracking_number ) AS total, orders_id
FROM orders_active_export  GROUP BY tracking_number ORDER BY `total` DESC) as A WHERE A.total>1 ORDER BY orders_id DESC LIMIT 0, " . $number ;
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
	</div>
</BODY>
</html>