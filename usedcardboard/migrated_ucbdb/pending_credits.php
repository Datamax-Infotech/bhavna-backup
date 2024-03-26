<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Pending Credits</title>
</head>

<body>
	<?php
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	?>
	<div>
		<?php include("inc/header.php"); ?>

	</div>
	<div class="main_data_css">


		<?php
		echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
		echo "<Font Face='arial' size='2'>";
		$sql_debug_mode = 0;
		error_reporting(E_WARNING | E_PARSE);
		$thispage	= "pending_credits.php"; //SET THIS TO THE NAME OF THIS FILE
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
		$proc = $_REQUEST['proc'];
		db();
		if ($proc == "") {
			if ($allowaddnew == "yes") {
				//echo "<a href=\"index.php\">Home</a><br><br>"; 
		?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php  } ?>
			<br><br>
			<?php
			if ($_REQUEST['posting'] == "yes") {
				$pagenorecords = 50;  //THIS IS THE PAGE SIZE
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
				if ($searchcrit == "") {
					$flag = "all";
					$sql = "SELECT * FROM ucbdb_credits";
					$sqlcount = "select count(*) as reccount from ucbdb_credits";
				} else {
					//IF THEY TYPED SEARCH WORDS
					$sqlcount = "select count(*) as reccount from ucbdb_credits WHERE (";
					$sql = "SELECT * FROM ucbdb_credits WHERE (";
					$sqlwhere = "
 orders_id like '%$searchcrit%' OR 
 item_name like '%$searchcrit%' OR 
 warehouse like '%$searchcrit%' OR 
 reason_code like '%$searchcrit%' OR 
 quantity like '%$searchcrit%' OR 
 chargeback like '%$searchcrit%' OR 
 total like '%$searchcrit%' OR 
 employee like '%$searchcrit%' OR 
 pending like '%$searchcrit%' OR 
 credit_date like '%$searchcrit%' "; //FINISH SQL STRING

				} //END IF SEARCHCRIT = "";

				if ($flag == "all") {
					$sql = $sql . " WHERE (1=1) AND total > 0 AND pending = 'Pending' ORDER BY credit_date $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sqlcount = $sqlcount  . " WHERE (1=1) AND total > 0 AND pending = 'Pending' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				} else {
					$sqlcount = $sqlcount . $sqlwhere . ") total > 0 pending = 'Pending' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sql = $sql . $sqlwhere . ") AND total > 0 AND pending = 'Pending' ORDER BY credit_date $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				}
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
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
					<br>
					<!--	<A HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>"><< Previous <?php echo $pagenorecords; ?> Records</a>-->
					<br>
					<?php

				} //IF NEWPAGE != -1
				$result = db_query($sql);
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				if ($myrowsel = array_shift($result)) {
					$id = $myrowsel["id"];
					echo "<TABLE WIDTH='100%'>";
					echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>PENDING CREDITS</strong></td></tr>";
					echo "	<TR>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Item</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Reason</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Notes</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Quantity</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Chargeback</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Total</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Employee</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Pending?</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Credit Date</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Process</DIV></TD>";
					echo "<TD><DIV CLASS='TBL_COL_HDR'>Remove</DIV></TD>";
					echo "\n\n		</TR>";
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
						$auth_trans_id = $myrowsel["auth_trans_id"];
						$cc_number = $myrowsel["cc_number"];
						$cc_expires = $myrowsel["cc_expires"];
						$orders_id = $myrowsel["orders_id"];
						echo "<input type='hidden' name='auth_trans_id_" . $database_id . "' value=" . $auth_trans_id . ">";
						echo "<input type='hidden' name='cc_number_" . $database_id . "' value=" . $cc_number . ">";
						echo "<input type='hidden' name='cc_expires_" . $database_id . "' value=" . $cc_expires . ">";
						echo "<input type='hidden' name='orders_id_" . $database_id . "' value=" . $orders_id . ">";
					?>
						<TD CLASS='<?php echo $shade; ?>'>
							<a href="orders.php?id=<?php echo encrypt_url($orders_id); ?>&proc=View"><?php echo $orders_id; ?></a>
						</TD>
						<?php $item_name = $myrowsel["item_name"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $item_name; ?>
						</TD>
						<?php $warehouse = $myrowsel["warehouse"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $warehouse; ?>
						</TD>
						<?php $reason_code = $myrowsel["reason_code"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $reason_code; ?>
						</TD>
						<?php $notes = $myrowsel["notes"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $notes; ?>
						</TD>
						<?php $quantity = $myrowsel["quantity"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $quantity; ?>
						</TD>
						<?php $chargeback = $myrowsel["chargeback"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $chargeback; ?>
						</TD>
						<?php $total = $myrowsel["total"];
						echo "<input type='hidden' name='amount_" . $database_id . "' value=" . $total . ">"; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $total; ?>
						</TD>
						<?php $employee = $myrowsel["employee"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $employee; ?>
						</TD>
						<?php $pending = $myrowsel["pending"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $pending; ?>
						</TD>
						<?php $credit_date_load = $myrowsel["credit_date"];

						$credit_date = date("l, jS F Y", strtotime($credit_date_load));

						?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $credit_date; ?>
						</TD>
						<TD CLASS='<?php echo $shade; ?>'>
							<DIV CLASS='PAGE_OPTIONS'>
								<!--<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">Process</a>--> <input type="checkbox" name="process[]" value="<?php echo $database_id; ?>">
						</TD>
						<TD CLASS='<?php echo $shade; ?>'>


							<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
								<?php  } ?><?php if ($allowedit == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
								<?php  } ?><?php if ($allowdelete == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo encrypt_url($orders_id); ?>">Remove</a>
							<?php  } ?>
	</DIV>
	</TD>
	</TR>
	<?php
					} while ($myrowsel = array_shift($result));
					echo "</TABLE>";
					echo "<div align='center'><input type='submit' value='Process Credits'></div></form>";
					if ($reccount > 10) {
						//IF THERE ARE MORE THAN 10 RECORDS PAGING
						$ttlpages = ($reccount / 10);
						if ($page < $ttlpages) {
	?>

		<HR> <br>
		<A HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next <?php echo $pagenorecords; ?> Records >></a> <br>

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
	<A HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">
		<< Previous <?php echo $pagenorecords; ?> Records</a>
			<br>
<?php
					} //IF NEWPAGE != -1
				} //END PROC == ""
			} //END IF POSTING = YES
		} // END IF PROC = ""

?>
<?php
if ($proc == "New") {
?>
	<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
	<?php
	if ($allowaddnew == "yes") {
	?>
		<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
		<?php  } //END OF IF ALLOW ADDNEW 
	if ($proc == "New") {
		echo "<DIV CLASS='PAGE_STATUS'>Adding Record</DIV>";
		if ($_REQUEST['post'] == "yes") {
			$orders_id = FixString($_REQUEST['orders_id']);
			$item_name = FixString($_REQUEST['item_name']);
			$warehouse = FixString($_REQUEST['warehouse']);
			$reason_code = FixString($_REQUEST['reason_code']);
			$quantity = FixString($_REQUEST['quantity']);
			$chargeback = FixString($_REQUEST['chargeback']);
			$total = FixString($_REQUEST['total']);
			$employee = FixString($_REQUEST['employee']);
			$pending = FixString($_REQUEST['pending']);
			$credit_date = FixString($_REQUEST['credit_date']);
			$sql = "INSERT INTO ucbdb_credits ( orders_id,item_name,warehouse,reason_code,quantity,chargeback,total,employee,pending,credit_date $addl_insert_crit ) 
	VALUES ( '$orders_id','$item_name','$warehouse','$reason_code','$quantity','$chargeback','$total','$employee','$pending','$credit_date'$addl_insert_values )";
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			$result = db_query($sql);
			if (empty($result)) {
				echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
			} else {
				echo "Error inserting record (9994SQL)";
			}
		} //END IF POST = YES FOR ADDING NEW RECORDS
		if (!$_REQUEST['post']) {
		?>
			<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
				<TABLE ALIGN='LEFT'>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>ORDERS_ID:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="orders_id" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>ITEM_NAME:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="item_name" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>WAREHOUSE:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>REASON_CODE:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="reason_code" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>QUANTITY:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="quantity" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>CHARGEBACK:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="chargeback" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>TOTAL:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="total" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>EMPLOYEE:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="employee" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>PENDING:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="pending" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>
							<B>CREDIT_DATE:</B>
						</TD>
						<TD>
							<INPUT CLASS='TXT_BOX' type="text" NAME="credit_date" SIZE="20">
						</TD>
					</TR>
					<TR>
						<TD>
						</TD>
						<TD>
							<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
							<INPUT CLASS='BUTTON' TYPE="RESET" VALUE="RESET" NAME="RESET">
						</TD>
					</TR>
				</TABLE>
				<BR>
			</FORM>

<?php
		} //END if post=""
	} //END PROC == NEW
} // END IF PROC = "NEW"
?>
<?php
if ($proc == "Edit") {
	$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
?>
	<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
	<?php
	if ($allowaddnew == "yes") {
	?>
		<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
	<?php  } //END OF IF ALLOW ADDNEW 
	?>
	<DIV CLASS='PAGE_OPTIONS'>
		<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
			<?php  } //END ALLOWVIEW 
			?><?php if ($allowedit == "yes") { ?>
			<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
			<?php  } //END ALLOWEDIT 
			?><?php if ($allowdelete == "yes") { ?>
			<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
		<?php  } //END ALLOWDELETE 
		?></font>
		<font face="arial" size="2">
	</DIV>
	<?php

	if ($proc == "Edit") {
		if ($_REQUEST['post'] == "yes") {
			$id = $_REQUEST['id'];
			$orders_id = FixString($_REQUEST['orders_id']);
			$item_name = FixString($_REQUEST['item_name']);
			$warehouse = FixString($_REQUEST['warehouse']);
			$reason_code = FixString($_REQUEST['reason_code']);
			$quantity = FixString($_REQUEST['quantity']);
			$chargeback = FixString($_REQUEST['chargeback']);
			$total = FixString($_REQUEST['total']);
			$employee = FixString($_REQUEST['employee']);
			$pending = FixString($_REQUEST['pending']);
			$credit_date = FixString($_REQUEST['credit_date']);
			$sql = "UPDATE ucbdb_credits SET orders_id='$orders_id', item_name='$item_name', warehouse='$warehouse', reason_code='$reason_code', quantity='$quantity',
 			chargeback='$chargeback', total='$total', employee='$employee', pending='$pending', credit_date='$credit_date $addl_update_crit WHERE (id='$id') $addl_select_crit ";
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			$result = db_query($sql);
			if (empty($result)) {
				echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
			} else {
				echo "Error Updating Record (9993SQLUPD)";
			}
		} //END IF POST IS YES
		if ($_REQUEST['post'] == "") { //THEN WE ARE EDITING A RECORD
			echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
			$id =  isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
			$sql = "SELECT * FROM ucbdb_credits WHERE (id = '$id') $addl_select_crit ";
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			$result = db_query($sql);
			if ($myrow = array_shift($result)) {
				do {
					$orders_id = $myrow["orders_id"];
					$orders_id = preg_replace("/(\n)/", "<br>", $orders_id);
					$item_name = $myrow["item_name"];
					$item_name = preg_replace("/(\n)/", "<br>", $item_name);
					$warehouse = $myrow["warehouse"];
					$warehouse = preg_replace("/(\n)/", "<br>", $warehouse);
					$reason_code = $myrow["reason_code"];
					$reason_code = preg_replace("/(\n)/", "<br>", $reason_code);
					$quantity = $myrow["quantity"];
					$quantity = preg_replace("/(\n)/", "<br>", $quantity);
					$chargeback = $myrow["chargeback"];
					$chargeback = preg_replace("/(\n)/", "<br>", $chargeback);
					$total = $myrow["total"];
					$total = preg_replace("/(\n)/", "<br>", $total);
					$employee = $myrow["employee"];
					$employee = preg_replace("/(\n)/", "<br>", $employee);
					$pending = $myrow["pending"];
					$pending = preg_replace("/(\n)/", "<br>", $pending);
					$credit_date = $myrow["credit_date"];
					$credit_date = preg_replace("/(\n)/", "<br>", $credit_date);
	?>
					<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
						<br>
						<TABLE ALIGN='LEFT'>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									ORDERS_ID:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='orders_id' value='<?php echo $orders_id; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									ITEM_NAME:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='item_name' value='<?php echo $item_name; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									WAREHOUSE:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse' value='<?php echo $warehouse; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									REASON_CODE:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='reason_code' value='<?php echo $reason_code; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									QUANTITY:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='quantity' value='<?php echo $quantity; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									CHARGEBACK:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='chargeback' value='<?php echo $chargeback; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									TOTAL:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='total' value='<?php echo $total; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									EMPLOYEE:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='employee' value='<?php echo $employee; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									PENDING:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='pending' value='<?php echo $pending; ?>' size='20'>
								</td>
							</tr>
							<TR>
								<TD CLASS='TBL_ROW_HDR'>
									CREDIT_DATE:
								</TD>
								<TD>
									<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='credit_date' value='<?php echo $credit_date; ?>' size='20'>
								</td>
							</tr>
							</td>
							</tr>
							<tr>
								<td>
								</td>
								<td>
									<?php $id = $myrow["id"]; ?>
									<input type="hidden" value="<?php echo $id; ?>" name="id">
									<input CLASS="BUTTON" type="submit" value="Save" name="submit">
								</td>
							</tr>
						</table>
						<BR>
					</form>
<?php
				} while ($myrow = array_shift($result));
			} //END IF RESULTS
		} //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
	} //END PROC == EDIT
} // END IF PROC = "EDIT"
?>
<?php
if ($proc == "View") {
?>
	<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
	<?php
	if ($allowaddnew == "yes") {
	?>
		<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
		<?php  }
	if ($proc == "View") {
		echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
		$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
		$sql = "SELECT * FROM ucbdb_credits WHERE id='$id' $addl_select_crit ";
		if ($sql_debug_mode == 1) {
			echo "<BR>SQL: $sql<BR>";
		}
		$result = db_query($sql);
		if ($myrowsel = array_shift($result)) {
			do {
				$id = $myrowsel["id"];
				$orders_id = $myrowsel["orders_id"];
				$item_name = $myrowsel["item_name"];
				$warehouse = $myrowsel["warehouse"];
				$reason_code = $myrowsel["reason_code"];
				$quantity = $myrowsel["quantity"];
				$chargeback = $myrowsel["chargeback"];
				$total = $myrowsel["total"];
				$employee = $myrowsel["employee"];
				$pending = $myrowsel["pending"];
				$credit_date = $myrowsel["credit_date"];
		?>
				<TABLE>
					<DIV CLASS='PAGE_OPTIONS'>
						<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
							<?php  } //END ALLOWVIEW 
							?><?php if ($allowedit == "yes") { ?>
							<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
							<?php  } //END ALLOWEDIT 
							?><?php if ($allowdelete == "yes") { ?>
							<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
						<?php  } //END ALLOWDELETE 
						?><br></font>
						<font face="arial" size="2">
					</DIV>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>ORDERS_ID:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $orders_id; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>ITEM_NAME:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $item_name; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>WAREHOUSE:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>REASON_CODE:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $reason_code; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>QUANTITY:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $quantity; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>CHARGEBACK:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $chargeback; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>TOTAL:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $total; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>EMPLOYEE:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $employee; ?> </DIV>
						</TD>
					<TR>
						<TD CLASS='TBL_ROW_HDR'>PENDING:</TD>
						<TD>
							<DIV CLASS='TBL_ROW_DATA'><?php echo $pending; ?> </DIV>
						</TD>
		<?php
			} while ($myrowsel = array_shift($result));
			echo "</TR>\n</TABLE>";
		} //IF RESULT

	} //END OF PROC VIEW
} // END IF PROC = "VIEW"
		?>
		<?php
		if ($proc == "Delete") {
		?>
			<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
			<?php

			if ($allowaddnew == "yes") {
			?>

				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php  } //END OF IF ALLOW ADDNEW 
			?>
			<DIV CLASS='PAGE_STATUS'>Deny Credit</DIV><br><br>
			<?php

			$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
			if (!$_REQUEST['delete']) {
			?>
				<DIV CLASS='PAGE_OPTIONS'>
					Are you sure you want to Deny This Credit?<BR><br>
					<?php $orders_id = isset($_GET['orders_id']) ? decrypt_url($_GET["orders_id"]) : "";	?>
					<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&orders_id=<?php echo encrypt_url($orders_id); ?>&<?php echo $pagevars; ?>">Yes</a>
					<a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
				</DIV>
		<?php
			} //IF !DELETE
			if ($_REQUEST['delete'] == "yes") {
				$sql = "UPDATE ucbdb_credits SET pending = 'Denied' WHERE id='$id' $addl_select_crit ";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$result = db_query($sql);

				$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
				$res_commqryrw = db_query($commqry);
				$commqryrw = array_shift($res_commqryrw);
				$comm_type = $commqryrw["id"];
				$orders_id = $_GET["orders_id"];

				$output10 = "Credit Denied";
				$today = date("Ymd");
				$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $output10 . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
				$result3 = db_query($sql3);
				if (empty($result)) {
					if (!headers_sent()) {    //If headers not sent yet... then do php redirect
						header('Location: http://b2c.usedcardboardboxes.com/pending_credits.php?posting=yes&');
						exit;
					} else {
						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"pending_credits.php?posting=yes&\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=pending_credits.php?posting=yes&\" />";
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