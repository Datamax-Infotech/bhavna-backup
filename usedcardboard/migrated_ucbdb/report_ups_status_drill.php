<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>
<head>
	<title>DASH - Reports - UPS Status Detail</title>
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
	<?php // echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";c
	?>
	<BR>
	<span class="style2">
		<a href="index.php">Home</a> <a href="javascript: history.go(-1)">Back</a>
	</span>
	<br><br>
	<span class="style13">
		<span class="style15">
			<?php
			$war_sql = "SELECT * FROM warehouse WHERE warehouse_id = " . decrypt_url($_GET['warehouse_id']);
			$war_res = db_query($war_sql);
			$warehouse = "";
			while ($war_row = array_shift($war_res)) {
				$warehouse = $war_row['name'];
			}
			?>
			<table cellSpacing="1" cellPadding="1" width="500" border="0">
				<tr align="middle">
					<td colSpan="3" class="style7">
						UPS STATUS REPORT DETAIL
					</td>
				</tr>

				<tr>
					<td class="style7" colspan="3" align="center">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Warehouse: <strong><?php echo $warehouse; ?></strong></font>
					</td>
				</tr>
				<tr>
					<td class="style7" colspan="3" align="center">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Module Name: <strong><?php echo $_GET['module_name']; ?></strong></font>
					</td>
				</tr>
				<tr>
					<td class="style7" colspan="3" align="center">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Status: <strong><?php echo $_GET['status']; ?></strong>
					</td>
				</tr>
				<tr>
					<td style="width: 20%" class="style5">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> Order ID</font>
					</td>
					<td style="width: 20%" class="style5">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Tracking Number</font>
					</td>
					<td class="style5" style="width: 20%">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Label Print Date</font>
					</td>
				</tr>
				<?php
				$start_date = $_GET['start_date'];
				$end_date = $_GET['end_date'];
				$warehouse_id = decrypt_url($_GET['warehouse_id']);
				$module_name = $_GET['module_name'];
				$status = $_GET['status'];
				$mod_sql = "";
				if ($_REQUEST["status"] == "Manifest Pickup") {
					$mod_sql = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $warehouse_id . " AND module_name = '" . $module_name . "' AND (status = '" . $status . "' OR fedex_status = 'OC')";
				}
				if ($_REQUEST["status"] == "In Transit") {
					$mod_sql = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $warehouse_id . " AND module_name = '" . $module_name . "' AND (status = '" . $status . "' OR (fedex_status != 'OC' AND fedex_status != '' AND fedex_status !='DE' AND fedex_status != 'DL'))";
				}
				if ($_REQUEST["status"] == "Exception") {
					$mod_sql = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $warehouse_id . " AND module_name = '" . $module_name . "' AND (status = '" . $status . "' OR fedex_status = 'DE')";
				}
				if ($_REQUEST["status"] == "Delivered") {
					$mod_sql = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $warehouse_id . " AND module_name = '" . $module_name . "' AND (status = '" . $status . "' OR fedex_status = 'DL')";
				}

				if ($_GET["start_date"] != "") {
					$mod_sql .= " AND print_date>='$start_date'";
				}
				if ($_GET["end_date"] != "") {
					$mod_sql .= " AND print_date<='$end_date'";
				}
				$mod_res = db_query($mod_sql);
				while ($mod_row = array_shift($mod_res)) {
				?>
					<tr>
						<td style="width: 20%" class="style11">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<a href="orders.php?id=<?php echo encrypt_url($mod_row['orders_id']); ?>&proc=View"><?php echo $mod_row['orders_id']; ?></a>
							</font>
						</td>
						<td class="style11" style="width: 20%">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo $mod_row['tracking_number']; ?>
							</font>
						</td>
						<td class="style11" style="width: 20%">
							<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
								<?php echo $mod_row['print_date']; ?>
							</font>
						</td>
					</tr>
				<?php  } ?>
			</table>
</body>

</html>