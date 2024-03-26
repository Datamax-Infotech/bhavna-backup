<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
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

		select,
		input {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #000000;
			font-weight: normal;
		}
	</style>
</head>

<body>
	<div>
		<?php  //include("inc/header.php"); 
		?>
	</div>
	<div class="main_data_css">
		<?php // echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >"; 
		?>
		<table cellSpacing="1" cellPadding="1" width="800" border="0">
			<tr align="middle">
				<td colSpan="10" class="style7">
					REFERRED ORDER REPORT
				</td>
			</tr>
			<tr>
				<td width="10" class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						EMPLOYEE
					</font>
				</td>
				<td width="10" class="style17">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DIVISION
					</font>
				</td>
				<td width="10" class="style5">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DEPARTMENT
					</font>
				</td>
				<td width="10" align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						FREQUENCY
					</font>
				</td>
				<td align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						TASK
					</font>
				</td>
				<td align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DESCRIPTION
					</font>
				</td>
				<td width="10" align="middle" class="style16">
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						DETAILS
					</font>
				</td>
			</tr>

			<?php
			db();
			$query = "SELECT ucbdb_employees.Name AS A, sop_division.name AS B, sop_department.name AS C, sop_frequency.name AS D, sop_task.name AS E, sop_task.description AS F, sop_task.details AS G  FROM sop_task INNER JOIN ucbdb_employees ON sop_task.employee = ucbdb_employees.id INNER JOIN sop_division ON sop_task.division = sop_division.id INNER JOIN sop_department ON sop_task.department = sop_department.id INNER JOIN sop_frequency ON sop_task.frequency = sop_frequency.id WHERE sop_task.id = " . decrypt_url($_REQUEST["id"]);
			echo "<font color=red>" . $query . "</font>";
			$res = db_query($query);
			while ($row = array_shift($res)) {

			?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["A"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["B"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["C"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["D"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["E"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["F"]; ?>
						</font>
					</td>
					<td bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<a href="">Details</a>
						</font>
					</td>
				</tr>
				<tr vAlign="center">
					<td colspan="7" bgColor="#e4e4e4" class="style3">
						<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
							<?php echo $row["G"]; ?>
						</font>
					</td>
				</tr>
			<?php
			}
			?>
		</table>
		</form>
	</div>
</body>

</html>