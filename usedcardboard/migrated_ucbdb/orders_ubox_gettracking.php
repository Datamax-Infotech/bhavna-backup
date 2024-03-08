<?php 
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

	$token = "uumpondjxakyfpl9t0p7kqox8jjh95hp";

	function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL) {
	  if (!defined('SATURDAY')) define('SATURDAY', 6);
	  if (!defined('SUNDAY')) define('SUNDAY', 0);

	  // Array of all public festivities
	  $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26');
	  // The Patron day (if any) is added to public festivities
	  if ($patron) {
		$publicHolidays[] = $patron;
	  }

	  /*
	   * Array of all Easter Mondays in the given interval
	   */
	  $yearStart = date('Y', strtotime($date1));
	  $yearEnd   = date('Y', strtotime($date2));

	  for ($i = $yearStart; $i <= $yearEnd; $i++) {
		$easter = date('Y-m-d', easter_date($i));
		list($y, $m, $g) = explode("-", $easter);
		$monday = mktime(0,0,0, date($m), date($g)+1, date($y));
		$easterMondays[] = $monday;
	  }

	  $start = strtotime($date1);
	  $end   = strtotime($date2);
	  $workdays = 0;
	  for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
		$day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
		$mmgg = date('m-d', $i);
		if ($day != SUNDAY &&
		  !in_array($mmgg, $publicHolidays) &&
		  !in_array($i, $easterMondays) &&
		  !($day == SATURDAY && $workSat == FALSE)) {
			$workdays++;
		}
	  }

	  return intval($workdays);
	}	
	
	function update_shopify_tracking_no($orders_id, $tracking_number_org, $carrier_code_org){
		//update the Shopify Tracking number
		$shopify_order_no = "";
		$sel_sql = "Select shopify_order_no from orders where orders_id = '" . $orders_id . "'";
		$sel_sql_data = db_query($sel_sql, db());

		while ($sel_sql_row = array_shift($sel_sql_data)) {
			$shopify_order_no = $sel_sql_row["shopify_order_no"];
		}
					
		if ($shopify_order_no != "")
		{			
			$data_found = "no"; $first_order = "no"; $shopify_order_fulfillment_id = 0;
			//$tracking_number_arr = explode("," , $tracking_number_org);
			
			$cnt = 0;
			$data_found = "yes";
			if (strtoupper($carrier_code_org) == "FEDEX"){
				$carrier_code = "FedEx";
			}
			if (strtoupper($carrier_code_org) == "UPS"){
				$carrier_code = "UPS";
			}
			
			$data = array( "fulfillment" => array(
				"location_id" => 40886534275, 
				"tracking_number" => strval($tracking_number_org),
				"tracking_company" => strval($carrier_code)
			));
			
			$data_string = json_encode($data);                                                                                   
		
			$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
			curl_setopt($ch_shopify, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);

			curl_setopt($ch_shopify, CURLOPT_POST, 1);
			curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);
			
			$result = curl_exec($ch_shopify);		
			
			if ($result){					
				//echo "data updated <br>";

				//$jsonData = json_decode($result,true);
				
				//print_r($jsonData);
				
				//$shopify_order_fulfillment_id = $jsonData["fulfillment"]["id"];	
				//echo "shopify_order_fulfillment_id  - " . $shopify_order_fulfillment_id;
			}
				
			$cnt = $cnt + 1;
			
		}	
	}	
	
	$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63, 64, 65, 66, 67, 68, 69, 70)";
	$rgp = array_shift(db_query($query, db()));
	$arr_rgp = explode(',', $rgp["grp_prod_id"]);
	
	$sqlgetemp = "SELECT * FROM orders where (ubox_order_tracking_number is null or ubox_order_tracking_number = '') and ubox_order <> '' and year(date_purchased) >= 2020 ";
	//$sqlgetemp = "SELECT * FROM orders where orders_id = 395753";

	//$sqlgetemp = "SELECT * FROM orders where ubox_order <> '' and year(date_purchased) = 2020 ";
	$ressqlgetemp = db_query($sqlgetemp,db() );
	while ($myrowselemp = array_shift($ressqlgetemp)) {

		$ubox_order = $myrowselemp["ubox_order"];
		$ubox_tracking_cnt = 0;
		
		$ch = curl_init("https://www.uboxes.com/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
		
		// Returns the data/output as a string instead of raw data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//Set your auth headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		   'Content-Type: application/json',
		   'Authorization: Bearer ' . $token
		   ));

		$data = curl_exec($ch);

		$jsonData = json_decode($data,true);
		curl_close($ch);

		//var_dump($jsonData);
		
		//echo "<br><br><br>";

		$main_array = $jsonData['items'][0];

		$parent_id = $main_array['extension_attributes']['shipping_assignments'][0]['shipping']['address']['parent_id'];
		//echo "parent_id " . $parent_id . "<br><br>";

		$items_array = $main_array['extension_attributes']['shipping_assignments'][0]['items'];
		$item_count = 0;
		foreach($items_array as $items_array_data)
		{
			$track_number = "";
			$carrier_code = "";

			//echo $items_array_data['qty_ordered'] . "<br>";
			
			$item_count = $item_count + 1;
			
			/*if ($items_array_data['qty_ordered'] == 1){
				if ($item_count == 1){
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
				}else{
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "-" . $item_count . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
				}			
			}else{
				$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "-" . $items_array_data['qty_ordered'] . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
			}	*/
			
			if ($items_array_data['qty_ordered'] == 1){
				if ($item_count == 1){
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][field]=order_id&searchCriteria[filter_groups][0][filters][0][value]=" . $parent_id . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
					//echo "In step 1 <br>";
				}else{
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "-" . $item_count . "&searchCriteria[filter_groups][0][filters][0][field]=order_id&searchCriteria[filter_groups][0][filters][0][value]=" . $parent_id . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
					//echo "In step 2 <br>";
				}			
			}else{
				$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "-" . $items_array_data['qty_ordered'] . "&searchCriteria[filter_groups][0][filters][0][field]=order_id&searchCriteria[filter_groups][0][filters][0][value]=" . $parent_id . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
					//echo "In step 3 <br>";
			}
				
			/*if ($items_array_data['qty_ordered'] == 1){
				if ($item_count == 1){
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][value]=758515&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
				}else{
					$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=" . $ubox_order . "-" . $item_count . "&searchCriteria[filter_groups][0][filters][0][value]=758515&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
				}			
			}else{
				$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=" . $ubox_order . "-" . $items_array_data['qty_ordered'] . "&searchCriteria[filter_groups][0][filters][0][value]=758515&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
			}*/			

			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));
			$data = curl_exec($ch);

			$jsonData = json_decode($data,true);
			
			curl_close($ch);

			//var_dump($jsonData);

			$track_items_array = $jsonData['items'];
			foreach($track_items_array as $track_items_array_data)
			{
				$main_array2 = $track_items_array_data['tracks'];

				//if ($item_count == 1){
				//	$track_number = $main_array2[0]['track_number'];
				//}else{
					$track_number = $track_number . $main_array2[0]['track_number'] . ",";
				//}	
				
				//$carrier_code = $carrier_code . $main_array2[0]['carrier_code'] . ",";
				$carrier_code = $main_array2[0]['carrier_code'];
				
			}
			if ($track_number != ""){
				$track_number = substr($track_number, 0, strlen($track_number)-1);	
			}
			//if ($carrier_code != ""){
				//$carrier_code = substr($carrier_code, 0, strlen($carrier_code)-1);	
			//}
			
			
			/*$main_array = $jsonData['items'][0];
			$main_array2 = $main_array['tracks'];

			//echo "track_number - " . $main_array2[0]['track_number'] . "<br>";
			if ($item_count == 1){
				$track_number = $main_array2[0]['track_number'];
			}else{
				if ($track_number == ""){
					$track_number = $main_array2[0]['track_number'] ;
				}else{
					$track_number = $track_number . ", " . $main_array2[0]['track_number'] ;
				}				
			}			*/
			
		}
		
	/*	for ($tmpcnt = 1; $tmpcnt <= $item_count; $tmpcnt++) { 
			if ($tmpcnt == 1){
				$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
			}else{
				$ch = curl_init("https://www.uboxes.com/rest/V1/shipments?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "-" . $tmpcnt . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
			}			
				
			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $token
			   ));
			$data = curl_exec($ch);

			$jsonData = json_decode($data,true);

			//var_dump($jsonData);

			$main_array = $jsonData['items'][0];
			$main_array2 = $main_array['tracks'];

			echo "track_number - " . $main_array2[0]['track_number'] . "<br>";
			if ($tmpcnt == 1){
				$track_number = $main_array2[0]['track_number'];
			}else{
				$track_number .= ", " . $main_array2[0]['track_number'] ;
			}			
			curl_close($ch);
		}*/
		
		
		$ubox_tracking_arr = explode("," , $track_number);
		if (strpos($track_number, ",") > 0) {
			$ubox_tracking_cnt = count($ubox_tracking_arr);
		}else{
			$ubox_tracking_cnt = 1;
		}
		
		$ucb_prod_id = 0; $ucb_prod_id_cnt = 0;
		$orders_products_query = db_query("SELECT products_id FROM orders_products where orders_id = '" . $myrowselemp["orders_id"] . "'");
		while($row_prod_info = array_shift($orders_products_query))
		{
			$ucb_prod_id = $row_prod_info["products_id"];
			
			if(in_array($ucb_prod_id, $arr_rgp))
			{
				$ubox_order = "yes";
				$ucb_prod_id_cnt = $ucb_prod_id_cnt + 1;
			}
		}
		
		//echo "Order id" . $myrowselemp["orders_id"] . " cnt [" . $ubox_tracking_cnt . " " . $ucb_prod_id_cnt . " ], Ubox_order " . $ubox_order . ", track_number - " . $track_number . ", Parent Id " . $parent_id . ", Carrier_code " . $carrier_code . "<br>";
		$process_orders = "yes";
		if ($ubox_tracking_cnt != $ucb_prod_id_cnt){	
			$order_date = new DateTime($myrowselemp["date_purchased"]);
			$curr_date = new DateTime();

			//$order_date_diff = $curr_date->diff($order_date)->format("%d");					
			$order_date_diff = getWorkdays($myrowselemp["date_purchased"], date("Y-m-d"));
			//echo $myrowselemp["orders_id"] . " - order_date_diff - " . $order_date_diff . "<br>";
			if ($order_date_diff >= 2){
				$process_orders = "yes";
			}else{
				$process_orders = "no";
			}			
		}
		
		if ($process_orders == "yes"){		
			echo "Order id" . $myrowselemp["orders_id"] . ", Ubox_order " . $ubox_order . ", track_number - " . $track_number . ", Parent Id " . $parent_id . ", Carrier_code " . $carrier_code . "<br>";
			
			db_query("Update orders set ubox_order_tracking_number = '" . $track_number . "', ubox_order_parent_id = '" . $parent_id . "', ubox_order_carrier_code = '" . $carrier_code . "' where orders_id = " . $myrowselemp["orders_id"] ,db() );
		}
		
		//update_shopify_tracking_no($myrowselemp["orders_id"], $track_number, $carrier_code);
			
	}	
	
	
	function old_update_shopify_tracking_no($orders_id, $tracking_number_org, $carrier_code_org){
		//update the Shopify Tracking number
		$shopify_order_no = "";
		$sel_sql = "Select shopify_order_no from orders where orders_id = '" . $orders_id . "'";
		$sel_sql_data = db_query($sel_sql, db());

		while ($sel_sql_row = array_shift($sel_sql_data)) {
			$shopify_order_no = $sel_sql_row["shopify_order_no"];
		}
					
		if ($shopify_order_no != "")
		{			
			/*$data_found = "no";
			$tracking_number_arr = explode("," , $tracking_number_org);
			$carrier_code_arr = explode("," , $carrier_code_org);
			$cnt = 0;
			foreach($tracking_number_arr as $tracking_number_arr_tmp){
				$data_found = "yes";
				$carrier_code = $carrier_code_arr[$cnt];
				if (strtoupper($carrier_code) == "FEDEX"){
					$carrier_code = "FedEx";
				}
				if (strtoupper($carrier_code) == "UPS"){
					$carrier_code = "UPS";
				}
				
				$data_fulfillment[] = array(
					"location_id" => 40886534275, 
					"tracking_number" => strval($tracking_number_arr_tmp),
					"tracking_company" => strval($carrier_code)
				);
				$cnt = $cnt + 1;
			}
			
			if ($data_found == "yes"){
				$data = array("fulfillment" => $data_fulfillment);
			
				$data_string = json_encode($data);                                                                                   
			}else{*/
				$data = array( "fulfillment" => array(
					"location_id" => 40886534275, 
					"tracking_number" => strval($tracking_number_org),
					"tracking_company" => strval($carrier_code_org)
				));
				
				$data_string = json_encode($data);                                                                                   
			//}			
			
			//echo $data_string;
			
			$ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
			curl_setopt($ch_shopify, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);

			curl_setopt($ch_shopify, CURLOPT_POST, 1);
			curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);
			
			$result = curl_exec($ch_shopify);		
			
			var_dump($result);
		}	
	}		
?>				
