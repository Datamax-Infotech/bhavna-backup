<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
require '../ucbloop/phpmailer/PHPMailerAutoload.php';
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
			Dear $customer_name:<br><br>

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

	echo $message;

	//$to = $emailaddress;
	$to = "zacfratkin@usedcardboardboxes.com";
	//$to = "prasad@extractinfo.com";
	$mail->addAddress($to, $to);

	$mail->SetFrom("ucbemail@usedcardboardboxes.com", "Marty Metro");
	//$mail->addAddress("zacfratkin@usedcardboardboxes.com");

	$mail->AddReplyTo("CEO@UsedCardboardBoxes.com", "CEO@UsedCardboardBoxes.com");

	$mail->IsHTML(true);
	$mail->Encoding = 'base64';
	$mail->CharSet = "UTF-8";
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;
	if (!$mail->send()) {
		return 'emailerror';
	} else {
		return "email sent";
	}
}

$resp =	send_email('', 'Test Name', '144 SSS', 4411);

exit;
