<?php  
ini_set("display_errors", "0");
error_reporting(E_ALL);
require ("inc/header_session.php");
?>

<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - UPS Status Report</title>
<style type="text/css"> 
.style1 {
	font-size: xx-small;
	background-color: #FF9933;
}
.style5 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	text-align: center;
	background-color: #99FF99;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
}
.style3 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #333333;
}
.style4 {
	text-align: left;
}
.style7 {
	background-color: #99FF99;
}
.style8 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
	background-color: #99FF99;
}
.style9 {
	text-align: center;
	background-color: #99FF99;
}
.style10 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: xx-small;
}
.style11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: xx-small;
	text-align: center;
}
</style>

</head>

<body>
<div>
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">
<?php 

$status = $_GET["status"];
$statUbox = '';
switch($status)
	{
		case 'I':
			$stat = " (OE.status = 'In transit' OR OE.fedex_status NOT LIKE 'DE' AND OE.fedex_status NOT LIKE 'SE' AND OE.fedex_status NOT LIKE 'DL' AND OE.fedex_status NOT LIKE '' AND OE.fedex_status NOT LIKE 'OC') ";
			$statUbox = " (UOFD.ubox_order_fedex_status NOT LIKE 'DE' AND UOFD.ubox_order_fedex_status NOT LIKE 'SE' AND UOFD.ubox_order_fedex_status NOT LIKE 'DL' AND UOFD.ubox_order_fedex_status NOT LIKE '' AND UOFD.ubox_order_fedex_status NOT LIKE 'OC') ";
			BREAK;
		case 'D':
			$stat = " (OE.status = 'Delivered' OR OE.fedex_status LIKE 'DL') ";
			BREAK;
		case 'TN_No_Shopify':
			//$stat = " O.shopify_order_no <> '' and (OE.tracking_number <> '' or O.ubox_order_tracking_number<> '') and O.updated_tracking_shopify_flg = 0 and O.orders_id > 391425 ";
			$stat = 'TN_No_Shopify';
			$statUbox = " ";
			BREAK;
		case 'X':
			$stat = " (OE.status = 'Exception' OR OE.fedex_status LIKE 'DE' OR OE.fedex_status LIKE 'SE') ";
			$statUbox = " (UOFD.ubox_order_fedex_status = 'DE' OR UOFD.ubox_order_fedex_status = 'SE') ";
			BREAK;
		case 'P':
			$stat = " (OE.status = 'Pickup' OR OE.fedex_status LIKE 'PU') ";
			BREAK;
		case 'M':
			$stat = " (OE.status = 'Manifest Pickup' OR OE.fedex_status LIKE 'OC') ";
			$statUbox = " (UOFD.ubox_order_fedex_status LIKE 'OC') ";
			BREAK;	
		case 'Z':
			$stat = "Z";
			$statUbox = " (UOFD.ubox_order_fedex_status LIKE '') ";
			BREAK;					
	}
//echo "<br /> statUbox -> ".$statUbox;

//echo "<br /> status -> ".$status;

	if ($status != 'possible_issue'){	
		if ($stat != "Z"  ) {
			if($stat == 'TN_No_Shopify' ){
				$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel 
				FROM orders O LEFT JOIN orders_active_export OE ON O.orders_id = OE.orders_id 
				where O.`cancel` <> 'Yes' and O.shopify_order_no <> '' and (OE.tracking_number <> '' or O.ubox_order_tracking_number <> '') 
				and O.updated_tracking_shopify_flg = 0 and O.orders_id > 391425 AND OE.setignore != 1 ";

				//$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders O LEFT JOIN orders_active_export OE ON O.orders_id = OE.orders_id where O.shopify_order_no <> '' and (OE.tracking_number <> '' or O.ubox_order_tracking_number <> '') and O.updated_tracking_shopify_flg = 0 and O.orders_id > 391425 AND OE.setignore != 1 AND OE.fedex_description != 'Delivered' ";
			}else{
				$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
				//$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 AND OE.fedex_description != 'Delivered' ORDER BY O.date_purchased";
			}
		}
		
		if ($stat == 'Z' ) {
			$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = '' AND OE.setignore != 1 AND OE.fedex_status = '' ORDER BY O.date_purchased";
		}

		//echo $ship_it . "<br>";
		$ship_it_result = db_query($ship_it, db());
		$ship_it_result_rows = tep_db_num_rows($ship_it_result);

		if ($status != "TN_No_Shopify") {	
			$resFedexDt = db_query("SELECT UOFD.product_description, UOFD.id, UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name, O.ubox_order_tracking_number AS uboxOrderTrackingNumber, O.ubox_order_carrier_code, O.cancel FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE ".$statUbox." AND UOFD.setignore != 1 ORDER BY O.date_purchased", db());

			//$resFedexDt = db_query("SELECT UOFD.product_description, UOFD.id, UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name, O.ubox_order_tracking_number AS uboxOrderTrackingNumber, O.ubox_order_carrier_code, O.cancel, OE.fedex_description FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id INNER JOIN orders_active_export OE ON OE.orders_id = O.orders_id WHERE ".$statUbox." AND UOFD.setignore != 1 AND OE.fedex_description != 'Delivered' ORDER BY O.date_purchased", db());

			//echo "<br /> resFedexDt -> ";
			//echo "SELECT UOFD.product_description, UOFD.id, UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name, O.ubox_order_tracking_number AS uboxOrderTrackingNumber, O.ubox_order_carrier_code, O.cancel FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE ".$statUbox." AND UOFD.setignore != 1 ORDER BY O.date_purchased <br>";
		}
	}
	
	//  Possible issues
	if ($status == "possible_issue") {	
		$possible_issue_order_id_list = "";
		$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = '' AND OE.setignore != 1 AND OE.fedex_status = '' ORDER BY O.date_purchased";
		$ship_it_result = db_query($ship_it, db());
		while ($report_data = array_shift($ship_it_result)) {
			$dp = $report_data["print_date"];
			$print_date = date("F j Y H:i:s", strtotime($dp)); 			
			$todaysDt = date('Y-m-d H:i:s');

			if(strtotime($dp) < strtotime(date('Y-m-d')) ){
				$possible_issue_order_id_list .= $report_data["id"] . ",";
			}
		}
		
		//Not Picked Up: M case
		$stat = " (OE.status = 'Manifest Pickup' OR OE.fedex_status LIKE 'OC') ";
		$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
		$ship_it_result = db_query($ship_it, db());
		while ($report_data = array_shift($ship_it_result)) {
			$dp = $report_data["print_date"];
			$print_date = date("F j Y H:i:s", strtotime($dp)); 			
			$todaysDt = date('Y-m-d H:i:s');
			$otStr = '';
			//get the todays day
			//$dp = '2021-03-12 14:40:00';
			//$todaysDt ='2021-03-15 18:00:00';
			$todaysDay = date("D",strtotime($todaysDt));
			if($todaysDay == 'Sun'){
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 2 days'));
			}else if($todaysDay == 'Mon'){
				$considerDay =  date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 3 days'));
			}else{
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 1 days'));
			}			
			//echo $considerDay;
			$dayGrtrfourDays =  date('Y-m-d H:i:s', strtotime($todaysDt. ' +  4 days'));
			$dayOther = date('Y-m-d H:i:s', strtotime($considerDay. ' - 1 days'));

			if ( strtotime($dp) < strtotime($considerDay)  ){
				$possible_issue_order_id_list .= $report_data["id"] . ",";
			}
		}
		
		//In Transit: I case
		$stat = " (OE.status = 'In transit' OR OE.fedex_status NOT LIKE 'DE' AND OE.fedex_status NOT LIKE 'SE' AND OE.fedex_status NOT LIKE 'DL' AND OE.fedex_status NOT LIKE '' AND OE.fedex_status NOT LIKE 'OC') ";
		$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
		$ship_it_result = db_query($ship_it, db());
		while ($report_data = array_shift($ship_it_result)) {
			$dp = $report_data["print_date"];
			$print_date = date("F j Y H:i:s", strtotime($dp)); 			
			$todaysDt = date('Y-m-d H:i:s');
			$otStr = '';
			$todaysDay = date("D",strtotime($todaysDt));
			if($todaysDay == 'Sun'){
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 2 days'));
			}else if($todaysDay == 'Mon'){
				$considerDay =  date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 3 days'));
			}else{
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 1 days'));
			}			
			//echo $considerDay;
			$dayGrtrfourDays =  date('Y-m-d H:i:s', strtotime($todaysDt. ' +  4 days'));
			$dayOther = date('Y-m-d H:i:s', strtotime($considerDay. ' - 1 days'));

			//if( strtotime($dp) > strtotime($dayGrtrfourDays) ){
			if( floor((strtotime($todaysDt) - strtotime($dp))/86400) > 4 ){
				$possible_issue_order_id_list .= $report_data["id"] . ",";
			}
			
		}	

		//Exceptions: X case
		$stat = " (OE.status = 'Exception' OR OE.fedex_status LIKE 'DE' OR OE.fedex_status LIKE 'SE') ";
		$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel  FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
		$ship_it_result = db_query($ship_it, db());
		while ($report_data = array_shift($ship_it_result)) {
			$possible_issue_order_id_list .= $report_data["id"] . ",";
		}		
		
		if (trim($possible_issue_order_id_list) != ""){
			$possible_issue_order_id_list = substr($possible_issue_order_id_list, 0, strlen($possible_issue_order_id_list)-1);
			
			$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel  FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.id in ( " . $possible_issue_order_id_list . ") ORDER BY O.date_purchased ";
			//echo $ship_it . "<br>";
			
			$ship_it_result = db_query($ship_it, db());
			$ship_it_result_rows = tep_db_num_rows($ship_it_result);
		}
	}

	//echo  "<br /> ship_it -> ".$ship_it . "<br>";
?>

<!--
<span class="style2">
	<a href="index.php">Home</a></span>
<br />-->
<br />

<table cellSpacing="1" cellPadding="1" width="98%" border="0">
	<tr align="middle">
		<td colSpan="9" class="style1">
			<font face="Arial, Helvetica, sans-serif" color="#333333">
				ORDER TRACKING - 
				<?php  
				if ($stat == 'Z') { 
					echo "No Data Available"; 
				}elseif($stat == 'TN_No_Shopify'){
					echo " O.shopify_order_no <> '' and (OE.tracking_number <> '' or O.ubox_order_tracking_number<> '') and O.updated_tracking_shopify_flg = 0 and O.orders_id > 391425 ";
				} else { echo $stat; } ?>
			</font>
		</td>
	</tr>
	<tr>
		<td align="center" style="width: 4%; height: 16px;" class="style7">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER ID</font></td>
		<td class="style5" style="width: 12%; height: 16px;">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		ORDER DATE</td>
		<td class="style5" style="width: 12%; height: 16px;">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		LABEL PRINT DATE</td>
		<td class="style5" style="width: 4%; height: 16px;">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			WAREHOUSE
		</td>
		<td class="style5" style="width: 4%; height: 16px;">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			BOX ITEM
		</td>
		<td style="width: 20%; height: 16px;" class="style9">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		NAME</a></font></td>
		<td class="style5" style="width: 15%; height: 16px;">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		TRACKING NUMBER</td>
		<td align="middle" style="width: 20%; height: 16px;" class="style8">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		STATUS</td>
		<td align="middle" style="width: 5%; height: 16px" class="style8">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		IGNORE</td>
	</tr>
<?php   
//echo "<br /> ship_it_result -> <pre>"; print_r($ship_it_result);
$i=0;
while ($report_data = array_shift($ship_it_result)) {
	if ($i % 2 == 0){
		$bgcolor = "#ffffff";
	}else{
		$bgcolor = "#DCDCDC";
	}
	
	$orders_warehouse = "";
	$orders_warehouse_query = db_query("select name, abbreviation from warehouse where warehouse_id = '" . $report_data["warehouse_id"] . "'", db());
	while ($orders_warehousers = array_shift($orders_warehouse_query)) 
	{
		$orders_warehouse = $orders_warehousers["abbreviation"];
	}
	?>
	<tr vAlign="center" bgColor="<?php  echo $bgcolor; ?>" >
		<td align="center" class="style3" style="width: 4%; height: 22px;">		
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
				<a target="_blank" href="orders.php?id=<?php  echo $report_data["orders_id"]; ?>&proc=View&searchcrit=&page=0"><?php  echo $report_data["orders_id"]; ?></a>
		</td>
		<td align="left" style="width: 12%; height: 22px;">
		<?php  
			$dp = $report_data["date_purchased"];
			$order_date = date("F j Y H:i:s", strtotime($dp)); 
		?>
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<?php  echo $order_date; ?> </font>
		</td>
		<td align="left" style="width: 12%; height: 22px;">
		<?php  
			$dp = $report_data["print_date"];
			$print_date = date("F j Y H:i:s", strtotime($dp)); 			
			$todaysDt = date('Y-m-d H:i:s');
			$otStr = '';
			//get the todays day
			//$dp = '2021-03-12 14:40:00';
			//$todaysDt ='2021-03-15 18:00:00';
			$todaysDay = date("D",strtotime($todaysDt));
			if($todaysDay == 'Sun'){
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 2 days'));
			}else if($todaysDay == 'Mon'){
				$considerDay =  date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 3 days'));
			}else{
				$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt. ' - 1 days'));
			}			
			//echo $considerDay;
			$dayGrtrfourDays =  date('Y-m-d H:i:s', strtotime($todaysDt. ' +  4 days'));
			$dayOther = date('Y-m-d H:i:s', strtotime($considerDay. ' - 1 days'));


			if ($status == "Z") {	
				if(strtotime($dp) < strtotime(date('Y-m-d')) ){
					$otStr = 'MakeitRed'; //'No Data';
				}
			}

			if ($status == "M") {	
				if ( strtotime($dp) < strtotime($considerDay)  ){
					$otStr = 'MakeitRed'; //'Not Picked Up';
				}
			}

			if ($status == "I") {	
				//echo "I - " . date('Y-m-d', strtotime($dp)) . " " . date('Y-m-d', strtotime($todaysDt)) . " = " . floor((strtotime($todaysDt) - strtotime($dp))/86400) . "<br>";
				if( floor((strtotime($todaysDt) - strtotime($dp))/86400) > 4 ){
				
					$otStr = 'MakeitRed'; //'In Transit';
				}
			}

			if ($status == "X") {	
				if( $report_data['fedex_status'] == 'DE' || $report_data['fedex_status'] == 'SE' ){
					$otStr = 'MakeitRed';
				}
			}
			
			if ($status == "possible_issue") {	
				$otStr = 'MakeitRed';
			}	
		?>
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<?php  
			if($otStr == 'MakeitRed'){ 
				echo "<font color=red>" . $print_date . "</font>";
			}else{
				echo $print_date;
			} 
			?>
			</font>
		</td>

		<td style="width: 4%; height: 22px;" class="style11">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $orders_warehouse; ?> </font>
		</td>
		<td style="width: 4%; height: 22px;" class="style11">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["module_name"]; ?> </font>
		</td>
		<td style="width: 20%; height: 22px;" class="style11">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
				<?php 
				if($report_data["cancel"] == "Yes"){
					echo $report_data["customers_name"] . ' - Order Cancelled.';
				} else {
					echo $report_data["customers_name"]; 
				}
				?> 
			</font>
		</td>

		<td style="width: 15%; height: 22px;" class="style11">
			<?php  
				$tracking_no_len = strlen(trim($report_data["tracking_number"]))."<br>";
				if ($tracking_no_len < 16){
					$fexex_str = "https://www.fedex.com/fedextrack/summary?trknbr=";
				} else {
					$fexex_str = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=";
				}
				if ($report_data["tracking_number"] != ""){
				?>
					<a target="_blank"  href="<?php  echo $fexex_str . $report_data["tracking_number"] ?>">
					<?php  echo $report_data["tracking_number"];?></a>								
				<?php 	
				}
				/*-------------------------------------------------------------------------------------------------------------------* /
				<a href="https://www.fedex.com/apps/fedextrack/?action=track&ascend_header=1&clienttype=dotcom&mi=n&cntry_code=us&language=english&tracknumbers=<?php  echo $report_data["tracking_number"]; ?>"><?php  echo $report_data["tracking_number"]; ?></a>
				/*-------------------------------------------------------------------------------------------------------------------*/
			?>
		</td>
		<td align="middle" style="width: 20%; height: 22px;" class="style3 style11">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php  echo $report_data["status"]; ?><?php  echo $report_data["fedex_description"]; ?></font></td>
		<td align="middle" width="5%" class="style3 style11" style="height: 22px">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><a href="ups_status_report_ignore.php?id=<?php  echo $report_data["id"]; ?>&status=<?php  echo $status; ?>">IGNORE</a></td>
	</tr>
	<?php  
	$i++;
} ?>

<?php  
$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63, 64, 65, 66, 67, 68, 69, 70)";
$rgp = array_shift(db_query($query));
$arr_rgp = explode(',', $rgp["grp_prod_id"]);
//echo "<br /> resFedexDt -> <pre>"; print_r($resFedexDt);
while ($rowDtFedex = array_shift($resFedexDt)) {
	if ($i % 2 == 0){
		$bgcolor = "#ffffff";
	}else{
		$bgcolor = "#DCDCDC";
	}

	$shopify_product_nm = $rowDtFedex["product_description"];
	/*$orders_products_query = db_query("select orders_products_id, products_id, orders_products.orders_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from orders_products where orders_products.orders_id = '" . (int)$rowDtFedex["orders_id"] . "'");
	while ($orders_products = array_shift($orders_products_query)) {
		
		if(in_array($orders_products["products_id"], $arr_rgp))
		{
		  $shopify_product_nm = "";
		  $orders_products_query1 = db_query("select * from products_shopify where ucb_products_id = '" . $orders_products["products_id"] . "'");
		  while ($orders_products1 = array_shift($orders_products_query1)) {
			$shopify_product_nm = $orders_products1["product_description"];
		  }
		  
		  //if ($shopify_product_nm == ""){
		//	$shopify_product_nm = $orders_products['products_name'];
		  //}
		}
	}*/	
	?>
	<tr vAlign="center" bgColor="<?php  echo $bgcolor; ?>" >
		<td align="center" class="style3" style="width: 6%; height: 22px;">		
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<a target="_blank" href="orders.php?id=<?php  echo $rowDtFedex["orders_id"]; ?>&proc=View&searchcrit=&page=0"><?php  echo $rowDtFedex["orders_id"]; ?></a>
		</td>
		<td align="left" style="width: 6%; height: 22px;">										
			<?php  
			$dp = $rowDtFedex["date_purchased"];
			$order_date = date("F j Y", strtotime($dp)); 
			?>	
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<?php  echo $order_date; ?> </font>
		</td>
		
		<td align="left" style="width: 11%; height: 22px;" class="style4">&nbsp;
			
		</td>
		<td style="width: 4%; height: 22px;" class="style11">&nbsp;
			
		</td>
		<td style="width: 20%; height: 22px;" class="style11">
			&nbsp;
			<?php  echo $shopify_product_nm; ?>
		</td>
		<td style="width: 11%; height: 22px;" class="style11">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
				<?php 
				if($rowDtFedex["cancel"] == "Yes"){
					echo $rowDtFedex["customers_name"] . ' - Order Cancelled.';
				} else {
					echo $rowDtFedex["customers_name"]; 
				}
				?> 
			</font>
		</td>
		
		<td style="width: 14%; height: 22px;" class="style11">
		<?php  
			$tracking_no_len = strlen(trim($rowDtFedex["ubox_order_tracking_number"]))."<br>";
			if ($tracking_no_len < 16){
				$fexex_str = "https://www.fedex.com/fedextrack/summary?trknbr=";
			} else {
				$fexex_str = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=";
			}
			
			if (trim($rowDtFedex["ubox_order_tracking_number"]) != ""){
			?>
				<a target="_blank"  href="<?php  echo $fexex_str . $rowDtFedex["ubox_order_tracking_number"] ?>">
				<?php  echo trim($rowDtFedex["ubox_order_tracking_number"]);?></a>								
			<?php 
			}
			/*-------------------------------------------------------------------------------------------------------------------* /
			<a href="https://www.fedex.com/apps/fedextrack/?action=track&ascend_header=1&clienttype=dotcom&mi=n&cntry_code=us&language=english&tracknumbers=<?php  echo $rowDtFedex["ubox_order_tracking_number"]; ?>"><?php  echo $rowDtFedex["ubox_order_tracking_number"]; ?></a>
			/*-------------------------------------------------------------------------------------------------------------------*/
			?>
		</td>
		<td align="middle" style="width: 11%; height: 22px;" class="style3">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
				<?php  echo $rowDtFedex["ubox_order_fedex_description"]; ?>
			</font>
		</td>
		<td align="middle" width="10%" class="style3" style="height: 22px">
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
				<a href="ups_status_report_ignore.php?id=<?php  echo $rowDtFedex["id"]; ?>&status=<?php  echo $status; ?>&UboxexTrack=yes">IGNORE</a>
			</font>
		</td>
	</tr>
	<?php  
	$i++;

} ?>
</table>
 
	</div>
</body>
</html>
