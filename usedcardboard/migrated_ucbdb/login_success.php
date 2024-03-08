<?php 
session_start();
if(!isset($_SESSION['myusername'])){
    header("location:login.php");
}
?>

<html>
<body>
Login Successful  Please wait while you are redirected.
</body>
</html>
