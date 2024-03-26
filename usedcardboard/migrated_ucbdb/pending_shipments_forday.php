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
			$sql_table = " orders_active_ucb_los_angeles inner join orders on orders.orders_id = orders_active_ucb_los_angeles.orders_id ";
			$sql_query = " orders.date_purchased BETWEEN '" . date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and ";
		}
		if ($_REQUEST["tbl"] == "huntvally") {
			$sql_table = " orders_active_ucb_hunt_valley inner join orders on orders.orders_id = orders_active_ucb_hunt_valley.orders_id ";
			$sql_query = " orders.date_purchased BETWEEN '" . date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and ";
		}
		if ($_REQUEST["tbl"] == "hannibal") {
			$sql_table = " orders_active_ucb_hannibal inner join orders on orders.orders_id = orders_active_ucb_hannibal.orders_id ";
			$sql_query = " orders.date_purchased BETWEEN '" . date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and ";
		}
		if ($_REQUEST["tbl"] == "saltlake") {
			$sql_table = " orders_active_ucb_salt_lake inner join orders on orders.orders_id = orders_active_ucb_salt_lake.orders_id ";
			$sql_query = " orders.date_purchased BETWEEN '" . date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and ";
		}
		if ($_REQUEST["tbl"] == "newitem") {
			$sql_table = " orders_sps inner join orders on orders.orders_id = orders_sps.orders_id ";
			$sql_query = " orders.date_purchased BETWEEN '" . date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and ";
		}
	}
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	error_reporting(E_WARNING | E_PARSE);
	$thispage	= "pending_shipments_forday.php"; //SET THIS TO THE NAME OF THIS FILE
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
	$proc = isset($_REQUEST['proc']) ? $_REQUEST['proc'] : "";
	db();
	if ($proc == "") {
		if ($allowaddnew == "yes") {
			echo "<a href=\"index.php\">Home</a><br><br>";
	?>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
		<?php  }
		echo "<a href=\"index.php\">Home</a><br><br>";
		?>
		<?php
		if ($_REQUEST['posting'] == "yes") {
			$pagenorecords = 50;  //THIS IS THE PAGE SIZE
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
			if ($searchcrit == "") {
				$flag = "all";
				$sql = "SELECT * FROM $sql_table";
				$sqlcount = "select count(*) as reccount from $sql_table";
			} else {
				//IF THEY TYPED SEARCH WORDS
				$sqlcount = "select count(*) as reccount from $sql_table WHERE $sql_query (";
				$sql = "SELECT * FROM $sql_table WHERE $sql_query (";
				$sqlwhere = " orders_id like '%$searchcrit%' OR 
				item_name like '%$searchcrit%' OR 
				warehouse like '%$searchcrit%' OR 
				reason_code like '%$searchcrit%' OR 
				quantity like '%$searchcrit%' OR 
				chargeback like '%$searchcrit%' OR 
				total like '%$searchcrit%' OR 
				employee like '%$searchcrit%' OR 
				pending like '%$searchcrit%' OR 
				credit_date like '%$searchcrit%' 
				"; //FINISH SQL STRING
			} //END IF SEARCHCRIT = "";

			if ($flag == "all") {
				$sql = $sql . " WHERE $sql_query (1=1)  ORDER BY id $addl_select_crit ";
				$sqlcount = $sqlcount  . " WHERE $sql_query (1=1) $addl_select_crit ";
			} else {
				$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit ";
				$sql = $sql . $sqlwhere . ")  ORDER BY id$addl_select_crit ";
			}

			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
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

			echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
			echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
			if ($reccount > 10) {
				$ttlpages = ($reccount / 10);
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
				<br><br>
				<?php

			} //IF NEWPAGE != -1
			//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
			$result = db_query($sql);
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			if ($myrowsel = array_shift($result)) {
				$id = $myrowsel["id"];
				echo "<TABLE WIDTH='100%'>";
				echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
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
				echo "<TD><DIV CLASS='TBL_COL_HDR'>Remove</DIV></TD>";
				echo "\n\n	</TR>";
				do {
					$id = $myrowsel["id"];
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
					$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $orders_id;
					$t_sql_1_res = db_query($t_sql_1);
					while ($t_sql_1_row = array_shift($t_sql_1_res)) {
						$order_amount = number_format($t_sql_1_row["value"], 2);
					}
					$date_purchased = "";
					$t_sql_1 = "SELECT date_purchased FROM orders WHERE orders_id = " . $orders_id;
					$t_sql_1_res = db_query($t_sql_1);
					while ($t_sql_1_row = array_shift($t_sql_1_res)) {
						$date_purchased = $t_sql_1_row["date_purchased"];
					}
				?>
					<TD CLASS='<?php echo $shade; ?>'>
						<a href="orders.php?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($orders_id); ?>&proc=View"><?php echo $orders_id; ?></a>
					</TD>
					<TD CLASS='<?php echo $shade; ?>'>$<?php echo $order_amount; ?></td>

					<TD CLASS='<?php echo $shade; ?>'><?php $order_date = date("F j, Y", strtotime($date_purchased));
														echo $order_date; ?></td>
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

					<TD CLASS='<?php echo $shade; ?>'>
						<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo $orders_id; ?>">Remove</a>
					</TD>
					</TR>
					<?php
				} while ($myrowsel = array_shift($result));
				echo "</TABLE>";
				echo "</form>";
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
				$newpage = 0;
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
			} //END PROC == ""
		}
	} // END IF PROC = ""
			?>

			<?php
			if ($proc == "Delete") {
				$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
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
						<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&orders_id=<?php echo $orders_id; ?>&<?php echo $pagevars; ?>">Yes</a>
						<a href="<?php echo $thispage; ?>?tbl=<?php echo $_REQUEST["tbl"]; ?>&<?php echo $pagevars; ?>">No</a>
					</DIV>
			<?php
				} //IF !DELETE

				if ($_REQUEST['delete'] == "yes") {
					if ($_REQUEST["tbl"] == "newitem") {
						$sql = "UPDATE $sql_table SET sent = '2' WHERE id='$id' $addl_select_crit ";
					} else {
						$sql = "UPDATE $sql_table SET ship_status = 'X' WHERE id='$id' $addl_select_crit ";
					}
					//echo "<BR>SQL: $sql<BR>";
					$result = db_query($sql);
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
			} // END IF PROC = "DELETE"
			?>
			<BR>

			<BR>
			</Font>
</body>

</html>