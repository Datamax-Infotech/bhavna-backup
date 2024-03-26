<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
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
			background-color: #FF9933;
		}

		.style5 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			text-align: center;
			background-color: #e4e4e4;
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

		.style4 {
			text-align: left;
		}

		.style7 {
			background-color: #99FF99;
		}

		.style8 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			background-color: #99FF99;
		}

		.style9 {
			text-align: center;
			background-color: #99FF99;
		}

		.style10 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: xx-small;
		}

		.style11 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: xx-small;
			text-align: center;
		}

		.search input {
			height: 24px !important;
		}
	</style>
</head>
<body>
	<div>
		<?php 
			include("inc/header.php");
		?>
	</div>
	<div class="main_data_css">
		<br />
		<?php
		?>
		<form action="addeod.php" method="post" encType="multipart/form-data" name="rptSearch">
			<table cellSpacing="1" cellPadding="1" width="500" border="0">
				<tr align="middle">
					<td colSpan="4" class="style1">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							UPLOAD END OF DAY
						</font>
					</td>
				</tr>
				<tr>
					<td class="style5" style="width: 7%; height: 16px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							Warehouse
						</font>
					</td>
					<td class="style5" style="width: 7%; height: 16px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							Date
						</font>
					</td>
					<td class="style5" style="width: 24%; height: 16px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							# of LABELS ON EOD REPORT
						</font>
					</td>
					<td class="style5" style="width: 24%; height: 16px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							# UPS PICKED UP
						</font>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" style="width: 7%" class="style10">
						<select name="warehouse_name">
							<?php
							$sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
							$result2 = db_query($sql2);
							$thewarehouse = isset($_GET['warehouse_name']) ? $_GET['warehouse_name'] : '';
							echo "<option value='" . $thewarehouse . "'>" . $thewarehouse . "</option><option></option>";
							while ($myrowsel2 = array_shift($result2)) {
							?>
								<option value="<?php echo $myrowsel2["distribution_center"]; ?>"><?php echo $myrowsel2["distribution_center"]; ?></option>
							<?php  } ?>
						</select>
					</td>
					<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
					<script LANGUAGE="JavaScript">
						document.write(getCalendarStyles());
					</script>
					<script LANGUAGE="JavaScript">
						var cal1xx = new CalendarPopup();
						cal1xx.showNavigationDropdowns();
						var cal2xx = new CalendarPopup("listdiv");
						cal2xx.showNavigationDropdowns();
					</script>
					<td bgColor="#e4e4e4" style="width: 7%" class="style10">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Date: <input type="text" name="search_date" size="11" value="<?php echo date('m/d/y'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.search_date,'anchor1xx','MM/dd/yy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
					</td>
					<td bgColor="#e4e4e4" class="style5" style="width: 24%">
						<input size="20 " type="text" style="width: 66px" name="labels_on_report">
					</td>
					<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style5">
						<input type="text" size="5" style="width: 55px" name="labels_on_pickup">
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" style="width: 7%" class="style5">
						EOD LABEL</td>
					<td bgColor="#e4e4e4" class="style5" colspan="3" style="width: 24%">
						<input type="file" name="file" size="50">
					</td>
				</tr>
				<tr>
					<td colspan="3" class="style6"> <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" /> <input type="submit" value="Submit EOD"></td>
				</tr>
			</table>
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		<br />
		<br />
		<br />
		<?php
		if ($_GET['link'] == 'Yes') {
			$today = date('m/d/y');
			$eodsql = "SELECT * FROM ucbdb_endofday where search_date = '" . $today . "' AND warehouse_name = '" . $_GET['warehouse_name'] . "'";
			$eodsql_result = db_query($eodsql);
			$eodsql_result_count = tep_db_num_rows($eodsql_result);
			while ($eodsql_result_array = array_shift($eodsql_result)) {
		?>
				<font face="arial" size="2"><strong>End of Day Report for <?php echo $_GET['warehouse_name']; ?> on <?php echo date('m/d/y'); ?><br></strong>
					<IFRAME SRC="show_fax.php?file=<?php echo $eodsql_result_array['file_name']; ?>" WIDTH=1121 HEIGHT=1000>
						If you can see this, your browser doesn't understand IFRAME. However, we'll still <A HREF="faxes/<?php echo $eodsql_result_array['file_name']; ?>">link</A> you to the file.
					</IFRAME>
				</font>
			<?php

			}
		}
			?>
			<table cellSpacing="1" cellPadding="1" width="500" border="0">
				<tr align="middle">
					<td bgColor="#ffcccc" colSpan="4" class="style1">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							END OF DAYS SUBMITTED TODAY <?php echo date('m/d/y'); ?>
						</font>
					</td>
				</tr>
				<tr>
					<td bgColor="#d9f2ff" class="style5" style="width: 13%; height: 16px;">
						Warehouse
					</td>
					<td bgColor="#d9f2ff" class="style5" style="width: 24%; height: 16px;">
						EOD LABEL #
					</td>
					<td class="style5" bgColor="#d9f2ff" style="width: 20%; height: 16px;" class="style2">
						UPS PICKED UP
					</td>
					<td class="style5" bgColor="#d9f2ff" style="width: 20%; height: 16px;" class="style2">
						VIEW FILE
					</td>
				</tr>
				<?php
				$sql3 = "SELECT * FROM ucbdb_endofday WHERE search_date = '" . date('m/d/y') . "'";
				$result3 = db_query($sql3);
				while ($myrowsel3 = array_shift($result3)) {
				?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" style="width: 13%" class="style10">
							<?php echo $myrowsel3['warehouse_name'] ?></td>
						<td align="middle" bgColor="#e4e4e4" class="style10" style="width: 24%">
							<?php echo $myrowsel3['labels_on_report'] ?></td>
						<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style10">
							<?php echo $myrowsel3['labels_on_pickup'] ?></td>
						<td align="middle" bgColor="#e4e4e4" style="width: 20%" class="style10">
							<a href="show_fax.php?file=<?php echo $myrowsel3['file_name'] ?>">View</a>
						</td>
					</tr>
				<?php  } ?>
			</table>
	</div>
</body>
</html>