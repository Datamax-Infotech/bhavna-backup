<?
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
?>
<style>
	.table-wrapper {
		/*border: 1px solid red;*/
		width: 1020px;
		height: 500px;
		overflow: auto;
	}

	table.tbstyle {
		width: 1000px;
	}

	.style7 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12;
		color: #333333;
		text-align: center;
	}

	.style7left {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12;
		color: #333333;
		text-align: left;
	}

	.style27 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 14;
		text-align: center;
	}
</style>
<br>
<div class="table-wrapper">
	<table cellSpacing="1" cellPadding="1" border="0" class="tbstyle">
		<tr>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">&nbsp;</span></td>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">B2B ID</span></td>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">Loops ID</span></td>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">L</span></td>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">W</span></td>
			<td bgColor="#c0cdda" align="center" valign="top" width="30px;"><span class="style27">H</span></td>
			<td bgColor="#c0cdda" valign="top" width="200px;"><span class="style27">Box Description</span></td>
			<td bgColor="#c0cdda" valign="top" width="200px;"><span class="style27">Supplier</span></td>
		</tr>
		<?
			db();
			$get_boxes_query = db_query("SELECT * FROM loop_boxes WHERE type = 'Gaylord' OR type = 'GaylordUCB' OR type = 'Loop'OR type = 'PresoldGaylord' OR type = 'Medium' OR type = 'Large' OR type = 'Xlarge' OR type = 'LoopShipping' OR type = 'Box' OR type = 'Boxnonucb' OR type = 'Presold' OR type = 'SupersackUCB' OR type = 'SupersacknonUCB' OR type = 'PalletsUCB' OR type = 'PalletsnonUCB' ORDER BY b2b_id limit 100");
			while ($boxes = array_shift($get_boxes_query)) {
		?>
				<tr <? $c = 0;
					if ($c == 1) {
						echo " bgcolor=lightgrey";
						$c = 0;
					} else {
						$c = 1;
					} ?>>
					<td valign="top" class="style7">
						<div id='updbox_action_div<?= $boxes["id"]; ?>'>
							<?php
							$checked_sql = "SELECT * FROM boomerange_inventory_gaylords_favorite WHERE fav_b2bid = '" . $boxes["b2b_id"] . "' AND user_id = '" . $_REQUEST['user_id'] . "' AND  fav_status = 1";
							$result_cnt = db_query($checked_sql, db());
							$number = tep_db_num_rows($result_cnt);

							//$boxes["id"];
							if ($number > 0) {
							?>
								<a id='btnremove' href='javascript:void(0);' onClick='Remove_boxes_warehouse_data(<?= $boxes["b2b_id"]; ?>,<?php echo $_REQUEST['user_id']; ?>)'><img src='images/fav.png' width='20%'> </a>
							<?
							} else {
							?>
								<a id='btnremove' href='javascript:void(0);' onClick='Add_boxes_warehouse_data(<?= $boxes["b2b_id"]; ?>,<?php echo $_REQUEST['user_id']; ?>)'><img src='images/non_fav.png' width='20%'> </a>
							<?
							} ?>
						</div>
					</td>
					<td valign="top" class="style7"><? echo $boxes["b2b_id"]; ?></td>
					<td valign="top" class="style7"><? echo $boxes["id"]; ?></td>
					<td valign="top" class="style7"><? echo $boxes["blength"]; ?> <? echo $boxes["blength_frac"]; ?></td>
					<td valign="top" class="style7"><? echo $boxes["bwidth"]; ?> <? echo $boxes["bwidth_frac"]; ?> </td>
					<td valign="top" class="style7"><? echo $boxes["bdepth"]; ?> <? echo $boxes["bdepth_frac"]; ?> </td>
					<td valign="top" class="style7left">
						<a target="_blank" href="manage_box_b2bloop.php?id=<? echo $boxes["id"]; ?>&proc=View">
							<? echo $boxes["bdescription"]; ?>
						</a>
					</td>
					<?
					$vender_nm = "";
					$b2b_location_city = "";
					$b2b_location_st = "";
					$b2b_location_zip = "";
					$sql_b2b = "SELECT * FROM inventory where ID = " . $boxes["b2b_id"];
					$result_b2b = db_query($sql_b2b, db_b2b());
					if ($myrowsel_b2b = array_shift($result_b2b)) {
						$b2b_location_city = $myrowsel_b2b["location_city"];
						$b2b_location_st = $myrowsel_b2b["location_state"];
						$b2b_location_zip = $myrowsel_b2b["location_zip"];
						$vendor_b2b_rescue = $myrowsel_b2b["vendor_b2b_rescue"];

						$vender_nm = "";
						if ($vendor_b2b_rescue != "") {
							$qry_supplier = "SELECT id, company_name, b2bid FROM loop_warehouse where id = " . $vendor_b2b_rescue;
							$res_supplier = db_query($qry_supplier, db());
							while ($fetch_supplier = array_shift($res_supplier)) {
								$vender_nm = get_nickname_val($fetch_supplier['company_name'], $fetch_supplier["b2bid"]) . " (Loop ID: " . $fetch_supplier["id"] . " B2B ID:" . $fetch_supplier["b2bid"] . ")";
							}
						}
					}
					?>
					<td valign="top" class="style7left"><? echo $vender_nm; ?></td>
				</tr>

		<?
			}
		?>
	</table>
</div>