<?php
/*
Page Name: cal_functions.php
Page created By: Amarendra
Page created On: 13-04-2021
Last Modified On: 
Last Modified By: Amarendra
Change History:
Date           By            Description
==================================================================================================================
13-04-21      Amarendra     This file is created to find the shipping quest, distance.
21-04-21	  Amarendra		Added function for finding distance from ipaddress.
==================================================================================================================
*/

//
function get_geolocation_google($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=AIzaSyBq4jZPJj02A76ujs5n4cNc7NtGuhAMb98";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
	var_dump($resp);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
         // get the important data
        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
        $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }
 
    else{
        echo "<strong>ERROR: {$resp['status']}</strong>";
        return false;
    }
}

function get_geolocation($ip) {
	if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
	$url = "https://api.ipgeolocationapi.com/geolocate/".$ip;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
	$result = curl_exec($curl);
	
	return $result;
}

function find_distance_ip($id, $ipadd){
	$qry = "Select * FROM loop_boxes WHERE id = '" . $id  . "'";
	$res = db_query($qry, db());		
	$row = array_shift($res);	
	$id2 = $row["b2b_id"];	
	//$sql1result = get_latlog(90010);
	$sql1result = get_latlog($rowb2b["location_zip"]);
	$locLat = $sql1result["latitude"];
	$locLong = $sql1result["longitude"];

	//$sql2result = get_geolocation(192.168.0.164);
	$loc = get_geolocation($ipadd);
	$location = json_decode($loc, true);
	$shipLat = $location['geo']['latitude'];
	$shipLong = $location['geo']['longitude'];
	
	$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
	$distLong = ($shipLong - $locLong) * 3.141592653 / 180;
	$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
	$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
	$miles_from=(int) (6371 * $distC * .621371192);
	
	return $miles_from;
}



function get_latlog($zip){
	$sql = "Select * from ZipCodes WHERE zip = '" . intval($zip) . "'";
	$sqlres = db_query($sql, db_b2b());
	if(count($sqlres)>0){
	$sqlresult = array_shift($sqlres);
	}else {
		$sql1 = "Select * from zipcodes_mexico WHERE zip = '" . intval($zip) . "'";
		$sqlres1 = db_query($sql1, db_b2b());
		if(count($sqlres1)>0){
			$sqlresult = array_shift($sqlres1);
		} else{
			$sql2 = "Select * from zipcodes_canada WHERE zip = '" . intval($zip) . "'";
			$sqlres2 = db_query($sql2, db_b2b());
			$sqlresult = array_shift($sqlres2);
		}
	}
	return $sqlresult;
}

function find_distance($id, $zip){
	$qry = "Select * FROM loop_boxes WHERE id = '" . $id  . "'";
	$res = db_query($qry, db());		
	$row = array_shift($res);	
	$id2 = $row["b2b_id"];	
	$qryb2b = "Select * FROM inventory WHERE id = '" . $id2 . "'";
	$resb2b = db_query($qryb2b, db_b2b() );		
	$rowb2b = array_shift($resb2b);
	
	$box_warehouse_id = $row["box_warehouse_id"];
	
	$qryb2b = "Select * FROM inventory WHERE id = '" . $id2 . "'";
	$resb2b = db_query($qryb2b, db_b2b() );		
	$rowb2b = array_shift($resb2b);
	$b2b_location_zip = "";
	if ($rowb2b["vendor_b2b_rescue"] != "" && $box_warehouse_id=="238"){
		$q1 = "SELECT * FROM loop_warehouse where id = ".$rowb2b["vendor_b2b_rescue"];
		$v_query = db_query($q1, db());
		while($v_fetch = array_shift($v_query))
		{
			$com_qry=db_query("select shipZip from companyInfo where ID='".$v_fetch["b2bid"]."'",db_b2b());
			$com_row= array_shift($com_qry);
			//
			$b2b_location_zip = $com_row["shipZip"]; 
		}
	}
	elseif($box_warehouse_id>0 && $box_warehouse_id!="238"){
		$lwqry = db_query("Select * from loop_warehouse where id = ".$box_warehouse_id, db() );
			while ($lwrow = array_shift($lwqry))
			{
				$b2b_location_zip = $lwrow["warehouse_zip"]; 
			}
	}	

	//$sql1result = get_latlog(90010);
	$sql1result2 = get_latlog($b2b_location_zip);
	$locLat = $sql1result2["latitude"];
	$locLong = $sql1result2["longitude"];

	//$sql2result = get_latlog(90025);
	$sql2result = get_latlog($zip);
	$shipLat = $sql2result["latitude"];
	$shipLong = $sql2result["longitude"];
	
	$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
	$distLong = ($shipLong - $locLong) * 3.141592653 / 180;
	$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
	$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
	$miles_from =(int) (6371 * $distC * .621371192);
	
	return $miles_from;
}


function uber_freight_data($proid, $orderData){

	$avg_quote_amount_tot = 0;
	$error_str = "";
	$url = 'https://login.uber.com/oauth/v2/token';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=HIpNTYMMUZbj_KGeBqLST9D1FCZ6bT2B&client_secret=YFjURNQqmTqXRFdTOlyEUpba0Q3Rvq0ftcQ_ujFe&grant_type=client_credentials&scope=freight.loads");

	// In real life you should use something like:
	// curl_setopt($ch, CURLOPT_POSTFIELDS, 
	//          http_build_query(array('postvar1' => 'value1')));

	// Receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$res_Data = curl_exec($ch);
	
	$jsonData = json_decode($res_Data, true);

	curl_close ($ch);

	$client_auth_code = "";
	if (isset($jsonData["code"])) {
		$error_str = "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
	}else{		
		//"{"access_token":"JA.VUNmGAAAAAAAEgASAAAABwAIAAwAAAAAAAAAEgAAAAAAAAHQAAAAFAAAAAAADgAQAAQAAAAIAAwAAAAOAAAApAAAABwAAAAEAAAAEAAAADzmoONAboovssJm-_-brEyAAAAA1N5aTk7mKqW3eGCnnIwPVAPboKd4kmu08ebl4hCkrDM2hAqO41XyWrYNzDN1Y71BAkIgY5lOsoZRiPg6_bSsd0wFuTEaPgDpjcFJA94rCFO0kRsv2KV_R25bXP-wt8DdJgBtSQiNNrKRuMtmPacgCO6W-vxHlFWW3VDeN8qsDzkMAAAA1lWENOfWpive5TfoJAAAAGIwZDg1ODAzLTM4YTAtNDJiMy04MDZlLTdhNGNmOGUxOTZlZQ","token_type":"Bearer","expires_in":2592000,"scope":"freight.loads"}
		$client_auth_code = $jsonData["access_token"];
	}	
	
	//$url = 'https://sandbox-api.uber.com/v1/freight/loads/quotes';
	$url = 'https://api.uber.com/v1/freight/loads/quotes';

	$bweight = 0; $vendor_b2b_rescue = 0; $vendor_b2b_rescue_b2bid = 0; $box_weight = 0;
	$pickup_nm = ""; $pickup_add1 = ""; $pickup_add2 = ""; $dropoff_country = "";
	$pickup_city = ""; $pickup_state = "";  $pickup_zip = "";  $pickup_country = "";
	$dropoff_nm = ""; $dropoff_add1 = ""; $dropoff_add2 = ""; $box_id = 0; $box_inv_id = 0; 
	$dropoff_city = ""; $dropoff_state = "";  $dropoff_zip = ""; $box_description = ""; 

	//case to handle UCB warehouse (HA, ML, HV, HK Trans) 
	$rec_found_sorting_w = "no";
	$sql_data = "Select * from inventory Where loops_id = '" . $proid . "'";
	$dt_view_res_data = db_query($sql_data, db_b2b() );
	while ($myrowsel_b2b = array_shift($dt_view_res_data))
	{
		$box_id	= $myrowsel_b2b["loops_id"];
		$box_inv_id = $myrowsel_b2b["ID"];

		$vendor_b2b_rescue = $myrowsel_b2b["vendor_b2b_rescue"];
		$box_description = $myrowsel_b2b["description"];

		$box_warehouse_id = 0;
		$sql_data_child = "Select * from loop_boxes Where b2b_id = " . $myrowsel_b2b["ID"];
		$dt_view_res_data_child = db_query($sql_data_child, db() );
		while ($myrowsel_child = array_shift($dt_view_res_data_child)){
			$bweight = $myrowsel_child["bweight"];
			$box_warehouse_id = $myrowsel_child["box_warehouse_id"];
		}
			
		$box_weight = $myrowsel_b2b["quantity"] * $bweight;
		
		$b2b_location_add1 = $myrowsel_b2b["location_add1"];
		$b2b_location_add2 = $myrowsel_b2b["location_add2"];
		$b2b_location_city = $myrowsel_b2b["location_city"];
		$b2b_location_st = $myrowsel_b2b["location_state"];
		$b2b_location_zip = $myrowsel_b2b["location_zip"];
		$b2b_location_country = $myrowsel_b2b["location_country"];
		
	}
	
	if ($vendor_b2b_rescue > 0 && $box_warehouse_id == 238) {
		$vendor_b2b_rescue = $vendor_b2b_rescue;
	}

	if ($box_warehouse_id > 0 && $box_warehouse_id != 238) {
		$vendor_b2b_rescue = $box_warehouse_id;
	}
	
	if ($box_warehouse_id == 0) {
		$nowarehouse = "yes";	
		$error_str = "<font color=red>Inventory Item does not have a selected Warehouse on the spec page, inform Inventory Manager ASAP</font>";
	}

	$dt_view_res_data = db_query("Select * from companyInfo where loopid = " . $vendor_b2b_rescue, db_b2b() );
	while ($myrowsel_b2b = array_shift($dt_view_res_data))
	{
		$vendor_b2b_rescue_b2bid = $myrowsel_b2b["ID"]; 
		$pickup_nm = strval(get_nickname_val($myrowsel_b2b["company"] , $myrowsel_b2b["ID"])); 
		//$pickup_nm = str_replace("-", " " , $pickup_nm);
		//$pickup_nm = str_replace(",", " " , $pickup_nm);
		$pickup_add1 = strval($myrowsel_b2b["shipAddress"]); 
		$pickup_add2 = strval($myrowsel_b2b["shipAddress2"]); 
		$pickup_city = strval($myrowsel_b2b["shipCity"]); 
		$pickup_state = strval($myrowsel_b2b["shipState"]); 
		$pickup_country = $myrowsel_b2b["shipcountry"]; 
		if (strtolower($myrowsel_b2b["shipcountry"]) == "usa"){
			$pickup_zip = substr(strval($myrowsel_b2b["shipZip"]),0,5); 
		}else{
			$pickup_zip = $myrowsel_b2b["shipZip"]; 
		}	
		
	}

	if ($vendor_b2b_rescue_b2bid == 0){
		$dt_view_res_data = db_query("Select * from loop_warehouse where id = " . $vendor_b2b_rescue, db() );
		while ($myrowsel_b2b = array_shift($dt_view_res_data))
		{
			$vendor_b2b_rescue_b2bid = $myrowsel_b2b["id"]; 
			$pickup_nm = $myrowsel_b2b["company_name"]; 
			$pickup_add1 = strval($myrowsel_b2b["company_address1"]); 
			$pickup_add2 = strval($myrowsel_b2b["company_address2"]); 
			$pickup_city = strval($myrowsel_b2b["company_city"]); 
			$pickup_state = strval($myrowsel_b2b["company_state"]); 
			$pickup_country = $myrowsel_b2b["warehouse_country"]; 
			if (strtolower($myrowsel_b2b["warehouse_country"]) == "usa" || $myrowsel_b2b["warehouse_country"] == ""){
				$pickup_zip = substr(strval($myrowsel_b2b["company_zip"]),0,5); 
			}else{
				$pickup_zip = $myrowsel_b2b["shipZip"]; 
			}	
		}
	}

	$dropoff_nm = $orderData['shippingaddCompny'];
	$dropoff_add1 = $orderData['shippingAdd1'];
	$dropoff_add2 = $orderData['shippingAdd2'];
	$dropoff_city = $orderData['shippingaddCity'];
	$dropoff_state = $orderData['shippingaddState'];
	$dropoff_zip = $orderData['shippingaddZip'];
	
	$donotprocess = "no";
	if ($pickup_nm == ""){
		$pickup_nm = "Facility pickup - " . $vendor_b2b_rescue_b2bid;
	}	
	if ($dropoff_nm == ""){
		$dropoff_nm = "Facility dropoff";
	}	
	
	//$error_str = "Pickup_add1 = " . $pickup_add1 . " - " . $pickup_city . " - " . $pickup_state . " - " . $pickup_zip . " - " . $dropoff_add1 . " - " . $dropoff_city . " - " . $dropoff_state . " - " . $dropoff_zip . "<br>";
	
	if ($pickup_add1 == "" || $pickup_city == "" || $pickup_state == "" || $pickup_zip == "" || 
	$dropoff_add1 == "" || $dropoff_city == "" || $dropoff_state == "" || $dropoff_zip == ""){
		$donotprocess = "yes";
		$error_str =  "<font color=red>Pickup/Drop off address1/city/state/zip is blank, process terminated.</font>";
	}
		
	if ($client_auth_code == ""){
		$donotprocess = "yes";
		$error_str =  "<font color=red>Uber Authentication token is empty, process terminated.</font>";
	}

	//Calculate the distance in miles
	//echo "pickup_zip - " . $pickup_zip . " Dropoff_zip - "  . $dropoff_zip . "<br>";
	$transit_time = 0;
	if ($pickup_zip != "" && $dropoff_zip != "") { 
		$tmppos_1 = strpos($pickup_zip, " ");
		if (strtolower($pickup_country) == "canada")
		{ 	
			//$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
			$tmp_zipval = str_replace(" ", "", $pickup_zip);
			$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
		}else {
			$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($pickup_zip) . "'";
		}
		
		$res4 = db_query($zipStr, db_b2b());
		$objShipZip= array_shift($res4);		

		$shipLat = $objShipZip["latitude"];
		$shipLong = $objShipZip["longitude"];

		$location_zip = $dropoff_zip;
		
		//$zipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($objInvmatch["location"]);
		$zipStr= "";
		$tmppos_1 = strpos($location_zip, " ");
		if ($tmppos_1 != false)
		{ 	
			//$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
			$tmp_zipval = str_replace(" ", "", $location_zip);
			$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
		}else {
			$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($location_zip) . "'";
		}
		
		$dt_view_res4 = db_query($zipStr,db_b2b() );
		while ($ziploc = array_shift($dt_view_res4)) {
			$locLat = $ziploc["latitude"];
			
			$locLong = $ziploc["longitude"];
		}

		$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
		$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

		$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);

		$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));
		$distance = (int) (6371 * $distC * .621371192); 
		//echo "Distance: ". $distance . " miles<BR>";
		
		//$transit_time = ceil($distance/500);
		$transit_time = ceil($distance/700);

		//echo $transit_time . "<br>";
	}			 
	
	if ($transit_time == 0){
		//$transit_time = 1;
		$donotprocess = "yes";
		$error_str = "<font color=red>Cannot calculate freight rate, please contact your Account Manager for an updated freight quote.</font>";
	}
	
	if ($donotprocess == "no")
	{	
		
		$freight_str = ""; $quote_amount_tot = 0; $ifany_err = "";
	 
		for ($tmpcnt = 1; $tmpcnt <= 1; $tmpcnt++) { 
			$tmpcnt = 2; 				// as Pickup date after 2 Days

			$date = new DateTime(); // format: MM/DD/YYYY
			$datenxt = new DateTime(); // format: MM/DD/YYYY
			$interval = new DateInterval('P' . $tmpcnt. 'DT1H');
			$date->add($interval);
			$datenxt->add($interval);
			
			if($date->format('N') == 6){
				$date->modify( '+2 day' ); 
				$datenxt->modify( '+2 day' );
			}
			if($date->format('N') == 7){
				$date->modify( '+1 day' ); 
				$datenxt->modify( '+1 day' );
			}
			$starttime = $date->format('U');
			$endtime = $datenxt->format('U');
			$starttime = floatval($starttime);
			$endtime = floatval($endtime);
			
			$date2 = new DateTime();
			$date3 = new DateTime();
			$interval2 = new DateInterval('P' . $tmpcnt. 'DT1H');
			$date2->add($interval2);
			$date3->add($interval2);
			if($date2->format('N') == 6){
				$date2->modify( '+2 day' ); 
				$date3->modify( '+2 day' );
			}
			if($date2->format('N') == 7){
				$date2->modify( '+1 day' ); 
				$date3->modify( '+1 day' );
			}
			$interval3 = new DateInterval('P' . $transit_time . 'DT1H');
			$date2->add($interval3);
			$date3->add($interval3);

			//echo "To date - " . $date2->format('m/d/Y') . "<br>";
			
			$starttime_2 = $date2->format('U');
			$endtime_2 = $date3->format('U');
			$starttime_2 = floatval($starttime_2);
			$endtime_2 = floatval($endtime_2);
			
			/*
			$date = new DateTime(); // format: MM/DD/YYYY
			$datenxt = new DateTime(); // format: MM/DD/YYYY
			$interval = new DateInterval('P' . $tmpcnt. 'DT1H');
			$date->add($interval);
			$starttime = $date->format('U');
			//echo $date->format('m/d/Y') . "<br>";

			$interval = new DateInterval('P' . $tmpcnt . 'DT1H');
			$datenxt->add($interval);
			$endtime = $datenxt->format('U');
			//echo $datenxt->format('m/d/Y') . "<br>";
			$starttime = floatval($starttime);
			$endtime = floatval($endtime);

			$date2 = new DateTime(); // format: MM/DD/YYYY
			$interval = new DateInterval('P' . ($transit_time + $tmpcnt). 'DT1H');
			$date2->add($interval);
			$starttime_2 = $date2->format('U');
			//echo $date2->format('m/d/Y') . "<br>";

			$date3 = new DateTime(); // format: MM/DD/YYYY
			$interval = new DateInterval('P' . ($transit_time + $tmpcnt) . 'DT1H');
			$date3->add($interval);
			$endtime_2 = $date3->format('U');
			//echo $date3->format('m/d/Y') . "<br><br>";
			
			$starttime_2 = floatval($starttime_2);
			$endtime_2 = floatval($endtime_2);
			*/
			
			$vendor_b2b_rescue_b2bid = strval($vendor_b2b_rescue_b2bid);
			$compid = 99999;
			$compid = strval($compid); //strval($vendor_b2b_rescue_b2bid); //as company is not directly relate to box for drop off 

			$uber_qote_id = 0;
			$dt_view_res_data = db_query("Select max(unqid) as maxunqid from quoting_uber_freight_data", db_b2b() );
			while ($myrowsel_b2b = array_shift($dt_view_res_data))
			{
				$uber_qote_id = $myrowsel_b2b["maxunqid"];
				$uber_qote_id = $uber_qote_id + 1;
			}	
			
			/*$starttime = floatval("1589184000");
			$endtime = floatval("1589209200");
			
			$starttime_2 = floatval("1589443200");
			$endtime_2 = floatval("1589468400");
			*/
			
			//echo "Starttime - " . $starttime, ' - End_time_utc - ' . $endtime . "<br>";
			//echo "Starttime - " . $starttime_2, ' - End_time_utc - ' . $endtime_2 . "<br>";
			
			$stoparr = array(array('sequence_number'=> 1,'type'=> 'PICKUP', 'mode'=> 'LIVE', 'facility'=>array('facility_id'=> $vendor_b2b_rescue_b2bid, 'name'=> $pickup_nm , 
			'address'=>array('line1'=> $pickup_add1, 'line2'=> $pickup_add2, 'city'=> $pickup_city,  'principal_subdivision'=> $pickup_state, 'postal_code'=> $pickup_zip, 'country'=> 'USA')), 
			'appointment'=>array('status'=> 'NEEDED', 'start_time_utc'=> $starttime, 'end_time_utc'=> $endtime)), 
			array('sequence_number'=> 2,'type'=> 'DROPOFF', 'mode'=> 'LIVE', 
			'facility'=>array('facility_id'=> $compid, 'name'=> $dropoff_nm, 
			'address'=>array('line1'=> $dropoff_add1, 'line2'=> $dropoff_add2, 'city'=> $dropoff_city,  'principal_subdivision'=> $dropoff_state, 'postal_code'=> $dropoff_zip, 'country'=> 'USA')), 
			'appointment'=>array('status'=> 'NEEDED', 'start_time_utc'=> $starttime_2, 'end_time_utc'=> $endtime_2)));
			
			$quote_req = array('quote_id'=> 'UCB_B2B_id'. $uber_qote_id,'customer_id'=>'USEDCARDBOARDBOXES', 
			'requirements'=> array('vehicle_type'=> 'DRY','weight'=>array('amount'=> $box_weight,'unit'=> 'LB')), 
			'stops'=>$stoparr, 'quote_type' => '');
			
			//LHR_ONLY
			
			$ch = curl_init();
			//print_r($quote_req);
				
			$json = json_encode($quote_req);
			//var_dump($json);

			//echo "<br>";
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			
			// Returns the data/output as a string instead of raw data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			//Set your auth headers
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Authorization: Bearer ' . $client_auth_code
			   ));

			$data = curl_exec($ch);

			$jsonData = json_decode($data,true);
			curl_close($ch);

			//var_dump($jsonData);
			//array(6) { ["status"]=> string(6) "ACCEPT" ["uber_quote_uuid"]=> string(36) "36d6cd29-4274-4021-9a86-f5dd750fbf1b" ["price"]=> array(2) { ["amount"]=> int(40700) ["currency_code"]=> string(3) "USD" } ["expiration_time_utc"]=> string(10) "1588423434" ["notes"]=> string(0) "" ["uber_quote_id"]=> string(10) "1470517115" } 
			//$jsonData = '{ ["status"]=> string(6) "ACCEPT" ["uber_quote_uuid"]=> string(36) "36d6cd29-4274-4021-9a86-f5dd750fbf1b" ["price"]=> array(2) { ["amount"]=> int(40700) ["currency_code"]=> string(3) "USD" } ["expiration_time_utc"]=> string(10) "1588423434" ["notes"]=> string(0) "" ["uber_quote_id"]=> string(10) "1470517115" }';
			
			if (isset($jsonData["code"])) {
				$error_str = "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
				//print_r($quote_req);
				$ifany_err = "yes";
				break;
			}else{		
				$dateInLocal = date("m/d/Y H:i:s" , gmdate($jsonData["expiration_time_utc"]));
				$quote_amount = $jsonData["price"]["amount"]/100;
				$quote_amount_tot = $quote_amount_tot + $quote_amount;
			
				/*$freight_str .= "Quote for '" . $box_description . "' <br> Pickup :" . 
				$pickup_nm . " " . $pickup_add1 . " " . $pickup_add2 . " " . $pickup_city . "," . $pickup_state . " " .	$pickup_zip . " <br>" . 
				"<br>Drop off :" . 
				$dropoff_nm . " " . $dropoff_add1 . " " . $dropoff_add2 . " " . $dropoff_city . "," . $dropoff_state . " " . $dropoff_zip . " <br>" .
				"<br>Box weight (in Lb): " . $box_weight .;
				*/
				
				$freight_str .=  "<br>Response from Uber Freight " . 
				"<br>Quote ID: " . $jsonData["uber_quote_id"] . 
				"<br>Start Date: " . $date->format('m/d/Y') . 
				"<br>End Date: " . $date2->format('m/d/Y') . 
				"<br>Quote status: " . $jsonData["status"] . 
				"<br><b>Quote amount: $" . number_format($quote_amount,2) . "</b>" .  
				"<br>Expiration Time: " . $dateInLocal . 
				"<br>Notes : " . $jsonData["notes"].
				"<br>"; 
				
				$res_ins_qry = "Insert into quoting_uber_freight_data (company_id, cart_item_id, box_id, box_inv_id, pickup_zip, dropoff_zip, box_weight, uber_quote_id, uber_quote_status,
				uber_quote_amount, uber_quote_exp_time, uber_quote_uuid, uber_quote_note, pickup_comp_id, pickup_nm, pickup_add1, pickup_add2, pickup_city, pickup_state, pickup_starttime,
				pickup_endtime, dropoff_nm, dropoff_add1, dropoff_add2, dropoff_city, dropoff_state, dropoff_starttime, dropoff_endtime, 
				transit_time, process_date_time ) select '" . $compid . "',
				'" . $cart_itemID . "', '" . $box_id . "', '" . $box_inv_id . "', '" . $pickup_zip . "', '" . $dropoff_zip . "', '" . $box_weight . "', '" . $jsonData["uber_quote_id"] . "', '" . $jsonData["status"] . "',
				'" . $quote_amount . "', '" . $jsonData["expiration_time_utc"] . "', '" . $jsonData["uber_quote_uuid"] . "', '" . str_replace("'", "\'" , $jsonData["notes"]) . "', 
				'" . $vendor_b2b_rescue_b2bid . "', '" . str_replace("'", "\'" , $pickup_nm) . "', '" . str_replace("'", "\'" , $pickup_add1) . "', '" . str_replace("'", "\'" , $pickup_add2) . "', '" . str_replace("'", "\'" , $pickup_city) . "', '" . str_replace("'", "\'" , $pickup_state) . "', '" . $starttime . "',
				'" . $endtime . "', '" . str_replace("'", "\'" , $dropoff_nm) . "', '" . str_replace("'", "\'" , $dropoff_add1) . "', '" . str_replace("'", "\'" , $dropoff_add2) . "', '" . str_replace("'", "\'" , $dropoff_city) . "', '" . str_replace("'", "\'" , $dropoff_state) . "',
				'" . $starttime_2 . "', '" . $endtime_2 . "', '" . $transit_time . "', '" . date("Y-m-d H:i:s") . "'";
				
				//echo $res_ins_qry . "<br>";
				db_query($res_ins_qry, db_b2b());
				
			}
		}
		
		if ($ifany_err == ""){
			$avg_quote_amount_tot = number_format($quote_amount_tot*1.01,2);
			//$avg_quote_amount_tot = number_format(($quote_amount_tot/7)*1.01,2);
			//echo $avg_quote_amount_tot;
			
			/*echo "&nbsp;Quote for '" . $box_description . "' <br> Pickup :" . 
			$vendor_b2b_rescue_b2bid . " = "  .$pickup_nm . " " . $pickup_add1 . " " . $pickup_add2 . " " . $pickup_city . "," . $pickup_state . " " .	$pickup_zip . " <br>" . 
			"<br>Drop off :" . 
			$compid . " = " . $dropoff_nm . " " . $dropoff_add1 . " " . $dropoff_add2 . " " . $dropoff_city . "," . $dropoff_state . " " . $dropoff_zip . " <br>" .
			"<br>Box weight (in Lb): " . $box_weight .
			$freight_str . "";*/
		}
		
	}
	
	if ($error_str != ""){
		return $error_str;
	}else{
		return $avg_quote_amount_tot;
	}	
}
?>