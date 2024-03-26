<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
require '../phpmailer/PHPMailerAutoload.php';
define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

function send_email(string $emailaddress, string $customer_name, string $shopify_order_no, float $qualifier): string
{
	//Code to send mail
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = 'smtp.office365.com';
	$mail->Port       = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth   = true;
	$mail->Username = "ucbemail@usedcardboardboxes.com";
	$mail->Password = '#UCBgrn4652';

	$subject = "A Personal Request from the CEO";

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
		</style></head><body style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";

	$message .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100.0%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style='border-collapse:collapse'><tr><td>";

	$message .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align='center'><tr><td><img src='https://www.ucbzerowaste.com/images/logo2.png' width='80px' height='auto'></td><td align='right' valign='bottom'><span style='font-family: Montserrat; font-size:12pt;color:#538135;'><i>Creating profit by reducing waste... with integrity and transparency</i></span></td></tr></table>";

	$message .= "</td></tr><tr><td height='20px' style='border-top:1px solid #538135;'>&nbsp;</td></tr><tr><td>";

	$message .= "<div style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#a6a6a6;\" >ORDER #" . $shopify_order_no . "</div><br>";

	$message .= "<span style=\"width:100%%; font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >A Personal Request from the CEO</span> <br><br>";

	//$message .= "<div style='font-family: Montserrat;font-size:12pt;color:#767171; margin-top:3px;'>Do you mind <a href=\"https://business.usedcardboardboxes.com/survey.php?q=" . $qualifier . "\">clicking here</a> to complete our quick 2 question survey?</div><br>";

	$message .= "<div style='font-family: Montserrat; font-size:12pt;color:#767171; margin-top:3px;'>
			Dear $customer_name,<br><br>

			My name is Marty Metro and I am Founder and CEO of UsedCardboardBoxes & UCBZeroWaste. I sincerely appreciate your business and feel honored by our tens of thousands of customers that believe in supporting good companies trying to do great things. By now you should have received your moving boxes and I am sure you have been busy packing up your home.
			<br><br>

			I would like to personally ask for two minutes of your time to fill out our online survey so that we can continue to improve our products and services. I personally read every survey response and have implemented many suggestions from our customers to date. I love to hear the compliments, but it is the suggestions that help us get even better. I know we are not perfect, but I can assure you it is not for lack of trying!
			<br><br>
			
			Thank you for your time and support!
			<br>
		</div><br>";

	$message .= "<table style='border-spacing: 0; border-collapse: collapse; float: left; margin-right: 15px;'><tr>
		<td style='font-family: Montserrat; height:50px; width:200px; border-radius: 4px;  moz-border-radius: 4px; khtml-border-radius: 4px; o-border-radius: 4px; webkit-border-radius: 4px; ms-border-radius: 4px;' bgcolor='#41b839' align='center'>
		<a href='https://business.usedcardboardboxes.com/survey.php?q=$qualifier' style='font-size: 16px; text-decoration: none; display: block; color: #fff; padding: 20px 25px;'>Take Survey</a></td>
		</tr></table>";

	$signature = "</br></br></br></br><div style='font-family: Montserrat; font-size:12pt;color:#767171; margin-top:3px;'>Best regards, </div>
		<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
	$signature .= "<td><p style='font-family:Montserrat;font-size:13pt;color:#538135'><b><u>Marty Metro</u><br>Founder and CEO</b><br>";
	$signature .= "UsedCardboardBoxes (UCB)</p>";
	$signature .= "<span style='font-family:Montserrat; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
	$signature .= "1-888-BOXES-88<br>";
	$signature .= "</span>";
	$signature .= "</td></tr></table>";

	$message .= $signature;
	$message .= "</td></tr></table></td></tr></tbody></table></div></body></html>";

	//echo $message;

	$to = $emailaddress;
	//$to = "zacfratkin@usedcardboardboxes.com";
	//$to = "prasad@extractinfo.com";
	$mail->addAddress($to, $to);

	$mail->SetFrom("ucbemail@usedcardboardboxes.com", "UsedCardboardBoxes");

	$mail->AddReplyTo("noreply@usedcardboardboxes.com", "noreply@usedcardboardboxes.com");

	$mail->IsHTML(true);
	$mail->Encoding = 'base64';
	$mail->CharSet = "UTF-8";
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;
	if (!$mail->send()) {
		return 'emailerror';
	} else {
		return "Email Sent";
	}
}

/*function sendemail_withattachment_byphpemail_new(array $files, string $path, string $mailto, string $scc, string $sbcc, string $from_mail, string $from_name, string $replyto, string $subject, string $message): string
{	//Code to send mail
	$mail = new PHPMailer(true);
	$mail->isSMTP();
	$mail->Host = 'smtp.office365.com';
	$mail->Port       = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth   = true;
	$mail->Username = "ucbemail@usedcardboardboxes.com";
	$mail->Password = "#UCBgrn4652";
	$mail->SetFrom($from_mail, $from_name);
	//
	if ($mailto != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($mailto, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $mailto);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->addAddress($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($mailto, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $mailto);

			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->addAddress($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->addAddress($mailto, $mailto);
		}
	}

	if ($sbcc != "") {
		$cc_flg = "";

		$tmppos_1 = strpos($sbcc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $sbcc);
			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddBCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($sbcc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $sbcc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddBCC($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddBCC($sbcc, $sbcc);
		}
	}

	if ($scc != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($scc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $scc);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($scc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $scc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddCC($cc_ids_tmp2);

					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddCC($scc, $scc);
		}
	}
	if ($files != "null") {
		for ($x = 0; $x < count($files); $x++) {
			$mail->addAttachment($path . $files[$x]);
		}
	}


	//$mail->addAttachment('quotes/UsedCardboardBoxes_PO_1257427.pdf');
	//$mail->SMTPDebug  = 3;
	//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";}; //$mail->Debugoutput = 'echo';

	$mail->IsHTML(true);
	$mail->Encoding = 'base64';
	$mail->CharSet = "UTF-8";
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;
	if (!$mail->send()) {
		return 'emailerror';
	} else {
		return 'emailsend';
	}
} 
*/

db();
$dt = mktime(0, 0, 0, (int)date('m'), (date('d') - 8), (int)date('Y'));
$query = "SELECT orders_id, customers_name, customers_email_address, shopify_order_display_no FROM orders WHERE survey = 0 AND unix_timestamp(date_purchased) <= $dt";
$run_query = db_query($query);
$qualifier = 0;
while ($row = array_shift($run_query)) {
	$qualifier = ($row["orders_id"] * 7);
	$firstname = $row["customers_name"];
	$array_name = explode(" ", $firstname);
	$firstname = ucfirst(strtolower($array_name[0]));
	$emailaddress = $row["customers_email_address"];
	if ($emailaddress != "") {
		$resp =	send_email($emailaddress, $firstname, $row["shopify_order_display_no"], $qualifier);

		$sql = "INSERT INTO orders_survey_data_log (orders_id, survey_sent,	survey_sent_on) VALUES ( '" . $row["orders_id"] . "','1','" . date("Y-m-d") . "')";
		$result = db_query($sql);

		$update_query = "UPDATE orders SET survey = 1 WHERE orders_id = " . $row["orders_id"];
		$run_update_query = db_query($update_query);
	}
}
$datewtime = date("F j, Y, g:i a");
$ddw_sql = "UPDATE tblvariable SET variablevalue = '$datewtime' where variablename = 'b2c_load_survey_time'";
$ddw_sql_result = db_query($ddw_sql);
echo "All emails have been sent.";
//exit;
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title>Welcome to UsedCardboardBoxes.com - Customer Survey</title>
	<script language="javascript" src="js/common.js"></script>
	<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
	<!-- header //-->
	<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
	<!-- header_eof //-->

	<!-- body //-->
	<table border="0" width="100%" cellspacing="3" cellpadding="3">
		<tr>
			<td width="<?php echo BOX_WIDTH; ?>" valign="top">
				<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
					<!-- left_navigation //-->
					<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
					<!-- left_navigation_eof //-->
				</table>
			</td>
			<!-- body_text //-->
		<tr>
			<td></td>
		</tr>
		<?php
		if (isset($_GET["msg"]) && $_GET["msg"] == 1) {
		?>
			<tr>
				<td class="smlBlackBold" align="center">The Survey Email has been sent.</td>
			</tr>
		<?php
		} else {
		?>
			<tr>
				<td>
					<script language="JavaScript">
						function FormCheck() {
							if (document.SurveyForm.OverallExperience.value == "" | document.SurveyForm.WhatCanWeDoToImproveService.value == "") {
								alert("Please take a moment to enter all questions.  Thank you.");
								return false;
							}
						}
					</SCRIPT>
					<div>
						<?php include("inc/header.php"); ?>
					</div>
					<div class="main_data_css">
						<table width="100%" border="0" cellspacing="3" cellpadding="3">
							<tr>
								<td width="100%" class="xlrgGreenBold">Send Customer Survey</td>
							</tr>
							<tr valign="top">
								<td width="100%">
									<form ACTION="load_survey.php" METHOD="POST" name="SurveyForm" onSubmit="return FormCheck()">
										<input type="hidden" name="q" value="<?php echo $qualifier; ?>">
										<p>Use this page to test the new Survey email. </p>
										<p>Name: <input type="text" name="firstname">
										<p>Email: <input type="text" name="email">
										<p>Order ID: <input type="text" name="orders_id">
										<p><input type="submit" value="Submit"> </p>
									</form>
								</td>
							</tr>
						</table>
				</td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td></td>
		</tr>
		<!-- body_text_eof //-->
		<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
				<!-- right_navigation //-->
				<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
				<!-- right_navigation_eof //-->
			</table>
		</td>
		</tr>
	</table>
	<!-- body_eof //-->

	<!-- footer //-->
	<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
	<!-- footer_eof //-->
	<br>
	</div>
</body>

</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>