<?php  
session_start();

require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");


?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Show Only B2C Order Issues</title>
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
	//
	$previous_week = strtotime("-1 week +1 day");

		$start_week = strtotime("last sunday midnight",$previous_week);
		$end_week = strtotime("next saturday",$start_week);

		$start_week = date("m/d/Y",$start_week);
		$end_week = date("m/d/Y",$end_week);

		//echo $start_week.' '.$end_week ;
	//
	
?>

<h3>Show Only B2C Order Issues</h3>

	<form method="post" name="shippingtool" action="report_search_order_issue.php">
		<table border="0"><tr>
			<td>Date Range Selector:</td>
			<td>
				From: 
					<input type="text" name="date_from" id="date_from" size="10" value="<?php  echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : $start_week; ?>" > 
					<a href="#" onclick="cal2xx.select(document.shippingtool.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
				To: 
					<input type="text" name="date_to" id="date_to" size="10" value="<?php  echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : $end_week; ?>" > 
					<a href="#" onclick="cal2xx.select(document.shippingtool.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
					<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
			</td>
			<td>Warehouse:</td>
			<td>	
				<select name="list_warehouse" name="list_warehouse">
					<option value="">ALL</option>
					<option value="hv" <?php  if ($_REQUEST["list_warehouse"] == "hv") { echo " selected ";}?>>HV</option>
					<option value="ha" <?php  if ($_REQUEST["list_warehouse"] == "ha") { echo " selected ";}?>>HA</option>
					<option value="sl" <?php  if ($_REQUEST["list_warehouse"] == "sl") { echo " selected ";}?>>SL</option>
					<option value="la" <?php  if ($_REQUEST["list_warehouse"] == "la") { echo " selected ";}?>>LA</option>
				</select>
			</td>
			<td>
				&nbsp;
				<!-- Show only Order Issue Records<input type="checkbox" name="chkshow_order_issue" id="chkshow_order_issue" value="yes" <?php  if ($_REQUEST["chkshow_order_issue"] == "yes") { echo " checked ";}?> /> -->
				<input type="submit" name="btntool" value="Search" />
				<input type="hidden" name="hd_pgpost" id="hd_pgpost" value=""/>
				<!-- <input type="hidden" name="onlyactive_orders" id="onlyactive_orders" value="<?php  if (isset($_REQUEST["onlyactive_orders"])) { echo $_REQUEST["onlyactive_orders"]; }?>"/> -->
			</td>
			</tr>
		</table>
	</form>
	
<div >
<i>Red rows means >5 days as Order Issue</i></div>

<div >
Note: Wait for <font color="red">Report</font> to complete, use the Sort option after the Report is completed.</div>

<?php  if (isset($_REQUEST["date_from"]) || $_REQUEST["onlyactive_orders"] == "yes") { 

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
        $sorturl="report_search_order_issue.php?date_from=".$_REQUEST['date_from']."&date_to=".$_REQUEST['date_to'] ."&onlyactive_orders=".$_REQUEST['onlyactive_orders'];
        ?>
		<tr>
			<td width="50px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order ID&nbsp;<a href="<?php  echo $sorturl; ?>&order_id=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&order_id=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>
			
			<td width="100px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Warehouse&nbsp;<a href="<?php  echo $sorturl; ?>&warehouse=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&warehouse=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>

			<td width="150px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Name&nbsp;<a href="<?php  echo $sorturl; ?>&comp_name=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&comp_name=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>
			
			<td width="20px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Amount&nbsp;<a href="<?php  echo $sorturl; ?>&po_amount=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&po_amount=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a></font></td>

			<td width="50px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Issue flag Marked By&nbsp;<a href="<?php  echo $sorturl; ?>&employee_id=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&employee_id=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="200px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Notes - when marked as order issue&nbsp;<a href="<?php  echo $sorturl; ?>&trans_log_mark=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&trans_log_mark=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="120px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Marked as order issue on&nbsp;<a href="<?php  echo $sorturl; ?>&order_issue_date=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&order_issue_date=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="50px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Issue flag UnMarked By&nbsp;<a href="<?php  echo $sorturl; ?>&employee_id_unmark=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&employee_id_unmark=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="200px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Notes - when unmarked as order issue&nbsp;<a href="<?php  echo $sorturl; ?>&trans_log_unmark=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&trans_log_unmark=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="100px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">UnMarked as order issue on&nbsp;<a href="<?php  echo $sorturl; ?>&un_order_issue_date=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&un_order_issue_date=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="50px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Days as an order issue&nbsp;<a href="<?php  echo $sorturl; ?>&days_ano_order_issue=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&days_ano_order_issue=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>

			<td width="70px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order issue Estimated Cost&nbsp;<a href="<?php  echo $sorturl; ?>&est_cost=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&est_cost=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>
			<td width="70px" bgcolor="#D9F2FF" align="center"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order issue Reason&nbsp;<a href="<?php  echo $sorturl; ?>&reason=asc&sort=y"><img src="images/sort_asc.png" width="6px;" height="12px;"></a>&nbsp;<a href="<?php  echo $sorturl; ?>&reason=desc&sort=y"><img src="images/sort_desc.png" width="6px;" height="12px;"></a>
                </font></td>

		</tr>
<?php 

//	if (tep_db_num_rows($data_res) > 0) {

	$rowcolor = "#E4E4E4";

	if(!isset($_REQUEST["sort"]))
	{
		
		if ($onlyactive_orders == "yes")
		{
		
			$dt_view_qry = "SELECT orders.*, b2c_order_issue.* ";
			$dt_view_qry .= "FROM b2c_order_issue Right JOIN orders ON b2c_order_issue.order_id = orders.orders_id  ";
			$dt_view_qry .= "where orders.order_issue = 1 ";
			$dt_view_qry .= " order by order_issue_start_date_time asc";
		}else{	
			$dt_view_qry = "SELECT orders.*, b2c_order_issue.* ";
			$dt_view_qry .= "FROM b2c_order_issue Right JOIN orders ON b2c_order_issue.order_id = orders.orders_id  ";
			$dt_view_qry .= "where order_issue_start_date_time between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
			$dt_view_qry .= " order by order_issue_start_date_time asc";
		}
	
		//echo "<br/>" . $dt_view_qry . "<br/><br/>";

		$data_res = db_query($dt_view_qry, db() );
		$ha_count = 0; $hv_count = 0; $la_count = 0; $salt_lake_count = 0; 
		 $forbillto_sellto = ""; $cnt = 0;
		 while ($data = array_shift($data_res)) {
			//echo "sdfsd".$data["order_issue_reason_id"];
			$order_issue_reason = "";
			$data_res1 = db_query("Select * from loop_order_issue_reason where reason_id = '" . $data["order_issue_reason_id"]."'", db() );
			while ($data1 = array_shift($data_res1)) {
				$order_issue_reason = $data1["reason_name"];
			}
			
			$warehouse_str = "";
			$ha_flg = "no"; $hv_flg = "no"; $la_flg = "no"; $sl_flg = "no";
			$data_res1 = db_query("Select * from ucbdb_warehouse", db() );
			while ($data1 = array_shift($data_res1)) {
				if ($data1["tablename"] != "") {
					$data_res2 = db_query("SELECT * FROM " . $data1["tablename"] . " WHERE orders_id = '" . $data["orders_id"] . "' limit 1", db() );
					while ($data2 = array_shift($data_res2)) {
						if ($data1["tablename"] == "orders_active_ucb_hannibal"){
							$ha_count = $ha_count + 1;
							$ha_flg = "yes"; 
						}
						if ($data1["tablename"] == "orders_active_ucb_hunt_valley"){
							$hv_count = $hv_count + 1;
							$hv_flg = "yes"; 
						}
						if ($data1["tablename"] == "orders_active_ucb_los_angeles"){
							$la_count = $la_count + 1;
							$la_flg = "yes"; 
						}
						if ($data1["tablename"] == "orders_active_ucb_salt_lake"){
							$salt_lake_count = $salt_lake_count + 1;
							$sl_flg = "yes"; 
						}
						$warehouse_str = $data1["distribution_center"];
					}	
				}	
			}

			$show_row = "yes";
			if ($_REQUEST["list_warehouse"] != ""){
				$show_row = "no";
				if ($_REQUEST["list_warehouse"] == "ha" && $ha_flg == "yes"){
					$show_row = "yes";
				}
				if ($_REQUEST["list_warehouse"] == "hv" && $hv_flg == "yes"){
					$show_row = "yes";
				}
				if ($_REQUEST["list_warehouse"] == "la" && $la_flg == "yes"){
					$show_row = "yes";
				}
				if ($_REQUEST["list_warehouse"] == "sl" && $sl_flg == "yes"){
					$show_row = "yes";
				}
			}
			
			if ($show_row == "yes"){
				
				$order_amount = 0;
				$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $data["orders_id"];
				$t_sql_1_res = db_query($t_sql_1,db() );
				while ($t_sql_1_row = array_shift($t_sql_1_res)) {
					$order_amount = number_format($t_sql_1_row["value"],2);
				}			
				
				$trans_log_mark = ""; $trans_log_mark_date = ""; $employee_id_mark = 0; $employee_id_unmark = 0;
				$trans_log_unmark = ""; $trans_log_unmark_date = "";

				$emp_initials_mark = $data["order_issue_start_done_by"];
				$trans_log_mark = $data["order_issue_start_notes"];
				
				$order_issue_end_date_time ="";
				if ($data["order_issue_end_date_time"] != "0000-00-00 00:00:00"){
					$order_issue_end_date_time = $data["order_issue_end_date_time"];
				}	
				
					$emp_initials_unmark =  $data["order_issue_end_done_by"];
					$trans_log_unmark = $data["order_issue_end_notes"];
					
					$mark_unmark_day = 0;
					if ($data["order_issue_no_of_days"] > 0){
						$mark_unmark_day = $data["order_issue_no_of_days"];
					}else{				
						$no_of_days = 0;
						$sql_ch = "SELECT order_issue_start_date_time FROM b2c_order_issue where order_id = " . $data["orders_id"] . " and order_issue_end_date_time is null";
						$result_ch = db_query($sql_ch, db() );
						while ($row_ch = array_shift($result_ch)) {
							$datetime1 = date_create($row_ch["order_issue_start_date_time"]); 
							$datetime2 = date_create(date("Y-m-d")); 
							  
							$interval = date_diff($datetime1, $datetime2); 
							  
							$no_of_days = $interval->format('%a'); 			
						}
						
						$mark_unmark_day = $no_of_days;
					}
				
					if ($mark_unmark_day > 5){
						$rowcolor = "#ee735b";
					}else{
						$rowcolor = "#E4E4E4";
					}
					
					if ($mark_unmark_day == 0){
						$datetime1 = date_create($data["order_issue_start_date_time"]); 
						$datetime2 = date_create(date("Y-m-d")); 
						  
						$interval = date_diff($datetime1, $datetime2); 
						  
						$mark_unmark_day = $interval->format('%a'); 			
					}
					
					?>		
				<tr valign="middle">
					<td width="50px" bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $data["orders_id"]?>&proc=View&searchcrit=<?php echo $data["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["orders_id"]?></font></a></td>
					
					<td width="100px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $warehouse_str; ?></font></td>
					
					<td width="150px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $data["customers_name"]; ?></font></td>
					
					<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$<?php echo $order_amount?></font></td>

					<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $emp_initials_mark;?></font></td>
					
					<td width="200px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $trans_log_mark?></font></td>

					<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["order_issue_start_date_time"]?></font></td>

					<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $emp_initials_unmark;?></font></td>

					<td width="200px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $trans_log_unmark?></font></td>

					<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $order_issue_end_date_time?></font></td>
					
					<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $mark_unmark_day?></font></td>
					
					<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $data["order_issue_estimated_cost"]?></font></td>

					<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $order_issue_reason?></font></td>
						
				</tr>

			<?php 
					$cnt = $cnt + 1;
				
				$MGArray_data[] = array('emp_initials_mark' => $emp_initials_mark, 'warehouse_str' => $warehouse_str, 'emp_initials_unmark' => $emp_initials_unmark,
				'order_issue_start_date_time' => $data["order_issue_start_date_time"], 'order_issue_estimated_cost' => $data["order_issue_estimated_cost"], 'order_issue_reason' => $order_issue_reason,
				'order_issue_end_date_time' => $order_issue_end_date_time, 'orders_id' => $data["orders_id"], 'name' => $data["customers_name"], 
				'orderamount' => $order_amount, 'trans_log_mark' => $trans_log_mark, 'trans_log_unmark' => $trans_log_unmark, 
				'mark_unmark_day' => $mark_unmark_day);		
			}
			
			$_SESSION['sortarrayn'] = $MGArray_data;	 
		}	
	}
	
	//if(isset($_REQUEST["sort"])){
		

	//}
	
		if(isset($_REQUEST["sort"]))
		{
			$MGArray_data = $_SESSION['sortarrayn'];
			
			foreach ($MGArray_data as $key => $row) { 
				$vc_array_compid[$key] = $row['orders_id']; 
				$vc_array_nickname[$key] = $row['name']; 
				$vc_array_po_poorderamount[$key] = $row['orderamount']; 
				$vc_array_trans_log_mark[$key] = $row['trans_log_mark']; 
				$vc_array_trans_log_unmark[$key] = $row['trans_log_unmark']; 
				$vc_array_mark_unmark_day[$key] = $row['mark_unmark_day']; 
				$vc_array_employee_id[$key] = $row['emp_initials_mark']; 
				$vc_array_employee_id_unmark[$key] = $row['emp_initials_unmark']; 
				
				$vc_array_warehouse_str[$key] = $row['warehouse_str']; 
			
				$vc_array_estimated_cost[$key] = $row['order_issue_estimated_cost']; 
				$vc_array_reason[$key] = $row['order_issue_reason']; 
			
				$vc_array_mark_on[$key] = $row['order_issue_start_date_time']; 
				$vc_array_un_mark_on[$key] = $row['order_issue_end_date_time']; 
			}
			
			//order_issue_start_date_time' => $data["order_issue_start_date_time"], 'order_issue_end_date_time'
			
			if($_REQUEST["est_cost"]=="asc"){
				array_multisort($vc_array_estimated_cost, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["est_cost"]=="desc"){
				array_multisort($vc_array_estimated_cost, SORT_DESC, $MGArray_data);
			}

			if($_REQUEST["reason"]=="asc"){
				array_multisort($vc_array_reason, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["reason"]=="desc"){
				array_multisort($vc_array_reason, SORT_DESC, $MGArray_data);
			}
	
			if($_REQUEST["order_issue_date"]=="asc"){
				array_multisort($vc_array_mark_on, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["order_issue_date"]=="desc"){
				array_multisort($vc_array_mark_on, SORT_DESC, $MGArray_data);
			}
			if($_REQUEST["un_order_issue_date"]=="asc"){
				array_multisort($vc_array_un_mark_on, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["un_order_issue_date"]=="desc"){
				array_multisort($vc_array_un_mark_on, SORT_DESC, $MGArray_data);
			}
			if($_REQUEST["employee_id"]=="asc"){
				array_multisort($vc_array_employee_id, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["employee_id"]=="desc"){
				array_multisort($vc_array_employee_id, SORT_DESC, $MGArray_data);
			}
			if($_REQUEST["employee_id_unmark"]=="asc"){
				array_multisort($vc_array_employee_id_unmark, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["employee_id_unmark"]=="desc"){
				array_multisort($vc_array_employee_id_unmark, SORT_DESC, $MGArray_data);
			}
			
			if($_REQUEST["warehouse"]=="asc"){
				array_multisort($vc_array_warehouse_str, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["warehouse"]=="desc"){
				array_multisort($vc_array_warehouse_str, SORT_DESC, $MGArray_data);
			}
			
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
				 //print_r($MGArray_parent_child_data);
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
			 
			elseif($_REQUEST["trans_log_mark"]=="asc"){
				array_multisort($vc_array_trans_log_mark, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["trans_log_mark"]=="desc"){
				array_multisort($vc_array_trans_log_mark, SORT_DESC, $MGArray_data);
			}
					
			elseif($_REQUEST["trans_log_unmark"]=="asc"){
				array_multisort($vc_array_trans_log_unmark, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["trans_log_unmark"]=="desc"){
				array_multisort($vc_array_trans_log_unmark, SORT_DESC, $MGArray_data);
			}

			elseif($_REQUEST["days_ano_order_issue"]=="asc"){
			   array_multisort($vc_array_mark_unmark_day, SORT_ASC, $MGArray_data);
			}
			elseif($_REQUEST["days_ano_order_issue"]=="desc"){
			  array_multisort($vc_array_mark_unmark_day, SORT_DESC, $MGArray_data);
			}

		   //Display sorted data in the table
		   $unqid =0; $cnt = 0;
		   foreach ($MGArray_data as $MGArraytmp2) { 
				
		?>
		<tr valign="middle">
			<td width="50px" bgcolor="<?php echo $rowcolor?>"><a target="_blank" href="orders.php?id=<?php echo $MGArraytmp2["orders_id"]?>&proc=View&searchcrit=<?php echo $MGArraytmp2["orders_id"]?>&page=0"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php echo $MGArraytmp2["orders_id"]?></font></a></td>
			
			<td width="150px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["warehouse_str"]; ?></font></td>

			<td width="150px" bgcolor="<?php echo $rowcolor?>"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["name"]; ?></font></td>
			
			<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$<?php echo number_format($MGArraytmp2["orderamount"],2)?></font></td>
			
			<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["emp_initials_mark"];?></font></td>
			
			<td width="200px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["trans_log_mark"];?></font></td>
			
			<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["order_issue_start_date_time"];?></font></td>
			
			<td width="20px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["emp_initials_unmark"];?></font></td>
			
			<td width="200px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["trans_log_unmark"];?></font></td>
			
			<td width="100px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["order_issue_end_date_time"];?></font></td>

			<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["mark_unmark_day"];?></font></td>
			
			<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["order_issue_estimated_cost"];?></font></td>
			
			<td width="50px" bgcolor="<?php echo $rowcolor?>" align="center"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php  echo $MGArraytmp2["order_issue_reason"];?></font></td>
			
		</tr>
		<?php 
				$cnt = $cnt + 1;
			 }//End foreach display data
	  
			//
		}//End if (isset($_REQUEST["sort"]))
				 
		echo "</table>";
		
		$dt_view_qry = "Select id as employee_id, initials from loop_employees where status = 'Active' union Select 0 as employee_id, '' as initials from loop_employees where id = 1 and status = 'Active' order by initials";
		$data_res = db_query($dt_view_qry, db() );

		if (tep_db_num_rows($data_res) > 0) {
			?>
			<table width="250px" border="0" cellspacing="1" cellpadding="1" >
				<tr>
					<td colspan="3" align="center" >&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Order Issues Report - Summary</b></font></td>
				</tr>
				<tr>
					<td width="50px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Employee </font></td>
				
					<td width="100px" bgcolor="#D9F2FF" align="center">
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"># of Order Issues
						</font>
					</td>
					<!-- <td width="200px" bgcolor="#D9F2FF" align="center">
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Count (Transaction Log - when unmarked as order issue)
						</font>
					</td> -->
					<td width="150px" bgcolor="#D9F2FF" align="center">
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Average Order Issue Length
						</font>
					</td>
				</tr>
			<?php 
				$count_mark_all = 0; $count_un_mark = 0; $total_counts_all = 0; $tot_days_all = 0;  
				while ($data = array_shift($data_res)) {
				
					$count_mark_cnt = 0; $total_counts = 0; $count_un_mark_cnt = 0; $mark_unmark_day_tot = 0; $tot_days = 0;
					if ($_REQUEST["onlyactive_orders"] == "yes"){
						$dt_view_qry1 = "SELECT orders.*, b2c_order_issue.* ";
						$dt_view_qry1 .= "FROM b2c_order_issue right JOIN orders ON b2c_order_issue.order_id = orders.orders_id ";
						if ($data["initials"] == ''){
							$dt_view_qry1 .= " where (order_issue_start_done_by = '' or order_issue_start_done_by is null) and orders.order_issue = 1 ";
						}else{
							$dt_view_qry1 .= " where order_issue_start_done_by = '" . $data["initials"]. "' and orders.order_issue = 1 ";
						}						
						$dt_view_qry1 .= " GROUP BY orders.orders_id order by orders.orders_id";
					}else{
						$dt_view_qry1 = "SELECT orders.*, b2c_order_issue.* ";
						$dt_view_qry1 .= "FROM b2c_order_issue right JOIN orders ON b2c_order_issue.order_id = orders.orders_id ";
						if ($data["initials"] == ''){
							$dt_view_qry1 .= " where (order_issue_start_done_by = '' or order_issue_start_done_by is null) and order_issue_start_date_time between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
						}else{
							$dt_view_qry1 .= " where order_issue_start_done_by = '" . $data["initials"]. "' and order_issue_start_date_time between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
						}						
						$dt_view_qry1 .= " GROUP BY orders.orders_id order by orders.orders_id";
					}						
					//echo $dt_view_qry1 . "<br>";
					$data_res1 = db_query($dt_view_qry1, db());
					while ($data_1 = array_shift($data_res1)) {
						$count_mark_cnt = $count_mark_cnt + 1;
						$count_mark_all = $count_mark_all + 1;
						
						if ($data_1["order_issue_no_of_days"] >= 0){
							$total_counts = $total_counts + 1; 
							$total_counts_all = $total_counts_all + 1; 
						}	
						
						if ($data_1["order_issue_no_of_days"] > 0){
							$tot_days = $tot_days + $data_1["order_issue_no_of_days"];
							$tot_days_all = $tot_days_all + $data_1["order_issue_no_of_days"];
						}else{				
							$no_of_days = 0;
							$sql_ch = "SELECT order_issue_start_date_time FROM b2c_order_issue where order_id = " . $data_1["orders_id"] . " and order_issue_end_date_time is null";
							$result_ch = db_query($sql_ch, db() );
							while ($row_ch = array_shift($result_ch)) {
								$datetime1 = date_create($row_ch["order_issue_start_date_time"]); 
								$datetime2 = date_create(date("Y-m-d")); 
								  
								$interval = date_diff($datetime1, $datetime2); 
								  
								$no_of_days = $interval->format('%a'); 			
							}
							
							$tot_days = $tot_days + $no_of_days;
							$tot_days_all = $tot_days_all + $no_of_days;
						}
						
					}
					

					if ($count_mark_cnt > 0 || $count_un_mark_cnt >0){
					
						//echo $data["initials"] . " - " . $tot_days . " - " . $total_counts . "<br>";
				?>
					<tr>
						<td width="50px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
						<?php  
							echo $data["initials"];
						?></font></td>
						
						<td width="100px" bgcolor="#D9F2FF" align="center">
							<?php 	echo "<font size=1>" . $count_mark_cnt . "</font>"; ?></td>	
						
						<!-- <td width="200px" bgcolor="#D9F2FF" align="center">
							<?php 	echo "<font size=1>" . $count_un_mark_cnt . "</font>"; ?>
						</td> -->	
						
						<td width="150px" bgcolor="#D9F2FF" align="center">
							<font size=1><?php  echo number_format($tot_days/$total_counts, 2); ?></font>
						</td>	
					</tr>	
						
					<?php } 
				}
				
					?>
						<tr>
							<td width="50px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><b>
							Operations<b></font></td>
							
							<td width="100px" bgcolor="#D9F2FF" align="center">
								<?php 	echo "<font size=1><b>" . $count_mark_all . "</b></font>"; ?></td>	
							
							<td width="150px" bgcolor="#D9F2FF" align="center">
								<font size=1><b><?php  echo number_format($tot_days_all/$total_counts_all, 2); ?></b></font>
							</td>	
						</tr>	
			</table>
	<?php 
		}		
	?>		
		
		<table width="350px" border="0" cellspacing="1" cellpadding="1" >
			<tr>
				<td colspan="2" align="center" >&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Order Issue Warehouse Summary</b></font></td>
			</tr>
			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Issue Warehouse</font></td>
			
				<td width="100px" bgcolor="#D9F2FF" align="center">
					<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Count
					</font>
				</td>
			</tr>
			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">HV</font></td>
				<td width="100px" bgcolor="#D9F2FF" align="center"><font size=1><?php  echo $hv_count; ?></font></td>	
			</tr>	
			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">HA</font></td>
				<td width="100px" bgcolor="#D9F2FF" align="center"><font size=1><?php  echo $ha_count; ?></font></td>	
			</tr>	
			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">SL</font></td>
				<td width="100px" bgcolor="#D9F2FF" align="center"><font size=1><?php  echo $salt_lake_count; ?></font></td>	
			</tr>	
			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">LA</font></td>
				<td width="100px" bgcolor="#D9F2FF" align="center"><font size=1><?php  echo $la_count; ?></font></td>	
			</tr>	
		</table>	

	<?php 				
		if ($_REQUEST["onlyactive_orders"] == "yes"){
			$dt_view_qry = "SELECT loop_order_issue_reason.reason_name, count(*) as cnt ";
			$dt_view_qry .= "FROM b2c_order_issue right JOIN orders ON b2c_order_issue.order_id = orders.orders_id inner join loop_order_issue_reason on loop_order_issue_reason.reason_id = b2c_order_issue.order_issue_reason_id ";
			$dt_view_qry .= "where orders.order_issue = 1 ";
			$dt_view_qry .= " group by order_issue_reason_id order by order_issue_reason_id";
		}else{
			$dt_view_qry = "SELECT loop_order_issue_reason.reason_name, count(*) as cnt ";
			$dt_view_qry .= "FROM b2c_order_issue right join loop_order_issue_reason on loop_order_issue_reason.reason_id = b2c_order_issue.order_issue_reason_id ";
			$dt_view_qry .= "where order_issue_start_date_time between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
			$dt_view_qry .= " group by order_issue_reason_id order by order_issue_reason_id";
		}			
		
		$data_res = db_query($dt_view_qry, db() );
		?>
			<table width="350px" border="0" cellspacing="1" cellpadding="1" >
				<tr>
					<td colspan="2" align="center" >&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center" bgcolor="#D9F2FF"><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>Order Issue Reason Summary</b></font></td>
				</tr>
				<tr>
					<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Order Issue Reason</font></td>
				
					<td width="100px" bgcolor="#D9F2FF" align="center">
						<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">Count
						</font>
					</td>
				</tr>
			<?php 
		if (tep_db_num_rows($data_res) > 0) {
				$count_tot = 0; 
				while ($data = array_shift($data_res)) {
					$count_tot = $count_tot + $data["cnt"]; 	
				?>
					<tr>
						<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
						<?php  
							echo $data["reason_name"];
						?></font></td>
						
						<td width="100px" bgcolor="#D9F2FF" align="center">
							<font size=1><?php  echo $data["cnt"]; ?></font>
						</td>	
					</tr>	
						
					<?php  
				}
		}		

		if ($_REQUEST["onlyactive_orders"] == "yes"){
			$dt_view_qry = "SELECT count(*) as cnt ";
			$dt_view_qry .= "FROM b2c_order_issue right JOIN orders ON b2c_order_issue.order_id = orders.orders_id  ";
			$dt_view_qry .= "where orders.order_issue = 1 and (b2c_order_issue.order_issue_reason_id is null or b2c_order_issue.order_issue_reason_id = 0) ";
		}else{
			$dt_view_qry = "SELECT count(*) as cnt ";
			$dt_view_qry .= "FROM b2c_order_issue  ";
			$dt_view_qry .= "where (b2c_order_issue.order_issue_reason_id is null or b2c_order_issue.order_issue_reason_id = 0) and order_issue_start_date_time between '" . $dt_from . "' AND '" . $dt_to . " 23:59:59' ";
		}			
		
		$data_res = db_query($dt_view_qry, db() );
//echo $dt_view_qry;
			$noreason_cnt = 0;
			while ($data = array_shift($data_res)) {
				$noreason_cnt = $data["cnt"];
				$count_tot = $count_tot + $noreason_cnt; 
			}
			?>

			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
				No Reason Entered</font></td>
				
				<td width="100px" bgcolor="#D9F2FF" align="center">
					<font size=1><?php  echo $noreason_cnt; ?></font>
				</td>	
			</tr>	

			<tr>
				<td width="250px" bgcolor="#D9F2FF"><font size="1" face="Arial, Helvetica, sans-serif" color="#333333">
				Total:</font></td>
				
				<td width="100px" bgcolor="#D9F2FF" align="center">
					<font size=1><?php  echo $count_tot; ?></font>
				</td>	
			</tr>	
		</table>
	<?php 

}
	?>

<?php 
	$tot_lead = 0;  $tot_lead_assign = 0; $tot_lead_not_assign = 0; $tot_contact = 0; 
?>
	<?php 	

	if((isset($_REQUEST["btntool"])) && ($_REQUEST["btntool"]=="Search"))
	{
		
		getreportdata($eid , $_REQUEST["so"], $_REQUEST["sk"], $date_from_val, $date_to_val, $_REQUEST["onlyactive_orders"]); 
				
		}		
				
	?>

<?php 	ob_flush();
}
 ?>	
</div>
</body>

</html>