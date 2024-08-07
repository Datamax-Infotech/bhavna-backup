<?php
	$b_debugmode = 1; // 0 || 1

	function timeAgo($timestamp){
		$datetime1=new DateTime("now");
		$datetime2=date_create($timestamp);
		$diff=date_diff($datetime1, $datetime2);
		$timemsg='';
		if($diff->y > 0){
			$timemsg = $diff->y .' year'. ($diff->y > 1?"'s":'');

		}
		else if($diff->m > 0){
		 $timemsg = $diff->m . ' month'. ($diff->m > 1?"'s":'');
		}
		else if($diff->d > 0){
		 $timemsg = $diff->d .' day'. ($diff->d > 1?"'s":'');
		}
		else if($diff->h > 0){
		 $timemsg = $diff->h .' hour'.($diff->h > 1 ? "'s":'');
		}
		else if($diff->i > 0){
		 $timemsg = $diff->i .' minute'. ($diff->i > 1?"'s":'');
		}
		else if($diff->s > 0){
		 $timemsg = $diff->s .' second'. ($diff->s > 1?"'s":'');
		}

		$timemsg = $timemsg.' ago';
		return $timemsg;
	}

	function timestamp_to_datetime_mysqli($d)
	{
		$da = explode(" ",$d);
		$dp = explode("-", $da[0]);
		$dh = explode(":", $da[1]);
		
		$x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

		if ($dh[0] == 12) {
			$x = $x . " " . ($dh[0] - 0) . ":" . $dh[1] . "PM CT";
		} elseif ($dh[0] == 0) {
			$x = $x . " 12:" . $dh[1] . "AM CT";
		}
		elseif ($dh[0] > 12) {
		$x = $x . " " . ($dh[0] - 12) . ":" . $dh[1] . "PM CT";
		} else {
		$x = $x . " " . ($dh[0] ) . ":" . $dh[1] . "AM CT";
		}
		
		return $x;
	}

	function number_of_working_dates($from, $days) {
		$workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
		$holidayDays = ['*-12-25', '*-01-01', '2013-12-24', '2013-12-25']; # variable and fixed holidays

		$ret_dates = "";
		$from = new DateTime($from);
		while ($days) {
			$from->modify('+1 day');

			if (!in_array($from->format('N'), $workingDays)) continue;
			if (in_array($from->format('Y-m-d'), $holidayDays)) continue;
			if (in_array($from->format('*-m-d'), $holidayDays)) continue;

			$ret_dates = $from->format('Y-m-d');
			$days--;
		}
		return $ret_dates;
	}

	
	function dateDiff($start, $end) {
	  $start_ts = strtotime($start);
	  $end_ts = strtotime($end);
	  $diff = $start_ts-$end_ts ;
	  return number_format(abs($diff / 86400));
	}
	
	function amt_roundup($float, $dec = 2){
		if ($dec == 0) {
			if ($float < 0) {
				return floor($float);
			} else {
				return ceil($float);
			}
		} else {
			$d = pow(10, $dec);
			if ($float < 0) {
				return floor($float * $d) / $d;
			} else {
				return ceil($float * $d) / $d;
			}
		}
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

		$strtofix = str_replace("<", "_", $strtofix );

		$strtofix = str_replace("'", "_", $strtofix );	

		$strtofix = str_replace("#", "_", $strtofix );
		
		$strtofix = str_replace(" ", "_", $strtofix );

		$strtofix = str_replace("(\n)", "<BR>", $strtofix );

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
					$tmppos_1 = strpos($row_comp["company"], "-");
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
		
		if (mail($mailto, $subject, "", $header)) {       return "emailsend";    } else {       return "emailerror";    }	
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
		
		if (mail($mailto, $subject, $nmessage, $header)) {       return "emailsend";    } else {       return "emailerror";    }	
	}	
	
	function get_result_new( $Statement) {
		$RESULT = array();
		$Statement->store_result();
		for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
			$Metadata = $Statement->result_metadata();
			$PARAMS = array();
			while ( $Field = $Metadata->fetch_field() ) {
				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
			}
			call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
			$Statement->fetch();
		}
		return $RESULT;
	}
	
	function db_query($query, $param_type = array(), $param_data = array()) {
		$link = 'db_link';
		global $$link;

		if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		  error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
		}

		/* Bind parameters. Types: s = string, i = integer, d = double,  b = blob */
		$a_param_type = $param_type; $a_bind_params = $param_data;
		$a_params = array();
		 
		$param_type = '';
		$n = count($a_param_type);
		for($i = 0; $i < $n; $i++) {
		  $param_type .= $a_param_type[$i];
		}
		 
		/* with call_user_func_array, array params must be passed by reference */
		$a_params[] = & $param_type;
		for($i = 0; $i < $n; $i++) {
		  /* with call_user_func_array, array params must be passed by reference */
		  $a_params[] = & $a_bind_params[$i];
		}

		$resultnew = $$link->prepare($query) or tep_db_error($query, mysqli_errno($$link), mysqli_error($$link));
		//$resultnew->bind_param('i',$page_id);
		if ($param_type){
		
			call_user_func_array(array($resultnew, 'bind_param'), $a_params);
			//echo "<br>";
			//print_r($a_params);
		}
		
		//$resultnew->execute();
		if (!$resultnew->execute()) echo "Execute failed: (" . $resultnew->errno . ") " . $resultnew->error;
		
		$result = get_result_new($resultnew);

		if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		   $result_error = mysqli_error($$link);
		   error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
		}

		return $result;
	}
  
	function tep_db_num_rows($db_query) {
		return  sizeof($db_query);
	}
  
	  function tep_db_close($link = 'db_link') {
		global $$link;

		//return mysql_close($$link);
		return mysqli_close($$link);
	  }

	  function tep_db_error($query, $errno, $error) { 
		die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
	  }

	  function tep_db_insert_id() {
		$link = 'db_link';
		global $$link;
		return mysqli_insert_id($$link);
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
	
	if (mail($mailto, $subject, $message, $header)) {       
		return "emailsend";    
	} else {       
		return "emailerror";    
	}
}	

	function convertto_loops($ID)
	{
		$sql = "SELECT * FROM companyInfo where ID = " . $ID . " ";
		$result = db_query($sql, db_b2b() );

		while ($myrowsel = array_shift($result)) {
		
			if ($myrowsel["haveNeed"] == "Need Boxes"){
				$tmp_rec_type = "Supplier";
				$tmp_bs_status = "Buyer";
			}		

			if ($myrowsel["haveNeed"] == "Water"){
				$tmp_rec_type = "Water";
				$tmp_bs_status = "Water";
			}		
			
			if ($myrowsel["haveNeed"] == "Have Boxes"){
				$tmp_rec_type = "Manufacturer";
				$tmp_bs_status = "Seller";
			}		
			$tmp_company = preg_replace("/'/", "\'", $myrowsel["company"]);
			$tmp_address = preg_replace("/'/", "\'", $myrowsel["address"]);
			$tmp_address2 = preg_replace("/'/", "\'", $myrowsel["address2"]);
			$tmp_city = preg_replace("/'/", "\'", $myrowsel["city"]);
			$tmp_contact = preg_replace("/'/", "\'", $myrowsel["contact"]);
			
			$tmp_state = preg_replace("/'/", "\'", $myrowsel["state"]);
			$tmp_phone = preg_replace("/'/", "\'", $myrowsel["phone"]);
			$tmp_accounting_contact = preg_replace("/'/", "\'", $myrowsel["accounting_contact"]);
			$tmp_accounting_phone = preg_replace("/'/", "\'", $myrowsel["accounting_phone"]);
			
			//$tmp_company = preg_replace ( "/'/", "\'", $_REQUEST["company"]);
			//echo $tmp_company;
			
			$strQuery = "Insert into loop_warehouse (b2bid, company_name, company_address1, company_address2, company_city, company_state, company_zip, company_phone, company_email, company_contact, " ; 
			$strQuery = $strQuery . " warehouse_name, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_zip, " ;
			$strQuery = $strQuery . " warehouse_contact, warehouse_contact_phone, warehouse_contact_email, warehouse_manager, warehouse_manager_phone, warehouse_manager_email, " ;
			$strQuery = $strQuery . " dock_details, warehouse_notes, " ;
			$strQuery = $strQuery . " rec_type, bs_status, overall_revenue_comp, noof_location, accounting_email, accounting_contact, accounting_phone) " ;
			$strQuery = $strQuery . " values(" . $ID  . ", '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
			$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '" . $tmp_phone . "', " ;
			$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '" . $tmp_contact . "', '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
			$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '', '" . $tmp_phone . "', " ;
			$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '', '', '', '', '', " ;
			$strQuery = $strQuery . " '" . $tmp_rec_type . "', '" . $tmp_bs_status . "', '" . $myrowsel["overall_revenue_comp"] . "', '" . $myrowsel["noof_location"] . "', '" . $myrowsel["accounting_email"] . "', '" . $tmp_accounting_contact . "', '" . $tmp_accounting_phone . "') " ;
			
			$res = db_query($strQuery , db());
			//echo $strQuery;
			$new_loop_id = tep_db_insert_id();
			db_query("Update companyInfo set loopid = " . $new_loop_id . " where ID = " . $ID, db_b2b() );
			
			$sql = "SELECT inventory.id as b2bid FROM boxes inner join inventory on inventory.id = boxes.inventoryid where boxes.inventoryid > 0 and boxes.companyid = " . $ID . " ";
			$result_box = db_query($sql, db_b2b() );

			while ($myrowsel_box = array_shift($result_box)) {
				$sql = "SELECT id FROM loop_boxes where b2b_id = " . $myrowsel_box["b2bid"] . " ";
				$result_box_loop = db_query($sql,db() );

				while ($myrowsel_box_loop = array_shift($result_box_loop)) {
					$sql = "Insert into loop_boxes_to_warehouse (loop_boxes_id, loop_warehouse_id ) SELECT " . $myrowsel_box_loop["id"] . ", " . $new_loop_id;
					//echo $sql . "</br>";
					$result_box_loop_ins = db_query($sql,db() );
				}
			}
		}
		
		
	}


	function sendemail_php_function($files, $path, $mailto, $scc, $sbcc, $from_mail, $from_name, $replyto, $subject, $message) 
	{
		//Code to send mail
		require_once 'phpmailer/PHPMailerAutoload.php';

		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = 'smtp.office365.com';
		$mail->Port       = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth   = true;
		$mail->Username = "ucbemail@usedcardboardboxes.com";
		$mail->Password = "#UCBgrn4652";
		$mail->SetFrom($from_mail, $from_name);
		$mail->addReplyTo($replyto, $from_name);
		
		//
		if ($mailto != ""){
			$cc_flg = "";
			$tmppos_1 = strpos($mailto, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , $mailto);

				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->addAddress($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos($mailto, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , $mailto);

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

			$tmppos_1 = strpos($sbcc, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , $sbcc);
				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->AddBCC($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos($sbcc, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , $sbcc);
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
			$tmppos_1 = strpos($scc, ",");
			if ($tmppos_1 != false)
			{
				$cc_ids = explode("," , $scc);

				foreach ($cc_ids as $cc_ids_tmp){
					if ($cc_ids_tmp != "") {
						$mail->AddCC($cc_ids_tmp);
						$cc_flg = "y";
					}	
				}
			}	

			$tmppos_1 = strpos($scc, ";");
			if ($tmppos_1 != false)
			{
				$cc_flg = "";
				$cc_ids1 = explode(";" , $scc);
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
	
function feed_mysqli($a,$b,$c)
{
	db();
	$qry = "INSERT INTO loop_feed SET message = ?, created_by = ?, created_for = ?";
	$res_newtrans = db_query($qry, array("s", "s","s"), array($a, $b, $c));
	return $res_newtrans;
}

function comment_mysqli($ac,$b,$c)
{
	db();
	$qry = "INSERT INTO loop_feed_comments SET cmessage = ? messageid = ? employeeid = ?";
	$res_newtrans = db_query($qry, array("s", "s","s"), array($ac, $b, $c) );
}
//

function send_transactionlog_email($warehouseid, $transid, $rec_type, $buyer_view){
	db();
	$b2bid = 0;
	$sql = "SELECT b2bid, warehouse_name from loop_warehouse where id = ?";
	$result_comp = db_query($sql , array("i"), array($warehouseid));
	while ($row_comp = array_shift($result_comp)) {
		$b2bid = $row_comp["b2bid"];
		$notes_company = get_nickname_val($row_comp["warehouse_name"], $b2bid);
	}

	$po_employee = "";
	$sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
	$result_comp = db_query($sql , array("i"), array($transid));
	while ($row_comp = array_shift($result_comp)) {
		$po_employee = $row_comp["po_employee"];
	}

	if ($po_employee == ""){
		db_b2b();
		$sql = "SELECT assignedto from companyInfo where ID = ?";
		$result_comp = db_query($sql , array("i"), array($b2bid));
		while ($row_comp = array_shift($result_comp)) {
			$assignedto = $row_comp["assignedto"];
		}

		$acc_owner_email = "";
		$sql = "SELECT email FROM employees WHERE employees.status='Active' and employeeID = ?";
		$result_comp = db_query($sql , array("i"), array($assignedto));
		while ($row_comp = array_shift($result_comp)) {
			$acc_owner_email = $row_comp["email"];
		}
	}else{
		db();
		$acc_owner_email = "";
		$sql = "SELECT email FROM loop_employees WHERE status='Active' and initials = ?";
		$result_comp = db_query($sql , array("s"), array($po_employee));
		while ($row_comp = array_shift($result_comp)) {
			$acc_owner_email = $row_comp["email"];
		}
	}
	
	if ($acc_owner_email != ""){
		//
		$sql = "SELECT message,date,loop_employees.name FROM loop_transaction_notes";
		$sql.= " INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id";
		$sql.= " WHERE loop_transaction_notes.company_id = '" .  $warehouseid . "' AND";
		$sql.= " loop_transaction_notes.rec_id = '".$transid."' order by loop_transaction_notes.date DESC" ;
		//echo $sql."<br><br>";
		$result = db_query($sql,db() );

		$tdno=0;
		$str_email = "";
		$str_email = "<html><head></head><body bgcolor='#E7F5C2'><table border=0 align='center' cellpadding='0' width='700px' bgcolor='#E7F5C2'><tr><td ><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td><br>";
		$str_email .= "<table width='700px' cellSpacing='1' cellPadding='3'><tr><th colspan=3>TRANSACTION LOG UPDATES</th></tr>";

		$str_email .= "<tr><td bgColor='#98bcdf' colspan=3><font face='Arial, Helvetica, sans-serif' size='1'><strong>Company Name: <a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=".$b2bid."&show=transactions&warehouse_id=" . $warehouseid . "&rec_type=".$rec_type."&proc=View&searchcrit=&id=" . $warehouseid . "&rec_id=". $transid ."&display=".$buyer_view."'>" . $notes_company . "</a></strong></font></td></tr>";

		$str_email .= "<tr><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Date/Time<strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Employee</strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Notes</strong></font></td></tr>";
		//
		while ($myrowsel = array_shift($result)) {

			$the_log_date = $myrowsel["date"];
			$yearz = substr("$the_log_date", 0, 4);
			$monthz = substr("$the_log_date", 4, 2);
			$dayz = substr("$the_log_date", 6, 2); 
			
			$tdno=$tdno+1;
			if($tdno==1){
				$tdbgcolor="#d1cfce";
			}
			else{
				$tdbgcolor="#e4e4e4";
			}
			//
			//$str_email = "<b>Transaction Log Update</b>:<br>";
			$str_email.= "<tr><td bgColor='".$tdbgcolor."'><font face='Arial, Helvetica, sans-serif' size='1'>".$the_log_date ."</font></td><td bgColor='".$tdbgcolor."'><font face='Arial, Helvetica, sans-serif' size='1'>".$myrowsel['name']."</font></td>";

			$str_email.="<td bgColor='".$tdbgcolor."'><font face='Arial, Helvetica, sans-serif' size='1'>".$myrowsel['message']."</font></td><tr><tr><td height='7px' colspan=4></td></tr>";

			/*$str_email = "<b>Transaction Log Update</b>:<br>";
			$str_email.= "Company name: ". "<a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=".$comp_b2bid."&warehouse_id=" . $warehouseid . "&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id= " . $warehouseid . "&rec_id=". $transid ."&display=".$buyer_view."'>" . $notes_company . "</a><br>";
			$str_email.= "Log Entered By: ".$myrowsel['name']."<br><br/>";
			$str_email.= "Transaction log note: ".$myrowsel['message']."<br>";
			$str_email.= "Transaction log date/time: ".$the_log_date ."<br><br>";*/
		}
		$str_email .= "</table></td></tr><tr><td><p align='center'><img width='650' height='87' src='http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";

		//$emlstatus = sendemail_attachment_new(null, "", $acc_owner_email, "", "", "operations@usedcardboardboxes.com","Admin UCB", "", "Transaction Log Update for " . $notes_company . " - " . $transid , $str_email );

		$emlstatus = sendemail_php_function(null, '', $acc_owner_email, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@usedcardboardboxes.com", "Transaction Log Update for " . $notes_company . " - " . $transid, $str_email); 
	}	
}
//

function maintain_transaction_log($intRecId, $strMessage ){
	$qry = "SELECT loop_warehouse.id FROM loop_warehouse INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.warehouse_id = loop_warehouse.id  WHERE loop_transaction_buyer.id = '" . $intRecId . "'";
	$qryRes = db_query($qry, db());

	while ($qryRow = array_shift($qryRes) ) {	
		$company_id = $qryRow['id'];	
	}
	$rec_type		= 'Supplier';
	$employee_id 	= $_COOKIE['employeeid'];
	
	db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $company_id . "', '".$rec_type."' , '" . $intRecId . "', '" . $strMessage . "', '" . $employee_id . "'", db());
}


function getEstimatedNextLoad($loopId, $warehouseId, $nextLoadAvailableDate, $leadTime, $myrowLeadTime, $txtafterPo, $boxesPerTrailer, $myrowExpectedLoadsPerMo, $st_rowStatusKey, $updateQryAction = 'no' ){
	//echo "<br /> function pg  -> ".$loopId." / ".$warehouseId." / ". $nextLoadAvailableDate." / ". $leadTime." / ". $myrowLeadTime." / ". $txtafterPo." / ". $boxesPerTrailer." / ". $myrowExpectedLoadsPerMo." / ". $st_rowStatusKey ;
	if ($warehouseId == 238 && ($nextLoadAvailableDate != "" && $nextLoadAvailableDate != "0000-00-00")){
		$now_date = time(); // or your date as well
		$next_load_date = strtotime($nextLoadAvailableDate);
		$datediff = $next_load_date - $now_date;
		$no_of_loaddays = round($datediff / (60 * 60 * 24))+1;
		
		if($no_of_loaddays<$leadTime)
		{
			if($myrowLeadTime>1)
			{
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Days</font>";
			}
			else{
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Day</font>";
			}
			
		}
		else{
			if ($no_of_loaddays == -0){
				$estimated_next_load= "<font color=green>0 Day</font>";
			}else{
				//commented on 2 dec 2021 as per teams chat with Zac $estimated_next_load= "<font color=green>" . $no_of_loaddays . " Days</font>";
				$estimated_next_load = $no_of_loaddays . " Days";
			}						
		}
	} else{			
		if ($txtafterPo >= $boxesPerTrailer) {
			//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

			if ($myrowLeadTime == 0){
				$estimated_next_load= "<font color=green>Now</font>";
			}							

			if ($myrowLeadTime == 1){
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Day</font>";
			}							
			if ($myrowLeadTime > 1){
				$estimated_next_load= "<font color=green>" . $myrowLeadTime . " Days</font>";
			}							
		}
		else{
			if (($myrowExpectedLoadsPerMo <= 0) && ($txtafterPo < $boxesPerTrailer)){
				$estimated_next_load= "<font color=red>Never (sell the " . $txtafterPo . ")</font>";
			}else{
				//echo "next_load_available_date - " . $txtafterPo . " - " . $boxesPerTrailer . " - " . $myrowExpectedLoadsPerMo .  "<br>";
				$estimated_next_load=ceil((((($txtafterPo/$boxesPerTrailer)*-1)+1)/$myrowExpectedLoadsPerMo)*4)." Weeks";
			}
		}
	}	

	//echo "In step " . $estimated_next_load . " " . $txtafterPo . " " . $boxesPerTrailer . "<br>";
	if ($txtafterPo == 0 && $myrowExpectedLoadsPerMo == 0 ) {
		$estimated_next_load= "<font color=red>Ask Purch Rep</font>";
	}
	//

	/*$b2b_status = $myrow["b2b_status"];
	$b2bstatuscolor="";
	$st_query="select * from b2b_box_status where status_key='".$b2b_status."'";
	$st_res = db_query($st_query, db() );
	$st_row = array_shift($st_res);
	$b2bstatus_name=$st_row["box_status"];*******/
	if($st_rowStatusKey=="1.0" || $st_rowStatusKey=="1.1" || $st_rowStatusKey=="1.2"){
		$b2bstatuscolor="green";
	}
	elseif($st_rowStatusKey=="2.0" || $st_rowStatusKey=="2.1" || $st_rowStatusKey=="2.2"){
		$b2bstatuscolor="orange";
		$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
	}
	if($updateQryAction == 'yes'){
		db_query("UPDATE loop_boxes SET buy_now_load_can_ship_in = '".$estimated_next_load."' WHERE id = '".$loopId."'", db());
		db_query("UPDATE inventory SET buy_now_load_can_ship_in = '".$estimated_next_load."' WHERE loops_id = '".$loopId."'", db_b2b());
	}
	//echo "<br /> function pg estimated_next_load -> ".$estimated_next_load;
	return $estimated_next_load;
}

function get_territory($state_name){
	$territory="";
	$canada_east=array('NB', 'NF', 'NS','ON', 'PE', 'QC');
	$east=array('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV');
	$south=array('NC','SC','GA','AL','MS','TN','FL');
	$midwest=array('MI','OH','IN','KY');
	$north_central=array('ND','SD','NE','MN','IA','IL','WI');
	$south_central=array('LA','AR','MO','TX','OK','KS','CO','NM');
	$canada_west=array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
	$pacific_northwest=array('WA','OR','ID','MT','WY','AK');
	$west=array('CA','NV','UT','AZ','HI');
	$canada=array();
	$mexico=array('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA');
	$territory_sort=99;	
	if (in_array($state_name, $canada_east, TRUE)) 
	{ 
		$territory="Canada East";
		$territory_sort=1;
	} 
	elseif(in_array($state_name, $east, TRUE))
	{ 
		$territory="East";
		$territory_sort=2;
	} 
	elseif(in_array($state_name, $south, TRUE))
	{ 
		$territory="South";
		$territory_sort=3;
	} 
	elseif(in_array($state_name, $midwest, TRUE))
	{ 
		$territory="Midwest";
		$territory_sort=4;
	} 
	else if(in_array($state_name, $north_central, TRUE))
	{ 
	  $territory="North Central";
		$territory_sort=5;
	} 
	elseif(in_array($state_name, $south_central, TRUE))
	{ 
		$territory="South Central";
		$territory_sort=6;
	} 
	elseif(in_array($state_name, $canada_west, TRUE))
	{ 
		$territory="Canada West";
		$territory_sort=7;
	} 
	elseif(in_array($state_name, $pacific_northwest, TRUE))
	{ 
		$territory=" Pacific Northwest";
		$territory_sort=8;
	} 
	elseif(in_array($state_name, $west, TRUE))
	{ 
		$territory="West";
		$territory_sort=9;
	} 
	elseif(in_array($state_name, $canada, TRUE))
	{ 
		$territory="Canada";
		$territory_sort=10;
	} 
	elseif(in_array($state_name, $mexico, TRUE))
	{ 
		$territory="Mexico";
		$territory_sort=11;
	} 
	
	return $territory;
}


function get_lead_time_v3($g_timing, $box_id_list, $inventory_lead_time, $g_timing_enter_dt)
{
	//To get the Shipsinweek
	$no_of_loads = 0; $shipsinweek = ""; $to_show_rec = ""; $total_no_of_loads = 0;
	if ($g_timing == 4) {
		$to_show_rec = "";
		$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}
			}
			
		}
	}

	//Can ship next month a date range of the 1st day of next month to last day of next month 
	if ($g_timing == 7) {
		$to_show_rec = "";
		$next_month_date = date("Y-m-t");
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_month_date . "')
		order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}
				
			}
			
		}
		//echo "in step 7 " . $to_show_rec . "<br>";	
	}

	//Can ship next month
	if ($g_timing == 8) {
		$to_show_rec = "";
		$next_month_date = date("Y-m-t", strtotime("+1 month"));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date between '" . date("Y-m-1", strtotime("+1 month")) . "' and '" . $next_month_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}								}
		}
	}

	//Enter ship by date = Take user input of 1 date
	if ($g_timing == 9 && $g_timing_enter_dt != '') {
		$to_show_rec = "";
		$next_month_date = date("Y-m-d", strtotime($g_timing_enter_dt));
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and load_available_date <= '" . $next_month_date . "' order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}								
			}
		}
	}

	if ($g_timing == 6) {
		$to_show_rec = "";
		$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				if (($inventory_lead_time > $shipsinweek_org) || ($shipsinweek_org < 0)){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				//echo $inv["ID"] . " | " . $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . "|" . $shipsinweekval . " <br>";
				
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}

	if ($g_timing == 5) {
		$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
		$to_show_rec = "";
		$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
		and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
		//echo $dt_view_qry . "<br>";
		$dt_view_res_box = db_query($dt_view_qry, db() );
		while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
			if ($dt_view_res_box_data["trans_rec_id"] == 0 ){ 
				$no_of_loads = $no_of_loads + 1;
				$to_show_rec = "y";	
			}	
			$total_no_of_loads = $total_no_of_loads + 1;
			
			if ($no_of_loads == 1){
				$now_date = time();
				$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
				$datediff = $next_load_date - $now_date;
				$shipsinweek_org = round($datediff / (60 * 60 * 24));
				//echo $inventory_lead_time . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
				if ($inventory_lead_time > $shipsinweek_org){
					$shipsinweekval = $inventory_lead_time;
				}else{
					$shipsinweekval = $shipsinweek_org;
				}
				if ($shipsinweekval == 0) { $shipsinweekval = 1; }
				if ($shipsinweekval >= 10){
					$shipsinweek = round($shipsinweekval / 7) . " weeks";
				}
				if ($shipsinweekval >= 2 && $shipsinweekval < 10){
					$shipsinweek = $shipsinweekval . " days";
				}
				if ($shipsinweekval == 1){
					$shipsinweek = $shipsinweekval . " day";
				}
			}
		}
	}	
	
	return $shipsinweek;
}

function getOwerHeadshotForMeeting($headShot, $emp_txt){
	if($headShot!="" && $headShot!="UCBZW_Logo_200px.png"){
		$emp_img="https://loops.usedcardboardboxes.com/images/employees/".$headShot;
		$emp_txt='&nbsp';
	}else{
		$emp_img="";
		if(strlen($emp_txt)>2){
			$emp_txt =strtoupper(substr(implode('',preg_split("/[\s,_-]+/", $emp_txt)),0,2));
		}
	}
	return array('emp_img'=>$emp_img,'emp_txt'=>$emp_txt);
}

// // Encryption and Decryption
function new_dash_encrypt($encryptValue){
    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $cryption_iv = '1234567891011121';
    $cryption_key = "U1C!2B3l4@o5o#6p7";

    $encryption = openssl_encrypt($encryptValue, $ciphering,
            $cryption_key, $options, $cryption_iv);
        
    return $encryption;
}

function new_dash_decrypt($decryptValue){
    
    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;
    $cryption_iv = '1234567891011121';
    $cryption_key = "U1C!2B3l4@o5o#6p7";

    $decryption = openssl_decrypt ($decryptValue, $ciphering, 
        $cryption_key, $options, $cryption_iv);

    return $decryption;
}
?>