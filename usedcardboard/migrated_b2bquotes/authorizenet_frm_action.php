<?php
//Confrimation Page start here
if (session_id() == '') {
	session_start();
}
$sessionId = session_id();
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
require("cal_functions.php");

//Code to send mail
require '../phpmailer/PHPMailerAutoload.php';

function addNewWareHouse_Rec(string $b2b_id): void
{
	$sql = "SELECT * FROM companyInfo where ID = " . $b2b_id . " ";
	db_b2b();
	$result = db_query($sql);

	while ($myrowsel = array_shift($result)) {
		$tmp_bs_status = "";
		$tmp_rec_type = "";
		if ($myrowsel["haveNeed"] == "Need Boxes") {
			$tmp_rec_type = "Supplier";
			$tmp_bs_status = "Buyer";
		}

		if ($myrowsel["haveNeed"] == "Water") {
			$tmp_rec_type = "Water";
			$tmp_bs_status = "Water";
		}

		if ($myrowsel["haveNeed"] == "Have Boxes") {
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

		$strQuery = "Insert into loop_warehouse (b2bid, company_name, company_address1, company_address2, company_city, company_state, company_zip, company_phone, company_email, company_contact, ";
		$strQuery = $strQuery . " warehouse_name, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_zip, ";
		$strQuery = $strQuery . " warehouse_contact, warehouse_contact_phone, warehouse_contact_email, warehouse_manager, warehouse_manager_phone, warehouse_manager_email, ";
		$strQuery = $strQuery . " dock_details, warehouse_notes, ";
		$strQuery = $strQuery . " rec_type, bs_status, overall_revenue_comp, noof_location, accounting_email, accounting_contact, accounting_phone) ";
		$strQuery = $strQuery . " values(" . $b2b_id  . ", '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', ";
		$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '" . $tmp_phone . "', ";
		$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '" . $tmp_contact . "', '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', ";
		$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '', '" . $tmp_phone . "', ";
		$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '', '', '', '', '', ";
		$strQuery = $strQuery . " '" . $tmp_rec_type . "', '" . $tmp_bs_status . "', '" . $myrowsel["overall_revenue_comp"] . "', '" . $myrowsel["noof_location"] . "', '" . $myrowsel["accounting_email"] . "', '" . $tmp_accounting_contact . "', '" . $tmp_accounting_phone . "') ";
		db();
		$res = db_query($strQuery);
		//echo $strQuery;
		$new_loop_id = tep_db_insert_id();
		db_b2b();
		db_query("Update companyInfo set loopid = " . $new_loop_id . " where ID = " . $b2b_id);

		$sql = "SELECT inventory.id as b2bid FROM boxes inner join inventory on inventory.id = boxes.inventoryid where boxes.inventoryid > 0 and boxes.companyid = " . $b2b_id . " ";
		db_b2b();
		$result_box = db_query($sql);

		while ($myrowsel_box = array_shift($result_box)) {
			$sql = "SELECT id FROM loop_boxes where b2b_id = " . $myrowsel_box["b2bid"] . " ";
			db();
			$result_box_loop = db_query($sql);

			while ($myrowsel_box_loop = array_shift($result_box_loop)) {
				$sql = "Insert into loop_boxes_to_warehouse (loop_boxes_id, loop_warehouse_id ) SELECT '" . $myrowsel_box_loop["id"] . "', '" . $new_loop_id . "'";
				db();
				$result_box_loop_ins = db_query($sql);
			}
		}
	}
}

$lead_time_stored_val = "";

if (isset($_REQUEST['quote_id'])) {
	$quote_id_tmp = decrypt_password($_REQUEST['quote_id']);
	$quote_id = (int)$quote_id_tmp - 3770;

	//$quote_id = $_REQUEST['quote_id_tmp'];
	//$quote_id_tmp = $quote_id + 3770;

	$productTotal = 0;
	$quoteTotal = 0;
	$total_amount = 0;
	$ProductLoopId = 0;
	$order_id = 0;
	$customer_email = "";
	$lastTransactionId = "";
	$total = 0;
	db();
	$getOrderId = db_query("SELECT id AS OrderId FROM b2becommerce_order_item WHERE quote_id ='" . $quote_id . "' AND session_id = '" . $sessionId . "'");
	$rowOrderId = array_shift($getOrderId);

	$b2becomm_OrderId = $rowOrderId['OrderId'];
	db();
	$getOrderedProd = db_query("SELECT * FROM b2becommerce_order_item_details WHERE order_item_id = '" . $rowOrderId['OrderId'] . "'");

	$arrProdDt = array();
	$po_freight = 0;
	while ($rowsOrderedProd = array_shift($getOrderedProd)) {
		//echo "nyn<pre>"; print_r($getOrderedProd); echo "</pre>";
		$arrProdDt[$rowsOrderedProd['id']]['productIdloop'] 	= $rowsOrderedProd['product_id'];
		$arrProdDt[$rowsOrderedProd['id']]['productQntypeid'] 	= $rowsOrderedProd['product_name_id'];
		$arrProdDt[$rowsOrderedProd['id']]['productQntype'] 	= $rowsOrderedProd['product_name'];
		$arrProdDt[$rowsOrderedProd['id']]['productQnt'] 		= $rowsOrderedProd['product_qty'];
		$arrProdDt[$rowsOrderedProd['id']]['productQntprice'] 	= $rowsOrderedProd['product_unitprice'];
		$arrProdDt[$rowsOrderedProd['id']]['productTotal'] 		= $rowsOrderedProd['product_total'];
		$arrProdDt[$rowsOrderedProd['id']]['hdAvailability'] 	= $rowsOrderedProd['product_availability'];
		$arrProdDt[$rowsOrderedProd['id']]['hdLeadTime'] 	    = $rowsOrderedProd['product_lead_time'];
		$arrProdDt[$rowsOrderedProd['id']]['item_id'] 			= $rowsOrderedProd['product_item_id'];

		$lead_time_stored_val = $rowsOrderedProd['product_availability'];

		$loop_b2b_id = 0;
		db();
		$getemp = db_query("SELECT b2b_id FROM loop_boxes WHERE id = '" . $rowsOrderedProd['product_id'] . "'");
		while ($rowemp = array_shift($getemp)) {
			$loop_b2b_id = $rowemp["b2b_id"];
		}
		db_b2b();
		$getemp = db_query("SELECT item_id FROM quote_to_item WHERE quote_id = '" . $quote_id . "'");
		while ($rowemp = array_shift($getemp)) {
			db_b2b();
			$getemp1 = db_query("SELECT inventoryID, shipfinal, quantity, item, salePrice FROM boxes WHERE ID = '" . $rowemp["item_id"] . "'");
			//echo "SELECT inventoryID, shipfinal, quantity, item, salePrice FROM boxes WHERE ID = '". $rowemp["item_id"] ."' <br>";
			while ($rowemp1 = array_shift($getemp1)) {
				if (($loop_b2b_id == $rowemp1["inventoryID"]) && ($rowsOrderedProd['product_qty'] == $rowemp1["quantity"])) {
					$po_freight = $po_freight + $rowemp1["shipfinal"];
				}

				if ($rowemp1["item"] == "Delivery") {
					$po_freight = $rowemp1["salePrice"];
				}
			}
		}
	}
	db_b2b();
	$getQuoteShipping = db_query("SELECT free_shipping FROM quote WHERE ID = " . $quote_id);
	$rowQuoteShipping = array_shift($getQuoteShipping);
	if ($rowQuoteShipping['free_shipping'] == 1) {
		$arrProdDt[$rowsOrderedProd['product_id']]['productIdloop'] 	= 0;
		$arrProdDt[$rowsOrderedProd['product_id']]['productQntypeid'] 	= "Delivery";
		$arrProdDt[$rowsOrderedProd['product_id']]['productQntype'] 	= "Delivery";
		$arrProdDt[$rowsOrderedProd['product_id']]['productQnt'] 		= 1;
		$arrProdDt[$rowsOrderedProd['product_id']]['productQntprice'] 	= 0;
		$arrProdDt[$rowsOrderedProd['product_id']]['productTotal'] 		= 0;
		$arrProdDt[$rowsOrderedProd['product_id']]['hdAvailability'] 	= "";
		$arrProdDt[$rowsOrderedProd['product_id']]['hdLeadTime'] 	= "";
	}

	$shippinginfo = "";
	$billinginfo = "";
	$payment_method = "";
	$pickup_type = "";
	$po_delivery_dt = "";
	$shipping_method = "";
	$sellto_name = "";
	$orderData = array();
	if ($sessionId) {
		db();
		$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '" . $sessionId . "' and quote_id = '" . $quote_id . "'");
		while ($rowContactInfo = array_shift($getSessionDt)) {
			$order_id = $rowContactInfo['id'];

			$orderData['cntInfoEmail'] 		= $rowContactInfo['contact_email'];
			$customer_email 				= $rowContactInfo['contact_email'];
			$orderData['cntInfoPhn'] 		= $rowContactInfo['contact_phone'];
			$orderData['cntInfoFNm'] 		= $rowContactInfo['contact_firstname'];
			$orderData['cntInfoLNm'] 		= $rowContactInfo['contact_lastname'];
			$orderData['cntInfoCompny'] 	= $rowContactInfo['contact_company'];

			$orderData['billingAdd1'] 		= $rowContactInfo['billing_add1'];
			$orderData['billingAdd2'] 		= $rowContactInfo['billing_add2'];
			$orderData['billingAddCity'] 	= $rowContactInfo['billing_city'];
			$orderData['billingAddState'] 	= $rowContactInfo['billing_state'];
			$orderData['billingAddZip'] 	= $rowContactInfo['billing_zip'];

			$orderData['shippingAdd1'] 		= $rowContactInfo['shipping_add1'];
			$orderData['shippingAdd2'] 		= $rowContactInfo['shipping_add2'];
			$orderData['shippingaddCity'] 	= $rowContactInfo['shipping_city'];
			$orderData['shippingaddState'] 	= $rowContactInfo['shipping_state'];
			$orderData['shippingaddZip'] 	= $rowContactInfo['shipping_zip'];
			$po_delivery_dt                 = $rowContactInfo['shippingShipDate'];

			$sellto_name = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname']));
			$shippinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_company'] . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_add1'] . " " . trim($rowContactInfo['shipping_add2']) . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_city'] . ", " . trim($rowContactInfo['shipping_state']) . ", " . trim($rowContactInfo['shipping_zip']) . "<br>";
			if (!empty($rowContactInfo['shipping_phone'])) {
				$shippinginfo .= $rowContactInfo['shipping_phone'] . "<br>";
			}
			$shippinginfo .= $rowContactInfo['shipping_email'] . "<br>";
			$shippinginfo .= "Shipping/Receiving Hours: " . $rowContactInfo['shipping_dockhrs'] . "<br>";

			$billinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
			$billinginfo .= $rowContactInfo['shipping_company'] . "<br>";
			if (!empty($rowContactInfo['billing_add1'])) {
				$billinginfo .= $rowContactInfo['billing_add1'] . " " . trim($rowContactInfo['billing_add2']) . "<br>";
			}
			if (!empty($rowContactInfo['billing_city'])) {
				$billinginfo .= $rowContactInfo['billing_city'] . ", " . trim($rowContactInfo['billing_state']) . ", " . trim($rowContactInfo['billing_zip']) . "<br>";
			}
			if (!empty($rowContactInfo['billing_phone'])) {
				$billinginfo .= $rowContactInfo['billing_phone'] . "<br>";
			}
			if (!empty($rowContactInfo['billing_email'])) {
				$billinginfo .= $rowContactInfo['billing_email'] . "<br>";
			}

			if ($rowContactInfo['pickup_type'] == 'UCB Delivery') {
				$shipping_method = "UCB will deliver, 3rd party";
			}
			if ($rowContactInfo['pickup_type'] == 'Customer Pickup') {
				$shipping_method = "Customer Pickup";
			}

			$payment_method = $rowContactInfo['payment_method'];
			$pickup_type 	= $rowContactInfo['pickup_type'];
		}
	}

	$arrProdLoopId = $arrAvailability =  array();
	//echo "<pre>"; print_r($arrProdDt); echo "</pre>"; exit();
	foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
		$arrProdLoopId[] = $arrProdDtV['productIdloop'];
		$arrAvailability[$arrProdDtV['productIdloop']] = $arrProdDtV['item_id'];
		$total = $total + $arrProdDtV['productTotal'];
	}
	//echo "<pre>"; print_r($arrProdLoopId); echo "</pre>"; exit();


	$distance = find_distance($arrProdLoopId[0], $orderData['shippingaddZip']);

	$qry_loopbox = "SELECT * FROM loop_boxes WHERE id = '" . $arrProdLoopId[0] . "'";
	db();
	$res_loopbox = db_query($qry_loopbox);
	$row_loopbox = array_shift($res_loopbox);
	$id2 = $row_loopbox["b2b_id"];

	$qryb2b = "SELECT * FROM inventory WHERE id = '" . $id2 . "'";
	db_b2b();
	$resb2b = db_query($qryb2b);
	$rowb2b = array_shift($resb2b);

	$box_type = $rowb2b["box_type"];

	$browserTitle 	= get_b2bEcomm_boxType_BasicDetails($box_type, 1);
	$pgTitle 		= get_b2bEcomm_boxType_BasicDetails($box_type, 2);
	$idTitle		= get_b2bEcomm_boxType_BasicDetails($box_type, 3);
	$boxid_text		= get_b2bEcomm_boxType_BasicDetails($box_type, 8);

	$data_already_added = "no";
	db();
	$getSessionDt = db_query("SELECT id FROM loop_transaction_buyer WHERE quote_number = '" . $quote_id . "'");
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$data_already_added = "yes";
	}

	//echo "response_transId - " . $_REQUEST["response_transId"];
	if (isset($_REQUEST["response_transId"])) {
		$quote_rep_email = "";
		$acc_owner = "Operations Team";
			$credit_term = "Prepaid (Credit Card)";
		if ($_REQUEST["response_transId"] != "" && $data_already_added == "no") { ?>
			<script>
				//alert("Payment is Successful.");
			</script>
		<?php
			$trans_type = "";
			if ($_REQUEST["trans_type"] == "authCaptureTransaction") {
				$trans_type = "Authorize and Capture";
			}

			$final_amt = $_REQUEST["totalcnt_withccfee"];
			$payment_method = "Credit Card";
			

			if ($_REQUEST["response_transId"] == "credit_term") {
				$payment_method = "Credit Term";
				$final_amt = $_REQUEST["totalcnt_withoutccfee"];
				$credit_term = "";
			}
			db();
			$setLoopTrans_buyr = db_query("UPDATE b2becommerce_order_item SET response_trans_id = '" . $_REQUEST["response_transId"] . "', payment_method = '" . $payment_method . "', credit_card_process_date = '" . date("Y-m-d H:i:s") . "', purchase_order_no = '" . str_replace("'", "\'", $_REQUEST["txtPO"]) . "' WHERE session_id = '" . $sessionId . "' and quote_id = '" . $quote_id . "'");

			/*Update quote status and make new transaction start*/
			db_b2b();
			$updtQuoteStatus = db_query("UPDATE quote SET qstatus = 8, online_order = 1 WHERE ID=" . $quote_id);
			db_b2b();
			$getCompId = db_query("SELECT companyID, rep, terms, notes FROM quote WHERE ID = " . $quote_id);
			//echo "SELECT companyID, rep, terms FROM quote WHERE ID = ". $quote_id;
			$rowCompID = array_shift($getCompId);
			$quote_rep = $rowCompID['rep'];
			$quote_terms = $rowCompID['terms'];
			$quote_notes = $rowCompID['notes'];

			$add_freight = "N";
			db_b2b();
			$getemp = db_query("SELECT total FROM quote_to_item WHERE quote_id = '" . $quote_id . "' and item = 'Delivery' and total > 0");
			while ($rowemp = array_shift($getemp)) {
				$add_freight = "Y";
			}

			$quote_rep_initial = "";
			db_b2b();
			$getemp = db_query("SELECT name, initials, email FROM employees WHERE employeeID = '" . $quote_rep . "'");
			while ($rowemp = array_shift($getemp)) {
				$quote_rep_initial = $rowemp["initials"];
				$quote_rep_email = $rowemp["email"];
			}

			$start_first_trans = "no";
			$lbq = "SELECT b2bid FROM loop_warehouse WHERE b2bid = '" . $rowCompID['companyID'] . "'";
			db();
			$lb_res = db_query($lbq);
			while ($lbrow = array_shift($lb_res)) {
				$start_first_trans = "yes";
			}

			if ($start_first_trans == "no") {
				addNewWareHouse_Rec($rowCompID['companyID']);
			}
			db_b2b();
			$getLoopWarehouseId = db_query("SELECT loopid FROM companyInfo WHERE ID = '" . $rowCompID['companyID'] . "'");
			$rowLoopWarehouseId = array_shift($getLoopWarehouseId);
			$rec_type = 'Supplier';
			$todayDate = date('m/d/y h:i a');
			$trans_type = 'Seller';
			$tran_status = $pickup_type;
			$user = 'AA';
			$lastLoad = 1;
			db();
			$resLastLoad = db_query("SELECT load_number FROM loop_transaction_buyer WHERE warehouse_id = " . $rowLoopWarehouseId['loopid'] . " ORDER BY load_number DESC LIMIT 1");
			if (!empty($resLastLoad)) {
				while ($rowLastLoad = array_shift($resLastLoad)) {
					$lastLoad = $rowLastLoad["load_number"];
					$lastLoad = $lastLoad + 1;
				}
			}
			if ($pickup_type == 'UCB Delivery') {
				$customerpickup_ucbdelivering_flg = 2;
			} else {
				$customerpickup_ucbdelivering_flg = 1;
			}

			$order_summary = "<table width='95%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
			$order_summary .= "<tr><td colspan=2 align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Item</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Lead Time</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Qty</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Price</td>";
			$order_summary .= "<td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Total</td></tr>";

			$bsize = "";
			$total = 0;
			foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
				$lbq = "SELECT * FROM loop_boxes WHERE id = '" . $arrProdDtV['productIdloop'] . "'";
				db();
				$lb_res = db_query($lbq);

				$fly_txt = "";
				while ($lbrow = array_shift($lb_res)) {
					$id2 = $lbrow["b2b_id"];
					$systemDesc = $lbrow["system_description"];
					$bpic_1 = $lbrow['bpic_1'];
					$bpic_2 = $lbrow['bpic_2'];
					$bpic_3 = $lbrow['bpic_3'];
					$bpic_4 = $lbrow['bpic_4'];

					if (file_exists('boxpics_thumbnail/' . $bpic_1)) {
						$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/>
						</div><br>";
					} else {
						if ($lbrow['bpic_1'] != '') {
							$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/></div><br>";
						}
					}
				}
				$qryb2b = "SELECT * FROM inventory WHERE id = '" . $id2 . "'";
				db_b2b();
				$resb2b = db_query($qryb2b);
				$rowb2b = array_shift($resb2b);

				$box_type = $rowb2b["box_type"];
				$boxid_text		= get_b2bEcomm_boxType_BasicDetails($box_type, 8);
				$description = $arrProdDtV['productQntype'];

				$order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>" . $fly_txt . "</td><td align='left' width='500px' style='width:502px;'>" . $description . "</td>";
				$order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $arrProdDtV['hdLeadTime'] . "</td><td align='right'  style='white-space: nowrap;'>" . $arrProdDtV['productQnt'] . "</td><td align='right' style='white-space: nowrap;'>$" .  number_format((float)str_replace(",", "", $arrProdDtV['productQntprice']), 2) . "</td><td  align='right'  style='white-space: nowrap;'>$" . number_format((float)str_replace(",", "", $arrProdDtV['productTotal']), 2) . "</td></tr>";
				$total = $total + $arrProdDtV['productTotal'];
			}

			$cc_fees = 0;
			if ($_REQUEST["response_transId"] == "credit_term") {
			} else {
				if ($total > 0) {
					$cc_fees = (float)str_replace(",", "", number_format($total, 2)) * 0.03;
				}

				//$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'>Convenience Fee (3%)</td>";
				//$order_summary.="<td align='right'  style='white-space: nowrap;'>1</td><td align='right' style='white-space: nowrap;'>$". number_format($cc_fees,2) ."</td><td  align='right'  style='white-space: nowrap;'>$". number_format($cc_fees,2) ."</td></tr>";
			}
			// commented the code as per Zac request 
			$finalTotal = $total + $cc_fees;

			$order_summary .= "<tr style='border-top:1px solid #a6a6a6;'><td style='border-top:1px solid #a6a6a6; padding:5px 0px; white-space: nowrap;' colspan=5 align='right'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:600;\">Total</span></td>
			<td align='right' style='border-top:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">$" . number_format($total, 2) . "</span></td></tr>";
			if ($_REQUEST["response_transId"] == "credit_card") {
				$order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'></td><td align='left' width='500px' style='width:502px;'></td>";
				$order_summary .= "<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right' style='white-space: nowrap;'>Convenience Fee (3%)<br>Total if Paid by Credit Card</td><td  align='right'  style='white-space: nowrap;'>$" . number_format($cc_fees, 2) . "<br>$" . number_format($finalTotal, 2) . "</td></tr>";
			}
			$order_summary .= "</table>";

			//$po_poorderamount = str_replace(",", "", $finalTotal);
			$po_poorderamount = str_replace(",", "", strval($total)); //Need to store it without cc_fees
			$po_employee = $quote_rep_initial;
			$po_payment_method = $payment_method;
			$po_date = date("m/d/Y");

			$qry_newtrans = "INSERT INTO loop_transaction_buyer (load_number, warehouse_id, rec_type, start_date, trans_type, tran_status, employee, 
			customerpickup_ucbdelivering_flg, po_poorderamount, po_employee, po_payment_method, po_date, online_order, po_file, 
			quote_number, po_poterm, po_freight, po_division , po_ponumber, notes_for_ops_team, add_freight, po_delivery_dt) 
			VALUES('" . $lastLoad . "', '" . $rowLoopWarehouseId['loopid'] . "', '" . $rec_type . "', '" . $todayDate . "', '" . $trans_type . "', 'Pickup', 'Ecom', 
			'" . $customerpickup_ucbdelivering_flg . "', '" . $po_poorderamount . "', '" . $po_employee . "', '" . $po_payment_method . "', '" . $po_date . "', '" . $b2becomm_OrderId . "', 
			'B2B online order', '" . $quote_id . "', '" . $quote_terms . "', '" . $po_freight . "', '', '" . str_replace("'", "\'", $_REQUEST["txtPO"]) . "', '" . str_replace("'", "\'", $quote_notes) . "', '" . $add_freight . "', '" . $po_delivery_dt . "') ";
			//echo $qry_newtrans;
			db();
			$res_newtrans = db_query($qry_newtrans);
			$lastTransactionId = tep_db_insert_id();
			//
			if ($lastTransactionId > 0) {
				//
				//Save original planned delivery date
				if ($po_delivery_dt != "") {
					$date_log = date("Y-m-d H:i:s");
					//
					db();
					$sql = "UPDATE loop_transaction_buyer SET original_planned_delivery_dt = '" . $po_delivery_dt . "', planned_delivery_dt_customer_confirmed=0, dt_customer_confirmed_by='', dt_customer_confirmed_on='' WHERE id = '" . $lastTransactionId . "'";
					$result = db_query($sql);
					//
					db();
					$savelog_qry = "INSERT INTO `planned_delivery_date_history` (`comp_id`, `trans_id`, `planned_delivery_dt`, `user_log`, `date_log`, `planned_delivery_dt_customer_confirmed`, `dt_customer_confirmed_by`, `dt_customer_confirmed_on`) VALUES ('', '" . $lastTransactionId . "', '" . $po_delivery_dt . "', 'Ecom', '" . $date_log . "',0, '', '')";
					$result = db_query($savelog_qry);
				}
				//End Save original planned delivery date

				//echo "<br /> "."UPDATE b2becommerce_order_item SET is_company_set = 1, company_id = ".$rowCompID['companyID'].", transaction_id = ".$lastTransactionId." WHERE session_id = '" . $sessionId . "' and quote_id = '" . $quote_id ."'";
				db();
				db_query("UPDATE b2becommerce_order_item SET is_company_set = 1, company_id = " . $rowCompID['companyID'] . ", transaction_id = " . $lastTransactionId . " WHERE session_id = '" . $sessionId . "' and quote_id = '" . $quote_id . "'");
				db();
				db_query("Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) 
				select '" . $rowLoopWarehouseId['loopid'] . "', 'Supplier', '" . $lastTransactionId . "', 'System generated log - B2B Online order has been added from quote #" . $quote_id_tmp . "', 10");

				/*set the transaction for inventory items start*/
				foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
					if ($arrProdDtV['productIdloop'] > 0) {
						$sql = "SELECT loop_boxes_id FROM loop_boxes_to_warehouse WHERE loop_warehouse_id = '" . $rowLoopWarehouseId['loopid'] . "' AND loop_boxes_id = '" . $arrProdDtV['productIdloop'] . "'";
						$rec_found = "no";
						db();
						$boxes_query = db_query($sql);
						while ($boxes_data = array_shift($boxes_query)) {
							$rec_found = "yes";
						}

						if ($rec_found == "no") {
							$sql = "INSERT INTO loop_boxes_to_warehouse SET loop_warehouse_id = '" . $rowLoopWarehouseId['loopid'] . "', loop_boxes_id = '" . $arrProdDtV['productIdloop'] . "'";
							db();
							db_query($sql);
						}
					}
				}
				/*set the transaction for inventory items end*/
			}

			/*Update quote status and make new transaction end*/
		} //End if quote ID
		else {
			$order_summary = "";
		}
		//echo $order_summary;

		//$eml_confirmation = "<br> ----<h1>Mail to Customer</h1> ---------------------------------------------------------------------------------------------------------------";
		$eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
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

		$eml_confirmation .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";

		$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #" . $lastTransactionId . "</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Thank you for your purchase! </div></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Hi " . $sellto_name . ", we are currently allocating the specific inventory against this order. We will notify you once the order is ready to ship.</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
			Please take a moment to <u>affirm</u> the order summary, shipping and billing address info for accuracy. If there are any errors, please let us know immediately. <br>Should any information be missing, we may delay shipping the order until we receive that information.</div></td></tr>";

		if ($order_summary != "") {
			$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
		}
		//
		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $shippinginfo . "</span>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $billinginfo . "</span>
			<br><br></td></tr>";

		if ($shipping_method != "") {
			$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
				<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $shipping_method . "</span>
				<br><br></td></tr>";
		}

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $payment_method . "</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: " . $_REQUEST["txtPO"] . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: " . $credit_term . "</div>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";

		$eml_confirmation .= "<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";

		$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p>
			<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p>
			<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p>
			<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep " . $acc_owner . " anytime, if you have any questions or concerns.</p>
			<p>Thank you again for Order #" . $lastTransactionId . " and the opportunity to work with you!</p></div></td></tr>";

		$signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
		$signature .= "<td><p style='font-size:13pt;color:#538135'>";
		$signature .= "<u>National Operations Team</u><br>UsedCardboardBoxes (UCB)</p>";
		$signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
		$signature .= "323-724-2500 x709<br><br>";
		$signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
		$signature .= "</td></tr></table>";

		$eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
		$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";

		//$eml_confirmation2 = "<h1>Mail to UCB</h1>";
		//echo "<br /> eml_confirmation => ".$eml_confirmation;
		$eml_confirmation2 = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
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

		$eml_confirmation2 .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation2 .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";

		$eml_confirmation2 .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #" . $lastTransactionId . " (QUOTE #" . $quote_id_tmp . ")</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >NEW B2B ONLINE ORDER FROM QUOTE!</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Please process the above online order, which was accepted online by the customer.</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><a href='https://loops.usedcardboardboxes.com/b2becommerce_reports.php?order_id=" . $order_id . "' target='_blank'>Click Here</a> to view order.</div></td></tr>";

		if ($order_summary != "") {
			$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation2 .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
		}
		//
		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $shippinginfo . "</span>
			<br><br></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $billinginfo . "</span>
			<br><br></td></tr>";

		if ($shipping_method != "") {
			$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
				<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $shipping_method . "</span>
				<br><br></td></tr>";
		}

		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $payment_method . "</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: " . $_REQUEST["txtPO"] . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: " . $credit_term . "</div>
			<br><br></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p><br>
			<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p><br>
			<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p><br>
			<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep " . $acc_owner . " anytime, if you have any questions or concerns.</p><br>
			<p>Thank you again for ONLINE Order #" . $lastTransactionId . " and the opportunity to work with you!</p></div></td></tr>";

		$eml_confirmation2 .= "<br><br><tr><td>" . $signature . "</td></tr>";
		$eml_confirmation2 .= "</table></td></tr></tbody></table></div></body></html>";

		//echo "<br /> eml_confirmation => ".$eml_confirmation;
		//echo "<br /> eml_confirmation2 => ".$eml_confirmation2;

		if ($lastTransactionId > 0) {
			//echo "<br /> eml_confirmation => ".$eml_confirmation;
			//$customer_email = "prasad@extractinfo.com";
			//$emlstatus = sendemail_attachment(null, "", $customer_email, "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $lastTransactionId . " Received" , $eml_confirmation);
			$emlstatus = sendemail_php_function(null, '', $customer_email, "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $lastTransactionId . " Received", $eml_confirmation);
			//echo "<br /> eml_confirmation2 => ".$eml_confirmation2;

			//Operations@UsedCardboardBoxes.com
			//$emlstatus = sendemail_attachment(null, "", "Operations@UsedCardboardBoxes.com", $quote_rep_email, "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Quote Order ID #" . $lastTransactionId, $eml_confirmation2);
			$emlstatus = sendemail_php_function(null, '', "Operations@UsedCardboardBoxes.com", $quote_rep_email, "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Quote Order ID #" . $lastTransactionId, $eml_confirmation2);
		}
	}
	/*****Compare original quote address & b2b order address details and hit the mail start*******/
	$arrQuoteDtls = $arrOrderData = array();
	db_b2b();
	$getCompId = db_query("SELECT ID, companyID FROM quote WHERE ID = " . $quote_id);
	$rowCompID = array_shift($getCompId);
	db_b2b();
	$getCompInfo = db_query("SELECT assignedto, contact, company, nickname, email, phone, sellto_main_line_ph, sellto_main_line_ph_ext, shipContact, shipAddress, shipAddress2, shipCity, shipState, shipZip, shipemail, shipPhone, shipto_main_line_ph, shipping_receiving_hours FROM companyInfo WHERE ID = " . $rowCompID['companyID']);
	$rowContactInfo = array_shift($getCompInfo);
	$arrContact = array();
	if (!empty($rowContactInfo['contact'])) {
		$arrContact = explode(" ", $rowContactInfo['contact']);
	}
	/*$arrQuoteDtls['cntInfoFNm']		= current($arrContact) ?? current($arrContact);
	$arrQuoteDtls['cntInfoLNm']		= end($arrContact) ?? end($arrContact);*/
	$arrQuoteDtls['cntInfoFNm']		= current($arrContact);
	$arrQuoteDtls['cntInfoLNm']		= end($arrContact);
	$arrQuoteDtls['cntInfoCompny'] 	= $rowContactInfo['company'] ?? $rowContactInfo['company'];
	$arrQuoteDtls['cntInfoEmail']	= $rowContactInfo['email'] ?? $rowContactInfo['email'];
	$assignedto	                    = $rowContactInfo['assignedto'] ?? $rowContactInfo['assignedto'];
	db_b2b();
	$getassignedto = db_query("SELECT email FROM employees WHERE employeeID = '" . $assignedto . "'");
	$rowassignedto = array_shift($getassignedto);
	$acc_owner_email = $rowassignedto["email"];

	$cntInfoPhn = '';
	if ($rowContactInfo['phone'] == "") {
		if ($rowContactInfo['sellto_main_line_ph'] != "") {
			if ($rowContactInfo['sellto_main_line_ph_ext'] != "") {
				$cntInfoPhn 	= $rowContactInfo['sellto_main_line_ph'] . " x" . $rowContactInfo['sellto_main_line_ph_ext'];
			} else {
				$cntInfoPhn 	= $rowContactInfo['sellto_main_line_ph'];
			}
		} else {
			$cntInfoPhn 	= $rowContactInfo['mobileno'];
		}
	} else {
		$cntInfoPhn 	= $rowContactInfo['phone'] ?? $rowContactInfo['phone'];
	}
	$arrQuoteDtls['cntInfoPhn']	= $cntInfoPhn;

	$arrShippingContact = "";
	if (!empty($rowContactInfo['shipContact'])) {
		$arrShippingContact = explode(" ", $rowContactInfo['shipContact']);
	}
	$arrQuoteDtls['shippingaddFNm']  = current($arrShippingContact) ?? current($arrShippingContact);
	$arrQuoteDtls['shippingaddLNm']  = end($arrShippingContact) ?? end($arrShippingContact);
	$arrQuoteDtls['shippingaddCompny'] 	= $rowContactInfo['company'];

	$arrQuoteDtls['shippingAdd1'] = $rowContactInfo['shipAddress'] ?? $rowContactInfo['shipAddress'];
	$arrQuoteDtls['shippingAdd2'] = $rowContactInfo['shipAddress2'] ?? $rowContactInfo['shipAddress2'];
	$arrQuoteDtls['shippingaddCity'] = $rowContactInfo['shipCity'] ?? $rowContactInfo['shipCity'];
	$arrQuoteDtls['shippingaddState'] = $rowContactInfo['shipState'] ?? $rowContactInfo['shipState'];
	$arrQuoteDtls['shippingaddZip'] = $rowContactInfo['shipZip'] ?? $rowContactInfo['shipZip'];
	$arrQuoteDtls['shippingaddEmail'] = $rowContactInfo['shipemail'] ?? $rowContactInfo['shipemail'];

	if ($rowContactInfo['shipPhone'] == "") {
		$shipPhone 	= $rowContactInfo['shipto_main_line_ph'];
		if ($rowContactInfo['shipto_main_line_ph_ext'] != "") {
			$shipPhone 	= $shipPhone . " x" . $rowContactInfo['shipto_main_line_ph_ext'];
		}
	} else {
		$shipPhone 	 	= $rowContactInfo['shipPhone'] ?? $rowContactInfo['shipPhone'];
	}
	$arrQuoteDtls['shippingaddPhone'] = $shipPhone;
	//$arrQuoteDtls['shippingaddDockhrs']	= $rowContactInfo['shipping_receiving_hours'];	
	//echo "<pre> arrQuoteDtls - "; print_r($arrQuoteDtls); echo "</pre>";
	//echo "<br /> <br />";
	db();
	$getOrderCntDt = db_query("SELECT contact_firstname, contact_lastname, contact_company, contact_email, contact_phone, shipping_firstname, shipping_lastname, shipping_company, shipping_add1, shipping_add2, shipping_city, shipping_state, shipping_zip, shipping_email, shipping_phone, shipping_dockhrs FROM b2becommerce_order_item  WHERE session_id = '" . $sessionId . "' AND quote_id = '" . $quote_id . "'");
	$rowOrderCntDt = array_shift($getOrderCntDt);
	$arrOrderData['cntInfoFNm']			= $rowOrderCntDt['contact_firstname'];
	$arrOrderData['cntInfoLNm']			= $rowOrderCntDt['contact_lastname'];
	$arrOrderData['cntInfoCompny'] 		= $rowOrderCntDt['contact_company'];
	$arrOrderData['cntInfoEmail']		= $rowOrderCntDt['contact_email'];
	$arrOrderData['cntInfoPhn']			= $rowOrderCntDt['contact_phone'];
	$arrOrderData['shippingaddFNm']		= $rowOrderCntDt['shipping_firstname'];
	$arrOrderData['shippingaddLNm'] 	= $rowOrderCntDt['shipping_lastname'];
	$arrOrderData['shippingaddCompny']	= $rowOrderCntDt['shipping_company'];
	$arrOrderData['shippingAdd1']		= $rowOrderCntDt['shipping_add1'];
	$arrOrderData['shippingAdd2']		= $rowOrderCntDt['shipping_add2'];
	$arrOrderData['shippingaddCity'] 	= $rowOrderCntDt['shipping_city'];
	$arrOrderData['shippingaddState']	= $rowOrderCntDt['shipping_state'];
	$arrOrderData['shippingaddZip']		= $rowOrderCntDt['shipping_zip'];
	$arrOrderData['shippingaddEmail']	= $rowOrderCntDt['shipping_email'];
	$arrOrderData['shippingaddPhone'] 	= $rowOrderCntDt['shipping_phone'];
	//$arrOrderData['shippingaddDockhrs']	= $rowOrderCntDt['shipping_dockhrs'];
	//echo "<pre> arrOrderData - "; print_r($arrOrderData); echo "</pre>";
	$arrRes = array_diff($arrQuoteDtls, $arrOrderData);
	if (!empty($arrRes)) {
		//echo "<pre> arrRes - "; print_r($arrRes); echo "</pre>";

		$sellToDiff = $shipToDiff = '';
		if (!empty($arrRes['cntInfoFNm']) || !empty($arrRes['cntInfoLNm']) || !empty($arrRes['cntInfoCompny']) || !empty($arrRes['cntInfoEmail']) || !empty($arrRes['cntInfoPhn'])) {
			$sellToDiff = "<table width='100%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
			$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Field Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Old Data</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">New Data</td></tr>";
			if (!empty($arrRes['cntInfoFNm'])) {
				$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">First Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['cntInfoFNm'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['cntInfoFNm'] . "</td></tr>";
			}
			if (!empty($arrRes['cntInfoLNm'])) {
				$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Last Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['cntInfoLNm'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['cntInfoLNm'] . "</td></tr>";
			}
			if (!empty($arrRes['cntInfoCompny'])) {
				$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Company</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['cntInfoCompny'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['cntInfoCompny'] . "</td></tr>";
			}
			if (!empty($arrRes['cntInfoEmail'])) {
				$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Email (for order notification)</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['cntInfoEmail'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['cntInfoEmail'] . "</td></tr>";
			}
			if (!empty($arrRes['cntInfoPhn'])) {
				$sellToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Phone</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['cntInfoPhn'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['cntInfoPhn'] . "</td></tr>";
			}

			$sellToDiff .= "</table>";
		}
		//echo "<br /> sellToDiff - "; print_r($sellToDiff); 

		if (!empty($arrRes['shippingaddFNm']) || !empty($arrRes['shippingaddLNm']) || !empty($arrRes['shippingaddCompny']) || !empty($arrRes['shippingAdd1']) || !empty($arrRes['shippingAdd2']) || !empty($arrRes['shippingaddCity']) || !empty($arrRes['shippingaddState']) || !empty($arrRes['shippingaddZip']) || !empty($arrRes['shippingaddEmail']) || !empty($arrRes['shippingaddPhone'])) {
			$shipToDiff = "<table width='100%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
			$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Field Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Old Data</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">New Data</td></tr>";
			if (!empty($arrRes['shippingaddFNm'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">First Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddFNm'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddFNm'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddLNm'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Last Name</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddLNm'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddLNm'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddCompny'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Company</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddCompny'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddCompny'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingAdd1'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\"> Address </td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingAdd1'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingAdd1'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingAdd2'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Suite number</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingAdd2'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingAdd2'] . "</td></tr>";
			}

			if (!empty($arrRes['shippingaddCity'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">City</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddCity'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddCity'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddState'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">State</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddState'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddState'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddZip'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Zip</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddZip'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddZip'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddEmail'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Email (for scheduling delivery appointment)</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddEmail'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddEmail'] . "</td></tr>";
			}
			if (!empty($arrRes['shippingaddPhone'])) {
				$shipToDiff .= "<tr><td align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Phone (for scheduling delivery appointment)</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrQuoteDtls['shippingaddPhone'] . "</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">" . $arrOrderData['shippingaddPhone'] . "</td></tr>";
			}
			$shipToDiff .= "</table>";
		}
		//echo "<br /> shipToDiff - "; print_r($shipToDiff); 

		if ((!empty($sellToDiff)) || (!empty($shipToDiff))) {
			$eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial,'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'><link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
				@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');</style><style scoped>.tablestyle {width:800px;}table.ordertbl tr td{padding:4px;}
				@media only screen and (max-width: 768px) {.tablestyle {width:98%;}}
				</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";
			$eml_confirmation .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";
			$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" ><a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $rowCompID['companyID'] . "' target='_blank'>Company " . $rowContactInfo['nickname'] . "</a></span></td></tr>";
			if (!empty($sellToDiff)) {
				$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Sell To Data</div><br></td></tr>";

				$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $sellToDiff . "</div></td></tr>";
			}
			if (!empty($shipToDiff)) {
				$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Ship To Data</div><br></td></tr>";

				$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $shipToDiff . "</div></td></tr>";
			}
			$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
			//echo "<br /> eml_confirmation => ".$eml_confirmation;
			$emailto = 'Operations@UsedCardboardBoxes.com';
			//$emailto = 'prasad@extractinfo.com';
			$emailcc = $acc_owner_email;
			$emailbcc = '';
			$emailTeamFrm = "Operations@UsedCardboardBoxes.com";
			$emailTeamNm = "UCB Operations Team";
			$replyto = "Operations@UsedCardboardBoxes.com";
			$emailsubject = 'B2B ecomm contact/shipping details updated';

			//$emlstatus = sendemail_attachment(null, "", $emailto, $emailcc, $emailbcc, $emailTeamFrm, $emailTeamNm, $replyto, $emailsubject, $eml_confirmation);
			$emlstatus = sendemail_php_function(null, '', $emailto, $emailcc, $emailbcc, "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", $emailsubject, $eml_confirmation);

			//echo "Email Confirmation - sent - " . $eml_confirmation;
		} else {
			//echo "Email Confirmation - not sent - ";
		}
	}
	/*****Compare original quote address & b2b order address details and hit the mail end*********/
	?>
	<!DOCTYPE html>
	<html dir="ltr" class="js windows firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity placeholder no-touchevents displaytable display-table generatedcontent cssanimations flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">

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
				if (document.getElementById("payment_type").value == "voidTransaction" || document.getElementById("payment_type").value == "refundTransaction") {
					document.getElementById("divvoidref").style.display = "inline";
				} else {
					document.getElementById("divvoidref").style.display = "none";
				}

				if (document.getElementById("payment_type").value == "priorAuthCaptureTransaction") {
					document.getElementById("divcaptureonly").style.display = "inline";
				} else {
					document.getElementById("divcaptureonly").style.display = "none";
				}
			}
		</script>

		<style type="text/css">
			.display_none {
				display: none;
			}

			.payButton {
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
										<strong>1-888-BOXES-88 (1-888-269-3788)</strong>
									</div>
									<div class="needhelp"><?php include("login.php"); ?></div>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="sections new-section-margin">
			<div class="new_container no-top-padding">
				<div class="parentdiv">
					<div class="innerdiv">
						<div class="section-top-margin_1">
							<h1 class="section-title">Quote #<?php echo $quote_id_tmp; ?></h1>
							<div class="title_desc">Order has been placed</div>
						</div>
						<!--Start Breadcrums-->
						<nav aria-label="Breadcrumb">
							<ol class="breadcrumb " role="list">
								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="index_quote.php?quote_id=<?php echo urlencode(encrypt_password($quote_id + 3770)); ?>">Select Quantity</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right"></use>
									</svg>
								</li>

								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="contact.php?quote_id=<?php echo urlencode(encrypt_password($quote_id + 3770)); ?>">Contact</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right">
											<symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
													<path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
												</svg></symbol>
										</use>
									</svg>
								</li>
								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="shipping.php?quote_id=<?php echo urlencode(encrypt_password($quote_id + 3770)); ?>">Shipping</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right"></use>
									</svg>
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
								<div class="frmsection">
									<div class="frm-txt">
										<div class="frm-txt-shipping">
											<h2>Order Complete!</h2> <br>Your Order ID is #<?php echo $lastTransactionId; ?>
										</div>
									</div>
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
										<div>Thank you again for Order #<b><?php echo $lastTransactionId; ?></b> and the opportunity to work with you! </div>
										<div class="inner">
											<div class="collapsible">
												<div class="show-order" id="showorder">Show order summary
													<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
														<path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path>
													</svg>

												</div>
												<div class="show-order-total">
													<?php echo "$" . $total; ?>
												</div>
											</div>

											<div class="inner-content-shipping" id="order-content">
												<?php require('item_sections.php'); ?>

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


														<?php
														
														$total = 0;
														if (!empty($arrProdDt)) {
															foreach ($arrProdDt as $arrProdDtK => $arrProdDtV) {
														?>
																<tr>
																	<td><?php if ($arrProdDtV['productIdloop'] >= 1 && $arrProdDtV['productIdloop'] <= 5) {
																			echo $arrProdDtV['productQntype'];
																		} else if ($arrProdDtV['productIdloop'] >= 5) {
																			echo "ID: " . get_b2b_box_id($arrProdDtV['productIdloop']);
																		}
																		?></td>

																	<td align="right"><?php echo trim($arrProdDtV['hdLeadTime']); ?></td>
																	<td id="totolProQnt" align="center"><?php echo number_format((float)str_replace(",", "", $arrProdDtV['productQnt']), 0); ?></td>
																	<td align="right">$<?php echo number_format((float)str_replace(",", "", $arrProdDtV['productQntprice']), 2); ?></td>
																	<td align="right">$<?php echo number_format((float)trim($arrProdDtV['productTotal']), 2); ?></td>
																</tr>
														<?php
																$total = $total + $arrProdDtV['productTotal'];
															}
														} ?>

														<?php
														$totalTemp =  $total;
														?>
														<?php
														$cc_fees = 0;
														if ($_REQUEST["response_transId"] == "credit_term") {
														} else {

															if ($totalTemp > 0) {
																$cc_fees = (float)str_replace(",", "", number_format($totalTemp, 2)) * 0.03;
															}
														?>
														<?php } ?>
														<tr>
															<td colspan="5">
																<div class="sidebar-sept-intable"></div>
															</td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td></td>
															<td style="font-weight: 500;" align="right">Total</td>
															<td align="right">
																<span class="payment-due__price">
																	<?php
																	$finalTtal = $totalTemp + $cc_fees;
																	echo "$" . number_format($totalTemp, 2);
																	db();
																	$setLoopTrans_buyr = db_query("UPDATE b2becommerce_order_item SET response_amount = '" . $finalTtal . "' WHERE session_id = '" . $sessionId . "' and quote_id = '" . $quote_id . "'");
																	?>
																</span>
															</td>
														</tr>
														<?php
														if ($_REQUEST["response_transId"] == "credit_card") {
														?>
															<tr>
																<td></td>
																<td></td>
																<td align="right" colspan="2">Convenience Fee (3%)<br>Total if Paid by Credit Card</td>
																<td align="right">$<?php echo number_format($cc_fees, 2); ?><br><?php echo "$" . number_format($finalTtal, 2); ?> </td>
															</tr>
														<?php } ?>
													</table>
												</div>

												<div style="padding-top: 60px;">
													<ol class="name-values" style="width: 100%;">
														<li>
															<label for="about">Sell To Contact</label>
															<span id="about">
																<?php if (!empty($orderData['cntInfoFNm']) || !empty($orderData['cntInfoLNm'])) {
																	echo $orderData['cntInfoFNm'] . " " . $orderData['cntInfoLNm'];
																} ?><?php if (!empty($orderData['cntInfoCompny'])) {
													echo ", " . $orderData['cntInfoCompny'];
												} ?>.
															</span>
														</li>
														<li>
															<label for="Span1">Ship To Address</label>
															<span id="Span1">
																<?php if ($rowQuoteShipping["via"] == 'Pickup') { ?>
																	Customer Pickup
																<?php } else { ?>
																	<?php if (!empty($orderData['shippingAdd1'])) {
																		echo $orderData['shippingAdd1'];
																	} ?><?php if (!empty($orderData['shippingAdd2'])) {
																		echo ", " . $orderData['shippingAdd2'];
																	} ?><?php if (!empty($orderData['shippingaddCity'])) {
																		echo ", " . $orderData['shippingaddCity'];
																	} ?><?php if (!empty($orderData['shippingaddState'])) {
																		echo ", " . $orderData['shippingaddState'];
																	} ?><?php if (!empty($orderData['shippingaddZip'])) {
																		echo ", " . $orderData['shippingaddZip'];
																	} ?>
																	<br />
																	<span id="Span1" class="caltxt"><?php echo number_format($distance, 0); ?> mi from item origin</span>
																<?php } ?>
															</span>
														</li>
														<li>
															<label for="Span2">Payment Info</label>
															<span id="Span2">
																<?php
																if ($_REQUEST["response_transId"] == "credit_term") {
																	echo 'Credit Term';
																} else {
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
											$(".collapsible").click(function() {
												// show hide paragraph on button click
												$("div#order-content").slideToggle("slow", function() {
													// check paragraph once toggle effect is completed
													if ($("div#order-content").is(":visible")) {
														$("div.show-order").html('Hide order summary <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z"></path></svg>');
													} else {
														$("div.show-order").html('Show order summary <svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path></svg>');
													}
												});
											});
										</script>

									</div>
								</div>
							</div><!--End div left-form-->
						</div>
						<div class="privacy-links_inner">
							<div class="bottomlinks">
								<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
							</div>
						</div>
					</div><!--End inner div-->
				</div>
			</div>
		</div>
		<?php
		//tep_session_destroy();
		session_regenerate_id();
		?>

	</body>

	</html>

<?php } else { ?>
	<?php
	db();


	$productTotal = 0;
	$quoteTotal = 0;
	$total_amount = 0;
	$ProductLoopId = 0;
	$order_id = 0;
	$customer_email = "";

	$ProductLoopId = $_REQUEST["productIdloop"];

	$shippinginfo = "";
	$billinginfo = "";
	$payment_method = "";
	$shipping_method = "";
	$pgTitle = "";
	$sellto_name = "";
	if ($sessionId) {
		db();
		$getSessionDt = db_query("SELECT b2becommerce_order_item.*, b2becommerce_order_item_details.order_item_id, b2becommerce_order_item_details.product_name_id, b2becommerce_order_item_details.product_name, b2becommerce_order_item_details.product_qty, b2becommerce_order_item_details.product_unitprice, b2becommerce_order_item_details.product_total, b2becommerce_order_item_details.product_lead_time FROM b2becommerce_order_item INNER JOIN b2becommerce_order_item_details ON b2becommerce_order_item_details.order_item_id = b2becommerce_order_item.id WHERE session_id = '" . $sessionId . "' and product_loopboxid = '" . $ProductLoopId . "'");

		while ($rowContactInfo = array_shift($getSessionDt)) {
			$order_id = $rowContactInfo['id'];
			$ProductLoopId = $rowContactInfo['product_loopboxid'];

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

			$orderData['productTeadTime'] = $rowContactInfo['product_lead_time'];

			$orderData['quoteName'] = $rowContactInfo['quote_name'];
			$orderData['quoteQty'] = $rowContactInfo['quote_qty'];
			$orderData['quoteUnitPr'] = $rowContactInfo['quote_unit_price'];
			$orderData['quoteTotal'] = $quoteTotal;

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

			$sellto_name = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname']));
			$shippinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_company'] . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_add1'] . " " . trim($rowContactInfo['shipping_add2']) . "<br>";
			$shippinginfo .= $rowContactInfo['shipping_city'] . ", " . trim($rowContactInfo['shipping_state']) . ", " . trim($rowContactInfo['shipping_zip']) . "<br>";
			if (!empty($rowContactInfo['shipping_phone'])) {
				$shippinginfo .= $rowContactInfo['shipping_phone'] . "<br>";
			}
			$shippinginfo .= $rowContactInfo['shipping_email'] . "<br>";
			$shippinginfo .= "Shipping/Receiving Hours: " . $rowContactInfo['shipping_dockhrs'] . "<br>";

			$billinginfo = trim($rowContactInfo['shipping_firstname'] . " " . trim($rowContactInfo['shipping_lastname'])) . "<br>";
			$billinginfo .= $rowContactInfo['shipping_company'] . "<br>";
			if (!empty($rowContactInfo['billing_add1'])) {
				$billinginfo .= $rowContactInfo['billing_add1'] . " " . trim($rowContactInfo['billing_add2']) . "<br>";
			}
			if (!empty($rowContactInfo['billing_city'])) {
				$billinginfo .= $rowContactInfo['billing_city'] . ", " . trim($rowContactInfo['billing_state']) . ", " . trim($rowContactInfo['billing_zip']) . "<br>";
			}
			if (!empty($rowContactInfo['billing_phone'])) {
				$billinginfo .= $rowContactInfo['billing_phone'] . "<br>";
			}
			if (!empty($rowContactInfo['billing_email'])) {
				$billinginfo .= $rowContactInfo['billing_email'] . "<br>";
			}

			if ($rowContactInfo['pickup_type'] == 'UCB Delivery') {
				$shipping_method = "UCB will deliver, 3rd party";
			}
			if ($rowContactInfo['pickup_type'] == 'Customer Pickup') {
				$shipping_method = "Customer Pickup";
			}

			$payment_method = $rowContactInfo['payment_method'];
		}
	}

	$orderData['hdAvailability'] = $_REQUEST["hdAvailability"];

	$distance = find_distance($ProductLoopId, $orderData['shippingaddZip']);

	$qry_loopbox = "Select * FROM loop_boxes WHERE id = '" . $ProductLoopId . "'";
	db();
	$res_loopbox = db_query($qry_loopbox);
	$row_loopbox = array_shift($res_loopbox);
	$id2 = $row_loopbox["b2b_id"];

	$qryb2b = "Select * FROM inventory WHERE id = '" . $id2 . "'";
	db_b2b();
	$resb2b = db_query($qryb2b);
	$rowb2b = array_shift($resb2b);

	$box_type = $rowb2b["box_type"];

	$boxid_text		= "Item";
	if (in_array(strtolower($box_type), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord")))) {
		$browserTitle 	= "Buy Gaylord Totes";
		$pgTitle		= "Buy Gaylord Totes";
		$idTitle		= "Gaylord ID";
		$boxid_text		= "Gaylord";
	} elseif (in_array(strtolower($box_type), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold")))) {
		$browserTitle 	= "Buy Shipping Boxes";
		$pgTitle		= "Buy Shipping Boxes";
		$idTitle		= "Shipping Box ID";
		$boxid_text		= "Shipping Box";
	} elseif (in_array(strtolower($box_type), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB")))) {
		$browserTitle 	= "Buy Super Sacks";
		$pgTitle		= "Buy Super Sacks";
		$idTitle		= "Super Sack ID";
		$boxid_text		= "Super Sack";
	} elseif (in_array(strtolower($box_type), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))) {
		$browserTitle 	= "Buy Pallets";
		$pgTitle 		= "Buy Pallets";
		$idTitle		= "Pallet ID";
		$boxid_text		= "Pallet";
	} elseif (in_array(strtolower($box_type), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other")))) {
		$browserTitle 	= "Buy Items";
		$pgTitle 		= "Buy Items";
		$idTitle		= "Item ID";
		$boxid_text		= "Item";
	}

	if (isset($_REQUEST["response_transId"])) {
		$acc_owner = "Operations Team";
		$credit_term = "Prepaid (Credit Card)";
		if ($_REQUEST["response_transId"] != "") { ?>
			<script>
				//alert("Payment is Successful.");
			</script>
			<?php
			$trans_type = "";
			if ($_REQUEST["trans_type"] == "authCaptureTransaction") {
				$trans_type = "Authorize and Capture";
			}

			$final_amt = $_REQUEST["totalcnt_withccfee"];
			$payment_method = "Credit Card";
			
			

			if ($payment_method == "Credit Term") {
				$payment_method = "Credit Term";
				$final_amt = $_REQUEST["totalcnt_withoutccfee"];
				$credit_term = "";
			}
			db();
			$setLoopTrans_buyr = db_query("Update b2becommerce_order_item SET response_trans_id = '" . $_REQUEST["response_transId"] . "', payment_method = '" . $payment_method . "', credit_card_process_date = '" . date("Y-m-d H:i:s") . "', purchase_order_no = '" . str_replace("'", "\'", $_REQUEST["txtPO"]) . "' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $_REQUEST["productIdloop"] . "'");

			$order_summary = "<table width='95%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
			$order_summary .= "<tr><td colspan=2 align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Item</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Lead Time</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Qty</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Price</td>";
			$order_summary .= "<td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Total</td></tr>";

			$fly_txt = "";
			$bsize = "";


			$lbq = "SELECT * from loop_boxes WHERE id = $ProductLoopId";
			db();
			$lb_res = db_query($lbq);

			while ($lbrow = array_shift($lb_res)) {
				$bpic_1 = $lbrow['bpic_1'];
				$bpic_2 = $lbrow['bpic_2'];
				$bpic_3 = $lbrow['bpic_3'];
				$bpic_4 = $lbrow['bpic_4'];

				if (file_exists('boxpics_thumbnail/' . $bpic_1)) {
					$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/>
					</div><br>";
				} else {
					if ($lbrow['bpic_1'] != '') {
						$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/></div><br>";
					}
				}
			}

			$description = 'ID:' . $ProductLoopId . ', ' . $boxid_text . ', ' . $row_loopbox["system_description"];

			$order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>" . $fly_txt . "</td><td align='left' width='500px' style='width:502px;'>" . $description . "</td>";
			$order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $orderData['productTeadTime'] . "</td><td align='right'  style='white-space: nowrap;'>" . $orderData['productQnt'] . "</td><td align='right' style='white-space: nowrap;'>$" .  number_format((float)str_replace(",", "", $orderData['productUnitPr']), 2) . "</td><td  align='right'  style='white-space: nowrap;'>$" . number_format((float)str_replace(",", "", $productTotal), 2) . "</td></tr>";

			$order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'>Shipping Quote</td>";
			$order_summary .= "<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right'  style='white-space: nowrap;'>1</td><td align='right' style='white-space: nowrap;'>$" . number_format((float)str_replace(",", "", $orderData['quoteUnitPr']), 2) . "</td><td  align='right'  style='white-space: nowrap;'>$" . number_format((float)str_replace(",", "", $orderData['quoteTotal']), 2) . "</td></tr>";

			if (!empty($orderData['productTotal']) && !empty($orderData['quoteTotal'])) {
				$productTotal = str_replace(",", "", $orderData['productTotal']);
				$productTotal = str_replace("$", "", $productTotal);
				$total = (float)$productTotal + (float)str_replace(",", "", trim($orderData['quoteTotal']));
			} elseif (!empty($orderData['productTotal'])) {
				$productTotal = str_replace(",", "", $orderData['productTotal']);
				$productTotal = str_replace("$", "", $productTotal);
				$total = $productTotal;
			} else {
				$total = "0.00";
			}
			$totalTemp =  str_replace("$", "", $total);

			$cc_fees = 0;
			if ($_REQUEST["response_transId"] == "credit_term") {
			} else {
				if ((float)$totalTemp > 0) {
					$cc_fees = number_format((float)str_replace(",", "", number_format((float)$totalTemp, 2)) * 0.03, 2);
				}

				//$order_summary.="<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'>Convenience Fee (3%)</td>";
				//$order_summary.="<td align='right'  style='white-space: nowrap;'>1</td><td align='right' style='white-space: nowrap;'>$". $cc_fees ."</td><td  align='right'  style='white-space: nowrap;'>$". $cc_fees ."</td></tr>";
			}

			$totalTemp =  str_replace("$", "", $total);
			$totalTemp =  str_replace(",", "", $total);
			$cc_feesTemp =  str_replace(",", "", $cc_fees);
			$total = (float)$totalTemp + (float)$cc_feesTemp;

			$order_summary .= "<tr style='border-top:1px solid #a6a6a6;'><td style='border-top:1px solid #a6a6a6; padding:5px 0px; white-space: nowrap;' colspan=5 align='right'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:600;\">Total</span></td>
			<td align='right' style='border-top:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">$" . number_format((float)$totalTemp, 2) . "</span></td></tr>";
			if ($_REQUEST["response_transId"] == "credit_card") {
				$order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'></td>";
				$order_summary .= "<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right' style='white-space: nowrap;'>Convenience Fee (3%)<br>Total if Paid by Credit Card</td><td  align='right'  style='white-space: nowrap;'>$" . number_format($cc_fees, 2) . "<br>$" . number_format($total, 2) . "</td></tr>";
			}
			$order_summary .= "</table>";
		} //End if quote ID
		else {
			$order_summary = "";
		}
		//echo $order_summary;

		//$eml_confirmation = "<br> ----<h1>Mail to Customer</h1> ---------------------------------------------------------------------------------------------------------------";
		$eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
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

		$eml_confirmation .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";

		$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #" . $order_id . "</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Thank you for your purchase! </div></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Hi " . $sellto_name . ", we are currently allocating the specific inventory against this order. We will notify you once the order is ready to ship.</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
			Please take a moment to <u>affirm</u> the order summary, shipping and billing address info for accuracy. If there are any errors, please let us know immediately. <br>Should any information be missing, we may delay shipping the order until we receive that information.</div></td></tr>";

		if ($order_summary != "") {
			$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
		}
		//
		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $shippinginfo . "</span>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $billinginfo . "</span>
			<br><br></td></tr>";

		if ($shipping_method != "") {
			$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
				<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $shipping_method . "</span>
				<br><br></td></tr>";
		}

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $payment_method . "</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: " . $_REQUEST["txtPO"] . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: " . $credit_term . "</div>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";

		$eml_confirmation .= "<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";

		$eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p>
			<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p>
			<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p>
			<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep " . $acc_owner . " anytime, if you have any questions or concerns.</p>
			<p>Thank you again for Order #" . $order_id . " and the opportunity to work with you!</p></div></td></tr>";

		$signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
		$signature .= "<td><p style='font-size:13pt;color:#538135'>";
		$signature .= "<u>National Operations Team</u><br>UsedCardboardBoxes (UCB)</p>";
		$signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
		$signature .= "323-724-2500 x709<br><br>";
		$signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
		$signature .= "</td></tr></table>";

		$eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
		$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";




		//$eml_confirmation2 = "<h1>Mail to UCB</h1>";
		$eml_confirmation2 = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
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

		$eml_confirmation2 .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation2 .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";

		$eml_confirmation2 .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ONLINE ORDER #" . $order_id . "</span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >NEW B2B ONLINE ORDER!</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Please process the above online order which was captured on the B2B e-commerce store, but still needs created in loops.</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><a href='https://loops.usedcardboardboxes.com/b2becommerce_reports.php?order_id=" . $order_id . "' target='_blank'>Click Here</a> to view order.</div></td></tr>";

		if ($order_summary != "") {
			$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

			$eml_confirmation2 .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
		}
		//
		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $shippinginfo . "</span>
			<br><br></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $billinginfo . "</span>
			<br><br></td></tr>";

		if ($shipping_method != "") {
			$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
				<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $shipping_method . "</span>
				<br><br></td></tr>";
		}

		$eml_confirmation2 .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . $payment_method . "</div>
			<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO)#: " . $_REQUEST["txtPO"] . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: " . $credit_term . "</div>
			<br><br></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";

		$eml_confirmation2 .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p><br>
			<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p><br>
			<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p><br>
			<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep " . $acc_owner . " anytime, if you have any questions or concerns.</p><br>
			<p>Thank you again for ONLINE Order #" . $order_id . " and the opportunity to work with you!</p></div></td></tr>";

		$eml_confirmation2 .= "<br><br><tr><td>" . $signature . "</td></tr>";
		$eml_confirmation2 .= "</table></td></tr></tbody></table></div></body></html>";

		//echo "<br /> eml_confirmation => ".$eml_confirmation;  // mail to customer

		//$customer_email = "prasad@extractinfo.com";
		//$emlstatus = sendemail_attachment(null, "", $customer_email, "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $order_id . " Received" , $eml_confirmation);
		$emlstatus = sendemail_php_function(null, '', $customer_email, "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "UsedCardboardBoxes Order #" . $order_id . " Received", $eml_confirmation);

		//echo "<br />eml_confirmation2 => ".$eml_confirmation2; // mail to UCB

		//$emlstatus = sendemail_attachment(null, "", "Operations@UsedCardboardBoxes.com", "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Order ID #" . $order_id, $eml_confirmation2);
		$emlstatus = sendemail_php_function(null, '', "Operations@UsedCardboardBoxes.com", "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", "New B2B Online Order ID #" . $order_id, $eml_confirmation2);
		//$emlstatus = sendemail_attachment(null, "", "davidkrasnow@usedcardboardboxes.com,", "bk@mooneem.com,creditcard@usedcardboardboxes.com", "", "admin@usedcardboardboxes.com", "Admin UCB","", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$_REQUEST['ID']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );

	}


	?>
	<!DOCTYPE html>
	<html dir="ltr" class="js windows firefox desktop page--no-banner page--logo-main page--show page--show card-fields cors svg opacity placeholder no-touchevents displaytable display-table generatedcontent cssanimations flexbox no-flexboxtweener anyflexbox shopemoji floating-labels" style="" lang="en">

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
				if (document.getElementById("payment_type").value == "voidTransaction" || document.getElementById("payment_type").value == "refundTransaction") {
					document.getElementById("divvoidref").style.display = "inline";
				} else {
					document.getElementById("divvoidref").style.display = "none";
				}

				if (document.getElementById("payment_type").value == "priorAuthCaptureTransaction") {
					document.getElementById("divcaptureonly").style.display = "inline";
				} else {
					document.getElementById("divcaptureonly").style.display = "none";
				}
			}
		</script>

		<style type="text/css">
			.display_none {
				display: none;
			}

			.payButton {
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

	<body>
		<div class="main_container">
			<div class="sub_container">
				<div class="header">
					<div class="logo_img"><a href="https://www.usedcardboardboxes.com/"><img src="images/ucb_logo.jpg" alt="moving boxes"></a></div>
					<div class="contact_number">
						<span class="login-username">
							<div class="needhelp">Need help? </div>
							<div class="needhelp_call">
								<img src="images/callicon.png" alt="" class="call_img"><strong>1-888-BOXES-88 (1-888-269-3788)</strong>
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="sections new-section-margin">
			<div class="new_container no-top-padding">
				<div class="parentdiv">
					<div class="innerdiv">
						<div class="section-top-margin_1">
							<h1 class="section-title"><?php echo $pgTitle; ?></h1>
							<div class="title_desc">Order has been placed</div>
						</div>
						<!--Start Breadcrums-->
						<nav aria-label="Breadcrumb">
							<ol class="breadcrumb " role="list">
								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="index.php?id=<?php echo $ProductLoopId; ?>">Select Quantity</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right"></use>
									</svg>
								</li>

								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="contact.php?id=<?php echo $ProductLoopId; ?>">Contact</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right">
											<symbol id="chevron-right"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10">
													<path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
												</svg></symbol>
										</use>
									</svg>
								</li>
								<li class="breadcrumb__item breadcrumb__item--completed">
									<a class="breadcrumb__link" href="shipping.php?id=<?php echo $ProductLoopId; ?>">Shipping</a>
									<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
										<use xlink:href="#chevron-right"></use>
									</svg>
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
									<?php if ($_REQUEST["response_transId"] == "credit_term") {
										echo "Payment will be processed using Credit Term<br><br><b>Order id: " . $order_id . "</b><br><br> We will follow up with you after your order is placed to confirm payment details, whether that is using credit terms you already have approved, or helping you get setup with credit terms.<br><br>";
									} else { ?>
											Payment will be charged by Credit Card<br><br>
											<b>Order id:&nbsp;<?php echo $order_id; ?></b><br><br>
										<?php
										//echo "Payment Transaction ID : " . $_REQUEST["response_transId"];
										?>
									<?php } ?>
								</div> -->

									<!-- 
								<div class="btn-div-shipping content-bottom-padding"></div> -->
									<div class="frm-txt">
										<div class="frm-txt-shipping">
											<h2>Order Complete!</h2> <br>Your Order ID is #<?php echo $order_id; ?>
										</div>
									</div>
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
										<div>Thank you again for Order #<b><?php echo $order_id; ?></b> and the opportunity to work with you! </div>

										<?php  /* ?>
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
			<?php */ ?>
										<div class="inner">
											<div class="collapsible">
												<div class="show-order" id="showorder">Show order summary
													<svg width="11" height="6" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000">
														<path d="M.504 1.813l4.358 3.845.496.438.496-.438 4.642-4.096L9.504.438 4.862 4.534h.992L1.496.69.504 1.812z"></path>
													</svg>

												</div>
												<div class="show-order-total">
													<?php
													if (!empty($orderData['productTotal']) && !empty($orderData['quoteTotal'])) {
														$total = str_replace(",", "", $orderData['productTotal']) + str_replace(",", "", $orderData['quoteTotal']);
													} else {
														$total = "0.00";
													}
													echo "$" . $total;
													?>
												</div>
											</div>

											<div class="inner-content-shipping" id="order-content">
												<?php require('item_sections.php'); ?>

												<div class="sidebar-sept"></div>
												<div class="table_mob_div">
													<table class="sidebar-table">
														<tr>
															<th align="left">Truckload</th>
															<th align="center">Quantity</th>
															<th align="right">Price/Unit</th>
															<th align="right">Total</th>
														</tr>

														<tr>
															<td><?php echo $orderData['productName']; ?></td>
															<td align="center"><?php echo $orderData['productQnt']; ?></td>
															<td align="right">$<?php echo number_format((float)str_replace(",", "", $orderData['productUnitPr']), 2); ?></td>
															<td align="right">$<?php echo number_format((float)str_replace(",", "", $orderData['productTotal']), 2); ?></td>
														</tr>
														<tr>
															<td><?php if (!empty($orderData['quoteName'])) {
																	echo $orderData['quoteName'];
																} ?>
															</td>
															<td align="center">
																<?php if (empty($orderData['quoteQty'])) {
																	echo '0';
																} else {
																	echo number_format($orderData['quoteQty'], 0);
																} ?>
															</td>
															<td align="right">
																<?php if (empty($orderData['quoteUnitPr'])) {
																	echo '$0.00';
																} else {
																	echo "$" . number_format((float)str_replace(",", "", $orderData['quoteUnitPr']), 2);
																} ?>
															</td>
															<td align="right">
																<?php if (empty($orderData['quoteTotal'])) {
																	echo '$0.00';
																} else {
																	echo "$" . number_format((float)str_replace(",", "", $orderData['quoteTotal']), 2);
																} ?>
															</td>
														</tr>
														<?php
														if (!empty($orderData['productTotal']) && !empty($orderData['quoteTotal'])) {
															$productTotal = str_replace(",", "", $orderData['productTotal']);
															$productTotal = str_replace("$", "", $productTotal);
															$total = (float)$productTotal + (float)str_replace(",", "", trim($orderData['quoteTotal']));
														} elseif (!empty($orderData['productTotal'])) {
															$productTotal = str_replace(",", "", $orderData['productTotal']);
															$productTotal = str_replace("$", "", $productTotal);
															$total = $productTotal;
														} else {
															$total = "0.00";
														}
														$totalTemp =  str_replace("$", "", $total);

														?>
														<?php

														$cc_fees = 0;
														if ($_REQUEST["response_transId"] == "credit_term") {
														} else {
															if ((float)$totalTemp > 0) {
																$cc_fees = number_format((float)str_replace(",", "", number_format((float)$totalTemp, 2)) * 0.03, 2);
															}
														?>
														<?php } ?>
														<tr>
															<td colspan="4">
																<div class="sidebar-sept-intable"></div>
															</td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td style="font-weight: 500;" align="right">Total</td>
															<td align="right">
																<span class="payment-due__price">
																	<?php
																	$totalTemp =  str_replace("$", "", $total);
																	$totalTemp =  str_replace(",", "", $total);
																	$cc_feesTemp =  str_replace(",", "", $cc_fees);
																	$total = (float)$totalTemp + (float)$cc_feesTemp;
																	echo "$" . number_format((float)$totalTemp, 2);
																	db();
																	$setLoopTrans_buyr = db_query("Update b2becommerce_order_item SET response_amount = '" . $total . "' where session_id = '" . $sessionId . "' and product_loopboxid = '" . $_REQUEST["productIdloop"] . "'");
																	?>
																</span>
															</td>
														</tr>
														<?php
														if ($_REQUEST["response_transId"] == "credit_card") {
														?>
															<tr>
																<td></td>
																<td align="right" colspan="2">Convenience Fee (3%)<br>Total if Paid by Credit Card</td>
																<td align="right">$<?php echo number_format($cc_fees, 2); ?><br><?php echo "$" . number_format($total, 2); ?> </td>
															</tr>
														<?php } ?>

													</table>
												</div>


												<div style="padding-top: 60px;">
													<ol class="name-values" style="width: 100%;">
														<li>
															<label for="about">Sell To Contact</label>
															<span id="about">
																<?php if (!empty($orderData['cntInfoFNm']) || !empty($orderData['cntInfoLNm'])) {
																	echo $orderData['cntInfoFNm'] . " " . $orderData['cntInfoLNm'];
																} ?><?php if (!empty($orderData['cntInfoCompny'])) {
													echo ", " . $orderData['cntInfoCompny'];
												} ?>.
															</span>
														</li>
														<li>
															<label for="Span1">Ship To Address</label>
															<span id="Span1">
																<?php if (!empty($orderData['shippingAdd1'])) {
																	echo $orderData['shippingAdd1'];
																} ?><?php if (!empty($orderData['shippingAdd2'])) {
													echo ", " . $orderData['shippingAdd2'];
												} ?><?php if (!empty($orderData['shippingaddCity'])) {
													echo ", " . $orderData['shippingaddCity'];
												} ?><?php if (!empty($orderData['shippingaddState'])) {
													echo ", " . $orderData['shippingaddState'];
												} ?><?php if (!empty($orderData['shippingaddZip'])) {
													echo ", " . $orderData['shippingaddZip'];
												} ?>
																<br />
																<span id="Span1" class="caltxt"><?php echo number_format($distance, 0); ?> mi from item origin</span>
															</span>
														</li>
														<li>
															<label for="Span2">Payment Info</label>
															<span id="Span2">
																<?php
																if ($_REQUEST["response_transId"] == "credit_term") {
																	echo 'Credit Term';
																} else {
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
											$(".collapsible").click(function() {
												// show hide paragraph on button click
												$("div#order-content").slideToggle("slow", function() {
													// check paragraph once toggle effect is completed
													if ($("div#order-content").is(":visible")) {
														$("div.show-order").html('Hide order summary <svg width="11" height="7" xmlns="http://www.w3.org/2000/svg" class="order-summary-toggle__dropdown" fill="#000"><path d="M6.138.876L5.642.438l-.496.438L.504 4.972l.992 1.124L6.138 2l-.496.436 3.862 3.408.992-1.122L6.138.876z"></path></svg>');
													} else {
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
								<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div>
								<div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
							</div>
						</div>
					</div><!--End inner div-->







				</div>
			</div>
		</div>
		<?php

		/*tep_session_unregister('productData');
		tep_session_unregister('orderData');
		tep_session_destroy();
		*/

		session_regenerate_id();
		?>

	</body>

	</html>


<?php } ?>