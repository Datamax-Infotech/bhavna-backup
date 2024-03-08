<?php  
session_start();

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
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
	.order-items{
		font-family: Arial, Helvetica, sans-serif;
		color:"#333333";
		font-size: 12px;
	}
	table tr th{
		font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		font-size: 13px;
	}
	table tr td{
		font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		font-size: 12px;
	}
</style>

</head>

<script language="JavaScript" >
	
	function displayemail(id) {
		document.getElementById("email_light").innerHTML = document.getElementById("emlmsg"+id).innerHTML;
		document.getElementById('email_light').style.display='block';
	}		
	
	function displaytrans_log(cnt, warehouse_id, rec_id)
	{
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
		document.getElementById('light').style.display='block';

		selectobject = document.getElementById("translog"+cnt);				
		n_left = f_getPosition(selectobject, 'Left');
		n_top  = f_getPosition(selectobject, 'Top');
		
		document.getElementById('light').style.left= (n_left - 250) + 'px';
		document.getElementById('light').style.top = n_top - 50 + 'px';
	
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" + xmlhttp.responseText;
				
			}
		}

		xmlhttp.open("GET","displaytrans_log.php?warehouse_id=" + warehouse_id + "&rec_id=" + rec_id , true);
		xmlhttp.send();
	}
		
	function f_getPosition (e_elemRef, s_coord) {
		var n_pos = 0, n_offset,
			e_elem = e_elemRef;

		while (e_elem) {
			n_offset = e_elem["offset" + s_coord];
			n_pos += n_offset;
			e_elem = e_elem.offsetParent;
		}

		e_elem = e_elemRef;
		while (e_elem != document.body) {
			n_offset = e_elem["scroll" + s_coord];
			if (n_offset && e_elem.style.overflow == 'scroll')
				n_pos -= n_offset;
			e_elem = e_elem.parentNode;
		}
		return n_pos;
	}
	
	function show_file_inviewer_pos(filename, formtype, ctrlnm){
		var selectobject = document.getElementById(ctrlnm); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');

		document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
		document.getElementById('light').style.display='block';

		document.getElementById('light').style.left = n_left - 400 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
	}

    function load_all_nocontacted(unqid, compid, scomplist,dt_from,dt_to){
        //alert(scomplist);
        var selectobject = document.getElementById(unqid); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
        
		document.getElementById('light').style.left = n_left + 10 + 'px';
		document.getElementById('light').style.top = n_top + 10 + 'px';
        
        document.getElementById('light').style.width=450+'px';
        document.getElementById('light').style.height=350+'px';
        //
        if (window.XMLHttpRequest)
	   {// code for IE7+, Firefox, Chrome, Opera, Safari

		  xmlhttp=new XMLHttpRequest();
	   }
	   else
	   {
           // code for IE6, IE5
	       xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	   }
	   xmlhttp.onreadystatechange=function()
	   {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		  {
			 document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>"+xmlhttp.responseText;
              //
              document.getElementById('light').style.display='block';
		  }
	   }
        xmlhttp.open("GET","penetration_all_contacted_list.php?showquotedata=1&compid="+compid+"&scomplist="+scomplist+"&dt_from="+dt_from+"&dt_to="+dt_to,true);	
	    xmlhttp.send();
    }
	
    function update_note(cnt, ordersid)
	{
		var orderissue_lastnote = document.getElementById('orderissue_lastnote'+ cnt).value;
		
			
		document.getElementById("row" + cnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						
		
		if (window.XMLHttpRequest)
		{
		  xmlhttp=new XMLHttpRequest();
		}
		else
		{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		  xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				//alert(xmlhttp.responseText);
			  document.getElementById("row" + cnt).innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","report_active_order_issue_update.php?cnt=" + cnt + "&updatenote=1&ordersid=" + ordersid+ "&orderissue_lastnote=" + orderissue_lastnote,true);
		xmlhttp.send();
	}	
	
     
</script>

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>
	
<LINK rel='stylesheet' type='text/css' href='one_style.css' >
<body>
	<div>
	<?php  include("inc/header.php"); ?>
</div>
<div class="main_data_css">
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>

	<div id="email_light" class="white_content_email"></div>
<?php 
	$time = strtotime(Date('Y-m-d'));

	if (date('l',$time) != "Friday") {
		$st_friday = strtotime('last friday', $time);
	} else {
		$st_friday = $time;
	}
	$st_friday_last = '01/01/' . date('Y');
	$st_thursday_last = Date('m/d/Y');
	
	$crm_numberof_chr = 0; $crm_rows_per_page =0; $crm_numberof_chr_divheight = 0;
	$sqlt_crm = "SELECT * FROM tblvariable";
	$result_crm = db_query($sqlt_crm, db_b2c_email_new());
	while ($myrowselt_crm = array_shift($result_crm)) {
		if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_numberof_chr"))
		{
			$crm_numberof_chr = $myrowselt_crm["variablevalue"];
		}
		if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_rows_per_page"))
		{
			$crm_rows_per_page = $myrowselt_crm["variablevalue"];
		}
		if (strtoupper($myrowselt_crm["variablename"]) == strtoupper("crm_numberof_chr_divheight"))
		{
			$crm_numberof_chr_divheight = $myrowselt_crm["variablevalue"];
		}
	}	
	
	$crm_numberof_chr = 100;
	$crm_numberof_chr_divheight = 80;
	db();
?>

<h3>B2C Active Order Issues</h3>

<!--<div><i>Red rows means >5 days as Order Issue</i></div>-->

<div >
	Note: Wait for <font color="red">Report</font> to complete, use the Sort option after the Report is completed.</div>
	<br><br>
<form name="frmnewpage" id="frmnewpage" method="post" action="">
	<table width="1330px" cellpadding="4" cellspacing="1">
		
		<tr>
			<?php 
			$sorturl="report_active_order_issue.php?act=1";
			?>
			<th bgcolor="#D9F2FF" align="center" width="60px">Order ID <a href="<?php  echo $sorturl; ?>&sort_order_pre=ASC&sort=orderid"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&sort_order_pre=DESC&sort=orderid"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></th>
			
			<th bgcolor="#D9F2FF" align="center" width="100px">Customer Name <a href="<?php  echo $sorturl; ?>&sort_order_pre=ASC&sort=customername"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&sort_order_pre=DESC&sort=customername"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></th>
			
			<th bgcolor="#D9F2FF" align="center" width="130px">Customer Address </th>
			
			<th bgcolor="#D9F2FF" align="center" width="100px">Warehouse Shipped From <a href="<?php  echo $sorturl; ?>&sort_order_pre=ASC&sort=wh_ship_from"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&sort_order_pre=DESC&sort=wh_ship_from"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></th>
			
			<th bgcolor="#D9F2FF" align="center" width="220px">What Ordered</th>
			
			<th bgcolor="#D9F2FF" align="center" width="70px">Date of Order <a href="<?php  echo $sorturl; ?>&sort_order_pre=ASC&sort=order_date"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&sort_order_pre=DESC&sort=order_date"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></th>
			
			<th bgcolor="#D9F2FF" align="center" width="170px">Last Note</th>

			<th bgcolor="#D9F2FF" align="center" width="100px">Last Note Date <a href="<?php  echo $sorturl; ?>&sort_order_pre=ASC&sort=last_note_date"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&sort_order_pre=DESC&sort=last_note_date"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></th>

			<th bgcolor="#D9F2FF" align="center" width="170px">Last Note Update</th>
			
			<th bgcolor="#D9F2FF" align="center" width="10px"></th>
		</tr>
		<?php 
		if(!isset($_REQUEST["sort"]))
		{
		?>
		<?php 
			$rowcolor = "#E4E4E4"; $count=0;
			$dt_view_qry = "SELECT orders.*, b2c_order_issue.* ";
			$dt_view_qry .= "FROM b2c_order_issue Right JOIN orders ON b2c_order_issue.order_id = orders.orders_id  ";
			$dt_view_qry .= "where orders.order_issue = 1 ";
			$dt_view_qry .= " order by order_issue_start_date_time asc";
			$data_res = db_query($dt_view_qry, db() );
			while ($data = array_shift($data_res)) {
				$count=$count+1;
				$address=""; $orderlist="";
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
				$result = db_query($sql, db() );
				$myrowsel = array_shift($result);
				if ($myrowsel["comm_type"] == "10"){
					$query = "select emaildate, fromadd, toadd, ccadd, subject FROM tblemail WHERE unqid =" . $myrowsel["EmailID"];
					$dt_view_eml = db_query($query , db_b2c_email_new() );
					while ($rec_em = array_shift($dt_view_eml)) {
						$query_att = "select attachmentname FROM tblemail_attachment WHERE emailid =" . $myrowsel["EmailID"];
						$dt_view_eml_att = db_query($query_att , db_b2c_email_new() );
						while ($rec_em_att = array_shift($dt_view_eml_att)) {		
							$attachment_str = $attachment_str . "<a style='color:#0000FF' target='_blank' href='emailatt_uploads/" . $myrowsel["EmailID"]."/".$rec_em_att["attachmentname"] . "'>" . $rec_em_att["attachmentname"]."</a>, ";
						}

						$final_msg = "";
						$query_att = "select body_txt FROM tblemail_body_txt WHERE email_id =" . $myrowsel["EmailID"];
						$dt_view_eml_att = db_query($query_att , db_b2c_email_new() );
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
					
					$tmppos = strlen($email_body_toppart . $attstr . $final_msg_nodivs);
					if ($tmppos > $crm_numberof_chr) {
						$tmpstr = "<br><div style='background-color:#E4E4E4; height:".$crm_numberof_chr_divheight."px; width:400px; overflow-x: hidden; overflow-y: hidden;'>". $final_msg_top . "</div> <br/><a href='#' onclick='displayemail(".$myrowsel["id"].")'>View Complete Email</a> <br/><br/>";
						$tmpstr .= "<div style='display:none;' id='emlmsg".$myrowsel["id"]."'> <a href='javascript:void(0)' onclick=document.getElementById('email_light').style.display='none';>Close Window</a> <br/><br/>";
						$tmpstr .= $email_body_toppart . $attstr . $final_msg . "</div>";

						$last_note = $tmpstr;
					
					}else { 
						$last_note = $email_body_toppart . $attstr . $final_msg ;
					}		
					db();
				}else{
					$last_note = $myrowsel["message"];
				}	
				$last_note_date = $myrowsel["timestamp"];
				//
				
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
					
	  ?>								
		
	  <tr id="row<?php  echo $count; ?>">
			<td bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $data["orders_id"]?>&proc=View&searchcrit=<?php echo $data["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["orders_id"]?></font></a></td>
			
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
				//
				$MGArray[] = array('orders_id' => $orders_id, 'customers_name' => $data["customers_name"], 'address' => $address, 'warehouse_str' => $warehouse_str, 'orderlist' => $orderlist, 'order_date' => $order_date,
				'last_note' => $last_note, 'last_note_date' => $last_note_date);	
			}
				$_SESSION['sortarrayn'] = $MGArray;	
		}
		if(isset($_REQUEST["sort"]))
		{
			$MGArray = $_SESSION['sortarrayn'];
                        
			 if($_REQUEST['sort'] == "orderid")
			 {
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
					$MGArraysort_I[] = $MGArraytmp['orders_id'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
				}

			}
			if($_REQUEST['sort'] == "customername")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['customers_name'];
				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,SORT_STRING,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,SORT_STRING,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "wh_ship_from")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['warehouse_str'];

				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
				}
			}
			if($_REQUEST['sort'] == "order_date")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['order_date'];

				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
				}
			}
			
			if($_REQUEST['sort'] == "last_note_date")
			{
				$MGArraysort_I = array();

				foreach ($MGArray as $MGArraytmp) {
				$MGArraysort_I[] = $MGArraytmp['last_note_date'];

				}

				if ($_REQUEST['sort_order_pre'] == "ASC"){
					array_multisort($MGArraysort_I,SORT_ASC,$MGArray); 
				}
				if ($_REQUEST['sort_order_pre'] == "DESC"){
					array_multisort($MGArraysort_I,SORT_DESC,$MGArray); 
				}
			}
			//
			$rowcolor = "#E4E4E4"; $count=0;
			foreach ($MGArray as $MGArraytmp2) {
				$count=$count+1;
			?>
			<tr id="row<?php  echo $count; ?>">
				<td bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $MGArraytmp2["orders_id"]?>&proc=View&searchcrit=<?php echo $MGArraytmp2["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["orders_id"]?></font></a></td>

				<td bgcolor="<?php echo $rowcolor?>"><?php  echo $MGArraytmp2["customers_name"]; ?></td>

				<td bgcolor="<?php echo $rowcolor?>"><?php  echo $MGArraytmp2["address"]; ?></td>

				<td bgcolor="<?php echo $rowcolor?>"><?php  echo $MGArraytmp2["warehouse_str"]; ?></td>

				<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo $MGArraytmp2["orderlist"]; ?></td>

				<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo date("m/d/Y H:i:s" , strtotime($MGArraytmp2["order_date"])); ?></td>

				<td bgcolor="<?php echo $rowcolor?>"><?php  echo $MGArraytmp2["last_note"]; ?></td>

				<td align="center" bgcolor="<?php echo $rowcolor?>"><?php  echo date("m/d/Y H:i:s", strtotime($MGArraytmp2["last_note_date"])); ?></td>

				<td bgcolor="<?php echo $rowcolor?>"><textarea name="orderissue_lastnote<?php  echo $count; ?>" id="orderissue_lastnote<?php  echo $count; ?>" style="width:98%;"></textarea></td>

				<td align="center" bgcolor="<?php echo $rowcolor?>"><input type="button" name="btnupdate" id="btnupdate" value="Update" onclick="update_note(<?php  echo $count; ?>, <?php  echo $MGArraytmp2["orders_id"]; ?>)"></td>

			</tr>
			<?php 
			//
		}
	}//End if isset sort
		?>
	</table>
</form>

</div>
</body>

</html>