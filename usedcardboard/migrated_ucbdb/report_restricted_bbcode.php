<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Reports - Restricted BB Code Report.</title>
	<style type="text/css">
		.style7 {
			font-size: xx-small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
			background-color: #FFCC66;
		}

		.style5 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			text-align: center;
			background-color: #99FF99;
		}

		.style6 {
			text-align: center;
			background-color: #99FF99;
		}

		.style2 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
		}

		.style3 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
		}

		.style8 {
			text-align: left;
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
		}

		.style11 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: center;
		}

		.style10 {
			text-align: left;
		}

		.style12 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: right;
		}

		.style13 {
			font-family: Arial, Helvetica, sans-serif;
		}

		.style14 {
			font-size: x-small;
		}

		.style15 {
			font-size: small;
		}

		.style16 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			background-color: #99FF99;
		}

		.style17 {
			background-color: #99FF99;
		}

		select,
		input {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #000000;
			font-weight: normal;
		}
	</style>
</head>

<body>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<BR>
		<form name="rptSearch" action="report_restricted_bbcode.php" method="GET">
			<input type="hidden" name="action" value="run">
			<!--<span class="style2"> <a href="index.php">Home</a></span><br>-->
			<br>
			<span class="style13"><span class="style15">

					<br />
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						List the Restricted BB Code Log
				</span>
				<span class="style14">
					<br />
					<span class="style15">
						<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
						<script LANGUAGE="JavaScript">
							document.write(getCalendarStyles());
						</script>
						<script LANGUAGE="JavaScript">
							var cal1xx = new CalendarPopup("listdiv");
							cal1xx.showNavigationDropdowns();
							var cal2xx = new CalendarPopup("listdiv");
							cal2xx.showNavigationDropdowns();
						</script>
						<?php
						$start_date = isset($_GET["start_date"]) ? strtotime($_GET["start_date"]) : strtotime(date('m/d/Y'));
						$end_date = isset($_GET["end_date"]) ? strtotime($_GET["end_date"]) : strtotime(date('m/d/Y'));
						?>
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Date from:
							<input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
						</font>
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to:
							<input type="text" name="end_date" size="11" value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
						</font>
						&nbsp; <input type="submit" value="Search">
					</span>
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		<br>
		</span>
		</span><br>
		<br />
		<?php

		if (isset($_GET["action"])) {
			if ($_GET["action"] == 'run') {
				$start_date = date('Ymd', $start_date);
				$end_date = date('Ymd', $end_date + 86400);
			}
		}
		if ($start_date > $end_date) {
			echo "<font size=10>End date > Start date, please check.</font>";
		}
		?>
		<table cellSpacing="1" cellPadding="1" width="90%" border="0">
			<tr align="middle">
				<td colSpan="13" class="style7">
					RESTRICTED BB CODE REPORT
				</td>
			</tr>
			<tr>

				<td style="width: 5%" class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						BOX BUCK CODE
					</font>
				</td>
				<td class="style5" style="width: 20%">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						NAME
					</font>
				</td>
				<td align="middle" style="width: 20%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						COMPANY
					</font>
				</td>
				<td align="middle" style="width: 10%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						ADDRESS
					</font>
				</td>
				<td align="middle" style="width: 15%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						ADDRESS 2
					</font>
				</td>
				<td align="middle" style="width: 10%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						CITY
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						ZIPCODE
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						STATE
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						COUNTY
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						TELEPHONE
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						EMAIL ADDRESS
					</font>
				</td>
				<td align="middle" style="width: 10%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						ORDER AMOUNT
					</font>
				</td>
				<td align="middle" style="width: 5%" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DATE
					</font>
				</td>
			</tr>
			<?php
			$queryfinal = "SELECT * FROM boxbuck_restricted_log INNER JOIN coupons ON boxbuck_restricted_log.boxbuck_code=coupons.id";
			if (isset($_GET["start_date"]) && isset($_GET["end_date"])) {
				if ($_GET["start_date"] != "") {
					$queryfinal .= " AND entry_date>='$start_date'";
				}
				if ($_GET["end_date"] != "") {
					$queryfinal .= " AND entry_date<='$end_date'";
				}
			}

			$res = db_query($queryfinal);
			while ($row = array_shift($res)) {
			?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["code"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_name"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_company"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_street_address"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_street_address2"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_city"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_postcode"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_state"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_country"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_telephone"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["customers_email_address"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["order_total"]; ?>
					</td>
					<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"></font>
						<?php echo $row["entry_date"]; ?>
					</td>
				</tr>
			<?php } ?>
	</div>
</body>

</html>