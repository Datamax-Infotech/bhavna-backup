<?php
session_start();
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require_once("cal_functions.php");

/*echo "<pre>"; print_r($_REQUEST); echo "</pre>";
exit();*/

if (isset($_REQUEST['txtshippingaddEmail']) && !empty($_REQUEST['txtshippingaddEmail'])) {

	$orderData['shippingaddFNm']	= $_REQUEST['txtshippingaddFNm'];
	$orderData['shippingaddLNm']	= $_REQUEST['txtshippingaddLNm'];
	$orderData['shippingaddCompny']	= $_REQUEST['txtshippingaddCompny'];
	$orderData['shippingAdd1'] 		= $_REQUEST['txtshippingAdd1'];
	$orderData['shippingAdd2'] 		= $_REQUEST['txtshippingAdd2'];
	$orderData['shippingaddCity'] 	= $_REQUEST['txtshippingaddCity'];
	$orderData['shippingaddState'] 	= $_REQUEST['txtshippingaddState'];
	$orderData['shippingaddZip'] 	= $_REQUEST['txtshippingaddZip'];
	$orderData['shippingaddEmail']	= $_REQUEST['txtshippingaddEmail'];

	$phone_format1 = str_replace(' ', '', $_REQUEST['txtshippingaddPhone']);
	$phone_format2 = str_replace('(', '', $phone_format1);
	$phone_format3 = str_replace(')', '', $phone_format2);

	if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone_format3,  $new_phone_no)) {
		$phone_result = '(' . $new_phone_no[1] . ') ' . $new_phone_no[2] . '-' . $new_phone_no[3];
		$orderData['shippingaddPhone'] = $phone_result;
	} else {
		$orderData['shippingaddPhone'] = $_REQUEST['txtshippingaddPhone'];
	}

	$orderData['shippingaddDockhrs'] = $_REQUEST['txtshippingaddDockhrs'];
	$orderData['lastInsertId']		= $_REQUEST['hdnLastInsertId'];
	$pickupType 					= $_REQUEST['rdoPickUp'];

	$ProductLoopId = $_REQUEST['hdnloopboxid'];
	// update respective order shipping addres 
	$sessionId = session_id();
	$qryUpdt = "UPDATE b2becommerce_order_item SET shipping_firstname = '" . str_replace("'", "\'", $orderData['shippingaddFNm']) . "', shipping_lastname = '" . str_replace("'", "\'", $orderData['shippingaddLNm']) . "', shipping_company = '" . str_replace("'", "\'", $orderData['shippingaddCompny']) . "', shipping_add1 = '" . str_replace("'", "\'", $orderData['shippingAdd1']) . "', shipping_add2 = '" . str_replace("'", "\'", $orderData['shippingAdd2']) . "', shipping_city = '" . str_replace("'", "\'", $orderData['shippingaddCity']) . "', shipping_state = '" . str_replace("'", "\'", $orderData['shippingaddState']) . "', shipping_zip = '" . str_replace("'", "\'", $orderData['shippingaddZip']) . "', shipping_email = '" . str_replace("'", "\'", $orderData['shippingaddEmail']) . "', shipping_phone = '" . str_replace("'", "\'", $orderData['shippingaddPhone']) . "', shipping_dockhrs = '" . str_replace("'", "\'", $orderData['shippingaddDockhrs']) . "', pickup_type = '" . str_replace("'", "\'", $pickupType) . "' WHERE session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId . "'";
	//echo $qryUpdt;
	db();
	$resUpdt = db_query($qryUpdt);

	/*Merge session product all info & product shipping data and set order data SESSION array*/
	//$orderData = array_merge($_SESSION['orderData'], $orderData );
	//$_SESSION['orderData'] 	= $orderData;

	/*calculates the Uber Freight Program Rate*/

	/*Do the needful calculations here*/

	/*For now just initialise the required variables */
	$product_loopboxid = 0;
	$productTotal = 0;
	$productQnt = 0;
	db();
	$getSessionDt = db_query("SELECT b2becommerce_order_item.product_loopboxid, b2becommerce_order_item_details.product_total, b2becommerce_order_item_details.product_qty FROM b2becommerce_order_item INNER JOIN b2becommerce_order_item_details ON b2becommerce_order_item_details.order_item_id = b2becommerce_order_item.id WHERE session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId . "'");
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$product_loopboxid = $rowContactInfo['product_loopboxid'];
		$productTotal = str_replace(",", "", $rowContactInfo['product_total']);
		$productQnt = $rowContactInfo['product_qty'];
	}

	$uberrespone_amt = uber_freight_data($product_loopboxid, $orderData);
	//$uberrespone_amt = 6454;
	$uberrespone_amt_tmp = str_replace(",", "", $uberrespone_amt);

	$orderQuote['quoteName']	= 'Shipping Quote';
	if ($uberrespone_amt_tmp > 0) {
		$shipping_cost_err_flg = 0;
		$orderQuote['quoteQty'] 	= '1';
		$orderQuote['quoteUnitPr']	= $uberrespone_amt;
		$orderQuote['quoteTotal'] 	= $uberrespone_amt;
		$orderQuote['quoteRate']	= $uberrespone_amt;
	} else {
		$shipping_cost_err_flg = 1;
		$orderQuote['quoteQty'] 	= '1';
		$orderQuote['quoteUnitPr']	= 0;
		$orderQuote['quoteTotal'] 	= 0;
		$orderQuote['quoteRate']	= $uberrespone_amt;
	}

	$distance = find_distance($product_loopboxid, $orderData['shippingaddZip']);
	//$shipadd = $_REQUEST['txtshippingaddFNm'] . " " . $_REQUEST['txtshippingaddLNm'] .", ";
	$shipadd = $_REQUEST['txtshippingAdd1'] . trim($_REQUEST['txtshippingAdd2']) . ", " . $_REQUEST['txtshippingaddCity'] . ", " . $_REQUEST['txtshippingaddState'] . " " . $_REQUEST['txtshippingaddZip'];


	/*Merge session product all info & quote data and set order data SESSION array*/
	$orderData = array_merge($_SESSION['orderData'], $orderQuote);
	$_SESSION['orderData'] 	= $orderData;

	/*echo "<pre>";
	print_r($_SESSION['orderData'] );
	echo "</pre>";*/

	$uberrespone_amt = str_replace(",", "", $uberrespone_amt);
	$productTotalTemp = str_replace("$", "", $productTotal);
	$productTotalTemp = str_replace(",", "", $productTotal);
	$total =  $productTotalTemp + $uberrespone_amt;
	$unitpriceafteruberresponce = $total / $productQnt;

	/* update respective order Quote details*/
	$qryUpdt = "UPDATE b2becommerce_order_item SET quote_name = '" . str_replace("'", "\'", $orderQuote['quoteName']) . "', quote_qty = '" . str_replace("'", "\'", $orderQuote['quoteQty']) . "', quote_unit_price = '" . str_replace("'", "\'", $orderQuote['quoteUnitPr']) . "', quote_total = '" . str_replace("'", "\'", $orderQuote['quoteTotal']) . "', quote_rate = '" . str_replace("'", "\'", $orderQuote['quoteRate']) . "' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId . "'";
	//echo $qryUpdt;
	db();
	$resUpdt = db_query($qryUpdt);

	echo $orderQuote['quoteName'] . "~" . $orderQuote['quoteQty'] . "~" . $orderQuote['quoteUnitPr'] . "~" . $orderQuote['quoteTotal'] . "~" . $orderQuote['quoteRate'] . "~" . number_format($total, 2) . "~" . number_format($distance, 0) . "~" . $shipadd . "~" . number_format($unitpriceafteruberresponce, 2) . "~" . $shipping_cost_err_flg;
}
