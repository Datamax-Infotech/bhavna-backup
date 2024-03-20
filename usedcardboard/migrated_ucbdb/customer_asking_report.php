<?php  //session_start();
//if(!session_is_registered(myusername)){
if(!$_COOKIE['userloggedin']){
header("location:login.php");
}
require ("inc/header_session.php");
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<style type="text/css">
			span.infotxt:hover {text-decoration: none; background: #ffffff; z-index: 6;cursor:pointer; }
			span.infotxt span {position: absolute; left: -9999px; margin: 20px 0 0 0px; padding: 3px 3px 3px 3px; z-index: 6;}
			span.infotxt:hover span {left: 5%; background: #ffffff;} 
			span.infotxt span {position: absolute; left: -9999px; margin: 1px 0 0 0px; padding: 0px 3px 3px 3px; border-style:solid; border-color:black; border-width:1px;}
			span.infotxt:hover span {margin: 1px 0 0 170px; background: #ffffff; z-index:6;} 
		</style>
		
		<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
		<SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
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
	<?php echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";?>
	
		<form method="get" name="rpt_customer" action="customer_asking_report.php">
			<table>
				<tr>
					<td>
						From:
							<input type="text" name="date_from" id="date_from" size="10" value="<?php  echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>" > 
							<a href="#" onclick="cal2xx.select(document.rpt_customer.date_from,'dtanchor2xx','yyyy-MM-dd'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
							<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
						&nbsp;&nbsp;To: 
							<input type="text" name="date_to" id="date_to" size="10" value="<?php  echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>" > 
							<a href="#" onclick="cal2xx.select(document.rpt_customer.date_to,'dtanchor3xx','yyyy-MM-dd'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
							<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>			
					</td>
					<td>
						&nbsp;<input type=submit value="Run Report" onClick="javascript: return  loadmainpg()">
					</td>
				</tr>
				</table>
		</form>
				<?php 
				if(isset($_GET["date_from"])) {// && (isset($_GET["date_to"]))){
				if($_GET["date_from"] !=""){// && $_GET["date_to"] !=""){
				?>
				<br/>
				<table>
				<tr>
					<td class="style24" colspan=12 style="height: 16px" align="middle"><strong>CUSTOMER ASKING FOR</strong></td>
				</tr>
				
				<tr>
					<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Customer asking for</strong></td>
					<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>No. of times asked</strong></td>
					<td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Date</strong></td>
				</tr>
				
				<?php 
				$qry = "select *, count(*) as cnt from ucbdb_customer_asking_trans inner join ucbdb_customer_asking_txtmaster on ucbdb_customer_asking_txtmaster.uid = ucbdb_customer_asking_trans.reason_id where (reason_dt >='".$_REQUEST["date_from"]."') AND (reason_dt <='".$_REQUEST["date_to"]." 23:59:59') group by reason_id order by reason_id"; 
				//echo $qry;
				db();
				$res = db_query($qry);
				while ($myrowsel = array_shift($res))
				{?>
				<tr>
					<td bgColor="#e4e4e4" class="style12" >
						<span class="infotxt"><u><?php  echo $myrowsel['reason']; ?></u>
							<span>
								<table cellSpacing="1" cellPadding="1" border="0">
									<tr align="middle">
										<td class="style7" colspan="3" style="height: 16px"><strong>Customer Details</strong></td>
									</tr>

									<tr vAlign="center">
										<!--<td bgColor="#e4e4e4" width="15" align="center"><font size=1>
										<strong>ID</strong></font></td>-->
										<td bgColor="#e4e4e4" align="center">
											<font size=1><strong>Customer asking for</strong></font>
										</td>
										<?php if($myrowsel['reason']=='Other'){?>
										<td bgColor="#e4e4e4" align="center">
											<font size=1><strong>Other text</strong></font>
										</td>	
										<?php }else{}?>										
										<td bgColor="#e4e4e4" align="center">
											<font size=1><strong>User Name</strong></font>
										</td>
										<td bgColor="#e4e4e4" align="center">
											<font size=1><strong>Date</strong></font>
										</td>
									</tr>
									<?php 
										$get_sales_order = db_query("select * from ucbdb_customer_asking_trans inner join ucbdb_customer_asking_txtmaster on ucbdb_customer_asking_txtmaster.uid = ucbdb_customer_asking_trans.reason_id where reason_id=".$myrowsel['reason_id']); // reason_id=".$myrowsel['reason_id']);
										while ($boxes = array_shift($get_sales_order)) {
											$cus_reason = $boxes["reason"];
											$cus_user = $boxes["user_intitial"];
											$cus_dt = $boxes["reason_dt"];
											$reason_txt = $boxes["reason_text"];
										?>	
										<tr bgColor="#e4e4e4">
											<td height="13" class="style1" align="left"><Font Face='arial' size='1'>
												<?php  echo $cus_reason; ?>
											</td>
											<?php if($myrowsel['reason']=='Other'){?>
											<td height="13" class="style1" align="left"><Font Face='arial' size='1'>
												<?php  echo $reason_txt; ?>
											</td>
											<?php }else{}?>
										
											<td height="13" class="style1" align="left"><Font Face='arial' size='1'>
												<?php  echo $cus_user; ?>
											</td>
											<td height="13" class="style1" align="left"><Font Face='arial' size='1'>
												<?php  echo $cus_dt; ?>
											</td>								
										</tr>
										<?php }?>
								</table>
							</span>
						</span>
					</td>
					<td bgColor="#e4e4e4" class="style12" ><?php echo $myrowsel['cnt'];?></td>
					<td bgColor="#e4e4e4" class="style12" ><?php echo $myrowsel['reason_dt'];?></td>
				</tr>	
				<?php }}}?>
			</table>
	</body>
</html>