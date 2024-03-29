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

function getinventory_change(int $id): string
{
	if ($id != "") {
		db();
		$dt_so_item = "SELECT *, loop_salesorders.location_warehouse_id AS wid FROM loop_salesorders JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id WHERE loop_transaction_buyer.bol_create =0 AND box_id =" . $id;
		$dt_res_so_item = db_query($dt_so_item);
		$inv_array = array();
		while ($so_item_row = array_shift($dt_res_so_item)) {

			$inv_array[$so_item_row["wid"]][$so_item_row["box_id"]] += $so_item_row["qty"];
		}

		//print_r($so_item_row);
		$res = "";
		$box_type = "";
		$dt_view_qry = "SELECT loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id WHERE loop_boxes.id = " . $id . " GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth ";
		//echo $dt_view_qry;
		$dt_view_res = db_query($dt_view_qry);
		//echo $st_view_qry;
		$num_rows = tep_db_num_rows($dt_view_res);
		
		$hasrecprint = "n";
		if ($num_rows > 0)
		while ($dt_view_row = array_shift($dt_view_res)) {
			$box_type = $dt_view_row["TYPE"];
			if ($box_type == "GaylordUCB") {
				if ($res == "") {
					$res .= "<b>UCB Owned Inventory</b><br>";
				}
				if ($dt_view_row["A"] != 0) {

					if ($dt_view_row["ISBOX"] != 'Y') {
						if ($dt_view_row["B"] != 'Virtual Inventory') {
							$hasrecprint = "y";
							$res .= "Actual: " . $dt_view_row["A"] . "<br>";
							$res .= "After PO: " . ($dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]) . "<br>";
							$res .= "Location: " . $dt_view_row["B"] . "<br> ";
							$res .= "Box Type: " . $dt_view_row["TYPE"] . " ";
							$res .= " <BR>";
						}
					} else {
						if ($dt_view_row["B"] != 'Virtual Inventory') {
							$hasrecprint = "y";
							$res .= "Actual: " . $dt_view_row["A"] . "<br> ";
							$res .= "After PO: " . ($dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]) . "<br> ";
							$res .= "Location: " . $dt_view_row["B"] . "<br> ";
							$res .= "Box Type: " . $dt_view_row["TYPE"] . "<br> ";
							$res .= " <BR>";
						}
					}
				}
			}
		}
		if ($box_type == "GaylordUCB") {
			if ($hasrecprint == "n") {
				$res .= "Actual: <br>";
				$res .= "After PO: <br>";
				$res .= "Location: <br> ";
				$res .= "Box Type: ";
				$res .= " <BR>";
			}
		}

		//if ($box_type == "Gaylord"){
		$dt_view_qry = "SELECT *, inventory.notes AS N, inventory.date AS DT FROM inventory WHERE inventory.gaylord=1 AND inventory.ID = " . get_b2b_box_id($id) . " ORDER BY inventory.availability DESC";
		db_b2b();
		$dt_view_res = db_query($dt_view_qry);
		//$res .= $dt_view_qry;
		while ($inv = array_shift($dt_view_res)) {

			if ($inv["active"] == "A") {
				$res .= "<b>Non-UCB Owned Inventory: </b><br>";
				if ($inv["availability"] == "0") {
					$res .= "Not Available";
				}
				if ($inv["availability"] == "1") {
					$res .= "Available Soon";
				}
				if ($inv["availability"] == "2") {
					$res .= "Available Now";
				}
				if ($inv["availability"] == "3") {
					$res .= "Available & Urgent";
				}
				if ($inv["availability"] == "2.5") {
					$res .= "Available >= 1TL";
				}
				if ($inv["availability"] == "2.15") {
					$res .= "Available < 1TL";
				}
				if ($inv["availability"] == "-4") {
					$res .= "Inactive";
				}
				if ($inv["availability"] == "-1") {
					$res .= "Presell Available";
				}
				if ($inv["availability"] == "-2") {
					$res .= "Active but Unavailable";
				}
				if ($inv["availability"] == "-3") {
					$res .= "Potential";
				}
				if ($inv["availability"] == "-3.5") {
					$res .= "Check Loops";
				}
				$res .= "<br> ";
				$res .= "Actual: " . $inv["actual_inventory"] . "<br> After PO: " . $inv["after_actual_inventory"] . "<br> Last Month Qty: " . $inv["lastmonthqty"];
				$res .= "<br> ";
				$res .= "Last Updated: " . date('m-d-Y', strtotime($inv["DT"])) . "<br> ";
				$res .= "Inventory Note: " . $inv["N"];
			}
		}
		//}
		return $res;
	} else {
		return "";
	}
}

?>

<div class="scrollit">
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td colspan="10">
				<font face='Arial, Helvetica, sans-serif' size='1'><a href='#' onmouseover="Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. And matches are done based on box paramters saved in the database.')" onmouseout="UnTip()">How it Works</a></font>
			</td>
		</tr>

		<tr>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Add</font>
			</td>

			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Actual</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>After PO</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Last Month Quantity</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Availability</font>
			</td>

			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Vendor</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Ship From</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Miles From</font>
			</td>

			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>L x W x H</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Description</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Per Pallet</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Per Trailer</font>
			</td>
			<td bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>MIN FOB</font>
			</td>
			<td width="55px" bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>COST</font>
			</td>
			<td width="50px" bgColor="#e4e4e4">
				<font face='Arial, Helvetica, sans-serif' size='1'>Matches</font>
			</td>
		</tr>
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

					$tmppos_1 = strpos($row["shipZip"], " ");
					if ($tmppos_1 != false) {
						$tmp_zipval = str_replace(" ", "", $row["shipZip"]);
						$zipShipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
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

			$aa = "Select * from boxesGaylord Where companyID = " . $_REQUEST["ID"];
			// Added by Mooneem Jul-13-12 to Bring the green thread at top	

			$inv_id_list = "";
			$MGArray = array();
			$x = 0;
			$bg = "#f4f4f4";
			db_b2b();
			$dt_view_res = db_query($aa);
			while ($gb = array_shift($dt_view_res)) {
				if ($_REQUEST["display-allrec"] == 1) {
					//$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC, vendors.name ASC";
					$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 and availability != 1 and availability != -1 and availability != -3 and availability != 0 and availability != -3.5 and availability != -4 and availability != -2 AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC, vendors.name ASC";
				} else {
					//$dk = "Select *, inventory.id AS I from inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.availability in (1,2,3,2.5,2.15) and box_type in ('Gaylord', 'GaylordUCB') AND active LIKE 'A' AND availability != -4 ORDER BY vendors.Name";
					$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 ORDER BY inventory.availability DESC, vendors.name ASC";
				}
				//echo $dk . "<br>";
				db_b2b();
				$yyyy = db_query($dk);
				$xxx =  tep_db_num_rows($yyyy);

				$count = 0;	
				$tipcount_match_str = "";
				while ($inv = array_shift($yyyy)) {
					$count = 0;
					$tipcount_match_str = "";
					//echo $count;
					if ((int) $gb["shape_rect"] + (int) $inv["shape_rect"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Rectangular Shape missing<br>";
					}

					if ((int) $gb["shape_oct"] + (int) $inv["shape_oct"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Octagonal Shape missing<br>";
					}

					if ((int) $gb["wall_2"] + (int) $inv["wall_2"] == 2) {
						$count = $count + 1;
					}

					if ((int) $gb["wall_3"] + (int) $inv["wall_3"] == 2) {
						$count = $count + 1;
					}

					if ((int) $gb["wall_4"] + (int) $inv["wall_4"] == 2) {
						$count = $count + 1;
					}

					if ((int) $gb["wall_5"] + (int) $inv["wall_5"] == 2) {
						$count = $count + 1;
					}

					if ((int) $gb["top_nolid"] + (int) $inv["top_nolid"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Top: No Flaps or Lid missing<br>";
					}

					if ((int) $gb["top_partial"] + (int) $inv["top_partial"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Top: Partial Flap (P) missing<br>";
					}

					if ((int) $gb["top_full"] + (int) $inv["top_full"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Top: Full Flap (F) missing<br>";
					}

					if ((int) $gb["top_hinged"] + (int) $inv["top_hinged"] == 2) {
						$count = $count + 1;
					}

					if ((int) $gb["top_remove"] + (int) $inv["top_remove"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Top: Removable Lid (L) missing<br>";
					}

					if ((int) $gb["bottom_no"] + (int) $inv["bottom_no"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: No Flaps or Lid (N) missing<br>";
					}

					if ((int) $gb["bottom_partial"] + (int) $inv["bottom_partial"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: Partial Flap Without Slip Sheet (P) missing<br>";
					}

					if ((int) $gb["bottom_partialsheet"] + (int) $inv["bottom_partialsheet"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: Partial Flap With Slip Sheet (S) missing<br>";
					}

					if ((int) $gb["bottom_fullflap"] + (int) $inv["bottom_fullflap"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: Full Flap (F) missing<br>";
					}

					if ((int) $gb["bottom_interlocking"] + (int) $inv["bottom_interlocking"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: Interlocking Flaps (I) missing<br>";
					}

					if ((int) $gb["bottom_tray"] + (int) $inv["bottom_tray"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Bottom: Tray (T) missing<br>";
					}

					if ((int) $gb["vents_no"] + (int) $inv["vents_no"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Vents: No (N) missing<br>";
					}

					if ((int) $gb["vents_yes"] + (int) $inv["vents_yes"] == 2) {
						$count = $count + 1;
					} else {
						$tipcount_match_str .= "Vents: Yes (V) missing<br>";
					}

					if ($count >= 0) {

						// Added by Mooneem Jul-13-12 to Bring the green thread at top	
						//$tmpTDstr = "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . get_loop_box_id($inv["I"]) . "&proc=Edit&'";

						$b2b_ulineDollar = round($inv["ulineDollar"]);
						$b2b_ulineCents = $inv["ulineCents"];
						$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
						$b2b_fob = "$ " . number_format($b2b_fob, 2);

						$b2b_costDollar = round($inv["costDollar"]);
						$b2b_costCents = $inv["costCents"];
						$b2b_cost = $b2b_costDollar + $b2b_costCents;
						$b2b_cost = "$ " . number_format($b2b_cost, 2);

						//if ($inv["location"] != "" )
						//$tipStr = $tipStr . "Location: " . $inv["location"] . "<br>";


						$bpallet_qty = 0;
						$boxes_per_trailer = 0;
						$box_type = "";
						$loop_id = 0;
						db();
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

						$tipStr = "Loops ID#: " . $loop_id . "<br>";
						$tipStr .= "B2B ID#: " . $inv["I"] . "<br>";
						$tipStr .= "Notes: " . $inv["N"] . "<br>";
						if ($inv["DT"] != "0000-00-00") {
							$tipStr .= "Notes Date: " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
						} else {
							$tipStr .= "Notes Date: <br>";
						}

						//$zipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($inv["location"]);
						$tmp_zipval = "";
						$tmppos_1 = strpos($inv["location_zip"], " ");
						if ($tmppos_1 != false) {
							$tmp_zipval = str_replace(" ", "", $inv["location_zip"]);
							$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
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
							//echo $distC . "g <br/>";

							//To get the Actual PO, After PO
							$rec_found_box = "n";
							//$dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.b2b_id = " . $inv["I"] . " GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . get_loop_box_id($inv["I"]) . " order by warehouse, type_ofbox, Description";
							db_b2b();
							$dt_view_res_box = db_query($dt_view_qry);

							$actual_val = 0;
							$after_po_val = 0;
							$last_month_qty = 0;
							$pallet_val = "";
							$pallet_val_afterpo = "";
							$tmp_noofpallet = 0;
							$ware_house_boxdraw = "";
							$preorder_txt = "";
							$preorder_txt2 = "";

							if ($rec_found_box == "n") {
								$actual_val = $inv["actual_inventory"];
								$after_po_val = $inv["after_actual_inventory"];
								$last_month_qty = $inv["lastmonthqty"];
							}				
							$tmpTDstr = "<tr bgcolor='#E4E4E4' >";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $inv["I"] . ")'>";
							$tmpTDstr =  $tmpTDstr . "Add</a></font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>";
							if ($actual_val < 0) {
								$tmpTDstr =  $tmpTDstr . "<font color='red'>" . $actual_val . $pallet_val . "</font></td>";
							} else {
								$tmpTDstr =  $tmpTDstr . "<font color='green'>" . $actual_val . $pallet_val . "</font></td>";
							}

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>";
							if ($after_po_val < 0) {
								$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . $after_po_val . $pallet_val_afterpo . $preorder_txt2 . "</font></td>";
							} else {
								$tmpTDstr =  $tmpTDstr . "<font color='green'>" . $after_po_val . $pallet_val_afterpo . $preorder_txt2 . "</font></td>";
							}

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $last_month_qty . "</font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl ><font face='Arial, Helvetica, sans-serif' size='1'>";
							if ($inv["availability"] == "3") $tmpTDstr =  $tmpTDstr . "<b>";
							if ($inv["availability"] == "3") $tmpTDstr =  $tmpTDstr . "Available Now & Urgent";
							if ($inv["availability"] == "2") $tmpTDstr =  $tmpTDstr . "Available Now";
							if ($inv["availability"] == "1") $tmpTDstr =  $tmpTDstr . "Available Soon";
							if ($inv["availability"] == "2.5") $tmpTDstr =  $tmpTDstr . "Available >= 1TL";
							if ($inv["availability"] == "2.15") $tmpTDstr =  $tmpTDstr . "Available < 1TL";
							if ($inv["availability"] == "-1") $tmpTDstr =  $tmpTDstr . "Presell";
							if ($inv["availability"] == "-2") $tmpTDstr =  $tmpTDstr . "Active by Unavailable";
							if ($inv["availability"] == "-3") $tmpTDstr =  $tmpTDstr . "Potential";
							if ($inv["availability"] == "-3.5") $tmpTDstr =  $tmpTDstr . "Check Loops";
							$tmpTDstr =  $tmpTDstr . "</font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $inv["Name"] . "</font></td>";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"] . "</font></td>";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . (int) (6371 * $distC * .621371192) . "</font></td>";

							$tmpTDstr =  $tmpTDstr . "<td  bgColorrepl width='70px'><font face='Arial, Helvetica, sans-serif' size='1'>" . $inv["lengthInch"] . " x " . $inv["widthInch"] . " x " . $inv["depthInch"] . "</font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . encrypt_url(get_loop_box_id($inv["I"])) . "&proc=View&'";
							$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . $tipStr . "')\" onmouseout=\"UnTip()\"";

							//echo " >" ;
							$tmpTDstr =  $tmpTDstr . " >" . "<font face='Arial, Helvetica, sans-serif' size='1' color=blue>";

							if ((int) (6371 * $distC * .621371192) < 301) {	//echo "chk gr <br/>";
								$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' Color=green>";
							}
							if (((int) (6371 * $distC * .621371192) < 601) && ((int) (6371 * $distC * .621371192) > 300)) {
								$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' color='#FF9933'>";
							}
							if (((int) (6371 * $distC * .621371192) > 600)) {
								$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' color=red>";
							}
							$tmpTDstr =  $tmpTDstr . $inv["description"] . "</a></font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $bpallet_qty . "</font></td>";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $boxes_per_trailer . "</font></td>";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $b2b_fob . "</font></td>";
							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $b2b_cost . "</font></td>";

							$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . "<a href='#' onmouseover=\"Tip('" . $tipcount_match_str . "')\" onmouseout=\"UnTip()\" >";
							$tmpTDstr =  $tmpTDstr . $count . "/5</a></font></td>";

							$tmpTDstr =  $tmpTDstr . "</tr>";

							$mileage = (int) (6371 * $distC * .621371192);

							$MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr);
							//}
						}
					} //if count > 4
				} //inv

				//From UCB owned inventory

				if ($inv_id_list != "") {
					$inv_id_list = substr($inv_id_list, 0, strlen($inv_id_list) - 1);
				}
				if ($_REQUEST["display-allrec"] == 1) {
					$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') and inv_id not in ($inv_id_list) order by warehouse, type_ofbox, Description";
				} else {
					$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') and inv_id not in ($inv_id_list) order by warehouse, type_ofbox, Description";
				}

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
					db();
					$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor from loop_boxes where id=" . $dt_view_row["trans_id"];
					$dt_view_loopbox = db_query($qry_loopbox);
					while ($rs_loopbox = array_shift($dt_view_loopbox)) {
						$b2bid_tmp = $rs_loopbox['b2b_id'];
						$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
						$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
						$vendor_id = $rs_loopbox['vendor'];
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
					$location_city = "";
					$location_state = "";
					$location_zip = "";
					$inv_qry = "SELECT * from inventory where id = " . $b2bid_tmp;
					db();
					$dt_view_inv_res = db_query($inv_qry);
					while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
						$inv_notes = $dt_view_row_inv["notes"];
						$inv_notes_dt = $dt_view_row_inv["date"];
						$location_city = $dt_view_row_inv["location_city"];
						$location_state = $dt_view_row_inv["location_state"];
						$location_zip = $dt_view_row_inv["location_zip"];

						$inv_availability = $dt_view_row_inv["availability"];

						$tmp_zipval = "";
						$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
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
					}

					$tipStr = "Loops ID#: " . $dt_view_row["trans_id"] . "<br>";
					$tipStr .= "B2B ID#: " . $b2bid_tmp . "<br>";
					$tipStr .= "Notes: " . $inv_notes . "<br>";
					if ($inv_notes_dt != "0000-00-00") {
						$tipStr .= "Notes Date: " . date("m/d/Y", strtotime($inv_notes_dt)) . "<br>";
					} else {
						$tipStr .= "Notes Date: <br>";
					}
					$tipStr .= "<b>UCB Owned Inventory </b><br>";

					$b2b_fob = "$ " . number_format((float)$dt_view_row["min_fob"], 2);
					$b2b_cost = "$ " . number_format((float)$dt_view_row["cost"], 2);

					$sales_order_qty = $dt_view_row["sales_order_qty"];

					if ($dt_view_row["actual"] != 0 or $dt_view_row["actual"] - $sales_order_qty != 0) {
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
						$actual_po = $dt_view_row["actual"] - $sales_order_qty;

						if ($bpallet_qty_tmp > 0) {
							$pallet_val = number_format($dt_view_row["actual"] / $bpallet_qty_tmp, 1, '.', '');
							$pallet_val_afterpo = number_format($actual_po / $bpallet_qty_tmp, 1, '.', '');
						}

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

						$pallet_space_per = "";

						$tmpTDstr = "<tr bgcolor='#E4E4E4' >";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $b2bid_tmp . ")'>";
						$tmpTDstr =  $tmpTDstr . "Add</a></font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>";
						if ($dt_view_row["actual"] < 0) {
							$tmpTDstr =  $tmpTDstr . "<font color='red'>" . $dt_view_row["actual"] . $pallet_val . "</font></td>";
						} else {
							$tmpTDstr =  $tmpTDstr . "<font color='green'>" . $dt_view_row["actual"] . $pallet_val . "</font></td>";
						}

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>";
						if ($actual_po < 0) {
							$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . $actual_po . $pallet_val_afterpo . $preorder_txt2 . "</font></td>";
						} else {
							$tmpTDstr =  $tmpTDstr . "<font color='green'>" . $actual_po . $pallet_val_afterpo . $preorder_txt2 . "</font></td>";
						}

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $lastmonth_val . "</font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl ><font face='Arial, Helvetica, sans-serif' size='1'>" . $dt_view_row["warehouse"];
						$tmpTDstr =  $tmpTDstr . "</font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $vendor_name . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $location_city . ", " . $location_state . " " . $location_zip . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . (int) (6371 * $distC * .621371192) . "</font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $dt_view_row["LWH"] . "</font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . encrypt_url($dt_view_row["trans_id"]) . "&proc=View&'";
						$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . $tipStr . "')\" onmouseout=\"UnTip()\"";

						//echo " >" ;
						$tmpTDstr =  $tmpTDstr . " >" . "<font face='Arial, Helvetica, sans-serif' size='1' color=blue>";

						if ((int) (6371 * $distC * .621371192) < 301) {
							$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' Color=green>";
						}
						if (((int) (6371 * $distC * .621371192) < 601) && ((int) (6371 * $distC * .621371192) > 300)) {
							$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' color='#FF9933'>";
						}
						if (((int) (6371 * $distC * .621371192) > 600)) {
							$tmpTDstr =  $tmpTDstr . "<font face='Arial, Helvetica, sans-serif' size='1' color=red>";
						}
						$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</a></font></td>";

						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $bpallet_qty_tmp . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . number_format($boxes_per_trailer_tmp, 0) . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $b2b_fob . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . $b2b_cost . "</font></td>";
						$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font face='Arial, Helvetica, sans-serif' size='1'>" . "<a href='#' onmouseover=\"Tip('" . $tipcount_match_str . "')\" onmouseout=\"UnTip()\" >";
						$tmpTDstr =  $tmpTDstr . $count . "/5</a></font></td>";

						$tmpTDstr =  $tmpTDstr . "</tr>";

						$mileage = (int) (6371 * $distC * .621371192);

						$MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr);
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
				echo preg_replace("/bgColorrepl/", "bgColor=$bg", $MGArraytmp2['arrdet']);

				if ($x == 0) {
					$x = 1;
					$bg = "#e4e4e4";
				} else {
					$x = 0;
					$bg = "#f4f4f4";
				}
			}
			?>
		<?php }
		?>
	</table>
</div>