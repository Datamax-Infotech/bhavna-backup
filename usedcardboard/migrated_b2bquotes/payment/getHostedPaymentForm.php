<?php 
session_start();

function xml_entities($string) {
	return strtr(
		$string, 
		array(
			"<" => "&lt;",
			">" => "&gt;",
			'"' => "&quot;",
			"'" => "&apos;",
			"&" => "and",
		)
	);
}

$sessionId = session_id();

$productTotal = 0; $quoteTotal = 0; $total_amount = 0;
$ProductLoopId = $_REQUEST["id"];

if ($sessionId){
	$getSessionDt = db_query("SELECT * FROM b2becommerce_order_item WHERE session_id = '".$sessionId."' and product_loopboxid = '" . $ProductLoopId ."'", db() );
	while ($rowContactInfo = array_shift($getSessionDt)) {
		$ProductLoopId = $rowContactInfo['product_loopboxid'];
		
		$productTotal = str_replace(",", "", $rowContactInfo['product_total']);
		$quoteTotal = str_replace(",", "", $rowContactInfo['quote_total']);
		$total_amount = round($productTotal) + round($quoteTotal);

		$orderData['productTotal'] = $productTotal;
		
		$orderData['productName'] = $rowContactInfo['product_name'];
		$orderData['product_name_id'] = $rowContactInfo['product_name_id'];
		$orderData['productQntypeid'] = $rowContactInfo['product_name_id'];
		$orderData['productQnt'] = $rowContactInfo['product_qty'];
		$orderData['productUnitPr'] = $rowContactInfo['product_unitprice'];
		
		$orderData['quoteName'] = $rowContactInfo['quote_name'];
		$orderData['quoteQty'] = $rowContactInfo['quote_qty'];
		$orderData['quoteUnitPr'] = $rowContactInfo['quote_unit_price'];
		$orderData['quoteTotal'] = $quoteTotal;
		
		$orderData['lastInsertId'] = $rowContactInfo['id'];
		$orderData['cntInfoEmail'] = $rowContactInfo['contact_email'];
		$orderData['cntInfoPhn'] = $rowContactInfo['contact_phone'];
		$orderData['cntInfoFNm'] = $rowContactInfo['contact_firstname'];
		$orderData['cntInfoLNm'] = $rowContactInfo['contact_lastname'];
		$orderData['cntInfoCompny'] = $rowContactInfo['contact_company'];
		$orderData['billingAdd1'] = $rowContactInfo['billing_add1'];
		$orderData['billingAdd2'] = $rowContactInfo['billing_add2'];
		$orderData['billingAddCity'] = $rowContactInfo['billing_city'];
		$orderData['billingAddState'] = $rowContactInfo['billing_state'];
		$orderData['billingAddZip'] = $rowContactInfo['billing_zip'];
	
		$orderData['shippingAdd1'] = $rowContactInfo['shipping_add1'];
		$orderData['shippingAdd2'] = $rowContactInfo['shipping_add2'];
		$orderData['shippingaddCity'] = $rowContactInfo['shipping_city'];
		$orderData['shippingaddState'] = $rowContactInfo['shipping_state'];
		$orderData['shippingaddZip'] = $rowContactInfo['shipping_zip'];
		
	}
}

	$ordertot = $total_amount; 
	$tax_val = 0; 
	
	$product_details = "B2B product";;
	$inv_number = $orderData['lastInsertId'];
	$customer_email_address =  $orderData['cntInfoEmail'];
	
	//$auth_trans_id = $_SESSION["auth_trans_id"];
	
	$customer_phone        = $orderData['cntInfoPhn'];
	$bill_firstname        = xml_entities($orderData['cntInfoFNm']);
	$bill_lastname         = xml_entities($orderData['cntInfoLNm']);
	$bill_comp             = xml_entities($orderData['cntInfoCompny']);
	$bill_street_address   = xml_entities($orderData['billingAdd1']) . ", " . xml_entities($orderData['billingAdd2']);
	$bill_city             = $orderData['billingAddCity'];
	$bill_state            = $orderData['billingAddState'];
	$bill_postcode         = $orderData['billingAddZip'];
	$payment_type          =  'authCaptureTransaction'; 

	$ship_firstname        = $bill_firstname;
	$ship_lastname         = $bill_lastname;
	$ship_street_address   = $orderData['shippingAdd1'] . " " . $orderData['shippingAdd2'];
	$ship_city             = $orderData['shippingaddCity'];
	$ship_state            = $orderData['shippingaddState'];
	$ship_postcode         = $orderData['shippingaddZip'];
		
	$passv_is_pickup_call = "Y";
	
if ($payment_type == "priorAuthCaptureTransaction")
{	

}else{
$xmlStr = <<<XML
<?php xml version="1.0" encoding="utf-8"?>
<getHostedPaymentPageRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
    <merchantAuthentication></merchantAuthentication>
    <transactionRequest>
        <transactionType>$payment_type</transactionType>
        <amount>$ordertot</amount>
        <order>
			<invoiceNumber>$inv_number</invoiceNumber>
        </order>
		<customer>
				<email>$customer_email_address</email>
		</customer>
		<billTo>
			 <firstName>$bill_firstname</firstName>
			 <lastName>$bill_lastname</lastName>
			 <company>$bill_comp</company>
			 <address>$bill_street_address</address>
			 <city>$bill_city</city>
			 <state>$bill_state</state>
			 <zip>$bill_postcode</zip>
			 <country>US</country>
			 <phoneNumber>$customer_phone</phoneNumber>
		</billTo>
		<shipTo>
			 <firstName>$ship_firstname</firstName>
			 <lastName>$ship_lastname</lastName>
			 <company>$bill_comp</company>
			 <address>$ship_street_address</address>
			 <city>$ship_city</city>
			 <state>$ship_state</state>
			 <zip>$ship_postcode</zip>
			 <country>US</country>
		  </shipTo>
		 <userFields>
			<userField>
				<name>passv_is_pickup_call</name>
				<value>$passv_is_pickup_call</value>
			</userField>
		</userFields>			  
    </transactionRequest>
    <hostedPaymentSettings>
         <setting>
            <settingName>hostedPaymentIFrameCommunicatorUrl</settingName>
        </setting>
        <setting>
            <settingName>hostedPaymentButtonOptions</settingName>
            <settingValue>{"text": "Pay"}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentReturnOptions</settingName>
        </setting>
        <setting>
            <settingName>hostedPaymentOrderOptions</settingName>
            <settingValue>{"show": false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentCustomerOptions</settingName>
            <settingValue>{"showEmail": false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentPaymentOptions</settingName>
            <settingValue>{"cardCodeRequired": true}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentBillingAddressOptions</settingName>
            <settingValue>{"show": false, "required":false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentShippingAddressOptions</settingName>
            <settingValue>{"show": false, "required":false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentSecurityOptions</settingName>
            <settingValue>{"captcha": false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentStyleOptions</settingName>
            <settingValue>{"bgColor": "#ff8000"}</settingValue>
        </setting>
    </hostedPaymentSettings>
</getHostedPaymentPageRequest>
XML;
}

$xml = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOWARNING);

$xml->merchantAuthentication->addChild('name', "4Rw6UcB57");  ///4Rw6UcB57
$xml->merchantAuthentication->addChild('transactionKey', "8c6KJ9862eJs2jBx"); //8c6KJ9862eJs2jBx

/*NOTE : NEED TO UPDATE LIVE URL*/
$commUrl = json_encode(array('url' => "https://b2b.usedcardboardboxes.com/payment/IFrameCommunicator.html" ), JSON_UNESCAPED_SLASHES);
$xml->hostedPaymentSettings->setting[0]->addChild('settingValue', $commUrl);
/*NOTE : NEED TO UPDATE LIVE URL*/
$retUrl = json_encode(array("showReceipt" => false , 'url' => "https://b2b.usedcardboardboxes.com/payment/return.html", "urlText"=>"Continue to site", "cancelUrl" => "https://b2b.usedcardboardboxes.com/payment/return.html", "cancelUrlText" => "Cancel" ), JSON_UNESCAPED_SLASHES);
$xml->hostedPaymentSettings->setting[2]->addChild('settingValue', $retUrl);

//$url = "https://apitest.authorize.net/xml/v1/request.api";
$url = "https://api.authorize.net/xml/v1/request.api";

try {   //setting the curl parameters.
        $ch = curl_init();
    if (false === $ch) {
        throw new Exception('failed to initialize');
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->asXML());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    // The following two curl SSL options are set to "false" for ease of development/debug purposes only.
    // Any code used in production should either remove these lines or set them to the appropriate
    // values to properly use secure connections for PCI-DSS compliance.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //for production, set value to true or 1
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //for production, set value to 2
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    //curl_setopt($ch, CURLOPT_PROXY, 'userproxy.visa.com:80');
    $content = curl_exec($ch);

    $content = str_replace('xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"', '', $content);
	
    $hostedPaymentResponse = new SimpleXMLElement($content);

  /* echo "<pre>";
    print_r($hostedPaymentResponse);
    echo "</pre>"; 
    echo $hostedPaymentResponse->token;*/

    if (false === $content) {
		throw new Exception(curl_error($ch), curl_errno($ch));
    }
    curl_close($ch);
} catch (Exception $e) {
        trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
}

function thisPageURL()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    $pageLocation = str_replace('index.php', '', $pageURL);

    return $pageLocation;
}

