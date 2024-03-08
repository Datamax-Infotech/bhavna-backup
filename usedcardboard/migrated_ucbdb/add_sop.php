<?php 
	require ("inc/header_session.php");
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>DASH - Reports - Standard Operating Procedures</title>	
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
</head>
<body>
	<div>
	<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
	<?php
	// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	?>
	<BR>
	<?php
		if ($_REQUEST["action"] == 'run') {

		$sql2 = "INSERT INTO `sop_task` (`id` ,`employee` ,`division` ,`department` ,`frequency` ,`name` ,`description` ,`details` )VALUES (NULL , ";
		$sql2 .= "'". $_REQUEST["employee"] ."', ";
		$sql2 .= "'". $_REQUEST["division"] ."', ";
		$sql2 .= "'". $_REQUEST["department"] ."', ";
		$sql2 .= "'". $_REQUEST["frequency"] ."', ";
		$sql2 .= "'". $_REQUEST["task"] ."', ";
		$sql2 .= "'". $_REQUEST["description"] ."', ";
		$sql2 .= "'". $_REQUEST["details"] ."')";

		$result2 = db_query($sql2,db() );
		echo "<font color=red>RECORD SUBMITTED</font>";
		}
	?>
	<br>
	<form name="rptSearch" action="add_sop.php" method="POST">
	<input type="hidden" name="action" value="run">
	<!--<span class="style2">
	<a href="index.php">Home</a></span>-->
	<br>

	<span class="style13"><span class="style15">

	<br /><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
	Employee:&nbsp;&nbsp;&nbsp; 

	<select name="employee">
	<option value="A">Select Employee</option>
	<?php 

	$sql2 = "SELECT * FROM ucbdb_employees WHERE status LIKE 'Active' ORDER BY name";
	$result2 = db_query($sql2,db() );
	while ($myrowsel2 = array_shift($result2)) {

	?>
	<option value="<?php echo $myrowsel2["id"]; ?>"><?php echo $myrowsel2["name"]; ?></option>
	<?php } ?>
	</select>
	<br>
	Division:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	<select name="division">
	<option value="A">Select Division</option>
	<?php 

	$sql2 = "SELECT * FROM sop_division ORDER BY name";
	$result2 = db_query($sql2,db() );
	while ($myrowsel2 = array_shift($result2)) {

	?>
	<option value="<?php echo $myrowsel2["id"]; ?>"><?php echo $myrowsel2["name"]; ?></option>
	<?php } ?>
	</select>
	<br>
	Department: 
	<select name="department">
	<option value="A">Select Department</option>
	<?php 

	$sql2 = "SELECT * FROM sop_department ORDER BY name";
	$result2 = db_query($sql2,db() );
	while ($myrowsel2 = array_shift($result2)) {

	?>
	<option value="<?php echo $myrowsel2["id"]; ?>"><?php echo $myrowsel2["name"]; ?></option>
	<?php } ?>
	</select>
	<br>
	Frequency: &nbsp;&nbsp;
	<select name="frequency">
	<option value="A">Select Frequency</option>
	<?php 

	$sql2 = "SELECT * FROM sop_frequency ORDER BY id";
	$result2 = db_query($sql2,db() );
	while ($myrowsel2 = array_shift($result2)) {

	?>
	<option value="<?php echo $myrowsel2["id"]; ?>"><?php echo $myrowsel2["name"]; ?></option>
	<?php } ?>
	</select>
	<br>
	Task Name:
	<input type="text" name="task" size="80">
	<br>
	Description:
	<input type="text" name="description" size="80">
	<br>
	<textarea name="details" cols="80" rows="17"></textarea>
	<br><br>



	</span><span class="style14"><span c6lass="style15">


	&nbsp; <input type="submit" value="   ADD TASK    "></form>
	<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

	<br></span></span></span><br>
	<br />
	</div>
</body>
</html>
