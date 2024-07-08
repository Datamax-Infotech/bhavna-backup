<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

if (isset($_REQUEST['form_action']) && $_REQUEST['form_action'] == "edit_profile") {
	$user_id = $_REQUEST['user_id'];
	$user_name = $_REQUEST['user_name'];
	$user_email = $_REQUEST['user_email'];
	$user_last_name = $_REQUEST['user_last_name'];
	$user_phone = $_REQUEST['user_phone'];
	$user_company = $_REQUEST['user_company'];
	db();
	$update_user = db_query("UPDATE boomerang_usermaster SET `user_name` = '" . $user_name . "', `user_email` = '" . $user_email . "', 
	`user_last_name` = '" . $user_last_name . "', `user_phone` = '" . $user_phone . "', `user_company` = '" . $user_company . "'
	WHERE loginid = '" . $user_id . "'");
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
	
	$add_set_as_def = 0;
	if (isset($_REQUEST['add_set_as_def'])){
		$add_set_as_def = 1;
		$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 0 WHERE user_id = '".$user_id."'");
	}
	
    $insert_qry = "INSERT INTO boomerang_user_addresses (user_id,status,first_name,last_name,company,country,addressline1,addressline2,city,state, zip,mobile_no,email,company_id,dock_hours,mark_default,created_on) 
    VALUES ('".$user_id."',1,'" . $_REQUEST['first_name']."','" . $_REQUEST['last_name']."','" . $_REQUEST['company']."','" . $_REQUEST['country']."','" . $_REQUEST['addressline1']."','" . $_REQUEST['addressline2']."'
    ,'" . $_REQUEST['city']."','" . $_REQUEST['state']."','" . $_REQUEST['zip']."','" . $_REQUEST['mobile_no']."','" . $_REQUEST['email']."',0,'".$_REQUEST['dock_hours']."','".$add_set_as_def."','" . date('Y-m-d H:i:s') . "')";
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

	$update_qry = "UPDATE boomerang_user_addresses SET first_name = '".$_REQUEST['first_name']."',last_name = '".$_REQUEST['last_name']."',company = '".$_REQUEST['company']."',country = '".$_REQUEST['country']."',addressline1 = '".$_REQUEST['addressline1']."',
	addressline2 = '".$_REQUEST['addressline2']."',city = '".$_REQUEST['city']."',state = '".$_REQUEST['state']."',zip = '".$_REQUEST['zip']."',mobile_no = '".$_REQUEST['mobile_no']."',email = '".$_REQUEST['email']."', 
	dock_hours = '".$_REQUEST['dock_hours']."' WHERE id = '".$_REQUEST['address_id']."'";
	db_query($update_qry);
	echo 1;
}

if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="mark_default"){
	db();
	$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 0 WHERE user_id = '".$_COOKIE['loginid']."'");
	$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 1 WHERE id = '".$_REQUEST['address_id']."'");
	echo 1;
}

if(isset($_REQUEST['form_action']) && $_REQUEST['form_action']=="delete_address"){
	db();
	$user_id = 0;
	$chk_Del = db_query("SELECT user_id FROM boomerang_user_addresses where mark_default = 1 and id = '".$_REQUEST['address_id']."'");
	while ($rowchk_Del = array_shift($chk_Del)) {
		$user_id = $rowchk_Del["user_id"];
	}

	$delete_qry = db_query("DELETE FROM boomerang_user_addresses WHERE id = '".$_REQUEST['address_id']."'");
	
	if ($user_id > 0){
		$rec_id = 0;
		$chk_Del = db_query("SELECT id FROM boomerang_user_addresses where user_id = '".$user_id."' order by id asc limit 1");
		while ($rowchk_Del = array_shift($chk_Del)) {
			$rec_id = $rowchk_Del["id"];
		}
		
		if ($rec_id > 0){
			$update_qry = db_query("UPDATE boomerang_user_addresses SET mark_default = 1 WHERE id = '".$rec_id."'");
		}
	}
	

	echo 1;
}