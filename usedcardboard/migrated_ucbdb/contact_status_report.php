<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - UPS Status Report</title>
	<style type="text/css">
		.style1 {
			font-size: xx-small;
			background-color: #FF9933;
		}
		.style5 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
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
	</style>

</head>

<body>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<?php
		$type_id = $_GET["type_id"];
		db();
		$contact_it = "SELECT * FROM ucb_contact WHERE type_id = '" . $type_id . "' AND order_no = '' ORDER BY added_on DESC";
		$ship_it_result = db_query($contact_it);
		$ship_it_result_rows = tep_db_num_rows($ship_it_result);
		?>
		<span class="style2">
			<a href="index.php">Home</a>
		</span>
		<br />
		<br />
		<table cellSpacing="1" cellPadding="1" width="98%" border="0">
			<tr align="middle">
				<td colSpan="7" class="style1">
					<font face="Arial, Helvetica, sans-serif" color="#333333">
						CONTACT FORM SUMMARY
					</font>
				</td>
			</tr>
			<tr>
				<td style="width: 6%; height: 16px;" class="style7">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						NAME
					</font>
				</td>
				<td style="width: 6%; height: 16px;" class="style7">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						COMPANY
					</font>
				</td>
				<td class="style5" style="width: 6%; height: 16px;">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						PHONE
					</font>
				</td>
				<td style="width: 11%; height: 16px;" class="style9">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						EMAIL
					</font>
				</td>
				<td class="style5" style="width: 14%; height: 16px;">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DATE
					</font>
				</td>

			</tr>
			<?php
			while ($report_data = array_shift($ship_it_result)) {
			?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style3" style="width: 6%; height: 22px;">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<a href="contact_status_drill.php?id=<?php echo encrypt_url($report_data["id"]); ?>&proc=View&"><?php echo $report_data["first_name"]; ?> <?php echo $report_data["last_name"]; ?></a>
						</font>
					</td>
					<td bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style4">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php echo $report_data["company"]; ?> </font>
					</td>
					<td bgColor="#e4e4e4" style="width: 11%; height: 22px;" class="style4">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><?php echo $report_data["phone1"]; ?> </font>
					</td>
					<td bgColor="#e4e4e4" style="width: 14%; height: 22px;" class="style11"><?php echo $report_data["email"]; ?></td>
					<td bgColor="#e4e4e4" style="width: 6%; height: 22px;">
						<?php
						$dp = $report_data["added_on"];
						$order_date = date("F j Y", strtotime($dp));
						?>
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $order_date; ?> 
						</font>
					</td>
				</tr>
			<?php  } ?>
		</table>
	</div>
</body>
</html>