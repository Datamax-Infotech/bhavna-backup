<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$txt_other = $_REQUEST["txt_other"];
$qry =  "Insert into ucbdb_customer_asking_trans (reason_id,  reason_text, user_intitial, reason_dt) ";
$qry .= " values ('" . $_REQUEST["res_id"] . "', '" . $txt_other . "',  '" . $_COOKIE['userinitials'] . "', '" . Date('Y-m-d H:i:s') . "') ";
//echo $qry ;
$res = db_query($qry);
redirect($_SERVER['HTTP_REFERER']);
