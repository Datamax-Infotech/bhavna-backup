<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.display_maintitle {
    font-size: 13px;
    padding: 3px;
    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    background: #98bcdf;
    white-space: nowrap;
}
.display_title {
    font-size: 12px;
    padding: 3px;
    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    background: #ABC5DF;
    white-space: nowrap;
}
.display_table {
    font-size: 11px;
    padding: 3px;
    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    background: #EBEBEB;
}
.form_component {
    font-size: 11px;
    margin-top: 2px;
    margin-bottom: 2px;
    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
}
.info_s {
    font-size: 11px;
    font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	 padding: 3px;
}

.delTransaction{
	display: none;
    position: absolute;
    top: 0%;
    left: 0%;
    width: 1200px;
    height: 520px;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
}

#tbl_baseline input {width:100%}
</style>


<style>

/*Tooltip style*/
.tooltip {
  position: relative;
  display: inline-block;
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

.fa-info-circle{
	font-size: 9px;
	color: #767676;
}
</style>
<? 
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");
echo "<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css' >";

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script language="javascript">
    function deletetrans(trans_rec_id, supuservalue)
	{
        if(supuservalue === 'true'){
            if (confirm("Do you want to delete the Transcation?") == true) {
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
            		  document.getElementById("rowid"+trans_rec_id).style.display = "none";
            		}
            	}

            	xmlhttp.open("GET","water_trans_delete.php?trans_rec_id=" +trans_rec_id,true);
            	xmlhttp.send();
            }
        }else{
            if (supuservalue == '#DELwater4652') {
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
						document.getElementById("rowid"+trans_rec_id).style.display = "none";
						document.getElementById('delTrans_window').style.display='none'
                    }
                }

                xmlhttp.open("GET","water_trans_delete.php?trans_rec_id=" +trans_rec_id+"&userpass="+ supuservalue,true);
                xmlhttp.send();
            }else{
				alert("Please enter the correct Password!");
				return false;
			}
        }
	}	

	function deleteTranByPass(ctrlid,tranID){
		var selectobject = document.getElementById(ctrlid+tranID); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('delTrans_window').style.display='block';
		document.getElementById('delTrans_window').style.left = n_left + 50 + 'px';
		document.getElementById('delTrans_window').style.top = n_top + 20 + 'px';
		document.getElementById('delTrans_window').style.width = '380px';
		document.getElementById('delTrans_window').style.height = '170px';
		
		// document.getElementById("delTrans_window").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 
		const copiesHTML = `
		<form onclick="return false">
			<a id="hd_frm_orderissue_close" style="cursor:pointer;display: block;text-align: end;" href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('delTrans_window').style.display='none';document.getElementById('fade').style.display='none'>Close</a>
			<br>

			<font size="2" color="#333333" style="margin-bottom: 10px;">Only certain employees can delete Water Trasnsactions. If you're one of them, you'll have a password. Please enter it below.</font>
			<br>
			<font size="2" color="#333333" style="margin-bottom: 10px;">Password :</font>
			<input type="password" name="delpass" id="delpass" style="margin-bottom: 10px;">
			<br>
			<button onclick="deletetrans(${tranID},document.getElementById('delpass').value)" style="margin-bottom: 10px;">Confirm Delete</button>
			<br>
			<font size="2" color="#333333">If you need support, please contact the UCBZeroWaste Technology Manager, or the HR Department.</font>
		</form>
		`;
		document.getElementById('delTrans_window').innerHTML = copiesHTML;
	}
</script>

<script language="javascript">
	function deleteTran(){
		console.log("Enter");
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
	
	function update_edit_item_water(unqid)
	{
		document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						
		
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
			  document.getElementById('light_todo').style.display='none';
			  document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
			}
		}
		  var task_title="", task_details="", due_date="", task_owner="", change_status="", todo_taskID="";
			var compid = document.getElementById('todo_companyID').value;
			var todo_count = document.getElementById('todo_count').value;
			for(var i=1; i<=todo_count; i++)
			{
				task_title+= document.getElementById('task_title'+i).value+"|";
				task_details+= document.getElementById('task_details'+i).value+"|";
				due_date+= document.getElementById('due_date'+i).value+"|";
				todo_taskID+= document.getElementById('todo_taskID'+i).value+"|";
				task_owner+= document.getElementById('task_owner'+i).value+"|";
				if(document.getElementById('change_status'+i).checked) { 
					change_status+= document.getElementById('change_status'+i).value+"|";
				}
				
			}
			var init_step = document.getElementById('init_step').value;
		//
		task_title=task_title.slice(0, -1);
		task_details=task_details.slice(0, -1);
		due_date=due_date.slice(0, -1);
		task_owner=task_owner.slice(0, -1);
		change_status=change_status.slice(0, -1);
		//
		
		xmlhttp.open("GET","todolist_water_update_row.php?inedit_mode=1&unqid="+unqid+"&compid=" +compid+"&task_title="+encodeURIComponent(task_title)+"&task_details="+encodeURIComponent(task_details)+"&due_date="+due_date+"&task_owner="+task_owner+"&change_status="+change_status+"&todo_count="+todo_count+"&todo_taskID="+todo_taskID+"&init_step="+init_step,true);
		xmlhttp.send();		
	}
	
	function update_mark_comp_water(unqid)
	{
		document.getElementById("initiative_div").innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />"; 						
		
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
			  document.getElementById('light_todo').style.display='none';
			  document.getElementById("initiative_div").innerHTML = xmlhttp.responseText;
			}
		}

		var compid = document.getElementById('todo_companyID').value;
		var todo_date = document.getElementById('todo_date_edit').value;
		
		xmlhttp.open("GET","todolist_water_update.php?mark_comp_edit=1&unqid="+unqid+"&compid=" +compid+"&todo_date="+encodeURIComponent(todo_date),true);
		xmlhttp.send();		
	}


    function update_water_lik_edit(compid)
	{
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
				document.getElementById("txt_water_link_div_edit").innerHTML = xmlhttp.responseText;
			}
		}

		xmlhttp.open("GET","water_update_link.php?water_action=edit&comp_id=" +compid,true);
		xmlhttp.send();
	}


    function update_water_lik_del(compid)
	{
		var answer = prompt("Are you sure want to delete link?");
		if (answer == "4652"){
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
					alert("Link removed.");
					document.getElementById("txt_water_link_div").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET","water_update_link.php?water_action=del&comp_id=" +compid,true);
			xmlhttp.send();
		}
		else{
			alert("Password Incorrect!");
			return false;
		}
	}

    function update_water_lik(compid)
	{
		var txt_water_link = document.getElementById("txt_water_link").value;
		
		if (txt_water_link != ""){
		
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
					alert("Link saved.");
					document.getElementById("txt_water_link_div_edit").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET","water_update_link.php?water_action=add&water_link=" + encodeURIComponent(txt_water_link) + "&comp_id=" +compid,true);
			xmlhttp.send();
		}else{
			alert("Enter the Link to be saved.");
		}
	}	
	
    function searchinvno(compid, rec_id)
	{
		var txtinvno = document.getElementById("txtsrchinvno").value;
		
		if (txtinvno != ""){
		
			document.getElementById("water_maintbl").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
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
				  document.getElementById("water_maintbl").innerHTML = xmlhttp.responseText;  
				}
			}

			xmlhttp.open("GET","water_get_inv_no.php?invno=" +txtinvno + "&comp_id=" +compid + "&rec_id=" +rec_id,true);
			xmlhttp.send();
		}
	}	
	function fun_show_new_initiative() {
    	var new_initiative_frm = document.getElementById('new_initiative_frm');
    	var displaySetting = new_initiative_frm.style.display;
    	// also get the  button, so we can change what it says
    	var add_new_initiative = document.getElementById('add_new_initiative');

   		// now toggle and the button text, depending on current state
    	if (displaySetting == 'block') {
      		// div is visible. hide it
      		new_initiative_frm.style.display = 'none';
			// change button text
			// add_new_initiative.innerHTML = 'Show';
    	}
    	else {
      		// div is hidden. show it
      		new_initiative_frm.style.display = 'block';
      		// change button text
      		//add_new_initiative.innerHTML = 'Hide';
		}
  	}
	function newinitiavite_cancel(){
		//cancel_newinitiavite_btn
		var new_initiative_frm = document.getElementById('new_initiative_frm');
		new_initiative_frm.style.display = 'none';
		//
		document.getElementById('task_title').value="";
		document.getElementById('task_detail').value="";
		document.getElementById('due_date').value="<?php echo date('m/d/Y'); ?>";
		document.getElementById('task_owner').value="";
	}
	function show_vendor_payment_report(){
		if(document.getElementById("make_receive_payment").checked){
			document.getElementById("vendor_pay_reportid").style.display='block';
		}
		else{
			document.getElementById("vendor_pay_reportid").style.display='none';
		}
	}
	
	function show_ucb_inventory(d, compid, wid, commid=0){
		
		var chkbox = document.getElementById("ucbinventory"+d);
		var sstr = "";		
		sstr = sstr + "<a href='javascript:void(0)'  onclick='close_baseline_popup("+d+")' style=' text-decoration:none;color:black;cursor:pointer;'>Close</a><br>";
			
		var selectobject = document.getElementById("ucbinventory"+d); 
		var n_left = f_getPosition(selectobject, 'Left');
		var n_top  = f_getPosition(selectobject, 'Top');
		document.getElementById('light_todo').style.height = 450 + 'px';
		document.getElementById('light_todo').style.width = 700 + 'px';
		document.getElementById('light_todo').style.left = n_left - 60 + 'px';
		document.getElementById('light_todo').style.top = n_top - 10 + 'px';
		document.getElementById('light_todo').style.display = 'block';
		document.getElementById("light_todo").innerHTML = sstr + "<br/>Loading .....<img src='images/wait_animated.gif'/>";

		if (window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
		}else{
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xhr.onreadystatechange=function(){		
			if (xhr.readyState==4 && xhr.status==200){ 
				document.getElementById("light_todo").innerHTML = sstr + xhr.responseText; 
			}
		}

		xhr.open("GET","baseline_ucb_inventory.php?row="+ d +"&ID="+ compid + "&id="+ wid + "&commid="+ commid,true);
		xhr.send();

		
	}
	
	function close_baseline_popup(d){
		document.getElementById('light_todo').style.display='none';
		var chkbox = document.getElementById("ucbinventory"+d);
		var invlist = document.getElementById("inventory_list"+d).value;
		if(invlist != ""){
			chkbox.checked = true;
		}else{
			chkbox.checked = false;
		}
	}
	
	function itemSelected(d){
		//var checkboxes = document.getElementsByName("ucb_inventory");
		//var checkboxes = document.getElementsByName("ucbinv_cls");
		var checkboxes = document.querySelectorAll(".ucbinv_cls");
		var selectedCheckboxes = [];
		
		checkboxes.forEach(function(checkbox) {
			if (checkbox.checked) {
				selectedCheckboxes.push(checkbox.value);
			}
		});
		
		var lt_inv = selectedCheckboxes.join(",");
		
		//return selectedCheckboxes.join(",");
		document.getElementById("inventory_list"+d).value = lt_inv;
		close_baseline_popup(d);
		
	}

</script>
<?
if  ($_REQUEST["show"] == "watertransactions") 
{
?>	

  <style type="text/css">
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
		left: 5%;
		width: 60%;
		height: 90%;
		padding: 16px;
		border: 1px solid gray;
		background-color: white;
		z-index:1002;
		overflow: auto;
	}
</style>

	<div id="light_todo" class="white_content"></div>
	<div id="fade_todo" class="black_overlay"></div>

	<script LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></script>
	<script LANGUAGE="JavaScript" SRC="inc/general.js"></script>
		<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
		<script LANGUAGE="JavaScript">
			var cal2xxwater = new CalendarPopup("listdivwater");
			cal2xxwater.showNavigationDropdowns();
			
			var stepduedt = new CalendarPopup("listdiv_blastdt");
			stepduedt.showNavigationDropdowns();
			
			var cal2xxwateredit = new CalendarPopup("listdiv1");
			cal2xxwateredit.showNavigationDropdowns();
			
			var duedt = new CalendarPopup("listdiv_duedt");
			duedt.showNavigationDropdowns();
			
		</script>
	
<?
	$ID=$_REQUEST["ID"];
	if(isset($_REQUEST["id"])){
		$id=$_REQUEST["id"];
	}
	if(isset($_REQUEST["warehouse_id"])){
		$warehouse_id=$_REQUEST["warehouse_id"];
		$id=$_REQUEST["warehouse_id"];
	}
	if(isset($_REQUEST["ID"])){
		$ID=$_REQUEST["ID"];
		$b2bid=$_REQUEST["ID"];
	}
	if(isset($_REQUEST["b2bid"])){
		$b2bid=$_REQUEST["b2bid"];
		$ID=$_REQUEST["b2bid"];
	}
	
	
	//
	$loop_rec_found = "no"; $water_google_link = ""; $water_google_link_by = ""; $water_google_link_on = ""; $parent_child_flgwater = "";
	$res_totcnt = db_query("Select loopid, water_google_link, water_google_link_by, water_google_link_on, parent_child from companyInfo where ID = " . $_REQUEST["ID"] . " and loopid > 0", db_b2b() );
	while ($row_totcnt = array_shift($res_totcnt)) 
	{
		$loop_rec_found = "yes";
		$parent_child_flgwater = $row_totcnt["parent_child"];
		$water_google_link = $row_totcnt["water_google_link"];
		$water_google_link_by = $row_totcnt["water_google_link_by"];
		$water_google_link_on = $row_totcnt["water_google_link_on"];
	}

	if ($loop_rec_found == "no") {
		$sql = "SELECT * FROM companyInfo where ID = " . $_REQUEST["ID"] . " ";
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
			$strQuery = $strQuery . " values(" . $_REQUEST["ID"]  . ", '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
			$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '" . $tmp_phone . "', " ;
			$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '" . $tmp_contact . "', '" . $tmp_company . "', '" . $tmp_address . "', '" . $tmp_address2 . "', " ;
			$strQuery = $strQuery . " '" . $tmp_city . "', '" . $tmp_state . "', '" . $myrowsel["zip"] . "', '', '" . $tmp_phone . "', " ;
			$strQuery = $strQuery . " '" . $myrowsel["email"] . "', '', '', '', '', '', " ;
			$strQuery = $strQuery . " '" . $tmp_rec_type . "', '" . $tmp_bs_status . "', '" . $myrowsel["overall_revenue_comp"] . "', '" . $myrowsel["noof_location"] . "', '" . $myrowsel["accounting_email"] . "', '" . $tmp_accounting_contact . "', '" . $tmp_accounting_phone . "') " ;
			
			$res = db_query($strQuery , db());
			//echo $strQuery;
			$new_loop_id = tep_db_insert_id();
			$id = $new_loop_id;
			db_query("Update companyInfo set loopid = " . $new_loop_id . " where ID = " . $_REQUEST["ID"], db_b2b() );
			
			$sql = "SELECT inventory.id as b2bid FROM boxes inner join inventory on inventory.id = boxes.inventoryid where boxes.inventoryid > 0 and boxes.companyid = " . $_REQUEST["ID"] . " ";
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
			
			echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"viewCompany_func_water-mysqli.php?ID=" . $_REQUEST["ID"] .'&warehouse_id=' . $new_loop_id .  "&rec_type=Manufacturer&show=watertransactions&proc=View&searchcrit=\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_sales_iframe_po_display.php?poadded=yes&ID=" . $_REQUEST["ID"] .'&warehouse_id=' . $new_loop_id . "&rec_type=Manufacturer&show=watertransactions&proc=View&searchcrit\" />";
			echo "</noscript>"; exit;
			
		}	
		
	}
	
?>

<?
$sqlGetZWDt = "SELECT loginid, companyid, user_name, password FROM supplierdashboard_usermaster WHERE companyid=".$_REQUEST["ID"]." and activate_deactivate = 1" ;
$resGetZWDt = db_query($sqlGetZWDt, db());
$arrGetZWDt = array_shift($resGetZWDt);
if(!empty($arrGetZWDt)){
	$userName 	= base64_encode($arrGetZWDt['user_name']);
	$pwd  		= base64_encode($arrGetZWDt['password']);	
?>
	<a target="_blank" href="https://www.ucbzerowaste.com/index.php?txtemail=<? echo $userName; ?>&txtpassword=<? echo $pwd; ?>&redirect=yes "><h4>Client WATER Dashboard</h4></a>
<? } ?>
<br>
<Font Face='arial' size='4' color='#333333'>
<!--  baseline table goes here -->
<div style="min-height:150px;max-height:500px; max-width:100%;overflow:auto">
	<?
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo "<div style=''><pre>";
			
			$comm_lock_savings_to_zero = $_POST['com_lock_savings_to_zero'];
			
			//print_r($_POST);
			echo "</pre></div>";
			if ($_POST["comm_mod"] == 5){
				$sqldel = "DELETE FROM `base_line_commodity_master` WHERE `commodity_id` = '".  $_POST["comm_id"] ."'";
				$res = db_query($sqldel, db());
			}else{
				if ($_POST["comm_mod"] == 0){
					$sql_insert = "INSERT INTO base_line_commodity_master(company_id, commodity, cost, revenue, unit_of_measure, active_flg, entry_added_by, entry_added_on, lock_savings_to_zero) VALUES (";
					$sql_insert .= "'".$ID."', '". $_POST["comm_name"] ."', '". $_POST["comm_cost"] ."', '". $_POST["comm_revenue"] ."', '". $_POST["comm_uom"] ."',";
					$sql_insert .= "'1', '".$_COOKIE['employeeid']."', '".date("Y-m-d H:i:s")."', '". $comm_lock_savings_to_zero ."')";
					$res_insert = db_query($sql_insert,db());
					
					$commodityid = tep_db_insert_id();
				}else{
					$sql_update = "update base_line_commodity_master set commodity = '". $_POST["comm_name"] ."', cost='". $_POST["comm_cost"] ."',";
					$sql_update .= "revenue='". $_POST["comm_revenue"] ."', unit_of_measure='". $_POST["comm_uom"] ."', entry_modified_by='".$_COOKIE['employeeid']."', lock_savings_to_zero='". $comm_lock_savings_to_zero ."', ";
					$sql_update .= "entry_modified_on='".date("Y-m-d")."' where commodity_id = '".  $_POST["comm_id"] ."'";
					//echo $sql_update . "<br>";
					$res_update = db_query($sql_update,db());
					$commodityid = $_POST["comm_id"];
				}
				
				$invfound = "no";
				$sqlinv = "SELECT id FROM baseline_ucbinventory WHERE commodity_id = '". $commodityid ."'";
				$resinv = db_query($sqlinv, db());
				$rescnt = tep_db_num_rows($resinv);
				//echo $rescnt. "=585";
				$rowres = array_shift($resinv);
				if($rescnt > 0){
					//update
					$sqlupdt = "UPDATE `baseline_ucbinventory` SET `inventory_list`= '". $_POST["comm_invlist"] ."' WHERE `id`= '". $rowres["id"] ."'";
					//echo $sqlupdt . "<br>";
					$resinv2 = db_query($sqlupdt, db());
				}else{
					//insert
					$sqlinst = "INSERT INTO `baseline_ucbinventory`(`commodity_id`, `inventory_list`, `entry_added_by`) VALUES ('". $commodityid ."', '". $_POST["comm_invlist"] ."', '". $_COOKIE['employeeid'] ."')";
					//echo $sqlinst . "<br>";
					$resinv2 = db_query($sqlinst, db());
				}
			}
		}
		
	?>
	<b>Baseline Table</b>
	
		<table cellSpacing="1" cellPadding="1"  border="0" id="tbl_baseline" style="width:60%;font-size: xx-small">
			<tbody>
			<tr>
				<th bgcolor="#c0cdda" style='width:12%'>Commodity Number</th>
				<th bgcolor="#c0cdda" style='width:8%'>UCB Inventory</th>
				<th bgcolor="#c0cdda" style='width:22%'>Commodity Name</th>
				<th bgcolor="#c0cdda" style='width:8%'>Cost</th>
				<th bgcolor="#c0cdda" style='width:8%'>Revenue</th>
				<th bgcolor="#c0cdda" style='width:12%'>UOM</th>
				<th bgcolor="#c0cdda" style='width:8%'>Lock Savings to Zero
					<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">If UCBZeroWaste has NOT yet implemented changes to this waste stream, mark the check-box to lock the savings to $0.00</span></div><br>
				</th>
				<th bgcolor="#c0cdda" style='width:30%'>Log</th>
				<th bgcolor="#c0cdda">&nbsp;</th>
			</tr> 
			<?
			  // $ID has company id
			  // first read all data from base_line_commodity_master where company_id=$ID and show all rows with edit option.
			  // default view is text. If edit link is cliked then the row is set to editable fields
			  $uom_sql = "select * from base_line_unit_of_measure";
			  $uom_res = db_query($uom_sql,db());
			  $existing_baseline_sql = "select cm.*, um.unit_of_measure as uom, um.id as uom_id from base_line_commodity_master cm join base_line_unit_of_measure um on cm.unit_of_measure=um.id where company_id=$ID order by commodity_id ";
			  $existing_baseline_res = db_query($existing_baseline_sql,db());
			  $existing_baseline_rows = tep_db_num_rows($existing_baseline_res);
			  //echo "Found $existing_baseline_rows rows ";
			  $i=1;
			  if($existing_baseline_rows >0) {
				  while($baseline_row = array_shift($existing_baseline_res)) {
					  $added_by_initials = $modified_by_initials = $log = "";
					  if($baseline_row['entry_added_by'] >0 ){
						  $added_by_sql = "select username,initials from loop_employees where id=".$baseline_row['entry_added_by'];
						  $added_by_res = db_query($added_by_sql,db());
						  $added_by_row = array_shift($added_by_res);
						  $added_by_initials = $added_by_row['initials'];
					  }
					  if($baseline_row['entry_modified_by'] >0 ){
						  $modified_by_sql = "select username,initials from loop_employees where id=".$baseline_row['entry_modified_by'];
						  $modified_by_res = db_query($modified_by_sql,db());
						  $modified_by_row = array_shift($modified_by_res);
						  $modified_by_initials = $modified_by_row['initials'];
					  }
					  $log = "Added by $added_by_initials on ".date("m/d/Y",strtotime($baseline_row['entry_added_on']));
					  if($modified_by_initials != ''){
						  $log = " Edited by $modified_by_initials on ".date("m/d/Y",strtotime($baseline_row['entry_modified_on'])).". Previously $log";
					  }
					  
					  $ucbinventory = "";
					  $boxlink = $checked = "";
					  $inv_arr = $invlist = "";
					  $ucbinv = "no";
					  $sqlinv2 = "SELECT inventory_list FROM baseline_ucbinventory WHERE inventory_list <> '' and commodity_id = '". $baseline_row['commodity_id']."'";
					  //echo $sqlinv2 . "<br>";
					  $sqlres2 = db_query($sqlinv2, db());
					  
					  if(tep_db_num_rows($sqlres2)>0){
						$invlist = array_shift($sqlres2);
						$inv_arr = explode(",", $invlist['inventory_list']);
						$ucbinventory =  count($inv_arr);
						$ucbinv = "yes";
						if($invlist['inventory_list']!=""){
							$checked = "checked";
						}
					  }
					  /*
					  if($cnt_arr > 1){
						  foreach($inv_arr as $val){
							  $boxlink[] = '<a href="manage_box_b2bloop.php?id ='. $val .'&proc=View" target="_blank">'.$val.'</a>';
						  }
						  $ucbinventory = implode(" , ", $boxlink);
						  
					  }elseif($cnt_arr == 1){
						  $ucbinventory = '<a href="manage_box_b2bloop.php?id ='. $inv_arr[0] .'&proc=View" target="_blank">'. $inv_arr[0] .'</a>';
						  
					  }else{
						  $ucbinventory = "";
					  }
					  */
					  
					  $lock_savings_to_zero = "";
					  if ($baseline_row['lock_savings_to_zero'] == 1)
					  {
						  $lock_savings_to_zero = "Yes";
					  }
						  
					  echo "<tr bgcolor='#e4e4e4' id='view_baseline_$i'>";
					  echo "<td class='style1'>Commodity $i</td>";
					  echo "<td class='style1'>".$ucbinventory."</td>";
					  echo "<td class='style1'>".$baseline_row['commodity']."</td>";
					  echo "<td class='style1'>".$baseline_row['cost']."</td>";
					  echo "<td class='style1'>".$baseline_row['revenue']."</td>";
					  echo "<td class='style1'>".$baseline_row['uom']."</td>";
					  echo "<td class='style1'>". $lock_savings_to_zero ."</td>";
					  echo "<td class='style1'>$log</td>";
					  echo "<td class='style1'><a href='javascript:void(0);edit_baseline_row($i)'>Edit</a></td>";
					  echo "</tr>";
					  
					  echo "<tr bgcolor='#e4e4e4' id='edit_baseline_$i' style='display:none'>";
					  echo "<td class='style1'>Commodity $i</td>";
					  echo "<td class='style1'><input id='ucbinventory$i' name='ucbinventory[]' type='checkbox' onclick='show_ucb_inventory(".$i.", ".$baseline_row['company_id'].", ".$warehouse_id.", ".$baseline_row['commodity_id']." )' $checked>";
					  echo "<input id='inventory_list$i' name='inventorylist[]' type='hidden' value='".$invlist['inventory_list']."'> </td>";
					  echo "<td class='style1'><input id='commodity$i' name='commodity[]' placeholder='Commodity name' value='".$baseline_row['commodity']."' required></td>";
					  echo "<td class='style1'><input id='commodity_cost$i' name='commodity_cost[]' placeholder='Cost' value='".$baseline_row['cost']."'></td>";
					  echo "<td class='style1'><input id='commodity_revenue$i' name='commodity_revenue[]' placeholder='Revenue' value='".$baseline_row['revenue']."'></td>";
					  echo "<td class='style1'>";
					  echo "<select name='commodity_uom[]' id='uom$i' required>";
					  echo "<option>--Select UOM--</option>";
					  $uom_res = db_query($uom_sql,db());
					  while($row_uom= array_shift($uom_res)){
						  $sel = "";
						  if($baseline_row['uom_id']==$row_uom['id'])
							  $sel =" selected ";
						  echo "<option value='".$row_uom['id']."' $sel>".$row_uom['unit_of_measure']."</option>";
					  }
					  //mysqli_data_seek($uom_res, 0);
					  echo "</select>";
					  echo "</td>";
					  $lock_savings_to_zero_val = "";
					  if ($baseline_row['lock_savings_to_zero'] == 1) { $lock_savings_to_zero_val = " checked "; }
					  echo "<td class='style1'><input id='comm_lock_savings_to_zero$i' name='comm_lock_savings_to_zero[]' type='checkbox' value=1 " . $lock_savings_to_zero_val . ">";
					  echo "<td class='style1'><div class='style1'><input type='button' value='Save' onclick='commodity_addupdt(". $i .", 1)'></div></td>";
					  echo "<td class='style1'><a href='javascript:void(0);ignore_edit($i)'>Ignore</a><br><a href='javascript:void(0);commodity_addupdt($i, 5)' style='color:#f00'>Delete</a> </td> &nbsp; <input name='c_id[]' value='".$baseline_row['commodity_id']."' id='c_id$i' type='hidden'></td>";
					  echo "</tr>";
					  $i++;
				  }
			  }
			  //else {
				  echo "<tr bgcolor='#e4e4e4' id='add_baseline_row_tr'  style='display:none'>";
				  echo "<td class='style1'>Commodity $i</td>";
				  echo "<td class='style1'><input id='ucbinventory$i' name='ucbinventory[]' type='checkbox' onclick='show_ucb_inventory($i, $ID, $warehouse_id)'>";
				  //echo "<input id='inventory_list$i' name='inventorylist[]' type='hidden' value='".$invlist['inventory_list']."'> </td>";
				  echo "<input id='inventory_list$i' name='inventorylist[]' type='hidden' value=''> </td>";
				  echo "<td class='style1'><input  id='commodity$i' name='commodity[]' placeholder='Commodity name'></td>";
				  echo "<td class='style1'><input id='commodity_cost$i' name='commodity_cost[]' placeholder='Cost' ></td>";
				  echo "<td class='style1'><input id='commodity_revenue$i' name='commodity_revenue[]' placeholder='Revenue'></td>";
				  echo "<td class='style1'>";
				  echo "<select name='commodity_uom[]' id='uom$i'>";
				  echo "<option>--Select UOM--</option>";
					  $uom_res = db_query($uom_sql,db());
					  while($row_uom= array_shift($uom_res)){
						  echo "<option value='".$row_uom['id']."'>".$row_uom['unit_of_measure']."</option>";
					  }
					mysqli_data_seek($uom_res, 0);
				  echo "</select>";
				  echo "</td>";
				  echo "<td class='style1'><input id='comm_lock_savings_to_zero$i' name='comm_lock_savings_to_zero[]' type='checkbox' value=1>";
				  //echo "<td class='style1'><input name='log[]' value='' readonly></td>";
				  echo "<td><input id='c_id$i' name='c_id[]' value='' type='hidden'>
				  <div class='style1'><input type='button' value='Save' onclick='commodity_addupdt($i, 0)'></div></td>";
				  echo "<td><a href='javascript:void(0);ignore_edit_newrow()'>Ignore</a></td>";
				  echo "</tr>";
			  //}
			  ?>
			  </tbody>
		</table>
		<div class="style1"><a href="javascript:void(0);add_baseline_row_new()">Add row</a></div>
	<form method="post" action="" name="frm_baseline">
		<input type="hidden" id="comm_name" name="comm_name" value="">
		<input type="hidden" id="comm_invlist" name="comm_invlist" value="">
		<input type="hidden" id="comm_cost" name="comm_cost" value="">
		<input type="hidden" id="comm_revenue" name="comm_revenue" value="">
		<input type="hidden" id="comm_uom" name="comm_uom" value="">
		<input type="hidden" id="comm_mod" name="comm_mod" value="">
		<input type="hidden" id="comm_id" name="comm_id" value="">
		<input type="hidden" id="com_lock_savings_to_zero" name="com_lock_savings_to_zero" value="">
		
	</form>
</div>
<script>
	function commodity_addupdt(d, mod){
		var comm_name = document.getElementById('commodity'+d).value;
		var comm_uom  = document.getElementById('uom'+d).value;
		var ucbinventory = document.getElementById('inventory_list'+d).value;
		
		if(comm_name == ''){
			alert("Please Fill Commodity Name");
			document.getElementById('commodity'+d).focus;
			return false;
		}else if(comm_uom == ''){
			alert("Please Select UOM");
			document.getElementById('uom'+d).focus;
			return false;
		}else{
			
			document.getElementById('comm_invlist').value = ucbinventory;
			document.getElementById('comm_name').value = comm_name;
			document.getElementById('comm_uom').value = comm_uom;
			document.getElementById('comm_cost').value = document.getElementById('commodity_cost'+d).value;
			document.getElementById('comm_revenue').value = document.getElementById('commodity_revenue'+d).value;
			document.getElementById('comm_id').value = document.getElementById('c_id'+d).value;
			document.getElementById('comm_mod').value = mod;
			
			if (document.getElementById('comm_lock_savings_to_zero'+d).checked)
			{
				document.getElementById('com_lock_savings_to_zero').value = 1;
			}else{
				document.getElementById('com_lock_savings_to_zero').value = "";
			}
			
			document.frm_baseline.submit();
		}
		
	}
	
	function add_baseline_row(){
		var tbl_baseline = document.getElementById("tbl_baseline");
		var row_count = parseInt(tbl_baseline.rows.length)-1;
		var existing_baseline_rows = '<?= $existing_baseline_rows?>';
		console.log(row_count);
		console.log(existing_baseline_rows);
		var last_dd = "uom"+(row_count-1);
		console.log(last_dd);
		var uom_dd = document.getElementById(last_dd).cloneNode(true);
		//row_count++;
		var newrow = document.createElement("tr");
		newrow.setAttribute("bgcolor","#f00");
		uom_dd.setAttribute("id","uom"+row_count);
		var rowhtml = "<td class='style1'>Commodity "+row_count+"</td>";
		rowhtml += "<td class='style1'><input  id='commodity"+row_count+"' name='commodity[]' placeholder='Commodity name' required></td>";
		rowhtml += "<td class='style1'><input  id='commodity_cost"+row_count+"' name='commodity_cost[]' placeholder='Commodity cost'></td>";
		rowhtml += "<td class='style1'><input  id='commodity_revenue"+row_count+"' name='commodity_revenue[]' placeholder='Commodity revenue'></td>";
		rowhtml += "<td class='style1'>"+uom_dd.outerHTML+"</td>";
		rowhtml += "<td>&nbsp;</td>";
		newrow.innerHTML = rowhtml;
		tbl_baseline.querySelector('tbody').appendChild(newrow, tbl_baseline.lastElementChild.previousSibling.lastElementChild);
	}
	
	function add_baseline_row_new(row_number){
		document.getElementById('comm_invlist').value = "";
		$("#add_baseline_row_tr").show();
	}

	function edit_baseline_row(row_number){
		//console.log(row_number);
		$("#view_baseline_"+row_number).hide();
		$("#edit_baseline_"+row_number).show();
		$("#add_baseline_row_tr").hide();
	}
	
	function ignore_edit(row_number){
		$("#view_baseline_"+row_number).show();
		$("#edit_baseline_"+row_number).hide();
	}

	function ignore_edit_newrow(row_number){
		$("#add_baseline_row_tr").hide();
	}
</script>
<br>
<!-- end of baseline table -->

<div id="delTrans_window" class="delTransaction"></div>

<b>Transactions</b><br><br>
<table cellSpacing="1" cellPadding="1" width="500" border="0">
	<tr>
		<td valign="top">
		
		<form name="entry_form" id="entry_form" action="" method="post">
		<table cellSpacing="1" cellPadding="1" width="500" border="0" id="table15">
			<tr align="middle">
				<td bgColor="#c0cdda" colSpan="10">
					<font size="1" color="#333333">WATER Invoices and Reports</font>
					<font size="1">&nbsp;- <a style="color:#0000FF;" href="<? echo $_SERVER['REQUEST_URI'] . "&show_all_trans=yes";?>">Show All Transaction</a></font>
				</td>
			</tr>	
			<?
				$sort_str_sel1 = ""; $sort_str_sel2 = ""; $sort_str_sel3 = ""; $sort_str_sel4 = ""; $sort_str_sel5 = ""; $sort_str_sel6 = "";
				$sort_str = "order by transaction_date desc";
				if (isset($_REQUEST['selsortby'])){
					if ($_REQUEST['selsortby'] == "invdate_new"){
						$sort_str_sel1 = " selected ";
						$sort_str = "order by invoice_date desc";
					}
					if ($_REQUEST['selsortby'] == "invdate_old"){
						$sort_str_sel2 = " selected ";
						$sort_str = "order by invoice_date asc";
					}
					if ($_REQUEST['selsortby'] == "vendor_asc"){
						$sort_str_sel3 = " selected ";
						$sort_str = "order by water_vendors.Name asc";
					}
					if ($_REQUEST['selsortby'] == "vendor_desc"){
						$sort_str_sel4 = " selected ";
						$sort_str = "order by water_vendors.Name desc";
					}
					if ($_REQUEST['selsortby'] == "totamt_low"){
						$sort_str_sel5 = " selected ";
						$sort_str = "order by amount asc";
					}
					if ($_REQUEST['selsortby'] == "totamt_high"){
						$sort_str_sel6 = " selected ";
						$sort_str = "order by amount desc";
					}
				}
			?>					
			<tr align="middle">
				<td bgColor="#c0cdda" colSpan="10">
					<font size="1">Sort By: <select name="selsortby" id="selsortby" onchange="this.form.submit()">
							<option value="invdate_new" <? echo $sort_str_sel1; ?>>Service End Date (newest-oldest)</option>
							<option value="invdate_old" <? echo $sort_str_sel2; ?>>Service End Date  (oldest-newest)</option>
							<option value="vendor_asc" <? echo $sort_str_sel3; ?>>Vendor Name (A-Z)</option>
							<option value="vendor_desc" <? echo $sort_str_sel4; ?>>Vendor Name (Z-A)</option>
							<option value="totamt_low" <? echo $sort_str_sel5; ?>>Total Amount (low-high)</option>
							<option value="totamt_high" <? echo $sort_str_sel6; ?>>Total Amount (high-low)</option>
					</select>
					&nbsp;
					Filter by Inv. No.: <input type="text" name="txtsrchinvno" id="txtsrchinvno" onkeypress="searchinvno(<? echo $id;?>, <? echo $_REQUEST["rec_id"];?>)"/>
					<input type="button" name="btn_search" id="btn_search" value="Search" onclick="searchinvno(<? echo $id;?>, <? echo $_REQUEST["rec_id"];?>)"/>
					</font>
				</td>
			</tr>	

			<tr>
				<td colspan="10">
					<div id="water_maintbl" style="height:300px; margin: 0; padding:0; overflow:scroll;">		
						<table cellSpacing="1" cellPadding="1" width="550" border="0" >
							<tr bgColor="#e4e4e4">
								<td style="width: 105px; height: 13px" class="style1" align="center">
									DATE ENTERED
								</td>		
								<td style="width: 105px; height: 13px" class="style1" align="center">
									LAST MODIFIED
								</td>		
								<td style="width: 91px; height: 13px" class="style1" align="center">
									INVOICE NUMBER
								</td>	
								<td style="width: 88px; height: 13px" class="style1" align="center">SERVICE END DATE
								</td>
								<td style="width: 87px; height: 13px" class="style1" align="center">VENDOR
								</td>				
								<td style="height: 13px; width: 87px;" class="style1" align="center">VENDOR REPORT
								</td> 		
								<td style="height: 13px; width: 87px;" class="style1" align="center">TOTAL AMOUNT		
								</td>		
								<td style="height: 13px; width: 50px;" class="style1" align="center">FILES		
								</td>		
								<td style="height: 13px; width: 87px;" class="style1" align="center">DELETE		
								</td>
								<td style="height: 13px; width: 87px;" class="style1" align="center">Entry Confirmed		
								</td>
								<td style="height: 13px; width: 87px;" class="style1" align="center">Make or receive payment?		
								</td>
							</tr>	
						
							<?
							if (isset($_REQUEST["rec_id"])){
								if (isset($_REQUEST["show_all_trans"])){
									$get_trans_sql = "SELECT * FROM water_transaction WHERE company_id = '" . $id . "' " . $sort_str;
								}else {
									$sql_cnt = "SELECT (SELECT COUNT(*) FROM `water_transaction` WHERE company_id = '" . $id . "' and id >= '". $_REQUEST["rec_id"] . "' order by id desc) AS `position`,";
									$sql_cnt .= "(SELECT COUNT(id) FROM `water_transaction` WHERE company_id = '" . $id . "' ) AS totcnt";
									$sql_cnt .= " FROM `water_transaction` ";
									$sql_cnt .= " WHERE company_id = '" . $id . "' and id = '".$_REQUEST["rec_id"]."'";
									//echo $sql_cnt . "<br>";
									$res_totcnt = db_query($sql_cnt, db() );
									$show_all = "no"; $rec_pos = 0;
									while ($row_totcnt = array_shift($res_totcnt)) 
									{
										if ($row_totcnt["position"] > 100) {
											$show_all = "yes";
											if ($row_totcnt["position"] > 0){
												$rec_pos = $row_totcnt["position"] - 1; 
											}
										}
									}

									if ($show_all == "yes") 
									{
										$get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = '" . $id . "' " . $sort_str . " LIMIT $rec_pos, 100";
									} else{
										$get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = '" . $id . "' " . $sort_str . " LIMIT 0, 100";
									}
								
								}
							}else {
								if (isset($_REQUEST["show_all_trans"])){
									$get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = '" . $id . "' " . $sort_str;
								}else{
									$get_trans_sql = "SELECT *, water_transaction.id as transid FROM water_transaction left join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE company_id = '" . $id . "' " . $sort_str . " LIMIT 0, 100";	
									//echo $get_trans_sql;
								}
							}
						
							//echo $get_trans_sql;
							$tran = db_query($get_trans_sql, db() );
							while ($tranlist = array_shift($tran)) 
							{
								$vender_nm = "";
								$q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '". $tranlist["vendor_id"] . "'";
								$query = db_query($q1, db());
								while($fetch = array_shift($query))
								{
									$vender_nm = $fetch['Name'];
								}
							
								$tran_status = $tranlist["tran_status"];
								switch($tran_status)
								{
									case 'Pickup':
									$stat = "circle_open.gif";
									BREAK;
									case '':
									$stat = "circle_open.gif";
									BREAK;
								}
								$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
								$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
								$full = "<img src=\"images/complete.jpg\" border=\"0\">";

							?>
							<tr id='rowid<? echo $tranlist["transid"]; ?>' bgColor='<? if ($tranlist["ignore"] == 0) { if ($tranlist["transid"]==$_REQUEST["rec_id"]) { echo "#CCFFCC";} else { echo "#e4e4e4";} } else { echo "#EE7373"; }?>'>	
								<td style='width: 98px; height: 13px' class='style1' align="center">	
									<font size="1"><? $the_date_timestamp = date("Y-m-d H:m:i", strtotime($tranlist["transaction_date"])); echo $the_date_timestamp; ?></font>
								</td>	 	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["last_edited"]; ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<? if ($tranlist["no_invoice_due_flg"] == 1) { ?>
										<font size="1">Marked as No Invoice</font>
									<? } else{ ?>
										<font size="1"><? echo $tranlist["invoice_number"]; ?></font>
									<? }  ?>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["invoice_date"]; ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $vender_nm; ?></font>
								</td>	
								<td style='width: 90px; height: 13px' class='style1' align="center">		
									<a href="viewCompany_func_water-mysqli.php?ID=<?=$_REQUEST["ID"];?>&show=watertransactions&company_id=<? echo $id; ?>&rec_type=<? echo $rec_type; ?>&proc=View&searchcrit=&id=<? echo $id; ?>&b2bid=<? echo $b2bid; ?>&rec_id=<? echo $tranlist['transid']; ?>&display=water_sort#watersort">		
									<? if (($tranlist["report_entered"] == 1) || $tranlist["no_invoice_due_flg"] == 1)
									{		 echo $full; 			} 		
									elseif ($tranlist["po_date"] != "") {			echo $half; 		} 
									else { 			echo $open; 		}
									?>
									</a>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	<?
									if ($tranlist["total_due_to_ucb"] <> 0){
										$totalAmout= $tranlist["total_due_to_ucb"];
									}else{
										$totalAmout= $tranlist["amount"];
									}									
									?>
									<font size="1">$<? echo number_format($totalAmout,2); ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<? if ($tranlist["scan_report"] != "") {
											$tmppos_1 = strpos($tranlist["scan_report"], "|");
											if ($tmppos_1 != false)
											{ 	
												$elements = explode("|", $tranlist["scan_report"]);
												for ($i = 0; $i < count($elements); $i++) {	?>										
													<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
												<?}
											}else {		
									?>
											<a target="_blank" href='water_scanreport/<? echo $tranlist["scan_report"]; ?>'><font size="1">View Attachments</font></a>
										<? }
										}?>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<?
									$getLevelQueryData = db_query("SELECT level FROM `loop_employees` WHERE id = '". $_COOKIE['employeeid']."'",db());
									$getLevel = array_shift($getLevelQueryData);
                                    $deleteUserCheck = $getLevel['level'];
									
                                    if(isset($deleteUserCheck) && $deleteUserCheck == 2){
                                    ?>
                                    	<a href='#' onclick="deletetrans(<? echo $tranlist['transid']; ?>,'true'); return false;">Delete</a>
                                    <? }else{ ?>
										<a id="delTrans<?=$tranlist['transid'] ?>" style="cursor:pointer;" onclick="deleteTranByPass('delTrans',<?=$tranlist['transid'] ?>)" data-target="#delTrans">Delete</a>
                                    <? } ?>
									
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<?
										if($tranlist["double_chk_confirm"]==1){
											echo "Confirmed by:<br>".$tranlist["confirmed_user"]."<br>".$tranlist["confirmed_date"];
										}
										else{
											echo "Pending";
										}
									?>
								</td>
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<?
										if($tranlist["make_receive_payment"]==1){
											echo "Yes";
										}
										else{
											echo "No";
										}
									?>
								</td>	
								

							</tr>
							<?
							$rec_id = $tranlist["transid"];	
							} 
							?>
						</table >	
					</div>
				</td>
			</tr>
			
			<tr bgColor="#e4e4e4">
				<td height="13" colspan="8" class="style1">
					<p align="center">
					<font size="1">
						<a href="viewCompany_func_water-mysqli.php?ID=<?=$_REQUEST["ID"];?>&show=watertransactions&company_id=<? echo $id; ?>&rec_type=<? echo $rec_type; ?>&proc=View&searchcrit=&id=<? echo $id; ?>&b2bid=<? echo $b2bid; ?>&action=water">Add Transaction</a>
					</font>
				</td>
			</tr>

		</table>
		</form>
		
		<br><br>

		<form name="entry_form_water" id="entry_form_water" action="" method="post">
		<table cellSpacing="1" cellPadding="1" width="500" border="0" id="table15">
			<tr align="middle">
				<td bgColor="#c0cdda" colSpan="10">
					<font size="1" color="#333333">WATER Pick-up Requests</font>
					<font size="1">&nbsp;- <a style="color:#0000FF;" href="<? echo $_SERVER['REQUEST_URI'] . "&show_all_trans_water=yes";?>">Show All Transaction</a></font>
				</td>
			</tr>	
			<?
				$sort_str_sel1 = ""; $sort_str_sel2 = ""; $sort_str_sel3 = ""; $sort_str_sel4 = ""; $sort_str_sel5 = ""; $sort_str_sel6 = "";
				$sort_str = "order by transaction_date desc";
				if (isset($_REQUEST['selsortby_water'])){
					if ($_REQUEST['selsortby_water'] == "pick-up-date-request_asc"){
						$sort_str_sel1 = " selected ";
						$sort_str = "order by pr_requestdate_php desc";
					}
					if ($_REQUEST['selsortby_water'] == "pick-up-date-request_desc"){
						$sort_str_sel2 = " selected ";
						$sort_str = "order by pr_requestdate_php asc";
					}
					if ($_REQUEST['selsortby_water'] == "pick-up-date_asc"){
						$sort_str_sel3 = " selected ";
						$sort_str = "order by pr_pickupdate asc";
					}
					if ($_REQUEST['selsortby_water'] == "pick-up-date_desc"){
						$sort_str_sel4 = " selected ";
						$sort_str = "order by pr_pickupdate desc";
					}
					if ($_REQUEST['selsortby_water'] == "commodity_asc"){
						$sort_str_sel5 = " selected ";
						$sort_str = "order by amount asc";
					}
					if ($_REQUEST['selsortby_water'] == "commodity_desc"){
						$sort_str_sel6 = " selected ";
						$sort_str = "order by amount desc";
					}
				}
			?>					
			<tr align="middle">
				<td bgColor="#c0cdda" colSpan="10">
					<font size="1">Sort By: <select name="selsortby_water" id="selsortby_water" onchange="this.form.submit()">
							<option value="pick-up-date-request_asc" <? echo $sort_str_sel1; ?>>Pick-up Date Request (newest-oldest)</option>
							<option value="pick-up-date-request_desc" <? echo $sort_str_sel2; ?>>Pick-up Date Request  (oldest-newest)</option>
							<option value="pick-up-date_asc" <? echo $sort_str_sel3; ?>>Pick-up Date (A-Z)</option>
							<option value="pick-up-date_desc" <? echo $sort_str_sel4; ?>>Pick-up Date (Z-A)</option>
							<option value="commodity_asc" <? echo $sort_str_sel5; ?>>Commodity (A-Z)</option>
							<option value="commodity_desc" <? echo $sort_str_sel6; ?>>Commodity (Z-A)</option>
					</select>
					</font>
				</td>
			</tr>	

			<tr>
				<td colspan="10">
					<div id="water_maintbl" style="height:300px; margin: 0; padding:0; overflow:scroll;">		
						<table cellSpacing="1" cellPadding="1" width="550" border="0" >
							<tr bgColor="#e4e4e4">
								<td style="width: 105px; height: 13px" class="style1" align="center">
									Pick-up Date Request
								</td>		
								<td style="width: 105px; height: 13px" class="style1" align="center">
									Pick-up Date 
								</td>		
								<td style="width: 91px; height: 13px" class="style1" align="center">
									Commodity
								</td>	
								<td style="width: 88px; height: 13px" class="style1" align="center">Trailer #
								</td>
								<td style="width: 87px; height: 13px" class="style1" align="center">Pickup Status
								</td>				
								<td style="height: 13px; width: 87px;" class="style1" align="center">Requested By
								</td> 		
								<td style="height: 13px; width: 87px;" class="style1" align="center">Bill of Lading		
								</td>		
							</tr>	
						
							<?
						
							if (isset($_REQUEST["show_all_trans"])){
								$get_trans_sql = "SELECT *, water_loop_transaction.id as transid FROM water_loop_transaction left join supplier_commodity_details on water_loop_transaction.commodity = supplier_commodity_details.id WHERE warehouse_id = " . $id . " " . $sort_str;
							}else{
								$get_trans_sql = "SELECT *, water_loop_transaction.id as transid FROM water_loop_transaction left join supplier_commodity_details on water_loop_transaction.commodity = supplier_commodity_details.id WHERE warehouse_id = " . $id . " " . $sort_str . " LIMIT 0, 100";	
								//echo $get_trans_sql;
							}
							//echo $get_trans_sql;
							$tran = db_query($get_trans_sql, db() );
							while ($tranlist = array_shift($tran)) 
							{
								$tran_status = $tranlist["tran_status"];
								switch($tran_status)
								{
									case 'Pickup':
										$stat = "circle_open.gif";
										BREAK;
									case '':
										$stat = "circle_open.gif";
										BREAK;
								}
								$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
								$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
								$full = "<img src=\"images/complete.jpg\" border=\"0\">";

							?>
							<tr id='rowid_water<? echo $tranlist["transid"]; ?>' bgColor='<? if ($tranlist["ignore"] == 0) { if ($tranlist["transid"]==$_REQUEST["rec_id_water"]) { echo "#CCFFCC";} else { echo "#e4e4e4";} } else { echo "#EE7373"; }?>'>	
								<td style='width: 98px; height: 13px' class='style1' align="center">	
									<font size="1"><? $the_date_timestamp = date("Y-m-d H:m:i", strtotime($tranlist["pr_requestdate_php"])); echo $the_date_timestamp; ?></font>
								</td>	 	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["pr_pickupdate"]; ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["commodity"]; ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["dt_trailer"]; ?></font>
								</td>	
								<td style='width: 90px; height: 13px' class='style1' align="center">		
									<a href="viewCompany_func_water-mysqli.php?ID=<?=$_REQUEST["ID"];?>&show=watertransactions&company_id=<? echo $id; ?>&rec_type=<? echo $rec_type; ?>&proc=View&searchcrit=&id=<? echo $id; ?>&b2bid=<? echo $b2bid; ?>&rec_id_water=<? echo $tranlist['transid']; ?>&display=water_sort_pickup#watersort">		
									<? if (($tranlist["cp_date"] != "" && $tranlist["pa_pickupdate"] != "" ) || $tranlist["cnfmPickup"] != "")
									{		 echo $full; 			} 		
									elseif ($tranlist["pa_pickupdate"] != "" ) {			echo $half; 		} 
									else { 			echo $open; 		}
									?>
									</a>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<font size="1"><? echo $tranlist["pr_requestdate_php"]; ?></font>
								</td>	
								<td style='width: 92px; height: 13px' class='style1' align="center">	
									<? if ($tranlist["bol_filename"] != "") { 
										?>
											<a target="_blank" href='files/<? echo $tranlist["bol_filename"]; ?>'><font size="1">View BOL</font></a>
									<?}?>
								</td>	

							</tr>
							<?
								$water_rec_id = $tranlist["transid"];	
							} 
							?>
						</table >	
					</div>
				</td>
			</tr>

		</table>
		</form>
		
		<br><br>
		
		<!-- Display the Pending Invoice details -->
		<br>
		<? include ("search_result_include_water_box_table.php"); ?>
	    </td>
    </tr>
</table>

<!--</form>-->
 
<!-- End Set Up Initial Transaction --> 
 
<!-- Set Up Initial WATER Transaction --> 
<!-- Write Initial Fields in Transaction Table Depending on Record Type --> 
 
<? if ($_GET["action"] == 'water') { 

$company_id = $_GET["id"];
$id = $_GET["id"];
$rec_type = $_GET["rec_type"];
$user = $_COOKIE['userinitials'];
$rec_id = $_GET["rec_id"];
$today = date('m/d/y h:i a'); 

$qry_newtrans = "INSERT INTO water_transaction SET company_id = '" . $company_id . "', tran_status = 'Pickup'";
$res_newtrans = db_query($qry_newtrans,db() );

$rec_id = tep_db_insert_id();

// echo $qry_newtrans;
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: viewCompany_func_water-mysqli.php?ID=' . $_REQUEST["ID"] . '&show=watertransactions&company_id=' . $_GET["company_id"] . '&id=' . $_GET["company_id"] . '&proc=View&searchcrit=&rec_type=' . $_GET["rec_type"] . '&proc=View&searchcrit=&rec_id=' . $rec_id .'&b2bid=' . $_GET["b2bid"] . '&display=water_sort#watersort'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"viewCompany_func_water-mysqli.php?ID=" . $_REQUEST["ID"] . "&show=watertransactions&company_id=" . $_GET["company_id"] . "&id=" . $_GET["company_id"] . "&proc=View&searchcrit=&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=&rec_id=" . $rec_id .'&b2bid=' . $_GET["b2bid"] .  "&display=water_sort#watersort\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=ID=" . $_REQUEST["ID"] . "&show=watertransactions&company_id=" . $_GET["company_id"] . "&id=" . $_GET["company_id"] . "&proc=View&searchcrit=&rec_type=" . $_GET["rec_type"] . "&proc=View&searchcrit=&rec_id=" . $rec_id .'&b2bid=' . $_GET["b2bid"] .  "&display=water_sort#watersort\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
}
?>
<div id="watersort"></div>
<?
 if ($_GET["display"] == 'water_sort') { 
	include ("search_result_include_water_sort.php");
 } 
 
 if ($_GET["display"] == 'water_sort_pickup') { 
 	include ("search_result_include_water_pickup.php");
 } 
 

}?>
