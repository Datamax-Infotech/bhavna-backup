<?php  
	require ("inc/header_session.php");
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");

	define('EMAIL_LINEFEED', 'LF');	
	define('EMAIL_TRANSPORT', 'sendmail');	
	define('EMAIL_USE_HTML', 'true');
	
	function send_phpemil_new($from_email, $to_email, $subject, $eml_body)
	{
		global $phpmailer; // define the global variable
		if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) { // check if $phpmailer object of class PHPMailer exists
			require_once '../includes/class.phpmailer.php';
			require_once '../includes/class.smtp.php';

			$phpmailer = new PHPMailer( true );
		}
  	   try {
			$phpmailer->isSMTP();
			$phpmailer->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);			
			$phpmailer->Host = "107.180.85.1"; //smtp.exg7.exghost.com
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port = "25";
			$phpmailer->Username = "ucbemail@UsedCardboardBoxes.com";
			$phpmailer->Password = "boomerang123";
			
			$phpmailer->From = $from_email;
			$phpmailer->FromName = $from_email;
			$phpmailer->Subject = $subject; // subject
			$phpmailer->SingleTo = true;
			$phpmailer->ContentType = 'text/html'; // Content Type
			$phpmailer->IsHTML( true );
			$phpmailer->CharSet = 'utf-8';
			$phpmailer->SMTPDebug = 0;
			$phpmailer->AddAddress($to_email); // the recipient's address
			$phpmailer->Body = $eml_body;
			
			$phpmailer->Send(); // the last thing - send the email
		}
		catch (phpmailerException $e)
		{
			 $msg = "Email Delivery failed -" .  $e->errorMessage();
		} 		
	}
	
	$eml_msg = $_REQUEST["hidden_reply_eml"]; 
 
	//$message = nl2br($eml_msg);
	$message = stripslashes($eml_msg);

	//$message = preg_replace ( "/'/", "'", $message);
	
	$resp = send_phpemil_new($_REQUEST["txtemailfrom"], $_REQUEST["txtemailto"], $_REQUEST["txtemailsubject"], $message);

	$sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $_REQUEST["order_id"] . "', '8', 'Bad Address email sent to " . $_REQUEST["txtemailto"] . "','" . date("Y-m-d H:i:s") . "','" . $_COOKIE['userinitials'] . "','')";		
	$result_crm = db_query($sql,db() );
	
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"orders.php?id=" . $_REQUEST["order_id"] .'&proc=View&searchcrit=&page=0' . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=orders.php?id=" . $_REQUEST["order_id"] .'&proc=View&searchcrit=&page=0' . "\" />";
	echo "</noscript>"; exit;
	
?>
