<?php  
/*	$nnProdItemArray[] = array('status' => "success", 'order_id' => "390101044");
	
	print_r($nnProdItemArray);
	
	if ($nnProdItemArray['status'] == "success"){
		echo "test <br>";
		echo $nnProdItemArray['order_id'];
	}
exit;
*/

require ("inc/header_session.php");
//error_reporting(0);
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
require("../../securedata/main-enc-class.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>UBox Upload Orders</title>
</head>

<body>

<?php 
	function convert_state_abbrv($state_abbr_org){
		$state_abbr = $state_abbr_org;
		
		if($state_abbr_org=="AL" ) { $state_abbr="Alabama"; }
		if($state_abbr_org=="AK" ) { $state_abbr="Alaska"; }
		if($state_abbr_org=="AZ" ) { $state_abbr="Arizona"; }
		if($state_abbr_org=="AR" ) { $state_abbr="Arkansas"; }
		if($state_abbr_org=="CA" ) { $state_abbr="California"; }
		if($state_abbr_org=="CO" ) { $state_abbr="Colorado"; }
		if($state_abbr_org=="CT" ) { $state_abbr="Connecticut"; }
		if($state_abbr_org=="DE" ) { $state_abbr="Delaware"; }
		if($state_abbr_org=="DC" ) { $state_abbr="District of Columbia"; }
		if($state_abbr_org=="FL" ) { $state_abbr="Florida"; }
		if($state_abbr_org=="GA" ) { $state_abbr="Georgia"; }
		if($state_abbr_org=="HI" ) { $state_abbr="Hawaii"; }
		if($state_abbr_org=="ID" ) { $state_abbr="Idaho"; }
		if($state_abbr_org=="IL" ) { $state_abbr="Illinois"; }
		if($state_abbr_org=="IN" ) { $state_abbr="Indiana"; }
		if($state_abbr_org=="IA" ) { $state_abbr="Iowa"; }
		if($state_abbr_org=="KS" ) { $state_abbr="Kansas"; }
		if($state_abbr_org=="KY" ) { $state_abbr="Kentucky"; }
		if($state_abbr_org=="LA" ) { $state_abbr="Louisiana"; }
		if($state_abbr_org=="ME" ) { $state_abbr="Maine"; }
		if($state_abbr_org=="MD" ) { $state_abbr="Maryland"; }
		if($state_abbr_org=="MA" ) { $state_abbr="Massachusetts"; }
		if($state_abbr_org=="MI" ) { $state_abbr="Michigan"; }
		if($state_abbr_org=="MN" ) { $state_abbr="Minnesota"; }
		if($state_abbr_org=="MS" ) { $state_abbr="Mississippi"; }
		if($state_abbr_org=="MO" ) { $state_abbr="Missouri"; }
		if($state_abbr_org=="MT" ) { $state_abbr="Montana"; }
		if($state_abbr_org=="NE" ) { $state_abbr="Nebraska"; }
		if($state_abbr_org=="NV" ) { $state_abbr="Nevada"; }
		if($state_abbr_org=="NH" ) { $state_abbr="New Hampshire"; }
		if($state_abbr_org=="NJ" ) { $state_abbr="New Jersey"; }
		if($state_abbr_org=="NM" ) { $state_abbr="New Mexico"; }
		if($state_abbr_org=="NY" ) { $state_abbr="New York"; }
		if($state_abbr_org=="NC" ) { $state_abbr="North Carolina"; }
		if($state_abbr_org=="ND" ) { $state_abbr="North Dakota"; }
		if($state_abbr_org=="OH" ) { $state_abbr="Ohio"; }
		if($state_abbr_org=="OK" ) { $state_abbr="Oklahoma"; }
		if($state_abbr_org=="OR" ) { $state_abbr="Oregon"; }
		if($state_abbr_org=="PA" ) { $state_abbr="Pennsylvania"; }
		if($state_abbr_org=="RI" ) { $state_abbr="Rhode Island"; }
		if($state_abbr_org=="SC" ) { $state_abbr="South Carolina"; }
		if($state_abbr_org=="SD" ) { $state_abbr="South Dakota"; }
		if($state_abbr_org=="TN" ) { $state_abbr="Tennessee"; }
		if($state_abbr_org=="TX" ) { $state_abbr="Texas"; }
		if($state_abbr_org=="UT" ) { $state_abbr="Utah"; }
		if($state_abbr_org=="VT" ) { $state_abbr="Vermont"; }
		if($state_abbr_org=="VA" ) { $state_abbr="Virginia"; }
		if($state_abbr_org=="WA" ) { $state_abbr="Washington"; }
		if($state_abbr_org=="WV" ) { $state_abbr="West Virginia"; }
		if($state_abbr_org=="WI" ) { $state_abbr="Wisconsin"; }
		if($state_abbr_org=="WY" ) { $state_abbr="Wyoming"; }
		
		return $state_abbr;
	}


    $orders_id=$_REQUEST["ordersid"];
    $sql = "SELECT * FROM orders WHERE orders_id='$orders_id' ";
	$result = db_query($sql,db() );		
	while($myrowsel = array_shift($result)) {
		//$id = $myrowsel["id"];
		$orders_id = $myrowsel["orders_id"];
		$order_date = date("Y-m-d", strtotime($myrowsel["date_purchased"]));     
		$order_time = date("g:i:s a", strtotime($myrowsel["date_purchased"])); 
		$created_at=$order_date." ".$order_time;
		//Customer details--------------------------------------
		$customer_email_address = $myrowsel["customers_email_address"]; 
		$customer_name= trim($myrowsel["customers_name"]);
		 $cust_name=explode(" ", $customer_name);   
		//--------------------------------------------------------    
		//Shipping details--------------------------------------
		$shipping_name1 = trim($myrowsel["delivery_name"]);
			$shipping_name=explode(" ", $shipping_name1);
		$shipping_first_name = $shipping_name[0];
		$shipping_last_name = $shipping_name[1];
		if ($shipping_last_name == ""){
			$shipping_last_name = $shipping_first_name;
		}
		$shipping_country = $myrowsel["delivery_country"];
		$shipping_state = convert_state_abbrv($myrowsel["delivery_state"]); 
		$shipping_city = $myrowsel["delivery_city"];  
		$shipping_postcode = $myrowsel["delivery_postcode"];  
		$shipping_address = $myrowsel["delivery_street_address"];  
		$shipping_address_line2 = $myrowsel["delivery_street_address2"];  
		$shipping_telephone = $myrowsel["customers_telephone"]; 
		//--------------------------------------------------------
		//Billing details--------------------------------------
		$billing_firstname = trim($myrowsel["delivery_name"]); 
		$billing_lastname = trim($myrowsel["delivery_name"]);
		$billing_country = $myrowsel["delivery_state"]; 
		$billing_state = $myrowsel["delivery_city"];  
		$billing_city = $myrowsel["delivery_postcode"];  
		$billing_postcode = $myrowsel["delivery_street_address"];  
		$billing_address = $myrowsel["delivery_street_address2"];  
		$billing_telephone = $myrowsel["customers_telephone"]; 
		//--------------------------------------------------------
			
		$taxamount="";
		$shipping_amount="";
		$discount_amount="";
		$subtotal="";
		$grand_total="";
			
    
    }
	
	$product_subtotal = 0; $product_grand_total = 0; $products_price = 0; $rec_found = "n";
	
    $orders_products_query = db_query("select ubox_mapping.*, orders_products_id, orders_products.products_id, orders_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from orders_products inner join ubox_mapping on ubox_mapping.products_id = orders_products.products_id where orders_id = '" . (int)$orders_id . "'", db());
	while ($orders_products = array_shift($orders_products_query)) {
		$rec_found = "y";
		$products_price_discount = (15*$orders_products['ubox_product_price'])/100;
		$products_price = $orders_products['ubox_product_price'] - $products_price_discount;
		
		$product_subtotal=($orders_products['products_quantity'] * $orders_products['quantity'])*$products_price;
		$product_grand_total = $product_grand_total + $product_subtotal;
		$products_quantity = $orders_products['products_quantity'] * $orders_products['quantity'];
		 
		$ProdItemArray[] = array('product_id' => $orders_products['ubox_id'], 'product_sku' => $orders_products['ubox_sku'], 'product_name' => $orders_products['ubox_name'],'product_weight' => $orders_products['ubox_weight'],
		'product_qty' => $products_quantity,'product_price' => number_format($products_price, 2, '.', ''),'product_subtotal' => number_format($product_subtotal, 2, '.', ''));
	} 
   // $mainxml.="</item></order_item>";
     //echo "product_grand_total m - " . $product_grand_total . "<br>"; 
	
	if ($product_grand_total < 35){
		$product_grand_total = $product_grand_total+ 3.95;
	}
   
     //echo "product_grand_total - " . $product_grand_total . "<br>"; 
	 //exit;
	 
   
/*   $mainxml.="<tax_amount>0</tax_amount>
    <shipping_amount>0</shipping_amount>
    <discount_amount>0</discount_amount>
    <subtotal>".$product_grand_total."</subtotal>
    <grand_total>".$product_grand_total."</grand_total>";
	
	$mainxml.="<shipping_method>fedex_GROUND_HOME_DELIVERY</shipping_method>
    <billing_firstname>UsedCardboard</billing_firstname>
    <billing_lastname>Boxes.com</billing_lastname>
    <billing_country>United States</billing_country>
    <billing_state>California</billing_state>
    <billing_city>Los Angeles</billing_city>
    <billing_postcode>90010</billing_postcode>
    <billing_address>4032 Wilshire Blvd. #402</billing_address>
    <billing_telephone>3237242500</billing_telephone>
    </order></order>";
    */
	
/*	$xml = simplexml_load_string($mainxml);
	$jsondt = json_encode($xml);
	$array = json_decode($jsondt,TRUE);
	
	var_dump($array);
*/	

	if ($rec_found == "y")
	{
	//"order_id" => $orders_id, removed as per Cheryl email on 15-Sep-2020
	//"product_type"=>'configurable'
		//$data = array("created_at" => $created_at,"customer_email"=>"customerservice@usedcardboardboxes.com","customer_firstname"=>"UsedCardboard", "customer_lastname"=>"boxes", "tax_amount"=>0, "shipping_amount"=>0, "discount_amount"=>0, "subtotal"=>$product_grand_total, "grand_total"=>$product_grand_total, "shipping_firstname"=>$shipping_first_name, "shipping_lastname"=>$shipping_last_name, "shipping_country"=>$shipping_country, "shipping_state"=>$shipping_state, "shipping_city"=>$shipping_city, "shipping_postcode"=>$shipping_postcode, "shipping_address"=>$shipping_address, "shipping_address_line2"=>$shipping_address_line2, "shipping_telephone"=>$shipping_telephone, "order_item" => array ("item"=>$ProdItemArray), "shipping_method"=>"fedex_GROUND_HOME_DELIVERY", "billing_firstname"=>"UsedCardboard", "billing_lastname"=>"Boxes.com", "billing_country"=>"United States", "billing_state"=>"California","billing_city"=>"Los Angeles", "billing_postcode"=>"90010", "billing_address"=>"4032 Wilshire Blvd", "billing_telephone"=>"3237242500");
		$data = array("created_at" => $created_at,"customer_email"=>"customerservice@usedcardboardboxes.com","customer_firstname"=>"UsedCardboard", "customer_lastname"=>"boxes", "tax_amount"=>0, "shipping_amount"=>0, "discount_amount"=>0, "subtotal"=>$product_grand_total, "grand_total"=>$product_grand_total, "shipping_firstname"=>$shipping_first_name, "shipping_lastname"=>$shipping_last_name, "shipping_country"=>$shipping_country, "shipping_state"=>$shipping_state, "shipping_city"=>$shipping_city, "shipping_postcode"=>$shipping_postcode, "shipping_address"=>$shipping_address, "shipping_address_line2"=>$shipping_address_line2, "shipping_telephone"=>$shipping_telephone, "unique_vendor_order_id"=>$orders_id, "order_item" => array ("item"=>$ProdItemArray), "shipping_method"=>"fedex_GROUND_HOME_DELIVERY", "billing_firstname"=>"UsedCardboard", "billing_lastname"=>"Boxes.com", "billing_country"=>"United States", "billing_state"=>"California","billing_city"=>"Los Angeles", "billing_postcode"=>"90010", "billing_address"=>"4032 Wilshire Blvd", "billing_telephone"=>"3237242500");
		
		$data_string = json_encode(array("order" =>$data));
		//var_dump($data_string);
		$array = json_decode($data_string,TRUE);
		//var_dump($array);

		//$token = "ob6hpkx9e4iduergynnyki1wh7xbbme1";
		$token = "uumpondjxakyfpl9t0p7kqox8jjh95hp";
		
		//setup the request, you can also use CURLOPT_URL
		//$ch = curl_init('https://www.uboxes.com/rest/api/V1/vendor-order');
		//$ch = curl_init('https://www.uboxes.com/rest/wholesale/V1/vendor-order');
		$ch = curl_init('https://www.uboxes.com/rest/api/V1/vendor-order');
		
		// Returns the data/output as a string instead of raw data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//Set your auth headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		   'Content-Type: application/json',
		   'Authorization: Bearer ' . $token
		   ));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		
		// get stringified data/output. See CURLOPT_RETURNTRANSFER
		$data = curl_exec($ch);

		// get info about the request
		$info = curl_getinfo($ch);

		// close curl resource to free up system resources
		//print_r($data);
		
		//echo "<br><br>Chk";
		//1{"status":"success","order_id":"390126102"}
		//{"status":"success","order_id":"390101044"} 
		$jsonData = json_decode($data,true);
		
		curl_close($ch);
		
		//var_dump($jsonData);
		
		if ($jsonData['status'] == "success"){
			$sql = "Insert into ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $orders_id . "','8','Order shipped by Uboxes, order number is: " . $jsonData['order_id'] . "','" . date("Ymd") . "','" . $_COOKIE['userinitials'] . "','')";
			$result = db_query($sql,db() );
			
			$sql = "Update orders set ubox_order = '" . $jsonData['order_id'] . "' where orders_id = '" . $orders_id . "'";
			$result = db_query($sql,db() );
			
			$sql = "Update orders_sps set sent = 1 where orders_id = '" . $orders_id . "'";
			$result = db_query($sql,db() );
			
		}else{
			$sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $orders_id . "','8','Order not shipped by Uboxes, order status: " . $jsonData['status'] . " error message:" . $jsonData['message'] . "','" . date("Ymd") . "','" . $_COOKIE['userinitials'] . "','')";
			$result = db_query($sql,db() );
		}
	}else{
		$sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $orders_id . "','8','Order not shipped, no matching product found in Ubox','" . date("Ymd") . "','" . $_COOKIE['userinitials'] . "','')";
		$result = db_query($sql,db() );
	}
	
	
	$pgurl = "https://b2c.usedcardboardboxes.com/orders.php";

	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"".$pgurl."?id=".$orders_id."&proc=View&searchcrit=".$orders_id."&page=0" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=".$pgurl."?id=".$orders_id."&proc=View&searchcrit=".$orders_id."&page=0" . "\" />";
	echo "</noscript>"; exit;
	
    ?>
</body>
</html>
