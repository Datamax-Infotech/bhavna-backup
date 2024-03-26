<!DOCTYPE html>
<html>

<head>
	<title>UCB Loop System - Manufacturer Management</title>
</head>

<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= 'manage_manufacturer.php'; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
	$addl_select_crit = "AND rec_type='Manufacturer' ORDER BY company_name"; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
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
			$posting = isset($_REQUEST['posting']) ? $_REQUEST['posting'] : "";
			if ($allowaddnew == "yes") {
		?>
				<br>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">Add New Manufacturer</a><br>
			<?php  }
			?>
			<?php
			if ($posting == "yes") {
				$pagenorecords = 10;  //THIS IS THE PAGE SIZE
				//IF NO PAGE
				$myrecstart = 0;
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
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
					$sql = "SELECT * FROM loop_warehouse";
					$sqlcount = "select count(*) as reccount from loop_warehouse";
				} else {
					//IF THEY TYPED SEARCH WORDS
					$sqlcount = "select count(*) as reccount from loop_warehouse WHERE (";
					$sql = "SELECT * FROM loop_warehouse WHERE (";
					$sqlwhere = "
						company_name like '%$searchcrit%' OR 
						company_address1 like '%$searchcrit%' OR 
						company_address2 like '%$searchcrit%' OR 
						company_city like '%$searchcrit%' OR 
						company_state like '%$searchcrit%' OR 
						company_zip like '%$searchcrit%' OR 
						company_phone like '%$searchcrit%' OR 
						company_email like '%$searchcrit%' OR 
						company_terms like '%$searchcrit%' OR 
						company_contact like '%$searchcrit%' OR 
						warehouse_name like '%$searchcrit%' OR 
						warehouse_address1 like '%$searchcrit%' OR 
						warehouse_address2 like '%$searchcrit%' OR 
						warehouse_city like '%$searchcrit%' OR 
						warehouse_state like '%$searchcrit%' OR 
						warehouse_zip like '%$searchcrit%' OR 
						warehouse_contact like '%$searchcrit%' OR 
						warehouse_contact_phone like '%$searchcrit%' OR 
						warehouse_contact_email like '%$searchcrit%' OR 
						warehouse_manager like '%$searchcrit%' OR 
						warehouse_manager_phone like '%$searchcrit%' OR 
						warehouse_manager_email like '%$searchcrit%' OR 
						dock_details like '%$searchcrit%' OR 
						warehouse_notes like '%$searchcrit%' OR 
						other1 like '%$searchcrit%' OR 
						other2 like '%$searchcrit%' OR 
						other3 like '%$searchcrit%' 
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
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
				if ($page == 0) {
					$page = 1;
				} else {
					$page = ($page + 1);
				}
				/*----------- FIND OUT HOW MANY RECORDS WE HAVE -------------*/
				$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
				if ($reccount == 0) {
					$resultcount = db_query($sqlcount);
					if ($myrowcount = array_shift($resultcount)) {
						$reccount = $myrowcount["reccount"];
					} //IF RECCOUNT = 0
				} //end if reccount

				//	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
				//	echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
				echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";

				/*------------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ---------------*/
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
						$result = db_query($sql);
						if ($sql_debug_mode == 1) {
							echo "<BR>SQL: $sql<BR>";
						}
						if ($myrowsel = array_shift($result)) {
							$id = $myrowsel["id"];
							echo "<br><TABLE WIDTH='780'>";
							echo "	<tr align='middle'><td colspan='6' class='style24' style='height: 16px'><strong>MANUFACTURER SETUP</strong></td></tr>";
							echo "	<TR>";

							echo "		<TD><DIV CLASS='TBL_COL_HDR'>Company Name</DIV></TD>";
							echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Name</DIV></TD>";
							echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact</DIV></TD>";
							echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Phone</DIV></TD>";
							echo "		<TD><DIV CLASS='TBL_COL_HDR'>Warehouse Contact Email</DIV></TD>";
							echo "<TD><DIV CLASS='TBL_COL_HDR'>Options</DIV></TD>";
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
								echo "<TR>";
							?>
								<?php $company_name = $myrowsel["company_name"]; ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<?php echo $company_name; ?>
								</TD>
								<?php $warehouse_name = $myrowsel["warehouse_name"]; ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<?php echo $warehouse_name; ?>
								</TD>
								<?php $warehouse_contact = $myrowsel["warehouse_contact"]; ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<?php echo $warehouse_contact; ?>
								</TD>
								<?php $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"]; ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<?php echo $warehouse_contact_phone; ?>
								</TD>
								<?php $warehouse_contact_email = $myrowsel["warehouse_contact_email"]; ?>
								<TD CLASS='<?php echo $shade; ?>'>
									<?php echo $warehouse_contact_email; ?>
								</TD>
								<TD CLASS='<?php echo $shade; ?>'>
									<DIV CLASS='PAGE_OPTIONS'>
										<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=View&<?php echo $pagevars; ?>">View</a>
											<?php  } ?><?php if ($allowedit == "yes") { ?>
											<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
											<?php  } ?><?php if ($allowdelete == "yes") { ?>
											<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
										<?php  } ?>
									</DIV>
								</TD>
								</TR>
								<?php
							} while ($myrowsel = array_shift($result));
							echo "</TABLE>";

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
						} //END PROC == ""
					} //END IF POSTING = YES
				} // END IF PROC = ""
						?>
						<?php
						/*-------- ADD NEW RECORDS SECTION --------*/
						if ($proc == "New") {
						?>
							<!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>  -->
							<?php
							if ($allowaddnew == "yes") {
							?>
								<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a>-->
								<?php  } //END OF IF ALLOW ADDNEW 
							if ($proc == "New") {
								echo "<br><a href=\"manage_manufacturer.php?posting=yes\">Back</a><br><br>";
								echo "<table border=0 width=780><tr><td valign=top><DIV CLASS='PAGE_STATUS'>Add New Manufacturer</DIV>";
								if ($_REQUEST['post'] == "yes") {
									/* FIX STRING */
									$company_name = FixString($_REQUEST['company_name']);
									$company_address1 = FixString($_REQUEST['company_address1']);
									$company_address2 = FixString($_REQUEST['company_address2']);
									$company_city = FixString($_REQUEST['company_city']);
									$company_state = FixString($_REQUEST['company_state']);
									$company_zip = FixString($_REQUEST['company_zip']);
									$company_phone = FixString($_REQUEST['company_phone']);
									$company_email = FixString($_REQUEST['company_email']);
									$company_terms = FixString($_REQUEST['company_terms']);
									$company_contact = FixString($_REQUEST['company_contact']);
									$warehouse_name = FixString($_REQUEST['warehouse_name']);
									$warehouse_address1 = FixString($_REQUEST['warehouse_address1']);
									$warehouse_address2 = FixString($_REQUEST['warehouse_address2']);
									$warehouse_city = FixString($_REQUEST['warehouse_city']);
									$warehouse_state = FixString($_REQUEST['warehouse_state']);
									$warehouse_zip = FixString($_REQUEST['warehouse_zip']);
									$warehouse_contact = FixString($_REQUEST['warehouse_contact']);
									$warehouse_contact_phone = FixString($_REQUEST['warehouse_contact_phone']);
									$warehouse_contact_email = FixString($_REQUEST['warehouse_contact_email']);
									$warehouse_manager = FixString($_REQUEST['warehouse_manager']);
									$warehouse_manager_phone = FixString($_REQUEST['warehouse_manager_phone']);
									$warehouse_manager_email = FixString($_REQUEST['warehouse_manager_email']);
									$dock_details = FixString($_REQUEST['dock_details']);
									$warehouse_notes = FixString($_REQUEST['warehouse_notes']);
									$rec_type = FixString($_REQUEST['rec_type']);
									$bs_status = FixString($_REQUEST['bs_status']);
									$other1 = FixString($_REQUEST['other1']);
									$other2 = FixString($_REQUEST['other2']);
									$other3 = FixString($_REQUEST['other3']);
									/*-- SECTION: 9994SQL --*/
									$sql = "INSERT INTO loop_warehouse (company_name,company_address1,company_address2,company_city,
									company_state, company_zip, company_phone,company_email,company_terms,company_contact,warehouse_name,
									warehouse_address1, warehouse_address2,warehouse_city,warehouse_state,warehouse_zip,warehouse_contact,warehouse_contact_phone,
									warehouse_contact_email,warehouse_manager,warehouse_manager_phone,warehouse_manager_email,dock_details,
									warehouse_notes, rec_type,bs_status,other1,other2,other3 $addl_insert_crit ) 
									VALUES ( '$company_name','$company_address1','$company_address2','$company_city','$company_state',
									'$company_zip','$company_phone','$company_email','$company_terms','$company_contact','$warehouse_name',
									'$warehouse_address1','$warehouse_address2','$warehouse_city','$warehouse_state','$warehouse_zip','$warehouse_contact',
									'$warehouse_contact_phone','$warehouse_contact_email','$warehouse_manager','$warehouse_manager_phone','$warehouse_manager_email',
									'$dock_details','$warehouse_notes', '$rec_type','$bs_status','$other1','$other2','$other3' $addl_insert_values )";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql);
									if (empty($result)) {
										echo "<br><DIV CLASS='SQL_RESULTS'>Manufacturer Added.  <a href=\"manage_manufacturer.php?posting=yes\">Continue</a></DIV>";
										if (!headers_sent()) {    //If headers not sent yet... then do php redirect
											header('Location: manage_manufacturer.php?posting=yes');
											exit;
										} else {
											echo "<script type=\"text/javascript\">";
											echo "window.location.href=\"manage_manufacturer.php?posting=yes\";";
											echo "</script>";
											echo "<noscript>";
											echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_manufacturer.php?posting=yes\" />";
											echo "</noscript>";
											exit;
										} //==== End -- Redirect

									} else {
										echo "Error inserting record (9994SQL)";
									}
									//***** END INSERT SQL *****
								} //END IF POST = YES FOR ADDING NEW RECORDS

								if (!$_REQUEST['post']) { //THEN WE ARE ENTERING A NEW RECORD
									//SHOW THE ADD RECORD RECORD DATA INPUT FORM
									/*-- SECTION: 9994FORM --*/
								?>
									<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
										<input type="hidden" name="rec_type" value="Manufacturer">
										<input type="hidden" name="bs_status" value="Seller">
										<TABLE ALIGN='LEFT'>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_name" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Address One:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_address1" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Address Two</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_address2" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company City:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_city" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company State:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_state" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Zip Code:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_zip" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Contact Name:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_contact" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Contact Phone:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_phone" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Contact Email:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_email" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Company Credit Terms:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="company_terms" SIZE="20">
												</TD>
											</TR>

											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Name:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_name" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Address One:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_address1" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Address Two:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_address2" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse City:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_city" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehosue State:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_state" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Zip Code:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_zip" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Contact Name:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Contact Phone:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact_phone" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Contact Email:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_contact_email" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Other Contact:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Other Contact Phone:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager_phone" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Other Contact Email:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse_manager_email" SIZE="20">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Dock Details:</B>
												</TD>
												<TD>
													<INPUT CLASS='TXT_BOX' type="text" NAME="dock_details" SIZE="40">
												</TD>
											</TR>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>
													<B>Warehouse Notes:</B>
												</TD>
												<TD>
													<textarea CLASS='TXT_BOX' NAME="warehouse_notes" ROWS="4" cols="35"></TEXTAREA>
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
								//***** END ADD NEW ENTRY FORM*****
								echo "</table>";
							} //END PROC == NEW
						} // END IF PROC = "NEW"
						?>
						<?php
						if ($proc == "Edit") {
							echo "<br><a href=\"manage_manufacturer.php?posting=yes\">Back</a><br><br>";
						?>
							<?php
							if ($allowaddnew == "yes") {
							?>
							<?php  } //END OF IF ALLOW ADDNEW 
							?>
							<?php

							if ($proc == "Edit") {
								//SHOW THE EDIT RECORD RECORD PAGE
								if ($_REQUEST['post'] == "yes") {
									//THEN WE ARE POSTING UPDATES TO A RECORD
									/* FIX STRING */
									$id = $_REQUEST['id'];
									$company_name = FixString($_REQUEST['company_name']);
									$company_address1 = FixString($_REQUEST['company_address1']);
									$company_address2 = FixString($_REQUEST['company_address2']);
									$company_city = FixString($_REQUEST['company_city']);
									$company_state = FixString($_REQUEST['company_state']);
									$company_zip = FixString($_REQUEST['company_zip']);
									$company_phone = FixString($_REQUEST['company_phone']);
									$company_email = FixString($_REQUEST['company_email']);
									$company_terms = FixString($_REQUEST['company_terms']);
									$company_contact = FixString($_REQUEST['company_contact']);
									$warehouse_name = FixString($_REQUEST['warehouse_name']);
									$warehouse_address1 = FixString($_REQUEST['warehouse_address1']);
									$warehouse_address2 = FixString($_REQUEST['warehouse_address2']);
									$warehouse_city = FixString($_REQUEST['warehouse_city']);
									$warehouse_state = FixString($_REQUEST['warehouse_state']);
									$warehouse_zip = FixString($_REQUEST['warehouse_zip']);
									$warehouse_contact = FixString($_REQUEST['warehouse_contact']);
									$warehouse_contact_phone = FixString($_REQUEST['warehouse_contact_phone']);
									$warehouse_contact_email = FixString($_REQUEST['warehouse_contact_email']);
									$warehouse_manager = FixString($_REQUEST['warehouse_manager']);
									$warehouse_manager_phone = FixString($_REQUEST['warehouse_manager_phone']);
									$warehouse_manager_email = FixString($_REQUEST['warehouse_manager_email']);
									$dock_details = FixString($_REQUEST['dock_details']);
									$warehouse_notes = FixString($_REQUEST['warehouse_notes']);
									$other1 = FixString($_REQUEST['other1']);
									$other2 = FixString($_REQUEST['other2']);
									$other3 = FixString($_REQUEST['other3']);
									//SQL STRING
									/*-- SECTION: 9993SQLUPD --*/
									$sql = "UPDATE loop_warehouse SET company_name='$company_name', company_address1='$company_address1',
									company_address2='$company_address2', company_city='$company_city', company_state='$company_state',company_zip='$company_zip',company_phone='$company_phone',
									company_email='$company_email',company_terms='$company_terms',company_contact='$company_contact',warehouse_name='$warehouse_name',
									warehouse_address1='$warehouse_address1',warehouse_address2='$warehouse_address2',warehouse_city='$warehouse_city',
									warehouse_state='$warehouse_state', warehouse_zip='$warehouse_zip',warehouse_contact='$warehouse_contact',warehouse_contact_phone='$warehouse_contact_phone',
									warehouse_contact_email='$warehouse_contact_email',warehouse_manager='$warehouse_manager',warehouse_manager_phone='$warehouse_manager_phone',warehouse_manager_email='$warehouse_manager_email',
 									dock_details='$dock_details', warehouse_notes='$warehouse_notes',other1='$other1', other2='$other2', other3='$other3' $addl_update_crit 
									WHERE (id='$id') $addl_select_crit ";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql);
									if (empty($result)) {
										echo "<DIV CLASS='SQL_RESULTS'>Manufacturer Updated.  <a href=\"manage_manufacturer.php?posting=yes\">Continue</a></DIV>";
										if (!headers_sent()) {    //If headers not sent yet... then do php redirect
											header('Location: manage_manufacturer.php?posting=yes');
											exit;
										} else {
											echo "<script type=\"text/javascript\">";
											echo "window.location.href=\"manage_manufacturer.php?posting=yes\";";
											echo "</script>";
											echo "<noscript>";
											echo "<meta http-equiv=\"refresh\" content=\"0;url=manage_manufacturer.php?posting=yes\" />";
											echo "</noscript>";
											exit;
										} //==== End -- Redirect
									} else {
										echo "Error Updating Record (9993SQLUPD)";
									}
									//***** END UPDATE SQL *****
								} //END IF POST IS YES
								if ($_REQUEST['post'] == "") { //THEN WE ARE EDITING A RECORD
									echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
									/*-- SECTION: 9993SQLGET --*/
									$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
									$sql = "SELECT * FROM loop_warehouse WHERE (id = '$id') $addl_select_crit ";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
									if ($myrow = array_shift($result)) {
										do {
											$company_name = $myrow["company_name"];
											$company_name = preg_replace("/(\n)/", "<br>", $company_name);
											$company_address1 = $myrow["company_address1"];
											$company_address1 = preg_replace("/(\n)/", "<br>", $company_address1);
											$company_address2 = $myrow["company_address2"];
											$company_address2 = preg_replace("/(\n)/", "<br>", $company_address2);
											$company_city = $myrow["company_city"];
											$company_city = preg_replace("/(\n)/", "<br>", $company_city);
											$company_state = $myrow["company_state"];
											$company_state = preg_replace("/(\n)/", "<br>", $company_state);
											$company_zip = $myrow["company_zip"];
											$company_zip = preg_replace("/(\n)/", "<br>", $company_zip);
											$company_phone = $myrow["company_phone"];
											$company_phone = preg_replace("/(\n)/", "<br>", $company_phone);
											$company_email = $myrow["company_email"];
											$company_email = preg_replace("/(\n)/", "<br>", $company_email);
											$company_terms = $myrow["company_terms"];
											$company_terms = preg_replace("/(\n)/", "<br>", $company_terms);
											$company_contact = $myrow["company_contact"];
											$company_contact = preg_replace("/(\n)/", "<br>", $company_contact);
											$warehouse_name = $myrow["warehouse_name"];
											$warehouse_name = preg_replace("/(\n)/", "<br>", $warehouse_name);
											$warehouse_address1 = $myrow["warehouse_address1"];
											$warehouse_address1 = preg_replace("/(\n)/", "<br>", $warehouse_address1);
											$warehouse_address2 = $myrow["warehouse_address2"];
											$warehouse_address2 = preg_replace("/(\n)/", "<br>", $warehouse_address2);
											$warehouse_city = $myrow["warehouse_city"];
											$warehouse_city = preg_replace("/(\n)/", "<br>", $warehouse_city);
											$warehouse_state = $myrow["warehouse_state"];
											$warehouse_state = preg_replace("/(\n)/", "<br>", $warehouse_state);
											$warehouse_zip = $myrow["warehouse_zip"];
											$warehouse_zip = preg_replace("/(\n)/", "<br>", $warehouse_zip);
											$warehouse_contact = $myrow["warehouse_contact"];
											$warehouse_contact = preg_replace("/(\n)/", "<br>", $warehouse_contact);
											$warehouse_contact_phone = $myrow["warehouse_contact_phone"];
											$warehouse_contact_phone = preg_replace("/(\n)/", "<br>", $warehouse_contact_phone);
											$warehouse_contact_email = $myrow["warehouse_contact_email"];
											$warehouse_contact_email = preg_replace("/(\n)/", "<br>", $warehouse_contact_email);
											$warehouse_manager = $myrow["warehouse_manager"];
											$warehouse_manager = preg_replace("/(\n)/", "<br>", $warehouse_manager);
											$warehouse_manager_phone = $myrow["warehouse_manager_phone"];
											$warehouse_manager_phone = preg_replace("/(\n)/", "<br>", $warehouse_manager_phone);
											$warehouse_manager_email = $myrow["warehouse_manager_email"];
											$warehouse_manager_email = preg_replace("/(\n)/", "<br>", $warehouse_manager_email);
											$dock_details = $myrow["dock_details"];
											$dock_details = preg_replace("/(\n)/", "<br>", $dock_details);
											$warehouse_notes = $myrow["warehouse_notes"];
											$warehouse_notes = preg_replace("/(\n)/", "<br>", $warehouse_notes);
											$other1 = $myrow["other1"];
											$other1 = preg_replace("/(\n)/", "<br>", $other1);
											$other2 = $myrow["other2"];
											$other2 = preg_replace("/(\n)/", "<br>", $other2);
											$other3 = $myrow["other3"];
											$other3 = preg_replace("/(\n)/", "<br>", $other3);
							?>
											<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
												<br>
												<TABLE ALIGN='LEFT'>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Name:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_name' value='<?php echo $company_name; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Address One:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address1' value='<?php echo $company_address1; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Address Two:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_address2' value='<?php echo $company_address2; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company City:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_city' value='<?php echo $company_city; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company State:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_state' value='<?php echo $company_state; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Zip Code:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_zip' value='<?php echo $company_zip; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Contact Name:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_contact' value='<?php echo $company_contact; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Contact Phone:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_phone' value='<?php echo $company_phone; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Contact Email:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_email' value='<?php echo $company_email; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Company Credit Terms:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company_terms' value='<?php echo $company_terms; ?>' size='20'>
														</td>
													</tr>

													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Name:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_name' value='<?php echo $warehouse_name; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Address One:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_address1' value='<?php echo $warehouse_address1; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Address Two:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_address2' value='<?php echo $warehouse_address2; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse City:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_city' value='<?php echo $warehouse_city; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse State:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_state' value='<?php echo $warehouse_state; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Zip Code:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_zip' value='<?php echo $warehouse_zip; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Contact:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact' value='<?php echo $warehouse_contact; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Contact Phone:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact_phone' value='<?php echo $warehouse_contact_phone; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Contact Email:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_contact_email' value='<?php echo $warehouse_contact_email; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Other Contact:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager' value='<?php echo $warehouse_manager; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Other Phone:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager_phone' value='<?php echo $warehouse_manager_phone; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Other Contact Email:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_manager_email' value='<?php echo $warehouse_manager_email; ?>' size='20'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Dock Details:
														</TD>
														<TD>
															<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='dock_details' value='<?php echo $dock_details; ?>' size='40'>
														</td>
													</tr>
													<TR>
														<TD CLASS='TBL_ROW_HDR'>
															Warehouse Notes:
														</TD>
														<TD>
															<?php $warehouse_notes = preg_replace("/(<BR>)/", "", $warehouse_notes); ?>
															<textarea CLASS='TXT_BOX' name="warehouse_notes" rows="4" cols="35"><?php echo $warehouse_notes; ?></textarea>
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
								//***** END EDIT FORM*****
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
								<?php  } //END OF IF ALLOW ADDNEW 
							if ($proc == "View") {
								$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : '';
								echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
								$sql = "SELECT * FROM loop_warehouse WHERE id='$id' $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if ($myrowsel = array_shift($result)) {
									do {
										$id = $myrowsel["id"];
										$company_name = $myrowsel["company_name"];
										$company_address1 = $myrowsel["company_address1"];
										$company_address2 = $myrowsel["company_address2"];
										$company_city = $myrowsel["company_city"];
										$company_state = $myrowsel["company_state"];
										$company_zip = $myrowsel["company_zip"];
										$company_phone = $myrowsel["company_phone"];
										$company_email = $myrowsel["company_email"];
										$company_terms = $myrowsel["company_terms"];
										$company_contact = $myrowsel["company_contact"];
										$warehouse_name = $myrowsel["warehouse_name"];
										$warehouse_address1 = $myrowsel["warehouse_address1"];
										$warehouse_address2 = $myrowsel["warehouse_address2"];
										$warehouse_city = $myrowsel["warehouse_city"];
										$warehouse_state = $myrowsel["warehouse_state"];
										$warehouse_zip = $myrowsel["warehouse_zip"];
										$warehouse_contact = $myrowsel["warehouse_contact"];
										$warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
										$warehouse_contact_email = $myrowsel["warehouse_contact_email"];
										$warehouse_manager = $myrowsel["warehouse_manager"];
										$warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
										$warehouse_manager_email = $myrowsel["warehouse_manager_email"];
										$dock_details = $myrowsel["dock_details"];
										$warehouse_notes = $myrowsel["warehouse_notes"];
										$other1 = $myrowsel["other1"];
										$other2 = $myrowsel["other2"];
										$other3 = $myrowsel["other3"];
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
												<TD CLASS='TBL_ROW_HDR'>COMPANY_NAME:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_name; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_ADDRESS1:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_address1; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_ADDRESS2:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_address2; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_CITY:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_city; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_STATE:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_state; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_ZIP:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_zip; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_PHONE:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_phone; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_EMAIL:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_email; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_TERMS:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_terms; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>COMPANY_CONTACT:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $company_contact; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_NAME:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_name; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ADDRESS1:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_address1; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ADDRESS2:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_address2; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CITY:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_city; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_STATE:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_state; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ZIP:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_zip; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT_PHONE:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact_phone; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_CONTACT_EMAIL:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_contact_email; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER_PHONE:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager_phone; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_MANAGER_EMAIL:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_manager_email; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>DOCK_DETAILS:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $dock_details; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>WAREHOUSE_NOTES:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $warehouse_notes; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>OTHER1:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $other1; ?> </DIV>
												</TD>
											<TR>
												<TD CLASS='TBL_ROW_HDR'>OTHER2:</TD>
												<TD>
													<DIV CLASS='TBL_ROW_DATA'><?php echo $other2; ?> </DIV>
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
									echo "<br><a href=\"manage_manufacturer.php?posting=yes\">Back</a><br><br>";
								?>
									<!-- <a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
									<?php
									if ($allowaddnew == "yes") {
									?>
									<?php  } //END OF IF ALLOW ADDNEW 

									/*------- DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS --------*/
									?>
									<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
									<?php
									/*-- SECTION: 9995CONFIRM --*/
									$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : '';
									if (!$_REQUEST['delete']) {
									?>
										<DIV CLASS='PAGE_OPTIONS'>
											<br><br>Are you sure you want to delete this Manufacturer?<br><br>
											<strong>THIS CANNOT BE UNDONE!</strong><BR><br>
											<a href="<?php echo $thispage; ?>?id=<?php echo $id; ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
											<a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
										</DIV>
								<?php
									} //IF !DELETE

									if ($_REQUEST['delete'] == "yes") {
										/*-- SECTION: 9995SQL --*/
										$sql = "DELETE FROM loop_warehouse WHERE id='$id' $addl_select_crit ";
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
								} // END IF PROC = "DELETE"
								?>
								<BR>
								<BR>

	</div>
</body>
</html>