<?php 
/*
File Name: UCBZeroWaste_Vendors_AP_AR_child.php
Page created By: Amarendra
Page created On: 27-03-2023
Last Modified On: 
Last Modified By: bhavna
Change History:
Date           By            Description
================================================================================================
10-06-2024      Bhavna    UCBZeroWaste Vendors AP new feedback		
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
	$date_from	= $_REQUEST["date_from"];
	$date_to	= $_REQUEST["date_to"];
	$swhere_condition .= " AND invoice_date BETWEEN '" . Date("Y-m-d" , strtotime($date_from)) . "' and '" . Date("Y-m-d" , strtotime($date_to."+1 day")) . "'";
}

$companyTermsQry = db_query("SELECT DISTINCT company_terms FROM `loop_warehouse`", db());
$termsArray = array_filter(array_column($companyTermsQry,"company_terms"));
	
?>
	<table width="60%" border="0" cellspacing="1" cellpadding="1">
		
	<tr class="display_maintitle">
		<td>Sr.No </td>
		<td width="290px">Vendor Name&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 1, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>',1, 2);" ><img src="images/sort_desc.png"  width="5px;" height="10px;"></a>
		</td>	
		<!-- <td>Vendor A/P Contact</td>-->
		<td>UCBZeroWaste Client Name&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 2, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 2, 2);" ><img src="images/sort_desc.png"  width="5px;" height="10px;"></a>
		</td>

		<td>Service Month&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 5, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 5, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
		</td>
		<td>Invoice Number&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 6, 1);" ><img src="images/sort_asc.png" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>',6, 2);" ><img src="images/sort_desc.png" width="5px" height="10px"></a>
		</td>

		<td>Scan of Invoice</td>

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

		<td>Vendor Preferred Payment&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 12, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 12, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
		</td>

	<td>Send Invoice&nbsp;
			<a href="javascript:void();"></a>
		</td>

		<td>Has UCBZW Paid the Invoice?&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 11, 1);" ><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?=$comp_sel;?>','<?=$vendors_dd;?>','<?=$ddMadePayment;?>', '<?=$date_from;?>', '<?=$date_to;?>', '<?=$receivables;?>', '<?=$payables;?>', 11, 2);" ><img src="images/sort_desc.png"  width="5px" height="10px"></a>
		</td>
		<td>Log Notes Date?&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', 20, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', 20, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
		</td>
		<td>Log Notes?&nbsp;
			<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', 21, 1);"><img src="images/sort_asc.png" width="5px" height="10px"></a>&nbsp;<a href="javascript:void();" onclick="displarepsorteddata('<?= $comp_sel; ?>','<?= $vendors_dd; ?>','<?= $ddMadePayment; ?>', '<?= $date_from; ?>', '<?= $date_to; ?>', '<?= $receivables; ?>', '<?= $payables; ?>', 21, 2);"><img src="images/sort_desc.png" width="5px" height="10px"></a>
		</td>
		<? if(isset($_REQUEST['ddMadePayment']) && $_REQUEST['ddMadePayment'] == 1 ){ ?>
			<td>&nbsp;</td>
		<? }else{ ?>
			<td>Vendor Payment Report</td>
		<? } ?>
			
	</tr>
		<?
		//
		
		if (isset($_REQUEST["vendors_dd"])) {
			if ($_REQUEST["vendors_dd"] != "All" && $_REQUEST["vendors_dd"] != "") {
				$vendorsQry = "Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id inner join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id where make_receive_payment = 1 and vendor_id = '".$_REQUEST["vendors_dd"]."' group by vendor_id";
				//$Vwhere_condition = " and vendor_id = " . $_REQUEST["vendors_dd"] ;
			
			}else{
				$vendorsQry = "
				Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id inner join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id where make_receive_payment = 1 group by vendor_id";
				
			}
		}else{
			$vendorsQry = "
			Select *, water_vendors.id as vid from water_transaction inner join water_vendors on water_transaction.vendor_id=water_vendors.id inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id inner join water_vendors_payable_contact on water_transaction.vendor_id=water_vendors_payable_contact.water_vendor_id where make_receive_payment = 1 group by vendor_id";
		}
		$vendorsQry_order = " order by invoice_due_date desc ";
		//
		if (isset($_REQUEST["columnno"])) {
			if ($_REQUEST["columnno"] == "1" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by Name asc";
				$vendorsQry_order = " order by Name asc";
			}	
			if ($_REQUEST["columnno"] == "1" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by Name desc";
				$vendorsQry_order = " order by Name desc";
			}	
			if ($_REQUEST["columnno"] == "2" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by company_name asc";
				$vendorsQry_order = " order by loop_warehouse.company_name asc";
			}	
			if ($_REQUEST["columnno"] == "2" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by company_name desc";
				$vendorsQry_order = " order by company_name desc";
			}	
			if ($_REQUEST["columnno"] == "3" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by amount asc";
				$vendorsQry_order = " order by amount asc";
			}	
			if ($_REQUEST["columnno"] == "3" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by amount desc";
				$vendorsQry_order = " order by amount desc";
			}	
			
			if ($_REQUEST["columnno"] == "4" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by date_of_bill_switch asc";
				$vendorsQry_order = " order by date_of_bill_switch asc";
			}	
			if ($_REQUEST["columnno"] == "4" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by date_of_bill_switch desc";
				$vendorsQry_order = " order by date_of_bill_switch desc";
			}	
			if ($_REQUEST["columnno"] == "5" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by invoice_date asc";
				$vendorsQry_order = " order by invoice_date asc";
			}	
			if ($_REQUEST["columnno"] == "5" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by invoice_date desc";
				$vendorsQry_order = " order by invoice_date desc";
			}	
			if ($_REQUEST["columnno"] == "6" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by invoice_number asc";
				$vendorsQry_order = " order by invoice_number asc";
			}	
			if ($_REQUEST["columnno"] == "6" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by invoice_number desc";
				$vendorsQry_order = " order by invoice_number desc";
			}	
			if ($_REQUEST["columnno"] == "7" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by company_terms asc";
				$vendorsQry_order = " order by company_terms asc";
			}	
			if ($_REQUEST["columnno"] == "7" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by company_terms desc";
				$vendorsQry_order = " order by company_terms desc";
			}	
			if ($_REQUEST["columnno"] == "8" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by new_invoice_date asc";
				$vendorsQry_order = " order by new_invoice_date asc";
			}	
			if ($_REQUEST["columnno"] == "8" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by new_invoice_date desc";
				$vendorsQry_order = " order by new_invoice_date desc";
			}	
			if ($_REQUEST["columnno"] == "9" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by invoice_due_date asc";
				$vendorsQry_order = " order by invoice_due_date asc";
			}	
			if ($_REQUEST["columnno"] == "9" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by invoice_due_date desc";
				$vendorsQry_order = " order by invoice_due_date desc";
			}	
			if ($_REQUEST["columnno"] == "10" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by invoice_due_date asc";
				$vendorsQry_order = " order by invoice_due_date asc";
			}	
			if ($_REQUEST["columnno"] == "10" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by invoice_due_date desc";
				$vendorsQry_order = " order by invoice_due_date desc";
			}			
			if ($_REQUEST["columnno"] == "11" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by made_payment asc";
				$vendorsQry_order = " order by made_payment asc";
			}	
			if ($_REQUEST["columnno"] == "11" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by made_payment desc";
				$vendorsQry_order = " order by made_payment desc";
			}	
			if ($_REQUEST["columnno"] == "12" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by payment_method asc";
				$vendorsQry_order = " order by payment_method asc";
			}	
			if ($_REQUEST["columnno"] == "12" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by payment_method desc";
				$vendorsQry_order = " order by payment_method desc";
			}
			if ($_REQUEST["columnno"] == "13" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by paid_date asc";
				$vendorsQry_order = " order by paid_date asc";
			}	
			if ($_REQUEST["columnno"] == "13" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by paid_date desc";
				$vendorsQry_order = " order by paid_date desc";
			}	
			if ($_REQUEST["columnno"] == "14" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by payment_proof_file asc";
				$vendorsQry_order = " order by payment_proof_file asc";
			}	
			if ($_REQUEST["columnno"] == "14" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by payment_proof_file desc";
				$vendorsQry_order = " order by payment_proof_file desc";
			}
			if ($_REQUEST["columnno"] == "15" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by receivable_portal_link asc";
				$vendorsQry_order = " order by receivable_portal_link asc";
			}	
			if ($_REQUEST["columnno"] == "15" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by receivable_portal_link desc";
				$vendorsQry_order = " order by receivable_portal_link desc";
			}
			if ($_REQUEST["columnno"] == "16" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by paid_by asc";
				$vendorsQry_order = " order by paid_by asc";
			}	
			if ($_REQUEST["columnno"] == "16" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by paid_by desc";
				$vendorsQry_order = " order by paid_by desc";
			}	
			if ($_REQUEST["columnno"] == "17" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by paid_date asc";
				$vendorsQry_order = " order by paid_date asc";
			}	
			if ($_REQUEST["columnno"] == "17" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by paid_date desc";
				$vendorsQry_order = " order by paid_date desc";
			}	
			if ($_REQUEST["columnno"] == "19" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by receivable_notes asc";
				$vendorsQry_order = " order by receivable_notes asc";
			}	
			if ($_REQUEST["columnno"] == "19" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by receivable_notes desc";
				$vendorsQry_order = " order by receivable_notes desc";
			}	
			if ($_REQUEST["columnno"] == "20" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by last_edited asc";
				$vendorsQry_order = " order by last_edited asc";
			}	
			if ($_REQUEST["columnno"] == "20" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by last_edited desc";
				$vendorsQry_order = " order by last_edited desc";
			}	
			if ($_REQUEST["columnno"] == "21" && $_REQUEST["sortflg"] == "1") {
				$vendorsQry .= " order by vendor_payment_log_notes asc";
				$vendorsQry_order = " order by vendor_payment_log_notes asc";
			}	
			if ($_REQUEST["columnno"] == "21" && $_REQUEST["sortflg"] == "2") {
				$vendorsQry .= " order by vendor_payment_log_notes desc";
				$vendorsQry_order = " order by vendor_payment_log_notes desc";
			}	
			
		}	
		
		//echo $vendorsQry . "<br>";
		//$v_res = db_query($vendorsQry,db());
		
		$whrMadePayConditn = '';
		if(isset($_REQUEST['ddMadePayment']) && $_REQUEST['ddMadePayment'] != 'All'){
			$whrMadePayConditn = ' and water_transaction.made_payment = '.$_REQUEST['ddMadePayment'];
		}
		$sr_inv=1;
		//while ($data_row = array_shift($v_res)) {
				$pagination_string = "";
				if ($_REQUEST['ddMadePayment'] == 1 or $_REQUEST['ddMadePayment'] == "All") {
				$items_per_page = 500; // Number of items per page
				$current_page = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1; // Get the current page number from the request
				$offset = ($current_page - 1) * $items_per_page; // Calculate the offset for the SQL query
				$pagination_string = " LIMIT $items_per_page OFFSET $offset";
				$sr_inv = $items_per_page * ($current_page - 1) + 1;
			}
			if (isset($_REQUEST["vendors_dd"])) {
				if ($_REQUEST["vendors_dd"] != "All" && $_REQUEST["vendors_dd"] != "") {
					$vendorQry = "Select *, water_transaction.id as transid, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
					inner join water_vendors on water_transaction.vendor_id=water_vendors.id
					where make_receive_payment = 1 and vendor_id = '".$_REQUEST["vendors_dd"]."' ". $whrMadePayConditn . " $swhere_condition group by company_id, water_transaction.id having sum(amount) <= 0 $vendorsQry_order" . $pagination_string;
				}else{
					$vendorQry = "Select *, water_transaction.id as transid, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
					inner join water_vendors on water_transaction.vendor_id=water_vendors.id
					where make_receive_payment = 1 ". $whrMadePayConditn . " $swhere_condition group by company_id, water_transaction.id having sum(amount) <= 0 $vendorsQry_order " .$pagination_string;
				}
			}else{
				$vendorQry = "Select *,water_transaction.id as transid, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
				inner join water_vendors on water_transaction.vendor_id=water_vendors.id
				where make_receive_payment = 1 ". $whrMadePayConditn . " $swhere_condition group by company_id, water_transaction.id having sum(amount) <= 0 $vendorsQry_order" .$pagination_string;
			}
			//echo $vendorQry . "<br>";
			$v_res1 = db_query($vendorQry,db());
			
			$vnumrows = 0;
					
				$row = 1; $isRec = ''; $unq_inc=0;
				while ($rows = array_shift($v_res1)) {
				//
				
					$unique_count=$row;
					$unq_inc++;
					$nickname = get_nickname_val($rows["company_name"], $rows["b2bid"]);
						
					$switchDate = "";
					if($rows["billingSwitchToZeroWaste"] == 'Yes'){
						$switchDate = ($rows["date_of_bill_switch"] != '0000-00-00' && $rows["date_of_bill_switch"] != '1969-12-31') ? date("m/d/Y", strtotime($rows["date_of_bill_switch"])) : '';
					}
						
					$inv_due_date_color = ""; $past_due = 0;
					if ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00")										
					{
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
						}	
					}

					$water_transaction_log_notes_dt = "";
					$ch_qry = db_query("SELECT `date` from water_transaction_log_notes where trans_id = '" . $rows["transid"] . "' ORDER BY `date` DESC limit 1");
					while ($rows_ch = array_shift($ch_qry)) {
						$water_transaction_log_notes_dt = $rows_ch["date"];
					}

					//if ($_REQUEST["columnno"] != "3") {
				?>	
					<tr>
						<td class="display_table"><?= $sr_inv++; ?></td>
						<td class="display_table" >
							<a target="_blank" href="water_vendor_master_new.php?id=<?=$rows["vendor_id"]?>&proc=View&flag=yes&compid=<?=$rows["b2bid"]?>">
								<? echo $rows["Name"]. " - ". $rows["description"] . " - ". $rows['city']. ", ". $rows['state']. " ". $rows['zipcode']; ?>
							</a>
						</td>
						<?php 
						/*
						<td class="display_table">
							C: <?= $rows["payable_contact_name"] ?> <br>
							P: <?= $rows["payable_main_phone"] ?> <br>
							E: <?= $rows["payable_email"] ?>
						</td>
						*/
						?>
						<td bgcolor= "<? echo $bgcolor; ?>" class="display_table"><a target="_blank" href="viewCompany.php?ID=<? echo $rows["b2bid"];?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><? echo $nickname; //echo "<br>".$rows["company_id"]; ?></a></td>
						<td class="display_table" ><? if ($rows["invoice_date"] != "") { echo date("M Y", strtotime($rows["invoice_date"]));} ?></td>
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
						<td class="display_table"><?= date('m/d/Y', strtotime($rows["new_invoice_date"])); ?></td>
						<td class="display_table" style="<?=$inv_due_date_color?>"><?= ($rows["invoice_due_date"] !== null && $rows["invoice_due_date"] != "0000-00-00") ? date('m/d/Y', strtotime($rows["invoice_due_date"])) : '' ?></td>
						<td class="display_table"><? echo $past_due;?></td>
						<td class="display_table">$<? echo number_format($rows["amt"],2); ?></td>
						
						<td class="display_table" id="payment_method_td-<?=$unique_count;?>"><? echo $rows["payment_method"]; ?></td>
						<td class="display_table">
							<?php 
							if($rows["scan_report"] != "") {
								if($rows['send_invoice_flg'] == 1 ){?>
								<a class="btnEdit" type="button" onclick="send_invoice(this,'<?php echo $rows['transid'];?>', 2)">Resend Invoice</a>
							<?php }else{?>
								<a class="btnEdit" type="button" onclick="send_invoice(this,'<?php echo $rows['transid'];?>', 1)">Send Invoice</a>
							<?php } 
							} ?>
						</td>
						<td class="display_table" id="made_payment_td-<?=$unique_count;?>"><?php if($rows["made_payment"]=="1"){ echo 'Yes'; }else{ echo "No"; } ?></td>
						<?php 
								$log_notes = $rows['vendor_payment_log_notes'];
								$display_anch = "none";
								if($log_notes !="" ){
									$display_anch = "revert";
								}
							?>
							<td class="display_table" id="log_notes_date_td-<?= $unique_count; ?>">
								<span><?php echo $water_transaction_log_notes_dt; ?></span>
								<br><br><a class="log_note_history" style="display:<?php echo $display_anch;?>" id='show_date_history_btn_<?= $unique_count ?>' onclick="javascript:show_all_log_notes(<?= $rows['transid'] ?>,'<?= $unique_count; ?>','date')">Log Notes Date History</a>
							</td>
							<td class="display_table" id="log_notes_td-<?= $unique_count; ?>">
								<span><?php echo $log_notes; ?></span>
								<br><br><a class="log_note_history" style="display:<?php echo $display_anch;?>" id='show_notes_history_btn_<?= $unique_count ?>' onclick="javascript:show_all_log_notes(<?= $rows['transid'] ?>,'<?= $unique_count; ?>','notes')">Log Notes History</a>
							</td>
							
						<td class="display_table">
							<a style="cursor:pointer" id='atag_vendor_payment_div<?=$unique_count?>' onclick='javascript:show_vendor_payment_div(<?=$unique_count?>)'><u>View</u></a>
							
							<div id="payment_no_div_edit-<?=$row;?>" style="display:none;">
								<form id="vendor_edit_form_each_row-<?=$unique_count;?>">
									<div id="editSectionTbl_<?=$unique_count?>">
									
										<table width="300px">
											<tr>
												<td style="background-color: #ABC5DF;" align="center" colspan="2">Vendor Payment Report
												</td>
											</tr>
									
											<tr>
												<td class="display_table">Made or Received Payment?</td>
												<td class="display_table">
													<input type="checkbox" name="made_payment" id="made_payment" value="1" <? if($rows['made_payment'] == 1 ){ echo 'checked'; } ?> >
												</td>
											</tr>

											<tr>
												<td class="display_table">Paid/Received by:</td>
												<td class="display_table">
													<input type="text" name="paid_by" id="paid_by" value="<?=$rows["paid_by"] ?>">
												</td>
											</tr>

											<tr>
												<td class="display_table">Paid/Received date:</td>
												<td class="display_table">
													<input type="text" name="paid_date" id="paid_date" value="<?= $rows["paid_date"]!="" ? date('m/d/Y', strtotime($rows["paid_date"])) : "";?>">
												</td>
											</tr>

											<tr>
												<td class="display_table">Payment Method:</td>
												<td class="display_table">
													<input type="text" name="payment_method" id="payment_method" value="<?=$rows["payment_method"] ?>">
												</td>
											</tr>

											<tr>
												<td class="display_table">Payment proof file:</td>
												<td class="display_table">
													<input type="file" name="payment_proof_file[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
													<input type="hidden" name="hdnWatrTrnstnId" value="<? echo $rows["transid"] ?>">
													<input type="hidden" name="hdnvendrId" value="<? echo $rows["vendor_id"] ?>">
													<input type="hidden" name="hdnInvcNo" value="<? echo $rows["invoice_number"] ?>">
													<input type="hidden" name="hdnInvcVendorEmail" value="<? echo $rows["contact_email"] ?>">
													<input type="hidden" name="vnumrows" value="<?=$vnumrows?>">
													<input type="hidden" name="vendors_dd" name="vendors_dd" value="<? echo $_REQUEST["vendors_dd"];?>">
													<input type="hidden" name="comp_sel" name="comp_sel" value="<? echo $_REQUEST["comp_sel"];?>">
													<input type="hidden" name="ddMadePayment" value="<? echo $_REQUEST["ddMadePayment"];?>">
													<input type="hidden" name="vendorpagename"  value="UCBZeroWaste_Vendors_AP_new.php">
													<input type="hidden" name="common_vendor_id"  value="<?= $common_vendor_id;?>">
													<input type="hidden" name="edit_report" value="yes"/>
												</td>
											</tr>

											<tr>
												<td class="display_table">Log Notes:</td>
												<td class="display_table">
													<input type="text" name="vendor_payment_log_notes" id="vendor_payment_log_notes" value="<?=$rows["vendor_payment_log_notes"] ?>">
												</td>
											</tr>
												
											<tr>
												<td colspan="2" class="display_table">
													<input type="button" name="btnUpdateVendrRpt" id="btnUpdateVendrRpt_<?= $unique_count; ?>" class="btnUpdateVendrRpt" onclick="update_vendor_report('<?=$unique_count; ?>')" value="Save">
													
													<a class="btnEdit" id="btnCancelSectionClose_<?=$unique_count?>" onclick="cancelSectionClose('<?=$unique_count;?>')" >Cancel</a>
												</td>
											</tr>
										</table>
									</div>
								</form>
							</div>	
							
							<div id="payment_no_div_view-<?=$unique_count;?>" style="display:none;">
								<div id="viewSectionTbl_<?=$unique_count?>" >
									<table width="300px">
										<tr >
											<td style="background-color: #ABC5DF;" align="center" colspan="2">Vendor Payment Report
												<a style="cursor:pointer" id="btnEditSectionOpen_<?=$unique_count?>" onclick="editSectionOpen('<?=$unique_count?>')"><u>Edit</u></a>
											</td>
										</tr>

										<tr>
											<td class="display_table" width="150px">Made or Received Payment?</td>
											<td class="display_table" width="150px">
												<span id='view_made_payment<?=$unique_count;?>'><? echo ($rows['made_payment'] == 1) ? 'Yes' : 'No';?></span>
											</td>
										</tr>

										<tr>
											<td class="display_table">Paid/Received by:</td>
											<td class="display_table">
												<span id='view_paid_by<?=$unique_count;?>'><?=$rows["paid_by"] ?></span>
											</td>
										</tr>

										<tr>
											<td class="display_table">Paid/Received date:</td>
											<td class="display_table">
												<span id='view_paid_date<?=$unique_count;?>'><?= $rows["paid_date"]!="" ? date('m/d/Y', strtotime($rows["paid_date"])) : "";?></span>
											</td>
										</tr>

										<tr>
											<td class="display_table">Payment Method:</td>
											<td class="display_table">
												<span id='view_payment_method<?=$unique_count;?>'><?=$rows["payment_method"] ?></span>
											</td>
										</tr>

										<tr>
											<td class="display_table">Payment proof file:</td>
											<td class="display_table">
												<span id='view_payment_proof_file<?=$unique_count;?>'><? if ($rows["payment_proof_file"] != "") {
														$tmppos_1 = strpos($rows["payment_proof_file"], "|");
														if ($tmppos_1 != false)
														{ 	
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
												?></span>
											</td>
										</tr>

										<tr>
											<td class="display_table">Log Notes:</td>
											<td class="display_table">
												<span id='view_vendor_payment_log_notes<?=$unique_count;?>'><?=$rows["vendor_payment_log_notes"] ?></span>
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
					//}
					
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
						'water_transaction_log_notes_dt' => $water_transaction_log_notes_dt, 'log_notes' => $log_notes,
						'amt' => $rows["amt"], 'nickname' => $nickname,'vnumrows' => $vnumrows, 'vendor_name' => $rows["Name"]. " - ". $rows["description"] ." - " . $rows['city']. ", ". $rows['state']. " ". $rows['zipcode']);

				}

			//}
			?>
		
		<?
			
		//}
		
		if ($_REQUEST["columnno"] == "DONOTUSE3") {
			$MGArraysort_B = array();
			foreach ($MGarray as $MGArraytmp) {
				$MGArraysort_B[] = $MGArraytmp['amt'];
			}
			if ($_REQUEST["sortflg"] == "1") {
				array_multisort($MGArraysort_B, SORT_ASC, SORT_NUMERIC, $MGarray); 
			}	
			if ($_REQUEST["sortflg"] == "2") {
				array_multisort($MGArraysort_B, SORT_DESC, SORT_NUMERIC, $MGarray); 
			}	
			
			foreach ($MGarray as $MGArraytmp) { 
				?>
				<tr>
					<td class="display_table"><? echo $MGArraytmp["vendor_name"]; ?></td>
					<td class="display_table"><a target="_blank" href="viewCompany.php?ID=<? echo $MGArraytmp["b2bid"];?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><? echo $MGArraytmp["nickname"]; ?></a></td>
					<td class="display_table">
						C: <?= $MGArraytmp["company_contact"] ?> <br>
						P: <?= $MGArraytmp["company_phone"] ?> <br>
						E: <?= $MGArraytmp["company_email"] ?>
					</td>
					<td class="display_table"><?= $MGArraytmp["switchDate"] ?></td>
					<td class="display_table"><? if ($MGArraytmp["invoice_date"] != "") { echo date("m/d/Y", strtotime($MGArraytmp["invoice_date"]));} ?></td>
					<td class="display_table"><?= $MGArraytmp["invoice_number"]; ?></td>
					<td class="display_table">
						<? if ($MGArraytmp["scan_report"] != "") {
								$tmppos_1 = strpos($MGArraytmp["scan_report"], "|");
								if ($tmppos_1 != false)
								{ 	
									$elements = explode("|", $MGArraytmp["scan_report"]);
									for ($i = 0; $i < count($elements); $i++) {	?>										
										<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'><font size="1">View</font></a><br />
									<?}
								}else {		
						?>
								<a target="_blank" href='water_scanreport/<? echo $MGArraytmp["scan_report"]; ?>'><font size="1">View Attachments</font></a>
							<? }
							}?>
					</td>

					<td class="display_table"><?= $MGArraytmp["company_terms"] ?></td>
					<td class="display_table"><?= date('m/d/Y', strtotime($MGArraytmp["new_invoice_date"])); ?></td>
					<td class="display_table"><?= $MGArraytmp["no_invoice_due_marked_on"] != '0000-00-00 00:00:00' ? date('m/d/Y', strtotime($MGArraytmp["no_invoice_due_marked_on"])) : '' ?></td>
					<td class="display_table"><?=$MGArraytmp["invoice_age"]?></td>
					<td class="display_table">$<? echo number_format($MGArraytmp["amt"],2); ?></td>
					<td class="display_table"><?php if($MGArraytmp["made_payment"]=="1"){ echo 'Yes'; }else{ echo "No"; } ?></td>
					<td class="display_table"><? echo $MGArraytmp["payment_method"]; ?></td>
					<td class="display_table"><? echo $MGArraytmp["paid_date"]; ?></td>
					<td class="display_table">
						<? if ($MGArraytmp["payment_proof_file"] != "") {
							$tmppos_1 = strpos($MGArraytmp["payment_proof_file"], "|");
							if ($tmppos_1 != false)
							{ 	
								$elements = explode("|", $MGArraytmp["payment_proof_file"]);
								for ($i = 0; $i < count($elements); $i++) {	?>										
									<a target="_blank" href='water_payment_proof/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
									<br>
								<?}
							}else {		
						?>
							<a target="_blank" href='water_payment_proof/<? echo $MGArraytmp["payment_proof_file"]; ?>'><font size="1">View Attachments</font></a>
							<br>
						<? 
							}
						}
						?>
					</td>
					<td class="display_table"><?= $MGArraytmp["receivable_portal_link"] ?></td>
					<td class="display_table"><?= $MGArraytmp["paid_by"] ?></td>
					<td class="display_table"><?= date('m/d/Y', strtotime($MGArraytmp["paid_date"])) ?></td>
					<td class="display_table"><?= $MGArraytmp["receivable_notes"] ?></td>
					
					<td class="display_table">
						<? 
							if($MGArraytmp["made_payment"] == "" && $MGArraytmp["payment_method"] == ""){
						?>
							<form name="frmEditVendrRptLinkpg" method="post" action="editVendrRptLinkpg.php" encType="multipart/form-data">
							<table>
								<tr>
									<td class="display_table">Made or Received Payment?</td>
									<td class="display_table">
										<input type="checkbox" name="made_payment-<?=$row;?>" id="made_payment" value="1" >
									</td>
									<td class="display_table">Transaction Log Notes:</td>
									<td class="display_table">
										<input type="text" name="paid_by-<?=$row;?>" id="paid_by" value=" ">
									</td>

									<td class="display_table">Payment Method:</td>
									<td class="display_table">
										<input type="text" name="payment_method-<?=$row;?>" id="payment_method" value=" ">
									</td>

									<td class="display_table">Terms:</td>
									<td class="display_table">
										<select name="company_terms-<?=$row;?>" id="company_terms">
										<option value="">Select Terms</option>
										<?
										foreach($termsArray as $value){
											$termsselectedValue = isset($MGArraytmp["company_terms"]) && $MGArraytmp["company_terms"] != '' && $MGArraytmp["company_terms"] ==  $value ? 'selected' : ''; 
										?>
											<option value="<?=$value?>" <?=$termsselectedValue?>><?=$value?></option>
										<? } ?>
										</select>
									</td>

									<td class="display_table">Transaction Log Date:</td>
									<td class="display_table"><input type="text" name="paid_date-<?=$row;?>" id="paid_date" value=""></td>

									<td class="display_table">Select Template</td>
									<td class="display_table">
										<select name="dueMailByAR-<?=$row;?>" id="dueMailByAR">
											<option value="" selected>Select a Due</option>
											<option value="soon">Due soon email to vendor</option>
											<option value="past">Past due email to vendor</option>
										</select>
									</td>
									
									<td class="display_table">Remittance Confirmation:</td>
									<td class="display_table">
										<input type="file" name="payment_proof_file-<?=$row;?>[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
										<input type="hidden" name="hdnWatrTrnstnId-<?=$row;?>" value="<?=$MGArraytmp["transid"];?>">
										<input type="hidden" name="hdnvendrId-<?=$row;?>" value="<?=$MGArraytmp["vendor_id"];?>">
										<input type="hidden" name="hdnInvcNo-<?=$row;?>" value="<?=$MGArraytmp["invoice_number"];?>">
									</td>
								</tr>
							</table>
						
						<?	}else{	?>
						
							<a class="btnEdit" id="btnEditSectionOpen_<?=$MGArraytmp["invoice_number"];?><? echo $MGArraytmp["transid"];?>" onclick="editSectionOpen('<?=$MGArraytmp["invoice_number"];?><? echo $MGArraytmp["transid"] ?>')">Edit</a>
							<div  id="editSectionTbl_<?=$MGArraytmp["invoice_number"];?><? echo $MGArraytmp["transid"];?>" style="display: none;">
							
							<form name="frmEditVendrRptLinkpg" method="post" action="editVendrRptLinkpg.php" encType="multipart/form-data">
							<table>
								<tr>
									<td class="display_table">Made or Received Payment?</td>
									<td class="display_table">
										<input type="checkbox" name="made_payment-<?=$row;?>" id="made_payment" value="1" <? if($MGArraytmp['made_payment'] == 1 ){ echo 'checked'; } ?> >
									</td>
									<td class="display_table">Paid/Received by:</td>
									<td class="display_table">
										<input type="text" name="paid_by-<?=$row;?>" id="paid_by" value="<?=$MGArraytmp["paid_by"] ?>">
									</td>

									<td class="display_table">Payment Method:</td>
									<td class="display_table">
										<input type="text" name="payment_method-<?=$row;?>" id="payment_method" value="<?=$MGArraytmp["payment_method"] ?>">
									</td>

									<td class="display_table">Terms:</td>
									<td class="display_table">
										<select name="company_terms-<?=$row;?>" id="company_terms">
										<option value="">Select Terms</option>
										<?
										foreach($termsArray as $value){
											$termsselectedValue = isset($MGArraytmp["company_terms"]) && $MGArraytmp["company_terms"] != '' && $MGArraytmp["company_terms"] ==  $value ? 'selected' : ''; 
										?>
											<option value="<?=$value?>" <?=$termsselectedValue?>><?=$value?></option>
										<? } ?>
										</select>
									</td>

									<td class="display_table">Transaction Log Date:</td>
									<td class="display_table"><input type="text" name="paid_date-<?=$row;?>" id="paid_date" value="<?=$MGArraytmp["paid_date"] ?>"></td>

									<td class="display_table">Select Template</td>
									<td class="display_table">
										<select name="dueMailByAR-<?=$row;?>" id="dueMailByAR">
											<option value="" selected>Select a Due</option>
											<option value="soon">Due soon email to vendor</option>
											<option value="past">Past due email to vendor</option>
										</select>
									</td>

									<td class="display_table">Remittance Confirmation:</td>
									<td class="display_table">
										<input type="file" name="payment_proof_file-<?=$row;?>[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
										<input type="hidden" name="hdnWatrTrnstnId-<?=$row;?>" value="<? echo $MGArraytmp["transid"] ?>">
										<input type="hidden" name="hdnvendrId-<?=$row;?>" value="<? echo $MGArraytmp["vendor_id"] ?>">
										<input type="hidden" name="hdnInvcNo-<?=$row;?>" value="<? echo $MGArraytmp["invoice_number"] ?>">
									</td>

									<td class="display_table">
										<a id="btnCancelSectionClose_<?=$MGArraytmp["invoice_number"];?>" class="btnEdit" onclick="cancelSectionClose('<?=$MGArraytmp["invoice_number"];?>')">Cancel</a>
									</td>
								</tr>
							</table>
							</div>
						<?
						}
						?>
					</td>
				</tr>
			<?

			}
		}	
		
		$_SESSION["exportArray"] = $MGarray;
	?>
	
	</table>
	
	