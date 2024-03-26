<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
db();
$order_id = "";
if(count($_POST))
{
	$arr_data = array();
	foreach($_POST['process'] as $process_id)
	{
		$id = ${"id_".$process_id};
		$order_id = ${"orders_id_".$process_id};
		$amount = ${"amount_".$process_id};
		if(isset($arr_data[$order_id]))
			$amount+= $arr_data[$order_id];
		$arr_data[$order_id] = $amount;	
		$cc_number = ${"cc_number_".$process_id};
		$cc_expires = ${"cc_expires_".$process_id};
		$auth_trans_id = ${"auth_trans_id_".$process_id};						
		$employee = ${"employee_".$process_id};		
	}
	foreach($arr_data as $order_id=>$amount)
	{
		//// Echo Out the Values for Testing
		echo "Row ID :".$id.'<br>';
		echo "Order ID :".$order_id.'<br>';
		echo "Amount :".$amount.'<br>';
		echo "Card Number :".$cc_number.'<br>';
		echo "Expiration :".$cc_expires.'<br>';
		echo "Auth.Net Trans ID :".$auth_trans_id.'<br>';						
		echo "<hr width='100%'>";
		// Echo Out the Values for Testing

$auth_net_login_id			= "6Tg3s2VHA";
$auth_net_tran_key			= "638s776J25vaHHvj";

$submit_data 				= array
(
	"x_login"				=> $auth_net_login_id,
	"x_version"				=> "3.1",
	"x_delim_char"			=> ",",
	"x_delim_data"			=> "TRUE",
	"x_type"				=> "CREDIT",
	"x_method"				=> "CC",
 	"x_tran_key"			=> $auth_net_tran_key,
 	"x_relay_response"		=> "FALSE",
	"x_card_num"			=> "$cc_number",
	"x_exp_date"			=> "$cc_expires",
	"x_amount"				=> "$amount",
	"x_trans_id"			=> "$auth_trans_id",	
);
//unset($data);	
$data = "";
// Concatenate - I love that word - the submission data and put into variable $data
foreach($submit_data as $key => $value) {
    $data .= $key . '=' . urlencode(preg_replace('/,/', '', $value)) . '&';
}
// Remove the last "&" from the string
$data = substr($data, 0, -1);
//echo $data;
unset($response);

$url = 'https://secure.authorize.net/gateway/transact.dll'; 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);

curl_setopt($ch, CURLOPT_VERBOSE, 0);

curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

$authorize = curl_exec($ch);

curl_close ($ch);

//$response = split('\,', $authorize);
$response = explode(',', $authorize);
//	  print_r(array_values($response));
$response_code = explode(',', $response[0]);
$response_text = explode(',', $response[3]);
$response_trans_id = explode(',', $response[6]);
$x_response_code = $response_code[0];
$x_response_text = $response_text[0];
$x_response_trans_id = $response_trans_id[0];	  
$_SESSION['x_response_trans_id'] = $x_response_trans_id;
// If the response code is not 1 (approved) then redirect back to the payment page with the appropriate error message
if ($x_response_code != '1') 
echo  $x_response_text; 
echo "<br><br><STRONG>THIS ONLY HAPPENS ON TEST SERVER</STRONG>";
echo "<br><br>";
echo "<strong>SUBMISSTION STRING</strong>";
echo "<br><br>";
echo $data;
echo "<br><br>";
echo "<strong>RESPONSE STRING</strong>";
echo "<br><br>";
print_r($response);
echo "<br><br>";

//Insert these values
$ins_sql = "insert into auth_net (orders_id, trans_id, type) values (" . $order_id . "," . $x_response_trans_id . ", 'Credit')";
db_query($ins_sql);

// Now that Auth.Net Processing is doe, do all the inserts.

$today = date("Ymd"); 

//$sql = "UPDATE ucbdb_credits SET pending = 'Processed' WHERE orders_id = " . $order_id; 
$sql = "UPDATE ucbdb_credits SET pending = 'Processed' WHERE id = " . $process_id;
db_query($sql);
$output = "<STRONG>CREDIT PROCESSED</STRONG><br> ";
$output .= "Total:  ";
$output .= number_format(($amount), 2);
$output .= "<br>Auth.Net ID:  ";
$output .= $x_response_trans_id;

$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$res_commqry = db_query($commqry);
$commqryrw = array_shift($res_commqry);
$comm_type = $commqryrw["id"];  

$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $order_id . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
//echo "<BR>SQL: $sql<BR>";
$result3 = db_query($sql3);
}

}
echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: orders.php?id=' . encrypt_url($order_id) . '&proc=View'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"orders.php?id=" . encrypt_url($order_id) . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . encrypt_url($order_id) . "&proc=View\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');

?>