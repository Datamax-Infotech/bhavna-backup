<style>
	.nowraptxt {
		white-space: nowrap;
	}
</style>

<!-- <link rel="stylesheet" type="text/css" href="css/managebox-styles.css" /> -->

<link rel="stylesheet" type="text/css" href="css/newstylechange.css" />

<?php
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}
} else {
	require("inc/header_session_client.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>
<div class="scrollit">
	<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
		<input type="hidden" name="fav_match_id" id="fav_match_id" value="<?php echo $_REQUEST["ID"] ?>">
		<input type="hidden" name="fav_match_boxid" id="fav_match_boxid" value="<?php echo $_REQUEST["gbox"] ?>">
		<input type="hidden" name="fav_match_display-allrec" id="fav_match_display-allrec" value="<?php echo $_REQUEST["display-allrec"] ?>">
		<input type="hidden" name="fav_match_viewflg" id="fav_match_viewflg" value="<?php echo $_REQUEST["display_view"] ?>">
		<input type="hidden" name="fav_match_flg" id="fav_match_flg" value="<?php echo $_REQUEST["sort_g_tool2"] ?>">
		<input type="hidden" name="fav_match_load_all" id="fav_match_load_all" value="<?php echo $_REQUEST["load_all"] ?>">
		<input type="hidden" name="fav_match_client_flg" id="fav_match_client_flg" value="<?php echo $_REQUEST["client_flg"] ?>">
		<input type="hidden" name="fav_boxtype" id="fav_boxtype" value="sup">

		<?php if ((isset($_REQUEST["display_view"])) && ($_REQUEST["display_view"] == "1")) { ?>
			<tr class="headrow2"><!-- <td class='display_title'>Add to Cart</td> -->
				<td class='display_title'>View Item</td>
				<!--<td class='display_title'>Actual</td>-->

				<td class='display_title'>Qty Avail</td>
				<td class='display_title'>Lead Time</td>

				<td class='display_title'>Expected # of Loads/Mo</td>

				<td class='display_title'>Per Truckload</td>

				<td class='display_title'>MIN FOB</td>

				<td class='display_title'>B2B ID</td>

				<td class='display_title'>Miles Away
					<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
						<span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles away <= 550 and> 250,
									<br>Red Color - miles away > 550</span>
					</div>
				</td>

				<td class='display_title'>B2B Status</td>

				<td align="center" class='display_title'>L</td>

				<td align="center" class='display_title'>x</td>

				<td align="center" class='display_title'>W</td>

				<td align="center" class='display_title'>x</td>

				<td align="center" class='display_title'>H</td>

				<td class='display_title'>Description
				</td>

				<td class='display_title'>Supplier</td>

				<td class='display_title'>Ship From</td>

				<td class='display_title' width="70px">Supplier Relationship Owner</td>
			</tr>
		<?php
		}
		if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
		?>
			<tr class="headrow2">
				<td class='display_title'>View Item</td>
				<td class='display_title'>Qty Avail</td>

				<td class='display_title'>Lead Time</td>

				<td class='display_title'>Expected # of Loads/Mo</td>

				<td class='display_title'>Per Truckload</td>

				<td class='display_title'>FOB Origin Price/Unit</td>

				<td class='display_title'>B2B ID</td>

				<td class='display_title'>Miles Away
					<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
						<span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles away <= 550 and> 250,
									<br>Red Color - miles away > 550</span>
					</div>

				</td>

				<td align="center" class='display_title'>L</td>

				<td align="center" class='display_title'>x</td>

				<td align="center" class='display_title'>W</td>

				<td align="center" class='display_title'>x</td>

				<td align="center" class='display_title'>H</td>

				<td class='display_title'>Walls</td>

				<td class='display_title'>Description
				</td>

				<td class='display_title'>Ship From</td>
				<?php
				if ($_REQUEST["client_flg"] == 1) {
				?>
					<td class='display_title'>Add an item as a favorite?</td>
				<?php
				}
				?>
			</tr>
		<?php
		}
		?>
		<?php
		$numrows = 0;
		$gbl_res = array();
		if ($numrows > 0) { ?>
			<?php
			while ($gblrow = array_shift($gbl_res)) {
				echo $gblrow["tipstr"];
			}
			?>

			<?php } else {

			$x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
			db_b2b();
			$dt_view_res = db_query($x);
			$shipLat = 0;
			$shipLong = 0;
			while ($row = array_shift($dt_view_res)) {
				//if((remove_non_numeric($row["shipZip"])) !="")
				if (($row["shipZip"]) != "") {
					//$zipShipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($row["shipZip"]);
					$tmp_zipval = "";
					$tmp_zipval = str_replace(" ", "", $row["shipZip"]);
					if ($row["shipcountry"] == "Canada") {
						$zipShipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
					} elseif (($row["shipcountry"]) == "Mexico") {
						$zipShipStr = "Select * from zipcodes_mexico limit 1";
					} else {
						$zipShipStr = "Select * from ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
					}
					db_b2b();
					$dt_view_res = db_query($zipShipStr);
					while ($zip = array_shift($dt_view_res)) {
						$shipLat = $zip["latitude"];
						$shipLong = $zip["longitude"];
					}
				}
			}
			$qaa = "";
			$inv_id_list = "";

			$MGArray = array();
			if ($_REQUEST["gbox"] == 0) {
				$qaa = "Select * from quote_request limit 1";
			} else {
				$qaa = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["ID"] . " and quote_supersacks.id = " . $_REQUEST["gbox"] . " order by quote_supersacks.id DESC";
			}
			//$qaa = "Select * from quote_request Where companyID = " . $_REQUEST["ID"]. " and quote_item=1";
			$qdt_view_res = db_query($qaa);
			$qnumrows = tep_db_num_rows($qdt_view_res);
			if ($qnumrows > 0) {
				while ($qgb = array_shift($qdt_view_res)) {
					// Added by Mooneem Jul-13-12 to Bring the green thread at top	
					$aa = "Select * from quote_supersacks Where quote_id = " . $qgb["quote_id"];
					$x = 0;
					$bg = "#f4f4f4";
					$dt_view_res = db_query($aa);
					$sb = array_shift($dt_view_res);
					//declare variables

					$sup_length = $sb["sup_item_length"];
					$sup_width = $sb["sup_item_width"];
					$sup_height = $sb["sup_item_height"];

					//
					$dk = "";
					if ($_REQUEST["load_all"] == 1) {
						//AND (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
						$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'SupersackUCB' or inventory.box_type = 'SupersacknonUCB' or inventory.box_type = 'Supersacks') and inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
					} else {
						if ($_REQUEST["display-allrec"] == 1) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'SupersackUCB' or inventory.box_type = 'SupersacknonUCB' or inventory.box_type = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
						} elseif ($_REQUEST["display-allrec"] == 2) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'SupersackUCB' or inventory.box_type = 'SupersacknonUCB' or inventory.box_type = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
						} elseif ($_REQUEST["sort_g_tool2"] == 1) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'SupersackUCB' or inventory.box_type = 'SupersacknonUCB' or inventory.box_type = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
						}
					}

					//echo $dk . "<br>";
					db_b2b();
					$yyyy = db_query($dk);
					$xxx =  tep_db_num_rows($yyyy);

					while ($inv = array_shift($yyyy)) {
						$count = 0;
						$tipcount_match_str = "";

						$show_rec_condition1 = "no";
						$show_rec_condition1 = "yes";
						if (($_REQUEST["sort_g_tool2"] == 2) || ($show_rec_condition1 == "yes")) {

							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2b_cost = "$" . number_format($b2b_cost, 2);

							//if ($inv["location"] != "" )
							//$tipStr = $tipStr . "Location: " . $inv["location"] . "<br>";

							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type from loop_boxes where b2b_id=" . $inv["I"];
							$sku = "";
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
							}
							$zipStr  = "";
							//$zipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($inv["location"]);
							if ($inv["location_country"] == "Canada") {
								$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
								$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							} elseif (($inv["location_country"]) == "Mexico") {
								$zipStr = "Select * from zipcodes_mexico limit 1";
							} else {
								$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($inv["location_zip"]) . "'";
							}

							//if (remove_non_numeric($inv["location"]) != "")		
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								db_b2b();
								$dt_view_res3 = db_query($zipStr);
								$locLat = 0;
								$locLong = 0;
								while ($ziploc = array_shift($dt_view_res3)) {
									$locLat = $ziploc["latitude"];
									$locLong = $ziploc["longitude"];
								}

								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));

								//To get the Actual PO, After PO
								$rec_found_box = "n";
								//$dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.b2b_id = " . $inv["I"] . " GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;

								//
								$next_load_available_date = "";
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, next_load_available_date from loop_boxes where b2b_id=" . $inv["I"];
								$dt_view = db_query($qry_loc);
								$vendor_b2b_rescue_id = '';
								$shipfrom_city = "";
								$shipfrom_zip = "";
								$shipfrom_state = "";
								while ($loc_res = array_shift($dt_view)) {
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$next_load_available_date = $loc_res["next_load_available_date"];

									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
									}
								}
								$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
								$ship_from2 = $shipfrom_state;
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

								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									//if ($rec_found_box == "n"){
									//	$after_po_val = $inv["after_actual_inventory"];
									//}else{
									$after_po_val = $after_po_val_tmp;
									//}	
								}

								$to_show_rec = "y";

								if ($_REQUEST["g_timing"] == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								if ($to_show_rec == "y") {

									$vendor_name = "";
									$ownername = "";
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);
											db_b2b();
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									} else {
										$vendor_b2b_rescue = $inv["V"];
										if ($vendor_b2b_rescue != "") {
											$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
											db_b2b();
											$query = db_query($q1);
											while ($fetch = array_shift($query)) {
												$vendor_name = $fetch["Name"];

												$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
												db_b2b();
												$comres = db_query($comqry);
												while ($comrow = array_shift($comres)) {
													$ownername = $comrow["initials"];
												}
											}
										}
									}
									$lead_time = "";
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									$estimated_next_load = "";
									$expected_loads_per_mo = 0;
									$b2bstatuscolor = "";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										//$next_load_available_date = $b2b_inv_row["next_load_available_date"];
										//echo "next_load_available_date - " . $inv["I"] . " " . $next_load_available_date . " " . $inv["lead_time"] . "<br>";
										if ($next_load_available_date != "0000-00-00") {
											//
											$now_date = time(); // or your date as well
											$next_load_date = strtotime($next_load_available_date);
											$datediff = $next_load_date - $now_date;
											$no_of_loaddays = round($datediff / (60 * 60 * 24));
											//echo $no_of_loaddays;
											if ($no_of_loaddays < $lead_time) {
												if ($inv["lead_time"] > 1) {
													$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
												} else {
													$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
												}
											} else {
												$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
											}
										} else {
											$estimated_next_load = $inv["lead_time"] . " Day";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}

											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
											} else {
												// logic changed by Zac
												//$estimated_next_load=round((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." weeks";;
												//echo "next_load_available_date - " . $inv["I"] . " " . $after_po_val . " " . $boxes_per_trailer . " " . $inv["expected_loads_per_mo"] .  "<br>";
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $inv["expected_loads_per_mo"];
										}
									}

									//
									$b2b_status = $inv["b2b_status"];

									$b2bstatuscolor = "";
									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}

									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//

									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}

									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}

									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}

									$miles_from = (int) (6371 * $distC * .621371192);
									$miles_away_color = "";
									if ($miles_from <= 250) {	//echo "chk gr <br/>";
										$miles_away_color = "green";
									}
									if (($miles_from <= 550) && ($miles_from > 250)) {
										$miles_away_color = "#FF9933";
									}
									if (($miles_from > 550)) {
										$miles_away_color = "red";
									}
									//

									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}

									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
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
									$tipStr .= "<b>Supplier:</b> " .  $vendor_name . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Miles From:</b> " . $miles_from . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//
									//To get the Actual PO, After Po
									$tmpTDstr = "";
									if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "1") {
										$tmpTDstr = "<tr  >";
										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2bquote.usedcardboardboxes.com/?id=" . get_loop_box_id($inv["I"]) . "&compnewid=" . $_REQUEST['compnewid'] . "' target='_blank' >View Item</a></td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >";
										if ($after_po_val < 0) {
											$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										} else if ($after_po_val >= $boxes_per_trailer) {
											$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										} else {
											$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										}

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $estimated_next_load . "</td>";

										//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $lead_time . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $expected_loads_per_mo . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . number_format($boxes_per_trailer, 0) . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $b2b_fob . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $inv["I"] . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='$miles_away_color'>" . $miles_from . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='" . $b2bstatuscolor . "'>" . $b2bstatus_name . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl width='40px'>" . $length . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl> x </td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center'  bgColorrepl width='40px'>" . $width . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center' bgColorrepl> x </td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center'  bgColorrepl width='40px'>" . $depth . "</td>";

										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $inv["wall"] . "</td>";
										}

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . encrypt_url(get_loop_box_id($inv["I"])) . "&proc=View&'";
										$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

										//echo " >" ;
										$tmpTDstr =  $tmpTDstr . " >";

										$tmpTDstr =  $tmpTDstr . $inv["description"] . "</a></td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $vendor_name . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ownername . "</td>";

										$tmpTDstr =  $tmpTDstr . "</tr>";
									}
									//Display customer view
									if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
										$tmpTDstr = "<tr  >";
										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2bquote.usedcardboardboxes.com/?id=" . get_loop_box_id($inv["I"]) . "&compnewid=" . $_REQUEST['compnewid'] . "' target='_blank' >View Item</a></td>";
										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>";
										if ($after_po_val < 0) {
											$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										} else if ($after_po_val >= $boxes_per_trailer) {
											$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										} else {
											$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
										}

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $estimated_next_load . "</td>";

										//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $lead_time . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $expected_loads_per_mo . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . number_format($boxes_per_trailer, 0) . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $b2b_fob . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $inv["I"] . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='$miles_away_color'>" . $miles_from . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl  width='40px'>" . $length . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl> x </td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center'  bgColorrepl width='40px'>" . $width . "</td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center' bgColorrepl> x </td>";

										$tmpTDstr =  $tmpTDstr . "<td  align='center'  bgColorrepl width='40px'>" . $depth . "</td>";

										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $inv["bwall"] . "</td>";
										}

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<div ";
										$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";
										$tmpTDstr =  $tmpTDstr . " >";
										$tmpTDstr =  $tmpTDstr . $inv["description"] . "</div></td>";

										$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from2 . "</td>";

										if ($_REQUEST["client_flg"] == 1) {
											//
											if ($after_po_val < 0) {
												$qty_avail =  "<font color=blue>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											} else if ($after_po_val >= $boxes_per_trailer) {
												$qty_avail =  "<font color=green>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											} else {
												$qty_avail =  "<font color=black>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											}
											$fav_b2bid = $inv["I"];
			?>

											<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
											<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
											<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $expected_loads_per_mo ?>">
											<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer ?>">
											<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
											<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $inv["I"] ?>">
											<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $length ?>">
											<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $width ?>">
											<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $depth ?>">
											<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $inv["bwall"] ?>">
											<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $inv["description"] ?>">
											<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2 ?>">
											<?php
											//
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a id='div_favourite" . $inv["I"] . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $inv["I"] . ")' >Add</a></td>";
										}

										$tmpTDstr =  $tmpTDstr . "</tr>";
									}

									//new log to record the top10 option for filter#2
									if ($_REQUEST["display-allrec"] == 2) {
										$dttoday = date("Y-m-d");
									}
									//
									$mileage = (int) (6371 * $distC * .621371192);

									$MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr, 'box_urgent' => $inv["box_urgent"]);
								}
							}
						} //if count > 4
					} //inv
					//-------------------------------------------------------------------------------------
					//From UCB owned inventory

					if ($inv_id_list != "") {
						$inv_id_list = substr($inv_id_list, 0, strlen($inv_id_list) - 1);
					}
					$dt_view_qry = "";
					if ($_REQUEST["load_all"] == 1) {
						//and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'SupersackUCB' or type_ofbox = 'SupersacknonUCB' or type_ofbox = 'Supersacks') order by warehouse, type_ofbox, Description";
					} else {
						if ($_REQUEST["display-allrec"] == 1) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'SupersackUCB' or type_ofbox = 'SupersacknonUCB' or type_ofbox = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
						}
						if ($_REQUEST["display-allrec"] == 2) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'SupersackUCB' or type_ofbox = 'SupersacknonUCB' or type_ofbox = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
						}
						if ($_REQUEST["sort_g_tool2"] == 1) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'SupersackUCB' or type_ofbox = 'SupersacknonUCB' or type_ofbox = 'Supersacks') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
						}
					}
					//and inv_id not in ($inv_id_list)
					//echo $dt_view_qry;
					db_b2b();
					$dt_view_res = db_query($dt_view_qry);

					$tmpwarenm = "";
					$tmp_noofpallet = 0;
					$ware_house_boxdraw = "";
					while ($dt_view_row = array_shift($dt_view_res)) {

						$b2bid_tmp = 0;
						$boxes_per_trailer_tmp = 0;
						$bpallet_qty_tmp = 0;
						$vendor_id = 0;
						$vendor_b2b_rescue_id = 0;
						$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, expected_loads_per_mo from loop_boxes where id=" . $dt_view_row["trans_id"];
						$dt_view_loopbox = db_query($qry_loopbox);
						while ($rs_loopbox = array_shift($dt_view_loopbox)) {
							$b2bid_tmp = $rs_loopbox['b2b_id'];
							$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
							$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
							$vendor_id = $rs_loopbox['vendor'];
							$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
						}

						$vendor_name = "";
						if ($vendor_id != "") {
							$qry = "select vendors.name AS VN from vendors where id=" . $vendor_id;
							db_b2b();
							$dt_view = db_query($qry);
							while ($sku_val = array_shift($dt_view)) {
								$vendor_name = $sku_val["VN"];
							}
						}

						$inv_availability = "";
						$distC = 0;
						$inv_notes = "";
						$inv_notes_dt = "";

						$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp;
						db_b2b();
						$dt_view_inv_res = db_query($inv_qry);

						$show_rec_condition1 = "no";
						$show_rec_condition2 = "no";
						$show_rec_condition3 = "no";
						$show_rec_condition4 = "no";
						$show_rec_condition5 = "no";
						$show_rec_condition6 = "no";
						$show_rec_condition7 = "no";
						$show_rec_condition8 = "no";
						$dt_view_row_inv = array_shift($dt_view_inv_res);
						//while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
						$inv_notes = $dt_view_row_inv["notes"];
						$inv_notes_dt = $dt_view_row_inv["date"];
						$location_city = $dt_view_row_inv["location_city"];
						$location_state = $dt_view_row_inv["location_state"];
						$location_zip = $dt_view_row_inv["location_zip"];
						$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
						$vendor_id = $dt_view_row_inv["vendor"];
						$lead_time = "";
						if ($dt_view_row_inv["lead_time"] <= 1) {
							$lead_time = "Next Day";
						} else {
							$lead_time = $dt_view_row_inv["lead_time"] . " Days";
						}
						//
						$b2bstatus = $dt_view_row_inv['b2bstatus'];
						$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];

						//account owner
						$vendor_name = "";
						$ownername = "";
						if ($vendor_b2b_rescue > 0) {
							$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
							$query = db_query($q1);
							while ($fetch = array_shift($query)) {
								$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

								$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
								db_b2b();
								$comres = db_query($comqry);
								while ($comrow = array_shift($comres)) {
									$ownername = $comrow["initials"];
								}
							}
						} else {
							$vendor_b2b_rescue = $vendor_id;
							if ($vendor_b2b_rescue != "") {
								$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
								db_b2b();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$vendor_name = $fetch["Name"];

									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							}
						}
						//				
						$show_rec_condition1 = "yes";
						$inv_availability = $dt_view_row_inv["availability"];

						$tmp_zipval = "";
						$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
						$zipStr = "";
						if ($tmppos_1 != false) {
							$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
							$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
						} else {
							$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
						}

						//if (remove_non_numeric($dt_view_row_inv["location"]) != "")		
						if ($dt_view_row_inv["location_zip"] != "") {
							db_b2b();
							$dt_view_res3 = db_query($zipStr);

							$locLat = 0;
							$locLong = 0;
							while ($ziploc = array_shift($dt_view_res3)) {
								$locLat = $ziploc["latitude"];

								$locLong = $ziploc["longitude"];
							}

							//	echo $locLong;
							$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
							$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

							$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
							//echo $dt_view_row_inv["I"] . " " . $distA . "p <br/>"; 
							$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
						}
						//}

						$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
						$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {

							//echo "2 Show_rec_condition - " . $b2bid_tmp . " = " . $show_rec_condition1 . " " . $show_rec_condition2 . " " . $show_rec_condition3 . " " . $show_rec_condition4 . " " . $show_rec_condition5 . " " . $show_rec_condition6 . " " . $dt_view_row["Description"] . " " . "<br>"; 			
							//($_REQUEST["sort_g_tool2"] == 1) ||
							if (($_REQUEST["sort_g_tool2"] == 2) || ($show_rec_condition1 == "yes")) {
								$lastmonth_val = $dt_view_row["lastmonthqty"];

								$reccnt = 0;
								if ($sales_order_qty > 0) {
									$reccnt = $sales_order_qty;
								}

								$preorder_txt = "";
								$preorder_txt2 = "";

								if ($reccnt > 0) {
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
									$pallet_val = number_format($dt_view_row["actual"] / $bpallet_qty_tmp, 1, '.', '');
									$pallet_val_afterpo = number_format($actual_po_tmp / $bpallet_qty_tmp, 1, '.', '');
								}

								$to_show_rec1 = "y";

								if ($to_show_rec1 == "y") {
									$pallet_space_per = "";

									if ($pallet_val > 0) {
										$tmppos_1 = strpos($pallet_val, '.');
										if ($tmppos_1 != false) {
											if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
												$pallet_val_temp = $pallet_val;
												$pallet_val = " (" . $pallet_val_temp . ")";
											} else {
												$pallet_val_format = number_format((float)$pallet_val, 0);
												$pallet_val = " (" . $pallet_val_format . ")";
											}
										} else {
											$pallet_val_format = number_format((float)$pallet_val, 0);
											$pallet_val = " (" . $pallet_val_format . ")";
										}
									} else {
										$pallet_val = "";
									}

									if ($pallet_val_afterpo > 0) {
										//reg_format = '/^\d+(?:,\d+)*$/';
										$tmppos_1 = strpos($pallet_val_afterpo, '.');
										if ($tmppos_1 != false) {
											if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
												$pallet_val_afterpo_temp = $pallet_val_afterpo;
												$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
											} else {
												$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
												$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
											}
										} else {
											$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
										}
									} else {
										$pallet_val_afterpo = "";
									}
									//

									if ($vendor_b2b_rescue_id == 238) {
										$actual_po = $dt_view_row_inv["after_actual_inventory"];
									} else {
										$actual_po = $actual_po_tmp;
									}

									$to_show_rec = "y";
									if ($_REQUEST["g_timing"] == 2) {
										$to_show_rec = "";
										if ($actual_po >= $boxes_per_trailer_tmp) {
											$to_show_rec = "y";
										}
									}

									$estimated_next_load = "";
									$expected_loads_per_mo = "";
									if ($to_show_rec == "y") {

										if ($actual_po >= $boxes_per_trailer_tmp) {
											//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

											if ($dt_view_row_inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}

											if ($dt_view_row_inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $dt_view_row_inv["lead_time"] . " Day</font>";
											}
											if ($dt_view_row_inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $dt_view_row_inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($dt_view_row_inv["expected_loads_per_mo"] <= 0) && ($actual_po < $boxes_per_trailer_tmp)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $actual_po . ")</font>";
											} else {
												$estimated_next_load = ceil((((($actual_po / $boxes_per_trailer_tmp) * -1) + 1) / $dt_view_row_inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($actual_po == 0 && $dt_view_row_inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($dt_view_row_inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $dt_view_row_inv["expected_loads_per_mo"];
										}

										if ($dt_view_row_inv["uniform_mixed_load"] == "Mixed") {
											$blength = $dt_view_row_inv["blength_min"] . " - " . $dt_view_row_inv["blength_max"];
											$bwidth = $dt_view_row_inv["bwidth_min"] . " - " . $dt_view_row_inv["bwidth_max"];
											$bdepth = $dt_view_row_inv["bheight_min"] . " - " . $dt_view_row_inv["bheight_max"];
										} else {
											$blength = $dt_view_row_inv["lengthInch"];
											$bwidth = $dt_view_row_inv["widthInch"];
											$bdepth = $dt_view_row_inv["depthInch"];
										}
										$blength_frac = 0;
										$bwidth_frac = 0;
										$bdepth_frac = 0;

										$length = $blength;
										$width = $bwidth;
										$depth = $bdepth;

										if ($dt_view_row_inv["lengthFraction"] != "") {
											$arr_length = explode("/", $dt_view_row_inv["lengthFraction"]);
											if (count($arr_length) > 0) {
												$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
												$length = floatval($blength + $blength_frac);
											}
										}
										if ($dt_view_row_inv["widthFraction"] != "") {
											$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
											if (count($arr_width) > 0) {
												$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
												$width = floatval($bwidth + $bwidth_frac);
											}
										}

										if ($dt_view_row_inv["depthFraction"] != "") {
											$arr_depth = explode("/", $dt_view_row_inv["depthFraction"]);
											if (count($arr_depth) > 0) {
												$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
												$depth = floatval($bdepth + $bdepth_frac);
											}
										}

										//
										$b2b_status = $dt_view_row["b2b_status"];

										$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
										//echo $st_query;
										$st_res = db_query($st_query);
										$st_row = array_shift($st_res);
										$b2bstatus_nametmp = $st_row["box_status"];

										$b2bstatuscolor = "";

										if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
											$b2bstatuscolor = "green";
										} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
											$b2bstatuscolor = "orange";
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($dt_view_row_inv["box_urgent"] == 1) {
											$b2bstatuscolor = "red";
											$b2bstatus_nametmp = "URGENT";
										}

										//
										$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
										$dt_view = db_query($qry_loc);
										$shipfrom_city = "";
										$shipfrom_zip = "";
										$shipfrom_state = "";
										while ($loc_res = array_shift($dt_view)) {
											if ($loc_res["box_warehouse_id"] == "238") {
												$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
												$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
												db_b2b();
												$get_loc_res = db_query($get_loc_qry);
												$loc_row = array_shift($get_loc_res);
												$shipfrom_city = $loc_row["shipCity"];
												$shipfrom_state = $loc_row["shipState"];
												$shipfrom_zip = $loc_row["shipZip"];
											} else {

												$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
												$get_loc_qry = "Select * from loop_warehouse where id = '" . $vendor_b2b_rescue_id . "'";
												$get_loc_res = db_query($get_loc_qry);
												$loc_row = array_shift($get_loc_res);
												$shipfrom_city = $loc_row["company_city"];
												$shipfrom_state = $loc_row["company_state"];
												$shipfrom_zip = $loc_row["company_zip"];
											}
										}
										$ship_from_tmp  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
										$ship_from2_tmp = $shipfrom_state;
										//
										$miles_from = (int) (6371 * $distC * .621371192);
										$miles_away_color = "";
										if ($miles_from <= 250) {	//echo "chk gr <br/>";
											$miles_away_color = "green";
										}
										if (($miles_from <= 550) && ($miles_from > 250)) {
											$miles_away_color = "#FF9933";
										}
										if (($miles_from > 550)) {
											$miles_away_color = "red";
										}

										$b_urgent = "No";
										$contracted = "No";
										$prepay = "No";
										$ship_ltl = "No";
										if ($dt_view_row_inv["box_urgent"] == 1) {
											$b_urgent = "Yes";
										}
										if ($dt_view_row_inv["contracted"] == 1) {
											$contracted = "Yes";
										}
										if ($dt_view_row_inv["prepay"] == 1) {
											$prepay = "Yes";
										}
										if ($dt_view_row_inv["ship_ltl"] == 1) {
											$ship_ltl = "Yes";
										}

										//$tipStr = "Loops ID#: " . $loop_id . "<br>";
										$tipStr = "<b>Notes:</b> " . $inv_notes . "<br>";
										if ($inv_notes_dt != "0000-00-00") {
											$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv_notes_dt)) . "<br>";
										} else {
											$tipStr .= "<b>Notes Date:</b> <br>";
										}
										$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
										$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
										$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
										$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

										$tipStr .= "<b>Qty Avail:</b> " . $actual_po . "<br>";
										$tipStr .= "<b>Lead Time:</b> " . $estimated_next_load . "<br>";
										$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $dt_view_row_inv["expected_loads_per_mo"] . "<br>";
										$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_nametmp . "<br>";
										$tipStr .= "<b>Supplier Relationship Owner:</b> " . $ownername . "<br>";
										$tipStr .= "<b>B2B ID#:</b> " . $b2bid_tmp . "<br>";
										$tipStr .= "<b>Description:</b> " . $dt_view_row["Description"] . "<br>";
										$tipStr .= "<b>Supplier:</b> " .  $vendor_name . "<br>";
										$tipStr .= "<b>Ship From:</b> " . $ship_from_tmp . "<br>";
										$tipStr .= "<b>Miles From:</b> " . $miles_from . "<br>";
										$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty_tmp . "<br>";
										$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer_tmp . "<br>";
										$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
										$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
										$tipStr .= "<b>UCB Owned Inventory </b><br>";
										//

										$pallet_space_per = "";
										$tmpTDstr = "";
										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "1") {
											$tmpTDstr = "<tr  >";
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2bquote.usedcardboardboxes.com/?id=" . get_loop_box_id($dt_view_row_inv["ID"]) . "&compnewid=" . $_REQUEST['compnewid'] . "' target='_blank' >View Item</a></td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >";
											if ($actual_po < 0) {
												$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
											} else if ($actual_po >= $boxes_per_trailer_tmp) {
												$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
											} else {
												$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
											}

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $estimated_next_load . "</td>";

											//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $lead_time . " Days</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $expected_loads_per_mo . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . number_format($boxes_per_trailer_tmp, 0) . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $b2b_fob . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $dt_view_row_inv["ID"] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='" . $b2bstatuscolor . "'>" . $b2bstatus_nametmp . "</td>";

											//
											$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
											$boxsize = explode("x", $btemp);
											//
											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $length . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $width . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $depth . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . encrypt_url($dt_view_row["trans_id"]) . "&proc=View&'";
											$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

											//echo " >" ;
											$tmpTDstr =  $tmpTDstr . " >";

											$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</a></td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $vendor_name . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from_tmp . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ownername . "</td>";

											$tmpTDstr =  $tmpTDstr . "</tr>";
										}
										//----------------------------------------------------------------
										//Display customer view
										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
											$tmpTDstr = "<tr  >";
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2bquote.usedcardboardboxes.com/?id=" . encrypt_url(get_loop_box_id($dt_view_row_inv["ID"])) . "&compnewid=" . encrypt_url($_REQUEST['compnewid']) . "' target='_blank' >View Item</a></td>";
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>";
											if ($actual_po < 0) {
												$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
											} else {
												$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
											}

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $estimated_next_load . "</td>";

											//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $lead_time . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $dt_view_row_inv["expected_loads_per_mo"] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . number_format($boxes_per_trailer_tmp, 0) . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $b2b_fob . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $dt_view_row_inv["ID"] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='$miles_away_color'>" . $miles_from . "</td>";

											//
											$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
											$boxsize = explode("x", $btemp);
											//
											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[0] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[1] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[2] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $dt_view_row_inv["bwall"] . "</td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<div ";
											$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

											$tmpTDstr =  $tmpTDstr . " >";

											$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</div></td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from2_tmp . "</td>";

											if ($_REQUEST["client_flg"] == 1) {
												if ($actual_po < 0) {
													$qty_avail =  "<font color=blue>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
												} else {
													$qty_avail =  "<font color=green>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
												}
												$fav_b2bid = $dt_view_row_inv["ID"];
											?>

												<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
												<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
												<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["expected_loads_per_mo"] ?>">
												<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer_tmp ?>">
												<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
												<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
												<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[0] ?>">
												<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[1] ?>">
												<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[2] ?>">
												<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall"] ?>">
												<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row["Description"] ?>">
												<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2_tmp ?>">
				<?php
												//
												$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $fav_b2bid . ")' >Add</a></td>";
											}

											$tmpTDstr =  $tmpTDstr . "</tr>";
										}

										$mileage = (int) (6371 * $distC * .621371192);

										$MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr, 'box_urgent' => $dt_view_row_inv["box_urgent"]);
									}
								}
							}
						}
					}
				} //gaylord

				// Added by Mooneem Jul-13-12 to Bring the green thread at top	
				// Sort the Array based on Mileage	
				$MGArraysort = array();

				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['arrorder'];
				}

				array_multisort($MGArraysort, SORT_NUMERIC, $MGArray);

				?>
				<?php
				$x = 0;
				$bg = "#e4e4e4";
				foreach ($MGArray as $MGArraytmp2) {
					if ($x == 0) {
						$x = 1;
						$bg = "#e4e4e4";
						$bgstyle = "display_table";
					} else {
						$x = 0;
						$bg = "#f4f4f4";
						$bgstyle = "display_table_alt";
					}

					echo preg_replace("/bgColorrepl/", "class=$bgstyle", $MGArraytmp2['arrdet']);
				}
				?>
		<?php
			} //end if num>0
		}
		?>
	</table>
</div>