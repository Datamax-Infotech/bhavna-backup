<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
$orders_id = $_POST['orders_id'];
$contact_mb_rdy = "SELECT * FROM orders WHERE orders_id = $orders_id";
$contact_mb_rdy_result = db_query($contact_mb_rdy, db());
$contact_mb_rdy_rows = tep_db_num_rows($contact_mb_rdy_result);
if ($contact_mb_rdy_rows == 0) {
        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
                header('Location: contact_status_drill.php?id=' . $_POST['id'] . '&proc=View&notice=1');
                exit;
        } else {
                echo "<script type=\"text/javascript\">";
                echo "window.location.href=\"contact_status_drill.php?id=" . $_POST['id'] . "&proc=View&notice=1\";";
                echo "</script>";
                echo "<noscript>";
                echo "<meta http-equiv=\"refresh\" content=\"0;url=contact_status_drill.php.php?id=" . $_POST['id'] . "&proc=View&notice=1\" />";
                echo "</noscript>";
                exit;
        } //==== End -- Redirect
}


$id = $_POST['id'];
$contact_id = $_POST['id'];

$sql = "SELECT * FROM ucb_contact WHERE id='$id'";
$result = db_query($sql, db());
while ($myrowsel = array_shift($result)) {
        $id = $myrowsel["id"];
        $type_id = $myrowsel["type_id"];
        $first_name = $myrowsel["first_name"];
        $last_name = $myrowsel["last_name"];
        $title = $myrowsel["title"];
        $company = $myrowsel["company"];
        $industry = $myrowsel["industry"];
        $address1 = $myrowsel["address1"];
        $address2 = $myrowsel["address2"];
        $city = $myrowsel["city"];
        $state = $myrowsel["state"];
        $zip = $myrowsel["zip"];
        $phone1 = $myrowsel["phone1"];
        $phone2 = $myrowsel["phone2"];
        $email = $myrowsel["email"];
        $website = $myrowsel["website"];
        $order_no = $myrowsel["order_no"];
        $choose = $myrowsel["choose"];
        $ccheck = $myrowsel["ccheck"];
        $infomation = $myrowsel["infomation"];
        $help = $myrowsel["help"];
        $experience = $myrowsel["experience"];
        $mail_lists = $myrowsel["mail_lists"];
        $comments = $myrowsel["comments"];
        $sel_service = $myrowsel["sel_service"];
        $experiance = $myrowsel["experiance"];
        $is_export = $myrowsel["is_export"];
        $added_on = $myrowsel["added_on"];
        $have_permission = $myrowsel["have_permission"];

        $details = "Inquiry Date: " . $added_on . "<br>";
        $details .= "Time: " . $added_on . "<br>";
        $details .= "First Name: " . $first_name . "<br>";
        $details .= "Last Name: " . $last_name . "<br>";
        $details .= "Title: " . $title . "<br>";
        $details .= "Company Name: " . $company . "<br>";
        $details .= "Industry: " . $industry . "<br>";
        $details .= "Address: " . $address1 . "<br>";
        $details .= "Address 2: " . $address2 . "<br>";
        $details .= "City: " . $city . "<br>";
        $details .= "State: " . $state . "<br>";
        $details .= "Zip: " . $zip . "<br>";
        $details .= "Phone: " . $phone . "<br>";
        $details .= "Phone 2: " . $phone2 . "<br>";
        $details .= "Email: " . $email . "<br>";
        $details .= "Website: " . $website . "<br>";
        $details .= "How Hear: " . $infomation . "<br>";
        $details .= "Help: " . $help . "<br>";
        $details .= "Other Comments: " . $comments . "<br>";
        $details .= "Testimonial: " . $experience . "<br>";
        $details .= "Have Permission (Testimonial): " . $have_permission . "<br>";

        $today = date("Ymd");
        $realtoday = date("m d Y");
        $datewtime = date("F j, Y, g:i a");

        $commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
        $commqryrw = array_shift(db_query($commqry, db()));
        $comm_type = $commqryrw["id"];

        $sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $details . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
        $result = db_query($sql, db());


        $sql7 = "SELECT * FROM ucbdb_contact_crm WHERE contact_id = " . $id . " ORDER BY message_date DESC, id DESC ";
        $result7 = db_query($sql7, db());
        while ($myrowsel7 = array_shift($result7)) {
                $sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $orders_id . "','" . $myrowsel7["comm_type"] . "','" . $myrowsel7["message"] . "','" . $today . "','" . $_COOKIE['userinitials'] . "','" . $myrowsel7["file_name"] . "')";
                $result = db_query($sql, db());
        }


        $messagexfer = "<b>Transferred From Contact</b>.  The contact inquiry below has been trasnferred to this Order on " . $datewtime;
        $sqlxfer = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $orders_id . "','" . $comm_type . "','" . $messagexfer . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
        $resultxfer = db_query($sqlxfer, db());

        $message = "<b>Transferred to Order</b>.  This contact inquiry has been trasnferred to Order Number <a href=\"orders.php?id=" . $orders_id . "&proc=View&searchcrit=&page=0\">" . $orders_id . "</a> on " . $datewtime;

        $sql = "INSERT INTO ucbdb_contact_crm (contact_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $_POST['id'] . "','" . $comm_type . "','" . $message . "','" . $today . "','" . $_COOKIE['userinitials'] . "','" . $_FILES["file"]["name"] . "')";
        //echo "<BR>SQL: $sql<BR>";
        $result = db_query($sql, db());



        $sqlissue = "SELECT * FROM ucb_contact WHERE id = " . $id . " AND status = 'Attention'";
        $resissue = db_query($sqlissue, db());
        $resissuecount = tep_db_num_rows($resissue);
        if ($resissuecount != 0) {
                while ($resissuearr = array_shift($resissue)) {
                        $status = 'Attention';
                        $assign_to = $resissuearr["employee"];

                        $sqlissue1 = "SELECT * FROM ucbdb_issue WHERE orders_id = " . $orders_id . " AND issue = 'Attention'";
                        $resissue1 = db_query($sqlissue1, db());
                        $resissue1count = tep_db_num_rows($resissue1);
                        if ($resissue1count != 0) {
                                $upd_sql = "UPDATE ucbdb_issue SET issue = '" . $status . "', assigned_to = '" . $assign_to . "', assigned_by = '" . $_COOKIE['userinitials'] . "' WHERE orders_id = " . $orders_id;
                                db_query($upd_sql, db());
                        } else {
                                $ins_sql = "INSERT INTO ucbdb_issue (orders_id, issue, assigned_to, assigned_by, when_assigned) VALUES ( '" . $orders_id . "','" . $status . "','" . $assign_to . "','" . $_COOKIE['userinitials'] . "','" . $datewtime . "')";
                                db_query($ins_sql, db());
                        }
                }
        }
}

echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: contact_status_drill.php?id=' . $_POST['id'] . '&proc=View');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"contact_status_drill.php?id=" . $_POST['id'] . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=contact_status_drill.php.php?id=" . $_POST['id'] . "&proc=View\" />";
        echo "</noscript>";
        exit;
} //==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');
