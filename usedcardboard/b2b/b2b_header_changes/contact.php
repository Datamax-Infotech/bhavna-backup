<?
session_start();

//ini_set("display_errors", "1");
//error_reporting(E_ALL);

require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$sessionId = session_id();

$orderData['productName'] 		= $_REQUEST["productQntype"];
$orderData['productQntypeid'] 	= $_REQUEST["productQntypeid"];
$orderData['productQnt'] 		= $_REQUEST["productQnt"];
$orderData['productUnitPr'] 	= $_REQUEST["productQntprice"];
$orderData['productTotal'] 		= $_REQUEST["productTotal"];
$quoteTotal = 0;

if(isset($_REQUEST['compnewid'])){
	$compnewid = decrypt_password($_REQUEST['compnewid']);
	$_SESSION['compnewid'] = $compnewid;
}

if(isset($_REQUEST['id'])){
	$ProductLoopId = decrypt_password($_REQUEST["id"]);
	
	$getDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId."'", db() );
	//echo "SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId."'";
	$rowDt = array_shift($getDt);
	$orderData['ProductLoopId'] 	= $ProductLoopId;
	$orderData['productName'] 		= $rowDt["product_name"];
	$orderData['productQntypeid'] 	= $rowDt["product_name_id"];
	$orderData['productQnt'] 		= $rowDt["product_qty"];
	$orderData['productUnitPr'] 	= $rowDt["product_unitprice"];
	$orderData['productTotal'] 		= $rowDt["product_total"];
	$orderData['hdAvailability'] 	= $_SESSION['hdAvailability'];
	$quoteTotal                     = $rowDt["quote_total"];
}else{
	$ProductLoopId = $_REQUEST["productIdloop"];
	$orderData['hdAvailability'] 	= $_REQUEST["hdAvailability"];
}

$orderData['ProductLoopId'] 	= $ProductLoopId;


if ($_REQUEST["productQntypeid"] != ""){
	$getSessionDt = db_query("SELECT id,quote_total FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db() );
	$rowSessionDt = array_shift($getSessionDt);
	$quoteTotal   = $rowSessionDt["quote_total"];
	if(empty($rowSessionDt['id'])){
		$qryOrderDt = db_query("INSERT INTO b2becommerce_order_item(user_master_id, session_id, product_loopboxid, product_name_id, product_name, product_qty, 
		product_unitprice, product_total, lead_time) 
		VALUES('".$userMasterId."', '".$sessionId."', '". $ProductLoopId ."', '".$_REQUEST["productQntypeid"]."', '".$_REQUEST["productQntype"]."', '".$_REQUEST["productQnt"]."', '".$_REQUEST["productQntprice"]."', '".$_REQUEST["productTotal"]."', '" . str_replace("'", "\'", $_REQUEST["hdAvailability"]) . "' )", db());
		$insertedId = tep_db_insert_id();
		$responce = $insertedId;
	}else{
		$responce = $rowSessionDt['id'];
		$qryOrderDt = db_query("Update b2becommerce_order_item set product_name_id = '". $_REQUEST["productQntypeid"] ."', product_name = '".$_REQUEST["productQntype"]."', product_qty = '". $_REQUEST["productQnt"] . "', product_unitprice = '". $_REQUEST["productQntprice"] ."', product_total = '". $_REQUEST["productTotal"] ."' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId ."'", db());
	}
}
if($_COOKIE['uid'] != ""){ 
	$getUserDt = db_query("SELECT * FROM b2becommerce_user_master WHERE userid  = '".$_COOKIE['uid']."'", db());
	while ($rowUserDt = array_shift($getUserDt)) {
		$cntInfoFNm 	= $rowUserDt['first_name'];
		$cntInfoLNm 	= $rowUserDt['last_name'];
		$cntInfoCompny 	= $rowUserDt['companyid'];
		$cntInfoEmail 	= $rowUserDt['email'];
		$cntInfoPhn 	= $rowUserDt['phone'];
	}
}else if(isset($_SESSION['compnewid']) && !empty($_SESSION['compnewid'])){
		$getCompContactInfo = db_query("SELECT * FROM companyInfo WHERE ID = ".$_SESSION['compnewid'], db_b2b());
		$rowCompContactInfo = array_shift($getCompContactInfo);
		if(!empty($rowCompContactInfo['contact'])){
			$arrContact = explode(" ", $rowCompContactInfo['contact']);
		}
		/*Initialize the variable & set value*/
		$cntInfoFNm 	= current($arrContact) ?? current($arrContact);
		$cntInfoLNm 	= end($arrContact) ?? end($arrContact);
		$cntInfoCompny 	= $rowCompContactInfo['company'] ?? $rowCompContactInfo['company'];
		$cntInfoEmail 	= $rowCompContactInfo['email'] ?? $rowCompContactInfo['email'];
		$cntInfoPhn = '';
		if ($rowCompContactInfo['phone'] == ""){
			if ($rowCompContactInfo['sellto_main_line_ph'] != ""){
				if ($rowCompContactInfo['sellto_main_line_ph_ext'] != ""){
					$cntInfoPhn 	= $rowCompContactInfo['sellto_main_line_ph'] . " x" . $rowCompContactInfo['sellto_main_line_ph_ext'];
				}else{
					$cntInfoPhn 	= $rowCompContactInfo['sellto_main_line_ph'];
				}
			}else{
				$cntInfoPhn 	= $rowCompContactInfo['mobileno'];
			}
		}else{
			$cntInfoPhn 	= $rowCompContactInfo['phone'] ?? $rowCompContactInfo['phone'];
		}
		$_SESSION['contactcompnewid'] = $_SESSION['compnewid'];
		//unset($_SESSION['compnewid']);
}else if ($sessionId){  
	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db() );
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$cntInfoFNm 	= $rowContactInfo['contact_firstname'];
		$cntInfoLNm 	= $rowContactInfo['contact_lastname'];
		$cntInfoCompny 	= $rowContactInfo['contact_company'];
		$cntInfoEmail 	= $rowContactInfo['contact_email'];
		$cntInfoPhn 	= $rowContactInfo['contact_phone'];
		$quoteTotal     = $rowContactInfo["quote_total"];
	}
	
}else{ 
	/*Get data depends on user master id  */
	if($orderData['user_master_id'] > 0 ){
		$getUserDt = db_query("SELECT companyid FROM b2becommerce_user_master WHERE userid = ".$orderData['user_master_id'], db());
		$rowUserDt = array_shift($getUserDt);
	}
	if(!empty($rowUserDt['companyid'])){
		$getContactInfo = db_query("SELECT * FROM companyInfo WHERE ID = ".$rowUserDt['companyid'], db_b2b());
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
	$cntInfoPhn 	= $rowContactInfo['phone'] ?? $rowContactInfo['phone'];
}	

$qry_loopbox = "Select * FROM loop_boxes WHERE id = '" . $ProductLoopId . "'";		
$res_loopbox = db_query($qry_loopbox, db() );		
$row_loopbox = array_shift($res_loopbox);
$id2 = $row_loopbox["b2b_id"];	
$invId = $id2;

$qryb2b = "Select * FROM inventory WHERE id = '" . $id2 . "'";		
$resb2b = db_query($qryb2b, db_b2b() );		
$rowb2b = array_shift($resb2b);

$box_type = $rowb2b["box_type"];

$boxid_text		= "Item";
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
	<title><?=$browserTitle?> | UsedCardboardBoxes</title>
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
<?php require_once('boomerange_common_header.php'); ?>
	<div class="sections">
		<div class="new_container no-top-padding">
			<div class="parentdiv">
			<div class="innerdiv">
			<div class="section-top-margin_1">
				<h1 class="section-title"><?=$pgTitle;?></h1>
			</div>
			<!--Start Breadcrums-->
			<nav aria-label="Breadcrumb">
			<ol class="breadcrumb " role="list">
				<li class="breadcrumb__item breadcrumb__item--completed">
				  <a class="breadcrumb__link" href="index.php?id=<? echo urlencode(encrypt_password($ProductLoopId));?>&product_name_id=<? echo $orderData['productQntypeid'];?>">Select Quantity</a>
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
								<!-- <p class="layout-flex__item">
									<span aria-hidden="true">Already have an account?</span>
									<a href="javascript:openmodal('signin');">
										Log in
									</a>        
								</p> -->
							</div>
						</div>

					<!-- <div class="contact-form-option">
						<div class="frm-txt-check-contact">
							<input id="chkContactFrmGuest" type="radio" name="chkContactFrmGuest" value="1" checked="checked" onchange="chkCntactStatus1()">
							<label for="chkContactFrmGuest"><span>&nbsp;</span>Proceed as guest</label>
						</div>
					</div> -->
					<div class="div-space-frm"></div>
					
					<div class="floating-labels">
						<form name="frmContactInfo" id="frmContactInfo" action="shipping.php" method="post">
							<div class="fieldset" id="contactInfoSection">
								<div class="field field--required field--half" data-address-field="first_name">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
									  <input placeholder="First name" autocomplete="shipping given-name" autocorrect="off" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoFNm" id="checkout_shipping_address_first_name" value="<? echo $cntInfoFNm; ?>">
									</div>
								</div>
								<div class="field field--required field--half" data-address-field="last_name">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
									  <input placeholder="Last name" autocomplete="shipping given-name" autocorrect="off" data-backup="last_name" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoLNm" id="checkout_shipping_address_last_name" value="<? echo $cntInfoLNm; ?>">
									</div>
								</div>
								<div data-address-field="company" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_company">Company</label>
										<input placeholder="Company" autocomplete="shipping organization" autocorrect="off" data-backup="company" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoCompny" id="checkout_shipping_address_company" value="<? echo $cntInfoCompny; ?>">
									  </div>
								</div>
								<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (For order notifications)</label>
										<input placeholder="Email (for order notifications)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="txtCntInfoEmail" id="checkout_shipping_address_email" value="<? echo $cntInfoEmail; ?>">
									  </div>
								</div>
								<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (For order notifications)</label>
										<input placeholder="Phone (1234567891)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="txtCntInfoPhn" id="checkout_shipping_address_phone" value="<? echo $cntInfoPhn; ?>" onkeyup="addHyphen(this)" maxlength="12" >
									  </div>
								</div>
							</div>
						<?
						if($_COOKIE['uid'] == ""){
						?>
							<!-- <div class="div-space"></div>
							<div class="new-acct-margin">
								<div class="contact-form-option">
									<div class="frm-txt-check-contact">
										<input id="chkContactFrmCreateAcc" type="checkbox" name="chkContactFrmCreateAcc" value="2" onchange="chkCntactStatus2()">
										<label for="chkContactFrmCreateAcc"><span></span>Create an account (saves time in future)</label>
									</div>
								</div>
								<div class="div-space-frm"></div>
								<div class="createAccSec" id="createAccSection" style="display:none;">
									<div id="errCrAcc" style="color: red;"></div>
									<div class="fieldset"  >
										<div data-address-field="password" data-autocomplete-field-container="true" class="field field--optional">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="txtCrAccPass">Password</label>
											  <input placeholder="Password" autocomplete="" autocorrect="off" data-backup="password" class="field__input" aria-required="true" size="30" type="password" name="txtCrAccPass" id="txtCrAccPass" value="" >
											</div>
										</div>
										<div data-address-field="conf_password" data-autocomplete-field-container="true" class="field field--optional">
											<div class="field__input-wrapper"><label class="field__label field__label--visible" for="txtCrAccPassConfrm">Confirm Password</label>
											  <input placeholder="Confirm Password" autocomplete="" autocorrect="off" data-backup="confirm_password" class="field__input" aria-required="true" size="30" type="password" name="txtCrAccPassConfrm" id="txtCrAccPassConfrm" value="" >
											</div>
										</div>
									</div>
								</div>
									
							</div>
							<div class="div-space"></div> -->
							<!--<div class="contact-form-option">
								<div class="frm-txt-check-contact">
									<input id="chkContactFrmLogin" type="radio" name="chkContactFrmLogin" value="3" onchange="javascript:openmodal('signin');" onclick="chkCntactStatus3()">
									<label for="chkContactFrmLogin"><span>&nbsp;</span>Login to your account</label>
								</div>
							</div>
							<div class="div-space-frm"></div> -->
							<!-- <div class="fieldset" id="loginSection">
								<div data-address-field="eemail" data-autocomplete-field-container="true" class="field field--optional">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_paswd">Email</label>
									  <input placeholder="Email" autocomplete="" autocorrect="off" data-backup="paswd" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoFNm2" id="checkout_e" value="">
									</div>
								</div>
								<div data-address-field="conf_password" data-autocomplete-field-container="true" class="field field--optional">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_conf_pass">Last name</label>
									  <input placeholder="Password" autocomplete="" autocorrect="off" data-backup="last_name" class="field__input" aria-required="true" size="30" type="text" name="txtCntInfoLNm2" id="checkout_e_pass" value="">
									</div>
								</div>
							</div> -->
						<?
						}
						?>		
							<div class="btn-div-shipping content-bottom-padding">
								<input type="hidden" name="hdnLastInsertId" id="hdnLastInsertId" value="">	
								<input type="hidden" id="productIdloop" name="productIdloop" value="<?=$orderData['ProductLoopId']?>">
								<input type="hidden" id="productQntypeid" name="productQntypeid" value="<?=$orderData['productQntypeid']?>">
								<input type="hidden" id="productNameid" name="productNameid" value="<?=$orderData['productQntypeid']?>">
								<input type="hidden" id="productQntype" name="productQntype" value="<?=$orderData['productName']?>">
								<input type="hidden" id="productQnt" name="productQnt" value="<?=$orderData['productQnt']?>">
								<input type="hidden" id="productQntprice" name="productQntprice" value="<?=$orderData['productUnitPr']?>">
								<input type="hidden" id="productTotal" name="productTotal" value="<?=$orderData['productTotal']?>">
								<input type="hidden" id="session_login_id" name="session_login_id" value="<?=$_REQUEST['session_login_id']?>">
								
								<input type="hidden" name="hdnUserMastrId" value="<?=$orderData['user_master_id']?>">
								<input type="hidden" id="hdAvailability" name="hdAvailability" value="<? echo $orderData['hdAvailability'];?>">
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
					<div class="show-order-total">$<? echo $orderData['productTotal'] ; ?></div>
				</div>
				<div class="inner-content" id="order-content">
					<? require('item_sections.php'); ?>
					<div class="sidebar-sept"></div>
				
					<table class="sidebar-table">
						<tr>
							<th width="30%" align="left">Truckload</th>
							<th width="20%" align="center">Quantity</th>
							<th width="20%" align="right">Price/Unit</th>
							<th width="30%" align="right">Total</th>
						</tr>
						<? 
						if(!empty($orderData)){ ?>
							<tr>
								<td><? echo $orderData['productName']; ?></td>
								<td id="totolProQnt" align="center"><? echo number_format(str_replace(",", "" , $orderData['productQnt']),0) ; ?></td>
								<td align="right">$<? echo number_format(str_replace(",", "" ,$orderData['productUnitPr']),2); ?></td>
								<td align="right">$<? echo trim($orderData['productTotal']); ?></td>
							</tr>
						<? } ?>
						<? if ($quoteTotal <> 0) { ?>
							<tr>
								<td id="quoteName" >Shipping Quote</td>
								<td align="center" id="quoteQty">1</td>
								<td align="right" id="quoteUnitPr">$<? echo $quoteTotal; ?></td>
								<td align="right" id="quoteTotal">$<? echo $quoteTotal; ?></td>
							</tr>
						<? }else{ ?>	
							<tr>
								<td class="caltxt">Shipping Quote</td>
								<td></td>
								<td></td>
								<td align="right" class="caltxt">Calculated at next step</td>
							</tr>
						<? } ?>
						<tr><td colspan="4"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td></td>
							<td></td>
							<td align="right" style="font-weight: 500;">Total</td>
							<td align="right"><span class="payment-due__price">$<? echo number_format((str_replace(",","",$orderData['productTotal'])+str_replace(",","",$quoteTotal)),2); ?></span></td>
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
		if (e.value.length==3 || e.value.length==7 )
		//e.value=e.value+" ";	
	}	
}
</script>	