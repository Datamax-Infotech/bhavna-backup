<?php
    $date_of_expiry = time() - 60001 ;
    setcookie( "userloggedin", $myusername, $date_of_expiry );
    // echo $myusername;
    header("location:login.php");
?>

