<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
//To validate the address
require '../fedex/fedexapi/src/FedEx/AbstractComplexType.php';
require '../fedex/fedexapi/src/FedEx/AbstractRequest.php';
require '../fedex/fedexapi/src/FedEx/AbstractSimpleType.php';
require '../fedex/fedexapi/src/FedEx/Reflection.php';

require '../fedex/fedexapi/src/FedEx/AddressValidationService/Request.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/Address.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/AddressAttribute.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/AddressToValidate.php';

require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/AddressValidationReply.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/AddressValidationRequest.php';

require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/AddressValidationResult.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/ClientDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/Contact.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/Localization.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/Notification.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/NotificationParameter.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/ParsedAddressPartsDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/ParsedPostalCodeDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/ParsedStreetLineDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/TransactionDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/VersionId.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/WebAuthenticationCredential.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/ComplexType/WebAuthenticationDetail.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/SimpleType/AutoConfigurationType.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/SimpleType/FedExAddressClassificationType.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/SimpleType/NotificationSeverityType.php';
require '../fedex/fedexapi/src/FedEx/AddressValidationService/SimpleType/OperationalAddressStateType.php';
db();
$fedex_chk_address = $_REQUEST["order_add1"];
$fedex_chk_address2 = $_REQUEST["order_add2"];
$fedex_chk_city = $_REQUEST["order_city"];
$fedex_chk_state = $_REQUEST["order_state"];
$fedex_chk_zipcode = $_REQUEST["order_zipcode"];

require_once("../fedex/fedexapi/examples/address-validation.php");

$shipzipcode = "";
$customer_firstname = "";
$customer_company = "";
$customer_address = "";
$customer_address2 = "";
$customer_city = "";
$statesmall = "";
$customer_postcode = "";
$customer_telephone = "";
$customer_email = "";
$order_comment = "";
$fedex_bad_add = 0;
$cancel_order = "No";
$bill_to_name = "";
$bill_to_street = "";
$bill_to_city = "";
$bill_to_state = "";
$bill_to_country = "";
$bill_to_zip = "";
$dt_view_qry = "Select * from orders where orders_id = '" . $_REQUEST["tmp_orderid"] . "'";
$data_res = db_query($dt_view_qry);
while ($product_details_tmp = array_shift($data_res)) {
	$cancel_order = $product_details_tmp["cancel"];
	$fedex_bad_add = $product_details_tmp["fedex_validate_bad_add"];
	$shipzipcode = $product_details_tmp["delivery_postcode"];
	$customer_firstname = $product_details_tmp["customers_name"];
	$customer_company = $product_details_tmp["customers_company"];
	$customer_address = $product_details_tmp["customers_street_address"];
	$customer_address2 = $product_details_tmp["customers_street_address2"];
	$customer_city = $product_details_tmp["customers_city"];
	$statesmall = $product_details_tmp["customers_state"];
	$customer_postcode = $product_details_tmp["customers_postcode"];
	$customer_telephone = $product_details_tmp["customers_telephone"];
	$customer_email = $product_details_tmp["customers_email_address"];
	$order_comment = $product_details_tmp["comment"];

	$bill_to_name = $product_details_tmp["billing_name"];
	$bill_to_street = $product_details_tmp["billing_street_address"];
	$bill_to_city = $product_details_tmp["billing_city"];
	$bill_to_state = $product_details_tmp["billing_state"];
	$bill_to_country = $product_details_tmp["billing_country"];
	$bill_to_zip = $product_details_tmp["billing_postcode"];
}

$fedex_validate_bad_add = 0;
$fedex_reply_time = "";
$fedex_search_resp_state = "";
$fedex_search_resp_classification = "";
$fedex_search_resp_add1 = '';
$fedex_search_resp_add2 = '';
$fedex_search_resp_statecode = '';
$fedex_search_resp_city = '';
$fedex_search_resp_zip = '';
$fedex_search_res = isset($fedex_search_res) ? $fedex_search_res : "";
if ($fedex_search_res == "Request not processed.") {
	$fedex_validate_bad_add = 1;
} else {
	$fedex_search_res_arr = explode("|", $fedex_search_res);
	if ($fedex_search_res_arr[2] == "UNKNOWN") {
		$fedex_validate_bad_add = 1;
	}
	$fedex_reply_time = $fedex_search_res_arr[0];
	$fedex_search_resp_state = $fedex_search_res_arr[1];
	$fedex_search_resp_classification = $fedex_search_res_arr[2];
	$fedex_search_resp_add1 = $fedex_search_res_arr[3];
	$fedex_search_resp_add2 = $fedex_search_res_arr[4];
	$fedex_search_resp_city = $fedex_search_res_arr[5];
	$fedex_search_resp_statecode = $fedex_search_res_arr[6];
	$fedex_search_resp_zip = $fedex_search_res_arr[7];
}

$sql_ins = "Update orders set delivery_name = '" . str_replace("'", "\'", $_REQUEST["order_name"]) . "', delivery_company = '" . str_replace("'", "\'", $_REQUEST["order_company"]) . "', delivery_street_address = '" . str_replace("'", "\'", $_REQUEST["order_add1"]) . "', delivery_street_address2 = '" . str_replace("'", "\'", $_REQUEST["order_add2"]) . "', delivery_city = '" . str_replace("'", "\'", $_REQUEST["order_city"]) . "', delivery_state = '" . str_replace("'", "\'", $_REQUEST["order_state"]) . "', delivery_postcode = '" . str_replace("'", "\'", $_REQUEST["order_zipcode"]) . "', ";
$sql_ins .= " customers_telephone = '" . str_replace("'", "\'", $_REQUEST["order_phone"]) . "', customers_email_address = '" . str_replace("'", "\'", $_REQUEST["order_email"]) . "', comment = '" . str_replace("'", "\'", $_REQUEST["order_comments"]) . "',  ";
//$sql_ins .= " fedex_validate_bad_add = '" . str_replace("'", "\'", $fedex_validate_bad_add) . "', fedex_reply_time = '" . $fedex_reply_time . "', ";
$sql_ins .= " fedex_validate_bad_add = '" . str_replace("'", "\'", (string)$fedex_validate_bad_add) . "', fedex_reply_time = '" . $fedex_reply_time . "', ";
$sql_ins .= " fedex_search_resp_state = '" . str_replace("'", "\'", $fedex_search_resp_state) . "', fedex_search_resp_classification = '" . str_replace("'", "\'", $fedex_search_resp_classification) . "', ";
$sql_ins .= " fedex_search_resp_add1 = '" . str_replace("'", "\'", $fedex_search_resp_add1) . "', fedex_search_resp_add2 = '" . str_replace("'", "\'", $fedex_search_resp_add2) . "', ";
$sql_ins .= " fedex_search_resp_statecode = '" . str_replace("'", "\'", $fedex_search_resp_statecode) . "', fedex_search_resp_city = '" . str_replace("'", "\'", $fedex_search_resp_city) . "', ";
$sql_ins .= " fedex_search_resp_zip = '" . str_replace("'", "\'", $fedex_search_resp_zip) . "' ";
$sql_ins .= " where orders_id = " . $_REQUEST["tmp_orderid"];
$result = db_query($sql_ins);

$shipzipcode = $_REQUEST["order_zipcode"];
$customer_address = $_REQUEST["order_add1"];
$customer_address2 = $_REQUEST["order_add2"];
$customer_city = $_REQUEST["order_city"];
$statesmall = $_REQUEST["order_state"];
$customer_postcode = $_REQUEST["order_zipcode"];

$tracking_no_found = "no";
$query_child = "select * from orders_active_export where orders_id = ? AND tracking_number <> ''";
$res_child = db_query($query_child, array("i"), array($_REQUEST["tmp_orderid"]));
while ($row_mk_info = array_shift($res_child)) {
	$tracking_no_found = "yes";
}

//echo "Chk " . $fedex_validate_bad_add . " " . $fedex_bad_add . " " . $cancel_order . "<br>";
//&& $fedex_bad_add == 1 &&
if ($fedex_validate_bad_add == 0 && $tracking_no_found == "no" && $cancel_order == "No") {
	$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63, 64, 65, 66, 67, 68, 69, 70)";
	$res_rgp = db_query($query);
	$rgp = array_shift($res_rgp);
	$arr_rgp = explode(',', $rgp["grp_prod_id"]);

	$query = "SELECT W.warehouse_id, W.kit_warehouseid, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip=?";
	$res_row = db_query($query, array("s"), array(substr($shipzipcode, 0, 3)));
	$row = array_shift($res_row);
	$warehouse_id = $row["warehouse_id"];
	$kit_warehouseid = $row["kit_warehouseid"];
	$tbl_name = "orders_active_" . str_replace(' ', '_', strtolower($row["name"]));

	//Delete order warehouse rows as it can add multiple rows
	$tblarry_str = "orders_active_ucb_atlanta,orders_active_ucb_dallas,orders_active_ucb_danville,orders_active_ucb_hannibal,orders_active_ucb_hunt_valley,orders_active_ucb_iowa,orders_active_ucb_los_angeles,orders_active_ucb_montreal,orders_active_ucb_philadelphia,orders_active_ucb_rochester,orders_active_ucb_salt_lake,orders_active_ucb_toronto, orders_active_ucb_phoenix, orders_active_ucb_evansville";
	$arr_warehouse = explode(",", $tblarry_str);
	foreach ($arr_warehouse as $tbl_warehouse) {
		$dt_view_qry1 = "Delete from " . $tbl_warehouse . " where orders_id = '" . $_REQUEST["tmp_orderid"] . "'";
		$data_res1 = db_query($dt_view_qry1);
	}

	$dt_view_qry = "Select * from orders_products where orders_id = '" . $_REQUEST["tmp_orderid"] . "'";
	$data_res = db_query($dt_view_qry);
	while ($product_details_tmp = array_shift($data_res)) {

		if ($product_details_tmp["products_id"] != "") {
			$ucb_prod_id = $product_details_tmp["products_id"];
			$products_name = $product_details_tmp["products_name"];
			$products_model = $product_details_tmp["products_model"];

			if (in_array($ucb_prod_id, $arr_rgp)) {
				$rec_found = "no";
				$query_child = "Select orders_id from orders_sps where orders_id = ?";
				$res_child = db_query($query_child, array("i"), array($_REQUEST["tmp_orderid"]));
				while ($row_mk_info = array_shift($res_child)) {
					$rec_found = "yes";
				}

				$tmpName = $customer_firstname;
				$thirdparty_mnsd = '"' . $_REQUEST["tmp_orderid"] . '","' . $tmpName . '","' . $customer_company . '","","","' . $customer_address . '","' . $customer_address2 . '","' . $customer_city . '","' . $statesmall . '","' . $customer_postcode . '","8882693788","UCB","sps_ups@usedcardboardboxes.com"';
				$thirdparty_mnsd .= ',"' . $products_name . '","' . $products_model . '",1"';
				$mid_thirdparty_mnsd = str_replace("<br>", "", "$thirdparty_mnsd");
				$new_thirdparty_mnsd = str_replace("'", "", "$mid_thirdparty_mnsd");

				if ($rec_found == "no") {
					$ttpp_query = "INSERT INTO orders_sps SET orders_id = ?, order_string = ?, shipping_name = ?, 
						shipping_street1 = ?, shipping_street2 = ?, shipping_city = ?, shipping_state = ?, shipping_zip = ?,
						phone = ?, email = ?, bill_to_name = ?, bill_to_street = ?, bill_to_city = ?,
						bill_to_state = ?, bill_to_country = ?, bill_to_zip = ?";
					//echo $ttpp_query . "<br>";
					db_query($ttpp_query, array("i", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s"), array(
						$_REQUEST["tmp_orderid"], $new_thirdparty_mnsd,
						$tmpName, $customer_address, $customer_address2, $customer_city, $statesmall, $customer_postcode,
						$customer_telephone, $customer_email, $bill_to_name, $bill_to_street, $bill_to_city, $bill_to_state, $bill_to_country, $bill_to_zip
					));
				} else {
					$ttpp_query = "Update orders_sps SET order_string = ?, shipping_name = ?, 
						shipping_street1 = ?, shipping_street2 = ?, shipping_city = ?, shipping_state = ?, shipping_zip = ?,
						phone = ?, email = ?, bill_to_name = ?, bill_to_street = ?, bill_to_city = ?,
						bill_to_state = ?, bill_to_country = ?, bill_to_zip = ? where orders_id = ? ";
					//echo $ttpp_query . "<br>";
					db_query($ttpp_query, array("s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "i"), array(
						$new_thirdparty_mnsd,
						$tmpName, $customer_address, $customer_address2, $customer_city, $statesmall, $customer_postcode,
						$customer_telephone, $customer_email, $bill_to_name, $bill_to_street, $bill_to_city, $bill_to_state, $bill_to_country, $bill_to_zip, $_REQUEST["tmp_orderid"]
					));
				}
			}
			$pord_order_id = "";
			if (!in_array($ucb_prod_id, $arr_rgp)) {
				$chk_only_first_rec = "yes";
				$product_id_new = $ucb_prod_id;
				$pord_order_id = ($pord_order_id == "") ? $product_id_new : $pord_order_id . ',' . $product_id_new;

				$pr_qty = "SELECT products_quantity FROM orders_products WHERE orders_id=? AND products_id=?";
				$res_row_qty = db_query($pr_qty, array("i", "i"), array($_REQUEST["tmp_orderid"], $product_id_new));
				$row_qty = array_shift($res_row_qty);
				$y = $row_qty["products_quantity"];
				for ($x = 1; $x <= $y; $x++) {
					$query = "SELECT kit_id FROM orders_products WHERE orders_id=? AND products_id=?";
					$res_row_kits = db_query($query, array("i", "i"), array($_REQUEST["tmp_orderid"], $product_id_new));
					$row_kits = array_shift($res_row_kits);

					$query = "SELECT M.module_id, M.name, M.description, M.weight, M.length1, M.width, M.height, M.reference, M.tree_value, K.kit_id as kits_id, K.name as kits_name";
					$query .= " FROM module M INNER JOIN moduletokits MK ON M.module_id=MK.module_id";
					$query .= " INNER JOIN kits K ON MK.kit_id=K.kit_id WHERE MK.kit_id=?";

					$res_mk_info = db_query($query, array("i"), array($row_kits["kit_id"]));
					$trees_saved = 0; 
					while ($row_mk_info = array_shift($res_mk_info)) {
						$trees_saved += $row_mk_info['tree_value']; // Line Added by devi for Tree Counter Update
						//###############################################//
						//Check the warehouse Rule
						$dest_tbl_name = "";
						$dest_warehouse_id = "";
						//$query = "SELECT * FROM wh_rule WHERE module_id='".$row_mk_info["module_id"]."' AND warehouse_id='$warehouse_id'";
						$query = "SELECT * FROM wh_rule WHERE module_id= ? AND warehouse_id=?";
						$rswc = db_query($query, array("i", "s"), array($row_mk_info["module_id"], $warehouse_id));
						$num_rswc = tep_db_num_rows($rswc);
						if ($num_rswc > 0) {
							$rwwc = array_shift($rswc);
							$dest_warehouse_id = $rwwc["warehouse_d_id"];
							//$query = "SELECT * FROM warehouse WHERE warehouse_id='$dest_warehouse_id'";
							$query = "SELECT * FROM warehouse WHERE warehouse_id=?";
							$res_rw = db_query($query, array("s"), array($dest_warehouse_id));
							$rw = array_shift($res_rw);
							$dest_tbl_name = "orders_active_" . str_replace(' ', '_', strtolower($rw["name"]));
						}
						$dest_warehouse_id = ($dest_warehouse_id == "") ? $warehouse_id : $dest_warehouse_id;
						$dest_tbl_name = ($dest_tbl_name == "") ? $tbl_name : $dest_tbl_name;

						$rec_found = "no";
						if ($chk_only_first_rec == "yes") {
							$res_dup_chk = db_query("Select orders_id from " . $dest_tbl_name . " where orders_id = '" . $_REQUEST["tmp_orderid"] . "'");
							while ($row_dup_info = array_shift($res_dup_chk)) {
								$rec_found = "yes";
							}
						}

						if ($rec_found == "no") {
							$query = "INSERT INTO " . $dest_tbl_name . " SET warehouse_id=$dest_warehouse_id, orders_id=" . $_REQUEST["tmp_orderid"] . ", product_id=$product_id_new, kit_id=" . $row_mk_info["kits_id"] . ", module_id=" . $row_mk_info["module_id"];
							$query .= ", module_name='" . str_replace("'", "\'", $row_mk_info["name"]) . "', description='" . str_replace("'", "\'", $row_mk_info["description"]) . "', weight='" . $row_mk_info["weight"] . "', length1='" . $row_mk_info["length1"] . "'";
							$query .= ", width='" . $row_mk_info["width"] . "', height='" . $row_mk_info["height"] . "', reference='" . str_replace("'", "\'", $row_mk_info["reference"]) . "', kits_name='" . str_replace("'", "\'", $row_mk_info["kits_name"]) . "'";
							$query .= ", shipping_name='" . str_replace("'", "\'", $customer_firstname) . "', shipping_attention='" . str_replace("'", "\'", $customer_company) . "', shipping_street1='" . str_replace("'", "\'", $customer_address) . "', shipping_street2='" . str_replace("'", "\'", $customer_address2) . "'";
							$query .= ", shipping_city='" . str_replace("'", "\'", $customer_city) . "', shipping_state='" . str_replace("'", "\'", $statesmall) . "', shipping_zip='" . str_replace("'", "\'", $customer_postcode) . "', ups_shipping_release=''";
							$query .= ", phone='" . str_replace("'", "\'", $customer_telephone) . "', email='" . str_replace("'", "\'", $customer_email) . "', qvnemail1='" . str_replace("'", "\'", $customer_email) . "', comments='" . str_replace("'", "\'", $order_comment) . "'";

							//echo $query . "<br>";
							db_query($query);
						} else {
							echo "<font color=red>Record not deleted from " . $dest_tbl_name . " table, order #'" . $_REQUEST["tmp_orderid"] . "'. Please check.</font>";
							exit;
						}
						$chk_only_first_rec = "no";
					}
				}
			}
		}
	}
}

$tblarry_str = "orders_active_ucb_atlanta,orders_active_ucb_dallas,orders_active_ucb_danville,orders_active_ucb_hannibal,orders_active_ucb_hunt_valley,orders_active_ucb_iowa,orders_active_ucb_los_angeles,orders_active_ucb_montreal,orders_active_ucb_philadelphia,orders_active_ucb_rochester,orders_active_ucb_salt_lake,orders_active_ucb_toronto, orders_active_ucb_phoenix, orders_active_ucb_evansville";
$tblarry = explode(",", $tblarry_str);

$total_cnt = count($tblarry);

for ($cnt = 0; $cnt < $total_cnt; $cnt++) {
	$sql_ins = "Update " . $tblarry[$cnt] . " set shipping_name = '" . str_replace("'", "\'", $_REQUEST["order_name"]) . "', shipping_street1 = '" . str_replace("'", "\'", $_REQUEST["order_add1"]) . "', shipping_street2 = '" . str_replace("'", "\'", $_REQUEST["order_add2"]) . "', shipping_city = '" . str_replace("'", "\'", $_REQUEST["order_city"]) . "', shipping_state = '" . str_replace("'", "\'", $_REQUEST["order_state"]) . "', shipping_zip = '" . str_replace("'", "\'", $_REQUEST["order_zipcode"]) . "', ";
	$sql_ins .= " phone = '" . str_replace("'", "\'", $_REQUEST["order_phone"]) . "', email = '" . str_replace("'", "\'", $_REQUEST["order_email"]) . "' ";
	$sql_ins .= " where ship_status = 'N' and orders_id = " . $_REQUEST["tmp_orderid"];

	$result = db_query($sql_ins);
}


echo "<script type=\"text/javascript\">";
echo "window.location.href=\"orders.php?id=" . $_REQUEST["tmp_orderid"] . '&proc=View&searchcrit=&page=0' . "\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $_REQUEST["tmp_orderid"] . '&proc=View&searchcrit=&page=0' . "\" />";
echo "</noscript>";
exit;
