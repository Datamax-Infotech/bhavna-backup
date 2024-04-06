<?php 
if(session_id() == ''){
    session_start();
}
	//require ("../../ucb/loop/inc/header_session.php");
	require ("../mainfunctions/database.php");
	require ("../mainfunctions/general-functions.php");
	//require ("../../../securedata/main-enc-class.php");

	db();
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<script src="scripts/jquery-2.1.4.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/jquery.cookie.js"></script>
	
	<script src="https://sandbox-assets.secure.checkout.visa.com/checkout-widget/resources/js/integration/v1/sdk.js"></script>
	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://jstest.authorize.net/v1/Accept.js"></script>
	<script src="https://jstest.authorize.net/v3/acceptUI.js"></script>
		
	<link rel="stylesheet" type="text/css" href="../stylesheet.css">

<title>B2B Invoice Payment</title>	
<script type="text/javascript">

	var baseUrl = "https://www.authorize.net/customer/";
	var onLoad = true;
	tab = null;

	function returnLoaded() { alert('111');
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
 
</script>

</head>

<body >

<div align="center">
	
	<?php 
		//$_REQUEST["selected_id"]
		
		$company_nm = ""; $warehouse_id = 0; $first_name = ""; $last_name = ""; $inv_number = "";
		$company_add = ""; $company_add2 = ""; $company_city = ""; $company_state = ""; $company_zip = ""; 
		$company_ph = ""; $company_eml = ""; $po_poorderamount = 0; $cc_owner = ""; $redirect_pg = "no";

		if (isset($_REQUEST["selected_id"])) {
			db_b2b();
			
			$redirect_pg = " selected ";
			$sql = "Select company, loopid from companyInfo Where ID = " . $_REQUEST["selected_id"];
			$dt_view_res = db_query($sql);
			while ($row = array_shift($dt_view_res)) 
			{
				$company_nm = $row["company"];
				$warehouse_id = $row["loopid"];
			}
			
			$sql = "Select * from b2bbillto where companyid = " . $_REQUEST["selected_id"] . " order by billtoid limit 1";
			$dt_view_res = db_query($sql);
			while ($row = array_shift($dt_view_res)) 
			{
				if ($row["name"] != ""){
					$fn_tmp = explode(" " , $row["name"]);
					$first_name = $fn_tmp[0];
					$last_name = $fn_tmp[1];
				}
				
				$company_add = $row["address"];
				$company_add2 = $row["address2"];
				$company_city = $row["city"];
				$company_state = $row["state"];
				$company_zip = $row["zipcode"];
				$company_ph = $row["mainphone"];
				$company_eml = $row["email"];
			}
		
			$tableedit  = "SELECT * FROM zones where zone_country_id in (223,38) and zone_code = '" . $company_state . "'" ;
			$dt_view_res = db_query($tableedit);
			while ($row = array_shift($dt_view_res)) {
				$company_state = $row["zone_name"];
			}	
			
			db();
			$dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" .  $_REQUEST["rec_id"] . "' AND po_file != ''";
			$result = db_query($dt_view_qry);
			$cc_number = ""; $cc_expires_month = ""; $cc_expires_year = ""; $cc_cvv = "";
			while ($dt_view_row = array_shift($result)) {	
				$inv_number = $dt_view_row["inv_number"];
				
				if($dt_view_row["po_cc_number"]!=""){
					$cc_number = decryptstr($dt_view_row["po_cc_number"]);
				}else{
					$cc_number = "";
				}
				if($dt_view_row["po_cc_expiration"]!=""){
					$cc_expires = decryptstr($dt_view_row["po_cc_expiration"]);
				}else{
					$cc_expires = "";
				}
				if($dt_view_row["po_cc_owner"]!=""){
					$cc_owner = decryptstr($dt_view_row["po_cc_owner"]);
				}else{
					$cc_owner = "";
				}
			
				$po_poorderamount = $dt_view_row["po_poorderamount"]; 
				
				$tmppos_1 = strpos($cc_expires, "/");
				if ($tmppos_1 != false)
				{ 	
					$cc_expires_month = substr($cc_expires, 0, strpos($cc_expires,'/')); 
					if (strlen($cc_expires_month) == 1) { $cc_expires_month = "0" . $cc_expires_month; }
					$cc_expires_year1 = substr($cc_expires, strpos($cc_expires,'/')+1); 
					$cc_expires_year = date("Y", strtotime($cc_expires_year1 . "-01-01"));
				} 
				if($dt_view_row["po_cc_cvv"]!=""){
					$cc_cvv = decryptstr($dt_view_row["po_cc_cvv"]);
				}else{
					$cc_cvv = "";
				}
			}
		}
	?>
	<form name="frmpayform" id="frmpayform" action="">
		<!-- <input type="text"  name="selected_id" id="selected_id" value="<?php echo $_REQUEST["selected_id"];?>" />
		<input type="text"  name="rec_id" id="rec_id" value="<?php echo $_REQUEST["rec_id"];?>" />
		<input type="text"  name="page_name" id="page_name" value="<?php echo $_REQUEST["page_name"];?>" /> -->

		<input type="text"  name="selected_id" id="selected_id" value="81689" />
		<input type="text"  name="rec_id" id="rec_id" value="" />
		<input type="text"  name="page_name" id="page_name" value="" />
		
		<table cellspacing="1" cellspacing="1" align="center" border="0" >
			<tr>
				<td align="left" width="30%">
					<img src="../images/demo.jpg">
				</td>
				<td align="center" width="40%">
					<font face="Ariel" size="5">
					<b>UsedCardboardBoxes.com<br></b>
					<b><i>B2B Invoice Payment Form</i></b>
					</i>
				</td>
				<td align="right" width="30%">
					<img src="../images/new_interface_help.gif">
				</td>
			</tr>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		
			<tr>
				<td ><font size="2">Select Transaction Type</font></td>
				<td >
					<select name="payment_type" id="payment_type" onchange="payment_type_chg()">
						<option value="authCaptureTransaction" <?php if ($_REQUEST["payment_type"] == "authCaptureTransaction") { echo " selected ";}?>>Authorization and Capture</option>
						<option value="authOnlyTransaction" <?php if ($_REQUEST["payment_type"] == "authOnlyTransaction") { echo " selected ";}?> >Authorization Only</option>
						<option value="priorAuthCaptureTransaction" <?php if ($_REQUEST["payment_type"] == "priorAuthCaptureTransaction") { echo " selected ";}?>>Prior Authorization Capture</option>
						<!-- <option value="voidTransaction" <?php if ($_REQUEST["payment_type"] == "voidTransaction") { echo " selected ";}?> >Void</option>
						<option value="refundTransaction" <?php if ($_REQUEST["payment_type"] == "refundTransaction") { echo " selected ";}?> >Refund</option> -->
					</select>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" align="center">
					<div id="divvoidref" style="display:none;">
						<table cellspacing="1" cellspacing="1" align="center" border="0" >
							<tr>
								<td ><font size="2">Original Transaction ID</font></td>
								<td >
									<input name="trans_id" id="trans_id" value=""/><br>
									<font size="2">(not required for unlinked refunds) *</font>  
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<font size="2">Accepted Payment Method Visa, MasterCard, American Express, Discover</font>
								</td>
							</tr>
							
							<tr>
								<td ><font size="2">Card Number</font></td>
								<td >
									<input name="trans_cardno" id="trans_cardno" value=""/><br>
									 <font size="2">(only the last four digits required, see Tips) *</font>    
								</td>
							</tr>
							<tr>
								<td ><font size="2">Expiration Date</font></td>
								<td >
									<input name="trans_expdate" id="trans_expdate" value=""/>
									<font size="2">(mmyy)</font>   
								</td>
							</tr>
							<tr>
								<td ><font size="2">Amount</font></td>
								<td >
									<input name="trans_amount" id="trans_amount" value=""/>
									 <font size="2">(i.e.,10.00) * </font>
								</td>
							</tr>

							<tr>
								<td colspan="2"><br><font size="2">
									TIPS FOR SUBMITTING REFUNDS:<br>
									+  A refund may only be issued for a charge transaction settled within the past 120 days.<br>
									+  The full Credit Card number is only required for unlinked refunds (without spaces).<br>
									+  Be sure to enter the correct amount for a partial refund.<br>
									</font>
								</td>
							</tr>
							
						</table> 
					</div>
					
					<div id="divcaptureonly" style="display:none;">
						<table cellspacing="1" cellspacing="1" align="center" border="0" >
							<tr>
								<td ><font size="2">Authorize Transaction ID</font></td>
								<td >
									<input name="auth_trans_id" id="auth_trans_id" value=""/><br>
								</td>
							</tr>
							
						</table> 
					</div>
					
					
				</td>
			</tr>

			<tr>
				<td ><font size="2">Amount</font></td>
				<td >
					<input name="trans_amount" id="trans_amount" value="<?php echo $_REQUEST["trans_amount"];?>"/>
				</td>
			</tr>

			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="btnsubmit" id="btnsubmit" value="Submit"/>
				</td>
			</tr>
			
		</table>
	</form>
	  
<?php 
	if (isset($_REQUEST["btnsubmit"])){
		$po_poorderamount = str_replace(",", "", $_REQUEST["trans_amount"]);

		if ($_REQUEST["payment_type"] == "priorAuthCaptureTransaction")
		{
			$auth_trans_id = $_REQUEST["auth_trans_id"];
			$ordertot = $po_poorderamount;
			
			$post_url = "https://secure.authorize.net/gateway/transact.dll";
			$payment_type = "Capture";
			$post_values = array();
			$post_values = array(
				// the API Login ID and Transaction Key must be replaced with valid values
				//"x_login"			=> "6Dz9Bf6Fm",
				//"x_tran_key"		=> "64W8Z4U3j6xkz98r",
				
				"x_login"			=> "4Rw6UcB57",
				"x_tran_key"		=> "8c6KJ9862eJs2jBx",

				"x_version"			=> "3.1",
				"x_delim_data"		=> "TRUE",
				"x_delim_char"		=> "|",
				"x_relay_response"	=> "FALSE",

				"x_type"			=> "PRIOR_AUTH_CAPTURE",
				"x_trans_id"		=> $auth_trans_id,
				"x_amount"			=> $ordertot
				// Additional fields can be added here as outlined in the AIM integration
				// guide at: http://developer.authorize.net
			);
		
			// This section takes the input fields and converts them to the proper format
			// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
			$post_string = "";
			foreach( $post_values as $key => $value )
				{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
			$post_string = rtrim( $post_string, "& " );

			$request = curl_init($post_url); // initiate curl object
			curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
			$post_response = curl_exec($request); // execute curl post and store results in $post_response

			curl_close ($request); // close curl object

			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array = explode($post_values["x_delim_char"],$post_response);

			//var_dump($post_response);

			// The results are output to the screen in the form of an html numbered list.
			echo "<div id='result_div'>";
			if ($response_array[0] == 1) {
				$payment_status = "approved";
				echo "<font size='8'>Payment Processed. Transaction has been approved.</font><br>Following is the response from Authorize net:<br>" . $response_array[3] . "<br>Transaction ID: " . $response_array[6]; 
			}else{
				$payment_status = "declined";
				echo "<font color='red' size='8'>Payment not Processed. Transaction has been declined.</font><br><br>Following is the response from Authorize net:<br>" . $response_array[3] . "<br>"; 
			}
			echo "</div>";
			
			if ($payment_status == "approved"){
				db();
				
				$sql = "INSERT INTO loop_transaction_buyer_cc SET trans_rec_id = " . $_REQUEST["rec_id"] . ", transaction_type = 'Capture' , transaction_id = '" . $response_array[6] . "', employee_id = " . $_COOKIE["employeeid"] . ", amount = '"  . $ordertot ."'";
				$result = db_query($sql);

				$qry = "Update loop_transaction_buyer SET trans_status = 4 where id = " . $_REQUEST["rec_id"] . "";
				$res_newtrans = db_query($qry);			
				
				$cc_fees = ($ordertot*3)/100;
				$sql_ins = "INSERT INTO loop_transaction_buyer_payments (`transaction_buyer_id` ,`company_id` ,`typeid`  ,`fileid` ,`employee_id` ,`date` ,`estimated_cost` ,`status` ,`notes` ,`notes2` ) VALUES ( ";
				$sql_ins .= " '". $_REQUEST['rec_id'] ."', '232', '8', '0', '" . $_COOKIE["employeeid"] . "', '". date("Y-m-d H:i:s") ."', '". $cc_fees ."', 6, '', '')";
				$result = db_query($sql_ins);
				
				$warehouse_id = 0;
				$sql1 = "Select loop_warehouse.id, loop_warehouse.company_name from loop_warehouse inner join loop_transaction_buyer on loop_transaction_buyer.warehouse_id = loop_warehouse.id where loop_transaction_buyer.id = " . $_REQUEST["rec_id"];
				$warehousedet = db_query($sql1);
				$warehousenm = "";
				while ($dt_view_row = array_shift($warehousedet)) {
					$warehouse_id = $dt_view_row["id"];
					$warehousenm = $dt_view_row["company_name"];
				}

				$msg_trans = "System generated log - CC Captured on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'];
				db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $warehouse_id . "', 'Supplier' , '" . $_REQUEST["rec_id"] . "', '" . $msg_trans . "', '" . $_COOKIE['employeeid'] . "'");			

				$emlstatus = sendemail_attachment(null, "", "davidkrasnow@usedcardboardboxes.com,", "bk@mooneem.com,creditcard@usedcardboardboxes.com", "", "admin@usedcardboardboxes.com", "Admin UCB","", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='https://loops.usedcardboardboxes.com//viewCompany.php?ID=".$_REQUEST['selected_id']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );
				//$emlstatus = sendemail_attachment(null, "", "prasad.brid@gmail.com,", "", "", "admin@usedcardboardboxes.com", "Admin UCB","", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='https://loops.usedcardboardboxes.com//viewCompany.php?ID=".$_REQUEST['selected_id']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );

				$page_url = "https://loops.usedcardboardboxes.com//viewCompany.php?ID=" . $_REQUEST["selected_id"] . "&show=transactions&warehouse_id=" . $warehouse_id . "&rec_type=Supplier&proc=View&searchcrit=&id=". $warehouse_id . "&rec_id=" . $_REQUEST["rec_id"] . "&display=" . $_REQUEST["page_name"];
				
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"". $page_url . "\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $page_url . "\" />";
				echo "</noscript>"; exit;
			}		
		
		}else {
		
			$_SESSION["payment_type"] = $_REQUEST["payment_type"];
			$_SESSION["ucb_ordertot"] = $po_poorderamount;
			$_SESSION["ucb_tax_val"] = 0;
			$_SESSION["ucb_inv_details"] = $_REQUEST["rec_id"];
			$_SESSION["inv_number"] = $inv_number;
			$_SESSION["ucb_product_details"] = "B2B product";
			$_SESSION["customer_email_address"] = $company_eml;
			$_SESSION["customer_phone"] = $company_ph;

			$_SESSION["ucb_bill_comp"] = $company_nm;
			$_SESSION["ucb_bill_fn"] = $first_name;
			$_SESSION["ucb_bill_ln"] = $last_name;
			$_SESSION["ucb_bill_add"] = $company_add;
			$_SESSION["ucb_bill_add2"] = $company_add2;
			$_SESSION["ucb_bill_city"] = $company_city;
			$_SESSION["ucb_bill_state"] = $company_state;
			$_SESSION["ucb_bill_zip"] = $company_zip;

			if (isset($_REQUEST["auth_trans_id"])){
				$_SESSION["auth_trans_id"] = $_REQUEST["auth_trans_id"];
			}else{
				$_SESSION["auth_trans_id"] = "";
			}l
			
						
			//$api_login_id = '4Rw6UcB57';
			//$transactionKey = '8c6KJ9862eJs2jBx'; // removed to protect the innocent

			//$url = "https://api.authorize.net/xml/v1/request.api";
		?>
			  <tr>
				<td>
					<input type='hidden' id='cardinalRequestJwt' value='<?php echo $cardinalRequestJwt; ?>'>

					<div style="width: 100%; text-align:center;">
						<img src="images/authorize.net-logo.jpg" alt="Authorize.net" width="150" height="40" border="0">
					</div>
					
					<div class="container-fluid" style="width: 100%; margin: 0; padding:0">
						<div id="getHPToken">
							<?php include 'getHostedPaymentForm.php'; ?>
						</div>
					</div>
						
						<div  id="iframe_holder" class="center-block" style="width:90%;max-width: 800px; height:800px;">

							<iframe id="load_payment" name="load_payment" width="100%" height="800px" frameborder="0" scrolling="auto" style="display:block;">
								Loading ....
							</iframe>

							<iframe id="add_payment" class="embed-responsive-item panel" name="add_payment" width="100%"  frameborder="0" scrolling="no" hidden="true">
							</iframe>

							<form id="send_hptoken" action="https://accept.authorize.net/payment/payment" method="post" target="load_payment" >
								<input type="hidden" name="token" value="<?php echo $hostedPaymentResponse->token ?>" />
							</form>
						</div>

						<div class="tab-content panel-group">
							<div class="tab-pane" id="pay" hidden="true"></div>
						</div>

						
						<div >
							<form id="post_data" name="post_data" action="https://loops.usedcardboardboxes.com//viewCompany.php?ID=<?php echo $_REQUEST["selected_id"];?>&show=transactions&warehouse_id=<?php echo $warehouse_id;?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $warehouse_id;?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&display=<?php echo $_REQUEST["page_name"];?>&trans_type=<?php echo $_REQUEST["payment_type"];?>" method="post" >
								<input type="hidden" name="response_accountType" id="response_accountType" value="" />
								<input type="hidden" name="response_accountNumber" id="response_accountNumber" value="" />
								<input type="hidden" name="response_transId" id="response_transId" value="" />
								<input type="hidden" name="response_amt" id="response_amt" value="<?php echo $po_poorderamount;?>" />
								<input type="hidden" name="response_responseCode" id="response_responseCode" value="" />
								<input type="hidden" name="response_authorization" id="response_authorization" value="" />
							</form>
							
							<form id="return_page" name="return_page" action="https://loops.usedcardboardboxes.com//viewCompany.php?ID=<?php echo $_REQUEST["selected_id"];?>&show=transactions&warehouse_id=<?php echo $warehouse_id;?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $warehouse_id;?>&rec_id=<?php echo $_REQUEST["rec_id"];?>&display=<?php echo $_REQUEST["page_name"];?>&trans_type=<?php echo $_REQUEST["payment_type"];?>" method="post" >
							</form>
						</div>
						
				</td>
			  </tr>

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
<?php 	
		}
	}?>

</div>

</html>
