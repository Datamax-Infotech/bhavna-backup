<?php
	session_start();
//	include 'generateCardinalJWT.php';
			$_SESSION["cpid_error"]='true';
			setcookie("cpid",'', time() -1, "/");
			setcookie("temp_cpid",'', time() -1, "/");
	
	/*if ($response->messages->resultCode != "Ok") {
			$_SESSION["cpid_error"]='true';
			setcookie("cpid",'', time() -1, "/");
			setcookie("temp_cpid",'', time() -1, "/");
			header('Location: login.php');
			exit();	
    }else{
    	$_SESSION["cpid_error"]='false';
    }*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Check Process | UsedCardboardBoxes.com. Quality Used Moving Boxes for Sale - Cheap. Green. Guaranteed.</title>

	<!-- Bootstrap core CSS -->
	<link href="scripts/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">

		.navbar {min-height: 0px; margin-bottom: 0px; border: 0px;}
		.nav>li {display: inline-block;}
		.navbar-centered .nav > li > a {color: white}
		.navbar-inverse { background-color: #555  } /* #7B7B7B */
		.navbar-centered .nav > li > a:hover{ background-color: white; color: black }
		.navbar-centered .nav .active > a,.navbar-centered .navbar-nav > .active > a:focus { background-color: white; color: black; font-weight:bold; }
		.navbar-centered .navbar-nav { float: none; text-align: center; }
	    .navbar-centered .navbar-nav > li { float: none; }
	    .navbar-centered .nav > li { display: inline; }
	    .navbar-centered .nav > li > a {display: inline-block; }
	    #home { color:ivory; margin-left: 15%; margin-right: 15%;}

		@media (min-width: 768px) {
	    	.navbar-centered .nav > li > a { width:15%; }
	    	#home { font-size: 30px}
	    }

	    @media (min-width:360px ) and (max-width: 768px){
	    	.navbar-centered .nav > li > a {font-size: 12px}
	    	#home { font-size: 20px}
	    }

	    @media (max-width: 360px) {
	    	.navbar-centered .nav > li > a {font-size: 10px}
	    	#home { font-size: 15px}
	    }

	    @media (min-width: 1022px) {
	    	.modal-dialog { width: 850px}
	    	#add_shipping { height: 300px }
	    }

		/* vertically center the Bootstrap modals */
		.modal {
			text-align: center;
			padding: 0!important;
		}

		.modal:before {
			content: '';
			display: inline-block;
			height: 100%;
			vertical-align: middle;
			margin-right: -4px;
		}

		.modal-dialog {
			display: inline-block;
			text-align: left;
			vertical-align: middle;
		}

	.apple-pay-button-with-text {
	    --apple-pay-scale: 1; /* (height / 32) */
	    display: inline-flex;
	    justify-content: center;
	    font-size: 12px;
	    border-radius: 5px;
	    padding: 0px;
	    box-sizing: border-box;
	    min-width: 200px;
	    min-height: 32px;
	    max-height: 64px;
	    cursor: pointer;
	}

	.apple-pay-button-white-with-text {
	    background-color: white;
	    color: black;
	}

	.apple-pay-button-white-with-line-with-text {
	    background-color: white;
	    color: black;
	    border: .5px solid black;
	}

	.apple-pay-button-with-text.apple-pay-button-white-with-text > .logo {
	    background-image: -webkit-named-image(apple-pay-logo-black);
	    background-color: white;
	}

	.apple-pay-button-with-text.apple-pay-button-white-with-line-with-text > .logo {
	    background-image: -webkit-named-image(apple-pay-logo-black);
	    background-color: white;
	}

	.apple-pay-button-with-text > .text {
	    font-family: -apple-system;
	    font-size: calc(1em * var(--apple-pay-scale));
	    font-weight: 300;
	    align-self: center;
	    margin-right: calc(2px * var(--apple-pay-scale));
	}

	.apple-pay-button-with-text > .logo {
	    width: calc(35px * var(--scale));
	    height: 100%;
	    background-size: 100% 60%;
	    background-repeat: no-repeat;
	    background-position: 0 50%;
	    margin-left: calc(2px * var(--apple-pay-scale));
	    border: none;
	}

	</style>

	<script src="scripts/jquery-2.1.4.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/jquery.cookie.js"></script>
	
	<script src="https://sandbox-assets.secure.checkout.visa.com/checkout-widget/resources/js/integration/v1/sdk.js"></script>
	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://jstest.authorize.net/v1/Accept.js"></script>
	<script src="https://jstest.authorize.net/v3/acceptUI.js"></script>
	
<script type="text/javascript">

	var baseUrl = "https://www.authorize.net/customer/";
	var onLoad = true;
	tab = null;

	function returnLoaded() {
		console.log("Return Page Called ! ");
		showTab(tab);
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
	alert("test");
		params = parseQueryString(argument.qstr)
		parentFrame = argument.parent.split('/')[4];
		console.log(params);
		console.log(parentFrame);
		//alert(params['height']);
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
										$("#HPConfirmation p").html("<strong><b> Success.. !! </b></strong> <br><br> Your payment of <b>$"+transResponse.totalAmount+"</b> for <b>"+transResponse.orderDescription+"</b> has been Processed Successfully on <b>"+transResponse.dateTime+"</b>.<br><br>Generated Order Invoice Number is :  <b>"+transResponse.orderInvoiceNumber+"</b><br><br> Happy Shopping with us ..");
										$("#HPConfirmation p b").css({"font-size":"22px", "color":"green"});
										$("#HPConfirmation").modal("toggle");
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
 
</script>

</head>

<body style="padding-top: 50px;">
	
	<input type='hidden' id='cardinalRequestJwt' value='<?php echo $cardinalRequestJwt; ?>'>
	
	<div class="container-fluid" style="width: 100%; margin: 0; padding:0">
		

		<div id="getHPToken">
			<?php include 'getHostedPaymentForm.php'; ?>
		</div>
		
		<!-- <textarea><?php echo $xml->AsXML()?></textarea> -->

	</div>
		
		<div  id="iframe_holder" class="center-block" style="width:90%;max-width: 800px; height:800px;">
			<iframe id="load_payment" name="load_payment" width="100%" height="800px" frameborder="0" scrolling="auto" style="display:block;">
			</iframe>

			<iframe id="add_payment" class="embed-responsive-item panel" name="add_payment" width="100%"  frameborder="0" scrolling="no" hidden="true">
			</iframe>

			<form id="send_hptoken" action="https://accept.authorize.net/payment/payment" method="post" target="load_payment" >
				<input type="text" name="token" value="<?php echo $hostedPaymentResponse->token ?>" />
			</form>
		</div>

		<div class="tab-content panel-group">

			<div class="tab-pane" id="pay" hidden="true"></div>
		</div>

		

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel" style="font-weight: bold">Edit </h4>
		      </div>
		      <div class="modal-body">
		          	<iframe id="add_shipping" class="embed-responsive-item" name="add_shipping" width="100%"  frameborder="0" scrolling="no" hidden="true" ></iframe>
					<iframe id="edit_shipping" class="embed-responsive-item" name="edit_shipping" width="100%"  frameborder="0" scrolling="no" hidden="true"></iframe> 
		      </div>
		    </div>
		  </div>
		</div>

		<div class="modal fade" id="HPConfirmation" role="dialog">
		    <div class="modal-dialog" style="display: inline-block; vertical-align: middle;">
		      <div class="modal-content">
		        <div class="modal-header">
		          <button id="closeAcceptConfirmationHeaderBtn" type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title"><b>Payment Confirmation</b></h4>
		        </div>
			        <div class="modal-body" style="background-color: antiquewhite">
			          	<p style="font-size: 16px; font-style: italic; padding:10px; color: #444; text-align: center"></p>
			        </div>
		        <div class="modal-footer">
		          <button id="closeAcceptConfirmationFooterBtn" type="button" class="btn btn-success" data-dismiss="modal">Close</button>
		        </div>
		      </div> 
    		</div>
    	</div>

	</div>
</body>

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