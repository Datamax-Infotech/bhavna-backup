<?php
require("inc/header_session.php");
//error_reporting(0);
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
//
$orders_id = decrypt_url($_REQUEST["orders_id"]);
$orid = decrypt_url($_REQUEST["orid"]);
db();
$cancel_ord_qry = db_query("Update orders_active_export set setignore = 1, cancel_flag='yes', cancel_flg_marked_by = '" . $_COOKIE['userinitials'] . "', cancel_flg_marked_on = '" . date("Y-m-d H:i:s") . "' where id=" . $orid);
if (empty($cancel_ord_qry)) {
	echo "yes";
}
