<?php  
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
?>

<!DOCTYPE html>

<html>
<head>
	<title>Daily Log</title>

<link rel="stylesheet" href="sorter/style_rep.css" />
<style type="text/css">
	.txtstyle_color
	{
	font-family:arial;
	font-size:12;
	height: 16px; 
	background:#ABC5DF;
	}

	.txtstyle
	{
		font-family:arial;
		font-size:12;
	}

</style>	

<script LANGUAGE="JavaScript">

	function chkdata(){
		if (document.getElementById('txtnomin').value.trim() == ""){
			alert("Please enter the Minutes.");
			return false;
		}else {
			return true;
		}
	}

</script>

</head>

<body>
<?php 
$user_initials = $_COOKIE['userinitials'] ;

if (isset($_POST["txtnomin"]))
{

	$sql = "Insert into call_daily_log (user_initials, call_category, call_mins, call_notes, call_date) ";
	$sql .= " values('" . $user_initials . "', '" . preg_replace( "/'/", "\'", $_POST["call_cat"]) . "','" . $_POST["txtnomin"] . "','" . preg_replace( "/'/", "\'", $_POST["call_comments"]) . "','" . date("Y-m-d H:i:s") . "')";

	$result = db_query($sql, db() );
	
}
?>
<br/>
	<table border="0" >
	<tr><td colspan="5" align="center" style="font-size:16pt;"><strong>Enter Call details</strong></td></tr>
	<tr><td colspan="5" align="center" style="font-size:12pt;"><a target="_blank" href="daily_log_report.php">Call details Report</a></td></tr>
	<tr><td colspan="5" align="left" >
		<form method="post" name="daily_log" action="daily_log.php"  onsubmit="return chkdata();">
			<table border="0">
				<tr>
					<td>CALL Category:</td>
					<td>
						<select name="call_cat" id="call_cat">
					<?php 
						$sql = "SELECT category_id, category FROM call_category_master where active = 1 ORDER BY category";
						$result = db_query($sql, db() );
						while ($rowemp = array_shift($result)) { ?>
							<option value="<?php  echo $rowemp["category_id"]; ?>"><?php  echo $rowemp["category"]; ?></option>
						<?php }?>	
						</select>
					</td>
					<td>
						No of Minutes:&nbsp;<input type="text" name="txtnomin" id="txtnomin" />
					</td>
				</tr>
				<tr>
					<td>Comments:</td>
					<td colspan="2">
						<textarea name="call_comments" id="call_comments" cols="40" rows="4"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
						<input type="submit" name="btnsave" value="Save Data">
					</td>
				</tr>
			</table>
		</form>
	</td></tr>
	<tr><td colspan="5" align="center" style="font-size:16pt;"><strong>Entered Call details for Today</strong></td></tr>
	<tr><td colspan="5" align="left" >
			<table cellSpacing="1" cellPadding="1" border="0">
					<tr>
						<td bgColor='#ABC5DF'>Entry Date</td>
						<td bgColor='#ABC5DF'>Call Category</td>
						<td bgColor='#ABC5DF'>Minutes</td>
						<td bgColor='#ABC5DF'>Comments</td>
					</tr>
				<?php 
					$flg = 0;
					$sql = "SELECT * FROM call_daily_log left join call_category_master on call_category_master.category_id = call_daily_log.call_category where DATE_FORMAT(call_date, '%Y-%m-%d') = '" . Date("Y-m-d") . "' and user_initials = '" . $_COOKIE['userinitials'] . "' ORDER BY unqid";
					$result = db_query($sql, db() );
					while ($rowemp = array_shift($result)) { ?>
					<tr>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["call_date"]; ?>
						</td>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["category"]; ?>
						</td>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["call_mins"]; ?>
						</td>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["call_notes"]; ?>
						</td>
					</tr>
				<?php  }?>
			</table>
	</td></tr>
	</table>

</body>
</html>
