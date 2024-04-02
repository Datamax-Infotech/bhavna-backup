<?php
require ("inc/header_session.php");
require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");

db();
?>
	<title>B2B Online Customer Portal Login Tracker Report</title>
	<LINK rel='stylesheet' type='text/css' href='one_style.css' >
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css' >
	
	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		//var cal1xx = new CalendarPopup("list_div");
		//cal1xx.showNavigationDropdowns();
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		
		function loadmainpg() 
		{
			if(document.getElementById('date_from').value !="" && document.getElementById('date_to').value !="")
			{
				  document.rpt_leaderboard.action = "report_client_dash_log.php";
			}
			else
			{
				  alert("Please select date From/To.");
				  return false;
			}
		}

	</script>
	
<style>
.newtxttheam_withdot
{
	font-family:Arial;
	font-size:12px;
	padding:4px;
	background-color:#EFEEE7;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

	.main_data_css{
		margin: 0 auto;
		width: 100%;
		height: auto;
		clear: both !important;
		padding-top: 35px;
		margin-left: 10px;
		margin-right: 10px;
	}
</style>
<?php
$todaysDt = date('Y-m-d');
if(isset($_GET["date_from"]) && !empty($_GET["date_from"])){
	$date_from =  date("Y-m-d", strtotime($_GET["date_from"]));
}else{
	$date_from =  date('Y-m-d', strtotime( $todaysDt . " -1 month"));
}

if(isset($_GET["date_to"] ) && !empty($_GET["date_to"] )){
	$date_to =  date("Y-m-d" , strtotime($_GET["date_to"]));
}else{
	$date_to =  date('Y-m-d', strtotime( $todaysDt));;
}
?>

<?php include("inc/header.php"); ?>
	<br><br>
<div class="main_data_css">

	<div class="dashboard_heading" style="float: left;">
		<div style="float: left;">
		  B2B Customer Online Portal Login Summary Report
		</div>
	</div>

	&nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
	<span class="tooltiptext">This report allows the user to see who is using the B2B Online Customer Dashboards.</span></div><br>
	
	<form method="get" name="rpt_leaderboard" action="report_client_dash_log.php">
		<table>
			<tr>
				<td>Please select Date Range </td>
				<td>				
					From: 
						<input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date("m/d/Y", strtotime($date_from)); ?>" > 
						<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;"></div>		
					To: 
						<input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date("m/d/Y", strtotime($date_to)); ?>" > 
						<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
						<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;"></div>		
				</td>
				<td>
					<input type=submit value="Run Report" onClick="javascript: return  loadmainpg()">
				</td>
			</tr>
		</table>
	</form>

	<?php //if( $_GET["date_from"] !="" && $_GET["date_to"] !=""){ ?>
		<table width="80%" border="0" cellspacing="1" cellpadding="1" >
			<tr>
				<td class="style24" colspan=6 style="height: 16px" align="middle"><strong>Client Dashboard log</strong></td>
			</tr> 
			<tr>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>IP Address</strong>
				</td>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>Device Info</strong>
				</td>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>User Name</strong>
				</td>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>Company Name</strong>
				</td>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>Contact name</strong>
				</td>
				<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
					<strong>Log in Date Time</strong>
				</td>			
			</tr>
			<?php		
			/*$date_from =  date("Y-m-d H:i:s", strtotime($_GET["date_from"]));
			$date_to =  date("Y-m-d H:i:s" , strtotime($_GET["date_to"] . "23:59:59"));*/

			$dt_view_qry = "SELECT clientdashboard_user_log.*, um.user_name, um.companyid FROM clientdashboard_user_log left JOIN clientdashboard_usermaster AS um ON um.loginid = clientdashboard_user_log.userid";
			$dt_view_qry .= " WHERE (login_datetime between '" . $date_from . " 00:00:00' AND '" .$date_to." 23:59:59') ORDER BY clientdashboard_user_log.unqid DESC ";
			//echo $dt_view_qry;

			$dt_view_res = db_query($dt_view_qry );

			//echo "<pre>"; print_r($dt_view_res); echo "</pre>";

			while ($dt_view_row = array_shift($dt_view_res)) {
				$machine_ip = $dt_view_row["ipaddress"];
				$getCompNm = get_nickname_val('', $dt_view_row["companyid"]);
				db_b2b();
				$getCompContact = db_query("SELECT contact FROM companyInfo where ID = '".$dt_view_row['companyid'] ."'");
				$rowCompContact = array_shift($getCompContact);
				?>

				<tr>
					<td bgColor="#e4e4e4" class="style12" >
						<a href="https://tools.keycdn.com/geo?host=<?php echo $machine_ip ?>" target="_blank"><?php echo $dt_view_row["ipaddress"];?></a>
					 </td>
					<td bgColor="#e4e4e4" width="200px" class="style12" > <?php  echo $dt_view_row["client_device_info"];?> </td>
					<td bgColor="#e4e4e4" class="style12" > <?php  echo $dt_view_row["user_name"];?> </td>
					<td bgColor="#e4e4e4" class="style12" ><a href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"];?>" target="_blank"><?php echo $getCompNm;?></a></td>
					<td bgColor="#e4e4e4" class="style12" ><?php echo $rowCompContact["contact"];?></td>
					<td bgColor="#e4e4e4" class="style12" ><?php echo date("m/d/Y H:i:s", strtotime($dt_view_row["login_datetime"])) . " CT";?></td>
				</tr>

				<?php

			}
			?>
		</table>
		<?php
	//}
	?>
</div>