<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "edit_profile") {
	$user_id = $_REQUEST['user_id'];
	$user_name = $_REQUEST['user_name'];
	$user_email = $_REQUEST['user_email'];
	$companies = isset($_REQUEST['companies']) ? $_REQUEST['companies'] : array();
	db();
	$update_user = db_query("UPDATE boomerang_usermaster SET `user_name` = '" . $user_name . "', `user_email` = '" . $user_email . "'
	WHERE loginid = '" . $user_id . "'");
	// Fetch the current company data
	$current_companies_query = db_query("SELECT company_id FROM boomerang_user_companies WHERE user_id = '" . $user_id . "'");
	$current_companies = array();
	while ($row = array_shift($current_companies_query)) {
		$current_companies[] = $row['company_id'];
	}
	if (count($companies) == 0) {
		db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $user_id . "'");
	} else {
		// Compare the current data with the new data
		$companies_to_add = array_diff($companies, $current_companies);
		$companies_to_remove = array_diff($current_companies, $companies);
		// Update the database only if there's a difference
		if (!empty($companies_to_add) || !empty($companies_to_remove)) {
			// Remove the companies that are not in the new data
			foreach ($companies_to_remove as $company_id) {
				db_query("DELETE FROM boomerang_user_companies WHERE user_id = '" . $user_id . "' AND company_id = '" . $company_id . "'");
			}
			// Add the companies that are in the new data but not in the current data
			foreach ($companies_to_add as $company_id) {
				db_query("INSERT INTO boomerang_user_companies (user_id, company_id) VALUES ('" . $user_id . "', '" . $company_id . "')");
			}
		}
	}
	echo 1;
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "change_password"){
    $user_id = $_REQUEST['user_id'];
    $password = $_REQUEST['new_password'];
    db();
    //echo "UPDATE boomerang_usermaster SET `password` = '" . base64_encode($password) . "' WHERE loginid = '" . $user_id . "'";
    $update_user = db_query("UPDATE boomerang_usermaster SET `password` = '" . base64_encode($password) . "' WHERE loginid = '" . $user_id . "'");
    echo 1;
}
if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "add_address"){
    $user_id = $_COOKIE['loginid'];
    db();
    $insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,name,company,country,addressline1,addressline2,city,state, zip,mobile_no,mark_default) 
    VALUES ('".$user_id."',1,'" . $_REQUEST['name']."','" . $_REQUEST['company']."','" . $_REQUEST['country']."','" . $_REQUEST['addressline1']."','" . $_REQUEST['addressline2']."'
    ,'" . $_REQUEST['city']."','" . $_REQUEST['state']."','" . $_REQUEST['zip']."','" . $_REQUEST['mobile_no']."',0)";
    db_query($insert_qry);
    echo 1;
}
if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="get_edit_address"){
	db();
	$select_add = db_query("SELECT * FROM boomerang_user_addresses WHERE id = '".$_REQUEST['address_id']."'");
	$address = array_shift($select_add);
	echo json_encode($address);
}
if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="update_address"){
	db();
	$update_qry = "UPDATE boomerang_user_addresses SET name = '".$_REQUEST['name']."',company = '".$_REQUEST['company']."',country = '".$_REQUEST['country']."',addressline1 = '".$_REQUEST['addressline1']."',
	addressline2 = '".$_REQUEST['addressline2']."',city = '".$_REQUEST['city']."',state = '".$_REQUEST['state']."',zip = '".$_REQUEST['zip']."',mobile_no = '".$_REQUEST['mobile_no']."' WHERE id = '".$_REQUEST['address_id']."'";
	db_query($update_qry);
	echo 1;
}
if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="get_edit_address"){
	db();
	$select_add = db_query("SELECT * FROM boomerang_user_addresses WHERE id = '".$_REQUEST['address_id']."'");
	$address = array_shift($select_add);
	echo json_encode($address);
}

if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="mark_default"){
	db();
	$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 0 WHERE user_id = '".$_COOKIE['loginid']."'");
	$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 1 WHERE id = '".$_REQUEST['address_id']."'");
	echo 1;
}

if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="delete_address"){
	db();
	$delete_qry = db_query("DELETE FROM boomerang_user_addresses WHERE id = '".$_REQUEST['address_id']."'");

	echo 1;
}