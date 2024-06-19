<?php
/*
Page Name: confirmation.php
Page created By: Amarendra
Page created On: 07-04-2021
Last Modified On: 
Last Modified By: Amarendra
Change History:
Date           By            Description
==================================================================================================================
07-04-21      Amarendra     This file is created to show that the product order process and payment completed.
==================================================================================================================
*/
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Shipping - Buy Gaylord Totes - Usedcardboardboxes</title>
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
</head>

<body>
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
<?
/*-------------------------------------------------------------------------------
reason
-------------------------------------------------------------------------------*/
//$id = $_REQUEST["id"]
//$orderid = $_REQUEST["orderid"];
$id=2566;  //Assumed 
$orderid = 1234;
$qry = "Select * FROM loop_boxes WHERE id =" . $id ;
$res = db_query($qry, db());		
$row = array_shift($res);	
$id2 = $row["b2b_id"];	
$qryb2b = "Select * FROM inventory WHERE id =" . $id2;		
$resb2b = db_query($qryb2b, db_b2b() );		
$rowb2b = array_shift($resb2b);
	
/*-------------------------------------------------------------------------------
Start updateing by Amarendra dated 01-04-2021 
-------------------------------------------------------------------------------*/
?>
	
	<div class="sections new-section-margin">
		<div class="new_container no-top-padding">
			<div class="parentdiv">
			<div class="innerdiv">
			<div class="section-top-margin_1">
				<h1 class="section-title">Buy <?=$rowb2b["box_type"];?></h1>
				<div class="title_desc">It's as easy as Select Your Quantity and Buy!</div>
			</div>
			<div class="content-div content-padding ">
				<div class="left_form">
					<?php
					$content = '<div class="frm-txt"><div class="frm-txt-shipping">Order Complete! <span class="title_sub">';
					$content .= 'Your Order ID is # ' .$orderid . ' </span></div><br></div><div class="div-space"></div>';
					$content .= '<div style="text-align:justify;"><p class=""> UCB\'s National Operations Team';
					$content .= ' will now allocate the specific inventory against this order. The next steps will include';
					$content .= 'preparing your order for shipment and booking the freight for pickup and delivery. To ';
					$content .= 'ensure a smooth transaction, we\'ll be communicating with you every step of the way!</p>';
					$content .= '<p style="margin:10px 0;">Next step is UCB will be confirming availability and pickup '; 
					$content .= 'appointments with the shipper location, and our Operations Team will be reaching out ';
					$content .= 'to you to schedule delivery date and time.</p>';
					$content .= '<p class=""><strong>Logistics Notes:</strong></p>';
					$content .= '<p class="" style="margin:10px 0;">As previously stated and acknowledged, you will need a ';
					$content .= 'loading dock and forklift to unload the delivered trailer. Any costs incurred by UCB ';
					$content .= 'due to not having a forklift or a loading dock will be charged to the same card or ';
					$content .= 'credit terms used for this order.</p>';
					$content .= '<p class="">In the meantime and as always, please feel free to contact ';
					$content .= 'UCB\'s Operations Team (Operations@UsedCardboardBoxes.com), if you have any questions ';
					$content .= 'or concerns.</p><p class="" style="margin:10px 0;">Thank you again for Order<strong> #';
					$content .= $orderid .'</strong> and the opportunity to work with you!	</p> </div>';
					echo $content;
					?>
					
				</div>	
			</div>
			<!---->
				<div class="privacy-links_inner">
					<div class="bottomlinks">
					<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div><div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div><div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div><div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
					</div>
				</div>
			</div><!--End inner div-->
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
			<div class="innerdiv_2">
				<div class="collapsible"><div class="show-order" id="showorder">Show order summary
					<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
					
					
					</div>
				<div class="show-order-total">$6,583.95</div>
				</div>
				<div class="inner-content-shipping" id="order-content">
					<h3 class="item1">Item</h3>
					<p class="sidebartxt">ID 1234, Gaylord Tote, Used, 48.5"x40"x41.25", 5ply, Recatnagular, Removable Lid Top, Full Flap Bottom, No Vents</p>
				
				<div class="sidebar-sept"></div>
				
					<table class="sidebar-table">
						<tr>
							<th align="left">Truckload</th>
							<th align="center">Quantity</th>
							<th align="right">Price/Unit</th>
							<th align="right">Total</th>
							<th></th>
						</tr>
						
						<tr>
							<td>Full Truckload</td>
							<td align="center">504</td>
							<td align="right">$11.08</td>
							<td align="right">$5,584.32</td>
							<td align="right"></td>
						</tr>
						<tr>
							<td>Shipping Quote</td>
							<td align="center">1</td>
							<td align="right">$999.63</td>
							<td align="right">$999.63</td>
							<td align="right"></td>
						</tr>
						<tr><td colspan="5"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td style="font-weight: 500;"  align="right">Total</td>
							<td></td>
							<td align="right" colspan="2">USD&nbsp;&nbsp;<span class="payment-due__price">$6,583.95</span></td>
							<td class="caltxt">($13.06/unit shipping incl.)</td>
						</tr>
					</table>
					
					<div style="padding-top: 60px;">
						<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">Joe Smith, ABC Company Inc.</span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1">1234 Wilshire St, Baltimore, MD 21031</span>
								</li>
								<li>
									<label for="Span2">Payment Info</label>
									<span id="Span2"></span>
								</li>
						</ol>
					</div>
				</div>
			</div>
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
<!-- THis section is same as contact or shipping page -->
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
