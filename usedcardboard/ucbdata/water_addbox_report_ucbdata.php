<? 
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

$today = date("Y-m-d H:i:s"); 
$warehouse_id = $_POST["warehouse_id"];
$id = $_POST["id"];
$rec_type = $_POST["rec_type"];
//$user = $_COOKIE['userinitials'];
$employee = $_POST["txtemployee"];
$sort_date = $today;
$sort_date_NEW = date("Y-m-d");
$trans_rec_id = $_POST["rec_id"];
$boxnotes = $_POST["boxnotes"];
$count = $_POST["row_cnt_main"];
$count_add_ctrl = $_POST["row_cnt_fees"];
//vendor payment report values
$make_receive_payment = $_POST["make_receive_payment"];
$payment_method = $_POST["payment_method"];
if((isset($make_receive_payment)) && ($make_receive_payment==1)){
	$made_payment = $_POST["made_payment"];
	$paid_by = $_POST["paid_by"];
	$paid_date = $_POST["paid_date"];
	$vendor_credit = $_POST['vendor_credit'];
}
else{
	$made_payment = 0;
	$paid_by = "";
	$paid_date = "";
	$vendor_credit = "";
}
$payment_proof_file = $_POST["payment_proof_file"];
//
$tot_value = 0;

	$scan_rep_name = "";
	if ($_POST["update"] == "yes") {
		$query = db_query("SELECT scan_report FROM water_transaction WHERE id = " . $trans_rec_id . " ", db() );
		while ($rowsel_getdata = array_shift($query)) {
			$scan_rep_name = $rowsel_getdata["scan_report"];
		}
	}
	
	$filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF,xls,xlsx";
	$allow_ext = explode(",",$filetype);
	
	if(!empty( $_FILES['uploadscanrep'] ) )
	{
		$scan_rep_name_new = "";	
		foreach( $_FILES['uploadscanrep']['tmp_name'] as $index => $tmpName )
		{

			if( !empty( $_FILES['uploadscanrep']['error'][ $index ] ) )
			{

			}else{

				if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) )
				{

					$ext = pathinfo($_FILES["uploadscanrep"]["name"][ $index ], PATHINFO_EXTENSION);
					if(in_array(strtolower($ext), $allow_ext) )
					{				
						$attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace( "/'/", "\'", $_FILES['uploadscanrep']['name'][ $index ]); 	

						$scan_rep_name_new = $scan_rep_name_new . $attachfile_nm_tmp . "|";

						move_uploaded_file( $tmpName, "water_scanreport/". $attachfile_nm_tmp); // move to new location perhaps?
					}
				}

			}

		}
		
		$tmppos_1 = strpos($scan_rep_name_new, "|");
		if ($tmppos_1 != false)
		{ 	
			if ($scan_rep_name != "") {
				$scan_rep_name = $scan_rep_name . "|" . substr($scan_rep_name_new, 0 , strlen($scan_rep_name_new,'|')-1); 
			}else{
				$scan_rep_name = substr($scan_rep_name_new, 0 , strlen($scan_rep_name_new,'|')-1); 
			}
		}		
	}	

	if ($scan_rep_name != "") {
		$scan_rep_name = preg_replace("/'/", "\'", $scan_rep_name); 
	}

	/*$ext = pathinfo($_FILES["uploadscanrep"]["name"], PATHINFO_EXTENSION);
	if(in_array(strtolower($ext), $allow_ext) )
	{
		$scan_rep_name = date("mdYHis") . " " . $_FILES["uploadscanrep"]["name"];

		move_uploaded_file($_FILES["uploadscanrep"]["tmp_name"], "water_scanreport/" . $scan_rep_name);
	}else{
		echo "<font color=red>" . $_FILES["uploadscanrep"]["name"] . " file not uploaded, this file type is restricted.</font>";
		echo "<script>alert('" . $_FILES["uploadscanrep"]["name"] . " file not uploaded, this file type is restricted.');</script>";
	}*/	



	//To mark no Invoice
	if(($_REQUEST["mark_no_inv"]=="yes")){

		//$sql_upd = "UPDATE water_transaction SET no_invoice_due_flg = 1, no_invoice_due_marked_by = '" . $_COOKIE['userinitials'] . "', no_invoice_due_marked_on = '" . date("Y-m-d H:i:s") . "' WHERE id = " . $trans_rec_id;
		//$result_sort = db_query($sql_upd,db() );
		
		$sql_upd = "UPDATE water_transaction SET no_invoice_due_flg = 1, no_invoice_due_marked_by = '" . $_COOKIE['userinitials'] . "', no_invoice_due_marked_on = '" . date("Y-m-d H:i:s") . "', 
		doubt = '" . preg_replace("/'/", "\'", $_POST["txtdoubt"]) . "', invoice_currency = '" . $_POST["invoice_currency"] . "', have_doubt = '" . $_POST["chkdoubt"] . "', 
		invoice_date = '" . $_POST["invoice_date"] . "', vendor_id = '" . $_POST["vendor_id"] . "' WHERE id = " . $trans_rec_id;
		$result_sort = db_query($sql_upd,db() );
		
		redirect("viewCompany_func_water-mysqli.php?ID=".$_REQUEST['ID']."&show=watertransactions&warehouse_id=". $warehouse_id ."&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $trans_rec_id. "&display=water_sort");//seller_sort		
	}
	
	if(($_REQUEST["mark_no_inv_undo"]=="yes")){

		$sql_upd = "UPDATE water_transaction SET no_invoice_due_flg = 0, no_invoice_due_marked_by = '', no_invoice_due_marked_on = '' WHERE id = " . $trans_rec_id;
		$result_sort = db_query($sql_upd,db() );
		
		redirect("viewCompany_func_water-mysqli.php?ID=".$_REQUEST['ID']."&show=watertransactions&warehouse_id=". $warehouse_id ."&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $trans_rec_id. "&display=water_sort");//seller_sort
	}

	//To Save payment proof file.
	if((isset($make_receive_payment)) && ($make_receive_payment==1)){
		$payment_proof_name="";
		if ($_POST["update"] == "yes") {
			$query = db_query("SELECT payment_proof_file FROM water_transaction WHERE id = " . $trans_rec_id . " ", db() );
			while ($rowsel_getdata = array_shift($query)) {
				$payment_proof_name = $rowsel_getdata["payment_proof_file"];
			}
		}
		$filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
		$allow_ext = explode(",",$filetype);

		if(!empty( $_FILES['payment_proof_file'] ) )
		{
			$payment_proof_files = "";	
			if( !empty( $_FILES['payment_proof_file']['error'][ $index ] ) )
			{

			}else{
				
				foreach( $_FILES['payment_proof_file']['tmp_name'] as $index => $tmpName )
				{
					if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) )
					{

						$ext = pathinfo($_FILES["payment_proof_file"]["name"][ $index ], PATHINFO_EXTENSION);
						if(in_array(strtolower($ext), $allow_ext) )
						{				
							$attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace( "/'/", "\'", $_FILES['payment_proof_file']['name'][ $index ]); 	

							$payment_proof_files = $payment_proof_files . $attachfile_nm_tmp . "|";

							move_uploaded_file( $tmpName, "water_payment_proof/". $attachfile_nm_tmp); // move to new location perhaps?
						}
					}
				}
			}

		}

		$tmppos_1 = strpos($payment_proof_files, "|");
		if ($tmppos_1 != false)
		{ 	
			if ($payment_proof_name != "") {
				$payment_proof_name = $payment_proof_name . "|" . substr($payment_proof_files, 0 , strlen($payment_proof_files,'|')-1); 
			}else{
				$payment_proof_name = substr($payment_proof_files, 0 , strlen($payment_proof_files,'|')-1); 
			}
		}
		if ($payment_proof_name != "") {
			$payment_proof_name = preg_replace("/'/", "\'", $payment_proof_name); 
		}
	}//End isset make_receive_payment


/**For add functionality**/
if ($_POST["update"] != "yes") {  

	$get_trans_sql = "SELECT water_transaction.id as transid FROM water_transaction where company_id = " . $warehouse_id . " and invoice_number = '" . trim($_POST["invoice_no"]) . "' limit 1";	
	$tran = db_query($get_trans_sql, db() );
	while ($tranlist = array_shift($tran)) 
	{
		echo "A Vendor Report with the same Invoice Number - " . $_POST["invoice_no"] . " has already been added, you will not be able to save two Vendor Reports with the same Invoice Number.";
		exit;
	} 
	
	for($i=0;$i<$count;$i++){
		
		$water_item_str = explode("|", $_POST["water_item"][$i]);
		$valueach = 0; $tot_value = 0;
		if ($water_item_str[1] == "Cost Per Unit"  || $water_item_str[6] == "Cost Per Pull" || $water_item_str[7] == "Cost Per Item")
		{
			$valueach = $_POST["txtcostperunit"][$i];
			$tot_value = $_POST["txttotalcost"][$i];
		}
		if ($water_item_str[1] == "Revenue Per Unit" || $water_item_str[6] == "Revenue Per Pull" || $water_item_str[7] == "Revenue Per Item")
		{
			$valueach = $_POST["txtrevenueperunit"][$i];
			$tot_value = $_POST["txttotalrevenue"][$i];
		}

		/***************************/
		/**Update the query for gallon weight unit i.e. AmountUnitEquivalent
		/**Done by Nayan
		/**Date : 16 Feb 2021
		/***************************/

		if($_POST["weight_unit"][$i] == 'Gallon'){
			$weightUnit[$i]			=  'Pounds';
			$amountUnitEquivalent[$i] 	= $_POST["weight_unit"][$i];
		}else{
			$weightUnit[$i]			=  $_POST["weight_unit"][$i];
			$amountUnitEquivalent[$i] 	= $_POST["weight_unit"][$i];
		}		
		
		$sql_sort = "INSERT INTO water_boxes_report_data ( entry_date, warehouse_id, trans_rec_id, box_id, count_val, weight, value_each, total_value, outlet, WeightorNumberofPulls, AmountUnit, CostOrRevenuePerUnit, unit_count, weight_unit, CostOrRevenuePerPull, CostOrRevenuePerItem, AmountUnitEquivalent ) ";
		$sql_sort .= " VALUES ('" . $sort_date . "', '" . $warehouse_id . "', '" . $trans_rec_id . "', '" . $water_item_str[0] . "', '', '" . $_POST["txtweight"][$i] . "', '" . $valueach . "', '" . $tot_value . "', '" . $water_item_str[4] . "', '" . $water_item_str[5] . "', '" . $water_item_str[2] . "', '" . $water_item_str[1] . "', '" . $_POST["txtunitcount"][$i] . "', '" . $weightUnit[$i] . "', '" . $water_item_str[6] . "', '" . $water_item_str[7]  ."','". $amountUnitEquivalent[$i] ."')";

		if ($_POST["totalvalue"][$i] > 0){
			$tot_value = $tot_value + $_POST["totalvalue"][$i];
		}
		
		$result_sort = db_query($sql_sort,db() );
	}

	$fees_tot_value = 0;
	for($i=0;$i<$count_add_ctrl;$i++){
		$savings_calculation_category_arr = explode("|" , $_POST["savings_calculation_category"][$i]);
		$sql_sort = "INSERT INTO water_trans_addfees (trans_id, add_fees_id, add_fees, add_fees_occurance, add_fee_remark, savings_calculation_category_id, savings_calculation_category_flg) ";
		$sql_sort .= " VALUES ('" . $trans_rec_id . "', '" . $_POST["selfees"][$i] . "', '" . $_POST["txtcharge"][$i] . "', '" . $_POST["txtOccurrences"][$i] . "', '" . str_replace("'", "\'" , $_POST["txtremark_fees"][$i]) . "', '" . $savings_calculation_category_arr[0] . "', '" . $savings_calculation_category_arr[1] . "')";
		if ($_POST["txtcharge"][$i] > 0){
			$fees_tot_value = $fees_tot_value + $_POST["txtcharge"][$i];
		}
		
		$result_sort = db_query($sql_sort,db() );
	}
	$fin_tot_value = $tot_value - $fees_tot_value;

	/***************************/
	/**Update the query with adding repor_entry_emp field value
	/**Done by Nayan
	/**Date : 11 Feb 2021/**Update the query for edited by & entered by field
	/**Date : 17 Feb 2021
	/***************************/
	if($_POST['hidtxtnumrows'] == 0){
		$reportEntryEditedEmp 	= $_POST['txtEditedBY'];
		$repor_entry_emp 		= $_COOKIE['userinitials'];
	}else{
		$reportEntryEditedEmp 	= $_POST['txtEditedBY'];
		$repor_entry_emp 		= $_COOKIE['userinitials'];
	}
	

	//To update the final amount, as per #438 bug
	$rec_id = $trans_rec_id;
	$total_value_tot = 0; $tot_cost = 0; $tot_revenue = 0;
	$dt_view_qry = "SELECT * FROM water_boxes_report_data Left JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.id WHERE water_boxes_report_data.trans_rec_id = '" . $rec_id . "'";
	$dt_view_res = db_query($dt_view_qry,db() );
	while ($dt_view_row = array_shift($dt_view_res)) {
		$total_value_tot = $total_value_tot + $dt_view_row["total_value"];
		if ($dt_view_row["CostOrRevenuePerUnit"] == "Revenue Per Unit"  || $dt_view_row["CostOrRevenuePerPull"] == "Revenue Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Revenue Per Item") {
			$tot_revenue = $tot_revenue + $dt_view_row["total_value"];
		}
		if ($dt_view_row["CostOrRevenuePerUnit"] == "Cost Per Unit" || $dt_view_row["CostOrRevenuePerPull"] == "Cost Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Cost Per Item") { 
			$tot_cost = $tot_cost - $dt_view_row["total_value"];
		}
	}
	$dt_view_qry = "SELECT * from water_boxes_report_data WHERE trans_rec_id = '" . $rec_id . "' LIMIT 0,1";
	$dt_view_res = db_query($dt_view_qry,db() );
	while ($dt_view_row = array_shift($dt_view_res)) {
		$total_value_tot = $tot_revenue + $tot_cost;
	}
	$tot_add_fee = 0;
	$query = db_query("SELECT * FROM water_trans_addfees left join water_additional_fees on water_additional_fees.id = water_trans_addfees.add_fees_id where trans_id = " . $rec_id, db() );
	while ($rowsel_getdata = array_shift($query)){
		if ($rowsel_getdata["active_flg"] == 1 || $rowsel_getdata["add_fees_id"] == 0) {
			$tot_add_fee = $tot_add_fee + ($rowsel_getdata["add_fees"] * $rowsel_getdata["add_fees_occurance"]);
		}
	}

	//$_POST["final_total"]	
	if(isset($_REQUEST['txtNetcostRev'])){
		$vendors_net_cost_or_revenue = $_REQUEST['txtNetcostRev'];
	}else{
		$vendors_net_cost_or_revenue = 0;
	}
	if(isset($_REQUEST['txtGrossSavings'])){
		$gross_savings = $_REQUEST['txtGrossSavings'];
	}else{
		$gross_savings = 0;
	}
	if(isset($_REQUEST['txtClientNetSavings'])){
		$client_net_savings = $_REQUEST['txtClientNetSavings'];
	}else{
		$client_net_savings = 0;
	}
	if(isset($_REQUEST['txtUCBSavingsSplit'])){
		$ucb_savings_split = $_REQUEST['txtUCBSavingsSplit'];
	}else{
		$ucb_savings_split = 0;
	}
	if(isset($_REQUEST['txtTotalDueToUcb'])){
		$total_due_to_ucb = $_REQUEST['txtTotalDueToUcb'];
	}else{
		$total_due_to_ucb = 0;
	}

	$total_charges = $total_due_to_ucb + $total_value_tot - $tot_add_fee;

	//To check the duplicate for Month Service end date
	//if ($_POST["vendor_id"] == 844){
		$ser_end_dt_dup = "no";
		$get_trans_sql = "SELECT water_transaction.id as transid FROM water_transaction where company_id = " . $warehouse_id . " and vendor_id = 844 and month(invoice_date) = '" . date("m", strtotime($_POST["invoice_date"])) . "' and year(invoice_date) = '" . date("Y", strtotime($_POST["invoice_date"])) . "' limit 1";	
		$tran = db_query($get_trans_sql, db() );
		while ($tranlist = array_shift($tran)) 
		{
			$ser_end_dt_dup = "yes";
		} 	
		
		if ($ser_end_dt_dup == "yes"){
			$message = "UCBZW Consolidated Invoice Team, <br><br>";
			$message .= "A vendor report or invoice was entered in WATER under " . get_nickname_val('',$_REQUEST["ID"]) . " ";
			$message .= "after the UCBZeroWaste Consolidated Invoice was already done for this month. Please make sure to create an UCBZW Accounting Log to add this vendor report or invoice charge on the next month's UCBZeroWaste Consolidated invoice.<br><br>";
			$message .= "Link to the Vendor Report or Invoice: <a href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $_REQUEST["ID"] . "&show=watertransactions&company_id=" . $warehouse_id . "&rec_type=&proc=View&searchcrit=&id=" . $warehouse_id . "&b2bid=" . $_REQUEST["ID"] . "&rec_id=" . $trans_rec_id . "&display=water_sort#watersort'>Click here to view</a><br><br>";
			
			//"UCBZWConsolidatedInvoiceTeam@UCBZeroWaste.com"
			//$resp =	sendemail_attachment(array(), "", "UCBZWConsolidatedInvoiceTeam@UCBZeroWaste.com", "", "", "admin@usedcardboardboxes.com", "admin@usedcardboardboxes.com", "admin@usedcardboardboxes.com", "WATER Entry Notification - Vendor report added in after the UCBZeroWaste Consolidated Invoice was already done for this month", $message);	
			$resp = sendemail_php_function(null, '', "UCBZWConsolidatedInvoiceTeam@UCBZeroWaste.com", "", "", "ucbemail@usedcardboardboxes.com", "Freight Usedcardboardboxes", "freight@usedcardboardboxes.com", "WATER Entry Notification - Vendor report added in after the UCBZeroWaste Consolidated Invoice was already done for this month", $message); 
		}
	//}
	
	$sql_sort = "UPDATE water_transaction SET doubt = '" . preg_replace("/'/", "\'", $_POST["txtdoubt"]) . "', invoice_currency = '" . $_POST["invoice_currency"] . "', have_doubt = '" . $_POST["chkdoubt"] . "', service_begin_date = '" . $_POST["service_begin_date"] . "', 
	new_invoice_date = '" . $_POST["new_invoice_date"] . "', invoice_date = '" . $_POST["invoice_date"] . "', invoice_due_date = '" . $_POST["invoice_due_date"] . "',  invoice_number = '" . preg_replace("/'/", "\'", $_POST["invoice_no"]) . "', vendor_id = '" . $_POST["vendor_id"] . "', report_date = '" . $_POST["report_date"] . "', amount = '" . $total_charges . "', scan_report = '" . preg_replace("/'/", "\'", $scan_rep_name) . "', report_notes = '" . preg_replace("/'/", "\'", $_POST["boxnotes"]) . "', make_receive_payment = '" . $make_receive_payment . "', made_payment = '" . $made_payment . "', paid_by = '" . $paid_by . "', paid_date = '" . $paid_date . "', payment_method = '" . $payment_method . "', vendor_credit = '".$vendor_credit."', payment_proof_file = '" . preg_replace("/'/", "\'", $payment_proof_name) . "', report_entered = 1, repor_entry_emp = '".$repor_entry_emp."', reportEntryEditedEmp = '".$reportEntryEditedEmp."', vendors_net_cost_or_revenue = '".$vendors_net_cost_or_revenue."', gross_savings = '".$gross_savings."', client_net_savings = '".$client_net_savings."', ucb_savings_split = '".$ucb_savings_split."', total_due_to_ucb = '".$total_due_to_ucb."' WHERE id = " . $trans_rec_id;
	$result_sort = db_query($sql_sort,db() );
}

/**For edit functionality**/
if ($_POST["update"] == "yes") {  
	$result_sort = db_query("DELETE FROM water_boxes_report_data WHERE trans_rec_id = ". $trans_rec_id,db() );
	if ($count > 0){
		
		for($i=0;$i<$count;$i++){
			$water_item_str = explode("|", $_POST["water_item"][$i]);
			
			$valueach = 0; $tot_value = 0;
			if ($water_item_str[1] == "Cost Per Unit"  || $water_item_str[6] == "Cost Per Pull" || $water_item_str[7] == "Cost Per Item")
			{
				$valueach = $_POST["txtcostperunit"][$i];
				$tot_value = $_POST["txttotalcost"][$i];
			}
			if ($water_item_str[1] == "Revenue Per Unit" || $water_item_str[6] == "Revenue Per Pull" || $water_item_str[7] == "Revenue Per Item")
			{
				$valueach = $_POST["txtrevenueperunit"][$i];
				$tot_value = $_POST["txttotalrevenue"][$i];
			}

			/***************************/
			/**Update for the adding weight unit gallon into db as per client requirement
			/**Done by Nayan
			/**Date : 12 Feb 2021
			/***************************/
			/**Note : I am not inserting any value in AmountUnit column because that field contain the water_item_str[2] value. As per my consideration this value is depends on water_item  **/

			if($_POST["weight_unit"][$i] == 'Gallon'){
				$weightUnit[$i]			=  'Pounds';
				$amountUnitEquivalent[$i] 	= $_POST["weight_unit"][$i];
			}else{
				$weightUnit[$i]			=  $_POST["weight_unit"][$i];
				$amountUnitEquivalent[$i] 	= $_POST["weight_unit"][$i];
			}

			$sql_sort = "INSERT INTO water_boxes_report_data ( entry_date, warehouse_id, trans_rec_id, box_id, count_val, weight, value_each, total_value, outlet, WeightorNumberofPulls, AmountUnit, CostOrRevenuePerUnit, unit_count, weight_unit, CostOrRevenuePerPull, CostOrRevenuePerItem, AmountUnitEquivalent) ";
			$sql_sort .= " VALUES ('" . $sort_date . "', '" . $warehouse_id . "', '" . $trans_rec_id . "', '" . $water_item_str[0] . "', '', '" . $_POST["txtweight"][$i] . "', '" . $valueach . "', '" . $tot_value . "', '" . $water_item_str[4] . "', '" . $water_item_str[5] . "', '" . $water_item_str[2] . "', '" . $water_item_str[1] . "', '" . $_POST["txtunitcount"][$i] . "', '" . $weightUnit[$i] . "', '" . $water_item_str[6] . "', '" . $water_item_str[7] ."','". $amountUnitEquivalent[$i] ."')";
			$result_sort = db_query($sql_sort,db() );
		
			if ($_POST["totalvalue"][$i] > 0){
				$tot_value = $tot_value + $_POST["totalvalue"][$i];
			}
		}
		
	}
	
	$result_sort = db_query("DELETE FROM water_trans_addfees WHERE trans_id = ". $trans_rec_id,db() );
	if ($count_add_ctrl > 0){
		
		$fees_tot_value = 0;
		for($i=0;$i<$count_add_ctrl;$i++){
			$savings_calculation_category_arr = explode("|" , $_POST["savings_calculation_category"][$i]);
			
			$sql_sort = "INSERT INTO water_trans_addfees (trans_id, add_fees_id, add_fees, add_fees_occurance, add_fee_remark, savings_calculation_category_id, savings_calculation_category_flg) ";
			$sql_sort .= " VALUES ('" . $trans_rec_id . "', '" . $_POST["selfees"][$i] . "', '" . $_POST["txtcharge"][$i] . "', '" . $_POST["txtOccurrences"][$i] . "', '" . str_replace("'", "\'" , $_POST["txtremark_fees"][$i]) . "', '" . $savings_calculation_category_arr[0] . "', '" . $savings_calculation_category_arr[1] . "')";
			if ($_POST["txtcharge"][$i] > 0){
				$fees_tot_value = $fees_tot_value + $_POST["txtcharge"][$i];
			}
			
			$result_sort = db_query($sql_sort,db() );
		}
		$fin_tot_value = $tot_value - $fees_tot_value;
	}

	/***************************/
	/**Update the query with adding repor_entry_emp field value
	/**Done by Nayan
	/**Date : 11 Feb 2021
	/**Update the query for edited by & entered by field
	/**Date : 17 Feb 2021
	/***************************/
	if($_POST['hidtxtnumrows'] == 0){
		$reportEntryEditedEmp 	= $_POST['txtEditedBY'];
		$repor_entry_emp 		= $_POST['txtEditedBY'];
	}else{
		$reportEntryEditedEmp 	= $_POST['txtEditedBY'];
		$repor_entry_emp 		= $_COOKIE['userinitials'];
	}
		
	//To update the final amount, as per #438 bug
	$rec_id = $trans_rec_id;
	$total_value_tot = 0; $tot_cost = 0; $tot_revenue = 0;
	$dt_view_qry = "SELECT * FROM water_boxes_report_data Left JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.id WHERE water_boxes_report_data.trans_rec_id = '" . $rec_id . "'";
	$dt_view_res = db_query($dt_view_qry,db() );
	while ($dt_view_row = array_shift($dt_view_res)) {
		$total_value_tot = $total_value_tot + $dt_view_row["total_value"];
		if ($dt_view_row["CostOrRevenuePerUnit"] == "Revenue Per Unit"  || $dt_view_row["CostOrRevenuePerPull"] == "Revenue Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Revenue Per Item") {
			$tot_revenue = $tot_revenue + $dt_view_row["total_value"];
		}
		if ($dt_view_row["CostOrRevenuePerUnit"] == "Cost Per Unit" || $dt_view_row["CostOrRevenuePerPull"] == "Cost Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Cost Per Item") { 
			$tot_cost = $tot_cost - $dt_view_row["total_value"];
		}
	}
	$dt_view_qry = "SELECT * from water_boxes_report_data WHERE trans_rec_id = '" . $rec_id . "' LIMIT 0,1";
	$dt_view_res = db_query($dt_view_qry,db() );
	while ($dt_view_row = array_shift($dt_view_res)) {
		$total_value_tot = $tot_revenue + $tot_cost;
	}
	$tot_add_fee = 0;
	$query = db_query("SELECT * FROM water_trans_addfees left join water_additional_fees on water_additional_fees.id = water_trans_addfees.add_fees_id where trans_id = " . $rec_id, db() );
	while ($rowsel_getdata = array_shift($query)){
		if ($rowsel_getdata["active_flg"] == 1 || $rowsel_getdata["add_fees_id"] == 0) {
			$tot_add_fee = $tot_add_fee + ($rowsel_getdata["add_fees"] * $rowsel_getdata["add_fees_occurance"]);
		}
	}
	$total_charges = $total_value_tot - $tot_add_fee;
	//$_POST["final_total"]	
	if(isset($_REQUEST['txtNetcostRev'])){
		$vendors_net_cost_or_revenue = $_REQUEST['txtNetcostRev'];
	}else{
		$vendors_net_cost_or_revenue = 0;
	}
	if(isset($_REQUEST['txtGrossSavings'])){
		$gross_savings = $_REQUEST['txtGrossSavings'];
	}else{
		$gross_savings = 0;
	}
	if(isset($_REQUEST['txtClientNetSavings'])){
		$client_net_savings = $_REQUEST['txtClientNetSavings'];
	}else{
		$client_net_savings = 0;
	}
	if(isset($_REQUEST['txtUCBSavingsSplit'])){
		$ucb_savings_split = $_REQUEST['txtUCBSavingsSplit'];
	}else{
		$ucb_savings_split = 0;
	}
	if(isset($_REQUEST['txtTotalDueToUcb'])){
		$total_due_to_ucb = $_REQUEST['txtTotalDueToUcb'];
	}else{
		$total_due_to_ucb = 0;
	}
		
	$sql_sort = "UPDATE water_transaction SET doubt = '" . preg_replace("/'/", "\'", $_POST["txtdoubt"]) . "',  invoice_currency = '" . $_POST["invoice_currency"] . "', have_doubt = '" . $_POST["chkdoubt"] . "', last_edited = '" . date("Y-m-d H:i:s") . "', 
	service_begin_date = '" . $_POST["service_begin_date"] . "', new_invoice_date = '" . $_POST["new_invoice_date"] . "', invoice_date = '" . $_POST["invoice_date"] . "', invoice_due_date = '" . $_POST["invoice_due_date"] . "', invoice_number = '" . preg_replace("/'/", "\'", $_POST["invoice_no"]) . "', vendor_id = '" . $_POST["vendor_id"] . "', report_date = '" . $_POST["report_date"] . "', amount = '" . $total_charges . "', scan_report = '" . preg_replace("/'/", "\'", $scan_rep_name) . "', report_notes = '" . preg_replace("/'/", "\'", $_POST["boxnotes"]) . "', make_receive_payment = '" . $make_receive_payment . "', made_payment = '" . $made_payment . "', paid_by = '" . $paid_by . "', paid_date = '" . $paid_date . "', payment_method = '" . $payment_method . "', vendor_credit = '".$vendor_credit."', payment_proof_file = '" . preg_replace("/'/", "\'", $payment_proof_name) . "', report_entered = 1, reportEntryEditedEmp = '".$reportEntryEditedEmp."', vendors_net_cost_or_revenue = '".$vendors_net_cost_or_revenue."', gross_savings = '".$gross_savings."', client_net_savings = '".$client_net_savings."', ucb_savings_split = '".$ucb_savings_split."', total_due_to_ucb = '".$total_due_to_ucb."' WHERE id = " . $trans_rec_id;

	$result_sort = db_query($sql_sort,db() );
}

	//to convert the weight into pound
	//$query_mtd = "SELECT weight_unit, water_boxes_report_data.id, Estimatedweight , Estimatedweight_value, water_boxes_report_data.unit_count, water_boxes_report_data.WeightorNumberofPulls, value_each, weight, weight_in_pound, water_boxes_report_data.AmountUnit, water_inventory.AmountUnit as InvAmountUnit from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID where weight >0 and trans_rec_id = " .$trans_rec_id;
	$query_mtd = "SELECT weight_unit, water_boxes_report_data.id, Estimatedweight , Estimatedweight_value, water_boxes_report_data.unit_count, 
	water_boxes_report_data.WeightorNumberofPulls, value_each, weight, weight_in_pound, water_boxes_report_data.AmountUnit, water_inventory.AmountUnit as InvAmountUnit, 
	Estimatedweight_peritem,  Estimatedweight_value_peritem, water_boxes_report_data.AmountUnitEquivalent from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID where weight >0 and trans_rec_id = " . $trans_rec_id; 
	$res = db_query($query_mtd, db());

	while($row_mtd = array_shift($res))
	{
		$weight_in_pound  = 0; $avg_price_per_pound = 0;
		if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["weight_unit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons"){
			$weight_in_pound = $row_mtd["weight"] * 2000;
		}

		if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["weight_unit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms"){
			$weight_in_pound = $row_mtd["weight"] * 2.20462;
		}
		if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds"){
			$weight_in_pound = $row_mtd["weight"];
		}

		if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons"){
			$avg_price_per_pound = $row_mtd["value_each"]/2000;
		}

		if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms"){
			$avg_price_per_pound = $row_mtd["value_each"] * 2.20462;
		}

		if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds"){
			$avg_price_per_pound = $row_mtd["value_each"];
		}

		/***************************/
		/**Update for the avg_price_per_pound when weight_unit is gallon as per client requirement
		/**Done by Nayan
		/**Date : 12 Feb 2021
		/**Note : Here I'm not considering InvAmountUnit which is come from water_inventory	**/
		/***************************/
		
		if ($row_mtd["weight_unit"] == "Pounds" && $row_mtd["AmountUnitEquivalent"] == "Gallon" ){
			$avg_price_per_pound = $row_mtd["value_each"] * 11;
		}

		if ($row_mtd["InvAmountUnit"] == "Gallon" || $row_mtd["AmountUnit"] == "Gallon"){
			$weight_in_pound = $row_mtd["weight"] * 11;
		}
		


		
		if ($row_mtd["WeightorNumberofPulls"] != "By Weight"){
			if ($row_mtd["WeightorNumberofPulls"] == "By Number of Pulls"){
				if ($row_mtd["weight"] > 0 && $row_mtd["unit_count"] > 0){
					if ($row_mtd["weight_unit"] == "Tons"){
						$weight_in_pound = ($row_mtd["weight"] * 2000) * $row_mtd["unit_count"];
					}	
					if ($row_mtd["weight_unit"] == "Pounds"){
						$weight_in_pound = $row_mtd["weight"] * $row_mtd["unit_count"];
					}	
					if ($row_mtd["weight_unit"] == "Kilograms"){
						$weight_in_pound = ($row_mtd["weight"]* 2.20462) * $row_mtd["unit_count"];
					}
					/***************************/
					/**Update for the weight_in_pound when weight_unit is gallon as per client requirement
					/**Done by Nayan
					/**Date : 12 Feb 2021
					/***************************/
					if ($row_mtd["weight_unit"] == "Pounds" && $row_mtd["AmountUnitEquivalent"] == "Gallon" ){
						$weight_in_pound = ($row_mtd["weight"]* 11) * $row_mtd["unit_count"];
					}	
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
					}	
				}		
				$avg_price_per_pound = $row_mtd["value_each"];											
			}elseif ($row_mtd["WeightorNumberofPulls"] == "Per Item"){
				if (($row_mtd["weight"] > 0) && ($row_mtd["unit_count"] > 0)){
				//$row_mtd["Estimatedweight_peritem"] == "Tons" ||
					if ($row_mtd["weight_unit"] == "Tons"){
						//$weight_in_pound = ($row_mtd["Estimatedweight_value_peritem"] * 2000) * $row_mtd["unit_count"];
						$weight_in_pound = ($row_mtd["weight"] * 2000) * $row_mtd["unit_count"];
					}	
					if ($row_mtd["weight_unit"] == "Pounds"){
						//$weight_in_pound = $row_mtd["Estimatedweight_value_peritem"] * $row_mtd["unit_count"];
						$weight_in_pound = $row_mtd["weight"] * $row_mtd["unit_count"];
					}	
					if ($row_mtd["weight_unit"] == "Kilograms"){
						//$weight_in_pound = ($row_mtd["Estimatedweight_value_peritem"]* 2.20462) * $row_mtd["unit_count"];
						$weight_in_pound = ($row_mtd["weight"]* 2.20462) * $row_mtd["unit_count"];
					}
					/***************************/
					/**Update for the weight_in_pound when weight_unit is gallon as per client requirement
					/**Done by Nayan
					/**Date : 12 Feb 2021
					/***************************/	
					if ($row_mtd["weight_unit"] == "Pounds" && $row_mtd["AmountUnitEquivalent"] == "Gallon" ){
						$weight_in_pound = ($row_mtd["weight"]* 11) * $row_mtd["unit_count"];
					}
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
					}	
				}		
				$avg_price_per_pound = $row_mtd["value_each"];											
			}else{
				if ($row_mtd["weight_unit"] == "Kilograms" || $row_mtd["weight_unit"] == "Tons"){
					
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
					}	
				}	
				$avg_price_per_pound = $row_mtd["value_each"];
				
				
			}
		}	

		if ($row_mtd["id"] == 594) {
		//		echo $row_mtd["WeightorNumberofPulls"] . " Weight:" . $row_mtd["weight"] . " InvAmountUnit:" . $row_mtd["InvAmountUnit"] . " Estimatedweight:" . $row_mtd["Estimatedweight"] . " Estimatedweight_value:" . $row_mtd["Estimatedweight_value"] . " Estimatedweight_peritem:" . $row_mtd["Estimatedweight_peritem"] . " Estimatedweight_value_peritem:" . $row_mtd["Estimatedweight_value_peritem"] . " unit_count: " .  $row_mtd["unit_count"] . " <b> Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = " . $avg_price_per_pound . " where id = " . $row_mtd["id"] . "<br>";									
		}
		$res_ret = db_query("Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = '" . $avg_price_per_pound . "' where id = " . $row_mtd["id"], db());
	}					

	
	$total_cost_parent = 0; $landfill_diversion = 0;

    $st_date = date("Y-01-01");
	$end_date = date("Y-m-d");
	
	/*$result_vendor = db_query("SELECT warehouse_id, water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where outlet <> '' and warehouse_id = " . $warehouse_id . " and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by warehouse_id order by warehouse_id", db());
	while($row_vendor = array_shift($result_vendor))
	{
		$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
		$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id = " . $row_vendor["warehouse_id"] . " and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59')";
		//echo $query_mtd . "<br>";
		$totalval_tot_parent = 0;
		$res = db_query($query_mtd, db());
		while($row_mtd = array_shift($res))
		{								
			if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull"){
				$totalval_tot_parent = $totalval_tot_parent - $row_mtd["totalval"];
			}else{
				$totalval_tot_parent = $totalval_tot_parent + $row_mtd["totalval"];
			}
		}

		$query_mtd = "SELECT water_vendors.Name as Vendorname, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";
		$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";
		$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";
		$query_mtd .= " inner join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
		$query_mtd.= " WHERE company_id = " . $row_vendor["warehouse_id"] . " and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";
		$res = db_query($query_mtd, db());
		while($row_mtd = array_shift($res))
		{							
			$totalval_tot_parent = $totalval_tot_parent - $row_mtd["addfees"];
		}
		
		$total_cost_parent = $total_cost_parent + $totalval_tot_parent;
		
	} 
	
	$ucb_item_totamt_parent = 0; 
	$query_mtd1 = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
	$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
	$query_mtd1 .= " WHERE loop_transaction.warehouse_id = " . $warehouse_id . " and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59'  group by boxgood";
	$res1 = db_query($query_mtd1, db());
	while($row_mtd1 = array_shift($res1))
	{
		$ucb_item_totamt_parent = $ucb_item_totamt_parent + $row_mtd1["totamt"];
	}
	
	$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
	$query_mtd1 .= " WHERE loop_transaction.warehouse_id = " . $warehouse_id . " and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' ";
	$res1 = db_query($query_mtd1, db());
	while($row_mtd1 = array_shift($res1))
	{
		$ucb_item_totamt_parent = $ucb_item_totamt_parent + $row_mtd1["freightcharge"];
		$ucb_item_totamt_parent = $ucb_item_totamt_parent + $row_mtd1["othercharge"];
	}	
	//echo "ucb_item_totamt : " . $ucb_item_totamt . "<br>";
	
	$total_cost_parent = $total_cost_parent + $ucb_item_totamt_parent;		

	//For Diversion report
	$sumtot_parent = 0; 
	$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
	$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE warehouse_id = (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
	$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
	$result = db_query($query_mtd, db());
	while($row = array_shift($result))
	{
		$sumtot_parent = $sumtot_parent + $row["sumweight"];
	}
	
	$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.tree_saved_per_ton, loop_boxes.type, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
	$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
	$query_mtd1 .= " WHERE loop_transaction.warehouse_id = (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
	$res1 = db_query($query_mtd1, db());
	while($row_mtd1 = array_shift($res1))
	{
		$sumtot_parent = $sumtot_parent + $row_mtd1["sumweight"];
	}
	
	$outlet_array = array("Reuse","Recycling","Waste To Energy","Incineration (No Energy Recovery)","Landfill");
	
	$totalval_tot = 0; $weightval_tot = 0; $display_flg1 = "n"; $display_flg2 = "n"; $display_flg3 = "n";
	$arrlength = count($outlet_array); 
	for($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
	
		$weightval = 0; $valueeachval = 0; $totalval = 0;
		$valueeachval_tot = 0; $weight_tot = 0; $amt_tot = 0;
		$tot_show = "y";
		$trans_rec_id_list = "";

		if ($outlet_array[$arrycnt] == "Recycling") {
			
			if ($display_flg1 == "n"){
				$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
				$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id = (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
				//echo $query_mtd1 . "<br>";
				$res1 = db_query($query_mtd1, db());
				while($row_mtd1 = array_shift($res1))
				{
					$weightval = $row_mtd1['sumweight'];
					$display_flg1 = "y";
					$weight_tot = $weight_tot + $weightval;
				}
			}	
		
		}else if ($outlet_array[$arrycnt] == "Reuse") {
			
			if ($display_flg2 == "n"){
				$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
				$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id = (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
				//echo $query_mtd1 . "<br>";
				$res1 = db_query($query_mtd1, db());
				while($row_mtd1 = array_shift($res1))
				{
					$weightval = $row_mtd1['sumweight'];

					$display_flg2 = "y";
				
					$weight_tot = $weight_tot + $weightval;
				}
			}	
		
		}else if ($outlet_array[$arrycnt] == "Waste To Energy") {
			
			if ($display_flg3 == "n"){
				$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
				$query_mtd1 .= " WHERE loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id = (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
				//echo $query_mtd1 . "<br>";
				$res1 = db_query($query_mtd1, db());
				while($row_mtd1 = array_shift($res1))
				{
					$weightval = $row_mtd1['sumweight'];

					$display_flg3 = "y";
				
					$weight_tot = $weight_tot + $weightval;
				}
			}	
		}
		
		$query_mtd  = "SELECT sum(weight_in_pound) as weightval, sum(avg_price_per_pound) as valueeachval, sum(total_value) as totalval, sum(unit_count) as itemcount, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
		$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE warehouse_id = (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') and water_inventory.Outlet = '" . $outlet_array[$arrycnt] . "'";
		$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
		//echo $query_mtd . "<br>";
		$res = db_query($query_mtd, db());
		while($row_mtd = array_shift($res))
		{								
			$weightval = $row_mtd["weightval"];

			$weight_tot = $weight_tot + $weightval;
		} 
		
		$per_val = 0; $per_val_2 = 0;
		//echo "weight_tot - " . $weight_tot . " " . $sumtot_parent . "<br>";
		if ($sumtot_parent > 0){
			$per_val_2 = number_format(($weight_tot/$sumtot_parent)*100,2);
			$landfill_diversion = $landfill_diversion + $per_val_2;
		}
	}*/		
	//
	$water_cron_flg_year = date("Y"); $water_cron_flg_month = date("m");
	$query = db_query("SELECT invoice_date FROM water_transaction WHERE id = '" . $trans_rec_id . "' ", db() );
	while ($rowsel_getdata = array_shift($query)) {
		if ($rowsel_getdata["invoice_date"] != ""){
			$water_cron_flg_year = date("Y", strtotime($rowsel_getdata["invoice_date"]));
			$water_cron_flg_month = date("m", strtotime($rowsel_getdata["invoice_date"]));
		}
	}

	$sql_c = "UPDATE companyInfo SET water_cron_flg = 1, water_cron_flg_year = '" . $water_cron_flg_year . "' WHERE ID = '" . $_REQUEST['ID'] . "'";
	$result_c = db_query($sql_c,db_b2b() );
	//
	
	if ($water_cron_flg_year < (date("Y")-1)){
		$checkval="successful";
		
		if ($_POST["chkdoubt"] == 1){
			$checkval="info_needed";
		}
		
		$loginid = 0; $parent_comp_flg = 0;
		$sql_chk = "Select loginid, parent_comp_flg FROM supplierdashboard_usermaster where companyid = '" . $_REQUEST['ID'] . "'"; 
		$result = db_query($sql_chk, db());
		while ($myrowsel = array_shift($result)) {
			$loginid = $myrowsel["loginid"];
		}		
		$vendor_id = $_POST["vendor_id"];

		$vender_nm = "";
		$sql_chk = "Select name FROM water_vendors where id = '" . $vendor_id . "'"; 
		$result = db_query($sql_chk, db());
		while ($myrowsel = array_shift($result)) {
			$vender_nm = $myrowsel["name"];
		}		
		
		$activity_details_flg = "no"; 
		$sql_chk = "Select id FROM vendor_report_greenchecks_cron where comp_id = '" . $warehouse_id . "' 
		and vendor_id = '" . $vendor_id . "' and year = '" . $water_cron_flg_year . "' and month1 = '" . $water_cron_flg_month . "'";
		//echo $sql_chk . "<br>";
		$result = db_query($sql_chk, db());
		while ($myrowsel = array_shift($result)) {
			$activity_details_flg = "yes";
		}

		$query = "SELECT company_id, have_doubt, doubt, no_invoice_due_flg, amount from water_transaction 
		where company_id = " . $warehouse_id . " and vendor_id = " . $vendor_id . " and Month(invoice_date) = " . $water_cron_flg_month . " 
		and Year(invoice_date) = " . $water_cron_flg_year;
		//echo $query . "<br>";
		$final_tot = 0; $final_cnt = 0;
		$res = db_query($query);
		$rec_found = "no"; $have_doubt = ""; $doubt_txt = ""; $no_invoice_due_flg = "";
		while($row = array_shift($res))
		{
			$final_tot = $final_tot + $row["amount"];
			$final_cnt = $final_cnt + 1;
		}
		
		if ($activity_details_flg == "yes"){
			$sql_chk = "Update vendor_report_greenchecks_cron set re_updated = 1, re_updated_on = '" . date("Y-m-d H:i:s") . "', check_val = '" . $checkval . "', no_of_entries = $final_cnt, `total` = $final_tot , 
			`doubt_txt`= '" . preg_replace("/'/", "\'", $_POST["txtdoubt"]) . "', vendor = '". str_replace("'", "\'", $vender_nm) ."', parent_flg = $parent_comp_flg 
			where comp_id = '" . $warehouse_id . "' and vendor_id = '" . $vendor_id . "' and year = '" . $water_cron_flg_year . "' 
			and month1 = '" . $water_cron_flg_month . "'";
			//echo $sql_chk . "<br>";
			$result2 = db_query($sql_chk, db()) ;
		}else{
			$sql_chk = "Insert into vendor_report_greenchecks_cron (re_updated, re_updated_on, comp_id, vendor_id, year, month1, vendor, parent_flg, check_val, no_of_entries, `total`, doubt_txt) 
			select 1, '" . date("Y-m-d H:i:s") . "', '" . $warehouse_id . "', '" . $vendor_id . "', '" . $water_cron_flg_year . "', '" . $water_cron_flg_month . "', '". str_replace("'", "\'", $vender_nm) ."' ,
			'" . $parent_comp_flg . "', '" . $checkval . "', '" . $final_cnt . "', '" . str_replace(",", "" , $final_tot) . "',  '" . preg_replace("/'/", "\'", $_POST["txtdoubt"]) . "' ";
			//echo $sql_chk . "<br>";
			$result2 = db_query($sql_chk, db()) ;
		}	
	}
	/*$data_found = "n";	
	$res1 = db_query("Select warehouse_id from water_cron_fordash where warehouse_id = " . $warehouse_id, db());
	while($row_mtd1 = array_shift($res1)){
		$data_found = "y";	
	}
				
	if ($data_found == "y")
	{
		$res1 = db_query("Update water_cron_fordash set waste_financial = '" . $total_cost_parent . "', landfill_diversion = '" . $landfill_diversion . "' where warehouse_id = " . $warehouse_id , db());
	}else{
		$res1 = db_query("Insert into water_cron_fordash (waste_financial, landfill_diversion, warehouse_id) select '" . $total_cost_parent . "', '" . $landfill_diversion . "', " . $warehouse_id, db());
	}*/	
		

echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";


//redirect("viewCompany-purchasing.php?ID=".$_REQUEST['ID']."&show=watertransactions&warehouse_id=". $warehouse_id ."&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $trans_rec_id. "&display=seller_sort");
redirect("viewCompany_func_water-mysqli.php?ID=".$_REQUEST['ID']."&show=watertransactions&warehouse_id=". $warehouse_id ."&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $trans_rec_id. "&display=water_sort");//seller_sort
?>