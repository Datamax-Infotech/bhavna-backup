<?php
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
?>

<?
if ($_REQUEST["chkval"] == "1") { $clientdash_flg = 1; } else { $clientdash_flg = 0; }
//$strQuery = "Update clientdashboard_usermaster set user_name = '". $_REQUEST["clientdash_username_edit"]. "' , password = '" . $_REQUEST["clientdash_pwd_edit"] . "', activate_deactivate = " . $clientdash_flg . " where loginid = " . $_REQUEST["loginid"];

$strQuery = "Update clientdashboard_usermaster set user_name = '". $_REQUEST["usernm"]. "' , password = '" . $_REQUEST["pwd"] . "', client_email = '" . $_REQUEST["clientdash_eml"] . "', activate_deactivate = " . $clientdash_flg . " where loginid = " . $_REQUEST["loginid"];
//echo $strQuery;
$result = db_query($strQuery, db());

redirect($_SERVER['HTTP_REFERER']);
?>


