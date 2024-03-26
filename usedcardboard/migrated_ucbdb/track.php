<?php
////////////////////////////////
////////////////////////////////
////////////////////////////////
/*
A working example of this script is at http://tracking.atticghost.com/
Use this script in any way you wish. End user is responsible for following the strict licensing
agreements with UPS.
The script is ready to run, just enter your username/password & license key. That's it!
Ronald D. Lawson
webmaster@atticghost.com
[[[[ UPDATED 5-5-2005 ]]]]

Added a line in the cURL functions...

curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

If your script has stopped working and the only thing you know that has changed is the version of PHP, 
adding the line above will solve it.
*/
?>
<!-- begin main content -->
<BR><BR>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<div align="center">
		<center>
			<table border="0" width="266">
				<tr>
					<td width="266">
						<font face="Arial" size="2">David - Enter Tracking Number</font>
						<br>
						<input type="hidden" name="action" value="track">
						<input class="track1" type="text" name="tracknum" value="<?php echo $_POST['tracknum'] ?>" maxlength="100" size="20"><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input class="track2" type="submit" name="button" value="Track!">

					</td>
				</tr>
			</table>
		</center>
	</div>
</form>

<?php
//////////// begin tracking script  ////////////
if ($_POST['action'] == "track") {
	//$_POST['tracknum'] = substr(eregi_replace("[^A-z0-9. -]", "", $_POST['tracknum']), 0, 100); /// just a simple filter and to limit any number to 100 characters for safety sake
	$tracknum = isset($_POST['tracknum']) ? $_POST['tracknum'] : '';
	$_POST['tracknum'] = substr(preg_replace("/[^A-Za-z0-9. -]/", "", $tracknum), 0, 100);
	$userid_pass = "ucbups";  /// The username and password from UPS (username and password are the same)
	$access_key = "1C09C459AAA8A1FE";  //// license key from UPS
	$upsURL = "https://wwwcie.ups.com/ups.app/xml/Track"; /// This will be provided to you by UPS
	$activity = "activity"; /// UPS activity code

	///// The below variable is the query string to be posted. /////
	$y = "<?php xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>" . $access_key . "</AccessLicenseNumber><UserId>" . $userid_pass . "</UserId><Password>" . $userid_pass . "</Password></AccessRequest><?php xml version=\"1.0\"?><TrackRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>Example 1</CustomerContext><XpciVersion>1.0001</XpciVersion></TransactionReference><RequestAction>Track</RequestAction><RequestOption>" . $activity . "</RequestOption></Request><TrackingNumber>" . $_POST['tracknum'] . "</TrackingNumber></TrackRequest>";
	$ch = curl_init(); /// initialize a cURL session
	curl_setopt($ch, CURLOPT_URL, $upsURL); /// set the post-to url (do not include the ?query+string here!)
	curl_setopt($ch, CURLOPT_HEADER, 0); /// Header control
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); /// Use this to prevent PHP from verifying the host (later versions of PHP including 5)
	/// If the script you were using with cURL has stopped working. Likely adding the line above will solve it.
	curl_setopt($ch, CURLOPT_POST, 1);  /// tell it to make a POST, not a GET
	curl_setopt($ch, CURLOPT_POSTFIELDS, $y);  /// put the query string here starting with "?" 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /// This allows the output to be set into a variable $xyz
	$upsResponse = curl_exec($ch); /// execute the curl session and return the output to a variable $xyz
	curl_close($ch); /// close the curl session
	///////////////////  end the cURL Engine  /////////////////


	//////////// begin xml parser Class function ////////////
	/////// class function taken from http://www.hansanderson.com/php/xml/class.xml.php.txt

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



		/* when expat hits a closing tag, it fires up this function */

		function endElement(string $parser, string $name): bool
		{

			$curtag = implode("_", $this->current_tag); 	// piece together tag
			// before we pop it off,
			// so we can get the correct
			// cdata

			if (!$this->tagdata["$curtag"]) {
				$popped = array_pop($this->current_tag); // or else we screw up where we are
			} else {
				$TD = $this->tagdata["$curtag"];
				unset($this->tagdata["$curtag"]);
			}

			$popped = array_pop($this->current_tag);
			// we want the tag name for
			// the tag above this, it 
			// allows us to group the
			// tags together in a more
			// intuitive way.

			if (sizeof($this->current_tag) == 0) return; 	// if we aren't in a tag

			$curtag = implode("_", $this->current_tag); 	// piece together tag
			// this time for the arrays

			$j = $this->tagtracker["$curtag"];
			if (!$j) $j = 0;

			if (!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
				$GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
			}

			$GLOBALS[$this->identifier]["$curtag"][$j]->store($name, $TD); #$this->tagdata["$curtag"]);
			unset($TD);
			return TRUE;
		}



		/* when expat finds some internal tag character data,
 	   it fires up this function */

		function characterData(string $parser, string $cdata): void
		{
			$curtag = implode("_", $this->current_tag); // piece together tag		
			$this->tagdata["$curtag"] .= $cdata;
		}


		/* this is the constructor: automatically called when the class is initialized */

		function xml(string $data, string $identifier = 'xml'): void
		{

			$this->identifier = $identifier;

			// create parser object
			$this->xml_parser = xml_parser_create();

			// set up some options and handlers
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

			// we are done with the parser, so let's free it
			xml_parser_free($this->xml_parser);
		}  // end constructor: function xml()


	} // thus, we end our class xml
	///////////////////////////////////////////////////
	/////////// end XML Class  //////////////////////// 

	$obj = new xml($upsResponse, "xml"); /// create the object
	$nine = trim($xml["TrackResponse_Response"][0]->ResponseStatusCode[0]);
	////////////////////////
	$stat = "";
	if ($nine == "1") {
		$seven = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->AddressLine1[0] . "\n";
		$six = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->AddressLine2[0] . "\n";
		$five = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->City[0] . "\n";
		$four = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->StateProvinceCode[0] . "\n";
		$three = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->PostalCode[0] . "\n";
		$two = $xml["TrackResponse_Shipment_ShipTo_Address"][0]->CountryCode[0] . "\n";
		$twelve = $xml["TrackResponse_Shipment_Package_PackageWeight_UnitOfMeasurement"][0]->Code[0] . "\n";
		$eleven = $xml["TrackResponse_Shipment_Package_PackageWeight"][0]->Weight[0] . "\n";
		$thirteen = $xml["TrackResponse_Shipment_Service"][0]->Description[0] . "\n";
		///current location
		$fourteen = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation"][0]->Description[0] . "\n";
		$eighteen = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][0]->City[0] . "\n";
		$nineteen = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][0]->CountryCode[0] . "\n";
		$twenty = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][0]->StateProvinceCode[0] . "\n";
		$fifteen = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation"][0]->SignedForByName[0] . "\n";
		// end location
		$sixteen = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Description[0] . "\n";
		$seventeen = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Code[0] . "\n";
		$twentyfour = $xml["TrackResponse_Shipment_Package_Activity"][0]->Date[0] . "\n";
		$twentyfive = $xml["TrackResponse_Shipment_Package_Activity"][0]->Time[0] . "\n";
		$yearx = substr("$twentyfour", 0, 4);
		$monthx = substr("$twentyfour", 4, 2);
		$dayx = substr("$twentyfour", 6, 2);
		$hhx = substr("$twentyfive", 0, 2);
		$mmx = substr("$twentyfive", 2, 2);
		$ssx = substr("$twentyfive", 4, 2);
		$seventeen = trim($seventeen);
		switch ($seventeen) {
			case 'I':
				$stat = "In transit";
				break;
			case 'D':
				$stat = "Delivered";
				break;
			case 'X':
				$stat = "Exception";
				break;
			case 'P':
				$stat = "Pickup";
				break;
			case 'M':
				$stat = "Manifest Pickup";
				break;
		}
?>
		<font color="#000000" size="1" face="Verdana">Exporting out raw crap to show David<br>
			<br>
			Tracking Number: <?php echo $_POST['tracknum'] ?>
		</font><br>

		</font>
		<font color="#000000" size="1" face="Verdana"> <?php echo $stat ?> </font>
		<font color="#FFFFFF" size="2" face="Verdana"> <br>

		</font>
		<font color="#000000" size="1" face="Verdana"><?php if ($seventeen == "D") {
															echo ("Left at: $fourteen");
														} ?></font></b><br>

		<font size="1" face="Verdana">Shipping Method:</b></font>
		<font face="Verdana" size="1" color="#000000"><?php echo $thirteen ?></font><br>

		<font size="1" face="Verdana">Weight:</b></font>
		<font face="Verdana" size="1" color="#000000"><?php echo "$eleven $twelve" ?></font><br>

		<font face="Verdana" size="2" color="#FFFFFF"><b><i>Destined for:</i></font></b>
		<font color="#000000" size="1" face="Verdana"><b><?php echo "$monthx/$dayx/$yearx" ?></font> - <font color="#000000" size="1" face="Verdana"><?php echo "$hhx:$mmx:$ssx" ?></b></font>
		</font><br>

		<b>
			<font face="Verdana" size="1">
				<?php if ($stat == "I") {
					$sixteen = trim($sixteen);
					echo ("$sixteen");
				} else {
					$sixteen = trim($sixteen);
					echo ("$sixteen");
				} ?>:
			</font>
		</b>
		<br>
		<font face="Verdana" color="#000000" size="1">
			<?php if ($eighteen) {
				echo ("$eighteen, ");
			}
			if ($twenty) {
				echo ("$twenty ");
			}
			if ($nineteen) {
				echo ("$nineteen");
			} ?>
		</font>
		<br>
		<font face="Verdana" size="2"><b><?php if ($seventeen == "D") {
												echo ("Signed for by: $fifteen");
											} ?></b></font>

<?php
	} else {
		$eight = $xml["TrackResponse_Response_Error"][0]->ErrorDescription[0];
		echo "<center><b><font color='#FF0000'>" . $eight . "</font></b></center>";
	}  /// end if nine is 1 or 0
	/////////// end xml parser  /////
}    ///// end if action is track
?>
<BR>
<?php
if ($nine == "1") {
	$bgcolor = "";
	echo ("<center><b>Package History for use in UCB Order Page</b></center>
<font face=\"Verdana\" size=\"1\">
<table border='0' align=\"center\"><tr><td nowrap width='20%' bgcolor='#C0C0C0'><B><font face=\"Verdana\" color=\"#000000\" size=\"1\">Date</font></b></td><td nowrap width='20%' bgcolor='#C0C0C0'><B><font face=\"Verdana\" color=\"#000000\" size=\"1\">Time</font></b></td><td nowrap width='20%' bgcolor='#C0C0C0'><B><font face=\"Verdana\" color=\"#000000\" size=\"1\">Location</font></b></td><td width='40%' width='20%' bgcolor='#C0C0C0'><B><font face=\"Verdana\" color=\"#000000\" size=\"1\">Activity</font></b></td></tr>");
	for ($i = 0; $i < count($xml["TrackResponse_Shipment_Package_Activity"]); $i++) {
		$twentyone = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][$i]->Description[0] . "\n";
		$twentytwo = $xml["TrackResponse_Shipment_Package_Activity"][$i]->Date[0] . "\n";
		$twentythree = $xml["TrackResponse_Shipment_Package_Activity"][$i]->Time[0] . "\n";
		$twentyfour = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][$i]->City[0] . "\n";
		$twentyfive = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][$i]->StateProvinceCode[0] . "\n";
		$twentysix = $xml["TrackResponse_Shipment_Package_Activity_ActivityLocation_Address"][$i]->CountryCode[0] . "\n";


		$year = substr("$twentytwo", 0, 4);
		$month = substr("$twentytwo", 4, 2);
		$day = substr("$twentytwo", 6, 2);

		$hh = substr("$twentythree", 0, 2);
		$mm = substr("$twentythree", 2, 2);
		$ss = substr("$twentythree", 4, 2);

		$xday = $day;
		echo ("<tr>");
		if ($xday != $day) {

			echo ("<td nowrap width='20%' bgcolor='" . $bgcolor . "'><B><font face=\"Verdana\" color=\"#000080\" size=\"1\">" . $month . "/" . $day . "/" . $year . "</font></b></td>");
		} else {
			echo ("<td nowrap width='20%' bgcolor='" . $bgcolor . "'>&nbsp;</td>");
		}

		$xmonth = $month;
		$xday = $day;
		//$xyear = $xyear;

		echo ("<td nowrap width='20%' bgcolor='" . $bgcolor . "'><font face=\"Verdana\" color=\"#000000\" size=\"1\"><i>" . $hh . ":" . $mm . ":" . $ss . "</i></font></td><td nowrap width='20%' bgcolor='" . $bgcolor . "'><font face=\"Verdana\" color=\"#000000\" size=\"1\">" . $twentyfour . " " . $twentyfive . " " . $twentysix . "</font></td><td width='40%' bgcolor='" . $bgcolor . "'><font face=\"Verdana\" color=\"#000080\" size=\"1\">" . $twentyone . "</font></td></tr>");
	}

	echo ("
</table>
</font>
<BR>");

	echo ("
");
}  /// end if 17 is X
?>
<BR>
<!-- end main content -->