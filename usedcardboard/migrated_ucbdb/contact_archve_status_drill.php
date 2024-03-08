<!DOCTYPE html>

<html>
<head>
	<title>DASH - Search Contact Records</title>
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
$allowview		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
$allowdelete	= "no"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS


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
//As get_magic_quotes_gpc is deprecated
//if (get_magic_quotes_gpc()) { $addslash="no"; } //AUTO-ADD SLASHES IF MAGIC QUOTES IS OFF
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

/*----------------------------------------
ADD NEW LINK
----------------------------------------*/
if ($proc == "") {
 if ($allowaddnew == "yes") { 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } 


/*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
/*-- SECTION: 9991FORM --*/
?>
<!--<a href="index.php">Home</a>-->  <br>
<br>

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
$pagenorecords = 500;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}


/*-- SECTION: 9991SQL --*/
if ($searchcrit == "") {
$flag = "all";
$sql = "SELECT * FROM ucb_contact";
$sqlcount = "select count(*) as reccount from ucb_contact";
} else {
//IF THEY TYPED SEARCH WORDS
$sqlcount = "select count(*) as reccount from ucb_contact WHERE (";
$sql = "SELECT * FROM ucb_contact WHERE (";
$sqlwhere = "

 type_id like '%$searchcrit%' OR 
 first_name like '%$searchcrit%' OR 
 last_name like '%$searchcrit%' OR 
 title like '%$searchcrit%' OR 
 company like '%$searchcrit%' OR 
 industry like '%$searchcrit%' OR 
 address1 like '%$searchcrit%' OR 
 address2 like '%$searchcrit%' OR 
 city like '%$searchcrit%' OR 
 state like '%$searchcrit%' OR 
 zip like '%$searchcrit%' OR 
 phone1 like '%$searchcrit%' OR 
 phone2 like '%$searchcrit%' OR 
 email like '%$searchcrit%' OR 
 website like '%$searchcrit%' OR 
 order_no like '%$searchcrit%' OR 
 choose like '%$searchcrit%' OR 
 ccheck like '%$searchcrit%' OR 
 infomation like '%$searchcrit%' OR 
 help like '%$searchcrit%' OR 
 experience like '%$searchcrit%' OR 
 mail_lists like '%$searchcrit%' OR 
 comments like '%$searchcrit%' OR 
 sel_service like '%$searchcrit%' OR 
 experiance like '%$searchcrit%' OR 
 is_export like '%$searchcrit%' OR 
 added_on like '%$searchcrit%' OR 
 have_permission like '%$searchcrit%' 

		"; //FINISH SQL STRING

//NOTE:CHECK ABOVE FOR AN EXTRA "OR":
//IF YOU LEFT THE LAST 
//FIELD IN THE FIELD NAME ENTRY FORM BLANK, YOU WILL
//GET AN EXTRA "OR" ABOVE.  SIMPLY DELETE IT.

} //END IF SEARCHCRIT = "";

if ($flag == "all") {
$sql = $sql . " WHERE (1=1) $addl_select_crit ORDER BY id DESC LIMIT $myrecstart, $pagenorecords";
$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
} else {
$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
$sql = $sql . $sqlwhere . ") $addl_select_crit ORDER BY id DESC LIMIT $myrecstart, $pagenorecords";

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
	if ($reccount > 500) {
	$ttlpages = ($reccount / 500);
	if ($page < $ttlpages) {
	?>

<!--<HR>	<br>
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br> -->
	<?php  
	} //END IF AT LAST PAGE
	} //END IF RECCOUNT > 10

	if ($page > 0) { 
 	$newpage = $page - 2;
 	}
 	if ($newpage != -1) {
	?>
	<br>
<!--	<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a> -->
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
echo "		<TD><DIV CLASS='TBL_COL_HDR'>TYPE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>NAME</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>DATE</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>COMPANY</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>PHONE1</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>EMAIL</DIV></TD>"; 
echo "  </TR>";

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
		<?php  ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  $type_id = $myrowsel["type_id"]; 
			switch($type_id)
			{
				case 'mb_ny':
					$stat = "Moving Box Order Not Yet Placed";
					break;
				case 'mb_rdy':
					$stat = "Moving Box Order Already Placed";
					break;                
				case 'spbox_not':
					$stat = "Shipping Box Order Not Yet Placed";
					break;
				case 'spbox_rdy':
					$stat = "Shipping Box Order Already Placed ";
					break;
				case 'tml':
					$stat = "Testimonial";
					break;
				case 'ptn':
					$stat = "Partnering Opportunities";
					break;
				case 'inv_rel':
					$stat = "Investor Relations";
					break;
				case 'med_inq':
					$stat = "Media Inquiries";
					break;
				case 'box_req':
					$stat = "Box Rescue";
					break;
				case 'other':
					$stat = "Other";
					break;
				case 'Voicemail':
					$stat = "Voicemail";
					break;
				case 'Fax':
					$stat = "Fax";
					break;                
			}
			echo $stat; ?> 
			</TD>
				<?php  $first_name = $myrowsel["first_name"]; $last_name = $myrowsel["last_name"];?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<a href="contact_status_drill.php?id=<?php  echo $myrowsel["id"]; ?>&proc=View&"><?php  echo $first_name; echo " "; echo $last_name;?></a> 
			</TD>

			
<?php  
$order_date = date("F j, Y, g:i a", strtotime($myrowsel["added_on"])); 
?>			
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $order_date; ?> 
			</TD>
				<?php  $company = $myrowsel["company"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $company; ?> 
			</TD>


				<?php  $phone1 = $myrowsel["phone1"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $phone1; ?> 
			</TD>
				<?php  $email = $myrowsel["email"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $email; ?> 
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
<A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	

	<?php  
	} //END IF AT LAST PAGE
	} //END IF RECCOUNT > 10
	//PREVIOUS RECORDS LINK
	if ($page > 0) { 
 	$newpage = $page - 2;
 	}
 	if ($newpage != -1) {
	?>


	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <A HREF="<?php  echo $thispage; ?>?posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>
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
$type_id = FixString($type_id);
		$first_name = FixString($first_name);
		$last_name = FixString($last_name);
		$title = FixString($title);
		$company = FixString($company);
		$industry = FixString($industry);
		$address1 = FixString($address1);
		$address2 = FixString($address2);
		$city = FixString($city);
		$state = FixString($state);
		$zip = FixString($zip);
		$phone1 = FixString($phone1);
		$phone2 = FixString($phone2);
		$email = FixString($email);
		$website = FixString($website);
		$order_no = FixString($order_no);
		$choose = FixString($choose);
		$ccheck = FixString($ccheck);
		$infomation = FixString($infomation);
		$help = FixString($help);
		$experience = FixString($experience);
		$mail_lists = FixString($mail_lists);
		$comments = FixString($comments);
		$sel_service = FixString($sel_service);
		$experiance = FixString($experiance);
		$is_export = FixString($is_export);
		$added_on = FixString($added_on);
		$have_permission = FixString($have_permission);
		


/*-- SECTION: 9994SQL --*/
	$sql = "INSERT INTO ucb_contact (
type_id,
first_name,
last_name,
title,
company,
industry,
address1,
address2,
city,
state,
zip,
phone1,
phone2,
email,
website,
order_no,
choose,
ccheck,
infomation,
help,
experience,
mail_lists,
comments,
sel_service,
experiance,
is_export,
added_on,
have_permission
 $addl_insert_crit ) VALUES ( '$type_id',
'$first_name',
'$last_name',
'$title',
'$company',
'$industry',
'$address1',
'$address2',
'$city',
'$state',
'$zip',
'$phone1',
'$phone2',
'$email',
'$website',
'$order_no',
'$choose',
'$ccheck',
'$infomation',
'$help',
'$experience',
'$mail_lists',
'$comments',
'$sel_service',
'$experiance',
'$is_export',
'$added_on',
'$have_permission'
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
			<B>TYPE_ID:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="type_id" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>FIRST_NAME:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="first_name" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>LAST_NAME:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="last_name" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>TITLE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="title" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>COMPANY:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="company" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>INDUSTRY:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="industry" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ADDRESS1:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="address1" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ADDRESS2:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="address2" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>CITY:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="city" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>STATE:</B>
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
			<B>PHONE1:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="phone1" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>PHONE2:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="phone2" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>EMAIL:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="email" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>WEBSITE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="website" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ORDER_NO:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="order_no" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>CHOOSE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="choose" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>CCHECK:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="ccheck" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>INFOMATION:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="infomation" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>HELP:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="help" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>EXPERIENCE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="experience" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>MAIL_LISTS:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="mail_lists" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>COMMENTS:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="comments" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>SEL_SERVICE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="sel_service" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>EXPERIANCE:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="experiance" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>IS_EXPORT:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="is_export" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>ADDED_ON:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="added_on" SIZE="20">
		</TR>
		<TR>
		<TD CLASS='TBL_ROW_HDR'>
			<B>HAVE_PERMISSION:</B>
		</TD>
		<TD>
	<INPUT CLASS='TXT_BOX' type="text" NAME="have_permission" SIZE="20">
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
$type_id = FixString($type_id);
		$first_name = FixString($first_name);
		$last_name = FixString($last_name);
		$title = FixString($title);
		$company = FixString($company);
		$industry = FixString($industry);
		$address1 = FixString($address1);
		$address2 = FixString($address2);
		$city = FixString($city);
		$state = FixString($state);
		$zip = FixString($zip);
		$phone1 = FixString($phone1);
		$phone2 = FixString($phone2);
		$email = FixString($email);
		$website = FixString($website);
		$order_no = FixString($order_no);
		$choose = FixString($choose);
		$ccheck = FixString($ccheck);
		$infomation = FixString($infomation);
		$help = FixString($help);
		$experience = FixString($experience);
		$mail_lists = FixString($mail_lists);
		$comments = FixString($comments);
		$sel_service = FixString($sel_service);
		$experiance = FixString($experiance);
		$is_export = FixString($is_export);
		$added_on = FixString($added_on);
		$have_permission = FixString($have_permission);
		
//SQL STRING
/*-- SECTION: 9993SQLUPD --*/



$sql = "UPDATE ucb_contact SET 
 type_id='$type_id',
 first_name='$first_name',
 last_name='$last_name',
 title='$title',
 company='$company',
 industry='$industry',
 address1='$address1',
 address2='$address2',
 city='$city',
 state='$state',
 zip='$zip',
 phone1='$phone1',
 phone2='$phone2',
 email='$email',
 website='$website',
 order_no='$order_no',
 choose='$choose',
 ccheck='$ccheck',
 infomation='$infomation',
 help='$help',
 experience='$experience',
 mail_lists='$mail_lists',
 comments='$comments',
 sel_service='$sel_service',
 experiance='$experiance',
 is_export='$is_export',
 added_on='$added_on',
 have_permission='$have_permission'
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
$sql = "SELECT * FROM ucb_contact WHERE (id = '$id') $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
$result = db_query($sql,db() ) or die ("Error Retrieving Records (9993SQLGET)");
if ($myrow = array_shift($result)) {
do
{

$type_id = $myrow["type_id"];
$type_id =  preg_replace("/(\n)/", "<br>", $type_id);
$first_name = $myrow["first_name"];
$first_name =  preg_replace("/(\n)/", "<br>", $first_name);
$last_name = $myrow["last_name"];
$last_name =  preg_replace("/(\n)/", "<br>", $last_name);
$title = $myrow["title"];
$title =  preg_replace("/(\n)/", "<br>", $title);
$company = $myrow["company"];
$company =  preg_replace("/(\n)/", "<br>", $company);
$industry = $myrow["industry"];
$industry =  preg_replace("/(\n)/", "<br>", $industry);
$address1 = $myrow["address1"];
$address1 =  preg_replace("/(\n)/", "<br>", $address1);
$address2 = $myrow["address2"];
$address2 =  preg_replace("/(\n)/", "<br>", $address2);
$city = $myrow["city"];
$city =  preg_replace("/(\n)/", "<br>", $city);
$state = $myrow["state"];
$state =  preg_replace("/(\n)/", "<br>", $state);
$zip = $myrow["zip"];
$zip =  preg_replace("/(\n)/", "<br>", $zip);
$phone1 = $myrow["phone1"];
$phone1 =  preg_replace("/(\n)/", "<br>", $phone1);
$phone2 = $myrow["phone2"];
$phone2 =  preg_replace("/(\n)/", "<br>", $phone2);
$email = $myrow["email"];
$email =  preg_replace("/(\n)/", "<br>", $email);
$website = $myrow["website"];
$website =  preg_replace("/(\n)/", "<br>", $website);
$order_no = $myrow["order_no"];
$order_no =  preg_replace("/(\n)/", "<br>", $order_no);
$choose = $myrow["choose"];
$choose =  preg_replace("/(\n)/", "<br>", $choose);
$ccheck = $myrow["ccheck"];
$ccheck =  preg_replace("/(\n)/", "<br>", $ccheck);
$infomation = $myrow["infomation"];
$infomation =  preg_replace("/(\n)/", "<br>", $infomation);
$help = $myrow["help"];
$help =  preg_replace("/(\n)/", "<br>", $help);
$experience = $myrow["experience"];
$experience =  preg_replace("/(\n)/", "<br>", $experience);
$mail_lists = $myrow["mail_lists"];
$mail_lists =  preg_replace("/(\n)/", "<br>", $mail_lists);
$comments = $myrow["comments"];
$comments =  preg_replace("/(\n)/", "<br>", $comments);
$sel_service = $myrow["sel_service"];
$sel_service =  preg_replace("/(\n)/", "<br>", $sel_service);
$experiance = $myrow["experiance"];
$experiance =  preg_replace("/(\n)/", "<br>", $experiance);
$is_export = $myrow["is_export"];
$is_export =  preg_replace("/(\n)/", "<br>", $is_export);
$added_on = $myrow["added_on"];
$added_on =  preg_replace("/(\n)/", "<br>", $added_on);
$have_permission = $myrow["have_permission"];
$have_permission =  preg_replace("/(\n)/", "<br>", $have_permission);
?>
<form method="post" action="<?php  echo $thispage; ?>?proc=Edit&post=yes&<?php  echo $pagevars; ?>">
<br>
<TABLE ALIGN='LEFT'>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		TYPE_ID:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='type_id' value='<?php  echo$type_id; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		FIRST_NAME:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='first_name' value='<?php  echo$first_name; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		LAST_NAME:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='last_name' value='<?php  echo$last_name; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		TITLE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='title' value='<?php  echo$title; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		COMPANY:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='company' value='<?php  echo$company; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		INDUSTRY:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='industry' value='<?php  echo$industry; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ADDRESS1:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='address1' value='<?php  echo$address1; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ADDRESS2:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='address2' value='<?php  echo$address2; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		CITY:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='city' value='<?php  echo$city; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		STATE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='state' value='<?php  echo$state; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ZIP:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='zip' value='<?php  echo$zip; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		PHONE1:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='phone1' value='<?php  echo$phone1; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		PHONE2:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='phone2' value='<?php  echo$phone2; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		EMAIL:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='email' value='<?php  echo$email; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		WEBSITE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='website' value='<?php  echo$website; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ORDER_NO:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='order_no' value='<?php  echo$order_no; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		CHOOSE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='choose' value='<?php  echo$choose; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		CCHECK:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='ccheck' value='<?php  echo$ccheck; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		INFOMATION:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='infomation' value='<?php  echo$infomation; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		HELP:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='help' value='<?php  echo$help; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		EXPERIENCE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='experience' value='<?php  echo$experience; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		MAIL_LISTS:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='mail_lists' value='<?php  echo$mail_lists; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		COMMENTS:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='comments' value='<?php  echo$comments; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		SEL_SERVICE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='sel_service' value='<?php  echo$sel_service; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		EXPERIANCE:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='experiance' value='<?php  echo$experiance; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		IS_EXPORT:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='is_export' value='<?php  echo$is_export; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		ADDED_ON:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='added_on' value='<?php  echo$added_on; ?>' size='20'>
		</td>
</tr>
<TR>
	<TD CLASS='TBL_ROW_HDR'>	
		HAVE_PERMISSION:
	</TD>
	<TD>	
				<INPUT TYPE='TEXT' CLASS='TXT_BOX' name='have_permission' value='<?php  echo$have_permission; ?>' size='20'>
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
<a href="index.php">Home</a>  <a href="javascript: history.go(-1)">Back</a> <a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search Again</a><br><br>

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
$sql = "SELECT * FROM ucb_contact WHERE id='$id' $addl_select_crit ";
if ($sql_debug_mode==1) { echo "<BR>SQL: $sql<BR>"; }
	$result = db_query($sql,db() );		
	if ($myrowsel = array_shift($result)) {
	do{
	$id = $myrowsel["id"];
		$type_id = $myrowsel["type_id"];
$first_name = $myrowsel["first_name"];
$last_name = $myrowsel["last_name"];
$title = $myrowsel["title"];
$company = $myrowsel["company"];
$industry = $myrowsel["industry"];
$address1 = $myrowsel["address1"];
$address2 = $myrowsel["address2"];
$city = $myrowsel["city"];
$state = $myrowsel["state"];
$zip = $myrowsel["zip"];
$phone1 = $myrowsel["phone1"];
$phone2 = $myrowsel["phone2"];
$email = $myrowsel["email"];
$website = $myrowsel["website"];
$order_no = $myrowsel["order_no"];
$choose = $myrowsel["choose"];
$ccheck = $myrowsel["ccheck"];
$infomation = $myrowsel["infomation"];
$help = $myrowsel["help"];
$experience = $myrowsel["experience"];
$mail_lists = $myrowsel["mail_lists"];
$comments = $myrowsel["comments"];
$sel_service = $myrowsel["sel_service"];
$experiance = $myrowsel["experiance"];
$is_export = $myrowsel["is_export"];
$added_on = $myrowsel["added_on"];
$have_permission = $myrowsel["have_permission"];

switch($type_id){
    case 'mb_ny':
        $stat = "Moving Box Order Not Yet Placed";
        break;
    case 'spbox_not':
        $stat = "Shipping Box Order Not Yet Placed";
        break;
    case 'spbox_rdy':
        $stat = "Shipping Box Order Already Placed ";
        break;
    case 'tml':
        $stat = "Testimonial";
        break;
    case 'ptn':
        $stat = "Partnering Opportunities";
        break;
    case 'inv_rel':
        $stat = "Investor Relations";
        break;
    case 'med_inq':
        $stat = "Media Inquiries";
        break;
    case 'box_req':
        $stat = "Box Rescue";
        break;
    case 'other':
        $stat = "Other";
        break;
    case 'Voicemail':
        $stat = "Voicemail";
        break;
	}
?>
<br>


<table>
<tr>
<td valign="top">
<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		Inquiry Details</font>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Inquiry Date</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo substr($added_on, 0, 10); ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td style="width: 100px; height: 13px;" class="style1">Time</td>
		<td align="left" style="width: 235px; height: 13px;" class="style1"><?php  echo substr($added_on, 11, 19); ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td style="width: 100px; height: 13px;" class="style1">Type</td>
		<td align="left" style="width: 235px; height: 13px;" class="style1"><?php  echo $stat; ?></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">First Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $first_name; ?></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Last Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $last_name; ?></td>
	</tr>
	<?php  if ($title != '') { ?>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Title</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $title; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($company != '') { ?>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Company Name</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $company; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($industry != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Industry</font></td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $industry; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($address1 != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Address</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $address1; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($address2 != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">Address 2</td>
		<td align="left" height="10" style="width: 235px" class="style1"><?php  echo $address2; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($city != '') { ?>	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">City</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $city; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($state != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 100px" class="style1">State</td>
		<td align="left" height="10" style="width: 235px" class="style1"><?php  echo $state; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($zip != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Zip</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $zip; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($phone1 != '') { ?>			
	<tr bgColor="#e4e4e4">
		<td height="19" style="width: 100px" class="style1">Phone</td>
		<td align="left" height="19" style="width: 235px" class="style1"><?php  echo $phone1; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($phone2 != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="19" style="width: 100px" class="style1">Phone 2</td>
		<td align="left" height="19" style="width: 235px" class="style1"><?php  echo $phone2; ?></td>
	</tr>	
	<?php  } ?>
	<?php  if ($email != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">E-mail</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $email; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($website != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Website</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $website; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($infomation != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">How Hear</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $infomation; ?></td>
	</tr>
	<?php  } ?>	
	<?php  if ($order_no != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Order No.</td>
		<td align="left" height="13" style="width: 235px" class="style1"><a href="orders.php?id=<?php  echo $order_no; ?>&proc=View&searchcrit=&page=0"><?php  echo $order_no; ?></a></td>
	</tr>
	<?php  } ?>

<?php 
$voicemail = substr($help, 0, 17);
?>
	<?php  if ($help != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1"><?php  if ($voicemail == 'https://webrouter') { echo 'Voicemail'; } else { echo 'Help'; } ?></td></font></td>
		<td align="left" height="13" style="width: 235px" class="style1">
		<?php  if ($voicemail == 'https://webrouter') { 
		echo "<a href=\"" . $help . "\" target=\"_blank\">" . $help . "</a>";
		} else { echo $help; } ?>
</td>
	</tr>
	<?php  } ?>
	<?php  if ($comments != '') { ?>		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Other Comments</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $comments; ?></td>
	</tr>
	<?php  } ?>
	<?php  if ($experience != '') { ?>		
		<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Testimonial</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $experience; ?></td>
	</tr>
		<tr bgColor="#e4e4e4">
		<td height="13" style="width: 100px" class="style1">Testimonial Permission</td>
		<td align="left" height="13" style="width: 235px" class="style1"><?php  echo $have_permission; ?></td>
	</tr>
	<?php  } ?>
	</table>
</td>
<td valign="top">
<form action="addcontactstatus.php" method="post">
<table cellSpacing="1" cellPadding="1" border="0" width="250">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Status</span>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13"  class="style1">
		Status</td>
		<td align="left" height="13" style="width: 197px" class="style1">
		<select name="issue">

<?php 
$sqlissue = "SELECT * FROM ucb_contact WHERE id = " . $id;
$resissue = db_query($sqlissue,db() );
$resissuecount = tep_db_num_rows($resissue);
if ($resissuecount == 0) {
?>
		<option value="OK">OK</option>
		<option value="Attention">Attention</option>

<?php  } else 
{
while ($myresissue = array_shift($resissue)) {
if ($myresissue["status"] == 'Attention') {
?>
<option valie="Attention">Attention</option>
<option value="OK">OK</option>		
<?php  } 
if ($myresissue["status"] != 'Attention') {
?>
		<option value="OK">OK</option>
		<option value="Attention">Attention</option>
<?php  
}
}
}
?>
		</select>
		
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 189px">
		<span class="style1">Assigned To</span><font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> </font></td>
				<td align="left" height="13" style="width: 197px" class="style1">
<select name="assigned_to">
<?php 
$sqlissue1 = "SELECT * FROM ucb_contact WHERE id = " . $id . " AND status = 'Attention'";
$resissue1 = db_query($sqlissue1,db() );
$resissue1count = tep_db_num_rows($resissue1);

if ($resissue1count == 0) {
	$sqlgetemp = "SELECT * FROM ucbdb_employees ORDER BY Initials ASC";
	$ressqlgetemp = db_query($sqlgetemp,db() );
	echo "<option></option>";
	while ($myrowselemp = array_shift($ressqlgetemp)) {
	?>
		<option value="<?php  echo $myrowselemp["initials"]; ?>"><?php  echo $myrowselemp["initials"]; ?></option>
	<?php  
	} 
}
else 
{
	while ($myresissue1 = array_shift($resissue1)) {
	?>
	<option value="<?php  echo $myresissue1["employee"]; ?>"><?php  echo $myresissue1["employee"]; ?></option>
	<?php  
	} 
	?>
	<option></option>
	<?php 
$sqlgetemp = "SELECT * FROM ucbdb_employees ORDER BY Initials ASC";
$ressqlgetemp = db_query($sqlgetemp,db() );
while ($myrowselemp = array_shift($ressqlgetemp)) {
?>
<option value="<?php  echo $myrowselemp["initials"]; ?>"><?php  echo $myrowselemp["initials"]; ?></option>
<?php  }
} ?>
</select></td>	</tr>
	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" "style="width: 189px" class="style1">
		<input type="submit" value="Update"></td>
 
	</tr>
	</table>
		<input type="hidden" value="<?php  echo $id ?>" name="contact_id" />
		<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
 
</form>
 
<br>
<br>
<form action="addcontactorder.php" method="post">
<table cellSpacing="1" cellPadding="1" border="0" width="250">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="2">
		<span class="style1">Transfer to Order</span>
		</td>
	</tr>
<?php  if ($_GET['notice'] == 1) {
?>
	<tr align="middle">
		<td bgColor="red" colSpan="2">
		<span class="style1">INVALID ORDER ID</span>
		</td>
	</tr>
<?php  } ?>
	<tr bgColor="#e4e4e4">
		<td height="13" width="40%" class="style1">
		Order ID</td>
		<td align="left" height="13" style="width: 197px" class="style1">
		<input type="text" name="orders_id" size="15">
		
		</td>
	</tr>
 
	<tr bgColor="#e4e4e4">
		<td height="10" colspan="2" style="width: 189px" class="style1">
		<input type="submit" value="Transfer"></td>
 
	</tr>
	</table>
		<input type="hidden" value="<?php  echo $id ?>" name="id" />
		<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />				
 
</form>
</td>
<td colspan="3" valign="top">
<form method="post" encType="multipart/form-data" action="addcontactcrm.php">
	<table cellSpacing="1" cellPadding="1" width="100%" border="0">
		<tr align="middle">
			<td bgColor="#c0cdda" colspan="4">
			<font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER 
			LOG</font><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			</font></td>
		</tr>
		<input type="hidden" value="<?php  echo $id ?>" name="contact_id" />
		<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
		
		<tr vAlign="top">
			<td bgColor="#e4e4e4" style="width: 106px" class="style1"><select size="1" name="comm_type">
<?php  

$sql1 = "SELECT * FROM ucbdb_customer_log_config ORDER BY rank";
$result1 = db_query($sql1,db() );
while ($myrowsel1 = array_shift($result1)) {
?>
<option value="<?php  echo $myrowsel1["id"]; ?>"><?php  echo $myrowsel1["comm_type"]; ?></option>
<?php  } ?>
			</select>&nbsp;</td>
			<td bgColor="#e4e4e4" style="width: 288px" class="style1">
			<textarea name="message" style="width: 361px; height: 41px;"></textarea></td>
			<td bgColor="#e4e4e4">&nbsp;</td>
			<td align="middle" bgColor="#e4e4e4" rowSpan="2" style="width: 76px" class="style1">
			<input type="submit" value="Add" /></td>
		</tr>
		<tr>
			<td bgColor="#e4e4e4" colSpan="3" class="style1">
			<input type="file" size="50" name="file" /></td>
		</tr>
	</table>
</form>

<table cellSpacing="1" cellPadding="1" width="100%" border="0">
		<tr align="middle">
			<td bgColor="#c0cdda" colspan="6">
			<font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER 
			LOG HOSTORY</font><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
			</font></td>
		</tr>
<tr bgColor="#e4e4e4">
<td class="style1">Date</td>
<td class="style1">Image</td>
<td class="style1">Type</td>
<td class="style1">Notes</td>
<td class="style1">Employee</td>
<td class="style1">File Link</td>
</tr>				
<?php 
$sql7 = "SELECT * FROM ucbdb_contact_crm WHERE contact_id = " . $id . " ORDER BY message_date DESC, id DESC ";
$result7 = db_query($sql7,db() );
while ($myrowsel7 = array_shift($result7)) {
$the_log_date = $myrowsel7["message_date"];

$yearz = substr("$the_log_date", 0, 4);
$monthz = substr("$the_log_date", 4, 2);
$dayz = substr("$the_log_date", 6, 2); 
?>


<tr bgColor="#e4e4e4">
<td class="style1"><?php  echo $monthz . "/" . $dayz . "/" . $yearz; ?></td>
<td class="style1"><?php  
if ($myrowsel7["comm_type"] != ""){
	$qry2 = "SELECT icon_file, comm_type FROM ucbdb_customer_log_config WHERE id = '" . $myrowsel7["comm_type"] . "'";
	$result2 = db_query($qry2,db() );
	while ($myrowsel2 = array_shift($result2)) { ?>
	<img src="images/<?php  echo $myrowsel2["icon_file"]; ?>" alt="" border="0"></td>
	<td class="style1"><?php  echo $myrowsel2["comm_type"]; ?></td>
	<?php  } 
}
	?>
<td class="style1"><?php  echo $myrowsel7["message"]; ?></td>
<td class="style1"><?php  echo $myrowsel7["employee"]; ?></td>
<td class="style1"><?php  if ($myrowsel7["file_name"] != '') 
{
echo "<a href='files/" . $myrowsel7["file_name"] . "'>File</a>";
}
?></td>
</tr>

<?php  } ?>

</table>


<br>
<br>
<br>

<!--
<TABLE>
<DIV CLASS='PAGE_OPTIONS'>
<?php  if ($allowview == "yes") { ?><a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=View&<?php  echo $pagevars; ?>">View</a>
<?php  } //END ALLOWVIEW ?><?php  if ($allowedit == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Edit&<?php  echo $pagevars; ?>">Edit</a>
<?php  } //END ALLOWEDIT ?><?php  if ($allowdelete == "yes") { ?>
<a href="<?php  echo $thispage; ?>?id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>">Delete</a>
<?php  } //END ALLOWDELETE ?><br></font><font face="arial" size="2">
</DIV>
<TR><TD CLASS='TBL_ROW_HDR'>TYPE_ID:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $stat; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>FIRST_NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $first_name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>LAST_NAME:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $last_name; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>TITLE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $title; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>COMPANY:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $company; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>INDUSTRY:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $industry; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ADDRESS1:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $address1; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ADDRESS2:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $address2; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>CITY:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $city; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>STATE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $state; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ZIP:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $zip; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>PHONE1:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $phone1; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>PHONE2:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $phone2; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>EMAIL:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $email; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>WEBSITE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $website; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ORDER_NO:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $order_no; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>CHOOSE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $choose; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>CCHECK:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $ccheck; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>INFOMATION:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $infomation; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>HELP:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $help; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>EXPERIENCE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $experience; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>MAIL_LISTS:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $mail_lists; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>COMMENTS:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $comments; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>SEL_SERVICE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $sel_service; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>EXPERIANCE:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $experiance; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>IS_EXPORT:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $is_export; ?> </DIV></TD>
		<TR><TD CLASS='TBL_ROW_HDR'>ADDED_ON:</TD>
<TD ><DIV CLASS='TBL_ROW_DATA'><?php  echo $added_on; ?> </DIV></TD>
		<?php 
		}
		while ($myrowsel = array_shift($result));
		echo "</TR>\n</TABLE> -->";
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
	$sql = "DELETE FROM ucb_contact WHERE id='$id' $addl_select_crit ";
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
</div>


</body>
</html>
----------------------
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
	$sql = "DELETE FROM ucb_contact WHERE id='$id' $addl_select_crit ";
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
