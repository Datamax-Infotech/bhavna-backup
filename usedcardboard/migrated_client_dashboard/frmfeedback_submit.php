<?php
require("../mainfunctions/database.php");
require ("../mainfunctions/general-functions.php");
$machine_ip = $_SERVER['REMOTE_ADDR'];
db();
db_query("INSERT INTO `clientdashboard_feedback` ( `subject`, `message`, `machine_ip`, companyid, userid) VALUES ('". str_replace("'", "\'" ,$_REQUEST['txtSubject'])."', '". str_replace("'", "\'" ,$_REQUEST['txtMessage'])."', '".$machine_ip."', '". $_REQUEST['hdncompnewid']."', '". $_REQUEST['hdclient_loginid']."')");
$cl_username = "";
if ($_REQUEST["hdclient_loginid"] != "")
{
	$sql = "Select * FROM clientdashboard_usermaster WHERE loginid= '" . $_REQUEST["hdclient_loginid"] . "'";
	$result = db_query($sql);
	while ($rq = array_shift($result)) {
		$cl_username = $rq["user_name"];
	}
}

$message = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
	<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
	</style><style scoped>
	.tablestyle {
	   width:800px;
	}
	@media only screen and (max-width: 768px) {
		.tablestyle {
		   width:98%;
		}
	}
	</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";

$message .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";
			
$message .= "<tr><td>Username : </td><td>".$cl_username."</td></tr>";
$message .= "<tr><td>Company name: </td><td>&nbsp;<a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=".encrypt_url($_REQUEST['hdncompnewid'])."'><span  style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#4472cf;\">".get_nickname_val('', $_REQUEST['hdncompnewid'])."</span></a></td></tr>";
$message .= "<tr><td>Subject: </td><td>".$_REQUEST['txtSubject']."</td></tr>";
$message .= "<tr><td>Message: </td><td>".$_REQUEST['txtMessage']."</td></tr>";

$message.="</table></td></tr></tbody></table></div></body></html>";
$subject = "Feedback Submitted for B2B Customer Portals - ". get_nickname_val('', $_REQUEST['hdncompnewid']);

//prasad@extractinfo.com
//$emlstatus = sendemail_attachment(null, "", "ZacFratkin@UsedCardboardBoxes.com", "", "", "Operations@UsedCardboardBoxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", $subject, $message);
$resp = sendemail_php_function(null, '', "ZacFratkin@UsedCardboardBoxes.com", "", "", "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@UsedCardboardBoxes.com", $subject, $message); 

echo "email_sent";
?>