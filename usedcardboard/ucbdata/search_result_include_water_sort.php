<?
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

set_time_limit(0);

?>
<Font Face='arial' size='2'>
 
<br>
<style>
	.vendor_payment_css tr td{
		padding: 2px;
	}
</style>
<script LANGUAGE="JavaScript">

var specialKeys = new Array();
specialKeys.push(8); //Backspace
specialKeys.push(9); //Tab
specialKeys.push(37); //Arrows
specialKeys.push(38); //Arrows
specialKeys.push(39); //Arrows
specialKeys.push(40); //Arrows
specialKeys.push(36); //Home
specialKeys.push(35); //End
specialKeys.push(46); //Delete
function IsNumeric(e) {
	var keyCode = e.which ? e.which : e.keyCode
	var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
	return ret;
}

function paddy(num, padlen, padchar) {
    var pad_char = typeof padchar !== 'undefined' ? padchar : '0';
    var pad = new Array(1 + padlen).join(pad_char);
    return (pad + num).slice(-pad.length);
}

function chkduplicate_onservice_end_dt(y,m,d) 
{

	var monthval = paddy(m, 2);
	var dayval = paddy(d, 2);
	
	document.getElementById("invoice_date").value = y + "-" + monthval + "-" + dayval;	
	if (document.getElementById("invoice_date").value != ""){
		var retstr = "";
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
			if (xmlhttp.responseText == "Duplicate"){
				alert("The UCBZeroWaste Consolidated Invoice has already been created for this month. Please enter the Vendor Report under the following month, or, create an UCBZW Accounting Log instructing the Consolidated Invoice Team to charge this invoice under the next month UCBZeroWaste Consolidated Invoice.");
			}
		  }
		}

		xmlhttp.open("POST","water_entry_chk_onservice_end_dt.php?comp_id=" + document.getElementById("warehouse_id").value + "&vendor_id=" + document.getElementById("vendor_id").value + "&invoice_date=" + document.getElementById("invoice_date").value,true);			
		xmlhttp.send();	
	}	
}


function showfees_other(row_cnt) 
{
	//if (document.getElementById("selfees"+row_cnt).value == "19"){
	//	document.getElementById("txtcharge"+row_cnt).removeEventListener("onkeypress", "chknumberval");
	//}
	
	if (document.getElementById("selfees"+row_cnt).value != ""){
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
			document.getElementById("savings_calculation_category"+row_cnt).value = xmlhttp.responseText;
		  }
		}

		xmlhttp.open("POST","water_entry_getsaving_cat_id.php?add_fee_id=" + document.getElementById("selfees"+row_cnt).value, true);			
		xmlhttp.send();	
	}	
}


function chknumberval(evt, row_cnt) 
{
	if (document.getElementById("selfees"+row_cnt).value == "19"){

		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 45 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
		 return false;

		return true;
	}else{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
		 return false;

		return true;
	}
}

function chkduplicateinv() 
{
	if (document.getElementById("invoice_no").value != ""){
		var retstr = "";
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
			if (xmlhttp.responseText == "Duplicate"){
				alert("A Vendor Report with the same Invoice Number - " + document.getElementById("invoice_no").value + " has already been added, you will not be able to save two Vendor Reports with the same Invoice Number.");
				document.getElementById("invoice_no").value = "";
			}
		  }
		}

		xmlhttp.open("POST","water_entry_chkinv.php?comp_id=" + document.getElementById("warehouse_id").value + "&invno=" + document.getElementById("invoice_no").value,true);			
		xmlhttp.send();	
	}	
}

function removeattachment(invfile, recid, compid, wid)
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
		document.getElementById("scanfiletd").innerHTML = xmlhttp.responseText;
	  }
	}

	xmlhttp.open("POST","water_entry_removefile.php?recid=" + recid + "&invfile=" + invfile + "&compid=" + compid + "&wid=" + wid,true);			
	xmlhttp.send();	
}
function removeattachment_proof(invfile, recid, compid, wid) 
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
		document.getElementById("proof_filetd").innerHTML = xmlhttp.responseText;
	  }
	}

	xmlhttp.open("POST","water_entry_removefile.php?payment_proof=1&recid=" + recid + "&invfile=" + invfile + "&compid=" + compid + "&wid=" + wid,true);			
	xmlhttp.send();	
}

function mark_btn_no_inv() 
{
	var msgstr = "";
	if (document.getElementById("vendor_id").value == ""){
		msgstr = msgstr + "Vendor\r\n";
	}
	if (document.getElementById("invoice_date").value == ""){
		msgstr = msgstr + "Service End Date\r\n";
	}

	if (msgstr != ""){
		alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
		return false;
	}else{
		document.getElementById("mark_no_inv").value = "yes";
		document.frmsort.action ="water_addbox_report.php";
		document.frmsort.submit();
		return true;
	}		
	
}

function mark_btn_no_inv_edit_undo() 
{
	document.getElementById("mark_no_inv_undo").value = "yes";
	document.frmsortedit.action ="water_addbox_report.php";
	document.frmsortedit.submit();
}

function mark_btn_no_inv_edit() 
{
	var msgstr = "";
	if (document.getElementById("vendor_id").value == ""){
		msgstr = msgstr + "Vendor\r\n";
	}
	if (document.getElementById("invoice_date").value == ""){
		msgstr = msgstr + "Service End Date\r\n";
	}

	if (msgstr != ""){
		alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
		return false;
	}else{
		document.getElementById("mark_no_inv").value = "yes";
		document.frmsortedit.action ="water_addbox_report.php";
		document.frmsortedit.submit();
		return true;
	}		
	
}


function onsubmitform() 
{
	document.getElementById("water_entry_btn").style.display = "none";
	
	$tot_count = 0;
	if (document.getElementById("row_cnt_main"))
	{
		$tot_count = document.getElementById("row_cnt_main").value;
	}
	var msgstr = "";
	
	if (document.getElementById("water_item1"))
	{
		var mainstr = document.getElementById("water_item1").value;
		var str_array = mainstr.split('|');
		
		if (str_array[5] =="By Weight"){
			if (document.getElementById("vendor_id").value == ""){
				msgstr = msgstr + "Vendor\r\n";
			}
			if (document.getElementById("invoice_date").value == ""){
				msgstr = msgstr + "Service End Date\r\n";
			}
			if (document.getElementById("invoice_no").value == ""){
				msgstr = msgstr + "Invoice Number\r\n";
			}
			
			if (document.getElementById("invoice_due_date").value == ""){
				msgstr = msgstr + "Invoice Due Date\r\n"; 
			}
			
			if ($tot_count == 0){
				msgstr = msgstr + "atleast one Material detail\r\n";
			}else {
				if (document.getElementById("water_item1").value != "0"){
					if (document.getElementById("txtcostperunit1").value == "" && document.getElementById("txtrevenueperunit1").value == ""){
					
						msgstr = msgstr + "'Cost Per Unit' OR 'Revenue Per Unit'\r\n";
					}
				
					if (document.getElementById("txtweight1").value == ""){
						msgstr = msgstr + "'Weight' (EVEN IF the material item is priced 'Per Item' OR 'Per Number of Pulls', an estimated weight amount has to be inserted)";
					}
				}	
			}
		
		}else{

			if (document.getElementById("vendor_id").value == ""){
				msgstr = msgstr + "Vendor\r\n";
			}
			if (document.getElementById("invoice_date").value == ""){
				msgstr = msgstr + "Service End Date\r\n";
			}
			if (document.getElementById("invoice_no").value == ""){
				msgstr = msgstr + "Invoice Number\r\n";
			}
			if (document.getElementById("invoice_due_date").value == ""){
				msgstr = msgstr + "Invoice Due Date\r\n"; 
			}
			
			if ($tot_count == 0){
				msgstr = msgstr + "atleast one Material detail\r\n";
			}else {
				if (document.getElementById("water_item1").value != "0"){
					if (document.getElementById("txtcostperunit1").value == "" && document.getElementById("txtrevenueperunit1").value == ""){
						msgstr = msgstr + "'Cost Per Unit' OR 'Revenue Per Unit'\r\n";
					}
					if (document.getElementById("txtunitcount1").value == ""){
						msgstr = msgstr + "'Unit Count' (ONLY if the material item is priced 'Per Item' or 'Per Number of Pulls')\r\n";
					}
					if (document.getElementById("txtweight1").value == ""){
						msgstr = msgstr + "'Weight' (EVEN IF the material item is priced 'Per Item' OR 'Per Number of Pulls', an estimated weight amount has to be inserted)";
					}
				}	
			}
		}
		if (msgstr != ""){
			alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
			document.getElementById("water_entry_btn").style.display = "inline";
			return false;
		}else{
			document.frmsort.action ="water_addbox_report.php";
			return true;
		}		
	}else{
		if (document.getElementById("vendor_id").value == ""){
			msgstr = msgstr + "Vendor\r\n";
		}
		if (document.getElementById("invoice_date").value == ""){
			msgstr = msgstr + "Service End Date\r\n";
		}
		if (document.getElementById("invoice_no").value == ""){
			msgstr = msgstr + "Invoice Number\r\n";
		}
		if (document.getElementById("invoice_due_date").value == ""){
			msgstr = msgstr + "Invoice Due Date\r\n"; 
		}
		
		if ($tot_count == 0){
			msgstr = msgstr + "atleast one Material detail\r\n";
		}
		if (msgstr != ""){
			alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
			document.getElementById("water_entry_btn").style.display = "inline";
			return false;
		}else{
			document.frmsort.action ="water_addbox_report.php";
			return true;
		}		
		
	}
}

function resetctrls()
{
	document.getElementById("vendor_id").value = "";
	document.getElementById("invoice_date").value = "";
	document.getElementById("invoice_no").value = "";
	document.getElementById("final_total").value = "";
	document.getElementById("boxnotes").value = "";
	document.getElementById("addfees_total").value = "";
	
	if (document.getElementById("txttotal_revenue")){
		document.getElementById("txttotal_revenue").value = "";
		document.getElementById("txttotal_cost").value = "";
		document.getElementById("txttotal_profit").value = "";
	}
	document.getElementById("div_boxrep").innerHTML = "";
	
	var cnt = document.getElementById("row_cnt_fees").value;

	for (var i =cnt; i > 1; i--) 
	{
		document.getElementById("tblmain_fees").deleteRow(i);
	}	
	document.getElementById("row_cnt_fees").value = "1";
	
	var addfees_ctrl1 = document.getElementsByName('selfees[]');
	var addfees_ctrl2 = document.getElementsByName('txtcharge[]');
	var addfees_ctrl3 = document.getElementsByName('txtOccurrences[]');
	var addfees_ctrl4 = document.getElementsByName('txtremark_fees[]');
	var addfees_ctrl5 = document.getElementsByName('txtfeetotalcost[]');

	addfees_ctrl1[0].value = "";
	addfees_ctrl2[0].value = "";
	addfees_ctrl3[0].value = "";
	addfees_ctrl4[0].value = "";
	addfees_ctrl5[0].value = "";
}

function loadaddfees() 
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
		document.getElementById("div_fees").innerHTML = document.getElementById("div_fees").innerHTML + xmlhttp.responseText;
	  }
	}

	xmlhttp.open("POST","water_getaddfees.php",true);			
	xmlhttp.send();	
}

function update_fee_total() 
{
	var totval = 0; 
	var addfee = 0;

	var addfees = document.getElementsByName('txtcharge[]');
	var feesOccurrences = document.getElementsByName('txtOccurrences[]');
	var feestot = document.getElementsByName('txtfeetotalcost[]');
	
	for (var i =0; i < addfees.length; i++) 
	{
	  addfee = Number(addfees[i].value);	
	  feerecurance = Number(feesOccurrences[i].value);	
	  
		if (feerecurance != ""){
			if (feerecurance > 0){
				totval = totval + (addfee*feerecurance);
				feestot[i].value = (addfee*feerecurance).toFixed(2);
			}else{
				totval = totval + addfee;
			}
		}
	}	
	
	document.getElementById("tot_fees").value = totval;	
	document.getElementById("addfees_total").value = totval;	
	
	update_total();
}

function add_newrow_fees(){

	var cnt = document.getElementById("row_cnt_fees").value;
	var row_str = document.getElementById("row_str_fees").value;
	cnt = Number(cnt) + 1;

	var row_str_new = row_str.replace(/ctrltoreplace/g,  cnt.toString());

	document.getElementById("tblmain_fees").insertRow(cnt).innerHTML = row_str_new;
	
	document.getElementById("row_cnt_fees").value = cnt;
}

function remove_newrow_chrg(r){
	var i = r.parentNode.parentNode.rowIndex;
	document.getElementById("tblmain_fees").deleteRow(i);

	var cnt = document.getElementById("row_cnt_fees").value;
	cnt = Number(cnt) - 1;
	document.getElementById("row_cnt_fees").value = cnt;
	
	update_fee_total()
}

function add_newrow_main(){
	var cnt = document.getElementById("row_cnt_main").value;
	var row_str = document.getElementById("row_str_main").value;
	cnt = Number(cnt) + 1;

	var row_str_new = row_str.replace(/ctrltoreplace/g,  cnt.toString());

	document.getElementById("tblmain_entry").insertRow(cnt+1).innerHTML = row_str_new;

	document.getElementById("row_cnt_main").value = cnt;
}

function remove_newrow_main(r){
	row_cnt_main = document.getElementById("row_cnt_main").value;

	var i = r.parentNode.parentNode.rowIndex;

	document.getElementById("tblmain_entry").deleteRow(i);

	var cnt = document.getElementById("row_cnt_main").value;
	cnt = Number(cnt) - 1;
	document.getElementById("row_cnt_main").value = cnt;

	var nextOldRow = Number(i)-1;
	for(var row = Number(i)-1; cnt >= row; row++){
		//var nextNewRow = Number(row) - 1;
		var nextOldRow = Number(nextOldRow) + 1;

		/*WATER ITEM*/
		var water_item = document.getElementById("water_item"+nextOldRow);
		if(water_item != ''){
			water_item.setAttribute("id", "water_item"+row);
			/*var attrwater_item = document.createAttribute("onchange");
  			attrwater_item.value = "water_item_showdetails("+row+")";
  			water_item.setAttributeNode(attrwater_item);*/
		}
		

		/*COST (US $) PER UNIT*/
		var txtcostperunit = document.getElementById("txtcostperunit"+nextOldRow);
		if(txtcostperunit != ''){
			txtcostperunit.setAttribute("id", "txtcostperunit"+row);
			var attr = document.createAttribute("onchange");
  			attr.value = "calculatecost("+row+")";
  			txtcostperunit.setAttributeNode(attr);
		}
		var txtcostunit = document.getElementById("txtcostunit"+nextOldRow);
		if(txtcostunit != ''){
			txtcostunit.setAttribute("id", "txtcostunit"+row);
		}else{
			return true;
		}

		/*REVENUE (US $) PER UNIT*/
		var txtrevenueperunit = document.getElementById("txtrevenueperunit"+nextOldRow);
		if(txtrevenueperunit != ''){
			txtrevenueperunit.setAttribute("id", "txtrevenueperunit"+row);
			var attrtxtrevenueperunit = document.createAttribute("onchange");
  			attrtxtrevenueperunit.value = "calculaterevenue("+row+")";
  			txtrevenueperunit.setAttributeNode(attrtxtrevenueperunit);/**/
		}
		var txtrevenueunit = document.getElementById("txtrevenueunit"+nextOldRow);
		if(txtrevenueunit != null || txtrevenueunit != '' ){
			txtrevenueunit.setAttribute("id", "txtrevenueunit"+row);
		}else{
			return true;
		}

		/*UNIT COUNT*/
		var txtunitcount = document.getElementById("txtunitcount"+nextOldRow);
		if(txtunitcount != ''){
			txtunitcount.setAttribute("id", "txtunitcount"+row);
			var attrtxtunitcount = document.createAttribute("onchange");
  			attrtxtunitcount.value = "calculateunitcount("+row+")";
  			txtunitcount.setAttributeNode(attrtxtunitcount);/**/
		}

		/*WEIGHT*/
		var txtweight = document.getElementById("txtweight"+nextOldRow);
		if(txtweight != ''){
			txtweight.setAttribute("id", "txtweight"+row);
			var attrtxtweight = document.createAttribute("onchange");
  			attrtxtweight.value = "calculateweightnew("+row+")";
  			txtweight.setAttributeNode(attrtxtweight);/**/
		}
		var weight_unit = document.getElementById("weight_unit"+nextOldRow);
		if(weight_unit != ''){
			weight_unit.setAttribute("id", "weight_unit"+row);
			var attrweight_unit = document.createAttribute("onchange");
  			attrweight_unit.value = "water_item_convunit("+row+")";
  			weight_unit.setAttributeNode(attrweight_unit);/**/
		}

		/*	TOTAL COST (US $)*/
		var txttotalcost = document.getElementById("txttotalcost"+nextOldRow);
		if(txttotalcost != ''){
			txttotalcost.setAttribute("id", "txttotalcost"+row);
		}

		/*	TOTAL REVENUE (US $)*/
		var txttotalrevenue = document.getElementById("txttotalrevenue"+nextOldRow);
		if(txttotalrevenue != ''){
			txttotalrevenue.setAttribute("id", "txttotalrevenue"+row);
		}



	}
	

	totcost = 0; totrevenue = 0;
	document.getElementById("txttotal_cost").value = 0;
	document.getElementById("txttotal_revenue").value = 0;
	document.getElementById("txttotal_profit").value = 0;	
	for (var tmpcnt = 1; tmpcnt <= row_cnt_main; tmpcnt++) { 	
		fin_totn = 0;
		if (document.getElementById("txttotalcost"+tmpcnt)){
			if (document.getElementById("txttotalcost"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalcost"+tmpcnt).value);
				totcost = totcost + fin_totn;
			}
		}	
		fin_totn = 0;
		if (document.getElementById("txttotalrevenue"+tmpcnt)){
			if (document.getElementById("txttotalrevenue"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalrevenue"+tmpcnt).value);
				totrevenue = totrevenue + fin_totn;
			}
		}
	}
	
	document.getElementById("txttotal_cost").value = totcost.toFixed(2);
	document.getElementById("txttotal_revenue").value = totrevenue.toFixed(2);
	document.getElementById("txttotal_profit").value = (totrevenue-totcost).toFixed(2);	
	update_total();
}


function water_item_showdetails(ctrlcnt) 
{
	var mainstr = document.getElementById("water_item"+ctrlcnt).value;
	var str_array = mainstr.split('|');
	
	var idMap = {};
	var duplicatectrlfound = "no";
	var all = document.getElementsByTagName("*");
	for (var i=0; i < all.length; i++) {
		 var elem = all[i];
		 if(elem.id != ""){ //skip without id
		   if(idMap[elem.id]){
			 // alert("'" + elem.id + "' is not unique")
			if ("water_item"+ctrlcnt == elem.id){
				duplicatectrlfound = "yes";
				break;
			}	
		   }
		   idMap[elem.id] = true; 
		 }
	}
	
	if (duplicatectrlfound == "yes"){

	}
	
	if (str_array[1] == "Cost Per Unit"){
		document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'inline';
		document.getElementById("txtcostperunit"+ctrlcnt).value = str_array[3];
		document.getElementById("txtcostunit"+ctrlcnt).innerHTML = "/" + str_array[2];
		document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];
		document.getElementById("txttotalcost"+ctrlcnt).style.display = 'inline';
		
		document.getElementById("txttotalcost"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtweight"+ctrlcnt).value)).toFixed(2);
		
		document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'none';
		document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'none';
		document.getElementById("txtrevenueperunit"+ctrlcnt).value = "";
		document.getElementById("txtrevenueunit"+ctrlcnt).innerHTML = "";
	}
	if (str_array[1] =="Revenue Per Unit"){
		//for (var tmpcnt = ctrlcnt; tmpcnt <= ctrlcnt+100; tmpcnt++) { 	 
		document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'none';
		
		document.getElementById("txtcostperunit"+ctrlcnt).value = "";
		document.getElementById("txtcostunit"+ctrlcnt).innerHTML = "";

		document.getElementById("txttotalcost"+ctrlcnt).style.display = 'none';
		
		document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'inline';
		
		document.getElementById("txttotalrevenue"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtweight"+ctrlcnt).value)).toFixed(2);
		
		document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'inline';
		document.getElementById("txtrevenueperunit"+ctrlcnt).value = str_array[3];
		document.getElementById("txtrevenueunit"+ctrlcnt).innerHTML = "/" + str_array[2];
		document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];

		//}	
	
	}

	if (str_array[5] =="By Weight"){
		document.getElementById("txtunitcount"+ctrlcnt).style.display = 'none';
	}else{
		document.getElementById("txtunitcount"+ctrlcnt).style.display = 'inline';
	}
	
	if (str_array[5] =="By Number of Pulls"){
		document.getElementById("txtcostunit"+ctrlcnt).innerHTML = "/Pull";
		document.getElementById("txtrevenueunit"+ctrlcnt).innerHTML = "/Pull";

		if (str_array[9] != ""){
			document.getElementById("txtweight"+ctrlcnt).value = str_array[9];
		}	
		
		if (str_array[6] == "Cost Per Pull"){
			document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'inline';
			document.getElementById("txtcostperunit"+ctrlcnt).value = str_array[3];

			document.getElementById("txttotalcost"+ctrlcnt).style.display = 'inline';

			document.getElementById("txttotalcost"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtweight"+ctrlcnt).value)).toFixed(2);
			
			document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'none';
			document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'none';
			
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];
		}
		if (str_array[6] =="Revenue Per Pull"){
			document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'none';

			document.getElementById("txttotalcost"+ctrlcnt).style.display = 'none';
			
			document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'inline';

			document.getElementById("txttotalrevenue"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtweight"+ctrlcnt).value)).toFixed(2);
			
			document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'inline';
			document.getElementById("txtrevenueperunit"+ctrlcnt).value = str_array[3];
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];
		}

		if (str_array[8] != ""){
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[8];
		}	
	}

	if (str_array[5] =="Per Item"){
		document.getElementById("txtcostunit"+ctrlcnt).innerHTML = "/Item";
		document.getElementById("txtrevenueunit"+ctrlcnt).innerHTML = "/Item";
		
		if (str_array[11] != ""){
			document.getElementById("txtweight"+ctrlcnt).value = str_array[11];
		}	

		if (str_array[7] == "Cost Per Item"){
			document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'inline';
			document.getElementById("txtcostperunit"+ctrlcnt).value = str_array[3];
			
			document.getElementById("txttotalcost"+ctrlcnt).style.display = 'inline';

			document.getElementById("txttotalcost"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtunitcount"+ctrlcnt).value)).toFixed(2);
			
			document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'none';
			document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'none';
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];
		}
		if (str_array[7] =="Revenue Per Item"){
			document.getElementById("txtcostperunit"+ctrlcnt).style.display = 'none';
			document.getElementById("txtcostunit"+ctrlcnt).innerHTML = "";

			document.getElementById("txttotalcost"+ctrlcnt).style.display = 'none';
			
			document.getElementById("txttotalrevenue"+ctrlcnt).style.display = 'inline';

			document.getElementById("txttotalrevenue"+ctrlcnt).value = (str_array[3] * Number(document.getElementById("txtunitcount"+ctrlcnt).value)).toFixed(2);
			
			document.getElementById("txtrevenueperunit"+ctrlcnt).style.display = 'inline';
			document.getElementById("txtrevenueperunit"+ctrlcnt).value = str_array[3];
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[2];
		}
		if (str_array[10] != ""){
			document.getElementById("weight_unit"+ctrlcnt).value = str_array[10];
		}	
		
	}
	
	document.getElementById("txtoutlet"+ctrlcnt).innerHTML = str_array[4];
	
	row_cnt_main = document.getElementById("row_cnt_main").value;

	totcost = 0; totrevenue = 0;
	document.getElementById("txttotal_cost").value = 0;
	document.getElementById("txttotal_revenue").value = 0;
	document.getElementById("txttotal_profit").value = 0;	
	for (var tmpcnt = 1; tmpcnt <= row_cnt_main; tmpcnt++) { 	
		fin_totn = 0;
		if (document.getElementById("txttotalcost"+tmpcnt)){
			if (document.getElementById("txttotalcost"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalcost"+tmpcnt).value);
				totcost = totcost + fin_totn;
			}
		}	
		fin_totn = 0;
		if (document.getElementById("txttotalrevenue"+tmpcnt)){
			if (document.getElementById("txttotalrevenue"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalrevenue"+tmpcnt).value);
				totrevenue = totrevenue + fin_totn;
			}
		}
	}
	
	document.getElementById("txttotal_cost").value = totcost.toFixed(2);
	document.getElementById("txttotal_revenue").value = totrevenue.toFixed(2);
	document.getElementById("txttotal_profit").value = (totrevenue-totcost).toFixed(2);	
	
	document.getElementById("base_line_commodity"+ctrlcnt).innerHTML = str_array[13];
	document.getElementById("base_line_rate_cost"+ctrlcnt).innerHTML = str_array[14];
	document.getElementById("base_line_rate_revenue"+ctrlcnt).innerHTML = str_array[15];
}

function water_item_convunit(ctrlcnt) 
{ 
	var mainstr = document.getElementById("water_item"+ctrlcnt).value;
	var str_array = mainstr.split('|');
	var untinm = document.getElementById("weight_unit"+ctrlcnt).value;
	var txtweight = Number(document.getElementById("txtweight"+ctrlcnt).value);
	if (document.getElementById("pound_per_gallon"+ctrlcnt)){
		var poundpergallon = document.getElementById("pound_per_gallon"+ctrlcnt).value;
	}else{
		var poundpergallon = 0;
	}	
	
	if (str_array[5] =="By Weight"){  
		if (untinm != str_array[2])
		{ 
			if (untinm == "Pounds" && str_array[2] == "Tons")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight/2000;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Tons";	
				calculateweightnew(ctrlcnt);
			}

			if (untinm == "Kilograms" && str_array[2] == "Tons")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*0.00110231;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Tons";	
				calculateweightnew(ctrlcnt);
			}

			if (untinm == "Tons" && str_array[2] == "Pounds")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*2000;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Pounds";	
				calculateweightnew(ctrlcnt);
			}

			if (untinm == "Kilograms" && str_array[2] == "Pounds")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*2.20462;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Pounds";	
				calculateweightnew(ctrlcnt);
			}

			if (untinm == "Tons" && str_array[2] == "Kilograms")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*907.185;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Kilograms";	
				calculateweightnew(ctrlcnt);
			}

			if (untinm == "Pounds" && str_array[2] == "Kilograms")
			{
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*0.453592;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Kilograms";	
				calculateweightnew(ctrlcnt);
			}

			//***************************************
			//Update this as per the above unit weight calculations
			//Added by nayan
			//16 feb 2021
			//***************************************
			if (untinm == "Gallon" && str_array[2] == "Kilograms"){
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*3.7854;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Kilograms";	
				calculateweightnew(ctrlcnt);
			}
			if (untinm == "Gallon" && str_array[2] == "Pounds"){
				//document.getElementById("txtweight"+ctrlcnt).value = txtweight*11 ;	//change from 8.34 to 11 as per point 423
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*poundpergallon;
				document.getElementById("weight_unit"+ctrlcnt).value = "Pounds";	
				calculateweightnew(ctrlcnt);
			}
			if (untinm == "Gallon" && str_array[2] == "Tons"){
				document.getElementById("txtweight"+ctrlcnt).value = txtweight*0.0013368056;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Tons";	
				calculateweightnew(ctrlcnt);
			} 
			//Note : Here water Item unit is Gallon.So convert all txtweight into pounds as per client requirement
			if (untinm == "Pounds" && str_array[2] == "Gallon"){
				//First pounds convert into gallon
				var txtWeightGallon = txtweight * 0.119826;
				//Second convert  gallon value into pounds as per client requirement gallon value everytime store into pounds & weight unit is pounds also
				//var poundsVal = (txtWeightGallon * 11).toFixed(2);  //change from 8.34 to 11 as per point 423
				var poundsVal = (txtWeightGallon * poundpergallon).toFixed(2);
				
				document.getElementById("txtweight"+ctrlcnt).value = poundsVal;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Gallon";	
				calculateweightnew(ctrlcnt);
			}
			if (untinm == "Tons" && str_array[2] == "Gallon"){
				//First tons convert into gallon
				var txtWeightGallon = txtweight * 31.75471;
				//Second convert gallon value into pounds as per client requirement gallon value everytime store into pounds & weight unit is pounds also
				//var poundsVal = (txtWeightGallon * 11).toFixed(2); //change from 8.34 to 11 as per point 423
				var poundsVal = (txtWeightGallon * poundpergallon).toFixed(2);
				
				document.getElementById("txtweight"+ctrlcnt).value = poundsVal;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Gallon";	
				calculateweightnew(ctrlcnt);
			}
			if (untinm == "Kilograms" && str_array[2] == "Gallon"){
				//First kilograms convert into gallon
				var txtWeightGallon = txtweight * 0.2642;
				//Second convert gallon value into pounds as per client requirement gallon value everytime store into pounds & weight unit is pounds also
				//var poundsVal = (txtWeightGallon * 11).toFixed(2); 
				var poundsVal = (txtWeightGallon * poundpergallon).toFixed(2);

				document.getElementById("txtweight"+ctrlcnt).value = poundsVal;	
				document.getElementById("weight_unit"+ctrlcnt).value = "Gallon";	
				calculateweightnew(ctrlcnt);
			}
		}else{
			if(untinm == "Gallon" && str_array[2] == "Gallon"){ //alert('Gallon&Gallon'); 
				//document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value * 11) * unitcount).toFixed(2);
				document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value * poundpergallon) * unitcount).toFixed(2);
			}
		}
	}	
	
	if (str_array[5] =="Per Item" || str_array[5] =="By Number of Pulls"){  
		var unitcount = document.getElementById("txtunitcount"+ctrlcnt).value;

		if (document.getElementById("weight_unit"+ctrlcnt).value == "Pounds"){
			//document.getElementById("txttotweight"+ctrlcnt).innerHTML = "Total Weight (lbs): " + (document.getElementById("txtweight"+ctrlcnt).value * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrlcnt).innerHTML = (document.getElementById("txtweight"+ctrlcnt).value * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrlcnt).value == "Tons"){
			//document.getElementById("txttotweight"+ctrlcnt).innerHTML = "Total Weight (lbs): " + ((document.getElementById("txtweight"+ctrlcnt).value* 2000) * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value* 2000) * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrlcnt).value == "Kilograms"){
			//document.getElementById("txttotweight"+ctrlcnt).innerHTML = "Total Weight (lbs): " + ((document.getElementById("txtweight"+ctrlcnt).value * 2.20462) * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value * 2.20462) * unitcount).toFixed(2);
		}

		//Added by nayan
		if (document.getElementById("weight_unit"+ctrlcnt).value == "Gallon"){
			//document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value * 11) * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrlcnt).innerHTML = ((document.getElementById("txtweight"+ctrlcnt).value * poundpergallon) * unitcount).toFixed(2);
		}	

	}	
	
}

function loaddata(warehouse_id, editflg) 
{
	document.getElementById("div_boxrep").style.display = 'block';
	document.getElementById("div_boxrep").innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";
	
	vendor_id = document.getElementById("vendor_id").value;

	if(vendor_id == 844){		
		document.getElementById("ucbzw_extra_display1").style.display = 'table-row';
		document.getElementById("ucbzw_extra_display2").style.display = 'table-row';
		document.getElementById("ucbzw_extra_display3").style.display = 'table-row';
		document.getElementById("ucbzw_extra_display4").style.display = 'table-row';
		document.getElementById("ucbzw_extra_display5").style.display = 'table-row';
		document.getElementById("ucbzw_extra_display6").style.display = 'table-row';
	}
	
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
		document.getElementById("div_boxrep").innerHTML = xmlhttp.responseText;
	  }
	}

	//alert("warehouse_id="+warehouse_id+" / vendorid="+vendor_id+" / editflg="+editflg);


	xmlhttp.open("POST","water_getboxrep.php?warehouse_id="+warehouse_id+"&vendorid="+vendor_id+"&editflg="+editflg,true);			
	xmlhttp.send();	
}

function calculateunitcount(ctrcnt)
{
	if (document.getElementById("pound_per_gallon"+ctrcnt)){
		var poundpergallon = document.getElementById("pound_per_gallon"+ctrcnt).value;
	}else{
		var poundpergallon = 0;
	}	

	var mainstr = document.getElementById("water_item"+ctrcnt).value;
	var str_array = mainstr.split('|');

	if (str_array[5] =="Per Item" || str_array[5] =="By Number of Pulls"){
		var unitcount = document.getElementById("txtunitcount"+ctrcnt).value;

		if (document.getElementById("weight_unit"+ctrcnt).value == "Pounds"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = (document.getElementById("txtweight"+ctrcnt).value * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrcnt).value == "Tons"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value* 2000) * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrcnt).value == "Kilograms"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 2.20462) * unitcount).toFixed(2);
		}	

		//Adding by nayan
		if (document.getElementById("weight_unit"+ctrcnt).value == "Gallon"){
			//document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 11) * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * poundpergallon) * unitcount).toFixed(2);
		}

		
		if (document.getElementById("txtcostperunit"+ctrcnt).style.display != "none"){
			var box_value = document.getElementById("txtcostperunit"+ctrcnt).value;
		}
		if (document.getElementById("txtrevenueperunit"+ctrcnt).style.display != "none"){
			var box_value = document.getElementById("txtrevenueperunit"+ctrcnt).value;
		}
		
		var total_ctr = document.getElementById("row_cnt_main").value;
		
		var fin_tot = 0; 
		var fin_totn = 0;
		if (unitcount > 0 && box_value > 0){
			if (document.getElementById("txttotalcost"+ctrcnt))
			{
				document.getElementById("txttotalcost"+ctrcnt).value = (unitcount * box_value).toFixed(2); 
			}
			if (document.getElementById("txttotalrevenue"+ctrcnt))
			{
				document.getElementById("txttotalrevenue"+ctrcnt).value = (unitcount * box_value).toFixed(2); 
			}
		}
		
		totcost = 0; totrevenue = 0;
		for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
			fin_totn = 0;
			if (document.getElementById("txttotalcost"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalcost"+tmpcnt).value);
				totcost = totcost + fin_totn;
			}
			fin_totn = 0;
			if (document.getElementById("txttotalrevenue"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalrevenue"+tmpcnt).value);
				totrevenue = totrevenue + fin_totn;
			}
		}
		
		if (document.getElementById("txttotalcost"+ctrcnt).style.display != "none") {
			document.getElementById("txttotal_cost").value = totcost.toFixed(2);
		}
		if (document.getElementById("txttotalrevenue"+ctrcnt).style.display != "none") {
			document.getElementById("txttotal_revenue").value = totrevenue.toFixed(2);
		}
		
		document.getElementById("txttotal_profit").value = (totrevenue-totcost).toFixed(2);
		update_total();
	}else{ 
		var unitcount = document.getElementById("txtunitcount"+ctrcnt).value;
		//Adding by nayan
		if (document.getElementById("weight_unit"+ctrcnt).value == "Gallon"){
			//document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 11) ).toFixed(2);
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * poundpergallon) ).toFixed(2);
		}
	}	
}

function calculateweightnew(ctrcnt)
{	
	var poundpergallon = 0;
	
	var mainstr = document.getElementById("water_item"+ctrcnt).value;
	var str_array = mainstr.split('|');
	poundpergallon = str_array[12];
	
	if (str_array[5] =="By Weight"){

		var unitcount = document.getElementById("txtunitcount"+ctrcnt).value;
		//Adding by nayan
		if (document.getElementById("weight_unit"+ctrcnt).value == "Gallon"){
			//document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 11)).toFixed(2);
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * poundpergallon)).toFixed(2);
		}

		var box_weight = document.getElementById("txtweight"+ctrcnt).value;
		if (document.getElementById("txtcostperunit"+ctrcnt).style.display != "none"){
			var box_value = document.getElementById("txtcostperunit"+ctrcnt).value;
		}
		if (document.getElementById("txtrevenueperunit"+ctrcnt).style.display != "none"){
			var box_value = document.getElementById("txtrevenueperunit"+ctrcnt).value;
		}
		
		var total_ctr = document.getElementById("row_cnt_main").value;
		
		var fin_tot = 0; 
		var fin_totn = 0;
		if (box_weight > 0 && box_value > 0){
			if (document.getElementById("txttotalcost"+ctrcnt))
			{
				document.getElementById("txttotalcost"+ctrcnt).value = (box_weight * box_value).toFixed(2); 
			}
			if (document.getElementById("txttotalrevenue"+ctrcnt))
			{
				document.getElementById("txttotalrevenue"+ctrcnt).value = (box_weight * box_value).toFixed(2); 
			}
		}
		
		totcost = 0; totrevenue = 0;
		for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
			fin_totn = 0;
			if (document.getElementById("txttotalcost"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalcost"+tmpcnt).value);
				totcost = totcost + fin_totn;
			}
			fin_totn = 0;
			if (document.getElementById("txttotalrevenue"+tmpcnt).style.display != "none")
			{
				fin_totn = Number(document.getElementById("txttotalrevenue"+tmpcnt).value);
				totrevenue = totrevenue + fin_totn;
			}
		}
		
		if (document.getElementById("txttotalcost"+ctrcnt).style.display != "none") {
			document.getElementById("txttotal_cost").value = totcost.toFixed(2);
		}
		if (document.getElementById("txttotalrevenue"+ctrcnt).style.display != "none") {
			document.getElementById("txttotal_revenue").value = totrevenue.toFixed(2);
		}
		document.getElementById("txttotal_profit").value = (totrevenue-totcost).toFixed(2);
		update_total();
	}
	
	if (str_array[5] =="Per Item" || str_array[5] =="By Number of Pulls"){
		var unitcount = document.getElementById("txtunitcount"+ctrcnt).value;
		if (document.getElementById("weight_unit"+ctrcnt).value == "Pounds"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = (document.getElementById("txtweight"+ctrcnt).value * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrcnt).value == "Tons"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value* 2000) * unitcount).toFixed(2);
		}	
		if (document.getElementById("weight_unit"+ctrcnt).value == "Kilograms"){
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 2.20462) * unitcount).toFixed(2);
		}

		//Added by nayan
		if (document.getElementById("weight_unit"+ctrcnt).value == "Gallon"){
			//document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * 11) * unitcount).toFixed(2);
			document.getElementById("txttotweight"+ctrcnt).innerHTML = ((document.getElementById("txtweight"+ctrcnt).value * poundpergallon) * unitcount).toFixed(2);
		}	
	}	
	
}

function calculatecost(ctrcnt)
{
	var mainstr = document.getElementById("water_item"+ctrcnt).value;
	var str_array = mainstr.split('|');
	
	if (str_array[5] =="Per Item" || str_array[5] =="By Number of Pulls")
	{
		var box_weight = document.getElementById("txtunitcount"+ctrcnt).value;
	}else{
		var box_weight = document.getElementById("txtweight"+ctrcnt).value;
	}

	var box_value = document.getElementById("txtcostperunit"+ctrcnt).value;
	var total_ctr = document.getElementById("row_cnt_main").value;
	
	var fin_tot = 0; 
	var fin_totn = 0;
	
	if (box_weight > 0 && box_value > 0){
		document.getElementById("txttotalcost"+ctrcnt).value = (box_weight * box_value).toFixed(2); 
	}

	for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
		if (document.getElementById("txttotalcost"+tmpcnt).style.display != "none" ){
			fin_totn = Number(document.getElementById("txttotalcost"+tmpcnt).value);
			fin_tot = fin_tot + fin_totn;
		}	
	}
	document.getElementById("txttotal_cost").value = fin_tot.toFixed(2);
	
	totrevenue = document.getElementById("txttotal_revenue").value;
	
	document.getElementById("txttotal_profit").value = (totrevenue-fin_tot).toFixed(2);
	update_total();
}

function calculaterevenue(ctrcnt)
{
	var mainstr = document.getElementById("water_item"+ctrcnt).value;
	var str_array = mainstr.split('|');
	
	if (str_array[5] =="Per Item" || str_array[5] =="By Number of Pulls")
	{
		var box_weight = document.getElementById("txtunitcount"+ctrcnt).value;
	}else{
		var box_weight = document.getElementById("txtweight"+ctrcnt).value;
	}
	var box_value = document.getElementById("txtrevenueperunit"+ctrcnt).value;
	var total_ctr = document.getElementById("row_cnt_main").value;
	
	var fin_tot = 0; 
	var fin_totn = 0;
	
	if (box_weight > 0 && box_value > 0){
		document.getElementById("txttotalrevenue"+ctrcnt).value = (box_weight * box_value).toFixed(2); 
	}

	for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
		if (document.getElementById("txttotalrevenue"+tmpcnt).style.display != "none" ){
			fin_totn = Number(document.getElementById("txttotalrevenue"+tmpcnt).value);
			
			fin_tot = fin_tot + fin_totn;
		}	
	}
	document.getElementById("txttotal_revenue").value = fin_tot.toFixed(2);
	//update_total();

	totcost = document.getElementById("txttotal_cost").value;
	addfees_total = document.getElementById("addfees_total").value;

	document.getElementById("txttotal_profit").value = (fin_tot-totcost-addfees_total).toFixed(2);
	update_total();
}


function calculateweight(ctrcnt)
{
	//var box_count = document.getElementById("boxcount"+ctrcnt).value;
	var box_weight = document.getElementById("txtweight"+ctrcnt).value;
	var box_value = document.getElementById("valueeach"+ctrcnt).value;
	var total_ctr = document.getElementById("total_ctr").value;
	
	var fin_tot = 0; 
	var fin_totn = 0;
	
	//if (box_count > 0 && box_weight > 0){
		//document.getElementById("txtweight"+ctrcnt).value = box_count * box_weight; 
	//}
	if (box_weight > 0 && box_value > 0){
		document.getElementById("totalvalue"+ctrcnt).value = (box_weight * box_value).toFixed(2); 
		//fin_totn = box_weight * box_value; 
		//fin_tot = Number(document.getElementById("totalvalue_tot").value);
		//document.getElementById("totalvalue_tot").value = fin_tot + fin_totn;
		
		//update_total();
	}

	for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
		fin_totn = Number(document.getElementById("totalvalue"+tmpcnt).value);
		fin_tot = fin_tot + fin_totn;
	}
	document.getElementById("totalvalue_tot").value = fin_tot.toFixed(2);
	update_total();
}


function update_total_val(ctrcnt)
{
	var fin_tot = 0; 
	var total_ctr = document.getElementById("total_ctr").value;

	for (var tmpcnt = 1; tmpcnt <= total_ctr; tmpcnt++) { 	
		fin_totn = Number(document.getElementById("totalvalue"+tmpcnt).value);
		fin_tot = fin_tot + fin_totn;
	}
	document.getElementById("totalvalue_tot").value = fin_tot.toFixed(2);
	update_total();
}

function update_total()
{
	var vendor_id = document.getElementById('vendor_id').value;
	var totalvalue_tot = 0;
	if (document.getElementById("totalvalue_tot")){
		totalvalue_tot = Number(document.getElementById("totalvalue_tot").value); 
	}
	//var othercharge = Number(document.getElementById("othercharge").value);
	var tot_fees = Number(document.getElementById("tot_fees").value);
	
	if (document.getElementById("addfees_total")){
		tot_fees = Number(document.getElementById("addfees_total").value); 
	}
	
	total_profit = Number(document.getElementById("txttotal_profit").value);	
	
	if(vendor_id == '844'){ 
		//alert('1')
		var txtTotalDueToUcb = Number(document.getElementById('txtTotalDueToUcb').value);
		if (tot_fees)  { 
			//alert('1_1'+txtTotalDueToUcb+" / "+total_profit+" / "+tot_fees )
			if (total_profit){
				document.getElementById("final_total").value = (txtTotalDueToUcb + total_profit - tot_fees).toFixed(2);
			}else{
				document.getElementById("final_total").value = (txtTotalDueToUcb - tot_fees).toFixed(2);;
			}			
		}else{ 
			//alert('1_2 txtTotalDueToUcb-> '+txtTotalDueToUcb+" / total_profit-> " + total_profit )
			if (total_profit){
				document.getElementById("final_total").value = (txtTotalDueToUcb + total_profit).toFixed(2);;
			}else{
				document.getElementById("final_total").value = (txtTotalDueToUcb).toFixed(2);;
			}			
		}
	}else{ //alert('2')
		//if (tot_fees > 0)  {  //alert('2_1'+ total_profit+" / "+tot_fees )
			document.getElementById("final_total").value = (total_profit - tot_fees).toFixed(2);
		//}else{ //alert('2_2'+ total_profit  )
		//	document.getElementById("final_total").value = total_profit.toFixed(2);
		//}
	}	
	/*if (tot_fees > 0)  {
		document.getElementById("final_total").value = (total_profit - tot_fees).toFixed(2);
	}else{
		document.getElementById("final_total").value = total_profit.toFixed(2);
	}*/
	
}

	function GetFileSize() {
        var fi = document.getElementById('uploadscanrep'); // GET THE FILE INPUT.

        // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
        if (fi.files.length > 0) {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fi.files.length - 1; i++) {
				var filenm = fi.files.item(i).name;
				
				if (filenm.indexOf("#") > 0){
					alert("Remove # from Scan file and then upload file!");
					document.getElementById("uploadscanrep").value = "";
				}
		if (filenm.indexOf("\'") > 0){
			alert("Remove '\'' from Scan file "+filenm+" and then upload file!");
			document.getElementById("uploadscanrep").value = "";
		}	
				
				if (filenm.indexOf("\'") > 0){
					alert("Remove '\'' from Scan file "+filenm+" and then upload file!");
					document.getElementById("uploadscanrep").value = "";
				}	
				
                var fsize = fi.files.item(i).size;      // THE SIZE OF THE FILE.
				if (Math.round(fsize / 1024) > 8000)
				{
					alert("Only files with 8mb is allowed.");	
					document.getElementById("uploadscanrep").value = "";
				}
            }
        }
		
	//For payment proof file
        var fiPF = document.getElementById('payment_proof_file');
        if (fiPF.files.length > 0) {
            // RUN A LOOP TO CHECK EACH SELECTED FILE.
            for (var i = 0; i <= fiPF.files.length - 1; i++) {
				var filenmPF = fiPF.files.item(i).name;
				if (filenmPF.indexOf("#") > 0){
					alert("Remove # from Scan file and then upload file!");
					document.getElementById("payment_proof_file").value = "";
				}
				if (filenmPF.indexOf("\'") > 0){
					alert("Remove \' from Scan file "+filenmPF+" and then upload file!");
					document.getElementById("payment_proof_file").value = "";
				}				
						var fsizePF = fiPF.files.item(i).size;      // THE SIZE OF THE FILE.
				if (Math.round(fsizePF / 1024) > 8000)
				{
					alert("Only files with 8mb is allowed.");	
					document.getElementById("payment_proof_file").value = "";
				}
			}
        }		
    }

    function calcTotalDuetoucb(){
    	var txtNetcostRev = document.getElementById('txtNetcostRev').value;
    	var txtClientNetSavings = document.getElementById('txtClientNetSavings').value;
    	var vendor_id = document.getElementById('vendor_id').value;
    	var txttotal_profit = document.getElementById('txttotal_profit').value;
    	var addfees_total = document.getElementById('addfees_total').value;
		var UCBSavingsSplit = document.getElementById('txtUCBSavingsSplit').value;
		
    	var oldDiffTotal = 0;
		if (txttotal_profit != "" && addfees_total != ""){
			oldDiffTotal= parseFloat(txttotal_profit).toFixed(2) - parseFloat(addfees_total).toFixed(2);
		}	
    	var difference = 0;
    	//alert(parseFloat(txtNetcostRev).toFixed(2) +" / "+ parseFloat(UCBSavingsSplit).toFixed(2)+" / "+vendor_id+" / "+parseFloat(oldDiffTotal).toFixed(2))
    	
		if (txtNetcostRev != '' && UCBSavingsSplit == ''){
    		difference = parseFloat(txtNetcostRev) - difference;
    	}else if(txtNetcostRev == '' && UCBSavingsSplit != ''){
    		difference = difference - parseFloat(UCBSavingsSplit);
    	}else if(txtNetcostRev != '' && UCBSavingsSplit != '' ){
    		difference = parseFloat(txtNetcostRev) + parseFloat(UCBSavingsSplit);
    	}

		/*if(txtNetcostRev != '' && txtClientNetSavings == ''){
    		difference = parseFloat(txtNetcostRev).toFixed(2) - difference;
    	}else if(txtNetcostRev == '' && txtClientNetSavings != ''){
    		difference = difference - parseFloat(txtClientNetSavings).toFixed(2);
    	}else if(txtNetcostRev != '' && txtClientNetSavings != '' ){
    		difference = parseFloat(txtNetcostRev).toFixed(2) - parseFloat(txtClientNetSavings).toFixed(2);
    	} */
		
		document.getElementById('txtTotalDueToUcb').value = parseFloat(difference).toFixed(2);
		
    	if(vendor_id == '844'){
    		document.getElementById('final_total').value = (difference + oldDiffTotal).toFixed(2);
    	}
    }
	
</script>

	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT><SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
	<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
	<script LANGUAGE="JavaScript">
		var cal1xx = new CalendarPopup("listdiv");
		cal1xx.showNavigationDropdowns();
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
		var cal2xxdup = new CalendarPopup("listdiv");
		cal2xxdup.showNavigationDropdowns();
		cal2xxdup.setReturnFunction("chkduplicate_onservice_end_dt");
		var cal4xx = new CalendarPopup("listdiv");
		cal4xx.showNavigationDropdowns();
		var cal5xx = new CalendarPopup("listdiv");
		cal5xx.showNavigationDropdowns();
		var cal6xx = new CalendarPopup("listdiv");
		cal6xx.showNavigationDropdowns();
	</script>

<?

$rec_id = $_REQUEST["rec_id"];

$dt_view_qry = "SELECT * from water_transaction WHERE id = '" . $rec_id . "' AND report_entered = 1";
$dt_view_res = db_query($dt_view_qry,db() );
$num_rows = tep_db_num_rows($dt_view_res);
if (($num_rows < 1) || ($_GET["pa_edit"] == "true")) {
//echo $dt_view_qry;
?>

<a target="_blank" href="water_cron_fordash-selectedrecord.php">Recalculate Water Dashboard Data</a>&nbsp;&nbsp;
<a target="_blank" href="water_cron_fordash-parent-selectedrecord.php">Recalculate Water Parent Dashboard Data</a>
<br>

<!-- INITIAL SORT --> 
<form name="frmsort" method="post" action="#" encType="multipart/form-data" onsubmit="return onsubmitform();">
<input type="hidden" name="ID" id="ID" value="<? echo $_REQUEST['ID']; ?>"/>
<input type="hidden" name="warehouse_id" id="warehouse_id" value="<? echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<? echo $rec_type; ?>"/>		
<input type="hidden" value="<? echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<? echo $rec_id; ?>"/>	
<input type="hidden" name="mark_no_inv" id="mark_no_inv" value=""/>	
<input type="hidden" name="mark_no_inv_undo" id="mark_no_inv_undo" value=""/>	

<table cellSpacing="1" cellPadding="1" border="0" style="width: 600px" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="6">
		<font size="1">VENDOR REPORT</font></td>
	</tr>
	<tr align="left" id="ucbzw_extra_display1" style="display: none;" >
		<td bgColor="#c0cdda" colSpan="6">
		<font size="1"><b>Select Vendor: UCBZeroWaste - Waste Manager Consolidated Invoice</b></font></td>
	</tr>
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="6">
			<font size="1">Select Vendor:
				<select id="vendor_id" name="vendor_id" onchange="loaddata(<? echo $id; ?>,0)">
					<option value=""></option>
				<?	
					$vendor_ids = "";
					$query = db_query("SELECT water_inventory.vendor FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id WHERE water_boxes_to_warehouse.water_warehouse_id = " . $id . " group by water_inventory.vendor", db() );
					while ($rowsel_getdata = array_shift($query)) {
						$vendor_ids = $vendor_ids . $rowsel_getdata["vendor"] . ",";
					}
					if ($vendor_ids != ""){
						$vendor_ids = substr($vendor_ids, 0, strlen($vendor_ids)-1);
					}
					
					$query = db_query( "SELECT * FROM water_vendors where active_flg = 1 and id in ($vendor_ids) order by Name", db() );
					while ($rowsel_getdata = array_shift($query)) {
					
						if ($_REQUEST["vendor_id"] == $rowsel_getdata["id"]) {
							$tmp_str = " selected ";
						}
						
						$main_material = $rowsel_getdata['description'];
						
						//$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
						$vender_nm = $rowsel_getdata['Name']. " - ". $main_material;
					?>
						<option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?> ><? echo $vender_nm;?></option>
					<?}
				?>
				</select>
			</font>
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		ENTRY DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="report_date" id="report_date" size="20" readonly value="<?php echo isset($_GET['report_date']) ? $_GET['report_date'] : date("Y-m-d"); ?>" > 
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE CURRENCY
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<select name="invoice_currency" id="invoice_currency">
				<option>U.S. Dollars</option>
				<option>Canadian Dollars</option>
			</select>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		SERVICE BEGIN DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="service_begin_date" id="service_begin_date" size="20" readonly value="<?php echo isset($_GET['service_begin_date']) ? $_GET['service_begin_date'] : ''; ?>" > 
			<a href="#" onclick="cal4xx.select(document.frmsort.service_begin_date,'dtanchor4xx','yyyy-MM-dd'); return false;" name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		SERVICE END DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="invoice_date" id="invoice_date" size="20" readonly value="<?php echo isset($_GET['invoice_date']) ? $_GET['invoice_date'] : ''; ?>" > 
			<a href="#" onclick="cal2xxdup.select(document.frmsort.invoice_date,'dtanchor3xx','yyyy-MM-dd'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
			<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="new_invoice_date" id="new_invoice_date" size="20" readonly value="<?php echo isset($_GET['new_invoice_date']) ? $_GET['new_invoice_date'] : ''; ?>" > 
			<a href="#" onclick="cal5xx.select(document.frmsort.new_invoice_date,'dtanchor5xx','yyyy-MM-dd'); return false;" name="dtanchor5xx" id="dtanchor5xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE DUE DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="invoice_due_date" id="invoice_due_date" size="20" readonly value="<?php echo isset($_GET['invoice_due_date']) ? $_GET['invoice_due_date'] : ''; ?>" > 
			<a href="#" onclick="cal6xx.select(document.frmsort.invoice_due_date,'dtanchor6xx','yyyy-MM-dd'); return false;" name="dtanchor6xx" id="dtanchor6xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE NUMBER
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="invoice_no" id="invoice_no" size="20" value="" onchange="chkduplicateinv()"> 
		</td>
	</tr>
	<tr bgColor="#e4e4e4" id="ucbzw_extra_display2" style="display: none;">
		<td colspan="3" height="13" class="style1" align="right">
		VENDORS NET COST OR REVENUE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="txtNetcostRev" id="txtNetcostRev" size="20" value="" onchange="calcTotalDuetoucb()" >
		</td>
	</tr>
	<tr bgColor="#e4e4e4" id="ucbzw_extra_display3" style="display: none;">
		<td colspan="3" height="13" class="style1" align="right">
		GROSS SAVINGS
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="txtGrossSavings" id="txtGrossSavings" size="20" value=""  >
		</td>
	</tr>
	<tr bgColor="#e4e4e4" id="ucbzw_extra_display4" style="display: none;">
		<td colspan="3" height="13" class="style1" align="right">
		CLIENT NET SAVINGS
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="txtClientNetSavings" id="txtClientNetSavings" size="20" value="" >
		</td>
	</tr>
	<tr bgColor="#e4e4e4" id="ucbzw_extra_display5" style="display: none;">
		<td colspan="3" height="13" class="style1" align="right">
		UCB SAVINGS SHARE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="txtUCBSavingsSplit" id="txtUCBSavingsSplit" size="20" value="" onchange="calcTotalDuetoucb()"  >
		</td>
	</tr>
	<tr bgColor="#e4e4e4" id="ucbzw_extra_display6" style="display: none;">
		<td colspan="3" height="13" class="style1" align="right">
		TOTAL DUE TO UCB
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="txtTotalDueToUcb" id="txtTotalDueToUcb" size="20" value="" readonly >
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
			UPLOAD INVOICE SCAN:
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="file" name="uploadscanrep[]" id="uploadscanrep"  multiple onchange="GetFileSize()">
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
			NO INVOICE DUE:
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="button" name="btn_no_inv" id="btn_no_inv" value="NO INVOICE" onclick="mark_btn_no_inv()">
		</td>
	</tr>
	
	<tr align="middle"><td bgColor="#c0cdda" class="style1" colspan="6">MATERIALS</td></tr>

	<tr align="middle"><td colspan="6">
		<div id="div_boxrep">
		</div>
		</td>
	</tr>

	<tr bgColor="#e4e4e4"><td colspan="7">
		<input type="checkbox" name="chkdoubt" id="chkdoubt" value="1" /><font size="1">I have a Question</font>
		&nbsp;<input type="text" name="txtdoubt" id="txtdoubt" value="" />
	</tr>
	
	<tr align="middle"><td colspan="7">&nbsp;</td></tr>

	<tr bgColor="#e4e4e4"><td colspan="7">
		<table id="tblmain_fees">
			<thead>
			  <tr>
				<th bgColor="#c0cdda" class="first"><font size="1">ADDITIONAL FEES & CREDITS</font></th>
				<th bgColor="#c0cdda"><font size="1">COST ($) PER UNIT</font></th>
				<th bgColor="#c0cdda"><font size="1">OCCURRENCES</font></th>
				<th bgColor="#c0cdda"><font size="1">TOTAL COST ($)</font></th>
				<th bgColor="#c0cdda"><font size="1">SAVINGS CALCULATION CATEGORY</font></th>
				<th bgColor="#c0cdda"><font size="1">REMARK</font></th>
			  </tr>
			</thead>

			<tbody>			
			<?
			$control_chg_cnt = 0; $row_cnt = 1; $blank_row_str = "";
			$blank_row_str = "<tr><td>";
			//if (isset($_REQUEST["edit"])){
			//	$sql = "SELECT item_charge, unqid from transaction_details_charges where transaction_id = ? order by unqid";
			//	$result = db_query($sql, db());
			//}else{
				$sql = "SELECT * from water_additional_fees where active_flg = 1 order by display_order ";
				$result = db_query($sql, db());
			//}							
			$blank_row_str .= "<select name='selfees[]' id='selfees" . $row_cnt . "' onchange='showfees_other(" . $row_cnt . ")'>" ;
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel = array_shift($result)) {
				$control_chg_cnt = $control_chg_cnt + 1;

				$blank_row_str .= "<option value=".$myrowsel["id"]." ";
					/*if($selfees>0) 
					{ 
						if ($myrowsel["id"] == $selfees) $blank_row_str .= " selected ";
					}*/
				$blank_row_str .= " >". $myrowsel["additional_fees_display"] . "</option>";
				

				$item_charge = 0;  $item_remark = "";
				if (isset($_REQUEST["edit"])){
					/*$sql_data = "SELECT item_charge_val, item_remark FROM transaction_details_charges where unqid = ?";
					$result_data = db_query($sql_data, array("i"), array($myrowsel["unqid"]));
					while ($myrowsel_data = array_shift($result_data)) 
					{
						$item_charge = $myrowsel_data["item_charge_val"];
						$item_remark = $myrowsel_data["item_remark"];
						$total_cost = $total_cost + $item_charge;
					}*/
				}
			}				
			$blank_row_str .= "</select>";
			$blank_row_str .= "</td>";
			$blank_row_str .= "<td> <input type='text' name='txtcharge[]' onkeypress='return chknumberval(event, " . $row_cnt . ");' id='txtcharge" . $row_cnt . "' value='" . $item_charge . "' onchange='update_fee_total()' size='5' /></td>";
			$blank_row_str .= "<td> <input type='text' name='txtOccurrences[]' onkeypress='return IsNumeric(event);' id='txtOccurrences' value='' onchange='update_fee_total()' size='5' /> </td>";
			$blank_row_str .= "<td> <input type='text' name='txtfeetotalcost[]' readonly onkeypress='return IsNumeric(event);' id='txtfeetotalcost' value='' size='5' /> </td>";

			$blank_row_str .= "<td>";
			$sql = "SELECT savings_calculation_category_id, savings_calculation_category, 1 as savings_calculation_category_flg from water_savings_calculation_category
				union
				SELECT commodity_id as savings_calculation_category_id, commodity as savings_calculation_category , 2 as savings_calculation_category_flg
				from base_line_commodity_master where company_id = '" . $_REQUEST["ID"] ."'";
			$result = db_query($sql, db());
			$blank_row_str .= "<select name='savings_calculation_category[]' id='savings_calculation_category" . $row_cnt . "'>" ;
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel = array_shift($result)) {
				$blank_row_str .= "<option value=".$myrowsel["savings_calculation_category_id"]."|" . $myrowsel["savings_calculation_category_flg"];
				$blank_row_str .= " >". $myrowsel["savings_calculation_category"] . "</option>";
			}				
			$blank_row_str .= "</select>";
			$blank_row_str .= "</td>";
			
			$blank_row_str .= "<td> <input type='text' name='txtremark_fees[]' id='txtremark_fees' value='" . $item_remark . "' size='20' /> </td>";
			$blank_row_str .= "<td> <input type='button' name='btnremove_chrg[]' id='btnremove_chrg' value='X' onclick='remove_newrow_chrg(this)'/> </td></tr>";
			$row_cnt = $row_cnt + 1;

			echo $blank_row_str;	
			
			//To add blank row
			$blank_row_str = "<tr><td>";
			$sql1 = "SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order";
			$result1 = db_query($sql1, db());
			$blank_row_str .= "<select name='selfees[]' id='selfeesctrltoreplace' onchange='showfees_other(ctrltoreplace)'>";
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel1 = array_shift($result1)) 
			{
				$blank_row_str .= "<option value=".$myrowsel1["id"]." ";
				$blank_row_str .= " >". $myrowsel1["additional_fees_display"] . "</option>";
			}
			$blank_row_str .= "</select>";
			$blank_row_str .= "<td> <input type='text' name='txtcharge[]' id='txtchargectrltoreplace' onkeypress='return chknumberval(event,ctrltoreplace);' onchange='update_fee_total()' value='' size='5' /> </td>";
			$blank_row_str .= "<td> <input type='text' name='txtOccurrences[]' id='txtOccurrences' onkeypress='return IsNumeric(event);' onchange='update_fee_total()' value='' size='5' /> </td>";
			$blank_row_str .= "<td> <input type='text' name='txtfeetotalcost[]' readonly onkeypress='return IsNumeric(event);' id='txtfeetotalcost' value='' size='5' /> </td>";

			$blank_row_str .= "<td>";
			$sql = "SELECT savings_calculation_category_id, savings_calculation_category, 1 as savings_calculation_category_flg from water_savings_calculation_category
				union
				SELECT commodity_id as savings_calculation_category_id, commodity as savings_calculation_category , 2 as savings_calculation_category_flg
				from base_line_commodity_master where company_id = '" . $_REQUEST["ID"] ."'";
			$result = db_query($sql, db());
			$blank_row_str .= "<select name='savings_calculation_category[]' id='savings_calculation_categoryctrltoreplace'>" ;
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel = array_shift($result)) {
				$blank_row_str .= "<option value=".$myrowsel["savings_calculation_category_id"]."|" . $myrowsel["savings_calculation_category_flg"];
				$blank_row_str .= " >". $myrowsel["savings_calculation_category"] . "</option>";
			}				
			$blank_row_str .= "</select>";
			$blank_row_str .= "</td>";
			
			$blank_row_str .= "<td> <input type='text' name='txtremark_fees[]' id='txtremark_fees' value='' size='20' /> </td>";
			$blank_row_str .= "<td> <input type='button' name='btnremove_chrg[]' id='btnremove_chrg' value='X' onclick='remove_newrow_chrg(this)'/> </td></tr>";
			
			if ($row_cnt == 1) {
				echo $blank_row_str;
			}else{
				$row_cnt = $row_cnt -1 ;
			}
			?>
			</tbody>
		</table>

		<table>
				<tr>
					<td colspan="8" align="right">
						<input type="button" name="add_newrec" id="add_newrec" value="Add New Row" onclick="add_newrow_fees()">
						<input type="hidden" name="row_cnt_fees" id="row_cnt_fees" value="<? echo $row_cnt; ?>" > 
						<input type="hidden" name="row_str_fees" id="row_str_fees" value="<? echo $blank_row_str; ?>" > 
					</td>
				</tr>
		</table>
	</td></tr>	

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		TOTAL ADDITIONAL FEES ($)
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input size="10" type="text" readonly name="addfees_total" id="addfees_total" >
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		FINAL TOTAL ($)
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input size="10" type="text" readonly name="final_total" id="final_total" >
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		<!-- USER INITIALS --> ENTERED BY 
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<? echo $_COOKIE["userinitials"]; ?>
			<input type="hidden" name="hidtxtnumrows" value="<? echo $num_rows; ?>">
			<input size="10" type="hidden" name="txtEditedBY" id="txtEditedBY" value="<? echo $_COOKIE['userinitials'];?>">
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td height="13"class="style1" align="right">
		NOTES</td>
		<td height="13" class="style1" align="right" colspan="5">
		<Font size='2' Face="arial">
			<p align="left">
				<textarea rows="3" cols="30" name="boxnotes" id="boxnotes"></textarea>
			</p>
		</Font>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td height="13"class="style1" align="right">
		Make or receive payment?</td>
		<td height="13" class="style1" align="left" colspan="5">
		<Font size='2' Face="arial">
			<input type="checkbox" name="make_receive_payment" id="make_receive_payment" value="1" onchange="show_vendor_payment_report()"></font>
		</td>
	</tr>
	<tr>
		<td height="13" class="style1" align="left" colspan="6">
			<div id="vendor_pay_reportid" style="display: none;">
				<table width="100%" class="vendor_payment_css" cellpadding="0" cellspacing="1">
					<tr bgColor="#e4e4e4">
						<th colspan="2" class="first" align="center" bgcolor="#c0cdda" style="height: 22px;"><font size="2">Vendor Payment Report</font></th>	
					</tr>	
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right" width="285px">Made Payment or Received Payment?</td>
						<td><input type="checkbox" name="made_payment" id="made_payment" value="1"></td>	
					</tr>
					
					<tr bgColor="#e4e4e4" id="vendorCredit" style="display: none;">
						<td class="style1" align="right">Vendor Credit:</td>
						<td><input type="text" name="vendor_credit" id="vendor_credit" value=" "></td>
					</tr>

					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received by:</td>
						<td><input type="text" name="paid_by" id="paid_by"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received date:</td>
						<td><input type="text" name="paid_date" id="paid_date"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Payment proof file:</td>
						<td>
							<input type="file" name="payment_proof_file[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
						</td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Log Notes:</td>
						<td><input type="text" name="log_notes" id="log_notes"></td>	
					</tr>
				</table>
			</div>
		</td>
	</tr>
	
<Font Face='arial' size='2'>
	<tr bgColor="#e4e4e4">
		<td colspan=6 align="left" height="19" class="style1">
			<p align="center">
			<input type="hidden" name="count" value="<? echo $count; ?>">
			<input type="hidden" name="tot_fees" id="tot_fees" value="">
			<input type="submit" value="SAVE" id="water_entry_btn" name="water_entry_btn" style="cursor:pointer;">&nbsp;<input type="button" value="RESET" onclick="resetctrls()" style="cursor:pointer;">
		</td>
	</tr>
	</table>
</form>
<? } ?>


<!-- EDIT A SORT --> 
<? if ($_GET["sort_edit"] == "true") { ?>

<script LANGUAGE="JavaScript">
function onsubmitformedit_old() 
{

	$tot_count = 0;
	if (document.getElementById("row_cnt_main"))
	{
		$tot_count = document.getElementById("row_cnt_main").value;
	}

	if (document.getElementById("vendor_id").value == ""){
		alert("Please select the vendor.")
		return false;
	}else if (document.getElementById("report_date").value == ""){
		alert("Please enter the entry date.")
		return false;
	}else if (document.getElementById("invoice_date").value == ""){
		alert("Please enter the Service End Date.")
		return false;
	}else if (document.getElementById("invoice_no").value == ""){
		alert("Please enter the invoice number.")
		return false;
	}else if ($tot_count == 0){
		alert("Please enter atleast one Material detail.")
		return false;
	}else if ($tot_count > 0){
		if (document.getElementById("txtweight1").value == ""){
			alert("Please enter atleast one Material detail.")
			return false;
		}else{
			document.frmsortedit.action ="water_addbox_report.php";
			return true;
		}
	}else{
		document.frmsortedit.action ="water_addbox_report.php";
		return true;
	}
}


function onsubmitformedit() 
{
	document.getElementById("water_entry_btn").style.display = "none";

	$tot_count = 0;
	if (document.getElementById("row_cnt_main"))
	{
		$tot_count = document.getElementById("row_cnt_main").value;
	}
	var msgstr = "";
	
	if (document.getElementById("invoice_due_date").value == ""){
		msgstr = msgstr + "Invoice Due Date\r\n"; 
	}
	
	if (document.getElementById("water_item1"))
	{
		var mainstr = document.getElementById("water_item1").value;
		var str_array = mainstr.split('|');
		
		if (str_array[5] =="By Weight"){
			if (document.getElementById("vendor_id").value == ""){
				msgstr = msgstr + "Vendor\r\n";
			}
			if (document.getElementById("invoice_date").value == ""){
				msgstr = msgstr + "Service End Date\r\n";
			}
			if (document.getElementById("invoice_no").value == ""){
				msgstr = msgstr + "Invoice Number\r\n";
			}
			if ($tot_count == 0){
				msgstr = msgstr + "atleast one Material detail\r\n";
			}else {
				if (document.getElementById("water_item1").value != "0"){
					if (document.getElementById("txtcostperunit1").value == "" && document.getElementById("txtrevenueperunit1").value == ""){
					
						msgstr = msgstr + "'Cost Per Unit' OR 'Revenue Per Unit'\r\n";
					}
				
					if (document.getElementById("txtweight1").value == ""){
						msgstr = msgstr + "'Weight' (EVEN IF the material item is priced 'Per Item' OR 'Per Number of Pulls', an estimated weight amount has to be inserted)";
					}
				}	
			}
		
		}else{

			if (document.getElementById("vendor_id").value == ""){
				msgstr = msgstr + "Vendor\r\n";
			}
			if (document.getElementById("invoice_date").value == ""){
				msgstr = msgstr + "Service End Date\r\n";
			}
			if (document.getElementById("invoice_no").value == ""){
				msgstr = msgstr + "Invoice Number\r\n";
			}
			if ($tot_count == 0){
				msgstr = msgstr + "atleast one Material detail\r\n";
			}else {
				if (document.getElementById("water_item1").value != "0"){
					if (document.getElementById("txtcostperunit1").value == "" && document.getElementById("txtrevenueperunit1").value == ""){
						msgstr = msgstr + "'Cost Per Unit' OR 'Revenue Per Unit'\r\n";
					}
					if (document.getElementById("txtunitcount1").value == ""){
						msgstr = msgstr + "'Unit Count' (ONLY if the material item is priced 'Per Item' or 'Per Number of Pulls')\r\n";
					}
					if (document.getElementById("txtweight1").value == ""){
						msgstr = msgstr + "'Weight' (EVEN IF the material item is priced 'Per Item' OR 'Per Number of Pulls', an estimated weight amount has to be inserted)";
					}
				}	
			}
		}
		if (msgstr != ""){
			alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
			document.getElementById("water_entry_btn").style.display = "inline";
			return false;
		}else{
			document.frmsortedit.action ="water_addbox_report.php";
			return true;
		}		
	}else{
		if (document.getElementById("vendor_id").value == ""){
			msgstr = msgstr + "Vendor\r\n";
		}
		if (document.getElementById("invoice_date").value == ""){
			msgstr = msgstr + "Service End Date\r\n";
		}
		if (document.getElementById("invoice_no").value == ""){
			msgstr = msgstr + "Invoice Number\r\n";
		}
		if ($tot_count == 0){
		//	msgstr = msgstr + "atleast one Material detail\r\n";
		}
		if (msgstr != ""){
			alert("For the Items to be saved, the following required fields needs to be filled out:\r\n" + msgstr);		
			document.getElementById("water_entry_btn").style.display = "inline";
			return false;
		}else{
			document.frmsortedit.action ="water_addbox_report.php";
			return true;
		}		
		
	}
}	
</script>


<form name="frmsortedit" method="post" action="#" encType="multipart/form-data" onsubmit="return onsubmitformedit();">
<input type="hidden" name="ID" id="ID" value="<?=$_REQUEST['ID'];?>">
<input type="hidden" name="warehouse_id" id="warehouse_id" value="<? echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<? echo $rec_type; ?>"/>		
<input type="hidden" value="<? echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<? echo $rec_id; ?>"/>	
<input type="hidden" name="update" value="yes"/>	
<input type="hidden" name="updatecrm" value="yes"/>	
<input type="hidden" name="mark_no_inv" id="mark_no_inv" value=""/>	
<input type="hidden" name="mark_no_inv_undo" id="mark_no_inv_undo" value=""/>	

<table cellSpacing="1" cellPadding="1" border="0" style="width: 600px" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="8">
		<font size="1">VENDOR REPORT [In Edit Mode]</font></td>
	</tr>
	<?
	$dt_view_tran_qry = "SELECT * from water_transaction WHERE id = " . $rec_id;
	$dt_view_tran = db_query($dt_view_tran_qry,db() );
	$dt_view_tran_row = array_shift($dt_view_tran);
	//echo $dt_view_tran_qry;
	$chkdoubt_val = "";
	if ($dt_view_tran_row["have_doubt"] == 1){
		$chkdoubt_val = " checked ";
	}
	$doubt = $dt_view_tran_row["doubt"];
	 
	$saved_ocr_val_flg = $dt_view_tran_row["saved_ocr_val_flg"];
									 
	?>
	<? if($dt_view_tran_row["vendor_id"] == 844 ){ ?> 
		<tr align="left" id="ucbzw_extra_display1" >
			<td bgColor="#c0cdda" colSpan="6">
			<font size="1"><b>Select Vendor: UCBZeroWaste - Waste Manager Consolidated Invoice</b></font></td>
		</tr>
	<? } 

	?>
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="8">
			<font size="1">Select Vendor:
				<select id="vendor_id" name="vendor_id" onchange="loaddata(<? echo $id; ?>,1)">
					<option value=""></option>
				<?	
							//echo "sdsdf".$dt_view_tran_row["vendor_id"]
					$vendor_ids = ""; $selected_vendor = 0;
					$query = db_query("SELECT water_inventory.vendor FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id WHERE water_boxes_to_warehouse.water_warehouse_id = " . $id . " group by water_inventory.vendor", db() );
									 
					while ($rowsel_getdata = array_shift($query)) {
						$vendor_ids = $vendor_ids . $rowsel_getdata["vendor"] . ",";
					}
					if ($vendor_ids != ""){
						$vendor_ids = substr($vendor_ids, 0, strlen($vendor_ids)-1);
					}
					
					$query = db_query( "SELECT * FROM water_vendors where active_flg = 1 and id in ($vendor_ids) order by Name", db() );
					while ($rowsel_getdata = array_shift($query)) {
						$tmp_str = "";
						if ($dt_view_tran_row["vendor_id"] == $rowsel_getdata["id"]) {
							$tmp_str = " selected ";
							$selected_vendor = $rowsel_getdata["id"];
						}
						
						$main_material = $rowsel_getdata['description'];
						
						//$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
						$vender_nm = $rowsel_getdata['Name']. " - ". $main_material;
					?>
						<option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?> ><? echo $vender_nm;?></option>
					<?}
				?>
				</select>
			</font>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		ENTRY DATE
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<input type="text" name="report_date" id="report_date" size="20" readonly value="<?php echo isset($dt_view_tran_row['report_date']) ? $dt_view_tran_row['report_date'] : ''; ?>" > 
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE CURRENCY
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<select name="invoice_currency" id="invoice_currency">
				<option <? if($dt_view_tran_row['invoice_currency']=="U.S. Dollars"){ echo "selected='selected'"; } ?> >U.S. Dollars</option>
				<option <? if($dt_view_tran_row['invoice_currency']=="Canadian Dollars"){ echo "selected='selected'"; } ?> >Canadian Dollars</option>
			</select>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		SERVICE BEGIN DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="service_begin_date" id="service_begin_date" size="20" value="<?php echo isset($dt_view_tran_row['service_begin_date']) ? $dt_view_tran_row['service_begin_date'] : ''; ?>" > 
			<a href="#" onclick="cal4xx.select(document.frmsortedit.service_begin_date,'dtanchor4xx','yyyy-MM-dd'); return false;" name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		SERVICE END DATE
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<input type="text" name="invoice_date" id="invoice_date" readonly size="20" value="<?php echo isset($dt_view_tran_row['invoice_date']) ? $dt_view_tran_row['invoice_date'] : ''; ?>" > 
			<a href="#" onclick="cal2xx.select(document.frmsortedit.invoice_date,'dtanchor3xx','yyyy-MM-dd'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
			<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>		
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE DATE
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="new_invoice_date" id="new_invoice_date" size="20" value="<?php echo isset($dt_view_tran_row['new_invoice_date']) ? $dt_view_tran_row['new_invoice_date'] : ''; ?>" > 
			<a href="#" onclick="cal5xx.select(document.frmsortedit.new_invoice_date,'dtanchor5xx','yyyy-MM-dd'); return false;" name="dtanchor5xx" id="dtanchor5xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE DUE DATE<font color="red">*</font>
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input type="text" name="invoice_due_date" id="invoice_due_date" size="20" value="<?php echo isset($dt_view_tran_row['invoice_due_date']) ? $dt_view_tran_row['invoice_due_date'] : ''; ?>" > 
			<a href="#" onclick="cal6xx.select(document.frmsortedit.invoice_due_date,'dtanchor6xx','yyyy-MM-dd'); return false;" name="dtanchor6xx" id="dtanchor6xx"><img border="0" src="images/calendar.jpg"></a>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		LAST MODIFIED
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<?php echo $dt_view_tran_row['last_edited']; ?>
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		INVOICE NUMBER
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<input type="text" name="invoice_no" id="invoice_no" size="20" value="<? echo $dt_view_tran_row["invoice_number"]; ?>" onchange="chkduplicateinv()" > 
		</td>
	</tr>
	<? if( $dt_view_tran_row["vendor_id"] == 844 ){ ?> 
		<tr bgColor="#e4e4e4" id="ucbzw_extra_display2" >
			<td colspan="3" height="13" class="style1" align="right">
			VENDORS NET COST OR REVENUE
			</td>
			<td height="13" colspan="3" class="style1" align="left">
				<input type="text" name="txtNetcostRev" id="txtNetcostRev" size="20" value="<?=$dt_view_tran_row["vendors_net_cost_or_revenue"]; ?>" onchange="calcTotalDuetoucb()" >
			</td>
		</tr>
		<tr bgColor="#e4e4e4" id="ucbzw_extra_display3" >
			<td colspan="3" height="13" class="style1" align="right">
			GROSS SAVINGS
			</td>
			<td height="13" colspan="3" class="style1" align="left">
				<input type="text" name="txtGrossSavings" id="txtGrossSavings" size="20" value="<?=$dt_view_tran_row["gross_savings"]; ?>"  >
			</td>
		</tr>
		<tr bgColor="#e4e4e4" id="ucbzw_extra_display4" >
			<td colspan="3" height="13" class="style1" align="right">
			CLIENT NET SAVINGS
			</td>
			<td height="13" colspan="3" class="style1" align="left">
				<input type="text" name="txtClientNetSavings" id="txtClientNetSavings" size="20" value="<?=$dt_view_tran_row["client_net_savings"]; ?>" >
			</td>
		</tr>
		<tr bgColor="#e4e4e4" id="ucbzw_extra_display5" >
			<td colspan="3" height="13" class="style1" align="right">
			UCB SAVINGS SHARE
			</td>
			<td height="13" colspan="3" class="style1" align="left">
				<input type="text" name="txtUCBSavingsSplit" id="txtUCBSavingsSplit" size="20" value="<?=$dt_view_tran_row["ucb_savings_split"]; ?>"  onchange="calcTotalDuetoucb()"  >
			</td>
		</tr>
		<tr bgColor="#e4e4e4" id="ucbzw_extra_display6" >
			<td colspan="3" height="13" class="style1" align="right">
			TOTAL DUE TO UCB
			</td>
			<td height="13" colspan="3" class="style1" align="left">
				<input type="text" name="txtTotalDueToUcb" id="txtTotalDueToUcb" size="20" value="<?=$dt_view_tran_row["total_due_to_ucb"]; ?>" readonly >
			</td>
		</tr>
	<? } ?>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
			NO INVOICE DUE:
		</td>
		<td height="13" colspan="3" class="style1" align="left">
		
			<?
			if ($dt_view_tran_row["no_invoice_due_flg"] == 1){
				echo "Yes, flag marked by " . $dt_view_tran_row["no_invoice_due_marked_by"] . " " . date("m/d/Y" , strtotime($dt_view_tran_row["no_invoice_due_marked_on"]));
			?>	
				<input type="button" name="btn_no_inv_rev" id="btn_no_inv_rev" value="UNDO - NO INVOICE" onclick="mark_btn_no_inv_edit_undo()">
			<?	
			}else{
			?>
				<input type="button" name="btn_no_inv" id="btn_no_inv" value="NO INVOICE" onclick="mark_btn_no_inv_edit()">
			<?
			}
			?>
			
		</td>
		
		
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
			SCAN OF INVOICE:
		</td>
		<td height="13" colspan="5" class="style1" align="left" id="scanfiletd">
			<? if ($dt_view_tran_row["scan_report"] != "") {
					$tmppos_1 = strpos($dt_view_tran_row["scan_report"], "|");
					if ($tmppos_1 != false)
					{ 	
						$elements = explode("|", $dt_view_tran_row["scan_report"]);
						for ($i = 0; $i < count($elements); $i++) {	?>										
							<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
							<input type="button" name="btnremoveattachment" id="btnremoveattachment" value="X" onclick="removeattachment('<? echo $elements[$i]; ?>', <? echo $rec_id; ?>, <? echo $_REQUEST['ID']; ?>, <? echo $id; ?>)" />
							<br>
						<?}
					}else {		
			?>
					<a target="_blank" href='water_scanreport/<? echo $dt_view_tran_row["scan_report"]; ?>'><font size="1">View Attachments</font></a>
					<input type="button" name="btnremoveattachment" id="btnremoveattachment" value="X" onclick="removeattachment('<? echo $dt_view_tran_row["scan_report"]; ?>', <? echo $rec_id; ?>, <? echo $_REQUEST['ID']; ?>, <? echo $id; ?>)" />
					<br>
				<? }
				}?>
			<input type="file" name="uploadscanrep[]" id="uploadscanrep"  multiple>
		</td>
	</tr>
	
	<tr>
		<td colspan="8" >
		<div id="div_boxrep">
	<table id="tblmain_entry" width="600px" cellSpacing="1" cellPadding="1" border="0">
		<tr>
			<th bgColor="#c0cdda" colspan="12"><font size="1">MATERIALS</font></th>
	    </tr>
	  <tr>
		<th bgColor="#c0cdda" class="first"><font size="1">WATER ITEM</font></th>
		<th bgColor="#c0cdda"><font size="1">COST ($) PER UNIT</font></th>
		<th bgColor="#c0cdda"><font size="1">REVENUE ($) PER UNIT</font></th>
		<th bgColor="#c0cdda"><font size="1">UNIT COUNT</font></th>
		<th bgColor="#c0cdda" width="120"><font size="1">WEIGHT</font></th>
		<th bgColor="#c0cdda"><font size="1">TOTAL COST ($)</font></th>
		<th bgColor="#c0cdda"><font size="1">TOTAL REVENUE ($)</font></th>
		<th bgColor="#c0cdda" ><font size="1">OUTLET</font></th>
		<th bgColor="#c0cdda" ><font size="1">BASELINE COMMODITY</font></th>
		<th bgColor="#c0cdda" ><font size="1">BASELINE RATE (COST)/UNIT</font></th>
		<th bgColor="#c0cdda" ><font size="1">BASELINE RATE (REVENUE)/UNIT</font></th>
		<th bgColor="#c0cdda">&nbsp;</th>
	  </tr>

<? 
$inedit_data_found = "no";
$display_header = "n"; $display_header2 = "n"; $CostOrRevenuePerUnit = "";
$get_boxes_query = db_query("SELECT *, water_inventory.id as boxid FROM water_boxes_report_data INNER JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.id WHERE water_boxes_report_data.trans_rec_id = " . $rec_id . " order by water_boxes_report_data.id asc ", db());
$i=0; $ctrlcnt = 0; $tot_val1 = 0;
$tot_cost =0;  $tot_revenue =0;
while ($boxes = array_shift($get_boxes_query)) {
	$inedit_data_found = "yes";
	
	$count=tep_db_num_rows($get_boxes_query);
	$count_and_one = $count + 1;
	$i++;
	$ctrlcnt = $ctrlcnt+ 1;
	$sql_getdata = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $boxes["vendor"]  . "'";
	$query = db_query($sql_getdata, db());
	$vender_nm = "";
	while($rowsel_getdata = array_shift($query))
	{
		$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
	}
	$tot_val1 = $tot_val1 + $boxes["total_value"];
	
	$base_line_rate_cost = ""; $base_line_rate_revenue = ""; $base_line_commodity = "";
	$q1 = "select cm.*, um.unit_of_measure as uom, um.id as uom_id from base_line_commodity_master cm join base_line_unit_of_measure um on cm.unit_of_measure=um.id
	where cm.active_flg = 1 and cm.commodity_id = '" . $boxes["base_line_commodity_id"] . "'";
	$query = db_query($q1, db());
	while($data_row = array_shift($query))
	{
		$base_line_commodity = $data_row['commodity'];
		if ($data_row["cost"] != 0){
			$base_line_rate_cost = "Cost: $". $data_row["cost"] . "/". $data_row["uom"];
		}	
		if ($data_row["revenue"] != 0){
			$base_line_rate_revenue = "Revenue: $". $data_row["revenue"] . "/". $data_row["uom"];
		}	
	} 
				
	/*if ($boxes["WeightorNumberofPulls"] == "Weight"){
		if ($display_header == "n"){
			$display_header = "y";
			
			$AmountUnit = ""; 
			$head_str = ""; $head_str1 = "";
			if ($boxes["AmountUnit"] == "Tons"){
				$AmountUnit = "tons";
			} 
			if ($boxes["AmountUnit"] == "Pounds"){
				$AmountUnit = "lbs";
			} 
			if ($boxes["AmountUnit"] == "Kilograms"){
				$AmountUnit = "kg";
			} 
			
			if ($boxes["CostOrRevenuePerUnit"] == "Cost Per Unit"){
				$head_str =	"COST PER UNIT ($/" . $AmountUnit . ")";
				$head_str1 = "TOTAL COST";
				$CostOrRevenuePerUnit = "COST";
			}
			if ($boxes["CostOrRevenuePerUnit"] == "Revenue Per Unit"){
				$head_str =	"REVENUE PER UNIT ($/" . $AmountUnit . ")";
				$head_str1 = "TOTAL REVENUE";
				$CostOrRevenuePerUnit = "REVENUE";
			}
		}
	}

	if ($boxes["WeightorNumberofPulls"] == "Number of Pulls"){
		if ($display_header2 == "n"){
			$display_header2 = "y";
			
			$AmountUnit = ""; 
			$head_str = ""; $head_str1 = "";
			
			$head_str =	"COST PER UNIT ($/pull)";
			$head_str1 = "TOTAL COST";
			$CostOrRevenuePerUnit = "COST";
			if ($boxes["CostOrRevenuePerUnit"] == "Cost Per Unit"){
				$head_str =	"COST PER UNIT ($/pull)";
				$head_str1 = "TOTAL COST";
				$CostOrRevenuePerUnit = "COST";
			}
			if ($boxes["CostOrRevenuePerUnit"] == "Revenue Per Unit"){
				$head_str =	"REVENUE PER UNIT ($/pull)";
				$head_str1 = "TOTAL REVENUE";
				$CostOrRevenuePerUnit = "REVENUE";
			}
		}
	}	*/
?>	 	

	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
			<? echo $boxes["description"] . "/" . $boxes["WeightorNumberofPulls"];?>
		</td>
	
		<!-- <td height="13" class="style1" align="right">
			<input size="3" name="boxcount[]" id="boxcount<? echo $ctrlcnt; ?>" onchange="calculateweight(<? echo $ctrlcnt; ?>)" type="text" value="<? echo $boxes["count_val"]; ?>">
		</td> -->
		
		<? if ($boxes["CostOrRevenuePerUnit"] == "Cost Per Unit" || $boxes["CostOrRevenuePerPull"] == "Cost Per Pull" || $boxes["CostOrRevenuePerItem"] == "Cost Per Item") { ?>		
			<td height="13" class="style1" align="right">
				<input size="3" name="txtcostperunit[]" type="text" onkeypress='return IsNumeric(event);' id="txtcostperunit<? echo $ctrlcnt; ?>" onchange="calculatecost(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["value_each"]; ?>">
				<span id="txtcostunit<? echo $ctrlcnt; ?>">
				<?  if ($boxes["WeightorNumberofPulls"] == "By Number of Pulls") { ?>
					<? 
						echo "/Pull";
					}else{
						if ($boxes["AmountUnit"] != ""){ echo "/" . $boxes["AmountUnit"]; } 
					}
				?>
				</span>
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">
				<input size="3" name="txtcostperunit[]" type="text" onkeypress='return IsNumeric(event);' id="txtcostperunit<? echo $ctrlcnt; ?>" style="display:none;" onchange="calculatecost(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["value_each"]; ?>">
			</td><span id="txtcostunit<? echo $ctrlcnt; ?>"></span>
		<? }  ?>		
		<? if ($boxes["CostOrRevenuePerUnit"] == "Revenue Per Unit" || $boxes["CostOrRevenuePerPull"] == "Revenue Per Pull" || $boxes["CostOrRevenuePerItem"] == "Revenue Per Item") { ?>		
			<td height="13" class="style1" align="right">
				<input size="3" name="txtrevenueperunit[]" type="text" onkeypress='return IsNumeric(event);' id="txtrevenueperunit<? echo $ctrlcnt; ?>"  onchange="calculaterevenue(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["value_each"]; ?>">
				<span id="txtrevenueunit<? echo $ctrlcnt; ?>">
				<? if ($boxes["AmountUnit"] != ""){ echo "/" . $boxes["AmountUnit"]; } ?>
				</span>
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">
				<input size="3" name="txtrevenueperunit[]" type="text" onkeypress='return IsNumeric(event);' id="txtrevenueperunit<? echo $ctrlcnt; ?>" style="display:none;" onchange="calculaterevenue(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["value_each"]; ?>">
			</td><span id="txtrevenueunit<? echo $ctrlcnt; ?>"></span>
		<? }  ?>		
		<td height="13" class="style1" align="right">
			<input size="3" name="txtunitcount[]" type="text" id="txtunitcount<? echo $ctrlcnt; ?>" onkeypress='return IsNumeric(event);' onchange="calculateunitcount(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["unit_count"]; ?>">
		</td>
		<?
		/***************************/
		/**Add gallon option in weight unit. Also add it on add new row functinality
		/**Done by Nayan
		/**Date : 11 Feb 2021
		/**If selected unit is gallon then option select shows Gallon
		/**Date : 12 Feb 2021
		/***************************/
		?>
		<td height="13" class="style1" align="right">
			<Font Face='arial' size='2'>
			<input size="3" name="txtweight[]" type="text" id="txtweight<? echo $ctrlcnt; ?>" onkeypress='return IsNumeric(event);' onchange="calculateweightnew(<? echo $ctrlcnt; ?>)" value="<? echo $boxes["weight"]; ?>">
			<select name='weight_unit[]' id='weight_unit<? echo $ctrlcnt; ?>' onchange='water_item_convunit(<? echo $ctrlcnt; ?>)' style='width: 60px'>
				<?
					$selectedval_1 = ""; $selectedval_2 = ""; 
					$selectedval_3 = ""; $selectedval_4 = "";
					if ($boxes["weight_unit"] == "Tons"){
						$selectedval_1 = " selected ";
					} 
					if ($boxes["weight_unit"] == "Pounds"){
						$selectedval_2 = " selected ";
					} 
					if ($boxes["weight_unit"] == "Kilograms"){
						$selectedval_3 = " selected ";
					} 
					if ($boxes["weight_unit"] == "Pounds" && $boxes["AmountUnitEquivalent"] == "Gallon"){
						$selectedval_4 = " selected ";
					}
				?>
			
				<option value=0></option>
				<option value="Tons" <? echo $selectedval_1;?>>Tons (T)</option>
				<option value="Pounds" <? echo $selectedval_2;?>>Pounds (lb)</option>
				<option value="Kilograms" <? echo $selectedval_3;?>>Kilograms (kg)</option>
				<option value="Gallon" <? echo $selectedval_4;?>>Gallon (gal)</option>
			</select>
			<span id="txttotweight<? echo $ctrlcnt; ?>" style='font-size:10;'></span>			
		</td>
		<? if ($boxes["CostOrRevenuePerUnit"] == "Cost Per Unit" || $boxes["CostOrRevenuePerPull"] == "Cost Per Pull" || $boxes["CostOrRevenuePerItem"] == "Cost Per Item") { 
				$tot_cost =$tot_cost + $boxes["total_value"]; 
		?>		
			<td height="13" class="style1" align="right">
				<Font Face='arial' size='2'>
				<input size="5" name="txttotalcost[]" type="text" readonly id="txttotalcost<? echo $ctrlcnt; ?>" value="<? echo $boxes["total_value"]; ?>">
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">
				<Font Face='arial' size='2'>
				<input size="5" name="txttotalcost[]" type="text" readonly style="display:none;" id="txttotalcost<? echo $ctrlcnt; ?>" value="<? echo $boxes["total_value"]; ?>">
			</td>
		<? }  
			//echo $boxes["total_value"] . "<br>";
		?>		

		<? if ($boxes["CostOrRevenuePerUnit"] == "Revenue Per Unit"  || $boxes["CostOrRevenuePerPull"] == "Revenue Per Pull" || $boxes["CostOrRevenuePerItem"] == "Revenue Per Item") { 
				$tot_revenue = $tot_revenue + $boxes["total_value"]; 
		?>		
			<td height="13" class="style1" align="right">
				<Font Face='arial' size='2'>
				<input size="5" name="txttotalrevenue[]" type="text" readonly id="txttotalrevenue<? echo $ctrlcnt; ?>" value="<? echo $boxes["total_value"]; ?>">
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">
				<Font Face='arial' size='2'>
				<input size="5" name="txttotalrevenue[]" type="text" readonly style="display:none;" id="txttotalrevenue<? echo $ctrlcnt; ?>" value="<? echo $boxes["total_value"]; ?>">
			</td>
		<? }  ?>		
		<td align="left" height="13" style="width: 80px" class="style1">
			<input type="hidden" name="water_item[]" id="water_item<? echo $ctrlcnt; ?>" value="<? echo $boxes["boxid"]."|" . $boxes["CostOrRevenuePerUnit"] . "|" . $boxes["AmountUnit"] . "|" . $boxes["Amount"]. "|" . $boxes["Outlet"] . "|" . $boxes["WeightorNumberofPulls"] . "|" . $boxes["CostOrRevenuePerPull"]. "|" . $boxes["CostOrRevenuePerItem"]. "|" . $boxes["Estimatedweight"]. "|" . $boxes["Estimatedweight_value"]. "|" . $boxes["Estimatedweight_peritem"]. "|" . $boxes["Estimatedweight_value_peritem"]. "|" . $boxes["poundpergallon_value"] . "|" . $base_line_commodity . "|" . $base_line_rate_cost . "|" . $base_line_rate_revenue; ?>"/>	
			<input type="hidden" name="box_id[]" value="<? echo $boxes["boxid"]; ?>"/>
			<input type="hidden" name="poundpergallon[]" id="pound_per_gallon<? echo $ctrlcnt; ?>" value="<? echo $boxes["poundpergallon_value"]; ?>"/>
			<input type="hidden" name="CostOrRevenuePerUnit[]" value="<? echo $CostOrRevenuePerUnit; ?>"/>	
			<input type="hidden" name="box_weight" id="box_weight<? echo $ctrlcnt; ?>" value="<? echo $boxes["bweight"]; ?>"/>	
			<input type="hidden" name="box_value" id="box_value<? echo $ctrlcnt; ?>" value="<? echo $boxes["boxgoodvalue"]; ?>"/>	
			<input type="hidden" name="outlet[]" id="outlet<? echo $ctrlcnt; ?>" value="<? echo $boxes["Outlet"]; ?>"/>	
			<font size="1" Face="arial"><? echo $boxes["Outlet"]; ?></font>
		</td>
		<td align="left" height="13" style="width: 80px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_commodity; ?></font>
		</td>
		<td align="left" height="13" style="width: 80px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_rate_cost; ?></font>
		</td>
		<td align="left" height="13" style="width: 80px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_rate_revenue; ?></font>
		</td>
		
		<td bgColor='#e4e4e4'> <input type='button' name='btnremove[]' id='btnremove' value='X' onclick='remove_newrow_main(this)'/> </td>
		
	</tr>
	<?	
		//To add blank row
		$blank_row_str = "<tr ><td bgColor='#e4e4e4'>";
		$get_boxes_query1 = "SELECT *, water_inventory.id as boxid FROM water_boxes_to_warehouse INNER JOIN water_inventory ON water_boxes_to_warehouse.water_boxes_id = water_inventory.id WHERE water_boxes_to_warehouse.water_warehouse_id = " . $boxes["warehouse_id"] . " and vendor = '" . $boxes["vendor"] . "' ORDER BY description";
		$result = db_query($get_boxes_query1, db());
		$blank_row_str .= "<select name='water_item[]' id='water_itemctrltoreplace' onchange='water_item_showdetails(ctrltoreplace)'>" ;
		$blank_row_str .= "<option value=0></option>";
		while ($myrowsel = array_shift($result)) {
			$base_line_rate_cost = ""; $base_line_rate_revenue = ""; $base_line_commodity = "";
			$q1 = "select cm.*, um.unit_of_measure as uom, um.id as uom_id from base_line_commodity_master cm join base_line_unit_of_measure um on cm.unit_of_measure=um.id
			where cm.active_flg = 1 and cm.commodity_id = '" . $myrowsel["base_line_commodity_id"] . "'";
			$query = db_query($q1, db());
			while($data_row = array_shift($query))
			{
				$base_line_commodity = $data_row['commodity'];
				if ($data_row["cost"] != 0){
					$base_line_rate_cost = "Cost: $". $data_row["cost"] . "/". $data_row["uom"];
				}	
				if ($data_row["revenue"] != 0){
					$base_line_rate_revenue = "Revenue: $". $data_row["revenue"] . "/". $data_row["uom"];
				}	
			} 
			
			$control_chg_cnt = $control_chg_cnt + 1;
			
			$blank_row_str .= "<option value='".$myrowsel["boxid"]."|" . $myrowsel["CostOrRevenuePerUnit"] . "|" . $myrowsel["AmountUnit"] . "|" . $myrowsel["Amount"] . "|" . $myrowsel["Outlet"]. "|" . $myrowsel["WeightorNumberofPulls"] . "|" . $myrowsel["CostOrRevenuePerPull"]. "|" . $myrowsel["CostOrRevenuePerItem"]. "|" . $myrowsel["Estimatedweight"] . "|" . $myrowsel["Estimatedweight_value"]. "|" . $myrowsel["Estimatedweight_peritem"]. "|" . $myrowsel["Estimatedweight_value_peritem"]. "|" . $myrowsel["poundpergallon_value"] . "|" . $base_line_commodity . "|" . $base_line_rate_cost . "|" . $base_line_rate_revenue;
			$blank_row_str .= "' >". $myrowsel["description"]  . "/" . $myrowsel["WeightorNumberofPulls"] . "</option>";
		}				
		$blank_row_str .= "</select>";
		$blank_row_str .= "</td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txtcostperunit[]' onkeypress='return IsNumeric(event);' type='text' id='txtcostperunitctrltoreplace' onchange='calculatecost(ctrltoreplace)' value=''> <font size=1><span id='txtcostunitctrltoreplace'> </span></font></td>";
		//$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txtcostperunit[]' onkeypress='return IsNumeric(event);' type='text' id='txtcostperunitctrltoreplace' onchange='calculatecost(ctrltoreplace)' value=''> <font size=1><span class='txtcostunitctrltoreplace'> </span></font></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txtrevenueperunit[]' onkeypress='return IsNumeric(event);' type='text' id='txtrevenueperunitctrltoreplace' onchange='calculaterevenue(ctrltoreplace)' value=''> <font size=1><span id='txtrevenueunitctrltoreplace'> </span></font></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txtunitcount[]' type='text' onkeypress='return IsNumeric(event);' id='txtunitcountctrltoreplace' onchange='calculateunitcount(ctrltoreplace)' value=''> </td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txtweight[]' type='text' onkeypress='return IsNumeric(event);' id='txtweightctrltoreplace' onchange='calculateweightnew(ctrltoreplace)' value=''> ";
		$blank_row_str .= "<select name='weight_unit[]' id='weight_unitctrltoreplace' onchange='water_item_convunit(ctrltoreplace)' style='width: 50px'>";
		$blank_row_str .= "<option value=0></option>";
		$blank_row_str .= "<option value='Tons'>Tons (T)</option>";
		$blank_row_str .= "<option value='Pounds'>Pounds (lb)</option>";
		$blank_row_str .= "<option value='Kilograms'>Kilograms (kg)</option>";
		$blank_row_str .= "<option value='Gallon'>Gallon (gal)</option>";
		$blank_row_str .= "</select>&nbsp;<span id='txttotweightctrltoreplace' style='font-size:10;'></span></td> "; 
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txttotalcost[]' type='text' id='txttotalcostctrltoreplace' value=''> </td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input size='3' name='txttotalrevenue[]' type='text' id='txttotalrevenuectrltoreplace' value=''> </td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <span id='txtoutletctrltoreplace' style='font-size:12;'> </span></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <span id='base_line_commodityctrltoreplace' style='font-size:12;'> </span></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <span id='base_line_rate_costctrltoreplace' style='font-size:12;'> </span></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <span id='base_line_rate_revenuectrltoreplace' style='font-size:12;'> </span></td>";
		$blank_row_str .= "<td bgColor='#e4e4e4'> <input type='button' name='btnremove[]' id='btnremove' value='X' onclick='remove_newrow_main(this)'/> </td></tr>";
} 
?>
</table>
<table id="tblmain_entry" width="600px" cellSpacing="1" cellPadding="1" border="0">
		<tr>
			<td colspan="12" align="right" bgColor='#e4e4e4'>
				<input type="button" name="add_newrec" id="add_newrec" value="Add New Row" onclick="add_newrow_main()">
				<input type="hidden" name="row_cnt_main" id="row_cnt_main" value="<? echo $ctrlcnt; ?>" > 
				<input type="hidden" name="row_str_main" id="row_str_main" value="<? echo $blank_row_str; ?>" > 
			</td>
		</tr>
		<tr>
			<td colspan="10" align="left" bgColor='#e4e4e4'>
				<font size="1">Total Material Revenue ($)</font>
			</td>
			<td align="right" bgColor='#e4e4e4'>
				<input type="text" name="txttotal_revenue" id="txttotal_revenue" size="5" readonly value="<? echo $tot_revenue; ?>" > 
			</td>
			<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" align="left" bgColor='#e4e4e4'>
				<font size="1">Total Material Cost ($)</font>
			</td>
			<td align="right" bgColor='#e4e4e4'>
				<input type="text" name="txttotal_cost" id="txttotal_cost" size="5" readonly value="<? echo $tot_cost; ?>" > 
			</td>
			<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" align="left" bgColor='#e4e4e4'>
				<font size="1">Total Material Profit ($)</font>
			</td>
			<td align="right" bgColor='#e4e4e4'>
				<input type="text" name="txttotal_profit" id="txttotal_profit" size="5" readonly value="<? echo $tot_revenue - $tot_cost; ?>" > 
			</td>
			<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		</tr>
	</table>
	
	</div>

		</td>
	</tr>
	<?
		if ($inedit_data_found == "no")
		{
	?>		
		<script LANGUAGE="JavaScript">
			loaddata(<? echo $id; ?>,0);
		</script>
	<?}?>
	<tr bgColor="#e4e4e4"><td colspan="12">
		<input type="checkbox" name="chkdoubt" id="chkdoubt" value="1" <? echo $chkdoubt_val;?> /><font size="1">I have a Question
		&nbsp;<input type="text" name="txtdoubt" id="txtdoubt" value="<? echo $doubt;?>" /></font>
	</tr>
	
	<tr bgColor="#e4e4e4"><td colspan="12">
		<table id="tblmain_fees">
			<thead>
			  <tr>
				<th bgColor="#c0cdda" class="first"><font size="1">ADDITIONAL FEES & CREDITS</font></th>
				<th bgColor="#c0cdda"><font size="1">COST ($) PER UNIT</font></th>
				<th bgColor="#c0cdda"><font size="1">OCCURRENCES</font></th>
				<th bgColor="#c0cdda"><font size="1">TOTAL COST ($)</font></th>
				<th bgColor="#c0cdda"><font size="1">SAVINGS CALCULATION CATEGORY</font></th>
				<th bgColor="#c0cdda"><font size="1">REMARK</font></th>
			  </tr>
			</thead>

			<tbody>			
			<?
			$control_chg_cnt = 0; $row_cnt = 1; $blank_row_str = ""; $tot_add_fees = 0;
			//if (isset($_REQUEST["edit"])){
			//	$sql = "SELECT item_charge, unqid from transaction_details_charges where transaction_id = ? order by unqid";
			//	$result = db_query($sql, db());
			//}else{
			
			$sql_main = "SELECT * from water_trans_addfees where trans_id = " . $rec_id . " order by id";
			$result_main = db_query($sql_main, db());
			while ($myrowsel_main = array_shift($result_main)) {	
				$sql = "SELECT * from water_additional_fees where active_flg = 1  order by display_order";
				$result = db_query($sql, db());
				
				$blank_row_str = "<tr><td>";
				$blank_row_str .= "<select name='selfees[]' id='selfees" . $row_cnt . "' onchange='showfees_other(" . $row_cnt . ")'>" ;
				$blank_row_str .= "<option value=0></option>";
				while ($myrowsel = array_shift($result)) {
					$control_chg_cnt = $control_chg_cnt + 1;

					$blank_row_str .= "<option value=".$myrowsel["id"]." ";
					if ($myrowsel["id"] == $myrowsel_main["add_fees_id"]) $blank_row_str .= " selected ";
					$blank_row_str .= " >". $myrowsel["additional_fees_display"] . "</option>";
				}	
					
				if ($saved_ocr_val_flg == 1){
					$tot_add_fees = $tot_add_fees + ($myrowsel_main["fee_total_val"]);	
				}else{
					if ($myrowsel_main["add_fees_occurance"] > 0)
					{
						$tot_add_fees = $tot_add_fees + ($myrowsel_main["add_fees"] * $myrowsel_main["add_fees_occurance"]);	
					}else{
						$tot_add_fees = $tot_add_fees + ($myrowsel_main["add_fees"]);	
					}
				}

				$blank_row_str .= "</select>";
				$blank_row_str .= "</td>";
				$blank_row_str .= "<td> <input type='text' name='txtcharge[]' onkeypress='return chknumberval(event," . $row_cnt . ");' id='txtcharge" . $row_cnt . "' value='" . $myrowsel_main["add_fees"] . "' onchange='update_fee_total()' size='5' /></td>";
				$blank_row_str .= "<td> <input type='text' name='txtOccurrences[]' onkeypress='return IsNumeric(event);' id='txtOccurrences' value='" . $myrowsel_main["add_fees_occurance"] . "' onchange='update_fee_total()' size='5' /> </td>";
				$blank_row_str .= "<td> <input type='text' name='txtfeetotalcost[]' readonly onkeypress='return IsNumeric(event);' id='txtfeetotalcost' value='" . ($myrowsel_main["add_fees"] * $myrowsel_main["add_fees_occurance"]) . "' size='5' /> </td>";

				$blank_row_str .= "<td>";
				$sql = "SELECT savings_calculation_category_id, savings_calculation_category, 1 as savings_calculation_category_flg from water_savings_calculation_category
				union
				SELECT commodity_id as savings_calculation_category_id, commodity as savings_calculation_category , 2 as savings_calculation_category_flg
				from base_line_commodity_master where company_id = '" . $_REQUEST["ID"] ."'";
				$result = db_query($sql, db());
				$blank_row_str .= "<select name='savings_calculation_category[]' id='savings_calculation_category" . $row_cnt . "'>" ;
				$blank_row_str .= "<option value=0></option>";
				while ($myrowsel = array_shift($result)) {
					if (($myrowsel["savings_calculation_category_id"] == $myrowsel_main["savings_calculation_category_id"]) && ($myrowsel["savings_calculation_category_flg"] == $myrowsel_main["savings_calculation_category_flg"])) {
						$blank_row_str .= "<option value=".$myrowsel["savings_calculation_category_id"] ."|" . $myrowsel["savings_calculation_category_flg"] . " selected ";
					}else{
						$blank_row_str .= "<option value=".$myrowsel["savings_calculation_category_id"] ."|" . $myrowsel["savings_calculation_category_flg"] . " ";
					}						
					$blank_row_str .= " >". $myrowsel["savings_calculation_category"] . "</option>";
				}				
				$blank_row_str .= "</select>";
				$blank_row_str .= "</td>";
				
				$blank_row_str .= "<td> <input type='text' name='txtremark_fees[]' id='txtremark_fees' value='" . $myrowsel_main["add_fee_remark"] . "' size='20' /> </td>";
				$blank_row_str .= "<td> <input type='button' name='btnremove_chrg[]' id='btnremove_chrg' value='X' onclick='remove_newrow_chrg(this)'/> </td></tr>";
				$row_cnt = $row_cnt + 1;
				
				echo $blank_row_str;	
			}
			
			//To add blank row
			$blank_row_str = "<tr><td>";
			$sql1 = "SELECT * FROM water_additional_fees  where active_flg = 1 order by display_order";
			$result1 = db_query($sql1, db());
			$blank_row_str .= "<select name='selfees[]' id='selfeesctrltoreplace' onchange='showfees_other(ctrltoreplace)'>";
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel1 = array_shift($result1)) 
			{
				$blank_row_str .= "<option value=".$myrowsel1["id"]." ";
				$blank_row_str .= " >". $myrowsel1["additional_fees_display"] . "</option>";
			}
			$blank_row_str .= "</select>";
			$blank_row_str .= "<td> <input type='text' name='txtcharge[]' id='txtchargectrltoreplace' onkeypress='return chknumberval(event,ctrltoreplace);' onchange='update_fee_total()' value='' size='5' /> </td>";
			$blank_row_str .= "<td> <input type='text' name='txtOccurrences[]' id='txtOccurrences' onkeypress='return IsNumeric(event);' onchange='update_fee_total()' value='' size='5' /> </td>";
			$blank_row_str .= "<td> <input type='text' name='txtfeetotalcost[]' readonly onkeypress='return IsNumeric(event);' id='txtfeetotalcost' value='' size='5' /> </td>";
			
			$blank_row_str .= "<td>";
			$sql = "SELECT savings_calculation_category_id, savings_calculation_category, 1 as savings_calculation_category_flg from water_savings_calculation_category
				union
				SELECT commodity_id as savings_calculation_category_id, commodity as savings_calculation_category , 2 as savings_calculation_category_flg
				from base_line_commodity_master where company_id = '" . $_REQUEST["ID"] ."'";
			$result = db_query($sql, db());
			$blank_row_str .= "<select name='savings_calculation_category[]' id='savings_calculation_categoryctrltoreplace'>" ;
			$blank_row_str .= "<option value=0></option>";
			while ($myrowsel = array_shift($result)) {
				$blank_row_str .= "<option value=".$myrowsel["savings_calculation_category_id"]."|" . $myrowsel["savings_calculation_category_flg"];
				$blank_row_str .= " >". $myrowsel["savings_calculation_category"] . "</option>";
			}				
			$blank_row_str .= "</select>";
			$blank_row_str .= "</td>";
			
			$blank_row_str .= "<td> <input type='text' name='txtremark_fees[]' id='txtremark_fees' value='' size='20' /> </td>";
			$blank_row_str .= "<td> <input type='button' name='btnremove_chrg[]' id='btnremove_chrg' value='X' onclick='remove_newrow_chrg(this)'/> </td></tr>";
			
			if ($row_cnt == 1) {
				echo $blank_row_str;
			}else{
				$row_cnt = $row_cnt -1 ;
			}
			?>
			</tbody>
		</table>
		<table>
				<tr>
					<td colspan="8" align="right">
						<input type="button" name="add_newrec" id="add_newrec" value="Add New Row" onclick="add_newrow_fees()">
						<input type="hidden" name="row_cnt_fees" id="row_cnt_fees" value="<? echo $row_cnt; ?>" > 
						<input type="hidden" name="row_str_fees" id="row_str_fees" value="<? echo $blank_row_str; ?>" > 
					</td>
				</tr>
		</table>
		
	</td></tr>	

	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		TOTAL ADDITIONAL FEES ($)
		</td>
		<td height="13" colspan="3" class="style1" align="left">
			<input size="10" type="text" readonly name="addfees_total" id="addfees_total" value="<? echo $tot_add_fees;?>">
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		FINAL TOTAL ($)
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<input size="10" type="text" name="final_total" readonly id="final_total" value="<? echo ($dt_view_tran_row["total_due_to_ucb"] + ($tot_revenue - $tot_cost) - $tot_add_fees); ?>">
		</td>
	</tr>
	<? 
	/***************************/
	/**Add form field for EDITED BY 
	/**Done by Nayan
	/**Date : 17 Feb 2021
	/***************************/
	?>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		<!-- USER INITIALS --> ENTERED BY (USER INITIALS)
		</td>
		<td height="13" colspan="5" class="style1" align="left">
			<? echo $dt_view_tran_row["repor_entry_emp"]; ?>
			<input type="hidden" name="hidtxtnumrows" value="<? echo $num_rows; ?>">
			<input size="10" type="hidden" name="txtEditedBY" id="txtEditedBY" value="<? echo $_COOKIE['userinitials'];?>">
		</td>
	</tr>

	<tr bgColor="#e4e4e4">
		<td height="13" colspan="3" class="style1" align="right">
		NOTES</td>
		<td height="13" class="style1" align="right" colspan="5">
			<Font size='2' Face="arial">
			<p align="left">
				<textarea rows="3" cols="30" name="boxnotes" id="boxnotes"><? echo $dt_view_tran_row["report_notes"]; ?></textarea>
			</p></Font>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13"class="style1" align="right">
		Make or receive payment?</td>
		<td height="13" class="style1" align="left" colspan="5">
		<Font size='2' Face="arial">
			<input type="checkbox" name="make_receive_payment" id="make_receive_payment" value="1" <?php if($dt_view_tran_row["make_receive_payment"]=="1"){ echo 'checked'; } ?> onchange="show_vendor_payment_report()"></font>
		</td>
	</tr>
	<?
	if($dt_view_tran_row["make_receive_payment"]=="1")
	{
	?>
	<tr>
		<td height="13" class="style1" align="left" colspan="6">
			<div id="vendor_pay_reportid">
				<table width="100%" class="vendor_payment_css" cellpadding="0" cellspacing="1">
					<tr bgColor="#e4e4e4">
						<th colspan="2" class="first" align="center" bgcolor="#c0cdda" style="height: 22px;"><font size="2">Vendor Payment Report</font></th>	
					</tr>	
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right" width="285px">Made Payment or Received Payment?</td>
						<td><input type="checkbox" name="made_payment" id="made_payment" value="1" <?php if($dt_view_tran_row["made_payment"]=="1"){ echo 'checked'; } ?>></td>	
					</tr>
					<?
					$finalTotal = ($tot_revenue - $tot_cost) - $tot_add_fees;
					if($finalTotal > 0 ){
					?>
						<tr bgColor="#e4e4e4">
							<td class="style1" align="right">Vendor Credit:</td>
							<td><input type="text" name="vendor_credit" id="vendor_credit" value="<? echo $dt_view_tran_row["vendor_credit"]; ?>"></td>
						</tr>
					<? } ?>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received by:</td>
						<td><input type="text" name="paid_by" id="paid_by" value="<? echo $dt_view_tran_row["paid_by"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received date:</td>
						<td><input type="text" name="paid_date" id="paid_date" value="<? echo $dt_view_tran_row["paid_date"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Payment Method:</td>
						<td><input type="text" name="payment_method" id="payment_method" value="<? echo $dt_view_tran_row["payment_method"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Payment proof file:</td>
						<td id="proof_filetd">
							<? if ($dt_view_tran_row["payment_proof_file"] != "") {
								$tmppos_1 = strpos($dt_view_tran_row["payment_proof_file"], "|");
								if ($tmppos_1 != false)
								{ 	
									$elements = explode("|", $dt_view_tran_row["payment_proof_file"]);
									for ($i = 0; $i < count($elements); $i++) {	?>										
										<a target="_blank" href='water_payment_proof/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
										<input type="button" name="btnremoveattachment_proof" id="btnremoveattachment_proof" value="X" onclick="removeattachment_proof('<? echo $elements[$i]; ?>', <? echo $rec_id; ?>, <? echo $_REQUEST['ID']; ?>, <? echo $id; ?>)" />
										<br>
									<?}
								}else {		
							?>
								<a target="_blank" href='water_payment_proof/<? echo $dt_view_tran_row["payment_proof_file"]; ?>'><font size="1">View Attachments</font></a>
								<input type="button" name="btnremoveattachment_proof" id="btnremoveattachment_proof" value="X" onclick="removeattachment_proof('<? echo $dt_view_tran_row["payment_proof_file"]; ?>', <? echo $rec_id; ?>, <? echo $_REQUEST['ID']; ?>, <? echo $id; ?>)" />
								<br>
							<? 
								}
							}
							?>
							<input type="file" name="payment_proof_file[]" id="payment_proof_file"  multiple onchange="GetFileSize()">
						</td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Log Notes:</td>
						<td><input type="text" name="log_notes" id="log_notes" value="<? echo $dt_view_tran_row["vendor_payment_log_notes"]; ?>"></td>	
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?
	}
  	else{
	?>  
	<tr>
		<td height="13" class="style1" align="left" colspan="6">
			<div id="vendor_pay_reportid"  style="display: none;">
				<table width="100%" class="vendor_payment_css" cellpadding="0" cellspacing="1">
					<tr bgColor="#e4e4e4">
						<th colspan="2" class="first" align="center" bgcolor="#c0cdda" style="height: 22px;"><font size="2">Vendor Payment Report</font></th>	
					</tr>	
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right" width="285px">Made Payment or Received Payment?</td>
						<td><input type="checkbox" name="made_payment" id="made_payment" value="1" <?php if($dt_view_tran_row["made_payment"]=="1"){ echo 'checked'; } ?>></td>	
					</tr>
					<?
					$finalTotal = ($tot_revenue - $tot_cost) - $tot_add_fees;
					//if($finalTotal > 0 ){
					?>
						<tr bgColor="#e4e4e4">
							<td class="style1" align="right">Vendor Credit:</td>
							<td><input type="text" name="vendor_credit" id="vendor_credit" value="<? echo $dt_view_tran_row["vendor_credit"]; ?>"></td>
						</tr>
					<? //} ?>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received by:</td>
						<td><input type="text" name="paid_by" id="paid_by" value="<? echo $dt_view_tran_row["paid_by"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Paid/Received date:</td>
						<td><input type="text" name="paid_date" id="paid_date" value="<? echo $dt_view_tran_row["paid_date"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Payment Method:</td>
						<td><input type="text" name="payment_method" id="payment_method" value="<? echo $dt_view_tran_row["payment_method"]; ?>"></td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Payment proof file:</td>
						<td  id="proof_filetd">
							<input type="file" name="payment_proof_file[]" id="payment_proof_file" multiple onchange="GetFileSize()">
						</td>	
					</tr>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Log Notes:</td>
						<td><input type="text" name="log_notes" id="log_notes" value="<? echo $dt_view_tran_row["vendor_payment_log_notes"]; ?>"></td>	
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?
  }
	?>
	<tr bgColor="#e4e4e4">
		<td colspan=8 align="left" height="19" class="style1">
			<p align="center">	
			<input type="hidden" name="count" value="<? echo $count; ?>">
			<input type="hidden" name="tot_fees" id="tot_fees" value="">
			<input style="cursor:pointer;" type="submit" id="water_entry_btn" name="water_entry_btn" value="SAVE"> <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a>
		</td>
	</tr>
	</table>
</form>
<? } ?>


<br>

<?
if ($_GET["sort_edit"] != "true") { 
	
$good = 0;
$bad = 0;// Added by Mooneem on Apr-24-12$goodvalue = 0;$badvalue = 0;// Added by Mooneem on Apr-24-12
//$dt_view_qry = "SELECT * from water_boxes_report_data WHERE trans_rec_id = '" . $rec_id . "'";
$dt_view_qry = "SELECT * FROM water_boxes_report_data Left JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.id WHERE water_boxes_report_data.trans_rec_id = '" . $rec_id . "'";
//echo $dt_view_qry;
$dt_view_res = db_query($dt_view_qry,db() );
//$num_rows = tep_db_num_rows($dt_view_res);
//$num_rows > 0 &&
if ( $rec_id > 0) {
?>

<!-- VIEW ENTERED SORT --> 
<table cellSpacing="1" cellPadding="1" border="0" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="12">
		<font size="1">VENDOR REPORT</font> 
		<a href="viewCompany_func_water-mysqli.php?ID=<?=$_REQUEST['ID']?>&show=watertransactions&warehouse_id=<? echo $id; ?>&b2bid=<? echo $b2bid; ?>&rec_id=<? echo $rec_id; ?>&rec_type=<? echo $rec_type; ?>&proc=View&searchcrit=&display=water_sort&sort_edit=true">EDIT</a> 
		</td>
	</tr>

<?
$dt_view_tran_qry = "SELECT * from water_transaction WHERE id = " . $rec_id;
$dt_view_tran = db_query($dt_view_tran_qry,db() );
$dt_view_tran_row = array_shift($dt_view_tran);

	$saved_ocr_val_flg = $dt_view_tran_row["saved_ocr_val_flg"];

	$chkdoubt_val = "";
	if ($dt_view_tran_row["have_doubt"] == 1){
		$chkdoubt_val = " checked ";
	}
	$doubt = $dt_view_tran_row["doubt"];
	
	$vender_nm = "";
	$q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '". $dt_view_tran_row["vendor_id"]  . "'";
	$query = db_query($q1, db());
	while($fetch = array_shift($query))
	{
		$vender_nm = $fetch['Name'];
	}

?>
	<?if($dt_view_tran_row["vendor_id"] == 844){ ?>
	<tr align="left">
		<td bgColor="#c0cdda" colSpan="12">
		<font size="1"><b>Select Vendor: UCBZeroWaste - Waste Manager Consolidated Invoice</b></font> 
		</td>
	</tr>
	<? } ?>
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Vendor:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $vender_nm; ?></font></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Invoice Currency:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["invoice_currency"]; ?></font></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Service Begin Date:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["service_begin_date"]; ?></font></td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Service End Date:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["invoice_date"]; ?></font></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Invoice Date:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["new_invoice_date"]; ?></font></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Invoice Due Date:
		</td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["invoice_due_date"]; ?></font></td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Transaction Date: </td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["report_date"]; ?></font></td>
	</tr>	

	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Last Modified: </td>
		<td height="14" class="style1" align="left" colspan="10">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["last_edited"]; ?></font></td>
	</tr>	

	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		Invoice number
		</td>
		<td height="14" colspan="10" class="style1" align="left">
			<font size="1"><? echo $dt_view_tran_row["invoice_number"]; ?></font>
		</td>
	</tr>
	<?if($dt_view_tran_row["vendor_id"] == 844){ ?>
		<tr bgColor="#e4e4e4">
			<td colspan="2" height="13" class="style1" align="left">
			Vendors Net Cost or Revenue
			</td>
			<td height="14" colspan="10" class="style1" align="left">
				<font size="1"><? echo "$".$dt_view_tran_row["vendors_net_cost_or_revenue"]; ?></font>
			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td colspan="2" height="13" class="style1" align="left">
			Gross Savings
			</td>
			<td height="14" colspan="10" class="style1" align="left">
				<font size="1"><? echo "$".$dt_view_tran_row["gross_savings"]; ?></font>
			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td colspan="2" height="13" class="style1" align="left">
			Client Net Savings
			</td>
			<td height="14" colspan="10" class="style1" align="left">
				<font size="1"><? echo "$".$dt_view_tran_row["client_net_savings"]; ?></font>
			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td colspan="2" height="13" class="style1" align="left">
			UCB Savings SHARE
			</td>
			<td height="14" colspan="10" class="style1" align="left">
				<font size="1"><? echo "$".$dt_view_tran_row["ucb_savings_split"]; ?></font>
			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td colspan="2" height="13" class="style1" align="left">
			Total Due to UCB
			</td>
			<td height="14" colspan="10" class="style1" align="left">
				<font size="1"><? echo "$".$dt_view_tran_row["total_due_to_ucb"]; ?></font>
			</td>
		</tr>
	<? } ?>


	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" align="left" class="style1">
		Scan of Invoice: </td>
		<td height="14" class="style1" align="left" colspan="10">
			<? if ($dt_view_tran_row["scan_report"] != "") {
					$tmppos_1 = strpos($dt_view_tran_row["scan_report"], "|");
					if ($tmppos_1 != false)
					{ 	
						$elements = explode("|", $dt_view_tran_row["scan_report"]);
						for ($i = 0; $i < count($elements); $i++) {	?>										
							<a target="_blank" href='water_scanreport/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
						<?}
					}else {		
			?>
					<a target="_blank" href='water_scanreport/<? echo $dt_view_tran_row["scan_report"]; ?>'><font size="1">View Attachments</font></a>
				<? }
				}?>
		
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="2" height="13" class="style1" align="left">
		No Invoice due
		</td>
		<td height="14" colspan="10" class="style1" align="left">
			<?
			if ($dt_view_tran_row["no_invoice_due_flg"] == 1){
				echo "Yes, flag marked by " . $dt_view_tran_row["no_invoice_due_marked_by"] . " " . date("m/d/Y" , strtotime($dt_view_tran_row["no_invoice_due_marked_on"]));
			}
			?>
		</td>
	</tr>	
	
	<tr bgColor="#e4e4e4">
		<td colspan="12" height="13" class="style1" align="center" bgColor="#c0cdda">
			MATERIAL
		</td>
	</tr>	
    <tr>
		<td class="style1" style="width: 150px; height: 13px" bgColor="#c0cdda" ><font size="1">Water Item</font></td>
		<td class="style1" style="width: 80px;" bgColor="#c0cdda"><font size="1">Cost ($) Per Unit</font></td>
		<td class="style1" style="width: 80px;" bgColor="#c0cdda"><font size="1">Revenue ($)<br>Per unit</font></td>
		<td class="style1" style="width: 80px;" bgColor="#c0cdda"><font size="1">Unit Count</font></td>
		<td class="style1" style="width: 20px;" bgColor="#c0cdda" width="150"><font size="1">Weight</font></td>
		<td class="style1" style="width: 20px;" bgColor="#c0cdda" width="150"><font size="1">Total Weight</font></td>
		<td class="style1" style="width: 20px;" bgColor="#c0cdda"><font size="1">Total Cost ($)</font></td>
		<td class="style1" style="width: 20px;" bgColor="#c0cdda"><font size="1">Total Revenue ($)</font></td>
		<td class="style1" style="width: 10px;" bgColor="#c0cdda" ><font size="1">Outlet</font></td>
		<td class="style1" style="width: 10px;" bgColor="#c0cdda" ><font size="1">Baseline commodity</font></td>
		<td class="style1" style="width: 10px;" bgColor="#c0cdda" ><font size="1">Baseline Rate (Cost)/Unit</font></td>
		<td class="style1" style="width: 10px;" bgColor="#c0cdda" ><font size="1">Baseline Rate (Revenue)/Unit</font></td>
	</tr>
	
<?
$display_header = "n"; $display_header2 = "n";
$count_val_tot = 0; $weight_tot = 0; $value_each_tot = 0; $total_value_tot = 0;
$tot_cost = 0; $tot_revenue = 0;
while ($dt_view_row = array_shift($dt_view_res)) {
	$box_desc = ""; $WeightorNumberofPulls = ""; $AmountUnitval = ""; $CostOrRevenuePerUnit = ""; $Outlet = "";
	$sql_getdata = "SELECT vendor, description, WeightorNumberofPulls, AmountUnit, CostOrRevenuePerUnit, Outlet FROM water_inventory where water_inventory.id = '".$dt_view_row["box_id"]."'";
	$query = db_query($sql_getdata, db());
	$vender_id = "";
	while($rowsel_getdata = array_shift($query))
	{
		$vender_id = $rowsel_getdata['vendor'];
		$box_desc =  $rowsel_getdata["description"];
		$WeightorNumberofPulls = $rowsel_getdata["WeightorNumberofPulls"]; 
		$AmountUnitval = $rowsel_getdata["AmountUnit"]; 
		$CostOrRevenuePerUnit = $rowsel_getdata["CostOrRevenuePerUnit"]; 
		$Outlet = $rowsel_getdata["Outlet"]; 
	}
	
	$base_line_rate_cost = ""; $base_line_rate_revenue = ""; $base_line_commodity = "";
	$q1 = "select cm.*, um.unit_of_measure as uom, um.id as uom_id from base_line_commodity_master cm join base_line_unit_of_measure um on cm.unit_of_measure=um.id
	where cm.active_flg = 1 and cm.commodity_id = '" . $dt_view_row["base_line_commodity_id"] . "'";
	$query = db_query($q1, db());
	while($data_row = array_shift($query))
	{
		$base_line_commodity = $data_row['commodity'];
		if ($data_row["cost"] != 0){
			$base_line_rate_cost = "Cost: $". $data_row["cost"] . "/". $data_row["uom"];
		}	
		if ($data_row["revenue"] != 0){
			$base_line_rate_revenue = "Revenue: $". $data_row["revenue"] . "/". $data_row["uom"];
		}	
	} 
	
	$sql_getdata = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $vender_id  . "'";
	$query = db_query($sql_getdata, db());
	$vender_nm = "";
	while($rowsel_getdata = array_shift($query))
	{
		$vender_nm = $rowsel_getdata['Name']. " - ". $rowsel_getdata['city']. ", ". $rowsel_getdata['state']. " ". $rowsel_getdata['zipcode'];
	}
	$count_val_tot = $count_val_tot + $dt_view_row["count_val"];
	$weight_tot = $weight_tot + $dt_view_row["weight"];
	$value_each_tot = $value_each_tot + $dt_view_row["value_each"];
	$total_value_tot = $total_value_tot + $dt_view_row["total_value"];

	/*if ($WeightorNumberofPulls == "By Weight"){
		if ($display_header == "n"){
			$display_header = "y";
			
			$AmountUnit = ""; 
			$head_str = ""; $head_str1 = "";
			if ($AmountUnitval == "Tons"){
				$AmountUnit = "tons";
			} 
			if ($AmountUnitval == "Pounds"){
				$AmountUnit = "lbs";
			} 
			if ($AmountUnitval == "Kilograms"){
				$AmountUnit = "kg";
			} 
			
			if ($CostOrRevenuePerUnit == "Cost Per Unit"){
				$head_str =	"COST PER UNIT ($/" . $AmountUnit . ")";
				$head_str1 = "TOTAL COST";
				$CostOrRevenuePerUnit = "COST";
			}
			if ($CostOrRevenuePerUnit == "Revenue Per Unit"){
				$head_str =	"REVENUE PER UNIT ($/" . $AmountUnit . ")";
				$head_str1 = "TOTAL REVENUE";
				$CostOrRevenuePerUnit = "REVENUE";
			}			
		}
	}

	if ($WeightorNumberofPulls == "Number of Pulls"){
		if ($display_header2 == "n"){
			$display_header2 = "y";
			
			$AmountUnit = ""; 
			$head_str = ""; $head_str1 = "";
			
			$head_str =	"COST PER UNIT ($/pull)";
			$head_str1 = "TOTAL COST";
			$CostOrRevenuePerUnit = "COST";
			if ($CostOrRevenuePerUnit == "Cost Per Unit"){
				$head_str =	"COST PER UNIT ($/pull)";
				$head_str1 = "TOTAL COST";
				$CostOrRevenuePerUnit = "COST";
			}
			if ($CostOrRevenuePerUnit == "Revenue Per Unit"){
				$head_str =	"REVENUE PER UNIT ($/pull)";
				$head_str1 = "TOTAL REVENUE";
				$CostOrRevenuePerUnit = "REVENUE";
			}
		}
	}	*/
?>	 	

	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="left">
			<? echo $box_desc; ?>
		</td>

		<? if ($dt_view_row["CostOrRevenuePerUnit"] == "Cost Per Unit" || $dt_view_row["CostOrRevenuePerPull"] == "Cost Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Cost Per Item") { ?>		
			<td height="13" class="style1" align="right">
				<? echo "$" . $dt_view_row["value_each"]; ?>
				<?  if ($WeightorNumberofPulls == "By Number of Pulls") { ?>
					<? 
						echo "/Pull";
					}else{
						if ($dt_view_row["AmountUnit"] != ""){ echo "/" . $dt_view_row["AmountUnit"]; } 
					}
				?>
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">&nbsp;
				
			</td>
		<? }  ?>		
		<? if ($dt_view_row["CostOrRevenuePerUnit"] == "Revenue Per Unit"  || $dt_view_row["CostOrRevenuePerPull"] == "Revenue Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Revenue Per Item") { ?>		
			<td height="13" class="style1" align="right">
				<? echo "$" . $dt_view_row["value_each"]; ?>
				<? if ($dt_view_row["AmountUnit"] != ""){ echo "/" . $dt_view_row["AmountUnit"]; } ?>
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">&nbsp;
				
			</td>
		<? }  ?>		
		
		<td height="13" class="style1" align="right">
			<? echo $dt_view_row["unit_count"]; ?>
		</td>
		
		<td height="13" style="width: 94px" class="style1" align="center">
			<? 
			/***************************/
			/**Some changes done to show weight_unit gallon if required
			/**Done by Nayan
			/**Date : 12 Feb 2021
			/***************************/
			if($dt_view_row["AmountUnitEquivalent"] == 'Gallon'){
				$weightUnit = $dt_view_row["AmountUnitEquivalent"];
			}else{
				$weightUnit = $dt_view_row["weight_unit"];
			}

			?>
			<? echo number_format($dt_view_row["weight"], 2). " " . $weightUnit;?>
		</td>

		<td height="13" style="width: 94px" class="style1" align="center">
			<? 
			if($dt_view_row["AmountUnitEquivalent"] == 'Gallon'){
				$weightUnit = $dt_view_row["weight_unit"];
			}else{
				$weightUnit = $dt_view_row["weight_unit"];
			}

			if ($dt_view_row["AmountUnitEquivalent"] == 'Gallon'){
				//echo "Chk " . number_format($dt_view_row["weight"]). " | " . $dt_view_row["unit_count"] . " | " . $dt_view_row["poundpergallon_value"] . $weightUnit;
				if ($dt_view_row["unit_count"] > 0){
					echo number_format(($dt_view_row["weight"] * $dt_view_row["unit_count"]) * $dt_view_row["poundpergallon_value"], 2). " " . $weightUnit;
				}else{
					echo number_format($dt_view_row["weight"] * $dt_view_row["poundpergallon_value"], 2). " " . $weightUnit;
				}					
			}else if ($dt_view_row["unit_count"] > 0){
				echo number_format($dt_view_row["weight"] * $dt_view_row["unit_count"], 2). " " . $weightUnit;
			}else{
				echo number_format($dt_view_row["weight"], 2). " " . $weightUnit;
			}
				?>
		</td>
		<? if ($dt_view_row["CostOrRevenuePerUnit"] == "Cost Per Unit" || $dt_view_row["CostOrRevenuePerPull"] == "Cost Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Cost Per Item") { 
			$tot_cost = $tot_cost - $dt_view_row["total_value"]; 
		?>		
			<td height="13" class="style1" align="right">
				<Font Face='arial' size='1'>
				<? echo $dt_view_row["total_value"]; ?>
			</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">&nbsp;
				
			</td>
		<? }  ?>		
		
		<? if ($dt_view_row["CostOrRevenuePerUnit"] == "Revenue Per Unit"  || $dt_view_row["CostOrRevenuePerPull"] == "Revenue Per Pull" || $dt_view_row["CostOrRevenuePerItem"] == "Revenue Per Item") {
			  $tot_revenue = $tot_revenue + $dt_view_row["total_value"];
		?>		
		<td height="13" class="style1" align="right">
			<Font Face='arial' size='1'>
			$<? echo $dt_view_row["total_value"]; ?>
		</td>
		<? } else{ ?>		
			<td height="13" class="style1" align="right">&nbsp;
				
			</td>
		<? }  ?>		
		<td align="center" height="13" style="width: 100px" class="style1">
			<font size="1" Face="arial"><? echo $Outlet; ?> </font>
		</td>
		<td align="center" height="13" style="width: 100px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_commodity; ?> </font>
		</td>
		<td align="center" height="13" style="width: 100px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_rate_cost; ?> </font>
		</td>
		<td align="center" height="13" style="width: 100px" class="style1">
			<font size="1" Face="arial"><? echo $base_line_rate_revenue; ?> </font>
		</td>

	</tr>
	
	
<? } ?>	


<?
//$dt_view_qry = "SELECT * from water_boxes_report_data WHERE trans_rec_id = '" . $rec_id . "' LIMIT 0,1";
//$dt_view_res = db_query($dt_view_qry,db() );
//while ($dt_view_row = array_shift($dt_view_res)) {
?>	
	<tr>
		<td colspan="9" align="left" bgColor='#e4e4e4'>
			<font size="1">Total Material Revenue ($)</font>
		</td>
		<td align="right" bgColor='#e4e4e4'>
			<font size="1">$<? echo $tot_revenue;?></font>  
		</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="9" align="left" bgColor='#e4e4e4'>
			<font size="1">Total Material Cost ($)</font>
		</td>
		<td align="right" bgColor='#e4e4e4'>
			<font size="1">$<? echo $tot_cost;?></font>  
		</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="9" align="left" bgColor='#e4e4e4'>
			<font size="1">Total Material Profit ($)</font>
		</td>
		<td align="right" bgColor='#e4e4e4'>
			<font size="1">$<?
			$total_value_tot = $tot_revenue + $tot_cost;
			echo $tot_revenue + $tot_cost;?></font> 
		</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
		<td align="right" bgColor='#e4e4e4'>&nbsp;</td>
	</tr>

	<tr bgColor="#e4e4e4"><td colspan="12">
		<input type="checkbox" name="chkdoubt" id="chkdoubt" value="1" <? echo $chkdoubt_val;?> disabled /><font size="1">I have a Question
		&nbsp;
		<? echo $doubt; ?></font>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td colspan="12" height="13" class="style1" align="center">&nbsp;
			
		</td>
	</tr>	
	
	
<Font size='1'>
<?
$dt_view_tran_qry = "SELECT * from water_transaction WHERE id = " . $rec_id;
$dt_view_tran = db_query($dt_view_tran_qry,db() );
$dt_view_tran_row = array_shift($dt_view_tran);
?>

	<tr bgColor="#e4e4e4">
		<td bgColor="#c0cdda" colspan="5" height="13" class="style1" align="center">
			ADDITIONAL FEES & CREDITS
		</td>
		<td bgColor="#c0cdda" height="13" class="style1" align="center">
			COST ($) PER UNIT
		</td>
		<td bgColor="#c0cdda" height="13" class="style1" align="center">
			OCCURRENCES
		</td>
		<td bgColor="#c0cdda" height="13" class="style1" align="center">
			TOTAL COST ($)
		</td>
		<td bgColor="#c0cdda" height="13" class="style1" align="center">
			SAVINGS CALCULATION CATEGORY
		</td>
		<td bgColor="#c0cdda" height="13" class="style1" align="center">
			REMARK
		</td>
		
	</tr>

		<?	
			$tot_add_fee = 0;
			$query = db_query("SELECT *, water_trans_addfees.savings_calculation_category_id as add_fee_cal_cat_id FROM water_trans_addfees left join water_additional_fees 
			on water_additional_fees.id = water_trans_addfees.add_fees_id where trans_id = " . $rec_id, db() );
			while ($rowsel_getdata = array_shift($query)){
				if ($rowsel_getdata["active_flg"] == 1 || $rowsel_getdata["add_fees_id"] == 0) {
					
					$savings_calculation_category = "";
					//$sql_data = "SELECT * FROM water_savings_calculation_category where savings_calculation_category_id = ? and savings_calculation_category_flg = ?";
					$sql_data = "SELECT savings_calculation_category_id, savings_calculation_category, 1 as savings_calculation_category_flg from water_savings_calculation_category
						union
						SELECT commodity_id as savings_calculation_category_id, commodity as savings_calculation_category , 2 as savings_calculation_category_flg
						from base_line_commodity_master where company_id = ?";
					$result_data = db_query($sql_data, array("i"), array($_REQUEST["ID"]));
					while ($myrowsel_data = array_shift($result_data)) 
					{
						if (($myrowsel_data["savings_calculation_category_id"] == $rowsel_getdata["add_fee_cal_cat_id"]) && ($myrowsel_data["savings_calculation_category_flg"] == $rowsel_getdata["savings_calculation_category_flg"]))
						{
							$savings_calculation_category = $myrowsel_data["savings_calculation_category"];
						}
					}
			?>
			<tr bgColor="#e4e4e4">
				<td colspan="5" height="13" class="style1" align="center">
				<?
					if ($rowsel_getdata["add_fees_id"] == 0) {
						echo "Other fees";
					}else {
						echo $rowsel_getdata["additional_fees_display"];
					}					
				?>	
				</td>
				<td height="13" class="style1" align="right">
					<? if ($rowsel_getdata["add_fees"] > 0) { echo '$' . $rowsel_getdata["add_fees"]; } ?>		
				</td>
				<td height="13" class="style1" align="center">
					<? echo $rowsel_getdata["add_fees_occurance"]; ?>	
				</td>
				<td height="13" class="style1" align="center">
					<? echo ($rowsel_getdata["add_fees"] * $rowsel_getdata["add_fees_occurance"]); ?>	
				</td>

				<td height="13" class="style1" align="center" >
					<font size="1"><? echo $savings_calculation_category; ?></font>
				</td>

				<td height="13" class="style1" align="center">
					<font size="1"><? echo $rowsel_getdata["add_fee_remark"]; ?></font>
				</td>
			</tr>
			<?
			
					if ($dt_view_tran_row["saved_ocr_val_flg"] == 1){
						if ($rowsel_getdata["fee_total_val"] == 0){
							$tot_add_fee = $tot_add_fee + ($rowsel_getdata["add_fees"] * $rowsel_getdata["add_fees_occurance"]);
						}else{
							$tot_add_fee = $tot_add_fee + ($rowsel_getdata["fee_total_val"]);	
						}
					}else{
						if ($rowsel_getdata["add_fees_occurance"] > 0)
						{
							$tot_add_fee = $tot_add_fee + ($rowsel_getdata["add_fees"] * $rowsel_getdata["add_fees_occurance"]);
							
						}else{
							$tot_add_fee = $tot_add_fee + ($rowsel_getdata["add_fees"]);
							
						}
					}
			
				}
			}	
		?>

	<tr bgColor="#e4e4e4">
		<td colspan="10" height="13" class="style1" align="center">&nbsp;
			
		</td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td colspan="6" height="13" class="style1" align="center">
			<b>Final Total ($)</b>
		</td>
		<td height="13" class="style1" align="left">&nbsp;
			
		</td>
		<td height="13" class="style1" align="right">
			<b>$<? 
			$total_charges = ($dt_view_tran_row["total_due_to_ucb"] + $total_value_tot - $tot_add_fee);
			echo number_format($total_charges,2); ?></b>
		</td>
		<td height="13" class="style1" align="left" colspan="2">&nbsp;
			
		</td>
	</tr>	
		
	<tr bgColor="#e4e4e4">
		<td colspan="10" height="13" class="style1" align="center">&nbsp;
			
		</td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td colspan="5" height="13" class="style1" align="left">
		Notes: </td>
		<td height="13" class="style1" align="left" colspan="5">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["report_notes"]; ?></font></p></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td colspan="5" height="13" class="style1" align="left">
		Entered By: </td>
		<td height="13" class="style1" align="left" colspan="5">
		<p align="left"><font size="1"><? echo $dt_view_tran_row["repor_entry_emp"]; ?></font></td>
		</tr></font></font></font>
	<? if ($dt_view_tran_row["reportEntryEditedEmp"] != "") {?>	
		<tr bgColor="#e4e4e4">
			<td colspan="5" height="13" class="style1" align="left">
			Edited By: </td>
			<td height="13" class="style1" align="left" colspan="5">
			<p align="left"><font size="1"><? echo $dt_view_tran_row["reportEntryEditedEmp"]; ?></font></td>
		</tr>
	<? }?>	
		
	<tr bgColor="#e4e4e4">
		<td colspan="5" height="13" class="style1" align="left">
		Entry Date: </td>
		<td height="13" class="style1" align="left" colspan="5">
			<p align="left"><font size="1"><? echo $dt_view_tran_row["transaction_date"]; ?></font>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="left" colspan="5" width="235px">
		Make or receive payment?</td>
		<td height="13" class="style1" align="left" colspan="5">
			<font size='1' Face="arial">
				<?php 
					if($dt_view_tran_row["make_receive_payment"]=="1"){ 
						echo 'Yes'; 
					} 
					else{
						echo 'No'; 
					}
				?>
			</font>
		</td>
	</tr>
	<?
	if($dt_view_tran_row["make_receive_payment"]=="1")
	{
	?>
		<tr>
			<td height="13" class="style1" align="left" colspan="11">
				<table width="100%" class="vendor_payment_css" cellpadding="0" cellspacing="1">
				<tr bgColor="#e4e4e4">
					<th colspan="2" class="first" align="center" bgcolor="#c0cdda" style="height: 22px;"><font size="2">Vendor Payment Report</font></th>	
				</tr>	
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right" width="285px">Made Payment or Received Payment?</td>
					<td>
						<font size="1">
						<?php 
							if($dt_view_tran_row["made_payment"]=="1"){ 
								echo 'Yes'; 
							} 
							else{
								echo 'No'; 
							}
						?>
						</font>
					</td>	
				</tr>
				<?
				$finalTotal = ($tot_revenue - $tot_cost) - $tot_add_fees;
				if($finalTotal > 0 ){
				?>
					<tr bgColor="#e4e4e4">
						<td class="style1" align="right">Vendor Credit:</td>
						<td><font size="1"><? echo $dt_view_tran_row["vendor_credit"]; ?></font></td>
					</tr>
				<? } ?>
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right">Paid/Received by:</td>
					<td><font size="1"><? echo $dt_view_tran_row["paid_by"]; ?></font></td>	
				</tr>
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right">Paid/Received date:</td>
					<td><font size="1"><? echo $dt_view_tran_row["paid_date"]; ?></font></td>	
				</tr>
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right">Payment Method:</td>
					<td><font size="1"><? echo $dt_view_tran_row["payment_method"]; ?></font></td>	
				</tr>
				
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right">Payment proof file:</td>
					<td id="proof_filetd">
						<? if ($dt_view_tran_row["payment_proof_file"] != "") {
								$tmppos_1 = strpos($dt_view_tran_row["payment_proof_file"], "|");
								if ($tmppos_1 != false)
								{ 	
									$elements = explode("|", $dt_view_tran_row["payment_proof_file"]);
									for ($i = 0; $i < count($elements); $i++) {	?>			
										<a target="_blank" href='water_payment_proof/<? echo $elements[$i]; ?>'><font size="1">View</font></a>
										<br>
									<?}
								}else {		
							?>
								<a target="_blank" href='water_payment_proof/<? echo $dt_view_tran_row["payment_proof_file"]; ?>'><font size="1">View Attachments</font></a>
								<br>
							<? 
								}
							}
							?>
					</td>	
				</tr>
				<tr bgColor="#e4e4e4">
					<td class="style1" align="right">Log Notes:</td>
					<td><input type="text" name="log_notes" id="log_notes" value="<? echo $dt_view_tran_row["vendor_payment_log_notes"]; ?>"></td>	
				</tr>
				</table>
			</td>
		</tr>
	<?
	}
	?>
	<tr bgColor="#e4e4e4">
		<?
		if($dt_view_tran_row["double_chk_confirm"]==1){
		?>
			<td colspan="5" height="13" class="style1" align="left">
					Entry double-checked and confirmed by:
			</td>
			<td colspan="5" height="13" class="style1" align="left">
				<?php echo $dt_view_tran_row["confirmed_user"]."<br>".$dt_view_tran_row["confirmed_date"]; ?>
			</td>
		<?
		}
		else
		{
		?>
		<td colspan="10" height="13" class="style1" align="left">
			Entry double-checked and confirmed:
			<a href="viewCompany_func_water-mysqli.php?ID=<?=$_REQUEST['ID']?>&show=watertransactions&warehouse_id=<? echo $id; ?>&b2bid=<? echo $b2bid; ?>&rec_id=<? echo $rec_id; ?>&rec_type=<? echo $rec_type; ?>&proc=View&searchcrit=&entry_confirmed=yes&display=water_sort&sort_edit=false"><input type="button" name="confirmed_btn" value="Confirm"></a>
		</td>
		<?
			}
		?>
			
		</td>
	</tr>
<?
												  
//} 

?>	
	
</table>	
<? } 

}
if(isset($_REQUEST["entry_confirmed"]) && $_REQUEST["entry_confirmed"]=="yes")
{
	$rec_id=$_REQUEST["rec_id"];
	$user = $_COOKIE['userinitials'];
	$conf_date=date("Y-m-d H:i:s");
	$sql="update water_transaction set double_chk_confirm=1, confirmed_user='$user', confirmed_date='$conf_date' where id='".$rec_id."'";
	$result=db_query($sql,db());
	//
	echo "<script type=\"text/javascript\">";	
	echo "window.location.href=\"viewCompany_func_water-mysqli.php?ID=" .$_REQUEST['ID']. "&show=watertransactions&warehouse_id=" .$id. "&b2bid=" .$b2bid. "&rec_id=".$rec_id. "&rec_type=" .$rec_type. "&proc=View&searchcrit=&display=water_sort&sort_edit=false\";";	
	echo "</script>";	echo "<noscript>";	
	echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany_func_water-mysqli.php?ID=" .$_REQUEST['ID']. "&show=watertransactions&warehouse_id=" .$id. "&b2bid=" .$b2bid. "&rec_id=" .$rec_id. "&rec_type=" .$rec_type. "&proc=View&searchcrit=&display=water_sort&sort_edit=false\" />";	echo "</noscript>";exit;
}
?>

</font></font>

</font></font>

<br><br><br>
			<input type="text" name="tmp" id="water_trans_tmpid" value="" style="width:0px;" autofocus>

<script language="javascript">
	var url_string = window.location.href
	var url = new URL(url_string);
	var urlparam1 = url.searchParams.get("display");	
	if (urlparam1 == 'water_sort')
	{
		document.getElementById("water_trans_tmpid").focus(); 
	}
</script>