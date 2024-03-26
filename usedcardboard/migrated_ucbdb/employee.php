<?php
require("inc/header_session.php");
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
	$sql_debug_mode = 1;
	$thispage	= "employee.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
	/*-----------  NOTE: THE FOLLOWING 4 "allow" VARIABLES SIMPLY DISABLE THE LINKS FOR  ADD/EDIT/VIEW/DELETE. ------------*/
	$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS
	$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).

	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.

	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.

	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

	//if (get_magic_quotes_gpc()) { $addslash="no"; } //AUTO-ADD SLASHES IF MAGIC QUOTES IS OFF
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
		$proc = $_GET["proc"];
		$posting = isset($_GET["posting"]) ? $_GET["posting"] : "";
		if ($proc == "") {
			echo "<br>";
			if ($allowaddnew == "yes") {
		?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">Add a New Employee</a><br><br>
			<?php  }
			$toggle_status = "Active";
			if ($_GET['toggle_status'] == "Active") {
				$toggle_status = "Inactive";
			} else {
				$toggle_status = "Active";
			}
			?>
			<a href='employee.php?posting=yes&toggle_status=<?php echo $toggle_status; ?>'>Active/Inactive</a>
			<br><br>

			<?php
			if ($posting == "yes") {

				/*----------------- PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS ------------*/
				$pagenorecords = 50;  //THIS IS THE PAGE SIZE
				$page = ($_GET['page']);
				if ($page)
					$myrecstart = ($page - 1) * $pagenorecords; 			//first item to display on this page
				else
					$myrecstart = 0;

				/*-- SECTION: 9991SQL --*/
				$searchcrit = isset($_REQUEST["searchcrit"]) ? $_REQUEST["searchcrit"] : "";
				$flag = "";
				$sqlwhere = "";
				if ($searchcrit == "") {
					$flag = "all";
					$sql = "SELECT * FROM ucbdb_employees";
					$sqlcount = "select count(*) as reccount from ucbdb_employees";
				} else {
					//IF THEY TYPED SEARCH WORDS
					$sqlcount = "select count(*) as reccount from ucbdb_employees WHERE (";
					$sql = "SELECT * FROM ucbdb_employees WHERE (";
					$sqlwhere = "
					username like '%$searchcrit%' OR 
					password like '%$searchcrit%' OR 
					name like '%$searchcrit%' OR 
					initials like '%$searchcrit%' OR 
					email like '%$searchcrit%' OR 
					level like '%$searchcrit%' OR 
					status like '%$searchcrit%' OR 
					last_login like '%$searchcrit%' OR 
					option_two like '%$searchcrit%' OR 
					option_three like '%$searchcrit%' 
					"; //FINISH SQL StrING

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
				if ($page == 0)
					$page = 1;					//if no page var is given, default to 1.
				$next = $page + 1;
				/*----------------- FIND OUT HOW MANY RECORDS WE HAVE ------------------*/
				$reccount = 0;
				$resultcount = (db_query($sqlcount)) or die;
				while ($myrowcount = array_shift($resultcount)) {
					$reccount = $myrowcount["reccount"];
				} 
				// 	echo "<div class='NUM_RECS_FOUND'>$reccount Records Found</div>";
			?>
				<?php
				//EXECUTE OUR SQL StrING FOR THE TABLE RECORDS
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
								<div class='TBL_COL_HDR'><a href='employee.php?posting=yes&sorting=yes&sort=username&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>User ID</a></div>
							</td>
							<td>
								<div class='TBL_COL_HDR'><a href='employee.php?posting=yes&sorting=yes&sort=name&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Employee Name</a></div>
							</td>
							<td>
								<div class='TBL_COL_HDR'><a href='employee.php?posting=yes&sorting=yes&sort=initials&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Initials</a></div>
							</td>
							<td>
								<div class='TBL_COL_HDR'><a href='employee.php?posting=yes&sorting=yes&sort=email&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Email Address</a></div>
							</td>
							<td>
								<div class='TBL_COL_HDR'><a href='employee.php?posting=yes&sorting=yes&sort=level&sort_order_pre=<?php echo $sort_order_pre; ?>&page=<?php echo $page; ?>&toggle_status=<?php echo $_REQUEST['toggle_status']; ?>'>Level</a></div>
							</td>
							<td>
								<div class='TBL_COL_HDR'>Status</div>
							</td>
							<td>
								<div class='TBL_COL_HDR'>Options</div>
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
							/*------------------ VIEW SEARCH RESULTS BY PAGE -----------------*/

							/*---------------- BEGIN RESULTS TABLE --------------*/
						?>
							<tr>
								<?php $username = $myrowsel["username"]; ?>
								<td class='<?php echo $shade; ?>'>
									<?php echo $username; ?>
								</td>
								<?php $name = $myrowsel["name"]; ?>
								<td class='<?php echo $shade; ?>'>
									<?php echo $name; ?>
								</td>
								<?php $initials = $myrowsel["initials"]; ?>
								<td class='<?php echo $shade; ?>'>
									<?php echo $initials; ?>
								</td>
								<?php $email = $myrowsel["email"]; ?>
								<td class='<?php echo $shade; ?>'>
									<?php echo $email; ?>
								</td>
								<?php $level = $myrowsel["level"]; ?>
								<td class='<?php echo $shade; ?>'>
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
								<td class='<?php echo $shade; ?>'>
									<?php echo $status; ?> </td>
								<td class='<?php echo $shade; ?>'>
									<div class='PAGE_OPTIONS'>
										<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
											<?php  } ?><?php if ($allowedit == "yes") { ?>
											<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
											<?php  } ?><?php if ($allowdelete == "yes") { ?>
											<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Mark as In-Active</a>
										<?php  } ?>
									</div>
								</td>
							</tr>
						<?php
						} while ($myrowsel = array_shift($result));
						echo "</table>";
						?>
			<?php
					/*-------------- END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS -----------------*/
				} //END PROC == ""

			} //END IF POSTING = YES
			/*-------------------------- END SEARCH SECTION 9991 ---------------------*/
		} // END IF PROC = ""

			?>
			<?php
			/*-------------- ADD NEW RECORDS SECTION -----------------------*/
			if ($proc == "New") {
				echo "<br><a href=\"employee.php?posting=yes\">Back</a><br><br>";
			?>
				<?php
				if ($allowaddnew == "yes") {
				?>
					<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
					<?php  } //END OF IF ALLOW ADDNEW 
				/*-------------- ADD NEW RECORD SECTION 9994 ------------------*/
				if ($proc == "New") {
					echo "<div class='PAGE_STATUS'>Adding Employee</div>";
					$post = $_REQUEST['post'] ? $_REQUEST['post'] : "";
					if ($post == "yes") {
						/*WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM, NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A StrING */
						/* FIX StrING */
						$emp_id = $_POST['emp_id'];
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
						$sql = "INSERT INTO ucbdb_employees ( emp_id,	
						username, password, name, initials, email, level, status, last_login, option_two, option_three $addl_insert_crit ) 
						VALUES ( $emp_id, '$username',  '$password', '$name', '$initials','$email', '$level', '$status', '$last_login',
						'$option_two', '$option_three' $addl_insert_values )";
						if ($sql_debug_mode == 1) {
							echo "<BR>SQL: $sql<BR>";
						}
						//echo $sql;
						$result = db_query($sql);
						if (empty($result)) {
							echo "<div class='SQL_RESULTS'>Record Inserted</div>";
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"employee.php?posting=yes\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=employee.php?posting=yes\" />";
							echo "</noscript>";
							exit;
						} else {
							echo "Error inserting record (9994SQL)";
						}
						//***** END INSERT SQL *****
					} //END IF POST = YES FOR ADDING NEW RECORDS
					/*------------ ADD NEW RECORD (CREATING)------------*/
					if (!$post) { //THEN WE ARE ENTERING A NEW RECORD
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
									<td class='TBL_ROW_HDR'>
										<B>User ID:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="text" NAME="username" SIZE="20">
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Employee ID:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id" size='20'>
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Password:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="password" NAME="password" SIZE="20">
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Employee Name:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="text" NAME="name" SIZE="20">
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Initials:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="text" NAME="initials" SIZE="20">
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Email Address:</B>
									</td>
									<td>
										<INPUT class='TXT_BOX' type="text" NAME="email" SIZE="20">
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Security Level:</B>
									</td>
									<td>
										<SELECT class='TXT_BOX' NAME="level" SIZE="1">
											<option value="1">Administrator</option>
											<option value="2">High Access</option>
											<option value="3">Customer Service</option>
											<option value="4">Minimal Access</option>
										</SELECT>
								</tr>
								<tr>
									<td class='TBL_ROW_HDR'>
										<B>Status:</B>
									</td>
									<td>
										<SELECT class='TXT_BOX' NAME="status" SIZE="1">
											<option value="Active">Active</option>
											<option value="Inactive">Inactive</option>
										</SELECT>
								</tr>
								<tr>
									<td>
									</td>
									<td>
										<INPUT class='BUTTON' TYPE="SUBMIT" VALUE="Submit" NAME="SUBMIT">
										<INPUT class='BUTTON' TYPE="RESET" VALUE="Reset" NAME="RESET">
									</td>
								</tr>
							</table>
							<BR>
						</FORM>
			<?php
					} //END if post=""
					//***** END ADD NEW ENtrY FORM*****
				} //END PROC == NEW
				/*-------------- END ADD SECTION 9994 --------------*/
			} // END IF PROC = "NEW"
			?>
			<?php
			/*------------ SEARCH AND ADD-NEW LINKS ------------*/
			if ($proc == "Edit") {
			?>
				<?php
				if ($allowaddnew == "yes") {
				?>
					<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
				<?php  } //END OF IF ALLOW ADDNEW 
				/*--- EDIT RECORDS SECTION ---*/
				/*--------- EDIT RECORD SECTION 9993 -----------*/
				echo "<br><a href=\"employee.php?posting=yes\">Back</a><br><br>";
				?>
				<?php
				if ($proc == "Edit") {
					$post = isset($_REQUEST['post']) ? $_REQUEST['post'] : "";
					//SHOW THE EDIT RECORD RECORD PAGE
					if ($post == "yes") {
						//THEN WE ARE POSTING UPDATES TO A RECORD
						//***** BEGIN UPDATE SQL***** */
						$id = $_REQUEST["id"];
						/* FIX StrING */
						$emp_id = $_POST['emp_id'];
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
						//SQL StrING
						/*-- SECTION: 9993SQLUPD --*/
						$sql = "UPDATE ucbdb_employees SET emp_id='$emp_id', username='$username', password='$password',name='$name',
 						initials='$initials', email='$email', level='$level',status='$status', last_login='$last_login',option_two='$option_two',
 						option_three='$option_three' $addl_update_crit  WHERE (id='$id') $addl_select_crit ";
						if ($sql_debug_mode == 1) {
							echo "<BR>SQL: $sql<BR>";
						}
						$result = db_query($sql);
						if (empty($result)) {
							echo "<div class='SQL_RESULTS'>Updated</div>";
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"employee.php?posting=yes\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=employee.php?posting=yes\" />";
							echo "</noscript>";
							exit;
						} else {
							echo "Error Updating Record (9993SQLUPD)";
						}
						//***** END UPDATE SQL *****
					} //END IF POST IS YES
					/*------------ EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM ------------*/
					if ($post == "") { //THEN WE ARE EDITING A RECORD
						$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
						echo "<div class='PAGE_STATUS'>Edit Employee</div>";
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
								$username = str_replace("\n", "<br>", $username);
								$password = $myrow["password"];
								$password = str_replace("\n", "<br>", $password);
								$name = $myrow["name"];
								$name = str_replace("\n", "<br>", $name);
								$initials = $myrow["initials"];
								$initials = str_replace("\n", "<br>", $initials);
								$email = $myrow["email"];
								$email = str_replace("\n", "<br>", $email);
								$level = $myrow["level"];
								$level = str_replace("\n", "<br>", $level);
								$status = $myrow["status"];
								$status = str_replace("\n", "<br>", $status);
								$last_login = $myrow["last_login"];
								$last_login = str_replace("\n", "<br>", $last_login);
								$option_two = $myrow["option_two"];
								$option_two = str_replace("\n", "<br>", $option_two);
								$option_three = $myrow["option_three"];
								$option_three = str_replace("\n", "<br>", $option_three);
				?>
								<form method="post" action="<?php echo $thispage; ?>?proc=Edit&post=yes&<?php echo $pagevars; ?>">
									<br>
									<table ALIGN='LEFT'>
										<tr>
											<td class='TBL_ROW_HDR'>
												<B>Employee ID:</B>
											</td>
											<td>
												<INPUT class='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id" value='<?php echo $emp_id; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												User ID:
											</td>
											<td>
												<INPUT TYPE='TEXT' class='TXT_BOX' name='username' value='<?php echo $username; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												Password:
											</td>
											<td>
												<INPUT TYPE='PASSWORD' class='TXT_BOX' name='password' value='<?php echo $password; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												Employee Name:
											</td>
											<td>
												<INPUT TYPE='TEXT' class='TXT_BOX' name='name' value='<?php echo $name; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												Initials:
											</td>
											<td>
												<INPUT TYPE='TEXT' class='TXT_BOX' name='initials' value='<?php echo $initials; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												Email:
											</td>
											<td>
												<INPUT TYPE='TEXT' class='TXT_BOX' name='email' value='<?php echo $email; ?>' size='20'>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												Security Level:
											</td>
											<td>
												<select name="level" class='TXT_BOX' size="1">
													<option value="1">Administrator</option>
													<option value="2">High Access</option>
													<option value="3">Customer Service</option>
													<option value="4">Minimal Access</option>
												</select>
											</td>
										</tr>
										<tr>
											<td class='TBL_ROW_HDR'>
												STATUS:
											</td>
											<td>
												<select name="status" class='TXT_BOX' size="1">
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
				/*------------ END EDIT RECORD SECTION 9993 ------------*/
			} // END IF PROC = "EDIT"
			?>
			<?php
			/*------------- SEARCH AND ADD-NEW LINKS -----------*/
			if ($proc == "View") {
				$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
			?>
				<?php
				if ($allowaddnew == "yes") {
				?>
					<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
					<?php  } //END OF IF ALLOW ADDNEW 
				/*--- VIEW RECORDS SECTION - VIEW SINGLE RECORDS ---*/
				/*------------ VIEW RECORD SECTION 9992 -----------*/
				if ($proc == "View") {
					echo "<div class='PAGE_STATUS'>Viewing Record</div>";
					//***** BEGIN SEARCH RESULTS ***********
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
								<div class='PAGE_OPTIONS'>
									<?php if ($allowview == "yes") { ?><a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&<?php echo $pagevars; ?>">View</a>
										<?php  } //END ALLOWVIEW 
										?><?php if ($allowedit == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Edit&<?php echo $pagevars; ?>">Edit</a>
										<?php  } //END ALLOWEDIT 
										?><?php if ($allowdelete == "yes") { ?>
										<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=Delete&<?php echo $pagevars; ?>">Mark as In Active</a>
									<?php  } //END ALLOWDELETE 
									?><br>
								</div>
								<tr>
									<td class='TBL_ROW_HDR'>USERNAME:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $username; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>PASSWORD:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $password; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>NAME:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $name; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>INITIALS:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $initials; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>EMAIL:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $email; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>LEVEL:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $level; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>STATUS:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $status; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>LAST_LOGIN:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $last_login; ?> </div>
									</td>
								<tr>
									<td class='TBL_ROW_HDR'>OPTION_TWO:</td>
									<td>
										<div class='TBL_ROW_DATA'><?php echo $option_two; ?> </div>
									</td>
					<?php
						} while ($myrowsel = array_shift($result));

						echo "</tr>\n</table>";
					} //IF RESULT
				} //END OF PROC VIEW
				/*------------ END VIEW RECORD SECTION 9992 ------------*/
			} // END IF PROC = "VIEW"
					?>
					<?php
					if ($proc == "Delete") {
						$id = isset($_GET["id"]) ? decrypt_url($_GET["id"]) : "";
						echo "<br><a href=\"employee.php?posting=yes\">Back</a><br><br>";
					?>
						<?php
						if ($allowaddnew == "yes") {
						?>
							<!--<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>-->
						<?php  } //END OF IF ALLOW ADDNEW 
						/*--- DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS ---*/
						/*----------- DELETE RECORD SECTION 9995------------*/
						?>
						<div class='PAGE_STATUS'>Deleting Record</div>
						<?php
						/*-- SECTION: 9995CONFIRM --*/
						if (!$_REQUEST['delete']) {
						?>
							<div class='PAGE_OPTIONS'>
								Are you sure you want to mark as In Active?<BR>
								<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&delete=yes&proc=Delete&<?php echo $pagevars; ?>">Yes</a>
								<a href="<?php echo $thispage; ?>?<?php  /* echo $pagevars; */ ?>posting=yes">No</a>
							</div>
					<?php
						} //IF !DELETE
						if ($_REQUEST['delete'] == "yes") {
							/*-- SECTION: 9995SQL --*/
							$sql = "Update ucbdb_employees set `status` = 'Inactive' where id='$id'";
							$result = db_query($sql);
							if (empty($result)) {
								echo "<div class='SQL_RESULTS'>Successfully Mark as InActive</div>";
								echo "<script type=\"text/javascript\">";
								echo "window.location.href=\"employee.php?posting=yes\";";
								echo "</script>";
								echo "<noscript>";
								echo "<meta http-equiv=\"refresh\" content=\"0;url=employee.php?posting=yes\" />";
								echo "</noscript>";
								exit;
							} else {
								echo "Error Deleting Record (9995SQL)";
							}
						} //END IF $DELETE=YES

						/*------------ END DELETE RECORD SECTION 9995 ------------*/
					} // END IF PROC = "DELETE"
					?>
					<BR>
					<BR>
					</font>
	</div>
</body>

</html>