<?php
//movewarehouseentry.php
// [id] => 393217 [tableid] => 11 [rowid] => 69139
require_once("inc/header_session.php");
require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");
$orderid = decrypt_url($_GET["id"]);
$tableid = decrypt_url($_GET["tableid"]);
db();
$warehouse = "SELECT * FROM ucbdb_warehouse WHERE id =" . $tableid;
$warehouse2 = db_query($warehouse);
$warehousename = "";
while ($warehousename2 = array_shift($warehouse2)) {
	$warehousename = $warehousename2['tablename'];
}
$sqlwarehouse = "SELECT * FROM ucbdb_warehouse";
$resw = db_query($sqlwarehouse);
while ($wrow = array_shift($resw)) {
	if ($wrow["tablename"] != "") {
		$sql = "SELECT * FROM " . $wrow["tablename"] . " WHERE orders_id = " . $_GET["id"] . " AND id IN (" . $_GET["rowid"] . ")";
		$rest = db_query($sql);
		$tablename2del = $wrow["tablename"];
		while ($instdata = array_shift($rest)) {
			$insql = "INSERT INTO " . $warehousename . "(`warehouse_id`, `orders_id`, `product_id`, `kit_id`, `module_id`,";
			$insql .= "`module_name`, `description`, `weight`, `length1`, `width`, `height`, `reference`, `kits_name`,";
			$insql .= "`shipping_name`, `shipping_attention`, `shipping_street1`, `shipping_street2`, `shipping_city`,";
			$insql .= "`shipping_state`, `shipping_zip`, `ups_shipping_release`, `phone`, `email`, `comments`, ";
			$insql .= "`tracking_no`, `service`, `bill_to_name`, `bill_to_street`, `bill_to_city`, `bill_to_state`,";
			$insql .= "`bill_to_country`, `bill_to_zip`, `bill_to_acct`, `qvnship1`, `qvnexcetiption1`, `qvntype1`,";
			$insql .= "`qvnemail1`, `qvnexcetiption2`, `qvntype2`, `qvnemail2`, `billing_option`, `package_type`,";
			$insql .= "`residential_indicator`, `ship_status`, `eod_flag`, `eod_date`) VALUES (";
			$insql .= $tableid . ',' . $orderid . ',' . $instdata['product_id'] . ',' . $instdata['kit_id'] . ',';
			$insql .= $instdata['module_id'] . ',"' . $instdata['module_name'] . '","' . $instdata['description'] . '","';
			$insql .= $instdata['weight'] . '","' . $instdata['length1'] . '","' . $instdata['width'] . '","';
			$insql .= $instdata['height'] . '","' . $instdata['reference'] . '","' . $instdata['kits_name'] . '","';
			$insql .= $instdata['shipping_name'] . '","' . $instdata['shipping_attention'] . '","';
			$insql .= $instdata['shipping_street1'] . '","' . $instdata['shipping_street2'] . '","';
			$insql .= $instdata['shipping_city'] . '","' . $instdata['shipping_state'] . '","' . $instdata['shipping_zip'] . '","';
			$insql .= $instdata['ups_shipping_release'] . '","' . $instdata['phone'] . '","' . $instdata['email'] . '","';
			$insql .= $instdata['comments'] . '","' . $instdata['tracking_no'] . '","' . $instdata['service'] . '","';
			$insql .= $instdata['bill_to_name'] . '","' . $instdata['bill_to_street'] . '","' . $instdata['bill_to_city'] . '","';
			$insql .= $instdata['bill_to_state'] . '","' . $instdata['bill_to_country'] . '","' . $instdata['bill_to_zip'] . '","';
			$insql .= $instdata['bill_to_acct'] . '","' . $instdata['qvnship1'] . '","' . $instdata['qvnexcetiption1'] . '","';
			$insql .= $instdata['qvntype1'] . '","' . $instdata['qvnemail1'] . '","' . $instdata['qvnexcetiption2'] . '","';
			$insql .= $instdata['qvntype2'] . '","' . $instdata['qvnemail2'] . '","' . $instdata['billing_option'] . '","';
			$insql .= $instdata['package_type'] . '","' . $instdata['residential_indicator'] . '","';
			$insql .=  $instdata['ship_status'] . '","' . $instdata['eod_flag'] . '","' . $instdata['eod_date'] . '")';

			//echo $insql . "<br>";
			$result1 = db_query($insql);

			if (!$result1) {
				$deisql = "DELETE FROM " . $tablename2del . " WHERE id = " . $instdata['id'];
				//echo $deisql . "<br>";

				$result2 = db_query($deisql);
			}
		}
	}
}
