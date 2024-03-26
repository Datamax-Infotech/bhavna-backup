<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

$shipzipcode = "";
$customer_firstname = "";
$customer_company = "";
$customer_address = "";
$customer_address2 = "";
$customer_city = "";
$statesmall = "";
$customer_postcode = "";
$cancel_order = "No";
$customer_telephone = "";
$customer_email = "";
$order_comment = "";
$fedex_bad_add = 0;
$dt_view_qry = "Select * from orders where orders_id = '" . decrypt_url($_REQUEST["orders_id"]) . "'";
$data_res = db_query($dt_view_qry);
db();
while ($product_details_tmp = array_shift($data_res)) {
	$cancel_order = $product_details_tmp["cancel"];
	$fedex_bad_add = $product_details_tmp["fedex_validate_bad_add"];
	$shipzipcode = $product_details_tmp["delivery_postcode"];
	$customer_firstname = $product_details_tmp["customers_name"];
	$customer_company = $product_details_tmp["customers_company"];
	$customer_address = $product_details_tmp["delivery_street_address"];
	$customer_address2 = $product_details_tmp["delivery_street_address2"];
	$customer_city = $product_details_tmp["delivery_city"];
	$statesmall = $product_details_tmp["delivery_state"];
	$customer_postcode = $product_details_tmp["delivery_postcode"];
	$customer_telephone = $product_details_tmp["customers_telephone"];
	$customer_email = $product_details_tmp["customers_email_address"];
	$order_comment = $product_details_tmp["comment"];
}

if ($fedex_bad_add == 0) {
	$query_ins = "INSERT into b2c_order_address_fedex (orders_id, address1, address2, city, state, zipcode) Select orders_id, delivery_street_address, delivery_street_address2, delivery_city, delivery_state, delivery_postcode from orders WHERE orders_id = '" . $_REQUEST["orders_id"] . "'";
	$result_ins = db_query($query_ins);

	if (empty($result_ins)) {
		$query_ins = "Update orders set delivery_street_address = fedex_search_resp_add1, delivery_street_address2 = fedex_search_resp_add2, delivery_city = fedex_search_resp_city, delivery_state = fedex_search_resp_statecode, delivery_postcode = fedex_search_resp_zip WHERE orders_id = '" . $_REQUEST["orders_id"] . "'";
		$result_ins = db_query($query_ins);
	}

	if ($cancel_order == "No") {

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

		$dt_view_qry = "Select * from orders where orders_id = '" . $_REQUEST["orders_id"] . "'";
		$data_res = db_query($dt_view_qry);
		while ($product_details_tmp = array_shift($data_res)) {
			$shipzipcode = $product_details_tmp["delivery_postcode"];
			$customer_firstname = $product_details_tmp["customers_name"];
			$customer_company = $product_details_tmp["customers_company"];
			$customer_address = $product_details_tmp["delivery_street_address"];
			$customer_address2 = $product_details_tmp["delivery_street_address2"];
			$customer_city = $product_details_tmp["delivery_city"];
			$statesmall = $product_details_tmp["delivery_state"];
			$customer_postcode = $product_details_tmp["delivery_postcode"];
			$customer_telephone = $product_details_tmp["customers_telephone"];
			$customer_email = $product_details_tmp["customers_email_address"];
			$order_comment = $product_details_tmp["comment"];
		}


		$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63, 64, 65, 66, 67, 68, 69, 70)";
		$qry = db_query($query);
		$rgp = array_shift($qry);
		$arr_rgp = explode(',', $rgp["grp_prod_id"]);

		$query = "SELECT W.warehouse_id, W.kit_warehouseid, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip=?";
		$res_query = db_query($query, array("s"), array(substr($shipzipcode, 0, 3)));
		$row = array_shift($res_query);
		$warehouse_id = $row["warehouse_id"];
		$kit_warehouseid = $row["kit_warehouseid"];
		$tbl_name = "orders_active_" . str_replace(' ', '_', strtolower($row["name"]));

		//Delete order warehouse rows as it can add multiple rows
		$tblarry_str = "orders_active_ucb_atlanta,orders_active_ucb_dallas,orders_active_ucb_danville,orders_active_ucb_hannibal,orders_active_ucb_hunt_valley,orders_active_ucb_iowa,orders_active_ucb_los_angeles,orders_active_ucb_montreal,orders_active_ucb_philadelphia,orders_active_ucb_rochester,orders_active_ucb_salt_lake,orders_active_ucb_toronto, orders_active_ucb_phoenix, orders_active_ucb_evansville";
		$arr_warehouse = explode(",", $tblarry_str);
		foreach ($arr_warehouse as $tbl_warehouse) {
			$dt_view_qry1 = "Delete from " . $tbl_warehouse . " where orders_id = '" . $_REQUEST["orders_id"] . "'";
			$data_res1 = db_query($dt_view_qry1);
		}

		$dt_view_qry = "Select * from orders_products where orders_id = '" . $_REQUEST["orders_id"] . "'";
		$data_res = db_query($dt_view_qry);
		while ($product_details_tmp = array_shift($data_res)) {
			$products_name = $product_details_tmp["products_name"];
			$products_model = $product_details_tmp["products_model"];
			$bill_to_zip = "";
			$bill_to_street = "";
			$bill_to_state = "";
			$bill_to_name = "";
			$bill_to_country = "";
			$bill_to_city = ""; 
			if ($product_details_tmp["products_id"] != "") {
				$ucb_prod_id = $product_details_tmp["products_id"];

				if (in_array($ucb_prod_id, $arr_rgp)) {
					$rec_found = "no";
					$query_child = "Select orders_id from orders_sps where orders_id = ?";
					$res_child = db_query($query_child, array("i"), array($_REQUEST["orders_id"]));
					while ($row_mk_info = array_shift($res_child)) {
						$rec_found = "yes";
					}

					$tmpName = $customer_firstname;
					$thirdparty_mnsd = '"' . $_REQUEST["orders_id"] . '","' . $tmpName . '","' . $customer_company . '","","","' . $customer_address . '","' . $customer_address2 . '","' . $customer_city . '","' . $statesmall . '","' . $customer_postcode . '","8882693788","UCB","sps_ups@usedcardboardboxes.com"';
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
							$_REQUEST["orders_id"], $new_thirdparty_mnsd,
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
							$customer_telephone, $customer_email, $bill_to_name, $bill_to_street, $bill_to_city, $bill_to_state, $bill_to_country, $bill_to_zip, $_REQUEST["orders_id"]
						));
					}
				}
				if (!in_array($ucb_prod_id, $arr_rgp)) {
					$product_id_new = $ucb_prod_id;
					if (!isset($pord_order_id)) {
						$pord_order_id = "";
					}
					$pord_order_id = $pord_order_id == "" ? $product_id_new : $pord_order_id . ',' . $product_id_new;

					$pr_qty = "SELECT products_quantity FROM orders_products WHERE orders_id=? AND products_id=?";
					$result2 = db_query($pr_qty, array("i", "i"), array($_REQUEST["orders_id"], $product_id_new));
					$row_qty = array_shift($result2);
					$y = $row_qty["products_quantity"];
					for ($x = 1; $x <= $y; $x++) {
						$query = "SELECT kit_id FROM orders_products WHERE orders_id=? AND products_id=?";
						$result1 = db_query($query, array("i", "i"), array($_REQUEST["orders_id"], $product_id_new));
						$row_kits = array_shift($result1);
						$query = "SELECT M.module_id, M.name, M.description, M.weight, M.length1, M.width, M.height, M.reference, M.tree_value, K.kit_id as kits_id, K.name as kits_name";
						$query .= " FROM module M INNER JOIN moduletokits MK ON M.module_id=MK.module_id";
						$query .= " INNER JOIN kits K ON MK.kit_id=K.kit_id WHERE MK.kit_id=?";
						echo $query . " " . $row_kits["kit_id"] . "<br>";
						$trees_saved = 0; 
						$res_mk_info = db_query($query, array("i"), array($row_kits["kit_id"]));
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
								$result = db_query($query, array("s"), array($dest_warehouse_id));
								$rw = array_shift($result);
								$dest_tbl_name = "orders_active_" . str_replace(' ', '_', strtolower($rw["name"]));
							}
							$dest_warehouse_id = ($dest_warehouse_id == "") ? $warehouse_id : $dest_warehouse_id;
							$dest_tbl_name = ($dest_tbl_name == "") ? $tbl_name : $dest_tbl_name;
							$query_new = "INSERT INTO " . $dest_tbl_name . " (warehouse_id, orders_id, product_id, kit_id, module_id, module_name, 
								description, weight, length1, width, height, reference, kits_name, shipping_name, shipping_attention, shipping_street1, shipping_street2, shipping_city, shipping_state, 
								shipping_zip, ups_shipping_release, phone, email, qvnemail1, comments) VALUES ('" . $dest_warehouse_id . "', '" . $_REQUEST["orders_id"] . "', '" . $product_id_new . "', '" . $row_mk_info["kits_id"] . "', '" . $row_mk_info["module_id"] . "'";
							$query_new .= ", '" . str_replace("'", "\'", $row_mk_info["name"]) . "', '" . str_replace("'", "\'", $row_mk_info["description"]) . "', '" . $row_mk_info["weight"] . "', '" . $row_mk_info["length1"] . "'";
							$query_new .= ", '" . $row_mk_info["width"] . "', '" . $row_mk_info["height"] . "', '" . str_replace("'", "\'", $row_mk_info["reference"]) . "', '" . str_replace("'", "\'", $row_mk_info["kits_name"]) . "'";
							$query_new .= ", '" . str_replace("'", "\'", $customer_firstname) . "', '" . str_replace("'", "\'", $customer_company) . "', '" . str_replace("'", "\'", $customer_address) . "', '" . str_replace("'", "\'", $customer_address2) . "'";
							$query_new .= ", '" . str_replace("'", "\'", $customer_city) . "', '" . $statesmall . "', '" . $customer_postcode . "', ''";
							$query_new .= ", '" . $customer_telephone . "', '" . $customer_email . "', '" . $customer_email . "', '" . str_replace("'", "\'", $order_comment) . "')";

							echo $query_new . "<br>";

							$new_res = db_query($query_new);
						}
					}
				}
			}
		}
	}
}


echo "<script type=\"text/javascript\">";
echo "window.location.href=\"orders.php?id=" . encrypt_url($_REQUEST["orders_id"]) . '&proc=View&searchcrit=&page=0' . "\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . encrypt_url($_REQUEST["orders_id"]) . '&proc=View&searchcrit=&page=0' . "\" />";
echo "</noscript>";
exit;
