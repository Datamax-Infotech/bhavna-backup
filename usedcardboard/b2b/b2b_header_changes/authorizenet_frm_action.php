<?
//Confrimation Page start here
if(session_id() == ''){
    session_start();
}
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
require ("cal_functions.php");
db();

//Code to send mail
require '../phpmailer/PHPMailerAutoload.php';

$sessionId = session_id();

$compnewid = $_SESSION['compnewid'];

$productTotal = 0; $quoteTotal = 0; $total_amount = 0; $ProductLoopId = 0; $order_id = 0; $customer_email = "";

$ProductLoopId = $_REQUEST["productIdloop"];

function addNewWareHouse_Rec($b2b_id)
{
	$sql = "SELECT * FROM companyInfo where ID = " . $b2b_id . " ";
	$result = db_query($sql, db_b2b() );

	while ($myrowsel = array_shift($result)) {
	
		if ($myrowsel["haveNeed"] == "Need Boxes"){
			$tmp_rec_type = "Supplier";
			$tmp_bs_status = "Buyer";
		}		

		if ($myrowsel["haveNeed"] == "Water"){
			$tmp_rec_type = "Water";
			$tmp_bs_status = "Water";
		}		
		
		if ($myrowsel["haveNeed"] == "Have Boxes"){
			$tmp_rec_type = "Manufacturer";
			$tmp_bs_status = "Seller";
		}		
		$tmp_company = preg_replace("/'/", "\'", $myrowsel["company"]);
		$tmp_address = preg_replace("/'/", "\'", $myrowsel["address"]);
		$tmp_address2 = preg_replace("/'/", "\'", $myrowsel["address2"]);
		$tmp_city = preg_replace("/'/", "\'", $myrowsel["city"]);
		$tmp_contact = preg_replace("/'/", "\'", $myrowsel["contact"]);
		
		$tmp_state = preg_replace("/'/", "\'", $myrowsel["state"]);
		$tmp_phone = preg_replace("/'/", "\'", $myrowsel["phone"]);
		$tmp_accounting_contact = preg_replace("/'/", "\'", $myrowsel["accounting_contact"]);
		$tmp_accounting_phone = preg_replace("/'/", "\'", $myrowsel["accounting_phone"]);
		
		//$tmp_company = preg_replace ( "/'/", "\'", $_REQUEST["company"]);
		//echo $tmp_company;
		
		$strQuery = "Insert into loop_warehouse (b2bid, company_name, company_address1, company_address2, company_city, company_state, company_zip, company_phone, company_email, company_contact, " ; 
		$strQuery = $strQuery . " warehouse_name, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_zip, " ;
		$strQuery = $strQuery . " warehouse_contact, warehouse_contact_phone, warehouse_contact_email, warehouse_manager, warehouse_manager_phone, warehouse_manager_email, " ;
		$strQuery = $strQuery . " dock_details, warehouse_notes, " ;
		$strQuery = $strQuery . " rec_type, bs_status, overall_revenue_comp, noof_location, accounting_email, accounting_contact, accounting_phone) " ;
		$strQuery = $strQuery . " values(" . $b2b_id  . ", '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
		$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '" . $tmp_phone . "', " ;
		$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '" . $tmp_contact . "', '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
		$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '', '" . $tmp_phone . "', " ;
		$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '', '', '', '', '', " ;
		$strQuery = $strQuery . " '" . $tmp_rec_type . "', '" . $tmp_bs_status . "', '" . $myrowsel["overall_revenue_comp"] . "', '" . $myrowsel["noof_location"] . "', '" . $myrowsel["accounting_email"] . "', '" . $tmp_accounting_contact . "', '" . $tmp_accounting_phone . "') " ;
		
		$res = db_query($strQuery , db());
		//echo $strQuery;
		$new_loop_id = tep_db_insert_id();
		db_query("Update companyInfo set loopid = " . $new_loop_id . " where ID = " . $b2b_id, db_b2b() );
		
		$sql = "SELECT inventory.id as b2bid FROM boxes inner join inventory on inventory.id = boxes.inventoryid where boxes.inventoryid > 0 and boxes.companyid = " . $b2b_id . " ";
		$result_box = db_query($sql, db_b2b() );

		while ($myrowsel_box = array_shift($result_box)) {
			$sql = "SELECT id FROM loop_boxes where b2b_id = " . $myrowsel_box["b2bid"] . " ";
			$result_box_loop = db_query($sql,db() );

			while ($myrowsel_box_loop = array_shift($result_box_loop)) {
				$sql = "Insert into loop_boxes_to_warehouse (loop_boxes_id, loop_warehouse_id ) SELECT '" . $myrowsel_box_loop["id"] . "', '" . $new_loop_id . "'";
				$result_box_loop_ins = db_query($sql,db() );
			}
		}
	}
}

$shippinginfo = ""; $billinginfo = ""; $payment_method = ""; $session_login_id = "";
if ($sessionId){
	
	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db() );
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$order_id = $rowContactInfo['id'];
		$ProductLoopId = $rowContactInfo['product_loopboxid'];
		
		$session_login_id = $rowContactInfo['session_login_id'];
		
		$productTotal = str_replace(",", "", trim($rowContactInfo['product_total']));
		$quoteTotal = str_replace(",", "", $rowContactInfo['quote_total']);
		//$total_amount = round($productTotal,0) + round($quoteTotal,0);
	
		$total_amount = $rowContactInfo['response_amount'];

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
		
		$orderData['hdLeadTime'] = $rowContactInfo['lead_time'];
		
		$orderData['lastInsertId'] = $rowContactInfo['id'];
		$orderData['cntInfoEmail'] = $rowContactInfo['contact_email'];
		$customer_email = $rowContactInfo['contact_email'];
		$orderData['cntInfoPhn'] = $rowContactInfo['contact_phone'];
		$orderData['cntInfoFNm'] = $rowContactInfo['contact_firstname'];
		$orderData['cntInfoLNm'] = $rowContactInfo['contact_lastname'];
		$orderData['cntInfoCompny'] = $rowContactInfo['contact_company'];
		$orderData['billingAdd1'] = $rowContactInfo['billing_add1'];
		$orderData['billingAdd2'] = $rowContactInfo['billing_add2'];
		$orderData['billingAddCity'] = $rowContactInfo['billing_city'];
		$orderData['billingAddState'] = $rowContactInfo['billing_state'];
		$orderData['billingAddZip'] = $rowContactInfo['billing_zip'];
	
		$orderData['shippingAdd1'] = $rowContactInfo['shipping_add1'];
		$orderData['shippingAdd2'] = $rowContactInfo['shipping_add2'];
		$orderData['shippingaddCity'] = $rowContactInfo['shipping_city'];
		$orderData['shippingaddState'] = $rowContactInfo['shipping_state'];
		$orderData['shippingaddZip'] = $rowContactInfo['shipping_zip'];
		$po_delivery_dt = $rowContactInfo['shippingShipDate'];
		
		//echo $po_delivery_dt;

		$sellto_name = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname']));
		$shippinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
		$shippinginfo .= $rowContactInfo['shipping_company'] . "<br>";
		$shippinginfo .= $rowContactInfo['shipping_add1'] . " " . trim($rowContactInfo['shipping_add2']) . "<br>";
		$shippinginfo .= $rowContactInfo['shipping_city'] . ", " . trim($rowContactInfo['shipping_state']) . ", " . trim($rowContactInfo['shipping_zip']) . "<br>";
		if(!empty($rowContactInfo['shipping_phone'])){
			$shippinginfo .= $rowContactInfo['shipping_phone'] . "<br>";
		}
		$shippinginfo .= $rowContactInfo['shipping_email'] . "<br>";
		$shippinginfo .= "Shipping/Receiving Hours: " . $rowContactInfo['shipping_dockhrs'] . "<br>";
		
		$billinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
		$billinginfo .= $rowContactInfo['shipping_company'] . "<br>";
		if(!empty($rowContactInfo['billing_add1'])){
			$billinginfo .= $rowContactInfo['billing_add1'] . " " . trim($rowContactInfo['billing_add2']) . "<br>";
		}
		if(!empty($rowContactInfo['billing_city'])){
			$billinginfo .= $rowContactInfo['billing_city'] . ", " . trim($rowContactInfo['billing_state']) . ", " . trim($rowContactInfo['billing_zip']) . "<br>";
		}
		if(!empty($rowContactInfo['billing_phone'])){
			$billinginfo .= $rowContactInfo['billing_phone'] . "<br>";
		}
		if(!empty($rowContactInfo['billing_email'])){
			$billinginfo .= $rowContactInfo['billing_email'] . "<br>";
		}
		
		if ($rowContactInfo['pickup_type'] == 'UCB Delivery' ){
			$shipping_method = "UCB will deliver, 3rd party";		
		}	
		if ($rowContactInfo['pickup_type'] == 'Customer Pickup' ){
			$shipping_method = "Customer Pickup";		
		}	
		
		$payment_method = $rowContactInfo['payment_method'];
		$pickup_type 	= $rowContactInfo['pickup_type'];
	}
}

$orderData['hdAvailability'] = $_REQUEST["hdAvailability"];

$distance = find_distance($ProductLoopId, $orderData['shippingaddZip']);

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


/*
if($orderData['user_master_id'] > 0 ){
	$getUserDt = db_query("SELECT companyid FROM b2becommerce_user_master WHERE userid = ".$orderData['user_master_id'], db());
	$rowUserDt = array_shift($getUserDt);
}
if(!empty($rowUserDt['companyid'])){
	$getLoopId = db_query("SELECT loopid FROM companyInfo WHERE ID = ".$rowUserDt['companyid'], db_b2b());
	$rowLoopId = array_shift($getLoopId);
	$warehouse_id = $rowLoopId['loopid'];
}

$_REQUEST["selected_id"] = $rowUserDt['companyid'];   	//'81689';
$_REQUEST["b2b_rec_id"]  = $orderData['lastInsertId'];	//order_id
$_REQUEST["page_name"]   = '';							//buyer_payment
*/

if (isset($_REQUEST["response_transId"])){		
	if ($_REQUEST["response_transId"] != ""){ ?>
		<script>
			//alert("Payment is Successful.");
		</script>
<?
		$trans_type = "";
		if ($_REQUEST["trans_type"] == "authCaptureTransaction")
		{
			$trans_type = "Authorize and Capture";
		}

		$final_amt = $_REQUEST["totalcnt_withccfee"];
		$payment_method = "Credit Card";
		$acc_owner = "Operations Team";
		$credit_term = "Prepaid (Credit Card)";
		
		if ($_REQUEST["response_transId"] == "credit_term")
		{
			$payment_method = "Credit Term";
			$final_amt = $_REQUEST["totalcnt_withoutccfee"];
			$credit_term = "";
		}

		$setLoopTrans_buyr = db_query("Update b2becommerce_order_item SET response_trans_id = '" . $_REQUEST["response_transId"] . "', payment_method = '" . $payment_method . "', credit_card_process_date = '" . date("Y-m-d H:i:s") . "', purchase_order_no = '" . $_REQUEST["txtPO"] ."' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $_REQUEST["productIdloop"] ."'", db() ); 
		
		$order_summary="<table width='95%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
		$order_summary.="<tr><td colspan=2 align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Item</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Lead Time</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Qty</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Price</td>";
		$order_summary.="<td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Total</td></tr>";
		
		$fly_txt = "";$bsize="";
			
		$lbq = "SELECT * from loop_boxes WHERE id = $ProductLoopId";
		$lb_res = db_query($lbq , db() );
				
		while ($lbrow = array_shift($lb_res)) {
			$bpic_1=$lbrow['bpic_1'];
			$bpic_2=$lbrow['bpic_2'];
			$bpic_3=$lbrow['bpic_3'];
			$bpic_4=$lbrow['bpic_4'];
		
			if (file_exists('boxpics_thumbnail/' . $bpic_1)) {							
				$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/>
				</div><br>";
			}else{
				if ($lbrow['bpic_1'] != ''){
					$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/></div><br>";
				}
			}
		}
		
		if ($_SESSION['idTitle_new'] != "") {
			$description = 'ID:' . $invId .', '. $_SESSION['idTitle_new'];
		} else {
			$description = 'ID:' . $invId .', '. $boxid_text.', '. $row_loopbox["system_description"];
		}
		
		$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>".$fly_txt."</td><td align='left' width='500px' style='width:502px;'>".$description."</td>";
		$order_summary.="<td align='right' style='white-space: nowrap;'>".$orderData['hdLeadTime']."</td><td align='right'  style='white-space: nowrap;'>".$orderData['productQnt']."</td><td align='right' style='white-space: nowrap;'>$".  number_format(str_replace(",", "" ,$orderData['productUnitPr']),2)."</td><td  align='right'  style='white-space: nowrap;'>$". number_format(str_replace(",", "" ,$productTotal),2)."</td></tr>";

		$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'>Shipping Quote</td>";
		$order_summary.="<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right'  style='white-space: nowrap;'>1</td><td align='right' style='white-space: nowrap;'>$". number_format(str_replace(",", "" ,$orderData['quoteUnitPr']),2)."</td><td  align='right'  style='white-space: nowrap;'>$". number_format(str_replace(",", "" ,$orderData['quoteTotal']),2) ."</td></tr>";
		
		if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
			$productTotal = str_replace(",", "", $orderData['productTotal']);
			$productTotal = str_replace("$", "", $productTotal);
			$total = $productTotal + str_replace(",", "", trim($orderData['quoteTotal']));
		}elseif(!empty($orderData['productTotal']) ){	
			$productTotal = str_replace(",", "", $orderData['productTotal']);
			$productTotal = str_replace("$", "", $productTotal);
			$total = $productTotal;
		}else{
			$total = "0.00";
		}
		$totalTemp =  str_replace("$", "", $total);
			
		$cc_fees = 0;
		if ($_REQUEST["response_transId"] == "credit_term"){

		}else{						
			if ($totalTemp > 0){
				$cc_fees = number_format($totalTemp * 0.03,2);
			}	
			
			//$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'>Convenience Fee (3%)</td>";
			//$order_summary.="<td align='right'  style='white-space: nowrap;'>1</td><td align='right' style='white-space: nowrap;'>$". $cc_fees ."</td><td  align='right'  style='white-space: nowrap;'>$". $cc_fees ."</td></tr>";
		}

		$totalTemp =  str_replace("$", "", $total);
		$totalTemp =  str_replace(",", "", $total);
		$cc_feesTemp =  str_replace(",", "", $cc_fees);
		$total = $totalTemp + $cc_feesTemp;
		
		$order_summary.="<tr style='border-top:1px solid #a6a6a6;'><td style='border-top:1px solid #a6a6a6; padding:5px 0px; white-space: nowrap;' colspan=5 align='right'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:600;\">Total</span></td>
		<td align='right' style='border-top:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">$".number_format($totalTemp,2)."</span></td></tr>";
		if ($_REQUEST["response_transId"] == "credit_card"){
			$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'></td><td align='left' width='500px' style='width:502px;'></td>";
			$order_summary.="<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right' style='white-space: nowrap;'>Convenience Fee (3%)<br>Total if Paid by Credit Card</td><td  align='right'  style='white-space: nowrap;'>$". number_format($cc_fees,2) ."<br>$". number_format($total,2) ."</td></tr>";
		}
		$order_summary.="</table>";
		
		if ($compnewid > 0){
			//Add record in Loop tran buyer
			$start_first_trans = "no";			
			$lbq = "SELECT b2bid FROM loop_warehouse WHERE b2bid = '". $compnewid ."'";
			$lb_res = db_query($lbq, db() );
			while ($lbrow = array_shift($lb_res)) {
				$start_first_trans = "yes";
			}
				
			if ($start_first_trans == "no")
			{	
				addNewWareHouse_Rec($compnewid);
			}				
		
			$getLoopWarehouseId = db_query( "SELECT loopid, parent_comp_id FROM companyInfo WHERE ID = '".$compnewid."'",db_b2b() );
			$rowLoopWarehouseId = array_shift($getLoopWarehouseId);
			//
			$parent_comp_id=$rowLoopWarehouseId['parent_comp_id'];
			//
			$rec_type = 'Supplier';
			$todayDate = date('m/d/y h:i a'); 
			$trans_type = 'Seller';
			$tran_status = $pickup_type;
			$user = 'AA';
			$lastLoad = 1;			
			$resLastLoad = db_query("SELECT load_number FROM loop_transaction_buyer WHERE warehouse_id = " . $rowLoopWarehouseId['loopid'] . " ORDER BY load_number DESC LIMIT 1", db() );
			if(!empty($resLastLoad)){
				while ($rowLastLoad = array_shift($resLastLoad)) {
					$lastLoad = $rowLastLoad["load_number"];
					$lastLoad = $lastLoad + 1;
				}
			}
			
			if($pickup_type == 'UCB Delivery'){
				$customerpickup_ucbdelivering_flg = 2;
			}else{
				$customerpickup_ucbdelivering_flg = 1;
			}	
			//
			if($parent_comp_id>0){
				//
				$getloopId = db_query( "SELECT loopid FROM companyInfo WHERE ID = '".$parent_comp_id."'",db_b2b() );
				$rescompid = array_shift($getloopId);
				//
				$ploopid=$rescompid["loopid"];
				$dt_view_qry = "SELECT * from loop_warehouse WHERE id= '" . $ploopid ."' AND credit_application_file != ''";

				$dt_view_res = db_query($dt_view_qry,db() );

				if (tep_db_num_rows($dt_view_res) == 0){

					$dt_view_qry = "SELECT * from loop_warehouse WHERE id= '" . $rowLoopWarehouseId['loopid'] ."' AND credit_application_file != ''";
					$dt_view_res = db_query($dt_view_qry,db() );
				}
				while ($dt_view_row = array_shift($dt_view_res)) {
					$quote_terms = $dt_view_row["credit_application_net_term"];
				}
			}
			else{
				$get_warehouse = db_query("Select credit_application_net_term from loop_warehouse where id = " . $rowLoopWarehouseId['loopid']);
				while ($warehouse_data = array_shift($get_warehouse)) {
					$quote_terms = $warehouse_data["credit_application_net_term"];
				}
			}
			
			//
			$po_poorderamount = str_replace(",", "", $totalTemp);//Need to store it without cc_fees
			
			$po_payment_method = $payment_method; 
			$po_date = date("m/d/Y");
			//
			$qry_1 = "Select assignedto from companyInfo Where ID = '" . $compnewid . "'";
			$dt_view_1 = db_query($qry_1, db_b2b() );
			while ($rows = array_shift($dt_view_1)) 
			{
				$assignedto = $rows["assignedto"];
			}
			$emp_loop_id = 0;
			$qassign = "SELECT initials, loopID FROM employees WHERE status='Active' and employeeID = '" . $assignedto . "'";
			$dt_view_res_assign = db_query($qassign ,db_b2b() );
			while ($emp_assign= array_shift($dt_view_res_assign)) {
				$po_employee = $emp_assign["initials"]; //$quote_rep_initial;
				$emp_loop_id = $emp_assign["loopID"]; 
			}	
			//
			
			db();	
			$qry_newtrans = "INSERT INTO loop_transaction_buyer (load_number, warehouse_id, rec_type, start_date, trans_type, tran_status, employee, 
			customerpickup_ucbdelivering_flg, po_poorderamount, po_employee, po_payment_method, po_date, online_order, po_file, 
			po_poterm, po_freight, po_division , po_ponumber, po_delivery_dt, notes_for_ops_team) 
			VALUES('".$lastLoad."', '".$rowLoopWarehouseId['loopid']."', '".$rec_type."', '".$todayDate."', '".$trans_type."', 'Pickup', 'Ecom', 
			'".$customerpickup_ucbdelivering_flg."', '".$po_poorderamount."', '".$po_employee."', '".$po_payment_method."', '".$po_date."', '".$order_id."', 
			'B2B online order', '" . $quote_terms . "', '" . $quoteTotal . "', '', '" . str_replace("'", "\'" ,$_REQUEST["txtPO"]) . "', '" . $po_delivery_dt . "', '" . $_REQUEST["txtnotes"] . "') ";
			//echo $qry_newtrans;
			
			$res_newtrans = db_query($qry_newtrans,db() );
			$lastTransactionId = tep_db_insert_id();

			if($lastTransactionId > 0) {  
				
				//Save original planned delivery date
				if($po_delivery_dt=="" || $po_delivery_dt=="0000-00-00")
				{
					
				}
				else{
					
					$date_log = date("Y-m-d H:i:s"); 
					//
					$sql = "UPDATE loop_transaction_buyer SET original_planned_delivery_dt = '" . $po_delivery_dt . "', planned_delivery_dt_customer_confirmed=0, dt_customer_confirmed_by='', dt_customer_confirmed_on='' WHERE id = '" . $lastTransactionId . "'";
					$result = db_query($sql,db() );
					//

					$savelog_qry = "INSERT INTO `planned_delivery_date_history` (`comp_id`, `trans_id`, `planned_delivery_dt`, `user_log`, `date_log`, `planned_delivery_dt_customer_confirmed`, `dt_customer_confirmed_by`, `dt_customer_confirmed_on`) VALUES ('', '".$lastTransactionId."', '".$po_delivery_dt."', 'Ecom', '".$date_log."',0, '', '')";
					$result = db_query($savelog_qry,db() );
				}
				//
				db_query("UPDATE b2becommerce_order_item SET is_company_set = 1, company_id = ".$compnewid.", transaction_id = ".$lastTransactionId." WHERE session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId ."'", db());
				
				//
				$notetxt="System generated log - B2B Online order has been added from B2b domain<br> Notes:".$_REQUEST["txtnotes"];
				//
				db_query("Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) 
				select '". $rowLoopWarehouseId['loopid']."', 'Supplier', '". $lastTransactionId."', '".$notetxt."', 10", db());
				
				//db_query("Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select '". $rowLoopWarehouseId['loopid']."', 'Supplier', '". $lastTransactionId."', '".$_REQUEST["txtnotes"]."', 10", db());
				//
				
				//	db_query("Insert into CRM (companyID, type, message, employee, messageDate) VALUES ( '".$compnewid."', 'note', '".$_REQUEST["txtnotes"]."', 'Ecom', '".date('m/d/Y')."')", db_b2b());
				
				$activity_details_flg = "no";
				$sql_chk = "Select unqid FROM employee_all_activity_details where employee_id = '" . $emp_loop_id . "' and entry_date = '" . date("Y-m-d") . "'";
				$result = db_query($sql_chk, db_b2b());
				while ($myrowsel = array_shift($result)) {
					$activity_details_flg = "yes";
				}
			
				if ($activity_details_flg == "yes"){
					$sql_chk = "Update employee_all_activity_details set daily_deals = daily_deals + 1, sales_po_amunt = sales_po_amunt + " . str_replace(",", "" , $po_poorderamount) . "   
					where employee_id = '" . $emp_loop_id . "' and entry_date = '" . date("Y-m-d") . "'";
					$result2 = db_query($sql_chk, db_b2b()) ;
				}else{
					$sql_chk = "Insert into employee_all_activity_details (employee_id, entry_date, daily_deals, sales_po_amunt) 
					select '" . $emp_loop_id . "', '" . date("Y-m-d") . "', 1, " . str_replace(",", "" , $po_poorderamount) . "'";
					$result2 = db_query($sql_chk, db_b2b()) ;
				}	
				
				db();

				/*set the transaction for inventory items start*/
				$sql = "SELECT loop_boxes_id FROM loop_boxes_to_warehouse WHERE loop_warehouse_id = '" . $rowLoopWarehouseId['loopid'] . "' AND loop_boxes_id = '" . $ProductLoopId . "'";
				$rec_found = "no";
				$boxes_query = db_query($sql, db());								
				while ($boxes_data = array_shift($boxes_query)){							
					$rec_found = "yes";
				}
				
				if($rec_found == "no"){			
					$sql = "INSERT INTO loop_boxes_to_warehouse SET loop_warehouse_id = '" . $rowLoopWarehouseId['loopid'] . "', loop_boxes_id = '" . $ProductLoopId . "'"; 				
					db_query($sql,db() );				
				}
			}
			
		}
		
	}//End if quote ID
	else{
		$order_summary="";
	}
	//echo $order_summary;
	
	//$eml_confirmation = "<br> ----<h1>Mail to Customer</h1> ---------------------------------------------------------------------------------------------------------------";
	$eml_confirmation ="<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
		<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
		</style><style scoped>
		.tablestyle {
		   width:800px;
		}
		table.ordertbl tr td{
			padding:4px;
		}
		@media only screen and (max-width: 768px) {
			.tablestyle {
			   width:98%;
			}
		}
		</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";
		
		$eml_confirmation .="<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";
		
		$eml_confirmation .="<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";
		
		$eml_confirmation .="<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #".$order_id."</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Thank you for your purchase! </div></td></tr>";
		
		$eml_confirmation .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Hi ".$sellto_name.", we are currently allocating the specific inventory against this order. We will notify you once the order is ready to ship.</div>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
		Please take a moment to <u>affirm</u> the order summary, shipping and billing address info for accuracy. If there are any errors, please let us know immediately. <br>Should any information be missing, we may delay shipping the order until we receive that information.</div></td></tr>";
		
		if($order_summary!="")
		{
			$eml_confirmation .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation .="<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">".$order_summary."</div></td></tr>";
		}
		//
		$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$shippinginfo."</span>
		<br><br></td></tr>";
		
		$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$billinginfo."</span>
		<br><br></td></tr>";
		
		if ($shipping_method != ""){
			$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">".$shipping_method."</span>
			<br><br></td></tr>";
		}	
		
		$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">".$payment_method."</div>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: ". $_REQUEST["txtPO"] ."</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: ".$credit_term."</div>
		<br><br></td></tr>";
		
		$eml_confirmation .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";
		
		$eml_confirmation .="<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";
		
		$eml_confirmation .="<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p>
		<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p>
		<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p>
		<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep ".$acc_owner." anytime, if you have any questions or concerns.</p>
		<p>Thank you again for Order #".$order_id." and the opportunity to work with you!</p></div></td></tr>";
		
		$signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
			$signature .= "<td><p style='font-size:13pt;color:#538135'>";
			$signature .= "<u>National Operations Team</u><br>UsedCardboardBoxes (UCB)</p>";
			$signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
			$signature .= "323-724-2500 x709<br><br>";
			$signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
			$signature .= "</td></tr></table>";
			
		$eml_confirmation .= "<br><br><tr><td>".$signature."</td></tr>";
		$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
		
		
		
		
		//$eml_confirmation2 = "<h1>Mail to UCB</h1>";
	$eml_confirmation2 ="<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
		<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
		</style><style scoped>
		.tablestyle {
		   width:800px;
		}
		table.ordertbl tr td{
			padding:4px;
		}
		@media only screen and (max-width: 768px) {
			.tablestyle {
			   width:98%;
			}
		}
		</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";
		
		$eml_confirmation2 .="<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";
		
		$eml_confirmation2 .="<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";
		
		$eml_confirmation2 .="<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #".$order_id."</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >NEW B2B ONLINE ORDER!</div></td></tr>";
		
		$eml_confirmation2 .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Please process the above online order which was captured on the B2B e-commerce store, but still needs created in loops.</div>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><a href='https://loops.usedcardboardboxes.com/b2becommerce_reports.php?order_id=".$order_id."' target='_blank'>Click Here</a> to view order.</div></td></tr>";
		
		if($order_summary!="")
		{
			$eml_confirmation2 .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation2 .="<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">".$order_summary."</div></td></tr>";
		}
		//
		$eml_confirmation2 .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$shippinginfo."</span>
		<br><br></td></tr>";
		
		$eml_confirmation2 .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$billinginfo."</span>
		<br><br></td></tr>";
		
		if ($shipping_method != ""){
			$eml_confirmation2 .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">".$shipping_method."</span>
			<br><br></td></tr>";
		}	
		
		$eml_confirmation2 .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">".$payment_method."</div>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: ". $_REQUEST["txtPO"] ."</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: ".$credit_term."</div>
		<br><br></td></tr>";
		
		$eml_confirmation2 .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";
		
		$eml_confirmation2 .="<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";
		
		$eml_confirmation2 .="<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p><br>
		<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p><br>
		<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p><br>
		<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep ".$acc_owner." anytime, if you have any questions or concerns.</p><br>
		<p>Thank you again for ONLINE Order #".$order_id." and the opportunity to work with you!</p></div></td></tr>";
		
		$eml_confirmation2 .= "<br><br><tr><td>".$signature."</td></tr>";
		$eml_confirmation2 .= "</table></td></tr></tbody></table></div></body></html>";
		
		//echo $eml_confirmation;  // mail to customer
		
		//$customer_email = "prasad@extractinfo.com";
		//$emlstatus = sendemail_attachment(null, "", $customer_email, "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $order_id . " Received" , $eml_confirmation);
		$emlstatus = sendemail_php_function(null, '', $customer_email, "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $order_id . " Received", $eml_confirmation); 
		
		//echo $eml_confirmation2; // mail to UCB
		
		//Operations@UsedCardboardBoxes.com
		//$emlstatus = sendemail_attachment(null, "", "Operations@UsedCardboardBoxes.com", "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Order ID #" . $order_id, $eml_confirmation2);
		$emlstatus = sendemail_php_function(null, '', "Operations@UsedCardboardBoxes.com", "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Order ID #" . $order_id, $eml_confirmation2); 
	/*	
	
	$eml_confirmation .="<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
	<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
	</style><style scoped>
	.tablestyle {
	   width:800px;
	}
	table.ordertbl tr td{
		padding:4px;
	}
	@media only screen and (max-width: 768px) {
		.tablestyle {
		   width:98%;
		}
	}
	</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";
	
	$eml_confirmation .="<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";
	
	$eml_confirmation .="<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";
	
	$eml_confirmation .="<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >
	B2B ONLINE ORDER #". $order_id ."</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >
	New B2B Online Order! <span style=\"font-size:12pt;color:red;\">(Action Required)</span> </div></td></tr>";
	
	$eml_confirmation .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">
	Please review the order and add the appropriate transaction into loops for it, linking it to this order.</div>
	<p><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">
	<i>As a reminder, the system does not do this automatically yet, but that will be the plan in the future.</i></div></p></td></tr>";
	
	if($order_summary!="")
	{
		$eml_confirmation .="<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

		$eml_confirmation .="<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">".$order_summary."</div></td></tr>";
	}
	
	$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
	<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$shippinginfo."</span>
	<br><br></td></tr>";
	
	$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
	<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">".$billinginfo."</span>
	<br><br></td></tr>";
	
	if ($shipping_method != ""){
		$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">".$shipping_method."</span>
		<br><br></td></tr>";
	}	
	
	$eml_confirmation .="<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
	<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">". $payment_method."</div>
	<br><br></td></tr>";
	
	$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
		
		/*
		if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
		    $productTotal = str_replace(",", "", $orderData['productTotal']);
		    $quoteTotal = str_replace(",", "", $orderData['quoteTotal']);
		    $total_amount = round($productTotal) + round($quoteTotal);
		} 
		$setLoopTrans_buyr = db_query("INSERT INTO loop_transaction_buyer SET Preorder = 1, preorder_marked_by = '" .$orderData["user_master_id"]. "', preorder_marked_on = '" . date("Y-m-d H:i:s") . "', customerpickup_ucbdelivering_flg = '2', Leaderboard = 'B2B', add_freight = '', notes_for_ops_team = '', quote_number = '', po_ponumber= '" . $orderDate['lastInsertId'] . "', po_poterm = '', po_poorderamount= '" . $total_amount . "', po_freight= '', is_preorder= '', po_division = '',  po_employee = '', po_payment_method = 'Credit Card', po_cc_number = '', po_cc_owner = '', po_cc_expiration = '', po_cc_cvv = '',  po_source = '', po_date = '" .date("m/d/Y"). "'", db() ); 
		$lastInsertedId = tep_db_insert_id();
		$_REQUEST["rec_id"]  = $lastInsertedId;

		$qry = "INSERT INTO loop_transaction_buyer_cc SET trans_rec_id = " . $_REQUEST["rec_id"] . ", transaction_type = '" . $trans_type . "' , transaction_id = '" . $_REQUEST["response_transId"] . "', b2b_employee_id = " . $orderData["user_master_id"] . ", amount = '"  . $_REQUEST["response_amt"] ."' , b2b_trans_rec_d = '".$_REQUEST['b2b_rec_id']."'";
		$res_newtrans = db_query($qry, db());		

		if ($_REQUEST["trans_type"] == "authCaptureTransaction") {
			$qry = "Update loop_transaction_buyer SET trans_status = 4 where id = " . $_REQUEST["rec_id"] . "";
			$res_newtrans = db_query($qry, db());			
			
			$cc_fees = amt_roundup($_REQUEST["response_amt"]*0.03,2);
			$sql_ins = "INSERT INTO loop_transaction_buyer_payments (`transaction_buyer_id` ,`company_id` ,`typeid`  ,`fileid` ,`employee_id` ,`date` ,`estimated_cost` ,`status` ,`notes` ,`notes2` ) VALUES ( ";
			$sql_ins .= " '". $_REQUEST['rec_id'] ."', '232', '8', '0', '" . $_COOKIE["employeeid"] . "', '". date("Y-m-d H:i:s") ."', '". $cc_fees ."', 6, '', '')";
			$result = db_query($sql_ins, db());
			
			//added the code to update the invoice_paid flag
			$to_chk_amount=0;
			$inv_qry = "SELECT * FROM loop_buyer_payments WHERE trans_rec_id = " . $_REQUEST['rec_id'] ;
			$inv_res = db_query($inv_qry, db() );
			while ($inv_row = array_shift($inv_res)) {
				$to_chk_amount += $inv_row["amount"];
			}

			$to_chk_invoice_amt=0;
			$inv_qry = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $_REQUEST['rec_id'] . " ORDER BY id ASC";
			$inv_res = db_query($inv_qry , db());
			while ($inv_row = array_shift($inv_res)) {
				$to_chk_invoice_amt += $inv_row["quantity"]*$inv_row["price"];
			}
			
			db_query("Update loop_transaction_buyer set invoice_paid = 0 where id = " . $_REQUEST['rec_id'], db());
			if ($to_chk_amount > 0 && $to_chk_invoice_amt > 0) {
				if ($to_chk_amount >= $to_chk_invoice_amt) {
					db_query("Update loop_transaction_buyer set invoice_paid = 1 where id = " . $_REQUEST['rec_id'], db());
				}
			}
			
		}
		
		$warehouse_id = 0;
		$sql1 = "Select loop_warehouse.id, loop_warehouse.company_name from loop_warehouse inner join loop_transaction_buyer on loop_transaction_buyer.warehouse_id = loop_warehouse.id where loop_transaction_buyer.id = " . $_REQUEST["rec_id"];
		$warehousedet = db_query($sql1, db());
		$warehousenm = "";
		while ($dt_view_row = array_shift($warehousedet)) {
			$warehouse_id = $dt_view_row["id"];
			$warehousenm = $dt_view_row["company_name"];
		}

		if ($_REQUEST["trans_type"] == "captureOnlyTransaction" || $_REQUEST["trans_type"] == "authCaptureTransaction") {
			$msg_trans = "System generated log - CC Captured on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'];
			db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $warehouse_id . "', 'Supplier' , '" . $_REQUEST["rec_id"] . "', '" . $msg_trans . "', '" . $_COOKIE['employeeid'] . "'", db());			
		}
		
		$emlstatus = sendemail_attachment(null, "", "nayan.dhoke@extractinfo.com,", " ", "", "admin@usedcardboardboxes.com", "Admin UCB","", "B2B ecommerce # " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='http://localhost/B2B_responsive/authorizenet_frm_action.php?ID=".$_REQUEST['ID']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );
		*/
		//$emlstatus = sendemail_attachment(null, "", "davidkrasnow@usedcardboardboxes.com,", "bk@mooneem.com,creditcard@usedcardboardboxes.com", "", "admin@usedcardboardboxes.com", "Admin UCB","", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$_REQUEST['ID']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );
			
}


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
		
	<link rel="stylesheet" type="text/css" href="stylesheet.css">

	<!-- Payment UI css/js start -->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="CSS/style.css">
	<link rel="stylesheet" type="text/css" href="CSS/payment.css">
	
	<link rel="stylesheet" href="product-slider/slick.css">
	<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
	<link rel="stylesheet" href="product-slider/prod-style.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<!-- Payment UI css/js end -->

<title>B2B Ecommerce - Payment Confirmation - Usedcardboardboxes</title>
<script type="text/javascript">

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
<?php require_once('boomerange_common_header.php'); ?>
	<div class="sections">
		<div class="new_container no-top-padding">
			<div class="parentdiv">
			<div class="innerdiv">
			<div class="section-top-margin_1">
				<h1 class="section-title"><?=$pgTitle;?></h1>
				<div class="title_desc">Order has been placed</div>
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
				<li class="breadcrumb__item breadcrumb__item--completed">
					<a class="breadcrumb__link" href="shipping.php?id=<? echo urlencode(encrypt_password($ProductLoopId));?>">Shipping</a>
					<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false"> <use xlink:href="#chevron-right"></use> </svg>
				</li>
				  <li class="breadcrumb__item breadcrumb__item--current" aria-current="step">
				  <span class="breadcrumb__text">Payment</span>
				</li>
				</ol>
		  	</nav>
			<!--End Breadcrums-->
			<div class="content-div content-padding-big">
				<div class="left_form">
					<div class="div-space"></div>
					<!-- <form name="frmpayform" id="frmpayform" action=""> -->
						<div class="frmsection">
							<!-- <div class="section__content">
								<? if ($_REQUEST["response_transId"] == "credit_term") {
										echo "Payment will be processed using Credit Term<br><br><b>Order id: " .$order_id ."</b><br><br> We will follow up with you after your order is placed to confirm payment details, whether that is using credit terms you already have approved, or helping you get setup with credit terms.<br><br>";
								   }else{?>
										Payment will be charged by Credit Card<br><br>
										<b>Order id:&nbsp;<? echo $order_id; ?></b><br><br>
									<?
										//echo "Payment Transaction ID : " . $_REQUEST["response_transId"];
									?>
								<? }?>
							</div> -->
							
							<!-- 
							<div class="btn-div-shipping content-bottom-padding"></div> -->
							<div class="frm-txt"><div class="frm-txt-shipping"><h2>Order Complete!</h2> <br>Your Order ID is #<? echo $order_id; ?></div></div>
							<div class="div-space"></div>
							<div class="section__content leftalgn">
								UCB's National Operations Team will now allocate the specific inventory against this order. The next steps will include preparing your order for shipment and booking the freight for pickup and delivery. To ensure a smooth transaction, we'll be communicating with you every step of the way!
							</div>
							<div class="div-space"></div>
							<div class="section__content leftalgn">
								Next step is UCB will be confirming availability and pickup appointments with the shipper location, and our Operations Team will be reaching out to you to schedule delivery date and time.
							</div>
							<div class="div-space"></div>
							<div class="section__content leftalgn"><b>Logistics Notes: </b></div>

							<div class="div-space"></div>
							<div class="section__content leftalgn">
								As previously stated and acknowledged, you will need a loading dock and forklift to unload the delivered trailer. Any costs incurred by UCB due to not having a forklift or a loading dock will be charged to the same card or credit terms used for this order.
							</div>
							<div class="div-space"></div>
							<div class="section__content leftalgn">
								In the meantime and as always, please feel free to contact UCB's Operations Team (Operations@UsedCardboardBoxes.com), if you have any questions or concerns.
							</div>
							<div class="div-space"></div>
							<div class="section__content leftalgn">
								<div>Thank you again for Order #<b><? echo $order_id; ?></b> and the opportunity to work with you! </div>
							
							<?  /* ?>
							</div>
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
		<? */ ?>
			<div class="inner">
				<div class="collapsible"><div class="show-order" id="showorder">Show order summary
					<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>
					
					</div>
				<div class="show-order-total">
					<?
					if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
						$total = str_replace(",", "", $orderData['productTotal']) + str_replace(",", "", $orderData['quoteTotal']);
					}else{
						$total = "0.00";
					}
					echo "$". $total;
					?>
				</div>
				</div>
				
				<div class="inner-content-shipping" id="order-content">
					<? require('item_sections.php'); ?>
				
				<div class="sidebar-sept"></div>
					<div class="table_mob_div">
					<table class="sidebar-table">
						<tr>
							<th align="left">Truckload</th>
							<th align="right">Lead Time</th>
							<th align="center">Quantity</th>
							<th align="right">Price/Unit</th>
							<th align="right">Total</th>
						</tr>
						
						<tr>
							<td><? echo $orderData['productName'] ; ?></td>
							<td align="right"><? echo $orderData['hdLeadTime'] ; ?></td>
							<td align="right"><? echo number_format(str_replace(",", "" ,$orderData['productQnt']),0) ; ?></td>
							<td align="right">$<? echo number_format(str_replace(",", "" ,$orderData['productUnitPr']),2) ; ?></td>
							<td align="right">$<? echo number_format(str_replace(",", "" ,$orderData['productTotal']),2); ?></td>
						</tr>
						<tr>
							<td><? if(!empty($orderData['quoteName']) ){ 
									echo $orderData['quoteName'];
								} ?>
							</td>
							<td align="right">&nbsp;
								
							</td>
							<td align="right">
								<? if(empty($orderData['quoteQty']) ){ 
									echo '0'; 
								}else { 
									echo number_format($orderData['quoteQty'],0);
								} ?>
							</td>
							<td align="right">
								<? if(empty($orderData['quoteUnitPr'] ) ){ 
									echo '$0.00'; 
								}else { 
									echo "$".number_format(str_replace(",", "" ,$orderData['quoteUnitPr']),2);
								} ?>
							</td>
							<td align="right">
								<? if(empty($orderData['quoteTotal']) ){ 
									echo '$0.00'; 
								}else { 
									echo "$".number_format(str_replace(",", "" ,$orderData['quoteTotal']),2);
								} ?>
							</td>
						</tr>
							<?
							if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
								$productTotal = str_replace(",", "", $orderData['productTotal']);
								$productTotal = str_replace("$", "", $productTotal);
								$total = $productTotal + str_replace(",", "", trim($orderData['quoteTotal']));
							}elseif(!empty($orderData['productTotal']) ){	
								$productTotal = str_replace(",", "", $orderData['productTotal']);
								$productTotal = str_replace("$", "", $productTotal);
								$total = $productTotal;
							}else{
								$total = "0.00";
							}
							$totalTemp =  str_replace("$", "", $total);
								
							?>
							<?
							/*if(!empty($orderData['productTotal']) && !empty($orderData['quoteTotal']) ){
								$productTotal = str_replace(",", "", $orderData['productTotal']);
								$total = $productTotal + str_replace(",", "", $orderData['quoteTotal']);
							}else{
								$total = "0.00";
							}*/
						if ($_REQUEST["response_transId"] == "credit_term"){

						}else{						
							$cc_fees = 0;
							if ($totalTemp > 0){
								$cc_fees = number_format($totalTemp * 0.03,2);
							}	
							?>						
						<? } ?>
						<tr><td colspan="5"><div class="sidebar-sept-intable"></div></td></tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td style="font-weight: 500;"  align="right">Total</td>
							<td align="right">
								<span class="payment-due__price">
									<?
									$totalTemp =  str_replace("$", "", $total);
									$totalTemp =  str_replace(",", "", $total);
									$cc_feesTemp =  str_replace(",", "", $cc_fees);
									$total = $totalTemp + $cc_feesTemp;
									echo "$". number_format($totalTemp,2);
									
									$setLoopTrans_buyr = db_query("Update b2becommerce_order_item SET response_amount = '" . $total . "' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $_REQUEST["productIdloop"] ."'", db() ); 
									?>
								</span>
							</td>
						</tr>
						
						<?
						if ($_REQUEST["response_transId"] == "credit_card"){
							?>						
							<tr>
								<td></td>
								<td></td>
								<td align="right" colspan="2">Convenience Fee (3%)<br>Total if Paid by Credit Card</td>
								<td align="right">$<? echo $cc_fees ; ?><br><? echo "$". number_format($total,2); ?> </td>
							</tr>
						<? } ?>
					</table>
					</div>
					
					<div style="padding-top: 60px;">
						<ol class="name-values" style="width: 100%;">
								<li>                    
									<label for="about">Sell To Contact</label>
									<span id="about">
										<? if(!empty($orderData['cntInfoFNm']) || !empty($orderData['cntInfoLNm']) ){ 
											echo $orderData['cntInfoFNm'] ." ".$orderData['cntInfoLNm']; 
										} ?><? if(!empty($orderData['cntInfoCompny'])){ 
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
											echo ", ".strtoupper($orderData['shippingaddState']); 
										} ?><? if( !empty($orderData['shippingaddZip']) ){ 
											echo ", ".$orderData['shippingaddZip']; 
										} ?>
										<br />
										<span id="Span1" class="caltxt"><? echo number_format($distance,0);?> mi from item origin</span>
									</span>
								</li>
								<li>
									<label for="Span2">Payment Info</label>
									<span id="Span2">
										<? 
										if ($_REQUEST["response_transId"] == "credit_term") { 
											echo 'Credit Term';
										}else{ 
											 //NOTE: here if we get cc number then need to print last 4 digit of cc
											echo 'Credit Card';
										} 
										?>
									</span>
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
		
		
		
		
		
		
		
			</div>
		</div>
	</div>
<?

	tep_session_unregister('productData');
	tep_session_unregister('orderData');
	tep_session_destroy();

	session_regenerate_id();	
?>

</body>
</html>