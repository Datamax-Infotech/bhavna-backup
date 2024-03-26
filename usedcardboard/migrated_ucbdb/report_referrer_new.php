<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Reports - Referrered Orders</title>
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
		<form name="rptSearch" action="report_referrer_new.php" method="GET">
			<input type="hidden" name="action" value="run">
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
			<?php
			$start_date = isset($_GET["start_date"]) ? strtotime($_GET["start_date"]) : strtotime(date('m/d/Y'));
			$end_date = isset($_GET["end_date"]) ? strtotime($_GET["end_date"]) : strtotime(date('m/d/Y'));
			?>
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Date from:
				<input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>">
				<a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
			</font>
			<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to:
				<input type="text" name="end_date" size="11" value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>">
				<a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
			</font>
			&nbsp; <input type="submit" value="Search">
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		<br /><br /><br />
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
					<td colSpan="4" class="style7">
						REFERRED ORDER REPORT</td>
				</tr>
				<tr>
					<td style="width: 20%" class="style17">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							SOURCE
						</font>
					</td>
					<td style="width: 10%" class="style17">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							TOTAL CLICKS
						</font>
					</td>
					<td style="width: 10%" class="style17">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							TOTAL ORDERS
						</font>
					</td>
					<td class="style5" style="width: 10%">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							TOTAL REVENUE
						</font>
					</td>
				</tr>
				<?php
				$sql_mainref = "SELECT site_referrer FROM orders INNER JOIN orders_products ON orders.orders_id=orders_products.orders_id ";
				if ($_GET["start_date"] != "") {
					$sql_mainref .= " where orders.date_purchased>='$start_date'";
				}
				if ($_GET["end_date"] != "") {
					$sql_mainref .= " AND orders.date_purchased<='$end_date'";
				}
				$sql_mainref .= " group by orders.site_referrer order by site_referrer";

				$result_mainref = db_query($sql_mainref);

				while ($myrowsel_mainref = array_shift($result_mainref)) {

					$referrer = $myrowsel_mainref["site_referrer"];

					$query_clicks = "SELECT * FROM site_ref_key_hits WHERE referrer LIKE '" . $referrer . "'";
					if ($_GET["start_date"] != "") {
						$query_clicks .= " AND acs_date>='$start_date'";
					}
					if ($_GET["end_date"] != "") {
						$query_clicks .= " AND acs_date<='$end_date'";
					}
					$total_clicks = 0;
					$res_clicks = db_query($query_clicks);
					while ($row = array_shift($res_clicks)) {
						$total_clicks = $total_clicks + 1;
					}

					$query = "SELECT count(*) as totorder FROM orders INNER JOIN orders_products ON orders.orders_id=orders_products.orders_id WHERE site_referrer LIKE '$referrer'";
					if ($_GET["start_date"] != "") {
						$query .= " AND date_purchased>='$start_date'";
					}
					if ($_GET["end_date"] != "") {
						$query .= " AND date_purchased<='$end_date'";
					}
					$query .= " group by orders_products.orders_id";
					$total_revenue = 0;
					$total_orders = 0;
					$res_totorder = db_query($query);

					$total_orders = tep_db_num_rows($res_totorder);

					$query = "SELECT sum(final_price * products_quantity) as totorderamt FROM orders INNER JOIN orders_products ON orders.orders_id=orders_products.orders_id WHERE site_referrer LIKE '$referrer'";
					if ($_GET["start_date"] != "") {
						$query .= " AND date_purchased>='$start_date'";
					}
					if ($_GET["end_date"] != "") {
						$query .= " AND date_purchased<='$end_date'";
					}
					$query .= " group by orders_products.orders_id";

					$res_totamt = db_query($query);
					while ($row_amt = array_shift($res_totamt)) {
						$total_revenue = $total_revenue + $row_amt["totorderamt"];
					}

				?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style3" style="width: 20%; height: 22px;">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo $myrowsel_mainref["site_referrer"]; ?>
							</font>
						</td>
						<td bgColor="#e4e4e4" class="style3" style="width: 10%; height: 22px;">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo $total_clicks; ?>
							</font>
						</td>
						<td bgColor="#e4e4e4" style="width: 10%; height: 22px;" class="style3">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo $total_orders; ?>
							</font>
						</td>
						<td bgColor="#e4e4e4" style="width: 10%; height: 22px;" class="style3">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo '$' . number_format($total_revenue, 2); ?>
							</font>
						</td>
					</tr>
			<?php
				}
			}
			?>
			</table>
	</div>
</body>

</html>