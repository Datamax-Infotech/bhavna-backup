<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


$status = $_GET["status"];
$id = $_GET["id"];
if(isset($_REQUEST['UboxexTrack']) && $_REQUEST['UboxexTrack'] == 'yes'){
	$sql = "UPDATE ubox_order_fedex_details SET setignore = 1 where id = " . $id ;
	db_query($sql,db() );
}else{
        $sql = "update orders_active_export SET setignore = 1 where id = " . $id ;
        db_query($sql,db() );
}

echo "<DIV CLASS='SQL_RESULTS'>Record Updated<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: ups_status_report.php?status=' . $status . '&proc=View'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"ups_status_report.php?status=" . $status . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=ups_status_report.php?status=" . $status . "&proc=View\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');
?>