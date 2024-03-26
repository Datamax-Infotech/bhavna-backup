<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
db();
	$order_id = decrypt_url($_REQUEST["order_id"]);
	$order_comments = "";
	$sql="select * from orders WHERE orders_id = " . $order_id;

	$result = db_query($sql);
	$pwa_array_address = array_shift($result); 
	$order_comments = $pwa_array_address["comment"];

	function tep_calculate_tax(float $price, float $tax): float
	{
		global $currencies;

		return tep_round($price * $tax / 100, 2);
	}
	
	function tep_add_tax(float $price, float $tax): float  {
		global $currencies;

		if ( ($tax > 0) ) {
		  return tep_round($price, 2) + tep_calculate_tax($price, $tax);
		} else {
		  return tep_round($price, 2);
		}
	}
	
	/*function tep_round(float $number, int $precision): float {
		//if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
			if (strpos((string)$number, '.') && (strlen(substr((string)$number, strpos((string)$number, '.') + 1)) > $precision)) {	
		  //$number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);
		  $number = substr((string)$number, 0, strpos((string)$number, '.') + 1 + $precision + 1);
		  if (substr($number, -1) >= 5) {
			if ($precision > 1) {
			  //$number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
			  $number = substr($number, 0, -1) . ('0.' . str_repeat('0', $precision-1) . '1');
			} elseif ($precision == 1) {
			  $number = substr($number, 0, -1) + 0.1;
			} else {
			  $number = substr($number, 0, -1) + 1;
			}
		  } else {
			$number = substr($number, 0, -1);
		  }
		}

		return $number;
	}
	*/
	function tep_round(float $number, int $precision): float {
		if (strpos((string)$number, '.') && (strlen(substr((string)$number, strpos((string)$number, '.') + 1)) > $precision)) {    
			$number = substr((string)$number, 0, strpos((string)$number, '.') + 1 + $precision + 1);
			if (substr($number, -1) >= 5) {
				if ($precision > 1) {
					$number = floatval(substr($number, 0, -1)) + floatval('0.' . str_repeat('0', $precision-1) . '1');
				} elseif ($precision == 1) {
					$number = floatval(substr($number, 0, -1)) + 0.1;
				} else {
					$number = floatval(substr($number, 0, -1)) + 1;
				}
			} else {
				$number = floatval(substr($number, 0, -1));
			}
		}
	
		return $number;
	}
	
    function display_price(float $products_price, float $products_tax, int $quantity = 1): string {
      return "$" . round(tep_add_tax($products_price, $products_tax) * $quantity, 2);
    }
	
	 
	define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
	define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
	define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
	define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
	 
	define('PICKUPCALLADDRESS', '720 South Vail Avenue <br>Montebello, CA 90640');
	 
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

	$subject ="Invalid Delivery Address on Your UsedCardboardBoxes.com Order #". $order_id." ";		

	//$email_order = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td colspan=\"2\"><p align=\"center\"><a href=\"https://loops.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td colspan=\"2\"><p align=\"center\"><font face=\"arial\" size=\"2\"><a href=\"https://loops.usedcardboardboxes.com/index.php\">Home</a> | <a href=\"https://loops.usedcardboardboxes.com/search_trackingno.php\">Track Your Order</a> | <a href=\"https://loops.usedcardboardboxes.com/moving_resources.php\">Moving Resources</a></font></p><br></td></tr><tr><td width=\"23\"><p> </p></td><td width=\"682\"><br>";
	$email_order = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";

	$email_order .= "<p style='font-family: Calibri;'>
	Uh oh, the delivery address you entered has been flagged as an invalid delivery address by our carrier!<br><br>
	Due to this, we are <u>unable to ship your order until we get a valid address</u>. We will be reaching out to you by phone or email shortly to get this corrected in time to ship out your order on time, but if you are able to reply to this e-mail with the correct address, we can correct it that way as well.<br><br>

	Your order confirmation number is ". $order_id." <br><br>

	Once your delivery address is correct, and after your order is picked up from our warehouse, you can track the progress of your 
	order here: <a href='https://www.usedcardboardboxes.com/search_trackingno.php'>https://www.usedcardboardboxes.com/search_trackingno.php</a><br><br>";

	$email_order .= EMAIL_SEPARATOR . "<br><br>" . 
				 EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "<br><br>" .
				 EMAIL_TEXT_DATE_ORDERED . ' ' . date('l d F, Y', strtotime($pwa_array_address["date_purchased"])) . "<br><br>" .
				 'IP Address: ' . $pwa_array_address["user_ipaddress"] . "<br><br>";

	if ($order_comments != "") {
		$email_order .= $order_comments . "<br><br>";
	}

	$products_ordered = "";
	$sql="select * from orders_products WHERE orders_id = " . $order_id;
	$result = db_query($sql);
	while ($myrowsel = array_shift($result)) 
	{
		$products_ordered .= $myrowsel['products_quantity'] . ' x ' . str_replace("<br>", " " , $myrowsel['products_name']) . ' (' . $myrowsel['products_model'] . ') = ' . display_price($myrowsel['final_price'], $myrowsel['products_tax'], $myrowsel['products_quantity']) . "<br>";
	}
	
	$email_order .= EMAIL_TEXT_PRODUCTS . "<br>" . 
				  EMAIL_SEPARATOR . "<br>" . 
				  $products_ordered .
				  EMAIL_SEPARATOR . "<br>";

	$sql="select * from orders_total WHERE orders_id = " . $order_id . " order by sort_order";
	$result = db_query($sql);
	while ($myrowsel = array_shift($result)) 
	{
		if(substr_count($myrowsel['title'], "Gift Voucher") > 0 )
		{}
		else
		{
			$email_order .= strip_tags($myrowsel['title']) . ' ' . strip_tags($myrowsel['text']) . "<br>";
		}
	}
	
	$email_order .= "<br>" . EMAIL_TEXT_DELIVERY_ADDRESS . "<br>" . EMAIL_SEPARATOR . "<br>";
	$email_order .= "<font color=red>";
	if($pwa_array_address["delivery_company"] != "")
	{
		$email_order .= $pwa_array_address["delivery_company"]."<br>";
	}
	$email_order .= $pwa_array_address["delivery_name"]."<br>";
	$email_order .= $pwa_array_address["delivery_street_address"]."<br>";
	if($pwa_array_address["delivery_street_address2"] != "")
	{
		$email_order .= $pwa_array_address["delivery_street_address2"]."<br>";
	}
	$email_order .= $pwa_array_address["delivery_city"].", ";
	$email_order .= $pwa_array_address["delivery_state"]."  ";
	$email_order .= $pwa_array_address["delivery_postcode"]."<br>";
	
	if($pwa_array_address["ups_signature"] == "Y")
	{
		$email_order .= "Do not require my signature for delivery<br>";
	}
	$email_order .= "</font>";

	$email_order .= "<br><br>" . EMAIL_TEXT_BILLING_ADDRESS . "<br>" . EMAIL_SEPARATOR . "<br>";
	if($pwa_array_address["customers_company"] != "")
	{
		$email_order .= $pwa_array_address["customers_company"]."<br>";
	}
	$email_order .= $pwa_array_address["customers_name"]."<br>";
	$email_order .= $pwa_array_address["customers_street_address"]."<br>";
	if($pwa_array_address["customers_street_address2"] != "")
	{
		$email_order .= $pwa_array_address["customers_street_address2"]."<br>";
	}
	$email_order .= $pwa_array_address["customers_city"].", ";
	$email_order .= $pwa_array_address["customers_state"]."  ";
	$email_order .= $pwa_array_address["customers_postcode"]."<br>";
	$email_order .= $pwa_array_address['customers_telephone']."<br>";
	$email_order .= $pwa_array_address['customers_email_address']."<br><br>";

	$email_order .= 'Tell a friend to use Box Bucks Discount Code: "ORDER" and we\'ll give them $1 off their order!'."<br><br>";
	$email_order .= "Help us improve!  Please take 30 seconds to fill out our Customer Satisfaction Survey so we can continue to provide you and all future customers with excellent service!  Thanks!<br><br>";
	$email_order .= "Click here to complete our survey: <a href='https://www.usedcardboardboxes.com/survey.php'>https://www.usedcardboardboxes.com/survey.php</a><br><br>";

	$query = "SELECT * FROM page_text WHERE page_id=26";
	$result = db_query($query);
	$row= array_shift($result);
	$email_order.= "<br>==================================================================<br><br>";
	$email_order.= str_replace("\n", "<br>", strip_tags($row["page_text"]))."<br><br>";

	$email_order .= "		</p></td></tr><tr><td colspan=\"2\"><p align=\"center\"><img width=\"650\" height=\"87\" src=\"https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr><tr><td width=\"23\"><p>&nbsp; </p></td><td width=\"682\"><p>&nbsp; </p></td></tr></table></body></html>";

?>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/js/sample.js"></script>

<link rel="stylesheet" href="ckeditor/css/samples.css">
<link rel="stylesheet" href="ckeditor/toolbarconfigurator/lib/codemirror/neo.css">


<form name="frm_badadd_email" id="frm_badadd_email" action="badadd_send_email_new.php" method="post" ENCTYPE="multipart/form-data">

<table width="70%">
    <tr>

		<td width="10%">From:</td> 

		<td width="90%"> <input size=60 type="text" id="txtemailfrom" name="txtemailfrom" value="CustomerService@UsedCardboardBoxes.com">&nbsp;<font size=1></font></td>

	</tr>

	<tr>

		<td width="10%">To:</td> 

		<td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto" value="<?php echo $pwa_array_address["customers_email_address"];?>">&nbsp;<font size=1>(Use ; to separate multiple email address)</font></td>

	</tr>

	<tr>
		<td width="10%">Subject:</td>
		<td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject" value="<?php echo $subject;?>"></td>
	</tr>


	<tr>
		<td valign="top" width="10%">Body:</td>
		<td width="250px" id="bodytxt">
            <?php 
                require_once('fckeditor_new/fckeditor.php');
                $FCKeditor = new FCKeditor('txtemailbody');

                $FCKeditor->BasePath = 'fckeditor_new/';

                $FCKeditor->Value = $email_order;

                $FCKeditor->Height = 300;

                $FCKeditor->Width = 950;

                $FCKeditor->Create();

			?>


			<div style="heighr:15px;" >&nbsp;</div>

			<input type="button" name="btn_send_eml" id="btn_send_eml" value="Send Email" onclick="badadd_send_eml()">
			
			<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id?>">
			<input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="inemailmode">
			<input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode">
		</td>

	</tr>

</table>	

</form>