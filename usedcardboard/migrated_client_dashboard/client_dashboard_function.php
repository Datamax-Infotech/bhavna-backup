<?php
function viewSellToAdditionalDt(string $title, string $name, string $address, string $address2, string $city, string $state, string $zipcode, string $main_line_ph, string $main_line_ph_ext, string $mainphone, string $cellphone, string $email, string $linked_profile, string $fax, string $opt_out_mkt_sellto_email, string|int $contactSrno): void
{ ?>
    <table width="100%" cellspacing="1" cellpadding="1" border="0" class="mb-10 tableBorder">
        <tbody>
            <tr class="headrow">
                <td colspan="2" align="center">Sell To Contact <?php echo $contactSrno ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Contact Name</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Contact Title</td>
                <td><?php echo $title; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Address 1</td>
                <td><?php echo $address; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Address 2</td>
                <td><?php echo $address2; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>City</td>
                <td><?php echo $city; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>State/Province</td>
                <td><?php echo $state; ?></td>
            </tr>

            <tr class="rowalt1">
                <td>Zip</td>
                <td><?php echo $zipcode; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Main line</td>
                <td> <?php echo $main_line_ph; ?> </td>
            </tr>
            <tr class="rowalt1">
                <td>Main line Ext</td>
                <td><?php echo $main_line_ph_ext; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Direct No</td>
                <td> <?php echo $mainphone; ?> </td>
            </tr>

            <tr class="rowalt1">
                <td>Mobile No</td>
                <td> <?php echo $cellphone; ?> </td>
            </tr>
            <tr class="rowalt2">
                <td>Reply To E-mail </td>
                <td>
                    <?php echo $email ?>
                </td>
            </tr>
            <tr class="rowalt1">
                <td>Fax</td>
                <td><?php echo $fax; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Opt-out Email Marketing</td>
                <td><?php echo ($opt_out_mkt_sellto_email == 0) ? "No" : "Yes"; ?></td>
            </tr>
        </tbody>
    </table>
<?php
}

function viewShipToAdditionalDt(string $title, string $name, string $main_line_ph, string $main_line_ph_ext, string $mainphone, string $cellphone, string $email, string $linked_profile, string $fax, string $opt_out_mkt_shipto_email, string|int $contactSrno): void
{
?>
    <table width="100%" cellspacing="1" cellpadding="1" border="0" class="mb-10 tableBorder">
        <tbody>
            <tr class="headrow">
                <td colspan="2" align="center">Ship To Contact <?php echo $contactSrno; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Name</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Title</td>
                <td><?php echo $title; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Main line</td>
                <td> <?php echo $main_line_ph; ?> </td>
            </tr>
            <tr class="rowalt2">
                <td>Main line Ext</td>
                <td><?php echo $main_line_ph_ext; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Direct No</td>
                <td> <?php echo $mainphone; ?> </td>
            </tr>

            <tr class="rowalt2">
                <td>Mobile No</td>
                <td> <?php echo $cellphone; ?> </td>
            </tr>
            <tr class="rowalt1">
                <td>Email </td>
                <td><?php echo $email ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Fax</td>
                <td><?php echo $fax; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Opt-out Email Marketing </td>
                <td><?php echo ($opt_out_mkt_shipto_email == 0) ? "No" : "Yes"; ?></td>
            </tr>
        </tbody>
    </table>
<?php
}

function viewBillToAdditionalDt(string $title, string $name, string $address, string $address2, string $city, string $state, string $zipcode, string $main_line_ph, string $main_line_ph_ext, string $mainphone, string $cellphone, string $email, string $linked_profile, string $fax, string|int $contactSrno): void
{
?>
    <table width="100%" cellspacing="1" cellpadding="1" border="0" class="mb-10 tableBorder">
        <tbody>
            <tr class="headrow">
                <td colspan="2" align="center">Bill To Contact <?php echo $contactSrno ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Name</td>
                <td><?php echo $name; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Title</td>
                <td><?php echo $title; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>Address 1</td>
                <td><?php echo $address; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Address 2</td>
                <td><?php echo $address2; ?></td>
            </tr>
            <tr class="rowalt1">
                <td>City</td>
                <td><?php echo $city; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>State/Province</td>
                <td><?php echo $state; ?></td>
            </tr>

            <tr class="rowalt1">
                <td>Zip</td>
                <td><?php echo $zipcode; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Main line</td>
                <td> <?php echo $main_line_ph; ?> </td>
            </tr>
            <tr class="rowalt1">
                <td>Main line Ext</td>
                <td><?php echo $main_line_ph_ext; ?></td>
            </tr>
            <tr class="rowalt2">
                <td>Direct No</td>
                <td> <?php echo $mainphone; ?> </td>
            </tr>

            <tr class="rowalt1">
                <td>Mobile No</td>
                <td> <?php echo $cellphone; ?> </td>
            </tr>
            <tr class="rowalt2">
                <td>Email </td>
                <td><?php echo $email ?></td>
            </tr>

            <tr class="rowalt1">
                <td>Fax</td>
                <td><?php echo $fax; ?></td>
            </tr>
        </tbody>
    </table>
    <?php
}
function get_loop_box_id(string $b2b_id): int
{
    $dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
    $dt_res_so = db_query($dt_so);
    $output = 0;
    while ($so_row = array_shift($dt_res_so)) {
        if ($so_row["id"] > 0)
            $output = $so_row["id"];
    }
    return $output;
}

function get_quote_gaylord_count(int $clientdash_flg, int $quoteItem, int $client_companyid): int
{
    if ($clientdash_flg == 1) {
        $getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item= '" . $quoteItem . "' AND companyID = '" . $client_companyid . "' and client_dash_flg=1 ORDER BY quote_gaylord.id ASC";
    } else {
        $getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item= '" . $quoteItem . "' AND companyID = '" . $client_companyid . "' ORDER BY quote_gaylord.id ASC";
    }
    $g_res = db_query($getrecquery);
    //echo "<pre>"; print_r($getrecquery); echo "</pre>";
    $gCount = tep_db_num_rows($g_res);
    return $gCount;
}

function get_quote_shipping_count(int $clientdash_flg, int $quoteItem, int $client_companyid): int
{
    if ($clientdash_flg == 1) {
        $getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' AND quote_request.client_dash_flg=1 ORDER BY quote_shipping_boxes.id ASC";
    } else {
        $getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' ORDER BY quote_shipping_boxes.id ASC";
    }
    $s_res = db_query($getrecquery2);
    $sCount = tep_db_num_rows($s_res);
    //echo "<pre>"; print_r($s_res); echo "</pre>";
    return $sCount;
}
function get_quote_supersacks_count(int $clientdash_flg, int $quoteItem, int $client_companyid): int
{
    if ($clientdash_flg == 1) {
        $getrecquery3 = "SELECT * FROM quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' AND client_dash_flg=1 ORDER BY quote_supersacks.id ASC";
    } else {
        $getrecquery3 = "SELECT * FROM quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' ORDER BY quote_supersacks.id ASC";
    }
    $sup_res = db_query($getrecquery3);
    $supCount = tep_db_num_rows($sup_res);
    //echo "<pre>"; print_r($sup_res); echo "</pre>";

    return $supCount;
}
function get_quote_pallets_count(int $clientdash_flg, int $quoteItem, int $client_companyid): int
{
    if ($clientdash_flg == 1) {
        $getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' AND client_dash_flg=1 ORDER BY quote_pallets.id asc";
    } else {
        $getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id WHERE quote_item=" . $quoteItem . " AND companyID = '" . $client_companyid . "' ORDER BY quote_pallets.id asc";
    }
    $p_res = db_query($getrecquery2);
    $pCount = tep_db_num_rows($p_res);

    return $pCount;
}


function getnickname(string $warehouse_name, string $b2bid): string
{
    $nickname = "";
    if ($b2bid > 0) {
        $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
        db_b2b();
        $result_comp = db_query($sql);
        while ($row_comp = array_shift($result_comp)) {
            if ($row_comp["nickname"] != "") {
                $nickname = $row_comp["nickname"];
            } else {
                $tmppos_1 = strpos($row_comp["company"], "-");
                if ($tmppos_1 != false) {
                    $nickname = $row_comp["company"];
                } else {
                    if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                        $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                    } else {
                        $nickname = $row_comp["company"];
                    }
                }
            }
        }
    } else {
        $nickname = $warehouse_name;
    }

    return $nickname;
}
//To Show the Invocie and Active Status table
function showchild_invnotpaid(string $comp_nm, string $warehouseid): void
{
    global $tot_invoice_cnt;
    global $tot_invoice_amt;
    global $tot_past_due_cnt;
    global $tot_past_due_amt;

    $display_info = "no";
    $total_balance = 0;

    $dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_warehouse.credit_application_net_term as Netterm,loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J, loop_transaction_buyer.no_invoice, loop_transaction_buyer.inv_number AS INVN,loop_transaction_buyer.trans_status, loop_transaction_buyer.invoice_paid, loop_transaction_buyer.rec_type  FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_warehouse.id = " . $warehouseid . " and loop_transaction_buyer.shipped = 1 AND pmt_entered = 0 AND loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
    //echo $dt_view_qry;
    $dt_view_res = db_query($dt_view_qry);
    while ($dt_view_row = array_shift($dt_view_res)) {

        //This is the payment Info for the Customer paying UCB
        $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
        $payment_qry = db_query($payments_sql);
        $payment = array_shift($payment_qry);

        //This is the payment info for UCB paying the related vendors
        $vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
        $vendor_qry = db_query($vendor_sql);
        $vendor = array_shift($vendor_qry);

        //Info about Shipment
        $bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
        $bol_file_res = db_query($bol_file_qry);
        $bol_file_row = array_shift($bol_file_res);

        $fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
        $fbookresult = db_query($fbooksql);
        $freightbooking = array_shift($fbookresult);

        $vendors_paid = 0; //Are the vendors paid
        $vendors_entered = 0; //Has a vendor transaction been entered?
        $invoice_paid = 0; //Have they paid their invoice?
        $invoice_entered = 0; //Has the inovice been entered
        $signed_customer_bol = 0;     //Customer Signed BOL Uploaded
        $courtesy_followup = 0;     //Courtesy Follow Up Made
        $delivered = 0;     //Delivered
        $signed_driver_bol = 0;     //BOL Signed By Driver
        $shipped = 0;     //Shipped
        $bol_received = 0;     //BOL Received @ WH
        $bol_sent = 0;     //BOL Sent to WH"
        $bol_created = 0;     //BOL Created
        $freight_booked = 0; //freight booked
        $sales_order = 0;   // Sales Order entered
        $po_uploaded = 0;  //po uploaded 

        //Are all the vendors paid?
        if ($vendor["B"] == 2 && $vendor["C"] == 2) {
            $vendors_paid = 1;
        }

        //Have we entered a vendor transaction?
        if ($vendor["A"] > 0) {
            $vendors_entered = 1;
        }

        //Have they paid their invoice?
        if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
            $invoice_paid = 1;
        }

        if ($dt_view_row["no_invoice"] == 1) {
            $invoice_paid = 1;
        }
        //if ($dt_view_row["invoice_paid"] == 1) {				$invoice_paid = 1; 			}


        //Has an invoice amount been entered?
        if ($dt_view_row["F"] > 0) {
            $invoice_entered = 1;
        }

        if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
            $signed_customer_bol = 1;
        }    //Customer Signed BOL Uploaded
        if ($bol_file_row["bol_shipment_followup"] > 0) {
            $courtesy_followup = 1;
        }    //Courtesy Follow Up Made
        if ($bol_file_row["bol_shipment_received"] > 0) {
            $delivered = 1;
        }    //Delivered
        if ($bol_file_row["bol_signed_file_name"] != "") {
            $signed_driver_bol = 1;
        }    //BOL Signed By Driver
        if ($bol_file_row["bol_shipped"] > 0) {
            $shipped = 1;
        }    //Shipped
        if ($bol_file_row["bol_received"] > 0) {
            $bol_received = 1;
        }    //BOL Received @ WH
        if ($bol_file_row["bol_sent"] > 0) {
            $bol_sent = 1;
        }    //BOL Sent to WH"
        if ($bol_file_row["id"] > 0) {
            $bol_created = 1;
        }    //BOL Created

        if ($freightbooking["id"] > 0) {
            $freight_booked = 1;
        } //freight booked

        if (($dt_view_row["G"] == 1)) {
            $sales_order = 1;
        } //sales order created
        if ($dt_view_row["H"] != "") {
            $po_uploaded = 1;
        } //po uploaded 

        $boxsource = "";
        $box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
        $box_res = db_query($box_qry);
        $paid_ids = "";
        $p2p_ids = "";
        $not_paid_ids = "";
        while ($box_row = array_shift($box_res)) {
            $boxsource = $box_row["C"];
        }

        if ($invoice_entered == 1 && $invoice_paid == 0) {

            $qry = "SELECT timestamp FROM loop_invoice_details WHERE trans_rec_id = '" . $dt_view_row["I"] . "'";
            $qry_res = db_query($qry);
            $net_row = array_shift($qry_res);
            $invoice_date = date("m/d/Y", strtotime($net_row["timestamp"]));

            $display_info == "yes";
            $dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
            $dt_view_res2 = db_query($dt_view_qry2);
            $dt_view_row2 = array_shift($dt_view_res2);
            //
            $rec_type = $dt_view_row['rec_type'];
            if ($dt_view_row["trans_status"] == 4) {
                $paid_ids .= $dt_view_row["I"] . ",";
                $paid_MGArray[] = array('b2bid' => $_REQUEST["ID"], 'comp_nm' => $comp_nm, 'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered, 'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"], 'inv_date' => $dt_view_row["J"]);
            } else if ($dt_view_row["trans_status"] == 3) {
                $p2p_ids .= $dt_view_row["I"] . ",";
                $p2p_MGArray[] = array('b2bid' => $_REQUEST["ID"], 'comp_nm' => $comp_nm, 'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered, 'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"], 'inv_date' => $dt_view_row["J"]);
            } else {
                $not_paid_ids .= $dt_view_row["I"] . ",";
                $not_paid_MGArray[] = array('b2bid' => $_REQUEST["ID"], 'comp_nm' => $comp_nm, 'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered, 'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"], 'inv_date' => $dt_view_row["J"]);
            }
            //

            $tot_invoice_cnt = $tot_invoice_cnt + 1;
            $tot_invoice_amt = $tot_invoice_amt + str_replace(",", "", $dt_view_row["F"]);
        }    //if not paid
    }    //while loop

    $show_grand_total = "no";
    
    $total_balance_not_paid = 0;
    if (!empty($not_paid_MGArray)) {
        $display_row = "1";
        $tblecnt = 0;
        $rowcolor = 0;
    ?>
        <br>
        <table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0">
            <?php
            foreach ($not_paid_MGArray as $not_paid_array) {
                if ($display_row == "1") {
                    $display_row = "0";
            ?>
                    <tr class="headrow">
                        <td colspan="16" align="center"><?php echo $not_paid_array["comp_nm"]; ?></td>
                    </tr>

                    <tr class="headrow">
                        <td colspan="16" class="blackFont" align="center">INVOICED AND ACTIVE STATUS</td>
                    </tr>
                    <tr>
                        <td class="blackFont" align="center">ID</td>
                        <td class="blackFont" align="center">Invoice Number</td>
                        <td class="blackFont" align="center">Invoice Date</td>
                        <td class="blackFont" align="center">Invoiced Amount</td>
                        <td class="blackFont" align="center">Balance</td>
                        <td class="blackFont" align="center">Invoice Age</td>
                    </tr>

                <?php
                }

                if ($rowcolor % 2 == 0) {
                    $rowclr = 'rowalt2';
                } else {
                    $rowclr = 'rowalt1';
                }
                $rowcolor = $rowcolor + 1;
                ?>
                <tr class="<?php echo $rowclr; ?>">

                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $not_paid_array["rec_id"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $not_paid_array["inv_number"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $not_paid_array["invoice_date"]; ?>
                    </td>


                    <td bgColor="#e4e4e4" class="txt_style12" align="right">
                        $<?php echo number_format($not_paid_array["inv_amt"], 2); ?>
                    </td>
                    <?php

                    $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '" . $not_paid_array["rec_id"] . "'";

                    $dt_view_res3 = db_query($dt_view_qry3);
                    $dt_view_row3 = array_shift($dt_view_res3);
                    $blalnce_col_bg = "txt_style12";
                    if (($not_paid_array["inv_amt"] - $dt_view_row3["PAID"]) < 0) {
                        $blalnce_col_bg  = "txt_style12_bold";
                    }
                    ?>
                    <td bgColor="#e4e4e4" class="<?php echo $blalnce_col_bg; ?>" align="right">
                        $<?php echo number_format(($not_paid_array["inv_amt"] - $dt_view_row3["PAID"]), 2);
                            $total_balance_not_paid += $not_paid_array["inv_amt"] - $dt_view_row3["PAID"];
                            ?>
                    </td>
                    <?php
                    $today = strtotime(date("m/d/Y"));
                    $inv_date = strtotime($not_paid_array["inv_date"]);
                    $diff = ($today - $inv_date) / 60 / 60 / 24;
                    $qry = "SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '" . $not_paid_array["rec_id"] . "'";
                    $qry_res = db_query($qry);
                    $net_row = array_shift($qry_res);

                    //
                    $no_of_net = 0;
                    $duedate_flg = false;
                    if ($net_row["Netterm"] != "") {
                        if ($net_row["Netterm"] == "Prepaid" || $net_row["Netterm"] == "Due On Receipt" || $net_row["Netterm"] == "Other-See Notes") {
                            $no_of_net = 0;
                        }
                        if ($net_row["Netterm"] == "Net 10") {
                            $no_of_net = 10;
                        }
                        if ($net_row["Netterm"] == "Net 15") {
                            $no_of_net = 15;
                        }
                        if ($net_row["Netterm"] == "Net 20") {
                            $no_of_net = 20;
                        }
                        if ($net_row["Netterm"] == "Net 25") {
                            $no_of_net = 25;
                        }
                        if ($net_row["Netterm"] == "Net 30" || $net_row["Netterm"] == "1% 10 Net30" || $net_row["Netterm"] == "1% 15 Net 30") {
                            $no_of_net = 30;
                        }
                        if ($net_row["Netterm"] == "Net 45") {
                            $no_of_net = 45;
                        }
                        if ($net_row["Netterm"] == "Net 60") {
                            $no_of_net = 60;
                        }
                        if ($net_row["Netterm"] == "Net 75") {
                            $no_of_net = 75;
                        }
                        if ($net_row["Netterm"] == "Net 90") {
                            $no_of_net = 90;
                        }
                        if ($net_row["Netterm"] == "Net 120") {
                            $no_of_net = 120;
                        }
                        //
                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            //
                            $next_due_date1 = date('m/d/Y', strtotime('+120 days', strtotime($not_paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                //echo $next_due_date2."--".date("m/d/Y");
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            //
                            $no_of_net = 120;
                        }
                        //

                        if ($net_row["Netterm"] == "Net 30 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+30 days', strtotime($not_paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 30;
                        }

                        if ($net_row["Netterm"] == "Net 45 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+45 days', strtotime($not_paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 45;
                        }

                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            if ($duedate_flg == "false") {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        } else {
                            if ($diff > $no_of_net) {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        }
                        //
                    } else {
                        $no_of_net = 0;
                        $inv_age_color = "#ff0000";
                        $tot_past_due_cnt = $tot_past_due_cnt + 1;
                        $tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
                    }
                    //		
                    $start_t = strtotime($not_paid_array["inv_date"]);
                    $end_time =  strtotime('now');
                    //	
                    //echo "net--".number_format(($end_time-$start_t)/(3600*24),0);
                    //
                    ?>
                    <td bgColor="<?php echo $inv_age_color ?>" class="txt_style12" align="right">
                        <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                    </td>
                </tr>
            <?php
            } //End foreach
            ?>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12">Total:</td>
                <td bgColor="#e4e4e4" class="txt_style12" align="right">$<?php echo number_format($total_balance_not_paid, 2); ?></td>
                <td colspan=2 bgColor="#e4e4e4" class="txt_style12"></td>
            </tr>
        </table>
    <?php
    } //End Not paid arr
    //

    $total_balance_p2p = 0;
    if (!empty($p2p_MGArray)) {
    ?>
        <br>
        <table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0">
            <tr class="headrow">
                <td colspan="16" align="center">INVOICED AND PROMISE TO PAY</td>
            </tr>

            <tr>
                <td class="blackFont" align="center">ID</td>
                <td class="blackFont" align="center">Invoice Number</td>
                <td class="blackFont" align="center">Invoice Date</td>
                <td class="blackFont" align="center">Invoiced Amount</td>
                <td class="blackFont" align="center">Balance</td>
                <td class="blackFont" align="center">Invoice Age</td>
            </tr>
            <?php
            foreach ($p2p_MGArray as $paid_array) {
                $show_grand_total = "yes";
                //}
            ?>
                <tr>

                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["rec_id"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["inv_number"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["invoice_date"]; ?>
                    </td>

                    <td bgColor="#e4e4e4" class="txt_style12" align="right">
                        $<?php echo number_format($paid_array["inv_amt"], 2); ?>
                    </td>
                    <?php

                    $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '" . $paid_array["rec_id"] . "'";

                    $dt_view_res3 = db_query($dt_view_qry3);
                    $dt_view_row3 = array_shift($dt_view_res3);
                    $blalnce_col_bg = "txt_style12";
                    if (($paid_array["inv_amt"] - $dt_view_row3["PAID"]) < 0) {
                        $blalnce_col_bg  = "txt_style12_bold";
                    }
                    ?>
                    <td bgColor="#e4e4e4" class="<?php echo $blalnce_col_bg; ?>" align="right">
                        $<?php echo number_format(($paid_array["inv_amt"] - $dt_view_row3["PAID"]), 2);
                            $total_balance_p2p += $paid_array["inv_amt"] - $dt_view_row3["PAID"];
                            ?>
                    </td>
                    <?php
                    $today = strtotime(date("m/d/Y"));
                    $inv_date = strtotime($paid_array["inv_date"]);
                    $diff = ($today - $inv_date) / 60 / 60 / 24;
                    $qry = "SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '" . $paid_array["rec_id"] . "'";
                    $qry_res = db_query($qry);
                    $net_row = array_shift($qry_res);
                    $no_of_net = 0;
                    if ($net_row["Netterm"] != "") {
                        if ($net_row["Netterm"] == "Prepaid" || $net_row["Netterm"] == "Due On Receipt" || $net_row["Netterm"] == "Other-See Notes") {
                            $no_of_net = 0;
                        }
                        if ($net_row["Netterm"] == "Net 10") {
                            $no_of_net = 10;
                        }
                        if ($net_row["Netterm"] == "Net 15") {
                            $no_of_net = 15;
                        }
                        if ($net_row["Netterm"] == "Net 20") {
                            $no_of_net = 20;
                        }
                        if ($net_row["Netterm"] == "Net 25") {
                            $no_of_net = 25;
                        }
                        if ($net_row["Netterm"] == "Net 30" || $net_row["Netterm"] == "1% 10 Net30" || $net_row["Netterm"] == "1% 15 Net 30") {
                            $no_of_net = 30;
                        }
                        if ($net_row["Netterm"] == "Net 45") {
                            $no_of_net = 45;
                        }
                        if ($net_row["Netterm"] == "Net 60") {
                            $no_of_net = 60;
                        }
                        if ($net_row["Netterm"] == "Net 75") {
                            $no_of_net = 75;
                        }
                        if ($net_row["Netterm"] == "Net 90") {
                            $no_of_net = 90;
                        }
                        if ($net_row["Netterm"] == "Net 120") {
                            $no_of_net = 120;
                        }
                        $duedate_flg = "false";
                        if ($net_row["Netterm"] == "Net 30 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+30 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 30;
                        }

                        if ($net_row["Netterm"] == "Net 45 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+45 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 45;
                        }

                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+120 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                //echo $next_due_date2."--".date("m/d/Y");
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            //
                            $no_of_net = 120;
                        }
                        //
                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            if ($duedate_flg == "false") {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        } else {
                            if ($diff > $no_of_net) {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        }

                        //
                    } else {
                        $no_of_net = 0;
                        $inv_age_color = "#ff0000";
                        $tot_past_due_cnt = $tot_past_due_cnt + 1;
                        $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                    }
                    //		
                    $start_t = strtotime($paid_array["inv_date"]);
                    $end_time =  strtotime('now');
                    ?>
                    <td bgColor="<?php echo $inv_age_color ?>" class="txt_style12" align="right">
                        <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                    </td>
                </tr>
            <?php
            } //End foreach
            ?>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12">Total:</td>
                <td bgColor="#e4e4e4" class="txt_style12" align="right">$<?php echo number_format($total_balance_p2p, 2); ?></td>
                <td colspan=2 bgColor="#e4e4e4" class="txt_style12"></td>
            </tr>
        </table>
    <?php
    } //End P2p Status array

    $total_balance_paid = 0;
    if (!empty($paid_MGArray)) {
    ?>
        <br>
        <table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0">
            <tr class="headrow">
                <td colspan="16" align="center">INVOICED AND PAID STATUS</td>
            </tr>

            <tr>
                <td class="blackFont" align="center">ID</td>
                <td class="blackFont" align="center">Invoice Number</td>
                <td class="blackFont" align="center">Invoice Date</td>
                <td class="blackFont" align="center">Invoiced Amount</td>
                <td class="blackFont" align="center">Balance</td>
                <td class="blackFont" align="center">Invoice Age</td>
            </tr>
            <?php
            $rowColor = 0;
            foreach ($paid_MGArray as $paid_array) {
                $show_grand_total = "yes";
                //}

                if ($rowColor % 2 == 0) {
                    $rowclr = 'rowalt2';
                } else {
                    $rowclr = 'rowalt1';
                }
                $rowColor = $rowColor + 1;
            ?>
                <tr class="<?php echo $rowclr; ?>">

                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["rec_id"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["inv_number"]; ?>
                    </td>
                    <td bgColor="#e4e4e4" class="txt_style12" align="center">
                        <?php echo $paid_array["invoice_date"]; ?>
                    </td>

                    <td bgColor="#e4e4e4" class="txt_style12" align="right">
                        $<?php echo number_format($paid_array["inv_amt"], 2); ?>
                    </td>
                    <?php

                    $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '" . $paid_array["rec_id"] . "'";

                    $dt_view_res3 = db_query($dt_view_qry3);
                    $dt_view_row3 = array_shift($dt_view_res3);
                    $blalnce_col_bg = "txt_style12";
                    if (($paid_array["inv_amt"] - $dt_view_row3["PAID"]) < 0) {
                        $blalnce_col_bg  = "txt_style12_bold";
                    }
                    ?>
                    <td bgColor="#e4e4e4" class="<?php echo $blalnce_col_bg; ?>" align="right">
                        $<?php echo number_format(($paid_array["inv_amt"] - $dt_view_row3["PAID"]), 2);
                            $total_balance_paid += $paid_array["inv_amt"] - $dt_view_row3["PAID"];
                            ?>
                    </td>
                    <?php
                    $today = strtotime(date("m/d/Y"));
                    $inv_date = strtotime($paid_array["inv_date"]);
                    $diff = ($today - $inv_date) / 60 / 60 / 24;
                    $qry = "SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '" . $paid_array["rec_id"] . "'";
                    $qry_res = db_query($qry);
                    $net_row = array_shift($qry_res);
                    $no_of_net = 0;
                    if ($net_row["Netterm"] != "") {
                        if ($net_row["Netterm"] == "Prepaid" || $net_row["Netterm"] == "Due On Receipt" || $net_row["Netterm"] == "Other-See Notes") {
                            $no_of_net = 0;
                        }
                        if ($net_row["Netterm"] == "Net 10") {
                            $no_of_net = 10;
                        }
                        if ($net_row["Netterm"] == "Net 15") {
                            $no_of_net = 15;
                        }
                        if ($net_row["Netterm"] == "Net 20") {
                            $no_of_net = 20;
                        }
                        if ($net_row["Netterm"] == "Net 25") {
                            $no_of_net = 25;
                        }
                        if ($net_row["Netterm"] == "Net 30" || $net_row["Netterm"] == "1% 10 Net30" || $net_row["Netterm"] == "1% 15 Net 30") {
                            $no_of_net = 30;
                        }
                        if ($net_row["Netterm"] == "Net 45") {
                            $no_of_net = 45;
                        }
                        if ($net_row["Netterm"] == "Net 60") {
                            $no_of_net = 60;
                        }
                        if ($net_row["Netterm"] == "Net 75") {
                            $no_of_net = 75;
                        }
                        if ($net_row["Netterm"] == "Net 90") {
                            $no_of_net = 90;
                        }
                        if ($net_row["Netterm"] == "Net 120") {
                            $no_of_net = 120;
                        }
                        $duedate_flg = false;
                        if ($net_row["Netterm"] == "Net 30 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+30 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 30;
                        }

                        if ($net_row["Netterm"] == "Net 45 EOM +1") {
                            $next_due_date1 = date('m/d/Y', strtotime('+45 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            $no_of_net = 45;
                        }

                        //
                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            //
                            $next_due_date1 = date('m/d/Y', strtotime('+120 days', strtotime($paid_array["inv_date"])));
                            $m = date("m", strtotime($next_due_date1));
                            $y = date("Y", strtotime($next_due_date1));
                            $no_days = cal_days_in_month(CAL_GREGORIAN, (int)$m, (int)$y);
                            $eom_date = $m . "/" . $no_days . "/" . $y;
                            $next_due_date2 = date('m/d/Y', strtotime('+1 days', strtotime($eom_date)));
                            $new_due_date = strtotime($next_due_date2);
                            //
                            if ($new_due_date > $today) {
                                //echo $next_due_date2."--".date("m/d/Y");
                                $duedate_flg = "true";
                            } else {
                                $duedate_flg = "false";
                            }
                            //
                            $no_of_net = 120;
                        }
                        //
                        if ($net_row["Netterm"] == "Net 120 EOM +1") {
                            if ($duedate_flg == "false") {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        } else {
                            if ($diff > $no_of_net) {
                                $inv_age_color = "#ff0000";
                                $tot_past_due_cnt = $tot_past_due_cnt + 1;
                                $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                            } else {
                                $inv_age_color = "#e4e4e4";
                            }
                        }

                        //
                    } else {
                        $no_of_net = 0;
                        $inv_age_color = "#ff0000";
                        $tot_past_due_cnt = $tot_past_due_cnt + 1;
                        $tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
                    }
                    //		
                    $start_t = strtotime($paid_array["inv_date"]);
                    $end_time =  strtotime('now');
                    ?>
                    <td bgColor="<?php echo $inv_age_color ?>" class="txt_style12" align="right">
                        <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                    </td>
                </tr>
            <?php
            } //End foreach
            ?>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12">Total:</td>
                <td bgColor="#e4e4e4" class="txt_style12" align="right">$<?php echo number_format($total_balance_paid, 2); ?></td>
                <td colspan=2 bgColor="#e4e4e4" class="txt_style12"></td>
            </tr>
        </table>
    <?php
    } //End paid arr

    $total_balance = $total_balance_paid + $total_balance_not_paid + $total_balance_p2p;

    if ($show_grand_total == "yes") {
    ?>
        <!-- <br>
<table width="427px">	
<tr><td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Grand Total:</strong></td><td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="left" style="padding-left: 15px;"><strong>$<?php echo number_format($total_balance, 2); ?></strong></td></tr>
</table>	
-->
<?php

        $show_grand_total = "no";
    }
} //End function


?>