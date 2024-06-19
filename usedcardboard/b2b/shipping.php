<?
session_start();
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

//ini_set("display_errors", "1");
//error_reporting(E_ALL);

require_once("cal_functions.php");

//$orderData = $_SESSION['orderData'];
$orderData['productName'] 		= $_REQUEST["productQntype"];
$orderData['productQntypeid'] 	= $_REQUEST["productQntypeid"];
$orderData['productQnt'] 		= $_REQUEST["productQnt"];
$orderData['productUnitPr'] 	= $_REQUEST["productQntprice"];
$orderData['productTotal'] 		= trim($_REQUEST["productTotal"]);
$orderData['hdAvailability'] 	= $_REQUEST["hdAvailability"];
$quoteTotal = 0;

if(isset($_REQUEST['id'])){
	$ProductLoopId = decrypt_password($_REQUEST["id"]);
	$orderData['hdAvailability'] = $_SESSION['hdAvailability'];
}else{
	$ProductLoopId = $_REQUEST["productIdloop"];
	$orderData['hdnLastInsertId'] 	= $_REQUEST["hdnLastInsertId"];
}
$orderData['ProductLoopId'] 	= $ProductLoopId;

if(isset($_REQUEST['compnewid'])){
	$compnewid = decrypt_password($_REQUEST['compnewid']);
	$_SESSION['compnewid'] = $compnewid;
}

$sessionId = session_id();
//echo "compid-".$_SESSION['compnewid'];
if(isset($_SESSION['compnewid']) && !empty($_SESSION['compnewid']) ){
	//SELECT shipContact, shipAddress, shipAddress2, shipMobileno, shipCity, shipState, shipZip, shipemail, shipPhone, shipto_main_line_ph, shipto_main_line_ph_ext, shipping_receiving_hours, company FROM companyInfo WHERE ID = ".$rowCompID['companyID']
	$getCompInfo = db_query("SELECT * FROM companyInfo WHERE ID = ".$_SESSION['compnewid'], db_b2b());
	$rowCompInfo = array_shift($getCompInfo);
	//echo "<pre>rowCompInfo - "; print_r($rowCompInfo);echo "</pre>";
	$arrContact = "";
	if(!empty($rowCompInfo['shipContact'])){
		if (strpos($rowCompInfo['shipContact'], " ") > 0) {		
			$arrContact = explode(" ", $rowCompInfo['shipContact']);
			$shipInfoFNm 	= current($arrContact) ?? current($arrContact);
			$shipInfoLNm 	= end($arrContact) ?? end($arrContact);
		}else{
			$shipInfoFNm 	= $rowCompInfo['shipContact'];
		}	
	}	
	if(!empty($rowCompInfo['contact'])){
			$arrContact = explode(" ", $rowCompInfo['contact']);
		}
		/*Initialize the variable & set value*/
		$cntInfoFNm_n 	= current($arrContact) ?? current($arrContact);
		$cntInfoLNm_n 	= end($arrContact) ?? end($arrContact);

	/*Initialize the variable & set value*/
	//echo "<br /> shipInfoFNm - ".$shipInfoFNm." / shipInfoLNm - ".$shipInfoLNm;
	$dock_hour 		= $rowCompInfo['shipping_receiving_hours'];		
	$cntInfoShipCompny 	= $rowCompInfo['company'];		
	$shipAddress	= $rowCompInfo['shipAddress'] ?? $rowCompInfo['shipAddress'];
	$shipAddress2 	= $rowCompInfo['shipAddress2'] ?? $rowCompInfo['shipAddress2'];
	$shipCity 	 	= $rowCompInfo['shipCity'] ?? $rowCompInfo['shipCity'];
	$shipState  	= $rowCompInfo['shipState'] ?? $rowCompInfo['shipState'];
	$shipZip 	 	= $rowCompInfo['shipZip'] ?? $rowCompInfo['shipZip'];
	$shipEmail 	 	= $rowCompInfo['shipemail'] ?? $rowCompInfo['shipemail'];

	/*if (strpos($shipEmail, ",") > 0) {
		$shipEmail = substr($shipEmail, 0 , strpos($shipEmail, ","));
	}
	if (strpos($shipEmail, ";") > 0) {
		$shipEmail = substr($shipEmail, 0 , strpos($shipEmail, ";"));
	}*/
	
	if ($rowCompInfo['shipPhone'] == ""){
		if ($rowCompInfo['shipto_main_line_ph'] != ""){
			if ($rowCompInfo['shipto_main_line_ph_ext'] != ""){
				$shipPhone 	= $rowCompInfo['shipto_main_line_ph'] . " x" . $rowCompInfo['shipto_main_line_ph_ext'];
			}else{
				$shipPhone 	= $rowCompInfo['shipto_main_line_ph'];
			}
		}else{
			$shipPhone 	= $rowCompInfo['shipMobileno'];
		}
	}else{
		$shipPhone 	= $rowCompInfo['shipPhone'] ?? $rowCompInfo['shipPhone'];
	}
	$cntInfoCompny 	= $rowCompInfo['company'] ?? $rowCompInfo['company'];
	$cntInfoEmail 	= $rowCompInfo['shipemail'] ?? $rowCompInfo['shipemail'];

	$cntInfoFNm 	= $shipInfoFNm;
	$cntInfoLNm 	= $shipInfoLNm;
	$cntInfoCompny 	= $cntInfoCompny;
	$cntInfoEmail 	= $cntInfoEmail;
	$cntInfoPhn 	= $shipPhone;

	$cntInfoAdd1 	= $shipAddress;
	$cntInfoAdd2 	= $shipAddress2;
	$cntInfoCity 	= $shipCity;
	$cntInfoState 	= strtoupper($shipState);
	$cntInfoZip 	= $shipZip;

	$orderData['cntInfoFNm'] 	= $shipInfoFNm;
	$orderData['cntInfoLNm'] 	= $shipInfoLNm;
	$orderData['cntInfoCompny']	= $cntInfoCompny;
	$orderData['cntInfoEmail'] 	= $cntInfoEmail;
	$orderData['cntInfoPhn']	= $shipPhone;

	$orderData['shipping_add1']	= $shipAddress;
	$orderData['shipping_add2']	= $shipAddress2;
	$orderData['shipping_city']	= $shipCity;
	$orderData['shipping_state']= $shipState;
	$orderData['shipping_zip']  = $shipZip;

	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE shipping_add1 <> '' and session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db());
	$rec_found = "no";
	while ($rowSessionDt = array_shift($getSessionDt)) {
		$orderData['shipping_add1'] = $rowSessionDt['shipping_add1'];
		$orderData['shipping_add2'] = $rowSessionDt['shipping_add2'];
		$orderData['shipping_city'] = $rowSessionDt['shipping_city'];
		$orderData['shipping_state'] = $rowSessionDt['shipping_state'];
		$orderData['shipping_zip'] = $rowSessionDt['shipping_zip'];
		$quoteTotal = $rowSessionDt['quote_total'];
		//
		$orderData['productName'] 		= $rowSessionDt["product_name"];
		$orderData['productQntypeid'] 	= $rowSessionDt["product_name_id"];
		$orderData['productQnt'] 		= $rowSessionDt["product_qty"];
		$orderData['productUnitPr'] 	= $rowSessionDt["product_unitprice"];
		$orderData['productTotal'] 		= trim($rowSessionDt["product_total"]);
		//
	}
		
	$orderData['dock_hour']  = $dock_hour;


	$_SESSION['shippingcompnewid'] = $_SESSION['contactcompnewid'];
	unset($_SESSION['contactcompnewid']);

}else{
	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db() );

	while ($rowContactInfo = array_shift($getSessionDt)) {
		$cntInfoFNm 	= $rowContactInfo['contact_firstname'];
		$cntInfoLNm 	= $rowContactInfo['contact_lastname'];
		$cntInfoCompny 	= $rowContactInfo['contact_company'];
		$cntInfoEmail 	= $rowContactInfo['contact_email'];
		$cntInfoPhn 	= $rowContactInfo['contact_phone'];
		$quoteTotal     = $rowContactInfo['quote_total'];

		$cntInfoAdd1 	= $rowContactInfo['shipping_add1'];
		$cntInfoAdd2 	= $rowContactInfo['shipping_add2'];
		$cntInfoCity 	= $rowContactInfo['shipping_city'];
		$cntInfoState 	= strtoupper($rowContactInfo['shipping_state']);
		$cntInfoZip 	= $rowContactInfo['shipping_zip'];

		$orderData['cntInfoFNm'] 	= $rowContactInfo['contact_firstname'];
		$orderData['cntInfoLNm'] 	= $rowContactInfo['contact_lastname'];
		$orderData['cntInfoCompny']	= $rowContactInfo['contact_company'];
		$orderData['cntInfoEmail'] 	= $rowContactInfo['contact_email'];
		$orderData['cntInfoPhn']	= $rowContactInfo['contact_phone'];

		$orderData['shipping_add1']	= $rowContactInfo['shipping_add1'];
		$orderData['shipping_add2']	= $rowContactInfo['shipping_add2'];
		$orderData['shipping_city']	= $rowContactInfo['shipping_city'];
		$orderData['shipping_state']= $rowContactInfo['shipping_state'];
		$orderData['shipping_zip']  = $rowContactInfo['shipping_zip'];

		$orderData['productName'] 		= $rowContactInfo["product_name"];
		$orderData['productQntypeid'] 	= $rowContactInfo["product_name_id"];
		$orderData['productQnt'] 		= $rowContactInfo["product_qty"];
		$orderData['productUnitPr'] 	= $rowContactInfo["product_unitprice"];
		$orderData['productTotal'] 		= trim($rowContactInfo["product_total"]);
	}
}
//echo "nyn<pre>"; print_r($orderData); echo "</pre>";
	
$qry_loopbox = "Select * FROM loop_boxes WHERE id = '" . $ProductLoopId . "'";		
$res_loopbox = db_query($qry_loopbox, db() );		
$row_loopbox = array_shift($res_loopbox);
$id2 = $row_loopbox["b2b_id"];	
$invId = $id2;

$qryb2b = "Select * FROM inventory WHERE id = '" . $id2 . "'";		
$resb2b = db_query($qryb2b, db_b2b() );		
$rowb2b = array_shift($resb2b);

$box_type = $rowb2b["box_type"];
$box_warehouse_id = $row_loopbox["box_warehouse_id"];  //!=238

	$shipfrom_state = ""; $shipfrom_city = ''; $territory_country = "";
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
		$territory_country = "Canada";
	} 
	elseif(in_array($shipfrom_state, $canada_west, TRUE))
	{ 
		$territory_country = "Canada";
	} 
	elseif(in_array($shipfrom_state, $canada, TRUE))
	{ 
		$territory_country = "Canada";
	} 
									
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

$_SESSION['hdAvailability'] = $orderData['hdAvailability'];

?>
<!doctype html>
<html  dir="ltr" class="js windows firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity placeholder no-touchevents displaytable display-table generatedcontent cssanimations flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">
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
	
	<script src="https://code.jquery.com/jquery-3.4.0.min.js" type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" href="CSS/payment.css">

	<script type="text/javascript" src="js/custom.js"></script>	

<script type="text/javascript">
function chkPgLoad(){
	var chkStats = document.getElementById("chkContactInfoSame").checked;
	//alert('chkPgLoad cb val => '+chkStats  );
	var cntInfoFNm 		= "<? if(!empty($orderData['cntInfoFNm'])){ echo $orderData['cntInfoFNm']; } ?>";
	var cntInfoLNm 		= "<? if(!empty($orderData['cntInfoLNm'])){  echo $orderData['cntInfoLNm']; } ?>";
	var cntInfoCompny 	= "<? if(!empty($orderData['cntInfoCompny'])){ echo $orderData['cntInfoCompny'];}?>";
	var cntInfoEmail 	= "<? if(!empty($orderData['cntInfoEmail'])){ echo $orderData['cntInfoEmail']; } ?>";
	var cntInfoPhn 		= "<? if(!empty($orderData['cntInfoPhn'])){ echo $orderData['cntInfoPhn']; } ?>";

	var cntInfoAdd1 	= "<? if(!empty($orderData['shipping_add1'])){ echo $orderData['shipping_add1']; } ?>";
	var cntInfoAdd2 	= "<? if(!empty($orderData['shipping_add2'])){ echo $orderData['shipping_add2']; } ?>";
	var cntInfoCity 	= "<? if(!empty($orderData['shipping_city'])){ echo $orderData['shipping_city']; } ?>";
	var cntInfoState 	= "<? if(!empty($orderData['shipping_state'])){ echo strtoupper($orderData['shipping_state']); } ?>";
	var cntInfoZip 		= "<? if(!empty($orderData['shipping_zip'])){ echo $orderData['shipping_zip']; } ?>";

	if( chkStats == 1 ){ 
		if(cntInfoFNm != ''){
			document.getElementById("txtshippingaddFNm").value 			= cntInfoFNm;
		//	document.getElementById("txtshippingaddFNm").readOnly 		= true;
		}
		if(cntInfoLNm != ''){
			document.getElementById("txtshippingaddLNm").value 			= cntInfoLNm;
			//document.getElementById("txtshippingaddLNm").readOnly 		= true;
		}
		if(cntInfoCompny != ''){
			document.getElementById("txtshippingaddCompny").value 		= cntInfoCompny 
			//document.getElementById("txtshippingaddCompny").readOnly 	= true;
		}
		if(cntInfoEmail != ''){
			document.getElementById("txtshippingaddEmail").value 		= cntInfoEmail;
			//document.getElementById("txtshippingaddEmail").readOnly 	= true;
		}
		if(cntInfoPhn != ''){
			document.getElementById("txtshippingaddPhone").value 		= cntInfoPhn
			//document.getElementById("txtshippingaddPhone").readOnly 	= true;
		}		

		if(cntInfoAdd1 != ''){
			document.getElementById("txtshippingAdd1").value 		= cntInfoAdd1
			//document.getElementById("txtshippingAdd1").readOnly 	= true;
		}		
		if(cntInfoAdd2 != ''){
			document.getElementById("txtshippingAdd2").value 		= cntInfoAdd2
			//document.getElementById("txtshippingAdd2").readOnly 	= true;
		}		
		if(cntInfoCity != ''){
			document.getElementById("txtshippingaddCity").value 		= cntInfoCity
			//document.getElementById("txtshippingaddCity").readOnly 	= true;
		}		
		if(cntInfoState != ''){
			document.getElementById("txtshippingaddState").value 		= cntInfoState
			//document.getElementById("txtshippingaddState").readOnly 	= true;
		}		
		if(cntInfoZip != ''){
			document.getElementById("txtshippingaddZip").value 		= cntInfoZip
			//document.getElementById("txtshippingaddZip").readOnly 	= true;
		}		
	}
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

function chkstatus(){ 
	var chkStats = document.getElementById("chkContactInfoSame").checked;
	var cntInfoFNm = "<? if(!empty($orderData['cntInfoFNm'])){ echo $orderData['cntInfoFNm']; } ?>";
	var cntInfoLNm = "<? if(!empty($orderData['cntInfoLNm'])){  echo $orderData['cntInfoLNm']; } ?>";
	var cntInfoCompny = "<? if(!empty($orderData['cntInfoCompny'])){ echo $orderData['cntInfoCompny']; } ?>";
	var cntInfoEmail = "<? if(!empty($orderData['cntInfoEmail'])){ echo $orderData['cntInfoEmail']; } ?>";
	var cntInfoPhn = "<? if(!empty($orderData['cntInfoPhn'])){ echo $orderData['cntInfoPhn']; } ?>";
	
	var cntInfoAdd1 	= "<? if(!empty($orderData['shipping_add1'])){ echo $orderData['shipping_add1']; } ?>";
	var cntInfoAdd2 	= "<? if(!empty($orderData['shipping_add2'])){ echo $orderData['shipping_add2']; } ?>";
	var cntInfoCity 	= "<? if(!empty($orderData['shipping_city'])){ echo $orderData['shipping_city']; } ?>";
	var cntInfoState 	= "<? if(!empty($orderData['shipping_state'])){ echo strtoupper($orderData['shipping_state']); } ?>";
	var cntInfoZip 		= "<? if(!empty($orderData['shipping_zip'])){ echo $orderData['shipping_zip']; } ?>";
	var dock_hour 		= "<? if(!empty($orderData['dock_hour'])){ echo $orderData['dock_hour']; }else{ echo ''; } ?>";

	if( chkStats == 1 ){ 
		if(cntInfoFNm != ''){
			document.getElementById("txtshippingaddFNm").value 			= cntInfoFNm;
			//document.getElementById("txtshippingaddFNm").readOnly 		= true;
		}
		if(cntInfoLNm != ''){
			document.getElementById("txtshippingaddLNm").value 			= cntInfoLNm;
			//document.getElementById("txtshippingaddLNm").readOnly 		= true;
		}
		if(cntInfoCompny != ''){
			document.getElementById("txtshippingaddCompny").value 		= cntInfoCompny 
			//document.getElementById("txtshippingaddCompny").readOnly 	= true;
		}
		if(cntInfoEmail != ''){
			document.getElementById("txtshippingaddEmail").value 		= cntInfoEmail;
			//document.getElementById("txtshippingaddEmail").readOnly 	= true;
		}
		if(cntInfoPhn != ''){
			document.getElementById("txtshippingaddPhone").value 		= cntInfoPhn
			//document.getElementById("txtshippingaddPhone").readOnly 	= true;
		}
		
		if(cntInfoAdd1 != ''){
			document.getElementById("txtshippingAdd1").value 		= cntInfoAdd1
			//document.getElementById("txtshippingAdd1").readOnly 	= true;
		}		
		if(cntInfoAdd2 != ''){
			document.getElementById("txtshippingAdd2").value 		= cntInfoAdd2
			//document.getElementById("txtshippingAdd2").readOnly 	= true;
		}		
		if(cntInfoCity != ''){
			document.getElementById("txtshippingaddCity").value 		= cntInfoCity
			//document.getElementById("txtshippingaddCity").readOnly 	= true;
		}		
		if(cntInfoState != ''){
			document.getElementById("txtshippingaddState").value 		= cntInfoState
			//document.getElementById("txtshippingaddState").readOnly 	= true;
		}		
		if(cntInfoZip != ''){
			document.getElementById("txtshippingaddZip").value 		= cntInfoZip
			//document.getElementById("txtshippingaddZip").readOnly 	= true;
		}		
		if(dock_hour != ''){
			document.getElementById("txtshippingaddDockhrs").value 		= dock_hour
		}		
		
	}else{ 
		document.getElementById("txtshippingaddFNm").value 		= "";
		document.getElementById("txtshippingaddLNm").value 		= "";
		document.getElementById("txtshippingaddCompny").value 	= "";
		document.getElementById("txtshippingaddEmail").value 	= "";
		document.getElementById("txtshippingaddPhone").value 	= "";
		document.getElementById("txtshippingaddFNm").placeholder	= "First name";
		document.getElementById("txtshippingaddLNm").placeholder 	= "Last name";
		document.getElementById("txtshippingaddCompny").placeholder = "Company (optional)";
		document.getElementById("txtshippingaddEmail").placeholder 	= "Email (for sheduling delivery appointment)";
		document.getElementById("txtshippingaddPhone").placeholder 	= "Phone (for scheduling delivery appointment)";
		document.getElementById("txtshippingaddFNm").readOnly 		= false;
		document.getElementById("txtshippingaddLNm").readOnly 		= false;
		document.getElementById("txtshippingaddCompny").readOnly 	= false;
		document.getElementById("txtshippingaddEmail").readOnly 	= false;
		document.getElementById("txtshippingaddPhone").readOnly 	= false;
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
	/*payment pg js ends*/
function chkstatus1(){
	var chkStats = document.getElementById("chkContactInfoSame1").checked;
	var cntInfoFNm = "<? if(!empty($orderData['cntInfoFNm'])){ echo $orderData['cntInfoFNm']; } ?>";
	var cntInfoLNm = "<? if(!empty($orderData['cntInfoLNm'])){  echo $orderData['cntInfoLNm']; } ?>";
	var cntInfoCompny = "<? if(!empty($orderData['cntInfoCompny'])){ echo $orderData['cntInfoCompny']; }?>";
	var cntInfoEmail = "<? if(!empty($orderData['cntInfoEmail'])){ echo $orderData['cntInfoEmail']; } ?>";
	var cntInfoPhn = "<? if(!empty($orderData['cntInfoPhn'])){ echo $orderData['cntInfoPhn']; } ?>";
	if( chkStats == 1 ){ 
		if(cntInfoFNm != ''){
			document.getElementById("txtbillingaddFNm").value 		= cntInfoFNm;
		}
		if(cntInfoLNm != ''){
			document.getElementById("txtbillingaddLNm").value 		= cntInfoLNm;
		}
		if(cntInfoCompny != ''){
			document.getElementById("txtbillingAddCompny").value 	= cntInfoCompny; 
		}
		if(cntInfoEmail != ''){
			document.getElementById("billingAddEmail").value 		= cntInfoEmail;
		}
		if(cntInfoPhn != ''){
			document.getElementById("billingAddPhn").value 		= cntInfoPhn;
		}
	}else{
		document.getElementById("txtbillingaddFNm").value 		= "";
		document.getElementById("txtbillingaddLNm").value 		= "";
		document.getElementById("txtbillingAddCompny").value 	= ""; 
		document.getElementById("billingAddEmail").value 		= "";
		document.getElementById("billingAddPhn").value 			= "";
	}

}

function chngPickupDisp(strVal){
	if(strVal == 'Customer Pickup'){
		$('#frmTxtShipping').addClass('display_none');
		$('.frm-term-text-ucbpickup').addClass('display_none');
		$('.frm-term-text-custpickup').removeClass('display_none');
		$('#shipping_quote').addClass('display_none');

		$("#final_val_div").html(document.getElementById("productTotal_val").value);
		
		//$('#errMsg3').removeClass('display_none');
		
		document.getElementById("errMsg3").style.display = "block";
		
		//$('#div_shipping_sel').addClass('display_none');
		//$('#div_shipping_sel1').addClass('display_none');
		$('#shippingAddSection').addClass('display_none');
		//$('#rdoSameBillingAdd').removeAttr('checked');
		//document.getElementById("rdoDiffBillingAdd").checked = true;
		//$("#billingAddSection").removeClass('display_none');
		//$('#billingExtraFields').removeClass('display_none');
		//$('#custPickedAdding').html('<div class="frm-txt-check"><input id="chkContactInfoSame1" type="checkbox" name="chkContactInfoSame1" value="1" checked="checked" onchange="chkstatus1()"><label for="chkContactInfoSame1"><span></span>Same as Contact Info</label></div>');
		
		/*var cntInfoFNm = "<? if(!empty($orderData['cntInfoFNm'])){ echo $orderData['cntInfoFNm']; } ?>";
		var cntInfoLNm = "<? if(!empty($orderData['cntInfoLNm'])){  echo $orderData['cntInfoLNm']; } ?>";
		var cntInfoCompny = "<? if(!empty($orderData['cntInfoCompny'])){ echo $orderData['cntInfoCompny']; }?>";
		var cntInfoEmail = "<? if(!empty($orderData['cntInfoEmail'])){ echo $orderData['cntInfoEmail']; } ?>";
		var cntInfoPhn = "<? if(!empty($orderData['cntInfoPhn'])){ echo $orderData['cntInfoPhn']; } ?>";

		if(cntInfoFNm != ''){
			$('#txtbillingaddFNm').val(cntInfoFNm);
		}
		if(cntInfoLNm != ''){
			$("#txtbillingaddLNm").val(cntInfoLNm);
		}
		if(cntInfoCompny != ''){
			$("#txtbillingAddCompny").val(cntInfoCompny); 
		}
		if(cntInfoEmail != ''){
			$("#billingAddEmail").val(cntInfoEmail);
		}
		if(cntInfoPhn != ''){
			$("#billingAddPhn").val(cntInfoPhn);
		}
		*/

		//$('#btnContinuePay').removeAttr("disabled");
		
	}else{
		//$('#div_shipping_sel').removeClass('display_none');
		//$('#div_shipping_sel1').removeClass('display_none');
		$('.frm-term-text-ucbpickup').removeClass('display_none');
		$('.frm-term-text-custpickup').addClass('display_none');
		//$('#errMsg3').addClass('display_none');
		document.getElementById("errMsg3").style.display = "none";
		
		$('#shipping_quote').removeClass('display_none');

		$("#final_val_div").html(document.getElementById("product_shipTotal_val").value);

		$('#frmTxtShipping').removeClass('display_none');
		$('#shippingAddSection').removeClass('display_none');
		//document.getElementById("rdoSameBillingAdd").checked = true;
		//$('#rdoDiffBillingAdd').removeAttr('checked');
		//$("#billingAddSection").addClass('display_none');
		//$('#billingExtraFields').addClass('display_none');
		//$("#billingAddEmail").val();
		//$("#billingAddPhn").val();
		//$('#custPickedAdding').html('');

		//$('#btnContinuePay').attr('disabled', true);
	}
}

</script>
<style type="text/css">
.coninuePayment{
	background: #cccccc;
	margin-top: 10px;
	padding-top: 10px;
	/*display: none;*/
	padding-bottom: 30px;
	padding-left: 10px;
	padding-right: 10px;
}
#loader {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,0.75) url(images/loader.gif) no-repeat center center;
  z-index: 10000;
}
#errMsg1, #errMsg2, #errMsg3{
	color: red;
}
.display_none{
	display: none;
}

</style>
</head>

<body onload="chkPgLoad();" >
<?php require_once('boomerange_common_header.php'); ?>
	<div class="sections">
		<div class="new_container no-top-padding">
			<div class="parentdiv">
			<div class="innerdiv">
			<div class="section-top-margin_1">
				<h1 class="section-title"><?=$pgTitle;?></h1>
				<div class="title_desc">Let us know where to deliver</div>
			</div>
			<!--Start Breadcrums-->
			<nav aria-label="Breadcrumb">
			<ol class="breadcrumb " role="list">
				<li class="breadcrumb__item breadcrumb__item--completed">
				  <a class="breadcrumb__link" href="index.php?id=<? echo urlencode(encrypt_password($ProductLoopId));?>">Select Quantity</a>					
				  <svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>

				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="contact.php?id=<? echo urlencode(encrypt_password($ProductLoopId));?>">Contact</a>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"><symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path></svg></symbol></use> </svg>
				</li>
				  <li class="breadcrumb__item breadcrumb__item--current" aria-current="step">
				  <span class="breadcrumb__text breadcrumnow">Shipping</span>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>
				  <li class="breadcrumb__item breadcrumb__item--blank">
				  <span class="breadcrumb__text">Payment</span>
				</li>
			</ol>
		  </nav>
			<!--End Breadcrums-->
			<form name="frmContinuePayment"  id="frmContinuePayment"  method="post" action="payment/index.php">	
			
			<div class="content-div content-padding ">
				<div class="left_form">
					<? $customer_pickup_allowed = $row_loopbox['customer_pickup_allowed']; 
					$customer_pickup_allowed =1;
					?>
					<div class="frm-txt"><div class="frm-txt-shipping">Shipping Type</div> 
						<div class="frm-txt-check-shipping">
							<? if($customer_pickup_allowed == 1 ) { ?>
								<input class="input-radio" data-backup="rdoPickUp" type="radio" name="rdoPickUp" id="rdoPickUp" value="UCB Delivery" checked="checked" onchange="chngPickupDisp(this.value)" >
								<label for="rdoPickUp"><span></span></label>
								<label class="content-box__emphasis" for="checkout_UCB_delivery">UCB Delivery</label><span style="margin-right: 40px;"></span>
								<input class="input-radio" data-backup="rdoPickUp" type="radio" name="rdoPickUp" id="rdoPickUp" value="Customer Pickup" onchange="chngPickupDisp(this.value)" >
								<label for="rdoPickUp"><span></span></label>
								<label class="content-box__emphasis" for="checkout_custome_pickup">Customer Pickup</label>
							<? }else{ ?>
								<input class="input-radio" data-backup="rdoPickUp" type="radio" name="rdoPickUp" id="rdoPickUp" value="UCB Delivery" checked="checked" onchange="chngPickupDisp(this.value)" >
								<label for="rdoPickUp"><span></span></label>
								<label class="content-box__emphasis" for="checkout_UCB_delivery">UCB Delivery</label>
							<? } ?>
						</div>
					</div>
					<div class="div-space"></div>
					<div class="div-space"></div>
					
					<div class="frm-txt" id="frmTxtShipping">
						<div class="frm-txt-shipping" >Shipping Address</div> 
						<div class="frm-txt-check">
							<input id="chkContactInfoSame" type="checkbox" name="chkContactInfoSame" value="1" checked="checked" onchange="chkstatus()"><label for="chkContactInfoSame"><span></span>Same as Contact Info</label>
						</div>
					</div>
					<div class="div-space"></div>
					<div class="floating-labels" id="shippingAddSection">
						<!-- <form name="frmshippingadd" id="frmshippingadd" method="post" action="#"> -->
							<div class="fieldset">
								<div class="field field--required field--half" data-address-field="first_name">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
										<input placeholder="First name" autocomplete="shipping given-name" autocorrect="off" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="txtshippingaddFNm" id="txtshippingaddFNm" value="">
									</div>
								</div>
								<div class="field field--required field--half" data-address-field="last_name">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
									  <input placeholder="Last name" autocomplete="shipping given-name" autocorrect="off" data-backup="last_name" class="field__input" aria-required="true" size="30" type="text" name="txtshippingaddLNm" id="txtshippingaddLNm">
									</div>
								</div>
								<div data-address-field="company" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_company">Company (optional)</label>
										<input placeholder="Company (optional)" autocomplete="shipping organization" autocorrect="off" data-backup="company" class="field__input" size="30" type="text" name="txtshippingaddCompny" id="txtshippingaddCompny">
									  </div>
								</div>
								<div data-address-field="add1" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_add1">Address</label>
										<input placeholder="Address" autocomplete="shipping organization" autocorrect="off" data-backup="add1" class="field__input" size="30" type="text" name="txtshippingAdd1" id="txtshippingAdd1">
									  </div>
								</div>
								<div data-address-field="add2" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_add2">Suite number (optional)</label>
										<input placeholder="Suite number (optional)" autocomplete="shipping organization" autocorrect="off" data-backup="add2" class="field__input" size="30" type="text" name="txtshippingAdd2" id="txtshippingAdd2">
									  </div>
								</div>
								<div data-address-field="city" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_city">City</label>
										<input placeholder="City" autocomplete="shipping organization" autocorrect="off" data-backup="city" class="field__input" size="30" type="text" name="txtshippingaddCity" id="txtshippingaddCity">
									  </div>
								</div>
								<div class="field field--required field--half" data-address-field="state">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_state">State</label>
									  <input placeholder="State" autocomplete="shipping given-name" autocorrect="off" data-backup="state" class="field__input input_state" aria-required="true" size="30" type="text" name="txtshippingaddState" id="txtshippingaddState">
									</div>
								</div>
								<div class="field field--required field--half" data-address-field="zip_code">
									<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_zip_code">ZIP Code</label>
									  <input placeholder="ZIP Code" autocomplete="shipping given-name" autocorrect="off" data-backup="zip_code" class="field__input" aria-required="true" size="30" type="text" name="txtshippingaddZip" id="txtshippingaddZip">
									</div>
								</div>
								<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (for sheduling delivery appointment)</label>
										<input placeholder="Email (for sheduling delivery appointment)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="txtshippingaddEmail" id="txtshippingaddEmail">
									  </div>
								</div>
								<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (for scheduling delivery appointment)</label>
										<input placeholder="Phone (123 456 7891) (for scheduling delivery appointment)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="txtshippingaddPhone" id="txtshippingaddPhone" onkeyup="addHyphen(this)" maxlength="12">
									  </div>
								</div>
								<div data-address-field="dockhrs" data-autocomplete-field-container="true" class="field field--optional">

									  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_dockhrs">Your Dock Hours (days open, open time - close time)</label>
										<input placeholder="Your Dock Hours (days open, open time - close time)" autocomplete="shipping organization" autocorrect="off" data-backup="dockhrs" class="field__input" size="30" type="text" name="txtshippingaddDockhrs" id="txtshippingaddDockhrs">
									  </div>
								</div>
								
								<div data-address-field="ship_date" data-autocomplete-field-container="true" class="field field--optional">
										  <div style="display:flex !important; position: relative;width: 100%;"><label class="field__label field__label--visible" for="checkout_shipping_address_dockhrs">Preferred Delivery Date</label>
																		  
											<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
											<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
											<script LANGUAGE="JavaScript">
												var cal1xx = new CalendarPopup("listdiv");
												cal1xx.showNavigationDropdowns();
											</script>
									
										  
											<input placeholder="Preferred Delivery Date" autocomplete="shipping organization" autocorrect="off" data-backup="ship_date" class="field__input" size="30" type="text" 
											name="txtship_date" id="txtship_date" style="width:50%;" value="" >
											
											<a href="#" style="padding-top: 10px;" onclick="cal1xx.select(document.frmContinuePayment.txtship_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx">
											<img border="0" src="images/calendar.jpg" style="position:relative;top:2px;"></a>											
											<i style="font-size: 10px; padding-top: 10px;">This selected date does not change the current lead time on the box, but helps us understand when you desire it by</i>
										  </div>
										
											<div ID="listdiv" STYLE="display:inline-block;visibility:hidden;background-color:white;layer-background-color:white;"></div>
									</div>
							</div>
							
							<div class="btn-div-shipping">
								<input type="hidden" name="hdnLastInsertId" value="<? echo $orderData['hdnLastInsertId']; ?>">
								<input type="hidden" name="hdnloopboxid" value="<? echo $orderData['ProductLoopId']; ?>">
								<? if ($quoteTotal <> 0) { ?>
									<br><span id="quoteRate">Shipping Quote = $<? echo $quoteTotal; ?></span><br>
								<? }else{ ?>
									<br><span id="quoteRate"></span><br>
								<? } ?>								
								
								<? if ($territory_country == "Canada") {?>
									<font color=red>Unable to calculate freight rates for Canadian addresses on Boomerang. For shipping rates to Canada (or globally), please contact your Account Manager.</font>
								<? }else{ ?>								
									<input type="button" name="btnShippingadd" id="btnShippingadd" class="button_slide_cal_shipping slide_right" data-testid="order-button" value="Calculate Shipping">								
								<? } ?>								
							</div>

					</div><!-- class="coninuePayment"  -->
					
					<!--
						<div class="frmsection" >
							<div class="frm-txt">
								<div class="frm-txt-shipping">Billing address</div><span id="custPickedAdding"></span><br>
								<div class="frm-subtext">Select the address that matches your credit card.</div>

							</div>
							<div class="div-space"></div>
							<fieldset id="div_shipping_sel" class="content-box"  >
								<div  class="radio-wrapper content-box__row" data-same-billing-address="">
									<div class="radio__input">
										<input class="input-radio" data-backup="different_billing_address_false" type="radio" value="same" checked="checked" name="rdoBillingAdd" id="rdoSameBillingAdd" onchange="rdoStatus()" >
										<label for="rdoSameBillingAdd"><span></span>
									</div>

									<label class="radio__label content-box__emphasis shipping_billing_add" for="checkout_different_billing_address_false">
										Same as shipping address
									</label>            
								</div>
								<div class="radio-wrapper content-box__row" data-different-billing-address="">
								  <div class="radio__input">
								  	<input class="input-radio" data-backup="different_billing_address_true" aria-controls="section--billing-address__different" 
									type="radio" value="different" name="rdoBillingAdd" id="rdoDiffBillingAdd" onchange="rdoStatus()"  >
									<label for="rdoDiffBillingAdd"><span></span>
								  </div>
								  	<label class="radio__label content-box__emphasis shipping_billing_add" for="checkout_different_billing_address_true">
									Use a different billing address
									</label> 
								</div>
							</fieldset>
							<div id="div_shipping_sel1" class="div-space"></div>
							<fieldset class="content-box" id="billingAddSection">
								<div id="payment-gateway-subfields-44168151171" data-subfields-for-gateway="44168151171" class="radio-wrapper content-box__row content-box__row--secondary card-fields-container card-fields-container--loaded card-fields-container--transitioned">
									<div class="fieldset" data-credit-card-fields="" data-slate="" dir="ltr">
										<div class="fieldset display_none" id="billingExtraFields" >
											<div class="field field--required field--half" data-address-field="first_name">
												<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_first_name">First name</label>
													<input placeholder="First name" autocomplete="shipping given-name" autocorrect="off" data-backup="first_name" class="field__input" aria-required="true" size="30" type="text" name="txtbillingaddFNm" id="txtbillingaddFNm" value="">
												</div>
											</div>
											<div class="field field--required field--half" data-address-field="last_name">
												<div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_last_name">Last name</label>
												  <input placeholder="Last name" autocomplete="shipping given-name" autocorrect="off" data-backup="last_name" class="field__input" aria-required="true" size="30" type="text" name="txtbillingaddLNm" id="txtbillingaddLNm">
												</div>
											</div>
											<div data-address-field="company" data-autocomplete-field-container="true" class="field field--optional">
												  <div class="field__input-wrapper"><label class="field__label field__label--visible" for="checkout_shipping_address_company">Company (optional)</label>
													<input placeholder="Company (optional)" autocomplete="shipping organization" autocorrect="off" data-backup="company" class="field__input" size="30" type="text" name="txtbillingAddCompny" id="txtbillingAddCompny">
												  </div>
											</div>
										</div>
										<div class="fieldset">											
											<div data-address-field="add1" data-autocomplete-field-container="true" class="field field--optional">
											  	<div class="field__input-wrapper">
											  		<label class="field__label field__label--visible" for="checkout_shipping_address_add1">Address Line1</label>
													<input placeholder="Address Line1" autocomplete="shipping organization" autocorrect="off" data-backup="add1" class="field__input" size="30" type="text" name="billingAdd1" id="billingAdd1" value=""  >
											  </div>
											</div>
											<div data-address-field="add2" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_add2">Address Line2</label>
													<input placeholder="Address Line2" autocomplete="shipping organization" autocorrect="off" data-backup="add2" class="field__input" size="30" type="text" name="billingAdd2" id="billingAdd2"  >
										  		</div>
											</div>
											<div data-address-field="city" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_city">City</label>
													<input placeholder="City" autocomplete="shipping organization" autocorrect="off" data-backup="city" class="field__input" size="30" type="text" name="billingAddCity" id="billingAddCity"  >
										  		</div>
											</div>
											<div class="field field--required field--half" data-address-field="state">
												<div class="field__input-wrapper">
													<label class="field__label field__label--visible" for="checkout_shipping_address_state">State</label>
										  			<input placeholder="State" autocomplete="shipping given-name" autocorrect="off" data-backup="state" class="field__input" aria-required="true" size="30" type="text" name="billingAddState" id="billingAddState"  >
												</div>
											</div>
											<div class="field field--required field--half" data-address-field="zip_code">
												<div class="field__input-wrapper">
													<label class="field__label field__label--visible" for="checkout_shipping_address_zip_code">ZIP Code</label>
										  			<input placeholder="ZIP Code" autocomplete="shipping given-name" autocorrect="off" data-backup="zip_code" class="field__input" aria-required="true" size="30" type="text" name="billingAddZip" id="billingAddZip"  >
												</div>
											</div>
											<div data-address-field="email" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_email">Email (for any billing issues)</label>
													<input placeholder="Email (for any billing issues)" autocomplete="shipping organization" autocorrect="off" data-backup="email" class="field__input" size="30" type="text" name="billingAddEmail" id="billingAddEmail"  >
										  		</div>
											</div>
											<div data-address-field="phone" data-autocomplete-field-container="true" class="field field--optional">

										  		<div class="field__input-wrapper">
										  			<label class="field__label field__label--visible" for="checkout_shipping_address_phone">Phone (for any billing issues)</label>
													<input placeholder="Phone (123 456 7891)" autocomplete="shipping organization" autocorrect="off" data-backup="phone" class="field__input" size="30" type="text" name="billingAddPhn" id="billingAddPhn" onkeyup="addHyphen(this)" maxlength="12"  >
										  		</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>		
						-->							
						
						<div class="frm-term-text frm-term-text-ucbpickup">
							<div id="errMsg1"></div>
							<div style="float: left;">
								<input type="checkbox" name="chkcp1" id="chkcp1" value="1" onclick="javascript: removemess('1');"><label for="chkcp1"><span></span></label>
							</div>
							<div class="frm-checktext">I understand it is required for me to have a loading dock and forklift to unload the delivered trailer. Any costs incurred by UCB due to not having a forklift or a loading dock will be charged to the same card or credit line used for this order.</div>
						</div>
					
						<div class="frm-term-text frm-term-text-ucbpickup" >
							<div id="errMsg2"></div>
							<div style="float: left;">
								<input type="checkbox" name="chkcp2" id="chkcp2" value="1" onclick="javascript: removemess('2');" ><label for="chkcp2"><span></span></label>
							</div>
							<? if ($quoteTotal <> 0) { ?>
								<div  id="chkcp2-txt" class="frm-checktext">I approved the shipping quote of $<? echo $quoteTotal; ?> provided by UCB.</div>
							<? }else{ ?>
								<div  id="chkcp2-txt" class="frm-checktext">I approved the shipping quote of $ provided by UCB.</div>
							<? } ?>								
							
						</div>	

						<div class="frm-term-text frm-term-text-custpickup display_none">
							<div id="errMsg3"></div>
							<div style="float: left;">
								<input type="checkbox" name="chkcp3" id="chkcp3" value="1" onclick="javascript: removemess('3');" ><label for="chkcp3"><span></span></label>
							</div>
							<div  id="chkcp3-txt" class="frm-checktext">I understand that UCB will provide me with the exact pickup address of this item after I place my order.</div>
						</div>	
										
						<div class="btn-div-shipping content-bottom-padding">
							<input type="hidden" name="hdnLastInsertId" id="hdnLastInsertId" value="<? echo $orderData['hdnLastInsertId']; ?>">
							<input type="hidden" id="hdAvailability" name="hdAvailability" value="<? echo $orderData['hdAvailability'];?>">

							<input type="hidden" id="productId" name="productIdloop" value="<? echo $ProductLoopId;?>">
							<input type="hidden" id="productQntypeid" name="productQntypeid" value="<?=$orderData['productQntypeid']?>">
							<input type="hidden" id="productNameid" name="productNameid" value="<?=$orderData['productQntypeid']?>">
							<input type="hidden" id="productQntype" name="productQntype" value="<?=$orderData['productName']?>">
							<input type="hidden" id="productQnt" name="productQnt" value="<?=$orderData['productQnt']?>">
							<input type="hidden" id="productQntprice" name="productQntprice" value="<?=$orderData['productUnitPr']?>">
							<input type="hidden" id="productTotal" name="productTotal" value="<?=$orderData['productTotal']?>">
							<input type="hidden" name="hdnUserMastrId" value="<?=$orderData['user_master_id']?>">
							<input type="hidden" id="session_login_id" name="session_login_id" value="<?=$_REQUEST['session_login_id']?>">
							<? if ($territory_country == "Canada") {?>
								<input type="hidden" name="shipping_cost_err_flg" id="shipping_cost_err_flg" value="0">
							<? }else{?>
								<input type="hidden" name="shipping_cost_err_flg" id="shipping_cost_err_flg" value="<? echo $quoteTotal;?>">
							<? }?>											
							<input type="button" name="btnContinuePay" id="btnContinuePay" class="button_slide slide_right" data-testid="order-button" value="Continue to payment" >
						</div>
				</div>
				
			</div>
			</form>

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
					$<span class="total">
						<?
						if(!empty($orderData['productTotal'])){
							echo $orderData['productTotal'];
						}else{
							echo "0.00";
						}
						?>
					</span>
				</div>
				</div>
				<div class="inner-content-shipping" id="order-content">
					<? require('item_sections.php'); ?>
				
				<div class="sidebar-sept"></div>
				
					<table class="sidebar-table">
						<tr>
							<th align="left">Truckload</th>
							<th align="center">Quantity</th>
							<th align="right">Price/Unit</th>
							<th align="right">Total</th>
						</tr>
						
						<tr>
							<td><? echo $orderData['productName'] ; ?></td>
							<td id="totolProQnt" align="center"><? echo number_format(str_replace(",", "" , $orderData['productQnt']),0) ; ?></td>
							<td align="right">$<? echo number_format(str_replace(",", "" ,$orderData['productUnitPr']),2) ; ?></td>
							<td align="right">$<? echo trim($orderData['productTotal']);?></td>
						</tr>
						<? if ($quoteTotal <> 0) { ?>
							<tr id="shipping_quote">
								<td id="quoteName" >Shipping Quote</td>
								<td align="center" id="quoteQty">1</td>
								<td align="right" id="quoteUnitPr">$<? echo $quoteTotal; ?></td>
								<td align="right" id="quoteTotal">$<? echo $quoteTotal; ?></td>
							</tr>
						<? }else{ ?>
							<tr>
								<td id="quoteName" ></td>
								<td align="center" id="quoteQty"></td>
								<td align="right" id="quoteUnitPr"></td>
								<td align="right" id="quoteTotal"></td>
							</tr>
						<? } ?>
						<tr><td colspan="4"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td></td>
							<td></td>
							<td align="right" style="font-weight: 500;">Total</td>
							<td align="right"><span class="payment-due__price">$<div id="final_val_div" style="display: inline;"><? if(!empty($orderData['productTotal'])){ 
								echo number_format((str_replace(",","",$orderData['productTotal'])+str_replace(",","",$quoteTotal)),2);}else{echo "0.00";}?></div></span>
								<input type="hidden" id="productTotal_val" name="productTotal_val" value=<? echo $orderData['productTotal']; ?> >
								
								<input type="hidden" id="product_shipTotal_val" name="product_shipTotal_val" value=<? echo number_format((str_replace(",","",$orderData['productTotal'])+str_replace(",","",$quoteTotal)),2); ?> >
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td align="right" style="font-weight: 500;">&nbsp;</td>
							<? if ($quoteTotal <> 0) {
									if(!empty($orderData['productTotal'])){ 
										$tot_val = (str_replace(",","",$orderData['productTotal'])+str_replace(",","",$quoteTotal));
										$unitvalue = number_format($tot_val/str_replace(",", "" , $orderData['productQnt']),2); ?>
										<td align="right"><span class="caltxt">$<? echo $unitvalue;?>/ unit shipping incl.</span></td>
									<?}
								?>
							<? }else{ ?>
								<td align="right"><span class="caltxt"></span></td>
							<? } ?>
						</tr>
					</table>
					
					<div style="padding-top: 60px;">
						<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about"><? if(!empty($cntInfoFNm_n) || !empty($cntInfoLNm_n) ){ echo $cntInfoFNm_n ." ".$cntInfoLNm_n; } ?><? if(!empty($orderData['cntInfoCompny'])){ echo ", ".$orderData['cntInfoCompny']; }  ?></span>
								</li>
								<li>
									<label for="Span1">Ship To Address</label>
									<span id="Span1"></span>
								</li>
								<li>
									<label for="distance"></label>
									<span id="distance"></span>
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
	<div id="loader"></div>
	<script src="http://code.jquery.com/jquery.js"></script>
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
function removemess(e){
	switch(e){
		case '1': 
		document.getElementById("errMsg1").style.display = "none";
		break;
		case '2': 
		document.getElementById("errMsg2").style.display = "none";
		break;
		case '3': 
		document.getElementById("errMsg3").style.display = "none";
		break;
	}
}

</script>	