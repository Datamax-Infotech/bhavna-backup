<?php  
require ("inc/header_session.php");
?>
<!DOCTYPE html>

<html>
<head>
	<title>DASH - Configuration - Employee Setup</title>
	<script>
			function numbersonly(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode;
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) 
			{
				return false;
			} 
			else 
			{
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
/*------------------------------------------------
THE FOLLOWING ALLOWS GLOBALS = OFF SUPPORT
------------------------------------------------*/
	// $_GET VARIABLES
	foreach($_GET as $a=>$b){$$a=$b;} 

	// $_POST VARIABLES
	foreach($_POST as $a=>$b){$$a=$b;} 
/*------------------------------------------------
END GLOBALS OFF SUPPORT
------------------------------------------------*/

echo "<Font Face='arial' size='2'>";

/*---------------------------------------------------------------------------------
TURN DEBUG ON - THIS ALLOWS YOU TO VIEW ALL SQL STATEMENTS AS THEY ARE EXECUTED
$sql_debug_mode=0 --> OFF (NO SQL STATEMENTS WILL BE SHOWN)
$sql_debug_mode=1 --> ON (SQL STATEMENTS WILL BE SHOWN)
---------------------------------------------------------------------------------*/
$sql_debug_mode=0;


/*---------------------------------------------------------------------------------
ERROR REPORTING: TO VIEW ALL ERRORS SET error_reporting TO E_ALL
OTHER ERROR REPORTING OPTIONS (FROM THE PHP.INI FILE):
; error_reporting is a bit-field.  Or each number up to get desired error
; reporting level
; E_ALL             - All errors and warnings
; E_ERROR           - fatal run-time errors
; E_WARNING         - run-time warnings (non-fatal errors)
; E_PARSE           - compile-time parse errors
; E_NOTICE          - run-time notices (these are warnings which often result
;                     from a bug in your code, but it's possible that it was
;                     intentional (e.g., using an uninitialized variable and
;                     relying on the fact it's automatically initialized to an
;                     empty string)
; E_CORE_ERROR      - fatal errors that occur during PHP's initial startup
; E_CORE_WARNING    - warnings (non-fatal errors) that occur during PHP's
;                     initial startup
; E_COMPILE_ERROR   - fatal compile-time errors
; E_COMPILE_WARNING - compile-time warnings (non-fatal errors)
; E_USER_ERROR      - user-generated error message
; E_USER_WARNING    - user-generated warning message
; E_USER_NOTICE     - user-generated notice message
;
; Examples:
;
;   - Show all errors, except for notices
;
;error_reporting = E_ALL & ~E_NOTICE
;
;   - Show only errors
;
;error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR
;
;   - Show all errors except for notices
;
error_reporting  = E_PARSE|E_ERROR|E_WARNING|E_NOTICE; display all errors, warnings and notices
---------------------------------------------------------------------------------*/
error_reporting(E_WARNING|E_PARSE);

//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
$thispage	= $SCRIPT_NAME; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...



/*----------------------------------------------------------------------
NOTE: THE FOLLOWING 4 "allow" VARIABLES SIMPLY DISABLE THE LINKS FOR 
ADD/EDIT/VIEW/DELETE.
----------------------------------------------------------------------*/
$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS


/*----------------------------------------------------------------------
* SEARCH SECTIONS BY CODE-NUMBER (LISTED BELOW). (7/7/02):
   THIS CAN BE VERY USEFUL WHEN IDENTIFYING SECTIONS YOU WISH TO EDIT.

USE THE FOLLOWING CODES IN YOUR SEARCH (TYPICALLY, USE CTRL + F):
9991 - SEARCH SECTION (INCLUDES SEARCH FORM AND SEARCH RESULTS)
	|_ 9991SQL - SQL STATEMENT FOR SEARCH RESULTS
	|_ 9991FORM - FORM USED FOR POSTING SEARCHES
9992 - VIEW RECORD SECTION (VIEWING SINGLE-RECORD)
	|_ 9992SQL - SQL STATEMENT FOR OBTAINING SINGLE-RECORD
9993 - EDIT RECORD SECTION (FORM USED FOR EDITING AND POST RESULTS)
	|_ 9993SQLGET - SQL STATEMENT USED FOR OBTAINING EDIT RECORD
	|_ 9993SQLUPD - SQL STATEMENT USED FOR UPDATING POSTED RECORD
	|_ 9993SQLFORM - USER INPUT FORM FOR EDITING RECORD
9994 - ADD RECORD SECTION
	|_ 9994SQL - SQL STATEMENT USED TO INSERT A RECORD
	|_ 9994FORM - FORM USED TO CREATE NEW RECORDS
9995 - DELETE RECORD SECTION
	|_ 9995SQL - SQL STATEMENT USED TO DELETE A RECORD
	|_ 9995CONFIRM = CONFIRMATION TO DELETE RECORD


* GLOBALLY APPEND CRITERIA TO YOUR SQL STATEMENTS USING THE 
$addl_select AND $addl_insert VARIABLES BELOW. (7/7/02):
USEFUL FOR SPECIFYING USERNAMES, OR OTHER RECORD-LIMITING CRITERIA.
* THE $addl_select_crit WILL BE APPENDED TO ALL SEL/UPD/DEL
STATEMENTS, AS "WHERE" CRITERIA, BEFORE ANY ORDER/LIMIT 
STATEMENTS ARE APPLIED.
	EXAMPLE: $addl_select_crit (NOTE! YOU MUST INCLUDE 'AND')
		$addl_select_crit = "AND (userid='$someusersid' 
							AND create_date='2002-07-07')";

* THE $addl_insert_crit AND $addl_insert_values VARIABLES
WILL BE APPENDED TO ALL INSERT STATEMENTS.
		
	EXAMPLE: $addl_insert_crit (NOTE! YOU MUST INCLUDE A COMMA (,)
				BETWEEN FIELDS AND VALUES)
		$addl_insert_crit = ",userid, create_date";
	EXAMPLE: $addl_insert_values (NOTE! YOU MUST INCLUDE A COMMA (,))
				BETWEEN FIELDS AND VALUES)
		$addl_insert_values = ",'$someusersid','2002-07-07'";
	EXAMPLE: $addl_select_crit (NOTE! YOU MUST ADD AND/OR)
		$addl_select_crit = " AND userid = '$someuserid'"
	EXAMPLE: $addl_update_crit (NOTE! YOU MUST INCLUDE A COMMA (,))
		$addl_update_crit = "',userid='$someuserid'"			
----------------------------------------------------------------------*/
$addl_select_crit = ""; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

/*----------------------------------------------------------------------
NOTE: SOME SERVERS RUN "MAGIC QUOTES" TO FIX STRINGS WHEN INSERTING/
		UPDATING RECORDS.  IF MAGIC QUOTES OPTION IS DISABLED, THE GENERATED
		CODE WILL AUTOMATICALLY FIX STRINGS TO HELP ELIMINATE ERRORS.
----------------------------------------------------------------------*/
$addslash="yes";

/*----------------------------------------------------------------------
FUNCTIONS
----------------------------------------------------------------------*/


/*---------------------------------------------------------------
FUNCTION:			db()
DESCRIPTION:		CREATES DB CONNECTION OBJECT FOR KNEBEL.NET
					PHP CODE GENERATOR CODE.
INPUTS:				NONE
RETURNS:			DATABASE CONNECTION OBJECT
GLOBAL VARIABLES:	NONE
FUNCTIONS CALLED:	NONE
CALLED BY:			
AUTHOR:				KNEBEL
MODIFICATIONS:
	MM/DD/YY - CREATED FUNCTION (KNEBEL.NET)
NOTES:

----------------------------------------------------------------*/
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

/*----------------------------------------
ADD NEW LINK
----------------------------------------*/
if ($proc == "") {

/*----------------------------------------
ADD NEW LINK
----------------------------------------*/

echo "<a href=\"index.php\">Home</a><br><br>";

 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">Add a New Employee</a><br><br>
 <?php  } 
	$toggle_status = "Active";	
	
	if($_GET['toggle_status'] == "Active")
	{
		$toggle_status = "Inactive";
	}else{
		$toggle_status = "Active";
	}?>
	
<a href='employee_test_b2b.php?posting=yes&toggle_status=<?php  echo $toggle_status;?>' >Active/Inactive</a>
<br><br>

<!--	<form method="POST" action="<?php  echo $thispage; ?>?posting=yes&<?php  echo $pagevars; ?>">
  	<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php  echo $searchcrit; ?>">
  	<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1"></P>
	</form>  -->

<?php 

/*----------------------------------------------------------------
IF THEY ARE POSTING TO THE SEARCH PAGE 
(SHOW SEARCH RESULTS)
----------------------------------------------------------------*/
if ($posting == "yes") {


/*----------------------------------------------------------------
PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
$pagenorecords = 50;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	/*if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}*/
	
	$page = ($_GET['page']);
	if($page) 
		$myrecstart = ($page - 1) * $pagenorecords; 			//first item to display on this page
	else
		$myrecstart = 0;	


/*-- SECTION: 9991SQL --*/
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

		"; //FINISH SQL STRING

//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
//IF YOU LEFT THE LAST 
//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

} //END IF SEARCHCRIT = "";

if ($flag == "all") {
	if (isset($_REQUEST["sorting"]) == 'yes')
	{
		$sql = $sql . " WHERE (1=1) and status='".$toggle_status ."' order by " .$_REQUEST["sort"]." ".$_REQUEST["sort_order_pre"]." "; //LIMIT $myrecstart, $pagenorecords
		$sqlcount = $sqlcount  . " WHERE (1=1) and status='".$toggle_status ."' order by " .$_REQUEST["sort"]." ".$_REQUEST["sort_order_pre"]." "; //LIMIT $myrecstart, $pagenorecords
		//echo $sql;
	}
	else
	{
		$sql = $sql . " WHERE (1=1) and status='".$toggle_status ."' "; //LIMIT $myrecstart, $pagenorecords
		//echo $sql ."*";
		$sqlcount = $sqlcount  . " WHERE (1=1) and status='".$toggle_status ."' "; //LIMIT $myrecstart, $pagenorecords
	} 
} 
else 
{
$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit "; //LIMIT $myrecstart, $pagenorecords
$sql = $sql . $sqlwhere . ") $addl_select_crit "; //LIMIT $myrecstart, $pagenorecords
}

if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }



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

/*----------------------------------------------------------------
FIND OUT HOW MANY RECORDS WE HAVE
----------------------------------------------------------------*/

	if ($reccount == 0) 
		{
		//$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError($err_type,$err_descr););
		$resultcount = (db_query($sqlcount,db() )) OR DIE (ThrowError("9991SQLresultcount",$sqlcount));				
		if ($myrowcount = array_shift($resultcount)) 
			{
				$reccount = $myrowcount["reccount"];
			} //IF RECCOUNT = 0
	}//end if reccount

// 	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
	echo "<!--<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>-->";
	

/*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	//if ($reccount > 50) {
	//$ttlpages = ($reccount / 50);
	//if ($page < $ttlpages) {
	?>

<!-- <HR>	<br>
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br>
-->
	<?php  
	//} //END IF AT LAST PAGE
	//} //END IF RECCOUNT > 10

//if ($page > 1) { 
	//$newpage = $page - 1;	
	?>
	<!-- <br>
	<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>
	<br> -->
	<?php  

	//} //IF NEWPAGE != -1
/*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
	$result = db_query($sql,db() );
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	if ($myrowsel = array_shift($result)) {
	$id = $myrowsel["id"];
	
	$sort_order_pre = "ASC";				
	if($_GET['sort_order_pre'] == "ASC")
	{
		$sort_order_pre = "DESC";
	}else{
		$sort_order_pre = "ASC";
	}
	
	?>

           <TABLE WIDTH='780'>
           <tr align='middle'><td colspan='7' class='style24' style='height: 16px'><strong>EMPLOYEE SETUP</strong></td></tr>
	   <TR>

         <TD><DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=username&sort_order_pre=<?php  echo $sort_order_pre;?>&page=<?php  echo $page; ?>&toggle_status=<?php  echo $_REQUEST['toggle_status'];?>' >User ID</a></DIV></TD> 
<!--echo "	<TD><DIV CLASS='TBL_COL_HDR'>PASSWORD</DIV></TD>"; -->
	<TD><DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=name&sort_order_pre=<?php  echo $sort_order_pre;?>&page=<?php  echo $page; ?>&toggle_status=<?php  echo $_REQUEST['toggle_status'];?>' >Employee Name</a></DIV></TD>
	<TD><DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=initials&sort_order_pre=<?php  echo $sort_order_pre;?>&page=<?php  echo $page; ?>&toggle_status=<?php  echo $_REQUEST['toggle_status'];?>' >Initials</a></DIV></TD>
	<TD><DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=email&sort_order_pre=<?php  echo $sort_order_pre;?>&page=<?php  echo $page; ?>&toggle_status=<?php  echo $_REQUEST['toggle_status'];?>' >Email Address</a></DIV></TD>
	<TD><DIV CLASS='TBL_COL_HDR'><a href='employee_test_b2b.php?posting=yes&sorting=yes&sort=level&sort_order_pre=<?php  echo $sort_order_pre;?>&page=<?php  echo $page; ?>&toggle_status=<?php  echo $_REQUEST['toggle_status'];?>' >Level</a></DIV></TD>
	<TD><DIV CLASS='TBL_COL_HDR'>Status</DIV></TD> 
<!--echo "	<TD><DIV CLASS='TBL_COL_HDR'>LAST_LOGIN</DIV></TD>"; -->
<!--echo "	<TD><DIV CLASS='TBL_COL_HDR'>OPTION_TWO</DIV></TD>";  -->
<!--echo "	<TD><DIV CLASS='TBL_COL_HDR'>OPTION_THREE</DIV></TD>";  -->
<TD><DIV CLASS='TBL_COL_HDR'>Options</DIV></TD>
</TR>
<?php 
			do
  			{
  			//FORMAT THE OUTPUT OF THE SEARCH
  			$id = $myrowsel["id"];
  			
  			//SWITCH ROW COLORS
  			switch ($shade)
  			{
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


/*----------------------------------------------------------------
VIEW SEARCH RESULTS BY PAGE
----------------------------------------------------------------*/

/*---------------------------------------
BEGIN RESULTS TABLE
---------------------------------------*/
?>
	<TR>
		
					<?php  $username = $myrowsel["username"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $username; ?> 
			</TD>
<!--				<?php  /* $password = $myrowsel["password"]; */?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $password; ?> 
			</TD> -->
				<?php  $name = $myrowsel["name"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $name; ?> 
			</TD>
				<?php  $initials = $myrowsel["initials"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $initials; ?> 
			</TD>
				<?php  $email = $myrowsel["email"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $email; ?> 
			</TD>
				<?php  $level = $myrowsel["level"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  switch ($level) {
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
			</TD>
				<?php  $status = $myrowsel["status"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $status; ?>  </TD> 
<!--			
				<?php  $last_login = $myrowsel["last_login"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $last_login; ?> 
			</TD>
				<?php  $option_two = $myrowsel["option_two"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $option_two; ?> 
			</TD>
				<?php  $option_three = $myrowsel["option_three"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $option_three; ?> 
			</TD>-->
						<TD CLASS='<?php  echo $shade; ?>'>
				<DIV CLASS='PAGE_OPTIONS'>
				<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
				<?php  } ?><?php  if ($allowedit == "yes") { ?>
				<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
				<?php  } ?><?php  if ($allowdelete == "yes") { ?>
				<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
				<?php  } ?>
				</DIV>
			</TD>
		</TR>
<?php 
			} while ($myrowsel = array_shift($result));
echo "</TABLE>";
/*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	//if ($reccount > 50) {
//IF THERE ARE MORE THAN 10 RECORDS PAGING
	//$ttlpages = ($reccount / 50);
	//if ($page < $ttlpages) {
	?>

<!-- <HR>	<br>
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br>
-->
	<?php  
	//} //END IF AT LAST PAGE
	//} //END IF RECCOUNT > 10
	//PREVIOUS RECORDS LINK
	
	//if ($page > 1) { 
	//$newpage = $page - 1;	
	?>

	<!-- <br>
	<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>
	<br> -->
	<?php  
	//} //IF NEWPAGE != -1
/*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
} //END PROC == ""
} //END IF POSTING = YES
/*---------------------------------------------------------------------------------
END SEARCH SECTION 9991
---------------------------------------------------------------------------------*/

}// END IF PROC = ""

?>
<?php  

/*----------------------------------------------------------------------
ADD NEW RECORDS SECTION
----------------------------------------------------------------------*/
if ($proc == "New") {
echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
?>
<!--<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>-->
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<!--<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>-->
 <?php  } //END OF IF ALLOW ADDNEW 
/*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

if ($proc == "New") { 
echo "<DIV CLASS='PAGE_STATUS'>Adding Employee</DIV>";
	if ($post == "yes") {
/*
WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
*/

/* FIX STRING */
		$emp_id = FixString($emp_id);
		$username = FixString($username);
		$password = FixString($password);
		$name = FixString($name);
		$initials = FixString($initials);
		$email = FixString($email);
		$level = FixString($level);
		$status = FixString($status);
		$last_login = FixString($last_login);
		$option_two = FixString($option_two);
		$option_three = FixString($option_three);
		


/*-- SECTION: 9994SQL --*/
	$sql = "INSERT INTO ucbdb_employees (
emp_id,	
username,
password,
name,
initials,
email,
level,
status,
last_login,
option_two,
option_three
 $addl_insert_crit ) VALUES (
 $emp_id,
'$username',
'$password',
'$name',
'$initials',
'$email',
'$level',
'$status',
'$last_login',
'$option_two',
'$option_three'
 $addl_insert_values )";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	//echo $sql;
	$result = db_query($sql,db() );
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
	header('Location: employee_test_b2b.php?posting=yes');	
	} else {
	echo ThrowError("9994SQL",$sql);
	echo "Error inserting record (9994SQL)";
	}
//***** END INSERT SQL *****
} //END IF POST = YES FOR ADDING NEW RECORDS
/*-------------------------------------------------------------------------------
ADD NEW RECORD (CREATING)
-------------------------------------------------------------------------------*/
	if (!$post) {//THEN WE ARE ENTERING A NEW RECORD
	//SHOW THE ADD RECORD RECORD DATA INPUT FORM
	/*-- SECTION: 9994FORM --*/
	?>
<script type="text/javascript"> 
window.onload = function init() { 
document.newpep.username.focus(); 
} 
</script> 	
	<FORM METHOD="POST" name="newpep" ACTION="<?php  echo $thispage; ?>?proc=New&post=yes&<?php  echo $pagevars; ?>">
	<TABLE ALIGN='LEFT'>
	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>User ID:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="username" SIZE="20">
		</TR>
	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Employee ID:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id" size='20'>
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Password:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="password" NAME="password" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Employee Name:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="name" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Initials:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="initials" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Email Address:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="email" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Security Level:</B>
		</TD>
		<TD>
	<SELECT CLASS='TXT_BOX' NAME="level" SIZE="1">
			<option value="1">Administrator</option>
		<option value="2">High Access</option>
		<option value="3">Customer Service</option>
		<option value="4">Minimal Access</option>	
	</SELECT>
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>Status:</B>
		</TD>
		<TD>
	<SELECT CLASS='TXT_BOX' NAME="status" SIZE="1">
<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>		
	</SELECT>
		</TR>
<!--		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>LAST_LOGIN:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="last_login" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>OPTION_TWO:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="option_two" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>OPTION_THREE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="option_three" SIZE="20">
		</TR>-->
		<TR>
	<TD>
	</TD>
	<TD>
		<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="Submit" NAME="SUBMIT">
		<INPUT CLASS='BUTTON' TYPE="RESET" VALUE="Reset" NAME="RESET">
	</TD>
 </TR>
	</TABLE>
	<BR>
	</FORM>

<?php 
} //END if post=""
//***** END ADD NEW ENTRY FORM*****
} //END PROC == NEW


/*---------------------------------------------------------------------------------
END ADD SECTION 9994
---------------------------------------------------------------------------------*/}// END IF PROC = "NEW"
?>
<?php  
/*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/

if ($proc == "Edit") {
?>
<!--<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>-->
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<!--<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>-->
 <?php  } //END OF IF ALLOW ADDNEW 

/*----------------------------------------------------------------------
EDIT RECORDS SECTION
----------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------
EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/

echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
?><!--
<DIV CLASS='PAGE_OPTIONS'>
	<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
	<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
	<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
	<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
	<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
	<?php  } //END ALLOWDELETE ?></font><font face="arial" size="2">
</DIV>-->

<?php 

if ($proc == "Edit") {
//SHOW THE EDIT RECORD RECORD PAGE
//******************************************************************//
if ($post == "yes") {
//THEN WE ARE POSTING UPDATES TO A RECORD
//***** BEGIN UPDATE SQL*****

//REPLACE THE FIELD CONTENTS SO THEY DON'T MESS UP YOUR QUERY
//NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING

/* FIX STRING */
	$emp_id = FixString($emp_id);
	$username = FixString($username);
	$password = FixString($password);
	$name = FixString($name);
	$initials = FixString($initials);
	$email = FixString($email);
	$level = FixString($level);
	$status = FixString($status);
	$last_login = FixString($last_login);
	$option_two = FixString($option_two);
	$option_three = FixString($option_three);
		
//SQL STRING
/*-- SECTION: 9993SQLUPD --*/



$sql = "UPDATE ucbdb_employees SET 
 emp_id='$emp_id',
 username='$username',
 password='$password',
 name='$name',
 initials='$initials',
 email='$email',
 level='$level',
 status='$status',
 last_login='$last_login',
 option_two='$option_two',
 option_three='$option_three'
$addl_update_crit 
	WHERE (id='$id') $addl_select_crit ";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() );
if (empty($result)) {
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: employee_test_b2b.php?posting=yes');
} else {
echo "Error Updating Record (9993SQLUPD)";
}
//***** END UPDATE SQL *****
} //END IF POST IS YES

/*-------------------------------------------------------------------------------
EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM
-------------------------------------------------------------------------------*/
if ($post == "") { //THEN WE ARE EDITING A RECORD
echo "<DIV CLASS='PAGE_STATUS'>Edit Employee</DIV>";


/*-- SECTION: 9993SQLGET --*/
$sql = "SELECT * FROM ucbdb_employees WHERE (id = '$id') $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() ) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
do
{
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
<form method="post" action="<?php  echo $thispage; ?>?proc=Edit&post=yes&<?php  echo $pagevars; ?>">
<br>
<TABLE ALIGN='LEFT'>
<TR>
	<TD CLASS='TBL_ROW_HDR'>
		<B>Employee ID:</B>
	</TD>
	<TD>
		<INPUT CLASS='TXT_BOX' type="text" onkeypress="return numbersonly(event)" NAME="emp_id"  value='<?php  echo$emp_id; ?>'size='20'>
	</td>
</TR>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		User ID:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='username' value='<?php  echo$username; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Password:
	</TD>
	<TD>	
				<INPUT TYPE='PASSWORD' CLASS='TXT_BOX' name='password' value='<?php  echo$password; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Employee Name:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='name' value='<?php  echo$name; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Initials:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='initials' value='<?php  echo$initials; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Email:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='email' value='<?php  echo$email; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Security Level:
	</TD>
	<TD>	
        <select name="level"  CLASS='TXT_BOX' size="1">
		<option value="1">Administrator</option>
		<option value="2">High Access</option>
		<option value="3">Customer Service</option>
		<option value="4">Minimal Access</option>		
		</select>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		STATUS:
	</TD>
	<TD>	
				<select name="status"  CLASS='TXT_BOX' size="1">
		<option value="Active">Active</option>
		<option value="Active">Active</option>
		<option value="Inactive">Inactive</option>					
		</select>
		</td>
</tr>
<!--<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		LAST_LOGIN:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='last_login' value='<?php  echo$last_login; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		OPTION_TWO:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='option_two' value='<?php  echo$option_two; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		OPTION_THREE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='option_three' value='<?php  echo$option_three; ?>' size='20'>
		</td>
</tr>-->
	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
		<?php  $id = $myrow["id"]; ?>
		<input type="hidden" value="<?php  echo $id; ?>" name="id">
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

/*-------------------------------------------------------------------------------
END EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/}// END IF PROC = "EDIT"
?>
<?php  
/*-------------------------------------------------
SEARCH AND ADD-NEW LINKS
-------------------------------------------------*/



if ($proc == "View") {
?>
<!--<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>-->
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<!--<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>-->
 <?php  } //END OF IF ALLOW ADDNEW 

/*----------------------------------------------------------------------
VIEW RECORDS SECTION - VIEW SINGLE RECORDS
----------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/
if ($proc == "View") {
echo "<DIV CLASS='PAGE_STATUS'>Viewing Record</DIV>";
//***** BEGIN SEARCH RESULTS ****************************************************
//THEN WE ARE SHOWING THE RESULTS OF A SEARCH


/*-- SECTION: 9992SQL --*/
//IF NO SEARCH WORDS TYPED, SHOW ALL RECORDS
$sql = "SELECT * FROM ucbdb_employees WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if ($myrowsel = array_shift($result)) {
	do{
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
<TABLE>
<DIV CLASS='PAGE_OPTIONS'>
<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
<?php  } //END ALLOWDELETE ?><br></font><font face="arial" size="2">
</DIV>
<TR><TD CLASS='TBL_ROW_HDR'>USERNAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $username; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>PASSWORD:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $password; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>INITIALS:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $initials; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>EMAIL:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $email; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>LEVEL:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $level; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>STATUS:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $status; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>LAST_LOGIN:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $last_login; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>OPTION_TWO:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $option_two; ?> </DIV></TD>
		<?php 
		}
		while ($myrowsel = array_shift($result));
		echo "</TR>\n</TABLE>";
	} //IF RESULT

} //END OF PROC VIEW

/*-------------------------------------------------------------------------------
END VIEW RECORD SECTION 9992
-------------------------------------------------------------------------------*/

}// END IF PROC = "VIEW"
?>
<?php  


if ($proc == "Delete") {
echo "<a href=\"employee_test_b2b.php?posting=yes\">Back</a><br><br>";
?>
<!--<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>-->
<?php 

 if ($allowaddnew == "yes") { 
 ?>

<!--<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>-->
 <?php  } //END OF IF ALLOW ADDNEW 
 
/*----------------------------------------------------------------------
DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS
----------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
?>
<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
<?php 
/*-- SECTION: 9995CONFIRM --*/
if (!$delete) {
?>
		<DIV CLASS='PAGE_OPTIONS'>
			Are you sure you want to delete?<BR>
			 <a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&delete=yes&proc=Delete&<?php  echo $pagevars; ?>">Yes</a>
			   <a href="<?php  echo $thispage; ?>?<?php  /* echo $pagevars; */?>posting=yes">No</a>
		</DIV>
	<?php 
	} //IF !DELETE
	
if ($delete == "yes") {

	/*-- SECTION: 9995SQL --*/
	$sql = "DELETE FROM ucbdb_employees WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
	header('Location: employee_test_b2b.php?posting=yes');		
	} else {
	echo "Error Deleting Record (9995SQL)";
	}
} //END IF $DELETE=YES
/*-------------------------------------------------------------------------------
END DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
}// END IF PROC = "DELETE"




?>
<BR>

<BR>
</Font>



</body>
</html>
