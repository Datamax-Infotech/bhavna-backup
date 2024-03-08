<?php
	$b_debugmode = 1; // 0 || 1
	
	function dateDiff($start, $end) {
	  $start_ts = strtotime((string) $start);
	  $end_ts = strtotime((string) $end);
	  $diff = $start_ts-$end_ts ;
	  return number_format(abs($diff / 86400));
	}
	
	function showarrays_new($p)
	{
		$z = "";
		$count = count($p);
		for ($i = 0; $i < $count - 1; $i++) {
			$z .= $p[$i] . ", ";
		}
		$z .=  $p[$i];
		return $z;
	}

	function FixFilename($strtofix)
	{ //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL

		$strtofix = preg_replace(  '#<#m', "_", (string) $strtofix );

		$strtofix = preg_replace(  '#\'#m', "_", (string) $strtofix );	

		$strtofix = preg_replace(  '###m', "_", (string) $strtofix );
		
		$strtofix = preg_replace(  '# #m', "_", (string) $strtofix );

		$strtofix = preg_replace(  '#(
)#m', "<BR>", (string) $strtofix );

		return $strtofix;

	}
	
	function redirect($a)
	{
		if (!headers_sent()){   
			header('Location: ' . $a); exit;
		}
		else
		{
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"". $a. "\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $a . "\" />";
				echo "</noscript>"; exit;
		}
	}
	
	function get_nickname_val($warehouse_name, $b2bid){
		$nickname = "";
		if ($b2bid > 0) {
			db_b2b();
			$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
			$result_comp = db_query($sql);
			while ($row_comp = array_shift($result_comp)) {
				if ($row_comp["nickname"] != "") {
					$nickname = $row_comp["nickname"];
				}else {
					$tmppos_1 = strpos((string) $row_comp["company"], "-");
					if ($tmppos_1 != false)
					{
						$nickname = $row_comp["company"];
					}else {
						if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
						{
							$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
						}else { $nickname = $row_comp["company"]; }
					}
				}
			}
			db();
		}else {
			$nickname = $warehouse_name;
		}
		
		return $nickname;
	}	

	function sendemail_attachment($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message) {
		$uid = md5(uniqid(time()));    
		$header = "From: ".$from_name." <".$from_mail.">\r\n";	
		$header.= "Cc: " . $scc . "\r\n";	
		$header.= "Bcc: " . $sbcc. "\r\n";    	
		
		if (count($files) > 0)	{	
			$header .= "MIME-Version: 1.0\r\n";		
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n";		
			$header .= "This is a multi-part message in MIME format.\r\n";		
			$header .= "--".$uid."\r\n";	
		}		
		
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";	
		$header .= "Content-Transfer-Encoding: 7bit\r\n";	
		$header .= $message."\r\n";		
		
		for($x=0;$x<count($files);$x++)	{
			$file_size = filesize($path.$files[$x]);		
			$handle = fopen($path.$files[$x], "rb");		
			$content = fread($handle, $file_size);		
			fclose($handle);		
			$content = chunk_split(base64_encode($content));		
			$header .= "--".$uid."\r\n";		
			$header .= "Content-Type: application/octet-stream; name=\"". $files[$x] ."\"\r\n";
			$header .= "Content-Transfer-Encoding: base64\r\n";		
			
			$header .= "Content-Disposition: attachment; filename=\"". $files[$x] ."\"\r\n";		
			
			$header .= $content."\r\n";		
			$header .= "\n";	
		}		
		if (count($files) > 0)	{		$header .= "--".$uid."--";	}    
		
		if (mail((string) $mailto, (string) $subject, "", $header)) {       return "emailsend";    } else {       return "emailerror";    }	
	}	
	
	function sendemail_attachment_new($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message) {
		$uid = md5(uniqid(time()));    
		$header = "From: ".$from_name." <".$from_mail.">\r\n";	
		$header.= "Cc: " . $scc . "\r\n";	
		$header.= "Bcc: " . $sbcc. "\r\n";    	
		
		if (count($files) > 0)	{	
			$header .= "MIME-Version: 1.0\r\n";		
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n";		
			$header .= "This is a multi-part message in MIME format.\r\n";		
			$header .= "--".$uid."\r\n";	
		}		
		
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";	
		$header .= "Content-Transfer-Encoding: 7bit\r\n";	
		if (count($files) > 0)	{	
		
		}else{
			$header .= $message."\r\n";		
		}	
		
		$nmessage = "";
		
		for($x=0;$x<count($files);$x++)	{
			$file_size = filesize($path.$files[$x]);		
			$handle = fopen($path.$files[$x], "rb");		
			$content = fread($handle, $file_size);		
			fclose($handle);		
			$content = chunk_split(base64_encode($content));		
			$nmessage = $message."\r\n";		
			$nmessage .= "--".$uid."\r\n";		
			$nmessage .= "Content-Type: application/octet-stream; name=\"". $files[$x] ."\"\r\n";
			$nmessage .= "Content-Transfer-Encoding: base64\r\n";		
			
			$nmessage .= "Content-Disposition: attachment; filename=\"". $files[$x] ."\"\r\n";		
			
			$nmessage .= $content."\r\n";		
			$nmessage .= "\n";	
		}		
		if (count($files) > 0)	{		$nmessage .= "--".$uid."--";	}    
		
		if (mail((string) $mailto, (string) $subject, $nmessage, $header)) {       return "emailsend";    } else {       return "emailerror";    }	
	}	
	
	function get_result_new( $Statement) {
		$RESULT = [];
		$Statement->store_result();
		for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
			$Metadata = $Statement->result_metadata();
			$PARAMS = [];
			while ( $Field = $Metadata->fetch_field() ) {
				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
			}
			call_user_func_array( [$Statement, 'bind_result'], $PARAMS );
			$Statement->fetch();
		}
		return $RESULT;
	}
	
function db_query($query, $param_type = [], $param_data = []) {
    $link = 'db_link';
    global ${$link};

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $param_type = implode('', $param_type); // Join array elements with a string
    $a_params = array_merge([$param_type], $param_data); // Merge two arrays

    $resultnew = ${$link}->prepare($query) or tep_db_error($query, mysqli_errno(${$link}), mysqli_error(${$link}));

    if ($param_type){
        $a_params_ref = array_map(function(&$value) { return $value; }, $a_params); // Create references
        call_user_func_array([$resultnew, 'bind_param'], $a_params_ref);
    }

    if (!$resultnew->execute()) echo "Execute failed: (" . $resultnew->errno . ") " . $resultnew->error;

    $result = get_result_new($resultnew);

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        $result_error = mysqli_error(${$link});
        error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
}
  
	function tep_db_num_rows($db_query) {
		return  sizeof($db_query);
	}
  
	  function tep_db_close($link = 'db_link') {
		global ${$link};

		//return mysql_close($$link);
		return mysqli_close(${$link});
	  }

	  function tep_db_error($query, $errno, $error): never { 
		die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
	  }

	  function tep_db_insert_id() {
		$link = 'db_link';
		global ${$link};
		return mysqli_insert_id(${$link});
	  }
	  
	  function db_query_old( $query ){
		global $b_debugmode;
	 
	  // Perform Query
	  $result = db_query($query, db());

	  // Check result
	  // This shows the actual query sent to MySQL, and the error. Useful for debugging.
	  if (!$result) {
		if($b_debugmode){
		  $message  = '<b>Invalid query:</b><br>' . mysql_error() . '<br><br>';
		  die($message);
		}

		raise_error('db_query_error: ' . $message);
	  }
	  return $result;
	}

	function raise_error( $message ){
		global $system_operator_mail, $system_from_mail;

		$serror=
		"timestamp: " . Date('m/d/Y H:i:s') . "\r\n" .
		"script:    " . $_SERVER['PHP_SELF'] . "\r\n" .
		"error:     " . $message ."\r\n\r\n";

		// open a log file and write error
		$fhandle = fopen( '/logs/errors'.date('Y-m-d h-m-s').'.txt', 'a' );
		if($fhandle){
		  fwrite( $fhandle, $serror );
		  fclose(( $fhandle ));
		 }

		// e-mail error to system operator
		//if(!$b_debugmode)
		  //mail($system_operator_mail, 'error: '.$message, $serror, 'From: ' . $system_from_mail );
	}

	
function sendemail($hasattachment_flg, $mailto, $scc, $sbcc, $from_mail, $from_name, $subject, $eml_message) {    
	$uid = md5(uniqid(time()));    
	$header = "From: ".$from_name." <".$from_mail.">\n";	
	$header.= "Cc: " . $scc . "\n";	
	$header.= "Bcc: " . $sbcc. "\n";    	
	$message = "";
	if ($hasattachment_flg == "yes")	{
		$header .= "MIME-Version: 1.0\n";		
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\n\n";		

		$message = "--".$uid."\n";	
		$message .= "Content-type:text/html; charset=iso-8859-1\n";	
		$message .= "Content-Transfer-Encoding: 7bit\n\n";	
		
		$message .= $eml_message."\n";		
		
		foreach ($_FILES as $fieldName => $file) {
			for ($i=0;$i < count($file['tmp_name']);$i++) {
				if (is_uploaded_file($file['tmp_name'][$i])) {
					$file_handle = fopen($file["tmp_name"][$i], "rb");
					$file_name = $file["name"][$i];
					$file_size = filesize($file["tmp_name"][$i]);
					$content = fread($file_handle, $file_size);		
					fclose($file_handle);		

					$content = chunk_split(base64_encode($content));		

					$message .= "--".$uid."\n";		

					$message .= "Content-Type: application/octet-stream; name=\"". $file["name"][$i] ."\"\n"; 		

					$message .= "Content-Transfer-Encoding: base64\n";		
					$message .= "Content-Disposition: attachment; filename=\"". $file["name"][$i] ."\"\n\n";		
					$message .= $content."\n";		
					//$message .= "\n\n";	
				}
			}
		}			
	}else{
		$header .= "MIME-Version: 1.0\n";		
		$header .= "Content-Type: text/html; boundary=\"".$uid."\"\n\n";		

		$message = $eml_message."\n";		
	}	
	
	if ($hasattachment_flg == "yes")	{
		$message .= "--".$uid."--";	
	}   
	
	if (mail((string) $mailto, (string) $subject, $message, $header)) {       
		return "emailsend";    
	} else {       
		return "emailerror";    
	}
}	

	function sendemail_php_function($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message) 
	{
		//Code to send mail
		require 'phpmailer/PHPMailerAutoload.php';

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
		if ($mailto != ""){
			$cc_flg = "";
			$tmppos_1 = strpos((string) $mailto, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , (string) $mailto);

				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->addAddress($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos((string) $mailto, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , (string) $mailto);

				foreach ($cc_ids1 as $cc_ids_tmp2){
					if ($cc_ids_tmp2 != "") {
						$mail->addAddress($cc_ids_tmp2);
						$cc_flg = "y";
					}	
				}
			}	

			if ($cc_flg == ""){
				$mail->addAddress($mailto, $mailto);
			}	
		}			

		if ($sbcc != ""){
			$cc_flg = "";

			$tmppos_1 = strpos((string) $sbcc, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , (string) $sbcc);
				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->AddBCC($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos((string) $sbcc, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , (string) $sbcc);
				foreach ($cc_ids1 as $cc_ids_tmp2){
					if ($cc_ids_tmp2 != "") {
						$mail->AddBCC($cc_ids_tmp2);
						$cc_flg = "y";
					}	
				}
			}				

			if ($cc_flg == ""){
				$mail->AddBCC($sbcc, $sbcc);
			}	
		}			

		if ($scc != ""){
			$cc_flg = "";
			$tmppos_1 = strpos((string) $scc, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , (string) $scc);

				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->AddCC($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos((string) $scc, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , (string) $scc);
				foreach ($cc_ids1 as $cc_ids_tmp2){
					if ($cc_ids_tmp2 != "") {
						$mail->AddCC($cc_ids_tmp2);

						$cc_flg = "y";
					}	
				}
			}	

			if ($cc_flg == ""){
				$mail->AddCC($scc, $scc);
			}	

		}	
		if($files!="null")
		{
			for($x=0;$x<count($files);$x++)	{
			 $mail->addAttachment($path . $files[$x]);
		  }		
		}
		
		
		$mail->IsHTML(true);
		$mail->Encoding = 'base64';
		$mail->CharSet = "UTF-8";
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		if(!$mail->send()) {
			return 'emailerror';
		} else {
			return 'emailsend';
		}	
	}	
?>
