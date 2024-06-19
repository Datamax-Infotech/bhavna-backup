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
	</style>
	<link href="css/bootstrap4.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet" />

	<script type="text/javascript" src="scripts/jquery-3.7.1.min.js"></script>
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
$pageNo = isset($coordinates_op[4]) ? $coordinates_op[4] : 1;																							
echo '<option ' . (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" pageNo="' . $pageNo . '" option_val="' . $fields_arr_generic['field_val'] . '">'
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
$pageNo = 1;
																$option_val = "";
																$selected_op = "";
																while ($fields_arr_generic = array_shift($water_ocr_all_generic_fields_dropdown)) {
																	$coordinates_op = explode("|", $fields_arr_generic['coordinates']);
																	if (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name']) {
																		$input_coordinates = $coordinates_op;
																		$option_val = $fields_arr_generic['field_val'];
																		$selected_op = $fields_arr_generic['field_name'];
																		$pageNo = isset($coordinates_op[4]) ? $coordinates_op[4] : 1;
																	}
																	echo '<option ' . (trim($fields_arr_generic['field_name']) == $fields_arr['maaped_field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" pageNo="' . $pageNo . '" option_val="' . $fields_arr_generic['field_val'] . '">'
																		. $fields_arr_generic['field_name']
																		. '</option>';
																}
																?>
															</select>
														</div>
														<div class="col-sm-6">
															<input type="hidden" name="mapped_with[]" value="<?= $fields_arr['uniq']; ?>">
															<input type="hidden" name="fv_id[]" value="<?= $fields_arr['fv_id']; ?>">
															<input type="text" <?= $field_count == 1 && $account_data['account_no'] == 0 ? " readonly " : "" ?> value="<?= $fields_arr['field_value']; ?>" name="field_value[]" id="<?= $field_name_unq; ?>" class="form-control form-control-sm generic_field_input <?= $fields_arr['field_type'] ? "date" : ""; ?>" cor_x="<?php echo $input_coordinates[0]; ?>" cor_y="<?php echo $input_coordinates[1]; ?>" w="<?php echo $input_coordinates[2]; ?>" h="<?php echo $input_coordinates[3]; ?>" pageNo="<?php echo $pageNo; ?>" option_val="<?php echo $option_val; ?>" selected_op="<?php echo $selected_op; ?>" <? echo $req_txt; ?>>
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
	$pageNo = isset($coordinates_op[4]) ? $coordinates_op[4] : 1;
																						if (trim($fields_arr_generic['field_name']) == $no_account_data['field_name']) {
																							$input_coordinates = $coordinates_op;
																							$option_val = $fields_arr_generic['field_val'];
																							$selected_op = $fields_arr_generic['field_name'];
																						}
																						echo '<option ' . (trim($fields_arr_generic['field_name']) == $no_account_data['field_name'] ? "selected" : "") . ' value="' . $fields_arr_generic['field_name'] . '" cor_x = "' . $coordinates_op[0] . '" cor_y = "' . $coordinates_op[1] . '" w="' . $coordinates_op[2] . '"  h="' . $coordinates_op[3] . '" pageNo="' . $pageNo . '" option_val="' . $fields_arr_generic['field_val'] . '">'
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
									<div class="col-md-12 p-0 table-responsive mt-3">
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
																		<select <?php echo $selected_value_array[$index] == "Material/Fee column"? "id='mapping_field_head_$table_no'" : ""; ?> name="table_mapping_field<?php echo $table_no; ?>0[]" class="form-control form-control-sm mt-1 table_mapping_field_head_dp" table_id="<?php echo $table_no; ?>">
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

																	<div id='divrow_txt-<?php echo ($table_no + 2) . '-' . $count1.'-'.$table_no; ?>' class="divrow_txt"><small><?php echo $mapped_data['ocr_selected_text'] ?></small></div>
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
																	$pageNo = isset($coordinates_arr[4]) ? $coordinates_arr[4] : 1;
																?>
																	<td colspan="<?php echo $col_span_val; ?>">
																		<textarea style="resize: both; padding:0px" name="table_mapping_field<?php echo $table_no . "" . $count1; ?>[]" id="row_txt<?php echo $index1 + 1; ?>" class="form-control form-control-sm" onclick="updateCursorPosition(this, <?php echo ($table_no + 2) . ',' . $count1 .','. $table_no; ?>)" onfocus="modifyPdf(<?php echo $coordinates_arr[0] . ',' . $coordinates_arr[1] . ',' . $coordinates_arr[2] . ',' . $coordinates_arr[3].',' . $pageNo; ?>)"><?php echo trim($field_data_arr[$index1]); ?></textarea>
																		<input type="hidden" class="table_mapping_field_cord" name="table_mapping_field_cord<?php echo $table_no . "" . $count1; ?>[]" value="<?php echo $coordinates_arr[0] . '|' . $coordinates_arr[1] . '|' . $coordinates_arr[2] . '|' . $coordinates_arr[3] . '|'. $pageNo; ?>">
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
									//$ocr_file = "Noosa_Republic_service_inv_sample.pdf";
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
											<iframe  id="pdf_frame" src="<?php echo "water_inv_files_PW/" . $ocr_file; ?>"></iframe>
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
					//alert("Data extracted successfully");
					var data = `<?= $mainstr; ?>`;
					//console.log(data);
					var primary_array = data.replace(/\s+/g, ' ').replace().split("|Delimiter|")[0].split("|Keypair|<br> ");
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
							//console.log(position_data_array);
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
							var pageNo = typeof position_data_array[5] !== 'undefined' ? position_data_array[5] : 1;
							//console.log("PageNo "+pageNo);
							var w = (cor_x2 - cor_x).toFixed(4);
							var h = (cor_y - cor_y2).toFixed(4);

							//console.log(`${option_name} : ${cor_x} ${cor_y}  ${w}  ${h}`);
							select_options += `<option value="${option_name}" cor_x="${cor_x}" cor_y="${cor_y}", w="${w}" h="${h}" pageNo="${pageNo}" option_val="${option_val}">${option_name}</option>`;
							all_generic_fields_array.push(`${option_name}||${option_val}||${cor_x}|${cor_y}|${w}|${h}|${pageNo}`);
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
								var table_mapping_field_cnt = 1;
								while ((tdMatch = tdRegex.exec(val)) !== null) {

									const val = tdMatch[1].trim(); // Extracted name						
									total_cols += total_cols;
									total_cols_head = total_cols_head + 1;

									tableData_top_tds += `
									<td><span class="nowrap_word">${val}</span>
										<input type="hidden" name="tbl_column_no-${count}[]" value="${total_cols_head}">
										<input type="hidden" name="tbl_column_text-${count}[]" value="${val}">
										<select name="table_mapping_field${count}${uniq_count2}[]" class="form-control form-control-sm mt-1 table_mapping_field_head_dp" table_id="${count}">
											<option></option>
											<option>Material/Fee column</option>
											<option>Quantity</option>
											<option>Unit Price</option>
											<option>Amount</option>
										</select>
									</td>
								`;
								table_mapping_field_cnt++;
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

								if (colspan_cnt < total_cols_head) {
									colspan_val = total_cols_head - colspan_cnt + 1;
								}

								if (colspan_cnt == 1) {
									colspan_val = "colspan=" + colspan_val;
								}

								var tdcnt = 0;
								//while ((tdMatch = tdRegex.exec(val)) !== null) {
								var text_area_uniq_cnt = 1;
								while ((tdMatch = tdRegex.exec(val))) {
									const val = tdMatch[1].trim(); // Extracted name
									const value = tdMatch[2].trim(); // Extracted cordinates
									// rowData.push({"value" : key});

									var option_name, option_val, cor_x, cor_y, w, h;
									//if (val) {
									tdcnt = tdcnt + 1;
									//console.log(position_data_array);
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
									//console.log(position_data_array[5] + "Pos 5");
									var pageNo = typeof position_data_array[4] !== 'undefined' ? position_data_array[4] : 1;
									table_td_Data_flg = 1;

									//${colspan_val}

									table_td_Data += `
									<td>
										<textarea style="resize: both; padding:0px" name="table_mapping_field${count}${uniq_count2}[]" id="row_txt${count}" class="form-control form-control-sm" 
										onclick="updateCursorPosition(this, ${uniq_count1}, ${uniq_count2},${count})" onfocus="modifyPdf(${cor_x ?? ''}, ${cor_y ?? ''}, ${w ?? ''},${h ?? ''}, ${pageNo})" >${val ?? ''}</textarea>
										
										<input class="table_mapping_field_cord" type="hidden" name="table_mapping_field_cord${count}${uniq_count2}[]" value="${cor_x}|${cor_y}|${w}|${h}|${pageNo}"/>
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
							<tr>
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
									
									<div id='divrow_txt-${uniq_count1}-${uniq_count2}-${count}' style="display:none;" class="divrow_txt"></div>
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
					var pageNo = $('option:selected', this).attr('pageNo');
					$(this).parents(".form-group").find('.generic_field_input ').val(option_val);
					$(this).parents(".form-group").find('.generic_field_input ').attr({
						'selected_op': selected_op,
						'option_val': option_val,
						'cor_x': cor_x,
						'cor_y': cor_y,
						'w': w,
						h,
						pageNo,
					});
					console.log("Page No 3538 "+pageNo);
					modifyPdf(cor_x, cor_y, w, h, pageNo);
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
						var pageNo = $(this).attr('pageNo');
						var option_val = $(this).attr('option_val');
						var selected_op = $(this).attr('selected_op');
						modifyPdf(cor_x, cor_y, w, h, pageNo);
					}
				});

				

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
					let unque_count = parseInt(unque_keys[3]);
					console.log(unque_number1 + "- " + unque_number2);
					clonedRow.find('.divrow_txt').attr('id', 'divrow_txt-' + unque_number1 + '-' + unque_number2+'-'+unque_count);
					clonedRow.find('.row_txt_start_position').attr('id', 'row_txt_start_position' + unque_number1 + unque_number2);
					clonedRow.find('.row_txt_end_position').attr('id', 'row_txt_end_position' + unque_number1 + unque_number2);
					clonedRow.find('.row_txt_start_position').val("");
					clonedRow.find('.row_txt_end_position').val("");
					clonedRow.find('textarea').attr('onclick', 'updateCursorPosition(this, ' + unque_number1 + ', ' + unque_number2 + ','+unque_count+')');
					clonedRow.find('textarea').attr('onfocus', 'modifyPdf(0,0,0,0,0)');
					clonedRow.find('textarea').attr('name', 'table_mapping_field' + (unque_number1 - 2) + unque_number2 + '[]');
					clonedRow.find('.table_mapping_field_cord').attr('name', 'table_mapping_field_cord' + (unque_number1 - 2) + unque_number2 + '[]');
					clonedRow.find('.row_no_text').val(unque_number2);
					clonedRow.find('.input_row_txt').attr('id', 'input_row_txt-' + unque_number1 + '-' + unque_number2);
					clonedRow.find('.input_row_txt').val("");
					clonedRow.find('.divrow_txt').text("");
					clonedRow.find('select').val('');
					clonedRow.find('input[type="checkbox"]').prop('checked', false);
					clonedRow.find('.table_mapping_field_cord').val("0|0|0|0|1");
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

				const renderInIframe = (pdfBytes, pageNumber) => {
					const blob = new Blob([pdfBytes], {
						type: 'application/pdf'
					});
					//const url = URL.createObjectURL(blob);
					const blobUrl = URL.createObjectURL(blob);
					//document.getElementById('iframe').src = blobUrl;
					document.getElementById('pdf_frame').src = blobUrl;
				};
				
				async function modifyPdf(cor_x = "", cor_y = "", w = "", h = "", pageNumber = 1) {
					//console.log("pageNumber "+pageNumber);
					const url = `<?= $ocr_file; ?>`;
					const existingPdfBytes = await fetch(url).then(res => res.arrayBuffer())
					cor_y = cor_y - 0.026;

					var bytes = new Uint8Array(existingPdfBytes);
					const pdfDoc = await PDFDocument.load(existingPdfBytes)
					const pages = pdfDoc.getPages();
					//console.log(pages);
					const page = pdfDoc.getPage(pageNumber - 1);
					//var firstPage = pages[0];
					var pgheight = page.getHeight();
					pgheight = pgheight - 2;

					if (cor_x != "") {
						const {
							pg_width,
							pg_height
						} = page.getSize();
						var x = cor_x * 72;
						var y = pgheight - (cor_y * 72);
						var width = w * 72;
						var height = h * 72;

						try {
							page.drawRectangle({
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
					renderInIframe(pdfBytes,pageNumber);
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

				$(document).on('change', '.table_mapping_field_head_dp', function() {
					var table_id = $(this).attr('table_id');
					var selected_val = $(this).val();
					if (selected_val == "Material/Fee column") {
						$(this).attr('id', 'mapping_field_head_' + table_id);
					}
				});

				function updateCursorPosition(ctrltextbox, uniq_count1, uniq_count2, uniq_count) {
					if ($("#mapping_field_head_" + uniq_count).length > 0) {
						var material_selected = $("#mapping_field_head_" + uniq_count).val();
						var column_no_head = $("#mapping_field_head_" + uniq_count).closest('td').index();
						//console.log("Column number db is:", column_no_head);
						var column_no_text_area = ctrltextbox.closest('td').cellIndex; // Find the closest parent <td>
						//console.log("Column number Textarea is:", column_no_text_area);
						if (material_selected == "Material/Fee column" && column_no_head == column_no_text_area) {
							var selectionStart = ctrltextbox.selectionStart;
							var selectionEnd = ctrltextbox.selectionEnd;
							var txtval = ctrltextbox.value;
							var seltxtval = txtval.substring(selectionStart, selectionEnd);
							$("#row_txt_start_position" + uniq_count1 + "" + uniq_count2).val(selectionStart + 1);
							$("#row_txt_end_position" + uniq_count1 + "" + uniq_count2).val(selectionEnd);
							$("#divrow_txt-" + uniq_count1 + "-" + uniq_count2+'-'+uniq_count).css("display", "inline");
							$("#divrow_txt-" + uniq_count1 + "-" + uniq_count2+'-'+uniq_count).html("<small>" + seltxtval + "</small>");

							// Update the display
							//document.getElementById("row_txt_position"+tbl_head_cnt+ctrl_cnt).innerText = cursorPosition;

							$("#input_row_txt-" + uniq_count1 + "-" + uniq_count2).val(seltxtval);
						}
					} else {
						alert("For OCR Text, Please select the Material/Fee column from the Above dropdown!");
					}
				}
			</script>
</body>