<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Reports - Time Clock</title>
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

	<?php
	// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	?>
	<BR>
	<form name="rptSearch" action="report_hourspertrailer.php" method="GET">
		<input type="hidden" name="action" value="run">
		<span class="style2">
			<a href="index.php">Home</a></span><br>
		<br>
		<span class="style13"><span class="style15">
				<br />
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">

			</span><span class="style14"><span class="style15">
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
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from: <input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a></font>
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to: <input type="text" name="end_date" size="11" value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a></font>
					&nbsp; <input type="submit" value="Search">
	</form>
	<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

	<br></span></span></span>

	<?php

	if ($_GET["action"] == 'run') {
		$start_date = date('Y-m-d', $start_date);
		$end_date = date('Y-m-d', $end_date + 86400);
		if ($start_date > $end_date) {
			//echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
		}
	?>
		<table cellSpacing="1" cellPadding="1" width="800" border="0">
			<tr align="middle">
				<td colSpan="10" class="style7">
					TIMECLOCK REPORT FOR:

					<?php
					$query = "SELECT * FROM `loop_workers` WHERE id = " . decrypt_url($_REQUEST["worker"]);
					$res = db_query($query);
					$row = array_shift($res);

					?>
				</td>
			</tr>
			<tr>
				<td class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DATE
					</font>
				</td>
				<td class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						TIME IN
					</font>
				</td>
				<td class="style5">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						TIME OUT
					</font>
				</td>
				<td align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						AMOUNT
					</font>
				</td>
				<td align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						EDIT
					</font>
				</td>
				<td align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						NOTES
					</font>
				</td>
			</tr>

			<?php
			$query = "SELECT * FROM quote WHERE ";
			if ($_GET["start_date"] != "") {
				$query .= " quoteDate BETWEEN '$start_date'";
			}
			if ($_GET["end_date"] != "") {
				$query .= " AND '$end_date' ORDER BY qstatus ASC";
			}
			// echo $query;
			$res = db_query($query);
			while ($row = array_shift($res)) {
			?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style3" style="height: 22px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["qstatus"]; ?>
						</font>
					</td>
				</tr>
		<?php
			}
		}
		?>
</body>

</html>