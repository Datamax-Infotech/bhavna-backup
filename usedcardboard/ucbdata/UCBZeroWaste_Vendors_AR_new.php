<?
/*
File Name: UCBZeroWaste_Vendors_AR_new.php
Page created By: Amarendra
Page created On: 27-03-2023
Last Modified On: 
Last Modified By: Bhavna
Change History:
Date           By            Description
================================================================================================
10-06-2024      Bhavna    UCBZeroWaste Vendors AR new feedback		
================================================================================================
*/

session_start();
require_once("inc/header_session.php");
require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");

$swhere_condition = "";
if (isset($_REQUEST["comp_sel"])) {
	if ($_REQUEST["comp_sel"] != "All") {
		$swhere_condition = " and loop_warehouse.id = " . $_REQUEST["comp_sel"];
	}
	$comp_sel = $_REQUEST["comp_sel"];
} else {
	$comp_sel = "All";
}
if (isset($_REQUEST["vendors_dd"])) {
	$vendors_dd = $_REQUEST["vendors_dd"];
} else {
	$vendors_dd = 'All';
}
$receivables = $payables = $ddMadePayment = $date_from = $date_to = "";

$receivables = 'yes';

if (isset($_REQUEST['ddMadePayment'])) {
	$ddMadePayment = $_REQUEST['ddMadePayment'];
	if ($ddMadePayment != "All") {
		$swhere_condition .= " AND made_payment = '" . $ddMadePayment . "' ";
	}
} else {
	$ddMadePayment = '0';
}

if ($_REQUEST["date_from"] != "" && $_REQUEST["date_to"] != "") {
	//$date_from	= date("2022-01-01");
	//$date_to	= date("Y-m-d");
	$date_from	= $_REQUEST["date_from"];
	$date_to	= $_REQUEST["date_to"];
	$swhere_condition .= " AND invoice_date BETWEEN '" . Date("Y-m-d", strtotime($date_from)) . "' and '" . Date("Y-m-d", strtotime($date_to . "+1 day")) . "'";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
	<title>Vendor A/R Aging Report.</title>
	<style>
		.display_maintitle {
			font-size: 13px;
			padding: 3px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			background: #98bcdf;
			white-space: nowrap;
		}

		.display_title {
			font-size: 12px;
		}

		.display_table {
			font-size: 11px;
			padding: 3px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			background: #EBEBEB;
		}

		.btnEdit {
			appearance: auto;
			user-select: none;
			white-space: pre;
			align-items: flex-start;
			text-align: center;
			cursor: default;
			color: -internal-light-dark(black, white);
			background-color: -internal-light-dark(rgb(239, 239, 239), rgb(59, 59, 59));
			box-sizing: border-box;
			padding: 1px 6px;
			border-width: 2px;
			border-style: outset;
			border-color: -internal-light-dark(rgb(118, 118, 118), rgb(133, 133, 133));
			border-image: initial;
		}

		.black_overlay {
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none;
			position: absolute;
			padding: 5px;
			border: 2px solid black;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}

		.notes_tbl th {
			white-space: nowrap;
		}

		.notes_tbl,
		.notes_tbl th,
		.notes_tbl td {
			border: solid 1px #000;
			border-collapse: collapse;
			padding: 5px 8px;
			font-size: 11px;
		}
		.log_note_history{
			cursor:pointer;
			white-space:nowrap;
			text-decoration: underline;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>
		function displarepsorteddata(comp_id, vendor_id, dmadepayment, dtfrom, dtto, receives, payables, flt_inv_due_dt, columnno, sortflg) {

			document.getElementById("div_general_forrep").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();

			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("div_general_forrep").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "UCBZeroWaste_Vendors_AR_child_new.php?comp_sel=" + comp_id + "&vendors_dd=" + vendor_id + "&ddMadePayment=" + dmadepayment + "&date_from=" + dtfrom + "&date_to=" + dtto + "&receivables=" + receives + "&payables=" + payables + "&flt_inv_due_dt=" + flt_inv_due_dt + "&columnno=" + columnno + "&sortflg=" + sortflg, true);
			xmlhttp.send();
		}

		function GetFileSize() {
			var fi = document.getElementById('payment_proof_file'); // GET THE FILE INPUT.

			// VALIDATE OR CHECK IF ANY FILE IS SELECTED.
			if (fi.files.length > 0) {
				// RUN A LOOP TO CHECK EACH SELECTED FILE.
				for (var i = 0; i <= fi.files.length - 1; i++) {
					var filenm = fi.files.item(i).name;

					if (filenm.indexOf("#") > 0) {
						alert("Remove # from Scan file and then upload file!");
						document.getElementById("payment_proof_file").value = "";
					}
					if (filenm.indexOf("\'") > 0) {
						alert("Remove \' from " + filenm + " file and then upload file!");
						document.getElementById("payment_proof_file").value = "";
					}

					var fsize = fi.files.item(i).size; // THE SIZE OF THE FILE.
					if (Math.round(fsize / 1024) > 8000) {
						alert("Only files with 8mb is allowed.");
						document.getElementById("payment_proof_file").value = "";
					}
				}
			}
		}

		function editSectionOpen(rec_id) {
			/*var editSectionTbl = document.getElementById("editSectionTbl_"+rec_id);
			var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_"+rec_id);
			if(editSectionTbl != ''){
				editSectionTbl.style.display = 'revert';
				btnEditSectionOpen.style.display = 'none';
			}*/

			var selectobject = document.getElementById("atag_vendor_payment_div" + rec_id);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = (n_left - 250) + 'px';
			document.getElementById('light').style.top = n_top + 5 + 'px';
			document.getElementById('light').style.width = 300;

			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + document.getElementById("payment_no_div_edit-" + rec_id).innerHTML;
			document.getElementById('light').style.display = 'block';
		}

		function cancelSectionClose(rec_id) {
			var editSectionTbl = document.getElementById("editSectionTbl_" + rec_id);
			var btnCancelSectionClose = document.getElementById("btnCancelSectionClose_" + rec_id);
			var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_" + rec_id);
			if (editSectionTbl != '') {
				editSectionTbl.style.display = 'none';
				btnEditSectionOpen.style.display = 'revert';
			}

		}

		function openCalendar(buttonId) {
			var selectobject = document.getElementById(buttonId);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');

			let popup = window.open('calendar.php?buttonId=' + buttonId, 'calendar', 'width=500,height=600,left=${n_left},top=${n_top}');
			popup.onunload = function() {
				// Placeholder for further actions when the popup is closed
			}
		}


		function show_vendor_payment_div(rowcnt) {
			var selectobject = document.getElementById("atag_vendor_payment_div" + rowcnt);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = (n_left - 250) + 'px';
			document.getElementById('light').style.top = n_top + 5 + 'px';
			document.getElementById('light').style.width = 300;

			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + document.getElementById("payment_no_div_view-" + rowcnt).innerHTML;
			document.getElementById('light').style.display = 'block';
		}

		//----------Open All Log Notes Popup--------------------------------------
		function show_all_log_notes(transid, cnt, type) {
			if (type == "date") {
				var selectobject = document.getElementById("show_date_history_btn_" + cnt);
			} else {
				var selectobject = document.getElementById("show_notes_history_btn_" + cnt);
			}
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = (n_left) + 'px';
			document.getElementById('light').style.top = n_top + 20 + 'px';

			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light').style.display = 'block';
				}
			}

			xmlhttp.open("GET", "update_vendor_ap_ar_data.php?get_all_log_notes=yes&type=" + type + "&transid=" + transid, true);
			xmlhttp.send();
		}

		function update_vendor_report(rec_id) {
			var formElement = document.getElementById('vendor_edit_form_each_row-' + rec_id);
			var all_data = new FormData(formElement);
			//console.log(all_data);
			$.ajax({
				url: 'update_vendor_ap_ar_data.php',
				type: 'post',
				data: all_data,
				datatype: 'json',
				contentType: false,
				processData: false,
				async: false,
				beforeSend: function() {
					$("#btnUpdateVendrRpt_" + rec_id).attr('disabled', true);
					//$('#save-task').attr('disabled',true);
					//$('#save-task').prev('.spinner').removeClass('d-none');
				},
				success: function(res) {
					var res = JSON.parse(res);
					console.log(res);
					if (res.updated == 1) {
						var data = res.data;
						var made_payment = data.made_payment == "1" ? 'Yes' : "No";

						$('#made_payment_td-' + rec_id).text(made_payment);
						$('#payment_method_td-' + rec_id).text(data.payment_method);
						$('#paid_date_td-' + rec_id).text(data.paid_date);
						$('#paid_date_td2-' + rec_id).text(data.paid_date);
						$('#paid_by_td-' + rec_id).text(data.paid_by);
						$('#ar_status_td-' + rec_id).text(data.ar_status);
						$('#view_vendor_payment_log_notes' + rec_id).text(data.view_vendor_payment_log_notes);
						$('#view_vendor_payment_log_notes' + rec_id).html(data.view_vendor_payment_log_notes);
						$("#log_notes_td-" + rec_id).find('span').text(data.vendor_payment_log_notes);
						$("#show_date_history_btn_" + rec_id).css('display', 'revert');
						$("#show_notes_history_btn_" + rec_id).css('display', 'revert');
						$("#log_notes_date_td-" + rec_id).find('span').text(data.last_edited);

						if (data.payment_proof_file != "") {
							var file_tag = "";
							if (data.payment_proof_file.indexOf("|") > 0) {
								var elements = data.payment_proof_file.split("|");
								for (i = 0; i < elements.length; i++) {
									file_tag += `<a target="_blank" href="water_payment_proof/${elements[i]}"><font size="1">View</font></a><br>`;
								}
							} else {
								file_tag += `<a target="_blank" href="water_payment_proof/${data.payment_proof_file}"><font size="1">View</font></a>`;
							}
							$('#payment_proof_file_td-' + rec_id).html(file_tag);
						}
						data.made_payment == "" && data.payment_method == "" ? $("#editSectionTbl_" + rec_id).css('display', 'revert') : $("#editSectionTbl_" + rec_id).css('display', 'none');
						data.made_payment == "" && data.payment_method == "" ? $("#btnEditSectionOpen_" + rec_id).css('display', 'none') : $("#btnEditSectionOpen_" + rec_id).css('display', 'revert');
					} else {
						alert("Transaction Log Notes can't be blank to update data!");
					}
				},
				complete: function() {
					$("#btnUpdateVendrRpt_" + rec_id).attr('disabled', false);
				}
			});

			return false;
		}

		function f_getPosition(e_elemRef, s_coord) {
			var n_pos = 0,
				n_offset,
				//e_elem = selectobject;
				e_elem = e_elemRef;
			while (e_elem) {
				n_offset = e_elem["offset" + s_coord];
				n_pos += n_offset;
				e_elem = e_elem.offsetParent;

			}
			e_elem = e_elemRef;
			//e_elem = selectobject;
			while (e_elem != document.body) {
				n_offset = e_elem["windows" + s_coord];
				if (n_offset && e_elem.style.overflow == 'windows')
					n_pos -= n_offset;
				e_elem = e_elem.parentNode;
			}

			return n_pos;

		}
		//----------Open show all notes popup--------------------------------------
		function show_all_notes(type, vendor_id, cnt) {
			var selectobject = document.getElementById("show_all_btn_" + cnt);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = (n_left - 400) + 'px';
			document.getElementById('light').style.top = n_top + 40 + 'px';

			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light').style.display = 'block';
				}
			}

			xmlhttp.open("GET", "update_vendor_ap_ar_data.php?get_all_notes=yes&type=" + type + "&vendor_id=" + vendor_id, true);
			xmlhttp.send();
		}

		function open_template_vendors_AR(summaryrep, warehouse_id, transid, vendor_id, cnt) {

			var selectobject = document.getElementById("agtempl" + cnt);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = (n_left - 900) + 'px';
			document.getElementById('light').style.top = n_top - 10 + 'px';

			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;
					document.getElementById('light').style.display = 'block';
				}
			}

			xmlhttp.open("GET", "open_template_vendors_AR.php?summaryrep=" + summaryrep + "&warehouse_id=" + warehouse_id + "&transid=" + transid + "&vendor_id=" + vendor_id + "&cnt=" + cnt, true);
			xmlhttp.send();
		}

		function select_email_templ() {
			var x = document.getElementById("ag_template").value;
			if (x == "-1") {
				document.getElementById('show_templ' + x).style.display = "none";
			}
			for (var i = 1; i < 5; i++) {
				var n = i.toString();
				if (x == n) {
					//console.log('show_templ'+i);
					document.getElementById('show_templ' + i).style.display = "block";
				} else {
					document.getElementById('show_templ' + i).style.display = "none";
				}
			}
		}

		function btnsendclick_templ(id) {
			var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

			tmp_element1 = document.getElementById("txtemailto" + id).value;

			tmp_element2 = document.getElementById("templ_email");

			tmp_element3 = document.getElementById("txtemailcc" + id).value;

			tmp_element4 = document.getElementById("txtemailsubject" + id).value;

			tmp_element5 = document.getElementById("hidden_reply_eml" + id).value;

			var warehouse_id = document.getElementById("warehouse_id_e" + id).value;
			var rec_id = document.getElementById("rec_id_e" + id).value;
			var summaryrep = document.getElementById("summaryrep").value;

			var inst = FCKeditorAPI.GetInstance("txtemailbody" + id);
			var emailtext = inst.GetHTML();
			//alert(emailtext);
			tmp_element5.value = emailtext;
			//tmp_element2.submit();

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert("sdfsd".xmlhttp.responseText);
					if (xmlhttp.responseText == "") {
						alert("Email Sent.");
					} else {
						alert(xmlhttp.responseText);
					}
					document.getElementById('light').style.display = 'none';
				}
			}

			xmlhttp.open("GET", "open_template_vendors_AR.php?txtemailto=" + tmp_element1 + "&unqid=" + id + "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id + "&txtemailcc=" + tmp_element3 + "&txtemailsubject=" + encodeURIComponent(tmp_element4) + "&summaryrep=" + summaryrep + "&txtemailattch=" + encodeURIComponent(document.getElementById("txtemailattch" + id).value) + "&hidden_sendemail=inemailmode&emlbody=" + encodeURIComponent(emailtext), true);
			xmlhttp.send();

		}
	</script>
	<LINK rel='stylesheet' type='text/css' href='one_style.css'>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>
	<? include("inc/header.php"); ?>

	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">Vendor A/R Aging Report.
				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">Vendor A/R Aging Report.</span>
				</div>
				<div style="height: 13px;">&nbsp;</div>
			</div>
		</div>

		<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
		<SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
		<script LANGUAGE="JavaScript">
			document.write(getCalendarStyles());
		</script>
		<script LANGUAGE="JavaScript">
			var cal2xx = new CalendarPopup("listdiv");
			cal2xx.showNavigationDropdowns();
			var cal3xx = new CalendarPopup("listdiv");
			cal3xx.showNavigationDropdowns();
		</script>

		<form method="GET" name="frmwater_report_internal" action="UCBZeroWaste_Vendors_AR_new.php">

			<table border="0" cellspacing="1" cellpadding="1">
				<tr>
					<td align="left" colspan="5">
						<b>Select Mandatory Filters:</b>
					</td>
				</tr>
				<tr>
					<td class="" align="left">
						Vendor: <br>

						<select id="vendors_dd" name="vendors_dd" style="width: 230px;">
							<option value="All" <?= (($vendors_dd == 'All') ? "selected" : ""); ?>>All</option>
							<?
							$vendor_qry = "SELECT * FROM water_vendors where active_flg = 1 order by Name, city, state, zipcode";
							$query = db_query($vendor_qry, db());
							$vender_nm = "";
							//	
							while ($vendor_row = array_shift($query)) {
								$vender_nm = $vendor_row['Name'] . " - " . $vendor_row["description"] . " - " . $vendor_row['city'] . ", " . $vendor_row['state'] . " " . $vendor_row['zipcode'];
							?>
								<option value="<? echo $vendor_row['id']; ?>" <?= (($vendors_dd == $vendor_row['id']) ? "selected" : ""); ?>>
									<? echo $vender_nm; ?>
								</option>
							<?
							}
							?>
						</select>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td align="left">
						Company: <br>
						<select id="comp_sel" name="comp_sel" style="width: 200px;">
							<option value="All">All</option>
							<?
							$main_sql = "Select loop_warehouse.id, company_name, b2bid from loop_warehouse inner join water_transaction on loop_warehouse.id = water_transaction.company_id where Active = 1 and loop_warehouse.rec_type = 'Manufacturer' group by loop_warehouse.id order by company_name";
							//
							$data_res = db_query($main_sql);
							while ($data = array_shift($data_res)) {
								echo "<option value='" . $data["id"] . "' ";
								if ($_REQUEST["comp_sel"] == $data["id"]) {
									echo " selected ";
								}
								echo ">" . get_nickname_val($data["company_name"], $data["b2bid"]) . "</option>";
							}
							?>
						</select>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td align="left">
						Has UCBZW Received the Rebate?<br>
						<select id="ddMadePayment" name="ddMadePayment" style="width: 200px;">
							<option value="All" <?= (($ddMadePayment == 'All') ? "selected" : ""); ?>>All</option>
							<option value="1" <?= (($ddMadePayment == '1') ? "selected" : ""); ?>>Yes</option>
							<option value="0" <?= (($ddMadePayment == '0') ? "selected" : ""); ?>>No</option>
						</select>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td align="left">
						Filter by Invoice Due Date:<br>
						<input type="text" name="flt_inv_due_dt" id="flt_inv_due_dt" size="10" value="<?= (isset($_REQUEST['flt_inv_due_dt']) ? $_REQUEST['flt_inv_due_dt'] : "01/01/2023"); ?>">
						<a href="#" onclick="cal3xx.select(document.frmwater_report_internal.flt_inv_due_dt,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>

					</td>
				</tr>
			</table>

			<table border="0" cellspacing="1" cellpadding="1">
				<tr>
					<td align="left" colspan="5">
						<b>Select Additional Filter Options:</b>
					</td>
				</tr>
				<tr>
					<td>
						Service Month Date From:
						<input type="text" name="date_from" id="date_from" size="10" value="<?= (isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : ""); ?>">
						<a href="#" onclick="cal2xx.select(document.frmwater_report_internal.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
						Service Month Date To:
						<input type="text" name="date_to" id="date_to" size="10" value="<?= (isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : ""); ?>">
						<a href="#" onclick="cal3xx.select(document.frmwater_report_internal.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
					</td>
					<td>&nbsp;&nbsp;&nbsp;</td>
					<td>
						<input type="submit" id="btnrep" name="btnrep" value="Run Report" />
					</td>
				</tr>
			</table>
		</form>

		<?

		if (isset($_REQUEST["btnrep"])) {

		?>

			<div>
				<a href="UCBZeroWaste_Vendors_AP_AR_Excel.php?from=AR"><span class="">"Click Here"</span></a> to export the table.<br>
				<span><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before using the download option.</i></span>
			</div>

			<?
			$termsOutputData = array();
			$healthOutputData = array();

			$comp_where_condition = "";
			if (isset($_REQUEST["comp_sel"])) {
				if ($_REQUEST["comp_sel"] != "All") {
					$comp_where_condition = " and water_transaction.company_id = " . $_REQUEST["comp_sel"];
				}
			}

			if (isset($_REQUEST["vendors_dd"])) {
				if ($_REQUEST["vendors_dd"] != "All") {
					$vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 and vendor_id = '" . $_REQUEST["vendors_dd"] . "' $comp_where_condition group by vendor_id";
				} else {
					$vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 $comp_where_condition group by vendor_id";
				}
			} else {
				$vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 $comp_where_condition group by vendor_id";
			}

			$v_res = db_query($vendorsQry, db());
			// echo $vendorsQry;
			$whrMadePayConditn = '';
			if (isset($_REQUEST['ddMadePayment']) && $_REQUEST['ddMadePayment'] != 'All') {
				$whrMadePayConditn = ' and water_transaction.made_payment = ' . $_REQUEST['ddMadePayment'];
			}

			$whr_flt_inv_due_dt = "";
			$flt_inv_due_dt = "";
			if (isset($_REQUEST['flt_inv_due_dt']) && $_REQUEST['flt_inv_due_dt'] != '') {
				$flt_inv_due_dt = $_REQUEST['flt_inv_due_dt'];
				$whr_flt_inv_due_dt = ' and (invoice_due_date >= "' . date("Y-m-d", strtotime($_REQUEST['flt_inv_due_dt'])) . '" OR invoice_due_date is NULL)';
			}

			//$arStatus = ['Status','Active','P2P','Escalated','Paid'];
			$arStatus = ['', 'P2P', 'Escalated'];
			$companyTermsQry = db_query("SELECT DISTINCT company_terms FROM `loop_warehouse`", db());
			$termsArray = array_filter(array_column($companyTermsQry, "company_terms"));
			// $termsArray = ["Due Upon Reciept","Net 15","Net 30","Net 60","Net 90","Other"];

			// 	ini_set("display_errors", "1");
			// error_reporting(E_ALL);

			$totalAmount = $totalInvoice = $totalWithINRangeAmount = $totalnoduedate = $countWithIN = $countnoduedate = $total1RangeAmount = $count1 = $total31RangeAmount = $count31 = $total61RangeAmount = $count61 = $total90RangeAmount = $count90 = 0;

			$healthtotalAmount = $healthtotalInvoice = $healthtotalActiveRangeAmount = $countActive = $totalEscalatedRangeAmount = $countEscalated = $totalp2pRangeAmount = $countp2p = $totalpaidRangeAmount = $countPaid = $totalNoActionRangeAmount = $countNoAction = 0;

			//foreach ($v_res as $termHealth) {
			$TermsQuery = "SELECT CASE WHEN (DATEDIFF(CURDATE(), invoice_due_date) < 1 ) THEN '0 - Within Terms' 
		WHEN (invoice_due_date is null) THEN '5 - Without Due Date' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 1 AND 30 THEN '1 - 1-30 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 31 AND 60 THEN '2 - 31-60 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 61 AND 90 THEN '3 - 61-90 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) > 90 THEN '4 - 90+ Days Past Due' ELSE 'Not Categorized' END AS DueRange, invoice_due_date, SUM(amount) AS TotalAmount, 
		invoice_date FROM water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
		left join water_vendors_payable_contact on water_transaction.vendor_id = water_vendors_payable_contact.water_vendor_id
		where make_receive_payment = 1  " . $whrMadePayConditn . " " . $whr_flt_inv_due_dt . " $swhere_condition 
		group by DueRange, company_id, water_transaction.id having sum(amount) > 0 order by DueRange asc";
			//echo $TermsQuery . "<br>";
			//and vendor_id='".$termHealth["vendor_id"]."'
			$TermsResult = db_query($TermsQuery, db());

			if (!empty($TermsResult)) {
				foreach ($TermsResult as $TermsData) {

					$totalAmount += $TermsData['TotalAmount'];
					$totalInvoice = $totalInvoice + 1;

					switch ($TermsData['DueRange']) {
						case "0 - Within Terms":
							$totalWithINRangeAmount += $TermsData['TotalAmount'];
							$countWithIN = $countWithIN + 1;
							$termsOutputData["Within Terms"] = [
								'terms' => "Within Terms",
								'invoices' => $countWithIN,
								'amount' => $totalWithINRangeAmount
							];
							break;
						case "5 - Without Due Date":
							$totalnoduedate += $TermsData['TotalAmount'];
							$countnoduedate = $countnoduedate + 1;
							$termsOutputData["Without Due Date"] = [
								'terms' => "Without Due Date",
								'invoices' => $countnoduedate,
								'amount' => $totalnoduedate
							];
							break;
						case "1 - 1-30 Days Past Due":
							$total1RangeAmount += $TermsData['TotalAmount'];
							$count1 = $count1 + 1;
							$termsOutputData["1-30 Days Past Due"] = [
								'terms' => "1-30 Days Past Due",
								'invoices' => $count1,
								'amount' => $total1RangeAmount
							];
							break;
						case "2 - 31-60 Days Past Due":
							$total31RangeAmount += $TermsData['TotalAmount'];
							$count31 = $count31 + 1;
							$termsOutputData["31-60 Days Past Due"] = [
								'terms' => "31-60 Days Past Due",
								'invoices' => $count31,
								'amount' => $total31RangeAmount
							];
							break;
						case "3 - 61-90 Days Past Due":
							$total61RangeAmount += $TermsData['TotalAmount'];
							$count61 = $count61 + 1;
							$termsOutputData["61-90 Days Past Due"] = [
								'terms' => "61-90 Days Past Due",
								'invoices' => $count61,
								'amount' => $total61RangeAmount
							];
							break;
						case "4 - >90 Days Past Due":
							$total90RangeAmount += $TermsData['TotalAmount'];
							$count90 = $count90 + 1;
							$termsOutputData[">90 Days Past Due"] = [
								'terms' => ">90 Days Past Due",
								'invoices' => $count90,
								'amount' => $total90RangeAmount
							];
							break;
					}
				}
			}

			//CASE WHEN ar_status = 'active' THEN 'Active' WHEN ar_status = 'escalated' THEN 'Escalated' WHEN ar_status = 'p2p' THEN 'P2P' WHEN ar_status = 'paid' THEN 'Paid' ELSE 'No Action Needed' END AS Status,
			$healthQuery = "SELECT  CASE WHEN made_payment = 0 THEN 'Active' WHEN made_payment = 1 THEN 'Paid' END AS Status, SUM(amount) AS TotalAmount 
		FROM water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
		left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
		where make_receive_payment = 1  " . $whrMadePayConditn . " " . $whr_flt_inv_due_dt . " $swhere_condition group by Status, company_id, water_transaction.id having sum(amount) > 0 order by made_payment desc";
			//and vendor_id='".$termHealth["vendor_id"]."'
			$healthResult = db_query($healthQuery, db());

			if (!empty($healthResult)) {
				foreach ($healthResult as $healthData) {

					$healthtotalAmount += $healthData['TotalAmount'];
					$healthtotalInvoice = $healthtotalInvoice + 1;

					switch ($healthData['Status']) {
						case "Active":
							$healthtotalActiveRangeAmount += $healthData['TotalAmount'];
							$countActive = $countActive + 1;
							$healthOutputData["Active"] = [
								'terms' => $healthData['Status'],
								'invoices' => $countActive,
								'amount' => $healthtotalActiveRangeAmount
							];
							break;
						case "Escalated":
							$totalEscalatedRangeAmount += $healthData['TotalAmount'];
							$countEscalated = $countEscalated + 1;
							$healthOutputData["Escalated"] = [
								'terms' => $healthData['Status'],
								'invoices' => $countEscalated,
								'amount' => $totalEscalatedRangeAmount
							];
							break;
						case "P2P":
							$totalp2pRangeAmount += $healthData['TotalAmount'];
							$countp2p = $countp2p + 1;
							$healthOutputData["P2P"] = [
								'terms' => $healthData['Status'],
								'invoices' => $countp2p,
								'amount' => $totalp2pRangeAmount
							];
							break;
						case "Paid":
							$totalpaidRangeAmount += $healthData['TotalAmount'];
							$countPaid = $countPaid + 1;
							$healthOutputData["Paid"] = [
								'terms' => $healthData['Status'],
								'invoices' => $countPaid,
								'amount' => $totalpaidRangeAmount
							];
							break;
						default:
							$totalNoActionRangeAmount += $healthData['TotalAmount'];
							$countNoAction = $countNoAction + 1;
							$healthOutputData["No Action Needed"] = [
								'terms' => $healthData['Status'],
								'invoices' => $countNoAction,
								'amount' => $totalNoActionRangeAmount
							];
							break;
					}
				}
			}
			//}
			?>

			<div id="ARTearmBreakdownTable" class="ARTearmBreakdownTable" style="padding: 25px 0;">
				<table width="50%" border="1" cellspacing="1" cellpadding="2">
					<thead style="background-color: #000;color: #fff;text-align: center;">
						<tr>
							<td colspan="4"><strong>A/R Terms Breakdown</strong></td>
						</tr>
						<tr>
							<td width="30%">Terms</td>
							<td width="20%">Invoices</td>
							<td width="30%">Amount ($)</td>
							<td width="20%">% of A/R</td>
						</tr>
					</thead>
					<tbody style="text-align: right;">
						<?php
						foreach ($termsOutputData as $termValue) {
						?>
							<tr>
								<td style="text-align: center;"><?= $termValue['terms'] ?></td>
								<td><?= $termValue['invoices'] ?></td>
								<td><?= "$" . number_format($termValue['amount'], 2) ?></td>
								<td><?= number_format(($termValue['amount'] / $totalAmount) * 100, 2) . "%" ?></td>
							</tr>
						<? } ?>
						<tr style="font-weight: 700;">
							<td>Totals</td>
							<td><?= $totalInvoice ?></td>
							<td><?= "$" . number_format($totalAmount, 2) ?></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="healthTable" class="healthTable" style="padding: 25px 0;">
				<table width="50%" border="1" cellspacing="1" cellpadding="2">
					<thead style="background-color: #000;color: #fff;text-align: center;">
						<tr>
							<td colspan="4"><strong>A/R Health</strong></td>
						</tr>
						<tr>
							<td width="30%">Status</td>
							<td width="20%">Invoices</td>
							<td width="30%">Amount ($)</td>
							<td width="20%">% of A/R</td>
						</tr>
					</thead>
					<tbody style="text-align: right;">
						<?php
						foreach ($healthOutputData as $healthValue) {
						?>
							<tr>
								<td style="text-align: center;"><?= $healthValue['terms'] ?></td>
								<td><?= $healthValue['invoices'] ?></td>
								<td><?= "$" . number_format($healthValue['amount'], 2) ?></td>
								<td><?= number_format(($healthValue['amount'] / $healthtotalAmount) * 100, 2) . "%" ?></td>
							</tr>
						<? } ?>
						<tr style="font-weight: 700;">
							<td>Totals</td>
							<td><?= $healthtotalInvoice ?></td>
							<td><?= "$" . number_format($healthtotalAmount, 2) ?></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="light" class="white_content"></div>
			<div id="fade" class="black_overlay"></div>
			<div id="div_general_forrep" name="div_general_forrep">

				<table width="60%" border="0" cellspacing="1" cellpadding="1">

					<tr class="display_maintitle" style="background-color: #ABC5DF;">
						<td>Sr.No</td>
						<td width="290px">Vendor Name&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 1, 1);"><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 1, 2);"><img src="images/sort_desc.png" width="5px;" height="10px;"></a>
						</td>


						<td>Vendor A/P Contact</td>
						<td>UCBZeroWaste Client Name&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 2, 1);"><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 2, 2);"><img src="images/sort_desc.png" width="5px;" height="10px;"></a>
						</td>

						<td>Service Month&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 5, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>',  5, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>
						<td>Invoice Number&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 6, 1);"><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 6, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Scan of Invoice</td>

						<td>Invoice Date&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 8, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 8, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Invoice Due Date&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 9, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 9, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Invoice Age&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 10, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 10, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Invoice Amount&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 3, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 3, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Vendor Payment Method to UCBZeroWaste&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 11, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 11, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>

						<td>Has UCBZW Received the Rebate?&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 12, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 12, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>
						<td>Log Notes Date?&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>','<?= $flt_inv_due_dt; ?>', 20, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 20, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>
						<td>Log Notes?&nbsp;
							<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>',21, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', '<?= $flt_inv_due_dt; ?>', 21, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
						</td>
						<td>Send Template</td>

						<td>Vendor Payment Report
						</td>

					</tr>
					<?
					$arcount = 1;
					$sr_inv = 1;
					//while ($data_row = array_shift($v_res)) {

					if (isset($_REQUEST["vendors_dd"])) {
						if ($_REQUEST["vendors_dd"] != "All") {
							$vendorQry = "Select billingSwitchToZeroWaste, date_of_bill_switch, invoice_due_date, vendor_id, Name, description, city, state, zipcode, payable_contact_name, payable_main_phone, payable_email, invoice_date, invoice_number, scan_report, new_invoice_date, made_payment, paid_by	
					,paid_date, payment_method, contact_email, vendor_payment_log_notes, payment_proof_file, company_contact, company_phone, company_email, company_terms, no_invoice_due_marked_on, 
					loop_warehouse.id as warehouse_id,water_vendors_payable_contact.id as receivable_id, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid 
					from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
					inner join water_vendors on water_transaction.vendor_id=water_vendors.id
					left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
					where water_transaction.vendor_id = '" . $_REQUEST["vendors_dd"] . "' and make_receive_payment = 1  " . $whrMadePayConditn . " " . $whr_flt_inv_due_dt . "  $swhere_condition group by company_id, 
					water_transaction.id having sum(amount) > 0 order by invoice_due_date IS NULL, invoice_due_date asc";
						} else {

							$vendorQry = "Select billingSwitchToZeroWaste, date_of_bill_switch, invoice_due_date, vendor_id, Name, description, city, state, zipcode, payable_contact_name, payable_main_phone, payable_email, invoice_date, invoice_number, scan_report, new_invoice_date, made_payment, paid_by	
					,paid_date, payment_method, contact_email, vendor_payment_log_notes, payment_proof_file, company_contact, company_phone, company_email, company_terms, no_invoice_due_marked_on, 
					loop_warehouse.id as warehouse_id,water_vendors_payable_contact.id as receivable_id, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid 
					from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
					inner join water_vendors on water_transaction.vendor_id=water_vendors.id
					left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
					where make_receive_payment = 1  " . $whrMadePayConditn . " " . $whr_flt_inv_due_dt . "  $swhere_condition group by company_id, 
					water_transaction.id having sum(amount) > 0 order by invoice_due_date IS NULL, invoice_due_date asc";
						}
					} else {
						//and vendor_id='".$data_row["vendor_id"]."'
						$vendorQry = "Select billingSwitchToZeroWaste, date_of_bill_switch, invoice_due_date, vendor_id, Name, description, city, state, zipcode, payable_contact_name, payable_main_phone, payable_email, invoice_date, invoice_number, scan_report, new_invoice_date, made_payment, paid_by	
				,paid_date, payment_method, contact_email, vendor_payment_log_notes, payment_proof_file, company_contact, company_phone, company_email, company_terms, no_invoice_due_marked_on, 
				loop_warehouse.id as warehouse_id,water_vendors_payable_contact.id as receivable_id, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid 
				from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
				inner join water_vendors on water_transaction.vendor_id=water_vendors.id
				left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
				where make_receive_payment = 1  " . $whrMadePayConditn . " " . $whr_flt_inv_due_dt . " $swhere_condition group by company_id, 
				water_transaction.id having sum(amount) > 0 order by  invoice_due_date IS NULL, invoice_due_date asc";
					}
					//echo $vendorQry . "<br>";

					// Select *, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id inner join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id where make_receive_payment = 1 and vendor_id='634' group by company_id, water_transaction.id having sum(amount) > 0 order by water_transaction.company_id
					db();
					$v_res1 = db_query($vendorQry);

					$vnumrows = tep_db_num_rows($v_res1);
					if ($vnumrows > 0) {
						$common_vendor_id = $data_row["vendor_id"];

						$arcount++;

						$row = 1;
						$isRec = '';
						$unq_inc = 0;
						while ($rows = array_shift($v_res1)) {
							$unq_inc++;
							$nickname = get_nickname_val($rows["company_name"], $rows["b2bid"]);

							$switchDate = "";
							if ($rows["billingSwitchToZeroWaste"] == 'Yes') {
								$switchDate = ($rows["date_of_bill_switch"] != '0000-00-00' && $rows["date_of_bill_switch"] != '1969-12-31') ? date("m/d/Y", strtotime($rows["date_of_bill_switch"])) : '';
							}

							$inv_due_date_color = "";
							$past_due = 0;
							if ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00") {
								// Create DateTime objects for the given date and today's date
								$given_date_obj = new DateTime($rows["invoice_due_date"]);
								$today_date_obj = new DateTime();

								// Calculate the difference between the two dates
								$interval = $today_date_obj->diff($given_date_obj);

								// Get the number of days from the interval
								$past_due = $interval->days;

								// Compare the given date with today's date
								if ($given_date_obj < $today_date_obj) {
									$inv_due_date_color = "color:red;";
									$past_due = $past_due * -1;
								} else {
									//$past_due = $past_due * -1;
								}
							}

							$payable_payment_terms_accepted_by_ucbzerowaste = "";
							$ch_qry = db_query("SELECT payable_payment_terms_accepted_by_ucbzerowaste from water_vendors_payable_contact where water_vendor_id='" . $rows["vendor_id"] . "' ORDER BY created_on DESC limit 1");
							while ($rows_ch = array_shift($ch_qry)) {
								$payable_payment_terms_accepted_by_ucbzerowaste = $rows_ch["payable_payment_terms_accepted_by_ucbzerowaste"];
							}

							$water_transaction_log_notes_dt = "";
							$ch_qry = db_query("SELECT `date` from water_transaction_log_notes where trans_id = '" . $rows["transid"] . "' ORDER BY `date` DESC limit 1");
							while ($rows_ch = array_shift($ch_qry)) {
								$water_transaction_log_notes_dt = $rows_ch["date"];
							}

					?>
							<td class="display_table"><?= $sr_inv++; ?></td>
							<td class="display_table">
								<a target="_blank" href="water_vendor_master_new.php?id=<?= $rows["vendor_id"] ?>&proc=View&flag=yes&compid=<?= $rows["b2bid"] ?>">
									<? echo $rows["Name"] . " - " . $rows["description"] . " - " . $rows['city'] . ", " . $rows['state'] . " " . $rows['zipcode']; ?>
								</a>
							</td>
							<td class="display_table">
								C: <?= $rows["payable_contact_name"] ?> <br>
								P: <?= $rows["payable_main_phone"] ?> <br>
								E: <?= $rows["payable_email"] ?>
							</td>

							<td bgcolor="<? echo $bgcolor; ?>" class="display_table"><a target="_blank" href="viewCompany.php?ID=<? echo $rows["b2bid"]; ?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><? echo $nickname;?></a></td>
							<td class="display_table"><? if ($rows["invoice_date"] != "") {
															echo date("M Y", strtotime($rows["invoice_date"]));
														} ?></td>
							<td class="display_table"><? echo $rows["invoice_number"]; ?></td>
							<td class="display_table">
								<? if ($rows["scan_report"] != "") {
									$tmppos_1 = strpos($rows["scan_report"], "|");
									if ($tmppos_1 != false) {
										$elements = explode("|", $rows["scan_report"]);
										for ($i = 0; $i < count($elements); $i++) {	?>
											<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'>
												<font size="1">View</font>
											</a><br />
										<? }
									} else {
										?>
										<a target="_blank" href='water_scanreport/<? echo $rows["scan_report"]; ?>'>
											<font size="1">View Attachments</font>
										</a>
								<? }
								} ?>
							</td>

							<? $unique_count = $row;

							$invoice_due_date = ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00") ? date('m/d/Y', strtotime($rows["invoice_due_date"])) : '';
							?>
							<td class="display_table"><?= date('m/d/Y', strtotime($rows["new_invoice_date"])); ?></td>
							<td class="display_table" style="<?= $inv_due_date_color ?>"><?= ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00") ? date('m/d/Y', strtotime($rows["invoice_due_date"])) : '' ?></td>
							<td class="display_table"><? echo $past_due; ?></td>
							<td class="display_table">$<? echo number_format($rows["amt"], 2); ?></td>

							<td class="display_table" id="payment_method_td-<?= $unique_count; ?>"><? echo $payable_payment_terms_accepted_by_ucbzerowaste; ?></td>

							<td class="display_table" id="ar_status_td-<?= $unique_count; ?>"><? if($rows['made_payment'] == 1 ) { echo 'Yes'; } else { echo 'No'; } ?></td>

							<?php
							$log_notes = $rows['vendor_payment_log_notes'];
							$display_anch = "none";
							if($log_notes !="" ){
								$display_anch = "revert";
							}
							?>
							<td class="display_table" id="log_notes_date_td-<?= $unique_count; ?>">
								<span><?php echo $water_transaction_log_notes_dt; ?></span>
								<br><br><a class="log_note_history" style="display:<?php echo $display_anch; ?>" id='show_date_history_btn_<?= $unique_count ?>' onclick="javascript:show_all_log_notes(<?= $rows['transid'] ?>,'<?= $unique_count; ?>','date')">Log Notes Date History</a>
							</td>
							<td class="display_table" id="log_notes_td-<?= $unique_count; ?>">
								<span><?php echo $log_notes; ?></span>
								<br><br><a class="log_note_history" style="display:<?php echo $display_anch; ?>" id='show_notes_history_btn_<?= $unique_count ?>' onclick="javascript:show_all_log_notes(<?= $rows['transid'] ?>,'<?= $unique_count; ?>','notes')">Log Notes History</a>
							</td>
							<td class="display_table">
								<input type='button' id='agtempl<?= $unique_count; ?>' name='send_tmpl' value='Select Template' onclick='open_template_vendors_AR(1,<?= $rows["warehouse_id"] ?>,<?= $rows["transid"]; ?>,<?= $rows["vendor_id"]; ?>, "<?= $unique_count; ?>")' />
							</td>

							<td class="display_table">
								<a style="cursor:pointer" id='atag_vendor_payment_div<?= $unique_count ?>' onclick='javascript:show_vendor_payment_div(<?= $unique_count ?>)'><u>View</u></a>

								<div id="payment_no_div_edit-<?= $row; ?>" style="display:none;">
									<form id="vendor_edit_form_each_row-<?= $unique_count; ?>" name="vendor_edit_form_each_row">
										<div id="editSectionTbl_<?= $unique_count ?>">

											<table width="300px">
												<tr>
													<td style="background-color: #ABC5DF;" align="center" colspan="2">Vendor Payment Report
													</td>
												</tr>

												<tr>
													<td class="display_table">Made or Received Payment?</td>
													<td class="display_table">
														<input type="checkbox" name="made_payment" id="made_payment" value="1" <? if ($rows['made_payment'] == 1) {
																																	echo 'checked';
																																} ?>>
													</td>
												</tr>

												<tr>
													<td class="display_table">Paid/Received by:</td>
													<td class="display_table">
														<input type="text" name="paid_by" id="paid_by" value="<?= $rows["paid_by"] ?>">
													</td>
												</tr>

												<tr>
													<td class="display_table">Paid/Received date:</td>
													<td class="display_table">
														<input type="text" name="paid_date" id="paid_date<?= $unique_count; ?>" value="<?= $rows["paid_date"] != "" ? date('m/d/Y', strtotime($rows["paid_date"])) : ""; ?>">

														<a href="#" onclick="openCalendar('paid_date<?= $unique_count; ?>'); return false;" name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
													</td>
												</tr>

												<tr>
													<td class="display_table">Payment Method:</td>
													<td class="display_table">
														<input type="text" name="payment_method" id="payment_method" value="<?= $rows["payment_method"] ?>">
													</td>
												</tr>

												<tr>
													<td class="display_table">Payment proof file:</td>
													<td class="display_table">
														<input type="file" name="payment_proof_file[]" id="payment_proof_file" multiple onchange="GetFileSize()">
														<input type="hidden" name="hdnWatrTrnstnId" value="<? echo $rows["transid"] ?>">
														<input type="hidden" name="hdnvendrId" value="<? echo $rows["vendor_id"] ?>">
														<input type="hidden" name="hdnInvcNo" value="<? echo $rows["invoice_number"] ?>">
														<input type="hidden" name="hdnInvcVendorEmail" value="<? echo $rows["contact_email"] ?>">
														<input type="hidden" name="vnumrows" value="<?= $vnumrows ?>">
														<input type="hidden" name="vendors_dd" name="vendors_dd" value="<? echo $_REQUEST["vendors_dd"]; ?>">
														<input type="hidden" name="comp_sel" name="comp_sel" value="<? echo $_REQUEST["comp_sel"]; ?>">
														<input type="hidden" name="ddMadePayment" value="<? echo $_REQUEST["ddMadePayment"]; ?>">
														<input type="hidden" name="vendorpagename" value="UCBZeroWaste_Vendors_AP_new.php">
														<input type="hidden" name="common_vendor_id" value="<?= $common_vendor_id; ?>">
														<input type="hidden" name="edit_report" value="yes" />
													</td>
												</tr>

												<tr>
													<td class="display_table">Log Notes:</td>
													<td class="display_table">
														<input type="text" name="vendor_payment_log_notes" id="vendor_payment_log_notes" value="<?= $rows["vendor_payment_log_notes"] ?>">
													</td>
												</tr>

												<tr>
													<td colspan="2" class="display_table">
														<input type="button" name="btnUpdateVendrRpt" id="btnUpdateVendrRpt_<?= $unique_count; ?>" class="btnUpdateVendrRpt" onclick="update_vendor_report('<?= $unique_count; ?>')" value="Save">

														<a class="btnEdit" id="btnCancelSectionClose_<?= $unique_count ?>" onclick="cancelSectionClose('<?= $unique_count; ?>')">Cancel</a>
													</td>
												</tr>
											</table>
										</div>
									</form>
								</div>

								<div id="payment_no_div_view-<?= $unique_count; ?>" style="display:none;">
									<div id="viewSectionTbl_<?= $unique_count ?>">
										<table width="300px">
											<tr>
												<td style="background-color: #ABC5DF;" align="center" colspan="2">Vendor Payment Report
													<a style="cursor:pointer" id="btnEditSectionOpen_<?= $unique_count ?>" onclick="editSectionOpen('<?= $unique_count ?>')"><u>Edit</u></a>
												</td>
											</tr>

											<tr>
												<td class="display_table" width="150px">Made or Received Payment?</td>
												<td class="display_table" width="150px">
													<span id='view_made_payment<?= $unique_count; ?>'><? echo ($rows['made_payment'] == 1) ? 'Yes' : 'No'; ?></span>
												</td>
											</tr>

											<tr>
												<td class="display_table">Paid/Received by:</td>
												<td class="display_table">
													<span id='view_paid_by<?= $unique_count; ?>'><?= $rows["paid_by"] ?></span>
												</td>
											</tr>

											<tr>
												<td class="display_table">Paid/Received date:</td>
												<td class="display_table">
													<span id='view_paid_date<?= $unique_count; ?>'><?= $rows["paid_date"] != "" ? date('m/d/Y', strtotime($rows["paid_date"])) : ""; ?></span>
												</td>
											</tr>

											<tr>
												<td class="display_table">Payment Method:</td>
												<td class="display_table">
													<span id='view_payment_method<?= $unique_count; ?>'><?= $rows["payment_method"] ?></span>
												</td>
											</tr>

											<tr>
												<td class="display_table">Payment proof file:</td>
												<td class="display_table">
													<span id='view_payment_proof_file<?= $unique_count; ?>'><? if ($rows["payment_proof_file"] != "") {
																												$tmppos_1 = strpos($rows["payment_proof_file"], "|");
																												if ($tmppos_1 != false) {
																													$elements = explode("|", $rows["payment_proof_file"]);
																													for ($i = 0; $i < count($elements); $i++) {	?>
																	<a target="_blank" href='water_payment_proof/<? echo $elements[$i]; ?>'>
																		<font size="1">View</font>
																	</a>
																	<br>
																<? }
																												} else {
																?>
																<a target="_blank" href='water_payment_proof/<? echo $rows["payment_proof_file"]; ?>'>
																	<font size="1">View Attachments</font>
																</a>
																<br>
														<?
																												}
																											}
														?></span>
												</td>
											</tr>

											<tr>
												<td class="display_table">Log Notes:</td>
												<td class="display_table">
													<span id='view_vendor_payment_log_notes<?= $unique_count; ?>'><?= $rows["vendor_payment_log_notes"] ?></span>
												</td>
											</tr>

										</table>
									</div>
								</div>

							</td>
							<?
							$row++;
							$isRec = 'y';
							?>
							</tr>
					<?
							$MGarray[] = array(
								'b2bid' => $rows["b2bid"],
								'company_contact' => $rows["company_contact"],
								'company_phone' => $rows["company_phone"],
								'company_email' => $rows["company_email"],
								'switchDate' => $switchDate,
								'vendor_ap_contact' => "C: " . $rows["payable_contact_name"] . "<br> P: " . $rows["payable_main_phone"] . "<br> E: " . $rows["payable_email"],
								'invoice_date' => $rows["invoice_date"],
								'invoice_number' => $rows["invoice_number"],
								'invoice_due_date' => $invoice_due_date,
								'inv_due_date_color' => $inv_due_date_color,
								'invoice_age' => $past_due,
								'company_terms' => $rows["company_terms"],
								'new_invoice_date' => $rows["new_invoice_date"],
								'no_invoice_due_marked_on' => $rows["no_invoice_due_marked_on"],
								'amt' => $rows["amt"],
								'made_payment' => $rows["made_payment"],
								'vendor_preferred_payment_by' => $payable_payment_terms_accepted_by_ucbzerowaste,
								'payment_method' => $rows["payment_method"],
								'paid_date' => $rows["paid_date"],
								'payment_proof_file' => $rows["payment_proof_file"],
								'receivable_portal_link' => $rows["receivable_portal_link"],
								'paid_by' => $rows["paid_by"],
								'tranlogdate' => $rows['paid_date'],
								'ar_status' => $rows['ar_status'],
								'receivable_notes' => $rows['receivable_notes'],
								'transid' => $rows["transid"],
								'vendor_id' => $rows["vendor_id"], 'scan_report' => $rows["scan_report"],
								'water_transaction_log_notes_dt' => $water_transaction_log_notes_dt, 'log_notes' => $log_notes,
								'amt' => $rows["amt"], 'nickname' => $nickname, 'vnumrows' => $vnumrows, 'vendor_name' => $rows["Name"] . " - " . $rows["description"] . " - " . $rows['city'] . ", " . $rows['state'] . " " . $rows['zipcode']
							);
						}
					}
					$arcount++;
					//}

					$_SESSION['exportArray'] = $MGarray;
					?>
				</table>
				<div>
					<font color="red">"END OF REPORT"</font>
				</div>
			</div>
		<?php
		}
		//echo "<pre>"; print_r($_SESSION['exportArray']); echo "</pre>";
		?>

</body>

</html>