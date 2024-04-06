<?php
session_start();
$sessionId = session_id();
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db();

if(isset($_REQUEST['quote_id_tmp'])){
	$quote_id = $_REQUEST['quote_id_tmp'];
	
	if(isset($_REQUEST['txtCntInfoEmail']) && !empty($_REQUEST['txtCntInfoEmail'])){
		$orderData['cntInfoFNm']	= $_REQUEST['txtCntInfoFNm'];
		$orderData['cntInfoLNm']	= $_REQUEST['txtCntInfoLNm'];
		$orderData['cntInfoCompny']	= $_REQUEST['txtCntInfoCompny'];
		$orderData['cntInfoEmail']	= $_REQUEST['txtCntInfoEmail'];
		//
		$phone_format1 = str_replace(' ', '', $_REQUEST['txtCntInfoPhn']);
		$phone_format2 = str_replace('(', '', $phone_format1);
		$phone_format3 = str_replace(')', '', $phone_format2);
		
		if ( preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $phone_format3,  $new_phone_no))
      	{
        	$phone_result = '(' . $new_phone_no[1] . ') ' .$new_phone_no[2] . '-' . $new_phone_no[3];
        	$orderData['cntInfoPhn'] = $phone_result;
      	}else{
			$orderData['cntInfoPhn'] = $_REQUEST['txtCntInfoPhn'];
		}
		
		$orderData['sessionId']	    = $sessionId;

		$userMasterId 				= $_REQUEST['hdnUserMastrId'];
		$hdnNoOfProd 				= $_REQUEST['hdnNoOfProd'];

		$userMasterId 				= $_COOKIE['uid'];

		/*check the user product entry already or not with current session id*/
		$getSessionDt = db_query("SELECT id FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and quote_id = '" . $quote_id ."'");
		$rec_found = "no";
		while ($rowSessionDt = array_shift($getSessionDt)) {
			$orderItemId = $rowSessionDt['id'];
			$rec_found = "yes";
		}
		$responce = '';
		if($rec_found == "yes"){			
			$responce = $orderItemId;
			$qryOrderDt = db_query("UPDATE b2becommerce_order_item SET user_master_id = '".$userMasterId."', 
			contact_firstname = '".str_replace("'", "\'" ,$orderData['cntInfoFNm'])."',  contact_lastname = '". str_replace("'", "\'" ,$orderData['cntInfoLNm']) ."', contact_company = '". str_replace("'", "\'" ,$orderData['cntInfoCompny']) ."', 
			contact_email = '". str_replace("'", "\'" ,$orderData['cntInfoEmail']) ."', contact_phone = '". str_replace("'", "\'" ,$orderData['cntInfoPhn']) ."' WHERE session_id = '".$sessionId."' and quote_id = '" . $quote_id ."'" );
		}
		echo $responce;
	}

}else{
	if(isset($_REQUEST['txtCntInfoEmail']) && !empty($_REQUEST['txtCntInfoEmail'])){	
		$orderData['cntInfoFNm']	= $_REQUEST['txtCntInfoFNm'];
		$orderData['cntInfoLNm']	= $_REQUEST['txtCntInfoLNm'];
		$orderData['cntInfoCompny']	= $_REQUEST['txtCntInfoCompny'];
		$orderData['cntInfoEmail']	= $_REQUEST['txtCntInfoEmail'];
		//
		$phone_format1 = str_replace(' ', '', $_REQUEST['txtCntInfoPhn']);
		$phone_format2 = str_replace('(', '', $phone_format1);
		$phone_format3 = str_replace(')', '', $phone_format2);
		
		if (preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $phone_format3,  $new_phone_no ))
      	{
        	$phone_result = '(' . $new_phone_no[1] . ') ' .$new_phone_no[2] . '-' . $new_phone_no[3];
        	$orderData['cntInfoPhn'] = $phone_result;
      	}else{
			$orderData['cntInfoPhn'] = $_REQUEST['txtCntInfoPhn'];
		}	
		$orderData['sessionId']	    = $sessionId;
		$userMasterId 				= $_REQUEST['hdnUserMastrId'];
		$productLoopId 				= $_REQUEST['productIdloop'];
		$productName 				= $_REQUEST['productQntype'];
		$proNameid 				    = $_REQUEST['productQntypeid'];
		$productQnt 				= $_REQUEST['productQnt'];
		$productUnitPr 				= $_REQUEST['productQntprice'];
		$productTotal 				= $_REQUEST['productTotal'];
		$createAccVal  				= $_REQUEST['chkContactFrmCreateAcc'];

		if($createAccVal == 2 ){
			$txtCrAccPass 	= $_REQUEST['txtCrAccPass'];
			db();
			$qryUserDt 		= "SELECT * FROM b2becommerce_user_master WHERE email = ? ";
			$resUserDt 		= db_query($qryUserDt, array("s"), array($orderData['cntInfoEmail']));
			$rec_found = "no";
			while ($rowInfo = array_shift($resUserDt)) {
				$rec_found = "yes";
			}
			
			$user_already_register = "no";
			
			if($rec_found == "no"){
				$rec_found = "no";
				db_query("INSERT INTO b2becommerce_user_master(first_name, last_name, email, password, phone) VALUES ('".str_replace("'", "\'" ,$orderData['cntInfoFNm'])."', '".str_replace("'", "\'" ,$orderData['cntInfoLNm'])."', '".str_replace("'", "\'" ,$orderData['cntInfoEmail'])."', '". $txtCrAccPass."', '".str_replace("'", "\'" ,$orderData['cntInfoPhn'])."' )" );
				$userInsertedId = tep_db_insert_id();
				if($userInsertedId != "" ){
					$rec_found 	= "yes";
					$employeeid = $userInsertedId;
					$firstname 	= $orderData['cntInfoFNm'];
				}
				if($rec_found == "yes"){
					$date_of_expiry = time() + 600000 ;
					setcookie( "username", $firstname, $date_of_expiry );
					setcookie( "uid", $employeeid, $date_of_expiry );	
				}				
			}else{
				$user_already_register = "yes";
			}
		}
		if($userInsertedId == ""){
			$userMasterId 	= $_COOKIE['uid'];
		}else{
			$userMasterId 	= $userInsertedId;
		}	
		/* save order item with contact details*/
		
		/*check the user product entry already or not with current session id*/
		$getSessionDt = db_query("SELECT id FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $productLoopId ."'");
		$rec_found = "no";
		while ($rowSessionDt = array_shift($getSessionDt)) {
			$rec_found = "yes";
		}
		$responce = '';
		if($rec_found == "no"){
			
			$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(user_master_id, session_id, product_loopboxid, contact_firstname, contact_lastname, contact_company, contact_email, contact_phone, order_date ) 
			VALUES('".$userMasterId."', '".$sessionId."', '".str_replace("'", "\'" ,$productLoopId)."', '".str_replace("'", "\'" ,$orderData['cntInfoFNm'])."', '".str_replace("'", "\'" ,$orderData['cntInfoLNm'])."', '".str_replace("'", "\'" ,$orderData['cntInfoCompny'])."', '".str_replace("'", "\'" ,$orderData['cntInfoEmail'])."', '". str_replace("'", "\'" ,$orderData['cntInfoPhn']) ."', '".date('YmdHis')."' )");
			$insertedId = tep_db_insert_id();

			$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item_details(order_item_id, product_id, product_name_id, product_name, product_qty, product_unitprice, product_total) 
				VALUES( ".$insertedId.", '" . str_replace("'", "\'" ,$productLoopId) ."', '". str_replace("'", "\'" ,$proNameid) ."', '". str_replace("'", "\'" ,$productName) ."', '". str_replace("'", "\'" ,$productQnt) ."', '". str_replace("'", "\'" ,$productUnitPr) ."', '". str_replace("'", "\'" ,$productTotal)."')");

			$responce = $insertedId;
		}else{
			$responce = $productLoopId;
			$qryOrderDt = db_query("UPDATE b2becommerce_order_item SET user_master_id = '".$userMasterId."', 
			contact_firstname = '".str_replace("'", "\'" ,$orderData['cntInfoFNm'])."',  contact_lastname = '".str_replace("'", "\'" ,$orderData['cntInfoLNm'])."', contact_company = '".str_replace("'", "\'" ,$orderData['cntInfoCompny'])."', 
			contact_email = '". str_replace("'", "\'" ,$orderData['cntInfoEmail'])."', contact_phone = '". str_replace("'", "\'" ,$orderData['cntInfoPhn']) ."' WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $productLoopId ."'" );

			$qryOrderDt = db_query("UPDATE b2becommerce_order_item_details SET product_name_id = '". str_replace("'", "\'" ,$proNameid) ."', product_name = '". str_replace("'", "\'" ,$productName) ."', product_qty = '". str_replace("'", "\'" ,$productQnt) . "', product_unitprice = '". str_replace("'", "\'" ,$productUnitPr) ."', product_total = '". str_replace("'", "\'" ,$productTotal) ."' WHERE order_item_id = '" . $rowSessionDt['id'] ."' AND product_id = '".$productLoopId."'");

		}

		$orderData['lastInsertId'] = $responce;

		/*Merge session product Data & contact info and set order data array*/
		$orderData = array_merge($_SESSION['productData'], $orderData );
		$_SESSION['orderData'] 	= $orderData;

		//echo $responce." - > <pre>"; print_r($_SESSION['orderData']); echo "</pre>";

		if ($user_already_register == "yes"){
			echo "STOP";
		}else{
			echo $responce;
		}	

	}

}


?>