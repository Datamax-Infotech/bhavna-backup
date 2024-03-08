
<?php 

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 4.0.0

require_once('../fedex/library/fedex-common.php5');
//require_once('inc/database.php');

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

ini_set("display_errors", "1");
error_reporting(E_ALL);


//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../fedex/wsdl/TrackService_v4.wsdl";


//$orders_tracking_query = db_query(("SELECT * FROM orders_active_export WHERE setignore != 1 AND LENGTH(tracking_number) < 16 AND LENGTH(tracking_number) > 3 AND fedex_status = '' ORDER BY id ASC limit 100"), db());
//$orders_tracking_query = db_query(("SELECT * FROM orders_active_export WHERE setignore != 1 and LENGTH(tracking_number) > 3 AND (fedex_status <> 'DL' and fedex_status <> 'DE' and fedex_status <> 'CA' and fedex_status <> 'SE') ORDER BY id DESC"), db());
$orders_tracking_query = db_query(("SELECT * FROM orders_active_export WHERE setignore != 1 and LENGTH(tracking_number) > 3 AND (fedex_status <> 'DL') ORDER BY id DESC"), db());

while ($orders_tracking = array_shift($orders_tracking_query)) {

	ini_set("soap.wsdl_cache_enabled", "0");

	$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

	$request['WebAuthenticationDetail'] = array('UserCredential' =>
														  array('Key' => getProperty('key'), 'Password' => getProperty('password')));
	$request['ClientDetail'] = array('AccountNumber' => getProperty('shipaccount'), 'MeterNumber' => getProperty('meter'));
	$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request v4 using PHP ***');
	$request['Version'] = array('ServiceId' => 'trck', 'Major' => '4', 'Intermediate' => '1', 'Minor' => '0');
	$request['PackageIdentifier'] = array('Value' => $orders_tracking["tracking_number"], // Replace 'XXX' with a valid tracking identifier
										  'Type' => 'TRACKING_NUMBER_OR_DOORTAG');

	try 
	{
		if(setEndpoint('changeEndpoint'))
		{
			$newLocation = $client->__setLocation(setEndpoint('endpoint'));
		}
		
		$response = $client ->track($request);

		//if ($response -> HighestSeverity != 'FAILURE' || $response -> HighestSeverity != 'ERROR')
		
		if ($response -> HighestSeverity == 'SUCCESS')
		{

			//echo '<table border="1">';
			echo 'order Id: ';
			echo $orders_tracking["orders_id"] . " Tracking status: ";
		    echo getStatus($response->TrackDetails) . " Tracking details: ";
		    echo getDetails($response->TrackDetails);
			echo "<br>";
			
			$ins_sql = "update orders_active_export SET fedex_status = '" . getStatus($response->TrackDetails) . "', fedex_description = '" . getDetails($response->TrackDetails) . "' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );

		}
		else
		{
			echo "Error" . $orders_tracking["tracking_number"] . "<BR>";
		} 
		
		//writeToLog($client);    // Write to log file   

	} catch (SoapFault $exception) {
		//printFault($exception, $client);
		echo "BAD Error" . $orders_tracking["tracking_number"] . "<BR>";
	}


}

$datewtime = date("F j, Y, g:i a"); 


$ddw_sql = "UPDATE ucbdb_last_ups_check SET when_process = '$datewtime'";
$ddw_sql_result = db_query($ddw_sql,db() );

?>