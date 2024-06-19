<?
	set_time_limit(0);	
	ini_set('memory_limit', '-1');

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");

	db();

	$data_year = date("Y");
	//$data_year = 2023;

	$query_mtd = "SELECT water_inventory.poundpergallon_value, weight_unit, water_boxes_report_data.id, Estimatedweight , Estimatedweight_value, water_boxes_report_data.unit_count, 
	water_boxes_report_data.WeightorNumberofPulls, value_each, weight, weight_in_pound, water_boxes_report_data.AmountUnit, water_inventory.AmountUnit as InvAmountUnit, water_boxes_report_data.AmountUnitEquivalent,
	Estimatedweight_peritem,  Estimatedweight_value_peritem from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID 
	inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id where weight >0 ";
	//and water_boxes_report_data.warehouse_id = '1470' and (invoice_date >= '2023-01-01' AND invoice_date <= '2023-01-31 23:59:59') and water_boxes_report_data.outlet = 'WASTE TO ENERGY' and water_inventory.description = '275Gal Tote Non-RCRA Hazardous Waste Liquid (Cosmetic Waste)' 
	//and water_inventory.vendor = 1937 and water_boxes_report_data.outlet = 'Landfill'"; //and  water_inventory.description = 'Compactor - 30 Yd' and  and water_boxes_report_data.outlet = 'Waste To Energy' and water_boxes_report_data.id = 185550 
	$res = db_query($query_mtd);
	while($row_mtd = array_shift($res))
	{
		$weight_in_pound  = 0; $avg_price_per_pound = 0;
		if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["weight_unit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons"){
			$weight_in_pound = $row_mtd["weight"] * 2000;
			//echo "in step 1 <br>";
		}

		if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["weight_unit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms"){
			$weight_in_pound = $row_mtd["weight"] * 2.20462;
			//echo "in step 2 <br>";
		}

		//If unit is blank then weight should be 0 || $row_mtd["AmountUnit"] == "Pounds"
		if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds"){
			$weight_in_pound = $row_mtd["weight"];
			//echo "in step 3 <br>";
		}
		
		if ($row_mtd["InvAmountUnit"] == "Tons" || $row_mtd["AmountUnit"] == "Tons"){
			$avg_price_per_pound = $row_mtd["value_each"]/2000;
			//echo "in step 4 <br>";
		}

		if ($row_mtd["InvAmountUnit"] == "Kilograms" || $row_mtd["AmountUnit"] == "Kilograms"){
			$avg_price_per_pound = $row_mtd["value_each"] * 2.20462;
			//echo "in step 5 <br>";
		}

		if ($row_mtd["InvAmountUnit"] == "Pounds" || $row_mtd["AmountUnit"] == "Pounds"){
			$avg_price_per_pound = $row_mtd["value_each"];
			//echo "in step 6 <br>";
		}
		
		if ($row_mtd["WeightorNumberofPulls"] != "By Weight"){
			if ($row_mtd["WeightorNumberofPulls"] == "By Number of Pulls"){
				if ($row_mtd["weight"] > 0 && $row_mtd["unit_count"] > 0){
					if ($row_mtd["weight_unit"] == "Tons"){
						$weight_in_pound = ($row_mtd["weight"] * 2000) * $row_mtd["unit_count"];
						//echo "in step 7 <br>";
					}	
					//If unit is blank then weight should be 0 || $row_mtd["weight_unit"] == "Pounds"
					if ($row_mtd["weight_unit"] == "Pounds"){
						$weight_in_pound = $row_mtd["weight"] * $row_mtd["unit_count"];
						//echo "in step 8 <br>";
					}	
					if ($row_mtd["weight_unit"] == "Kilograms"){
						$weight_in_pound = ($row_mtd["weight"]* 2.20462) * $row_mtd["unit_count"];
						//echo "in step 9 <br>";
					}	
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
						//echo "in step 10 <br>";
					}	
				}		
				$avg_price_per_pound = $row_mtd["value_each"];											
			}elseif ($row_mtd["WeightorNumberofPulls"] == "Per Item"){
				if (($row_mtd["weight"] > 0) && ($row_mtd["unit_count"] > 0)){
				//$row_mtd["Estimatedweight_peritem"] == "Tons" ||
					//if ($row_mtd["AmountUnit"] == "Tons" || $row_mtd["Estimatedweight_peritem"] == "Tons"){
					if ($row_mtd["weight_unit"] == "Tons"){
						//$weight_in_pound = ($row_mtd["Estimatedweight_value_peritem"] * 2000) * $row_mtd["unit_count"];
						$weight_in_pound = ($row_mtd["weight"] * 2000) * $row_mtd["unit_count"];
						//echo "in step 11 <br>";
					}	

					//If unit is blank then weight should be 0 || $row_mtd["weight_unit"] == "Pounds" 
					//if ($row_mtd["AmountUnit"] == "Pounds" || $row_mtd["Estimatedweight_peritem"] == "Pounds"){
					if ($row_mtd["weight_unit"] == "Pounds"){
						//$weight_in_pound = $row_mtd["Estimatedweight_value_peritem"] * $row_mtd["unit_count"];
						$weight_in_pound = $row_mtd["weight"] * $row_mtd["unit_count"];
						//echo "in step 12 <br>";
					}	

					//if ($row_mtd["AmountUnit"] == "Kilograms" || $row_mtd["Estimatedweight_peritem"] == "Kilograms"){
					if ($row_mtd["weight_unit"] == "Kilograms"){
						//$weight_in_pound = ($row_mtd["Estimatedweight_value_peritem"]* 2.20462) * $row_mtd["unit_count"];
						$weight_in_pound = ($row_mtd["weight"]* 2.20462) * $row_mtd["unit_count"];
						//echo "in step 13 <br>";
					}	
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
						//echo "in step 14 <br>";
					}	
				}		
				$avg_price_per_pound = $row_mtd["value_each"];											
			}else{
				if ($row_mtd["weight_unit"] == "Kilograms" || $row_mtd["weight_unit"] == "Tons"){
					
				}else{
					if ($weight_in_pound == 0){
						$weight_in_pound = $row_mtd["weight"];
						//echo "in step 15 <br>";
					}	
				}	
				$avg_price_per_pound = $row_mtd["value_each"];
				
				
			}
		}	

		if ($row_mtd["InvAmountUnit"] == "Gallon" || $row_mtd["AmountUnitEquivalent"] == "Gallon"){
			if ($row_mtd["unit_count"] > 0){
				$weight_in_pound = (str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["unit_count"])) * str_replace(",", "", $row_mtd["poundpergallon_value"]);
				//echo "in step 16 <br>";
			}else{
				$weight_in_pound = str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["poundpergallon_value"]);
				//echo "in step 17 <br>";
			}															
			
			//$weight_in_pound = $row_mtd["weight"] * $row_mtd["poundpergallon_value"];
		}

		if ($row_mtd["id"] == 594) {
		//		echo $row_mtd["WeightorNumberofPulls"] . " Weight:" . $row_mtd["weight"] . " InvAmountUnit:" . $row_mtd["InvAmountUnit"] . " Estimatedweight:" . $row_mtd["Estimatedweight"] . " Estimatedweight_value:" . $row_mtd["Estimatedweight_value"] . " Estimatedweight_peritem:" . $row_mtd["Estimatedweight_peritem"] . " Estimatedweight_value_peritem:" . $row_mtd["Estimatedweight_value_peritem"] . " unit_count: " .  $row_mtd["unit_count"] . " <b> Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = " . $avg_price_per_pound . " where id = " . $row_mtd["id"] . "<br>";									
		}

		$weight_in_pound_tot = $weight_in_pound_tot + $weight_in_pound;
		
		//echo "Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = '" . $avg_price_per_pound . "' where id = " . $row_mtd["id"] . "<br>";
		$res_ret = db_query("Update water_boxes_report_data set weight_in_pound = " . $weight_in_pound . ", avg_price_per_pound = '" . $avg_price_per_pound . "' where id = " . $row_mtd["id"]);
		
	}					
	
	$companyid = 0;	$warehouse_id = 0; $company_name = ""; $company_logo = ""; 
	$st_date = date($data_year . "-01-01");
	$end_date = date($data_year ."-12-31");
	
	//$end_date = date($data_year . "-12-31");

	$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster where activate_deactivate = 1 and parent_comp_flg = 1 group by companyid order by loginid";
	//$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid = 108 order by loginid";

	//loginid = 93 and
	//echo $sql . "<br>";
	$warehouse_id_parent = 0;	
	$result_main = db_query($sql, db());
	while ($myrowsel = array_shift($result_main)) {
		$parent_comp_flg = $myrowsel['parent_comp_flg'];
		
		$tree_saved= 0;
		$warehouse_id = 0; $water_cron_flg_year = date("Y");
		//
		$compid = $myrowsel['companyid']; 
		$vcsql = "select ID, ucbzw_flg, water_cron_flg, water_cron_flg_year from companyInfo where water_cron_flg=1 and ID=". $myrowsel['companyid'];
		$vcresult = db_query($vcsql, db_b2b());
		while($vcrow = array_shift($vcresult))
		{
			$show = "yes";
			if ($vcrow["water_cron_flg_year"] > 0){
				$water_cron_flg_year = $vcrow["water_cron_flg_year"];
			}	
		}
		db();
		
		//
		if($show == "yes")
		{
			$data_year = $water_cron_flg_year;
			$st_date = date($data_year . "-01-01");
			$end_date = date($data_year ."-12-31");
			
			$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? " ;
			$result1 = db_query($sql1, array("i") , array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				//$warehouse_id = $myrowsel1["id"];
				$warehouse_id_parent = $myrowsel1["id"];
			}
			if ($warehouse_id_parent == 0) {
				$warehouse_id_parent = $myrowsel["companyid"];
			}			

			if ($parent_comp_flg == 1) {
				db_b2b();
				$vcsql = "select ID, loopid, parent_child, parent_comp_id from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=". $myrowsel['companyid'];
				$vcresult = db_query($vcsql);
				while($vcrow = array_shift($vcresult))
				{
					if ($vcrow["loopid"] > 0) {
						$ch_id = $vcrow["loopid"];
						if ($warehouse_id == 0){
							$warehouse_id = $ch_id . ",";
						}else{
							$warehouse_id .= $ch_id . ",";
						}					
					}	
				}
				db();
			}

			if ($warehouse_id != ""){
				$warehouse_id = substr($warehouse_id, 0, strlen($warehouse_id)-1);
			}

			$sumtot = 0; 
			$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
			$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE  water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
			$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
	echo "Water Step 1 - " . $query_mtd . "<br>";		
			$result = db_query($query_mtd);
			while($row = array_shift($result))
			{
				$sumtot = $sumtot + $row["sumweight"];
			}
	//echo "sumtot " . $sumtot . "<br>";

			$tree_saved_val = 0;
			$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
			$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE  water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
			$query_mtd .= " group by water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
	echo "Step 2 - " . $query_mtd . "<br>";		

			$result = db_query($query_mtd);
			while($row = array_shift($result))
			{
				//if (($row["Outlet"] == "Reuse" || $row["Outlet"] == "Recycling")){
					if ($row["tree_saved_per_ton"] > 0 && $row["sumweight"] > 0) {
						$tree_saved_val = $tree_saved_val + (($row["sumweight"]/2000)*$row["tree_saved_per_ton"]);
						echo "$warehouse_id Tree_saved_val1 : " . $tree_saved_val . "<br>";
					}
				//}	
			}

			$tree_saved = $tree_saved_val;

			$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.tree_saved_per_ton, loop_boxes.type, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
	echo "Loop - Step 3 - " . $query_mtd1 . "<br>";		
			$tree_saved_val = 0; $sumtot_chk = 0;
			$res1 = db_query($query_mtd1);
			while($row_mtd1 = array_shift($res1))
			{
				$sumtot = $sumtot + $row_mtd1["sumweight"];
			        if ($row_mtd1["tree_saved_per_ton"] > 0 && $row_mtd1["sumweight"] > 0){
				   $sumtot_chk = $sumtot_chk + (($row_mtd1["sumweight"]/2000)*$row_mtd1["tree_saved_per_ton"]);
			        }

				if ($row_mtd1["sumweight"] > 0 && ($row_mtd1["type"] == "Recycling" || $row_mtd1["type"] == "Other") ) {
					if ($row_mtd1["tree_saved_per_ton"] > 0){
						//$tree_saved_val = $tree_saved_val + (($row_mtd1["sumweight"]/2000)*$row_mtd1["tree_saved_per_ton"]);
						//echo "$warehouse_id Tree_saved_val2 : " . $tree_saved_val . "<br>";
					}	
				}else{
					//$tree_saved_val = $tree_saved_val + (($row_mtd1["sumweight"]/2000)*17);
					//echo "$warehouse_id Tree_saved_val3 : " . $tree_saved_val . "<br>";
				}
			}

			//For the Summary table in dash
			//Additional Fees
			$query_mtd = "SELECT water_vendors.Name as Vendorname, water_transaction.vendor_id, water_trans_addfees.add_fees_id, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";
			$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";
			$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";
			$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
			$query_mtd.= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";
		echo "Water other chg - Step 4 - " . $query_mtd . "<br>";		
			//echo $query_mtd ."<br>";
			$othar_charges = 0; $vendor_nm = ""; $add_fee_tot = 0; $first_rec = "n"; $fees = 0;
			$res = db_query($query_mtd);
			while($row_mtd = array_shift($res))
			{
				$othar_charges = $othar_charges - $row_mtd["addfees"];
			}

			$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
			$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59'";
			echo "Loop other chg - Step 4 - " . $query_mtd1 . "<br>";		
		//echo $query_mtd1 . "<br>";
			$res1 = db_query($query_mtd1);
			while($row_mtd1 = array_shift($res1))
			{
				$othar_charges = $othar_charges + $row_mtd1["freightcharge"];
				$othar_charges = $othar_charges + $row_mtd1["othercharge"];
			}	

			$Recycling_tot = 0;
			$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
			echo "Step 5 - " . $query_mtd . "<br>";		
			//echo $query_mtd . "<br>";
			$res = db_query($query_mtd);
			while($row_mtd = array_shift($res))
			{								
				$Recycling_tot = $Recycling_tot + $row_mtd["sumweight"];
			}

			$Ruse_tot = 0;
			$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
			echo "Step 6 - " . $query_mtd . "<br>";		
			//echo $query_mtd . "<br>";
			$res = db_query($query_mtd);
			while($row_mtd = array_shift($res))
			{								
				$Ruse_tot = $Ruse_tot + $row_mtd["sumweight"];
			}

			$WasteToEnergy_tot = 0;
			$query_mtd = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd .= " WHERE loop_boxes_sort.boxgood > 0 and loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
			echo "Step 7 - " . $query_mtd . "<br><br><br>";		
			//echo $query_mtd . "<br>";
			$res = db_query($query_mtd);
			while($row_mtd = array_shift($res))
			{								
				$WasteToEnergy_tot = $WasteToEnergy_tot + $row_mtd["sumweight"];
			}

			if ($parent_comp_flg == 1) {
				$res = db_query("Delete from water_cron_summary_rep_new where warehouse_id = " . $warehouse_id_parent . " and data_year = '" . $data_year . "'");
			}else{
				$res = db_query("Delete from water_cron_summary_rep_new where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");
			}		

			if ($parent_comp_flg == 1) {
				$res = db_query("Delete from water_cron_fordash_piechart_new where warehouse_id = " . $warehouse_id_parent . " and data_year = '" . $data_year . "'");
			}else{
				$res = db_query("Delete from water_cron_fordash_piechart_new where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");
			}		
			$rec_added = "no";

			$outlet_array = array("Reuse","Recycling","Waste To Energy","Incineration (No Energy Recovery)","Landfill");

			$totalval_tot = 0; $weightval_tot = 0; $display_flg1 = "n"; $display_flg2 = "n"; $display_flg3 = "n"; $grand_tot = 0; $weight_tot_reuse = 0; $display_order = 0;
			$arrlength = count($outlet_array); 
			for($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
				$display_order = $display_order + 1;

				$weightval = 0; $valueeachval = 0; $totalval = 0;
				$valueeachval_tot = 0; $weight_tot = 0; $amt_tot = 0;
				$tot_show = "y";
				$trans_rec_id_list = "";

				if ($outlet_array[$arrycnt] == "Recycling") {

					if ($display_flg1 == "n"){
						$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
						$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
						$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
						//echo $query_mtd1 . "<br>";
						echo "Step 8 - " . $query_mtd . "<br>";		
						$res1 = db_query($query_mtd1);
						while($row_mtd1 = array_shift($res1))
						{
							//if ($row_mtd1['sumweight'] > 0){
								$weightval = $row_mtd1['sumweight'];
								$display_flg1 = "y";

								$weight_tot = $weight_tot + $weightval;
								$weightval_tot = $weightval_tot + $weightval;

								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
								$amt_tot = $amt_tot + $row_mtd1["totamt"];
							//}
						}
					}	

				}else if ($outlet_array[$arrycnt] == "Reuse") {

					if ($display_flg2 == "n"){
						$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
						$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
						$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
						//echo $query_mtd1 . "<br>";
						echo "Step 9 - " . $query_mtd1 . "<br>";		
						$res1 = db_query($query_mtd1);
						while($row_mtd1 = array_shift($res1))
						{
							//$Ruse_tot = $Ruse_tot + $row_mtd1['sumweight'];
							//if ($row_mtd1['sumweight'] > 0){
								$weightval = $row_mtd1['sumweight'];

								$display_flg2 = "y";

								$weight_tot = $weight_tot + $weightval;
								$weightval_tot = $weightval_tot + $weightval;

								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
								$amt_tot = $amt_tot + $row_mtd1["totamt"];

								$vendor_name= "UsedCardboardBoxes";
							//}
						}
					}	

				}else if ($outlet_array[$arrycnt] == "Waste To Energy") {

					if ($display_flg3 == "n"){
						$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight, sum(boxgood + boxbad) as itemcount from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
						$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
						$query_mtd1 .= " WHERE loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
						//echo $query_mtd1 . "<br>";
						echo "Step 10 - " . $query_mtd1 . "<br>";		
						$res1 = db_query($query_mtd1);
						while($row_mtd1 = array_shift($res1))
						{
							//$Ruse_tot = $Ruse_tot + $row_mtd1['sumweight'];
							//if ($row_mtd1['sumweight'] > 0){
								$weightval = $row_mtd1['sumweight'];

								$display_flg3 = "y";

								$weight_tot = $weight_tot + $weightval;
								$weightval_tot = $weightval_tot + $weightval;

								$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
								$amt_tot = $amt_tot + $row_mtd1["totamt"];

								$vendor_name= "UsedCardboardBoxes";
							//}
						}
					}	

				}


				//echo "weight_tot" . $weight_tot . "<br>";
				//and AmountUnitEquivalent <> 'Gallon'
				$query_mtd  = "SELECT sum(weight_in_pound) as weightval, sum(avg_price_per_pound) as valueeachval, sum(total_value) as totalval, sum(unit_count) as itemcount, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") 
				and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') and water_inventory.Outlet = '" . $outlet_array[$arrycnt] . "'";
				$query_mtd .= "  group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
				//echo "Step 8 " . $query_mtd . "<br>";
				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{								
					$weightval = $row_mtd["weightval"];

					$weight_tot = $weight_tot + $weightval;
					$weightval_tot = $weightval_tot + $weightval;

					if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull"){
						//echo "In the negative values <br>";
						$totalval_tot = $totalval_tot - $row_mtd["totalval"];
						$amt_tot = $amt_tot - $row_mtd["totalval"];
					}else{
						$totalval_tot = $totalval_tot + $row_mtd["totalval"];
						$amt_tot = $amt_tot + $row_mtd["totalval"];
					}
					
				} 

				//only for Gallon
				/*$query_mtd  = "SELECT total_value as totalval, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 and warehouse_id in (" . $warehouse_id . ") 
				and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') and water_inventory.Outlet = '" . $outlet_array[$arrycnt] . "'";
				$query_mtd .= " and AmountUnitEquivalent = 'Gallon' order by water_vendors.Name, water_boxes_report_data.box_id";
				//echo "Step 8 " . $query_mtd . "<br>";
				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{								
					if ($row_mtd["unit_count"] > 0){
						$weightval = (str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["unit_count"])) * str_replace(",", "", $row_mtd["poundpergallon_value"]);
					}else{
						$weightval = str_replace(",", "", $row_mtd["weight"]) * str_replace(",", "", $row_mtd["poundpergallon_value"]);
					}															

					//echo "<br>weightval=" .  $outlet_array[$arrycnt] . " | " . $weightval . " | " . $row_mtd["weight"] . " | " . $row_mtd["unit_count"] . " | " . $row_mtd["poundpergallon_value"] . "<br>";
					
					$weight_tot = $weight_tot + $weightval;
					$weightval_tot = $weightval_tot + $weightval;

					if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull"){
						//echo "In the negative values <br>";
						$totalval_tot = $totalval_tot - $row_mtd["totalval"];
						$amt_tot = $amt_tot - $row_mtd["totalval"];
					}else{
						$totalval_tot = $totalval_tot + $row_mtd["totalval"];
						$amt_tot = $amt_tot + $row_mtd["totalval"];
					}
				} */
				
				$per_val = 0; $per_val_2 = 0;
				if ($sumtot > 0){
					$per_val = number_format(($weight_tot/$sumtot)*100,2) . "%";
					$per_val_2 = number_format(($weight_tot/$sumtot)*100,2);
					if ($outlet_array[$arrycnt] == "Recycling" || $outlet_array[$arrycnt] == "Reuse" ||  $outlet_array[$arrycnt] == "Waste To Energy"){
						$landfill_diversion = $landfill_diversion + $per_val_2;
					}	
				}
				$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => $weight_tot, 'perc' => $per_val, 'totval' => $amt_tot);

				/*if ($outlet_array[$arrycnt] == 'Recycling')
				{
					$Recycling_tot = $Recycling_tot + $weight_tot;
				}	
				if ($outlet_array[$arrycnt] == 'Reuse')
				{
					$Ruse_tot = $Ruse_tot + $weight_tot;
				}	
				if ($outlet_array[$arrycnt] == 'Waste To Energy')
				{
					$WasteToEnergy_tot = $WasteToEnergy_tot + $weight_tot;
				}	*/
				
				if ($outlet_array[$arrycnt] == 'Reuse')
				{
					$weight_tot_reuse = $weight_tot;
				}	
				
				//commented on Feb 15 2024 $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot 
				if ($parent_comp_flg == 1) {
					$res = db_query("Insert into water_cron_summary_rep_new (data_year, warehouse_id, outlet, weight_tot, perc_val, amount_tot, sumtot_weight, sumtot_amount, other_charges, Recycling_tot, Ruse_tot, 
					WasteToEnergy_tot) select '" . $data_year . "', '" . $warehouse_id_parent . "', '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $per_val_2 . "', '" . $amt_tot . "', '" . $sumtot . "' , 
					'" . $totalval_tot . "', '" . $othar_charges . "', '0', '0', '0'");
				}else{
					$res = db_query("Insert into water_cron_summary_rep_new (data_year, warehouse_id, outlet, weight_tot, perc_val, amount_tot, sumtot_weight, sumtot_amount, other_charges, Recycling_tot, Ruse_tot, 
					WasteToEnergy_tot) select '" . $data_year . "', '" . $warehouse_id . "', '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $per_val_2 . "', '" . $amt_tot . "', '" . $sumtot . "' , 
					'" . $totalval_tot . "', '" . $othar_charges . "', '0', '0', '0'");
				}		

				$rec_added = "yes";
				if ($parent_comp_flg == 1) {
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) 
					select '" . $data_year . "', '" . $warehouse_id_parent . "', '" . $sumtot . "', 0, 0, 0, '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $display_order . "'");
				}else{
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) 
					select '" . $data_year . "', '" . $warehouse_id . "', '" . $sumtot . "', 0, 0, 0, '" . $outlet_array[$arrycnt] . "', '" . $weight_tot . "', '" . $display_order . "'");
				}		

			}

			$grand_tot = $totalval_tot + $othar_charges;
			//For the Summary table in dash

			if ($sumtot_chk > 0) {
				$tree_saved_val = $tree_saved_val + $sumtot_chk;
			}	

			echo "$warehouse_id Tree_saved_val4 : " . $tree_saved_val . "<br>";

			$tree_saved = $tree_saved + $tree_saved_val;

			/*$result = db_query("SELECT water_inventory.outlet, sum(weight_in_pound) as weight, water_outlet.display_order FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID inner join water_outlet on water_outlet.outlet = water_boxes_report_data.outlet where water_boxes_report_data.outlet <> '' and warehouse_id in (" . $warehouse_id . ")  and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by outlet order by water_outlet.display_order" );
			echo "Step 12 - " . "SELECT water_inventory.outlet, sum(weight_in_pound) as weight, water_outlet.display_order FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID inner join water_outlet on water_outlet.outlet = water_boxes_report_data.outlet where water_boxes_report_data.outlet <> '' and warehouse_id in (" . $warehouse_id . ")  and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by outlet order by water_outlet.display_order" . "<br>";				
			while ($data_row = array_shift($result)) {
				if ($data_row['outlet'] == "Reuse"){
					$weight_data = $weight_tot_reuse;		
				}else{					
					$weight_data = $data_row['weight'];			
				}
				
				$rec_added = "yes";
				if ($parent_comp_flg == 1) {
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "', '" . $warehouse_id_parent . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', '" . $data_row['outlet'] . "', '" . $weight_data . "', '" . $data_row['display_order'] . "'");
				}else{
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "', '" . $warehouse_id . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', '" . $data_row['outlet'] . "', '" . $weight_data . "', '" . $data_row['display_order'] . "'");
				}		
			}
			*/
			
			if ($rec_added == "no")
			{
				if ($parent_comp_flg == 1) {
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "','" . $warehouse_id_parent . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', 'Reuse', 0, 1");
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "','" . $warehouse_id_parent . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', 'Recycling', 0, 1");
				}else{
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "','" . $warehouse_id . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', 'Reuse', 0, 1");
					$res = db_query("Insert into water_cron_fordash_piechart_new (data_year, warehouse_id, sumtot, recycling_tot, ruse_tot, WasteToEnergy_tot , outlet, weight, display_order) select '" . $data_year . "','" . $warehouse_id . "', '" . $sumtot . "', '" . $Recycling_tot . "', '" . $Ruse_tot . "', '" . $WasteToEnergy_tot . "', 'Recycling', 0, 1");
				}		
			}

			//Total cal
			$weightval_tot_grand = 0; $total_cost = 0; 
			$totalval_tot = 0; $net_finance = 0; 
			$result_vendor = db_query("SELECT water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where  water_vendors.id <> 844 and outlet <> '' and warehouse_id in (" . $warehouse_id . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name", array("s", "s") , array($st_date, $end_date));
			echo "Step 13 - " . "SELECT water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where outlet <> '' and warehouse_id in (" . $warehouse_id . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name" . "<br>";				
			while($row_vendor = array_shift($result_vendor))
			{
				$weightval_tot = 0;

				$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
				//echo $query_mtd . "<br>";
				$totalval_tot = 0;
				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{								
					if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull"){
						$totalval_tot = $totalval_tot - $row_mtd["totalval"];
					}else{
						$totalval_tot = $totalval_tot + $row_mtd["totalval"];
					}
				}

				$query_mtd = "SELECT water_vendors.Name as Vendorname, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";
				$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";
				$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";
				$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
				$query_mtd.= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";
				$add_fees = 0;
				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{							
					//echo "Add fee: " . $row_mtd["addfees"] . "<br>";
					$add_fees = $add_fees + $row_mtd["addfees"];
					$totalval_tot = $totalval_tot - $row_mtd["addfees"];
				}

				$weightval_tot_grand = $weightval_tot_grand + $weightval_tot;
				$total_cost = $total_cost + $totalval_tot;

			} 

			$ucb_item_totamt = 0; 
			$query_mtd1 = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59'  group by boxgood";

			$res1 = db_query($query_mtd1);
			while($row_mtd1 = array_shift($res1))
			{
				$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["totamt"];
			}
			//echo "ucb_item_totamt : " . $ucb_item_totamt . "<br>";

			$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
			$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' ";
			$res1 = db_query($query_mtd1);
			while($row_mtd1 = array_shift($res1))
			{
				$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["freightcharge"];
				$ucb_item_totamt = $ucb_item_totamt + $row_mtd1["othercharge"];
			}	
			//echo "ucb_item_totamt : " . $ucb_item_totamt . "<br>";

			$total_cost = $total_cost + $ucb_item_totamt;		

			//For Parent child - Best Performing Locations Worst Performing Locations 
			if ($parent_comp_flg == 1) {

				$tmp_array = explode(",", $warehouse_id);

				$MGArray_parent_child_data = array();
				foreach ($tmp_array as $tmp_array_item) 
				{			
					$total_cost_parent = 0; $landfill_diversion = 0;
					//outlet <> '' and 
					$result_vendor = db_query("SELECT warehouse_id, water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where  water_vendors.id <> 844 and warehouse_id in (" . $tmp_array_item . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name", array("s", "s") , array($st_date, $end_date));
					echo "Step 14 - " . "SELECT warehouse_id, water_vendors.Name, water_vendors.id FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where warehouse_id in (" . $tmp_array_item . ") and invoice_date between ? and ? group by water_vendors.Name, water_vendors.id order by water_vendors.Name" . "<br>";				
					while($row_vendor = array_shift($result_vendor))
					{
						$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
						$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id = " . $row_vendor["warehouse_id"] . " and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
						//echo $query_mtd . "<br>";
						echo "Step 16 - " . $query_mtd . "<br>";
						$totalval_tot_parent = 0;
						$res = db_query($query_mtd);
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
						$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
						$query_mtd.= " WHERE  water_vendors.id <> 844 and company_id = " . $row_vendor["warehouse_id"] . " and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";
						echo "Step 17 - " . $query_mtd . "<br>";
						$res = db_query($query_mtd);
						while($row_mtd = array_shift($res))
						{							
							$totalval_tot_parent = $totalval_tot_parent - $row_mtd["addfees"];
						}

						$total_cost_parent = $total_cost_parent + $totalval_tot_parent;
					} 

					$ucb_item_totamt_parent = 0; 
					$query_mtd1 = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
					$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
					$query_mtd1 .= " WHERE loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59'  group by boxgood";
					echo "Step 18 - " . $query_mtd1 . "<br>";
					$res1 = db_query($query_mtd1);
					while($row_mtd1 = array_shift($res1))
					{
						$ucb_item_totamt_parent = $ucb_item_totamt_parent + $row_mtd1["totamt"];
					}

					$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
					$query_mtd1 .= " WHERE loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' ";
					echo "Step 19 - " . $query_mtd1 . "<br>";
					$res1 = db_query($query_mtd1);
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
					$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE  water_vendors.id <> 844 and warehouse_id = (" . $tmp_array_item . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
					$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
					echo "Step 20 - " . $query_mtd . "<br>";
					$result = db_query($query_mtd);
					while($row = array_shift($result))
					{
						$sumtot_parent = $sumtot_parent + $row["sumweight"];
					}

					$query_mtd1 = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
					$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
					$query_mtd1 .= " WHERE loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
					echo "Step 21 - " . $query_mtd1 . "<br>";
					$res1 = db_query($query_mtd1);
					while($row_mtd1 = array_shift($res1))
					{
						$sumtot_parent = $sumtot_parent + $row_mtd1["sumweight"];
					}

					//$outlet_array = array("Reuse","Recycling","Waste To Energy","Incineration (No Energy Recovery)","Landfill");
					$outlet_array = array("Reuse","Recycling","Waste To Energy");

					$totalval_tot = 0; $weightval_tot = 0; $display_flg1 = "n"; $display_flg2 = "n"; $display_flg3 = "n";
					$arrlength = count($outlet_array); 
					for($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {

						$weightval = 0; $valueeachval = 0; $totalval = 0;
						$valueeachval_tot = 0; $weight_tot = 0; $amt_tot = 0;
						$tot_show = "y";
						$trans_rec_id_list = "";

						if ($outlet_array[$arrycnt] == "Recycling") {

							if ($display_flg1 == "n"){
								$query_mtd1 = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
								$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
								$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'N' and loop_boxes.type <> 'Waste-to-Energy' and loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
								//echo $query_mtd1 . "<br>";
								echo "Step 14 a - " . $query_mtd1 . "<br>";				
								$res1 = db_query($query_mtd1);
								while($row_mtd1 = array_shift($res1))
								{
									$weightval = $row_mtd1['sumweight'];
									$display_flg1 = "y";
									$weight_tot = $weight_tot + $weightval;
								}
							}	

						}else if ($outlet_array[$arrycnt] == "Reuse") {

							if ($display_flg2 == "n"){
								$query_mtd1 = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
								$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
								$query_mtd1 .= " WHERE loop_boxes.isbox LIKE 'Y' and loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
								//echo $query_mtd1 . "<br>";
								echo "Step 14 b - " . $query_mtd1 . "<br>";				
								$res1 = db_query($query_mtd1);
								while($row_mtd1 = array_shift($res1))
								{
									$weightval = $row_mtd1['sumweight'];

									$display_flg2 = "y";

									$weight_tot = $weight_tot + $weightval;
								}
							}	

						}else if ($outlet_array[$arrycnt] == "Waste To Energy") {

							if ($display_flg3 == "n"){
								$query_mtd1 = "SELECT sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
								$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
								$query_mtd1 .= " WHERE loop_boxes.type = 'Waste-to-Energy' and loop_transaction.warehouse_id = (" . $tmp_array_item . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
								//echo $query_mtd1 . "<br>";
								echo "Step 14 c - " . $query_mtd1 . "<br>";				
								$res1 = db_query($query_mtd1);
								while($row_mtd1 = array_shift($res1))
								{
									$weightval = $row_mtd1['sumweight'];

									$display_flg3 = "y";

									$weight_tot = $weight_tot + $weightval;
								}
							}	

						}


						$query_mtd  = "SELECT sum(weight_in_pound) as weightval from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
						$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE  water_vendors.id <> 844 and warehouse_id = (" . $tmp_array_item . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') and water_inventory.Outlet = '" . $outlet_array[$arrycnt] . "'";
						$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
						echo "Step 15 - " . $query_mtd . "<br>";				
						//echo $query_mtd . "<br>";
						$res = db_query($query_mtd);
						while($row_mtd = array_shift($res))
						{								
							$weightval = $row_mtd["weightval"];

							$weight_tot = $weight_tot + $weightval;
						} 

						$per_val = 0; $per_val_2 = 0;
						if ($sumtot_parent > 0){
							$per_val_2 = number_format(($weight_tot/$sumtot_parent)*100,2);
							$landfill_diversion = $landfill_diversion + $per_val_2;
						}
					}				

					//For Diversion report

					$MGArray_parent_child_data[] = array('warehouse_id' => $tmp_array_item, 'netfinacne_amt' => $total_cost_parent, 'landfill_diversion' => $landfill_diversion); 
				}	
			}
			//For Parent child - Best Performing Locations Worst Performing Locations 

			//For High and Low value
			$MGArray = array();
			$weightval_tot_grand = 0; 
			$totalval_tot = 0; $net_finance = 0; 
			$result_vendor = db_query("SELECT water_vendors.Name, water_vendors.id, water_vendors.logo_image FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where  water_vendors.id <> 844 and outlet <> '' and warehouse_id in (" . $warehouse_id . ") and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by water_vendors.Name, water_vendors.id order by water_vendors.Name");
			echo "Step 16 - " . "SELECT water_vendors.Name, water_vendors.id, water_vendors.logo_image FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id inner join water_vendors on water_transaction.vendor_id = water_vendors.id where outlet <> '' and warehouse_id in (" . $warehouse_id . ") and invoice_date between '" . $st_date . "' and '" . $end_date . "' group by water_vendors.Name, water_vendors.id order by water_vendors.Name" . "<br>";	
			while($row_vendor = array_shift($result_vendor))
			{
				$weightval_tot = 0;

				$query_mtd  = "SELECT total_value as totalval, vendor_id, water_boxes_report_data.*, water_inventory.* from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
				$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id WHERE warehouse_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
				$totalval_tot = 0;
				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{								
					if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull"){
						$totalval_tot = $totalval_tot - $row_mtd["totalval"];
					}else{
						$totalval_tot = $totalval_tot + $row_mtd["totalval"];
					}
				}

				$query_mtd = "SELECT water_vendors.Name as Vendorname, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";
				$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";
				$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";
				$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
				$query_mtd.= " WHERE  water_vendors.id <> 844 and company_id in (" . $warehouse_id . ") and water_transaction.vendor_id = " . $row_vendor["id"] . " and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";

				$res = db_query($query_mtd);
				while($row_mtd = array_shift($res))
				{							
					//echo "Add fee: " . $row_mtd["addfees"] . "<br>";
					$totalval_tot = $totalval_tot - $row_mtd["addfees"];
				}

				$MGArray[] = array('vendor_id' => $row_vendor["id"], 'logo_image' => $row_vendor["logo_image"], 'totalval_tot' => $totalval_tot); 
			} 

			$query_mtd = "SELECT sum(loop_boxes.bweight * boxgood) as sumweight,  sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
			$query_mtd .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
			$query_mtd .= " WHERE  loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59' ";
			//echo $query_mtd . "<br>";
			$res = db_query($query_mtd);
			$amt_tot = 0; 
			$tot_show = "y";
			$trans_rec_id_list = "";
			while($row_mtd = array_shift($res))
			{								
				$amt_tot = $amt_tot + $row_mtd["totamt"];
			}

			$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
			$query_mtd1 .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date ." 00:00:00' AND '" . $end_date . " 23:59:59'";
			//echo $query_mtd1 . "<br>";
			$ucb_otherchgs_tot = 0;
			$res1 = db_query($query_mtd1);
			while($row_mtd1 = array_shift($res1))
			{
				$ucb_otherchgs_tot = $ucb_otherchgs_tot + $row_mtd1["freightcharge"];
				$ucb_otherchgs_tot = $ucb_otherchgs_tot + $row_mtd1["othercharge"];
			}
			//echo "oth fee" . $ucb_otherchgs_tot . "<br>";
			$amt_tot = $amt_tot - $ucb_otherchgs_tot;
			$MGArray[] = array('vendor_id' => 0, 'logo_image' => 'ucblogo.jpg', 'totalval_tot' => $amt_tot); 

			$MGArrayNew = $MGArray;

			$MGArraysort_I = array();
			 echo "Step 17 - " . "Array sort " . "<br>";	
			foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['totalval_tot'];
			}
			array_multisort($MGArraysort_I,SORT_ASC,SORT_NUMERIC,$MGArray); 

			$costly_vendor = ""; $costly_vendor_val = "";
			foreach ($MGArray as $MGArraytmp2){
				if ($MGArraytmp2["totalval_tot"] != 0){
					$costly_vendor = $MGArraytmp2["vendor_id"];
					$costly_vendor_val = $MGArraytmp2["totalval_tot"];
					break;
				}	
			}	

			$MGArraysort_2 = array();

			foreach ($MGArrayNew as $MGArraytmp1) {
				$MGArraysort_2[] = $MGArraytmp1['totalval_tot'];
			}
			array_multisort($MGArraysort_2,SORT_DESC,SORT_NUMERIC,$MGArrayNew); 

			//echo $warehouse_id . "<br>";
			//print_r($MGArrayNew);
			//echo "<br>";

			$high_pay_vendor = ""; $high_pay_vendor_val = "";
			foreach ($MGArrayNew as $MGArraytmp3){
				if ($MGArraytmp3["totalval_tot"] != 0){
					$high_pay_vendor = $MGArraytmp3["vendor_id"];
					$high_pay_vendor_val = $MGArraytmp3["totalval_tot"];
					break;
				}	
			}	

			if ($costly_vendor_val > 0 && $costly_vendor == $high_pay_vendor && $costly_vendor_val == $high_pay_vendor_val){
				$costly_vendor = 0;
				$costly_vendor_val = 0;
			}

			// To calculate the best_finance_impact best_landfil_diversion lowest_finance_impact lowest_landfil_diversion
			$MGArraysort_2 = array();
			 //$MGArray_parent_child_data[] = array('warehouse_id' => $tmp_array_item, 'netfinacne_amt' => $total_cost_parent, 'landfill_diversion' => $landfill_diversion); 

			foreach ($MGArray_parent_child_data as $MGArraytmp1) {
				$MGArraysort_2[] = $MGArraytmp1['netfinacne_amt'];
			}
			array_multisort($MGArraysort_2,SORT_ASC,SORT_NUMERIC,$MGArray_parent_child_data); 

			echo "lowest_finance_impact_val <br>";
			var_dump($MGArray_parent_child_data);

			$lowest_finance_impact = ""; $lowest_finance_impact_val = "";
			foreach ($MGArray_parent_child_data as $MGArraytmp3){
				//if ($MGArraytmp3["netfinacne_amt"] != 0){
					$lowest_finance_impact = $MGArraytmp3["warehouse_id"];
					$lowest_finance_impact_val = $MGArraytmp3["netfinacne_amt"];
					break;
				//}	
			}	
			echo "lowest_finance_impact : " . $lowest_finance_impact . " " . $lowest_finance_impact_val . "<br>";

			$MGArraysort_2 = array();
			foreach ($MGArray_parent_child_data as $MGArraytmp1) {
				$MGArraysort_2[] = $MGArraytmp1['netfinacne_amt'];
			}
			array_multisort($MGArraysort_2,SORT_DESC,SORT_NUMERIC,$MGArray_parent_child_data); 

			echo "best_finance_impact_val <br>";
			var_dump($MGArray_parent_child_data);

			$best_finance_impact = ""; $best_finance_impact_val = "";
			foreach ($MGArray_parent_child_data as $MGArraytmp3){
				//if ($MGArraytmp3["netfinacne_amt"] != 0){
					$best_finance_impact = $MGArraytmp3["warehouse_id"];
					$best_finance_impact_val = $MGArraytmp3["netfinacne_amt"];
					break;
				//}	
			}	
			echo "best_finance_impact : " . $best_finance_impact . " " . $best_finance_impact_val . "<br>";

			$MGArraysort_2 = array();
			foreach ($MGArray_parent_child_data as $MGArraytmp1) {
				$MGArraysort_2[] = $MGArraytmp1['landfill_diversion'];
			}
			array_multisort($MGArraysort_2,SORT_ASC,SORT_NUMERIC,$MGArray_parent_child_data); 

			echo "lowest_landfil_diversion_val <br>";
			var_dump($MGArray_parent_child_data);

			$lowest_landfil_diversion = ""; $lowest_landfil_diversion_val = "";
			foreach ($MGArray_parent_child_data as $MGArraytmp3){
				if ($MGArraytmp3["landfill_diversion"] != 0){
					$lowest_landfil_diversion = $MGArraytmp3["warehouse_id"];
					$lowest_landfil_diversion_val = $MGArraytmp3["landfill_diversion"];
					break;
				}	
			}	

			$MGArraysort_2 = array();
			foreach ($MGArray_parent_child_data as $MGArraytmp1) {
				$MGArraysort_2[] = $MGArraytmp1['landfill_diversion'];
			}
			array_multisort($MGArraysort_2,SORT_DESC,SORT_NUMERIC,$MGArray_parent_child_data); 

			echo "best_landfil_diversion_val <br>";
			var_dump($MGArray_parent_child_data);

			$best_landfil_diversion = ""; $best_landfil_diversion_val = "";
			foreach ($MGArray_parent_child_data as $MGArraytmp3){
				if ($MGArraytmp3["landfill_diversion"] != 0){
					$best_landfil_diversion = $MGArraytmp3["warehouse_id"];
					$best_landfil_diversion_val = $MGArraytmp3["landfill_diversion"];
					break;
				}	
			}		
			echo "Step 18 - " . "Add in temp table " . "<br>";	

			$pastdue = 0;
			//for Past due value commented here as created new page for Location page 
			/*$n=12; 
			$selected_month = Date("m");

			$query_mtd1 = "(SELECT water_vendors.Name, water_vendors.main_material, water_vendors.id as vendor from water_comp_vendor_list inner join water_vendors on water_vendors.id = water_comp_vendor_list.vendor_id
			WHERE comp_id in (" . $warehouse_id . ") and water_vendors.Name <> '' group by water_vendors.id ORDER BY water_vendors.Name) order by Name";
			$res1 = db_query($query_mtd1, db());
			while($row_vendor = array_shift($res1))
			{
				$vender_nm = $row_vendor['Name'];
				$main_material = $row_vendor['main_material'];
			
				for ($month_cnt=1, $n=$selected_month; $month_cnt<=$n; $month_cnt++) {
					$query = "SELECT company_id, have_doubt, doubt from water_transaction where company_id in (" . $warehouse_id . ") and vendor_id = " . $row_vendor['vendor'] . " and Month(invoice_date) = " . $month_cnt . " and Year(invoice_date) = " . $data_year;
					$res = db_query($query, db());
					$rec_found = "no"; $have_doubt = ""; $doubt_txt = "";
					while($row = array_shift($res))
					{
						$rec_found = "yes";
					}
					
					if ($rec_found == "no") 
					{
						$pastdue=$pastdue+1;
					}
				}
			}
		
			for ($month_cnt=1, $n=12; $month_cnt<=$n; $month_cnt++) {
				if ($month_cnt > $selected_month) {
					$str_rep .= "";
				}else{
					$query = "SELECT loop_boxes.vendor, loop_boxes.bdescription from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
					$query .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
					$query .= " WHERE loop_transaction.warehouse_id in (" . $warehouse_id . ") and year(STR_TO_DATE(sort_date, '%m/%d/%Y')) = '" . $data_year ."' and month(STR_TO_DATE(sort_date, '%m/%d/%Y')) = " . $month_cnt ." ";
					//echo $query . "<br>";
					$res = db_query($query, db());
					$rec_found = "no";
					while($row = array_shift($res))
					{
						$rec_found = "yes";
					}
					
					if ($rec_found == "yes") 
					{
						if ($data_foun_ucb_firstrec == "no"){
							$data_foun_ucb_firstrec = "yes";
						}
					}else{
						$pastdue=$pastdue+1;
					}
				}	
			}*/
		
			if ($parent_comp_flg == 1) {
				$res = db_query("Delete from water_cron_fordash_new where warehouse_id = " . $warehouse_id_parent . " and data_year = '" . $data_year . "'");
	//change $total_cost to $grand_tot
				$res = db_query("Insert into water_cron_fordash_new (data_year, warehouse_id, high_pay_vendor, costly_vendor, tree_saved, waste_financial, high_pay_vendor_val, costly_vendor_val, best_finance_impact, best_finance_impact_val, best_landfil_diversion, best_landfil_diversion_val , lowest_finance_impact_val, lowest_landfil_diversion_val, lowest_finance_impact, lowest_landfil_diversion, inv_past_due) select '" . $data_year . "', '" . $warehouse_id_parent . "', '" . $high_pay_vendor . "', '" . $costly_vendor . "', '" . $tree_saved . "', '" . $grand_tot . "', '" . $high_pay_vendor_val . "' , '" . $costly_vendor_val . "', '" . $best_finance_impact . "', '" . $best_finance_impact_val . "', '" . $best_landfil_diversion . "', '" . $best_landfil_diversion_val . "', '" . $lowest_finance_impact_val . "', '" . $lowest_landfil_diversion_val . "', '". $lowest_finance_impact . "', '" . $lowest_landfil_diversion . "', '" . $pastdue . "'");
			}else{
				$res = db_query("Delete from water_cron_fordash_new where warehouse_id = " . $warehouse_id . " and data_year = '" . $data_year . "'");

				$res = db_query("Insert into water_cron_fordash_new (data_year, warehouse_id, high_pay_vendor, costly_vendor, tree_saved, waste_financial, high_pay_vendor_val, costly_vendor_val, best_finance_impact, best_finance_impact_val, best_landfil_diversion, best_landfil_diversion_val , lowest_finance_impact_val, lowest_landfil_diversion_val, lowest_finance_impact, lowest_landfil_diversion, inv_past_due) select '" . $data_year . "', '" . $warehouse_id . "', '" . $high_pay_vendor . "', '" . $costly_vendor . "', '" . $tree_saved . "', '" . $grand_tot . "', '" . $high_pay_vendor_val . "' , '" . $costly_vendor_val . "', '" . $best_finance_impact . "', '" . $best_finance_impact_val . "', '" . $best_landfil_diversion . "', '" . $best_landfil_diversion_val . "', '" . $lowest_finance_impact_val . "', '" . $lowest_landfil_diversion_val . "', '". $lowest_finance_impact . "', '" . $lowest_landfil_diversion . "', '" . $pastdue . "'");
			}
			//
			$csql = "UPDATE companyInfo SET water_cron_flg = 0, water_cron_flg_year = 0 WHERE ID = ". $compid;
			$cresult = db_query($csql,db_b2b());
		}
		
	}	
	
?>  

