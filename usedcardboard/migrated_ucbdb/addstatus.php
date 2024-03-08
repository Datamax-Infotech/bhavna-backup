<?php 
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


$orders_id = $_POST["orders_id"];
	
db();

$today = date("Ymd"); 
$realtoday = date("m d Y"); 
$datewtime = date("F j, Y, g:i a"); 

//$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
//$commqryrw = array_shift(db_query($commqry, db()));
//$comm_type = $commqryrw["id"];  

$comm_type = 8;

if ($_POST["order_issue"] == '0') {
	$query1 = db_query('Update orders set order_issue = 1 where orders_id = ?', array("i") , array($_POST["orders_id"]));				
	
	$strQuery_upd = "Insert into b2c_order_issue (order_id, order_issue_start_date_time, order_issue_start_done_by, order_issue_start_notes, assigned_to) ";
	$strQuery_upd .= " select '" . $_POST["orders_id"]. "', '" . date("Y-m-d H:i:s") . "', '" . $_COOKIE['userinitials']. "', '" . str_replace("'", "\'" ,$_POST['orderproblem_text']) . "', '' ";
	
	db_query($strQuery_upd);
	
	//$output = "<b>Order Problem</b>.  This order has been assigned to " . $_POST[assigned_to] . " by " . $_POST[assigned_by] . " on " . $datewtime;

	$msg_trans = "System generated log - Action taken on 'Mark as Order issue' on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";
	$msg_trans .= "Action : 'Mark as Order issue'.";	
	//	$msg_trans .= "Action : 'UnMark as Order issue'";	
	$msg_trans .= "<br>Notes: " . $_POST["orderproblem_text"];	

	$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( ?, ?, ?, ? ,?)";
	$result3 = db_query($sql3, array("i", "s","s", "s", "s") , array($_POST["orders_id"] , $comm_type, str_replace("'", "\'" ,$msg_trans) , $today , $_POST['assigned_by']));

	$ee_query = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST["assigned_to"] . "'";
	$com_ee_query = array_shift(db_query($ee_query, db()));
	$com_email = $com_ee_query["email"];  


	$to = $com_email;
	 
		$str_email = "The following Order has been assigned to you:\n\n";
		$str_email.= "Order ID:  ".$_POST["orders_id"]." \n";
		$str_email.= "\n\n";
		$str_email.= "You may click the link below to view the order.\n\n";
		$str_email.= "http://b2c.usedcardboardboxes.com/orders.php?id=" . $_POST["orders_id"] . "&proc=View";

		$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

	 mail($to,"Urgent - Order With Problem",$str_email,$mailheadersadmin);

	$sqlissue = "SELECT * FROM ucbdb_issue WHERE orders_id = " . $orders_id;
	$resissue = db_query($sqlissue,db() );
	$resissuecount = tep_db_num_rows($resissue); 

	if ($resissuecount == 0) {
	$ins_sql = "INSERT INTO ucbdb_issue (orders_id, issue, assigned_to, assigned_by, when_assigned) VALUES ( '" . $_POST["orders_id"] . "','Order Problem','" . $_POST['assigned_to'] . "','" . $_POST['assigned_by'] . "','" . $datewtime . "')";
	db_query($ins_sql, db()); 
	}

	if ($resissuecount != 0) {
	$upd_sql = "UPDATE ucbdb_issue SET issue = 'Order Problem', assigned_to = '" . $_POST["assigned_to"] . "', assigned_by = '" . $_POST["assigned_by"] . "' WHERE orders_id = " . $_POST["orders_id"];
	db_query($upd_sql, db());
	}
}

if ($_POST["order_issue"] == '1') {
	$query1 = db_query('Update orders set order_issue = 0 where orders_id = ?', array("i") , array($_POST["orders_id"]));				
	
	$no_of_days = 0; $last_order_id = 0;
	$sql = "SELECT * from b2c_order_issue where order_id = " . $_POST["orders_id"] . " and (order_issue_end_done_by is null or order_issue_end_done_by = '')";
	$result = db_query($sql );
	while ($row = array_shift($result)) {
		$last_order_id = $row["unqid"];
		
		$datetime1 = new DateTime($row["order_issue_start_date_time"]); 
		$datetime2 = new DateTime(); 
		  
		$interval = date_diff($datetime1, $datetime2); 
		  
		$no_of_days = $interval->format('%a'); 			
	}
	
	$strQuery_upd = "Update b2c_order_issue set order_issue_end_date_time = '" . date("Y-m-d H:i:s") . "', order_issue_end_done_by = '" . $_POST['assigned_by'] . "', order_issue_end_notes = '" . str_replace("'", "\'" ,$_POST['orderproblem_text']) . "', order_issue_no_of_days = '" . $no_of_days . "' ";
	$strQuery_upd .= " , order_issue_estimated_cost =  '" . $_POST["txt_stimated_cost"] . "', order_issue_reason_id =  '" . str_replace("'", "\'" ,$_POST["orderissue_reason"]) . "'  where order_id = '" . $_POST["orders_id"] . "' and unqid = " . $last_order_id;
	
	db_query($strQuery_upd);

	//$output = "<b>Order Problem resolved</b>.  This order issue has been resolved.  " . $_POST[assigned_by] . " has updated the status and set the order to OK on " . $datewtime;

	$msg_trans = "System generated log - Action taken on 'Mark as Order issue' on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";
	$msg_trans .= "Action : 'UnMark as Order issue'";	
	$msg_trans .= "<br>Notes: " . $_POST["orderproblem_text"];	

	$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( ?, ?, ?, ? ,?)";
	$result3 = db_query($sql3, array("i", "s","s", "s", "s") , array($_POST["orders_id"] , $comm_type, str_replace("'", "\'" ,$msg_trans) , $today , $_POST['assigned_by']));

	$ee_query = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['assigned_to'] . "'";
	$com_ee_query = array_shift(db_query($ee_query, db()));
	$com_email = $com_ee_query["email"];  

	$upd_sql = "UPDATE ucbdb_issue SET issue = 'Order Problem  resolved', assigned_by = '" . $_POST['assigned_by'] . "' WHERE orders_id = " . $_POST["orders_id"];
	db_query($upd_sql, db());

}

echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"orders.php?id=" . $_POST["orders_id"] . "&proc=View\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $_POST["orders_id"] . "&proc=View\" />";
	echo "</noscript>"; exit;
//header('Location: orders.php?id=' . $_POST["orders_id"] . '&proc=View');
?>