<? 
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

function encrypt_password($txt){
	$key = "1sw54@$sa$offj";

	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
	return $ciphertext;
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Boomerang Portal Setup</title>

<script language="javascript">

	function clientdash_chkfrm() {
		var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var clientdash_username = document.getElementById('clientdash_username');
		var clientdash_pwd = document.getElementById('clientdash_pwd');
		if (clientdash_username.value == '') {
			alert("Please enter the Email as User name.");
			clientdash_username.focus();
			return false;
		}else if(mailformat.test(clientdash_username.value) == false){
			alert("Enter valid email as User name!");
			clientdash_username.focus();
			return false;
		}else if (clientdash_pwd.value == '') {
			alert("Please enter the Password.");
			clientdash_pwd.focus();
			return false;
		}/*else if (document.getElementById('clientdash_eml').value == '') {
			alert("Please enter the Client Email.");
			document.getElementById('clientdash_eml').focus();
			return false;
		}*/ else { document.clientdash_adduser.submit(); return true; }
		
	}
	
	function favitem_remove(favitemid, companyid) {
		document.location = "clientdashboard_setup.php?ID=" + companyid + "&favitemid=" + favitemid + "&favremoveflg=yes";
	}
	
	function handleActive(loginid, id) { 
		var mailformat = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var clientdash_username_edit = document.getElementById('clientdash_username_edit'+loginid);
		if (clientdash_username_edit.value == '') {
			alert("Please enter the Email as User name.");
			clientdash_username_edit.focus();
			return false;
		}else if(mailformat.test(clientdash_username_edit.value) == false){
			alert("Enter valid email as User name!");
			clientdash_username_edit.focus();
			return false;
		}

		var checkbox = document.getElementById('clientdash_flg'+loginid).checked;
		if (checkbox){
			var alertval = confirm("Are you sure you want to 'activate' the customer user.");
		}else{
			var alertval = confirm("Are you sure you want to 'deactivate' the customer user.");
		}		
		if (alertval) {
			var clientdash_pwd_edit = document.getElementById('clientdash_pwd_edit'+loginid).value;
			var clientdash_user_edit = document.getElementById('clientdash_username_edit'+loginid).value;
			
			var useraction_flg = 1;
			if (checkbox){
				var alertval = confirm("Username will be 'activated' from this record. Do you wish to also 'activate' this same username from all connected records as well?");
			}else{
				var alertval = confirm("Username will be 'deactivated' from this record. Do you wish to also 'deactivate' this same username from all connected records as well?");
			}			
			if (alertval) {
				var useraction_flg = 2;
			}
			
			document.location = "clientdashboard_user_status.php?loginid=" + loginid + "&useraction_flg=" + useraction_flg + "&companyid=" + id +"&checkbox="+checkbox+"&status=handleActive&clientdash_pwd_edit="+clientdash_pwd_edit+"&usnm="+clientdash_user_edit;
			return true; 
		}else {
			return false; 
		}		
	}
	
	function closed_loop_item_remove(favitemid, companyid) {
		document.location = "clientdashboard_setup.php?ID=" + companyid + "&favitemid=" + favitemid + "&closed_loop_item_removeflg=yes";
	}
	
	
	function show_closed_loop_inv(companyid) {
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
				alert("'Show this section in Front End' - Updated.");
			}
		}
		
		var chkclosed_inv = 0;
		if (document.getElementById('div_closed_inv').checked){
			var chkclosed_inv = 1;
		}
		
		xmlhttp.open("GET","update_closed_loop_inv_flg.php?companyid=" + companyid + "&chkclosed_inv=" + chkclosed_inv, true);
		xmlhttp.send();				
	}
	
	function clientdash_edit(loginid, id) {
		if (document.getElementById('clientdash_username_edit'+ loginid).value == '') {
			alert("Please enter the User name.");
			return false;
		}else if (document.getElementById('clientdash_pwd_edit'+ loginid).value == '') {
			alert("Please enter the Password.");
			return false;
		} else { 
			var chknewval = 0;
			if (document.getElementById('clientdash_flg' + loginid).checked){
				var chknewval = 1;
			}
			document.location = "clientdashboard_edituser.php?chkval=" + chknewval + "&usernm=" + document.getElementById('clientdash_username_edit' + loginid).value + "&pwd=" + document.getElementById('clientdash_pwd_edit' + loginid).value + "&clientdash_eml=" + document.getElementById('clientdash_eml_edit' + loginid).value + "&loginid=" + loginid + "&companyid=" + id ;
			
			//document.getElementById('user_edit_id').value = loginid;
			//document.clientdash_edituser.submit(); return true; 
		}
	}
	

	function clientdash_dele(loginid, id) {
		var alertval = confirm("Are you sure you want to delete the client user.");
		if (alertval) {
			var useraction_flg = 1;
			var alertval2 = confirm("Username will be deleted from this record. Do you wish to also delete this same username from all connected records as well?");
			if (alertval2) {
				var useraction_flg = 2;
			}
		
			document.location = "clientdashboard_deluser.php?loginid=" + loginid + "&companyid=" + id + "&useraction_flg=" + useraction_flg;
			return true; 
		}
	}
	
	function clientdash_sec_edit(sectionid, id) {
		var chknewval = 0;
		if (document.getElementById('clientdash_sec_flg' + sectionid).checked){
			var chknewval = 1;
		}
		document.location = "clientdashboard_edit_sec.php?clientdash_flg_sec=" + chknewval + "&sectionid=" + sectionid + "&companyid=" + id ;
	}
	
	function clientdash_sec_dele(sectionid, id) {
		var alertval = confirm("Are you sure you want to delete the record.");
		if (alertval) {
			document.location = "clientdashboard_del_sec.php?sectionid=" + sectionid + "&companyid=" + id ;
			return true; 
		}
	}	
	
	function clientdash_sec_col_edit(section_col_id, id) {
		var chknewval = 0;
		if (document.getElementById('clientdash_sec_col_flg' + section_col_id).checked){
			var chknewval = 1;
		}
		document.location = "clientdashboard_col_edit_sec.php?clientdash_flg_sec=" + chknewval + "&section_col_id=" + section_col_id + "&companyid=" + id ;
	}

	function clientdash_sec_list_edit(section_list_id, id) {
		var chknewval = 0;
		if (document.getElementById('clientdash_sec_list_flg' + section_list_id).checked){
			var chknewval = 1;
		}
		document.location = "clientdashboard_list_edit_sec.php?clientdash_flg_sec=" + chknewval + "&section_list_id=" + section_list_id + "&companyid=" + id ;
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
	
	function add_inventory_to_favorite(compId){ 
		var selectobject = document.getElementById("btnAddFavoriteInv"); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display='block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';
		
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

		var sstr = "";		
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";
		
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("light").innerHTML = sstr + xmlhttp.responseText; 
		  }
		}
		xmlhttp.open("GET","getBoxData.php?ID="+compId,true);			
		xmlhttp.send();/**/
	}


	function add_inventory_to_hide(compId){ 
		var selectobject = document.getElementById("btnAddHideInv"); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display='block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';
		
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

		var sstr = "";		
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";
		
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("light").innerHTML = sstr + xmlhttp.responseText; 
		  }
		}
		xmlhttp.open("GET","getBoxDataHide.php?ID="+compId,true);			
		xmlhttp.send();
	}

	function Remove_boxes_hide(compId, favB2bId) { 
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == 'done'){
				alert('Remove successfully. Need to reload the page after all boxes are updated.');
				document.location.reload(true);
			}
		  }
		}

		xmlhttp.open("GET","update_hide_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=2",true);			
		xmlhttp.send();	
	}
	
	function Add_boxes_hide(compId, favB2bId) { 
		
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == 'done'){
				alert('Added successfully. Need to reload the page after all boxes are updated.');
				document.location.reload(true);
			}
		  }
		}

		xmlhttp.open("GET","update_hide_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=1",true);			
		xmlhttp.send();
		
	}

	function hideitem_remove(hideitemid, companyid) {
		document.location = "clientdashboard_setup.php?ID=" + companyid + "&hideitemid=" + hideitemid + "&hideremoveflg=yes";
	}

	function Remove_boxes_warehouse_data(compId, favB2bId, closeloop = 0) { 
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == 'done'){
				alert('Remove successfully. Need to reload the page after all boxes are updated.');
				//document.getElementById('light').style.display='none';
				//window.location.replace("https://loops.usedcardboardboxes.com/clientdashboard_setup.php?ID="+compId);
			}
		  }
		}
		if(closeloop == 1){
			xmlhttp.open("GET","update_closeloop_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=2",true);			
			xmlhttp.send();
		}else{
			xmlhttp.open("GET","update_favorite_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=2",true);			
			xmlhttp.send();	
		}
	}
	
	function Add_boxes_warehouse_data(compId, favB2bId, closeloop = 0) { 
		
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText == 'done'){
				alert('Added successfully. Need to reload the page after all boxes are updated.');
				//document.getElementById('light').style.display='none';
				//window.location.replace("https://loops.usedcardboardboxes.com/clientdashboard_setup.php?ID="+compId);
			}
		  }
		}
		if(closeloop == 1){
			xmlhttp.open("GET","update_closeloop_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=1",true);			
			xmlhttp.send();
		}else{
			xmlhttp.open("GET","update_favorite_inventory_data.php?compId="+compId+"&favB2bId="+favB2bId+"&upd_action=1",true);			
			xmlhttp.send();
		}
			
	}

	function add_inventory_to_closeloop(compId){ //alert('compId -> '+compId)
		var selectobject = document.getElementById("btnAddCloseloopInv"); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light').style.display='block';
		document.getElementById('light').style.left = n_left + 50 + 'px';
		document.getElementById('light').style.top = n_top + 20 + 'px';
		
		document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

		var sstr = "";		
		sstr = "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
		sstr = sstr + "<br>";
		
		if (window.XMLHttpRequest) {
		  xmlhttp=new XMLHttpRequest();
		} else {
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("light").innerHTML = sstr + xmlhttp.responseText; 
		  }
		}
		xmlhttp.open("GET","getBoxDataCloseloop.php?ID="+compId,true);			
		xmlhttp.send();
	}

	function handleSetuphide(id){

		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}else{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				alert("Record Updated.");
			}
		}
		
		var setuphide_flg = 0;
		if (document.getElementById('clientdash_setup_hide').checked){
			var setuphide_flg = 1;
		}
	
		var boxprofileinv_flg = 0;
		if (document.getElementById('clientdash_boxprofile_inv_hide').checked){
			var boxprofileinv_flg = 1;
		}
		
		var setupfamily_tree = 0;
		if (document.getElementById('clientdash_family_tree')){
			if (document.getElementById('clientdash_family_tree').checked){
				var setupfamily_tree = 1;
			}
		}
		
		xmlhttp.open("GET","update_setup_hide_flg.php?companyid=" + id + "&setuphide_flg=" + setuphide_flg  + "&boxprofileinv_flg=" + boxprofileinv_flg + "&setupfamily_tree=" + setupfamily_tree  , true);
		xmlhttp.send();
	}
	
	function handleBoxprofileInvhide(id){
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}else{
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				alert("Record Updated.");
			}
		}
		

		
		xmlhttp.open("GET","update_boxPinv_hide_flg.php?companyid=" + id + "&boxprofileinv_flg=" + boxprofileinv_flg, true);
		xmlhttp.send();
	}

		
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.tooltip {
	    position: relative;
	    display: inline-block;
	}
	.fa-info-circle {
	    font-size: 9px;
	    color: #767676;
	}
	.fa {
	    display: inline-block;
	    font: normal normal normal 14px/1 FontAwesome;
	    font-size: inherit;
	    text-rendering: auto;
	    -webkit-font-smoothing: antialiased;
	    -moz-osx-font-smoothing: grayscale;
	}
	.tooltip .tooltiptext {
	    visibility: hidden;
	    width: 250px;
	    background-color: #464646;
	    color: #fff;
	    text-align: left;
	    border-radius: 6px;
	    padding: 5px 7px;
	    position: absolute;
	    z-index: 1;
	    top: -5px;
	    left: 110%;
	    /* white-space: nowrap; */
	    font-size: 12px;
	    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important;
	}
	.tooltip .tooltiptext::after {
	    content: "";
	    position: absolute;
	    top: 35%;
	    right: 100%;
	    margin-top: -5px;
	    border-width: 5px;
	    border-style: solid;
	    border-color: transparent black transparent transparent;
	}

	.tooltip:hover .tooltiptext {
	    visibility: visible;
	}

	.tooltip_large {
	    position: relative;
	    display: inline-block;
	}

	.tooltip_large .tooltiptext_large {
	  visibility: hidden;
	  width: 400px;
	  background-color: #464646;
	  color: #fff;
	  text-align: center;
	  border-radius: 6px;
	  padding: 5px 7px;
	  position: absolute;
	  z-index: 1;
	  top: -5px;
	  left: 110%;
	}

	.tooltip_large .tooltiptext_large::after {
	    content: "";
	    position: absolute;
	    top: 10%;
	    right: 100%;
	    margin-top: -5px;
	    border-width: 5px;
	    border-style: solid;
	    border-color: transparent black transparent transparent;
	}

	.tooltip_large:hover .tooltiptext_large {
	    visibility: visible;
	}
	/*right tip*/

	.tooltip_right {
	    position: relative;
	    display: inline-block;
	}

	.tooltip_right .tooltiptext_right {
	    visibility: hidden;
	    width: 250px;
	    background-color: black;
	    color: #fff;
	    text-align: center;
	    border-radius: 6px;
	    padding: 5px 7px;
	    position: absolute;
	    z-index: 1;
	    top: -5px;
	    right: 110%;
	  font-size: 11px;
	}

	.tooltip_right .tooltiptext_right::after {
	  content: " ";
	  position: absolute;
	  top: 30%;
	  left: 100%; /* To the right of the tooltip */
	  margin-top: -5px;
	  border-width: 5px;
	  border-style: solid;
	  border-color: transparent transparent transparent black;
	}

	.tooltip_right:hover .tooltiptext_right {
	  visibility: visible;
	}
	/*--------*/

	.fa-info-circle{
	  font-size: 9px;
	  color: #767676;
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
	.textbox-label{
		background: transperant;
		border: none;
		width: 300px;
		min-width: 90px;
		max-width: 300px;
		transition: width 0.25s;  
	}
</style>
	<LINK rel='stylesheet' type='text/css' href='one_style.css' >
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
</head>
<?
	$ID = $_REQUEST["ID"];
	$account_owner = 0;
	$company_log = "";
	$res = db_query("Select * from clientdashboard_details where companyid = $ID" , db()); 
	while($fetch_data=array_shift($res))
	{
		$account_owner = $fetch_data["accountmanager_empid"];
		$company_log = $fetch_data["logo_image"];
	}
	
	if ($account_owner == 0) 
	{
		$res = db_query("Select assignedto from companyInfo where ID = $ID" , db_b2b()); 
		while($fetch_data=array_shift($res))
		{
			$account_owner = $fetch_data["assignedto"];
		}

		//$res = db_query("Insert into clientdashboard_details ( companyid, accountmanager_empid, logo_image) values ($ID, $account_owner, '')" , db()); 
	}

	$res = db_query("Select haveNeed, email, parent_child from companyInfo where ID = $ID" , db_b2b()); 
	$buyer_seller_flg = 0; $client_eml = ""; $parent_flg = "";
	while($fetch_data=array_shift($res))
	{
		if ($fetch_data["haveNeed"] == "Need Boxes") {
			$buyer_seller_flg = 0;
		}else {
			$buyer_seller_flg = 1;
		}
		$client_eml = $fetch_data["email"];
		$parent_flg = $fetch_data["parent_child"];
	}

	//echo "<br /> buyer_seller_flg - ".$buyer_seller_flg;
	$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 6 and activate_deactivate = 1" , db()); 
	$close_inv_flg = 0;
	while($fetch_data =array_shift($res))
	{
		$close_inv_flg = 1;
	}

	$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 7 and activate_deactivate = 1" , db()); 
	$setup_hide_flg = 0;
	while($fetch_data =array_shift($res))
	{
		$setup_hide_flg = 1;
	}


	$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 8 and activate_deactivate = 1" , db()); 
	$setup_boxprofile_inv_flg = 0;
	while($fetch_data =array_shift($res))
	{
		$setup_boxprofile_inv_flg = 1;
	}
	
	$setup_family_tree = 0; 
	$res = db_query("Select activate_deactivate from clientdashboard_section_details where companyid = $ID and section_id = 9 and activate_deactivate = 1" , db());
	while($fetch_data = array_shift($res)){
		$setup_family_tree = 1;
	}
	
	if ($buyer_seller_flg == 0) {
		$res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 1" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 1, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 1, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 2, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 3, 1)" , db()); 
		}

		$res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 2" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 2, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 4, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 5, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 6, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 7, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 8, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 9, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 10, 1)" , db()); 
			$res = db_query("Insert into clientdashboard_section_col_details ( companyid, section_col_id, displayflg) values ($ID, 11, 1)" , db()); 
		}

		$res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 3" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 3, 1)" , db()); 
		}
		
		$res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 4" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 4, 1)" , db()); 
		}
		
		$res = db_query("Select section_id from clientdashboard_section_details where companyid = $ID and section_id = 5" , db()); 
		$rec_found = "no";
		while($fetch_data=array_shift($res))
		{
			$rec_found = "yes";
		}
		if ($rec_found == "no") {
			$res = db_query("Insert into clientdashboard_section_details ( companyid, section_id, activate_deactivate) values ($ID, 5, 1)" , db()); 
		}
	}

	function get_loop_box_id($b2b_id){
		$dt_so = "SELECT * FROM loop_boxes WHERE b2b_id = " . $b2b_id;
		$dt_res_so = db_query($dt_so,db() );

		while ($so_row = array_shift($dt_res_so)) {
		if ($so_row["id"] > 0) 
		return $so_row["id"];
		}
	}	
?>
<body>
	<? include("inc/header.php"); ?>
	<div class="main_data_css">
		<div style="height: 13px;">&nbsp;</div>		
		<div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
			<img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;	
			<a href="viewCompany.php?ID=<?=$ID?>">View B2B page</a> &nbsp;&nbsp;
			<a target="_blank" href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?=$ID?>&repchk=yes">Old Client dash</a> &nbsp;&nbsp;
			<a target="_blank" href="https://boomerang.usedcardboardboxes.com/client_dashboard.php?compnewid=<? echo urlencode(encrypt_password($ID));?>&repchk=yes&repchk_from_setup=yes&userid=<? echo $_COOKIE["employeeid"]?>">
			View Boomerang Portal</a>
			<font color=red size=1>*Do NOT give this link out to customers! It is a "back door" to the portal ONLY FOR YOU!</font>
		</div>
			
			<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">
			 <? $comp_name = get_nickname_val('', $ID);
				echo $comp_name;
			?>
			</div>

			&nbsp;<!--<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
			<span class="tooltiptext">&nbsp;</span></div>-->
			<div style="height: 13px;">&nbsp;</div>			
		</div>
		
		
	<div id="light" class="white_content">	</div>


	<!--<center><h3><? //$comp_name = get_nickname_val('', $ID);
		//echo $comp_name;
	?></h3>
	</center>-->
	
	<table border="0" bgcolor="#F6F8E5" align="center" width="85%" style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
		<tr align="center">
			<td colspan="6" width="320px" bgcolor="#E8EEA8"><strong>Boomerang Portal Setup</strong></font></td>
		</tr>
		<form method="post" name="clientdash_adduser" action="clientdashboard_adduser.php" >
			<input type="hidden" name="hidden_companyid" value="<? echo $ID; ?>" />
			
			<? if (isset($_REQUEST["duprec"])) { 
			if ($_REQUEST["duprec"] == "yes") { 
				
				$res = db_query("SELECT companyid FROM clientdashboard_usermaster WHERE user_name = '" . $_REQUEST["usrnm"]. "'"  , db()); 
				
				$fetch_data=array_shift($res);
				$cid=$fetch_data["companyid"];
				
				$ures = db_query("SELECT company, ID FROM companyInfo WHERE ID = '" . $cid . "'"  , db_b2b());
				$ufetch_data=array_shift($ures);
				//echo "old  name--".$cid."<br>";
				$usr_company_name = get_nickname_val($ufetch_data["company"], $ufetch_data["ID"]);
				
			?>
			<tr align="center">
				<td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
					This username already exists for <strong style="font-size: 13px;"><a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=<? echo $ufetch_data["ID"]; ?>' target="_blank" ><? echo $usr_company_name; ?></a></strong>, add their username to this location as well?<br> <a href="clientdashboard_adduser.php?hidden_companyid=<? echo $ID;?>&clientdash_username=<? echo $_REQUEST["usrnm"]?>&existing=new" style="color:#FFFFFF;">Yes</a> &nbsp;&nbsp;&nbsp; <a href="clientdashboard_setup.php?ID=<? echo $ID;?>&dupl_recheck=yes"  style="color:#FFFFFF;">No</a>
					
					<!--User name already exists, record not added.--></td>
			</tr>
			<? }
			} ?>
			<? if (isset($_REQUEST["dupl_recheck"])) { 
				if ($_REQUEST["dupl_recheck"] == "yes") { 
			?>
			<tr align="center">
				<td colspan="6" width="960px" align="left" bgcolor="red" style="padding-left: 10px; color:#FFFFFF;">
				User name already exists, record not added.
				</td>
			</tr>
			<?
				}
			}
			?>
			
			<tr align="center">
				<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Add new user for Customer</td>
			</tr>
			<tr align="center">
				<td align="left" width="180px" >Email for login: </td>
				<td colspan="5" width="320px" align="left"><input type="text" name="clientdash_username" id="clientdash_username" value="" /></td>
			</tr>
			<tr align="center">
				<td align="left" width="180px" >Password: </td>
				<td colspan="5" width="320px" align="left"><input type="password" name="clientdash_pwd" id="clientdash_pwd" value="" /></td>
			</tr>
			<!-- <tr align="center">
				<td width="80px" >Email: </td>
				<td colspan="5" width="320px" align="left"><input type="text" name="clientdash_eml" id="clientdash_eml" value="<? echo $client_eml;?>" /></td>
			</tr> -->
			<tr align="center">
				<td width="80px" >&nbsp;</td>
				<td colspan="5" width="320px" align="left"><input type="button" name="clientdash_adduser" value="Add" onclick="clientdash_chkfrm()" /></td>
			</tr>
		</form>
		<form name="frmClientDashboard" method="post" action="frmClientDashboardSave.php" encType="multipart/form-data">
			<input type="hidden" name="user_edit" id="user_edit" value="yes" />
				
			<tr align="center">
				<td colspan="6" width="320px" align="left" bgcolor="">Customer user list</td>
			</tr>
			<tr align="center">
				<td width="80px" >User name (Email address)</td>
				<td width="80px" align="left">Password</td>
				<td width="100px" align="left">Activate/Deactivate<br>
				<font size=1><i>Click on checkbox to update Activate/Deactivate user</i></font></td>
				<td width="100px" align="left">Delete</td>
				<td width="100px" align="left">&nbsp;</td>
			</tr>
			<?
			$qry ="Select * From clientdashboard_usermaster Where companyid = $ID";
			$res = db_query($qry , db());
			while($fetch_data=array_shift($res))
			{
			?>
				<input type="hidden" name="loginid" id="loginid" value="<? echo $fetch_data["loginid"];?>" />
				<input type="hidden" name="ID" id="ID" value="<? echo $ID;?>" />

				<tr align="center">
					<td width="80px" ><input type="text" name="clientdash_username_edit" id="clientdash_username_edit<? echo $fetch_data["loginid"];?>" class="textbox-label" value="<? echo $fetch_data["user_name"];?>" disabled /></td>
					<td width="80px" align="left"><input type="password" name="clientdash_pwd_edit" id="clientdash_pwd_edit<? echo $fetch_data["loginid"];?>" value="<? echo $fetch_data["password"];?>" /></td>
					<!-- <td width="100px" align="left"><input type="checkbox" name="clientdash_flg" id="clientdash_flg<? echo $fetch_data["loginid"];?>" <? if ($fetch_data["activate_deactivate"] == 1) { echo " checked "; }?> onchange='handleActive(<? echo $fetch_data["loginid"];?>, <? echo $ID;?>);'/></td> -->
					<td width="100px" align="left"><input type="checkbox" name="clientdash_flg" id="clientdash_flg<? echo $fetch_data["loginid"];?>" <? if ($fetch_data["activate_deactivate"] == 1) { echo " checked "; }?> /></td>
					<td width="100px" align="left"><input type="button" value="Delete" onclick="clientdash_dele(<? echo $fetch_data["loginid"];?>, <? echo $ID;?>)" /></td>
					<td width="80px" align="left"><input type="button" name="clientdash_eml_edit" id="clientdash_eml_edit" value="Submit" onclick='handleActive(<? echo $fetch_data["loginid"];?>, <? echo $ID;?>);' /></td>
					
					<td width="80px" align="left"><input type="hidden" name="clientdash_eml_edit" id="clientdash_eml_edit<? echo $fetch_data["loginid"];?>" value="" /></td>
				</tr>
			<?
			}
			?>
			<!-- <tr align="center">
				<td colspan="6" width="320px">
					<input type="button" name="clientdash_userEdit" value="Submit" onclick="onchange='clientdash_userEdit(<? echo $ID;?>);'" />
				</td>
			</tr> -->

		</form>
		
		<form name="frmClientDashboard_logo" method="post" action="frmClientDashboardSave.php" encType="multipart/form-data">
			<tr align="center">
				<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;
					<input type="hidden" name="hidden_companyid" value="<? echo $ID; ?>" />
					<input type="hidden" name="ID" id="ID" value="<? echo $ID;?>" />
				</td>
			</tr>
				<input type="hidden" name="clientdash_edituser_details_id" id="clientdash_edituser_details_id" value="<? echo $ID; ?>" />
				<tr align="center">
					<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Update Details</td>
				</tr>
				<!-- <tr align="center">
					<td width="140px" align="left" >Account owner</td>
					<td colspan="3" width="180px" align="left" ><select name="clientdash_acc_owner" id="clientdash_acc_owner" >
					<?	
						$res = db_query("Select employeeID, name from employees order by name" , db_b2b()); 
						
						$tmpvar = "";
						while($fetch_data=array_shift($res))
						{
							if ($fetch_data['employeeID'] == $account_owner ) { $tmpvar = " selected "; } else { $tmpvar = " "; }
							echo "<option value='" . $fetch_data["employeeID"] . "' $tmpvar >" . $fetch_data["name"] ." </option>";
						}
					?></select>
					</td>
				</tr> -->
				
				<tr align="center">
					<td width="140px" align="left" >Company Logo</td>
					<td colspan="3" width="180px" align="left"><input type="file" name="companylogo" /> <br/>
					<? if ($company_log != "") { 
							echo "Uploaded file: " . $company_log; ?> 
						<image src="clientdashboard_images/<?=$company_log;?>" width="100px" height="100px" style="object-fit: cover;"/>
					<? } ?> 
					</td>
				</tr>
				
				<?	
				/*$res = db_query("Select * from clientdashboard_globalvar where variable_name = 'tollfree_no'" , db()); 
				$tollfree_no = "";
				while($fetch_data=array_shift($res))
				{
					$tollfree_no = $fetch_data["variable_value"];
				}*/

				/*$res = db_query("Select * from clientdashboard_globalvar where variable_name = 'office_no'" , db()); 
				$office_no = "";
				while($fetch_data=array_shift($res))
				{
					$office_no = $fetch_data["variable_value"];
				}*/
				
				/*$res = db_query("Select phoneext from loop_employees where b2b_id = $account_owner" , db()); 
			    $office_no_ext = "";
				while($fetch_data=array_shift($res))
				{
					$office_no_ext = $fetch_data["phoneext"];
				}*/
				?>
				<!-- <tr align="center">
					<td width="140px" align="left" >UCB Toll Free number:</td>
					<td colspan="3" width="180px" align="left"><input type="text" name="tollfree_no" id="tollfree_no" value="<? echo $tollfree_no; ?>"/>
					</td>
				</tr> -->
				<!-- <tr align="center">
					<td width="140px" align="left" >UCB Office number:</td>
					<td colspan="3" width="180px" align="left"><input type="text" name="office_no" id="office_no" value="<? echo $office_no; ?>"/>
					</td>
				</tr> -->
				<!-- <tr align="center">
					<td width="140px" align="left" >UCB Office number ext.:</td>
					<td colspan="3" width="180px" align="left"><input type="text" name="office_ext" id="office_ext" value="<? echo $office_no_ext; ?>" disabled style="background:gray;"/>
					</td>
				</tr> -->
		
			<tr align="center">
				<td colspan="6" width="320px" align="left" >&nbsp;</td>
			</tr>
		
			<!-- Section list -->
				<!-- <tr align="center">
					<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Section list</td>
				</tr>
				<tr align="center">
					<td colspan="2" width="180px" align="left" bgcolor="#E4D5D5">Section name</td>
					<td width="100px" align="left" bgcolor="#E4D5D5">Activate/Deactivate</td>
					<!-- <td colspan="2" width="80px" align="left" bgcolor="#E4D5D5">Delete</td> --
				</tr>
				<input type="hidden" name="buyer_seller_flg" id="buyer_seller_flg" value="<? echo $buyer_seller_flg; ?>" />
				<?
				$qry ="SELECT clientdashboard_section_details.*, clientdashboard_section_master.section_name  FROM clientdashboard_section_details INNER JOIN clientdashboard_section_master ON clientdashboard_section_master.section_id = clientdashboard_section_details.section_id WHERE companyid = $ID AND buyer_seller = $buyer_seller_flg";
				//echo "<br /> ".$qry;
				$res = db_query($qry , db());
				while($fetch_data=array_shift($res))
				{
					?>
					<input type="hidden" name="section_id" id="section_id" value="<? echo $fetch_data["section_id"]; ?>" />
					<input type="hidden" name="companyid_sec" id="companyid_sec" value="<? echo $ID; ?>" />
				
					<tr align="center">
						<td colspan="2" width="180px" align="left"><? echo $fetch_data["section_name"];?></td>
						<td width="100px" align="left"><input type="checkbox" name="clientdash_sec_flg<? echo $fetch_data["section_id"];?>" id="clientdash_sec_flg<? echo $fetch_data["section_id"];?>" <? if ($fetch_data["activate_deactivate"] == 1) { echo " checked "; }?> /></td>
						<!-- <td colspan="2" width="80px" align="left"><input type="button" value="Delete" onclick="clientdash_sec_dele(<? echo $fetch_data["section_id"];?>, <? echo $ID;?>)" /></td> --
					</tr>
					<?
					// for the column in the section list
					$qry_2 ="Select * From clientdashboard_section_col_master where section_id = " . $fetch_data["section_id"] . " order by section_col_id";
					$res_2 = db_query($qry_2 , db());
					$reccnt = tep_db_num_rows($res_2);
					if ($reccnt > 0) { ?>
						<tr align="center">
							<td colspan="5">
							<table style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
							<tr align="center">
								<td colspan="2" width="180px" align="left" bgcolor="#E4D5D5" >Column name</td>
								<td width="100px" align="left" bgcolor="#E4D5D5">Display (Yes/No)</td>
							</tr>
							<?}
							while($fetch_data_2=array_shift($res_2))
							{
								$qry_3 ="Select * From clientdashboard_section_col_details where companyid = $ID and section_col_id = " . $fetch_data_2["section_col_id"];
								$res_3 = db_query($qry_3 , db());
								$section_col_flg = 0;
								while($fetch_data_3=array_shift($res_3))
								{
									$section_col_flg = $fetch_data_3["displayflg"];
								}
								?>
								<tr align="center">
									<td colspan="2" width="180px" align="left" ><? echo $fetch_data_2["column_name"]; ?></td>
									<td width="100px" align="left"><input type="checkbox" name="clientdash_sec_col_flg<? echo $fetch_data_2["section_col_id"];?>" id="clientdash_sec_col_flg<? echo $fetch_data_2["section_col_id"];?>" <? if ($section_col_flg == 1) { echo " checked "; }?> /></td>
								</tr>
								<?
							}
							if ($reccnt > 0) { ?>
								</table>
								</td>
						</tr>
						<tr><td colspan="5"><hr/></td></tr>
						<?}
						// for the column in the section list
						$qry_2 ="Select * From clientdashboard_section_list_master where section_id = " . $fetch_data["section_id"] . " order by section_list_id";
						$res_2 = db_query($qry_2 , db());
						$reccnt = tep_db_num_rows($res_2);
						if ($reccnt > 0) { ?>
							<tr align="center">
								<td colspan="5">
								<table style="font-family:Arial, Helvetica, sans-serif; font-size:12;">
								<tr align="center">
									<td colspan="2" width="180px" align="left" bgcolor="#E4D5D5" >List data</td>
									<td width="100px" align="left" bgcolor="#E4D5D5">Display (Yes/No)</td>
									<td width="40px" align="left" bgcolor="#E4D5D5">Edit</td>
								</tr>
						<?}
						while($fetch_data_2=array_shift($res_2))
						{
							$qry_3 ="Select * From clientdashboard_section_list_details where companyid = $ID and section_list_id = " . $fetch_data_2["section_list_id"];
							$res_3 = db_query($qry_3 , db());
							$section_list_flg = 0;
							while($fetch_data_3=array_shift($res_3))
							{
								$section_list_flg = $fetch_data_3["displayflg"];
							}
						?>
						<tr align="center">
							<td colspan="2" width="180px" align="left" ><? echo $fetch_data_2["section_list_data"]; ?></td>
							<td width="100px" align="left"><input type="checkbox" name="clientdash_sec_list_flg" id="clientdash_sec_list_flg<? echo $fetch_data_2["section_list_id"];?>" <? if ($section_list_flg == 1) { echo " checked "; }?> /></td>
							<td width="40px" align="left"><input type="button" value="Edit" onclick="clientdash_sec_list_edit(<? echo $fetch_data_2["section_list_id"];?>,<? echo $ID; ?>)" /></td>
						</tr>
						<?}
						if ($reccnt > 0) { ?>
							</table>
								</td>
							</tr>
							<tr><td colspan="5"><hr/></td></tr>
						<?}
						
					}
					?>
			
					<?	$res = db_query("Select * from clientdashboard_section_master where section_id not in (select section_id from clientdashboard_section_details where companyid = $ID) and buyer_seller = $buyer_seller_flg order by section_name" , db()); 
						if (tep_db_num_rows($res) > 0) {
					?>
					<input type="hidden" name="hidden_companyid_secadd" id="hidden_companyid_secadd" value="<? echo $ID; ?>" />
					<tr align="center">
						<td colspan="2" width="180px" align="left" ><select name="clientdash_section" id="clientdash_section" >
						<?	
							
							while($fetch_data=array_shift($res))
							{
								echo "<option value='" . $fetch_data["section_id"] . "'>" . $fetch_data["section_name"] ." </option>";
							}
						?></select>
						</td>
						<td width="100px" align="left">&nbsp;</td>
						<td colspan="2" width="120px" align="left"><input type="submit" value="Add" /></td>
					</tr>
				<? } ?> -->
			<!-- Section list -->
			<tr align="center">
				<td colspan="6" width="320px"><input type="submit" name="btn_clientdash_upd_logo" id="btn_clientdash_upd_logo" value="Submit" /></td>
			</tr>
		</form>	
		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		
		<!--Favorite Inventory section start  -->
		<?
		//if(isset($_REQUEST['hdnFavItemsAction']) && $_REQUEST['hdnFavItemsAction'] == 1 ){
		if(isset($_REQUEST['favremoveflg']) && $_REQUEST['favremoveflg'] == "yes" ){
		
			db_query("Delete from clientdash_favorite_items WHERE id =".$_REQUEST['favitemid'], db());
			
			/*$selFavIds = db_query("SELECT id FROM clientdash_favorite_items WHERE compid = ".$_REQUEST['ID']." ORDER BY id DESC", db());
			while ($rowsFavIds = array_shift($selFavIds)) {
				if(in_array($rowsFavIds['id'], $_REQUEST['favItemIds'] )){
					//echo "<br/> if -> "."UPDATE clientdash_favorite_items SET favItems = 1 WHERE id =".$rowsFavIds['id'];
					db_query("UPDATE clientdash_favorite_items SET favItems = 1 WHERE id =".$rowsFavIds['id'], db());
				}else{
					//echo "<br /> else -> "."UPDATE clientdash_favorite_items SET favItems = 0 WHERE id =".$rowsFavIds['id'];
					db_query("UPDATE clientdash_favorite_items SET favItems = 0 WHERE id =".$rowsFavIds['id'], db());
				}
			}*/
		}
		?>
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Favorite Inventory</td>
		</tr>
		<?
		$x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
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
		<!--
		<tr>   
			<td colspan="6" >
				<form name="frmFavItems" method="post" action="clientdashboard_setup.php?ID=<?=$_REQUEST['ID'];?>">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
					<?
					$selFavDt = db_query("SELECT * FROM clientdash_favorite_items WHERE compid = ".$_REQUEST['ID']." and favItems = 1 ORDER BY id DESC", db());
					//echo "<pre>";print_r($selFavDt);echo "</pre>";
					$selFavDtCnt = tep_db_num_rows($selFavDt);
					if($selFavDtCnt > 0 ){
						?>
						<tr bgcolor="#E4D5D5" >
							<td class='display_title'>&nbsp;</td>
							<td class='display_title'>Buy Now</td>
							<td class='display_title'>Qty Avail</td>		
							<td class='display_title'>Buy Now, Load Can Ship In</td>			
							<td class='display_title'>Expected # of Loads/Mo</td>			
							<td class='display_title'>Per Truckload</td>			
							<td class='display_title'>MIN FOB</td>
							<td class='display_title'>B2B ID</td>
							<td class='display_title'>Miles Away
								<div class="tooltip">
									<i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">Green Color - miles away <= 250, 
									<br>Orange Color - miles away <= 550 and > 250, 
									<br>Red Color - miles away > 550</span>
								</div>		
							</td>
							<td align="center" class='display_title'>B2B Status</td>
							<td align="center" class='display_title'>L</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>W</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>H</td>	
							<td class='display_title'>Description</td>	
							<td class='display_title'>Supplier</td>	
							<td class='display_title'>Ship From</td>
							<td class='display_title'>Supplier Relationship Owner</td>
							<td class=''>Box Type</td>
						</tr>
						<?		
						$i = 0;
						while ($rowsFavDt = array_shift($selFavDt)) {
							if ($i % 2 == 0){ $rowclr = '#dcdcdc';  }else{ $rowclr = '#f7f7f7'; }
							?>
							<tr bgcolor="<?=$rowclr?>" >	
							<td class=''>
								<? if ($rowsFavDt['favItems'] == 1) { ?><input type="button" name="favItemIds" id="favItemIds<?=$rowsFavDt['id'];?>" value="Remove" onclick="favitem_remove(<?=$rowsFavDt['id'];?>, <?=$_REQUEST['ID'];?>)"> <? }?>
							</td>				
							<td class=''><a href='https://b2b.usedcardboardboxes.com/?id=<?=get_loop_box_id($rowsFavDt["fav_b2bid"])?>' target='_blank' >Buy Now</a></td>
							<td class=''><?=$rowsFavDt['fav_qty_avail']?></td>		
							<td class=''><?=$rowsFavDt['fav_estimated_next_load']?></td>			
							<td class=''><?=$rowsFavDt['fav_expected_loads_per_mo']?></td>			
							<td class=''><?=$rowsFavDt['fav_boxes_per_trailer']?></td>			
							<td class=''><?=$rowsFavDt['fav_fob']?></td>
							<td class=''><?=$rowsFavDt['fav_b2bid']?></td>
							<td class=''><?=$rowsFavDt['fav_miles']?></td>				
							<td class=''>B2B Status</td>				
							<td align="center" class=''><?=$rowsFavDt['fav_bl']?></td>		
							<td align="center" class=''>x</td>			
							<td align="center" class=''><?=$rowsFavDt['fav_bw']?></td>		
							<td align="center" class=''>x</td>			
							<td align="center" class=''><?=$rowsFavDt['fav_bh']?></td>
							<td class=''><?=$rowsFavDt['fav_desc']?></td>	
							<td class=''>Supplier</td>		
							<td class=''><?=$rowsFavDt['fav_shipfrom']?></td>
							<td class=''>Supplier Relationship Owner</td>
							<td class=''>
								<?
								if($rowsFavDt['boxtype'] == 'g'){
									$boxtype = 'Gaylord';
								}elseif($rowsFavDt['boxtype'] == 'sb'){
									$boxtype = 'Shipping';
								}elseif($rowsFavDt['boxtype'] == 'sup'){
									$boxtype = 'Supersack';
								}elseif($rowsFavDt['boxtype'] == 'pal'){
									$boxtype = 'Pallet';
								}elseif($rowsFavDt['boxtype'] == 'other'){
									$boxtype = 'Other';
								}
								echo $boxtype;
								?>
							</td>
							</tr>
							<?
							$i++;
						}
						?>
						<?
					}else{
						?>
						<tr><td colspan="18">No record found</td></tr>
						<?
					}
					?>
					<tr>
						<td colspan="18">
							<input type="hidden" name="hdnFavItemsAction" value="1">			
							<input type="hidden" name="hdnCompanyId" value="<?=$_REQUEST['ID'];?>">
							<input type="button" name="btnAddFavoriteInv" id="btnAddFavoriteInv" value="Add new favorite inventory" style="cursor:pointer;" onclick="add_inventory_to_favorite(<?=$ID; ?>)" >
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>  -->


		<tr>   
			<td colspan="6" >
				<form name="frmFavItems" method="post" action="clientdashboard_setup.php?ID=<?=$_REQUEST['ID'];?>">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
					<?
					$selFavData = db_query("SELECT * FROM clientdash_favorite_items WHERE compid = ".$_REQUEST['ID']." and favItems = 1 ORDER BY id DESC", db());
					//echo "<pre>";print_r($selFavData);echo "</pre>";
					$selFavDataCnt = tep_db_num_rows($selFavData);
					if($selFavDataCnt > 0 ){
						?>
						<tr bgcolor="#E4D5D5" >
							<td class='display_title'>&nbsp;</td>
							<td class='display_title'>Buy Now</td>
							<td class='display_title'>Qty Avail</td>		
							<td class='display_title'>Buy Now, Load Can Ship In</td>			
							<td class='display_title'>Expected # of Loads/Mo</td>			
							<td class='display_title'>Per Truckload</td>			
							<td class='display_title'>MIN FOB</td>
							<td class='display_title'>B2B ID</td>
							<td class='display_title'>Miles Away
								<div class="tooltip">
									<i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">Green Color - miles away <= 250, 
									<br>Orange Color - miles away <= 550 and > 250, 
									<br>Red Color - miles away > 550</span>
								</div>		
							</td>
							<td align="center" class='display_title'>B2B Status</td>
							<td align="center" class='display_title'>L</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>W</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>H</td>	
							<td class='display_title'>Description</td>	
							<td class='display_title'>Supplier</td>	
							<td class='display_title'>Ship From</td>
							<td class='display_title'>Supplier Relationship Owner</td>
							<td class=''>Box Type</td>
						</tr>
						<?		
						$i = 0;
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

										$to_show_rec = "y";
										

										if ($to_show_rec == "y") {
											$vendor_name = "";
											//account owner
											if($rowInvDt["vendor_b2b_rescue"]>0){
												
												$vendor_b2b_rescue=$rowInvDt["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												$query = db_query($q1, db());
												while($fetch = array_shift($query))
												{
													$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);
													
													$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
													$comres=db_query($comqry,db_b2b());
													while($comrow=array_shift($comres))
													{
														$ownername=$comrow["initials"];
													}
												}
											}else{
												$vendor_b2b_rescue=$rowInvDt["V"];
												if ($vendor_b2b_rescue != ""){
													$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
													$query = db_query($q1, db_b2b());
													while($fetch = array_shift($query))
													{
														$vendor_name = $fetch["Name"];
														
														$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
														$comres=db_query($comqry,db_b2b());
														while($comrow=array_shift($comres))
														{
															$ownername=$comrow["initials"];
														}
													}
												}	
											}
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
												$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
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
											$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
										}
										
										$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
										
										$b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
										$b2b_ulineCents = $rowInvDt["ulineCents"];
										$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
										$b2b_fob = "$" . number_format($b2b_fob,2);

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
											$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
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
										$ship_from2 = $shipfrom_state;	


										if ($i % 2 == 0){ $rowclr = 'rowalt2';  }else{ $rowclr = 'rowalt1'; }

										?>
										<tr class="<?=$rowclr?>">

											<td class=''>
												<? if ($rowsFavData['favItems'] == 1) { ?><input type="button" name="favItemIds" id="favItemIds<?=$rowsFavData['id'];?>" value="Remove" onclick="favitem_remove(<?=$rowsFavData['id'];?>, <?=$_REQUEST['ID'];?>)"> <? }?>
											</td>	
											<td class='' width="5%"><a href='https://b2b.usedcardboardboxes.com/?id=<? echo urlencode(encrypt_password(get_loop_box_id($rowBoxDt['b2b_id']))); ?>&compnewid=<? echo urlencode(encrypt_password($_REQUEST['ID']));?>' target='_blank' >Buy Now</a></td>
											<td class='' width="5%"><?=$qty?></td>		
											<td class='' width="8%"><?=$estimated_next_load?></td>	
											<td class='' width="5%"><?=$expected_loads_per_mo?></td>
											<td class='' width="5%"><?=$boxes_per_trailer?></td>
											<td class='' width="3%"><?=$b2b_fob?></td>
											<td class='' width="5%"><?=$rowInvDt['ID']?></td>
											<td class='' width="5%"><font color='<?=$miles_away_color;?>'><?=$miles_from?></font></td>	
											<td align="center" class='display_title'><font color="<?=$b2bstatuscolor;?>"><?=$b2bstatus_name?></font></td>

											<td align="center" class=''><?=$length?></td>		
											<td align="center" class=''>x</td>			
											<td align="center" class=''><?=$width?></td>		
											<td align="center" class=''>x</td>			
											<td align="center" class=''><?=$depth?></td>		
											<td class='' width="20%">
												<a href='https://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?=$rowBoxDt["id"]?>&proc=View' target='_blank'>
												<?=$rowInvDt["description"]?></a>
											</td>	
											<td class='' width="5%"><?=$vendor_name;?></td>
											<td class='' width="5%"><?=$ship_from?></td>
											<td class=''><?=$ownername;?></td>
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
											<!-- <td class='' width="5%">
												<a id='btnremove' href='javascript:void(0);' onClick='Remove_favorites(<?=$rowsFavData['id'];?>, <?=$_REQUEST['compnewid'];?>)' >Remove</a>
												<input type="hidden" name="hdnFavItemsAction" id="hdnFavItemsAction" value="1">		
												<input type="hidden" name="hdnCompanyId" id="hdnCompanyId" value="<?=$_REQUEST['compnewid'];?>">
												<input type="hidden" name="repchk_str" id="repchk_str" value="<?=$repchk_str;?>">
											</td> -->
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
					<tr>
						<td colspan="18">
							<input type="hidden" name="hdnFavItemsAction" value="1">			
							<input type="hidden" name="hdnCompanyId" value="<?=$_REQUEST['ID'];?>">
							<input type="button" name="btnAddFavoriteInv" id="btnAddFavoriteInv" value="Add new favorite inventory" style="cursor:pointer;" onclick="add_inventory_to_favorite(<?=$ID; ?>)" >
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>

		<!--Favorite Inventory section ends  -->
		<tr align="center">
			<td colspan="6" width="320px" align="left" >&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		<!--Closed Loop Inventory section start  -->
		
		<?
		//if(isset($_REQUEST['hdnClosedLoopInvAction']) && $_REQUEST['hdnClosedLoopInvAction'] == 1 ){
		if(isset($_REQUEST['closed_loop_item_removeflg']) && $_REQUEST['closed_loop_item_removeflg'] == "yes" ){
			//db_query("UPDATE clientdash_closed_loop_items SET favItems = 0 WHERE id =".$_REQUEST['favitemid'], db());
			db_query("Delete from clientdash_closed_loop_items WHERE id =".$_REQUEST['favitemid'], db());
			
			/*  //echo "<pre>"; print_r($_REQUEST); echo "</pre>";
			$selFavIds = db_query("SELECT id FROM clientdash_closed_loop_items WHERE compid = ".$_REQUEST['ID']." ORDER BY id DESC", db());
			while ($rowsFavIds = array_shift($selFavIds)) {
				if(in_array($rowsFavIds['id'], $_REQUEST['favClosedLoopInvItemIds'] )){
					db_query("UPDATE clientdash_closed_loop_items SET favItems = 1 WHERE id =".$rowsFavIds['id'], db());
					//echo "In 1 <br>";
				}else{
					db_query("UPDATE clientdash_closed_loop_items SET favItems = 0 WHERE id =".$rowsFavIds['id'], db());
					//echo "In 2 <br>";
				}
			}*/
			
		}
		?>		
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">
				Closed Loop Inventory&nbsp;<input type="checkbox" name="div_closed_inv" id="div_closed_inv" value="yes" <? if ($close_inv_flg == 1) { echo " checked ";}?> onclick="show_closed_loop_inv(<? echo $_REQUEST["ID"];?>)">
				Show this section in Front End
			</td>
		</tr>
		<tr>   
			<td colspan="6" >
				<form name="frmClosedLoopInv" method="post" action="clientdashboard_setup.php?ID=<?=$_REQUEST['ID'];?>">
					<table width="100%" cellspacing="1" cellpadding="1"border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
						<?
						$selFavDt1 = db_query("SELECT * FROM clientdash_closed_loop_items WHERE compid = ".$_REQUEST['ID']." and favItems = 1 ORDER BY id DESC", db());
						//echo "SELECT * FROM clientdash_closed_loop_items WHERE compid = ".$_REQUEST['ID']." and favItems = 1 ORDER BY id DESC </br>";
						$selFavDtCnt1 = tep_db_num_rows($selFavDt1);
						if($selFavDtCnt1 > 0 ){
							?>
							<tr bgcolor="#E4D5D5" >
								<td class='display_title'>Select</td>
								<td class='display_title'>Buy Now</td>
								<td class='display_title'>Qty Avail</td>		
								<td class='display_title'>Buy Now, Load Can Ship In</td>			
								<td class='display_title'>Expected # of Loads/Mo</td>			
								<td class='display_title'>Per Truckload</td>			
								<td class='display_title'>FOB Origin Price/Unit</td>
								<td class='display_title'>B2B ID</td>
								<td class='display_title'>Miles Away
									<div class="tooltip">
										<i class="fa fa-info-circle" aria-hidden="true"></i>
										<span class="tooltiptext">Green Color - miles away <= 250, 
										<br>Orange Color - miles away <= 550 and > 250, 
										<br>Red Color - miles away > 550</span>
									</div>		
								</td>				
								<td align="center" class='display_title'>L</td>		
								<td align="center" class='display_title'>x</td>			
								<td align="center" class='display_title'>W</td>		
								<td align="center" class='display_title'>x</td>			
								<td align="center" class='display_title'>H</td>			
								<td class='display_title'>Walls</td>		
								<td class='display_title'>Description</td>		
								<td class='display_title'>Ship From</td>
								<td class=''>Box Type</td>
							</tr>

							<?		
							$x = "SELECT * FROM companyInfo WHERE ID = " . $_REQUEST["ID"];
							$dt_view_res = db_query($x,db_b2b() );		
							while ($row = array_shift($dt_view_res)) {
								//if((remove_non_numeric($row["shipZip"])) !="")
								if(($row["shipZip"]) !=""){
									$tmp_zipval = "";
									$tmp_zipval = str_replace(" ", "", $row["shipZip"]);
									if($row["shipcountry"] == "Canada" ){ 	
										$zipShipStr= "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
									}elseif(($row["shipcountry"]) == "Mexico" ){
										$zipShipStr= "SELECT * FROM zipcodes_mexico LIMIT 1";
									}else {
										$zipShipStr= "SELECT * FROM ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
									}			
									$dt_view_res = db_query($zipShipStr,db_b2b() );
									while ($zip = array_shift($dt_view_res)) {
										$shipLat = $zip["latitude"];
										$shipLong = $zip["longitude"];
									}
								}
							}
	
							$i = 0;
							while ($rowsFavDt = array_shift($selFavDt1)) {
							
								$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = ".$rowsFavDt['fav_b2bid'], db());
								$rowBoxDt = array_shift($selBoxDt);
								if($rowBoxDt['b2b_id'] > 0){
									$selInvDt = db_query("SELECT * FROM inventory WHERE ID = ".$rowBoxDt['b2b_id'], db_b2b());
									$rowInvDt = array_shift($selInvDt);
								}

								$loop_id = $rowBoxDt['id'];
								$bpallet_qty= $rowBoxDt['bpallet_qty']; 
								$boxes_per_trailer= $rowBoxDt['boxes_per_trailer']; 
								$box_warehouse_id = $rowBoxDt["box_warehouse_id"];								
								$next_load_available_date = $rowBoxDt["next_load_available_date"];
								
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
								$ship_from2 = $shipfrom_state;
								
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
								
								if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")){
								
								}else{
									if ($rowInvDt["expected_loads_per_mo"] == 0 ) {
										$expected_loads_per_mo= "<font color=red> 0 </font>";
									}else{
										$expected_loads_per_mo= $rowInvDt["expected_loads_per_mo"];
									}
								}								
							
								$b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
								$b2b_ulineCents = $rowInvDt["ulineCents"];
								$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
								$b2b_fob = "$" . number_format($b2b_fob,2);
							
								$miles_from = "";
								if($rowInvDt["location_country"] == "Canada" ) { 	
									$tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
									$zipStr= "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
								}elseif(($rowInvDt["location_country"]) == "Mexico" ){
									$zipStr= "SELECT * FROM zipcodes_mexico LIMIT 1";
								}else {
									$zipStr= "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
								}

								if ($rowInvDt["location_zip"] != "") {
									if ($rowInvDt["availability"] != "-3.5" ) {
										$inv_id_list .= $rowInvDt["I"] . ",";
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
									
									$miles_from=(int) (6371 * $distC * .621371192);
									
									if ($miles_from <= 250){	//echo "chk gr <br/>";
										$miles_away_color = "green";
									}
									if ( ($miles_from <= 550) && ($miles_from > 250)){	
										$miles_away_color = "#FF9933";
									}
									if (($miles_from > 550) ){	
										$miles_away_color = "red";
									}
									
								}
								
								$type = "";
								if (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord" )))){ 
									$type = 'g';
								}elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold" )))){ 
									$type = 'sb';
								}elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB" )))){ 
									$type = 'sup';
								}elseif (in_array(strtolower(trim($rowBoxDt['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))){ 
									$type = 'pal';
								}
								
								if ($i % 2 == 0){ $rowclr = '#dcdcdc';  }else{ $rowclr = '#f7f7f7'; }
								?>
								<tr bgcolor="<?=$rowclr?>" >	
								<td class=''>
									<? if ($rowsFavDt['favItems'] == 1) { ?><input type="button" name="favClosedLoopInvItemIds" id="favClosedLoopInvItemIds<?=$rowsFavDt['id'];?>" value="Remove" onclick="closed_loop_item_remove(<?=$rowsFavDt['id'];?>, <?=$_REQUEST['ID'];?>)"> <? }?>
								</td>				
								<td class=''><a href='https://b2b.usedcardboardboxes.com/?id=<? echo urlencode(encrypt_password(get_loop_box_id($rowsFavDt["fav_b2bid"])));?>&compnewid=<? echo urlencode(encrypt_password($_REQUEST['ID']));?>' target='_blank' >Buy Now</a></td>
								<td class=''><?=$after_po_val?></td>		
								<td class=''><?=$rowBoxDt['buy_now_load_can_ship_in']?></td>			
								<td class=''><?=$expected_loads_per_mo?></td>			
								<td class=''><?=$rowBoxDt['boxes_per_trailer']?></td>			
								<td class=''><?=$b2b_fob?></td>
								<td class=''><?=$rowBoxDt['b2b_id']?></td>
								<td class=''><font color='<?=$miles_away_color;?>'><?=$miles_from?></font></td>	
								<td align="center" class=''><?=$fav_bl?></td>		
								<td align="center" class=''>x</td>			
								<td align="center" class=''><?=$fav_bw?></td>		
								<td align="center" class=''>x</td>			
								<td align="center" class=''><?=$fav_bh?></td>			
								<td class=''><?=$rowInvDt["bwall"]?></td>		
								<td class=''>
									<a href='https://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?=$rowBoxDt["id"]?>&proc=View' target='_blank'>
									<?=$rowInvDt["description"]?></a>
								</td>		
								<td class=''><?=$ship_from2?></td>
								<td class=''>
									<?
									if($type == 'g'){
										$boxtype = 'Gaylord';
									}elseif($type == 'sb'){
										$boxtype = 'Shipping';
									}elseif($type == 'sup'){
										$boxtype = 'Supersack';
									}elseif($type == 'pal'){
										$boxtype = 'Pallet';
									}elseif($type == 'other'){
										$boxtype = 'Other';
									}
									echo $boxtype;
									?>
								</td>
								</tr>
								<?
								$i++;
							}
							?>
							<!-- <tr>
								<td colspan="18"><input type="submit" value="Update" style="cursor:pointer;"></td>
							</tr> -->
							<?
						}else{
							?>
							<tr><td colspan="18">No record found</td></tr>
							<?
						}
						?>
						<tr>
							<td colspan="18">
								<input type="button" name="btnAddCloseloopInv" id="btnAddCloseloopInv" value="Add new close loop inventory" style="cursor:pointer;" onclick="add_inventory_to_closeloop(<?=$ID; ?>)" >
							</td>
						</tr>
					</table>
				</form>	
			</td>
		</tr>
		
		<!--Closed Loop Inventory section ends  -->

		<!-- Hide Inventory Section start -->
		<?
		if(isset($_REQUEST['hideremoveflg']) && $_REQUEST['hideremoveflg'] == "yes" ){
		
			db_query("Delete from clientdash_hide_items WHERE id =".$_REQUEST['hideitemid'], db());
		}
		?>
		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">Hide Inventory</td>
		</tr>

		<td colspan="6" >
				<form name="frmhideItems" method="post" action="clientdashboard_setup.php?ID=<?=$_REQUEST['ID'];?>">
				<table width="100%" cellspacing="1" cellpadding="1"border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12; border: 1px solid #cccccc;">
					<?
					$selhideData = db_query("SELECT * FROM clientdash_hide_items WHERE compid = ".$_REQUEST['ID']." and hideItems = 1 ORDER BY id DESC", db());
					
					$selhideDataCnt = tep_db_num_rows($selhideData);
					if($selhideDataCnt > 0 ){

						?>
						<tr bgcolor="#E4D5D5" >
							<td class='display_title'>&nbsp;</td>
							<td class='display_title'>Qty Avail</td>		
							<td class='display_title'>Buy Now, Load Can Ship In</td>			
							<td class='display_title'>Expected # of Loads/Mo</td>			
							<td class='display_title'>Per Truckload</td>			
							<td class='display_title'>MIN FOB</td>
							<td class='display_title'>B2B ID</td>
							<td class='display_title'>Miles Away
								<div class="tooltip">
									<i class="fa fa-info-circle" aria-hidden="true"></i>
									<span class="tooltiptext">Green Color - miles away <= 250, 
									<br>Orange Color - miles away <= 550 and > 250, 
									<br>Red Color - miles away > 550</span>
								</div>		
							</td>
							<td align="center" class='display_title'>B2B Status</td>
							<td align="center" class='display_title'>L</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>W</td>		
							<td align="center" class='display_title'>x</td>			
							<td align="center" class='display_title'>H</td>	
							<td class='display_title'>Description</td>	
							<td class='display_title'>Supplier</td>	
							<td class='display_title'>Ship From</td>
							<td class='display_title'>Supplier Relationship Owner</td>
							<td class=''>Box Type</td>
						</tr>
						<?
						
						$i = 0;
						while ($rowsFavData = array_shift($selhideData)) {
							//echo "<pre> rowsFavData ->";print_r($rowsFavData);echo "</pre>";
							$after_po_val_tmp = 0; $after_po_val = 0; 
							$pallet_val_afterpo = $preorder_txt2 = "";
							$rec_found_box = "n";
							$boxes_per_trailer= 0;
							$next_load_available_date = "";
							if($rowsFavData['hide_b2bid'] > 0){
								$selBoxDt = db_query("SELECT * FROM loop_boxes WHERE b2b_id = ".$rowsFavData['hide_b2bid'], db());
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

										
										$vendor_name = "";
										//account owner
										if($rowInvDt["vendor_b2b_rescue"]>0){
											
											$vendor_b2b_rescue=$rowInvDt["vendor_b2b_rescue"];
											$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
											$query = db_query($q1, db());
											while($fetch = array_shift($query))
											{
												$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);
												
												$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
												$comres=db_query($comqry,db_b2b());
												while($comrow=array_shift($comres))
												{
													$ownername=$comrow["initials"];
												}
											}
										}else{
											$vendor_b2b_rescue=$rowInvDt["V"];
											if ($vendor_b2b_rescue != ""){
												$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
												$query = db_query($q1, db_b2b());
												while($fetch = array_shift($query))
												{
													$vendor_name = $fetch["Name"];
													
													$comqry="select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=".$fetch["b2bid"];
													$comres=db_query($comqry,db_b2b());
													while($comrow=array_shift($comres))
													{
														$ownername=$comrow["initials"];
													}
												}
											}	
										}
										


										if ($after_po_val < 0) { 
											$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
										}else if ($after_po_val >= $boxes_per_trailer) { 
											$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
										} else {  
											$qty = number_format($after_po_val,0) . $pallet_val_afterpo . $preorder_txt2;
										}


										$estimated_next_load = ""; $b2bstatuscolor="";
										if ($rowInvDt["expected_loads_per_mo"] == 0 ) {
											$expected_loads_per_mo= "<font color=red>0</font>";
										}else{
											$expected_loads_per_mo= $rowInvDt["expected_loads_per_mo"];
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
											//$estimated_next_load= "<font color=red>Ask Rep</font>";
										}
										if($rowInvDt["buy_now_load_can_ship_in"] == '<font color=red>Ask Purch Rep</font>'
										 || $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red> Ask Purch Rep </font>'){
											$estimated_next_load = '<font color=red>Ask Rep</font>';
										}else{
											$estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
										}
										$b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
										$b2b_ulineCents = $rowInvDt["ulineCents"];
										$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
										$b2b_fob = "$" . number_format($b2b_fob,2);

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
											$get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;
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
										$ship_from2 = $shipfrom_state;	


										if ($i % 2 == 0){ $rowclr = '#dcdcdc';  }else{ $rowclr = '#f7f7f7'; }
										?>
										<tr bgcolor="<?=$rowclr?>" >

											<td class=''>
												<? if ($rowsFavData['hideItems'] == 1) { ?><input type="button" name="hideItemIds" id="hideItemIds<?=$rowsFavData['id'];?>" value="Remove" onclick="hideitem_remove(<?=$rowsFavData['id'];?>, <?=$_REQUEST['ID'];?>)"> <? }?>
											</td>	
											
											<td class='' width="5%"><?=$qty?></td>		
											<td class='' width="8%"><?=$estimated_next_load?></td>	
											<td class='' width="5%"><?=$expected_loads_per_mo?></td>
											<td class='' width="5%"><?=$boxes_per_trailer?></td>
											<td class='' width="3%"><?=$b2b_fob?></td>
											<td class='' width="5%"><?=$rowInvDt['ID']?></td>
											<td class='' width="5%"><font color='<?=$miles_away_color;?>'><?=$miles_from?></font></td>	
											<td align="center" class='display_title'><font color="<?=$b2bstatuscolor;?>"><?=$b2bstatus_name?></font></td>

											<td align="center" class=''><?=$length?></td>		
											<td align="center" class=''>x</td>			
											<td align="center" class=''><?=$width?></td>		
											<td align="center" class=''>x</td>			
											<td align="center" class=''><?=$depth?></td>		
											<td class='' width="20%">
												<a href='https://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?=$rowBoxDt["id"]?>&proc=View' target='_blank'>
												<?=$rowInvDt["description"]?></a>
											</td>	
											<td class='' width="5%"><?=$vendor_name;?></td>
											<td class='' width="5%"><?=$ship_from?></td>
											<td class=''><?=$ownername;?></td>
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
					<tr>
						<td colspan="18">
							<input type="hidden" name="hdnhideItemsAction" value="1">			
							<input type="hidden" name="hdnCompanyId" value="<?=$_REQUEST['ID'];?>">
							<input type="button" name="btnAddHideInv" id="btnAddHideInv" value="Add New Inventory to Hide" style="cursor:pointer;" onclick="add_inventory_to_hide(<?=$ID; ?>)" >
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		
		<!-- Hide Inventory Section End -->


		<!--Start Setup for hiding coliumns from user in boomerang section defined as seven 7  -->
		
		<tr align="center">
			<td colspan="6" width="320px" align="left" style="background-color:white;">&nbsp;</td>
		</tr>
		<tr align="center">
			<td colspan="6" width="320px" align="left" bgcolor="#C1C1C1">
				Setup 
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;Hide Pricing and Buy Now page?&emsp;
				<input type="checkbox" name="clientdash_setup_hide" id="clientdash_setup_hide" value="yes" <? if ($setup_hide_flg == 1) { echo " checked "; }?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr>

		<tr>
			<td>
				&nbsp;Hide Box Profiles and Browse Inventory Pages?&emsp;
				<input type="checkbox" name="clientdash_boxprofile_inv_hide" id="clientdash_boxprofile_inv_hide" value="yes" <? if ($setup_boxprofile_inv_flg == 1) { echo " checked "; }?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr>
		
		<? if($parent_flg == "Parent"){ ?>
		<tr>
			<td>
				&nbsp;Hide Corporate Views from Reports?&emsp;
				<input type="checkbox" name="clientdash_family_tree" id="clientdash_family_tree" value="yes" <? if ($setup_family_tree == 1) { echo " checked "; }?> />
			</td>
			<td colspan="5" align="left">&nbsp;</td>
		</tr>
		<? } ?>
		
		<tr align="">
			<td colspan="6">
				<input type="button" name="clientdash_setup_submit" id="clientdash_setup_submit" value="Submit" onclick='handleSetuphide(<?=$ID;?>);' />
			</td>
		</tr>
		<!--Closed Setup for hiding coliumns from user in boomerang section 7 -->

		<!--Start Setup for hiding coliumns from user in boomerang links defined as eight 8  -->
		<!--Closed Setup for hiding coliumns from user in boomerang section 8 -->

		
	</table>

	
<br/>
	</div>
</body>
</html>