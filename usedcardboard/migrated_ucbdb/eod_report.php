<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Language" content="en-us" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>DASH - End of Day Entry</title>
	<style type="text/css">
		.style1 {
			font-size: xx-small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
		}

		.style5 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			text-align: center;
		}

		.style6 {
			text-align: center;
		}

		.style2 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
		}

		.style9 {
			font-size: xx-small;
			text-align: center;
		}

		.style8 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
		}

		.style10 {
			font-size: xx-small;
			font-family: Arial, Helvetica, sans-serif;
		}

		.style11 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: small;
		}

		.search input {
			height: 24px !important;
		}
	</style>
</head>

<body>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<!--<span class="style11">
<a href="index.php">Home</a></span><br>-->
		<br />

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

		<form name="rptSearch" action="eod_report.php" method="POST">
			<input type="hidden" name="action" value="run">

			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Enter the Report Date: <input type="text" name="search_date" size="11" value="<?php echo date('m/d/y'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.search_date,'anchor1xx','MM/dd/yy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>

				<input type="submit" value="go">
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		<?php


		if ($_POST["action"] == 'run') {
		?>
			<table cellSpacing="1" cellPadding="1" width="500" border="0">
				<tr align="middle">
					<td bgColor="#ffcccc" colSpan="4" class="style1">
						END OF DAY REPORT </td>
				</tr>
				<tr>
					<td bgColor="#d9f2ff" class="style5" style="width: 13%; height: 16px;">
						Warehouse</td>
					<td bgColor="#d9f2ff" class="style5" style="width: 24%; height: 16px;">
						EOD LABEL #</td>
					<td align="middle" bgColor="#d9f2ff" style="width: 20%; height: 16px;" class="style2">
						UPS PICKED UP</td>
					<td align="middle" bgColor="#d9f2ff" style="width: 20%; height: 16px;" class="style2">
						VIEW FILE</td>
				</tr>
				<?php
				$sql3 = "SELECT * FROM ucbdb_endofday WHERE search_date = '" . $_POST['search_date'] . "'";
				db();
				$result3 = db_query($sql3);
				while ($myrowsel3 = array_shift($result3)) {
				?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" style="width: 13%" class="style10">
							<?php echo $myrowsel3['warehouse_name'] ?></td>
						<td bgColor="#e4e4e4" class="style9" style="width: 24%">
							<?php echo $myrowsel3['labels_on_report'] ?></td>
						<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style8">
							<?php echo $myrowsel3['labels_on_pickup'] ?></td>
						<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style8">
							<a href="show_fax.php?file=<?php echo $myrowsel3['file_name'] ?>">View</a>
						</td>
					</tr>
				<?php  } ?>
			</table>


		<?php  } ?>
	</div>
</body>

</html>