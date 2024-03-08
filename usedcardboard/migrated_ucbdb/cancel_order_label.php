<?php  
require ("inc/header_session.php");
//error_reporting(0);
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
//
$orders_id = $_REQUEST["orders_id"];
$orid=$_REQUEST["orid"];
//

/*$arr_warehouse = array('orders_active_ucb_evansville', 'orders_active_ucb_hannibal', 'orders_active_ucb_hunt_valley', 'orders_active_ucb_los_angeles', 'orders_active_ucb_rochester', 'orders_active_ucb_salt_lake', 'orders_active_ucb_atlanta', 'orders_active_ucb_dallas', 'orders_active_ucb_danville', 'orders_active_ucb_iowa', 'orders_active_ucb_montreal', 'orders_active_ucb_toronto', 'orders_active_ucb_philadelphia');
foreach($arr_warehouse as $tbl_warehouse)
{
	$query = "DELETE FROM ".$tbl_warehouse." WHERE orders_id = " . $orders_id;
	echo $query . "<br>";
	//$result = db_query($query,db() );
}*/

$cancel_ord_qry = db_query("Update orders_active_export set setignore = 1, cancel_flag='yes', cancel_flg_marked_by = '" . $_COOKIE['userinitials'] . "', cancel_flg_marked_on = '" . date("Y-m-d H:i:s") . "' where id=".$orid,db());
if (empty($cancel_ord_qry)) {
	echo "yes";
}

/*if (empty($cancel_ord_qry)) {
	echo "<script type=\"text/javascript\">";
	echo "window.confirm('Label has been cancelled in DASH, but NOT in FedEx Ship Manager. If label is already printed, you must 1. Also Delete it in FedEx Ship Manager AND 2. Tell the local Manager to destroy the label and not send out the kit for it.')";
	echo "window.history.back();";
	echo "</script>";
	echo "<noscript>";
	//echo "<meta http-equiv=\"refresh\" content=\"0;url=history.go(-1)\" />";
	echo "</noscript>"; exit;
}*/
//

?>
