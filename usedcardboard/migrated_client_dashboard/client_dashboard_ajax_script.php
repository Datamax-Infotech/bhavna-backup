<script language="javascript">
    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
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
        if (e.value != "") {
            e.value = parseFloat(e.value).toFixed(2);
        }
    }

    function swicthacc(loginid, client_companyid) {

        var n_left = f_getPosition(document.getElementById('btnswitchacc'), 'Left');
        var n_top = f_getPosition(document.getElementById('btnswitchacc'), 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';>Close</a><br>" + xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = n_left - 150 + 'px';
                document.getElementById('light').style.top = n_top + 50 + 'px';
            }
        }

        xmlhttp.open("GET", "show_account_list.php?loginid=" + loginid + "&client_companyid=" + client_companyid, true);
        xmlhttp.send();
    }

    function show_loading() {
        document.getElementById('overlay').style.display = 'block';
    }

    function show_loading1(arg1, arg_show) {
        document.getElementById('overlay').style.display = 'block';

        //$('#submit').click(function(event){ 
        $("#div_element").load('client_dashboard.php?compnewid=' + arg1 + '&show=' + arg_show);

        //}); 

    }

    function remove_loading() {
        document.getElementById('overlay').style.display = 'none';
    }

    function display_file_boxdesc(filename, formtype, tmpcnt) {

        var n_left = f_getPosition(document.getElementById('box_desc'), 'Left');
        var n_top = f_getPosition(document.getElementById('box_desc'), 'Top');

        document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';
        //document.getElementById('fade').style.display='block';

        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 'px';

    }

    function display_file(filename, formtype, tmpcnt) {

        var n_left = f_getPosition(document.getElementById('po_order_show' + tmpcnt), 'Left');
        var n_top = f_getPosition(document.getElementById('po_order_show' + tmpcnt), 'Top');

        document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';
        //document.getElementById('fade').style.display='block';

        document.getElementById('light').style.left = n_left - 130 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

    }

    function display_inv_file(filename, formtype, tmpcnt) {

        var n_left = f_getPosition(document.getElementById('inv_file_show' + tmpcnt), 'Left');
        var n_top = f_getPosition(document.getElementById('inv_file_show' + tmpcnt), 'Top');

        document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';
        //document.getElementById('fade').style.display='block';

        document.getElementById('light').style.left = n_left - 380 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

    }

    function display_bol_file(filename, formtype, tmpcnt) {

        var n_left = f_getPosition(document.getElementById('bol_show' + tmpcnt), 'Left');
        var n_top = f_getPosition(document.getElementById('bol_show' + tmpcnt), 'Top');

        document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" + formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';
        //document.getElementById('fade').style.display='block';

        document.getElementById('light').style.left = n_left - 430 + 'px';
        document.getElementById('light').style.top = n_top + 20 + 'px';

    }



    function chkchgpwd() {
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

        if (document.getElementById("txt_newpwd_re").value == "") {
            alert("Please enter the Re-type password.");
            document.getElementById("txt_newpwd_re").focus();
            return false;
        }

        var str1 = document.getElementById("txt_oldpwd").value;
        var str2 = document.getElementById("hd_chgpwd_val").value;
        var compareval = str1.localeCompare(str2);

        if (compareval != 0) {
            alert("Entered Old password is incorrect, please check.");
            document.getElementById("txt_oldpwd").focus();
            return false;
        }

        var str1 = document.getElementById("txt_newpwd").value;
        var str2 = document.getElementById("txt_newpwd_re").value;
        var compareval = str1.localeCompare(str2);

        if (compareval != 0) {
            alert("Entered Re-type password does not match with New password.");
            document.getElementById("txt_newpwd").focus();
            return false;
        }

        document.frmchgpwd.submit();
    }

    function boxreport(repchk, compnewid, client_loopid) {
        var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;
        if (document.getElementById("dView")) {
            var dView = document.getElementById("dView").value;
        } else {
            var dView = "";
        }

        if (start_date == "") {
            alert("Please enter the Date From.");
            return false;
        }
        if (end_date == "") {
            alert("Please enter the Date To.");
            return false;
        }

        document.getElementById("boxtrailer_rep_div").innerHTML = "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("boxtrailer_rep_div").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "client_dashboard_boxreport.php?repchk=" + repchk + "&compnewid=" + compnewid + "&dView=" + dView + "&start_date=" + start_date + "&end_date=" + end_date + "&client_loopid=" + client_loopid, true);
        xmlhttp.send();
    }

    function boxreport_trailer(repchk, trailer, id, runningcnt) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("boxtrailer_bytrailer_div").innerHTML = xmlhttp.responseText;
                var n_top = f_getPosition(document.getElementById('boxreport_trailerdiv' + runningcnt), 'Top');
                var n_left = f_getPosition(document.getElementById('boxreport_trailerdiv' + runningcnt), 'Left');

                document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;" + xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = (n_left - 200) + 'px';
                document.getElementById('light').style.top = n_top + 'px';

            }
        }
        xmlhttp.open("GET", "client_dashboard_boxrep_trailer.php?repchk=" + repchk + "&trailer_no=" + trailer + "&trans_rec_id=" + id, true);
        xmlhttp.send();
    }


    function selltotoggle() {
        if (document.getElementById('expand').style.display == 'block') {
            document.getElementById('expand1').style.display = 'block';
            document.getElementById('expand').style.display = 'none';
            document.getElementById("ex_co_div").innerHTML = "Expand";
        } else {
            document.getElementById('expand1').style.display = 'none';
            document.getElementById('expand').style.display = 'block';
            document.getElementById("ex_co_div").innerHTML = "Collapse";
        }
    }

    function shiptotoggle() {
        if (document.getElementById('expand_ship').style.display == 'block') {
            document.getElementById('expand1_ship').style.display = 'block';
            document.getElementById('expand_ship').style.display = 'none';
            document.getElementById("ex_co_div_ship").innerHTML = "Expand";
            window.parent.document.getElementById('show_compinfo').height = '450px';
        } else {
            document.getElementById('expand1_ship').style.display = 'none';
            document.getElementById('expand_ship').style.display = 'block';
            document.getElementById("ex_co_div_ship").innerHTML = "Collapse";
            window.parent.document.getElementById('show_compinfo').height = document.body.scrollHeight;
        }
    }

    function billtotoggle() {
        if (document.getElementById('expand_bill').style.display == 'block') {
            document.getElementById('expand1_bill').style.display = 'block';
            document.getElementById('expand_bill').style.display = 'none';
            document.getElementById("ex_co_div_bill").innerHTML = "Expand";
            window.parent.document.getElementById('show_compinfo').height = '450px';
        } else {
            document.getElementById('expand1_bill').style.display = 'none';
            document.getElementById('expand_bill').style.display = 'block';
            document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
            window.parent.document.getElementById('show_compinfo').height = document.body.scrollHeight;
        }
    }

    function Remove_favorites(favItemId, compnewid) {
        show_loading();
        var hdnFavItemsAction = document.getElementById('hdnFavItemsAction').value;

        var repchk_str = document.getElementById('repchk_str').value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 'true') {
                    window.location.replace("https://boomerang.usedcardboardboxes.com/client_dashboard.php?companyid_login=" + compnewid + "&show=favorites&" + repchk_str);
                }
            }
        }

        xmlhttp.open("GET", "client_dashboard_remove_favitem.php?favItemId=" + favItemId + "&hdnFavItemsAction=" + hdnFavItemsAction + "&compnewid=" + compnewid + "&repchk=yes", true);
        xmlhttp.send();
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- BOX PROFILE SCRIPT START -->
	
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

		// TEST GAYLORD MATCHING TOOL POPUP SECTION START 	
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
			sstr = sstr +">All Items (Ignore Criteria)</option></select>"

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
			
			xmlhttp.open("GET","quote_request_gaylords_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&gbox="+boxid+"&g_timing=2&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);
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

			xmlhttp.open("GET","quote_request_gaylords_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&client_flg="+client_flg+"&sort_g_location="+sort_g_location+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		// TEST GAYLORD MATCHING TOOL POPUP SECTION ENDS 

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

		// TEST SHIPPING MATCHING TOOL POPUP SECTION START 
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
				
			xmlhttp.open("GET","quote_request_shipping_tool_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+ client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			
			xmlhttp.open("GET","quote_request_shipping_tool_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_shipping+"&sort_g_tool2="+ sort_g_tool_shipping2+"&client_flg="+ client_flg+"&sort_g_location_shipping="+sort_g_location_shipping+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);	 	
			xmlhttp.send();
		}	
		//  TEST SHIPPING MATCHING TOOL POPUP SECTION ENDS 

		//<!-- TEST PALLET MATCHING TOOL POPUP SECTION START
		
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
			
			xmlhttp.open("GET","quote_req_pallet_matching_new_test.php?first_load=1&repchk=yes&ID="+id+"&inboxprofile="+ inboxprofile+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+ "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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

			xmlhttp.open("GET","quote_req_pallet_matching_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_pallet+"&sort_g_tool2="+ sort_g_tool_pallet2 + "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&sort_g_location_pallet="+sort_g_location_pallet+"&compnewid=<?php echo $_REQUEST['compnewid'];?>", true);			
			xmlhttp.send();
		}
		//<!-- TEST PALLET MATCHING TOOL POPUP SECTION ENDS 

		//<!-- TEST SUPERSACKS MATCHING TOOL POPUP SECTION START
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
			xmlhttp.open("GET","quote_req_supersacks_matching_new_test.php?first_load=1&repchk=yes&inboxprofile="+ inboxprofile+"&ID="+id+"&g_timing=2&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+viewflg+"&load_all="+load_all+"&client_flg="+client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			
			xmlhttp.open("GET","quote_req_supersacks_matching_new_test.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_supersack+"&sort_g_tool2="+ sort_g_tool_supersack2+"&client_flg="+ client_flg+"&sort_g_location_supersack="+sort_g_location_supersack+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
			xmlhttp.send();
		}
		// TEST SUPERSACKS MATCHING TOOL POPUP SECTION ENDS 

		// TEST OTHER MATCHING  SECTION START
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
		// TEST OTHER MATCHING  SECTION END

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
				
				xmlhttp.open("GET","quote_request_gaylords_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			
			xmlhttp.open("GET","quote_request_gaylords_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing+"&sort_g_tool2="+ sort_g_tool2+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			xmlhttp.open("GET","quote_request_shipping_tool_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&sort_g_tool2="+flg+"&load_all="+load_all+"&client_flg="+ client_flg+"&g_timing="+g_timing+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			
			xmlhttp.open("GET","quote_request_shipping_tool_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_shipping+"&sort_g_tool2="+ sort_g_tool_shipping2+"&client_flg="+ client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);	 	
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
				
				xmlhttp.open("GET","quote_req_supersacks_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+"&display_view="+viewflg+"&load_all="+load_all+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			
			xmlhttp.open("GET","quote_req_supersacks_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_supersack+"&sort_g_tool2="+ sort_g_tool_supersack2+"&client_flg="+ client_flg+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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
			xmlhttp.open("GET","quote_req_pallet_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flg+"&display_view="+viewflg+ "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&load_all="+load_all+"&compnewid=<?php echo $_REQUEST['compnewid'];?>",true);			
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

			xmlhttp.open("GET","quote_req_pallet_matching_new.php?repchk=yes&ID="+id+"&gbox="+boxid+"&display-allrec="+flgs+"&display_view="+viewflgs+"&g_timing="+g_timing_pallet+"&sort_g_tool2="+ sort_g_tool_pallet2 + "&pallet_height="+pallet_height+"&pallet_width="+pallet_width+"&compnewid=<?php echo $_REQUEST['compnewid'];?>", true);			
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