<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>

<html>

<head>
	<title>DASH - Reports - How Did Customers Hear About UCB?</title>
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
		<form name="rptSearch" action="report_how_hear2.php" method="GET">
			<input type="hidden" name="action" value="run">
			<br>

			<span class="style13"><span class="style15">

					<br />
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Find sources of orders placed
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

						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from: <input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to: <input type="text" name="end_date" size="11" value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>


								&nbsp; <input type="submit" value="Search">
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

		<br></span></span></span><br>
		<br />

		<?php

		if ($_GET["action"] == 'run') {
			$start_date = date('Ymd', $start_date);
			$end_date = date('Ymd', $end_date + 86400);

			if ($start_date > $end_date) {
				echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
			}

		?>




			<table cellSpacing="1" cellPadding="1" width="500" border="0">
				<tr align="middle">
					<td colSpan="10" class="style7">
						ORDER SOURCE REPORT</td>
				</tr>
				<tr>

					<td style="width: 55%" class="style17">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							HOW THEY HEARD ABOUT UCB</font>
					</td>
					<td class="style5" style="width: 25%">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							NAME
					</td>
					<td align="middle" style="width: 20%" class="style16">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							AMOUNT
					</td>
				</tr>

				<?php



				$queryfinal = "SELECT * FROM orders INNER JOIN orders_products ON orders.orders_id=orders_products.orders_id";
				if ($_GET["start_date"] != "") {
					$queryfinal .= " AND date_purchased>='$start_date'";
				}
				if ($_GET["end_date"] != "") {
					$queryfinal .= " AND date_purchased<='$end_date'";
				}
				$total_revenue = 0;
				$res = db_query($queryfinal);
				while ($row = array_shift($res)) {


					$total_revenue = $total_revenue + $row["final_price"] * $row["products_quantity"];
				}

				$query = "SELECT * FROM orders INNER JOIN orders_products ON orders.orders_id=orders_products.orders_id";
				if ($_GET["start_date"] != "") {
					$query .= " AND date_purchased>='$start_date'";
				}
				if ($_GET["end_date"] != "") {
					$query .= " AND date_purchased<='$end_date'";
				}
				$query .= " ORDER BY how_to_hear_about";
				$total_orders = 0;
				$current_orders = 0;
				$initial = 1;
				$current_how_hear = "";
				$current_how_hear_revenue = 0;
				$current_order_id = 0;
				$res = db_query($query);
				$current_order_revenue = 0;
				while ($row = array_shift($res)) {

					if ($row["orders_id"] != $current_order_id) {
						$current_orders = $current_orders + 1;
					}
					if ($row["how_to_hear_about"] == $current_how_hear) {
						$current_order_revenue = $current_order_revenue + $row["final_price"] * $row["products_quantity"];
					} else {

						if ($initial == 1) {
				?>
							<tr vAlign="center">
								<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo $row["how_to_hear_about"]; ?>
								</td>

							<?php
							$initial = 0;
						} else {


							?>
								<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo "\$" . number_format($current_order_revenue, 2); ?>
								</td>
								<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo number_format($current_order_revenue / $total_revenue * 100, 2); ?>%
								</td>
							</tr>
							<tr vAlign="center">
								<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo $row["how_to_hear_about"]; ?>
								</td>


						<?php
							$current_order_revenue = 0;
							$current_orders = 1;
							$total_orders = $total_orders + 1;
						}

						$current_order_revenue = $current_order_revenue + $row["final_price"] * $row["products_quantity"];
						$current_how_hear = $row["how_to_hear_about"];
					}

					$current_order_id = $row["orders_id"];
				}
				if ($current_how_hear != "") {
						?>
						<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo "\$" . number_format($current_order_revenue, 2); ?>
						</td>
						<td bgColor="#e4e4e4" style="width: 9%; height: 22px;" class="style3">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo number_format($current_order_revenue / $total_revenue * 100, 2); ?>%
						</td>
							</tr>
						<?php
						$total_orders = $total_orders + 1;
					}
						?>
						<tr vAlign="center">
							<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									Totals
							</td>
							<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									<?php echo "\$" . number_format($total_revenue, 2); ?>
							</td>
							<td bgColor="#e4e4e4" class="style3" style="width: 7%; height: 22px;">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
									100.00%
							</td>
						</tr>


			</table>


		<?php
		}
		?>
	</div>

</body>

</html>