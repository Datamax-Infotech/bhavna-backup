<?php 
if(session_id() == ''){
    session_start();
}
require ("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");

require ("../cal_functions.php");


db();

$orderData = $_SESSION['orderData'];

if($_REQUEST['rdoBillingAdd'] == 'same'){
	$billingAdd['billingAdd1'] 		= $orderData['shippingAdd1'];
	$billingAdd['billingAdd2']	 	= $orderData['shippingAdd2'];
	$billingAdd['billingAddCity'] 	= $orderData['shippingaddCity'];
	$billingAdd['billingAddState'] 	= $orderData['shippingaddState'];
	$billingAdd['billingAddZip']	= $orderData['shippingaddZip'];
	$billingAdd['billingAddEmail'] 	= $orderData['shippingaddEmail'];
	$billingAdd['billingAddPhn']	= $orderData['shippingaddPhone'];

}else{
	$billingAdd['billingAdd1']		= $_REQUEST['billingAdd1'];
	$billingAdd['billingAdd2'] 		= $_REQUEST['billingAdd2'];
	$billingAdd['billingAddCity']	= $_REQUEST['billingAddCity'];
	$billingAdd['billingAddState'] 	= $_REQUEST['billingAddState'];
	$billingAdd['billingAddZip']	= $_REQUEST['billingAddZip'];
	$billingAdd['billingAddEmail'] 	= $_REQUEST['billingAddEmail'];
	$billingAdd['billingAddPhn']	= $_REQUEST['billingAddPhn'];
}

$orderAllData = array_merge($_SESSION['orderData'], $billingAdd );
$_SESSION['orderData'] 	= $orderAllData;
$orderData = $_SESSION['orderData'];


$sessionId = session_id();
$ProductLoopId = $orderData['ProductLoopId'];

$qryUpdt = "UPDATE b2becommerce_order_item SET shipping_firstname = '".$orderData['shippingaddFNm']."', shipping_lastname = '".$orderData['shippingaddLNm']."', shipping_company = '".$orderData['shippingaddCompny']."', shipping_add1 = '".$orderData['shippingAdd1']."', shipping_add2 = '".$orderData['shippingAdd2']."', shipping_city = '".$orderData['shippingaddCity']."', shipping_state = '".$orderData['shippingaddState']."', shipping_zip = '".$orderData['shippingaddZip']."', shipping_email = '".$orderData['shippingaddEmail']."', shipping_phone = '".$orderData['shippingaddPhone']."', shipping_dockhrs = '".$orderData['shippingaddDockhrs']."', quote_name = '".$orderData['quoteName']."', quote_qty = '".$orderData['quoteQty']."', quote_unit_price = '".$orderData['quoteUnitPr']."', quote_total = '".$orderData['quoteTotal']."', quote_rate = '".$orderData['quoteRate']."', billing_add1 = '".$orderData['billingAdd1']."', billing_add2 = '".$orderData['billingAdd2']."', billing_city = '".$orderData['billingAddCity']."', billing_state = '".$orderData['billingAddState']."', billing_zip = '".$orderData['billingAddZip']."', billing_email = '".$orderData['billingAddEmail']."', billing_phone = '".$orderData['billingAddPhn']."' WHERE session_id = '".$sessionId."'";
$resUpdt = db_query($qryUpdt, db());

if($orderData['user_master_id'] > 0 ){
	$getUserDt = db_query("SELECT companyid FROM b2becommerce_user_master WHERE userid = ".$orderData['user_master_id'], db());
	$rowUserDt = array_shift($getUserDt);
}
if(!empty($rowUserDt['companyid'])){
	$getLoopId = db_query("SELECT loopid FROM companyInfo WHERE ID = ".$rowUserDt['companyid'], db_b2b());
	$rowLoopId = array_shift($getLoopId);
	$warehouse_id = $rowLoopId['loopid'];
}

$productTotal = 0; $quoteTotal = 0;
if ($sessionId){
	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."'", db() );
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$ProductLoopId = $rowContactInfo['product_loopboxid'];
		
		$productTotal = str_replace(",", "", $rowContactInfo['product_total']);
		$quoteTotal = str_replace(",", "", $rowContactInfo['quote_total']);
		$total_amount = round($productTotal) + round($quoteTotal);

		$orderData['productTotal'] = $productTotal;
		
		$orderData['productName'] = $rowContactInfo['product_name'];
		$orderData['product_name_id'] = $rowContactInfo['product_name_id'];
		$orderData['productQntypeid'] = $rowContactInfo['product_name_id'];
		$orderData['productQnt'] = $rowContactInfo['product_qty'];
		$orderData['productUnitPr'] = $rowContactInfo['product_unitprice'];
		
		$orderData['quoteName'] = $rowContactInfo['quote_name'];
		$orderData['quoteQty'] = $rowContactInfo['quote_qty'];
		$orderData['quoteUnitPr'] = $rowContactInfo['quote_unit_price'];
		$orderData['quoteTotal'] = $quoteTotal;
		
		$orderData['shippingaddZip'] = $rowContactInfo['shipping_zip'];
	}
}

$_REQUEST["selected_id"] = $rowUserDt['companyid'];   	//'81689';

$distance = find_distance($ProductLoopId, $orderData['shippingaddZip']);

?>
<!DOCTYPE html>
<html  dir="ltr" class="js windows firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity placeholder no-touchevents displaytable display-table generatedcontent cssanimations flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">
<head>
	
	<script src="scripts/jquery-2.1.4.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/jquery.cookie.js"></script>
	
	<script src="https://sandbox-assets.secure.checkout.visa.com/checkout-widget/resources/js/integration/v1/sdk.js"></script>
	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://jstest.authorize.net/v1/Accept.js"></script>
	<script src="https://jstest.authorize.net/v3/acceptUI.js"></script>
		
	<link rel="stylesheet" type="text/css" href="../stylesheet.css">

	<!-- Payment UI css/js start -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../CSS/style.css">
	<link rel="stylesheet" type="text/css" href="../CSS/payment.css">
	
	<link rel="stylesheet" href="../product-slider/slick.css">
	<link rel="stylesheet" href="../product-slider/jquery.fancybox.min.css">
	<link rel="stylesheet" href="../product-slider/prod-style.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Payment UI css/js end -->

<title>Payment - Buy Gaylord Totes - Usedcardboardboxes</title>
<script type="text/javascript">
	var baseUrl = "https://www.authorize.net/customer/";
	var onLoad = true;
	tab = null;

	function returnLoaded() {  										
		document.return_page.submit();
	}
	window.CommunicationHandler = {};
	function parseQueryString(str) {
		var vars = [];
		var arr = str.split('&');
		var pair;
		for (var i = 0; i < arr.length; i++) {
			pair = arr[i].split('=');
			vars[pair[0]] = unescape(pair[1]);
		}		
		return vars;
	}
	CommunicationHandler.onReceiveCommunication = function (argument) {  
		params = parseQueryString(argument.qstr)
		parentFrame = argument.parent.split('/')[4];
		console.log(params);
		console.log(parentFrame);

		$frame = null;
		switch(parentFrame){
			case "manage" 		: $frame = $("#load_profile");break;
			case "addPayment" 	: $frame = $("#add_payment");break;
			case "addShipping" 	: $frame = $("#add_shipping");break;
			case "editPayment" 	: $frame = $("#edit_payment");break;
			case "editShipping"	: $frame = $("#edit_shipping");break;
			case "payment"		: $frame = $("#load_payment");break;
		}

		switch(params['action']){
			case "resizeWindow" 	: 	if( parentFrame== "manage" && parseInt(params['height'])<1150) params['height']=1150;
										if( parentFrame== "payment" && parseInt(params['height'])<1000) params['height']=1000;
										if(parentFrame=="addShipping" && $(window).width() > 1021) params['height']= 350;
										$frame.outerHeight(parseInt(params['height']));
										break;

			case "successfulSave" 	: 	$('#myModal').modal('hide'); location.reload(false); break;

			case "cancel" 			: 	
										var currTime = sessionStorage.getItem("lastTokenTime");
										if (currTime === null || (Date.now()-currTime)/60000 > 15){
											location.reload(true);
											onLoad = true;
										}
										switch(parentFrame){
										case "addPayment"   : $("#send_token").attr({"action":baseUrl+"addPayment","target":"add_payment"}).submit(); $("#add_payment").hide(); break; 
										case "addShipping"  : $("#send_token").attr({"action":baseUrl+"addShipping","target":"add_shipping"}).submit(); $("#add_shipping").hide(); $('#myModal').modal('toggle'); break;
										case "manage"       : $("#send_token").attr({"action":baseUrl+"manage","target":"load_profile" }).submit(); break;
										case "editPayment"  : $("#payment").show(); $("#addPayDiv").show(); break; 
										case "editShipping" : $('#myModal').modal('toggle'); $("#shipping").show(); $("#addShipDiv").show(); break;
										case "payment"		: sessionStorage.removeItem("HPTokenTime"); $('#HostedPayment').attr('src','about:blank'); break; 
										}
						 				break;

			case "transactResponse"	: 	sessionStorage.removeItem("HPTokenTime");
										$('#HostedPayment').attr('src','about:blank');
										var transResponse = JSON.parse(params['response']);
										
										document.getElementById("response_accountType").value = transResponse.accountType;
										document.getElementById("response_accountNumber").value = transResponse.accountNumber;
										document.getElementById("response_transId").value = transResponse.transId;
										document.getElementById("response_responseCode").value = transResponse.responseCode;
										document.getElementById("response_authorization").value = transResponse.authorization;
										document.post_data.submit();
										
										//$("#HPConfirmation p").html("<strong><b> Success.. !! </b></strong> <br><br> Your payment of <b>$"+transResponse.totalAmount+"</b> for <b>"+transResponse.orderDescription+"</b> has been Processed Successfully on <b>"+transResponse.dateTime+"</b>.<br><br>Generated Order Invoice Number is :  <b>"+transResponse.orderInvoiceNumber+"</b><br><br> Happy Shopping with us ..");
										//$("#HPConfirmation p b").css({"font-size":"22px", "color":"green"});
										//$("#HPConfirmation").modal("toggle");
		}
	}

	function showTab(target){ 											
		//onLoad = true;
		var currTime = sessionStorage.getItem("lastTokenTime");         
		if (currTime === null || (Date.now()-currTime)/60000 > 15){
			location.reload(true);
			onLoad = true;
		}
		if (onLoad) {
			setTimeout(function(){ 
				$("#send_token").attr({"action":baseUrl+"manage","target":"load_profile" }).submit();
				$("#send_token").attr({"action":baseUrl+"addPayment","target":"add_payment"}).submit();
				$("#send_token").attr({"action":baseUrl+"addShipping","target":"add_shipping"}).submit();

				var currHPTime = sessionStorage.getItem("HPTokenTime"); 	
				if (currHPTime === null || (Date.now()-currHPTime)/60000 > 5){
					sessionStorage.setItem("HPTokenTime",Date.now());
					$("#getHPToken").load("getHostedPaymentForm.php");
					$("#HostedPayment").css({"height": "200px","background":"url(images/loader.gif) center center no-repeat"});
					$("#send_hptoken").submit();
				}
				sessionStorage.removeItem("HPTokenTime");
			} ,100);
			onLoad = false;
		}
		
	}

    function refreshAcceptHosted()
    {   																		
    			var currHPTime = sessionStorage.getItem("HPTokenTime");
				if (currHPTime === null || (Date.now()-currHPTime)/60000 > 5){
					sessionStorage.setItem("HPTokenTime",Date.now());
					$("#getHPToken").load("getHostedPaymentForm.php");
					$("#HostedPayment").css({"height": "200px","background":"url(images/loader.gif) center center no-repeat"});
					$("#send_hptoken").submit();
				}
				sessionStorage.removeItem("HPTokenTime");
    }

	$(function(){   														

		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			tab = $(e.target).attr("href") // activated tab
			sessionStorage.setItem("tab",tab);
			showTab(tab);
		});
		onLoad = true;
		sessionStorage.setItem("lastTokenTime",Date.now());
		tab = sessionStorage.getItem("tab");
		if (tab === null) {
			$("[href='#home']").parent().addClass("active");
			tab = "#home";
		}
		else{
			$("[href='"+tab+"']").parent().addClass("active");
		}
		console.log("Tab : "+tab);
		showTab(tab);

		$("#addPaymentButton").click(function() {
			$("#edit_payment").hide();
			$("#add_payment").show();
			$(window).scrollTop($('#add_payment').offset().top-50);
		});

		$("#addShippingButton").click(function() {				
			$("#myModalLabel").text("Add Details");
			$("#edit_shipping").hide();
			$("#add_shipping").show();
			$(window).scrollTop($("#add_shipping").offset().top-30);
		});


		vph = $(window).height();  						
		$("#home").css("margin-top",(vph/4)+'px');

		$(window).resize(function(){
			$('#home').css({'margin-top':(($(window).height())/4)+'px'});
		});

		$(window).keydown(function(event) {
		  if(event.ctrlKey && event.keyCode == 69) { 
		  	event.preventDefault(); 
		    logOut();
		  }
		});

	});

	function logOut() {
		console.log("Log Out event Triggered ..!");
	    $.removeCookie('cpid', { path: '/' });
	    $.removeCookie('temp_cpid', { path: '/' });
	    window.location.href = 'login.php';
	}
 
 	function payment_type_chg() {
		if (document.getElementById("payment_type").value == "voidTransaction" || document.getElementById("payment_type").value == "refundTransaction")
		{
			document.getElementById("divvoidref").style.display = "inline";
		}else{
			document.getElementById("divvoidref").style.display = "none";
		}		

		if (document.getElementById("payment_type").value == "priorAuthCaptureTransaction")
		{
			document.getElementById("divcaptureonly").style.display = "inline";
		}else{
			document.getElementById("divcaptureonly").style.display = "none";
		}		
	}

	$(document).ready(function(){
		document.getElementById("div_credit_term").style.display = "none";

		$("#payBtn").click(function(){
			return false;
			$.ajax({
				url: "demo_test.txt", 
				success: function(result){
					$("#div1").html(result);
				}
			});
		});
	});

	function rdoStatus(){
		var rdoVal = $("input[name='rdoCc_or_credit_term']:checked").val();
		if(rdoVal == 'same'){
			//$("#CredittermSection").addClass('display_none');
			//$("#CreditCardSection").removeClass('display_none');
			document.getElementById("div_credit_term").style.display = "none";
			document.getElementById("div_credit_card").style.display = "inline";
			
			addRow();
		}else{
			//$("#CredittermSection").removeClass('display_none');
			//$("#CreditCardSection").addClass('display_none');
			document.getElementById("div_credit_term").style.display = "inline";
			document.getElementById("div_credit_card").style.display = "none";
			removeRow();
		}
	}

    function addRow() {  
		var hide= false;	
        var table = document.getElementById('paymenttbl');  
		var rowStyle = (hide) ? "none":"";
		table.rows[3].style.display = rowStyle;
    }  
      
    function removeRow() {  
        var table = document.getElementById('paymenttbl');  
		var hide= true;
		var rowStyle = (hide) ? "none":"";
		table.rows[3].style.display = rowStyle;
    } 

</script>
<style type="text/css">
.display_none{
	display: none;
}
.payButton{
	color: #FFF;
    border: 2px solid #5cb726;
    padding: 12px 24px;
    display: inline-block;
    font-size: 14px;
    letter-spacing: 1px;
    cursor: pointer;
    background-color: #5cb726 !important;
    box-shadow: inset 0 0 0 0 #3e7f18;
    -webkit-transition: ease-out 0.4s;
    -moz-transition: ease-out 0.4s;
    transition: ease-out 0.4s;
    border-radius: 5px;
    margin-top: 20px;
}
</style>

</head>

<body >
	<div class="main_container">
		<div class="sub_container">
			<div class="header">
				<div class="logo_img"><a href="https://www.usedcardboardboxes.com/"><img src="../images/ucb_logo.jpg" alt="moving boxes"></a></div>
				<div class="contact_number">
					<span class="login-username"><div class="needhelp">Need help? </div><div class="needhelp_call">
						<img src="../images/callicon.png" alt="" class="call_img"><strong>1-888-BOXES-88 (1-888-269-3788)</strong></div></span>
				</div>
			</div>
		</div>
	</div>

	<div class="sections new-section-margin">
		<div class="new_container no-top-padding">
			<div class="parentdiv">
			<div class="innerdiv">
			<div class="section-top-margin_1">
				<h1 class="section-title">Buy Gaylord Totes</h1>
				<div class="title_desc">How will you be paying?</div>
			</div>
			<!--Start Breadcrums-->
			<nav aria-label="Breadcrumb">
				<ol class="breadcrumb " role="list">
				<li class="breadcrumb__item breadcrumb__item--completed">
				  <a class="breadcrumb__link" href="../index.php?id=<?php echo $ProductLoopId;?>">Select Quantity</a>
					
				  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>

				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="../contact.php?id=<?php echo $ProductLoopId;?>">Contact</a>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
				</li>
				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="../shipping.php?id=<?php echo $ProductLoopId;?>">Shipping</a>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>
				  <li class="breadcrumb__item breadcrumb__item--current" aria-current="step">
				  <span class="breadcrumb__text">Payment</span>
				</li>
				</ol>
		  	</nav>
			<!--End Breadcrums-->
			<div class="content-div content-padding ">
				<div class="left_form">
					<div class="frm-txt"><div class="frm-txt-shipping">Payment</div><br>
						<div class="frm-subtext">All transactions are secure and encrypted. </div>
					</div>
					<div class="div-space"></div>
					<!-- <form name="frmpayform" id="frmpayform" action=""> -->
						<div class="frmsection">
							<div class="section__content">
								<fieldset class="content-box" >
									<div class="radio-wrapper content-box__row" data-same-billing-address="">
										<div class="radio__input">
											<input class="input-radio" data-backup="credit_term_false" type="radio" value="same" checked="checked" name="rdoCc_or_credit_term" id="rdoCreditCard" onchange="rdoStatus()" >
											<label for="rdoCreditCard"><span></span>
										</div>

										<label class="radio__label content-box__emphasis cc_or_term" style="text-align: left;" for="credit_term_false">
											Credit card
										</label>            
									</div>
								
									<!-- <div class="radio-wrapper content-box__row " data-gateway-group="direct" data-gateway-name="credit_card" data-select-gateway="44168151171" data-submit-i18n-key="pay_now">
									  	<div class="radio__input">
										  	<input class="input-radio" id="checkout_payment_gateway_44168151171" data-backup="payment_gateway_44168151171" aria-describedby="payment_gateway_44168151171_description" aria-controls="payment-gateway-subfields-44168151171" type="radio" value="44168151171" checked="checked" name="checkout[payment_gateway]">
									  	</div>
										<div class="radio__label payment-method-wrapper ">
											<label for="checkout_payment_gateway_44168151171" class="radio__label__primary content-box__emphasis">
												  Credit card
											</label>
										</div>
									</div> -->
									
									<div id="div_credit_card">
										<?php 
										$_SESSION["payment_type"] 			= 'authCaptureTransaction';
										$_SESSION["ucb_ordertot"] 			= $total_amount;
										$_SESSION["ucb_tax_val"] 			= 0;
										$_SESSION["ucb_product_details"] 	= "B2B product";
										$_SESSION["customer_email_address"]	= $orderData['cntInfoEmail'];
										$_SESSION["customer_phone"] 		= $orderData['cntInfoPhn'];
										$_SESSION["ucb_bill_comp"] 			= $orderData['cntInfoCompny'];
										$_SESSION["ucb_bill_fn"] 			= $orderData['cntInfoFNm'];
										$_SESSION["ucb_bill_ln"] 			= $orderData['cntInfoLNm'];
										$_SESSION["ucb_bill_add"] 			= $orderData['billingAdd1'];
										$_SESSION["ucb_bill_add2"] 			= $orderData['billingAdd2'];
										$_SESSION["ucb_bill_city"] 			= $orderData['billingAddCity'];
										$_SESSION["ucb_bill_state"] 		= $orderData['billingAddState'];
										$_SESSION["ucb_bill_zip"] 			= $orderData['billingAddZip'];

										$api_login_id 	= '4Rw6UcB57';   		//6Dz9Bf6Fm
										$transactionKey = '8c6KJ9862eJs2jBx'; 	//64W8Z4U3j6xkz98r
										//$url 			= "https://apitest.authorize.net/xml/v1/request.api";
										$url 			= "https://api.authorize.net/xml/v1/request.api";
										
										?>

										<!-- <div style="width: 100%; text-align:center;">
											<img src="images/authorize.net-logo.jpg" alt="Authorize.net" width="150" height="40" border="0">
										</div>	 -->										
										<div class="container-fluid" style="width: 100%; margin: 0; padding:0">
											<div id="getHPToken">
												<?php include 'getHostedPaymentForm.php'; ?>
											</div>
										</div>												
										<div  id="iframe_holder" class="center-block" style="width:90%; max-width: 100%; height:300px;">
											<iframe id="load_payment" name="load_payment" width="80%" height="300px" frameborder="0" scrolling="auto" style="display:block;">Loading ....</iframe>
											<iframe id="add_payment" class="embed-responsive-item panel" name="add_payment" width="100%"  frameborder="0" scrolling="no" hidden="true"></iframe>
											<form id="send_hptoken" action="https://accept.authorize.net/payment/payment" method="post" target="load_payment" >
												<input type="hidden" name="token" value="<?php echo $hostedPaymentResponse->token ?>" />
											</form>
										</div>
										<div class="tab-content panel-group">
											<div class="tab-pane" id="pay" hidden="true"></div>
										</div>
										<div >
											<!-- /*NOTE : NEED TO UPDATE LIVE URL*/ -->
											<form id="post_data" name="post_data" action="https://b2b.usedcardboardboxes.com/authorizenet_frm_action.php" method="post" >
												<input type="hidden" name="response_accountType" id="response_accountType" value="" />
												<input type="hidden" name="response_accountNumber" id="response_accountNumber" value="" />
												<input type="hidden" name="response_transId" id="response_transId" value="" />
												<input type="hidden" name="response_amt" id="response_amt" value="<?php echo $total_amount;?>" />
												<input type="hidden" name="response_responseCode" id="response_responseCode" value="" />
												<input type="hidden" name="response_authorization" id="response_authorization" value="" />
											</form>		
											<!-- /*NOTE : NEED TO UPDATE LIVE URL*/ -->											
											<form id="return_page" name="return_page" action="https://b2b.usedcardboardboxes.com/authorizenet_frm_action.php" method="post" >
											</form>
										</div>
									</div>
									
									<div class="radio-wrapper content-box__row" data-same-billing-address="">
										<div class="radio__input">
											<input class="input-radio" data-backup="credit_term_true" aria-controls="section--billing-address__different"  
											type="radio" value="different" name="rdoCc_or_credit_term" id="rdoCreditterm" onchange="rdoStatus()" >
											<label for="rdoCreditterm"><span></span>
										</div>

										<label class="radio__label content-box__emphasis cc_or_term" style="text-align: left;" for="credit_term_false">
											Use credit terms (or apply)
										</label>            
									</div>
									
									<div id="div_credit_term">
										<br><div style="padding-left:20px;padding-right:10px;">We will follow up with you after your order is placed to confirm payment details, whether that is using credit terms you already have approved, or helping you get setup with credit terms.</div><br>
										
										<form id="post_data" name="post_data" action="https://b2b.usedcardboardboxes.com/authorizenet_frm_action.php" method="post" >
											<input type="hidden" name="response_transId" id="response_transId" value="credit_term" />
											<input type="hidden" name="response_amt" id="response_amt" value="<?php echo $total_amount;?>" />
											<button type="submit" class="button_slide slide_right" data-testid="order-button">Process</button>
										</form>		
										<br>	
									</div>
									
								</fieldset>
							</div>
							<div class="btn-div-shipping content-bottom-padding">

							</div>
							<div class="div-space"></div>
						</div>
					<!-- </form>	 -->
					
				</div><!--End div left-form-->
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
				<div class="show-order-total">
					<?php 
					if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
						$total = str_replace(",", "", $orderData['productTotal']) + str_replace(",", "", $orderData['quoteTotal']);
					}else{
						$total = "0.00";
					}
					echo "$".$total;
					?>
				</div>
				</div>
				
				<div class="inner-content-shipping" id="order-content">
					<?php require('../item_sections.php'); ?>
				
				<div class="sidebar-sept"></div>
					<div class="table_mob_div">
					<table class="sidebar-table" id="paymenttbl">
						<tr>
							<th align="left">Truckload</th>
							<th align="center">Quantity</th>
							<th align="right">Price/Unit</th>
							<th align="right">Total</th>
							<th></th>
						</tr>
						
						<tr>
							<td><?php echo $orderData['productName'] ; ?></td>
							<td align="center"><?php echo $orderData['productQnt'] ; ?></td>
							<td align="right">$<?php echo $orderData['productUnitPr'] ;?></td>
							<td align="right">$<?php echo $orderData['productTotal'] ; ?></td>
							<td align="right"></td>
						</tr>
						<tr>
							<td><?php echo $orderData['quoteName'] ; ?></td>
							<td align="center"><?php echo $orderData['quoteQty'] ; ?></td>
							<td align="right">$<?php echo $orderData['quoteUnitPr'] ; ?></td>
							<td align="right">$<?php echo $orderData['quoteTotal'] ; ?></td>
							<td align="right"></td>
						</tr>
							<?php 
							if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
								$productTotal = str_replace(",", "", $orderData['productTotal']);
								$total = $productTotal + str_replace(",", "", $orderData['quoteTotal']);
							}elseif(!empty($orderData['productTotal']) ){	
								$productTotal = str_replace(",", "", $orderData['productTotal']);
								$total = $productTotal;
							}else{
								$total = "0.00";
							}
							$cc_fees = 0;
							if ($total > 0){
								$cc_fees = $total * 0.03;
							}	
							?>						
						<tr>
							<td>Credit Card charges (3%)</td>
							<td align="center">1</td>
							<td align="right">$<?php echo $cc_fees ; ?></td>
							<td align="right">$<?php echo $cc_fees ; ?></td>
							<td align="right"></td>
						</tr>
						<tr><td colspan="5"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td style="font-weight: 500;"  align="right">Total</td>
							<td></td>
							<td align="right" colspan="2">USD&nbsp;&nbsp;
								<span class="payment-due__price">
									<?php 
									$total = $total + $cc_fees;
									echo "$". number_format($total,2);
									?>
								</span>
							</td>
							<td class="caltxt">&nbsp;</td>
						</tr>
					</table>
					</div>
					
					<div style="padding-top: 60px;">
						<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">
										<?php if(!empty($orderData['cntInfoFNm']) || !empty($orderData['cntInfoLNm']) ){ 
											echo $orderData['cntInfoFNm'] ." ".$orderData['cntInfoLNm']; 
										} ?><?php if(!empty($orderData['cntInfoCompny'])){ 
											echo ", ".$orderData['cntInfoCompny']; 
										} ?>.
									</span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1">
										<?php if(!empty($orderData['shippingAdd1']) ){ 
											echo $orderData['shippingAdd1']; 
										} ?><?php if( !empty($orderData['shippingAdd2']) ){ 
											echo ", ".$orderData['shippingAdd2']; 
										} ?><?php if( !empty($orderData['shippingaddCity']) ){ 
											echo ", ".$orderData['shippingaddCity']; 
										} ?><?php if( !empty($orderData['shippingaddState']) ){ 
											echo ", ".$orderData['shippingaddState']; 
										} ?><?php if( !empty($orderData['shippingaddZip']) ){ 
											echo ", ".$orderData['shippingaddZip']; 
										} ?>
										<br />
										<span id="Span1" class="caltxt"><?php echo $distance;?> mi from item origin</span>
									</span>
								</li>
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
					// show hide paragraph on button click
					$("div#order-content").slideToggle("slow", function(){
						// check paragraph once toggle effect is completed
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
<script>
	$('#submitButton').click(function(e){
		e.preventDefault();
		acceptJSCaller();
	});
	$('#submitPAButton').click(function(e){
		e.preventDefault();
		payerAuthCaller();
	});
	$('#closeAcceptConfirmationHeaderBtn').click(function(e){
		refreshAcceptHosted();
	});
	$('#closeAcceptConfirmationFooterBtn').click(function(e){
		refreshAcceptHosted();
	});
</script>

</html>
