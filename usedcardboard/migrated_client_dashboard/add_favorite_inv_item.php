<?php
//ini_set("display_errors", "0");
//error_reporting(E_ALL);
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}	
}else {
	require ("inc/header_session_client.php");
}

require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");
/*echo "<pre>";print_r($_REQUEST);echo "</pre>";
exit();
*/
db();
$fav_qty_avail=$_REQUEST["fav_qty_avail"];
$fav_estimated_next_load=$_REQUEST["fav_estimated_next_load"];
$fav_expected_loads_per_mo=$_REQUEST["fav_expected_loads_per_mo"];
$fav_boxes_per_trailer=$_REQUEST["fav_boxes_per_trailer"];
$fav_fob=$_REQUEST["fav_fob"];
$fav_miles=$_REQUEST["fav_miles"];
$fav_bl=$_REQUEST["fav_bl"];
$fav_bw=$_REQUEST["fav_bw"];
$fav_bh=$_REQUEST["fav_bh"];
$fav_walls=$_REQUEST["fav_walls"];
$fav_desc=$_REQUEST["fav_desc"];
$fav_shipfrom=$_REQUEST["fav_shipfrom"];
$bid=$_REQUEST["bid"];
$fav_match_id=$_REQUEST["fav_match_id"];
$boxtype=$_REQUEST["boxtype"];

$qry="INSERT INTO `clientdash_favorite_items` (`compid`,`fav_b2bid`, `fav_qty_avail`, `fav_estimated_next_load`, `fav_expected_loads_per_mo`, `fav_boxes_per_trailer`, `fav_fob`, `fav_bl`, `fav_bw`, `fav_bh`, `fav_walls`, `fav_desc`, `fav_shipfrom`, `boxtype`, `fav_miles`) VALUES ('".$fav_match_id."','".$bid."', '".$fav_qty_avail."', '".$fav_estimated_next_load."', '".$fav_expected_loads_per_mo."', '".$fav_boxes_per_trailer."', '".$fav_fob."', '".$fav_bl."', '".$fav_bw."', '".$fav_bh."', '".$fav_walls."', '".$fav_desc."', '".$fav_shipfrom."', '".$boxtype."', '".$fav_miles."' )";
$res=db_query($qry);
echo "done";
//
?>
