<?php 
//session_start();
//if(!session_is_registered(myusername)){
if(!$_COOKIE['userloggedin']){
header("location:login.php");
}
?>