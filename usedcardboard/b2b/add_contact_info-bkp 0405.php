<?
session_start();
$sessionId = session_id();
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

/*echo "nyn<pre>"; print_r($_REQUEST); echo "</pre>";
exit();*/

if(isset($_REQUEST['txtCntInfoEmail']) && !empty($_REQUEST['txtCntInfoEmail'])){
	
	$orderData['cntInfoFNm']	= $_REQUEST['txtCntInfoFNm'];
	$orderData['cntInfoLNm']	= $_REQUEST['txtCntInfoLNm'];
	$orderData['cntInfoCompny']	= $_REQUEST['txtCntInfoCompny'];
	$orderData['cntInfoEmail']	= $_REQUEST['txtCntInfoEmail'];
	$orderData['cntInfoPhn']	= $_REQUEST['txtCntInfoPhn'];
	$orderData['sessionId']	    = $sessionId;
	$userMasterId 				= $_REQUEST['hdnUserMastrId'];
	$productLoopId 				= $_REQUEST['productId'];
	$productName 				= $_REQUEST['productQntype'];
	$proNameid 				    = $_REQUEST['productQntypeid'];
	$productQnt 				= $_REQUEST['productQnt'];
	$productUnitPr 				= $_REQUEST['productQntprice'];
	$productTotal 				= $_REQUEST['productTotal'];

	/*check the user product entry already or not with current session id*/
	$getSessionDt = db_query("SELECT id FROM b2becommerce_order_item WHERE session_id = '".$sessionId."'", db() );
	$rowSessionDt = array_shift($getSessionDt);
	
	/* save order item with contact details*/
	$responce = '';
	if(empty($rowSessionDt['id'])){
		$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(user_master_id, session_id, product_loopboxid, product_name_id, product_name, product_qty, 
		product_unitprice, product_total, contact_firstname, contact_lastname, contact_company, contact_email, contact_phone, order_date ) 
		VALUES('".$userMasterId."', '".$sessionId."', '".$productLoopId."', '".$proNameid."', '".$productName."', '".$productQnt."', '".$productUnitPr."', '".$productTotal."', '".$orderData['cntInfoFNm']."', '".$orderData['cntInfoLNm']."', '".$orderData['cntInfoCompny']."', '".$orderData['cntInfoEmail']."', '".$orderData['cntInfoPhn']."', '".date('YmdHis')."'  )", db());
		$insertedId = tep_db_insert_id();
		$responce = $insertedId;
	}else{
		$responce = $rowSessionDt['id'];
		$qryOrderDt = db_query("Update b2becommerce_order_item set product_name_id = '". $proNameid ."', product_name = '".$productName."', 
		product_qty = '". $productQnt . "', product_unitprice = '". $productUnitPr ."', product_total = '". $productTotal ."' ,
		contact_firstname = '".$orderData['cntInfoFNm']."',  contact_lastname = '".$orderData['cntInfoLNm']."', contact_company = '".$orderData['cntInfoCompny']."', 
		contact_email = '".$orderData['cntInfoEmail']."', contact_phone = '".$orderData['cntInfoPhn']."' where session_id = '" . $sessionId . "'" , db());
	}

	$orderData['lastInsertId'] = $responce;

	/*Merge session product Data & contact info and set order data array*/
	$orderData = array_merge($_SESSION['productData'], $orderData );
	$_SESSION['orderData'] 	= $orderData;

	//echo $responce." - > <pre>"; print_r($_SESSION['orderData']); echo "</pre>";

	echo $responce;

}
?>