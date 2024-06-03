<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
function encrypt_password($txt)
{
	$key = '1sw54@$sa$offj';
	$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
	return $ciphertext;
}
function get_loop_box_id($b2b_id)
{
	$dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
	$dt_res_so = db_query($dt_so, db());

	while ($so_row = array_shift($dt_res_so)) {
		if ($so_row["id"] > 0)
			return $so_row["id"];
	}
}

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
//echo "<br /> buyer_seller_flg - ".$buyer_seller_flg;
$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 6 and activate_deactivate = 1", db());
$close_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
	$close_inv_flg = 1;
}

$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 7 and activate_deactivate = 1", db());
$setup_hide_flg = 0;
while ($fetch_data = array_shift($res)) {
	$setup_hide_flg = 1;
}


$res = db_query("Select activate_deactivate from boomerang_user_section_details where user_id = $user_id and section_id = 8 and activate_deactivate = 1", db());
$setup_boxprofile_inv_flg = 0;
while ($fetch_data = array_shift($res)) {
	$setup_boxprofile_inv_flg = 1;
}


?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Boomerang Portal Setup</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		.tooltip {
			position: relative;
			display: inline-block;
		}

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}

		.fa {
			display: inline-block;
			font: normal normal normal 14px/1 FontAwesome;
			font-size: inherit;
			text-rendering: auto;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}

		.tooltip .tooltiptext {
			visibility: hidden;
			width: 250px;
			background-color: #464646;
			color: #fff;
			text-align: left;
			border-radius: 6px;
			padding: 5px 7px;
			position: absolute;
			z-index: 1;
			top: -5px;
			left: 110%;
			/* white-space: nowrap; */
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		}

		.tooltip .tooltiptext::after {
			content: "";
			position: absolute;
			top: 35%;
			right: 100%;
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent black transparent transparent;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
		}

		.tooltip_large {
			position: relative;
			display: inline-block;
		}

		.tooltip_large .tooltiptext_large {
			visibility: hidden;
			width: 400px;
			background-color: #464646;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 7px;
			position: absolute;
			z-index: 1;
			top: -5px;
			left: 110%;
		}

		.tooltip_large .tooltiptext_large::after {
			content: "";
			position: absolute;
			top: 10%;
			right: 100%;
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent black transparent transparent;
		}

		.tooltip_large:hover .tooltiptext_large {
			visibility: visible;
		}

		/*right tip*/

		.tooltip_right {
			position: relative;
			display: inline-block;
		}

		.tooltip_right .tooltiptext_right {
			visibility: hidden;
			width: 250px;
			background-color: black;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 7px;
			position: absolute;
			z-index: 1;
			top: -5px;
			right: 110%;
			font-size: 11px;
		}

		.tooltip_right .tooltiptext_right::after {
			content: " ";
			position: absolute;
			top: 30%;
			left: 100%;
			/* To the right of the tooltip */
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent transparent transparent black;
		}

		.tooltip_right:hover .tooltiptext_right {
			visibility: visible;
		}

		/*--------*/

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}

		.white_content {
			display: none;
			position: absolute;
			padding: 5px;
			border: 2px solid black;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}

		.textbox-label {
			background: transperant;
			border: none;
			width: 300px;
			min-width: 90px;
			max-width: 300px;
			transition: width 0.25s;
		}

		.color_red {
			color: red;
		}

		.hide_error {
			display: none;
		}

		.table_boomerang_portal {
			width: 85%;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12;
			border: none;
			background-color: #F6F8E5;
			margin: 0px auto;
		}

		.align_center {
			text-align: center;
		}

		.bg_C1C1C1 {
			background-color: #C1C1C1;
		}

		.tbl_border,
		.tbl_border td,
		.tbl_border tr {
			border: solid 1px #C8C8C8;
			border-collapse: collapse;
		}
	</style>
	<LINK rel='stylesheet' type='text/css' href='one_style.css'>
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<? include("inc/header.php"); ?>
<div class="main_data_css">
	<div style="height: 13px;">&nbsp;</div>
	<select multiple name="companies[]" id="all_companies" style="display:none; ">
		<option>Select Companies</option>
		<?php
		$select_sales_comp = db_query("SELECT ID, nickname FROM companyInfo where haveNeed = 'Have Boxes' && company!='' ORDER BY company", db_b2b());
		while ($row = array_shift($select_sales_comp)) {
			echo "<option value='" . $row['ID'] . "'>" . $row['nickname'] . "</option>";
		}
		?>
	</select>
	<div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
		<img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;
		<!--<a href="viewCompany.php?ID=<?= $ID ?>">View B2B page</a> &nbsp;&nbsp;
			<a target="_blank" href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?= $ID ?>&repchk=yes">Old Client dash</a> &nbsp;&nbsp; -->
		<a target="_blank" href="https://www.ucbdata.com/ucbloop/boomerang_business/client_dashboard_new.php">View Boomerang Portal</a>
		<span class="color_red">*Do NOT give this link out to customers! It is a "back door" to the portal ONLY FOR YOU!</span>
	</div>
	<div id="light" class="white_content"> </div>
	<table class="table_boomerang_portal">
		<tr>
			<td colspan="6" style="background:#E8EEA8; text-align:center"><strong>Boomerang Portal User Setup</strong></font>
			</td>
		</tr>
		<tr>
			<td colspan="6" class="bg_C1C1C1 align_center">User Profile</td>
		</tr>
		<tr>
			<td colspan="6">
				<table class="tbl_border" style="width: 100%">
					<tr>
						<td>Name</td>
						<td>Email</td>
						<td>Company</td>
						<td>Address</td>
						<td>Status</td>
						<td>Blocked</td>
						<td>Action</td>
					</tr>
					<?php
					db();
					$select_users = db_query("SELECT * FROM boomerang_usermaster where loginid = '" . $user_id . "'");
					$row = array_shift($select_users);
					$select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $user_id . "'", db());
					$company_list = "";
					if (tep_db_num_rows($select_user_companies) > 0) {
						while ($row1 = array_shift($select_user_companies)) {
							db_b2b();
							$company_name = db_query("SELECT nickname FROM companyInfo where ID = '" . $row1['company_id'] . "'");
							$company_name = array_shift($company_name);
							$company_list .= $company_name['nickname'] . "<br>";
						}
					}
					
					echo "<tr id='userrowid_" . $row['loginid'] . "'>
									<td>" . $row['user_name'] . "</td>
									<td>" . $row['user_email'] . "</td>
									<td>" . $company_list . "</td>
									<td>" . ($row['activate_deactivate'] == 1 ? 'Active' : 'Deactive') . "</td>
									<td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
									<td>
										<button type='button' user_id='" . $row['loginid'] . "' class='edit_user'>Edit</button>
										<button type='button' user_id='" . $row['loginid'] . "' class='delete_user'>Delete</button>
									</td>	
									</tr>";
					?>
				</table>
				<table class="tbl_border" style="width: 100%">
				<tr>
					<td colspan="11" style="background: #FFF">&nbsp;</td>
					</tr>
					<tr>
					<td colspan="11" class="bg_C1C1C1 align_center">User Address</td>
					</tr>
					<tr>
						<td>Name</td>
						<td>Company</td>
						<td>Country</td>
						<td>Address Line 1</td>
						<td>Address Line 2</td>
						<td>City</td>
						<td>State</td>
						<td>Zip</td>
						<td>Mobile No</td>
						<td>Email </td>
						<td>Dock Hours</td>
					</tr>
					<?php
					db();
					$select_user_address = db_query("SELECT * FROM boomerang_user_addresses where user_id = '" . $user_id . "' and status = 1", db());
					if (tep_db_num_rows($select_user_address) > 0) {
						while ($row = array_shift($select_user_address)) {
							echo "<tr id='userrowid_" . $row['id'] . "'>
									<td>" . $row['first_name'] ." ".$row['last_name']."</td>
									<td>" . $row['company'] . "</td>
									<td>" . $row['country'] . "</td>	
									<td>" . $row['addressline1'] . "</td>	
									<td>" . $row['addressline2'] . "</td>	
									<td>" . $row['city'] . "</td>	
									<td>" . $row['state'] . "</td>	
									<td>" . $row['zip'] . "</td>	
									<td>" . $row['mobile_no'] . "</td>	
									<td>" . $row['email'] . "</td>
									<td>" . $row['dock_hours'] . "</td>
									</tr>";
						}
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="6" style="background:while;"></td>
		</tr>
		<!--Favorite Inventory section start  -->
		<?
		if (isset($_REQUEST['favremoveflg']) && $_REQUEST['favremoveflg'] == "yes") {

			db_query("Delete from boomerange_inventory_gaylords_favorite WHERE id =" . $_REQUEST['favitemid'], db());
		}
		?>
		<tr align="center">
			<td colspan="6" width="320px" align="center" bgcolor="#C1C1C1"><b>Favorite Inventory</b></td>
		</tr>
		<?php
		db();
		$select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $user_id . "'");
		$company_list = "";
		if (tep_db_num_rows($select_user_companies) > 0) {
			$company_id_array = array();
			while ($row1 = array_shift($select_user_companies)) {
				$company_id_array[] = $row1['company_id'];
			}
			$company_ids = implode(",", $company_id_array);
			db_b2b();
			$company_data_qry = db_query("SELECT * FROM companyInfo where ID IN ($company_ids)");
			$dt_view_res = array_shift($company_data_qry);
			$company_id = $dt_view_res['ID'];

			$shipLat = 0;
			$shipLong = 0;
			while ($row = array_shift($dt_view_res)) {
				if (($row["shipZip"]) != "") {
					$tmp_zipval = "";
					$tmp_zipval = str_replace(" ", "", $row["shipZip"]);
					if ($row["shipcountry"] == "Canada") {
						$zipShipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
					} elseif (($row["shipcountry"]) == "Mexico") {
						$zipShipStr = "Select * from zipcodes_mexico limit 1";
					} else {
						$zipShipStr = "Select * from ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
					}

					$dt_view_res = db_query($zipShipStr, db_b2b());
					while ($zip = array_shift($dt_view_res)) {
						$shipLat = $zip["latitude"];
						$shipLong = $zip["longitude"];
					}
				}
			}
		?>
			<tr>
				<td colspan="6">
					<form name="frmFavItems" method="post" action="boomerange_users_profile.php?user_id=<?php echo $user_id; ?>">
						<table width="100%" cellspacing="1" cellpadding="1" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
							<?
							$selFavData = db_query("SELECT * FROM boomerange_inventory_gaylords_favorite WHERE user_id = '" . $user_id . "' and fav_status = 1 ORDER BY id DESC", db());
							//echo "<pre>";print_r($selFavData);echo "</pre>";
							$selFavDataCnt = tep_db_num_rows($selFavData);
							if ($selFavDataCnt > 0) {
							?>
								<tr bgcolor="#E4D5D5">
									<td class='display_title'>&nbsp;</td>
									<td class='display_title'>Buy Now</td>
									<td class='display_title'>Qty Avail</td>
									<td class='display_title'>Buy Now, Load Can Ship In</td>
									<td class='display_title'>Expected # of Loads/Mo</td>
									<td class='display_title'>Per Truckload</td>
									<td class='display_title'>MIN FOB</td>
									<td class='display_title'>B2B ID</td>
									<td class='display_title'>Miles Away
										<div class="tooltip">
											<i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles away <= 550 and> 250,
														<br>Red Color - miles away > 550</span>
										</div>
									</td>
									<td align="center" class='display_title'>B2B Status</td>
									<td align="center" class='display_title'>L</td>
									<td align="center" class='display_title'>x</td>
									<td align="center" class='display_title'>W</td>
									<td align="center" class='display_title'>x</td>
									<td align="center" class='display_title'>H</td>
									<td class='display_title'>Description</td>
									<td class='display_title'>Supplier</td>
									<td class='display_title'>Ship From</td>
									<td class='display_title'>Supplier Relationship Owner</td>
									<td class=''>Box Type</td>
								</tr>
								<?
								$i = 0;
								while ($rowsFavData = array_shift($selFavData)) {
									$after_po_val_tmp = 0;
									$after_po_val = 0;
									$pallet_val_afterpo = $preorder_txt2 = "";
									$rec_found_box = "n";
									$boxes_per_trailer = 0;
									$next_load_available_date = "";
									if ($rowsFavData['fav_b2bid'] > 0) {
										$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavData['fav_b2bid'], db());
										//echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
										$rowBoxDt = array_shift($selBoxDt);

										if ($rowBoxDt['b2b_id'] > 0) {
											$selInvDt = db_query("SELECT * FROM inventory WHERE ID = " . $rowBoxDt['b2b_id'], db_b2b());
											$rowInvDt = array_shift($selInvDt);

											$box_type = $rowInvDt['box_type'];
											$box_warehouse_id = $rowBoxDt["box_warehouse_id"];
											$next_load_available_date = $rowBoxDt["next_load_available_date"];
											$boxes_per_trailer = $rowBoxDt['boxes_per_trailer'];
											if ($rowInvDt["loops_id"] > 0) {
												$dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
												$dt_view_res_box = db_query($dt_view_qry, db_b2b());
												while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
													$rec_found_box = "y";
													$actual_val = $dt_view_res_box_data["actual"];
													$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
													$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
												}
												if ($rec_found_box == "n") {
													$actual_val = $rowInvDt["actual_inventory"];
													$after_po_val = $rowInvDt["after_actual_inventory"];
													$last_month_qty = $rowInvDt["lastmonthqty"];
												}
												if ($box_warehouse_id == 238) {
													$after_po_val = $rowInvDt["after_actual_inventory"];
												} else {
													$after_po_val = $after_po_val_tmp;
												}

												$to_show_rec = "y";

												$ownername = "";
												if ($to_show_rec == "y") {
													$vendor_name = "";
													//account owner
													if ($rowInvDt["vendor_b2b_rescue"] > 0) {

														$vendor_b2b_rescue = $rowInvDt["vendor_b2b_rescue"];
														$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
														$query = db_query($q1, db());
														while ($fetch = array_shift($query)) {
															$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

															$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
															$comres = db_query($comqry, db_b2b());
															while ($comrow = array_shift($comres)) {
																$ownername = $comrow["initials"];
															}
														}
													} else {
														$vendor_b2b_rescue = $rowInvDt["V"];
														if ($vendor_b2b_rescue != "") {
															$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
															$query = db_query($q1, db_b2b());
															while ($fetch = array_shift($query)) {
																$vendor_name = $fetch["Name"];

																$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
																$comres = db_query($comqry, db_b2b());
																while ($comrow = array_shift($comres)) {
																	$ownername = $comrow["initials"];
																}
															}
														}
													}
												}


												if ($after_po_val < 0) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else if ($after_po_val >= $boxes_per_trailer) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												}


												$estimated_next_load = "";
												$b2bstatuscolor = "";
												if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
													$now_date = time(); // or your date as well
													$next_load_date = strtotime($next_load_available_date);
													$datediff = $next_load_date - $now_date;
													$no_of_loaddays = round($datediff / (60 * 60 * 24));
													//echo $no_of_loaddays;
													if ($no_of_loaddays < $lead_time) {
														if ($rowInvDt["lead_time"] > 1) {
															$estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
														} else {
															$estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
														}
													} else {
														$estimated_next_load = "<font color=green> " . $no_of_loaddays . " Days </font>";
													}
												} else {
													if ($after_po_val >= $boxes_per_trailer) {
														if ($rowInvDt["lead_time"] == 0) {
															$estimated_next_load = "<font color=green> Now </font>";
														}
														if ($rowInvDt["lead_time"] == 1) {
															$estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
														}
														if ($rowInvDt["lead_time"] > 1) {
															$estimated_next_load = "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
														}
													} else {
														if (($rowInvDt["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
															$estimated_next_load = "<font color=red> Never (sell the " . $after_po_val . ") </font>";
														} else {
															// logic changed by Zac
															//$estimated_next_load=round((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." weeks";;
															//echo "next_load_available_date - " . $inv["I"] . " " . $after_po_val . " " . $boxes_per_trailer . " " . $inv["expected_loads_per_mo"] .  "<br>";
															$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $rowInvDt["expected_loads_per_mo"]) * 4) . " Weeks";
														}
													}

													if ($after_po_val == 0 && $rowInvDt["expected_loads_per_mo"] == 0) {
														$estimated_next_load = "<font color=red> Ask Purch Rep </font>";
													}

													if ($rowInvDt["expected_loads_per_mo"] == 0) {
														$expected_loads_per_mo = "<font color=red>0</font>";
													} else {
														$expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
													}
												}

												$b2b_status = $rowInvDt["b2b_status"];
												$b2bstatuscolor = "";
												$st_query = "SELECT * FROM b2b_box_status WHERE status_key='" . $b2b_status . "'";
												$st_res = db_query($st_query, db());
												$st_row = array_shift($st_res);
												$b2bstatus_name = $st_row["box_status"];
												if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
													$b2bstatuscolor = "green";
												} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
													$b2bstatuscolor = "orange";
													$estimated_next_load = "<font color=red> Ask Purch Rep </font>";
												}

												$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];

												$b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
												$b2b_ulineCents = $rowInvDt["ulineCents"];
												$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
												$b2b_fob = "$" . number_format($b2b_fob, 2);

												if ($rowInvDt["location_country"] == "Canada") {
													$tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
													$zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
												} elseif (($rowInvDt["location_country"]) == "Mexico") {
													$zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
												} else {
													$zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
												}

												$dt_view_res3 = db_query($zipStr, db_b2b());
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

												$miles_from = (int) (6371 * $distC * .621371192);
												if ($miles_from <= 250) {	//echo "chk gr <br/>";
													$miles_away_color = "green";
												}
												if (($miles_from <= 550) && ($miles_from > 250)) {
													$miles_away_color = "#FF9933";
												}
												if (($miles_from > 550)) {
													$miles_away_color = "red";
												}


												if ($rowInvDt["uniform_mixed_load"] == "Mixed") {
													$blength = $rowInvDt["blength_min"] . " - " . $rowInvDt["blength_max"];
													$bwidth = $rowInvDt["bwidth_min"] . " - " . $rowInvDt["bwidth_max"];
													$bdepth = $rowInvDt["bheight_min"] . " - " . $rowInvDt["bheight_max"];
												} else {
													$blength = $rowInvDt["lengthInch"];
													$bwidth = $rowInvDt["widthInch"];
													$bdepth = $rowInvDt["depthInch"];
												}
												$blength_frac = 0;
												$bwidth_frac = 0;
												$bdepth_frac = 0;
												$length = $blength;
												$width = $bwidth;
												$depth = $bdepth;
												if ($rowInvDt["lengthFraction"] != "") {
													$arr_length = explode("/", $rowInvDt["lengthFraction"]);
													if (count($arr_length) > 0) {
														$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
														$length = floatval($blength + $blength_frac);
													}
												}
												if ($rowInvDt["widthFraction"] != "") {
													$arr_width = explode("/", $rowInvDt["widthFraction"]);
													if (count($arr_width) > 0) {
														$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
														$width = floatval($bwidth + $bwidth_frac);
													}
												}
												if ($rowInvDt["depthFraction"] != "") {
													$arr_depth = explode("/", $rowInvDt["depthFraction"]);
													if (count($arr_depth) > 0) {
														$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
														$depth = floatval($bdepth + $bdepth_frac);
													}
												}


												if ($rowBoxDt["box_warehouse_id"] == "238") {
													$vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
													$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
													$get_loc_res = db_query($get_loc_qry, db_b2b());
													$loc_row = array_shift($get_loc_res);
													$shipfrom_city = $loc_row["shipCity"];
													$shipfrom_state = $loc_row["shipState"];
													$shipfrom_zip = $loc_row["shipZip"];
												} else {
													$vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];
													$get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='" . $vendor_b2b_rescue_id . "'";
													$get_loc_res = db_query($get_loc_qry, db());
													$loc_row = array_shift($get_loc_res);
													$shipfrom_city = $loc_row["company_city"];
													$shipfrom_state = $loc_row["company_state"];
													$shipfrom_zip = $loc_row["company_zip"];
												}
												$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
												$ship_from2 = $shipfrom_state;


												if ($i % 2 == 0) {
													$rowclr = 'rowalt2';
												} else {
													$rowclr = 'rowalt1';
												}

								?>
												<tr class="<?= $rowclr ?>">

													<td class=''>
														<? if ($rowsFavData['fav_status'] == 1) { ?><input type="button" name="favItemIds" id="favItemIds<?= $rowsFavData['id']; ?>" value="Remove" onclick="favitem_remove(<?= $rowsFavData['id']; ?>, <?php echo $user_id; ?>)"> <? } ?>
													</td>
													<td class='' width="5%"><a href='https://b2b.usedcardboardboxes.com/?id=<?php echo urlencode(encrypt_password(get_loop_box_id($rowBoxDt['b2b_id']))); ?>&compnewid=<? echo urlencode(encrypt_password($company_id)); ?>' target='_blank'>Buy Now</a></td>
													<td class='' width="5%"><?= $qty ?></td>
													<td class='' width="8%"><?= $estimated_next_load ?></td>
													<td class='' width="5%"><?= $expected_loads_per_mo ?></td>
													<td class='' width="5%"><?= $boxes_per_trailer ?></td>
													<td class='' width="3%"><?= $b2b_fob ?></td>
													<td class='' width="5%"><?= $rowInvDt['ID'] ?></td>
													<td class='' width="5%">
														<font color='<?= $miles_away_color; ?>'><?= $miles_from ?></font>
													</td>
													<td align="center" class='display_title'>
														<font color="<?= $b2bstatuscolor; ?>"><?= $b2bstatus_name ?></font>
													</td>

													<td align="center" class=''><?= $length ?></td>
													<td align="center" class=''>x</td>
													<td align="center" class=''><?= $width ?></td>
													<td align="center" class=''>x</td>
													<td align="center" class=''><?= $depth ?></td>
													<td class='' width="20%">
														<a href='https://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?= $rowBoxDt["id"] ?>&proc=View' target='_blank'>
															<?= $rowInvDt["description"] ?></a>
													</td>
													<td class='' width="5%"><?= $vendor_name; ?></td>
													<td class='' width="5%"><?= $ship_from ?></td>
													<td class=''><?= $ownername; ?></td>
													<td class='' width="7%">
														<?
														$arrG = array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord");
														$arrSb = array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold");
														$arrSup = array("SupersackUCB", "SupersacknonUCB");
														$arrPal = array("PalletsUCB", "PalletsnonUCB");
														$arrOther = array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " ");
														if (in_array($box_type, $arrG)) {
															$boxtype		= "Gaylord";
														} elseif (in_array($box_type, $arrSb)) {
															$boxtype		= "Shipping";
														} elseif (in_array($box_type, $arrSup)) {
															$boxtype		= "SuperSack";
														} elseif (in_array($box_type, $arrPal)) {
															$boxtype		= "Pallet";
														} elseif (in_array($box_type, $arrOther)) {
															$boxtype		= "Other";
														}
														echo $boxtype;
														?>
													</td>
												</tr>
								<?
											}
										}
									}
									$i++;
								}
							} else {
								?>
								<tr>
									<td colspan="18">No record found</td>
								</tr>
							<?
							}
							?>
							<tr>
								<td colspan="18">
									<input type="hidden" name="hdnFavItemsAction" value="1">
									<input type="button" name="btnAddFavoriteInv" id="btnAddFavoriteInv" value="Add new favorite inventory" style="cursor:pointer;" onclick="add_inventory_to_favorite(<?php echo $user_id; ?>)">
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		<?php
		}
		?>
		<!--Favorite Inventory section ends  -->
		<tr>
			<td colspan="6" style="background:while;"></td>
		</tr>
		<!-- Hide Inventory Section start -->
		<?
		if (isset($_REQUEST['hideremoveflg']) && $_REQUEST['hideremoveflg'] == "yes") {

			db_query("Delete from boomerang_inventory_hide_items WHERE id =" . $_REQUEST['hideitemid'], db());
		}
		?>
		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="6" width="320px" align="center" bgcolor="#C1C1C1"><b>Hide Inventory</b></td>
		</tr>

		<td colspan="6">
			<form name="frmhideItems" method="post" action="boomerang_users_profile.php?user_id=<?= $user_id;?>">
				<table width="100%" cellspacing="1" cellpadding="1" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
					<?
					db();
					
							$selhideData = db_query("SELECT * FROM boomerang_inventory_hide_items WHERE hideItems = 1 and user_id =" . $user_id . " ORDER BY id DESC", db());
							$selhideDataCnt = tep_db_num_rows($selhideData);
							if ($selhideDataCnt > 0) {

					?>
								<tr bgcolor="#E4D5D5">
									<td class='display_title'>&nbsp;</td>
									<td class='display_title'>Qty Avail</td>
									<td class='display_title'>Buy Now, Load Can Ship In</td>
									<td class='display_title'>Expected # of Loads/Mo</td>
									<td class='display_title'>Per Truckload</td>
									<td class='display_title'>MIN FOB</td>
									<td class='display_title'>B2B ID</td>
									<td class='display_title'>Miles Away
										<div class="tooltip">
											<i class="fa fa-info-circle" aria-hidden="true"></i>
											<span class="tooltiptext">Green Color - miles away <= 250, <br>Orange Color - miles away <= 550 and> 250,
														<br>Red Color - miles away > 550</span>
										</div>
									</td>
									<td align="center" class='display_title'>B2B Status</td>
									<td align="center" class='display_title'>L</td>
									<td align="center" class='display_title'>x</td>
									<td align="center" class='display_title'>W</td>
									<td align="center" class='display_title'>x</td>
									<td align="center" class='display_title'>H</td>
									<td class='display_title'>Description</td>
									<td class='display_title'>Supplier</td>
									<td class='display_title'>Ship From</td>
									<td class='display_title'>Supplier Relationship Owner</td>
									<td class=''>Box Type</td>
								</tr>
								<?

								$i = 0;
								while ($rowsFavData = array_shift($selhideData)) {
									//echo "<pre> rowsFavData ->";print_r($rowsFavData);echo "</pre>";
									$after_po_val_tmp = 0;
									$after_po_val = 0;
									$pallet_val_afterpo = $preorder_txt2 = "";
									$rec_found_box = "n";
									$boxes_per_trailer = 0;
									$next_load_available_date = "";
									if ($rowsFavData['hide_b2bid'] > 0) {
										$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = " . $rowsFavData['hide_b2bid'], db());
										//echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
										$rowBoxDt = array_shift($selBoxDt);

										if ($rowBoxDt['b2b_id'] > 0) {
											$selInvDt = db_query("SELECT * FROM inventory WHERE ID = " . $rowBoxDt['b2b_id'], db_b2b());
											$rowInvDt = array_shift($selInvDt);

											$box_type = $rowInvDt['box_type'];
											$box_warehouse_id = $rowBoxDt["box_warehouse_id"];
											$next_load_available_date = $rowBoxDt["next_load_available_date"];
											$boxes_per_trailer = $rowBoxDt['boxes_per_trailer'];
											if ($rowInvDt["loops_id"] > 0) {
												$dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
												$dt_view_res_box = db_query($dt_view_qry, db_b2b());
												while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
													$rec_found_box = "y";
													$actual_val = $dt_view_res_box_data["actual"];
													$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
													$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
												}
												if ($rec_found_box == "n") {
													$actual_val = $rowInvDt["actual_inventory"];
													$after_po_val = $rowInvDt["after_actual_inventory"];
													$last_month_qty = $rowInvDt["lastmonthqty"];
												}
												if ($box_warehouse_id == 238) {
													$after_po_val = $rowInvDt["after_actual_inventory"];
												} else {
													$after_po_val = $after_po_val_tmp;
												}


												$vendor_name = "";
												//account owner
												if ($rowInvDt["vendor_b2b_rescue"] > 0) {

													$vendor_b2b_rescue = $rowInvDt["vendor_b2b_rescue"];
													$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
													$query = db_query($q1, db());
													while ($fetch = array_shift($query)) {
														$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

														$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
														$comres = db_query($comqry, db_b2b());
														while ($comrow = array_shift($comres)) {
															$ownername = $comrow["initials"];
														}
													}
												} else {
													$vendor_b2b_rescue = $rowInvDt["V"];
													if ($vendor_b2b_rescue != "") {
														$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
														$query = db_query($q1, db_b2b());
														while ($fetch = array_shift($query)) {
															$vendor_name = $fetch["Name"];

															$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
															$comres = db_query($comqry, db_b2b());
															while ($comrow = array_shift($comres)) {
																$ownername = $comrow["initials"];
															}
														}
													}
												}



												if ($after_po_val < 0) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else if ($after_po_val >= $boxes_per_trailer) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												}


												$estimated_next_load = "";
												$b2bstatuscolor = "";
												if ($rowInvDt["expected_loads_per_mo"] == 0) {
													$expected_loads_per_mo = "<font color=red>0</font>";
												} else {
													$expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
												}


												$b2b_status = $rowInvDt["b2b_status"];
												$b2bstatuscolor = "";
												$st_query = "SELECT * FROM b2b_box_status WHERE status_key='" . $b2b_status . "'";
												$st_res = db_query($st_query, db());
												$st_row = array_shift($st_res);
												$b2bstatus_name = $st_row["box_status"];
												if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
													$b2bstatuscolor = "green";
												} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
													$b2bstatuscolor = "orange";
													//$estimated_next_load= "<font color=red>Ask Rep</font>";
												}
												if (
													$rowInvDt["buy_now_load_can_ship_in"] == '<font color=red>Ask Purch Rep</font>'
													|| $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red> Ask Purch Rep </font>'
												) {
													$estimated_next_load = '<font color=red>Ask Rep</font>';
												} else {
													$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
												}
												$b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
												$b2b_ulineCents = $rowInvDt["ulineCents"];
												$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
												$b2b_fob = "$" . number_format($b2b_fob, 2);

												if ($rowInvDt["location_country"] == "Canada") {
													$tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
													$zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
												} elseif (($rowInvDt["location_country"]) == "Mexico") {
													$zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
												} else {
													$zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
												}

												$dt_view_res3 = db_query($zipStr, db_b2b());
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

												$miles_from = (int) (6371 * $distC * .621371192);
												if ($miles_from <= 250) {	//echo "chk gr <br/>";
													$miles_away_color = "green";
												}
												if (($miles_from <= 550) && ($miles_from > 250)) {
													$miles_away_color = "#FF9933";
												}
												if (($miles_from > 550)) {
													$miles_away_color = "red";
												}


												if ($rowInvDt["uniform_mixed_load"] == "Mixed") {
													$blength = $rowInvDt["blength_min"] . " - " . $rowInvDt["blength_max"];
													$bwidth = $rowInvDt["bwidth_min"] . " - " . $rowInvDt["bwidth_max"];
													$bdepth = $rowInvDt["bheight_min"] . " - " . $rowInvDt["bheight_max"];
												} else {
													$blength = $rowInvDt["lengthInch"];
													$bwidth = $rowInvDt["widthInch"];
													$bdepth = $rowInvDt["depthInch"];
												}
												$blength_frac = 0;
												$bwidth_frac = 0;
												$bdepth_frac = 0;
												$length = $blength;
												$width = $bwidth;
												$depth = $bdepth;
												if ($rowInvDt["lengthFraction"] != "") {
													$arr_length = explode("/", $rowInvDt["lengthFraction"]);
													if (count($arr_length) > 0) {
														$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
														$length = floatval($blength + $blength_frac);
													}
												}
												if ($rowInvDt["widthFraction"] != "") {
													$arr_width = explode("/", $rowInvDt["widthFraction"]);
													if (count($arr_width) > 0) {
														$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
														$width = floatval($bwidth + $bwidth_frac);
													}
												}
												if ($rowInvDt["depthFraction"] != "") {
													$arr_depth = explode("/", $rowInvDt["depthFraction"]);
													if (count($arr_depth) > 0) {
														$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
														$depth = floatval($bdepth + $bdepth_frac);
													}
												}


												if ($rowBoxDt["box_warehouse_id"] == "238") {
													$vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
													$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
													$get_loc_res = db_query($get_loc_qry, db_b2b());
													$loc_row = array_shift($get_loc_res);
													$shipfrom_city = $loc_row["shipCity"];
													$shipfrom_state = $loc_row["shipState"];
													$shipfrom_zip = $loc_row["shipZip"];
												} else {
													$vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];
													$get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='" . $vendor_b2b_rescue_id . "'";
													$get_loc_res = db_query($get_loc_qry, db());
													$loc_row = array_shift($get_loc_res);
													$shipfrom_city = $loc_row["company_city"];
													$shipfrom_state = $loc_row["company_state"];
													$shipfrom_zip = $loc_row["company_zip"];
												}
												$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
												$ship_from2 = $shipfrom_state;


												if ($i % 2 == 0) {
													$rowclr = '#dcdcdc';
												} else {
													$rowclr = '#f7f7f7';
												}
								?>
												<tr bgcolor="<?= $rowclr ?>">

													<td class=''>
														<? if ($rowsFavData['hideItems'] == 1) { ?><input type="button" name="hideItemIds" id="hideItemIds<?= $rowsFavData['id']; ?>" value="Remove" onclick="hideitem_remove(<?= $rowsFavData['id']; ?>,<?php echo $user_id ?>)"> <? } ?>
													</td>

													<td class='' width="5%"><?= $qty ?></td>
													<td class='' width="8%"><?= $estimated_next_load ?></td>
													<td class='' width="5%"><?= $expected_loads_per_mo ?></td>
													<td class='' width="5%"><?= $boxes_per_trailer ?></td>
													<td class='' width="3%"><?= $b2b_fob ?></td>
													<td class='' width="5%"><?= $rowInvDt['ID'] ?></td>
													<td class='' width="5%">
														<font color='<?= $miles_away_color; ?>'><?= $miles_from ?></font>
													</td>
													<td align="center" class='display_title'>
														<font color="<?= $b2bstatuscolor; ?>"><?= $b2bstatus_name ?></font>
													</td>

													<td align="center" class=''><?= $length ?></td>
													<td align="center" class=''>x</td>
													<td align="center" class=''><?= $width ?></td>
													<td align="center" class=''>x</td>
													<td align="center" class=''><?= $depth ?></td>
													<td class='' width="20%">
														<a href='https://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?= $rowBoxDt["id"] ?>&proc=View' target='_blank'>
															<?= $rowInvDt["description"] ?></a>
													</td>
													<td class='' width="5%"><?= $vendor_name; ?></td>
													<td class='' width="5%"><?= $ship_from ?></td>
													<td class=''><?= $ownername; ?></td>
													<td class='' width="7%">
														<?
														$arrG = array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord");
														$arrSb = array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold");
														$arrSup = array("SupersackUCB", "SupersacknonUCB");
														$arrPal = array("PalletsUCB", "PalletsnonUCB");
														$arrOther = array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " ");
														if (in_array($box_type, $arrG)) {
															$boxtype		= "Gaylord";
														} elseif (in_array($box_type, $arrSb)) {
															$boxtype		= "Shipping";
														} elseif (in_array($box_type, $arrSup)) {
															$boxtype		= "SuperSack";
														} elseif (in_array($box_type, $arrPal)) {
															$boxtype		= "Pallet";
														} elseif (in_array($box_type, $arrOther)) {
															$boxtype		= "Other";
														}
														echo $boxtype;
														?>
													</td>

												</tr>


								<?
											}
										}
									}
									$i++;
								}
							} else {
								?>
								<tr>
									<td colspan="18">No record found</td>
								</tr>
							<?
							}
							?>
							<tr>
								<td colspan="18">
									<input type="hidden" name="hdnhideItemsAction" value="1">
									<input type="hidden" name="hdnCompanyId" value="<?= $_REQUEST['ID']; ?>">
									<input type="button" name="btnAddHideInv" id="btnAddHideInv" value="Add New Inventory to Hide" style="cursor:pointer;" onclick="add_inventory_to_hide(<?php echo $user_id; ?>)">
								</td>
							</tr>
				</table>
			</form>
		</td>
		</tr>


		<!-- Hide Inventory Section End -->


		<!--Start Setup for hiding coliumns from user in boomerang section defined as seven 7  -->

		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">
				Setup
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;Hide Pricing and Buy Now page?&emsp;
				<input type="checkbox" name="clientdash_setup_hide" id="clientdash_setup_hide" value="yes" <? if ($setup_hide_flg == 1) {
																												echo " checked ";
																											} ?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr>

		<tr>
			<td>
				&nbsp;Hide Box Profiles and Browse Inventory Pages?&emsp;
				<input type="checkbox" name="clientdash_boxprofile_inv_hide" id="clientdash_boxprofile_inv_hide" value="yes" <? if ($setup_boxprofile_inv_flg == 1) {
																																	echo " checked ";
																																} ?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr>

		<tr align="">
			<td colspan="6">
				<input type="button" name="clientdash_setup_submit" id="clientdash_setup_submit" value="Submit" onclick='handleSetuphide(<?= $user_id; ?>);' />
			</td>
		</tr>
	</table>
</div>

<script>
	async function checkDuplicate_email(input_form_action) {
		console.log(input_form_action);
		var action_id = (input_form_action == "add_user" ? "user_email_add" : "user_email_update");
		user_email = $("#" + action_id).val();
		//var user_email = $("#user_email").val();
		var res = 2;
		console.log(("#" + action_id + "_error"));
		$.ajax({
			url: 'boomerang_users_action.php',
			data: {
				user_email,
				form_action: 'check_duplicate_email'
			},
			method: "post",
			async: false,
			success: function(response) {
				if (response > 1 && input_form_action == 'update_user') {
					$("#" + action_id + "_error").removeClass('hide_error');
					$("#" + action_id + "_error").text('Email already exists!');
					res = 0;
				} else if (response == 1 && input_form_action == 'add_user') {
					$("#" + action_id + "_error").removeClass('hide_error');
					$("#" + action_id + "_error").text('Email already exists!');
					res = 0;
				} else {
					$("#" + action_id + "_error").addClass('hide_error');
					$("#" + action_id + "_error").text("Email can't be blank!");
					res = 1;
				}
			}
		});
		return res;
	}
	$(document).ready(function() {
		$('body').on('submit', ".save_user", async function(event) {
			event.preventDefault();
			var input_form_action = $(this).find('.cls_form_action').val();
			var username = $(this).find("input[name='user_name']").val();
			var useremail = $(this).find("input[name='user_email']").val();
			var user_password = $(this).find("input[name='user_password']").val();
			var flag = true;
			if (username == "") {
				$(this).find('.user_name_error').removeClass('hide_error');
				flag = false;
			} else {
				$(this).find('.user_name_error').addClass('hide_error');
			}
			if (useremail == "") {
				$(this).find('.useremail_error').removeClass('hide_error');
				flag = false;
			} else {
				$(this).find('.useremail_error').addClass('hide_error');
			}
			if (user_password == "") {
				flag = false;
				$(this).find('.user_password_error').removeClass('hide_error');
			} else {
				$(this).find('.user_password_error').addClass('hide_error');
			}
			var duplicate_email = await checkDuplicate_email(input_form_action);
			console.log("Duplicate Email " + duplicate_email);
			if (flag == true && duplicate_email == 1) {
				var all_data = new FormData(this);
				$.ajax({
					url: 'boomerang_users_action.php',
					data: all_data,
					method: "post",
					processData: false,
					contentType: false,
					success: function(response) {
						console.log(response);
						console.log(input_form_action);
						if (response == 1) {
							if (input_form_action == 'add_user') {
								alert('User added successfully');
							} else {
								alert('User updated successfully');
							}
							location.reload();
						} else {
							alert('Something went wrong, try again later');
						}
					}
				})
			}
			return false;
		});

		$('body').on('click', '.edit_user', function() {
			var user_id = $(this).attr('user_id');
			$.ajax({
				url: 'boomerang_users_action.php',
				data: {
					user_id,
					form_action: 'get_edit_user_data'
				},
				method: "post",
				type: 'json',
				success: function(response) {
					//console.log(response);
					var data = JSON.parse(response);
					var all_companies_dp = $('#all_companies').html();
					var company_list = data.company_list;
					// Create an array of company IDs from the company_list
					var company_ids = company_list.map(function(company) {
						return company;
					});
					// Add the 'selected' attribute to the options that match the company IDs

					all_companies_dp = all_companies_dp.replace(/<option value="(\d+)">/g, function(match, id) {
						return '<option value="' + id + '"' + (company_ids.includes(Number(id)) ? ' selected' : '') + '>';
					});

					var edit_html = `<td colspan="6"><form class="save_user"><table>
						<td>
							<input type="text" name="user_name" value="${data.user_name}">
							<br><span class="color_red hide_error user_name_error">Name can't be blank!</span>
						</td>
						<td>
							<input type="email" name="user_email" id="user_email_update" value="${data.user_email}" onblur="checkDuplicate_email('update_user')">
							<span id="user_email_update_error" class="color_red hide_error useremail_error" >Email can't be blank!</span>
							<input type="password" name="user_password" value="${data.user_password}">
							<span class="color_red hide_error user_password_error">Password can't be blank!</span>
						</td>
						<td><select multiple name="companies[]">${all_companies_dp}</select></td>
						<td><select name="activate_deactivate"><option></option><option value="1" ${data.activate_deactivate == 1 ? "selected" : ""}>Active</option><option value="0" ${data.activate_deactivate == 0 ? "selected" : ""}>Deactive</option></select></td>
						<td><select name="user_block"><option></option><option value="1" ${data.user_block == 1 ? "selected" : ""}>Block</option><option value="0" ${data.user_block == 0 ? "selected" : ""}>Unblock</option></select></td>
						<td>
							<input type="hidden" name="form_action" value="update_user" class="cls_form_action"> 
							<input type="hidden" name="user_id" id="user_id" value="${user_id}">
							<input type="submit" value="Update">
							<button type="button" class="cancel_edit">Cancel</button>
						</td></tr></table></form></td>`;
					$('#userrowid_' + user_id).html(edit_html);
					//$('#userrowid_' + user_id + ' td').wrap("<form class='save_user'></form>");
				}
			})
		});

		$('body').on('click', '.cancel_edit', function() {
			location.reload();
		});

		$('body').on('click', ".delete_user", function() {
			var user_id = $(this).attr('user_id');
			alert("Do you sure want to delete this user?");
			$.ajax({
				url: 'boomerang_users_action.php',
				data: {
					user_id,
					form_action: 'delete_user'
				},
				method: "post",
				type: 'json',
				success: function(response) {
					if (response == 1) {
						alert('User deleted successfully');
						window.location.href = "boomerang_users_profile.php";
					} else {
						alert("Something went wrong, try again later")
					}
				}
			});
		})
	});

	function f_getPosition(e_elemRef, s_coord) {
		var n_pos = 0,
			n_offset,
			e_elem = e_elemRef;

		while (e_elem) {
			n_offset = e_elem["offset" + s_coord];
			n_pos += n_offset;
			e_elem = e_elem.offsetParent;
		}

		e_elem = e_elemRef;
		while (e_elem != document.body) {
			n_offset = e_elem["scroll" + s_coord];
			if (n_offset && e_elem.style.overflow == 'scroll')
				n_pos -= n_offset;
			e_elem = e_elem.parentNode;
		}
		return n_pos;
	}



	function add_inventory_to_favorite(user_id) {
		var selectobject = document.getElementById("btnAddFavoriteInv");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display = 'block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';

		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

		var sstr = "";
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "boomerang_getBoxData.php?user_id=" + user_id, true);
		xmlhttp.send(); /**/
	}

	function Remove_boxes_warehouse_data(favB2bId, user_id, closeloop = 0) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == 'done') {
					alert('Remove successfully. Need to reload the page after all boxes are updated.');
					document.location.reload(true);
					//document.getElementById('light').style.display='none';
					//window.location.replace("https://loops.usedcardboardboxes.com/clientdashboard_setup.php?ID="+compId);
				}
			}
		}
		if (closeloop == 1) {
			xmlhttp.open("GET", "boomerang_update_closeloop_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=2", true);
			xmlhttp.send();
		} else {
			xmlhttp.open("GET", "boomerang_update_favorite_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=2", true);
			xmlhttp.send();
		}
	}

	function Add_boxes_warehouse_data(favB2bId, user_id, closeloop = 0) {

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == 'done') {
					alert('Added successfully. Need to reload the page after all boxes are updated.');
					//document.getElementById('light').style.display='none';
					//window.location.replace("https://loops.usedcardboardboxes.com/clientdashboard_setup.php?ID="+compId);
					document.location.reload(true);
				}
			}
		}
		if (closeloop == 1) {
			xmlhttp.open("GET", "boomerang_update_closeloop_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=1", true);
			xmlhttp.send();
		} else {
			xmlhttp.open("GET", "boomerang_update_favorite_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=1", true);
			xmlhttp.send();
		}

	}

	function add_inventory_to_closeloop(compId) { //alert('compId -> '+compId)
		var selectobject = document.getElementById("btnAddCloseloopInv");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display = 'block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';

		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

		var sstr = "";
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "boomerang_getBoxDataCloseloop.php?ID=" + compId, true);
		xmlhttp.send();
	}

	function favitem_remove(favitemid, user_id) {
		document.location = "boomerang_users_profile.php?favitemid=" + favitemid + "&user_id=" + user_id + "&favremoveflg=yes";
	}

	function add_inventory_to_hide(user_id) {
		var selectobject = document.getElementById("btnAddHideInv");
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display = 'block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';

		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

		var sstr = "";
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("light").innerHTML = sstr + xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "boomerang_getBoxDataHide.php?user_id=" + user_id, true);
		xmlhttp.send();
	}

	function Remove_boxes_hide(favB2bId, user_id) {
		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == 'done') {
					alert('Remove successfully. Need to reload the page after all boxes are updated.');
					document.location.reload(true);
				}
			}
		}

		xmlhttp.open("GET", "boomerang_update_hide_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=2", true);
		xmlhttp.send();
	}

	function Add_boxes_hide(favB2bId, user_id) {

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == 'done') {
					alert('Added successfully. Need to reload the page after all boxes are updated.');
					document.location.reload(true);
				}
			}
		}

		xmlhttp.open("GET", "boomerang_update_hide_inventory_data.php?favB2bId=" + favB2bId + "&user_id=" + user_id + "&upd_action=1", true);
		xmlhttp.send();

	}

	function hideitem_remove(hideitemid, user_id) {
		document.location = "boomerang_users_profile.php?hideitemid=" + hideitemid + "&user_id=" + user_id + "&hideremoveflg=yes";
	}

	function handleSetuphide(user_id) {

		if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest();
		} else {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				alert("Record Updated.");
			}
		}

		var setuphide_flg = 0;
		if (document.getElementById('clientdash_setup_hide').checked) {
			var setuphide_flg = 1;
		}

		var boxprofileinv_flg = 0;
		if (document.getElementById('clientdash_boxprofile_inv_hide').checked) {
			var boxprofileinv_flg = 1;
		}


		xmlhttp.open("GET", "boomerang_update_setup_hide_flg.php?user_id=" + user_id + "&setuphide_flg=" + setuphide_flg + "&boxprofileinv_flg=" + boxprofileinv_flg, true);
		xmlhttp.send();
	}
</script>