<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Reports - Inventory </title>
</head>

<body>
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
	<?php
	/*-------- THE FOLLOWING ALLOWS GLOBALS = OFF SUPPORT --------*/
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	$thispage	= "inventory_transfer.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...

	$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
	$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.
	$addslash = "yes";
	require("mainfunctions/database.php");
	require("mainfunctions/general-functions.php");
	db();
	$proc = isset($_REQUEST['proc']) ? $_REQUEST['proc'] : "";
	$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit']: "";
	/*-------------- ADD NEW LINK---------------*/
	if ($proc == "") {
		if ($allowaddnew == "yes") {
	?>
		<?php  }
		/*------- BEGIN SEARCH SECTION 9991 -------*/
		/*-- SECTION: 9991FORM --*/
		?>
		<form method="POST" action="<?php echo $thispage; ?>?posting=yes&<?php echo $pagevars; ?>">
			<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php echo $searchcrit; ?>">
				<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1">
			</P>
		</form>
		<?php
		$posting = isset($_REQUEST['posting']) ? $_REQUEST['posting'] : "";
		/*------------------------ IF THEY ARE POSTING TO THE SEARCH PAGE  (SHOW SEARCH RESULTS) ------------------------*/
		if ($posting == "yes") {
			/*-------- PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -------------------*/
			$pagenorecords = 10;  //THIS IS THE PAGE SIZE
			//IF NO PAGE
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
			$myrecstart = 0;
			if ($page == 0) {
				$myrecstart = 0;
			} else {
				$myrecstart = ($page * $pagenorecords);
			}
			/*-- SECTION: 9991SQL --*/
			$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit'] : "";
			$flag = "";
			$sqlwhere = "";
			if ($searchcrit == "") {
				$flag = "all";
				$sql = "SELECT * FROM inv_warehouse_transactions";
				$sqlcount = "select count(*) as reccount from inv_warehouse_transactions";
			} else {
				//IF THEY TYPED SEARCH WORDS
				$sqlcount = "select count(*) as reccount from inv_warehouse_transactions WHERE (";
				$sql = "SELECT * FROM inv_warehouse_transactions WHERE (";
				$sqlwhere = "
				warehouse_id like '%$searchcrit%' OR 
				warehouse_name like '%$searchcrit%' OR 
				module_id like '%$searchcrit%' OR 
				module_name like '%$searchcrit%' OR 
				quantity like '%$searchcrit%' OR 
				update_date like '%$searchcrit%' 
				"; //FINISH SQL STRING
			} //END IF SEARCHCRIT = "";
			if ($flag == "all") {
				$sql = $sql . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
			} else {
				$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				$sql = $sql . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
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
			/*------------------ FIND OUT HOW MANY RECORDS WE HAVE ----------*/
			$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
			if ($reccount == 0) {
				$resultcount = (db_query($sqlcount)) or die;
				if ($myrowcount = array_shift($resultcount)) {
					$reccount = $myrowcount["reccount"];
				} //IF RECCOUNT = 0
			} //end if reccount
			echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
			echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
			/*----------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -----------*/
			if ($reccount > 10) {
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
					/*---------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---------------*/
					//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
					$result = db_query($sql);
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					if ($myrowsel = array_shift($result)) {
						$id = $myrowsel["id"];
						echo "<TABLE WIDTH='100%'>";
						echo "	<tr>";
						echo "<td><DIV CLASS='TBL_COL_HDR'>OPTIONS</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ID</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_NAME</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>MODULE_ID</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>MODULE_NAME</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>QUANTITY</DIV></td>";
						echo "		<td><DIV CLASS='TBL_COL_HDR'>UPDATE_DATE</DIV></td>";
						echo "\n\n		</tr>";
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

							/*----------- VIEW SEARCH RESULTS BY PAGE ---------------------*/
							/*------------ BEGIN RESULTS TABLE------------------*/
							echo "<tr>";
						?>
							<td CLASS='<?php echo $shade; ?>'>
								<DIV CLASS='PAGE_OPTIONS'>
									<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
										<?php  } ?><?php if ($allowedit == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
										<?php  } ?><?php if ($allowdelete == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
									<?php  } ?>
								</DIV>
							</td>
							<?php $warehouse_id = $myrowsel["warehouse_id"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $warehouse_id; ?>
							</td>
							<?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $warehouse_name; ?>
							</td>
							<?php $module_id = $myrowsel["module_id"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $module_id; ?>
							</td>
							<?php $module_name = $myrowsel["module_name"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $module_name; ?>
							</td>
							<?php $quantity = $myrowsel["quantity"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $quantity; ?>
							</td>
							<?php $update_date = $myrowsel["update_date"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $update_date; ?>
							</td>
							</tr>
							<?php
						} while ($myrowsel = array_shift($result));
						echo "</TABLE>";
						/*---------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS --------------*/
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
						/*--------------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS --------------*/
					} //END PROC == ""
				} //END IF POSTING = YES
				//END SEARCH SECTION 9991
			} // END IF PROC = ""
					?>
					<?php

					/*--------- ADD NEW RECORDS SECTION --------*/
					if ($proc == "New") {
					?>
						<a href="javascript: history.go(-1)">Back</a><br><br>
						<?php
						if ($allowaddnew == "yes") {
						?>
						<?php  } //END OF IF ALLOW ADDNEW 
						/*--------- ADD NEW RECORD SECTION 9994 -----------------*/
						if ($proc == "New") {
							if ($_REQUEST['post'] == "yes") {
								/* WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING*/
								/* FIX STRING */
								$warehouse_id = FixString($_REQUEST['warehouse_id']);
								$module_name = FixString($_REQUEST['module_name']);
								$quantity = FixString($_REQUEST['quantity']);
								$update_date = FixString($_REQUEST['update_date']);
								/*-- SECTION: 9994SQL --*/
								$sql = "INSERT INTO inv_warehouse_transactions (warehouse_id, module_name, quantity, update_date $addl_insert_crit ) 
								VALUES ( '$warehouse_id','$module_name','$quantity','$update_date' $addl_insert_values )";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br><a href='inventory_transfer.php?proc=New&'>Add / Subtract Additional Inventory</a><br><br><a href='report_inventory.php'>Return to Inventory Report</a></DIV>";
								} else {
									echo "Error inserting record (9994SQL)";
								}
								//***** END INSERT SQL *****
							} //END IF POST = YES FOR ADDING NEW RECORDS
							/*-------ADD NEW RECORD (CREATING) -------*/
							if (!$_REQUEST['post']) { //THEN WE ARE ENTERING A NEW RECORD
								//SHOW THE ADD RECORD RECORD DATA INPUT FORM
								/*-- SECTION: 9994FORM --*/
								echo "<DIV CLASS='PAGE_STATUS'>Use the form below to add or subtract inventory.  Note that a negative value entered in the quantity field will subtract inventory from the warehouse.  </DIV>";
							?>
								<FORM METHOD="POST" name="rptSearch" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
									<TABLE ALIGN='LEFT'>
										<tr>
											<td class="style3">
												<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
													<B>Warehouse:</B>
												</font>
											</td>
											<td>
												<select name="warehouse_id">
													<option value="">Please Select</option>
													<?php
													$sql2 = "SELECT DISTINCT warehouse_name, warehouse_id FROM inv_warehouse_to_modules ORDER BY warehouse_id";
													$result2 = db_query($sql2);
													while ($myrowsel2 = array_shift($result2)) {
													?>
														<option value="<?php echo $myrowsel2["warehouse_id"]; ?>"><?php echo $myrowsel2["warehouse_name"]; ?></option>
													<?php  } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="style3">
												<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
													<B>Module Type:</B>
												</font>
											</td>
											<td>
												<select name="module_name">
													<option value="">Please Select</option>
													<?php
													$sql3 = "SELECT DISTINCT module_name FROM inv_warehouse_to_modules ORDER BY module_id";
													$result3 = db_query($sql3);
													while ($myrowsel3 = array_shift($result3)) {
													?>
														<option value="<?php echo $myrowsel3["module_name"]; ?>"><?php echo $myrowsel3["module_name"]; ?></option>
													<?php  } ?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="style3">
												<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Quantity</font>
											</td>
											<td>
												<INPUT CLASS='TXT_BOX' type="text" NAME="quantity" SIZE="20">
											</td>
										</tr>
										<tr>
											<td class="style3">
												<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Update Date</font>
											</td>
											<td>
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

												<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><input type="text" name="update_date" size="11" value="<?php echo date('Ymd'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.update_date,'anchor2xx','yyyyMMdd'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
													<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
												</font>
											</td>
										</tr>
										<tr>
											<td>
											</td>
											<td>
												<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
											</td>
										</tr>
									</TABLE>
									<BR>
								</FORM>
					<?php
							} //END if post=""
							//***** END ADD NEW ENTRY FORM*****
						} //END PROC == NEW
						/*-------- END ADD SECTION 9994 --------------*/
					} // END IF PROC = "NEW"
					?>
					<?php
					/*-------- SEARCH AND ADD-NEW LINKS --------*/
					if ($proc == "Edit") {
						$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
					?>
						<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
						<?php  } //END OF IF ALLOW ADDNEW 
						/*------------ EDIT RECORDS SECTION --------------*/
						/*------------- EDIT RECORD SECTION 9993 -------------*/
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
							?>
							<font face="arial" size="2">
						</DIV>
						<?php
						if ($proc == "Edit") {
							//SHOW THE EDIT RECORD RECORD PAGE
							if ($_REQUEST['post'] == "yes") {
								/* FIX STRING */
								$warehouse_id = FixString($_REQUEST['warehouse_id']);
								$warehouse_name = FixString($_REQUEST['warehouse_name']);
								$module_id = FixString($_REQUEST['module_id']);
								$module_name = FixString($_REQUEST['module_name']);
								$quantity = FixString($_REQUEST['quantity']);
								$update_date = FixString($_REQUEST['update_date']);

								//SQL STRING
								/*-- SECTION: 9993SQLUPD --*/
								$sql = "UPDATE inv_warehouse_transactions SET warehouse_id='$warehouse_id', warehouse_name='$warehouse_name',
 								module_id='$module_id', module_name='$module_name', quantity='$quantity', update_date='$update_date $addl_update_crit WHERE (id='$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
								} else {
									echo "Error Updating Record (9993SQLUPD)";
								}
								//***** END UPDATE SQL *****
							} //END IF POST IS YES
							/*--------- EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM -----------*/
							if (!$_REQUEST['post']) { //THEN WE ARE EDITING A RECORD
								echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
								/*-- SECTION: 9993SQLGET --*/
								$sql = "SELECT * FROM inv_warehouse_transactions WHERE (id = '$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
								if ($myrow = array_shift($result)) {
									do {

										$warehouse_id = $myrow["warehouse_id"];
										$warehouse_id = preg_replace("/(\n)/", "<br>", $warehouse_id);
										$warehouse_name = $myrow["warehouse_name"];
										$warehouse_name = preg_replace("/(\n)/", "<br>", $warehouse_name);
										$module_id = $myrow["module_id"];
										$module_id = preg_replace("/(\n)/", "<br>", $module_id);
										$module_name = $myrow["module_name"];
										$module_name = preg_replace("/(\n)/", "<br>", $module_name);
										$quantity = $myrow["quantity"];
										$quantity = preg_replace("/(\n)/", "<br>", $quantity);
										$update_date = $myrow["update_date"];
										$update_date = preg_replace("/(\n)/", "<br>", $update_date);
						?>
										<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
											<br>
											<TABLE ALIGN='LEFT'>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														WAREHOUSE_ID:
													</td>
													<td>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_id' value='<?php echo $warehouse_id; ?>' size='20'>
													</td>
												</tr>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														Warehouse:
													</td>
													<td>
														<select name="warehouse">
															<option value="">Please Select</option>
															<?php

															$sql2 = "SELECT * FROM warehouse ORDER BY warehouse_id";
															$result2 = db_query($sql2);
															while ($myrowsel2 = array_shift($result2)) {

															?>
																<option value="<?php echo $myrowsel2["warehouse_id"]; ?>"><?php echo $myrowsel2["warehouse_name"]; ?></option>
															<?php  } ?>
														</select>
													</td>
												</tr>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														MODULE_ID:
													</td>
													<td>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='module_id' value='<?php echo $module_id; ?>' size='20'>
													</td>
												</tr>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														MODULE_NAME:
													</td>
													<td>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='module_name' value='<?php echo $module_name; ?>' size='20'>
													</td>
												</tr>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														QUANTITY:
													</td>
													<td>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='quantity' value='<?php echo $quantity; ?>' size='20'>
													</td>
												</tr>
												<tr>
													<td CLASS='TBL_ROW_HDR'>
														UPDATE_DATE:
													</td>
													<td>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='update_date' value='<?php echo $update_date; ?>' size='20'>
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
						/*------------- END EDIT RECORD SECTION 9993 ----------------------*/
					} // END IF PROC = "EDIT"
					?>
					<?php
					/*--------- SEARCH AND ADD-NEW LINKS ---------*/
					if ($proc == "View") {
					?>
						<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
							<?php  } //END OF IF ALLOW ADDNEW 

						/*----------- VIEW RECORDS SECTION - VIEW SINGLE RECORDS ----------------*/

						/*------------ VIEW RECORD SECTION 9992 ----------------*/
						if ($proc == "View") {
							$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
							echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
							//***** BEGIN SEARCH RESULTS ****************************************************
							//THEN WE ARE SHOWING THE RESULTS OF A SEARCH
							/*-- SECTION: 9992SQL --*/
							//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
							$sql = "SELECT * FROM inv_warehouse_transactions WHERE id='$id' $addl_select_crit ";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql);
							if ($myrowsel = array_shift($result)) {
								do {
									$id = $myrowsel["id"];
									$warehouse_id = $myrowsel["warehouse_id"];
									$warehouse_name = $myrowsel["warehouse_name"];
									$module_id = $myrowsel["module_id"];
									$module_name = $myrowsel["module_name"];
									$quantity = $myrowsel["quantity"];
									$update_date = $myrowsel["update_date"];
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
										<tr>
											<td CLASS='TBL_ROW_HDR'>WAREHOUSE_ID:</td>
											<td>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_id; ?> </DIV>
											</td>
										<tr>
											<td CLASS='TBL_ROW_HDR'>WAREHOUSE_NAME:</td>
											<td>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_name; ?> </DIV>
											</td>
										<tr>
											<td CLASS='TBL_ROW_HDR'>MODULE_ID:</td>
											<td>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $module_id; ?> </DIV>
											</td>
										<tr>
											<td CLASS='TBL_ROW_HDR'>MODULE_NAME:</td>
											<td>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $module_name; ?> </DIV>
											</td>
										<tr>
											<td CLASS='TBL_ROW_HDR'>QUANTITY:</td>
											<td>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $quantity; ?> </DIV>
											</td>
							<?php
								} while ($myrowsel = array_shift($result));
								echo "</tr>\n</TABLE>";
							} //IF RESULT
						} //END OF PROC VIEW
						/*--------- END VIEW RECORD SECTION 9992---------------*/
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

								/*------ DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS -------------*/

								/*-------- DELETE RECORD SECTION 9995 ----------------------*/
								?>
								<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
								<?php
								/*-- SECTION: 9995CONFIRM --*/
								$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
								if (!$_REQUEST['delete']) {
								?>
									<DIV CLASS='PAGE_OPTIONS'>
										Are you sure you want to delete?<BR>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
										<a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
									</DIV>
							<?php
								} //IF !DELETE
								if ($_REQUEST['delete'] == "yes") {
									/*-- SECTION: 9995SQL --*/
									$sql = "DELETE FROM inv_warehouse_transactions WHERE id='$id' $addl_select_crit ";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql);
									if (empty($result)) {
										echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
									} else {
										echo "Error Deleting Record (9995SQL)";
									}
								} //END IF $DELETE=YES
								/*----------END DELETE RECORD SECTION 9995 ----------------*/
							} // END IF PROC = "DELETE"
							?>
							<BR>
							<BR>
							</Font>
</body>

</html>