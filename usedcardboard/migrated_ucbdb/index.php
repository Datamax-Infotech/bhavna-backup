<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>DASH - Home</title>
	<meta http-equiv="refresh" content="600">
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
		as="style" onload="this.rel='stylesheet'">
	<link rel="preload" href="https://loops.usedcardboardboxes.com/css/tooltip_style.css" as="style"
		onload="this.rel='stylesheet'">
	<LINK rel="preload" type="text/css" href="one_style.css" as="style" onload="this.rel='stylesheet'">
	<script>
		function eod_popup(warehousetbl) {
			document.getElementById("hd_warehouse").value = warehousetbl;

			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" + document.getElementById("diveod").innerHTML;
			document.getElementById('light').style.display = 'block';

		}
		function FormCheck() {
			if (document.ContactForm.searchcrit.value == "") {
				alert("A Tracking Number must be entered in this field.  Please try again.  Thank you.");
				return false;
			}
		}
	</script>
	<style>
		/*Tooltip style*/
		.tooltip {
			position: relative;
			display: inline-block;
		}

		.tooltip .tooltiptext {
			visibility: hidden;
			width: 250px;
			background-color: #464646;
			color: #fff;
			text-align: left;
			border-radius: 6px;
			padding: 5px 7px;
			position: absolute;
			z-index: 1;
			top: -5px;
			left: 110%;
			/*white-space: nowrap;*/
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		}

		.tooltip .tooltiptext::after {
			content: "";
			position: absolute;
			top: 35%;
			right: 100%;
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent black transparent transparent;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
		}

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}

		.black_overlay {
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none;
			position: absolute;
			top: 5%;
			left: 10%;
			width: 40%;
			height: 40%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}
	</style>
</head>

<body>
	<div id="light" class="white_content"></div>
	<div id="fade" class="black_overlay"></div>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<p align="center">&nbsp;&nbsp;<strong>
				<font face="Arial" Size="3">UsedCardboardBoxes.com Company Dashboard</font>
			</strong></p><br><br>
		<table border="0" cellpadding="5" cellspacing="2" width="80%" align="center">
			<tr>
				<td valign="top">
					<table cellSpacing="1" cellPadding="1" width="200" border="0">
						<tr align="middle">
							<td bgColor="#ffcccc" colSpan="2" class="style9">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">NPS
									<div class="tooltip">
										<i class="fa fa-info-circle" aria-hidden="true"></i>
										<span class="tooltiptext">
											NPS stands for Net Promoter Score which is a metric<br>
											used in customer experience programs, measuring <br>
											customer loyalty with a single question survey <br>
											and reported with a number from -100 to +100, <br>
											a higher score is desirable.
										</span>
									</div>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" class="style1" style="width: 6%">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last 7 Days
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">

								<?php
								// Last Week
								$promoter = 0;
								$detractor = 0;
								$neutral = 0;
								$lastweekstdate = date("Ymd", strtotime("-7 days"));
								$lastweekendate = date("Ymd");
								$end_date = date('Ymd', strtotime("last sunday") + 86400);
								$query = "SELECT nps FROM survey_nps WHERE date>='" . $lastweekstdate . "' AND date<='" . $lastweekendate . "'";
								$res = db_query($query, db());
								while ($row = array_shift($res)) {

									if ($row["nps"] >= 0 && $row["nps"] < 7) {
										$detractor += 1;
									}
									if ($row["nps"] >= 6 && $row["nps"] < 9) {
										$neutral += 1;
									}
									if ($row["nps"] >= 9) {
										$promoter += 1;
									}
								}
								?>
								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastweekstdate; ?>&end_date=<?php echo $lastweekendate; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter - $detractor) / ($detractor + $neutral + $promoter), 0);
									?>
								</a>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last 30 Days</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">

								<?php
								// Last Month
								$lastmonthstdate = date("Ymd", strtotime("-30 days"));
								$lastmonthendate = date("Ymd");
								$end_date = date('Ymd', strtotime("last day of previous month") + 86400);
								$query2 = "SELECT nps FROM survey_nps WHERE date>='" . $lastmonthstdate . "' AND date<='" . $lastmonthendate . "'";
								//echo $query2;
								
								$promoter2;
								$avg;
								$neutral2;
								$detractor2;
								$res2 = db_query($query2, db());
								while ($row2 = array_shift($res2)) {

									if ($row2["nps"] >= 0 && $row2["nps"] < 7) {
										$detractor2 += 1;
									}
									if ($row2["nps"] >= 6 && $row2["nps"] < 9) {
										$neutral2 += 1;
									}
									if ($row2["nps"] >= 9) {
										$promoter2 += 1;
									}
									$avg += $row2["nps"];
								}

								?>
								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastmonthstdate; ?>&end_date=<?php echo $lastmonthendate; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter2 - $detractor2) / ($detractor2 + $neutral2 + $promoter2), 0);
									?>
								</a>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last 91 Days
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<?php
								// Last quarter
								$current_month = date('m');
								$current_year = date('Y');

								if ($current_month >= 1 && $current_month <= 3) {
									$st_lastqtr = date('Y-m-d', strtotime('1-October-' . ($current_year - 1)));
									$end_lastqtr = date('Y-m-d', strtotime('31-December-' . ($current_year - 1)));
								} else if ($current_month >= 4 && $current_month <= 6) {
									$st_lastqtr = date('Y-m-d', strtotime('1-January-' . $current_year));
									$end_lastqtr = date('Y-m-d', strtotime('31-March-' . $current_year));
								} else if ($current_month >= 7 && $current_month <= 9) {
									$st_lastqtr = date('Y-m-d', strtotime('1-April-' . $current_year));
									$end_lastqtr = date('Y-m-d', strtotime('30-June-' . $current_year));
								} else if ($current_month >= 10 && $current_month <= 12) {
									$st_lastqtr = date('Y-m-d', strtotime('1-July-' . $current_year));
									$end_lastqtr = date('Y-m-d', strtotime('30-September-' . $current_year));
								}

								$quarterstdate = $st_lastqtr;
								$quarterendate = $end_lastqtr;

								$quarterstdate = date("Ymd", strtotime("-91 days"));
								$quarterendate = date("Ymd");

								$query3 = "SELECT * FROM survey_nps WHERE date>='" . $quarterstdate . "' AND date<='" . $quarterendate . "'";
								//echo $query3;
								
								$res3 = db_query($query3, db());
								while ($row3 = array_shift($res3)) {

									if ($row3["nps"] >= 0 && $row3["nps"] < 7) {
										$detractor3 += 1;
									}
									if ($row3["nps"] >= 6 && $row3["nps"] < 9) {
										$neutral3 += 1;
									}
									if ($row3["nps"] >= 9) {
										$promoter3 += 1;
									}
									$avg3 += $row3["nps"];
								}


								?>
								<a
									href="report_survey.php?action=run&start_date=<?php echo $quarterstdate; ?>&end_date=<?php echo $quarterendate; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter3 - $detractor3) / ($detractor3 + $neutral3 + $promoter3), 0);
									?>
								</a>

							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Year to Date
									<?php echo date("Y"); ?>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">

								<?php
								// Year to Date
								$yearstdate = date("Y0101");
								$yearendate = date("Ymd");
								$query4 = "SELECT * FROM survey_nps WHERE date>='" . $yearstdate . "' AND date<='" . $yearendate . "'";
								//echo $query4;
								
								$res4 = db_query($query4, db());

								while ($row4 = array_shift($res4)) {

									if ($row4["nps"] >= 0 && $row4["nps"] < 7) {
										$detractor4 += 1;
									}
									if ($row4["nps"] >= 6 && $row4["nps"] < 9) {
										$neutral4 += 1;
									}
									if ($row4["nps"] >= 9) {
										$promoter4 += 1;
									}
									$avg4 += $row4["nps"];
								}
								// Last quarter
								
								?>

								<a
									href="report_survey.php?action=run&start_date=<?php echo $yearstdate; ?>&end_date=<?php echo $yearendate; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter4 - $detractor4) / ($detractor4 + $neutral4 + $promoter4), 0);
									?>
								</a>

							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Year
									<?php echo date("Y", strtotime("-1 year")) ?>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<?php
								// Year 2020
								$lastyearstdate = date("Y0101", strtotime("-1 year"));
								$lastyearendate = date("Y1231", strtotime("-1 year"));
								$query5 = "SELECT * FROM survey_nps WHERE date>='" . $lastyearstdate . "' AND date<='" . $lastyearendate . "'";
								//echo $query5;
								
								$res5 = db_query($query5, db());
								while ($row5 = array_shift($res5)) {

									if ($row5["nps"] >= 0 && $row5["nps"] < 7) {
										$detractor5 += 1;
									}
									if ($row5["nps"] >= 6 && $row5["nps"] < 9) {
										$neutral5 += 1;
									}
									if ($row5["nps"] >= 9) {
										$promoter5 += 1;
									}

									$avg += $row5["nps"];
								}
								?>
								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate; ?>&end_date=<?php echo $lastyearendate; ?>&surveyview=1">
									<?php

									echo number_format(100 * ($promoter5 - $detractor5) / ($detractor5 + $neutral5 + $promoter5), 0);
									?>
								</a>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Year
									<?php echo date("Y", strtotime("-2 year")) ?>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<?php
								// Year 2019
								$lastyearstdate2 = date("Y0101", strtotime("-2 year"));
								$lastyearendate2 = date("Y1231", strtotime("-2 year"));
								$query6 = "SELECT * FROM survey_nps WHERE date>='" . $lastyearstdate2 . "' AND date<='" . $lastyearendate2 . "'";
								//echo $query6;
								
								$res6 = db_query($query6, db());

								while ($row6 = array_shift($res6)) {

									if ($row6["nps"] >= 0 && $row6["nps"] < 7) {
										$detractor6 += 1;
									}

									if ($row6["nps"] >= 6 && $row6["nps"] < 9) {
										$neutral6 += 1;
									}

									if ($row6["nps"] >= 9) {
										$promoter6 += 1;
									}

									$avg += $row6["nps"];
								}
								?>

								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate2; ?>&end_date=<?php echo $lastyearendate2; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter6 - $detractor6) / ($detractor6 + $neutral6 + $promoter6), 0);
									?>
								</a>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Year
									<?php echo date("Y", strtotime("-3 year")) ?>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<?php
								// Year 2018
								$lastyearstdate3 = date("Y0101", strtotime("-3 year"));
								$lastyearendate3 = date("Y1231", strtotime("-3 year"));
								$query7 = "SELECT * FROM survey_nps WHERE date>='" . $lastyearstdate3 . "' AND date<='" . $lastyearendate3 . "'";
								//echo $query7;
								
								$res7 = db_query($query7, db());

								while ($row7 = array_shift($res7)) {

									if ($row7["nps"] >= 0 && $row7["nps"] < 7) {
										$detractor7 += 1;
									}

									if ($row7["nps"] >= 6 && $row7["nps"] < 9) {
										$neutral7 += 1;
									}

									if ($row7["nps"] >= 9) {
										$promoter7 += 1;
									}

									$avg += $row7["nps"];
								}
								?>

								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate3; ?>&end_date=<?php echo $lastyearendate3; ?>&surveyview=1">
									<?php

									echo number_format(100 * ($promoter7 - $detractor7) / ($detractor7 + $neutral7 + $promoter7), 0);
									?>
								</a>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Year
									<?php echo date("Y", strtotime("-4 year")) ?>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<?php
								// Year 2017
								$lastyearstdate4 = date("Y0101", strtotime("-4 year"));
								$lastyearendate4 = date("Y1231", strtotime("-4 year"));
								$query8 = "SELECT * FROM survey_nps WHERE date>='" . $lastyearstdate4 . "' AND date<='" . $lastyearendate4 . "'";
								//echo $query8;
								
								$res8 = db_query($query8, db());
								$neutral8 = 0;
								while ($row8 = array_shift($res8)) {

									if ($row8["nps"] >= 0 && $row8["nps"] < 7) {
										$detractor8 += 1;
									}

									if ($row8["nps"] >= 6 && $row8["nps"] < 9) {
										$neutral8 += 1;
									}

									if ($row8["nps"] >= 9) {
										$promoter8 += 1;
									}

									$avg += $row8["nps"];
								}
								?>
								<a
									href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate4; ?>&end_date=<?php echo $lastyearendate4; ?>&surveyview=1">
									<?php
									echo number_format(100 * ($promoter8 - $detractor8) / ($detractor8 + $neutral8 + $promoter8), 0);
									?>
								</a>
							</td>
						</tr>
					</table>
					<br>
					<!--  --------------------------------- end NPS ------------------------------------------>
					<?php
					/*
							 //YTD 2 years ago

							 $start_date_ytdl2 = strtotime(date('01/01/'.(date("Y")-2)));
							 $end_date_ytdl2 = strtotime(date("M d ".(date("Y")-2))) ;
							 $end_date_ytdl2 = ($end_date_ytdl2 + 86400);
							 $query_ytdl2 = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
							 $query_ytdl2.= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl2 AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl2)";
							 $resl2 = db_query($query_ytdl2, db());
							 while($row_ytdl2 = array_shift($resl2))
							 {
							 $total_revenue_ytdl2+=$row_ytdl2["order_total"];
							 }

							 $query_ytdl2 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl2 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl2)";
							 $resl2 = db_query($query_ytdl2, db());
							 while($row_ytdl2 = array_shift($resl2))
							 {
							 $total_revenue_ytdl2 = $total_revenue_ytdl2 + $row_ytdl2["T"];
							 }

							 //YTD 3 years ago

							 $start_date_ytdl3 = strtotime(date('01/01/'.(date("Y")-3)));
							 $end_date_ytdl3 = strtotime(date("M d ".(date("Y")-3))) ;
							 $end_date_ytdl3 = ($end_date_ytdl3 + 86400);
							 $query_ytdl3 = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
							 $query_ytdl3.= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl3 AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl3)";
							 $resl3 = db_query($query_ytdl3, db());
							 while($row_ytdl3 = array_shift($resl3))
							 {
							 $total_revenue_ytdl3+=$row_ytdl3["order_total"];
							 }

							 $query_ytdl3 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND(UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl3 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl3)";
							 $resl3 = db_query($query_ytdl3, db());
							 while($row_ytdl3 = array_shift($resl3))
							 {
								 $total_revenue_ytdl3 = $total_revenue_ytdl3 + $row_ytdl3["T"];
							 }

							 //YTD 4 years ago

							 $start_date_ytdl4 = strtotime(date('01/01/'.(date("Y")-4)));
							 $end_date_ytdl4 = strtotime(date("M d ".(date("Y")-4))) ;
							 $end_date_ytdl4 = ($end_date_ytdl4 + 86400);
							 $query_ytdl4 = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
							 $query_ytdl4.= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl4 AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl4)";
							 $resl4 = db_query($query_ytdl4, db());
							 while($row_ytdl4 = array_shift($resl4))
							 {
							 $total_revenue_ytdl4+=$row_ytdl4["order_total"];
							 }

							 $query_ytdl4 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl4 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl4)";
							 $resl4 = db_query($query_ytdl4, db());
							 while($row_ytdl4 = array_shift($resl4))
							 {
								 $total_revenue_ytdl4 = $total_revenue_ytdl4 + $row_ytdl4["T"];
							 }
							 */
					//YTD 5 years ago
					
					$end_date_mtd = strtotime(date("M d Y"));
					$end_date_mtd = $end_date_mtd + 86400;
					$start_date_mtd = strtotime(date(date("m") . "/01/" . date("Y")));

					$query_mtd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_mtd .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtd)";
					$res = db_query($query_mtd, db());
					while ($row_mtd = array_shift($res)) {
						$total_revenue_mtd += $row_mtd["order_total"];
					}

					/*$query_mtd = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND(UNIX_TIMESTAMP(entry_date)>=$start_date_mtd AND UNIX_TIMESTAMP(entry_date)<=$end_date_mtd)";
							 $res = db_query($query_mtd, db());
							 while($row_mtd = array_shift($res))
							 {
								 $total_revenue_mtd = $total_revenue_mtd - $row_mtd["T"];
							 }*/
					//echo "total_revenue_mtd=" . $total_revenue_mtd . "<br>";
					$query_mtd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_mtd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtd)";
					$res = db_query($query_mtd, db());
					while ($row_mtd = array_shift($res)) {
						$total_revenue_mtd -= $row_mtd["order_total"];
					}
					//echo "total_revenue_mtd 2nd =" . $total_revenue_mtd . "<br>";
					
					$quota_ov_tod_mtd = 0;
					$begin = new DateTime(date(date("m") . "/01/" . date("Y")));
					$end = new DateTime(date("M t Y"));
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_mtd = $quota_ov_tod_mtd + $quota_one_day;
						}
					}

					$st_date = Date('Y-m-01');
					$end_date = Date('Y-m-t');

					$st_lastmonth = date("Y-n-j", strtotime("first day of previous month"));
					$end_lastmonth = date("Y-n-j 23:59:59", strtotime("last day of previous month"));

					$end_date_mtdl = strtotime($end_lastmonth);
					$start_date_mtdl = strtotime($st_lastmonth);

					$total_revenue_mtdl = 0;

					$query_mtdl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_mtdl .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtdl)";
					$resl = db_query($query_mtdl, db());
					while ($row_mtdl = array_shift($resl)) {
						$total_revenue_mtdl += $row_mtdl["order_total"];
					}

					/*$query_mtdl = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND(UNIX_TIMESTAMP(entry_date)>=$start_date_mtdl AND UNIX_TIMESTAMP(entry_date)<=$end_date_mtdl)";
							 $res = db_query($query_mtdl, db());
							 while($row_mtdl = array_shift($res))
							 {
								 $total_revenue_mtdl = $total_revenue_mtdl - $row_mtdl["T"];
							 }*/

					$query_mtd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_mtd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtdl)";
					$res = db_query($query_mtd, db());
					while ($row_mtd = array_shift($res)) {
						$total_revenue_mtdl = $total_revenue_mtdl - $row_mtd["order_total"];
					}

					$quota_ov_tod_mtd1 = 0;
					$begin = new DateTime($st_lastmonth);
					$end = new DateTime(date("m/d/Y", $end_date_mtdl));
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_mtd1 = $quota_ov_tod_mtd1 + $quota_one_day;
						}
					}

					$start_date_tod = date('Y-m-d', strtotime("now"));
					$end_date_tod = date('Y-m-d', strtotime("now"));
					$total_revenue_tod = 0;
					$sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59')";
					$resultmtd = db_query($sqlmtd);
					while ($summtd = array_shift($resultmtd)) {
						$total_revenue_tod = $summtd["revenue"];
					}

					/*$sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59'";
							 $resultmtd = db_query($sqlmtd );
							 while ($summtd = array_shift($resultmtd)) {
								 $total_revenue_tod = $total_revenue_tod - $summtd["revenue"];
							 }*/

					$sqlmtd = "SELECT value AS revenue FROM orders_total where class = 'ot_tax' and orders_id in (Select orders_id from orders WHERE customers_name <> '' 
			and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59')";
					$resultmtd = db_query($sqlmtd);
					while ($summtd = array_shift($resultmtd)) {
						$total_revenue_tod -= $summtd["revenue"];
					}

					$quota_ov_tod = 0;
					$begin = new DateTime($start_date_tod);
					$end = new DateTime($end_date_tod);
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod = $quota_ov_tod + $quota_one_day;
						}
					}

					$start_date_tod = date('Y-m-d', strtotime("-1 day"));
					$end_date_tod = date('Y-m-d', strtotime("-1 day"));
					$total_revenue_yes = 0;
					$sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59')";
					$resultmtd = db_query($sqlmtd);
					while ($summtd = array_shift($resultmtd)) {
						$total_revenue_yes = $summtd["revenue"];
					}

					/*$sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59'";
							 $resultmtd = db_query($sqlmtd );
							 while ($summtd = array_shift($resultmtd)) {
								 $total_revenue_yes = $total_revenue_yes - $summtd["revenue"];
							 }*/

					$sqlmtd = "SELECT value AS revenue FROM orders_total where class = 'ot_tax' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_date_tod)) . "'  AND '" . Date("Y-m-d", strtotime($end_date_tod)) . " 23:59:59')";
					$resultmtd = db_query($sqlmtd);
					while ($summtd = array_shift($resultmtd)) {
						$total_revenue_yes = $total_revenue_yes - $summtd["revenue"];
					}

					$quota_ov_tod_yesterday = 0;
					$begin = new DateTime($start_date_tod);
					$end = new DateTime($end_date_tod);
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_yesterday = $quota_ov_tod_yesterday + $quota_one_day;
						}
					}

					//QTD and QTD1
					function getCurrentQuarter($timestamp = false)
					{
						if (!$timestamp)
							$timestamp = time();
						$day = date('n', $timestamp);
						$quarter = ceil($day / 3);
						return $quarter;
					}

					function getPreviousQuarter($timestamp = false)
					{
						if (!$timestamp)
							$timestamp = time();
						//$quarter = getCurrentQuarter($timestamp) - 1;
						$quarter = getCurrentQuarter($timestamp);
						if ($quarter < 0) {
							$quarter = 4;
						}
						return $quarter;
					}

					$quarter = getCurrentQuarter();
					$year = date('Y');
					$st_date_n = new DateTime($year . '-' . ($quarter * 3 - 2) . '-1');
					//Get first day of first month of next quarter
					$endMonth = $quarter * 3 + 1;
					if ($endMonth > 12) {
						$endMonth = 1;
						$year++;
					}
					$end_date_n = new DateTime($year . '-' . $endMonth . '-1');

					//Subtract 1 second to get last day of prior month
					$end_date_n->sub(new DateInterval('PT1S'));
					$st_date = $st_date_n->format('Y-m-d');
					$end_date = $end_date_n->format('Y-m-d');
					$end_date_2 = $end_date_n->format('Y-m-t');

					$quarter = getPreviousQuarter();
					$year = date('Y');
					$st_lastqtr_n = new DateTime($year . '-' . ($quarter * 3 - 2) . '-1');
					//Get first day of first month of next quarter
					$endMonth = $quarter * 3 + 1;
					if ($endMonth > 12) {
						$endMonth = 1;
						$year++;
					}
					$end_lastqtr_n = new DateTime($year . '-' . $endMonth . '-1');

					//Subtract 1 second to get last day of prior month
					$end_lastqtr_n->sub(new DateInterval('PT1S'));

					//$st_lastqtr = $st_lastqtr_n->format('Y-m-d');
					//$end_lastqtr = $end_lastqtr_n->format('Y-m-d');
					$current_month = date('m');
					$current_year = date('Y');

					if ($current_month >= 1 && $current_month <= 3) {
						$st_lastqtr = date('Y-m-d', strtotime('1-October-' . ($current_year - 1)));
						$end_lastqtr = date('Y-m-d', strtotime('31-December-' . ($current_year - 1)));
					} else if ($current_month >= 4 && $current_month <= 6) {
						$st_lastqtr = date('Y-m-d', strtotime('1-January-' . $current_year));
						$end_lastqtr = date('Y-m-d', strtotime('31-March-' . $current_year));
					} else if ($current_month >= 7 && $current_month <= 9) {
						$st_lastqtr = date('Y-m-d', strtotime('1-April-' . $current_year));
						$end_lastqtr = date('Y-m-d', strtotime('30-June-' . $current_year));
					} else if ($current_month >= 10 && $current_month <= 12) {
						$st_lastqtr = date('Y-m-d', strtotime('1-July-' . $current_year));
						$end_lastqtr = date('Y-m-d', strtotime('30-September-' . $current_year));
					}

					$st_lastqtr_lastyr = date($last_yr . '-m-01', strtotime($st_date));
					$end_lastqtr_lastyr = date($last_yr . '-m-t', strtotime($end_date));

					$unqid = $unqid + 1;

					$this_qtr_st_dt = $st_date;
					$this_qtr_end_dt = $end_date;

					$total_revenue_qtr = 0;
					$start_date_qtr = strtotime($st_date);
					$end_date_qtr = strtotime($end_date);
					$end_date_qtr = $end_date_qtr + 86400;
					$query_qtr = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_qtr .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtr AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtr)";
					//echo $query_qtr . " = " . $st_date . " " . $end_date . "<br>";
					$res = db_query($query_qtr, db());
					while ($row_qtr = array_shift($res)) {
						$total_revenue_qtr += $row_qtr["order_total"];
					}

					/*$query_qtr = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_qtr AND UNIX_TIMESTAMP(entry_date)<=$end_date_qtr)";
							 $res = db_query($query_qtr, db());
							 while($row_qtr = array_shift($res))
							 {
								 $total_revenue_qtr = $total_revenue_qtr - $row_qtr["T"];
							 }*/

					$query_qtr = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_qtr .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtr AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtr)";
					$res = db_query($query_qtr, db());
					while ($row_qtr = array_shift($res)) {
						$total_revenue_qtr = $total_revenue_qtr - $row_qtr["order_total"];
					}

					$quota_ov_tod_qtr = 0;
					$begin = new DateTime($st_date);
					$end = new DateTime($end_date_2);
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_qtr = $quota_ov_tod_qtr + $quota_one_day;
						}
					}


					$start_date_qtrl = strtotime($st_lastqtr);
					$end_date_qtrl = strtotime($end_lastqtr);
					$end_date_qtrl = ($end_date_qtrl + 86400);
					$query_qtrl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_qtrl .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtrl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtrl)";
					$resl = db_query($query_qtrl, db());
					while ($row_qtrl = array_shift($resl)) {
						$total_revenue_qtrl += $row_qtrl["order_total"];
					}

					/*$query_qtrl = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_qtrl AND UNIX_TIMESTAMP(entry_date)<=$end_date_qtrl)";
							 $resl = db_query($query_qtrl, db());
							 while($row_qtrl = array_shift($resl))
							 {
								 $total_revenue_qtrl = $total_revenue_qtrl - $row_qtrl["T"];
							 }*/

					$query_qtrl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_qtrl .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtrl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtrl)";
					$resl = db_query($query_qtrl, db());
					while ($row_qtrl = array_shift($resl)) {
						$total_revenue_qtrl = $total_revenue_qtrl - $row_qtrl["order_total"];
					}

					$quota_ov_tod_qtr1 = 0;
					$begin = new DateTime($st_lastqtr);
					$end = new DateTime($end_lastqtr);
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_qtr1 = $quota_ov_tod_qtr1 + $quota_one_day;
						}
					}

					//YTD and YTD1
					$start_date_ytd = strtotime(date('01/01/' . date("Y")));
					$end_date_ytd = strtotime(date("M d Y"));
					$end_date_ytd = $end_date_ytd + 86400;
					$query_ytd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_ytd .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytd)";
					$res = db_query($query_ytd, db());
					while ($row_ytd = array_shift($res)) {
						$total_revenue_ytd += $row_ytd["order_total"];
					}

					/*$query_ytd = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytd AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytd)";
							 $res = db_query($query_ytd, db());
							 while($row_ytd = array_shift($res))
							 {
								 $total_revenue_ytd = $total_revenue_ytd - $row_ytd["T"];
							 }*/

					$query_ytd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_ytd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytd)";
					$res = db_query($query_ytd, db());
					while ($row_ytd = array_shift($res)) {
						$total_revenue_ytd = $total_revenue_ytd - $row_ytd["order_total"];
					}

					$quota_ov_tod_ytd = 0;
					$begin = new DateTime(date('01/01/' . date("Y")));
					$end = new DateTime(date("12/31/Y"));
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_ytd = $quota_ov_tod_ytd + $quota_one_day;
						}
					}


					$start_date_ytdl = strtotime(date('01/01/' . (date("Y") - 1)));
					$end_date_ytdl = strtotime(date("12/31/" . (date("Y") - 1)));
					$end_date_ytdl = ($end_date_ytdl + 86400);
					$query_ytdl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_ytdl .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl)";
					$resl = db_query($query_ytdl, db());
					while ($row_ytdl = array_shift($resl)) {
						$total_revenue_ytdl += $row_ytdl["order_total"];
					}

					/*$query_ytdl = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl)";
							 $resl = db_query($query_ytdl, db());
							 while($row_ytdl = array_shift($resl))
							 {
								 $total_revenue_ytdl = $total_revenue_ytdl - $row_ytdl["T"];
							 }*/

					$query_ytdl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
					$query_ytdl .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl)";
					$resl = db_query($query_ytdl, db());
					while ($row_ytdl = array_shift($resl)) {
						$total_revenue_ytdl = $total_revenue_ytdl - $row_ytdl["order_total"];
					}

					$quota_ov_tod_ytd1 = 0;
					$begin = new DateTime(date('01/01/' . (date("Y") - 1)));
					$end = new DateTime(date("12/31/" . (date("Y") - 1)));
					for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
						$start_Dt_tmp = $datecnt->format("Y-m-d");
						$newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
						$result_empq = db_query($newsel, db());
						while ($rowemp_empq = array_shift($result_empq)) {
							$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
							$quota_ov_tod_ytd1 = $quota_ov_tod_ytd1 + $quota_one_day;
						}
					}


					/*
							 echo number_format($total_revenue_ytd, 2);
							 echo "<br>";
							 echo number_format($total_revenue_mtd, 2);
							 echo "<br>";
							 echo number_format($total_revenue_yes, 2);
							 echo "<br>";
							 echo number_format($total_revenue_tod, 2);

							 */
					?>


					<table cellSpacing="1" cellPadding="1" width="200" border="0">
						<tr align="middle">
							<td bgColor="#ffcccc" colSpan="3" class="style9">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">REVENUE
							</td>
						</tr>
						<tr align="middle">
							<td bgColor="#ffcccc" class="style9">
								&nbsp;
							</td>
							<td bgColor="#ffcccc" class="style9">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">QUOTA
							</td>
							<td bgColor="#ffcccc" class="style9">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">REVENUE
							</td>

						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" class="style1" style="width: 6%">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Today
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_tod, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Yesterday</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_yesterday, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_yes, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">This Month
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_mtd, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_mtd, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last Month
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_mtd1, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_mtdl, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">This Quarter
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_qtr, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_qtr, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last Quarter
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_qtr1, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_qtrl, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">This Year
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_ytd, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_ytd, 0); ?>
								</font>
							</td>
						</tr>
						<tr vAlign="center">
							<td bgColor="#d9f2ff" style="width: 6%" class="style1">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">Last Year
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($quota_ov_tod_ytd1, 0); ?>
								</font>
							</td>
							<td bgColor="#e4e4e4" style="width: 6%" class="style1" align="right">
								<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">$
									<?php echo number_format($total_revenue_ytdl, 0); ?>
								</font>
							</td>
						</tr>

					</table>
					<br>
					<br>

					<?php
					$ship_it = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE (OE.status = 'In transit' OR OE.fedex_status NOT LIKE 'DE' AND OE.fedex_status NOT LIKE 'DL' AND OE.fedex_status NOT LIKE 'SE' AND OE.fedex_status NOT LIKE '' AND OE.fedex_status NOT LIKE 'OC') AND OE.setignore != 1 ORDER BY O.date_purchased";

					$ship_d = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = 'Delivered' AND OE.setignore != 1 ORDER BY O.date_purchased";

					$ship_e = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE (OE.status = 'Exception' OR OE.fedex_status = 'DE' OR OE.fedex_status = 'SE') AND OE.setignore != 1 ORDER BY O.date_purchased";

					$ship_p = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = 'Pickup' AND OE.setignore != 1 ORDER BY O.date_purchased";

					$ship_mp = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE (OE.status = 'Manifest Pickup' OR OE.fedex_status = 'OC') AND OE.setignore != 1 ORDER BY O.date_purchased";

					$ship_null = "SELECT OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = '' AND OE.setignore != 1 AND OE.fedex_status LIKE '' ORDER BY O.date_purchased";
					//echo $ship_null . "<br>";
					
					$ship_it_result = db_query($ship_it, db());
					$ship_d_result = db_query($ship_d, db());
					$ship_e_result = db_query($ship_e, db());
					$ship_p_result = db_query($ship_p, db());
					$ship_mp_result = db_query($ship_mp, db());
					$ship_null_result = db_query($ship_null, db());
					$ship_it_result_rows = tep_db_num_rows($ship_it_result);
					$ship_d_result_rows = tep_db_num_rows($ship_d_result);
					$ship_e_result_rows = tep_db_num_rows($ship_e_result);
					$ship_p_result_rows = tep_db_num_rows($ship_p_result);
					$ship_mp_result_rows = tep_db_num_rows($ship_mp_result);
					$ship_null_result_rows = tep_db_num_rows($ship_null_result);


					$tracking_sent_to_shopify_qry = "SELECT orders.orders_id from orders orders left JOIN orders_active_export ON orders.orders_id = orders_active_export.orders_id WHERE orders_active_export.setignore != 1 and `cancel` <> 'Yes' and shopify_order_no <> '' and (tracking_number <> '' or ubox_order_tracking_number<> '') and updated_tracking_shopify_flg = 0 and orders.orders_id > 391425";
					$tracking_sent_to_shopify_rs = db_query($tracking_sent_to_shopify_qry, db());
					$tracking_sent_to_shopify = tep_db_num_rows($tracking_sent_to_shopify_rs);

					/*Getting Ubox tracking data */

					$seldtFedexX = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status = 'DE' OR UOFD.ubox_order_fedex_status = 'SE') AND UOFD.setignore != 1 ORDER BY O.date_purchased", db());
					$seldtFedexM = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status = 'OC') AND UOFD.setignore != 1 ORDER BY O.date_purchased", db());
					$seldtFedexI = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status NOT LIKE 'DE' AND UOFD.ubox_order_fedex_status NOT LIKE 'DL' AND UOFD.ubox_order_fedex_status NOT LIKE 'SE' AND UOFD.ubox_order_fedex_status NOT LIKE '' AND UOFD.ubox_order_fedex_status NOT LIKE 'OC') AND UOFD.setignore != 1 ORDER BY O.date_purchased", db());

					$seldtFedexZ = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE UOFD.ubox_order_fedex_status LIKE '' AND UOFD.setignore != 1 and O.date_purchased < '" . date("Y-m-d") . "' ORDER BY O.date_purchased", db());
					//echo "<br />";
					//echo "SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE UOFD.ubox_order_fedex_status LIKE '' AND UOFD.setignore != 1 and O.date_purchased < '" . date("Y-m-d")  . "' ORDER BY O.date_purchased";
					
					$arrX = array_merge($ship_e_result, $seldtFedexX);
					$arrM = array_merge($ship_mp_result, $seldtFedexM);
					$arrI = array_merge($ship_it_result, $seldtFedexI);
					$arrZ = array_merge($ship_null_result, $seldtFedexZ);
					$cntX = count($arrX);
					$cntM = count($arrM);
					$cntI = count($arrI);
					$cntZ = count($arrZ);
					$cntTN_No_Shopify = count($tracking_sent_to_shopify_rs);

					?>
					<!--  Possible issues-->
					<?php
					$cnt_possible_issue = 0;
					$possible_issue_order_id_list = "";
					$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.status = '' AND OE.setignore != 1 AND OE.fedex_status = '' ORDER BY O.date_purchased";
					$ship_it_result = db_query($ship_it, db());
					while ($report_data = array_shift($ship_it_result)) {
						$dp = $report_data["print_date"];
						$print_date = date("F j Y H:i:s", strtotime($dp));
						$todaysDt = date('Y-m-d H:i:s');

						if (strtotime($dp) < strtotime(date('Y-m-d'))) {
							$cnt_possible_issue = $cnt_possible_issue + 1;
						}

						if (strtotime($dp) < strtotime(date('Y-m-d'))) {
							$possible_issue_order_id_list .= $report_data["id"] . ",";
						}
					}

					//Not Picked Up: M case
					$stat = " (OE.status = 'Manifest Pickup' OR OE.fedex_status LIKE 'OC') ";
					$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
					$ship_it_result = db_query($ship_it, db());
					while ($report_data = array_shift($ship_it_result)) {
						$dp = $report_data["print_date"];
						$print_date = date("F j Y H:i:s", strtotime($dp));
						$todaysDt = date('Y-m-d H:i:s');
						$otStr = '';
						//get the todays day
						//$dp = '2021-03-12 14:40:00';
						//$todaysDt ='2021-03-15 18:00:00';
						$todaysDay = date("D", strtotime($todaysDt));
						if ($todaysDay == 'Sun') {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 2 days'));
						} else if ($todaysDay == 'Mon') {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 3 days'));
						} else {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 1 days'));
						}
						//echo $considerDay;
						$dayGrtrfourDays = date('Y-m-d H:i:s', strtotime($todaysDt . ' +  4 days'));
						$dayOther = date('Y-m-d H:i:s', strtotime($considerDay . ' - 1 days'));

						if (strtotime($dp) < strtotime($considerDay)) {
							$cnt_possible_issue = $cnt_possible_issue + 1;
						}

						if (strtotime($dp) < strtotime($considerDay)) {
							$possible_issue_order_id_list .= $report_data["id"] . ",";
						}
					}

					//In Transit: I case
					$stat = " (OE.status = 'In transit' OR OE.fedex_status NOT LIKE 'DE' AND OE.fedex_status NOT LIKE 'SE' AND OE.fedex_status NOT LIKE 'DL' AND OE.fedex_status NOT LIKE '' AND OE.fedex_status NOT LIKE 'OC') ";
					$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
					$ship_it_result = db_query($ship_it, db());
					while ($report_data = array_shift($ship_it_result)) {
						$dp = $report_data["print_date"];
						$print_date = date("F j Y H:i:s", strtotime($dp));
						$todaysDt = date('Y-m-d H:i:s');
						$otStr = '';
						$todaysDay = date("D", strtotime($todaysDt));
						if ($todaysDay == 'Sun') {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 2 days'));
						} else if ($todaysDay == 'Mon') {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 3 days'));
						} else {
							$considerDay = date('Y-m-d 15:00:00', strtotime($todaysDt . ' - 1 days'));
						}
						//echo $considerDay;
						$dayGrtrfourDays = date('Y-m-d H:i:s', strtotime($todaysDt . ' +  4 days'));
						$dayOther = date('Y-m-d H:i:s', strtotime($considerDay . ' - 1 days'));

						if (strtotime($dp) > strtotime($dayGrtrfourDays)) {
							$cnt_possible_issue = $cnt_possible_issue + 1;
						}

						if (floor((strtotime($todaysDt) - strtotime($dp)) / 86400) > 4) {
							$possible_issue_order_id_list .= $report_data["id"] . ",";
						}
					}

					//Exceptions: X case
					$stat = " (OE.status = 'Exception' OR OE.fedex_status LIKE 'DE' OR OE.fedex_status LIKE 'SE') ";
					$ship_it = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE " . $stat . " AND OE.setignore != 1 ORDER BY O.date_purchased";
					$ship_it_result = db_query($ship_it, db());
					while ($report_data = array_shift($ship_it_result)) {
						$cnt_possible_issue = $cnt_possible_issue + 1;

						$possible_issue_order_id_list .= $report_data["id"] . ",";
					}

					$rowsPossibleIssues = 0;
					if (trim($possible_issue_order_id_list) != "") {
						$possible_issue_order_id_list = substr($possible_issue_order_id_list, 0, strlen($possible_issue_order_id_list) - 1);

						$qryPossibleIssues = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel  FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.id in ( " . $possible_issue_order_id_list . ") ORDER BY O.date_purchased ";
						//echo $qryPossibleIssues . "<br>";
					
						$resPossibleIssues = db_query($qryPossibleIssues, db());
						$rowsPossibleIssues = tep_db_num_rows($resPossibleIssues);
					}

					//echo  "<br /> ship_it -> ".$ship_it . "<br>";	
					?>
					<!--  Possible issues-->

					<table cellSpacing="1" cellPadding="1" border="0" width="200">
						<tr align="middle">
							<td class="style24" style="height: 16px" colspan="2">
								<strong>ORDER TRACKING</strong>
							</td>
						</tr>

						<tr>
							<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
								No Data:
							</td>
							<td bgColor="#e4e4e4" class="style12" style="width: 10%">
								<a href="ups_status_report.php?status=Z">
									<?php echo $cntZ; ?>
								</a>
							</td>
						</tr>

						<tr>
							<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
								Not Picked Up:
							</td>
							<td bgColor="#e4e4e4" class="style12" style="width: 10%">
								<a href="ups_status_report.php?status=M">
									<?php echo $cntM; ?>
								</a>
							</td>
				</td>
			</tr>

			<tr>
				<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
					In Transit:
				</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="ups_status_report.php?status=I">
						<?php echo $cntI; ?>
					</a>
				</td>
			</tr>

			<tr>
				<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
					Exceptions:
				</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="ups_status_report.php?status=X">
						<?php if ($cntX > 0) {
							echo "<font color=red>" . $cntX . "</font>";
						} else {
							echo $cntX;
						} ?>
					</a>
				</td>
			</tr>

			<tr>
				<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
					Possible Issues:
				</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="ups_status_report.php?status=possible_issue">
						<?php //if ($cnt_possible_issue > 0) { echo "<font color=red>" . $cnt_possible_issue . "</font>"; } else { echo $cnt_possible_issue;} ?>
						<?php if ($rowsPossibleIssues > 0) {
							echo "<font color=red>" . $rowsPossibleIssues . "</font>";
						} else {
							echo '0';
						} ?>
					</a>
				</td>
			</tr>

			<tr>
				<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
					Tracking not Sent to Shopify:
				</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="ups_status_report.php?status=TN_No_Shopify">
						<?php echo $cntTN_No_Shopify; ?>
					</a>
				</td>
			</tr>

			<tr>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
					<a href="fedex_update_status.php">Manually Update </a>
					<div class="tooltip">
						<i class="fa fa-info-circle" aria-hidden="true"></i>
						<span class="tooltiptext">Cron job automatically runs once every hour</span>
					</div>
				</td>
			</tr>
			<tr>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
					<?php
					$yyww2 = "SELECT * FROM ucbdb_last_ups_check";
					$yyww2_result = db_query($yyww2, db());
					while ($row_yyww2_result = array_shift($yyww2_result)) {
						?>
						Last Update:
						<?php echo $row_yyww2_result['when_process']; ?>
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php
		$con_roblem_you = "SELECT * FROM ucb_contact WHERE employee = '" . $_COOKIE['userinitials'] . "' AND status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
		$con_roblem_all = "SELECT * FROM ucb_contact WHERE status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
		$con_roblem_you_result = db_query($con_roblem_you, db());
		$con_roblem_all_result = db_query($con_roblem_all, db());
		$con_roblem_you_result_rows = tep_db_num_rows($con_roblem_you_result);
		$con_roblem_all_result_rows = tep_db_num_rows($con_roblem_all_result);
		?>
		<table cellSpacing="1" cellPadding="1" border="0" width="200">
			<tr align="middle">
				<td class="style24" style="height: 16px" colspan="2">
					<strong>CONTACT ISSUES</strong>
				</td>
			</tr>
			<?php if ($con_roblem_you_result_rows > 0) { ?>
				<tr vAlign="center">
					<td bgColor="red" class="style12left" style="width: 10%">Your Issues:</td>
					<td bgColor="red" class="style12" style="width: 10%"><a
							href="contact_status_report_employee.php?ty_id=<?php echo $_COOKIE['userinitials'] ?>">
							<?php echo $con_roblem_you_result_rows; ?>
						</a></td>
				</tr>
			<?php } ?>
			<?php
			$con2_roblem_all = "SELECT DISTINCT employee FROM ucb_contact WHERE status = 'Attention' AND type_id NOT LIKE '%mb_rdy%' and employee != '" . $_COOKIE['userinitials'] . "'";
			$con2_roblem_all_result = db_query($con2_roblem_all, db());
			$con2_roblem_all_result_rows = tep_db_num_rows($con2_roblem_all_result);
			while ($con2_roblem_all_result_array = array_shift($con2_roblem_all_result)) {
				// echo $con2_roblem_all_result_array[employee];
				$con3_roblem_you = "SELECT * FROM ucb_contact WHERE employee = '" . $con2_roblem_all_result_array['employee'] . "' AND status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
				$con3_roblem_you_result = db_query($con3_roblem_you, db());
				$con3_roblem_you_result_rows = tep_db_num_rows($con3_roblem_you_result);
				?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
						<?php
						$gfn2 = "SELECT * FROM ucbdb_employees WHERE initials = '" . $con2_roblem_all_result_array['employee'] . "'";
						$gfn2_result = db_query($gfn2, db());
						while ($gfn2_array = array_shift($gfn2_result)) {
							echo $gfn2_array['name'];
						}
						?>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a
							href="contact_status_report_employee.php?ty_id=<?php echo $con2_roblem_all_result_array['employee']; ?>">
							<?php echo $con3_roblem_you_result_rows; ?>
						</a>
					</td>
				</tr>

			<?php } ?>

			<?php if ($con_roblem_all_result_rows > 0) { ?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">Total Issues:</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%"><a
							href="contact_status_report_employee.php?ty_id=All">
							<?php echo $con_roblem_all_result_rows; ?>
						</a></td>
				</tr>
			<?php } ?>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
					<a href="contact_entry.php">Submit New Record</a>
				</td>
			</tr>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
					<a href="contact_archve_status_drill.php?posting=yes&page=0&searchcrit=&">Search Old Records</a>
				</td>
			</tr>
		</table>
		<br>

		<?php
		$sql_pc = "SELECT * FROM ucbdb_credits WHERE pending = 'Pending' AND total > 0";
		$result_pc = db_query($sql_pc, db());
		$count_pc = tep_db_num_rows($result_pc);

		$sql_dc
			= "SELECT * FROM ucbdb_credits WHERE pending = 'Denied'  AND total > 0";
		$result_dc = db_query($sql_dc, db());
		$count_dc = tep_db_num_rows($result_dc);

		?>
		<table cellSpacing="1" cellPadding="1" border="0" width="200">
			<tr align="middle">
				<td colSpan="2" class="style24" style="height: 16px">
					PENDING CREDITS</td>
			</tr>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					Pending Credits</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="pending_credits.php?posting=yes&">
						<?php echo $count_pc; ?>
					</a>
				</td>
			</tr>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					Denied Credits</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="denied_credits.php?posting=yes&">
						<?php echo $count_dc; ?>
					</a>
				</td>
			</tr>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					Processed Credits</td>
				<td bgColor="#e4e4e4" class="style12" style="width: 10%">
					<a href="processed_credits.php?posting=yes&">View</a>
				</td>
			</tr>
		</table>
		<br>
		<table cellSpacing="1" cellPadding="1" border="0" width="200">
			<tr align="middle">
				<td colSpan="2" class="style24" style="height: 16px">
					<strong>EOD STATUS</strong>
				</td>
			</tr>
			<?php
			$today = date('m/d/y');
			$wh_query = "SELECT * FROM ucbdb_warehouse WHERE eod_time != '' ORDER BY eod_time";
			$wh_query_result = db_query($wh_query, db());
			while ($wh_query_array = array_shift($wh_query_result)) {
				$mysql_time = date("Y-m-d") . " " . $wh_query_array['eod_time'];   // Need to add date to the time to make this work
				$mysql_time = strtotime($mysql_time);
				$server_time = time();
				$diff = ($server_time - $mysql_time) / 60;

				$eodsql = "SELECT * FROM ucbdb_endofday where search_date = '" . $today . "' AND warehouse_name = '" . $wh_query_array['distribution_center'] . "'";
				$eodsql_result = db_query($eodsql, db());
				$eodsql_result_count = tep_db_num_rows($eodsql_result);
				if (($eodsql_result_count == 0) && ($diff < 60)) {
					$message = "PENDING";
					$bgcolor = "#d9f2ff";
					$link_a = "No";
				}
				if (($eodsql_result_count == 0) && ($diff > 60)) {
					$message = "LATE";
					$bgcolor = "red";
					$link_a = "No";
				}
				while ($eodsql_result_array = array_shift($eodsql_result)) {
					if (($eodsql_result_array['labels_on_report'] == '') && ($diff <= 60)) {
						$message = "PENDING";
						$bgcolor = "#d9f2ff";
						$link_a = "No";
					} elseif (($eodsql_result_array['labels_on_report'] == '') && ($diff > 60)) {
						$message = "LATE";
						$bgcolor = "#d9f2ff";
						$link_a = "No";
					} elseif (($eodsql_result_array['labels_on_report'] != $eodsql_result_array['labels_on_pickup'])) {
						$message = "ATTN";
						$bgcolor = "red";
						$link_a = "Yes";
					} elseif (($eodsql_result_array['labels_on_report'] != '') && ($eodsql_result_array['labels_on_report'] == $eodsql_result_array['labels_on_pickup'])) {
						$message = "OK";
						$bgcolor = "#d9f2ff";
						$link_a = "Yes";
					}
				}
				$arr_wh_report[] = array('distribution_center' => $wh_query_array['distribution_center'], 'message' => $message, 'bgcolor' => $bgcolor, 'link' => $link_a);
			}
			foreach ($arr_wh_report as $ind_report) {
				?>
				<tr>
					<td vAlign="center" bgColor="d9f2ff" class="style12left" style="width: 13%; height: 22px;"><strong>
							<?php echo $ind_report['distribution_center']; ?>
						</strong> </td>
					<td bgColor="<?php echo $ind_report['bgcolor']; ?>" class="style12" style="width: 10%; height: 22px;"><a
							href="eod_upload.php?warehouse_name=<?php echo $ind_report['distribution_center']; ?>&link=<?php echo $ind_report['link']; ?>">
							<?php echo $ind_report['message']; ?>
						</a></td>
				</tr>
			<?php
			}
			?>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
					<a href="eod_upload.php">Submit EOD</a>
				</td>
			</tr>
		</table>
		</td>
		<td valign="top">
			<?php
			$problem_all = "SELECT orders_id FROM orders WHERE order_issue = 1";
			$problem_all_result = db_query($problem_all, db());
			$problem_all_result_rows = tep_db_num_rows($problem_all_result);
			?>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px" colspan="2">
						<strong>Order Issues</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">Active Order Issues:</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%"><a
							href="report_active_order_issue.php?onlyactive_orders=yes">
							<?php
							if ($problem_all_result_rows > 0) {
								echo "<font color=red>";
							}
							echo $problem_all_result_rows;
							if ($problem_all_result_rows > 0) {
								echo "</font>";
							}
							?>
						</a></td>
				</tr>
				<tr vAlign="center">

					<td colspan="2" bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_search_order_issue.php?onlyactive_orders=yes">Search Former Order Issues
						</a>
					</td>
					<!--<td bgColor="#e4e4e4" class="style12" style="width: 10%"><a href="report_search_order_issue.php?onlyactive_orders=yes">
		<?php
		if ($problem_all_result_rows > 0) {
			echo "<font color=red>";
		}
		echo $problem_all_result_rows;
		if ($problem_all_result_rows > 0) {
			echo "</font>";
		}
		?></a></td>-->
				</tr>

			</table>
			<br>
			<?php
			$problem_all = "SELECT orders_id FROM orders WHERE fedex_validate_bad_add = 1";
			$problem_all_result = db_query($problem_all, db());
			$problem_all_result_rows = tep_db_num_rows($problem_all_result);
			?>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px" colspan="2">
						<strong>Bad Address List</strong>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">Current Bad Addresses:</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%"><a href="report_bad_address_list.php">
							<?php
							if ($problem_all_result_rows > 0) {
								echo "<font color=red>";
							}
							echo $problem_all_result_rows;
							if ($problem_all_result_rows > 0) {
								echo "</font>";
							}
							?>
						</a></td>
				</tr>
			</table>
			<br>
			<?php
			$sql_pc = "SELECT * FROM `orders_active_ucb_los_angeles` WHERE ship_status LIKE 'N' ";
			$result_pc = db_query($sql_pc, db());
			$count_pc = tep_db_num_rows($result_pc);

			$currentdate = new DateTime();
			$prev_date = $currentdate;

			$sql_pc = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 1 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
			//echo $sql_pc;
			$result_pc = db_query($sql_pc, db());
			$count_pc_tot_for_day = 0;
			while ($result_pc_row = array_shift($result_pc)) {
				$count_pc_tot_for_day = $count_pc_tot_for_day + 1;
			}

			$sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` WHERE ship_status LIKE 'N' ";
			$result_pc = db_query($sql_pc, db());
			$count_pc2 = tep_db_num_rows($result_pc);
			$sql_pc2 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 11 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
			//$sql_pc2 = "SELECT * FROM `orders_by_warehouse_eod` where warehouse_name = 'Hunt Valley' and eod_date = '". date("Y-m-d") ."'";
			$result_pc2 = db_query($sql_pc2, db());
			$count_pc_tot_for_day2 = 0;
			while ($result_pc_row = array_shift($result_pc2)) {
				$count_pc_tot_for_day2 = $count_pc_tot_for_day2 + 1;
			}

			//$sql_pc = "SELECT * FROM `orders_active_ucb_evansville` WHERE ship_status LIKE 'N' ";
			$sql_pc = "SELECT * FROM `orders_active_ucb_hannibal` WHERE ship_status LIKE 'N' ";
			$result_pc = db_query($sql_pc, db());
			$count_pc3 = tep_db_num_rows($result_pc);
			$sql_pc3 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 12 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
			//$sql_pc3 = "SELECT * FROM `orders_by_warehouse_eod` where warehouse_name = 'Hannibal' and eod_date = '". date("Y-m-d") ."'";
			$result_pc3 = db_query($sql_pc3, db());
			$count_pc_tot_for_day3 = 0;
			while ($result_pc_row = array_shift($result_pc3)) {
				$count_pc_tot_for_day3 = $count_pc_tot_for_day3 + 1;
			}

			$sql_pc = "SELECT * FROM `orders_active_ucb_salt_lake` WHERE ship_status LIKE 'N' ";
			$result_pc = db_query($sql_pc, db());
			$count_pc4 = tep_db_num_rows($result_pc);
			$sql_pc4 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 3 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
			//echo $sql_pc4 . "<br>";
			$result_pc4 = db_query($sql_pc4, db());
			$count_pc_tot_for_day4 = 0;
			while ($result_pc_row = array_shift($result_pc4)) {
				$count_pc_tot_for_day4 = $count_pc_tot_for_day4 + 1;
			}

			$sql_pc = "SELECT * FROM `orders_sps` WHERE sent = 0 ";
			$result_pc = db_query($sql_pc, db());
			$count_pc5 = tep_db_num_rows($result_pc);
			//$sql_pc5 = "SELECT * FROM `orders_sps` inner join orders on orders.orders_id = orders_sps.orders_id WHERE orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "'";
			$sql_pc5 = "SELECT * FROM `orders_active_export` inner join orders_sps on orders_active_export.orders_id = orders_sps.orders_id WHERE sent = 1 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
			//echo $sql_pc5 . "<br>";
			$result_pc5 = db_query($sql_pc5, db());
			$count_pc_tot_for_day5 = tep_db_num_rows($result_pc5);

			$sql_pc5 = "SELECT * FROM `ubox_order_fedex_details` WHERE ubox_order_fedex_details.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'
	and ubox_order_fedex_details.orders_id in (Select orders_id from orders_sps where sent = 1)";
			//echo $sql_pc5 . "<br>";
			$result_pc5 = db_query($sql_pc5, db());
			$count_pc_tot_for_day5_fedex = tep_db_num_rows($result_pc5);

			$count_pc_tot_for_day5 = $count_pc_tot_for_day5 + $count_pc_tot_for_day5_fedex;

			?>
			<table cellSpacing="1" cellPadding="1" border="0" width="250">
				<tr align="middle">
					<td colSpan="4" class="style24" style="height: 16px">
						<b>PENDING SHIPMENTS</b>
					</td>
				</tr>
				<tr align="middle">
					<td class="style24" style="height: 16px">
						Warehouse</td>
					<td class="style24" style="height: 16px">
						Labels to be Printed</td>
					<td class="style24" style="height: 16px">
						Labels Printed Today</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">Los Angeles</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="pending_shipments.php?tbl=losangeles&posting=yes" target="_blank">
							<?php echo $count_pc; ?>
						</a>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="shipments_shipped.php?tbl=losangeles&posting=yes" target="_blank">
							<?php echo $count_pc_tot_for_day; ?>
						</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">Hunt Valley</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="pending_shipments.php?tbl=huntvally&posting=yes" target="_blank">
							<?php echo $count_pc2; ?>
						</a>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="shipments_shipped.php?tbl=huntvally&posting=yes" target="_blank">
							<?php echo $count_pc_tot_for_day2; ?>
						</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">Hannibal</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="pending_shipments.php?tbl=hannibal&posting=yes" target="_blank">
							<?php echo $count_pc3; ?>
						</a>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="shipments_shipped.php?tbl=hannibal&posting=yes" target="_blank">
							<?php echo $count_pc_tot_for_day3; ?>
						</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">Salt Lake</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="pending_shipments.php?tbl=saltlake&posting=yes" target="_blank">
							<?php echo $count_pc4; ?>
						</a>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="shipments_shipped.php?tbl=saltlake&posting=yes" target="_blank">
							<?php echo $count_pc_tot_for_day4; ?>
						</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">New Items</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="pending_shipments.php?tbl=newitem&posting=yes" target="_blank">
							<?php echo $count_pc5; ?>
						</a>
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="shipments_shipped.php?tbl=newitem&posting=yes" target="_blank">
							<?php echo $count_pc_tot_for_day5; ?>
						</a>
					</td>
				</tr>


			</table>

			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="250">
				<tr align="middle">
					<td colSpan="4" class="style24" style="height: 16px">
						<b>ORDERS which are not added in Label data</b>
					</td>
				</tr>
				<tr align="middle">
					<td class="style24" style="height: 16px">
						Order #</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12">
						<?php
						$sql_pc = "Select orders_id from orders where date_purchased >= '" . date("Y-m-d", strtotime("-2 day")) . "' and (ubox_order = '' or ubox_order is null)";
						//echo $sql_pc;
						$result_pc = db_query($sql_pc, db());
						while ($result_pc_row = array_shift($result_pc)) {
							$arr_warehouse = array('orders_active_ucb_los_angeles', 'orders_active_ucb_philadelphia', 'orders_active_ucb_phoenix', 'orders_active_ucb_hunt_valley', 'orders_active_ucb_hannibal', 'orders_active_ucb_evansville', 'orders_active_ucb_rochester', 'orders_active_ucb_salt_lake', 'orders_active_ucb_atlanta', 'orders_active_ucb_dallas', 'orders_active_ucb_danville', 'orders_active_ucb_iowa', 'orders_active_ucb_montreal', 'orders_active_ucb_toronto');
							$data_found = "no";
							foreach ($arr_warehouse as $tbl_warehouse) {
								$sql_pc4 = "Select orders_id FROM $tbl_warehouse where orders_id = " . $result_pc_row["orders_id"];
								$result_pc4 = db_query($sql_pc4, db());
								while ($result_pc_row4 = array_shift($result_pc4)) {
									$data_found = "yes";
								}
							}

							if ($data_found == "no") {
								?>
								<a href="orders.php?id=<?php echo $result_pc_row["orders_id"]; ?>&proc=View&page=0"
									target="_blank">
									<?php echo $result_pc_row["orders_id"]; ?>
								</a>
							<?php }

						} ?>

					</td>
				</tr>
			</table>
			<br>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px" colspan="2">
						<strong>Survey Responses</strong>
					</td>
				</tr>

				<?php
				$start_date = date('Ymd', strtotime(date('m/d/Y')));
				$end_date = date('Ymd', strtotime('today - 30 days'));

				$hm = "SELECT * FROM survey_nps WHERE date<='$start_date' and date>='$end_date'";
				$hm_result = db_query($hm, db());
				$surveycount = tep_db_num_rows($hm_result);
				?>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
						Last 30 Days:
					</td>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a
							href="http://b2c.usedcardboardboxes.com/report_survey.php?action=run&start_date=<?php echo $end_date; ?>&end_date=<?php echo $start_date; ?>&surveyview=1">
							<?php echo $surveycount; ?>
						</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
						Need to contact:
					</td>
					<?php
					$currentYear = date("Y");
					$noOfNeedToCntct = db_query("SELECT COUNT(id) AS noOfNeedToCntct FROM survey_nps INNER JOIN orders ON orders.orders_id = survey_nps.order_id  WHERE YEAR(survey_nps.date) = '" . $currentYear . "' AND survey_nps.nps <= 7 AND survey_nps.contactok = 'Y' AND orders.survey_res_flag = 0 ", db());
					?>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a style=" color: red;"
							href="survey_resp_contact_log.php?action=showNTC&year=<?php echo $currentYear; ?>"
							target="_blank">
							<?php echo !empty($noOfNeedToCntct[0]['noOfNeedToCntct']) ? $noOfNeedToCntct[0]['noOfNeedToCntct'] : ''; ?>
						</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
						Contacted:
					</td>
					<?php
					$currentYear = date("Y");
					$noOfResponces = db_query("SELECT COUNT(orders_id) AS noOfResponces FROM orders WHERE orders.survey_res_flag = 1 ", db());
					?>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="survey_resp_contact_log.php?action=showResp&year=<?php echo $currentYear; ?>"
							target="_blank">
							<?php echo !empty($noOfResponces[0]['noOfResponces']) ? $noOfResponces[0]['noOfResponces'] : ''; ?>
						</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12left" style="width: 10%">
						Number of Survey Requests Emailed (in 2021):
					</td>
					<?php
					$currentYear = date("Y");
					$noOfEmailSent = db_query("SELECT COUNT(*) AS noOfEmailSent FROM orders_survey_data_log INNER JOIN orders ON orders_survey_data_log.orders_id = orders.orders_id WHERE YEAR(orders_survey_data_log.survey_sent_on) = '" . $currentYear . "' AND orders.survey = 1", db());
					?>
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="survey_resp_contact_log.php?action=showEmailSent&year=<?php echo $currentYear; ?>"
							target="_blank">
							<?php echo !empty($noOfEmailSent[0]['noOfEmailSent']) ? $noOfEmailSent[0]['noOfEmailSent'] : ''; ?>
						</a>
					</td>
				</tr>

				<tr>
					<td colspan="2" bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="https://b2c.usedcardboardboxes.com/load_survey.php">Manually Update
						</a>
						<div class="tooltip">
							<i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Cron job automatically runs once a day at 3 am CST</span>
						</div>
					</td>
				</tr>

				<?php
				$sql = "SELECT * FROM tblvariable where variablename = 'b2c_load_survey_time'";
				$result = db_query($sql, db());
				while ($myrowsel = array_shift($result)) { ?>
					<tr vAlign="center">
						<td colspan="2" bgColor="#e4e4e4" class="style12" style="height: 16px">
							Last Update:
							<?php echo $myrowsel["variablevalue"]; ?>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<br>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>MOVING.COM</strong>
					</td>
				</tr>
				<?php
				$ml_proc = "SELECT * FROM moving_leads WHERE flag = 0";
				$ml_proc_result = db_query($ml_proc, db());
				$ml_proc_result_rows = tep_db_num_rows($ml_proc_result);
				?>
				<tr vAlign="center">
					<?php if ($ml_proc_result_rows != 0) { ?>
						<td bgColor="red" class="style12" style="width: 10%">
						<?php } else { ?>
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<?php } ?>
						<a href="http://www.usedcardboardboxes.com/moving_leads/">Moving.com Processing</a><br>User &
						Pass: ucbox
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://admin.moving.com">Moving.com Portal</a><br>User & Pass: ucbox
					</td>
				</tr>


				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="https://loops.usedcardboardboxes.com/deleteSPAMmoveleads.php">DELETE MOVING SPAM</a>
					</td>
				</tr>
			</table>
			<br>



			<?php
			$dupqry = "SELECT * FROM (SELECT count( tracking_number ) AS total, orders_id
FROM orders_active_export  GROUP BY tracking_number ORDER BY `total` DESC) as A WHERE A.total>1";
			$dupqryres = db_query($dupqry, db());
			$dupqryresnum = tep_db_num_rows($dupqryres);


			$dupqrycurcount = "SELECT DISTINCT number FROM orders_active_dupes";
			$dupqrycurcountres = db_query($dupqrycurcount, db());
			while ($dupqrycurcountrescnt = array_shift($dupqrycurcountres)) {
				$dupqrycurcountrescount = $dupqrycurcountrescnt["number"];
			}
			?>


			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>UPS DUPLICATE CHECK</strong>
					</td>
				</tr>
				<?php
				$value = $dupqryresnum - $dupqrycurcountrescount;
				if ($value != 0) {

					$sql33 = "UPDATE orders_active_dupes SET mailflag = 1";
					$result33 = db_query($sql33, db());
					?>
					<tr vAlign="center">
						<td bgColor="red" class="style12" style="width: 10%">
							<strong>DUPLICATES EXIST</strong>
						</td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<a href="dupe_view.php?number=<?php echo $value; ?>">There are
								<?php echo $value; ?> Duplicates!
							</a>
						</td>
					</tr>

					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%"><br>

							<a href="dupe_reset.php?number=<?php echo $dupqryresnum; ?>">Reset Duplicate Warning</a>
						</td>
					</tr>
					<?php
				}
				if ($value == 0) {
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							No Duplicates Found</td>
					</tr>
				<?php } ?>

			</table>

			<br>
			<?php
			$returns = "SELECT id FROM orders_active_export WHERE return_tracking_number != ''";
			$returnsres = db_query($returns, db());
			$returnsresnum = tep_db_num_rows($returnsres);
			?>


			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px" colspan="2">
						<strong>RETURN STATUS</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">Pending Returns:
						<a href="return_status_report.php">
							<?php echo $returnsresnum; ?>
						</a>
					</td>
				</tr>
			</table>


			<br>
			<br>
		</td>

		<?php // second coloumn ends here  ?>

		<td valign="top">
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>REPORTS</strong>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="https://loops.usedcardboardboxes.com/report_b2c_inventory_new.php">B2C Inventory</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="https://loops.usedcardboardboxes.com/report_box_bucks_code.php">Box Bucks Code
							report</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_credit.php">Credit Report</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_referrer.php">Referrer Report</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_referrer_new.php">Summary Referrer Report</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_sps.php">SPS Order Report</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a
							href="http://www.usedcardboardboxes.com/sps/index.php?posting=yes&page=0&searchcrit=&action=12345672343223433445">SPS
							Shipped Order Report</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_how_hear.php">Order Source Report<br>By Single Source</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_how_hear2.php">Order Source Report<br>All Sources By Date</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_ups_module.php">UPS Status Report</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
						<a href="eod_report.php">End of Day Reports</a>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="report_move.php">Moving.com Lead Report</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%"> <a
							href="report_restricted_bbcode.php">Restricted BB Code Report</a></td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%"> <a
							href="https://loops.usedcardboardboxes.com/report_freight_broker.php">Freight Broker
							Report</a></td>
				</tr>
			</table>

			<br>
			<?php
			$a_qry = 'SELECT * FROM `affiliate_affiliate` WHERE is_approved = \'N\' AND affiliate_id > 624';
			$a_qryres = db_query($a_qry, db());
			$a_qryresnum = tep_db_num_rows($a_qryres);
			?>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>LINKS</strong>
					</td>

				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="https://b2c.usedcardboardboxes.com/b2c-order-giftcard.php" target="_blank">B2C Order -
							Post directly</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="b2c-order-giftcard-new.php" target="_blank">Create New Order (w/o payment)</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/b2b5/manageCompanies.asp">Access B2B System</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://loops.usedcardboardboxes.com">Access Loop System</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://loops.usedcardboardboxes.com/mccormickdashboard.php">McCormick Dashboard</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://loops.usedcardboardboxes.com/huntvalleywarehouse_141592653.php">Hunt Valley
							Dashboard</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://loops.usedcardboardboxes.com/demodashboard.php">Demo Dashboard</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://usedcardboardboxeshv.myq-see.com:90/webcamera.html">Hunt Valley Cameras</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://usedcardboardboxesev.myq-see.com:90/webcamera.html">EVV Cameras</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/CL/b2b_gaylord.asp">Gaylord Farming</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/CL/b2b_pallet.asp">Pallet Box Farming</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://loops.usedcardboardboxes.com/getEmails2.php">B2B Email Extractor</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2c.usedcardboardboxes.com/report_b2c_emails.php">B2C Email Extractor</a>
					</td>
				</tr>

			</table><br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>B2C FARMING SYSTEM</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/CL/nextday.asp">Next Day Farming</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/CL/twoday.asp">Two Day Farming</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2b.usedcardboardboxes.com/CL/farm.asp">Email Farming</a>
					</td>
				</tr>

			</table><br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>FILE CABINET</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="upload_files.php">Upload Files</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="view_files.php">View Files</a>
					</td>
				</tr>

			</table>
			<br>
			<br>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>AFFILIATES</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						Awaiting Approval: <a
							href="http://www.usedcardboardboxes.com/administration/affiliate_affiliates.php">
							<?php echo $a_qryresnum; ?>
						</a></td>
				</tr>
			</table>
			<br>
			<br>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>HOLIDAY MESSAGE</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<?php
					$hm = "SELECT * FROM holiday_message WHERE id = 1";
					$hm_result = db_query($hm, db());
					while ($hm_array = array_shift($hm_result)) {
						if ($hm_array["messageon"] == 1) {
							?>
							<td bgColor="red" class="style12" style="width: 10%">
								<a href="http://b2c.usedcardboardboxes.com/holidaymessage.php">
									<strong>ON</strong>
								</a>
							</td>
							<?php
						} else {
							?>
							<td bgColor="#e4e4e4" class="style12" style="width: 10%">
								<a href="http://b2c.usedcardboardboxes.com/holidaymessage.php">
									Off
								</a>
							</td>
							<?php
						}
					}
					?>
				</tr>
			</table><br>
			<br>
			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>SOPS</strong>
					</td>
				</tr>

				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<form action="report_sop.php" method="GET">
							<input type="hidden" name="action" value="run">
							<input type="hidden" name="employee" value="A">
							<input type="hidden" name="division" value="A">
							<input type="hidden" name="department" value="A">
							<input type="hidden" name="frequency" value="A">
							<input type=text size=10 name="search"> <input type=submit size=10 value="Search">
					</td>
				</tr>
				</form>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2c.usedcardboardboxes.com/report_sop.php">View SOPs
						</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://b2c.usedcardboardboxes.com/add_sop.php">Add SOP
						</a>
					</td>
				</tr>
			</table><br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>ADMINISTRATION</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="employee.php?posting=yes">Employee Setup</a>
					</td>
				</tr>

			</table><br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<form action="update_masterpin.php" name="masterpin" method="post">
					<tr align="middle">
						<td class="style24" style="height: 16px">
							<strong>Master Pin</strong>
						</td>
					</tr>
					<?php

					$sql = "SELECT * FROM tblvariable where variablename = 'master_pin'";
					$result = db_query($sql, db());
					$zopim_chat_flg = "on";
					while ($myrowsel = array_shift($result)) { ?>
						<tr vAlign="center">
							<td bgColor="#e4e4e4" class="style12" style="width: 10%">
								Master Pin: <input type="password" name="txtmaterpin" id="txtmaterpin"
									value="<?php echo $myrowsel["variablevalue"]; ?>" />
							</td>
						</tr>
						<?php
					}
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<input type="submit" name="updatemaster" value="Update Master Pin" />
						</td>
					</tr>
				</form>
			</table>
			<br> <br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px" colspan="2">
						<strong>Email Configuration</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%" colspan="2">
						<form action="update_emailgrplist.php" name="updateemlgrp" method="post">
							<select name="emailgrp" id="emailgrp" onchange="onchgemlgrp(this.value); return false;">
								<option value="">Select the Email Group</option>
								<?php
								$sql = "SELECT * FROM email_config order by emailgroup ";
								$result = db_query($sql, db());
								while ($myrowsel = array_shift($result)) {
									echo "<option value=" . $myrowsel["unqid"] . " ";

									if (isset($_REQUEST["emailgrpid"])) {
										if ($myrowsel["unqid"] == $_REQUEST["emailgrpid"])
											echo " selected ";
									}
									echo " >" . $myrowsel["emailgroup"] . "</option>";
								}
								?>
							</select>
							<div id="emailgrpdiv" class="style12"></div>
						</form>
					</td>
				</tr>
			</table>


			<br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>Zopim Chat</strong>
					</td>
				</tr>
				<?php

				$sql = "SELECT * FROM tblvariable where variablename = 'zopim_chat_flg'";
				$result = db_query($sql, db());
				$zopim_chat_flg = "on";
				while ($myrowsel = array_shift($result)) {
					if (strtoupper($myrowsel["variablevalue"]) == strtoupper('off')) {
						$zopim_chat_flg = "off";
					}
				}
				if ($zopim_chat_flg == "on") {
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							Zopim chat is <b>On</b></td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<a href="zopim_chat_flg.php?flg=off">Turn off Zopim chat</a>
						</td>
					</tr>
					<?php
				} else {
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							Zopim chat is <b>Off</b></td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<a href="zopim_chat_flg.php?flg=on">Turn on Zopim chat</a>
						</td>
					</tr>
					<?php
				}
				?>

			</table>

			<br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>B2C Google Address Flag</strong>
					</td>
				</tr>
				<?php

				$sql = "SELECT * FROM tblvariable where variablename = 'b2c_page_google_add_flg'";
				$result = db_query($sql, db());
				$b2c_page_google_add_flg = "on";
				while ($myrowsel = array_shift($result)) {
					if (strtoupper($myrowsel["variablevalue"]) == strtoupper('off')) {
						$b2c_page_google_add_flg = "off";
					}
				}
				if ($b2c_page_google_add_flg == "on") {
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							Google Address Flag is <b>On</b></td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<a href="b2c_page_google_add_flg.php?flg=off">Turn off Google Address Flag</a>
						</td>
					</tr>
					<?php
				} else {
					?>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							Google Address Flag is <b>Off</b></td>
					</tr>
					<tr vAlign="center">
						<td bgColor="#e4e4e4" class="style12" style="width: 10%">
							<a href="b2c_page_google_add_flg.php?flg=on">Turn on Google Address Flag</a>
						</td>
					</tr>
					<?php
				}
				?>

			</table>
			<br> <br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>CONFIGURATION</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="customer_log.php?posting=yes">Customer Log</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="warehouse_list.php?posting=yes">Distribution Centers</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="item_list.php?posting=yes">Item List</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="reason_list.php?posting=yes">Credit Reason Codes</a>
					</td>
				</tr>

			</table>
			<br>
			<br>


			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>SUPPORT TICKET SYSTEM</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://tivex.com/support/index.php" target="blank">Create New Ticket</a>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://tivex.com/support/tickets.php" target="blank">Track Open Ticket</a>
					</td>
				</tr>
			</table>
			<br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>COOKIE CLEANER</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="http://www.usedcardboardboxes.com/cookie_eater.php">Delete Cookies and Stored
							Values</a>
					</td>
				</tr>

			</table>
			<br>
			<br>

			<table cellSpacing="1" cellPadding="1" border="0" width="200">
				<tr align="middle">
					<td class="style24" style="height: 16px">
						<strong>LOGOUT</strong>
					</td>
				</tr>
				<tr vAlign="center">
					<td bgColor="#e4e4e4" class="style12" style="width: 10%">
						<a href="logoff.php">Log Out</a>
					</td>
				</tr>

			</table>
			<br>
			<br>
			<font></font>
		</td>
		</tr>
		</table>
	</div>
	<script>
		function onchgemlgrp(selectedgrpval) {

			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			}
			else {// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("emailgrpdiv").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "getemailgrplist.php?selectedgrpid=" + selectedgrpval, true);
			xmlhttp.send();
		}
	</script>
</body>

</html>