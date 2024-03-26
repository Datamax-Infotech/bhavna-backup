<?php
require("inc/header_session.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>DASH - Configuration - Item List</title>
</head>
<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	$thispage = "item_list.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars = ""; //INSERT ANY "GET" VARIABLES HERE...
	$allowedit = "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew = "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview = "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete = "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
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
	</strong>
		<div class="main_data_css">
	<?php
	/*------ ADD NEW LINK -------*/
	$proc = $_REQUEST['proc'];
	if ($proc == "") {
		if ($allowaddnew == "yes") {
			//echo "<a href=\"index.php\">Home</a><br><br>";
			?>
			<br>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br><br>
		<?php }
		/*--------- BEGIN SEARCH SECTION 9991 ---------*/
		/*-- SECTION: 9991FORM --*/
		?>
		<?php

		if ($_REQUEST['posting'] == "yes") {
			/*------- PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -------*/
			$pagenorecords = 50;  //THIS IS THE PAGE SIZE
			$page = isset($_REQUEST['page'])? $_REQUEST['page'] : 0;
			//IF NO PAGE
			$myrecstart = 0;
			if ($page == 0) {
				$myrecstart = 0;
			} else {
				$myrecstart = ($page * $pagenorecords);
			}
			/*-- SECTION: 9991SQL --*/
			$flag = "";
			$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit'] : "";
			$sqlwhere = "";
			$sqlcount = 0;
			if ($searchcrit == "") {
				$flag = "all";
				$sql = "SELECT * FROM ucbdb_items";
				$sqlcount = "select count(*) as reccount from ucbdb_items";
			} else {
				//IF THEY TYPED SEARCH WORDS
				$sqlcount = "select count(*) as reccount from ucbdb_items WHERE (";
				$sql = "SELECT * FROM ucbdb_items WHERE (";
				$sqlwhere = " rank like '%$searchcrit%' OR  item_name like '%$searchcrit%' "; //FINISH SQL STRING	
			} //END IF SEARCHCRIT = "";
	
			if ($flag == "all") {
				$sql = $sql . " WHERE (1=1) $addl_select_crit ORDER BY rank LIMIT $myrecstart, $pagenorecords";
				$sqlcount = $sqlcount . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
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
			/*-------
			FIND OUT HOW MANY RECORDS WE HAVE
			-------*/
			$reccount = 0;
			if ($reccount == 0) {
				$resultcount = (db_query($sqlcount)) or DIE;
				if ($myrowcount = array_shift($resultcount)) {
					$reccount = $myrowcount["reccount"];
				} //IF RECCOUNT = 0
			}//end if reccount
			//	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
			echo "<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>";
			/*-------
			PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
			-------*/
			if ($reccount > 10) {
				$ttlpages = ($reccount / 10);
				if ($page < $ttlpages) {
					?>
					<HR> <br>
					<A
						HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next
						<?php echo $pagenorecords; ?> Records >>
					</a> <br>
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
				<A
					HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">
					<< Previous <?php echo $pagenorecords; ?> Records
				</a>
				<br>
			<?php

			} //IF NEWPAGE != -1
			/*------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -------*/
			//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
			$result = db_query($sql);
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			if ($myrowsel = array_shift($result)) {
				$id = $myrowsel["id"];
				echo "<table WIDTH='100%'>";
				echo "	<tr align='middle'><td colspan='5' class='style24' style='height: 16px'><strong>ITEMS</strong></td></tr>";
				echo "	<tr>";
				echo "	<tr>";
				echo "		<td><DIV CLASS='TBL_COL_HDR'>Rank</DIV></td>";
				echo "		<td><DIV CLASS='TBL_COL_HDR'>Item Name</DIV></td>";
				echo "		<td><DIV CLASS='TBL_COL_HDR'>Amount</DIV></td>";
				echo "		<td><DIV CLASS='TBL_COL_HDR'>Restock Amount</DIV></td>";
				echo "		<td><DIV CLASS='TBL_COL_HDR'>Options</DIV></td>";
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
					/*------- VIEW SEARCH RESULTS BY PAGE -------*/
					/*------ BEGIN RESULTS TABLE ------*/
					echo "<tr>";
					?>
					<?php $rank = $myrowsel["rank"]; ?>
					<td CLASS='<?php echo $shade; ?>'>
						<?php 
						$prev_rank = $rank;
						if ($prev_rank > 0) { ?>
							<a href="item_list_update_priority.php?id=<?php echo encrypt_url($id); ?>&prank=<?php echo $prev_rank ?>"><!-- Move Up --><strong>
									<font size="+1">^</font>
								</strong></a>
						<?php } ?>
					</td>
					<?php $item_name = $myrowsel["item_name"]; ?>
					<td CLASS='<?php echo $shade; ?>'>
						<?php echo $item_name; ?>
					</td>
					<?php $amount = $myrowsel["amount"]; ?>
					<td CLASS='<?php echo $shade; ?>'>
						<?php
						//echo money_format('%(#10n', $amount); 
						$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
						echo $fmt->formatCurrency($amount, 'USD');
						?>
					</td>
					<?php $restock_amount = $myrowsel["restock_amount"]; ?>
					<td CLASS='<?php echo $shade; ?>'>
						<?php
						//echo money_format('%(#10n', $restock_amount); 
						$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
						echo $fmt->formatCurrency($restock_amount, 'USD');
						?>
					</td>
					<td CLASS='<?php echo $shade; ?>'>
						<DIV CLASS='PAGE_OPTIONS'>
							<?php if ($allowview == "yes") { ?><a
									href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
							<?php } ?>
							<?php if ($allowedit == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
							<?php } ?>
							<?php if ($allowdelete == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
							<?php } ?>
						</DIV>
					</td>
					</tr>
					<?php
					$prev_rank = $rank;
				} while ($myrowsel = array_shift($result));
				echo "</table>";
				/*-------
				PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
				-------*/
				if ($reccount > 10) {
					//IF THERE ARE MORE THAN 10 RECORDS PAGING
					$ttlpages = ($reccount / 10);
					if ($page < $ttlpages) {
						?>
						<HR> <br>
						<A
							HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next
							<?php echo $pagenorecords; ?> Records >>
						</a> <br>
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
					<A
						HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">
						<< Previous <?php echo $pagenorecords; ?> Records
					</a>
					<br>
				<?php
				} //IF NEWPAGE != -1
				/*------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORD -------*/
			} //END PROC == ""
		} //END IF POSTING = YES
		/*---END SEARCH SECTION 9991 ---*/
	}// END IF PROC = ""
	?>
	<?php
	/*---- ADD NEW RECORDS SECTION ----*/
	if ($proc == "New") {
		echo "<a href=\"item_list.php?posting=yes\">Back</a><br><br>";
		?>
		<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
		<?php
		if ($allowaddnew == "yes") {
			?>
			<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
		<?php } //END OF IF ALLOW ADDNEW 
		/*---------- ADD NEW RECORD SECTION 9994 -------*/
		if ($proc == "New") {
			echo "<DIV CLASS='PAGE_STATUS'>Adding Record</DIV>";
			if ($_REQUEST['post'] == "yes") {
				/* FIX STRING */
				$rank = FixString($_REQUEST['rank']);
				$item_name = FixString($_REQUEST['item_name']);
				$amount = FixString($_REQUEST['amount']);
				$restock_amount = FixString($_REQUEST['restock_amount']);
				$other1 = FixString($_REQUEST['other1']);
				$other2 = FixString($_REQUEST['other2']);
				/*-- SECTION: 9994SQL --*/
				$sql = "INSERT INTO ucbdb_items ( rank, item_name, amount, restock_amount, other1, other2 $addl_insert_crit ) 
				VALUES ( '$rank', '$item_name', '$amount', '$restock_amount', '$other1', '$other2' $addl_insert_values )";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$result = db_query($sql);
				if (empty($result)) {
					echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
					header('Location: item_list.php?posting=yes');
				} else {
					echo "Error inserting record (9994SQL)";
				}
			} //END IF POST = YES FOR ADDING NEW RECORDS
			/*------------ ADD NEW RECORD (CREATING) ---------*/
			if (!$_REQUEST['post']) {//THEN WE ARE ENTERING A NEW RECORD
				//SHOW THE ADD RECORD RECORD DATA INPUT FORM
				/*-- SECTION: 9994FORM --*/
				?>
				<FORM METHOD="POST" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
					<table ALIGN='LEFT'>
						<tr>
							<td CLASS='TBL_ROW_HDR'>
								<B>Item Name:</B>
							</td>
							<td>
								<INPUT CLASS='TXT_BOX' type="text" NAME="item_name" SIZE="20">
							</td>
						</tr>
						<tr>
							<td CLASS='TBL_ROW_HDR'>
								<B>Amount:</B>
							</td>
							<td>
								<INPUT CLASS='TXT_BOX' type="text" NAME="amount" SIZE="20">
							</td>
						</tr>
						<tr>
							<td CLASS='TBL_ROW_HDR'>
								<B>Restock Amount:</B>
							</td>
							<td>
								<INPUT CLASS='TXT_BOX' type="text" NAME="restock_amount" SIZE="20">
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td>
								<?php
								$qry = "SELECT MAX(rank) as maxrank FROM ucbdb_items";
								$result = db_query($qry);
								$result_max = array_shift($result);
								$themax = $result_max["maxrank"] + 1;
								?>
								<input type="hidden" value="<?php echo $themax; ?>" name="rank">
								<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
							</td>
						</tr>
					</table>
					<BR>
				</FORM>
			<?php
			} //END if post=""
			//***** END ADD NEW ENTRY FORM*****
		} //END PROC == NEW
		/*--- END ADD SECTION 9994 ---*/
	}// END IF PROC = "NEW"
	?>
	<?php
	/*---- SEARCH AND ADD-NEW LINKS ----*/
	if ($proc == "Edit") {
		echo "<a href=\"item_list.php?posting=yes\">Back</a><br><br>";
		?>
		<?php
		if ($allowaddnew == "yes") {
			?>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
		<?php } //END OF IF ALLOW ADDNEW 
		/*---- EDIT RECORDS SECTION ----*/
		?>
		<?php
		if ($proc == "Edit") {
			$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
			//SHOW THE EDIT RECORD RECORD PAGE
			$post = $_REQUEST['post'];
			if ($_REQUEST['post'] == "yes") {
				/* FIX STRING */
				$rank = FixString($_REQUEST['rank']);
				$item_name = FixString($_REQUEST['item_name']);
				$amount = FixString($_REQUEST['amount']);
				$restock_amount = FixString($_REQUEST['restock_amount']);
				$other1 = FixString($_REQUEST['other1']);
				$other2 = FixString($_REQUEST['other2']);
				//SQL STRING
				$sql = "UPDATE ucbdb_items SET  rank='$rank', item_name='$item_name', amount='$amount', restock_amount='$restock_amount',
 				other1='$other1',other2='$other2' $addl_update_crit  WHERE (id='$id') $addl_select_crit ";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$result = db_query($sql);
				if (empty($result)) {
					echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
					header('Location: item_list.php?posting=yes');
				} else {
					echo "Error Updating Record (9993SQLUPD)";
				}
				//***** END UPDATE SQL *****
			} //END IF POST IS YES
			/*------------- EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM------------*/
			if ($post == "") { //THEN WE ARE EDITING A RECORD
				echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";
				$sql = "SELECT * FROM ucbdb_items WHERE (id = '$id') $addl_select_crit ";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
				if ($myrow = array_shift($result)) {
					do {
						$rank = $myrow["rank"];
						$rank = preg_replace("/(\n)/", "<br>", $rank);
						$item_name = $myrow["item_name"];
						$item_name = preg_replace("/(\n)/", "<br>", $item_name);
						$amount = $myrow["amount"];
						$amount = preg_replace("/(\n)/", "<br>", $amount);
						$restock_amount = $myrow["restock_amount"];
						$restock_amount = preg_replace("/(\n)/", "<br>", $restock_amount);
						$other1 = $myrow["other1"];
						$other1 = preg_replace("/(\n)/", "<br>", $other1);
						$other2 = $myrow["other2"];
						$other2 = preg_replace("/(\n)/", "<br>", $other2);
						?>
						<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
							<br>
							<table ALIGN='LEFT'>
								<tr>
									<td CLASS='TBL_ROW_HDR'>
										Item Name:
									</td>
									<td>
										<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='item_name' value='<?php echo $item_name; ?>' size='20' />
									</td>
								</tr>
								<tr>
									<td CLASS='TBL_ROW_HDR'>
										Amount:
									</td>
									<td>
										<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='amount' value='<?php echo $amount; ?>' size='20'>
									</td>
								</tr>
								<tr>
									<td CLASS='TBL_ROW_HDR'>
										Restock Amount:
									</td>
									<td>
										<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='restock_amount' value='<?php echo $restock_amount; ?>'
											size='20'>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?php $id = $myrow["id"]; ?>

										<input type="hidden" value="<?php echo $rank; ?>" name="rank">
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
	}// END IF PROC = "EDIT"
	?>
	<?php
	/*---- SEARCH AND ADD-NEW LINKS ----*/
	if ($proc == "View") {
		$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
		?>
		<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>
		<?php
		if ($allowaddnew == "yes") {
			?>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
		<?php } //END OF IF ALLOW ADDNEW 
		/*- VIEW RECORD SECTION 9992--------*/
		if ($proc == "View") {
			echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
			/*-- SECTION: 9992SQL --*/
			$sql = "SELECT * FROM ucbdb_items WHERE id='$id' $addl_select_crit ";
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			$result = db_query($sql);
			if ($myrowsel = array_shift($result)) {
				do {
					$id = $myrowsel["id"];
					$rank = $myrowsel["rank"];
					$item_name = $myrowsel["item_name"];
					$amount = $myrowsel["amount"];
					$restock_amount = $myrowsel["restock_amount"];
					$other1 = $myrowsel["other1"];
					$other2 = $myrowsel["other2"];
					?>
					<table>
						<DIV CLASS='PAGE_OPTIONS'>
							<?php if ($allowview == "yes") { ?><a
									href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
							<?php } //END ALLOWVIEW  ?>
							<?php if ($allowedit == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
							<?php } //END ALLOWEDIT  ?>
							<?php if ($allowdelete == "yes") { ?>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Delete</a>
							<?php } //END ALLOWDELETE  ?><br></font>
							<font face="arial" size="2">
						</DIV>
						<tr>
							<td CLASS='TBL_ROW_HDR'>RANK:</td>
							<td>
								<DIV CLASS='TBL_ROW_DATA'>
									<?php echo $rank; ?>
								</DIV>
							</td>
						<tr>
							<td CLASS='TBL_ROW_HDR'>ITEM_NAME:</td>
							<td>
								<DIV CLASS='TBL_ROW_DATA'>
									<?php echo $item_name; ?>
								</DIV>
							</td>
						<tr>
							<td CLASS='TBL_ROW_HDR'>AMOUNT:</td>
							<td>
								<DIV CLASS='TBL_ROW_DATA'>
									<?php echo $amount; ?>
								</DIV>
							</td>
						<tr>
							<td CLASS='TBL_ROW_HDR'>RESTOCK_AMOUNT:</td>
							<td>
								<DIV CLASS='TBL_ROW_DATA'>
									<?php echo $restock_amount; ?>
								</DIV>
							</td>
						<tr>
							<td CLASS='TBL_ROW_HDR'>OTHER1:</td>
							<td>
								<DIV CLASS='TBL_ROW_DATA'>
									<?php echo $other1; ?>
								</DIV>
							</td>
						<?php
				}
				while ($myrowsel = array_shift($result));
				echo "</tr>\n</table>";
			} //IF RESULT
		} //END OF PROC VIEW
		/*---------- END VIEW RECORD SECTION 9992-------------*/
	}// END IF PROC = "VIEW"
	?>
			<?php
			if ($proc == "Delete") {
				$id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
				echo "<a href=\"item_list.php?posting=yes\">Back</a><br><br>";
				?>
				<?php
				if ($allowaddnew == "yes") {
					?>
					<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
				<?php } //END OF IF ALLOW ADDNEW 
				/*-------- DELETE RECORD SECTION 9995 -----------*/
				?>
				<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
				<?php
				/*-- SECTION: 9995CONFIRM --*/
				if (!$_REQUEST['delete']) {
					?>
					<DIV CLASS='PAGE_OPTIONS'>
						Are you sure you want to delete?<BR>
						<a
							href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
						<a href="<?php echo $thispage; ?>?<?php echo $pagevars; ?>">No</a>
					</DIV>
				<?php
				} //IF !DELETE
				if ($_REQUEST['delete'] == "yes") {
					/*-- SECTION: 9995SQL --*/
					$sql = "DELETE FROM ucbdb_items WHERE id='$id' $addl_select_crit ";
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					$result = db_query($sql);
					if (empty($result)) {
						echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
						header('Location: item_list.php?posting=yes');
					} else {
						echo "Error Deleting Record (9995SQL)";
					}
				} //END IF $DELETE=YES
			}// END IF PROC = "DELETE"
			?>
			<BR>

			<BR>
			</Font>
			</div>
</body>

</html>