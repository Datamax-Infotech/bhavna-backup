<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Configuration - Reason Codes</title>
</head>

<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	error_reporting(E_WARNING | E_PARSE);

	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= 'reason_list.php'; //SET THIS TO THE NAME OF THIS FILE
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
		<?php  } ?>
		<?php

		if ($_REQUEST['posting']  == "yes") {


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
				$sql = "SELECT * FROM ucbdb_reason_code";
				$sqlcount = "select count(*) as reccount from ucbdb_reason_code";
			} else {
				//IF THEY TYPED SEARCH WORDS
				$sqlcount = "select count(*) as reccount from ucbdb_reason_code WHERE (";
				$sql = "SELECT * FROM ucbdb_reason_code WHERE (";
				$sqlwhere = "
					rank like '%$searchcrit%' OR 
					reason like '%$searchcrit%' OR 
					chargeback like '%$searchcrit%' OR 
					other1 like '%$searchcrit%' OR 
					other2 like '%$searchcrit%' 
					"; //FINISH SQL STRING
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
			//SET PAGE
			if ($page == 0) {
				$page = 1;
			} else {
				$page = ($page + 1);
			}
			$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
			if ($reccount == 0) {
				//$resultcount = (db_query($sqlcount)) OR DIE (ThrowError($err_type,$err_descr););
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

					} //IF NEWPAGE != -1
					$result = db_query($sql);
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					if ($myrowsel = array_shift($result)) {
						$id = $myrowsel["id"];
						echo "<TABLE WIDTH='100%'>";
						echo "	<tr align='middle'><td colspan='5' class='style24' style='height: 16px'><strong>ITEMS</strong></td></tr>";
						echo "	<TR>";

						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Rank</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Reason</DIV></TD>";
						echo "		<TD><DIV CLASS='TBL_COL_HDR'>Chargeback</DIV></TD>";
						echo "<TD><DIV CLASS='TBL_COL_HDR'>Options</DIV></TD>";
						echo "\n\n		</TR>";
						do {
							//FORMAT THE OUTPUT OF THE SEARCH
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
							echo "<TR>";
						?>

							<?php $rank = $myrowsel["rank"]; 
							$prev_rank = 0; ?>
							<TD CLASS='<?php echo $shade; ?>'><?php if ($prev_rank > 0) { ?>
									<a href="reason_list_update_priority.php?id=<?php echo encrypt_url($id); ?>&prank=<?php echo $prev_rank ?>"><!-- Move Up --><strong>
											<font size="+1">^</font>
										</strong></a><?php  } ?>
							</TD>
							<?php $reason = $myrowsel["reason"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $reason; ?>
							</TD>
							<?php $chargeback = $myrowsel["chargeback"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $chargeback; ?>
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
					} //END PROC == ""
				} //END IF POSTING = YES\
			} // END IF PROC = ""
					?>
					<?php
					if ($proc == "New") {
						echo "<br><a href=\"reason_list.php?posting=yes\">Back</a><br><br>";
					?>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
							<?php  } //END OF IF ALLOW ADDNEW 

						if ($proc == "New") {
							echo "<DIV CLASS='PAGE_STATUS'>Adding Record</DIV>";
							if ($_REQUEST['post'] == "yes") {
								$rank = FixString($_REQUEST['rank']);
								$reason = FixString($_REQUEST['reason']);
								$chargeback = FixString($_REQUEST['chargeback']);
								$other1 = FixString($_REQUEST['other1']);
								$other2 = FixString($_REQUEST['other2']);
								/*-- SECTION: 9994SQL --*/
								$sql = "INSERT INTO ucbdb_reason_code ( rank, reason, chargeback, other1, other2 $addl_insert_crit ) 
								VALUES ( '$rank', '$reason', '$chargeback', '$other1', '$other2' $addl_insert_values )";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
									header('Location: reason_list.php?posting=yes');
								} else {
									echo "Error inserting record (9994SQL)";
								}
								//***** END INSERT SQL *****
							} //END IF POST = YES FOR ADDING NEW RECORDS
							if (!$_REQUEST['post']) { //THEN WE ARE ENTERING A NEW RECORD
							?>
								<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
									<TABLE ALIGN='LEFT'>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Reason:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' type="text" NAME="reason" SIZE="20">
											</TD>
										</TR>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>
												<B>Chargeback?:</B>
											</TD>
											<TD>
												<INPUT CLASS='TXT_BOX' TYPE="checkbox" NAME="chargeback" VALUE="checked">
											</TD>
										</TR>
										<TR>
											<TD>
											</TD>
											<TD>
												<?php
												$qry = db_query("SELECT MAX(rank) as maxrank FROM ucbdb_reason_code");
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
						$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
						echo "<br><a href=\"reason_list.php?posting=yes\">Back</a><br><br>";
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
								$id = $_REQUEST['id'];
								$rank = FixString($_REQUEST['rank']);
								$reason = FixString($_REQUEST['reason']);
								$chargeback = FixString($_REQUEST['chargeback']);
								$other1 = FixString($_REQUEST['other1']);
								$other2 = FixString($_REQUEST['other2']);

								if ($chargeback == 'on') {
									$chargeback = '1';
								} else {
									$chargeback = '0';
								}
								$sql = "UPDATE ucbdb_reason_code SET  rank='$rank', reason='$reason', chargeback='$chargeback', other1='$other1', other2='$other2'
								$addl_update_crit WHERE (id='$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql);
								if (empty($result)) {
									echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
									header('Location: reason_list.php?posting=yes');
								} else {
									echo "Error Updating Record (9993SQLUPD)";
								}
							} //END IF POST IS YES
							if ($_REQUEST['post'] == "") { //THEN WE ARE EDITING A RECORD
								$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
								echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
								$sql = "SELECT * FROM ucbdb_reason_code WHERE (id = '$id') $addl_select_crit ";
								if ($sql_debug_mode == 1) {
									echo "<BR>SQL: $sql<BR>";
								}
								$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
								if ($myrow = array_shift($result)) {
									do {
										$rank = $myrow["rank"];
										$rank = preg_replace("/(\n)/", "<br>", $rank);
										$reason = $myrow["reason"];
										$reason = preg_replace("/(\n)/", "<br>", $reason);
										$chargeback = $myrow["chargeback"];
										$chargeback = preg_replace("/(\n)/", "<br>", $chargeback);
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
														Reacon:
													</TD>
													<TD>
														<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='reason' value='<?php echo $reason; ?>' size='20'>
													</td>
												</tr>
												<TR>
													<TD CLASS='TBL_ROW_HDR'>
														Chargeback:
													</TD>
													<TD>
														<?php
														if ($chargeback == "1") {
															$chargeback = "checked";
														} //end if chargeback == 1
														?>
														<input type="checkbox" CLASS='TXT_BOX' name="chargeback" <?php echo $chargeback; ?>>
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
					?>
						<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
							<?php  } //END OF IF ALLOW ADDNEW 
						if ($proc == "View") {
							$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
							echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
							//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
							$sql = "SELECT * FROM ucbdb_reason_code WHERE id='$id' $addl_select_crit ";
							if ($sql_debug_mode == 1) {
								echo "<BR>SQL: $sql<BR>";
							}
							$result = db_query($sql);
							if ($myrowsel = array_shift($result)) {
								do {
									$id = $myrowsel["id"];
									$rank = $myrowsel["rank"];
									$reason = $myrowsel["reason"];
									$chargeback = $myrowsel["chargeback"];
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
											<TD CLASS='TBL_ROW_HDR'>REASON:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $reason; ?> </DIV>
											</TD>
										<TR>
											<TD CLASS='TBL_ROW_HDR'>CHARGEBACK:</TD>
											<TD>
												<DIV CLASS='TBL_ROW_DATA'><?php echo $chargeback; ?> </DIV>
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
								$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : 0;
								echo "<br><a href=\"reason_list.php?posting=yes\">Back</a><br><br>";
							?>
								<?php
								if ($allowaddnew == "yes") {
								?>
									<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
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
									$sql = "DELETE FROM ucbdb_reason_code WHERE id='$id' $addl_select_crit ";
									if ($sql_debug_mode == 1) {
										echo "<BR>SQL: $sql<BR>";
									}
									$result = db_query($sql);
									if (empty($result)) {
										echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
										header('Location: reason_list.php?posting=yes');
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