<?php 
require ("../ucbloop/mainfunctions/database.php");
require ("../ucbloop/mainfunctions/general-functions.php");
db_b2b() ;
function showmap_schnitzer(): void{ ?>
<script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>
<div id="map" style="width: 1800px; height: 1000px;"></div>

  <script type="text/javascript">
    var locations = [
	
  <?php
	
	$qry = "SELECT * FROM `inventory` WHERE `gaylord` LIKE '1' AND `availability` != 0 AND `active` LIKE 'A' ORDER BY availability ASC";
	$result = db_query($qry );
	$i = 1;
	while ($row = array_shift($result)) {
	echo "['" . $row["description"] . "<br>";
	
	$vq = "Select * from vendors WHERE id = " . $row["vendor"];
	$vres = db_query($vq);
	while ($vendorrow = array_shift($vres)) {
	//echo $vendorrow["Name"] . "<br>";
	}
	?>
	<?php if ($row["availability"] == "3" ) {echo "Available Now & Urgent"; $c = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";} ?>
			<?php if ($row["availability"] == "2" ) { echo "Available Now"; $c = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";} ?>
			<?php if ($row["availability"] == "1" ) { echo "Available Soon";  $c = "http://maps.google.com/mapfiles/ms/icons/ltblue-dot.png";} ?>
			<?php if ($row["availability"] == "-1" ) { echo "Presell";  $c = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";} ?>
			<?php if ($row["availability"] == "2.5" ) { echo "Available and >1 TL";  $c = "http://maps.google.com/mapfiles/ms/icons/pink-dot.png";} ?>
			<?php if ($row["availability"] == "-2" ) { echo "Active by Unavailable";  $c = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";} ?>
			<?php if ($row["availability"] == "-3" ) { echo "Potential";  $c = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";} ?>
			<?php if ($row["availability"] == "-3.5" ) { echo "Check Loops";  $c = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";} ?>
	<?php		
	//echo "', ";
	//$zipStr= "Select * from ZipCodes WHERE zip = " . preg_replace('/\D/', '', $row["location"]);
	//echo "<br>Location Zip: " . $row["location_zip"] . "<br>";
	echo "<br>Location Zip: " . $row["location_zip"] . "<br>";
	
	echo "', ";
	$tmp_zipval = "";
	
	$tmppos_1 = strpos($row["location_zip"], " ");
	if ($tmppos_1 != false)
	{ 	
		//$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
		$tmp_zipval = str_replace(" ", "", $row["location_zip"]);
		$zipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
	}else {
		$zipStr= "Select * from ZipCodes WHERE zip = '" . intval($row["location_zip"]) . "'";
	}
	
		//if (preg_replace('/\D/', '', $row["location"]) != "")		
		//{
				
			$dt_view_res3 = db_query($zipStr);
			$locLat = ""; $locLong  = "";
			while ($ziploc = array_shift($dt_view_res3)) {
				$locLat = $ziploc["latitude"];
				
				$locLong = $ziploc["longitude"] + ($i * .0001);
				
				}
		//}
		echo " " . $locLat . ", " . $locLong  . ", " . $i . ", '";
		 if ($row["availability"] == "3" ) {echo "http://maps.google.com/mapfiles/ms/icons/red-dot.png";} ?>
			<?php if ($row["availability"] == "2" ) { echo "http://maps.google.com/mapfiles/ms/icons/green-dot.png";} ?>
			<?php if ($row["availability"] == "1" ) { echo "http://maps.google.com/mapfiles/ms/icons/ltblue-dot.png";} ?>
			<?php if ($row["availability"] == "-1" ) { echo "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";} ?>
			<?php if ($row["availability"] == "2.5" ) { echo "http://maps.google.com/mapfiles/ms/icons/pink-dot.png";} ?>
			<?php if ($row["availability"] == "-2" ) { echo "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";} ?>
			<?php if ($row["availability"] == "-3" ) { echo "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";} ?>
			<?php if ($row["availability"] == "-3.5" ) { echo "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";}
		echo  "'";
		echo "],";
		$i++;
	}
	?>	
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 5,
      center: new google.maps.LatLng(39.695798, -91.40084),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		icon: new google.maps.MarkerImage(locations[i][4]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>

<?php }
showmap_schnitzer();
?>
