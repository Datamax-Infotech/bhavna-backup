<?php  
require ("inc/header_session.php");
?>

<!DOCTYPE html>

<html>
<head>
	<title>DASH - Processed Credits</title>
</head>

<body>
<?php 
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	?>
<div>
	<?php  include("inc/header.php"); 
	
	?>
	
</div>
<div class="main_data_css">

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
$allowedit		= "no"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
$allowaddnew	= "no"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
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
/*require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");*/

/*----------------------------------------
ADD NEW LINK
----------------------------------------*/
if ($proc == "") {
 if ($allowaddnew == "yes") { 
  //echo "<a href=\"index.php\">Home</a><br><br>"; 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } 
//echo "<a href=\"index.php\">Home</a><br><br>";

/*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
/*-- SECTION: 9991FORM --*/
?>
	<br>
	<!--
	<form method="POST" action="<?php  echo $thispage; ?>?posting=yes&<?php  echo $pagevars; ?>">
  	<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php  echo $searchcrit; ?>">
  	<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1"></P>
	</form>
-->
<?php 

/*----------------------------------------------------------------
IF THEY ARE POSTING TO THE SEARCH PAGE 
(SHOW SEARCH RESULTS)
----------------------------------------------------------------*/
if ($posting == "yes") {


/*----------------------------------------------------------------
PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
$pagenorecords = 20;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}


/*-- SECTION: 9991SQL --*/
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
 credit_date like '%$searchcrit%' 

		"; //FINISH SQL STRING

//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
//IF YOU LEFT THE LAST 
//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

} //END IF SEARCHCRIT = "";

if ($flag == "all") {
$sql = $sql . " WHERE (1=1) AND total > 0 AND pending = 'Processed' ORDER BY credit_date DESC $addl_select_crit LIMIT $myrecstart, $pagenorecords";
$sqlcount = $sqlcount  . " WHERE (1=1) AND total > 0 AND pending = 'Processed' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
} else {
$sqlcount = $sqlcount . $sqlwhere . ") total > 0 pending = 'Processed' $addl_select_crit LIMIT $myrecstart, $pagenorecords";
$sql = $sql . $sqlwhere . ") AND total > 0 AND pending = 'Processed' ORDER BY credit_date DESC $addl_select_crit LIMIT $myrecstart, $pagenorecords";

}

if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }



//SET PAGE
	if ($page == 0) {
	$page = 1;
	} else {
  	$page = ($page + 1);
  	}

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

	echo "<DIV CLASS='NUM_RECS_FOUND'>$reccount Records Found</DIV>";
	echo "<DIV CLASS='CURR_PAGE'>Page $page</DIV>";
	

/*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	if ($reccount > 10) {
	$ttlpages = ($reccount / 10);
	if ($page < $ttlpages) {
	?>

<HR>	<br>
<!--<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	--><br>
	<?php  
	} //END IF AT LAST PAGE
	} //END IF RECCOUNT > 10

	if ($page > 0) { 
 	$newpage = $page - 2;
 	}
 	if ($newpage != -1) {
	?>
	<br>
<!--	<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>-->
	<br>
	<?php  

	} //IF NEWPAGE != -1
/*----------------------------------------------------------------
END PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
	$result = db_query($sql,db() );
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	if ($myrowsel = array_shift($result)) {
	$id = $myrowsel["id"];
	
	echo "<TABLE WIDTH='100%'>";
	echo "	<tr align='middle'><td colspan='12' class='style24' style='height: 16px'><strong>DENIED CREDITS</strong></td></tr>";

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
 echo "<TD><DIV CLASS='TBL_COL_HDR'>Action</DIV></TD>"; 
echo "\n\n		</TR>";
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
echo "<TR>";
$database_id = $myrowsel["id"];
$auth_trans_id = $myrowsel["auth_trans_id"];
$cc_number = $myrowsel["cc_number"];
$cc_expires = $myrowsel["cc_expires"];
$orders_id = $myrowsel["orders_id"];
echo "<input type='hidden' name='auth_trans_id_" . $database_id . "' value=" . $auth_trans_id .">";
echo "<input type='hidden' name='cc_number_" . $database_id . "' value=" . $cc_number . ">";
echo "<input type='hidden' name='cc_expires_" . $database_id . "' value=" . $cc_expires . ">";
echo "<input type='hidden' name='orders_id_" . $database_id . "' value=" . $orders_id . ">";
?>

			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $orders_id; ?> 
			</TD>
				<?php  $item_name = $myrowsel["item_name"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $item_name; ?> 
			</TD>
				<?php  $warehouse = $myrowsel["warehouse"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $warehouse; ?> 
			</TD>
				<?php  $reason_code = $myrowsel["reason_code"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $reason_code; ?> 
			</TD>
				<?php  $notes = $myrowsel["notes"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $notes; ?> 
			</TD>						
				<?php  $quantity = $myrowsel["quantity"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $quantity; ?> 
			</TD>
				<?php  $chargeback = $myrowsel["chargeback"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $chargeback; ?> 
			</TD>
				<?php  $total = $myrowsel["total"]; 
				echo "<input type='hidden' name='amount_" . $database_id . "' value=" . $total . ">";?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $total; ?> 
			</TD>
				<?php  $employee = $myrowsel["employee"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $employee; ?> 
			</TD>
				<?php  $pending = $myrowsel["pending"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $pending; ?> 
			</TD>
				<?php  $credit_date = $myrowsel["credit_date"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $credit_date; ?> 
			</TD>
			<TD CLASS='<?php  echo $shade; ?>'>
				<DIV CLASS='PAGE_OPTIONS'>
				<!--<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">Process</a>--> <!-- <input type="checkbox" name="process[]" value="<?php  echo $database_id; ?>"> -->
 				<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
				<?php  } ?><?php  if ($allowedit == "yes") { ?>
				<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
				<?php  } ?><?php  if ($allowdelete == "yes") { ?>
				<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>&orders_id=<?php  echo $orders_id; ?>">Re-Apply Credit</a>
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
	if ($reccount > 10) {
//IF THERE ARE MORE THAN 10 RECORDS PAGING
	$ttlpages = ($reccount / 10);
	if ($page < $ttlpages) {
	?>

<HR>	<br>
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br>

	<?php  
	} //END IF AT LAST PAGE
	} //END IF RECCOUNT > 10
	//PREVIOUS RECORDS LINK
	if ($page > 0) { 
 	$newpage = $page - 2;
 	}
 	if ($newpage != -1) {
	?>

	<br>
	<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>
	<br>
	<?php  
	} //IF NEWPAGE != -1
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
?>
<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } //END OF IF ALLOW ADDNEW 
/*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

if ($proc == "New") { 
echo "<DIV CLASS='PAGE_STATUS'>Adding Record</DIV>";
	if ($post == "yes") {
/*
WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
*/

/* FIX STRING */
$orders_id = FixString($orders_id);
		$item_name = FixString($item_name);
		$warehouse = FixString($warehouse);
		$reason_code = FixString($reason_code);
		$quantity = FixString($quantity);
		$chargeback = FixString($chargeback);
		$total = FixString($total);
		$employee = FixString($employee);
		$pending = FixString($pending);
		$credit_date = FixString($credit_date);
		


/*-- SECTION: 9994SQL --*/
	$sql = "INSERT INTO ucbdb_credits (
orders_id,
item_name,
warehouse,
reason_code,
quantity,
chargeback,
total,
employee,
pending,
credit_date
 $addl_insert_crit ) VALUES ( '$orders_id',
'$item_name',
'$warehouse',
'$reason_code',
'$quantity',
'$chargeback',
'$total',
'$employee',
'$pending',
'$credit_date'
 $addl_insert_values )";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Record Inserted</DIV>";
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
	<FORM METHOD="POST" ACTION="<?php  echo $thispage; ?>?proc=New&post=yes&<?php  echo $pagevars; ?>">
	<TABLE ALIGN='LEFT'>
	<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ORDERS_ID:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="orders_id" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ITEM_NAME:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="item_name" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>WAREHOUSE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="warehouse" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>REASON_CODE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="reason_code" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>QUANTITY:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="quantity" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>CHARGEBACK:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="chargeback" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>TOTAL:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="total" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>EMPLOYEE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="employee" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>PENDING:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="pending" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>CREDIT_DATE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="credit_date" SIZE="20">
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
<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } //END OF IF ALLOW ADDNEW 

/*----------------------------------------------------------------------
EDIT RECORDS SECTION
----------------------------------------------------------------------*/
/*-------------------------------------------------------------------------------
EDIT RECORD SECTION 9993
-------------------------------------------------------------------------------*/

?>
<DIV CLASS='PAGE_OPTIONS'>
	<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
	<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
	<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
	<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
	<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
	<?php  } //END ALLOWDELETE ?></font><font face="arial" size="2">
</DIV>

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
$orders_id = FixString($orders_id);
		$item_name = FixString($item_name);
		$warehouse = FixString($warehouse);
		$reason_code = FixString($reason_code);
		$quantity = FixString($quantity);
		$chargeback = FixString($chargeback);
		$total = FixString($total);
		$employee = FixString($employee);
		$pending = FixString($pending);
		$credit_date = FixString($credit_date);
		
//SQL STRING
/*-- SECTION: 9993SQLUPD --*/



$sql = "UPDATE ucbdb_credits SET 
 orders_id='$orders_id',
 item_name='$item_name',
 warehouse='$warehouse',
 reason_code='$reason_code',
 quantity='$quantity',
 chargeback='$chargeback',
 total='$total',
 employee='$employee',
 pending='$pending',
 credit_date='$credit_date'
$addl_update_crit 
	WHERE (id='$id') $addl_select_crit ";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() );
if (empty($result)) {
echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
} else {
echo "Error Updating Record (9993SQLUPD)";
}
//***** END UPDATE SQL *****
} //END IF POST IS YES

/*-------------------------------------------------------------------------------
EDIT RECORD (FORM) - SHOW THE EDIT RECORD RECORD DATA INPUT FORM
-------------------------------------------------------------------------------*/
if ($post == "") { //THEN WE ARE EDITING A RECORD
echo "<DIV CLASS='PAGE_STATUS'>Editing Record</DIV>";


/*-- SECTION: 9993SQLGET --*/
$sql = "SELECT * FROM ucbdb_credits WHERE (id = '$id') $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() ) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
do
{

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
<form method="post" action="<?php  echo $thispage; ?>?proc=Edit&post=yes&<?php  echo $pagevars; ?>">
<br>
<TABLE ALIGN='LEFT'>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ORDERS_ID:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='orders_id' value='<?php  echo$orders_id; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ITEM_NAME:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='item_name' value='<?php  echo$item_name; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		WAREHOUSE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse' value='<?php  echo$warehouse; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		REASON_CODE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='reason_code' value='<?php  echo$reason_code; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		QUANTITY:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='quantity' value='<?php  echo$quantity; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		CHARGEBACK:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='chargeback' value='<?php  echo$chargeback; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		TOTAL:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='total' value='<?php  echo$total; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		EMPLOYEE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='employee' value='<?php  echo$employee; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		PENDING:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='pending' value='<?php  echo$pending; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		CREDIT_DATE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='credit_date' value='<?php  echo$credit_date; ?>' size='20'>
		</td>
</tr>
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
<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
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
$sql = "SELECT * FROM ucbdb_credits WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if ($myrowsel = array_shift($result)) {
	do{
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
<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
<?php  } //END ALLOWDELETE ?><br></font><font face="arial" size="2">
</DIV>
<TR><TD CLASS='TBL_ROW_HDR'>ORDERS_ID:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $orders_id; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ITEM_NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $item_name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>WAREHOUSE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $warehouse; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>REASON_CODE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $reason_code; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>QUANTITY:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $quantity; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>CHARGEBACK:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $chargeback; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>TOTAL:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $total; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>EMPLOYEE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $employee; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>PENDING:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $pending; ?> </DIV></TD>
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
?>
<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>
<?php 

 if ($allowaddnew == "yes") { 
 ?>

<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } //END OF IF ALLOW ADDNEW 
 
/*----------------------------------------------------------------------
DELETE RECORD SECTION - THIS SECTION WILL CONFIRM/PERFORM DELETIONS
----------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
?>
<DIV CLASS='PAGE_STATUS'>Re-Apply Credit</DIV><br><br>
<?php 
/*-- SECTION: 9995CONFIRM --*/
if (!$delete) {
?>
		<DIV CLASS='PAGE_OPTIONS'>
			Are you sure you want to Re-Apply This Credit?<BR><br>
<?php  $orders_id = $_GET["orders_id"];	?>
			 <a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&delete=yes&proc=Delete&orders_id=<?php  echo $orders_id; ?>&<?php  echo $pagevars; ?>">Yes</a>
			   <a href="<?php  echo $thispage; ?>?<?php  echo $pagevars; ?>">No</a>
		</DIV>
	<?php 
	} //IF !DELETE
	
if ($delete == "yes") {

	/*-- SECTION: 9995SQL --*/
	$sql = "UPDATE ucbdb_credits SET pending = 'Pending' WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	
$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$commqryrw = array_shift(db_query($commqry, db()));
$comm_type = $commqryrw["id"];  
$orders_id = $_GET["orders_id"];

$output10 = "Credit Re-Applied";
$today = date("Ymd"); 
$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $output10 . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
$result3 = db_query($sql3,db() );	


	
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Successfully Updated</DIV><br><br>";
	echo "<a href=\"index.php\">Home</a><br><br>";
	if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: processed_credits.php?posting=yes&'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"processed_credits.php?posting=yes&\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=processed_credits.php?posting=yes&\" />";
        echo "</noscript>"; exit;
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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


	</div>
</body>
</html>
