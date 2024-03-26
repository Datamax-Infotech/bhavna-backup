<?php
require("inc/header_session.php");
db();
?>
<!DOCTYPE html>
<html>

<head>
	<title>DASH - Configuration - Employee Setup</title>
	<script>
		function numbersonly(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			} else {
				// If the number field already has . then don't allow to enter . again.
				if (evt.target.value.search(/\./) > -1 && charCode == 46) {
					return false;
				}
				return true;
			}
		}
	</script>
</head>

<body>
	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	echo "<Font Face='arial' size='2'>";
	$sql_debug_mode = 0;
	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= "employee_test_b2b.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	/*------------------
	NOTE: THE FOLLOWING 4 "allow" VARIABLES SIMPLY DISABLE THE LINKS FOR 
	ADD/EDIT/VIEW/DELETE.
	-----------*/
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
	$proc = isset($_GET['proc']) ? $_GET['proc'] : "";
	/*-------------- ADD NEW LINK -----------------------*/
	if ($proc == "") {
		/*----------------------- ADD NEW LINK ------------------------*/
		echo "<a href=\"index.php\">Home</a><br><br>";
		if ($allowaddnew == "yes") {
	?>
			<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">Add a New Employee</a><br><br>
		<?php  }
		$toggle_status = "Active";

		if ($_GET['toggle_status'] == "Active") {
			$toggle_status = "Inactive";
		} else {
			$toggle_status = "Active";
		} ?>
		<a href='employee_test_b2b.php?posting=yes&toggle_status=<?php echo $toggle_status; ?>'>Active/Inactive</a>

		<?php

		/*------------ IF THEY ARE POSTING TO THE SEARCH PAGE (SHOW SEARCH RESULTS) -----*/
		$posting = isset($_GET['posting']) ? $_GET['posting'] : "";
		if ($posting == "yes") {
			/*----------- PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -----*/
			$pagenorecords = 50;  //THIS IS THE PAGE SIZE
			//IF NO PAGE

			$page = ($_GET['page']);
			if ($page)
				$myrecstart = ($page - 1) * $pagenorecords; 			//first item to display on this page
			else
				$myrecstart = 0;
			/*-- SECTION: 9991SQL --*/
			$sqlwhere = "";
			$flag = "";
			$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit'] : "";
			if ($searchcrit == "") {
				$flag = "all";
				$sql = "SELECT * FROM ucbdb_employees";
				$sqlcount = "select count(*) as reccount from ucbdb_employees";
			} else {
				//IF THEY TYPED SEARCH WORDS
				$sqlcount = "select count(*) as reccount from ucbdb_employees WHERE (";
				$sql = "SELECT * FROM ucbdb_employees WHERE (";
				$sqlwhere = "username like '%$searchcrit%' OR 
				password like '%$searchcrit%' OR 
				name like '%$searchcrit%' OR 
				initials like '%$searchcrit%' OR 
				email like '%$searchcrit%' OR 
				level like '%$searchcrit%' OR 
				status like '%$searchcrit%' OR 
				last_login like '%$searchcrit%' OR 
				option_two like '%$searchcrit%' OR 
				option_three like '%$searchcrit%' 
				"; //FINISH SQL STRING

				//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
				//IF YOU LEFT THE LAST 
				//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
				//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

			} //END IF SEARCHCRIT = "";

			if ($flag == "all") {
				if (isset($_REQUEST["sorting"]) == 'yes') {
					$sql = $sql . " WHERE (1=1) and status='" . $toggle_status . "' order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . " "; //LIMIT $myrecstart, $pagenorecords
					$sqlcount = $sqlcount  . " WHERE (1=1) and status='" . $toggle_status . "' order by " . $_REQUEST["sort"] . " " . $_REQUEST["sort_order_pre"] . " "; //LIMIT $myrecstart, $pagenorecords
					//echo $sql;
				} else {
					$sql = $sql . " WHERE (1=1) and status='" . $toggle_status . "' "; //LIMIT $myrecstart, $pagenorecords
					//echo $sql ."*";
					$sqlcount = $sqlcount  . " WHERE (1=1) and status='" . $toggle_status . "' "; //LIMIT $myrecstart, $pagenorecords
				}
			} else {
				$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit "; //LIMIT $myrecstart, $pagenorecords
				$sql = $sql . $sqlwhere . ") $addl_select_crit "; //LIMIT $myrecstart, $pagenorecords
			}

			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}

			//SET PAGE
			/*if ($page == 0) {
				$page = 1;
				} else {
				$page = ($page + 1);
				}
			*/
			if ($page == 0)
				$page = 1;					//if no page var is given, default to 1.
			$next = $page + 1;

			/*------------ FIND OUT HOW MANY RECORDS WE HAVE -----*/
			$reccount =  0;
			$resultcount = (db_query($sqlcount));
			while ($myrowcount = array_shift($resultcount)) {
				$reccount = $myrowcount["reccount"];
			} 

			// 	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
			echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";
			/*------------ PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -----*/
			//if ($reccount > 50) {
			//$ttlpages = ($reccount / 50);
			//if ($page < $ttlpages) {
		?>
			<?php
			//} //END IF AT LAST PAGE
			//} //END IF RECCOUNT > 10

			//if ($page > 1) { 
			//$newpage = $page - 1;	
			?>
			<?php
			//} //IF NEWPAGE != -1
			/*------------ END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -----*/
			//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
			$result = db_query($sql);
			if ($sql_debug_mode == 1) {
				echo "<BR>SQL: $sql<BR>";
			}
			if ($myrowsel = array_shift($result)) {
				$id = $myrowsel["id"];

				$sort_order_pre = "ASC";
				if ($_GET['sort_order_pre'] == "ASC") {
					$sort_order_pre = "DESC";
				} else {
					$sort_order_pre = "ASC";
				}

			?>
				<table WIDTH='780'>
					<tr align='middle'>
						<td colspan='7' class='style24' style='height: 16px'><strong>EMPLOYEE SETUP</strong></td>
					</tr>
					<tr>

						<td>
							<DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=username&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>User ID</a></DIV>
						</td>
						<!--echo "	<td><DIV CLASS='TBL_COL_HDR'>PASSWORD</DIV></td>"; -->
						<td>
							<DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=name&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Employee Name</a></DIV>
						</td>
						<td>
							<DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=initials&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Initials</a></DIV>
						</td>
						<td>
							<DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=email&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Email Address</a></DIV>
						</td>
						<td>
							<DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=level&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Level</a></DIV>
						</td>
						<td>
							<DIV CLASS='TBL_COL_HDR'>Status</DIV>
						</td>
						<!--echo "	<td><DIV CLASS='TBL_COL_HDR'>LAST_LOGIN</DIV></td>"; -->
						<!--echo "	<td><DIV CLASS='TBL_COL_HDR'>OPTION_TWO</DIV></td>";  -->
						<!--echo "	<td><DIV CLASS='TBL_COL_HDR'>OPTION_THREE</DIV></td>";  -->
						<td>
							<DIV CLASS='TBL_COL_HDR'>Options</DIV>
						</td>
					</tr>
					<?php
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
						/*----------- VIEW SEARCH RESULTS BY PAGE -----*/

						/*--------------- BEGIN RESULTS TABLE -------------------*/
					?>
						<tr>
							<?php $username = $myrowsel["username"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $username; ?>
							</td>
							<?php $name = $myrowsel["name"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $name; ?>
							</td>
							<?php $initials = $myrowsel["initials"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $initials; ?>
							</td>
							<?php $email = $myrowsel["email"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $email; ?>
							</td>
							<?php $level = $myrowsel["level"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php switch ($level) {
									case "1":
										echo "Administrator";
										break;
									case "2":
										echo "High Access";
										break;
									case "3":
										echo "Customer Service";
										break;
									case "4":
										echo "Minimal Access";
										break;
								} ?>
							</td>
							<?php $status = $myrowsel["status"]; ?>
							<td CLASS='<?php echo $shade; ?>'>
								<?php echo $status; ?>
							</td>
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
						</tr>
					<?php
					} while ($myrowsel = array_shift($result));
					echo "</table>";
					?>

				
		<?php
				/*----------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS-----*/
			} //END PROC == ""
		} //END IF POSTING = YES
		/*------------- END SEARCH SECTION 9991 -------------------*/
	} // END IF PROC = ""
		?>
		<?php
		/*----------------- ADD NEW RECORDS SECTION ----------*/
		if ($proc == "New") {
			echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
		?>
			<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
			<?php
			if ($allowaddnew == "yes") {
			?>
				<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
				<?php  } //END OF IF ALLOW ADDNEW 
			/*------------------- ADD NEW RECORD SECTION 9994 -------------*/
			if ($proc == "New") {
				echo "<DIV CLASS='PAGE_STATUS'>Adding Employee</DIV>";
				$post = isset($_POST['post']) ? $_POST['post'] : "";
				if ($post == "yes") {
					/*
				WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
				NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING */
					/* FIX STRING */
					$emp_id = FixString($_POST['emp_id']);
					$username = FixString($_POST['username']);
					$password = FixString($_POST['password']);
					$name = FixString($_POST['name']);
					$initials = FixString($_POST['initials']);
					$email = FixString($_POST['email']);
					$level = FixString($_POST['level']);
					$status = FixString($_POST['status']);
					$last_login = FixString($_POST['last_login']);
					$option_two = FixString($_POST['option_two']);
					$option_three = FixString($_POST['option_three']);
					/*-- SECTION: 9994SQL --*/
					$sql = "INSERT INTO ucbdb_employees 
						(emp_id,	username, password, name, initials, email, level, status, last_login, option_two, option_three $addl_insert_crit ) 
						VALUES ($emp_id, '$username','$password', '$name', '$initials', '$email', '$level', '$status', '$last_login', '$option_two', '$option_three' $addl_insert_values )";
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					//echo $sql;
					$result = db_query($sql);
					if (empty($result)) {
						echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
						header('Location: employee_test_b2b.php?posting=yes');
					} else {
						echo "Error inserting record (9994SQL)";
					}
					//***** END INSERT SQL *****
				} //END IF POST = YES FOR ADDING NEW RECORDS
				/*----------------- ADD NEW RECORD (CREATING) ------------------*/
				if ($post == "") { //THEN WE ARE ENTERING A NEW RECORD
					//SHOW THE ADD RECORD RECORD DATA INPUT FORM
					/*-- SECTION: 9994FORM --*/
				?>
					<script type="text/javascript">
						window.onload = function init() {
							document.newpep.username.focus();
						}
					</script>
					<FORM METHOD="POST" name="newpep" ACTION="<?php echo $thispage; ?>?proc=New&post=yes&<?php echo $pagevars; ?>">
						<table ALIGN='LEFT'>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>User ID:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="text" NAME="username" SIZE="20">
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Employee ID:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id" size='20'>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Password:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="password" NAME="password" SIZE="20">
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Employee Name:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="text" NAME="name" SIZE="20">
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Initials:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="text" NAME="initials" SIZE="20">
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Email Address:</B>
								</td>
								<td>
									<INPUT CLASS='TXT_BOX' type="text" NAME="email" SIZE="20">
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Security Level:</B>
								</td>
								<td>
									<SELECT CLASS='TXT_BOX' NAME="level" SIZE="1">
										<option value="1">Administrator</option>
										<option value="2">High Access</option>
										<option value="3">Customer Service</option>
										<option value="4">Minimal Access</option>
									</SELECT>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>
									<B>Status:</B>
								</td>
								<td>
									<SELECT CLASS='TXT_BOX' NAME="status" SIZE="1">
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</SELECT>
							</tr>

							<tr>
								<td>
								</td>
								<td>
									<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="Submit" NAME="SUBMIT">
									<INPUT CLASS='BUTTON' TYPE="RESET" VALUE="Reset" NAME="RESET">
								</td>
							</tr>
						</table>
						<BR>
					</FORM>

		<?php
				} //END if post=""
				//***** END ADD NEW ENTRY FORM*****
			} //END PROC == NEW


			/*----------------- END ADD SECTION 9994 ----------------------*/
		} // END IF PROC = "NEW"
		?>
		<?php
		/*--------------------------- SEARCH AND ADD-NEW LINKS ----------------------------*/

		if ($proc == "Edit") {
		?>
			<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
			<?php
			if ($allowaddnew == "yes") {
			?>
				<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
			<?php  } //END OF IF ALLOW ADDNEW 

			/*------------------ EDIT RECORDS SECTION -----------*/
			/*--------------------------- EDIT RECORD SECTION 9993 --------------------*/
			echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
			?>
			<?php
			if ($proc == "Edit") {
				//SHOW THE EDIT RECORD RECORD PAGE
				//******************************************************************//
				$post = isset($_POST['post']) ? $_POST['post'] : "";
				if ($post == "yes") {
					$id = $_POST['id'];
					//THEN WE ARE POSTING UPDATES TO A RECORD
					//***** BEGIN UPDATE SQL*****
					//REPLACE THE FIELD CONTENTS SO THEY DON'T MESS UP YOUR QUERY
					//NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
					/* FIX STRING */
					$emp_id = FixString($_POST['emp_id']);
					$username = FixString($_POST['username']);
					$password = FixString($_POST['password']);
					$name = FixString($_POST['name']);
					$initials = FixString($_POST['initials']);
					$email = FixString($_POST['email']);
					$level = FixString($_POST['level']);
					$status = FixString($_POST['status']);
					$last_login = FixString($_POST['last_login']);
					$option_two = FixString($_POST['option_two']);
					$option_three = FixString($_POST['option_three']);
					//SQL STRING
					/*-- SECTION: 9993SQLUPD --*/
					$sql = "UPDATE ucbdb_employees SET emp_id='$emp_id', username='$username', password='$password',name='$name',
 					initials='$initials', email='$email', level='$level', status='$status', last_login='$last_login', option_two='$option_two',
					option_three='$option_three' $addl_update_crit  WHERE (id='$id') $addl_select_crit ";
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					$result = db_query($sql);
					if (empty($result)) {
						echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
						header('Location: employee_test_b2b.php?posting=yes');
					} else {
						echo "Error Updating Record (9993SQLUPD)";
					}
					//***** END UPDATE SQL *****
				} //END IF POST IS YES

				/*---------------- EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM ---------------*/
				if ($post == "") { //THEN WE ARE EDITING A RECORD
					$id = decrypt_url($_GET['id']);
					echo "<DIV CLASS='PAGE_STATUS'>Edit Employee</DIV>";


					/*-- SECTION: 9993SQLGET --*/
					$sql = "SELECT * FROM ucbdb_employees WHERE (id = '$id') $addl_select_crit ";
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					$result = db_query($sql) or die("Error Retrieving Records (9993SQLGET)");
					if ($myrow = array_shift($result)) {
						do {
							$emp_id = $myrow["emp_id"];
							$emp_id =  preg_replace("/(\n)/", "<br>", $emp_id);
							$username = $myrow["username"];
							$username = preg_replace("/(\n)/", "<br>", $username);
							$password = $myrow["password"];
							$password = preg_replace("/(\n)/", "<br>", $password);
							$name = $myrow["name"];
							$name = preg_replace("/(\n)/", "<br>", $name);
							$initials = $myrow["initials"];
							$initials = preg_replace("/(\n)/", "<br>", $initials);
							$email = $myrow["email"];
							$email = preg_replace("/(\n)/", "<br>", $email);
							$level = $myrow["level"];
							$level = preg_replace("/(\n)/", "<br>", $level);
							$status = $myrow["status"];
							$status = preg_replace("/(\n)/", "<br>", $status);
							$last_login = $myrow["last_login"];
							$last_login = preg_replace("/(\n)/", "<br>", $last_login);
							$option_two = $myrow["option_two"];
							$option_two = preg_replace("/(\n)/", "<br>", $option_two);
							$option_three = $myrow["option_three"];
							$option_three = preg_replace("/(\n)/", "<br>", $option_three);
			?>
							<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
								<br>
								<table ALIGN='LEFT'>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											<B>Employee ID:</B>
										</td>
										<td>
											<INPUT CLASS='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id" value='<?php echo $emp_id; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											User ID:
										</td>
										<td>
											<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='username' value='<?php echo $username; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											Password:
										</td>
										<td>
											<INPUT TYPE='PASSWORD' CLASS='TXT_BOX' name='password' value='<?php echo $password; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											Employee Name:
										</td>
										<td>
											<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='name' value='<?php echo $name; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											Initials:
										</td>
										<td>
											<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='initials' value='<?php echo $initials; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											Email:
										</td>
										<td>
											<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='email' value='<?php echo $email; ?>' size='20'>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											Security Level:
										</td>
										<td>
											<select name="level" CLASS='TXT_BOX' size="1">
												<option value="1">Administrator</option>
												<option value="2">High Access</option>
												<option value="3">Customer Service</option>
												<option value="4">Minimal Access</option>
											</select>
										</td>
									</tr>
									<tr>
										<td CLASS='TBL_ROW_HDR'>
											STATUS:
										</td>
										<td>
											<select name="status" CLASS='TXT_BOX' size="1">
												<option value="Active">Active</option>
												<option value="Active">Active</option>
												<option value="Inactive">Inactive</option>
											</select>
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
								<center>
									<BR>
							</form>
		<?php
						} while ($myrow = array_shift($result));
					} //END IF RESULTS
				} //END IF POST IS "" (THIS IS THE END OF EDITING A RECORD)
				//***** END EDIT FORM*****
			} //END PROC == EDIT

			/*---------- END EDIT RECORD SECTION 9993 --------------------*/
		} // END IF PROC = "EDIT"
		?>
		<?php
		/*------------------- SEARCH AND ADD-NEW LINKS ------------------------*/

		if ($proc == "View") {
			$id = isset($_GET['id']) ? decrypt_url($_GET['id']) : "";
		?>
			<!--<a href="<?php echo $thispage; ?>?proc=&<?php echo $pagevars; ?>">Search</a><br>-->
			<?php
			if ($allowaddnew == "yes") {
			?>
				<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
				<?php  } //END OF IF ALLOW ADDNEW 

			/*------------------ VIEW RECORDS SECTION - VIEW SINGLE RECORDS -----------*/

			/*--------------------------- VIEW RECORD SECTION 9992 -------------------*/
			if ($proc == "View") {
				echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
				//***** BEGIN SEARCH RESULTS ****************************************************
				//THEN WE ARE SHOWING THE RESULTS OF A SEARCH

				/*-- SECTION: 9992SQL --*/
				//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
				$sql = "SELECT * FROM ucbdb_employees WHERE id='$id' $addl_select_crit ";
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				$result = db_query($sql);
				if ($myrowsel = array_shift($result)) {
					do {
						$id = $myrowsel["id"];
						$username = $myrowsel["username"];
						$password = $myrowsel["password"];
						$name = $myrowsel["name"];
						$initials = $myrowsel["initials"];
						$email = $myrowsel["email"];
						$level = $myrowsel["level"];
						$status = $myrowsel["status"];
						$last_login = $myrowsel["last_login"];
						$option_two = $myrowsel["option_two"];
						$option_three = $myrowsel["option_three"];
				?>
						<table>
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
								<td CLASS='TBL_ROW_HDR'>USERNAME:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $username; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>PASSWORD:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $password; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>NAME:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $name; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>INITIALS:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $initials; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>EMAIL:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $email; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>LEVEL:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $level; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>STATUS:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $status; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>LAST_LOGIN:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $last_login; ?> </DIV>
								</td>
							</tr>
							<tr>
								<td CLASS='TBL_ROW_HDR'>OPTION_TWO:</td>
								<td>
									<DIV CLASS='TBL_ROW_DATA'><?php echo $option_two; ?> </DIV>
								</td>
							</tr>
			<?php
					} while ($myrowsel = array_shift($result));
					echo "</table>";
				} //IF RESULT

			} //END OF PROC VIEW
			/*--------------------------- END VIEW RECORD SECTION 9992 --------------------*/
		} // END IF PROC = "VIEW"
			?>
			<?php
			if ($proc == "Delete") {
				echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
			?>
				<?php

				if ($allowaddnew == "yes") {
				?>

				<?php  } //END OF IF ALLOW ADDNEW 

				/*------------------ DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS -----------*/

				/*--------------------------- DELETE RECORD SECTION 9995 -------------------*/
				?>
				<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
				<?php
				$delete = isset($_REQUEST["delete"]) ? $_REQUEST["delete"] : "";
				$id = isset($_REQUEST["id"]) ? decrypt_url($_REQUEST["id"]) : "";
				/*-- SECTION: 9995CONFIRM --*/
				if ($delete == "") {
				?>
					<DIV CLASS='PAGE_OPTIONS'>
						Are you sure you want to delete?<BR>
						<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
						<a href="<?php echo $thispage; ?>?<?php  /* echo $pagevars; */ ?>posting=yes">No</a>
					</DIV>
			<?php
				} //IF !DELETE
				if ($delete == "yes") {
					/*-- SECTION: 9995SQL --*/
					$sql = "DELETE FROM ucbdb_employees WHERE id='$id' $addl_select_crit ";
					if ($sql_debug_mode == 1) {
						echo "<BR>SQL: $sql<BR>";
					}
					$result = db_query($sql);
					if (empty($result)) {
						echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
						header('Location: employee_test_b2b.php?posting=yes');
					} else {
						echo "Error Deleting Record (9995SQL)";
					}
				} //END IF $DELETE=YES
				/*--------------------------- END DELETE RECORD SECTION 9995 --------------------*/
			} // END IF PROC = "DELETE"
			?>
			<BR>
			<BR>
			</Font>
</body>

</html>