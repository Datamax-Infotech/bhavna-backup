<?
ini_set("display_errors", "1");
error_reporting(E_ERROR); 

session_start();
$sessionId = session_id();
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$ipadd = $_SERVER['REMOTE_ADDR'];
$boxId = decrypt_password($_REQUEST["id"]);
if(isset($_REQUEST['quote_id'])){
	$redirectUrl = "index_quote.php?quote_id=".$_REQUEST['quote_id'];
	header("Location: " . $redirectUrl);
}

/*set the user details if request from clientdashboard*/
if(isset($_REQUEST['compnewid'])){
	$compnewid = decrypt_password($_REQUEST['compnewid']);
	$_SESSION['compnewid'] = $compnewid;
}

if(isset($_SESSION['compnewid']) && !empty($_SESSION['compnewid'])){
	$compnewid = $_SESSION['compnewid'];
}

$param1 = "";
if(isset($_REQUEST['param1'])){
	$param1 = decrypt_password($_REQUEST['param1']);
}


$miles_from = "";
if(isset($_REQUEST['miles'])){
	$miles_from = decrypt_password($_REQUEST['miles']);
}

$qry = "Select * FROM loop_boxes WHERE id =" . $boxId ;
$res = db_query($qry, db());		
$row = array_shift($res);	
$invId = $row["b2b_id"];	

$qryb2b = "Select * FROM inventory WHERE id =" . $invId;		
$resb2b = db_query($qryb2b, db_b2b() );		
$rowb2b = array_shift($resb2b);

/*echo "<pre>"; print_r($row); echo "</pre>";
echo "<pre>"; print_r($rowb2b); echo "</pre>";*/
$box_type = $rowb2b["box_type"];
/* SET BROWSER TITLE, PAGE TITLE, ID TITLE & UNIT PRICE STARTS */
if (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
	$browserTitle 	= "Buy Gaylord Totes"; 
	$pgTitle		= "Buy Gaylord Totes";
	$idTitle		= "Gaylord ID";
	$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
	$halfUnitPr 	= $fullUnitPr + 1.00;
	$qrtrUnitPr 	= $fullUnitPr + 2.00;
	$palletUnitPr 	= $fullUnitPr + 3.00;
}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
	$browserTitle 	= "Buy Shipping Boxes"; 
	$pgTitle		= "Buy Shipping Boxes";
	$idTitle		= "Shipping Box ID";
	$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
	$halfUnitPr 	= $fullUnitPr + 0.25;
	$qrtrUnitPr 	= $fullUnitPr + 0.50;
	$palletUnitPr	= $fullUnitPr + 1.00;
}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
	$browserTitle 	= "Buy Super Sacks";
	$pgTitle		= "Buy Super Sacks";
	$idTitle		= "Super Sack ID";
	$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
	$halfUnitPr 	= $fullUnitPr + 0.50;
	$qrtrUnitPr 	= $fullUnitPr + 1.00;
	$palletUnitPr	= $fullUnitPr + 2.00;
}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
	$browserTitle 	= "Buy Pallets"; 
	$pgTitle 		= "Buy Pallets"; 
	$idTitle		= "Pallet ID";
	$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
	$halfUnitPr 	= $fullUnitPr + 1.00;
	$qrtrUnitPr 	= $fullUnitPr + 2.00;
	$palletUnitPr	= $fullUnitPr + 4.00;
}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " "))) || ( strtolower(trim($box_type)) == strtolower(trim('Recycling')) ) ) { 
	$browserTitle 	= "Buy Items"; 
	$pgTitle 		= "Buy Items";
	$idTitle		= "Item ID";
	$fullUnitPr 	= $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
	$halfUnitPr 	= $fullUnitPr;
	$qrtrUnitPr 	= $fullUnitPr;
	$palletUnitPr	= $fullUnitPr;
}
/* SET BROWSER TITLE, PAGE TITLE, ID TITLE & UNIT PRICE ENDS */
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
	<link rel="stylesheet" href="CSS/radio-pure-css.css">
	<link rel="stylesheet" href="product-slider/slick.css">
	<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
	<link rel="stylesheet" href="product-slider/prod-style.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="https://www.google.com/jsapi"></script>
<script>

function show_moreinfo(){
	var checkopen = document.getElementById("extra-info-main").style.display;
	if (checkopen == "none"){
		document.getElementById("extra-info-main").style.display = "block";
	} else {
		document.getElementById("extra-info-main").style.display = "none";
	}
}

function productinfo(selectedval){  
	highlightRow();

	switch(selectedval){  
		case 1: 
			document.getElementById("radio1").checked = true;
			highlightRow();
			document.getElementById("productQntypeid").value = 1;
			document.getElementById("productQntype").value = document.getElementById("productQntype1").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt1").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice1").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal1").innerHTML;
			break;
		case 2: 
			document.getElementById("radio2").checked = true;
			highlightRow();
			document.getElementById("productQntypeid").value = 2;
			document.getElementById("productQntype").value = document.getElementById("productQntype2").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt2").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice2").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal2").innerHTML;
			break;
		case 3: 
			document.getElementById("radio3").checked = true;
			highlightRow();
			document.getElementById("productQntypeid").value = 3;
			document.getElementById("productQntype").value = document.getElementById("productQntype3").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt3").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice3").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal3").innerHTML;
			break;
		case 4: 
			document.getElementById("radio4").checked = true;
			highlightRow();
			document.getElementById("productQntypeid").value = 4;
			document.getElementById("productQntype").value = document.getElementById("productQntype4").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt4").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice4").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal4").innerHTML;
			break;
	}
	
}

	function showimg(){
		document.getElementById("map-overlay").style.display = "block";
		document.getElementById('light').style.display='block';
		document.getElementById('light').innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('map-overlay').style.display='none';>Close</a> &nbsp;<br/><img src='images/usa_map_territories.png' class='mapimg' style='object-fit: cover;'/>";
	}	

	function off_overlay() {
  		document.getElementById("map-overlay").style.display = "none";
	}
	
	
	function formvalidate(){
        var proradio = document.getElementsByName('radio');
        var proValue = false;

        for(var i=0; i<proradio.length;i++){
            if(proradio[i].checked == true){
                proValue = true;    
            }
        }
        if(!proValue){
            alert("Please select Truckload you want to order.");
            return false;
        } else {
			document.getElementById("orderfrm").submit(); 
		}

    }

function highlightRow(){
	var proradio = document.getElementsByName("radio"); 	
	for(var i=0; i<proradio.length;i++) { 
		if(proradio[i].checked == true){
			proradio[i].parentElement.parentElement.classList.add("highlightrow");    
		}else{
			proradio[i].parentElement.parentElement.classList.remove("highlightrow");
		}
	}
}

	function calculatedistance_ip(ipadd, id)
	{
		
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("distancewarehouse").innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("POST","calculate_distance.php?ipadd=" + ipadd + "&id="+id, true);
		xmlhttp.send();
	}

	var counter = 0;

	function calculatedistance(loopboxid) { 
		var zip = document.getElementById("distzip").value;
		if(counter > 5){
			alert("Limit of 5 distances reached");
			document.getElementById("caldist").disabled = true;
		} else {
			
			if (window.XMLHttpRequest)
			{
			  xmlhttp=new XMLHttpRequest();
			}
			else
			{
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			  xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("distancewarehouse").innerHTML = xmlhttp.responseText;
					document.getElementById("distzip").value = zip;
					counter = (counter + 1);
				}
			}

			xmlhttp.open("POST","calculate_distance.php?zip=" + zip + "&id="+ loopboxid, true);
			xmlhttp.send();
		}
	}

	function calculateShippingQte(prodId){
		var zip = document.getElementById("quotezip").value;
		alert("ok zip -> "+zip+" / prodId -> "+prodId);

		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				alert(xmlhttp.responseText);
				//document.getElementById("distancewarehouse").innerHTML = xmlhttp.responseText;
				//document.getElementById("distzip").value = zip;
			}
		}

		xmlhttp.open("POST","calculate_quote.php?zip=" + zip + "&id="+prodId, true);
		xmlhttp.send();

	}


	/*Scroll to top when order clicked BEGIN*/
	$(document).ready(function() {
	  $("#ordernow").click(function() {
			$('html,body').animate({
				scrollTop: $(".btn-div").offset().top},
				'slow');
		}); 
	});
	/*Scroll to top when order clicked END*/

	
	function show_shipping_quote(territory){
		let position = territory.search("Canada");
		if (position >= 0){
			alert("Unable to calculate freight rates for Canadian addresses on Boomerang. For shipping rates to Canada (or globally), please contact your Account Manager.");	
		}else{
			var selectobject = document.getElementById("btngetshippingquote"); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('lightnew').style.display='inline';
			document.getElementById("lightnew").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('lightnew').style.display='none';>Close</a>&nbsp;<center></center><br/>"+ document.getElementById("div_shipping_quote").innerHTML;
			document.getElementById('lightnew').style.left = 50 + '%';
			document.getElementById('lightnew').style.top = n_top - 100 + 'px';
		}
	}
	
	function f_getPosition (e_elemRef, s_coord) {
		var n_pos = 0, n_offset,
			e_elem = e_elemRef;

		while (e_elem) {
			n_offset = e_elem["offset" + s_coord];
			n_pos += n_offset;
			e_elem = e_elem.offsetParent;
		}

		e_elem = e_elemRef;
		while (e_elem != document.body) {
			n_offset = e_elem["scroll" + s_coord];
			if (n_offset && e_elem.style.overflow == 'scroll')
				n_pos -= n_offset;
			e_elem = e_elem.parentNode;
		}
		return n_pos;
	}
	
	function get_shipping_quote(loop_box_id){
	
		if (document.getElementById("shipping_add1").value == ""){
			alert("Please enter the Address.");
			return false;
		}
		if (document.getElementById("shipping_city").value == ""){
			alert("Please enter the City.");
			return false;
		}
		if (document.getElementById("shipping_state").value == ""){
			alert("Please enter the State.");
			return false;
		}
		if (document.getElementById("shipping_zip").value == ""){
			alert("Please enter the Proximity.");
			return false;
		}
	
		document.getElementById("btn_get_shipping_q").value = "Re-calculate Shipping";
		document.getElementById("btn_get_shipping_q").style.display = "none";
		
		document.getElementById("trdiv_shipping_quote").innerHTML = "Loading ... <img src='https://b2b.usedcardboardboxes.com/images/wait_animated.gif' />";
		
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				alert("Shipping Quote is calculated = $" + xmlhttp.responseText);
				document.getElementById("td_shipping_quote").innerHTML = "Shipping Quote = $" + xmlhttp.responseText;
				document.getElementById("btngetshippingquote").value = "Re-calculate Shipping";
				document.getElementById('lightnew').style.display='none';
			}
		}

		xmlhttp.open("POST","calculate_distance.php?getshippingquote_id=" + loop_box_id + "&shipping_add1="+ encodeURIComponent(document.getElementById("shipping_add1").value)	+ "&shipping_add2="+ encodeURIComponent(document.getElementById("shipping_add2").value)+ "&shipping_city="+ encodeURIComponent(document.getElementById("shipping_city").value)+ "&shipping_state="+ encodeURIComponent(document.getElementById("shipping_state").value)+ "&shipping_zip="+ encodeURIComponent(document.getElementById("shipping_zip").value), true);
		xmlhttp.send();	
	}

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

<style>
	.highlightrow{
		transition : all ease-in-out .25s;
		background-color: #e4f9e3;
	}
	
	#map-overlay {
	  position: fixed;
	  display: none;
	  width: 100%;
	  height: 100%;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: rgba(0,0,0,0.5);
	  z-index: 2;
	  cursor: pointer;
	}

	.white_content_new {
		display: none;
		position: absolute;
		top: 25px;
		left: 10%;
		width: 500px;
		height: 450px;
		padding: 16px;
		border: 1px solid gray;
		background-color: white;
		z-index:1002;
		overflow: auto;
	}
	
	.white_content {
		display: none;
		position: absolute;
		top: 50%;
		left: 50%;
		font-size: 14px;
		color: white;
		transform: translate(-50%,-50%);
		-ms-transform: translate(-50%,-50%);
		padding: 10px 10px 10px 10px;
		border: 2px solid black;
		background-color: white;
		overflow:auto;
		height:350px;
		width:450px;
		z-index:1002;
	}
	
	.mapimg{
		width:400px;
		height:300px;
	}
	.seemap{
		cursor:pointer;
	}
	@media screen and (max-width: 360px) 
	{
		.white_content {
			width:350px;
		}
		.mapimg{
			width:100%;
			height:auto;
		}
	}

	.btnOrderTop {
	    color: #FFF;
	    border: 2px solid #5cb726;
	    padding: 12px 24px;
	    display: inline-block;
	    font-size: 14px;
	    letter-spacing: 1px;
	    cursor: pointer;
	    background-color: #5cb726;
	    box-shadow: inset 0 0 0 0 #3e7f18;
	    -webkit-transition: ease-out 0.4s;
	    -moz-transition: ease-out 0.4s;
	    transition: ease-out 0.4s;
	    border-radius: 5px;
	}

	.show-order-now{
		display: none;
	}

	@media screen and (max-width: 880px){
		.show-order-now{
			display: table-cell;
			text-align: right;
			font-size: 1.2857142857em;
			line-height: 1em;
			color: #333333;
		}
	}
	@media screen and (max-width: 768px) {
		.show-order-now{
			display: table-cell;
			text-align: right;
			font-size: 1.2857142857em;
			line-height: 1em;
			color: #333333;
		}
	}

	
</style>
</head>

<body >
	<div id="map-overlay" onclick="off_overlay()">
		<div id="light" class="white_content">	</div>
	</div>
	
	<div id="lightnew" class="white_content_new1">	</div>

	<div class="main_container">
		<div class="sub_container">
			<div class="header">
				<div id="container">
					<div id="left">
						<div class="logo_img">
							<div class="logo_display">
								<? if ($param1 != "") {?>
									<a href="https://boomerang.usedcardboardboxes.com/?param1=<? echo $param1;?>">
								<? }else { ?>
									<a href="https://b2b.usedcardboardboxes.com/">
								<? } ?>
							
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
tlssitdctklcom2a5veoqvshu1
tlssitdctklcom2a5veoqvshu1
-------------------------------------------------------------------------------*/

/*data store in db for tracking*/
$machineIP = ''; $userId = ''; 
if(!empty($_COOKIE["uid"])){
	echo $userId = $_COOKIE["uid"];
}

if ($_SESSION['productData']) {
	$orderData = $_SESSION['productData'];
	if ($orderData['ProductLoopId'] == $boxId) {
		$id = $orderData['ProductLoopId'];
	}else{
		$id = $boxId;
	}	
}else{
	$id = $boxId;
}	
//$id = 2566;
$recordDt 	= date('YmdHis');


$machineIP 	= $_SERVER['REMOTE_ADDR'];
//$machineIP 	= getHostByName(getHostName());

/*$location = @unserialize(file_get_contents('http://ip-api.com/php/'.$machineIP));
if($location && $location['status'] == 'success'){
	$loczip = $location['zip'];
}*/


	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE shipping_add1 <> '' and session_id = '".$sessionId."' and product_loopboxid = '" . $id ."'", db());
	$rec_found = "no";
	while ($rowSessionDt = array_shift($getSessionDt)) {
		$rec_found = "yes";
		$locadd = $rowSessionDt['shipping_add1'];
		$locadd2 = $rowSessionDt['shipping_add2'];
		$loccity = $rowSessionDt['shipping_city'];
		$locstate = $rowSessionDt['shipping_state'];
		$loczip = $rowSessionDt['shipping_zip'];
	}

	if ($rec_found == "no"){
		$com_qry=db_query("select shipAddress, shipAddress2, shipState, shipCity, shipZip from companyInfo where ID='". $compnewid."'",db_b2b());
		$com_row= array_shift($com_qry);
		$locadd = $com_row["shipAddress"];
		$locadd2 = $com_row["shipAddress2"];
		$loccity = $com_row["shipCity"];
		$locstate = $com_row["shipState"];
		$loczip = $com_row["shipZip"];

	}
	
/*check the user product entry already or not with current session id*/
//AND product_loopboxid = '".$id . "'
$getSessionDt = db_query("SELECT id FROM b2becommerce_tracking WHERE session_id = '".$sessionId."' and product_loopboxid = '".$id."'", db() );
$rowSessionDt = array_shift($getSessionDt);
if($rowSessionDt["id"] != ""){
	//db_query("Update b2becommerce_tracking set product_loopboxid = '".$id."' where session_id = '".$sessionId."'", db());
}else{
	db_query("INSERT INTO b2becommerce_tracking(session_id, product_loopboxid, machine_ip, user_master_id, record_date) VALUES('".$sessionId."', '".$id."', '".$machineIP."', '".$userId."', '".$recordDt."')", db());
}

$qry = "Select * FROM loop_boxes WHERE id =" . $id ;
$res = db_query($qry, db());		
$row = array_shift($res);	
$id2 = $row["b2b_id"];	
$qryb2b = "Select * FROM inventory WHERE id =" . $id2;		
$resb2b = db_query($qryb2b, db_b2b() );		
$rowb2b = array_shift($resb2b);

$product_name_id = 1;
if (isset($_REQUEST["product_name_id"])){	
	$product_name_id = $_REQUEST["product_name_id"];
}	

$data_product_name1 = ""; $data_product_name2 = ""; $data_product_name3 = ""; $data_product_name4 = "";	 
if ($product_name_id == 1){ 
	$data_product_name1 = " checked ";
}	
if ($product_name_id == 2){ 
	$data_product_name2 = " checked ";
}	
if ($product_name_id == 3){ 
	$data_product_name3 = " checked ";
}	
if ($product_name_id == 4){ 
	$data_product_name4 = " checked ";
}	

/*-------------------------------------------------------------------------------
Started updating by Amarendra dated 01-04-2021 
-------------------------------------------------------------------------------*/
?>
	<div class="new_section new-section-margin">
	<div class="new_container">

			<div class="inner-margin">
				<div class="section-top-margin">
					<h1 class="section-title"><?=$pgTitle;?></h1>
					<div class="title_desc">It's as easy as <b>Select Your Quantity</b> and <b>Buy</b>!</div>
				</div>
				<span class="show-order-now">
			  		<table>
			  			<tr><td align="center"><button type="button" onclick="javascript: formvalidate();" class="btnOrderTop" data-testid="order-button">Proceed to Checkout</button></td></tr>
			  		</table>			  		
			  	</span>
			</div>
			<!--Start Breadcrums-->
			<nav aria-label="Breadcrumb">
				<ol class="breadcrumb " role="list">
				<li class="breadcrumb__item breadcrumb__item--current">
				  <span class="breadcrumb__text breadcrumnow">Select Quantity</span>
				  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>

				  <li class="breadcrumb__item breadcrumb__item--blank" aria-current="step">
				  <span class="breadcrumb__text">Contact</span>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
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
			
			<!--For Shipping Quote-->
			<div id="div_shipping_quote" class="content-div content-padding" style="display:none;">
				<div class="popup-cal-shipping-table">
					<table class="qty-table-top" style="width:100%;">
						<tr>
							<td style="text-align:center !important;" colspan="2" ><strong>Enter delivery address</strong></td>
						</tr>
						<tr>
							<td >Street Address</td>
							<td ><input class="input-new-css" type="text" name="shipping_add1" id="shipping_add1" value="<? echo $locadd;?>" size="10" placeholder="Street Address"></td>	
						</tr>
						<tr>
							<td >Suite number (optional)</td>
							<td ><input class="input-new-css" type="text" name="shipping_add2" id="shipping_add2" value="<? echo $locadd2;?>" size="10" placeholder="Suite number (optional)"></td>	
						</tr>
						<tr>
							<td >City</td>
							<td ><input class="input-new-css" type="text" name="shipping_city" id="shipping_city" value="<? echo $loccity;?>" size="10" placeholder="City"></td>	
						</tr>
						<tr>
							<td >State</td>
							<td >
								<select id="shipping_state" name="shipping_state">
									<option value=""></option>
									<?	
										$res = db_query("Select * from state_master order by state" , db()); 
										while($fetch_data=array_shift($res))
										{
											if ($fetch_data['state_code'] == $locstate ) { $tmpvar = " selected "; } else { $tmpvar = " "; }
											echo "<option value='" . $fetch_data["state_code"] . "' $tmpvar >" . $fetch_data["state"] ." </option>";
										}
									?>									
								</select>
							</td>	
						</tr>
						<tr>
							<td >Proximity</td>
							<td ><input class="input-new-css" type="text" name="shipping_zip" id="shipping_zip" value="<? echo $loczip;?>" size="10" placeholder="Zip code"></td>	
						</tr>
						<tr>
							<td colspan="2" id="tr_shipping_quote" style="text-align:center !important;">
							<div id="trdiv_shipping_quote"></div>
							<input class="button_slide_cal_shipping_new slide_right" type="button" name="btn_get_shipping_q" id="btn_get_shipping_q" onclick="get_shipping_quote(<? echo $id;?>)" value="Calculate Shipping"></td>	
						</tr>
					</table>	
				</div>
			</div>			
			
			<!--End Breadcrums-->
			<div class="content-div content-padding">
				<div class="left_qty">
					<table class="qty-table-top">
						<tr>
							<td><strong>Lead Time </strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">If you order now, this is how long until a full truckload is accumulated and ready to ship</span></div></td>
							<td>
								<?php
									//$estimated_next_load = $row["buy_now_load_can_ship_in"];
									$estimated_next_load = "Load Ships in " . get_lead_time_v3(6, $id, $rowb2b["lead_time"], '');
									echo $estimated_next_load;
								?>
							</td>
						</tr>
						 
						 <tr>
							<td><strong>Location </strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Territory where the boxes are located</span></div>
								<a class="seemap" onclick="showimg()">(see map)</a>							
							</td>
							<td>
								<? 
								$customer_pickup_allowed = $row['customer_pickup_allowed']; //==1
								$box_warehouse_id = $row["box_warehouse_id"];  //!=238
								$shipfrom_state = ""; $shipfrom_city = '';
								if ($rowb2b["vendor_b2b_rescue"] != "" && $box_warehouse_id == "238"){
										$q1 = "SELECT * FROM loop_warehouse where id = ".$rowb2b["vendor_b2b_rescue"];
										//echo $q1 . "<br>"; 
										$v_query = db_query($q1, db());
										while($v_fetch = array_shift($v_query))
										{
											$com_qry=db_query("select shipState, shipCity from companyInfo where ID='".$v_fetch["b2bid"]."'",db_b2b());
											$com_row= array_shift($com_qry);
											$shipfrom_state = $com_row["shipState"];
											$shipfrom_city = $com_row["shipCity"];
										}
								}
								elseif($box_warehouse_id > 0 && $box_warehouse_id!="238"){
									$lwqry = db_query("Select * from loop_warehouse where id = ".$box_warehouse_id, db() );
									//echo "Select * from loop_warehouse where id = ".$box_warehouse_id . "<br>"; 
									while ($lwrow = array_shift($lwqry))
									{
										$shipfrom_state = $lwrow["warehouse_state"]; 
										$shipfrom_city = $lwrow["warehouse_city"];
									}
								}								
	
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								$territory="";
								$canada_east = array('NB', 'NF', 'NS','ON', 'PE', 'QC');
								$east = array('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV');
								$south = array('NC','SC','GA','AL','MS','TN','FL');
								$midwest =array('MI','OH','IN','KY');
								$north_central = array('ND','SD','NE','MN','IA','IL','WI');
								$south_central = array('LA','AR','MO','TX','OK','KS','CO','NM');
								$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
								$pacific_northwest=array('WA','OR','ID','MT','WY','AK');
								$west=array('CA','NV','UT','AZ','HI');
								$canada=array();
								$mexico==array('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA');
								$territory_sort=99;	
								if (in_array($shipfrom_state, $canada_east, TRUE)) 
								{ 
									$territory="Canada East";
									$territory_sort=1;
								} 
								elseif(in_array($shipfrom_state, $east, TRUE))
								{ 
									$territory="East";
									$territory_sort=2;
								} 
								elseif(in_array($shipfrom_state, $south, TRUE))
								{ 
									$territory="South";
									$territory_sort=3;
								} 
								elseif(in_array($shipfrom_state, $midwest, TRUE))
								{ 
									$territory="Midwest";
									$territory_sort=4;
								} 
								else if(in_array($shipfrom_state, $north_central, TRUE))
								{ 
								  $territory="North Central";
									$territory_sort=5;
								} 
								elseif(in_array($shipfrom_state, $south_central, TRUE))
								{ 
									$territory="South Central";
									$territory_sort=6;
								} 
								elseif(in_array($shipfrom_state, $canada_west, TRUE))
								{ 
									$territory="Canada West";
									$territory_sort=7;
								} 
								elseif(in_array($shipfrom_state, $pacific_northwest, TRUE))
								{ 
									$territory=" Pacific Northwest";
									$territory_sort=8;
								} 
								elseif(in_array($shipfrom_state, $west, TRUE))
								{ 
									$territory="West";
									$territory_sort=9;
								} 
								elseif(in_array($shipfrom_state, $canada, TRUE))
								{ 
									$territory="Canada";
									$territory_sort=10;
								} 
								elseif(in_array($shipfrom_state, $mexico, TRUE))
								{ 
									$territory="Mexico";
									$territory_sort=11;
								} 
								if($shipfrom_city != ''){
									$shipfrom_city = $shipfrom_city.",";
								}

								if($customer_pickup_allowed == "1" && $box_warehouse_id != "238" ){
									echo $territory . " Territory"; //(".$shipfrom_city.$shipfrom_state.")";
								}else{
									echo $territory . " Territory";
								}								
								
								?>
							</td>
						</tr>
				        <tr>
							<td><strong>Delivery</strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Get the Shipping Quote.</span></div></td>
							<td >
								<div id="td_shipping_quote"></div>
								<form action="" method="post">
									<input class="button_slide_dis slide_right" type="button" id="btngetshippingquote" name="btngetshippingquote" onclick="javascript:show_shipping_quote('<? echo $territory;?>');" value="Calculate Shipping">
								</form>
							</td>
						</tr>
				        <tr>
							<td><strong>Enter Zip</strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">To see how close the item is to your facility.</span></div></td>
							<td>
								<form action="" method="post">
									<input class="field__input" type="text" name="distzip" id="distzip" value="<?=$loczip?>" size="10" maxlength="7" placeholder="Zip code">
									<input class="button_slide_dis slide_right" type="button" id="caldist" name="caldist" onclick="javascript:calculatedistance(<? echo $boxId;?>);" value="Find Distance">
								</form>
							</td>
						</tr>
						<tr>
							<td><strong>Distance</strong>
								<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">Auto-calculates based on your IP address, unless you enter a different zip.</span>
								</div>	
							</td>
							<td>
							<? if ($miles_from != "") { ?>
								<span id="distancewarehouse" ><i><? echo $miles_from;?> miles</i></span>
							<? }else { ?>
								<span id="distancewarehouse" style="color:#aea7a7;"><i>Enter zip code to check</i></span>
							<? } ?>
							</td>
						</tr>
						
					</table>
					
					
					<div class="div-space"></div>
					<div class="qty-div">
						<table class="qty-table" id="productInfo">
							<caption>Select Quantity You Want to Order</caption>
							<tr>
								<th width="170px">
									Truckload
								</th>
								<th>
									Quantity
								</th>
								<th>
									Price/Unit*^
								</th>
								<th>
									Total
								</th>
							</tr>
							<?php
							$unitprice = $rowb2b["onlineDollar"] + $rowb2b["onlineCents"];
							//$unitfactor = 1.0031812;
							
							$halfprice = ($unitprice + 1.50);
							$quarterprice = ($unitprice + 3.00);
							$palleteprice = ($unitprice + 6.00);	
							$ship_ltl = $row["ship_ltl"];

							?>
							<tr id="productinfo_1" class="highlightrow" onclick="javascript: productinfo(1);">
								<td align="left">
									<input id="radio1" type="radio" name="radio" value="1" <? echo $data_product_name1;?> <?=($row["ship_ltl"]=="0")? ' checked ' : ' ';?> onclick="javascript: productinfo(1);">
									<label for="radio1"><span><span></span></span>
									<label id="productQntype1">Full</label></label>

								</td>
								<td>
									<span id="productQnt1"><?=number_format(trim($row["boxes_per_trailer"]));?>
									</span>
									
								</td>
								<td>
									$<span id="productQntprice1"><?=number_format($fullUnitPr,2);?>
									</span>
									
								</td>
								<td>
									<? $priceUnit = number_format($fullUnitPr,2); ?>
									$<span id="productTotal1"><? echo number_format(($row["boxes_per_trailer"] * $priceUnit),2);?></span>
								</td>
							</tr>
							<?php  
							if($row["ship_ltl"] == 0){	
								
							?>
								<tr style="color:#666;">
									<td align="left" colspan="4">
										<i>This location can only ship full truckload quantities</i>
									</td>
								</tr>
							<?php
							} else {
							?>
								<tr id="productinfo_2" onclick="javascript: productinfo(2);" >
									<td align="left">
										<input id="radio2" type="radio" name="radio" value="2" <? echo $data_product_name2;?> onclick="javascript: productinfo(2);" >
										<label for="radio2"><span><span></span></span>
										<label id="productQntype2">Half</label></label>
									</td>
									<td>
										<span id="productQnt2"><?=number_format($row["boxes_per_trailer"]/2);?></span>
									</td>
									<td>
										$<span id="productQntprice2"><?=number_format($halfUnitPr, 2);?></span>
									</td>
									<td>
										<? $halfpriceUnit = number_format($halfUnitPr, 2); ?>
										$<span id="productTotal2"><? echo number_format((($row["boxes_per_trailer"]/2)*$halfpriceUnit), 2);?></span>
									</td>
								</tr>
								<tr id="productinfo_3" onclick="javascript: productinfo(3);" >
									<td align="left">
										<input id="radio3" type="radio" name="radio" value="3" <? echo $data_product_name3;?> onclick="javascript: productinfo(3);">
										<label for="radio3"><span><span></span></span><label id="productQntype3">Quarter</label></label>

									</td>
									<td>
										<? $qrtQtyCal = number_format( ceil( $row["boxes_per_trailer"]/$row["bpallet_qty"]/4 ) * $row["bpallet_qty"] );?>
										<span id="productQnt3"><? echo $qrtQtyCal; ?></span>
									</td>
									<td>
										$<span id="productQntprice3"><?=number_format($qrtrUnitPr, 2);?></span>
									</td>
									<td>
										<? 
										$testqrtQtyCal = ceil( $row["boxes_per_trailer"]/$row["bpallet_qty"]/4 ) * $row["bpallet_qty"];
										$qrtrpriceUnit = number_format($qrtrUnitPr, 2); 
										?>
										$<span id="productTotal3"><? echo number_format(($testqrtQtyCal*$qrtrpriceUnit), 2);?></span>
									</td>
								</tr>
								<!-- <tr id="productinfo_4" onclick="javascript: productinfo(4);" >
									<td align="left">
										<input id="radio4" type="radio" name="radio" value="4" <? echo $data_product_name4;?> onclick="javascript: productinfo(4);">
										<label for="radio4"><span><span></span></span><label id="productQntype4">1 Pallet</label></label>
									</td>
									<td>
										<span id="productQnt4"><?=number_format($row["bpallet_qty"]);?></span>
									</td>
									<td>
										$<span id="productQntprice4"><?=number_format($palletUnitPr, 2);?></span>
									</td>
									<td>
										<?
										$palletunitPr = number_format($palletUnitPr, 2);
										?>											
										$<span id="productTotal4"><? echo number_format(($row["bpallet_qty"] * $palletunitPr), 2); ?></span>
									</td>
								</tr> -->
							<?php
							}
							?>
						</table>
						<br>
						<div class="note_text">
							<i>* Shipping calculated in next step (separately)</i><br><br>
							<i>^ Price subject to change at any time, without notice, based on supply / demand</i>
						</div>
					</div>
					<div class="btn-div" id="order-div">
					  <form action="contact.php" method="post" id="orderfrm">
						<?php
						if(isset($_REQUEST["quote_id"])){
							echo '<input type="hidden" name="quoteid" value="'.$_REQUEST["quote_id"].'">';
						}
						?>

						<input type="hidden" name="userid" value="<?=$_COOKIE["uid"]?>">
						<input type="hidden" id="productId" name="productIdloop" value="<?=$row["id"]?>">
						<input type="hidden" id="productQntypeid" name="productQntypeid" value="">
						<input type="hidden" id="productQntype" name="productQntype" value="">
						<input type="hidden" id="productQnt" name="productQnt" value="">
						<input type="hidden" id="productQntprice" name="productQntprice" value="">
						<input type="hidden" id="productTotal" name="productTotal" value="">
						<input type="hidden" id="hdAvailability" name="hdAvailability" value="<? echo $estimated_next_load;?>">
						<input type="hidden" id="session_login_id" name="session_login_id" value="<? echo $param1;?>">
						
						<button type="button" onclick="javascript: formvalidate();" class="button_slide slide_right" data-testid="order-button">Proceed to Checkout</button>
					  </form>
					</div>
				</div>
				<?
					$b2b_shape_rect = $rowb2b["shape_rect"];
					$b2b_shape_oct = $rowb2b["shape_oct"];
					$b2b_top_nolid = $rowb2b["top_nolid"];
					$b2b_top_partial = $rowb2b["top_partial"];
					$b2b_top_full = $rowb2b["top_full"];
					$b2b_top_hinged = $rowb2b["top_hinged"];
					$b2b_top_spout = $rowb2b["top_spout"];
					$b2b_top_open = $rowb2b["top_open"];
					$b2b_top_duffle = $rowb2b["top_duffle"];
					$b2b_top_remove = $rowb2b["top_remove"];
					$b2b_bottom_no = $rowb2b["bottom_no"];
					$b2b_bottom_partial = $rowb2b["bottom_partial"];
					$b2b_bottom_partialsheet = $rowb2b["bottom_partialsheet"];
					$b2b_bottom_fullflap = $rowb2b["bottom_fullflap"];
					$b2b_bottom_interlocking = $rowb2b["bottom_interlocking"];
					$b2b_bottom_tray = $rowb2b["bottom_tray"];
					$b2b_bottom_spout = $rowb2b["bottom_spout"];
					$b2b_bottom_spiked = $rowb2b["bottom_spiked"];
					$b2b_bottom_flat = $rowb2b["bottom_flat"];
					$b2b_vents_no = $rowb2b["vents_no"];
					$b2b_vents_yes = $rowb2b["vents_yes"];
					if($rowb2b['vents_no'] == 1){ $b2b_no_of_vents = "No"; }elseif($rowb2b['vents_yes'] == 1){ $b2b_no_of_vents = "Yes"; }
					$b2b_no_of_vents_min = $rowb2b["no_of_vents_min"];
					$b2b_no_of_vents_max = $rowb2b["no_of_vents_max"];
					
					$shape = ""; $bottom = ""; $top="";
					$bottom_1 = ""; $bottom_2 = ""; $bottom_3 = ""; $bottom_4 = ""; $bottom_5 = ""; $bottom_6 = ""; $bottom_7 = ""; $bottom_8 = "";
					$top_1 = ""; $top_2 = ""; $top_3 = ""; $top_4 = "";$top_5 = "";$top_6 = "";
					$shape_1 = ""; $shape_2= "";

					if ($b2b_shape_oct == 1)
					{
						$shape_1 = "Octagonal";
					}

					if ($b2b_shape_rect == 1)
					{
						$shape_2 = "Rectangular";
					}
					 ///top..............
					if ($b2b_top_nolid == 1)
					{
						$top_1 = "None";
					}
					if ($b2b_top_partial == 1)
					{
						$top_2 = "Partial Flap";
					}
					if ($b2b_top_full == 1)
					{
						$top_3 = "Full Flap";
					}
					if ($b2b_top_hinged == 1)
					{
						$top_4 = "Hinged Lid";
					}
					if ($b2b_top_remove == 1)
					{
						$top_5 = "Removable Lid";
					}
					if ($b2b_top_open == 1)
					{
						$top_6 = "Open Top";
					}
					if ($b2b_top_spout == 1)
					{
						$top_7 = "Spout Top";
					}
					if ($b2b_top_duffle == 1)
					{
						$top_8 = "Duffle Top";
					}

					if ($b2b_bottom_no == 1)
					{
						$bottom_1 = "None";
					}
					if ($b2b_bottom_partial == 1)
					{
						$bottom_2 = "Partial Flap Without Slip Sheet";
					}
					if ($b2b_bottom_partialsheet == 1)
					{
						$bottom_3 = "Partial Flap With Slip Sheet";
					}
					if ($b2b_bottom_fullflap == 1)
					{
						$bottom_4 = "Full Flap";
					}
					if ($b2b_bottom_interlocking == 1)
					{
						$bottom_5 = "Interlocking Flaps";
					}
					if ($b2b_bottom_tray == 1)
					{
						$bottom_6 = "Tray";
					}
					if ($b2b_bottom_spout == 1)
					{
						$bottom_7 = "Spout Bottom";
					}
					if ($b2b_bottom_flat == 1)
					{
						$bottom_8 = "Flat Bottom";
					}
					if ($b2b_bottom_spiked == 1)
					{
						$bottom_9 = "Flat Spiked Bottom";
					}
					$vents = "";
					if ($b2b_vents_no == 1) {
						$vents = "No Vents";
					}			
					$val_bottom = $bottom_1.",".$bottom_2.",".$bottom_3.",".$bottom_4.",".$bottom_5.",".$bottom_6.",".$bottom_7.",".$bottom_8.",".$bottom_9;
					$str1 = trim ($val_bottom, ',');
					$bottom = preg_replace('/,+/', ', ', $str1);  

					$val_top =  $top_1.",".$top_2.",".$top_3.",".$top_4.",".$top_5.",".$top_6.",".$top_7.",".$top_8;
					$str2 = trim ($val_top, ',');
					$top = preg_replace('/,+/', ', ', $str2); 
					 
					$val_shape =  $shape_1.",".$shape_2;
					$str3 = trim ($val_shape, ',');
					$shape = preg_replace('/,+/', ', ', $str3);  
					
					$dimension = ""; $wall_str = "";
					if($row["uniform_mixed_load"] == "Mixed"){					
						$blength_min = floatval($row["blength_min"]);
						$blength_max = floatval($row["blength_max"]);
						
						if ($blength_min == $blength_max){
							$dimension = $blength_min . '" x ';
						}else{
							$dimension = $blength_min . ' - '. $blength_max . '" x ';
						}											
						$bwidth_min = floatval($row["bwidth_min"]);
						$bwidth_max = floatval($row["bwidth_max"]);
						
						if ($bwidth_min == $bwidth_max){
							$dimension .= $bwidth_min . '" x ';
						}else{
							$dimension .= $bwidth_min . ' - '. $bwidth_max . '" x ';
						}											

						$bheight_min = floatval($row["bheight_min"]);
						$bheight_max = floatval($row["bheight_max"]);
						
						if ($bheight_min == $bheight_max){
							$dimension .= $bheight_min ;
						}else{
							$dimension .= $bheight_min . ' - '. $bheight_max . '"';
						}											
						
						$bwall_min = $row["bwall_min"];
						$bwall_max = $row["bwall_max"];
						
						if ($bwall_min != "" && $bwall_max != ""){
							if ($bwall_min == $bwall_max){
								$wall_str = $bwall_min . 'ply';
							}else{
								$wall_str = $bwall_min . '-'. $bwall_max . 'ply';
							}											
						}
					}else{
						$length = $row["blength"];
						$width = $row["bwidth"];
						$depth = $row["bdepth"];
						if ($row["blength_frac"] != "") {
							$arr_length = explode("/", $row["blength_frac"]);
							if (count($arr_length) > 0 ) {
								$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
								$length = floatval(intval($length) + $blength_frac);
							}
						}

						if ($row["bwidth_frac"] != "") {
							$arr_length = explode("/", $row["bwidth_frac"]);
							if (count($arr_length) > 0 ) {
								$bwidth_frac = intval($arr_length[0])/intval($arr_length[1]);
								$width = floatval($width + $bwidth_frac);
							}
						}
						if ($row["bdepth_frac"] != "") {
							$arr_length = explode("/", $row["bdepth_frac"]);
							if (count($arr_length) > 0 ) {
								$bdepth_frac = intval($arr_length[0])/intval($arr_length[1]);
								$depth = floatval($depth + $bdepth_frac);
							}
						}
						
						$dimension =  ($length=="")? '' : $length . '" x ';
						$dimension .= ($width=="")? '' : ' ' . $width . '" x ';
						$dimension .= ($depth=="")? '' : ' ' . $depth . '"';
						
						if ($row["bwall"] != ""){
							$wall_str = $row["bwall"] . "ply";
						}	
					}
				?>				
				
				<div class="right-products">
					<div class="productinfo-section">
					<div class="productinfo-main product-box-shadow">
						<h2 class="prod-title"><? echo $idTitle . ": ". $invId; ?></h2>
						<div class="mob_container">
						<div class="prod-info">

							<?
							if (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
							?>
							<ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span><br><br>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										echo $dimension;
									?>
									</span><br><br>
								</li>
								<li>
									<label for="Span2">Walls Thick</label>
									<span id="Span2"><? echo $wall_str;?></span><br><br>
								</li>
								 <li>
									<label for="Span3">Shape</label>
									<span id="Span3"><?php echo $shape; ?></span><br><br>
								</li>
								 <li>
									<label for="Span4">Top</label>
									<span id="Span4"><? echo $top;?></span><br><br>
								</li>
								 <li>
									<label for="Span5">Bottom</label>
									<span id="Span5"><? echo $bottom;?></span><br><br>
								</li>
								 <li>
									<label for="Span8">Vents</label>
									<span id="Span8"><?=($b2b_no_of_vents=="0")? '0' : $b2b_no_of_vents;?></span><br><br>
								</li>
								 <li>
									<label for="Span9">Pallet Qty</label>
									<span id="Span9"><?=($row["bpallet_qty"]=="0")? '0' : number_format($row["bpallet_qty"]);?><br></span><br><br>
								</li>
								 <li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span><br><br>
								</li>
								 <li>
									<label for="Span10">Frequency
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
										</div>	
									</label>
									<span id="Span10">
										<?=($row["expected_loads_per_mo"]!="0" && $row["boxes_per_trailer"]!="0")? number_format($row["expected_loads_per_mo"]*$row["boxes_per_trailer"]) . "/mo" : '0/mo';?><br></span><br><br>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span><br><br>
								</li>
								<!--<li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span><br><br>
								</li> -->
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span><br><br>
								</li>
								
							</ol>
							<?
							}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){
							?>
							<ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span><br><br>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										echo $dimension;
									?>
									</span><br><br>
								</li>
								<li>
									<label for="Span2">Walls Thick</label>
									<span id="Span2"><? echo $wall_str;?></span><br><br>
								</li>
								 <li>
								 	<? if($rowb2b['burst'] == 'ECT'){ ?>
								 		<label for="Span3">ECT</label>
										<span id="Span3"><? echo $rowb2b['ect_val']; ?></span>
								 	<? }else { ?>
								 		<label for="Span3">BURST</label>
										<span id="Span3"><? echo $rowb2b['burst_val']; ?></span>
								 	<? } ?>
									<br><br>
								</li>
								 <li>
									<label for="Span4">Top</label>
									<span id="Span4"><? echo $top;?></span><br><br>
								</li>
								 <li>
									<label for="Span5">Bottom</label>
									<span id="Span5"><? echo $bottom;?></span><br><br>
								</li>
								 <li>
									<label for="Span8">Vents</label>
									<span id="Span8"><?=($b2b_no_of_vents=="0")? '0' : $b2b_no_of_vents;?></span><br><br>
								</li>
								 <li>
									<label for="Span9">Pallet Qty</label>
									<span id="Span9"><?=($row["bpallet_qty"]=="0")? '0' : number_format($row["bpallet_qty"]);?><br></span><br><br>
								</li>
								 <li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span>
									<br><br>
								</li>
								 <li>
									<label for="Span10">Frequency
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
										</div>	
									</label>
									<span id="Span10"><?=($row["expected_loads_per_mo"]!="0" && $row["boxes_per_trailer"]!="0")? number_format($row["expected_loads_per_mo"]*$row["boxes_per_trailer"]) . "/mo" : '0/mo';?><br></span><br><br>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span><br><br>
								</li>
								<!-- <li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span><br><br>
								</li> -->
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span><br><br>
								</li>
								
							</ol>
							<?
							}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
							?>
							<ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span><br><br>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										echo $dimension;
									?>
									</span><br><br>
								</li>
								 <li>
									<label for="Span4">Top</label>
									<span id="Span4"><? echo $top;?></span><br><br>
								</li>
								 <li>
									<label for="Span5">Bottom</label>
									<span id="Span5"><? echo $bottom;?></span><br><br>
								</li>
								 <li>
									<label for="Span9">Bale Qty </label>
									<span id="Span9"><?=($row["bpallet_qty"]=="0")? '0' : number_format($row["bpallet_qty"]);?><br></span><br><br>
								</li>
								 <li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span>
									<br><br>
								</li>
								 <li>
									<label for="Span10">Frequency
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
										</div>	
									</label>
									<span id="Span10"><?=($row["expected_loads_per_mo"]!="0" && $row["boxes_per_trailer"]!="0")? number_format($row["expected_loads_per_mo"]*$row["boxes_per_trailer"]) . "/mo" : '0/mo';?><br></span><br><br>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span><br><br>
								</li>
								<!-- <li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span><br><br>
								</li> -->
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span><br><br>
								</li>
								
							</ol>
							<?
							}elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
							?>
							<ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span><br><br>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										echo $dimension;
									?>
									</span>
								</li>
								<li>
									<label for="Span2">Grade</label>
									<span id="Span2"><? echo $row['grade']; ?></span><br><br>
								</li>
								 
								<li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span>
									<br><br>
								</li>
								 <li>
									<label for="Span10">Frequency
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
										</div>	
									</label>
									<span id="Span10"><?=($row["expected_loads_per_mo"]!="0" && $row["boxes_per_trailer"]!="0")? number_format($row["expected_loads_per_mo"]*$row["boxes_per_trailer"]) . "/mo" : '0/mo';?><br></span><br><br>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span>
									<br><br>
								</li>
								<!-- <li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span>
									<br><br>
								</li> -->
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span>
									<br><br>
								</li>
								
							</ol>
							<?
							}elseif ( in_array(strtolower(trim($box_type)), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " "))) || ( strtolower(trim($box_type)) == strtolower(trim('Recycling')) ) ) { 
							?>
							<ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span><br><br>
								</li>
								<li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span>
									<br><br>
								</li>
								 <li>
									<label for="Span10">Frequency
										<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
										</div>	
									</label>
									<span id="Span10"><?=($row["expected_loads_per_mo"]!="0" && $row["boxes_per_trailer"]!="0")? number_format($row["expected_loads_per_mo"]*$row["boxes_per_trailer"]) . "/mo" : '0/mo';?><br></span><br><br>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span><br><br>
								</li>
								<!-- <li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span><br><br>
								</li> -->
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span><br><br>
								</li>
								
							</ol>
							<?
							}
							?>

							<!-- <ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										echo $dimension;
									?>
									</span>
								</li>
								<li>
									<label for="Span2">Walls Thick</label>
									<span id="Span2"><? if ($row["bwall"] != "") { echo $row["bwall"] . "ply"; }?></span>
								</li>
								 <li>
									<label for="Span3">Shape</label>
									<span id="Span3"><?php echo $shape; ?></span>
								</li>
								 <li>
									<label for="Span4">Top</label>
									<span id="Span4"><? echo $top;?></span>
								</li>
								 <li>
									<label for="Span5">Bottom</label>
									<span id="Span5"><? echo $bottom;?></span>
								</li>
								 <li>
									<label for="Span8">Vents</label>
									<span id="Span8"><?=($b2b_no_of_vents=="0")? '0' : $b2b_no_of_vents;?></span>
								</li>
								 <li>
									<label for="Span9">Pallet Qty</label>
									<span id="Span9"><?=($row["bpallet_qty"]=="0")? '0' : number_format($row["bpallet_qty"]);?><br></span>
								</li>
								 <li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : number_format($row["boxes_per_trailer"]);?><br></span>
								</li>
								<li>                    
									<label for="Span11">Previous Use</label>
									<span id="Span11"><?=$row["previous_contents"];?><br></span>
								</li>
								<li>                    
									<label for="Span12">Ideal Uses</label>
									<span id="Span12"><?=$row["ideal_uses"];?><br></span>
								</li>
								<li>                    
									<label for="Span13">Notes</label>
									<span id="Span13"><?=(empty($row["flyer_notes"]))? 'None' : $row["flyer_notes"];?></span>
								</li>
								
							</ol> -->
							<!-- 
							<div class="more-btn-padding">
								<button class="more-info-btn moreslide_right" onclick="javascript: show_moreinfo();">
								More Info
								</button>
							</div>
							-->
						</div>
						<div class="prod-gallery">
							<section id="detail">
							<div class="container">
								<div class="row">
									<div class="main_img">
									<?  $imgpath = "https://loops.usedcardboardboxes.com/boxpics/";
										$imgpath_internal = "../ucbloop/boxpics/";
									?>
									<div class="product-images demo-gallery">
									<div class="main-img-slider">
										<? if (@getimagesize($imgpath_internal.$row["bpic_1"])){?>
											<a data-fancybox="gallery" href="<?=$imgpath.$row["bpic_1"]?>" data-width="2048" data-height="1365">
												<img src="<?=$imgpath.$row["bpic_1"]?>" class="img-fluid">
											</a>
										<? }else{
												if ($row["bpic_2"] == "" && $row["bpic_3"] == "" && $row["bpic_4"] == ""){
												?>
													<img src="<?=$imgpath."Base-image-blank.jpg"?>" class="img-fluid">
												<?}
											}
										?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_2"])){?>
											<a data-fancybox="gallery" href="<?=$imgpath.$row["bpic_2"]?>" data-width="2048" data-height="1365">
												<img src="<?=$imgpath.$row["bpic_2"]?>" class="img-fluid">
											</a>
										<? }?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_3"])){?>
											<a data-fancybox="gallery" href="<?=$imgpath.$row["bpic_3"]?>" data-width="2048" data-height="1365">
												<img src="<?=$imgpath.$row["bpic_3"]?>" class="img-fluid">
											</a>
										<? }?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_4"])){?>
											<a data-fancybox="gallery" href="<?=$imgpath.$row["bpic_4"]?>" data-width="2048" data-height="1365">
												<img src="<?=$imgpath.$row["bpic_4"]?>" class="img-fluid">
											</a>
										<? }?>
										<? /* if (@getimagesize($imgpath_internal.$row["bpic_5"])){?>
											<a data-fancybox="gallery" href="<?=$imgpath.$row["bpic_5"]?>" data-width="2048" data-height="1365">
												<img src="<?=$imgpath.$row["bpic_5"]?>" class="img-fluid">
											</a>
										<? } */?>
									</div>

									<ul class="thumb-nav">
										<? if (@getimagesize($imgpath_internal.$row["bpic_1"])){?>
											<li><img src="<?=$imgpath.$row["bpic_1"]?>" class="prod-pics-thumb"></li>
										<? }?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_2"])){?>
											<li><img src="<?=$imgpath.$row["bpic_2"]?>" class="prod-pics-thumb"></li>
										<? }?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_3"])){?>
											<li><img src="<?=$imgpath.$row["bpic_3"]?>" class="prod-pics-thumb"></li>
										<? }?>
										<? if (@getimagesize($imgpath_internal.$row["bpic_4"])){?>
											<li><img src="<?=$imgpath.$row["bpic_4"]?>" class="prod-pics-thumb"></li>
										<? }?>
										<? /* if (@getimagesize($imgpath_internal.$row["bpic_5"])){?>
											<li><img src="<?=$imgpath.$row["bpic_5"]?>" class="prod-pics-thumb"></li>
										<? } */?>
									</ul>

									</div>

									</div>
								</div>
							</div>
							</section>
							<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>	
							<script src="product-slider/js/popper.min.js"></script>
							<!--<script src="product-slider/js/slick.min.js"></script>-->
							<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
							<script src="product-slider/js/jquery.fancybox.min.js"></script>
							<script id="rendered-js">
								/*--------------*/
								// Main/Product image slider for product page
								$('#detail .main-img-slider').slick({
								  slidesToShow: 1,
								  slidesToScroll: 1,
								  infinite: true,
								  arrows: false,
								  fade: true,
								  autoplay: false,
								  /*
								  autoplaySpeed: 4000,
								  speed: 300,
								  lazyLoad: 'ondemand',
								  asNavFor: '.thumb-nav',
								  prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable"><</span></div>',
								  nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">></span></div>'
								  */
								  });

								// Thumbnail/alternates slider for product page
								$('.thumb-nav').slick({
								  slidesToShow: 4,
								  slidesToScroll: 1,
								  infinite: true,
								  centerPadding: '0px',
								  asNavFor: '.main-img-slider',
								  dots: false,
								  centerMode: false,
								  draggable: true,
								  speed: 200,
								  focusOnSelect: true,
								  prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable"><</span></div>',
								  nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">></span></div>' });


								/*
								//keeps thumbnails active when changing main image, via mouse/touch drag/swipe
								$('.main-img-slider').on('afterChange', function (event, slick, currentSlide, nextSlide) {
								  //remove all active class
								  $('.thumb-nav .slick-slide').removeClass('slick-current');
								  //set active class for current slide
								  $('.thumb-nav .slick-slide:not(.slick-cloned)').eq(currentSlide).addClass('slick-current');
								});
								//# sourceURL=pen.js
								*/
								
								$('[data-fancybox="gallery"]').fancybox({
								  afterLoad : function(instance, current) {
									var pixelRatio = window.devicePixelRatio || 1;

									if ( pixelRatio > 1.5 ) {
									  current.width  = current.width  / pixelRatio;
									  current.height = current.height / pixelRatio;
									}
								  }
								});
								
							</script>
						</div>
						</div>
					</div>	
				</div>
				</div>
			</div>
			<!---->
			<div class="privacy-links_inner" style="margin-left: 30px;">
				<div class="bottomlinks">
					<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div>
					<div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div>
					<div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div>
					<div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
				</div>
			</div>
			</div>
		</div>
		
	</div>
	<div class="footer_l">
		<div class="copytxt">© UsedCardboardBoxes</div>
	</div>
	
<?
	if ($ship_ltl == 0){
?>
	<script>
		productinfo(1);
		
	</script>
<?}

if ($product_name_id == 1){ 
?>
	<script>
		productinfo(1);
	</script>
<?
}	
if ($product_name_id == 2){ 
?>
	<script>
		productinfo(2);
	</script>
<?
}	

if ($product_name_id == 3){ 
?>
	<script>
		productinfo(3);
	</script>
<?
	
}	
if ($product_name_id == 4){ 

?>
	<script>
		productinfo(4);
	</script>
<?
}	
?>
	<script>
		//calculatedistance_ip('<? echo $ipadd;?>', <? echo $_REQUEST['id'];?>);		
		//calculatedistance_ip('38.73.241.221', <? echo $_REQUEST['id'];?>);		
	
		if (window.innerWidth <= 980) {
		  function isVisible($el) {
			let docViewTop = $(window).scrollTop();
			let docViewBottom = docViewTop + $(window).height();
			let elTop = $el.offset().top;
			let elBottom = elTop + $el.height();
			return((elBottom <= docViewBottom) && (elTop >= docViewTop));
		  }
		  $(function() {
			$(window).scroll(function() {
			 
					if(isVisible($("#order-div"))){
						$('#ordernow').fadeOut();
					}
					else{
						$('#ordernow').fadeIn();
					}
			  });
		  });
		}
		
    </script>
	
	<? if ($_REQUEST["checkout"] == 1) { ?>
		<script>
			document.getElementById("orderfrm").submit(); 
		</script>
	<? } ?>
</body>
</html>