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
	if (isset($_REQUEST["tbl"])) {
		if ($_REQUEST["tbl"] == "losangeles") {
			$sql_table = " orders_active_ucb_los_angeles ";
			$sql_query = " ship_status LIKE 'N' and ";
		}
		if ($_REQUEST["tbl"] == "huntvally") {
			$sql_table = " orders_active_ucb_hunt_valley ";
			$sql_query = " ship_status LIKE 'N' and ";
		}
		if ($_REQUEST["tbl"] == "hannibal") {
			$sql_table = " orders_active_ucb_hannibal ";
			$sql_query = " ship_status LIKE 'N' and ";
		}
		if ($_REQUEST["tbl"] == "saltlake") {
			$sql_table = " orders_active_ucb_salt_lake ";
			$sql_query = " ship_status LIKE 'N' and ";
		}
		if ($_REQUEST["tbl"] == "newitem") {
			$sql_table = " orders_sps ";
			$sql_query = " sent = 0 and ";
		}
	}

	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	error_reporting(E_WARNING | E_PARSE);

	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage = "pending_shipments.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars = ""; //INSERT ANY "GET" VARIABLES HERE...
	
	$allowedit = "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew = "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview = "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete = "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
	$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.
	$addslash = "yes";
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	?>
	<div>
		<?php
		include("inc/header.php");
		?>

	</div>
	<div class="main_data_css">
		<?php
		/*----------------------------------------
			  ADD NEW LINK
			  ----------------------------------------*/
		$proc = isset($_REQUEST['proc']) ? $_REQUEST['proc'] : '';
		if ($proc == "") {
			if ($allowaddnew == "yes") {
				// echo "<a href=\"index.php\">Home</a><br><br>"; 
				?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php }
			//echo "<a href=\"index.php\">Home</a><br><br>";
		

			/*---------------------------------------------------------------------------------------
					 BEGIN SEARCH SECTION 9991
					 ---------------------------------------------------------------------------------------*/
			/*-- SECTION: 9991FORM --*/
			?>

			<br>
			<?php

			/*----------------------------------------------------------------
					 IF THEY ARE POSTING TO THE SEARCH PAGE 
					 (SHOW SEARCH RESULTS)
					 ----------------------------------------------------------------*/
			$posting = isset($_REQUEST['posting']) ? $_REQUEST['posting'] : '';
			if ($posting == "yes") {
				/*-- SECTION: 9991SQL --*/
				$flag = "all";
				$sql = "SELECT * FROM $sql_table";
				$sqlcount = "select count(*) as reccount from $sql_table";
				$sql = $sql . " WHERE $sql_query (1=1)  ORDER BY id $addl_select_crit ";
				$sqlcount = $sqlcount . " WHERE $sql_query (1=1) $addl_select_crit ";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$resultcount = (db_query($sqlcount, db())) or DIE(ThrowError("9991SQLresultcount", $sqlcount));
				if ($myrowcount = array_shift($resultcount)) {
					$reccount = $myrowcount["reccount"];
				}
				echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
				
/*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
				//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
				$result = db_query($sql, db());
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				if ($myrowsel = array_shift($result)) {
					$id = $myrowsel["id"];

					echo "<TABLE WIDTH='100%'>";
					if (isset($_REQUEST["fromdash"]) && $_REQUEST["fromdash"] == 'y') {
						echo "	<tr align='middle'><td colspan='14' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
					} else {
						echo "	<tr align='middle'><td colspan='15' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
					}
					echo "	<TR>";

					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Move</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Send To UBox</DIV></TD>";
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
					if (isset($_REQUEST["fromdash"]) && $_REQUEST["fromdash"] == 'y') {
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
						}//end switch shade
		
						/*----------------------------------------------------------------
										  VIEW SEARCH RESULTS BY PAGE
										  ----------------------------------------------------------------*/

						/*---------------------------------------
										  BEGIN RESULTS TABLE
										  ---------------------------------------*/
						echo "<form action='process_credit.php' method='post'>";
						echo "<TR>";
						$database_id = $myrowsel["id"];
						$orders_id = $myrowsel["orders_id"];

						$order_amount = 0;
						$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $orders_id;
						$t_sql_1_res = db_query($t_sql_1, db());
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$order_amount = number_format($t_sql_1_row["value"], 2);
						}

						$date_purchased = "";
						$t_sql_1 = "SELECT date_purchased FROM orders WHERE orders_id = " . $orders_id;
						$t_sql_1_res = db_query($t_sql_1, db());
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$date_purchased = $t_sql_1_row["date_purchased"];
						}



						/*-------------------------------------------------------------------------------
										  Addition Start check box show if order not found in tracking 
										  -------------------------------------------------------------------------------*/
						$rec_found_in_ord_trk = "no";
						$orders_tracking_chk = db_query("Select * from orders_active_export where orders_id = " . $orders_id);
						while ($orders_tracking_row = array_shift($orders_tracking_chk)) {
							$rec_found_in_ord_trk = "yes";
						}
						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php if ($rec_found_in_ord_trk == "no") { ?>
								<input type="checkbox" name="warehouserowid[]" value="<?php echo $id; ?>">
							<?php } ?>
						</TD>
						<?php
						/*-------------------------------------------------------------------------------
										  Addition end by Amarendra dated 20-03-2021 
										  -------------------------------------------------------------------------------*/
						?>

						<TD CLASS='<?php echo $shade; ?>'>
							<a target="_blank"
								href="orders.php?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo $orders_id; ?>&proc=View">
								<?php echo $orders_id; ?>
							</a>
						</TD>

						<?php if ($orders_id_tmp != $orders_id) { ?>
							<td class='<?php echo $shade; ?>'><a href="send_orders.php?ordersid=<?php echo $orders_id; ?>">Send to UBox</a>
							</td>
						<?php } else { ?>
							<td class='<?php echo $shade; ?>'>&nbsp;</td>
						<?php } ?>

						<TD CLASS='<?php echo $shade; ?>'>$
							<?php echo $order_amount; ?>
						</td>

						<TD CLASS='<?php echo $shade; ?>'>
							<?php $order_date = date("F j, Y", strtotime($date_purchased));
							echo $order_date; ?>
						</td>
						<?php $customers_name = $myrowsel["shipping_name"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_name; ?>
						</TD>
						<?php $customers_street_address = $myrowsel["shipping_street1"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address; ?>
						</TD>
						<?php $customers_street_address2 = $myrowsel["shipping_street2"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address2; ?>
						</TD>
						<?php $customers_city = $myrowsel["shipping_city"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_city; ?>
						</TD>
						<?php $customers_state = $myrowsel["shipping_state"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_state; ?>
						</TD>
						<?php $customers_postcode = $myrowsel["shipping_zip"]; ?>
						<?php $billing_postcode = $myrowsel["bill_to_zip"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_postcode; ?>
						</TD>
						<?php $customers_telephone = $myrowsel["phone"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_telephone; ?>
						</TD>
						<?php $customers_email_address = $myrowsel["email"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_email_address; ?>
						</TD>
						<?php $item_name = $myrowsel["description"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $item_name . $myrowsel["order_string"]; ?>
						</TD>

						<?php if (isset($_REQUEST["fromdash"]) && $_REQUEST["fromdash"] == 'y') { ?>

						<?php } else { ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<a
									href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo $orders_id; ?>">Remove</a>
							</TD>
						<?php } ?>
						</TR>
						<?php

						$orders_id_tmp = $myrowsel["orders_id"];
					} while ($myrowsel = array_shift($result));

					//echo "</TABLE>";			
					echo "</form>";
					/*-------------------------------------------------------------------------------
								   Addition Start Last Row to add select all oprtions 
								   -------------------------------------------------------------------------------*/
					$TBL_COL_HDR = "";
					echo '<TR><TD CLASS="' . $TBL_COL_HDR . '">';
					echo '<input type="checkbox" name="selectall" onchange="javascript:checkallrecords(this);" >';
					echo '</TD><TD CLASS="' . $TBL_COL_HDR . '" colspan="2" style="font-size:11px;"> Select All Records </TD>';
					echo '<input type="hidden" name="movetablefrom" id="movetablefrom" value="' . trim($sql_table) . '" />';
					echo '<TD CLASS="' . $TBL_COL_HDR . '" colspan="2"><Select name="warehouse2move" id="warehouse2move" >';
					echo '<option value=0>Please Select Warehouse</option>';
					$warehouse = "SELECT * FROM ucbdb_warehouse";
					$resw2 = db_query($warehouse, db());
					while ($wrow2 = array_shift($resw2)) {
						echo '<option value="' . $wrow2['id'] . '">' . $wrow2['distribution_center'] . '</option>';
					}
					echo '</select></TD><TD CLASS="' . $TBL_COL_HDR . '" colspan="2">';
					echo '<input type="button" name="move_record" id="move_record" ';
					echo 'onclick="javascript: move_warehouse_entry(); return false;" value="Move Warehouse" />';
					echo '</TD><TD CLASS="' . $TBL_COL_HDR . '" colspan="8"></TD></TR>';
					echo "</TABLE>";
					?>
					<script>

						function select_warehouse_entry() {
							var checkboxes2 = document.getElementsByName("warehouserowid[]");
							var vals = "";
							for (var i = 0, n = checkboxes2.length; i < n; i++) {
								if (checkboxes2[i].checked) {
									vals += "," + checkboxes2[i].value;
								}
							}
							vals = vals.slice(1);
							return vals;
						}


						function checkallrecords(ele) {
							var checkboxes3 = document.getElementsByName("warehouserowid[]");
							if (ele.checked) {
								for (var i = 0; i < checkboxes3.length; i++) {
									if (checkboxes3[i].type == 'checkbox') {
										checkboxes3[i].checked = true;
									}
								}

							}
							else {
								for (var i = 0; i < checkboxes3.length; i++) {
									if (checkboxes3[i].type == 'checkbox') {
										checkboxes3[i].checked = false;
									}
								}

							}

						}

						function Uncheckwarehouse() {
							var checkboxes4 = document.getElementsByName("warehouserowid[]");
							for (var i = 0; i < checkboxes4.length; i++) {
								if (checkboxes4[i].type == 'checkbox') {
									checkboxes4[i].checked = false;
								}
							}
						}


						function move_warehouse_entry() {
							var msgtxt = prompt('Enter the password.');
							if (msgtxt == "boomerang") {
								var rowids = select_warehouse_entry();
								var tableid = document.getElementById("warehouse2move").value;
								var tblfrm = document.getElementById("movetablefrom").value;

								if (window.XMLHttpRequest) {
									xmlhttp = new XMLHttpRequest();
								}
								else {
									xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
								}
								xmlhttp.onreadystatechange = function () {
									if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
										Uncheckwarehouse();
										location.reload();
									}
								}

								//				alert("move_pending_shipping_entry.php?tbl=" + tblfrm + "&tableid=" + tableid + "&rowid=" + rowids);

								xmlhttp.open("GET", "move_pending_shipping_entry.php?tbl=" + tblfrm + "&tableid=" + tableid + "&rowid=" + rowids, true);
								xmlhttp.send();
							} else {
								alert("Password incorrect.");
							}
						}
					</script>
					<hr>
					<?php
				} //END PROC == ""
			} //END IF POSTING = YES
/*---------------------------------------------------------------------------------
END SEARCH SECTION 9991
---------------------------------------------------------------------------------*/

		}// END IF PROC = ""
		
		?>

		<?php
		if ($proc == "Delete") {
			$id = $_REQUEST['id'];
			?>
			<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
			<?php

			/*-------------------------------------------------------------------------------
					 DELETE RECORD SECTION 9995
					 -------------------------------------------------------------------------------*/
			?>
			<DIV CLASS='PAGE_STATUS'>Remove status Flag</DIV><br><br>
			<?php
			/*-- SECTION: 9995CONFIRM --*/
			$delete = $_REQUEST['delete'];
			if (!$delete) {
				?>
				<DIV CLASS='PAGE_OPTIONS'>
					Are you sure you want to mark the remove flag?<BR><br>
					<?php $orders_id = $_GET["orders_id"]; ?>
					<a
						href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo $id; ?>&delete=yes&proc=Delete&orders_id=<?php echo $orders_id; ?>&<?php echo $pagevars; ?>">Yes</a>
					<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&<?php echo $pagevars; ?>">No</a>
				</DIV>
				<?php
			} //IF !DELETE
		
			if ($delete == "yes") {

				/*-- SECTION: 9995SQL --*/
				if ($_REQUEST["tbl"] == "newitem") {
					$sql = "UPDATE $sql_table SET sent = '2' WHERE id='$id' $addl_select_crit ";
				} else {
					$sql = "UPDATE $sql_table SET ship_status = 'X' WHERE id='$id' $addl_select_crit ";
				}
				//echo "<BR>SQL: $sql<BR>";
				$result = db_query($sql, db());


				if (empty($result)) {
					if (!headers_sent()) {    //If headers not sent yet... then do php redirect
						header('Location: http://b2c.usedcardboardboxes.com/pending_shipments.php?posting=yes&');
						exit;
					} else {
						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"pending_shipments.php?tbl=" . $_REQUEST["tbl"] . "&posting=yes&\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=pending_shipments.php?tbl=" . $_REQUEST["tbl"] . "&posting=yes&\" />";
						echo "</noscript>";
						exit;
					}

				} else {
					echo "Error Deleting Record (9995SQL)";
				}
			} //END IF $DELETE=YES
/*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
		}// END IF PROC = "DELETE"
		



		?>
		<BR>

		<BR>
		</Font>


	</div>

</body>

</html>