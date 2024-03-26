<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>DASH - Reports - Survey Responses</title>
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
		<form name="rptSearch" action="report_survey.php" method="GET">
			<input type="hidden" name="action" value="run">
			<!--<span class="style2"><a href="index.php">Home</a></span><br>-->
			<br>
			<span class="style13">
				<span class="style15">
					<br />
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Find surveys
					</font>
				</span>
				<span class="style14">
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
						$start_date = isset($_REQUEST["start_date"]) ? strtotime($_REQUEST["start_date"]) : strtotime(date('m/d/Y'));
						$end_date = isset($_REQUEST["end_date"]) ? strtotime($_REQUEST["end_date"]) : strtotime(date('m/d/Y'));
						?>

						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from:
							<input type="text" name="start_date" size="11" value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>">
							<a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
						</font>
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to:
							<input type="text" name="end_date" size="11" value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>">
							<a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
							<input type=radio <?php if ($_REQUEST["surveyview"] == "1" || $_REQUEST["surveyview"] == "") {
													echo "checked";
												} ?> name="surveyview" value="1">Show All
							<input type=radio name="surveyview" <?php if ($_REQUEST["surveyview"] == "0") {
																	echo "checked";
																} ?> value="0">
							Show Uncategorized
						</font>
						&nbsp; <input type="submit" value="Search">
					</span>
				</span>
			</span>
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		<br><br>
		<br />
		<?php
		if ($_REQUEST["update"] == 'save') {
			echo "<font color=red><b>SAVED</b></font>";
			foreach ($_REQUEST as $key => $value) {
				$user = strstr($key, 'cat', true); // As of PHP 5.3.0
				if (substr($key, 0, 3) == "cat") {
					db_query("UPDATE survey_nps SET category" . $key[3] . "=" . $value . " WHERE id=" . substr($key, 4));
				}

				if (substr($key, 0, 4) == "note") {
					db_query("UPDATE survey_nps SET notes='" . $value . "' WHERE id=" . substr($key, 4));
				}
			}
		}
		$promoter = 0;
		$neutral = 0;
		$detractor = 0;
		$ave = 0;
		if ($_REQUEST["action"] == 'run') {
			
			$start_date = date('Ymd', $start_date);
			$end_date = date('Ymd', $end_date + 86400);

			if ($start_date > $end_date) {
				echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
			}

		?>
			<form name="rptSearch2" action="report_survey.php" method="POST">
				<input type=hidden name="surveyview" value="<?php echo $_REQUEST["surveyview"]; ?>">
				<input type=hidden name="update" value="save">
				<input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>">
				<input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>">
				<input type=hidden name="action" value="run">
				<table cellSpacing="1" cellPadding="1" width="1400" border="0">
					<tr align="middle">
						<td colSpan="10" class="style7">
							REFERRED ORDER REPORT</td>
					</tr>
					<tr>
						<td style="width: 70" class="style17">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								DATE
							</font>
						</td>
						<td style="width: 30" class="style17">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								ORDER ID
							</font>
						</td>
						<td class="style5" style="width: 20">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								NPS #
							</font>
						</td>
						<td class="style5" style="width: 200">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Customer Name
							</font>
						</td>
						<td align="middle" style="width: 600" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Recommendation
							</font>
						</td>
						<td align="middle" style="width: 20" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Contact Ok?(If < 10) </font>
						</td>
						<td align="middle" style="width: 20" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Can use for Marketing?(If = 10)
							</font>
						</td>
						<td align="middle" style="width: 100" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Category 1
							</font>
						</td>
						<td align="middle" style="width: 100" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Category 2
							</font>
						</td>
						<td align="middle" style="width: 100" class="style16">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								Notes
							</font>
						</td>
					</tr>
					<?php
					$query = "SELECT * FROM survey_nps WHERE ";
					if ($_REQUEST["start_date"] != "") {
						$query .= " date>='$start_date'";
					}
					if ($_REQUEST["end_date"] != "") {
						$query .= " AND date<='$end_date'";
					}
					if ($_REQUEST["surveyview"] == "0") {
						$query .= " AND category1=0";
					}
					$res = db_query($query);
					while ($row = array_shift($res)) {

						$customer_name = "";
						if ($row["order_id"] > 0) {
							$opt = db_query("Select customers_name FROM orders where orders_id = " . $row["order_id"]);
							while ($option_select = array_shift($opt)) {
								$customer_name = $option_select["customers_name"];
							}
						}
					?>
						<tr vAlign="center">
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php echo date('m-d-Y', strtotime($row["date"])); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php if ($row["order_id"] > 0) { ?>
										<a target="_blank" href="https://b2c.usedcardboardboxes.com/orders.php?id=<?php echo encrypt_url($row["order_id"]); ?>&proc=View&searchcrit=">
											<?php echo $row["order_id"]; ?>
										</a>
									<?php } else { ?>
										<?php echo $row["order_id"]; ?>
									<?php } ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php echo $row["nps"]; ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php echo $customer_name; ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php echo $row["recommendation"]; ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" align="center" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php
									if ((int) $row["nps"] < 10) {
										$contact_val = "-";
										if ($row["contactok"] == "Y") {
											$contact_val = "Y";
										}
										if ($row["contactok"] == "N") {
											$contact_val = "N";
										}
										echo $contact_val;
									} else {
										echo "-";
									}
									?>
								</font>
							</td>
							<td bgColor="#e4e4e4" align="center" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php
									if ((int) $row["nps"] == 10) {
										$contact_val = "-";
										if ($row["contactok"] == "Y") {
											$contact_val = "Y";
										}
										if ($row["contactok"] == "N") {
											$contact_val = "N";
										}
										echo $contact_val;
									} else {
										echo "-";
									}
									?>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<select name="cat1<?php echo $row["id"] ?>">
										<option value=0>Please Select</option>
										<?php
										$opt = db_query("Select * FROM survey_categories ORDER BY dropdown");
										while ($option_select = array_shift($opt)) {
											if ($option_select["id"] == $row["category1"]) {
												echo "<option selected value=" . $option_select["id"] . ">" . $option_select["dropdown"] . "</option>";
											} else {
												echo "<option value=" . $option_select["id"] . ">" . $option_select["dropdown"] . "</option>";
											}
										}
										?>
									</select>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<select name="cat2<?php echo $row["id"] ?>">
										<option value=0>Please Select</option>
										<?php
										$opt = db_query("Select * FROM survey_categories ORDER BY dropdown");
										while ($option_select = array_shift($opt)) {
											if ($option_select["id"] == $row["category2"]) {
												echo "<option selected value=" . $option_select["id"] . ">" . $option_select["dropdown"] . "</option>";
											} else {
												echo "<option value=" . $option_select["id"] . ">" . $option_select["dropdown"] . "</option>";
											}
										}
										?>
									</select>
								</font>
							</td>
							<td bgColor="#e4e4e4" class="style3">
								<input size=100 name="note<?php echo $row["id"]; ?>" value="<?php echo $row["notes"]; ?>">
							</td>
						</tr>

					<?php
						if ($row["nps"] >= 0 && $row["nps"] < 7) {
							$detractor += 1;
						}
						if ($row["nps"] >= 6 && $row["nps"] < 9) {
							$neutral += 1;
						}
						if ($row["nps"] >= 9) {
							$promoter += 1;
						}
						$ave += $row["nps"];
					}
					?>
				</table>
				<input type="submit" value="Update Records">
			<?php
		}
			?>
			</form>
			<br>
			Detractors:
			<?php echo $detractor; ?><br>
			Neutrals:
			<?php echo $neutral; ?><br>
			Promoters:
			<?php echo $promoter; ?><br>
			Average:
			<?php echo number_format($ave / ($detractor + $neutral + $promoter), 2); ?><br>
			<b>NPS:
				<?php echo number_format(100 * ($promoter - $detractor) / ($detractor + $neutral + $promoter), 0); ?>
			</b>
	</div>
</body>

</html>