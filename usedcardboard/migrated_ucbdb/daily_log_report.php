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

	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		
		function loadmainpg() 
		{
			if(document.getElementById('date_from').value !="" && document.getElementById('date_to').value !="")
			{
				  //document.frmactive.action = "adminpg.php";
			}
			else
			{
				  alert("Please select date From/To.");
				  return false;
			}
		}
	</script>
</head>

<body>
<?php 
$user_initials = $_COOKIE['userinitials'] ;
?>
<br/>

<form method="get" name="rpt_leaderboard" action="daily_log_report.php">
	<table border="0"><tr>
			<td>Date Range Selector:</td>
			<td>
					From: 
						<input type="text" name="date_from" id="date_from" size="10" value="<?php  echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>" > 
						<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
					To: 
						<input type="text" name="date_to" id="date_to" size="10" value="<?php  echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>" > 
						<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
			</td>
			<td>
				<input type=submit value="Run Report">
			</td>
			</tr>
	</table>
</form>
<?php 

	$in_dt_range = "no";
	if( $_GET["date_from"] !="" && $_GET["date_to"] !=""){
		$date_from_val = date("Y-m-d", strtotime($_GET["date_from"]));
		$date_to_val = date("Y-m-d", strtotime($_GET["date_to"]));
		$in_dt_range = "yes";
	}
	
if ($in_dt_range == "yes"){
?>
	<table border="0" >
		<tr><td colspan="5" align="center" style="font-size:16pt;"><strong>Call details Report</strong></td></tr>
		<tr><td colspan="5" align="left" >
			<table cellSpacing="1" cellPadding="1" border="0">
					<tr>
						<td bgColor='#ABC5DF'>Employee Initials</td>
						<td bgColor='#ABC5DF'>Call Category</td>
						<td bgColor='#ABC5DF'>Number of Minutes</td>
					</tr>
				<?php 
					$flg = 0; $totmin = 0;
					$sql = "SELECT user_initials, DATE_FORMAT(call_date, '%m/%d/%Y') as call_date, category, sum(call_mins) as summin  FROM call_daily_log left join call_category_master on call_category_master.category_id = call_daily_log.call_category where DATE_FORMAT(call_date, '%Y-%m-%d') between '" . $date_from_val . "' and '" . $date_to_val . "' GROUP BY user_initials, category ORDER BY category";
					
					$result = db_query($sql, db() );
					while ($rowemp = array_shift($result)) { ?>
					<tr>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["user_initials"]; ?>
						</td>
						<td bgColor='#E4EAEB'>
							<?php  echo $rowemp["category"]; ?>
						</td>
						<td bgColor='#E4EAEB' align="right">
							<?php  echo $rowemp["summin"]; ?>
						</td>
					</tr>
				<?php  
					$totmin = $totmin + $rowemp["summin"];
				}?>
					<tr>
						<td colspan="2" bgColor='#ABC5DF' align="right">Total:</td>
						<td bgColor='#ABC5DF' align="right"><?php  echo $totmin; ?></td>
					</tr>
			</table>
	</td></tr>
	</table>
<?php  }?>

</body>
</html>
