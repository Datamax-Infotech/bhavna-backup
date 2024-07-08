<?
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

db();	
$warehouse_id = "";
$qry = "Select id from loop_warehouse where b2bid = '" . $_REQUEST["company_id"] . "'";
$row1 = db_query($qry);
while ($main_res1 = array_shift($row1)){
	$warehouse_id = $main_res1["id"];
}

?>
	   <label class="col-form-label"><span class="details">Select vendor&nbsp;&nbsp;<img src="images/refreshimg.png" width="10px" height="10px" onclick="reload_page()"/></span></label>
		<select id="vendor_id" name="vendor_id" onchange="loaddata(<? echo $warehouse_id; ?>,0, <?=$_REQUEST["company_id"]?>, 0)" class="form-control form-control-sm">
			<option value=""></option>
			<?	
				$vendor_ids = "";
				$query = db_query("SELECT water_inventory.vendor FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id
				WHERE water_boxes_to_warehouse.water_warehouse_id = " . $warehouse_id . " group by water_inventory.vendor", db() );
				while ($rowsel_getdata = array_shift($query)) {
					$vendor_ids = $vendor_ids . $rowsel_getdata["vendor"] . ",";
				}
				if ($vendor_ids != ""){
					$vendor_ids = substr($vendor_ids, 0, strlen($vendor_ids)-1);
				}

				$query = db_query( "SELECT * FROM water_vendors where active_flg = 1 and id in ($vendor_ids) order by Name", db() );
				while ($rowsel_getdata = array_shift($query)) {
					$tmp_str = "";
					if ($vendor_id == $rowsel_getdata["id"]) {
						$tmp_str = " selected ";
					}

					$main_material = $rowsel_getdata['description'];

					//$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
					$vender_nm = $rowsel_getdata['Name']. " - ". $main_material;
				?>
					<option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?> ><? echo $vender_nm;?></option>
				<?}
			?>
			<option value="addvendor">Add Vendor</option>
		</select>

