<?php
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}
} else {
	require("inc/header_session_client.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db();

$bid = $_REQUEST["bid"];
$fav_match_id = $_REQUEST["fav_match_id"];
$boxtype = $_REQUEST["boxtype"];
//
//
$qry = "Update `clientdash_favorite_items` set favItems = 0 where compid=" . $fav_match_id . " and fav_b2bid=" . $bid;
//
$res = db_query($qry);
echo "done";
