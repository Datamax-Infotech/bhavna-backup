<!DOCTYPE html>
<html>

<head>
	<title>UPS Status Code Table Update</title>
</head>

<body>
	<P>The following Tracking Numbers are being updated.
	<P>
		<?php
		require("mainfunctions/database.php");
		require("mainfunctions/general-functions.php");
		db();
		$userid_pass = "ucbups";
		$access_key = "4CF1A79FF61AF418";
		$upsURL = "https://wwwcie.ups.com/ups.app/xml/Track";
		$activity = "activity";

		class xml_container
		{
			function store($k, $v)
			{
				$this->{$k}[] = $v;
			}
		}

		class xml
		{
			var array $current_tag = array();
			var  $xml_parser;
			var float $Version = 1.0;
			var array $tagtracker = array();

			function startElement(string $parser, string $name, array $attrs): void
			{
				array_push($this->current_tag, $name);
				$curtag = implode("_", $this->current_tag);
				if (isset($this->tagtracker["$curtag"])) {
					$this->tagtracker["$curtag"]++;
				} else {
					$this->tagtracker["$curtag"] = 0;
				}
				if (count($attrs) > 0) {
					$j = $this->tagtracker["$curtag"];
					if (!$j) $j = 0;
					if (!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
						$GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
					}
					$GLOBALS[$this->identifier]["$curtag"][$j]->store("attributes", $attrs);
				}
			} // end function startElement

			function endElement(string $parser, string $name): bool
			{
				$TD = "";
				$curtag = implode("_", $this->current_tag); 	// piece together tag
				if (!$this->tagdata["$curtag"]) {
					$popped = array_pop($this->current_tag); // or else we screw up where we are
				} else {
					$TD = $this->tagdata["$curtag"];
					unset($this->tagdata["$curtag"]);
				}
				$popped = array_pop($this->current_tag);
				if (sizeof($this->current_tag) == 0) return; 	// if we aren't in a tag
				$curtag = implode("_", $this->current_tag); 	// piece together tag
				$j = $this->tagtracker["$curtag"];
				if (!$j) $j = 0;
				if (!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
					$GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
				}
				$GLOBALS[$this->identifier]["$curtag"][$j]->store($name, $TD); #$this->tagdata["$curtag"]);
				unset($TD);
				return TRUE;
			}

			function characterData(string $parser, string $cdata): void
			{
				$curtag = implode("_", $this->current_tag); // piece together tag		
				$this->tagdata["$curtag"] .= $cdata;
			}

			function xml(string $data, string $identifier = 'xml'): void
			{
				$this->identifier = $identifier;
				$this->xml_parser = xml_parser_create();
				xml_set_object($this->xml_parser, $this);
				xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, 0);
				xml_set_element_handler($this->xml_parser, [$this, "startElement"], [$this, "endElement"]);
				xml_set_character_data_handler($this->xml_parser, [$this, "characterData"]);
				if (!xml_parse($this->xml_parser, $data, TRUE)) {
					sprintf(
						"XML error: %s at line %d",
						xml_error_string(xml_get_error_code($this->xml_parser)),
						xml_get_current_line_number($this->xml_parser)
					);
				}
				xml_parser_free($this->xml_parser);
			}  // end constructor: function xml()
		}
		$orders_tracking_query = db_query(("SELECT * FROM orders_active_export WHERE setignore != 1 AND LENGTH(tracking_number) > 16  AND orders_id > 226000"));
		//$orders_tracking_query = db_query("SELECT * FROM orders_active_export WHERE orders_id > 287185");

		while ($orders_tracking = array_shift($orders_tracking_query)) {
			/* XML Communication with UPS */
			$y = "<?php xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>" . $access_key . "</AccessLicenseNumber><UserId>" . $userid_pass . "</UserId><Password>" . $userid_pass . "</Password></AccessRequest><?php xml version=\"1.0\"?><TrackRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>Example 1</CustomerContext><XpciVersion>1.0001</XpciVersion></TransactionReference><RequestAction>Track</RequestAction><RequestOption>" . $activity . "</RequestOption></Request><TrackingNumber>" . $orders_tracking["tracking_number"] . "</TrackingNumber></TrackRequest>";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $upsURL);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $y);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$upsResponse = curl_exec($ch);
			curl_close($ch);

			$obj = new xml($upsResponse, "xml");

			$what_ups_says = trim($xml["TrackResponse_Response"][0]->ResponseStatusCode[0]);
			$stat = "";
			if ($what_ups_says == "1") {
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
				switch ($the_code) {
					case 'I':
						$stat = "In transit";
						$stat_code = 2;
						break;
					case 'D':
						$stat = "Delivered";
						$stat_code = 1;
						break;
					case 'X':
						$stat = "Exception";
						$stat_code = 2;
						break;
					case 'P':
						$stat = "Pickup";
						$stat_code = 2;
						break;
					case 'M':
						$stat = "Manifest Pickup";
						$stat_code = 3;
						break;
					case 'Z':
						$stat = "No tracking information available";
						$stat_code = 9;
						break;
				}
			} else {
				$ups_error_note = $xml["TrackResponse_Response_Error"][0]->ErrorDescription[0];
				echo "<center><b><font color='#FF0000'>" . $ups_error_note . "</font></b></center>";
			}
			//echo $stat;
			$ins_sql = "";
			if ($stat == 'Delivered') {
				$ins_sql = "update orders_active_export SET setignore = 1, status = 'Delivered' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			if ($stat == 'In transit') {
				$ins_sql = "update orders_active_export SET status = 'In transit' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			if ($stat == 'Exception') {
				$ins_sql = "update orders_active_export SET status = 'Exception' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			if ($stat == 'Pickup') {
				$ins_sql = "update orders_active_export SET status = 'Pickup' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			if ($stat == 'Manifest Pickup') {
				$ins_sql = "update orders_active_export SET status = 'Manifest Pickup' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			if ($stat == 'No tracking information available') {
				$ins_sql = "update orders_active_export SET status = 'No tracking information available' where id = " . $orders_tracking["id"];
				db_query($ins_sql);
			}
			echo "Order ID:" . $orders_tracking["orders_id"] . $ins_sql . "<br>";


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
		$ddw_sql_result = db_query($ddw_sql);

		echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
		if (!headers_sent()) {    //If headers not sent yet... then do php redirect
			header('Location: index.php');
			exit;
		} else {
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"index.php\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
			echo "</noscript>";
			exit;
		} //==== End -- Redirect

		?>
</body>

</html>