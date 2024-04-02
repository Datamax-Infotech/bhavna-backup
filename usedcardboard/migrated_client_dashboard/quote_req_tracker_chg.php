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

db();
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit();
if (isset($_REQUEST["company_id"])) {
?>

	Select Demand Entry ID:
	<select id="demand_entry_list" name="demand_entry_list">
		<option value="">Select</option>
		<?php
		if ($_REQUEST["quote_req_quote_type"] != "") {
			$item_query = "SELECT * FROM quote_request WHERE quote_item = '" . $_REQUEST["quote_req_quote_type"] . "' AND companyID = " . $_REQUEST["company_id"];
		} else {
			$item_query = "SELECT * FROM quote_request WHERE companyID = " . $_REQUEST["company_id"];
		}
		$item_res = db_query($item_query);
		while ($item_rows = array_shift($item_res)) {
		?>
			<option value="<?php echo $item_rows["quote_id"]; ?>"><?php echo $item_rows["quote_id"]; ?></option>
		<?php
		}
		?>
	</select>
<?php
}
?>