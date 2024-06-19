<?php
/*
Page Name: calculate_distance.php
Page created By: Amarendra
Page created On: 21-04-2021
Last Modified On: 
Last Modified By: Amarendra
Change History:
Date           By            Description
==================================================================================================================
21-04-21      Amarendra     This file is created to handle request to calculate distance between zip to warehouse.
==================================================================================================================
*/
session_start();

require_once ("mainfunctions/database.php");
require_once ("mainfunctions/general-functions.php"); 
require_once("cal_functions.php");
db();

// Calculate Distance
if (isset($_REQUEST["zip"]) && !empty($_REQUEST["zip"])){
	
	$dist = find_distance($_REQUEST["id"], $_REQUEST["zip"]);

	echo number_format($dist,0) . " mi " . "<span style='color:#b1bdb1;'>(from zip " . $_REQUEST["zip"]. ")</span>";
} 
if (isset($_REQUEST["ipadd"]) && !empty($_REQUEST["ipadd"])){
	$dist = find_distance_ip($_REQUEST["id"], $_REQUEST["ipadd"]);
	echo number_format($dist, 0) . " mi " . "<span style='color:#b1bdb1;'>(from IP " . $_REQUEST["ipadd"]. ")</span>";
}

if (isset($_REQUEST["getshippingquote_id"]) && !empty($_REQUEST["getshippingquote_id"])){
	$orderData['shippingAdd1']	   = $_REQUEST['shipping_add1'];
	$orderData['shippingAdd2']	   = $_REQUEST['shipping_add2'];
	$orderData['shippingaddCity']  = $_REQUEST['shipping_city'];
	$orderData['shippingaddState'] = $_REQUEST['shipping_state'];
	$orderData['shippingaddZip']   = $_REQUEST['shipping_zip'];
	
	$ProductLoopId = $_REQUEST['getshippingquote_id'];
	$sessionId = session_id();
	
	$uberrespone_amt = uber_freight_data($_REQUEST["getshippingquote_id"], $orderData );
	
	$getSessionDt = db_query("SELECT id FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db());
	$rec_found = "no";
	while ($rowSessionDt = array_shift($getSessionDt)) {
		$rec_found = "yes";
	}
	if($rec_found == "no"){
		$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(session_id, product_loopboxid, 
		shipping_add1, shipping_add2, shipping_city, shipping_state, shipping_zip, quote_name, quote_qty, quote_unit_price, quote_total, quote_rate ) 
		VALUES('".$sessionId."', '".$ProductLoopId."', '".str_replace("'", "\'" ,$orderData['shippingAdd1'])."', '". str_replace("'", "\'" ,$orderData['shippingAdd2']) ."', '". str_replace("'", "\'" ,$orderData['shippingaddCity']) ."',
		'". str_replace("'", "\'" ,$orderData['shippingaddState']) ."', '". str_replace("'", "\'" ,$orderData['shippingaddZip']) ."',
		'Shipping Quote', '1', '" . $uberrespone_amt . "', '" . $uberrespone_amt . "', '" . $uberrespone_amt . "' )", db());
	}else{
		$qryOrderDt = db_query("Update b2becommerce_order_item set shipping_add1 = '". str_replace("'", "\'" ,$orderData['shippingAdd1']) . "', shipping_add2 = '". str_replace("'", "\'" ,$orderData['shippingAdd2']) ."',
		 shipping_city = '". str_replace("'", "\'" ,$orderData['shippingaddCity']) ."', shipping_state = '". str_replace("'", "\'" ,$orderData['shippingaddState']) . "', 
		 shipping_zip = '". str_replace("'", "\'" ,$orderData['shippingaddZip'])."',
		 quote_name = 'Shipping Quote', quote_qty = 1, quote_unit_price = '" . $uberrespone_amt . "', quote_total = '" . $uberrespone_amt . "', quote_rate = '" . $uberrespone_amt . "'
		 where session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'" , db());
	}	
	
	echo $uberrespone_amt;
}

?>