<? 
/*
File Name: update_setup_hide_flg.php
Page created By: Bhavna Patidar
Page created On: 05-04-2022
Last Modified On: 
Last Modified By: Bhavna Patidar
Change History:
Date           By            Description
==================================================================================================================
05-04-2022	  Bhavna		This page is crested to save records for a company. Here the section id 7 is 
							defined so that the records in boomeang client side data can be show or hide. This 
							value or flage determined that the column is going to show the records or hide.
==================================================================================================================
*/
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

ini_set("display_errors", "1");
error_reporting(E_ALL);

$rec_found = "no";
$get_boxes_query = db_query("SELECT * FROM boomerang_user_section_details where user_id = '" . $_REQUEST['user_id'] . "' and section_id = 7", db());	
while ($boxes = array_shift($get_boxes_query)) 	 {	
	$rec_found = "yes";
}

if ($rec_found == "no"){
	$qry = "Insert into boomerang_user_section_details (user_id, section_id, activate_deactivate) select '" . $_REQUEST['user_id'] . "' , 7, '" . $_REQUEST["setuphide_flg"] . "'";	
	$res = db_query($qry, db());		
}else{
	$qry = "Update boomerang_user_section_details set activate_deactivate = '" . $_REQUEST["setuphide_flg"] . "' where user_id = '" . $_REQUEST['user_id'] . "' and section_id = 7";	
	$res = db_query($qry, db());		
}

$rec_found = "no";
$get_boxes_query = db_query("SELECT * FROM boomerang_user_section_details where user_id = '" . $_REQUEST['user_id'] . "' and section_id = 8", db());	
while ($boxes = array_shift($get_boxes_query))  {	$rec_found = "yes"; }

if ($rec_found == "no"){
	$qry = "Insert into boomerang_user_section_details (user_id, section_id, activate_deactivate) select '" . $_REQUEST['user_id'] . "' , 8, '" . $_REQUEST["boxprofileinv_flg"] . "'";			
}else{
	$qry = "Update boomerang_user_section_details set activate_deactivate = '" . $_REQUEST["boxprofileinv_flg"] . "' where user_id = '" . $_REQUEST['user_id'] . "' and section_id = 8";			
}
$res = db_query($qry, db());

?>