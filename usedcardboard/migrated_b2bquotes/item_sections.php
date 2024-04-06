<?php if($_REQUEST['quote_id']){ ?>
	<table>
		<tr>
			<td colspan="2" class="item1" ><h3 class="item1">Item</h3></td>
		</tr>
		<?php 
		//echo " arrAvailability - <pre>"; print_r($arrAvailability); echo "</pre>";
		$i = 1;
		$quote_sel = 0;	
		db_b2b();
		$resQuoteItems = db_query("SELECT quoteType FROM quote WHERE ID = ".$quote_id."");
		while ($rowsQuoteItems = array_shift($resQuoteItems)) {	
			if ($rowsQuoteItems["quoteType"] == "Quote Select"){
				$quote_sel = 1;	
			}
		}
			
		$estimated_next_load = "";
		if ($quote_sel	== 1) {
			foreach ($arrAvailability as $arrAvailabilityK => $arrAvailabilityV) {
				db_b2b();
				$resBoxId = db_query("SELECT * FROM boxes WHERE ID = '".$arrAvailabilityV."'");
			
				$rowBoxId = array_shift($resBoxId);
			
			$box_desc = ""; $inv_id = 0; $boxstr = "";
			if ($rowBoxId['inventoryID'] == 0) {
				$box_desc = $rowBoxId["description"];
			}
			else if($rowBoxId['inventoryID'] > 0) 
			{

				if($rowBoxId['inventoryID'] == 0) {
					$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId["box_id"];
				}else{
					$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId["inventoryID"];
				}
				db();
				$lb_res = db_query($lbq);
				$lbrow = array_shift($lb_res);
				if ($rowBoxId['inventoryID'] == 0) {
					$inv_id = $lbrow['b2b_id'];
				}else{
					$inv_id = $rowBoxId['inventoryID'];
				}
				$loop_box_id = $lbrow['id'];
				$loop_box_lead_time = $lbrow['lead_time'];
				
				if($rowBoxId['item'] == "Boxes") 
				{
					if ($rowBoxId["uniform_mixed_load"] == "Mixed") {
						$blength_min = $rowBoxId["blength_min"];
						$blength_max = $rowBoxId["blength_max"];
						$bwidth_min = $rowBoxId["bwidth_min"];
						$bwidth_max = $rowBoxId["bwidth_max"];
						$bheight_min = $rowBoxId["bheight_min"];
						$bheight_max = $rowBoxId["bheight_max"];

						if($blength_min==$blength_max){
							$bl_mixed=$blength_max;
						}
						else{
							$bl_mixed=$blength_min."-".$blength_max;
						}
						if($bwidth_min==$bwidth_max){
							$bw_mixed=$bwidth_max;
						}
						else{
							$bw_mixed=$bwidth_min."-".$bwidth_max;
						}
						if($bheight_min==$bheight_max){
							$bh_mixed=$bheight_max;
						}
						else{
							$bh_mixed=$bheight_min."-".$bheight_max;
						}
						//
						$box_desc .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";				 
						$boxstr .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";				 
					}else{	
						if($rowBoxId['lengthNumerator'] != 0)
						{
							$box_desc = $rowBoxId['lengthInch'] . " " . $rowBoxId['lengthNumerator'] . "/" . $rowBoxId['lengthDenominator'] . " x ";
							$boxstr .= $rowBoxId['lengthInch'] . " " . $rowBoxId['lengthNumerator'] . "/" . $rowBoxId['lengthDenominator'] . " x "; 
						}	
						else
						{
							$box_desc = $rowBoxId['lengthInch'] . " x ";
							$boxstr .= $rowBoxId['lengthInch'] . " x ";
						}
							
						if($rowBoxId['widthNumerator'] != 0)
						{
							$box_desc .= $rowBoxId['widthInch'] . " " . $rowBoxId['widthNumerator'] . "/" . $rowBoxId['widthDenominator'] . " x ";
							$boxstr .= $rowBoxId['widthInch'] . " " . $rowBoxId['widthNumerator'] . "/" . $rowBoxId['widthDenominator'] . " x ";
						}	
						else
						{
							$box_desc .= $rowBoxId['widthInch'] . " x ";
							$boxstr .= $rowBoxId['widthInch'] . " x ";
						}
							
						if($rowBoxId['depthNumerator'] != 0)
						{
							$box_desc .= $rowBoxId['depthInch'] . " " . $rowBoxId['depthNumerator'] . "/" . $rowBoxId['depthDenominator'] . " ";
							$boxstr .= $rowBoxId['depthInch'] . " " . $rowBoxId['depthNumerator'] . "/" . $rowBoxId['depthDenominator'] . " ";
						}	
						else
						{
							$box_desc .= $rowBoxId['depthInch'] . " ";
							$boxstr .= $rowBoxId['depthInch'] . " ";
						}	

						if($rowBoxId['lengthDenominator'] == 0)
						{
							$a = 1;
						}	
						else
						{
							$a = $rowBoxId['lengthDenominator'];
						}	
						
						if($rowBoxId['widthDenominator'] == 0)
						{
							$b = 1;
						}	
						else
						{
							$b = $rowBoxId['widthDenominator'];
						}	

						if($rowBoxId['depthDenominator'] == 0)
						{
							$c = 1;
						}	
						else
						{
							$c = $rowBoxId['depthDenominator'];
						} 
							
						$box_desc .= "(" . number_format(($rowBoxId['lengthInch'] + $rowBoxId['lengthNumerator'] / $a) * ($rowBoxId['widthInch'] + $rowBoxId['widthNumerator'] / $b) * ($rowBoxId['depthInch'] + $rowBoxId['depthNumerator'] / $c) / 1728, 2) . "cf) ";
						$boxstr .= "(" . number_format(($rowBoxId['lengthInch'] + $rowBoxId['lengthNumerator'] / $a) * ($rowBoxId['widthInch'] + $rowBoxId['widthNumerator'] / $b) * ($rowBoxId['depthInch'] + $rowBoxId['depthNumerator'] / $c) / 1728, 2) . "cf) ";


						$box_desc .= $rowBoxId['newUsed'] . " ";
						$boxstr .= $rowBoxId['newUsed'] . " ";
					}	
				}
				$box_desc .= $rowBoxId['description']; 
				$boxstr .= $rowBoxId['description']; 
						
				$rec_found_box = "n"; $after_po_val_tmp = 0;
				$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = '" . $lbrow["id"] . "' order by warehouse, type_ofbox, Description";
				db_b2b();
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();
						
				//Buy Now, Load Can Ship In
				$expected_loads_per_mo=$lbrow["expected_loads_per_mo"];
				$lead_time=$lbrow["lead_time"];
				$next_load_available_date = $lbrow["next_load_available_date"];
				$warehouse_id = $lbrow["box_warehouse_id"];
				$txt_actual_qty = $lbrow["actual_qty"];	
				$txt_after_po = $lbrow["after_po"];	
				$txt_last_month_qty = $lbrow["last_month_qty"];	
				$availability = $lbrow["availability"];	
				$boxes_per_trailer = $lbrow["boxes_per_trailer"];
				
				if ($warehouse_id == 238){
					$txt_after_po = $lbrow["after_po"];
				}else{	
					if ($rec_found_box == "n"){
						$txt_after_po = $lbrow["after_po"];
					}else{
						$txt_after_po = $after_po_val_tmp;						
					}	
				}	
						
				//echo "In step " . $warehouse_id . " - " . $next_load_available_date . " - " . $next_load_available_date . " - " . "<br>";
				if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
				{
					//echo "In step 1 <br>";
					$now_date = time(); // or your date as well
					$next_load_date = strtotime($next_load_available_date);
					$datediff = $next_load_date - $now_date;
					$no_of_loaddays=round($datediff / (60 * 60 * 24));
					
					if($no_of_loaddays<$lead_time)
					{
						if($lbrow["lead_time"]>1)
						{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}
						else{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}
						
					}
					else{
						if ($no_of_loaddays == -0){
							//$estimated_next_load= "<font color=green>0 Day</font>";
						}else{
							//$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
						}						
					}
				}
				else{			
					if ($txt_after_po >= $boxes_per_trailer) {
						//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

						if ($lbrow["lead_time"] == 0){
							//$estimated_next_load= "<font color=green>Now</font>";
						}							

						if ($lbrow["lead_time"] == 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}							
						if ($lbrow["lead_time"] > 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}							
					}
					else{
						if (($lbrow["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
							//$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
						}else{
							//$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$lbrow["expected_loads_per_mo"])*4)." Weeks";
						}
					}
				}	

				if ($txt_after_po == 0 && $lbrow["expected_loads_per_mo"] == 0 ) {
					//$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
				}					
				
				//$estimated_next_load= $lbrow["buy_now_load_can_ship_in"];
				//$estimated_next_load = "First load ships in " . get_lead_time_v3(6, $loop_box_id, $loop_box_lead_time, '');
				$estimated_next_load = $lead_time_stored_val;
				
				//echo "In step " . $estimated_next_load . " " . $txt_after_po . " " . $boxes_per_trailer . "<br>";
			}
			else
			{
				$z = "select * from inventory Where ID = '".$rowBoxId['inventoryID']."'";
				db_b2b();
				$boxSql = db_query($z);
				$objInv = array_shift($boxSql);  

				if ($objInv["uniform_mixed_load"] == "Mixed") {
					$blength_min = $objInv["blength_min"];
					$blength_max = $objInv["blength_max"];
					$bwidth_min = $objInv["bwidth_min"];
					$bwidth_max = $objInv["bwidth_max"];
					$bheight_min = $objInv["bheight_min"];
					$bheight_max = $objInv["bheight_max"];

					if($blength_min==$blength_max){
						$bl_mixed=$blength_max;
					}
					else{
						$bl_mixed=$blength_min."-".$blength_max;
					}
					if($bwidth_min==$bwidth_max){
						$bw_mixed=$bwidth_max;
					}
					else{
						$bw_mixed=$bwidth_min."-".$bwidth_max;
					}
					if($bheight_min==$bheight_max){
						$bh_mixed=$bheight_max;
					}
					else{
						$bh_mixed=$bheight_min."-".$bheight_max;
					}
					//
					$box_desc .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";
				}else{				
					if($objInv['lengthFraction'] != "")
					{
						$box_desc = $objInv['lengthInch'] . " " . $objInv['lengthFraction'] . " x ";
					}	
					else
					{
						$box_desc .= $objInv['lengthInch'] . " x ";
					} 	
					if($objInv['widthFraction'] != "")
					{
						$box_desc .= $objInv['widthInch'] . " " . $objInv['widthFraction'] . " x ";
					}	
					else
					{
						$box_desc .= $objInv['widthInch'] . " x ";
					}	
					if($objInv['depthFraction'] != "")
					{
						$box_desc .= $objInv['depthInch'] . " " . $objInv['depthFraction'] . " ";
					}	
					else
					{
						$box_desc .= $objInv['depthInch'] . " ";
					}	

					$box_desc .= "(" . number_format($objInv['cubicFeet'],2) . " cf) "; 
				}		
				
				if($objInv['newUsed'] != "")
				{
					$box_desc .= $objInv['newUsed'] . " ";
				}	
				if($objInv['printing'] != "")
				{
					$box_desc .= $objInv['printing'] . " ";
				}	
				if($objInv['labels'] != "" && $objInv['labels'] != "No Labels")
				{
					$box_desc .= $objInv['labels'] . " ";
				}	
				if($objInv['writing'] != "" && $objInv['writing'] != "None")
				{
					$box_desc .= $objInv['writing'] . " ";
				}	
				if($objInv['burst'] != "")
				{
					$box_desc .= $objInv['burst'] . " ";
				}	
				$box_desc .= $objInv['description'];

				//Buy Now, Load Can Ship In
				$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId['inventoryID'];
				db();
				$lb_res = db_query($lbq);
				$lbrow = array_shift($lb_res);
				
				$rec_found_box = "n"; $after_po_val_tmp = 0;
				$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $lbrow["id"] . " order by warehouse, type_ofbox, Description";
				db_b2b();
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();
						
				//Buy Now, Load Can Ship In
				$expected_loads_per_mo=$lbrow["expected_loads_per_mo"];
				$lead_time=$lbrow["lead_time"];
				$next_load_available_date = $lbrow["next_load_available_date"];
				$warehouse_id = $lbrow["box_warehouse_id"];
				$txt_actual_qty = $lbrow["actual_qty"];	
				$txt_after_po = $lbrow["after_po"];	
				$txt_last_month_qty = $lbrow["last_month_qty"];	
				$availability = $lbrow["availability"];	
				$boxes_per_trailer = $lbrow["boxes_per_trailer"];
				
				if ($warehouse_id == 238){
					$txt_after_po = $lbrow["after_po"];
				}else{	
					if ($rec_found_box == "n"){
						$txt_after_po = $lbrow["after_po"];
					}else{
						$txt_after_po = $after_po_val_tmp;						
					}	
				}						
				if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
				{
					$now_date = time(); // or your date as well
					$next_load_date = strtotime($next_load_available_date);
					$datediff = $next_load_date - $now_date;
					$no_of_loaddays=round($datediff / (60 * 60 * 24));
					
					if($no_of_loaddays<$lead_time)
					{
						if($lbrow["lead_time"]>1)
						{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}
						else{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}
						
					}
					else{
						if ($no_of_loaddays == -0){
							//$estimated_next_load= "<font color=green>0 Day</font>";
						}else{
							//$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
						}						
					}
				}
				else{			
					if ($txt_after_po >= $boxes_per_trailer) {
						//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

						if ($lbrow["lead_time"] == 0){
							//$estimated_next_load= "<font color=green>Now</font>";
						}							

						if ($lbrow["lead_time"] == 1){
						//	$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}							
						if ($lbrow["lead_time"] > 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}							
					}
					else{
						if (($lbrow["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
							//$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
						}else{
						//	$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$lbrow["expected_loads_per_mo"])*4)." Weeks";
						}
					}
				}	

				if ($txt_after_po == 0 && $lbrow["expected_loads_per_mo"] == 0 ) {
					//$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
				}										
				
				//$estimated_next_load= $lbrow["buy_now_load_can_ship_in"];
				//$estimated_next_load = "First load ships in " . get_lead_time_v3(6, $lbrow["id"], $lbrow["lead_time"], '');	
				$estimated_next_load = $lead_time_stored_val;				
			}							
			
			?>
			<tr class="sidebartxt" >
				<td>
					<?php if ($inv_id == "") { ?>
						<?php echo $box_desc; ?>
					<?php } else { ?>
						ID: <?php echo $inv_id; ?>, <?php echo $box_desc; ?>
					<?php }  ?>
				</td>
			</tr>
			<!-- <tr class="" >
				<td>
					<br>Availability: <?php echo $estimated_next_load; ?>
				</td>
			</tr> -->
			
			<?php if(count($arrAvailability) != $i){?>
				<tr class="sidebartxt" ><td class="sidebar-sept"></td></tr>
				<tr class="div-space" ><td class="div-space"></td></tr>
			<?php } ?>
		<?php 
			$i++; 
			} 
		}else{
			db_b2b();
			$resQuoteItems = db_query("SELECT * FROM quote_to_item WHERE quote_id = ".$quote_id." ORDER BY sort_order");
			while ($rowsQuoteItems = array_shift($resQuoteItems)) {	
				db_b2b();
				$resBoxId = db_query("SELECT * FROM boxes WHERE ID = '".$rowsQuoteItems["item_id"]."'");
		
				$rowBoxId = array_shift($resBoxId);
			
			$box_desc = ""; $inv_id = 0;
			if ($rowBoxId['inventoryID'] == 0) {
				$box_desc = $rowBoxId["description"];
			}
			else if($rowBoxId['inventoryID'] > 0) 
			{

				if($rowBoxId['inventoryID'] == 0) {
					$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId["box_id"];
				}else{
					$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId["inventoryID"];
				}
				db();
				$lb_res = db_query($lbq);
				$lbrow = array_shift($lb_res);
				if ($rowBoxId['inventoryID'] == 0) {
					$inv_id = $lbrow['b2b_id'];
				}else{
					$inv_id = $rowBoxId['inventoryID'];
				}
				$boxstr = "";
				if($rowBoxId['item'] == "Boxes") 
				{
					if ($rowBoxId["uniform_mixed_load"] == "Mixed") {
						$blength_min = $rowBoxId["blength_min"];
						$blength_max = $rowBoxId["blength_max"];
						$bwidth_min = $rowBoxId["bwidth_min"];
						$bwidth_max = $rowBoxId["bwidth_max"];
						$bheight_min = $rowBoxId["bheight_min"];
						$bheight_max = $rowBoxId["bheight_max"];

						if($blength_min==$blength_max){
							$bl_mixed=$blength_max;
						}
						else{
							$bl_mixed=$blength_min."-".$blength_max;
						}
						if($bwidth_min==$bwidth_max){
							$bw_mixed=$bwidth_max;
						}
						else{
							$bw_mixed=$bwidth_min."-".$bwidth_max;
						}
						if($bheight_min==$bheight_max){
							$bh_mixed=$bheight_max;
						}
						else{
							$bh_mixed=$bheight_min."-".$bheight_max;
						}
						//
						$box_desc .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";
						$boxstr .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";
					}else{				 
						if($rowBoxId['lengthNumerator'] != 0)
						{
							$box_desc = $rowBoxId['lengthInch'] . " " . $rowBoxId['lengthNumerator'] . "/" . $rowBoxId['lengthDenominator'] . " x ";
							$boxstr .= $rowBoxId['lengthInch'] . " " . $rowBoxId['lengthNumerator'] . "/" . $rowBoxId['lengthDenominator'] . " x "; 
						}	
						else
						{
							$box_desc = $rowBoxId['lengthInch'] . " x ";
							$boxstr .= $rowBoxId['lengthInch'] . " x ";
						}
							
						if($rowBoxId['widthNumerator'] != 0)
						{
							$box_desc .= $rowBoxId['widthInch'] . " " . $rowBoxId['widthNumerator'] . "/" . $rowBoxId['widthDenominator'] . " x ";
							$boxstr .= $rowBoxId['widthInch'] . " " . $rowBoxId['widthNumerator'] . "/" . $rowBoxId['widthDenominator'] . " x ";
						}	
						else
						{
							$box_desc .= $rowBoxId['widthInch'] . " x ";
							$boxstr .= $rowBoxId['widthInch'] . " x ";
						}
							
						if($rowBoxId['depthNumerator'] != 0)
						{
							$box_desc .= $rowBoxId['depthInch'] . " " . $rowBoxId['depthNumerator'] . "/" . $rowBoxId['depthDenominator'] . " ";
							$boxstr .= $rowBoxId['depthInch'] . " " . $rowBoxId['depthNumerator'] . "/" . $rowBoxId['depthDenominator'] . " ";
						}	
						else
						{
							$box_desc .= $rowBoxId['depthInch'] . " ";
							$boxstr .= $rowBoxId['depthInch'] . " ";
						}	

						if($rowBoxId['lengthDenominator'] == 0)
						{
							$a = 1;
						}	
						else
						{
							$a = $rowBoxId['lengthDenominator'];
						}	
						
						if($rowBoxId['widthDenominator'] == 0)
						{
							$b = 1;
						}	
						else
						{
							$b = $rowBoxId['widthDenominator'];
						}	

						if($rowBoxId['depthDenominator'] == 0)
						{
							$c = 1;
						}	
						else
						{
							$c = $rowBoxId['depthDenominator'];
						} 
							
						$box_desc .= "(" . number_format(($rowBoxId['lengthInch'] + $rowBoxId['lengthNumerator'] / $a) * ($rowBoxId['widthInch'] + $rowBoxId['widthNumerator'] / $b) * ($rowBoxId['depthInch'] + $rowBoxId['depthNumerator'] / $c) / 1728, 2) . "cf) ";
						$boxstr .= "(" . number_format(($rowBoxId['lengthInch'] + $rowBoxId['lengthNumerator'] / $a) * ($rowBoxId['widthInch'] + $rowBoxId['widthNumerator'] / $b) * ($rowBoxId['depthInch'] + $rowBoxId['depthNumerator'] / $c) / 1728, 2) . "cf) ";


						$box_desc .= $rowBoxId['newUsed'] . " ";
						$boxstr .= $rowBoxId['newUsed'] . " ";
					}	
				}
				$box_desc .= $rowBoxId['description']; 
				$boxstr .= $rowBoxId['description']; 
						
				$rec_found_box = "n"; $after_po_val_tmp = 0;
				$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = '" . $lbrow["id"] . "' order by warehouse, type_ofbox, Description";
				db_b2b();
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();
						
				//Buy Now, Load Can Ship In
				$expected_loads_per_mo=$lbrow["expected_loads_per_mo"];
				$lead_time=$lbrow["lead_time"];
				$next_load_available_date = $lbrow["next_load_available_date"];
				$warehouse_id = $lbrow["box_warehouse_id"];
				$txt_actual_qty = $lbrow["actual_qty"];	
				$txt_after_po = $lbrow["after_po"];	
				$txt_last_month_qty = $lbrow["last_month_qty"];	
				$availability = $lbrow["availability"];	
				$boxes_per_trailer = $lbrow["boxes_per_trailer"];
				
				if ($warehouse_id == 238){
					$txt_after_po = $lbrow["after_po"];
				}else{	
					if ($rec_found_box == "n"){
						$txt_after_po = $lbrow["after_po"];
					}else{
						$txt_after_po = $after_po_val_tmp;						
					}	
				}	
						
				//echo "In step " . $warehouse_id . " - " . $next_load_available_date . " - " . $next_load_available_date . " - " . "<br>";
				if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
				{
					//echo "In step 1 <br>";
					$now_date = time(); // or your date as well
					$next_load_date = strtotime($next_load_available_date);
					$datediff = $next_load_date - $now_date;
					$no_of_loaddays=round($datediff / (60 * 60 * 24));
					
					if($no_of_loaddays<$lead_time)
					{
						if($lbrow["lead_time"]>1)
						{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}
						else{
						//	$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}
						
					}
					else{
						if ($no_of_loaddays == -0){
							//$estimated_next_load= "<font color=green>0 Day</font>";
						}else{
							//$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
						}						
					}
				}
				else{			
					if ($txt_after_po >= $boxes_per_trailer) {
						//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

						if ($lbrow["lead_time"] == 0){
							//$estimated_next_load= "<font color=green>Now</font>";
						}							

						if ($lbrow["lead_time"] == 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}							
						if ($lbrow["lead_time"] > 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}							
					}
					else{
						if (($lbrow["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
							//$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
						}else{
							//$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$lbrow["expected_loads_per_mo"])*4)." Weeks";
						}
					}
				}	

				if ($txt_after_po == 0 && $lbrow["expected_loads_per_mo"] == 0 ) {
					//$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
				}					
				
				//$estimated_next_load= $lbrow["buy_now_load_can_ship_in"];
				//echo "data ". $lbrow["id"] . " " . $lbrow["lead_time"] . "<br>";
				//$estimated_next_load = "First load ships in " . get_lead_time_v3(6, $lbrow["id"], $lbrow["lead_time"], '');				
				//echo "In step " . $estimated_next_load . " " . $txt_after_po . " " . $boxes_per_trailer . "<br>";
				$estimated_next_load = $lead_time_stored_val;
			}
			else
			{
				$z = "select * from inventory Where ID = '".$rowBoxId['inventoryID']."'";
				db_b2b();
				$boxSql = db_query($z);
				$objInv = array_shift($boxSql);  

				if ($objInv["uniform_mixed_load"] == "Mixed") {
					$blength_min = $objInv["blength_min"];
					$blength_max = $objInv["blength_max"];
					$bwidth_min = $objInv["bwidth_min"];
					$bwidth_max = $objInv["bwidth_max"];
					$bheight_min = $objInv["bheight_min"];
					$bheight_max = $objInv["bheight_max"];

					if($blength_min==$blength_max){
						$bl_mixed=$blength_max;
					}
					else{
						$bl_mixed=$blength_min."-".$blength_max;
					}
					if($bwidth_min==$bwidth_max){
						$bw_mixed=$bwidth_max;
					}
					else{
						$bw_mixed=$bwidth_min."-".$bwidth_max;
					}
					if($bheight_min==$bheight_max){
						$bh_mixed=$bheight_max;
					}
					else{
						$bh_mixed=$bheight_min."-".$bheight_max;
					}
					//
					$box_desc .= $bl_mixed."x".$bw_mixed."x".$bh_mixed . " ";

				}else{					
					if($objInv['lengthFraction'] != "")
					{
						$box_desc = $objInv['lengthInch'] . " " . $objInv['lengthFraction'] . " x ";
					}	
					else
					{
						$box_desc .= $objInv['lengthInch'] . " x ";
					} 	
					if($objInv['widthFraction'] != "")
					{
						$box_desc .= $objInv['widthInch'] . " " . $objInv['widthFraction'] . " x ";
					}	
					else
					{
						$box_desc .= $objInv['widthInch'] . " x ";
					}	
					if($objInv['depthFraction'] != "")
					{
						$box_desc .= $objInv['depthInch'] . " " . $objInv['depthFraction'] . " ";
					}	
					else
					{
						$box_desc .= $objInv['depthInch'] . " ";
					}	

					$box_desc .= "(" . number_format($objInv['cubicFeet'],2) . " cf) "; 
				}
				
				if($objInv['newUsed'] != "")
				{
					$box_desc .= $objInv['newUsed'] . " ";
				}	
				if($objInv['printing'] != "")
				{
					$box_desc .= $objInv['printing'] . " ";
				}	
				if($objInv['labels'] != "" && $objInv['labels'] != "No Labels")
				{
					$box_desc .= $objInv['labels'] . " ";
				}	
				if($objInv['writing'] != "" && $objInv['writing'] != "None")
				{
					$box_desc .= $objInv['writing'] . " ";
				}	
				if($objInv['burst'] != "")
				{
					$box_desc .= $objInv['burst'] . " ";
				}	
				$box_desc .= $objInv['description'];

				//Buy Now, Load Can Ship In
				$lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $rowBoxId['inventoryID'];
				db();
				$lb_res = db_query($lbq);
				$lbrow = array_shift($lb_res);
				
				$rec_found_box = "n"; $after_po_val_tmp = 0;
				$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $lbrow["id"] . " order by warehouse, type_ofbox, Description";
				db_b2b();
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();
						
				//Buy Now, Load Can Ship In
				$expected_loads_per_mo=$lbrow["expected_loads_per_mo"];
				$lead_time=$lbrow["lead_time"];
				$next_load_available_date = $lbrow["next_load_available_date"];
				$warehouse_id = $lbrow["box_warehouse_id"];
				$txt_actual_qty = $lbrow["actual_qty"];	
				$txt_after_po = $lbrow["after_po"];	
				$txt_last_month_qty = $lbrow["last_month_qty"];	
				$availability = $lbrow["availability"];	
				$boxes_per_trailer = $lbrow["boxes_per_trailer"];
				
				if ($warehouse_id == 238){
					$txt_after_po = $lbrow["after_po"];
				}else{	
					if ($rec_found_box == "n"){
						$txt_after_po = $lbrow["after_po"];
					}else{
						$txt_after_po = $after_po_val_tmp;						
					}	
				}						
				if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00"))
				{
					$now_date = time(); // or your date as well
					$next_load_date = strtotime($next_load_available_date);
					$datediff = $next_load_date - $now_date;
					$no_of_loaddays=round($datediff / (60 * 60 * 24));
					
					if($no_of_loaddays<$lead_time)
					{
						if($lbrow["lead_time"]>1)
						{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}
						else{
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}
						
					}
					else{
						if ($no_of_loaddays == -0){
							//$estimated_next_load= "<font color=green>0 Day</font>";
						}else{
							//$estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
						}						
					}
				}
				else{			
					if ($txt_after_po >= $boxes_per_trailer) {
						//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

						if ($lbrow["lead_time"] == 0){
							//$estimated_next_load= "<font color=green>Now</font>";
						}							

						if ($lbrow["lead_time"] == 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Day</font>";
						}							
						if ($lbrow["lead_time"] > 1){
							//$estimated_next_load= "<font color=green>" . $lbrow["lead_time"] . " Days</font>";
						}							
					}
					else{
						if (($lbrow["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)){
							//$estimated_next_load= "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
						}else{
							//$estimated_next_load=ceil((((($txt_after_po/$boxes_per_trailer)*-1)+1)/$lbrow["expected_loads_per_mo"])*4)." Weeks";
						}
					}
				}	

				if ($txt_after_po == 0 && $lbrow["expected_loads_per_mo"] == 0 ) {
					//$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
				}	
				
				//$estimated_next_load= $lbrow["buy_now_load_can_ship_in"];		
				//$estimated_next_load = "First load ships in " . get_lead_time_v3(6, $lbrow["id"], $lbrow["lead_time"], '');				
				$estimated_next_load = $lead_time_stored_val;
			}	

			?>
			<tr class="sidebartxt" >
				<td>
					<?php if ($inv_id == "") { ?>
						<?php echo $box_desc; ?>
					<?php } else { ?>
						ID: <?php echo $inv_id; ?>, <?php echo $box_desc; ?>
					<?php }  ?>
				</td>
			</tr>
			<?php //if($rowBoxId['item'] == "Boxes"){ ?>
				<tr class="sidebartxt" >
					<td>
						<br>Lead Time: <?php echo $estimated_next_load; ?>
					</td>
				</tr>
			<?php //} ?>
			
			<?php if(count($arrAvailability) != $i){?>
				<tr class="sidebartxt" ><td class="sidebar-sept"></td></tr>
				<tr class="div-space" ><td class="div-space"></td></tr>
			<?php } ?>
		<?php 
			$i++; 
			} 		
		
		}
		?>
		
	</table>

<?php }else{?>
	<table>
		<tr>
			<td colspan="2" class="item1" ><h3 class="item1">Item</h3></td>
		</tr>
		<tr class="sidebartxt" >
			<td>
				ID: <?php echo $ProductLoopId; ?>, <?php echo $boxid_text; ?>,  <?php echo $row_loopbox["system_description"];?> 
			</td>
		</tr>
		<tr class="sidebartxt" >
			<td>
				<br>Lead Time: <?php echo $orderData['hdAvailability']; ?>
			</td>
		</tr>
		
	</table>
<?php } ?>