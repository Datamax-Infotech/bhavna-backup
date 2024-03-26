<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
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
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<?php // echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >"; ?>
		<BR>
		<?php
		if ($_REQUEST["action"] == 'run') {

			$sql2 = "UPDATE `sop_task` SET `employee` = '" . $_REQUEST["employee"] . "' ,`division` = '" . $_REQUEST["division"] . "',`department` = '" . $_REQUEST["department"] . "',`frequency` = '" . $_REQUEST["frequency"] . "' ,`name` = '" . $_REQUEST["task"] . "',`description` ='" . $_REQUEST["description"] . "' ,`details` = '" . $_REQUEST["details"] . "'";
			$sql2 .= " WHERE id = " . $_REQUEST["id"];


			$result2 = db_query($sql2);
			echo "<font color=red>RECORD UPDATED</font>";
		}

		?>
		<br>
		<form name="rptSearch" action="report_sop_edit.php" method="POST">
			<input type="hidden" name="action" value="run">
			<input type="hidden" name="backemployee" value="<?php echo $_REQUEST["backemployee"] ?>">
			<input type="hidden" name="backdivision" value="<?php echo $_REQUEST["backdivision"] ?>">
			<input type="hidden" name="backdepartment" value="<?php echo $_REQUEST["backdepartment"] ?>">
			<input type="hidden" name="backfrequency" value="<?php echo $_REQUEST["backfrequency"] ?>">
			<input type="hidden" name="backsearch" value="<?php echo $_REQUEST["backsearch"] ?>">
			<input type="hidden" name="id" value="<?php echo $_REQUEST["id"] ?>">
			<span class="style2">
				<a href="report_sop.php?action=run&employee=<?php echo ($_REQUEST["backemployee"]); ?>&division=<?php echo $_REQUEST["backdivision"] ?>&department=<?php echo $_REQUEST["backdepartment"] ?>&frequency=<?php echo $_REQUEST["backfrequency"] ?>&search=<?php echo $_REQUEST["backsearch"] ?>&id=<?php echo $_REQUEST["id"]; ?>">Back</a></span>
			<br>

			<span class="style13"><span class="style15">

					<br />
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						Employee:&nbsp;&nbsp;&nbsp;

						<select name="employee">
							<option value="A">Any Employee</option>
							<?php

							$sql2 = "SELECT * FROM ucbdb_employees WHERE status LIKE 'Active' ORDER BY name";
							$result2 = db_query($sql2);
							while ($myrowsel2 = array_shift($result2)) {

							?>
								<option value="<?php echo $myrowsel2["id"]; ?>" <?php
																					if ($myrowsel2["id"] == $_REQUEST["employee"]) {
																						echo " selected ";
																					}
																					?>><?php echo $myrowsel2["name"]; ?></option>
							<?php  } ?>
						</select>

						<br>
						Division:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name="division">
							<option value="A">Any Division</option>
							<?php

							$sql2 = "SELECT * FROM sop_division ORDER BY name";
							$result2 = db_query($sql2);
							while ($myrowsel2 = array_shift($result2)) {

							?>
								<option value="<?php echo $myrowsel2["id"]; ?>" <?php
																					if ($myrowsel2["id"] == $_REQUEST["division"]) {
																						echo " selected ";
																					}
																					?>><?php echo $myrowsel2["name"]; ?></option>
							<?php  } ?>
						</select>
						<br>
						Department:
						<select name="department">
							<option value="A">Any Department</option>
							<?php

							$sql2 = "SELECT * FROM sop_department ORDER BY name";
							$result2 = db_query($sql2);
							while ($myrowsel2 = array_shift($result2)) {

							?>
								<option value="<?php echo $myrowsel2["id"]; ?>" <?php
																					if ($myrowsel2["id"] == $_REQUEST["department"]) {
																						echo " selected ";
																					}
																					?>><?php echo $myrowsel2["name"]; ?></option>
							<?php  } ?>
						</select>
						<br>
						Frequency: &nbsp;&nbsp;
						<select name="frequency">
							<option value="A">Any Frequency</option>
							<?php

							$sql2 = "SELECT * FROM sop_frequency ORDER BY id";
							$result2 = db_query($sql2);
							while ($myrowsel2 = array_shift($result2)) {

							?>
								<option value="<?php echo $myrowsel2["id"]; ?>" <?php
																					if ($myrowsel2["id"] == $_REQUEST["frequency"]) {
																						echo " selected ";
																					}
																					?>><?php echo $myrowsel2["name"]; ?></option>
							<?php  } ?>
						</select>
						<br>
						<?php

						$sql2 = "SELECT * FROM sop_task WHERE id = " . $_REQUEST["id"];;
						$result2 = db_query($sql2);
						while ($myrowsel2 = array_shift($result2)) {

						?>

							Task Name:
							<input type="text" name="task" size="80" value="<?php echo $myrowsel2["name"] ?>">
							<br>
							Description:
							<input type="text" name="description" size="80" value="<?php echo $myrowsel2["description"] ?>">
							<br>
							<textarea name="details" cols="80" rows="17"><?php echo $myrowsel2["details"] ?></textarea>
						<?php
						}
						?>
						<br><br>



				</span><span class="style14"><span c6lass="style15">


						&nbsp; <input type="submit" value="   UPDATE TASK    ">
		</form>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

		<br></span></span></span><br>
		<br />


	</div>
</body>

</html>