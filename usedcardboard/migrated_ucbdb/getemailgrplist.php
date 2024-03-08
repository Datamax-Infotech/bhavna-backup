<?php 
	require ("inc/header_session.php");
	require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php"); 
	
	$sql = "SELECT * from email_config where unqid = " . $_REQUEST["selectedgrpid"];
	$result_n = db_query($sql,db() );
	echo "<table border='0' width='350px' class='style12'>";
	while ($myrowsel_n = array_shift($result_n)) {
		echo "<tr><td width='120'>From Email: </td><td width='60'><input type='text' name='fromeml' size='40' value='" . $myrowsel_n["from_email"]. "'/></td></tr>";
		echo "<tr><td width='120'>To Email: </td><td width='60'><input type='text' name='toeml' size='40' value='" . $myrowsel_n["to_email"]. "'/></td></tr>";
		echo "<tr><td width='120'>Cc Email: </td><td width='60'><input type='text' name='ccmeml' size='40' value='" . $myrowsel_n["cc_email"]. "'/></td></tr>";
	}
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td colspan='2' align='center'><input type='submit' name='updemlgrp' value='Update Email address'/></td></tr>";
	echo "</table>";
?>
