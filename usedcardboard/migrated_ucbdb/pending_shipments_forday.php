<?php  
require ("inc/header_session.php");
?>

<!DOCTYPE html>

<html>
<head>
	<title>DASH - Pending Shipments</title>
</head>

<body>


<?php 

$sql_table = ""; $sql_query = ""; 
if (isset($_REQUEST["tbl"])){
	if ($_REQUEST["tbl"] == "losangeles"){
		$sql_table = " orders_active_ucb_los_angeles inner join orders on orders.orders_id = orders_active_ucb_los_angeles.orders_id ";
		$sql_query = " orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and "; 
	}
	if ($_REQUEST["tbl"] == "huntvally"){
		$sql_table = " orders_active_ucb_hunt_valley inner join orders on orders.orders_id = orders_active_ucb_hunt_valley.orders_id ";
		$sql_query = " orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and "; 
	}
	if ($_REQUEST["tbl"] == "hannibal"){
		$sql_table = " orders_active_ucb_hannibal inner join orders on orders.orders_id = orders_active_ucb_hannibal.orders_id ";
		$sql_query = " orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and "; 
	}
	if ($_REQUEST["tbl"] == "saltlake"){
		$sql_table = " orders_active_ucb_salt_lake inner join orders on orders.orders_id = orders_active_ucb_salt_lake.orders_id ";
		$sql_query = " orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and "; 
	}
	if ($_REQUEST["tbl"] == "newitem"){
		$sql_table = " orders_sps inner join orders on orders.orders_id = orders_sps.orders_id ";
		$sql_query = " orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "' and "; 
	}
}

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
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

/*----------------------------------------
ADD NEW LINK
----------------------------------------*/
if ($proc == "") {
 if ($allowaddnew == "yes") { 
  echo "<a href=\"index.php\">Home</a><br><br>"; 
 ?>
<a href="<?php  echo $thispage; ?>?proc=New&<?php  echo $pagevars; ?>">New Record</a><br>
 <?php  } 
echo "<a href=\"index.php\">Home</a><br><br>";

/*---------------------------------------------------------------------------------------
BEGIN SEARCH SECTION 9991
---------------------------------------------------------------------------------------*/
/*-- SECTION: 9991FORM --*/
?><!--
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
$pagenorecords = 50;  //THIS IS THE PAGE SIZE
	//IF NO PAGE
	if ($page == 0) {
	$myrecstart = 0;
	} else {
	$myrecstart = ($page * $pagenorecords);
	}


/*-- SECTION: 9991SQL --*/
if ($searchcrit == "") {
$flag = "all";
$sql = "SELECT * FROM $sql_table";
$sqlcount = "select count(*) as reccount from $sql_table";
} else {
//IF THEY TYPED SEARCH WORDS
$sqlcount = "select count(*) as reccount from $sql_table WHERE $sql_query (";
$sql = "SELECT * FROM $sql_table WHERE $sql_query (";
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
$sql = $sql . " WHERE $sql_query (1=1)  ORDER BY id $addl_select_crit ";
$sqlcount = $sqlcount  . " WHERE $sql_query (1=1) $addl_select_crit ";
} else {
$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit ";
$sql = $sql . $sqlwhere . ")  ORDER BY id$addl_select_crit ";

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
	echo "	<tr align='middle'><td colspan='13' class='style24' style='height: 16px'><strong>PENDING SHIPMENTS</strong></td></tr>";
	echo "	<TR>";


echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>"; 
echo "		<TD><DIV CLASS='TBL_COL_HDR'>Item</DIV></TD>"; 
 echo "<TD><DIV CLASS='TBL_COL_HDR'>Remove</DIV></TD>"; 
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
echo "<form action='process_credit.php' method='post'>";
echo "<TR>";
$database_id = $myrowsel["id"];
$orders_id = $myrowsel["orders_id"];

		$order_amount = 0;
		$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $orders_id;
		$t_sql_1_res = db_query($t_sql_1,db() );
		while ($t_sql_1_row = array_shift($t_sql_1_res)) {
			$order_amount = number_format($t_sql_1_row["value"],2);
		}			

		$date_purchased = "";
		$t_sql_1 = "SELECT date_purchased FROM orders WHERE orders_id = " . $orders_id;
		$t_sql_1_res = db_query($t_sql_1,db() );
		while ($t_sql_1_row = array_shift($t_sql_1_res)) {
			$date_purchased = $t_sql_1_row["date_purchased"];
		}			
?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<a href="orders.php?tbl=<?php  echo $_REQUEST["tbl"];?>&id=<?php  echo $orders_id; ?>&proc=View"><?php  echo $orders_id; ?></a> 
			</TD>
			
			<TD CLASS='<?php  echo $shade; ?>'>$<?php  echo $order_amount; ?></td>
			
			<TD CLASS='<?php  echo $shade; ?>'><?php  $order_date = date("F j, Y", strtotime($date_purchased)); echo $order_date; ?></td>
				<?php  $customers_name = $myrowsel["shipping_name"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_name; ?> 
			</TD>
				<?php  $customers_street_address = $myrowsel["shipping_street1"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_street_address; ?> 
			</TD>
				<?php  $customers_street_address2 = $myrowsel["shipping_street2"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_street_address2; ?> 
			</TD>
				<?php  $customers_city = $myrowsel["shipping_city"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_city; ?> 
			</TD>
				<?php  $customers_state = $myrowsel["shipping_state"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_state; ?> 
			</TD>			
				<?php  $customers_postcode = $myrowsel["shipping_zip"]; ?>
				<?php  $billing_postcode = $myrowsel["bill_to_zip"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_postcode; ?> 
			</TD>
				<?php  $customers_telephone = $myrowsel["phone"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_telephone; ?> 
			</TD>
				<?php  $customers_email_address = $myrowsel["email"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $customers_email_address; ?> 
			</TD>						
				<?php  $item_name = $myrowsel["description"]; ?>
			<TD CLASS='<?php  echo $shade; ?>'>
				<?php  echo $item_name . $myrowsel["order_string"]; ?> 
			</TD>
			
			<TD CLASS='<?php  echo $shade; ?>'>
				<a href="<?php  echo $thispage; ?>?tbl=<?php  echo $_REQUEST["tbl"];?>&id=<?php  echo $id; ?>&proc=Delete&<?php  echo $pagevars; ?>&orders_id=<?php  echo $orders_id; ?>">Remove</a>
			</TD>
		</TR>
<?php 
			} while ($myrowsel = array_shift($result));
echo "</TABLE>";
echo "</form>";
/*----------------------------------------------------------------
PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS
----------------------------------------------------------------*/
	if ($reccount > 10) {
//IF THERE ARE MORE THAN 10 RECORDS PAGING
	$ttlpages = ($reccount / 10);
	if ($page < $ttlpages) {
	?>

<HR>	<br>
<A HREF="<?php  echo $thispage; ?>?tbl=<?php  echo $_REQUEST["tbl"];?>&posting=yes&page=<?php  echo $page; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>">Next <?php  echo $pagenorecords; ?> Records >></a>	<br>

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
	<A HREF="<?php  echo $thispage; ?>?tbl=<?php  echo $_REQUEST["tbl"];?>&posting=yes&page=<?php  echo $newpage; ?>&reccount=<?php  echo $reccount; ?>&searchcrit=<?php  echo $searchcrit; ?>&<?php  echo $pagevars; ?>"><< Previous <?php  echo $pagenorecords; ?> Records</a>
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
if ($proc == "Delete") {
?>
<a href="<?php  echo $thispage; ?>?proc=&<?php  echo $pagevars; ?>">Search</a><br>
<?php 

/*-------------------------------------------------------------------------------
DELETE RECORD SECTION 9995
-------------------------------------------------------------------------------*/
?>
<DIV CLASS='PAGE_STATUS'>Remove status Flag</DIV><br><br>
<?php 
/*-- SECTION: 9995CONFIRM --*/
if (!$delete) {
?>
		<DIV CLASS='PAGE_OPTIONS'>
			Are you sure you want to mark the remove flag?<BR><br>
<?php  $orders_id = $_GET["orders_id"];	?>
			 <a href="<?php  echo $thispage; ?>?tbl=<?php  echo $_REQUEST["tbl"];?>&id=<?php  echo $id; ?>&delete=yes&proc=Delete&orders_id=<?php  echo $orders_id; ?>&<?php  echo $pagevars; ?>">Yes</a>
			   <a href="<?php  echo $thispage; ?>?tbl=<?php  echo $_REQUEST["tbl"];?>&<?php  echo $pagevars; ?>">No</a>
		</DIV>
	<?php 
	} //IF !DELETE
	
if ($delete == "yes") {

	/*-- SECTION: 9995SQL --*/
	if ($_REQUEST["tbl"] == "newitem"){
		$sql = "UPDATE $sql_table SET sent = '2' WHERE id='$id' $addl_select_crit ";
	}else{
		$sql = "UPDATE $sql_table SET ship_status = 'X' WHERE id='$id' $addl_select_crit ";
	}
//echo "<BR>SQL: $sql<BR>";
	$result = db_query($sql,db() );		
	

	if (empty($result)) {
		if (!headers_sent()){    //If headers not sent yet... then do php redirect
			header('Location: http://b2c.usedcardboardboxes.com/pending_shipments.php?posting=yes&'); exit;
		}
		else
		{
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"pending_shipments.php?tbl=". $_REQUEST["tbl"]. "&posting=yes&\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=pending_shipments.php?tbl=". $_REQUEST["tbl"]. "&posting=yes&\" />";
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



</body>
</html>
