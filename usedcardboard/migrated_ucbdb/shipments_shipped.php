<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>

<html>

<head>
	<title>DASH - Pending Shipments</title>
</head>

<body>


	<?php

	$sql_table = "";
	$sql_query = "";
	$sql_table_warehouse = "";
	if (isset($_REQUEST["tbl"])) {
		if ($_REQUEST["tbl"] == "losangeles") {
			$sql_table_warehouse = " orders_active_ucb_los_angeles ";
			$sql_query = " orders_active_export.warehouse_id = 1 and ";
		}
		if ($_REQUEST["tbl"] == "huntvally") {
			$sql_table_warehouse = " orders_active_ucb_hunt_valley ";
			$sql_query = " orders_active_export.warehouse_id = 11 and ";
		}
		if ($_REQUEST["tbl"] == "hannibal") {
			$sql_table_warehouse = " orders_active_ucb_hannibal ";
			$sql_query = " orders_active_export.warehouse_id = 12 and ";
		}
		if ($_REQUEST["tbl"] == "saltlake") {
			$sql_table_warehouse = " orders_active_ucb_salt_lake ";
			$sql_query = " orders_active_export.warehouse_id = 3 and ";
		}
		if ($_REQUEST["tbl"] == "newitem") {
			$sql_table = " orders_sps ";
			$sql_query = " sent = 1 and ";
		} else {
			$sql_table = " orders_active_export ";
		}
	}
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	error_reporting(E_WARNING | E_PARSE);

	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= "shipments_shipped.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	$allowedit		= "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS

	$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.
	$addslash = "yes";
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	db();
	?>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<?php
		$proc = $_REQUEST['proc'];
		if ($proc == "") {
			if ($allowaddnew == "yes") {
				//echo "<a href=\"index.php\">Home</a><br><br>"; 
		?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php  }
			?>
			<br>
			<?php
			if ($_REQUEST['posting'] == "yes") {
				$pagenorecords = 500;  //THIS IS THE PAGE SIZE
				//IF NO PAGE
				$myrecstart = 0;
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
				if ($page == 0) {
					$myrecstart = 0;
				} else {
					$myrecstart = ($page * $pagenorecords);
				}
				$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit'] : "";
				$flag = "";
				$sqlwhere = "";
				$sqlcount = "";
				$sql = "";
				if ($searchcrit == "") {
					$flag = "all";
					$sql = "SELECT * FROM $sql_table ";
					$sqlcount = "select count(*) as reccount from $sql_table ";
				}

				$currentdate = new DateTime();
				//$prev_date = $currentdate->modify('-4 day');
				$prev_date = $currentdate;
				if ($flag == "all") {
					if ($_REQUEST["tbl"] == "newitem") {
						$sql = $sql . " inner join orders_active_export on orders_active_export.orders_id = orders_sps.orders_id WHERE $sql_query orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59' ORDER BY orders_active_export.orders_id ";
						$sqlcount = $sqlcount . " inner join orders_active_export on orders_active_export.orders_id = orders_sps.orders_id WHERE $sql_query orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59' ORDER BY orders_active_export.orders_id";
					} else {
						$sql = $sql . " WHERE $sql_query orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59' ORDER BY orders_active_export.orders_id $addl_select_crit LIMIT $myrecstart, $pagenorecords";
						$sqlcount = $sqlcount . " WHERE $sql_query orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					}
				}

				//SET PAGE
				if ($page == 0) {
					$page = 1;
				} else {
					$page = ($page + 1);
				}
				$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
				if ($reccount == 0) {
					$resultcount = (db_query($sqlcount));
					if ($myrowcount = array_shift($resultcount)) {
						$reccount = $myrowcount["reccount"];
					} //IF RECCOUNT = 0
				} //end if reccount
				if ($reccount > 500) {
					$ttlpages = ($reccount / 500);
					if ($page < $ttlpages) {
			?>

						<HR> <br>
					<?php
					} //END IF AT LAST PAGE
				} //END IF RECCOUNT > 10
				$newpage = 0;
				if ($page > 0) {
					$newpage = $page - 2;
				}
				if ($newpage != -1) {
					?>
					<br>
					<br>
					<?php

				} //IF NEWPAGE != -1
				$result = db_query($sql);
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				if ($myrowsel = array_shift($result)) {
					$id = $myrowsel["id"];
					echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
					echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";

					echo "<TABLE WIDTH='100%'>";
					if ($_REQUEST["fromdash"] == 'y') {
						echo "	<tr align='middle'><td colspan='12' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
					} else {
						echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
					}
					echo "	<TR>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Item</DIV></TD>";
					if ($_REQUEST["fromdash"] == 'y') {
					} else {
						echo "      <TD><DIV CLASS='TBL_COL_HDR'>Remove</DIV></TD>";
					}
					echo "\n\n		</TR>";

					$orders_id_tmp = "";
					do {
						//FORMAT THE OUTPUT OF THE SEARCH
						$id = $myrowsel["id"];
						//SWITCH ROW COLORS
						$shade = "TBL_ROW_DATA_LIGHT";
						switch ($shade) {
							case "TBL_ROW_DATA_LIGHT":
								$shade = "TBL_ROW_DATA_DRK";
								break;
							case "TBL_ROW_DATA_DRK":
								$shade = "TBL_ROW_DATA_LIGHT";
								break;
							default:
								$shade = "TBL_ROW_DATA_DRK";
								break;
						} //end switch shade
						echo "<form action='process_credit.php' method='post'>";
						echo "<TR>";
						$database_id = $myrowsel["id"];
						$orders_id = $myrowsel["orders_id"];
						$order_amount = 0;
						$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = '" . $orders_id . "'";
						$t_sql_1_res = db_query($t_sql_1);
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$order_amount = number_format($t_sql_1_row["value"], 2);
						}

						$date_purchased = "";
						$t_sql_1 = "SELECT date_purchased FROM orders WHERE orders_id = '" . $orders_id . "'";
						$t_sql_1_res = db_query($t_sql_1);
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$date_purchased = $t_sql_1_row["date_purchased"];
						}
						$customers_name = "";
						$customers_street_address = "";
						$customers_street_address2 = "";
						$customers_city = "";
						$customers_state = "";
						$customers_postcode = "";
						$customers_telephone = "";
						$customers_email_address = "";
						$item_name = "";
						if ($_REQUEST["tbl"] == "newitem") {
						} else {
							$t_sql_1 = "SELECT * from $sql_table_warehouse WHERE orders_id = '" . $orders_id . "' and module_name = '" . $myrowsel["module_name"] . "'";
							$t_sql_1_res = db_query($t_sql_1);
							while ($t_sql_1_row = array_shift($t_sql_1_res)) {
								$customers_name = $t_sql_1_row["shipping_name"];
								$customers_street_address = $t_sql_1_row["shipping_street1"];
								$customers_street_address2 = $t_sql_1_row["shipping_street2"];
								$customers_city = $t_sql_1_row["shipping_city"];
								$customers_state = $t_sql_1_row["shipping_state"];
								$customers_postcode = $t_sql_1_row["shipping_zip"];

								$customers_telephone = $t_sql_1_row["phone"];
								$customers_email_address = $t_sql_1_row["email"];
								$item_name = $t_sql_1_row["description"];
							}
						}

					?>

						<TD CLASS='<?php echo $shade; ?>'>
							<a target="_blank" href="orders.php?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($orders_id); ?>&proc=View"><?php echo $orders_id; ?></a>
						</TD>
						<TD CLASS='<?php echo $shade; ?>'>$<?php echo $order_amount; ?></td>

						<TD CLASS='<?php echo $shade; ?>'>
							<?php $order_date = date("F j, Y", strtotime($date_purchased));
							echo $order_date; ?>
						</td>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_name; ?>
						</TD>
						<?php  //$customers_street_address = $myrowsel["shipping_street1"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address; ?>
						</TD>
						<?php  //$customers_street_address2 = $myrowsel["shipping_street2"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address2; ?>
						</TD>
						<?php  //$customers_city = $myrowsel["shipping_city"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_city; ?>
						</TD>
						<?php  //$customers_state = $myrowsel["shipping_state"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_state; ?>
						</TD>
						<?php  //$customers_postcode = $myrowsel["shipping_zip"]; 
						?>
						<?php $billing_postcode = $myrowsel["bill_to_zip"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_postcode; ?>
						</TD>
						<?php  //$customers_telephone = $myrowsel["phone"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_telephone; ?>
						</TD>
						<?php  //$customers_email_address = $myrowsel["email"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_email_address; ?>
						</TD>
						<?php  //$item_name = $myrowsel["description"]; 
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $item_name . $myrowsel["order_string"]; ?>
						</TD>

						<?php if ($_REQUEST["fromdash"] == 'y') {
						} else { ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo $orders_id; ?>">Remove</a>
							</TD>
						<?php 	} ?>
						</TR>
						<?php

						$orders_id_tmp = $myrowsel["orders_id"];
					} while ($myrowsel = array_shift($result));

					if ($_REQUEST["tbl"] != "newitem") {
						echo "</TABLE>";
						echo "</form>";
					}
				} //END PROC == ""

				//For the SPS - Ubox orders
				if ($_REQUEST["tbl"] == "newitem") {
					$sql = "SELECT * FROM `ubox_order_fedex_details` WHERE ubox_order_fedex_details.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59' 
	and ubox_order_fedex_details.orders_id in (Select orders_id from orders_sps where sent = 1)";
					//echo $sql . "<br>";
					$result = db_query($sql);
					$reccount = tep_db_num_rows($result);
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					if ($myrowsel = array_shift($result)) {
						$id = $myrowsel["id"];
						echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";

						echo "<TABLE WIDTH='100%'>";
						if ($_REQUEST["fromdash"] == 'y') {
							echo "	<tr align='middle'><td colspan='12' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
						} else {
							echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
						}
						echo "	<TR>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Item</DIV></TD>";
						if ($_REQUEST["fromdash"] == 'y') {
						} else {
							echo "      <TD><DIV CLASS='TBL_COL_HDR'>Remove</DIV></TD>";
						}
						echo "\n\n		</TR>";
						$orders_id_tmp = "";
						do {
							//FORMAT THE OUTPUT OF THE SEARCH
							$id = $myrowsel["id"];

							//SWITCH ROW COLORS
							$shade = "TBL_ROW_DATA_LIGHT";
							switch ($shade) {
								case "TBL_ROW_DATA_LIGHT":
									$shade = "TBL_ROW_DATA_DRK";
									break;
								case "TBL_ROW_DATA_DRK":
									$shade = "TBL_ROW_DATA_LIGHT";
									break;
								default:
									$shade = "TBL_ROW_DATA_DRK";
									break;
							} //end switch shade

							echo "<TR>";
							$database_id = $myrowsel["id"];
							$orders_id = $myrowsel["orders_id"];

							$order_amount = 0;
							$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = '" . $orders_id . "'";
							$t_sql_1_res = db_query($t_sql_1);
							while ($t_sql_1_row = array_shift($t_sql_1_res)) {
								$order_amount = number_format($t_sql_1_row["value"], 2);
							}

							$date_purchased = "";
							$t_sql_1 = "SELECT date_purchased FROM orders WHERE orders_id = '" . $orders_id . "'";
							$t_sql_1_res = db_query($t_sql_1);
							while ($t_sql_1_row = array_shift($t_sql_1_res)) {
								$date_purchased = $t_sql_1_row["date_purchased"];
							}
							
						$customers_name = "";
						$customers_street_address = "";
						$customers_street_address2 = "";
						$customers_city = "";
						$customers_state = "";
						$customers_postcode = "";
						$customers_telephone = "";
						$customers_email_address = "";
							$t_sql_1 = "SELECT * FROM orders_sps WHERE orders_id = '" . $orders_id . "'";
							$t_sql_1_res = db_query($t_sql_1);
							while ($t_sql_1_row = array_shift($t_sql_1_res)) {

								$customers_name = $t_sql_1_row["shipping_name"];
								$customers_street_address = $t_sql_1_row["shipping_street1"];
								$customers_street_address2 = $t_sql_1_row["shipping_street2"];
								$customers_city = $t_sql_1_row["shipping_city"];
								$customers_state = $t_sql_1_row["shipping_state"];
								$customers_postcode = $t_sql_1_row["shipping_zip"];

								$customers_telephone = $t_sql_1_row["phone"];
								$customers_email_address = $t_sql_1_row["email"];
							}

							$item_name = $myrowsel["product_description"];

						?>

							<TD CLASS='<?php echo $shade; ?>'>
								<a target="_blank" href="orders.php?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($orders_id); ?>&proc=View"><?php echo $orders_id; ?></a>
							</TD>
							<TD CLASS='<?php echo $shade; ?>'>$<?php echo $order_amount; ?></td>

							<TD CLASS='<?php echo $shade; ?>'><?php $order_date = date("F j, Y", strtotime($date_purchased));
																echo $order_date; ?></td>
							<?php  //$customers_name = $myrowsel["shipping_name"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_name; ?>
							</TD>
							<?php  //$customers_street_address = $myrowsel["shipping_street1"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_street_address; ?>
							</TD>
							<?php  //$customers_street_address2 = $myrowsel["shipping_street2"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_street_address2; ?>
							</TD>
							<?php  //$customers_city = $myrowsel["shipping_city"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_city; ?>
							</TD>
							<?php  //$customers_state = $myrowsel["shipping_state"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_state; ?>
							</TD>
							<?php  //$customers_postcode = $myrowsel["shipping_zip"]; 
							?>
							<?php $billing_postcode = $myrowsel["bill_to_zip"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_postcode; ?>
							</TD>
							<?php  //$customers_telephone = $myrowsel["phone"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_telephone; ?>
							</TD>
							<?php  //$customers_email_address = $myrowsel["email"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_email_address; ?>
							</TD>
							<?php  //$item_name = $myrowsel["description"]; 
							?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $item_name; ?>
							</TD>

							<?php if ($_REQUEST["fromdash"] == 'y') {
							} else { ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo $orders_id; ?>">Remove</a>
								</TD>
							<?php 	} ?>
							</TR>
						<?php

							$orders_id_tmp = $myrowsel["orders_id"];
						} while ($myrowsel = array_shift($result));
						echo "</TABLE>";
						echo "</form>";
					}
				}
				//For the SPS - Ubox orders
				if ($reccount > 10) {
					//IF THERE ARE MORE THAN 10 RECORDS PAGING
					$ttlpages = ($reccount / 10);
					if ($page < $ttlpages) {
						?>

						<HR> <br>
						<A HREF="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&posting=yes&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next <?php echo $pagenorecords; ?> Records >></a> <br>

					<?php
					} //END IF AT LAST PAGE
				} //END IF RECCOUNT > 10

				//PREVIOUS RECORDS LINK
				if ($page > 0) {
					$newpage = $page - 2;
				}
				if ($newpage != -1) {
					?>

					<br>
					<A HREF="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">
						<< Previous <?php echo $pagenorecords; ?> Records</a>
							<br>
				<?php
				} //IF NEWPAGE != -1
			} //END IF POSTING = YES
		} // END IF PROC = ""
				?>
				<?php
				if ($proc == "Delete") {
				?>
					<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
					<?php
					?>
					<DIV CLASS='PAGE_STATUS'>Remove status Flag</DIV><br><br>
					<?php
					/*-- SECTION: 9995CONFIRM --*/
					if (!$_REQUEST['delete']) {
					?>
						<DIV CLASS='PAGE_OPTIONS'>
							Are you sure you want to mark the remove flag?<BR><br>
							<?php $orders_id = $_GET["orders_id"];	?>
							<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo $_GET['id']; ?>&delete=yes&proc=Delete&orders_id=<?php echo $orders_id; ?>&<?php echo $pagevars; ?>">Yes</a>
							<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&<?php echo $pagevars; ?>">No</a>
						</DIV>
				<?php
					} //IF !DELETE

					if ($_REQUEST['delete'] == "yes") {
						$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
						if ($_REQUEST["tbl"] == "newitem") {
							$sql = "UPDATE $sql_table SET sent = '2' WHERE id='$id' $addl_select_crit ";
						} else {
							$sql = "UPDATE $sql_table SET ship_status = 'X' WHERE id='$id' $addl_select_crit ";
						}
						//echo "<BR>SQL: $sql<BR>";
						$result = db_query($sql);
						if (empty($result)) {
							if (!headers_sent()) {    //If headers not sent yet... then do php redirect
								header('Location: http://b2c.usedcardboardboxes.com/shipments_shipped.php?posting=yes&');
								exit;
							} else {
								echo "<script type=\"text/javascript\">";
								echo "window.location.href=\"shipments_shipped.php?tbl=" . $_REQUEST["tbl"] . "&posting=yes&\";";
								echo "</script>";
								echo "<noscript>";
								echo "<meta http-equiv=\"refresh\" content=\"0;url=shipments_shipped.php?tbl=" . $_REQUEST["tbl"] . "&posting=yes&\" />";
								echo "</noscript>";
								exit;
							}
						} else {
							echo "Error Deleting Record (9995SQL)";
						}
					} //END IF $DELETE=YES
				} // END IF PROC = "DELETE"
				?>
				<BR>
				<BR>
				</Font>
	</div>
</body>

</html>