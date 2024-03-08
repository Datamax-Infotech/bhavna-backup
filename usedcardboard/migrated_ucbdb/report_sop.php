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
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">

<?php 
// echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
?>


<BR>




<!--<span class="style2">
<a href="index.php">Home</a></span><br>-->
<br>

<span class="style13"><span class="style15">

<br /><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
<table><tr><td>
<form name="rptSearch" action="report_sop.php" method="GET">
<input type="hidden" name="action" value="run">
View SOPs for

<input type="hidden" name="employee" value="A">

<select name="division">
<option value="A">Any Function</option>
<?php  

$sql2 = "SELECT * FROM sop_division ORDER BY name";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {

?>
<option value="<?php  echo $myrowsel2["id"]; ?>"<?php 
if ($myrowsel2["id"] == $_REQUEST["division"]) {
echo " selected ";
}
?>><?php  echo $myrowsel2["name"]; ?></option>
<?php  } ?>
</select>

<select name="department">
<option value="A">Any Format</option>
<?php  

$sql2 = "SELECT * FROM sop_department ORDER BY name";
$result2 = db_query($sql2,db() );
while ($myrowsel2 = array_shift($result2)) {

?>
<option value="<?php  echo $myrowsel2["id"]; ?>"<?php 
if ($myrowsel2["id"] == $_REQUEST["department"]) {
echo " selected ";
}
?>><?php  echo $myrowsel2["name"]; ?></option>
<?php  } ?>
</select>


<input type="hidden" name="frequency" value="A">







</span>

&nbsp; <input type="submit" value="   Show    "></form>
</td>
<td width="150">&nbsp;

</td>
<td>
<form action="report_sop.php" method="GET">
		<input type="hidden" name="action" value="run">
		<input type="hidden" name="employee" value="A">
		<input type="hidden" name="division" value="A">
		<input type="hidden" name="department" value="A">
		<input type="hidden" name="frequency" value="A">
		Keyword Search: <input type=text size=25 name="search"> <input type=submit size=10 value="Search"> 
</form>
</td></tr></table>		
  <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

<br></span><br>
<br />

<?php 



if ($_REQUEST["action"] == 'run') {


?>




<table cellSpacing="1" cellPadding="1" width="1000" border="0">
	<tr align="middle">
		<td colSpan="10" class="style7">
		STANDARD OPERATING PROCEDURES REPORT</td>
	</tr>
	<tr>
		<td width="10" class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		EMPLOYEE</font></td>
		<td  width="10" class="style17">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
		DIVISION</font></td>
		<td  width="10" class="style5" >
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		DEPARTMENT</td>
		<td  width="10" align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		FREQUENCY</td>
		<td align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		TASK</td>
		<td align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		DESCRIPTION</td>
		<td  width="10" align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		DETAILS</td>
		<td  width="10" align="middle" class="style16">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">		
		EDIT</td>
	</tr>
	
<?php 
$query = "SELECT ucbdb_employees.Name AS A, sop_division.name AS B, sop_department.name AS C, sop_frequency.name AS D, sop_task.name AS E, sop_task.description AS F , sop_task.description AS G, sop_task.id AS H, sop_task.details AS I , sop_task.employee AS G1, sop_task.division AS G2, sop_task.department AS G3, sop_task.frequency AS G4 FROM sop_task INNER JOIN ucbdb_employees ON sop_task.employee = ucbdb_employees.id INNER JOIN sop_division ON sop_task.division = sop_division.id INNER JOIN sop_department ON sop_task.department = sop_department.id INNER JOIN sop_frequency ON sop_task.frequency = sop_frequency.id ";

if ($_REQUEST["employee"] != 'A' ){
	$query .= "WHERE sop_task.employee = " . $_REQUEST["employee"];
} else {
	$query .= "WHERE sop_task.employee > -1 ";

}

if ($_REQUEST["division"] != 'A' ){
	$query .= " AND sop_task.division = " . $_REQUEST["division"];
} else {
	$query .= " AND sop_task.division > -1 ";

}

if ($_REQUEST["department"] != 'A' ){
	$query .= " AND sop_task.department = " . $_REQUEST["department"];
} else {
	$query .= " AND sop_task.department > -1 ";

}

if ($_REQUEST["frequency"] != 'A' ){
	$query .= " AND sop_task.frequency = " . $_REQUEST["frequency"];
} else {
	$query .= " AND sop_task.frequency > -1 ";

}

if ($_REQUEST["search"] != ""){
$query = "SELECT ucbdb_employees.Name AS A, sop_division.name AS B, sop_department.name AS C, sop_frequency.name AS D, sop_task.name AS E, sop_task.description AS F , sop_task.description AS G, sop_task.id AS H, sop_task.details AS I , sop_task.employee AS G1, sop_task.division AS G2, sop_task.department AS G3, sop_task.frequency AS G4 FROM sop_task INNER JOIN ucbdb_employees ON sop_task.employee = ucbdb_employees.id INNER JOIN sop_division ON sop_task.division = sop_division.id INNER JOIN sop_department ON sop_task.department = sop_department.id INNER JOIN sop_frequency ON sop_task.frequency = sop_frequency.id ";
$query .= " WHERE sop_task.name LIKE '%" . $_REQUEST["search"] . "%' OR sop_task.description LIKE '%" . $_REQUEST["search"] . "%' OR sop_task.details LIKE '%" . $_REQUEST["search"] . "%'";

}

//echo "<font color=red>".$query."</font>";
$res = db_query($query,db());
while($row = array_shift($res))
{

	?>
		<tr vAlign="center">
			<td bgColor="#e4e4e4" class="style3" ><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["A"]; ?></td>
			<td bgColor="#e4e4e4" class="style3" ><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["B"]; ?></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["C"]; ?></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["D"]; ?></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["E"]; ?></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php  echo $row["F"]; ?></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<a href="report_sop.php?action=run&employee=<?php echo $_REQUEST["employee"];?>&division=<?php echo $_REQUEST["division"];?>&department=<?php echo $_REQUEST["department"];?>&frequency=<?php echo $_REQUEST["frequency"];?>&search=<?php echo $_REQUEST["search"];?>&id=<?php echo $row["H"];?>" >Details</a></td>
			<td bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<a href="report_sop_edit.php?employee=<?php echo $row["G1"];?>&division=<?php echo $row["G2"];?>&department=<?php echo $row["G3"];?>&frequency=<?php echo $row["G4"];?>&backemployee=<?php echo $_REQUEST["employee"];?>&backdivision=<?php echo $_REQUEST["division"];?>&backdepartment=<?php echo $_REQUEST["department"];?>&backfrequency=<?php echo $_REQUEST["frequency"];?>&backsearch=<?php echo $_REQUEST["search"];?>&id=<?php echo $row["H"];?>" >Edit</a></td>
		</tr>
<?php 
	if ($_REQUEST["id"] == $row["H"]) {
?>
		<tr>
			<td colspan="8" bgColor="#e4e4e4" class="style3"><font face="Arial, Helvetica, sans-serif" color="#333333" size="1">	
			<?php echo $row["I"];?>
			</td>
		</tr>
<?php 
	}
?>


<?php  

}
?>
</table>

<?php 

}

?>
</form>
	</div>
</body>
</html>
