<!DOCTYPE html>

<html>
<head>
	<title>UPS Status Code Table Update</title>
</head>

<body>
<P>The following Tracking Numbers are being updated.
<P>
<?php  
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");



/* Begin the Trackung Number Stuff */


$userid_pass = "ucbups"; 
$access_key = "1C09C459AAA8A1FE";  
$upsURL = "https://wwwcie.ups.com/ups.app/xml/Track"; 
$activity = "activity"; 

class xml_container {
 	function store($k,$v) {
 		$this->{$k}[] = $v;
 	}
}
 
class xml { 
 	var $current_tag=array();
 	var $xml_parser;
 	var $Version = 1.0;
 	var $tagtracker = array();
	
 	function startElement($parser, $name, $attrs) {
 		array_push($this->current_tag, $name);
 		$curtag = implode("_",$this->current_tag);
 		if(isset($this->tagtracker["$curtag"])) {
 			$this->tagtracker["$curtag"]++;
 		} else {
 			$this->tagtracker["$curtag"]=0;
 		}
 		if(count($attrs)>0) {
 			$j = $this->tagtracker["$curtag"];
 			if(!$j) $j = 0;
 			if(!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
 				$GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
 			}
 			$GLOBALS[$this->identifier]["$curtag"][$j]->store("attributes",$attrs);
                 }
 	} // end function startElement
	
 	function endElement($parser, $name) {
 		$curtag = implode("_",$this->current_tag); 	// piece together tag
 		if(!$this->tagdata["$curtag"]) {
 			$popped = array_pop($this->current_tag); // or else we screw up where we are
 			return; 	// if we have no data for the tag
 		} else {
 			$TD = $this->tagdata["$curtag"];
 			unset($this->tagdata["$curtag"]);
 		}
 		$popped = array_pop($this->current_tag);
 		if(sizeof($this->current_tag) == 0) return; 	// if we aren't in a tag
 		$curtag = implode("_",$this->current_tag); 	// piece together tag
 		$j = $this->tagtracker["$curtag"];
 		if(!$j) $j = 0;
 		if(!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
 			$GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
 		}
 		$GLOBALS[$this->identifier]["$curtag"][$j]->store($name,$TD); #$this->tagdata["$curtag"]);
 		unset($TD);
 		return TRUE;
	}
 	
	function characterData($parser, $cdata) {
 		$curtag = implode("_",$this->current_tag); // piece together tag		
 		$this->tagdata["$curtag"] .= $cdata;
	}
 	
	function xml($data,$identifier='xml') {  
 		$this->identifier = $identifier;
 		$this->xml_parser = xml_parser_create();
 		xml_set_object($this->xml_parser,$this);
 		xml_parser_set_option($this->xml_parser,XML_OPTION_CASE_FOLDING,0);
 		xml_set_element_handler($this->xml_parser, "startElement", "endElement");
 		xml_set_character_data_handler($this->xml_parser, "characterData");
 		if (!xml_parse($this->xml_parser, $data, TRUE)) {
 			sprintf("XML error: %s at line %d",
 			xml_error_string(xml_get_error_code($this->xml_parser)),
 			xml_get_current_line_number($this->xml_parser));
 		}
 		xml_parser_free($this->xml_parser);
 	}  // end constructor: function xml()
}
$orders_tracking_query = db_query(("SELECT * FROM orders_active_export WHERE setignore != 1 AND LENGTH(tracking_number) > 16 LIMIT 0,1"), db());
//$orders_tracking_query = db_query("SELECT * FROM orders_active_export WHERE orders_id > 287185");

while ($orders_tracking = array_shift($orders_tracking_query)) {
/* XML Communication with UPS */		
$y = "<?php xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>".$access_key."</AccessLicenseNumber><UserId>".$userid_pass."</UserId><Password>".$userid_pass."</Password></AccessRequest><?php xml version=\"1.0\"?><TrackRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>Example 1</CustomerContext><XpciVersion>1.0001</XpciVersion></TransactionReference><RequestAction>Track</RequestAction><RequestOption>".$activity."</RequestOption></Request><TrackingNumber>".$orders_tracking["tracking_number"]."</TrackingNumber></TrackRequest>";

$ch = curl_init(); 
curl_setopt ($ch, CURLOPT_URL,$upsURL); 
curl_setopt ($ch, CURLOPT_HEADER, 0); 
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $y);  
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
$upsResponse = curl_exec ($ch); 
curl_close ($ch); 

$obj = new xml($upsResponse,"xml"); 

$what_ups_says = trim($xml["TrackResponse_Response"][0]->ResponseStatusCode[0]);
if($what_ups_says == "1")
	{
		$ups_description = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Description[0] . "\n";
		$the_code = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Code[0] . "\n";
		$the_delivery_date = $xml["TrackResponse_Shipment"][0]->ScheduledDeliveryDate[0] . "\n";
		$the_date = $xml["TrackResponse_Shipment_Package_Activity"][0]->Date[0] . "\n";
		$yearx = substr("$the_delivery_date", 0, 4);
		$monthx = substr("$the_delivery_date", 4, 2);
		$dayx = substr("$the_delivery_date", 6, 2); 
		$yeary = substr("$the_date", 0, 4);
		$monthy = substr("$the_date", 4, 2);
		$dayy = substr("$the_date", 6, 2); 
		$the_code = trim($the_code);
		switch($the_code)
			{
				case 'I':
				$stat = "In transit";
				$stat_code = 2;				
				BREAK;
				case 'D':
				$stat = "Delivered";
				$stat_code = 1;
				BREAK;
				case 'X':
				$stat = "Exception";
				$stat_code = 2;				
				BREAK;
				case 'P':
				$stat = "Pickup";
				$stat_code = 2;				
				BREAK;
				case 'M':
				$stat = "Manifest Pickup";
				$stat_code = 3;
				BREAK;
				case 'Z':
				$stat = "No tracking information available";
				$stat_code = 9;
				BREAK;				
		}
	}
	else
	{
		$ups_error_note = $xml["TrackResponse_Response_Error"][0]->ErrorDescription[0];
		echo "<center><b><font color='#FF0000'>".$ups_error_note."</font></b></center>";
	}  
//echo $stat;
if ($stat == 'Delivered') {
			$ins_sql = "update orders_active_export SET setignore = 1, status = 'Delivered' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
if ($stat == 'In transit') {
			$ins_sql = "update orders_active_export SET status = 'In transit' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
if ($stat == 'Exception') {
			$ins_sql = "update orders_active_export SET status = 'Exception' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
if ($stat == 'Pickup') {
			$ins_sql = "update orders_active_export SET status = 'Pickup' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
if ($stat == 'Manifest Pickup') {
			$ins_sql = "update orders_active_export SET status = 'Manifest Pickup' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
if ($stat == 'No tracking information available') {
			$ins_sql = "update orders_active_export SET status = 'No tracking information available' where id = " . $orders_tracking["id"] ;
			db_query($ins_sql,db() );
}
echo "Order ID:" . $orders_tracking["orders_id"] . "<br>";


unset($stat);
unset($obj);
unset($xml);

}
?>	
<P>Processing completed.

<?php 


/* Last Update */

$datewtime = date("F j, Y, g:i a"); 


$ddw_sql = "UPDATE ucbdb_last_ups_check SET when_process = '$datewtime'";
$ddw_sql_result = db_query($ddw_sql,db() );


echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";


?>
</body>
</html>
