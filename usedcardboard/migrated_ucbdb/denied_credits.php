<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Denied Credits</title>
</head>

<body>
	<?php
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	db();
	?>
	<div>
		<?php
		include("inc/header.php");
		?>
	</div>
	<div class="main_data_css">
		<?php
		echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
		echo "<Font Face='arial' size='2'>";
		$sql_debug_mode = 0;
		error_reporting(E_WARNING | E_PARSE);
		//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
		$thispage	= 'denied_credits.php'; //SET THIS TO THE NAME OF THIS FILE
		$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
		/*---------------- NOTE: THE FOLLOWING 4 "allow" VARIABLES SIMPLY DISABLE THE LINKS FOR ADD/EDIT/VIEW/DELETE.---------*/
		$allowedit		= "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
		$allowaddnew	= "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
		$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
		$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
		$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
		$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
		$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
		$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.
		$addslash = "yes";
		$proc = isset($_GET['proc']) ? $_GET['proc'] : "";
		/*------------- ADD NEW LINK ----------------------*/
		if ($proc == "") {
			if ($allowaddnew == "yes") {
		?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php
			}
			/*--------------------------- BEGIN SEARCH SECTION 9991 -------------------*/
			/*-- SECTION: 9991FORM --*/
			?>
			<br>
			<?php
			$posting = isset($_GET["posting"]) ? $_GET["posting"] : "";
			$page = isset($_GET["page"]) ? $_GET["page"] : "";
			/*---------- IF THEY ARE POSTING TO THE SEARCH PAGE (SHOW SEARCH RESULTS) ---*/
			if ($posting == "yes") {
				/*---------- PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---*/
				$pagenorecords = 20;  //THIS IS THE PAGE SIZE
				//IF NO PAGE
				if ($page == 0) {
					$myrecstart = 0;
				} else {
					$myrecstart = ($page * $pagenorecords);
				}
				/*-- SECTION: 9991SQL --*/
				$flag = "";
				$searchcrit = isset($_GET["searchcrit"]) ? $_GET["searchcrit"] : "";
				if ($searchcrit == "") {
					$flag = "all";
					$sql = "SELECT * FROM ucbdb_credits";
					$sqlcount = "select count(*) as reccount from ucbdb_credits";
				} else {
					//IF THEY TYPED SEARCH WORDS
					$sqlcount = "select count(*) as reccount from ucbdb_credits WHERE (";
					$sql = "SELECT * FROM ucbdb_credits WHERE (";
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

					//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
					//IF YOU LEFT THE LAST 
					//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
					//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

				} //END IF SEARCHCRIT = "";
				$sqlwhere = "";
				if ($flag == "all") {
					$sql = $sql . " WHERE (1=1) AND total > 0 AND pending = 'Denied' ORDER BY credit_date $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sqlcount = $sqlcount  . " WHERE (1=1) AND total > 0 AND pending = 'Denied' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				} else {
					$sqlcount = $sqlcount . $sqlwhere . ") total > 0 pending = 'Denied' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sql = $sql . $sqlwhere . ") AND total > 0 AND pending = 'Denied' ORDER BY credit_date$addl_select_crit LIMIT $myrecstart, $pagenorecords";
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
				/*---------- FIND OUT HOW MANY RECORDS WE HAVE ---*/
				$reccount = isset($_GET["reccount"]) ? $_GET["reccount"] : 0;
				if ($reccount == 0) {
					//$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError($err_type,$err_descr););
					$resultcount = (db_query($sqlcount)) or die("SQL Error");
					if ($myrowcount = array_shift($resultcount)) {
						$reccount = $myrowcount["reccount"];
					} //IF RECCOUNT = 0
				} //end if reccount
				echo "<DIV class='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
				echo "<DIV class='CURR_PAGE'>Page $page</DIV>";
				/*---------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---*/
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
					<br>
					<?php

				} //IF NEWPAGE != -1
				/*---------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---*/
				//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
				$result = db_query($sql);
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				if ($myrowsel = array_shift($result)) {
					$id = $myrowsel["id"];
					echo "<table WIDTH='100%'>";
					echo "	<tr align='middle'><td colspan='12' class='style24' style='height: 16px'><strong>DENIED CREDITS</strong></td></tr>";

					echo "	<tr>";

					echo "		<td><DIV class='TBL_COL_HDR'>Order ID</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Item</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Warehouse</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Reason</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Notes</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Quantity</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Chargeback</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Total</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Employee</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Pending?</DIV></td>";
					echo "		<td><DIV class='TBL_COL_HDR'>Credit Date</DIV></td>";
					echo "<td><DIV class='TBL_COL_HDR'>Action</DIV></td>";
					echo "\n\n		</tr>";
					do {
						//FORMAT THE OUTPUT OF THE SEARCH
						$id = $myrowsel["id"];
						$shade = "TBL_ROW_DATA_LIGHT";
						//SWITCH ROW COLORS
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

						/*---------- VIEW SEARCH RESULTS BY PAGE ---*/

						/*--------------- BEGIN RESULTS TABLE -----------*/
						echo "<tr>";
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

						<td class='<?php echo $shade; ?>'>
							<?php echo $orders_id; ?>
						</td>
						<?php $item_name = $myrowsel["item_name"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $item_name; ?>
						</td>
						<?php $warehouse = $myrowsel["warehouse"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $warehouse; ?>
						</td>
						<?php $reason_code = $myrowsel["reason_code"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $reason_code; ?>
						</td>
						<?php $notes = $myrowsel["notes"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $notes; ?>
						</td>
						<?php $quantity = $myrowsel["quantity"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $quantity; ?>
						</td>
						<?php $chargeback = $myrowsel["chargeback"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $chargeback; ?>
						</td>
						<?php $total = $myrowsel["total"];
						echo "<input type='hidden' name='amount_" . $database_id . "' value=" . $total . ">"; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $total; ?>
						</td>
						<?php $employee = $myrowsel["employee"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $employee; ?>
						</td>
						<?php $pending = $myrowsel["pending"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $pending; ?>
						</td>
						<?php $credit_date = $myrowsel["credit_date"]; ?>
						<td class='<?php echo $shade; ?>'>
							<?php echo $credit_date; ?>
						</td>
						<td class='<?php echo $shade; ?>'>
							<DIV class='PAGE_OPTIONS'>
								<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
									<?php  } ?><?php if ($allowedit == "yes") { ?>
									<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
									<?php  } ?><?php if ($allowdelete == "yes") { ?>
									<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>&orders_id=<?php echo $orders_id; ?>">Re-Apply Credit</a>
								<?php  } ?>
							</DIV>
						</td>
						</tr>
						<?php
					} while ($myrowsel = array_shift($result));
					echo "</table>";

					/*---------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---*/
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
					//PREVIOUS RECORDS LINK
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
					/*---------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---*/
				} //END PROC == ""
			} //END IF POSTING = YES
			/*-------------------- END SEARCH SECTION 9991 --------------------*/
		} // END IF PROC = ""
		/*-------------- ADD NEW RECORDS SECTION ----------------------------------*/
		if ($proc == "New") {
					?>
					<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
					<?php
					if ($allowaddnew == "yes") {
					?>
						<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
						<?php  } //END OF IF ALLOW ADDNEW 
					/*------------------- ADD NEW RECORD SECTION 9994 ---------------------*/

					if ($proc == "New") {
						echo "<DIV class='PAGE_STATUS'>Adding Record</DIV>";
						$post = isset($_GET['post']) ? $_GET['post'] : "";
						if ($post == "yes") {
							/*
					WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
					NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
					*/
							/* FIX STRING */
							$orders_id = FixString($_POST['orders_id']);
							$item_name = FixString($_POST['item_name']);
							$warehouse = FixString($_POST['warehouse']);
							$reason_code = FixString($_POST['reason_code']);
							$quantity = FixString($_POST['quantity']);
							$chargeback = FixString($_POST['chargeback']);
							$total = FixString($_POST['total']);
							$employee = FixString($_POST['employee']);
							$pending = FixString($_POST['pending']);
							$credit_date = FixString($_POST['credit_date']);
							/*-- SECTION: 9994SQL --*/
							$sql = "INSERT INTO ucbdb_credits ( orders_id, item_name, warehouse, reason_code, quantity, chargeback,
					total,employee,pending,credit_date $addl_insert_crit ) VALUES ( '$orders_id', '$item_name', '$warehouse',
					'$reason_code', '$quantity', '$chargeback','$total', '$employee', '$pending', '$credit_date'$addl_insert_values )";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql);
							if (empty($result)) {
								echo "<DIV class='SQL_RESULTS'>Record Inserted</DIV>";
							} else {
								echo "Error inserting record (9994SQL)";
							}
							//***** END INSERT SQL *****
						} //END IF POST = YES FOR ADDING NEW RECORDS
						/*------------- ADD NEW RECORD (CREATING) -------------------------*/
						if (!$post) { //THEN WE ARE ENTERING A NEW RECORD
							//SHOW THE ADD RECORD RECORD DATA INPUT FORM
							/*-- SECTION: 9994FORM --*/
						?>
							<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
								<table ALIGN='LEFT'>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>ORDERS_ID:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="orders_id" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>ITEM_NAME:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="item_name" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>WAREHOUSE:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="warehouse" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>REASON_CODE:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="reason_code" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>QUANTITY:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="quantity" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>CHARGEBACK:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="chargeback" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>TOTAL:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="total" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>EMPLOYEE:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="employee" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>PENDING:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="pending" SIZE="20">
									</tr>
									<tr>
										<td class='TBL_ROW_HDR'>
											<B>CREDIT_DATE:</B>
										</td>
										<td>
											<INPUT class='TXT_BOX' type="text" NAME="credit_date" SIZE="20">
									</tr>
									<tr>
										<td>
										</td>
										<td>
											<INPUT class='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
											<INPUT class='BUTTON' TYPE="RESET" VALUE="RESET" NAME="RESET">
										</td>
									</tr>
								</table>
								<BR>
							</FORM>
				<?php
						} //END if post=""
						//***** END ADD NEW ENTRY FORM*****
					} //END PROC == NEW
					/*--------------------- END ADD SECTION 9994 -----------------------*/
				} // END IF PROC = "NEW"
				?>
				<?php
				/*------------- SEARCH AND ADD-NEW LINKS -------------------*/
				if ($proc == "Edit") {
					$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
				?>
					<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
					<?php
					if ($allowaddnew == "yes") {
					?>
						<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
					<?php  } //END OF IF ALLOW ADDNEW 

					/*--------------------- EDIT RECORDS SECTION --------------------*/
					/*--------------------- EDIT RECORD SECTION 9993 -------------------------------*/

					?>
					<DIV class='PAGE_OPTIONS'>
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
						$post = isset($_GET["post"]) ? $_GET["post"] : "";
						$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
						//'SHOW THE EDIT RECORD RECORD PAGE
						//******************************************************************//
						if ($post == "yes") {
							//THEN WE ARE POSTING UPDATES TO A RECORD
							//***** BEGIN UPDATE SQL****
							/* FIX STRING */
							$orders_id = FixString($_POST['orders_id']);
							$item_name = FixString($_POST['item_name']);
							$warehouse = FixString($_POST['warehouse']);
							$reason_code = FixString($_POST['reason_code']);
							$quantity = FixString($_POST['quantity']);
							$chargeback = FixString($_POST['chargeback']);
							$total = FixString($_POST['total']);
							$employee = FixString($_POST['employee']);
							$pending = FixString($_POST['pending']);
							$credit_date = FixString($_POST['credit_date']);
							//SQL STRING
							/*-- SECTION: 9993SQLUPD --*/
							$sql = "UPDATE ucbdb_credits SET orders_id='$orders_id', item_name='$item_name', warehouse='$warehouse',
			reason_code='$reason_code', quantity='$quantity', chargeback='$chargeback', total='$total',employee='$employee',
			pending='$pending', credit_date='$credit_date' $addl_update_crit  WHERE (id='$id') $addl_select_crit ";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql);
							if (empty($result)) {
								echo "<DIV class='SQL_RESULTS'>Updated</DIV>";
							} else {
								echo "Error Updating Record (9993SQLUPD)";
							}
							//***** END UPDATE SQL *****
						} //END IF POST IS YES

						/*--------------- EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM -----------------------*/
						if ($post == "") { //THEN WE ARE EDITING A RECORD
							echo "<DIV class='PAGE_STATUS'>Editing Record</DIV>";
							$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
							/*-- SECTION: 9993SQLGET --*/
							$sql = "SELECT * FROM ucbdb_credits WHERE (id = '$id') $addl_select_crit ";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
							if ($myrow = array_shift($result)) {
								do {
									$orders_id = $myrow["orders_id"];
									$orders_id = preg_replace("/(\n)/", "<br>", $orders_id);
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
										<table ALIGN='LEFT'>
											<tr>
												<td class='TBL_ROW_HDR'>
													ORDERS_ID:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='orders_id' value='<?php echo $orders_id; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													ITEM_NAME:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='item_name' value='<?php echo $item_name; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													WAREHOUSE:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='warehouse' value='<?php echo $warehouse; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													REASON_CODE:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='reason_code' value='<?php echo $reason_code; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													QUANTITY:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='quantity' value='<?php echo $quantity; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													CHARGEBACK:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='chargeback' value='<?php echo $chargeback; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													TOTAL:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='total' value='<?php echo $total; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													EMPLOYEE:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='employee' value='<?php echo $employee; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													PENDING:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='pending' value='<?php echo $pending; ?>' size='20'>
												</td>
											</tr>
											<tr>
												<td class='TBL_ROW_HDR'>
													CREDIT_DATE:
												</td>
												<td>
													<INPUT TYPE='TEXT' class='TXT_BOX' name='credit_date' value='<?php echo $credit_date; ?>' size='20'>
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
													<input class="BUTTON" type="submit" value="Save" name="submit">
												</td>
											</tr>
										</table>
										<BR>
									</form>
				<?php
								} while ($myrow = array_shift($result));
							} //END IF RESULTS
						} //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
						//***** END EDIT FORM*****
					} //END PROC == EDIT

					/*------------------------- END EDIT RECORD SECTION 9993 ----------------*/
				} // END IF PROC = "EDIT"
				?>
				<?php
				/*----------------- SEARCH AND ADD-NEW LINKS ----------------------*/
				if ($proc == "View") {
					$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
				?>
					<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
					<?php
					if ($allowaddnew == "yes") {
					?>
						<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
						<?php  } //END OF IF ALLOW ADDNEW 

					/*---------------- VIEW RECORDS SECTION - VIEW SINGLE RECORDS---------------*/

					/*--------------- VIEW RECORD SECTION 9992 ---------------------*/
					if ($proc == "View") {
						echo "<DIV class='PAGE_STATUS'>Viewing Record</DIV>";
						//***** BEGIN SEARCH RESULTS ****************************************************
						//THEN WE ARE SHOWING THE RESULTS OF A SEARCH


						/*-- SECTION: 9992SQL --*/
						//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
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
								<table>
									<DIV class='PAGE_OPTIONS'>
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
									<tr>
										<td class='TBL_ROW_HDR'>ORDERS_ID:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $orders_id; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>ITEM_NAME:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $item_name; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>WAREHOUSE:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $warehouse; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>REASON_CODE:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $reason_code; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>QUANTITY:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $quantity; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>CHARGEBACK:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $chargeback; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>TOTAL:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $total; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>EMPLOYEE:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $employee; ?> </DIV>
										</td>
									<tr>
										<td class='TBL_ROW_HDR'>PENDING:</td>
										<td>
											<DIV class='TBL_ROW_DATA'><?php echo $pending; ?> </DIV>
										</td>
						<?php
							} while ($myrowsel = array_shift($result));
							echo "</tr>\n</table>";
						} //IF RESULT

					} //END OF PROC VIEW

					/*--------------- END VIEW RECORD SECTION 9992 ----------------------*/
				} // END IF PROC = "VIEW"
						?>
						<?php
						if ($proc == "Delete") {
							$id = decrypt_url($_GET["id"]);
							$delete = isset($_GET['delete']) ? $_GET['delete'] : '';
						?>
							<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
							<?php
							if ($allowaddnew == "yes") {
							?>
								<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
							<?php  } //END OF IF ALLOW ADDNEW 

							/*------------- DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS ---------------*/

							/*-------------------- DELETE RECORD SECTION 9995 -----------------------------*/
							?>
							<DIV class='PAGE_STATUS'>Re-APply Credit</DIV><br><br>
							<?php
							/*-- SECTION: 9995CONFIRM --*/
							if (!$delete) {
							?>
								<DIV class='PAGE_OPTIONS'>
									Are you sure you want to Re-Apply This Credit?<BR><br>
									<?php $orders_id = decrypt_url($_GET["orders_id"]);	?>
									<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&orders_id=<?php echo encrypt_url($orders_id); ?>&<?php echo $pagevars; ?>">Yes</a>
									<a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
								</DIV>
						<?php
							} //IF !DELETE

							if ($delete == "yes") {

								/*-- SECTION: 9995SQL --*/
								$sql = "UPDATE ucbdb_credits SET pending = 'Pending' WHERE id='$id' $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);

								$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
								$result = db_query($commqry);
								$commqryrw = array_shift($result);
								$comm_type = $commqryrw["id"];
								$orders_id = decrypt_url($_GET["orders_id"]);

								$output10 = "Credit Re-Applied";
								$today = date("Ymd");
								$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $output10 . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
								$result3 = db_query($sql3);
								if (empty($result)) {
									echo "<DIV class='SQL_RESULTS'>Successfully Updated</DIV><br><br>";
									echo "<a href=\"index.php\">Home</a><br><br>";
									if (!headers_sent()) {    //If headers not sent yet... then do php redirect
										header('Location: denied_credits.php?posting=yes&');
										exit;
									} else {
										echo "<script type=\"text/javascript\">";
										echo "window.location.href=\"denied_credits.php?posting=yes&\";";
										echo "</script>";
										echo "<noscript>";
										echo "<meta http-equiv=\"refresh\" content=\"0;url=denied_credits.php?posting=yes&\" />";
										echo "</noscript>";
										exit;
									}
								} else {
									echo "Error Deleting Record (9995SQL)";
								}
							} //END IF $DELETE=YES
							/*-------------------------
END DELETE RECORD SECTION 9995
------------------*/
						} // END IF PROC = "DELETE"

						?>
						<BR>
						<BR>
	</div>
</body>

</html>