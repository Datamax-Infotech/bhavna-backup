<?php
/* 19-10-22	  Amarendra		To show old page table. */
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
$client_companyid = isset($_REQUEST['compnewid']) ? decrypt_password($_REQUEST['compnewid']) : "";
?>
<div style="text-align:left;">
	<?php
	$sql_child = "SELECT id FROM companyInfo where companyInfo.parent_child = 'Child' and active = 1 and parent_comp_id = " . $client_companyid;
	db_b2b();
	$res1w = db_query($sql_child);
	echo "<font size='1'><b>Total Locations:" . tep_db_num_rows($res1w) . "</b></font><br>";
	?>

	<i>Note: Please wait until you see <font size="1" color="red">"END OF REPORT"</font> at the bottom of the report, before using the sort option.</i>
</div>
<table cellSpacing="1" cellPadding="1" border="0" width="100%">
	<tr align="middle">
		<td colSpan="12" class="style7">
			<b>VIEW LATEST SHIPMENTS (Showing for current Year)</b>
		</td>
	</tr>
	<tr>
		<form action="client_dashboard.php?compnewid=<?php echo urlencode($_REQUEST['compnewid']); ?>&show=<?php echo $_REQUEST['show'] . $_REQUEST['repchk_str']; ?>" method="post">
			<td colSpan="12" class="style7">
				<b>WHICH VIEW:</b>
				<select name="dView" onchange="this.form.submit();">
					<option value="1" <?php echo (($_REQUEST['dView'] == 1) ? "Selected" : ""); ?>>This Location Only</option>
					<option value="2" <?php echo (($_REQUEST['dView'] == 2) ? "Selected" : ""); ?>>Corporate View</option>
				</select>
			</td>
		</form>
	</tr>
	<tr>
		<?php
		$col_cnt_tmp = 0;
		$col_cnt_tmp = $col_cnt_tmp + 1;
		$section_lastship_col1_flg = null;
		$section_lastship_col2_flg = null;
		$section_lastship_col3_flg = null;
		$section_lastship_col4_flg = null;
		$section_lastship_col5_flg = null;
		$section_lastship_col6_flg = null;
		$section_lastship_col7_flg = null;
		$section_lastship_col8_flg = null;
		$client_companyid = "";
		$sort_order = "DESC";

		?>
		<td class="style17" align="center"><b>
				<a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=location&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Location</a></b>
		</td>
		<?php
		$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
		<td class="style17" align="center">
			<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=accout_manager&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Account Manager</a></b>

		</td>

		<?php if ($section_lastship_col1_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<?php if (isset($_REQUEST["vs_start_date"])) { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=datesubmit&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes&vs_start_date=<?php echo $_REQUEST["vs_start_date"]; ?>&vs_end_date=<?php echo $_REQUEST["vs_end_date"]; ?>">Date Submitted</a></b>
				<?php } else { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=datesubmit&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Date Submitted</a></b>
				<?php } ?>

			</td>
		<?php } ?>
		<?php if ($section_lastship_col2_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<?php if (isset($_REQUEST["vs_start_date"])) { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=status&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes&vs_start_date=<?php echo $_REQUEST["vs_start_date"]; ?>&vs_end_date=<?php echo $_REQUEST["vs_end_date"]; ?>">Status</a></b>
				<?php } else { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=status&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Status</a></b>
				<?php } ?>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col3_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<b>Purchase Order</b>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col7_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td align="middle" style="width: 70px" class="style16" align="center">
				<b>View BOL</b>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col4_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<?php if (isset($_REQUEST["vs_start_date"])) { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=dateshipped&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes&vs_start_date=<?php echo $_REQUEST["vs_start_date"]; ?>&vs_end_date=<?php echo $_REQUEST["vs_end_date"]; ?>">Date Shipped</a></b>
				<?php } else { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=dateshipped&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Date Shipped</a></b>
				<?php } ?>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col4_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=datedeliver&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Date Delivered</a></b>

			</td>
		<?php } ?>
		<?php if ($section_lastship_col5_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<?php if (isset($_REQUEST["vs_start_date"])) { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=boxes&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes&vs_start_date=<?php echo $_REQUEST["vs_start_date"]; ?>&vs_end_date=<?php echo $_REQUEST["vs_end_date"]; ?>">Boxes</a></b>
				<?php } else { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=boxes&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Boxes</a></b>
				<?php } ?>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col6_flg == "yes") {
			$col_cnt_tmp = $col_cnt_tmp + 1;
		?>
			<td class="style17" align="center">
				<b>Invoice</b>
			</td>
			<td class="style17" align="center">
				<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=invamt&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Amount</a></b>
			</td>
		<?php } ?>
		<?php if ($section_lastship_col8_flg == "yes") {
		?>
			<td class="style17" align="center">
				<?php if (isset($_REQUEST["vs_start_date"])) { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=invage&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes&vs_start_date=<?php echo $_REQUEST["vs_start_date"]; ?>&vs_end_date=<?php echo $_REQUEST["vs_end_date"]; ?>">Invoice Age</a></b>
				<?php } else { ?>
					<b><a href="client_dashboard.php?repchk=<?php echo $_REQUEST["repchk"]; ?>&compnewid=<?php echo urlencode(encrypt_password($client_companyid)); ?>&show=<?php echo $_REQUEST["show"]; ?>&sort=invage&sort_order=<?php echo $sort_order; ?>&sorting_ship=yes">Invoice Age</a></b>
				<?php } ?>
			</td>
		<?php } ?>
	</tr>

	<?php
	$MGArray = array();
	$MGArraysort = array();
	$report_total_loc = 0;
	$comp_id = "";
	$boxes_sort = "";
	$accountowner = "";
	$total_loc = array();
	$client_loopid = "";
	$child_comp_new = "";
	$total_trans = 0;
	$tot_inv_amount = 0;
	$tmpcnt = 0;
	$invoice_age = "";
	if ($_REQUEST['sorting_ship'] == "yes") {
		$sort_order_arrtxt = "SORT_ASC";
		if ($_REQUEST["sort_order"] != "") {
			if ($_REQUEST["sort_order"] == "ASC") {
				$sort_order_arrtxt = "SORT_ASC";
			} else {
				$sort_order_arrtxt = "SORT_DESC";
			}
		} else {
			$sort_order_arrtxt = "SORT_DESC";
		}

		$MGArray = $_SESSION['sortarrayn_dsh'];
		if ($_REQUEST["sort"] == "location") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['company'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}

		if ($_REQUEST["sort"] == "datesubmit") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['dt_submitted_sort'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "accout_manager") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['accountowner'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}

		if ($_REQUEST["sort"] == "status") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['status'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "dateshipped") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['dt_shipped_sort'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "datedeliver") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['dt_delv_sort'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "invamt") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['inv_amount'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "boxes") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['boxes_sort'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		if ($_REQUEST["sort"] == "invage") {
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort[] = $MGArraytmp['invoice_age'];
			}
			if ($sort_order_arrtxt == "SORT_ASC") {
				array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
			} else {
				array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
			}
		}
		foreach ($MGArray as $MGArraytmp2) {
			$total_loc[] = $MGArraytmp2["comp_id"];
	?>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style3" align="center">
					<?php echo $MGArraytmp2["company"]; ?>
				</td>
				<td bgColor="#e4e4e4" class="style3" align="center">
					<?php echo $MGArraytmp2["accountowner"]; ?>
				</td>
				<!--<td bgColor="#e4e4e4" class="style3"  align="center">	
						<?php //echo $MGArraytmp2["assignedto"]; 
						?>
					</td>-->
				<?php if ($section_lastship_col1_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $MGArraytmp2["dt_submitted"]; ?>
					</td>
				<?php } ?>
				<?php if ($section_lastship_col2_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $MGArraytmp2["status"]; ?>
					</td>
				<?php } ?>
				<?php
				if ($section_lastship_col3_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center" id="po_order_show<?php echo $tmpcnt; ?>">
						<?php echo $MGArraytmp2["purchase_order"]; ?>
					</td>
				<?php } ?>

				<?php if ($section_lastship_col7_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center" id="bol_show<?php echo $tmpcnt; ?>">
						<?php echo $MGArraytmp2["viewbol"]; ?>
					</td>
				<?php } ?>

				<?php if ($section_lastship_col4_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $MGArraytmp2["dt_shipped"]; ?>
					</td>
				<?php } ?>

				<?php if ($section_lastship_col4_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $MGArraytmp2["dt_delv"]; ?>
					</td>
				<?php } ?>
				<?php if ($section_lastship_col5_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $MGArraytmp2["boxes"]; ?>
					</td>
				<?php } ?>

				<?php if ($section_lastship_col6_flg == "yes") { ?>
					<td bgColor="#e4e4e4" class="style3" align="center" id="inv_file_show<?php echo $tmpcnt; ?>">
						<?php echo $MGArraytmp2["invoice"]; ?>
					</td>
					<td bgColor="#e4e4e4" class="style3" align="right">
						<?php echo number_format($MGArraytmp2["inv_amount"], 2); ?>
					</td>
				<?php } ?>

				<?php if ($section_lastship_col8_flg == "yes") { ?>
					<td bgColor="<?php echo $MGArraytmp2["invoice_age_color"]; ?>" class="style3" align="center">
						<?php echo $invoice_age; ?>
					</td>
				<?php
				}

				$tot_inv_amount = $tot_inv_amount + $MGArraytmp2["inv_amount"];
				$total_trans = $total_trans + 1;
				?>

			</tr>

			<?php
			$report_total_loc = count(array_unique($total_loc));
			$tmpcnt = $tmpcnt + 1;
		}
		if ($section_lastship_col6_flg == "yes") {
			if ($tot_inv_amount > 0) { ?>
				<tr>
					<td bgColor="#e4e4e4" colspan="<?php echo $col_cnt_tmp; ?>" class="style3" align="right">Total Amount:&nbsp;</td>
					<td bgColor="#e4e4e4" class="style3" align="right"><strong><?php echo "$" . number_format($tot_inv_amount, 2); ?></strong></td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>

			<?php	}

			if ($total_trans > 0) {
			?>
				<tr>
					<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
						<?php
						echo "<font size='1'><b>Total Number of Locations: " . $report_total_loc . "";
						?>
					</td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>
				<tr>
					<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
						<?php
						echo "<font size='1'><b>Total Number of Transactions: " . $total_trans . "";
						?>
					</td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>
			<?php
			}
		}
	} else {
		//if ($child_comp != "") {
		$query1 = "SELECT * FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp_new . ") ORDER BY warehouse_id asc, id DESC";
		//}else{
		//$query1 = "SELECT * FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id = $client_loopid ORDER BY id DESC";
		//}

		$res1 = db_query($query1);
		$tmpcnt = 0;
		$tot_inv_amount = 0;
		$total_trans = 0;
		while ($row1 = array_shift($res1)) {

			if (isset($_REQUEST["vs_start_date"])) {
				$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C FROM loop_bol_tracking WHERE trans_rec_id = " . $row1["id"] . " and (STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') >= '" . $_REQUEST["vs_start_date"] . "' and STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') <= '" . $_REQUEST["vs_end_date"] . "') GROUP BY trans_rec_id ORDER BY C DESC ";
			} else {
				$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C FROM loop_bol_tracking WHERE trans_rec_id = " . $row1["id"] . " and YEAR(STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y')) = " . date("Y") . " GROUP BY trans_rec_id ORDER BY C  DESC";
			}
			$res = db_query($query);

			while ($row = array_shift($res)) {
				//This is the payment Info for the Customer paying UCB
				$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $row1["id"];
				$payment_qry = db_query($payments_sql);
				$payment = array_shift($payment_qry);

				$tot_inv_amount = $tot_inv_amount + $row1["inv_amount"];
				$total_trans = $total_trans + 1;
				//This is the payment info for UCB paying the related vendors
				$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row1["id"];
				$vendor_qry = db_query($vendor_sql);
				$vendor = array_shift($vendor_qry);

				//Info about Shipment
				$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $row1["id"] . "' ORDER BY id DESC";
				//echo $bol_file_qry ;
				$bol_file_res = db_query($bol_file_qry);
				$bol_file_row = array_shift($bol_file_res);

				$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $row1["id"];
				$fbookresult = db_query($fbooksql);
				$freightbooking = array_shift($fbookresult);

				$vendors_paid = 0; //Are the vendors paid
				$vendors_entered = 0; //Has a vendor transaction been entered?
				$invoice_paid = 0; //Have they paid their invoice?
				$invoice_entered = 0; //Has the inovice been entered
				$signed_customer_bol = 0; 	//Customer Signed BOL Uploaded
				$courtesy_followup = 0; 	//Courtesy Follow Up Made
				$delivered = 0; 	//Delivered
				$signed_driver_bol = 0; 	//BOL Signed By Driver
				$shipped = 0; 	//Shipped
				$bol_received = 0; 	//BOL Received @ WH
				$bol_sent = 0; 	//BOL Sent to WH"
				$bol_created = 0; 	//BOL Created
				$freight_booked = 0; //freight booked
				$sales_order = 0;   // Sales Order entered
				$po_uploaded = 0;  //po uploaded 

				//Are all the vendors paid?
				if ($vendor["B"] == 2 && $vendor["C"] == 2) {
					$vendors_paid = 1;
				}

				//Have we entered a vendor transaction?
				if ($vendor["A"] > 0) {
					$vendors_entered = 1;
				}

				//Have they paid their invoice?
				if (number_format($row1["inv_amount"], 2) == number_format($payment["A"], 2) && $row1["inv_amount"] != "") {
					$invoice_paid = 1;
				}
				if ($row1["no_invoice"] == 1) {
					$invoice_paid = 1;
				}

				//Has an invoice amount been entered?
				if ($row1["inv_amount"] > 0) {
					$invoice_entered = 1;
				}

				if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
					$signed_customer_bol = 1;
				}	//Customer Signed BOL Uploaded
				if ($bol_file_row["bol_shipment_followup"] > 0) {
					$courtesy_followup = 1;
				}	//Courtesy Follow Up Made
				if ($bol_file_row["bol_shipment_received"] > 0) {
					$delivered = 1;
				}	//Delivered
				if ($bol_file_row["bol_signed_file_name"] != "") {
					$signed_driver_bol = 1;
				}	//BOL Signed By Driver
				if ($bol_file_row["bol_shipped"] > 0) {
					$shipped = 1;
				}	//Shipped
				if ($bol_file_row["bol_received"] > 0) {
					$bol_received = 1;
				}	//BOL Received @ WH
				if ($bol_file_row["bol_sent"] > 0) {
					$bol_sent = 1;
				}	//BOL Sent to WH"
				if ($bol_file_row["id"] > 0) {
					$bol_created = 1;
				}	//BOL Created

				if ($freightbooking["id"] > 0) {
					$freight_booked = 1;
				} //freight booked

				$start_t = strtotime($row1["inv_date_of"]);
				$end_time =  strtotime('now');
				$invoice_age = number_format(($end_time - $start_t) / (3600 * 24), 0);
				if (($row1["so_entered"] == 1)) {
					$sales_order = 1;
				} //sales order created
				if ($row1["po_date"] != "") {
					$po_uploaded = 1;
				} //po uploaded 			

				$nn = "";
				$dt_submitted = "";
				$status = "";
				$dt_submitted_sort = "";
				$dt_shipped_sort = "";
				$dt_delv_sort = "";

				$dt_submitted = date('m-d-Y', strtotime($row1["start_date"]));
				$dt_submitted_sort = date('Y-m-d', strtotime($row1["start_date"]));

				$dt_shipped = "";
				$dt_delv = "";
				$boxes = "";
				$inv_age = "";
				$purchase_order = "";
				$viewbol = "";
				$invoice = "";
				if ($row["B"] > 0) {
					$dt_shipped = date('m-d-Y', strtotime($row["B"]));
					$dt_shipped_sort = date('Y-m-d', strtotime($row["B"]));
				}
				if ($bol_file_row['bol_shipment_received_date'] > 0) {
					$dt_delv = date('m-d-Y', strtotime($bol_file_row['bol_shipment_received_date']));
					$dt_delv_sort = date('Y-m-d', strtotime($bol_file_row['bol_shipment_received_date']));
				}
				if ($row["A"] > 0) {
					$boxes = number_format($row["A"], 0);
					$boxes_sort = $row["A"];
				}

			?>
				<tr vAlign="center">
					<?php
					$nickname = "";

					db_b2b();
					$sql1 = "SELECT ID,nickname,assignedto, company, shipCity, shipState FROM companyInfo where loopid = " . $row1["warehouse_id"];

					$result_comp = db_query($sql1);
					while ($row_comp = array_shift($result_comp)) {
						if ($row_comp["nickname"] != "") {
							$nickname = $row_comp["nickname"];
						} else {
							$tmppos_1 = strpos($row_comp["company"], "-");
							if ($tmppos_1 != false) {
								$nickname = $row_comp["company"];
							} else {
								if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
									$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
								} else {
									$nickname = $row_comp["company"];
								}
							}
						}
						$comp_id = $row_comp["ID"];
						$total_loc[] = $comp_id;

						$arr = explode(",", $row_comp["assignedto"]);
						db_b2b();
						$qassign = "SELECT * FROM employees WHERE status='Active' and  employeeID='" . $row_comp["assignedto"] . "' order by name asc";
						$dt_view_res_assign = db_query($qassign);
						$res_assign = array_shift($dt_view_res_assign);
						$accountowner = $res_assign["name"];
						db();
					}

					?>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $nickname; ?>
					</td>
					<td bgColor="#e4e4e4" class="style3" align="center">
						<?php echo $accountowner; ?>
					</td>

					<?php if ($section_lastship_col1_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center">
							<?php echo date('m-d-Y', strtotime($row1["start_date"])); ?>
						</td>
					<?php } ?>
					<?php if ($section_lastship_col2_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center">
							<?php
							if (($row1["ignore"] == 1)) {
								echo "Cancelled";
								$status = "Cancelled";
							} elseif ($invoice_paid == 1) {
								echo "Paid";
								$status = "Paid";
							} elseif ($invoice_entered == 1) {
								echo "Invoice sent";
								$status = "Invoice sent";
							} elseif ($signed_customer_bol == 1) {
								echo "Customer Signed BOL";
								$status = "Customer Signed BOL";
							} elseif ($courtesy_followup == 1) {
								echo "Courtesy Followup Made";
								$status = "Courtesy Followup Made";
							} elseif ($delivered == 1) {
								echo "Delivered";
								$status = "Delivered";
							} elseif ($signed_driver_bol == 1) {
								echo "Shipped - Driver Signed";
								$status = "Shipped - Driver Signed";
							} elseif ($shipped == 1) {
								echo "Shipped";
								$status = "Shipped";
							} elseif ($bol_received == 1) {
								echo "BOL @ Warehouse";
								$status = "BOL @ Warehouse";
							} elseif ($bol_sent == 1) {
								echo "BOL Sent to Warehouse";
								$status = "BOL Sent to Warehouse";
							} elseif ($bol_created == 1) {
								echo "BOL Created";
								$status = "BOL Created";
							} elseif ($freight_booked == 1) {
								echo "Freight Booked";
								$status = "Freight Booked";
							} elseif ($sales_order == 1) {
								echo "Sales Order Entered";
								$status = "Sales Order Entered";
							} elseif ($po_uploaded == 1) {
								echo "PO Uploaded";
								$status = "PO Uploaded";
							}
							?>
						</td>
					<?php } ?>
					<?php
					if ($section_lastship_col3_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center" id="po_order_show<?php echo $tmpcnt; ?>">
							<a href="javascript:void(0);" onclick="display_file('https://loops.usedcardboardboxes.com/po/<?php echo str_replace(" ", "%20", $row1["po_file"]); ?>', 'Purchase order',<?php echo $tmpcnt; ?>)">
								<font color="blue"><u><?php echo "&nbsp;" . $row1["po_ponumber"]; ?></u></font>
							</a>
						</td>
					<?php $purchase_order = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_file('https://loops.usedcardboardboxes.com/po/" . str_replace(" ", "%20", $row1["po_file"]) . "', 'Purchase order'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>&nbsp;" . $row1["po_ponumber"] . "</u></font></a>";
					} ?>

					<?php if ($section_lastship_col7_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center" id="bol_show<?php echo $tmpcnt; ?>">
							<?php
							$bol_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id = '" . $row1["id"] . "' ORDER BY id DESC";
							$bol_view_res = db_query($bol_view_qry);
							while ($bol_view_row = array_shift($bol_view_res)) {
								if ($bol_view_row["trans_rec_id"] != '') {
							?>
									<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?php echo str_replace(" ", "%20", $bol_view_row["file_name"]); ?>', 'BOL',<?php echo $tmpcnt; ?>)">
										<font color="blue"><u><?php echo $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"]; ?></u></font>
									</a>
								<?php
									$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"] . "</u></font></a>";
								} else {
								?>
									<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?php echo str_replace(" ", "%20", $bol_view_row["file_name"]); ?>', 'BOL',<?php echo $tmpcnt; ?>)">
										<font color="blue"><u>View BOL</u></font>
									</a>
							<?php
									$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View BOL</u></font></a>";
								}
							}
							?>
						</td>
					<?php } ?>

					<?php if ($section_lastship_col4_flg == "yes") {

					?>
						<td bgColor="#e4e4e4" class="style3" align="center">
							<?php if ($row["B"] > 0)  echo date('m-d-Y', strtotime($row["B"])); ?>
						</td>
					<?php } ?>

					<?php if ($section_lastship_col4_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center">
							<?php if ($bol_file_row['bol_shipment_received_date'] > 0)  echo date('m-d-Y', strtotime($bol_file_row['bol_shipment_received_date'])); ?>
						</td>
					<?php } ?>
					<?php if ($section_lastship_col5_flg == "yes") {
					?>
						<td bgColor="#e4e4e4" class="style3" align="center">
							<?php if ($row["A"] > 0)   echo number_format($row["A"], 0); ?>
						</td>
					<?php } ?>

					<?php if ($section_lastship_col6_flg == "yes") { ?>
						<td bgColor="#e4e4e4" class="style3" align="center" id="inv_file_show<?php echo $tmpcnt; ?>">
							<?php if ($row1["inv_file"] > 0) {
								if ($row1["inv_number"] != '') {
							?>
									<a href="javascript:void(0);" onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?php echo str_replace(" ", "%20", $row1["inv_file"]); ?>', 'Invoice',<?php echo $tmpcnt; ?>)">
										<font color="blue"><u><?php echo $row1["inv_number"]; ?></u></font>
									</a>
								<?php
									$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $row1["inv_number"] . "</u></font></a>";
								} else {
								?>
									<a href="javascript:void(0);" onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?php echo str_replace(" ", "%20", $row1["inv_file"]); ?>', 'Invoice',<?php echo $tmpcnt; ?>)">
										<font color="blue"><u>View Invoice</u></font>
									</a>
							<?php
									$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View Invoice</u></font></a>";
								}
							}
							?>
						</td>

						<td bgColor="#e4e4e4" class="style3" align="right">
							<?php if ($row1["inv_amount"] > 0) {
								echo "$" . number_format($row1["inv_amount"], 2);
							}
							?>
						</td>
					<?php } ?>

					<?php
					$invoice_age_internal = "";
					$invoice_age_color = "";
					if ($section_lastship_col8_flg == "yes") {
						if ($invoice_age > 30 && $invoice_age < 1000) {
							if ($invoice_paid == 1) {
								$invoice_age_internal = "";
								$invoice_age_color = "#e4e4e4";
					?>
								<td bgColor="#e4e4e4" class="style3" align="center">&nbsp;

								</td>
							<?php
							} else {
								$invoice_age_internal = $invoice_age;
								$invoice_age_color = "#ff0000";
							?>
								<td bgColor="#ff0000" class="style3" align="center">
									<?php echo $invoice_age; ?>
								</td>
							<?php }
						} elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
							$invoice_age_internal = "";
							$invoice_age_color = "#e4e4e4";
							?>
							<td bgColor="#e4e4e4" class="style3" align="center">&nbsp;

							</td>
							<?php
						} else {
							if ($invoice_paid == 1) {
								$invoice_age_internal = "";
								$invoice_age_color = "#e4e4e4";
							?>
								<td bgColor="#e4e4e4" class="style3" align="center">&nbsp;

								</td>
							<?php } else {
								$invoice_age_internal = $invoice_age;
								$invoice_age_color = "#e4e4e4";
							?>
								<td bgColor="#e4e4e4" class="style3" align="center">
									<?php echo $invoice_age; ?>
								</td>
					<?php
							}
						}
					}
					?>

				</tr>

			<?php
				$report_total_loc = count(array_unique($total_loc));
				$tmpcnt = $tmpcnt + 1;

				$MGArray[] = array('inv_amount' => $row1["inv_amount"], 'accountowner' => $accountowner, 'dt_submitted_sort' => $dt_submitted_sort, 'dt_shipped_sort' => $dt_shipped_sort, 'dt_delv_sort' => $dt_delv_sort, 'purchase_order' => $purchase_order, 'viewbol' => $viewbol, 'invoice' => $invoice, 'company' => $nickname, 'dt_submitted' => $dt_submitted, 'status' => $status, 'dt_shipped' => $dt_shipped, 'dt_delv' => $dt_delv, 'boxes' => $boxes, 'boxes_sort' => $boxes_sort, 'invoice_age' => $invoice_age_internal, 'invoice_age_color' => $invoice_age_color, 'total_trans' => $total_trans, 'report_total_loc' => $report_total_loc, 'comp_id' => $comp_id);
				//print_r($MGArray)."<br>";
			}
		}
		if ($section_lastship_col6_flg == "yes") {
			if ($tot_inv_amount > 0) { ?>
				<tr>
					<td bgColor="#e4e4e4" colspan="<?php echo $col_cnt_tmp; ?>" class="style3" align="right">Total Amount:&nbsp;</td>
					<td bgColor="#e4e4e4" class="style3" align="right"><strong><?php echo "$" . number_format($tot_inv_amount, 2); ?></strong></td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>

			<?php	}

			if ($total_trans > 0) {
			?>
				<tr>
					<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
						<?php
						echo "<font size='1'><b>Total Number of Locations: " . $report_total_loc . "";
						?>
					</td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>
				<tr>
					<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
						<?php
						echo "<font size='1'><b>Total Number of Transactions: " . $total_trans . "";
						?>
					</td>
					<td bgColor="#e4e4e4">&nbsp;</td>
				</tr>
	<?php
			}
		}
		$_SESSION['sortarrayn_dsh'] = $MGArray;
	}
	?>
</table>
<div style="text-align:left;"><i>
		<font size="1" color="red">"END OF REPORT"</font>
	</i></div>