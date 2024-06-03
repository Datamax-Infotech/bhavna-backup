<?
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

if ($_REQUEST["upd_action"] == "1") {
	$user_id = $_REQUEST['user_id'];
	$favB2bId = $_REQUEST['favB2bId'];
	$marked_by = $_COOKIE['b2b_id'];
	$sql = "SELECT fav_b2bid FROM boomerange_inventory_gaylords_favorite WHERE fav_b2bid = " . $favB2bId ." AND user_id = " . $user_id; 			
	
	$rec_found = "no";
	$boxes_query = db_query($sql, db());								
	while ($boxes_data = array_shift($boxes_query))  {							
		$rec_found = "yes";
	}
	db();
	if($rec_found == "no") {		
		$qry="INSERT INTO `boomerange_inventory_gaylords_favorite` (`user_id`,`fav_b2bid`,`fav_status`,`marked_by`,`created_on`) 
		VALUES ('".$user_id."','".$favB2bId."', 1, '".$_COOKIE['b2b_id']."', '".date('Y-m-d H:i:s')."')";
		$res=db_query($qry);
		echo "done";		
	}else{
		$qry = "UPDATE boomerange_inventory_gaylords_favorite SET favItems = 1 WHERE fav_b2bid = " . $favB2bId ."AND user_id = " . $user_id;
		$res=db_query($qry);
		echo "done";
	}				
}

if ($_REQUEST["upd_action"] == "2") {
	db();
	$sql = "DELETE FROM boomerange_inventory_gaylords_favorite WHERE fav_b2bid = " . $_REQUEST["favB2bId"] . " AND user_id =".$_REQUEST['user_id']; 				
	db_query($sql);	
	echo "done";
}	

?>