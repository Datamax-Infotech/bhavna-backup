<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Reports - Inventory </title>
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
	<form name="rptSearch" action="report_inventory.php" method="GET">
		<?php
		$warehouse = isset($_GET["warehouse"]) ? $_GET["warehouse"] : 'A';
		$reason_code = isset($_GET["reason_code"]) ? $_GET["reason_code"] : 'A';
		?>
		<input type="hidden" name="action" value="run">
		<span class="style2">
			<a href="index.php">Home</a></span><br>
		<br>
		<span class="style13"><span class="style15">
				<br />
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
					Select Inventory from
			</span><span class="style14"><span class="style15">
					<select name="warehouse">
						<option value="A" <?php echo ($warehouse == 'A') ? ' selected' : '' ?>>All Warehouse Types</option>
						<?php

						$sql2 = "SELECT DISTINCT warehouse_type FROM inv_warehouse_to_modules ORDER BY warehouse_id";
						$result2 = db_query($sql2);
						while ($myrowsel2 = array_shift($result2)) {

						?>
							<option value="<?php echo $myrowsel2["warehouse_type"]; ?>"><?php echo $myrowsel2["warehouse_type"]; ?></option>
						<?php  } ?>
					</select>


					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">and view <select name="reason_code">
							<option value="A" <?php echo ($reason_code == 'A') ? ' selected' : '' ?>>All Modules</option>
							<?php

							$sql3 = "SELECT DISTINCT module_name FROM inv_warehouse_to_modules ORDER BY module_id";
							$result3 = db_query($sql3);
							while ($myrowsel3 = array_shift($result3)) {
							?>
								<option value="<?php echo $myrowsel3["module_name"]; ?>"><?php echo $myrowsel3["module_name"]; ?></option>
							<?php  } ?>
						</select>



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

						<!-- <font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from: <input type="text" name="start_date" size="11" value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a> -->
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to: <input type="text" name="end_date" size="11" value="<?php echo date('m/d/y'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>


							&nbsp; <input type="submit" value="Search">
	</form>
	<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

	</span></span></span><br>
	<?php
	$get_the_date = "SELECT * FROM inv_start_date";
	$get_the_date_res = db_query($get_the_date);
	while ($row_date = array_shift($get_the_date_res)) {
		$start_date = $row_date['update_date'];
	}
	?>
	The starting Invensoty date is: <strong><?php echo date('m/d/Y', strtotime($start_date)); ?></strong>. Starting quantities are based on this date. Once this report format is approved, the starting quantity and adjustment columns will be hidden. They are visible simply to aid in testing.
	<br>

	<br />

	<?php

	if ($_GET["action"] == 'run') {
		$end_date = date('Ymd', $start_date);

		if ($start_date > $end_date) {
			echo "<font size=20>The start date cannot come after the end date.  <br>Try again.</font>";
			exit;
		}
	?>
		<table cellSpacing="1" cellPadding="1" width="98%" border="0">
			<tr align="middle">
				<td colSpan="6" class="style7">
					Inventory Report</td>
			</tr>
			<tr>
				<td style="width: 7%" class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Warehouse Name</font>
				</td>
				<td style="width: 7%" class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Module Type</font>
				</td>
				<td class="style5" style="width: 9%">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Starting Quantity <?php  /* echo date( 'm/d/Y',strtotime($start_date)); */ ?>
				</td>
				<td class="style5" style="width: 9%">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Adjustments
				</td>
				<td class="style5" style="width: 9%">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Inventory Used
				</td>
				<td class="style5" style="width: 9%">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Ending Quantity on <?php echo date('m/d/Y', strtotime($end_date)); ?>
				</td>
			</tr>

			<?php
			$query = "SELECT * FROM inv_warehouse_to_modules WHERE warehouse_name != '' ";
			if ($reason_code != 'A') {
				$query .= " AND module_name='$reason_code'";
			}
			if ($warehouse != 'A') {
				$query .= " AND warehouse_type='$warehouse'";
			}
			$res = db_query($query);
			while ($row = array_shift($res)) {
				$quantity = $row["quantity"];
			?>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["warehouse_name"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["module_name"]; ?>
						</font>
					</td>
					<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $quantity; ?>
						</font>
					</td>
					<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php
							$tiger = "SELECT SUM(quantity) FROM inv_warehouse_transactions WHERE warehouse_id = " . $row["warehouse_id"] . " AND module_name = '" . $row["module_name"] . "' AND update_date >= '" . date('Ymd', strtotime($start_date)) . "' AND update_date <= '" . date('Ymd', strtotime($end_date)) . "' GROUP BY warehouse_id";
							// echo $tiger;
							$tiger_res = db_query($tiger);
							$tiger_res_rows = tep_db_num_rows($tiger_res);
							$adjusted_qty = 0;
							while ($tiger_row = array_shift($tiger_res)) {
								$adjusted_qty = $tiger_row["SUM(quantity)"];
								echo $adjusted_qty;
							}
							?>
						</font>
					</td>


					<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php
							$the_date_timestamp = date("Y-m-d H:m:i", strtotime($start_date));
							$monster = "SELECT * FROM orders_active_export WHERE warehouse_id = " . $row["warehouse_id"] . " AND module_name = '" . $row["module_name"] . "' AND status != 'Manifest Pickup' AND print_date >= '" . date('Y-m-d H:i:s', strtotime($start_date)) . "' AND print_date <= '" . date('Y-m-d H:i:s', strtotime($end_date)) . "'";
							$monster_res = db_query($monster);
							$monster_res_rows = tep_db_num_rows($monster_res);
							$used = $monster_res_rows;
							echo $used;
							?>
					</td>
					<?php
					$pre_balance = $quantity + $adjusted_qty;
					$balance = $pre_balance - $used;
					?>
					<td align="middle" bgColor="#e4e4e4" style="width: 5%; height: 22px;" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $balance; ?>
					</td>
				</tr>

			<?php
				// Reset the Variables
				$quantity = 0;
				$adjusted_qty = 0;
				$used = 0;
			}
			?>
		</table>

		<br>
		<br>
		<a href="inventory_transfer.php?proc=New&">Click Here</a> to Transfer or Replenish Inventory
	<?php
	}
	?>
</body>

</html>