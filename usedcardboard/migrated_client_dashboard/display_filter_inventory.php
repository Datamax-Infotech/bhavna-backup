<?php 
session_start();
require ("inc/header_session_client.php");
require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");
?>
	<?php 
			$sort_g_view=$_REQUEST["sort_g_view"];
			$sort_g_tool=$_REQUEST["sort_g_tool"];
			$g_timing=$_REQUEST["g_timing"];
			//
			$filter_tag = "";
			if(isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"]!="")){
				
				$search_tag=explode(",",$_REQUEST["search_tag"]);
				// Retrieving each selected option 
				$total_tag=count($search_tag);
				
				 if($total_tag >= 1){
					$search_tag_val = "";
					foreach ($search_tag as $tag_val)  
					{
						$search_tag_val.= " tag like '%$tag_val%' or ";
					} 
					
					$search_tags=rtrim($search_tag_val, "or ");

					$filter_tag=" and (". $search_tags . ")";
				 }	
			}
			if(isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"]=="")){
				$search_tags="";
				$filter_tag="";
			}
		
			//
			//
		//$main_box_types=array("Gaylord","Shipping Boxes", "Supersacks", "Pallets", "Drums/Barrels/IBCs" );
		$gy = array(); $sb = array(); $pal = array(); $sup = array(); $dbi = array(); $recy = array();
			$_SESSION['sortarraygy'] = ""; $_SESSION['sortarraysb'] = ""; $_SESSION['sortarraysup'] = "";
			$_SESSION['sortarraydbi'] = ""; $_SESSION['sortarraypal'] = ""; $_SESSION['sortarrayrecy'] = "";
		//
		$x=0; $newflg = "no"; $preordercnt = 1; 
		$box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord'", "'Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'PalletsUCB','PalletsnonUCB'" , "'SupersackUCB','SupersacknonUCB','Supersacks'", "'DrumBarrelUCB','DrumBarrelnonUCB'");
			$box_type_cnt = 0;	
		foreach ($box_type_str_arr as $box_type_str_arr_tmp){
			
			//
			$box_type_cnt = $box_type_cnt + 1;	

				if ($box_type_cnt == 1){ $box_type = "Gaylord"; }
				if ($box_type_cnt == 2){ $box_type = "Shipping Boxes"; }
				if ($box_type_cnt == 3){ $box_type = "Pallets"; }
				if ($box_type_cnt == 4){ $box_type = "Supersacks"; }
				if ($box_type_cnt == 5){ $box_type = "Drums/Barrels/IBCs"; }
				$box_query = "";
				if ($sort_g_tool == 1) {
					$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (".$box_type_str_arr_tmp.")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ".$filter_tag." ORDER BY inventory.availability DESC";
				}
				if ($sort_g_tool == 2) {
					$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (".$box_type_str_arr_tmp.")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' ".$filter_tag." ORDER BY inventory.availability DESC";
				}
				//
				//echo $box_query;
				db_b2b();
				$act_inv_res=db_query($box_query);
				//echo tep_db_num_rows($act_inv_res)."<br>";
				if(tep_db_num_rows($act_inv_res)>0)
				{
				?>
					
				<?php
				while ($inv = array_shift($act_inv_res)) {
					$b2b_ulineDollar = round($inv["ulineDollar"]);
					$b2b_ulineCents = $inv["ulineCents"];
					$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
					$minfob=$b2b_fob;
					$b2b_fob = "$" . number_format($b2b_fob,2);

					$b2b_costDollar = round($inv["costDollar"]);
					$b2b_costCents = $inv["costCents"];
					$b2b_cost = $b2b_costDollar+$b2b_costCents;
					$b2bcost=$b2b_cost;
					$b2b_cost = "$" . number_format($b2b_cost,2);
					
					
					//
					$b2b_notes = $inv["N"];
					$b2b_notes_date = $inv["DT"];
					//
					$bpallet_qty= 0; $boxes_per_trailer= 0;	$box_type = "";	$loop_id = 0; $boxgoodvalue = 0;		
					$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue from loop_boxes where b2b_id=". $inv["I"];	
					//echo $qry_sku."<br>";
					$sku = "";
					$dt_view_sku = db_query($qry_sku );
					$inv_id_list = "";
					while ($sku_val = array_shift($dt_view_sku)) 
					{
						$loop_id = $sku_val['id'];
						$sku = $sku_val['sku'];
						$bpallet_qty= $sku_val['bpallet_qty']; 
						$boxes_per_trailer= $sku_val['boxes_per_trailer']; 				
						$box_type = $sku_val['type'];
						$box_wall = $sku_val['bwall'];
						$boxgoodvalue = $sku_val['boxgoodvalue'];
					}
					if ($inv["location_zip"] != "")		
					{
						if ($inv["availability"] != "-3.5" )
						{
							$inv_id_list .= $inv["I"] . ",";
						}
						//To get the Actual PO, After PO
						$rec_found_box = "n";
						$actual_val = 0; $after_po_val = 0; $last_month_qty = 0; $pallet_val = ""; $pallet_val_afterpo = "";
						$tmp_noofpallet = 0; $ware_house_boxdraw = ""; $preorder_txt = ""; $preorder_txt2 = ""; $box_warehouse_id = 0;
						$vendor_b2b_rescue_id = 0; $supplier_id = ""; $ownername = "";$estimated_next_load = "";$box_wall = "";
						$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id from loop_boxes where b2b_id=". $inv["I"];	
						$dt_view = db_query($qry_loc );
						$shipfrom_state = ""; $shipfrom_zip = ""; $shipfrom_city = "";
						while ($loc_res = array_shift($dt_view)) 
						{
							$box_warehouse_id = $loc_res["box_warehouse_id"];
							
							if($loc_res["box_warehouse_id"]=="238")	
							{
								$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
								$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
								db_b2b();
								$get_loc_res = db_query($get_loc_qry);
								$loc_row = array_shift($get_loc_res);
								$shipfrom_city = $loc_row["shipCity"]; 
								$shipfrom_state = $loc_row["shipState"]; 
								$shipfrom_zip = $loc_row["shipZip"]; 
							}
							else{

								$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];	
								$get_loc_qry = "Select * from loop_warehouse where id ='".$vendor_b2b_rescue_id."'";
								$get_loc_res = db_query($get_loc_qry );
								$loc_row = array_shift($get_loc_res);
								$shipfrom_city = $loc_row["company_city"]; 
								$shipfrom_state = $loc_row["company_state"]; 
								$shipfrom_zip = $loc_row["company_zip"]; 
							}
						}
						$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
						$ship_from2 = $shipfrom_state;
						//Find territory
						//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
						$territory="";
						$canada_east=array('NB', 'NF', 'NS','ON', 'PE', 'QC');
						$east=array('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV');
						$south=array('NC','SC','GA','AL','MS','TN','FL');
						$midwest=array('MI','OH','IN','KY');
						$north_central=array('ND','SD','NE','MN','IA','IL','WI');
						$south_central=array('LA','AR','MO','TX','OK','KS','CO','NM');
						$canada_west=array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
						$pacific_northwest=array('WA','OR','ID','MT','WY','AK');
						$west=array('CA','NV','UT','AZ','HI');
						$canada=array();
						$mexico = array('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA');
						$territory_sort=99;	
						if (in_array($shipfrom_state, $canada_east, TRUE)) 
						{ 
						 	$territory="Canada East";
							$territory_sort=1;
						} 
						elseif(in_array($shipfrom_state, $east, TRUE))
						{ 
						  	$territory="East";
							$territory_sort=2;
						} 
						elseif(in_array($shipfrom_state, $south, TRUE))
						{ 
						  	$territory="South";
							$territory_sort=3;
						} 
						elseif(in_array($shipfrom_state, $midwest, TRUE))
						{ 
						  	$territory="Midwest";
							$territory_sort=4;
						} 
						else if(in_array($shipfrom_state, $north_central, TRUE))
						{ 
						  $territory="North Central";
							$territory_sort=5;
						} 
						elseif(in_array($shipfrom_state, $south_central, TRUE))
						{ 
						  	$territory="South Central";
							$territory_sort=6;
						} 
						elseif(in_array($shipfrom_state, $canada_west, TRUE))
						{ 
						  	$territory="Canada West";
							$territory_sort=7;
						} 
						elseif(in_array($shipfrom_state, $pacific_northwest, TRUE))
						{ 
						  	$territory=" Pacific Northwest";
							$territory_sort=8;
						} 
						elseif(in_array($shipfrom_state, $west, TRUE))
						{ 
						  	$territory="West";
							$territory_sort=9;
						} 
						elseif(in_array($shipfrom_state, $canada, TRUE))
						{ 
						  	$territory="Canada";
							$territory_sort=10;
						} 
						elseif(in_array($shipfrom_state, $mexico, TRUE))
						{ 
						  	$territory="Mexico";
							$territory_sort=11;
						} 
						//
						$after_po_val_tmp = 0;
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
						db_b2b();
						$dt_view_res_box = db_query($dt_view_qry);
						while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
							$rec_found_box = "y";
							$actual_val = $dt_view_res_box_data["actual"];
							$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
							$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
							//
						}
						if ($rec_found_box == "n"){
							$actual_val = $inv["actual_inventory"];
							$after_po_val = $inv["after_actual_inventory"];
							$last_month_qty = $inv["lastmonthqty"];
						}

						if ($box_warehouse_id == 238){
							$after_po_val = $inv["after_actual_inventory"];
						}else{	
							if ($rec_found_box == "n"){
								$after_po_val = $inv["after_actual_inventory"];
							}else{
								$after_po_val = $after_po_val_tmp;						
							}	
						}	
				
						$to_show_rec = "y";

						if ($g_timing == 2) {
							$to_show_rec = "";
							if ($after_po_val >= $boxes_per_trailer) {
								$to_show_rec = "y";	
							}
						}	

						//if ($sort_g_tool == 2){
						//	$to_show_rec = "y";	
						//}
						
						if ($to_show_rec == "y")
						{
							//account owner
							if($inv["vendor_b2b_rescue"]>0){

								$vendor_b2b_rescue=$inv["vendor_b2b_rescue"];
								$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
								$query = db_query($q1);
								while($fetch = array_shift($query))
								{
									$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
									db_b2b();
									$comres=db_query($comqry);
									while($comrow=array_shift($comres))
									{
										$ownername=$comrow["initials"];
									}
								}
							}
							//
							$vender_nm = "";
							if ($inv["vendor_b2b_rescue"] != ""){
								$q1 = "SELECT * FROM loop_warehouse where id = ".$inv["vendor_b2b_rescue"];
								$v_query = db_query($q1);
								while($v_fetch = array_shift($v_query))
								{
									$supplier_id=$v_fetch["b2bid"];
									$vender_nm = get_nickname_val($v_fetch['company_name'],$v_fetch["b2bid"]);
									//$vender_nm = $v_fetch['company_name'];
									//
									db_b2b();
									$com_qry=db_query("select * from companyInfo where ID='".$v_fetch["b2bid"]."'");
									$com_row= array_shift($com_qry);
								}
							}
							//
							if ($inv["lead_time"] <= 1){
								$lead_time = "Next Day";	
							}else{
								$lead_time = $inv["lead_time"] . " Days";	
							}
						
							if ($after_po_val >= $boxes_per_trailer) {
								if ($inv["lead_time"] == 0){
									$estimated_next_load= "<font color=green>Now</font>";
								}							

								if ($inv["lead_time"] == 1){
									$estimated_next_load= "<font color=green>" . $inv["lead_time"] . " Day</font>";
								}							
								if ($inv["lead_time"] > 1){
									$estimated_next_load= "<font color=green>" . $inv["lead_time"] . " Days</font>";
								}							
							}
							else{
								if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)){
									$estimated_next_load= "<font color=red>Never (sell the " . $after_po_val . ")</font>";
								}else{	
								// logic changed by Zac
									$estimated_next_load=ceil((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." Weeks";
								}
							}
							if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0 ) {
								$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
							}
							//
							if ($inv["expected_loads_per_mo"] == 0 ) {
								$expected_loads_per_mo= "<font color=red>0</font>";
							}else{
								$expected_loads_per_mo= $inv["expected_loads_per_mo"];
							}
							
							//
							$b2b_status=$inv["b2b_status"];

							$st_query="select * from b2b_box_status where status_key='".$b2b_status."'";
							$st_res = db_query($st_query );
							$st_row = array_shift($st_res);
							$b2bstatus_name=$st_row["box_status"];
							$b2bstatuscolor = "";
							if($st_row["status_key"]=="1.0" || $st_row["status_key"]=="1.1" || $st_row["status_key"]=="1.2"){
								$b2bstatuscolor="green";
							}
							elseif($st_row["status_key"]=="2.0" || $st_row["status_key"]=="2.1" || $st_row["status_key"]=="2.2"){
								$b2bstatuscolor="orange";
							}
							//
							if ($inv["box_urgent"] == 1){
								$b2bstatuscolor="red";
								$b2bstatus_name="URGENT";
							}
							//
							$blength = $inv["lengthInch"];
							$bwidth = $inv["widthInch"];
							$bdepth = $inv["depthInch"];
							$blength_frac = 0;
							$bwidth_frac = 0;
							$bdepth_frac = 0;
							//
							$length = $blength;
							$width = $bwidth;
							$depth = $bdepth;

							if ($inv["lengthFraction"] != "") {
								$arr_length = explode("/", $inv["lengthFraction"]);
								if (count($arr_length) > 0 ) {
									$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
									$length = floatval($blength + $blength_frac);
								}
							}
							if ($inv["widthFraction"] != "") {
								$arr_width = explode("/", $inv["widthFraction"]);
								if (count($arr_width) > 0) {
									$bwidth_frac = intval($arr_width[0])/intval($arr_width[1]);
									$width = floatval($bwidth + $bwidth_frac);
								}
							}	
							if ($inv["depthFraction"] != "") {
								$arr_depth = explode("/", $inv["depthFraction"]);
								if (count($arr_depth) > 0) {
									$bdepth_frac = intval($arr_depth[0])/intval($arr_depth[1]);
									$depth = floatval($bdepth + $bdepth_frac);
								}
							}
							$b_urgent= "No"; $contracted= "No"; $prepay= "No"; $ship_ltl= "No";
							if ($inv["box_urgent"] == 1){
								$b_urgent= "Yes";
							}	
							if ($inv["contracted"] == 1){
								$contracted= "Yes";
							}	
							if ($inv["prepay"] == 1){
								$prepay= "Yes";
							}	
							if ($inv["ship_ltl"] == 1){
								$ship_ltl= "Yes";
							}	
							//$tipStr = "Loops ID#: " . $loop_id . "<br>";
							$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
							if ($inv["DT"] != "0000-00-00"){
								$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
							}else{	
								$tipStr .= "<b>Notes Date:</b> <br>";
							}
							$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
							$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
							$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
							$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

							$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
							$tipStr .= "<b>Lead Time:</b> " . $estimated_next_load . "<br>";
							$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
							$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
							$tipStr .= "<b>Supplier Relationship Owner:</b> " . $ownername . "<br>";
							$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
							$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
							$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
							$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
							$tipStr .= "<b>Territory:</b> " . $territory . "<br>";
							$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
							$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
							$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
							$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
							//
							
							//Get data in array
								if($box_type_cnt==1){
									$gy[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername'=>$ownername, 'b2b_notes'=>$inv["N"], 'b2b_notes_date'=>$inv["DT"], 'box_wall'=>$box_wall, 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'bpallet_qty'=>$bpallet_qty, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'binv'=>'nonucb','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==2){
									$sb[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername'=>$ownername, 'b2b_notes'=>$inv["N"], 'b2b_notes_date'=>$inv["DT"], 'box_wall'=>$box_wall, 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'bpallet_qty'=>$bpallet_qty, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'binv'=>'nonucb','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==3){
									$pal[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername'=>$ownername, 'b2b_notes'=>$inv["N"], 'b2b_notes_date'=>$inv["DT"], 'box_wall'=>$box_wall, 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'bpallet_qty'=>$bpallet_qty, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id ,'binv'=>'nonucb','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==4){
									$sup[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername'=>$ownername, 'b2b_notes'=>$inv["N"], 'b2b_notes_date'=>$inv["DT"], 'box_wall'=>$box_wall, 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'bpallet_qty'=>$bpallet_qty, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'binv'=>'nonucb','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==5){
									$dbi[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername'=>$ownername, 'b2b_notes'=>$inv["N"], 'b2b_notes_date'=>$inv["DT"], 'box_wall'=>$box_wall, 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'bpallet_qty'=>$bpallet_qty, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'binv'=>'nonucb','territory_sort'=>$territory_sort);
									
								}
							//	
							}//end $to_show_rec == "y"
						 }//end if ($inv["location_zip"] != "")	
					//
					}//End while $inv
				}//End check num rows>0
				
				//Ucbowned
				$dt_view_qry = "";
				if ($sort_g_tool == 1) {
					$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
				}
				if ($sort_g_tool == 2) {
					$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
				}
			//echo $dt_view_qry;
				db_b2b();
				$dt_view_res = db_query($dt_view_qry);
				$tmpwarenm = ""; $tmp_noofpallet = 0; $ware_house_boxdraw = "";
				while ($dt_view_row = array_shift($dt_view_res)) {

					$b2bid_tmp = 0; $boxes_per_trailer_tmp = 0; $bpallet_qty_tmp = 0; $vendor_id = 0; $vendor_b2b_rescue_id = 0; $boxgoodvalue = 0;
					$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, boxgoodvalue, expected_loads_per_mo from loop_boxes where id=". $dt_view_row["trans_id"];	
					$dt_view_loopbox = db_query($qry_loopbox );
					while ($rs_loopbox = array_shift($dt_view_loopbox)) 
					{
						$b2bid_tmp = $rs_loopbox['b2b_id'];
						$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
						$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
						$vendor_id = $rs_loopbox['vendor'];
						$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
						$boxgoodvalue = $rs_loopbox['boxgoodvalue'];
					}

					$inv_availability = ""; $distC = 0; $inv_notes = ""; $inv_notes_dt = "";
					$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp." ". $filter_tag;
					db_b2b();
					$dt_view_inv_res = db_query($inv_qry);
					while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
						$inv_notes = $dt_view_row_inv["notes"]; 
						$inv_notes_dt = $dt_view_row_inv["date"]; 
						$location_city = $dt_view_row_inv["location_city"]; 
						$location_state = $dt_view_row_inv["location_state"]; 
						$location_zip = $dt_view_row_inv["location_zip"]; 
						$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
						$vendor_id = $dt_view_row_inv["vendor"];
				
						if ($dt_view_row_inv["lead_time"] <= 1){
							$lead_time = "Next Day";	
						}else{
							$lead_time = $dt_view_row_inv["lead_time"] . " Days";	
						}
						//
						$b2bstatus = $dt_view_row_inv['b2bstatus'];
						$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];
							
						//account owner
						$vender_name = ""; $supplier_id = ""; $ownername = ""; 
						if($vendor_b2b_rescue>0){
							$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
							$query = db_query($q1);
							while($fetch = array_shift($query))
							{
								$supplier_id=$fetch["b2bid"];
								$vender_name = get_nickname_val($fetch['company_name'],$fetch["b2bid"]);
								//
								$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=".$fetch["b2bid"];
								db_b2b();
								$comres=db_query($comqry);
								while($comrow=array_shift($comres))
								{
									$ownername=$comrow["initials"];
								}
							}
						}		
							$tmp_zipval = "";
							$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
							if ($tmppos_1 != false)
							{ 	
								$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
								$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							}else {
								$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
							}
							if ($dt_view_row_inv["location_zip"] != "")		
							{
								db_b2b();
								$dt_view_res3 = db_query($zipStr);
								while ($ziploc = array_shift($dt_view_res3)) {
									$locLat = $ziploc["latitude"];

									$locLong = $ziploc["longitude"];
								}
							}
						
						$minfob=$dt_view_row["min_fob"];
						$b2bcost=$dt_view_row["b2b_cost"];
						$b2b_fob = "$" . number_format($dt_view_row["min_fob"],2); 
						$b2b_cost = "$" . number_format($dt_view_row["cost"],2); 

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 ))
						{
							$lastmonth_val = $dt_view_row["lastmonthqty"];

							$reccnt = 0;
							if ($sales_order_qty > 0) { $reccnt = $sales_order_qty; }
							
							$preorder_txt = "";
							$preorder_txt2 = "";

							if ($reccnt > 0){ 
								$preorder_txt = "<u>";
								$preorder_txt2 = "</u>";
							}
					
							if (($dt_view_row["actual"] >= $boxes_per_trailer_tmp) && ($boxes_per_trailer_tmp > 0)) {
								$bg = "yellow";
							}

							$pallet_val = 0;
							$pallet_val_afterpo = 0;
							$actual_po_tmp = $dt_view_row["actual"] - $sales_order_qty; 

							if ($bpallet_qty_tmp > 0) {
								$pallet_val = number_format($dt_view_row["actual"]/$bpallet_qty_tmp, 1, '.', ''); 
								$pallet_val_afterpo = number_format($actual_po_tmp/$bpallet_qty_tmp,1, '.', ''); 
							}

							$to_show_rec1 = "y";

							if ($to_show_rec1 == "y")					
							{
								$pallet_space_per = "";

								if ($pallet_val > 0) 
								{
									$tmppos_1 = strpos($pallet_val,'.');
									if ($tmppos_1 != false)
									{ 	
										if (intval(substr($pallet_val, strpos($pallet_val,'.')+1,1)) > 0 )
										{
											$pallet_val_temp = $pallet_val;
											$pallet_val = " (" . $pallet_val_temp . ")";
										}else { 
											$pallet_val_format = number_format((float)$pallet_val, 0);
											$pallet_val = " (" . $pallet_val_format . ")";
										}
									} else		{
										$pallet_val_format = number_format((float)$pallet_val, 0);
										$pallet_val = " (" . $pallet_val_format . ")";
									}
								}
								else { $pallet_val = ""; }

								if ($pallet_val_afterpo > 0) 
								{
									//reg_format = '/^\d+(?:,\d+)*$/';
									$tmppos_1 = strpos($pallet_val_afterpo,'.');
									if ($tmppos_1 != false)
									{ 	
										if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo,'.')+1,1)) > 0)
										{
											$pallet_val_afterpo_temp = $pallet_val_afterpo;
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
										}
										else
										{
											$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo,0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
										}
									}else {
											$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo,0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
									}
								}
								else { $pallet_val_afterpo = ""; }
								//
								if ($vendor_b2b_rescue_id == 238){
									$actual_po = $dt_view_row_inv["after_actual_inventory"];
								}else{	
									$actual_po = $actual_po_tmp;						
								}	
								//
								$to_show_rec = "y";
								if ($g_timing == 2) {
									$to_show_rec = "";	
									if ($actual_po >= $boxes_per_trailer_tmp) {					
										$to_show_rec = "y";	
									}
								}	
					
								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}
								//
								$estimated_next_load= "<font> </font>";
								if ($to_show_rec == "y"){	
					
									if ($actual_po >= $boxes_per_trailer_tmp) {
									//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

										if ($dt_view_row_inv["lead_time"] == 0){
											$estimated_next_load= "<font color=green>Now</font>";
										}							

										if ($dt_view_row_inv["lead_time"] == 1){
											$estimated_next_load= "<font color=green>" . $dt_view_row_inv["lead_time"] . " Day</font>";
										}							
										if ($dt_view_row_inv["lead_time"] > 1){
											$estimated_next_load= "<font color=green>" . $dt_view_row_inv["lead_time"] . " Days</font>";
										}							
						
									}
								else{
									if (($dt_view_row_inv["expected_loads_per_mo"] <= 0) && ($actual_po < $boxes_per_trailer_tmp)){
										$estimated_next_load= "<font color=red>Never (sell the " . $actual_po . ")</font>";
									}else{	
										$estimated_next_load=ceil((((($actual_po/$boxes_per_trailer_tmp)*-1)+1)/$dt_view_row_inv["expected_loads_per_mo"])*4)." Weeks";
									}
								}

								if ($actual_po == 0 && $dt_view_row_inv["expected_loads_per_mo"] == 0 ) {
									$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
								}

								if ($dt_view_row_inv["expected_loads_per_mo"] == 0 ) {
									$expected_loads_per_mo= "<font color=red>0</font>";
								}else{
									$expected_loads_per_mo= $dt_view_row_inv["expected_loads_per_mo"];
								}

								$blength = $dt_view_row_inv["lengthInch"];
								$bwidth = $dt_view_row_inv["widthInch"];
								$bdepth = $dt_view_row_inv["depthInch"];
								$blength_frac = 0;
								$bwidth_frac = 0;
								$bdepth_frac = 0;

								$length = $blength;
								$width = $bwidth;
								$depth = $bdepth;
					
								if ($dt_view_row_inv["lengthFraction"] != "") {
									$arr_length = explode("/", $dt_view_row_inv["lengthFraction"]);
									if (count($arr_length) > 0 ) {
										$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
										$length = floatval($blength + $blength_frac);
									}
								}
								if ($dt_view_row_inv["widthFraction"] != "") {
									$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
									if (count($arr_width) > 0) {
										$bwidth_frac = intval($arr_width[0])/intval($arr_width[1]);
										$width = floatval($bwidth + $bwidth_frac);
									}
								}	

								if ($dt_view_row_inv["depthFraction"] != "") {
									$arr_depth = explode("/", $dt_view_row_inv["depthFraction"]);
									if (count($arr_depth) > 0) {
										$bdepth_frac = intval($arr_depth[0])/intval($arr_depth[1]);
										$depth = floatval($bdepth + $bdepth_frac);
									}
								}									
					
					//
								$b2b_status=$dt_view_row["b2b_status"];

								$st_query="select * from b2b_box_status where status_key='".$b2b_status."'";
								//echo $st_query;
								$st_res = db_query($st_query);
								$st_row = array_shift($st_res);
								$b2bstatus_nametmp=$st_row["box_status"];
								$b2bstatuscolor = "";
								if($st_row["status_key"]=="1.0" || $st_row["status_key"]=="1.1" || $st_row["status_key"]=="1.2"){
									$b2bstatuscolor="green";
								}
								elseif($st_row["status_key"]=="2.0" || $st_row["status_key"]=="2.1" || $st_row["status_key"]=="2.2"){
									$b2bstatuscolor="orange";
								}

								if ($dt_view_row_inv["box_urgent"] == 1){
									$b2bstatuscolor="red";
									$b2bstatus_nametmp="URGENT";
								}
					
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=". $dt_view_row["trans_id"];	
								$dt_view = db_query($qry_loc );
								$shipfrom_state = ""; $shipfrom_city = ""; $shipfrom_zip = "";
								while ($loc_res = array_shift($dt_view)) 
								{
									if($loc_res["box_warehouse_id"]=="238")	
									{
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"]; 
										$shipfrom_state = $loc_row["shipState"]; 
										$shipfrom_zip = $loc_row["shipZip"]; 
									}
									else{

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];	
										$get_loc_qry = "Select * from loop_warehouse where id = '".$vendor_b2b_rescue_id."'";
										$get_loc_res = db_query($get_loc_qry );
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"]; 
										$shipfrom_state = $loc_row["company_state"]; 
										$shipfrom_zip = $loc_row["company_zip"]; 
									}
								}
								$ship_from_tmp  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
								$ship_from2_tmp = $shipfrom_state;
								//
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								$territory="";
								$canada_east=array('NB', 'NF', 'NS','ON', 'PE', 'QC');
								$east=array('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV');
								$south=array('NC','SC','GA','AL','MS','TN','FL');
								$midwest=array('MI','OH','IN','KY');
								$north_central=array('ND','SD','NE','MN','IA','IL','WI');
								$south_central=array('LA','AR','MO','TX','OK','KS','CO','NM');
								$canada_west=array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
								$pacific_northwest=array('WA','OR','ID','MT','WY','AK');
								$west=array('CA','NV','UT','AZ','HI');
								$canada = array();
								$mexico = array('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA');
								$territory_sort=99;	
								if (in_array($shipfrom_state, $canada_east, TRUE)) 
								{ 
									$territory="Canada East";
									$territory_sort=1;
								} 
								elseif(in_array($shipfrom_state, $east, TRUE))
								{ 
									$territory="East";
									$territory_sort=2;
								} 
								elseif(in_array($shipfrom_state, $south, TRUE))
								{ 
									$territory="South";
									$territory_sort=3;
								} 
								elseif(in_array($shipfrom_state, $midwest, TRUE))
								{ 
									$territory="Midwest";
									$territory_sort=4;
								} 
								else if(in_array($shipfrom_state, $north_central, TRUE))
								{ 
								  $territory="North Central";
									$territory_sort=5;
								} 
								elseif(in_array($shipfrom_state, $south_central, TRUE))
								{ 
									$territory="South Central";
									$territory_sort=6;
								} 
								elseif(in_array($shipfrom_state, $canada_west, TRUE))
								{ 
									$territory="Canada West";
									$territory_sort=7;
								} 
								elseif(in_array($shipfrom_state, $pacific_northwest, TRUE))
								{ 
									$territory=" Pacific Northwest";
									$territory_sort=8;
								} 
								elseif(in_array($shipfrom_state, $west, TRUE))
								{ 
									$territory="West";
									$territory_sort=9;
								} 
								elseif(in_array($shipfrom_state, $canada, TRUE))
								{ 
									$territory="Canada";
									$territory_sort=10;
								} 
								elseif(in_array($shipfrom_state, $mexico, TRUE))
								{ 
									$territory="Mexico";
									$territory_sort=11;
								} 
								//
								//
								$b_urgent= "No"; $contracted= "No"; $prepay= "No"; $ship_ltl= "No";
								if ($dt_view_row_inv["box_urgent"] == 1){
									$b_urgent= "Yes";
								}	
								if ($dt_view_row_inv["contracted"] == 1){
									$contracted= "Yes";
								}	
								if ($dt_view_row_inv["prepay"] == 1){
									$prepay= "Yes";
								}	
								if ($dt_view_row_inv["ship_ltl"] == 1){
									$ship_ltl= "Yes";
								}	
	
								//
								$btemp=str_replace(' ', '', $dt_view_row["LWH"]);
								$boxsize=explode("x",$btemp);		
								//Ucb owned data
								//echo $box_type_cnt."<br>";
								if($box_type_cnt==1){
									$gy[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername'=>$ownername, 'b2b_notes'=>$inv_notes, 'b2b_notes_date'=>$inv_notes_dt, 'box_wall'=>$dt_view_row_inv["bwall"], 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'bpallet_qty'=>$bpallet_qty_tmp,'binv'=>'ucbown','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==2){
									$sb[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername'=>$ownername, 'b2b_notes'=>$inv_notes, 'b2b_notes_date'=>$inv_notes_dt, 'box_wall'=>$dt_view_row_inv["bwall"], 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'bpallet_qty'=>$bpallet_qty_tmp,'binv'=>'ucbown','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==3){
									$pal[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername'=>$ownername, 'b2b_notes'=>$inv_notes, 'b2b_notes_date'=>$inv_notes_dt, 'box_wall'=>$dt_view_row_inv["bwall"], 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'bpallet_qty'=>$bpallet_qty_tmp,'binv'=>'ucbown','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==4){
									$sup[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername'=>$ownername, 'b2b_notes'=>$inv_notes, 'b2b_notes_date'=>$inv_notes_dt, 'box_wall'=>$dt_view_row_inv["bwall"], 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'bpallet_qty'=>$bpallet_qty_tmp,'binv'=>'ucbown','territory_sort'=>$territory_sort);
								}
								if($box_type_cnt==5){
									$dbi[]= array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername'=>$ownername, 'b2b_notes'=>$inv_notes, 'b2b_notes_date'=>$inv_notes_dt, 'box_wall'=>$dt_view_row_inv["bwall"], 'b_urgent'=>$b_urgent, 'contracted'=>$contracted, 'prepay'=>$prepay, 'ship_ltl'=>$ship_ltl, 'supplier_id'=>$supplier_id, 'b2b_cost'=>$b2b_cost,'minfob'=>$minfob,  'b2bcost'=>$b2bcost, 'vendor_b2b_rescue_id'=>$vendor_b2b_rescue_id, 'bpallet_qty'=>$bpallet_qty_tmp,'binv'=>'ucbown','territory_sort'=>$territory_sort);
								}
							//
							//$pallet_space_per = "";

							//----------------------------------------------------------------
						  }//end if ($to_show_rec == "y")
				  	}//End if ($to_show_rec1 == "y")	
	
				}//if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 )
				}
			} //while ($dt_view_row
				$_SESSION['sortarraygy'] = $gy;
				$_SESSION['sortarraysb'] = $sb;
				$_SESSION['sortarraysup'] = $sup;
				$_SESSION['sortarraydbi'] = $dbi;
				$_SESSION['sortarraypal'] = $pal;	
			//}									
		}//foreach array loop
	//
?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
			<?php
			$sort_g_view=$_REQUEST["sort_g_view"];
			$sort_g_tool=$_REQUEST["sort_g_tool"];
			$g_timing=$_REQUEST["g_timing"];
			
			$x=0;$boxtype_cnt=0;
			$boxtype="";
			//
			$box_name_arr= array('gy', 'sb', 'pal', 'sup', 'dbi');
			foreach($box_name_arr as $box_name){
				//
				if($box_name=="gy"){
					$boxtype="Gaylord";
					$boxtype_cnt=1;
				}
				if($box_name=="sb"){
					$boxtype="Shipping Boxes";
					$boxtype_cnt=2;
				}
				if($box_name=="pal"){
					$boxtype="Pallets";
					$boxtype_cnt=3;
				}
				if($box_name=="sup"){
					$boxtype="Supersacks";
					$boxtype_cnt=4;
				}
				if($box_name=="dbi"){
					$boxtype="Drums/Barrels/IBCs";
					$boxtype_cnt=5;
				}
				
				//
				$MGarray = $_SESSION['sortarray'.$box_name];
				$MGArraysort_I = array();
				$MGArraysort_II = array();
				$MGArraysort_III = array();
				foreach ($MGarray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['territory_sort'];
				$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
				$MGArraysort_III[] = $MGArraytmp['depth'];
				}
				//print_r($MGarray)."<br>";
					array_multisort($MGArraysort_I,SORT_ASC,$MGArraysort_II,SORT_ASC,$MGArraysort_III,SORT_ASC,$MGarray); 
				//
				//print_r($MGarray);
				$total_rec=count($MGarray);
				if($total_rec>0)
				{
					
			?>
					<tr class="headrow"><td class="display_maintitle" align="center">Active Inventory Items - <?php echo $boxtype; ?></td></tr>
					<tr><td>
						<div id="btype<?php echo $boxtype_cnt; ?>">
						<table width="100%" cellspacing="1" cellpadding="2">
							<?php if ((isset($sort_g_view)) && ($sort_g_view=="1")) { ?>
							<tr  class="headrow2">
								<td class='display_title' >Qty Avail&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title' width="80px">Lead Time&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>
								
								<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>	

								<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td align="center" class='display_title'>x</td>

								<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td align="center" class='display_title'>x</td>

								<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,2,<?php echo $box_type_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

								<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>
							</tr>	
					<?php
					}
					if ((isset($sort_g_view)) && ($sort_g_view=="2")) {
					?>
						<tr  class="headrow2">
							<td class='display_title'>Qty Avail<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title' width="80px">Lead Time&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>FOB Origin Price/Unit&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td align="center" class='display_title'>x</td>

							<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td align="center" class='display_title'>x</td>

							<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>

							<td class='display_title'>Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></td>
					</tr>

					<?php
					}
					?>
					<?php
						$count_arry=0; $count=0;
						$totQty = 0; $totEL = 0;
						$row_cnt = 0;
						foreach ($MGarray as $MGArraytmp2) {
							//
							$count=$count+1;
							$binv="";
							if($MGArraytmp2["binv"]=="nonucb"){
								$binv="";
							}
							if($MGArraytmp2["binv"]=="ucbown"){
								$binv="<b>UCB Owned Inventory </b><br>";
							}
							//
							$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
							if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00"){
								$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
							}else{	
								$tipStr .= "<b>Notes Date:</b> <br>";
							}
							$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
							$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
							$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
							$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

							$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
							$tipStr .= "<b>Lead Time:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
							$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
							$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
							$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
							$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
							$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
							$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
							$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
							$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
							$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
							$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
							$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
							$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
							$tipStr .= $binv;
							//
							if ($row_cnt == 0){
									$display_table_css = "display_table";
									$row_cnt = 1;
							}else{
								$row_cnt = 0;	
								$display_table_css = "display_table_alt";
							}	
							//
							$loopid=get_loop_box_id($MGArraytmp2["b2bid"]);
							$vendornme=$MGArraytmp2["vendor_nm"];
							
							//
							$sales_order_qty = 0;
							if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
								$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
								$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
								$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
								$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";

								$dt_res_so_item = db_query($dt_so_item );
								while ($so_item_row = array_shift($dt_res_so_item)) 
								{
									if ($so_item_row["sumqty"] > 0) {
										$sales_order_qty = $so_item_row["sumqty"];
									}	
								}
							}
							//
							if ((isset($sort_g_view)) && ($sort_g_view=="1")) {
							$tmpTDstr = "<tr  >";

								$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
									if ($MGArraytmp2["after_po_val"] < 0) {

										$tmpTDstr =  $tmpTDstr . "<div ";
										if ($sales_order_qty > 0) {
										$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
										}
										$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</div></td>";
									}else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
										$tmpTDstr =  $tmpTDstr . "<div"; 
										if ($sales_order_qty > 0) {
										$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
										}
										$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</div></td>";
									} else { 
										$tmpTDstr =  $tmpTDstr . "<div ";
										if ($sales_order_qty > 0) {
										$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
										}
										$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</div></td>";
									}
								//
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"],0) . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"],2) . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='".$MGArraytmp2["b2bstatuscolor"]."'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
								//
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
								$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'" , $tipStr) . "')\" onmouseout=\"UnTip()\""; 

								//echo " >" ;
								$tmpTDstr =  $tmpTDstr . " >";

								$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";
								
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$MGArraytmp2["supplier_id"]."'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
								if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00"){
								$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
								}
								$tmpTDstr =  $tmpTDstr ."</td>";

								$tmpTDstr =  $tmpTDstr . "</tr>";
								//
								$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_".$count."' align='middle' style='display:none;'>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td colspan='16'>
										<div id='inventory_preord_middle_div_". $count."'></div>		
								  </td></tr>";
							}
							$tmpTDstr = "";
							if ((isset($sort_g_view)) && ($sort_g_view=="2")) {
							$tmpTDstr = "<tr  >";

								$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
									if ($MGArraytmp2["after_po_val"] < 0) {
										$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</td>";
									}else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
										$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</td>";
									} else { 
										$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($MGArraytmp2["after_po_val"],0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"]. "</td>";
									}
								//
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"],0) . "</td>";
								
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"],2) . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"]. "</td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

								$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
								//
								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";

								$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</td>";

								/*$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["vendor_nm"] . "</td>";*/

								$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from2"] . "</td>";

								//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

								//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes_date"] . "</td>";

								$tmpTDstr =  $tmpTDstr . "</tr>";
							}
							echo $tmpTDstr;
							$totQty1 = array_sum(array_column($MGarray,'after_po_val'));
							$totQty = $totQty1;
							$totEL = array_sum(array_column($MGarray,'expected_loads_per_mo'));
						}
						?>
						<tr>
						  	<td><?php echo number_format($totQty, 2);?></td>
						  	<td>&nbsp;</td>
						  	<td><?php echo number_format($totEL, 2);?></td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						  	<td>&nbsp;</td>
						</tr>
							</table>
					</div>
				</td>
			</tr>
			<tr><td height="10px"></td></tr>
					<?php
				}
				
			}
	?>
</table>
