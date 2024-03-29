<?php
session_start();
require("inc/header_session_client.php");
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
$thispage	= "dashboard_inv_sort.php"; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...

$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
$allowinactive	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE INACTIVE RECORDS

$addl_select_crit = "order by b2b_id"; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>

<?php
$x = 0;
if ($_REQUEST["colid"] == 1) {
	//l
	$MGArray = array();
}
if ($_REQUEST["colid"] == 2) {
	//w
	$MGArray = array();
}
if ($_REQUEST["colid"] == 3) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 4) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 5) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 6) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 7) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 8) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 9) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 10) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 11) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 12) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 13) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 14) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 15) {
	//h
	$MGArray = array();
}
if ($_REQUEST["colid"] == 16) {
	$MGArray = array();
}
if ($_REQUEST["colid"] == 17) {
	$MGArray = array();
}
if ($_REQUEST["colid"] == 18) {
	$MGArray = array();
}
if ($_REQUEST["colid"] == 19) {
	$MGArray = array();
}
if (isset($_REQUEST["g_timing"])) {
	$g_timing = $_REQUEST["g_timing"];
} else {
	$g_timing = 1;
}
if (isset($_REQUEST["sort_g_tool"])) {
	$sort_g_tool = $_REQUEST["sort_g_tool"];
} else {
	$sort_g_tool = 1;
}
if (isset($_REQUEST["sort_g_view"])) {
	$sort_g_view = $_REQUEST["sort_g_view"];
} else {
	$sort_g_view = 1;
}
$filter_tag = "";
$search_tag = "";
//
//
if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] != "")) {

	$search_tag = explode(",", $_REQUEST["search_tag"]);
	// Retrieving each selected option 
	$total_tag = count($search_tag);

	if ($total_tag >= 1) {
		$search_tag_val = "";
		foreach ($search_tag as $tag_val) {
			$search_tag_val .= " tag like '%$tag_val%' or ";
		}

		$search_tags = rtrim($search_tag_val, "or ");

		$filter_tag = " and (" . $search_tags . ")";
	}
}
if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] == "")) {
	$search_tags = "";
	$filter_tag = "";
}
//

$box_type_cnt = $_REQUEST["box_type_cnt"];
//echo $box_type_cnt;
//$box_type_cnt = 1;
$box_type_str_arr_tmp = "";
if ($box_type_cnt == 1) {
	$box_type_str_arr_tmp = "'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'";
	$box_type = "Gaylord";
}
if ($box_type_cnt == 2) {
	$box_type_str_arr_tmp = "'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'";
	$box_type = "Shipping Boxes";
}
if ($box_type_cnt == 3) {
	$box_type_str_arr_tmp = "'PalletsUCB','PalletsnonUCB'";
	$box_type = "Pallets";
}
if ($box_type_cnt == 4) {
	$box_type_str_arr_tmp = "'SupersackUCB','SupersacknonUCB'";
	$box_type = "Supersacks";
}
if ($box_type_cnt == 5) {
	$box_type_str_arr_tmp = "'DrumBarrelUCB','DrumBarrelnonUCB'";
	$box_type = "Drums/Barrels/IBCs";
}
//
$box_query = "";
if ($sort_g_tool == 1) {
	$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' " . $filter_tag . " ORDER BY inventory.availability DESC";
}
if ($sort_g_tool == 2) {
	$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' " . $filter_tag . " ORDER BY inventory.availability DESC";
}
//
//echo $box_query;
$act_inv_res = db_query($box_query);
//echo tep_db_num_rows($act_inv_res)."<br>";
if (tep_db_num_rows($act_inv_res) > 0) {
?>

<?php
	while ($inv = array_shift($act_inv_res)) {
		$b2b_ulineDollar = round($inv["ulineDollar"]);
		$b2b_ulineCents = $inv["ulineCents"];
		$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
		$minfob = $b2b_fob;
		$b2b_fob = "$" . number_format($b2b_fob, 2);

		$b2b_costDollar = round($inv["costDollar"]);
		$b2b_costCents = $inv["costCents"];
		$b2b_cost = $b2b_costDollar + $b2b_costCents;
		$b2bcost = $b2b_cost;
		$b2b_cost = "$" . number_format($b2b_cost, 2);


		//
		$b2b_notes = $inv["N"];
		$b2b_notes_date = $inv["DT"];
		//
		$bpallet_qty = 0;
		$boxes_per_trailer = 0;
		$box_type = "";
		$loop_id = 0;
		$boxgoodvalue = 0;
		$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue from loop_boxes where b2b_id=" . $inv["I"];
		//echo $qry_sku."<br>";
		$sku = "";
		$dt_view_sku = db_query($qry_sku);
		$box_wall = "";
		$inv_id_list = "";
		while ($sku_val = array_shift($dt_view_sku)) {
			$loop_id = $sku_val['id'];
			$sku = $sku_val['sku'];
			$bpallet_qty = $sku_val['bpallet_qty'];
			$boxes_per_trailer = $sku_val['boxes_per_trailer'];
			$box_type = $sku_val['type'];
			$box_wall = $sku_val['bwall'];
			$boxgoodvalue = $sku_val['boxgoodvalue'];
		}
		if ($inv["location_zip"] != "") {
			if ($inv["availability"] != "-3.5") {
				$inv_id_list .= $inv["I"] . ",";
			}
			//To get the Actual PO, After PO
			$rec_found_box = "n";
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
			$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id from loop_boxes where b2b_id=" . $inv["I"];
			$dt_view = db_query($qry_loc);
			$vendor_b2b_rescue_id = "";
			$supplier_id = "";
			$shipfrom_state = "";
			$shipfrom_city = "";
			$shipfrom_zip = "";
			while ($loc_res = array_shift($dt_view)) {
				$box_warehouse_id = $loc_res["box_warehouse_id"];
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
			//Find territory
			//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
			$territory = "";
			$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
			$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV');
			$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
			$midwest = array('MI', 'OH', 'IN', 'KY');
			$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
			$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
			$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
			$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
			$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
			$canada = array();
			$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
			//
			$territory_sort = 99;
			if (in_array($shipfrom_state, $canada_east, TRUE)) {
				$territory = "Canada East";
				$territory_sort = 1;
			} elseif (in_array($shipfrom_state, $east, TRUE)) {
				$territory = "East";
				$territory_sort = 2;
			} elseif (in_array($shipfrom_state, $south, TRUE)) {
				$territory = "South";
				$territory_sort = 3;
			} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
				$territory = "Midwest";
				$territory_sort = 4;
			} else if (in_array($shipfrom_state, $north_central, TRUE)) {
				$territory = "North Central";
				$territory_sort = 5;
			} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
				$territory = "South Central";
				$territory_sort = 6;
			} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
				$territory = "Canada West";
				$territory_sort = 7;
			} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
				$territory = " Pacific Northwest";
				$territory_sort = 8;
			} elseif (in_array($shipfrom_state, $west, TRUE)) {
				$territory = "West";
				$territory_sort = 9;
			} elseif (in_array($shipfrom_state, $canada, TRUE)) {
				$territory = "Canada";
				$territory_sort = 10;
			} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
				$territory = "Mexico";
				$territory_sort = 11;
			}
			//
			$after_po_val_tmp = 0;
			$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
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

			if ($g_timing == 2) {
				$to_show_rec = "";
				if ($after_po_val >= $boxes_per_trailer) {
					$to_show_rec = "y";
				}
			}

			if ($sort_g_tool == 2) {
				$to_show_rec = "y";
			}

			if ($to_show_rec == "y") {
				//account owner
				$ownername = "";
				if ($inv["vendor_b2b_rescue"] > 0) {

					$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
					$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
					$query = db_query($q1);
					while ($fetch = array_shift($query)) {
						$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
						$comres = db_query($comqry);
						while ($comrow = array_shift($comres)) {
							$ownername = $comrow["initials"];
						}
					}
				}
				//
				$vender_nm = "";
				$supplier_id = "";
				if ($inv["vendor_b2b_rescue"] != "") {
					$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
					$v_query = db_query($q1);
					while ($v_fetch = array_shift($v_query)) {

						$supplier_id = $v_fetch["b2bid"];
						$vender_nm = get_nickname_val($v_fetch['company_name'], $v_fetch["b2bid"]);
						//$vender_nm = $v_fetch['company_name'];
						//
						$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
						$com_row = array_shift($com_qry);
					}
				}
				//
				if ($inv["lead_time"] <= 1) {
					$lead_time = "Next Day";
				} else {
					$lead_time = $inv["lead_time"] . " Days";
				}
				$estimated_next_load = "";
				if ($after_po_val >= $boxes_per_trailer) {
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
						$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
					}
				}
				if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
					$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
				}
				//
				if ($inv["expected_loads_per_mo"] == 0) {
					$expected_loads_per_mo = "<font color=red>0</font>";
				} else {
					$expected_loads_per_mo = $inv["expected_loads_per_mo"];
				}
				//
				$b2b_status = $inv["b2b_status"];

				$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
				$st_res = db_query($st_query);
				$st_row = array_shift($st_res);
				$b2bstatus_name = $st_row["box_status"];
				$b2bstatuscolor = "";
				if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
					$b2bstatuscolor = "green";
				} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
					$b2bstatuscolor = "orange";
				}
				//
				if ($inv["box_urgent"] == 1) {
					$b2bstatuscolor = "red";
					$b2bstatus_name = "URGENT";
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
				$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
				$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
				$tipStr .= "<b>Territory:</b> " . $territory . "<br>";
				$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
				$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
				$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
				$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
				//

				//Get data in array
				if ($box_type_cnt == 1) {
					$gy[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'supplier_id' => $supplier_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
				}
				if ($box_type_cnt == 2) {
					$sb[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'supplier_id' => $supplier_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
				}
				if ($box_type_cnt == 3) {
					$pal[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
				}
				if ($box_type_cnt == 4) {
					$sup[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'supplier_id' => $supplier_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
				}
				if ($box_type_cnt == 5) {
					$dbi[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'supplier_id' => $supplier_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
				}
				//	
			} //end $to_show_rec == "y"
		} //end if ($inv["location_zip"] != "")	
		//
	} //End while $inv
	//
	//Ucbowned
	$dt_view_qry = "";
	if ($sort_g_tool == 1) {
		$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
	}
	if ($sort_g_tool == 2) {
		$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
	}
	//
	db_b2b();
	$dt_view_res = db_query($dt_view_qry);
	$tmpwarenm = "";
	$tmp_noofpallet = 0;
	$ware_house_boxdraw = "";
	$pal = array();
	$dbi = array();
	$sup = array();
	$sb = array();
	$gy = array();
	while ($dt_view_row = array_shift($dt_view_res)) {

		$b2bid_tmp = 0;
		$boxes_per_trailer_tmp = 0;
		$bpallet_qty_tmp = 0;
		$vendor_id = 0;
		$vendor_b2b_rescue_id = 0;
		$boxgoodvalue = 0;
		$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, expected_loads_per_mo, boxgoodvalue from loop_boxes where id=" . $dt_view_row["trans_id"];
		$dt_view_loopbox = db_query($qry_loopbox);
		$vendor_name = "";
		$miles_from = "";
		while ($rs_loopbox = array_shift($dt_view_loopbox)) {
			$b2bid_tmp = $rs_loopbox['b2b_id'];
			$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
			$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
			$vendor_id = $rs_loopbox['vendor'];
			$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
			$boxgoodvalue = $rs_loopbox['boxgoodvalue'];
		}

		$qry = "select vendors.name AS VN from vendors where id=" . $vendor_id;
		db_b2b();
		$dt_view = db_query($qry);
		while ($sku_val = array_shift($dt_view)) {
			$vendor_name = $sku_val["VN"];
		}

		$inv_availability = "";
		$distC = 0;
		$inv_notes = "";
		$inv_notes_dt = "";

		$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp;
		db_b2b();
		$dt_view_inv_res = db_query($inv_qry);
		$vender_name  = "";
		$supplier_id = "";
		$ownername = "";
		$estimated_next_load = "";
		$b2bstatuscolor = "";
		$bwall = "";
		$res_box_urgent = "";
		$res_contracted = "";
		$res_prepay = "";
		$res_ship_ltl = "";
		$res_lengthFraction = "";
		$res_widthFraction = "";
		$res_depthFraction = "";
		$res_lead_time = 0;
		$expected_loads_per_mo = "";
		$res_expected_loads_per_mo = 0;
		$blength = 0;
		$bwidth = 0;
		$bdepth = 0;
		$res_after_actual_inventory = "";
		while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
			$inv_notes = $dt_view_row_inv["notes"];
			$inv_notes_dt = $dt_view_row_inv["date"];
			$location_city = $dt_view_row_inv["location_city"];
			$location_state = $dt_view_row_inv["location_state"];
			$location_zip = $dt_view_row_inv["location_zip"];

			$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
			$vendor_id = $dt_view_row_inv["vendor"];
			$bwall = $dt_view_row_inv["bwall"];
			$res_box_urgent = $dt_view_row_inv["box_urgent"];
			$res_prepay = $dt_view_row_inv["prepay"];
			$res_contracted = $dt_view_row_inv["contracted"];
			$res_ship_ltl = $dt_view_row_inv["ship_ltl"];
			$res_lengthFraction = $dt_view_row_inv["lengthFraction"];
			$res_widthFraction = $dt_view_row_inv["widthFraction"];
			$res_depthFraction = $dt_view_row_inv["depthFraction"];
			$res_lead_time = $dt_view_row_inv["lead_time"];
			$res_expected_loads_per_mo = $dt_view_row_inv["expected_loads_per_mo"];
			$blength = $dt_view_row_inv["lengthInch"];
			$bwidth = $dt_view_row_inv["widthInch"];
			$bdepth = $dt_view_row_inv["depthInch"];
			$res_after_actual_inventory = $dt_view_row_inv["after_actual_inventory"];
			if ($inv["lead_time"] <= 1) {
				$lead_time = "Next Day";
			} else {
				$lead_time = $dt_view_row_inv["lead_time"] . " Days";
			}
			//
			$b2bstatus = $dt_view_row_inv['b2bstatus'];
			$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];

			//account owner
			if ($vendor_b2b_rescue > 0) {
				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
				$query = db_query($q1);
				while ($fetch = array_shift($query)) {
					$supplier_id = $fetch["b2bid"];
					$vender_name = get_nickname_val($fetch['company_name'], $fetch["b2bid"]);
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
						$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
						$comres = db_query($comqry);
						while ($comrow = array_shift($comres)) {
							$ownername = $comrow["initials"];
						}
					}
				}
			}
			//				
			$tmp_zipval = "";
			$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
			if ($tmppos_1 != false) {
				$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
				$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
			} else {
				$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
			}
			if ($dt_view_row_inv["location_zip"] != "") {
				$dt_view_res3 = db_query($zipStr);
				while ($ziploc = array_shift($dt_view_res3)) {
					$locLat = $ziploc["latitude"];

					$locLong = $ziploc["longitude"];
				}
			}
		}
		$minfob = $dt_view_row["min_fob"];
		$b2bcost = $dt_view_row["b2b_cost"];
		$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
		$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

		$sales_order_qty = $dt_view_row["sales_order_qty"];

		if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {
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
					$actual_po = $res_after_actual_inventory;
				} else {
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

				if ($sort_g_tool == 2) {
					$to_show_rec = "y";
				}
				//


				$estimated_next_load = "";
				if ($to_show_rec == "y") {

					if ($actual_po >= $boxes_per_trailer_tmp) {
						//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

						if ($res_lead_time == 0) {
							$estimated_next_load = "<font color=green>Now</font>";
						}

						if ($res_lead_time == 1) {
							$estimated_next_load = "<font color=green>" . $res_lead_time . " Day</font>";
						}
						if ($res_lead_time > 1) {
							$estimated_next_load = "<font color=green>" . $res_lead_time . " Days</font>";
						}
					} else {
						if (($res_expected_loads_per_mo <= 0) && ($actual_po < $boxes_per_trailer_tmp)) {
							$estimated_next_load = "<font color=red>Never (sell the " . $actual_po . ")</font>";
						} else {
							$estimated_next_load = ceil((((($actual_po / $boxes_per_trailer_tmp) * -1) + 1) / $res_expected_loads_per_mo) * 4) . " Weeks";
						}
					}

					if ($actual_po == 0 && $res_expected_loads_per_mo == 0) {
						$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
					}

					if ($res_expected_loads_per_mo == 0) {
						$expected_loads_per_mo = "<font color=red>0</font>";
					} else {
						$expected_loads_per_mo = $res_expected_loads_per_mo;
					}



					$blength_frac = 0;
					$bwidth_frac = 0;
					$bdepth_frac = 0;

					$length = $blength;
					$width = $bwidth;
					$depth = $bdepth;



					if ($res_lengthFraction != "") {
						$arr_length = explode("/", $res_lengthFraction);
						if (count($arr_length) > 0) {
							$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
							$length = floatval($blength + $blength_frac);
						}
					}
					if ($res_widthFraction != "") {
						$arr_width = explode("/", $res_widthFraction);
						if (count($arr_width) > 0) {
							$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
							$width = floatval($bwidth + $bwidth_frac);
						}
					}

					if ($res_depthFraction != "") {
						$arr_depth = explode("/", $res_depthFraction);
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

					if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
						$b2bstatuscolor = "green";
					} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
						$b2bstatuscolor = "orange";
					}

					if ($res_box_urgent == 1) {
						$b2bstatuscolor = "red";
						$b2bstatus_nametmp = "URGENT";
					}

					//
					$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
					$dt_view = db_query($qry_loc);
					$shipfrom_state = "";
					$shipfrom_city = "";
					$shipfrom_zip = "";
					while ($loc_res = array_shift($dt_view)) {
						if ($loc_res["box_warehouse_id"] == "238") {
							$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
							$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
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
					//Find territory
					//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
					$territory = "";
					$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
					$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV');
					$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
					$midwest = array('MI', 'OH', 'IN', 'KY');
					$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
					$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
					$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
					$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
					$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
					$canada = array();
					$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
					$territory_sort = 99;
					if (in_array($shipfrom_state, $canada_east, TRUE)) {
						$territory = "Canada East";
						$territory_sort = 1;
					} elseif (in_array($shipfrom_state, $east, TRUE)) {
						$territory = "East";
						$territory_sort = 2;
					} elseif (in_array($shipfrom_state, $south, TRUE)) {
						$territory = "South";
						$territory_sort = 3;
					} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
						$territory = "Midwest";
						$territory_sort = 4;
					} else if (in_array($shipfrom_state, $north_central, TRUE)) {
						$territory = "North Central";
						$territory_sort = 5;
					} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
						$territory = "South Central";
						$territory_sort = 6;
					} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
						$territory = "Canada West";
						$territory_sort = 7;
					} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
						$territory = " Pacific Northwest";
						$territory_sort = 8;
					} elseif (in_array($shipfrom_state, $west, TRUE)) {
						$territory = "West";
						$territory_sort = 9;
					} elseif (in_array($shipfrom_state, $canada, TRUE)) {
						$territory = "Canada";
						$territory_sort = 10;
					} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
						$territory = "Mexico";
						$territory_sort = 11;
					}
					//
					//
					$b_urgent = "No";
					$contracted = "No";
					$prepay = "No";
					$ship_ltl = "No";
					if ($res_box_urgent == 1) {
						$b_urgent = "Yes";
					}
					if ($res_contracted == 1) {
						$contracted = "Yes";
					}
					if ($res_prepay == 1) {
						$prepay = "Yes";
					}
					if ($res_ship_ltl == 1) {
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
					$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $expected_loads_per_mo . "<br>";
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
					$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
					$boxsize = explode("x", $btemp);
					//
					if ($box_type_cnt == 1) {
						$gy[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $bwall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
					}
					if ($box_type_cnt == 2) {
						$sb[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $bwall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
					}
					if ($box_type_cnt == 3) {
						$pal[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $bwall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
					}
					if ($box_type_cnt == 4) {
						$sup[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $bwall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
					}
					if ($box_type_cnt == 5) {
						$dbi[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $bwall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'supplier_id' => $supplier_id, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
					}
					//
					//$pallet_space_per = "";

					//----------------------------------------------------------------
				} //end if ($to_show_rec == "y")
			} //End if ($to_show_rec1 == "y")	

		} //if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 )
	} //while ($dt_view_row
	//
	$_SESSION['sortarraygy'] = $gy;
	$_SESSION['sortarraysb'] = $sb;
	$_SESSION['sortarraysup'] = $sup;
	$_SESSION['sortarraydbi'] = $dbi;
	$_SESSION['sortarraypal'] = $pal;
} //End check num rows>0
//
?>
<?php
$x = 0;
$boxtype_cnt = 0;
$sorturl = "dashboardnew.php?show=inventory_new&sort_g_view=" . $sort_g_view . "&sort_g_tool=" . $sort_g_tool . "&g_timing=" . $g_timing;
$box_name_arr = array('gy', 'sb', 'pal', 'sup', 'dbi');
//foreach($box_name_arr as $box_name){
//
$box_name = "";
if ($box_type_cnt == 1) {
	$box_name = "gy";
	$boxtype = "Gaylord";
	$boxtype_cnt = 1;
}
if ($box_type_cnt == 2) {
	$box_name = "sb";
	$boxtype = "Shipping Boxes";
	$boxtype_cnt = 2;
}
if ($box_type_cnt == 3) {
	$box_name = "pal";
	$boxtype = "Pallets";
	$boxtype_cnt = 3;
}
if ($box_type_cnt == 4) {
	$box_name = "sup";
	$boxtype = "Supersacks";
	$boxtype_cnt = 4;
}
if ($box_type_cnt == 5) {
	$box_name = "dbi";
	$boxtype = "Drums/Barrels/IBCs";
	$boxtype_cnt = 5;
}
//
//
$MGArray = $_SESSION['sortarray' . $box_name];
$MGArraysort = array();
//
if ($_REQUEST["colid"] == 1) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['after_po_val'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 2) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['estimated_next_load'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 3) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['expected_loads_per_mo'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 4) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['boxes_per_trailer'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}

if ($_REQUEST["colid"] == 5) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['minfob'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 6) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['b2bid'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 7) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['territory'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 8) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['b2bstatus_name'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 9) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['length'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 10) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['width'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 11) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['depth'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 12) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['box_wall'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
if ($_REQUEST["colid"] == 13) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['description'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 14) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['vendor_nm'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 15) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['ship_from'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 16) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['ownername'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 17) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['b2b_notes'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 18) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['b2b_notes_date'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
	}
}
if ($_REQUEST["colid"] == 19) {
	foreach ($MGArray as $MGArraytmp) {
		$MGArraysort[] = $MGArraytmp['boxgoodvalue'];
	}
	if ($_REQUEST["sortflg"] == 1) {
		array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
	} else {
		array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
	}
}
//
?>
<table width="100%" cellspacing="1" cellpadding="2">
	<?php if ($sort_g_view == "1") { ?>
		<tr class="headrow">
			<td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title' width="60px">Ask Purch Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>x</td>

			<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>x</td>

			<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,2,<?php echo $box_type_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
		</tr>
	<?php
	}
	if ($sort_g_view == "2") {
	?>
		<tr class="headrow">
			<td class='display_title'>Qty Avail<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title' width="60px">Ask Purch Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>FOB Origin Price/Unit&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>x</td>

			<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>x</td>

			<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

			<td class='display_title'>Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
		</tr>

	<?php
	}
	?>
	<?php
	$count_arry = 0;
	$count = 0;
	//echo "<pre>"; print_r($MGarray); echo "</pre>";
	$totQty = 0;
	$totEL = 0;
	$row_cnt = 0;
	foreach ($MGArray as $MGArraytmp2) {
		$count = $count + 1;
		$binv = "";
		if ($MGArraytmp2["binv"] == "nonucb") {
			$binv = "";
		}
		if ($MGArraytmp2["binv"] == "ucbown") {
			$binv = "<b>UCB Owned Inventory </b><br>";
		}
		$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
		if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
			$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
		} else {
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
		if ($row_cnt == 0) {
			$display_table_css = "display_table";
			$row_cnt = 1;
		} else {
			$row_cnt = 0;
			$display_table_css = "display_table_alt";
		}
		//
		$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
		$vendornme = $MGArraytmp2["vendor_nm"];
		//
		$sales_order_qty = 0;
		if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
			$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
			$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
			$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
			$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";

			$dt_res_so_item = db_query($dt_so_item);
			while ($so_item_row = array_shift($dt_res_so_item)) {
				if ($so_item_row["sumqty"] > 0) {
					$sales_order_qty = $so_item_row["sumqty"];
				}
			}
		}
		//
		$tmpTDstr = "";
		if ($sort_g_view == "1") {
			$tmpTDstr = "<tr  >";

			$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
			if ($MGArraytmp2["after_po_val"] < 0) {

				$tmpTDstr =  $tmpTDstr . "<div ";
				if ($sales_order_qty > 0) {
					$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
				}
				$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
			} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
				$tmpTDstr =  $tmpTDstr . "<div";
				if ($sales_order_qty > 0) {
					$tmpTDstr =  $tmpTDstr . "onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
				}
				$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
			} else {
				$tmpTDstr =  $tmpTDstr . "<div ";
				if ($sales_order_qty > 0) {
					$tmpTDstr =  $tmpTDstr . "onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
				}
				$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
			}
			//
			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

			$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

			$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
			//
			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
			$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

			//echo " >" ;
			$tmpTDstr =  $tmpTDstr . " >";

			$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
			if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
				$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
			}
			$tmpTDstr =  $tmpTDstr . "</td>";

			$tmpTDstr =  $tmpTDstr . "</tr>";
			//
			$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_" . $count . "' align='middle' style='display:none;'>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  <td colspan='16'>
					<div id='inventory_preord_middle_div_" . $count . "'></div>		
			  </td></tr>";
		}
		if ($sort_g_view == "2") {
			$tmpTDstr = "<tr  >";

			$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
			if ($MGArraytmp2["after_po_val"] < 0) {
				$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
			} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
				$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
			} else {
				$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
			}
			//
			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

			$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

			$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

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
		$totQty1 = array_sum(array_column($MGArray, 'after_po_val'));
		$totQty = $totQty1;
		$totEL = array_sum(array_column($MGArray, 'expected_loads_per_mo'));
	}
	?>
	<tr>
		<td><?php echo number_format($totQty, 2); ?></td>
		<td>&nbsp;</td>
		<td><?php echo number_format($totEL, 2); ?></td>
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
<?php
//}
?>