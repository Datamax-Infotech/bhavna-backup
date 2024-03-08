<?php 
require ("inc/header_session.php");

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


// Start Processing Function


$today = date("m/d/Y"); 
$today_date = date("Y-m-d"); 
$today_crm = date("Ymd"); 
$warehouse_id = $_POST["warehouse_id"];
$id = $_POST["id"];
$rec_type = $_POST["rec_type"];
$user = $_COOKIE['userinitials'];
$employee = $user;
$bol_date = $today;
$trans_rec_id = $_POST["rec_id"];

$sql_remove = "DELETE FROM loop_bol_tracking WHERE trans_rec_id = " . $trans_rec_id;
$result_remove = db_query($sql_remove,db() );

$sql_remove = "DELETE FROM loop_bol_files WHERE trans_rec_id = " . $trans_rec_id;
$result_remove = db_query($sql_remove,db() );

$sql_remove = "DELETE FROM loop_inventory WHERE in_out = 1 AND trans_rec_id = " . $trans_rec_id;
$result_remove = db_query($sql_remove,db() );

$sql = "UPDATE loop_transaction_buyer SET bol_create = 0, bol_upload_file = '', bol_date = '', bol_signed_uploaded = '0', bol_sent = '0', bol_received = '0' WHERE id = '" . $trans_rec_id . "'";
$result = db_query($sql,db() );

for($i=0;$i<$count;$i++){
$sql_sort = "INSERT INTO loop_bol_tracking (box_id, qty, pallets, warehouse_id, location_warehouse_id, trans_rec_id, quantity1, pallet1, weight1, description1, quantity2, pallet2, weight2, description2, quantity3, pallet3, weight3, description3, bol_pickupdate, bol_STL1, bol_STL2, bol_STL3, bol_STL4, trailer_no, bol_pickup_time, bol_payment, bol_freight_vendor, bol_freight_biller, bol_instructions, bol_date, bol_employee) VALUES ( '" . $_POST["box_id"][$i] . "', '" . $_POST["qty"][$i] . "', '" . $_POST["pallets"][$i] . "', '" . $warehouse_id . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $trans_rec_id . "', '" . $_POST["quantity1"] . "', '" . $_POST["pallet1"] . "', '" . $_POST["weight1"] . "', '" . $_POST["description1"] . "', '" . $_POST["quantity2"] . "', '" . $_POST["pallet2"] . "', '" . $_POST["weight2"] . "', '" . $_POST["description2"] . "', '" . $_POST["quantity3"] . "', '" . $_POST["pallet3"] . "', '" . $_POST["weight3"] . "', '" . $_POST["description3"] . "', '" . $_POST["bol_pickupdate"] . "', '"  . $_POST["stl1"] . "', '"  . $_POST["stl2"] . "', '"  . $_POST["stl3"] . "', '"  . $_POST["stl4"] . "', '"  . $_POST["trailer_number"] . "', '" . $_POST["bol_pickup_time"] . "', '" . $_POST["bol_payment"] . "', '" . $_POST["bol_freight_vendor"] . "', '" . $_POST["bol_freight_biller"] . "', '" . $_POST["bol_instructions"] . "', '" . $bol_date . "', '" . $employee . "')";
$result_sort = db_query($sql_sort,db() );

$sql_sort = "INSERT INTO loop_inventory (add_date, box_id, warehouse_id, trans_rec_id, in_out, boxgood, employee) VALUES ('". $today_date . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $trans_rec_id. "', '1', '-". $_POST["qty"][$i] . "', '" . $employee . "')";
$result_sort = db_query($sql_sort,db() );

}

$filequery = "INSERT INTO loop_bol_files (file_name) VALUES ('0')";
$fileresult = db_query($filequery);


 	$sql_bols = "SELECT * FROM loop_bol_files ORDER BY id DESC";
	$result_bols = db_query($sql_bols ,db() );
	$bols_row = array_shift($result_bols );
	
	$bol_number = $bols_row["id"];


$sql = "UPDATE loop_transaction_buyer SET bol_create = 1 WHERE id = '" . $trans_rec_id . "'";
$result = db_query($sql,db() );


$message = "<strong>Note for Transaction # "; 
$message .=  $trans_rec_id;
$message .= "</strong>: ";
$message .=  $employee;
$message .= " entered a BOL on ";
$message .= $bol_date;
/*
if ($_POST["updatecrm"] == "yes") {
for($i=0;$i<$count;$i++){
$message .= "<br>Previous Good " . $_POST[boxbad_desc_old][$i] . " Boxes ";
$message .= $_POST["boxgood_old"][$i];
$message .= "<br>Previous Bad " . $_POST[boxbad_desc_old][$i] . " Boxes ";
$message .= $_POST["boxbad_old"][$i];
}
$message .= "<br>Previous Scrap Boxes ";
$message .= $_POST["scrap_old"];
$message .= "<br>Previous Notes ";
$message .= $_POST["notes_old"];
$message .= "<br>Previous Sort Date ";
$message .= $_POST["sort_date_old"];
$message .= "<br>Previous Employee ";
$message .= $_POST["employee_old"];
}

*/

//$sql_crm = "INSERT INTO loop_crm  ( warehouse_id, message_date, employee, comm_type, message) VALUES ( '" . $warehouse_id . "', '" . $today_crm . "', '" . $employee . "', '5', '" . $message . "')";


//$result_crm = db_query($sql_crm,db() );



// Ebd Processing functions


/*
$sql = "INSERT INTO loop_crm (warehouse_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $_POST[warehouse_id] . "','" . $_POST[comm_type] . "','" . $_POST[message] . "','" . $today . "','" . $_COOKIE['userinitials'] . "','" . $_FILES["file"]["name"] . "')";
//echo "<BR>SQL: $sql<BR>";
$result = db_query($sql,db() );
*/



$thepdf = "<html>
<head>
<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\">
<meta name=Generator content=\"Microsoft Word 12 (filtered)\">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Wingdings;
	panose-1:5 0 0 0 0 0 0 0 0 0;}
@font-face
	{font-family:\"Cambria Math\";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
h1
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:center;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	text-transform:uppercase;}
h2
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:right;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:normal;}
h3
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:normal;}
h4
	{margin-top:3.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	text-align:center;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:\"Arial\",\"sans-serif\";}
h5
	{margin-top:3.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:\"Arial\",\"sans-serif\";}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.SectionTitle, li.SectionTitle, div.SectionTitle
	{mso-style-name:\"Section Title\";
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	text-transform:uppercase;
	font-weight:bold;}
p.FinePrint, li.FinePrint, div.FinePrint
	{mso-style-name:\"Fine Print\";
	mso-style-link:\"Fine Print Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:6.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
span.FinePrintChar
	{mso-style-name:\"Fine Print Char\";
	mso-style-link:\"Fine Print\";
	font-family:\"Tahoma\",\"sans-serif\";}
p.Centered, li.Centered, div.Centered
	{mso-style-name:Centered;
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.Bold, li.Bold, div.Bold
	{mso-style-name:Bold;
	mso-style-link:\"Bold Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.CheckBox, li.CheckBox, div.CheckBox
	{mso-style-name:\"Check Box\";
	mso-style-link:\"Check Box Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:10.0pt;
	font-family:Wingdings;
	color:#333333;}
span.CheckBoxChar
	{mso-style-name:\"Check Box Char\";
	mso-style-link:\"Check Box\";
	font-family:Wingdings;
	color:#333333;}
p.LightGreylines, li.LightGreylines, div.LightGreylines
	{mso-style-name:\"Light Grey lines\";
	mso-style-link:\"Light Grey lines Char Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:6.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	color:#999999;}
span.LightGreylinesCharChar
	{mso-style-name:\"Light Grey lines Char Char\";
	mso-style-link:\"Light Grey lines\";
	font-family:\"Tahoma\",\"sans-serif\";
	color:#999999;}
span.BoldChar
	{mso-style-name:\"Bold Char\";
	mso-style-link:Bold;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.Terms, li.Terms, div.Terms
	{mso-style-name:Terms;
	margin-top:2.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.ShipperSignature, li.ShipperSignature, div.ShipperSignature
	{mso-style-name:\"Shipper Signature\";
	mso-style-link:\"Shipper Signature Char\";
	margin-top:2.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
span.ShipperSignatureChar
	{mso-style-name:\"Shipper Signature Char\";
	mso-style-link:\"Shipper Signature\";
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.BarCode, li.BarCode, div.BarCode
	{mso-style-name:\"Bar Code\";
	margin-top:4.0pt;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:center;
	font-size:12.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	color:gray;
	text-transform:uppercase;
	font-weight:bold;}
p.BoldCentered, li.BoldCentered, div.BoldCentered
	{mso-style-name:\"Bold Centered\";
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.Signatureheading, li.Signatureheading, div.Signatureheading
	{mso-style-name:\"Signature heading\";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:6.0pt;
	margin-left:0in;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
@page Section1
	{size:8.5in 11.0in;
	margin:27.35pt 27.35pt 36.7pt 45.35pt;}
div.Section1
	{page:Section1;}
-->
</style>

</head>

<body lang=EN-US>

<div class=Section1>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left
 width=732 style='width:549.0pt;border-collapse:collapse;border:none;
 margin-left:7.1pt;margin-right:7.1pt'>
 <tr style='height:23.05pt'>
  <td width=128 colspan=3 style='width:96.05pt;border:none;border-bottom:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h3>Date: ". $_POST["bol_pickupdate"] ."</h3>
  </td>
  <td width=434 colspan=12 style='width:325.85pt;border:none;border-bottom:
  solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h1>Bill of Lading � Short Form � Not Negotiable</h1>
  </td>
  <td width=169 colspan=3 style='width:127.1pt;border:none;border-bottom:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h2>Page 1 of 1</h2>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Ship From</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border:none;border-right:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Bill of Lading Number: " . $bol_number . "</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 valign=bottom style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal>";
	$total_boxes = 0;
	$total_weight = 0;

 	$sql_warehouse = "SELECT * FROM loop_warehouse WHERE id = " . $_POST["location_warehouse_id"][0];
	$result_warehouse = db_query($sql_warehouse ,db() );
	$warehouse_row = array_shift($result_warehouse );
	$thepdf .= $warehouse_row["warehouse_name"] . "<BR>" . $warehouse_row["warehouse_address1"] . ", " . $warehouse_row["warehouse_address2"] . "<BR>" . $warehouse_row["warehouse_city"]. ", " . $warehouse_row["warehouse_state"] . " " . $warehouse_row["warehouse_zip"] . "</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=BarCode>Bar Code Space</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Ship To</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border:none;border-right:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Carrier Name: </p>";

 	$sql_freight = "SELECT * FROM loop_freightvendor WHERE id = " . $_POST["bol_freight_vendor"];
	$result_freight = db_query($sql_freight ,db() );
	$freight_row = array_shift($result_freight );
	$thepdf .= $freight_row["company_name"] . "</td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 valign=top style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal>" . $_POST["stl1"] . "<BR>" . $_POST["stl2"] . "<BR>" . $_POST["stl3"] . "<BR>" . $_POST["stl4"] . "</p>
  </td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal>Trailer number:". $_POST["trailer_number"] . "</p>
  <p class=MsoNormal>Serial number(s):</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Third Party Freight Charges Bill to:</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border:none;border-right:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>SCAC:</p>
  </td>
 </tr>
 <tr style='height:24.45pt'>
  <td width=365 colspan=9 valign=top style='width:273.4pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:24.45pt'>";

 	$sql_freightbiller = "SELECT * FROM loop_freightvendor WHERE id = " . $_POST["bol_freight_biller"];
	$result_freightbiller = db_query($sql_freightbiller ,db() );
	$freightbiller_row = array_shift($result_freightbiller );
	$thepdf .= "<p class=MsoNormal>" . $freightbiller_row["company_name"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_address1"] . $freightbiller_row["company_address2"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_city"] . ", " . $freightbiller_row["company_state"] . " " . $freightbiller_row["company_zip"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_phone"] . "</p>
  </td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:24.45pt'>
  <p class=MsoNormal>Pro Number:</p>
  <p class=BarCode>Bar Code Space</p>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 rowspan=2 valign=top style='width:273.4pt;border:
  solid gray 1.0pt;border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;
  height:8.8pt'>
  <p class=Bold>Special Instructions: </p>
  <p class=MsoNormal>" . $_POST["bol_instructions"] . "</p>
  </td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:.7pt 4.3pt .7pt 4.3pt;height:8.8pt'>
  <p class=Bold>Freight Charge Terms <span class=FinePrintChar><span
  style='font-size:6.0pt'>(Freight charges are prepaid unless marked otherwise):</span></span></p>
  <p class=Terms>Prepaid <input type=checkbox>�Collect <input type=checkbox>�3rd Party <input type=checkbox></p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.9pt 4.3pt 2.15pt 4.3pt;height:.2in'>
  <p class=MsoNormal><input type=checkbox> Master bill of lading
  with attached underlying bills of lading.</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=732 colspan=18 style='width:549.0pt;border:solid gray 1.0pt;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Customer Order Information</p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=Bold>Customer Order No.</p>
  </td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered># of Packages</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Weight</p>
  </td>
  <td width=78 colspan=2 style='width:58.5pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Pallet/Slip<br>
  (circle one)</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=Bold>Additional Shipper Information</p>
  </td>
 </tr>";
 $total_boxes = 0;
 $total_weight = 0;
 for($i=0;$i<$count;$i++){
 	$sql_box = "SELECT * FROM loop_boxes WHERE id = " . $_POST["box_id"][$i];
	$result_box = db_query($sql_box,db() );
	$box_row = array_shift($result_box);
	
	if ($_POST["qty"][$i]>0)
	
	{
 $thepdf .= "<tr style='height:.2in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>";

	if ($box_row["isbox"] == 'Y') $thepdf .= $box_row["blength"]." ".$box_row["blength_frac"]." x ". $box_row["bwidth"]." ".$box_row["bwidth_frac"]." x ". $box_row["bdepth"]." ".$box_row["bdepth_frac"]." ";

  $thepdf .= $box_row["bdescription"]."</p></td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["qty"][$i],0)."</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format(($_POST["qty"][$i]*$box_row["bweight"]+40*$_POST["pallets"][$i]),0)."</p>
  </td>
  <td width=36 style='width:27.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=42 style='width:31.5pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>";
	}
 $total_boxes += $_POST["qty"][$i];
 $total_weight += ($_POST["qty"][$i]*$box_row["bweight"] + 40*$_POST["pallets"][$i]);
 }
 if ($_POST["quantity1"] . $_POST["pallet1"] . $_POST["weight1"] . $_POST["description1"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>".$_POST["description1"]."</p>
  </td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["quantity1"],0)."</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["weight1"],0)."</p>
  </td>
  <td width=36 style='width:27.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=42 style='width:31.5pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>";
	}
 if ($_POST["quantity2"] . $_POST["pallet2"] . $_POST["weight2"] . $_POST["description2"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>".$_POST["description2"]."</p>
  </td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["quantity2"],0)."</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["weight2"],0)."</p>
  </td>
  <td width=36 style='width:27.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=42 style='width:31.5pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>";
	}
 if ($_POST["quantity3"] . $_POST["pallet3"] . $_POST["weight3"] . $_POST["description3"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>".$_POST["description3"]."</p>
  </td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["quantity3"],0)."</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($_POST["weight3"],0)."</p>
  </td>
  <td width=36 style='width:27.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=42 style='width:31.5pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>";
	}

	
	$total_weight += $_POST["weight1"] + $_POST["weight2"] + $_POST["weight3"];
	
	$total_boxes += $_POST["quantity1"] + $_POST["quantity2"] + $_POST["quantity3"];



   $thepdf .= "<tr style='height:.2in'>
  <td width=281 colspan=7 style='width:210.4pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Grand Total</p>
  </td>
  <td width=84 colspan=2 style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($total_boxes,0)."</p>
  </td>
  <td width=48 colspan=2 style='width:.5in;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>".number_format($total_weight,0)."</p>
  </td>
  <td width=319 colspan=7 style='width:239.6pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;background:
  #F3F3F3;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=732 colspan=18 style='width:549.0pt;border:solid gray 1.0pt;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Carrier Information</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=91 colspan=2 style='width:68.5pt;border:solid gray 1.0pt;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=BoldCentered>Handling Unit</p>
  </td>
  <td width=91 colspan=2 style='width:68.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=BoldCentered>Package</p>
  </td>
  <td width=424 colspan=12 style='width:318.05pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
  <td width=125 colspan=2 style='width:93.9pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=BoldCentered>LTL Only</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=37 valign=top style='width:27.5pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>Qty</p>
  </td>
  <td width=55 valign=top style='width:41.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:2.15pt 4.3pt 2.15pt 4.3pt;
  height:8.8pt'>
  <p class=Centered>Type</p>
  </td>
  <td width=37 valign=top style='width:27.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>Qty</p>
  </td>
  <td width=55 valign=top style='width:41.0pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:2.15pt 4.3pt 2.15pt 4.3pt;
  height:8.8pt'>
  <p class=Centered>Type</p>
  </td>
  <td width=45 valign=top style='width:33.75pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>Weight</p>
  </td>
  <td width=45 valign=top style='width:33.75pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>HM (X)</p>
  </td>
  <td width=334 colspan=10 valign=top style='width:250.55pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Bold>Commodity Description </p>
  <p class=FinePrint>Commodities requiring special or additional care or
  attention in handling or stowing must be so marked and packaged as to ensure
  safe transportation with ordinary care. See Section 2(e) of NMFC item 360</p>
  </td>
  <td width=63 valign=top style='width:46.95pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>NMFC No.</p>
  </td>
  <td width=63 valign=top style='width:46.95pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=Centered>Class</p>
  </td>
 </tr>";
 $total_pallets = 0;
 for($i=0;$i<$count;$i++){
 	$sql_box = "SELECT * FROM loop_boxes WHERE id = " . $_POST["box_id"][$i];
	$result_box = db_query($sql_box,db() );
	$box_row = array_shift($result_box);
	
	if ($_POST["pallets"][$i]>0)
	
	{	
 $thepdf .= "<tr style='height:.2in'>
  <td width=37 style='width:27.5pt;border:solid gray 1.0pt;border-top:none;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>".$_POST["pallets"][$i]."</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Pallets</p>
  </td>
  <td width=37 style='width:27.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>".number_format(($_POST["qty"][$i]*$box_row["bweight"]+ 40*$_POST["pallets"][$i]),0)."</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=334 colspan=10 style='width:250.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>";

	if ($box_row["isbox"] == 'Y') $thepdf .= $box_row["blength"]." ".$box_row["blength_frac"]." x ". $box_row["bwidth"]." ".$box_row["bwidth_frac"]." x ". $box_row["bdepth"]." ".$box_row["bdepth_frac"]." ";

  $thepdf .= $box_row["bdescription"]."</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
 </tr>";
 $total_pallets += $_POST["pallets"][$i];
	}
 }
 
$total_pallets += $_POST["pallet1"] + $_POST["pallet2"] + $_POST["pallet3"];

 
 if ($_POST["quantity1"] . $_POST["pallet1"] . $_POST["weight1"] . $_POST["description1"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=37 style='width:27.5pt;border:solid gray 1.0pt;border-top:none;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>".$_POST["pallet1"]."</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Pallets</p>
  </td>
  <td width=37 style='width:27.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>".number_format($_POST["weight1"],0)."</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=334 colspan=10 style='width:250.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>". $_POST["description1"] . "</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
 </tr>";
 }
 if ($_POST["quantity2"] . $_POST["pallet2"] . $_POST["weight2"] . $_POST["description2"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=37 style='width:27.5pt;border:solid gray 1.0pt;border-top:none;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>".$_POST["pallet2"]."</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Pallets</p>
  </td>
  <td width=37 style='width:27.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>".number_format($_POST["weight2"],0)."</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=334 colspan=10 style='width:250.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>". $_POST["description2"] . "</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
 </tr>";
 }
 if ($_POST["quantity3"] . $_POST["pallet3"] . $_POST["weight3"] . $_POST["description3"] != "") {
 $thepdf .= "<tr style='height:.2in'>
  <td width=37 style='width:27.5pt;border:solid gray 1.0pt;border-top:none;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>".$_POST["pallet3"]."</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Pallets</p>
  </td>
  <td width=37 style='width:27.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>".number_format($_POST["weight3"],0)."</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=334 colspan=10 style='width:250.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>". $_POST["description3"] . "</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
 </tr>";
 }
 $thepdf .= "<tr style='height:.2in'>
  <td width=37 style='width:27.5pt;border:solid gray 1.0pt;border-top:none;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>" . ($total_pallets) . "</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;background:#E6E6E6;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=37 style='width:27.55pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=55 style='width:41.0pt;border-top:none;border-left:none;border-bottom:
  solid gray 1.0pt;border-right:solid gray 1.0pt;background:#E6E6E6;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>" . number_format($total_weight ,0) . "</p>
  </td>
  <td width=45 style='width:33.75pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=334 colspan=10 style='width:250.55pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
  <td width=63 style='width:46.95pt;border-top:none;border-left:none;
  border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>&nbsp;</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=395 colspan=10 valign=top style='width:295.9pt;border:none;
  border-bottom:solid gray 1.0pt;padding:.05in 4.3pt .05in 4.3pt;height:8.8pt'>
  <p class=FinePrint>Where the rate is dependent on value, shippers are
  required to state specifically in writing the agreed or declared value of the
  property as follows: �The agreed or declared value of the property is
  specifically stated by the shipper to be not exceeding<span
  class=LightGreylinesCharChar> _______________</span> per <span
  class=LightGreylinesCharChar>_______________</span>.</p>
  </td>
  <td width=337 colspan=8 style='width:253.1pt;border:none;border-bottom:solid gray 1.0pt;
  padding:.05in 4.3pt .05in 4.3pt;height:8.8pt'>
  <p class=Bold>COD Amount: $ <span class=LightGreylinesCharChar><span
  style='font-size:6.0pt'>_______________________________________________ </span></span></p>
  <p class=Terms>Fee terms: Collect <input type=checkbox> ����Prepaid
  <input type=checkbox> ����Customer check acceptable <input type=checkbox></p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=732 colspan=18 style='width:549.0pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=BoldCentered>Note: Liability limitation for loss or damage in this
  shipment may be applicable. See 49 USC � 14706(c)(1)(A) and (B).</p>
  </td>
 </tr>
 <tr style='height:.5in'>
  <td width=323 colspan=8 style='width:241.9pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.5in'>
  <p class=FinePrint>Received, subject to individually determined rates or
  contracts that have been agreed upon in writing between the carrier and
  shipper, if applicable, otherwise to the rates, classifications, and rules
  that have been established by the carrier and are available to the shipper,
  on request, and to all applicable state and federal regulations.</p>
  </td>
  <td width=409 colspan=10 style='width:307.1pt;border-top:none;border-left:
  none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:.5in'>
  <p class=MsoNormal>The carrier shall not make delivery of this shipment
  without payment of charges and all other lawful fees.</p>
  <p class=ShipperSignature>Shipper Signature �<span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>_________________________________________________________ </span></span></p>
  </td>
 </tr>
 <tr style='height:53.5pt'>
  <td width=228 colspan=5 valign=top style='width:170.8pt;border:solid gray 1.0pt;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:53.5pt'>
  <p class=Signatureheading>Shipper Signature/Date</p>
  <p class=Bold><span class=LightGreylinesCharChar><span style='font-size:6.0pt'>___________________________________________ </span></span></p>
  <p class=FinePrint>This is to certify that the above named materials are
  properly classified, packaged, marked, and labeled, and are in proper
  condition for transportation according to the applicable regulations of the
  DOT.</p>
  </td>
  <td width=95 colspan=3 valign=top style='width:71.1pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:53.5pt'>
  <p class=Bold>Trailer Loaded:</p>
  <p class=MsoNormal><input type=checkbox> By shipper</p>
  <p class=MsoNormal><input type=checkbox> By driver</p>
  </td>
  <td width=184 colspan=6 valign=top style='width:137.8pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:53.5pt'>
  <p class=Bold>Freight Counted:</p>
  <p class=MsoNormal><input type=checkbox> By shipper</p>
  <p class=MsoNormal><input type=checkbox> By driver/pallets said
  to contain</p>
  <p class=MsoNormal><input type=checkbox> By driver/pieces</p>
  </td>
  <td width=226 colspan=4 valign=top style='width:169.3pt;border-top:none;
  border-left:none;border-bottom:solid gray 1.0pt;border-right:solid gray 1.0pt;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:53.5pt'>
  <p class=Signatureheading>Carrier Signature/Pickup Date</p>
  <p class=Bold><span class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________ </span></span></p>
  <p class=FinePrint>Carrier acknowledges receipt of packages and required
  placards. Carrier certifies emergency response information was made available
  and/or carrier has the DOT emergency response guidebook or equivalent
  documentation in the vehicle. Property described above is received in good
  order, except as noted.</p>
  </td>
 </tr>
 <tr height=0>
  <td width=37 style='border:none'></td>
  <td width=55 style='border:none'></td>
  <td width=37 style='border:none'></td>
  <td width=55 style='border:none'></td>
  <td width=47 style='border:none'></td>
  <td width=44 style='border:none'></td>
  <td width=7 style='border:none'></td>
  <td width=42 style='border:none'></td>
  <td width=41 style='border:none'></td>
  <td width=30 style='border:none'></td>
  <td width=18 style='border:none'></td>
  <td width=36 style='border:none'></td>
  <td width=42 style='border:none'></td>
  <td width=15 style='border:none'></td>
  <td width=56 style='border:none'></td>
  <td width=44 style='border:none'></td>
  <td width=63 style='border:none'></td>
  <td width=63 style='border:none'></td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

</div>

</body>

</html>
";

include('class.ezpdf.php');
$pdf =new Cezpdf();
$pdf->selectFont('./fonts/Helvetica');
//$pdf->ezText($thepdf,10);
$pdf->ezText(html_entity_decode($thepdf),'');
$pdfcode = $pdf->output();



$data = ob_get_clean();
include("mpdf/mpdf.php");
$mpdf=new mPDF('en','Letter','10','arial', 15,15,16,16,9,9); 
$mpdf->useOnlyCoreFonts = false;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list
$mpdf->WriteHTML($thepdf, 0);
//$mpdf->Output('files/bol.pdf','F'); 


//header("Location: files/bol.pdf");
//exit();




/* 
$pdf->ezText("\n\n".$pdf->messages,10,array('justification'=>'left'));
  
if (isset($d) && $d){
  $pdfcode = $pdf->output(1);
  $end_time = getmicrotime();
  $pdfcode = str_replace("\n","\n<br>",htmlspecialchars($pdfcode));
  echo '<html>';
  echo trim($pdfcode);
  echo '</body>';
} else {
  $pdf->stream();
}

*/
echo "1";
$dir = 'bol';
//save the file
if (!file_exists($dir)){
mkdir ($dir,0777);
}
echo "2";
$fname = tempnam($dir.'/','PDF_').'.pdf';
$mpdf->Output($fname,'F'); 



$file_name = basename($fname);
//$fp = fopen($fname,'w');
//fwrite($fp,$pdfcode);
//fclose($fp);

echo $fname . "3<br>" . $sql_sort;

foreach($_POST as $field_name=>$fld_val)
	{
		echo $field_name . " - " . $fld_val . "<BR>";
	}

srand ((double) microtime( )*1000000);
$random_number = rand( );


$filequery = "UPDATE loop_bol_files SET bo_date = '" . $bol_date . "', warehouse_id = '". $warehouse_id . "', trans_rec_id = '" . $trans_rec_id . "', employee = '" . $employee . "', rand_value = '" . $random_number . "', file_name = '" . $file_name . "' WHERE id = " . $bol_number;
$fileresult = db_query($filequery);

//$filequery = "INSERT INTO loop_bol_files (bo_date, warehouse_id, trans_rec_id, employee, rand_value, file_name) VALUES ( '" . $bol_date . "', '" . $warehouse_id . "', '" . $trans_rec_id . "', '" . $employee . "', '" . $random_number . "', '" . $file_name . "')";
//$fileresult = db_query($filequery);


// header('Location: http://localhost:1001/ucbloops/bol/' . $file_name);  
header('Location: search_results.php?id=' . $_POST["warehouse_id"] . '&proc=View&searchcrit=&rec_id=' . $_POST["rec_id"] . '&display=buyer_ship&rec_type=' . $_POST["rec_type"]);





echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";

if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: search_results.php?id=' . $_POST["warehouse_id"] . '&proc=View&searchcrit=&rec_id=' . $_POST["rec_id"] . '&display=buyer_ship&rec_type=' . $_POST["rec_type"]); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"search_results.php?id=" . $_POST["warehouse_id"] . "&rec_id=" . $_POST["rec_id"] . "&rec_type=" . $_POST["rec_type"] . "&proc=View&searchcrit=&display=buyer_ship\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=search_results.php?id=" . $_POST["warehouse_id"] . "&rec_id=" . $_POST["rec_id"] . "&rec_type=" . $_POST["rec_type"] . "&proc=View&searchcrit=&display=buyer_ship\" />";
        echo "</noscript>"; exit;
}

//==== End -- Redirect
?>