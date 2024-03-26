<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Configuration - Distribution</title>
</head>

<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";

	echo "<Font Face='arial' size='2'>";

	$sql_debug_mode = 0;

	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= "warehouse_list.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
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
	<strong>
		<div>
			<?php include("inc/header.php"); ?>
		</div>
		<div class="main_data_css">
	</strong>
	<?php
	$proc = $_REQUEST['proc'];
	if ($proc == "") {
		if ($allowaddnew == "yes") {
			//echo "<a href=\"index.php\">Home</a><br><br>";
	?>
			<br>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br><br>
			<?php  }
		if ($_REQUEST['posting'] == "yes") {
			$myrecstart = 0;
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
			$pagenorecords = 50;  //THIS IS THE PAGE SIZE
			//IF NO PAGE
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
				$sql = "SELECT * FROM ucbdb_warehouse";
				$sqlcount = "select count(*) as reccount from ucbdb_warehouse";
			} else {
				$sqlcount = "select count(*) as reccount from ucbdb_warehouse WHERE (";
				$sql = "SELECT * FROM ucbdb_warehouse WHERE (";
				$sqlwhere = " 
				rank like '%$searchcrit%' OR 
				distribution_center like '%$searchcrit%' OR 
				address_one like '%$searchcrit%' OR 
				address_two like '%$searchcrit%' OR 
				city like '%$searchcrit%' OR 
				state like '%$searchcrit%' OR 
				zip like '%$searchcrit%' OR 
				country like '%$searchcrit%' OR 
				phone like '%$searchcrit%' OR 
				fax like '%$searchcrit%' OR 
				eod_time like '%$searchcrit%' OR 
				other1 like '%$searchcrit%' OR 
				other2 like '%$searchcrit%' "; //FINISH SQL STRING

			} //END IF SEARCHCRIT = "";

			if ($flag == "all") {
				$sql = $sql . " WHERE (1=1) $addl_select_crit ORDER BY rank LIMIT $myrecstart, $pagenorecords";
				$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
			} else {
				$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				$sql = $sql . $sqlwhere . ") $addl_select_crit ORDER BY rank LIMIT $myrecstart, $pagenorecords";
			}

			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}


			$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
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
			echo "<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>";
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

					}
					//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
					$result = db_query($sql);
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					if ($myrowsel = array_shift($result)) {
						$id = $myrowsel["id"];

						echo "<TABLE WIDTH='100%'>";
						echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>DISTRIBUTION CENTER SETUP</strong></td></tr>";

						echo "	<TR>";
						//echo "		<TD><DIV CLASS='TBL_COL_HDR'>RANK</DIV></TD>"; 
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Rank</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Nickname</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Legal Name</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address </DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>City </DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Country</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Fax</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>EOD Time</DIV></TD>";
						echo "<TD><DIV CLASS='TBL_COL_HDR'>Option</DIV></TD>";
						//echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER1</DIV></TD>"; 
						//echo "		<TD><DIV CLASS='TBL_COL_HDR'>OTHER2</DIV></TD>"; echo "\n\n		</TR>";
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

							<?php 
							 	$rank = $myrowsel["rank"];  //old code
								$prev_rank = $rank //added by bhavna
							?>

							<TD CLASS='<?php echo $shade; ?>'><?php if ($prev_rank > 0) { ?>
									<a href="warehouse_list_update_priority.php?id=<?php echo encrypt_url($id); ?>&prank=<?php echo $prev_rank ?>"><!-- Move Up --><strong>
											<font size="+1">^</font>
										</strong></a><?php  } ?>
							</TD>
							<?php $distribution_center = $myrowsel["distribution_center"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $distribution_center; ?>
							</TD>
							<?php $legalname = $myrowsel["legalname"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $legalname; ?>
							</TD>
							<?php $address_one = $myrowsel["address_one"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $address_one; ?>
							</TD>
							<?php $address_two = $myrowsel["address_two"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $address_two; ?>
							</TD>
							<?php $city = $myrowsel["city"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $city; ?>
							</TD>
							<?php $state = $myrowsel["state"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $state; ?>
							</TD>
							<?php $zip = $myrowsel["zip"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $zip; ?>
							</TD>
							<?php $country = $myrowsel["country"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $country; ?>
							</TD>
							<?php $phone = $myrowsel["phone"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $phone; ?>
							</TD>
							<?php $fax = $myrowsel["fax"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $fax; ?>
							</TD>
							<?php $eod_time = $myrowsel["eod_time"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $eod_time; ?>
							</TD>
							<TD CLASS='<?php echo $shade; ?>'>
								<DIV CLASS='PAGE_OPTIONS'>
									<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
										<?php  } ?><?php if ($allowedit == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
										<?php  } ?><?php if ($allowdelete == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
									<?php  } ?>
								</DIV>
							</TD>
							</TR>
							<?php
							$prev_rank = $rank;
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
					} // END IF PROC = ""
				}
			}	?>
					<?php
					if ($proc == "New") {
						echo "<br><a href=\"warehouse_list.php?posting=yes\">Back</a><br><br>";
					?>
						<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
						<?php
						if ($allowaddnew == "yes") {
						?>
							<!-- <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br><br>-->
							<?php  } //END OF IF ALLOW ADDNEW 

						if ($proc == "New") {
							echo "<DIV CLASS='PAGE_STATUS'>Adding Record</DIV>";
							if ($_REQUEST['post'] == "yes") {
								/* FIX STRING */
								$rank = FixString($_REQUEST['rank']);
								$distribution_center = FixString($_REQUEST['distribution_center']);
								$legalname = FixString($_REQUEST['legalname']);
								$address_one = FixString($_REQUEST['address_one']);
								$address_two = FixString($_REQUEST['address_two']);
								$city = FixString($_REQUEST['city']);
								$state = FixString($_REQUEST['state']);
								$zip = FixString($_REQUEST['zip']);
								$country = FixString($_REQUEST['country']);
								$phone = FixString($_REQUEST['phone']);
								$fax = FixString($_REQUEST['fax']);
								$eod_time = FixString($_REQUEST['eod_time']);
								$other1 = FixString($_REQUEST['other1']);
								$other2 = FixString($_REQUEST['other2']);
								/*-- SECTION: 9994SQL --*/
								$sql = "INSERT INTO ucbdb_warehouse ( rank, distribution_center,legalname,address_one, address_two, city, state, zip, country, phone, fax, eod_time, other1, other2
								$addl_insert_crit ) VALUES ( '$rank', '$distribution_center','$legalname','$address_one','$address_two','$city','$state','$zip','$country',
								'$phone', '$fax','$eod_time', '$other1','$other2' $addl_insert_values )";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
									header('Location: warehouse_list.php?posting=yes');
								} else {
									echo "Error inserting record (9994SQL)";
								}
								//***** END INSERT SQL *****
							} //END IF POST = YES FOR ADDING NEW RECORDS
							if (!$_REQUEST['post']) { //THEN WE ARE ENTERING A NEW RECORD
							?>
								<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
									<?php
									$qry = db_query("SELECT MAX(rank) as maxrank FROM ucbdb_warehouse");
									$result_max = array_shift($qry);
									$themax = $result_max["maxrank"] + 1;


									?>
									<input type="hidden" value="<?php echo $themax; ?>" name="rank">
									<TABLE ALIGN='LEFT'>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Distribution Center:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="distribution_center" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Legal Name:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="legalname" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Address:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="address_one" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Address Two:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="address_two" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>City:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="city" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>State:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="state" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>ZIP:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="zip" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Country</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="country" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Phone:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="phone" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Fax:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="fax" SIZE="20">
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>EOD Time</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="eod_time" SIZE="20">
										</TR>
										<TR>
											<TD>
											</TD>
											<TD>
												<?php
												$qry = db_query("SELECT MAX(rank) as maxrank FROM ucbdb_warehouse");
												$result_max = array_shift($qry);
												$themax = $result_max["maxrank"] + 1;


												?>
												<input type="hidden" value="<?php echo $themax; ?>" name="rank">
												<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">

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
						echo "<br><a href=\"warehouse_list.php?posting=yes\">Back</a><br><br>";
					?>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<!-- <a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br> -->
						<?php  } //END OF IF ALLOW ADDNEW 
						?>

						<?php

						if ($proc == "Edit") {
							if ($_REQUEST['post'] == "yes") {
								/* FIX STRING */
								$id = $_REQUEST['id'];
								$rank = FixString($_REQUEST['rank']);
								$distribution_center = FixString($_REQUEST['distribution_center']);
								$legalname = FixString($_REQUEST['legalname']);
								$address_one = FixString($_REQUEST['address_one']);
								$address_two = FixString($_REQUEST['address_two']);
								$city = FixString($_REQUEST['city']);
								$state = FixString($_REQUEST['state']);
								$zip = FixString($_REQUEST['zip']);
								$country = FixString($_REQUEST['country']);
								$phone = FixString($_REQUEST['phone']);
								$fax = FixString($_REQUEST['fax']);
								$eod_time = FixString($_REQUEST['eod_time']);
								$other1 = FixString($_REQUEST['other1']);
								$other2 = FixString($_REQUEST['other2']);
								$sql = "UPDATE ucbdb_warehouse SET rank='$rank', distribution_center='$distribution_center', legalname='$legalname',
 								address_one='$address_one', address_two='$address_two', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', 
								fax='$fax', eod_time='$eod_time', other1='$other1', other2='$other2' $addl_update_crit WHERE (id='$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
									header('Location: warehouse_list.php?posting=yes');
								} else {
									echo "Error Updating Record (9993SQLUPD)";
								}
								//***** END UPDATE SQL *****
							} //END IF POST IS YES

							if ($_REQUEST['post'] == "") { //THEN WE ARE EDITING A RECORD
								echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
								$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
								$sql = "SELECT * FROM ucbdb_warehouse WHERE (id = '$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
								if ($myrow = array_shift($result)) {
									do {
										$rank = $myrow["rank"];
										$rank = preg_replace("/(\n)/", "<br>", $rank);
										$distribution_center = $myrow["distribution_center"];
										$distribution_center = preg_replace("/(\n)/", "<br>", $distribution_center);
										$legalname = $myrow["legalname"];
										$legalname = preg_replace("/(\n)/", "<br>", $legalname);
										$address_one = $myrow["address_one"];
										$address_one = preg_replace("/(\n)/", "<br>", $address_one);
										$address_two = $myrow["address_two"];
										$address_two = preg_replace("/(\n)/", "<br>", $address_two);
										$city = $myrow["city"];
										$city = preg_replace("/(\n)/", "<br>", $city);
										$state = $myrow["state"];
										$state = preg_replace("/(\n)/", "<br>", $state);
										$zip = $myrow["zip"];
										$zip = preg_replace("/(\n)/", "<br>", $zip);
										$country = $myrow["country"];
										$country = preg_replace("/(\n)/", "<br>", $country);
										$phone = $myrow["phone"];
										$phone = preg_replace("/(\n)/", "<br>", $phone);
										$fax = $myrow["fax"];
										$fax = preg_replace("/(\n)/", "<br>", $fax);
										$eod_time = $myrow["eod_time"];
										$eod_time = preg_replace("/(\n)/", "<br>", $eod_time);
										$other1 = $myrow["other1"];
										$other1 = preg_replace("/(\n)/", "<br>", $other1);
										$other2 = $myrow["other2"];
										$other2 = preg_replace("/(\n)/", "<br>", $other2);
						?>
										<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
											<br>
											<TABLE ALIGN='LEFT'>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Distribution Center:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='distribution_center' value='<?php echo $distribution_center; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Legal Name:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='legalname' value='<?php echo $legalname; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Address:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='address_one' value='<?php echo $address_one; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Address Two:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='address_two' value='<?php echo $address_two; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														City:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='city' value='<?php echo $city; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														State:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='state' value='<?php echo $state; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Zip:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='zip' value='<?php echo $zip; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Country:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='country' value='<?php echo $country; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Phone:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='phone' value='<?php echo $phone; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Fax:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='fax' value='<?php echo $fax; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														EOD Time:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='eod_time' value='<?php echo $eod_time; ?>' size='20'>
													</td>
												</tr>
												<tr>
													<td>
													</td>
													<td>
														<?php $id = $myrow["id"]; ?>
														<input type="hidden" value="<?php echo $id; ?>" name="id">
														<input type="hidden" value="<?php echo $rank; ?>" name="rank">
														<input CLASS="BUTTON" type="submit" value="Save" name="submit">
													</td>
												</tr>
											</table>
											<center>
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
						$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
					?>
						<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
							<?php  } //END OF IF ALLOW ADDNEW 
						if ($proc == "View") {
							echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
							$sql = "SELECT * FROM ucbdb_warehouse WHERE id='$id' $addl_select_crit ";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql);
							if ($myrowsel = array_shift($result)) {
								do {
									$id = $myrowsel["id"];
									$rank = $myrowsel["rank"];
									$distribution_center = $myrowsel["distribution_center"];
									$legalname = $myrowsel["legalname"];
									$address_one = $myrowsel["address_one"];
									$address_two = $myrowsel["address_two"];
									$city = $myrowsel["city"];
									$state = $myrowsel["state"];
									$zip = $myrowsel["zip"];
									$country = $myrowsel["country"];
									$phone = $myrowsel["phone"];
									$fax = $myrowsel["fax"];
									$eod_time = $myrowsel["eod_time"];
									$other1 = $myrowsel["other1"];
									$other2 = $myrowsel["other2"];
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
											<TD CLASS='TBL_ROW_HDR'>RANK:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $rank; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>DISTRIBUTION_CENTER:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $distribution_center; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>LEGAL NAME:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $legalname; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>ADDRESS_ONE:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $address_one; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>ADDRESS_TWO:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $address_two; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>CITY:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $city; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>STATE:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $state; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>ZIP:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $zip; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>COUNTRY:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $country; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>PHONE:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $phone; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>FAX:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $fax; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>EOD_TIME:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $eod_time; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>OTHER1:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $other1; ?> </DIV>
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
								echo "<br><a href=\"warehouse_list.php?posting=yes\">Back</a><br><br>";
								$id  = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
							?>
								<?php

								if ($allowaddnew == "yes") {
								?>
								<?php  } //END OF IF ALLOW ADDNEW
								?>
								<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
								<?php
								/*-- SECTION: 9995CONFIRM --*/
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
									$sql = "DELETE FROM ucbdb_warehouse WHERE id='$id' $addl_select_crit ";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql);
									if (empty($result)) {
										echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
										header('Location: warehouse_list.php?posting=yes');
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