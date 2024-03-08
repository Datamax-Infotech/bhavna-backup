<?php  
require ("inc/header_session.php");
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
select, input {
font-family: Arial, Helvetica, sans-serif; 
font-size: 10px; 
color : #000000; 
font-weight: normal; 
}
</style>	

<?php 
// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";


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
$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
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
 if ($allowaddnew == "yes") { 
 ?>
<!-- <a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">Add / Subtract Inventory</a><br> -->
 <?php  } 


/*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
/*-- SECTION: 9991FORM --*/
?>
	<form method="POST" action="<?php  echo $thispage; ?>?posting=yes&<?php  echo $pagevars; ?>">
  	<p><B>Search:</B> <input type="text" CLASS='TXT_BOX' name="searchcrit" size="20" value="<?php  echo $searchcrit; ?>">
  	<INPUT CLASS="BUTTON" TYPE="SUBMIT" VALUE="Search!" NAME="B1"></P>
	</form>

<?php 

/*----------------------------------------------------------------
IF THEY ARE POSTING TO THE SEARCH PAGE 
(SHOW SEARCH RESULTS)
----------------------------------------------------------------*/
if ($posting == "yes") {


/*----------------------------------------------------------------
PAGING SETTINGS - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
$pagenorecords = 10;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}


/*-- SECTION: 9991SQL --*/
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

//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
//IF YOU LEFT THE LAST 
//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

} //END IF SEARCHCRIT = "";

if ($flag == "all") {
$sql = $sql . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
} else {
$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
$sql = $sql . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";

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
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br>
	<?php  
	} //END IF AT LAST PAGE
	} //END IF RECCOUNT > 10

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
	//EXECUTE OUR SQL STRING FOR THE TABLE RECORDS
	$result = db_query($sql,db() );
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	if ($myrowsel = array_shift($result)) {
	$id = $myrowsel["id"];
	
	echo "<TABLE WIDTH='100%'>";
	echo "	<TR>";
 echo "<TD><DIV CLASS='TBL_COL_HDR'>OPTIONS</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_ID</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>WAREHOUSE_NAME</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>MODULE_ID</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>MODULE_NAME</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>QUANTITY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>UPDATE_DATE</DIV></TD>"; echo "\n\n		</TR>";
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
			?>
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
					<?php  $warehouse_id = $myrowsel["warehouse_id"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $warehouse_id; ?> 
			</TD>
				<?php  $warehouse_name = $myrowsel["warehouse_name"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $warehouse_name; ?> 
			</TD>
				<?php  $module_id = $myrowsel["module_id"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $module_id; ?> 
			</TD>
				<?php  $module_name = $myrowsel["module_name"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $module_name; ?> 
			</TD>
				<?php  $quantity = $myrowsel["quantity"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $quantity; ?> 
			</TD>
				<?php  $update_date = $myrowsel["update_date"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $update_date; ?> 
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
<a href="javascript: history.go(-1)">Back</a><br><br>
<?php 
 if ($allowaddnew == "yes") { 
 ?>
<!-- <a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">Add / Subtract Inventory</a><br> -->
 <?php  } //END OF IF ALLOW ADDNEW 
/*-------------------------------------------------------------------------------
ADD NEW RECORD SECTION 9994
-------------------------------------------------------------------------------*/

if ($proc == "New") { 

	if ($post == "yes") {
/*
WE ARE ADDING A NEW RECORD SUBMITTED FROM THE FORM
NOW LOOP THROUGH ALL OF THE RECORDS AND OUTPUT A STRING
*/

/* FIX STRING */
$warehouse_id = FixString($warehouse_id);
		$module_name = FixString($module_name);
		$quantity = FixString($quantity);
		$update_date = FixString($update_date);
		


/*-- SECTION: 9994SQL --*/
	$sql = "INSERT INTO inv_warehouse_transactions (
warehouse_id,
module_name,
quantity,
update_date
 $addl_insert_crit ) VALUES ( '$warehouse_id',
'$module_name',
'$quantity',
'$update_date'
 $addl_insert_values )";
	if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br><a href='inventory_transfer.php?proc=New&'>Add / Subtract Additional Inventory</a><br><br><a href='report_inventory.php'>Return to Inventory Report</a></DIV>";
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
echo "<DIV CLASS='PAGE_STATUS'>Use the form below to add or subtract inventory.  Note that a negative value entered in the quantity field will subtract inventory from the warehouse.  </DIV>";
	?>
	<FORM METHOD="POST" name="rptSearch" ACTION="<?php  echo $thispage; ?>?proc=New&post=yes&<?php  echo $pagevars; ?>">
	<TABLE ALIGN='LEFT'>
		<TR>
		<TD class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<B>Warehouse:</B>
		</TD>
		<TD>
				<select name="warehouse_id">
<option value="">Please Select</option>
<?php  

$sql2 = "SELECT DISTINCT warehouse_name, warehouse_id FROM inv_warehouse_to_modules ORDER BY warehouse_id";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {

?>
<option value="<?php  echo $myrowsel2["warehouse_id"]; ?>"><?php  echo $myrowsel2["warehouse_name"]; ?></option>
<?php  } ?>
</select>
		</TR>
		<TR>
		<TD class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			<B>Module Type:</B>
		</TD>
		<TD>
<select name="module_name">
<option value="">Please Select</option>
<?php  

$sql3 = "SELECT DISTINCT module_name FROM inv_warehouse_to_modules ORDER BY module_id";
$result3 = db_query($sql3,db() );
while ($myrowsel3 = array_shift($result3)) {
?>
<option value="<?php  echo $myrowsel3["module_name"]; ?>"><?php  echo $myrowsel3["module_name"]; ?></option>
<?php  } ?>
</select>		</TR>
		<TR>
		<TD class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Quantity
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="quantity" SIZE="20">
		</TR>
		<TR>
		<TD class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Update Date
		</TD>
		<TD>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
<script LANGUAGE="JavaScript">
	var cal1xx = new CalendarPopup("listdiv");
	cal1xx.showNavigationDropdowns();
	var cal2xx = new CalendarPopup("listdiv");
	cal2xx.showNavigationDropdowns();
</script>
<?php 
$start_date = isset($_GET["start_date"])?strtotime($_GET["start_date"]):strtotime(date('m/d/Y'));
$end_date = isset($_GET["end_date"])?strtotime($_GET["end_date"]):strtotime(date('m/d/Y'));
?>
	
<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"><input type="text" name="update_date" size="11" value="<?php  echo date('Ymd'); ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.update_date,'anchor2xx','yyyyMMdd'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
		</TR>
		<TR>
	<TD>
	</TD>
	<TD>
		<INPUT CLASS='BUTTON' TYPE="SUBMIT" VALUE="SAVE" NAME="SUBMIT">
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
$warehouse_id = FixString($warehouse_id);
		$warehouse_name = FixString($warehouse_name);
		$module_id = FixString($module_id);
		$module_name = FixString($module_name);
		$quantity = FixString($quantity);
		$update_date = FixString($update_date);
		
//SQL STRING
/*-- SECTION: 9993SQLUPD --*/



$sql = "UPDATE inv_warehouse_transactions SET 
 warehouse_id='$warehouse_id',
 warehouse_name='$warehouse_name',
 module_id='$module_id',
 module_name='$module_name',
 quantity='$quantity',
 update_date='$update_date'
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
$sql = "SELECT * FROM inv_warehouse_transactions WHERE (id = '$id') $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() ) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
do
{

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
<form method="post" action="<?php  echo $thispage; ?>?proc=Edit&post=yes&<?php  echo $pagevars; ?>">
<br>
<TABLE ALIGN='LEFT'>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		WAREHOUSE_ID:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='warehouse_id' value='<?php  echo$warehouse_id; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		Warehouse:
	</TD>
	<TD>	
				<select name="warehouse">
<option value="">Please Select</option>
<?php  

$sql2 = "SELECT * FROM warehouse ORDER BY warehouse_id";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {

?>
<option value="<?php  echo $myrowsel2["warehouse_id"]; ?>"><?php  echo $myrowsel2["warehouse_name"]; ?></option>
<?php  } ?>
</select>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		MODULE_ID:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='module_id' value='<?php  echo$module_id; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		MODULE_NAME:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='module_name' value='<?php  echo$module_name; ?>' size='20'>
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
		UPDATE_DATE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='update_date' value='<?php  echo$update_date; ?>' size='20'>
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
$sql = "SELECT * FROM inv_warehouse_transactions WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if ($myrowsel = array_shift($result)) {
	do{
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
<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
<?php  } //END ALLOWDELETE ?><br></font><font face="arial" size="2">
</DIV>
<TR><TD CLASS='TBL_ROW_HDR'>WAREHOUSE_ID:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $warehouse_id; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>WAREHOUSE_NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $warehouse_name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>MODULE_ID:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $module_id; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>MODULE_NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $module_name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>QUANTITY:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $quantity; ?> </DIV></TD>
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
<DIV CLASS='PAGE_STATUS'>Deleting Record</DIV>
<?php 
/*-- SECTION: 9995CONFIRM --*/
if (!$delete) {
?>
		<DIV CLASS='PAGE_OPTIONS'>
			Are you sure you want to delete?<BR>
			 <a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&delete=yes&proc=Delete&<?php  echo $pagevars; ?>">Yes</a>
			   <a href="<?php  echo $thispage; ?>?<?php  echo $pagevars; ?>">No</a>
		</DIV>
	<?php 
	} //IF !DELETE
	
if ($delete == "yes") {

	/*-- SECTION: 9995SQL --*/
	$sql = "DELETE FROM inv_warehouse_transactions WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if (empty($result)) {
	echo "<DIV CLASS='SQL_RESULTS'>Successfully Deleted</DIV>";
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
