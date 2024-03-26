<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>

<html>

<head>
	<title>Update EOD</title>
	<meta http-equiv="refresh" content="300">
</head>

<style type="text/css">
	.black_overlay {
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: gray;
		z-index: 1001;
		-moz-opacity: 0.8;
		opacity: .80;
		filter: alpha(opacity=80);
	}

	.white_content {
		display: none;
		position: absolute;
		top: 5%;
		left: 10%;
		width: 40%;
		height: 40%;
		padding: 16px;
		border: 1px solid gray;
		background-color: white;
		z-index: 1002;
		overflow: auto;
	}
</style>

<body>
	<?php
	if (isset($_REQUEST["btnqtyofkitship"])) {
		$rec_found = "no";
		$unqid = 0;
		$query4 = "SELECT * FROM orders_by_warehouse_eod where warehouse_name = '" . $_REQUEST["hd_warehouse"] . "' and eod_date = '" . date("Y-m-d") . "'";
		$dt_view_res4 = db_query($query4);
		while ($objQuote4 = array_shift($dt_view_res4)) {
			$rec_found = "yes";
			$unqid = $objQuote4["unqid"];
		}
		$count_shipped = 0;
		$count_pending_ship = 0;

		$warehouse_name = "";
		if ($_REQUEST["hd_warehouse"] == "la") {
			$warehouse_name = "Los Angeles";

			$sql_pc = "SELECT * FROM `orders_active_ucb_los_angeles` inner join orders on orders.orders_id = orders_active_ucb_los_angeles.orders_id WHERE ship_status LIKE 'Y' and DATE_FORMAT(date_purchased, '%Y-%m-%d') = '" . date("Y-m-d") . "'";
			$result_pc = db_query($sql_pc);
			$count_shipped = tep_db_num_rows($result_pc);

			$sql_pc = "SELECT * FROM `orders_active_ucb_los_angeles` where ship_status LIKE 'N'";
			$result_pc = db_query($sql_pc);
			$count_pending_ship = tep_db_num_rows($result_pc);
		}
		if ($_REQUEST["hd_warehouse"] == "hv") {
			$warehouse_name = "Hunt Valley";
			$sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` inner join orders on orders.orders_id = orders_active_ucb_hunt_valley.orders_id WHERE ship_status LIKE 'Y' and DATE_FORMAT(date_purchased, '%Y-%m-%d') = '" . date("Y-m-d") . "'";
			$result_pc = db_query($sql_pc);
			$count_shipped = tep_db_num_rows($result_pc);

			$sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` where ship_status LIKE 'N'";
			$result_pc = db_query($sql_pc);
			$count_pending_ship = tep_db_num_rows($result_pc);
		}
		if ($_REQUEST["hd_warehouse"] == "ha") {
			$warehouse_name = "Hannibal";
			$sql_pc = "SELECT * FROM `orders_active_ucb_hannibal` inner join orders on orders.orders_id = orders_active_ucb_hannibal.orders_id WHERE ship_status LIKE 'Y' and DATE_FORMAT(date_purchased, '%Y-%m-%d') = '" . date("Y-m-d") . "'";
			$result_pc = db_query($sql_pc);
			$count_shipped = tep_db_num_rows($result_pc);

			$sql_pc = "SELECT * FROM `orders_active_ucb_hannibal` where ship_status LIKE 'N'";
			$result_pc = db_query($sql_pc);
			$count_pending_ship = tep_db_num_rows($result_pc);
		}
		if ($_REQUEST["hd_warehouse"] == "sl") {
			$warehouse_name = "Salt Lake";
			$sql_pc = "SELECT * FROM `orders_active_ucb_salt_lake` inner join orders on orders.orders_id = orders_active_ucb_salt_lake.orders_id WHERE ship_status LIKE 'Y' and DATE_FORMAT(date_purchased, '%Y-%m-%d') = '" . date("Y-m-d") . "'";
			$result_pc = db_query($sql_pc);
			$count_shipped = tep_db_num_rows($result_pc);

			$sql_pc = "SELECT * FROM `orders_active_ucb_salt_lake` where ship_status LIKE 'N'";
			$result_pc = db_query($sql_pc);
			$count_pending_ship = tep_db_num_rows($result_pc);
		}

		$message = "Following are the EOD details:<br><br>";
		$message .= "Warehouse name: " . $warehouse_name . "<br>";
		if ($count_pending_ship > 0) {
			$message .= "Labels to be Printed: <font color=red>" . $count_pending_ship . "(NOT ALL LABELS PRINTED BY EOD) </font><br>";
			echo "<script>";
			echo "alert('NOT ALL LABELS PRINTED BY EOD');";
			echo "</script>";
		} else {
			$message .= "Labels to be Printed: " . $count_pending_ship . "<br>";
		}
		$message .= "No. of Qty of Kits Shipped (as per database): " . $count_shipped . "<br>";
		if ($count_shipped != $_REQUEST["qtyofkitship"]) {
			$message .= "No. of Qty of Kits Shipped (as per EOD entry): <font color=red>" . $_REQUEST["qtyofkitship"] . "(QTY SHIPPED DIFFERENT FROM QTY PRINTED)</font><br>";
			echo "<script>";
			echo "alert('QTY SHIPPED DIFFERENT FROM QTY PRINTED');";
			echo "</script>";
		} else {
			$message .= "No. of Qty of Kits Shipped (as per EOD entry): " . $_REQUEST["qtyofkitship"] . "<br>";
		}
		$message .= "Date: " . date("Y-m-d H:i:s") . " CT<br>";
		$message .= "Initials: " . $_COOKIE["userinitials"] . "<br>";

		$mailheaders = "From: Loops System <admin@usedcardboardboxes.com>\n";
		$mailheaders .= "MIME-Version: 1.0\r\n";
		$mailheaders .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail("customerservice@UsedCardboardBoxes.com", "B2C End of Day status - " . $warehouse_name, $message, $mailheaders);
		//mail("prasad@extractinfo.com","B2C End of Day status - " . $warehouse_name, $message, $mailheaders);

		if ($rec_found == "yes") {
			$sql = "UPDATE orders_by_warehouse_eod SET eod_date = '" . date("Y-m-d") . "', warehouse_name = '" . $warehouse_name . "', no_of_kits_shipped = '" . $_REQUEST["qtyofkitship"] . "', eod_entry_date = '" . date("Y-m-d H:i:s") . "', eod_entry_done_by = '" . $_COOKIE['userinitials'] . "'	where unqid = '" . $unqid . "'";
			$result = db_query($sql);
		} else {
			$sql = "Insert into orders_by_warehouse_eod (eod_date, warehouse_name, no_of_kits_shipped, eod_entry_date, eod_entry_done_by) values('" . date("Y-m-d") . "', '" . $warehouse_name . "', '" . $_REQUEST["qtyofkitship"] . "', '" . date("Y-m-d H:i:s") . "', '" . $_COOKIE['userinitials'] . "')";
			$result = db_query($sql);
		}

		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php" . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
		echo "</noscript>";
		exit;
	}
	?>
</body>

</html>