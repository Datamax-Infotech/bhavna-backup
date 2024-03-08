<?php 
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

function redirect($a)
	{
	if (!headers_sent()){   
        header('Location: ' . $a); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"". $a. "\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $a . "\" />";
        echo "</noscript>"; exit;
}
}

	$qry =  "Insert into ucbdb_customer_asking_trans (reason_id,  reason_text, user_intitial, reason_dt) ";
	$qry .= " values ('" .$_REQUEST["res_id"]. "', '" . $txt_other . "',  '" . $_COOKIE['userinitials'] . "', '" . Date('Y-m-d H:i:s') . "') ";
	//echo $qry ;
	$res = db_query($qry , db() );
	redirect($_SERVER['HTTP_REFERER']);
?>