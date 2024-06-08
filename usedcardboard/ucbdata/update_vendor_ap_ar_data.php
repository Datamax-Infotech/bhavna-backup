<?
require ("inc/header_session.php");

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

//$vnumrows = $_REQUEST['vnumrows'];
if (isset($_REQUEST['edit_report']) && $_REQUEST['edit_report'] == "yes") {
	$filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
	$allow_ext = explode(",", $filetype);
	$data = [];
	$updated = 0;
	if (isset($_REQUEST['paid_by']) && ($_REQUEST['paid_by'] != "") && ($_REQUEST['paid_by'] != " ")) {
		if (isset($_REQUEST["dueMailByAR"]) && $_REQUEST["dueMailByAR"] != '') {
			$vendorEmail = $_REQUEST["hdnInvcVendorEmail"];
			if ($_REQUEST["dueMailByAR"] == 'soon') {
				$eml_message = "Due Soon";
			} else {
				$eml_message = "Past";
			}
			//Vendor Mail Send Code 
			// sendemail("No", $mailto, $scc, $sbcc, $from_mail, $from_name, $subject, $eml_message);
		}

		if (!empty($_FILES['payment_proof_file'])) {
			$payment_proof_files = "";
			$payment_proof_name = "";
			if (!empty($_FILES['payment_proof_file']['error'][$index])) {
			} else {
				foreach ($_FILES['payment_proof_file']['tmp_name'] as $index => $tmpName) {
					if (!empty($tmpName) && is_uploaded_file($tmpName)) {
						$ext = pathinfo($_FILES["payment_proof_file"]["name"][$index], PATHINFO_EXTENSION);
						if (in_array(strtolower($ext), $allow_ext)) {
							$attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace("/'/", "\'", $_FILES['payment_proof_file']['name'][$index]);
							$payment_proof_files = $payment_proof_files . $attachfile_nm_tmp . "|";
							move_uploaded_file($tmpName, "water_payment_proof/" . $attachfile_nm_tmp);
						}
					}
				}
			}
		}
		$tmppos_1 = strpos($payment_proof_files, "|");
		if ($tmppos_1 != false) {
			if ($payment_proof_name != "") {
				$payment_proof_name = $payment_proof_name . "|" . substr($payment_proof_files, 0, strlen($payment_proof_files, '|') - 1);
			} else {
				$payment_proof_name = substr($payment_proof_files, 0, strlen($payment_proof_files, '|') - 1);
			}
		}
		if ($payment_proof_name != "") {
			$payment_proof_name = preg_replace("/'/", "\'", $payment_proof_name);
		}

		$sqlUpdtVendrPayRpt = "UPDATE water_transaction SET last_edited = '" . date("Y-m-d H:i:s") . "', vendor_payment_log_notes = '" . str_replace("'", "\'", $_REQUEST['vendor_payment_log_notes']) . "', made_payment = '" . $_REQUEST['made_payment'] . "', paid_by = '" . $_REQUEST['paid_by'] . "', paid_date = '" . $_REQUEST['paid_date'] . "',";
		if ($_REQUEST["vendorpagename"] == 'UCBZeroWaste_Vendors_AR.php') {
			$sqlUpdtVendrPayRpt .= " ar_status = '" . $_REQUEST['ar_status'] . "',";
		}
		if ($payment_proof_name != '') {
			$sqlUpdtVendrPayRpt .= " payment_proof_file = '" . preg_replace("/'/", "\'", $payment_proof_name) . "', ";
		}
		$sqlUpdtVendrPayRpt .= " payment_method = '" . $_REQUEST['payment_method'] . "' WHERE invoice_number = '" . $_REQUEST['hdnInvcNo'] . "' AND vendor_id = '" . $_REQUEST['hdnvendrId'] . "' and id = '" . $_REQUEST['hdnWatrTrnstnId'] . "'";
		$result_sort = db_query($sqlUpdtVendrPayRpt, db());

		$get_updated_data_sql = db_query("SELECT made_payment,paid_by,paid_date,payment_method, payment_proof_file,ar_status,vendor_payment_log_notes FROM water_transaction WHERE invoice_number = '" . $_REQUEST['hdnInvcNo'] . "' AND vendor_id = '" . $_REQUEST['hdnvendrId'] . "' and id = '" . $_REQUEST['hdnWatrTrnstnId'] . "'");
		while ($row = array_shift($get_updated_data_sql)) {
			$data['made_payment'] = $row['made_payment'];
			$data['paid_by'] = $row['paid_by'];
			$data['paid_date'] = $row['paid_date'];
			$data['payment_method'] = $row['payment_method'];
			$data['payment_proof_file'] = $row['payment_proof_file'];
			$data['ar_status'] = ucfirst($row['ar_status']);
			$data['view_vendor_payment_log_notes'] = $row['vendor_payment_log_notes'];
			//$data['row_id']=$i;
		}
		$updated = 1;
	}
	echo json_encode(array('updated' => $updated, 'data' => $data));

} else if (isset($_REQUEST['get_all_notes']) && $_REQUEST['get_all_notes'] == "yes") {
	$vendor_id_comm = $_REQUEST["vendor_id"];
	$data_str = "";
	if ($_REQUEST['type'] == "payable") {
		$special_notes_qry = db_query("SELECT payable_notes, payable_contact_name,id from water_vendors_payable_contact where water_vendor_id='$vendor_id_comm' AND payable_notes!='' ORDER BY created_on DESC", db());
		$data_str .= "<table class='notes_tbl'><tr><th>Payable Contact Id</th><th>Payable Name</th><th>Payable Notes</th></tr>";
		if (tep_db_num_rows($special_notes_qry) > 0) {
			while ($res = array_shift($special_notes_qry)) {
				$data_str .= "<tr><td>" . $res['id'] . "</td><td style='white-space:nowrap'>" . $res['payable_contact_name'] . "</td><td>" . $res['payable_notes'] . "</td></tr>";
			}
		} else {
			$data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
		}
		$data_str .= "</table><br>";
	} else {
		$special_notes_qry = db_query("SELECT receivable_notes, receivable_contact_name,id from water_vendors_receivable_contact where water_vendor_id='$vendor_id_comm' AND receivable_notes!='' ORDER BY created_on DESC", db());
		$data_str .= "<table class='notes_tbl'><tr><th>Receivable Contact Id</th><th>Receivable Name</th><th>Receivable Notes</th></tr>";
		if (tep_db_num_rows($special_notes_qry) > 0) {
			while ($res = array_shift($special_notes_qry)) {
				$data_str .= "<tr><td>" . $res['id'] . "</td><td style='white-space:nowrap'>" . $res['receivable_contact_name'] . "</td><td>" . $res['receivable_notes'] . "</td></tr>";
			}
		} else {
			$data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
		}
		$data_str .= "</table><br>";
	}
	echo $data_str;
}
if (isset($_REQUEST['send_invoice']) && $_REQUEST['send_invoice'] == 1) {
	$transid = $_REQUEST['transid'];
	db();
	$select_scan_reports = db_query("SELECT scan_report from water_transaction where id = " . $transid);
	$files = array();
	$response = 0;
	if (tep_db_num_rows($select_scan_reports) > 0) {
		while ($rows = array_shift($select_scan_reports)) {
			$tmppos_1 = strpos($rows["scan_report"], "|");
			if ($tmppos_1 != false) {
				$elements = explode("|", $rows["scan_report"]);
				for ($i = 0; $i < count($elements); $i++) {
					$files[] = $elements[$i];
				}
			} else {
				$files[] = $rows['scan_report'];
			}
		}
		//print_r($files);
		//UCBZeroWaste@bill.com
		$res = sendemail_attachment_new($files, "water_scanreport/", "bhavana.patidar@extractinfo.com", "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "", "Vendor A/P Aging Invoice", "");
		if ($res == "emailsend") {
			if ($_REQUEST['flg'] == 1) {
				$update_invoice_sent_flg = db_query("UPDATE water_transaction set send_invoice_flg = 1 , invoice_sent_on = '" . date("Y-m-d H:i:s") . "', invoice_sent_by = '" . $_COOKIE['b2b_id'] . "' where id = " . $transid);
			}
			$response = 1;
		}
	}
	echo $response;
}
?>