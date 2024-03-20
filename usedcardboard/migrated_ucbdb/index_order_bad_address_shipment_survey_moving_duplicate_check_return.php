<?php
$problem_all = "SELECT orders_id FROM orders WHERE order_issue = 1";
$problem_all_result = db_query($problem_all);
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
        <td bgColor="#e4e4e4" class="style12" style="width: 10%"><a href="report_active_order_issue.php?onlyactive_orders=yes">
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
$problem_all_result = db_query($problem_all);
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
$result_pc = db_query($sql_pc);
$count_pc = tep_db_num_rows($result_pc);

$currentdate = new DateTime();
$prev_date = $currentdate;

$sql_pc = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 1 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
//echo $sql_pc;
$result_pc = db_query($sql_pc);
$count_pc_tot_for_day = 0;
while ($result_pc_row = array_shift($result_pc)) {
    $count_pc_tot_for_day = $count_pc_tot_for_day + 1;
}

$sql_pc = "SELECT * FROM `orders_active_ucb_hunt_valley` WHERE ship_status LIKE 'N' ";
$result_pc = db_query($sql_pc);
$count_pc2 = tep_db_num_rows($result_pc);
$sql_pc2 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 11 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
//$sql_pc2 = "SELECT * FROM `orders_by_warehouse_eod` where warehouse_name = 'Hunt Valley' and eod_date = '". date("Y-m-d") ."'";
$result_pc2 = db_query($sql_pc2);
$count_pc_tot_for_day2 = 0;
while ($result_pc_row = array_shift($result_pc2)) {
    $count_pc_tot_for_day2 = $count_pc_tot_for_day2 + 1;
}

//$sql_pc = "SELECT * FROM `orders_active_ucb_evansville` WHERE ship_status LIKE 'N' ";
$sql_pc = "SELECT * FROM `orders_active_ucb_hannibal` WHERE ship_status LIKE 'N' ";
$result_pc = db_query($sql_pc);
$count_pc3 = tep_db_num_rows($result_pc);
$sql_pc3 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 12 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
//$sql_pc3 = "SELECT * FROM `orders_by_warehouse_eod` where warehouse_name = 'Hannibal' and eod_date = '". date("Y-m-d") ."'";
$result_pc3 = db_query($sql_pc3);
$count_pc_tot_for_day3 = 0;
while ($result_pc_row = array_shift($result_pc3)) {
    $count_pc_tot_for_day3 = $count_pc_tot_for_day3 + 1;
}

$sql_pc = "SELECT * FROM `orders_active_ucb_salt_lake` WHERE ship_status LIKE 'N' ";
$result_pc = db_query($sql_pc);
$count_pc4 = tep_db_num_rows($result_pc);
$sql_pc4 = "SELECT orders_active_export.orders_id FROM `orders_active_export` WHERE warehouse_id = 3 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
//echo $sql_pc4 . "<br>";
$result_pc4 = db_query($sql_pc4);
$count_pc_tot_for_day4 = 0;
while ($result_pc_row = array_shift($result_pc4)) {
    $count_pc_tot_for_day4 = $count_pc_tot_for_day4 + 1;
}

$sql_pc = "SELECT * FROM `orders_sps` WHERE sent = 0 ";
$result_pc = db_query($sql_pc);
$count_pc5 = tep_db_num_rows($result_pc);
//$sql_pc5 = "SELECT * FROM `orders_sps` inner join orders on orders.orders_id = orders_sps.orders_id WHERE orders.date_purchased BETWEEN '". date("Y-m-d 00:00:00") . "' and '" . date("Y-m-d 23:59:59") . "'";
$sql_pc5 = "SELECT * FROM `orders_active_export` inner join orders_sps on orders_active_export.orders_id = orders_sps.orders_id WHERE sent = 1 and orders_active_export.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'";
//echo $sql_pc5 . "<br>";
$result_pc5 = db_query($sql_pc5);
$count_pc_tot_for_day5 = tep_db_num_rows($result_pc5);

$sql_pc5 = "SELECT * FROM `ubox_order_fedex_details` WHERE ubox_order_fedex_details.print_date BETWEEN '" . $prev_date->format("Y-m-d") . "' and '" . $prev_date->format("Y-m-d") . " 23:59:59" . "'
	and ubox_order_fedex_details.orders_id in (Select orders_id from orders_sps where sent = 1)";
//echo $sql_pc5 . "<br>";
$result_pc5 = db_query($sql_pc5);
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
            <a href="pending_shipments.php?tbl=<?php echo encrypt_url("losangeles");?>&posting=yes" target="_blank">
                <?php echo $count_pc; ?>
            </a>
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="shipments_shipped.php?tbl=<?php echo encrypt_url("losangeles");?>&posting=yes" target="_blank">
                <?php echo $count_pc_tot_for_day; ?>
            </a>
        </td>
    </tr>
    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">Hunt Valley</td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="pending_shipments.php?tbl=<?php echo encrypt_url("huntvally");?>&posting=yes" target="_blank">
                <?php echo $count_pc2; ?>
            </a>
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="shipments_shipped.php?tbl=<?php echo encrypt_url("huntvally");?>&posting=yes" target="_blank">
                <?php echo $count_pc_tot_for_day2; ?>
            </a>
        </td>
    </tr>
    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">Hannibal</td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="pending_shipments.php?tbl=<?php echo encrypt_url("hannibal");?>&posting=yes" target="_blank">
                <?php echo $count_pc3; ?>
            </a>
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="shipments_shipped.php?tbl=<?php echo encrypt_url("hannibal");?>&posting=yes" target="_blank">
                <?php echo $count_pc_tot_for_day3; ?>
            </a>
        </td>
    </tr>
    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">Salt Lake</td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="pending_shipments.php?tbl=<?php echo encrypt_url("saltlake");?>&posting=yes" target="_blank">
                <?php echo $count_pc4; ?>
            </a>
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="shipments_shipped.php?tbl=<?php echo encrypt_url("saltlake");?>&posting=yes" target="_blank">
                <?php echo $count_pc_tot_for_day4; ?>
            </a>
        </td>
    </tr>
    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">New Items</td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="pending_shipments.php?tbl=<?php echo encrypt_url("newitem");?>&posting=yes" target="_blank">
                <?php echo $count_pc5; ?>
            </a>
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="shipments_shipped.php?tbl=<?php echo encrypt_url("newitem");?>&posting=yes" target="_blank">
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
            $result_pc = db_query($sql_pc);
            while ($result_pc_row = array_shift($result_pc)) {
                $arr_warehouse = array('orders_active_ucb_los_angeles', 'orders_active_ucb_philadelphia', 'orders_active_ucb_phoenix', 'orders_active_ucb_hunt_valley', 'orders_active_ucb_hannibal', 'orders_active_ucb_evansville', 'orders_active_ucb_rochester', 'orders_active_ucb_salt_lake', 'orders_active_ucb_atlanta', 'orders_active_ucb_dallas', 'orders_active_ucb_danville', 'orders_active_ucb_iowa', 'orders_active_ucb_montreal', 'orders_active_ucb_toronto');
                $data_found = "no";
                foreach ($arr_warehouse as $tbl_warehouse) {
                    $sql_pc4 = "Select orders_id FROM $tbl_warehouse where orders_id = " . $result_pc_row["orders_id"];
                    $result_pc4 = db_query($sql_pc4);
                    while ($result_pc_row4 = array_shift($result_pc4)) {
                        $data_found = "yes";
                    }
                }

                if ($data_found == "no") {
            ?>
                    <a href="orders.php?id=<?php echo encrypt_url($result_pc_row["orders_id"]); ?>&proc=View&page=0" target="_blank">
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
    $timestamp = strtotime(date('m/d/Y'));

    if ($timestamp === false) {
        // Handle the error, e.g. throw an exception
        throw new Exception('Invalid date');
    }
    $start_date = date('Ymd', $timestamp);
    $end_date = date('Ymd', strtotime('today - 30 days'));

    $hm = "SELECT * FROM survey_nps WHERE date<='$start_date' and date>='$end_date'";
    $hm_result = db_query($hm);
    $surveycount = tep_db_num_rows($hm_result);
    ?>
    <tr vAlign="center">
        <td bgColor="#e4e4e4" class="style12left" style="width: 10%">
            Last 30 Days:
        </td>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="http://b2c.usedcardboardboxes.com/report_survey.php?action=run&start_date=<?php echo $end_date; ?>&end_date=<?php echo $start_date; ?>&surveyview=1">
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
        $noOfNeedToCntct = db_query("SELECT COUNT(id) AS noOfNeedToCntct FROM survey_nps INNER JOIN orders ON orders.orders_id = survey_nps.order_id  WHERE YEAR(survey_nps.date) = '" . $currentYear . "' AND survey_nps.nps <= 7 AND survey_nps.contactok = 'Y' AND orders.survey_res_flag = 0 ");
        ?>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a style=" color: red;" href="survey_resp_contact_log.php?action=showNTC&year=<?php echo $currentYear; ?>" target="_blank">
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
        $noOfResponces = db_query("SELECT COUNT(orders_id) AS noOfResponces FROM orders WHERE orders.survey_res_flag = 1 ");
        ?>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="survey_resp_contact_log.php?action=showResp&year=<?php echo $currentYear; ?>" target="_blank">
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
        $noOfEmailSent = db_query("SELECT COUNT(*) AS noOfEmailSent FROM orders_survey_data_log INNER JOIN orders ON orders_survey_data_log.orders_id = orders.orders_id WHERE YEAR(orders_survey_data_log.survey_sent_on) = '" . $currentYear . "' AND orders.survey = 1");
        ?>
        <td bgColor="#e4e4e4" class="style12" style="width: 10%">
            <a href="survey_resp_contact_log.php?action=showEmailSent&year=<?php echo $currentYear; ?>" target="_blank">
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
    $result = db_query($sql);
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
    $ml_proc_result = db_query($ml_proc);
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
$dupqryres = db_query($dupqry);
$dupqryresnum = tep_db_num_rows($dupqryres);


$dupqrycurcount = "SELECT DISTINCT number FROM orders_active_dupes";
$dupqrycurcountres = db_query($dupqrycurcount);
$dupqrycurcountrescount = 0;
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
        $result33 = db_query($sql33);
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
$returnsres = db_query($returns);
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