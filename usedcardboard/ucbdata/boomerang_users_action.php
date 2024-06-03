<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "check_duplicate_email") {
	$user_email = $_REQUEST['user_email'];
	db();
	$user_data_qry = db_query("SELECT * FROM boomerang_usermaster where user_status = 1 && user_email = '" . $user_email . "'");
	echo tep_db_num_rows($user_data_qry);
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_user") {
	$user_name = $_REQUEST['user_name'];
	$user_email = $_REQUEST['user_email'];
	$user_password = $_REQUEST['user_password'];
	$companies = $_REQUEST['companies'];
	db();
	$insert_usr = db_query("INSERT INTO boomerang_usermaster (`user_name`,`password`,`activate_deactivate`,`user_email`,`user_name_bkp`,`user_block`,`user_status`,`created_by`,`created_on`)
		VALUES ('" . $user_name . "','" . base64_encode($user_password) . "','1','" . $user_email . "','" . $user_name . "','0',1,'" . $_COOKIE['b2b_id'] . "','" . date('Y-m-d H:i:s') . "')");
	$user_id = tep_db_insert_id();
	if ($user_id != "" && count($companies) > 0) {
		foreach ($companies as $company) {
			db();
			db_query("INSERT INTO boomerang_user_companies (`user_id`,`company_id`) VALUES ('" . $user_id . "','" . $company . "')");
			db_b2b();
			$select_sell_to_address = db_query("SELECT contact,email,address,address2,city,state,zip,country,phone,email,company FROM companyInfo where id = '" . $company . "' ORDER BY id DESC LIMIT 1");
			$sell_to_address = array_shift($select_sell_to_address);
			db();
			$insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
			VALUES ('".$user_id."',1,'" . $sell_to_address['contact']."','','" . $sell_to_address['company']."','" . $sell_to_address['country']."','" . $sell_to_address['address']."','" . $sell_to_address['address2']."'
			,'" . $sell_to_address['city']."','" . $sell_to_address['state']."','" . $sell_to_address['zip']."','" . $sell_to_address['phone']."','".$sell_to_address['email']."','".$company."','',0,'" . date('Y-m-d H:i:s') . "')";
			db_query($insert_qry);
		}
	}
	echo 1;
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "get_edit_user_data") {
	$user_id = $_REQUEST['user_id'];
	db();
	$user_data_qry = db_query("SELECT * FROM boomerang_usermaster where loginid = '" . $user_id . "'");
	$user_data = array_shift($user_data_qry);
	$password = $user_data['password'];
	$user_data['company_list'] = array();
	$select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $user_id . "'");
	if (tep_db_num_rows($select_user_companies) > 0) {
		while ($row1 = array_shift($select_user_companies)) {
			$user_data['company_list'][] = $row1['company_id'];
		}
	}

	$user_data['user_password'] = base64_decode($password);
	echo json_encode($user_data);
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "update_user") {
	$loginid = $_REQUEST['user_id'];
	$user_name = $_REQUEST['user_name'];
	$user_email = $_REQUEST['user_email'];
	$user_password = $_REQUEST['user_password'];
	$companies = isset($_REQUEST['companies']) ? $_REQUEST['companies'] : array();
	$activate_deactivate = $_REQUEST['activate_deactivate'];
	$user_block = $_REQUEST['user_block'];
	db();
	$update_user = db_query("UPDATE boomerang_usermaster SET `user_name` = '" . $user_name . "', `user_email` = '" . $user_email . "',
	`password` = '" . base64_encode($user_password) . "', `activate_deactivate`='" . $activate_deactivate . "',`user_block`='" . $user_block . "' WHERE loginid = '" . $loginid . "'");
	// Fetch the current company data
	$current_companies_query = db_query("SELECT company_id FROM boomerang_user_companies WHERE user_id = '" . $loginid . "'");
	$current_companies = array();
	while ($row = array_shift($current_companies_query)) {
		$current_companies[] = $row['company_id'];
	}
	if (count($companies) == 0) {
		db();
		db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $loginid . "'");
		db_query("DELETE FROM boomerang_user_addresses WHERE user_id = '" . $loginid . "' && company_id != 0");
	} else {
		// Compare the current data with the new data
		$companies_to_add = array_diff($companies, $current_companies);
		$companies_to_remove = array_diff($current_companies, $companies);
		// Update the database only if there's a difference
		if (!empty($companies_to_add) || !empty($companies_to_remove)) {
			// Remove the companies that are not in the new data
			foreach ($companies_to_remove as $company_id) {
				db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $loginid . "' AND company_id = '" . $company_id . "'");
				db_query("DELETE FROM boomerang_user_addresses WHERE user_id = '" . $loginid . "'  AND company_id = '" . $company_id . "'");
				
			}
			// Add the companies that are in the new data but not in the current data
			foreach ($companies_to_add as $company_id) {
				db_query("INSERT INTO boomerang_user_companies (user_id, company_id) VALUES ('" . $loginid . "', '" . $company_id . "')");
				db_b2b();
				$select_sell_to_address = db_query("SELECT contact,address,email,address2,city,state,zip,country,phone,email,company FROM companyInfo where id = '" . $company_id . "' ORDER BY id DESC LIMIT 1");
				$sell_to_address = array_shift($select_sell_to_address);
				db();
				$insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
				VALUES ('".$loginid."',1,'" . $sell_to_address['contact']."','','" . $sell_to_address['company']."','" . $sell_to_address['country']."','" . $sell_to_address['address']."','" . $sell_to_address['address2']."'
				,'" . $sell_to_address['city']."','" . $sell_to_address['state']."','" . $sell_to_address['zip']."','" . $sell_to_address['phone']."','" . $sell_to_address['email']."','".$company_id."','',0,'" . date('Y-m-d H:i:s') . "')";
				db_query($insert_qry);
			}
		}
	}
	echo 1;
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "delete_user") {
	$user_id = $_REQUEST['user_id'];
	db();
	$update_user = db_query("UPDATE boomerang_usermaster SET `user_status` = '0' WHERE loginid = '" . $user_id . "'");
	echo 1;
}
