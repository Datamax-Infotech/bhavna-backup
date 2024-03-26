<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<!DOCTYPE html>
<html>
<head>
	<title>DASH - Reports - Receivables</title>
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

	<?php
	?>


	<BR>


	<form name="rptSearch" action="report_ups_module.php" method="GET">
		<input type="hidden" name="action" value="run">
		<span class="style2">
			<a href="index.php">Home</a></span><br>
		<br>
		<span class="style13"><span class="style15">
				<table cellSpacing="1" cellPadding="1" width="500" border="0">
					<tr align="middle">
						<td colSpan="10" class="style7">
							ACCOUNTS RECEIVABLE REPORT</td>
					</tr>
					<tr align="middle">
						<td colSpan="10">
						</td>
					</tr>
					<?php
					//$start_date = date('Ymd', $start_date);
					//$end_date = date('Ymd', $end_date + 86400);
					$mod_sql = "SELECT * FROM loop_warehouse WHERE rec_type LIKE 'Supplier' ORDER BY company_name ASC";
					$mod_res = db_query($mod_sql);
					while ($row = array_shift($mod_res)) {
						$inv = 0;
						$amount = 0;
						$amount2 = 0;
						$mod_sql2 = "SELECT loop_transaction_buyer.id AS I, inv_amount, amount FROM loop_transaction_buyer LEFT JOIN loop_buyer_payments ON loop_transaction_buyer.id = loop_buyer_payments.trans_rec_id WHERE loop_transaction_buyer.warehouse_id = " . $row["id"];
						$mod_res2 = db_query($mod_sql2);
						while ($row2 = array_shift($mod_res2)) {
							$inv = $inv + $row2["inv_amount"];
							$amount = $amount + $row2["amount"];
							$mod_sql3 = "SELECT amount FROM loop_buyer_payments WHERE trans_rec_id = " . $row2["I"];
							$mod_res3 = db_query($mod_sql3);
							while ($row3 = array_shift($mod_res3)) {
								$amount2 = $amount2 + $row3["amount"];
							}
						}
						if (number_format($inv, 2) != number_format($amount2, 2)) {
					?>
							<tr>
								<td style="width: 70%" class="style5">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<a href=http://loops.usedcardboardboxes.com/search_results.php?id=<?php echo $row["id"] ?>&proc=View&rec_type=Supplier&page=0" target=_blank><?php echo $row["company_name"] ?></a>
									</font>
								</td>
								<td class="style5" style="width: 20%">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo Number_format($inv, 2) ?>
									</font>
								</td>
								<td class="style5" style="width: 20%">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo Number_format($amount2, 2) ?>
									</font>
								</td>
								<td class="style5" style="width: 20%">
									<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
										<?php echo Number_format($inv - $amount2, 2) ?>
									</font>
								</td>
						</tr>
					<?php
						}
					} ?>
				</table>
</body>

</html>