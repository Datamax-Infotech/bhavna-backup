<?php  
session_start();
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
db();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>B2C Active Order Issues</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<style type="text/css">

	.txtstyle_color
	{
		font-family:arial;
		font-size:12;
		height: 16px; 
		background:#ABC5DF;
	}

	.black_overlay{
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: gray;
		z-index:1001;
		-moz-opacity: 0.8;
		opacity:.80;
		filter: alpha(opacity=80);
	}

	.white_content {
		display: none;
		position: absolute;
		top: 5%;
		left: 10%;
		width: 60%;
		height: 90%;
		padding: 16px;
		border: 1px solid gray;
		background-color: white;
		z-index:1002;
		overflow: auto;
	}
table.table_style {
  margin: 15px 0;
    width: 70%;
    white-space: nowrap;
}
table.table_style tr td{
    padding: 5px;
    font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
    }
    table.table_style tr:nth-child(2) td{
   font-weight: bold;
    }
</style>
</head>

<body>
	<?php 
	$crm_numberof_chr_divheight = 'aut0';
	if((isset($_REQUEST["updatenote"])) && ($_REQUEST["updatenote"]==1))
	   {

			$count=$_REQUEST["cnt"];
			$updatenote=$_REQUEST["updatenote"];
			$orders_id=decrypt_url($_REQUEST["ordersid"]);
			$orderissue_lastnote=$_REQUEST["orderissue_lastnote"];
		    $today = date("Ymd"); 
		    // 
		   //Save log
		   $sql = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $orders_id . "',8,'" . preg_replace( "/'/", "\'", $orderissue_lastnote) . "','" . $today . "','" . $_COOKIE['
		   userinitials'] . "','')";
		   $result = db_query($sql);
		
			$rowcolor = "#E4E4E4"; 
			$dt_view_qry = "SELECT orders.*, b2c_order_issue.* ";
			$dt_view_qry .= "FROM b2c_order_issue Right JOIN orders ON b2c_order_issue.order_id = orders.orders_id  ";
			$dt_view_qry .= "where orders.order_issue = 1 and orders.orders_id=".$orders_id;
			$data_res = db_query($dt_view_qry);
			$data = array_shift($data_res);
				
				$address=""; 
				$orderlist="";
				//
				$orderlist="<table width='100%' cellspacing='1' cellpadding='3'>";
		//
				$orders_id=$data["orders_id"];
				$delivery_street_address = $data["delivery_street_address"];
				$delivery_street_address2 = $data["delivery_street_address2"];
				$address=$delivery_street_address;
				if($delivery_street_address2!="")
				{
					$address.=", ".$delivery_street_address2;
				}
				//
				$order_date = $data["date_purchased"];
				//
				$sql = "SELECT * FROM ucbdb_crm WHERE orders_id = " . $orders_id . " ORDER BY message_date DESC, id DESC limit 1 ";
				$result = db_query($sql);
				$myrowsel = array_shift($result);
				if ($myrowsel["comm_type"] == "10"){
					db_b2c_email_new();
					$query = "select emaildate, fromadd, toadd, ccadd, subject FROM tblemail WHERE unqid =" . $myrowsel["EmailID"];
					$dt_view_eml = db_query($query);
					
					$final_msg = "";
					$email_body_toppart = "";
				    $attstr = "";
					$final_msg_top = "";
					while ($rec_em = array_shift($dt_view_eml)) {
						$query_att = "select attachmentname FROM tblemail_attachment WHERE emailid =" . $myrowsel["EmailID"];
						$dt_view_eml_att = db_query($query_att);
						$attachment_str = "";
						while ($rec_em_att = array_shift($dt_view_eml_att)) {		
							$attachment_str = $attachment_str . "<a style='color:#0000FF' target='_blank' href='emailatt_uploads/" . $myrowsel["EmailID"]."/".$rec_em_att["attachmentname"] . "'>" . $rec_em_att["attachmentname"]."</a>, ";
						}
						$query_att = "select body_txt FROM tblemail_body_txt WHERE email_id =" . $myrowsel["EmailID"];
						$dt_view_eml_att = db_query($query_att);
						while ($rec_em_att = array_shift($dt_view_eml_att)) {		
							$final_msg = $rec_em_att["body_txt"];
						}
						
						$final_msg = preg_replace( "/bgcolor=" . chr(34) . "#E7F5C2" . chr(34) . "/", "", $final_msg );
						$final_msg_top = preg_replace( "/background-color:/", "\ ", $final_msg );
						
						$email_body_toppart = "<b>" . $rec_em["subject"] . "</b> <br/> Date: ". date("m/d/Y h:i:s a", strtotime($rec_em["emaildate"])) . "<br/> From:". $rec_em["fromadd"]. "<br/>";  
						$email_body_toppart .= "To: " . $rec_em["toadd"] ;
						if ($rec_em["ccadd"] != "") {
							$email_body_toppart .= "<br/>Cc: " . $rec_em["ccadd"] ;
						}
						$email_body_toppart .= "<div style='height:1px; background: url(images/singleline.png) repeat-x;'></div>";

						if (trim($attachment_str) == "") {
							$attstr = "" ;
						} else {
							$attstr = 'Attachment: ' . substr($attachment_str, 0, strlen(trim($attachment_str))-1) ."<br/><br/>";
						}
					}
					
					$final_msg_nodivs = strip_tags($final_msg);
					$crm_numberof_chr = 0;
					$tmppos = strlen($email_body_toppart . $attstr . $final_msg_nodivs);
					if ($tmppos > $crm_numberof_chr) {
						$tmpstr = "<br><div style='background-color:#E4E4E4; height:".$crm_numberof_chr_divheight."px; width:400px; overflow-x: hidden; overflow-y: hidden;'>". $final_msg_top . "</div> <br/><a href='#' onclick='displayemail(".$myrowsel["id"].")'>View Complete Email</a> <br/><br/>";
						$tmpstr .= "<div style='display:none;' id='emlmsg".$myrowsel["id"]."'> <a href='javascript:void(0)' onclick=document.getElementById('email_light').style.display='none';>Close Window</a> <br/><br/>";
						$tmpstr .= $email_body_toppart . $attstr . $final_msg . "</div>";

						$last_note = $tmpstr;
					
					}else { 
						$last_note = $email_body_toppart . $attstr . $final_msg ;
					}	
				}else{
					$last_note = $myrowsel["message"];
				}	
				$last_note_date = $myrowsel["timestamp"];
				//
				db();
				$wareh_query = db_query("select * from orders_active_export where orders_id = '" . (int)$orders_id . "'");
				$orders_tracking = array_shift($wareh_query);
				$orders_warehouse_query = db_query("select * from warehouse where warehouse_id = '" . $orders_tracking["warehouse_id"] . "'");
				$orders_warehouse = array_shift($orders_warehouse_query);
				$warehouse_str=$orders_warehouse["name"];
				//
				$orders_products_query = db_query("select orders_products_id, products_id, orders_id, products_name, products_model, products_price, products_tax, products_quantity, final_price from orders_products where orders_id = '" . (int)$orders_id . "'");
		  		while ($orders_products = array_shift($orders_products_query)) {
				$shopify_product_nm = "";

				  $orders_products_query1 = db_query("select * from products_shopify where ucb_products_id = '" . $orders_products["products_id"] . "'");
				  while ($orders_products1 = array_shift($orders_products_query1)) {
					$shopify_product_nm = $orders_products1["product_description"];
				  }
				  if ($shopify_product_nm == ""){
					$shopify_product_nm = $orders_products['products_name'];
				  }
					$orderlist.="<tr><td bgcolor='#bdbdbd' class='order-items'>".$shopify_product_nm."</td></tr>";
				}//end product loop
				$orderlist.="</table>";
				
				//
			?>
			 <tr id="row<?php  echo $count; ?>">
			<td bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo encrypt_url($data["orders_id"])?>&proc=View&searchcrit=<?php echo $data["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["orders_id"]?></font></a></td>
			
			<td bgcolor="<?php echo $rowcolor?>"><?php  echo $data["customers_name"]; ?></td>
			
			<td bgcolor="<?php echo $rowcolor?>"><?php  echo $address; ?></td>
			
			<td bgcolor="<?php echo $rowcolor?>"><?php  echo $warehouse_str; ?></td>
			
			<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo $orderlist; ?></td>
			
			<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo date("m/d/Y H:i:s", strtotime($order_date)); ?></td>

			<td bgcolor="<?php echo $rowcolor?>"><?php  echo $last_note; ?></td>

			<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo date("m/d/Y H:i:s", strtotime($last_note_date)); ?></td>
			
			<td bgcolor="<?php echo $rowcolor?>"><textarea name="orderissue_lastnote<?php  echo $count; ?>" cols="20" id="orderissue_lastnote<?php  echo $count; ?>" style="width:98%;"></textarea></td>
			
			<td align="center" bgcolor="<?php echo $rowcolor?>"><input type="button" name="btnupdate" id="btnupdate" value="Update" onclick="update_note(<?php  echo $count; ?>, <?php  echo $orders_id; ?>)"></td>
		</tr>
	<?php 
	   }
	?>
</body>
</html>
