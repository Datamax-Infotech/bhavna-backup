<?php
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
$res = db_query("UPDATE clientdash_favorite_items SET favItems = 0 WHERE id =" . $_REQUEST['favItemId']);
echo "true";
