<?php
if ($proc == "View") {
    $auth_trans_id = "";
    $ubox_order = 0;
    $id = isset($_REQUEST['id']) ? decrypt_url($_REQUEST['id']) : "";
    $sql = "SELECT * FROM orders WHERE orders_id='$id' $addl_select_crit ";
    if ($sql_debug_mode == 1) {
        echo "<BR>SQL: $sql<BR>";
    }
    $result = db_query($sql);
    if ($myrowsel = array_shift($result)) {
        do {
            $id = $myrowsel["orders_id"];
            $tracking_number = $myrowsel["ubox_order_tracking_number"];
            $ubox_order = $myrowsel["ubox_order"];
            $ubox_order_parent_id = $myrowsel["ubox_order_parent_id"];
            $ubox_order_carrier_code = $myrowsel["ubox_order_carrier_code"];

            $orders_id = $myrowsel["orders_id"];
            $shopify_orders_id = $myrowsel["shopify_order_display_no"];
            $shopify_orders_no = $myrowsel["shopify_order_no"];
            $coupon_id = $myrowsel["coupon_id"];
            $customers_id = $myrowsel["customers_id"];
            $customers_name = $myrowsel["customers_name"];
            $customers_company = $myrowsel["customers_company"];
            $customers_street_address = $myrowsel["customers_street_address"];
            $customers_street_address2 = $myrowsel["customers_street_address2"];
            $customers_suburb = $myrowsel["customers_suburb"];
            $customers_city = $myrowsel["customers_city"];
            $customers_postcode = $myrowsel["customers_postcode"];
            $customers_state = $myrowsel["customers_state"];
            $customers_country = $myrowsel["customers_country"];
            $customers_telephone = $myrowsel["customers_telephone"];
            $customers_email_address = $myrowsel["customers_email_address"];
            $customers_address_format_id = $myrowsel["customers_address_format_id"];
            $is_pickup_call = $myrowsel["is_pickup_call"];
            $delivery_name = $myrowsel["delivery_name"];
            $delivery_company = $myrowsel["delivery_company"];
            $delivery_apartment_no = $myrowsel["delivery_apartment_no"];
            $delivery_street_address = $myrowsel["delivery_street_address"];
            $delivery_street_address2 = $myrowsel["delivery_street_address2"];
            $delivery_suburb = $myrowsel["delivery_suburb"];
            $delivery_city = $myrowsel["delivery_city"];
            $delivery_postcode = $myrowsel["delivery_postcode"];
            $delivery_state = $myrowsel["delivery_state"];
            $delivery_country = $myrowsel["delivery_country"];
            $delivery_address_format_id = $myrowsel["delivery_address_format_id"];
            $billing_name = $myrowsel["billing_name"];
            $billing_company = $myrowsel["billing_company"];
            $billing_street_address = $myrowsel["billing_street_address"];
            $billing_suburb = $myrowsel["billing_suburb"];
            $billing_city = $myrowsel["billing_city"];
            $billing_postcode = $myrowsel["billing_postcode"];
            $billing_state = $myrowsel["billing_state"];
            $billing_country = $myrowsel["billing_country"];
            $billing_address_format_id = $myrowsel["billing_address_format_id"];
            $name_of_employee_helped = $myrowsel["name_of_employee_helped"];
            $ups_signature = $myrowsel["ups_signature"];
            $bad_address = $myrowsel["fedex_validate_bad_add"];
            $fedex_search_resp_state = $myrowsel["fedex_search_resp_state"];

            $fedex_search_resp_state = $myrowsel["fedex_search_resp_state"];
            $fedex_search_resp_classification = $myrowsel["fedex_search_resp_classification"];
            $fedex_search_resp_add1 = $myrowsel["fedex_search_resp_add1"];
            $fedex_search_resp_add2 = $myrowsel["fedex_search_resp_add2"];
            $fedex_search_resp_statecode = $myrowsel["fedex_search_resp_statecode"];
            $fedex_search_resp_city = $myrowsel["fedex_search_resp_city"];
            $fedex_search_resp_zip = $myrowsel["fedex_search_resp_zip"];

            $how_to_hear_about = $myrowsel["how_to_hear_about"];
            $user_ipaddress = $myrowsel["user_ipaddress"];
            $payment_method = $myrowsel["payment_method"];
            $survey = $myrowsel['survey'];

            if ($myrowsel["data_encrypt"] == 0) {
                $cc_type = $myrowsel["cc_type"];
                $cc_owner = $myrowsel["cc_owner"];
                $cc_number = $myrowsel["cc_number"];
                $cc_expires = $myrowsel["cc_expires"];
            } else {
                $cc_type = "";
                $cc_owner = "";
                $cc_expires = "";
                $cc_number = "";
                if ($myrowsel['cc_type'] <> "") {
                    $cc_type = decryptstr($myrowsel['cc_type']);
                }
                if ($myrowsel['cc_owner'] <> "") {
                    $cc_owner = decryptstr($myrowsel['cc_owner']);
                }
                if ($myrowsel['cc_number'] <> "") {
                    if (strlen($myrowsel['cc_number']) == 4) {
                        $cc_number = $myrowsel['cc_number'];
                    } else {
                        $cc_number = decryptstr($myrowsel['cc_number']);
                    }
                }
                if ($myrowsel['cc_expires'] <> "") {
                    $cc_expires = decryptstr($myrowsel['cc_expires']);
                }
            }
            $comment = $myrowsel["comment"];
            $last_modified = $myrowsel["last_modified"];
            $date_purchased = $myrowsel["date_purchased"];
            $orders_status = $myrowsel["orders_status"];
            $orders_date_finished = $myrowsel["orders_date_finished"];
            $currency = $myrowsel["currency"];
            $currency_value = $myrowsel["currency_value"];
            $billing_street_address2 = $myrowsel["billing_street_address2"];
            $cancel = $myrowsel["cancel"];
            $cancel_order_by = $myrowsel["cancel_order_by"];
            $cancel_order_on = $myrowsel["cancel_order_on"];
            $site_referrer = $myrowsel["site_referrer"];
            $site_ref_keyword = $myrowsel["site_ref_keyword"];
            $site_hits_id = $myrowsel["site_hits_id"];
        } while ($myrowsel = array_shift($result));
        echo ""; ?>

        <br>
        <table>
            <tr>
                <td valign="top">

                    <div id="display_order_shipping">
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="2">
                                    <span class="style1">CUSTOMER</span>
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        SHIPPING DETAILS&nbsp;<a href='#' onclick="editshipping(<?php echo encrypt_url($orders_id); ?>)">Edit</a>
                                    </font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Order #</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $orders_id; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Shopify Order #</td>
                                <td align="left" height="13" style="width: 235px" class="style1">
                                    <a target="_blank" href="https://usedcardboardboxes.myshopify.com/admin/orders/<?php echo $shopify_orders_no; ?>?orderListBeta=true"><?php echo $shopify_orders_id; ?></a>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Order Date</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php $order_date = date("l, jS F Y", strtotime($date_purchased));
                                                                                                    echo $order_date; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td style="width: 100px; height: 13px;" class="style1">Order Time</td>
                                <td align="left" style="width: 235px; height: 13px;" class="style1"><?php $order_time = date("g:i:s a", strtotime($date_purchased));
                                                                                                    echo $order_time; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Name</font>
                                </td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $delivery_name; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Company Name</font>
                                </td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $delivery_company; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Address</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $delivery_street_address; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 100px" class="style1">Address 2</td>
                                <td align="left" height="10" style="width: 235px" class="style1"><?php echo $delivery_street_address2; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">City</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $delivery_city; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 100px" class="style1">State</td>
                                <td align="left" height="10" style="width: 235px" class="style1"><?php echo $delivery_state; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Zip</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $delivery_postcode; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="19" style="width: 100px" class="style1">Phone</td>
                                <td align="left" height="19" style="width: 235px" class="style1">
                                    <?php if ($customers_telephone != "") {
                                        echo "<img src='images/phone_img.png' width='12px' height='12px'>&nbsp; <a href='tel:$customers_telephone'>" . $customers_telephone . "</a>";
                                    } ?>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">E-mail</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><a href="mailto:<?php echo $customers_email_address; ?>"><?php echo $customers_email_address; ?></a></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Bad Address</font>
                                </td>
                                <?php if ($bad_address == 1) { ?>
                                    <td align="left" height="13" style="width: 235px; color: red;" class="style1">Yes

                                        <form name="frm_bad_add_igr" id="frm_bad_add_igr" action="badadd_ignore_update.php">
                                            <input type="submit" name="btn_bad_add_igr" id="btn_bad_add_igr" value="Ignore Bad Address Notification" />
                                            <input type="hidden" name="bad_add_orders_id" id="bad_add_orders_id" value="<?php echo $orders_id; ?>" />
                                        </form>
                                    </td>
                                <?php  } else { ?>
                                    <td align="left" height="13" style="width: 235px" class="style1">No</td>
                                <?php  } ?>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Residential</td>
                                <td align="left" height="13" style="width: 235px" class="style1">Yes</td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Shipper Release</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $ups_signature; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td style="width: 100px; height: 13px;" class="style1">Employee Help</td>
                                <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $name_of_employee_helped; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">UPS Notes</font>
                                </td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $comment; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">How Hear</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $how_to_hear_about; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">Ip Address</td>
                                <td align="left" height="13" style="width: 235px" class="style1"><?php echo $user_ipaddress; ?></td>
                            </tr>
                        </table>
                    </div>

                    <?php if ($fedex_search_resp_state != "") { ?>
                        <form name="frmfedex" id="frmfedex" action="badadd_copy_data.php">
                            <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                                <tr align="middle">
                                    <td bgColor="#c0cdda" colSpan="2">
                                        <span class="style1">FedEx Response</span></font>
                                    </td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" style="width: 100px" class="style1">Fedex search resp state</td>
                                    <td align="left" height="13" style="width: 235px" class="style1"><?php echo $fedex_search_resp_state; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" style="width: 100px" class="style1">Fedex search resp classification</td>
                                    <td align="left" height="13" style="width: 235px" class="style1"><?php echo $fedex_search_resp_classification; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 100px; height: 13px;" class="style1">Fedex resp add1</td>
                                    <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $fedex_search_resp_add1; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 100px; height: 13px;" class="style1">Fedex resp add2</td>
                                    <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $fedex_search_resp_add2; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 100px; height: 13px;" class="style1">Fedex resp city</td>
                                    <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $fedex_search_resp_city; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 100px; height: 13px;" class="style1">Fedex resp state</td>
                                    <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $fedex_search_resp_statecode; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 100px; height: 13px;" class="style1">Fedex resp zipcode</td>
                                    <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $fedex_search_resp_zip; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td align="left" style="height: 13px;" class="style1" colspan="2">
                                        <input type="button" name="btnsend_badadd_eml" id="btnsend_badadd_eml" value="Send Email to Customer" onclick="fun_badadd_send_email(<?php echo encrypt_url($orders_id); ?>)" />
                                        <input type="submit" name="btn_badadd_copy" id="btn_badadd_copy" value="FedEx Add -> Customer Shipping Address" />
                                        <input type="hidden" name="orders_id" id="orders_id" value="<?php echo $orders_id; ?>" />
                                    </td>
                                </tr>
                            </table>
                        </form>

                        <div id="fed_add_old">
                            <?php
                            $rec_found_add_fedex = "no";
                            $sql_1 = "SELECT orders_id FROM b2c_order_address_fedex where orders_id = " . $orders_id;
                            $rec_1 = db_query($sql_1);
                            while ($rec_row = array_shift($rec_1)) {
                                $rec_found_add_fedex = "yes";
                            }

                            if ($rec_found_add_fedex == "yes") {
                            ?>
                                <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                                    <tr align="middle">
                                        <td bgColor="#c0cdda" colSpan="2">
                                            <span class="style1">Old Address Details</span></font>
                                        </td>
                                    </tr>
                                    <?php
                                    $sql_1 = "SELECT * FROM b2c_order_address_fedex where orders_id = " . $orders_id;
                                    $rec_1 = db_query($sql_1);
                                    while ($rec_row = array_shift($rec_1)) {
                                    ?>

                                        <tr bgColor="#e4e4e4">
                                            <td style="width: 100px; height: 13px;" class="style1">Address</td>
                                            <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $rec_row["address1"]; ?></td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td style="width: 100px; height: 13px;" class="style1">Address2</td>
                                            <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $rec_row["address2"]; ?></td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td style="width: 100px; height: 13px;" class="style1">City</td>
                                            <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $rec_row["city"]; ?></td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td style="width: 100px; height: 13px;" class="style1">State</td>
                                            <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $rec_row["state"]; ?></td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td style="width: 100px; height: 13px;" class="style1">Zipcode</td>
                                            <td align="left" style="width: 235px; height: 13px;" class="style1"><?php echo $rec_row["zipcode"]; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                        </div>
                <?php
                            }
                        } ?>

                </td>
                <td> </td>
                <td valign="top">
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                        <tr align="middle">
                            <td bgColor="#c0cdda" colSpan="2" style="height: 22px">
                                <span class="style1">CUSTOMER</span>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                    BILLING DETAILS</font>
                            </td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 127px" class="style1">Name</td>
                            <td align="left" height="13" class="style1"><?php echo $billing_name; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 127px" class="style1">Address</td>
                            <td align="left" height="13" class="style1"><?php echo $billing_street_address; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="10" style="width: 127px" class="style1">Address 2</td>
                            <td align="left" height="10" class="style1"><?php echo $billing_street_address2; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 127px" class="style1">City</td>
                            <td align="left" height="13" class="style1"><?php echo $billing_city; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="10" style="width: 127px" class="style1">State</td>
                            <td align="left" height="10" class="style1"><?php echo $billing_state; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 127px" class="style1">Zip</td>
                            <td align="left" height="13" class="style1"><?php echo $billing_postcode; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="19" style="width: 127px" class="style1">Phone</td>
                            <td align="left" height="19" class="style1">
                                <?php if ($customers_telephone != "") {
                                    echo "<img src='images/phone_img.png' width='12px' height='12px'>&nbsp; <a href='tel:$customers_telephone'>" . $customers_telephone . "</a>";
                                } ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <?php if ($payment_method == 'Paypal') { ?>
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="2">
                                    <span class="style1">CUSTOMER</span>
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        PAYMENT INFO</font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Payment type</td>
                                <td align="left" height="13" style="width: 177px" class="style1">PayPal</td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Paypal Transaction ID</td>
                                <?php
                                $auth_code_query = db_query("select * from auth_net where type IS NULL AND orders_id = '" . (int)$orders_id . "'");
                                while ($auth_code_res = array_shift($auth_code_query)) {
                                ?>
                                    <td align="left" height="10" style="width: 177px" class="style1"><?php if ($auth_code_res["trans_id"] != '') {
                                                                                                            echo $auth_code_res["trans_id"];
                                                                                                            $auth_trans_id = $auth_code_res["trans_id"];
                                                                                                        } else {
                                                                                                            echo "No Id Available";
                                                                                                        }  ?></td>
                                <?php  } ?>
                            </tr>
                        </table>
                    <?php  } else { ?>
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="2">
                                    <span class="style1">CUSTOMER</span>
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        PAYMENT INFO</font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Name</td>
                                <td align="left" height="13" style="width: 177px" class="style1"><?php echo $cc_owner; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Credit Card #</td>
                                <td align="left" height="13" style="width: 177px" class="style1"><?php echo substr($cc_number, -4, 4); ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 95px" class="style1">Expiration</td>
                                <td align="left" height="10" style="width: 177px" class="style1"><?php echo substr($cc_expires, 0, 2); ?> / <?php echo substr($cc_expires, 2, 4); ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 95px" class="style1">Authorize.Net ID</td>
                                <?php
                                $auth_code_query = db_query("select * from auth_net where type IS NULL AND orders_id = '" . (int)$orders_id . "'");
                                while ($auth_code_res = array_shift($auth_code_query)) {
                                ?>
                                    <td align="left" height="10" style="width: 177px" class="style1"><?php if ($auth_code_res["trans_id"] != '') {
                                                                                                            echo $auth_code_res["trans_id"];
                                                                                                            $auth_trans_id = $auth_code_res["trans_id"];
                                                                                                        } else {
                                                                                                            echo "No Id Available";
                                                                                                        }  ?></td>
                                <?php  } ?>
                            </tr>
                        </table>

                    <?php  } ?>

                    <!-- Customer survey Response start -->
                    <br>

                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                        <tr align="middle">
                            <td bgColor="#c0cdda" colSpan="2" style="height: 22px">
                                <span class="style1">CUSTOMER</span>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                    SURVEY RESPONSE </font>
                            </td>
                        </tr>
                        <?php if ($survey == 0) { ?>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 127px" class="style1">Supposed to be sent</td>
                                <td align="left" height="13" class="style1">
                                    <?php
                                    echo date('m/d/Y', strtotime('+8 day', strtotime($date_purchased)));
                                    ?>

                                </td>
                            </tr>
                        <?php  } else {
                            $selSrvey = "SELECT survey_nps.nps, survey_nps.recommendation, survey_nps.contactok FROM survey_nps WHERE order_id = " . $orders_id;
                            $selSrveyArr = db_query($selSrvey);
                            $recommendation = "";
                            $contactok = "";
                            $nps = "";
                            $surveySentOn = "";
                            while ($srveyRow = array_shift($selSrveyArr)) {

                                $surveySentOn     = "";
                                $selSrvey1 = "SELECT * FROM orders_survey_data_log WHERE orders_id = " . $orders_id;
                                $selSrveyArr1 = db_query($selSrvey1);
                                while ($srveyRow1 = array_shift($selSrveyArr1)) {
                                    $surveySentOn     = !empty($srveyRow1['survey_sent_on']) ? date("m/d/Y", strtotime($srveyRow1['survey_sent_on'])) : '';
                                }

                                $nps             = !empty($srveyRow['nps']) ? $srveyRow['nps'] : '';
                                $recommendation = !empty($srveyRow['recommendation']) ? $srveyRow['recommendation'] : '';
                                if ($srveyRow['contactok'] == 'Y') {
                                    $contactok         = 'Yes';
                                } else {
                                    $contactok         = 'No';
                                }
                            }

                        ?>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 127px" class="style1">Survey Sent On</td>
                                <td align="left" height="13" class="style1"><?php echo $surveySentOn; ?></td>
                            </tr>

                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 127px" class="style1">Response</td>
                                <td align="left" height="10" class="style1"><?php echo $nps; ?></td>
                            </tr>
                            <?php if ($nps == 10) { ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="10" style="width: 127px" class="style1">Recommendation</td>
                                    <td align="left" height="10" class="style1"><?php echo $recommendation; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td height="10" style="width: 127px" class="style1">May we use your survey response in our marketing efforts</td>
                                    <td align="left" height="10" class="style1"><?php echo $contactok; ?></td>
                                </tr>
                            <?php  } else { ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="10" style="width: 127px" class="style1">Feedback</td>
                                    <td align="left" height="10" class="style1"><?php echo $recommendation; ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td height="10" style="width: 127px" class="style1">May we contact you in regards to your survey?</td>
                                    <td align="left" height="10" class="style1"><?php echo $contactok; ?></td>
                                </tr>
                            <?php  } ?>
                        <?php  } ?>
                    </table>
                    <!-- Customer survey Response ends -->

                </td>
                <td style="width: 10px"> </td>
                <td valign="top">

                    <?php if ($cancel == "No") { ?>
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="3">
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        ORDER INFO</font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">

                                <td style="width: 310px; height: 13px;" class="style1">Quantity</td>
                                <td style="width: 1484px; height: 13px;" class="style1">Item</td>
                                <td style="width: 127px; height: 13px;" class="style1" align="right">Cost</td>
                            </tr>

                            <?php

                            $orders_products_query = db_query("select orders_products_id, products_id, orders_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from orders_products where orders_id = '" . (int)$orders_id . "'");
                            while ($orders_products = array_shift($orders_products_query)) {

                                $shopify_product_nm = "";
                                $orders_products_query1 = db_query("select * from products_shopify where ucb_products_id = '" . $orders_products["products_id"] . "'");
                                while ($orders_products1 = array_shift($orders_products_query1)) {
                                    $shopify_product_nm = $orders_products1["product_description"];
                                }
                                if ($shopify_product_nm == "") {
                                    $shopify_product_nm = $orders_products['products_name'];
                                }
                            ?>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1"><?php echo $orders_products['products_quantity']; ?></td>
                                    <td style="width: 1484px; height: 13px;" class="style1"><?php echo $shopify_product_nm; ?></td>
                                    <td style="width: 127px; height: 13px;" class="style1" align="right">$<?php echo number_format($orders_products['products_price'], 2, '.', ''); ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1" colspan="3">&nbsp;</td>
                                </tr>
                            <?php
                            }
                            ?>

                            <?php
                            if ($cancel == "No") {
                                $tax_amount = 0;
                                $tot_amount = 0;
                                $totals_query = db_query("select title, text, value, class from orders_total where orders_id = '" . (int)$orders_id . "' order by sort_order");
                                while ($totals = array_shift($totals_query)) {

                                    $title_txt = $totals["title"];
                                    if ($totals["class"] == "ot_tax") {
                                        $tax_amount = $totals["value"];
                                    }
                                    if ($totals["class"] == "ot_total") {
                                        $tot_amount = $totals["value"];
                                        $title_txt = "Customer Total";
                                    }

                            ?>
                                    <tr bgColor="#e4e4e4">
                                        <td style="width: 310px; height: 13px;" class="style1"></td>
                                        <td style="width: 310px; height: 13px;" class="style1"><?php echo $title_txt; ?></td>
                                        <td style="width: 310px; height: 13px;" class="style1" colspan="2" align="right"><?php echo $totals["text"]; ?></td>
                                    </tr>

                                <?php
                                }

                                $tot_amount = $tot_amount - $tax_amount;
                                ?>

                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" colspan="3">&nbsp;</td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1"></td>
                                    <td style="width: 310px; height: 13px;" class="style1">Revenue:</td>
                                    <td style="width: 310px; height: 13px;" class="style1" colspan="2" align="right">$<?php echo $tot_amount; ?></td>
                                </tr>

                            <?php
                            }
                            ?>
                            <tr bgColor="#e4e4e4">
                                <td colspan="3" class="style1">
                                    <form action="send_orders.php" method="post">
                                        <input type="hidden" name="ordersid" value="<?php echo encrypt_url($orders_id); ?>">
                                        <input type="submit" value="Send Order to Ubox">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    <?php  } ?>
                    <br>
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                        <tr align="middle">
                            <td bgColor="#c0cdda" colSpan="2">
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                    OTHER INFO
                                </font>
                            </td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 95px" class="style1">Referrer
                            </td>
                            <td align="left" height="13" style="width: 177px" class="style1"><?php echo $site_referrer; ?></td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td height="13" style="width: 95px" class="style1">Keyword</td>
                            <td align="left" height="13" style="width: 177px" class="style1"><?php echo $site_ref_keyword; ?></td>
                        </tr>
                    </table>
                    <br>
                    <?php
                    if ($ubox_order > 0) {
                        //$token = "ob6hpkx9e4iduergynnyki1wh7xbbme1";
                        $token = "uumpondjxakyfpl9t0p7kqox8jjh95hp";
                        //$ubox_order = "390101101";
                        //$ch = curl_init("https://www.uboxes.com/rest/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");
                        $ch = curl_init("https://www.uboxes.com/rest/wholesale/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=" . $ubox_order . "&searchCriteria[filter_groups][0][filters][0][condition_type]=eq");

                        // Returns the data/output as a string instead of raw data
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        //Set your auth headers
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ));
                        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

                        // get stringified data/output. See CURLOPT_RETURNTRANSFER
                        $data = curl_exec($ch);

                        // close curl resource to free up system resources
                        //print_r($data);

                        $jsonData = json_decode($data, true);

                        //var_dump($jsonData);

                        $main_array = $jsonData['items'][0];
                        $main_array2 = $main_array['extension_attributes']['shipping_assignments'][0]['shipping']['address'];

                        //var_dump($main_array);
                        //$shipping_add = $main_array['extension_attributes']['shipping_assignments'][0]['shipping']['address']['street'][0];

                        $shipping_add = $main_array2['firstname'] . " " . $main_array2['lastname'] . "<br>" . $main_array2['street'][0] . "<br>" . $main_array2['city'] . "," . $main_array2['region'] . " " . $main_array2['postcode'] . "<br>" . $main_array2['telephone'];

                        $items_array = $main_array['extension_attributes']['shipping_assignments'][0]['items'];
                        $items_data = "";
                        foreach ($items_array as $items_array_data) {
                            $items_data .= $items_array_data['name'] . "<br>SKU: " . $items_array_data['sku'] . "<br>Price: " . $items_array_data['base_row_total'] . "<br>Qty Ordered:" . $items_array_data['qty_ordered'] . "<br><br>";
                        }

                        curl_close($ch);
                    ?>

                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="2">
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        UBOX INFO
                                    </font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Shipping Address
                                </td>
                                <td align="left" height="13" style="width: 177px" class="style1"><?php echo $shipping_add; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Item Details</td>
                                <td align="left" height="13" style="width: 177px" class="style1"><?php echo $items_data; ?></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Ubox Order #</td>
                                <td align="left" height="13" style="width: 177px" class="style1">
                                    <?php if ($ubox_order_parent_id != "") { ?>
                                        <a target="_blank" href="https://wholesale.boxengine.com/sales/order/view/order_id/<?php echo $ubox_order_parent_id; ?>/">
                                            <?php echo $ubox_order; ?></a>
                                    <?php  } else { ?>
                                        <?php echo $ubox_order; ?>
                                    <?php  } ?>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 95px" class="style1">Tracking Number</td>
                                <td align="left" height="13" style="width: 177px" class="style1">
                                    <?php
                                    $tracking_no_arr = explode(",", $tracking_number);
                                    $ubox_order_carrier_code_arr = explode(",", $ubox_order_carrier_code);

                                    $tracking_no_cnt = count($tracking_no_arr);
                                    $fexex_str = "";
                                    if ($tracking_no_cnt > 0) {
                                        for ($cnt = 0; $cnt <= $tracking_no_cnt; $cnt++) {
                                            if ($ubox_order_carrier_code_arr[$cnt] == "fedex") {
                                                $fexex_str = "https://www.fedex.com/fedextrack/summary?trknbr=";
                                            }
                                            if ($ubox_order_carrier_code_arr[$cnt] == "usps") {
                                                $fexex_str = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=";
                                            }
                                            if ($ubox_order_carrier_code_arr[$cnt] == "ups") {
                                                $fexex_str = "https://www.ups.com/track?loc=en_US&tracknum=";
                                            }

                                    ?>
                                            <a target="_blank" href="<?php echo $fexex_str . $tracking_number ?>">
                                                <?php echo $tracking_no_arr[$cnt]; ?></a>
                                        <?php
                                        }
                                    } else {
                                        if ($ubox_order_carrier_code_arr == "fedex") {
                                            $fexex_str = "https://www.fedex.com/fedextrack/summary?trknbr=";
                                        }
                                        if ($ubox_order_carrier_code_arr == "usps") {
                                            $fexex_str = "https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=";
                                        }
                                        if ($ubox_order_carrier_code_arr == "ups") {
                                            $fexex_str = "https://www.ups.com/track?loc=en_US&tracknum=";
                                        }

                                        if (strpos($tracking_number, ",") > 0) {
                                            $tracking_number = substr($tracking_number, 0, strlen($tracking_number) - 1);
                                        }
                                        ?>
                                        <a target="_blank" href="<?php echo $fexex_str . $tracking_number ?>">
                                            <?php echo $tracking_number; ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    <?php  } ?>
                </td>
                <td></td>
                <td valign="top">
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 250px">
                        <tr align="middle">
                            <td bgColor="#c0cdda" colSpan="2">
                                <span class="style1">QUICK LINKS</span>
                            </td>
                        </tr>
                        <?php if ($cancel == "Yes") { ?>
                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 189px" class="style1">Cancel Order (incl. labels)</td>
                                <td align="left" height="10" class="style1" style="width: 189px">Order Cancel by <?php echo $cancel_order_by; ?> on <?php echo $cancel_order_on; ?></td>
                            </tr>
                        <?php  } else { ?>

                            <tr bgColor="#e4e4e4">
                                <td height="10" style="width: 189px" class="style1">Cancel Order (incl. labels)</td>
                                <td align="left" height="10" class="style1" style="width: 189px"><a href="cancel_order.php?orders_id=<?php echo $orders_id; ?>">
                                        Process</a></td>
                            </tr>
                        <?php  } ?>
                    </table>
                    <br>
                    <?php if ($cancel == "Yes") { ?>
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="3">
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        CANCEL ORDER INFO
                                    </font>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td style="width: 310px; height: 13px;" class="style1">Quantity</td>
                                <td style="width: 1484px; height: 13px;" class="style1">Item</td>
                                <td style="width: 127px; height: 13px;" class="style1" align="right">Cost</td>
                            </tr>

                            <?php

                            $orders_products_query = db_query("select orders_products_id, orders_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from orders_products where orders_id = '" . (int)$orders_id . "'");
                            while ($orders_products = array_shift($orders_products_query)) {

                            ?>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1"><?php echo $orders_products['products_quantity']; ?></td>
                                    <td style="width: 1484px; height: 13px;" class="style1"><?php echo $orders_products['products_name']; ?></td>
                                    <td style="width: 127px; height: 13px;" class="style1" align="right">$<?php echo number_format($orders_products['products_price'], 2, '.', ''); ?></td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1" colspan="3">&nbsp;</td>
                                </tr>
                            <?php
                            }

                            $totals_query = db_query("select title, text, value, class from orders_total_cancel_order where orders_id = '" . (int)$orders_id . "' order by sort_order");
                            while ($totals = array_shift($totals_query)) {
                            ?>

                                <tr bgColor="#e4e4e4">
                                    <td style="width: 310px; height: 13px;" class="style1"></td>
                                    <td style="width: 310px; height: 13px;" class="style1"><?php echo $totals["title"]; ?></td>
                                    <td style="width: 310px; height: 13px;" class="style1" colspan="2" align="right"><?php echo $totals["text"]; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr bgColor="#e4e4e4">
                                <td colspan="3" class="style1">
                                    <form action="send_orders.php" method="post">

                                        <input type="hidden" name="ordersid" value="<?php echo encrypt_url($orders_id); ?>">

                                        <input type="submit" value="Send Order to Ubox">
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <br>
                    <?php  } ?>

                    <form action="addstatus.php" method="post">
                        <table cellSpacing="1" cellPadding="1" border="0" width="250">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="2">
                                    <span class="style1">Order Issues</span>
                                </td>
                            </tr>
                            <?php
                            $order_issue = 0;
                            $sqlissue = "SELECT order_issue FROM orders WHERE orders_id = " . $orders_id;
                            $resissue = db_query($sqlissue);
                            while ($myresissue = array_shift($resissue)) {
                                $order_issue = $myresissue["order_issue"];
                            }

                            if ($order_issue == 1) {
                            ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1">
                                        Estimated Cost of Resolution:</td>
                                    <td align="left" height="13" style="width: 197px" class="style1">
                                        <input name="txt_stimated_cost" id="txt_stimated_cost" />
                                    </td>
                                </tr>

                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1">
                                        Reason:</td>
                                    <td align="left" height="13" style="width: 197px" class="style1">
                                        <select name="orderissue_reason" id="orderissue_reason" />
                                        <option value="">Please Select</option>
                                        <?php
                                        db();

                                        $qry = "SELECT * FROM loop_order_issue_reason order by reason_name";
                                        $qry_res = db_query($qry);
                                        while ($data_row = array_shift($qry_res)) {
                                        ?>
                                            <option value="<?php echo $data_row["reason_id"]; ?>"><?php echo $data_row["reason_name"]; ?></option>
                                        <?php
                                        }
                                        ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1">
                                        Describe the Resolution in Detail:</td>
                                    <td align="left" height="13" style="width: 197px" class="style1">
                                        <textarea name="orderproblem_text" id="orderproblem_text"></textarea>
                                    </td>

                                </tr>

                                <tr bgColor="#e4e4e4">
                                    <td height="10" colspan="2" style="width: 189px" class="style1">
                                        <input type="submit" value="UnMark as Order Issue">
                                    </td>

                                </tr>
                            <?php  } ?>

                            <?php if ($order_issue == 0) { ?>

                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1">
                                        Describe the Order Issue in Detail:</td>
                                    <td align="left" height="13" style="width: 197px" class="style1">
                                        <textarea name="orderproblem_text" id="orderproblem_text"></textarea>
                                    </td>

                                </tr>

                                <tr bgColor="#e4e4e4">
                                    <td height="10" colspan="2" style=" width: 189px" class="style1">
                                        <input type="submit" value="Mark as Order Issue">
                                    </td>

                                </tr>
                            <?php  } ?>

                        </table>
                        <input type="hidden" value="<?php echo $orders_id ?>" name="orders_id" />
                        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="assigned_by" />
                        <input type="hidden" value="<?php echo $order_issue ?>" name="order_issue" id="order_issue" />
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="3">

                    <br>
                    <div id="display_order_database_import">
                        <table cellSpacing="1" cellPadding="1" border="0" style="width: 500px">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="6">
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                        DATABASE IMPORT
                                    </font>
                                </td>
                            </tr>

                            <tr bgColor="#e4e4e4">
                                <td height="13" style="width: 100px" class="style1">WAREHOUSE
                                </td>
                                <td align="left" height="13" style="width: 50px" class="style1">Move</td>
                                <td align="left" height="13" style="width: 100px" class="style1">ID</td>
                                <td align="left" height="13" style="width: 100px" class="style1">Module</td>
                                <td align="left" height="13" style="width: 100px" class="style1">FedEx Status</td>
                                <td align="left" height="13" style="width: 60px" class="style1"></td>
                            </tr>
                            <?php

                            $sqlwarehouse = "SELECT * FROM ucbdb_warehouse";
                            $rec_found_in_ord_trk = "no";
                            $resw = db_query($sqlwarehouse);
                            while ($wrow = array_shift($resw)) {

                                if ($wrow["tablename"] != "") {
                                    $sql_orders_active_table = "SELECT * FROM " . $wrow["tablename"] . " WHERE orders_id = " . decrypt_url($_GET['id']);
                                    $rest = db_query($sql_orders_active_table);

                                    while ($row_active = array_shift($rest)) {
                                        $shipping_details = $row_active["shipping_name"] . "\n" . $row_active["shipping_street1"] . ", " . $row_active["shipping_street2"] . ", " . $row_active["shipping_city"] . ", " . $row_active["shipping_state"] . ", " . $row_active["shipping_zip"];
                                        $shipping_details .= "\n" . $row_active["phone"] . "\n" . $row_active["email"];

                                        $rec_found_in_ord_trk = "no";
                                        $orders_tracking_chk = db_query("Select * from orders_active_export where orders_id = '" . decrypt_url($_GET['id']) . "'");
                                        while ($orders_tracking_row = array_shift($orders_tracking_chk)) {
                                            $rec_found_in_ord_trk = "yes";
                                        }
                            ?>

                                        <tr bgColor="#e4e4e4">
                                            <td height="13" class="style1">
                                                <?php echo $wrow["distribution_center"] ?>
                                            </td>
                                            <td align="left" height="13" class="style1">
                                                <?php if ($rec_found_in_ord_trk == "no") { ?>
                                                    <input type="checkbox" name="warehouserowid[]" value="<?php echo $row_active["id"]; ?>">
                                                <?php  } ?>
                                            </td>
                                            <td align="left" height="13" class="style1" title="<?php echo $shipping_details; ?>">
                                                <?php echo $row_active["id"]; ?>
                                            </td>
                                            <td align="left" height="13" class="style1"><?php echo $row_active["module_name"]; ?></td>
                                            <td align="left" height="13" class="style1"><?php echo $row_active["ship_status"]; ?></td>
                                            <td align="left" height="13" class="style1">
                                                <a href="deletelabel.php?table_name=<?php echo encrypt_url($wrow["tablename"]); ?>&id=<?php echo encrypt_url($row_active["id"]); ?>">Delete</a>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>

                            <script>
                                function value_set2kitid(e) {
                                    document.getElementById("kit_id").value = e.options[e.selectedIndex].getAttribute('data-kitid');
                                }

                                function module_data_show(e) {
                                    if (e.options[e.selectedIndex].value != "") {

                                        document.getElementById("productmoduleid").value = e.options[e.selectedIndex].getAttribute('data-moduleid');
                                        document.getElementById("productdescription").value = e.options[e.selectedIndex].getAttribute('data-description');
                                        document.getElementById("reference").value = e.options[e.selectedIndex].getAttribute('data-reference');
                                        document.getElementById("boxweight").value = e.options[e.selectedIndex].getAttribute('data-weight');
                                        document.getElementById("boxlength").value = e.options[e.selectedIndex].getAttribute('data-length');
                                        document.getElementById("boxwidth").value = e.options[e.selectedIndex].getAttribute('data-width');
                                        document.getElementById("boxheight").value = e.options[e.selectedIndex].getAttribute('data-height');
                                    } else {
                                        document.getElementById("productmoduleid").value = "";
                                        document.getElementById("productdescription").value = "";
                                        document.getElementById("reference").value = "";
                                        document.getElementById("boxweight").value = "";
                                        document.getElementById("boxlength").value = "";
                                        document.getElementById("boxwidth").value = "";
                                        document.getElementById("boxheight").value = "";
                                    }
                                }

                                function importproductfromdatabase() {

                                    if (window.XMLHttpRequest) {
                                        xmlhttp = new XMLHttpRequest();
                                    } else {
                                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                    }
                                    xmlhttp.onreadystatechange = function() {
                                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                            document.getElementById("add_row_respose").innerHTML = xmlhttp.responseText;
                                            /*location.reload(); */
                                        }
                                    }
                                    var warehouseid = document.getElementById("warehouseid").value;
                                    var orderid = document.getElementById("orderid").value;
                                    var product_id = document.getElementById("product_name").value;
                                    var kit_id = document.getElementById("kit_id").value;
                                    var productmoduleid = document.getElementById("productmoduleid").value;
                                    var productmodule = document.getElementById("productmodule").value;
                                    var productdescription = document.getElementById("productdescription").value;
                                    var boxweight = document.getElementById("boxweight").value;
                                    var boxlength = document.getElementById("boxlength").value;
                                    var boxwidth = document.getElementById("boxwidth").value;
                                    var boxheight = document.getElementById("boxheight").value;
                                    var reference = document.getElementById("reference").value;
                                    var kitsname = document.getElementById("kitsname").value;
                                    var shipname = document.getElementById("shipname").value;
                                    var shipcompany = document.getElementById("shipcompany").value;
                                    var shipstreet1 = document.getElementById("shipstreet1").value;
                                    var shipstreet2 = document.getElementById("shipstreet2").value;
                                    var shipcity = document.getElementById("shipcity").value;
                                    var shipstate = document.getElementById("shipstate").value;
                                    var shipzip = document.getElementById("shipzip").value;
                                    var shiprelease = document.getElementById("shiprelease").value;
                                    var custphone = document.getElementById("custphone").value;
                                    var custemail = document.getElementById("custemail").value;
                                    var submitimport = document.getElementById("submitimport").value;

                                    var data = "warehouseid=" + warehouseid + "&product_id=" + product_id + "&kit_id=" + kit_id + "&productmoduleid=";
                                    data += productmoduleid + "&productmodule=" + productmodule + "&productdescription=" + productdescription;
                                    data += "&boxweight=" + boxweight + "&boxlength=" + boxlength + "&boxwidth=" + boxwidth + "&boxheight=" + boxheight;
                                    data += "&reference=" + reference + "&kitsname=" + kitsname + "&shipname=" + shipname + "&shipcompany=" + shipcompany;
                                    data += "&shipstreet1=" + shipstreet1 + "&shipstreet2=" + shipstreet2 + "&shipcity=" + shipcity + "&shipstate=";
                                    data += shipstate + "&shipzip=" + shipzip + "&shiprelease=" + shiprelease + "";
                                    data += "&custphone=" + custphone + "&custemail=" + custemail + "&submit=" + submitimport;

                                    xmlhttp.open("GET", "orders_import_new_product2.php?id=" + orderid + "&" + data, true);
                                    xmlhttp.send();

                                }


                                function open_entry_frmimport(id) {
                                    if (window.XMLHttpRequest) {
                                        xmlhttp = new XMLHttpRequest();
                                    } else {
                                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                    }
                                    xmlhttp.onreadystatechange = function() {
                                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                            document.getElementById("lightbox").style.display = "block";
                                            document.getElementById("frmimport_datafrm").innerHTML = xmlhttp.responseText;
                                        }
                                    }

                                    xmlhttp.open("GET", "orders_import_new_product.php?id=" + id, true);
                                    xmlhttp.send();
                                }

                                function closeme() {
                                    document.getElementById("lightbox").style.display = "none";
                                }


                                function select_warehouse_entry() {
                                    var checkboxes2 = document.getElementsByName("warehouserowid[]");
                                    var vals = "";
                                    for (var i = 0, n = checkboxes2.length; i < n; i++) {
                                        if (checkboxes2[i].checked) {
                                            vals += "," + checkboxes2[i].value;
                                        }
                                    }
                                    vals = vals.slice(1);
                                    return vals;
                                }


                                function checkallrecords(ele) {
                                    var checkboxes3 = document.getElementsByName("warehouserowid[]");
                                    if (ele.checked) {
                                        for (var i = 0; i < checkboxes3.length; i++) {
                                            if (checkboxes3[i].type == 'checkbox') {
                                                checkboxes3[i].checked = true;
                                            }
                                        }

                                    } else {
                                        for (var i = 0; i < checkboxes3.length; i++) {
                                            if (checkboxes3[i].type == 'checkbox') {
                                                checkboxes3[i].checked = false;
                                            }
                                        }

                                    }

                                }

                                function Uncheckwarehouse() {
                                    var checkboxes4 = document.getElementsByName("warehouserowid[]");
                                    for (var i = 0; i < checkboxes4.length; i++) {
                                        if (checkboxes4[i].type == 'checkbox') {
                                            checkboxes4[i].checked = false;
                                        }
                                    }
                                }


                                function move_warehouse_entry(id) {
                                    var msgtxt = prompt('Enter the password.');
                                    if (msgtxt == "boomerang") {
                                        var rowids = select_warehouse_entry();
                                        var tableid = document.getElementById("warehouse2move").value;

                                        if (window.XMLHttpRequest) {
                                            xmlhttp = new XMLHttpRequest();
                                        } else {
                                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                        }
                                        xmlhttp.onreadystatechange = function() {
                                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                                Uncheckwarehouse();
                                                location.reload();
                                            }
                                        }

                                        xmlhttp.open("GET", "movewarehouseentry.php?id=" + id + "&tableid=" + tableid + "&rowid=" + rowids, true);
                                        xmlhttp.send();
                                    } else {
                                        alert("Password incorrect.");
                                    }
                                }
                            </script>
                            <style>
                                .lightbox {
                                    display: none;
                                    position: fixed;
                                    z-index: 1;
                                    padding-top: 100px;
                                    left: 0;
                                    top: 0;
                                    width: 100%;
                                    height: 100%;
                                    overflow: auto;
                                    background-color: rgb(0, 0, 0);
                                    background-color: rgba(0, 0, 0, 0.4);
                                }

                                .frmimport_datafrm {
                                    background-color: #fefefe;
                                    margin: auto;
                                    padding: 20px;
                                    border: 1px solid #000;
                                    width: 80%;
                                }

                                .close {
                                    color: #000;
                                    float: right;
                                    font-size: 28px;
                                    font-weight: bold;
                                }

                                .close:hover,
                                .close:focus {
                                    color: #f00;
                                    text-decoration: none;
                                    cursor: pointer;
                                }
                            </style>

                            <?php if ($rec_found_in_ord_trk == "no") { ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="10" class="style1">
                                        Select All
                                    </td>
                                    <td height="10" class="style1">
                                        <input type="checkbox" name="selectall" onchange="javascript:checkallrecords(this);">
                                    </td>
                                    <td height="10" colspan="3" class="style1">
                                        <Select name="warehouse2move" id="warehouse2move">
                                            <?php
                                            echo '<option value=0>Please Select</option>';
                                            $warehouse = "SELECT * FROM ucbdb_warehouse";
                                            $resw2 = db_query($warehouse);
                                            while ($wrow2 = array_shift($resw2)) {
                                                echo '<option value="' . $wrow2['id'] . '">' . $wrow2['distribution_center'] . '</option>';
                                            }
                                            ?>
                                        </select>&nbsp;
                                        <input type="button" onclick="javascript: move_warehouse_entry(<?php echo encrypt_url($_GET['id']); ?>); return false;" id="move_record" name="move_record" value="Move Warehouse" />
                                    </td>
                                    <td align="left" height="10" style="width: 100px">
                                        <input type="button" onclick="javascript: open_entry_frmimport(<?php echo $_GET['id']; ?>); return false;" id="add_record" name="add_record" value="Add Record" />
                                    </td>
                                </tr>
                            <?php  } ?>
                        </table>
                        <?php if ($bad_address == 1) { ?>
                            <p style="color: red;" class="style1">No Import due to bad address. Fix bad address first.</p>
                        <?php  } ?>

                    </div>
                    <div id="lightbox" class="lightbox">
                        <div id="frmimport_datafrm" class="frmimport_datafrm"></div>
                    </div>

                    <br>
                    <div id="ord_trac">
                        <form action="add_return.php" method="post">
                            <table cellSpacing="1" cellPadding="1" width="100%" border="0">
                                <tr align="middle">
                                    <td bgColor="#c0cdda" colSpan="12">
                                        <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                            ORDER TRACKING
                                        </font>
                                    </td>
                                </tr>
                                <tr bgColor="#e4e4e4">
                                    <td style="width: 55px; height: 13px;" class="style1">Module</td>
                                    <td style="width: 55px; height: 13px;" class="style1">Warehouse</td>
                                    <td style="height: 13px; width: 129px;" class="style1">Tracking #</td>
                                    <td style="width: 87px; height: 13px;" class="style1">Status</td>
                                    <td class="style1">Last Activity Date</td>
                                    <td class="style1">Delivery Date</td>
                                    <td class="style1">UPS Chargeback</td>
                                    <td class="style1">Resend</td>
                                    <td class="style1">Return Tracking</td>
                                    <td class="style1">Shipper</td>
                                    <td class="style1">Received</td>
                                    <td class="style1">Cancel Label</td>
                                </tr>
                                <?php
                                /* Begin the Trackung Number Stuff */
                                $userid_pass = "judykrasnow";
                                $access_key = "CCFCCB38E6C3BCB5";
                                $upsURL = "https://wwwcie.ups.com/ups.app/xml/Track";
                                $activity = "activity";
                                class xml_container
                                {
                                    function store(string $k, mixed $v): void
                                    {
                                        $this->{$k}[] = $v;
                                    }
                                }

                                class xml
                                {
                                    var $current_tag = array();
                                    var $xml_parser;
                                    var $Version = 1.0;
                                    var $tagtracker = array();

                                    function startElement($parser, string $name, array $attrs): void
                                    {
                                        array_push($this->current_tag, $name);
                                        $curtag = implode("_", $this->current_tag);
                                        if (isset($this->tagtracker["$curtag"])) {
                                            $this->tagtracker["$curtag"]++;
                                        } else {
                                            $this->tagtracker["$curtag"] = 0;
                                        }
                                        if (count($attrs) > 0) {
                                            $j = $this->tagtracker["$curtag"];
                                            if (!$j) $j = 0;
                                            if (!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
                                                $GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
                                            }
                                            $GLOBALS[$this->identifier]["$curtag"][$j]->store("attributes", $attrs);
                                        }
                                    } // end function startElement

                                    function endElement($parser, string $name)
                                    {
                                        $curtag = implode("_", $this->current_tag);     // piece together tag
                                        if (!$this->tagdata["$curtag"]) {
                                            $popped = array_pop($this->current_tag); // or else we screw up where we are
                                            return;     // if we have no data for the tag
                                        } else {
                                            $TD = $this->tagdata["$curtag"];
                                            unset($this->tagdata["$curtag"]);
                                        }
                                        $popped = array_pop($this->current_tag);
                                        if (sizeof($this->current_tag) == 0) return;     // if we aren't in a tag
                                        $curtag = implode("_", $this->current_tag);     // piece together tag
                                        $j = $this->tagtracker["$curtag"];
                                        if (!$j) $j = 0;
                                        if (!is_object($GLOBALS[$this->identifier]["$curtag"][$j])) {
                                            $GLOBALS[$this->identifier]["$curtag"][$j] = new xml_container;
                                        }
                                        $GLOBALS[$this->identifier]["$curtag"][$j]->store($name, $TD); #$this->tagdata["$curtag"]);
                                        unset($TD);
                                        return TRUE;
                                    }

                                    function characterData($parser, string $cdata): void
                                    {
                                        $curtag = implode("_", $this->current_tag); // piece together tag		
                                        $this->tagdata["$curtag"] .= $cdata;
                                    }

                                    function xml(string $data, string $identifier = 'xml'): void
                                    {
                                        $this->identifier = $identifier;
                                        $this->xml_parser = xml_parser_create();
                                        xml_set_object($this->xml_parser, $this);
                                        xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, 0);
                                        //	xml_set_element_handler($this->xml_parser, "startElement", "endElement");
                                        xml_set_element_handler($this->xml_parser, [$this, "startElement"], [$this, "endElement"]);
                                        // xml_set_character_data_handler($this->xml_parser, "characterData");
                                        xml_set_character_data_handler($this->xml_parser, [$this, "characterData"]);
                                        if (!xml_parse($this->xml_parser, $data, TRUE)) {
                                            sprintf(
                                                "XML error: %s at line %d",
                                                xml_error_string(xml_get_error_code($this->xml_parser)),
                                                xml_get_current_line_number($this->xml_parser)
                                            );
                                        }
                                        xml_parser_free($this->xml_parser);
                                    }  // end constructor: function xml()
                                }

                                $orders_tracking_query = db_query("select * from orders_active_export where orders_id = '" . (int)$orders_id . "' AND LENGTH(tracking_number) > 16");
                                $r_count = tep_db_num_rows($orders_tracking_query);
                                while ($orders_tracking = array_shift($orders_tracking_query)) {
                                    /* Module Table Cell */
                                    $orid = $orders_tracking["id"];
                                    echo "<tr bgColor='#e4e4e4'><td style='width: 55px; height: 13px;' class='style1'>" . $orders_tracking["module_name"] . "</td>";
                                    $orders_warehouse_query = db_query("select * from warehouse where warehouse_id = '" . $orders_tracking["warehouse_id"] . "'");
                                    while ($orders_warehouse = array_shift($orders_warehouse_query)) {
                                        /* WarehouseTable Cell */
                                        echo "<td class='style1'>" . $orders_warehouse["name"] . "</td>";
                                    }
                                    /* Tracking Number Table Cell */
                                    echo "<td style='height: 13px; width: 129px;' class='style1'><a target='_blank' href='http://wwwapps.ups.com/WebTracking/OnlineTool?InquiryNumber1=" .         $orders_tracking["tracking_number"] . "&UPS_HTML_License=9C15C7C38A14393C&IATA=us&Lang=eng&UPS_HTML_Version=3.0&TypeOfInquiryNumberT'>" . $orders_tracking["tracking_number"] . "</a></td>";
                                    /* UPS XML Response Cell */
                                    echo "<td style='width: 87px; height: 13px;' class='style1'>";
                                    $stat_code = "";
                                    $dayx = "";
                                    $monthx = "";
                                    $yearx = "";
                                    $dayy = "";
                                    $monthy = "";
                                    $yeary = "";
                                    if ($orders_tracking["cancel_flag"] == "yes") {
                                        echo "Label Cancel by " . $orders_tracking["cancel_flg_marked_by"] . " on " . $orders_tracking["cancel_flg_marked_on"];
                                    } else {
                                        /* XML Communication with UPS */
                                        $y = "<?php xml version=\"1.0\"?><AccessRequest xml:lang=\"en-US\"><AccessLicenseNumber>" . $access_key . "</AccessLicenseNumber><UserId>" . $userid_pass . "</UserId><Password>" . $userid_pass . "</Password></AccessRequest><?php xml version=\"1.0\"?><TrackRequest xml:lang=\"en-US\"><Request><TransactionReference><CustomerContext>Example 1</CustomerContext><XpciVersion>1.0001</XpciVersion></TransactionReference><RequestAction>Track</RequestAction><RequestOption>" . $activity . "</RequestOption></Request><TrackingNumber>" . $orders_tracking["tracking_number"] . "</TrackingNumber></TrackRequest>";

                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $upsURL);
                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $y);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        $upsResponse = curl_exec($ch);
                                        curl_close($ch);

                                        $obj = new xml($upsResponse, "xml");
                                        //$xml = new xml($upsResponse, "xml");
                                        $xml = new xml($upsResponse, "xml");

                                        $what_ups_says = trim($xml["TrackResponse_Response"][0]->ResponseStatusCode[0]);
                                        $stat = "";
                                        if ($what_ups_says == "1") {
                                            $ups_description = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Description[0] . "\n";
                                            $the_code = $xml["TrackResponse_Shipment_Package_Activity_Status_StatusType"][0]->Code[0] . "\n";
                                            $the_delivery_date = $xml["TrackResponse_Shipment"][0]->ScheduledDeliveryDate[0] . "\n";
                                            $the_date = $xml["TrackResponse_Shipment_Package_Activity"][0]->Date[0] . "\n";
                                            $yearx = substr("$the_delivery_date", 0, 4);
                                            $monthx = substr("$the_delivery_date", 4, 2);
                                            $dayx = substr("$the_delivery_date", 6, 2);
                                            $yeary = substr("$the_date", 0, 4);
                                            $monthy = substr("$the_date", 4, 2);
                                            $dayy = substr("$the_date", 6, 2);
                                            $the_code = trim($the_code);
                                            switch ($the_code) {
                                                case 'I':
                                                    $stat = "In transit";
                                                    $stat_code = 2;
                                                    break;
                                                case 'D':
                                                    $stat = "Delivered";
                                                    $stat_code = 1;
                                                    break;
                                                case 'X':
                                                    $stat = "Exception";
                                                    $stat_code = 2;
                                                    break;
                                                case 'P':
                                                    $stat = "Pickup";
                                                    $stat_code = 2;
                                                    break;
                                                case 'M':
                                                    $stat = "Manifest Pickup";
                                                    $stat_code = 3;
                                                    break;
                                            }
                                        } else {
                                            $ups_error_note = $xml["TrackResponse_Response_Error"][0]->ErrorDescription[0];
                                            echo "<center><b><font color='#FF0000'>" . $ups_error_note . "</font></b></center>";
                                        }
                                        if ($the_code = '') {
                                            echo "No Data";
                                        } else {
                                            echo $stat;
                                            unset($stat);
                                        }
                                    }
                                    echo "</td>";
                                    echo "<td class='style1'>";
                                    echo $monthy . "/" . $dayy . "/" . $yeary;
                                    echo "</td>";
                                    echo "<td class='style1'>";
                                    if ($stat_code == 1) {
                                        echo $monthy . "/" . $dayy . "/" . $yeary;
                                    }
                                    if ($stat_code == 2) {
                                        echo $monthx . "/" . $dayx . "/" . $yearx;
                                    }
                                    if ($stat_code == 3) {
                                        echo "No Data";
                                    }
                                    echo "</td>";
                                    unset($obj);
                                    unset($xml);
                                    $cb_id = $orders_tracking["id"];
                                    $chargeback = $orders_tracking["chargeback"];
                                    $cb_stat = "";
                                    switch ($chargeback) {
                                        case 0:
                                            $cb_stat = "<a href=\"process_chargeback.php?id=" . encrypt_url($cb_id) . "&orders_id=" . encrypt_url($orders_id) . "\">Chargeback</a>";
                                            break;
                                        case 1:
                                            $cb_stat = "<a href=\"process_chargeback.php\">Chargeback</a>";
                                            break;
                                        case 2:
                                            $cb_stat = "Requested";
                                            break;
                                        case 3:
                                            $cb_stat = "Submitted";
                                            break;
                                        case 4:
                                            $cb_stat = "Refunded";
                                            break;
                                        case 5:
                                            $cb_stat = "Denied";
                                            break;
                                    }

                                    echo "<td class='style1' style='height: 13px' class='style1'>" . $cb_stat . "</td>";
                                    echo "<td class='style1' style='height: 13px' class='style1'><a href='resend_module.php?id=" . encrypt_url($orders_tracking['id']) . "&orders_id=" . encrypt_url($orders_id) . "&warehouse_id=" . encrypt_url($orders_tracking["warehouse_id"]) . "&module_name=" . $orders_tracking["module_name"] . "'>Resend</a></td>";
                                    if ($orders_tracking['return_tracking_number'] != '') {
                                        echo "<td class='style1' style='height: 13px' class='style1'>" . $orders_tracking['return_tracking_number'] . "</td>";
                                    } else {
                                        echo "<td class='style1' style='height: 13px' class='style1'><input type='text' name='return_tracking_number[]' size='12'></td>";
                                    }
                                    if ($orders_tracking['return_delivery_service'] != '') {
                                        echo "<td class='style1' style='height: 13px' class='style1'>" . $orders_tracking['return_delivery_service'];
                                    } else {
                                        echo "<td class='style1' style='height: 13px' class='style1'><select name=\"return_delivery_service[]\">
																	<option></option>
																	<option value=\"UPS\">UPS</option>
																	<option value=\"FedEx\">FedEx</option>
																	<option value=\"DHL\">DHL</option>
																	<option value=\"USPS\">USPS</option>
																</select>";
                                    }
                                    echo "<input type=\"hidden\" value=" . $cb_id . " name=\"id[]\" /></td>";
                                    if ($orders_tracking['return_tracking_number'] != '') {
                                        echo "<td class='style1' style='height: 13px'><input type='checkbox' name='received' ></td>";
                                    } else {
                                        echo "<td></td>";
                                    }

                                    if ($orders_tracking["cancel_flag"] == "yes") {
                                        echo "<td>&nbsp;</td>";
                                    } else {
                                        echo "<td><input type='button' name='cancel_label' id='cancel_label' value='Cancel Label'></td>";
                                    }
                                    echo "</tr>";
                                }

                                //to get all tracking_number
                                $all_tracking_number = "";
                                $orders_tracking_query = db_query("select tracking_number from orders_active_export where orders_id = '" . (int)$orders_id . "' AND LENGTH(tracking_number) < 16");
                                $r_count = tep_db_num_rows($orders_tracking_query);
                                while ($orders_tracking = array_shift($orders_tracking_query)) {
                                    $all_tracking_number .= $orders_tracking["tracking_number"] . ",";
                                }

                                if (strpos($all_tracking_number, ",") > 0) {
                                    $all_tracking_number = substr($all_tracking_number, 0, strlen($all_tracking_number) - 1);
                                }

                                $orders_tracking_query = db_query("select * from orders_active_export where orders_id = '" . (int)$orders_id . "' AND LENGTH(tracking_number) < 16");
                                $r_count = tep_db_num_rows($orders_tracking_query);
                                while ($orders_tracking = array_shift($orders_tracking_query)) {
                                    /* Module Table Cell */
                                    $orid = $orders_tracking["id"];
                                    echo "<tr bgColor='#e4e4e4'><td style='width: 55px; height: 13px;' class='style1'>" . $orders_tracking["module_name"] . "</td>";
                                    $orders_warehouse_query = db_query("select * from warehouse where warehouse_id = '" . $orders_tracking["warehouse_id"] . "'");
                                    while ($orders_warehouse = array_shift($orders_warehouse_query)) {
                                        /* WarehouseTable Cell */
                                        echo "<td class='style1'>" . $orders_warehouse["name"] . "</td>";
                                    }
                                    /* Tracking Number Table Cell */
                                    echo "<td style='height: 13px; width: 129px;' class='style1'><a target='_blank' href='https://www.fedex.com/fedextrack/summary?trknbr=" . $all_tracking_number . "'>" . $orders_tracking["tracking_number"] . "</a></td>";
                                    /* UPS XML Response Cell */
                                    echo "<td style='width: 87px; height: 13px;' class='style1'>";

                                    if ($orders_tracking["cancel_flag"] == "yes") {
                                        echo "Label Cancel by " . $orders_tracking["cancel_flg_marked_by"] . " on " . $orders_tracking["cancel_flg_marked_on"] . "</td><td> </td><td> </td><td> </td>";
                                    } else {

                                        echo $orders_tracking["fedex_description"] . "</td><td> </td><td> </td>";
                                        unset($obj);
                                        unset($xml);
                                        $cb_id = $orders_tracking["id"];
                                        $chargeback = $orders_tracking["chargeback"];
                                        $cb_stat = "";
                                        switch ($chargeback) {
                                            case 0:
                                                $cb_stat = "<a href=\"process_chargeback.php?id=" . encrypt_url($cb_id) . "&orders_id=" . encrypt_url($orders_id) . "\">Chargeback</a>";
                                                break;
                                            case 1:
                                                $cb_stat = "<a href=\"process_chargeback.php\">Chargeback</a>";
                                                break;
                                            case 2:
                                                $cb_stat = "Requested";
                                                break;
                                            case 3:
                                                $cb_stat = "Submitted";
                                                break;
                                            case 4:
                                                $cb_stat = "Refunded";
                                                break;
                                            case 5:
                                                $cb_stat = "Denied";
                                                break;
                                        }

                                        echo "<td class='style1' style='height: 13px' class='style1'>" . $cb_stat . "</td>";
                                    }

                                    echo "<td class='style1' style='height: 13px' class='style1'><a href='resend_module.php?id=" . encrypt_url($orders_tracking['id']) . "&orders_id=" . encrypt_url($orders_id) . "&warehouse_id=" . encrypt_url($orders_tracking["warehouse_id"]) . "&module_name=" . $orders_tracking["module_name"] . "'>Resend</a></td>";
                                    if ($orders_tracking['return_tracking_number'] != '') {
                                        echo "<td class='style1' style='height: 13px' class='style1'>" . $orders_tracking['return_tracking_number'] . "</td>";
                                    } else {
                                        echo "<td class='style1' style='height: 13px' class='style1'><input type='text' name='return_tracking_number[]' size='12'></td>";
                                    }
                                    if ($orders_tracking['return_tracking_number'] != '') {
                                        echo "<td class='style1' style='height: 13px' class='style1'>" . $orders_tracking['return_tracking_number'];
                                    } else {
                                        echo "<td class='style1' style='height: 13px' class='style1'><select name=\"return_delivery_service[]\">
																	<option></option>
																	<option value=\"UPS\">UPS</option>
																	<option value=\"FedEx\">FedEx</option>
																	<option value=\"DHL\">DHL</option>
																	<option value=\"USPS\">USPS</option>
																	</select>";
                                    }
                                    echo "<input type=\"hidden\" value=" . $cb_id . " name=\"id[]\" /></td>";
                                    if ($orders_tracking['return_tracking_number'] != '') {
                                        echo "<td class='style1' style='height: 13px'><input type='checkbox' name='received' ></td>";
                                    } else {
                                        echo "<td></td>";
                                    }
                                    if ($orders_tracking["cancel_flag"] == "yes") {
                                        echo "<td>&nbsp;</td>";
                                    } else {
                                        echo "<td>
																	<a onclick='order_cancel_labels(encrypt_url($orders_id),encrypt_url($orid))' style='text-decoration: underline; cursor: pointer;'>Cancel Label</a>
																</td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" colspan="12" class="style1">
                                        <input type="hidden" value="<?php echo $r_count ?>" name="count" />
                                        <input type="hidden" value="<?php echo $orders_id ?>" name="orders_id" />
                                        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="assigned_by" />
                                        <input type="submit" value="Save" />
                                    </td>
                                </tr>

                            </table>
                        </form>
                    </div>
                </td>

                <td></td>
                <td colspan="3" valign="top">
                    <?php
                    $crm_numberof_chr = 0;
                    $crm_rows_per_page = 0;
                    $crm_numberof_chr_divheight = 0;
                    $sqlt_crm = "SELECT * FROM tblvariable";
                    db_b2c_email_new();
                    $result_crm = db_query($sqlt_crm);
                    while ($myrowselt_crm = array_shift($result_crm)) {
                        if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_numberof_chr")) {
                            $crm_numberof_chr = $myrowselt_crm["variablevalue"];
                        }
                        if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_rows_per_page")) {
                            $crm_rows_per_page = $myrowselt_crm["variablevalue"];
                        }
                        if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_numberof_chr_divheight")) {
                            $crm_numberof_chr_divheight = $myrowselt_crm["variablevalue"];
                        }
                    }
                    ?>

                    <form method="post" encType="multipart/form-data" action="addcrm.php">
                        <table cellSpacing="1" cellPadding="1" width="100%" border="0">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colspan="4">
                                    <font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER
                                        LOG
                                    </font>
                                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                    </font>
                                </td>
                            </tr>
                            <input type="hidden" value="<?php echo $orders_id ?>" name="orders_id" />
                            <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                            <tr vAlign="top">
                                <td bgColor="#e4e4e4" style="width: 106px" class="style1"><select size="1" name="comm_type">
                                        <?php
                                        $sql = "SELECT * FROM ucbdb_customer_log_config ORDER BY rank";
                                        db();
                                        $result = db_query($sql);
                                        while ($myrowsel = array_shift($result)) {
                                        ?>
                                            <option value="<?php echo $myrowsel["id"]; ?>"><?php echo $myrowsel["comm_type"]; ?></option>
                                        <?php  } ?>
                                    </select>&nbsp;</td>
                                <td bgColor="#e4e4e4" style="width: 288px" class="style1">
                                    <textarea name="message" style="width: 361px; height: 41px;"></textarea>
                                </td>
                                <td bgColor="#e4e4e4">&nbsp;</td>
                                <td align="middle" bgColor="#e4e4e4" rowSpan="2" style="width: 76px" class="style1">
                                    <input type="submit" value="Add" />
                                </td>
                            </tr>
                            <tr>
                                <td bgColor="#e4e4e4" colSpan="3" class="style1">
                                    <input type="file" size="50" name="file" />
                                </td>
                            </tr>
                        </table>
                    </form>

                    <table cellSpacing="1" cellPadding="1" width="100%" border="0">
                        <tr align="middle">
                            <td bgColor="#c0cdda" colspan="6">
                                <font face="Arial, Helvetica, sans-serif" size="1">CUSTOMER
                                    LOG HISTORY</font>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                                </font>
                            </td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td width="15%" class="style1">Date</td>
                            <td class="style1">Image</td>
                            <td class="style1">Type</td>
                            <td class="style1">Notes</td>
                            <td class="style1">Employee</td>
                            <td class="style1">File Link</td>
                        </tr>
                        <?php
                        $sql = "SELECT * FROM ucbdb_crm WHERE orders_id = " . $orders_id . " ORDER BY message_date DESC, id DESC ";
                        $result = db_query($sql);
                        while ($myrowsel = array_shift($result)) {
                        ?>
                            <?php
                            $the_log_date = $myrowsel["message_date"];
                            $yearz = substr("$the_log_date", 0, 4);
                            $monthz = substr("$the_log_date", 4, 2);
                            $dayz = substr("$the_log_date", 6, 2);
                            ?>


                            <tr bgColor="#e4e4e4">
                                <td class="style1"><?php echo date("m/d/Y H:i:s", strtotime($myrowsel["timestamp"])) . " CT"; ?></td>
                                <td class="style1"><?php
                                                    if ($myrowsel["comm_type"] != "") {
                                                        $qry2 = "SELECT icon_file, comm_type FROM ucbdb_customer_log_config WHERE id = '" . $myrowsel["comm_type"] . "'";
                                                        $result2 = db_query($qry2);
                                                        while ($myrowsel2 = array_shift($result2)) { ?>
                                            <img src="images/<?php echo $myrowsel2["icon_file"]; ?>" alt="" border="0">
                                </td>
                                <td class="style1"><?php echo $myrowsel2["comm_type"]; ?></td>
                        <?php  }
                                                    }
                        ?>
                        <td class="style1">
                            <div>
                                <?php
                                $final_msg = "";
                                $final_msg_top = "";
                                $attachment_str = "";
                                $email_body_toppart = "";
                                if ($myrowsel["comm_type"] == "10") {
                                    db_b2c_email_new();
                                    $query = "select emaildate, fromadd, toadd, ccadd, subject FROM tblemail WHERE unqid =" . $myrowsel["EmailID"];
                                    $dt_view_eml = db_query($query);
                                    $attstr = "";
                                    while ($rec_em = array_shift($dt_view_eml)) {
                                        $query_att = "select attachmentname FROM tblemail_attachment WHERE emailid =" . $myrowsel["EmailID"];
                                        $dt_view_eml_att = db_query($query_att);
                                        while ($rec_em_att = array_shift($dt_view_eml_att)) {
                                            $attachment_str = $attachment_str . "<a style='color:#0000FF' target='_blank' href='emailatt_uploads/" . $myrowsel["EmailID"] . "/" . $rec_em_att["attachmentname"] . "'>" . $rec_em_att["attachmentname"] . "</a>, ";
                                        }

                                        $final_msg = "";
                                        $query_att = "select body_txt FROM tblemail_body_txt WHERE email_id =" . $myrowsel["EmailID"];
                                        $dt_view_eml_att = db_query($query_att);
                                        while ($rec_em_att = array_shift($dt_view_eml_att)) {
                                            $final_msg = $rec_em_att["body_txt"];
                                        }

                                        $final_msg = preg_replace("/bgcolor=" . chr(34) . "#E7F5C2" . chr(34) . "/", "", $final_msg);
                                        $final_msg_top = preg_replace("/background-color:/", "\ ", $final_msg);

                                        $email_body_toppart = "<b>" . $rec_em["subject"] . "</b> <br/> Date: " . date("m/d/Y h:i:s a", strtotime($rec_em["emaildate"])) . "<br/> From:" . $rec_em["fromadd"] . "<br/>";
                                        $email_body_toppart .= "To: " . $rec_em["toadd"];
                                        if ($rec_em["ccadd"] != "") {
                                            $email_body_toppart .= "<br/>Cc: " . $rec_em["ccadd"];
                                        }
                                        $email_body_toppart .= "<div style='height:1px; background: url(images/singleline.png) repeat-x;'></div>";

                                        if (trim($attachment_str) == "") {
                                            $attstr = "";
                                        } else {
                                            $attstr = 'Attachment: ' . substr($attachment_str, 0, strlen(trim($attachment_str)) - 1) . "<br/><br/>";
                                        }
                                    }

                                    $final_msg_nodivs = strip_tags($final_msg);

                                    $tmppos = strlen($email_body_toppart . $attstr . $final_msg_nodivs);
                                    if ($tmppos > $crm_numberof_chr) {
                                        $tmpstr = "<br><div style='background-color:#E4E4E4; height:" . $crm_numberof_chr_divheight . "px; width:400px; overflow-x: hidden; overflow-y: hidden;'>" . $email_body_toppart . $attstr . $final_msg_top . "</div> <br/><a href='#' onclick='displayemail(" . $myrowsel["id"] . ")'>View Complete Email</a> <br/><br/>";
                                        $tmpstr .= "<div style='display:none;' id='emlmsg" . $myrowsel["id"] . "'> <a href='javascript:void(0)' onclick=document.getElementById('email_light').style.display='none';>Close Window</a> <br/><br/>";
                                        $tmpstr .= $email_body_toppart . $attstr . $final_msg . "</div>";

                                        echo $tmpstr;
                                    } else {
                                        echo $email_body_toppart . $attstr . $final_msg;
                                    }
                                } else {
                                    echo $myrowsel["message"];
                                }
                                ?>
                            </div>
                        </td>
                        <td class="style1"><?php echo $myrowsel["employee"]; ?></td>
                        <td class="style1"><?php if ($myrowsel["file_name"] != '') {
                                                echo "<a href='files/" . $myrowsel["file_name"] . "'>File</a>";
                                            }
                                            ?></td>
                            </tr>

                        <?php  } ?>

                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <?php
                    db();
                    $sql = "SELECT * FROM ucbdb_items ORDER BY rank";
                    $result = db_query($sql);
                    $count = tep_db_num_rows($result);
                    $count_and_one = $count + 1;
                    ?>

                    <style type="text/css">
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

                        .white_content_email {
                            display: none;
                            position: absolute;
                            top: 5%;
                            left: 10%;
                            width: 70%;
                            height: 60%;
                            padding: 16px;
                            border: 1px solid gray;
                            background-color: white;
                            z-index: 1002;
                            overflow: auto;
                        }
                    </style>

                    <div id="light" class="white_content"></div>
                    <div id="fade" class="black_overlay"></div>

                    <div id="email_light" class="white_content_email"></div>

                    <script type="text/javascript">
                        function editshipping(tmp_orderid) {

                            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp = new XMLHttpRequest();
                            } else { // code for IE6, IE5
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" + xmlhttp.responseText;
                                    document.getElementById('light').style.display = 'block';
                                }
                            }

                            xmlhttp.open("GET", "order_shipping_data.php?tmp_orderid=" + tmp_orderid, true);
                            xmlhttp.send();
                        }

                        function update_order_details() {
                            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                                xmlhttp = new XMLHttpRequest();
                            } else { // code for IE6, IE5
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    //document.getElementById("display_order_shipping").innerHTML=xmlhttp.responseText;
                                    document.getElementById('light').style.display = 'none';
                                }
                            }

                            order_name = document.getElementById("order_name").value;
                            order_company = document.getElementById("order_company").value;
                            order_add1 = document.getElementById("order_add1").value;
                            order_add2 = document.getElementById("order_add2").value;
                            order_city = document.getElementById("order_city").value;
                            order_state = document.getElementById("order_state").value;
                            order_zipcode = document.getElementById("order_zipcode").value;
                            order_phone = document.getElementById("order_phone").value;
                            order_email = document.getElementById("order_email").value;
                            tmp_orderid = document.getElementById("tmp_orderid").value;

                            xmlhttp.open("POST", "order_shipping_update.php?order_name=" + encodeURIComponent(order_name) + "&order_company=" + encodeURIComponent(order_company) + "&order_add1=" + encodeURIComponent(order_add1) + "&order_add2=" + encodeURIComponent(order_add2) + "&order_city=" + encodeURIComponent(order_city) + "&order_state=" + encodeURIComponent(order_state) + "&order_zipcode=" + encodeURIComponent(order_zipcode) + "&order_phone=" + encodeURIComponent(order_phone) + "&order_email=" + encodeURIComponent(order_email) + "&tmp_orderid=" + tmp_orderid, true);
                            xmlhttp.send();

                            /*if (window.XMLHttpRequest)
                            {// code for IE7+, Firefox, Chrome, Opera, Safari
                            	xmlhttp2=new XMLHttpRequest();
                            }
                            else
                            {// code for IE6, IE5
                            xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp2.onreadystatechange=function()
                            {
                            	if (xmlhttp2.readyState==4 && xmlhttp2.status==200)
                            	{
                            		document.getElementById("display_order_database_import").innerHTML=xmlhttp2.responseText;
                            	}
                            }	
                            
                            xmlhttp2.open("GET","order_shipping_update2.php?tmp_orderid="+tmp_orderid,true);
                            xmlhttp2.send();  
                            */
                        }

                        function update_cart() {
                            var x
                            var total = 0
                            var newtotal = 0
                            var price
                            var quantity
                            var other_total
                            var item_total
                            var order_total
                            for (x = 1; x <= <?php echo $count ?>; x++) {
                                //    quantity=document.getElementById("quantity_"+x).value
                                //    price=document.getElementById("price_"+x).value
                                item_total = document.getElementById("total_" + x)
                                //    answer=quantity * price
                                //    document.getElementById("total_"+x).value=answer.toFixed(2)
                                total = total + item_total.value * 1
                            }
                            other_total = document.getElementById("other_amt").value
                            total = total + other_total * 1
                            order_total = document.getElementById("order_total")
                            document.getElementById("order_total").value = total.toFixed(2)
                        }

                        function calculate(x) {
                            var x
                            var total = 0
                            var newtotal = 0
                            var price
                            var quantity
                            var other_total
                            var item_total
                            var order_total
                            quantity = document.getElementById("quantity_" + x).value
                            price = document.getElementById("price_" + x).value
                            answer = quantity * price
                            document.getElementById("total_" + x).value = answer.toFixed(2)
                            update_cart()
                        }
                    </script>

                    <form method="post" action="addcredit.php">
                        <input type="hidden" value="<?php echo $orders_id; ?>" name="orders_id">
                        <input type="hidden" value="<?php echo $cc_number; ?>" name="cc_number" />
                        <input type="hidden" value="<?php echo $cc_expires; ?>" name="cc_expires" />
                        <input type="hidden" value="<?php echo $_COOKIE['userinitials']; ?>" name="employee" />
                        <input type="hidden" value="<?php echo $auth_trans_id; ?>" name="auth_trans_id" />
                        <?php

                        $sqlas = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type = 'Credit'";
                        $resultas = db_query($sqlas);
                        while ($myrowselas = array_shift($resultas)) {
                            $comm_type_insert = $myrowselas["id"];
                        ?>
                            <input type="hidden" value="<?php echo $comm_type_insert; ?>" name="comm_type" />
                        <?php  } ?>

                        <table cellSpacing="1" cellPadding="1" width="100%" border="0">
                            <tr align="middle">
                                <td bgColor="#c0cdda" colSpan="7">
                                    <span class="style1">CREDITS</span>
                                </td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td style="width: 280px; height: 13px;" class="style1">
                                    Item</td>
                                <td style="height: 13px; width: 173px;" class="style1">
                                    Distribution Center</td>
                                <td style="height: 13px; width: 173px;" class="style1">
                                    Notes:</td>

                                <td style="width: 127px; height: 13px;" class="style1">
                                    Reason Code</td>
                                <td class="style1">Chargeback</td>

                                <td class="style1">Quantity</td>

                                <td class="style1">Amount</td>

                            </tr>
                            <?php
                            $i = 0;
                            while ($myrowsel = array_shift($result)) {
                                $i++;
                            ?>

                                <tr bgColor="#e4e4e4">
                                    <td style='width: 200px; height: 13px;' class='style1'><?php echo $myrowsel["item_name"]; ?>

                                        <input type="hidden" name="item_name[]" value="<?php echo $myrowsel["item_name"]; ?>">

                                    </td>



                                    <td align="left" class="style1" style="width: 173px; height: 13px;">
                                        <select name="warehouse[]">
                                            <?php

                                            $sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
                                            $result2 = db_query($sql2);
                                            while ($myrowsel2 = array_shift($result2)) {

                                            ?>
                                                <option value="<?php echo $myrowsel2["distribution_center"]; ?>"><?php echo $myrowsel2["distribution_center"]; ?></option>
                                            <?php  } ?>
                                        </select>
                                    </td>

                                    <td class="style1"><input type="textbox" id="notes" name="notes[]" size="20"></td>

                                    <td style="height: 13px">
                                        <select name="reason_code[]">
                                            <?php

                                            $sql3 = "SELECT * FROM ucbdb_reason_code ORDER BY rank";
                                            $result3 = db_query($sql3);
                                            while ($myrowsel3 = array_shift($result3)) {
                                            ?>
                                                <option value="<?php echo $myrowsel3["reason"]; ?>"><?php echo $myrowsel3["reason"]; ?></option>
                                            <?php  } ?>
                                        </select>
                                    </td>
                                    <td class="style7" style="height: 13px">
                                        <select name="chargeback[]">
                                            <option value="No">No
                                            <option value="Yes">Yes
                                        </select>
                                    </td>
                                    <td style="height: 13px">
                                        <select name="quantity[]" id="quantity_<?php echo $i; ?>" onchange="calculate(<?php echo $i; ?>)">
                                            <option value="0"></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select><input type="hidden" name="price_<?php echo $i; ?>" value="<?php echo $myrowsel["amount"]; ?>">
                                    </td>
                                    <td style="width: 214px; height: 13px;" class="style1"><input type="textbox" name="total[]" id="total_<?php echo $i; ?>" size="8" onchange="update_cart()"></td>
                                </tr>
                            <?php  } ?>
                            <tr bgColor="#e4e4e4">
                                <td height="13" class="style1">
                                    Other:</td>
                                <td align="left" class="style1" style="width: 173px; height: 13px;">
                                    <select name="warehouse_other">
                                        <?php
                                        $sql2 = "SELECT * FROM ucbdb_warehouse ORDER BY rank";
                                        $result2 = db_query($sql2);
                                        while ($myrowsel2 = array_shift($result2)) {

                                        ?>
                                            <option value="<?php echo $myrowsel2["distribution_center"]; ?>"><?php echo $myrowsel2["distribution_center"]; ?></option>
                                        <?php  } ?>
                                    </select>
                                </td>
                                <td style="width: 214px; height: 13px;" class="style1"><input type="textbox" id="other" name="other" size="20"></td>
                                <td></td>
                                <td class="style7" style="height: 13px">

                                    <input name="chargeback_other" type="checkbox">
                                </td>
                                <td style="height: 13px">
                                <td class="style1"><input type="textbox" id="other_amt" name="other_amt" size="8" onchange="update_cart()"></td>
                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" colspan="6" class="style1">
                                    Total
                                </td>
                                <td class="style1"><input type="textbox" id="order_total" name="order_total" size="8" readonly></td>

                            </tr>
                            <tr bgColor="#e4e4e4">
                                <td height="13" colspan="7" class="style1">
                                    <input type="hidden" name="count" value="<?php echo $count; ?>">
                                    <input type="submit" size="8" value="Process Credit">
                                </td>
                            </tr>

                        </table>
                    </form>
                </td>
                <td>
                </td>
                <td>
                    <br />
                </td>
            </tr>
        </table>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
<?php

    } //IF RESULT
} //END OF PROC VIEW
