<?php 
ini_set("display_errors", "1");
error_reporting(E_ALL);

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

require_once('../fedex/library/fedex-common.php5');

//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
//$path_to_wsdl = "wsdl/TrackService_v4.wsdl";
$path_to_wsdl = "../fedex/wsdl/TrackService_v4.wsdl";

function curl_get_file_contents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if ($contents) return $contents;
    else return FALSE;
}
//updated_tracking_ubox_shopify_flg = 0

//$orders_tracking_res = db_query(("SELECT orders.orders_id, orders.ubox_order_tracking_number, orders.date_purchased, orders.user_ipaddress, orders.customers_name, orders.ubox_order_carrier_code FROM orders WHERE orders.ubox_order_tracking_number != '' AND YEAR(orders.date_purchased) >= ".date('Y')." and orders.updated_tracking_ubox_shopify_flg = 0 ORDER BY orders.date_purchased ASC "), db());
$orders_tracking_res = db_query(("SELECT orders.orders_id, orders.ubox_order_tracking_number, orders.date_purchased, orders.user_ipaddress, orders.customers_name, orders.ubox_order_carrier_code FROM orders WHERE orders.ubox_order_tracking_number != '' AND YEAR(orders.date_purchased) >= ".date('Y')." and orders.usps_tracking_delivery_status = 0 ORDER BY orders.date_purchased ASC "), db());

//echo "orders_tracking_res - <pre>"; print_r($orders_tracking_res); echo "</pre>"; 
$order_id_list = "";
$arrCarrierCode = $arrTrackNum = array();
while ($orders_tracking = array_shift($orders_tracking_res)) {
	$arrOrdrTrackngNo = explode(",", $orders_tracking["ubox_order_tracking_number"]);
	$order_id_list .= $orders_tracking["orders_id"] . ",";
	//echo "arrOrdrTrackngNo - <pre>"; print_r($arrOrdrTrackngNo); echo "</pre>";
	if(strpos($orders_tracking["ubox_order_carrier_code"], ",") !== false){
		$carrierCode = ''; 
		$arrCarrierCode = explode(",", $orders_tracking["ubox_order_carrier_code"]);
		//echo "<pre>"; print_r($arrCarrierCode); echo "</pre>";
		for($i = 0; $i < count($arrOrdrTrackngNo); $i++){
			$carrierCode = $arrCarrierCode[$i];
		}
	}else{
		$carrierCode = $orders_tracking["ubox_order_carrier_code"];
	}

	foreach ($arrOrdrTrackngNo as $arrTracNoIdKey => $arrTracNoIdValue) {
		$arrTrackNum[$arrTracNoIdValue]['tracking_number'] 	= $arrTracNoIdValue;
		$arrTrackNum[$arrTracNoIdValue]['orders_id'] 		= $orders_tracking["orders_id"];
		$arrTrackNum[$arrTracNoIdValue]['date_purchased'] 	= $orders_tracking["date_purchased"];
		$arrTrackNum[$arrTracNoIdValue]['user_ipaddress']	= $orders_tracking["user_ipaddress"];
		$arrTrackNum[$arrTracNoIdValue]['customers_name'] 	= $orders_tracking["customers_name"];
		$arrTrackNum[$arrTracNoIdValue]['carrier_code'] 	= $carrierCode;
	}
}

if ($order_id_list != ""){
	$order_id_list = substr($order_id_list, 0 , strlen($order_id_list)-1);
}
//echo "arrTrackNum - <pre>"; print_r($arrTrackNum); echo "</pre>";

foreach ($arrTrackNum as $arrTrackNumKey => $arrTrackNumValue) {
	//echo "<pre>"; print_r($arrTrackNumValue); echo "</pre>";

	if($arrTrackNumValue['tracking_number'] != ''){ 
		if($arrTrackNumValue['carrier_code'] == 'fedex'){ 
		
			$orderID 			= $arrTrackNumValue['orders_id'];
			$trackingNumber 	= $arrTrackNumValue['tracking_number'];
			$clientIP 			= $arrTrackNumValue["user_ipaddress"];
			$sourceId  			= $arrTrackNumValue["customers_name"];
			/*$prodUrl 			= "https://secure.shippingapis.com/ShippingAPI.dll";
			$service 			= "TrackV2";*/

			/*Start fedex */
			ini_set("soap.wsdl_cache_enabled", "0");
			$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
			$request['WebAuthenticationDetail'] = array('UserCredential' =>
																  array('Key' => getProperty('key'), 'Password' => getProperty('password')));
			$request['ClientDetail'] = array('AccountNumber' => getProperty('shipaccount'), 'MeterNumber' => getProperty('meter'));
			$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request v4 using PHP ***');
			$request['Version'] = array('ServiceId' => 'trck', 'Major' => '4', 'Intermediate' => '1', 'Minor' => '0');
			$request['PackageIdentifier'] = array(
							'Value' => $trackingNumber, // Replace 'XXX' with a valid tracking identifier
							'Type' => 'TRACKING_NUMBER_OR_DOORTAG');

			try 
			{
				if(setEndpoint('changeEndpoint'))
				{
					$newLocation = $client->__setLocation(setEndpoint('endpoint'));
				}
				
				$response = $client ->track($request);

				//if ($response -> HighestSeverity != 'FAILURE' || $response -> HighestSeverity != 'ERROR')
				var_dump($response);
				
				if ($response -> HighestSeverity == 'SUCCESS')
				{
					echo $trackingNumber. "<br><br><br>";
					$uboxOrderFedexStatus = "";
					foreach($response->TrackDetails as $key => $value){
						if ($key != "" && $key == "StatusCode") {
							$uboxOrderFedexStatus = $value;
							break;
						}
					}
					
					$tmparr = $response->TrackDetails->OtherIdentifiers[0];
					
					//var_dump($tmparr);
					$product_details = "";
					if ($tmparr){
						$product_details = $tmparr->Value;
					}	
					
					$uboxOrderFedexDescription = "";
					foreach($response->TrackDetails as $key => $value){
						if ($key != "" && $key == "StatusDescription") {
							$uboxOrderFedexDescription = $value;
							break;
						}
					}

					echo "<BR>";
					echo "uboxOrderFedexStatus = " . $uboxOrderFedexStatus . " ". $uboxOrderFedexDescription . "<br>";
					echo "<BR>";

					//$uboxOrderFedexStatus = getStatus($response->TrackDetails); //'DE'; 
					//$uboxOrderFedexDescription = getDetails($response->TrackDetails); //'Delivery exception'; 

					$selUboxDtsTrkngNo = db_query("SELECT ubox_order_tracking_number FROM  ubox_order_fedex_details WHERE ubox_order_tracking_number = '".$trackingNumber."' AND  orders_id = ".$orderID , db()); 

					if(empty($selUboxDtsTrkngNo[0]['ubox_order_tracking_number'])){
						//echo "<br />INSERT into ubox_order_fedex_details(orders_id,ubox_order_tracking_number,ubox_order_fedex_status,ubox_order_fedex_description) VALUES(".$orderID.",'".$trackingNumber."','".$uboxOrderFedexStatus."','".$uboxOrderFedexDescription."')";
						db_query("INSERT into ubox_order_fedex_details(orders_id,ubox_order_tracking_number,ubox_order_fedex_status,ubox_order_fedex_description, product_description) VALUES(".$orderID.",'".$trackingNumber."','".$uboxOrderFedexStatus."','".$uboxOrderFedexDescription."', '" . $product_details . "')",db());
						echo "INSERT into ubox_order_fedex_details(orders_id,ubox_order_tracking_number,ubox_order_fedex_status,ubox_order_fedex_description, product_description) VALUES(".$orderID.",'".$trackingNumber."','".$uboxOrderFedexStatus."','".$uboxOrderFedexDescription."', '" . $product_details . "' <br>";
					}else{
						db_query("UPDATE ubox_order_fedex_details SET ubox_order_fedex_status = '".$uboxOrderFedexStatus."', ubox_order_fedex_description = '".$uboxOrderFedexDescription."', product_description = '" . $product_details . "' WHERE ubox_order_tracking_number = '".$trackingNumber."' AND orders_id = ".$orderID, db());
						echo "UPDATE ubox_order_fedex_details SET ubox_order_fedex_status = '".$uboxOrderFedexStatus."', ubox_order_fedex_description = '".$uboxOrderFedexDescription."', product_description = '" . $product_details . "' WHERE ubox_order_tracking_number = '".$trackingNumber."' AND orders_id = ".$orderID . "<br>";
					}
					
					db_query("Update orders set updated_tracking_ubox_shopify_flg = 1 WHERE orders_id = '". $orderID ."'" , db()); 
				}
				else
				{
					echo "Error - " . $trackingNumber . "<BR>";
				} 

			} catch (SoapFault $exception) {
				//printFault($exception, $client);
				echo "BAD Error" . $trackingNumber. "<BR>";
			}
		}
		
		if($arrTrackNumValue['carrier_code'] == 'usps'){ 
			//echo "<br /> id -> ".$arrTrackNumValue['orders_id']."  / trackingNumber -> ".$arrTrackNumValue['tracking_number']."  / date_purchased ->  ".$arrTrackNumValue['date_purchased']."  / user_ipaddress -> ".$arrTrackNumValue['user_ipaddress']."  / customers_name -> ".$arrTrackNumValue['customers_name']."  / carrierCode -> ".$arrTrackNumValue['carrier_code'];
			
			$orderID 			= $arrTrackNumValue['orders_id'];
			$trackingNumber 	= $arrTrackNumValue['tracking_number'];
			$clientIP 			= $arrTrackNumValue["user_ipaddress"];
			$sourceId  			= $arrTrackNumValue["customers_name"];
			$prodUrl 			= "https://secure.shippingapis.com/ShippingAPI.dll";
			$service 			= "TrackV2";

			$req_xmlTrack 		= "<TrackFieldRequest USERID=\"542USEDC8097\">
					<Revision>1</Revision>
					<ClientIp>$clientIP</ClientIp>
					<SourceId>$sourceId</SourceId>
					<TrackID ID=\"$trackingNumber\"/>			
				</TrackFieldRequest>";

			$doc_stringTrack = preg_replace('/[\t\n]/', '', $req_xmlTrack);
			$doc_stringTrack = urlencode($doc_stringTrack);
			$requestXML = $prodUrl . "?API=" . $service . "&XML=" . $doc_stringTrack;
			
			$responceTrack = curl_get_file_contents($requestXML);

			$TrackResponse = simplexml_load_string($responceTrack) or die('Can not create objects');

			echo "<pre>"; print_r($TrackResponse); echo "</pre>";
			echo "<br /> Service -> ".$TrackResponse->TrackInfo->Service[0];
			echo "<br /> StatusCategory -> ".$TrackResponse->TrackInfo->StatusCategory;
			echo "<br /> StatusSummary -> ".$TrackResponse->TrackInfo->StatusSummary;
			echo "<br /><br /><br /> ";

			/*Set ubox USPS details in ubox_order_fedex_details tbl start*/
			if ($TrackResponse->TrackInfo->StatusCategory == "Delivered"){
				$uboxOrderUspsStatus 		= 'DL';
			}else{
				$uboxOrderUspsStatus 		= $TrackResponse->TrackInfo->StatusCategory;
			}		
			$uboxOrderUspsDescription 	= $TrackResponse->TrackInfo->StatusSummary;
			$selUboxDtsTrkngNo = db_query("SELECT ubox_order_tracking_number FROM  ubox_order_fedex_details WHERE ubox_order_tracking_number = ".$trackingNumber." AND  orders_id = ".$orderID , db()); 

			if(empty($selUboxDtsTrkngNo[0]['ubox_order_tracking_number'])){
				db_query("INSERT INTO ubox_order_fedex_details(orders_id, ubox_order_tracking_number, ubox_order_fedex_status, ubox_order_usps_description, usps_order) VALUES(".$orderID.", '".$trackingNumber."', '".$uboxOrderUspsStatus."', '".$uboxOrderUspsDescription."', 1)",db());
				echo "INSERT INTO ubox_order_fedex_details(orders_id, ubox_order_tracking_number, ubox_order_fedex_status, ubox_order_usps_description, usps_order) VALUES(".$orderID.", '".$trackingNumber."', '".$uboxOrderUspsStatus."', '".$uboxOrderUspsDescription."', 1)<br>";
			}else{
				db_query("UPDATE ubox_order_fedex_details SET ubox_order_fedex_status = '".$uboxOrderUspsStatus."', ubox_order_usps_description = '".$uboxOrderUspsDescription."' WHERE ubox_order_tracking_number = ".$trackingNumber." AND orders_id = ".$orderID, db());
				echo "UPDATE ubox_order_fedex_details SET ubox_order_fedex_status = '".$uboxOrderUspsStatus."', ubox_order_usps_description = '".$uboxOrderUspsDescription."' WHERE ubox_order_tracking_number = ".$trackingNumber." AND orders_id = ".$orderID . "<br>";
			}
			/*Set ubox USPS details in ubox_order_fedex_details tbl end*/

		}
	}// end of tracking_number condition
	
}




$order_id_array = explode(",", $order_id_list);
foreach ($order_id_array as $order_id_array_val) {	
	$update_flg = ""; 
	$orders_tracking_res = db_query(("SELECT ubox_order_fedex_status FROM ubox_order_fedex_details WHERE orders_id = " . $order_id_array_val . ""), db());
	while ($orders_tracking = array_shift($orders_tracking_res)) {
		$update_flg = "yes"; 
		if ($orders_tracking["ubox_order_fedex_status"] != 'DL') {
			$update_flg = "no"; 
		}	
	}

	if ($update_flg == "yes") {
		db_query("UPDATE orders SET usps_tracking_delivery_status = 1 WHERE orders_id = '". $order_id_array_val ."'" , db()); 
	}	

}
	
$datewtime = date("F j, Y, g:i a"); 

db_query("UPDATE tblvariable SET variablevalue = '" . $datewtime . "' WHERE variablename = 'ubox_tracking_usps_updation'",db() );
?>