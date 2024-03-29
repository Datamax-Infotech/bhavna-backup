<style>
	.nowraptxt {
		white-space: nowrap;
	}
</style>

<!-- <link rel="stylesheet" type="text/css" href="css/managebox-styles.css" /> -->

<link rel="stylesheet" type="text/css" href="css/newstylechange.css" />

<?php
set_time_limit(0);
ini_set('memory_limit', '-1');
/*echo "<pre>";print_r($_REQUEST);echo "</pre>";
exit();*/
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
		<input type="hidden" name="fav_match_flg" id="fav_match_flg" value="<?php echo $_REQUEST["display-allrec"] ?>">
		<input type="hidden" name="fav_match_load_all" id="fav_match_load_all" value="<?php echo $_REQUEST["load_all"] ?>">
		<input type="hidden" name="fav_match_client_flg" id="fav_match_client_flg" value="<?php echo $_REQUEST["client_flg"] ?>">
		<input type="hidden" name="fav_match_inboxprofile" id="fav_match_inboxprofile" value="<?php echo $_REQUEST["inboxprofile"] ?>">
		<input type="hidden" name="fav_boxtype" id="fav_boxtype" value="other">

		<?php
		if (isset($_REQUEST["sort_g_location_other"]) && ($_REQUEST["sort_g_location_other"] == "2" || $_REQUEST["sort_g_location_other"] == "3" || $_REQUEST["sort_g_location_other"] == "4")) {
		?>
			<tr class="headrow2">
				<?php //if($_REQUEST['sort_g_location_other'] == 2 ){ 
				?>
				<?php if ($_REQUEST['sort_g_location_other'] == 2 && $_REQUEST['display_view'] == 1) { ?>
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
					<td class='display_title'>Description</td>
					<td class='display_title'>Ship From</td>
					<td class='display_title'>Favorite?</td>
				<?php } ?>
				<?php //if($_REQUEST['sort_g_location_other'] == 3 ){ 
				?>
				<?php if ($_REQUEST['sort_g_location_other'] == 2 && $_REQUEST['display_view'] == 2) { ?>
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
					<td class='display_title'>Description</td>
					<td class='display_title'>Ship From</td>
					<td class='display_title'>Favorite?</td>
				<?php } ?>
				<?php if ($_REQUEST['sort_g_location_other'] == 4) { ?>
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
					<td class='display_title'>Description</td>
					<td class='display_title'>Ship From</td>
				<?php } ?>
			</tr>
			<?php
		} else {
			if ((isset($_REQUEST["display_view"])) && ($_REQUEST["display_view"] == "1")) { ?>
				<tr class="headrow2">
					<td class='display_title'>Add to Cart</td>

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
					//if($_REQUEST["client_flg"]==1){
					?>
					<td class='display_title'>Favorite?</td>
					<?php
					//}
					?>
				</tr>
		<?php
			}
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
			$shipLat = "";
			$shipLong = "";
			while ($row = array_shift($dt_view_res)) {
				$shipLat = $row["ship_zip_latitude"];
				$shipLong = $row["ship_zip_longitude"];
			}
			if ($_REQUEST["gbox"] == 0) {
				$qaa = "Select * from quote_request limit 1";
			} else {
				$qaa = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_request.companyID = " . $_REQUEST["ID"] . " and quote_other.id = " . $_REQUEST["gbox"] . " order by quote_other.id DESC";
			}
			//$qaa = "Select * from quote_request Where companyID = " . $_REQUEST["ID"]. " and quote_item=1";
			$qdt_view_res = db_query($qaa);
			$qnumrows = tep_db_num_rows($qdt_view_res);

			$arrCombineItemView = array();
			$MGArray = array();
			$MGArray1 = array();
			$MGArray2 = array();

			$qty_avail = "";
			if ($qnumrows > 0) {
				while ($qgb = array_shift($qdt_view_res)) {
					// Added by Mooneem Jul-13-12 to Bring the green thread at top	
					$aa = "Select * from quote_other Where quote_id = " . $qgb["quote_id"];
					$inv_id_list = "";
					$MGArray = array();
					$x = 0;
					$bg = "#f4f4f4";
					$dt_view_res = db_query($aa);
					$sb = array_shift($dt_view_res);
					//declare variables

					//
					$dk = "";
					if ($_REQUEST["load_all"] == 1) {
						//AND (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
						$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Other' or inventory.box_type = 'Recycling' or inventory.box_type = 'DrumBarrelUCB' or inventory.box_type = 'DrumBarrelnonUCB') and inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
					} else {
						if ($_REQUEST["display-allrec"] == 1) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Other' or inventory.box_type = 'Recycling' or inventory.box_type = 'DrumBarrelUCB' or inventory.box_type = 'DrumBarrelnonUCB') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
						} elseif ($_REQUEST["display-allrec"] == 2) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Other' or inventory.box_type = 'Recycling' or inventory.box_type = 'DrumBarrelUCB' or inventory.box_type = 'DrumBarrelnonUCB') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
						} elseif ($_REQUEST["sort_g_tool2"] == 1) {
							$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Other' or inventory.box_type = 'Recycling' or inventory.box_type = 'DrumBarrelUCB' or inventory.box_type = 'DrumBarrelnonUCB') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
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
						//($_REQUEST["sort_g_tool2"] == 1) || 
						if (($_REQUEST["sort_g_tool2"] == 2) || ($show_rec_condition1 == "yes")) {
							// Added by Mooneem Jul-13-12 to Bring the green thread at top	
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2b_cost = "$" . number_format($b2b_cost, 2);


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
							//if (remove_non_numeric($inv["location"]) != "")		
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}

								$locLat = $inv["location_zip_latitude"];
								$locLong = $inv["location_zip_longitude"];

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
								$vendor_b2b_rescue_id = "";
								$shipfrom_state = "";
								$shipfrom_zip = "";
								$shipfrom_city = "";
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
										db();
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
									if ($rec_found_box == "n") {
										$after_po_val = $inv["after_actual_inventory"];
									} else {
										$after_po_val = $after_po_val_tmp;
									}
								}

								$to_show_rec = "y";

								if ($_REQUEST["g_timing"] == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								if ($_REQUEST["g_timing"] == 3) {
									$to_show_rec = "";
									$rowsel_arr = "";
									$rowsel_arr = explode(" ", $inv["buy_now_load_can_ship_in"], 2);

									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
									if (($rowsel_arr[1] == 'Weeks') && ($rowsel_arr[0] <= 13)) {
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

											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
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

									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									$estimated_next_load = "";
									$b2bstatuscolor = "";
									$expected_loads_per_mo = "";
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
													$estimated_next_load = "<font color=green> " . $inv["lead_time"] . " Days </font>";
												} else {
													$estimated_next_load = "<font color=green> " . $inv["lead_time"] . " Day </font>";
												}
											} else {
												$estimated_next_load = "<font color=green> " . $no_of_loaddays . " Days </font>";
											}
										} else {
											$estimated_next_load = $inv["lead_time"] . " Day";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green> Now </font>";
											}

											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green> " . $inv["lead_time"] . " Day </font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green> " . $inv["lead_time"] . " Days </font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red> Never (sell the " . $after_po_val . ") </font>";
											} else {
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red> Ask Purch Rep </font>";
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
										$estimated_next_load = "<font color=red> Ask Purch Rep </font>";
									}

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									$bwall = "";
									if ($inv["uniform_mixed_load"] == "Mixed") {
										if ($inv["blength_min"] == $inv["blength_max"]) {
											$blength = $inv["blength_min"];
										} else {
											$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										}
										if ($inv["bwidth_min"] == $inv["bwidth_max"]) {
											$bwidth = $inv["bwidth_min"];
										} else {
											$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										}
										if ($inv["bheight_min"] == $inv["bheight_max"]) {
											$bdepth = $inv["bheight_min"];
										} else {
											$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
										}

										$bwall_min = $inv["bwall_min"];
										$bwall_max = $inv["bwall_max"];

										if ($inv["bwall_min"] == $inv["bwall_max"]) {
											$bwall = $inv["bwall_min"] . " ply";
										} else {
											$bwall = $inv["bwall_min"] . "-" . $inv["bwall_max"] . " ply";
										}
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
									$tmpTDstr = "";

									if (isset($_REQUEST["sort_g_location_other"]) && ($_REQUEST["sort_g_location_other"] == "2" || $_REQUEST["sort_g_location_other"] == "3" || $_REQUEST["sort_g_location_other"] == "4")) {
										if ($after_po_val < 0) {
											$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
										} else if ($after_po_val >= $boxes_per_trailer) {
											$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
										} else {
											$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
										}

										$arrCombineItemView[$inv["ID"]]['ID'] = $inv["ID"];
										$arrCombineItemView[$inv["ID"]]['I'] = $inv["I"];
										$arrCombineItemView[$inv["ID"]]['qty'] = $qty;
										$arrCombineItemView[$inv["ID"]]['ship_from'] = $ship_from;
										$arrCombineItemView[$inv["ID"]]['vendor_b2b_rescue_id'] = $vendor_b2b_rescue_id;
										$arrCombineItemView[$inv["ID"]]['ReqId'] = $_REQUEST["ID"];
										$arrCombineItemView[$inv["ID"]]['expected_loads_per_mo'] = $expected_loads_per_mo;
										$arrCombineItemView[$inv["ID"]]['box_warehouse_id'] = $box_warehouse_id;
										$arrCombineItemView[$inv["ID"]]['next_load_available_date'] = $next_load_available_date;
										$arrCombineItemView[$inv["ID"]]['estimated_next_load'] = $estimated_next_load;
										$arrCombineItemView[$inv["ID"]]['boxes_per_trailer'] = number_format($boxes_per_trailer, 0);
										$arrCombineItemView[$inv["ID"]]['b2b_fob'] = $b2b_fob;
										$arrCombineItemView[$inv["ID"]]['miles_from'] = $miles_from;
										$arrCombineItemView[$inv["ID"]]['b2bstatuscolor'] = $b2bstatuscolor;
										$arrCombineItemView[$inv["ID"]]['b2bstatus_name'] = $b2bstatus_name;
										$arrCombineItemView[$inv["ID"]]['length'] = $length;
										$arrCombineItemView[$inv["ID"]]['width'] = $width;
										$arrCombineItemView[$inv["ID"]]['depth'] = $depth;
										//$arrCombineItemView[$inv["ID"]]['wall'] = $wall; //chnaged by Bhavna as $wall is not defined
										$arrCombineItemView[$inv["ID"]]['bwall'] = $bwall;
										$arrCombineItemView[$inv["ID"]]['description'] = $inv["description"];
										$arrCombineItemView[$inv["ID"]]['vendor_name'] = $vendor_name;
										$arrCombineItemView[$inv["ID"]]['ownername'] = $ownername;
										$arrCombineItemView[$inv["ID"]]['bwall'] = $inv["bwall"];
										$arrCombineItemView[$inv["ID"]]['ship_from2'] = $ship_from;
										$arrCombineItemView[$inv["ID"]]['after_po_val'] = $after_po_val;
										$arrCombineItemView[$inv["ID"]]['boxes_per_trailer'] = $boxes_per_trailer;
										$arrCombineItemView[$inv["ID"]]['miles_away_color'] = $miles_away_color;
										$arrCombineItemView[$inv["ID"]]['bpallet_qty'] = $bpallet_qty;
										$arrCombineItemView[$inv["ID"]]['b2b_cost'] = $b2b_cost;
										$arrCombineItemView[$inv["ID"]]['ship_ltl'] = $ship_ltl;
										$arrCombineItemView[$inv["ID"]]['prepay'] = $prepay;
										$arrCombineItemView[$inv["ID"]]['contracted'] = $contracted;
										$arrCombineItemView[$inv["ID"]]['b_urgent'] = $b_urgent;
										$arrCombineItemView[$inv["ID"]]['N'] = $inv["N"];
										$arrCombineItemView[$inv["ID"]]['DT'] = $inv["DT"];
										$arrCombineItemView[$inv["ID"]]['distC'] = $distC;
										$arrCombineItemView[$inv["ID"]]['box_urgent'] = $inv["box_urgent"];
										$arrCombineItemView[$inv["ID"]]['lead_time'] = $inv["lead_time"];
									} else {
										//To get the Actual PO, After Po
										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "1") {
											$tmpTDstr = "<tr  >";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $inv["I"] . ")'>";
											$tmpTDstr =  $tmpTDstr . "Add</font></a></td>";

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
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2b.usedcardboardboxes.com/?id=" . urlencode(encrypt_password(get_loop_box_id($inv["I"]))) . "' target='_blank' >View Item</a></td>";
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
											$tmpTDstr =  $tmpTDstr . " >";
											$tmpTDstr =  $tmpTDstr . $inv["description"] . "</div></td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . get_territory($ship_from2) . "</td>";

											//if($_REQUEST["client_flg"]==1){
											//
											if ($after_po_val < 0) {
												$qty_avail =  "<font color=blue>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											} else if ($after_po_val >= $boxes_per_trailer) {
												$qty_avail =  "<font color=green>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											} else {
												$qty_avail =  "<font color=black>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
											}
											$fav_b2bid = $inv["I"];
											$selFavouriteDt = db_query("SELECT id FROM clientdash_favorite_items WHERE favItems = 1 AND fav_b2bid = '" . $fav_b2bid . "' AND compid = '" . $_REQUEST["ID"] . "'  AND boxtype = 'other' ");
											?>

											<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
											<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
											<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $expected_loads_per_mo ?>">
											<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer ?>">
											<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
											<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $inv["I"] ?>">
											<input type="hidden" name="fav_miles" id="fav_miles<?php echo $fav_b2bid; ?>" value="<?php echo $miles_from ?>">
											<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $length ?>">
											<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $width ?>">
											<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $depth ?>">
											<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $inv["bwall"] ?>">
											<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $inv["description"] ?>">
											<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2 ?>">
											<?php
											//
											if (!empty($selFavouriteDt)) {
												$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><div id=fav_div_display_other" . $fav_b2bid . "><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='remove_item_as_favorite(" . $fav_b2bid . ")'><img src='images/fav.png' width='10px' height='10px'></a></div></td>";
											} else {
												$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><div id=fav_div_display_other" . $fav_b2bid . "><a id='div_favourite" . $inv["I"] . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $fav_b2bid . ")' ><img src='images/non_fav.png' width='12px' height='12px'> </a></div></td>";
											}
											//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a id='div_favourite".$inv["I"]."' href='javascript:void(0);' onClick='add_item_as_favorite(".$inv["I"].")' >Add</a></td>";
											//}

											$tmpTDstr =  $tmpTDstr . "</tr>";
										}
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
					$dt_view_qry = "";
					if ($inv_id_list != "") {
						$inv_id_list = substr($inv_id_list, 0, strlen($inv_id_list) - 1);
					}
					if ($_REQUEST["load_all"] == 1) {
						//and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'Other') order by warehouse, type_ofbox, Description";
					} else {
						if ($_REQUEST["display-allrec"] == 1) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'Other') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
						}
						if ($_REQUEST["display-allrec"] == 2) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'Other') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
						}
						if ($_REQUEST["sort_g_tool2"] == 1) {
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'Other') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
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

						

						$show_rec_condition1 = "no";
						$show_rec_condition2 = "no";
						$show_rec_condition3 = "no";
						$show_rec_condition4 = "no";
						$show_rec_condition5 = "no";
						$show_rec_condition6 = "no";
						$show_rec_condition7 = "no";
						$show_rec_condition8 = "no";
						$box_urgent = "";
						$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp;
						db_b2b();
						$dt_view_inv_res = db_query($inv_qry);
						$dt_view_row_inv = array_shift($dt_view_inv_res);
						//while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
							$inv_notes = $dt_view_row_inv["notes"];
							$inv_notes_dt = $dt_view_row_inv["date"];
							$location_city = $dt_view_row_inv["location_city"];
							$location_state = $dt_view_row_inv["location_state"];
							$location_zip = $dt_view_row_inv["location_zip"];
							$locLat = $dt_view_row_inv["location_zip_latitude"];
							$locLong = $dt_view_row_inv["location_zip_longitude"];
							$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
							$vendor_id = $dt_view_row_inv["vendor"];
							$box_urgent = $dt_view_row_inv["box_urgent"];
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
								db();
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
							$show_rec_condition1 = "yes";

							$inv_availability = $dt_view_row_inv["availability"];

							$tmp_zipval = "";	
							if ($dt_view_row_inv["location_zip"] != "") {
								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
								//echo $dt_view_row_inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
							}

						$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
						$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {
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

									if ($_REQUEST["g_timing"] == 3) {
										$to_show_rec = "";
										$rowsel_arr = "";
										$rowsel_arr = explode(" ", $dt_view_row_inv["buy_now_load_can_ship_in"], 2);

										if ($actual_po >= $boxes_per_trailer_tmp) {
											$to_show_rec = "y";
										}
										if (($rowsel_arr[1] == 'Weeks') && ($rowsel_arr[0] <= 13)) {
											$to_show_rec = "y";
										}
									}

									$estimated_next_load = "";
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
											if ($dt_view_row_inv["blength_min"] == $dt_view_row_inv["blength_max"]) {
												$blength = $dt_view_row_inv["blength_min"];
											} else {
												$blength = $dt_view_row_inv["blength_min"] . " - " . $dt_view_row_inv["blength_max"];
											}
											if ($dt_view_row_inv["bwidth_min"] == $dt_view_row_inv["bwidth_max"]) {
												$bwidth = $dt_view_row_inv["bwidth_min"];
											} else {
												$bwidth = $dt_view_row_inv["bwidth_min"] . " - " . $dt_view_row_inv["bwidth_max"];
											}
											if ($dt_view_row_inv["bheight_min"] == $dt_view_row_inv["bheight_max"]) {
												$bdepth = $dt_view_row_inv["bheight_min"];
											} else {
												$bdepth = $dt_view_row_inv["bheight_min"] . " - " . $dt_view_row_inv["bheight_max"];
											}

											$bwall_min = $dt_view_row_inv["bwall_min"];
											$bwall_max = $dt_view_row_inv["bwall_max"];

											if ($dt_view_row_inv["bwall_min"] == $dt_view_row_inv["bwall_max"]) {
												$bwall = $dt_view_row_inv["bwall_min"] . " ply";
											} else {
												$bwall = $dt_view_row_inv["bwall_min"] . "-" . $dt_view_row_inv["bwall_max"] . " ply";
											}
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

										$estimated_next_load = $dt_view_row_inv["buy_now_load_can_ship_in"];

										if ($dt_view_row_inv["box_urgent"] == 1) {
											$b2bstatuscolor = "red";
											$b2bstatus_nametmp = "URGENT";
										}

										//
										$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
										$dt_view = db_query($qry_loc);
										$shipfrom_state = "";
										$shipfrom_zip = "";
										$shipfrom_city = "";
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
										//$ship_from_tmp=$location_city . ", " . $location_state . " " . $location_zip;
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

										$tmpTDstr = "";
										$pallet_space_per = "";
										if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "1") {
											$tmpTDstr = "<tr  >";
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $b2bid_tmp . ")'>";
											$tmpTDstr =  $tmpTDstr . "Add</font></a></td>";

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
											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='https://b2b.usedcardboardboxes.com/?id=" . get_loop_box_id($dt_view_row_inv["ID"]) . "' target='_blank' >View Item</a></td>";
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
											$tmpTDstr =  $tmpTDstr . " >";

											$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</div></td>";

											$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from2_tmp . "</td>";

											//if($_REQUEST["client_flg"]==1){
											$qty_avail = "";
											if ($actual_po < 0) {
												$qty_avail =  "<font color=blue>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
											} else {
												$qty_avail =  "<font color=green>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
											}
											$fav_b2bid = $dt_view_row_inv["ID"];
											$selFavouriteDt = db_query("SELECT id FROM clientdash_favorite_items WHERE favItems = 1 AND fav_b2bid = '" . $fav_b2bid . "' AND compid = '" . $_REQUEST["ID"] . "'  AND boxtype = 'other' ");
											?>

											<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
											<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
											<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["expected_loads_per_mo"] ?>">
											<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer_tmp ?>">
											<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
											<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
											<input type="hidden" name="fav_miles" id="fav_miles<?php echo $fav_b2bid; ?>" value="<?php echo $miles_from ?>">
											<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[0] ?>">
											<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[1] ?>">
											<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[2] ?>">
											<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall"] ?>">
											<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row["Description"] ?>">
											<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2_tmp ?>">
											<?php
											//
											if (!empty($selFavouriteDt)) {
												$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><div id=fav_div_display_other" . $fav_b2bid . "><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='remove_item_as_favorite(" . $fav_b2bid . ")'><img src='images/fav.png' width='10px' height='10px'></a></div></td>";
											} else {
												$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><div id=fav_div_display_other" . $fav_b2bid . "><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $fav_b2bid . ")' ><img src='images/non_fav.png' width='12px' height='12px'> </a></div></td>";
											}
											//$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a id='div_favourite".$fav_b2bid."' href='javascript:void(0);' onClick='add_item_as_favorite(".$fav_b2bid.")' >Add</a></td>";

											//}

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

				if (isset($_REQUEST["sort_g_location_other"]) && ($_REQUEST["sort_g_location_other"] == "2" || $_REQUEST["sort_g_location_other"] == "3" || $_REQUEST["sort_g_location_other"] == "4")) {

					if ($_REQUEST["sort_g_location_other"] == "4") {
						foreach ($arrCombineItemView as $arrCombineItemViewKey => $arrCombineItemViewVal) {
							$tmpTDstr1 = "<tr bgColorrepl >";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['qty'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['estimated_next_load'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['expected_loads_per_mo'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['boxes_per_trailer'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['b2b_fob'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['I'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td><font color='" . $arrCombineItemViewVal['miles_away_color'] . "'>" . $arrCombineItemViewVal['miles_from'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['length'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td align='center'>x</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['width'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td align='center'>x</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['depth'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td> " . $arrCombineItemViewVal['bwall'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td> " . $arrCombineItemViewVal['description'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['ship_from2'] . "</td>";
							$tmpTDstr1 =  $tmpTDstr1 . "</tr>";

							$mileage1 = (int) (6371 * $arrCombineItemViewVal['distC'] * .621371192);
							$MGArray1[] = array('arrorder' => $mileage1, 'arrdet' => $tmpTDstr1, 'box_urgent' => $arrCombineItemViewVal['box_urgent']);
						}
					}

					$newLocArr = array();
					foreach ($arrCombineItemView as $k => $v) {
						$newLocArr[$v['vendor_b2b_rescue_id']][] = $v;
					}
					//echo "new array :  <pre>"; print_r($newLocArr); echo "</pre><br/>";
					$k = 1;
					foreach ($newLocArr as $newLocArrKey => $newLocArrValue) {
						$shipFrom 				= $newLocArrValue[0]['ship_from'];
						$vendor_b2b_rescue_id 	= $newLocArrValue[0]['vendor_b2b_rescue_id'];
						$ReqId 					= $newLocArrValue[0]['ReqId'];
						$ID 					= $newLocArrValue[0]['ID'];
						$vendor_name 			= $newLocArrValue[0]['vendor_name'];
						$miles_away_color 		= $newLocArrValue[0]['miles_away_color'];
						$bwall 					= $newLocArrValue[0]['bwall'];
						$miles_from 			= $newLocArrValue[0]['miles_from'];

						$arrENL = array();
						if (count($newLocArrValue) > 1) {
							$shipFrom 				= $newLocArrValue[0]['ship_from'];
							$vendor_b2b_rescue_id 	= $newLocArrValue[0]['vendor_b2b_rescue_id'];
							$ReqId 					= $newLocArrValue[0]['ReqId'];
							$ID 					= $newLocArrValue[0]['ID'];
							$qty 	= $expected_loads_per_mo = $expected_loads_per_mo_cal = 0;
							$I 	= '';
							$arrBPT = array();
							$arrFOB = array();
							$arrL 	= array();
							$arrW 	= array();
							$arrD 	= array();
							$arrShip = array();
							$arrLeadTime = array();
							$arrOwner = $arrB2bStatus = $arrb2bstatuscolor_internal = array();
							$arrTest = array();
							$estimated_next_load = "";
							for ($i = 0; $i < count($newLocArrValue); $i++) {
								if ($newLocArrValue[$i]['qty'] > 0) {
									$qty = $qty + str_replace(',', '', $newLocArrValue[$i]['qty']);
								}
								//$qty = $qty + str_replace( ',', '', $newLocArrValue[$i]['qty']);
								//echo $vendor_b2b_rescue_id. " / ". $qty."=".$qty." +". $newLocArrValue[$i]['qty']."<br />";
								$expected_loads_per_mo 	= $expected_loads_per_mo + $newLocArrValue[$i]['expected_loads_per_mo'];
								if ($i > 0) {
									$I	= $I . "." . $newLocArrValue[$i]['I'];
									$estimated_next_load = $estimated_next_load . "|" . $newLocArrValue[$i]['estimated_next_load'];
								} else {
									$I	= $newLocArrValue[0]['I'];
									$estimated_next_load = $newLocArrValue[0]['estimated_next_load'];
								}
								$arrBPT[$i] = $newLocArrValue[$i]['boxes_per_trailer'];
								$arrFOB[$i] = $newLocArrValue[$i]['b2b_fob'];
								$arrL[$i] 	= $newLocArrValue[$i]['length'];
								$arrW[$i] 	= $newLocArrValue[$i]['width'];
								$arrD[$i] 	= $newLocArrValue[$i]['depth'];
								$arrOwner[$i] = $newLocArrValue[$i]['ownername'];
								$arrB2bStatus[$i] = $newLocArrValue[$i]['b2bstatus_name'];
								$arrb2bstatuscolor_internal[$i] = $newLocArrValue[$i]['b2bstatuscolor'];

								$arrENL[$i] = $newLocArrValue[$i]['estimated_next_load'];
								/******calculating D70 cell start set all values in array******/
								$tmpqty = str_replace(',', '', $newLocArrValue[$i]['qty']);
								$tmpqty = (float)$tmpqty;
								if ($tmpqty >= 0) {
									//if($newLocArrValue[$i]['qty'] > 0 ){
									//if($newLocArrValue[$i]['expected_loads_per_mo'] > 0 ){ 
									/**FORMULA PART**Just like A/T1, B/T2, C/T3 ******/
									$arrShip[$newLocArrValue[$i]['I']]  = (float)str_replace(',', '', $newLocArrValue[$i]['qty']) / (float)str_replace(',', '', $newLocArrValue[$i]['boxes_per_trailer']);
									$expected_loads_per_mo_cal 	= $expected_loads_per_mo_cal + $newLocArrValue[$i]['expected_loads_per_mo'];
									$arrLeadTime[$newLocArrValue[$i]['I']] = $newLocArrValue[$i]['lead_time'];
								} else {
									$arrTest[$i] = $newLocArrValue[$i]['estimated_next_load'];
								}
								/******calculating D70 cell end set all values in array******/
							}
							if (min($arrBPT) == max($arrBPT)) {
								$boxes_per_trailer 	= min($arrBPT);
							} else {
								$boxes_per_trailer 	= min($arrBPT) . " - " . max($arrBPT);
							}
							//$boxes_per_trailer 	= min($arrBPT)." - ".max($arrBPT);

							if (min($arrFOB) == max($arrFOB)) {
								$b2b_fob = min($arrFOB);
							} else {
								$b2b_fob = min($arrFOB) . " - " . max($arrFOB);
							}
							//$b2b_fob = min($arrFOB)." - ".max($arrFOB);

							if (min($arrL) == max($arrL)) {
								$length = min($arrL);
							} else {
								$length = min($arrL) . " - " . max($arrL);
							}

							if (min($arrW) == max($arrW)) {
								$width = min($arrW);
							} else {
								$width = min($arrW) . " - " . max($arrW);
							}

							if (min($arrD) == max($arrD)) {
								$depth = min($arrD);
							} else {
								$depth = min($arrD) . " - " . max($arrD);
							}
							$ownername = implode(',', array_unique($arrOwner));
							$b2bStatus = implode(' | ', array_unique($arrB2bStatus));
							$b2bstatuscolor_internal = implode(' | ', array_unique($arrb2bstatuscolor_internal));
						} else {
							$qty = 0;
							$I 						= $newLocArrValue[0]['I'];
							if ($newLocArrValue[0]['qty'] > 0) {
								$qty				= str_replace(',', '', $newLocArrValue[0]['qty']);
							}
							$expected_loads_per_mo 	= $newLocArrValue[0]['expected_loads_per_mo'];
							$estimated_next_load 	= $newLocArrValue[0]['estimated_next_load'];
							$ownername 				= $newLocArrValue[0]['ownername'];
							$boxes_per_trailer 		= $newLocArrValue[0]['boxes_per_trailer'];
							$b2b_fob 				= $newLocArrValue[0]['b2b_fob'];
							$length 				= $newLocArrValue[0]['length'];
							$width 					= $newLocArrValue[0]['width'];
							$depth 					= $newLocArrValue[0]['depth'];
							$b2bstatuscolor_internal = $newLocArrValue[0]['b2bstatuscolor'];
							$b2bStatus 				= $newLocArrValue[0]['b2bstatus_name'];
						}

						//echo "<br /><br /><br />".$vendor_b2b_rescue_id."<br />arrENL => <pre>"; print_r($arrENL); echo "</pre>";
						if (count($newLocArrValue) > 1) {
							$tempENLDays = $arrENLTemp = $tempENLDaysW = $tempENLDaysD = array();
							foreach ($arrENL as $arrENLKey => $arrENLValue) {
								//echo "arrENLValue => <pre>"; print_r($arrENLValue); echo "</pre>";
								$arrENLTemp = explode(' ', $arrENLValue);

								//echo "<br />arrENLTemp => <pre>"; print_r($arrENLTemp); echo "</pre>";
								if ($arrENLTemp[1] == 'Weeks' || $arrENLTemp[1] == 'Week') {
									$tempENLDays[$arrENLKey] = (float)$arrENLTemp[0] * 7;
								} elseif ($arrENLTemp[3] == 'Days' || $arrENLTemp[3] == 'Day') {
									$tempENLDays[$arrENLKey] = $arrENLTemp[2];
								}
							}

							//echo "<br />tempENLDays => <pre>"; print_r($tempENLDays); echo "</pre>";
							$daysTempKeyVal = array_keys($tempENLDays, min($tempENLDays));
							//echo "<br />daysTempKeyVal => <pre>"; print_r($daysTempKeyVal); echo "</pre>";
							$loadCanShip =  $arrENL[$daysTempKeyVal[0]];
						} else {
							$loadCanShip = $estimated_next_load;
						}
						$tmpTDstr2 = "";
						if ($_REQUEST["sort_g_location_other"] == "2") {
							$tmpTDstr2 = "<tr bgColorrepl id='selrow" . $vendor_b2b_rescue_id . "'>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($qty, 0) . "</td>";
							//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$estimated_next_load."</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $loadCanShip . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $expected_loads_per_mo . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $boxes_per_trailer . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $b2b_fob . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $b2bstatuscolor_internal . "'>" . $b2bStatus . "</font></td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $length . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $width . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $depth . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td><a name='location_details_show' id='loc_btn_" . $vendor_b2b_rescue_id . "' onClick='show_loc_dtls(" . $vendor_b2b_rescue_id . "," . count($newLocArrValue) . "," . $_REQUEST["sort_g_location_other"] . " )' class='ex_col_btn' >" . $shipFrom . "</a></td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $ownername . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

							$childRow = 1;
							for ($i = 0; $i < count($newLocArrValue); $i++) {
								if ($childRow % 2 == 0) {
									$classChild = 'display_table_alt_child';
								} else {
									$classChild = 'display_table_child';
								}
								$tmpTDstr2 =  $tmpTDstr2 . "<tr class='" . $classChild . "' id='loc_sub_table_" . $i . "_" . $vendor_b2b_rescue_id . "' style='display: none;'>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td><a href='javascript:void(0)' onclick='addgaylordMultiple(" . $newLocArrValue[$i]['ReqId'] . "," . $newLocArrValue[$i]['I'] . ")'>";
								$tmpTDstr2 =  $tmpTDstr2 . "Add </font></a></td>";
								if ($newLocArrValue[$i]['after_po_val'] < 0) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='blue'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else if ($newLocArrValue[$i]['after_po_val'] >= $newLocArrValue[$i]['boxes_per_trailer']) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='green'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='black'>" . $newLocArrValue[$i]['qty'] . "</td>";
								}
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['estimated_next_load'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['expected_loads_per_mo'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($newLocArrValue[$i]['boxes_per_trailer'], 0) . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['b2b_fob'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['I'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['miles_away_color'] . "'>" . $newLocArrValue[$i]['miles_from'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['b2bstatuscolor'] . "'>" . $newLocArrValue[$i]['b2bstatus_name'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['length'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['width'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['depth'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . encrypt_url(get_loop_box_id($newLocArrValue[$i]['I'])) . "&proc=View&'";
								$tmpTDstr2 =  $tmpTDstr2 . " >";
								$tmpTDstr2 =  $tmpTDstr2 . $newLocArrValue[$i]['description'] . "</a></td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['vendor_name'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ship_from'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ownername'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

								$childRow++;
							}
						}

						//if($_REQUEST["sort_g_location_other"]=="3"){
						if ($_REQUEST["display_view"] == "2") {
							$tmpTDstr2 = "<tr bgColorrepl id='selrow" . $vendor_b2b_rescue_id . "'>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td bgColorrepl>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($qty, 0) . "</td>";
							//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$estimated_next_load."</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $loadCanShip . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $expected_loads_per_mo . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $boxes_per_trailer . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $length . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $width . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $depth . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $bwall . "</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td><a name='location_details_show' id='loc_btn_" . $vendor_b2b_rescue_id . "' onClick='show_loc_dtls(" . $vendor_b2b_rescue_id . "," . count($newLocArrValue) . "," . $_REQUEST["sort_g_location_other"] . " )' class='ex_col_btn' >" . $shipFrom . "</a></td>";
							$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
							$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

							$childRow = 1;
							for ($i = 0; $i < count($newLocArrValue); $i++) {
								if ($childRow % 2 == 0) {
									$classChild = 'display_table_alt_child';
								} else {
									$classChild = 'display_table_child';
								}
								$tmpTDstr2 =  $tmpTDstr2 . "<tr class='" . $classChild . "' id='loc_sub_table_" . $i . "_" . $vendor_b2b_rescue_id . "' style='display: none;'>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td ><a href='https://b2b.usedcardboardboxes.com/?id=" . get_loop_box_id($newLocArrValue[$i]['I']) . "' target='_blank' >View Item</a></td>";
								
								//Old code replaced by bhavna as $arrRow is undefined
								/*if ($arrRow->after_po_val < 0) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='blue'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else if ($arrRow->after_po_val >= $arrRow->boxes_per_trailer) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='green'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='black'>" . $newLocArrValue[$i]['qty'] . "</td>";
								}
								*/
								if ($newLocArrValue[$i]['after_po_val'] < 0) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='blue'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else if ($newLocArrValue[$i]['after_po_val'] >= $newLocArrValue[$i]['boxes_per_trailer']) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='green'>" . $newLocArrValue[$i]['qty'] . "</td>";
								} else {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='black'>" . $newLocArrValue[$i]['qty'] . "</td>";
								}
								
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['estimated_next_load'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['expected_loads_per_mo'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($newLocArrValue[$i]['boxes_per_trailer'], 0) . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['b2b_fob'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['I'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['miles_away_color'] . "'>" . $newLocArrValue[$i]['miles_from'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['length'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['width'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['depth'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['bwall'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td>" .  $newLocArrValue[$i]['description'] . "</td>";
								$tmpTDstr2 =  $tmpTDstr2 . "<td> " . $newLocArrValue[$i]['ship_from2'] . " </td>";

								$fav_b2bid = $newLocArrValue[$i]['I'];
								?>

								<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
								<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['estimated_next_load'] ?>">
								<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['expected_loads_per_mo'] ?>">
								<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['boxes_per_trailer'] ?>">
								<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['b2b_fob'] ?>">
								<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
								<input type="hidden" name="fav_miles" id="fav_miles<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['miles_from'] ?>">

								<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['length'] ?>">
								<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['width'] ?>">
								<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['depth'] ?>">
								<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['bwall'] ?>">
								<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['description'] ?>">
								<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $newLocArrValue[$i]['ship_from2'] ?>">
				<?php

								$selFavouriteDt = db_query("SELECT id FROM clientdash_favorite_items WHERE favItems = 1 AND fav_b2bid = '" . $fav_b2bid . "' AND compid = '" . $_REQUEST['compnewid'] . "' AND boxtype = 'other'");
								if (!empty($selFavouriteDt)) {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><div id=fav_div_display_other" . $fav_b2bid . ">
						<a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='remove_item_as_favorite(" . $fav_b2bid . ")'><img src='images/fav.png' width='10px' height='10px'></a></div></td>";
								} else {
									$tmpTDstr2 =  $tmpTDstr2 . "<td><div id=fav_div_display_other" . $fav_b2bid . "><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $fav_b2bid . ")' ><img src='images/non_fav.png' width='12px' height='12px'> </a></div></td>";
								}
								$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

								$childRow++;
							}
						}

						$mileage2 = (int) (6371 * $newLocArrValue[0]['distC'] * .621371192);
						$MGArray2[] = array('arrorder' => $mileage2, 'arrdet' => $tmpTDstr2, 'box_urgent' => $newLocArrValue[0]['box_urgent']);
						$k++;
					} // close foreach newLocArr		

				}

				// Added by Mooneem Jul-13-12 to Bring the green thread at top	
				// Sort the Array based on Mileage	
				$MGArraysort = array();
				$MGArraysort_1 = array();
				$MGArraysort_2 = array();
				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['arrorder'];
				}

				foreach ($MGArray1 as $MGArraytmp_1) {
					$MGArraysort_1[] = $MGArraytmp_1['arrorder'];
				}
				foreach ($MGArray2 as $MGArraytmp_2) {
					$MGArraysort_2[] = $MGArraytmp_2['arrorder'];
				}

				array_multisort($MGArraysort, SORT_NUMERIC, $MGArray);

				array_multisort($MGArraysort_1, SORT_NUMERIC, $MGArray1);
				array_multisort($MGArraysort_2, SORT_NUMERIC, $MGArray2);

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
				$x_1 = 0;
				foreach ($MGArray1 as $MGArraytmp2_1) {
					if ($x_1 == 0) {
						$x_1 = 1;
						$bg_1 = "#e4e4e4";
						$bgstyle_1 = "display_table";
					} else {
						$x_1 = 0;
						$bg_1 = "#f4f4f4";
						$bgstyle_1 = "display_table_alt";
					}
					echo preg_replace("/bgColorrepl/", "class=$bgstyle_1", $MGArraytmp2_1['arrdet']);
				}
				$x_2 = 0;
				foreach ($MGArray2 as $MGArraytmp2_2) {
					if ($x_2 == 0) {
						$x_2 = 1;
						$bg_2 = "#e4e4e4";
						$bgstyle_2 = "display_table";
					} else {
						$x_2 = 0;
						$bg_2 = "#f4f4f4";
						$bgstyle_2 = "display_table_alt";
					}
					echo preg_replace("/bgColorrepl/", "class=$bgstyle_2", $MGArraytmp2_2['arrdet']);
				}
				?>
		<?php
			} //end if num>0
		}
		?>
	</table>
</div>