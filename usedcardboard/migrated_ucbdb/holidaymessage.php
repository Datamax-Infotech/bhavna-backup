<?php  
require ("inc/header_session.php");
?>

<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
?>
<!DOCTYPE html>

<html>
<head>
	<title>McCormick Report</title>
	<meta http-equiv="refresh" content="300">
</head>

<body>
	<div>
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">
<!--<a href="index.php">HOME</a><br><br>--><br>
<?php 
if ($_REQUEST["saved"] == 1)
{
$dt_view_qry = "UPDATE holiday_message SET message = '".  $_REQUEST["message"]  ."', messageon = ".  $_REQUEST["status"]  ." WHERE id=1";
$dt_view_res = db_query($dt_view_qry,db() );
echo "<font color=red>SAVED</font><br><br><br>";
}

?>




<?php 
echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>




<form method=get action="holidaymessage.php">

<?php 

$dt_view_qry = "SELECT * FROM holiday_message WHERE id = 1";
$dt_view_res = db_query($dt_view_qry,db() );

while ($dt_view_trl = array_shift($dt_view_res)) {

	if ($dt_view_trl["messageon"] == 1)
	{
?>
		Message: <textarea name="message" rows=5 cols=80><?php  echo $dt_view_trl["message"];?></textarea><br>
		<select name="status">
      <option value=1>ON</option>
	  <option value=0>OFF</option>
	
	
<?php  
	} else {
?>
    	Message: <textarea name="message" rows=5 cols=80><?php  echo $dt_view_trl["message"];?></textarea><br>
		<select name="status">
		<option value=0>OFF</option>
		<option value=1>ON</option>

<?php 
	}
} ?>	

</select>
<input type=hidden name="saved" value="1">
<input type=submit value="SAVE">
	</div>
</body>
</html>