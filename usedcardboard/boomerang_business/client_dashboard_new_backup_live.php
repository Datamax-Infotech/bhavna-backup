<? 
session_start();
$sales_rep_login = "no"; $repchk=0;
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
		$repchk=1;
	}	
}else {
	require ("inc/header_session_client.php");
}

$repchk_from_setup = 0;
if (isset($_REQUEST["repchk_from_setup"])) {
	if ($_REQUEST["repchk_from_setup"] == "yes") {
		$repchk_from_setup=1;
	}	
}
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

db();


//$client_loginid = $_COOKIE['loginid'];
$client_companyid = 0;
if (isset($_REQUEST["compnewid"])){
	$client_companyid = decrypt_password($_REQUEST["compnewid"]);
}	

if (isset($_REQUEST["companyid_login"])){
	$client_companyid = $_REQUEST["companyid_login"];
}	

if ($repchk_from_setup == 1){
	$sql = "Insert into clientdashboard_user_log (back_door_login, back_door_compid, userid, login_datetime, ipaddress, client_device_info) values( 1, '" . $client_companyid . "', '" . $_REQUEST["userid"] . "', '" . date("Y-m-d H:i:s") . "' , '" . $_SERVER['REMOTE_ADDR'] . "', '" . str_replace("'", "\'" , $_SERVER['HTTP_USER_AGENT']) . "')";
	$result = db_query($sql );
}

$client_loginid = '';
if(isset($_COOKIE['loginid']) && !empty($_COOKIE['loginid'])){
	$client_loginid = $_COOKIE['loginid'];
}

$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = '" . $client_companyid . "' and section_id = 6 and activate_deactivate = 1" , db()); 
$closed_loop_inv_flg = "no";
while($fetch_data =array_shift($res))
{
	$closed_loop_inv_flg = "yes";
}


$res1 = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = '" . $client_companyid . "' and section_id = 8 and activate_deactivate = 1" , db()); 
$show_boxprofile_inv = "yes";
while($fetch_data =array_shift($res1))
{
	$show_boxprofile_inv = "no";
}

$myusername = ""; $mypassword = "";
$sql = "Select * FROM clientdashboard_usermaster WHERE loginid= '" . $client_loginid . "'";
$result = db_query($sql);
while ($rq = array_shift($result)) {
	$myusername = $rq["user_name"];
	$mypassword = $rq["password"];
}

$sql = "Select * FROM clientdashboard_usermaster WHERE user_name='$myusername' and password='$mypassword' and activate_deactivate = 1";
$result = db_query($sql);
$reccnt = 0;
while ($rq = array_shift($result)) {
	$reccnt = $reccnt + 1;
}

$hdmultiple_acc_flg = 0;
if($reccnt > 1)
{
	$hdmultiple_acc_flg = 1;
}

$hide_bu_now = 0;
$sql_res = db_query("SELECT * FROM clientdashboard_section_details where companyid = '" . $client_companyid . "' and section_id = 7 and activate_deactivate = 1", db());	
while ($row_data = array_shift($sql_res)) 
{	
	$hide_bu_now = 1;
}

//echo "<br /> client_loginid -> ".$client_loginid." / client_companyid ->".$client_companyid." / sales_rep_login -> ".$sales_rep_login;
if (isset($_REQUEST["hd_signout"])) {
	if ($_REQUEST["hd_signout"] == "yes") {
		
		$date_of_expiry = time() - 2 ;
		setcookie( "client_dash_companyid", "" , $date_of_expiry );
		setcookie( "loginid", "", $date_of_expiry );
		
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"index.php\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
		echo "</noscript>"; exit;
		
	}
	
}

if (isset($_REQUEST["hd_chgpwd_upd"])) {
	if ($_REQUEST["hd_chgpwd_upd"] == "yes") {

		$sql="UPDATE clientdashboard_usermaster SET password = '" . $_REQUEST["txt_newpwd"]  . "' WHERE loginid = " . $client_loginid ;
		//echo "<br/>".$sql; exit();
		$result = db_query($sql, db());
		
		$res1 = db_query("SELECT user_name FROM clientdashboard_usermaster WHERE loginid = " . $client_loginid, db()); 
		while($fetch_data1=array_shift($res1)) {
			$strQuery = "UPDATE clientdashboard_usermaster SET password = '".$_REQUEST["txt_newpwd"]."' WHERE user_name = '" . $fetch_data1["user_name"] . "'";
			$result = db_query($strQuery, db());
		}
		
		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"client_dashboard_new.php?compnewid=" . urlencode(encrypt_password($_REQUEST["compnewid_chgpwd"])) . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=client_dashboard_new.php?compnewid=" . urlencode(encrypt_password($_REQUEST["compnewid_chgpwd"])) . "\" />";
		echo "</noscript>"; exit;
		
	}
	
}

if(!empty($client_loginid)){
	$sql="SELECT * FROM clientdashboard_usermaster WHERE loginid = " . $client_loginid ;
	$supplier_pwd = ""; $supplier_email = "";
	$result = db_query($sql , db());
	while ($rq = array_shift($result)) {
		$client_pwd = $rq["password"];
		$client_email = $rq["supplier_email"];
	}
}

db_b2b();
$sql="SELECT loopid, company, haveNeed, shipCity, shipState, assignedto, parent_child FROM companyInfo WHERE ID = '" . $client_companyid . "'";
$client_loopid = 0; $parent_child = "";
$buyer_seller_flg = 0; $warehouseid = 0;
$client_name = ""; $assignedto = "";
$shipCityNm = $shipStateNm = '';
$result = db_query($sql);
while ($rq = array_shift($result)) {
	$parent_child = $rq["parent_child"];
	$client_name = $rq["company"];
	$assignedto = $rq["assignedto"];
	$warehouseid = $rq["loopid"];

	$shipCityNm = $rq["shipCity"];
	$shipStateNm = $rq["shipState"];
	if ($rq["haveNeed"] == "Need Boxes") {
		$buyer_seller_flg = 0;
	}else {
		$buyer_seller_flg = 1;
	}
}

//Commented the code as per Zac request
$child_comp = ""; $child_comp_num=""; 

//This is for New rep #962
$child_comp_new = ""; $child_comp_num_new =""; 
$sql="SELECT ID FROM companyInfo WHERE parent_comp_id = " . $client_companyid ;
$result = db_query($sql);
while ($rq = array_shift($result)) {
	
	db();
	$sql1 = "SELECT id FROM loop_warehouse where b2bid = " . $rq["ID"] . " ";
	$result1 = db_query($sql1 );
	while ($myrowsel1 = array_shift($result1)) {
		$child_comp_new = $child_comp_new . $myrowsel1["id"] . ",";
		$child_comp_num_new=$child_comp_num_new+1;
	}
}
if (trim($child_comp_new) != ""){
	$child_comp_new = substr($child_comp_new, 0, strlen($child_comp_new) - 1);
}

if ($parent_child == "Parent")
{
	$setup_family_tree = 0; 
	$res2 = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = '" . $client_companyid . "' and section_id = 9 and activate_deactivate = 1" , db());
	while($fetch_data = array_shift($res2)){
		$setup_family_tree = 1;
	}
}

if ($parent_child != "Parent"){
	$setup_family_tree = 1;
}

db();
$sql = "SELECT id, warehouse_name FROM loop_warehouse where b2bid = " . $client_companyid . " ";
$result = db_query($sql );
$warehouse_name = "";
while ($myrowsel = array_shift($result)) {
	$client_loopid = $myrowsel["id"];
	$warehouse_name = $myrowsel["warehouse_name"];
}

$displymsg = "no";
if (isset($_REQUEST["hd_chgpwd"])) {
	if ($_REQUEST["hd_chgpwd"] == "yes") {
		$displymsg = "yes";
}}

if ($client_loopid == 0 && $displymsg == "no"){
//	echo "<h1><font color=red>UCB record not found.</font></h1>";
}

$sql="SELECT * FROM clientdashboard_section_details WHERE companyid = $client_companyid and activate_deactivate = 1";
$section_inventory_flg = ""; $section_lastship_flg = ""; $section_poupload_flg = ""; $section_non_inventory_flg = ""; $section_boxrep_flg = "";
$section_bol_flg = "";
$result = db_query($sql );
while ($rq = array_shift($result)) {
	if ($rq["section_id"] == 1) {
		$section_inventory_flg = "yes";
	}
	if ($rq["section_id"] == 2) {
		$section_lastship_flg = "yes";
	}
	if ($rq["section_id"] == 3) {
		$section_poupload_flg = "yes";
	}
	if ($rq["section_id"] == 4) {
		//$section_non_inventory_flg = "yes";
	}
	if ($rq["section_id"] == 5) {
		$section_boxrep_flg = "yes";
	}

	if ($rq["section_id"] == 6) {
		$section_bol_flg = "yes";
	}
	
}

$sql="SELECT * FROM clientdashboard_section_col_details WHERE companyid = $client_companyid and displayflg = 1";
$section_inventory_col1_flg = ""; $section_inventory_col2_flg = ""; $section_inventory_col3_flg = "";
$section_lastship_col1_flg = ""; $section_lastship_col2_flg = ""; $section_lastship_col3_flg = ""; $section_lastship_col4_flg = "";
$section_lastship_col5_flg = ""; $section_lastship_col6_flg = ""; $section_lastship_col7_flg = ""; $section_lastship_col8_flg = ""; 
$result = db_query($sql );
while ($rq = array_shift($result)) {
	if ($rq["section_col_id"] == 1) {
		$section_inventory_col1_flg = "yes";
	}
	if ($rq["section_col_id"] == 2) {
		$section_inventory_col2_flg = "yes";
	}
	if ($rq["section_col_id"] == 3) {
		$section_inventory_col3_flg = "yes";
	}

	if ($rq["section_col_id"] == 4) {
		$section_lastship_col1_flg = "yes";
	}
	if ($rq["section_col_id"] == 5) {
		$section_lastship_col2_flg = "yes";
	}
	if ($rq["section_col_id"] == 6) {
		$section_lastship_col3_flg = "yes";
	}
	if ($rq["section_col_id"] == 7) {
		$section_lastship_col4_flg = "yes";
	}
	if ($rq["section_col_id"] == 8) {
		$section_lastship_col5_flg = "yes";
	}
	if ($rq["section_col_id"] == 9) {
		$section_lastship_col6_flg = "yes";
	}
	if ($rq["section_col_id"] == 10) {
		$section_lastship_col7_flg = "yes";
	}
	if ($rq["section_col_id"] == 11) {
		$section_lastship_col8_flg = "yes";
	}
	
}

$sql="SELECT * FROM clientdashboard_globalvar WHERE variable_name = 'tollfree_no'";
$ucb_tollfree_no = ""; $ucb_off_no = "";
$result = db_query($sql );
while ($rq = array_shift($result)) {
	$ucb_tollfree_no = $rq["variable_value"];
}

$sql="SELECT * FROM clientdashboard_globalvar WHERE variable_name = 'office_no'";
$result = db_query($sql );
while ($rq = array_shift($result)) {
	$ucb_off_no = $rq["variable_value"];
}

$sql="SELECT * FROM clientdashboard_details WHERE companyid = " . $client_companyid ;
$client_logofile = "";
$client_account_mgr = 0;
$result = db_query($sql );
while ($rq = array_shift($result)) {
	$client_account_mgr = $rq["accountmanager_empid"];
	$client_logofile = $rq["logo_image"];
}

db_b2b();
$sql="SELECT * FROM employees WHERE employeeID = '" . $assignedto . "'";
$client_account_mgr_name = ""; $client_account_mgr_eml = "";  $client_account_mgr_initiails = "";
$result = db_query($sql);
while ($rq = array_shift($result)) {
	$client_account_mgr_name = $rq["name"];
	$client_account_mgr_initiails = $rq["initials"];
	$client_account_mgr_eml = $rq["email"];
	$client_account_mgr_loopID = $rq["loopID"];
}

db();
$sql= "Select phoneext from loop_employees where id = '" . $client_account_mgr_loopID . "'"; 
$client_account_mgr_phoneext = ""; 
$result = db_query($sql);
while ($rq = array_shift($result)) {
	$client_account_mgr_phoneext = $rq["phoneext"];
}
error_reporting(0);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html><head>
	<title>UsedCardboardBoxes B2B Customer Portal - <?=$client_name. " - ".$shipCityNm.", ".$shipStateNm ?></title>
	<link rel='stylesheet' type='text/css' href='assets/css/new_header-dashboard.css'>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript">
        var timerStart = Date.now();
       // alert('timerStart -> '+timerStart)
    </script>
	<style type="text/css">
		span.infotxt:hover {text-decoration: none; background: #ffffff; z-index: 6; }
		span.infotxt span {position: absolute; left: -9999px; margin: 20px 0 0 0px; padding: 3px 3px 3px 3px; z-index: 6;}
		span.infotxt:hover span {left: 25%; background: #ffffff;} 
		span.infotxt span {position: absolute; left: -9999px; margin: 1px 0 0 0px; padding: 0px 3px 3px 3px; border-style:solid; border-color:black; border-width:1px;}
		span.infotxt:hover span {margin: 1px 0 0 170px; background: #ffffff; z-index:6;} 

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
			padding: 5px;
			border: 2px solid black;
			background-color: white;
			z-index:1002;
			overflow: auto;
		}

		.style7 {
			font-size: x-small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
			background-color: #5cb726;
		}

		.style5 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			text-align: center;
			background-color: #99FF99;
		}

		.style6 {
			text-align: center;
			background-color: #99FF99;
		}

		.style2 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
		}

		.style3 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
		}

		.style8 {
			text-align: left;
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
		}

		.style11 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: center;
		}

		.style10 {
			text-align: left;
		}

		.style12 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: right;
		}

		.style12center {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: center;
		}

		.style12right {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10;
			color: #333333;
			text-align: right;
		}
		.style_total {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10;
			color: #333333;
			text-align: right;
		}

		.style12left {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: left;
		}

		.style13 {
			font-family: Arial, Helvetica, sans-serif;
		}

		.style14 {
			font-size: x-small;
		}

		.style15 {
			font-size: x-small;
		}

		.style16 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			background-color: #99FF99;
		}

		.style17 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			background-color: #99FF99;
		}

		select, input {
			font-family: Arial, Helvetica, sans-serif; 
			font-size: 12px; 
			color: #000000; 
			font-weight: normal; 
		}
		body{
      font-size: 0.8rem;
    }
    .nav-top-1 {
      border-bottom: 3px solid #f29e00;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding:5px 10px;
      position: relative;
    }
    .nav-top-1 .header_logo{
      width: 20%;
    }

    .nav-top-1 .company_text_header{
      /*color: #f29e00; */
      font-weight: 600;
      margin: 0px;
      font-size: 16px;
    }

    .nav-top-2 {
      background-color: #5CB726;
      color: #FFF;
      font-weight: 500;
      font-size: 1rem;
      padding: 5px;
    }
    .gaylord_link{
      color: #FFF;
      margin-left: 10px;
      font-weight: 400;
    }
     .gaylord_link:hover{
      color: #FFF;
      text-decoration: none;
    }

    .fontDtls {
      font-size: 12px;
      color: #555555;
      font-weight: 600;
    }

    .fontDtls span {
      display: block;
    }

    .header_need_help {
      font-size: 20px;
      font-weight: 500;
      color: #6b9624;
      display: block;
    }

    .btn-topbar {
      padding: 5px 10px;
      border-radius: 10px;
      font-size: 12px;
      border: solid 2px #000000;
      color: #000000;
    }
    .footer_l{
      height: auto;
    }
    .collapse-custom{
      position: absolute;
      background: #f8f9fa;
      top: 45px;
      left: 27px;
      width: 20%;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
	  z-index: 1;
    }
    .collapse-custom a{
      color: #000;
      font-weight: 500;
    }
    .collapse-custom a:hover{
      color: #5CB726;
    }
    .collapse-custom .nav-item{
      border:solid 1px #DDD;
      padding: 0px 20px;
    }
	</style>
	<!-- TOOLTIP STYLE START -->
	
	<link rel="stylesheet" type="text/css" href="css/tooltip_style.css" /> 
	
	
	<!-- TOOLTIP STYLE END -->
	<? if (!isset($_REQUEST["hd_chgpwd"])) { ?>
	<script language="JavaScript" SRC="inc/NewCalendarPopup.js"></script>
	<script language="JavaScript" SRC="inc/general.js"></script>
	<script language="JavaScript">document.write(getCalendarStyles());</script>
	<script language="JavaScript" >
		var cal1xx = new CalendarPopup("listdiv");
		cal1xx.showNavigationDropdowns();
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>
	<? }?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script language="javascript">
		function f_getPosition (e_elemRef, s_coord) {
			var n_pos = 0, n_offset,
				//e_elem = selectobject;
				e_elem = e_elemRef;
			while (e_elem) {
				n_offset = e_elem["offset" + s_coord];
				n_pos += n_offset;
				e_elem = e_elem.offsetParent;
				
			}
			e_elem = e_elemRef;
			//e_elem = selectobject;
			while (e_elem != document.body) {
				n_offset = e_elem["windows" + s_coord];
				if (n_offset && e_elem.style.overflow == 'windows')
					n_pos -= n_offset;
				e_elem = e_elem.parentNode;
			}

			return n_pos;
			
		}

		function setTwoNumberDecimal(e) {
			if (e.value != ""){
				e.value = parseFloat(e.value).toFixed(2);
			}	
		}		
		
		function swicthacc(loginid, client_companyid)
		{
		
			var n_left = f_getPosition(document.getElementById('btnswitchacc'), 'Left');
			var n_top  = f_getPosition(document.getElementById('btnswitchacc'), 'Top');
					
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
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';>Close</a><br>" + xmlhttp.responseText;
					document.getElementById('light').style.display='block';
					
					document.getElementById('light').style.left=n_left - 150 + 'px';
					document.getElementById('light').style.top=n_top + 50 + 'px';
				}
			}
			
			xmlhttp.open("GET","show_account_list.php?loginid=" + loginid + "&client_companyid=" + client_companyid, true);
			xmlhttp.send();			
		}
		
		function show_loading()
		{
			document.getElementById('overlay').style.display='block';
		}
		function show_loading1(arg1, arg_show)
		{
			document.getElementById('overlay').style.display='block';
		
			//$('#submit').click(function(event){ 
			  $("#div_element").load('client_dashboard_new.php?compnewid='+arg1+'&show='+arg_show);  

			//}); 

		}

		function remove_loading() {
			document.getElementById('overlay').style.display='none';
		}

		function display_file_boxdesc(filename, formtype,tmpcnt){
		
			var n_left = f_getPosition(document.getElementById('box_desc'), 'Left');
			var n_top  = f_getPosition(document.getElementById('box_desc'), 'Top');
					
			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
			document.getElementById('light').style.display='block';
			//document.getElementById('fade').style.display='block';
			
			document.getElementById('light').style.left=n_left + 'px';
			document.getElementById('light').style.top=n_top + 'px';
			
			//document.getElementById('light').style.left = '200px';
			//document.getElementById('light').style.top= 50 + 'px';
			
		}

		function display_file(filename, formtype,tmpcnt){
		
			var n_left = f_getPosition(document.getElementById('po_order_show'+tmpcnt), 'Left');
			var n_top  = f_getPosition(document.getElementById('po_order_show'+tmpcnt), 'Top');
					
			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
			document.getElementById('light').style.display='block';
			//document.getElementById('fade').style.display='block';
			
			document.getElementById('light').style.left=n_left - 130 + 'px';
			document.getElementById('light').style.top=n_top + 20 + 'px';
			
			//document.getElementById('light').style.left = '200px';
			//document.getElementById('light').style.top= 50 + 'px';
			
		}
		
		function display_inv_file(filename, formtype,tmpcnt){
		
			var n_left = f_getPosition(document.getElementById('inv_file_show'+tmpcnt), 'Left');
			var n_top  = f_getPosition(document.getElementById('inv_file_show'+tmpcnt), 'Top');
					
			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
			document.getElementById('light').style.display='block';
			//document.getElementById('fade').style.display='block';
			
			document.getElementById('light').style.left=n_left - 380 + 'px';
			document.getElementById('light').style.top=n_top + 20 + 'px';
			
			//document.getElementById('light').style.left = '200px';
			//document.getElementById('light').style.top= 50 + 'px';
			
		}
		
		function display_bol_file(filename, formtype,tmpcnt){
		
			var n_left = f_getPosition(document.getElementById('bol_show'+tmpcnt), 'Left');
			var n_top  = f_getPosition(document.getElementById('bol_show'+tmpcnt), 'Top');
					
			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype +	"</center><br/> <embed src='"+ filename + "' width='800' height='800'>";
			document.getElementById('light').style.display='block';
			//document.getElementById('fade').style.display='block';
			
			document.getElementById('light').style.left=n_left - 430 + 'px';
			document.getElementById('light').style.top=n_top + 20 + 'px';
			
			//document.getElementById('light').style.left = '200px';
			//document.getElementById('light').style.top= 50 + 'px';
			
		}
		
		
		
		function chkchgpwd(){
			if (document.getElementById("txt_oldpwd").value == "") {
				alert("Please enter the old password.");
				document.getElementById("txt_oldpwd").focus();
				return false;
			}
			
			if (document.getElementById("txt_newpwd").value == "") {
				alert("Please enter the New password.");
				document.getElementById("txt_newpwd").focus();
				return false;
			}

			if (document.getElementById("txt_newpwd_re").value == ""){
				alert("Please enter the Re-type password.");
				document.getElementById("txt_newpwd_re").focus();
				return false;
			}

			var str1 = document.getElementById("txt_oldpwd").value;
			var str2 = document.getElementById("hd_chgpwd_val").value;
			var compareval = str1.localeCompare(str2); 
			
			if (compareval != 0 ){
				alert("Entered Old password is incorrect, please check.");
				document.getElementById("txt_oldpwd").focus();
				return false;
			}
			
			var str1 = document.getElementById("txt_newpwd").value;
			var str2 = document.getElementById("txt_newpwd_re").value;
			var compareval = str1.localeCompare(str2); 
			
			if (compareval != 0){
				alert("Entered Re-type password does not match with New password.");
				document.getElementById("txt_newpwd").focus();
				return false;
			}
			
			document.frmchgpwd.submit();
		}
		
		function boxreport(repchk, compnewid, client_loopid){
			var start_date = document.getElementById("start_date").value;
			var end_date = document.getElementById("end_date").value;
			if (document.getElementById("dView")){
				var dView = document.getElementById("dView").value;
			}else{
				var dView = "";
			}				

			if (start_date == ""){
				alert("Please enter the Date From.");
				return false;
			}
			if (end_date == ""){
				alert("Please enter the Date To.");
				return false;
			}
			
			document.getElementById("boxtrailer_rep_div").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />";
			
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
					document.getElementById("boxtrailer_rep_div").innerHTML = xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("GET","client_dashboard_boxreport.php?repchk=" + repchk + "&compnewid=" +compnewid + "&dView=" + dView + "&start_date=" + start_date + "&end_date=" + end_date + "&client_loopid="+ client_loopid, true);
			xmlhttp.send();
		}
		
		function boxreport_trailer(repchk, trailer, id, runningcnt){
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
				    //document.getElementById("boxtrailer_bytrailer_div").innerHTML = xmlhttp.responseText;
					var n_top  = f_getPosition(document.getElementById('boxreport_trailerdiv'+runningcnt), 'Top');
					var n_left = f_getPosition(document.getElementById('boxreport_trailerdiv'+runningcnt), 'Left');
					
					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;" + xmlhttp.responseText;
					document.getElementById('light').style.display='block';
					
					document.getElementById('light').style.left= (n_left - 200) + 'px';
					document.getElementById('light').style.top= n_top + 'px';
				  
				}
			}
			xmlhttp.open("GET","client_dashboard_boxrep_trailer.php?repchk=" + repchk + "&trailer_no=" + trailer + "&trans_rec_id="+ id, true);
			xmlhttp.send();
		}


		function selltotoggle() {
			if(document.getElementById('expand').style.display == 'block'){
		        document.getElementById('expand1').style.display = 'block';
		        document.getElementById('expand').style.display = 'none';
		        document.getElementById("ex_co_div").innerHTML= "Expand";
		    }else{
		        document.getElementById('expand1').style.display = 'none';
		        document.getElementById('expand').style.display = 'block';
		        document.getElementById("ex_co_div").innerHTML = "Collapse";
		    }
		} 

		function shiptotoggle() {
			if(document.getElementById('expand_ship').style.display == 'block'){
				document.getElementById('expand1_ship').style.display = 'block';
				document.getElementById('expand_ship').style.display = 'none';
				document.getElementById("ex_co_div_ship").innerHTML= "Expand";
				window.parent.document.getElementById('show_compinfo').height = '450px';
			} else{
				document.getElementById('expand1_ship').style.display = 'none';
				document.getElementById('expand_ship').style.display = 'block';
				document.getElementById("ex_co_div_ship").innerHTML = "Collapse";
				window.parent.document.getElementById('show_compinfo').height = document.body.scrollHeight;
			}
		}

		function billtotoggle() {
			if(document.getElementById('expand_bill').style.display == 'block'){
				document.getElementById('expand1_bill').style.display = 'block';
				document.getElementById('expand_bill').style.display = 'none';
				document.getElementById("ex_co_div_bill").innerHTML= "Expand";
				window.parent.document.getElementById('show_compinfo').height = '450px';
			}else{
				document.getElementById('expand1_bill').style.display = 'none';
				document.getElementById('expand_bill').style.display = 'block';
				document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
				window.parent.document.getElementById('show_compinfo').height = document.body.scrollHeight;
			}
		}

		function Remove_favorites(favItemId, compnewid){
			show_loading();
			var hdnFavItemsAction = document.getElementById('hdnFavItemsAction').value;

			var repchk_str = document.getElementById('repchk_str').value;
			if (window.XMLHttpRequest) {
			  xmlhttp=new XMLHttpRequest();
			} else {
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				    if(xmlhttp.responseText == 'true'){
				    	window.location.replace("https://boomerang.usedcardboardboxes.com/client_dashboard_new.php?companyid_login="+compnewid+"&show=favorites&"+repchk_str);
				    }
				}
			}
			
			xmlhttp.open("GET","client_dashboard_remove_favitem.php?favItemId=" + favItemId +"&hdnFavItemsAction="+hdnFavItemsAction+"&compnewid="+compnewid+"&repchk=yes", true);
			xmlhttp.send();
		}

	</script >
	<!-- BOX PROFILE SCRIPT START -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function () {
			
			$('#quote_item').change(function(){ 
				
				if($(this).val() != "-1") {
					$('.noDemandEntries').hide();
					$('table.table').hide();
					$('table#table_'+$(this).val()).show();
				}
				if($(this).val() == "-1"){
					$('table.table').hide();
					$('.noDemandEntries').show();
				}
			})
		});
		
		function isNumberKey(evt){
          	var charCode = (evt.which) ? evt.which : evt.keyCode;
          	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
             	return false;
          	return true;
       	}

       	function quote_req_quote_type_chg(){
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}else{
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					document.getElementById("div_quote_request_main").innerHTML=xmlhttp.responseText;
				}
			}
			b2bid = document.getElementById("quote_req_compid").value;
			quote_req_quote_type = document.getElementById("quote_req_quote_type").value;
			alert('b2bid - '+b2bid+' / quote_req_quote_type - '+quote_req_quote_type)
			xmlhttp.open("GET","quote_req_tracker_chg.php?repchk=yes&company_id="+b2bid+"&quote_req_quote_type="+quote_req_quote_type,true);
			xmlhttp.send();
		}

		function show_g_details(gid) {
		    var x = document.getElementById("g_sub_table"+gid);
		    if (x.style.display === "none") {
		        x.style.display = "block";
		        document.getElementById("g_btn"+gid).innerHTML="Collapse Details";
				document.getElementById("g_btn_img"+gid).src="images/minus_icon.png";
		    } else {
		        x.style.display = "none";
		        document.getElementById("g_btn"+gid).innerHTML="Expand Details";
				document.getElementById("g_btn_img"+gid).src="images/plus-icon.png";
		    }
		}
		
		function g_quote_edit(b2bid,tableid,quote_item,client_dash_flg,repchk){
			var p="g";	
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("g"+tableid).innerHTML=xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("POST","quote_request_edit_new.php?editquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&p="+p+"&tableid="+tableid+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}	
		
		function g_quote_delete(tableid,quote_item,companyid,repchk){
		    var choice = confirm('Do you really want to delete this record?');
		    if(choice === true) {
		        var p="g";
		        if (window.XMLHttpRequest)
		        {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp=new XMLHttpRequest();
		        }
		        else
		        {// code for IE6, IE5
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange=function()
		        {
		            if (xmlhttp.readyState==4 && xmlhttp.status==200)
		            {
		                document.getElementById("g"+tableid).innerHTML=xmlhttp.responseText;
		                 $("#show_q_div").load(location.href + " #show_q_div");
		            }
		        }
		            xmlhttp.open("GET","delete_quote_request.php?deletequotedata=1&repchk="+repchk+"&p="+p+"&tableid="+tableid+"&quote_item="+quote_item+"&companyid="+companyid,true);
		            xmlhttp.send();
		    }
		    else{
		        
		    }
		}
       	
		function quote_save(b2bid, repchk){ 
			var g_item_length = document.getElementById("g_item_length").value;
			var g_item_width = document.getElementById("g_item_width").value;
			var g_item_height = document.getElementById("g_item_height").value;
			var g_item_min_height = document.getElementById("g_item_min_height").value;
			var g_item_max_height = document.getElementById("g_item_max_height").value;
			var sales_desired_price_g = document.getElementById("sales_desired_price_g").value;
			var g_shape_rectangular, g_shape_octagonal, g_wall_1, g_wall_2, g_wall_3, g_wall_4, g_wall_5, g_wall_6, g_wall_7, g_wall_8, g_wall_9, g_wall_10, g_no_top, g_lid_top, g_partial_flap_top, g_full_flap_top, g_no_bottom_config, g_partial_flap_w, g_full_flap_bottom, g_tray_bottom,  g_partial_flap_wo, g_vents_okay;

			if(document.getElementById("g_shape_rectangular").checked){
				g_shape_rectangular = document.getElementById("g_shape_rectangular").value;
			}else{
				g_shape_rectangular = "";
			}
			if(document.getElementById("g_shape_octagonal").checked){
				g_shape_octagonal = document.getElementById("g_shape_octagonal").value;
			}else{
				g_shape_octagonal = "";
			}
			if(document.getElementById("g_wall_1").checked){
				var g_wall_1 = document.getElementById("g_wall_1").value;
			}else{
				g_wall_1 = "";
			}
			if(document.getElementById("g_wall_2").checked){
				var g_wall_2 = document.getElementById("g_wall_2").value;
			}else{
				g_wall_2 = "";
			}
			if(document.getElementById("g_wall_3").checked){
				var g_wall_3 = document.getElementById("g_wall_3").value;
			}else{
				g_wall_3 = "";
			}
			if(document.getElementById("g_wall_4").checked){
				var g_wall_4 = document.getElementById("g_wall_4").value;
			}else{
				g_wall_4 = "";
			}
			if(document.getElementById("g_wall_5").checked){
				g_wall_5 = document.getElementById("g_wall_5").value;
			}else{
				g_wall_5 = "";
			}
			if(document.getElementById("g_wall_6").checked){
				g_wall_6 = document.getElementById("g_wall_6").value;
			}else{
				g_wall_6 = "";
			}
			if(document.getElementById("g_wall_7").checked){
				g_wall_7 = document.getElementById("g_wall_7").value;
			}else{
				g_wall_7 = "";
			}

			if(document.getElementById("g_wall_8").checked){
				g_wall_8 = document.getElementById("g_wall_8").value;
			}else{
				g_wall_8 = "";
			}
			if(document.getElementById("g_wall_9").checked){
				g_wall_9 = document.getElementById("g_wall_9").value;
			}else{
				g_wall_9 = "";
			}
			if(document.getElementById("g_wall_10").checked){
				g_wall_10 = document.getElementById("g_wall_10").value;
			}
			else{
				g_wall_10 = "";
			}
			if(document.getElementById("g_no_top").checked)	{
				g_no_top = document.getElementById("g_no_top").value;
			} else{
				g_no_top = "";
			}
			if(document.getElementById("g_lid_top").checked)	{
				g_lid_top = document.getElementById("g_lid_top").value;
			} else{
					g_lid_top = "";
			}
			if(document.getElementById("g_partial_flap_top").checked) {
				g_partial_flap_top = document.getElementById("g_partial_flap_top").value;
			} else{
				g_partial_flap_top = "";
			}
			if(document.getElementById("g_full_flap_top").checked){
				g_full_flap_top = document.getElementById("g_full_flap_top").value;
			}else{
				g_full_flap_top = "";
			}
			if(document.getElementById("g_no_bottom_config").checked){
				g_no_bottom_config = document.getElementById("g_no_bottom_config").value;
			}else{
				g_no_bottom_config = "";
			}
			if(document.getElementById("g_partial_flap_w").checked){
				g_partial_flap_w = document.getElementById("g_partial_flap_w").value;
			}else{
				g_partial_flap_w = "";
			}
			if(document.getElementById("g_tray_bottom").checked){
				g_tray_bottom = document.getElementById("g_tray_bottom").value;
			}else{
				g_tray_bottom = "";
			}
			if(document.getElementById("g_full_flap_bottom").checked){
				g_full_flap_bottom = document.getElementById("g_full_flap_bottom").value;
			}else{
				g_full_flap_bottom= "";
			}
			if(document.getElementById("g_partial_flap_wo").checked){
				g_partial_flap_wo = document.getElementById("g_partial_flap_wo").value;
			}else{
				g_partial_flap_wo= "";
			}
			if(document.getElementById("g_vents_okay").checked){
				g_vents_okay = document.getElementById("g_vents_okay").value;
			}else{
				g_vents_okay= "";
			}
			var need_pallets = '', quoterequest_saleslead_flag;
			/*if(document.getElementById("need_pallets").checked){
				need_pallets = document.getElementById("need_pallets").value;
			}else{
				need_pallets= "";
			}*/
			quoterequest_saleslead_flag= "";
			var g_quantity_request = document.getElementById("g_quantity_request").value;
			var g_other_quantity = document.getElementById("g_other_quantity").value;
			var g_frequency_order = document.getElementById("g_frequency_order").value;
			var g_what_used_for = document.getElementById("g_what_used_for").value;
		    var date_needed_by = ""; //document.getElementById("date_needed_by").value;
			var g_item_note = document.getElementById("g_item_note").value;
			var quote_item = document.getElementById("quote_item").value;
			var client_dash_flg = document.getElementById("client_dash_flg").value;
			var gmin=parseInt(g_item_min_height);
			var gmax=parseInt(g_item_max_height);

			if(gmin>=gmax){
				alert("Please enter correct height");
				//document.getElementById('g_item_max_height').focus();
				return false;
			}
			if(g_shape_rectangular=="" && g_shape_octagonal==""){
				alert("Please select shape");
				return false;
			}
			if(g_wall_1=="" && g_wall_2=="" && g_wall_3=="" && g_wall_4=="" && g_wall_5=="" && g_wall_6=="" && g_wall_7=="" && g_wall_8=="" && g_wall_9=="" && g_wall_10==""){
				alert("Please select atleast one # of Walls");
				return false;
			}
			//
			if(g_no_top=="" && g_lid_top=="" && g_partial_flap_top=="" && g_full_flap_top==""){
				alert("Please select Top Config");
				return false;
			}
			//
			if(g_no_bottom_config=="" && g_partial_flap_w=="" && g_tray_bottom=="" && g_full_flap_bottom=="" && g_partial_flap_wo==""){
				alert("Please select Bottom Config");
				return false;
			}

			//alert('b2bid - '+b2bid+' / g_item_length - '+g_item_length+' / g_item_width - '+g_item_width+' / g_item_height - '+g_item_height+' / g_item_min_height - '+g_item_min_height+' / g_item_max_height - '+g_item_max_height+' / sales_desired_price_g - '+sales_desired_price_g+' / g_shape_rectangular - '+g_shape_rectangular+' / g_shape_octagonal - '+g_shape_octagonal+' / g_wall_1 - '+g_wall_1+' / g_wall_2 - '+g_wall_2+' / g_wall_3 - '+g_wall_3+' / g_wall_4 - '+g_wall_4+' / g_wall_5 - '+g_wall_5+' / g_wall_6 - '+g_wall_6+' / g_wall_7 - '+g_wall_7+' / g_wall_8 - '+g_wall_8+' / g_wall_9 - '+g_wall_9+' / g_wall_10 - '+g_wall_10+' / g_no_top - '+g_no_top+' / g_lid_top - '+g_lid_top+' / g_partial_flap_top - '+g_partial_flap_top+' / g_full_flap_top - '+g_full_flap_top+' / g_no_bottom_config - '+g_no_bottom_config+' / g_partial_flap_w - '+g_partial_flap_w+' / g_full_flap_bottom - '+g_full_flap_bottom+' / g_tray_bottom -'+g_tray_bottom+' / g_partial_flap_wo - '+g_partial_flap_wo+' / g_vents_okay - '+g_vents_okay+' / g_quantity_request - '+g_quantity_request+ ' / g_other_quantity - '+g_other_quantity+' / g_frequency_order - '+g_frequency_order+' / g_what_used_for - '+g_what_used_for+' / date_needed_by - '+date_needed_by+' / g_item_note - '+g_item_note+' / quote_item - '+quote_item+' / client_dash_flg - '+client_dash_flg+' / gmin - '+gmin+' / gmax - '+gmax)

			if (window.XMLHttpRequest){
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}else{
				// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){ 
					alert("Record has been added successfully!!");

					document.getElementById("display_quote_request").innerHTML = xmlhttp.responseText;
					
					$('table.table').hide();
					$('#quote_item').prop('selectedIndex',0); 
		            $("#show_q_div").load(location.href + " #show_q_div");

					/*var new_quote_id=document.getElementById("quote_id_n").value;
		            var comp_id=document.getElementById("comp_id").value;
					if(quoterequest_saleslead_flag=="Yes"){
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,1);
					}*/
		            
					quote_req_quote_type_chg();
				}
			}
			
			xmlhttp.open("GET","quote_request_save_new.php?addquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&g_item_length="+g_item_length+"&g_item_width="+g_item_width+"&g_item_height="+g_item_height+"&g_item_min_height="+g_item_min_height+"&g_item_max_height="+g_item_max_height+"&g_shape_rectangular="+g_shape_rectangular+"&g_shape_octagonal="+g_shape_octagonal+"&g_wall_1="+g_wall_1+"&g_wall_2="+g_wall_2+"&g_wall_3="+g_wall_3+"&g_wall_4="+g_wall_4+"&g_wall_5="+g_wall_5+"&g_wall_6="+g_wall_6+"&g_wall_7="+g_wall_7+"&g_wall_8="+g_wall_8+"&g_wall_9="+g_wall_9+"&g_wall_10="+g_wall_10+"&g_no_top="+g_no_top+"&g_lid_top="+g_lid_top+"&g_partial_flap_top="+g_partial_flap_top+"&g_full_flap_top="+g_full_flap_top+"&g_no_bottom_config="+g_no_bottom_config+"&g_partial_flap_w="+g_partial_flap_w+"&g_tray_bottom="+g_tray_bottom+"&g_full_flap_bottom="+g_full_flap_bottom+"&g_partial_flap_wo="+g_partial_flap_wo+"&g_vents_okay="+g_vents_okay+"&g_quantity_request="+g_quantity_request+"&g_other_quantity="+g_other_quantity+"&g_frequency_order="+g_frequency_order+"&g_what_used_for="+g_what_used_for+"&date_needed_by="+date_needed_by+"&need_pallets="+need_pallets+"&g_item_note="+g_item_note+"&client_dash_flg="+client_dash_flg+"&quoterequest_saleslead_flag="+quoterequest_saleslead_flag+"&quote_item="+quote_item+"&sales_desired_price_g="+sales_desired_price_g,true);
			xmlhttp.send();
		}
		
		function quote_update(tableid){
			var company_id = document.getElementById("company_id"+tableid).value;
			var g_item_length = document.getElementById("g_item_length"+tableid).value;
			var g_item_width = document.getElementById("g_item_width"+tableid).value;
			var g_item_height = document.getElementById("g_item_height"+tableid).value;
			var g_item_min_height = document.getElementById("g_item_min_height"+tableid).value;
			var g_item_max_height = document.getElementById("g_item_max_height"+tableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+tableid).value;
			var sales_desired_price_g = document.getElementById("sales_desired_price_g"+tableid).value;
			var g_shape_rectangular, g_shape_octagonal, g_wall_1, g_wall_2, g_wall_3, g_wall_4, g_wall_5, g_wall_6, g_wall_7, g_wall_8, g_wall_9, g_wall_10, g_no_top, g_lid_top, g_partial_flap_top, g_full_flap_top, g_no_bottom_config, g_partial_flap_w, g_full_flap_bottom, g_tray_bottom,  g_partial_flap_wo, g_vents_okay;
			if(document.getElementById("g_shape_rectangular"+tableid).checked){
				g_shape_rectangular = document.getElementById("g_shape_rectangular").value;
			}else{
				g_shape_rectangular = "";
			}
			if(document.getElementById("g_shape_octagonal"+tableid).checked){
				g_shape_octagonal = document.getElementById("g_shape_octagonal"+tableid).value;
			}else{
				g_shape_octagonal = "";
			}
			if(document.getElementById("g_wall_1"+tableid).checked)
				{
					g_wall_1 = document.getElementById("g_wall_1"+tableid).value;
				}
			else{
					g_wall_1 = "";
			}
			if(document.getElementById("g_wall_2"+tableid).checked)
				{
					g_wall_2 = document.getElementById("g_wall_2"+tableid).value;
				}
			else{
					g_wall_2 = "";
			}
			if(document.getElementById("g_wall_3"+tableid).checked)
				{
					g_wall_3 = document.getElementById("g_wall_3"+tableid).value;
				}
			else{
					g_wall_3 = "";
			}
			if(document.getElementById("g_wall_4"+tableid).checked)
				{
					g_wall_4 = document.getElementById("g_wall_4"+tableid).value;
				}
			else{
					g_wall_4 = "";
			}
			if(document.getElementById("g_wall_5"+tableid).checked)
				{
					g_wall_5 = document.getElementById("g_wall_5"+tableid).value;
				}
			else{
					g_wall_5 = "";
			}
			if(document.getElementById("g_wall_6"+tableid).checked)
				{
					g_wall_6 = document.getElementById("g_wall_6"+tableid).value;
				}
			else{
					g_wall_6 = "";
			}
			if(document.getElementById("g_wall_7"+tableid).checked)
				{
					
					g_wall_7 = document.getElementById("g_wall_7"+tableid).value;
				}
			else{
					 g_wall_7 = "";
			}

			if(document.getElementById("g_wall_8"+tableid).checked)
				{
					g_wall_8 = document.getElementById("g_wall_8"+tableid).value;
				}
			else{
					 g_wall_8 = "";
			}
			if(document.getElementById("g_wall_9"+tableid).checked)
				{
					g_wall_9 = document.getElementById("g_wall_9"+tableid).value;
				}
			else{
					g_wall_9 = "";
			}
			if(document.getElementById("g_wall_10"+tableid).checked)
				{
					g_wall_10 = document.getElementById("g_wall_10"+tableid).value;
				}
			else{
					g_wall_10 = "";
			}
			if(document.getElementById("g_no_top"+tableid).checked)
				{
					g_no_top = document.getElementById("g_no_top"+tableid).value;
				}
			else{
					g_no_top = "";
			}
			if(document.getElementById("g_lid_top"+tableid).checked)
				{
					g_lid_top = document.getElementById("g_lid_top"+tableid).value;
				}
			else{
					g_lid_top = "";
			}
			if(document.getElementById("g_partial_flap_top"+tableid).checked)
				{
					g_partial_flap_top = document.getElementById("g_partial_flap_top"+tableid).value;
				}
			else{
					g_partial_flap_top = "";
			}
			if(document.getElementById("g_full_flap_top"+tableid).checked)
				{
					g_full_flap_top = document.getElementById("g_full_flap_top"+tableid).value;
				}
			else{
					g_full_flap_top = "";
			}
			if(document.getElementById("g_no_bottom_config"+tableid).checked)
				{
					g_no_bottom_config = document.getElementById("g_no_bottom_config"+tableid).value;
				}
			else{
					g_no_bottom_config = "";
			}
			if(document.getElementById("g_partial_flap_w"+tableid).checked)
				{
					g_partial_flap_w = document.getElementById("g_partial_flap_w"+tableid).value;
				}
			else{
					g_partial_flap_w = "";
			}
			if(document.getElementById("g_tray_bottom"+tableid).checked)
				{
					g_tray_bottom = document.getElementById("g_tray_bottom"+tableid).value;
				}
			else{
					g_tray_bottom = "";
			}
			if(document.getElementById("g_full_flap_bottom"+tableid).checked)
				{
					g_full_flap_bottom = document.getElementById("g_full_flap_bottom"+tableid).value;
				}
			else{
					g_full_flap_bottom= "";
			}
			if(document.getElementById("g_partial_flap_wo"+tableid).checked)
				{
					g_partial_flap_wo = document.getElementById("g_partial_flap_wo"+tableid).value;
				}
			else{
					g_partial_flap_wo= "";
			}
			if(document.getElementById("g_vents_okay"+tableid).checked)
				{
					g_vents_okay = document.getElementById("g_vents_okay"+tableid).value;
				}
			else{
					g_vents_okay= "";
			}
			 var need_pallets= "", quoterequest_saleslead_flag;
			/*if(document.getElementById("need_pallets"+tableid).checked)
				{
					need_pallets = document.getElementById("need_pallets"+tableid).value;
				}
			else{
					need_pallets= "";
			}*/
			//if(document.getElementById("quoterequest_saleslead_flag"+tableid).checked)
			//	{
			//		quoterequest_saleslead_flag = document.getElementById("quoterequest_saleslead_flag"+tableid).value;
			//	}
			//else{
					quoterequest_saleslead_flag= "";
			//}
			//
			var g_quantity_request = document.getElementById("g_quantity_request"+tableid).value;
			var g_other_quantity = document.getElementById("g_other_quantity"+tableid).value;
			//
			var g_frequency_order = document.getElementById("g_frequency_order"+tableid).value;
			var g_what_used_for = document.getElementById("g_what_used_for"+tableid).value;
		    var date_needed_by = ""; //document.getElementById("date_needed_by"+tableid).value;
			var g_item_note = document.getElementById("g_item_note"+tableid).value;
			var quote_item = document.getElementById("quote_item"+tableid).value;
			//Validations--------------------------------------------------
			//
			var gmin=parseInt(g_item_min_height);
			var gmax=parseInt(g_item_max_height);
			if(gmin>=gmax)
				{
					alert("Please enter correct height");
					//document.getElementById('g_item_max_height').focus();
					return false;
				}
			//
			
			//
			if(g_shape_rectangular=="" && g_shape_octagonal==""){
				alert("Please select shape");
				return false;
			}
			if(g_wall_1=="" && g_wall_2=="" && g_wall_3=="" && g_wall_4=="" && g_wall_5=="" && g_wall_6=="" && g_wall_7=="" && g_wall_8=="" && g_wall_9=="" && g_wall_10==""){
				alert("Please select atleast one # of Walls");
				return false;
			}
			//
			if(g_no_top=="" && g_lid_top=="" && g_partial_flap_top=="" && g_full_flap_top==""){
				alert("Please select Top Config");
				return false;
			}
			//
			if(g_no_bottom_config=="" && g_partial_flap_w=="" && g_tray_bottom=="" && g_full_flap_bottom=="" && g_partial_flap_wo==""){
				alert("Please select Bottom Config");
				return false;
			}
			
			//
			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("g"+tableid).innerHTML=xmlhttp.responseText;
		            //display table details
		            var g = document.getElementById("g_sub_table"+tableid);
		            if (g.style.display === "none") {
		                g.style.display = "block";
		                document.getElementById("g_btn"+tableid).innerHTML="Collapse Details";
						document.getElementById("g_btn_img"+tableid).src="images/minus_icon.png";
		            } 
					var new_quote_id=document.getElementById("quote_id_n").value;
					var comp_id=document.getElementById("comp_id").value;
				}
			}	
			xmlhttp.open("POST","quote_request_save_new.php?updatequotedata=1&repchk=yes&tableid="+tableid+"&company_id="+company_id+"&g_item_length="+g_item_length+"&g_item_width="+g_item_width+"&g_item_height="+g_item_height+"&g_item_min_height="+g_item_min_height+"&g_item_max_height="+g_item_max_height+"&g_shape_rectangular="+g_shape_rectangular+"&g_shape_octagonal="+g_shape_octagonal+"&g_wall_1="+g_wall_1+"&g_wall_2="+g_wall_2+"&g_wall_3="+g_wall_3+"&g_wall_4="+g_wall_4+"&g_wall_5="+g_wall_5+"&g_wall_6="+g_wall_6+"&g_wall_7="+g_wall_7+"&g_wall_8="+g_wall_8+"&g_wall_9="+g_wall_9+"&g_wall_10="+g_wall_10+"&g_no_top="+g_no_top+"&g_lid_top="+g_lid_top+"&g_partial_flap_top="+g_partial_flap_top+"&g_full_flap_top="+g_full_flap_top+"&g_no_bottom_config="+g_no_bottom_config+"&g_partial_flap_w="+g_partial_flap_w+"&g_tray_bottom="+g_tray_bottom+"&g_full_flap_bottom="+g_full_flap_bottom+"&g_partial_flap_wo="+g_partial_flap_wo+"&g_vents_okay="+g_vents_okay+"&g_quantity_request="+g_quantity_request+"&g_other_quantity="+g_other_quantity+"&g_frequency_order="+g_frequency_order+"&g_what_used_for="+g_what_used_for+"&date_needed_by="+date_needed_by+"&need_pallets="+need_pallets+"&g_item_note="+g_item_note+"&quoterequest_saleslead_flag="+quoterequest_saleslead_flag+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sales_desired_price_g="+sales_desired_price_g,true);
			xmlhttp.send();
		}
		
		function quote_cancel(tableid){
			var company_id = document.getElementById("company_id"+tableid).value;
			var quote_item = document.getElementById("quote_item"+tableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+tableid).value;
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					
					document.getElementById("g"+tableid).innerHTML=xmlhttp.responseText;
		             //display table details
		            var g = document.getElementById("g_sub_table"+tableid);
		            if (g.style.display === "none") {
		                g.style.display = "block";
		                document.getElementById("g_btn"+tableid).innerHTML="Collapse Details";
						document.getElementById("g_btn_img"+tableid).src="images/minus_icon.png";
		            } 
		            //
				}
			}
			
			xmlhttp.open("GET","quote_request_save_new.php?updatequotedata=2&repchk=yes&tableid="+tableid+"&company_id="+company_id+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}

		function sb_quote_save(b2bid, repchk){
			var sb_item_length = document.getElementById("sb_item_length").value;
			var sb_item_width = document.getElementById("sb_item_width").value;
			var sb_item_height = document.getElementById("sb_item_height").value;
			var sb_item_min_length = document.getElementById("sb_item_min_length").value;
			var sb_item_max_length = document.getElementById("sb_item_max_length").value;
			var sb_item_min_width = document.getElementById("sb_item_min_width").value;
			var sb_item_max_width = document.getElementById("sb_item_max_width").value;
			var sb_item_min_height = document.getElementById("sb_item_min_height").value;
			var sb_item_max_height = document.getElementById("sb_item_max_height").value;
			var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min").value;
			var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max").value;
			var sb_date_needed_by = ""; //document.getElementById("sb_date_needed_by").value;
			
			var sb_sales_desired_price = document.getElementById("sb_sales_desired_price").value;
			
			var sb_quantity_requested = document.getElementById("sb_quantity_requested").value;
			var sb_other_quantity = document.getElementById("sb_other_quantity").value;
			var sb_frequency_order = document.getElementById("sb_frequency_order").value;
			var sb_what_used_for = document.getElementById("sb_what_used_for").value;
			var sb_notes = document.getElementById("sb_notes").value;
			var quote_item = document.getElementById("quote_item").value;
			var sb_client_dash_flg = document.getElementById("sb_client_dash_flg").value;
			
			//
			var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay, sb_partial_flap_top, sb_partial_flap_bottom;
			if(document.getElementById("sb_wall_1").checked)
				{
					sb_wall_1 = document.getElementById("sb_wall_1").value;
				}
			else{
					sb_wall_1 = "";
			}
			if(document.getElementById("sb_wall_2").checked)
				{
					sb_wall_2 = document.getElementById("sb_wall_2").value;
				}
			else{
					sb_wall_2 = "";
			}
			if(document.getElementById("sb_no_top").checked)
				{
					sb_no_top = document.getElementById("sb_no_top").value;
				}
			else{
					sb_no_top = "";
			}
			if(document.getElementById("sb_full_flap_top").checked)
				{
					sb_full_flap_top = document.getElementById("sb_full_flap_top").value;
				}
			else{
					sb_full_flap_top = "";
			}
			if(document.getElementById("sb_partial_flap_top").checked)
				{
					sb_partial_flap_top = document.getElementById("sb_partial_flap_top").value;
				}
			else{
					sb_partial_flap_top = "";
			}
			if(document.getElementById("sb_no_bottom").checked)
				{
					sb_no_bottom = document.getElementById("sb_no_bottom").value;
				}
			else{
					sb_no_bottom = "";
			}
			if(document.getElementById("sb_full_flap_bottom").checked)
				{
					sb_full_flap_bottom = document.getElementById("sb_full_flap_bottom").value;
				}
			else{
					sb_full_flap_bottom = "";
			}
			if(document.getElementById("sb_partial_flap_bottom").checked)
				{
					sb_partial_flap_bottom = document.getElementById("sb_partial_flap_bottom").value;
				}
			else{
					sb_partial_flap_bottom = "";
			}
			
			if(document.getElementById("sb_vents_okay").checked)
				{
					sb_vents_okay = document.getElementById("sb_vents_okay").value;
				}
			else{
					sb_vents_okay= "";
			}
			 var sb_need_pallets = '', sb_quotereq_sales_flag;
			/*if(document.getElementById("sb_need_pallets").checked)
				{
					sb_need_pallets = document.getElementById("sb_need_pallets").value;
				}
			else{
					sb_need_pallets= "";
			}*/
			sb_quotereq_sales_flag= "";
			//Validations--------------------------------------------------
			//
			var sbmin_l=parseInt(sb_item_min_length);
			var sbmax_l=parseInt(sb_item_max_length);
			if(sbmin_l>=sbmax_l)
				{
					alert("Please enter correct Length");
					document.getElementById('sb_item_min_length').value="";
					return false;
				}
			var sbmin_w=parseInt(sb_item_min_width);
			var sbmax_w=parseInt(sb_item_max_width);
			if(sbmin_w>=sbmax_w)
				{
					alert("Please enter correct Width");
					//document.getElementById('sb_item_min_width').value="";
					//document.getElementById('sb_item_min_width').focus();
					return false;
				}
			var sbmin_h=parseInt(sb_item_min_height);
			var sbmax_h=parseInt(sb_item_max_height);
			if(sbmin_h>=sbmax_h)
				{
					alert("Please enter correct Height");
					//document.getElementById('sb_item_min_height').focus();
					return false;
				}
			var sbmin_cf=parseFloat(sb_cubic_footage_min);
			var sbmax_cf=parseFloat(sb_cubic_footage_max);
			if(sbmin_cf>=sbmax_cf)
				{
					alert("Please enter correct value of Cubic Footage");
					//document.getElementById('sb_cubic_footage_max').focus();
					return false;
				}
			
			if(sb_wall_1=="" && sb_wall_2==""){
				alert("Please select # of Walls");
				return false;
			}
			if(sb_no_top=="" && sb_full_flap_top=="" && sb_partial_flap_top == ""){
				alert("Please select Top Config");
				return false;
			}
			if(sb_no_bottom=="" && sb_full_flap_bottom=="" && sb_partial_flap_bottom == ""){
				alert("Please select Bottom Config");
				return false;
			}

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{   														
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{	 
					document.getElementById("display_quote_request_ship").innerHTML=xmlhttp.responseText;
					alert("Record has been added successfully!!");
					$('table.table').hide();
					$('#quote_item').prop('selectedIndex',0);
		            //
		            $("#show_q_div").load(location.href + " #show_q_div");
		            //
					if(sb_quotereq_sales_flag=="Yes")
					{
		            	var new_quote_id=document.getElementById("sb_quote_id_n").value;
		            	var comp_id=document.getElementById("comp_id").value;
						//commented as new tracker is used
		            	//quote_request_send_email(new_quote_id,comp_id,2);
					}
					quote_req_quote_type_chg();
				}
			}

				xmlhttp.open("POST","quote_request_save_new.php?addquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&sb_item_length="+sb_item_length+"&sb_item_width="+sb_item_width+"&sb_item_height="+sb_item_height+"&sb_item_min_length="+sb_item_min_length+"&sb_item_max_length="+sb_item_max_length+"&sb_item_min_width="+sb_item_min_width+"&sb_item_max_width="+sb_item_max_width+"&sb_item_min_height="+sb_item_min_height+"&sb_item_max_height="+sb_item_max_height+"&sb_cubic_footage_min="+sb_cubic_footage_min+"&sb_cubic_footage_max="+sb_cubic_footage_max+"&sb_wall_1="+sb_wall_1+"&sb_wall_2="+sb_wall_2+"&sb_no_top="+sb_no_top+"&sb_full_flap_top="+sb_full_flap_top+"&sb_no_bottom="+sb_no_bottom+"&sb_full_flap_bottom="+sb_full_flap_bottom+"&sb_vents_okay="+sb_vents_okay+"&sb_quantity_requested="+sb_quantity_requested+"&sb_other_quantity="+sb_other_quantity+"&sb_frequency_order="+sb_frequency_order+"&sb_what_used_for="+sb_what_used_for+"&sb_date_needed_by="+sb_date_needed_by+"&sb_need_pallets="+sb_need_pallets+"&sb_quotereq_sales_flag="+sb_quotereq_sales_flag+"&sb_notes="+sb_notes+"&quote_item="+quote_item+"&client_dash_flg="+sb_client_dash_flg+"&sb_sales_desired_price="+sb_sales_desired_price+"&sb_partial_flap_top="+sb_partial_flap_top+"&sb_partial_flap_bottom="+sb_partial_flap_bottom,true);
			xmlhttp.send();
		}
		function show_sb_details(sbid) {
		    var sb = document.getElementById("sb_sub_table"+sbid);
		    if (sb.style.display === "none") {
		        sb.style.display = "block";
		        document.getElementById("sb_btn"+sbid).innerHTML="Collapse Details";
				document.getElementById("sb_btn_img"+sbid).src="images/minus_icon.png";
		    } else {
		        sb.style.display = "none";
		        document.getElementById("sb_btn"+sbid).innerHTML="Expand Details";
				document.getElementById("sb_btn_img"+sbid).src="images/plus-icon.png";
		    }
		}
		
		function sb_quote_edit(b2bid,stableid,quote_item,client_dash_flg){ 
			var p="sb";
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("sb"+stableid).innerHTML=xmlhttp.responseText;
				}
			}
				xmlhttp.open("GET","quote_request_edit_new.php?editquotedata=1&repchk=yes&company_id="+b2bid+"&p="+p+"&stableid="+stableid+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
				xmlhttp.send();
		}
		
		function quote_updates(stableid){
			var company_id = document.getElementById("company_id"+stableid).value;
			
			var client_dash_flg = document.getElementById("client_dash_flg"+stableid).value;
			
			var sb_item_length = document.getElementById("sb_item_length"+stableid).value;
			var sb_item_width = document.getElementById("sb_item_width"+stableid).value;
			var sb_item_height = document.getElementById("sb_item_height"+stableid).value;
			var sb_item_min_length = document.getElementById("sb_item_min_length"+stableid).value;
			var sb_item_max_length = document.getElementById("sb_item_max_length"+stableid).value;
			var sb_item_min_width = document.getElementById("sb_item_min_width"+stableid).value;
			var sb_item_max_width = document.getElementById("sb_item_max_width"+stableid).value;
			var sb_item_min_height = document.getElementById("sb_item_min_height"+stableid).value;
			var sb_item_max_height = document.getElementById("sb_item_max_height"+stableid).value;
			var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min"+stableid).value;
			var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max"+stableid).value;
			var sb_date_needed_by = "";
			
			var sb_quantity_requested = document.getElementById("sb_quantity_requested"+stableid).value;
			var sb_other_quantity = document.getElementById("sb_other_quantity"+stableid).value;
			var sb_frequency_order = document.getElementById("sb_frequency_order"+stableid).value;
			var sb_what_used_for = document.getElementById("sb_what_used_for"+stableid).value;
			var sb_notes = document.getElementById("sb_notes"+stableid).value;
			
			var sb_sales_desired_price = document.getElementById("sb_sales_desired_price"+stableid).value;
			
			var quote_item = document.getElementById("quote_item"+stableid).value;
			//
			var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay, sb_partial_flap_top, sb_partial_flap_bottom;
			if(document.getElementById("sb_wall_1"+stableid).checked)
				{
					sb_wall_1 = document.getElementById("sb_wall_1"+stableid).value;
				}
			else{
					sb_wall_1 = "";
			}
			if(document.getElementById("sb_wall_2"+stableid).checked)
				{
					sb_wall_2 = document.getElementById("sb_wall_2"+stableid).value;
				}
			else{
					sb_wall_2 = "";
			}
			if(document.getElementById("sb_no_top"+stableid).checked)
				{
					sb_no_top = document.getElementById("sb_no_top"+stableid).value;
				}
			else{
					sb_no_top = "";
			}
			if(document.getElementById("sb_full_flap_top"+stableid).checked)
				{
					sb_full_flap_top = document.getElementById("sb_full_flap_top"+stableid).value;
				}
			else{
					sb_full_flap_top = "";
			}
			if(document.getElementById("sb_partial_flap_top"+stableid).checked)
				{
					sb_partial_flap_top = document.getElementById("sb_partial_flap_top"+stableid).value;
				}
			else{
					sb_partial_flap_top = "";
			}
			if(document.getElementById("sb_no_bottom"+stableid).checked)
				{
					sb_no_bottom = document.getElementById("sb_no_bottom"+stableid).value;
				}
			else{
					sb_no_bottom = "";
			}
			if(document.getElementById("sb_full_flap_bottom"+stableid).checked)
				{
					sb_full_flap_bottom = document.getElementById("sb_full_flap_bottom"+stableid).value;
				}
			else{
					sb_full_flap_bottom = "";
			}
			if(document.getElementById("sb_partial_flap_bottom"+stableid).checked)
				{
					sb_partial_flap_bottom = document.getElementById("sb_partial_flap_bottom"+stableid).value;
				}
			else{
					sb_partial_flap_bottom = "";
			}
			
			if(document.getElementById("sb_vents_okay"+stableid).checked)
				{
					sb_vents_okay = document.getElementById("sb_vents_okay"+stableid).value;
				}
			else{
					sb_vents_okay= "";
			}
			 var sb_need_pallets= "", sb_quotereq_sales_flag;
			/*if(document.getElementById("sb_need_pallets"+stableid).checked)
				{
					sb_need_pallets = document.getElementById("sb_need_pallets"+stableid).value;
				}
			else{
					sb_need_pallets= "";
			}*/
			sb_quotereq_sales_flag= "";

			//Validations--------------------------------------------------
			//
			var sbmin_l=parseInt(sb_item_min_length);
			var sbmax_l=parseInt(sb_item_max_length);
			if(sbmin_l>=sbmax_l)
				{
					alert("Please enter correct Length");
					document.getElementById('sb_item_min_length').value="";
					return false;
				}
			var sbmin_w=parseInt(sb_item_min_width);
			var sbmax_w=parseInt(sb_item_max_width);
			if(sbmin_w>=sbmax_w)
				{
					alert("Please enter correct Width");
					//document.getElementById('sb_item_min_width').value="";
					//document.getElementById('sb_item_min_width').focus();
					return false;
				}
			var sbmin_h=parseInt(sb_item_min_height);
			var sbmax_h=parseInt(sb_item_max_height);
			if(sbmin_h>=sbmax_h)
				{
					alert("Please enter correct Height");
					//document.getElementById('sb_item_min_height').focus();
					return false;
				}
			var sbmin_cf=parseFloat(sb_cubic_footage_min);
			var sbmax_cf=parseFloat(sb_cubic_footage_max);
			if(sbmin_cf>=sbmax_cf)
				{
					alert("Please enter correct value of Cubic Footage");
					//document.getElementById('sb_cubic_footage_max').focus();
					return false;
				}
			if(sb_wall_1=="" && sb_wall_2==""){
				alert("Please select # of Walls");
				return false;
			}
			if(sb_no_top=="" && sb_full_flap_top=="" && sb_partial_flap_top == ""){
				alert("Please select Top Config");
				return false;
			}
			if(sb_no_bottom=="" && sb_full_flap_bottom=="" && sb_partial_flap_bottom == ""){
				alert("Please select Bottom Config");
				return false;
			}

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("sb"+stableid).innerHTML=xmlhttp.responseText;
		            //display table details
		            var sb = document.getElementById("sb_sub_table"+stableid);
		            if (sb.style.display === "none") {
		                sb.style.display = "block";
		                document.getElementById("sb_btn"+stableid).innerHTML="Collapse Details";
						document.getElementById("sb_btn_img"+stableid).src="images/minus_icon.png";
		            } 
					var new_quote_id=document.getElementById("sb_quote_id_n").value;
					var comp_id=document.getElementById("comp_id").value;

				}
			}
			xmlhttp.open("POST","quote_request_save_new.php?sbupdatequotedata=1&repchk=yes&stableid="+stableid+"&company_id="+company_id+"&sb_item_length="+sb_item_length+"&sb_item_width="+sb_item_width+"&sb_item_height="+sb_item_height+"&sb_item_min_length="+sb_item_min_length+"&sb_item_max_length="+sb_item_max_length+"&sb_item_min_width="+sb_item_min_width+"&sb_item_max_width="+sb_item_max_width+"&sb_item_min_height="+sb_item_min_height+"&sb_item_max_height="+sb_item_max_height+"&sb_cubic_footage_min="+sb_cubic_footage_min+"&sb_cubic_footage_max="+sb_cubic_footage_max+"&sb_wall_1="+sb_wall_1+"&sb_wall_2="+sb_wall_2+"&sb_no_top="+sb_no_top+"&sb_full_flap_top="+sb_full_flap_top+"&sb_no_bottom="+sb_no_bottom+"&sb_full_flap_bottom="+sb_full_flap_bottom+"&sb_vents_okay="+sb_vents_okay+"&sb_quantity_requested="+sb_quantity_requested+"&sb_other_quantity="+sb_other_quantity+"&sb_frequency_order="+sb_frequency_order+"&sb_what_used_for="+sb_what_used_for+"&sb_date_needed_by="+sb_date_needed_by+"&sb_need_pallets="+sb_need_pallets+"&sb_quotereq_sales_flag="+sb_quotereq_sales_flag+"&sb_notes="+sb_notes+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sb_sales_desired_price="+sb_sales_desired_price+"&sb_partial_flap_top="+sb_partial_flap_top+"&sb_partial_flap_bottom="+sb_partial_flap_bottom,true);
			xmlhttp.send();
		}
		
		function sb_quote_cancel(stableid){
			var company_id = document.getElementById("company_id"+stableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+stableid).value;
			
			var sb_item_length = document.getElementById("sb_item_length"+stableid).value;
			var sb_item_width = document.getElementById("sb_item_width"+stableid).value;
			var sb_item_height = document.getElementById("sb_item_height"+stableid).value;
			var sb_item_min_length = document.getElementById("sb_item_min_length"+stableid).value;
			var sb_item_max_length = document.getElementById("sb_item_max_length"+stableid).value;
			var sb_item_min_width = document.getElementById("sb_item_min_width"+stableid).value;
			var sb_item_max_width = document.getElementById("sb_item_max_width"+stableid).value;
			var sb_item_min_height = document.getElementById("sb_item_min_height"+stableid).value;
			var sb_item_max_height = document.getElementById("sb_item_max_height"+stableid).value;
			var sb_cubic_footage_min = document.getElementById("sb_cubic_footage_min"+stableid).value;
			var sb_cubic_footage_max = document.getElementById("sb_cubic_footage_max"+stableid).value;
			var sb_date_needed_by = "";
			
			var sb_quantity_requested = document.getElementById("sb_quantity_requested"+stableid).value;
			var sb_other_quantity = document.getElementById("sb_other_quantity"+stableid).value;
			var sb_frequency_order = document.getElementById("sb_frequency_order"+stableid).value;
			var sb_what_used_for = document.getElementById("sb_what_used_for"+stableid).value;
			var sb_notes = document.getElementById("sb_notes"+stableid).value;
			var quote_item = document.getElementById("quote_item"+stableid).value;
			var sb_sales_desired_price = document.getElementById("sb_sales_desired_price"+stableid).value;
			
			//
			var sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay;
			sb_wall_1 = "";
			sb_wall_2 = "";
			sb_no_top = "";
			sb_full_flap_top = "";
			sb_partial_flap_top = "";
			sb_no_bottom = "";
			sb_full_flap_bottom = "";
			sb_partial_flap_bottom = "";
			sb_vents_okay= "";
			
			var sb_need_pallets, sb_quotereq_sales_flag;
			sb_need_pallets= "";
			sb_quotereq_sales_flag= "";

			var quote_item = document.getElementById("quote_item"+stableid).value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("sb"+stableid).innerHTML=xmlhttp.responseText;
		            //display table details
		            var sb = document.getElementById("sb_sub_table"+stableid);
		            if (sb.style.display === "none") {
		                sb.style.display = "block";
		                document.getElementById("sb_btn"+stableid).innerHTML="Collapse Details";
						document.getElementById("sb_btn_img"+stableid).src="images/minus_icon.png";
		            } 
		            //
				}
			}
			
			xmlhttp.open("POST","quote_request_save_new.php?repchk=yes&sbupdatequotedata=2&stableid="+stableid+"&company_id="+company_id+"&sb_item_length="+sb_item_length+"&sb_item_width="+sb_item_width+"&sb_item_height="+sb_item_height+"&sb_item_min_length="+sb_item_min_length+"&sb_item_max_length="+sb_item_max_length+"&sb_item_min_width="+sb_item_min_width+"&sb_item_max_width="+sb_item_max_width+"&sb_item_min_height="+sb_item_min_height+"&sb_item_max_height="+sb_item_max_height+"&sb_cubic_footage_min="+sb_cubic_footage_min+"&sb_cubic_footage_max="+sb_cubic_footage_max+"&sb_wall_1="+sb_wall_1+"&sb_wall_2="+sb_wall_2+"&sb_no_top="+sb_no_top+"&sb_full_flap_top="+sb_full_flap_top+"&sb_no_bottom="+sb_no_bottom+"&sb_full_flap_bottom="+sb_full_flap_bottom+"&sb_vents_okay="+sb_vents_okay+"&sb_quantity_requested="+sb_quantity_requested+"&sb_other_quantity="+sb_other_quantity+"&sb_frequency_order="+sb_frequency_order+"&sb_what_used_for="+sb_what_used_for+"&sb_date_needed_by="+sb_date_needed_by+"&sb_need_pallets="+sb_need_pallets+"&sb_quotereq_sales_flag="+sb_quotereq_sales_flag+"&sb_notes="+sb_notes+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sb_sales_desired_price="+sb_sales_desired_price+"&sb_partial_flap_top="+sb_partial_flap_top+"&sb_partial_flap_bottom="+sb_partial_flap_bottom,true);
			xmlhttp.send();
		}
		
		function sb_quote_delete(stableid,quote_item,companyid){
		    var choice = confirm('Do you really want to delete this record?');
		    if(choice === true) {
		        var p="sb";
		        if (window.XMLHttpRequest)
		        {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp=new XMLHttpRequest();
		        }
		        else
		        {// code for IE6, IE5
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange=function()
		        {
		            if (xmlhttp.readyState==4 && xmlhttp.status==200)
		            {
		                document.getElementById("sb"+stableid).innerHTML=xmlhttp.responseText;
		                 $("#show_q_div").load(location.href + " #show_q_div");
		            }
		        }
		        xmlhttp.open("GET","delete_quote_request.php?repchk=yes&editquotedata=1&p="+p+"&stableid="+stableid+"&quote_item="+quote_item+"&companyid="+companyid,true);
		        xmlhttp.send(); 
		    }
		    else{
		        
		    }
		}

		function sup_quote_save(b2bid, repchk){
			var sup_item_length = document.getElementById("sup_item_length").value;
			var sup_item_width = document.getElementById("sup_item_width").value;
			var sup_item_height = document.getElementById("sup_item_height").value;
			
			var sup_quantity_requested = document.getElementById("sup_quantity_requested").value;
			var sup_other_quantity = document.getElementById("sup_other_quantity").value;
			var sup_frequency_order = document.getElementById("sup_frequency_order").value;
			var sup_what_used_for = document.getElementById("sup_what_used_for").value;
			
			var sup_sales_desired_price = document.getElementById("sup_sales_desired_price").value;
			
			var sup_date_needed_by = ""; //document.getElementById("sup_date_needed_by").value;
			var sup_need_pallets = '';
			var sup_notes = document.getElementById("sup_notes").value;
			var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag").value;
			var quote_item = document.getElementById("quote_item").value;
			var client_dash_flg = document.getElementById("sup_client_dash_flg").value;
			//
			/*if(document.getElementById("sup_need_pallets").checked)
				{
					sup_need_pallets = document.getElementById("sup_need_pallets").value;
				}
			else{
					sup_need_pallets= "";
			}*/
			sup_quotereq_sales_flag= "";

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{ // alert(xmlhttp.responseText)
					document.getElementById("display_quote_request_super").innerHTML=xmlhttp.responseText;
					alert("Record has been added successfully!!");
					$('table.table').hide();
					 $('#quote_item').prop('selectedIndex',0);
		            //
		            $("#show_q_div").load(location.href + " #show_q_div");
		            //
					//if(quoterequest_saleslead_flag=="Yes")
					//{
						var new_quote_id=document.getElementById("sup_quote_id_n").value;
						var comp_id=document.getElementById("comp_id").value;
						if(sup_quotereq_sales_flag=="Yes")
						{
							//commented as new tracker is used
							//quote_request_send_email(new_quote_id,comp_id,3);
						}
						quote_req_quote_type_chg();
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?addquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&sup_item_length="+sup_item_length+"&sup_item_width="+sup_item_width+"&sup_item_height="+sup_item_height+"&sup_quantity_requested="+sup_quantity_requested+"&sup_frequency_order="+sup_frequency_order+"&sup_other_quantity="+sup_other_quantity+"&sup_date_needed_by="+sup_date_needed_by+"&sup_need_pallets="+sup_need_pallets+"&sup_what_used_for="+sup_what_used_for+"&sup_quotereq_sales_flag="+sup_quotereq_sales_flag+"&sup_notes="+sup_notes+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sup_sales_desired_price="+sup_sales_desired_price,true);
			xmlhttp.send();
		}
		
		function show_sup_details(supid) {
		    var sup = document.getElementById("sup_sub_table"+supid);
		    if (sup.style.display === "none") {
		        sup.style.display = "block";
		        document.getElementById("sup_btn"+supid).innerHTML="Collapse Details";
				document.getElementById("sup_btn_img"+supid).src="images/minus_icon.png";
		    } else {
		        sup.style.display = "none";
		        document.getElementById("sup_btn"+supid).innerHTML="Expand Details";
				document.getElementById("sup_btn_img"+supid).src="images/plus-icon.png";
		    }
		}
		
		function sup_quote_edit(b2bid,suptableid,quote_item,client_dash_flg){
			var p="sup";
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("sup"+suptableid).innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","quote_request_edit_new.php?repchk=yes&editquotedata=1&company_id="+b2bid+"&p="+p+"&suptableid="+suptableid+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}
		
		function sup_quote_updates(suptableid){
			var company_id = document.getElementById("company_id"+suptableid).value;
			var sup_item_length = document.getElementById("sup_item_length"+suptableid).value;
			var sup_item_width = document.getElementById("sup_item_width"+suptableid).value;
			var sup_item_height = document.getElementById("sup_item_height"+suptableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+suptableid).value;
			
			var sup_sales_desired_price = document.getElementById("sup_sales_desired_price"+suptableid).value;
			
			var sup_other_quantity;
			var sup_quantity_requested = document.getElementById("sup_quantity_requested"+suptableid).value;
			if(sup_quantity_requested=="Other")
				{
					sup_other_quantity = document.getElementById("sup_other_quantity"+suptableid).value;
				}
			else{
				sup_other_quantity = "";
			}
			
			var sup_frequency_order = document.getElementById("sup_frequency_order"+suptableid).value;
			var sup_what_used_for = document.getElementById("sup_what_used_for"+suptableid).value;
			var sup_date_needed_by = "";
			var sup_need_pallets = '';
			var sup_notes = document.getElementById("sup_notes"+suptableid).value;
			var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
			var quote_item = document.getElementById("quote_item"+suptableid).value;
			//
			/*if(document.getElementById("sup_need_pallets"+suptableid).checked)
				{
					sup_need_pallets = document.getElementById("sup_need_pallets"+suptableid).value;
				}
			else{
					sup_need_pallets= "";
			}*/
			sup_quotereq_sales_flag= "";

			if(sup_quantity_requested=="Other")
				{
					if(document.getElementById("sup_other_quantity"+suptableid).value=="")
						{
							alert("Please enter Quantity requested");
							return false;
						}
					
				}
			
			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
						document.getElementById("sup"+suptableid).innerHTML=xmlhttp.responseText;
		              //display table details
		            var sup = document.getElementById("sup_sub_table"+suptableid);
		            if (sup.style.display === "none") {
		                sup.style.display = "block";
		                document.getElementById("sup_btn"+suptableid).innerHTML="Collapse Details";
						document.getElementById("sb_btn_img"+suptableid).src="images/minus_icon.png";
		            } 
					var new_quote_id=document.getElementById("sup_quote_id_n").value;
						var comp_id=document.getElementById("comp_id").value;
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&supupdatequotedata=1&suptableid="+suptableid+"&company_id="+company_id+"&sup_item_length="+sup_item_length+"&sup_item_width="+sup_item_width+"&sup_item_height="+sup_item_height+"&sup_quantity_requested="+sup_quantity_requested+"&sup_frequency_order="+sup_frequency_order+"&sup_other_quantity="+sup_other_quantity+"&sup_date_needed_by="+sup_date_needed_by+"&sup_need_pallets="+sup_need_pallets+"&sup_what_used_for="+sup_what_used_for+"&sup_quotereq_sales_flag="+sup_quotereq_sales_flag+"&sup_notes="+sup_notes+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sup_sales_desired_price="+sup_sales_desired_price,true);
			xmlhttp.send();
		}
		function sup_quote_cancel(suptableid){
			var company_id = document.getElementById("company_id"+suptableid).value;
			var sup_item_length = document.getElementById("sup_item_length"+suptableid).value;
			var sup_item_width = document.getElementById("sup_item_width"+suptableid).value;
			var sup_item_height = document.getElementById("sup_item_height"+suptableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+suptableid).value;
			
			var sup_sales_desired_price = document.getElementById("sup_sales_desired_price"+suptableid).value;
			
			var sup_other_quantity;
			var sup_quantity_requested = document.getElementById("sup_quantity_requested"+suptableid).value;
			if(sup_quantity_requested=="Other")
				{
					sup_other_quantity = document.getElementById("sup_other_quantity"+suptableid).value;
				}
			else{
				sup_other_quantity = "";
			}
			var sup_frequency_order = document.getElementById("sup_frequency_order"+suptableid).value;
			var sup_what_used_for = document.getElementById("sup_what_used_for"+suptableid).value;
			var sup_date_needed_by = "";
			var sup_need_pallets;
			var sup_notes = document.getElementById("sup_notes"+suptableid).value;
			var sup_quotereq_sales_flag = ""; //document.getElementById("sup_quotereq_sales_flag"+suptableid).value;
			var quote_item = document.getElementById("quote_item"+suptableid).value;
			//
			sup_need_pallets= "";
			sup_quotereq_sales_flag= "";

			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("sup"+suptableid).innerHTML=xmlhttp.responseText;
		              //display table details
		            var sup = document.getElementById("sup_sub_table"+suptableid);
		            if (sup.style.display === "none") {
		                sup.style.display = "block";
		                document.getElementById("sup_btn"+suptableid).innerHTML="Collapse Details";
						document.getElementById("sb_btn_img"+suptableid).src="images/minus_icon.png";
		            } 
		            //
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&supupdatequotedata=2&suptableid="+suptableid+"&company_id="+company_id+"&sup_item_length="+sup_item_length+"&sup_item_width="+sup_item_width+"&sup_item_height="+sup_item_height+"&sup_quantity_requested="+sup_quantity_requested+"&sup_frequency_order="+sup_frequency_order+"&sup_other_quantity="+sup_other_quantity+"&sup_date_needed_by="+sup_date_needed_by+"&sup_need_pallets="+sup_need_pallets+"&sup_what_used_for="+sup_what_used_for+"&sup_quotereq_sales_flag="+sup_quotereq_sales_flag+"&sup_notes="+sup_notes+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&sup_sales_desired_price="+sup_sales_desired_price,true);
			xmlhttp.send();
		}
		function sup_quote_delete(suptableid,quote_item,companyid){
		    var choice = confirm('Do you really want to delete this record?');
		    if(choice === true) {
		        //
		        var p="sup";
		        //
		        if (window.XMLHttpRequest)
		        {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp=new XMLHttpRequest();
		        }
		        else
		        {// code for IE6, IE5
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange=function()
		        {
		            if (xmlhttp.readyState==4 && xmlhttp.status==200)
		            {
		                document.getElementById("sup"+suptableid).innerHTML=xmlhttp.responseText;
		                 $("#show_q_div").load(location.href + " #show_q_div");
		            }
		        }
		        xmlhttp.open("GET","delete_quote_request.php?repchk=yes&editquotedata=1&p="+p+"&suptableid="+suptableid+"&quote_item="+quote_item+"&companyid="+companyid,true);
		        xmlhttp.send();
		    }
		    else{
		        
		    }
		}

		function pallets_quote_save(b2bid, repchk){
			var pal_item_length = document.getElementById("pal_item_length").value;
			var pal_item_width = document.getElementById("pal_item_width").value;
			
			var pal_quantity_requested = document.getElementById("pal_quantity_requested").value;
			var pal_other_quantity = document.getElementById("pal_other_quantity").value;
			var pal_frequency_order = document.getElementById("pal_frequency_order").value;
			var pal_what_used_for = document.getElementById("pal_what_used_for").value;
			var pal_date_needed_by = ""; //document.getElementById("pal_date_needed_by").value;
			var pal_note = document.getElementById("pal_note").value;
			var pal_quotereq_sales_flag = ""; //document.getElementById("pal_quotereq_sales_flag").value;
			var quote_item = document.getElementById("quote_item").value;
			var client_dash_flg = document.getElementById("pal_client_dash_flg").value;
			
			var pal_grade_a, pal_grade_b, pal_grade_c, pal_material_wooden, pal_material_plastic, pal_material_corrugate, pal_entry_2way, pal_entry_4way, pal_structure_stringer, pal_structure_block;

			if(document.getElementById("pal_grade_a").checked){
				pal_grade_a = document.getElementById("pal_grade_a").value;
			}else{ 
				pal_grade_a = ""; 
			}

			if(document.getElementById("pal_grade_b").checked){
				pal_grade_b = document.getElementById("pal_grade_b").value;
			}else{ 
				pal_grade_b = ""; 
			}
			
			if(document.getElementById("pal_grade_c").checked){
				pal_grade_c = document.getElementById("pal_grade_c").value;
			}else{ 
				pal_grade_c = ""; 
			}
			
			if(document.getElementById("pal_material_wooden").checked){
				pal_material_wooden = document.getElementById("pal_material_wooden").value;
			}else{ 
				pal_material_wooden = ""; 
			}

			if(document.getElementById("pal_material_plastic").checked){
				pal_material_plastic = document.getElementById("pal_material_plastic").value;
			}else{ 
				pal_material_plastic = ""; 
			}

			if(document.getElementById("pal_material_corrugate").checked){
				pal_material_corrugate = document.getElementById("pal_material_corrugate").value;
			}else{ 
				pal_material_corrugate = ""; 
			}
			
			if(document.getElementById("pal_entry_2way").checked){
				pal_entry_2way = document.getElementById("pal_entry_2way").value;
			}else{ 
				pal_entry_2way = ""; 
			}

			if(document.getElementById("pal_entry_4way").checked){
				pal_entry_4way = document.getElementById("pal_entry_4way").value;
			}else{ 
				pal_entry_4way = ""; 
			}
			
			if(document.getElementById("pal_structure_stringer").checked){
				pal_structure_stringer = document.getElementById("pal_structure_stringer").value;
			}else{ 
				pal_structure_stringer = ""; 
			}
			
			if(document.getElementById("pal_structure_block").checked){
				pal_structure_block = document.getElementById("pal_structure_block").value;
			}else{ 
				pal_structure_block = ""; 
			}

			var pal_heat_treated = document.getElementById("pal_heat_treated").value;
			
			var pal_sales_desired_price = document.getElementById("pal_sales_desired_price").value;
			pal_quotereq_sales_flag= "";

			var quote_item = document.getElementById("quote_item").value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("display_quote_request_pallets").innerHTML=xmlhttp.responseText;
					alert("Record has been added successfully!!");
					$('table.table').hide();
					 $('#quote_item').prop('selectedIndex',0);
		            //
		            $("#show_q_div").load(location.href + " #show_q_div");
		            //
					if(pal_quotereq_sales_flag=="Yes")
					{
						var new_quote_id=document.getElementById("pal_quote_id_n").value;
		            	var comp_id=document.getElementById("comp_id").value;
						//commented as new tracker is used
		            	//quote_request_send_email(new_quote_id,comp_id,4);
					}
					quote_req_quote_type_chg();
		            
				}
			}

			xmlhttp.open("GET","quote_request_save_new.php?addquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&pal_item_length="+pal_item_length+"&pal_item_width="+pal_item_width+"&pal_quantity_requested="+pal_quantity_requested+"&pal_frequency_order="+pal_frequency_order+"&pal_other_quantity="+pal_other_quantity+"&pal_date_needed_by="+pal_date_needed_by+"&pal_what_used_for="+pal_what_used_for+"&pal_quotereq_sales_flag="+pal_quotereq_sales_flag+"&pal_note="+pal_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&pal_sales_desired_price="+pal_sales_desired_price+"&pal_grade_a="+pal_grade_a+"&pal_grade_b="+pal_grade_b+"&pal_grade_c="+pal_grade_c+"&pal_material_wooden="+pal_material_wooden+"&pal_material_plastic="+pal_material_plastic+"&pal_material_corrugate="+pal_material_corrugate+"&pal_entry_2way="+pal_entry_2way+"&pal_entry_4way="+pal_entry_4way+"&pal_structure_stringer="+pal_structure_stringer+"&pal_structure_block="+pal_structure_block+"&pal_heat_treated="+pal_heat_treated,true);
			xmlhttp.send();
		}
		
		function show_pal_details(palid) {
		    var pal = document.getElementById("pal_sub_table"+palid);
		    if (pal.style.display === "none") {
		        pal.style.display = "block";
		        document.getElementById("pal_btn"+palid).innerHTML="Collapse Details";
				document.getElementById("pal_btn_img"+palid).src="images/minus_icon.png";
		    } else {
		        pal.style.display = "none";
		        document.getElementById("pal_btn"+palid).innerHTML="Expand Details";
				document.getElementById("pal_btn_img"+palid).src="images/plus-icon.png";
		    }
		}
		
		function pal_quote_edit(b2bid,paltableid,quote_item, client_dash_flg){
			var p="pal";
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("pal"+paltableid).innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","quote_request_edit_new.php?repchk=yes&editquotedata=1&company_id="+b2bid+"&p="+p+"&paltableid="+paltableid+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}
		
		function pal_quote_updates(paltableid){
			var company_id = document.getElementById("company_id"+paltableid).value;
			
			var pal_item_length = document.getElementById("pal_item_length"+paltableid).value;
			var pal_item_width = document.getElementById("pal_item_width"+paltableid).value;
			
			var client_dash_flg = document.getElementById("client_dash_flg"+paltableid).value;
			
			var pal_quantity_requested = document.getElementById("pal_quantity_requested"+paltableid).value;
			var pal_other_quantity = document.getElementById("pal_other_quantity"+paltableid).value;
			var pal_frequency_order = document.getElementById("pal_frequency_order"+paltableid).value;
			var pal_what_used_for = document.getElementById("pal_what_used_for"+paltableid).value;
			var pal_date_needed_by =  "";
			var pal_note = document.getElementById("pal_note"+paltableid).value;
			var pal_quotereq_sales_flag = ""; //document.getElementById("pal_quotereq_sales_flag"+paltableid).value;
			var quote_item = document.getElementById("quote_item"+paltableid).value;
			
			var pal_sales_desired_price = document.getElementById("pal_sales_desired_price"+paltableid).value;
			pal_quotereq_sales_flag= "";

			var quote_item = document.getElementById("quote_item"+paltableid).value;

			var pal_grade_a, pal_grade_b, pal_grade_c, pal_material_wooden, pal_material_plastic, pal_material_corrugate, pal_entry_2way, pal_entry_4way, pal_structure_stringer, pal_structure_block;

			if(document.getElementById("pal_grade_a"+paltableid).checked){
				pal_grade_a = document.getElementById("pal_grade_a"+paltableid).value;
			}else{ 
				pal_grade_a = ""; 
			}

			if(document.getElementById("pal_grade_b"+paltableid).checked){
				pal_grade_b = document.getElementById("pal_grade_b"+paltableid).value;
			}else{ 
				pal_grade_b = ""; 
			}

			if(document.getElementById("pal_grade_c"+paltableid).checked){
				pal_grade_c = document.getElementById("pal_grade_c"+paltableid).value;
			}else{ 
				pal_grade_c = ""; 
			}

			if(document.getElementById("pal_material_wooden"+paltableid).checked){
				pal_material_wooden = document.getElementById("pal_material_wooden"+paltableid).value;
			}else{ 
				pal_material_wooden = ""; 
			}

			if(document.getElementById("pal_material_plastic"+paltableid).checked){
				pal_material_plastic = document.getElementById("pal_material_plastic"+paltableid).value;
			}else{ 
				pal_material_plastic = ""; 
			}

			if(document.getElementById("pal_material_corrugate"+paltableid).checked){
				pal_material_corrugate = document.getElementById("pal_material_corrugate"+paltableid).value;
			}else{ 
				pal_material_corrugate = ""; 
			}

			if(document.getElementById("pal_entry_2way"+paltableid).checked){
				pal_entry_2way = document.getElementById("pal_entry_2way"+paltableid).value;
			}else{ 
				pal_entry_2way = ""; 
			}

			if(document.getElementById("pal_entry_4way"+paltableid).checked){
				pal_entry_4way = document.getElementById("pal_entry_4way"+paltableid).value;
			}else{ 
				pal_entry_4way = ""; 
			}

			if(document.getElementById("pal_structure_stringer"+paltableid).checked){
				pal_structure_stringer = document.getElementById("pal_structure_stringer"+paltableid).value;
			}else{ 
				pal_structure_stringer = ""; 
			}

			if(document.getElementById("pal_structure_block"+paltableid).checked){
				pal_structure_block = document.getElementById("pal_structure_block"+paltableid).value;
			}else{ 
				pal_structure_block = ""; 
			}

			var pal_heat_treated = document.getElementById("pal_heat_treated"+paltableid).value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("pal"+paltableid).innerHTML = xmlhttp.responseText;

					//display table details
		            var pal = document.getElementById("pal_sub_table"+paltableid);
		            if (pal.style.display === "none") {
		                pal.style.display = "block";
		                document.getElementById("pal_btn"+paltableid).innerHTML="Collapse Details";
						document.getElementById("pal_btn_img"+paltableid).src="images/minus_icon.png";
		            }
					
					var new_quote_id=document.getElementById("pal_quote_id_n").value;
					var comp_id=document.getElementById("comp_id").value;
				}
			}	
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&palupdatequotedata=1&paltableid="+paltableid+"&company_id="+company_id+"&pal_item_length="+pal_item_length+"&pal_item_width="+pal_item_width+"&pal_quantity_requested="+pal_quantity_requested+"&pal_frequency_order="+pal_frequency_order+"&pal_other_quantity="+pal_other_quantity+"&pal_date_needed_by="+pal_date_needed_by+"&pal_what_used_for="+pal_what_used_for+"&pal_quotereq_sales_flag="+pal_quotereq_sales_flag+"&pal_note="+pal_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&pal_sales_desired_price="+pal_sales_desired_price+"&pal_grade_a="+pal_grade_a+"&pal_grade_b="+pal_grade_b+"&pal_grade_c="+pal_grade_c+"&pal_material_wooden="+pal_material_wooden+"&pal_material_plastic="+pal_material_plastic+"&pal_material_corrugate="+pal_material_corrugate+"&pal_entry_2way="+pal_entry_2way+"&pal_entry_4way="+pal_entry_4way+"&pal_structure_stringer="+pal_structure_stringer+"&pal_structure_block="+pal_structure_block+"&pal_heat_treated="+pal_heat_treated,true);
			xmlhttp.send();
		}
		
		function pal_quote_cancel(paltableid) {
			var company_id = document.getElementById("company_id"+paltableid).value;
			
			var pal_item_length = document.getElementById("pal_item_length"+paltableid).value;
			var pal_item_width = document.getElementById("pal_item_width"+paltableid).value;
			
			var client_dash_flg = document.getElementById("client_dash_flg"+paltableid).value;
			
			var pal_sales_desired_price = document.getElementById("pal_sales_desired_price"+paltableid).value;
			
			var pal_quantity_requested = document.getElementById("pal_quantity_requested"+paltableid).value;
			var pal_other_quantity = document.getElementById("pal_other_quantity"+paltableid).value;
			var pal_frequency_order = document.getElementById("pal_frequency_order"+paltableid).value;
			var pal_what_used_for = document.getElementById("pal_what_used_for"+paltableid).value;
			var pal_date_needed_by =  "";
			var pal_note = document.getElementById("pal_note"+paltableid).value;
			var pal_quotereq_sales_flag = ""; 

			var quote_item = document.getElementById("quote_item"+paltableid).value;
			pal_quotereq_sales_flag= "";

			var quote_item = document.getElementById("quote_item"+paltableid).value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("pal"+paltableid).innerHTML=xmlhttp.responseText;
		             //display table details
		            var pal = document.getElementById("pal_sub_table"+paltableid);
		            if (pal.style.display === "none") {
		                pal.style.display = "block";
		                document.getElementById("pal_btn"+paltableid).innerHTML="Collapse Details";
						document.getElementById("pal_btn_img"+paltableid).src="images/minus_icon.png";
		            } 
		            //
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&palupdatequotedata=2&paltableid="+paltableid+"&company_id="+company_id+"&pal_item_length="+pal_item_length+"&pal_item_width="+pal_item_width+"&pal_quantity_requested="+pal_quantity_requested+"&pal_frequency_order="+pal_frequency_order+"&pal_other_quantity="+pal_other_quantity+"&pal_date_needed_by="+pal_date_needed_by+"&pal_what_used_for="+pal_what_used_for+"&pal_quotereq_sales_flag="+pal_quotereq_sales_flag+"&pal_note="+pal_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg+"&pal_sales_desired_price="+pal_sales_desired_price,true);
			xmlhttp.send();
		}	
		
		function pal_quote_delete(paltableid,quote_item,companyid){
		    var choice = confirm('Do you really want to delete this record?');
		    if(choice === true) {
		        var p="pal";
		        if (window.XMLHttpRequest)
		        {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp=new XMLHttpRequest();
		        }
		        else
		        {// code for IE6, IE5
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange=function()
		        {
		            if (xmlhttp.readyState==4 && xmlhttp.status==200)
		            {
		                document.getElementById("pal"+paltableid).innerHTML=xmlhttp.responseText;
		                 $("#show_q_div").load(location.href + " #show_q_div");
		            }
		        }
		            xmlhttp.open("GET","delete_quote_request.php?repchk=yes&editquotedata=1&p="+p+"&paltableid="+paltableid+"&quote_item="+quote_item+"&companyid="+companyid,true);
		            xmlhttp.send();
		    }
		    else{
		        
		    }
		}

		function other_quote_save(b2bid, repchk){
			var other_quantity_requested = document.getElementById("other_quantity_requested").value;
			var other_other_quantity = document.getElementById("other_other_quantity").value;
			var other_frequency_order = document.getElementById("other_frequency_order").value;
			var other_what_used_for = document.getElementById("other_what_used_for").value;
			var other_date_needed_by = ""; //document.getElementById("other_date_needed_by").value;
			var other_need_pallets;
			var other_note = document.getElementById("other_note").value;
			
			var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag").value;
			var quote_item = document.getElementById("quote_item").value;
			var client_dash_flg = 1;
			if(document.getElementById("other_need_pallets").checked)
				{
					other_need_pallets = document.getElementById("other_need_pallets").value;
				}
			else{
					other_need_pallets= "";
			}
			other_quotereq_sales_flag= "";

			var quote_item = document.getElementById("quote_item").value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("display_quote_request_other").innerHTML=xmlhttp.responseText;
					alert("Record has been added successfully!!");
					$('table.table').hide();
		            $('#quote_item').prop('selectedIndex',0);
		            //
		            $("#show_q_div").load(location.href + " #show_q_div");
		            //
					var new_quote_id=document.getElementById("other_quote_id_n").value;
					var comp_id=document.getElementById("comp_id").value;
					if(other_quotereq_sales_flag=="Yes")
					{
						//commented as new tracker is used
		            	//quote_request_send_email(new_quote_id,comp_id,7);
					}
		            //display table details

		            //
					quote_req_quote_type_chg();
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?addquotedata=1&repchk="+repchk+"&company_id="+b2bid+"&other_quantity_requested="+other_quantity_requested+"&other_frequency_order="+other_frequency_order+"&other_other_quantity="+other_other_quantity+"&other_date_needed_by="+other_date_needed_by+"&other_need_pallets="+other_need_pallets+"&other_what_used_for="+other_what_used_for+"&other_quotereq_sales_flag="+other_quotereq_sales_flag+"&other_note="+other_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}
		function show_other_details(otherid) {
		    var other = document.getElementById("other_sub_table"+otherid);
		    if (other.style.display === "none") {
		        other.style.display = "block";
		        document.getElementById("other_btn"+otherid).innerHTML="Collapse Details";
				document.getElementById("other_btn_img"+otherid).src="images/minus_icon.png";       
		    } else {
		        other.style.display = "none";
		        document.getElementById("other_btn"+otherid).innerHTML="Expand Details";
				document.getElementById("other_btn_img"+otherid).src="images/plus-icon.png";
		    }
		} 
		function other_quote_edit(b2bid,othertableid,quote_item, client_dash_flg){
			var p="other";
			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("other"+othertableid).innerHTML=xmlhttp.responseText;
				}
			}
				
			xmlhttp.open("GET","quote_request_edit_new.php?repchk=yes&editquotedata=1&company_id="+b2bid+"&p="+p+"&othertableid="+othertableid+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}
		
		function other_quote_updates(othertableid){
			var company_id = document.getElementById("company_id"+othertableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+othertableid).value;
			
			var other_quantity_requested = document.getElementById("other_quantity_requested"+othertableid).value;
			var other_other_quantity = document.getElementById("other_other_quantity"+othertableid).value;
			var other_frequency_order = document.getElementById("other_frequency_order"+othertableid).value;
			var other_what_used_for = document.getElementById("other_what_used_for"+othertableid).value;
			var other_date_needed_by =  "";
			var other_need_pallets;
			var other_note = document.getElementById("other_note"+othertableid).value;
			var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag"+othertableid).value;
			var quote_item = document.getElementById("quote_item"+othertableid).value;

			//
			if(document.getElementById("other_need_pallets"+othertableid).checked)
				{
					other_need_pallets = document.getElementById("other_need_pallets"+othertableid).value;
				}
			else{
					other_need_pallets= "";
			}
			other_quotereq_sales_flag= "";
			var quote_item = document.getElementById("quote_item"+othertableid).value;

			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("other"+othertableid).innerHTML=xmlhttp.responseText;
		            //display table details
		            var other = document.getElementById("other_sub_table"+othertableid);
		            if (other.style.display === "none") {
		                other.style.display = "block";
		                document.getElementById("other_btn"+othertableid).innerHTML="Collapse Details";
						document.getElementById("other_btn_img"+othertableid).src="images/minus_icon.png";
		            } 
					var new_quote_id=document.getElementById("other_quote_id_n").value;
					var comp_id=document.getElementById("comp_id").value;
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&otherupdatequotedata=1&othertableid="+othertableid+"&company_id="+company_id+"&other_quantity_requested="+other_quantity_requested+"&other_frequency_order="+other_frequency_order+"&other_other_quantity="+other_other_quantity+"&other_date_needed_by="+other_date_needed_by+"&other_need_pallets="+other_need_pallets+"&other_what_used_for="+other_what_used_for+"&other_quotereq_sales_flag="+other_quotereq_sales_flag+"&other_note="+other_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		} 
		
		function other_quote_cancel(othertableid){
			var company_id = document.getElementById("company_id"+othertableid).value;
			var client_dash_flg = document.getElementById("client_dash_flg"+othertableid).value;
			
			var other_quantity_requested = document.getElementById("other_quantity_requested"+othertableid).value;
			var other_other_quantity = document.getElementById("other_other_quantity"+othertableid).value;
			var other_frequency_order = document.getElementById("other_frequency_order"+othertableid).value;
			var other_what_used_for = document.getElementById("other_what_used_for"+othertableid).value;
			
			var other_date_needed_by =  "";
			var other_need_pallets;
			var other_note = document.getElementById("other_note"+othertableid).value;
			var other_quotereq_sales_flag = ""; //document.getElementById("other_quotereq_sales_flag"+othertableid).value;
			var quote_item = document.getElementById("quote_item"+othertableid).value;
			//
			if(document.getElementById("other_need_pallets"+othertableid).checked)
				{
					other_need_pallets = document.getElementById("other_need_pallets"+othertableid).value;
				}
			else{
					other_need_pallets= "";
			}
			other_quotereq_sales_flag= "";
			var quote_item = document.getElementById("quote_item"+othertableid).value;
		 
			//
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("other"+othertableid).innerHTML=xmlhttp.responseText;
		            //display table details
		            var other = document.getElementById("other_sub_table"+othertableid);
		            if (other.style.display === "none") {
		                other.style.display = "block";
		                document.getElementById("other_btn"+othertableid).innerHTML="Collapse Details";
						document.getElementById("other_btn_img"+othertableid).src="images/minus_icon.png";
		            } 
		            //
				}
			}
			xmlhttp.open("GET","quote_request_save_new.php?repchk=yes&otherupdatequotedata=2&othertableid="+othertableid+"&company_id="+company_id+"&other_quantity_requested="+other_quantity_requested+"&other_frequency_order="+other_frequency_order+"&other_other_quantity="+other_other_quantity+"&other_date_needed_by="+other_date_needed_by+"&other_need_pallets="+other_need_pallets+"&other_what_used_for="+other_what_used_for+"&other_quotereq_sales_flag="+other_quotereq_sales_flag+"&other_note="+other_note+"&quote_item="+quote_item+"&client_dash_flg="+client_dash_flg,true);
			xmlhttp.send();
		}
		
		function other_quote_delete(othertableid,quote_item,companyid){
		    var choice = confirm('Do you really want to delete this record?');
		    if(choice === true) {
		        //
		        var p="other";
		        //
		        if (window.XMLHttpRequest)
		        {// code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp=new XMLHttpRequest();
		        }
		        else
		        {// code for IE6, IE5
		        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange=function()
		        {
		            if (xmlhttp.readyState==4 && xmlhttp.status==200)
		            {
		                document.getElementById("other"+othertableid).innerHTML=xmlhttp.responseText;
		                 $("#show_q_div").load(location.href + " #show_q_div");
		            }
		        }
		        xmlhttp.open("GET","delete_quote_request.php?repchk=yes&editquotedata=1&p="+p+"&othertableid="+othertableid+"&quote_item="+quote_item+"&companyid="+companyid,true);
		        xmlhttp.send();
		    }
		    else{
		        
		    }
		}


		function show_loc_dtls(vendorB2bRescueId, cntSubRow, sortlocationType) { 
			//alert('vendorB2bRescueId ->'+vendorB2bRescueId+ ' // cntSubRow -> '+cntSubRow+' // sortlocationType -> '+sortlocationType ); 
			
			for (var i = 0; i < cntSubRow; i++) {
				var x = document.getElementById("loc_sub_table_"+i+"_"+vendorB2bRescueId);
				var shipFrom = document.getElementById("loc_btn_"+vendorB2bRescueId).innerHTML;
				if (x.style.display === "none") {
					x.style.display = "block";
					x.removeAttribute('style');
					document.getElementById("loc_btn_"+vendorB2bRescueId).innerHTML=shipFrom;
					document.getElementById("selrow"+vendorB2bRescueId).style.backgroundColor  = '#e1e8fb';

				} else {
					document.getElementById("selrow"+vendorB2bRescueId).style.backgroundColor  = 'gainsboro';
		
					x.style.display = "none";
					document.getElementById("loc_btn_"+vendorB2bRescueId).innerHTML=shipFrom;
				}
			}
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
					n_pos -= n_offset;
				e_elem = e_elem.parentNode;
			}
			return n_pos;
		}

		/*<!-- TEST GAYLORD MATCHING TOOL POPUP SECTION START -->*/	
		function display_request_gaylords_test(id, boxid, flg, viewflg, client_flg, load_all = 0, inboxprofile = 0 ){ 
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
	        var selectobject = document.getElementById("lightbox_g"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_gaylord_new1').style.display='block';
			if (inboxprofile == 1){
				//document.getElementById('light_gaylord_new1').style.left = n_left - 630 + 'px';
				document.getElementById('light_gaylord_new1').style.left = 50 + '%';
				n_left = n_left - 630;
			}else{
				//document.getElementById('light_gaylord_new1').style.left = n_left - 515 + 'px';
				n_left = n_left - 515;
				document.getElementById('light_gaylord_new1').style.left = 50 + '%';
			}			
	
			window.scrollTo(0,0);

			//document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
			document.getElementById('light_gaylord_new1').style.top = 10 + 'px';
	        document.getElementById('light_gaylord_new1').style.height = 580 + 'px';
			document.getElementById('light_gaylord_new1').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "GAYLORD MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			//if(flg==1 || boxid == 0){
			//   sstr = sstr + " selected ";
			//} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";

			//if(flg==3){
			//   sstr = sstr +"selected";
			//} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			//if(flg==2 && boxid != 0){
			   sstr = sstr +"selected";
			//}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
			//sstr = sstr + "<br>";
			//if (flg == 0) {
	         
			sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
			//
	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
			//sstr = sstr + "<br>";
			//if (flg == 0) {
	         
			sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
			
			//if client dash
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr + " selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				if(viewflg==2){
				   sstr = sstr + " selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'>By Item</option>";
			sstr = sstr +"<option value='2'>By Location</option>";
			sstr = sstr +" </select>";
		
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";

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
				//alert('res -> '+xmlhttp.responseText);
				if (xmlhttp.readyState==4 && xmlhttp.status==200){ 
					document.getElementById('light_gaylord_new1').style.background = 'rgba(255, 255, 255, 1)';
		            if (load_all == 0){
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText; 
					}else{
						document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText; 
					}					 
				}
			}
			
			xmlhttp.open("GET","quote_request_gaylords_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&gbox="+boxid+"&g_timing=2&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);
			xmlhttp.send();
		}
	    
	    function display_request_gaylords_child_test(id, flg, boxid, viewflg, client_flg, n_left,n_top){ 
			
	        var flgs = document.getElementById("sort_g_tool").value;
			var flgs_org = document.getElementById("sort_g_tool").value;
			var viewflgs = 2;
	       
			var g_timing = document.getElementById("g_timing").value;
			var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
			var sort_g_location = document.getElementById("sort_g_location").value;
			//alert('sort_g_location -> ' + sort_g_location);	
			var selectobject = document.getElementById("lightbox_g"+boxid); 
			//var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
					  
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(g_timing==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(g_timing==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(g_timing==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
	         
			sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(sort_g_tool2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location' id= 'sort_g_location' onChange='display_request_gaylords_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'";
				if(sort_g_location == 1){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Item</option>";
			sstr = sstr +"<option value='2'";
				if(sort_g_location == 2){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Location</option>";

			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var selectobject = document.getElementById("lightbox"); 
			document.getElementById('light_gaylord_new1').style.display='block';
			document.getElementById('light_gaylord_new1').style.left = 50 + '%'; //n_left + 'px';
			document.getElementById('light_gaylord_new1').style.top = 10 + 'px';
			document.getElementById('light_gaylord_new1').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

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
				  	document.getElementById('light_gaylord_new1').style.background = 'rgba(255, 255, 255, 1)';
					document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}

			xmlhttp.open("GET","quote_request_gaylords_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&client_flg="+client_flg+"&sort_g_location="+sort_g_location+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		/*<!-- TEST GAYLORD MATCHING TOOL POPUP SECTION ENDS -->*/

		function display_matching_tool_gaylords_v3(id, boxid, flg, viewflg, client_flg, load_all = 0, onlyftl = 0) 
		{ 
			
			var selectobject = document.getElementById("lightbox_gv3"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			
			document.getElementById('light_gaylord_newv3').style.display='block';
			n_left = n_left - 215;
			document.getElementById('light_gaylord_newv3').style.left = 20 + '%';
						
			//window.scrollTo(0,0);

			document.getElementById('light_gaylord_newv3').style.top = 10 + 'px';
			//document.getElementById('light_gaylord_newv3').style.left = 10 + 'px';
	        document.getElementById('light_gaylord_newv3').style.height = 580 + 'px';
			
			//var selectobject = document.getElementById("lightbox_g"+boxid); 
		//var n_left = f_getPosition(selectobject, 'Left');
		//var n_top  = f_getPosition(selectobject, 'Top');
		//document.getElementById('light_gaylord_newv3').style.display='block';
		//document.getElementById('light_gaylord_newv3').style.left = n_left + 20 + 'px';
		//document.getElementById('light_gaylord_newv3').style.top = n_top + 20 + 'px';
        //document.getElementById('light_gaylord_newv3').style.height = 580 + 'px';
			
			document.getElementById("light_gaylord_newv3").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle' colspan='6'>";
			sstr = sstr + "GAYLORD MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_newv3').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
			sstr = sstr + "<br></td></tr><tr><td class='display_maintitle'>&nbsp;&nbsp;&nbsp;Timing<br>";

			sstr = sstr + "&nbsp;&nbsp;&nbsp;<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'>";
			var gtiming = 4;
			sstr = sstr + "<option value='4'>Can ship in 2 weeks</option>";
			sstr = sstr + "<option value='5'>Can ship immediately</option>";
			sstr = sstr + "<option value='7'>Can ship this month</option>";
			sstr = sstr + "<option value='8'>Can ship next month</option>";
			sstr = sstr + "<option value='6'>Ready to ship whenever</option>";
			sstr = sstr + "<option value='9'>Enter ship by date</option>";
			sstr = sstr +"</select>";	
			sstr = sstr +"<input type='text' id='g_timing_enter_dt' name='g_timing_enter_dt' value='' placeholder='mm/dd/yyyy' style='width:100px; display:none;'>";	
			sstr = sstr +"<input type='button' id='g_timing_enter_dt_btn' name='g_timing_enter_dt_btn' value='Load' onClick='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")' style='display:none;'>";	
			sstr = sstr + "</td>";		
		   
			sstr = sstr + "<td class='display_maintitle'>";		
			sstr = sstr + "&nbsp;Status&nbsp;<br>"; 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + boxid + ")'><option value='1'";
			
			if(flg==1  || boxid == 0){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2 && boxid != 0){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
			
			sstr = sstr + "</td><td class='display_maintitle'>";
			sstr = sstr + "&nbsp;Criteria&nbsp;<br>";
			sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			sstr = sstr + "</td><td class='display_maintitle'>";

			sstr = sstr +"&nbsp;View&nbsp;<br>";

			//if client dash
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr + " selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'><option value='2'";
				if(viewflg==2){
				   sstr = sstr + " selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			
			}

			sstr = sstr + "</td>";
			
			sstr = sstr + "<td class='display_maintitle'><input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'>";
			sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";
			
			sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ","+ boxid + ")'>";
			sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_newv3').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";

			if (window.XMLHttpRequest)
			{
			  xmlhttp=new XMLHttpRequest();
			}
			else
			{
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{														//alert('res -> '+xmlhttp.responseText);
			  if (xmlhttp.readyState==4 && xmlhttp.status==200){ 
				 if (load_all == 0){
					document.getElementById("light_gaylord_newv3").innerHTML = '<link rel="stylesheet" type="text/css" href="css/newstylechange.css" /><br>' +sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_gaylord_newv3").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
			  }
			}

			xmlhttp.open("GET","quote_request_gaylords_tool_v3.php?ID="+id+"&gbox="+boxid+"&g_timing="+gtiming+"&onlyftl="+onlyftl+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+client_flg+"&fntend=boomerang&repchk=yes",true);
			xmlhttp.send();
		}
	
		function display_request_gaylords_child_v3(id, flg, boxid, viewflg, client_flg, n_left,n_top, orgboxid) 
		{ 
			var selectobject = document.getElementById("lightbox_gv3"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			
			document.getElementById('light_gaylord_newv3').style.display='block';
			n_left = n_left - 215;
			document.getElementById('light_gaylord_newv3').style.left = 20 + '%';
			document.getElementById('light_gaylord_newv3').style.top = 10 + 'px';
	        document.getElementById('light_gaylord_newv3').style.height = 580 + 'px';
		
			var flgs = document.getElementById("sort_g_tool").value;
			var flgs_org = document.getElementById("sort_g_tool").value;
			var viewflgs = document.getElementById("sort_g_view").value;
		   
			var g_timing = document.getElementById("g_timing").value;
			var g_timing_enter_dt = "";
			if (g_timing == 9)
			{
				document.getElementById("g_timing_enter_dt").style.display = "inline";
				document.getElementById("g_timing_enter_dt_btn").style.display = "inline";
				
				g_timing_enter_dt = document.getElementById("g_timing_enter_dt").value; 
			}
			
			var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
			//var sort_g_location = document.getElementById("sort_g_location").value;
			var sort_g_location = "";

			if(document.getElementById("canship_ltl").checked){
				var canship_ltl = 1;
			}else{
				var canship_ltl = 0;
			}

			if(document.getElementById("customer_pickup_allowed").checked){
				var customer_pickup = 1;
			}else{
				var customer_pickup = 0;
			}
			//alert('sort_g_location -> ' + sort_g_location);	
			if (document.getElementById("lightbox_gv3"+orgboxid)){
				var selectobject = document.getElementById("lightbox_gv3"+orgboxid); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
			}
			
			if(sort_g_tool2==2){
			   boxid = 0;
			}  
			if(sort_g_tool2==1){
			   boxid = orgboxid;
			}  

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle' colspan='6'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_newv3').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

			if(flgs==1){
				sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
			}
			if(flgs==2){
				sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
			}
			//sstr = sstr + "<br>";
			
			sstr = sstr + "</td></tr><tr><td class='display_maintitle'>";
			sstr = sstr + "Timing&nbsp;<br>";
			sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'>";

			sstr = sstr + "<option value='4'";
			if(g_timing == 4){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Can ship in 2 weeks</option>";
			sstr = sstr + "<option value='5'";
			if(g_timing == 5){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Can ship immediately</option>";
			sstr = sstr + "<option value='7'";
			if(g_timing == 7){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Can ship this month</option>";
			sstr = sstr + "<option value='8'";
			if(g_timing == 8){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Can ship next month</option>";
			sstr = sstr + "<option value='6'";
			if(g_timing == 6){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Ready to ship whenever</option>";
			sstr = sstr + "<option value='9'";
			if(g_timing == 9){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr + " >Enter ship by date</option>";
			
			sstr = sstr +"</select>";
			sstr = sstr +"<input type='text' id='g_timing_enter_dt' name='g_timing_enter_dt' value='' placeholder='mm/dd/yyyy' style='width: 100px; display:none;'>";	
			sstr = sstr +"<input type='button' id='g_timing_enter_dt_btn' name='g_timing_enter_dt_btn' value='Load' onClick='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")' style='display:none;'>";	
			sstr = sstr +"</td>";
			sstr = sstr +"<td class='display_maintitle'>";
			sstr = sstr + "Status&nbsp;<br>";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

			sstr = sstr + "</td><td class='display_maintitle'>";
			sstr = sstr + "Criteria&nbsp;<br>";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'><option value='1'";

			if(sort_g_tool2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			sstr = sstr + "</td><td class='display_maintitle'>";

			sstr = sstr +"View&nbsp;<br>";

			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}

			sstr = sstr + "</td>";
			
			sstr = sstr + "<td class='display_maintitle'><input type='checkbox' name='canship_ltl' id='canship_ltl' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")'";

			if(canship_ltl == 1){
				sstr = sstr + " checked ";
			}
			sstr = sstr + ">";
			sstr = sstr + "&nbsp;&nbsp;Can Ship LTL Only <br>";
			
			sstr = sstr + "<input type='checkbox' name='customer_pickup_allowed' id='customer_pickup_allowed' onChange='display_request_gaylords_child_v3(" + id + "," + flg + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ","+ orgboxid + ")' ";
			if(customer_pickup == 1){
				sstr = sstr + " checked ";
			}
			sstr = sstr + ">";
			sstr = sstr + "&nbsp;&nbsp;Customer Pickups Allowed Only";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			//var selectobject = document.getElementById("lightbox"); 
			//document.getElementById('light_gaylord_newv3').style.display='block';
			//document.getElementById('light_gaylord_newv3').style.left = 50 + '%';
			//document.getElementById('light_gaylord_newv3').style.top = 10 + 'px';
			
			document.getElementById("light_gaylord_newv3").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>";
			
			if (g_timing == 9 && g_timing_enter_dt == "")
			{
				document.getElementById("light_gaylord_newv3").innerHTML = '<link rel="stylesheet" type="text/css" href="css/newstylechange.css" /><br>' + sstr; 
				
				document.getElementById("g_timing_enter_dt").style.display = "inline";
				document.getElementById("g_timing_enter_dt_btn").style.display = "inline";
			}else{
				document.getElementById("light_gaylord_newv3").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

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
					document.getElementById("light_gaylord_newv3").innerHTML = '<link rel="stylesheet" type="text/css" href="css/newstylechange.css" /><br>' + sstr + xmlhttp.responseText; 
					
					if (g_timing == 9)
					{
						document.getElementById("g_timing_enter_dt").style.display = "inline";
						document.getElementById("g_timing_enter_dt_btn").style.display = "inline";
						
						document.getElementById("g_timing_enter_dt").value = g_timing_enter_dt;
					}
					
				  }
				}
				
				
				xmlhttp.open("GET", "quote_request_gaylords_tool_v3.php?ID="+id+"&gbox="+boxid+"&orgboxid="+orgboxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&client_flg="+client_flg+"&sort_g_location="+sort_g_location+"&canship_ltl="+canship_ltl+"&customer_pickup="+customer_pickup+"&g_timing_enter_dt="+g_timing_enter_dt+"&fntend=boomerang&repchk=yes",true);			
				xmlhttp.send();
			}
		}
		
		function calculate_delivery(inv_b2b_id, companyID, minfob){
		
			document.getElementById("td_cal_del"+inv_b2b_id).innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
			
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
				 //var aa = xmlhttp.responseText; 
				document.getElementById("td_cal_del"+inv_b2b_id).innerHTML = xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET", "uber_freight_matching_tool_v3.php?inv_b2b_id="+inv_b2b_id+"&companyID="+companyID+"&minfob="+minfob+"&repchk=yes",true);			
			xmlhttp.send();
		}

		/*<!-- TEST SHIPPING MATCHING TOOL POPUP SECTION START -->*/
		function display_request_shipping_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0, inboxprofile = 0) { 
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
			var selectobject = document.getElementById("lightbox_req_shipping"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_shipping').style.display='block';
			if (inboxprofile == 1){
				//document.getElementById('light_new_shipping').style.left = n_left - 630 + 'px';
				document.getElementById('light_new_shipping').style.left = 50 + '%';
				n_left = n_left - 630;
			}else{
				//document.getElementById('light_new_shipping').style.left = n_left - 515 + 'px';
				document.getElementById('light_new_shipping').style.left = 50 + '%';
				n_left = n_left - 515;
			}			
			document.getElementById('light_new_shipping').style.top = 10 + 'px';
			document.getElementById('light_new_shipping').style.height = 580 + 'px';

			window.scrollTo(0,0);
			
			document.getElementById('light_new_shipping').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_shipping").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "SHIPPING BOX MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			//if(flg==1 || boxid == 0){
			//   sstr = sstr + " selected ";
			//} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			//if(flg==3){
			//   sstr = sstr +"selected";
			//} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			//if(flg==2 && boxid != 0){
			   sstr = sstr +"selected";
			//}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
			sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + ","  + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

			sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + ","  + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + this.value + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +" selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + this.value + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_shipping' id= 'sort_g_location_shipping' onChange='display_request_shipping_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'>By Item</option>";
			sstr = sstr +"<option value='2'>By Location</option>";
			sstr = sstr +" </select>";
			
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "SHIPPING BOX MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
			
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
				  document.getElementById('light_new_shipping').style.background = 'rgba(255, 255, 255, 1)';
				 if (load_all == 0){
					document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_shipping").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
				 
			  }
			}
				
			xmlhttp.open("GET","quote_request_shipping_tool_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+ client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}		
		
		function display_request_shipping_child_test(id, flg, boxid, viewflg, client_flg, n_left,n_top) { 
	        var flgs = document.getElementById("sort_g_tool_shipping").value;
			var flgs_org = document.getElementById("sort_g_tool_shipping").value;
			var viewflgs = 2; //document.getElementById("sort_g_view_shipping").value;
			
			var g_timing_shipping = document.getElementById("g_timing_shipping").value;
			var sort_g_tool_shipping2 = document.getElementById("sort_g_tool_shipping2").value;
			var sort_g_location_shipping = document.getElementById("sort_g_location_shipping").value;
			//alert('sort_g_location_shipping -> ' + sort_g_location_shipping);
			
			var selectobject = document.getElementById("lightbox_req_shipping"+boxid); 
			//var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SHIPPING BOX MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }

			sstr = sstr + "<br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(g_timing_shipping==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(g_timing_shipping==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(g_timing_shipping==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(sort_g_tool_shipping2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool_shipping2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflgs==1){
			   		sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_shipping' id= 'sort_g_location_shipping' onChange='display_request_shipping_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'";
			if(sort_g_location_shipping == 1){
				sstr = sstr + " selected ";
			}
			sstr = sstr +">By Item</option>";
			sstr = sstr +"<option value='2'";
			if(sort_g_location_shipping == 2){
				sstr = sstr + " selected ";
			}
			sstr = sstr +">By Location</option>";
			sstr = sstr +" </select>";

			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var selectobject = document.getElementById("lightbox"); 
			document.getElementById('light_new_shipping').style.display='block';
			document.getElementById('light_new_shipping').style.left = 50 + '%'; //n_left + 'px';
			document.getElementById('light_new_shipping').style.top = 10 + 'px';
			
			document.getElementById('light_new_shipping').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_shipping").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 					

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
				  document.getElementById('light_new_shipping').style.background = 'rgba(255, 255, 255, 1)';
				document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_request_shipping_tool_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_shipping+"&sort_g_tool2="+ sort_g_tool_shipping2+"&client_flg="+ client_flg+"&sort_g_location_shipping="+sort_g_location_shipping+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);	 	
			xmlhttp.send();
		}	
		/*<!-- TEST SHIPPING MATCHING TOOL POPUP SECTION ENDS -->*/

		/*<!-- TEST PALLET MATCHING TOOL POPUP SECTION START -->*/
		
		function getScreenTop() {
			var w = 0;
			var h = 0;
			var userAgent = navigator.userAgent,
			  mobile = function() {
				return /\b(iPhone|iP[ao]d)/.test(userAgent) ||
				  /\b(iP[ao]d)/.test(userAgent) ||
				  /Android/i.test(userAgent) ||
				  /Mobile/i.test(userAgent);
			  }
			  
			  screenX = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft;
			  screenY = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop;
			  outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.documentElement.clientWidth;
			  outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : document.documentElement.clientHeight - 22;
			  targetWidth = mobile() ? null : w;
			  targetHeight = mobile() ? null : h;
			  V = screenX < 0 ? window.screen.width + screenX : screenX;
			  left = parseInt(V + (outerWidth - targetWidth) / 2, 10);
			  topval = parseInt(screenY + (outerHeight - targetHeight) / 2.5, 10);
			  
			  alert(screenY);
			  alert(outerHeight);
			  alert(targetHeight);

			return topval;
		}
		
		function display_request_Pallet_tool_test(id, flg, viewflg, client_flg, boxid, pallet_height=0, pallet_width=0, ctrlid =0, load_all = 0, inboxprofile = 0){ 
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all+"--"+pallet_height+"--"+pallet_width+"--"+ctrlid);
			
			if (boxid == 0){
				var selectobject = document.getElementById("lightbox_req_pal"+ctrlid); 
			}else{	
				var selectobject = document.getElementById("lightbox_req_pal"+boxid); 
			}
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all+"--"+pallet_height+"--"+pallet_width+"--"+ctrlid+"--"+selectobject);	
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_pal').style.display='block';
			if (inboxprofile == 1){
				//document.getElementById('light_new_pal').style.left = n_left - 630 + 'px';
				document.getElementById('light_new_pal').style.left = 50 + '%';
				n_left = n_left - 630;
			}else{
				//document.getElementById('light_new_pal').style.left = n_left - 515 + 'px';
				document.getElementById('light_new_pal').style.left = 50 + '%';
				n_left = n_left - 515;
			}		
			
			document.getElementById('light_new_pal').style.top = 10 + 'px';
			document.getElementById('light_new_pal').style.height = 580 + 'px';
			
			window.scrollTo(0,0);
			
			document.getElementById('light_new_pal').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_pal").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "PALLET MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			//if(flg==1 || boxid == 0){
			//   sstr = sstr + " selected ";
			//} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			//if(flg==3){
			//  sstr = sstr +"selected";
			//} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			//if(flg==2 && boxid != 0){
			   sstr = sstr +"selected";
			//}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
			sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

			sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
			
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
				
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_pallet' id= 'sort_g_location_pallet' onChange='display_request_pallet_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")' > "; 
			sstr = sstr +"<option value='1'>By Item</option>";
			sstr = sstr +"<option value='2'>By Location</option>";
			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";

			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
			
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
				  document.getElementById('light_new_pal').style.background = 'rgba(255, 255, 255, 1)';
			  	//alert('res -> '+xmlhttp.responseText);
				 if (load_all == 0){
					document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
			  }
			}
			
			xmlhttp.open("GET","quote_req_pallet_matching_new_test.php?first_load=1&repchk=yes&ID="+id+"&inboxprofile="+ inboxprofile+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+ "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
	    
	    function display_request_pallet_child_test(id, flg, boxid , viewflg, client_flg, n_left,n_top, pallet_height=0, pallet_width=0, ctrlid =0) { 
	        var flgs = document.getElementById("sort_g_tool_pallet").value;
			var flgs_org = document.getElementById("sort_g_tool_pallet").value;
			var viewflgs = 2; //document.getElementById("sort_g_view_pallet").value;

			var g_timing_pallet = document.getElementById("g_timing_pallet").value;
			var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;
			var sort_g_location_pallet = document.getElementById("sort_g_location_pallet").value;
			
			if (boxid == 0){
				var selectobject = document.getElementById("lightbox_req_pal"+ctrlid); 
			}else{	
				var selectobject = document.getElementById("lightbox_req_pal"+boxid); 
			}	

			//var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(g_timing_pallet==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(g_timing_pallet==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(g_timing_pallet==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";         
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(sort_g_tool_pallet2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool_pallet2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_pallet' id= 'sort_g_location_pallet' onChange='display_request_pallet_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")' > "; 
			sstr = sstr +"<option value='1'";
				if(sort_g_location_pallet == 1){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Item</option>";
			sstr = sstr +"<option value='2'";
				if(sort_g_location_pallet == 2){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Location</option>";
			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";

			var selectobject = document.getElementById("lightbox"); 

			document.getElementById('light_new_pal').style.display='block';
			document.getElementById('light_new_pal').style.left = 50 + '%'; //n_left + 'px';
			document.getElementById('light_new_pal').style.top = 10 + 'px';
			
			document.getElementById('light_new_pal').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_pal").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

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
				  document.getElementById('light_new_pal').style.background = 'rgba(255, 255, 255, 1)';
				document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}

			xmlhttp.open("GET","quote_req_pallet_matching_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_pallet+"&sort_g_tool2="+ sort_g_tool_pallet2 + "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&sort_g_location_pallet="+sort_g_location_pallet+"&compnewid=<?=$_REQUEST['compnewid'];?>", true);			
			xmlhttp.send();
		}
		/*<!-- TEST PALLET MATCHING TOOL POPUP SECTION ENDS -->*/

		/*<!-- TEST SUPERSACKS MATCHING TOOL POPUP SECTION START -->*/
		function display_request_supersacks_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0, inboxprofile = 0) { 

			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);

			var selectobject = document.getElementById("lightbox_req_supersacks"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_supersacks').style.display='block';
			if (inboxprofile == 1){
				//document.getElementById('light_new_supersacks').style.left = n_left - 630 + 'px';
				document.getElementById('light_new_supersacks').style.left = 50 + '%';
				n_left = n_left - 630;
			}else{
				//document.getElementById('light_new_supersacks').style.left = n_left - 515 + 'px';
				document.getElementById('light_new_supersacks').style.left = 50 + '%';
				n_left = n_left - 515;
			}			
			document.getElementById('light_new_supersacks').style.top = 10 + 'px';
	        document.getElementById('light_new_supersacks').style.height = 580 + 'px';
			
			window.scrollTo(0,0);
			
			document.getElementById('light_new_supersacks').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_supersacks").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "SUPERSACK MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			//if(flg==1 || boxid == 0){
			//  sstr = sstr + " selected ";
			//} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			//if(flg==3){
			//   sstr = sstr +"selected";
			//} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			//if(flg==2 && boxid != 0){
			   sstr = sstr +"selected";
			//}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
			if(viewflg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">UCB View</option><option value='2'";
			if(viewflg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Customer Facing View</option></select>";
*/
			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_supersack' id= 'sort_g_location_supersack' onChange='display_request_supersack_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'>By Item</option>";
			sstr = sstr +"<option value='2'>By Location</option>";
			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";

			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "SUPERSACK MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
				
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
				  document.getElementById('light_new_supersacks').style.background = 'rgba(255, 255, 255, 1)';
				 if (load_all == 0){
					document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_supersacks").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
			  }
			}			
			xmlhttp.open("GET","quote_req_supersacks_matching_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+viewflg+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
	    
	   	function display_request_supersack_child_test(id, flg, boxid, viewflg, client_flg, n_left,n_top) { 
	        var flgs = document.getElementById("sort_g_tool_supersack").value;
			var flgs_org = document.getElementById("sort_g_tool_supersack").value;
			var viewflgs = 2; //document.getElementById("sort_g_view_supersack").value;
			
			var g_timing_supersack = document.getElementById("g_timing_supersack").value;
			var sort_g_tool_supersack2 = document.getElementById("sort_g_tool_supersack2").value;
			var sort_g_location_supersack = document.getElementById("sort_g_location_supersack").value;
			//alert('sort_g_location_supersack -> ' + sort_g_location_supersack);
			
			var selectobject = document.getElementById("lightbox_req_supersacks"+boxid); 
			//var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SUPERSACK MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
				  
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(g_timing_supersack==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(g_timing_supersack==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(g_timing_supersack==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(sort_g_tool_supersack2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool_supersack2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_supersack' id= 'sort_g_location_supersack' onChange='display_request_supersack_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'";
				if(sort_g_location_supersack == 1){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Item</option>";
			sstr = sstr +"<option value='2'";
				if(sort_g_location_supersack == 2){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Location</option>";
			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
		
			var selectobject = document.getElementById("lightbox"); 

			document.getElementById('light_new_supersacks').style.display='block';
			document.getElementById('light_new_supersacks').style.left = 50 + '%'; //n_left + 'px';
			document.getElementById('light_new_supersacks').style.top = 10 + 'px';
			
			document.getElementById('light_new_supersacks').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_supersacks").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

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
				  document.getElementById('light_new_supersacks').style.background = 'rgba(255, 255, 255, 1)';
				document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_req_supersacks_matching_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_supersack+"&sort_g_tool2="+ sort_g_tool_supersack2+"&client_flg="+ client_flg+"&sort_g_location_supersack="+sort_g_location_supersack+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		/*<!-- TEST SUPERSACKS MATCHING TOOL POPUP SECTION ENDS -->*/

		/*<!-- TEST OTHER MATCHING  SECTION START -->*/
		function display_request_other_tool_test(id, flg, viewflg, client_flg, boxid, load_all = 0){ 
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all);
			var selectobject = document.getElementById("lightbox_req_other"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_other').style.display='block';
			//document.getElementById('light_new_other').style.left = n_left - 515 + 'px';
			document.getElementById('light_new_other').style.left = 50 + '%';
			document.getElementById('light_new_other').style.top = 10 + 'px';
	        document.getElementById('light_new_other').style.height = 580 + 'px';
			
			window.scrollTo(0,0);
			
			document.getElementById('light_new_other').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_other").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 						

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "OTHER MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1 || boxid == 0){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(flg==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(flg==2  && boxid != 0){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
			if(viewflg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">UCB View</option><option value='2'";
			if(viewflg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Customer Facing View</option></select>";
			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_other' id= 'sort_g_location_other' onChange='display_request_other_child_test(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'>By Item</option>";
			sstr = sstr +"<option value='2'>By Location</option>";
			sstr = sstr +" </select>";
			*/
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";

			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "OTHER MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
			
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
				  document.getElementById('light_new_other').style.background = 'rgba(255, 255, 255, 1)';
				 if (load_all == 0){
					document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_other").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
			  }
			}
			
			xmlhttp.open("GET","quote_req_other_matching_new_test.php?first_load=1&repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+viewflg+"&load_all="+load_all+"&client_flg="+client_flg,true);			
			xmlhttp.send();
		}
	    
	   function display_request_other_child_test(id, flg, boxid, viewflg, client_flg, n_left,n_top){ 
	        var flgs = document.getElementById("sort_g_tool_other").value;
			var flgs_org = document.getElementById("sort_g_tool_other").value;
			var viewflgs =  2; //document.getElementById("sort_g_view_other").value;

			var g_timing_other = document.getElementById("g_timing_other").value;
			var sort_g_tool_other2 = document.getElementById("sort_g_tool_other2").value;
			var sort_g_location_other = document.getElementById("sort_g_location_other").value;
			//alert('sort_g_location_other -> ' + sort_g_location_other);
			
			var selectobject = document.getElementById("lightbox_req_other"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >OTHER MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";

	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }

			sstr = sstr + "<br>";
	        sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(g_timing_other==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='3'";
			if(g_timing_other==3){
			   sstr = sstr +"selected";
			} 
			sstr = sstr +">Rdy < 3mo </option><option value='2'";	
			if(g_timing_other==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
	        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
	         
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flgs_org==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flgs_org==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

	        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

			sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(sort_g_tool_other2==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(sort_g_tool_other2==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
		
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
				
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/

			sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";			
			sstr = sstr +"Combine Item View&nbsp;&nbsp;";
			sstr = sstr +"<select class='basic_style' name='sort_g_location_other' id= 'sort_g_location_other' onChange='display_request_other_child_test(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")' > "; 
			sstr = sstr +"<option value='1'";
				if(sort_g_location_other == 1){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Item</option>";
			sstr = sstr +"<option value='2'";
				if(sort_g_location_other == 2){
					sstr = sstr + " selected ";
				}
			sstr = sstr +">By Location</option>";
			sstr = sstr +" </select>";
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
		
			var selectobject = document.getElementById("lightbox"); 

			document.getElementById('light_new_other').style.display='block';
			document.getElementById('light_new_other').style.left = 50 + '%'; //n_left - 630 + 'px';
			document.getElementById('light_new_other').style.top = 10 + 'px';
			
			document.getElementById('light_new_other').style.background = 'rgba(255, 255, 255, 0.7)';
			
			document.getElementById("light_new_other").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

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
				  document.getElementById('light_new_other').style.background = 'rgba(255, 255, 255, 1)';
				document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_req_other_matching_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_other+"&sort_g_tool2="+ sort_g_tool_other2+"&client_flg="+ client_flg+"&sort_g_location_other="+sort_g_location_other,true);			
			xmlhttp.send();
		}
		/*<!-- TEST OTHER MATCHING  SECTION ENDS -->*/

		function add_item_as_favorite(bid,bno){ //alert('bid - '+bid)
			
			//var boxtype = document.getElementById("fav_boxtype").value;
			var fav_qty_avail = document.getElementById("fav_qty_avail"+bid).value;
			var fav_estimated_next_load = document.getElementById("fav_estimated_next_load"+bid).value;
			var fav_expected_loads_per_mo = document.getElementById("fav_expected_loads_per_mo"+bid).value;
			var fav_boxes_per_trailer = document.getElementById("fav_boxes_per_trailer"+bid).value;
			var fav_fob = document.getElementById("fav_fob"+bid).value;
			var fav_miles = document.getElementById("fav_miles"+bid).value;

			var fav_bl = document.getElementById("fav_bl"+bid).value;
			var fav_bw = document.getElementById("fav_bw"+bid).value;
			var fav_bh = document.getElementById("fav_bh"+bid).value;
			var fav_walls = document.getElementById("fav_walls"+bid).value;
			var fav_desc = document.getElementById("fav_desc"+bid).value;
			var fav_shipfrom = document.getElementById("fav_shipfrom"+bid).value;
			var fav_match_id = document.getElementById("fav_match_id").value;
			var fav_match_boxid = document.getElementById("fav_match_boxid").value;
			var fav_match_flg = document.getElementById("fav_match_flg").value;
			var fav_match_viewflg = document.getElementById("fav_match_viewflg").value;

			var fav_match_client_flg = document.getElementById("fav_match_client_flg").value;
			var fav_match_load_all = document.getElementById("fav_match_load_all").value;
			var fav_match_inboxprofile = document.getElementById("fav_match_inboxprofile").value;
			
			//
			var boxtype;
			if(bno==1){
				boxtype="g";
			}
			if(bno==2){
				boxtype="sb";
			}
			if(bno==3){
				boxtype="sup";
			}
			if(bno==4){
				boxtype="pal";
			}
			//
			
			
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
				  if(xmlhttp.responseText=="done"){
					 alert("Added an item as a favorite"); 
				  }
				//alert(boxtype);
					if(boxtype=='g')
					{
						document.getElementById("fav_div_display"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='remove_item_as_favorite("+bid+",1)' ><img src='images/fav.png' width='10px' height='10px'></a>";
						//display_request_gaylords_test(fav_match_id, fav_match_boxid, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_load_all, fav_match_inboxprofile);
					}
					if(boxtype=='sb')
					{
						//alert(" boxtype - "+boxtype+" / fav_match_id - "+fav_match_id+" / fav_match_flg - "+fav_match_flg+" / fav_match_viewflg - "+fav_match_viewflg+" / fav_match_client_flg - "+fav_match_client_flg+" / fav_match_boxid - "+fav_match_boxid+" / fav_match_load_all - "+fav_match_load_all)
						
						document.getElementById("fav_div_display_ship"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='remove_item_as_favorite("+bid+",2)' ><img src='images/fav.png' width='10px' height='10px'></a>";
						//display_request_shipping_tool_test(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all, fav_match_inboxprofile); 
					}
				  	if(boxtype=='sup')
					{
						//alert(" boxtype - "+boxtype+" / fav_match_id - "+fav_match_id+" / fav_match_flg - "+fav_match_flg+" / fav_match_viewflg - "+fav_match_viewflg+" / fav_match_client_flg - "+fav_match_client_flg+" / fav_match_boxid - "+fav_match_boxid+" / fav_match_load_all - "+fav_match_load_all)
						document.getElementById("fav_div_display_sup"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='remove_item_as_favorite("+bid+",3)' ><img src='images/fav.png' width='10px' height='10px'></a>";
						//display_request_supersacks_tool_test(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all, fav_match_inboxprofile); 
					}
				  	if(boxtype=='pal')
					{
						//alert(" boxtype - "+boxtype+" / fav_match_id - "+fav_match_id+" / fav_match_flg - "+fav_match_flg+" / fav_match_viewflg - "+fav_match_viewflg+" / fav_match_client_flg - "+fav_match_client_flg+" / fav_match_boxid - "+fav_match_boxid+" / fav_match_load_all - "+fav_match_load_all)
						document.getElementById("fav_div_display_pal"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='remove_item_as_favorite("+bid+",4)' ><img src='images/fav.png' width='10px' height='10px'></a>";
						//display_request_Pallet_tool_test(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, 0,0,0, fav_match_load_all, fav_match_inboxprofile); 
					}
				 	if(boxtype=='other')
					{
						//alert(" boxtype - "+boxtype+" / fav_match_id - "+fav_match_id+" / fav_match_flg - "+fav_match_flg+" / fav_match_viewflg - "+fav_match_viewflg+" / fav_match_client_flg - "+fav_match_client_flg+" / fav_match_boxid - "+fav_match_boxid+" / fav_match_load_all - "+fav_match_load_all)
						document.getElementById("fav_div_display_other"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='remove_item_as_favorite("+bid+",5)' ><img src='images/fav.png' width='10px' height='10px'></a>";
						//display_request_other_tool_test(fav_match_id, fav_match_flg, fav_match_viewflg, fav_match_client_flg, fav_match_boxid, fav_match_load_all, fav_match_inboxprofile); 
					}
			  	}
			}

			xmlhttp.open("GET","add_favorite_inv_item.php?repchk=yes&bid="+bid+"&fav_match_id="+fav_match_id+"&fav_qty_avail="+fav_qty_avail+"&fav_estimated_next_load="+fav_estimated_next_load+"&fav_expected_loads_per_mo="+fav_expected_loads_per_mo+"&fav_boxes_per_trailer="+fav_boxes_per_trailer+"&fav_bl="+ fav_bl + "&fav_bw="+fav_bw+"&fav_bh="+fav_bh+"&fav_walls="+fav_walls+"&fav_fob="+fav_fob+"&fav_desc="+fav_desc+"&fav_shipfrom="+fav_shipfrom+"&boxtype="+boxtype+"&fav_miles="+fav_miles,true);			
			xmlhttp.send();
		}	
		//
		function remove_item_as_favorite(bid,bno){ //alert('bid - '+bid)
			var boxtype = document.getElementById("fav_boxtype").value;
			var fav_qty_avail = document.getElementById("fav_qty_avail"+bid).value;
			var fav_estimated_next_load = document.getElementById("fav_estimated_next_load"+bid).value;
			var fav_expected_loads_per_mo = document.getElementById("fav_expected_loads_per_mo"+bid).value;
			var fav_boxes_per_trailer = document.getElementById("fav_boxes_per_trailer"+bid).value;
			var fav_fob = document.getElementById("fav_fob"+bid).value;
			var fav_miles = document.getElementById("fav_miles"+bid).value;
			var fav_bl = document.getElementById("fav_bl"+bid).value;
			var fav_bw = document.getElementById("fav_bw"+bid).value;
			var fav_bh = document.getElementById("fav_bh"+bid).value;
			var fav_walls = document.getElementById("fav_walls"+bid).value;
			var fav_desc = document.getElementById("fav_desc"+bid).value;
			var fav_shipfrom = document.getElementById("fav_shipfrom"+bid).value;
			//
			var fav_match_id = document.getElementById("fav_match_id").value;
			var fav_match_boxid = document.getElementById("fav_match_boxid").value;
			var fav_match_flg = document.getElementById("fav_match_flg").value;
			var fav_match_viewflg = document.getElementById("fav_match_viewflg").value;
			var fav_match_client_flg = document.getElementById("fav_match_client_flg").value;
			var fav_match_load_all = document.getElementById("fav_match_load_all").value;
			var fav_match_inboxprofile = document.getElementById("fav_match_inboxprofile").value;
			//
			
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
				  if(xmlhttp.responseText=="done"){
					 alert("Removed an item as a favorite"); 
				  }
				  //
					var boxtype;
					if(bno==1){
						boxtype="g";
					}
					if(bno==2){
						boxtype="sb";
					}
					if(bno==3){
						boxtype="sup";
					}
					if(bno==4){
						boxtype="pal";
					}
				  	if(bno==5){
						boxtype="other";
					}
					//
				  	//alert(boxtype+bid);
				  	//
					if(boxtype=='g')
					{
						document.getElementById("fav_div_display"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='add_item_as_favorite("+bid+",1)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>";
						
					}
					if(boxtype=='sb')
					{
						
						document.getElementById("fav_div_display_ship"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='add_item_as_favorite("+bid+",2)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>";
						 
					}
				  	if(boxtype=='sup')
					{
						
						document.getElementById("fav_div_display_sup"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='add_item_as_favorite("+bid+",3)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>";
						
					}
				  	if(boxtype=='pal')
					{
						
						document.getElementById("fav_div_display_pal"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='add_item_as_favorite("+bid+",4)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>";
						
					}
				 	if(boxtype=='other')
					{	
						document.getElementById("fav_div_display_other"+bid).innerHTML ="<a id='div_favourite"+bid+"' href='javascript:void(0);' onClick='add_item_as_favorite("+bid+",5)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>";
						
					}
			  	}
			}

			xmlhttp.open("GET","remove_favorite_inv_item.php?repchk=yes&bid="+bid+"&fav_match_id="+fav_match_id+"&fav_qty_avail="+fav_qty_avail+"&fav_estimated_next_load="+fav_estimated_next_load+"&fav_expected_loads_per_mo="+fav_expected_loads_per_mo+"&fav_boxes_per_trailer="+fav_boxes_per_trailer+"&fav_bl="+ fav_bl + "&fav_bw="+fav_bw+"&fav_bh="+fav_bh+"&fav_walls="+fav_walls+"&fav_fob="+fav_fob+"&fav_desc="+fav_desc+"&fav_shipfrom="+fav_shipfrom+"&boxtype="+boxtype+"&fav_miles="+fav_miles,true);			
			xmlhttp.send();
		}

		/* generic matching tool start */
		function display_gaylords_autoload(id, flg) {
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td bgcolor='#FF9900'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
			sstr = sstr + "<br>";
			if (flg == 0) {
				sstr = sstr + "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
				sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id + ", 1 ,0,0)'>Display Only Available Boxes</a>";
			} else {
				sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
				sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id + ", 0,0,0)'>Display All Boxes</a>";
			} 
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			if (window.XMLHttpRequest)
			{
			  xmlhttpauto=new XMLHttpRequest();
			}
			else
			{
			  xmlhttpauto=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttpauto.onreadystatechange=function()
			{
			  if (xmlhttpauto.readyState==4 && xmlhttpauto.status==200)
			  {
				document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttpauto.responseText; 
				document.getElementById("gayloardtoolautoload").innerHTML = "Data loaded."; 
			  }
			}
			xmlhttpauto.open("GET","gaylords_mrg.php?repchk=yes&ID="+id+"&display-allrec="+flg,true);			
			xmlhttpauto.send();
		}
		
		function display_gaylords(id, flg) { 
			if (document.getElementById("light_gaylord_new").innerHTML == "") {
				var selectobject = document.getElementById("lightbox"); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new').style.display='block';
				document.getElementById('light_gaylord_new').style.left = n_left - 515 + 'px';
				document.getElementById('light_gaylord_new').style.top = 10 + 'px';
				
				document.getElementById('light_gaylord_new').style.background = 'rgba(255, 255, 255, 0.7)';
			
				document.getElementById("light_gaylord_new").innerHTML = "<br/><br/><br/><div style='width:100%; text-align:center;' >Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' /></div>"; 				

				var sstr = "";		
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr +  "<td bgcolor='#FF9900'>";
				sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>"; 
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
				sstr = sstr + "<br>";
				if (flg == 0) {
					sstr = sstr + "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
					sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id + ", 1 ," + n_left + "," + n_top + ")'>Display Only Available Boxes</a>";
				} else {
					sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
					sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id + ", 0," + n_left + "," + n_top + ")'>Display All Boxes</a>";
				} 
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
				
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
					document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttp.responseText; 
				  }
				}
				xmlhttp.open("GET","gaylords_mrg.php?repchk=yes&ID="+id+"&display-allrec="+flg,true);			
				xmlhttp.send();
			}
			else 
			{
				var selectobject = document.getElementById("lightbox"); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new').style.display='block';
				document.getElementById('light_gaylord_new').style.left = n_left - 150 + 'px';
				document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';
			}
		}
		
		function display_request_gaylords(id, boxid, flg, viewflg, load_all = 0){ 
				var selectobject = document.getElementById("lightbox_g"+boxid); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_gaylord_new1').style.display='block';
				document.getElementById('light_gaylord_new1').style.left = n_left - 150 + 'px';
				document.getElementById('light_gaylord_new1').style.top = n_top - 200 + 'px';
	            document.getElementById('light_gaylord_new1').style.height = 580 + 'px';
				
				document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

				var sstr = "";		
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr +  "<td class='display_maintitle'>";
				sstr = sstr + "GAYLORD MATCHING TOOL"; 
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +" selected ";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +" selected ";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2' ";
				if(flg==2){
				   sstr = sstr +" selected ";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1' ";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2' ";
				if(viewflg==2){
				   sstr = sstr + " selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
				
				var sstr_load_all = "";		
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL"; 
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				
				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";
							
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
	                 if (load_all == 0){
						document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText; 
					 }else{
						document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText; 
					 }					 
				  }
				}
				
				xmlhttp.open("GET","quote_request_gaylords_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
				xmlhttp.send();
		}	    
		
	    function display_request_gaylords_child(id, flg, boxid, viewflg, n_left,n_top){ 
	        var flgs = document.getElementById("sort_g_tool").value;
			var flgs_org = document.getElementById("sort_g_tool").value;
			var viewflgs = 2;			
			var g_timing = document.getElementById("g_timing").value;
			var sort_g_tool2 = document.getElementById("sort_g_tool2").value;
			
			var selectobject = document.getElementById("lightbox_g"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
	        //
	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

				if(g_timing==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(g_timing==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flgs_org==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flgs_org==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>";
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" + id + "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

				if(sort_g_tool2==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(sort_g_tool2==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				sstr = sstr + "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflgs==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflgs==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
			
			var selectobject = document.getElementById("lightbox"); 
			//var n_left = f_getPosition(selectobject, 'Left');
			//var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_gaylord_new1').style.display='block';
			//document.getElementById('light_gaylord_new1').style.left = n_left - 150 + 'px';
			document.getElementById('light_gaylord_new1').style.left = 50 + '%';
			document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
			
			document.getElementById("light_gaylord_new1").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

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
				document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_request_gaylords_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		function display_request_shipping_tool(id, flg, viewflg, client_flg, boxid, load_all = 0){
			var selectobject = document.getElementById("lightbox_req_shipping"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_shipping').style.display='block';
			document.getElementById('light_new_shipping').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_shipping').style.top = n_top - 200 + 'px';
			document.getElementById('light_new_shipping').style.height = 580 + 'px';
			document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 	
	        var g_timing = 1;
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "SHIPPING BOX MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
	                   g_timing = 1;
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
	                   g_timing = 2;
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
			sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + ","  + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
			//
			sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + viewflg + ","+ client_flg + ","  + n_left + "," + n_top + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +" selected ";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flg + "," + boxid + "," + this.value + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +" selected ";
				}  
				sstr = sstr +">Customer Facing View</option></select>";			
			*/	
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";
			
			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "SHIPPING BOX MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
			
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
				 if (load_all == 0){
					document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_shipping").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
				 
			  }
			}
			xmlhttp.open("GET","quote_request_shipping_tool_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+ client_flg+"&g_timing="+g_timing+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		
		function display_request_shipping_child(id, flg, boxid, viewflg, client_flg, n_left,n_top) { 
	        var flgs = document.getElementById("sort_g_tool_shipping").value;
			var flgs_org = document.getElementById("sort_g_tool_shipping").value;
			var viewflgs = document.getElementById("sort_g_view_shipping").value;
			
			var g_timing_shipping = document.getElementById("g_timing_shipping").value;
			var sort_g_tool_shipping2 = document.getElementById("sort_g_tool_shipping2").value;
			
			var selectobject = document.getElementById("lightbox_req_shipping"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SHIPPING BOX MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }

			sstr = sstr + "<br>";

				//new code		  
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(g_timing_shipping==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(g_timing_shipping==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + ","+ client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flgs_org==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flgs_org==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(sort_g_tool_shipping2==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(sort_g_tool_shipping2==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				//if(client_flg!=1)
				//{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if(viewflgs==1){
				   		sstr = sstr + " selected ";
					} 
					sstr = sstr +">UCB View</option><option value='2'";
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

			//New code
			var selectobject = document.getElementById("lightbox"); 
			document.getElementById('light_new_shipping').style.display='block';
			document.getElementById('light_new_shipping').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';
			
			document.getElementById("light_new_shipping").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

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
				document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_request_shipping_tool_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_shipping+"&sort_g_tool2="+ sort_g_tool_shipping2+"&client_flg="+ client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);	 	
			xmlhttp.send();
		}
		function display_request_supersacks_tool(id, flg, viewflg, client_flg, boxid, load_all = 0) { 
				var selectobject = document.getElementById("lightbox_req_supersacks"+boxid); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_supersacks').style.display='block';
				document.getElementById('light_new_supersacks').style.left = n_left - 150 + 'px';
				document.getElementById('light_new_supersacks').style.top = n_top - 200 + 'px';
	            document.getElementById('light_new_supersacks').style.height = 580 + 'px';
				
				document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

				var sstr = "";		
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr +  "<td class='display_maintitle'>";
				sstr = sstr + "SUPERSACK MATCHING TOOL"; 
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";		
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "SUPERSACK MATCHING TOOL"; 
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				
				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";
				
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
					 if (load_all == 0){
						document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText; 
					 }else{
						document.getElementById("light_new_supersacks").innerHTML = sstr_load_all + xmlhttp.responseText; 
					 }					 
				  }
				}
				
				xmlhttp.open("GET","quote_req_supersacks_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&display_view="+viewflg+"&load_all="+load_all+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
				xmlhttp.send();
		}	    
	   	function display_request_supersack_child(id, flg, boxid, viewflg, client_flg, n_left,n_top){ 
	        var flgs = document.getElementById("sort_g_tool_supersack").value;
			var flgs_org = document.getElementById("sort_g_tool_supersack").value;
			var viewflgs = document.getElementById("sort_g_view_supersack").value;
	       //alert(boxid);
	        //
			
			var g_timing_supersack = document.getElementById("g_timing_supersack").value;
			var sort_g_tool_supersack2 = document.getElementById("sort_g_tool_supersack2").value;
			
			var selectobject = document.getElementById("lightbox_req_supersacks"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SUPERSACK MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
	        //
	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
			//if (flg == 0) {
	          //  alert(flgs);

				//new code		  
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(g_timing_supersack==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(g_timing_supersack==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flgs_org==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flgs_org==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(sort_g_tool_supersack2==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(sort_g_tool_supersack2==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				if(client_flg!=1)
				{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if(viewflgs==1){
					   sstr = sstr + " selected ";
					} 
					sstr = sstr +">UCB View</option><option value='2'";
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}
				else{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}*/
				
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
		
			var selectobject = document.getElementById("lightbox"); 
			//var n_left = f_getPosition(selectobject, 'Left');
			//var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_supersacks').style.display='block';
			document.getElementById('light_new_supersacks').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';
			
			document.getElementById("light_new_supersacks").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

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
				document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_req_supersacks_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_supersack+"&sort_g_tool2="+ sort_g_tool_supersack2+"&client_flg="+ client_flg+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		function display_request_Pallet_tool(id, flg, viewflg, client_flg, boxid, pallet_height=0, pallet_width=0, ctrlid =0, load_all = 0){ 
			if (boxid == 0){
				var selectobject = document.getElementById("lightbox_req_pal"+ctrlid); 
			}else{	
				var selectobject = document.getElementById("lightbox_req_pal"+boxid); 
			}	
			//alert(id+"--"+boxid+"--"+flg+"--"+viewflg+"--"+client_flg+"--"+load_all+"--"+pallet_height+"--"+pallet_width+"--"+ctrlid+"--"+selectobject);
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_pal').style.display='block';
			document.getElementById('light_new_pal').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_pal').style.top = n_top - 200 + 'px';
			document.getElementById('light_new_pal').style.height = 580 + 'px';
			
			document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr +  "<td class='display_maintitle'>";
			sstr = sstr + "PALLET MATCHING TOOL"; 
			sstr = sstr + "&nbsp;&nbsp;";
			sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			sstr = sstr + "Timing&nbsp;&nbsp;";
			sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Rdy Now + Presell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">FTL Rdy Now ONLY</option>";
			sstr = sstr +"</select>";			
			sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Available to Sell</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 

			sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
			 
			sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

			if(flg==1){
			   sstr = sstr + " selected ";
			} 
			sstr = sstr +">Matching</option><option value='2'";
			if(flg==2){
			   sstr = sstr +"selected";
			}  
			sstr = sstr +">All Items (Ignore Criteria)</option></select>";

			/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			
			sstr = sstr +"View&nbsp;&nbsp;";
			
			if(client_flg!=1)
			{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}
			else{
				sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
				
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
			}*/
			
			
			sstr = sstr + "</td>";
			sstr = sstr + "</tr>";
			sstr = sstr + "</table>";

			var sstr_load_all = "";		
			sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr_load_all = sstr_load_all + "<tr align='center'>";
			sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
			sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL"; 
			sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
			sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
			
			sstr_load_all = sstr_load_all + "</td>";
			sstr_load_all = sstr_load_all + "</tr>";
			sstr_load_all = sstr_load_all + "</table>";
			
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
				 if (load_all == 0){
					document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText; 
				 }else{
					document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText; 
				 }					 
			  }
			}
			xmlhttp.open("GET","quote_req_pallet_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+ "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&load_all="+load_all+"&compnewid=<?=$_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
	    function display_request_pallet_child(id, flg, boxid , viewflg, client_flg, n_left,n_top, pallet_height=0, pallet_width=0, ctrlid =0) { 
	        var flgs = document.getElementById("sort_g_tool_pallet").value;
			var flgs_org = document.getElementById("sort_g_tool_pallet").value;
			var viewflgs = document.getElementById("sort_g_view_pallet").value;
	       //alert(flgs);
	        //
			
			var g_timing_pallet = document.getElementById("g_timing_pallet").value;
			var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;
			
			if (boxid == 0){
				var selectobject = document.getElementById("lightbox_req_pal"+ctrlid); 
			}else{	
				var selectobject = document.getElementById("lightbox_req_pal"+boxid); 
			}	

			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
	        //
	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			sstr = sstr + "<br>";
			
				//new code		  
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if(g_timing_pallet==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(g_timing_pallet==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if(flgs_org==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flgs_org==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";

				if(sort_g_tool_pallet2==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(sort_g_tool_pallet2==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				if(client_flg!=1)
				{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='1'";
					if(viewflgs==1){
					   sstr = sstr + " selected ";
					} 
					sstr = sstr +">UCB View</option><option value='2'";
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}
				else{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + "," + pallet_height + "," + pallet_width + "," + ctrlid + ")'><option value='2'";
					
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

			var selectobject = document.getElementById("lightbox"); 
			//var n_left = f_getPosition(selectobject, 'Left');
			//var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_pal').style.display='block';
			document.getElementById('light_new_pal').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
			
			document.getElementById("light_new_pal").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

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
				document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}

			xmlhttp.open("GET","quote_req_pallet_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_pallet+"&sort_g_tool2="+ sort_g_tool_pallet2 + "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&compnewid=<?=$_REQUEST['compnewid'];?>", true);			
			xmlhttp.send();
		}
		function display_request_other_tool(id, flg, viewflg, client_flg, boxid, load_all = 0){
				var selectobject = document.getElementById("lightbox_req_other"+boxid); 
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top  = f_getPosition(selectobject, 'Top');
				document.getElementById('light_new_other').style.display='block';
				document.getElementById('light_new_other').style.left = n_left - 150 + 'px';
				document.getElementById('light_new_other').style.top = n_top + 20 + 'px';
	            document.getElementById('light_new_other').style.height = 580 + 'px';
				
				document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

				var sstr = "";		
				sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr = sstr + "<tr align='center'>";
				sstr = sstr +  "<td class='display_maintitle'>";
				sstr = sstr + "OTHER MATCHING TOOL"; 
				sstr = sstr + "&nbsp;&nbsp;";
				sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + viewflg + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(flg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flg + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
				if(viewflg==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">UCB View</option><option value='2'";
				if(viewflg==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Customer Facing View</option></select>";
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";

				var sstr_load_all = "";		
				sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
				sstr_load_all = sstr_load_all + "<tr align='center'>";
				sstr_load_all = sstr_load_all +  "<td class='display_maintitle'>";
				sstr_load_all = sstr_load_all + "OTHER MATCHING TOOL"; 
				sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
				sstr_load_all = sstr_load_all + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
				
				sstr_load_all = sstr_load_all + "</td>";
				sstr_load_all = sstr_load_all + "</tr>";
				sstr_load_all = sstr_load_all + "</table>";
				
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
					 if (load_all == 0){
						document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText; 
					 }else{
						document.getElementById("light_new_other").innerHTML = sstr_load_all + xmlhttp.responseText; 
					 }					 
				  }
				}
				xmlhttp.open("GET","quote_req_other_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&display_view="+viewflg+"&load_all="+load_all,true);			
				xmlhttp.send();
		}
	   	function display_request_other_child(id, flg, boxid, viewflg, client_flg, n_left,n_top){ 
	        var flgs = document.getElementById("sort_g_tool_other").value;
			var flgs_org = document.getElementById("sort_g_tool_other").value;
			var viewflgs = document.getElementById("sort_g_view_other").value;
	       //alert(boxid);
	        //
			
			var g_timing_other = document.getElementById("g_timing_other").value;
			var sort_g_tool_other2 = document.getElementById("sort_g_tool_other2").value;
			
			var selectobject = document.getElementById("lightbox_req_other"+boxid); 
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top  = f_getPosition(selectobject, 'Top');
		
			var sstr = "";		
			sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
			sstr = sstr + "<tr align='center'>";
			sstr = sstr + "<td class='display_maintitle'>";
			sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >OTHER MATCHING TOOL</font>"; 
			sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
	        sstr = sstr + "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_other').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
	        //
	        if(flgs==1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
	        if(flgs==2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }

	       /* if(sort_g_tool_supersack2 == 1){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
	        }
			if(sort_g_tool_other2 == 2){
	            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
	        }*/
	        //
			sstr = sstr + "<br>";
			//if (flg == 0) {
	          //  alert(flgs);

				//new code		  
	            sstr = sstr + "Timing&nbsp;&nbsp;";
				sstr = sstr + "<select class='basic_style' name='g_timing_other' id='g_timing_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(g_timing_other==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Rdy Now + Presell</option><option value='2'";
				if(g_timing_other==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">FTL Rdy Now ONLY</option>";
				sstr = sstr +"</select>";			
	            sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other' id='sort_g_tool_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(flgs_org==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Available to Sell</option><option value='2'";
				if(flgs_org==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">Available to Sell + Potential to Get</option></select>"; 
				//
	            sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
				//sstr = sstr + "<br>";
				//if (flg == 0) {
	             
				sstr = sstr + "<select class='basic_style' name='sort_g_tool_other2' id='sort_g_tool_other2' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + viewflgs + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";

				if(sort_g_tool_other2==1){
				   sstr = sstr + " selected ";
				} 
				sstr = sstr +">Matching</option><option value='2'";
				if(sort_g_tool_other2==2){
				   sstr = sstr +"selected";
				}  
				sstr = sstr +">All Items (Ignore Criteria)</option></select>";

				/*sstr = sstr +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				
				sstr = sstr +"View&nbsp;&nbsp;";
			
				if(client_flg!=1)
				{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='1'";
					if(viewflgs==1){
					   sstr = sstr + " selected ";
					} 
					sstr = sstr +">UCB View</option><option value='2'";
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}
				else{
					sstr = sstr + "<select class='basic_style' name='sort_g_view_other' id='sort_g_view_other' onChange='display_request_other_child(" + id + "," + flgs + "," + boxid + "," + this.value + "," + client_flg + "," + n_left + "," + n_top + ")'><option value='2'";
					
					if(viewflgs==2){
					   sstr = sstr +"selected";
					}  
					sstr = sstr +">Customer Facing View</option></select>";
				}
				*/
				sstr = sstr + "</td>";
				sstr = sstr + "</tr>";
				sstr = sstr + "</table>";
		
			var selectobject = document.getElementById("lightbox"); 
			//var n_left = f_getPosition(selectobject, 'Left');
			//var n_top  = f_getPosition(selectobject, 'Top');
			document.getElementById('light_new_other').style.display='block';
			document.getElementById('light_new_other').style.left = n_left - 150 + 'px';
			document.getElementById('light_new_other').style.top = n_top + 20 + 'px';
			
			document.getElementById("light_new_other").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />"; 				

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
				document.getElementById("light_new_other").innerHTML = sstr + xmlhttp.responseText; 
			  }
			}
			
			xmlhttp.open("GET","quote_req_other_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_other+"&sort_g_tool2="+ sort_g_tool_other2+"&client_flg="+ client_flg,true);			
			xmlhttp.send();
		}
		/* generic matching tool ends */
		function new_inventory_filter() {
			document.getElementById("new_inv").innerHTML  = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>"; 
		    //
		   	var sort_g_view = document.getElementById("sort_g_view").value;
			var sort_g_tool = document.getElementById("sort_g_tool").value;
			var g_timing = document.getElementById("g_timing").value;
			//
			var fld = document.getElementById('search_tag');
			var values = [];
			if (fld){
				for (var i = 0; i < fld.options.length; i++) {
				  if (fld.options[i].selected) {
					values.push(fld.options[i].value);
				  }
				}
			}	
			//
			//alert('sort_g_view -> '+sort_g_view+" / sort_g_tool -> "+sort_g_tool+" / g_timing -> "+g_timing+" / fld -> "+fld)
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
					document.getElementById("new_inv").innerHTML = xmlhttp.responseText; 
				}
			}

			//"&search_tag=" + values
			xmlhttp.open("GET","display_filter_inventory.php?repchk=yes&sort_g_view=" + sort_g_view + "&sort_g_tool=" + sort_g_tool + "&g_timing=" + g_timing + "&search_tag=" + values ,true);	
			xmlhttp.send();
		}

		function displayboxdata_invnew(colid, sortflg, box_type_cnt) {
			document.getElementById("btype"+box_type_cnt).innerHTML  = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>"; 
			//
			var sort_g_view = document.getElementById("sort_g_view").value;
			var sort_g_tool = document.getElementById("sort_g_tool").value;
			var g_timing = document.getElementById("g_timing").value;
			//
			var fld = document.getElementById('search_tag');
			var values = [];
			for (var i = 0; i < fld.options.length; i++) {
			  if (fld.options[i].selected) {
				values.push(fld.options[i].value);
			  }
			}
			//

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
					document.getElementById("btype"+box_type_cnt).innerHTML = xmlhttp.responseText; 
				}
			}

			xmlhttp.open("GET","dashboard_inv_sort.php?repchk=yes&colid=" + colid + "&sortflg=" + sortflg + "&sort_g_view=" + sort_g_view+ "&sort_g_tool=" + sort_g_tool+ "&g_timing=" + g_timing+ "&box_type_cnt=" + box_type_cnt+ "&search_tag=" + values ,true);	
			xmlhttp.send();
		}
        
	function chkFeedback(){
			var txtSubject = document.getElementById("txtSubject");
			var txtMessage = document.getElementById("txtMessage"); 
			var hdnRepchkStr = document.getElementById("hdnRepchkStr").value; 
			var hdncompnewid = document.getElementById("hdncompnewid").value; 
			var hdclient_loginid = document.getElementById("hdclient_loginid").value; 
			if(txtSubject.value == ''){
				alert("Please enter subject.");
				txtSubject.focus();
				return false;
			}
			if(txtMessage.value == ''){
				alert("Please enter message");
				txtMessage.focus();
				return false;
			}

			if (window.XMLHttpRequest) {
				xmlhttp=new XMLHttpRequest();
			} else {
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}

			show_loading();

			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {	
					remove_loading();
					if(xmlhttp.responseText != ''){
						document.getElementById("feedbackResponseText").innerHTML = "Feedback submitted successfully.";
					}
					 
				}
			}

			xmlhttp.open("GET","frmfeedback_submit.php?hdnRepchkStr=" + hdnRepchkStr + "&txtSubject=" + encodeURIComponent(txtSubject.value) + "&txtMessage=" + encodeURIComponent(txtMessage.value)+"&hdncompnewid="+hdncompnewid+"&hdclient_loginid="+hdclient_loginid, true);	
			xmlhttp.send();
		}
        
	</script>
	<!-- BOX PROFILE SCRIPT END  -->
	<link rel="stylesheet" type="text/css" href="css/style.css">	
</head>
<?
function viewSellToAdditionalDt($title, $name, $address, $address2, $city, $state, $zipcode, $main_line_ph, $main_line_ph_ext, $mainphone, $cellphone, $email, $linked_profile, $fax, $opt_out_mkt_sellto_email, $contactSrno){
	?>
	<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
		<tbody>
			<tr class="headrow">
				<td colspan="2" align="center" >Sell To Contact <?=$contactSrno?></td>
			</tr>
			<tr  class="rowalt1" >
				<td>Contact Name</td>
				<td><?=$name;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>Contact Title</td>
				<td><?=$title;?></td>
			</tr>
			<tr class="rowalt1" >
				<td>Address 1</td>
				<td><?=$address;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>Address 2</td>
				<td><?=$address2;?></td>
			</tr>
			<tr class="rowalt1" >
				<td>City</td>
				<td><?=$city;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>State/Province</td>
				<td><?=$state;?></td>
			</tr>

			<tr class="rowalt1" >
				<td>Zip</td>
				<td><?=$zipcode;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>Main line</td>
				<td> <?=$main_line_ph; ?> </td>
			</tr>
			<tr class="rowalt1" >
				<td>Main line Ext</td>
				<td><?=$main_line_ph_ext;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>Direct No</td>
				<td> <?=$mainphone;?> </td>
			</tr>

			<tr class="rowalt1" >
				<td>Mobile No</td>
				<td> <?=$cellphone; ?> </td>
			</tr>
			<tr class="rowalt2" >
				<td>Reply To E-mail	</td>
				<td>
					<?=$email?>
				</td>
			</tr>
			<tr class="rowalt1" >
				<td>Fax</td>
				<td><?=$fax;?></td>
			</tr>
			<tr  class="rowalt2" >
				<td>Opt-out Email Marketing</td>
				<td><?=($opt_out_mkt_sellto_email == 0)? "No" : "Yes";?></td>
			</tr>
		</tbody>
		</table>
	<?
}

function viewShipToAdditionalDt($title, $name, $main_line_ph, $main_line_ph_ext, $mainphone, $cellphone, $email, $linked_profile, $fax, $opt_out_mkt_shipto_email, $contactSrno){
	?>
	<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
		<tbody>
			<tr class="headrow">
				<td colspan="2" align="center" >Ship To Contact <?=$contactSrno;?></td>
			</tr>
			<tr class="rowalt1" >
				<td>Name</td>
				<td><?=$name;?></td>
			</tr>
			<tr class="rowalt2" >
				<td>Title</td>
				<td><?=$title;?></td>
			</tr>
			<tr class="rowalt1" >
				<td>Main line</td>
				<td> <?=$main_line_ph; ?> </td>
			</tr>
			<tr class="rowalt2">
				<td>Main line Ext</td>
				<td><?=$main_line_ph_ext;?></td>
			</tr>
			<tr class="rowalt1">
				<td>Direct No</td>
				<td> <?=$mainphone; ?> </td>
			</tr>

			<tr class="rowalt2">
				<td>Mobile No</td>
				<td> <?=$cellphone; ?> </td>
			</tr>
			<tr class="rowalt1">
				<td>Email	</td>
				<td><?=$email?></td>
			</tr>
			<tr class="rowalt2">
				<td>Fax</td>
				<td><?=$fax;?></td>
			</tr>
			<tr class="rowalt1">
				<td>Opt-out Email Marketing	</td>
				<td><?=($opt_out_mkt_shipto_email == 0)? "No" : "Yes";?></td>
			</tr>
		</tbody>
		</table>
	<?
}

function viewBillToAdditionalDt($title, $name, $address, $address2, $city, $state, $zipcode, $main_line_ph, $main_line_ph_ext, $mainphone, $cellphone, $email, $linked_profile, $fax, $contactSrno ){
	?>
	<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
		<tbody>
			<tr class="headrow">
				<td colspan="2" align="center" >Bill To Contact <?=$contactSrno?></td>
			</tr>
			<tr class="rowalt1">
				<td>Name</td>
				<td><?=$name;?></td>
			</tr>
			<tr class="rowalt2">
				<td>Title</td>
				<td><?=$title;?></td>
			</tr>
			<tr class="rowalt1">
				<td>Address 1</td>
				<td><?=$address;?></td>
			</tr>
			<tr class="rowalt2">
				<td>Address 2</td>
				<td><?=$address2;?></td>
			</tr>
			<tr class="rowalt1">
				<td>City</td>
				<td><?=$city;?></td>
			</tr>
			<tr class="rowalt2">
				<td>State/Province</td>
				<td><?=$state;?></td>
			</tr>

			<tr class="rowalt1">
				<td>Zip</td>
				<td><?=$zipcode;?></td>
			</tr>
			<tr class="rowalt2">
				<td>Main line</td>
				<td> <?=$main_line_ph; ?> </td>
			</tr>
			<tr class="rowalt1">
				<td>Main line Ext</td>
				<td><?=$main_line_ph_ext;?></td>
			</tr>
			<tr class="rowalt2">
				<td>Direct No</td>
				<td> <?=$mainphone; ?> </td>
			</tr>

			<tr class="rowalt1">
				<td>Mobile No</td>
				<td> <?=$cellphone; ?> </td>
			</tr>
			<tr class="rowalt2">
				<td>Email	</td>
				<td><?=$email?></td>
			</tr>

			<tr class="rowalt1">
				<td>Fax</td>
				<td><?=$fax;?></td>
			</tr>
		</tbody>
		</table>
	<?
}
function get_loop_box_id($b2b_id){
	$dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
	$dt_res_so = db_query($dt_so,db() );

	while ($so_row = array_shift($dt_res_so)) {
	if ($so_row["id"] > 0) 
	return $so_row["id"];
	}
}

function get_quote_gaylord_count($clientdash_flg, $quoteItem, $client_companyid){
	if($clientdash_flg==1){
		$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item= '".$quoteItem."' AND companyID = '". $client_companyid ."' and client_dash_flg=1 ORDER BY quote_gaylord.id ASC";
	} else{
		$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item= '".$quoteItem."' AND companyID = '". $client_companyid ."' ORDER BY quote_gaylord.id ASC";
	}
	$g_res = db_query($getrecquery, db());
	//echo "<pre>"; print_r($getrecquery); echo "</pre>";
	$gCount = tep_db_num_rows($g_res);
	return $gCount;
}

function get_quote_shipping_count($clientdash_flg, $quoteItem, $client_companyid){
	if($clientdash_flg==1){
		$getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' AND quote_request.client_dash_flg=1 ORDER BY quote_shipping_boxes.id ASC";
	} else{
		$getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' ORDER BY quote_shipping_boxes.id ASC";
	}
	$s_res = db_query($getrecquery2, db());
	$sCount = tep_db_num_rows($s_res);
	//echo "<pre>"; print_r($s_res); echo "</pre>";
	return $sCount;
}
function get_quote_supersacks_count($clientdash_flg, $quoteItem, $client_companyid){
	if($clientdash_flg==1){
		$getrecquery3 = "SELECT * FROM quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' AND client_dash_flg=1 ORDER BY quote_supersacks.id ASC";
	}else{
		$getrecquery3 = "SELECT * FROM quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' ORDER BY quote_supersacks.id ASC";
	}
	$sup_res = db_query($getrecquery3, db());
	$supCount = tep_db_num_rows($sup_res);
	//echo "<pre>"; print_r($sup_res); echo "</pre>";

	return $supCount;
}
function get_quote_pallets_count($clientdash_flg, $quoteItem, $client_companyid){
	if($clientdash_flg==1){
		$getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' AND client_dash_flg=1 ORDER BY quote_pallets.id asc";
	}else{
		$getrecquery2 = "SELECT * FROM quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id WHERE quote_item=".$quoteItem." AND companyID = '". $client_companyid ."' ORDER BY quote_pallets.id asc";
	}
	$p_res = db_query($getrecquery2, db());
	$pCount = tep_db_num_rows($p_res);

	return $pCount;
}


function getnickname($warehouse_name, $b2bid){
	$nickname = "";
	if ($b2bid > 0) {
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
		$result_comp = db_query($sql,db_b2b() );
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
	}else {
		$nickname = $warehouse_name;
	}
	
	return $nickname;
}	

?>
<? if ($_REQUEST["repchk"] == "yes") { 
	$repchk_str = "&repchk=yes";
} else { 
	$repchk_str = ""; 
}?>
<body >

<style>
.white_content_gaylord_newv3 {
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 1200px;
    height: 520px;
	padding: 16px;
	border: 1px solid gray;
	background-color: white;
	z-index:1002;
	-moz-box-shadow:    6px 6px 6px 6px #888888;
	-webkit-box-shadow: 6px 6px 6px 6px #888888;
	box-shadow:        6px 6px 6px 6px #888888;
    filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
}
</style>

	<script>
		window.addEventListener('load', (event) => {
		    console.log('All assets are loaded')
		    console.log(Date.now() - window.performance.timing.navigationStart);
		    $('#overlay').fadeOut();
		});		
	</script>
	<div id="overlay" style="border: 1px solid black; text-align: center; margin: 0 auto; width: 100%; display: block;">
		<font color="black"><div style="background-color:rgba(255, 255, 255, 1); width: 150px; display: block; padding: 10px; text-align: center; box-shadow: 0px 10px  5px rgba(0,0,0,0.6);
  -moz-box-shadow: 0px 10px  5px  rgba(0,0,0,0.6);
  -webkit-box-shadow: 0px 10px 5px  rgba(0,0,0,0.6);
  -o-box-shadow: 0px 10px 5px  rgba(0,0,0,0.6); border-radius:10px;">Loading... </font><img src="images/wait_animated.gif" alt="Loading" /></div>

	</div>
	<div id="light_gaylord_newv3" class="white_content_gaylord_newv3"></div>
	<div id="light_gaylord_new1" class="white_content_gaylord_new1"></div>
	<div id="light_new_shipping" class="white_content_gaylord_new1"></div>
	<div id="light_new_supersacks" class="white_content_gaylord_new1"></div>
	<div id="light_new_other" class="white_content_gaylord_new1"></div>
	<div id="light_new_pal" class="white_content_gaylord_new1"></div>

	<div id="light_gaylord_new" class="white_content_gaylord_new1"></div>
	<div id="map-overlay" onclick="off_overlay()">
		<div id="light" class="white_content">	</div>
	</div>	
	<nav class="nav-top-1 bg-light px-3">
    <div class="d-flex">
    <div class="d-flex align-items-center">
    <!-- Navbar toggler -->
    <button class="mr-4 navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNavTop" aria-controls="navbarNavTop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
    </button>
    
  
    <a class="" href="#"><img class="header_logo" src="images/ucb-logo-zero.png" /></a>

    </div>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse collapse-custom" id="navbarNavTop">
    <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=home<?= $repchk_str ?>" onclick="show_loading()">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=profile<?= $repchk_str ?>" onclick="show_loading()">Company profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=box_profile<?= $repchk_str ?>" onclick="show_loading()">Box profiles</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=closed_loop_inv<?= $repchk_str ?>" onclick="show_loading()">Closed Loop Inventory</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=sales_quotes<?= $repchk_str ?>" onclick="show_loading()">Sales quotes</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=favorites<?= $repchk_str ?>" onclick="show_loading()">Favorites/Re-order</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=history<?= $repchk_str ?>" onclick="show_loading()">Current orders/history</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=accounting<?= $repchk_str ?>" onclick="show_loading()">Accounting</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=reports<?= $repchk_str ?>" onclick="show_loading()">Reports</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=tutorials<?= $repchk_str ?>" onclick="show_loading()">Tutorials</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=why_boomerang<?= $repchk_str ?>" onclick="show_loading()">Why Boomerang?</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=feedback<?= $repchk_str ?>" onclick="show_loading()">Feedback</a>
        </li>
      </ul>
    </div>
    </div>

    <div class="d-flex align-items-center">
		
	<?php if ($sales_rep_login == "no") { ?>  
      <div class="d-flex align-items-center">
		 <? if ($hdmultiple_acc_flg == 1) { ?>
              <form class="px-1 mb-0" name="frmchangepwd" action="client_dashboard_new.php?compnewid=ssQ3ZaqbEIAA3emPe%2FPxcO5%2BYM1P2cfi%2F67%2BRAITKjxq5EufBol9yMCnEJvmzZIit1uiCzZVVUOl9BGOxUgy%2Bg%3D%3D&amp;show=change_password" method="Post">
                <input type="button" class="btn btn-topbar" id="btnswitchacc" name="btnswitchacc" onclick="swicthacc(201, 81689)" value="Switch Locations" style="cursor:pointer;">
              </form>
			   <? } ?>
            <? if (!isset($_REQUEST["hd_chgpwd"])) { ?>
              <form class="px-1 mb-0" name="frmchangepwd" action="client_dashboard_new.php?compnewid=urk%2F%2BS7UVfeB7Z4p5%2BImXBKvO%2Fa2h0f6RPTzUt5IX9AMci6uyhU0pw3MCDoBKYFUWEU5UvIK1IUZbTCFaQu6vQ%3D%3D&amp;show=change_password" method="Post">
                <input type="submit" class="btn btn-topbar" name="btnchgpwd" value="Change password" style="cursor:pointer;">
                <input type="hidden" name="hd_chgpwd" id="hd_chgpwd" value="yes">
              </form>
			   <? } ?>
              <form class="px-1 mb-0" name="frmsignout" action="client_dashboard_new.php" method="Post">
                <input type="submit" class="btn btn-topbar" name="btnsignout" value="Log off" style="cursor:pointer;">
                <input type="hidden" name="hd_signout" id="hd_signout" value="yes">
              </form>
        </div>
		
        <?php } ?>
        &nbsp;&nbsp;<p class="company_text_header"><i><? echo $client_name. " - ".$shipCityNm.", ".$shipStateNm; ?></i></p>
      </div>
  </nav>
  <div class="nav-top-2">
    <p class="pl-5 mb-0">
    Shop B2B:
    <?php /* href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=inventory<?= $repchk_str ?>" */ ?>
    <a class="gaylord_link"  href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=inventory<?= $repchk_str ?>&client_loginid=<?php $client_loginid;?>" onclick="show_loading()">Browse Gaylords</a>
  </p>
  </div>
	<main class="mt-3">
		<div class="sub_container">
			<div class="mb-3">
				<div class="col">
					<table width="100%">
						<tr>

							<td align="left" width="100%" valign="top">
								
<div id="div_element">							
								
<? if($_REQUEST['show'] == 'profile'){ 

	$getCompData = db_query("SELECT * FROM companyInfo WHERE ID = '" . $client_companyid . "'",db_b2b() );
	$rowCompData = array_shift($getCompData); 
	?> 
	<table width="100%">
		<tr >
			<td align="center"><h3>Company profile</h3></td>
		</tr>
		<tr >
			<td align="left"><i>Note: Email your UCB Account Rep to update any info</i></td>
		</tr>
		<tr>
		<td>
			<table width="100%">
			<tr>
			<td width="33%" valign="top">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
					<tr class="headrow">
						<td colspan="2" align="center" >Primary Sell To Contact</td>
					</tr>
					<tr class="rowalt1" >
						<td>Contact Name</td>
						<td><?=$rowCompData["contact"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Contact Title</td>
						<td><?=$rowCompData["contactTitle"];?></td>
					</tr>
					<tr class="rowalt1" >
						<td>Address 1</td>
						<td><?=$rowCompData["address"];?></td>
					</tr>
					<tr class="rowalt2" >
						<td>Address 2</td>
						<td><?=$rowCompData["address2"];?></td>
					</tr>
					<tr class="rowalt1" >
						<td>City</td>
						<td><?=$rowCompData["city"];?></td>
					</tr>
					<tr class="rowalt2" >
						<td>State/Province</td>
						<td><?=$rowCompData["state"];?></td>
					</tr>

					<tr class="rowalt1" >
						<td>Zip</td>
						<td><?=$rowCompData["zip"];?></td>
					</tr>
					<tr class="rowalt2" >
						<td>Country</td>
						<td><?=$rowCompData["country"];?></td>
					</tr>
					<tr class="rowalt1" >
						<td>Main line</td>
						<td><?=$rowCompData["sellto_main_line_ph"];?> </td>
					</tr>
					<tr class="rowalt2" >
						<td>Main line Ext</td>
						<td><?=$rowCompData["sellto_main_line_ph_ext"];?></td>
					</tr>
					<tr class="rowalt1" >
						<td>Direct No</td>
						<td> <?=$rowCompData["phone"]; ?> </td>
					</tr>

					<tr class="rowalt2" >
						<td>Mobile No</td>
						<td> <?=$rowCompData["mobileno"]; ?> </td>
					</tr>
					<tr class="rowalt1" >
						<td>Reply To E-mail	</td>
						<td>
							<?=$rowCompData["email"]?>
						</td>
					</tr>					
					<tr class="rowalt2" >
						<td>Fax</td>
						<td><?=$rowCompData["fax"];?></td>
					</tr>
					<tr class="rowalt1" >
						<td>Opt-out Email Marketing</td>
						<td><?=($rowCompData["opt_out_mkt_email"] == 0)? "No" : "Yes";?></td>
					</tr>
				</tbody>
				</table>
				<br />
				<?
				$qry = "SELECT * FROM b2bsellto WHERE companyid = " . $client_companyid . " order by selltoid";
				$dt_view= db_query($qry,db_b2b() );
				if ( tep_db_num_rows($dt_view) > 0){
					?>
					<div><a href="javascript:;" onclick=selltotoggle()><span id="ex_co_div">Show</span> secondary sell to contacts </a></div>
					<div id="expand1">
	                    <?                   
						
						while ($objdb = array_shift($dt_view)) {
							?>
							<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr bgcolor="#E4E4E4">
								<td align="left" width="60%"><?=$objdb['name'];?></td>
							</tr>

							</table>
							<?
						}					
	                    ?>
	                </div>
            		<? 
            	} ?>
                <div id="expand">
					<?
					$selAddSelltoDt = db_query("SELECT * FROM b2bsellto WHERE companyid = " . $client_companyid . " ORDER BY selltoid",db_b2b() );
					$i = 1;
					if (tep_db_num_rows($selAddSelltoDt) > 0) {
						while ($rowsAddSelltoDt = array_shift($selAddSelltoDt)) {		
							viewSellToAdditionalDt($rowsAddSelltoDt["title"], $rowsAddSelltoDt["name"], $rowsAddSelltoDt["address"], $rowsAddSelltoDt["address2"], $rowsAddSelltoDt["city"], $rowsAddSelltoDt["state"], $rowsAddSelltoDt["zipcode"], $rowsAddSelltoDt["main_line_ph"], $rowsAddSelltoDt["main_line_ph_ext"], $rowsAddSelltoDt["mainphone"], $rowsAddSelltoDt["cellphone"], $rowsAddSelltoDt["email"], $rowsAddSelltoDt["linked_profile"], $rowsAddSelltoDt["fax"], $rowsAddSelltoDt["opt_out_mkt_sellto_email"], $i);
							$i++;			
						} 
						?>
						
						<?		
					}
					?>
				</div>
			</td>

			<td width="2%">&nbsp;</td>
			<td width="33%" valign="top">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
					<tr class="headrow">
						<td colspan="2" align="center" >Primary Ship To Contact </td>
					</tr>
					<tr class="rowalt1">
						<td>Contact Name</td>
						<td><?=$rowCompData["shipContact"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Contact Title</td>
						<td><?=$rowCompData["shipTitle"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>Address 1</td>
						<td><?=$rowCompData["shipAddress"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Address 2</td>
						<td><?=$rowCompData["shipAddress2"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>City</td>
						<td><?=$rowCompData["shipCity"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>State/Province</td>
						<td><?=$rowCompData["shipState"];?></td>
					</tr>

					<tr class="rowalt1">
						<td>Zip</td>
						<td><?=$rowCompData["shipZip"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Country</td>
						<td><?=$rowCompData["shipcountry"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>Main line</td>
						<td> <?=$rowCompData["shipto_main_line_ph"]; ?> </td>
					</tr>
					<tr class="rowalt2">
						<td>Main line Ext</td>
						<td><?=$rowCompData["shipto_main_line_ph_ext"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>Direct No</td>
						<td> <?=$rowCompData["shipPhone"];?> </td>
					</tr>

					<tr class="rowalt2">
						<td>Mobile No</td>
						<td> <?=$rowCompData["shipMobileno"]; ?> </td>
					</tr>
					<tr class="rowalt1">
						<td>Email	</td>
						<td><?=$rowCompData["shipemail"]?></td>
					</tr>
					<tr class="rowalt2">
						<td>Receive Freight Updates</td>
						<td><?=($rowCompData["freightupdates"]== 0)? "No" : "Yes";?></td>
					</tr>
					<tr class="rowalt1">
						<td>Shipping/Receiving Hours</td>
						<td><?=$rowCompData["shipping_receiving_hours"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Opt-out Email Marketing	</td>
						<td><?=($rowCompData["opt_out_mkt_shipto_email"] == 0)? "No" : "Yes";?></td>
					</tr>
				</tbody>
				</table>
				<br />
				<?
				$dt_view = db_query("SELECT * FROM b2bshipto WHERE companyid = " . $client_companyid . " ORDER BY shiptoid",db_b2b() );
					if ( tep_db_num_rows($dt_view) > 0){
						?>
						<div><a href="javascript:;" onclick=shiptotoggle()><span id="ex_co_div_ship">Show</span> secondary ship to contacts</a></div>
						<div id="expand1_ship">
							<?		                    
							while ($objdb = array_shift($dt_view)) {
							?>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" class="<? echo $style_tbl; ?>">
									<tr bgcolor="#E4E4E4" >
										<td align="left" width="60%"> <?=$objdb["name"]?></td>
									</tr>
								</table>
							<?
							}
							?>
		                </div>
                		<?
					}
					?>
                <div id="expand_ship">
					<?
					$selAddShiptoDt = db_query("SELECT * FROM b2bshipto WHERE companyid = " . $client_companyid . " ORDER BY shiptoid",db_b2b() );
					$i = 1;
					if ( tep_db_num_rows($selAddShiptoDt) > 0){
						while ($rowsAddShiptoDt = array_shift($selAddShiptoDt)) {		
							viewShipToAdditionalDt($rowsAddShiptoDt["title"], $rowsAddShiptoDt["name"], $rowsAddShiptoDt["main_line_ph"], $rowsAddShiptoDt["main_line_ph_ext"], $rowsAddShiptoDt["mainphone"], $rowsAddShiptoDt["cellphone"], $rowsAddShiptoDt["email"], $rowsAddShiptoDt["linked_profile"], $rowsAddShiptoDt["fax"], $rowsAddShiptoDt["opt_out_mkt_shipto_email"], $i);
							$i++;		
						}	
					}
					?>
				</div>
			</td>
			<td width="2%">&nbsp;</td>
			<td width="33%" valign="top">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
					<tr class="headrow">
						<td colspan="2" align="center" >Primary Bill To Contact</td>
					</tr>
					<?
					$getCompBillData = db_query("SELECT * FROM b2bbillto WHERE companyid = " . $client_companyid . " ORDER BY billtoid LIMIT 1",db_b2b() );
					$rowCompBillData = array_shift($getCompBillData);
					?>
					<tr class="rowalt1">
						<td>Contact Name</td>
						<td><?=$rowCompBillData["name"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Contact Title</td>
						<td><?=$rowCompBillData["title"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>Address 1</td>
						<td><?=$rowCompBillData["address"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Address 2</td>
						<td><?=$rowCompBillData["address2"];?></td>
					</tr>
					<tr class="rowalt1">
						<td>City</td>
						<td><?=$rowCompBillData["city"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>State/Province</td>
						<td><?=$rowCompBillData["state"];?></td>
					</tr>

					<tr class="rowalt1">
						<td>Zip</td>
						<td><?=$rowCompBillData["zipcode"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Main line</td>
						<td> <?=$rowCompBillData["main_line_ph"];?>	 </td>
					</tr>
					<tr class="rowalt1">
						<td>Main line Ext</td>
						<td><?=$rowCompBillData["main_line_ph_ext"];?></td>
					</tr>
					<tr class="rowalt2">
						<td>Direct No</td>
						<td> <?=$rowCompBillData["mainphone"];?> </td>
					</tr>

					<tr class="rowalt1">
						<td>Mobile No</td>
						<td><?=$rowCompBillData["cellphone"]; ?> </td>
					</tr>
					<tr class="rowalt2">
						<td>Email	</td>
						<td><?=$rowCompBillData["email"]?></td>
					</tr>
					<tr class="rowalt1">
						<td>Fax</td>
						<td><?=$rowCompBillData["fax"];?></td>
					</tr>
				</tbody>
				</table>
				<br />
				<?

                $qry = "Select * from b2bbillto where companyid = " . $client_companyid . " order by billtoid LIMIT 18446744073709551610 OFFSET 1";
				$dt_view= db_query($qry,db_b2b() );
				if ( tep_db_num_rows($dt_view) > 0){
					?>
					<div><a href="javascript:;" onclick=billtotoggle()><span id="ex_co_div_bill">Show</span> secondary bill to contacts </a></div>
					<div id="expand1_bill">
					<?
					while ($objdb = array_shift($dt_view)) {
						?>
						<table width="100%" border="0" cellspacing="1" cellpadding="3" class="<? echo $style_tbl; ?>">
							<tr bgcolor="#E4E4E4" >
								<td align="left" width="60%"> <?=$objdb["name"]?></td>
							</tr>
						</table>
						<?
						}
					?>
                	</div>
                	<?
				}
				?>
                <div id="expand_bill">
					<?
					$selAddBilltoDt = db_query("SELECT * FROM b2bbillto WHERE companyid = " . $client_companyid . " ORDER BY billtoid LIMIT 18446744073709551610 OFFSET 1",db_b2b() );
					$i = 1;
					if (tep_db_num_rows($selAddBilltoDt) > 0) {
						while ($rowsAddBilltoDt = array_shift($selAddBilltoDt)){
							viewBillToAdditionalDt($rowsAddBilltoDt["title"], $rowsAddBilltoDt["name"], $rowsAddBilltoDt["address"], $rowsAddBilltoDt["address2"], $rowsAddBilltoDt["city"], $rowsAddBilltoDt["state"], $rowsAddBilltoDt["zipcode"], $rowsAddBilltoDt["main_line_ph"], $rowsAddBilltoDt["main_line_ph_ext"], $rowsAddBilltoDt["mainphone"], $rowsAddBilltoDt["cellphone"], 
								$rowsAddBilltoDt["email"], $rowsAddBilltoDt["linked_profile"], $rowsAddBilltoDt["fax"], $i );
							$i++;
						}	
					}
					?>
				</div>
			</td>
			</tr>
			</table>											
		</td>
		</tr>
	</table>
<?  } else if($_REQUEST['show'] == 'box_profile' && $show_boxprofile_inv == 'yes'){ ?>	
<? //} else if($_REQUEST['show'] == 'box_profile'){ ?>
	<table width="100%">
		<tr> <td align="center"><h3>Box profiles</h3></td> </tr>
		<tr> <td>Setup your profile for items you buy. This includes entering what you ideally would purchase if it was brand new, as well as how flexible you can be on sizing and specs (the more flexible you are, the greater chance to find cheaper boxes near you!).</td> </tr>
		<tr class="headrow" > 
			<td>
				<font size="1">Select Item
	                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">What commodity are you setting up?</span> </div>
	            </font><!--onchange="selSpecificFrm(this.value);"-->
	            <? 
	            $item_query = "SELECT * FROM quote_request_item WHERE status = 1";
				$item_res = db_query($item_query,db());
				unset($item_res[4]);
				//echo "<pre>"; print_r($item_res); echo "</pre>";
	            ?>
	            <select name="quote_item" id="quote_item"   >
	              	<option value="-1">Select</option>
	          		<?				
					while ($item_rows = array_shift($item_res)){
						?>
	          				<option value="<?php echo $item_rows["quote_rq_id"]; ?>"><?php echo $item_rows["item"]; ?></option>
	          			<?
					}
					?>
	            </select>
		 	</td> 
		</tr>
		<?
		$clientdash_flg = 1;			
		/*<!-- gaylord quote  -->*/
		$gCount = get_quote_gaylord_count($clientdash_flg, 1, $client_companyid);
		/*<!-- shipping quote -->*/
		$sCount = get_quote_shipping_count($clientdash_flg, 2, $client_companyid);
		/*<!-- supersack quote -->*/
		$supCount = get_quote_supersacks_count($clientdash_flg, 3, $client_companyid);	
		/*<!-- pallet quote -->*/
		$pCount = get_quote_pallets_count($clientdash_flg, 4, $client_companyid);
		//$gCount = 0;$sCount = 0;$supCount = 0;$pCount = 0;
		//echo "<br/>".$gCount." / ".$sCount." / ".$supCount." / ".$pCount;
		if ($gCount == 0 && $sCount == 0 && $supCount == 0 && $pCount == 0 && $show_boxprofile_inv == 'yes'){
			?>
			<tr class="noDemandEntries" >
			<td align="center">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
					<tbody>
					<tr>
						<td>
							<img style="vertical-align: middle;" src="images/exclamation-mark.png" /> <span style="color:red">You do not have any box profiles setup, which are required to view UCB's National, Real-Time Inventory database. Setup your box profiles above!</span> 
						</td>
					</tr>										
					</tbody>
				</table>
			</td>
			</tr>
			<?
		}
		?>
		<tr>
			<td>
			<div id="form_area">
				<?
				$rowcolor1 = "#e4e4e4";
				$rowcolor2 = "#ececec";
				$clientdash_flg = 1; // note:this value set 0 from ucbloop and 1 from cliend dashboard
				$subheading2 = "#d5d5d5";
				$subheading  = "#b6d4a4";
				?>
				<!-- Table for Gaylord Totes-->
				<form name="rptSearch">
				<table width="70%" id="table_1" cellpadding="3" cellspacing="1" class="table item tableBorder">
	          		<tr bgcolor="<? echo $rowcolor2; ?>">
	            		<td>Ideal Size (in)
	              			<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If you were to buy brand new, what would the size be?</span> </div>
	              		</td>
	            		<td width="130px" align="center">
	            			<div class="size_align" > <span class="label_txt">L</span><br>
	                  		<input type="text" name="g_item_length" id="g_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                		</div>
	                	</td>
	           			<td width="20px" align="center">x</td>
	            		<td width="130px"  align="center">
	            			<div class="size_align"> <span class="label_txt">W</span><br>
	              			<input type="text" name="g_item_width" id="g_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	            			</div>
	            		</td>
	            		<td width="20px" align="center">x</td>
	            		<td width="130px" align="center">
	            			<div class="size_align"> <span class="label_txt">H</span><br>
	              			<input type="text" name="g_item_height" id="g_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	            			</div>
	            		</td>
	          		</tr>
	              	<tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> Quantity Requested
	                      <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do you order at a time?</span> </div></td>
	                    <td colspan=5><select name="g_quantity_request" id="g_quantity_request" onChange="show_otherqty_text(this)">
	                      <option>Select One</option>
	                      <option>Full Truckload</option>
	                      <option>Half Truckload</option>
	                      <option>Quarter Truckload</option>
	                      <option>Other</option>
	                    </select>
	                      <br>
	                      <input type="text" name="g_other_quantity" id="g_other_quantity" size="10" style="display:none;" onKeyPress="return isNumberKey(event)"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor2; ?>">
	                    <td> Frequency of Order
	                      <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do you order this item?</span> </div></td>
	                    <td colspan=5><select name="g_frequency_order" id="g_frequency_order">
	                      <option>Select One</option>
						  <option>Multiple per Week</option>
	                      <option>Multiple per Month</option>
	                      <option>Once per Month</option>
	                      <option>Multiple per Year</option>
	                      <option>Once per Year</option>
	                      <option>One-Time Purchase</option>
	                    </select></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> What Used For?
	                      <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Describe what you put in this item, how much weight is going in it?</span> </div></td>
	                    <td colspan=5><input type="text" id="g_what_used_for"></td>
	                </tr>
	          		<div id="listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	          		<!--<tr bgcolor="<? echo $rowcolor1; ?>">
	                		<td> Also Need Pallets?
	                  	<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div></td>
	                	<td colspan=5><input type="checkbox" name="need_pallets" id="need_pallets" value="Yes"></td>
	              	</tr>  -->
					<tr bgcolor="<? echo $rowcolor1; ?>">
						<td>
							Desired Price
							 <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point you are trying to stay under. This value is per unit.</span> </div>
						</td>
						<td colspan=5>
							$<input type="text" name="sales_desired_price_g" id="sales_desired_price_g" size="11" onchange="setTwoNumberDecimal(this)">
						</td>
					</tr>
			  
	                <tr bgcolor="<? echo $rowcolor2; ?>">
	                    <td> Notes
	                      <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist understanding exactly what you need. More info is better.</span> </div></td>
	                    <td colspan=5><textarea name="g_item_note" id="g_item_note"></textarea></td>
	                </tr>
	          		<tr bgcolor="<? echo $subheading2; ?>">
	            		<td colspan="6"><strong>Criteria of what you SHOULD be able to use:</strong>
	              		<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It may be difficult to find the exact size you are looking for, exactly where you need it, at the price you need it at, and available exactly when you need it. Thus, fill out the criteria and ranges of what you SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to you (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to you (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div>
	              		</td>
	          		</tr>
	              	<tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td><!-- align="right"-->
	                      Height Flexibility </td>
	                    <td align="center"><span class="label_txt">Min</span> <br>
	                      <input type="text" class="size_txt_center" name="g_item_min_height" id="g_item_min_height" value="0" size="5" onKeyPress="return isNumberKey(event)"></td>
	                    <td align="center">-</td>
	                    <td align="center" ><span class="label_txt">Max</span> <br>
	                      <input type="text" class="size_txt_center" name="g_item_max_height" id="g_item_max_height" value="99" size="5" onKeyPress="return isNumberKey(event)"></td>
	                    <td align="center" colspan="2">&nbsp;</td>
	                </tr>
	              	<tr bgcolor="<? echo $rowcolor2; ?>">
	                	<td> Shape </td>
	                	<td> Rectangular </td>
	                	<td><input type="checkbox" id="g_shape_rectangular" value="Yes" checked="checked"></td>
	                	<td> Octagonal </td>
	                	<td colspan="2"><input type="checkbox" id="g_shape_octagonal" value="Yes" checked="checked"></td>
	              	</tr>
	          		<tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td rowspan="5"> # of Walls </td>
	                    <td> 1ply </td>
	                    <td><input type="checkbox" id="g_wall_1" value="Yes" checked="checked"></td>
	                    <td> 6ply </td>
	                    <td colspan="2"><input type="checkbox" id="g_wall_6" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> 2ply </td>
	                    <td><input type="checkbox" id="g_wall_2" value="Yes" checked="checked"></td>
	                    <td> 7ply </td>
	                    <td colspan="2"><input type="checkbox" id="g_wall_7" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> 3ply </td>
	                    <td><input type="checkbox" id="g_wall_3" value="Yes" checked="checked"></td>
	                    <td> 8ply </td>
	                    <td colspan="2"><input type="checkbox" id="g_wall_8" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> 4ply </td>
	                    <td><input type="checkbox" id="g_wall_4" value="Yes" checked="checked"></td>
	                    <td> 9ply </td>
	                    <td colspan="2"><input type="checkbox" id="g_wall_9" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> 5ply </td>
	                    <td><input type="checkbox" id="g_wall_5" value="Yes" checked="checked"></td>
	                    <td> 10ply </td>
	                    <td colspan="2"><input type="checkbox" id="g_wall_10" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor2; ?>">
	                    <td rowspan="2"> Top Config </td>
	                    <td> No Top </td>
	                    <td><input name="g_no_top" type="checkbox" id="g_no_top" value="Yes" checked="checked"></td>
	                    <td> Lid Top </td>
	                    <td colspan="2"><input name="g_lid_top" type="checkbox" id="g_lid_top" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor2; ?>">
	                    <td> Partial Flap Top </td>
	                    <td><input name="g_partial_flap_top" type="checkbox" id="g_partial_flap_top" value="Yes" checked="checked"></td>
	                    <td> Full Flap Top </td>
	                    <td colspan="2"><input name="g_full_flap_top" type="checkbox" id="g_full_flap_top" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td rowspan="3"> Bottom Config </td>
	                    <td> No Bottom </td>
	                    <td><input name="g_no_bottom_config" type="checkbox" id="g_no_bottom_config" value="Yes" checked="checked"></td>
	                    <td> Partial Flap w/ Slipsheet </td>
	                    <td colspan="2"><input name="g_partial_flap_w" type="checkbox" id="g_partial_flap_w" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> Tray Bottom </td>
	                    <td><input name="g_tray_bottom" type="checkbox" id="g_tray_bottom" value="Yes" checked="checked"></td>
	                    <td> Full Flap Bottom </td>
	                    <td colspan="2"><input name="g_full_flap_bottom" type="checkbox" id="g_full_flap_bottom" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor1; ?>">
	                    <td> Partial Flap w/o SlipSheet </td>
	                    <td colspan="4"><input name="g_partial_flap_wo" type="checkbox" id="g_partial_flap_wo" value="Yes" checked="checked"></td>
	                </tr>
	                <tr bgcolor="<? echo $rowcolor2; ?>">
	                    <td> Vents Okay? </td>
	                    <td colspan=5><input name="g_vents_okay" type="checkbox" id="g_vents_okay" value="Yes" checked="checked"></td>
	                </tr>
	                <tr align="center" bgcolor="<? echo $buttonrow; ?>">
	                    <td colspan="6" align="center">
							<input type="hidden" name="client_dash_flg" id="client_dash_flg" value="<? echo $clientdash_flg; ?>">
							<input type="button" name="g_item_submit" value="Add New Profile" onClick="quote_save(<?=$client_companyid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	                </tr>
	        	</table>
	        	</form>
	        	<!-- Table for Shipping Boxes-->
	        	<form name="sb">
	            <table width="70%" id="table_2" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td>Ideal Size (in)
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If you were to buy brand new, what would the size be?</span> </div></td>
	                <td width="130px"  align="center"><div class="size_align"> <span class="label_txt">L</span><br>
	                  <input type="text" name="sb_item_length" id="sb_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	                <td width="20px" align="center">x</td>
	                <td width="130px"  align="center"><div class="size_align"> <span class="label_txt">W</span><br>
	                  <input type="text" name="sb_item_width" id="sb_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	                <td width="20px" align="center">x</td>
	                <td width="130px"  align="center"><div class="size_align"> <span class="label_txt">H</span><br>
	                  <input type="text" name="sb_item_height" id="sb_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Quantity Requested
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do you order at a time?</span> </div></td>
	                <td colspan=5><select name="sb_quantity_requested" id="sb_quantity_requested" onChange="show_sb_otherqty_text(this)">
	                  <option>Select One</option>
	                  <option>Full Truckload</option>
	                  <option>Half Truckload</option>
	                  <option>Quarter Truckload</option>
	                  <option>Other</option>
	                </select>
	                  <br>
	                  <input type="text" name="sb_other_quantity" id="sb_other_quantity" size="10" style="display:none;" onKeyPress="return isNumberKey(event)"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Frequency of Order
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do you order this item?</span> </div></td>
	                <td colspan=5><select name="sb_frequency_order" id="sb_frequency_order">
	                  <option>Select One</option>
					  <option>Multiple per Week</option>
	                  <option>Multiple per Month</option>
	                  <option>Once per Month</option>
	                  <option>Multiple per Year</option>
	                  <option>Once per Year</option>
	                  <option>One-Time Purchase</option>
	                </select></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> What Used For?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Describe what you put in this item, how much weight is going in it?</span> </div></td>
	                <td colspan=5><input type="text" id="sb_what_used_for"></td>
	              </tr>
	              <div id="sb_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	              <!--<tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Also Need Pallets?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div></td>
	                <td colspan=5><input name="sb_need_pallets" type="checkbox" id="sb_need_pallets" value="Yes"></td>
	              </tr>  -->
					<tr bgcolor="<? echo $rowcolor1; ?>">
						<td>
							Desired Price
							 <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point you are trying to stay under. This value is per unit.</span> </div>
						</td>
						<td colspan=5>
							$ <input type="text" name="sb_sales_desired_price" id="sb_sales_desired_price" size="11" onchange="setTwoNumberDecimal(this)">
						</td>
					</tr>
					
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Notes
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist understanding exactly what you need. More info is better.</span> </div></td>
	                <td colspan=5><textarea name="sb_notes" id="sb_notes"></textarea></td>
	              </tr>
	              <tr bgcolor="<? echo $subheading2; ?>">
	                <td colspan="6"><strong>Criteria of what you SHOULD be able to use:</strong>
	                  <div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It may be difficult to find the exact size you are looking for, exactly where you need it, at the price you need it at, and available exactly when you need it. Thus, fill out the criteria and ranges of what you SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to you (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to you (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td colspan="6"><strong>Size Flexibility</strong></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td>
	                  Length </td>
	                <td align="center"><span class="label_txt">Min</span> <br>
	                  <input type="text" class="size_txt_center" name="sb_item_min_length" id="sb_item_min_length" value="0" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td align="center">-</td>
	                <td align="center"><span class="label_txt">Max</span> <br>
	                  <input type="text"  class="size_txt_center" name="sb_item_max_length" id="sb_item_max_length" value="99" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td colspan="2" align="center">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td>
	                  Width </td>
	                <td align="center"><span class="label_txt">Min</span> <br>
	                  <input type="text"  class="size_txt_center" name="sb_item_min_width" id="sb_item_min_width" value="0" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td align="center">-</td>
	                <td align="center"><span class="label_txt">Max</span> <br>
	                  <input type="text"  class="size_txt_center" name="sb_item_max_width" id="sb_item_max_width" value="99" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td colspan="2" align="center">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td>
	                  Height </td>
	                <td align="center"><span class="label_txt">Min</span> <br>
	                  <input type="text" class="size_txt_center"  name="sb_item_min_height" id="sb_item_min_height" value="0" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td align="center">-</td>
	                <td align="center"><span class="label_txt">Max</span> <br>
	                  <input type="text" class="size_txt_center"  name="sb_item_max_height" id="sb_item_max_height" value="99" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td colspan="2" align="center">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td>
	                  Cubic Footage </td>
	                <td align="center"><span class="label_txt">Min</span> <br>
	                  <input type="text" class="size_txt_center"  name="sb_cubic_footage_min" id="sb_cubic_footage_min" value="0" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td align="center">-</td>
	                <td align="center"><span class="label_txt">Max</span> <br>
	                  <input type="text" class="size_txt_center"  name="sb_cubic_footage_max" id="sb_cubic_footage_max" value="99" size="5" onKeyPress="return isNumberKey(event)"></td>
	                <td colspan="2" align="center">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> # of Walls </td>
	                <td> 1ply </td>
	                <td><input type="checkbox" id="sb_wall_1" value="Yes" checked="checked"></td>
	                <td> 2ply </td>
	                <td colspan="2"><input type="checkbox" id="sb_wall_2" value="Yes" checked="checked"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Top Config </td>
	                <td> No Top </td>
	                <td><input name="sb_no_top" type="checkbox" id="sb_no_top" value="Yes"></td>
	                <td> Full Flap Top </td>
	                <td colspan="2"><input name="sb_full_flap_top" type="checkbox" id="sb_full_flap_top" value="Yes" checked="checked"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td>&nbsp;</td>
	                <td> Partial Flap Top </td>
	                <td><input name="sb_partial_flap_top" type="checkbox" id="sb_partial_flap_top" value="Yes"></td>
	                <td>&nbsp;</td>
	                <td colspan="2">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Bottom Config </td>
	                <td> No Bottom </td>
	                <td><input name="sb_no_bottom" type="checkbox" id="sb_no_bottom" value="Yes"></td>
	                <td> Full Flap Bottom </td>
	                <td colspan="2"><input name="sb_full_flap_bottom" type="checkbox" id="sb_full_flap_bottom" value="Yes" checked="checked"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td>&nbsp;</td>
	                <td> Partial Flap Bottom </td>
	                <td><input name="sb_partial_flap_bottom" type="checkbox" id="sb_partial_flap_bottom" value="Yes"></td>
	                <td>&nbsp;</td>
	                <td colspan="2">&nbsp;</td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Vents Okay? </td>
	                <td colspan=5><input name="sb_vents_okay" type="checkbox" id="sb_vents_okay" value="Yes" ></td>
	              </tr>           
	              <tr align="center" bgcolor="<? echo $buttonrow; ?>">
					  <input type="hidden" name="sb_client_dash_flg" id="sb_client_dash_flg" value="<? echo $clientdash_flg; ?>">
	                <td colspan="6" align="center"><input type="button" name="sb_item_submit" value="Add New Profile" onClick="sb_quote_save(<?=$client_companyid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
	          	<!-- Table for Supersacks-->
	          	<form name="sup">
	            <table width="70%" id="table_3" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td>Ideal Size (in)
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If you were to buy brand new, what would the size be?</span> </div></td>
	                <td width="130px" align="center"><div class="size_align" > <span class="label_txt">L</span><br>
	                  <input type="text" name="sup_item_length" id="sup_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	                <td width="20px" align="center">x</td>
	                <td width="130px"  align="center"><div class="size_align"> <span class="label_txt">W</span><br>
	                  <input type="text" name="sup_item_width" id="sup_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	                <td width="20px" align="center">x</td>
	                <td width="130px"  align="center"><div class="size_align"> <span class="label_txt">H</span><br>
	                  <input type="text" name="sup_item_height" id="sup_item_height" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Quantity Requested
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do you order at a time?</span> </div></td>
	                <td colspan=5><select name="sup_quantity_requested" id="sup_quantity_requested" onChange="show_sup_otherqty_text(this)">
	                  <option>Select One</option>
	                  <option>Full Truckload</option>
	                  <option>Half Truckload</option>
	                  <option>Quarter Truckload</option>
	                  <option>Other</option>
	                </select>
	                  <br>
	                  <input type="text" name="sup_other_quantity" id="sup_other_quantity" size="10" style="display:none;"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Frequency of Order
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do you order this item?</span> </div></td>
	                <td colspan=5><select name="sup_frequency_order" id="sup_frequency_order">
	                  <option>Select One</option>
					  <option>Multiple per Week</option>
	                  <option>Multiple per Month</option>
	                  <option>Once per Month</option>
	                  <option>Multiple per Year</option>
	                  <option>Once per Year</option>
	                  <option>One-Time Purchase</option>
	                </select></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> What Used For?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Describe what you put in this item, how much weight is going in it?</span> </div></td>
	                <td colspan=5><input type="text" id="sup_what_used_for"></td>
	              </tr>
	              <div id="sup_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	              <!--<tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Also Need Pallets?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div></td>
	                <td colspan=5><input type="checkbox" name="sup_need_pallets" id="sup_need_pallets" value="Yes"></td>
	              </tr>  -->
					<tr bgcolor="<? echo $rowcolor1; ?>">
						<td>
							Desired Price
							 <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point you are trying to stay under. This value is per unit.</span> </div>
						</td>
						<td colspan=5>
							$ <input type="text" name="sup_sales_desired_price" id="sup_sales_desired_price" size="11" onchange="setTwoNumberDecimal(this)">
						</td>
					</tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Notes
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist understanding exactly what you need. More info is better.</span> </div></td>
	                <td colspan=5><textarea name="sup_notes" id="sup_notes"></textarea></td>
	              </tr>
	              <tr align="center" bgcolor="<? echo $buttonrow; ?>">
	                <td colspan="6" align="center">
						<input type="hidden" name="sup_client_dash_flg" id="sup_client_dash_flg" value="<? echo $clientdash_flg; ?>">
						<input type="button" name="sup_item_submit" value="Add New Profile" onClick="sup_quote_save(<?=$client_companyid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
	          	<!-- Table for Pallets-->
	          	<form name="pallets">
	            <table width="70%" id="table_4" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td width="250px">Ideal Size (in)
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">If you were to buy brand new, what would the size be?</span> </div></td>
	                <td width="120px" align="center"><div class="size_align" > <span class="label_txt">L</span><br>
	                  <input type="text" name="pal_item_length" id="pal_item_length" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	                <td width="30px" align="center">x</td>
	                <td colspan="4"><div class="size_align"> <span class="label_txt">W</span><br>
	                  <input type="text" name="pal_item_width" id="pal_item_width" size="5" onKeyPress="return isNumberKey(event)" class="size_txt_center">
	                </div></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Quantity Requested
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do you order at a time?</span> </div></td>
	                <td colspan=6><select name="pal_quantity_requested" id="pal_quantity_requested" onChange="show_pal_otherqty_text(this)">
	                  <option>Select One</option>
	                  <option>Full Truckload</option>
	                  <option>Half Truckload</option>
	                  <option>Quarter Truckload</option>
	                  <option>Other</option>
	                </select>
	                  <br>
	                  <input type="text" name="pal_other_quantity" id="pal_other_quantity" size="10" style="display:none;"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Frequency of Order
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do you order this item?</span> </div></td>
	                <td colspan=6><select name="pal_frequency_order" id="pal_frequency_order">
	                  <option>Select One</option>
					  <option>Multiple per Week</option>
	                  <option>Multiple per Month</option>
	                  <option>Once per Month</option>
	                  <option>Multiple per Year</option>
	                  <option>Once per Year</option>
	                  <option>One-Time Purchase</option>
	                </select></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> What Used For?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Describe what you put in this item, how much weight is going in it?</span> </div></td>
	                <td colspan=6><input type="text" id="pal_what_used_for"></td>
	              </tr>
					<tr bgcolor="<? echo $rowcolor1; ?>">
						<td>
							Desired Price
							 <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point you are trying to stay under. This value is per unit.</span> </div>
						</td>
						<td colspan=6>
							$ <input type="text" name="pal_sales_desired_price" id="pal_sales_desired_price" size="11" onchange="setTwoNumberDecimal(this)">
						</td>
					</tr>
	              <div id="pal_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Notes
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist understanding exactly what you need. More info is better.</span> </div></td>
	                <td colspan=6><textarea name="pal_note" id="pal_note"></textarea></td>
	              </tr>
	              <tr bgcolor="<? echo $subheading2; ?>">
                    <td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
                      <div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div></td>
                  </tr>
                  <tr bgcolor="<? echo $rowcolor1; ?>">
                    <td>Grade </td>
                    <td>A</td>
                    <td align="center"><input type="checkbox" id="pal_grade_a" value="Yes" checked="checked"></td>
                    <td width="120px">B</td>
                    <td width="30px" align="center"><input type="checkbox" id="pal_grade_b" value="Yes" checked="checked"></td>
                  	<td width="120px">C</td>
                    <td align="center"><input type="checkbox" id="pal_grade_c" value="Yes" checked="checked"></td>                   
                  </tr>
                  <tr bgcolor="<? echo $rowcolor2; ?>">
                    <td>Material </td>
                    <td>Wooden</td>
                    <td align="center"><input type="checkbox" id="pal_material_wooden" value="Yes" checked="checked"></td>
                    <td>Plastic</td>
                    <td align="center"><input type="checkbox" id="pal_material_plastic" value="Yes"></td>
                  	<td>Corrugate</td>
                    <td align="center"><input type="checkbox" id="pal_material_corrugate" value="Yes"></td>
                  </tr>
                  <tr bgcolor="<? echo $rowcolor1; ?>">
                    <td>Entry</td>
                    <td>2-way</td>
                    <td align="center"><input type="checkbox" id="pal_entry_2way" value="Yes"></td>
                    <td>4-way</td>
                    <td colspan="3">&ensp;<input type="checkbox" id="pal_entry_4way" value="Yes" checked="checked"></td>
                  </tr>
                  <tr bgcolor="<? echo $rowcolor2; ?>">
                    <td>Structure</td>
                    <td>Stringer</td>
                    <td align="center"><input type="checkbox" id="pal_structure_stringer" value="Yes"></td>
                    <td>Block</td>
                    <td colspan="3">&ensp;<input type="checkbox" id="pal_structure_block" value="Yes"></td>
                  </tr>
                  <tr bgcolor="<? echo $rowcolor1; ?>">
                  	<td>Heat Treated</td>
                  	<td colspan=6>
                  		<select name="pal_heat_treated" id="pal_heat_treated">
                      		<option>Select One</option>
							<option>Required</option>
							<option>Not Required</option>
                    	</select>
                	</td>
                  </tr>
                  
	              <tr align="center" bgcolor="<? echo $buttonrow; ?>">
	                <td colspan="7" align="center">
						<input type="hidden" name="pal_client_dash_flg" id="pal_client_dash_flg" value="<? echo $clientdash_flg; ?>">
						<input type="button" name="pallets_item_submit" value="Add New Profile" onClick="pallets_quote_save(<?=$client_companyid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
	          	<!-- Table for Drums/Barrels/IBC--
	         	<form name="dbi">
	            <table width="70%" id="table_5" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr>
	                <td> Notes </td>
	                <td><textarea name="dbi_notes" id="dbi_notes"></textarea></td>
	              </tr>
	              <tr align="center">
	                <td colspan="4" align="center"><input type="button" name="dbi_item_submit" value="Add New Profile" onClick="dbi_quote_save(<?=$b2bid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
	          	<!-- Recycling --
	          	<form name="recycling">
	            <table width="70%" id="table_6" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr>
	                <td> Notes </td>
	                <td><textarea name="recycling_notes" id="recycling_notes"></textarea></td>
	              </tr>
	              <tr align="center">
	                <td colspan="4" align="center"><input type="button" name="recycling_item_submit" value="Add New Profile" onClick="recycling_quote_save(<?=$b2bid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
	          	<!-- Other -->
	          	<form name="other">
	            <table width="70%" id="table_7" class="table item tableBorder" cellpadding="3" cellspacing="1">
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Quantity Requested
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How much of this item do you order at a time?</span> </div></td>
	                <td><select name="other_quantity_requested" id="other_quantity_requested" onChange="show_other_otherqty_text(this)">
	                  <option>Select One</option>
	                  <option>Full Truckload</option>
	                  <option>Half Truckload</option>
	                  <option>Quarter Truckload</option>
	                  <option>Other</option>
	                </select>
	                  <br>
	                  <input type="text" name="other_other_quantity" id="other_other_quantity" size="10" style="display:none;"></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Frequency of Order
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">How often do you order this item?</span> </div></td>
	                <td><select name="other_frequency_order" id="other_frequency_order">
	                  <option>Select One</option>
					  <option>Multiple per Week</option>
	                  <option>Multiple per Month</option>
	                  <option>Once per Month</option>
	                  <option>Multiple per Year</option>
	                  <option>Once per Year</option>
	                  <option>One-Time Purchase</option>
	                </select></td>
	              </tr>
	              <tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> What Used For?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Describe what you put in this item, how much weight is going in it?</span> </div></td>
	                <td><input type="text" id="other_what_used_for"></td>
	              </tr>
	              <div id="other_listdiv" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
	              <!--<tr bgcolor="<? echo $rowcolor2; ?>">
	                <td> Also Need Pallets?
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Do they also order pallets</span> </div></td>
	                <td><input type="checkbox" name="other_need_pallets" id="other_need_pallets" value="Yes"></td>
	              </tr>  -->
				
	              <tr bgcolor="<? echo $rowcolor1; ?>">
	                <td> Notes
	                  <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">Add any additional notes that will assist understanding exactly what you need. More info is better.</span> </div></td>
	                <td><textarea name="other_note" id="other_note"></textarea></td>
	              </tr>
	              <tr align="center" bgcolor="<? echo $buttonrow; ?>">
	                <td colspan="4" align="center"><input type="button" name="other_item_submit" value="Add New Profile" onClick="other_quote_save(<?=$client_companyid?>, <?=$repchk?>)" style="cursor: pointer;"></td>
	              </tr>
	            </table>
	          	</form>
			</div>
			</td>
		</tr>
		<!-- FOR RESPONCE DISPLAY START -->
		<tr>
			<td>
				<!-- Display gaylord quote start -->
				<div id="display_quote_request">
					<?
					if($clientdash_flg==1){
						$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item=1 AND  client_dash_flg=1 and companyID = '" . $client_companyid . "' ORDER BY quote_gaylord.id ASC";
					}
					else{
						$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_item=1 and companyID = '" . $client_companyid . "' ORDER BY quote_gaylord.id ASC";
					}
			
					//
					$g_res = db_query($getrecquery);
					//echo "<pre>"; print_r($g_res); echo "</pre>";
					$chkinitials =  $_COOKIE['userinitials'];
					//
					while($g_data = array_shift($g_res)) {
						?>
						<div id="g<?=$g_data["id"]?>" >
							<table width="100%" class="table1" cellpadding="3" cellspacing="1">
							<?
							$quote_item=$g_data["quote_item"];
							//Get Item Name
							$getquotequery = db_query("SELECT * FROM quote_request_item WHERE quote_rq_id=".$quote_item);
							$quote_item_rs = array_shift($getquotequery);
							$quote_item_name=$quote_item_rs['item'];
							//
							$quote_date = $g_data["quote_date"];
							//
							$g_id=$g_data["id"];
							//
							 //---------------check quote send or not-------------------------------
							$g_qut_id=$g_data['quote_id'];
							//
							$chk_quote_query1 = "SELECT * FROM quote WHERE companyID=".$g_data["companyID"];
							$chk_quote_res1 = db_query($chk_quote_query1,db_b2b());
							$g_no_of_quote_sent1="";
							$qtr=0;
							while($quote_rows1=array_shift($chk_quote_res1)){
								$quote_req=$quote_rows1["quoteRequest"];
								//if (strpos($quote_req, ',') !== false) {
								$quote_req_id = explode(",", $quote_req);
								$total_id=count($quote_req_id);
								// echo $total_id;

								for($req = 0; $req < $total_id; $req++) {
									//echo $quote_req_id[$req]."---".$g_qut_id;
									if($quote_req_id[$req]==$g_qut_id){
										
										if ($quote_rows1["filename"] != "") {
												  
											$qtid=$quote_rows1["ID"];
											$qtf=$quote_rows1["filename"];
											//
											$link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";

										} else {
											if ($quote_rows["quoteType"]=="Quote"){
												$link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"] . $repchk_str . "'>";
											} elseif ($quote_rows1["quoteType"]=="Quote Select") {
												$link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
											}else  {
												$link="<a href='#'>";
											}
										}
										$new_quote_id=($quote_rows1["ID"] + 3770);
										 //echo $quote_req_id[$req];
										  $g_no_of_quote_sent1.=$link.$new_quote_id."</a>, ";
										 $qtr++;
										$g_no_of_quote_sent=rtrim($g_no_of_quote_sent1,", ");
									}
										  
									if($qtr!=0){
										  $g_quote_sent_status="<span style='color:#004B03;'>QUOTE SENT</span> - ".$g_no_of_quote_sent;
									}else{
										$g_quote_sent_status="<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
									}
										 
								}
								
								//}//End str pos
							}
							//
							db();
							
								$g_quotereq_sales_flag = "";
								$chk_deny_query = "SELECT * FROM quote_gaylord WHERE quote_id=".$g_data["quote_id"];
								$chk_deny_res = db_query($chk_deny_query);
				   			    while($deny_row=array_shift($chk_deny_res)){
								  $g_quotereq_sales_flag = $deny_row["g_quotereq_sales_flag"];
								}
								
								if ($g_quotereq_sales_flag == "Yes") {
									$quotereq_sales_flag_color ="#D3FFB9";
								}else {
									$quotereq_sales_flag_color ="#91bb78";
								}
								?>
								<tr bgcolor="#e4e4e4" class="rowdata">
			                    <td style="background:<? echo $quotereq_sales_flag_color;?>; padding:5px;">
			                      	<table cellpadding="3" >
			                        <tr>
			                          <td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">Box Profile ID: <? echo $g_data["quote_id"]; ?></td>
			                          <td class="boxProSubHeading" width="200px" style="background:<? echo $quotereq_sales_flag_color;?>;">Item Type: <? echo $quote_item_name; ?></td>
			                          <td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">
										<font face="Roboto" size="1" color="<? //echo $quotereq_sales_flag_color;?>"> 
										<img id="g_btn_img<?=$g_id?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="g_btn<?=$g_id?>" onClick="show_g_details(<?=$g_id?>)" class="ex_col_btn boxProSubHeading"><u>Expand Details</u></a>
			                          </font></td>
			                          <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>"  src="images/edit.jpg" onClick="g_quote_edit(<?=$client_companyid?>, <?=$g_id?>, <?=$quote_item?>, <?=$clientdash_flg?>, <?=$repchk?>)" style="cursor: pointer;"> </font></td>
									  <!-- <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>">
										<a id="lightbox_g<?=$g_id?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?=$client_companyid;?>,<?=$g_id?>, 1, 2, 1, 0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
									  </td> -->
									  <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>">
										<a id="lightbox_gv3<?=$g_id?>" href="javascript:void(0);" onClick="display_matching_tool_gaylords_v3(<?=$client_companyid;?>,<?=$g_id?>, 1, 2, 1, 0,0)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
									  </td>
									  
			                          <!-- <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>" src="images/del_img.png" onClick="g_quote_delete(<?=$g_id?>, <?=$quote_item?>,<?=$client_companyid?>)" style="cursor: pointer;"> </font></td> -->
										<td style="background:<? echo $quotereq_sales_flag_color;?>;"> 
											<font face="Roboto" size="1">
											<? 
											if($clientdash_flg==0 && $g_data["client_dash_flg"]==1){ 
												echo "Client Dash entry";
											}
											?>
											</font>
										</td>
			                        </tr>
			                      </table></td>
			                    </tr>
			                    <tr >
			                    <td><div id="g_sub_table<?=$g_id?>" style="display: none;">
			                        <table width="80%" class="in_table_style tableBorder">
			                          <tr bgcolor="<? echo $subheading; ?>">
			                            <td colspan="6"><strong>What Do They Buy?</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Item </td>
			                            <td colspan="5"> Gaylord Totes </td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td >Ideal Size (in)</td>
			                            <td align="center" width="130px"><div class="size_align"> <span class="label_txt">L</span><br>
			                              <? echo $g_data["g_item_length"]; ?> </div></td>
			                            <td width="20px" align="center">x</td>
			                            <td  align="center" width="130px"><div class="size_align"> <span class="label_txt">W</span><br>
			                              <? echo $g_data["g_item_width"]; ?> </div></td>
			                            <td width="20px" align="center">x</td>
			                            <td  align="center"  width="130px"><div class="size_align"> <span class="label_txt">H</span><br>
			                              <? echo $g_data["g_item_height"]; ?> </div></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Quantity Requested </td>
			                            <td colspan=5><? echo $g_data["g_quantity_request"]; ?>
			                              <?
												if($g_data["g_quantity_request"]=="Other")
												{
													echo "<br>".$g_data["g_other_quantity"];
												}
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Frequency of Order </td>
			                            <td colspan=5><? echo $g_data["g_frequency_order"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> What Used For? </td>
			                            <td colspan=5><? echo $g_data["g_what_used_for"]; ?></td>
			                          </tr>
			                         <!--  <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Also Need Pallets? </td>
			                            <td colspan=5><? echo $g_data["need_pallets"]; ?></td>
			                          </tr> -->
										<tr bgcolor="<? echo $rowcolor1; ?>">
											<td>
												Desired Price
											</td>
											<td colspan=5>
												<?if ($g_data["sales_desired_price_g"] > 0) {
													echo "$".$g_data["sales_desired_price_g"]; 
												}else{
													echo "$0";
												} ?>
											</td>
										</tr>
										
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Notes </td>
			                            <td colspan=5><? echo $g_data["g_item_note"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $subheading2; ?>">
			                            <td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td><!-- align="right"-->
			                              Height Flexibility </td>
			                            <td align="center"><span class="label_txt">Min</span> <br>
			                              <? echo $g_data["g_item_min_height"]; ?></td>
			                            <td align="center">-</td>
			                            <td align="center" ><span class="label_txt">Max</span> <br><? echo $g_data["g_item_max_height"]; ?></td>
			                            <td align="center" colspan="2">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Shape </td>
			                            <td> Rectangular </td>
			                            <td><? 
													echo $g_data["g_shape_rectangular"];
												
												?></td>
			                            <td> Octagonal </td>
			                            <td colspan="2"><?
													echo $g_data["g_shape_octagonal"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td rowspan="5"> # of Walls </td>
			                            <td> 1ply </td>
			                            <td><? 
												echo $g_data["g_wall_1"];
												?></td>
			                            <td> 6ply </td>
			                            <td colspan="2"><? 
												echo $g_data["g_wall_6"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> 2ply </td>
			                            <td><? 
												echo $g_data["g_wall_2"];
												?></td>
			                            <td> 7ply </td>
			                            <td colspan="2"><? 
												echo $g_data["g_wall_7"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> 3ply </td>
			                            <td><? 
												echo $g_data["g_wall_3"];
												?></td>
			                            <td> 8ply </td>
			                            <td colspan="2"><? 
												echo $g_data["g_wall_8"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> 4ply </td>
			                            <td><? 
												echo $g_data["g_wall_4"];
												?></td>
			                            <td> 9ply </td>
			                            <td colspan="2"><? 
												echo $g_data["g_wall_9"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> 5ply </td>
			                            <td><? 
												echo $g_data["g_wall_5"];
												?></td>
			                            <td> 10ply </td>
			                            <td colspan="2"><? 
												echo $g_data["g_wall_10"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td rowspan="2"> Top Config </td>
			                            <td> No Top </td>
			                            <td><? 
												echo $g_data["g_no_top"];
												?></td>
			                            <td> Lid Top </td>
			                            <td colspan="2"><? echo $g_data["g_lid_top"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Partial Flap Top </td>
			                            <td><? echo $g_data["g_partial_flap_top"];
												?></td>
			                            <td> Full Flap Top </td>
			                            <td colspan="2"><? echo $g_data["g_full_flap_top"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td rowspan="3"> Bottom Config </td>
			                            <td> No Bottom </td>
			                            <td><? echo $g_data["g_no_bottom_config"];
												?></td>
			                            <td> Partial Flap w/ Slipsheet </td>
			                            <td colspan="2"><? echo $g_data["g_partial_flap_w"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Tray Bottom </td>
			                            <td><? echo $g_data["g_tray_bottom"];
												?></td>
			                            <td> Full Flap Bottom </td>
			                            <td colspan="2"><? echo $g_data["g_full_flap_bottom"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Partial Flap w/o SlipSheet </td>
			                            <td colspan="4"><? echo $g_data["g_partial_flap_wo"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Vents Okay? </td>
			                            <td colspan=5><? echo $g_data["g_vents_okay"];
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $g_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                              Date: <?php echo date("m/d/Y H:i:s" , strtotime($g_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
			                          </tr>
			                        </table>
			                      </div></td>
			                    </tr>
			                    <!-- <tr class="rowdata" >
			                    <td style="background:#FFFFFF;">
									<!-- TEST GAYLORD SECTION START --
									<?
										if($clientdash_flg==1)
										{
									?>
									    <a style='color:#6b9624;' id="lightbox_g<?=$g_id?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?=$client_companyid;?>,<?=$g_id?>, 1, 2, 1)"> 
									<?
										}
										else{
									?>
											<a style='color:#6b9624;' id="lightbox_g<?=$g_id?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?=$client_companyid;?>,<?=$g_id?>, 1, 1, 0)">
									<?
										}
									?>
									<font face="Roboto" size="1" color="#6b9624">GAYLORD MATCHING TOOL</font></a> 
									<span id="req_gayloardtoolautoload1" name="req_gayloardtoolautoload1" style='color:red;'></span>
									<!-- TEST GAYLORD SECTION ENDS --
									<br>
			                        <br>
			                        <? if($g_data["need_pallets"]=="Yes"){ ?>
										TEST PALLET SECTION START
										<? if($clientdash_flg==1) { ?>
				                        	<a style='color:#0000FF;' id="lightbox_req_pal<? echo $g_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,0<?=$g_data["g_item_length"];?>, <?=$g_data["g_item_width"];?>,<? echo $g_id;?>)">
										<? } else { ?>
											<a style='color:#0000FF;' id="lightbox_req_pal<? echo $g_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,1,0,0,<?=$g_data["g_item_length"];?>, <?=$g_data["g_item_width"];?>,<? echo $g_id;?>)">
										<? } ?>
										<font face="Roboto" size="1" color="#6b9624">PALLET MATCHING TOOL</font></a> 
										<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span> 
										<!-- TEST PALLET SECTION ENDS --
										<br>
				                        <br>
			                        <?
									}
								?></td>
			                    </tr> -->
			                    <tr>
									<td style="background:#FFFFFF; height:4px;"></td>
			                    </tr>
							</table>
	        			</div>
	        			<?
					}//End while($g_data = array_shift($g_res)) { 
					?>
				</div>
				<!-- Display gaylord quote end -->
				<!-- Display shipping quote start -->
				<div id="display_quote_request_ship">
		            <?
					if($clientdash_flg==1)
					{
						$getrecquery2 = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_item=2  and companyID = '" . $client_companyid . "'  and quote_request.client_dash_flg=1 order by quote_shipping_boxes.id asc";
					}
					else{
						$getrecquery2 = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_item=2  and companyID = '" . $client_companyid . "' order by quote_shipping_boxes.id asc";
					}
					$g_res2 = db_query($getrecquery2);
					$chkinitials =  $_COOKIE['userinitials'] ;
					while($sb_data = array_shift($g_res2)) {
						$sb_id=$sb_data["id"];
						?>
						<div id="sb<?=$sb_id?>">
		                  	<table width="100%" class="table1" cellpadding="3" cellspacing="1">
		                    <?
							if($sb_data["companyID"]==$client_companyid){
								$quote_item=$sb_data["quote_item"];
								//Get Item Name
								$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
								$quote_item_rs = array_shift($getquotequery);
								$quote_item_name=$quote_item_rs['item'];
								//
								$quote_date = $sb_data["quote_date"];
				                //---------------check quote send or not-------------------------------
				                $sb_qut_id=$sb_data['quote_id'];
				                //
				                $chk_quote_query1 = "Select * from quote where companyID=".$sb_data["companyID"];
				                $chk_quote_res1 = db_query($chk_quote_query1,db_b2b());
				                $no_of_quote_sent1="";
				                $qtr=0;
				                while($quote_rows1=array_shift($chk_quote_res1)) {
				                    $quote_req=$quote_rows1["quoteRequest"];
			                        $quote_req_id = explode(",", $quote_req);
			                        $total_id=count($quote_req_id);
				                    for($req = 0; $req < $total_id; $req++) {
										if($quote_req_id[$req]==$sb_qut_id){
				                            if ($quote_rows1["filename"] != "") {
			                                    $qtid=$quote_rows1["ID"];
			                                    $qtf=$quote_rows1["filename"];
			                                    $link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";
		                                    } else {
			                                    if ($quote_rows["quoteType"]=="Quote") {
			                                        $link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"] . $repchk_str . "'>";
			                                        } elseif ($quote_rows1["quoteType"]=="Quote Select") {
			                                        $link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
			                                    }else  {
			                                        $link="<a href='#'>";
			                                    }
			                                }
				                            $new_quote_id=($quote_rows1["ID"] + 3770);
				                            $no_of_quote_sent1.=$link.$new_quote_id."</a>, ";
				                            $qtr++;
				                            $no_of_quote_sent=rtrim($no_of_quote_sent1,", ");
				                        }
				                        if($qtr!=0){
				                             $sb_quote_sent_status="<span style='color:#004B03;'>QUOTE SENT</span> - ".$no_of_quote_sent;
				                        }else{
				                            $sb_quote_sent_status="<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
				                        }
				                             
				                    }
				                 }
				                db();						
								$sb_quotereq_sales_flag = "";
								$chk_deny_query = "Select * from quote_shipping_boxes where quote_id=".$sb_data["quote_id"];
								$chk_deny_res = db_query($chk_deny_query);
				   			    while($deny_row=array_shift($chk_deny_res)){
								  $sb_quotereq_sales_flag = $deny_row["sb_quotereq_sales_flag"];
								}
								
							 	if ($sb_quotereq_sales_flag == "Yes") {
									$quotereq_sales_flag_color ="#D3FFB9";
							 	}else {
									$quotereq_sales_flag_color ="#91bb78";
							 	}
								?>
								<tr bgcolor="#e4e4e4" class="rowdata">
			                      	<td colspan="4" style="background:<? echo $quotereq_sales_flag_color;?>; padding:5px;">
			                      		<table cellpadding="3" >
			                        	<tr>
			                          	<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">Box Profile ID: <? echo $sb_data["quote_id"]; ?></td>
			                          	<td class="boxProSubHeading" width="200px" style="background:<? echo $quotereq_sales_flag_color;?>;">Item Type: <? echo $quote_item_name; ?></td>
			                          	<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img id="sb_btn_img<?=$sb_id?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="sb_btn<?=$sb_id?>" onClick="show_sb_details(<?=$sb_id?>)" class="ex_col_btn boxProSubHeading"><u>Expand Details</u></a></font></td>
										<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>"  src="images/edit.jpg" onClick="sb_quote_edit(<?=$client_companyid?>, <?=$sb_id?>, <?=$quote_item?>, <?=$clientdash_flg?>)" style="cursor: pointer;"> </font></td>
										<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>">
											<a id="lightbox_req_shipping<?=$sb_id?>" href="javascript:void(0);" onClick="display_request_shipping_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $sb_id;?>,0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
										</td>
										
										<!-- <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>" src="images/del_img.png" onClick="sb_quote_delete(<?=$sb_id?>, <?=$quote_item?>,<?=$client_companyid?>)" style="cursor: pointer;"> </font></td> -->
										<td style="background:<? echo $quotereq_sales_flag_color;?>;">
											<font face="Roboto" size="1">
											<?php 
												if($clientdash_flg==0 && $sb_data["client_dash_flg"]==1)
												{
													echo "Client Dash entry";
												}
											?>
											</font>
										</td>
			                        	</tr>
			                      		</table>
			                      	</td>
		                    	</tr>
			                    <tr>
			                      <td><div id="sb_sub_table<?=$sb_id?>" style="display: none;">
			                        <table width="80%" class="in_table_style tableBorder">
			                          <tr bgcolor="<? echo $subheading; ?>">
			                            <td colspan="6"><strong>What Do They Buy?</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Item </td>
			                            <td colspan="5"> Shipping Boxes </td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td >Ideal Size (in)</td>
			                            <td  align="center"  width="130px"><span class="label_txt">L</span><br>
			                              <? echo $sb_data["sb_item_length"]; ?></td>
			                            <td width="20px" align="center">x</td>
			                            <td  align="center" width="130px"><div class="size_align"> <span class="label_txt">W</span><br>
			                              <? echo $sb_data["sb_item_width"]; ?> </div></td>
			                            <td width="20px" align="center">x</td>
			                            <td  align="center" width="130px"><div class="size_align"> <span class="label_txt">H</span><br>
			                              <? echo $sb_data["sb_item_height"]; ?> </div></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Quantity Requested </td>
			                            <td colspan=5><? echo $sb_data["sb_quantity_requested"]; ?>
			                              <?
												if($sb_data["sb_quantity_requested"]=="Other")
												{
													echo "<br>".$sb_data["sb_other_quantity"];
												}
												?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Frequency of Order </td>
			                            <td colspan=5><? echo $sb_data["sb_frequency_order"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> What Used For? </td>
			                            <td colspan=5><? echo $sb_data["sb_what_used_for"]; ?></td>
			                          </tr>
			                         <!--  <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Also Need Pallets? </td>
			                            <td colspan=5><? echo $sb_data["sb_need_pallets"]; ?></td>
			                          </tr> -->
										<tr bgcolor="<? echo $rowcolor1; ?>">
											<td>
												Desired Price
											</td>
											<td colspan=5>
												<? if($sb_data["sb_sales_desired_price"] > 0){ 
													echo "$".$sb_data["sb_sales_desired_price"];
												}else{
													echo "$0";
												} ?>
											</td>
										</tr>
				                        <tr bgcolor="<? echo $rowcolor2; ?>">
				                            <td> Notes </td>
				                            <td colspan=5><? echo $sb_data["sb_notes"]; ?></td>
				                        </tr>
				                        <tr bgcolor="<? echo $subheading2; ?>">
			                            <td colspan="6"><strong>Criteria of what they SHOULD be able to use:</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td colspan="6"><strong>Size Flexibility</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td><!-- align="right"-->
			                              Length </td>
			                            <td align="center"><span class="label_txt">Min</span> <br>
			                              <? echo $sb_data["sb_item_min_length"]; ?></td>
			                            <td align="center">-</td>
			                            <td align="center"><span class="label_txt">Max</span>  <br><? echo $sb_data["sb_item_max_length"]; ?></td>
			                            <td colspan="2" align="center">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td><!-- align="right"-->
			                              Width </td>
			                            <td align="center"><span class="label_txt">Min</span> <br>
			                              <? echo $sb_data["sb_item_min_width"]; ?></td>
			                            <td align="center">-</td>
			                            <td align="center"><span class="label_txt">Max</span>  <br><? echo $sb_data["sb_item_max_width"]; ?></td>
										<td colspan="2" align="center">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td><!-- align="right"-->
			                              Height </td>
			                            <td align="center"><span class="label_txt">Min</span> <br>
			                              <? echo $sb_data["sb_item_min_height"]; ?></td>
			                            <td align="center">-</td>
			                            <td align="center"><span class="label_txt">Max</span>  <br><? echo $sb_data["sb_item_max_height"]; ?></td>
										<td colspan="2" align="center">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td><!-- align="right"-->
			                              Cubic Footage </td>
			                            <td align="center"><span class="label_txt">Min</span> <br>
			                              <? echo $sb_data["sb_cubic_footage_min"]; ?></td>
			                            <td align="center">-</td>
			                            <td align="center"><span class="label_txt">Max</span>  <br><? echo $sb_data["sb_cubic_footage_max"]; ?></td>
										<td colspan="2" align="center">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> # of Walls </td>
			                            <td> 1ply </td>
			                            <td><? echo $sb_data["sb_wall_1"]; ?></td>
			                            <td> 2ply </td>
			                            <td colspan="2"><? echo $sb_data["sb_wall_2"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Top Config </td>
			                            <td> No Top </td>
			                            <td><? echo $sb_data["sb_no_top"]; ?></td>
			                            <td> Full Flap Top </td>
			                            <td colspan="2"><? echo $sb_data["sb_full_flap_top"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td>&nbsp;</td>
			                            <td> Partial Flap Top </td>
			                            <td><? echo $sb_data["sb_partial_flap_top"]; ?></td>
			                            <td>&nbsp;  </td>
			                            <td colspan="2">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Bottom Config </td>
			                            <td> No Bottom </td>
			                            <td><? echo $sb_data["sb_no_bottom"]; ?></td>
			                            <td> Full Flap Bottom </td>
			                            <td colspan="2"><? echo $sb_data["sb_full_flap_bottom"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td>&nbsp;</td>
			                            <td> Partial Flap Bottom </td>
			                            <td><? echo $sb_data["sb_partial_flap_bottom"]; ?></td>
			                            <td>&nbsp;</td>
			                            <td colspan="2">&nbsp;</td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Vents Okay? </td>
			                            <td colspan=5><? echo $sb_data["sb_vents_okay"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $sb_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                              Date: <?php echo date("m/d/Y H:i:s" , strtotime($sb_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
			                          </tr>
			                        </table>
			                      </div></td>
			                    </tr>
			                    <!-- <tr>
			                      <td bgcolor="#FFFFFF" style="background: #FFF;">
									<!-- TEST SHIPPING SECTION START --
									<? if($clientdash_flg==1) { ?>
			                       		<a style='color:#6b9624;' id="lightbox_req_shipping<? echo $sb_id;?>" href="javascript:void(0);" onClick="display_request_shipping_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $sb_id;?>)"> 
									<? } else{ ?>
										<a style='color:#6b9624;' id="lightbox_req_shipping<? echo $sb_id;?>" href="javascript:void(0);" onClick="display_request_shipping_tool_test(<?=$client_companyid;?>,1,1,0,<? echo $sb_id;?>)"> 
									<? } ?>						  
									<font face="Roboto" size="1" color="#6b9624">SHIPPING BOX MATCHING TOOL</font></a> <span id="req_shiptoolautoload1" name="req_shiptoolautoload1" style='color:red;'></span>
									<!-- TEST SHIPPING SECTION ENDS --
									<br>
			                        <br>
				                         
				                    <? if($sb_data["sb_need_pallets"]=="Yes"){ ?>
										TEST PALLET SECTION START
										<? if($clientdash_flg==1) { ?>
				                        	<a style='color:#0000FF;' id="lightbox_req_pal<? echo $sb_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,0,<?=$sb_data["sb_item_length"];?>, <?=$sb_data["sb_item_width"];?>, <? echo $sb_id;?>)">
										<? } else { ?>
											<a style='color:#0000FF;' id="lightbox_req_pal<? echo $sb_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,0,<?=$sb_data["sb_item_length"];?>, <?=$sb_data["sb_item_width"];?>, <? echo $sb_id;?>)">
										<? } ?>
										<font face="Roboto" size="1" color="#6b9624">PALLET MATCHING TOOL</font></a> 
										<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span> 
										<!-- TEST PALLET SECTION ENDS --
										<br>
				                        <br>
			                        <?
									}
								?></td>
			                    </tr> -->
			                    <tr>
			                      <td style="background:#FFFFFF; height:4px;"></td>
			                    </tr>
			                    <?
							}//End if company check
							?>
							</table>
		                </div>
						<?
					}
					?>
				</div>
				<!-- Display shipping quote end -->

				<!-- Display supersack quote start -->
				<div id="display_quote_request_super">
					<?
					if($clientdash_flg==1){
						$getrecquery3 = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_item=3 and client_dash_flg=1  and companyID = '" . $client_companyid . "' order by quote_supersacks.id asc";
					}else{
						$getrecquery3 = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_item=3  and companyID = '" . $client_companyid . "' order by quote_supersacks.id asc";
					}
					$g_res3 = db_query($getrecquery3);
					$chkinitials =  $_COOKIE['userinitials'] ;
					
					while($sup_data = array_shift($g_res3)) {
						$sup_id=$sup_data["id"];
						?>
	                	<div id="sup<?=$sup_id?>" >
	                  	<table width="100%" class="table1" cellpadding="3" cellspacing="1">
	                    <?
						if($sup_data["companyID"]==$client_companyid){
				
							$quote_item=$sup_data["quote_item"];
							//Get Item Name
							$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
							$quote_item_rs = array_shift($getquotequery);
							$quote_item_name=$quote_item_rs['item'];
							//
							$quote_date = $sup_data["quote_date"];
							//
			               //---------------check quote send or not-------------------------------
			                $sup_qut_id=$sup_data['quote_id'];
			                //
			                $chk_quote_query1 = "Select * from quote where companyID=".$sup_data["companyID"];
			                $chk_quote_res1 = db_query($chk_quote_query1,db_b2b());
			                $sup_no_of_quote_sent1="";
			                $qtr=0;
			                while($quote_rows1=array_shift($chk_quote_res1))
			                {
			                    $quote_req=$quote_rows1["quoteRequest"];
			                    //if (strpos($quote_req, ',') !== false) {
			                        $quote_req_id = explode(",", $quote_req);
			                        $total_id=count($quote_req_id);
			                        for($req = 0; $req < $total_id; $req++) {

			                             if($quote_req_id[$req]==$sup_qut_id)
			                             {
			                                
			                                  if ($quote_rows1["filename"] != "") {
			                                      
			                                    $qtid=$quote_rows1["ID"];
			                                    $qtf=$quote_rows1["filename"];
			                                    //
			                                    $link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";

			                                    } else {
			                                    if ($quote_rows["quoteType"]=="Quote") {
			                                        $link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"] . $repchk_str . "'>";
			                                        } elseif ($quote_rows1["quoteType"]=="Quote Select") {
			                                        $link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
			                                    }else  {
			                                        $link="<a href='#'>";
			                                    }
			                                }
			                                 
			                                 //echo $quote_req_id[$req];
			                                  $sup_no_of_quote_sent1.=$link.($quote_rows1["ID"] + 3770)."</a>, ";
			                                 $qtr++;
			                                $sup_no_of_quote_sent=rtrim($sup_no_of_quote_sent1,", ");
			                                
			                              
			                             }
			                              
			                              if($qtr!=0)
			                              {
			                                  $sup_quote_sent_status="<span style='color:#004B03;'>QUOTE SENT</span> - ".$sup_no_of_quote_sent;
			                              }
			                                
			                              else{
			                                $sup_quote_sent_status="<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
			                            }
			                             
			                        }                    
			                    //}//End str pos
			                }
	                		db();				
							$sup_quotereq_sales_flag = "";
							$chk_deny_query = "Select * from quote_supersacks where quote_id=".$sup_data["quote_id"];
							$chk_deny_res = db_query($chk_deny_query);
			   			    while($deny_row=array_shift($chk_deny_res)){
							  $sup_quotereq_sales_flag = $deny_row["sup_quotereq_sales_flag"];
							}
					
							if ($sup_quotereq_sales_flag == "Yes") {
								$quotereq_sales_flag_color ="#D3FFB9";
							}else {
								$quotereq_sales_flag_color ="#91bb78";
							}
							?>
							<tr bgcolor="#e4e4e4" class="rowdata">
	                      	<td colspan="4" style="background:<? echo $quotereq_sales_flag_color;?>; padding:5px;">
	                      		<table cellpadding="3" >
	                        		<tr>
	                          		<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">Box Profile ID: <? echo $sup_data["quote_id"]; ?></td>
	                          		<td class="boxProSubHeading" width="200px" style="background:<? echo $quotereq_sales_flag_color;?>;">Item Type: <? echo $quote_item_name; ?></td>
	                          		<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img id="sup_btn_img<?=$sup_id?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="sup_btn<?=$sup_id?>" onClick="show_sup_details(<?=$sup_id?>)"  class="ex_col_btn boxProSubHeading"><u>Expand Details</u></a></font></td>                          
									<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>"  src="images/edit.jpg" onClick="sup_quote_edit(<?=$client_companyid?>, <?=$sup_id?>, <?=$quote_item?>, <?=$clientdash_flg?>)" style="cursor: pointer;"> </font></td>
								    <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>">
										<a id="lightbox_req_supersacks<?=$sup_id?>" href="javascript:void(0);" onClick="display_request_supersacks_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $sup_id;?>, 0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
									</td>
									
									<!--<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>" src="images/del_img.png" onClick="sup_quote_delete(<?=$sup_id?>, <?=$quote_item?>,<?=$client_companyid?>)" style="cursor: pointer;"> </font></td> -->
									<td style="background:<? echo $quotereq_sales_flag_color;?>;">  
										<font face="Roboto" size="1">
										<?php 
										if($clientdash_flg==0 && $sup_data["client_dash_flg"]==1)
										{
											echo "Client Dash entry";
										}
										?>
										</font>
									</td>
	                        		</tr>
	                      		</table>
	                      		</td>
	                    	</tr>
		                    <tr>
		                      <td><div id="sup_sub_table<?=$sup_id?>" style="display: none;">
		                        <table class="table_sup" width="100%">
		                          <tr>
		                            <td><table width="80%" class="in_table_style tableBorder">
		                              <tr bgcolor="<? echo $subheading; ?>">
		                                <td colspan="6"><strong>What Do They Buy?</strong></td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor1; ?>">
		                                <td> Item </td>
		                                <td colspan="5"> Supersacks </td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor2; ?>">
		                                <td >Ideal Size (in)</td>
		                                <td  align="center" width="130px"><div class="size_align"> <span class="label_txt">L</span><br>
		                                  <? echo $sup_data["sup_item_length"]; ?> </div></td>
		                                <td width="20px" align="center">x</td>
		                                <td  align="center" width="130px"><div class="size_align"> <span class="label_txt">W</span><br>
		                                  <? echo $sup_data["sup_item_width"]; ?> </div></td>
		                                <td width="20px" align="center">x</td>
		                                <td align="center" width="130px"><div class="size_align"> <span class="label_txt">H</span><br>
		                                  <? echo $sup_data["sup_item_height"]; ?> </div></td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor1; ?>">
		                                <td> Quantity Requested </td>
		                                <td colspan=5><? echo $sup_data["sup_quantity_requested"]; ?>
		                                  <?
														if($sup_data["sup_quantity_requested"]=="Other")
														{
															echo "<br>".$sup_data["sup_other_quantity"];
														}
														?></td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor2; ?>">
		                                <td> Frequency of Order </td>
		                                <td colspan=5><? echo $sup_data["sup_frequency_order"]; ?></td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor1; ?>">
		                                <td> What Used For? </td>
		                                <td colspan=5><? echo $sup_data["sup_what_used_for"]; ?></td>
		                              </tr>
		                              <!-- <tr bgcolor="<? echo $rowcolor1; ?>">
		                                <td> Also Need Pallets? </td>
		                                <td colspan=5><? echo $sup_data["sup_need_pallets"]; ?></td>
		                              </tr> -->
										<tr bgcolor="<? echo $rowcolor1; ?>">
											<td>
												Desired Price
											</td>
											<td colspan=5>
												<? if($sup_data["sup_sales_desired_price"] > 0){ 
													echo "$".$sup_data["sup_sales_desired_price"];
												}else{ echo "$0";} ?>
											</td>
										</tr>
		                              <tr bgcolor="<? echo $rowcolor2; ?>">
		                                <td> Notes </td>
		                                <td colspan=5><? echo $sup_data["sup_notes"]; ?></td>
		                              </tr>
		                              <tr bgcolor="<? echo $rowcolor2; ?>">
		                                <td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $sup_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                                  Date: <?php echo date("m/d/Y H:i:s" , strtotime($sup_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
		                              </tr>
		                            </table></td>
		                          </tr>
		                        </table>
		                      </div></td>
		                    </tr>
		                    <!-- <tr>
		                      <td style="background:#FFFFFF;">
								<!-- TEST SUPERSACKS SECTION START --
								<? if($clientdash_flg==1) { ?>
								  	<a style='color:#6b9624;' id="lightbox_req_supersacks<? echo $sup_id;?>" href="javascript:void(0);" onClick="display_request_supersacks_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $sup_id;?>)">
								<? } else{ ?>
									<a style='color:#6b9624;' id="lightbox_req_supersacks<? echo $sup_id;?>" href="javascript:void(0);" onClick="display_request_supersacks_tool_test(<?=$client_companyid;?>,1,1,0,<? echo $sup_id;?>)">
								<? } ?>
								<font face="Roboto" size="1" color="#6b9624">SUPERSACKS MATCHING TOOL</font></a> <span id="req_supersackstoolautoload" name="req_supersackstoolautoload" style='color:red;'></span>
								<!-- TEST SUPERSACKS SECTION ENDS --
								<br>
		                        <br>
			                    <? if($sb_data["sup_need_pallets"]=="Yes"){ ?>
								TEST PALLET SECTION START
								<? if($clientdash_flg==1) { ?>
		                        		<a style='color:#6b9624;' id="lightbox_req_pal<? echo $sup_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,0,<?=$sup_data["sup_item_length"];?>, <?=$sup_data["sup_item_width"];?>, <? echo $sup_id;?>)">
								<? } else { ?>
									<a style='color:#6b9624;' id="lightbox_req_pal<? echo $sup_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,1,0,0,<?=$sup_data["sup_item_length"];?>, <?=$sup_data["sup_item_width"];?>, <? echo $sup_id;?>)">
								<? } ?>
								<font face="Roboto" size="1" color="#6b9624">PALLET MATCHING TOOL</font></a> 
								<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>
								<!-- TEST PALLET SECTION ENDS --
								 <br>
		                        <br>
		                        <?
								}
							?></td>
		                    </tr> -->
		                    <tr>
		                      <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
		                    </tr>
		                    <?
						}//End if company check
						?>
	                  	</table>
	                	</div>
	                	<?
					}//End While
					?>
				</div>
				<!-- Display supersack quote end -->
				<!-- Display pallet quote start -->
				<div id="display_quote_request_pallets">
	                <?
					if($clientdash_flg==1)
					{

						$getrecquery2 = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_item=4 and client_dash_flg=1  and companyID = '" . $client_companyid . "' order by quote_pallets.id asc";
					}
					else{
						$getrecquery2 = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_item=4  and companyID = '" . $client_companyid . "' order by quote_pallets.id asc";
					}
					$g_res2 = db_query($getrecquery2);
					//echo tep_db_num_rows($g_res);
					$chkinitials =  $_COOKIE['userinitials'] ;
					//
					while($pal_data = array_shift($g_res2)) {
						$pal_id=$pal_data["id"];
						?>
	                	<div id="pal<?=$pal_id?>" >
	                  	<table width="100%" class="table1" cellpadding="3" cellspacing="1">
	                    <?
						if($pal_data["companyID"]==$client_companyid){
							$quote_item=$pal_data["quote_item"];
							//Get Item Name
							$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
							$quote_item_rs = array_shift($getquotequery);
							$quote_item_name=$quote_item_rs['item'];
							//
							$quote_date = $pal_data["quote_date"];
							//
			                //---------------check quote send or not-------------------------------
			                $pal_qut_id=$pal_data['quote_id'];
			                //
			                $chk_quote_query1 = "Select * from quote where companyID=".$pal_data["companyID"];
			                $chk_quote_res1 = db_query($chk_quote_query1,db_b2b());
			                $pal_no_of_quote_sent1="";
			                $qtr=0;
			                while($quote_rows1=array_shift($chk_quote_res1)){
			                    $quote_req=$quote_rows1["quoteRequest"];
			                     //if (strpos($quote_req, ',') !== false) {
			                        $quote_req_id = explode(",", $quote_req);
			                        $total_id=count($quote_req_id);
			                    	// echo $total_id;

			                        for($req = 0; $req < $total_id; $req++) {

			                            if($quote_req_id[$req]==$pal_qut_id){
			                                
			                                if ($quote_rows1["filename"] != "") {
			                                      
			                                    $qtid=$quote_rows1["ID"];
			                                    $qtf=$quote_rows1["filename"];
			                                    //
			                                    $link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";

			                                } else {
			                                    if ($quote_rows["quoteType"]=="Quote") {
			                                        $link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"]. $repchk_str . "'>";
			                                        } elseif ($quote_rows1["quoteType"]=="Quote Select") {
			                                        $link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
			                                    }else  {
			                                        $link="<a href='#'>";
			                                    }
			                                }
			                                 
			                                //echo $quote_req_id[$req];
			                                $pal_no_of_quote_sent1.=$link.($quote_rows1["ID"] + 3770)."</a>, ";
			                                $qtr++;
			                                $pal_no_of_quote_sent=rtrim($pal_no_of_quote_sent1,", ");
			                                
			                              
			                            }
			                              
			                            if($qtr!=0) {
			                                  $pal_quote_sent_status="<span style='color:#004B03;'>QUOTE SENT</span> - ".$pal_no_of_quote_sent;
			                            }else{
			                                $pal_quote_sent_status="<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
			                            }
			                             
			                        }		                    
			                    //}//End str pos
			                }
			                db();						
							$pal_quotereq_sales_flag = "";
							$chk_deny_query = "Select * from quote_pallets where quote_id=".$pal_data["quote_id"];
							$chk_deny_res = db_query($chk_deny_query);
			   			    while($deny_row=array_shift($chk_deny_res)){
							  $pal_quotereq_sales_flag = $deny_row["pal_quotereq_sales_flag"];
							}
							
							if ($pal_quotereq_sales_flag == "Yes") {
								$quotereq_sales_flag_color ="#D3FFB9";
							}else {
								$quotereq_sales_flag_color ="#91bb78";
							}
							
							?>
	                    	<tr bgcolor="#e4e4e4" class="rowdata">
	                      	<td colspan="4" style="background:<? echo $quotereq_sales_flag_color;?>; padding:5px;">
	                      		<table cellpadding="3" >
	                        	<tr>
	                          	<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">Box Profile ID: <? echo $pal_data["quote_id"]; ?></td>
	                          	<td class="boxProSubHeading" width="200px" style="background:<? echo $quotereq_sales_flag_color;?>;">Item Type: <? echo $quote_item_name; ?></td>
	                          	<td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"><img id="pal_btn_img<?=$pal_id?>" src="images/plus-icon.png" />&nbsp; <a name="details_btn" id="pal_btn<?=$pal_id?>"  onClick="show_pal_details(<?=$pal_id?>)" class="ex_col_btn boxProSubHeading"><u>Expand Details</u></a></font></td>
								<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>"  src="images/edit.jpg" onClick="pal_quote_edit(<?=$client_companyid?>, <?=$pal_id?>, <?=$quote_item?>, <?=$clientdash_flg?>)" style="cursor: pointer;"> </font></td>
								<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>">
								    <a id="lightbox_req_pal<?=$pal_id?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $pal_id;?>, 0,0,0,0, 1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
								</td>
								
								<!-- <td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>" src="images/del_img.png" onClick="pal_quote_delete(<?=$pal_id?>, <?=$quote_item?>,<?=$client_companyid?>)" style="cursor: pointer;"> </font></td> -->
								<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1">
									<?php 
										if($clientdash_flg==0 && $pal_data["client_dash_flg"]==1)
										{
											echo "Client Dash entry";
										}
									?></font>
								</td>
	                        	</tr>
	                      		</table>
	                      	</td>
	                    	</tr>
		                    <tr>
		                      <td><div id="pal_sub_table<?=$pal_id?>" style="display: none;">
		                        <table width="80%" class="in_table_style tableBorder">
		                          <tr bgcolor="<? echo $subheading; ?>">
		                            <td colspan="7"><strong>What Do They Buy?</strong></td>
		                          </tr>
		                          <tr bgcolor="<? echo $rowcolor1; ?>">
		                            <td width="250px"> Item </td>
		                            <td colspan="6"> Pallets </td>
		                          </tr>
		                          <tr bgcolor="<? echo $rowcolor2; ?>">
		                            <td >Ideal Size (in)</td>
		                            <td  align="center" width="140px"><div class="size_align"> <span class="label_txt">L</span><br>
		                              <? echo $pal_data["pal_item_length"]; ?> </div></td>
		                            <td width="40px" align="center">x</td>
		                            <td  colspan="4"><div class="size_align"> <span class="label_txt">W</span><br>
		                              <? echo $pal_data["pal_item_width"]; ?> </div></td>
		                          </tr>
		                          <tr bgcolor="<? echo $rowcolor1; ?>">
		                            <td> Quantity Requested </td>
		                            <td colspan=6><? echo $pal_data["pal_quantity_requested"]; ?>
		                              <?
												if($pal_data["pal_quantity_requested"]=="Other")
												{
													echo "<br>".$pal_data["pal_other_quantity"];
												}
												?></td>
		                          </tr>
		                          <tr bgcolor="<? echo $rowcolor2; ?>">
		                            <td> Frequency of Order </td>
		                            <td colspan=6><? echo $pal_data["pal_frequency_order"]; ?></td>
		                          </tr>
		                          <tr bgcolor="<? echo $rowcolor1; ?>">
		                            <td> What Used For? </td>
		                            <td colspan=6><? echo $pal_data["pal_what_used_for"]; ?></td>
		                          </tr>
									<tr bgcolor="<? echo $rowcolor1; ?>">
											<td>
												Desired Price
											</td>
											<td colspan=6>
												<? if($pal_data["pal_sales_desired_price"] > 0){ 
													echo "$".$pal_data["pal_sales_desired_price"];
												}else{
													echo "$0";
												} ?>
											</td>
										</tr>
		                          <tr bgcolor="<? echo $rowcolor2; ?>">
		                            <td> Notes </td>
		                            <td colspan=6><? echo $pal_data["pal_note"]; ?></td>
		                          </tr>
		                          <tr bgcolor="#d5d5d5">
				                    <td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
				                      </td>
				                  </tr>
				                  <tr bgcolor="<? echo $rowcolor1; ?>">
				                    <td>Grade </td>
				                    <td>A</td>
				                    <td align="center"><?=$pal_data['pal_grade_a'];?></td>
				                    <td width="140px">B</td>
				                    <td width="40px" align="center"><?=$pal_data['pal_grade_b'];?></td>
				                  	<td width="140px">C</td>
				                    <td align="center"><?=$pal_data['pal_grade_c'];?></td>     
				                  </tr>
				                  <tr bgcolor="<? echo $rowcolor2; ?>">
				                    <td>Material </td>
				                    <td>Wooden</td>
				                    <td align="center"><?=$pal_data['pal_material_wooden'];?></td> 
				                    <td>Plastic</td>
				                    <td align="center"><?=$pal_data['pal_material_plastic'];?></td>
				                    <td>Corrugate</td>
				                    <td align="center"><?=$pal_data['pal_material_corrugate'];?>
				                    </td> 
				                  </tr>
				                  <tr bgcolor="<? echo $rowcolor1; ?>">
				                    <td>Entry</td>
				                    <td>2-way</td>
				                    <td align="center"><?=$pal_data['pal_entry_2way'];?>
				                    </td> 
				                    <td>4-way</td>
				                    <td colspan="3">&ensp;<?=$pal_data['pal_entry_4way'];?>
				                    </td>
				                  </tr>
				                  <tr bgcolor="<? echo $rowcolor2; ?>">
				                    <td>Structure</td>
				                    <td>Stringer</td>
				                    <td align="center"><?=$pal_data['pal_structure_stringer'];?></td>
				                    <td>Block</td>
				                    <td colspan="3">&ensp;<?=$pal_data['pal_structure_block'];?></td>
				                  </tr>
				                  <tr bgcolor="<? echo $rowcolor1; ?>">
				                  	<td>Heat Treated</td>
				                  	<td colspan=6><?=$pal_data['pal_heat_treated'];?></td>
				                  </tr>

		                          <tr bgcolor="<? echo $rowcolor2; ?>">
		                            <td colspan="7" align="right" style="padding: 4px;"> Created By:<?php echo $pal_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                              Date: <?php echo date("m/d/Y H:i:s" , strtotime($pal_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
		                          </tr>
		                        </table>
		                      </div></td>
		                    </tr>
		                    <!-- <tr>
		                      <td style="background:#FFFFFF;">
								<!-- TEST PALLET SECTION START --
								<? if($clientdash_flg==1) { ?>
		                        		<a style='color:#91bb78;' id="lightbox_req_pal<? echo $pal_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $pal_id;?>)">
								<? } else { ?>
									<a style='color:#91bb78;' id="lightbox_req_pal<? echo $pal_id;?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?=$client_companyid;?>,1,1,0,<? echo $pal_id;?>)">
								<? } ?>
								<font face="Roboto" size="1" color="#91bb78">PALLET MATCHING TOOL</font></a> 
								<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span> 
								<!-- TEST PALLET SECTION ENDS --
								<br>
		                        <br></td>
		                    </tr> -->
		                    <tr>
		                      <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
		                    </tr>
	                    	<?
						}//End if company check
						?>
	                	</table>
	            		</div>
	            		<?
					}//End While
					?>
	   			</div>
				<!-- Display pallet quote end -->
				<!-- Display other quote start -->
				<!-- <div id="display_quote_request_other">
	                <?
					if($clientdash_flg==1){
						$getrecquery2 = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_item=7 and client_dash_flg=1  and companyID = '" . $client_companyid . "' order by quote_other.id asc";
					}else{
						$getrecquery2 = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_item=7  and companyID = '" . $client_companyid . "' order by quote_other.id asc";
					}
					$g_res2 = db_query($getrecquery2);
					//echo tep_db_num_rows($g_res);
					$chkinitials =  $_COOKIE['userinitials'] ;
					//
					while($other_data = array_shift($g_res2)) {
						$other_id=$other_data["id"];
						?>
	                	<div id="other<?=$other_id?>">
	                  	<table width="100%" class="table1" cellpadding="3" cellspacing="1">
	                    	<?
							if($other_data["companyID"]==$client_companyid){
								$quote_item=$other_data["quote_item"];
								//Get Item Name
								$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=".$quote_item);
								$quote_item_rs = array_shift($getquotequery);
								$quote_item_name=$quote_item_rs['item'];
								//
								$quote_date = $other_data["quote_date"];
				                $other_qut_id=$other_data['quote_id'];
				                $chk_quote_query1 = "Select * from quote where companyID=".$other_data["companyID"];
				                $chk_quote_res1 = db_query($chk_quote_query1,db_b2b());
				                $other_no_of_quote_sent1="";
				                $qtr=0;
				                while($quote_rows1=array_shift($chk_quote_res1)) {
	                     			$quote_req=$quote_rows1["quoteRequest"];
	                     			//if (strpos($quote_req, ',') !== false) {
	                        		$quote_req_id = explode(",", $quote_req);
	                        		$total_id=count($quote_req_id);
	                    			// echo $total_id;
	                         		for($req = 0; $req < $total_id; $req++) {
	                             		if($quote_req_id[$req]==$other_qut_id) {
	                                
	                                  		if ($quote_rows1["filename"] != "") {
	                                      
			                                    $qtid=$quote_rows1["ID"];
			                                    $qtf=$quote_rows1["filename"];
			                                    //
			                                    $link="<a href='#' id='quotespdf".$qtid."' onclick=\"show_file_inviewer_pos('quotes/".$qtf."', 'Quote', 'quotespdf".$qtid."'); return false;\">";
			                                } else {
			                                    if ($quote_rows["quoteType"]=="Quote") {
			                                        $link="<a target='_blank' href='fullquote_mrg.php?ID=".$quote_rows1["ID"] . $repchk_str . "'>";
			                                        } elseif ($quote_rows1["quoteType"]=="Quote Select") {
			                                        $link="<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=".$quote_rows1["ID"]."' target='_blank'>";
			                                    }else  {
			                                        $link="<a href='#'>";
			                                    }
	                                		}
	                                 
	                                 		//echo $quote_req_id[$req];
	                                  		$other_no_of_quote_sent1.=$link.($quote_rows1["ID"] + 3770)."</a>, ";
	                                 		$qtr++;
	                                		$other_no_of_quote_sent=rtrim($other_no_of_quote_sent1,", ");
	                             		}
	                              
			                            if($qtr!=0) {
			                                $other_quote_sent_status="<span style='color:#004B03;'>QUOTE SENT</span> - ".$other_no_of_quote_sent;
			                            }else{
			                                $other_quote_sent_status="<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
			                            }
	                         		}
	                    
	                    			//}//End str pos
	                 			}
				                db();
								$other_quotereq_sales_flag = "";
								$chk_deny_query = "Select * from quote_other where quote_id=".$other_data["quote_id"];
								$chk_deny_res = db_query($chk_deny_query);
				   			    while($deny_row=array_shift($chk_deny_res)){
								  $other_quotereq_sales_flag = $deny_row["other_quotereq_sales_flag"];
								}
								if ($other_quotereq_sales_flag == "Yes") {
									$quotereq_sales_flag_color ="#D3FFB9";
								}else {
									$quotereq_sales_flag_color ="#91bb78";
								}
								?>
			                    <tr bgcolor="#e4e4e4" class="rowdata">
			                      <td colspan="4" style="background:<? echo $quotereq_sales_flag_color;?>; padding:5px;"><table cellpadding="3" >
			                        <tr>
			                          <td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;">Box Profile ID: <? echo $other_data["quote_id"]; ?></td>
			                          <td class="boxProSubHeading" width="200px" style="background:<? echo $quotereq_sales_flag_color;?>;">Item Type: <? echo $quote_item_name; ?></td>
			                          <td class="boxProSubHeading" style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <a name="details_btn" id="other_btn<?=$other_id?>" onClick="show_other_details(<?=$other_id?>)" class="ex_col_btn boxProSubHeading">Expand Details</a></font></td>
										<td style="background:<? echo $quotereq_sales_flag_color;?>;"><img bgcolor="<? echo $quotereq_sales_flag_color;?>"  src="images/edit.jpg" onClick="other_quote_edit(<?=$client_companyid?>, <?=$other_id?>, <?=$quote_item?>, <?=$clientdash_flg?>)" style="cursor: pointer;"></td>
										<td style="background:<? echo $quotereq_sales_flag_color;?>;"><font face="Roboto" size="1" color="<? echo $quotereq_sales_flag_color;?>"> <img bgcolor="<? echo $quotereq_sales_flag_color;?>" src="images/del_img.png" onClick="other_quote_delete(<?=$other_id?>, <?=$quote_item?>,<?=$client_companyid?>)" style="cursor: pointer;"> </font></td>
										<td>
											<font face="Roboto" size="1">
												<?php 
													if($clientdash_flg==0 && $other_data["client_dash_flg"]==1)
													{
														echo "Client Dash entry";
													}
												?>
													</font>
										</td>
			                        </tr>
			                      </table></td>
			                    </tr>
			                    <tr>
			                      <td><div id="other_sub_table<?=$other_id?>" style="display: none;">
			                        <table width="80%" class="in_table_style tableBorder">
			                          <tr bgcolor="<? echo $subheading; ?>">
			                            <td colspan="6"><strong>What Do They Buy?</strong></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Quantity Requested </td>
			                            <td colspan=5><? echo $other_data["other_quantity_requested"]; ?>
			                              <?
													if($other_data["other_quantity_requested"]=="Other")
													{
														echo "<br>".$other_data["other_other_quantity"];
													}
													?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Frequency of Order </td>
			                            <td colspan=5><? echo $other_data["other_frequency_order"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> What Used For? </td>
			                            <td colspan=5><? echo $other_data["other_what_used_for"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor1; ?>">
			                            <td> Also Need Pallets? </td>
			                            <td colspan=5><? echo $other_data["other_need_pallets"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td> Notes </td>
			                            <td colspan=5><? echo $other_data["other_note"]; ?></td>
			                          </tr>
			                          <tr bgcolor="<? echo $rowcolor2; ?>">
			                            <td colspan="6" align="right" style="padding: 4px;"> Created By:<?php echo $other_data['user_initials']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                              Date: <?php echo date("m/d/Y H:i:s" , strtotime($other_data['quote_date'])); ?> &nbsp;&nbsp;&nbsp; </td>
			                          </tr>
			                        </table>
			                      </div></td>
			                    </tr>
								<tr>
			                      <td style="background:#FFFFFF;">
									<!-- TEST OTHER MATCHING SECTION STARTS --
									<? if($clientdash_flg==1) { ?>
										<a style='color:#91bb78;' id="lightbox_req_other<? echo $other_id;?>" href="javascript:void(0);" onClick="display_request_other_tool_test(<?=$client_companyid;?>,1,2,1,<? echo $other_id;?>)">
									<? } else{ ?>
										<a style='color:#91bb78;' id="lightbox_req_other<? echo $other_id;?>" href="javascript:void(0);" onClick="display_request_other_tool_test(<?=$client_companyid;?>,1,1,0,<? echo $other_id;?>)">
									<? } ?>
									<font face="Roboto" size="1" color="#91bb78">OTHER MATCHING TOOL</font></a> <span id="req_othertoolautoload" name="req_othertoolautoload" style='color:red;'></span>
									<!-- TEST OTHER MATCHING SECTION ENDS --
									<br>
			                      
			                        
									</td>
			                    </tr>
			                    <tr>
			                      <td colspan="4" style="background:#FFFFFF; height:4px;"></td>
			                    </tr>
	                    		<?
							}//End if company check
							?>
	                  	</table>
	                	</div>
	                	<?
					}//End While
					?>
	            </div> -->
				<!-- Display other quote end -->

			</td>
		</tr>
		<!-- FOR RESPONCE DISPLAY END -->
		
	</table>
<? } else if($_REQUEST['show'] == 'favorites'){ ?>
	<?
	if(isset($_REQUEST['hdnFavItemsAction']) && $_REQUEST['hdnFavItemsAction'] == 1 ){
		//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
		$selFavIds = db_query("SELECT id FROM clientdash_favorite_items WHERE compid = ".$client_companyid." ORDER BY id DESC", db());
		while ($rowsFavIds = array_shift($selFavIds)) {
			if(in_array($rowsFavIds['id'], $_REQUEST['favItemIds'] )){
				//echo "<br/> if -> "."UPDATE clientdash_favorite_items SET favItems = 1 WHERE id =".$rowsFavIds['id'];
				db_query("UPDATE clientdash_favorite_items SET favItems = 1 WHERE id =".$rowsFavIds['id'], db());
			}else{
				//echo "<br /> else -> "."UPDATE clientdash_favorite_items SET favItems = 0 WHERE id =".$rowsFavIds['id'];
				db_query("UPDATE clientdash_favorite_items SET favItems = 0 WHERE id =".$rowsFavIds['id'], db());
			}
		}
	}

	$x = "Select * from companyInfo Where ID = '" . $client_companyid . "'";
	$dt_view_res = db_query($x,db_b2b() );
	
	while ($row = array_shift($dt_view_res)) {
			//if((remove_non_numeric($row["shipZip"])) !="")
		if(($row["shipZip"]) !="") {
			//$zipShipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($row["shipZip"]);
			$tmp_zipval = "";
			$tmp_zipval = str_replace(" ", "", $row["shipZip"]);
			if($row["shipcountry"] == "Canada" )
			{ 	
				$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
			}elseif(($row["shipcountry"]) == "Mexico" ){
				$zipShipStr= "Select * from zipcodes_mexico limit 1";
			}else {
				$zipShipStr= "Select * from ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
			}
							
			$dt_view_res = db_query($zipShipStr,db_b2b() );
			while ($zip = array_shift($dt_view_res)) {
				$shipLat = $zip["latitude"];
				$shipLong = $zip["longitude"];
			}
		}
	}
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Favorites/Re-order</h3></td>
		</tr>
		<tr >
		<td align="left">
		<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10">
		<tbody>
		<tr class="headrow"><td>Favorites</td></tr>
		<tr>
			<td>
				<form name="frmFavItems" method="post" action="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=favorites<?=$repchk_str?>">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tr class="headrow2">
					<? if ($hide_bu_now == 0) {?>					
						<td class='display_title'>View Item</td>
					<? } ?>					
					<td class='display_title'>Qty Avail</td>		
					<td class='display_title'>Lead Time</td>			
					<td class='display_title'>Expected # of Loads/Mo</td>			
					<td class='display_title'>Per Truckload</td>			
					<? if($show_boxprofile_inv == 'yes'){ ?> 
						<td class='display_title'>FOB Origin Price/Unit</td>
					<? } ?> 	
					<td class='display_title'>B2B ID</td>
					<td class='display_title'>Miles Away
						<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Green Color - miles away <= 250, 
							<br>Orange Color - miles away <= 550 and > 250, 
							<br>Red Color - miles away > 550</span>
						</div>		
					</td>				
					<td align="center" class='display_title'>L x W x H</td>		
					<td class='display_title'>Walls</td>		
					<td class='display_title'>Description</td>		
					<td class='display_title'>Ship From</td>
					<td class=''>Box Type</td>
					<td class='display_title'>Remove?</td>
				</tr>
				<?
				$selFavData = db_query("SELECT * FROM clientdash_favorite_items WHERE compid = '".$client_companyid."'and favItems = 1 ORDER BY fav_miles", db());
				//echo "<pre>";print_r($selFavData);echo "</pre>";
				$selFavDataCnt = tep_db_num_rows($selFavData);
				if($selFavDataCnt > 0 ){
					$i = 0; $rowcolor = 0;
					while ($rowsFavData = array_shift($selFavData)) {
						//echo "<pre> rowsFavData ->";print_r($rowsFavData);echo "</pre>";
						$after_po_val_tmp = 0; $after_po_val = 0; 
						$pallet_val_afterpo = $preorder_txt2 = "";
						$rec_found_box = "n";
						$boxes_per_trailer= 0;
						$next_load_available_date = "";
						if($rowsFavData['fav_b2bid'] > 0){
							$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = ".$rowsFavData['fav_b2bid'], db());
							//echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
							$rowBoxDt = array_shift($selBoxDt);

							if($rowBoxDt['b2b_id'] > 0){
								$selInvDt = db_query("SELECT * FROM inventory WHERE ID = ".$rowBoxDt['b2b_id'], db_b2b());
								$rowInvDt = array_shift($selInvDt);

								$box_type = $rowInvDt['box_type'];
								$box_warehouse_id = $rowBoxDt["box_warehouse_id"];
								$next_load_available_date = $rowBoxDt["next_load_available_date"];
								$boxes_per_trailer= $rowBoxDt['boxes_per_trailer'];
								if($rowInvDt["loops_id"] > 0){
									$dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
									$dt_view_res_box = db_query($dt_view_qry,db_b2b() );
									while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
										$rec_found_box = "y";
										$actual_val = $dt_view_res_box_data["actual"];
										$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
										$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									}
									if ($rec_found_box == "n"){
										$actual_val = $rowInvDt["actual_inventory"];
										$after_po_val =$rowInvDt["after_actual_inventory"];
										$last_month_qty = $rowInvDt["lastmonthqty"];
									}
									if ($box_warehouse_id == 238){
										$after_po_val =$rowInvDt["after_actual_inventory"];
									}else{
										$after_po_val = $after_po_val_tmp;
									}


									if ($after_po_val < 0) { 
										$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
									}else if ($after_po_val >= $boxes_per_trailer) { 
										$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
									} else {  
										$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
									}


									$estimated_next_load = ""; $b2bstatuscolor="";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")){
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays=round($datediff / (60 * 60 * 24));
										//echo $no_of_loaddays;
										if($no_of_loaddays<$lead_time){
											if($rowInvDt["lead_time"]>1){
												$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
											}else{
												$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
											}
										}else{
											$estimated_next_load= "<font color=green> " .$no_of_loaddays. " Days </font>";
										}
									}else{
										if ($after_po_val >= $boxes_per_trailer) {
											if ($rowInvDt["lead_time"] == 0){
												$estimated_next_load= "<font color=green> Now </font>";
											}	
											if ($rowInvDt["lead_time"] == 1){
												$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
											}							
											if ($rowInvDt["lead_time"] > 1){
												$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
											}							
										}else{
											if (($rowInvDt["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)){
												$estimated_next_load= "<font color=red> Never (sell the " . $after_po_val . ") </font>";
											}else{	
												// logic changed by Zac
												//$estimated_next_load=round((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." weeks";;
												//echo "next_load_available_date - " . $inv["I"] . " " . $after_po_val . " " . $boxes_per_trailer . " " . $inv["expected_loads_per_mo"] .  "<br>";
												$estimated_next_load=ceil((((($after_po_val/$boxes_per_trailer)*-1)+1)/$rowInvDt["expected_loads_per_mo"])*4)." Weeks";
											}
										}

										if ($after_po_val == 0 && $rowInvDt["expected_loads_per_mo"] == 0 ) {
											$estimated_next_load= "<font color=red> Ask Rep </font>";
										}

										if ($rowInvDt["expected_loads_per_mo"] == 0 ) {
											$expected_loads_per_mo= "<font color=red>0</font>";
										}else{
											$expected_loads_per_mo= $rowInvDt["expected_loads_per_mo"];
										}
									}

									$b2b_status=$rowInvDt["b2b_status"];
									$b2bstatuscolor="";
									$st_query="SELECT * FROM b2b_box_status WHERE status_key='".$b2b_status."'";
									$st_res = db_query($st_query, db() );
									$st_row = array_shift($st_res);
									$b2bstatus_name=$st_row["box_status"];
									if($st_row["status_key"]=="1.0" || $st_row["status_key"]=="1.1" || $st_row["status_key"]=="1.2"){
										$b2bstatuscolor="green";
									}elseif($st_row["status_key"]=="2.0" || $st_row["status_key"]=="2.1" || $st_row["status_key"]=="2.2"){
										$b2bstatuscolor="orange";
										$estimated_next_load= "<font color=red> Ask Rep </font>";
									}
									
									$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
									
									$b2b_onlineDollar = round($rowInvDt["onlineDollar"]);
									$b2b_onlineCents = $rowInvDt["onlineCents"];
									$b2b_online_fob = $b2b_onlineDollar + $b2b_onlineCents;
									$b2b_online_fob = "$" . number_format($b2b_online_fob,2);

									if($rowInvDt["location_country"] == "Canada" ){ 	
										$tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"] );
										$zipStr= "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($rowInvDt["location_country"]) == "Mexico" ){
										$zipStr= "SELECT * FROM zipcodes_mexico LIMIT 1";
									}else {
										$zipStr= "SELECT * FROM ZipCodes WHERE zip = '" .intval($rowInvDt["location_zip"]). "'";
									}

									$dt_view_res3 = db_query($zipStr,db_b2b() );
									while ($ziploc = array_shift($dt_view_res3)) {
										$locLat = $ziploc["latitude"];		
										$locLong = $ziploc["longitude"];		
									}
									//	echo $locLong;
									$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
									$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

									$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
									//echo $inv["I"] . " " . $distA . "p <br/>"; 
									$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));

									$miles_from = (int) (6371 * $distC * .621371192);							
									if ($miles_from <= 250){	//echo "chk gr <br/>";
										$miles_away_color = "green";
									}
									if ( ($miles_from <= 550) && ($miles_from > 250)){	
										$miles_away_color = "#FF9933";
									}
									if (($miles_from > 550) ){	
										$miles_away_color = "red";
									}


									if($rowInvDt["uniform_mixed_load"]=="Mixed"){
										$blength=$rowInvDt["blength_min"]. " - ". $rowInvDt["blength_max"];
										$bwidth=$rowInvDt["bwidth_min"]. " - ". $rowInvDt["bwidth_max"];
										$bdepth=$rowInvDt["bheight_min"]. " - ". $rowInvDt["bheight_max"];
									}else{
										$blength = $rowInvDt["lengthInch"];
										$bwidth = $rowInvDt["widthInch"];
										$bdepth = $rowInvDt["depthInch"];
									}
									$blength_frac = 0; $bwidth_frac = 0; $bdepth_frac = 0;
									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;		
									if ($rowInvDt["lengthFraction"] != "") {
										$arr_length = explode("/", $rowInvDt["lengthFraction"]);
										if (count($arr_length) > 0 ) {
											$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($rowInvDt["widthFraction"] != "") {
										$arr_width = explode("/", $rowInvDt["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0])/intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($rowInvDt["depthFraction"] != "") {
										$arr_depth = explode("/", $rowInvDt["depthFraction"]);
										if (count(arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0])/intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}


									if($rowBoxDt["box_warehouse_id"]=="238"){ 
										$vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
										$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = '" . $vendor_b2b_rescue_id . "'";
										$get_loc_res = db_query($get_loc_qry,db_b2b() );
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"]; 
										$shipfrom_state = $loc_row["shipState"]; 
										$shipfrom_zip = $loc_row["shipZip"]; 
									}else{									
										$vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];	
										$get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='".$vendor_b2b_rescue_id."'";
										$get_loc_res = db_query($get_loc_qry,db() );
										$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["company_city"]; 
											$shipfrom_state = $loc_row["company_state"]; 
											$shipfrom_zip = $loc_row["company_zip"]; 
									}
									$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
									$ship_from2 = get_territory($shipfrom_state);	


									if ($rowcolor % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
									$rowcolor = $rowcolor + 1;
									?>
									<tr class="<?=$rowclr?>">				
										<? if ($hide_bu_now == 0) { ?>
											<td class='' width="10%"><a href='https://b2b.usedcardboardboxes.com/?id=<? echo urlencode(encrypt_password(get_loop_box_id($rowBoxDt['b2b_id'])));?>&compnewid=<? echo urlencode(encrypt_password($client_companyid))?>&miles=<? echo urlencode(encrypt_password($miles_from));?>' target='_blank' >View Item</a></td>
										<? } ?>
											
										<td class='' width="5%"><?=$qty?></td>		
										<td class='' width="8%"><?=$estimated_next_load?></td>			
										<td class='' width="5%"><?=$expected_loads_per_mo?></td>			
										<td class='' width="5%"><?=$boxes_per_trailer?></td>			
										<? if($show_boxprofile_inv == 'yes'){ ?> 
											<td class='' width="5%"><?=$b2b_online_fob?></td>
										<? } ?> 	
										<td class='' width="5%"><?=$rowInvDt['ID']?></td>
										<td class='' width="5%"><font color='<?=$miles_away_color;?>'><?=$miles_from?></font></td>				
										<td align="center" width="15%" class=''><? echo $length . " x " . $width . " x " . $depth;?> </td>		
										<td class='' width="5%"><?=$rowInvDt["bwall"]?></td>		
										<td class='' width="20%"><?=$rowInvDt["description"]?></td>		
										<td class='' width="5%"><?=$ship_from2?></td>
										<td class='' width="7%">
											<? 
											$arrG = array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" );
											$arrSb = array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" );
											$arrSup = array("SupersackUCB", "SupersacknonUCB" );
											$arrPal = array("PalletsUCB", "PalletsnonUCB");
											$arrOther = array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " ");
											if (in_array($box_type, $arrG) ){
												$boxtype		= "Gaylord"; 
											}elseif (in_array($box_type, $arrSb)){
												$boxtype		= "Shipping"; 
											}elseif (in_array($box_type, $arrSup)){ 
												$boxtype		= "SuperSack"; 
											}elseif (in_array($box_type, $arrPal)){ 
												$boxtype		= "Pallet";
											}elseif (in_array($box_type, $arrOther)) { 
												$boxtype		= "Other";
											}
											echo $boxtype;
											?>
										</td>
										<td class='' width="5%">
											<a id='btnremove' href='javascript:void(0);' onClick='Remove_favorites(<?=$rowsFavData['id'];?>, <? echo $client_companyid;?>)' >Remove</a>
											<input type="hidden" name="hdnFavItemsAction" id="hdnFavItemsAction" value="1">		
											<input type="hidden" name="hdnCompanyId" id="hdnCompanyId" value="<?=$_REQUEST['compnewid'];?>">
											<input type="hidden" name="repchk_str" id="repchk_str" value="<?=$repchk_str;?>">
										</td>
									</tr>
									<?
								}
							}
						}
						$i++;


					}
				}else{
					?>
					<tr><td colspan="18">No record found</td></tr>
					<?
				}
				?>
				</table>
				</form>
			</td>
		</tr>

		<tr class="headrow"><td>Previously Ordered</td></tr>
		<tr>
			<td>
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">

				<input type="hidden" name="fav_match_id" id="fav_match_id" value="<?=$client_companyid?>">
				<input type="hidden" name="fav_match_boxid" id="fav_match_boxid" value="0">
				<input type="hidden" name="fav_match_display-allrec" id="fav_match_display-allrec" value="">
				<input type="hidden" name="fav_match_viewflg" id="fav_match_viewflg" value="">
				<input type="hidden" name="fav_match_flg" id="fav_match_flg" value="">
				<input type="hidden" name="fav_match_load_all" id="fav_match_load_all" value="">
				<input type="hidden" name="fav_match_client_flg" id="fav_match_client_flg" value="">
				<input type="hidden" name="fav_match_inboxprofile" id="fav_match_inboxprofile" value="">
				<input type="hidden" name="fav_boxtype" id="fav_boxtype" value="preorder">
			
				<tr class="headrow2">
					<? if ($hide_bu_now == 0) { ?>
						<td class='display_title'>View Item</td>
					<? } ?>														
					<td class='display_title'>Qty Avail</td>		
					<td class='display_title'>Lead Time</td>			
					<td class='display_title'>Expected # of Loads/Mo</td>			
					<td class='display_title'>Per Truckload</td>			
					<? if($show_boxprofile_inv == 'yes'){ ?> 
						<td class='display_title'>FOB Origin Price/Unit</td>
					<? } ?> 	
					<td class='display_title'>B2B ID</td>
					<td class='display_title'>Miles Away
						<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Green Color - miles away <= 250, 
							<br>Orange Color - miles away <= 550 and > 250, 
							<br>Red Color - miles away > 550</span>
						</div>		
					</td>				
					<td align="center" class='display_title'>L x W x H</td>		
					<td class='display_title'>Walls</td>		
					<td class='display_title'>Description</td>		
					<td class='display_title'>Ship From</td>
					<td class=''>Box Type</td>
					<td class=''>Favorites</td>
					
				</tr>
				<?
				//$selPrevDt = db_query("SELECT loop_bol_tracking.box_id FROM loop_transaction_buyer INNER JOIN loop_bol_files ON loop_bol_files.trans_rec_id = loop_transaction_buyer.id INNER JOIN loop_bol_tracking ON loop_bol_tracking.trans_rec_id = loop_transaction_buyer.id WHERE loop_transaction_buyer.warehouse_id = ".$client_loopid." AND loop_bol_files.bol_shipment_received = 1 GROUP BY loop_bol_tracking.box_id ORDER BY loop_transaction_buyer.id DESC", db());
				$selPrevDt = db_query("SELECT box_id from loop_bol_tracking inner join loop_boxes on loop_bol_tracking.box_id = loop_boxes.id where trans_rec_id in (SELECT id FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id = '".$client_loopid."' and `ignore` = 0) GROUP BY loop_bol_tracking.box_id order by loop_boxes.bdescription", db());
				//echo "<pre>";print_r($selPrevDt);echo "</pre>";
				$arr_previously_ordered = array();
				$i = 0;
				while ($rowsPrevDt = array_shift($selPrevDt)) {
					$after_po_val_tmp = 0; $after_po_val = 0; 
					$pallet_val_afterpo = $preorder_txt2 = "";
					$rec_found_box = "n";
					$boxes_per_trailer= 0;
					$next_load_available_date = "";
					if($rowsPrevDt['box_id'] > 0){
						$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE id = ".$rowsPrevDt['box_id'], db());
						$rowBoxDt = array_shift($selBoxDt);

						//echo "<br /> i -> ".$i." / b2b_id -> ".$rowBoxDt['b2b_id']." / box_id -> ".$rowsPrevDt['box_id'];
						if($rowBoxDt['b2b_id'] > 0){
							$selInvDt = db_query("SELECT * FROM inventory WHERE ID = ".$rowBoxDt['b2b_id'], db_b2b());
							$rowInvDt = array_shift($selInvDt);

							$box_type = $rowInvDt['box_type'];
							$box_warehouse_id = $rowBoxDt["box_warehouse_id"];
							$next_load_available_date = $rowBoxDt["next_load_available_date"];
							$boxes_per_trailer= $rowBoxDt['boxes_per_trailer'];
							if($rowInvDt["loops_id"] > 0){
								$dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
								$dt_view_res_box = db_query($dt_view_qry,db_b2b() );
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
								}
								if ($rec_found_box == "n"){
									$actual_val = $rowInvDt["actual_inventory"];
									$after_po_val =$rowInvDt["after_actual_inventory"];
									$last_month_qty = $rowInvDt["lastmonthqty"];
								}
								if ($box_warehouse_id == 238){
									$after_po_val =$rowInvDt["after_actual_inventory"];
								}else{
									$after_po_val = $after_po_val_tmp;
								}


								if ($after_po_val < 0) { 
									$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
								}else if ($after_po_val >= $boxes_per_trailer) { 
									$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
								} else {  
									$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
								}


								$estimated_next_load = ""; $b2bstatuscolor="";
								if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")){
									$now_date = time(); // or your date as well
									$next_load_date = strtotime($next_load_available_date);
									$datediff = $next_load_date - $now_date;
									$no_of_loaddays=round($datediff / (60 * 60 * 24));
									//echo $no_of_loaddays;
									if($no_of_loaddays<$lead_time){
										if($rowInvDt["lead_time"]>1){
											$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
										}else{
											$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
										}
									}else{
										$estimated_next_load= "<font color=green> " .$no_of_loaddays. " Days </font>";
									}
								}else{
									if ($after_po_val >= $boxes_per_trailer) {
										if ($rowInvDt["lead_time"] == 0){
											$estimated_next_load= "<font color=green> Now </font>";
										}	
										if ($rowInvDt["lead_time"] == 1){
											$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Day </font>";
										}							
										if ($rowInvDt["lead_time"] > 1){
											$estimated_next_load= "<font color=green> " . $rowInvDt["lead_time"] . " Days </font>";
										}							
									}else{
										if (($rowInvDt["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)){
											$estimated_next_load= "<font color=red> Never (sell the " . $after_po_val . ") </font>";
										}else{	
											// logic changed by Zac
											//$estimated_next_load=round((((($after_po_val/$boxes_per_trailer)*-1)+1)/$inv["expected_loads_per_mo"])*4)." weeks";;
											//echo "next_load_available_date - " . $inv["I"] . " " . $after_po_val . " " . $boxes_per_trailer . " " . $inv["expected_loads_per_mo"] .  "<br>";
											$estimated_next_load=ceil((((($after_po_val/$boxes_per_trailer)*-1)+1)/$rowInvDt["expected_loads_per_mo"])*4)." Weeks";
										}
									}

									if ($after_po_val == 0 && $rowInvDt["expected_loads_per_mo"] == 0 ) {
										$estimated_next_load= "<font color=red> Ask Rep </font>";
									}

									if ($rowInvDt["expected_loads_per_mo"] == 0 ) {
										$expected_loads_per_mo= "<font color=red>0</font>";
									}else{
										$expected_loads_per_mo= $rowInvDt["expected_loads_per_mo"];
									}
								}

								$b2b_status=$rowInvDt["b2b_status"];
								$b2bstatuscolor="";
								$st_query="SELECT * FROM b2b_box_status WHERE status_key='".$b2b_status."'";
								$st_res = db_query($st_query, db() );
								$st_row = array_shift($st_res);
								$b2bstatus_name=$st_row["box_status"];
								if($st_row["status_key"]=="1.0" || $st_row["status_key"]=="1.1" || $st_row["status_key"]=="1.2"){
									$b2bstatuscolor="green";
								}elseif($st_row["status_key"]=="2.0" || $st_row["status_key"]=="2.1" || $st_row["status_key"]=="2.2"){
									$b2bstatuscolor="orange";
									$estimated_next_load= "<font color=red> Ask Rep </font>";
								}

								$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];

								$b2b_onlineDollar = round($rowInvDt["onlineDollar"]);
								$b2b_onlineCents = $rowInvDt["onlineCents"];
								$b2b_online_fob = $b2b_onlineDollar + $b2b_onlineCents;
								$b2b_online_fob = "$" . number_format($b2b_online_fob,2);

								if($rowInvDt["location_country"] == "Canada" ){ 	
									$tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
									$zipStr= "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
								}elseif(($rowInvDt["location_country"]) == "Mexico" ){
									$zipStr= "SELECT * FROM zipcodes_mexico LIMIT 1";
								}else {
									$zipStr= "SELECT * FROM ZipCodes WHERE zip = '" .intval($rowInvDt["location_zip"]). "'";
								}
								$dt_view_res3 = db_query($zipStr,db_b2b() );
								while ($ziploc = array_shift($dt_view_res3)) {
									$locLat = $ziploc["latitude"];		
									$locLong = $ziploc["longitude"];		
								}
								//	echo $locLong;
								$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
								$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

								$distA = Sin($distLat/2) * Sin($distLat/2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong/2) * Sin($distLong/2);
								//echo $inv["I"] . " " . $distA . "p <br/>"; 
								$distC = 2 * atan2(sqrt($distA),sqrt(1-$distA));

								$miles_from = (int) (6371 * $distC * .621371192);							
								if ($miles_from <= 250){	//echo "chk gr <br/>";
									$miles_away_color = "green";
								}
								if ( ($miles_from <= 550) && ($miles_from > 250)){	
									$miles_away_color = "#FF9933";
								}
								if (($miles_from > 550) ){	
									$miles_away_color = "red";
								}


								if($rowInvDt["uniform_mixed_load"]=="Mixed"){
									$blength=$rowInvDt["blength_min"]. " - ". $rowInvDt["blength_max"];
									$bwidth=$rowInvDt["bwidth_min"]. " - ". $rowInvDt["bwidth_max"];
									$bdepth=$rowInvDt["bheight_min"]. " - ". $rowInvDt["bheight_max"];
								}else{
									$blength = $rowInvDt["lengthInch"];
									$bwidth = $rowInvDt["widthInch"];
									$bdepth = $rowInvDt["depthInch"];
								}
								$blength_frac = 0; $bwidth_frac = 0; $bdepth_frac = 0;
								$length = $blength;
								$width = $bwidth;
								$depth = $bdepth;		
								if ($rowInvDt["lengthFraction"] != "") {
									$arr_length = explode("/", $rowInvDt["lengthFraction"]);
									if (count($arr_length) > 0 ) {
										$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
										$length = floatval($blength + $blength_frac);
									}
								}
								if ($rowInvDt["widthFraction"] != "") {
									$arr_width = explode("/", $rowInvDt["widthFraction"]);
									if (count($arr_width) > 0) {
										$bwidth_frac = intval($arr_width[0])/intval($arr_width[1]);
										$width = floatval($bwidth + $bwidth_frac);
									}
								}
								if ($rowInvDt["depthFraction"] != "") {
									$arr_depth = explode("/", $rowInvDt["depthFraction"]);
									if (count(arr_depth) > 0) {
										$bdepth_frac = intval($arr_depth[0])/intval($arr_depth[1]);
										$depth = floatval($bdepth + $bdepth_frac);
									}
								}


								if($rowBoxDt["box_warehouse_id"]=="238"){ 
									$vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
									$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = '" . $vendor_b2b_rescue_id . "'";
									$get_loc_res = db_query($get_loc_qry,db_b2b() );
									$loc_row = array_shift($get_loc_res);
									$shipfrom_city = $loc_row["shipCity"]; 
									$shipfrom_state = $loc_row["shipState"]; 
									$shipfrom_zip = $loc_row["shipZip"]; 
								}else{									
									$vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];	
									$get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='".$vendor_b2b_rescue_id."'";
									$get_loc_res = db_query($get_loc_qry,db() );
									$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"]; 
										$shipfrom_state = $loc_row["company_state"]; 
										$shipfrom_zip = $loc_row["company_zip"]; 
								}
								$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
								$ship_from2 = get_territory($shipfrom_state);	


								if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
								$loop_box_id = get_loop_box_id($rowBoxDt['b2b_id']);

								
								$arr_previously_ordered[] = array('loop_box_id' => $loop_box_id, 'client_companyid' => $client_companyid,
								'qty' => $qty, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'boxes_per_trailer' => $boxes_per_trailer, 
								'b2b_online_fob' => $b2b_online_fob, 'ID' => $rowInvDt['ID'], 'miles_away_color' => $miles_away_color,
								'miles_from' => $miles_from, 'length' => $length, 'width' => $width, 'depth' => $depth, 'bwall' => $rowInvDt["bwall"], 
								'description' => $rowInvDt["description"], 'ship_from2' => $ship_from2,
								'boxtype' => $boxtype);
								
							}
						}
					}
					$i++;
				}
				
			/*	$MGArraysort = array();
		 
				foreach ($arr_previously_ordered as $MGArraytmp) {
					$MGArraysort[] = $MGArraytmp['miles_from'];
				}
				array_multisort($MGArraysort, SORT_NUMERIC, $arr_previously_ordered);	
			*/
				foreach ($arr_previously_ordered as $MGArraytmp2) {
					
					$selFavouriteDt = db_query("SELECT id FROM clientdash_favorite_items WHERE favItems = 1 AND fav_b2bid = '".$MGArraytmp2["ID"]."' AND compid = '".$MGArraytmp2["client_companyid"]."'", db());
					//echo "SELECT id FROM clientdash_favorite_items WHERE favItems = 1 AND fav_b2bid = '".$MGArraytmp2["ID"]."' AND compid = '".$MGArraytmp2["client_companyid"]."'";
					?>
					<tr class="<?=$rowclr?>">		
						<? if ($hide_bu_now == 0) { ?>
							<td class='' width="10%"><a href='https://b2b.usedcardboardboxes.com/?id=<? echo urlencode(encrypt_password($MGArraytmp2["loop_box_id"]));?>&compnewid=<? echo urlencode(encrypt_password($MGArraytmp2["client_companyid"]));?>&miles=<? echo urlencode(encrypt_password($MGArraytmp2["miles_from"]));?>' target='_blank' >View Item</a></td>
						<? } ?>														
					
						<td class='' width="5%"><?=$MGArraytmp2["qty"]?></td>		
						<td class='' width="8%"><?=$MGArraytmp2["estimated_next_load"]?></td>			
						<td class='' width="5%"><?=$MGArraytmp2["expected_loads_per_mo"]?></td>		
						
						<td class='' width="5%"><?=$MGArraytmp2["boxes_per_trailer"]?></td>			
						<? if($show_boxprofile_inv == 'yes'){ ?> 
							<td class='' width="5%"><?=$MGArraytmp2["b2b_online_fob"]?></td>
						<? } ?> 	
						<td class='' width="5%"><?=$MGArraytmp2["ID"]?></td>
						<td class='' width="5%"><font color='<?=$MGArraytmp2["miles_away_color"]?>'><?=$MGArraytmp2["miles_from"]?></font></td>				
						<td align="center" width="15%" class=''><? echo $MGArraytmp2["length"] . " x " . $MGArraytmp2["width"] . " x " . $MGArraytmp2["depth"];?> </td>		
						<td class='' width="5%"><?=$MGArraytmp2["bwall"]?></td>		
						<td class='' width="20%"><?=$MGArraytmp2["description"]?></td>		
						<td class='' width="5%"><?=$MGArraytmp2["ship_from2"]?></td>
						<td class='' width="7%">
							<? 
							echo $MGArraytmp2["boxtype"];
							?>
						</td>
						<td class='' width="7%">
							<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["qty"]?>">
							<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["estimated_next_load"]?>">
							<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["expected_loads_per_mo"]?>">
							<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["boxes_per_trailer"]?>">
							<input type="hidden" name="fav_fob" id="fav_fob<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["b2b_online_fob"]?>">
							<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["ID"]?>">
							<input type="hidden" name="fav_miles" id="fav_miles<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["miles_from"]?>">

							<input type="hidden" name="fav_bl" id="fav_bl<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["length"]?>">
							<input type="hidden" name="fav_bw" id="fav_bw<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["width"]?>">
							<input type="hidden" name="fav_bh" id="fav_bh<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["depth"]?>">
							<input type="hidden" name="fav_walls" id="fav_walls<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["bwall"]?>">
							<input type="hidden" name="fav_desc" id="fav_desc<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["description"]?>">
							<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $MGArraytmp2["ID"]; ?>" value="<?=$MGArraytmp2["ship_from2"]?>">
						
							<div id="fav_div_display<? echo $MGArraytmp2["ID"];?>">
							
								<? 
								if(!empty($selFavouriteDt)){	
								?>
									<a id="div_favourite<? echo $MGArraytmp2["ID"];?>" href='javascript:void(0);' onClick='remove_item_as_favorite("<? echo $MGArraytmp2["ID"];?>",1)'><img src='images/fav.png' width='10px' height='10px'></a>
								<?
								}
								else{
								?>
									<a id='div_favourite<? echo $MGArraytmp2["ID"];?>' href='javascript:void(0);' onClick='add_item_as_favorite("<? echo $MGArraytmp2["ID"];?>",1)' ><img src='images/non_fav.png' width='12px' height='12px'> </a>
								<?
								}
								?>
							</div>	
						</td>
						
					</tr>
					<?				
				}
				?>
			</table>				
			</td>
		</tr>
		</tbody>	
		</table>
		</td>
		</tr>
	</table>
<? } else if($_REQUEST['show'] == 'closed_loop_inv' && $closed_loop_inv_flg == 'yes'){ ?>
	<?
	?>
	<table width="100%">
		<tr valign="top">
		<td align="center"><h3>Closed Loop Inventory</h3></td>
		</tr>
		<tr >
		<td align="left">
		<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10">
		<tbody>
		<tr class="headrow"><td>Closed Loop Inventory</td></tr>
		<tr>
			<td>
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
					<?
					$selFavDt = db_query("SELECT * FROM clientdash_closed_loop_items WHERE compid = '".$client_companyid."' and favItems = 1 ORDER BY fav_bl asc", db());
					//echo "<pre>";print_r($selFavDt);echo "</pre>";
					$selFavDtCnt = tep_db_num_rows($selFavDt);
					?>
					<tr class="headrow2" >
						<td class='display_title'>Actual qty</td><!-- Qty Avail -->	
						<td class='display_title'>After PO qty</td>
						<td align="center" class='display_title'>L x W x H</td>	
						<td class='display_title'>Description</td>
						<td class='display_title'>Per pallet</td>
						<td class='display_title'>Per truckload</td>			
						<td class='display_title'>ID</td><!-- B2B ID -->
						<td class='display_title'>Ship from</td>
					</tr>
					<?			
					
					if($selFavDtCnt > 0 ){
						$i = 0;
						while ($rowsFavDt = array_shift($selFavDt)) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }

							//Get After PO quantity
							$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = ".$rowsFavDt['fav_b2bid'], db());
							//echo "<pre> selBoxDt ->";print_r($selBoxDt);echo "</pre>";
							$rowBoxDt = array_shift($selBoxDt);
							if($rowBoxDt['b2b_id'] > 0){
								$selInvDt = db_query("SELECT * FROM inventory WHERE ID = ".$rowBoxDt['b2b_id'], db_b2b());
								$rowInvDt = array_shift($selInvDt);
							}

							$loop_id = $rowBoxDt['id'];
							$bpallet_qty= $rowBoxDt['bpallet_qty']; 
							$boxes_per_trailer= $rowBoxDt['boxes_per_trailer']; 
							$box_warehouse_id = $rowBoxDt["box_warehouse_id"];								
							
							//Get ship from
							if($loc_res["box_warehouse_id"]=="238")	{
								$vendor_b2b_rescue_id = $rowBoxDt["vendor_b2b_rescue"];
								$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = '" . $vendor_b2b_rescue_id . "'";
								$get_loc_res = db_query($get_loc_qry,db_b2b() );
								$loc_row = array_shift($get_loc_res);
								$shipfrom_city = $loc_row["shipCity"]; 
								$shipfrom_state = $loc_row["shipState"]; 
								$shipfrom_zip = $loc_row["shipZip"]; 
							} else{
								$vendor_b2b_rescue_id = $rowBoxDt["box_warehouse_id"];	
								$get_loc_qry = "SELECT * FROM loop_warehouse WHERE id ='".$vendor_b2b_rescue_id."'";
								$get_loc_res = db_query($get_loc_qry,db() );
								$loc_row = array_shift($get_loc_res);
								$shipfrom_city = $loc_row["company_city"]; 
								$shipfrom_state = $loc_row["company_state"]; 
								$shipfrom_zip = $loc_row["company_zip"]; 
							}

							$ship_from  = $shipfrom_city . ", " . $shipfrom_state;
							
							$after_po_val_tmp = 0; $actual_val = 0;
							$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $loop_id . " order by warehouse, type_ofbox, Description";
							$dt_view_res_box = db_query($dt_view_qry,db_b2b() );
							while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
								$rec_found_box = "y";
								$actual_val = $dt_view_res_box_data["actual"];
								$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
								$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
								$sales_order_qty = $dt_view_res_box_data["sales_order_qty"];
								//
							}

							if ($rec_found_box == "n"){
								$after_po_val = $rowInvDt["after_actual_inventory"];
								$last_month_qty = $rowInvDt["lastmonthqty"];

								//$actual_val = $rowInvDt["actual_inventory"];
								
								$dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.work_as_kit_box, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.work_as_kit_box as kb, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse 
								ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.id = '" . $loop_id . "'
								GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
								$dt_view_res = db_query($dt_view_qry,db() );
								while ($dt_view_row = array_shift($dt_view_res)) {
									$actual_val = $dt_view_row["A"];
								}
								
							}

							if ($box_warehouse_id == 238){
								$after_po_val = $rowInvDt["after_actual_inventory"];
							}else{	
								$after_po_val = $after_po_val_tmp;						
							}								
							
							if($rowInvDt["uniform_mixed_load"]=="Mixed"){
								$blength=$rowInvDt["blength_min"]. " - ". $rowInvDt["blength_max"];
								$bwidth=$rowInvDt["bwidth_min"]. " - ". $rowInvDt["bwidth_max"];
								$bdepth=$rowInvDt["bheight_min"]. " - ". $rowInvDt["bheight_max"];
							} else{
								$blength = $rowInvDt["lengthInch"];
								$bwidth = $rowInvDt["widthInch"];
								$bdepth = $rowInvDt["depthInch"];
							}	
							$blength_frac = 0;
							$bwidth_frac = 0;
							$bdepth_frac = 0;

							$length = $blength;
							$width = $bwidth;
							$depth = $bdepth;
							
							if ($rowInvDt["lengthFraction"] != "") {
								$arr_length = explode("/", $rowInvDt["lengthFraction"]);
								if (count($arr_length) > 0 ) {
									$blength_frac = intval($arr_length[0])/intval($arr_length[1]);
									$length = floatval($blength + $blength_frac);
								}
							}
							if ($rowInvDt["widthFraction"] != "") {
								$arr_width = explode("/", $rowInvDt["widthFraction"]);
								if (count($arr_width) > 0) {
									$bwidth_frac = intval($arr_width[0])/intval($arr_width[1]);
									$width = floatval($bwidth + $bwidth_frac);
								}
							}	

							if ($rowInvDt["depthFraction"] != "") {
								$arr_depth = explode("/", $rowInvDt["depthFraction"]);
								if (count(arr_depth) > 0) {
									$bdepth_frac = intval($arr_depth[0])/intval($arr_depth[1]);
									$depth = floatval($bdepth + $bdepth_frac);
								}
							}	

							$fav_bl = $length;
							$fav_bw = $width;
							$fav_bh = $depth;		
							
							?>
							<tr class="<?=$rowclr?>" >	
								<td class='' width="5%"><?=$actual_val?></td>
								<td class='' width="5%"><?=$after_po_val?></td>			
								<td align="center" width="15%" class=''><? echo $fav_bl . " x " . $fav_bw . " x " . $fav_bh;?> </td>	
								<td class='' width="20%"><?=$rowInvDt["description"]?></td>
								<td class='' width="5%"><?=$bpallet_qty;?></td>			
								<td class='' width="5%"><?=$boxes_per_trailer?></td>			
								<td class='' width="5%"><?=$rowsFavDt['fav_b2bid']?></td>
								<td class='' width="5%"><?=$ship_from;?></td>
							</tr>
							<?
							$i++;
						}
					}else{
						?>
						<tr><td colspan="18">No record found</td></tr>
						<?
					}
					?>
				</table>
			</td>
		</tr>
		</tbody>	
		</table>
		</td>
		</tr>
	</table>
<? } else if($_REQUEST['show'] == 'reports'){ ?>
	<?
	$sort_order = "ASC";
	if($_GET['sort_order'] == "ASC"){
		$sort_order = "DESC";
	}else{
		$sort_order = "ASC";
	}
	$yearStartDt = date('01/01/Y');
	$yearTodaysDt = date('m/d/Y');
	?>
	<table width="100%">
		<tr >
			<td align="center"><h3>Reports</h3></td>
		</tr>
		<tr >
			<td align="left">
				<table width="100%" cellspacing="1" cellpadding="1" border="0" class="mb-10">
				<tbody>
					<tr class="headrow"><td colspan="3" >Boxes ordered report:</td></tr>
					<tr>
						<td colspan="3">
						<form action="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=reports<?=$repchk_str?>" name="rptboxreport" method="post" >
						<table cellSpacing="1" cellPadding="1" border="0" style="width:70%">
						<tr>
							<td align="left" bgColor="#e4e4e4">From Date: 
								<input type="text" name="start_date" id="start_date" size="10" value="<?php echo isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : $yearStartDt; ?>" > 
								<a href="#" onclick="cal2xx.select(document.rptboxreport.start_date,'start_date','MM/dd/yyyy'); return false;" name="start_date_a" id="start_date_a" ><img border="0" src="images/calendar.jpg"></a>
								<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
							</td>	
							<td bgColor="#e4e4e4">To: 
								<input type="text" name="end_date" id="end_date" size="10" value="<?php echo isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $yearTodaysDt; ?>" > 
								<a href="#" onclick="cal2xx.select(document.rptboxreport.end_date,'end_date','MM/dd/yyyy'); return false;" name="end_date_a" id="end_date_a" ><img border="0" src="images/calendar.jpg"></a>
								<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
							</td>
							
							<? if ($parent_child == "Parent" && $setup_family_tree == 0) { ?>
								<td bgColor="#e4e4e4">
									<b>WHICH VIEW:</b> 
									<select name="dView" id="dView" >
										<option value="1" <?=(($_REQUEST['dView'] == 1)? "Selected" : "");?>>This Location Only</option>
										<option value="2" <?=(($_REQUEST['dView'] == 2)? "Selected" : "");?>>Corporate View</option>
									</select>	
								</td>
							<? } ?>
							
							<td bgColor="#e4e4e4" align="left" colspan="2">
								<input type="button" name="btnboxrep" value="Run Box Order Report" onclick="boxreport('<? echo $sales_rep_login; ?>',<? echo $client_companyid; ?>, <? echo $client_loopid; ?>)"/>
							</td>
						</tr>											
						</table>
						</form>														
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div id="boxtrailer_rep_div"></div>
						</td>
					</tr>
					
				</tbody>	
				</table>
			</td>
		</tr>

	</table>

<? }else if($_REQUEST['show'] == 'history'){ ?>
	<?
	$sort_order = "ASC";
	if($_GET['sort_order'] == "ASC"){
		$sort_order = "DESC";
	}else{
		$sort_order = "ASC";
	}
	$yearStartDt = date('01/01/Y');
	$yearTodaysDt = date('m/d/Y');
	?>
	<table width="100%">
		<tr >
			<td align="center"><h3>Current orders/history</h3></td>
		</tr>
		<tr >
			<td align="left">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
					<tr class="headrow"><td colspan="14" >Search order history</td></tr>
					<tr>
						<td colspan="13">
						<form action="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=history<?=$repchk_str?>" name="rptviewshipment" method="post" >
							<input type="hidden" name="warehouse_id" value="<? echo $client_loopid; ?>"/>
							<input type="hidden" name="rec_type" value="Supplier"/>		
							<input type="hidden" name="repchk" value="<? echo $_REQUEST["repchk"]; ?>"/>	
							
							<table cellSpacing="1" cellPadding="1" border="0" style="width:700px">
								<tr>
									<td align="left" bgColor="#e4e4e4">Date shipped from: 
										<input type="text" name="vs_start_date" id="vs_start_date" size="8" value="<?php echo isset($_REQUEST['vs_start_date']) ? $_REQUEST['vs_start_date'] : $yearStartDt; ?>" > 
										<a href="#" onclick="cal2xx.select(document.rptviewshipment.vs_start_date,'vs_start_date','MM/dd/yyyy'); return false;" name="vs_start_date_a" id="vs_start_date_a" ><img border="0" src="images/calendar.jpg"></a>
										<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
									</td>	
									<td bgColor="#e4e4e4">To: 
										<input type="text" name="vs_end_date" id="vs_end_date" size="8" value="<?php echo isset($_REQUEST['vs_end_date']) ? $_REQUEST['vs_end_date'] : $yearTodaysDt; ?>" > 
										<a href="#" onclick="cal2xx.select(document.rptviewshipment.vs_end_date,'vs_end_date','MM/dd/yyyy'); return false;" name="vs_end_date_a" id="vs_end_date_a" ><img border="0" src="images/calendar.jpg"></a>
										<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
									</td>
									<? if ($parent_child == "Parent" && $setup_family_tree == 0) { ?>
										<td bgColor="#e4e4e4">
											<b>WHICH VIEW:</b> 
											<select name="dView" id="dView">
												<option value="1" <?=(($_REQUEST['dView'] == 1)? "Selected" : "");?>>This Location Only</option>
												<option value="2" <?=(($_REQUEST['dView'] == 2)? "Selected" : "");?>>Corporate View</option>
											</select>	
										</td>
									<? } ?>									
									
									<td bgColor="#e4e4e4" align="left">
										<input type="submit" name="btnviewship" value="Run Report" onclick="show_loading()" />
									</td>
								</tr>				
							</table>
						</form>	
						<br />
						<!-- <i>Note: Please wait until you see <font size="1" color="red">"END OF REPORT"</font> at the bottom of the report, before using the sort option.</i>	 -->								
						</td>
					</tr>
					
					<? //if (!isset($_REQUEST["vs_start_date"])){
						?>
						<tr ><td colspan="13">&nbsp;</td></tr>
						
						<!-- Current order-->
						<tr class="headrow"><td colspan="13">
							Current orders
						</td></tr>

						<tr class="headrow">
							<?  $col_cnt_tmp = 0; 	?>
							<?
							if ($child_comp_new != "" && (($_REQUEST['dView'] == 2) ) ) { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
								<td class="blackFont" align="center">
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=location&sort_order=<?=$sort_order;?>&sorting_ship=yes">Location</a>
								</td>
							<? } ?>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=order_id&sort_order=<?=$sort_order;?>&sorting_ship=yes">Order ID</a>
							</td>

							<? if ($section_lastship_col1_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=datesubmit&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Submitted</a>
							</td>
							<? } ?>

							<? if ($section_lastship_col2_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=status&sort_order=<?=$sort_order;?>&sorting_ship=yes">Status</a>
								<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">
										Cancelled <br />Invoice sent<br />Customer Signed BOL<br />Courtesy Followup Made<br/>Delivered<br/>Shipped - Driver Signed<br/>Shipped</br/>BOL @ Warehouse<br/>BOL Sent to Warehouse<br />BOL Created<br />Freight Booked<br />Sales Order Entered<br />PO Uploaded<br/> Order Entered - UPLOAD PO TABLE <br /> Pre-order - UPLOAD PO + Marked as Preorder <br /> Accumulating Inventory - UPLOAD PO + Unmarked as Preorder <br /> Arranging Pickup - Ready to hand to freight manager
									</span>
								</div>
							</td>
							<? } ?>

							<? if ($section_lastship_col3_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
								<td class="blackFont" align="center">
									Purchase Order
								</td>
							<? } ?>

							<? if ($section_lastship_col7_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
								<td align="middle" style="width: 70px" class="blackFont" align="center">
									View BOL
								</td>
								<td class="blackFont" align="center">
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&org_del_date=yes">Original Planned Delivery Date</a>
								</td>
								<td class="blackFont" align="center">
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&curr_planned_del=yes">Current Planned Delivery Date'</a>
								</td>
							<? } ?>

							<? if ($section_lastship_col4_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 3;
							?>
								<td class="blackFont" align="center">
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Shipped</a>
								</td>
							<? } ?>

							<? if ($section_lastship_col4_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
								<td class="blackFont" align="center">
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=datedeliver&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Delivered</a>
								</td>
							<? } ?>

							<? if ($section_lastship_col5_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=boxes&sort_order=<?=$sort_order;?>&sorting_ship=yes">Quantity</a></b>
							</td>
							<? } ?>

							<? if ($section_lastship_col6_flg == "yes") { 
								$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
							<td class="blackFont" align="center">
								Invoice
							</td>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=invamt&sort_order=<?=$sort_order;?>&sorting_ship=yes">Amount</a>
							</td>
							<? } ?>
							<? if ($section_lastship_col8_flg == "yes") {  ?>
							<td class="blackFont" align="center">
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=current_order&repchk=<?=$_REQUEST["repchk"];?>&sort=invage&sort_order=<?=$sort_order;?>&sorting_ship=yes">Invoice Age</a>
							</td>
							<? } ?>
						</tr>
						<?
						$todaysDate = date('Y-m-d');
						$toDate 	= date("Y-m-d");
						$fromDate 	= date("Y-m-01", strtotime("-12 months"));
						if($_REQUEST['show']=="current_order"){
								$sort_order_arrtxt = "SORT_ASC";
								if ($_REQUEST["sort_order"] != "") {
									if ($_REQUEST["sort_order"] == "ASC") {
										$sort_order_arrtxt = "SORT_ASC";
									} else {
										$sort_order_arrtxt = "SORT_DESC";
									}
								} else {
									$sort_order_arrtxt = "SORT_DESC";
								}
							
								$MGArray = $_SESSION['sortarrayn_dsh_curr_ord'];
								if($_REQUEST["sort"] == "order_id")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['order_id'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}

								if($_REQUEST["sort"] == "location")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['company'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								
								if($_REQUEST["sort"] == "datesubmit")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['dt_submitted_sort'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
										
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
										
									}
								}
								if($_REQUEST["sort"] == "accout_manager")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['accountowner'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								
								if($_REQUEST["sort"] == "status")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['status'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								if($_REQUEST["sort"] == "dateshipped")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['dt_shipped_sort'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								if($_REQUEST["sort"] == "datedeliver")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['dt_delv_sort'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								if($_REQUEST["sort"] == "invamt")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['inv_amount'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_NUMERIC,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_NUMERIC,$MGArray); 
									}
								}
								if($_REQUEST["sort"] == "boxes")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['boxes_sort'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
								if($_REQUEST["sort"] == "invage")
								{
									foreach ($MGArray as $MGArraytmp) {
										$MGArraysort[] = $MGArraytmp['invoice_age'];
									}
									if ($sort_order_arrtxt == "SORT_ASC") {
										array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
									}else{
										array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
									}
								}
							
								$i = 0;
								foreach ($MGArray as $MGArraytmp2) {
									if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
									$total_loc[] = $MGArraytmp2["comp_id"];
									?>
									<tr vAlign="center" class="<?=$rowclr?>">
										<? if ($child_comp_new != "") { 
											?>
											<td class="style3"  align="center">	
												<? echo $MGArraytmp2["company"]; ?>
											</td>
										<? } ?>
										<td class="style3"  align="center">	
											<? echo $MGArraytmp2["order_id"]; ?>
										</td>
										<? if ($section_lastship_col1_flg == "yes") { ?>
										<td class="style3"  align="center">	
											<? echo $MGArraytmp2["dt_submitted"]; ?>
										</td>
										<? } ?>
										<? if ($section_lastship_col2_flg == "yes") { ?>
										<td class="style3"  align="center">	
											<? echo $MGArraytmp2["status"]; ?>
										</td>
										<? } ?>
										<? 
											if ($section_lastship_col3_flg == "yes") { ?>
											<td class="style3"  align="center" id="po_order_show<?=$tmpcnt;?>">	
												<? echo $MGArraytmp2["purchase_order"]; ?>
											</td>
										<? } ?>

										<? if ($section_lastship_col7_flg == "yes") { ?>
											<td class="style3" align="center" id="bol_show<?=$tmpcnt;?>">	
												<? echo $MGArraytmp2["viewbol"]; ?>
											</td>
											<td class="style3"  align="center">
												<? echo $MGArraytmp2["original_planned_delivery_dt"]; ?>
											</td>
											<td class="style3"  align="center">
												<? echo $MGArraytmp2["po_delivery_dt"]; ?>
											</td>
										<? } ?>
										
										<? if ($section_lastship_col4_flg == "yes") { ?>
											<td class="style3"  align="center">
												<? echo $MGArraytmp2["dt_shipped"]; ?>
											</td>
										<? } ?>

										<? if ($section_lastship_col4_flg == "yes") { ?>
										<td class="style3"  align="center">
											<? echo $MGArraytmp2["dt_delv"]; ?>
										</td>
										<? } ?>
										<? if ($section_lastship_col5_flg == "yes") { ?>
										<td class="style3"  align="center">	
											<? echo $MGArraytmp2["boxes"]; ?>
										</td>
										<? } ?>

										<? if ($section_lastship_col6_flg == "yes") { ?>
										<td class="style3"  align="center" id="inv_file_show<?=$tmpcnt;?>">	
											<? echo $MGArraytmp2["invoice"]; ?>
										</td>
										<td class="style3"  align="right">	
											<? echo number_format($MGArraytmp2["inv_amount"],2); ?>
										</td>
										<? } ?>

										<? if ($section_lastship_col8_flg == "yes") { ?>
											<td bgColor="<? echo $MGArraytmp2["invoice_age_color"];?>" class="style3" align="center" >
												<?  echo $invoice_age; ?>
											</td>
										 <?
											}

											$tot_inv_amount = $tot_inv_amount + $MGArraytmp2["inv_amount"];			
											$total_trans=$total_trans +1;			
										?>				
										
									</tr>
									<?
									$report_total_loc=count(array_unique($total_loc));
									$tmpcnt = $tmpcnt + 1;
									$i++;
								}
								
							if ($section_lastship_col6_flg == "yes") {
								if ($tot_inv_amount > 0 ){?>
									<tr  class="rowdata" ><td bgColor="#e4e4e4" colspan="<? echo $col_cnt_tmp+1; ?>" class="style3" align="right"><b>Total Amount:</b>&nbsp;</td><td bgColor="#e4e4e4" class="style3" align="right"><strong><? echo "$" . number_format($tot_inv_amount,2); ?></strong></td><td bgColor="#e4e4e4">&nbsp;</td></tr>
								<?	
								}
								if ($child_comp != "") { 
									if($total_trans>0){
									?>
									<tr  class="rowdata">
										<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
										<?
											echo "<font size='1'><b>Total Number of Locations: ".$report_total_loc."";
										?>
										</td>
										<td bgColor="#e4e4e4">&nbsp;</td>
									</tr>
									<tr  class="rowdata">
										<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
											<?
											echo "<font size='1'><b>Total Number of Transactions: ".$total_trans."";
											?>
										</td>
										<td bgColor="#e4e4e4">&nbsp;</td>
									</tr>
									<?
									}
								}
							}
							
						}else{
							if ($_REQUEST['dView'] == 2 && $child_comp_new != "") {
								$query1 = "SELECT loop_transaction_buyer.* FROM loop_transaction_buyer WHERE invoice_paid = 0 and loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp_new . ") AND loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' and loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.ignore = 0 ORDER BY warehouse_id asc, loop_transaction_buyer.id DESC";
							}else{
								//$query1 = "SELECT loop_transaction_buyer.* FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.shipped = 1 AND loop_transaction_buyer.inv_amount > 0 and pmt_entered = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 and loop_transaction_buyer.warehouse_id = $client_loopid AND loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id DESC ";
								if ($child_comp_new != ""){
									$query1 = "SELECT loop_transaction_buyer.* FROM loop_transaction_buyer 
									WHERE loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 and loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp_new . ") AND loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id DESC ";
								}else{
									$query1 = "SELECT loop_transaction_buyer.* FROM loop_transaction_buyer 
									WHERE loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 and loop_transaction_buyer.warehouse_id in ( " . $client_loopid . ") AND loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id DESC ";
								}
							}
							//echo "<br /> query1 - ".$query1;
							$res1 = db_query($query1, db());
							//echo "<pre> res1 - ";  print_r($res1); echo "</pre>";
							$tmpcnt=0; $tot_inv_amount = 0; $total_trans=0;
							$i = 0;
							while($row1 = array_shift($res1)) {
								if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
								
								$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C FROM loop_bol_tracking WHERE trans_rec_id = ". $row1["id"] . " AND (STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') >= '" . $fromDate . "' and STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') <= '" . $toDate . "') ORDER BY C  DESC";
								//echo "<br /> query - ".$query;	
								$res = db_query($query);
								//echo "<pre> res - ";  print_r($res); echo "</pre>";
								while($row = array_shift($res)){
									//DATE SHIPPED FETCH START
									$resShippedDate = db_query("SELECT bol_shipped, bol_shipped_date FROM loop_bol_files WHERE trans_rec_id = ".$row1["id"], db());
									//DATE SHIPPED FETCH END
									//This is the payment Info for the Customer paying UCB
									$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $row1["id"];
									$payment_qry = db_query($payments_sql );
									$payment = array_shift($payment_qry);
									
									$tot_inv_amount = $tot_inv_amount + $row1["inv_amount"];
									$total_trans=$total_trans +1;
									//This is the payment info for UCB paying the related vendors
									$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row1["id"];
									$vendor_qry = db_query($vendor_sql );
									$vendor = array_shift($vendor_qry);

									//Info about Shipment
									$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $row1["id"] . "' ORDER BY id DESC";
									//echo $bol_file_qry ;
									$bol_file_res = db_query($bol_file_qry );
									$bol_file_row = array_shift($bol_file_res);

									/*GET UPLOAD PO TABLE INFO*/
									$qryPOTable = "SELECT * FROM loop_transaction_buyer_poeml WHERE trans_rec_id LIKE '" . $row1["id"] . "' ORDER BY unqid DESC";
									//echo $bol_file_qry ;
									$resPOTable = db_query($qryPOTable);
									$cntPOTable = tep_db_num_rows($resPOTable);
									/*GET UPLOAD PO TABLE INFO*/

									$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $row1["id"];
									$fbookresult = db_query($fbooksql );
									$freightbooking = array_shift($fbookresult);

									$vendors_paid = 0; //Are the vendors paid
									$vendors_entered = 0; //Has a vendor transaction been entered?
									$invoice_paid = 0; //Have they paid their invoice?
									$invoice_entered=0; //Has the inovice been entered
									$signed_customer_bol= 0; 	//Customer Signed BOL Uploaded
									$courtesy_followup = 0; 	//Courtesy Follow Up Made
									$delivered = 0; 	//Delivered
									$signed_driver_bol = 0; 	//BOL Signed By Driver
									$shipped = 0; 	//Shipped
									$bol_received = 0; 	//BOL Received @ WH
									$bol_sent = 0; 	//BOL Sent to WH"
									$bol_created = 0; 	//BOL Created
									$freight_booked = 0; //freight booked
									$sales_order = 0;   // Sales Order entered
									$po_uploaded = 0;  //po uploaded 
					 
									//Are all the vendors paid?
									if ($vendor["B"] == 2 && $vendor["C"] == 2) 
									{ 
										$vendors_paid = 1; 
									}
									
									//Have we entered a vendor transaction?
									if ($vendor["A"] > 0) { 
										$vendors_entered = 1; 
									} 		

									//Have they paid their invoice?
									if (number_format($row1["inv_amount"],2) == number_format($payment["A"],2) && $row1["inv_amount"] != "") 
									{ 
										$invoice_paid = 1; 
									} 
									if ($row1["no_invoice"] == 1) {				$invoice_paid = 1; 			}

									if ($invoice_paid == 0){
										//Has an invoice amount been entered?
										if ($row1["inv_amount"] > 0) { 
											$invoice_entered=1; 
										} 

										if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") { $signed_customer_bol=1; }	//Customer Signed BOL Uploaded
										if ($bol_file_row["bol_shipment_followup"] >0) { $courtesy_followup = 1; }	//Courtesy Follow Up Made
										if ($bol_file_row["bol_shipment_received"] >0) { $delivered=1; }	//Delivered
										if ($bol_file_row["bol_signed_file_name"]!= "") { $signed_driver_bol=1; }	//BOL Signed By Driver
										if ($bol_file_row["bol_shipped"] >0) { $shipped = 1; }	//Shipped
										if ($bol_file_row["bol_received"] >0) { $bol_received = 1; }	//BOL Received @ WH
										if ($bol_file_row["bol_sent"] > 0) { $bol_sent = 1; }	//BOL Sent to WH"
										if ($bol_file_row["id"] > 0) { $bol_created=1; }	//BOL Created

										if ($freightbooking["id"] > 0 ) { $freight_booked = 1;} //freight booked
										
										$start_t = strtotime($row1["inv_date_of"]);
										$end_time =  strtotime(now);
										$invoice_age = number_format(($end_time-$start_t)/(3600*24),0);		
										
										if (($row1["so_entered"] == 1)) {	 $sales_order = 1; } //sales order created
										if ($row1["po_date"] != "") { $po_uploaded = 1; } //po uploaded 			
										
										$nn =""; $dt_submitted = ""; $status = "";
										$dt_submitted_sort = ""; $dt_shipped_sort = ""; $dt_delv_sort = "";
										
										$dt_submitted = date('m-d-Y', strtotime($row1["start_date"]));
										$dt_submitted_sort = date('Y-m-d', strtotime($row1["start_date"]));
										
										$dt_shipped = ""; $dt_delv = ""; $boxes = ""; $inv_age = ""; $purchase_order = ""; $viewbol = ""; $invoice ="";
										
										if ($row["B"] > 0)  { $dt_shipped = date('m-d-Y', strtotime($row["B"])); $dt_shipped_sort = date('Y-m-d', strtotime($row["B"]));}
										if ($bol_file_row['bol_shipment_received_date'] > 0)  { $dt_delv = date('m-d-Y', strtotime($bol_file_row['bol_shipment_received_date'])); $dt_delv_sort = date('Y-m-d', strtotime($bol_file_row['bol_shipment_received_date']));}
										if ($row["A"] > 0)   { $boxes = number_format($row["A"],0); $boxes_sort = $row["A"]; } 
										
										$original_planned_delivery_dt = ""; $po_delivery_dt = "";
										if ($row1["original_planned_delivery_dt"] != ""){
											$original_planned_delivery_dt = date('m/d/Y', strtotime($row1["original_planned_delivery_dt"]));
										}	
										if ($row1["po_delivery_dt"] != ""){
											$po_delivery_dt = date('m/d/Y', strtotime($row1["po_delivery_dt"]));
										}	
										
										?>
										<tr vAlign="center"  class="<?=$rowclr?>">
											<? 
											$nickname = "";
											
											if (($_REQUEST['dView'] == 2) && ($child_comp_new != "")) { 
												db_b2b();
												$sql1 = "SELECT ID,nickname,assignedto, company, shipCity, shipState FROM companyInfo where loopid = '" . $row1["warehouse_id"] . "'";
												
												$result_comp = db_query($sql1, db_b2b() );
												while ($row_comp = array_shift($result_comp)) {
													if ($row_comp["nickname"] != ""){
														$nickname = $row_comp["nickname"];
													}else{
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
													$comp_id=$row_comp["ID"];
													$total_loc[] = $comp_id;
													
													$arr = explode(",", $row_comp["assignedto"]);
														
													db();
												}
												
												?>
												<td class="style3"  align="center">	
													<? echo $nickname; ?>
												</td>
											<? } ?>
											
											<td class="style3"  align="center">	
												<? echo $row1["id"]; ?>
											</td>
											
											<? if ($section_lastship_col1_flg == "yes") { 
											?>
											<td class="style3"  align="center">	
												<? echo date('m/d/Y', strtotime($row1["start_date"])); ?>
											</td>
											<? } ?>
											<? if ($section_lastship_col2_flg == "yes") {
											?>
											<td class="style3"  align="center">	
												<?	
													if (($row1["ignore"] == 1)) { Echo "Cancelled"; $status ="Cancelled";}
													elseif ($invoice_paid == 1)
													{		
														echo "Paid"; $status ="Paid";
													} elseif ($invoice_entered == 1) { Echo "Invoice sent"; $status ="Invoice sent";
													} elseif ($signed_customer_bol == 1) { Echo "Customer Signed BOL"; $status ="Customer Signed BOL";
													} elseif ($courtesy_followup == 1) { Echo "Courtesy Followup Made"; $status ="Courtesy Followup Made";
													} elseif ($delivered == 1) { Echo "Delivered"; $status ="Delivered";
													} elseif ($signed_driver_bol == 1) { Echo "Shipped - Driver Signed"; $status ="Shipped - Driver Signed";
													} elseif ($shipped == 1) { Echo "Shipped"; $status ="Shipped";
													} elseif ($bol_received == 1) { Echo "BOL @ Warehouse"; $status ="BOL @ Warehouse";
													} elseif ($bol_sent == 1) { Echo "BOL Sent to Warehouse"; $status ="BOL Sent to Warehouse";
													} elseif ($bol_created == 1) { Echo "BOL Created"; $status ="BOL Created";
													} elseif ($freight_booked == 1) { Echo "Freight Booked"; $status ="Freight Booked";
													} elseif ($sales_order == 1) { Echo "Sales Order Entered"; $status ="Sales Order Entered";
													} elseif ($po_uploaded == 1) { Echo "PO Uploaded"; $status ="PO Uploaded";
													} elseif ($cntPOTable > 0 ) { Echo "Order Entered"; $status ="Order Entered";
													} elseif ($cntPOTable > 0 && $row1["Preorder"] == 1 ) { Echo "Pre-order"; $status ="Pre-order";
													} elseif ($cntPOTable > 0 && $row1["Preorder"] == 0 ) { Echo "Accumulating Inventory"; $status ="Accumulating Inventory";
													} elseif ($row1["good_to_ship"] == 1 ) { Echo "Arranging Pickup"; $status ="Arranging Pickup";
													}
												?>					
											</td>
											<? } ?>
											<? 
											if ($section_lastship_col3_flg == "yes") { 
												?>
												<td class="style3"  align="center" id="po_order_show<?=$tmpcnt;?>">	
													<a href="javascript:void(0);" onclick="display_file('https://loops.usedcardboardboxes.com/po/<?=str_replace(" ", "%20", $row1["po_file"]);?>', 'Purchase order',<?=$tmpcnt;?>)"><font color="blue"><u><? echo "&nbsp;" . $row1["po_ponumber"]; ?></u></font></a>
												</td>
											<? $purchase_order = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_file('https://loops.usedcardboardboxes.com/po/" . str_replace(" ", "%20", $row1["po_file"]) . "', 'Purchase order'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>&nbsp;" . $row1["po_ponumber"] . "</u></font></a>";
											} ?>

											<? if ($section_lastship_col7_flg == "yes") { 
											?>
											<td class="style3" align="center" id="bol_show<?=$tmpcnt;?>">	
												<? 
													$bol_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id = '" . $row1["id"] . "' ORDER BY id DESC";
													$bol_view_res = db_query($bol_view_qry, db() );
													while ($bol_view_row = array_shift($bol_view_res)) {					
														if ($bol_view_row["trans_rec_id"] != '') {
														?>
															<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?=str_replace(" ", "%20", $bol_view_row["file_name"]);?>', 'BOL',<?=$tmpcnt;?>)"><font color="blue"><u><? echo $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"]; ?></u></font></a>
														<? 
															$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"] . "</u></font></a>";
														} else {
														?>
															<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?=str_replace(" ", "%20", $bol_view_row["file_name"]);?>', 'BOL',<?=$tmpcnt;?>)"><font color="blue"><u>View BOL</u></font></a>
														<?
															$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View BOL</u></font></a>";
														}
													} 
												?>
												</td>
												
												<td class="style3"  align="center">
													<? echo $original_planned_delivery_dt; ?>
												</td>
												<td class="style3"  align="center">
													<? echo $po_delivery_dt; ?>
												</td>
												
											<? } ?>
											
											<? if ($section_lastship_col4_flg == "yes") { 
											?>
												<td class="style3"  align="center">
													<? //if ($row["B"] > 0)  echo date('m-d-Y', strtotime($row["B"])); ?>
													<? if ($bol_file_row["bol_shipped"] > 0)  echo date('m/d/Y', strtotime($bol_file_row["bol_shipped_date"])); ?>
												</td>
											<? } ?>

											<? if ($section_lastship_col4_flg == "yes") { 
											?>
												<td class="style3"  align="center">
													<? if ($bol_file_row['bol_shipment_received_date'] > 0)  echo date('m/d/Y', strtotime($bol_file_row['bol_shipment_received_date'])); ?>
												</td>
											<? } ?>
											<? if ($section_lastship_col5_flg == "yes") { 
											?>
												<td class="style3"  align="center">	
													<? if ($row["A"] > 0)   echo number_format($row["A"],0); ?>
												</td>
											<? } ?>

											<? if ($section_lastship_col6_flg == "yes") { ?>
												<td class="style3"  align="center" id="inv_file_show<?=$tmpcnt;?>">	
													<? if ($row1["inv_file"] > 0)   { 
														if ($row1["inv_number"] != '')   { 
														?>
														<a href="javascript:void(0);" onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?=str_replace(" ", "%20", $row1["inv_file"]);?>', 'Invoice',<?=$tmpcnt;?>)"><font color="blue"><u><? echo $row1["inv_number"]; ?></u></font></a>
													<? 
														$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $row1["inv_number"] . "</u></font></a>";
														} else {
														?>
															<a href="javascript:void(0);"  onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?=str_replace(" ", "%20", $row1["inv_file"]);?>', 'Invoice',<?=$tmpcnt;?>)"><font color="blue"><u>View Invoice</u></font></a>
														<?
															$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View Invoice</u></font></a>";
														}
													} 
													?>
												</td>

												<td class="style3"  align="right">	
													<? if ($row1["inv_amount"] > 0) {
															echo "$" . number_format($row1["inv_amount"],2); 
														}
													?>
												</td>
											<? } ?>

											<? 
												$invoice_age_internal = ""; $invoice_age_color = "";
												if ($section_lastship_col8_flg == "yes") {
													if ($invoice_age > 30 && $invoice_age < 1000) 
													{
														if ($invoice_paid == 1) {
															$invoice_age_internal = "";
															$invoice_age_color = "#e4e4e4";
												?>
														<td class="style3" align="center" >&nbsp;
															
														</td>
												<?
													 }else{ $invoice_age_internal = $invoice_age;
															$invoice_age_color = "#ff0000";
															?>
														<td class="style3" align="center" bgcolor="<? echo $invoice_age_color?>">
															<?  echo $invoice_age; ?>
														</td>
													 <?}
													} elseif (number_format(($end_time-$start_t)/(3600*24000),0) > 10)
													{
														$invoice_age_internal = "";
														$invoice_age_color = "#e4e4e4";
												?>
														<td class="style3" align="center" >&nbsp;
															
														</td>
												<?
													} else
													{
														if ($invoice_paid == 1) {
															$invoice_age_internal = "";
															$invoice_age_color = "#e4e4e4";
												?>
														<td class="style3" align="center" >&nbsp;
															
														</td>
													<? }else { 
														$invoice_age_internal = $invoice_age;
														$invoice_age_color = "#e4e4e4";
														?>
														<td class="style3" align="center">
															<?  echo $invoice_age; ?>
														</td>
												<?
													  }
													} 
												}
											?>				
											
										</tr>

										<?
										$report_total_loc=count(array_unique($total_loc));
										$tmpcnt = $tmpcnt + 1;
									
										$MGArray_current_order[] = array( 'inv_amount' => $row1["inv_amount"], 'accountowner' => $accountowner, 
										'dt_submitted_sort' => $dt_submitted_sort, 'dt_shipped_sort' => $dt_shipped_sort, 'dt_delv_sort' => $dt_delv_sort, 'purchase_order' => $purchase_order, 'viewbol' => $viewbol, 'invoice' => $invoice, 'company' => $nickname, 'dt_submitted' => $dt_submitted , 'status' => $status, 'dt_shipped' => $dt_shipped , 
										'original_planned_delivery_dt' => $original_planned_delivery_dt, 'po_delivery_dt' => $po_delivery_dt,
										'dt_delv' => $dt_delv , 'boxes' => $boxes, 'boxes_sort' => $boxes_sort, 'invoice_age' => $invoice_age_internal, 'invoice_age_color' => $invoice_age_color, 'total_trans' => $total_trans, 'report_total_loc' => $report_total_loc, 'comp_id' =>$comp_id, 'order_id' => $row1["id"]);
										//print_r($MGArray)."<br>";
									}
								}
								$i++;
							}
							if ($section_lastship_col6_flg == "yes") {
								if ($tot_inv_amount > 0 ){?>
								<tr><td bgColor="#e4e4e4" colspan="<? echo $col_cnt_tmp+1; ?>" class="style3" align="right"><b>Total Amount:</b>&nbsp;</td><td bgColor="#e4e4e4" class="style3" align="right"><strong><? echo "$" . number_format($tot_inv_amount,2); ?></strong></td><td bgColor="#e4e4e4">&nbsp;</td></tr>
								
								<?	}
								if ($child_comp != "") { 
									if($total_trans>0)
									{
										?>
										<tr>
											<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
											<?
												echo "<font size='1'><b>Total Number of Locations: ".$report_total_loc."";
											?>
											</td>
											<td bgColor="#e4e4e4">&nbsp;</td>
										</tr>
										<tr>
											<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
												<?
												echo "<font size='1'><b>Total Number of Transactions: ".$total_trans."";
												?>
											</td>
											<td bgColor="#e4e4e4">&nbsp;</td>
										</tr>
										<?
									}
								}
							}
							$_SESSION['sortarrayn_dsh_curr_ord'] = $MGArray_current_order; 
						}
						?>					
						<!-- Current Order-->

						<tr ><td colspan="13">&nbsp;</td></tr>
					<? //} ?>
					
					<tr class="headrow"><td colspan="14">
						<? if (isset($_REQUEST["vs_start_date"])){
							?>
							Order history (Showing for <? echo $_REQUEST["vs_start_date"];?> To <? echo $_REQUEST["vs_end_date"];?>)
						<? } else{?>
							Order history (Showing last 25 transaction)
						<? }?>	
					</td></tr>

					<tr class="headrow">
						<?  $col_cnt_tmp = 0; 	?>
					    <?
					    if ($child_comp_new != "" && ($_REQUEST['dView'] == 2)) { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
							?>
							<td class="blackFont" align="center">
								<? if (isset($_REQUEST["vs_start_date"])){?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=location&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Location</a>
								<? } else{?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=location&sort_order=<?=$sort_order;?>&sorting_ship=yes">Location</a>
								<? } ?>
							</td>
						<? } ?>
					   	<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=order_id&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Order ID</a>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=order_id&sort_order=<?=$sort_order;?>&sorting_ship=yes">Order ID</a>
							<? } ?>
						</td>

			 		   	<? if ($section_lastship_col1_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
						<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=datesubmit&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Date Submitted</a>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=datesubmit&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Submitted</a>
							<? } ?>
						</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col2_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
						<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=status&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Status</a>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=status&sort_order=<?=$sort_order;?>&sorting_ship=yes">Status</a>
							<? }
							?>
							<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
								<span class="tooltiptext">
									Cancelled <br />Invoice sent<br />Customer Signed BOL<br />Courtesy Followup Made<br/>Delivered<br/>Shipped - Driver Signed<br/>Shipped</br/>BOL @ Warehouse<br/>BOL Sent to Warehouse<br />BOL Created<br />Freight Booked<br />Sales Order Entered<br />PO Uploaded<br/> Order Entered - UPLOAD PO TABLE <br /> Pre-order - UPLOAD PO + Marked as Preorder <br /> Accumulating Inventory - UPLOAD PO + Unmarked as Preorder <br /> Arranging Pickup - Ready to hand to freight manager
								</span>
							</div>
						</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col3_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
							<td class="blackFont" align="center">
								Purchase Order
							</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col7_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
							<td align="middle" style="width: 70px" class="blackFont" align="center">
								View BOL
							</td>

							<td class="blackFont" align="center">
								<? if (isset($_REQUEST["vs_start_date"])){?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&original_planned_delivery_dt=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Original Planned Delivery Date</a>
								<? } else{?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&original_planned_delivery_dt=yes">Original Planned Delivery Date</a>
								<? } ?>
							</td>

							<td class="blackFont" align="center">
								<? if (isset($_REQUEST["vs_start_date"])){?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&curr_planned_del=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Current Planned Delivery Date</a>
								<? } else{?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&curr_planned_del=yes">Current Planned Delivery Date</a>
								<? } ?>
							</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col4_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 3;
					   	?>
							<td class="blackFont" align="center">
								<? if (isset($_REQUEST["vs_start_date"])){?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Date Shipped</a>
								<? } else{?>
									<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=dateshipped&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Shipped</a>
								<? } ?>
							</td>
			 		   	<? } ?>
					   	<? if ($section_lastship_col4_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
						<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=datedeliver&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Date Delivered</a>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=datedeliver&sort_order=<?=$sort_order;?>&sorting_ship=yes">Date Delivered</a>
							<? } ?>
						</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col5_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
						<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=boxes&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Quantity</a>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=boxes&sort_order=<?=$sort_order;?>&sorting_ship=yes">Quantity</a></b>
							<? } ?>
						</td>
			 		   	<? } ?>

			 		   	<? if ($section_lastship_col6_flg == "yes") { 
							$col_cnt_tmp = $col_cnt_tmp + 1;
					   	?>
						<td class="blackFont" align="center">
							Invoice
						</td>
						<td class="blackFont" align="center">
							<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=invamt&sort_order=<?=$sort_order;?>&sorting_ship=yes">Amount</a>
						</td>
			 		   	<? } ?>
			 		   	<? if ($section_lastship_col8_flg == "yes") {  ?>
						<td class="blackFont" align="center">
							<? if (isset($_REQUEST["vs_start_date"])){?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=invage&sort_order=<?=$sort_order;?>&sorting_ship=yes&vs_start_date=<? echo $_REQUEST["vs_start_date"];?>&vs_end_date=<? echo $_REQUEST["vs_end_date"];?>">Invoice Age</a></b>
							<? } else{?>
								<a href="client_dashboard_new.php?compnewid=<?=$_REQUEST['compnewid'];?>&show=history&repchk=<?=$_REQUEST["repchk"];?>&sort=invage&sort_order=<?=$sort_order;?>&sorting_ship=yes">Invoice Age</a>
							<? } ?>
						</td>
			 		   	<? } ?>
					</tr>
					<?
					$todaysDate = date('Y-m-d');
					$toDate 	= date("Y-m-d", strtotime('last day of previous month'));
					$fromDate 	= date("Y-m-01", strtotime("-12 months"));
					if($_REQUEST['sorting_ship']=="yes"){
						$sort_order_arrtxt = "SORT_ASC";
						if ($_REQUEST["sort_order"] != "") {
							if ($_REQUEST["sort_order"] == "ASC") {
								$sort_order_arrtxt = "SORT_ASC";
							} else {
								$sort_order_arrtxt = "SORT_DESC";
							}
						} else {
							$sort_order_arrtxt = "SORT_DESC";
						}
					
						$MGArray = $_SESSION['sortarrayn_dsh'];
						if($_REQUEST["sort"] == "order_id")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['order_id'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}

						if($_REQUEST["sort"] == "location")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['company'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						
						if($_REQUEST["sort"] == "datesubmit")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['dt_submitted_sort'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
								
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
								
							}
						}
						if($_REQUEST["sort"] == "accout_manager")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['accountowner'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						
						if($_REQUEST["sort"] == "status")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['status'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						if($_REQUEST["sort"] == "dateshipped")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['dt_shipped_sort'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						if($_REQUEST["sort"] == "datedeliver")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['dt_delv_sort'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						if($_REQUEST["sort"] == "invamt")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['inv_amount'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_NUMERIC,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_NUMERIC,$MGArray); 
							}
						}
						if($_REQUEST["sort"] == "boxes")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['boxes_sort'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						if($_REQUEST["sort"] == "invage")
						{
							foreach ($MGArray as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['invoice_age'];
							}
							if ($sort_order_arrtxt == "SORT_ASC") {
								array_multisort($MGArraysort,SORT_ASC,SORT_STRING,$MGArray); 
							}else{
								array_multisort($MGArraysort,SORT_DESC,SORT_STRING,$MGArray); 
							}
						}
						$i = 0;
						foreach ($MGArray as $MGArraytmp2) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							$total_loc[] = $MGArraytmp2["comp_id"];
							?>
							<tr vAlign="center" class="<?=$rowclr?>">
								<? if ($child_comp != "") { 
								?>
								<td class="style3"  align="center">	
									<? echo $MGArraytmp2["company"]; ?>
								</td>
								<? } ?>
								<td class="style3"  align="center">	
									<? echo $MGArraytmp2["order_id"]; ?>
								</td>
								<? if ($section_lastship_col1_flg == "yes") { ?>
								<td class="style3"  align="center">	
									<? echo $MGArraytmp2["dt_submitted"]; ?>
								</td>
								<? } ?>
								<? if ($section_lastship_col2_flg == "yes") { ?>
								<td class="style3"  align="center">	
									<? echo $MGArraytmp2["status"]; ?>
								</td>
								<? } ?>
								<? 
								if ($section_lastship_col3_flg == "yes") { ?>
									<td class="style3"  align="center" id="po_order_show<?=$tmpcnt;?>">	
										<? echo $MGArraytmp2["purchase_order"]; ?>
									</td>
								<? } ?>

								<? if ($section_lastship_col7_flg == "yes") { ?>
									<td class="style3" align="center" id="bol_show<?=$tmpcnt;?>">	
										<? echo $MGArraytmp2["viewbol"]; ?>
									</td>
									<td class="style3"  align="center">
										<? echo $MGArraytmp2["original_planned_delivery_dt"]; ?>
									</td>
									<td class="style3"  align="center">
										<? echo $MGArraytmp2["po_delivery_dt"]; ?>
									</td>
								<? } ?>
								
								<? if ($section_lastship_col4_flg == "yes") { ?>
									<td class="style3"  align="center">
										<? echo $MGArraytmp2["dt_shipped"]; ?>
									</td>
								<? } ?>

								<? if ($section_lastship_col4_flg == "yes") { ?>
									<td class="style3"  align="center">
										<? echo $MGArraytmp2["dt_delv"]; ?>
									</td>
								<? } ?>
								<? if ($section_lastship_col5_flg == "yes") { ?>
								<td class="style3"  align="center">	
									<? echo $MGArraytmp2["boxes"]; ?>
								</td>
								<? } ?>

								<? if ($section_lastship_col6_flg == "yes") { ?>
								<td class="style3"  align="center" id="inv_file_show<?=$tmpcnt;?>">	
									<? echo $MGArraytmp2["invoice"]; ?>
								</td>
								<td class="style3"  align="right">	
									<? echo number_format($MGArraytmp2["inv_amount"],2); ?>
								</td>
								<? } ?>

								<? if ($section_lastship_col8_flg == "yes") { ?>
									<td bgColor="<? echo $MGArraytmp2["invoice_age_color"];?>" class="style3" align="center" >
										<?  echo $invoice_age; ?>
									</td>
								 <?
									}

									$tot_inv_amount = $tot_inv_amount + $MGArraytmp2["inv_amount"];			
									$total_trans=$total_trans +1;			
								?>				
								
							</tr>
							<?
							$report_total_loc=count(array_unique($total_loc));
							$tmpcnt = $tmpcnt + 1;
							$i++;
						}
						if ($section_lastship_col6_flg == "yes") {
							if ($tot_inv_amount > 0 ){?>
							<tr  class="rowdata" >
								<td bgColor="#e4e4e4" colspan="<? echo $col_cnt_tmp+1; ?>" class="style3" align="right"><b>Total Amount:</b>&nbsp;</td><td bgColor="#e4e4e4" class="style3" align="right"><strong><? echo "$" . number_format($tot_inv_amount,2); ?></strong></td><td bgColor="#e4e4e4">&nbsp;</td></tr>
							<?	
							}
							if ($child_comp != "") { 
								if($total_trans>0){
								?>
								<tr  class="rowdata">
									<td bgColor="#e4e4e4" colspan="12" class="style3" align="right">
									<?
										echo "<font size='1'><b>Total Number of Locations: ".$report_total_loc."";
									?>
									</td>
									<td bgColor="#e4e4e4">&nbsp;</td>
								</tr>
								<tr  class="rowdata">
									<td bgColor="#e4e4e4" colspan="12" class="style3" align="right">
										<?
										echo "<font size='1'><b>Total Number of Transactions: ".$total_trans."";
										?>
									</td>
									<td bgColor="#e4e4e4">&nbsp;</td>
								</tr>
								<?
								}
							}
						}
					}else{
						if (isset($_REQUEST["vs_start_date"])){
							if ($_REQUEST['dView'] == 2 && $child_comp_new != "") {
								$query1 = "SELECT loop_transaction_buyer.original_planned_delivery_dt , loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.id, no_invoice, inv_date_of, so_entered, po_date, start_date, loop_transaction_buyer.warehouse_id, `ignore`, good_to_ship, po_file, po_ponumber, inv_file, inv_number, inv_amount FROM loop_transaction_buyer inner join loop_bol_files ON loop_bol_files.trans_rec_id = loop_transaction_buyer.id 
								WHERE loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp_new . ") and date_format(str_to_date(bol_shipment_received_date, '%m/%d/%Y'), '%Y-%m-%d') BETWEEN '". date("Y-m-d" , strtotime($_REQUEST["vs_start_date"]))." 00:00:00' AND '". date("Y-m-d" , strtotime($_REQUEST["vs_end_date"])) ." 23:59:59' Group by loop_bol_files.trans_rec_id ORDER BY loop_transaction_buyer.warehouse_id, loop_transaction_buyer.id DESC";
							}else{
								$query1 = "SELECT loop_transaction_buyer.original_planned_delivery_dt , loop_transaction_buyer.po_delivery_dt , loop_transaction_buyer.id, no_invoice, inv_date_of, so_entered, po_date, start_date, loop_transaction_buyer.warehouse_id, `ignore`, good_to_ship, po_file, po_ponumber, inv_file, inv_number, inv_amount FROM loop_transaction_buyer inner join loop_bol_files ON loop_bol_files.trans_rec_id = loop_transaction_buyer.id 
								WHERE loop_transaction_buyer.warehouse_id = $client_loopid and date_format(str_to_date(bol_shipment_received_date, '%m/%d/%Y'), '%Y-%m-%d') BETWEEN '". date("Y-m-d" , strtotime($_REQUEST["vs_start_date"]))." 00:00:00' AND '". date("Y-m-d" , strtotime($_REQUEST["vs_end_date"])) ." 23:59:59' Group by loop_bol_files.trans_rec_id ORDER BY loop_transaction_buyer.id DESC";
							}								
						}else{
							if ($_REQUEST['dView'] == 2 && $child_comp_new != "") {
								$query1 = "SELECT loop_transaction_buyer.original_planned_delivery_dt , loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.id, loop_transaction_buyer.inv_amount FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id in ( " . $client_loopid . "," . $child_comp_new . ") AND loop_transaction_buyer.invoice_paid = 1 and loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59'  ORDER BY warehouse_id asc, id DESC LIMIT 25";
							}else{
								//$query1 = "SELECT * FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id = $client_loopid AND loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' ORDER BY id DESC LIMIT 25";
								$query1 = "SELECT * FROM loop_transaction_buyer WHERE loop_transaction_buyer.warehouse_id = $client_loopid AND loop_transaction_buyer.invoice_paid = 1 and loop_transaction_buyer.transaction_date BETWEEN '".$fromDate." 00:00:00' AND '".$toDate." 23:59:59' ORDER BY id DESC LIMIT 25";
							}
						}
						//echo "<br /> query1 - ".$query1;
						$res1 = db_query($query1, db());
						//echo "<pre> res1 - ";  print_r($res1); echo "</pre>";
						$tmpcnt=0; $tot_inv_amount = 0; $total_trans=0;
						$i = 0;
						while($row1 = array_shift($res1)) {
							if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
							if (isset($_REQUEST["vs_start_date"])){
								$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C FROM loop_bol_tracking WHERE trans_rec_id = ". $row1["id"] . " and (STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') >= '" . date("Y-m-d" , strtotime($_REQUEST["vs_start_date"])) . "' and STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') <= '" . date("Y-m-d" , strtotime($_REQUEST["vs_end_date"])). "') ORDER BY C DESC ";
								//GROUP BY trans_rec_id
							}else {
								$query = "SELECT SUM( loop_bol_tracking.qty ) AS A, loop_bol_tracking.bol_pickupdate AS B, loop_bol_tracking.trans_rec_id AS C FROM loop_bol_tracking WHERE trans_rec_id = ". $row1["id"] . " AND (STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') >= '" . $fromDate . "' and STR_TO_DATE(loop_bol_tracking.bol_pickupdate, '%m/%d/%Y') <= '" . $toDate . "') ORDER BY C  DESC";
							}
							//echo "<br /> query - ".$query;	
							$res = db_query($query);
							//echo "<pre> res - ";  print_r($res); echo "</pre>";
							while($row = array_shift($res)){
								//DATE SHIPPED FETCH START
								$resShippedDate = db_query("SELECT bol_shipped, bol_shipped_date FROM loop_bol_files WHERE trans_rec_id = ".$row1["id"], db());
								//DATE SHIPPED FETCH END
								//This is the payment Info for the Customer paying UCB
								$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $row1["id"];
								$payment_qry = db_query($payments_sql );
								$payment = array_shift($payment_qry);
								
								$tot_inv_amount = $tot_inv_amount + $row1["inv_amount"];
								$total_trans=$total_trans +1;
								//This is the payment info for UCB paying the related vendors
								$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row1["id"];
								$vendor_qry = db_query($vendor_sql );
								$vendor = array_shift($vendor_qry);

								//Info about Shipment
								$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $row1["id"] . "' ORDER BY id DESC";
								//echo $bol_file_qry ;
								$bol_file_res = db_query($bol_file_qry );
								$bol_file_row = array_shift($bol_file_res);

								/*GET UPLOAD PO TABLE INFO*/
								$qryPOTable = "SELECT * FROM loop_transaction_buyer_poeml WHERE trans_rec_id LIKE '" . $row1["id"] . "' ORDER BY unqid DESC";
								//echo $bol_file_qry ;
								$resPOTable = db_query($qryPOTable);
								$cntPOTable = tep_db_num_rows($resPOTable);
								/*GET UPLOAD PO TABLE INFO*/

								$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $row1["id"];
								$fbookresult = db_query($fbooksql );
								$freightbooking = array_shift($fbookresult);

								$vendors_paid = 0; //Are the vendors paid
								$vendors_entered = 0; //Has a vendor transaction been entered?
								$invoice_paid = 0; //Have they paid their invoice?
								$invoice_entered=0; //Has the inovice been entered
								$signed_customer_bol= 0; 	//Customer Signed BOL Uploaded
								$courtesy_followup = 0; 	//Courtesy Follow Up Made
								$delivered = 0; 	//Delivered
								$signed_driver_bol = 0; 	//BOL Signed By Driver
								$shipped = 0; 	//Shipped
								$bol_received = 0; 	//BOL Received @ WH
								$bol_sent = 0; 	//BOL Sent to WH"
								$bol_created = 0; 	//BOL Created
								$freight_booked = 0; //freight booked
								$sales_order = 0;   // Sales Order entered
								$po_uploaded = 0;  //po uploaded 
				 
								//Are all the vendors paid?
								if ($vendor["B"] == 2 && $vendor["C"] == 2) 
								{ 
									$vendors_paid = 1; 
								}
								
								//Have we entered a vendor transaction?
								if ($vendor["A"] > 0) { 
									$vendors_entered = 1; 
								} 		

								//Have they paid their invoice?
								if (number_format($row1["inv_amount"],2) == number_format($payment["A"],2) && $row1["inv_amount"] != "") 
								{ 
									$invoice_paid = 1; 
								} 
								if ($row1["no_invoice"] == 1) {				$invoice_paid = 1; 			}

								//Has an invoice amount been entered?
								if ($row1["inv_amount"] > 0) { 
									$invoice_entered=1; 
								} 

								if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") { $signed_customer_bol=1; }	//Customer Signed BOL Uploaded
								if ($bol_file_row["bol_shipment_followup"] >0) { $courtesy_followup = 1; }	//Courtesy Follow Up Made
								if ($bol_file_row["bol_shipment_received"] >0) { $delivered=1; }	//Delivered
								if ($bol_file_row["bol_signed_file_name"]!= "") { $signed_driver_bol=1; }	//BOL Signed By Driver
								if ($bol_file_row["bol_shipped"] >0) { $shipped = 1; }	//Shipped
								if ($bol_file_row["bol_received"] >0) { $bol_received = 1; }	//BOL Received @ WH
								if ($bol_file_row["bol_sent"] > 0) { $bol_sent = 1; }	//BOL Sent to WH"
								if ($bol_file_row["id"] > 0) { $bol_created=1; }	//BOL Created

								if ($freightbooking["id"] > 0 ) { $freight_booked = 1;} //freight booked
								
								$start_t = strtotime($row1["inv_date_of"]);
								$end_time =  strtotime(now);
								$invoice_age = number_format(($end_time-$start_t)/(3600*24),0);		
								
								if (($row1["so_entered"] == 1)) {	 $sales_order = 1; } //sales order created
								if ($row1["po_date"] != "") { $po_uploaded = 1; } //po uploaded 			
								
								$nn =""; $dt_submitted = ""; $status = "";
								$dt_submitted_sort = ""; $dt_shipped_sort = ""; $dt_delv_sort = "";
								
								$dt_submitted = date('m-d-Y', strtotime($row1["start_date"]));
								$dt_submitted_sort = date('Y-m-d', strtotime($row1["start_date"]));
								
								$dt_shipped = ""; $dt_delv = ""; $boxes = ""; $inv_age = ""; $purchase_order = ""; $viewbol = ""; $invoice ="";
								if ($row["B"] > 0)  { $dt_shipped = date('m-d-Y', strtotime($row["B"])); $dt_shipped_sort = date('Y-m-d', strtotime($row["B"]));}
								if ($bol_file_row['bol_shipment_received_date'] > 0)  { $dt_delv = date('m-d-Y', strtotime($bol_file_row['bol_shipment_received_date'])); $dt_delv_sort = date('Y-m-d', strtotime($bol_file_row['bol_shipment_received_date']));}
								if ($row["A"] > 0)   { $boxes = number_format($row["A"],0); $boxes_sort = $row["A"]; } 
								
								$original_planned_delivery_dt = ""; $po_delivery_dt = "";
								if ($row1["original_planned_delivery_dt"] != ""){
									$original_planned_delivery_dt = date('m/d/Y', strtotime($row1["original_planned_delivery_dt"]));
								}	
								if ($row1["po_delivery_dt"] != ""){
									$po_delivery_dt = date('m/d/Y', strtotime($row1["po_delivery_dt"]));
								}	
								
								?>
								<tr vAlign="center"  class="<?=$rowclr?>">
									<? 
									$nickname = "";
									
									if (($child_comp_new != "") && ($_REQUEST['dView'] == 2) )  { 
										db_b2b();
										$sql1 = "SELECT ID,nickname,assignedto, company, shipCity, shipState FROM companyInfo where loopid = '" . $row1["warehouse_id"] . "'";
										
										$result_comp = db_query($sql1, db_b2b() );
										while ($row_comp = array_shift($result_comp)) {
											if ($row_comp["nickname"] != ""){
												$nickname = $row_comp["nickname"];
											}else{
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
											$comp_id=$row_comp["ID"];
											$total_loc[] = $comp_id;
											
											$arr = explode(",", $row_comp["assignedto"]);

											db();
										}
										
									?>
										<td class="style3"  align="center">	
											<? echo $nickname; ?>
										</td>
									<? } ?>
									<td class="style3"  align="center">	
										<? echo $row1["id"]; ?>
									</td>
									
									<? if ($section_lastship_col1_flg == "yes") { 
									?>
									<td class="style3"  align="center">	
										<? echo date('m/d/Y', strtotime($row1["start_date"])); ?>
									</td>
									<? } ?>
									<? if ($section_lastship_col2_flg == "yes") {
									?>
									<td class="style3"  align="center">	
										<?	
											if (($row1["ignore"] == 1)) { Echo "Cancelled"; $status ="Cancelled";}
											elseif ($invoice_paid == 1)
											{		
												echo "Paid"; $status ="Paid";
											} elseif ($invoice_entered == 1) { Echo "Invoice sent"; $status ="Invoice sent";
											} elseif ($signed_customer_bol == 1) { Echo "Customer Signed BOL"; $status ="Customer Signed BOL";
											} elseif ($courtesy_followup == 1) { Echo "Courtesy Followup Made"; $status ="Courtesy Followup Made";
											} elseif ($delivered == 1) { Echo "Delivered"; $status ="Delivered";
											} elseif ($signed_driver_bol == 1) { Echo "Shipped - Driver Signed"; $status ="Shipped - Driver Signed";
											} elseif ($shipped == 1) { Echo "Shipped"; $status ="Shipped";
											} elseif ($bol_received == 1) { Echo "BOL @ Warehouse"; $status ="BOL @ Warehouse";
											} elseif ($bol_sent == 1) { Echo "BOL Sent to Warehouse"; $status ="BOL Sent to Warehouse";
											} elseif ($bol_created == 1) { Echo "BOL Created"; $status ="BOL Created";
											} elseif ($freight_booked == 1) { Echo "Freight Booked"; $status ="Freight Booked";
											} elseif ($sales_order == 1) { Echo "Sales Order Entered"; $status ="Sales Order Entered";
											} elseif ($po_uploaded == 1) { Echo "PO Uploaded"; $status ="PO Uploaded";
											} elseif ($cntPOTable > 0 ) { Echo "Order Entered"; $status ="Order Entered";
											} elseif ($cntPOTable > 0 && $row1["Preorder"] == 1 ) { Echo "Pre-order"; $status ="Pre-order";
											} elseif ($cntPOTable > 0 && $row1["Preorder"] == 0 ) { Echo "Accumulating Inventory"; $status ="Accumulating Inventory";
											} elseif ($row1["good_to_ship"] == 1 ) { Echo "Arranging Pickup"; $status ="Arranging Pickup";
											}
			 							?>					
									</td>
									<? } ?>
									
									<? 
									if ($section_lastship_col3_flg == "yes") { 
										?>
										<td class="style3"  align="center" id="po_order_show<?=$tmpcnt;?>">	
											<a href="javascript:void(0);" onclick="display_file('https://loops.usedcardboardboxes.com/po/<?=str_replace(" ", "%20", $row1["po_file"]);?>', 'Purchase order',<?=$tmpcnt;?>)"><font color="blue"><u><? echo "&nbsp;" . $row1["po_ponumber"]; ?></u></font></a>
										</td>
										<? $purchase_order = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_file('https://loops.usedcardboardboxes.com/po/" . str_replace(" ", "%20", $row1["po_file"]) . "', 'Purchase order'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>&nbsp;" . $row1["po_ponumber"] . "</u></font></a>";
									} ?>

									<? if ($section_lastship_col7_flg == "yes") { 
									?>
									<td class="style3" align="center" id="bol_show<?=$tmpcnt;?>">	
										<? 
											$bol_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id = '" . $row1["id"] . "' ORDER BY id DESC";
											$bol_view_res = db_query($bol_view_qry, db() );
											while ($bol_view_row = array_shift($bol_view_res)) {					
												if ($bol_view_row["trans_rec_id"] != '') {
												?>
													<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?=str_replace(" ", "%20", $bol_view_row["file_name"]);?>', 'BOL',<?=$tmpcnt;?>)"><font color="blue"><u><? echo $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"]; ?></u></font></a>
												<? 
													$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $bol_view_row["id"] . "-" . $bol_view_row["trans_rec_id"] . "</u></font></a>";
												} else {
												?>
													<a href="javascript:void(0);" onclick="display_bol_file('https://loops.usedcardboardboxes.com/bol/<?=str_replace(" ", "%20", $bol_view_row["file_name"]);?>', 'BOL',<?=$tmpcnt;?>)"><font color="blue"><u>View BOL</u></font></a>
												<?
													$viewbol = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_bol_file('https://loops.usedcardboardboxes.com/bol/" . str_replace(" ", "%20", $bol_view_row["file_name"]) . "', 'BOL'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View BOL</u></font></a>";
												}
											} 
										?>
										</td>
										
										<td class="style3"  align="center">
											<? echo $original_planned_delivery_dt; ?>
										</td>
										<td class="style3"  align="center">
											<? echo $po_delivery_dt; ?>
										</td>
									<? } ?>
									
									<? if ($section_lastship_col4_flg == "yes") { 
									
									?>
										<td class="style3"  align="center">
											<? //if ($row["B"] > 0)  echo date('m/d/Y', strtotime($row["B"])); ?>
											<? if ($bol_file_row["bol_shipped"] > 0)  echo date('m/d/Y', strtotime($bol_file_row["bol_shipped_date"])); ?>
										</td>
									<? } ?>

									<? if ($section_lastship_col4_flg == "yes") { 
									?>
										<td class="style3"  align="center">
											<? if ($bol_file_row['bol_shipment_received_date'] > 0)  echo date('m/d/Y', strtotime($bol_file_row['bol_shipment_received_date'])); ?>
										</td>
									<? } ?>
									<? if ($section_lastship_col5_flg == "yes") { 
									?>
									<td class="style3"  align="center">	
										<? if ($row["A"] > 0)   echo number_format($row["A"],0); ?>
									</td>
									<? } ?>

									<? if ($section_lastship_col6_flg == "yes") { ?>
										<td class="style3"  align="center" id="inv_file_show<?=$tmpcnt;?>">	
											<? if ($row1["inv_file"] > 0)   { 
												if ($row1["inv_number"] != '')   { 
												?>
												<a href="javascript:void(0);" onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?=str_replace(" ", "%20", $row1["inv_file"]);?>', 'Invoice',<?=$tmpcnt;?>)"><font color="blue"><u><? echo $row1["inv_number"]; ?></u></font></a>
											<? 
												$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>" . $row1["inv_number"] . "</u></font></a>";
												} else {
												?>
													<a href="javascript:void(0);"  onclick="display_inv_file('https://loops.usedcardboardboxes.com/files/<?=str_replace(" ", "%20", $row1["inv_file"]);?>', 'Invoice',<?=$tmpcnt;?>)"><font color="blue"><u>View Invoice</u></font></a>
												<?
													$invoice = "<a href='javascript:void(0);' onclick=" . chr(34) . "display_inv_file('https://loops.usedcardboardboxes.com/files/" . str_replace(" ", "%20", $row1["inv_file"]) . "', 'Invoice'," . $tmpcnt . ")" . chr(34) . "><font color='blue'><u>View Invoice</u></font></a>";
												}
											} 
											?>
										</td>

										<td class="style3"  align="right">	
											<? if ($row1["inv_amount"] > 0) {
													echo "$" . number_format($row1["inv_amount"],2); 
												}
											?>
										</td>
									<? } ?>

									<? 
										$invoice_age_internal = ""; $invoice_age_color = "";
										if ($section_lastship_col8_flg == "yes") {
											if ($invoice_age > 30 && $invoice_age < 1000) 
											{
												if ($invoice_paid == 1) {
													$invoice_age_internal = "";
													$invoice_age_color = "#e4e4e4";
										?>
												<td class="style3" align="center" >&nbsp;
													
												</td>
										<?
											 }else{ $invoice_age_internal = $invoice_age;
													$invoice_age_color = "#ff0000";
													?>
												<td class="style3" align="center" bgcolor="<? echo $invoice_age_color?>">
													<?  echo $invoice_age; ?>
												</td>
											 <?}
											} elseif (number_format(($end_time-$start_t)/(3600*24000),0) > 10)
											{
												$invoice_age_internal = "";
												$invoice_age_color = "#e4e4e4";
										?>
												<td class="style3" align="center" >&nbsp;
													
												</td>
										<?
											} else
											{
												if ($invoice_paid == 1) {
													$invoice_age_internal = "";
													$invoice_age_color = "#e4e4e4";
										?>
												<td class="style3" align="center" >&nbsp;
													
												</td>
											<? }else { 
												$invoice_age_internal = $invoice_age;
												$invoice_age_color = "#e4e4e4";
												?>
												<td class="style3" align="center" >
													<?  echo $invoice_age; ?>
												</td>
										<?
											  }
											} 
										}
									?>				
									
								</tr>

								<?
								$report_total_loc=count(array_unique($total_loc));
								$tmpcnt = $tmpcnt + 1;
							
								$MGArray[] = array( 'inv_amount' => $row1["inv_amount"], 'accountowner' => $accountowner, 'dt_submitted_sort' => $dt_submitted_sort, 'dt_shipped_sort' => $dt_shipped_sort, 'dt_delv_sort' => $dt_delv_sort, 
								'purchase_order' => $purchase_order, 'viewbol' => $viewbol, 'invoice' => $invoice, 'company' => $nickname, 'dt_submitted' => $dt_submitted , 'status' => $status, 
								'original_planned_delivery_dt' => $original_planned_delivery_dt, 'po_delivery_dt' => $po_delivery_dt, 
								'dt_shipped' => $dt_shipped , 'dt_delv' => $dt_delv , 'boxes' => $boxes, 'boxes_sort' => $boxes_sort, 'invoice_age' => $invoice_age_internal, 'invoice_age_color' => $invoice_age_color, 
								'total_trans' => $total_trans, 'report_total_loc' => $report_total_loc, 'comp_id' =>$comp_id, 'order_id' => $row1["id"]);
								//print_r($MGArray)."<br>";
						 	}
						 	$i++;
					 	}
						if ($section_lastship_col6_flg == "yes") {
							if ($tot_inv_amount > 0 ){?>
							<tr><td bgColor="#e4e4e4" colspan="<? echo $col_cnt_tmp+1; ?>" class="style3" align="right"><b>Total Amount:</b>&nbsp;</td><td bgColor="#e4e4e4" class="style3" align="right"><strong><? echo "$" . number_format($tot_inv_amount,2); ?></strong></td><td bgColor="#e4e4e4">&nbsp;</td></tr>
							
							<?	}
							if ($child_comp != "") { 
								if($total_trans>0)
								{
									?>
									<tr>
										<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
										<?
											echo "<font size='1'><b>Total Number of Locations: ".$report_total_loc."";
										?>
										</td>
										<td bgColor="#e4e4e4">&nbsp;</td>
									</tr>
									<tr>
										<td bgColor="#e4e4e4" colspan="11" class="style3" align="right">
											<?
											echo "<font size='1'><b>Total Number of Transactions: ".$total_trans."";
											?>
										</td>
										<td bgColor="#e4e4e4">&nbsp;</td>
									</tr>
									<?
								}
							}
						}
						$_SESSION['sortarrayn_dsh'] = $MGArray; 
					}
					?>
					
				</tbody>	
				</table>
			</td>
		</tr>
		<!-- <tr>
			<td align="center">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
				<tr class="headrow">
					<td colspan="6">ACCOUNT DETAILS</td>
				</tr>
				<?
				$resComp = db_query("SELECT loopid FROM companyInfo WHERE ID = '".$client_companyid . "'",db_b2b());
				$rowComp = array_shift($resComp);
				$dt_view_qry = "SELECT * FROM loop_warehouse WHERE id= '" . $rowComp['loopid'] ."' AND credit_application_file != ''";
				$dt_view_res = db_query($dt_view_qry,db() );
				$credit_file_found = "yes";
				if (tep_db_num_rows($dt_view_res) == 0){
					$credit_file_found = "no";
					$dt_view_qry = "SELECT * FROM loop_warehouse WHERE id= '" . $rowComp['loopid'] ."' AND credit_application_file != ''";
					$dt_view_res = db_query($dt_view_qry,db() );
				}
				//echo "<pre>"; print_r($dt_view_res); echo "</pre>";
				$termSetupCnt = tep_db_num_rows($dt_view_res);
				if($termSetupCnt > 0){
					?>
					<tr class="blackFont">
						<td colspan="2">Filename</td>
						<td colspan="2">User</td>
						<td colspan="2">Date</td>
					</tr>
					<?
					while ($dt_view_row = array_shift($dt_view_res)) {
						?>
						<tr>
							<td colspan="2"><a target="_blank" href="https://loops.usedcardboardboxes.com/credit_application/<? echo preg_replace( "/'/", "\'", $dt_view_row["credit_application_file"]) ; ?>" >		<? echo $dt_view_row["credit_application_file"]; ?></a>	</td>
							<td colspan="2"><? echo $dt_view_row["credit_application_by"]; ?></td>
							<td colspan="2"><? echo date('m/d/Y', strtotime($dt_view_row["credit_application_date"])); ?></td>
						</tr>
						<tr class="blackFont">
							<td>Net Term</td>
							<td>Credit Amount</td>
							<td>Notes</td>
							<td>Approve</td>
							<td>Approved By</td>
							<td>Approved On</td>
						</tr>
						<tr>
							<td><?=$dt_view_row["credit_application_net_term"];?></td>
							<td><?=$dt_view_row["credit_application_credit_amt"]; ?></td>
							<td><?=$dt_view_row["credit_application_notes"]; ?></td>
							<td>Approve</td>
							<td><?=$dt_view_row["credit_application_apprv_by"]; ?></td>
							<td><?=date('m/d/Y', strtotime($dt_view_row["credit_application_apprv_dt"])); ?></td>
						</tr>
						<?
					}
				}else{
					?>
					<tr>
						<td colspan="6">Want to apply for credit terms? Fill out our credit application and send it to <a href="mailto:CreditApproval@UsedCardboardBoxes.com">CreditApproval@UsedCardboardBoxes.com</a> </td>
					</tr>
					<?					
				}
				?>
				</tbody>
				</table>				
			</td>
		</tr> -->
	</table>
<? }else if($_REQUEST['show'] == 'change_password'){

	if (isset($_REQUEST["hd_chgpwd"])) {
		if ($_REQUEST["hd_chgpwd"] == "yes") {?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Change Password</h3></td>
		</tr>
		<tr >
		<td align="center">
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
			<tbody>
			<tr class="headrow">
				<td colspan="5" align="center">Enter your details
				</td>
			</tr>
			<tr>
				<td>
				<form name="frmchgpwd" action="client_dashboard_new.php?<?=$repchk_str?>" method="Post" >
					<table cellSpacing="1" cellPadding="1" border="0" width="100%">
					<tr vAlign="center" bgColor="#d4d4d4" >  
						<td width="15%">Old Password</td>	  
						<td width="85%"><input type="password" name="txt_oldpwd" id="txt_oldpwd" value=""/></td>	  
					</tr>
					<tr vAlign="center" bgColor="#c4c4c4">  
						<td>New Password</td>	  
						<td><input type="password" name="txt_newpwd" id="txt_newpwd" value=""/></td>	  
					</tr>
					<tr vAlign="center" bgColor="#d4d4d4">  
						<td>Re-type Password</td>	  
						<td><input type="password" name="txt_newpwd_re" id="txt_newpwd_re" value=""/></td>	  
					</tr>
					<tr>
						<td>
							<input type="hidden" name="hd_chgpwd_upd" id="hd_chgpwd_upd" value="yes"/>
							<input type="hidden" name="hd_chgpwd_val" id="hd_chgpwd_val" value="<? echo $client_pwd; ?>"/>
							<input type="hidden" name="compnewid_chgpwd" id="compnewid_chgpwd" value="<? echo $client_companyid; ?>"/>
						</td>
					  	<td align="left">
					  	<input type="button" name="btn_chgpwd" id="btn_chgpwd" onclick="chkchgpwd()" value="Update"/>
					  	</td>
					</tr>
					</table>
				</form>
				</td>
			</tr>
			</tbody>
			</table>
		</td>
		</tr>
	</table>
		<? }
	}
}else if($_REQUEST['show'] == 'sales_quotes'){  
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Sales quotes</h3></td>
		</tr>
		<tr >
		<td align="center">
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
				<tr class="headrow">
					<td colspan="6" align="center">QUOTES GENERATED</td>
				</tr>
				<tr class="style17">
					<td>UCB Quote #</td>
					<td>Quote Type</td>
					<td>PO #</td>
					<td>Quote Date</td>
					<td>Quote Amount</td>
					<td>Status</td>
				</tr>
				<?
				$quotes_archive_date = "";
				$query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
				$dt_view_res3 = db_query($query, db() );
				while ($objQuote= array_shift($dt_view_res3)) {
					$quotes_archive_date = $objQuote["variablevalue"];
				}
				$query = "SELECT * FROM quote WHERE companyID= '" . $client_companyid. "' ORDER BY ID DESC";
				$dtViewRes3 = db_query($query,db_b2b() );
				//echo "<pre>"; print_r($dtViewRes3); echo "</pre>";
				$rowno = 1;
				while ($objQuote= array_shift($dtViewRes3)) {
					if($rowno % 2 == 0){ $rowColor = '#dcdcdc'; }else{ $rowColor = '#f7f7f7'; }
					if (is_null($objQuote["quote_total"]) ) {
						$qtotalamt = 0;
					}else{
						$qtotalamt = $objQuote["quote_total"];
					}

					//here when we add quote, qstatus inserted 0 in quote thats why adding one more condition for open 
					if($objQuote["qstatus"] == 1 || $objQuote["qstatus"] == 11 || $objQuote["qstatus"] == 10 || $objQuote["qstatus"] == 0 ){
						$statusNm = 'Open';
						$orderStatus = 'yes';
						$fontColor = '';
						$bgColor = '#fbe800';
					}elseif($objQuote["qstatus"] == 8){
						$statusNm = 'Accepted';
						$orderStatus = 'no';
						$fontColor = '#008000';
						$bgColor = '';
					}elseif($objQuote["qstatus"] == 2){
						$statusNm = 'Requoted';
						$orderStatus = 'Requoted';
						$fontColor = '#008000';
						$bgColor = '';
					}else{
						$statusNm = 'Declined';
						$orderStatus = 'declined';
						$fontColor = '#f90219';
						$bgColor = '';
					}	
					?>
					<tr bgcolor="<?=$rowColor;?>">
						<td>
							<? $quoteid_rem = ($objQuote["ID"] + 3770);	 ?>
							<?// if($objQuote["qstatus"] == 1 || $objQuote["qstatus"] == 11 || $objQuote["qstatus"] == 10 || $objQuote["qstatus"] == 0 ){	?>
								<a href="https://b2bquote.usedcardboardboxes.com/index_quote.php?quote_id=<? echo urlencode(encrypt_password($objQuote["ID"] + 3770));?>&order_status=<?=$orderStatus?>" target="_blank"><?=($objQuote["ID"] + 3770);?></a>
							<? //}else {?>
								<? //echo ($objQuote["ID"] + 3770);?>
							<?// } ?>							
						</td>
						<td>
							<? 
							if ($objQuote["filename"] != "") {
								$archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
								$quote_date = new DateTime(date("Y-m-d", strtotime($objQuote["quoteDate"])));
								if ($quote_date < $archeive_date){
									if (file_exists("/home/usedcardboardbox/public_html/ucbloop/quotes/". $objQuote["filename"])){
										echo $objQuote["quoteType"];
									}else{
										echo $objQuote["quoteType"];
									}
								}else{
									echo $objQuote["quoteType"];
								}
							}else{
								if ($objQuote["quoteType"]=="Quote") {
									echo $objQuote["quoteType"];
								}elseif ($objQuote["quoteType"]=="Quote Select") {
									echo $objQuote["quoteType"];
								}else{
									echo $objQuote["quoteType"];
								}
							}
							?>
						</td>
						<td><?=$objQuote["poNumber"]?></td>
						<td><?=date('m/d/Y', strtotime($objQuote["quoteDate"]));?></td>
						<td>$<?=number_format($qtotalamt,2)?></td>
						<td style="color:<?=$fontColor;?>; background-color:<?=$bgColor?>"><?=$statusNm; ?> </td>
					</tr>
					<?
					$rowno++;
				}
				?>
				</tbody>
			</table>
		</td>
		</tr>
	</table>
	<? 
}else if($_REQUEST['show'] == 'inventory' && $show_boxprofile_inv == 'yes'){  
?>

	<iframe src="https://loops.usedcardboardboxes.com/B2Binventory_Gaylords_new.php?shown_in_client_flg=1&client_companyid=<? echo $client_companyid?>&client_loginid=<? echo $client_loginid;?>" name="ifrmgayloardplp" id="ifrmgayloardplp" frameborder="0" width="100%" height="800px"
	scrolling="auto" >
	
	<script>
		function ifrmgayloardplp_load() {
			ifrmaeobj = document.getElementById("ifrmgayloardplp");
			var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
			objheight = objheight + 30;

			ifrmaeobj.style.height = objheight + 'px';		

			var objwidth = ifrmaeobj.contentWindow.document.body.offsetWidth;
			objwidth = objwidth + 10;
			//ifrmaeobj.style.width  = objwidth + 'px';		
		}	
	
	</script>				
	
<?	
}else if($_REQUEST['show'] == 'tutorials'){  
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Tutorials</h3></td>
		</tr>
		<tr >
		<td align="center">

			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tr class="headrow">
					<td colspan="2" align="left">Tutorial videos</td>
				</tr>
				<tr class="headrow">
					<td align="center" width="50%"><b>Introduction page</b></td>
					<td align="center" width="50%"><b>Login page</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315"
						  id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/11nPIbgCfP8?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/11nPIbgCfP8/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" 
						  loading="lazy"
						 style="background-color: #000"></iframe>
					
					<td align="left">
						<iframe
						  width="540" height="315"
						  id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/efu9-RkVF3Q?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/efu9-RkVF3Q/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" 
						  loading="lazy"
						 style="background-color: #000"></iframe>
				</tr>
				<tr >
					<td colspan="2" align="left">&nbsp;</td>
				</tr>
			</table>	

			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tr class="headrow">
					<td align="center" width="50%"><b>Home Page</b></td>
					<td align="center" width="50%"><b>Company Profile</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/HTnTR6gOCro?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/HTnTR6gOCro/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/aVOPf0IF7Fo?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/aVOPf0IF7Fo/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
				</tr>
				<tr >
					<td colspan="2" align="left">&nbsp;</td>
				</tr>
			</table>	

			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tr class="headrow">
					<td align="center" width="50%"><b>Box Profiles</b></td>
					<td align="center" width="50%"><b>Sales Quotes</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/t4JvpYL_36Q?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/t4JvpYL_36Q/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/dWi0OPJ_Vuw?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/dWi0OPJ_Vuw/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
				</tr>
				<tr >
					<td colspan="2" align="left">&nbsp;</td>
				</tr>
			</table>	
			
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tr class="headrow">
					<td align="center" width="50%"><b>Browse Inventory</b></td>
					<td align="center" width="50%"><b>Favorites/Re-order</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/ZPRDZya1U7k?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/ZPRDZya1U7k/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/GdCRbD6d9nY?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/GdCRbD6d9nY/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
				</tr>
				<tr >
					<td colspan="2" align="left">&nbsp;</td>
				</tr>
			</table>	
			
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">			
				<tr class="headrow">
					<td align="center" width="50%"><b>Current order/history</b></td>
					<td align="center" width="50%"><b>Accounting</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/I82lkbiDJnU?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/I82lkbiDJnU/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/WAitSKM1lzU?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/WAitSKM1lzU/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
				</tr>
				<tr >
					<td colspan="2" align="left">&nbsp;</td>
				</tr>
			</table>	
			
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">	
				<tr class="headrow">
					<td align="center" width="50%"><b>Feedback</b></td>
					<td align="center" width="50%"><b>Buy Now Pages (Specs)</b></td>
				</tr>
				<tr class="">
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/OpkWKRckPFE?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/OpkWKRckPFE/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
					<td align="left">
						<iframe
						  width="540" height="315" id="videoframe"
						  srcdoc="<style>*{padding:0;margin:0;overflow:hidden}html,body{background:#000;height:100%}img{position:absolute;width:100%;top:0;bottom:0;margin:auto}</style>
						  <a href=https://www.youtube-nocookie.com/embed/txVqpHSs79Y?autoplay=1&modestbranding=1&iv_load_policy=3&theme=light&playsinline=1>
						  <img src=https://img.youtube.com/vi/txVqpHSs79Y/hqdefault.jpg>
						  <img id='playbutton' src='https://boomerang.usedcardboardboxes.com/images/youtube_play.png' style='width: 66px; position: absolute; left: 41.5%;'></a>"
						  frameborder="0"
						  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
						  allowfullscreen
						  scrolling="no" loading="lazy" style="background-color: #000"></iframe>
					</td>
				</tr>
			</table>
		</td>
		</tr>
	</table>
	<? 
}else if($_REQUEST['show'] == 'accounting'){  
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Accounting</h3></td>
		</tr>
		<tr>
			<td align="center">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
				<tr class="headrow">
					<td colspan="6" align="center">Account details&nbsp;
					<a href="UCB_Credit_App_2021.pdf" target="_blank"><font size="1" color="black"><u>New Customer Packet</u></font></a>
					</td>
				</tr>
				<?
				$on_hold = 0;
				$parent_child_flg = ""; $parent_child_compid = "";  $parent_comp_loopid = 0; $current_comp_loopid = 0;
				/*
				$dt_view_qry = "SELECT on_hold, parent_child, loopid, parent_comp_id from companyInfo WHERE ID=" . $client_companyid;
				$dt_view_res = db_query($dt_view_qry, db_b2b() );
				while ($dt_view_row = array_shift($dt_view_res)) {
					$on_hold = $dt_view_row["on_hold"];
					$parent_child_flg = $dt_view_row["parent_child"];
					$parent_child_compid = $dt_view_row["parent_comp_id"]; 
					$current_comp_loopid = $dt_view_row["loopid"]; 
				}
				
				if ($parent_child_compid >0) {
					$dt_view_qry = "SELECT on_hold, loopid from companyInfo WHERE ID = " . $parent_child_compid;
					$dt_view_res = db_query($dt_view_qry, db_b2b());
					$parent_comp_loopid = $current_comp_loopid;
					while ($dt_view_row = array_shift($dt_view_res)) {
					//	$on_hold = $dt_view_row["on_hold"];
						if ($dt_view_row["loopid"] > 0){
							$parent_comp_loopid = $dt_view_row["loopid"]; 
						}	
					}
				}else { $parent_comp_loopid = $current_comp_loopid; }				
				
				$dt_view_qry = "SELECT * FROM loop_warehouse WHERE id= '" . $parent_comp_loopid ."' AND credit_application_file != ''";
				$dt_view_res = db_query($dt_view_qry,db() );
				$credit_file_found = "yes";
				if (tep_db_num_rows($dt_view_res) == 0){
					$credit_file_found = "no";
					$dt_view_qry = "SELECT * FROM loop_warehouse WHERE id= '" . $parent_comp_loopid ."' AND credit_application_file != ''";
					$dt_view_res = db_query($dt_view_qry,db() );
				}*/
				$dt_view_qry = "SELECT * FROM loop_warehouse WHERE id= '" . $client_loopid ."' AND credit_application_file != ''";
				
				$dt_view_res = db_query($dt_view_qry, db() );
				//echo "<pre>"; print_r($dt_view_res); echo "</pre>";
				$termSetupCnt = tep_db_num_rows($dt_view_res);
				if($termSetupCnt > 0){
					?>
					<tr class="blackFont">
						<td colspan="2">Filename</td>
						<td colspan="2">User</td>
						<td colspan="2">Date</td>
					</tr>
					<?
					while ($dt_view_row = array_shift($dt_view_res)) {
						?>
						<tr>
							<td colspan="2"><a target="_blank" href="https://loops.usedcardboardboxes.com/credit_application/<? echo preg_replace( "/'/", "\'", $dt_view_row["credit_application_file"]) ; ?>" >		<? echo $dt_view_row["credit_application_file"]; ?></a>	</td>
							<td colspan="2"><? echo $dt_view_row["credit_application_by"]; ?></td>
							<td colspan="2"><? echo date('m/d/Y', strtotime($dt_view_row["credit_application_date"])); ?></td>
						</tr>
						<tr class="blackFont">
							<td>Net Term</td>
							<td>Credit Amount</td>
							<td>Notes</td>
							<td>Approve</td>
							<td>Approved By</td>
							<td>Approved On</td>
						</tr>
						<tr>
							<td>
								<?
								if(!empty($dt_view_row["credit_application_net_term"])){
									echo $dt_view_row["credit_application_net_term"];
								}else{
									echo 'Prepaid';
								}
								
								?>							
							</td>
							<td>$<?=number_format(str_replace(",", "", $dt_view_row["credit_application_credit_amt"]),2); ?></td>
							<td><?=$dt_view_row["credit_application_notes"]; ?></td>
							<td>Approve</td>
							<td><?=$dt_view_row["credit_application_apprv_by"]; ?></td>
							<td><?=date('m/d/Y', strtotime($dt_view_row["credit_application_apprv_dt"])); ?></td>
						</tr>
						<?
					}
				}else{
					?>
					<tr>
						<td colspan="6">Want to apply for credit terms? Fill out our <a href="UCB_Credit_App_2021.pdf" target="_blank"><u>credit application</u></a> and send it to <a href="mailto:CreditApproval@UsedCardboardBoxes.com">CreditApproval@UsedCardboardBoxes.com</a> </td>
					</tr>
					<?					
				}
				?>
				
				</tbody>
				</table>				
			</td>
		</tr>
	</table>

	<?
		//To Show the Invocie and Active Status table
		$display_info = "no";
		$total_balance = 0;
			
		$dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_warehouse.credit_application_net_term as Netterm,loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J, loop_transaction_buyer.no_invoice, loop_transaction_buyer.inv_number AS INVN,loop_transaction_buyer.trans_status  FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id 
		WHERE loop_warehouse.id = " . $warehouseid . " and loop_transaction_buyer.shipped = 1 AND pmt_entered = 0 AND loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
		//echo $dt_view_qry;
		$dt_view_res = db_query($dt_view_qry,db() );
		while ($dt_view_row = array_shift($dt_view_res)) {
		
			//This is the payment Info for the Customer paying UCB
			$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
			$payment_qry = db_query($payments_sql,db() );
			$payment = array_shift($payment_qry);

			//This is the payment info for UCB paying the related vendors
			$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
			$vendor_qry = db_query($vendor_sql,db() );
			$vendor = array_shift($vendor_qry);

			//Info about Shipment
			$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
			$bol_file_res = db_query($bol_file_qry,db());
			$bol_file_row = array_shift($bol_file_res);

			$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
			$fbookresult = db_query($fbooksql,db() );
			$freightbooking = array_shift($fbookresult);

			$vendors_paid = 0; //Are the vendors paid
			$vendors_entered = 0; //Has a vendor transaction been entered?
			$invoice_paid = 0; //Have they paid their invoice?
			$invoice_entered=0; //Has the inovice been entered
			$signed_customer_bol= 0; 	//Customer Signed BOL Uploaded
			$courtesy_followup = 0; 	//Courtesy Follow Up Made
			$delivered = 0; 	//Delivered
			$signed_driver_bol = 0; 	//BOL Signed By Driver
			$shipped = 0; 	//Shipped
			$bol_received = 0; 	//BOL Received @ WH
			$bol_sent = 0; 	//BOL Sent to WH"
			$bol_created = 0; 	//BOL Created
			$freight_booked = 0; //freight booked
			$sales_order = 0;   // Sales Order entered
			$po_uploaded = 0;  //po uploaded 
 
			//Are all the vendors paid?
			if ($vendor["B"] == 2 && $vendor["C"] == 2) 
			{ 
				$vendors_paid = 1; 
			}
			
			//Have we entered a vendor transaction?
			if ($vendor["A"] > 0) { 
				$vendors_entered = 1; 
			} 		

			//Have they paid their invoice?
			if (number_format($dt_view_row["F"],2) == number_format($payment["A"],2) && $dt_view_row["F"] != "") 
			{ 
				$invoice_paid = 1; 
			} 			
			if ($dt_view_row["no_invoice"] == 1) {				$invoice_paid = 1; 			}

			//Has an invoice amount been entered?
			if ($dt_view_row["F"] > 0) { 
				$invoice_entered=1; 
			} 

			if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") { $signed_customer_bol=1; }	//Customer Signed BOL Uploaded
			if ($bol_file_row["bol_shipment_followup"] >0) { $courtesy_followup = 1; }	//Courtesy Follow Up Made
			if ($bol_file_row["bol_shipment_received"] >0) { $delivered=1; }	//Delivered
			if ($bol_file_row["bol_signed_file_name"]!= "") { $signed_driver_bol=1; }	//BOL Signed By Driver
			if ($bol_file_row["bol_shipped"] >0) { $shipped = 1; }	//Shipped
			if ($bol_file_row["bol_received"] >0) { $bol_received = 1; }	//BOL Received @ WH
			if ($bol_file_row["bol_sent"] > 0) { $bol_sent = 1; }	//BOL Sent to WH"
			if ($bol_file_row["id"] > 0) { $bol_created=1; }	//BOL Created

			if ($freightbooking["id"] > 0 ) { $freight_booked = 1;} //freight booked

			if (($dt_view_row["G"] == 1)) {	 $sales_order = 1; } //sales order created
			if ($dt_view_row["H"] != "") { $po_uploaded = 1; } //po uploaded 

			$boxsource = "";
			$box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
			$box_res = db_query($box_qry,db() );
			while ($box_row = array_shift($box_res)) { $boxsource = $box_row["C"]; }

			//echo $dt_view_row["I"] . " = " . $invoice_entered . " - " . $invoice_paid . "<br>";
			if ($invoice_entered == 1 && $invoice_paid ==0)
			{ 
				$display_info == "yes";
				$dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
				$dt_view_res2 = db_query($dt_view_qry2,db() );
				$dt_view_row2 = array_shift($dt_view_res2);
				//
				/*if($dt_view_row["trans_status"]==4){
					$paid_ids.=$dt_view_row["I"].",";
					$paid_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
				}
				else if($dt_view_row["trans_status"]==3){
					$p2p_ids.=$dt_view_row["I"].",";
					$p2p_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
				}
				else{
				}
				*/
				
				$not_paid_ids.=$dt_view_row["I"].",";
				$not_paid_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
			
			}	//if not paid
		}	//while loop
			
		$show_grand_total="no";
		if(!empty($not_paid_MGArray)){
				$display_row="1"; $tblecnt=0;
			?>
			<br>
			<table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0" >
				<?
				$rowcolor = 0;
				foreach($not_paid_MGArray as $not_paid_array) {
					if ($rowcolor % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
					
					if($display_row=="1") {
						$display_row="0";
						?>
						<tr class="headrow">
							<td colspan="16" align="center">Outstanding invoices</td>	
						</tr>
						<tr>
							<td class="blackFont" align="center">ID</td>
							<td class="blackFont" align="center">Invoice Number</td>
							<td class="blackFont" align="center">Invoice Date</td>
							<!-- <td class="blackFont" align="center">Last Action</td> -->
							<td class="blackFont" align="center">Invoiced Amount</td>
							<td class="blackFont" align="center">Balance</td>
							<td class="blackFont" align="center">Invoice Age</td>
						</tr>
						<?
					}
					?>
				
					<tr class="<? echo $rowclr;?>">
			 		<td  align="center">
						  <?  echo $not_paid_array["rec_id"];?>
					</td>
					<td   align="center" >
						<?  echo $not_paid_array["inv_number"];?>
					</td>
					<td  align="center">
						<?  echo $invoice_date;?>
					</td>

					<!---- Last Action ------->
					<!-- <td   align="center">
						<?
						if ($not_paid_array["$invoice_paid"] == 1)
						{		
							if ($not_paid_array["vendors_paid"] == 1) { echo "Vendors Paid"; }
							elseif ($not_paid_array["vendors_entered"] == 1) { echo "Vendors Invoiced"; }
							else {echo "Customer Paid"; }
						} elseif ($not_paid_array["invoice_entered"] == 1) { echo "Customer Invoiced"; 
						} elseif ($not_paid_array["signed_customer_bol"] == 1) { echo "Customer Signed BOL"; 
						} elseif ($not_paid_array["courtesy_followup"] == 1) { echo "Courtesy Followup Made"; 
						} elseif ($not_paid_array["delivered"] == 1) { echo "Delivered"; 
						} elseif ($not_paid_array["signed_driver_bol"] == 1) { echo "Shipped - Driver Signed"; 
						} elseif ($not_paid_array["shipped"] == 1) { echo "Shipped"; 
						} elseif ($not_paid_array["bol_received"] == 1) { echo "BOL @ Warehouse"; 
						} elseif ($not_paid_array["bol_sent"] == 1) { echo "BOL Sent to Warehouse"; 
						} elseif ($not_paid_array["bol_created"] == 1) { echo "BOL Created"; 
						} elseif ($not_paid_array["freight_booked"] == 1) { echo "Freight Booked"; 
						} elseif ($not_paid_array["sales_order"] == 1) { echo "Sales Order Entered"; 
						} elseif ($not_paid_array["po_uploaded"] == 1) { echo "PO Uploaded"; }
						?>
					</td> -->

					<td   align="right" >
						$<?  echo number_format($not_paid_array["inv_amt"],2);?>
					</td>
					<?

					$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '".$not_paid_array["rec_id"]."'";

					$dt_view_res3 = db_query($dt_view_qry3,db() );
					$dt_view_row3 = array_shift($dt_view_res3);
					$blalnce_col_bg = "txt_style12";
					if (($not_paid_array["inv_amt"]-$dt_view_row3["PAID"]) < 0) {			
						$blalnce_col_bg  = "txt_style12_bold";
					}
					?>
						<td align="right" >
							$<? echo number_format(($not_paid_array["inv_amt"]-$dt_view_row3["PAID"]),2) ;
							$total_balance_not_paid += $not_paid_array["inv_amt"]-$dt_view_row3["PAID"];
							?>
						</td>
					<?
					$today=strtotime(date("m/d/Y"));
					$inv_date=strtotime($not_paid_array["inv_date"]);
					$diff=($today - $inv_date)/60/60/24;
					$qry="SELECT terms as Netterm, timestamp FROM loop_invoice_details WHERE trans_rec_id = '".$not_paid_array["rec_id"]."'";
						$qry_res=db_query($qry, db());	
						$net_row = array_shift($qry_res);
					
                                                $invoice_date = date("m/d/Y", strtotime($net_row["timestamp"]));
					//				
					if($net_row["Netterm"]!="")	
					{
						if($net_row["Netterm"]=="Prepaid" || $net_row["Netterm"]=="Due On Receipt" || $net_row["Netterm"]=="Other-See Notes"){
							$no_of_net=0;	
						}
						if($net_row["Netterm"]=="Net 10"){
							$no_of_net=10;	

						}
						if($net_row["Netterm"]=="Net 15"){
							$no_of_net=15;
						}
						if($net_row["Netterm"]=="Net 30" || $net_row["Netterm"]=="1% 10 Net30"){
							$no_of_net=30;
						}
						if($net_row["Netterm"]=="Net 45"){
							$no_of_net=45;
						}
						if($net_row["Netterm"]=="Net 60"){
							$no_of_net=60;
						}
						if($net_row["Netterm"]=="Net 75"){
							$no_of_net=75;
						}
						if($net_row["Netterm"]=="Net 90"){
							$no_of_net=90;
						}
						if($net_row["Netterm"]=="Net 120"){
							$no_of_net=120;
						}
						//
						if($net_row["Netterm"]=="Net 120 EOM +1"){
							//
							$next_due_date1 = date('m/d/Y',strtotime('+120 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								//echo $next_due_date2."--".date("m/d/Y");
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							//
							$no_of_net=120;
						}
						//
						
						if($net_row["Netterm"]=="Net 30 EOM +1"){
							$next_due_date1 = date('m/d/Y',strtotime('+30 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							$no_of_net=30;
						}
						
						if($net_row["Netterm"]=="Net 45 EOM +1"){
							$next_due_date1 = date('m/d/Y',strtotime('+45 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							$no_of_net=45;
						}

						if($net_row["Netterm"]=="Net 120 EOM +1"){
							if($duedate_flg=="false"){
								$inv_age_color="#ff0000";	
							}
							else{
								$inv_age_color="";	
							}
						}
						else{
							if($diff>$no_of_net){
								$inv_age_color="#ff0000";	
							}
							else{
								$inv_age_color="";	
							}
						}
						//
					}
					else{
						$no_of_net=0;
						$inv_age_color="#ff0000";
					}
					//		
					$start_t = strtotime($not_paid_array["inv_date"]);		
					$end_time =  strtotime(now);
					//	
					//echo "net--".number_format(($end_time-$start_t)/(3600*24),0);
					//
						?>
						<td align="center" bgColor="<?=$inv_age_color?>">
							<?  echo number_format(($end_time-$start_t)/(3600*24),0); ?>
						</td>
					</tr>
					<?
					$rowcolor++;
				}//End foreach
				if ( $rowclr == 'rowalt2'){ 
					$totalRowClr = 'rowalt1'; 
				}else{ 
					$totalRowClr = 'rowalt2'; 
				}
				?>
				<tr class="<? echo $totalRowClr;?>">
					<td   align="center" ></td><td   align="center" ></td><td   align="center" ></td>
					<td  align="right" >Total:</td><td  align="right" >$<?=number_format($total_balance_not_paid,2);?></td>
					<td colspan ="2"   align="center" ></td>
				</tr>
			</table>
		<?  
		}//End Not paid arr

		//To Show the Invocie and Active Status table
		

		function showchild_invnotpaid($comp_nm, $warehouseid){

			global $tot_invoice_cnt; 
			global $tot_invoice_amt; 
			global $tot_past_due_cnt;
			global $tot_past_due_amt;
			
			$display_info = "no";
			$total_balance = 0;
				
			$dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_warehouse.credit_application_net_term as Netterm,loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J, loop_transaction_buyer.no_invoice, loop_transaction_buyer.inv_number AS INVN,loop_transaction_buyer.trans_status, loop_transaction_buyer.invoice_paid  FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_warehouse.id = " . $warehouseid . " and loop_transaction_buyer.shipped = 1 AND pmt_entered = 0 AND loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
			//echo $dt_view_qry;
			$dt_view_res = db_query($dt_view_qry,db() );
			while ($dt_view_row = array_shift($dt_view_res)) {
			
				//This is the payment Info for the Customer paying UCB
				$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
				$payment_qry = db_query($payments_sql,db() );
				$payment = array_shift($payment_qry);

				//This is the payment info for UCB paying the related vendors
				$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
				$vendor_qry = db_query($vendor_sql,db() );
				$vendor = array_shift($vendor_qry);

				//Info about Shipment
				$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
				$bol_file_res = db_query($bol_file_qry,db());
				$bol_file_row = array_shift($bol_file_res);

				$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
				$fbookresult = db_query($fbooksql,db() );
				$freightbooking = array_shift($fbookresult);

				$vendors_paid = 0; //Are the vendors paid
				$vendors_entered = 0; //Has a vendor transaction been entered?
				$invoice_paid = 0; //Have they paid their invoice?
				$invoice_entered=0; //Has the inovice been entered
				$signed_customer_bol= 0; 	//Customer Signed BOL Uploaded
				$courtesy_followup = 0; 	//Courtesy Follow Up Made
				$delivered = 0; 	//Delivered
				$signed_driver_bol = 0; 	//BOL Signed By Driver
				$shipped = 0; 	//Shipped
				$bol_received = 0; 	//BOL Received @ WH
				$bol_sent = 0; 	//BOL Sent to WH"
				$bol_created = 0; 	//BOL Created
				$freight_booked = 0; //freight booked
				$sales_order = 0;   // Sales Order entered
				$po_uploaded = 0;  //po uploaded 
	 
				//Are all the vendors paid?
				if ($vendor["B"] == 2 && $vendor["C"] == 2) 
				{ 
					$vendors_paid = 1; 
				}
				
				//Have we entered a vendor transaction?
				if ($vendor["A"] > 0) { 
					$vendors_entered = 1; 
				} 		

				//Have they paid their invoice?
				if (number_format($dt_view_row["F"],2) == number_format($payment["A"],2) && $dt_view_row["F"] != "") 
				{ 
					$invoice_paid = 1; 
				} 			
				
				if ($dt_view_row["no_invoice"] == 1) {				$invoice_paid = 1; 			}
				//if ($dt_view_row["invoice_paid"] == 1) {				$invoice_paid = 1; 			}
				

				//Has an invoice amount been entered?
				if ($dt_view_row["F"] > 0) { 
					$invoice_entered=1; 
				} 

				if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") { $signed_customer_bol=1; }	//Customer Signed BOL Uploaded
				if ($bol_file_row["bol_shipment_followup"] >0) { $courtesy_followup = 1; }	//Courtesy Follow Up Made
				if ($bol_file_row["bol_shipment_received"] >0) { $delivered=1; }	//Delivered
				if ($bol_file_row["bol_signed_file_name"]!= "") { $signed_driver_bol=1; }	//BOL Signed By Driver
				if ($bol_file_row["bol_shipped"] >0) { $shipped = 1; }	//Shipped
				if ($bol_file_row["bol_received"] >0) { $bol_received = 1; }	//BOL Received @ WH
				if ($bol_file_row["bol_sent"] > 0) { $bol_sent = 1; }	//BOL Sent to WH"
				if ($bol_file_row["id"] > 0) { $bol_created=1; }	//BOL Created

				if ($freightbooking["id"] > 0 ) { $freight_booked = 1;} //freight booked

				if (($dt_view_row["G"] == 1)) {	 $sales_order = 1; } //sales order created
				if ($dt_view_row["H"] != "") { $po_uploaded = 1; } //po uploaded 

				$boxsource = "";
				$box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
				$box_res = db_query($box_qry,db() );
				while ($box_row = array_shift($box_res)) { $boxsource = $box_row["C"]; }

				if ($invoice_entered == 1 && $invoice_paid ==0)
				{ 

					$qry="SELECT timestamp FROM loop_invoice_details WHERE trans_rec_id = '". $dt_view_row["I"] ."'";
					$qry_res=db_query($qry, db());	
					$net_row = array_shift($qry_res);
					$invoice_date = date("m/d/Y", strtotime($net_row["timestamp"]));
			
					$display_info == "yes";
					$dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
					$dt_view_res2 = db_query($dt_view_qry2,db() );
					$dt_view_row2 = array_shift($dt_view_res2);
					//
					if($dt_view_row["trans_status"]==4){
						$paid_ids.=$dt_view_row["I"].",";
						$paid_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
					}
					else if($dt_view_row["trans_status"]==3){
						$p2p_ids.=$dt_view_row["I"].",";
						$p2p_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
					}
					else{
						$not_paid_ids.=$dt_view_row["I"].",";
						$not_paid_MGArray[] = array('b2bid' => $_REQUEST["ID"],'comp_nm' =>$comp_nm,'warehouse_id' => $dt_view_row["D"], 'rec_type' => $rec_type, 'rec_id' => $dt_view_row["I"], 'inv_number' => $dt_view_row["INVN"], 'invoice_date' => $invoice_date, 'ship_date' => $dt_view_row2["E"], 'invoice_paid' => $invoice_paid, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered,'invoice_entered' => $invoice_entered, 'signed_customer_bol' => $signed_customer_bol, 'courtesy_followup' => $courtesy_followup, 'delivered' => $delivered, 'signed_driver_bol' => $signed_driver_bol, 'shipped' => $shipped, 'bol_received' => $bol_received, 'bol_sent' => $bol_sent, 'bol_created' => $bol_created, 'freight_booked' => $freight_booked, 'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'inv_amt' => $dt_view_row["F"],'inv_date' => $dt_view_row["J"]); 
					}
					//
				
					$tot_invoice_cnt = $tot_invoice_cnt + 1; 
					$tot_invoice_amt = $tot_invoice_amt + str_replace(",", "", $dt_view_row["F"]); 
				
				}	//if not paid
			}	//while loop
			
			$show_grand_total="no";
			if(!empty($not_paid_MGArray)){
				$display_row="1"; $tblecnt=0; $rowcolor = 0;
			?>
			<br>
			<table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0" >
				<?
				foreach($not_paid_MGArray as $not_paid_array)
				{
					if($display_row=="1")
					{
						$display_row="0";
					?>
					<tr class="headrow">
						<td colspan="16" align="center"><? echo $not_paid_array["comp_nm"];?></td>	
					</tr>
					
					<tr class="headrow">
						<td colspan="16" class="blackFont" align="center">INVOICED AND ACTIVE STATUS</td>	
					</tr>
					<tr>
						<td class="blackFont" align="center">ID</td>
						<td class="blackFont" align="center">Invoice Number</td>
						<td class="blackFont" align="center">Invoice Date</td>
						<td class="blackFont" align="center">Invoiced Amount</td>
						<td class="blackFont" align="center">Balance</td>
						<td class="blackFont" align="center">Invoice Age</td>
					</tr>
						
				<?
				}
				
				if ($rowcolor % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
				$rowcolor = $rowcolor + 1;
			?>
				<tr class="<? echo $rowclr;?>">
			
			 		<td bgColor="#e4e4e4" class="txt_style12" align="center">
						  <?  echo $not_paid_array["rec_id"];?>
					</td>
					<td bgColor="#e4e4e4" class="txt_style12" align="center">
						<?  echo $not_paid_array["inv_number"];?>
					</td>
					<td bgColor="#e4e4e4" class="txt_style12" align="center">
						<?  echo $not_paid_array["invoice_date"];?>
					</td>


					<td bgColor="#e4e4e4" class="txt_style12" align="right">
						$<?  echo number_format($not_paid_array["inv_amt"],2);?>
					</td>
					<?

					$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '".$not_paid_array["rec_id"]."'";

					$dt_view_res3 = db_query($dt_view_qry3,db() );
					$dt_view_row3 = array_shift($dt_view_res3);
					$blalnce_col_bg = "txt_style12";
					if (($not_paid_array["inv_amt"]-$dt_view_row3["PAID"]) < 0) {			
						$blalnce_col_bg  = "txt_style12_bold";
					}
					?>
						<td bgColor="#e4e4e4" class="<? echo $blalnce_col_bg; ?>" align="right">
							$<? echo number_format(($not_paid_array["inv_amt"]-$dt_view_row3["PAID"]),2) ;
							$total_balance_not_paid += $not_paid_array["inv_amt"]-$dt_view_row3["PAID"];
							?>
						</td>
					<?
					$today=strtotime(date("m/d/Y"));
					$inv_date=strtotime($not_paid_array["inv_date"]);
					$diff=($today - $inv_date)/60/60/24;
					$qry="SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '".$not_paid_array["rec_id"]."'";
						$qry_res=db_query($qry, db());	
						$net_row = array_shift($qry_res);
					
					//				
					if($net_row["Netterm"]!="")	
					{
						if($net_row["Netterm"]=="Prepaid" || $net_row["Netterm"]=="Due On Receipt" || $net_row["Netterm"]=="Other-See Notes"){
							$no_of_net=0;	
						}
						if($net_row["Netterm"]=="Net 10"){
							$no_of_net=10;	

						}
						if($net_row["Netterm"]=="Net 15"){
							$no_of_net=15;
						}
						if($net_row["Netterm"]=="Net 20"){
							$no_of_net=20;
						}
						if($net_row["Netterm"]=="Net 25"){
							$no_of_net=25;
						}
						if($net_row["Netterm"]=="Net 30" || $net_row["Netterm"]=="1% 10 Net30" || $net_row["Netterm"]=="1% 15 Net 30"){
							$no_of_net=30;
						}
						if($net_row["Netterm"]=="Net 45"){
							$no_of_net=45;
						}
						if($net_row["Netterm"]=="Net 60"){
							$no_of_net=60;
						}
						if($net_row["Netterm"]=="Net 75"){
							$no_of_net=75;
						}
						if($net_row["Netterm"]=="Net 90"){
							$no_of_net=90;
						}
						if($net_row["Netterm"]=="Net 120"){
							$no_of_net=120;
						}
						//
						if($net_row["Netterm"]=="Net 120 EOM +1"){
							//
							$next_due_date1 = date('m/d/Y',strtotime('+120 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								//echo $next_due_date2."--".date("m/d/Y");
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							//
							$no_of_net=120;
						}
						//
						
						if($net_row["Netterm"]=="Net 30 EOM +1"){
							$next_due_date1 = date('m/d/Y',strtotime('+30 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							$no_of_net=30;
						}
						
						if($net_row["Netterm"]=="Net 45 EOM +1"){
							$next_due_date1 = date('m/d/Y',strtotime('+45 days', strtotime($not_paid_array["inv_date"])));
							$m = date("m", strtotime($next_due_date1)); 	
							$y = date("Y", strtotime($next_due_date1)); 	
							$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
							$eom_date= $m."/".$no_days."/".$y;
							$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
							$new_due_date=strtotime($next_due_date2);	
							//
							if($new_due_date>$today){
								$duedate_flg="true";
							}
							else{
								$duedate_flg="false";
							}
							$no_of_net=45;
						}

						if($net_row["Netterm"]=="Net 120 EOM +1"){
							if($duedate_flg=="false"){
								$inv_age_color="#ff0000";	
								$tot_past_due_cnt = $tot_past_due_cnt + 1;
								$tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
							}
							else{
								$inv_age_color="#e4e4e4";	
							}
						}
						else{
							if($diff>$no_of_net){
								$inv_age_color="#ff0000";	
								$tot_past_due_cnt = $tot_past_due_cnt + 1;
								$tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
							}
							else{
								$inv_age_color="#e4e4e4";	
							}
						}
						//
					}
					else{
						$no_of_net=0;
						$inv_age_color="#ff0000";
						$tot_past_due_cnt = $tot_past_due_cnt + 1;
						$tot_past_due_amt = $tot_past_due_amt + $not_paid_array["inv_amt"];
					}
					//		
					$start_t = strtotime($not_paid_array["inv_date"]);		
					$end_time =  strtotime(now);
					//	
					//echo "net--".number_format(($end_time-$start_t)/(3600*24),0);
					//
						?>
						<td bgColor="<?=$inv_age_color?>" class="txt_style12" align="right">
							<?  echo number_format(($end_time-$start_t)/(3600*24),0); ?>
						</td>
					</tr>
				<?
				}//End foreach
				?>
				<tr><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" >Total:</td><td bgColor="#e4e4e4" class="txt_style12" align="right" >$<?=number_format($total_balance_not_paid,2);?></td><td colspan= 2 bgColor="#e4e4e4" class="txt_style12" ></td></tr>
			</table>
		<?  
		}//End Not paid arr
		//

		$total_balance_p2p = 0;
		if(!empty($p2p_MGArray)){
		?>
		<br>
		<table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0" >
			<tr class="headrow">
				<td colspan="16" align="center">INVOICED AND PROMISE TO PAY</td>	
			</tr>

			<tr>
				<td class="blackFont" align="center">ID</td>
				<td class="blackFont" align="center">Invoice Number</td>
				<td class="blackFont" align="center">Invoice Date</td>
				<td class="blackFont" align="center">Invoiced Amount</td>
				<td class="blackFont" align="center">Balance</td>
				<td class="blackFont" align="center">Invoice Age</td>
			</tr>
		<?
			foreach($p2p_MGArray as $paid_array)
			{
				$show_grand_total="yes";
			//}
		?>
			<tr>
			
			 	<td bgColor="#e4e4e4" class="txt_style12" align="center" >
				  <?  echo $paid_array["rec_id"];?>
				</td>
				<td bgColor="#e4e4e4" class="txt_style12" align="center" >
					<?  echo $paid_array["inv_number"];?>
				</td>
				<td bgColor="#e4e4e4" class="txt_style12" align="center">
					<?  echo $paid_array["invoice_date"];?>
				</td>

		    <td bgColor="#e4e4e4" class="txt_style12" align="right">
				$<?  echo number_format($paid_array["inv_amt"],2);?>
			</td>
		<?

			$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '".$paid_array["rec_id"]."'";

			$dt_view_res3 = db_query($dt_view_qry3,db() );
			$dt_view_row3 = array_shift($dt_view_res3);
			$blalnce_col_bg = "txt_style12";
			if (($paid_array["inv_amt"]-$dt_view_row3["PAID"]) < 0) {			
				$blalnce_col_bg  = "txt_style12_bold";
			}
	?>
			<td bgColor="#e4e4e4" class="<? echo $blalnce_col_bg; ?>" align="right">
				$<? echo number_format(($paid_array["inv_amt"]-$dt_view_row3["PAID"]),2) ;
				$total_balance_p2p += $paid_array["inv_amt"]-$dt_view_row3["PAID"];
				?>
			</td>
	<?
			$today=strtotime(date("m/d/Y"));
			$inv_date=strtotime($paid_array["inv_date"]);
			$diff=($today - $inv_date)/60/60/24;
			$qry="SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '".$paid_array["rec_id"]."'";
			$qry_res=db_query($qry, db());	
			$net_row = array_shift($qry_res);
							
			if($net_row["Netterm"]!="")	
			{
				if($net_row["Netterm"]=="Prepaid" || $net_row["Netterm"]=="Due On Receipt" || $net_row["Netterm"]=="Other-See Notes"){
					$no_of_net=0;	
				}
				if($net_row["Netterm"]=="Net 10"){
					$no_of_net=10;	
					
				}
				if($net_row["Netterm"]=="Net 15"){
					$no_of_net=15;
				}
				if($net_row["Netterm"]=="Net 20"){
					$no_of_net=20;
				}
				if($net_row["Netterm"]=="Net 25"){
					$no_of_net=25;
				}
				if($net_row["Netterm"]=="Net 30" || $net_row["Netterm"]=="1% 10 Net30" || $net_row["Netterm"]=="1% 15 Net 30"){
					$no_of_net=30;
				}
				if($net_row["Netterm"]=="Net 45"){
					$no_of_net=45;
				}
				if($net_row["Netterm"]=="Net 60"){
					$no_of_net=60;
				}
				if($net_row["Netterm"]=="Net 75"){
					$no_of_net=75;
				}
				if($net_row["Netterm"]=="Net 90"){
					$no_of_net=90;
				}
				if($net_row["Netterm"]=="Net 120"){
					$no_of_net=120;
				}
				
				if($net_row["Netterm"]=="Net 30 EOM +1"){
					$next_due_date1 = date('m/d/Y',strtotime('+30 days', strtotime($paid_array["inv_date"])));
					$m = date("m", strtotime($next_due_date1)); 	
					$y = date("Y", strtotime($next_due_date1)); 	
					$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
					$eom_date= $m."/".$no_days."/".$y;
					$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
					$new_due_date=strtotime($next_due_date2);	
					//
					if($new_due_date>$today){
						$duedate_flg="true";
					}
					else{
						$duedate_flg="false";
					}
					$no_of_net=30;
				}
				
				if($net_row["Netterm"]=="Net 45 EOM +1"){
					$next_due_date1 = date('m/d/Y',strtotime('+45 days', strtotime($paid_array["inv_date"])));
					$m = date("m", strtotime($next_due_date1)); 	
					$y = date("Y", strtotime($next_due_date1)); 	
					$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
					$eom_date= $m."/".$no_days."/".$y;
					$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
					$new_due_date=strtotime($next_due_date2);	
					//
					if($new_due_date>$today){
						$duedate_flg="true";
					}
					else{
						$duedate_flg="false";
					}
					$no_of_net=45;
				}
						
				if($net_row["Netterm"]=="Net 120 EOM +1"){
					$next_due_date1 = date('m/d/Y',strtotime('+120 days', strtotime($paid_array["inv_date"])));
					$m = date("m", strtotime($next_due_date1)); 	
					$y = date("Y", strtotime($next_due_date1)); 	
					$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
					$eom_date= $m."/".$no_days."/".$y;
					$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
					$new_due_date=strtotime($next_due_date2);	
					//
					if($new_due_date>$today){
						//echo $next_due_date2."--".date("m/d/Y");
						$duedate_flg="true";
					}
					else{
						$duedate_flg="false";
					}
					//
					$no_of_net=120;
				}
				//
				if($net_row["Netterm"]=="Net 120 EOM +1"){
					if($duedate_flg=="false"){
						$inv_age_color="#ff0000";	
						$tot_past_due_cnt = $tot_past_due_cnt + 1;
						$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
					}
					else{
						$inv_age_color="#e4e4e4";	
					}
				}
				else{
					if($diff>$no_of_net){
						$inv_age_color="#ff0000";	
						$tot_past_due_cnt = $tot_past_due_cnt + 1;
						$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
					}
					else{
						$inv_age_color="#e4e4e4";	
					}
				}
				
				//
			}
			else{
				$no_of_net=0;
				$inv_age_color="#ff0000";
				$tot_past_due_cnt = $tot_past_due_cnt + 1;
				$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
			}
			//		
			$start_t = strtotime($paid_array["inv_date"]);		
			$end_time =  strtotime(now);
		?>
				<td bgColor="<?=$inv_age_color?>" class="txt_style12" align="right">
					<?  echo number_format(($end_time-$start_t)/(3600*24),0); ?>
				</td>
			</tr>
		<?
			}//End foreach
		?>
			<tr><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" >Total:</td><td bgColor="#e4e4e4" class="txt_style12" align="right">$<?=number_format($total_balance_p2p,2);?></td><td colspan= 2 bgColor="#e4e4e4" class="txt_style12" ></td></tr>
		</table>
		<?  
		}//End P2p Status array
		
		
		if(!empty($paid_MGArray)){
		?>
		<br>
			<table class="mb-10 tableBorder" width="100%" cellspacing="1" cellpadding="1" border="0" >
				<tr class="headrow">
					<td colspan="16" align="center">INVOICED AND PAID STATUS</td>	
				</tr>
			
				<tr>
					<td class="blackFont" align="center">ID</td>
					<td class="blackFont" align="center">Invoice Number</td>
					<td class="blackFont" align="center">Invoice Date</td>
					<td class="blackFont" align="center">Invoiced Amount</td>
					<td class="blackFont" align="center">Balance</td>
					<td class="blackFont" align="center">Invoice Age</td>
				</tr>
		<?
			$rowColor = 0;
			foreach($paid_MGArray as $paid_array)
			{
				$show_grand_total="yes";
			//}
			
				if ($rowColor % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }
				$rowColor = $rowColor + 1;
		?>
			<tr class="<? echo $rowclr;?>">
			
			 	<td bgColor="#e4e4e4" class="txt_style12" align="center">
                      <?  echo $paid_array["rec_id"];?>
				</td>
				<td bgColor="#e4e4e4" class="txt_style12" align="center">
					<?  echo $paid_array["inv_number"];?>
				</td>
				<td bgColor="#e4e4e4" class="txt_style12" align="center">
					<?  echo $paid_array["invoice_date"];?>
				</td>

		    <td bgColor="#e4e4e4" class="txt_style12"  align="right">
				$<?  echo number_format($paid_array["inv_amt"],2);?>
			</td>
		<?

		$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = '".$paid_array["rec_id"]."'";

		$dt_view_res3 = db_query($dt_view_qry3,db() );
		$dt_view_row3 = array_shift($dt_view_res3);
		$blalnce_col_bg = "txt_style12";
		if (($paid_array["inv_amt"]-$dt_view_row3["PAID"]) < 0) {			
			$blalnce_col_bg  = "txt_style12_bold";
		}
?>
		<td bgColor="#e4e4e4" class="<? echo $blalnce_col_bg; ?>"  align="right">
			$<? echo number_format(($paid_array["inv_amt"]-$dt_view_row3["PAID"]),2) ;
			$total_balance_paid += $paid_array["inv_amt"]-$dt_view_row3["PAID"];
			?>
		</td>
<?
		$today=strtotime(date("m/d/Y"));
		$inv_date=strtotime($paid_array["inv_date"]);
		$diff=($today - $inv_date)/60/60/24;
		$qry="SELECT terms as Netterm FROM loop_invoice_details WHERE trans_rec_id = '".$paid_array["rec_id"]."'";
		$qry_res=db_query($qry, db());	
		$net_row = array_shift($qry_res);
						
		if($net_row["Netterm"]!="")	
		{
			if($net_row["Netterm"]=="Prepaid" || $net_row["Netterm"]=="Due On Receipt" || $net_row["Netterm"]=="Other-See Notes"){
				$no_of_net=0;	
			}
			if($net_row["Netterm"]=="Net 10"){
				$no_of_net=10;	
				
			}
			if($net_row["Netterm"]=="Net 15"){
				$no_of_net=15;
			}
			if($net_row["Netterm"]=="Net 20"){
					$no_of_net=20;
				}
				if($net_row["Netterm"]=="Net 25"){
					$no_of_net=25;
				}
			if($net_row["Netterm"]=="Net 30" || $net_row["Netterm"]=="1% 10 Net30" || $net_row["Netterm"]=="1% 15 Net 30"){
				$no_of_net=30;
			}
			if($net_row["Netterm"]=="Net 45"){
				$no_of_net=45;
			}
			if($net_row["Netterm"]=="Net 60"){
				$no_of_net=60;
			}
			if($net_row["Netterm"]=="Net 75"){
				$no_of_net=75;
			}
			if($net_row["Netterm"]=="Net 90"){
				$no_of_net=90;
			}
			if($net_row["Netterm"]=="Net 120"){
				$no_of_net=120;
			}
			
			if($net_row["Netterm"]=="Net 30 EOM +1"){
				$next_due_date1 = date('m/d/Y',strtotime('+30 days', strtotime($paid_array["inv_date"])));
				$m = date("m", strtotime($next_due_date1)); 	
				$y = date("Y", strtotime($next_due_date1)); 	
				$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
				$eom_date= $m."/".$no_days."/".$y;
				$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
				$new_due_date=strtotime($next_due_date2);	
				//
				if($new_due_date>$today){
					$duedate_flg="true";
				}
				else{
					$duedate_flg="false";
				}
				$no_of_net=30;
			}
			
			if($net_row["Netterm"]=="Net 45 EOM +1"){
				$next_due_date1 = date('m/d/Y',strtotime('+45 days', strtotime($paid_array["inv_date"])));
				$m = date("m", strtotime($next_due_date1)); 	
				$y = date("Y", strtotime($next_due_date1)); 	
				$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
				$eom_date= $m."/".$no_days."/".$y;
				$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
				$new_due_date=strtotime($next_due_date2);	
				//
				if($new_due_date>$today){
					$duedate_flg="true";
				}
				else{
					$duedate_flg="false";
				}
				$no_of_net=45;
			}
			
			//
			if($net_row["Netterm"]=="Net 120 EOM +1"){
				//
				$next_due_date1 = date('m/d/Y',strtotime('+120 days', strtotime($paid_array["inv_date"])));
				$m = date("m", strtotime($next_due_date1)); 	
				$y = date("Y", strtotime($next_due_date1)); 	
				$no_days=cal_days_in_month(CAL_GREGORIAN, $m, $y);	
				$eom_date= $m."/".$no_days."/".$y;
				$next_due_date2 = date('m/d/Y',strtotime('+1 days', strtotime($eom_date)));		
				$new_due_date=strtotime($next_due_date2);	
				//
				if($new_due_date>$today){
					//echo $next_due_date2."--".date("m/d/Y");
					$duedate_flg="true";
				}
				else{
					$duedate_flg="false";
				}
				//
				$no_of_net=120;
			}
			//
			if($net_row["Netterm"]=="Net 120 EOM +1"){
				if($duedate_flg=="false"){
					$inv_age_color="#ff0000";	
					$tot_past_due_cnt = $tot_past_due_cnt + 1;
					$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
				}
				else{
					$inv_age_color="#e4e4e4";	
				}
			}
			else{
				if($diff>$no_of_net){
					$inv_age_color="#ff0000";	
					$tot_past_due_cnt = $tot_past_due_cnt + 1;
					$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
				}
				else{
					$inv_age_color="#e4e4e4";	
				}
			}
			
			//
		}
		else{
			$no_of_net=0;
			$inv_age_color="#ff0000";
			$tot_past_due_cnt = $tot_past_due_cnt + 1;
			$tot_past_due_amt = $tot_past_due_amt + $paid_array["inv_amt"];
		}
		//		
		$start_t = strtotime($paid_array["inv_date"]);		
		$end_time =  strtotime(now);
		?>
				<td bgColor="<?=$inv_age_color?>" class="txt_style12"  align="right">
					<?  echo number_format(($end_time-$start_t)/(3600*24),0); ?>
				</td>
			</tr>
		<?
		}//End foreach
		?>
		<tr><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" ></td><td bgColor="#e4e4e4" class="txt_style12" >Total:</td><td bgColor="#e4e4e4" class="txt_style12" align="right">$<?=number_format($total_balance_paid,2);?></td><td colspan= 2 bgColor="#e4e4e4" class="txt_style12" ></td></tr>
	</table>
	<?  
	}//End paid arr
	
		$total_balance=$total_balance_paid+$total_balance_not_paid+$total_balance_p2p;
		
		if ($show_grand_total == "yes")  {		
	?>
		<!-- <br>
		<table width="427px">	
			<tr><td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Grand Total:</strong></td><td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="left" style="padding-left: 15px;"><strong>$<?=number_format($total_balance,2);?></strong></td></tr>
		</table>	
		-->
		<?

			$show_grand_total = "no";
		}
	}//End function
		
		//for Parent company only
		if ($parent_child == "Parent" && $setup_family_tree == 0) {
			//echo "<br><h3>Child Company - Invoice not Paid in Full details</h3>";
			$tot_invoice_cnt = 0; $tot_invoice_amt = 0; $tot_past_due_cnt = 0; $tot_past_due_amt = 0;
			
			$sql_b2b = "Select companyInfo.parent_child, companyInfo.on_hold ,companyInfo.ID AS I, companyInfo.shipCity, companyInfo.shipState, companyInfo.loopid AS LID, companyInfo.contact AS C, companyInfo.dateCreated AS D, companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH, companyInfo.city AS CI,  companyInfo.state AS ST, companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_contact_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID ";
			$sql_b2b = $sql_b2b . " where companyInfo.parent_comp_id = " . $client_companyid . " and companyInfo.parent_child = 'Child'";
			//echo $sql_b2b . "<br>";

			$data_res_pc = db_query($sql_b2b,db_b2b() );
			while ($data_rec_pc = array_shift($data_res_pc)) {

				if ($data_rec_pc["NN"] != ""){
					$comp_nm = $data_rec_pc["NN"];
				}else{
					$tmppos_1 = strpos($data_rec_pc["CO"], "-");
					if ($tmppos_1 != false)
					{
						$comp_nm = $data_rec_pc["CO"];
					}else {
						if ($data_rec_pc["shipCity"] <> "" || $data_rec_pc["shipState"] <> "" ) 
						{
							$comp_nm = $data_rec_pc["CO"] . " - " . $data_rec_pc["shipCity"] . ", " . $data_rec_pc["shipState"] ;
						}else { $comp_nm = $data_rec_pc["CO"]; }
					}
				}		
				showchild_invnotpaid($comp_nm, $data_rec_pc["LID"]);
			}
			
			if ($tot_invoice_cnt > 0){
			?>
				<table width="427px">	
					<tr>
						<td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Total Invoice Amount:</strong></td>
						<td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="right" ><strong>$<?=number_format($tot_invoice_amt,2);?></strong></td>
					</tr>
					<tr>
						<td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Total Invoice Count:</strong></td>
						<td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="right"><strong><?=$tot_invoice_cnt;?></strong></td>
					</tr>
					<tr>
						<td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Total Past Due Amount:</strong></td>
						<td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="right" ><strong>$<?=number_format($tot_past_due_amt,2);?></strong></td>
					</tr>
					<tr>
						<td bgColor="#c0cdda" class="grand_txt_style12" align="right" ><strong>Total Past Due Count:</strong></td>
						<td bgColor="#c0cdda" class="grand_txt_style12" width="172px" align="right" ><strong><?=$tot_past_due_cnt;?></strong></td>
					</tr>
				</table>	
		<?			
			}
		}
		
	?>	
	<? 
}else if($_REQUEST['show'] == 'feedback'){  
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Feedback</h3></td>
		</tr>
		<tr >
			<td align="left"><i>We'd love your feedback! These online portals are new and we're regularly improving them! We're eager to remove the barriers to box re-use on a national scale, so if you have any feedback, ideas, questions or concerns...please don't hesitate to let us know!</i></td>
		</tr>
		<tr >
		<td align="center">
			<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
			<tbody>
			<tr class="headrow">
				<td align="left">Feedback form</td>
			</tr>
			<tr>
				<td>
					<table width="50%" cellspacing="1" cellpadding="1" border="0" class="mb-10">
					<tbody>
						<tr>
							<td><label>Subject:</label></td>
							<td><input type="text" name="txtSubject" id="txtSubject" value="" style="width: 100%;"></td>
						</tr>
						<tr>
							<td><label>Message:</label></td>
							<td><textarea name="txtMessage" id="txtMessage" rows="8" cols="60"></textarea></td>
						</tr>
						<tr>
							<td><input type="hidden" name="hdnRepchkStr" id="hdnRepchkStr" value="<?=$repchk_str?>"></td>
							<td align="left"><input type="button" name="btnFeedback" id="btnFeedback" value="Submit" onclick="chkFeedback()"></td>
							<td><input type="hidden" name="hdncompnewid" id="hdncompnewid" value="<?=$_REQUEST['compnewid'];?>"></td>
							<td><input type="hidden" name="hdclient_loginid" id="hdclient_loginid" value="<? echo $client_loginid;?>"></td>
						</tr>
						<tr>
							<td colspan="2" id="feedbackResponseText" style="color:green"></td>
						</tr>
						
					</tbody>
					</table>
				</td>
			</tr>			
			</tbody>
			</table>
		</td>
		</tr>
	</table>
	<?
}else if($_REQUEST['show'] == 'why_boomerang'){  
	?>
	<table width="100%">
		<tr >
		<td align="center"><h3>Why Boomerang by <span class="boomrang_main_heading">UsedCardboardBoxes?</span></h3></td>
		</tr>
		<tr>
			<td align="center">
				<div class="boomerang_main">
					<div class="boomerang_div_grey">
						<div class="layout">
						  	<div class="layout__item layout__item--body">
								<h2 class="layout_title_bg_l">North America's #1 used box provider.</h2>
								<p class="layout_text">
								   We've been in business for over 15 years (since 2006) perfecting our craft, investing in technology, and building an employment community of like-minded indivudals determined to make re-use sexier than buying brand new manufactured boxes.
								</p>
						  	</div>
						  	<div class="layout__item layout__item--figure">
								<img src="images/why_boomerang/usedboxprovider.png" alt="">
						  	</div>
						</div>
						<div class="layout boomerang_div_white">
						  <div class="layout__item_r layout__item--body">
							<h2 class="layout_title_bg_r">Save Money.</h2>
							<p class="layout_text_r">
							   Used boxes are up to 75% cheaper than buying brand new boxes of comparable spec. Advanced geolocating prioritizes boxes which are closest to you, saving you thousands on freight.
							</p>
						  </div>
						  <div class="layout__item layout__item--figure">
							<img src="images/why_boomerang/cheaper_than_new.png" alt="">
						  </div>
						</div>
						<div class="layout">
						  	<div class="layout__item layout__item--body">
								<h2 class="layout_title_bg_l">Convenience.</h2>
								<p class="layout_text">
								    Access to the entire national used box marketplace is in the palm of your hand, availability updated in real-time, and ordered with a tap of your finger. Easily cost compare different options which best suit your specific packaging needs. Delivery freight quotes calculate instantly in real-time.
								</p>
						  	</div>
						  	<div class="layout__item layout__item--figure">
								<img src="images/why_boomerang/convenience.png" alt="">
						  	</div>
						</div>
						<div class="layout boomerang_div_white">
						  <div class="layout__item_r layout__item--body">
							<h2 class="layout_title_bg_r">Eco-friendly.</h2>
							<p class="layout_text_r">
							    UsedCardboardBoxes doesn't cut down any trees. Instead, we "rescue" truckloads of quality used boxes from large companies who unpack millions of boxes per year that might otherwise throw them away. "You don't have to cut down a tree to make a used cardboard box."
							</p>
						  </div>
						  <div class="layout__item layout__item--figure">
							<img src="images/why_boomerang/eco-friendly.png" alt="">
						  </div>
						</div>
						<div class="layout">
						  	<div class="layout__item layout__item--body">
								<h2 class="layout_title_bg_l">Excellent customer service.</h2>
								<p class="layout_text">
								   Our entire business model is built on providing not just good customer service, but an overall impressive customer experience. From an easy to use website, to human beings that answer the phone when you call, we are driven to wow you with a great product AND service.
								</p>
						  	</div>
						  	<div class="layout__item layout__item--figure">
								<img src="images/why_boomerang/excellent_customer_service.png" alt="">
						  	</div>
						</div>
						<div class="layout boomerang_div_white">
						  <div class="layout__item_r layout__item--body">
							<h2 class="layout_title_bg_r">Satisfaction Guarantee.</h2>
							<p class="layout_text_r">
							    Every used box is backed by our 100% Description Guarantee*, which guarantees that the boxes will be as described and pictured. We understand buying a used box can be a scary proposition, and we are fully confident that if you give it a try, you'll be hooked! We're willing to put our money where our mouth is to prove it.
							</p>
						  </div>
						  <div class="layout__item layout__item--figure">
							<img src="images/why_boomerang/satisfaction_guarantee.png" alt="">
						  </div>
						</div>
						<div class="layout">
						  	<div class="layout__item layout__item--body">
								<h2 class="layout_title_bg_l">Global Reach.</h2>
								<p class="layout_text">
								   All our used boxes are sourced in North America, but can be delivered anywhere globally.
								</p>
						  	</div>
						  	<div class="layout__item layout__item--figure">
								<img src="images/why_boomerang/globa_reach.png" alt="">
						  	</div>
						</div>
						<div class="layout boomerang_div_white">
						  <div class="layout__item_r layout__item--body">
							<h2 class="layout_title_bg_r">We listen.</h2>
							<p class="layout_text_r">
							    You asked for it, we built it! Boomerang by UsedCardboardBoxes was built from a deep desire to meet our customer's needs in an evolving and complex marketplace. Most of the functionality was a direct result of finding solutions to common feedback through consultative conversations. This in turn manifested in Boomerang by UsedCardboardBoxes, which reduced barriers of entry and ease of leveraging the cost saving possibility of utilizing used over new.
							</p>
						  </div>
						  <div class="layout__item layout__item--figure">
							<img src="images/why_boomerang/we_listen.png" alt="">
						  </div>
						</div>
						
					</div>
				</div>
			</td>
		</tr>
		
	</table>
	<?
}
else{ ?> 
	<table width="100%">
		<tr >
		<!-- <td align="center"><h3>Home</h3></td> -->
		</tr>
		<!-- <tr >
		<td align="left">
			<table width="60%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
			<tbody>
			<tr class="headrow">
				<td colspan="5" align="center">Since 2010, UCB Has Processed...
				</td>
			</tr>
			<tr>
				<td align="center">Gaylords</td>
				<td align="center">Shipping Boxes</td>
				<td align="center">Pallets</td>
				<td align="center">Supersacks</td>
				<td align="center">Trees Saved</td>
			</tr>
			<?
			$box_cnt_qry	= "SELECT * FROM loop_box_counter";
			$boxcnt_res		= db_query($box_cnt_qry,db());
			$boxcnt_rows	= array_shift($boxcnt_res);
			$sb_qty = $boxcnt_rows["sb_qty"] + $boxcnt_rows["b2c_shipping_box_qty"];
			$greensave_sql		= 	"SELECT tree_counter.trees_saved AS saveone, tree_counter_b2b.trees_saved AS save2 FROM tree_counter,tree_counter_b2b";
			$greensave_query 	= 	db_query($greensave_sql);
			$greensave_result 	= 	array_shift($greensave_query);
			$save1 				= 	$greensave_result['saveone'];
			$save2 				= 	$greensave_result['save2'];
			$dTotalSave			=	$save1+$save2;
			$dTotalSave			=	number_format($dTotalSave, 0, '.', ',');
			?>
			<tr bgcolor="#dcdcdc">
				<td align="center"><? echo number_format($boxcnt_rows["gy_qty"]); ?></td>
				<td align="center"><? echo number_format($sb_qty); ?></td>
				<td align="center"><? echo number_format($boxcnt_rows["pal_qty"]); ?></td>
				<td align="center"><? echo number_format($boxcnt_rows["sup_qty"]); ?></td>
				<td align="center"><? echo $dTotalSave; ?></td>
			</tr>
			</tbody>
			</table>
		</td>
		</tr> -->
		<tr >
		<td align="left">
			<?
			$resComp = db_query("SELECT ID, loopid, dateCreated FROM companyInfo WHERE ID = '".$client_companyid . "'", db_b2b());
			$rowComp = array_shift($resComp);

			if ($rowComp['loopid'] > 0){
				$resLastTransDt1 = db_query("SELECT transaction_date FROM loop_transaction_buyer WHERE warehouse_id = ".$rowComp['loopid']." and `ignore` = 0 ORDER BY transaction_date DESC LIMIT 1", db());
				$rowLastTransDt1 = array_shift($resLastTransDt1);
			
				$resLastTransDt = db_query("Select STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') as bol_shipment_received_date from loop_bol_files inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.warehouse_id = ".$rowComp['loopid']." and `ignore` = 0 and loop_bol_files.bol_shipped = 1 ORDER BY STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') desc limit 1", db());
				$rowLastTransDt = array_shift($resLastTransDt);

				$resLTBCnt = db_query("SELECT count(ID) AS cnt FROM loop_transaction_buyer WHERE warehouse_id = ".$rowComp['loopid'] . " and `ignore` = 0", db());
				$rowLTBCnt = array_shift($resLTBCnt);

				$resTotInvAmt = db_query("SELECT SUM(inv_amount) AS totalInvAmt FROM loop_transaction_buyer WHERE `ignore` = 0 and warehouse_id = ".$rowComp['loopid'], db());
				$rowTotInvAmt = array_shift($resTotInvAmt);
			}
			db();
			?>
			<table width="40%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
				<tbody>
				<tr class="headrow">
					<td colspan="2" align="center">Account summary
					</td>
				</tr>
				<tr bgcolor="#dcdcdc">
					<td width="25%">Account Opened</td>
					<td width="15%"><?=date('m/d/Y', strtotime($rowComp["dateCreated"]));?></td>
				</tr>
				<tr bgcolor="#f7f7f7">
					<td>Last Puchase Date</td>
					<td><? 
					if ($rowComp['loopid'] > 0){
						echo date('m/d/Y', strtotime($rowLastTransDt1["transaction_date"]));
					}	
					?></td>
				</tr>
				<tr bgcolor="#dcdcdc">
					<td>Last Delivery Date</td>
					<td>
						<?
						if(!empty($rowLastTransDt["bol_shipment_received_date"])){
							echo date('m/d/Y', strtotime($rowLastTransDt["bol_shipment_received_date"]));
						}					
						?>						
					</td>
				</tr>
				<tr bgcolor="#f7f7f7">
					<td>Total Transactions</td>
					<td><?
					if ($rowComp['loopid'] > 0){
						echo $rowLTBCnt['cnt'];
					}	
					?></td>
				</tr>
				<tr bgcolor="#dcdcdc">
					<td>Total Purchased</td>
					<? if ($rowComp['loopid'] > 0){ ?>
						<td>$<? echo number_format(str_replace(",", "",$rowTotInvAmt['totalInvAmt']),0);?></td>
					<? } else {?>
						<td>&nbsp;</td>					
					<? } ?>
				</tr>									
				</tbody>
			</table>
		</td>
		</tr>

		<tr >
		<td align="left">
			<?

			$clientdash_flg = 1;			
			/*<!-- gaylord quote  -->*/
			$gCount = get_quote_gaylord_count($clientdash_flg, 1, $client_companyid);
			/*<!-- shipping quote -->*/
			$sCount = get_quote_shipping_count($clientdash_flg, 2, $client_companyid);
			/*<!-- supersack quote -->*/
			$supCount = get_quote_supersacks_count($clientdash_flg, 3, $client_companyid);	
			/*<!-- pallet quote -->*/
			$pCount = get_quote_pallets_count($clientdash_flg, 4, $client_companyid);	
			
			$total_box_profile_cnt = $gCount + $sCount + $supCount + $pCount;
			
			if($gCount == 0 && $sCount == 0 && $supCount == 0 && $pCount == 0  && $show_boxprofile_inv == 'yes'){
				?>

				<table width="100%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
					<tbody>
						<tr>
							<td><img style="vertical-align: middle;" src="images/exclamation-mark.png" /> <span style="color:red">You do not have any box profiles setup, which are required to view UCB's National, Real-Time Inventory database.</span></td>
						</tr>
						<tr>
							<td ><a href="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=box_profile<?=$repchk_str?>" class="btnStyle">Setup Box Profiles</a></td>
						</tr>											
					</tbody>
				</table>
				<?
			}else{
				if ($show_boxprofile_inv == 'yes'){
				?>
			
				<table width="20%" cellspacing="1" cellpadding="1"border="0" class="mb-10 tableBorder">
					<tbody>
					<tr class="headrow">
						<td colspan="2" align="center">Box profile summary
						</td>
					</tr>
					<tr bgcolor="#dcdcdc">
						<td width="80%">Gaylord</td>
						<td width="20%"><a href="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=box_profile<?=$repchk_str?>" onclick="show_loading()"><?=$gCount;?></a></td>
					</tr>
					<tr bgcolor="#f7f7f7">
						<td>Shipping Boxes</td>
						<td><a href="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=box_profile<?=$repchk_str?>" onclick="show_loading()"><?=$sCount?></a></td>
					</tr>
					<tr bgcolor="#dcdcdc">
						<td>Supersacks</td>
						<td><a href="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=box_profile<?=$repchk_str?>" onclick="show_loading()"><?=$supCount?></a></td>
					</tr>
					<tr bgcolor="#f7f7f7">
						<td>Pallets</td>
						<td><a href="client_dashboard_new.php?compnewid=<?=urlencode(encrypt_password($client_companyid));?>&show=box_profile<?=$repchk_str?>" onclick="show_loading()"><?=$pCount?></a></td>
					</tr>
					<tr bgcolor="#dcdcdc">
						<td>Total</td>
						<td><? echo $total_box_profile_cnt;?></td>
					</tr>									
					</tbody>
				</table>
				<?
				}
			}
			?>	
		</td>
		</tr>
	</table>
<? }?>	
	</div>	
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>						
					</table>
				</div>
			</div>
		</div>
	</main>
	<footer>
		<div class="footer_l">
			<div class="copytxt">© UsedCardboardBoxes</div>
		</div>
		<div class="text-center">
          <span class="header_need_help">Need Help?</span>
          <span class="fontDtls">
            <span>UCB Account Rep: Admin</span>
            <span>Email: <a href="mailto:ucbemail@usedcardboardboxes.com">ucbemail@usedcardboardboxes.com</a></span>
            <span>Office Number: 1-323-724-2500&nbsp;x701</span>
          </span>
      </div>
	</footer>
</body>

</html>
