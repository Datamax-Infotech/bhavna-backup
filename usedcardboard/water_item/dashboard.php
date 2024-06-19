<?php
session_start();
if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>UCB Zero Waste</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <link rel="shortcut icon" href="images/logo.jpg" type="image/jpg">
  <link href="css/home.css" rel="stylesheet">
  <link href="css/header-footer.css" rel="stylesheet">	
  <link href="css/home-table.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
<!--Menu =========================================================================================================================-->
     <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"> 
<!--End Menu ====================================================================================================================-->   
    
    
	<script language="javascript">
		function openInNewTab(url) {
			var win = window.open(url, '_blank');
			win.focus();
		}
	
		function showdetailrep(supplierid, parent_comp_id, start_date, end_date) 
		{	
			openInNewTab("detailed-waste-report.php?warehouse_id=" + supplierid + "&parent_comp_id=" + parent_comp_id + "&date_from=" + start_date + "&date_to="+ end_date + "&child_loc=all_loc");
		}
		
		function repsummaryinton() 
		{	
			document.getElementById('repsummarytype').value = "yes";
			document.frmrepsummary.submit();
		}
		
		function repsummaryinlbs() 
		{	
			document.getElementById('repsummarytype').value = "";
			document.frmrepsummary.submit();
		}
		
	</script>
	
	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window,document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '1109377375928443'); 
	fbq('track', 'PageView');
	</script>
	<noscript>
	<img height="1" width="1" 
	src="https://www.facebook.com/tr?id=1109377375928443&ev=PageView
	&noscript=1"/>
	</noscript>
	<!-- End Facebook Pixel Code -->
			
</head>

<body>


<?
//	require ("../../securedata/config_prod_mysqli.php");
	//require("../securedata/main-enc-class.php");
	
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");

	db();
error_reporting(0);
	function getnickname_warehouse($warehouse_name, $loopid){
		$nickname = "";
		if ($loopid > 0) {
			db_b2b();
			$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where loopid = ?";
			$result_comp = db_query($sql , array("i"), array($loopid));
			while ($row_comp = array_shift($result_comp)) {
				if ($row_comp["nickname"] != "") {
					$nickname = $row_comp["nickname"];
				}else {
					$tmppos_1 = strpos($row_comp["company"], "-");
					if ($tmppos_1 != false)
					{
						$nickname = $row_comp["company"];
					}else {
						if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
						{
							$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
						}else { $nickname = $row_comp["company"]; }
					}
				}
			}
			db();
		}else {
			$nickname = $warehouse_name;
		}
		
		return $nickname;
	}	
	
	$companyid = 0;	$warehouse_id = 0; $company_name = ""; $company_logo = "blank-logo.jpg";  $parent_comp_flg = 0; $warehouse_id_cronjobs = 0;
	$main_company_name = "";
	$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1" ;
	//echo $sql . "<br>";
	$result = db_query($sql, array("i") , array($_SESSION['loginid']));
	while ($myrowsel = array_shift($result)) {
		$parent_comp_flg = $myrowsel['parent_comp_flg'];
		
		if ($parent_comp_flg == 1){
			$child_found = "no";		
			db_b2b();
			$sql1 = "select ID from companyInfo where parent_comp_id= ? and parent_child ='Child' and haveNeed = 'Have Boxes'";
			$result1 = db_query($sql1, array("i") , array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$child_found = "yes";	
			}
			db();
			if ($child_found == "no") {
				$parent_comp_flg = 0;
			}
		}

		$sql1 = "SELECT logo_image FROM supplierdashboard_details WHERE companyid=? " ;
		$result1 = db_query($sql1, array("i") , array($myrowsel["companyid"]));
		while ($myrowsel1 = array_shift($result1)) {
			if ($myrowsel1["logo_image"] != "")
			{
				$company_logo = $myrowsel1["logo_image"];
			}	
		}

		$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? " ;
		$result1 = db_query($sql1, array("i") , array($myrowsel["companyid"]));
		while ($myrowsel1 = array_shift($result1)) {
			$warehouse_id = $myrowsel1["id"];
			$company_name = $myrowsel1["company_name"] . "'s";
			$main_company_name = getnickname_warehouse($myrowsel1["company_name"], $warehouse_id);
		}
		
		if ($parent_comp_flg == 1 && $warehouse_id == 0) {
			$warehouse_id_cronjobs = $myrowsel["companyid"];
		}else{
			$warehouse_id_cronjobs = $warehouse_id;
		}
		
		$companyid = $myrowsel["companyid"];
	}
	
	if (isset($_REQUEST["inv_rep_yr"])){
		$selected_yr = $_REQUEST["inv_rep_yr"];
	}else{
		$selected_yr = date("Y");
	}

	$st_date = date($selected_yr . "-01-01");
	$end_date = date($selected_yr . "-m-d");
	
	$total_cost = 0; $high_pay_vendor = 0; $costly_vendor = 0; $high_pay_vendor_val = ""; $costly_vendor_val = ""; $tree_saved = "";
	$best_finance_impact = ""; $best_finance_impact_val = "";  $best_landfil_diversion = ""; $best_landfil_diversion_val = "";
	$lowest_finance_impact_val = ""; $lowest_landfil_diversion_val = ""; $lowest_finance_impact = ""; $lowest_landfil_diversion ="";	
	$sql1 = "SELECT * FROM water_cron_fordash WHERE warehouse_id = ? and data_year = ?";
	$result1 = db_query($sql1, array("i", "i"), array($warehouse_id_cronjobs, $selected_yr));
	while ($myrowsel1 = array_shift($result1)) {
		$total_cost = $myrowsel1["waste_financial"];
		$high_pay_vendor = $myrowsel1["high_pay_vendor"];

		$high_pay_vendor_val = number_format($myrowsel1["high_pay_vendor_val"],2);
		$costly_vendor_val = number_format($myrowsel1["costly_vendor_val"],2);
		
		$costly_vendor = $myrowsel1["costly_vendor"];
		$tree_saved = number_format($myrowsel1["tree_saved"],0);
		
		$best_finance_impact = getnickname_warehouse("", $myrowsel1["best_finance_impact"]);
		$best_finance_impact_val = $myrowsel1["best_finance_impact_val"]; 
		$best_landfil_diversion = getnickname_warehouse("",$myrowsel1["best_landfil_diversion"]);
		$best_landfil_diversion_val = $myrowsel1["best_landfil_diversion_val"];
		$lowest_finance_impact_val = $myrowsel1["lowest_finance_impact_val"];
		$lowest_landfil_diversion_val = $myrowsel1["lowest_landfil_diversion_val"];
		$lowest_finance_impact = getnickname_warehouse("", $myrowsel1["lowest_finance_impact"]);
		$lowest_landfil_diversion = getnickname_warehouse("", $myrowsel1["lowest_landfil_diversion"]);		
	}
	
	if ($high_pay_vendor == 0 && $high_pay_vendor_val != 0){
		$high_pay_vendor_img = "ucb-logo.png"; $high_pay_vendor_nm = "Used Cardboard Boxes Inc";
	}else{
		$high_pay_vendor_img = ""; $high_pay_vendor_nm = "";
		$sql1 = "SELECT * FROM water_vendors WHERE id = ?";
		$result1 = db_query($sql1, array("i"), array($high_pay_vendor));
		while ($myrowsel1 = array_shift($result1)) {
			if ($myrowsel1["logo_image"] != ""){
				$high_pay_vendor_img = $myrowsel1["logo_image"];
			}else{
				$high_pay_vendor_img = "blank-logo.jpg";
			}			
			$high_pay_vendor_nm = $myrowsel1["Name"];
		}
	}	

	if ($costly_vendor == 0 && $costly_vendor_val != 0){
		$costly_vendor_img = "ucb-logo.png"; $costly_vendor_nm = "Used Cardboard Boxes Inc";
	}else{	
		$costly_vendor_img = ""; $costly_vendor_nm = "";
		$sql1 = "SELECT * FROM water_vendors WHERE id = ?";
		$result1 = db_query($sql1, array("i"), array($costly_vendor));
		while ($myrowsel1 = array_shift($result1)) {
			if ($myrowsel1["logo_image"] != ""){
				$costly_vendor_img = $myrowsel1["logo_image"];
			}else{
				$costly_vendor_img = "blank-logo.jpg";			
			}			
			$costly_vendor_nm = $myrowsel1["Name"];
		}
	}	
	$_SESSION['pgname'] = "home";
?>  
<? 	require ("mainfunctions/top-header.php");	?>
<div class="year-container">
	<div class="sub-year-container">
	<form name="frmselyear" id="frmselyear" action="dashboard.php" method="post">
		<h4>Viewing:  
			<select id="inv_rep_yr" name="inv_rep_yr" class="form-select1">
				<?
				for ($i=Date("Y")-2, $n=Date("Y")+1; $i<$n; $i++) 
				{
					?>
					<option value="<?=$i?>" <? if ($selected_yr == $i) { echo " selected "; }?>><?=$i?></option>	
					<?
				}
				?>
			</select>
			&nbsp;<input type="submit" class="logout-button" value="Submit" name="btnrepyear" id="btnrepyear">
		</h4>
	</form>
	</div>
</div>
<div class="slider-container">
		<div class="banner-image">
        <div class="counter-container">
        <ul>
        <li class="financial-circle">
		<? if ($total_cost > 0) {?>
			<div class="counter-number-text numbergreen">+<? echo number_format($total_cost,2);?></div>
		<? } elseif ($total_cost == 0) {?>
			<div class="counter-number-text number"><? echo number_format($total_cost,2);?></div>
		<? } else {?>
			<div class="counter-number-text numberred"><? echo number_format($total_cost,2);?></div>
		<? }?>
			<div class="counter-title-text tooltip">Net Financial Impact (YTD)* 
				<span class="tooltiptext"><img src="images/financial-icon.jpg" alt=""/>
					Waste Financial Impact refers to the Year-To-Date net financial impacted created from your waste stream. <br>
					Waste Financial Impact (YTD) = (Net financial revenue YTD – Additional Costs YTD) <br>– (Net financial costs YTD + Additional Fees).
				</span>
			</div>
        </li>
        
        <li class="landfill-circle">
        <div id="divlandfilldiversion" class="counter-number-text per"></div>
		<div class="counter-title-text tooltip">Current Landfill Diversion (YTD)*
			<span class="tooltiptext"><img src="images/landfill-icon.jpg" alt=""/>"UCB's definition of landfill diversion is 
including the  following diversion methods:<br>
				<span class="reuse">Reuse</span><br>
				<span class="recycling">Recycling (Including Composting)</span><br>
				<span class="waste">Waste to Energy</span>
</span>
			</div>
        </li>
        
        <li class="trees-circle">
        <div id="divtreesaved" class="counter-number-text number1"><? echo $tree_saved;?></div>
		<div class="counter-title-text tooltip">Estimated Trees Saved (YTD)
			<span class="tooltiptext"><img src="images/trees-saved-icon.jpg" alt=""/>
				This estimate is based on the amount of reused and recycled products that come from trees. <br>
				Source for the calculation can be found at: <a href="https://archive.epa.gov/epawaste/conserve/smm/wastewise/web/html/factoid.html" target="_blank">Link</a>
			</span>
			</div>
        </li>
        </ul>
        </div>
			<!--<div class="counter-container">
				<div class="counter-1">
					<div class="circle-div-main circle-div-financial">
						<div class="counter-number-text number">+ 4,580</div>
						<div class="counter-title-text">Financial improvement*</div>
					</div>
				</div>
				<div class="counter-1">
					<div class="circle-div-main circle-div-current-landfill">
						<div class="counter-number-text per">78%</div>
						<div class="counter-title-text">CURRENT LANDFILL DIVERSION*</div>
					</div>
				</div>
				<div class="counter-1">
					<div class="circle-div-main circle-div-trees-saved">
						<div class="counter-number-text number1">143</div>
						<div class="counter-title-text">trees saved</div>
					</div>
				</div>
			</div>-->
		</div>
	</div>
                                 
<div class="mid-container">
	<div class="mid-container1">
		
	<div class="container1">
		<h1>Landfill Diversion (YTD)</h1>
		<iframe src="water-ytd-pie-chart-dash.php?selected_yr=<? echo $selected_yr;?>&warehouse_id=<? echo $warehouse_id_cronjobs;?>&start_date=<? echo date($selected_yr . "-01-01"); ?>&end_date=<? echo date($selected_yr . "-m-d"); ?>" frameborder="0" width="100%" height="420px"> 
		</iframe>	
	</div>

<?
//For Summary of Waste Processing(YTD)
		$res1 = db_query("Select * from water_cron_summary_rep where warehouse_id = " . $warehouse_id_cronjobs . " and data_year = $selected_yr");
		while($row_mtd1 = array_shift($res1))
		{
			$outlet_tot[] = array('outlet' => $row_mtd1["outlet"], 'tot' => $row_mtd1["weight_tot"], 'perc' => $row_mtd1["perc_val"] . "%", 'totval' => $row_mtd1["amount_tot"]);
			
			$$sumtot = $row_mtd1["sumtot_weight"];
			$totalval_tot = $row_mtd1["sumtot_amount"];
			$othar_charges = $row_mtd1["other_charges"];
    	}	

				
		 $weight_str = "(Ib)";
		 if ($_REQUEST["repsummarytype"] == "yes"){
			$weight_str = "(Tons)";
		 }
?>		
	<div class="container2"><h1>Summary of Waste Processing (YTD)</h1>
		<div id="no-more-tables">
		<table>
			<tr>
				<th width="31%" valign="top">SUMMARY Per Process</th>
				<th width="23%" valign="top">Total Weight <? echo $weight_str;?></th>
				<th width="20%" valign="top">% Waste Stream</th>
				 <th width="26%" valign="top">Total Amount ($)</th>
			  </tr>
			  
				<?
				$divlandfilldiversion = ""; $total_weight = 0;
				foreach ($outlet_tot as $outlet_tottmp) {
					$bg_color = "";
					if ($outlet_tottmp['outlet'] == "Landfill")
					{
						$bg_color = "#df0000";
					}
					if ($outlet_tottmp['outlet'] == "Incineration (No Energy Recovery)")
					{
						$bg_color = "#cc6511";
					}
					if ($outlet_tottmp['outlet'] == "Waste To Energy")
					{
						$bg_color = "#ffb813";
					}
					if ($outlet_tottmp['outlet'] == "Recycling")
					{
						$bg_color = "#00b0f0";
					}
					if ($outlet_tottmp['outlet'] == "Reuse")
					{
						$bg_color = "#1cc700";
					}
					
					if ($outlet_tottmp['outlet'] == "Waste To Energy" || $outlet_tottmp['outlet'] == "Reuse" || $outlet_tottmp['outlet'] == "Recycling")
					{
						$divlandfilldiversion = $divlandfilldiversion + floatval($outlet_tottmp['perc']);
					}
					if ($_REQUEST["repsummarytype"] == "yes"){
						$total_weight = $total_weight + $outlet_tottmp['tot']/2000;
					}else{
						$total_weight = $total_weight + $outlet_tottmp['tot'];
					}					
				?>
					<tr>
						<? if ($outlet_tottmp['outlet'] == "Incineration (No Energy Recovery)"){ ?>
							<td class='td-leftalign' style='color:<? echo $bg_color;?>;'>Incineration</td>
						<? } else{ ?>
							<td class='td-leftalign' style='color:<? echo $bg_color;?>;'><? echo $outlet_tottmp['outlet'];?></td>
						<? } ?>						
						<? if ($_REQUEST["repsummarytype"] == "yes"){ ?>
							<td><? echo number_format($outlet_tottmp['tot']/2000,2);?></td>
							<td><? echo $outlet_tottmp['perc'];?></td>
						<? } else{ ?>
							<td><? echo number_format($outlet_tottmp['tot'],0);?></td>
							<td><? echo $outlet_tottmp['perc'];?></td>
						<? } ?>
					
						<? if ($outlet_tottmp['totval'] < 0){ ?>														
							<td class='red1' >$<? echo number_format($outlet_tottmp['totval'],2);?></td>
						<? } else {?>
							<td>$<? echo number_format($outlet_tottmp['totval'],2);?></td>
						<? } ?>
					</tr>
				<?
				}
				
				if ($divlandfilldiversion > 100){
					$divlandfilldiversion = 100;
				}
				$divlandfilldiversion = round($divlandfilldiversion, 2);
				?>								
				<tr>
					<td colspan="3" class='td-footer'>TOTAL WEIGHT <? echo $weight_str;?></td>
					<? if ($_REQUEST["repsummarytype"] == "yes"){ ?>
						<? if ($total_weight < 0){ ?>														
							<td class='td-footer1 red1' ><? echo number_format($total_weight,2);?></td>
						<? } else {?>
							<td  class='td-footer1'><? echo number_format($total_weight,2);?></td>
						<? } ?>
					<? } else {?>
						<? if ($total_weight < 0){ ?>														
							<td class='td-footer1 red1' ><? echo number_format($total_weight,0);?></td>
						<? } else {?>
							<td  class='td-footer1'><? echo number_format($total_weight,0);?></td>
						<? } ?>
					<? } ?>
				</tr>
				<tr>
					<td colspan="3" class='td-footer'>TOTAL AMOUNT ($)</td>
					<? if ($totalval_tot < 0){ ?>														
						<td class='td-footer1 red1'>$<? echo number_format($totalval_tot,2);?></td>
					<? } else {?>
						<td class='td-footer1'>$<? echo number_format($totalval_tot,2);?></td>
					<? } ?>
				</tr>
				<tr>
					<td colspan="3" class="td-footer">TOTAL ADDITIONAL FEES</td>
					<td class="td-footer1 red1">$<? echo number_format($othar_charges,2);?></td>
				</tr>
				<tr>
					<td colspan="3" class="td-footer">GRAND TOTAL </td>
					<? if (($totalval_tot+$othar_charges) < 0){ ?>														
						<td class="td-footer1 red1" >$<? echo number_format($totalval_tot+$othar_charges,2); ?></td>
					<? } else {?>
						<td class="td-footer1" >$<? echo number_format($totalval_tot+$othar_charges,2); ?></td>
					<? } ?>
				</tr>
		  </table>
      </div>
	  
	  <form name="frmrepsummary" id="frmrepsummary" action="dashboard.php" method="post">
		  <input type="hidden" value="<? echo $_REQUEST["inv_rep_yr"];?>" name="inv_rep_yr" id="inv_rep_yr" />		
		  <? if ($_REQUEST["repsummarytype"] == "yes"){ ?>
			  <div class="column-white"><input type="submit" class="logout-button1" value="Display Summary in Pounds" name="btnsummaryton" id="btnsummaryton" onclick="repsummaryinlbs()"></div>	  
			  <div class="column-white"><input type="button" class="logout-button1" value="See Full Detailed Report" onclick="showdetailrep(<? echo $warehouse_id; ?>, <? echo $companyid; ?>, '<? echo date("01/01" . $selected_yr);?>', '<? echo date("12/31/" . $selected_yr); ?>')"></div>

			  <input type="hidden" value="" name="repsummarytype" id="repsummarytype" />		
		  <? }else{ ?>  
			  <div class="column-white"><input type="submit" class="logout-button1" value="Display Summary in Tons" name="btnsummaryton" id="btnsummaryton" onclick="repsummaryinton()"></div>	  
			  <div class="column-white"><input type="button" class="logout-button1" value="See Full Detailed Report" onclick="showdetailrep(<? echo $warehouse_id; ?>, <? echo $companyid; ?>, '<? echo date("01/01/" . $selected_yr);?>', '<? echo date("12/31/" . $selected_yr); ?>')"></div>

			  <input type="hidden" value="" name="repsummarytype" id="repsummarytype" />		
		  <? } ?>  
	  </form>
		
	  </div>
		
		<script language="JavaScript">
			var landfilldiversion = <?php echo json_encode($divlandfilldiversion); ?>;
			document.getElementById("divlandfilldiversion").innerHTML = landfilldiversion + "%";
		</script>
    <div class="main_last_col">
	<? if ($parent_comp_flg == 1) { ?>		
		<div class="container3">
			<h1>Best Performing Locations(YTD)</h1>
			<div class="container4">
					<? 
					//&& $best_finance_impact_val > 0
					if ($best_finance_impact != "" ) { ?>
						<br>
						<div class="performing-location">
						<? echo "<span style='color:#1cc700;'>Best Financial Impact: " . $best_finance_impact; ?> :<br> $<? echo number_format($best_finance_impact_val, 2) . "</span>"; ?>
						</div><br><br><br>
					<? 
					}else{
						echo "<div class='performing-location'><span style='color:#1cc700;'>Best Financial Impact:</span></div><br><br><br>";
					}
					?>
					<? if ($best_landfil_diversion != "" && $best_landfil_diversion_val > 0) { ?>
						<div class="performing-location">
						<? echo "<span style='color:#1cc700;'>Best Landfill Diversion: " . $best_landfil_diversion; ?>:<br> <? echo $best_landfil_diversion_val . "%</span>";?>
						</div><br>
					<? }
                else{
                    echo "<div class='performing-location'><span style='color:#1cc700;'>Best Landfill Diversion:</span></div>";
                }
                ?>
				</div>
			</div>
		
		
		<div class="container5"><h1>Worst Performing Locations(YTD)</h1>
			<div class="costly-vendor">
					<? 
					//$lowest_finance_impact_val > 0
					if ($lowest_finance_impact != "" ) { ?>
						<br>
						<div class="performing-location">
						<? echo "<span style='color:#df0000;'>Lowest Financial Impact: " . $lowest_finance_impact; ?>:<br> $<? echo number_format($lowest_finance_impact_val,2) . "</span>"; ?>
						</div><br><br><br>
					<? }
                else{
                    echo "<div class='performing-location'><span style='color:#df0000;'>Lowest Financial Impact:</span></div><br><br><br>";
                }
                ?>
					<? if ($lowest_landfil_diversion != "" && $lowest_landfil_diversion_val > 0) { ?>
						<div class="performing-location bottom_m">
						<? echo "<span style='color:#df0000;'>Lower Landfill Diversion: " . $lowest_landfil_diversion; ?>:<br> <? echo $lowest_landfil_diversion_val . "%</span>"; ?>
						</div>
					<? } 
                else{
                    echo "<div class='performing-location bottom_m'><span style='color:#df0000;'>Lower Landfill Diversion:</span></div>";
                }
                ?>
			</div>
		</div>

	<? } else { ?>		
		<div class="container3">
			<h1>Highest Paying Vendor (YTD)</h1>
			<div class="container4">
				<div class="costly-vendor-img">
					<? if ($high_pay_vendor_nm != "" && $high_pay_vendor_val > 0) { 
						echo $high_pay_vendor_nm; ?><br>
                    <div class="img_vendor">
						<img src="https://loops.usedcardboardboxes.com/vendor_logo_images/<? echo $high_pay_vendor_img; ?>" width="100" height="100"  alt=""/>
                    </div>
						<div class="Highestpayfig">$<? echo $high_pay_vendor_val; ?></div>
					<? } ?>
				</div>
			</div>
		</div>
		
		<div class="container5"><h1>Most Costly Vendor (YTD)</h1>
			<div class="costly-vendor">
				<div class="costly-vendor-img">
					<? if ($costly_vendor_nm != "") { 
						echo $costly_vendor_nm; ?><br>
                    <div class="img_vendor">
						<img src="https://loops.usedcardboardboxes.com/vendor_logo_images/<? echo $costly_vendor_img; ?>" width="100" height="100" alt=""/>
                    </div>
						<div class="Costlyvendorfig">$<? echo $costly_vendor_val; ?></div>		
					<? } ?>
				</div>
			</div>
		</div>
	<? } ?>	
        </div>
	</div>
</div>	
<div class="footer">
	<div class="footer-logo"><img src="images/logo5.png" alt=""/></div>
	<div class="footer-text">© 2020 Used Cardboard Boxes, Inc.</div>
</div>

	
  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <!-- Contact Form JavaScript File -->

  <!-- Template Main Javascript File -->

 <script src="js/main.js"></script>
<script src="menu/slicknav/jquery.slicknav.min.js"></script>

<script>
    $(function(){
        $('.menu-navigation-icons').slicknav();
				
		//alert($(window).width());  
		//alert($(document).width());		
    });
</script>
</body>
</html>
<?php 

}else{
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>"; exit;
}
?>

