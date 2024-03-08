<?php 
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
error_reporting(E_WARNING|E_PARSE);

$thispage	= $SCRIPT_NAME; //SET THIS TO THE NAME OF THIS FILE
$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...
$addslash="yes";
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
//end function db

/*

$rowid = $id;
$idcolname = 'id';
$rowpriority = $rank;
$otherrowpriority = $rowpriority - 1;
$tablename = 'ucbdb_customer_log_config';
$sql_1 = "UPDATE $tablename SET `rank` = $rowpriority WHERE `Priority` = $otherrowpriority";
$sql_2 = "UPDATE $tablename SET `rank` = $otherrowpriority WHERE `$idcolname` = $rowid";
db_query($sql_1, db() );
db_query($sql_2, db() );

*/ 	
	
$sql = "UPDATE ucbdb_reason_code SET rank = rank + 1 WHERE rank >= $prank";
db_query($sql,db() );
$sql = "UPDATE ucbdb_reason_code SET rank=$prank WHERE id = $id";
db_query($sql,db() );
$prank=0;	



echo "<DIV CLASS='SQL_RESULTS'>Updated</DIV>";
header('Location: reason_list.php?posting=yes');

?>
