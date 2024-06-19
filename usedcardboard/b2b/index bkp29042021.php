<?
//ini_set("display_errors", "1");
//error_reporting(E_ERROR); 

session_start();

require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$ipadd = $_SERVER['REMOTE_ADDR'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Buy Gaylord Totes - Usedcardboardboxes</title>
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
			document.getElementById("productQntypeid").value = 1;
			document.getElementById("productQntype").value = document.getElementById("productQntype1").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt1").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice1").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal1").innerHTML;
			break;
		case 2: 
			document.getElementById("productQntypeid").value = 2;
			document.getElementById("productQntype").value = document.getElementById("productQntype2").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt2").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice2").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal2").innerHTML;
			break;
		case 3: 
			document.getElementById("productQntypeid").value = 3;
			document.getElementById("productQntype").value = document.getElementById("productQntype3").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt3").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice3").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal3").innerHTML;
			break;
		case 4: 
			document.getElementById("productQntypeid").value = 4;
			document.getElementById("productQntype").value = document.getElementById("productQntype4").innerHTML;
			document.getElementById("productQnt").value = document.getElementById("productQnt4").innerHTML;
			document.getElementById("productQntprice").value = document.getElementById("productQntprice4").innerHTML;
			document.getElementById("productTotal").value = document.getElementById("productTotal4").innerHTML;
			break;
	}
	
}
	
	function showimg(){
		document.getElementById('light').style.display='block';
		document.getElementById('light').innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';>Close</a> &nbsp;<br/><img src='images/usa_map_territories.png' width='400px' height='300px' style='object-fit: cover;'/>";
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
	for(var i=0; i<proradio.length;i++)
	{
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

	function calculatedistance()
	{
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

			xmlhttp.open("POST","calculate_distance.php?zip=" + zip + "&id=<?=$_REQUEST['id'];?>", true);
			xmlhttp.send();
		}
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

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

<style>
	.highlightrow{
		transition : all ease-in-out .25s;
		background-color: #e4f9e3;
	}
	
	.white_content {
		display: none;
		position: absolute;
		padding: 5px;
		border: 2px solid black;
		background-color: white;
		overflow:auto;
		height:350px;
		width:450px;
		z-index:1002;
		margin: 100px 50px 0px 0px; 
		padding: 10px 10px 10px 10px;
		border-color:black; 
		border-width:2px;
		overflow: auto;
	}
</style>
</head>

<body >
	<div id="light" class="white_content">	</div>

	<a id="ordernow" title="Order Now" href="#">Order Now</a>
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
<?
/*-------------------------------------------------------------------------------
reason
tlssitdctklcom2a5veoqvshu1
tlssitdctklcom2a5veoqvshu1
-------------------------------------------------------------------------------*/

$sessionId = session_id();

/*data store in db for tracking*/
$machineIP = ''; $userId = ''; 
if(!empty($_COOKIE["uid"])){
	echo $userId = $_COOKIE["uid"];
}

if ($_SESSION['productData']) {
	$orderData = $_SESSION['productData'];
	if ($orderData['ProductLoopId'] == $_REQUEST["id"]) {
		$id = $orderData['ProductLoopId'];
	}else{
		$id = $_REQUEST["id"];
	}	
}else{
	$id = $_REQUEST["id"];
}	
//$id = 2566;
//$machineIP 	= $_SERVER['REMOTE_ADDR'];
$recordDt 	= date('YmdHis');


$machineIP 	= getHostByName(getHostName());
if(substr($machineIP, 0, -3) == "192.168.0."){
	$machineIP 	= "";
}

$location = @unserialize(file_get_contents('http://ip-api.com/php/'.$machineIP));
if($location && $location['status'] == 'success'){
	$loczip = $location['zip'];
}
/*check the user product entry already or not with current session id*/
//AND product_loopboxid = '".$id . "'
$getSessionDt = db_query("SELECT id FROM b2becommerce_tracking WHERE session_id = '".$sessionId."' ", db() );
$rowSessionDt = array_shift($getSessionDt);
if($rowSessionDt["id"] != ""){
	db_query("Update b2becommerce_tracking set product_loopboxid = '".$id."' where session_id = '".$sessionId."'", db());
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
	
$product_name_id = "";
$dt_view_res_data = db_query("Select product_name_id from b2becommerce_order_item where session_id = '" . $sessionId . "'", db() );
while ($myrowsel_b2b = array_shift($dt_view_res_data))
{
	$product_name_id = $myrowsel_b2b["product_name_id"];
}	

$data_product_name1 = ""; $data_product_name2 = ""; $data_product_name3 = ""; $data_product_name4 = "";	 
if ($product_name_id == 1){ 
	$data_product_name1 = " checked ";
?>
	<script>
		productinfo(1);
	</script>
<?
}	
if ($product_name_id == 2){ 

	$data_product_name2 = " checked ";
	
?>
	<script>
		productinfo(2);
	</script>
<?
}	

if ($product_name_id == 3){ 
	$data_product_name3 = " checked ";
?>
	<script>
		productinfo(3);
	</script>
<?
	
}	
if ($product_name_id == 4){ 
	$data_product_name4 = " checked ";
	
?>
	<script>
		productinfo(4);
	</script>
<?
}	

/*-------------------------------------------------------------------------------
Started updating by Amarendra dated 01-04-2021 
-------------------------------------------------------------------------------*/
?>
	<div class="new_section new-section-margin">
	<div class="new_container">
	
		<!--testing
	</div>
	</div>
	
	
	
	<div class="sections  new-section-margin">
		<div class="section-header">-->
			<div class="inner-margin">
				<div class="section-top-margin">
				<?php
					$testvalue = $rowb2b["box_type"];
					//$testvalue = "Large";
					if (in_array(strtolower($testvalue), array_map('strtolower', array("Gaylord", "GaylordUCB" ))))
					{ 
						$boxtypename = "Gaylord"; 
					} elseif (array_search(strtolower($testvalue), array_map('strtolower', array("Medium", "Large", "Xlarge", "Box", "Boxnonucb", "Presold" ))))
						{ 
						$boxtypename = "Shipping Boxe"; 
					} elseif (array_search(strtolower($testvalue), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" ))))
					{ 
						$boxtypename = "Supersack"; 
					} elseif (array_search(strtolower($testvalue), array_map('strtolower', array("DrumBarrelUCB", "DrumBarrelnonUCB" ))))
						{ 
						$boxtypename = "DrumBarrelIBC"; 
					} elseif (array_search(strtolower($testvalue), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB"))))
						{ 
						$boxtypename = "Pallet"; 
					} elseif (array_search(strtolower($testvalue), array_map('strtolower', array("Recycling"))))
						{ 
						$boxtypename = "Recycling"; 
					
					} else { 
						$boxtypename = "Mixed Type"; }
	
				?>
					<h1 class="section-title">Buy <?=$boxtypename;?></h1>
					<div class="title_desc">It's as easy as <b>Select Your Quantity</b> and <b>Buy</b>!</div>
				</div>
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
				  <span class="breadcrumb__text">Shipping/Billing</span>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>
				  <li class="breadcrumb__item breadcrumb__item--blank">
				  <span class="breadcrumb__text">Payment</span>
				</li>
			</ol>
		  </nav>
			<!--End Breadcrums-->
			<div class="content-div content-padding">
				<div class="left_qty">
					<table class="qty-table-top">
						<tr>
							<td><strong>Availability </strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">If you order now, this is how long until a full truckload is accumulated and ready to ship</span></div></td>

							<td>
							<?php
							$warehouse_id = $row["box_warehouse_id"];
							$expected_loads_per_mo = $row["expected_loads_per_mo"];
							$lead_time = $row["lead_time"];
							$next_load_available_date = $row["next_load_available_date"];
							
							$txt_actual_qty = $row["actual_qty"];	
							$txt_after_po = $row["after_po"];	
							$txt_last_month_qty = $row["last_month_qty"];	
							$availability = $row["availability"];	
							$boxes_per_trailer = $row["boxes_per_trailer"];
							
							//Buy Now, Load Can Ship In
							if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
							{
								$now_date = time(); // or your date as well
								$next_load_date = strtotime($next_load_available_date);
								$datediff = $next_load_date - $now_date;
								$no_of_loaddays=round($datediff / (60 * 60 * 24));
								
								if($no_of_loaddays<$lead_time)
								{
									if($row["lead_time"]>1)
									{
										$estimated_next_load= $row["lead_time"] . " Working Days";
									}
									else{
										$estimated_next_load= $row["lead_time"] . " Working Day";
									}
									
								}
								else{
									if ($no_of_loaddays == -0){
										$estimated_next_load= "1 Working Day";
									}else{
										$estimated_next_load= $no_of_loaddays . " Working Days";
									}						
								}
							}
							else{			
								if ($txt_after_po >= $boxes_per_trailer) {
									if ($row["lead_time"] == 0){
										$estimated_next_load= "Now";
									}							

									if ($row["lead_time"] == 1){
										$estimated_next_load= $row["lead_time"] . " Working Day";
									}							
									if ($row["lead_time"] > 1){
										$estimated_next_load= $row["lead_time"] . " Working Days";
									}							
								}
								else{
									if (($row["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
										//$estimated_next_load= "Never (sell the " . $txt_after_po . ")";
									}else{
										$estimated_next_load = ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$row["expected_loads_per_mo"])*4)." Weeks";
									}
								}
							}	
							echo $estimated_next_load;
							?>
							
							</td>
						</tr>
						 
						 <tr>
							<td><strong>Location </strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i><span class="tooltiptext">Territory where the boxes are located</span></div>
								<a onclick="showimg()">(see map)</a>
							</td>
							<td>
								<? 
								$box_warehouse_id = $row["box_warehouse_id"];
								$shipfrom_state = "";
								if ($rowb2b["vendor_b2b_rescue"] != "" && $box_warehouse_id == "238"){
										$q1 = "SELECT * FROM loop_warehouse where id = ".$rowb2b["vendor_b2b_rescue"];
										//echo $q1 . "<br>"; 
										$v_query = db_query($q1, db());
										while($v_fetch = array_shift($v_query))
										{
											$com_qry=db_query("select * from companyInfo where ID='".$v_fetch["b2bid"]."'",db_b2b());
											$com_row= array_shift($com_qry);
											$shipfrom_state = $com_row["shipState"];
										}
								}
								elseif($box_warehouse_id > 0 && $box_warehouse_id!="238"){
									$lwqry = db_query("Select * from loop_warehouse where id = ".$box_warehouse_id, db() );
									//echo "Select * from loop_warehouse where id = ".$box_warehouse_id . "<br>"; 
									while ($lwrow = array_shift($lwqry))
									{
										$shipfrom_state = $lwrow["warehouse_state"]; 
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
								
								echo $territory . " Territory";?>
							</td>
						</tr>
				        <tr>
							<td><strong>Enter Zip</strong> <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">To see how close the item is to your facility.</span></div></td>
							<td>
								<form action="" method="post">
									<input class="field__input" type="text" name="distzip" id="distzip" value="<?=$loczip?>" size="10" maxlength="7" placeholder="Zip code">
									<input class="button_slide_dis slide_right" type="button" id="caldist" name="caldist" onclick="javascript:calculatedistance();" value="Find Distance">
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
								<span id="distancewarehouse">&nbsp;</span>
							</td>
						</tr>
						
					</table>
					
					
					<div class="div-space"></div>
					<div class="qty-div">
						<table class="qty-table">
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
							$unitprice = $rowb2b["ulineDollar"] + $rowb2b["ulineCents"];
							//$unitfactor = 1.0031812;
							
							$halfprice = ($unitprice + 1.50);
							$quarterprice = ($unitprice + 3.00);
							$palleteprice = ($unitprice + 6.00);	
							$ship_ltl = $row["ship_ltl"];
							?>
							<tr class="highlightrow">
								<td align="left">
									<input id="radio1" type="radio" name="radio" value="1" <? echo $data_product_name1;?> <?=($row["ship_ltl"]=="0")? ' checked ' : ' ';?> onclick="javascript: productinfo(1);">
									<label for="radio1"><span><span></span></span>
									<label id="productQntype1">Full</label></label>

								</td>
								<td>
									<span id="productQnt1"><?=trim($row["boxes_per_trailer"]);?>
									</span>
									
								</td>
								<td>
									$<span id="productQntprice1"><?=$unitprice;?>
									</span>
									
								</td>
								<td>
									$<span id="productTotal1"><?=number_format($row["boxes_per_trailer"] * ($rowb2b["ulineDollar"] + $rowb2b["ulineCents"]),2);?>
									</span>
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
							<tr>
								<td align="left">
									<input id="radio2" type="radio" name="radio" value="2" <? echo $data_product_name2;?> onclick="javascript: productinfo(2);" >
									<label for="radio2"><span><span></span></span>
									<label id="productQntype2">Half</label></label>
								</td>
								<td>
									<span id="productQnt2"><?=$row["boxes_per_trailer"]/2;?></span>
								</td>
								<td>
									$<span id="productQntprice2"><?=number_format($halfprice, 2);?></span>
								</td>
								<td>
									$<span id="productTotal2"><?=number_format(($row["boxes_per_trailer"]/2)*$halfprice, 2);?></span>
								</td>
							</tr>
							<tr>
								<td align="left">
									<input id="radio3" type="radio" name="radio" value="3" <? echo $data_product_name3;?> onclick="javascript: productinfo(3);">
									<label for="radio3"><span><span></span></span><label id="productQntype3">Quarter</label></label>

								</td>
								<td>
									<span id="productQnt3"><?=$row["boxes_per_trailer"]/4;?></span>
								</td>
								<td>
									$<span id="productQntprice3"><?=number_format($quarterprice, 2);?></span>
								</td>
								<td>
									$<span id="productTotal3"><?=number_format(($row["boxes_per_trailer"]/4)*$quarterprice, 2);?></span>
								</td>
							</tr>
							<tr>
								<td align="left">
									<input id="radio4" type="radio" name="radio" value="4" <? echo $data_product_name4;?> onclick="javascript: productinfo(4);">
									<label for="radio4"><span><span></span></span><label id="productQntype4">1 Pallet</label></label>

								</td>
								<td>
									<span id="productQnt4"><?=$row["bpallet_qty"];?></span>
								</td>
								<td>
									$<span id="productQntprice4"><?=number_format($palleteprice, 2);?></span>
								</td>
								<td>
									$<span id="productTotal4"><?=number_format($row["bpallet_qty"] * $palleteprice, 2);?></span>
								</td>
							</tr>
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
						<button type="button" onclick="javascript: formvalidate();" class="button_slide slide_right" data-testid="order-button">Order Now</button>
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
					
				?>				
				
				<div class="right-products">
					<div class="productinfo-section">
					<div class="productinfo-main product-box-shadow">
						<h2 class="prod-title">
						<? if ($boxtypename == "Recycling/Other Type") { ?> 
							Item ID: <?=$row["b2b_id"]?>
						<? }else{ ?> 
							<?=$boxtypename;?> ID: <?=$row["b2b_id"]?>
						<? } ?> 
						</h2>
						<div class="mob_container">
						<div class="prod-info">
							 <ol class="name-value" style="width: 100%;">
								<li>                    
									<label for="about">Condition</label>
									<span id="about"><?=$rowb2b["newUsed"]?></span>
								</li>
								<li>
									<label for="Span1">Dimensions</label>
									<span id="Span1">
									<? 
										if($row["uniform_mixed_load"] == "Mixed"){
											$dimension = $row["blength_min"] . ' - '. $row["blength_max"] . '" x ';
											$dimension .= $row["bwidth_min"] . ' - '. $row["bwidth_max"] . '" x ';
											$dimension .= $row["bheight_min"] . ' - '. $row["bheight_max"] . '"';
										} else {
											$dimension = $row["blength"];
											$dimension .= ($row["blength_frac"]=="")? '" x ' : ' ' . $row["blength_frac"] . '" x ';
											$dimension .= $row["bwidth"];
											$dimension .= ($row["bwidth_frac"]=="")? '" x ' : ' ' . $row["bwidth_frac"] . '" x ';
											$dimension .= $row["bdepth"];
											$dimension .= ($row["bdepth_frac"]=="")? '"' : ' ' . $row["bdepth_frac"] . '"';
										}
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
									<span id="Span9"><?=($row["bpallet_qty"]=="0")? '0' : $row["bpallet_qty"];?><br></span>
								</li>
								 <li>
									<label for="Span10">Truckload Qty</label>
									<span id="Span10"><?=($row["boxes_per_trailer"]=="0")? '' : $row["boxes_per_trailer"];?><br></span>
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
								
							</ol>
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
										<? }?>
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
			<div class="privacy-links_inner">
					<div class="bottomlinks">
					<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div><div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div><div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div><div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
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
?>	

	<script>
		calculatedistance_ip('<? echo $ipadd;?>', <? echo $_REQUEST['id'];?>);		
	
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
</body>
</html>
