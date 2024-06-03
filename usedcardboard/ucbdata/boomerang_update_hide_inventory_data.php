<?
/*
File Name: update_hide_inventory_data.php
Page created By: Bhavna Patidar
Page created On: 19-04-2022
Last Modified On: 
Last Modified By: Bhavna Patidar
Change History:
Date           		By         	   	Description
=============================================================================================================
05-28-2024		Bhavna		Show list of inventory which is to be hide.

=============================================================================================================
*/

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

if ($_REQUEST["upd_action"] == "1") {
	$company_id = $_REQUEST["compId"];
	$favB2bId = $_REQUEST["favB2bId"];
	$user_id = $_REQUEST['user_id'];
	db();
	$sql = "SELECT hide_b2bid FROM boomerang_inventory_hide_items WHERE hide_b2bid = " . $favB2bId. " AND user_id = " . $user_id; 			
	
	$rec_found = "no";
	$boxes_query = db_query($sql, db());								
	while ($boxes_data = array_shift($boxes_query))  {							
		$rec_found = "yes";
	}
	if($rec_found == "no") {		
		$qry="INSERT INTO `boomerang_inventory_hide_items` (`hide_b2bid`,`user_id`,`hideItems`) VALUES ('".$favB2bId."','".$user_id."',1 )";
		$res=db_query($qry);
		echo "done";		
	}else{
		$qry = "UPDATE boomerang_inventory_hide_items SET hideItems = 1 WHERE  hide_b2bid = " . $favB2bId ." AND user_id = " . $user_id;
		$res=db_query($qry);
		echo "done";
	}				
}

if ($_REQUEST["upd_action"] == "2") {
	$sql = "DELETE FROM boomerang_inventory_hide_items WHERE  hide_b2bid = " . $_REQUEST['favB2bId'] ." AND user_id = " . $_REQUEST['user_id']; 				
	db_query($sql,db() );	
	echo "done";
}	

?>