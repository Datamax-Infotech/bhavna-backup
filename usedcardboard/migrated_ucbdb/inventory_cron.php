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
	<title>DASH - Home</title>
	<meta http-equiv="refresh" content="300">
</head>

<body>

<?php 
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>


<?php 

$get_the_date = "SELECT * FROM inv_start_date";
$get_the_date_res = db_query($get_the_date,db());
while($row_date = array_shift($get_the_date_res))
{
$start_date = $row_date['update_date'];
// echo $start_date;
}

$today = date('Ymd'); 
$start_date = $start_date;
$end_date = $today;


$query = "SELECT * FROM inv_warehouse_to_modules WHERE warehouse_type = 'Ship' ";
$res = db_query($query,db());
while($row = array_shift($res))
{
$quantity = $row["quantity"];

echo $row["warehouse_name"] . " - " . $row["module_name"] . " Start Quantity:" . $row["quantity"] . " ";

$tiger = "SELECT SUM(quantity) FROM inv_warehouse_transactions WHERE warehouse_id = " . $row["warehouse_id"] . " AND module_name = '" . $row["module_name"] . "' AND update_date >= '" . date( 'Ymd',strtotime($start_date)) . "' AND update_date <= '" . date( 'Ymd',strtotime($end_date)). "' GROUP BY warehouse_id";
// echo $tiger;
$tiger_res = db_query($tiger,db());
$tiger_res_rows = tep_db_num_rows($tiger_res);
while($tiger_row = array_shift($tiger_res))
{
$adjusted_qty = $tiger_row["SUM(quantity)"];
// echo $adjusted_qty;
}


$the_date_timestamp = date("Y-m-d H:m:i" ,strtotime($start_date));

$monster = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $row["warehouse_id"] . " AND module_name = '" . $row["module_name"] . "' AND status != 'Manifest Pickup' AND print_date >= '" . date( 'Y-m-d H:i:s',strtotime($start_date)) . "' AND print_date <= '" . date( 'Y-m-d H:i:s',strtotime($end_date)). "'";

$monster_res = db_query($monster,db());
$monster_res_rows = tep_db_num_rows($monster_res);
$used = $monster_res_rows;
// $run_date = date('Ymd', $monster_res_rows[print_date])
echo "    Used: " . $used;

$pre_balance = $quantity + $adjusted_qty;
$balance = $pre_balance - $used;
echo "     Balance: " . $balance  . "<br>";


$up_qry = "UPDATE inv_warehouse_to_modules SET cron_quantity = " . $balance . ", cron_update_date = " . $end_date . " WHERE id = " . $row["id"];
$up_qry_res = db_query($up_qry,db());


$quantity = 0;
$adjusted_qty = 0;
$used = 0;

}



$datewtime = date("F j, Y, g:i a"); 


$ddw_sql = "UPDATE inv_start_date SET when_process = '$datewtime'";
$ddw_sql_result = db_query($ddw_sql,db() );
?>


<p>Done.



</body>
</html>