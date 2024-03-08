<?php  
session_start();

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

db();
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Show B2C Bad Address Issues</title>
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

<script language="JavaScript" >
	
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
	
    //for quoted list
    function load_all_noquoted(unqid, compid, scomplist,dt_from,dt_to){
        //alert(unqid);
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
        xmlhttp.open("GET","penetration_all_quoted_list.php?showquotedata=1&compid="+compid+"&scomplist="+scomplist+"&dt_from="+dt_from+"&dt_to="+dt_to,true);	
	    xmlhttp.send();
    }
	
    //For sold list
    function load_all_nosold(unqid, compid, scomplist, dt_from,dt_to){
       // alert(scomplist);
        var selectobject = document.getElementById(unqid); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
        
		document.getElementById('light').style.left = 710 + 'px';
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
        xmlhttp.open("GET","penetration_all_sold_list.php?showquotedata=1&compid="+compid+"&scomplist="+scomplist+"&dt_from="+dt_from+"&dt_to="+dt_to,true);	
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

<?php 
	$time = strtotime(Date('Y-m-d'));

	if (date('l',$time) != "Friday") {
		$st_friday = strtotime('last friday', $time);
	} else {
		$st_friday = $time;
	}
	$st_friday_last = '01/01/' . date('Y');
	$st_thursday_last = Date('m/d/Y');
	
	$date_from_val = date("Y-m-d", strtotime($st_friday_last));
	$date_to_val = date("Y-m-d", strtotime($st_thursday_last));
	
?>

<h3>Show B2C Bad Address Issues</h3>

	<form method="post" name="shippingtool" action="report_bad_address_list.php">
		<table border="0"><tr>
			<td>Date Range Selector:</td>
			<td>
				From: 
					<input type="text" name="date_from" id="date_from" size="10" value="<?php  echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : $st_friday_last; ?>" > 
					<a href="#" onclick="cal2xx.select(document.shippingtool.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
				To: 
					<input type="text" name="date_to" id="date_to" size="10" value="<?php  echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : $st_thursday_last; ?>" > 
					<a href="#" onclick="cal2xx.select(document.shippingtool.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
			</td>
			<td>
				&nbsp;
				<!-- Show only Order Issue Records<input type="checkbox" name="chkshow_order_issue" id="chkshow_order_issue" value="yes" <?php  if ($_REQUEST["chkshow_order_issue"] == "yes") { echo " checked ";}?> /> -->
				<input type="submit" name="btntool" value="Submit" />
				<input type="hidden" name="hd_pgpost" id="hd_pgpost" value=""/>
			</td>
			</tr>
		</table>
	</form>
	
<div >
Note: Wait for <font color="red">Report</font> to complete, use the Sort option after the Report is completed.</div>

<?php  

	$in_dt_range = "no";
	if( $_REQUEST["date_from"] !="" && $_REQUEST["date_to"] !=""){
		$date_from_val = date("Y-m-d", strtotime($_REQUEST["date_from"]));
		$date_to_val = date("Y-m-d", strtotime($_REQUEST["date_to"]));
		$in_dt_range = "yes";
	}

	/*function dateDiff($start, $end) {
	  $start_ts = strtotime($start);
	  $end_ts = strtotime($end);
	  $diff = $start_ts-$end_ts ;
	  return number_format(abs($diff / 86400));
	}*/
	
function getreportdata($eid, $so_val, $sk_val, $dt_from, $dt_to, $onlyactive_orders)
{
	global $tot_lead , $tot_lead_assign, $tot_lead_not_assign, $tot_contact, $tot_quotes_sent,$tot_deal_made;
	
	$tmpdisplay_flg = "n";
?>
	<table width="1200px" border="0" cellspacing="1" cellpadding="1" >
		<tr>
			<td colspan="15" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Order Issues Report</b></font></td>
		</tr>
        <?php 
        $sorturl="report_bad_address_list.php?date_from=".$_REQUEST['date_from']."&date_to=".$_REQUEST['date_to'] ."&onlyactive_orders=".$_REQUEST['onlyactive_orders'];
        ?>
		<tr>
			<td width="50px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order ID&nbsp;<a href="<?php  echo $sorturl; ?>&order_id=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&order_id=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>
			
			<td width="150px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Name&nbsp;<a href="<?php  echo $sorturl; ?>&comp_name=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&comp_name=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>
			
			<td width="20px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Amount&nbsp;
			<a href="<?php  echo $sorturl; ?>&po_amount=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&po_amount=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>

			<td width="20px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Bad Address&nbsp;
			<a href="<?php  echo $sorturl; ?>&bad_add=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&bad_add=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>

			<td width="50px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
			Fedex search resp State&nbsp;<a href="<?php  echo $sorturl; ?>&fedex_search_resp_state=asc&sort=y">
			<img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&fedex_search_resp_state=desc&sort=y">
			<img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>

			<td width="50px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
			Fedex search resp classification&nbsp;<a href="<?php  echo $sorturl; ?>&fedex_search_resp_classification=asc&sort=y">
			<img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&fedex_search_resp_classification=desc&sort=y">
			<img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
				
		</tr>
<?php 

//	if (tep_db_num_rows($data_res) > 0) {

	$rowcolor = "#E4E4E4";

	if(!isset($_REQUEST["sort"]))
	{
		$dt_view_qry = "SELECT * ";
		$dt_view_qry .= "FROM orders ";
		$dt_view_qry .= "where fedex_validate_bad_add = 1 and date_purchased between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
		$dt_view_qry .= " order by orders.orders_id";
		//echo "<br/>" . $dt_view_qry . "<br/><br/>";
		$data_res = db_query($dt_view_qry, db() );
	
		$forbillto_sellto = ""; $cnt = 0;
		while ($data = array_shift($data_res)) {
			//echo "sdfsd".$data["order_issue_reason_id"];
			
			$trans_log_mark = ""; $trans_log_mark_date = ""; $employee_id_mark = 0; $employee_id_unmark = 0;
			$trans_log_unmark = ""; $trans_log_unmark_date = "";
			
			$order_amount = 0;
			$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $data["orders_id"];
			$t_sql_1_res = db_query($t_sql_1,db() );
			while ($t_sql_1_row = array_shift($t_sql_1_res)) {
				$order_amount = number_format($t_sql_1_row["value"],2);
			}			
			
			$order_add = $data["delivery_street_address"] . " " . $data["delivery_street_address2"] . ", " . $data["delivery_city"] . " " . $data["delivery_state"] . " " . $data["delivery_postcode"];
			?>		
				<tr valign="middle">
					<td width="50px" bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $data["orders_id"]?>&proc=View&searchcrit=<?php echo $data["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["orders_id"]?></font></a></td>
					
					<td width="150px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $data["customers_name"]; ?></font></td>
					
					<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$<?php echo $order_amount?></font></td>

					<td width="200px" bgcolor="<?php echo $rowcolor?>" align="left"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $order_add?></font></td>
					
					<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $data["fedex_search_resp_state"];?></font></td>

					<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $data["fedex_search_resp_classification"];?></font></td>
						
				</tr>

			<?php 
				$cnt = $cnt + 1;
			
			$MGArray_data[] = array('fedex_search_resp_state' => $data["fedex_search_resp_state"], 'order_add' => $order_add,
			'fedex_search_resp_classification' => $data["fedex_search_resp_classification"], 
			'orders_id' => $data["orders_id"], 'name' => $data["customers_name"], 'orderamount' => $order_amount);		
		}
		
		$_SESSION['sortarrayn'] = $MGArray_data;	 
	}
	
		if(isset($_REQUEST["sort"]))
		{
			$MGArray_data = $_SESSION['sortarrayn'];
			
			foreach ($MGArray_data as $key => $row) { 
				$vc_array_compid[$key] = $row['orders_id']; 
				$vc_array_nickname[$key] = $row['name']; 
				$vc_array_po_poorderamount[$key] = $row['orderamount']; 
				$vc_array_fedex_search_resp_state[$key] = $row['fedex_search_resp_state']; 
				$vc_array_fedex_search_resp_classification[$key] = $row['fedex_search_resp_classification']; 
				$vc_array_order_add = $row['order_add']; 
			}
			
			//order_issue_start_date_time' => $data["order_issue_start_date_time"], 'order_issue_end_date_time'
			
			if($_REQUEST["compid"]=="asc"){
				array_multisort($vc_array_compid, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["compid"]=="desc"){
				array_multisort($vc_array_compid, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["comp_name"]=="asc"){
				array_multisort($vc_array_nickname, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["comp_name"]=="desc"){
				array_multisort($vc_array_nickname, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["order_id"]=="asc"){
				 array_multisort($vc_array_compid, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["order_id"]=="desc"){
				 array_multisort($vc_array_compid, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["po_amount"]=="asc"){
				 array_multisort($vc_array_po_poorderamount, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["po_amount"]=="desc"){
				array_multisort($vc_array_po_poorderamount, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["bad_add"]=="asc"){
				 array_multisort($vc_array_order_add, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["bad_add"]=="desc"){
				array_multisort($vc_array_order_add, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["fedex_search_resp_state"]=="asc"){
				 array_multisort($vc_array_fedex_search_resp_state, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["fedex_search_resp_state"]=="desc"){
				array_multisort($vc_array_fedex_search_resp_state, SORT_DESC, $MGArray_data);
			}
			elseif($_REQUEST["fedex_search_resp_classification"]=="asc"){
				 array_multisort($vc_array_fedex_search_resp_classification, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["fedex_search_resp_classification"]=="desc"){
				array_multisort($vc_array_fedex_search_resp_classification, SORT_DESC, $MGArray_data);
			}
			 

		   //Display sorted data in the table
		   $unqid =0; $cnt = 0;
		   foreach ($MGArray_data as $MGArraytmp2) { 
			?>
			<tr valign="middle">
				<td width="50px" bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $MGArraytmp2["orders_id"]?>&proc=View&searchcrit=<?php echo $MGArraytmp2["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["orders_id"]?></font></a></td>
				
				<td width="150px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["name"]; ?></font></td>
				
				<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$<?php echo number_format($MGArraytmp2["orderamount"],2)?></font></td>
				
				<td width="200px" bgcolor="<?php echo $rowcolor?>" align="left"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["order_add"]?></font></td>

				<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["fedex_search_resp_state"];?></font></td>
				
				<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["fedex_search_resp_classification"];?></font></td>
				
			</tr>
			<?php 
				$cnt = $cnt + 1;
			 }//End foreach display data
	  
			//
		}//End if (isset($_REQUEST["sort"]))
				 
		echo "</table>";
		
}
	$tot_lead = 0;  $tot_lead_assign = 0; $tot_lead_not_assign = 0; $tot_contact = 0; 

	getreportdata($eid , $_REQUEST["so"], $_REQUEST["sk"], $date_from_val, $date_to_val, $_REQUEST["onlyactive_orders"]); ?>

<?php 	ob_flush();

 ?>	
		</div>
</body>

</html>