<?php 
	ini_set("display_errors", "1");
	error_reporting(E_ALL);

	$token = "uumpondjxakyfpl9t0p7kqox8jjh95hp";
	
	$ubox_order = "390101101";
	$ch = curl_init("https://www.uboxes.com/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
	
	// Returns the data/output as a string instead of raw data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//Set your auth headers
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	   'Content-Type: application/json',
	   'Authorization: Bearer ' . $token
	   ));

	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	// get stringified data/output. See CURLOPT_RETURNTRANSFER
	$data = curl_exec($ch);

	// close curl resource to free up system resources
	//print_r($data);
	
	$jsonData = json_decode($data,true);

	//var_dump($jsonData);
	
	$main_array = $jsonData['items'][0];
	$main_array2 = $main_array['extension_attributes']['shipping_assignments'][0]['shipping']['address'];
	//var_dump($main_array);
	//$shipping_add = $main_array['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0];
	
	$shipping_add = $main_array2['firstname'] . " " . $main_array2['lastname'] . "<br>". $main_array2['street'][0] . "<br>". $main_array2['city'] . "," . $main_array2['region'] . " " . $main_array2['postcode'] . "<br>" . $main_array2['telephone'];

	$items_array = $main_array['extension_attributes']['shipping_assignments'][0]['items'];
	$items_data = "";
	foreach($items_array as $items_array_data)
	{
		$items_data .= $items_array_data['name'] . "<br>SKU: " . $items_array_data['sku'] . "<br>Price: " . $items_array_data['base_row_total'] . "<br>Qty Ordered:" . $items_array_data['qty_ordered'];
	}
	
	//$shipping_add = $jsonData['items']['extension_attributes']['shipping_assignments']['shipping']['address']['street'];

	curl_close($ch);
				
	echo "Shipping " . $shipping_add . "<br>" . $items_data;
	
	exit;
	
	$xmlStr = '<order>
				<order_id>9999</order_id>
				<created_at>' . Date("Y-m-d H:i:s") . '</created_at>
				<customer_email>prasad@extractinfo.com</customer_email>
				<customer_firstname>Prasad</customer_firstname>
				<customer_lastname>Brid</customer_lastname>
				<tax_amount>0</tax_amount>
				<shipping_amount>1</shipping_amount>
				<discount_amount>0</discount_amount>
				<subtotal>10</subtotal>
				<grand_total>10</grand_total>
				<shipping_firstname>Prasad</shipping_firstname>
				<shipping_lastname>Brid</shipping_lastname>
				<shipping_country>United States</shipping_country>
				<shipping_state>California</shipping_state>
				<shipping_city>Chino Hills</shipping_city>
				<shipping_postcode>91709</shipping_postcode>
				<shipping_address>1426 Rancho hills dr</shipping_address>
				<shipping_address_line2></shipping_address_line2>
				<shipping_telephone>3109294525</shipping_telephone>
				<order_item>
					<product_id>1</product_id>
					<product_sku>ECOBASICKT01</product_sku>
					<product_name>Economy Kit #1</product_name>
					<product_weight>10</product_weight>
					<product_qty>1</product_qty>
					<product_price>15</product_price>
					<product_subtotal>15</product_subtotal>
				</order_item>
				<shipping_method>flatrate_flatrate</shipping_method>
				<billing_firstname>Prasad</billing_firstname>
				<billing_lastname>Brid</billing_lastname>
				<billing_country>United States</billing_country>
				<billing_state>California</billing_state>
				<billing_city>Chino Hills</billing_city>
				<billing_postcode>91709</billing_postcode>
				<billing_address>1426 Rancho hills dr</billing_address>
				<billing_telephone>3109294525</billing_telephone>
			</order>';

	$xml = simplexml_load_string($xmlStr);

	$data = array("order_id" => 399100765,"created_at" => "2019-02-27 00:00:00","customer_email"=>"customerservice@usedcardboardboxes.com","customer_firstname"=>"UsedCardboard", "customer_lastname"=>"Boxes.com", "tax_amount"=>1, "shipping_amount"=>2, "discount_amount"=>1, "subtotal"=>10, "grand_total"=>10, "shipping_firstname"=>"UsedCardboard", "shipping_lastname"=>"Boxes.com", "shipping_country"=>"United States", "shipping_state"=>"California", "shipping_city"=>"Los Angeles", "shipping_postcode"=>"90010", "shipping_address"=>"4032 Wilshire Blvd", "shipping_address_line2"=>"Suite 402", "shipping_telephone"=>"3237242500", "order_item" => array ("item"=>array ("product_id" => 1 ,"product_sku" => "BASICBOXKT01","product_name" => "Basic Smart Moving Boxes Kit #1", "product_weight" => 1.34,"product_qty" => 1, "product_price" => 10, "product_subtotal" => 10)), "shipping_method"=>"fedex_GROUND_HOME_DELIVERY", "billing_firstname"=>"UsedCardboard", "billing_lastname"=>"Boxes.com", "billing_country"=>"United States", "billing_state"=>"California","billing_city"=>"Los Angeles", "billing_postcode"=>"90010", "billing_address"=>"4032 Wilshire Blvd", "billing_telephone"=>"3237242500");
		
	//$jsondt = json_encode($xml);
	//		$array = json_decode($jsondt,TRUE);
	//		var_dump($array);
		
	$data_string = json_encode(array("order" =>$data));
			//$array = json_decode($data_string,TRUE);
			//var_dump($array);
	//$array = json_decode($data_string,TRUE);
	//var_dump($array);
	//exit;
			
	print_r($data_string);
	
	$token = "ob6hpkx9e4iduergynnyki1wh7xbbme1";
	//setup the request, you can also use CURLOPT_URL
	//$ch = curl_init('https://www.uboxes.com/rest/V1/vendor-order');
	
	$ch = curl_init('https://www.boxengine.com/rest/V1/vendor-order');

	$ch = curl_init('https://www.uboxes.com/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=390100761&searchCriteria[filter_groups][0][filters][0][condition_type]=eq');
	
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
	print_r($data);

	//echo "<br>";
	//print_r($info);

	curl_close($ch);

?>
