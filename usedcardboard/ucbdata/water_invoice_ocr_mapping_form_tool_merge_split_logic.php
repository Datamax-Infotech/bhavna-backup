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
									$mainstr = "CustomerName^KDC ONE^0.918|
1.2463^2.2673x
1.7443^2.2726x
1.743^2.3967x
1.245^2.3914
|Keypair|<br>
RemittanceAddress^[object Object]^0.786|
0.4011^8.398x
1.5376^8.398x
1.5376^8.6319x
0.4011^8.6319
|Keypair|<br>
RemittanceAddressRecipient^AEROFIL TECHNOLOGY INC^0.625|
0.5539^1.0456x
2.3829^1.0456x
2.3829^1.1745x
0.5539^1.1745
|Keypair|<br>
ServiceAddress^[object Object]^0.773|
0.5539^2.5399x
2.8079^2.5399x
2.8079^2.8168x
0.5539^2.8168
|Keypair|<br>
ServiceAddressRecipient^CLEAN EARTH OF CALVERT CITY LLC^0.873|
0.5635^2.3967x
3.1469^2.3967x
3.1469^2.5543x
0.5635^2.5543
|Keypair|<br>
VendorAddress^[object Object]^0.747|
1.466^10.4223x
4.6559^10.4223x
4.6559^10.5655x
1.466^10.5655
|Keypair|<br>
VendorAddressRecipient^Univar Solutions USA,^0.886|
0.3581^10.4271x
1.4374^10.4271x
1.4374^10.5655x
0.3581^10.5655
|Keypair|<br>
VendorName^Univar
Solutions^0.921|
0.6734^0.403x
1.2272^0.4127x
1.2225^0.678x
0.6688^0.6683
|Keypair|<br>
CustomerAddress^[object Object]^0.696|
1.6133^1.3614x
2.7601^1.3579x
2.7608^1.5833x
1.614^1.5868
|Keypair|<br>
CustomerAddressRecipient^AEROFIL
TECHNOLOGY INC^0.748|
1.614^1.0981x
2.7601^1.0981x
2.7601^1.3416x
1.614^1.3416
|Keypair|<br>
BillingAddress^[object Object]^0.878|
1.2511^2.3872x
2.6121^2.3872x
2.6121^2.6354x
1.2511^2.6354
|Keypair|<br>
BillingAddressRecipient^KDC ONE^0.918|
1.2463^2.2673x
1.7443^2.2726x
1.743^2.3967x
1.245^2.3914
|Keypair|<br>
CustomerId^988501^0.821|
6.6758^1.1745x
7.034^1.1649x
7.034^1.2795x
6.6806^1.2747
|Keypair|<br>
DueDate^Fri Jun 28 2024 19:00:00 GMT-0500 (Central Daylight Time)^0.918|
6.6758^0.826x
7.2823^0.826x
7.2823^0.9358x
6.6758^0.9358
|Keypair|<br>
InvoiceDateVal^30 Apr 2024^0.917|
4.675^0.8212x
5.2767^0.8212x
5.2767^0.9501x
4.675^0.9501
|Keypair|<br>
InvoiceId^52048412^0.938|
2.8986^0.826x
3.3904^0.826x
3.3904^0.9358x
2.9034^0.9358
|Keypair|<br>
InvoiceTotal^USD
2,930.34^0.655|
5.6683^7.3859x
7.9747^7.3859x
7.9747^7.5004x
5.6683^7.5004
|Keypair|<br>
PaymentDetails^undefined^undefined|
|Keypair|<br>
PaymentTerm^1% 15 Days, Net 60 Days^0.866|
4.7038^1.1738x
5.6408^1.1847x
5.6395^1.2951x
4.7025^1.2842
|Keypair|<br>
PurchaseOrder^Verbal Jessica Davis^0.711|
2.8986^1.1745x
3.7534^1.1745x
3.7534^1.2843x
2.8986^1.2843
|Keypair|<br>
ShippingAddress^[object Object]^0.879|
5.4438^2.3872x
6.7809^2.3872x
6.7809^2.6354x
5.4438^2.6354
|Keypair|<br>
ShippingAddressRecipient^AEROFIL TECHNOLOGY INC^0.915|
5.4438^2.2582x
6.8907^2.2582x
6.8907^2.3967x
5.4438^2.3967
|Keypair|<br>
1. Generator ID Number^MOD981722762^0.796|
1.7191^0.7973x
2.7315^0.783x
2.7315^0.931x
1.7191^0.9262
|Keypair|<br>
2. Page 1 of
3. Emergency Response Phone^800-424-9300^0.74|
4.484^0.7925x
5.4725^0.7925x
5.4772^0.9262x
4.484^0.9262
|Keypair|<br>
4. Manifest Tracking Number^023508992 JJK^0.759|
6.112^0.709x
7.917^0.7057x
7.9174^0.9399x
6.1124^0.9431
|Keypair|<br>
5. Generator's Name and Mailing Address^AEROFIL TECHNOLOGY INC
225 INDUSTRIAL PARK DR
SULLIVAN MO 63080
Phone:73) 468-5551^0.726|
0.5539^1.0456x
2.3829^1.0456x
2.3829^1.5755x
0.5539^1.5755
|Keypair|<br>
ATTN:^JESSICA DAVIS^0.825|
2.8939^1.383x
3.9884^1.3893x
3.9874^1.5627x
2.8929^1.5564
|Keypair|<br>
6. Transporter 1 Company Name^UNIVAR SOLUTIONS USA^0.754|
0.548^1.6729x
2.2157^1.6567x
2.2171^1.7966x
0.5493^1.8129
|Keypair|<br>
7. Transporter 2 Company Name^SCHIBER TRUCK COMPANY INC^0.748|
0.5587^2.0243x
2.6455^2.0243x
2.6455^2.1771x
0.5587^2.1771
|Keypair|<br>
8. Designated Facility Name and Site Address^CLEAN EARTH OF CALVERT CITY LLC
1689 SHAR CAL RD
CALVERT CITY KY
42029-8948^0.711|
0.5539^2.3967x
3.1469^2.3967x
3.1469^2.8168x
0.5539^2.8168
|Keypair|<br>
Facility's Phone:^270-395-0504^0.526|
1.1604^2.7977x
2.1441^2.793x
2.1441^2.9219x
1.1604^2.9219
|Keypair|<br>
Generator's Site Address (if different than mailing address)^AEROFIL TECHNOLOGY INC 225 INDUSTRIAL PARK DR SULLIVAN MO 63080^0.715|
4.484^1.0503x
6.3225^1.0503x
6.3225^1.4275x
4.484^1.4275
|Keypair|<br>
U.S. EPA ID Number^1X8000084869^0.8|
6.0121^1.6901x
6.9862^1.6519x
6.9862^1.7856x
6.0121^1.7999
|Keypair|<br>
U.S. EPA ID Number^ILD006493191^0.79|
6.0121^2.0386x
7.0006^2.0386x
7.0006^2.158x
6.0169^2.1628
|Keypair|<br>
U.S. EPA ID Number^KYD985073196^0.793|
6.0025^2.4158x
6.991^2.411x
6.991^2.5399x
6.0025^2.5399
|Keypair|<br>
14. Special Handling Instructions and Additional Information^Emergency^0.743|
2.9034^5.2947x
3.3713^5.2899x
3.3666^5.4427x
2.8986^5.4427
|Keypair|<br>
Phone:^Cheatrec 800-424-9300 CC#1811; 14437614(10,20)^0.173|
3.7534^5.2804x
6.0742^5.2804x
6.0742^5.4427x
3.7534^5.4427
|Keypair|<br>
Generator's/Offeror's Printed/Typed Name^Claude RIFICE^0.796|
0.664^6.5679x
2.4928^6.5802x
2.4913^6.8014x
0.6625^6.7891
|Keypair|<br>
Signature^undefined^0.487|
|Keypair|<br>
Month Day Year^undefined^0.572|
|Keypair|<br>
Import to U.S.^:unselected:^0.814|
1.8893^6.8071x
2.0382^6.8071x
2.0382^6.9557x
1.8893^6.9557
|Keypair|<br>
Export from U.S.^:unselected:^0.814|
3.7878^6.8071x
3.9431^6.8071x
3.9431^6.962x
3.7878^6.962
|Keypair|<br>
Port of entry/exit:^undefined^0.574|
|Keypair|<br>
Transporter signature (for exports only):^undefined^0.588|
|Keypair|<br>
Date leaving U.S .:^undefined^0.589|
|Keypair|<br>
Transporter 1 Printed/Typed Name^Matt Sheppard^0.77|
1.0139^7.3793x
2.8954^7.3907x
2.8938^7.658x
1.0123^7.6466
|Keypair|<br>
Signature^undefined^0.487|
|Keypair|<br>
Month Day Year^14 11/26^0.802|
7.1247^7.4208x
8.1492^7.4347x
8.1465^7.64x
7.122^7.6262
|Keypair|<br>
Transporter 2 Printed/Typed Name^undefined^0.589|
|Keypair|<br>
Signature^undefined^0.485|
|Keypair|<br>
Month Day Year^undefined^0.565|
|Keypair|<br>
Quantity^:unselected:^0.838|
1.9829^8.1592x
2.1368^8.1592x
2.1368^8.3116x
1.9829^8.3116
|Keypair|<br>
Type^:unselected:^0.838|
3.3932^8.1592x
3.5497^8.1592x
3.5497^8.3154x
3.3932^8.3154
|Keypair|<br>
Residue^:unselected:^0.838|
4.6809^8.1566x
4.8399^8.1566x
4.8399^8.3154x
4.6809^8.3154
|Keypair|<br>
Partial Rejection^:unselected:^0.825|
5.885^8.1592x
6.0415^8.1592x
6.0415^8.3141x
5.885^8.3141
|Keypair|<br>
Full Rejection^:unselected:^0.825|
7.2777^8.1617x
7.4316^8.1617x
7.4316^8.3154x
7.2777^8.3154
|Keypair|<br>
18b. Alternate Facility (or Generator)^undefined^0.587|
|Keypair|<br>
Manifest^undefined^0.17|
|Keypair|<br>
U.S. EPA ID Number^undefined^0.589|
|Keypair|<br>
Facility's Phone:^undefined^0.508|
|Keypair|<br>
18c. Signature of Alternate Facility (or Generator)^undefined^0.584|
|Keypair|<br>
Month Day
Year^undefined^0.557|
|Keypair|<br>
19. Hazardous Waste Report Management Method Codes (i.e., codes for hazardous waste treatment, disposal, and recycling systems)^1.
4.^0.254|
0.5644^9.3854x
6.2499^9.6397x
6.2346^9.9819x
0.5491^9.7276
|Keypair|<br>
20. Designated Facility Owner or Operator: Certification of receipt of hazardous materials covered by the manifest except as nded in Item 18a^undefined^0.332|
|Keypair|<br>
Printed/Typed Name^undefined^0.257|
|Keypair|<br>
Signature^undefined^0.165|
|Keypair|<br>
Month
Day
Year^undefined^0.539|
|Keypair|<br>
Customer:^AEROFIL TECHNOLOGY INC 225 INDUSTRIAL PARK^0.216|
1.614^1.0981x
2.7601^1.0981x
2.7601^1.5851x
1.614^1.5851
|Keypair|<br>
Manifest Number :^023508992JJK^0.683|
6.3129^1.3559x
7.2871^1.3607x
7.2871^1.48x
6.3129^1.48
|Keypair|<br>
Customer Signature:^Clanc Pipe^0.899|
2.4201^9.1653x
3.7381^9.181x
3.7342^9.5075x
2.4162^9.4918
|Keypair|<br>
Date:^4-11-24^0.927|
1.2034^9.52x
1.8671^9.5009x
1.8671^9.6775x
1.2034^9.6727
|Keypair|<br>
Invoice Number^52048412^0.885|
2.8986^0.826x
3.3904^0.826x
3.3904^0.9358x
2.9034^0.9358
|Keypair|<br>
P.O.Number^Verbal Jessica Davis^0.862|
2.8986^1.1745x
3.7534^1.1745x
3.7534^1.2843x
2.8986^1.2843
|Keypair|<br>
Release Number^undefined^0.682|
|Keypair|<br>
Billing address^KDC ONE
225 INDUSTRIAL PARK DR
SULLIVAN MO 63080^0.807|
1.2463^2.2678x
2.6132^2.2743x
2.6115^2.637x
1.2446^2.6305
|Keypair|<br>
Shipped From^SAINT LOUIS POLK ST PLANT PKG^0.825|
2.9272^1.5135x
4.2787^1.5135x
4.2787^1.6233x
2.9272^1.6233
|Keypair|<br>
Original Invoice Number^52048412^0.862|
2.9081^1.8477x
3.3952^1.8477x
3.3952^1.9622x
2.9081^1.9622
|Keypair|<br>
Invoice Date^30 Apr 2024^0.856|
4.675^0.8212x
5.2767^0.8212x
5.2767^0.9501x
4.675^0.9501
|Keypair|<br>
Payment Terms^1% 15 Days, Net 60 Days^0.825|
4.7038^1.1738x
5.6408^1.1847x
5.6395^1.2951x
4.7025^1.2842
|Keypair|<br>
Sales Order Num^14437614^0.862|
4.6654^1.5039x
5.1382^1.5087x
5.1382^1.6185x
4.6702^1.6137
|Keypair|<br>
Incoterms :^SULLIVAN MO^0.267|
4.6893^1.862x
5.2671^1.862x
5.2671^1.9622x
4.6893^1.9622
|Keypair|<br>
Due Date^29 Jun 2024^0.856|
6.6758^0.826x
7.2823^0.826x
7.2823^0.9358x
6.6758^0.9358
|Keypair|<br>
Payer Number^988501^0.885|
6.6758^1.1745x
7.034^1.1649x
7.034^1.2795x
6.6806^1.2747
|Keypair|<br>
Bill-To Number^988502^0.884|
6.6758^1.5087x
7.0388^1.5135x
7.0388^1.6185x
6.6758^1.6137
|Keypair|<br>
Ship-To Number^472303^0.885|
6.6806^1.8572x
7.0388^1.8524x
7.0435^1.9575x
6.6806^1.9527
|Keypair|<br>
Shipping address^AEROFIL TECHNOLOGY INC
225 INDUSTRIAL PARK DR
SULLIVAN MO 63080^0.796|
5.4438^2.2582x
6.8907^2.2582x
6.8907^2.6354x
5.4438^2.6354
|Keypair|<br>
Env Charge^436.43^0.878|
7.6452^5.6528x
7.9795^5.648x
7.9795^5.7578x
7.6452^5.7578
|Keypair|<br>
contact^undefined^0.277|
|Keypair|<br>
payment terms^undefined^0.173|
|Keypair|<br>
Invoice Total :^2,930.34^0.526|
7.5497^7.3954x
7.9747^7.3906x
7.9747^7.5004x
7.5545^7.5004
|Keypair|<br>
Sign in or register on^www.univarsolutions.com/invoices^0.832|
0.3629^8.1593x
1.5949^8.1641x
1.5949^8.2595x
0.3629^8.2643
|Keypair|<br>
Remit to^62190 Collections Center Drive Chicago IL 60693-0621^0.819|
0.4011^8.398x
1.5376^8.398x
1.5376^8.6319x
0.4011^8.6319
|Keypair|<br>
DOM. WIRES:^026009593^0.882|
5.4008^8.398x
5.8115^8.3932x
5.8115^8.4935x
5.4008^8.4935
|Keypair|<br>
SWIFT Code INTL. WIRES:^BOFAUS3N^0.855|
5.2767^8.5174x
5.6969^8.5174x
5.6969^8.6128x
5.2767^8.6128
|Keypair|<br>
Comments:^Federal ID number 91-1347935^0.856|
0.3916^9.7969x
1.9531^9.7969x
1.9531^9.921x
0.3916^9.921
|Keypair|<br>
at^http://www.univarsolutions.com/sales-terms^0.414|
2.8938^9.9115x
4.9997^9.9115x
4.9997^10.0404x
2.8938^10.0499
|Keypair|<br>
Print date^01 May 2024^0.856|
7.5497^10.1502x
8.032^10.1502x
8.032^10.2504x
7.5497^10.2504
|Keypair|<br>
visit us at^www.univarsolutions.com^0.861|
6.0073^10.4271x
7.3157^10.4366x
7.3157^10.556x
6.0073^10.556
|Keypair|<br>
1. Generator ID Number^MOD981722762^0.741|
1.6713^0.7734x
2.698^0.7687x
2.698^0.9262x
1.6713^0.9167
|Keypair|<br>
2. Page 1 of^1^0.741|
3.973^0.7878x
4.0256^0.7878x
4.0303^0.8976x
3.973^0.9023
|Keypair|<br>
3. Emergency Response Phone^800-424-9300^0.741|
4.4601^0.7734x
5.4438^0.7734x
5.4438^0.9167x
4.4601^0.9214
|Keypair|<br>
4. Manifest Tracking Number^023508992 JJK^0.722|
6.0793^0.6946x
7.913^0.6979x
7.9126^0.9367x
6.0789^0.9334
|Keypair|<br>
5. Generator's Name and Mailing Address^AEROFIL TECHNOLOGY INC 225 INDUSTRIAL PARK DR SULLIVAN MO 63080 (573) 468-5551^0.691|
0.511^1.0265x
2.3447^1.0265x
2.3447^1.5421x
0.511^1.5421
|Keypair|<br>
ATTN:^JESSICA DAVIS^0.778|
2.8413^1.3798x
3.9444^1.3798x
3.9444^1.5421x
2.8413^1.5421
|Keypair|<br>
Generator's Phone!^R'SOPONIONS USA^0.742|
0.9401^1.5633x
2.2052^1.6324x
2.1923^1.8683x
0.9272^1.7992
|Keypair|<br>
Generator's Site Address (if different than mailing address)^AEROFIL TECHNOLOGY INC 225 INDUSTRIAL PARK DR SULLIVAN MO 63080^0.684|
4.4511^1.0335x
6.2747^1.0402x
6.2733^1.4247x
4.4497^1.418
|Keypair|<br>
U.S. EPAJP Number1^869^0.745|
6.7236^1.628x
6.9624^1.6328x
6.9624^1.7856x
6.7236^1.7713
|Keypair|<br>
7. Transporter 2 Company Name^SCHIBER TRUCK COMPANY INC^0.718|
0.5109^2.0026x
2.6073^1.9944x
2.6079^2.158x
0.5116^2.1662
|Keypair|<br>
8. Designated Facility Name and Site Address^CLEAN EARTH OF CALVERT CITY LLC
1689 SHAR CAL RD
CALVERT CITY KY 42029-8948
Phone:270-395-0504^0.421|
0.5157^2.3776x
3.1039^2.3776x
3.1039^2.9123x
0.5157^2.9123
|Keypair|<br>
U.S. EPA ID Number,^IL0006493191^0.739|
5.9643^2.0243x
6.9719^2.01x
6.9719^2.1484x
5.9643^2.158
|Keypair|<br>
U.S. EPA ID Number^KYD985073198^0.74|
5.95^2.3967x
6.9671^2.3872x
6.9671^2.5304x
5.9548^2.5256
|Keypair|<br>
14. Special Handling Instructions and Additional Information^Emergency Phone: Cheatrec 800-424-9300 CC#1811; 14437614(10,20)^0.689|
2.8508^5.2661x
6.0312^5.2661x
6.0312^5.4236x
2.8508^5.4236
|Keypair|<br>
Generator's/Offeror's Printed/Typed Name^Claude Ripley^0.742|
0.6494^6.5539x
2.4552^6.5599x
2.4545^6.776x
0.6487^6.77
|Keypair|<br>
Transporter signature (for exports only):^undefined^0.576|
|Keypair|<br>
Import to U.S.^:unselected:^0.751|
1.8716^6.8032x
2.0205^6.8032x
2.0205^6.9506x
1.8716^6.9506
|Keypair|<br>
Signature^Cleve Riper^0.778|
4.2597^6.516x
5.5367^6.5265x
5.5346^6.7901x
4.2576^6.7796
|Keypair|<br>
Export from U.S.^:unselected:^0.751|
3.7676^6.8045x
3.9216^6.8045x
3.9216^6.957x
3.7676^6.957
|Keypair|<br>
Transporter 1 Printed/Typed Name^Matt Sheppard^0.739|
0.9778^7.3408x
2.8461^7.3333x
2.8473^7.6246x
0.979^7.632
|Keypair|<br>
Transporter 2 Printed/Typed Name^WAYNE
Parken^0.739|
0.9588^7.7067x
3.4133^7.6962x
3.4143^7.9387x
0.9598^7.9492
|Keypair|<br>
Quantity^:unselected:^0.452|
1.9702^8.1528x
2.1229^8.1528x
2.1229^8.3039x
1.9702^8.3039
|Keypair|<br>
Port of entry/exit:^undefined^0.585|
|Keypair|<br>
Date leaving U.S .:^undefined^0.584|
|Keypair|<br>
Signature^undefined^0.475|
|Keypair|<br>
Signature^Wayne Parker^0.778|
4.6477^7.6128x
6.8203^7.5911x
6.824^7.9545x
4.6514^7.9762
|Keypair|<br>
Type^:unselected:^0.79|
3.3679^8.1528x
3.5219^8.1528x
3.5219^8.3065x
3.3679^8.3065
|Keypair|<br>
AcoAl^undefined^0.118|
|Keypair|<br>
Residue^:unselected:^0.79|
4.6745^8.1579x
4.8285^8.1579x
4.8285^8.3103x
4.6745^8.3103
|Keypair|<br>
Partial Rejection^:unselected:^0.776|
5.8661^8.1592x
6.0238^8.1592x
6.0238^8.3141x
5.8661^8.3141
|Keypair|<br>
Month Day Year^61/11/24^0.751|
7.1916^6.579x
8.0798^6.5933x
8.0798^6.7843x
7.1916^6.7604
|Keypair|<br>
Month
Day Year^41120^0.751|
7.2107^7.4193x
8.118^7.4193x
8.118^7.6246x
7.2154^7.6246
|Keypair|<br>
Month
'Day
Year^41524^0.749|
7.2202^7.7392x
8.1419^7.7392x
8.1371^7.9588x
7.2202^7.9444
|Keypair|<br>
Full Rejection^:unselected:^0.776|
7.2764^8.1655x
7.4291^8.1655x
7.4291^8.3167x
7.2764^8.3167
|Keypair|<br>
Manifest Reference Number:^Ollivan, Mc.^0.742|
5.6833^8.4197x
7.1056^8.3885x
7.11^8.5871x
5.6876^8.6183
|Keypair|<br>
18b. Alternate Facility (or Generator)^undefined^0.541|
|Keypair|<br>
U.S. EPA ID Number^1^0.739|
5.864^8.9184x
5.9213^8.9184x
5.9261^9.138x
5.8688^9.138
|Keypair|<br>
Facility's Phone:^undefined^0.527|
|Keypair|<br>
18c. Signature of Alternate Facility (or Generator)^Polk St^0.335|
5.8067^9.3436x
6.8907^9.3481x
6.8897^9.6009x
5.8057^9.5964
|Keypair|<br>
Month Day
Year^undefined^0.585|
|Keypair|<br>
19. Hazardous Waste Report Management Method Codes (i.e., codes for hazardous waste treatment, disposal, and recycling systems)^H141
#141^0.675|
0.9241^9.6766x
3.1083^9.6564x
3.1107^9.921x
0.9266^9.9412
|Keypair|<br>
Printed/Typed Name^Died Tooshice^0.75|
0.9071^10.2346x
2.402^10.217x
2.4046^10.4365x
0.9097^10.4541
|Keypair|<br>
Signature^undefined^0.474|
|Keypair|<br>
Month
Day
Year^11/16/24^0.75|
7.2393^10.26x
8.0798^10.2695x
8.0798^10.4557x
7.2393^10.4319
|Keypair|<br>
|Delimiter|
<table border=1>
<tr>
<td>UNIFORM HAZARDOUS^undefined|
0.4966^0.592x
1.6296^0.592x
1.6296^0.7512x
0.4966^0.7512
</td>
<td>1. Generator ID Number^undefined|
1.6296^0.592x
3.3252^0.592x
3.3252^0.7512x
1.6296^0.7512
</td>
<td>2. Page 1 of^undefined|
3.3252^0.592x
4.4106^0.592x
4.4106^0.7512x
3.3252^0.7512
</td>
<td>3. Emergency Response Phone^undefined|
4.4106^0.592x
5.7972^0.6x
5.7972^0.7512x
4.4106^0.7512
</td>
<td>4. Manifest Tracking Number^undefined|
5.7972^0.6x
8.0949^0.6x
8.0949^0.7512x
5.7972^0.7512
</td>
<tr>
<td>WASTE MANIFEST^undefined|
0.4966^0.7512x
1.6296^0.7512x
1.6296^0.9262x
0.4966^0.9262
</td>
<td>MOD981722762^undefined|
1.6296^0.7512x
3.3252^0.7512x
3.3252^0.9262x
1.6296^0.9262
</td>
<td>1^undefined|
3.3252^0.7512x
4.4106^0.7512x
4.4106^0.9262x
3.3252^0.9262
</td>
<td>800-424-9300^undefined|
4.4106^0.7512x
5.7972^0.7512x
5.7972^0.9342x
4.4106^0.9262
</td>
<td>023508992 JJK^undefined|
5.7972^0.7512x
8.0949^0.7512x
8.0949^0.9342x
5.7972^0.9342
</td>
</table><br>
<table border=1>
<tr>
<td>-
GENERATOR^undefined|
0.3276^2.9184x
0.5024^2.9184x
0.5024^3.7768x
0.3356^3.7768
</td>
<td>9a. HM^undefined|
0.5024^2.9184x
0.7647^2.9264x
0.7647^3.2602x
0.5024^3.2602
</td>
<td>9b. U.S. DOT Description (including Proper Shipping Name, Hazard Class, ID Number, and Packing Group (if any))^undefined|
0.7647^2.9264x
4.7299^2.9343x
4.7299^3.2681x
0.7647^3.2602
</td>
<td>10. Containers^undefined|
4.7299^2.9343x
5.8027^2.9343x
5.8027^3.1092x
4.7299^3.1092
</td>
<td>11. Total Quantity^undefined|
5.8027^2.9343x
6.4463^2.9343x
6.4463^3.2681x
5.8027^3.2681
</td>
<td>12. Unit Wt./Vol.^undefined|
6.4463^2.9343x
6.8516^2.9343x
6.8516^3.2681x
6.4463^3.2681
</td>
<td>13. Waste Codes^undefined|
6.8516^2.9343x
8.1469^2.9423x
8.1469^3.2681x
6.8516^3.2681
</td>
<td>No.^undefined|
4.7299^3.1092x
5.3497^3.1092x
5.3497^3.2681x
4.7299^3.2681
</td>
<td>Type^undefined|
5.3497^3.1092x
5.8027^3.1092x
5.8027^3.2681x
5.3497^3.2681
</td>
<td>X
:unselected:^undefined|
0.5024^3.2602x
0.7647^3.2602x
0.7647^3.7768x
0.5024^3.7768
</td>
<td>UN1950, WASTE Aerosols, flammable, 2.1, EQ (D001), ERG 126, 169-8531, MIXED AEROSOLS, ALSINGT-34436^undefined|
0.7647^3.2602x
4.7299^3.2681x
4.7299^3.7847x
0.7647^3.7768
</td>
<td>4^undefined|
4.7299^3.2681x
5.3497^3.2681x
5.3497^3.7847x
4.7299^3.7847
</td>
<td>DM^undefined|
5.3497^3.2681x
5.8027^3.2681x
5.8027^3.7847x
5.3497^3.7847
</td>
<td>^undefined|
5.8027^3.2681x
6.4463^3.2681x
6.4463^3.4907x
5.8027^3.4907
</td>
<td>^undefined|
6.4463^3.2681x
6.8516^3.2681x
6.8516^3.4907x
6.4463^3.4907
</td>
<td>D001^undefined|
6.8516^3.2681x
7.2807^3.2681x
7.2807^3.4907x
6.8516^3.4907
</td>
<td>D035^undefined|
7.2807^3.2681x
7.7257^3.2681x
7.7257^3.4907x
7.2807^3.4907
</td>
<td>^undefined|
7.7257^3.2681x
8.1469^3.2681x
8.1548^3.4907x
7.7257^3.4907
</td>
<td>1157^undefined|
5.8027^3.4907x
6.4463^3.4907x
6.4463^3.7847x
5.8027^3.7847
</td>
<td>P^undefined|
6.4463^3.4907x
6.8516^3.4907x
6.8516^3.7847x
6.4463^3.7847
</td>
<td>^undefined|
6.8516^3.4907x
7.2807^3.4907x
7.2807^3.7847x
6.8516^3.7847
</td>
<td>^undefined|
7.2807^3.4907x
7.7257^3.4907x
7.7257^3.7847x
7.2807^3.7847
</td>
<td>^undefined|
7.7257^3.4907x
8.1548^3.4907x
8.1548^3.7847x
7.7257^3.7847
</td>
<tr>
<td>-^undefined|
0.3356^3.7768x
0.5024^3.7768x
0.5024^4.2616x
0.3356^4.2616
</td>
<td>X
:selected:^undefined|
0.5024^3.7768x
0.7647^3.7768x
0.7647^4.2616x
0.5024^4.2616
</td>
<td>2. UN1993, WASTE FLAMMABLE LIQUID, W.O.S. (MIXED XYLENES, ACETOXE), 3, II, RQ (D001), EEG 128, 169-8655, HOMAX OIL BASED TEXTURE, ARSENGT-39869^undefined|
0.7647^3.7768x
4.7299^3.7847x
4.7299^4.2695x
0.7647^4.2616
</td>
<td>8^undefined|
4.7299^3.7847x
5.3497^3.7847x
5.3497^4.2616x
4.7299^4.2695
</td>
<td>DM^undefined|
5.3497^3.7847x
5.8027^3.7847x
5.8027^4.2695x
5.3497^4.2616
</td>
<td>^undefined|
5.8027^3.7847x
6.4463^3.7847x
6.4463^3.9755x
5.8027^3.9755
</td>
<td>^undefined|
6.4463^3.7847x
6.8516^3.7847x
6.8516^3.9755x
6.4463^3.9755
</td>
<td>D001^undefined|
6.8516^3.7847x
7.2807^3.7847x
7.2807^3.9834x
6.8516^3.9755
</td>
<td>^undefined|
7.2807^3.7847x
7.7257^3.7847x
7.7257^3.9834x
7.2807^3.9834
</td>
<td>^undefined|
7.7257^3.7847x
8.1548^3.7847x
8.1548^3.9834x
7.7257^3.9834
</td>
<td>36751^undefined|
5.8027^3.9755x
6.4463^3.9755x
6.4463^4.2695x
5.8027^4.2695
</td>
<td>P^undefined|
6.4463^3.9755x
6.8516^3.9755x
6.8516^4.2695x
6.4463^4.2695
</td>
<td>^undefined|
6.8516^3.9755x
7.2807^3.9834x
7.2807^4.0788x
6.8516^4.0788
</td>
<td>^undefined|
7.2807^3.9834x
7.7257^3.9834x
7.7257^4.0788x
7.2807^4.0788
</td>
<td>^undefined|
7.7257^3.9834x
8.1548^3.9834x
8.1548^4.2695x
7.7257^4.2695
</td>
<td>^undefined|
6.8516^4.0788x
7.2807^4.0788x
7.2807^4.2695x
6.8516^4.2695
</td>
<td>^undefined|
7.2807^4.0788x
7.7257^4.0788x
7.7257^4.2695x
7.2807^4.2695
</td>
<tr>
<td>^undefined|
0.3356^4.2616x
0.5024^4.2616x
0.4945^5.2709x
0.3356^5.2709
</td>
<td>:unselected:^undefined|
0.5024^4.2616x
0.7647^4.2616x
0.7647^4.7702x
0.4945^4.7702
</td>
<td>3.^undefined|
0.7647^4.2616x
4.7299^4.2695x
4.7299^4.7702x
0.7647^4.7702
</td>
<td>^undefined|
4.7299^4.2695x
5.3497^4.2616x
5.3497^4.7702x
4.7299^4.7702
</td>
<td>^undefined|
5.3497^4.2616x
5.8027^4.2695x
5.8106^4.7702x
5.3497^4.7702
</td>
<td>^undefined|
5.8027^4.2695x
6.4463^4.2695x
6.4463^4.7702x
5.8106^4.7702
</td>
<td>^undefined|
6.4463^4.2695x
6.8516^4.2695x
6.8516^4.7702x
6.4463^4.7702
</td>
<td>^undefined|
6.8516^4.2695x
7.2807^4.2695x
7.2886^4.7702x
6.8516^4.7702
</td>
<td>^undefined|
7.2807^4.2695x
7.7257^4.2695x
7.7336^4.7702x
7.2886^4.7702
</td>
<td>^undefined|
7.7257^4.2695x
8.1548^4.2695x
8.1548^4.7782x
7.7336^4.7702
</td>
<td>:unselected:^undefined|
0.4945^4.7702x
0.7647^4.7702x
0.7647^5.2709x
0.4945^5.2709
</td>
<td>4.^undefined|
0.7647^4.7702x
4.7299^4.7702x
4.7299^5.2709x
0.7647^5.2709
</td>
<td>^undefined|
4.7299^4.7702x
5.3497^4.7702x
5.3497^5.2709x
4.7299^5.2709
</td>
<td>^undefined|
5.3497^4.7702x
5.8106^4.7702x
5.8106^5.2709x
5.3497^5.2709
</td>
<td>^undefined|
5.8106^4.7702x
6.4463^4.7702x
6.4543^5.2789x
5.8106^5.2709
</td>
<td>^undefined|
6.4463^4.7702x
6.8516^4.7702x
6.8516^5.2789x
6.4543^5.2789
</td>
<td>^undefined|
6.8516^4.7702x
7.2886^4.7702x
7.2886^5.2789x
6.8516^5.2789
</td>
<td>^undefined|
7.2886^4.7702x
7.7336^4.7702x
7.7336^5.2789x
7.2886^5.2789
</td>
<td>^undefined|
7.7336^4.7702x
8.1548^4.7782x
8.1548^5.2789x
7.7336^5.2789
</td>
</table><br>
<table border=1>
<tr>
<td>^undefined|
0.3448^6.4338x
0.5035^6.4338x
0.5035^6.5768x
0.3448^6.5768
</td>
<td>Generator's/Offeror's Printed/Typed Name Claude RIFICE^undefined|
0.5035^6.4338x
3.2956^6.4418x
3.2956^6.7754x
0.5035^6.7674
</td>
<td>Signature^undefined|
3.2956^6.4418x
6.9525^6.4497x
6.9525^6.5927x
3.2956^6.5847
</td>
<td>Month Day Year^undefined|
6.9525^6.4497x
8.1661^6.4497x
8.1661^6.5927x
6.9525^6.5927
</td>
<tr>
<td>^undefined|
0.3448^6.5768x
0.5035^6.5768x
0.5035^6.7674x
0.3448^6.7674
</td>
<td>^undefined|
3.2956^6.5847x
6.9525^6.5927x
6.9525^6.7833x
3.2956^6.7754
</td>
<td>^undefined|
6.9525^6.5927x
8.1661^6.5927x
8.1661^6.7833x
6.9525^6.7833
</td>
<tr>
<td>INT'L^undefined|
0.3448^6.7674x
0.5035^6.7674x
0.5035^6.974x
0.3448^6.974
</td>
<td>16. International Shipments :unselected: Import to U.S.^undefined|
0.5035^6.7674x
3.2956^6.7754x
3.2956^6.974x
0.5035^6.974
</td>
<td>:unselected: Export from U.S. Port of entry/exit:^undefined|
3.2956^6.7754x
6.9525^6.7833x
6.9525^6.974x
3.2956^6.974
</td>
<td>^undefined|
6.9525^6.7833x
8.1661^6.7833x
8.1661^6.974x
6.9525^6.974
</td>
<tr>
<td>^undefined|
0.3448^6.974x
0.5035^6.974x
0.5035^7.109x
0.3448^7.109
</td>
<td>Transporter signature (for exports only):^undefined|
0.5035^6.974x
3.2956^6.974x
3.2956^7.109x
0.5035^7.109
</td>
<td>Date leaving U.S .:^undefined|
3.2956^6.974x
6.9525^6.974x
6.9525^7.1169x
3.2956^7.109
</td>
<td>^undefined|
6.9525^6.974x
8.1661^6.974x
8.1661^7.1169x
6.9525^7.1169
</td>
<tr>
<td>TRANSPORTER^undefined|
0.3448^7.109x
0.5035^7.109x
0.5035^7.6015x
0.3448^7.6015
</td>
<td>17. Transporter Acknowledgment of Receipt of Materials^undefined|
0.5035^7.109x
3.2956^7.109x
3.2956^7.2838x
0.5035^7.2838
</td>
<td>^undefined|
3.2956^7.109x
6.9525^7.1169x
6.9525^7.2917x
3.2956^7.2838
</td>
<td>^undefined|
6.9525^7.1169x
8.1661^7.1169x
8.1661^7.2917x
6.9525^7.2917
</td>
<td>Transporter 1 Printed/Typed Name^undefined|
0.5035^7.2838x
3.2956^7.2838x
3.2956^7.4267x
0.5035^7.4267
</td>
<td>Signature^undefined|
3.2956^7.2838x
6.9525^7.2917x
6.9525^7.4347x
3.2956^7.4267
</td>
<td>Month Day Year^undefined|
6.9525^7.2917x
8.1661^7.2917x
8.1661^7.4347x
6.9525^7.4347
</td>
<td>Matt Sheppard^undefined|
0.5035^7.4267x
3.2956^7.4267x
3.2956^7.6015x
0.5035^7.6015
</td>
<td>^undefined|
3.2956^7.4267x
6.9525^7.4347x
6.9525^7.6094x
3.2956^7.6015
</td>
<td>14 11/26^undefined|
6.9525^7.4347x
8.1661^7.4347x
8.1661^7.6174x
6.9525^7.6094
</td>
<tr>
<td>^undefined|
0.3448^7.6015x
0.5035^7.6015x
0.4955^7.951x
0.3448^7.951
</td>
<td>Transporter 2 Printed/Typed Name^undefined|
0.5035^7.6015x
3.2956^7.6015x
3.2956^7.951x
0.4955^7.951
</td>
<td>Signature^undefined|
3.2956^7.6015x
6.9525^7.6094x
6.9525^7.9589x
3.2956^7.951
</td>
<td>Month Day Year^undefined|
6.9525^7.6094x
8.1661^7.6174x
8.1661^7.9589x
6.9525^7.9589
</td>
</table><br>
<table border=1>
<tr>
<td>Line Item^undefined|
0.69^1.6731x
1.2344^1.6731x
1.2344^1.995x
0.69^1.995
</td>
<td>Waste Codes^undefined|
1.2344^1.6731x
2.1011^1.6731x
2.1011^1.995x
1.2344^1.995
</td>
<td>Waste Code Sub-Category^undefined|
2.1011^1.6731x
4.823^1.6731x
4.823^1.995x
2.1011^1.995
</td>
<td>WW / NWW^undefined|
4.823^1.6731x
5.8473^1.6731x
5.8473^1.995x
4.823^1.995
</td>
<td>UHC'S^undefined|
5.8473^1.6731x
7.1295^1.6731x
7.1295^1.995x
5.8473^1.995
</td>
<tr>
<td>1^undefined|
0.69^1.995x
1.2344^1.995x
1.2272^2.6603x
0.6829^2.6531
</td>
<td>D001 D035^undefined|
1.2344^1.995x
2.1011^1.995x
2.1011^2.6674x
1.2272^2.6603
</td>
<td>High TOC Ignitable Characteristic Liquids subcategory based on 40 CFR 261.21 (a) (1) -greater than or equal to 10% TOC^undefined|
2.1011^1.995x
4.823^1.995x
4.823^2.6674x
2.1011^2.6674
</td>
<td>NWW^undefined|
4.823^1.995x
5.8473^1.995x
5.8473^2.6674x
4.823^2.6674
</td>
<td>^undefined|
5.8473^1.995x
7.1295^1.995x
7.1295^2.6674x
5.8473^2.6674
</td>
<tr>
<td>2^undefined|
0.6829^2.6531x
1.2272^2.6603x
1.2272^3.3684x
0.6757^3.3684
</td>
<td>D001^undefined|
1.2272^2.6603x
2.1011^2.6674x
2.1011^3.3684x
1.2272^3.3684
</td>
<td>High TOC Ignitable Characteristic Liquids subcategory based on 40 CFR 261.21 (a) (1) -greater than or equal to 10% TOC^undefined|
2.1011^2.6674x
4.823^2.6674x
4.823^3.3756x
2.1011^3.3684
</td>
<td>NWW^undefined|
4.823^2.6674x
5.8473^2.6674x
5.8473^3.3756x
4.823^3.3756
</td>
<td>^undefined|
5.8473^2.6674x
7.1295^2.6674x
7.1295^3.3756x
5.8473^3.3756
</td>
</table><br>
<table border=1>
<tr>
<td>Qty.^undefined|
0.351^3.6516x
0.8828^3.6516x
0.8828^3.9609x
0.351^3.9609
</td>
<td>UoM^undefined|
0.8828^3.6516x
1.2559^3.6516x
1.2559^3.9609x
0.8828^3.9609
</td>
<td>Material Number^undefined|
1.2559^3.6516x
2.3353^3.6516x
2.3353^3.9609x
1.2559^3.9609
</td>
<td>Material Description^undefined|
2.3353^3.6516x
4.4386^3.6516x
4.4386^3.9609x
2.3353^3.9609
</td>
<td>Batch Number^undefined|
4.4386^3.6516x
5.2164^3.6516x
5.2164^3.9609x
4.4386^3.9609
</td>
<td>Billing Qty^undefined|
5.2164^3.6516x
5.9784^3.6516x
5.9863^3.9609x
5.2164^3.9609
</td>
<td>UoM^undefined|
5.9784^3.6516x
6.3832^3.6516x
6.3832^3.9609x
5.9863^3.9609
</td>
<td>Unit Price^undefined|
6.3832^3.6516x
7.161^3.6516x
7.169^3.9609x
6.3832^3.9609
</td>
<td>Amount USD^undefined|
7.161^3.6516x
8.042^3.6595x
8.05^3.9609x
7.169^3.9609
</td>
<tr>
<td>4^undefined|
0.351^3.9609x
0.8828^3.9609x
0.8828^4.2385x
0.351^4.2385
</td>
<td>DR^undefined|
0.8828^3.9609x
1.2559^3.9609x
1.2559^4.4765x
0.8828^4.4765
</td>
<td>16090102 Manifest#
Profile#^undefined|
1.2559^3.9609x
2.3353^3.9609x
2.3432^4.4765x
1.2559^4.4765
</td>
<td>023508992JJK
169-8531 MIXED AEROSOLS 55G DR467 MIXED AEROSOLS^undefined|
2.3353^3.9609x
4.4386^3.9609x
4.4386^4.4844x
2.3432^4.4765
</td>
<td>0003490649^undefined|
4.4386^3.9609x
5.2164^3.9609x
5.2164^4.2465x
4.4386^4.2385
</td>
<td>1,157^undefined|
5.2164^3.9609x
5.9863^3.9609x
5.9863^4.4844x
5.2164^4.4844
</td>
<td>LB^undefined|
5.9863^3.9609x
6.3832^3.9609x
6.3832^4.4844x
5.9863^4.4844
</td>
<td>0.8532^undefined|
6.3832^3.9609x
7.169^3.9609x
7.169^4.4844x
6.3832^4.4844
</td>
<td>987.15^undefined|
7.169^3.9609x
8.05^3.9609x
8.05^4.4844x
7.169^4.4844
</td>
<tr>
<td>^undefined|
0.351^4.2385x
0.8828^4.2385x
0.8828^4.4765x
0.351^4.4765
</td>
<td>^undefined|
4.4386^4.2385x
5.2164^4.2465x
5.2164^4.4844x
4.4386^4.4844
</td>
<tr>
<td>^undefined|
0.351^4.4765x
0.8828^4.4765x
0.8828^4.6034x
0.351^4.6034
</td>
<td>^undefined|
0.8828^4.4765x
1.2559^4.4765x
1.2559^4.6034x
0.8828^4.6034
</td>
<td>^undefined|
1.2559^4.4765x
2.3432^4.4765x
2.3432^4.6034x
1.2559^4.6034
</td>
<td>CC Fuel Srch^undefined|
2.3432^4.4765x
4.4386^4.4844x
4.4386^4.6034x
2.3432^4.6034
</td>
<td>^undefined|
4.4386^4.4844x
5.2164^4.4844x
5.2164^4.6113x
4.4386^4.6034
</td>
<td>^undefined|
5.2164^4.4844x
5.9863^4.4844x
5.9863^4.6113x
5.2164^4.6113
</td>
<td>^undefined|
5.9863^4.4844x
6.3832^4.4844x
6.3832^4.6113x
5.9863^4.6113
</td>
<td>9.0000 /DR^undefined|
6.3832^4.4844x
7.169^4.4844x
7.169^4.6113x
6.3832^4.6113
</td>
<td>36.00 V^undefined|
7.169^4.4844x
8.05^4.4844x
8.0579^4.6113x
7.169^4.6113
</td>
<tr>
<td>^undefined|
0.351^4.6034x
0.8828^4.6034x
0.8828^4.7224x
0.351^4.7224
</td>
<td>^undefined|
0.8828^4.6034x
1.2559^4.6034x
1.2559^4.7224x
0.8828^4.7224
</td>
<td>^undefined|
1.2559^4.6034x
2.3432^4.6034x
2.3432^4.7224x
1.2559^4.7224
</td>
<td>Service Date - 11 Apr 2024^undefined|
2.3432^4.6034x
4.4386^4.6034x
4.4386^4.7224x
2.3432^4.7224
</td>
<td>^undefined|
4.4386^4.6034x
5.2164^4.6113x
5.2164^4.7224x
4.4386^4.7224
</td>
<td>^undefined|
5.2164^4.6113x
5.9863^4.6113x
5.9863^4.7224x
5.2164^4.7224
</td>
<td>^undefined|
5.9863^4.6113x
6.3832^4.6113x
6.3832^4.7303x
5.9863^4.7224
</td>
<td>^undefined|
6.3832^4.6113x
7.169^4.6113x
7.169^4.7303x
6.3832^4.7303
</td>
<td>^undefined|
7.169^4.6113x
8.0579^4.6113x
8.0579^4.7303x
7.169^4.7303
</td>
<tr>
<td>8^undefined|
0.351^4.7224x
0.8828^4.7224x
0.8828^4.9683x
0.351^4.9683
</td>
<td>DR^undefined|
0.8828^4.7224x
1.2559^4.7224x
1.2559^5.0872x
0.8828^5.0872
</td>
<td>16090389 Manifest#
Profile#^undefined|
1.2559^4.7224x
2.3432^4.7224x
2.3432^5.0872x
1.2559^5.0872
</td>
<td>023508992JJK
169-8655 LIQUID - THIN LIQUID 55G DR467^undefined|
2.3432^4.7224x
4.4386^4.7224x
4.4386^5.0872x
2.3432^5.0872
</td>
<td>0003490650^undefined|
4.4386^4.7224x
5.2164^4.7224x
5.2164^4.9762x
4.4386^4.9762
</td>
<td>8^undefined|
5.2164^4.7224x
5.9863^4.7224x
5.9863^5.0872x
5.2164^5.0872
</td>
<td>DR^undefined|
5.9863^4.7224x
6.3832^4.7303x
6.3832^5.0952x
5.9863^5.0872
</td>
<td>171.7200^undefined|
6.3832^4.7303x
7.169^4.7303x
7.169^5.0952x
6.3832^5.0952
</td>
<td>1,373.76L^undefined|
7.169^4.7303x
8.0579^4.7303x
8.0579^5.0952x
7.169^5.0952
</td>
<tr>
<td>^undefined|
0.351^4.9683x
0.8828^4.9683x
0.8828^5.0872x
0.351^5.0872
</td>
<td>^undefined|
4.4386^4.9762x
5.2164^4.9762x
5.2164^5.0872x
4.4386^5.0872
</td>
<tr>
<td>^undefined|
0.351^5.0872x
0.8828^5.0872x
0.8828^5.4838x
0.351^5.4918
</td>
<td>^undefined|
0.8828^5.0872x
1.2559^5.0872x
1.2559^5.4838x
0.8828^5.4838
</td>
<td>^undefined|
1.2559^5.0872x
2.3432^5.0872x
2.3432^5.4838x
1.2559^5.4838
</td>
<td>HOMAX OIL BASED TEXTURE CC Fuel Srch Service Date - 11 Apr 2024^undefined|
2.3432^5.0872x
4.4386^5.0872x
4.4386^5.4838x
2.3432^5.4838
</td>
<td>^undefined|
4.4386^5.0872x
5.2164^5.0872x
5.2164^5.4838x
4.4386^5.4838
</td>
<td>^undefined|
5.2164^5.0872x
5.9863^5.0872x
5.9863^5.4838x
5.2164^5.4838
</td>
<td>^undefined|
5.9863^5.0872x
6.3832^5.0952x
6.3832^5.4838x
5.9863^5.4838
</td>
<td>9.0000 /DR^undefined|
6.3832^5.0952x
7.169^5.0952x
7.169^5.4838x
6.3832^5.4838
</td>
<td>72.00
:selected:^undefined|
7.169^5.0952x
8.0579^5.0952x
8.0658^5.4918x
7.169^5.4838
</td>
<tr>
<td>^undefined|
0.351^5.4918x
0.8828^5.4838x
0.8828^5.6425x
0.351^5.6425
</td>
<td>^undefined|
0.8828^5.4838x
1.2559^5.4838x
1.2559^5.6425x
0.8828^5.6425
</td>
<td>^undefined|
1.2559^5.4838x
2.3432^5.4838x
2.3512^5.6425x
1.2559^5.6425
</td>
<td>Manifest Fee^undefined|
2.3432^5.4838x
4.4386^5.4838x
4.4386^5.6425x
2.3512^5.6425
</td>
<td>^undefined|
4.4386^5.4838x
5.2164^5.4838x
5.2244^5.6425x
4.4386^5.6425
</td>
<td>^undefined|
5.2164^5.4838x
5.9863^5.4838x
5.9863^5.6425x
5.2244^5.6425
</td>
<td>^undefined|
5.9863^5.4838x
6.3832^5.4838x
6.3911^5.6425x
5.9863^5.6425
</td>
<td>^undefined|
6.3832^5.4838x
7.169^5.4838x
7.169^5.6425x
6.3911^5.6425
</td>
<td>25.00^undefined|
7.169^5.4838x
8.0658^5.4918x
8.0658^5.6425x
7.169^5.6425
</td>
<tr>
<td>^undefined|
0.351^5.6425x
0.8828^5.6425x
0.8908^5.809x
0.351^5.809
</td>
<td>^undefined|
0.8828^5.6425x
1.2559^5.6425x
1.2559^5.809x
0.8908^5.809
</td>
<td>^undefined|
1.2559^5.6425x
2.3512^5.6425x
2.3512^5.809x
1.2559^5.809
</td>
<td>Env Charge^undefined|
2.3512^5.6425x
4.4386^5.6425x
4.4386^5.809x
2.3512^5.809
</td>
<td>^undefined|
4.4386^5.6425x
5.2244^5.6425x
5.2244^5.809x
4.4386^5.809
</td>
<td>^undefined|
5.2244^5.6425x
5.9863^5.6425x
5.9863^5.809x
5.2244^5.809
</td>
<td>^undefined|
5.9863^5.6425x
6.3911^5.6425x
6.3911^5.809x
5.9863^5.809
</td>
<td>^undefined|
6.3911^5.6425x
7.169^5.6425x
7.169^5.809x
6.3911^5.809
</td>
<td>436.43^undefined|
7.169^5.6425x
8.0658^5.6425x
8.0658^5.809x
7.169^5.809
</td>
</table><br>
<table border=1>
<tr>
<td>^undefined|
0.2907^0.5851x
0.4253^0.5851x
0.4253^0.7439x
0.2986^0.7439
</td>
<td>UNIFORM HAZARDOUS^undefined|
0.4253^0.5851x
1.6131^0.5931x
1.6131^0.7439x
0.4253^0.7439
</td>
<td>1. Generator ID Number^undefined|
1.6131^0.5931x
3.3313^0.5931x
3.3313^0.7439x
1.6131^0.7439
</td>
<td>2. Page 1 of^undefined|
3.3313^0.5931x
4.4003^0.5931x
4.4003^0.7439x
3.3313^0.7439
</td>
<td>3. Emergency Response Phone^undefined|
4.4003^0.5931x
5.7781^0.601x
5.7781^0.7439x
4.4003^0.7439
</td>
<td>4. Manifest Tracking Number^undefined|
5.7781^0.601x
8.0982^0.601x
8.0982^0.7518x
5.7781^0.7439
</td>
<tr>
<td>^undefined|
0.2986^0.7439x
0.4253^0.7439x
0.4332^0.9186x
0.3144^0.9186
</td>
<td>WASTE MANIFEST^undefined|
0.4253^0.7439x
1.6131^0.7439x
1.6131^0.9186x
0.4332^0.9186
</td>
<td>MOD981722762^undefined|
1.6131^0.7439x
3.3313^0.7439x
3.3313^0.9265x
1.6131^0.9186
</td>
<td>1^undefined|
3.3313^0.7439x
4.4003^0.7439x
4.4003^0.9265x
3.3313^0.9265
</td>
<td>800-424-9300^undefined|
4.4003^0.7439x
5.7781^0.7439x
5.7781^0.9344x
4.4003^0.9265
</td>
<td>023508992 JJK^undefined|
5.7781^0.7439x
8.0982^0.7518x
8.0982^0.9424x
5.7781^0.9344
</td>
</table><br>
<table border=1>
<tr>
<td>-
GENERATOR^undefined|
0.3044^2.9127x
0.4718^2.9127x
0.4718^3.7577x
0.3123^3.7577
</td>
<td>9a. HM^undefined|
0.4718^2.9127x
0.735^2.9127x
0.735^3.2555x
0.4718^3.2555
</td>
<td>9b. U.S. DOT Description (including Proper Shipping Name, Hazard Class, ID Number, and Packing Group (if any))^undefined|
0.735^2.9127x
4.7142^2.9287x
4.7142^3.2635x
0.735^3.2555
</td>
<td>10. Containers^undefined|
4.7142^2.9287x
5.7907^2.9287x
5.7907^3.112x
4.7142^3.112
</td>
<td>11. Total Quantity^undefined|
5.7907^2.9287x
6.4287^2.9287x
6.4287^3.2714x
5.7907^3.2714
</td>
<td>12. Unit Wt./Vol.^undefined|
6.4287^2.9287x
6.8274^2.9366x
6.8274^3.2714x
6.4287^3.2714
</td>
<td>13. Waste Codes^undefined|
6.8274^2.9366x
8.1511^2.9366x
8.1511^3.2794x
6.8274^3.2714
</td>
<td>No.^undefined|
4.7142^3.112x
5.3362^3.112x
5.3362^3.2714x
4.7142^3.2635
</td>
<td>Type^undefined|
5.3362^3.112x
5.7907^3.112x
5.7907^3.2714x
5.3362^3.2714
</td>
<td>X
:selected:^undefined|
0.4718^3.2555x
0.735^3.2555x
0.735^3.7577x
0.4718^3.7577
</td>
<td>1. UN1950, WASTE Aerosola, flammable, 2.1, RQ (D001), ERG 126, 169-8531, MIXED AEROSOLS, AESAMGY-34436^undefined|
0.735^3.2555x
4.7142^3.2635x
4.7142^3.7736x
0.735^3.7577
</td>
<td>4^undefined|
4.7142^3.2635x
5.3362^3.2714x
5.3362^3.7736x
4.7142^3.7736
</td>
<td>DM^undefined|
5.3362^3.2714x
5.7907^3.2714x
5.7907^3.7816x
5.3362^3.7736
</td>
<td>1157^undefined|
5.7907^3.2714x
6.4287^3.2714x
6.4287^3.7816x
5.7907^3.7816
</td>
<td>P^undefined|
6.4287^3.2714x
6.8274^3.2714x
6.8274^3.7816x
6.4287^3.7816
</td>
<td>D001^undefined|
6.8274^3.2714x
7.2739^3.2714x
7.2819^3.4707x
6.8274^3.4627
</td>
<td>D035^undefined|
7.2739^3.2714x
7.7205^3.2794x
7.7285^3.4707x
7.2819^3.4707
</td>
<td>^undefined|
7.7205^3.2794x
8.1511^3.2794x
8.1511^3.4707x
7.7285^3.4707
</td>
<td>^undefined|
6.8274^3.4627x
7.2819^3.4707x
7.2819^3.7816x
6.8274^3.7816
</td>
<td>^undefined|
7.2819^3.4707x
7.7285^3.4707x
7.7285^3.7816x
7.2819^3.7816
</td>
<td>^undefined|
7.7285^3.4707x
8.1511^3.4707x
8.1511^3.7816x
7.7285^3.7816
</td>
<tr>
<td>-^undefined|
0.3123^3.7577x
0.4718^3.7577x
0.4798^5.2563x
0.3123^5.2563
</td>
<td>X^undefined|
0.4718^3.7577x
0.735^3.7577x
0.735^4.2519x
0.4798^4.2439
</td>
<td>2. UM1993, MASTE FLAMMABLE LIQUID, W.O.S. (MIXED XYLEBES, ACHTOUE), 3, II, RQ (D001), ERG 128, 169-8655, HOMAX OIL BASED TEXTURE, ARSENGT-39869^undefined|
0.735^3.7577x
4.7142^3.7736x
4.7142^4.2678x
0.735^4.2519
</td>
<td>8^undefined|
4.7142^3.7736x
5.3362^3.7736x
5.3362^4.2678x
4.7142^4.2678
</td>
<td>DM^undefined|
5.3362^3.7736x
5.7907^3.7816x
5.7907^4.2678x
5.3362^4.2678
</td>
<td>3675^undefined|
5.7907^3.7816x
6.4287^3.7816x
6.4287^4.2758x
5.7907^4.2678
</td>
<td>P^undefined|
6.4287^3.7816x
6.8274^3.7816x
6.8274^4.2758x
6.4287^4.2758
</td>
<td>D001^undefined|
6.8274^3.7816x
7.2819^3.7816x
7.2819^3.9888x
6.8274^3.9888
</td>
<td>^undefined|
7.2819^3.7816x
7.7285^3.7816x
7.7285^3.9888x
7.2819^3.9888
</td>
<td>^undefined|
7.7285^3.7816x
8.1511^3.7816x
8.1511^3.9968x
7.7285^3.9888
</td>
<td>^undefined|
6.8274^3.9888x
7.2819^3.9888x
7.2819^4.2758x
6.8274^4.2758
</td>
<td>^undefined|
7.2819^3.9888x
7.7285^3.9888x
7.7285^4.2758x
7.2819^4.2758
</td>
<td>^undefined|
7.7285^3.9888x
8.1511^3.9968x
8.1511^4.2758x
7.7285^4.2758
</td>
<td>^undefined|
0.4798^4.2439x
0.735^4.2519x
0.743^4.7541x
0.4798^4.7461
</td>
<td>3.^undefined|
0.735^4.2519x
4.7142^4.2678x
4.7142^4.77x
0.743^4.7541
</td>
<td>^undefined|
4.7142^4.2678x
5.3362^4.2678x
5.3362^4.77x
4.7142^4.77
</td>
<td>^undefined|
5.3362^4.2678x
5.7907^4.2678x
5.7907^4.77x
5.3362^4.77
</td>
<td>^undefined|
5.7907^4.2678x
6.4287^4.2758x
6.4287^4.778x
5.7907^4.77
</td>
<td>^undefined|
6.4287^4.2758x
6.8274^4.2758x
6.8274^4.778x
6.4287^4.778
</td>
<td>^undefined|
6.8274^4.2758x
7.2819^4.2758x
7.2819^4.778x
6.8274^4.778
</td>
<td>^undefined|
7.2819^4.2758x
7.7285^4.2758x
7.7285^4.778x
7.2819^4.778
</td>
<td>^undefined|
7.7285^4.2758x
8.1511^4.2758x
8.1591^4.778x
7.7285^4.778
</td>
<td>^undefined|
0.4798^4.7461x
0.743^4.7541x
0.743^5.2643x
0.4798^5.2563
</td>
<td>4.^undefined|
0.743^4.7541x
4.7142^4.77x
4.7142^5.2802x
0.743^5.2643
</td>
<td>^undefined|
4.7142^4.77x
5.3362^4.77x
5.3362^5.2802x
4.7142^5.2802
</td>
<td>^undefined|
5.3362^4.77x
5.7907^4.77x
5.7907^5.2802x
5.3362^5.2802
</td>
<td>^undefined|
5.7907^4.77x
6.4287^4.778x
6.4287^5.2882x
5.7907^5.2802
</td>
<td>^undefined|
6.4287^4.778x
6.8274^4.778x
6.8274^5.2882x
6.4287^5.2882
</td>
<td>^undefined|
6.8274^4.778x
7.2819^4.778x
7.2819^5.2882x
6.8274^5.2882
</td>
<td>^undefined|
7.2819^4.778x
7.7285^4.778x
7.7285^5.2882x
7.2819^5.2882
</td>
<td>^undefined|
7.7285^4.778x
8.1591^4.778x
8.1591^5.2882x
7.7285^5.2882
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