<?php
/*
Page Name: checkprocess.php
Page created By: Amarendra
Page created On: 03-04-2021
Last Modified On: 
Last Modified By: Amarendra
Change History:
Date           By            Description
==================================================================================================================
03-04-21      Amarendra     This file is created to handle submit request from sign in / register forms..
==================================================================================================================
*/
session_start();
require_once ("mainfunctions/database.php");
require_once ("mainfunctions/general-functions.php"); 
db();


// Login request
if (isset($_POST["btnsignin"]) && $_POST["btnsignin"] == "login" )
{
	$myusername = $_POST['username']; 
	$mypassword= stripslashes($_POST['password']); 

	$rec_found = "no";
	$sql="SELECT * FROM b2becommerce_user_master WHERE email=? ";
	$result=db_query($sql, array("s"), array($myusername));
	while ($rq = array_shift($result)) {
		if ($mypassword == $rq["password"]){	
			$rec_found = "yes";
			$employeeid = $rq["userid"];
			$firstname = $rq["first_name"];
			break;
		}
	}

	if(strpos($_POST["sendurl"], '?') == false ) {
		$redirectUrl = $_POST["sendurl"]."?id=".$_POST['boxId'];
	}else{
		$redirectUrl = $_POST["sendurl"];
	}

	if($rec_found == "yes"){
		$date_of_expiry = time() + 600000 ;
		setcookie( "username", $firstname, $date_of_expiry );
		setcookie( "uid", $employeeid, $date_of_expiry );
		//header("Location: " . $_POST["sendurl"]);
		header("Location: " . $redirectUrl);
  	} else {
        //echo "Incorrect password";
		//header("Location: " . $_POST["sendurl"]);
		header("Location: " . $redirectUrl);
	}
		
} 


// register / Sign up request
if(isset($_POST["confirmsignup"]) && $_POST["confirmsignup"] == "register"){
	$firstname = stripslashes($_POST['firstname']); 
	$lastname = stripslashes($_POST['lastname']);
	$companyNm = stripcslashes($_POST['companyNm']);
	$useremail = stripslashes($_POST['email']); 
	$phone = stripcslashes($_POST['phone']);
	$password = stripslashes($_POST['password']);
	$comid = stripslashes($_POST['comid']);

	$qryUserDt 		= "SELECT * FROM b2becommerce_user_master WHERE email = ? ";
	$resUserDt 		= db_query($qryUserDt, array("s"), array($useremail));

	if(empty($resUserDt)){
		$sql = "INSERT INTO b2becommerce_user_master (first_name, last_name, email, password, companyid, phone) VALUES ('".$firstname."', '".$lastname."', '".$useremail."', '".$password."', '".$companyNm."', '".$phone."' )";
		$qry_res=db_query($sql);
		$insertid = tep_db_insert_id();
	}
	/*$sql = 'INSERT INTO b2becommerce_user_master (`first_name`, `last_name`, `email`, `password`, `companyid`) ';
	$sql .= 'VALUES ("'. $firstname .'", "'. $lastname .'", "'. $useremail .'", "'. $password .'", "'. $comid .'")';
	$qry_res=db_query($sql);
	$insertid = tep_db_insert_id();*/

	if(strpos($_POST["sendurl"], '?') == false ) {
		$redirectUrl = $_POST["sendurl"]."?id=".$_POST['boxId'];
	}else{
		$redirectUrl = $_POST["sendurl"];
	}

	if ($insertid != ""){
		$date_of_expiry = time() + 600000 ;
		setcookie( "username", $firstname, $date_of_expiry );
		setcookie( "uid", $insertid, $date_of_expiry );
		//echo "One row inserted";
		//header("Location: " . $_REQUEST["sendurl"]);
		header("Location: " . $redirectUrl);
	} else {
		//header("Location: " . $_REQUEST["sendurl"]);
		header("Location: " . $redirectUrl);
	}
} 


// log out request
if(isset($_POST["logoff"]) && $_POST["logoff"] == "logout"){
		$date_of_expiry = time() + 600000 ;
		setcookie( "username", "", $date_of_expiry );
		setcookie( "uid", "", $date_of_expiry );
		if(strpos($_POST["sendurl"], '?') == false ) {
			$redirectUrl = $_POST["sendurl"]."?id=".$_POST['boxId'];
		}else{
			$redirectUrl = $_POST["sendurl"];
		}
		//header("Location: " . $_POST["sendurl"]);
		header("Location: " . $redirectUrl);

}


//forget password request
if (isset($_POST["forpassword"]) && $_POST["forpassword"] == "forgetpass" )
{
$myusername = $_POST['forgetemail']; 

$rec_found = "no";
$sql="SELECT * FROM `b2becommerce_user_master` WHERE `email` ='". $myusername ."' limit 1";
$result = db_query($sql, db() );
while ($rq = array_shift($result)) {
		$mypassword = $rq["password"];
		$first = $rq["first_name"];
		$last = $rq["last_name"];
		$rec_found = "yes";
}
$subject = "In reply of your request for password";
$message = "Dear Mr./Mrs. ". $last .", ". "\r\n" . "We have received a request for password. If you have not generate ";
$message .= "request for password than please ingorm us.". "\r\n" ."Your Current password is ". $mypassword .".";
$headers = "From: someone@usedcard.com" . "\r\n" .
			"CC: amarendra.singh@extractinfo.com";

if(strpos($_POST["sendurl"], '?') == false ) {
	$redirectUrl = $_POST["sendurl"]."?id=".$_POST['boxId'];
}else{
	$redirectUrl = $_POST["sendurl"];
}
if($rec_found == "yes"){
	$emailsent = mail($myusername, $subject, $message, $headers);
    //header("Location: " . $_POST["sendurl"]);
    header("Location: " . $redirectUrl);
  } else {
	  //header("Location: " . $_POST["sendurl"]);
	  header("Location: " . $redirectUrl);
	}

}