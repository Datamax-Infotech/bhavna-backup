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
            $res = db_query($query);
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
            <a href="report_survey.php?action=run&start_date=<?php echo $lastweekstdate; ?>&end_date=<?php echo $lastweekendate; ?>&surveyview=1">
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

            $promoter2 = 0;
            $avg = 0;
            $neutral2 = 0;
            $detractor2 = 0;
            $res2 = db_query($query2);
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
            <a href="report_survey.php?action=run&start_date=<?php echo $lastmonthstdate; ?>&end_date=<?php echo $lastmonthendate; ?>&surveyview=1">
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

            $current_month = date('n');
            $current_year = date('Y');
            $timestamp_start = strtotime('1-October-' . ($current_year - 1));
            $timestamp_end = strtotime('1-October-' . ($current_year - 1));
            if ($current_month >= 1 && $current_month <= 3) {
                $timestamp_start = strtotime('1-October-' . ($current_year - 1));
                $timestamp_end = strtotime('31-December-' . ($current_year - 1));
            } else if ($current_month >= 4 && $current_month <= 6) {
                $timestamp_start = strtotime('1-January-' . $current_year);
                $timestamp_end = strtotime('31-March-' . $current_year);
            } else if ($current_month >= 7 && $current_month <= 9) {
                $timestamp_start = strtotime('1-April-' . $current_year);
                $timestamp_end = strtotime('30-June-' . $current_year);
            } else if ($current_month >= 10 && $current_month <= 12) {
                $timestamp_start = strtotime('1-July-' . $current_year);
                $timestamp_end = strtotime('30-September-' . $current_year);
            }

            if ($timestamp_start === false || $timestamp_end === false) {
                throw new Exception('Invalid date');
            }

            $st_lastqtr = date('Y-m-d', $timestamp_start);
            $end_lastqtr = date('Y-m-d', $timestamp_end);

            $quarterstdate = $st_lastqtr;
            $quarterendate = $end_lastqtr;

            $quarterstdate = date("Ymd", strtotime("-91 days"));
            $quarterendate = date("Ymd");


            $query3 = "SELECT * FROM survey_nps WHERE date>='" . $quarterstdate . "' AND date<='" . $quarterendate . "'";
            //echo $query3;

            $res3 = db_query($query3);
            $detractor3 = 0;
            $neutral3 = 0;
            $promoter3 = 0;
            $avg3 = 0;
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
            <a href="report_survey.php?action=run&start_date=<?php echo $quarterstdate; ?>&end_date=<?php echo $quarterendate; ?>&surveyview=1">
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

            $res4 = db_query($query4);
            $detractor4 = 0;
            $neutral4 = 0;
            $promoter4 = 0;
            $avg4 = 0;
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

            <a href="report_survey.php?action=run&start_date=<?php echo $yearstdate; ?>&end_date=<?php echo $yearendate; ?>&surveyview=1">
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

            $res5 = db_query($query5);
            $detractor5 = 0;
            $neutral5 = 0;
            $promoter5 = 0;
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
            <a href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate; ?>&end_date=<?php echo $lastyearendate; ?>&surveyview=1">
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

            $res6 = db_query($query6);
            $detractor6 = 0;
            $neutral6 = 0;
            $promoter6 = 0;
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

            <a href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate2; ?>&end_date=<?php echo $lastyearendate2; ?>&surveyview=1">
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

            $res7 = db_query($query7);
            $detractor7 = 0;
            $neutral7 = 0;
            $promoter7 = 0;
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

            <a href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate3; ?>&end_date=<?php echo $lastyearendate3; ?>&surveyview=1">
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

            $res8 = db_query($query8);
            $detractor8 = 0;
            $neutral8 = 0;
            $promoter8 = 0;
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
            <a href="report_survey.php?action=run&start_date=<?php echo $lastyearstdate4; ?>&end_date=<?php echo $lastyearendate4; ?>&surveyview=1">
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
						$resl2 = db_query($query_ytdl2);
						while($row_ytdl2 = array_shift($resl2))
						{
						$total_revenue_ytdl2+=$row_ytdl2["order_total"];
						}

						$query_ytdl2 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl2 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl2)";
						$resl2 = db_query($query_ytdl2);
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
						$resl3 = db_query($query_ytdl3);
						while($row_ytdl3 = array_shift($resl3))
						{
						$total_revenue_ytdl3+=$row_ytdl3["order_total"];
						}

						$query_ytdl3 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND(UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl3 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl3)";
						$resl3 = db_query($query_ytdl3);
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
						$resl4 = db_query($query_ytdl4);
						while($row_ytdl4 = array_shift($resl4))
						{
						$total_revenue_ytdl4+=$row_ytdl4["order_total"];
						}

						$query_ytdl4 = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND (UNIX_TIMESTAMP(entry_date)>=$start_date_ytdl4 AND UNIX_TIMESTAMP(entry_date)<=$end_date_ytdl4)";
						$resl4 = db_query($query_ytdl4);
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
$res = db_query($query_mtd);
$total_revenue_mtd = 0;
while ($row_mtd = array_shift($res)) {
    $total_revenue_mtd += $row_mtd["order_total"];
}

$query_mtd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_mtd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtd)";
$res = db_query($query_mtd);
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
    $result_empq = db_query($newsel);
    while ($rowemp_empq = array_shift($result_empq)) {
        $timestamp = strtotime($start_Dt_tmp);
        if ($timestamp === false) {
            // Handle the error, e.g. throw an exception
            throw new Exception('Invalid date');
        }

        $quota_one_day = $rowemp_empq["quota"] / date('t', $timestamp);
        //$quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
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
$resl = db_query($query_mtdl);
while ($row_mtdl = array_shift($resl)) {
    $total_revenue_mtdl += $row_mtdl["order_total"];
}

$query_mtd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_mtd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_mtdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_mtdl)";
$res = db_query($query_mtd);
while ($row_mtd = array_shift($res)) {
    $total_revenue_mtdl = $total_revenue_mtdl - $row_mtd["order_total"];
}

$quota_ov_tod_mtd1 = 0;
$begin = new DateTime($st_lastmonth);
//$end = new DateTime(date("m/d/Y", $end_date_mtdl));
if ($end_date_mtdl === false) {
    // Handle the error, e.g. throw an exception
    throw new Exception('Invalid date');
}

$end = new DateTime(date("m/d/Y", $end_date_mtdl));

for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
    $start_Dt_tmp = $datecnt->format("Y-m-d");
    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
    $result_empq = db_query($newsel);
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
    $result_empq = db_query($newsel);
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
    $result_empq = db_query($newsel);
    while ($rowemp_empq = array_shift($result_empq)) {
        $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
        $quota_ov_tod_yesterday = $quota_ov_tod_yesterday + $quota_one_day;
    }
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
$last_yr = date('Y') - 1; // previously $last_yr was not defined					
$st_lastqtr_lastyr = date($last_yr . '-m-01', strtotime($st_date));
$end_lastqtr_lastyr = date($last_yr . '-m-t', strtotime($end_date));

//$unqid = $unqid + 1;

$this_qtr_st_dt = $st_date;
$this_qtr_end_dt = $end_date;

$total_revenue_qtr = 0;
$start_date_qtr = strtotime($st_date);
$end_date_qtr = strtotime($end_date);
$end_date_qtr = $end_date_qtr + 86400;
$query_qtr = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_qtr .= " WHERE class='ot_total' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtr AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtr)";
//echo $query_qtr . " = " . $st_date . " " . $end_date . "<br>";
$res = db_query($query_qtr);
while ($row_qtr = array_shift($res)) {
    $total_revenue_qtr += $row_qtr["order_total"];
}

$query_qtr = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_qtr .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtr AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtr)";
$res = db_query($query_qtr);
while ($row_qtr = array_shift($res)) {
    $total_revenue_qtr = $total_revenue_qtr - $row_qtr["order_total"];
}

$quota_ov_tod_qtr = 0;
$begin = new DateTime($st_date);
$end = new DateTime($end_date_2);
for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
    $start_Dt_tmp = $datecnt->format("Y-m-d");
    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
    $result_empq = db_query($newsel);
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
$resl = db_query($query_qtrl);
$total_revenue_qtrl = 0;
while ($row_qtrl = array_shift($resl)) {
    $total_revenue_qtrl += $row_qtrl["order_total"];
}

$query_qtrl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_qtrl .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_qtrl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_qtrl)";
$resl = db_query($query_qtrl);
while ($row_qtrl = array_shift($resl)) {
    $total_revenue_qtrl = $total_revenue_qtrl - $row_qtrl["order_total"];
}

$quota_ov_tod_qtr1 = 0;
$begin = new DateTime($st_lastqtr);
$end = new DateTime($end_lastqtr);
for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
    $start_Dt_tmp = $datecnt->format("Y-m-d");
    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
    $result_empq = db_query($newsel);
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
$res = db_query($query_ytd);
$total_revenue_ytd = 0;
while ($row_ytd = array_shift($res)) {
    $total_revenue_ytd += $row_ytd["order_total"];
}

$query_ytd = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_ytd .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytd AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytd)";
$res = db_query($query_ytd);
while ($row_ytd = array_shift($res)) {
    $total_revenue_ytd = $total_revenue_ytd - $row_ytd["order_total"];
}

$quota_ov_tod_ytd = 0;
$begin = new DateTime(date('01/01/' . date("Y")));
$end = new DateTime(date("12/31/Y"));
for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
    $start_Dt_tmp = $datecnt->format("Y-m-d");
    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
    $result_empq = db_query($newsel);
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
$resl = db_query($query_ytdl);
$total_revenue_ytdl = 0;
while ($row_ytdl = array_shift($resl)) {
    $total_revenue_ytdl += $row_ytdl["order_total"];
}

$query_ytdl = "SELECT OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
$query_ytdl .= " WHERE class='ot_tax' AND (UNIX_TIMESTAMP(date_purchased)>=$start_date_ytdl AND UNIX_TIMESTAMP(date_purchased)<=$end_date_ytdl)";
$resl = db_query($query_ytdl);
while ($row_ytdl = array_shift($resl)) {
    $total_revenue_ytdl = $total_revenue_ytdl - $row_ytdl["order_total"];
}

$quota_ov_tod_ytd1 = 0;
$begin = new DateTime(date('01/01/' . (date("Y") - 1)));
$end = new DateTime(date("12/31/" . (date("Y") - 1)));
for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
    $start_Dt_tmp = $datecnt->format("Y-m-d");
    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
    $result_empq = db_query($newsel);
    while ($rowemp_empq = array_shift($result_empq)) {
        $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
        $quota_ov_tod_ytd1 = $quota_ov_tod_ytd1 + $quota_one_day;
    }
}
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

$ship_it_result = db_query($ship_it);
$ship_d_result = db_query($ship_d);
$ship_e_result = db_query($ship_e);
$ship_p_result = db_query($ship_p);
$ship_mp_result = db_query($ship_mp);
$ship_null_result = db_query($ship_null);
$ship_it_result_rows = tep_db_num_rows($ship_it_result);
$ship_d_result_rows = tep_db_num_rows($ship_d_result);
$ship_e_result_rows = tep_db_num_rows($ship_e_result);
$ship_p_result_rows = tep_db_num_rows($ship_p_result);
$ship_mp_result_rows = tep_db_num_rows($ship_mp_result);
$ship_null_result_rows = tep_db_num_rows($ship_null_result);


$tracking_sent_to_shopify_qry = "SELECT orders.orders_id from orders orders left JOIN orders_active_export ON orders.orders_id = orders_active_export.orders_id WHERE orders_active_export.setignore != 1 and `cancel` <> 'Yes' and shopify_order_no <> '' and (tracking_number <> '' or ubox_order_tracking_number<> '') and updated_tracking_shopify_flg = 0 and orders.orders_id > 391425";
$tracking_sent_to_shopify_rs = db_query($tracking_sent_to_shopify_qry);
$tracking_sent_to_shopify = tep_db_num_rows($tracking_sent_to_shopify_rs);

/*Getting Ubox tracking data */

$seldtFedexX = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status = 'DE' OR UOFD.ubox_order_fedex_status = 'SE') AND UOFD.setignore != 1 ORDER BY O.date_purchased");
$seldtFedexM = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status = 'OC') AND UOFD.setignore != 1 ORDER BY O.date_purchased");
$seldtFedexI = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE (UOFD.ubox_order_fedex_status NOT LIKE 'DE' AND UOFD.ubox_order_fedex_status NOT LIKE 'DL' AND UOFD.ubox_order_fedex_status NOT LIKE 'SE' AND UOFD.ubox_order_fedex_status NOT LIKE '' AND UOFD.ubox_order_fedex_status NOT LIKE 'OC') AND UOFD.setignore != 1 ORDER BY O.date_purchased");

$seldtFedexZ = db_query("SELECT UOFD.orders_id, UOFD.ubox_order_tracking_number, UOFD.ubox_order_fedex_status, UOFD.ubox_order_fedex_description, O.date_purchased, O.customers_name FROM ubox_order_fedex_details UOFD INNER JOIN orders O on UOFD.orders_id = O.orders_id WHERE UOFD.ubox_order_fedex_status LIKE '' AND UOFD.setignore != 1 and O.date_purchased < '" . date("Y-m-d") . "' ORDER BY O.date_purchased");
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
$ship_it_result = db_query($ship_it);
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
$ship_it_result = db_query($ship_it);
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
$ship_it_result = db_query($ship_it);
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

    $timestamp = strtotime($considerDay . ' - 1 days');
    if ($timestamp === false) {
        throw new Exception('Invalid date');
    }
    $dayOther = date('Y-m-d H:i:s', $timestamp);

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
$ship_it_result = db_query($ship_it);
while ($report_data = array_shift($ship_it_result)) {
    $cnt_possible_issue = $cnt_possible_issue + 1;

    $possible_issue_order_id_list .= $report_data["id"] . ",";
}

$rowsPossibleIssues = 0;
if (trim($possible_issue_order_id_list) != "") {
    $possible_issue_order_id_list = substr($possible_issue_order_id_list, 0, strlen($possible_issue_order_id_list) - 1);

    $qryPossibleIssues = "SELECT OE.module_name, OE.print_date, OE.warehouse_id, OE.id, OE.orders_id, OE.tracking_number, OE.status, OE.fedex_status, OE.fedex_description, OE.setignore, O.date_purchased, O.customers_name, O.ubox_order_tracking_number, O.ubox_order_carrier_code, O.cancel  FROM orders_active_export OE INNER JOIN orders O on OE.orders_id = O.orders_id WHERE OE.id in ( " . $possible_issue_order_id_list . ") ORDER BY O.date_purchased ";
    //echo $qryPossibleIssues . "<br>";

    $resPossibleIssues = db_query($qryPossibleIssues);
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
                <?php //if ($cnt_possible_issue > 0) { echo "<font color=red>" . $cnt_possible_issue . "</font>"; } else { echo $cnt_possible_issue;} 
                ?>
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
            $yyww2_result = db_query($yyww2);
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
$con_roblem_you_result = db_query($con_roblem_you);
$con_roblem_all_result = db_query($con_roblem_all);
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
            <td bgColor="red" class="style12" style="width: 10%"><a href="contact_status_report_employee.php?ty_id=<?php echo encrypt_url($_COOKIE['userinitials']) ?>">
                    <?php echo $con_roblem_you_result_rows; ?>
                </a></td>
        </tr>
    <?php } ?>
    <?php
    $con2_roblem_all = "SELECT DISTINCT employee FROM ucb_contact WHERE status = 'Attention' AND type_id NOT LIKE '%mb_rdy%' and employee != '" . $_COOKIE['userinitials'] . "'";
    $con2_roblem_all_result = db_query($con2_roblem_all);
    $con2_roblem_all_result_rows = tep_db_num_rows($con2_roblem_all_result);
    while ($con2_roblem_all_result_array = array_shift($con2_roblem_all_result)) {
        // echo $con2_roblem_all_result_array[employee];
        $con3_roblem_you = "SELECT * FROM ucb_contact WHERE employee = '" . $con2_roblem_all_result_array['employee'] . "' AND status = 'Attention' AND type_id NOT LIKE '%mb_rdy%'";
        $con3_roblem_you_result = db_query($con3_roblem_you);
        $con3_roblem_you_result_rows = tep_db_num_rows($con3_roblem_you_result);
    ?>
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style12left" style="width: 10%">
                <?php
                $gfn2 = "SELECT * FROM ucbdb_employees WHERE initials = '" . $con2_roblem_all_result_array['employee'] . "'";
                $gfn2_result = db_query($gfn2);
                while ($gfn2_array = array_shift($gfn2_result)) {
                    echo $gfn2_array['name'];
                }
                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="width: 10%">
                <a href="contact_status_report_employee.php?ty_id=<?php echo encrypt_url($con2_roblem_all_result_array['employee']); ?>">
                    <?php echo $con3_roblem_you_result_rows; ?>
                </a>
            </td>
        </tr>

    <?php } ?>

    <?php if ($con_roblem_all_result_rows > 0) { ?>
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style12left" style="width: 10%">Total Issues:</td>
            <td bgColor="#e4e4e4" class="style12" style="width: 10%"><a href="contact_status_report_employee.php?ty_id=<?php encrypt_url('All'); ?>">
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
$result_pc = db_query($sql_pc);
$count_pc = tep_db_num_rows($result_pc);

$sql_dc
    = "SELECT * FROM ucbdb_credits WHERE pending = 'Denied'  AND total > 0";
$result_dc = db_query($sql_dc);
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
    $wh_query_result = db_query($wh_query);
    $arr_wh_report = [];
    while ($wh_query_array = array_shift($wh_query_result)) {
        $mysql_time = date("Y-m-d") . " " . $wh_query_array['eod_time'];   // Need to add date to the time to make this work
        $mysql_time = strtotime($mysql_time);
        $server_time = time();
        $diff = ($server_time - $mysql_time) / 60;

        $eodsql = "SELECT * FROM ucbdb_endofday where search_date = '" . $today . "' AND warehouse_name = '" . $wh_query_array['distribution_center'] . "'";
        $eodsql_result = db_query($eodsql);
        $eodsql_result_count = tep_db_num_rows($eodsql_result);
        $message = "";
        $link_a = "";
        $bgcolor = "";
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
            <td bgColor="<?php echo $ind_report['bgcolor']; ?>" class="style12" style="width: 10%; height: 22px;"><a href="eod_upload.php?warehouse_name=<?php echo encrypt_url($ind_report['distribution_center']); ?>&link=<?php echo $ind_report['link']; ?>">
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