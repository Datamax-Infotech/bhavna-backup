<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - File Cabinet</title>

	<SCRIPT TYPE="text/javascript">
		function company(val) {
			if (val < 0) {
				document.rptSearch.newcompany.style.visibility = "visible";

			} else {
				document.rptSearch.newcompany.style.visibility = "hidden";

			}
		}

		function typeadd(val) {
			if (val < 0) {
				document.rptSearch.newtype.style.visibility = "visible";

			} else {
				document.rptSearch.newtype.style.visibility = "hidden";

			}
		}
	</SCRIPT>
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
	<BR>
	<form name="rptSearch" action="addfileupload.php" method="POST" encType="multipart/form-data">
		<span class="style2">
			<a href="index.php">Home</a></span><br>
		<br>

		<span class="style13"><span class="style15">
				<table>
					<tr>
						<td>Company</td>
						<td>
							<select name="company_id" onchange="company(this.value)">
								<option value="0"></option>
								<option value="-1">Add New</option>
								<?php
								$query = "SELECT * FROM files_companies ORDER BY name ASC";
								$res = db_query($query);

								while ($row = array_shift($res)) {
									echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
								}
								?>
							</select> <input type=text size="30" name="newcompany" style="visibility:hidden">
						</td>
					</tr>
					<tr>
						<td>Type</td>
						<td>
							<select name="type_id" onchange="typeadd(this.value)">
								<option value="0"></option>
								<option value="-1">Add New</option>
								<?php
								$query = "SELECT * FROM files_types ORDER BY name ASC";
								$res = db_query($query);

								while ($row = array_shift($res)) {
									echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
								}
								?>
							</select> <input type=text size="30" name="newtype" style="visibility:hidden">
						</td>
					</tr>
					<tr>
						<td>Date</td>
						<td>
							<span class="style14"><span class="style15">
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
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a></font>
						</td>
					</tr>
					<tr>
						<td>Memo:</td>
						<td><input type=text name="memo" length=60></td>
				</table>
				&nbsp; <br><input type="submit" value="Upload File">
			</span>
		</span>
	</form>
	<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

	<br><br>
	<br />
	<?php
	if ($_REQUEST["fileid"] > 0) {
		$query = "SELECT files_file.filename AS A, files_companies.name AS B, files_types.name AS C, files_file.date AS D, files_file.memo AS E FROM files_file INNER JOIN files_companies ON files_file.company_id = files_companies.id INNER JOIN files_types ON files_file.type_id = files_types.id WHERE files_file.id = " . $_REQUEST["fileid"];
		$res = db_query($query);
		$row = array_shift($res);
	?>
		<font color="red">Added</font><br>
		Company: <?php echo $row["B"]; ?><br>
		Type: <?php echo $row["C"]; ?><br>
		Date: <?php echo $row["D"]; ?><br>
		Memo: <?php echo $row["E"]; ?><br>
		<embed src="uploadedfiles/<?php echo $row["A"]; ?>" width="1000" height="1000">
	<?php
	} ?>
</body>

</html>