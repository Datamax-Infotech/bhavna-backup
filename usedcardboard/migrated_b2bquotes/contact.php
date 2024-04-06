<?php 
session_start();
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$sessionId = session_id();
$lead_time_stored_val = "";
$userMasterId 	= $_COOKIE['uid'];

$arrContact = array();
//echo "<pre>";print_r($_REQUEST); echo "</pre>";
//exit();
if(isset($_REQUEST['quote_id'])){
	$quote_id_tmp = decrypt_password($_REQUEST['quote_id']);
	$quote_id = (int)$quote_id_tmp - 3770;
	?>
	<?php 
	/********************Selected product insertion array generate start****************************/
	if(!empty($_REQUEST['chkQty'])){
		$cntChkQty = count($_REQUEST['chkQty']);
		$arrProdId = $_REQUEST['productIdloop'];
		$arrChkQty 	= $_REQUEST['chkQty'];
		
		//echo "<br /> arrProdId - <pre>"; print_r($arrProdId); echo "</pre>";
		//echo "<br /> arrChkQty - <pre>"; print_r($arrChkQty); echo "</pre>";
		
		$newKeys = array();
		foreach ($arrChkQty as $arrChkQtyK => $arrChkQtyV){
			$newKeys[] = array_search($arrChkQtyV, $arrProdId);
		}
		//echo "<br /> newKeys - <pre>"; print_r($newKeys); echo "</pre>";
		$arrProdDt = array();
		for($i = 0; $i < $_REQUEST['totalProd']; $i++ ){
			foreach ($newKeys as $key => $value){
				if($i == $value){
					//echo "<br /> value - ".$value;
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productIdloop'] 	= $_REQUEST['productIdloop'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productQntypeid'] = $_REQUEST['productQntypeid'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productQntype'] 	= $_REQUEST['productQntype'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productQnt'] 		= $_REQUEST['productQnt'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productQntprice'] = $_REQUEST['productQntprice'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['productTotal'] 	= $_REQUEST['productTotal'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['hdAvailability'] 	= $_REQUEST['hdAvailability'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['hdLeadTime'] 	= $_REQUEST['hdLeadTime'][$i];
					$arrProdDt[$_REQUEST['productIdloop'][$i]]['item_id'] 	= $_REQUEST['hdnItemId'][$i];
					
					$lead_time_stored_val = $_REQUEST['hdAvailability'][$i];
				}
			}
		}
		//echo "<br /> arrProdDt - <pre>"; print_r($arrProdDt); echo "</pre>";
	}else{
		/********************Selected product insertion array generate end*****************************/
	
		$arrProdDt = array();
		if($_REQUEST['totalProd'] != ''){
			for($i = 0; $i < $_REQUEST['totalProd']; $i++ ){
				$arrProdDt[$_REQUEST['unqid'][$i]]['productIdloop'] 	= $_REQUEST['productIdloop'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['productQntypeid'] = $_REQUEST['productQntypeid'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['productQntype'] 	= $_REQUEST['productQntype'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['productQnt'] 		= $_REQUEST['productQnt'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['productQntprice'] = $_REQUEST['productQntprice'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['productTotal'] 	= $_REQUEST['productTotal'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['hdAvailability'] 	= $_REQUEST['hdAvailability'][$i];
				$arrProdDt[$_REQUEST['unqid'][$i]]['hdLeadTime'] 	= $_REQUEST['hdLeadTime'][$i];
				
				$lead_time_stored_val = $_REQUEST['hdAvailability'][$i];
			}
			//echo "<br /> newKeys - <pre>"; echo $_REQUEST['totalProd'] . " = "; print_r($arrProdDt); echo "</pre>";
		}else{
			db();
			$getOrderId = db_query("SELECT id AS OrderId FROM b2becommerce_order_item WHERE quote_id ='".$quote_id."' AND session_id = '".$sessionId."'");
			$rowOrderId = array_shift($getOrderId);
			db();
			$getOrderedProd = db_query("SELECT * FROM b2becommerce_order_item_details WHERE order_item_id = '".$rowOrderId['OrderId']."'");
			while ($rowsOrderedProd = array_shift($getOrderedProd)) {
				//echo "nyn<pre>"; print_r($getOrderedProd); echo "</pre>";
				$arrProdDt[$rowsOrderedProd['product_id']]['productIdloop'] 	= $rowsOrderedProd['product_id'];
				$arrProdDt[$rowsOrderedProd['product_id']]['productQntypeid'] 	= $rowsOrderedProd['product_name_id'];
				$arrProdDt[$rowsOrderedProd['product_id']]['productQntype'] 	= $rowsOrderedProd['product_name'];
				$arrProdDt[$rowsOrderedProd['product_id']]['productQnt'] 		= $rowsOrderedProd['product_qty'];
				$arrProdDt[$rowsOrderedProd['product_id']]['productQntprice'] 	= $rowsOrderedProd['product_unitprice'];
				$arrProdDt[$rowsOrderedProd['product_id']]['productTotal'] 		= $rowsOrderedProd['product_total'];
				$arrProdDt[$rowsOrderedProd['product_id']]['hdAvailability'] 	= $rowsOrderedProd['product_availability'];
				$arrProdDt[$rowsOrderedProd['product_id']]['hdLeadTime'] 		= $rowsOrderedProd['hdLeadTime'];
				
				$lead_time_stored_val = $rowsOrderedProd['product_availability'];
			}
		}
	}	
	//exit();
	db();
	$getSessionDt = db_query("SELECT id, contact_firstname FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and quote_id = '" . $quote_id ."'");
	$rowSessionDt = array_shift($getSessionDt);
	
	//echo "<pre>"; print_r($arrProdDt); echo "</pre>"; 

	$arrProdLoopId = $arrAvailability =  array();
	$total = 0;
	$machineIP 	= $_SERVER['REMOTE_ADDR'];
	
	if(empty($rowSessionDt['id'])){
		db();
		$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(user_master_id, session_id, quote_id, quote_notes, machine_ip) 
		VALUES('".$userMasterId."', '".$sessionId."', '". $quote_id ."', '".str_replace("'", "\'" ,$_REQUEST['quoteNotes'])."', '" . $machineIP . "' )");
		$insertedId = tep_db_insert_id();

		foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
			db();
			$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item_details(order_item_id, product_id, product_name_id, product_name, product_qty, product_unitprice, product_total, product_availability, product_item_id, product_lead_time) VALUES( ".$insertedId.", '".str_replace("'", "\'" ,$arrProdDtV['productIdloop'])."', '". str_replace("'", "\'" ,$arrProdDtV["productQntypeid"]) ."', '". str_replace("'", "\'" ,$arrProdDtV["productQntype"]) ."', '". str_replace("'", "\'" ,$arrProdDtV["productQnt"]) ."', '".$arrProdDtV["productQntprice"]."', '".$arrProdDtV["productTotal"]."', '". str_replace("'", "\'" ,$arrProdDtV['hdAvailability']) ."', '".$arrProdDtV['item_id']."', '". str_replace("'", "\'" ,$arrProdDtV['hdLeadTime']) ."')");
			$arrProdLoopId[] = $arrProdDtV['productIdloop'];
			$arrAvailability[$arrProdDtV['productIdloop']] = $arrProdDtV['item_id'];
			$total = $total + $arrProdDtV['productTotal']; 
		}
		$responce = $insertedId;
	}else{
		$responce = $rowSessionDt['id'];	
		db_query("DELETE FROM b2becommerce_order_item_details WHERE order_item_id = '".$rowSessionDt['id']."'");
			
		db();		
		foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {	
			db_query("INSERT INTO b2becommerce_order_item_details(order_item_id, product_id, product_name_id, product_name, product_qty, product_unitprice, product_total, product_availability, product_item_id, product_lead_time) VALUES( ".$rowSessionDt['id'].", '".str_replace("'", "\'" ,$arrProdDtV['productIdloop'])."', '". str_replace("'", "\'" ,$arrProdDtV["productQntypeid"]) ."', '". str_replace("'", "\'" ,$arrProdDtV["productQntype"]) ."', '". str_replace("'", "\'" ,$arrProdDtV["productQnt"]) ."', '".$arrProdDtV["productQntprice"]."', '".$arrProdDtV["productTotal"]."', '". str_replace("'", "\'" ,$arrProdDtV['hdAvailability']) ."', '".$arrProdDtV["item_id"]."', '".str_replace("'", "\'" ,$arrProdDtV['hdLeadTime']) ."')");
			$arrProdLoopId[] = $arrProdDtV['productIdloop'];
			$arrAvailability[$arrProdDtV['productIdloop']] = $arrProdDtV['item_id'];
			$total = $total + $arrProdDtV['productTotal']; 
		}
	}
	$cntInfoPhn = "";
	$cntInfoEmail = "";
	$cntInfoCompny = "";
	$cntInfoLNm = "";
	$cntInfoFNm = "";
	$rowContactInfo = array();
	if($_COOKIE['uid'] != ""){ 
		echo "In step 1 <br>";
		db();
		$getUserDt = db_query("SELECT * FROM b2becommerce_user_master WHERE userid  = '".$_COOKIE['uid']."'");
		while ($rowUserDt = array_shift($getUserDt)) {
			$cntInfoFNm 	= $rowUserDt['first_name'];
			$cntInfoLNm 	= $rowUserDt['last_name'];
			$cntInfoCompny 	= $rowUserDt['companyid'];
			$cntInfoEmail 	= $rowUserDt['email'];
			$cntInfoPhn 	= $rowUserDt['phone'];
		}
	}elseif(!empty($rowSessionDt['id']) && $rowSessionDt['contact_firstname'] != ""){	
		db();		
		$getOrderContInfo = db_query("SELECT contact_firstname, contact_lastname, contact_company, contact_email, contact_phone FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and quote_id = '" . $quote_id ."'");
		$rowOrderContInfo 	= array_shift($getOrderContInfo);
		$cntInfoFNm 		= $rowOrderContInfo['contact_firstname'];
		$cntInfoLNm 		= $rowOrderContInfo['contact_lastname'];
		$cntInfoCompny 		= $rowOrderContInfo['contact_company'];
		$cntInfoEmail 		= $rowOrderContInfo['contact_email'];
		$cntInfoPhn 		= $rowOrderContInfo['contact_phone'];
	}else{ 
		/*Get company data for contact page  */
		db_b2b();
		$getCompId = db_query("SELECT ID, companyID FROM quote WHERE ID = ".$quote_id);
		$rowCompID = array_shift($getCompId);
		if(!empty($rowCompID['companyID'])){
			db_b2b();
			$getContactInfo = db_query("SELECT * FROM companyInfo WHERE ID = ".$rowCompID['companyID']);
			$rowContactInfo = array_shift($getContactInfo);
			if(!empty($rowContactInfo['contact'])){
				$arrContact = explode(" ", $rowContactInfo['contact']);
			}	
		}
		/*Initialize the variable & set value*/
		$cntInfoFNm 	= current($arrContact) ?? current($arrContact);
		$cntInfoLNm 	= end($arrContact) ?? end($arrContact);
		$cntInfoCompny 	= $rowContactInfo['company'] ?? $rowContactInfo['company'];
		$cntInfoEmail 	= $rowContactInfo['email'] ?? $rowContactInfo['email'];
		$cntInfoPhn = '';
		if ($rowContactInfo['phone'] == ""){
			if ($rowContactInfo['sellto_main_line_ph'] != ""){
				if ($rowContactInfo['sellto_main_line_ph_ext'] != ""){
					$cntInfoPhn 	= $rowContactInfo['sellto_main_line_ph'] . " x" . $rowContactInfo['sellto_main_line_ph_ext'];
				}else{
					$cntInfoPhn 	= $rowContactInfo['sellto_main_line_ph'];
				}
			}else{
				$cntInfoPhn 	= $rowContactInfo['mobileno'];
			}
		}else{
			$cntInfoPhn 	= $rowContactInfo['phone'] ?? $rowContactInfo['phone'];
		}
		
	}

	//echo "<pre>"; print_r($arrProdLoopId); echo "</pre>"; exit();

	$qry_loopbox = "SELECT * FROM loop_boxes WHERE id = '" . $arrProdLoopId[0] . "'";	
	
	db();		
	$res_loopbox = db_query($qry_loopbox);		
	$row_loopbox = array_shift($res_loopbox);
	$id2 = $row_loopbox["b2b_id"];	

	$qryb2b = "SELECT * FROM inventory WHERE id = '" . $id2 . "'";		
	db_b2b();
	$resb2b = db_query($qryb2b);		
	$rowb2b = array_shift($resb2b);
	$box_type = $rowb2b["box_type"];

	$browserTitle 	= get_b2bEcomm_boxType_BasicDetails($box_type, 1);
	$pgTitle 		= get_b2bEcomm_boxType_BasicDetails($box_type, 2);
	$idTitle		= get_b2bEcomm_boxType_BasicDetails($box_type, 3);
	$boxid_text		= get_b2bEcomm_boxType_BasicDetails($box_type, 8);


	//echo "<br /> row_loopbox - <pre>"; print_r($row_loopbox); echo "</pre>";
	//echo "<br /> rowb2b - <pre>"; print_r($rowb2b); echo "</pre>";
	?>
	<!doctype html>
	<html>
	<head>
	<meta charset="utf-8">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Quote #<?php echo$quote_id_tmp?> | UsedCardboardBoxes</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="CSS/style.css">
		<link rel="stylesheet" type="text/css" href="CSS/contact.css">
		 <link rel="stylesheet" href="CSS/radio-pure-css.css">
		
		<link rel="stylesheet" href="product-slider/slick.css">
		<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
		<link rel="stylesheet" href="product-slider/prod-style.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

		<script type="text/javascript" src="js/custom.js"></script>
	</head>
	<style type="text/css">
		.flyer{
			height: 80px;
			width: 60px;
		}
		.display_none{
			display: none;
		}
		.display_block{
			display: block;
		}
		.createAccSec{
			padding: 10px 10px 10px 10px;
			display: grid;
		}
		.input-radio:checked {
			border-color: '#4eb84b';
			margin: 10px 0px 0px 0px;
		}	
		.radio-wrapper{
			display: table;
			margin: 10px 0px 0px 5px;
		}

	</style>

	<body>

	<script>
		function chkCntactStatus1(){
			if (document.getElementById("chkContactFrmGuest").checked == true){
				//document.getElementById("chkContactFrmCreateAcc").checked = false;
				document.getElementById("createAccSection").style.display = "none";
			}else{
				//document.getElementById("chkContactFrmCreateAcc").checked = true;
			}
		}

		function chkCntactStatus2(){
			if (document.getElementById("chkContactFrmCreateAcc").checked == true){
				document.getElementById("createAccSection").style.display = "block";
			}else{
				document.getElementById("createAccSection").style.display = "none";
			}
		}
		
		function chkCntactStatus3(){
			if (document.getElementById("chkContactFrmLogin").checked == true){
				//document.getElementById("chkContactFrmCreateAcc").checked = false;
				document.getElementById("createAccSection").style.display = "none";
			}else{
				document.getElementById("chkContactFrmLogin").checked = true;
			}
		}
	</script>
		<div class="main_container">
			<div class="sub_container">
				<div class="header">
					<div id="container">
					<div id="left">
						<div class="logo_img">
							<div class="logo_display">
								<a href="https://www.usedcardboardboxes.com/">
								<img src="images/ucb_logo.jpg" alt="moving boxes"></a>
							</div>
						</div>
					</div>
					<div id="right">
						<div class="contact_number">
							<span class="login-username">
								<div class="needhelp">Need help? </div>
								<div class="needhelp_call"><img src="images/callicon.png" alt="" class="call_img">
								<strong>1-888-BOXES-88 (1-888-269-3788)</strong></div>
								<div class="needhelp"><?php include ("login.php");?></div>
							</span>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		<div class="sections new-section-margin">
			<div class="new_container no-top-padding">
				<div class="parentdiv">
				<div class="innerdiv">
					<div class="section-top-margin_1">
						<h1 class="section-title">Quote #<?php echo$quote_id_tmp; ?></h1>
					<div class="title_desc">Review and Proceed to Checkout!</div>
					</div>
					<!--Start Breadcrums-->
					<nav aria-label="Breadcrumb">
						<ol class="breadcrumb " role="list">
							<li class="breadcrumb__item breadcrumb__item--completed">
							  <a class="breadcrumb__link" href="index_quote.php?quote_id=<?php echo urlencode(encrypt_password($quote_id + 3770));?>">Select Quantity</a>
							  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
							</li>

							<li class="breadcrumb__item breadcrumb__item--current" aria-current="step">
							  <span class="breadcrumb__text breadcrumnow">Contact</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
							</li>
							  <li class="breadcrumb__item breadcrumb__item--blank">
							  <span class="breadcrumb__text">Shipping</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
							</li>
							  <li class="breadcrumb__item breadcrumb__item--blank">
							  <span class="breadcrumb__text">Payment</span>
							</li>
						</ol>
					  </nav>
					<!--End Breadcrums-->
					<div class="content-div content-padding">
						<div class="left_form">
							<div class="div-space"></div>

								<div class="contactinfo-container">
									<div class="cntinfo">
										<h2 class="contactinfo__title">
											Contact information
										</h2>
									</div>
								</div>
							<div class="div-space-frm"></div>
							
							<div class="floating-labels">
								<form name="frmContactInfo" id="frmContactInfo" action="shipping.php?quote_id=<?php echo $_REQUEST['quote_id'];?>" method="post">
									<div class="fieldset" id="contactInfoSection">
										<div class="field field--required field--half" data-address-field="first_name">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
											  <input placeholder="First name" autocomplete="shipping given-name" autocorrect="off" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoFNm" id="checkout_shipping_address_first_name" value="<?php echo $cntInfoFNm; ?>">
											</div>
										</div>
										<div class="field field--half" data-address-field="last_name">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
											  <input placeholder="Last name" autocomplete="shipping given-name" autocorrect="off" data-backup="last_name" class="field__input" size="30" type="text" name="txtCntInfoLNm" id="checkout_shipping_address_last_name" value="<?php echo $cntInfoLNm; ?>">
											</div>
										</div>
										<div data-address-field="company" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_company">Company</label>
												<input placeholder="Company" autocomplete="shipping organization" autocorrect="off" data-backup="company" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoCompny" id="checkout_shipping_address_company" value="<?php echo $cntInfoCompny; ?>">
											  </div>
										</div>
										<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (For order notifications)</label>
												<input placeholder="Email (for order notifications)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="txtCntInfoEmail" id="checkout_shipping_address_email" value="<?php echo $cntInfoEmail; ?>">
											  </div>
										</div>
										<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (For order notifications)</label>
												<input placeholder="Phone (1234567891)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="txtCntInfoPhn" id="checkout_shipping_address_phone" value="<?php echo $cntInfoPhn; ?>" onkeyup="addHyphen(this)" maxlength="12" >
											  </div>
										</div>
									</div>
									<div class="btn-div-shipping content-bottom-padding">
										<input type="hidden" id="quote_id_tmp" name="quote_id_tmp" value="<?php echo $quote_id;?>">
										<input type="hidden" id="quote_id" name="quote_id" value="<?php echo $_REQUEST['quote_id'];?>">

										<?php 
										//echo "<pre>"; print_r($arrProdDt); echo "</pre>"; 
										foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
										?>
											<input type="hidden" id="productIdloop" name="productIdloop[]" value="<?php echo $arrProdDtV["productIdloop"]?>">
											<input type="hidden" id="productNameid" name="productNameid[]" value="<?php echo $arrProdDtV["productQntypeid"]?>">
											<input type="hidden" id="productQntype" name="productQntype[]" value="<?php echo $arrProdDtV["productQntype"]?>">
											<input type="hidden" id="productQnt" name="productQnt[]" value="<?php echo $arrProdDtV['productQnt']?>">
											<input type="hidden" id="productQntprice" name="productQntprice[]" value="<?php echo $arrProdDtV['productQntprice']?>">
											<input type="hidden" id="productTotal" name="productTotal[]" value="<?php echo $arrProdDtV['productTotal']?>">
											<input type="hidden" id="hdAvailability" name="hdAvailability[]" value="<?php echo $arrProdDtV['hdAvailability'];?>">
											<input type="hidden" id="hdnItemId" name="hdnItemId[]" value="<?php echo $arrProdDtV["item_id"];?>">
										<?php } ?>

										<input type="hidden" name="hdnUserMastrId" value="<?php echo $orderData['user_master_id']?>">	
										<input type="hidden" name="hdnNoOfProd" value="<?php echo count($arrProdDt);?>">										
										<input type="button" name="btnContactInfo" id="btnContactInfo" class="button_slide slide_right" data-testid="order-button" onclick="chkFrmContactInfo();" value="Continue to shipping">
									</div>
								</form>
							</div>
						</div>
						
					</div>
					<!---->
					<div class="privacy-links_inner">
						<div class="bottomlinks">
						<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div><div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div><div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div><div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
						</div>
					</div>
				</div><!--End inner div-->
				<div class="innerdiv_2">
					<div class="collapsible"><div class="show-order" id="showorder">Show order summary
						<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
						
						
						</div>
						<div class="show-order-total">$<?php echo number_format($total, 2); ?></div>
					</div>
					<div class="inner-content" id="order-content">
						<?php require('item_sections.php'); ?>
						<div class="sidebar-sept"></div>
					
						<table class="sidebar-table">
							<tr>
								<th width="30%" align="left">Truckload</th>
								<th width="20%" align="center">Quantity</th>
								<th width="20%" align="right">Price/Unit</th>
								<th width="30%" align="right">Total</th>
							</tr>
							<?php 
							if(!empty($arrProdDt)){ 
								$total = 0;
								foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
								?>
									<tr>
										<td><?php if ($arrProdDtV['productIdloop'] >= 1 && $arrProdDtV['productIdloop'] <= 5) {
												echo $arrProdDtV['productQntype'];
											   }else if ($arrProdDtV['productIdloop'] >= 5){
													echo "ID: ". get_b2b_box_id($arrProdDtV['productIdloop']);
											   }
											?></td>
										<td id="totolProQnt" align="center"><?php echo number_format((float)str_replace(",", "" , $arrProdDtV['productQnt']),0) ; ?></td>
										<td align="right">$<?php echo number_format((float)str_replace(",", "" ,$arrProdDtV['productQntprice']),2); ?></td>
										<td align="right">$<?php echo number_format((float)trim($arrProdDtV['productTotal']), 2); ?></td>
									</tr>
								<?php 
								$total = $total + $arrProdDtV['productTotal']; 
								}
							} ?>
							<?php 
							db_b2b();
							$getQuoteShipping = db_query("SELECT free_shipping FROM quote WHERE ID = ".$quote_id);
							$rowQuoteShipping = array_shift($getQuoteShipping);
							if($rowQuoteShipping['free_shipping'] == 1 ){ 
							?>
								<tr>
									<td>Delivery</td>
									<td align="center">1</td>
									<td align="right">$0.00</td>
									<td align="right">$0.00</td>
								</tr>
							<?php } ?>
							<tr><td colspan="4"><div class="sidebar-sept-intable"></div></td></tr>
							<tr>
								<td></td>
								<td></td>
								<td align="right" style="font-weight: 500;">Total</td>
								<td align="right"><span class="payment-due__price">$<?php echo number_format($total, 2); ?></span></td>
							</tr>
						</table>
						
						<div style="padding-top: 60px;">
							<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">&nbsp;</span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1">&nbsp;</span>
								</li>
								<!-- <li>
									<label for="distance"></label>
									<span id="distance"> mi from item origin.</span>
								</li> -->
								<li>
									<label for="Span2">Payment Info</label>
									<span id="Span2"></span>
								</li>
							</ol>
						</div>
					</div>
				</div>
				<script>
						$(".collapsible").click(function(){

							$("div#order-content").slideToggle("slow", function(){
								if($("div#order-content").is(":visible")){
									$("div.show-order").html('Hide order summary <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z"></path></svg>');
								} else{
								 $("div.show-order").html('Show order summary <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>');
								}
							});
						});

					</script>
				</div>
			</div>
		</div>
		<div class="footer_l">	
			<div class="copytxt">© UsedCardboardBoxes</div>
		</div>
	</body>
	</html>
	<script>
	function addHyphen(e){
		if (event.keyCode != 8){
			e.value = e.value.replace(/[^\d ]/g,'');
			if (e.value.length==3 || e.value.length==7 );
			//e.value=e.value+" ";	
		}	
	}
	</script>	


<?php }else{ ?>
	<?php 
	$orderData['productName'] 		= $_REQUEST["productQntype"];
	$orderData['productQntypeid'] 	= $_REQUEST["productQntypeid"];
	$orderData['productQnt'] 		= $_REQUEST["productQnt"];
	$orderData['productUnitPr'] 	= $_REQUEST["productQntprice"];
	$orderData['productTotal'] 		= $_REQUEST["productTotal"];

	if(isset($_REQUEST['id'])){
		$ProductLoopId = $_REQUEST["id"];
		db();
		$getDt = db_query("SELECT b2becommerce_order_item.*, b2becommerce_order_item_details.*  FROM b2becommerce_order_item INNER JOIN b2becommerce_order_item_details ON b2becommerce_order_item_details.order_item_id = b2becommerce_order_item.id WHERE b2becommerce_order_item.session_id = '".$sessionId."' AND b2becommerce_order_item.product_loopboxid = '" . $ProductLoopId."'");
		$rowDt = array_shift($getDt);
		$orderData['ProductLoopId'] 	= $_REQUEST["id"];
		$orderData['productName'] 		= $rowDt["product_name"];
		$orderData['productQntypeid'] 	= $rowDt["product_name_id"];
		$orderData['productQnt'] 		= $rowDt["product_qty"];
		$orderData['productUnitPr'] 	= $rowDt["product_unitprice"];
		$orderData['productTotal'] 		= $rowDt["product_total"];
		$orderData['hdAvailability'] 	= $_SESSION['hdAvailability'];
	}else{
		$ProductLoopId = $_REQUEST["productIdloop"];
		$orderData['hdAvailability'] 	= $_REQUEST["hdAvailability"];
	}

	$orderData['ProductLoopId'] 	= $ProductLoopId;
	db();
	$getSessionDt = db_query("SELECT id FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' AND product_loopboxid = '" . $ProductLoopId ."'");
	$rowSessionDt = array_shift($getSessionDt);

	$machineIP 	= $_SERVER['REMOTE_ADDR'];
	
	if ($_REQUEST["productQntypeid"] != ""){		
		if(empty($rowSessionDt['id'])){
			db();
			$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(user_master_id, session_id, product_loopboxid , machine_ip, lead_time) 
			VALUES('".$userMasterId."', '".$sessionId."', '". str_replace("'", "\'" ,$ProductLoopId) ."', '" . $machineIP . "', '".$_REQUEST["hdLeadTime"]."')");
			$insertedId = tep_db_insert_id();
			db();
			$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item_details(order_item_id, product_id, product_name_id, product_name, product_qty, product_unitprice, product_total, product_lead_time) 
			VALUES( ".$insertedId.", '" . $ProductLoopId ."', '".str_replace("'", "\'" ,$_REQUEST["productQntypeid"])."', '". str_replace("'", "\'" ,$_REQUEST["productQntype"]) ."', '". str_replace("'", "\'" ,$_REQUEST["productQnt"]) ."', '". str_replace("'", "\'" ,$_REQUEST["productQntprice"]) ."', '". str_replace("'", "\'" ,$_REQUEST["productTotal"]) ."', '".$_REQUEST["hdLeadTime"]."')");

			$responce = $insertedId;
		}else{
			$responce = $rowSessionDt['id'];
			db();
			$qryOrderDt = db_query("UPDATE b2becommerce_order_item_details SET product_name_id = '". str_replace("'", "\'" ,$_REQUEST["productQntypeid"]) ."', product_name = '". str_replace("'", "\'" ,$_REQUEST["productQntype"]) ."', product_qty = '". str_replace("'", "\'" ,$_REQUEST["productQnt"]) . "', product_unitprice = '". $_REQUEST["productQntprice"] ."', product_total = '". $_REQUEST["productTotal"] ."', product_lead_time = '".$_REQUEST["hdLeadTime"]."'  WHERE order_item_id = '" . $rowSessionDt['id'] ."'");
		}
	}
	$cntInfoPhn = "";
	$cntInfoEmail = "";
	$cntInfoCompny = "";
	$cntInfoLNm = "";
	$cntInfoFNm = "";
	if($_COOKIE['uid'] != ""){ 
		db();
		$getUserDt = db_query("SELECT * FROM b2becommerce_user_master WHERE userid  = '".$_COOKIE['uid']."'");
		while ($rowUserDt = array_shift($getUserDt)) {
			$cntInfoFNm 	= $rowUserDt['first_name'];
			$cntInfoLNm 	= $rowUserDt['last_name'];
			$cntInfoCompny 	= $rowUserDt['companyid'];
			$cntInfoEmail 	= $rowUserDt['email'];
			$cntInfoPhn 	= $rowUserDt['phone'];
		}
	}else if ($sessionId){  
		db();
		$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' AND product_loopboxid = '" . $ProductLoopId ."'");
		while ($rowContactInfo = array_shift($getSessionDt)) {
			$cntInfoFNm 	= $rowContactInfo['contact_firstname'];
			$cntInfoLNm 	= $rowContactInfo['contact_lastname'];
			$cntInfoCompny 	= $rowContactInfo['contact_company'];
			$cntInfoEmail 	= $rowContactInfo['contact_email'];
			$cntInfoPhn 	= $rowContactInfo['contact_phone'];
		}
		
	}else{ 
		/*Get data depends on user master id  */
		$rowContactInfo = array();
		if($orderData['user_master_id'] > 0 ){
			db();
			$getUserDt = db_query("SELECT companyid FROM b2becommerce_user_master WHERE userid = ".$orderData['user_master_id']);
			$rowUserDt = array_shift($getUserDt);
		}
		if(!empty($rowUserDt['companyid'])){
			db_b2b();
			$getContactInfo = db_query("SELECT * FROM companyInfo WHERE ID = ".$rowUserDt['companyid']);
			$rowContactInfo = array_shift($getContactInfo);
			if(!empty($rowContactInfo['contact'])){
				$arrContact = explode(" ", $rowContactInfo['contact']);
			}	
		}
		/*Initialize the variable & set value*/
		$cntInfoFNm 	= current($arrContact) ?? current($arrContact);
		$cntInfoLNm 	= end($arrContact) ?? end($arrContact);
		$cntInfoCompny 	= $rowContactInfo['company'] ?? $rowContactInfo['company'];
		$cntInfoEmail 	= $rowContactInfo['email'] ?? $rowContactInfo['email'];
		if ($rowContactInfo['phone'] == ""){
			$cntInfoPhn 	= $rowContactInfo['sellto_main_line_ph'];
			if ($rowContactInfo['sellto_main_line_ph_ext'] != ""){
				$cntInfoPhn 	= $cntInfoPhn . " x" . $rowContactInfo['sellto_main_line_ph_ext'];
			}
		}else{
			$cntInfoPhn 	= $rowContactInfo['phone'] ?? $rowContactInfo['phone'];
		}
		
	}	

	$qry_loopbox = "SELECT * FROM loop_boxes WHERE id = '" . $ProductLoopId . "'";	
	db();	
	$res_loopbox = db_query($qry_loopbox);		
	$row_loopbox = array_shift($res_loopbox);
	$id2 = $row_loopbox["b2b_id"];	

	$qryb2b = "SELECT * FROM inventory WHERE id = '" . $id2 . "'";	
	db_b2b();	
	$resb2b = db_query($qryb2b);		
	$rowb2b = array_shift($resb2b);

	$box_type = $rowb2b["box_type"];

	$boxid_text		= "Item";
	$pgTitle = "";
	$browserTitle = "";
	if (in_array(strtolower($box_type), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
		$browserTitle 	= "Buy Gaylord Totes"; 
		$pgTitle		= "Buy Gaylord Totes";
		$idTitle		= "Gaylord ID";
		$boxid_text		= "Gaylord";
	}elseif (in_array(strtolower($box_type), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
		$browserTitle 	= "Buy Shipping Boxes"; 
		$pgTitle		= "Buy Shipping Boxes";
		$idTitle		= "Shipping Box ID";
		$boxid_text		= "Shipping Box";
	}elseif (in_array(strtolower($box_type), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
		$browserTitle 	= "Buy Super Sacks";
		$pgTitle		= "Buy Super Sacks";
		$idTitle		= "Super Sack ID";
		$boxid_text		= "Super Sack";
	}elseif (in_array(strtolower($box_type), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
		$browserTitle 	= "Buy Pallets"; 
		$pgTitle 		= "Buy Pallets"; 
		$idTitle		= "Pallet ID";
		$boxid_text		= "Pallet";
	}elseif (in_array(strtolower($box_type), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other")))) { 
		$browserTitle 	= "Buy Items"; 
		$pgTitle 		= "Buy Items";
		$idTitle		= "Item ID";
		$boxid_text		= "Item";
	}

	//echo 'uid -> '.$_COOKIE['uid'];

	?>
	<!doctype html>
	<html>
	<head>
	<meta charset="utf-8">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo$browserTitle?> | UsedCardboardBoxes</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="CSS/style.css">
		<link rel="stylesheet" type="text/css" href="CSS/contact.css">
		 <link rel="stylesheet" href="CSS/radio-pure-css.css">
		
		<link rel="stylesheet" href="product-slider/slick.css">
		<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
		<link rel="stylesheet" href="product-slider/prod-style.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

		<script type="text/javascript" src="js/custom.js"></script>
	</head>
	<style type="text/css">
		.flyer{
			height: 80px;
			width: 60px;
		}
		.display_none{
			display: none;
		}
		.display_block{
			display: block;
		}
		.createAccSec{
			padding: 10px 10px 10px 10px;
			display: grid;
		}
		.input-radio:checked {
			border-color: '#4eb84b';
			margin: 10px 0px 0px 0px;
		}	
		.radio-wrapper{
			display: table;
			margin: 10px 0px 0px 5px;
		}

	</style>

	<body>

	<script>
		function chkCntactStatus1(){
			if (document.getElementById("chkContactFrmGuest").checked == true){
				//document.getElementById("chkContactFrmCreateAcc").checked = false;
				document.getElementById("createAccSection").style.display = "none";
			}else{
				//document.getElementById("chkContactFrmCreateAcc").checked = true;
			}
		}

		function chkCntactStatus2(){
			if (document.getElementById("chkContactFrmCreateAcc").checked == true){
				document.getElementById("createAccSection").style.display = "block";
			}else{
				document.getElementById("createAccSection").style.display = "none";
			}
		}
		
		function chkCntactStatus3(){
			if (document.getElementById("chkContactFrmLogin").checked == true){
				//document.getElementById("chkContactFrmCreateAcc").checked = false;
				document.getElementById("createAccSection").style.display = "none";
			}else{
				document.getElementById("chkContactFrmLogin").checked = true;
			}
		}
	</script>
		<div class="main_container">
			<div class="sub_container">
				<div class="header">
					<div class="logo_img"><a href="https://www.usedcardboardboxes.com/"><img src="images/ucb_logo.jpg" alt="moving boxes"></a></div>
					<div class="contact_number">
						<span class="login-username">
							<div class="needhelp">Need help? </div>
							<div class="needhelp_call"><img src="images/callicon.png" alt="" class="call_img">
							<strong>1-888-BOXES-88 (1-888-269-3788)</strong></div>
							<div class="needhelp"><?php include ("login.php");?></div>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="sections new-section-margin">
			<div class="new_container no-top-padding">
				<div class="parentdiv">
				<div class="innerdiv">
					<div class="section-top-margin_1">
						<h1 class="section-title"><?php echo$pgTitle;?></h1>
					</div>
					<!--Start Breadcrums-->
					<nav aria-label="Breadcrumb">
						<ol class="breadcrumb " role="list">
							<li class="breadcrumb__item breadcrumb__item--completed">
							  <a class="breadcrumb__link" href="index.php?id=<?php echo $ProductLoopId;?>&product_name_id=<?php echo $orderData['productQntypeid'];?>">Select Quantity</a>
							  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
							</li>

							  <li class="breadcrumb__item breadcrumb__item--current" aria-current="step">
							  <span class="breadcrumb__text breadcrumnow">Contact</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
							</li>
							  <li class="breadcrumb__item breadcrumb__item--blank">
							  <span class="breadcrumb__text">Shipping</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
							</li>
							  <li class="breadcrumb__item breadcrumb__item--blank">
							  <span class="breadcrumb__text">Payment</span>
							</li>
						</ol>
					  </nav>
					<!--End Breadcrums-->
					<div class="content-div content-padding">
						<div class="left_form">
							<!--<div class="frm-txt">Provide your contact information so we can communicate updates about your order:</div>-->
							<div class="div-space"></div>

								<div class="contactinfo-container">
									<div class="cntinfo">
										<h2 class="contactinfo__title">
											Contact information
										</h2>
									</div>
									<div class="cntinfo">
									</div>
								</div>
							<div class="div-space-frm"></div>
							
							<div class="floating-labels">
								<form name="frmContactInfo" id="frmContactInfo" action="shipping.php" method="post">
									<div class="fieldset" id="contactInfoSection">
										<div class="field field--required field--half" data-address-field="first_name">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
											  <input placeholder="First name" autocomplete="shipping given-name" autocorrect="off" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoFNm" id="checkout_shipping_address_first_name" value="<?php echo $cntInfoFNm; ?>">
											</div>
										</div>
										<div class="field field--half" data-address-field="last_name">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
											  <input placeholder="Last name" autocomplete="shipping given-name" autocorrect="off" data-backup="last_name" class="field__input" size="30" type="text" name="txtCntInfoLNm" id="checkout_shipping_address_last_name" value="<?php echo $cntInfoLNm; ?>">
											</div>
										</div>
										<div data-address-field="company" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_company">Company</label>
												<input placeholder="Company" autocomplete="shipping organization" autocorrect="off" data-backup="company" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoCompny" id="checkout_shipping_address_company" value="<?php echo $cntInfoCompny; ?>">
											  </div>
										</div>
										<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (For order notifications)</label>
												<input placeholder="Email (for order notifications)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="txtCntInfoEmail" id="checkout_shipping_address_email" value="<?php echo $cntInfoEmail; ?>">
											  </div>
										</div>
										<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

											  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (For order notifications)</label>
												<input placeholder="Phone (1234567891)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="txtCntInfoPhn" id="checkout_shipping_address_phone" value="<?php echo $cntInfoPhn; ?>" onkeyup="addHyphen(this)" maxlength="12" >
											  </div>
										</div>
									</div>
								<?php 
								if($_COOKIE['uid'] == ""){
								?>
								<?php 
								}
								?>		
									<div class="btn-div-shipping content-bottom-padding">
										<input type="hidden" name="hdnLastInsertId" id="hdnLastInsertId" value="">	
										<input type="hidden" id="productIdloop" name="productIdloop" value="<?php echo $orderData['ProductLoopId']?>">
										<input type="hidden" id="productQntypeid" name="productQntypeid" value="<?php echo $orderData['productQntypeid']?>">
										<input type="hidden" id="productNameid" name="productNameid" value="<?php echo $orderData['productQntypeid']?>">
										<input type="hidden" id="productQntype" name="productQntype" value="<?php echo $orderData['productName']?>">
										<input type="hidden" id="productQnt" name="productQnt" value="<?php echo $orderData['productQnt']?>">
										<input type="hidden" id="productQntprice" name="productQntprice" value="<?php echo $orderData['productUnitPr']?>">
										<input type="hidden" id="productTotal" name="productTotal" value="<?php echo $orderData['productTotal']?>">
										<input type="hidden" name="hdnUserMastrId" value="<?php echo $orderData['user_master_id']?>">
										<input type="hidden" id="hdAvailability" name="hdAvailability" value="<?php echo $orderData['hdAvailability'];?>">
										<input type="button" name="btnContactInfo" id="btnContactInfo" class="button_slide slide_right" data-testid="order-button" onclick="chkFrmContactInfo();" value="Continue to shipping">
									</div>
								</form>
							</div>
						</div>
						
					</div>
					<!---->
					<div class="privacy-links_inner">
						<div class="bottomlinks">
						<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div><div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div><div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div><div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
						</div>
					</div>
				</div><!--End inner div-->
				<div class="innerdiv_2">
					<div class="collapsible"><div class="show-order" id="showorder">Show order summary
						<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
						
						
						</div>
						<div class="show-order-total">$<?php echo $orderData['productTotal'] ; ?></div>
					</div>
					<div class="inner-content" id="order-content">
						<?php require('item_sections.php'); ?>
						<div class="sidebar-sept"></div>
					
						<table class="sidebar-table">
							<tr>
								<th width="30%" align="left">Truckload</th>
								<th width="20%" align="center">Quantity</th>
								<th width="20%" align="right">Price/Unit</th>
								<th width="30%" align="right">Total</th>
							</tr>
							<?php 
							if(!empty($orderData)){ ?>
								<tr>
									<td><?php echo $orderData['productName']; ?></td>
									<td id="totolProQnt" align="center"><?php echo number_format((float)str_replace(",", "" , $orderData['productQnt']),0) ; ?></td>
									<td align="right">$<?php echo number_format((float)str_replace(",", "" ,$orderData['productUnitPr']),2); ?></td>
									<td align="right">$<?php echo trim($orderData['productTotal']); ?></td>
								</tr>
							<?php } ?>
							<tr>
								<td class="caltxt">Shipping Quote</td>
								<td></td>
								<td></td>
								<td align="right" class="caltxt">Calculated at next step</td>
							</tr>
							<tr><td colspan="4"><div class="sidebar-sept-intable"></div></td></tr>
							<tr>
								<td></td>
								<td></td>
								<td align="right" style="font-weight: 500;">Total</td>
								<td align="right"><span class="payment-due__price">$<?php echo trim($orderData['productTotal']); ?></span></td>
							</tr>
						</table>
						
						<div style="padding-top: 60px;">
							<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">&nbsp;</span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1">&nbsp;</span>
								</li>
								<!-- <li>
									<label for="distance"></label>
									<span id="distance"> mi from item origin.</span>
								</li> -->
								<li>
									<label for="Span2">Payment Info</label>
									<span id="Span2"></span>
								</li>
							</ol>
						</div>
					</div>
				</div>
				<script>
						$(".collapsible").click(function(){

							$("div#order-content").slideToggle("slow", function(){
								if($("div#order-content").is(":visible")){
									$("div.show-order").html('Hide order summary <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z"></path></svg>');
								} else{
								 $("div.show-order").html('Show order summary <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>');
								}
							});
						});

					</script>
				</div>
			</div>
		</div>
		<div class="footer_l">	
			<div class="copytxt">© UsedCardboardBoxes</div>
		</div>
	</body>
	</html>
	<script>
	function addHyphen(e){
		if (event.keyCode != 8){
			e.value = e.value.replace(/[^\d ]/g,'');
			if (e.value.length==3 || e.value.length==7 );
			//e.value=e.value+" ";	
		}	
	}
	</script>	

<?php } ?>
