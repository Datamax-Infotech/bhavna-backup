<?
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$warehouse_id = $_REQUEST["warehouse_id"];
$vendor_id = $_REQUEST["vendor_id"];

?>
<select id="water_item" name="water_item" class="form-control form-control-sm mr-1">
	<option value=""></option>
	<?
	$get_boxes_query = "SELECT *, water_inventory.id as boxid FROM water_boxes_to_warehouse 
			INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id 
			WHERE water_boxes_to_warehouse.water_warehouse_id = " . $warehouse_id . " and vendor = '" . $vendor_id . "' ORDER BY description";
	$query = db_query($get_boxes_query, db());
	if (count($query) > 0) {
		echo '<optgroup label="Materials">';
		while ($myrowsel = array_shift($query)) {

			//$blank_row_str = "<option value='".$myrowsel["boxid"]."|" . $myrowsel["CostOrRevenuePerUnit"] . "|" . $myrowsel["AmountUnit"] . "|" . $myrowsel["Amount"]. "|" . $myrowsel["Outlet"]. "|" . $myrowsel["WeightorNumberofPulls"] . "|" . $myrowsel["CostOrRevenuePerPull"]. "|" . $myrowsel["CostOrRevenuePerItem"]. "|" . $myrowsel["Estimatedweight"] . "|" . $myrowsel["Estimatedweight_value"]. "|" . $myrowsel["Estimatedweight_peritem"]. "|" . $myrowsel["Estimatedweight_value_peritem"]. "|" . $myrowsel["poundpergallon_value"];
			$blank_row_str = "<option value='mat-" . $myrowsel["boxid"];
			$blank_row_str .= "' >" . $myrowsel["description"] . "/" . $myrowsel["WeightorNumberofPulls"] . "</option>";

			echo $blank_row_str;
		}
	}

	$query_fee = db_query("SELECT id,additional_fees_display FROM water_additional_fees  where active_flg = 1 order by display_order", db());
	if (count($query_fee) > 0) {
		echo '<optgroup label="Fee">';
		while ($rowsel_getdata = array_shift($query_fee)) {
			$blank_row_str = "<option value='fee-" . $rowsel_getdata["id"];
			$blank_row_str .= "' >" . $rowsel_getdata["additional_fees_display"] . "</option>";

			echo $blank_row_str;
		}
	}
	?>
</select>