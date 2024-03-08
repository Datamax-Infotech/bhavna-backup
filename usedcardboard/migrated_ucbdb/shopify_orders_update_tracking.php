<?php 
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	
	$data_string = "";                                                                                   

	function update_shopify_tracking_no($orders_id, $shopify_order_no, $tracking_number_org, $carrier_code_org, $tracking_urls){
		//update the Shopify Tracking number
		if ($shopify_order_no != "")
		{			
			$shopify_fulfillment_id = "";
			$data_string = "";
			
			//old method $ch_shopify = curl_init('https://0167e802b4c5847eec2856e3a06c4cc6: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@usedcardboardboxes.myshopify.com/admin/api/2020-10/orders/' . $shopify_order_no . '/fulfillments.json');
			$ch_shopify = curl_init('https://0bb8ffb13131f78f928fc125ff6a9f42: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@usedcardboardboxes.myshopify.com/admin/api/2023-01/orders/' . $shopify_order_no . '/fulfillment_orders.json');
			curl_setopt($ch_shopify, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);

			//curl_setopt($ch_shopify, CURLOPT_GET, 2);
			curl_setopt($ch_shopify, CURLOPT_HTTPGET, true);
			curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);
			
			//echo "<br><br>Step 1<br>";
			$result = curl_exec($ch_shopify);		
			if ($result){					
				$jsonData = json_decode($result,true);
				
				//print_r($jsonData);
				
				//$shopify_fulfillment_id = $jsonData["fulfillment"]["id"];	
				$shopify_fulfillment_id = ""; $shopify_fulfillment_line_item_arr = "";
				//$shopify_fulfillment_id_arr = $jsonData["fulfillments"];	
				$shopify_fulfillment_id_arr = $jsonData["fulfillment_orders"];	
				foreach ($shopify_fulfillment_id_arr as $shopify_fulfillment_id_sub){
					if ($shopify_fulfillment_id_sub["status"] == "open"){
						$shopify_fulfillment_id = $shopify_fulfillment_id_sub["id"];	
						
						//echo "<br><br>Step 1 sub<br>";
						
						//var_dump($shopify_fulfillment_id_sub["line_items"]);
						
						//$shopify_fulfillment_line_item_arr = $shopify_fulfillment_id_sub["line_items"];	
					}	
				}
				
				//echo "shopify_order_fulfillment_id  - " . $shopify_fulfillment_id;
				
				if ($shopify_fulfillment_id != ""){
				/*	curl_close($ch_shopify);
				
					echo "<br><br>Step 2<br>";
				
					$data_string = "";
					$ch_shopify = curl_init('https://0bb8ffb13131f78f928fc125ff6a9f42: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@usedcardboardboxes.myshopify.com/admin/api/2023-01/fulfillment_orders/' . $shopify_fulfillment_id . '/cancel.json');
					curl_setopt($ch_shopify, CURLOPT_HTTPHEADER, array(                                                                          
						'Content-Type: application/json',                                                                                
						'Content-Length: ' . strlen($data_string))                                                                       
					);

					curl_setopt($ch_shopify, CURLOPT_POST, 1);
					curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);
					
					$result = curl_exec($ch_shopify);
					
					var_dump($result);
					*/
				}
			}
				
			if ($shopify_fulfillment_id != "")
			{	
				//echo "<br><br>Step 3<br>";
			
				$data_found = "no"; $first_order = "no"; $shopify_order_fulfillment_id = 0;
				$tracking_number_arr = explode("," , $tracking_number_org);
				$tracking_urls_arr = explode("," , $tracking_urls);
				
				//print_r($tracking_urls_arr);
				
				$cnt = 0;
				$data_found = "yes";
				if (strtoupper($carrier_code_org) == "FEDEX"){
					$carrier_code = "FedEx";
				}
				else if (strtoupper($carrier_code_org) == "UPS"){
					$carrier_code = "UPS";
				}else{
					$carrier_code = $carrier_code_org;
				}	
				
				$shopify_fulfillment_line_item_arr = array(array(
					"fulfillment_order_id" => $shopify_fulfillment_id
				));
				
				$data = array( "fulfillment" => array(
					"location_id" => 40886534275, 
					"notify_customer" => true,
					"tracking_info" => array(
						"number" => strval($tracking_number_org),
						"company" => strval($carrier_code)
					),	
					"line_items_by_fulfillment_order" => $shopify_fulfillment_line_item_arr
				));
				
				//"url" => strval($tracking_urls)
				
				$data_string = json_encode($data);                                                                                   
				//var_dump($data_string);
				
				//fulfillment_orders/' . $shopify_order_no . '
				$ch_shopify = curl_init('https://0bb8ffb13131f78f928fc125ff6a9f42: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@usedcardboardboxes.myshopify.com/admin/api/2023-01/fulfillments.json');
				curl_setopt($ch_shopify, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($data_string))                                                                       
				);

				curl_setopt($ch_shopify, CURLOPT_POST, 2);
				curl_setopt($ch_shopify, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch_shopify, CURLOPT_RETURNTRANSFER, true);
				
				$result = curl_exec($ch_shopify);		
				
				//echo "<br><br>Step 4<br>";
				//var_dump($result);
				
				if ($result){		
					$jsonData = json_decode($result,true);
					
					//echo "<br><br>Step 5<br>";
					//print_r($jsonData);
					
					if ($jsonData["errors"]){
						echo "There is error <br>";
						print_r($jsonData);
					}else{
						echo "data updated <br>";
						db_query("Update orders set updated_tracking_shopify_flg = 1 where orders_id = " . $orders_id ,db() );
					}
					//$shopify_order_fulfillment_id = $jsonData["fulfillment"]["id"];	
					//echo "shopify_order_fulfillment_id  - " . $shopify_order_fulfillment_id;
				}
					
				$cnt = $cnt + 1;
			}
			
		}	
	}	
	
	//$tracking_urls = "'https://www.fedex.com/apps/fedextrack/?action=track&tracknumber=9541141&action=track'";
	//update_shopify_tracking_no(409668, 5108352057570, 9541141, 'Fedex', $tracking_urls);
	//exit;

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

	$query = "SELECT GROUP_CONCAT(p.products_id) as grp_prod_id FROM products p LEFT JOIN products_to_categories p2c ON p.products_id=p2c.products_id WHERE categories_id IN (46, 43, 44, 45, 49, 47, 48, 42, 50, 51, 52, 53, 54, 55, 62, 63, 64, 65, 66, 67, 68, 69, 70)";
	$rgp = array_shift(db_query($query, db()));
	$arr_rgp = explode(',', $rgp["grp_prod_id"]);
	
	// and orders.orders_id = 410449
	$sqlgetemp = "SELECT orders.orders_id, date_purchased, shopify_order_no FROM orders left JOIN orders_active_export ON orders.orders_id = orders_active_export.orders_id where shopify_order_no <> '' 
	and (tracking_number <> '' or ubox_order_tracking_number<> '')
	and updated_tracking_shopify_flg = 0 and `cancel` <> 'Yes' and orders.orders_id > 391425 group by orders.orders_id";
	//$sqlgetemp = "SELECT orders_id, shopify_order_no FROM orders where orders_id = 395609"; //391509
// dummy 391509 - 2892711559325
	//echo $sqlgetemp . "<br>";
	$ressqlgetemp = db_query($sqlgetemp,db() );
	while ($myrowselemp = array_shift($ressqlgetemp)) {
		$output_data = "yes"; $ubox_order = "no";
		
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

		$ubox_tracking = ""; $track_number = ""; $carrier_code = ""; $ubox_tracking_carrier = ""; $tracking_urls = "";
		$sqlgetemp1 = "SELECT orders.orders_id, orders.shopify_order_no, tracking_number, ubox_order_tracking_number, ubox_order_carrier_code FROM orders left JOIN orders_active_export ON orders.orders_id = orders_active_export.orders_id where orders.orders_id = " . $myrowselemp["orders_id"];
		$ressqlgetemp1 = db_query($sqlgetemp1,db() );
		while ($myrowselemp1 = array_shift($ressqlgetemp1)) {
			if ($myrowselemp1["ubox_order"] != "" ){
				if ($myrowselemp1["ubox_order_tracking_number"] != ""){
					$output_data = "yes";
				}else{
					$output_data = "no";
				}				
			}
			
			if ($ubox_order == "yes" && $myrowselemp1["ubox_order_tracking_number"] == ""){
				$output_data = "no";
			}
			
			if ($output_data == "yes") {
				if ($myrowselemp1["tracking_number"] != ""){
					$track_number .= $myrowselemp1["tracking_number"] . ",";
					$tracking_urls .= "'https://www.fedex.com/fedextrack/summary?trknbr=" . $myrowselemp1["tracking_number"] . "',";
					$carrier_code .= "FedEx,";
				}	

				if ($myrowselemp1["ubox_order_tracking_number"] != ""){
					$ubox_tracking = $myrowselemp1["ubox_order_tracking_number"];
					$ubox_tracking_arr = explode("," , $myrowselemp1["ubox_order_tracking_number"]);
					if (strpos($ubox_tracking, ",") > 0) {
						$ubox_tracking_cnt = count($ubox_tracking_arr);
					}else{
						$ubox_tracking_cnt = 1;
					}
					
					if ($ubox_tracking_cnt != $ucb_prod_id_cnt){
						$order_date = new DateTime($myrowselemp["date_purchased"]);
						$curr_date = new DateTime();
						//$order_date_diff = $curr_date->diff($order_date)->days;					
						
						$order_date_diff = getWorkdays($myrowselemp["date_purchased"], date("Y-m-d"));					
						
						//echo $myrowselemp["orders_id"] . " - order_date_diff - " . $order_date_diff . "<br>";
						if ($order_date_diff >= 2){
							$output_data = "yes";
						}else{
							$output_data = "no"; //as all Ubox order products have not received all tracking numbers
					
							echo "ubox_tracking_cnt - " . $ubox_tracking_cnt . " - " . $ucb_prod_id_cnt . " - " . $myrowselemp["orders_id"] . "<br>";
							
							$mailheadersadmin  = "From: UsedCardboardBoxes <NoReply@UsedCardboardBoxes.com>\n";
							$mailheadersadmin .= "MIME-Version: 1.0\r\n";
							$mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
							//echo $message;
							
							$message = "order # " . $myrowselemp["orders_id"] . "<br>";
							$message .= "Ubox Tracking numbers: " . $ubox_tracking . "<br>";
							$message .= "Ubox product cnt: " . $ucb_prod_id_cnt . "<br>";
							
							$resp = mail("prasad@extractinfo.com","Ubox Tracking numbers not matching with product count, order no. - " . $myrowselemp["orders_id"], $message, $mailheadersadmin);
						}			
					}
					$ubox_tracking_carrier = $myrowselemp1["ubox_order_carrier_code"];
				}	
			}	
		}
		if ($output_data == "yes") {
			if ($ubox_tracking != "" && $track_number != ""){
				$track_number .= $ubox_tracking;
				$carrier_code .= $ubox_tracking_carrier;
			}	
			if ($ubox_tracking != "" && $track_number == ""){
				$track_number = $ubox_tracking;
				$carrier_code = $ubox_tracking_carrier;
				//$tracking_urls = substr($tracking_urls, 0, strlen($tracking_urls)-1);
			}	

			if ($ubox_tracking == "" && $track_number != ""){
				$track_number = substr($track_number, 0, strlen($track_number)-1);
				$carrier_code = substr($carrier_code, 0, strlen($carrier_code)-1);
				$tracking_urls = substr($tracking_urls, 0, strlen($tracking_urls)-1);
			}	
			
			if (strpos(strtolower($carrier_code), "ups") > 0) {
			
			}else{
				$carrier_code = "FedEx";
			}
			
			echo "Order id - " . $myrowselemp["orders_id"] . " | " . $myrowselemp["shopify_order_no"] . ", tracking_number " . $track_number . " " . $carrier_code . " " . $tracking_urls . "<br>";
			//. " " . $tracking_urls
			
			//$myrowselemp["shopify_order_no"]
			//$carrier_code if FedEx,FedEx,fedex,fedex passed then link is not shown
			
			update_shopify_tracking_no($myrowselemp["orders_id"], $myrowselemp["shopify_order_no"], $track_number, $carrier_code, $tracking_urls);
		}	
	}	
?>
