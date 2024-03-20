<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$contact_id = $_POST['contact_id'];
$assigned_to = $_POST['assigned_to'];

$today = date("Ymd");
$realtoday = date("m d Y");
$datewtime = date("F j, Y, g:i a");

$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
$result = db_query($commqry);
$commqryrw = array_shift($result);
$comm_type = $commqryrw["id"];

if ($_POST['issue'] == 'Attention') {

        $output = "<b>" . $_POST['issue'] . "</b>.  This contact inquiry has been assigned to " . $_POST['assigned_to'] . " by " . $_POST['assigned_to'] . " on " . $datewtime;

        $sql3 = "INSERT INTO ucbdb_contact_crm (contact_id, comm_type, message, message_date, employee) VALUES ( '" . $_POST['assigned_to'] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_POST['employee'] . "')";
        $result3 = db_query($sql3);

        $sql3ud = "UPDATE ucb_contact SET status = 'Attention', employee = '$assigned_to' WHERE id = $contact_id";
        $result3ud = db_query($sql3ud);


        $ee_query = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['assigned_to'] . "'";
        $result = db_query($ee_query);
        $com_ee_query = array_shift($result);
        $com_email = $com_ee_query["email"];

        $to = $com_email;

        $str_email = "The following Contact has been assigned to you:\n\n";
        $str_email .= "\n\n";
        $str_email .= "You may click the link below to view the details.\n\n";
        $str_email .= "http://b2c.usedcardboardboxes.com/contact_status_drill.php?id=" . $_POST['assigned_to'] . "&proc=View";

        $mailheadersadmin = "From: UCB Dashboard System <info@usedcardboardboxes.com>\n";

        mail($to, "Urgent - Contact Record Assigned To You", $str_email, $mailheadersadmin);

        $sqlissue = "SELECT * FROM ucbdb_contact_issue WHERE contact_id = " . $contact_id;
        $resissue = db_query($sqlissue);
        $resissuecount = tep_db_num_rows($resissue);

        if ($resissuecount == 0) {
                $ins_sql = "INSERT INTO ucbdb_contact_issue (contact_id, issue, assigned_to, assigned_by, when_assigned) VALUES ( '" . $_POST['assigned_to'] . "','" . $_POST['issue'] . "','" . $_POST['assigned_to'] . "','" . $_POST['employee'] . "','" . $datewtime . "')";
                db_query($ins_sql);
        }

        if ($resissuecount != 0) {
                $upd_sql = "UPDATE ucbdb_contact_issue SET issue = '" . $_POST['issue'] . "', assigned_to = '" . $_POST['assigned_to'] . "', assigned_by = '" . $_POST['assigned_to'] . "' WHERE contact_id = " . $_POST['assigned_to'];
                db_query($upd_sql);
        }
}

if ($_POST['issue'] == 'OK') {

        $output = "<b>" . $_POST['issue'] . "</b>.  This contact issue has been resolved.  " . $_POST['assigned_to'] . " has updated the status and set the order to OK on " . $datewtime;

        $sql3 = "INSERT INTO ucbdb_contact_crm (contact_id, comm_type, message, message_date, employee) VALUES ( '" . $_POST['assigned_to'] . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_POST['employee'] . "')";
        $result3 = db_query($sql3);

        $sql3up = "UPDATE ucb_contact SET status = 'OK', employee = '$assigned_to' WHERE id = $contact_id";
        $result3up = db_query($sql3up);

        $ee_query = "SELECT * FROM ucbdb_employees WHERE initials = '" . $_POST['assigned_to'] . "'";
        $result = db_query($ee_query);
        $com_ee_query = array_shift($result);
        $com_email = $com_ee_query["email"];

        $upd_sql = "UPDATE ucbdb_contact_issue SET issue = '" . $_POST['issue'] . "', assigned_by = '" . $_POST['assigned_to'] . "' WHERE contact_id = " . $_POST['assigned_to'];
        db_query($upd_sql);
}

echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
        header('Location: contact_status_drill.php?id=' . encrypt_url($_POST['assigned_to']) . '&proc=View');
        exit;
} else {
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"contact_status_drill.php?id=" . encrypt_url($_POST['assigned_to']) . "&proc=View\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=contact_status_drill.php?id=" . encrypt_url($_POST['assigned_to']) . "&proc=View\" />";
        echo "</noscript>";
        exit;
} //==== End -- Redirect
//header('Location: orders.php?id=' . $_POST['assigned_to'] . '&proc=View');
