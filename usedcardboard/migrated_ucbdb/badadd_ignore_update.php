<?php 
	require ("inc/header_session.php");
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	
	$shipzipcode = ""; $customer_firstname = ""; $customer_company = ""; $customer_address = "";
	$customer_address2 = ""; $customer_city = ""; $statesmall = ""; $customer_postcode = ""; $cancel_order ="No";
	$customer_telephone = ""; $customer_email = ""; $order_comment = ""; $fedex_bad_add = 0; 
	$dt_view_qry = "Select * from orders where orders_id = '" . $_REQUEST["bad_add_orders_id"] . "'";
	$data_res = db_query($dt_view_qry, db() );
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

	$query_ins = "Update orders set fedex_validate_bad_add = 0, bad_add_ignore_by = '" . $_COOKIE['userinitials'] . "', bad_add_ignore_on = '" . date("Y-m-d H:i:s") . "' WHERE orders_id = '" . $_REQUEST["bad_add_orders_id"] . "'";
	$result_ins = db_query($query_ins, db());

	if ($cancel_order == "No") {		
	
		$shipzipcode = ""; $customer_firstname = ""; $customer_company = ""; $customer_address = "";
		$customer_address2 = ""; $customer_city = ""; $statesmall = ""; $customer_postcode = ""; 
		$customer_telephone = ""; $customer_email = ""; $order_comment = ""; 
		
		$dt_view_qry = "Select * from orders where orders_id = '" . $_REQUEST["bad_add_orders_id"] . "'";
		$data_res = db_query($dt_view_qry, db() );
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
		$rgp = array_shift(db_query($query));
		$arr_rgp = explode(',', $rgp["grp_prod_id"]);
		
		$query = "SELECT W.warehouse_id, W.kit_warehouseid, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip=?";
		$row = array_shift(db_query($query, array("s") , array(substr($shipzipcode, 0, 3))));
		$warehouse_id = $row["warehouse_id"];
		$kit_warehouseid = $row["kit_warehouseid"];
		$tbl_name = "orders_active_".str_replace(' ', '_', strtolower($row["name"]));
		
		//Delete order warehouse rows as it can add multiple rows
		$tblarry_str = "orders_active_ucb_atlanta,orders_active_ucb_dallas,orders_active_ucb_danville,orders_active_ucb_hannibal,orders_active_ucb_hunt_valley,orders_active_ucb_iowa,orders_active_ucb_los_angeles,orders_active_ucb_montreal,orders_active_ucb_philadelphia,orders_active_ucb_rochester,orders_active_ucb_salt_lake,orders_active_ucb_toronto, orders_active_ucb_phoenix, orders_active_ucb_evansville";
		$arr_warehouse = explode(",", $tblarry_str);
		foreach($arr_warehouse as $tbl_warehouse)
		{
			$dt_view_qry1 = "Delete from ".$tbl_warehouse." where orders_id = '" . $_REQUEST["bad_add_orders_id"] . "'";
			$data_res1 = db_query($dt_view_qry1, db() );
		}
				
		
		$dt_view_qry = "Select * from orders_products where orders_id = '" . $_REQUEST["bad_add_orders_id"] . "'";
		$data_res = db_query($dt_view_qry, db() );
		while ($product_details_tmp = array_shift($data_res)) {

			if ($product_details_tmp["products_id"] != ""){
				$ucb_prod_id = $product_details_tmp["products_id"];
			
				if(in_array($ucb_prod_id, $arr_rgp))
				{
					$rec_found = "no";
					$query_child = "Select orders_id from orders_sps where orders_id = ?";
					$res_child = db_query($query_child, array("i"), array($_REQUEST["bad_add_orders_id"]));
					while($row_mk_info = array_shift($res_child))
					{
						$rec_found = "yes";
					}
					
					$tmpName = $customer_firstname;				
					$thirdparty_mnsd = '"'.$_REQUEST["bad_add_orders_id"].'","'.$tmpName.'","'.$customer_company.'","","","'.$customer_address.'","'.$customer_address2.'","'.$customer_city.'","'.$statesmall.'","'.$customer_postcode.'","8882693788","UCB","sps_ups@usedcardboardboxes.com"';
					$thirdparty_mnsd .= ',"'.$products_name.'","'.$products_model.'",1"';
					$mid_thirdparty_mnsd = str_replace("<br>", "", "$thirdparty_mnsd"); 
					$new_thirdparty_mnsd = str_replace("'", "", "$mid_thirdparty_mnsd"); 

					if ($rec_found == "no") {
						$ttpp_query = "INSERT INTO orders_sps SET orders_id = ?, order_string = ?, shipping_name = ?, 
						shipping_street1 = ?, shipping_street2 = ?, shipping_city = ?, shipping_state = ?, shipping_zip = ?,
						phone = ?, email = ?, bill_to_name = ?, bill_to_street = ?, bill_to_city = ?,
						bill_to_state = ?, bill_to_country = ?, bill_to_zip = ?";
						//echo $ttpp_query . "<br>";
						db_query($ttpp_query , array("i","s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s") , array($_REQUEST["bad_add_orders_id"], $new_thirdparty_mnsd,
						$tmpName, $customer_address, $customer_address2, $customer_city, $statesmall, $customer_postcode,
						$customer_telephone, $customer_email, $bill_to_name, $bill_to_street, $bill_to_city, $bill_to_state, $bill_to_country, $bill_to_zip));
					}else{
						$ttpp_query = "Update orders_sps SET order_string = ?, shipping_name = ?, 
						shipping_street1 = ?, shipping_street2 = ?, shipping_city = ?, shipping_state = ?, shipping_zip = ?,
						phone = ?, email = ?, bill_to_name = ?, bill_to_street = ?, bill_to_city = ?,
						bill_to_state = ?, bill_to_country = ?, bill_to_zip = ? where orders_id = ? ";
						//echo $ttpp_query . "<br>";
						db_query($ttpp_query , array("s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s", "s","i") , array($new_thirdparty_mnsd,
						$tmpName, $customer_address, $customer_address2, $customer_city, $statesmall, $customer_postcode,
						$customer_telephone, $customer_email, $bill_to_name, $bill_to_street, $bill_to_city, $bill_to_state, $bill_to_country, $bill_to_zip, $_REQUEST["bad_add_orders_id"]));
					}					
				}
							
				if(!in_array($ucb_prod_id, $arr_rgp))
				{
					$product_id_new = $ucb_prod_id;
					$pord_order_id = ($pord_order_id == "")?$product_id_new:$pord_order_id.','.$product_id_new;

					$pr_qty = "SELECT products_quantity FROM orders_products WHERE orders_id=? AND products_id=?";
					$row_qty = array_shift(db_query($pr_qty, array("i","i"), array($_REQUEST["bad_add_orders_id"], $product_id_new)));
					$y=$row_qty["products_quantity"];
					for ($x=1; $x<=$y; $x++) 
					{
						$query = "SELECT kit_id FROM orders_products WHERE orders_id=? AND products_id=?";
						$row_kits = array_shift(db_query($query, array("i","i"), array($_REQUEST["bad_add_orders_id"],$product_id_new)));
						

						$query = "SELECT M.module_id, M.name, M.description, M.weight, M.length1, M.width, M.height, M.reference, M.tree_value, K.kit_id as kits_id, K.name as kits_name";
						$query.= " FROM module M INNER JOIN moduletokits MK ON M.module_id=MK.module_id";
						$query.= " INNER JOIN kits K ON MK.kit_id=K.kit_id WHERE MK.kit_id=?";
						
						$res_mk_info = db_query($query, array("i"), array($row_kits["kit_id"]));
						while($row_mk_info = array_shift($res_mk_info))
						{
							$trees_saved += $row_mk_info['tree_value'];// Line Added by devi for Tree Counter Update
							//###############################################//
							//Check the warehouse Rule
							$dest_tbl_name= "";
							$dest_warehouse_id = "";
							//$query = "SELECT * FROM wh_rule WHERE module_id='".$row_mk_info["module_id"]."' AND warehouse_id='$warehouse_id'";
							$query = "SELECT * FROM wh_rule WHERE module_id= ? AND warehouse_id=?";
							$rswc = db_query($query, array("i","s") , array($row_mk_info["module_id"], $warehouse_id));
							$num_rswc = tep_db_num_rows($rswc);
							if($num_rswc > 0)
							{
								$rwwc = array_shift($rswc);
								$dest_warehouse_id = $rwwc["warehouse_d_id"];
								//$query = "SELECT * FROM warehouse WHERE warehouse_id='$dest_warehouse_id'";
								$query = "SELECT * FROM warehouse WHERE warehouse_id=?";
								$rw = array_shift(db_query($query, array("s") , array($dest_warehouse_id)));
								$dest_tbl_name = "orders_active_".str_replace(' ', '_', strtolower($rw["name"]));

							}
							$dest_warehouse_id = ($dest_warehouse_id == "")?$warehouse_id:$dest_warehouse_id;
							$dest_tbl_name = ($dest_tbl_name == "")?$tbl_name:$dest_tbl_name;
							
							$query = "INSERT INTO ".$dest_tbl_name." SET warehouse_id=$dest_warehouse_id, orders_id=" . $_REQUEST["bad_add_orders_id"] .", product_id=$product_id_new, kit_id=".$row_mk_info["kits_id"].", module_id=".$row_mk_info["module_id"];
							$query.= ", module_name='".addslashes($row_mk_info["name"])."', description='".addslashes($row_mk_info["description"])."', weight='".$row_mk_info["weight"]."', length1='".$row_mk_info["length1"]."'";
							$query.= ", width='".$row_mk_info["width"]."', height='".$row_mk_info["height"]."', reference='".addslashes($row_mk_info["reference"])."', kits_name='".addslashes($row_mk_info["kits_name"])."'";
							$query.= ", shipping_name='".addslashes($customer_firstname)."', shipping_attention='".addslashes($customer_company)."', shipping_street1='".addslashes($customer_address)."', shipping_street2='".addslashes($customer_address2)."'";
							$query.= ", shipping_city='".addslashes($customer_city)."', shipping_state='".$statesmall."', shipping_zip='".$customer_postcode."', ups_shipping_release=''";
							$query.= ", phone='".$customer_telephone."', email='".$customer_email."', qvnemail1='".$customer_email."', comments='".addslashes($order_comment)."'";
							//echo $query . "<br>";
							db_query($query);
						}
					} 
				}
			}	
		}
	}
	
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"orders.php?id=" . $_REQUEST["bad_add_orders_id"] .'&proc=View&searchcrit=&page=0' . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $_REQUEST["bad_add_orders_id"] .'&proc=View&searchcrit=&page=0' . "\" />";
	echo "</noscript>"; exit;
	
?>
