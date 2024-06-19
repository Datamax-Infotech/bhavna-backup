<?
session_start();
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
/*echo "<pre>";
print_r($_SESSION['orderData']);*/

$orderData = $_SESSION['orderData'];

/*echo "<pre>";
print_r($orderData);
echo "</pre>";*/
/* update respective order shipping addres*/
$qryUpdt = "UPDATE b2becommerce_order_item SET shipping_firstname = '".$orderData['shippingaddFNm']."', shipping_lastname = '".$orderData['shippingaddLNm']."', shipping_company = '".$orderData['shippingaddCompny']."', shipping_add1 = '".$orderData['shippingAdd1']."', shipping_add2 = '".$orderData['shippingAdd2']."', shipping_city = '".$orderData['shippingaddCity']."', shipping_state = '".$orderData['shippingaddState']."', shipping_zip = '".$orderData['shippingaddZip']."', shipping_email = '".$orderData['shippingaddEmail']."', shipping_phone = '".$orderData['shippingaddPhone']."', shipping_dockhrs = '".$orderData['shippingaddDockhrs']."', quote_name = '".$orderData['quoteName']."', quote_qty = '".$orderData['quoteQty']."', quote_unit_price = '".$orderData['quoteUnitPr']."', quote_total = '".$orderData['quoteTotal']."', quote_rate = '".$orderData['quoteRate']."' WHERE id = ".$orderData['lastInsertId'];
$resUpdt = db_query($qryUpdt, db());

/* update respective order Quote details
$qryUpdt = "UPDATE b2becommerce_order_item SET  WHERE id = ".$orderData['lastInsertId'];
$resUpdt = db_query($qryUpdt, db());*/


?>
<!doctype html>
<html  dir="ltr" class="js windows firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity placeholder no-touchevents displaytable display-table generatedcontent cssanimations flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">
<head>
<meta charset="utf-8">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Payment - Buy Gaylord Totes - Usedcardboardboxes</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<link rel="stylesheet" type="text/css" href="CSS/payment.css">
	 <!--<link rel="stylesheet" href="CSS/radio-pure-css.css">-->
	
	<link rel="stylesheet" href="product-slider/slick.css">
	<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
	<link rel="stylesheet" href="product-slider/prod-style.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
function chkPaymntPgLd(){
	var shippingAdd1 	= "<? if(!empty($orderData['shippingAdd1'])){ echo $orderData['shippingAdd1']; } ?>";
	var shippingAdd2	= "<? if(!empty($orderData['shippingAdd2'])){  echo $orderData['shippingAdd2']; }?>";
	var shippingaddCity	= "<? if(!empty($orderData['shippingaddCity'])){ echo $orderData['shippingaddCity'];}?>";
	var shippingaddState = "<? if(!empty($orderData['shippingaddState'])){ echo $orderData['shippingaddState']; } ?>";
	var shippingaddZip	= "<? if(!empty($orderData['shippingaddZip'])){ echo $orderData['shippingaddZip']; } ?>";
	var shippingaddEmail = "<? if(!empty($orderData['shippingaddEmail'])){ echo $orderData['shippingaddEmail']; } ?>";
	var shippingaddPhone = "<? if(!empty($orderData['shippingaddPhone'])){ echo $orderData['shippingaddPhone']; } ?>";
	var rdoVal = $("input[name='rdoBillingAdd']:checked").val();
	//alert('rdoVal -> '+rdoVal);
	if(rdoVal == 'same'){
		$("#billingAddSection").addClass('display_none');
		if(shippingAdd1 != ''){
			$('#billingAdd1').val(shippingAdd1);
			$('#billingAdd1').attr('readonly', true);
		}
		if(shippingAdd2 != ''){
			$('#billingAdd2').val(shippingAdd2);
			$('#billingAdd2').attr('readonly', true);
		}
		if(shippingaddCity != ''){
			$('#billingAddCity').val(shippingaddCity);
			$('#billingAddCity').attr('readonly', true);
		}
		if(shippingaddState != ''){
			$('#billingAddState').val(shippingaddState);
			$('#billingAddState').attr('readonly', true);
		}
		if(shippingaddZip != ''){
			$('#billingAddZip').val(shippingaddZip);
			$('#billingAddZip').attr('readonly', true);
		}
		if(shippingaddEmail != ''){
			$('#billingAddEmail').val(shippingaddEmail);
			$('#billingAddEmail').attr('readonly', true);
		}
		if(shippingaddPhone != ''){
			$('#billingAddPhn').val(shippingaddPhone);
			$('#billingAddPhn').attr('readonly', true);
		}
	}
	
}
function rdoStatus(){
	var shippingAdd1 	= "<? if(!empty($orderData['shippingAdd1'])){ echo $orderData['shippingAdd1']; } ?>";
	var shippingAdd2	= "<? if(!empty($orderData['shippingAdd2'])){  echo $orderData['shippingAdd2']; }?>";
	var shippingaddCity	= "<? if(!empty($orderData['shippingaddCity'])){ echo $orderData['shippingaddCity'];}?>";
	var shippingaddState = "<? if(!empty($orderData['shippingaddState'])){ echo $orderData['shippingaddState']; } ?>";
	var shippingaddZip	= "<? if(!empty($orderData['shippingaddZip'])){ echo $orderData['shippingaddZip']; } ?>";
	var shippingaddEmail = "<? if(!empty($orderData['shippingaddEmail'])){ echo $orderData['shippingaddEmail']; } ?>";
	var shippingaddPhone = "<? if(!empty($orderData['shippingaddPhone'])){ echo $orderData['shippingaddPhone']; } ?>";
	var rdoVal = $("input[name='rdoBillingAdd']:checked").val();
	//alert('rdoVal -> '+rdoVal);
	if(rdoVal == 'same'){
		$("#billingAddSection").addClass('display_none');
		if(shippingAdd1 != ''){
			$('#billingAdd1').val(shippingAdd1);
			$('#billingAdd1').attr('readonly', true);
		}
		if(shippingAdd2 != ''){
			$('#billingAdd2').val(shippingAdd2);
			$('#billingAdd2').attr('readonly', true);
		}
		if(shippingaddCity != ''){
			$('#billingAddCity').val(shippingaddCity);
			$('#billingAddCity').attr('readonly', true);
		}
		if(shippingaddState != ''){
			$('#billingAddState').val(shippingaddState);
			$('#billingAddState').attr('readonly', true);
		}
		if(shippingaddZip != ''){
			$('#billingAddZip').val(shippingaddZip);
			$('#billingAddZip').attr('readonly', true);
		}
		if(shippingaddEmail != ''){
			$('#billingAddEmail').val(shippingaddEmail);
			$('#billingAddEmail').attr('readonly', true);
		}
		if(shippingaddPhone != ''){
			$('#billingAddPhn').val(shippingaddPhone);
			$('#billingAddPhn').attr('readonly', true);
		}
	}else{
		$("#billingAddSection").removeClass('display_none');
		$('#billingAdd1').attr('readonly', false);
		$('#billingAdd2').attr('readonly', false);
		$('#billingAddCity').attr('readonly', false);
		$('#billingAddState').attr('readonly', false);
		$('#billingAddZip').attr('readonly', false);
		$('#billingAddEmail').attr('readonly', false);
		$('#billingAddPhn').attr('readonly', false);
		$('#billingAdd1').val('');
		$('#billingAdd2').val('');
		$('#billingAddCity').val('');
		$('#billingAddState').val('');
		$('#billingAddZip').val('');
		$('#billingAddEmail').val('');
		$('#billingAddPhn').val('');
		$('#billingAdd1').attr('placeholder', 'Address Line1');
		$('#billingAdd2').attr('placeholder', 'Address Line2');
		$('#billingAddCity').attr('placeholder', 'City');
		$('#billingAddState').attr('placeholder', 'State');
		$('#billingAddZip').attr('placeholder', 'ZIP Code');
		$('#billingAddEmail').attr('placeholder', 'Email (for any billing issues)');
		$('#billingAddPhn').attr('placeholder', 'Phone (for any billing issues)');
	}

}
</script>
<style type="text/css">
	.display_none{
		display: none;
	}
</style>

</head>

<body onload="chkPaymntPgLd();" >
	<div class="main_container">
		<div class="sub_container">
			<div class="header">
				<div class="logo_img"><a href="https://www.usedcardboardboxes.com/"><img src="images/ucb_logo.jpg" alt="moving boxes"></a></div>
				<div class="contact_number">
					<span class="login-username"><div class="needhelp">Need help? </div><div class="needhelp_call">
						<img src="images/callicon.png" alt="" class="call_img"><strong>1-888-BOXES-88 (1-888-269-3788)</strong></div></span>
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
				<div class="title_desc">It's as easy as Select Your Quantity and Buy!</div>
			</div>
			<!--Start Breadcrums-->
			<nav aria-label="Breadcrumb">
				<ol class="breadcrumb " role="list">
				<li class="breadcrumb__item breadcrumb__item--completed">
				  <a class="breadcrumb__link" href="index.php">Select Quantity</a>
					
				  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>

				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="contact.php">Contact</a>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
				</li>
				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="shipping.php">Shipping</a>
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
					<form class="edit_checkout animate-floating-labels payfrm" data-payment-form="" action="/34391261315/checkouts/f44162a62f89b8d6b8dcf08c6a68fbb6" accept-charset="UTF-8" method="post">
						<div class="frmsection">
							<div class="frm-txt">
								<div class="frm-txt-shipping">Billing address</div><br>
								<div class="frm-subtext">Select the address that matches your credit card.</div>
							</div>
							<div class="div-space"></div>
							<fieldset class="content-box">
								<div class="radio-wrapper content-box__row" data-same-billing-address="">
									<div class="radio__input">
										<input class="input-radio" data-backup="different_billing_address_false" type="radio" value="same" checked="checked" name="rdoBillingAdd" id="rdoSameBillingAdd" onchange="rdoStatus()" >
									</div>

									<label class="radio__label content-box__emphasis shipping_billing_add" for="checkout_different_billing_address_false">
										Same as shipping address
									</label>            
								</div>
								<div class="radio-wrapper content-box__row" data-different-billing-address="">
								  <div class="radio__input">
								  	<input class="input-radio" data-backup="different_billing_address_true" aria-controls="section--billing-address__different" type="radio" value="different" name="rdoBillingAdd" id="rdoDiffBillingAdd" onchange="rdoStatus()" >
								  </div>
								  	<label class="radio__label content-box__emphasis shipping_billing_add" for="checkout_different_billing_address_true">
									Use a different billing address
									</label> 
								</div>
							</fieldset>
							<div class="div-space"></div>
							<fieldset class="content-box" id="billingAddSection">
								<div id="payment-gateway-subfields-44168151171" data-subfields-for-gateway="44168151171" class="radio-wrapper content-box__row content-box__row--secondary card-fields-container card-fields-container--loaded card-fields-container--transitioned">
									<div class="fieldset" data-credit-card-fields="" data-slate="" dir="ltr">
										<div class="fieldset">											
											<div data-address-field="add1" data-autocomplete-field-container="true" class="field field--optional">

											  	<div class="field__input-wrapper">
											  		<label class="field__label field__label--visible" for="checkout_shipping_address_add1">Address Line1</label>
													<input placeholder="Address Line1" autocomplete="shipping organization" autocorrect="off" data-backup="add1" class="field__input" size="30" type="text" name="billingAdd1" id="billingAdd1" value="">
											  </div>
											</div>
											<div data-address-field="add2" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_add2">Address Line2</label>
													<input placeholder="Address Line2" autocomplete="shipping organization" autocorrect="off" data-backup="add2" class="field__input" size="30" type="text" name="billingAdd2" id="billingAdd2">
										  		</div>
											</div>
											<div data-address-field="city" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_city">City</label>
													<input placeholder="City" autocomplete="shipping organization" autocorrect="off" data-backup="city" class="field__input" size="30" type="text" name="billingAddCity" id="billingAddCity">
										  		</div>
											</div>
											<div class="field field--required field--half" data-address-field="state">
												<div class="field__input-wrapper">
													<label class="field__label field__label--visible" for="checkout_shipping_address_state">State</label>
										  			<input placeholder="State" autocomplete="shipping given-name" autocorrect="off" data-backup="state" class="field__input" aria-required="true" size="30" type="text" name="billingAddState" id="billingAddState">
												</div>
											</div>
											<div class="field field--required field--half" data-address-field="zip_code">
												<div class="field__input-wrapper">
													<label class="field__label field__label--visible" for="checkout_shipping_address_zip_code">ZIP Code</label>
										  			<input placeholder="ZIP Code" autocomplete="shipping given-name" autocorrect="off" data-backup="zip_code" class="field__input" aria-required="true" size="30" type="text" name="billingAddZip" id="billingAddZip">
												</div>
											</div>
											<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (for any billing issues)</label>
													<input placeholder="Email (for any billing issues)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="billingAddEmail" id="billingAddEmail">
										  		</div>
											</div>
											<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (for any billing issues)</label>
													<input placeholder="Phone (for any billing issues)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="billingAddPhn" id="billingAddPhn">
										  		</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="div-space"></div>
							<div class="div-space"></div>
							<div class="section__content">
								<fieldset class="content-box">
									<div class="radio-wrapper content-box__row " data-gateway-group="direct" data-gateway-name="credit_card" data-select-gateway="44168151171" data-submit-i18n-key="pay_now">
									  	<div class="radio__input">
										  	<input class="input-radio" id="checkout_payment_gateway_44168151171" data-backup="payment_gateway_44168151171" aria-describedby="payment_gateway_44168151171_description" aria-controls="payment-gateway-subfields-44168151171" type="radio" value="44168151171" checked="checked" name="checkout[payment_gateway]">
									  	</div>
										<div class="radio__label payment-method-wrapper ">
											<label for="checkout_payment_gateway_44168151171" class="radio__label__primary content-box__emphasis">
												  Credit card
											</label>
										</div>
									</div>
									<div id="payment-gateway-subfields-44168151171" data-subfields-for-gateway="44168151171" class="radio-wrapper content-box__row content-box__row--secondary card-fields-container card-fields-container--loaded card-fields-container--transitioned">
										<div class="fieldset" data-credit-card-fields="" data-slate="" dir="ltr">
      										
											<div class="fieldset">
												<div data-address-field="cardnum" data-autocomplete-field-container="true" class="field field--optional">

													  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_cardnumber">Card Number</label>
														<input placeholder="Card Number" autocomplete="" autocorrect="off" data-backup="cardnum" class="field__input" size="30" type="text" name="txtPaymentCCNumber" id="txtPaymentCCNumber" value="<? echo $cc_number; ?>">
													  </div>
												</div>
												<div data-address-field="cardname" data-autocomplete-field-container="true" class="field field--optional">

													  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_cardname">Card Name</label>
														<input placeholder="Name on Card" autocomplete="" autocorrect="off" data-backup="cardname" class="field__input" size="30" type="text" name="txtPaymentCCName" id="txtPaymentCCName" value="<? echo $cc_owner; ?>">
													  </div>
												</div>
												<div class="field field--required field--half" data-address-field="exp_date">
													<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_exp_date">Expiration date (MM / YY)</label>
													  <input placeholder="Expiration date (MM / YY)" autocomplete="" autocorrect="off" data-backup="exp_date" class="field__input" aria-required="true" size="30" type="text" name="txtPaymentCCExpiryDt" id="txtPaymentCCExpiryDt">
													</div>
												</div>
												<div class="field field--required field--half" data-address-field="security_code">
													<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_security_code">Security code</label>
													  <input placeholder="Security code" autocomplete="shipping given-name" autocorrect="off" data-backup="zip_code" class="field__input" aria-required="true" size="30" type="text" name="txtPaymentCCSecurityCode" id="txtPaymentCCSecurityCode">
													</div>
												</div>
											</div>
									  </div>
									</div>
								</fieldset>
							</div>
							<div class="btn-div-shipping content-bottom-padding">
								<button type="button" class="button_slide slide_right" data-testid="order-button">PAY NOW</button>
							</div>
							<div class="div-space"></div>
						</div>
					</form>	
				</div><!--End div left-form-->
			</div>
			<!---->
			<div class="privacy-links_inner">
				<div class="bottomlinks">
					<div class="bottom-link">Refund Policy</div>    
					<div class="bottom-link">Shipping Policy</div> 
					<div class="bottom-link">Privacy Policy</div>   
					<div class="bottom-link">Terms of Service</div>
				</div>
			</div>
		</div><!--End inner div-->
			<div class="innerdiv_2">
				<div class="collapsible"><div class="show-order" id="showorder">Show order summary
					<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
					
					
					</div>
				<div class="show-order-total">
					<?
					if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
						$total = $orderData['productTotal'] + $orderData['quoteTotal'];
					}else{
						$total = "0.00";
					}
					echo "$".$total;
					?>
				</div>
				</div>
				
				<div class="inner-content-shipping" id="order-content">
					<? require('item_sections.php'); ?>
				
				<div class="sidebar-sept"></div>
					<div class="table_mob_div">
					<table class="sidebar-table">
						<tr>
							<th align="left">Amount</th>
							<th align="center">Quantity</th>
							<th align="right">Price/Unit</th>
							<th align="right">Total</th>
							<th></th>
						</tr>
						
						<tr>
							<td><? echo $orderData['productName'] ; ?></td>
							<td align="center"><? echo $orderData['productQnt'] ; ?></td>
							<td align="right">$<? echo $orderData['productUnitPr'] ; ?></td>
							<td align="right">$<? echo $orderData['productTotal'] ; ?></td>
							<td align="right"></td>
						</tr>
						<tr>
							<td><? echo $orderData['quoteName'] ; ?></td>
							<td align="center"><? echo $orderData['quoteQty'] ; ?></td>
							<td align="right">$<? echo $orderData['quoteUnitPr'] ; ?></td>
							<td align="right">$<? echo $orderData['quoteTotal'] ; ?></td>
							<td align="right"></td>
						</tr>
						<tr><td colspan="5"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td style="font-weight: 500;"  align="right">Total</td>
							<td></td>
							<td align="right" colspan="2">USD&nbsp;&nbsp;
								<span class="payment-due__price">
									<?
									if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
										$total = $orderData['productTotal'] + $orderData['quoteTotal'];
									}else{
										$total = "0.00";
									}
									echo "$".$total;
									?>
								</span>
							</td>
							<td class="caltxt">($13.06/unit shipping incl.)</td>
						</tr>
					</table>
					</div>
					
					<div style="padding-top: 60px;">
						<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">
										<? if(!empty($orderData['cntInfoFNm']) || !empty($orderData['cntInfoLNm']) ){ 
											echo $orderData['cntInfoFNm'] ." ".$orderData['cntInfoLNm']; 
										} ?>, 
										<? if(!empty($orderData['cntInfoCompny'])){ 
											echo ", ".$orderData['cntInfoCompny']; 
										} ?>.
									</span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1">
										<? if(!empty($orderData['shippingAdd1']) ){ 
											echo $orderData['shippingAdd1']; 
										} ?><? if( !empty($orderData['shippingAdd2']) ){ 
											echo ", ".$orderData['shippingAdd2']; 
										} ?><? if( !empty($orderData['shippingaddCity']) ){ 
											echo ", ".$orderData['shippingaddCity']; 
										} ?><? if( !empty($orderData['shippingaddState']) ){ 
											echo ", ".$orderData['shippingaddState']; 
										} ?><? if( !empty($orderData['shippingaddZip']) ){ 
											echo ", ".$orderData['shippingaddZip']; 
										} ?>
										<br />
										<span id="Span1" class="caltxt">1,234 mi from item origin</span>
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
	
	<div class="footer_l">
		
		<div class="copytxt">© UsedCardboardBoxes</div>

	</div>
</body>
</html>
