<?php 
	require ("inc/header_session.php");

	define("DEBUG", 1);

	// Set to 0 once you're ready to go live
	define("USE_SANDBOX", 0);

	define('TABLE_ADDRESS_BOOK', 'address_book');
	define('TABLE_ADDRESS_FORMAT', 'address_format');
	define('TABLE_BANNERS', 'banners');
	define('TABLE_BANNERS_HISTORY', 'banners_history');
	define('TABLE_CATEGORIES', 'categories');
	define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
	define('TABLE_CONFIGURATION', 'configuration');
	define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
	define('TABLE_COUNTER', 'counter');
	define('TABLE_COUNTER_HISTORY', 'counter_history');
	define('TABLE_COUNTRIES', 'countries');
	define('TABLE_CURRENCIES', 'currencies');
	define('TABLE_CUSTOMERS', 'customers');
	define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
	define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
	define('TABLE_CUSTOMERS_INFO', 'customers_info');
	//kgt - discount coupons
	define('TABLE_DISCOUNT_COUPONS', 'discount_coupons');
	define('TABLE_DISCOUNT_COUPONS_TO_ORDERS', 'discount_coupons_to_orders');
	//end kgt - discount coupons
	define('TABLE_LANGUAGES', 'languages');
	define('TABLE_MANUFACTURERS', 'manufacturers');
	define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
	define('TABLE_ORDERS', 'orders');
	define('TABLE_ORDERS_PRODUCTS', 'orders_products');
	define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
	define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
	define('TABLE_ORDERS_STATUS', 'orders_status');
	define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
	define('TABLE_ORDERS_TOTAL', 'orders_total');
	define('TABLE_PRODUCTS', 'products');
	define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
	define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
	define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
	define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
	define('TABLE_PRODUCTS_OPTIONS', 'products_options');
	define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
	define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
	define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
	define('TABLE_REVIEWS', 'reviews');
	define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
	define('TABLE_SESSIONS', 'sessions');
	define('TABLE_SPECIALS', 'specials');
	define('TABLE_TAX_CLASS', 'tax_class');
	define('TABLE_TAX_RATES', 'tax_rates');
	define('TABLE_GEO_ZONES', 'geo_zones');
	define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
	define('TABLE_WHOS_ONLINE', 'whos_online');
	define('TABLE_ZONES', 'zones');
	//For Easy Coupon
	define('TABLE_COUPONS', 'coupons');

	//Devi - Gift Certificate
	define('TABLE_GIFT_CERTIFICATE', 'gift_certificate');
	define('TABLE_GIFT_CERTIFICATE_TO_ORDERS', 'gift_certificate_to_orders');
	//Devi - Gift Certificate

	define('EMAIL_WARNING', 'ATTENTION: This email address was given to us by someone who had visited our well known online store. If this was not done by you please email us at  ' . STORE_OWNER_EMAIL_ADDRESS . ' Thank you for shopping with us and have a great day.');
	// PWA EOF
	define('EMAIL_TEXT_SUBJECT', 'Order Process');
	define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
	define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
	define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
	define('EMAIL_TEXT_PRODUCTS', 'Products');
	define('EMAIL_TEXT_SUBTOTAL', 'Sub-Total:');
	define('EMAIL_TEXT_TAX', 'Tax:        ');
	define('EMAIL_TEXT_SHIPPING', 'Shipping: ');
	define('EMAIL_TEXT_TOTAL', 'Total:    ');
	define('EMAIL_TEXT_DELIVERY_ADDRESS', 'Delivery Address');
	define('EMAIL_TEXT_BILLING_ADDRESS', 'Billing Address');
	define('EMAIL_TEXT_PAYMENT_METHOD', 'Payment Method');

	define('EMAIL_SEPARATOR', '------------------------------------------------------');
	define('TEXT_EMAIL_VIA', 'via');

@setlocale(LC_TIME, 'en_US.ISO_8859-1');
?>

<script>

	function check_form()
	{
		var error = 0;
		var checkstring = "Please complete required fields:\n";
			
		if (document.frmb2bgiftcard.entry_firstname.value == "")
		{
			checkstring = checkstring + "Name\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_company.value == "")
		{
			checkstring = checkstring + "Company Name\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_address1.value == "")
		{
			checkstring = checkstring + "Address\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_city.value == "")
		{
			checkstring = checkstring + "City\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_zipcode.value == "")
		{
			checkstring = checkstring + "Zip Code\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_state.value == "")
		{
			checkstring = checkstring + "State\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.entry_email.value == "")
		{
			checkstring = checkstring + "Email\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.order_coupon.value == "")
		{
			checkstring = checkstring + "Gift Voucher\n";
			error = 1;
		}
		if (document.frmb2bgiftcard.order_coupon_amt.value == "")
		{
			checkstring = checkstring + "Gift amount\n";
			error = 1;
		}
		
		if (error == 1 )
		{
			alert(checkstring);
			return false;
		}
	}	
</script>
<?php 
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
	
require('../includes/functions/general.php');
require('../includes/functions/html_output.php');

require('../includes/classes/mime.php');
require('../includes/classes/email.php');
require('../includes/classes/currencies.php');
require('../includes/classes/order_papal.php');
require("../../securedata/config_main.php");  

	function send_phpemil_new($from_email, $to_email, $subject, $eml_body)
	{
		global $phpmailer; // define the global variable
		if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) { // check if $phpmailer object of class PHPMailer exists
			require_once '../includes/class.phpmailer.php';
			require_once '../includes/class.smtp.php';

			$phpmailer = new PHPMailer( true );
		}
  	   try {
			$phpmailer->isSMTP();
			$phpmailer->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);			
			$phpmailer->Host = "107.180.85.1"; //smtp.exg7.exghost.com
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port = "25";
			$phpmailer->Username = "ucbemail@UsedCardboardBoxes.com";
			$phpmailer->Password = "boomerang123";
			
			$phpmailer->From = $from_email;
			$phpmailer->FromName = $from_email;
			$phpmailer->Subject = $subject; // subject
			$phpmailer->SingleTo = true;
			$phpmailer->ContentType = 'text/html'; // Content Type
			$phpmailer->IsHTML( true );
			$phpmailer->CharSet = 'utf-8';
			$phpmailer->SMTPDebug = 0;
			$phpmailer->AddAddress($to_email); // the recipient's address
			$phpmailer->Body = $eml_body;
			
			$phpmailer->Send(); // the last thing - send the email
		}
		catch (phpmailerException $e)
		{
			 $msg = "Email Delivery failed -" .  $e->errorMessage();
		} 		
		//$phpmailer->AddAttachment(getcwd() . '/plugins/' . $plugin_name . '.zip', $plugin_name . '.zip'); // add the attachment

		
	}

  function tep_mail_new($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer'));

    // Build the text version
    $text = strip_tags($email_text);
	$message->add_html($email_text, $text);

    // Send message
    $message->build_message();

    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }
  
  //tep_db_connect("localhost", "usedcard_prod", "WowNoIts@Attac#45421", "usedcard_production");
  tep_db_connect("localhost", DB_SERVER_USERNAME, DB_SERVER_PASSWORD, "usedcard_production");
  
  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
    global $$link;
	//$server = "ucbprodtest.db.10286395.hostedresource.com";
	$server = "localhost";
    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
		if ($$link) mysql_select_db($database);
    } else {
	  $$link = new mysqli($server, $username, $password, $database);
    }


    return $$link;
  }

  function get_result_new( $Statement ) {
	$RESULT = array();
	$Statement->store_result();
	for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
		$Metadata = $Statement->result_metadata();
		$PARAMS = array();
		while ( $Field = $Metadata->fetch_field() ) {
			$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
		}
		call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
		$Statement->fetch();
	}
	return $RESULT;
 }
 
  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysqli_close($$link);
  }

  function tep_db_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_db_query($query, $param_type = array(), $param_data = array()) {
	$link = 'db_link';
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

	//$tmppos = strpos($query, "where");
	//if ($tmppos != false)
	//{ 	
		/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
		$a_param_type = $param_type; $a_bind_params = $param_data;
		$a_params = array();
		 
		$param_type = '';
		$n = count($a_param_type);
		for($i = 0; $i < $n; $i++) {
		  $param_type .= $a_param_type[$i];
		}
		 
		/* with call_user_func_array, array params must be passed by reference */
		$a_params[] = & $param_type;
		for($i = 0; $i < $n; $i++) {
		  /* with call_user_func_array, array params must be passed by reference */
		  $a_params[] = & $a_bind_params[$i];
		}

		$resultnew = $$link->prepare($query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link));
		//$resultnew->bind_param('i',$page_id);
		if ($param_type){
		
			call_user_func_array(array($resultnew, 'bind_param'), $a_params);
		}
		$resultnew->execute();
		$result = get_result_new($resultnew);

	//}else {
	//	$resultnew = $$link->prepare($query) or tep_db_error($query, mysqli_errno(), mysqli_error());
	//	$resultnew->execute();
	//	$result = $resultnew->get_result();
	//}

    //$result = tep_db_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
       $result_error = mysqli_error($$link);
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_db_fetch_array($db_query) {
	//return array_shift($db_query);
    //return mysqli_fetch_array($db_query, MYSQLI_ASSOC);
  }
  
  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
  }

  function tep_db_num_rows($db_query) {
    return sizeof($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_db_insert_id() {
	$link = 'db_link';
    global $$link;
    return mysqli_insert_id($$link);
  }

  function tep_db_free_result($db_query) {
    return mysqli_free_result($db_query);
  }

  function tep_db_fetch_fields($db_query) {
    return mysqli_fetch_field($db_query);
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db_link') {
    global $$link;

    if (function_exists('$mysqli->real_escape_string')) {
      //return mysql_real_escape_string($string, $$link);
	  return $mysqli->real_escape_string($string);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_real_escape_string($string);
    }

    return addslashes($string);
  }

  function tep_db_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_db_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }


// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));

//if (strcmp ($res, "VERIFIED") == 0) {
	// check that txn_id has not been previously processed
	// check that payment_amount/payment_currency are correct

	// assign posted variables to local variables
$tmp_log = "";

if ($_POST["btnsubmit"] == "Submit") {
	
	$dbuser		= DB_SERVER_USERNAME; 
	$dbserver	= "localhost"; 
	$dbpass		= DB_SERVER_PASSWORD; 
	$dbname		= "usedcard_production";

	$db_conn = mysql_connect($dbserver, $dbuser, $dbpass)
	or die ("UNABLE TO CONNECT TO DATABASE");

	mysql_select_db($dbname)
	or die ("UNABLE TO SELECT DATABASE");
		
	$flg_recfound = "no";
	$passv_coupon_id = 0;
	$passv_is_pickup_call = ""; $passv_ups_signature = ""; $sess_site_referrer = ""; $sess_site_ref_keyword = ""; $sess_site_hits_id = "";
	$f_site_referrer = "";
	$f_site_ref_keyword = "";
	$f_site_hits_ins_id = "";

	$STORE_OWNER = ""; $STORE_OWNER_EMAIL_ADDRESS = ""; $DOWNLOAD_ENABLED = ""; $STOCK_LIMITED = ""; $SEND_EXTRA_ORDER_EMAILS_TO = "";
	$result = tep_db_query("SELECT configuration_value FROM configuration where configuration_key = 'SEND_EXTRA_ORDER_EMAILS_TO'");
	while ($myrowsel = array_shift($result)) {
		$SEND_EXTRA_ORDER_EMAILS_TO = $myrowsel["configuration_value"];
	}

	$result = tep_db_query("SELECT configuration_value FROM configuration where configuration_key = 'STORE_OWNER'");
	while ($myrowsel = array_shift($result)) {
		$STORE_OWNER = $myrowsel["configuration_value"];
	}

	$result = tep_db_query("SELECT configuration_value FROM configuration where configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'");
	while ($myrowsel = array_shift($result)) {
		$STORE_OWNER_EMAIL_ADDRESS = $myrowsel["configuration_value"];
	}

	$result = tep_db_query("SELECT configuration_value FROM configuration where configuration_key = 'DOWNLOAD_ENABLED'");
	while ($myrowsel = array_shift($result)) {
		$DOWNLOAD_ENABLED = $myrowsel["configuration_value"];
	}

	$result = tep_db_query("SELECT configuration_value FROM configuration where configuration_key = 'STOCK_LIMITED'");
	while ($myrowsel = array_shift($result)) {
		$STOCK_LIMITED = $myrowsel["configuration_value"];
	}

	$products_model = ""; $products_name = ""; $products_price = 0; $final_price = ""; $products_tax = ""; $products_weight = "";
	$orders_products_query = tep_db_query("SELECT * FROM products inner join products_description on products_description.products_id = products.products_id where products.products_id = 28 and language_id = 1");
	while ($orders_products = array_shift($orders_products_query)) {
		$products_model = $orders_products['products_model']; 
		$products_name = $orders_products['products_name']; 
		$products_price = $orders_products['products_price']; 
		$final_price = $orders_products['products_price']; 
		$products_tax = $orders_products['tax']; 
		$products_weight = $orders_products['products_weight']; 
	}

	$ord_comment = str_replace(chr(34), ' ', $_POST['entry_notes']); 
    $total_amount = 0;
	  
	$cust_firstname = $_POST['entry_firstname'];	
	$cust_company = $_POST['entry_company'];	
	$cust_address = $_POST['entry_address1'];	
	$cust_address2 = $_POST['entry_address2'];	
	$cust_city = $_POST['entry_city'];	
	$cust_postcode = $_POST['entry_zipcode'];	
	$cust_state = $_POST['entry_state'];	
	$cust_telephone = $_POST['entry_telephone'];	
	$cust_email = $_POST['entry_email'];	
	if ($_POST['order_coupon_amt'] > 0)
	{
		$total_amount = $products_price - $_POST['order_coupon_amt'];
	}	
	$coupon = $_POST['order_coupon'];
	
	  $cc_last4 = "";
	  $sql_data_array = array('coupon_id' => $passv_coupon_id,
							  'customers_id' => 0,
							  'customers_name' => $cust_firstname,
							  'customers_company' => $cust_company,
							  'customers_street_address' => $cust_address,
							  'customers_street_address2' => $cust_address2,
							  'customers_suburb' => "",
							  'customers_city' => $cust_city,
							  'customers_postcode' => $cust_postcode, 
							  'customers_state' => $cust_state, 
							  'customers_country' => 'United States', 
							  'customers_telephone' => $cust_telephone, 
							  'customers_email_address' => $cust_email,
							  'customers_address_format_id' => 2, 
							  'is_pickup_call' => 'N', 
							  'delivery_name' => $cust_firstname,
							  'delivery_company' => $cust_company,
							  'delivery_apartment_no' => "" , 
							  'delivery_street_address' => $cust_address,
							  'delivery_street_address2' => $cust_address2, 
							  'delivery_suburb' => "", 
							  'delivery_city' => $cust_city,
							  'delivery_postcode' => $cust_postcode, 
							  'delivery_state' => $cust_state, 
							  'delivery_country' => 'United States', 
							  'delivery_address_format_id' => 2, 
							  'name_of_employee_helped' => '', 
							  'ups_signature' => 'Y', 
							  'how_to_hear_about' => '', 
							  'billing_name' => '', 
							  'billing_company' => '',
							  'billing_street_address' => '', 
							  'billing_street_address2' => '',
							  'billing_suburb' => '', 
							  'billing_city' => '', 
							  'billing_postcode' => '', 
							  'billing_state' => '', 
							  'billing_country' => 'United States', 
							  'billing_address_format_id' => 2, 
							  'payment_method' => 'Credit Card', 
							  'cc_type' => '', 
							  'cc_owner' => '', 
							  'cc_number' => '', 
							  'cc_expires' => '', 
							  'comment' => '', 
							  'date_purchased' => 'now()', 
							  'site_referrer' => $f_site_referrer, 
							  'site_ref_keyword' => $f_site_ref_keyword, 
							  'site_hits_id' => $f_site_hits_ins_id, 
							  'orders_status' => 2, 
							  'paypal_pre_orderid' => '',  
							  'currency' => 'USD', 
							  'currency_value' => '1.000000');

	  tep_db_perform("orders", $sql_data_array);
	  $insert_id = tep_db_insert_id();

	//if($total_amount > 0)	{
		//tep_db_query("insert into auth_net (orders_id, trans_id ) values (?, ?)", array("i","s") , array($insert_id,$_POST['txn_id']));
	//}
	  
	//  ADD BILL TO THIRD PARTY - Confirmed working with David

	$shipzipcode = $cust_postcode;
	//$query = "SELECT W.warehouse_id, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip='".substr($shipzipcode, 0, 3)."'";
	$query = "SELECT W.warehouse_id, W.kit_warehouseid, name FROM warehouse W INNER JOIN zipcodes Z ON W.warehouse_id=Z.warehouse_id WHERE Z.zip=?";
	$row = array_shift(tep_db_query($query, array("s") , array(substr($shipzipcode, 0, 3))));
	$warehouse_id = $row["warehouse_id"];
	$kit_warehouseid = $row["kit_warehouseid"];
	
	$tbl_name = "orders_active_".str_replace(' ', '_', strtolower($row["name"]));

	$sql_data_array = array('orders_id' => $insert_id,
							'title' => 'Sub-Total:',
							'text' => '$' . number_format($products_price,2),
							'value' => $products_price, 
							'class' => 'ot_subtotal', 
							'sort_order' => 1);
	tep_db_perform("orders_total", $sql_data_array);

	$sql_data_array = array('orders_id' => $insert_id,
							'title' => 'Gift Voucher ' . $_POST["order_coupon"] . ":",
							'text' => '<font color="red">-$' . $_POST["order_coupon_amt"] . '</font>',
							'value' => '-' . $_POST["order_coupon_amt"], 
							'class' => 'ot_gift_voucher', 
							'sort_order' => 2);
	tep_db_perform("orders_total", $sql_data_array);

	$sql_data_array = array('orders_id' => $insert_id,
							'title' => 'Total:',
							'text' => '$' . $total_amount,
							'value' => $total_amount, 
							'class' => 'ot_total', 
							'sort_order' => 4);
	tep_db_perform("orders_total", $sql_data_array);
	  
	$customer_notification = '1';
	$sql_data_array = array('orders_id' => $insert_id, 
						  'orders_status_id' => 1, 
						  'date_added' => 'now()', 
						  'customer_notified' => $customer_notification,
						  'comments' => $ord_comment);
	tep_db_perform("orders_status_history", $sql_data_array);
	  
	//kgt - discount coupons
	//if( $coupon != '' ) {
	 // $sql_data_array = array('coupons_id' => $coupon, 'orders_id' => $insert_id );
	  //tep_db_perform( "discount_coupons_to_orders", $sql_data_array );
	//}
	//end kgt - discount coupons

	//Devi - Gift Voucher Discount
	//if( $order_papal->info['gift_cert_code'] != "" && $order_papal->info['gc_discount_value'] > 0 )
	if( $coupon != "" && $_POST['order_coupon_amt'] > 0 )
	{
	  $sql_data_array = array( 'coupons_id' => $coupon, 'orders_id' => $insert_id , 'discount_value'=>$_POST['order_coupon_amt'], 'entry_date'=>date('Y-m-d h:i:s'));
	  tep_db_perform("gift_certificate_to_orders", $sql_data_array );

	  //Update the Available Balance
	  //tep_db_query("UPDATE ". "gift_certificate" ." SET coupons_discount_percent=(coupons_discount_percent-".$order_papal->info['gc_discount_value'].") WHERE coupons_id='".$order_papal->info['gift_cert_code']."'");
	  tep_db_query("UPDATE ". "gift_certificate" ." SET coupons_discount_percent=(coupons_discount_percent-?) WHERE coupons_id=?", array("d","s") , array($_POST['order_coupon_amt'], $coupon));
	  //-----------------------------------
	}
	//Devi - Gift Voucher Discount

	$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63)";
	$rgp = array_shift(tep_db_query($query));
	$arr_rgp = explode(',', $rgp["grp_prod_id"]);
	$thirdparty_prod = "";

	// initialized for the email confirmation
	  $products_ordered = '';
	  $subtotal = 0;
	  $total_tax = 0;
	

	if ($STOCK_LIMITED == 'true') {
	  $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = 28");
	  if (tep_db_num_rows($stock_query) > 0) {
		$stock_values = array_shift($stock_query);

		if (($DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
		  $stock_left = $stock_values['products_quantity'] - 1;
		} else {
		  $stock_left = $stock_values['products_quantity'];
		}
		
		tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = ? where products_id = ?", array("i") , array($stock_left));

		if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
			tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = 28");
		}
	  }
	}

// Update products_ordered (for bestsellers list)
	tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + 1 where products_id = 28");

	$tmpprod_qty = 1;
	if ($tmpprod_qty > 0) {
		$query_main1 = "select products_id, products_kit_item_id, how_many from products_kit_item_website_prod_rel where products_id = 28 order by unqid";
		
		$result_main1 = tep_db_query($query_main1);
		while($row_main1 = array_shift($result_main1))
		{
			$query_main2 = "insert into products_kit_item_inventory (qty, products_kit_item_id, warehouse_id, last_updated_on, updated_by, in_out_flg) values(-" . $tmpprod_qty * $row_main1["how_many"] . ", " . $row_main1["products_kit_item_id"] . ", " . $kit_warehouseid . ", '" . date("Y-m-d H:i:s") . "', 'B2C', 1)";
			tep_db_query($query_main2);
		}	

	}		

	$sql_data_array = array('orders_id' => $insert_id, 
							'products_id' => 28, 
							'products_model' => $products_model, 
							'products_name' => $products_name, 
							'products_price' => $products_price, 
							'final_price' => $final_price, 
							'products_tax' => $products_tax, 
							'products_quantity' => 1);
	tep_db_perform("orders_products", $sql_data_array);
	$order_products_id = tep_db_insert_id();

	$products_ordered_attributes = '';
	
//------insert customer choosen option eof ----
	$total_weight += (1 * $products_weight);
	$total_tax += tep_calculate_tax($total_products_price, $products_tax) * 1;
	$total_cost += $total_products_price;
	$tmptax = $products_tax;
	$tmptax = round($tmptax,2);

	$products_ordered .= '1 x ' . $products_name . ' (' . $products_model . ') = $' . round($final_price,2) . " " . "\n";
	
	if(in_array(28, $arr_rgp))
	{
		$subtotal+= $currencies->get_price($final_price, $tmptax, 1);
		$item_price = $currencies->get_price($final_price, $tmptax, 1);
		$thirdparty_prod.= ',"BEGIN_ITEM","'.$products_model.'","'.$final_price.'",1,"'.$products_name.'","END_ITEM"';
		$qrystatefull = "SELECT zone_code FROM zones WHERE zone_name = ?";		
		//$row = array_shift(tep_db_query($qrystatefull));	
		$row = array_shift(tep_db_query($qrystatefull, array("s") , array($cust_state)));	
		
		$statecode = $row["zone_code"];		
		//$tmpName = format_name($cust_firstname);				
		$tmpName = $cust_firstname;				
		$thirdparty_mnsd = '"'.$insert_id.'","'.$tmpName.'","'.$cust_company.'","","","'.$cust_address.'","'.$cust_address2.'","'.$cust_city.'","'.$statecode.'","'.$cust_postcode.'","8882693788","UCB","sps_ups@usedcardboardboxes.com"';
		$thirdparty_mnsd .= ',"'.$products_name.'","'.$products_model.'",1"';
		$mid_thirdparty_mnsd = str_replace("<br>", "", "$thirdparty_mnsd"); 
		$new_thirdparty_mnsd = str_replace("'", "", "$mid_thirdparty_mnsd"); 

		//$ttpp_query = "INSERT INTO orders_sps SET orders_id = '" . $insert_id . "', order_string = '" . $new_thirdparty_mnsd . "'";
		$ttpp_query = "INSERT INTO orders_sps SET orders_id = ?, order_string = ?";
		//tep_db_query($ttpp_query);
		tep_db_query($ttpp_query , array("i","s") , array($insert_id, $new_thirdparty_mnsd));
		
	}

	if($thirdparty_prod != "")
	{
		//$qrystatefull = "SELECT zone_code FROM zones WHERE zone_name = '" . $cust_state . "'";
		$qrystatefull = "SELECT zone_code FROM zones WHERE zone_name = ?";
		//$row = array_shift(tep_db_query($qrystatefull));
		$row = array_shift(tep_db_query($qrystatefull, array("s"), array($cust_state)));
		$statecode = $row["zone_code"];

		//$tmpName = format_name($cust_firstname);
		$tmpName = $cust_firstname;
		$thirdparty_email = '"'.$insert_id.'","'.date('m/d/Y H:i:s').'","'.$cust_company.'","'.$tmpName.'","","'.$cust_address.'","'.$cust_address2.'","'.$cust_city.'","'.$statecode.'","'.$cust_postcode.'","sps_ups@usedcardboardboxes.com","8882693788","'.$subtotal.'","0","0","'.$subtotal.'","Free Ground","'.$ord_comment.'"';
		$thirdparty_email.= $thirdparty_prod;

		$mid_thirdparty_email = str_replace("<br>", "", "$thirdparty_email"); 

		$mailheadersadmin = "MIME-Version: 1.0\r\n";
		$mailheadersadmin .= "From: UsedCardboardBoxes.com <spsorders@usedcardboardboxes.com>\n";
		$mailheadersadmin .= "Content-Type: multipart/alternative; boundary = $boundary\r\n";
		$mailheadersadmin .= "\n--$boundary\n"; // beginning \n added to separate previous content
		$mailheadersadmin .= "Content-type: text/plain; charset=iso-8859-1\r\n";

		mail("spsorders@usedcardboardboxes.com","Smartpack Order Transmission",$mid_thirdparty_email,$mailheadersadmin);
		//mail("prasad@extractinfo.com","Smartpack Order Transmission",$mid_thirdparty_email,$mailheadersadmin);

		$new_thirdparty_email = str_replace("'", "", "$mid_thirdparty_email"); 
	}

	  $email_order = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td colspan=\"2\"><p align=\"center\"><a href=\"http://www.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"http://www.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td colspan=\"2\"><p align=\"center\"><font face=\"arial\" size=\"2\"><a href=\"http://www.usedcardboardboxes.com/index.php\">Home</a> | <a href=\"http://www.usedcardboardboxes.com/search_trackingno.php\">Track Your Order</a> | <a href=\"http://www.usedcardboardboxes.com/moving_resources.php\">Moving Resources</a></font></p><br></td></tr><tr><td width=\"23\"><p> </p></td><td width=\"682\"><br>";

	  $email_order .= "<font face=\"arial\" size=\"2\">DO NOT REPLY TO THIS EMAIL; IT IS UNMONITORED MAILBOX. IF YOU NEED TO CONTACT US, PLEASE VISIT: https://www.usedcardboardboxes.com/contact-us.php \n\nThanks for helping keep the trees in the forest and the boxes off our streets!\n\nThe order below has been received and is being processed.  Your confirmation number is ".$insert_id."\n\n";

	  $email_order .= "SAVE THIS RECEIPT!  After your order is picked up from our warehouse, you can track the progress of your order here:  http://www.usedcardboardboxes.com/search_trackingno.php<br><br><div align=\"center\"><br><a href=\"http://www.whitefence.com/WebObjects/WhiteFence.woa/wa/cm?id=1076517\"><img src=\"http://www.usedcardboardboxes.com/images/used-cardboard-boxes-white-fence-banner.jpg\" alt=\"Used Cardboard Boxes White Fence Banner\" border=\"0\" width=\"583\" height=\"116\"></a></div><p>";

	  $email_order .= /* STORE_NAME . "\n" . */ EMAIL_SEPARATOR . "\n" . 
					 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
					 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

	  if ($ord_comment) {
		$email_order .= tep_db_output($ord_comment) . "\n\n";
	  }
	  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
					  EMAIL_SEPARATOR . "\n" . 
					  str_replace("<br>", " ", $products_ordered) .
	//				  $products_ordered . 
					  EMAIL_SEPARATOR . "\n";
					  
		$email_order .= 'Sub-Total: $' . $products_price . "\n";
		$email_order .= 'Total: $' . $total_amount . "\n";

		$email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n";

		$email_order .= $cust_firstname."\n";
		$email_order .= $cust_address."\n";
		if($cust_address2 != "")
		{
			$email_order .= $cust_address2."\n";
		}
		$email_order .= $cust_city.", ";
		$email_order .= $cust_state."  ";
		$email_order .= $cust_postcode."\n\n";

		$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n";
		//$email_order .= tep_address_label($customer_id, $billto, 0, '', "\n") . "\n\n";
		$email_order .= $cust_company."\n";
		$email_order .= $cust_firstname."\n";
		$email_order .= $cust_address."\n";
		if($cust_address2 != "")
		{
			$email_order .= $cust_address2."\n";
		}
		$email_order .= $cust_city.", ";
		$email_order .= $cust_state."  ";
		$email_order .= $cust_postcode."\n";
		$email_order .= $cust_telephone."\n";
		$email_order .= $cust_email."\n\n";

		$email_order .= 'Tell a friend to use Box Bucks Discount Code: "ORDER" and we\'ll give them $1 off their order!'."\n\n";
		$email_order .= "Help us improve!  Please take 30 seconds to fill out our Customer Satisfaction Survey so we can continue to provide you and all future customers with excellent service!  Thanks!\n\n";
		$email_order .= "Click here to complete our survey:  http://www.usedcardboardboxes.com/survey.php?orderid=" . $insert_id . "\n\n";

		$query = "SELECT * FROM page_text WHERE page_id=26";
		$row= array_shift(tep_db_query($query));
		$email_order.= "\n==================================================================\n\n";
		$email_order.= str_replace("\n", "\n\n", strip_tags($row["page_text"]))."\n\n";

		$email_order .= "		</font></td></tr><tr><td colspan=\"2\"><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://www.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr><tr><td width=\"23\"><p>&nbsp; </p></td><td width=\"682\"><p>&nbsp; </p></td></tr></table></body></html>";
		
		//Mail to Store owner
		tep_mail_new($cust_firstname, "customerservice@usedcardboardboxes.com", "Your UsedCardboardBoxes.com Order Receipt", $email_order, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);
		//tep_mail_new($cust_firstname, "prasad@extractinfo.com", "Your UsedCardboardBoxes.com Order Receipt", $email_order, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);

		//Mail to Customer
		send_phpemil_new("customerservice@UsedCardboardBoxes.com", $cust_email, "Your UsedCardboardBoxes.com Order Receipt", str_replace("\n", "<br>", $email_order));
		//send_phpemil_new("prasad@extractinfo.com", $cust_email, "Your UsedCardboardBoxes.com Order Receipt", str_replace("\n", "<br>", $email_order));

		// send emails to other people
		if ($SEND_EXTRA_ORDER_EMAILS_TO != '')
		{
			tep_mail_new('', $SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);
			//tep_mail_new('', "prasad@extractinfo.com", EMAIL_TEXT_SUBJECT, $email_order, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);
		}

	// Update kits 
    tep_db_query("UPDATE orders_products p INNER JOIN products pp ON p.products_id = pp.products_id SET p.kit_id = pp.kit_id where orders_id = ?" , array("i") , array($insert_id));

	// Pull State Abbreviation
	// This is done becasue osCommerce uses full state and WorldShip needs abbreviation

	//$statefull = $order_papal->delivery['state'];
	$statefull = $cust_state;
	$querystatefull = "SELECT zone_code FROM zones WHERE zone_name = ?";
	$row = array_shift(tep_db_query($querystatefull, array("s") , array($statefull)));
	$statesmall = ($row["zone_code"]);
	$pord_order_id = "";
	//  ADD BILL TO THIRD PARTY - Confirmed with David
	$trees_saved = 0; // Line Added by devi for Tree Counter Update
	  //UPS CODE 
	  
		$pr_id = 28;
		if(!in_array($pr_id, $arr_rgp))
		{
			$pord_order_id = ($pord_order_id == "")?$pr_id:$pord_order_id.','.$pr_id;
			
			$pr_qty = "SELECT products_quantity FROM orders_products WHERE orders_id=? AND products_id=?";
			$row_qty = array_shift(tep_db_query($pr_qty, array("i","i"), array($insert_id,$pr_id)));
			$y=$row_qty["products_quantity"];
			for ($x=1; $x<=$y; $x++) 
			{

				//$query = "SELECT kit_id FROM orders_products WHERE orders_id=$insert_id AND products_id=$pr_id";
				$query = "SELECT kit_id FROM orders_products WHERE orders_id=? AND products_id=?";
				$row_kits = array_shift(tep_db_query($query, array("i","i"), array($insert_id,$pr_id)));
				
				$query = "SELECT M.module_id, M.name, M.description, M.weight, M.length1, M.width, M.height, M.reference, M.tree_value, K.kit_id as kits_id, K.name as kits_name";
				$query.= " FROM module M INNER JOIN moduletokits MK ON M.module_id=MK.module_id";
				$query.= " INNER JOIN kits K ON MK.kit_id=K.kit_id WHERE MK.kit_id=?";

				$res_mk_info = tep_db_query($query, array("i"), array($row_kits["kit_id"]));
				while($row_mk_info = array_shift($res_mk_info))
				{
					$trees_saved += $row_mk_info['tree_value'];// Line Added by devi for Tree Counter Update
					//###############################################//
					//Check the warehouse Rule
					$dest_tbl_name= "";
					$dest_warehouse_id = "";
					//$query = "SELECT * FROM wh_rule WHERE module_id='".$row_mk_info["module_id"]."' AND warehouse_id='$warehouse_id'";
					$query = "SELECT * FROM wh_rule WHERE module_id= ? AND warehouse_id=?";
					$rswc = tep_db_query($query, array("i","s") , array($row_mk_info["module_id"], $warehouse_id));
					$num_rswc = tep_db_num_rows($rswc);
					if($num_rswc > 0)
					{
						$rwwc = array_shift($rswc);
						$dest_warehouse_id = $rwwc["warehouse_d_id"];
						//$query = "SELECT * FROM warehouse WHERE warehouse_id='$dest_warehouse_id'";
						$query = "SELECT * FROM warehouse WHERE warehouse_id=?";
						$rw = array_shift(tep_db_query($query, array("s") , array($dest_warehouse_id)));
						$dest_tbl_name = "orders_active_".str_replace(' ', '_', strtolower($rw["name"]));

					}
					$dest_warehouse_id = ($dest_warehouse_id == "")?$warehouse_id:$dest_warehouse_id;
					$dest_tbl_name = ($dest_tbl_name == "")?$tbl_name:$dest_tbl_name;
					//###############################################//
					//$arr_warehouse = array('orders_active_ucb_los_angeles', 'orders_active_ucb_rochester', 'orders_active_ucb_salt_lake', 'orders_active_ucb_atlanta', 'orders_active_ucb_dallas', 'orders_active_ucb_danville', 'orders_active_ucb_iowa', 'orders_active_ucb_montreal', 'orders_active_ucb_toronto');
					//foreach($arr_warehouse as $tbl_warehouse)
					//{
						$query = "INSERT INTO ".$dest_tbl_name." SET warehouse_id=$dest_warehouse_id, orders_id=$insert_id, product_id=$pr_id, kit_id=".$row_mk_info["kits_id"].", module_id=".$row_mk_info["module_id"];
						$query.= ", module_name='".addslashes($row_mk_info["name"])."', description='".addslashes($row_mk_info["description"])."', weight='".$row_mk_info["weight"]."', length1='".$row_mk_info["length1"]."'";
						$query.= ", width='".$row_mk_info["width"]."', height='".$row_mk_info["height"]."', reference='".addslashes($row_mk_info["reference"])."', kits_name='".addslashes($row_mk_info["kits_name"])."'";
						$query.= ", shipping_name='".addslashes($cust_firstname)."', shipping_attention='".addslashes($cust_company)."', shipping_street1='".addslashes($cust_address)."', shipping_street2='".addslashes($cust_address2)."'";
						$query.= ", shipping_city='".addslashes($cust_city)."', shipping_state='".$statesmall."', shipping_zip='".$cust_postcode."', ups_shipping_release=''";
						$query.= ", phone='".$cust_telephone."', email='".$cust_email."', qvnemail1='".$cust_email."', comments='".addslashes($ord_comment)."'";
						tep_db_query($query);
						
					//}
				}
			} 
		}

		//Update Treee Counter // Line Added by devi for Tree Counter Update
		$query = "UPDATE tree_counter  SET trees_saved=trees_saved +". $trees_saved ." WHERE tree_index=0";
		tep_db_query($query);
	  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


	//  Add Tips Email
	$tip_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td colspan=\"2\"><p align=\"center\"><img width=\"650\" height=\"166\" src=\"http://www.usedcardboardboxes.com/images/ucb-banner1_free.jpg\"></p></td></tr><tr><td width=\"23\" valign=\"top\"><p> </p></td><td width=\"650\"><p><br><font face=\"arial\" size=\"2\">From the team at UsedCardboardBoxes.com, we would like to thank you for choosing the low-cost, earth-friendly approach. We have been helping people move since 2002 and have developed some great tips for moving as well as some partnerships with other great companies that can help make your move A LOT easier!</font></p><ul><li><font face=\"arial\" size=\"2\"><strong>Pack Heavy - Pack Light</strong> - Pack heavy items in small boxes and lighter items in larger boxes.  Never pack a box heavier than you (or the mover) would want to pickup. <br><br></font></li><li><font face=\"arial\" size=\"2\"><strong>Protect Your Memories</strong> - If it is irreplaceable, take it with you in the car. But if you do decide to pack framed photos or art, place sheets or blankets between them for added protection.</font><font face=\"arial\" size=\"2\"><br><br></font></li><li><font face=\"arial\" size=\"2\"><a href=\"http://www.usedcardboardboxes.com/static_page.php?id=12#24_tape\"><strong>Loading Tape Guns</strong></a> - With practice, it takes only a few seconds.  It is easy once you get the hang of it.</font><font face=\"arial\" size=\"2\"><br><br></font></li>";

	$tip_email .= "<li><font face=\"arial\" size=\"2\"><strong><a href=\"https://moversguide.usps.com/mgo/disclaimer\">Change your address online</a></strong> - Don't miss important mail. Change your address online. It's quick and easy!<br><br></font></li><li><font face=\"arial\" size=\"2\"><a href=\"http://www.whitefence.com/WebObjects/WhiteFence.woa/wa/cm?id=1076517\"><strong>Connect your utilities BEFORE you move in</strong></a> - Easily connect your utilities and services at your new residence online and at your convenience. <br><br></font></li><li><font face=\"arial\" size=\"2\"><a href=\"http://www.usedcardboardboxes.com/moving_resources.php#HIRE\"><strong>Hire Helpers / Movers</strong></a> - Keep your friends and spare your back (and theirs)! Get quotes and reviews of local moving labor.</font><font face=\"arial\" size=\"2\"><br><br></font></li><li><font face=\"arial\" size=\"2\"><a href=\"http://www.usedcardboardboxes.com/moving_resources.php#MOVE\">";

	$tip_email .= "<strong>Get Multiple Moving Quotes</strong></a> - Moving quotes can vary by hundred or even thousands of dollars. Use our online request form to get competitive quotes from up to six moving companies. This tip alone can more than pay for all your boxes!<br><br></font></li></ul><p><font face=\"arial\" size=\"2\">Need more tips and resources?  Be sure to also visit our <a href=\"http://www.usedcardboardboxes.com/moving_resources.php\">moving resources</a> page for additional offers. </font></p><p><font face=\"arial\" size=\"2\">Thank you again for purchasing moving boxes and accessories from UsedCardboardBoxes.com! <br></font><font face=\"arial\" size=\"2\"><br></font></p></td></tr><tr><td colspan=\"2\"><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://www.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr><tr><td width=\"23\"><p>&nbsp; </p></td><td width=\"682\"><p>&nbsp; </p></td></tr></table></body></html>";

	//Mail to Customer
	send_phpemil_new("customerservice@UsedCardboardBoxes.com", $cust_email, "Free Moving Tips from UsedCardboardBoxes.com", $tip_email);
	//send_phpemil_new("prasad@extractinfo.com", $cust_email, "Free Moving Tips from UsedCardboardBoxes.com", $tip_email);

	//Mail to David
	tep_mail_new($cust_firstname, "davidkrasnow@usedcardboardboxes.com", "Free Moving Tips from UsedCardboardBoxes.com", $tip_email, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);
	//tep_mail_new($cust_firstname, "prasad@extractinfo.com", "Free Moving Tips from UsedCardboardBoxes.com", $tip_email, $STORE_OWNER, $STORE_OWNER_EMAIL_ADDRESS);
?>	
	<table border="0" width="40%" cellspacing="1" cellpadding="1" style="font-family: Verdana; font-size: 8pt">
		<tr><td colspan="2">Order processed successfully, following are the details: <br><br></td></tr>
		<tr><td width="20%">Order #:</td><td width="80%"><b><?php  echo $insert_id; ?></b></td></tr>
		<tr><td>Name:</td><td ><?php  echo $cust_firstname; ?></td></tr>
	</table>
	<br><br>
<?php 
}

?>

<form name="frmb2bgiftcard" id="frmb2bgiftcard" action="b2c-order-giftcard.php" method="post" onSubmit="return check_form();" >
	<table border="0" width="40%" cellspacing="1" cellpadding="1" style="font-family: Verdana; font-size: 8pt">
		<tr>
		  <td width="100%" colspan="2"><strong>Enter B2C Order details</strong></td>
		</tr>
		<tr>
		  <td width="30%">Name</td>
		  <td width="70%"><?php  echo tep_draw_input_field('entry_firstname', '', 'size="26"')?></td>
		</tr>
		<tr>
		  <td width="30%">Company name</td>
		  <td width="70%"><?php  echo tep_draw_input_field('entry_company', 'Brookfield Residential Gift', 'size="26"')?></td>
		</tr>
		<tr>
		  <td width="14%">Address</td>
		  <td width="86%"><?php  echo tep_draw_input_field('entry_address1', '', 'size="26"') ?></td>
		</tr>
		<tr>
		  <td width="14%">Apt. # / Suite #</td>
		  <td width="86%"><?php  echo tep_draw_input_field('entry_address2', '', 'size="10" maxlength="10"')?></td>
		</tr>
		<tr>
		  <td width="14%">City</td>
		  <td width="86%"><?php  echo tep_draw_input_field('entry_city', '', 'size="26"'); ?></td>
		</tr>
		<tr>
		  <td width="14%">State</td>
		  <td width="86%">
	  
			<select name="entry_state" onChange="javascript:check_tax(this.value)">
			<option value=""></option>
			<option value="Alabama"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Alabama"){ print(" selected=\"selected\""); } ?>>Alabama</option>
			<option value="Arizona"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Arizona"){ print(" selected=\"selected\""); } ?>>Arizona</option>
			<option value="Arkansas"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Arkansas"){ print(" selected=\"selected\""); } ?>>Arkansas</option>
			<option value="California"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="California"){ print(" selected=\"selected\""); } ?>>California</option>
			<option value="Colorado"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Colorado"){ print(" selected=\"selected\""); } ?>>Colorado</option>
			<option value="Connecticut"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Connecticut"){ print(" selected=\"selected\""); } ?>>Connecticut</option>
			<option value="Delaware"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Delaware"){ print(" selected=\"selected\""); } ?>>Delaware</option>
			<option value="District of Columbia"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="District of Columbia"){ print(" selected=\"selected\""); } ?>>District of Columbia</option>
			<option value="Florida"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Florida"){ print(" selected=\"selected\""); } ?>>Florida</option>
			<option value="Georgia"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Georgia"){ print(" selected=\"selected\""); } ?>>Georgia</option>
			<option value="Idaho"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Idaho"){ print(" selected=\"selected\""); } ?>>Idaho</option>
			<option value="Illinois"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Illinois"){ print(" selected=\"selected\""); } ?>>Illinois</option>
			<option value="Indiana"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Indiana"){ print(" selected=\"selected\""); } ?>>Indiana</option>
			<option value="Iowa"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Iowa"){ print(" selected=\"selected\""); } ?>>Iowa</option>
			<option value="Kansas"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Kansas"){ print(" selected=\"selected\""); } ?>>Kansas</option>
			<option value="Kentucky"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Kentucky"){ print(" selected=\"selected\""); } ?>>Kentucky</option>
			<option value="Louisiana"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Louisiana"){ print(" selected=\"selected\""); } ?>>Louisiana</option>
			<option value="Maine"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Maine"){ print(" selected=\"selected\""); } ?>>Maine</option>
			<option value="Maryland"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Maryland"){ print(" selected=\"selected\""); } ?>>Maryland</option>
			<option value="Massachusetts"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Massachusetts"){ print(" selected=\"selected\""); } ?>>Massachusetts</option>
			<option value="Michigan"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Michigan"){ print(" selected=\"selected\""); } ?>>Michigan</option>
			<option value="Minnesota"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Minnesota"){ print(" selected=\"selected\""); } ?>>Minnesota</option>
			<option value="Mississippi"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Mississippi"){ print(" selected=\"selected\""); } ?>>Mississippi</option>
			<option value="Missouri"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Missouri"){ print(" selected=\"selected\""); } ?>>Missouri</option>
			<option value="Montana"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Montana"){ print(" selected=\"selected\""); } ?>>Montana</option>
			<option value="Nebraska"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Nebraska"){ print(" selected=\"selected\""); } ?>>Nebraska</option>
			<option value="Nevada"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Nevada"){ print(" selected=\"selected\""); } ?>>Nevada</option>
			<option value="New Hampshire"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="New Hampshire"){ print(" selected=\"selected\""); } ?>>New Hampshire</option>
			<option value="New Jersey"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="New Jersey"){ print(" selected=\"selected\""); } ?>>New Jersey</option>
			<option value="New Mexico"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="New Mexico"){ print(" selected=\"selected\""); } ?>>New Mexico</option>
			<option value="New York"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="New York"){ print(" selected=\"selected\""); } ?>>New York</option>
			<option value="North Carolina"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="North Carolina"){ print(" selected=\"selected\""); } ?>>North Carolina</option>
			<option value="North Dakota"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="North Dakota"){ print(" selected=\"selected\""); } ?>>North Dakota</option>
			<option value="Ohio"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Ohio"){ print(" selected=\"selected\""); } ?>>Ohio</option>
			<option value="Oklahoma"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Oklahoma"){ print(" selected=\"selected\""); } ?>>Oklahoma</option>
			<option value="Oregon"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Oregon"){ print(" selected=\"selected\""); } ?>>Oregon</option>
			<option value="Pennsylvania"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Pennsylvania"){ print(" selected=\"selected\""); } ?>>Pennsylvania</option>
			<option value="Rhode Island"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Rhode Island"){ print(" selected=\"selected\""); } ?>>Rhode Island</option>
			<option value="South Carolina"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="South Carolina"){ print(" selected=\"selected\""); } ?>>South Carolina</option>
			<option value="South Dakota"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="South Dakota"){ print(" selected=\"selected\""); } ?>>South Dakota</option>
			<option value="Tennessee"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Tennessee"){ print(" selected=\"selected\""); } ?>>Tennessee</option>
			<option value="Texas"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Texas"){ print(" selected=\"selected\""); } ?>>Texas</option>
			<option value="Utah"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Utah"){ print(" selected=\"selected\""); } ?>>Utah</option>
			<option value="Vermont"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Vermont"){ print(" selected=\"selected\""); } ?>>Vermont</option>
			<option value="Virginia"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Virginia"){ print(" selected=\"selected\""); } ?>>Virginia</option>
			<option value="Washington"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Washington"){ print(" selected=\"selected\""); } ?>>Washington</option>
			<option value="West Virginia"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="West Virginia"){ print(" selected=\"selected\""); } ?>>West Virginia</option>
			<option value="Wisconsin"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Wisconsin"){ print(" selected=\"selected\""); } ?>>Wisconsin</option>
			<option value="Wyoming"<?php  if(isset($_SESSION['entry_state']) && $_SESSION['entry_state']=="Wyoming"){ print(" selected=\"selected\""); } ?>>Wyoming</option>
		</select>
		  </td>
		</tr>
		<tr>
		  <td width="14%">Zip Code</td>
		  <td width="86%">
			<input type="text" maxlength="5" name="entry_zipcode" size="15" value=""></td>
		</tr>
		<tr>
		  <td width="14%">Phone</td>
		  <td width="86%">
		  <?php  echo tep_draw_input_field('entry_telephone', '', 'size="26"')?> </td>
		</tr>
		<tr>
		  <td width="14%">Email</td>
		  <td width="86%"><?php  echo tep_draw_input_field('entry_email', '', 'size="26"'); ?></td>
		</tr>				
		<tr>
		  <td width="14%">Notes</td>
		  <td width="86%"><?php  echo tep_draw_input_field('entry_notes', '', 'size="50"'); ?></td>
		</tr>				
		<tr>
		  <td width="14%">Gift Voucher</td>
		  <td width="86%"><?php  echo tep_draw_input_field('order_coupon', 'brookfieldmillier', 'size="15"'); ?></td>
		</tr>				
		<tr>
		  <td width="14%">Amount to debit gift voucher</td>
		  <td width="86%"><?php  echo tep_draw_input_field('order_coupon_amt', '', 'size="10"'); ?></td>
		</tr>				

		<tr>
		  <td colspan="2" align="center"><input type="submit" name="btnsubmit" id="btnsubmit" value="Submit" /></td>
		</tr>		
	</table>		
</form><br><Br>Dejah.Greco@brookfieldrp.com<br>7142001525 <br><br>7142002407<br>Millie.Rodriguez@brookfieldrp.com