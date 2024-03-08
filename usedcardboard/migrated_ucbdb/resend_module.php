<?php  
require ("inc/header_session.php");

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

?>


<?php 
if ($_GET['start_date'] != 'action')
{
?>
<P><font face="arial" size="2">Are you sure you want to resend the <?php  echo $_GET['module_name']; ?> module?
<br><br>
<a href="resend_module.php?orders_id=<?php  echo $_GET['orders_id']; ?>&id=<?php  echo $_GET['id']; ?>&warehouse_id=<?php  echo $_GET['warehouse_id']; ?>&module_name=<?php  echo $_GET['module_name']; ?>&process=action">Yes</a>
<br><br>
<a href="javascript: history.go(-1)">No</a>

<?php  } ?>


<?php 
if ($_GET['start_date'] == 'action')
{


if ($_GET['warehouse_id'] == '99')
{
$sps_qry = "SELECT * FROM orders_sps WHERE orders_id = " . $_GET['orders_id'];
$sps_qry_result = db_query($sps_qry, db());
$sps_qry_result_rows = tep_db_num_rows($sps_qry_result);
while($sps_qry_result_array = array_shift($sps_qry_result))
{
if ($sps_qry_result_rows > 0)
{
$thirdparty_email = $sps_qry_result_array['order_string'];

$mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

$to = 'judykrasnow@usedcardboardboxes.com';

mail($to, "Urgent - Resed SPS Order", $thirdparty_email, $mailheadersadmin);
 
//mail('mdewan@tivex.com', 'mdewan@tivex.com', 'Smartpack Order Transmission', $thirdparty_email, STORE_OWNER, 'mdewan@tivex.com');

echo "<br><br><font face=\"arial\" size=\"2\">SPS Products resent.  The Email string was resent to Therasa.";
        $today = date("Ymd");
        $output = "<STRONG>ORDER MODULE RESENT</STRONG><br><br>";
        $output .= "The following SPS Module was resent: " . $_GET['module_name'];

        $commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
        $commqryrw = array_shift(db_query($commqry));
        $comm_type = $commqryrw["id"];

        $sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $_GET['orders_id'] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
        // echo "<BR>SQL: $sql3<BR>";
        $result3 = db_query($sql3,db() );
echo "<font face=\"arial\" size=\"2\"><br><br><a href=\"orders.php?id=" . $_GET['orders_id'] . "&proc=View\">Return to Order</a>";
exit;
}
}
}


$cancel_ord_qry = db_query("Update orders_active_export set setignore = 1 where id = ". $_GET['id'], db());

$ordersql = "SELECT * FROM orders WHERE orders_id=" . $_GET['orders_id'];
$order_result = db_query($ordersql,db() );
while ($myrowsel = array_shift($order_result)) 
{
$orders_id = $myrowsel["orders_id"];
$coupon_id = $myrowsel["coupon_id"];
$customers_id = $myrowsel["customers_id"];
$customers_name = $myrowsel["customers_name"];
$customers_company = $myrowsel["customers_company"];
$customers_street_address = $myrowsel["customers_street_address"];
$customers_street_address2 = $myrowsel["customers_street_address2"];
$customers_suburb = $myrowsel["customers_suburb"];
$customers_city = $myrowsel["customers_city"];
$customers_postcode = $myrowsel["customers_postcode"];
$customers_state = $myrowsel["customers_state"];
$customers_country = $myrowsel["customers_country"];
$customers_telephone = $myrowsel["customers_telephone"];
$customers_email_address = $myrowsel["customers_email_address"];
$customers_address_format_id = $myrowsel["customers_address_format_id"];
$is_pickup_call = $myrowsel["is_pickup_call"];
$delivery_name = $myrowsel["delivery_name"];
$delivery_company = $myrowsel["delivery_company"];
$delivery_apartment_no = $myrowsel["delivery_apartment_no"];
$delivery_street_address = $myrowsel["delivery_street_address"];
$delivery_street_address2 = $myrowsel["delivery_street_address2"];
$delivery_suburb = $myrowsel["delivery_suburb"];
$delivery_city = $myrowsel["delivery_city"];
$delivery_postcode = $myrowsel["delivery_postcode"];
$delivery_state = $myrowsel["delivery_state"];
$delivery_country = $myrowsel["delivery_country"];
$delivery_address_format_id = $myrowsel["delivery_address_format_id"];
$billing_name = $myrowsel["billing_name"];
$billing_company = $myrowsel["billing_company"];
$billing_street_address = $myrowsel["billing_street_address"];
$billing_suburb = $myrowsel["billing_suburb"];
$billing_city = $myrowsel["billing_city"];
$billing_postcode = $myrowsel["billing_postcode"];
$billing_state = $myrowsel["billing_state"];
$billing_country = $myrowsel["billing_country"];
$billing_address_format_id = $myrowsel["billing_address_format_id"];
$name_of_employee_helped = $myrowsel["name_of_employee_helped"];
$ups_signature = $myrowsel["ups_signature"];
$how_to_hear_about = $myrowsel["how_to_hear_about"];
$payment_method = $myrowsel["payment_method"];
$cc_type = $myrowsel["cc_type"];
$cc_owner = $myrowsel["cc_owner"];
$cc_number = $myrowsel["cc_number"];
$cc_expires = $myrowsel["cc_expires"];
$cc_cvv = $myrowsel["cc_cvv"];
$comment = $myrowsel["comment"];
$last_modified = $myrowsel["last_modified"];
$date_purchased = $myrowsel["date_purchased"];
$orders_status = $myrowsel["orders_status"];
$orders_date_finished = $myrowsel["orders_date_finished"];
$currency = $myrowsel["currency"];
$currency_value = $myrowsel["currency_value"];
$billing_street_address2 = $myrowsel["billing_street_address2"];
$cancel = $myrowsel["cancel"];
$site_referrer = $myrowsel["site_referrer"];
$site_ref_keyword = $myrowsel["site_ref_keyword"];
$site_hits_id = $myrowsel["site_hits_id"];
}



$shipzipcode = $delivery_postcode;

$zip_query = "SELECT W.warehouse_id, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip='".substr($delivery_postcode, 0, 3)."'";
$zip_row = array_shift(db_query($zip_query));
$warehouse_id = $zip_row["warehouse_id"];
$tbl_name = "orders_active_".str_replace(' ', '_', strtolower($zip_row["name"]));





$mod_qry = "SELECT * FROM module WHERE name LIKE '%" . $_GET["module_name"] . "%'";

//echo $mod_qry;
$mod_qry_result = db_query($mod_qry, db());
while($mod_qry_result_array = array_shift($mod_qry_result))
        {
        $length = $mod_qry_result_array['length1'];
        $weight = $mod_qry_result_array['weight'];
        $width = $mod_qry_result_array['width'];
        $height = $mod_qry_result_array['height'];
/*
echo $length;
echo $weight;
echo $width;
echo $height;
*/

        }

        
$statesmall = "";
$statefull = $delivery_state;
$querystatefull = "SELECT zone_code FROM zones WHERE zone_name = '" . $statefull . "'";
$querystatefull_result = db_query($querystatefull);
while($row = array_shift($querystatefull_result)){
	$statesmall = $row["zone_code"];	
}	

if ($statesmall == ""){
	$statesmall = $delivery_state;	
}


$query = "INSERT INTO ".$tbl_name." SET warehouse_id=".$warehouse_id.", orders_id='".$orders_id."', ups_shipping_release='".$ups_signature."'";
$query.= ", module_name='".addslashes($_GET["module_name"])."', weight='".$weight."', length1='".$length."', description='".addslashes($_GET["module_name"])."'";
$query.= ", width='".$width."', height='".$height."', reference='".$_GET["module_name"]."'";
$query.= ", shipping_name='".$delivery_name."', shipping_attention='".$delivery_company."', shipping_street1='".$delivery_street_address."', shipping_street2='".$delivery_street_address2."'";
$query.= ", shipping_city='".$delivery_city."', shipping_state='".$statesmall."', shipping_zip='".$delivery_postcode."'";
$query.= ", phone='".$customers_telephone."', email='".$customers_email_address."', qvnemail1='".$customers_email_address."', comments='".$comment."'";
db_query($query);
$ins_id = tep_db_insert_id();
 echo $query;
// echo "<br><br>";
echo "Module Scheduled to be resent.  The Record was Successfully Inserted";
        $today = date("Ymd");
        $output = "<STRONG>ORDER MODULE RESENT</STRONG><br><br>";
        $output .= "The following Module was resent: " . $_GET['module_name'];
        $output .= "<br>Database Info for Marcus or David: Row ID - " . $ins_id;
		
        $commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
        $commqryrw = array_shift(db_query($commqry));
        $comm_type = $commqryrw["id"];

        $sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $_GET['orders_id'] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
        // echo "<BR>SQL: $sql3<BR>";
        $result3 = db_query($sql3,db() );
echo "<font face=\"arial\" size=\"2\"><br><br><a href=\"orders.php?id=" . $_GET['orders_id'] . "&proc=View\">Return to Order</a>";
exit;

}

?>