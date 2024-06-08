<?
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
?>
<!DOCTYPE html>

<head>
	<title>UCBZeroWaste Invoice Entry</title>
	<style>
		body {
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
			font-size: 0.85rem !important;
		}

		.form-control {
			border-radius: 0;
		}

		.form-control-sm {
			font-size: 0.8rem !important;
		}

		.thead-dark td {
			color: #000;
			background-color: #E5E5E5;
			text-align: center;
		}

		.table th,
		.table td {
			vertical-align: middle !important;
		}

		.top-box {
			padding: 15px;
			box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.14);
			font-size: 20px;
		}

		.btn.btn-outline-primary {
			color: rgba(38, 102, 180, 1);
			border: 1px solid rgba(38, 102, 180, 1);
		}

		.btn.btn-primary {
			background: rgba(38, 102, 180, 1);
		}

		.btn.btn-outline-primary:hover {
			color: rgba(38, 102, 180, 1);
			border: 1px solid rgba(38, 102, 180, 1);
			background-color: #FFF;

		}

		.btn.btn-primary:hover {
			background: rgba(38, 102, 180, 1);
		}

		.form-group {
			margin-bottom: .5rem !important;
		}

		.highlight_error:focus {
			border: solid 1px red;
		}

		.newvendor_topBarClose {
			float: left;
			font-size: 24px;
			margin: 4px 20px 4px 0;
			cursor: pointer;
			background: white;
			line-height: .75;
			color: rgba(38, 102, 180, 1);
			text-decoration: none;
			border: none;
		}

		.mapping_table .form-control {
			padding-left: 0px;
			padding-right: 0px;
		}

		.mapping_table {
			background-color: #FFF;
		}

		.mapping_table thead {
			position: sticky;
			top: 0;
			z-index: 1000;
			background: #FFF;
		}

		.default_table,
		.default_table .thead-dark td,
		td {
			color: gray;
		}

		.selected_table,
		.selected_table .thead-dark td,
		td {
			color: #212529;
		}

		.nowrap_word {
			white-space: nowrap;
		}

		.table_container {
			max-height: 380px;
			overflow-y: auto;
			margin-top: 20px;
		}

		#contentsplitter {
			border: 1px solid #C4C4C4;
		}

		.btn.removeButton:focus,
		.btn.removeButton.focus,
		.btn.add_tbl_row.focus,
		.btn.add_tbl_row:focus {
			box-shadow: none;
			outline: none;
		}

		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}

		table {
			width: 100%;
			margin-bottom: 20px;
			border-collapse: collapse;
		}

		th,
		td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}

		tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		tr:hover {
			background-color: #ddd;
		}

		.context-menu {
			display: none;
			position: absolute;
			z-index: 1000;
			background-color: white;
			border: 1px solid #ccc;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
			cursor: pointer;
			top: 13px;
		}

		#settingModal tr:hover {
			background: #FFF;
		}

		.arrow-button {
			cursor: pointer;
		}
	</style>
	<link href="css/bootstrap4.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet" />

	<script type="text/javascript" src="scripts/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
	<script type="text/javascript" src="scripts/pdf-lib.js"></script>
	<script type="text/javascript" src="scripts/utils.js"></script>
	<script type="text/javascript" src="https://unpkg.com/@pdf-lib/fontkit/dist/fontkit.umd.js"></script>
	<script src="splitterview/js/jquery.enhsplitter.js"></script>
	<link href="splitterview/css/jquery.enhsplitter.css" rel="stylesheet" />
	<script>
		jQuery(function($) {
			// $('#demoOne').enhsplitter({minSize: 50, vertical: false});
			$('#contentsplitter').enhsplitter({
				leftMinSize: 250,
				rightMinSize: 370
			});
			//$('#demoThree').enhsplitter({handle: 'bar', position: 150, leftMinSize: 0, fixed: true});
		});
	</script>
	<script>
		var select_options = "<select class='form-control form-control-sm generic_field_dropdown'><option></option></select>";
		$(".generic_field_dropdown_div").html(select_options);

		function loaddata(warehouse_id, editflg, compid, loadvendor) {
			compid = document.getElementById("company_id").value;
			vendor_id = document.getElementById("vendor_id").value;
			if (vendor_id == "addvendor") {
				window.open("https://loops.usedcardboardboxes.com/water_vendor_master_new.php?proc=New&compid=" + compid, "_blank");
				return;
			}

			if (loadvendor == 1) {
				if (window.XMLHttpRequest) {
					xmlhttp1 = new XMLHttpRequest();
				} else {
					xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp1.onreadystatechange = function() {
					if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
						document.getElementById("div_vendor").innerHTML = xmlhttp1.responseText;
						$('#div_vendor .details').html('Vendor');
					}
				}

				xmlhttp1.open("POST", "water_ocr_entry_get_vendor.php?warehouse_id=" + warehouse_id + "&company_id=" + compid + "&editflg=" + editflg, true);
				xmlhttp1.send();


			} else {
				vendor_id = document.getElementById("vendor_id").value;
				if (window.XMLHttpRequest) {
					xmlhttp1 = new XMLHttpRequest();
				} else {
					xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp1.onreadystatechange = function() {
					if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
						$(".water_material_select").html(xmlhttp1.responseText);
					}
				}

				xmlhttp1.open("POST", "water_ocr_entry_get_material.php?warehouse_id=" + warehouse_id + "&vendor_id=" + vendor_id, true);
				xmlhttp1.send();
			}
		}

		function onsubmitform() {
			var ocr_file = $('#ocr_file').val();
			if (ocr_file == "") {
				alert("Please select the invoice pdf file for mapping!");
				return false;
			} else {
				return true;
			}

		}

		function close_page() {
			window.open("https://loops.usedcardboardboxes.com/water_invoice_ocr_inbox.php", "_self");
		}
	</script>
</head>

<body>
	<?
	$ocr_id = "";
	if (isset($_REQUEST["ocr_id"])) {
		$ocr_id = $_REQUEST["ocr_id"];
	}
	$inview = "no";
	if (isset($_REQUEST["inview"])) {
		$inview = $_REQUEST["inview"];
	}

	$inedit = "no";
	if (isset($_REQUEST["inedit"])) {
		$inedit = $_REQUEST["inedit"];
	}
	$accuracy_threshold = 0.80;
	db_water_inbox_email();
	$vendor_id = "";
	$company_id = "";
	$warehouse_id = "";
	$water_trans_rec_id = "";
	$mainstr = "";
	$filename = "";
	$org_filename = "";
	$ocr_inv_number = "";
	$ocr_inv_date = "";
	$invoice_date_accuracy = 0;
	$inv_number_accuracy = 0;
	$water_invoice_email_ocr_id = "";
	$invoice_amount = "";
	$invoice_amount_accuracy = "";
	$account_no = "";
	$qry = "Select * from water_invoice_email_ocr where unqid = '" . $ocr_id . "'";
	$row1 = db_query($qry, db_water_inbox_email());
	while ($main_res1 = array_shift($row1)) {
		$water_invoice_email_ocr_id = $main_res1["unqid"];
		$vendor_id = $main_res1["vendor_id"];
		$account_no = $main_res1["account_no"];
		$company_id = $main_res1["company_id"];
		$water_trans_rec_id = $main_res1["water_trans_rec_id"];
		$ocr_inv_number = $main_res1["inv_number"];
		$inv_number_accuracy = $main_res1["inv_number_accuracy"];
		$ocr_inv_date = $main_res1["invoice_date"];
		$invoice_date_accuracy = $main_res1["invoice_date_accuracy"];
		$rec_id = $water_trans_rec_id;

		$invoice_amount = round($main_res1["invoice_amount"], 2);
		$invoice_amount_accuracy = $main_res1["invoice_amount_accuracy"];

		$mainstr = $main_res1["ocr_extract_text"];
		$org_filename = str_replace("-", " ", $main_res1["email_attachment"]);

		$qry1 = "Select inward_date from tblemail where unqid = '" . $main_res1["emailid"] . "'";
		$row2 = db_query($qry1);
		while ($main_res2 = array_shift($row2)) {
			$inward_date = $main_res2["inward_date"];
		}

		$filename = Date("Y", strtotime($inward_date)) . "_" . Date("m", strtotime($inward_date)) . "/" . $main_res1["emailid"] . "/" . $main_res1["email_attachment"];
	}


	db();
	$template_name = "";
	$qry = "Select template_name from water_ocr_account_mapping where account_no = '" . $account_no . "'";
	$row1 = db_query($qry);
	while ($main_res1 = array_shift($row1)) {
		$template_name = $main_res1["template_name"];
	}

	$qry = "Select id from loop_warehouse where b2bid = '" . $company_id . "'";
	$row1 = db_query($qry);
	while ($main_res1 = array_shift($row1)) {
		$warehouse_id = $main_res1["id"];
	}

	$water_entry_done = "no";
	$qry = "Select report_entered from water_transaction where id = '" . $water_trans_rec_id . "'";
	$row1 = db_query($qry);
	while ($main_res1 = array_shift($row1)) {
		$water_entry_done = "yes";
	}

	$company_id = 0;
	$warehouse_id = 0;

	$rec_type = "Manufacturer";
	/*$rec_id = 0;
    if ($water_trans_rec_id == 0)
    {	
        $qry_newtrans = "INSERT INTO water_transaction SET company_id = '" . $company_id . "', tran_status = 'Pickup'";
        $res_newtrans = db_query($qry_newtrans);
        
        $rec_id = tep_db_insert_id();
        
        db_water_inbox_email();	
        $qry_newtrans = "Update water_invoice_email_ocr SET water_trans_rec_id = '" . $rec_id . "' where unqid = '" . $water_invoice_email_ocr_id . "'";
        $res_newtrans = db_query($qry_newtrans);

    }else{
        $rec_id = $water_trans_rec_id;
    }
    */
	db();

	if ($inview != "yes") {

		if ($inedit == "yes") {

			$dt_view_tran_qry = "SELECT * from water_transaction WHERE id = " . $rec_id;
			$dt_view_tran = db_query($dt_view_tran_qry, db());
			$dt_view_tran_row = array_shift($dt_view_tran);

			$saved_ocr_val_flg = $dt_view_tran_row["saved_ocr_val_flg"];

			$chkdoubt_val = "";
			if ($dt_view_tran_row["have_doubt"] == 1) {
				$chkdoubt_val = " checked ";
			}
			$doubt = $dt_view_tran_row["doubt"];
			//$company_id = $dt_view_tran_row["company_id"];
			$vendor_id = $dt_view_tran_row["vendor_id"];
			$report_date = $dt_view_tran_row["report_date"];
			$service_begin_date = $dt_view_tran_row["service_begin_date"];
			$invoice_due_date = $dt_view_tran_row["invoice_due_date"];
			$invoice_date = $dt_view_tran_row["invoice_date"];
			$new_invoice_date = $dt_view_tran_row["new_invoice_date"];
			$ocr_inv_number = $dt_view_tran_row["invoice_number"];
			$vendors_net_cost_or_revenue = $dt_view_tran_row["vendors_net_cost_or_revenue"];
			$client_net_savings = $dt_view_tran_row["client_net_savings"];
			$ucb_savings_split = $dt_view_tran_row["ucb_savings_split"];
			$total_due_to_ucb = $dt_view_tran_row["total_due_to_ucb"];

			$company_name = "";
			$warehouse_id = 0;
			$q1 = "SELECT nickname, loopid FROM companyInfo where ID = '" . $company_id  . "'";
			$query = db_query($q1, db_b2b());
			while ($fetch = array_shift($query)) {
				$company_name = $fetch['nickname'];
				$warehouse_id = $fetch['loopid'];
			}

			$vender_nm = "";
			$q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $dt_view_tran_row["vendor_id"] . "'";
			$query = db_query($q1, db());
			while ($fetch = array_shift($query)) {
				$vender_nm = $fetch['Name'];
			}
		}
	?>

		<div class="top-box d-flex justify-content-between" role="alert">
			<span>
				<a role="link" tabindex="0" class="newvendor_topBarClose" onclick="close_page();" aria-label="Close overlay"> × </a>
				&nbsp;&nbsp;<span><b>Water OCR - Mapping Tool</b></span>
			</span>
			<span>
				<a href="water_invoice_ocr_mapping_templates.php" class="btn btn-outline-primary btn-sm">View All Templates</a>
			</span>
		</div>
		</div>


		<? if (isset($_GET['action']) && $_GET['action'] == 'edit') {
			$template_id = $_GET['id'];
			$account_query = db_query("SELECT * FROM water_ocr_account_mapping where unqid=$template_id", db());
			$account_data = array_shift($account_query);
			$company_id = $account_data['company_id'];
			$vendor_id = $account_data['vendor_id'];
			$ocr_file = $account_data['ocr_file_name'];

			$qry = "Select id from loop_warehouse where b2bid = '" . $company_id . "'";
			$row1 = db_query($qry);

			while ($main_res1 = array_shift($row1)) {
				$warehouse_id = $main_res1["id"];
			}
		?>

			<div class="container-fluid p-0">
				<div>
					<div id="account_no_not_found_container_for_html" class="d-none">
						<div class="row form-group">
							<div class="offset-md-3"></div>
							<di class="col-md-9">
								<div class="row">
									<div class="col-sm-6 generic_field_dropdown_div">
										<select class='form-control form-control-sm generic_field_dropdown' name='acc_no_not_found_mapped_field[]'>
											<option></option>
											<?php
											$water_ocr_all_generic_fields_dropdown = db_query("SELECT * FROM water_ocr_all_generic_fields_dropdown where template_id = $template_id", db());

											while ($fields_arr_generic = array_shift($water_ocr_all_generic_fields_dropdown)) {
												$coordinates_op = explode("|", $fields_arr_generic['coordinates']);
												echo '<option ' . (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" option_val="' . $fields_arr_generic['field_val'] . '">'
													. $fields_arr_generic['field_name']
													. '</option>';
											}
											?>
										</select>
									</div>
									<div class="col-sm-6 d-flex">
										<input type="text" name="acc_no_not_found_field_value[]" class="form-control form-control-sm generic_field_input">
										<button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
									</div>
								</div>
						</div>
					</div>
					<div id="contentsplitter" style="height:100vh">
						<div id="leftpanel_view">
							<div class="col-md-12 mt-4">
								<form name="frmsort" method="post" action="#" encType="multipart/form-data" id="mapping_tool_main_form">
									<p id="ready_setup_pdf" class="text-primary">You are editing <b>`<?php echo $account_data['ocr_file_name']; ?>`.</b>.</p>
									<div class="form-group row mt-2">
										<label class="col-sm-3 col-form-label">Company</label>
										<div class="col-sm-9">
											<select id="company_id" name="company_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?= $company_id ?>, 1)" class="form-control form-control-sm" required>
												<option value=""></option>
												<?
												$query = db_query("SELECT ID, nickname FROM companyInfo where active = 1 and ucbzw_account_status = 83 order by nickname", db_b2b());
												while ($rowsel_getdata = array_shift($query)) {
													$tmp_str = "";
													if (isset($_REQUEST["company_id"])) {
														if ($_REQUEST["company_id"] == $rowsel_getdata["ID"]) {
															$tmp_str = " selected ";
														}
													} else {
														if ($account_data['company_id'] == $rowsel_getdata["ID"]) {
															$tmp_str = " selected ";
														}
													}
												?>
													<option value="<? echo $rowsel_getdata["ID"]; ?>" <? echo $tmp_str; ?>><? echo $rowsel_getdata['nickname']; ?></option>
												<? }
												?>
											</select>
										</div>
									</div>
									<div id="div_vendor" class="full_input__box form-group row mt-2">
										<script>
											if (window.XMLHttpRequest) {
												xmlhttp1 = new XMLHttpRequest();
											} else {
												xmlhttp1 = new ActiveXObject("Microsoft.XMLHTTP");
											}
											xmlhttp1.onreadystatechange = function() {
												if (xmlhttp1.readyState == 4 && xmlhttp1.status == 200) {
													document.getElementById("div_vendor").innerHTML = xmlhttp1.responseText;
													$("#vendor_id").val('<?= $vendor_id ?>');
													$('#div_vendor .details').html('Vendor');
												}
											}

											xmlhttp1.open("POST", "water_ocr_entry_get_vendor.php?warehouse_id=" + '<?= $warehouse_id; ?>' + "&company_id=" + '<?= $company_id; ?>' + "&editflg=0", true);
											xmlhttp1.send();
										</script>
									</div>
									<div class="form-group row mt-2">
										<label class="col-sm-3 col-form-label">Template name</label>
										<div class="col-sm-9">
											<input type="text" value="<?= $account_data['template_name']; ?>" name="template_name" class="form-control form-control-sm" id="template_name" onblur="check_template_name()" required />
											<p id="template_name_error" class="text-danger mb-0 d-none highlight_error">Template name already exists, use other! </p>
										</div>
									</div>
									<?
									/* $inv_file_for_ocr = "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/upload/" . $account_data;
							$mainstr = shell_exec("/usr/bin/node analyzeDocumentByModelIducbdata.js '". escapeshellarg($inv_file_for_ocr) ."'");
							*/

									$mapping_field_sql = db_query("SELECT *, gf.id as uniq, fv.field_name as maaped_field_name, fv.id as fv_id FROM water_ocr_mapping_field_and_values as fv JOIN water_ocr_account_mapping_generic_fields gf ON gf.id = fv.mapped_with where fv.template_id = $template_id", db());
									//echo "SELECT *, gf.id as uniq, fv.field_name as maaped_field_name, fv.id as fv_id FROM water_ocr_mapping_field_and_values as fv JOIN water_ocr_account_mapping_generic_fields gf ON gf.id = fv.mapped_with where fv.template_id = $template_id";
									$field_count = 1;

									while ($fields_arr = array_shift($mapping_field_sql)) {
										$field_name_unq = strtolower(str_replace(' ', '_', $fields_arr['field_name']));
									?>
										<div id="mapping_field_div_<?= $field_count ?>">
											<div class="form-group row mt-2">
												<label class="col-sm-3 col-form-label">
													<? echo $fields_arr['field_name'];
													$checked_str = $account_data['account_no'] == 0 ? " checked " : "";

													$req_txt = "";
													if ($fields_arr['field_name'] == "Account number") {
														$req_txt = " required ";
													}

													if ($field_count == 1)
														echo "<small class='d-flex align-items-center '><input type='checkbox' id='checkbox_account_no' $checked_str />&nbsp;Acct. No not found</small>";

													?>
												</label>
												<div class="col-md-9">
													<div class="row">
														<div class="col-sm-6 generic_field_dropdown_div">
															<select class='form-control form-control-sm generic_field_dropdown' <?= $field_count == 1 && $account_data['account_no'] == 0 ? " readonly " : "" ?> name='mapped_field[]' id="<?= $field_name_unq . "_dp"; ?>" <? echo $req_txt; ?>>
																<option></option>
																<?php
																$water_ocr_all_generic_fields_dropdown = db_query("SELECT * FROM water_ocr_all_generic_fields_dropdown where template_id = $template_id", db());
																$input_coordinates = array();
																$option_val = "";
																$selected_op = "";
																while ($fields_arr_generic = array_shift($water_ocr_all_generic_fields_dropdown)) {
																	$coordinates_op = explode("|", $fields_arr_generic['coordinates']);
																	if (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name']) {
																		$input_coordinates = $coordinates_op;
																		$option_val = $fields_arr_generic['field_val'];
																		$selected_op = $fields_arr_generic['field_name'];
																	}
																	echo '<option ' . (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" option_val="' . $fields_arr_generic['field_val'] . '">'
																		. $fields_arr_generic['field_name']
																		. '</option>';
																}
																?>
															</select>
														</div>
														<div class="col-sm-6">
															<input type="hidden" name="mapped_with[]" value="<?= $fields_arr['uniq']; ?>">
															<input type="hidden" name="fv_id[]" value="<?= $fields_arr['fv_id']; ?>">
															<input type="text" <?= $field_count == 1 && $account_data['account_no'] == 0 ? " readonly " : "" ?> value="<?= $fields_arr['field_value']; ?>" name="field_value[]" id="<?= $field_name_unq; ?>" class="form-control form-control-sm generic_field_input <?= $fields_arr['field_type'] ? "date" : ""; ?>" cor_x="<?php echo $input_coordinates[0]; ?>" cor_y="<?php echo $input_coordinates[1]; ?>" w="<?php echo $input_coordinates[2]; ?>" h="<?php echo $input_coordinates[3]; ?>" option_val="<?php echo $option_val; ?>" selected_op="<?php echo $selected_op; ?>" <? echo $req_txt; ?>>
														</div>
													</div>
												</div>
											</div>
											<? if ($field_count == 1) { ?>
												<div id="accout_no_not_found_div" class="<?= $account_data['account_no'] == 0 ? "" : "d-none"; ?> border py-2">
													<div id="account_no_not_found_container">
														<?
														if ($account_data['account_no'] == 0) {
															$select_no_fields = db_query("SELECT * FROM water_ocr_account_mapping_account_not_found where template_id = $template_id", db());
															while ($no_account_data = array_shift($select_no_fields)) {
														?>
																<div class="row form-group">
																	<div class="offset-md-3"></div>
																	<div class="col-md-9">
																		<div class="row">
																			<div class="col-sm-6 generic_field_dropdown_div">
																				<select class='form-control form-control-sm generic_field_dropdown' <?= $field_count == 1 && $account_data['account_no'] == 0 ? " readonly " : "" ?> name='acc_no_not_found_mapped_field[]' required>
																					<option></option>
																					<?php
																					$water_ocr_all_generic_fields_dropdown = db_query("SELECT * FROM water_ocr_all_generic_fields_dropdown where template_id = $template_id", db());
																					$input_coordinates = array();
																					$option_val = "";
																					$selected_op = "";
																					while ($fields_arr_generic = array_shift($water_ocr_all_generic_fields_dropdown)) {
																						$coordinates_op = explode("|", $fields_arr_generic['coordinates']);
																						if (trim($fields_arr_generic['field_name']) == $no_account_data['field_name']) {
																							$input_coordinates = $coordinates_op;
																							$option_val = $fields_arr_generic['field_val'];
																							$selected_op = $fields_arr_generic['field_name'];
																						}
																						echo '<option ' . (trim($fields_arr_generic['field_name']) == $no_account_data['field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" option_val="' . $fields_arr_generic['field_val'] . '">'
																							. $fields_arr_generic['field_name']
																							. '</option>';
																					}
																					?>
																				</select>
																			</div>
																			<div class="col-sm-6 d-flex">
																				<input type="text" value="<?= $no_account_data['field_value']; ?>" name="acc_no_not_found_field_value[]" cor_x="<?php echo $input_coordinates[0]; ?>" cor_y="<?php echo $input_coordinates[1]; ?>" w="<?php echo $input_coordinates[2]; ?>" h="<?php echo $input_coordinates[3]; ?>" option_val="<?php echo $option_val; ?>" selected_op="<?php echo $selected_op; ?>" class="form-control form-control-sm generic_field_input">
																				<input type="hidden" name="no_acc_fv_id[]" value="<?= $no_account_data['id']; ?>">
																				<button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field" field_value_id="<?= $no_account_data['id']; ?>"><i class="fa fa-times"></i></button>
																			</div>
																		</div>
																	</div>
																</div>
															<? } ?>
														<? } else { ?>
															<div class="row form-group">
																<div class="offset-md-3"></div>
																<div class="col-md-9">
																	<div class="row">
																		<div class="col-sm-6 generic_field_dropdown_div">
																			<select class='form-control form-control-sm generic_field_dropdown generic_field_dropdown_add' name="acc_no_not_found_mapped_field[]">
																				<option></option>
																			</select>
																		</div>
																		<div class="col-sm-6 d-flex">
																			<input type="text" name="acc_no_not_found_field_value[]" class="form-control form-control-sm generic_field_input">
																			<button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
																		</div>
																	</div>
																</div>
															</div>
														<? } ?>
													</div>
													<div class="text-right"><button type="button" id="add_more_field_for_unqiue_account_no" class="btn-sm btn btn-success">Add Field to Create Account No</button></div>

												</div>
											<?
											}
											?>

										</div>
									<? $field_count++;
									} ?>
									<div class="col-md-12 p-0 table-responsive mt-3 secondary_array_table">
										<?php
										$select_consider_checkbox = db_query("SELECT * FROM water_ocr_consideration_of_tables_for_mapping where template_id = $template_id ORDER BY id ASC", db());
										while ($consider_check = array_shift($select_consider_checkbox)) {
											$table_no = $consider_check['table_no'];
											$select_tbl_heads = db_query("SELECT * FROM water_ocr_mapping_item_list_table_header where table_no = $table_no && template_id = $template_id ORDER BY id ASC", db());
											$tbl_heads_colspan = array_shift($select_tbl_heads);
											$colspan_count = count(explode('&&', $tbl_heads_colspan['column_text'])) + 6;
										?>
											<div class="table_container">
												<table class="table table-sm table-bordered mapping_table <?php echo $consider_check['consideration'] == 1 ? 'selected_table' : 'default_table' ?>">
													<thead>
														<tr>
															<td colspan="<?php echo $colspan_count; ?>">
																<input type="checkbox" id="chk_water_mapping" class="consider_table_checkbox" name="consider_table_checkbox[]" title="1" <?php echo $consider_check['consideration'] == 1 ? 'checked' : '' ?>>
																<b class="text-dark">Consider this table for Water mapping</b>
																<input type="hidden" name="consider_water_mapping_table_array[]" value="<?php echo $consider_check['consideration'] == 1 ? 1 : 0; ?>">
																<input type="hidden" name="consider_water_mapping_table_id_array[]" value="<?php echo $consider_check['id']; ?>">
																<input type="hidden" class="added_now_count" value="0">
															</td>
														</tr>
														<tr class="thead-dark">
															<?php
															$select_tbl_heads = db_query("SELECT * FROM water_ocr_mapping_item_list_table_header where table_no = $table_no && template_id = $template_id ORDER BY id ASC", db());
															while ($tbl_heads = array_shift($select_tbl_heads)) {
																$column_text_array = explode('&&', $tbl_heads['column_text']);
																$column_no_array = explode('&&', $tbl_heads['tbl_column_no']);
																$selected_value_array = explode('&&', $tbl_heads['selected_value']);
															?>
																<td>Material
																	<input type="hidden" name="water_material<?php echo $table_no; ?>[]">
																	<input type="hidden" name="water_list_item_header_id<?php echo $table_no; ?>" value="<?php echo $tbl_heads['id']; ?>">
																</td>
																<td>Fee<input type="hidden" name="add_fee<?php echo $table_no; ?>[]"></td>
																<td>Consider Line Item<input type="hidden" name="consider_line_item_array<?php echo $table_no; ?>[]"></td>
																<td>Group text<input type="hidden" name="group_text_array<?php echo $table_no; ?>[]"></td>
																<td><span data-toggle="tooltip" data-placement="top" title="OCR Text">
																		OCR Text
																		<input type="hidden" name="text_start_position<?php echo $table_no; ?>[]">
																		<input type="hidden" name="text_end_position<?php echo $table_no; ?>[]">
																		<input type="hidden" name="row_no<?php echo $table_no; ?>[]" value="0">
																		<input type="hidden" name="ocr_selected_text<?php echo $table_no; ?>[]">

																	</span></td>
																<?php
																$total_head_fields = count($column_text_array);
																for ($index = 0; $index < count($column_text_array); $index++) { ?>
																	<td><span class="nowrap_word"><?php echo $column_text_array[$index]; ?></span>
																		<input type="hidden" name="tbl_column_no-<?php echo $table_no; ?>[]" value="<?php echo $column_no_array[$index]; ?>">
																		<input type="hidden" name="tbl_column_text-<?php echo $table_no; ?>[]" value="<?php echo $column_text_array[$index]; ?>">
																		<select name="table_mapping_field<?php echo $table_no; ?>0[]" class="form-control form-control-sm mt-1">
																			<option <?php echo $selected_value_array[$index] == "" ? "selected" : "" ?>></option>
																			<option <?php echo $selected_value_array[$index] == "Material/Fee column" ? "selected" : "" ?>>Material/Fee column</option>
																			<option <?php echo $selected_value_array[$index] == "Quantity" ? "selected" : "" ?>>Quantity</option>
																			<option <?php echo $selected_value_array[$index] == "Unit Price" ? "selected" : "" ?>>Unit Price</option>
																			<option <?php echo $selected_value_array[$index] == "Amount" ? "selected" : "" ?>>Amount</option>
																		</select>
																	</td>
																<?php } ?>
															<?php } ?>
														</tr>
													</thead>
													<tbody>
														<?php
														//echo "SELECT * FROM water_ocr_mapping_item_list_table_data where table_no = $table_no && template_id = $template_id ORDER BY table_no";
														$select_mapped_data = db_query("SELECT * FROM water_ocr_mapping_item_list_table_data where table_no = $table_no && template_id = $template_id ORDER BY table_no");
														$count1 = 1;
														while ($mapped_data = array_shift($select_mapped_data)) {
														?>
															<tr>
																<td>
																	<div class="d-flex water_material_div">
																		<select class="form-control form-control-sm mr-1 water_material_select" name="water_material<?php echo $table_no; ?>[]">
																			<option value=""></option>
																			<?

																			$get_boxes_query = "SELECT *, water_inventory.id as boxid FROM water_boxes_to_warehouse 
															INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id 
															WHERE water_boxes_to_warehouse.water_warehouse_id = " . $warehouse_id . " and vendor = '" . $vendor_id . "' ORDER BY description";
																			$query = db_query($get_boxes_query, db());
																			while ($myrowsel = array_shift($query)) {

																				//$blank_row_str = "<option value='".$myrowsel["boxid"]."|" . $myrowsel["CostOrRevenuePerUnit"] . "|" . $myrowsel["AmountUnit"] . "|" . $myrowsel["Amount"]. "|" . $myrowsel["Outlet"]. "|" . $myrowsel["WeightorNumberofPulls"] . "|" . $myrowsel["CostOrRevenuePerPull"]. "|" . $myrowsel["CostOrRevenuePerItem"]. "|" . $myrowsel["Estimatedweight"] . "|" . $myrowsel["Estimatedweight_value"]. "|" . $myrowsel["Estimatedweight_peritem"]. "|" . $myrowsel["Estimatedweight_value_peritem"]. "|" . $myrowsel["poundpergallon_value"];
																				$blank_row_str = "<option value='" . $myrowsel["boxid"] . "'";
																				if ($myrowsel["boxid"] == $mapped_data['water_material']) {
																					$blank_row_str .= " selected ";
																				}

																				$blank_row_str .= " >" . $myrowsel["description"] . "/" . $myrowsel["WeightorNumberofPulls"] . "</option>";

																				echo $blank_row_str;
																			}
																			?>
																		</select>
																		<input type="hidden" class="water_list_item_body_id" name="water_list_item_body_id<?php echo $table_no; ?>[]" value="<?php echo $mapped_data['id']; ?>">
																	</div>
																</td>
																<td>
																	<select class="form-control form-control-sm mr-1 p-0" name="add_fee<?php echo $table_no; ?>[]" style="min-width:40px">
																		<option value=""></option>
																		<?
																		$query = db_query("SELECT id,additional_fees_display FROM water_additional_fees  where active_flg = 1 order by display_order", db());
																		while ($rowsel_getdata = array_shift($query)) {
																		?>
																			<option <?php echo $rowsel_getdata["id"] == $mapped_data['add_fee'] ? "selected" : ""; ?> value="<? echo $rowsel_getdata["id"]; ?>"><? echo $rowsel_getdata['additional_fees_display']; ?></option>
																		<? }
																		?>
																	</select>
																</td>
																<td>
																	<input class="consider_line_item" type="checkbox" <?php echo $mapped_data['line_item'] == 1 ? 'checked' : ''; ?>>
																	<input type="hidden" class="consider_line_item_array" name="consider_line_item_array<?php echo $table_no; ?>[]" value="<?php echo $mapped_data['line_item']; ?>">
																</td>
																<td>
																	<input type="checkbox" class="group_text_checkbox" <?php echo $mapped_data['group_text'] == 1 ? 'checked' : ''; ?>>
																	<input type="hidden" class="group_text_array" name="group_text_array<?php echo $table_no; ?>[]" value="<?php echo $mapped_data['group_text']; ?>">
																</td>
																<td>
																	<input type="hidden" name="text_start_position<?php echo $table_no; ?>[]" id="row_txt_start_position<?php echo ($table_no + 2) . '' . $count1; ?>" class="form-control form-control-sm row_txt_start_position" value="<?php echo $mapped_data['start_position'] == 0 ? "" : $mapped_data['start_position']; ?>" size="5">
																	<input type="hidden" name="text_end_position<?php echo $table_no; ?>[]" id="row_txt_end_position<?php echo ($table_no + 2) . '' . $count1; ?>" class="form-control form-control-sm row_txt_end_position" value="<?php echo $mapped_data['end_position'] == 0 ? "" : $mapped_data['end_position']; ?>" size="5">
																	<input type="hidden" class="row_no_text" name="row_no<?php echo $table_no; ?>[]" value="<?php echo $mapped_data['row_no']; ?>">

																	<div id='divrow_txt-<?php echo ($table_no + 2) . '-' . $count1; ?>' class="divrow_txt"><small><?php echo $mapped_data['ocr_selected_text'] ?></small></div>
																	<input class="input_row_txt" id='input_row_txt-<?php echo ($table_no + 2) . '-' . $count1; ?>' type="hidden" name="ocr_selected_text<?php echo $table_no; ?>[]" value="<?php echo $mapped_data['ocr_selected_text'] ?>">

																</td>
																<?php
																$field_data_arr = explode('&&', $mapped_data['field_data']);
																//print_r($mapped_data['coordinates']);
																$coordinates_arr_row = explode('&&', $mapped_data['coordinates']);
																//print_r($coordinates_arr_row);
																$count_row_data = count($field_data_arr);
																for ($index1 = 0; $index1 < count($field_data_arr); $index1++) {
																	$col_span_val = $count_row_data == 1 ? $total_head_fields : 1;
																	$coordinates_arr = explode('|', $coordinates_arr_row[$index1]);
																?>
																	<td colspan="<?php echo $col_span_val; ?>">
																		<textarea style="resize: both; padding:0px" name="table_mapping_field<?php echo $table_no . "" . $count1; ?>[]" id="row_txt<?php echo $index1 + 1; ?>" class="form-control form-control-sm" onclick="updateCursorPosition(this, <?php echo ($table_no + 2) . ',' . $count1; ?>)" onfocus="modifyPdf(<?php echo $coordinates_arr[0] . ',' . $coordinates_arr[1] . ',' . $coordinates_arr[2] . ',' . $coordinates_arr[3]; ?>)"><?php echo trim($field_data_arr[$index1]); ?></textarea>
																		<input type="hidden" class="table_mapping_field_cord" name="table_mapping_field_cord<?php echo $table_no . "" . $count1; ?>[]" value="<?php echo $coordinates_arr[0] . '|' . $coordinates_arr[1] . '|' . $coordinates_arr[2] . '|' . $coordinates_arr[3]; ?>">
																	</td>
																<?php } ?>
															</tr>
														<?
															$count1++;
														} ?>
														<tr>
															<td colspan="<?php echo $colspan_count; ?>" align="right">
																<button type="button" class="btn btn-primary btn-sm add_tbl_row">Add Row</button>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										<?php } ?>
									</div>
									<div class="text-center d-flex justify-content-center">

										<input type="hidden" id="" name="template_id_hidden" value="<?= $template_id; ?>">
										<input type="hidden" id="form_action" name="form_action" value="update">
										<div id="save-ocr-data-loader" class="d-none spinner spinner-border text-primary" role="status">
											<span class="sr-only">Loading...</span>
										</div>
										<button id="save-ocr-data" class="btn btn-sm btn-primary ml-2" type="submit">Save Updated Data</button>
										<!--<button class="btn btn-sm btn-primary">RESET</button> -->
									</div>
								</form>
							</div>
						</div>
						<div id="rightpanel_view">
							<div class="col-md-12 mt-4">
								<p class="mb-1"><span id="pdf_file_name_pdf_lib"></span><span class="">&nbsp;&nbsp;<a id="pdf_file_href_pdf_lib" href="" class="text-dark fa fa-share-square-o fa-1x"></a></span></p>
								<div class="embed-responsive embed-responsive-21by9" style="height:800px">
									<iframe id="pdf_frame" src="<?php echo $ocr_file; ?>"></iframe>
									<!--<iframe class="embed-responsive-item" src="https://loops.usedcardboardboxes.com/water_email_inbox_inv_files/2024_01/173/2024-01---Brand-Aromatics---WM--15-13198-32007--3335354-0515-8.pdf"></iframe>-->
								</div>
								<script>
									load_pdf_first_time();
									async function load_pdf_first_time() {
										const url = `<?= $ocr_file; ?>`;
										const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer())
										var bytes = new Uint8Array(existingPdfBytes);
										const pdfDoc = await PDFDocument.load(existingPdfBytes)
										pdfBytes = await pdfDoc.save()
										const blob = new Blob([pdfBytes], {
											type: 'application/pdf'
										});
										const blobUrl = URL.createObjectURL(blob);
										document.getElementById('pdf_frame').src = blobUrl;
									}
								</script>
							</div>
						</div>
					</div>
				</div>
			<?
		} else { ?>
				<div class="container-fluid p-0">
					<div class="">
						<div id="account_no_not_found_container_for_html" class="d-none">
							<div class="row form-group">
								<div class="offset-md-3"></div>
								<di class="col-md-9">
									<div class="row">
										<div class="col-sm-6 generic_field_dropdown_div">
											<select class='form-control form-control-sm generic_field_dropdown generic_field_dropdown_add' name="acc_no_not_found_mapped_field[]">
												<option></option>
											</select>
										</div>
										<div class="col-sm-6 d-flex">
											<input type="text" name="acc_no_not_found_field_value[]" class="form-control form-control-sm generic_field_input">
											<button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
										</div>
									</div>
							</div>
						</div>
						<div id="contentsplitter" style="height:100vh">
							<div id="leftpanel_view">
								<div class="col-md-12 mt-4">
									<? //if(!isset($_FILES['ocr_file'])) { 
									?>
									<form id="choose-ocr-form" method="POST" action="water_invoice_ocr_mapping_form_tool.php" encType="multipart/form-data" onsubmit="return onsubmitform();">
										<div class="form-group row">
											<label class="col-sm-3 col-form-label">Sample Invoice</label>
											<div class="col-sm-4">
												<input type="file" id="ocr_file" name="ocr_file" class="form-control-file form-control-sm">
												<input type="hidden" name="action" value="add" />
											</div>

											<div id="page-load" class="form-group row d-none align-items-center">
												<span>Processing OCR...</span>
												<div class="spinner-border" role="status">
												</div>
											</div>

											<!-- <div class="col-sm-3">
						<button type="submit" id="choose-ocr" class="btn btn-outline-primary btn-sm">Process OCR</button>
					</div> -->
										</div>

									</form>

									<? // } 

									if (isset($_FILES['ocr_file']) && $_FILES['ocr_file'] != "") {
										if (!empty($_FILES['ocr_file']['error'])) {
											echo "<h2 class='text text-danger'>Try Again</h2>";
										} else {
											$tmpName = $_FILES['ocr_file']['tmp_name'];
											$ocr_file_orgnm = "";
											if (!empty($tmpName) && is_uploaded_file($tmpName)) {
												$filename = str_replace(' ', "_", $_FILES['ocr_file']['name']);
												$filename = str_replace('(', "", $filename);
												$filename = str_replace(')', "", $filename);
												$filename = str_replace('&', "_", $filename);
												$ocr_file = str_replace('#', "", $filename);

												$target_file = "AZFormR/formrecognizer/ai-form-recognizer/upload/" . $ocr_file;
												//echo $target_file;
												if (move_uploaded_file($tmpName, $target_file)) {
													$ocr_file_orgnm = htmlspecialchars(basename($_FILES["ocr_file"]["name"]));
													echo "The file " . htmlspecialchars(basename($_FILES["ocr_file"]["name"])) . " has been uploaded.";

													$inv_file_for_ocr = "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/upload/" . $ocr_file;

													//$mainstr = shell_exec("/usr/bin/node analyzeDocumentByModelIducbdata.js '". escapeshellarg($inv_file_for_ocr) ."'");

													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL, "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/extract_invoice_data_ocr_new.php");
													curl_setopt($ch, CURLOPT_POST, 1);
													curl_setopt($ch, CURLOPT_POSTFIELDS, "inv_filename=" . $inv_file_for_ocr);
													curl_setopt($ch, CURLOPT_HEADER, 0);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

													$mainstr = curl_exec($ch);
													curl_close($ch);

													//var_dump($mainstr);

													$ocr_file = $inv_file_for_ocr;

													echo "<p id='ready_setup_pdf' class='d-none text-primary'>The template for <b>`$ocr_file` </b> is ready to be setup.</p>";
												} else {
													echo "<p style='color:red;'>Sorry, there was an error uploading your file.</p>";
													echo "<p id='ready_setup_pdf' class='d-none text-primary'>The template for <b>`$ocr_file` </b> is ready to be setup.</p>";
												}
											}
										}
									}

									// $ocr_file = "https://loops.usedcardboardboxes.com/AZFormR/formrecognizer/ai-form-recognizer/upload/Republic_service_inv_sample.pdf";
									$ocr_file = "Corridor_Recycling_108086.pdf";
									$mainstr = "CustomerAddress^[object Object]^0.878|
0.8691^2.5924x
3.0705^2.5924x
3.0705^2.9123x
0.8691^2.9123
|Keypair|<br>
CustomerAddressRecipient^kdc-one #1
UCB Zero Waste, LLC.^0.748|
0.8643^2.1819x
2.5747^2.1895x
2.5731^2.5491x
0.8627^2.5414
|Keypair|<br>
CustomerName^kdc-one #1
UCB Zero Waste, LLC.^0.748|
0.8643^2.1819x
2.5747^2.1895x
2.5731^2.5491x
0.8627^2.5414
|Keypair|<br>
DueDate^Tue Nov 14 2023 18:00:00 GMT-0600 (Central Standard Time)^0.938|
5.8831^2.8121x
6.4037^2.8121x
6.4037^2.9601x
5.8879^2.9553
|Keypair|<br>
InvoiceDateVal^11/2/2023^0.789|
5.8067^10.1979x
6.2986^10.1931x
6.2986^10.3125x
5.8115^10.3125
|Keypair|<br>
InvoiceId^108086^0.68|
6.3798^0.974x
6.9289^0.9644x
6.9242^1.1363x
6.3846^1.122
|Keypair|<br>
InvoiceTotal^$4,213.64^0.833|
7.3826^9.5295x
7.9604^9.5295x
7.9604^9.6727x
7.3874^9.6727
|Keypair|<br>
VendorAddress^[object Object]^0.879|
0.4202^1.0933x
2.0343^1.0933x
2.0343^1.3845x
0.4202^1.3845
|Keypair|<br>
VendorName^CORRIDOR
RECYCLING, INC^0.716|
0.4215^0.4345x
3.832^0.4462x
3.8298^1.1029x
0.4193^1.0912
|Keypair|<br>
Phone:^(310) 835-9109^0.874|
2.7507^1.0993x
3.884^1.1124x
3.8822^1.264x
2.7489^1.2509
|Keypair|<br>
FAX:^(310) 835-0366^0.874|
2.6264^1.2516x
3.8637^1.2556x
3.8632^1.4036x
2.6259^1.3996
|Keypair|<br>
Customer:^kdc-one #1
UCB Zero Waste, LLC.
4032 Wilshire Boulevard Suite #402
Los Angeles, CA 90010^0.704|
0.8548^2.1723x
3.0633^2.1815x
3.0603^2.9134x
0.8518^2.9042
|Keypair|<br>
Purchase Ticket #^108086^0.858|
6.3989^0.9883x
6.9194^0.9787x
6.9194^1.1506x
6.3989^1.1411
|Keypair|<br>
Purchase Date^10/31/23^0.875|
6.375^1.184x
6.9958^1.1793x
6.9958^1.3559x
6.375^1.3511
|Keypair|<br>
Currency^US Dollar^0.874|
6.353^1.4144x
7.1075^1.4227x
7.1056^1.5981x
6.3511^1.5898
|Keypair|<br>
Account Rep^Steve Noh^0.859|
4.7619^2.1246x
5.6405^2.1289x
5.6396^2.3104x
4.761^2.306
|Keypair|<br>
Payment Due^11/15/23^0.878|
5.8831^2.8121x
6.4037^2.8121x
6.4037^2.9601x
5.8879^2.9553
|Keypair|<br>
WT Ticket
Ticket
Ticket
# / Deliv^387399
387690
GALI^0.172|
2.1489^4.0868x
2.738^4.0917x
2.7113^7.3095x
2.1222^7.3046
|Keypair|<br>
Rec:^10/20/23^0.906|
0.6256^7.3906x
1.0506^7.3906x
1.0553^7.5148x
0.6303^7.5148
|Keypair|<br>
WT Ticket #S^388005^0.605|
2.1346^7.4145x
2.507^7.4097x
2.5118^7.5339x
2.1393^7.5291
|Keypair|<br>
Release # / Deliv^arline^0.832|
2.4306^7.8585x
2.7362^7.8585x
2.7362^7.9826x
2.4306^7.9826
|Keypair|<br>
Rec:^10/23/23^0.906|
0.6351^8.0734x
1.0601^8.0686x
1.0601^8.1975x
0.6351^8.1975
|Keypair|<br>
WT Ticket #S^388099^0.857|
2.1441^8.0829x
2.5166^8.0924x
2.5166^8.2118x
2.1441^8.2023
|Keypair|<br>
Release # / Deliv^GALI^0.833|
2.4258^8.5174x
2.7171^8.5174x
2.7124^8.651x
2.4258^8.6463
|Keypair|<br>
Weight Adjusted :^2,400.0 Pallets^0.843|
4.0733^8.4076x
4.8039^8.4076x
4.8039^8.5221x
4.0733^8.5221
|Keypair|<br>
Rec:^10/25/23^0.905|
0.6256^8.737x
1.0553^8.7322x
1.0553^8.8611x
0.6303^8.8563
|Keypair|<br>
WT Ticket #S^388186^0.859|
2.1298^8.7513x
2.507^8.7561x
2.507^8.8707x
2.1346^8.8707
|Keypair|<br>
Weight Adjusted :^2,600.0 Pallets^0.845|
4.0685^9.0712x
4.8087^9.0712x
4.8087^9.1953x
4.0685^9.1953
|Keypair|<br>
Release # / Deliv^GALI^0.602|
2.4258^9.181x
2.7171^9.181x
2.7124^9.3147x
2.4258^9.3099
|Keypair|<br>
Prepared By^Gil Dodson^0.857|
1.9292^10.1665x
2.485^10.1755x
2.483^10.3x
1.9272^10.291
|Keypair|<br>
Phone:^(310) 835-9109^0.908|
2.7553^1.1084x
3.868^1.1124x
3.8675^1.2604x
2.7548^1.2564
|Keypair|<br>
FAX:^(310) 835-0366^0.908|
2.6312^1.2509x
3.868^1.2509x
3.868^1.4036x
2.6312^1.4036
|Keypair|<br>
Purchase Ticket #^108086^0.897|
6.3798^0.974x
6.9289^0.9644x
6.9242^1.1363x
6.3846^1.122
|Keypair|<br>
Purchase Date^10/31/23^0.909|
6.3607^1.1793x
7.0006^1.1697x
7.0006^1.332x
6.3654^1.332
|Keypair|<br>
Currency^US Dollar^0.908|
6.3464^1.4x
7.1027^1.4084x
7.1008^1.5815x
6.3445^1.573
|Keypair|<br>
Customer:^kdc-one #1
UCB Zero Waste, LLC.
4032 Wilshire Boulevard Suite #402
Los Angeles, CA 90010^0.739|
0.8643^2.1819x
3.0705^2.1819x
3.0705^2.9123x
0.8643^2.9123
|Keypair|<br>
Account Rep^Steve Noh^0.898|
4.7755^2.1064x
5.6539^2.1198x
5.6511^2.305x
4.7727^2.2916
|Keypair|<br>
Payment Due^11/15/23^0.909|
5.8784^2.7882x
6.4037^2.793x
6.4084^2.9553x
5.8831^2.9553
|Keypair|<br>
Gross^undefined^0.256|
|Keypair|<br>
Tare^undefined^0.263|
|Keypair|<br>
RECEIVED BY:^undefined^0.74|
|Keypair|<br>
Prepared By^Gil Dodson^0.897|
1.958^10.1742x
2.5186^10.1836x
2.5166^10.3052x
1.9559^10.2958
|Keypair|<br>
|Delimiter|
<table border=1>
<tr>
<td>Item Name^undefined|
0.2045^3.1453x
1.0827^3.1533x
1.0827^3.3608x
0.2045^3.3528
</td>
<td>Order #^undefined|
1.0827^3.1533x
2.9509^3.1612x
2.9509^3.3767x
1.0827^3.3608
</td>
<td>Gross^undefined|
2.9509^3.1612x
3.7892^3.1612x
3.7892^3.3767x
2.9509^3.3767
</td>
<td>Tare^undefined|
3.7892^3.1612x
4.8909^3.1692x
4.8909^3.3927x
3.7892^3.3767
</td>
<td>Net^undefined|
4.8909^3.1692x
5.817^3.1692x
5.817^3.3927x
4.8909^3.3927
</td>
<td>Price^undefined|
5.817^3.1692x
7.1663^3.1772x
7.1663^3.4007x
5.817^3.3927
</td>
<td>Total^undefined|
7.1663^3.1772x
8.0125^3.1772x
8.0125^3.4007x
7.1663^3.4007
</td>
<tr>
<td>Rec:^undefined|
0.2045^3.3528x
0.5718^3.3608x
0.5718^3.5523x
0.2045^3.5443
</td>
<td>10/4/23^undefined|
0.5718^3.3608x
1.0827^3.3608x
1.0827^3.5523x
0.5718^3.5523
</td>
<td>WT Ticket #S^undefined|
1.0827^3.3608x
2.1685^3.3767x
2.1685^3.5603x
1.0827^3.5523
</td>
<td>387394^undefined|
2.1685^3.3767x
2.9509^3.3767x
2.9509^3.5603x
2.1685^3.5603
</td>
<td>^undefined|
2.9509^3.3767x
3.7892^3.3767x
3.7892^3.5683x
2.9509^3.5603
</td>
<td>^undefined|
3.7892^3.3767x
4.8909^3.3927x
4.8909^3.5763x
3.7892^3.5683
</td>
<td>^undefined|
4.8909^3.3927x
5.817^3.3927x
5.817^3.5763x
4.8909^3.5763
</td>
<td>^undefined|
5.817^3.3927x
7.1663^3.4007x
7.1663^3.5842x
5.817^3.5763
</td>
<td>^undefined|
7.1663^3.4007x
8.0125^3.4007x
8.0125^3.5922x
7.1663^3.5842
</td>
<tr>
<td>Occ^undefined|
0.2045^3.5443x
0.5718^3.5523x
0.5718^3.704x
0.2045^3.696
</td>
<td>^undefined|
0.5718^3.5523x
1.0827^3.5523x
1.0827^3.704x
0.5718^3.704
</td>
<td>152042^undefined|
1.0827^3.5523x
2.1685^3.5603x
2.1685^3.7119x
1.0827^3.704
</td>
<td>^undefined|
2.1685^3.5603x
2.9509^3.5603x
2.9509^3.7119x
2.1685^3.7119
</td>
<td>25,100.0^undefined|
2.9509^3.5603x
3.7892^3.5683x
3.7892^3.7199x
2.9509^3.7119
</td>
<td>15,140.0^undefined|
3.7892^3.5683x
4.8909^3.5763x
4.8909^3.7199x
3.7892^3.7199
</td>
<td>7,760.0 LB^undefined|
4.8909^3.5763x
5.817^3.5763x
5.817^3.7279x
4.8909^3.7199
</td>
<td>$80.000000 Ton^undefined|
5.817^3.5763x
7.1663^3.5842x
7.1663^3.7279x
5.817^3.7279
</td>
<td>$310.40^undefined|
7.1663^3.5842x
8.0125^3.5922x
8.0125^3.7359x
7.1663^3.7279
</td>
<tr>
<td>^undefined|
0.2045^3.696x
0.5718^3.704x
0.5718^3.8316x
0.2045^3.8237
</td>
<td>^undefined|
0.5718^3.704x
1.0827^3.704x
1.0827^3.8316x
0.5718^3.8316
</td>
<td>^undefined|
1.0827^3.704x
2.1685^3.7119x
2.1685^3.8396x
1.0827^3.8316
</td>
<td>Weight^undefined|
2.1685^3.7119x
2.9509^3.7119x
2.9509^3.8396x
2.1685^3.8396
</td>
<td>Adjusted :^undefined|
2.9509^3.7119x
3.7892^3.7199x
3.7892^3.8476x
2.9509^3.8396
</td>
<td>2,200.0 Pallets^undefined|
3.7892^3.7199x
4.8909^3.7199x
4.8909^3.8476x
3.7892^3.8476
</td>
<td>^undefined|
4.8909^3.7199x
5.817^3.7279x
5.817^3.8556x
4.8909^3.8476
</td>
<td>^undefined|
5.817^3.7279x
7.1663^3.7279x
7.1663^3.8636x
5.817^3.8556
</td>
<td>^undefined|
7.1663^3.7279x
8.0125^3.7359x
8.0125^3.8636x
7.1663^3.8636
</td>
<tr>
<td>^undefined|
0.2045^3.8237x
0.5718^3.8316x
0.5718^4.0152x
0.2045^4.0072
</td>
<td>^undefined|
0.5718^3.8316x
1.0827^3.8316x
1.0827^4.0152x
0.5718^4.0152
</td>
<td>Release # / Deliv^undefined|
1.0827^3.8316x
2.1685^3.8396x
2.1605^4.0232x
1.0827^4.0152
</td>
<td>ANA^undefined|
2.1685^3.8396x
2.9509^3.8396x
2.9509^4.0232x
2.1605^4.0232
</td>
<td>^undefined|
2.9509^3.8396x
3.7892^3.8476x
3.7892^4.0312x
2.9509^4.0232
</td>
<td>^undefined|
3.7892^3.8476x
4.8909^3.8476x
4.8909^4.0312x
3.7892^4.0312
</td>
<td>^undefined|
4.8909^3.8476x
5.817^3.8556x
5.817^4.0312x
4.8909^4.0312
</td>
<td>^undefined|
5.817^3.8556x
7.1663^3.8636x
7.1663^4.0392x
5.817^4.0312
</td>
<td>^undefined|
7.1663^3.8636x
8.0125^3.8636x
8.0125^4.0471x
7.1663^4.0392
</td>
<tr>
<td>Rec:^undefined|
0.2045^4.0072x
0.5718^4.0152x
0.5718^4.2227x
0.2045^4.2147
</td>
<td>10/4/23^undefined|
0.5718^4.0152x
1.0827^4.0152x
1.0827^4.2227x
0.5718^4.2227
</td>
<td>WT Ticket #S^undefined|
1.0827^4.0152x
2.1605^4.0232x
2.1605^4.2307x
1.0827^4.2227
</td>
<td>387399^undefined|
2.1605^4.0232x
2.9509^4.0232x
2.9509^4.2307x
2.1605^4.2307
</td>
<td>^undefined|
2.9509^4.0232x
3.7892^4.0312x
3.7892^4.2387x
2.9509^4.2307
</td>
<td>^undefined|
3.7892^4.0312x
4.8909^4.0312x
4.8909^4.2467x
3.7892^4.2387
</td>
<td>^undefined|
4.8909^4.0312x
5.817^4.0312x
5.817^4.2467x
4.8909^4.2467
</td>
<td>^undefined|
5.817^4.0312x
7.1663^4.0392x
7.1663^4.2546x
5.817^4.2467
</td>
<td>^undefined|
7.1663^4.0392x
8.0125^4.0471x
8.0125^4.2626x
7.1663^4.2546
</td>
<tr>
<td>#1^undefined|
0.2045^4.2147x
0.5718^4.2227x
0.5718^4.3744x
0.2045^4.3744
</td>
<td>Unprepared^undefined|
0.5718^4.2227x
1.0827^4.2227x
1.0827^4.3744x
0.5718^4.3744
</td>
<td>152042^undefined|
1.0827^4.2227x
2.9509^4.2307x
2.9509^4.3823x
1.0827^4.3744
</td>
<td>35,240.0^undefined|
2.9509^4.2307x
3.7892^4.2387x
3.7892^4.3823x
2.9509^4.3823
</td>
<td>27,540.0^undefined|
3.7892^4.2387x
4.8909^4.2467x
4.8909^4.3903x
3.7892^4.3823
</td>
<td>7,520.0 LB^undefined|
4.8909^4.2467x
5.817^4.2467x
5.817^4.3903x
4.8909^4.3903
</td>
<td>$170.000000 GT^undefined|
5.817^4.2467x
7.1663^4.2546x
7.1663^4.3983x
5.817^4.3903
</td>
<td>$570.71^undefined|
7.1663^4.2546x
8.0125^4.2626x
8.0125^4.4063x
7.1663^4.3983
</td>
<tr>
<td>^undefined|
0.2045^4.3744x
0.5718^4.3744x
0.5718^4.4941x
0.2045^4.4861
</td>
<td>^undefined|
0.5718^4.3744x
1.0827^4.3744x
1.0747^4.4941x
0.5718^4.4941
</td>
<td>Release # / Deliv^undefined|
1.0827^4.3744x
2.1605^4.3744x
2.1605^4.6936x
1.0747^4.6856
</td>
<td>Weight
FLOR^undefined|
2.1605^4.3744x
2.9509^4.3823x
2.9509^4.6936x
2.1605^4.6936
</td>
<td>Adjusted :^undefined|
2.9509^4.3823x
3.7892^4.3823x
3.7892^4.51x
2.9509^4.5021
</td>
<td>180.0 Trash^undefined|
3.7892^4.3823x
4.8909^4.3903x
4.8909^4.51x
3.7892^4.51
</td>
<td>^undefined|
4.8909^4.3903x
5.817^4.3903x
5.817^4.518x
4.8909^4.51
</td>
<td>^undefined|
5.817^4.3903x
7.1663^4.3983x
7.1663^4.526x
5.817^4.518
</td>
<td>^undefined|
7.1663^4.3983x
8.0125^4.4063x
8.0125^4.7175x
7.1663^4.7175
</td>
<tr>
<td>^undefined|
0.2045^4.4861x
0.5718^4.4941x
0.5718^4.6776x
0.1965^4.6697
</td>
<td>^undefined|
0.5718^4.4941x
1.0747^4.4941x
1.0747^4.6856x
0.5718^4.6776
</td>
<td>^undefined|
2.9509^4.5021x
3.7892^4.51x
3.7892^4.6936x
2.9509^4.6936
</td>
<td>^undefined|
3.7892^4.51x
4.8909^4.51x
4.8909^4.7016x
3.7892^4.6936
</td>
<td>^undefined|
4.8909^4.51x
5.817^4.518x
5.817^4.7016x
4.8909^4.7016
</td>
<td>^undefined|
5.817^4.518x
7.1663^4.526x
7.1663^4.7175x
5.817^4.7016
</td>
<tr>
<td>Rec:^undefined|
0.1965^4.6697x
0.5718^4.6776x
0.5718^4.8852x
0.1965^4.8772
</td>
<td>10/6/23^undefined|
0.5718^4.6776x
1.0747^4.6856x
1.0747^4.8852x
0.5718^4.8852
</td>
<td>WT Ticket #S^undefined|
1.0747^4.6856x
2.1605^4.6936x
2.1605^4.9011x
1.0747^4.8852
</td>
<td>387500^undefined|
2.1605^4.6936x
2.9509^4.6936x
2.9509^4.9011x
2.1605^4.9011
</td>
<td>^undefined|
2.9509^4.6936x
3.7892^4.6936x
3.7892^4.9091x
2.9509^4.9011
</td>
<td>^undefined|
3.7892^4.6936x
4.8909^4.7016x
4.8909^4.9171x
3.7892^4.9091
</td>
<td>^undefined|
4.8909^4.7016x
5.817^4.7016x
5.817^4.9171x
4.8909^4.9171
</td>
<td>^undefined|
5.817^4.7016x
7.1663^4.7175x
7.1663^4.9251x
5.817^4.9171
</td>
<td>^undefined|
7.1663^4.7175x
8.0125^4.7175x
8.0125^4.933x
7.1663^4.9251
</td>
<tr>
<td>occ^undefined|
0.1965^4.8772x
0.5718^4.8852x
0.5718^5.0368x
0.1965^5.0368
</td>
<td>^undefined|
0.5718^4.8852x
1.0747^4.8852x
1.0747^5.0368x
0.5718^5.0368
</td>
<td>152042^undefined|
1.0747^4.8852x
2.9509^4.9011x
2.9509^5.0448x
1.0747^5.0368
</td>
<td>41,620.0^undefined|
2.9509^4.9011x
3.7892^4.9091x
3.7892^5.0528x
2.9509^5.0448
</td>
<td>31,120.0^undefined|
3.7892^4.9091x
4.8909^4.9171x
4.8909^5.0528x
3.7892^5.0528
</td>
<td>7,800.0 LB^undefined|
4.8909^4.9171x
5.817^4.9171x
5.817^5.0528x
4.8909^5.0528
</td>
<td>$80.000000 Ton^undefined|
5.817^4.9171x
7.1663^4.9251x
7.1663^5.0607x
5.817^5.0528
</td>
<td>$312.00^undefined|
7.1663^4.9251x
8.0125^4.933x
8.0125^5.0687x
7.1663^5.0607
</td>
<tr>
<td>^undefined|
0.1965^5.0368x
0.5718^5.0368x
0.5718^5.1645x
0.1965^5.1565
</td>
<td>^undefined|
0.5718^5.0368x
1.0747^5.0368x
1.0747^5.1645x
0.5718^5.1645
</td>
<td>^undefined|
1.0747^5.0368x
2.1605^5.0368x
2.1605^5.1725x
1.0747^5.1645
</td>
<td>Weight^undefined|
2.1605^5.0368x
2.9509^5.0448x
2.9509^5.1805x
2.1605^5.1725
</td>
<td>Adjusted :^undefined|
2.9509^5.0448x
3.7892^5.0528x
3.7892^5.1805x
2.9509^5.1805
</td>
<td>2,700.0 Pallets^undefined|
3.7892^5.0528x
4.8909^5.0528x
4.8909^5.1884x
3.7892^5.1805
</td>
<td>^undefined|
4.8909^5.0528x
5.817^5.0528x
5.817^5.1964x
4.8909^5.1884
</td>
<td>^undefined|
5.817^5.0528x
7.1663^5.0607x
7.1663^5.2044x
5.817^5.1964
</td>
<td>^undefined|
7.1663^5.0607x
8.0125^5.0687x
8.0125^5.2124x
7.1663^5.2044
</td>
<tr>
<td>^undefined|
0.1965^5.1565x
0.5718^5.1645x
0.5718^5.3481x
0.1965^5.3401
</td>
<td>^undefined|
0.5718^5.1645x
1.0747^5.1645x
1.0747^5.3481x
0.5718^5.3481
</td>
<td>Release # / Deliv GALI^undefined|
1.0747^5.1645x
2.9509^5.1805x
2.9509^5.356x
1.0747^5.3481
</td>
<td>^undefined|
2.9509^5.1805x
3.7892^5.1805x
3.7892^5.364x
2.9509^5.356
</td>
<td>^undefined|
3.7892^5.1805x
4.8909^5.1884x
4.8909^5.364x
3.7892^5.364
</td>
<td>^undefined|
4.8909^5.1884x
5.817^5.1964x
5.817^5.372x
4.8909^5.364
</td>
<td>^undefined|
5.817^5.1964x
7.1663^5.2044x
7.1663^5.38x
5.817^5.372
</td>
<td>^undefined|
7.1663^5.2044x
8.0125^5.2124x
8.0125^5.388x
7.1663^5.38
</td>
<tr>
<td>Rec:^undefined|
0.1965^5.3401x
0.5718^5.3481x
0.5638^5.5476x
0.1965^5.5476
</td>
<td>10/12/23^undefined|
0.5718^5.3481x
1.0747^5.3481x
1.0747^5.5556x
0.5638^5.5476
</td>
<td>WT Ticket #S^undefined|
1.0747^5.3481x
2.1605^5.356x
2.1605^5.5635x
1.0747^5.5556
</td>
<td>387690^undefined|
2.1605^5.356x
2.9509^5.356x
2.9509^5.5715x
2.1605^5.5635
</td>
<td>^undefined|
2.9509^5.356x
3.7892^5.364x
3.7812^5.5795x
2.9509^5.5715
</td>
<td>^undefined|
3.7892^5.364x
4.8909^5.364x
4.8829^5.5795x
3.7812^5.5795
</td>
<td>^undefined|
4.8909^5.364x
5.817^5.372x
5.817^5.5875x
4.8829^5.5795
</td>
<td>^undefined|
5.817^5.372x
7.1663^5.38x
7.1663^5.5875x
5.817^5.5875
</td>
<td>^undefined|
7.1663^5.38x
8.0125^5.388x
8.0125^5.5955x
7.1663^5.5875
</td>
<tr>
<td>OCC^undefined|
0.1965^5.5476x
0.5638^5.5476x
0.5638^5.7072x
0.1965^5.7072
</td>
<td>^undefined|
0.5638^5.5476x
1.0747^5.5556x
1.0747^5.7072x
0.5638^5.7072
</td>
<td>152042^undefined|
1.0747^5.5556x
2.9509^5.5715x
2.9509^5.7152x
1.0747^5.7072
</td>
<td>25,100.0^undefined|
2.9509^5.5715x
3.7812^5.5795x
3.7812^5.7232x
2.9509^5.7152
</td>
<td>14,220.0^undefined|
3.7812^5.5795x
4.8829^5.5795x
4.8829^5.7232x
3.7812^5.7232
</td>
<td>8,180.0 LB^undefined|
4.8829^5.5795x
5.817^5.5875x
5.817^5.7232x
4.8829^5.7232
</td>
<td>$80.000000 Ton^undefined|
5.817^5.5875x
7.1663^5.5875x
7.1663^5.7311x
5.817^5.7232
</td>
<td>$327.20^undefined|
7.1663^5.5875x
8.0125^5.5955x
8.0125^5.7391x
7.1663^5.7311
</td>
<tr>
<td>^undefined|
0.1965^5.7072x
0.5638^5.7072x
0.5638^5.8349x
0.1965^5.8349
</td>
<td>^undefined|
0.5638^5.7072x
1.0747^5.7072x
1.0747^5.8349x
0.5638^5.8349
</td>
<td>Release # / Deliv^undefined|
1.0747^5.7072x
2.1605^5.7072x
2.1605^6.0264x
1.0747^6.0185
</td>
<td>Weight
ANA^undefined|
2.1605^5.7072x
2.9509^5.7152x
2.9509^6.0344x
2.1605^6.0264
</td>
<td>Adjusted :^undefined|
2.9509^5.7152x
3.7812^5.7232x
3.7812^5.8509x
2.9509^5.8429
</td>
<td>2,700.0 Pallets^undefined|
3.7812^5.7232x
4.8829^5.7232x
4.8829^5.8509x
3.7812^5.8509
</td>
<td>^undefined|
4.8829^5.7232x
5.817^5.7232x
5.817^5.8509x
4.8829^5.8509
</td>
<td>^undefined|
5.817^5.7232x
7.1663^5.7311x
7.1663^5.8588x
5.817^5.8509
</td>
<td>^undefined|
7.1663^5.7311x
8.0125^5.7391x
8.0125^6.0584x
7.1663^6.0504
</td>
<tr>
<td>^undefined|
0.1965^5.8349x
0.5638^5.8349x
0.5638^6.0185x
0.1965^6.0105
</td>
<td>^undefined|
0.5638^5.8349x
1.0747^5.8349x
1.0747^6.0185x
0.5638^6.0185
</td>
<td>^undefined|
2.9509^5.8429x
3.7812^5.8509x
3.7812^6.0344x
2.9509^6.0344
</td>
<td>^undefined|
3.7812^5.8509x
4.8829^5.8509x
4.8829^6.0424x
3.7812^6.0344
</td>
<td>^undefined|
4.8829^5.8509x
5.817^5.8509x
5.817^6.0424x
4.8829^6.0424
</td>
<td>^undefined|
5.817^5.8509x
7.1663^5.8588x
7.1663^6.0504x
5.817^6.0424
</td>
<tr>
<td>Rec:^undefined|
0.1965^6.0105x
0.5638^6.0185x
0.5638^6.218x
0.1885^6.218
</td>
<td>10/14/23^undefined|
0.5638^6.0185x
1.0747^6.0185x
1.0747^6.218x
0.5638^6.218
</td>
<td>WT Ticket #S^undefined|
1.0747^6.0185x
2.1605^6.0264x
2.1605^6.218x
1.0747^6.218
</td>
<td>387789^undefined|
2.1605^6.0264x
2.9509^6.0344x
2.9509^6.226x
2.1605^6.218
</td>
<td>^undefined|
2.9509^6.0344x
3.7812^6.0344x
3.7812^6.234x
2.9509^6.226
</td>
<td>^undefined|
3.7812^6.0344x
4.8829^6.0424x
4.8829^6.2419x
3.7812^6.234
</td>
<td>^undefined|
4.8829^6.0424x
5.817^6.0424x
5.817^6.2499x
4.8829^6.2419
</td>
<td>^undefined|
5.817^6.0424x
7.1663^6.0504x
7.1663^6.2579x
5.817^6.2499
</td>
<td>^undefined|
7.1663^6.0504x
8.0125^6.0584x
8.0125^6.2659x
7.1663^6.2579
</td>
<tr>
<td>Occ^undefined|
0.1885^6.218x
0.5638^6.218x
0.5638^6.3696x
0.1885^6.3617
</td>
<td>^undefined|
0.5638^6.218x
1.0747^6.218x
1.0747^6.3696x
0.5638^6.3696
</td>
<td>152042^undefined|
1.0747^6.218x
2.9509^6.226x
2.9509^6.3776x
1.0747^6.3696
</td>
<td>26,600.0^undefined|
2.9509^6.226x
3.7812^6.234x
3.7812^6.3856x
2.9509^6.3776
</td>
<td>14,140.0^undefined|
3.7812^6.234x
4.8829^6.2419x
4.8829^6.3856x
3.7812^6.3856
</td>
<td>9,760.0 LB^undefined|
4.8829^6.2419x
5.817^6.2499x
5.817^6.3936x
4.8829^6.3856
</td>
<td>$80.000000 Ton^undefined|
5.817^6.2499x
7.1663^6.2579x
7.1663^6.4016x
5.817^6.3936
</td>
<td>$390.40^undefined|
7.1663^6.2579x
8.0125^6.2659x
8.0125^6.4016x
7.1663^6.4016
</td>
<tr>
<td>^undefined|
0.1885^6.3617x
0.5638^6.3696x
0.5638^6.4973x
0.1885^6.4894
</td>
<td>^undefined|
0.5638^6.3696x
1.0747^6.3696x
1.0747^6.4973x
0.5638^6.4973
</td>
<td>Release # / Deliv^undefined|
1.0747^6.3696x
2.1605^6.3776x
2.1605^6.6889x
1.0747^6.6809
</td>
<td>Weight^undefined|
2.1605^6.3776x
2.9509^6.3776x
2.9509^6.5053x
2.1605^6.5053
</td>
<td>Adjusted :^undefined|
2.9509^6.3776x
3.7812^6.3856x
3.7812^6.5133x
2.9509^6.5053
</td>
<td>2,700.0 Pallets^undefined|
3.7812^6.3856x
4.8829^6.3856x
4.8829^6.5133x
3.7812^6.5133
</td>
<td>^undefined|
4.8829^6.3856x
5.817^6.3936x
5.817^6.5213x
4.8829^6.5133
</td>
<td>^undefined|
5.817^6.3936x
7.1663^6.4016x
7.1663^6.5293x
5.817^6.5213
</td>
<td>^undefined|
7.1663^6.4016x
8.0125^6.4016x
8.0125^6.7208x
7.1663^6.7128
</td>
<tr>
<td>^undefined|
0.1885^6.4894x
0.5638^6.4973x
0.5638^6.6809x
0.1885^6.6729
</td>
<td>^undefined|
0.5638^6.4973x
1.0747^6.4973x
1.0747^6.6809x
0.5638^6.6809
</td>
<td>ANA^undefined|
2.1605^6.5053x
2.9509^6.5053x
2.9509^6.6969x
2.1605^6.6889
</td>
<td>^undefined|
2.9509^6.5053x
3.7812^6.5133x
3.7812^6.6969x
2.9509^6.6969
</td>
<td>^undefined|
3.7812^6.5133x
4.8829^6.5133x
4.8829^6.6969x
3.7812^6.6969
</td>
<td>^undefined|
4.8829^6.5133x
5.817^6.5213x
5.817^6.7048x
4.8829^6.6969
</td>
<td>^undefined|
5.817^6.5213x
7.1663^6.5293x
7.1663^6.7128x
5.817^6.7048
</td>
<tr>
<td>Rec:^undefined|
0.1885^6.6729x
0.5638^6.6809x
0.5638^6.8804x
0.1885^6.8724
</td>
<td>10/19/23^undefined|
0.5638^6.6809x
1.0747^6.6809x
1.0747^6.8804x
0.5638^6.8804
</td>
<td>WT Ticket #S^undefined|
1.0747^6.6809x
2.1605^6.6889x
2.1605^6.8964x
1.0747^6.8804
</td>
<td>387973^undefined|
2.1605^6.6889x
2.9509^6.6969x
2.9509^6.8964x
2.1605^6.8964
</td>
<td>^undefined|
2.9509^6.6969x
3.7812^6.6969x
3.7812^6.9044x
2.9509^6.8964
</td>
<td>^undefined|
3.7812^6.6969x
4.8829^6.6969x
4.8829^6.9124x
3.7812^6.9044
</td>
<td>^undefined|
4.8829^6.6969x
5.817^6.7048x
5.817^6.9124x
4.8829^6.9124
</td>
<td>^undefined|
5.817^6.7048x
7.1663^6.7128x
7.1663^6.9283x
5.817^6.9124
</td>
<td>^undefined|
7.1663^6.7128x
8.0125^6.7208x
8.0125^6.9283x
7.1663^6.9283
</td>
<tr>
<td>OCC^undefined|
0.1885^6.8724x
0.5638^6.8804x
0.5638^7.0321x
0.1885^7.0241
</td>
<td>^undefined|
0.5638^6.8804x
1.0747^6.8804x
1.0668^7.0321x
0.5638^7.0321
</td>
<td>152042^undefined|
1.0747^6.8804x
2.9509^6.8964x
2.9509^7.048x
1.0668^7.0321
</td>
<td>43,240.0^undefined|
2.9509^6.8964x
3.7812^6.9044x
3.7812^7.048x
2.9509^7.048
</td>
<td>31,720.0^undefined|
3.7812^6.9044x
4.8829^6.9124x
4.8829^7.056x
3.7812^7.048
</td>
<td>8,920.0 LB^undefined|
4.8829^6.9124x
5.817^6.9124x
5.817^7.056x
4.8829^7.056
</td>
<td>$80.000000 Ton^undefined|
5.817^6.9124x
7.1663^6.9283x
7.1663^7.072x
5.817^7.056
</td>
<td>$356.80^undefined|
7.1663^6.9283x
8.0125^6.9283x
8.0125^7.072x
7.1663^7.072
</td>
<tr>
<td>^undefined|
0.1885^7.0241x
0.5638^7.0321x
0.5638^7.1598x
0.1885^7.1598
</td>
<td>^undefined|
0.5638^7.0321x
1.0668^7.0321x
1.0668^7.1598x
0.5638^7.1598
</td>
<td>^undefined|
1.0668^7.0321x
2.1605^7.04x
2.1605^7.1598x
1.0668^7.1598
</td>
<td>Weight^undefined|
2.1605^7.04x
2.9509^7.048x
2.9509^7.1677x
2.1605^7.1598
</td>
<td>Adjusted :^undefined|
2.9509^7.048x
3.7812^7.048x
3.7812^7.1757x
2.9509^7.1677
</td>
<td>2,600.0 Pallets^undefined|
3.7812^7.048x
4.8829^7.056x
4.8829^7.1757x
3.7812^7.1757
</td>
<td>^undefined|
4.8829^7.056x
5.817^7.056x
5.817^7.1757x
4.8829^7.1757
</td>
<td>^undefined|
5.817^7.056x
7.1663^7.072x
7.1663^7.1837x
5.817^7.1757
</td>
<td>^undefined|
7.1663^7.072x
8.0125^7.072x
8.0125^7.1917x
7.1663^7.1837
</td>
<tr>
<td>^undefined|
0.1885^7.1598x
0.5638^7.1598x
0.5638^7.3433x
0.1885^7.3353
</td>
<td>^undefined|
0.5638^7.1598x
1.0668^7.1598x
1.0668^7.3433x
0.5638^7.3433
</td>
<td>Release # / Deliv^undefined|
1.0668^7.1598x
2.1605^7.1598x
2.1605^7.3513x
1.0668^7.3433
</td>
<td>GALI^undefined|
2.1605^7.1598x
2.9509^7.1677x
2.9509^7.3513x
2.1605^7.3513
</td>
<td>^undefined|
2.9509^7.1677x
3.7812^7.1757x
3.7812^7.3593x
2.9509^7.3513
</td>
<td>^undefined|
3.7812^7.1757x
4.8829^7.1757x
4.8829^7.3593x
3.7812^7.3593
</td>
<td>^undefined|
4.8829^7.1757x
5.817^7.1757x
5.817^7.3593x
4.8829^7.3593
</td>
<td>^undefined|
5.817^7.1757x
7.1663^7.1837x
7.1663^7.3673x
5.817^7.3593
</td>
<td>^undefined|
7.1663^7.1837x
8.0125^7.1917x
8.0125^7.3753x
7.1663^7.3673
</td>
<tr>
<td>Rec:^undefined|
0.1885^7.3353x
0.5638^7.3433x
0.5638^7.5429x
0.1885^7.5429
</td>
<td>10/20/23^undefined|
0.5638^7.3433x
1.0668^7.3433x
1.0668^7.5429x
0.5638^7.5429
</td>
<td>WT Ticket #S^undefined|
1.0668^7.3433x
2.1605^7.3513x
2.1605^7.5508x
1.0668^7.5429
</td>
<td>388005^undefined|
2.1605^7.3513x
2.9509^7.3513x
2.9509^7.5588x
2.1605^7.5508
</td>
<td>^undefined|
2.9509^7.3513x
3.7812^7.3593x
3.7812^7.5668x
2.9509^7.5588
</td>
<td>^undefined|
3.7812^7.3593x
4.8829^7.3593x
4.8829^7.5668x
3.7812^7.5668
</td>
<td>^undefined|
4.8829^7.3593x
5.817^7.3593x
5.817^7.5748x
4.8829^7.5668
</td>
<td>^undefined|
5.817^7.3593x
7.1663^7.3673x
7.1663^7.5828x
5.817^7.5748
</td>
<td>^undefined|
7.1663^7.3673x
8.0125^7.3753x
8.0125^7.5828x
7.1663^7.5828
</td>
<tr>
<td>#1^undefined|
0.1885^7.5429x
0.5638^7.5429x
0.5638^7.6945x
0.1885^7.6865
</td>
<td>Unprepared^undefined|
0.5638^7.5429x
1.0668^7.5429x
1.0668^7.6945x
0.5638^7.6945
</td>
<td>152042^undefined|
1.0668^7.5429x
2.9509^7.5588x
2.9509^7.7025x
1.0668^7.6945
</td>
<td>45,980.0^undefined|
2.9509^7.5588x
3.7812^7.5668x
3.7812^7.7105x
2.9509^7.7025
</td>
<td>27,800.0^undefined|
3.7812^7.5668x
4.8829^7.5668x
4.8829^7.7184x
3.7812^7.7105
</td>
<td>18,180.0 LB^undefined|
4.8829^7.5668x
5.817^7.5748x
5.817^7.7184x
4.8829^7.7184
</td>
<td>$170.000000 GT^undefined|
5.817^7.5748x
7.1663^7.5828x
7.1663^7.7264x
5.817^7.7184
</td>
<td>$1,379.73^undefined|
7.1663^7.5828x
8.0125^7.5828x
8.0125^7.7344x
7.1663^7.7264
</td>
<tr>
<td>Trash^undefined|
0.1885^7.6865x
0.5638^7.6945x
0.5638^7.8382x
0.1806^7.8302
</td>
<td>^undefined|
0.5638^7.6945x
1.0668^7.6945x
1.0668^7.8382x
0.5638^7.8382
</td>
<td>152042^undefined|
1.0668^7.6945x
2.9509^7.7025x
2.9509^7.8541x
1.0668^7.8382
</td>
<td>47,780.0^undefined|
2.9509^7.7025x
3.7812^7.7105x
3.7812^7.8541x
2.9509^7.8541
</td>
<td>45,980.0^undefined|
3.7812^7.7105x
4.8829^7.7184x
4.8829^7.8621x
3.7812^7.8541
</td>
<td>1,800.0 LB^undefined|
4.8829^7.7184x
5.817^7.7184x
5.817^7.8621x
4.8829^7.8621
</td>
<td>-$120.000000 Ton^undefined|
5.817^7.7184x
7.1663^7.7264x
7.1663^7.8701x
5.817^7.8621
</td>
<td>-$108.00^undefined|
7.1663^7.7264x
8.0125^7.7344x
8.0125^7.8781x
7.1663^7.8701
</td>
<tr>
<td>^undefined|
0.1806^7.8302x
0.5638^7.8382x
0.5638^8.0217x
0.1806^8.0137
</td>
<td>^undefined|
0.5638^7.8382x
1.0668^7.8382x
1.0668^8.0217x
0.5638^8.0217
</td>
<td>Release # / Deliv^undefined|
1.0668^7.8382x
2.1605^7.8461x
2.1605^8.0297x
1.0668^8.0217
</td>
<td>arline^undefined|
2.1605^7.8461x
2.9509^7.8541x
2.9509^8.0297x
2.1605^8.0297
</td>
<td>^undefined|
2.9509^7.8541x
3.7812^7.8541x
3.7812^8.0377x
2.9509^8.0297
</td>
<td>^undefined|
3.7812^7.8541x
4.8829^7.8621x
4.8829^8.0377x
3.7812^8.0377
</td>
<td>^undefined|
4.8829^7.8621x
5.817^7.8621x
5.817^8.0377x
4.8829^8.0377
</td>
<td>^undefined|
5.817^7.8621x
7.1663^7.8701x
7.1663^8.0536x
5.817^8.0377
</td>
<td>^undefined|
7.1663^7.8701x
8.0125^7.8781x
8.0125^8.0536x
7.1663^8.0536
</td>
<tr>
<td>Rec:^undefined|
0.1806^8.0137x
0.5638^8.0217x
0.5638^8.2212x
0.1806^8.2133
</td>
<td>10/23/23^undefined|
0.5638^8.0217x
1.0668^8.0217x
1.0668^8.2212x
0.5638^8.2212
</td>
<td>WT Ticket #S^undefined|
1.0668^8.0217x
2.1605^8.0297x
2.1605^8.2372x
1.0668^8.2212
</td>
<td>388099^undefined|
2.1605^8.0297x
2.9509^8.0297x
2.9429^8.2372x
2.1605^8.2372
</td>
<td>^undefined|
2.9509^8.0297x
3.7812^8.0377x
3.7812^8.2452x
2.9429^8.2372
</td>
<td>^undefined|
3.7812^8.0377x
4.8829^8.0377x
4.8829^8.2452x
3.7812^8.2452
</td>
<td>^undefined|
4.8829^8.0377x
5.817^8.0377x
5.817^8.2532x
4.8829^8.2452
</td>
<td>^undefined|
5.817^8.0377x
7.1663^8.0536x
7.1663^8.2612x
5.817^8.2532
</td>
<td>^undefined|
7.1663^8.0536x
8.0125^8.0536x
8.0125^8.2691x
7.1663^8.2612
</td>
<tr>
<td>Occ^undefined|
0.1806^8.2133x
0.5638^8.2212x
0.5638^8.3809x
0.1806^8.3809
</td>
<td>^undefined|
0.5638^8.2212x
1.0668^8.2212x
1.0668^8.3809x
0.5638^8.3809
</td>
<td>152042^undefined|
1.0668^8.2212x
2.9429^8.2372x
2.9429^8.3889x
1.0668^8.3809
</td>
<td>41,660.0^undefined|
2.9429^8.2372x
3.7812^8.2452x
3.7812^8.3889x
2.9429^8.3889
</td>
<td>30,860.0^undefined|
3.7812^8.2452x
4.8829^8.2452x
4.8829^8.3968x
3.7812^8.3889
</td>
<td>8,400.0 LB^undefined|
4.8829^8.2452x
5.817^8.2532x
5.817^8.3968x
4.8829^8.3968
</td>
<td>$80.000000 Ton^undefined|
5.817^8.2532x
7.1663^8.2612x
7.1663^8.4128x
5.817^8.3968
</td>
<td>$336.00^undefined|
7.1663^8.2612x
8.0125^8.2691x
8.0125^8.4128x
7.1663^8.4128
</td>
<tr>
<td>^undefined|
0.1806^8.3809x
0.5638^8.3809x
0.5638^8.5006x
0.1806^8.5006
</td>
<td>^undefined|
0.5638^8.3809x
1.0668^8.3809x
1.0668^8.6842x
0.5638^8.6842
</td>
<td>Release # / Deliv^undefined|
1.0668^8.3809x
2.1605^8.3809x
2.1525^8.6921x
1.0668^8.6842
</td>
<td>Weight
GALI^undefined|
2.1605^8.3809x
2.9429^8.3889x
2.9429^8.6921x
2.1525^8.6921
</td>
<td>Adjusted :^undefined|
2.9429^8.3889x
3.7812^8.3889x
3.7812^8.5086x
2.9429^8.5086
</td>
<td>2,400.0 Pallets^undefined|
3.7812^8.3889x
4.8829^8.3968x
4.8829^8.7001x
3.7812^8.7001
</td>
<td>^undefined|
4.8829^8.3968x
5.817^8.3968x
5.817^8.5166x
4.8829^8.5166
</td>
<td>^undefined|
5.817^8.3968x
7.1663^8.4128x
7.1663^8.7161x
5.817^8.7081
</td>
<td>^undefined|
7.1663^8.4128x
8.0125^8.4128x
8.0125^8.7241x
7.1663^8.7161
</td>
<tr>
<td>^undefined|
0.1806^8.5006x
0.5638^8.5006x
0.5638^8.6842x
0.1806^8.6762
</td>
<td>^undefined|
2.9429^8.5086x
3.7812^8.5086x
3.7812^8.7001x
2.9429^8.6921
</td>
<td>^undefined|
4.8829^8.5166x
5.817^8.5166x
5.817^8.7081x
4.8829^8.7001
</td>
<tr>
<td>Rec:^undefined|
0.1806^8.6762x
0.5638^8.6842x
0.5638^8.8757x
0.1806^8.8757
</td>
<td>10/25/23^undefined|
0.5638^8.6842x
1.0668^8.6842x
1.0668^8.8837x
0.5638^8.8757
</td>
<td>WT Ticket #S^undefined|
1.0668^8.6842x
2.1525^8.6921x
2.1525^8.8837x
1.0668^8.8837
</td>
<td>388186^undefined|
2.1525^8.6921x
2.9429^8.6921x
2.9429^8.8917x
2.1525^8.8837
</td>
<td>^undefined|
2.9429^8.6921x
3.7812^8.7001x
3.7812^8.8996x
2.9429^8.8917
</td>
<td>^undefined|
3.7812^8.7001x
4.8829^8.7001x
4.8829^8.9076x
3.7812^8.8996
</td>
<td>^undefined|
4.8829^8.7001x
5.817^8.7081x
5.817^8.9076x
4.8829^8.9076
</td>
<td>^undefined|
5.817^8.7081x
7.1663^8.7161x
7.1663^8.9236x
5.817^8.9076
</td>
<td>^undefined|
7.1663^8.7161x
8.0125^8.7241x
8.0125^8.9236x
7.1663^8.9236
</td>
<tr>
<td>occ^undefined|
0.1806^8.8757x
0.5638^8.8757x
0.5558^9.0433x
0.1806^9.0433
</td>
<td>^undefined|
0.5638^8.8757x
1.0668^8.8837x
1.0668^9.0433x
0.5558^9.0433
</td>
<td>152042^undefined|
1.0668^8.8837x
2.9429^8.8917x
2.9429^9.0513x
1.0668^9.0433
</td>
<td>42,380.0^undefined|
2.9429^8.8917x
3.7812^8.8996x
3.7732^9.0513x
2.9429^9.0513
</td>
<td>31,320.0^undefined|
3.7812^8.8996x
4.8829^8.9076x
4.8829^9.0593x
3.7732^9.0513
</td>
<td>8,460.0 LB^undefined|
4.8829^8.9076x
5.817^8.9076x
5.817^9.0593x
4.8829^9.0593
</td>
<td>$80.000000 Ton^undefined|
5.817^8.9076x
7.1663^8.9236x
7.1663^9.0752x
5.817^9.0593
</td>
<td>$338.40^undefined|
7.1663^8.9236x
8.0125^8.9236x
8.0125^9.0752x
7.1663^9.0752
</td>
<tr>
<td>^undefined|
0.1806^9.0433x
0.5558^9.0433x
0.5558^9.179x
0.1806^9.179
</td>
<td>^undefined|
0.5558^9.0433x
1.0668^9.0433x
1.0668^9.179x
0.5558^9.179
</td>
<td>^undefined|
1.0668^9.0433x
2.1525^9.0433x
2.1525^9.179x
1.0668^9.179
</td>
<td>Weight^undefined|
2.1525^9.0433x
2.9429^9.0513x
2.9429^9.187x
2.1525^9.179
</td>
<td>Adjusted :^undefined|
2.9429^9.0513x
3.7732^9.0513x
3.7732^9.187x
2.9429^9.187
</td>
<td>2,600.0 Pallets^undefined|
3.7732^9.0513x
4.8829^9.0593x
4.875^9.1949x
3.7732^9.187
</td>
<td>^undefined|
4.8829^9.0593x
5.817^9.0593x
5.817^9.1949x
4.875^9.1949
</td>
<td>^undefined|
5.817^9.0593x
7.1663^9.0752x
7.1663^9.2029x
5.817^9.1949
</td>
<td>^undefined|
7.1663^9.0752x
8.0125^9.0752x
8.0125^9.2109x
7.1663^9.2029
</td>
<tr>
<td>^undefined|
0.1806^9.179x
0.5558^9.179x
0.5558^9.4424x
0.1726^9.4344
</td>
<td>^undefined|
0.5558^9.179x
1.0668^9.179x
1.0588^9.4424x
0.5558^9.4424
</td>
<td>Release # / Deliv GALI^undefined|
1.0668^9.179x
2.9429^9.187x
2.9429^9.4503x
1.0588^9.4424
</td>
<td>^undefined|
2.9429^9.187x
3.7732^9.187x
3.7732^9.4583x
2.9429^9.4503
</td>
<td>^undefined|
3.7732^9.187x
4.875^9.1949x
4.875^9.4663x
3.7732^9.4583
</td>
<td>^undefined|
4.875^9.1949x
5.817^9.1949x
5.817^9.4743x
4.875^9.4663
</td>
<td>^undefined|
5.817^9.1949x
7.1663^9.2029x
7.1663^9.4823x
5.817^9.4743
</td>
<td>^undefined|
7.1663^9.2029x
8.0125^9.2109x
8.0125^9.4823x
7.1663^9.4823
</td>
<tr>
<td>^undefined|
0.1726^9.4344x
0.5558^9.4424x
0.5558^9.6898x
0.1726^9.6818
</td>
<td>^undefined|
0.5558^9.4424x
1.0588^9.4424x
1.0588^9.6898x
0.5558^9.6898
</td>
<td>Totals:^undefined|
1.0588^9.4424x
2.9429^9.4503x
2.9429^9.6978x
1.0588^9.6898
</td>
<td>374,700.0^undefined|
2.9429^9.4503x
3.7732^9.4583x
3.7732^9.7057x
2.9429^9.6978
</td>
<td>269,840.0^undefined|
3.7732^9.4583x
4.875^9.4663x
4.875^9.7057x
3.7732^9.7057
</td>
<td>86,780.0^undefined|
4.875^9.4663x
5.817^9.4743x
5.817^9.7137x
4.875^9.7057
</td>
<td>^undefined|
5.817^9.4743x
7.1663^9.4823x
7.1663^9.7217x
5.817^9.7137
</td>
<td>$4,213.64^undefined|
7.1663^9.4823x
8.0125^9.4823x
8.0125^9.7297x
7.1663^9.7217
</td>
</table><br>
<table border=1>
<tr>
<td>Item Name^undefined|
0.1935^3.1756x
1.871^3.1756x
1.871^3.3751x
0.1935^3.3751
</td>
<td>Order #^undefined|
1.871^3.1756x
2.7977^3.1756x
2.7977^3.3751x
1.871^3.3751
</td>
<td>Gross^undefined|
2.7977^3.1756x
3.8761^3.1756x
3.8761^3.3751x
2.7977^3.3751
</td>
<td>Tare^undefined|
3.8761^3.1756x
4.8267^3.1756x
4.8267^3.3831x
3.8761^3.3751
</td>
<td>Net^undefined|
4.8267^3.1756x
5.8971^3.1756x
5.9051^3.3831x
4.8267^3.3831
</td>
<td>Price^undefined|
5.8971^3.1756x
7.0475^3.1756x
7.0475^3.3831x
5.9051^3.3831
</td>
<td>Total^undefined|
7.0475^3.1756x
8.014^3.1836x
8.014^3.3831x
7.0475^3.3831
</td>
<tr>
<td>Hauling Charge^undefined|
0.1935^3.3751x
1.871^3.3751x
1.871^3.6465x
0.1935^3.6465
</td>
<td>^undefined|
1.871^3.3751x
2.7977^3.3751x
2.7977^3.6465x
1.871^3.6465
</td>
<td>^undefined|
2.7977^3.3751x
3.8761^3.3751x
3.8761^3.6465x
2.7977^3.6465
</td>
<td>^undefined|
3.8761^3.3751x
4.8267^3.3831x
4.8347^3.6465x
3.8761^3.6465
</td>
<td>^undefined|
4.8267^3.3831x
5.9051^3.3831x
5.9051^3.6465x
4.8347^3.6465
</td>
<td>^undefined|
5.9051^3.3831x
7.0475^3.3831x
7.0554^3.6545x
5.9051^3.6465
</td>
<td>$0.00^undefined|
7.0475^3.3831x
8.014^3.3831x
8.014^3.6545x
7.0554^3.6545
</td>
<tr>
<td>Freight Charge - S387394^undefined|
0.1935^3.6465x
1.871^3.6465x
1.871^3.8141x
0.1935^3.8141
</td>
<td>^undefined|
1.871^3.6465x
2.7977^3.6465x
2.7977^3.8141x
1.871^3.8141
</td>
<td>^undefined|
2.7977^3.6465x
3.8761^3.6465x
3.8761^3.8221x
2.7977^3.8141
</td>
<td>^undefined|
3.8761^3.6465x
4.8347^3.6465x
4.8347^3.8221x
3.8761^3.8221
</td>
<td>^undefined|
4.8347^3.6465x
5.9051^3.6465x
5.9051^3.8221x
4.8347^3.8221
</td>
<td>^undefined|
5.9051^3.6465x
7.0554^3.6545x
7.0554^3.8221x
5.9051^3.8221
</td>
<td>-$442.00^undefined|
7.0554^3.6545x
8.014^3.6545x
8.022^3.8221x
7.0554^3.8221
</td>
<tr>
<td>Freight Charge - S387399^undefined|
0.1935^3.8141x
1.871^3.8141x
1.871^3.9897x
0.1855^3.9897
</td>
<td>^undefined|
1.871^3.8141x
2.7977^3.8141x
2.7977^3.9897x
1.871^3.9897
</td>
<td>^undefined|
2.7977^3.8141x
3.8761^3.8221x
3.8761^3.9897x
2.7977^3.9897
</td>
<td>^undefined|
3.8761^3.8221x
4.8347^3.8221x
4.8347^3.9897x
3.8761^3.9897
</td>
<td>^undefined|
4.8347^3.8221x
5.9051^3.8221x
5.9051^3.9897x
4.8347^3.9897
</td>
<td>^undefined|
5.9051^3.8221x
7.0554^3.8221x
7.0554^3.9977x
5.9051^3.9897
</td>
<td>-$312.00^undefined|
7.0554^3.8221x
8.022^3.8221x
8.022^3.9977x
7.0554^3.9977
</td>
<tr>
<td>Freight Charge - S387500^undefined|
0.1855^3.9897x
1.871^3.9897x
1.871^4.1493x
0.1855^4.1493
</td>
<td>^undefined|
1.871^3.9897x
2.7977^3.9897x
2.7977^4.1493x
1.871^4.1493
</td>
<td>^undefined|
2.7977^3.9897x
3.8761^3.9897x
3.8761^4.1493x
2.7977^4.1493
</td>
<td>^undefined|
3.8761^3.9897x
4.8347^3.9897x
4.8347^4.1493x
3.8761^4.1493
</td>
<td>^undefined|
4.8347^3.9897x
5.9051^3.9897x
5.9051^4.1493x
4.8347^4.1493
</td>
<td>^undefined|
5.9051^3.9897x
7.0554^3.9977x
7.0554^4.1573x
5.9051^4.1493
</td>
<td>$442.00^undefined|
7.0554^3.9977x
8.022^3.9977x
8.022^4.1573x
7.0554^4.1573
</td>
<tr>
<td>Freight Charge - S387690^undefined|
0.1855^4.1493x
1.871^4.1493x
1.871^4.3169x
0.1855^4.3169
</td>
<td>^undefined|
1.871^4.1493x
2.7977^4.1493x
2.7977^4.3249x
1.871^4.3169
</td>
<td>^undefined|
2.7977^4.1493x
3.8761^4.1493x
3.8761^4.3249x
2.7977^4.3249
</td>
<td>^undefined|
3.8761^4.1493x
4.8347^4.1493x
4.8347^4.3249x
3.8761^4.3249
</td>
<td>^undefined|
4.8347^4.1493x
5.9051^4.1493x
5.9051^4.3249x
4.8347^4.3249
</td>
<td>^undefined|
5.9051^4.1493x
7.0554^4.1573x
7.0554^4.3249x
5.9051^4.3249
</td>
<td>-$442.00^undefined|
7.0554^4.1573x
8.022^4.1573x
8.022^4.3329x
7.0554^4.3249
</td>
<tr>
<td>Freight Charge - S387789^undefined|
0.1855^4.3169x
1.871^4.3169x
1.871^4.4846x
0.1855^4.4846
</td>
<td>^undefined|
1.871^4.3169x
2.7977^4.3249x
2.7977^4.4846x
1.871^4.4846
</td>
<td>^undefined|
2.7977^4.3249x
3.8761^4.3249x
3.8761^4.4846x
2.7977^4.4846
</td>
<td>^undefined|
3.8761^4.3249x
4.8347^4.3249x
4.8347^4.4846x
3.8761^4.4846
</td>
<td>^undefined|
4.8347^4.3249x
5.9051^4.3249x
5.9051^4.4846x
4.8347^4.4846
</td>
<td>^undefined|
5.9051^4.3249x
7.0554^4.3249x
7.0554^4.4925x
5.9051^4.4846
</td>
<td>-$442.00^undefined|
7.0554^4.3249x
8.022^4.3329x
8.022^4.4925x
7.0554^4.4925
</td>
<tr>
<td>Freight Charge - S387973^undefined|
0.1855^4.4846x
1.871^4.4846x
1.871^4.6442x
0.1855^4.6442
</td>
<td>^undefined|
1.871^4.4846x
2.7977^4.4846x
2.7977^4.6522x
1.871^4.6442
</td>
<td>^undefined|
2.7977^4.4846x
3.8761^4.4846x
3.8761^4.6522x
2.7977^4.6522
</td>
<td>^undefined|
3.8761^4.4846x
4.8347^4.4846x
4.8347^4.6522x
3.8761^4.6522
</td>
<td>^undefined|
4.8347^4.4846x
5.9051^4.4846x
5.9051^4.6522x
4.8347^4.6522
</td>
<td>^undefined|
5.9051^4.4846x
7.0554^4.4925x
7.0554^4.6522x
5.9051^4.6522
</td>
<td>-$442.00^undefined|
7.0554^4.4925x
8.022^4.4925x
8.022^4.6601x
7.0554^4.6522
</td>
<tr>
<td>Freight Charge - S388005^undefined|
0.1855^4.6442x
1.871^4.6442x
1.871^4.8198x
0.1855^4.8198
</td>
<td>^undefined|
1.871^4.6442x
2.7977^4.6522x
2.7977^4.8198x
1.871^4.8198
</td>
<td>^undefined|
2.7977^4.6522x
3.8761^4.6522x
3.8761^4.8198x
2.7977^4.8198
</td>
<td>^undefined|
3.8761^4.6522x
4.8347^4.6522x
4.8347^4.8198x
3.8761^4.8198
</td>
<td>^undefined|
4.8347^4.6522x
5.9051^4.6522x
5.9051^4.8198x
4.8347^4.8198
</td>
<td>^undefined|
5.9051^4.6522x
7.0554^4.6522x
7.0554^4.8278x
5.9051^4.8198
</td>
<td>-$312.00^undefined|
7.0554^4.6522x
8.022^4.6601x
8.022^4.8278x
7.0554^4.8278
</td>
<tr>
<td>Freight Charge - S388099^undefined|
0.1855^4.8198x
1.871^4.8198x
1.871^4.9874x
0.1855^4.9874
</td>
<td>^undefined|
1.871^4.8198x
2.7977^4.8198x
2.8056^4.9874x
1.871^4.9874
</td>
<td>^undefined|
2.7977^4.8198x
3.8761^4.8198x
3.8761^4.9874x
2.8056^4.9874
</td>
<td>^undefined|
3.8761^4.8198x
4.8347^4.8198x
4.8347^4.9874x
3.8761^4.9874
</td>
<td>^undefined|
4.8347^4.8198x
5.9051^4.8198x
5.9051^4.9874x
4.8347^4.9874
</td>
<td>^undefined|
5.9051^4.8198x
7.0554^4.8278x
7.0554^4.9954x
5.9051^4.9874
</td>
<td>-$442.00^undefined|
7.0554^4.8278x
8.022^4.8278x
8.022^4.9954x
7.0554^4.9954
</td>
<tr>
<td>Freight Charge - S388186^undefined|
0.1855^4.9874x
1.871^4.9874x
1.871^5.155x
0.1855^5.139
</td>
<td>^undefined|
1.871^4.9874x
2.8056^4.9874x
2.8056^5.155x
1.871^5.155
</td>
<td>^undefined|
2.8056^4.9874x
3.8761^4.9874x
3.8761^5.155x
2.8056^5.155
</td>
<td>^undefined|
3.8761^4.9874x
4.8347^4.9874x
4.8347^5.155x
3.8761^5.155
</td>
<td>^undefined|
4.8347^4.9874x
5.9051^4.9874x
5.9051^5.155x
4.8347^5.155
</td>
<td>^undefined|
5.9051^4.9874x
7.0554^4.9954x
7.0554^5.163x
5.9051^5.155
</td>
<td>-$442.00^undefined|
7.0554^4.9954x
8.022^4.9954x
8.022^5.163x
7.0554^5.163
</td>
<tr>
<td>Freight Charge - S388294^undefined|
0.1855^5.139x
1.871^5.155x
1.871^5.4024x
0.1775^5.4024
</td>
<td>^undefined|
1.871^5.155x
2.8056^5.155x
2.8056^5.4024x
1.871^5.4024
</td>
<td>^undefined|
2.8056^5.155x
3.8761^5.155x
3.8761^5.4104x
2.8056^5.4024
</td>
<td>^undefined|
3.8761^5.155x
4.8347^5.155x
4.8347^5.4104x
3.8761^5.4104
</td>
<td>^undefined|
4.8347^5.155x
5.9051^5.155x
5.9051^5.4104x
4.8347^5.4104
</td>
<td>^undefined|
5.9051^5.155x
7.0554^5.163x
7.0554^5.4184x
5.9051^5.4104
</td>
<td>-$442.00^undefined|
7.0554^5.163x
8.022^5.163x
8.022^5.4184x
7.0554^5.4184
</td>
</table><br>";
									?>

									<hr>
									<form name="frmsort" method="post" action="#" encType="multipart/form-data" id="mapping_tool_main_form">
										<div class="form-group row mt-2">
											<label class="col-sm-3 col-form-label">Company</label>
											<div class="col-sm-9">
												<select id="company_id" name="company_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?= $company_id ?>, 1)" class="form-control form-control-sm" required>
													<option value=""></option>
													<?
													$query = db_query("SELECT ID, nickname FROM companyInfo where active = 1 and ucbzw_account_status = 83 order by nickname", db_b2b());
													while ($rowsel_getdata = array_shift($query)) {
														$tmp_str = "";
														if (isset($_REQUEST["company_id"])) {
															if ($_REQUEST["company_id"] == $rowsel_getdata["ID"]) {
																$tmp_str = " selected ";
															}
														} else {
															if ($company_id == $rowsel_getdata["ID"]) {
																$tmp_str = " selected ";
															}
														}
													?>
														<option value="<? echo $rowsel_getdata["ID"]; ?>" <? echo $tmp_str; ?>><? echo $rowsel_getdata['nickname']; ?></option>
													<? }
													?>
												</select>
											</div>
										</div>
										<div id="div_vendor" class="full_input__box form-group row mt-2">
											<label class="col-sm-3 col-form-label"><span class="details">Vendor</span></label>
											<div class="col-sm-9">
												<select id="vendor_id" name="vendor_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?= $company_id ?>, 0)" class="form-control form-control-sm" required>
													<option value=""></option>
													<?
													$vendor_ids = "";
													$query = db_query("SELECT water_inventory.vendor FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id
													WHERE water_boxes_to_warehouse.water_warehouse_id = " . $warehouse_id . " group by water_inventory.vendor", db());
													while ($rowsel_getdata = array_shift($query)) {
														$vendor_ids = $vendor_ids . $rowsel_getdata["vendor"] . ",";
													}
													if ($vendor_ids != "") {
														$vendor_ids = substr($vendor_ids, 0, strlen($vendor_ids) - 1);
													}

													$query = db_query("SELECT * FROM water_vendors where active_flg = 1 and id in ($vendor_ids) order by Name", db());
													while ($rowsel_getdata = array_shift($query)) {
														$tmp_str = "";
														if ($vendor_id == $rowsel_getdata["id"]) {
															$tmp_str = " selected ";
														}

														$main_material = $rowsel_getdata['description'];

														//$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
														$vender_nm = $rowsel_getdata['Name'] . " - " . $main_material;
													?>
														<option value="<? echo $rowsel_getdata["id"]; ?>" <? echo $tmp_str; ?>><? echo $vender_nm; ?></option>
													<? }
													?>
													<option value="addvendor">Add Vendor</option>
												</select>
											</div>
										</div>
										<div class="form-group row mt-2">
											<label class="col-sm-3 col-form-label">Template name</label>
											<div class="col-sm-9">
												<input type="text" name="template_name" class="form-control form-control-sm" id="template_name" onblur="check_template_name()" required />
												<p id="template_name_error" class="text-danger mb-0 d-none highlight_error">Template name already exists, use other! </p>
											</div>
										</div>
										<?

										$mapping_field_sql = db_query("SELECT * FROM water_ocr_account_mapping_generic_fields ORDER BY id ASC");
										$field_count = 1;
										while ($fields_arr = array_shift($mapping_field_sql)) {
											$field_name_unq = strtolower(str_replace(' ', '_', $fields_arr['field_name']));

											$req_txt = "";
											if ($fields_arr['field_name'] == "Account number") {
												$req_txt = " required ";
											}
										?>
											<div id="mapping_field_div_<?= $field_count ?>">
												<div class="form-group row mt-2">
													<label class="col-sm-3 col-form-label">
														<?= $fields_arr['field_name']; ?>
														<? if ($field_count == 1) echo '<br><small class="d-flex align-items-center "><input type="checkbox" id="checkbox_account_no"/>&nbsp;Acct. No not found</small>'; ?>
													</label>
													<div class="col-md-9">
														<div class="row">
															<div class="col-sm-6 generic_field_dropdown_div">
																<select class='form-control form-control-sm generic_field_dropdown generic_field_dropdown_add' name="mapped_field[]" id="<?= $field_name_unq . "_dp"; ?>" <? echo $req_txt; ?>>
																	<option></option>
																</select>
															</div>
															<div class="col-sm-6">
																<input type="hidden" name="mapped_with[]" value="<?= $fields_arr['id']; ?>">
																<input type="text" name="field_value[]" id="<?= $field_name_unq; ?>" <? echo $req_txt; ?> class="form-control form-control-sm generic_field_input <?= $fields_arr['field_type'] ? "date" : ""; ?>">
															</div>
														</div>
													</div>
												</div>
												<? if ($field_count == 1) { ?>
													<div id="accout_no_not_found_div" class="d-none border py-2">
														<div id="account_no_not_found_container">
															<div class="row form-group">
																<div class="offset-md-3"></div>
																<div class="col-md-9">
																	<div class="row">
																		<div class="col-sm-6 generic_field_dropdown_div">
																			<select class='form-control form-control-sm generic_field_dropdown generic_field_dropdown_add' name="acc_no_not_found_mapped_field[]">
																				<option></option>
																			</select>
																		</div>
																		<div class="col-sm-6 d-flex">
																			<input type="text" name="acc_no_not_found_field_value[]" class="form-control form-control-sm generic_field_input">
																			<button type="button" class="btn btn-danger btn-sm mx-1 remove_added_more_field"><i class="fa fa-times"></i></button>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="text-right"><button type="button" id="add_more_field_for_unqiue_account_no" class="btn-sm btn btn-success">Add Field to Create Account No</button></div>

													</div>
												<? } ?>
											</div>
										<? $field_count++;
										} ?>


										<? //if(isset($_FILES['ocr_file']) && $_FILES['ocr_file']!=""){ 
										?>

										<div class="col-md-12 p-0 table-responsive mt-3 secondary_array_table">
										</div>
										<div class="text-center d-flex justify-content-center">
											<div id="save-ocr-data-loader" class="d-none spinner spinner-border text-primary" role="status">
												<span class="sr-only">Loading...</span>
											</div>
											<input type="hidden" name="ocr_extract_text" value="<?php echo $mainstr; ?>">
											<input type="hidden" id="form_action" name="form_action" value="add">
											<input type="hidden" name="ocr_file_name" id="uploaded_ocr_file_name" value="<?= $ocr_file; ?>">
											<button id="save-ocr-data" class="btn btn-sm btn-primary" type="submit">SAVE</button>
											<!--<button class="btn btn-sm btn-primary">RESET</button> -->
										</div>
										<div class="alert d-none mt-2" id="save-msg" data-dismiss="alert">
											<span id="save-msg-text"></span>
										</div>
									</form>
								</div>
							</div>
							<div id="rightpanel_view">
								<div class="col-md-12 mt-4">
									<? if (isset($_FILES['ocr_file']) && $_FILES['ocr_file'] != "") { ?>
										<p class="mb-1"><span id="pdf_file_name_pdf_lib"></span><span class="">&nbsp;&nbsp;<a target="_blank" id="pdf_file_href_pdf_lib" href="<?php echo "water_inv_files_PW/" . $ocr_file; ?>" class="text-dark fa fa-share-square-o fa-1x"></a></span></p>
										<div class="embed-responsive embed-responsive-21by9" style="height:800px">
											<iframe id="pdf_frame" src="<?php echo "water_inv_files_PW/" . $ocr_file; ?>"></iframe>
											<!--<iframe class="embed-responsive-item" src="https://loops.usedcardboardboxes.com/water_email_inbox_inv_files/2024_01/173/2024-01---Brand-Aromatics---WM--15-13198-32007--3335354-0515-8.pdf"></iframe>-->
										</div>
										<script>
											load_pdf_first_time();
											async function load_pdf_first_time() {
												const url = `<?= $ocr_file; ?>`;
												const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer())
												var bytes = new Uint8Array(existingPdfBytes);
												const pdfDoc = await PDFDocument.load(existingPdfBytes)
												pdfBytes = await pdfDoc.save()
												const blob = new Blob([pdfBytes], {
													type: 'application/pdf'
												});
												const blobUrl = URL.createObjectURL(blob);

												document.getElementById('pdf_frame').src = blobUrl;

											}

											$('#page-load').addClass('d-none');

											$('#ready_setup_pdf').removeClass('d-none');
											$('#choose-ocr-form').addClass('d-none');
										</script>

									<? } else { ?>
										<p class="mb-1"><span id="pdf_file_name_pdf_lib"></span><span class="">&nbsp;&nbsp;<a target="_blank" id="pdf_file_href_pdf_lib" href="" class="text-dark fa fa-share-square-o fa-1x"></a></span></p>
										<div class="embed-responsive embed-responsive-21by9" style="height:800px">
											<iframe width='100%' class="embed-responsive-item" src="water_inv_files_PW/blank_ocr.pdf"></iframe>
										</div>

										<script>
											// Get the file input element
											const fileInput = document.getElementById('ocr_file');

											// Add onchange event listener to the file input
											fileInput.addEventListener('change', function() {
												// Get the form element
												const form = document.getElementById('choose-ocr-form');

												$('#page-load').removeClass('d-none');

												// Submit the form
												form.submit();
											});
										</script>

									<? } ?>
								</div>
							</div>

						</div>
					</div>
				<?


			}
		}


		if ($mainstr != "") {
			$mainstr = str_replace("`", "", $mainstr);
				?>
				<script>
					var data = `<?= $mainstr; ?>`;
					var primary_array = data.replace(/\s+/g, ' ').replace().split("|Delimiter|")[0].split("|Keypair|<br> ");
					//console.log(primary_array);

					var all_generic_fields_array = [];
					var select_options = "<option></option>";
					for (var count = 0; count < primary_array.length; count++) {
						var item_array = primary_array[count].split("<br>");

						//console.log(item_array);

						var option_name, option_val, cor_x, cor_y, w, h;
						var first_option = item_array[0].split("|");
						var option_data = first_option[0].split("^");
						var option_name = option_data[0];
						var option_val = option_data[1];

						//console.log(first_option[1]);

						if (first_option[1]) {
							var position_data_array = first_option[1].split(" ");
							if (position_data_array[1]) {
								var cor_x = position_data_array[1].split('^')[0];
							}
							if (position_data_array[2]) {
								var cor_x2 = position_data_array[2].split('^')[0];
							}
							if (position_data_array[3]) {
								var cor_y = position_data_array[3].split('^')[1];
								cor_y = cor_y.replace("x", "");
							}
							if (position_data_array[1]) {
								var cor_y2 = position_data_array[1].split('^')[1];
								cor_y2 = cor_y2.replace("x", "");
							}
							var w = (cor_x2 - cor_x).toFixed(4);
							var h = (cor_y - cor_y2).toFixed(4);

							//console.log(`${option_name} : ${cor_x} ${cor_y}  ${w}  ${h}`);
							select_options += `<option value="${option_name}" cor_x="${cor_x}" cor_y="${cor_y}", w="${w}" h="${h}" option_val="${option_val}">${option_name}</option>`;
							all_generic_fields_array.push(`${option_name}||${option_val}||${cor_x}|${cor_y}|${w}|${h}`);
						}
					}

					const secondary_array = data.replace(/\s+/g, ' ').replace().split("|Delimiter|")[1].split('<table');
					//console.log(secondary_array);

					var finalData = [];
					var total_cols = 0;
					var uniq_count1 = 1;
					for (var count = 0; count < secondary_array.length; count++) {
						uniq_count1++;
						var item_array = secondary_array[count].split('<tr> ');
						item_array.shift();
						var tableData = "";
						var tableData_top_tds = "";
						var table_td_Data_flg = "";
						var total_cols_head = 0;
						var uniq_count2 = 0;
						item_array.forEach((val, index) => {
							//For table header
							if (index == 0) {
								const tdRegex = /<td>(.*?)\^undefined\| (.*?)<\/td>/g;
								const rowData = [];
								var table_td_Data = ""

								let datalength = val.length;
								var total_cols = 0;
								while ((tdMatch = tdRegex.exec(val)) !== null) {

									const val = tdMatch[1].trim(); // Extracted name						
									total_cols += total_cols;
									total_cols_head = total_cols_head + 1;

									tableData_top_tds += `
									<td><span class="nowrap_word">${val}</span>
										<input type="hidden" name="tbl_column_no-${count}[]" value="${total_cols_head}">
										<input type="hidden" name="tbl_column_text-${count}[]" value="${val}">
										<select id="col_head${count}${total_cols_head}" name="table_mapping_field${count}${uniq_count2}[]" class="form-control form-control-sm mt-1">
											<option></option>
											<option>Material/Fee column</option>
											<option>Quantity</option>
											<option>Unit Price</option>
											<option>Amount</option>
										</select>
									</td>
								`;
								}
							}

							if (index > 0) {
								const tdRegex = /<td>(.*?)\^undefined\| (.*?)<\/td>/g;
								const rowData = [];
								var table_td_Data = "";

								let datalength = val.length;

								var colspan_val = "colspan=0";
								colspan_cnt = 0;
								//to show column in span for big text
								while ((tdMatch = tdRegex.exec(val)) !== null) {
									const val = tdMatch[1].trim(); // Extracted name

									if (val) {
										colspan_cnt = colspan_cnt + 1;
									}

								}

								/*if (colspan_cnt < total_cols_head) {
									colspan_val = "colspan=" + (total_cols_head - colspan_cnt + 1);
								} else if (colspan_cnt == 1) {
									colspan_val = "colspan=" + colspan_val;
								} else {
									colspan_val = "";
								}
								*/
								/*if (colspan_cnt < total_cols_head) {
									colspan_val = total_cols_head - colspan_cnt + 1;
								}
								if (colspan_cnt == 1) {
									colspan_val = "colspan=" + colspan_val;
								}*/

								var tdcnt = 0;
								//while ((tdMatch = tdRegex.exec(val)) !== null) {
								while ((tdMatch = tdRegex.exec(val))) {
									const val = tdMatch[1].trim(); // Extracted name
									const value = tdMatch[2].trim(); // Extracted cordinates
									// rowData.push({"value" : key});

									var option_name, option_val, cor_x, cor_y, w, h;
									//if (val) {
									tdcnt = tdcnt + 1;

									var position_data_array = value.split(" ");
									if (position_data_array[0]) {
										var cor_x = position_data_array[0].split('^')[0];
									}
									if (position_data_array[1]) {
										var cor_x2 = position_data_array[1].split('^')[0];
									}
									if (position_data_array[0]) {
										var cor_y = position_data_array[0].split('^')[1];
										cor_y = cor_y.replace("x", "");
									}
									if (position_data_array[2]) {
										var cor_y2 = position_data_array[2].split('^')[1];
										cor_y2 = cor_y2.replace("x", "");
									}
									var w = (cor_x2 - cor_x).toFixed(4);
									var h = (cor_y - cor_y2).toFixed(4);
									table_td_Data_flg = 1;

									//${colspan_val}
									
									table_td_Data += `
									<td ${colspan_val} class="text-center">
										<div>
										<textarea style="resize: both; padding:0px" name="table_mapping_field${count}${uniq_count2}[]" id="row_txt${count}" class="form-control form-control-sm" 
										onclick="updateCursorPosition(this, ${uniq_count1}, ${uniq_count2})" onfocus="modifyPdf(${cor_x ?? ''}, ${cor_y ?? ''}, ${w ?? ''},${h ?? ''})" >${val ?? ''}</textarea>
										
										<input class="table_mapping_field_cord" type="hidden" name="table_mapping_field_cord${count}${uniq_count2}[]" value="${cor_x}|${cor_y}|${w}|${h}"/>
										</div>
										</td>
									`;

									//console.log(`${cor_x} ${cor_x2} ${cor_y} ${cor_y2}  ${w}  ${h}`);

									rowData.push({
										"length": datalength,
										"value": val,
										"cor_x": cor_x,
										"cor_y": cor_y,
										"w": w,
										"h": h
									});
									//}
								}

								tableData += `
							<tr class="list_item_tr">
								<td> 
									<div class="d-flex water_material_div">
										<select class="form-control form-control-sm mr-1 water_material_select" name='water_material${count}[]'>
											<option value="0"></option>
										</select>
									</div>
								</td>
								<td>
									<select class="form-control form-control-sm mr-1 p-0" name="add_fee${count}[]" style="min-width:40px">
										<option value=""></option>
										<?
										$query = db_query("SELECT id,additional_fees_display FROM water_additional_fees  where active_flg = 1 order by display_order", db());
										while ($rowsel_getdata = array_shift($query)) {
										?>
												<option value="<? echo $rowsel_getdata["id"]; ?>" ><? echo $rowsel_getdata['additional_fees_display']; ?></option>
											<? }
											?>
									</select>
								</td>
								<td>
									<input class="consider_line_item" type="checkbox">
									<input type="hidden" class="consider_line_item_array" name="consider_line_item_array${count}[]" value="0">
								</td>
								<td>
									<input type="checkbox" class="group_text_checkbox">
									<input type="hidden" class="group_text_array" name="group_text_array${count}[]" value="0">
								</td>
								<td>
									<input type="hidden" name="text_start_position${count}[]" id="row_txt_start_position${uniq_count1}${uniq_count2}" class="form-control form-control-sm row_txt_start_position" value="" size="5">

									<input type="hidden" name="text_end_position${count}[]" id="row_txt_end_position${uniq_count1}${uniq_count2}" class="form-control form-control-sm row_txt_end_position" value="" size="5">
									<input class="row_no_text" type="hidden" name="row_no${count}[]" value="${uniq_count2}">
									
									<div id='divrow_txt-${uniq_count1}-${uniq_count2}' style="display:none;" class="divrow_txt"></div>
									<input class="input_row_txt" id='input_row_txt-${uniq_count1}-${uniq_count2}' type="hidden" name="ocr_selected_text${count}[]" value="">
								</td>
								${table_td_Data}
								
							</tr>
							`;
							}
							uniq_count2++;
						})

						tableData += `<tr>
									<td colspan="${total_cols_head+6}" align="right">
										<button type="button" class="btn btn-primary btn-sm add_tbl_row">Add Row</button>
									</td>
								</tr>
					`;

						tableData_top = `<div class="table_container"><table class="table table-sm table-bordered mapping_table default_table">
						<thead >
							<tr>
								<td colspan="${total_cols_head+6}">
									<input type="checkbox" id="chk_water_mapping${count}" class="consider_table_checkbox" name="consider_table_checkbox[]" title="${count}">
									<b class="text-dark">Consider this table for Water mapping</b>
									<input type="hidden" name="consider_water_mapping_table_array[]" value="0">
									<input type="hidden" class="added_now_count" value="0">
								</td>
							</tr>
							<tr class="thead-dark">
							<td>Material<input type="hidden" name="water_material${count}[]"></td>
							<td>Fee<input type="hidden" name="add_fee${count}[]"></td>
							<td>Consider Line Item<input type="hidden" name="consider_line_item_array${count}[]"></td>
							<td>Group text<input type="hidden" name="group_text_array${count}[]"></td>
							<td><span data-toggle="tooltip" data-placement="top" title="Enter number of character length to consider for OCR (Part text take from Water material name)">OCR Text
							<input type="hidden" name="text_start_position${count}[]"><input type="hidden" name="text_end_position${count}[]"><input type="hidden" name="row_no${count}[]" value="${uniq_count2}">
							<input type="hidden" name="ocr_selected_text${count}[]" >
							</td>
							${tableData_top_tds}

							</tr>
						</thead>
					`;

						if (table_td_Data_flg == 1) {
							$('.secondary_array_table').append(tableData_top + tableData + '</table></div>');

							// console.log(tableData);
						}

					}
					// pre variable was $ocr_file_orgnm instead of $ocr_file in next line
				</script>

			<? } ?>

			<script>
				$(".generic_field_dropdown_add").html(select_options);
				var select_default_fields = $(".generic_field_dropdown_add");
				$.each(select_default_fields, function(index, data) {
					$(this).val($(this).attr('edit_val'));
				})



				$('body').on("change", ".generic_field_dropdown", function() {
					var selected_op = $(this).val();
					var selected_dp_id = $(this).attr('id');
					var option_val = $('option:selected', this).attr('option_val');
					if (selected_dp_id == "terms_dp" && selected_op != "" && $('#invoice_due_date').val() == "") {
						let digitsArray = [];
						for (let i = 0; i < option_val.length; i++) {
							if (!isNaN(parseInt(option_val[i]))) { // Check if the character is a digit
								digitsArray.push(option_val[i]); // Add digit to array
							}
						}
						let today = new Date();
						let digitsString = digitsArray.join(""); // Convert array of digits to string
						var daysToAdd = digitsString != "" ? parseInt(digitsString) : 1;
						today.setDate(today.getDate() + daysToAdd);
						// Extract month, day, and year components
						let month = today.getMonth() + 1; // Months are zero-based, so we add 1
						let day = today.getDate();
						let year = today.getFullYear() % 100;
						let formattedDate = `${month}/${day}/${year < 10 ? '0' : ''}${year}`;
						$('#invoice_due_date').val(formattedDate)
					}
					//console.log("selected_val "+selected_val);
					//console.log(option_val);
					var cor_x = $('option:selected', this).attr('cor_x');
					var cor_y = $('option:selected', this).attr('cor_y');
					var w = $('option:selected', this).attr('w');
					var h = $('option:selected', this).attr('h');
					$(this).parents(".form-group").find('.generic_field_input ').val(option_val);
					$(this).parents(".form-group").find('.generic_field_input ').attr({
						'selected_op': selected_op,
						'option_val': option_val,
						'cor_x': cor_x,
						'cor_y': cor_y,
						'w': w,
						h
					});
					modifyPdf(cor_x, cor_y, w, h);
				});

				$('body').on("focus", ".generic_field_input", function() {
					var cor_x = $(this).attr('cor_x');
					if (typeof cor_x == 'undefined' || cor_x == false || cor_x == "") {
						alert("Choose the value from the dropdown!");
						$(this).blur();
						$(this).parents(".form-group").find('.generic_field_dropdown').focus();
					} else {
						var cor_y = $(this).attr('cor_y');
						var w = $(this).attr('w');
						var h = $(this).attr('h');
						var option_val = $(this).attr('option_val');
						var selected_op = $(this).attr('selected_op');
						modifyPdf(cor_x, cor_y, w, h);
					}
				});

				function updateCursorPosition(ctrltextbox, uniq_count1, uniq_count2) {
					// Get the current cursor position
					var selectionStart = ctrltextbox.selectionStart;
					var selectionEnd = ctrltextbox.selectionEnd;
					var txtval = ctrltextbox.value;
					var seltxtval = txtval.substring(selectionStart, selectionEnd);
					$("#row_txt_start_position" + uniq_count1 + "" + uniq_count2).val(selectionStart + 1);
					$("#row_txt_end_position" + uniq_count1 + "" + uniq_count2).val(selectionEnd);
					$("#divrow_txt-" + uniq_count1 + "-" + uniq_count2).css("display", "inline");
					$("#divrow_txt-" + uniq_count1 + "-" + uniq_count2).html("<small>" + seltxtval + "</small>");

					// Update the display
					//document.getElementById("row_txt_position"+tbl_head_cnt+ctrl_cnt).innerText = cursorPosition;

					$("#input_row_txt-" + uniq_count1 + "-" + uniq_count2).val(seltxtval);
				}

				$('body').on("click", '.add_tbl_row', function() {
					let parentTable = $(this).closest('table');
					var added_now_count = parentTable.find('.added_now_count').val();

					let lastRow = parentTable.find('tbody tr:nth-last-child(2)');
					// Get the second-to-last row of the table
					let secondLastRow = parentTable.find('tbody tr:nth-last-child(1)');
					// Clone the last row
					let clonedRow = lastRow.clone();
					if (!$(this).parent('td').find('.removeButton').length && added_now_count == 0) {
						// Add remove button inside the last td of the cloned row
						let removeButton = $('<button type="button" class="mr-2 btn btn-sm btn-danger removeButton">Remove last row</button>');
						$(this).before(removeButton);
						//clonedRow.find('td:last').addClass('d-flex');
					}
					var unque_keys = clonedRow.find('.divrow_txt').attr('id').split('-');
					let unque_number1 = unque_keys[1];
					let unque_number2 = parseInt(unque_keys[2]) + 1;
					console.log(unque_number1 + "- " + unque_number2);
					clonedRow.find('.divrow_txt').attr('id', 'divrow_txt-' + unque_number1 + '-' + unque_number2);
					clonedRow.find('.row_txt_start_position').attr('id', 'row_txt_start_position' + unque_number1 + unque_number2);
					clonedRow.find('.row_txt_end_position').attr('id', 'row_txt_end_position' + unque_number1 + unque_number2);
					clonedRow.find('.row_txt_start_position').val("");
					clonedRow.find('.row_txt_end_position').val("");
					clonedRow.find('textarea').attr('onclick', 'updateCursorPosition(this, ' + unque_number1 + ', ' + unque_number2 + ')');
					clonedRow.find('textarea').attr('onfocus', 'modifyPdf(0,0,0,0,0)');
					clonedRow.find('textarea').attr('name', 'table_mapping_field' + (unque_number1 - 2) + unque_number2 + '[]');
					clonedRow.find('.table_mapping_field_cord').attr('name', 'table_mapping_field_cord' + (unque_number1 - 2) + unque_number2 + '[]');
					clonedRow.find('.row_no_text').val(unque_number2);
					clonedRow.find('.input_row_txt').attr('id', 'input_row_txt-' + unque_number1 + '-' + unque_number2);
					clonedRow.find('.input_row_txt').val("");
					clonedRow.find('.divrow_txt').text("");
					clonedRow.find('select').val('');
					clonedRow.find('input[type="checkbox"]').prop('checked', false);
					clonedRow.find('.table_mapping_field_cord').val("0|0|0|0");
					if (clonedRow.find('.water_list_item_body_id').length) {
						clonedRow.find('.water_list_item_body_id').val('');
					}
					clonedRow.find('textarea').val('');
					clonedRow.find('textarea').html('');
					secondLastRow.before(clonedRow);
					parentTable.find('.added_now_count').val(parseInt(added_now_count) + 1);

				});
				$('body').on('click', '.removeButton', function() {
					var added_now_count = $(this).closest('table').find('.added_now_count').val();
					$(this).closest('table').find('tbody tr:nth-last-child(2)').remove();
					$(this).closest('table').find('.added_now_count').val(parseInt(added_now_count) - 1);
					if (added_now_count == 1) {
						$(this).remove();
					}
				});

				//console.log(finalData);

				const {
					drawRectangle,
					grayscale,
					PDFDocument,
					rgb,
					colorRgb
				} = PDFLib

				const renderInIframe = (pdfBytes) => {
					const blob = new Blob([pdfBytes], {
						type: 'application/pdf'
					});
					const blobUrl = URL.createObjectURL(blob);
					//document.getElementById('iframe').src = blobUrl;
					document.getElementById('pdf_frame').src = blobUrl;
				};
				async function modifyPdf(cor_x = "", cor_y = "", w = "", h = "") {
					const url = `water_inv_files_PW/<?= $ocr_file; ?>`;
					const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer())
					cor_y = cor_y - 0.026;

					var bytes = new Uint8Array(existingPdfBytes);
					const pdfDoc = await PDFDocument.load(existingPdfBytes)
					const pages = pdfDoc.getPages();
					var firstPage = pages[0];
					var pgheight = firstPage.getHeight();
					pgheight = pgheight - 2;

					if (cor_x != "") {
						const {
							pg_width,
							pg_height
						} = firstPage.getSize();
						var x = cor_x * 72;
						var y = pgheight - (cor_y * 72);
						var width = w * 72;
						var height = h * 72;

						try {
							firstPage.drawRectangle({
								x,
								y,
								width,
								height,
								borderWidth: 1,
								borderColor: rgb(0, 1, 0), //green
								opacity: 1,
							});
						} catch (err) {
							console.log(err.message);
						}
					}
					pdfBytes = await pdfDoc.save()
					renderInIframe(pdfBytes);
				}

				function check_template_name() {
					var res;
					$.ajax({
						url: 'water_invoice_ocr_mapping_form_save.php',
						data: {
							template_name: $("#template_name").val(),
							check_for_template: 1,
							'template_form_action': $('#form_action').val(),
							'template_id': '<?= isset($template_id) ? $template_id : "" ?>'
						},
						type: 'POST',
						async: false,
						success: function(data) {
							console.log("data " + data);
							if (data == 1) {
								$('#template_name_error').removeClass('d-none');
								res = false;
							} else {
								$('#template_name_error').addClass('d-none');
								res = true;
							}
						},
					});
					console.log(res);
					return res;
				}

				$(document).ready(function() {
					$("#mapping_tool_main_form").submit(function() {
						var temp_name_val = check_template_name();
						if (temp_name_val == true) {
							var fd = new FormData(this);
							fd.append('account_no', $("#account_number").val());
							if ($('#checkbox_account_no').prop('checked')) {
								fd.append('generate_unique_account_no', 1);
							} else {
								fd.append('generate_unique_account_no', 0);
							}
							fd.append('selected_line_item_array', selected_line_item);
							if ($('#form_action').val() != 'update') {
								var all_generic_fields_array_String = all_generic_fields_array.join(" && ");
								fd.append('all_generic_fields_array', all_generic_fields_array_String);
							}
							//console.log(fd);
							$.ajax({
								url: 'water_invoice_ocr_mapping_form_save.php',
								data: fd,
								processData: false,
								contentType: false,
								type: 'POST',
								async: false,
								beforeSend: function() {
									$("#save-ocr-data-loader").removeClass('d-none').addClass('d-inline-block');
									$('#save-ocr-data').attr('disabled', true);
								},
								success: function(data) {
									console.log(data);
									if (data == 1) {
										$("#save-msg").addClass('alert-primary').removeClass('d-none');
										$("#save-msg #save-msg-text").html("Mapping data saved successfully!");
										var msg = $('#form_action').val() == 'update' ? "Template updated Successfully" : "Template Added Successfully";
										alert(msg)
										window.location.href = "water_invoice_ocr_mapping_templates.php";
									} else {
										$("#save-msg").addClass('alert-danger').removeClass('d-none');
										$("#save-msg #save-msg-text").html("Something went wrong, try again later!")
									}
								},
								complete: function() {
									$("#save-ocr-data-loader").addClass('d-none');
									$('#save-ocr-data').attr('disabled', false);
								},
							});
						} else {
							$('#template_name').focus();
						}
						return false;
					});

					$(document).on('change', "#checkbox_account_no", function() {
						if ($('#checkbox_account_no').prop('checked')) {
							$("#accout_no_not_found_div").removeClass("d-none");

							$("select[name='acc_no_not_found_mapped_field[]']").attr('required', 'required');
							$("input[name='acc_no_not_found_field_value[]']").attr('required', 'required');
							$("#account_number").removeAttr('required').attr('readonly', 'true').addClass('disabled');
							$("#account_number").parents('.form-group').find('.generic_field_dropdown').removeAttr('required').attr('readonly', 'true').addClass('disabled');
							$("#account_number").val("");
							$("#account_number").parents('.form-group').find('.generic_field_dropdown').val("");
						} else {
							$("#accout_no_not_found_div").addClass("d-none");
							$("select[name='acc_no_not_found_mapped_field[]']").removeAttr('required');
							$("input[name='acc_no_not_found_field_value[]']").removeAttr('required');
							$("#account_number").attr('required', 'required').removeAttr('readonly').removeClass('disabled');
							$("#account_number").parents('.form-group').find('.generic_field_dropdown').attr('required', 'required').removeAttr('readonly').removeClass('disabled');

						}
					})

					var unq_account_no_html = $('#account_no_not_found_container_for_html').html();
					$(document).on('click', "#add_more_field_for_unqiue_account_no", function() {
						$("#account_no_not_found_container").append(unq_account_no_html);
					});

					$(document).on('click', ".remove_added_more_field", function() {
						if ($('#form_action').val() == "update") {
							var data_id = $(this).attr('field_value_id');
							alert(data_id)
							$.ajax({
								url: 'water_invoice_ocr_mapping_form_save.php',
								data: {
									data_id,
									'form_action': 'remove_value'
								},
								type: 'post',
								async: false,
								beforeSend: function() {
									$("#save-ocr-data-loader").removeClass('d-none');
									$('#save-ocr-data').attr('disabled', true);
								},
								success: function(data) {
									if (data == 1) {
										$("#save-msg").addClass('alert-primary').removeClass('d-none');
										$("#save-msg #save-msg-text").html("Mapping data saved successfully!");
									} else {
										$("#save-msg").addClass('alert-danger').removeClass('d-none');
										$("#save-msg #save-msg-text").html("Something went wrong, try again later!")
									}
								},
								complete: function() {
									$("#save-ocr-data-loader").addClass('d-none');
									$('#save-ocr-data').attr('disabled', false);
								},
							});
						}
						$(this).parents(".form-group").remove();
					});
				});
				setTimeout(function() {
					$('#save-msg').addClass('d-none');
				}, 5000);


				selected_line_item = [];

				$(document).on('click', '.consider_table_checkbox', function() {
					if ($(this).prop('checked') == true) {
						$(this).parents('td').find('input[name="consider_water_mapping_table_array[]"]').val(1);
						$(this).parents('.mapping_table').removeClass('default_table').addClass('selected_table');
					} else {
						var conf = confirm("Do you sure want to remove this table consideration? u will lost all the data mapped within this table ");
						$(this).parents('td').find('input[name="consider_water_mapping_table_array[]"]').val(0);
						$(this).parents('.mapping_table').find('select').val("");
						$(this).parents('.mapping_table').find('.consider_line_item').prop('checked', false);
						$(this).parents('.mapping_table').find('.consider_line_item_array').val(0);
						$(this).parents('.mapping_table').find('.group_text_checkbox').prop('checked', false);
						$(this).parents('.mapping_table').find('.group_text_array').val(0);
						$(this).parents('.mapping_table').find('.row_txt_start_position').val("");
						$(this).parents('.mapping_table').find('.row_txt_end_position').val("");
						$(this).parents('.mapping_table').find('.divrow_txt').html("");
						$(this).parents('.mapping_table').removeClass('selected_table').addClass('default_table');
					}
				});
				$(document).on('click', '.consider_line_item', function() {
					var name_uniq = $(this).attr("uniq");
					var row_no = $(this).attr("row_no");
					if ($(this).parents('tr').find('.water_material_select option').length > 1) {
						if ($(this).parents('.mapping_table').find('.consider_table_checkbox').prop('checked') == true) {
							if ($(this).prop('checked') == true) {
								$(this).parent('td').find('.consider_line_item_array').val(1);
							} else {
								$(this).parent('td').find('.consider_line_item_array').val(0);
							}
						} else {
							alert("Please Check consider table checkbox!");
							$(this).prop('checked', false);
						}
					} else {
						alert("Please Select Company & Vendor First");
						$(this).prop('checked', false);
						$('#company_id').focus();
					}
					// console.log(selected_line_item);
				});

				$(document).on('click', '.group_text_checkbox', function() {
					if ($(this).prop('checked') == true) {
						$(this).parent('td').find('.group_text_array').val(1);
					} else {
						$(this).parent('td').find('.group_text_array').val(0);
					}
				});

				$(document).ready(function() {
					$('.mapping_table tbody').on("contextmenu", "tr", function(e) {
						e.preventDefault();
						$('#settings').parents('td').remove();
						$('#item_list_tbl_row').removeAttr('id');
						$(this).append(`<td style="position:relative"><button class="context-menu btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#settingModal" id="settings"><i class="fa fa-cog"></i></button></td>`);
						$(this).css('position', 'relative');
						$(this).find('.context-menu').css({
							'display': 'block'
						});

						$(this).attr('id', "item_list_tbl_row");
					});

					$(document).on("click", '#settings', function() {
						//alert($(this).parents('tr').html());
						var tablerow = $(this).parents('tr').html();
						var tablehead = $(this).parents('table').find('.thead-dark').html();
						//alert(tablehead);

						$('#settingModal .modal-body table').html(`<tr>${tablehead}</tr><tr id="modal_tbl_row">${tablerow}</tr>`);
						$("#settingModal").modal('show');
						addMergeArrows();
						$('#settingModal').find('#settings').parents('td').remove();
					});
				});

				function addMergeArrows() {
					const rows = $('#modal_tbl_row');

					// Add merge arrows after the 4th column of each row
					rows.each(function() {
						const cells = $(this).find('td');

						// Start adding arrows after the 4th <td>
						cells.each(function(index) {
							if (index >= 5 && index < cells.length - 1) {
								const merge_arrow = $('<span class="arrow-button fa fa-arrow-right" data-toggle="tooltip" data-placement="bottom" title="Merge With Next Column"></span>');
								const split_arrow = $('<span class="arrow-button fa fa-arrows-h" data-toggle="tooltip" data-placement="bottom" title="Split Current Column"> </span>');
								merge_arrow.on('click', function() {
									mergeColumns(index);
								});
								split_arrow.on('click', function() {
									splitColumns(index);
								});

								$(this).find('div').append(merge_arrow);
								$(this).append(split_arrow);
								$(this).find('div').css({
									'display': 'flex',
									'align-items': 'center'
								});
							}
						});
					});
				}

				function mergeColumns(index) {
					$('#modal_tbl_row').each(function() {
						const tds = $(this).find('td');
						const targetCell = $(tds[index]);
						const nextCell = $(tds[index + 1]);
						const merged_text = targetCell.find('textarea').val() + " " + nextCell.find("textarea").val();
						var conf = confirm(`Merged Text will be "${merged_text}", Do you want to merge?`);
						if (conf) {
							targetCell.find('textarea').val(merged_text);
							targetCell.find('textarea').html(merged_text);
							nextCell.remove();
						}
						//targetCell.remove(".arrow_button")
						nextCell.remove();
					});

					// Remove all arrow buttons
					$('.arrow-button').remove();

					// Re-add arrow buttons to reflect the new structure
					addMergeArrows();
				}

				function splitColumns(index) {
					$("#current_target_index").val(index);
					$("#split_inputs").removeClass('d-none');
					$("#split_input1").focus();
					/*$('#modal_tbl_row').each(function() {
						const tds = $(this).find('td');
						const targetCell = $(tds[index]);
						const split_text_arr = (targetCell.find('textarea').val()).split(" ");
						var conf = confirm(`Split Text will be "${existing_cell_val}" & "${new_cell_val}", Do you want to split?`);
						if (conf) {
							targetCell.find('textarea').val(existing_cell_val);
							targetCell.find('textarea').html(existing_cell_val);
							let newCell = targetCell.clone();
							newCell.find('textarea').val(existing_cell_val);
							newCell.find('textarea').html(existing_cell_val);
							targetCell.after(newCell);

						}
					});
					// Remove all arrow buttons
					//$('.arrow-button').remove();
					// Re-add arrow buttons to reflect the new structure
					addMergeArrows();
					
					*/
				}

				function split_cols_value(){
					var index = $("#current_target_index").val();
					$('#modal_tbl_row').each(function() {
						const tds = $(this).find('td');
						const targetCell = $(tds[index]);
						const existing_cell_val = $("#split_input1").val();
						const new_cell_val = $("#split_input2").val();
						var conf = confirm(`Split Text will be "${existing_cell_val}" & "${new_cell_val}", Do you want to split?`);
						if (conf) {
							targetCell.find('textarea').val(existing_cell_val);
							targetCell.find('textarea').html(existing_cell_val);
							let newCell = targetCell.clone();
							newCell.find('textarea').val(new_cell_val);
							newCell.find('textarea').html(new_cell_val);
							targetCell.after(newCell);

						}
					});
					// Remove all arrow buttons
					$("#split_inputs").addClass('d-none');
					$('.arrow-button').remove();
					// Re-add arrow buttons to reflect the new structure
					addMergeArrows();
				}

				function row_setting() {
					//alert($("#modal_tbl_row").html());
					$("#modal_tbl_row").find('.arrow-button').remove();
					$("#item_list_tbl_row").html($("#modal_tbl_row").html());
					$("#settingModal").modal('hide')
				}
			</script>
			<!-- Modal -->
			<div class="modal fade" id="settingModal" tabindex="-1" aria-labelledby="settingModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="settingModalLabel">Setting</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<table class="table"></table>
							<div id="split_inputs" class="d-none">
							<div class="form-row align-items-end">
								<div class="form-group col-md-4 mb-0">
									<label><b>Value of Existing Column</b></label>
									<input type="text" id="split_input1" class="form-control form-control-sm"/>
								</div>
								<div class="form-group col-md-4 mb-0">
									<label><b>Value of New Column</b></label>
									<input type="text" id="split_input2" class="form-control form-control-sm"/>
									<input type="hidden" id="current_target_index"/>
								</div> 
								<button onclick="split_cols_value()" class="btn btn-primary btn-sm" style="height: max-content">Ok</button>
							</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary btn-sm" onclick="row_setting()">Apply changes</button>
						</div>
					</div>
				</div>
			</div>
</body>