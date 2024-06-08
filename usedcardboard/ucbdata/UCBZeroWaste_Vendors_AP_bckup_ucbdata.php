<?
/*
File Name: UCBZeroWaste_Vendors_AP.php
Page created By: Amarendra
Page created On: 27-03-2023
Last Modified On: 
Last Modified By: Ashiq
Change History:
Date           By            Description
================================================================================================
27-03-23      Ashiq     This file is created for the testing purpose. Once the file runs 
							as per the requirement, this file will be replace the existing 
							file named UCBZeroWaste_Vendors_AP.php. 
							
================================================================================================
*/

session_start(); 
require_once("inc/header_session.php");
require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");
	
$swhere_condition = ""; 
if (isset($_REQUEST["comp_sel"])) {
	if ($_REQUEST["comp_sel"] != "All") {
		$swhere_condition = " and loop_warehouse.id = " . $_REQUEST["comp_sel"] ;
	}	
	$comp_sel = $_REQUEST["comp_sel"];
}else{
	$comp_sel = "All";
}
if (isset($_REQUEST["vendors_dd"])) {
	$vendors_dd = $_REQUEST["vendors_dd"];
}else{
	$vendors_dd = 'All';
}
$receivables = $payables = $ddMadePayment = $date_from = $date_to = "";

$payables = 'yes';

if(isset($_REQUEST['ddMadePayment'])){
	$ddMadePayment = $_REQUEST['ddMadePayment'];
}else{
	$ddMadePayment = 'All';
}

if( $_REQUEST["date_from"] !="" && $_REQUEST["date_to"] != ""){
	//$date_from	= date("2022-01-01");
	//$date_to	= date("Y-m-d");
	$date_from	= $_REQUEST["date_from"];
	$date_to	= $_REQUEST["date_to"];
	$swhere_condition .= " AND invoice_date BETWEEN '" . Date("Y-m-d" , strtotime($date_from)) . "' and '" . Date("Y-m-d" , strtotime($date_to."+1 day")) . "'";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Vendor A/P Aging Report.</title>
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
.white_content {
		display: none;
		position: absolute;
		padding: 5px;
		border: 2px solid black;
		background-color: white;
		z-index:1002;
		overflow: auto;
	}
	.notes_tbl th{
		white-space:nowrap;
	}
	.notes_tbl,.notes_tbl th, .notes_tbl td {
		border:solid 1px #000;
		border-collapse: collapse;
		padding:5px 8px;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>	
	function displarepsorteddata(comp_id, vendor_id, dmadepayment, dtfrom, dtto, receives, payables, columnno, sortflg){
		
		document.getElementById("div_general_forrep").innerHTML  = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
			
		}else{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){	
				document.getElementById("div_general_forrep").innerHTML = xmlhttp.responseText; 
			}
		}

		xmlhttp.open("GET","UCBZeroWaste_Vendors_AP_child.php?comp_sel=" + comp_id + "&vendors_dd=" + vendor_id + "&ddMadePayment=" + dmadepayment + "&date_from=" + dtfrom + "&date_to=" + dtto + "&receivables=" + receives + "&payables="+ payables + "&columnno=" + columnno + "&sortflg=" + sortflg ,true);	
		xmlhttp.send();
	}

	function GetFileSize() { 
        var fi = document.getElementById('payment_proof_file'); // GET THE FILE INPUT.

        // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
        if (fi.files.length > 0) {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fi.files.length - 1; i++) {
				var filenm = fi.files.item(i).name;
				
				if (filenm.indexOf("#") > 0){
					alert("Remove # from Scan file and then upload file!");
					document.getElementById("payment_proof_file").value = "";
				}
		if (filenm.indexOf("\'") > 0){
			alert("Remove \' from "+filenm+" file and then upload file!");
			document.getElementById("payment_proof_file").value = "";
		}
				
                var fsize = fi.files.item(i).size;      // THE SIZE OF THE FILE.
				if (Math.round(fsize / 1024) > 8000)
				{
					alert("Only files with 8mb is allowed.");	
					document.getElementById("payment_proof_file").value = "";
				}
            }
        }
    } 

    function editSectionOpen(rec_id){ 
    	var editSectionTbl = document.getElementById("editSectionTbl_"+rec_id);
    	var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_"+rec_id);
    	if(editSectionTbl != ''){
    		editSectionTbl.style.display = 'revert';
    		btnEditSectionOpen.style.display = 'none';
    	}
    }

    function cancelSectionClose(rec_id){ 
    	var editSectionTbl = document.getElementById("editSectionTbl_"+rec_id);
    	var btnCancelSectionClose = document.getElementById("btnCancelSectionClose_"+rec_id);
    	var btnEditSectionOpen = document.getElementById("btnEditSectionOpen_"+rec_id);
    	if(editSectionTbl != ''){
    		editSectionTbl.style.display = 'none';
    		btnEditSectionOpen.style.display = 'revert';
    	}

    }

	function update_vendor_report(rec_id){
		var formElement = document.getElementById('vendor_edit_form_each_row-'+rec_id);
		var all_data=new FormData(formElement);
		//console.log(all_data);
		$.ajax({
			url:'update_vendor_ap_ar_data.php',
			type:'post',
			data:all_data,
			datatype:'json',
            contentType: false,
			processData: false,
			async:false,
			beforeSend: function () {
				$("#btnUpdateVendrRpt_"+rec_id).attr('disabled',true);
				//$('#save-task').attr('disabled',true);
				//$('#save-task').prev('.spinner').removeClass('d-none');
			},
			success:function(res){
				var res=JSON.parse(res);
				if(res.updated==1){
				var data=res.data;
				var made_payment=data.made_payment=="1" ? 'Yes': "No" ; 
				
				$('#made_payment_td-'+rec_id).text(made_payment);
				$('#payment_method_td-'+rec_id).text(data.payment_method);
				$('#paid_date_td-'+rec_id).text(data.paid_date);
				$('#paid_date_td2-'+rec_id).text(data.paid_date);
				$('#paid_by_td-'+rec_id).text(data.paid_by);	
				if (data.payment_proof_file!= "") {
					var file_tag="";
					if (data.payment_proof_file.indexOf("|")>0){ 	
						var elements = data.payment_proof_file.split("|");
						for (i = 0; i < elements.length; i++) {	
							file_tag+=`<a target="_blank" href="water_payment_proof/${elements[i]}"><font size="1">View</font></a><br>`;
						}
					}else {	
						file_tag+=`<a target="_blank" href="water_payment_proof/${data.payment_proof_file}"><font size="1">View</font></a>`;	
					}
					$('#payment_proof_file_td-'+rec_id).html(file_tag);
				}
				data.made_payment=="" && data.payment_method=="" ? $("#editSectionTbl_"+rec_id).css('display','revert'): $("#editSectionTbl_"+rec_id).css('display','none') ;
				data.made_payment=="" && data.payment_method=="" ? $("#btnEditSectionOpen_"+rec_id).css('display','none'): $("#btnEditSectionOpen_"+rec_id).css('display','revert') ;
				}else{
					alert("Transaction Log Notes can't be blank to update data!");
				}
			},
			complete:function(){
				$("#btnUpdateVendrRpt_"+rec_id).attr('disabled',false);
			}
		});
		
		return false;
	}
	function f_getPosition (e_elemRef, s_coord) {
			var n_pos = 0, n_offset,
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
		//----------Open send template popup--------------------------------------
		function show_all_notes(type,vendor_id,cnt)
		{
			var selectobject = document.getElementById("show_all_btn_"+cnt);				
			var n_left = f_getPosition(selectobject, 'Left');
			var	n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left= (n_left - 400 ) + 'px';
			document.getElementById('light').style.top = n_top + 40 + 'px';

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>"+xmlhttp.responseText;
					document.getElementById('light').style.display='block';
				}
			}

			xmlhttp.open("GET","update_vendor_ap_ar_data.php?get_all_notes=yes&type="+type+"&vendor_id="+vendor_id,true);
			xmlhttp.send();
		}

		
</script>	
	<LINK rel='stylesheet' type='text/css' href='one_style.css' >
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>	
	</head>

<body>
	<? include("inc/header.php"); ?>

<div class="main_data_css">
	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">Vendor A/P Aging Report.
			<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
				<span class="tooltiptext">Vendor A/P Aging Report.</span>
			</div>		
			<div style="height: 13px;">&nbsp;</div>				
		</div>
	</div>
	
		<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		var cal3xx = new CalendarPopup("listdiv");
		cal3xx.showNavigationDropdowns();
	</script>
	
	<form method="GET" name="frmwater_report_internal" action="UCBZeroWaste_Vendors_AP.php">
		
		<table border="0" cellspacing="1" cellpadding="1">
			<tr >
				<td align="left" colspan="5">
					<b>Select Mandatory Filters:</b>
				</td>
			</tr>				
			<tr >
				<td class="" align="left">
					Vendor: <br>
				
					<select id="vendors_dd" name="vendors_dd" style="width: 230px;">
						<option value="All" <?=(($vendors_dd == 'All')? "selected":"");?>>All</option>
					<?
					$vendor_qry = "SELECT * FROM water_vendors where active_flg = 1 order by Name, city, state, zipcode";
					$query = db_query($vendor_qry, db());
					$vender_nm = "";
					//	
					while($vendor_row = array_shift($query))
					{
						$vender_nm = $vendor_row['Name']. " - ". $vendor_row["description"] . " - ". $vendor_row['city']. ", ". $vendor_row['state']. " ". $vendor_row['zipcode'];
					?>
						<option value="<? echo $vendor_row['id']; ?>" <?=(($vendors_dd == $vendor_row['id'])? "selected":"");?>>
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
								echo "<option value='". $data["id"] ."' "; 
								if ($_REQUEST["comp_sel"] == $data["id"]) { echo " selected "; }
								echo ">" . get_nickname_val($data["company_name"],$data["b2bid"]) . "</option>";
							}
						?>
					</select>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td align="left"> 
					Has UCBZW Paid the Invoice Status:<br>
					<select id="ddMadePayment" name="ddMadePayment" style="width: 200px;">
						<option value="All" <?=(($ddMadePayment == 'All')? "selected":"");?>>All</option>
						<option value="1" <?=(($ddMadePayment == '1')? "selected":"");?>>Yes</option>
						<option value="0" <?=(($ddMadePayment != 'ALL' && $ddMadePayment != '1') ? "selected":"");?>>No</option>
					</select>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td align="left"> 
					Filter by Invoice Due Date:<br>
				    <input type="text" name="flt_inv_due_dt" id="flt_inv_due_dt" size="10" value="<?=(isset($_REQUEST['flt_inv_due_dt']) ? $_REQUEST['flt_inv_due_dt'] : "1/1/2021"); ?>" > 
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
				<td >
					Service End Date From:
						<input type="text" name="date_from" id="date_from" size="10" value="<?=(isset($_REQUEST['date_from'])? $_REQUEST['date_from'] : "");?>"> 
						<a href="#" onclick="cal2xx.select(document.frmwater_report_internal.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
					Service End Date To:
						<input type="text" name="date_to" id="date_to" size="10" value="<?=(isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : ""); ?>" > 
						<a href="#" onclick="cal3xx.select(document.frmwater_report_internal.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td>
					 <input type="submit" id="btnrep" name="btnrep"  value="Run Report"/>
				</td>
			</tr>	
		</table> 
	</form>
	
	<?
	
	if (isset($_REQUEST["btnrep"])) {
		
	?>
	<div>
		<a href="UCBZeroWaste_Vendors_AP_AR_Excel.php?from=AP"><span class="">"Click Here"</span></a> to export the table.<br>
		<span><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report, before using the download option.</i></span>
	</div>

    <?

	$termsOutputData = array();
	$healthOutputData = array();
	
	$comp_where_condition = "";
	if (isset($_REQUEST["comp_sel"])) {
		if ($_REQUEST["comp_sel"] != "All") {
			$comp_where_condition = " and water_transaction.company_id = " . $_REQUEST["comp_sel"] ;
		}	
	}
	
    if (isset($_REQUEST["vendors_dd"])) {
        if ($_REQUEST["vendors_dd"] != "All") {
            $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 and vendor_id = '".$_REQUEST["vendors_dd"]."' $comp_where_condition group by vendor_id";
        }else{
            $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 $comp_where_condition group by vendor_id";
            
        }
    }else{
        $vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id where make_receive_payment = 1 $comp_where_condition group by vendor_id";
    }
	//echo $vendorsQry . "<br>";

    $v_res = db_query($vendorsQry,db());
    $whrMadePayConditn = '';
    if(isset($_REQUEST['ddMadePayment']) && $_REQUEST['ddMadePayment'] != 'All'){
        $whrMadePayConditn = ' and water_transaction.made_payment = '.$_REQUEST['ddMadePayment'];
    }
	$whr_flt_inv_due_dt = "";
	if(isset($_REQUEST['flt_inv_due_dt']) && $_REQUEST['flt_inv_due_dt'] != ''){
		$whr_flt_inv_due_dt = ' and (invoice_due_date >= "'.date("Y-m-d",strtotime($_REQUEST['flt_inv_due_dt'])).'" OR invoice_due_date is NULL)';
	}
	
	$companyTermsQry = db_query("SELECT DISTINCT company_terms FROM `loop_warehouse`", db());
	$termsArray = array_filter(array_column($companyTermsQry,"company_terms"));

    $totalAmount = $totalInvoice = $totalWithINRangeAmount = $totalnoduedate = $countWithIN = $countnoduedate = $total1RangeAmount = $count1 = $total31RangeAmount = $count31 = $total61RangeAmount = $count61 = $total90RangeAmount = $count90 = 0;

	$healthtotalAmount = $healthtotalInvoice = $healthtotalActiveRangeAmount = $countActive = $totalEscalatedRangeAmount = $countEscalated = $totalp2pRangeAmount = $countp2p = $totalpaidRangeAmount = $countPaid = $totalNoActionRangeAmount = $countNoAction = 0;
	
	foreach ($v_res as $termHealth) {
		//invoice_date
		$TermsQuery = "SELECT CASE WHEN (DATEDIFF(CURDATE(), invoice_due_date) < 1 ) THEN 'Within Terms' 
		WHEN (invoice_due_date is null) THEN 'Without Due Date' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 1 AND 30 THEN '1-30 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 31 AND 60 THEN '31-60 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) BETWEEN 61 AND 90 THEN '61-90 Days Past Due' 
		WHEN DATEDIFF(CURDATE(), invoice_due_date) > 90 THEN '90+ Days Past Due' ELSE 'Not Categorized' END AS DueRange, invoice_due_date, SUM(amount) AS TotalAmount 
		FROM water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
		left join water_vendors_payable_contact on water_transaction.vendor_id = water_vendors_payable_contact.water_vendor_id
		where make_receive_payment = 1 and vendor_id='".$termHealth["vendor_id"]."' ".$whrMadePayConditn." ".$whr_flt_inv_due_dt." $swhere_condition group by DueRange, company_id, water_transaction.id having sum(amount) <= 0 order by DueRange DESC";
		//echo $TermsQuery . "<br>";
		$TermsResult = db_query($TermsQuery,db());

		if(!empty($TermsResult)){
			foreach($TermsResult as $TermsData){				
				if ($TermsData['DueRange'] != ""){
					//echo "DueRange - " . $TermsData['DueRange'] . "|" . $TermsData['TotalAmount'] . "<br>";
					
					//echo $TermsQuery . "<br>";
					
					$totalAmount += $TermsData['TotalAmount'];
					$totalInvoice = $totalInvoice + 1;

					switch($TermsData['DueRange']){
						case "Within Terms":
							$totalWithINRangeAmount += $TermsData['TotalAmount'];
							$countWithIN = $countWithIN + 1;
							$termsOutputData["Within Terms"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $countWithIN,
								'amount' => $totalWithINRangeAmount
							];
						break;
						case "Without Due Date":
							$totalnoduedate += $TermsData['TotalAmount'];
							$countnoduedate = $countnoduedate + 1;
							$termsOutputData["Without Due Date"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $countnoduedate,
								'amount' => $totalnoduedate
							];
						break;
						case "1-30 Days Past Due":
							$total1RangeAmount += $TermsData['TotalAmount'];
							$count1 = $count1 + 1;
							$termsOutputData["1-30 Days Past Due"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $count1,
								'amount' => $total1RangeAmount
							];
						break;
						case "31-60 Days Past Due":
							$total31RangeAmount += $TermsData['TotalAmount'];
							$count31 = $count31 + 1;
							$termsOutputData["31-60 Days Past Due"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $count31,
								'amount' => $total31RangeAmount
							];
						break;
						case "61-90 Days Past Due":
							$total61RangeAmount += $TermsData['TotalAmount'];
							$count61 = $count61 + 1;
							$termsOutputData["61-90 Days Past Due"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $count61,
								'amount' => $total61RangeAmount
							];
						break;
						case ">90 Days Past Due":
							$total90RangeAmount += $TermsData['TotalAmount'];
							$count90 = $count90 + 1;
							$termsOutputData[">90 Days Past Due"] = [
								'terms' => $TermsData['DueRange'],
								'invoices' => $count90,
								'amount' => $total90RangeAmount
							];
						break;
					}
				}
			}
		}

		//CASE WHEN ar_status = 'active' THEN 'Active' WHEN ar_status = 'escalated' THEN 'Escalated' WHEN ar_status = 'p2p' THEN 'P2P' WHEN ar_status = 'paid' THEN 'Paid' ELSE 'No Action Needed' END AS Status,
		$healthQuery = "SELECT CASE WHEN made_payment = 0 THEN 'Active' WHEN made_payment = 1 THEN 'Paid' END AS Status, SUM(amount) AS TotalAmount 
		FROM water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
		left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
		where make_receive_payment = 1 and vendor_id='".$termHealth["vendor_id"]."' ".$whrMadePayConditn." ".$whr_flt_inv_due_dt." $swhere_condition group by Status, company_id, water_transaction.id having sum(amount) <= 0 order by made_payment desc";

		$healthResult = db_query($healthQuery,db());

		if(!empty($healthResult)){
			foreach($healthResult as $healthData){				
			
				$healthtotalAmount += $healthData['TotalAmount'];
				$healthtotalInvoice = $healthtotalInvoice + 1;

				switch($healthData['Status']){
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
					default :
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
	}
    
    ?>

	<div id="ARTearmBreakdownTable" class="ARTearmBreakdownTable" style="padding: 25px 0;">
		<table width="50%" border="1" cellspacing="1" cellpadding="2">
			<thead style="background-color: #000;color: #fff;text-align: center;">
				<tr>
					<td colspan="4"><strong>A/P Terms Breakdown</strong></td>
				</tr>
				<tr>
					<td width="30%">Terms</td>
					<td width="20%">Invoices</td>
					<td width="30%">Amount ($)</td>
					<td width="20%">% of A/P</td>
				</tr>
			</thead>
			<tbody style="text-align: right;">
				<?php
				foreach ($termsOutputData as $termValue) {
				?>
				<tr>
					<td style="text-align: center;"><?=$termValue['terms']?></td>
					<td><?=$termValue['invoices']?></td>
					<td><?="$".number_format($termValue['amount'],2)?></td>
					<td><?=number_format(($termValue['amount']/$totalAmount)*100,2)."%"?></td>
				</tr>
				<?}?>
				<tr style="font-weight: 700;">
					<td>Totals</td>
					<td><?=$totalInvoice?></td>
					<td><?="$".number_format($totalAmount,2)?></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div id="healthTable" class="healthTable" style="padding: 25px 0;">
		<table width="50%" border="1" cellspacing="1" cellpadding="2">
			<thead style="background-color: #000;color: #fff;text-align: center;">
				<tr>
					<td colspan="4"><strong>A/P Health</strong></td>
				</tr>
				<tr>
					<td width="30%">Status</td>
					<td width="20%">Invoices</td>
					<td width="30%">Amount ($)</td>
					<td width="20%">% of A/P</td>
				</tr>
			</thead>
			<tbody style="text-align: right;">
				<?php
				foreach ($healthOutputData as $healthValue) {
				?>
				<tr>
					<td style="text-align: center;"><?=$healthValue['terms']?></td>
					<td><?=$healthValue['invoices']?></td>
					<td><?="$".number_format($healthValue['amount'],2)?></td>
					<td><?=number_format(($healthValue['amount']/$healthtotalAmount)*100,2)."%"?></td>
				</tr>
				<?}?>
				<tr style="font-weight: 700;">
					<td>Totals</td>
					<td><?=$healthtotalInvoice?></td>
					<td><?="$".number_format($healthtotalAmount,2)?></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="light" class="white_content"></div>
	<div id="fade" class="black_overlay"></div>
	<div id="div_general_forrep" name="div_general_forrep" >
	
	<table width="60%" border="0" cellspacing="1" cellpadding="1">
		
    <tr class="display_maintitle" style="background-color: #000;color: #fff;">
			<td>Sr.No </td>
			<td width="290px">Vendor Name&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 1, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>',1, 2);" ><img src="images/sort_desc.png"  width="5px;" height="10px;"></a>
			</td>	
			<td>Vendor A/P Contact</td>	
			<td>UCBZeroWaste Client Name&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 2, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 2, 2);" ><img src="images/sort_desc.png"  width="5px;" height="10px;"></a>
			</td>
			<td>Client Contact</td>
			<td>Billing Switch Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 4, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 4, 2);" ><img src="images/sort_desc.png"  width="5px;" height="10px;"></a>
			</td>

			<td>Service End Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 5, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 5, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>
			<td>Invoice Number&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 6, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>',6, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>

			<td>Scan of Invoice</td>

			<td>Terms&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 7, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 7, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>

			<td>Invoice Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 8, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 8, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Invoice Due Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 9, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 9, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Invoice Age&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 10, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 10, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>
			
			<td>Invoice Amount&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 3, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 3, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Made Payment?&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 11, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 11, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Payment Method&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 12, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 12, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>
			
			<td>Payment Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 13, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 13, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>
			
			<td>Payment Confirmation&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 14, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 14, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Vendor Portal&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 15, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 15, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>

			<td>Transaction Log Notes&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 16, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 16, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>

			<td>Transaction Log Date&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 17, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 17, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
			</td>

			<td>Special Notes:&nbsp;
				<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 19, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 19, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
			</td>
			
			<? if(isset($_REQUEST['ddMadePayment']) && $_REQUEST['ddMadePayment'] == 1 ){ ?>
				<td>&nbsp;</td>
			<? }else{ ?>
				<td>Update Vendor Report</td>
			<? } ?>

		</tr>
		<?	
		$arcount = 1;	
                $sr_inv=1;
		while ($data_row = array_shift($v_res)) {

			$vendorQry = "Select *, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
			left join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id
			where make_receive_payment = 1 and vendor_id='".$data_row["vendor_id"]."' ".$whrMadePayConditn."".$whr_flt_inv_due_dt." $swhere_condition group by company_id, 
			water_transaction.id having sum(amount) <= 0 order by water_transaction.company_id";
			//echo $vendorQry . "<br>";
			
			$v_res1 = db_query($vendorQry,db());			
			
			$vnumrows=tep_db_num_rows($v_res1);
			if($vnumrows>0)
			{
                $common_vendor_id=$data_row["vendor_id"];
				/*if($vnumrows>1)
				{
				?>
					<tr>
						<td class="display_table" rowspan="<?=$vnumrows?>"><?=$arcount?></td>
						<td class="display_table" rowspan="<?=$vnumrows?>">
							<a target="_blank" href="water_vendor_master_new.php?id=<?=$data_row["vendor_id"]?>&proc=View&flag=yes&compid=<?=$data_row["b2bid"]?>">
								<? echo $data_row["Name"]. " - ". $data_row["description"] . " - ". $data_row['city']. ", ". $data_row['state']. " ". $data_row['zipcode']; ?>
							</a>
						</td>
				<?
				}else{
				?>
					<tr>
						<td class="display_table"><?=$arcount?></td>
						<td class="display_table">
							<a target="_blank" href="water_vendor_master_new.php?id=<?=$data_row["vendor_id"]?>&proc=View&flag=yes&compid=<?=$data_row["b2bid"]?>">
							<? echo $data_row["Name"]. " - ". $data_row["description"] . " - ". $data_row['city']. ", ". $data_row['state']. " ". $data_row['zipcode']; ?>
							</a>
						</td>
				<?
				}
				*/
				$arcount++;
				
				$row = 1; $isRec = '';$unq_inc=0;
				while ($rows = array_shift($v_res1)) {
						$unq_inc++;
						$nickname = get_nickname_val($rows["company_name"], $rows["b2bid"]);
							
						$switchDate = "";
						if($data_row["billingSwitchToZeroWaste"] == 'Yes'){
							$switchDate = ($data_row["date_of_bill_switch"] != '0000-00-00' && $data_row["date_of_bill_switch"] != '1969-12-31') ? date("m/d/Y", strtotime($data_row["date_of_bill_switch"])) : '';
						}
				?>	
						<td class="display_table"><?= $sr_inv++; ?></td>
						<?php if($unq_inc==1){?>
							<td class="display_table" rowspan="<?=$vnumrows?>">
							<a target="_blank" href="water_vendor_master_new.php?id=<?=$data_row["vendor_id"]?>&proc=View&flag=yes&compid=<?=$data_row["b2bid"]?>">
								<? echo $data_row["Name"]. " - ". $data_row["description"] . " - ". $data_row['city']. ", ". $data_row['state']. " ". $data_row['zipcode']; ?>
							</a>
						</td>
						<? } ?>
						<td class="display_table">
							C: <?= $rows["payable_contact_name"] ?> <br>
							P: <?= $rows["payable_main_phone"] ?> <br>
							E: <?= $rows["payable_email"] ?>
						</td>
						<td bgcolor= "<? echo $bgcolor; ?>" class="display_table"><a target="_blank" href="viewCompany.php?ID=<? echo $rows["b2bid"];?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><? echo $nickname; //echo "<br>".$rows["company_id"]; ?></a></td>
						<td class="display_table">
							C: <?= $data_row["contact_name"] ?> <br>
							P: <?= $data_row["contact_phone"] ?> <br>
							E: <?= $data_row["contact_email"] ?>
						</td>
						<td class="display_table"><?= $switchDate ?></td>
						<td class="display_table"><? if ($rows["invoice_date"] != "") { echo date("m/d/Y", strtotime($rows["invoice_date"]));} ?></td>
			            <td class="display_table"><? echo $rows["invoice_number"]; ?></td>
						<td class="display_table">
							<? if ($rows["scan_report"] != "") {
								$tmppos_1 = strpos($rows["scan_report"], "|");
								if ($tmppos_1 != false)
								{ 	
									$elements = explode("|", $rows["scan_report"]);
									for ($i = 0; $i < count($elements); $i++) {	?>										
										<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'><font size="1">View</font></a><br />
									<?}
								}else {		
						?>
								<a target="_blank" href='water_scanreport/<? echo $rows["scan_report"]; ?>'><font size="1">View Attachments</font></a>
							<? }
							}?>
							

						</td>
                        <? $unique_count=$row."-".($arcount-1);?>
						<td class="display_table"><?= $data_row["vendor_terms"] ?></td>
						<td class="display_table"><?= date('m/d/Y', strtotime($rows["new_invoice_date"])); ?></td>
						<td class="display_table"><?= ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00") ? date('m/d/Y', strtotime($rows["invoice_due_date"])) : '' ?></td>
						<td class="display_table">-</td>
						<td class="display_table">$<? echo number_format($rows["amt"],2); ?></td>
						
						<td class="display_table" id="made_payment_td-<?=$unique_count;?>"><?php if($rows["made_payment"]=="1"){ echo 'Yes'; }else{ echo "No"; } ?></td>
						<td class="display_table" id="payment_method_td-<?=$unique_count;?>"><? echo $rows["payment_method"]; ?></td>
						<td class="display_table" id="paid_date_td-<?=$unique_count;?>"><? echo $rows["paid_date"]; ?></td>
						<td class="display_table" id="payment_proof_file_td-<?=$unique_count;?>">
						
							<? if ($rows["payment_proof_file"] != "") {
								$tmppos_1 = strpos($rows["payment_proof_file"], "|");
								if ($tmppos_1 != false){ 	
									$elements = explode("|", $rows["payment_proof_file"]);
									for ($i = 0; $i < count($elements); $i++) {	?>	
										<a target="_blank" href='water_payment_proof/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
										<br>
									<?}
								}else {		
							?>
								<a target="_blank" href='water_payment_proof/<? echo $rows["payment_proof_file"]; ?>'><font size="1">View Attachments</font></a>
								<br>
							<? 
								}
							}
							?>
						</td>
						<td class="display_table"><?=$rows["payable_portal_link"]?></td>
						<td class="display_table" id="paid_by_td-<?=$unique_count;?>"><?= $rows["paid_by"] ?></td>
						<td class="display_table" id="paid_date_td2-<?=$unique_count;?>"><?= date('m/d/Y', strtotime($rows["paid_date"])) ?></td>
						<td class="display_table">
						<?
							//$rows["payable_notes"];
							$vendor_id_comm = $rows["vendor_id"];
							$special_notes_count_qry=db_query("SELECT count(*) as total_notes from water_vendors_receivable_contact where water_vendor_id='$vendor_id_comm' AND receivable_notes!='' ",db()); 
							$special_notes_qry=db_query("SELECT receivable_notes from water_vendors_receivable_contact where water_vendor_id='$vendor_id_comm' AND receivable_notes!='' ORDER BY created_on DESC limit 1 ",db());
							echo array_shift($special_notes_qry)['receivable_notes'];
							if(array_shift($special_notes_count_qry)['total_notes'] > 1){?>
								<br><a style="cursor:pointer" id='show_all_btn_<?=$unique_count?>' onclick="javascript:show_all_notes('receivable',<?=$vendor_id_comm?>,'<?=$unique_count;?>')" >All Notes</a>
							<? } ?>
						</td>
						<td class="display_table">
						<div id="payment_no_div-<?=$row;?>">
							<form id="vendor_edit_form_each_row-<?=$unique_count;?>">
								<a class="btnEdit" style='<? echo $rows["made_payment"] == "" && $rows["payment_method"] == "" ? "display:none" : "display:revert"; ?>' id="btnEditSectionOpen_<?=$unique_count?>" onclick="editSectionOpen('<?=$unique_count?>')">Edit</a>
								<div id="editSectionTbl_<?=$unique_count?>" style='<? echo $rows["made_payment"] == "" && $rows["payment_method"] == "" ? "display:revert" : "display:none"; ?>'>
									<table>
										<tr>
											<td class="display_table">Made or Received Payment?</td>
											<td class="display_table">
												<input type="checkbox" name="made_payment" id="made_payment" value="1" <? if($rows['made_payment'] == 1 ){ echo 'checked'; } ?> >
											</td>
											<td class="display_table">Transaction Log Notes:</td>
											<td class="display_table">
												<input type="text" name="paid_by" id="paid_by" value="<?=$rows["paid_by"] ?>">
											</td>

											<td class="display_table">Payment Method:</td>
											<td class="display_table">
												<input type="text" name="payment_method" id="payment_method" value="<?=$rows["payment_method"] ?>">
											</td>
											<td class="display_table">Transaction Log Date:</td>
											<td class="display_table"><input type="text" name="paid_date" id="paid_date" value="<?= $rows["paid_date"]!="" ? date('m/d/Y', strtotime($rows["paid_date"])) : "";?>"></td>

											<td class="display_table">Payment proof file:</td>
											<td class="display_table">
												<input type="file" name="payment_proof_file[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
												<input type="hidden" name="hdnWatrTrnstnId" value="<? echo $rows["transid"] ?>">
												<input type="hidden" name="hdnvendrId" value="<? echo $rows["vendor_id"] ?>">
												<input type="hidden" name="hdnInvcNo" value="<? echo $rows["invoice_number"] ?>">
												<input type="hidden" name="hdnInvcVendorEmail" value="<? echo $data_row["contact_email"] ?>">
												<input type="hidden" name="vnumrows" value="<?=$vnumrows?>">
												<input type="hidden" name="vendors_dd" name="vendors_dd" value="<? echo $_REQUEST["vendors_dd"];?>">
												<input type="hidden" name="comp_sel" name="comp_sel" value="<? echo $_REQUEST["comp_sel"];?>">
												<input type="hidden" name="ddMadePayment" value="<? echo $_REQUEST["ddMadePayment"];?>">
												<input type="hidden" name="vendorpagename"  value="UCBZeroWaste_Vendors_AP.php">
												<input type="hidden" name="common_vendor_id"  value="<?= $common_vendor_id;?>">
												<input type="hidden" name="edit_report" value="yes"/>
											</td>
											<td class="display_table">
												<input type="button" name="btnUpdateVendrRpt" id="btnUpdateVendrRpt_<?= $unique_count; ?>" class="btnUpdateVendrRpt" onclick="update_vendor_report('<?=$unique_count; ?>')" value="save">
											</td>
											<td class="display_table">
												<a class="btnEdit" id="btnCancelSectionClose_<?=$unique_count?>" onclick="cancelSectionClose('<?=$unique_count;?>')" >Cancel</a>
											</td>
										</tr>
									</table>
								</div>
							</form>
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
						'switchDate' => $switchDate ,
						'invoice_date' => $rows["invoice_date"],
						'invoice_number' => $rows["invoice_number"],
						'company_terms' => $rows["company_terms"],
						'new_invoice_date' => $rows["new_invoice_date"],
						'no_invoice_due_marked_on' => $rows["no_invoice_due_marked_on"],
						'invoice_age' => '-',
						'amt' => $rows["amt"],
						'made_payment' => $rows["made_payment"],
						'payment_method' => $rows["payment_method"] ,
						'paid_date' => $rows["paid_date"],
						'payment_proof_file' => $rows["payment_proof_file"],
						'receivable_portal_link' => $rows["receivable_portal_link"],
						'paid_by' => $rows["paid_by"],
						'tranlogdate' => $rows['paid_date'] ,
						'receivable_notes' => $rows['receivable_notes'] ,
						'transid' => $rows["transid"],
						'vendor_id' => $rows["vendor_id"], 'scan_report' => $rows["scan_report"], 
						'amt' => $rows["amt"], 'nickname' => $nickname,'vnumrows' => $vnumrows, 'vendor_name' => $data_row["Name"]. " - ". $data_row["description"] ." - " . $data_row['city']. ", ". $data_row['state']. " ". $data_row['zipcode']);
				} 
				
				
				
				?>
				<? if($isRec == 'y') { ?>
					<tr>
					<td>&nbsp;</td>
						<!--<td class="display_table" colspan="10" align="right">&nbsp;</td>
						<td class="display_table" align="center">
							<input type="hidden" name="vnumrows" value="<?=$vnumrows?>">
							<input type="hidden" name="vendors_dd" name="vendors_dd" value="<? echo $_REQUEST["vendors_dd"];?>">
							<input type="hidden" name="comp_sel" name="comp_sel" value="<? echo $_REQUEST["comp_sel"];?>">
							<input type="hidden" name="ddMadePayment" value="<? echo $_REQUEST["ddMadePayment"];?>">
							<input type="hidden" name="vendorpagename"  value="UCBZeroWaste_Vendors_AP.php">
							<input type="hidden" name="common_vendor_id"  value="<?= $common_vendor_id;?>">
							<input type="submit" name="btnUpdateVendrRpt" id="btnUpdateVendrRpt" class="btnUpdateVendrRpt" value="Update Vendor Report">
							
						</td>-->
					</tr>
				<? } ?>
				</form>
			<?
			}
			
		}
		
		$_SESSION['exportAPArray'] = $MGarray;
	?>
	</table>
	<div ><font color="red">"END OF REPORT"</font></div>
	</div>
	<?php 
	}
		//echo "<pre>"; print_r($_SESSION['exportArray']); echo "</pre>";
	?>
	
</body>
</html>