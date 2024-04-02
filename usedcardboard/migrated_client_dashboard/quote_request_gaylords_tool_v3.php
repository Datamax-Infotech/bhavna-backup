<?php
/*
File Name: quote_request_gaylords_tool_v3.php
Page created By: Prasad Brid
Page created On: 06-03-2023
Last Modified On: 
Last Modified By: Prasad Brid
Change History:
Date           		By         	   	Description
=============================================================================================================
02-June-22 		Prasad Brid		 
=============================================================================================================
*/

session_start();
$sales_rep_login = "no";
$repchk = 0;
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
		$repchk = 1;
	}
} else {
	require("inc/header_session_client.php");
}

$repchk_from_setup = 0;
if (isset($_REQUEST["repchk_from_setup"])) {
	if ($_REQUEST["repchk_from_setup"] == "yes") {
		$repchk_from_setup = 1;
	}
}

require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");
?>
<style>
	.nowraptxt {
		white-space: nowrap;
	}

	th {
		position: -webkit-sticky;
		position: sticky;
		top: 0;
		z-index: 2;
	}

	a {
		font-size: 11px;
		font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	}
</style>

<link rel="stylesheet" type="text/css" href="css/newstylechange.css" />
<script type="text/javascript" src="wz_tooltip_new.js"></script>

<?php

$MGArray_new = array(); 

$ship_from2_tmp = "";
$after_po_val = 0;
$actual_po = 0;
$boxes_per_trailer_tmp = "";
$boxsize = array();
	if ($_REQUEST["othertha_gaylord"] == "yes") { ?>

	<?php
	
	
	$gr_avl_load_cost = 0;
	$gr_all_load_cost = 0;
	$gr_total_no_of_loads = 0;
	$gr_no_of_loads = 0 ;
	//Match inventory non Gaylord
	function showinventory_fordashboard_non_gaylord(int $g_timing, int $sort_g_tool, int $sort_g_view): void
	{
	?>
		<script>
			function displayboxdata_invnew(colid, sortflg, box_type_cnt) {
				document.getElementById("btype" + box_type_cnt).innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
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

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//alert(xmlhttp.responseText);
						document.getElementById("btype" + box_type_cnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "dashboard_inv_sort.php?colid=" + colid + "&sortflg=" + sortflg + "&sort_g_view=" + sort_g_view + "&sort_g_tool=" + sort_g_tool + "&g_timing=" + g_timing + "&box_type_cnt=" + box_type_cnt + "&search_tag=" + values, true);
				xmlhttp.send();
			}

			function display_preoder_sel(tmpcnt, box_id, warehouse_fullness_flg = 0) {
				if (warehouse_fullness_flg == 0) {
					if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
						document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
					} else {
						document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
					}

					document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
				} else {
					document.getElementById("div_warehouse_fullness" + tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";
				}

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (warehouse_fullness_flg == 0) {
							document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
						} else {
							document.getElementById("div_warehouse_fullness" + tmpcnt).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "dashboard_inv_qtyavail.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt, true);
				xmlhttp.send();
			}
		</script>
		<style>
			.popup_qty {
				text-decoration: underline;
				cursor: pointer;
			}

			#loadingDiv {
				position: absolute;
				;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: #000;
			}
		</style>
		<?php
		if (isset($_REQUEST["g_timing"])) {
			$g_timing = $_REQUEST["g_timing"];
		}
		if (isset($_REQUEST["sort_g_tool"])) {
			$sort_g_tool = $_REQUEST["sort_g_tool"];
		}
		if (isset($_REQUEST["sort_g_view"])) {
			$sort_g_view = $_REQUEST["sort_g_view"];
		}

		//Tag filter
		$filter_tag = "";
		if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] != "")) {
			// Retrieving each selected option 
			$total_tag = count($_REQUEST["search_tag"]);
			if ($total_tag >= 1) {
				$search_tag_val = "";
				foreach ($_REQUEST["search_tag"] as $tag_val) {
					$search_tag_val .= " tag like '%$tag_val%' or ";
				}
				$search_tags = rtrim($search_tag_val, "or ");

				$filter_tag = " and (" . $search_tags . ")";
			}
		}

		if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] == "")) {
			$search_tags = "";
			$filter_tag = "";
		}

		if (isset($_REQUEST["stag"]) && ($_REQUEST["stag"] != "")) {
			$stag_arr = explode(",", $_REQUEST["stag"]);
			$total_tag = count($stag_arr);
			if ($total_tag >= 1) {
				$search_tag_val = "";
				foreach ($stag_arr as $tag_val) {
					$search_tag_val .= " ( tag = $tag_val or ";
					$search_tag_val .= " tag like '%,$tag_val' or ";
					$search_tag_val .= " tag like '%,$tag_val,%' or ";
					$search_tag_val .= " tag like '$tag_val,%') or ";
				}
				$search_tags = rtrim($search_tag_val, "or ");

				$filter_tag = " and (" . $search_tags . ")";
			}
		}


		?>
		<script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
		<script language="JavaScript" SRC="inc/general.js"></script>
		<script language="JavaScript">
			document.write(getCalendarStyles());
		</script>
		<script language="JavaScript">
			var cal1xx = new CalendarPopup("listdiv");
			cal1xx.showNavigationDropdowns();
		</script>
		<script>
			function add_product_fun() {
				var cnt = document.getElementById("prod_cnt").value;
				var chkcondition = document.getElementById("filter_andorcondition" + cnt).value;
				var filtercol = document.getElementById("filter_column" + cnt).value;
				if (filtercol != "-" && chkcondition == "") {
					alert("Please select Condition");
					return false;
				}
				cnt = Number(cnt) + 1;

				var sstr = "";
				sstr = "<table style='font-size:8pt;' id='inv_child_div" + cnt + "'><tr><td>Select table column:</td><td><select style='font-size:8pt;' name='filter_column[]' id='filter_column" + cnt + "' onChange='showfilter_option(" + cnt + ")'><option value=''>Select Option</option><option value='Box Type'>Box Type</option><option value='State'>Location State</option><option value='No. of Wall'>No. of Wall</option><option value='ucbwarehouse'>Warehouse</option><option value='Actual'>Actual</option><option value='After PO'>After PO</option><option value='Last Month Quantity'>Last Month Quantity</option><option value='Availability'>Availability</option><option value='Vendor'>Vendor</option><option value='Ship From'>Ship From</option><option value='Length'>Box Length</option><option value='Width'>Box Width</option><option value='Height'>Box Height</option><option value='Description'>Description</option><option value='SKU'>SKU</option><option value='Per Pallet'>Per Pallet</option><option value='Per Trailer'>Per Trailer</option><option value='Min FOB'>Min FOB</option><option value='Cost'>Cost</option></select></td><td><select style='font-size:8pt;' id='filter_compare_condition" + cnt + "' name='filter_compare_condition[]'><option value='='>=</option><option value='>'>></option><option value='<'><</option></select></td><td><div id='filter_sub_option" + cnt + "'><input style='font-size:8pt;' type='input' id='filter_inp' value='' /></div></td><td><select style='font-size:8pt;' id='filter_andorcondition" + cnt + "' name='filter_andorcondition[]'><option value=''>Select</option><option value='And'>And</option><option value='Or'>Or</option></select><input style='font-size:8pt;' type='button' name='btn_remove' value='X' onclick='remove_product_fun(" + cnt + ")'></td></tr></table></div></div>";

				var divctl = document.getElementById("inv_main_div");
				divctl.insertAdjacentHTML('beforeend', sstr);

				document.getElementById("prod_cnt").value = cnt;
			}

			function remove_product_fun(cnt) {

				document.getElementById("inv_child_div" + cnt).innerHTML = "";

			}


			function showfilter_option(cnt) {
				// 
				var str = document.getElementById("filter_column" + cnt).value;

				if (str.length == 0) {
					//document.getElementById("filter_sub_option").innerHTML = "";
					return;
				} else {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("filter_sub_option" + cnt).innerHTML = this.responseText;
						}
					};
					xmlhttp.open("POST", "getfilter_sub_options.php?op=" + str + "&cnt=" + cnt, true);
					xmlhttp.send();
				}
			}
		</script>
		<script src="jQuery/jquery-2.1.3.min.js" type="text/javascript"></script>
		<script>
			function dynamic_Select(sort) {
				var skillsSelect = document.getElementById('dropdown');
				var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
				document.getElementById("temp").value = selectedText;
			}

			function displaynonucbgaylord(colid, sortflg) {
				document.getElementById("div_noninv_gaylord").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_gaylord").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbgaylord.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displayurgentbox(colid, sortflg, cnt) {
				document.getElementById("ug_box").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//alert(colid);
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("ug_box").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displayurgentbox.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displayucbinv(colid, sortflg) {
				document.getElementById("div_ucbinv").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_ucbinv").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displayucbinv.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbshipping(colid, sortflg) {
				document.getElementById("div_noninv_shipping").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_shipping").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbshipping.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbsupersack(colid, sortflg) {
				document.getElementById("div_noninv_supersack").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_supersack").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbsupersack.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbdrumBarrel(colid, sortflg) {
				document.getElementById("div_noninv_drumBarrel").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_drumBarrel").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbdrumBarrel.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbpallets(colid, sortflg) {
				document.getElementById("div_noninv_pallets").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_pallets").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbpallets.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function sort_Select(warehouseid) {
				var Selectval = document.getElementById('sort_by_order');
				var order_type = Selectval.options[Selectval.selectedIndex].text;


				if (document.getElementById("dropdown").value == "") {
					alert("Please Select the field.");
				} else {
					document.getElementById("tempval_focus").focus();

					document.getElementById("tempval").style.display = "none";
					document.getElementById("tempval1").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							if (order_type != "") {
								document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
							}
						}
					}

					xmlhttp.open("GET", "pre_order_sort.php?warehouseid=" + warehouseid + "&selectedgrpid_inedit=" + document.getElementById("temp").value + "&sort_order=" + order_type, true);
					xmlhttp.send();
				}
			}

			function f_getPosition(e_elemRef, s_coord) {
				var n_pos = 0,
					n_offset,
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

			function displayafterpo(boxid) {
				document.getElementById("light").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("after_pos" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 250;
				n_top = n_top - 100;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_showafterpo.php?id=" + boxid, true);
				xmlhttp.send();
			}

			function displaymap() {
				document.getElementById("light").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("show_map1");
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 50;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_showmap.php", true);
				xmlhttp.send();
			}


			function displayflyer(boxid, flyernm) {
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" + flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 'px';

			}

			function displayflyer_main(boxid, flyernm) {
				document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" + flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

			}

			function displayactualpallet(boxid) {
				document.getElementById("light").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("actual_pos" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
				xmlhttp.send();
			}

			function displayboxdata(boxid) {
				document.getElementById("light").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function displayboxdata_main(boxid) {
				document.getElementById("light").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" + xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function display_orders_data(tmpcnt, box_id, wid) {
				if (document.getElementById('inventory_preord_top_u' + tmpcnt).style.display == 'table-row') {
					document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'none';
				} else {
					document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'table-row';
				}

				document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML = "<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt, true);
				xmlhttp.send();
			}


			function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						alert("Data saved.");
						document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
				opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

				xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" + warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
				xmlhttp.send();
			}


			function new_inventory_filter() {
				document.getElementById("new_inv").innerHTML = "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//
				var sort_g_view = document.getElementById("sort_g_view").value;
				var sort_g_tool = document.getElementById("sort_g_tool").value;
				var g_timing = document.getElementById("g_timing").value;
				//
				var fld = document.getElementById('search_tag');
				var values = [];
				if (fld) {
					for (var i = 0; i < fld.options.length; i++) {
						if (fld.options[i].selected) {
							values.push(fld.options[i].value);
						}
					}
				}
				//

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//alert(xmlhttp.responseText);
						document.getElementById("new_inv").innerHTML = xmlhttp.responseText;
					}
				}

				//"&search_tag=" + values
				xmlhttp.open("GET", "display_filter_inventory.php?sort_g_view=" + sort_g_view + "&sort_g_tool=" + sort_g_tool + "&g_timing=" + g_timing + "&search_tag=" + values, true);
				xmlhttp.send();
			}
		</script>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>


		<link rel="stylesheet" type="text/css" href="css/newstylechange.css" />

		<div id="new_inv">
			<?php
			//$main_box_types=array("Gaylord","Shipping Boxes", "Supersacks", "Pallets", "Drums/Barrels/IBCs" );
			if (!isset($_REQUEST["sort"])) {
				$gy = array();
				$sb = array();
				$pal = array();
				$sup = array();
				$dbi = array();
				$recy = array();
				$_SESSION['sortarraygy'] = "";
				$_SESSION['sortarraysb'] = "";
				$_SESSION['sortarraysup'] = "";
				$_SESSION['sortarraydbi'] = "";
				$_SESSION['sortarraypal'] = "";
				$_SESSION['sortarrayrecy'] = "";
				//
				$x = 0;
				$newflg = "no";
				$preordercnt = 1;
				$box_type_str_arr = array("'Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'PalletsUCB','PalletsnonUCB'", "'SupersackUCB','SupersacknonUCB','Supersacks'", "'DrumBarrelUCB','DrumBarrelnonUCB'");
				$box_type_cnt = 0;
				$filter_subtype = "";
				foreach ($box_type_str_arr as $box_type_str_arr_tmp) {
					//
					$box_type_cnt = $box_type_cnt + 1;

					if ($box_type_cnt == 1) {
						$box_type = "Shipping Boxes";
					}
					if ($box_type_cnt == 2) {
						$box_type = "Pallets";
					}
					if ($box_type_cnt == 3) {
						$box_type = "Supersacks";
					}
					if ($box_type_cnt == 4) {
						$box_type = "Drums/Barrels/IBCs";
					}
					//
					
					$box_query = "";
					if ($sort_g_tool == 1) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' " . $filter_tag . " " . $filter_subtype . " ORDER BY inventory.availability DESC";
					}
					if ($sort_g_tool == 2) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' " . $filter_tag . " " . $filter_subtype . " ORDER BY inventory.availability DESC";
					}
					//
					//echo $box_query ."<br>";
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
					?>

					<?php
						$inv_id_list = "";
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);

							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$boxgoodvalue = 0;
							$actual_qty_calculated = "";
							$box_wall = "";
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue, actual_qty_calculated from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
								$boxgoodvalue = $sku_val['boxgoodvalue'];
								$actual_qty_calculated = $sku_val['actual_qty_calculated'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								$next_load_available_date = "";

								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, next_load_available_date, actual_qty_calculated from loop_boxes where b2b_id=" . $inv["I"];
								$dt_view = db_query($qry_loc);
								$territory = "";
								$territory_sort = 0;
								$vendor_b2b_rescue_id = "";
								$shipfrom_city = "";
								$shipfrom_zip = "";
								$shipfrom_state = "";
								while ($loc_res = array_shift($dt_view)) {
									$territory = "";
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$next_load_available_date = $loc_res["next_load_available_date"];
									$vendor_b2b_rescue_id = "";
									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
										//
										$territory = $loc_row["territory"];
										if ($territory == "Canada East") {
											$territory_sort = 1;
										}
										if ($territory == "East") {
											$territory_sort = 2;
										}
										if ($territory == "South") {
											$territory_sort = 3;
										}
										if ($territory == "Midwest") {
											$territory_sort = 4;
										}
										if ($territory == "North Central") {
											$territory_sort = 5;
										}
										if ($territory == "South Central") {
											$territory_sort = 6;
										}
										if ($territory == "Canada West") {
											$territory_sort = 7;
										}
										if ($territory == "Pacific Northwest") {
											$territory_sort = 8;
										}
										if ($territory == "West") {
											$territory_sort = 9;
										}
										if ($territory == "Canada") {
											$territory_sort = 10;
										}
										if ($territory == "Mexico") {
											$territory_sort = 11;
										}
										//
									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
										//
										//
										//Find territory
										//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico

										$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
										$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
										$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
										$midwest = array('MI', 'OH', 'IN', 'KY');
										$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
										$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
										$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
										$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
										$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
										$canada = array();
										$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
										$territory_sort = 99;
										$territory = "";
										if (in_array($shipfrom_state, $canada_east, TRUE)) {
											$territory = "Canada East";
											$territory_sort = 1;
										} elseif (in_array($shipfrom_state, $east, TRUE)) {
											$territory = "East";
											$territory_sort = 2;
										} elseif (in_array($shipfrom_state, $south, TRUE)) {
											$territory = "South";
											$territory_sort = 3;
										} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
											$territory = "Midwest";
											$territory_sort = 4;
										} else if (in_array($shipfrom_state, $north_central, TRUE)) {
											$territory = "North Central";
											$territory_sort = 5;
										} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
											$territory = "South Central";
											$territory_sort = 6;
										} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
											$territory = "Canada West";
											$territory_sort = 7;
										} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
											$territory = "Pacific Northwest";
											$territory_sort = 8;
										} elseif (in_array($shipfrom_state, $west, TRUE)) {
											$territory = "West";
											$territory_sort = 9;
										} elseif (in_array($shipfrom_state, $canada, TRUE)) {
											$territory = "Canada";
											$territory_sort = 10;
										} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
											$territory = "Mexico";
											$territory_sort = 11;
										}
									}
								}
								$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
								$ship_from2 = $shipfrom_state;

								//
								$after_po_val_tmp = 0;
								$after_po_val = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									$after_po_val = $after_po_val_tmp;
								}
								//$after_po_val = $actual_qty_calculated;

								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}

								if ($to_show_rec == "y") {
									//account owner
									$ownername = "";
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									$supplier_id = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											//
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}

									//
									$lead_time = "";
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"];
									}

									$estimated_next_load = "";
									$b2bstatuscolor = "";
									$expected_loads_per_mo = "";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										//$next_load_available_date = $b2b_inv_row["next_load_available_date"];
										//echo "next_load_available_date - " . $inv["I"] . " " . $next_load_available_date . " " . $inv["lead_time"] . "<br>";

										//
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays = round($datediff / (60 * 60 * 24));
										//echo $no_of_loaddays;
										if ($no_of_loaddays < $lead_time) {
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											} else {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
										} else {
											$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}
											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
											} else {
												// logic changed by Zac
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $inv["expected_loads_per_mo"];
										}
									}
									//							
									$lead_time = "";
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}

									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . $ownername . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . $territory . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									if ($box_type_cnt == 1) {
										$sb[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 2) {
										$pal[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo,  'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 3) {
										$sup[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 4) {
										$dbi[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => $ownername, 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => $box_wall, 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'binv' => 'nonucb', 'territory_sort' => $territory_sort);
									}
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					} //End check num rows>0

					//Ucbowned
					$dt_view_qry = "";
					if ($sort_g_tool == 1) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
					}
					if ($sort_g_tool == 2) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
					}
					//echo $dt_view_qry;
					db_b2b();
					$dt_view_res = db_query($dt_view_qry);
					$tmpwarenm = "";
					$tmp_noofpallet = 0;
					$ware_house_boxdraw = "";
					while ($dt_view_row = array_shift($dt_view_res)) {
						$actual_po = 0;
						$after_po_val = 0;
						$b2bid_tmp = 0;
						$boxes_per_trailer_tmp = 0;
						$bpallet_qty_tmp = 0;
						$vendor_id = 0;
						$vendor_b2b_rescue_id = 0;
						$actual_qty_calculated = "";
						$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, expected_loads_per_mo, actual_qty_calculated from loop_boxes where id=" . $dt_view_row["trans_id"];
						$dt_view_loopbox = db_query($qry_loopbox);
						while ($rs_loopbox = array_shift($dt_view_loopbox)) {
							$b2bid_tmp = $rs_loopbox['b2b_id'];
							$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
							$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
							$vendor_id = $rs_loopbox['vendor'];
							$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
							$actual_qty_calculated = $rs_loopbox['actual_qty_calculated'];
						}


						$inv_availability = "";
						$distC = 0;
						$inv_notes = "";
						$inv_notes_dt = "";

						$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp . " " . $filter_tag . " " . $filter_subtype;
						db_b2b();
						$dt_view_inv_res = db_query($inv_qry);
						$dt_view_row_inv = array_shift($dt_view_inv_res);
						//while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
						$inv_notes = $dt_view_row_inv["notes"];
						$inv_notes_dt = $dt_view_row_inv["date"];
						$location_city = $dt_view_row_inv["location_city"];
						$location_state = $dt_view_row_inv["location_state"];
						$location_zip = $dt_view_row_inv["location_zip"];
						$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
						$vendor_id = $dt_view_row_inv["vendor"];
						$lead_time = "";
						if ($dt_view_row_inv["lead_time"] <= 1) {
							$lead_time = "Next Day";
						} else {
							$lead_time = $dt_view_row_inv["lead_time"] . " Days";
						}
						//
						$b2bstatus = $dt_view_row_inv['b2bstatus'];
						$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];
						$vender_name = "";
						$ownername = "";
						$supplier_id = "";
						//account owner
						if ($vendor_b2b_rescue > 0) {
							$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
							$query = db_query($q1);
							while ($fetch = array_shift($query)) {
								$supplier_id = $fetch["b2bid"];
								$vender_name = getnickname($fetch['company_name'], $fetch["b2bid"]);
								//
								$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
								db_b2b();
								$comres = db_query($comqry);
								while ($comrow = array_shift($comres)) {
									$ownername = $comrow["initials"];
								}
							}
						}

						$tmp_zipval = "";
						$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
						$zip_str = "";
						if ($tmppos_1 != false) {
							$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
							$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
						} else {
							$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
						}
						$locLat = 0;
						$locLong = 0;
						if ($dt_view_row_inv["location_zip"] != "") {
							db_b2b();
							$dt_view_res3 = db_query($zipStr);
							while ($ziploc = array_shift($dt_view_res3)) {
								$locLat = $ziploc["latitude"];

								$locLong = $ziploc["longitude"];
							}
						}
						//}
						$minfob = $dt_view_row["min_fob"];
						$b2bcost = $dt_view_row["b2b_cost"];
						$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
						$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {
							$lastmonth_val = $dt_view_row["lastmonthqty"];

							$reccnt = 0;
							if ($sales_order_qty > 0) {
								$reccnt = $sales_order_qty;
							}

							$preorder_txt = "";
							$preorder_txt2 = "";

							if ($reccnt > 0) {
								$preorder_txt = "<u>";
								$preorder_txt2 = "</u>";
							}

							if (($dt_view_row["actual"] >= $boxes_per_trailer_tmp) && ($boxes_per_trailer_tmp > 0)) {
								$bg = "yellow";
							}

							$pallet_val = 0;
							$pallet_val_afterpo = 0;
							$actual_po_tmp = $dt_view_row["actual"] - $sales_order_qty;

							if ($bpallet_qty_tmp > 0) {
								$pallet_val = number_format($dt_view_row["actual"] / $bpallet_qty_tmp, 1, '.', '');
								$pallet_val_afterpo = number_format($actual_po_tmp / $bpallet_qty_tmp, 1, '.', '');
							}

							$to_show_rec1 = "y";

							if ($to_show_rec1 == "y") {
								$pallet_space_per = "";

								if ($pallet_val > 0) {
									$tmppos_1 = strpos($pallet_val, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
											$pallet_val_temp = $pallet_val;
											$pallet_val = " (" . $pallet_val_temp . ")";
										} else {
											$pallet_val_format = number_format((float)$pallet_val, 0);
											$pallet_val = " (" . $pallet_val_format . ")";
										}
									} else {
										$pallet_val_format = number_format((float)$pallet_val, 0);
										$pallet_val = " (" . $pallet_val_format . ")";
									}
								} else {
									$pallet_val = "";
								}

								if ($pallet_val_afterpo > 0) {
									//reg_format = '/^\d+(?:,\d+)*$/';
									$tmppos_1 = strpos($pallet_val_afterpo, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
											$pallet_val_afterpo_temp = $pallet_val_afterpo;
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
										} else {
											$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
										}
									} else {
										$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
										$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
									}
								} else {
									$pallet_val_afterpo = "";
								}
								//

								if ($vendor_b2b_rescue_id == 238) {
									$actual_po = $dt_view_row_inv["after_actual_inventory"];
								} else {
									$actual_po = $actual_po_tmp;
								}
								//$actual_po = $actual_qty_calculated;
								//
								$to_show_rec = "y";
								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($actual_po >= $boxes_per_trailer_tmp) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}
								//
								if ($to_show_rec == "y") {
									$estimated_next_load = "";
									$b2bstatuscolor = "";
									$expected_loads_per_mo = "";
									if ($actual_po >= $boxes_per_trailer_tmp) {
										//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

										if ($dt_view_row_inv["lead_time"] == 0) {
											$estimated_next_load = "<font color=green>Now</font>";
										}

										if ($dt_view_row_inv["lead_time"] == 1) {
											$estimated_next_load = "<font color=green>" . $dt_view_row_inv["lead_time"] . " Day</font>";
										}
										if ($dt_view_row_inv["lead_time"] > 1) {
											$estimated_next_load = "<font color=green>" . $dt_view_row_inv["lead_time"] . " Days</font>";
										}
									} else {
										if (($dt_view_row_inv["expected_loads_per_mo"] <= 0) && ($actual_po < $boxes_per_trailer_tmp)) {
											$estimated_next_load = "<font color=red>Never (sell the " . $actual_po . ")</font>";
										} else {
											$estimated_next_load = ceil((((($actual_po / $boxes_per_trailer_tmp) * -1) + 1) / $dt_view_row_inv["expected_loads_per_mo"]) * 4) . " Weeks";
										}
									}

									if ($actual_po == 0 && $dt_view_row_inv["expected_loads_per_mo"] == 0) {
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}

									if ($dt_view_row_inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $dt_view_row_inv["expected_loads_per_mo"];
									}

									$blength = $dt_view_row_inv["lengthInch"];
									$bwidth = $dt_view_row_inv["widthInch"];
									$bdepth = $dt_view_row_inv["depthInch"];
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($dt_view_row_inv["lengthFraction"] != "") {
										$arr_length = explode("/", $dt_view_row_inv["lengthFraction"]);
										if (count($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($dt_view_row_inv["widthFraction"] != "") {
										$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
										if (count($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}

									if ($dt_view_row_inv["depthFraction"] != "") {
										$arr_depth = explode("/", $dt_view_row_inv["depthFraction"]);
										if (count($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}

									//
									$estimated_next_load = $dt_view_row_inv["buy_now_load_can_ship_in"];

									$b2b_status = $dt_view_row["b2b_status"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									//echo $st_query;
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_nametmp = $st_row["box_status"];

									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}

									if ($dt_view_row_inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_nametmp = "URGENT";
									}

									//
									$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, boxgoodvalue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
									$dt_view = db_query($qry_loc);
									$shipfrom_city = "";
									$shipfrom_zip = "";
									$shipfrom_state = "";
									$territory_sort = 0;
									$territory = "";
									$boxgoodvalue = "";
									while ($loc_res = array_shift($dt_view)) {
										$territory = "";
										$boxgoodvalue = $loc_res["boxgoodvalue"];
										if ($loc_res["box_warehouse_id"] == "238") {
											$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
											$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
											db_b2b();
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["shipCity"];
											$shipfrom_state = $loc_row["shipState"];
											$shipfrom_zip = $loc_row["shipZip"];
											//
											$territory = $loc_row["territory"];
											//
											if ($territory == "Canada East") {
												$territory_sort = 1;
											}
											if ($territory == "East") {
												$territory_sort = 2;
											}
											if ($territory == "South") {
												$territory_sort = 3;
											}
											if ($territory == "Midwest") {
												$territory_sort = 4;
											}
											if ($territory == "North Central") {
												$territory_sort = 5;
											}
											if ($territory == "South Central") {
												$territory_sort = 6;
											}
											if ($territory == "Canada West") {
												$territory_sort = 7;
											}
											if ($territory == "Pacific Northwest") {
												$territory_sort = 8;
											}
											if ($territory == "West") {
												$territory_sort = 9;
											}
											if ($territory == "Canada") {
												$territory_sort = 10;
											}
											if ($territory == "Mexico") {
												$territory_sort = 11;
											}
										} else {

											$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
											$get_loc_qry = "Select * from loop_warehouse where id = '" . $vendor_b2b_rescue_id . "'";
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["company_city"];
											$shipfrom_state = $loc_row["company_state"];
											$shipfrom_zip = $loc_row["company_zip"];
											//
											//
											//Find territory
											//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico

											$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
											$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
											$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
											$midwest = array('MI', 'OH', 'IN', 'KY');
											$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
											$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
											$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
											$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
											$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
											$canada = array();
											$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
											$territory_sort = 99;
											if (in_array($shipfrom_state, $canada_east, TRUE)) {
												$territory = "Canada East";
												$territory_sort = 1;
											} elseif (in_array($shipfrom_state, $east, TRUE)) {
												$territory = "East";
												$territory_sort = 2;
											} elseif (in_array($shipfrom_state, $south, TRUE)) {
												$territory = "South";
												$territory_sort = 3;
											} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
												$territory = "Midwest";
												$territory_sort = 4;
											} else if (in_array($shipfrom_state, $north_central, TRUE)) {
												$territory = "North Central";
												$territory_sort = 5;
											} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
												$territory = "South Central";
												$territory_sort = 6;
											} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
												$territory = "Canada West";
												$territory_sort = 7;
											} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
												$territory = " Pacific Northwest";
												$territory_sort = 8;
											} elseif (in_array($shipfrom_state, $west, TRUE)) {
												$territory = "West";
												$territory_sort = 9;
											} elseif (in_array($shipfrom_state, $canada, TRUE)) {
												$territory = "Canada";
												$territory_sort = 10;
											} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
												$territory = "Mexico";
												$territory_sort = 11;
											}
										}
									}
									$ship_from_tmp  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
									$ship_from2_tmp = $shipfrom_state;
									//

									//
									//
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($dt_view_row_inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($dt_view_row_inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($dt_view_row_inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($dt_view_row_inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}

									//
									$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
									$boxsize = explode("x", $btemp);
									//Ucb owned data
									//echo $box_type_cnt."<br>";
									if ($box_type_cnt == 1) {
										$sb[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $dt_view_row_inv["bwall"], 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 2) {
										$pal[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $dt_view_row_inv["bwall"], 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 3) {
										$sup[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $dt_view_row_inv["bwall"], 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									if ($box_type_cnt == 4) {
										$dbi[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => $territory, 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => $vender_name, 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => $ownername, 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => $dt_view_row_inv["bwall"], 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => $supplier_id, 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => $territory_sort);
									}
									//
									//$pallet_space_per = "";

									//----------------------------------------------------------------
								} //end if ($to_show_rec == "y")
							} //End if ($to_show_rec1 == "y")	

						} //if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 )
					} //while ($dt_view_row
					$_SESSION['sortarraygy'] = $gy;
					$_SESSION['sortarraysb'] = $sb;
					$_SESSION['sortarraysup'] = $sup;
					$_SESSION['sortarraydbi'] = $dbi;
					$_SESSION['sortarraypal'] = $pal;
					//}									
				} //foreach array loop
			}
			//
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
				<?php
				$x = 0;
				$boxtype_cnt = 0;
				$boxtype = "";
				$sorturl = "dashboardnew.php?show=inventory_new&sort_g_view=" . $sort_g_view . "&sort_g_tool=" . $sort_g_tool . "&g_timing=" . $g_timing;
				$box_name_arr = array('sb', 'pal', 'sup', 'dbi');
				foreach ($box_name_arr as $box_name) {
					//
					if ($box_name == "gy") {
						$boxtype = "Gaylord";
						$boxtype_cnt = 1;
					}
					if ($box_name == "sb") {
						$boxtype = "Shipping Boxes";
						$boxtype_cnt = 2;
					}
					if ($box_name == "pal") {
						$boxtype = "Pallets";
						$boxtype_cnt = 3;
					}
					if ($box_name == "sup") {
						$boxtype = "Supersacks";
						$boxtype_cnt = 4;
					}
					if ($box_name == "dbi") {
						$boxtype = "Drums/Barrels/IBCs";
						$boxtype_cnt = 5;
					}

					//
					$MGarray = $_SESSION['sortarray' . $box_name];
					$MGArraysort_I = array();
					$MGArraysort_II = array();
					$MGArraysort_III = array();
					foreach ($MGarray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['territory_sort'];
						$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
						$MGArraysort_III[] = $MGArraytmp['depth'];
					}
					//print_r($MGarray)."<br>";
					array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
					//
					//print_r($MGarray);
					$total_rec = count($MGarray);
					if ($total_rec > 0) {

					?>
						<tr>
							<td class="display_maintitle" align="center">Active Inventory Items - <?php echo $boxtype; ?></td>
						</tr>
						<tr>
							<td>
								<div id="btype<?php echo $boxtype_cnt; ?>">
									<table width="100%" cellspacing="1" cellpadding="2">
										<?php if ((isset($sort_g_view)) && ($sort_g_view == "1")) { ?>
											<tr>
												<td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>
										<?php
										}
										if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
										?>
											<tr>
												<td class='display_title'>Qty Avail<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>FOB Origin Price/Unit&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>

										<?php
										}
										?>
										<?php
										$count_arry = 0;
										$count = 0;
										$row_cnt = 0;
										foreach ($MGarray as $MGArraytmp2) {
											//
											$binv = "";
											$count = $count + 1;
											if ($MGArraytmp2["binv"] == "nonucb") {
												$binv = "";
											}
											if ($MGArraytmp2["binv"] == "ucbown") {
												$binv = "<b>UCB Owned Inventory </b><br>";
											}
											//
											$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
											if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
												$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
											} else {
												$tipStr .= "<b>Notes Date:</b> <br>";
											}
											$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
											$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
											$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
											$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

											$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
											$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
											$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
											$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
											$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
											$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
											$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
											$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
											$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
											$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
											$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
											$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
											$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
											$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
											$tipStr .= $binv;
											//
											if ($row_cnt == 0) {
												$display_table_css = "display_table";
												$row_cnt = 1;
											} else {
												$row_cnt = 0;
												$display_table_css = "display_table_alt";
											}
											//
											$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
											$vendornme = $MGArraytmp2["vendor_nm"];

											//
											$sales_order_qty = 0;
											if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
												$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
												$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
												$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
												$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";

												$dt_res_so_item = db_query($dt_so_item);
												while ($so_item_row = array_shift($dt_res_so_item)) {
													if ($so_item_row["sumqty"] > 0) {
														$sales_order_qty = $so_item_row["sumqty"];
													}
												}
											}
											//
											$tmpTDstr = "";
											if ((isset($sort_g_view)) && ($sort_g_view == "1")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {

													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<div";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
												$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tipnew('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTipnew()\"";

												//echo " >" ;
												$tmpTDstr =  $tmpTDstr . " >";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
												}
												$tmpTDstr =  $tmpTDstr . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
												//
												$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_" . $count . "' align='middle' style='display:none;'>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td colspan='16'>
										<div id='inventory_preord_middle_div_" . $count . "'></div>		
								  </td></tr>";
											}
											if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {
													$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</td>";

												/*$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["vendor_nm"] . "</td>";*/

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from2"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes_date"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
											}
											echo $tmpTDstr;
										}
										?>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td height="10px"></td>
						</tr>
				<?php
					}
				}
				?>
			</table>
		</div>
	<?php

	}
	//End match inventory non gaylord

	showinventory_fordashboard_non_gaylord(1, 1, 1);
	?>

<?php } else { ?>

	<?php if ($_REQUEST["view_child"] == 1) { ?>
		<div>
		<?php } else { ?>
			<div class="scrollit">
			<?php } ?>

			<table width="100%" border="0" cellspacing="0" cellpadding="1" class="basic_style">
				<input type="hidden" name="fav_match_id" id="fav_match_id" value="<?php echo $_REQUEST["ID"] ?>">
				<input type="hidden" name="fav_match_boxid" id="fav_match_boxid" value="<?php echo $_REQUEST["gbox"] ?>">
				<input type="hidden" name="fav_match_display-allrec" id="fav_match_display-allrec" value="<?php echo $_REQUEST["display-allrec"] ?>">
				<input type="hidden" name="fav_match_viewflg" id="fav_match_viewflg" value="<?php echo $_REQUEST["display_view"] ?>">
				<input type="hidden" name="fav_match_flg" id="fav_match_flg" value="<?php echo $_REQUEST["sort_g_tool2"] ?>">
				<input type="hidden" name="fav_match_load_all" id="fav_match_load_all" value="<?php echo $_REQUEST["load_all"] ?>">
				<input type="hidden" name="fav_match_client_flg" id="fav_match_client_flg" value="<?php echo $_REQUEST["client_flg"] ?>">
				<input type="hidden" name="fav_match_inboxprofile" id="fav_match_inboxprofile" value="<?php echo $_REQUEST["inboxprofile"] ?>">
				<input type="hidden" name="fav_boxtype" id="fav_boxtype" value="g">

				<?php
				$onlyftl_mode = "no";
				if ($_REQUEST["onlyftl"] == 1) {
					$onlyftl_mode = "yes";
				}

				$numrows = 0;
				$gbl_res = array();
				if ($numrows > 0) {  ?>
					<?php
					while ($gblrow = array_shift($gbl_res)) {
						echo $gblrow["tipstr"];
					}
					?>

					<?php
				} else {

					$shipLat = "";
					$shipLong = "";
					if ($onlyftl_mode == "no") {
						$x = "Select * from companyInfo Where ID = '" . $_REQUEST["ID"] . "'";
						db_b2b();
						$dt_view_res = db_query($x);
						while ($row = array_shift($dt_view_res)) {
							$shipLat = $row["ship_zip_latitude"];
							$shipLong = $row["ship_zip_longitude"];
						}
					}
					$qaa = "";
					if ($_REQUEST["gbox"] == 0) {
						$qaa = "Select * from quote_request limit 1";
					} else {
						$qaa = "Select * from quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id 
			where quote_request.companyID = " .  $_REQUEST["ID"] . " and quote_gaylord.id = " . $_REQUEST["gbox"] . " order by quote_gaylord.id DESC";
					}
					//echo $qaa . "<br>";
					//$qaa = "Select * from quote_request Where companyID = " . $_REQUEST["ID"]. " and quote_item=1";
					$qdt_view_res = db_query($qaa);
					$qnumrows = tep_db_num_rows($qdt_view_res);

					$whileLoopIteration = 0;
					$g_item_sub_type = 0;
					$gr_total_no_of_loads = 0;
					$gr_no_of_loads = 0;
					$gr_all_load_cost = 0;
					$gr_avl_load_cost = 0;
					$arrCombineItemView = array();
					$MGArray = array();
					$MGArray1 = array();
					$MGArray2 = array();

					if ($qnumrows > 0) {
						while ($qgb = array_shift($qdt_view_res)) {
							// Added by Mooneem Jul-13-12 to Bring the green thread at top	
							$aa = "Select * from quote_gaylord Where quote_id = " . $qgb["quote_id"];
							$inv_id_list = "";
							$MGArray = array();
							$x = 0;
							$bg = "#f4f4f4";
							$dt_view_res = db_query($aa);
							$gb = array_shift($dt_view_res);
							//declare variables
							$g_shape_rectangular = $gb["g_shape_rectangular"];
							$g_shape_octagonal = $gb["g_shape_octagonal"];
							$wall= $gb["g_min_tickness"];
							$g_wall_1 = $gb["g_wall_1"];
							$g_wall_2 = $gb["g_wall_2"];
							$g_wall_3 = $gb["g_wall_3"];
							$g_wall_4 = $gb["g_wall_4"];
							$g_wall_5 = $gb["g_wall_5"];
							$g_wall_6 = $gb["g_wall_6"];
							$g_wall_7 = $gb["g_wall_7"];
							$g_wall_8 = $gb["g_wall_8"];
							$g_wall_9 = $gb["g_wall_9"];
							$g_wall_10 = $gb["g_wall_10"];
							//
							$g_item_min_height = $gb["g_item_min_height"];
							$g_item_max_height = $gb["g_item_max_height"];
							//
							//$top_config=$gb["g_top_config"];
							$g_no_top = $gb["g_no_top"];
							$g_lid_top = $gb["g_lid_top"];
							$g_partial_flap_top = $gb["g_partial_flap_top"];
							$g_full_flap_top = $gb["g_full_flap_top"];
							//
							//$bottom_config=$gb["g_bottom_config"];
							$g_no_bottom_config = $gb["g_no_bottom_config"];
							$g_tray_bottom = $gb["g_tray_bottom"];
							$g_partial_flap_wo = $gb["g_partial_flap_wo"];
							$g_partial_flap_w = $gb["g_partial_flap_w"];
							$g_full_flap_bottom = $gb["g_full_flap_bottom"];
							$vents_okay = $gb["g_vents_okay"];

							$g_item_sub_type = $gb["g_item_sub_type"];
							$g_item_sub_type_str = "";
							if ($g_item_sub_type <> 11 && $g_item_sub_type > 0) {
								if ($g_item_sub_type == 2) {
									$g_item_sub_type_str = " and box_sub_type in ('2', '1')";
								} else if ($g_item_sub_type == 3) {
									$g_item_sub_type_str = " and (box_sub_type = '3' or bottom_fullflap = 1) ";
								} else if ($g_item_sub_type == 6) {
									$g_item_sub_type_str = " and (box_sub_type = '6' or ((depthInch < 36 and depthInch > 0) or (bheight_max > 0 and bheight_max < 36))) ";
								} else if ($g_item_sub_type == 7) {
									$g_item_sub_type_str = " and (box_sub_type = '7' or ((depthInch > 0 and depthInch > 45) or (bheight_min > 0 and bheight_min > 45))) ";
								} else {
									$g_item_sub_type_str = " and box_sub_type = '" . $g_item_sub_type . "'";
								}
							}

							$canship_ltl_str = "";
							if ($_REQUEST["canship_ltl"] == 1) {
								$canship_ltl_str = " and ship_ltl = 1 ";
							}

							$customer_pickup_allowed_str = "";
							if ($_REQUEST["customer_pickup"] == 1) {
								$customer_pickup_allowed_str = " and customer_pickup_allowed = 1 ";
							}
							$dk = "";
							$boxtype_txt = "Gaylord";
							if ($_REQUEST["load_all"] == 1) {
								//AND (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
								$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Gaylord' or inventory.box_type = 'GaylordUCB' or inventory.box_type = 'PresoldGaylord') $canship_ltl_str $customer_pickup_allowed_str $g_item_sub_type_str and inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
							} else {
								if ($_REQUEST["display-allrec"] == 1) {
									$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Gaylord' or inventory.box_type = 'GaylordUCB' or inventory.box_type = 'PresoldGaylord') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) $canship_ltl_str $customer_pickup_allowed_str $g_item_sub_type_str AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
								} elseif ($_REQUEST["display-allrec"] == 2) {
									$b2b_status_str = "(b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2)";
									if ($_REQUEST["sort_g_tool"] == "2") {
										$b2b_status_str = "(b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)";
									}

									if ($onlyftl_mode == "no") {
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Gaylord' or inventory.box_type = 'GaylordUCB' or inventory.box_type = 'PresoldGaylord') and $b2b_status_str $canship_ltl_str $customer_pickup_allowed_str $g_item_sub_type_str AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
									} else {
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Gaylord' or inventory.box_type = 'GaylordUCB' or inventory.box_type = 'PresoldGaylord') and $b2b_status_str $canship_ltl_str $customer_pickup_allowed_str $g_item_sub_type_str AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
									}
								} else if ($_REQUEST["view_child"] == 1) {
									$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
						inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and inventory.loops_id in (" . $_REQUEST["child_ids_array"] . ") ORDER BY inventory.availability DESC";
								} else if ($_REQUEST["showonly_box"] == 1) {
									//echo $_REQUEST["stag"];
									$tag_filter = " ";
									if ($_REQUEST["stag"] != "") {
										if (isset($_REQUEST["stag"]) && ($_REQUEST["stag"] != "")) {
											// Retrieving each selected option 
											$stag_arr = explode(",", $_REQUEST["stag"]);
											$total_tag = count($stag_arr);
											$search_tag_val = "";
											if ($total_tag >= 1) {
												foreach ($stag_arr as $tag_val) {
													$search_tag_val .= " ( tag = $tag_val or ";
													$search_tag_val .= " tag like '%,$tag_val' or ";
													$search_tag_val .= " tag like '%,$tag_val,%' or ";
													$search_tag_val .= " tag like '$tag_val,%') or ";
												}
												$search_tags = rtrim($search_tag_val, "or ");

												$tag_filter = " and (" . $search_tags . ")";
											}
										}
									}

									//subtype filter
									$filter_subtype = "";
									if (isset($_REQUEST["ssubtype"]) && ($_REQUEST["ssubtype"] != "")) {
										// Retrieving each selected option 
										$subtype_arr = explode(",", $_REQUEST["ssubtype"]);
										$search_subtype_val = "";
										foreach ($subtype_arr as $subtype_val) {
											$search_subtype_val .= " box_sub_type like '%$subtype_val%' or ";
										}
										$search_subtype = rtrim($search_subtype_val, "or ");

										$filter_subtype = " and (" . $search_subtype . ")";
									}

									$b2b_status_str = "(b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2)";
									if ($_REQUEST["sort_g_tool"] == "2") {
										$b2b_status_str = "(b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)";
									}

									if ($_REQUEST["dashpg"] == "urgentbox") {
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' AND  box_urgent=1 $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else if ($_REQUEST["dashpg"] == 1) {
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and $b2b_status_str and 
							inventory.box_type in ('Gaylord','GaylordUCB', 'Loop','PresoldGaylord') $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else if ($_REQUEST["dashpg"] == 2) {
										$boxtype_txt = "Shipping Boxes";
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and $b2b_status_str and 
							inventory.box_type in ('Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb') $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else if ($_REQUEST["dashpg"] == 3) {
										$boxtype_txt = "Pallets";
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and $b2b_status_str and 
							inventory.box_type in ('PalletsUCB','PalletsnonUCB') $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else if ($_REQUEST["dashpg"] == 4) {
										$boxtype_txt = "Supersacks";
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and $b2b_status_str and 
							inventory.box_type in ('SupersackUCB','SupersacknonUCB','Supersacks') $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else if ($_REQUEST["dashpg"] == 5) {
										$boxtype_txt = "Drums/Barrels/IBCs";
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.Active LIKE 'A' and $b2b_status_str and 
							inventory.box_type in ('DrumBarrelUCB','DrumBarrelnonUCB') $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									} else {
										$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, 
							inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE ID = '" . $_REQUEST["boxid"] . "' $tag_filter $filter_subtype ORDER BY inventory.availability DESC";
									}
									$g_item_sub_type = 2;
								} elseif ($_REQUEST["sort_g_tool2"] == 1) {
									$dk = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE (inventory.box_type = 'Gaylord' or inventory.box_type = 'GaylordUCB' or inventory.box_type = 'Loop' or inventory.box_type = 'PresoldGaylord') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) $canship_ltl_str $customer_pickup_allowed_str $g_item_sub_type_str AND inventory.Active LIKE 'A' ORDER BY inventory.availability DESC";
								}
							}
							//echo "display-allrec - " . $_REQUEST["display-allrec"] . " sort_g_tool2 - " . $_REQUEST["sort_g_tool2"] . "<br>";
							//echo $dk . "|" . $filter_subtype . "<br>";
							db_b2b();
							$yyyy = db_query($dk);
							$xxx =  tep_db_num_rows($yyyy);

							$vendor_b2b_rescue_id_pre = '';

							while ($inv = array_shift($yyyy)) {
								$count = 0;
								$tipcount_match_str = "";
								$show_rec_condition1 = "no";
								$show_rec_condition2 = "no";
								$show_rec_condition3 = "no";
								$show_rec_condition4 = "no";
								$show_rec_condition5 = "no";
								$show_rec_condition6 = "no";
								if ($g_item_sub_type == 11) {
									$show_rec_condition1 = "no";
									//if( (int)$gb["g_item_length"] == (int)$inv["lengthInch"] && (int)$gb["g_item_width"] == (int)$inv["widthInch"] && (int)$gb["g_item_height"] == (int)$inv["depthInch"])
									if (((int)$inv["depthInch"] >= (int)$g_item_min_height) && ((int)$inv["depthInch"] <= (int)$g_item_max_height)) {
										$show_rec_condition1 = "yes";
									}
									if (((int)$inv["bheight_min"] >= (int)$g_item_min_height) && ((int)$inv["bheight_max"] <= (int)$g_item_max_height)) {
										$show_rec_condition1 = "yes";
									}

									$show_rec_condition2 = "no";
									if ($g_shape_rectangular != "") {
										if ($g_shape_rectangular == "Yes" && (int)$inv["shape_rect"] == 1 && (int) $inv["shape_oct"] == 0)
										//if($g_shape_rectangular=="Yes" && (int)$inv["shape_rect"]==1)
										{
											$count = $count + 1;
											$show_rec_condition2 = "yes";
										} else {
											$tipcount_match_str .= "Rectangular Shape missing<br>";
										}
									}
									if ($g_shape_octagonal != "") {
										if ($g_shape_octagonal == "Yes" && (int)$inv["shape_oct"] == 1 && (int) $inv["shape_rect"] == 0)
										//if($g_shape_octagonal=="Yes" && (int)$inv["shape_oct"]==1)
										{
											$count = $count + 1;
											$show_rec_condition2 = "yes";
										} else {
											$tipcount_match_str .= "Octagonal Shape missing<br>";
										}
									}
									if (($g_shape_rectangular == "Yes" && $g_shape_octagonal == "Yes") && ((int)$inv["shape_oct"] == 1 && (int) $inv["shape_rect"] == 1)) {
										$count = $count + 1;
										$show_rec_condition2 = "yes";
									}
									//
									$show_rec_condition3 = "no";
									//
									if ($g_wall_2 == "Yes" && $inv["wall_2"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_3 == "Yes" && $inv["wall_3"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_4 == "Yes" && $inv["wall_4"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_5 == "Yes" && $inv["wall_5"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_6 == "Yes" && $inv["wall_6"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_7 == "Yes" && $inv["wall_7"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									if ($g_wall_8 == "Yes" && $inv["wall_8"] == 1) {
										$count = $count + 1;
										$show_rec_condition3 = "yes";
									}
									//
									$show_rec_condition4 = "no";
									if ($g_no_top == "Yes" && ((int) $inv["top_nolid"] == 1)) {
										$count = $count + 1;
										$show_rec_condition4 = "yes";
									} else {
										$tipcount_match_str .= "Top: No Flaps or Lid missing<br>>";
									}
									if ($g_lid_top == "Yes" && ((int) $inv["top_remove"] == 1)) {
										$count = $count + 1;
										$show_rec_condition4 = "yes";
									}
									if ($g_partial_flap_top == "Yes" && ((int) $inv["top_partial"] == 1)) {
										$count = $count + 1;
										$show_rec_condition4 = "yes";
									}
									if ($g_full_flap_top == "Yes" && ((int) $inv["top_full"] == 1)) {
										$count = $count + 1;
										$show_rec_condition4 = "yes";
									}
									//
									$show_rec_condition5 = "no";
									if ($g_no_bottom_config == "Yes" && ((int) $inv["bottom_no"] == 1)) {
										$count = $count + 1;
										$show_rec_condition5 = "yes";
									} else {
										$tipcount_match_str .= "Bottom: No Flaps or Lid (N) missing<br>";
									}
									if ($g_tray_bottom == "Yes" && ((int) $inv["bottom_tray"] == 1)) {
										$count = $count + 1;
										$show_rec_condition5 = "yes";
									} else {
										$tipcount_match_str .= "Bottom: Tray (T) missing<br>";
									}
									if ($g_partial_flap_wo == "Yes" && ((int) $inv["bottom_partial"] == 1)) {
										$count = $count + 1;
										$show_rec_condition5 = "yes";
									} else {
										$tipcount_match_str .= "Bottom: Partial Flap Without Slip Sheet (P) missing<br>";
									}
									if ($g_partial_flap_w == "Yes" && ((int) $inv["bottom_partialsheet"] == 1)) {
										$count = $count + 1;
										$show_rec_condition5 = "yes";
									} else {
										$tipcount_match_str .= "Bottom: Partial Flap Without Slip Sheet (P) missing<br>";
									}
									if ($g_full_flap_bottom == "Yes" && ((int) $inv["bottom_fullflap"] == 1)) {
										$count = $count + 1;
										$show_rec_condition5 = "yes";
									} else {
										$tipcount_match_str .= "Bottom: Full Flap (F) missing<br>";
									}
									//		
									$show_rec_condition6 = "no";
									if ($vents_okay == "" && (int) $inv["vents_yes"] == 0) {
										$count = $count + 1;
										$show_rec_condition6 = "yes";
									} else {
										$tipcount_match_str .= "Vents: No (N) missing<br>";
									}

									if ($vents_okay == "Yes") {
										$count = $count + 1;
										$show_rec_condition6 = "yes";
									} else {
										$tipcount_match_str .= "Vents: Yes (V) missing<br>";
									}
								}

								//echo $_REQUEST["sort_g_tool2"] . " | " . $_REQUEST["canship_ltl"] . " | " . $_REQUEST["customer_pickup"] . " - Show_rec_condition - " . $inv["I"] . " " . $show_rec_condition1 . " " . $show_rec_condition2 . " " . $show_rec_condition3 . " " . $show_rec_condition4 . " " . $show_rec_condition5 . " " . $show_rec_condition6 . "<br>"; 

								//($_REQUEST["sort_g_tool2"] == 1) || 
								if (($g_item_sub_type <> 11 && $g_item_sub_type > 0) || ($_REQUEST["sort_g_tool2"] == 2) || ($show_rec_condition1 == "yes" && $show_rec_condition2 == "yes" && $show_rec_condition3 == "yes" && $show_rec_condition4 == "yes" && $show_rec_condition5 == "yes" && $show_rec_condition6 == "yes")) {

									$b2b_ulineDollar = round($inv["ulineDollar"]);
									$b2b_ulineCents = $inv["ulineCents"];
									$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
									$b2b_fob_org = $b2b_ulineDollar + $b2b_ulineCents;
									$b2b_fob = "$" . number_format($b2b_fob, 2);

									$b2b_costDollar = round($inv["costDollar"]);
									$b2b_costCents = $inv["costCents"];
									$b2b_cost = $b2b_costDollar + $b2b_costCents;
									$b2b_cost_org = $b2b_costDollar + $b2b_costCents;
									$b2b_cost = "$" . number_format($b2b_cost, 2);

									$box_sub_type = "";
									$q1 = "SELECT sub_type_name FROM loop_boxes_sub_type_master where unqid = '" . $inv["box_sub_type"] . "'";
									$query = db_query($q1);
									while ($fetch = array_shift($query)) {
										$box_sub_type = $fetch['sub_type_name'];
									}

									$box_description = "";
									if ($inv["uniform_mixed_load"] == "Uniform") {
										$box_description = "$boxtype_txt: " . $inv["bwall"] . "ply - " . $box_sub_type . " (ID " . $inv["ID"] . ")";
									} else {
										$wall_str = "";
										if ($inv["bwall_min"] == $inv["bwall_max"]) {
											$wall_str = $inv["bwall_min"];
										} else {
											$wall_str = $inv["bwall_min"] . "-" . $inv["bwall_max"];
										}
										$box_description = "$boxtype_txt: " . $wall_str . "ply - " . $box_sub_type . " (ID " . $inv["ID"] . ")";
									}

									//if ($inv["location"] != "" )
									//$tipStr = $tipStr . "Location: " . $inv["location"] . "<br>";

									$bpallet_qty = 0;
									$boxes_per_trailer = 0;
									$box_type = "";
									$loop_id = 0;
									$ship_cdata_ltl = 'No';
									$pickup_cdata_allowed = 'No';
									$flyer_notes = "";
									$ship_ltl = "";
									$customer_pickup_allowed = "";

									$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, ship_ltl, customer_pickup_allowed, bpic_1, flyer_notes from loop_boxes where b2b_id=" . $inv["I"];
									$sku = "";
									$bpic_1 = "";
									$dt_view_sku = db_query($qry_sku);
									while ($sku_val = array_shift($dt_view_sku)) {
										$bpic_1 = $sku_val['bpic_1'];
										$loop_id = $sku_val['id'];
										$sku = $sku_val['sku'];
										$bpallet_qty = $sku_val['bpallet_qty'];
										$boxes_per_trailer = $sku_val['boxes_per_trailer'];
										$box_type = $sku_val['type'];
										$ship_ltl = $sku_val['ship_ltl'];
										$flyer_notes = $sku_val['flyer_notes'];
										$customer_pickup_allowed = $sku_val['customer_pickup_allowed'];
									}

									$parent_loop_box_id = "";
									//$qry_vendor = "Select parent_box_id from loop_boxes_parent_child where loop_box_id = '". $loop_id . "' and parent_child_flg = 2 and parent_box_id > 0";
									$qry_vendor = "Select parent_box_id from loop_boxes_parent_child where parent_box_id = '" . $loop_id . "' and parent_child_flg = 2";
									$dt_res_so = db_query($qry_vendor);
									while ($so_row_main = array_shift($dt_res_so)) {
										$parent_loop_box_id = $so_row_main["parent_box_id"];
									}

									$box_id_list = "";
									if ($parent_loop_box_id > 0) {
										$qry_vendor = "Select loop_box_id from loop_boxes_parent_child where parent_box_id = '" . $parent_loop_box_id . "'";
										//echo $qry_vendor . "<br>";
										$dt_res_so = db_query($qry_vendor);
										while ($so_row_main = array_shift($dt_res_so)) {
											$box_id_list .= $so_row_main["loop_box_id"] . ",";
										}
									}
									if (trim($box_id_list) != "") {
										$box_id_list = substr($box_id_list, 0, strlen($box_id_list) - 1) . "," . $parent_loop_box_id;
									} else {
										$box_id_list = $loop_id;
									}

									if ($ship_ltl == 1) {
										$ship_cdata_ltl = 'Yes';
									}
									if ($customer_pickup_allowed == 1) {
										$pickup_cdata_allowed = 'Yes';
									}

									//echo "location_zip " . $inv["location_zip"] . "<br>";

									if ($inv["location_zip"] != "") {
										if ($inv["availability"] != "-3.5") {
											$inv_id_list .= $inv["I"] . ",";
										}

										$distC = 0;
										if ($onlyftl_mode == "no") {
											$locLat = $inv["location_zip_latitude"];
											$locLong = $inv["location_zip_longitude"];

											//	echo $locLong;
											$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
											$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

											$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
											//echo $inv["I"] . " " . $distA . "p <br/>"; 
											$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
										}
										//To get the Actual PO, After PO
										$rec_found_box = "n";
										//$dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.b2b_id = " . $inv["I"] . " GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
										$actual_val = 0;
										$after_po_val = 0;
										$last_month_qty = 0;
										$pallet_val = "";
										$pallet_val_afterpo = "";
										$tmp_noofpallet = 0;
										$ware_house_boxdraw = "";
										$preorder_txt = "";
										$preorder_txt2 = "";
										$box_warehouse_id = 0;

										$next_load_available_date = "";
										$shipfrom_comp = "";
										$previous_contents = "";
										$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, previous_contents, next_load_available_date from loop_boxes where b2b_id=" . $inv["I"];
										$dt_view = db_query($qry_loc);
										
										$territory = "";							
										$shipfrom_city = "";
										$shipfrom_zip = "";
										$shipfrom_state = "";
										$vendor_b2b_rescue_id = "";
										while ($loc_res = array_shift($dt_view)) {
											$previous_contents = $loc_res["previous_contents"];
											$box_warehouse_id = $loc_res["box_warehouse_id"];
											$next_load_available_date = $loc_res["next_load_available_date"];

											$shipfrom_city = "";
											$shipfrom_zip = "";
											$shipfrom_state = "";
											if (isset($_REQUEST["sort_g_location"]) && ($_REQUEST["sort_g_location"] == "2" || $_REQUEST["sort_g_location"] == "3")) {
												if ($loc_res["box_warehouse_id"] == "238") {
													$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
													$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
													db_b2b();
													$get_loc_res = db_query($get_loc_qry);
													$loc_row = array_shift($get_loc_res);
													$shipfrom_comp = $loc_row["nickname"];
													$shipfrom_city = $loc_row["shipCity"];
													$shipfrom_state = $loc_row["shipState"];
													$shipfrom_zip = $loc_row["shipZip"];
												} else {
													$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
													$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
													$get_loc_res = db_query($get_loc_qry);
													$loc_row = array_shift($get_loc_res);
													$shipfrom_comp = get_nickname_val($loc_row["company_name"], $loc_row["b2bid"]);
													$shipfrom_city = $loc_row["company_city"];
													$shipfrom_state = $loc_row["company_state"];
													$shipfrom_zip = $loc_row["company_zip"];
												}
											} else {
												if ($loc_res["box_warehouse_id"] == "238") {
													$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
													$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
													db_b2b();
													$get_loc_res = db_query($get_loc_qry);
													$loc_row = array_shift($get_loc_res);
													$shipfrom_comp = $loc_row["nickname"];
													$shipfrom_city = $loc_row["shipCity"];
													$shipfrom_state = $loc_row["shipState"];
													$shipfrom_zip = $loc_row["shipZip"];
												} else {
													$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
													$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
													$get_loc_res = db_query($get_loc_qry);
													$loc_row = array_shift($get_loc_res);
													$shipfrom_comp = get_nickname_val($loc_row["company_name"], $loc_row["b2bid"]);
													$shipfrom_city = $loc_row["company_city"];
													$shipfrom_state = $loc_row["company_state"];
													$shipfrom_zip = $loc_row["company_zip"];
												}
											}


											if ($loc_res["box_warehouse_id"] == "238") {
												$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
												$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
												db_b2b();
												$get_loc_res = db_query($get_loc_qry);
												$loc_row = array_shift($get_loc_res);
												$shipfrom_city = $loc_row["shipCity"];
												$shipfrom_state = $loc_row["shipState"];
												$shipfrom_zip = $loc_row["shipZip"];
												//

												$territory = $loc_row["territory"];
												if ($territory == "Canada East") {
													$territory_sort = 1;
												}
												if ($territory == "East") {
													$territory_sort = 2;
												}
												if ($territory == "South") {
													$territory_sort = 3;
												}
												if ($territory == "Midwest") {
													$territory_sort = 4;
												}
												if ($territory == "North Central") {
													$territory_sort = 5;
												}
												if ($territory == "South Central") {
													$territory_sort = 6;
												}
												if ($territory == "Canada West") {
													$territory_sort = 7;
												}
												if ($territory == "Pacific Northwest") {
													$territory_sort = 8;
												}
												if ($territory == "West") {
													$territory_sort = 9;
												}
												if ($territory == "Canada") {
													$territory_sort = 10;
												}
												if ($territory == "Mexico") {
													$territory_sort = 11;
												}
												//

											} else {

												$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
												$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
												$get_loc_res = db_query($get_loc_qry);
												$loc_row = array_shift($get_loc_res);
												$shipfrom_city = $loc_row["company_city"];
												$shipfrom_state = $loc_row["company_state"];
												$shipfrom_zip = $loc_row["company_zip"];
												//
												//
												$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
												$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
												$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
												$midwest = array('MI', 'OH', 'IN', 'KY');
												$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
												$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
												$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
												$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
												$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
												$canada = array();
												$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
												$territory_sort = 99;
												if (in_array($shipfrom_state, $canada_east, TRUE)) {
													$territory = "Canada East";
													$territory_sort = 1;
												} elseif (in_array($shipfrom_state, $east, TRUE)) {
													$territory = "East";
													$territory_sort = 2;
												} elseif (in_array($shipfrom_state, $south, TRUE)) {
													$territory = "South";
													$territory_sort = 3;
												} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
													$territory = "Midwest";
													$territory_sort = 4;
												} else if (in_array($shipfrom_state, $north_central, TRUE)) {
													$territory = "North Central";
													$territory_sort = 5;
												} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
													$territory = "South Central";
													$territory_sort = 6;
												} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
													$territory = "Canada West";
													$territory_sort = 7;
												} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
													$territory = "Pacific Northwest";
													$territory_sort = 8;
												} elseif (in_array($shipfrom_state, $west, TRUE)) {
													$territory = "West";
													$territory_sort = 9;
												} elseif (in_array($shipfrom_state, $canada, TRUE)) {
													$territory = "Canada";
													$territory_sort = 10;
												} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
													$territory = "Mexico";
													$territory_sort = 11;
												}
											}
										}
										if ($_REQUEST["display_view"] == 1) {
											$ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
										} else {
											$get_loc_qry = "Select * from state_master where state_code ='" . $shipfrom_state . "'";
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$ship_from = $loc_row["state"];
										}
										$ship_from2 = $shipfrom_state;
										//	

										$after_po_val_tmp = 0;
										$after_po_val = 0;
										$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
										db_b2b();
										$dt_view_res_box = db_query($dt_view_qry);
										while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
											$rec_found_box = "y";
											$actual_val = $dt_view_res_box_data["actual"];
											$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
											$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
											//
										}

										if ($rec_found_box == "n") {
											$actual_val = $inv["actual_inventory"];
											$after_po_val = $inv["after_actual_inventory"];
											$last_month_qty = $inv["lastmonthqty"];
										}

										if ($box_warehouse_id == 238) {
											$after_po_val = $inv["after_actual_inventory"];
										} else {
											//if ($rec_found_box == "n"){
											//	$after_po_val = $inv["after_actual_inventory"];
											//}else{
											$after_po_val = $after_po_val_tmp;
											//}	
										}

										$to_show_rec = "y";
										/*if ($rec_found_box == "n" && ($inv["box_type"] == 'Box' || $inv["box_type"] == 'GaylordUCB')){
							$to_show_rec = "n";
							}*/

										$notes_upd_date_str = "";
										if ($inv["date"] != "") {
											$days = number_format((strtotime(date("Y-m-d")) - strtotime($inv["date"])) / (60 * 60 * 24));
											if ($_REQUEST["showonly_box"] == "1" && $_REQUEST["dashpg"] == "dash") {
												if ($_REQUEST["quotingcart"] == "1") {
													$notes_upd_date_str = "Updated: " . date("m/d/Y", strtotime($inv["date"])) . "<br> (" . $days . " days ago)";
												} else {
													$notes_upd_date_str = "Updated: " . date("m/d/Y", strtotime($inv["date"])) . " (" . $days . " days ago)";
												}
											} else {
												$notes_upd_date_str = "Updated: " . date("m/d/Y", strtotime($inv["date"])) . "<br> (" . $days . " days ago)";
											}
										}

										//To get the Shipsinweek
										$no_of_loads = 0;
										$shipsinweek = "";
										$to_show_rec = "";
										$total_no_of_loads = 0;
										if ($_REQUEST["g_timing"] == 4) {
											$to_show_rec = "";
											$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
											$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
													if ($inv["lead_time"] > $shipsinweek_org) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}
													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
										}

										//Can ship next month a date range of the 1st day of next month to last day of next month 
										if ($_REQUEST["g_timing"] == 7) {
											$to_show_rec = "";
											$next_month_date = date("Y-m-t");
											$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								and (load_available_date <= '" . $next_month_date . "')
								order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
													$shipsinweekval = "";

													if ($inv["lead_time"] > $shipsinweek_org) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}
													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
											//echo "in step 7 " . $to_show_rec . "<br>";	
										}

										//Can ship next month
										if ($_REQUEST["g_timing"] == 8) {
											$to_show_rec = "";
											$next_month_date = date("Y-m-t", strtotime("+1 month"));
											$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								and (load_available_date between '" . date("Y-m-1", strtotime("+1 month")) . "' and '" . $next_month_date . "') order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
													if ($inv["lead_time"] > $shipsinweek_org) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}
													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
										}

										//Enter ship by date = Take user input of 1 date
										if ($_REQUEST["g_timing"] == 9 && $_REQUEST["g_timing_enter_dt"] != '') {
											$to_show_rec = "";
											$next_month_date = date("Y-m-d", strtotime($_REQUEST["g_timing_enter_dt"]));
											$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								and load_available_date <= '" . $next_month_date . "' order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
													if ($inv["lead_time"] > $shipsinweek_org) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}
													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
										}

										if ($_REQUEST["g_timing"] == 6) {
											$to_show_rec = "";
											$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													$shipsinweekval = "";
													if (($inv["lead_time"] > $shipsinweek_org) || ($shipsinweek_org < 0)) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}

													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													//echo $inv["ID"] . " | " . $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . "|" . $shipsinweekval . " <br>";

													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
										}

										if ($_REQUEST["g_timing"] == 5) {
											$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
											$to_show_rec = "";
											$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$to_show_rec = "y";
												}
												$total_no_of_loads = $total_no_of_loads + 1;

												if ($no_of_loads == 1 && $dt_view_res_box_data["trans_rec_id"] == 0) {
													$now_date = time();
													$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
													$datediff = $next_load_date - $now_date;
													$shipsinweek_org = round($datediff / (60 * 60 * 24));
													$shipsinweek = "";
													//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
													if ($inv["lead_time"] > $shipsinweek_org) {
														$shipsinweekval = $inv["lead_time"];
													} else {
														$shipsinweekval = $shipsinweek_org;
													}
													if ($shipsinweekval == 0) {
														$shipsinweekval = 1;
													}
													if ($shipsinweekval >= 10) {
														$shipsinweek = round($shipsinweekval / 7) . " weeks";
													}
													if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
														$shipsinweek = $shipsinweekval . " days";
													}
													if ($shipsinweekval == 1) {
														$shipsinweek = $shipsinweekval . " day";
													}
												}
											}
										}


										$no_of_loads_main = 0;
										if ($_REQUEST["showonly_box"] == 1 && $_REQUEST["dashpg"] == "dash") {
											$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id in (" . $box_id_list . ") and inactive_delete_flg = 0 
								order by load_available_date";
											//echo $dt_view_qry . "<br>";
											$no_of_loads = 0;
											$total_no_of_loads = 0;
											$dt_view_res_box = db_query($dt_view_qry);
											while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
												if ($dt_view_res_box_data["trans_rec_id"] == 0) {
													$no_of_loads = $no_of_loads + 1;
													$no_of_loads_main = $no_of_loads_main + 1;
												}
												$total_no_of_loads = $total_no_of_loads + 1;
											}
										}

										//echo "chk_no_loads_available_val " . $_REQUEST["chk_no_loads_available_val"] . "<br>";
										//echo "in 1 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";
										if ($_REQUEST["chk_no_loads_available_val"] == "chk_no_loads_available_no") {
											if ($no_of_loads_main == 0) {
												//	$to_show_rec = "n";	
											}
										}
										//echo "in 2 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";

										if ($_REQUEST["chk_no_loads_available_val"] == "chk_no_loads_available_yes") {
											$to_show_rec = "y";
										}

										if ($_REQUEST["view_child"] == 1) {
											$to_show_rec = "y";
										}
										//echo "in 3 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";

										if ($_REQUEST["g_timing"] == 2) {
											$to_show_rec = "";
											if ($after_po_val >= $boxes_per_trailer && $after_po_val > 0) {
												$to_show_rec = "y";
											}
										}
										//echo "in 4 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";

										if ($_REQUEST["g_timing"] == 3) {
											$to_show_rec = "";
											$rowsel_arr = "";
											$rowsel_arr = explode(" ", $inv["buy_now_load_can_ship_in"], 2);

											if ($after_po_val >= $boxes_per_trailer) {
												$to_show_rec = "y";
											}
											if (($rowsel_arr[1] == 'Weeks') && ($rowsel_arr[0] <= 13)) {
												$to_show_rec = "y";
											}
										}
										//echo "in 5 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";

										if ($_REQUEST["sort_g_tool2"] == 2 && $_REQUEST["gbox"] != 0) {
											$to_show_rec = "y";
										}
										//echo "in 6 to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";

										if ($_REQUEST["showonly_box"] == 1 && $_REQUEST["dashpg"] == "dash") {
											$to_show_rec = "y";
										}

										//echo "to_show_rec - " . $to_show_rec . " g_timing-" . $_REQUEST["g_timing"] ."<br>";
										if ($to_show_rec == "y") {
											$vendor_name = "";
											$ownername = "";
											//account owner
											if ($inv["vendor_b2b_rescue"] > 0) {

												$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $inv["V"];
												if ($vendor_b2b_rescue != "") {
													$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
													db_b2b();
													$query = db_query($q1);
													while ($fetch = array_shift($query)) {
														$vendor_name = $fetch["Name"];

														$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
														db_b2b();
														$comres = db_query($comqry);
														while ($comrow = array_shift($comres)) {
															$ownername = $comrow["initials"];
														}
													}
												}
											}
											$lead_time = "";
											if ($inv["lead_time"] <= 1) {
												$lead_time = "Next Day";
											} else {
												$lead_time = $inv["lead_time"] . " Days";
											}

											//
											$estimated_next_load = "";
											$b2bstatuscolor = "";
											if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
												$now_date = time(); // or your date as well
												$next_load_date = strtotime($next_load_available_date);
												$datediff = $next_load_date - $now_date;
												$no_of_loaddays = round($datediff / (60 * 60 * 24));
											}

											//$expected_loads_per_mo = round($inv["after_actual_inventory"]/$inv["quantity"],2);
											//change on 15 07 2022
											if ($boxes_per_trailer <> 0) {
												$expected_loads_per_mo = round($after_po_val / $boxes_per_trailer, 2);
											} else {
												$expected_loads_per_mo = 0;
											}

											//
											$expected_loads_per_mo_from_db = $inv["expected_loads_per_mo"];

											$annual_volume = "";
											if ($boxes_per_trailer == 0) {
												$annual_volume = "<font color=red>0</font>";
											} else {
												$annual_volume = number_format(($boxes_per_trailer  * $expected_loads_per_mo_from_db * 12), 0);
											}
											$annual_volume_total_load = $expected_loads_per_mo_from_db * 12;

											// Qty Available, Next 3 months $qty_avail_3month
											$qty_avail_3month = 0;
											$sold_qty = 0;
											$sales_ord_sql = "SELECT loop_salesorders.so_date, loop_salesorders.warehouse_id, SUM(loop_salesorders.qty) AS QTY, loop_warehouse.b2bid, loop_warehouse.company_name AS NAME, loop_transaction_buyer.id as transid, loop_transaction_buyer.po_delivery, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.ops_delivery_date FROM loop_salesorders INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id WHERE loop_salesorders.box_id = '" . $inv['loops_id'] . "' AND loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_delivery_dt BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime('+90 Days')) . "'";
											//echo $sales_ord_sql . "<br>";

											$sales_ord_res = db_query($sales_ord_sql);
											$sales_arr = array_shift($sales_ord_res);
											$sold_qty = $sales_arr['QTY'];

											$qty_avail_3month = number_format($after_po_val + ($boxes_per_trailer  * $expected_loads_per_mo_from_db * 3) - $sold_qty, 0);
											//echo $inv['I'] . " | qty_avail_3month | " . $qty_avail_3month . " | " . $after_po_val . " | " . $boxes_per_trailer . " | " .  $expected_loads_per_mo_from_db . " | " . $sold_qty . "<br>";

											$b2b_status = $inv["b2b_status"];
											$b2bstatuscolor = "";
											$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
											$st_res = db_query($st_query);
											$st_row = array_shift($st_res);
											$b2bstatus_name = $st_row["box_status"];
											if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
												$b2bstatuscolor = "green";
											} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
												$b2bstatuscolor = "orange";
												//$estimated_next_load= "<font color=red> Ask Purch Rep </font>";
											}

											if ($inv["buy_now_load_can_ship_in"] == "<font color=red> Ask Purch Rep </font>" || $inv["buy_now_load_can_ship_in"] == "<font color=red>Ask Purch Rep</font>") {
												$estimated_next_load = "<font color=red>Ask Rep</font>";
											} else {
												$estimated_next_load = $inv["buy_now_load_can_ship_in"];
											}


											if ($inv["box_urgent"] == 1) {
												$b2bstatuscolor = "red";
												$b2bstatus_name = "URGENT";
											}

											if ($inv["uniform_mixed_load"] == "Mixed") {
												if ($inv["blength_min"] == $inv["blength_max"]) {
													$blength = $inv["blength_min"];
												} else {
													$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
												}
												if ($inv["bwidth_min"] == $inv["bwidth_max"]) {
													$bwidth = $inv["bwidth_min"];
												} else {
													$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
												}
												if ($inv["bheight_min"] == $inv["bheight_max"]) {
													$bdepth = $inv["bheight_min"];
												} else {
													$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
												}
											} else {
												$blength = $inv["lengthInch"];
												$bwidth = $inv["widthInch"];
												$bdepth = $inv["depthInch"];
											}

											$blength_frac = 0;
											$bwidth_frac = 0;
											$bdepth_frac = 0;

											$length = $blength;
											$width = $bwidth;
											$depth = $bdepth;

											if ($inv["lengthFraction"] != "") {
												$arr_length = explode("/", $inv["lengthFraction"]);
												if (count($arr_length) > 0) {
													$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
													$length = floatval($blength + $blength_frac);
												}
											}
											if ($inv["widthFraction"] != "") {
												$arr_width = explode("/", $inv["widthFraction"]);
												if (count($arr_width) > 0) {
													$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
													$width = floatval($bwidth + $bwidth_frac);
												}
											}

											if ($inv["depthFraction"] != "") {
												$arr_depth = explode("/", $inv["depthFraction"]);
												if (count($arr_depth) > 0) {
													$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
													$depth = floatval($bdepth + $bdepth_frac);
												}
											}

											$miles_from = "";
											if ($onlyftl_mode == "no") {
												$miles_from = (int) (6371 * $distC * .621371192);
											}
											$miles_away_color = "";
											if ($miles_from <= 250) {	//echo "chk gr <br/>";
												$miles_away_color = "green";
											}
											if (($miles_from <= 550) && ($miles_from > 250)) {
												$miles_away_color = "#FF9933";
											}
											if (($miles_from > 550)) {
												$miles_away_color = "red";
											}
											//

											$b_urgent = "No";
											$contracted = "No";
											$prepay = "No";
											$ship_ltl = "No";
											if ($inv["box_urgent"] == 1) {
												$b_urgent = "Yes";
											}
											if ($inv["contracted"] == 1) {
												$contracted = "Yes";
											}
											if ($inv["prepay"] == 1) {
												$prepay = "Yes";
											}
											if ($inv["ship_ltl"] == 1) {
												$ship_ltl = "Yes";
											}

											//$tipStr = "Loops ID#: " . $loop_id . "<br>";
											$tipStr = "<b>Contracted:</b> " . $contracted . "<br>";
											$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
											$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
											$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
											$tipStr .= "<b>Min Profit:</b> " . ($b2b_fob_org - $b2b_cost_org) . "<br>";
											$tipStr .= "<b>Min Margin:</b> " . round((($b2b_fob_org - $b2b_cost_org) / ($b2b_fob_org)) * 100) . "%<br>";
											$tipStr .= "<b>Previous Contents:</b> " . $previous_contents . "<br>";

											//
											$tmpTDstr_new = "";
											if (isset($_REQUEST["sort_g_location"]) && ($_REQUEST["sort_g_location"] == "2" || $_REQUEST["sort_g_location"] == "3" || $_REQUEST["sort_g_location"] == "4")) {
												if ($after_po_val < 0) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else if ($after_po_val >= $boxes_per_trailer) {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												} else {
													$qty = number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2;
												}

												$arrCombineItemView[$inv["ID"]]['ID'] = $inv["ID"];
												$arrCombineItemView[$inv["ID"]]['I'] = $inv["I"];
												$arrCombineItemView[$inv["ID"]]['qty'] = $qty;
												$arrCombineItemView[$inv["ID"]]['ship_from'] = $ship_from;
												$arrCombineItemView[$inv["ID"]]['vendor_b2b_rescue_id'] = $vendor_b2b_rescue_id;
												$arrCombineItemView[$inv["ID"]]['ReqId'] = $_REQUEST["ID"];
												$arrCombineItemView[$inv["ID"]]['expected_loads_per_mo'] = $expected_loads_per_mo;
												$arrCombineItemView[$inv["ID"]]['annual_volume'] = $annual_volume;
												$arrCombineItemView[$inv["ID"]]['box_warehouse_id'] = $box_warehouse_id;
												$arrCombineItemView[$inv["ID"]]['next_load_available_date'] = $next_load_available_date;
												$arrCombineItemView[$inv["ID"]]['qty_avail_3month'] = $qty_avail_3month;
												$arrCombineItemView[$inv["ID"]]['estimated_next_load'] = $estimated_next_load;
												$arrCombineItemView[$inv["ID"]]['boxes_per_trailer'] = number_format($boxes_per_trailer, 0);
												$arrCombineItemView[$inv["ID"]]['b2b_fob'] = $b2b_fob;
												$arrCombineItemView[$inv["ID"]]['miles_from'] = $miles_from;
												$arrCombineItemView[$inv["ID"]]['b2bstatuscolor'] = $b2bstatuscolor;
												$arrCombineItemView[$inv["ID"]]['b2bstatus_name'] = $b2bstatus_name;
												$arrCombineItemView[$inv["ID"]]['length'] = $length;
												$arrCombineItemView[$inv["ID"]]['width'] = $width;
												$arrCombineItemView[$inv["ID"]]['depth'] = $depth;
												$arrCombineItemView[$inv["ID"]]['wall'] = $wall;
												$arrCombineItemView[$inv["ID"]]['tipStr'] = $tipStr;
												$arrCombineItemView[$inv["ID"]]['description'] = $inv["description"];
												$arrCombineItemView[$inv["ID"]]['vendor_name'] = $vendor_name;
												$arrCombineItemView[$inv["ID"]]['ownername'] = $ownername;
												if ($inv["uniform_mixed_load"] == "Mixed") {
													if ($inv["bwall_min"] == $inv["bwall_max"]) {
														$arrCombineItemView[$inv["ID"]]['bwall'] = $inv["bwall_min"];
													} else {
														$arrCombineItemView[$inv["ID"]]['bwall'] = $inv["bwall_min"] . "-" . $inv["bwall_max"];
													}
												} else {
													$arrCombineItemView[$inv["ID"]]['bwall'] = $inv["bwall"];
												}

												$arrCombineItemView[$inv["ID"]]['ship_from2'] = $ship_from2;
												$arrCombineItemView[$inv["ID"]]['after_po_val'] = $after_po_val;
												$arrCombineItemView[$inv["ID"]]['boxes_per_trailer'] = $boxes_per_trailer;
												$arrCombineItemView[$inv["ID"]]['miles_away_color'] = $miles_away_color;
												$arrCombineItemView[$inv["ID"]]['bpallet_qty'] = $bpallet_qty;
												$arrCombineItemView[$inv["ID"]]['b2b_cost'] = $b2b_cost;
												$arrCombineItemView[$inv["ID"]]['ship_ltl'] = $ship_ltl;
												$arrCombineItemView[$inv["ID"]]['prepay'] = $prepay;
												$arrCombineItemView[$inv["ID"]]['contracted'] = $contracted;
												$arrCombineItemView[$inv["ID"]]['b_urgent'] = $b_urgent;
												$arrCombineItemView[$inv["ID"]]['N'] = $inv["N"];
												$arrCombineItemView[$inv["ID"]]['DT'] = $inv["DT"];
												$arrCombineItemView[$inv["ID"]]['distC'] = $distC;
												$arrCombineItemView[$inv["ID"]]['box_urgent'] = $inv["box_urgent"];
												$arrCombineItemView[$inv["ID"]]['lead_time'] = $inv["lead_time"];
												$arrCombineItemView[$inv["ID"]]['ship_cdata_ltl'] = $ship_cdata_ltl;
												$arrCombineItemView[$inv["ID"]]['pickup_cdata_allowed'] = $pickup_cdata_allowed;
											} else {
												//To get the Actual PO, After Po //Display customer view
												if (isset($_REQUEST["display_view"])) {
													$b2b_fob_main = str_replace(",", "", $b2b_fob);
													$b2b_fob_main = str_replace("$", "", $b2b_fob_main);

													$avl_load_total_cost = 0;
													$avl_load_cost = 0;
													$avl_load_total_cost = (float)$total_no_of_loads * (float)$boxes_per_trailer * (float)$b2b_fob_main;
													$avl_load_cost = (float)$no_of_loads * (float)$boxes_per_trailer * (float)$b2b_fob_main;
													$gr_all_load_cost = $gr_all_load_cost + $avl_load_total_cost;
													$gr_avl_load_cost = $gr_avl_load_cost + $avl_load_cost;

													$expand_btn = "";
													$expand_btn_collapse = "";
													if ($parent_loop_box_id > 0) {
														$qry_vendor = "Select loop_box_id from loop_boxes_parent_child where parent_box_id = '" . $inv["loops_id"] . "'";
														$dt_res_so = db_query($qry_vendor);
														$loop_box_id_child = array();
														while ($so_row_main = array_shift($dt_res_so)) {
															$loop_box_id_child[] = $so_row_main['loop_box_id'];
														}
														if ($_REQUEST["showonly_box"] == 1) {
															//$function_params = "0,0,".$_REQUEST['gbox'].",".$_REQUEST['display_view'].",0,0,".$inv["loops_id"].",".json_encode($loop_box_id_child).",".$inv['loops_id']; 
														} else {
															$function_params = $_REQUEST['ID'] . "," . $_REQUEST['display-allrec'] . "," . $_REQUEST['gbox'] . "," . $_REQUEST['display_view'] . "," . $_REQUEST['client_flg'] . "," . $_REQUEST['orgboxid'] . "," . $inv["loops_id"] . "," . json_encode($loop_box_id_child) . "," . $inv['loops_id'];

															$expand_btn = "<a href='javascript:get_all_variations($function_params);'>Expand Variations</a>";
															$expand_btn_collapse = "<a href='javascript:get_all_variations_collapse($function_params);'>Collapse Variations</a>";
														}
													}

													$tmpTDstr_new = "<tr>";
													$tmpTDstr_new .= "<td rowspan='5' align='center' width='180px;' bgColorrepl>
													<a target='_blank' href='https://b2b.usedcardboardboxes.com/?id=" . urlencode(encrypt_password($inv["loops_id"])) . "'>
													<img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/" . $bpic_1 . "' width='120px' height='120px;' style='object-fit: cover; width:120px; height:120px;'/></a></td>";

													$tmpTDstr_new .= "<td colspan='5' bgColorrepl >";
													if (isset($_REQUEST["fntend"]) && $_REQUEST["fntend"] == "boomerang") {
														$tmpTDstr_new .= "<a href='javascript:void();'>";
													} else {
														$tmpTDstr_new .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($inv["I"]) . "&proc=View&'";
														$tmpTDstr_new .= " onmouseover=\"Tipnew('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTipnew()\">";
													}

													$tmpTDstr_new .= "<font size='4'><b>";
													$tmpTDstr_new .= $box_description . "</b></font></a> <span id='span_expand_v" . $inv["loops_id"] . "'>" . $expand_btn . "</span><span style='display:none;' id='span_expand_v_collapse" . $inv["loops_id"] . "'>" . $expand_btn_collapse . "</span></td>";
													if ($_REQUEST["showonly_box"] == 1) {
														$tmpTDstr_new .= "<td colspan='4' align='center' bgColorrepl>&nbsp;</td>";
														$tmpTDstr_new .= "<td id='td_cal_del" . $inv["ID"] . "' style='background:#cee7c3;' width='150px;' align='center'><font size='4'><b>" . $b2b_fob . "</b></font>
													<br>
													</td>";
													} else {
														$tmpTDstr_new .= "<td colspan='4' align='center' bgColorrepl><font color='$miles_away_color'>" . $miles_from . " mi away</td>";

														$tmpTDstr_new .= "<td id='td_cal_del" . $inv["ID"] . "' style='background:#cee7c3;' width='150px;' align='center'><font size='4'><b>" . $b2b_fob . "</b></font><br>
														<a href='#' style='color:blue !important;' onClick='calculate_delivery(" . $inv["ID"] . "," . $_REQUEST["ID"] . "," . $b2b_fob_main . "); return false;' >Calculate Delivery (FTL)</a></td>";
													}

													$tmpTDstr_new .= "</tr>";

													$tmpTDstr_new .= "<tr>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;' align='center'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_status.png' style='width:13px;height:13px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Status:</b></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='400px;'><font color='" . $b2bstatuscolor . "'>" . $b2bstatus_name . "</font></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_truck.png' style='width:15px;height:15px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='100px;'><b>Can Ship LTL?</b></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $ship_cdata_ltl . "</td>";
													$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
													$tmpTDstr_new .= "<td width='150px;' bgColorrepl>&nbsp;</td>";
													$tmpTDstr_new .= "</tr>";

													$tmpTDstr_new .= "<tr>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;' align='center' ><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_Location.png' style='width:10px;height:15px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Ship From:</b></td>";
													if ($_REQUEST["display_view"] == "1") {
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $ship_from . " (" . $shipfrom_comp . ") (" . $territory . " Territory)</td>";
													} else {
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $ship_from . "</td>";
													}
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/can_customer_pickup.png' style='width:15px;height:15px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Can Customer Pickup?</b></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $pickup_cdata_allowed . "</td>";
													$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
													$no_of_loads_str = "";
													if ($total_no_of_loads == 1) {
														$no_of_loads_str = " Load";
													}
													if ($total_no_of_loads > 1) {
														$no_of_loads_str = " Loads";
													}
													$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#cee7c3;' >";
													if ($_REQUEST["view_child"] == 1) {
														$tmpTDstr_new .= "&nbsp;</td>";
													} else {

														if (isset($_REQUEST["fntend"]) && $_REQUEST["fntend"] == "boomerang") {
															$tmpTDstr_new .= "
												$no_of_loads of $total_no_of_loads $no_of_loads_str</td>";
														} else {
															$tmpTDstr_new .= "
												<a target='_blank' href='manage_box_next_load_available_date.php?id=" . $inv["ID"] . "&inview=yes'>$no_of_loads of $total_no_of_loads $no_of_loads_str</a></td>";
														}
													}
													$tmpTDstr_new .= "</tr>";

													$gr_total_no_of_loads = $gr_total_no_of_loads + $total_no_of_loads;
													$gr_no_of_loads = $gr_no_of_loads + $no_of_loads;

													$tmpTDstr_new .= "<tr>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_Dimensions.png' style='width:15px;height:15px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Description:</b></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $inv["system_description"] . "<br>" . $inv["quantity_per_pallet"] . "/pallet, " . $inv["quantity"] . "/load " . $inv["additional_description_text"] . "</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_supplier_owner.png' style='width:15px;height:15px;'/></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Supplier Owner?</b></td>";
													$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $ownername . "</td>";
													$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
													if ($_REQUEST["view_child"] == 1) {
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#cee7c3;' >&nbsp;</td>";
													} else {
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#cee7c3;' >First load ships in $shipsinweek</td>";
													}
													$tmpTDstr_new .= "</tr>";

													$tmpTDstr_new .= "<tr>";
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;' align='center'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_notes.png' style='width:13px;height:13px;'/></td>";
													if ($_REQUEST["display_view"] == "1") {
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Sales Notes:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $inv["notes"] . "</td>";
													} else {
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Flyer Notes:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $flyer_notes . "</td>";
													}
													$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl width='10px;'>&nbsp;</td>";
													$tmpTDstr_new .= "<td bgColorrepl colspan='2' width='70px;'>" . $notes_upd_date_str . "</td>";
													$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
													if ($_REQUEST["showonly_box"] == 1) {
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#99c796;' ><font size='4'>&nbsp;</font></td>";
													} else {
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#99c796;'><div id=fav_div_display" . $inv["I"] . "><a id='div_favourite" . $inv["I"] . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $inv["I"] . ",1)' ><font size='4'><b>Add to Favorite</b></font></a></div></td>";
													}
													$tmpTDstr_new .= "</tr>";

													$tmpTDstr_new .= "<tr><td colspan='11' style='line-height: 1px; height: 1px !important;'>&nbsp;</td></tr>";

													//For the Expand Variations
													if ($parent_loop_box_id > 0) {
														$tmpTDstr_new .= "<tr ><td colspan='11'><div id='expand_var" . $inv["loops_id"] . "'></div></td></tr>";
													}

													if ($actual_po < 0) {
														$qty_avail =  "<font color=blue>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
													} else {
														$qty_avail =  "<font color=green>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
													}
													$fav_b2bid = $inv["I"];
													?>

													<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
													<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
													<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $inv["expected_loads_per_mo"] ?>">
													<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer_tmp ?>">
													<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
													<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
													<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[0] ?>">
													<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[1] ?>">
													<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[2] ?>">
													<input type="hidden" name="fav_miles" id="fav_miles<?php echo $fav_b2bid; ?>" value="<?php echo $miles_from ?>">
													<?php
													if ($inv["uniform_mixed_load"] == "Mixed") { ?>
														<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $inv["bwall_min"] . "-" . $inv["bwall_max"]; ?>">
													<?php } else { ?>
														<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $inv["bwall"] ?>">
													<?php } ?>

													<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $inv["description"] ?>">
													<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2_tmp ?>">
													<?php


												} //To get the Actual PO, After Po ends


											}

											//new log to record the top10 option for filter#2
											if ($_REQUEST["display-allrec"] == 2) {
												$dttoday = date("Y-m-d");
											}
											//
											$mileage = "";
											if ($onlyftl_mode == "no") {
												$mileage = (int) (6371 * $distC * .621371192);
											}


											$MGArray_new[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr_new, 'sub_type' => $box_sub_type, 'territory' => $territory, 'box_urgent' => $inv["box_urgent"]);
										}
									}
								} //if count > 4
							} //inv			
							//-------------------------------------------------------------------------------------

							//From UCB owned inventory
							if ($_REQUEST["showonly_box"] == 1) {
							} else {
								if ($inv_id_list != "") {
									$inv_id_list = substr($inv_id_list, 0, strlen($inv_id_list) - 1);
								}
								$dt_view_qry = "";
								if ($_REQUEST["load_all"] == 1) {
									//and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2)
									$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') order by warehouse, type_ofbox, Description";
								} else {
									if ($_REQUEST["display-allrec"] == 1) {
										$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
									}
									if ($_REQUEST["display-allrec"] == 2) {
										$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and actual > 0 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
									}
									if ($_REQUEST["sort_g_tool2"] == 1) {
										$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox = 'GaylordUCB' or type_ofbox = 'Gaylord') and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
									}
								}

								//and inv_id not in ($inv_id_list)
								//echo $dt_view_qry;
								db_b2b();
								$dt_view_res = db_query($dt_view_qry);

								$tmpwarenm = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								while ($dt_view_row = array_shift($dt_view_res)) {

									$b2bid_tmp = 0;
									$boxes_per_trailer_tmp = 0;
									$bpallet_qty_tmp = 0;
									$vendor_id = 0;
									$vendor_b2b_rescue_id = 0;
									$customer_pickup_allowed = "";
									$ship_ltl = "";
									$vendor_name = "";
									$ownername = "";
									$bpic_1 = "";
									$ship_cdata_ltl = "";
									$pickup_cdata_allowed = "";
									$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, ship_ltl, customer_pickup_allowed, 
						box_warehouse_id, expected_loads_per_mo, bpic_1 from loop_boxes where id=" . $dt_view_row["trans_id"] . $g_item_sub_type_str;
									$boxes_per_trailer_tmp ="";
									$dt_view_loopbox = db_query($qry_loopbox);
									while ($rs_loopbox = array_shift($dt_view_loopbox)) {
										$bpic_1 = $rs_loopbox['bpic_1'];
										$b2bid_tmp = $rs_loopbox['b2b_id'];
										$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
										$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
										$vendor_id = $rs_loopbox['vendor'];
										$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
										$ship_ltl = $rs_loopbox['ship_ltl'];
										$customer_pickup_allowed = $rs_loopbox['customer_pickup_allowed'];
									}

									if ($ship_ltl == 1) {
										$ship_cdata_ltl = 'Yes';
									}
									if ($customer_pickup_allowed == 1) {
										$pickup_cdata_allowed = 'Yes';
									}
									if ($vendor_id != "") {
										$qry = "select vendors.name AS VN from vendors where id=" . $vendor_id;
										db_b2b();
										$dt_view = db_query($qry);
										while ($sku_val = array_shift($dt_view)) {
											$vendor_name = $sku_val["VN"];
										}
									}
									$inv_availability = "";
									$distC = 0;
									$inv_notes = "";
									$inv_notes_dt = "";
									$system_description = "";
									$quantity_per_pallet = "";
									$quantity = "";

									$show_rec_condition1 = "no";
									$show_rec_condition2 = "no";
									$show_rec_condition3 = "no";
									$show_rec_condition4 = "no";
									$show_rec_condition5 = "no";
									$show_rec_condition6 = "no";
									$show_rec_condition7 = "no";
									$show_rec_condition8 = "no";
									$dt_view_row_inv = array();
									if ($b2bid_tmp > 0) {
										$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp;
										db_b2b();
										$dt_view_inv_res = db_query($inv_qry);
										$dt_view_row_inv = array_shift($dt_view_inv_res);
										//while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
											$system_description = $dt_view_row_inv["system_description"];
											$quantity_per_pallet = $dt_view_row_inv["quantity_per_pallet"];
											$quantity = $dt_view_row_inv["quantity"];

											$inv_notes = $dt_view_row_inv["notes"];
											$inv_notes_dt = $dt_view_row_inv["date"];
											$location_city = $dt_view_row_inv["location_city"];
											$location_state = $dt_view_row_inv["location_state"];
											$location_zip = $dt_view_row_inv["location_zip"];
											$locLat = $dt_view_row_inv["location_zip_latitude"];
											$locLong = $dt_view_row_inv["location_zip_longitude"];
											$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
											$vendor_id = $dt_view_row_inv["vendor"];
											$lead_time = "";
											if ($dt_view_row_inv["lead_time"] <= 1) {
												$lead_time = "Next Day";
											} else {
												$lead_time = $dt_view_row_inv["lead_time"] . " Days";
											}
											//
											$b2bstatus = $dt_view_row_inv['b2bstatus'];
											$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];

											//account owner
											$vendor_name = "";
											$ownername = "";
											if ($vendor_b2b_rescue > 0) {
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = get_nickname_val($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $vendor_id;
												if ($vendor_b2b_rescue != "") {
													$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
													db_b2b();
													$query = db_query($q1);
													while ($fetch = array_shift($query)) {
														$vendor_name = $fetch["Name"];

														$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
														db_b2b();
														$comres = db_query($comqry);
														while ($comrow = array_shift($comres)) {
															$ownername = $comrow["initials"];
														}
													}
												}
											}
											//				

											if (((int)$dt_view_row_inv["depthInch"] >= (int)$g_item_min_height && (int)$dt_view_row_inv["depthInch"] <= (int)$g_item_max_height)) {
												$show_rec_condition1 = "yes";
											}
											if (((int)$dt_view_row_inv["bheight_min"] >= (int)$g_item_min_height && (int)$dt_view_row_inv["bheight_max"] <= (int)$g_item_max_height)) {
												$show_rec_condition1 = "yes";
											}

											$count = 0;
											$tipcount_match_str = "";
											$show_rec_condition2 = "no";
											if ($g_shape_rectangular != "") {
												if ($g_shape_rectangular == "Yes" && (int)$dt_view_row_inv["shape_rect"] == 1 && (int) $dt_view_row_inv["shape_oct"] == 0) {
													$count = $count + 1;
													$show_rec_condition2 = "yes";
												} else {
													$tipcount_match_str .= "Rectangular Shape missing<br>";
												}
											}
											if ($g_shape_octagonal != "") {
												if ($g_shape_octagonal == "Yes" && (int)$dt_view_row_inv["shape_oct"] == 1 && (int) $dt_view_row_inv["shape_rect"] == 0) {
													$count = $count + 1;
													$show_rec_condition2 = "yes";
												} else {
													$tipcount_match_str .= "Octagonal Shape missing<br>";
												}
											}
											if (($g_shape_rectangular == "Yes" && $g_shape_octagonal == "Yes") && ((int)$dt_view_row_inv["shape_oct"] == 1 && (int) $dt_view_row_inv["shape_rect"] == 1)) {
												$count = $count + 1;
												$show_rec_condition2 = "yes";
											}
											//
											$show_rec_condition3 = "no";
											//
											if ($g_wall_1 == "Yes" && $dt_view_row_inv["bwall"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_2 == "Yes" && $dt_view_row_inv["wall_2"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_3 == "Yes" && $dt_view_row_inv["wall_3"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_4 == "Yes" && $dt_view_row_inv["wall_4"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_5 == "Yes" && $dt_view_row_inv["wall_5"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_6 == "Yes" && $dt_view_row_inv["wall_6"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_7 == "Yes" && $dt_view_row_inv["wall_7"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											if ($g_wall_8 == "Yes" && $dt_view_row_inv["wall_8"] == 1) {
												$count = $count + 1;
												$show_rec_condition3 = "yes";
											}
											//
											$show_rec_condition4 = "no";
											if ($g_no_top == "Yes" && ((int) $dt_view_row_inv["top_nolid"] == 1)) {
												$count = $count + 1;
												$show_rec_condition4 = "yes";
											} else {
												$tipcount_match_str .= "Top: No Flaps or Lid missing<br>>";
											}
											if ($g_lid_top == "Yes" && ((int) $dt_view_row_inv["top_remove"] == 1)) {
												$count = $count + 1;
												$show_rec_condition4 = "yes";
											}
											if ($g_partial_flap_top == "Yes" && ((int) $dt_view_row_inv["top_partial"] == 1)) {
												$count = $count + 1;
												$show_rec_condition4 = "yes";
											}
											if ($g_full_flap_top == "Yes" && ((int) $dt_view_row_inv["top_full"] == 1)) {
												$count = $count + 1;
												$show_rec_condition4 = "yes";
											}
											//
											$show_rec_condition5 = "no";
											if ($g_no_bottom_config == "Yes" && ((int) $dt_view_row_inv["bottom_no"] == 1)) {
												$count = $count + 1;
												$show_rec_condition5 = "yes";
											} else {
												$tipcount_match_str .= "Bottom: No Flaps or Lid (N) missing<br>";
											}
											if ($g_tray_bottom == "Yes" && ((int) $dt_view_row_inv["bottom_tray"] == 1)) {
												$count = $count + 1;
												$show_rec_condition5 = "yes";
											} else {
												$tipcount_match_str .= "Bottom: Tray (T) missing<br>";
											}
											if ($g_partial_flap_wo == "Yes" && ((int) $dt_view_row_inv["bottom_partial"] == 1)) {
												$count = $count + 1;
												$show_rec_condition5 = "yes";
											} else {
												$tipcount_match_str .= "Bottom: Partial Flap Without Slip Sheet (P) missing<br>";
											}
											if ($g_partial_flap_w == "Yes" && ((int) $dt_view_row_inv["bottom_partialsheet"] == 1)) {
												$count = $count + 1;
												$show_rec_condition5 = "yes";
											} else {
												$tipcount_match_str .= "Bottom: Partial Flap Without Slip Sheet (P) missing<br>";
											}
											if ($g_full_flap_bottom == "Yes" && ((int) $dt_view_row_inv["bottom_fullflap"] == 1)) {
												$count = $count + 1;
												$show_rec_condition5 = "yes";
											} else {
												$tipcount_match_str .= "Bottom: Full Flap (F) missing<br>";
											}
											//


											$show_rec_condition6 = "no";
											if ($vents_okay == "" && (int) $dt_view_row_inv["vents_yes"] == 0) {
												$count = $count + 1;
												$show_rec_condition6 = "yes";
											} else {
												$tipcount_match_str .= "Vents: No (N) missing<br>";
											}

											if ($vents_okay == "Yes") {
												$count = $count + 1;
												$show_rec_condition6 = "yes";
											} else {
												$tipcount_match_str .= "Vents: Yes (V) missing<br>";
											}


											$inv_availability = $dt_view_row_inv["availability"];
											

											$tmp_zipval = "";
											$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");

											//if (remove_non_numeric($dt_view_row_inv["location"]) != "")		
											if ($dt_view_row_inv["location_zip"] != "") {
												//	echo $locLong;
												$distC = 0;
												if ($onlyftl_mode == "no") {
													$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
													$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

													$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
													//echo $dt_view_row_inv["I"] . " " . $distA . "p <br/>"; 
													$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
													//echo $distC . "g <br/>";
												}
											}
										//} //dt_view_row_inv
									}

									$b2b_fob_org = $dt_view_row["min_fob"];
									$b2b_cost_org = $dt_view_row["cost"];

									$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
									$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

									$sales_order_qty = $dt_view_row["sales_order_qty"];

									if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {

										//echo "2 Show_rec_condition - " . $b2bid_tmp . " = " . $show_rec_condition1 . " " . $show_rec_condition2 . " " . $show_rec_condition3 . " " . $show_rec_condition4 . " " . $show_rec_condition5 . " " . $show_rec_condition6 . " " . $dt_view_row["Description"] . " " . "<br>"; 			
										//($_REQUEST["sort_g_tool2"] == 1) ||
										if (($g_item_sub_type <> 11 && $g_item_sub_type > 0) || ($_REQUEST["sort_g_tool2"] == 2) || ($show_rec_condition1 == "yes" && $show_rec_condition2 == "yes" && $show_rec_condition3 == "yes" && $show_rec_condition4 == "yes" && $show_rec_condition5 == "yes" && $show_rec_condition6 == "yes")) {
											$lastmonth_val = $dt_view_row["lastmonthqty"];

											$reccnt = 0;
											if ($sales_order_qty > 0) {
												$reccnt = $sales_order_qty;
											}

											$preorder_txt = "";
											$preorder_txt2 = "";

											if ($reccnt > 0) {
												$preorder_txt = "<u>";
												$preorder_txt2 = "</u>";
											}

											if (($dt_view_row["actual"] >= $boxes_per_trailer_tmp) && ($boxes_per_trailer_tmp > 0)) {
												$bg = "yellow";
											}

											$pallet_val = 0;
											$pallet_val_afterpo = 0;
											$actual_po_tmp = $dt_view_row["actual"] - $sales_order_qty;

											if ($bpallet_qty_tmp > 0) {
												$pallet_val = number_format($dt_view_row["actual"] / (float)$bpallet_qty_tmp, 1, '.', '');
												$pallet_val_afterpo = number_format($actual_po_tmp / $bpallet_qty_tmp, 1, '.', '');
											}

											$to_show_rec1 = "y";

											if ($to_show_rec1 == "y") {
												$pallet_space_per = "";

												if ($pallet_val > 0) {
													$tmppos_1 = strpos($pallet_val, '.');
													if ($tmppos_1 != false) {
														if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
															$pallet_val_temp = $pallet_val;
															$pallet_val = " (" . $pallet_val_temp . ")";
														} else {
															$pallet_val_format = number_format((float)$pallet_val, 0);
															$pallet_val = " (" . $pallet_val_format . ")";
														}
													} else {
														$pallet_val_format = number_format((float)$pallet_val, 0);
														$pallet_val = " (" . $pallet_val_format . ")";
													}
												} else {
													$pallet_val = "";
												}

												if ($pallet_val_afterpo > 0) {
													//reg_format = '/^\d+(?:,\d+)*$/';
													$tmppos_1 = strpos($pallet_val_afterpo, '.');
													if ($tmppos_1 != false) {
														if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
															$pallet_val_afterpo_temp = $pallet_val_afterpo;
															$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
														} else {
															$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
															$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
														}
													} else {
														$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
														$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
													}
												} else {
													$pallet_val_afterpo = "";
												}
												//
												$actual_po = "";
												if ($vendor_b2b_rescue_id == 238) {
													$actual_po = $dt_view_row_inv["after_actual_inventory"];
												} else {
													$actual_po = $actual_po_tmp;
												}

												$to_show_rec = "y";
												if ($_REQUEST["g_timing"] == 2) {
													$to_show_rec = "";
													if ($actual_po >= $boxes_per_trailer_tmp && $actual_po > 0) {
														$to_show_rec = "y";
													}
												}

												if ($_REQUEST["g_timing"] == 3) {
													$to_show_rec = "";
													$rowsel_arr = "";
													$rowsel_arr = explode(" ", $dt_view_row_inv["buy_now_load_can_ship_in"], 2);

													if ($actual_po >= $boxes_per_trailer_tmp) {
														$to_show_rec = "y";
													}
													if (($rowsel_arr[1] == 'Weeks') && ($rowsel_arr[0] <= 13)) {
														$to_show_rec = "y";
													}
												}

												if ($_REQUEST["sort_g_tool2"] == 2 && $_REQUEST["gbox"] != 0) {
													$to_show_rec = "y";
												}

												$notes_upd_date_str = "";
												if ($inv_notes_dt != "") {
													$days = number_format((strtotime(date("Y-m-d")) - strtotime($inv_notes_dt)) / (60 * 60 * 24));
													if ($_REQUEST["showonly_box"] == "1" && $_REQUEST["dashpg"] == "dash") {
														$notes_upd_date_str = "Updated: " . date("m/d/Y", strtotime($inv_notes_dt)) . " (" . $days . " days ago)";
													} else {
														$notes_upd_date_str = "Updated: " . date("m/d/Y", strtotime($inv_notes_dt)) . "<br> (" . $days . " days ago)";
													}
												}

												$no_of_loads = 0;
												$shipsinweek = 0;
												$to_show_rec = "";
												$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
												$dt_view_qry = "SELECT load_available_date from loop_next_load_available_history where inv_b2b_id = " . $b2bid_tmp . " and inactive_delete_flg = 0 
									and (load_available_date between '" . date("Y-m-d") . "' and '" . $next_2_week_date . "') order by load_available_date";
												//echo $dt_view_qry . "<br>";
												$dt_view_res_box = db_query($dt_view_qry);
												while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
													if ($no_of_loads == 1) {
														$now_date = time();
														$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
														$datediff = $next_load_date - $now_date;
														$shipsinweek = round($datediff / (60 * 60 * 24));

														if ($shipsinweek > 0) {
															$shipsinweek = ceil($shipsinweek / 7);
														}
													}
													$to_show_rec = "y";
												}

												if ($_REQUEST["g_timing"] == 6) {
													$to_show_rec = "y";
												}

												if ($_REQUEST["g_timing"] == 5) {
													$next_2_week_date = date("Y-m-d", strtotime("+1 day"));
													$to_show_rec = "";
													$dt_view_qry = "SELECT load_available_date from loop_next_load_available_history where inv_b2b_id = " . $b2bid_tmp . " and inactive_delete_flg = 0 
										and (load_available_date between '" . date("Y-m-d") . "' and '" . $next_2_week_date . "') order by load_available_date";
													//echo $dt_view_qry . "<br>";
													$dt_view_res_box = db_query($dt_view_qry);
													while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
														$no_of_loads = $no_of_loads + 1;
														$to_show_rec = "y";
													}
												}

												if ($to_show_rec == "y") {
													$estimated_next_load = "";
													$b2bstatuscolor = "";

													if ($dt_view_row_inv["expected_loads_per_mo"] == 0) {
														$expected_loads_per_mo = "<font color=red>0</font>";
													} else {
														$expected_loads_per_mo = $dt_view_row_inv["expected_loads_per_mo"];
													}


													$annual_volume = "";
													if ($dt_view_row_inv["expected_loads_per_mo"] == 0) {
														$annual_volume = "<font color=red>0</font>";
													} else {
														$annual_volume = round(($dt_view_row_inv["quantity"] * $dt_view_row_inv["expected_loads_per_mo"] * 12), 2);
													}
													$annual_volume_total_load = $dt_view_row_inv["expected_loads_per_mo"] * 12;

													// Qty Available, Next 3 months $qty_avail_3month
													$qty_avail_3month1 = 0;
													$sold_qty1 = 0;
													$sales_ord_sql1 = "SELECT loop_salesorders.so_date, loop_salesorders.warehouse_id, SUM(loop_salesorders.qty) AS QTY, loop_warehouse.b2bid, loop_warehouse.company_name AS NAME, loop_transaction_buyer.id as transid, loop_transaction_buyer.po_delivery, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.ops_delivery_date FROM loop_salesorders INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id WHERE loop_salesorders.box_id = '" . $dt_view_row_inv['loops_id'] . "'  AND loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 AND loop_transaction_buyer.po_delivery_dt BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime('+90 Days')) . "'";

													$sales_ord_res1 = db_query($sales_ord_sql1);
													$sales_arr1 = array_shift($sales_ord_res1);
													$sold_qty1 = $sales_arr1['QTY'];

													$qty_avail_3month1 = number_format($actual_po + ($dt_view_row_inv["quantity"] * $dt_view_row_inv["expected_loads_per_mo"] * 3) - $sold_qty1, 0);

													//echo $dt_view_row_inv['ID'] . " | qty_avail_3month1 | " . $qty_avail_3month1 . " | " . $actual_po . " | " . $dt_view_row_inv["quantity"] . " | " .  $dt_view_row_inv["expected_loads_per_mo"] . " | " . $sold_qty . "<br>";

													if ($dt_view_row_inv["uniform_mixed_load"] == "Mixed") {
														if ($dt_view_row_inv["blength_min"] == $dt_view_row_inv["blength_max"]) {
															$blength = $dt_view_row_inv["blength_min"];
														} else {
															$blength = $dt_view_row_inv["blength_min"] . " - " . $dt_view_row_inv["blength_max"];
														}
														if ($dt_view_row_inv["bwidth_min"] == $dt_view_row_inv["bwidth_max"]) {
															$bwidth = $dt_view_row_inv["bwidth_min"];
														} else {
															$bwidth = $dt_view_row_inv["bwidth_min"] . " - " . $dt_view_row_inv["bwidth_max"];
														}
														if ($dt_view_row_inv["bheight_min"] == $dt_view_row_inv["bheight_max"]) {
															$bdepth = $dt_view_row_inv["bheight_min"];
														} else {
															$bdepth = $dt_view_row_inv["bheight_min"] . " - " . $dt_view_row_inv["bheight_max"];
														}
													} else {

														$blength = $dt_view_row_inv["lengthInch"];
														$bwidth = $dt_view_row_inv["widthInch"];
														$bdepth = $dt_view_row_inv["depthInch"];
													}
													$blength_frac = 0;
													$bwidth_frac = 0;
													$bdepth_frac = 0;

													$length = $blength;
													$width = $bwidth;
													$depth = $bdepth;

													if ($dt_view_row_inv["lengthFraction"] != "") {
														$arr_length = explode("/", $dt_view_row_inv["lengthFraction"]);
														if (count($arr_length) > 0) {
															$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
															$length = floatval($blength + $blength_frac);
														}
													}
													if ($dt_view_row_inv["widthFraction"] != "") {
														$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
														if (count($arr_width) > 0) {
															$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
															$width = floatval($bwidth + $bwidth_frac);
														}
													}

													if ($dt_view_row_inv["depthFraction"] != "") {
														$arr_depth = explode("/", $dt_view_row_inv["depthFraction"]);
														if (count($arr_depth) > 0) {
															$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
															$depth = floatval($bdepth + $bdepth_frac);
														}
													}

													//
													$b2b_status = $dt_view_row["b2b_status"];

													$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
													//echo $st_query;
													$st_res = db_query($st_query);
													$st_row = array_shift($st_res);
													$b2bstatus_nametmp = $st_row["box_status"];

													if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
														$b2bstatuscolor = "green";
													} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
														$b2bstatuscolor = "orange";
													}

													if ($dt_view_row_inv["buy_now_load_can_ship_in"] == "Ask Purch Rep") {
														$estimated_next_load = "Ask Rep";
													} else {
														$estimated_next_load = $dt_view_row_inv["buy_now_load_can_ship_in"];
													}

													if ($dt_view_row_inv["box_urgent"] == 1) {
														$b2bstatuscolor = "red";
														$b2bstatus_nametmp = "URGENT";
													}

													$shipfrom_comp = "";
													$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
													$dt_view = db_query($qry_loc);
													$shipfrom_city = "";
													$shipfrom_zip = "";
													$shipfrom_state = "";
													while ($loc_res = array_shift($dt_view)) {
														if ($loc_res["box_warehouse_id"] == "238") {
															$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
															$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
															db_b2b();
															$get_loc_res = db_query($get_loc_qry);
															$loc_row = array_shift($get_loc_res);
															$shipfrom_comp = $loc_row["nickname"];
															$shipfrom_city = $loc_row["shipCity"];
															$shipfrom_state = $loc_row["shipState"];
															$shipfrom_zip = $loc_row["shipZip"];
														} else {

															$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
															$get_loc_qry = "Select * from loop_warehouse where id = '" . $vendor_b2b_rescue_id . "'";
															$get_loc_res = db_query($get_loc_qry);
															$loc_row = array_shift($get_loc_res);
															$shipfrom_comp = get_nickname_val($loc_row["company_name"], $loc_row["b2bid"]);
															$shipfrom_city = $loc_row["company_city"];
															$shipfrom_state = $loc_row["company_state"];
															$shipfrom_zip = $loc_row["company_zip"];
														}
													}
													$ship_from_tmp  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
													$ship_from2_tmp = $shipfrom_state;
													//
													$miles_from = "";
													if ($onlyftl_mode == "no") {
														$miles_from = (int) (6371 * $distC * .621371192);
													}
													$miles_away_color = "";
													if ($miles_from <= 250) {	//echo "chk gr <br/>";
														$miles_away_color = "green";
													}
													if (($miles_from <= 550) && ($miles_from > 250)) {
														$miles_away_color = "#FF9933";
													}
													if (($miles_from > 550)) {
														$miles_away_color = "red";
													}

													$b_urgent = "No";
													$contracted = "No";
													$prepay = "No";
													$ship_ltl = "No";
													if ($dt_view_row_inv["box_urgent"] == 1) {
														$b_urgent = "Yes";
													}
													if ($dt_view_row_inv["contracted"] == 1) {
														$contracted = "Yes";
													}
													if ($dt_view_row_inv["prepay"] == 1) {
														$prepay = "Yes";
													}
													if ($dt_view_row_inv["ship_ltl"] == 1) {
														$ship_ltl = "Yes";
													}

													$box_sub_type = "";
													$q1 = "SELECT sub_type_name FROM loop_boxes_sub_type_master where unqid = '" . $dt_view_row_inv["box_sub_type"] . "'";
													$query = db_query($q1);
													while ($fetch = array_shift($query)) {
														$box_sub_type = $fetch['sub_type_name'];
													}

													$box_description = "";
													if ($dt_view_row_inv["uniform_mixed_load"] == "Uniform") {
														$box_description = "$boxtype_txt: " . $dt_view_row_inv["bwall"] . "ply - " . $box_sub_type . " (ID " . $dt_view_row_inv["ID"] . ")";
													} else {
														$wall_str = "";
														if ($dt_view_row_inv["bwall_min"] == $dt_view_row_inv["bwall_max"]) {
															$wall_str = $dt_view_row_inv["bwall_min"];
														} else {
															$wall_str = $dt_view_row_inv["bwall_min"] . "-" . $dt_view_row_inv["bwall_max"];
														}
														$box_description = "$boxtype_txt: " . $wall_str . "ply - " . $box_sub_type . " (ID " . $dt_view_row_inv["ID"] . ")";
													}

													$tipStr = "<b>Contracted:</b> " . $contracted . "<br>";
													$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
													$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
													$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
													$tipStr .= "<b>Min Profit:</b> " . ($b2b_fob_org - $b2b_cost_org) . "<br>";
													$tipStr .= "<b>Min Margin:</b> " . round((($b2b_fob_org - $b2b_cost_org) / ($b2b_fob_org)) * 100) . "%<br>";
													$tipStr .= "<b>UCB Owned Inventory </b><br>";

													$pallet_space_per = "";
													$tmpTDstr_new = "";
													if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "1") {
														$b2b_fob_main = str_replace(",", "", $b2b_fob);
														$b2b_fob_main = str_replace("$", "", $b2b_fob_main);

														$tmpTDstr_new = "<tr>";
														$tmpTDstr_new .= "<td rowspan='5' align='center' width='180px;' bgColorrepl>
														<a target='_blank' href='https://b2b.usedcardboardboxes.com/?id=" . urlencode(encrypt_password($dt_view_row["trans_id"])) . "'>
														<img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/" . $bpic_1 . "' width='140px' height='140px;' ";

														if (isset($_REQUEST["fntend"]) && $_REQUEST["fntend"] == "boomerang") {
															$tmpTDstr_new .= " style='object-fit: cover; max-height:120px;'/> ";
														} else {
															$tmpTDstr_new .= "style='object-fit: cover;'/> ";
														}
														$tmpTDstr_new .= "</a></td>";

														$tmpTDstr_new .= "<td colspan='5' bgColorrepl >";

														if (isset($_REQUEST["fntend"]) && $_REQUEST["fntend"] == "boomerang") {
															$tmpTDstr_new .= "<a href='javascript:void();'>";
														} else {
															$tmpTDstr_new .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $dt_view_row["trans_id"] . "&proc=View&'";
															$tmpTDstr_new .= " onmouseover=\"Tipnew('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTipnew()\">";
														}

														$tmpTDstr_new .= "<font size='4'>";
														$tmpTDstr_new .= $box_description . "</font></a></td>";
														$tmpTDstr_new .= "<td colspan='4' align='center' bgColorrepl><font color='$miles_away_color'>" . $miles_from . " mi away</td>";
														$tmpTDstr_new .= "<td style='background:#cee7c3;' width='150px;' align='center'><font size='4'><b>" . $b2b_fob . "</b></font><br>
														<a href='#' style='color:blue !important;' onClick='calculate_delivery(" . $b2bid_tmp . "," . $_REQUEST["ID"] . "," . $b2b_fob_main . ");  return false;' >Calculate Delivery (FTL)</a></td>";
														$tmpTDstr_new .= "</tr>";

														$tmpTDstr_new .= "<tr>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_status.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Status:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'><font color='" . $b2bstatuscolor . "'>" . $b2bstatus_nametmp . "</font></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_truck.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='100px;'><b>Can Ship LTL?</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $ship_cdata_ltl . "</td>";
														$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
														$tmpTDstr_new .= "<td width='150px;' bgColorrepl>&nbsp;</td>";
														$tmpTDstr_new .= "</tr>";

														$tmpTDstr_new .= "<tr>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_Location.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Ship From:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $ship_from_tmp . " (" . $shipfrom_comp . ")</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/can_customer_pickup.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Can Customer Pickup?</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $pickup_cdata_allowed . "</td>";
														$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#cee7c3;'>";

														if (isset($_REQUEST["fntend"]) && $_REQUEST["fntend"] == "boomerang") {
															$tmpTDstr_new .= "$no_of_loads Loads";
														} else {
															$tmpTDstr_new .= "<a target='_blank' href='manage_box_next_load_available_date.php?id=" . $b2bid_tmp . "&inview=yes'>$no_of_loads Loads</a>";
														}
														$tmpTDstr_new .= "</td>";

														$tmpTDstr_new .= "</tr>";

														$tmpTDstr_new .= "<tr>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_Dimensions.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Description:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $system_description . "<br>" . $quantity_per_pallet . "/pallet, " . $quantity . "/load " . $dt_view_row["additional_description_text"] . "</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_supplier_owner.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Supplier Owner?</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='50px;'>" . $ownername . "</td>";
														$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#cee7c3;' ><u>Ships in $shipsinweek week</u></td>";
														$tmpTDstr_new .= "</tr>";

														$tmpTDstr_new .= "<tr>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'><img alt='' src='https://loops.usedcardboardboxes.com/matching_tool_icons/Icon_notes.png' style='width:15px;height:15px;'/></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='20px;'><b>Sales Notes:</b></td>";
														$tmpTDstr_new .= "<td bgColorrepl width='400px;'>" . $inv_notes . "</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='5px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl width='10px;'>&nbsp;</td>";
														$tmpTDstr_new .= "<td bgColorrepl colspan='2' width='20px;'>" . $notes_upd_date_str . "</td>";
														$tmpTDstr_new .= "<td width='5px;' bgColorrepl >&nbsp;</td>";
														$tmpTDstr_new .= "<td width='150px;' align='center' style='background:#99c796;' ><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $dt_view_row_inv["ID"] . ")'><font size='4'><b>Add to Cart</b></font></a></td>";
														$tmpTDstr_new .= "</tr>";

														$tmpTDstr_new .= "<tr><td colspan='10'>&nbsp;</td></tr>";

														if ($actual_po < 0) {
															$qty_avail =  "<font color=blue>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
														} else {
															$qty_avail =  "<font color=green>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
														}
														$fav_b2bid = $dt_view_row_inv["ID"];
														?>

														<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
														<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
														<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["expected_loads_per_mo"] ?>">
														<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer_tmp ?>">
														<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
														<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
														<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[0] ?>">
														<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[1] ?>">
														<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[2] ?>">
														<?php
														if ($dt_view_row_inv["uniform_mixed_load"] == "Mixed") { ?>
															<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall_min"] . "-" . $dt_view_row_inv["bwall_max"]; ?>">
														<?php } else { ?>
															<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall"] ?>">
														<?php } ?>

														<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row["Description"] ?>">
														<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2_tmp ?>">
														<?php

														$tmpTDstr = "<tr  >";
														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a href='javascript:void(0)' onclick='addgaylord(" . $_REQUEST["ID"] . "," . $b2bid_tmp . ")'>";
														$tmpTDstr =  $tmpTDstr . "Add</font></a></td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >";

														$font_blue = "";
														$font_blue1 = "";
														if ($actual_po < 0) {
															$font_blue = "<font color='blue'>";
															$font_blue1 = "</font>";

															$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
														} else if ($actual_po >= $boxes_per_trailer_tmp) {
															$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
														}

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $font_blue . $estimated_next_load . $font_blue1 . "</td>";

														if ((float)str_replace(",", "", $boxes_per_trailer_tmp) <> 0) {
															$expected_loads_per_mo_for_3m = round((float)str_replace(",", "", $qty_avail_3month1) / (float)str_replace(",", "", $boxes_per_trailer_tmp), 2);
														} else {
															$expected_loads_per_mo_for_3m = 0;
														}
														if ($qty_avail_3month1 == 0 && $expected_loads_per_mo_for_3m == 0) {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $font_blue . $qty_avail_3month1 . $font_blue1 . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $font_blue . $qty_avail_3month1 . " (" . $expected_loads_per_mo_for_3m . ")" . $font_blue1 . "</td>";
														}

														if ($annual_volume == 0 && $annual_volume_total_load == 0) {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $annual_volume . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $annual_volume . " (" . $annual_volume_total_load . ")</td>";
														}

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . number_format($boxes_per_trailer_tmp, 0) . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $b2b_fob . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $dt_view_row_inv["ID"] . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='" . $b2bstatuscolor . "'>" . $b2bstatus_nametmp . "</td>";

														$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
														$boxsize = explode("x", $btemp);
														//
														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $length . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $width . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $depth . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . $dt_view_row["trans_id"] . "&proc=View&'";
														$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tipnew('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTipnew()\"";

														//echo " >" ;
														$tmpTDstr =  $tmpTDstr . " >";

														$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</a></td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $vendor_name . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from_tmp . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_cdata_ltl . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $pickup_cdata_allowed . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ownername . "</td>";

														$tmpTDstr =  $tmpTDstr . "</tr>";
													}
													//----------------------------------------------------------------
													//Display customer view
													if (isset($_REQUEST["display_view"]) && $_REQUEST["display_view"] == "2") {
														$tmpTDstr = "<tr  >";
														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>";

														$font_blue = "";
														$font_blue1 = "";
														if ($actual_po < 0) {
															$font_blue = "<font color='blue'>";
															$font_blue1 = "</font>";
															$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
														} else if ($after_po_val == 0 && $dt_view_row_inv["expected_loads_per_mo"] == 0) {
															$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($after_po_val, 0) . "</font></td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "</td>";
														}

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $font_blue . $estimated_next_load . $font_blue1 . "</td>";
														if ((float)str_replace(",", "", $boxes_per_trailer_tmp) <> 0) {
															$expected_loads_per_mo_for_3m = round((float)str_replace(",", "", $qty_avail_3month1) / (float)str_replace(",", "", $boxes_per_trailer_tmp), 2);
														} else {
															$expected_loads_per_mo_for_3m = 0;
														}

														if ($qty_avail_3month1 == 0 && $expected_loads_per_mo_for_3m == 0) {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $font_blue . $qty_avail_3month1 . $font_blue1 . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $font_blue . $qty_avail_3month1 . " (" . $expected_loads_per_mo_for_3m . ")" . $font_blue1 . "</td>";
														}

														if ($annual_volume == 0 && $annual_volume_total_load == 0) {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $annual_volume . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl >" . $annual_volume . " (" . $annual_volume_total_load . ")</td>";
														}

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . number_format($boxes_per_trailer_tmp, 0) . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $b2b_fob . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $dt_view_row_inv["ID"] . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><font color='$miles_away_color'>" . $miles_from . "</td>";

														//
														$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
														$boxsize = explode("x", $btemp);
														//
														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[0] . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[1] . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>x</td>";

														$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $boxsize[2] . "</td>";

														if ($dt_view_row_inv["uniform_mixed_load"] == "Mixed") {
															$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $dt_view_row_inv["bwall_min"] . "-" . $dt_view_row_inv["bwall_max"] . "</td>";
														} else {
															$tmpTDstr =  $tmpTDstr . "<td align='center' bgColorrepl>" . $dt_view_row_inv["bwall"] . "</td>";
														}

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . "<div ";
														$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tipnew('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTipnew()\"";

														$tmpTDstr =  $tmpTDstr . " >";

														$tmpTDstr =  $tmpTDstr . $dt_view_row["Description"] . "</div></td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_from2_tmp . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $ship_cdata_ltl . "</td>";

														$tmpTDstr =  $tmpTDstr . "<td bgColorrepl>" . $pickup_cdata_allowed . "</td>";

														if ($_REQUEST["client_flg"] == 1) {
															if ($actual_po < 0) {
																$qty_avail =  "<font color=blue>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
															} else {
																$qty_avail =  "<font color=green>" . number_format($actual_po, 0) . $pallet_val_afterpo . $preorder_txt2 . "";
															}
															$fav_b2bid = $dt_view_row_inv["ID"];
														?>

															<input type="hidden" name="fav_qty_avail" id="fav_qty_avail<?php echo $fav_b2bid; ?>" value="<?php echo $qty_avail ?>">
															<input type="hidden" name="fav_estimated_next_load" id="fav_estimated_next_load<?php echo $fav_b2bid; ?>" value="<?php echo $estimated_next_load ?>">
															<input type="hidden" name="fav_expected_loads_per_mo" id="fav_expected_loads_per_mo<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["expected_loads_per_mo"] ?>">
															<input type="hidden" name="fav_boxes_per_trailer" id="fav_boxes_per_trailer<?php echo $fav_b2bid; ?>" value="<?php echo $boxes_per_trailer_tmp ?>">
															<input type="hidden" name="fav_fob" id="fav_fob<?php echo $fav_b2bid; ?>" value="<?php echo $b2b_fob ?>">
															<input type="hidden" name="fav_b2bid" id="fav_b2bid<?php echo $fav_b2bid; ?>" value="<?php echo $fav_b2bid ?>">
															<input type="hidden" name="fav_bl" id="fav_bl<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[0] ?>">
															<input type="hidden" name="fav_bw" id="fav_bw<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[1] ?>">
															<input type="hidden" name="fav_bh" id="fav_bh<?php echo $fav_b2bid; ?>" value="<?php echo $boxsize[2] ?>">
															<?php
															if ($dt_view_row_inv["uniform_mixed_load"] == "Mixed") { ?>
																<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall_min"] . "-" . $dt_view_row_inv["bwall_max"]; ?>">
															<?php } else { ?>
																<input type="hidden" name="fav_walls" id="fav_walls<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row_inv["bwall"] ?>">
															<?php } ?>

															<input type="hidden" name="fav_desc" id="fav_desc<?php echo $fav_b2bid; ?>" value="<?php echo $dt_view_row["Description"] ?>">
															<input type="hidden" name="fav_shipfrom" id="fav_shipfrom<?php echo $fav_b2bid; ?>" value="<?php echo $ship_from2_tmp ?>">
						<?php
															//
															$btype = "";
															$tmpTDstr =  $tmpTDstr . "<td bgColorrepl><a id='div_favourite" . $fav_b2bid . "' href='javascript:void(0);' onClick='add_item_as_favorite(" . $fav_b2bid . ",$btype)' >Add</a></td>";
														}


														$tmpTDstr =  $tmpTDstr . "</tr>";
													}

													$mileage = "";
													if ($onlyftl_mode == "no") {
														$mileage = (int) (6371 * $distC * .621371192);
													}

													$MGArray[] = array('arrorder' => $mileage, 'arrdet' => $tmpTDstr_new, 'box_urgent' => $dt_view_row_inv["box_urgent"]);
												} //to_show_rec end
											} //to_show_rec1 ends
										}
									}
								} // dt_view_row while ends	
							}
							$whileLoopIteration++;
						} //gaylord // end while $qgb

						if (isset($_REQUEST["sort_g_location"]) && ($_REQUEST["sort_g_location"] == "2" || $_REQUEST["sort_g_location"] == "3" || $_REQUEST["sort_g_location"] == "4")) {

							//echo "arrCombineItemView array :  <pre>"; print_r($arrCombineItemView); echo "</pre><br/>";
							if ($_REQUEST["sort_g_location"] == "4") {
								foreach ($arrCombineItemView as $arrCombineItemViewKey => $arrCombineItemViewVal) {
									$tmpTDstr1 = "<tr bgColorrepl >";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['qty'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['estimated_next_load'] . "</td>";
									//$tmpTDstr1 =  $tmpTDstr1 . "<td>".$arrCombineItemViewVal['expected_loads_per_mo']. "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['qty_avail_3month'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['annual_volume'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['boxes_per_trailer'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['b2b_fob'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['I'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td><font color='" . $arrCombineItemViewVal['miles_away_color'] . "'>" . $arrCombineItemViewVal['miles_from'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['length'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td align='center'>x</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['width'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td align='center'>x</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td align='center' width='40px'>" . $arrCombineItemViewVal['depth'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td> " . $arrCombineItemViewVal['bwall'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td> " . $arrCombineItemViewVal['description'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['ship_from2'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['ship_cdata_ltl'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "<td>" . $arrCombineItemViewVal['pickup_cdata_allowed'] . "</td>";
									$tmpTDstr1 =  $tmpTDstr1 . "</tr>";

									$mileage1 = (int) (6371 * $arrCombineItemViewVal['distC'] * .621371192);
									$MGArray1[] = array('arrorder' => $mileage1, 'arrdet' => $tmpTDstr1, 'box_urgent' => $arrCombineItemViewVal['box_urgent']);
								}
							}

							$newLocArr = array();
							foreach ($arrCombineItemView as $k => $v) {
								$newLocArr[$v['vendor_b2b_rescue_id']][] = $v;
							}
							//echo "<pre>"; print_r($newLocArr); echo "</pre>";
							foreach ($newLocArr as $newLocArrKey => $newLocArrValue) {

								$shipFrom 				= $newLocArrValue[0]['ship_from'];
								$vendor_b2b_rescue_id 	= $newLocArrValue[0]['vendor_b2b_rescue_id'];
								$ReqId 					= $newLocArrValue[0]['ReqId'];
								$ID 					= $newLocArrValue[0]['ID'];
								$vendor_name 			= $newLocArrValue[0]['vendor_name'];
								$miles_away_color 		= $newLocArrValue[0]['miles_away_color'];
								$bwall 					= $newLocArrValue[0]['bwall'];
								$miles_from 			= $newLocArrValue[0]['miles_from'];
								$b2bstatuscolor_internal = "";
								$arrENL = array();
								$pickup_cdata_allowed = "";
								$ship_cdata_ltl = "";
								if (count($newLocArrValue) > 1) {
									$shipFrom 				= $newLocArrValue[0]['ship_from'];
									$vendor_b2b_rescue_id 	= $newLocArrValue[0]['vendor_b2b_rescue_id'];
									$ReqId 					= $newLocArrValue[0]['ReqId'];
									$ID 					= $newLocArrValue[0]['ID'];
									$qty 	= $expected_loads_per_mo = $annual_volume	= $expected_loads_per_mo_cal = 0;
									$I 	= '';

									$arrBPT = array();
									$arrFOB = array();
									$arrL 	= array();
									$arrW 	= array();
									$arrD 	= array();
									$arrShip = array();
									$arrLeadTime = array();
									$arrOwner = $arrB2bStatus = $arrb2bstatuscolor_internal = array();
									$arrTest = array();
									$estimated_next_load = 0;
									$expected_loads_per_mo = 0;
									$qty_avail_3month = 0;
									for ($i = 0; $i < count($newLocArrValue); $i++) {
										if ($newLocArrValue[$i]['qty'] > 0) {
											$qty = $qty + str_replace(',', '', $newLocArrValue[$i]['qty']);
										}
										//$qty = $qty + str_replace( ',', '', $newLocArrValue[$i]['qty']);
										$expected_loads_per_mo 	= (int)$expected_loads_per_mo + (int)$newLocArrValue[$i]['expected_loads_per_mo'];
										$qty_avail_3month = number_format($qty_avail_3month + (float)$newLocArrValue[$i]['qty_avail_3month'], 0);
										$annual_volume = $annual_volume + str_replace(",", "", $newLocArrValue[$i]['annual_volume']);
										$annual_volume_total_load = " (" . $newLocArrValue[$i]['expected_loads_per_mo'] * 12 . ")";
										if ($i > 0) {
											$I	= $I . "." . $newLocArrValue[$i]['I'];
											$estimated_next_load = $estimated_next_load . "|" . $newLocArrValue[$i]['estimated_next_load'];
										} else {
											$I	= $newLocArrValue[0]['I'];
											$estimated_next_load = $newLocArrValue[0]['estimated_next_load'];
										}
										$arrBPT[$i] = $newLocArrValue[$i]['boxes_per_trailer'];
										$arrFOB[$i] = str_replace('$', '', $newLocArrValue[$i]['b2b_fob']);
										$arrL[$i] 	= $newLocArrValue[$i]['length'];
										$arrW[$i] 	= $newLocArrValue[$i]['width'];
										$arrD[$i] 	= $newLocArrValue[$i]['depth'];
										$arrOwner[$i] = $newLocArrValue[$i]['ownername'];
										$arrB2bStatus[$i] = $newLocArrValue[$i]['b2bstatus_name'];
										$arrb2bstatuscolor_internal[$i] = $newLocArrValue[$i]['b2bstatuscolor'];

										$arrENL[$i] = $newLocArrValue[$i]['estimated_next_load'];
										/******calculating D70 cell start set all values in array******/
										$tmpqty = str_replace(',', '', $newLocArrValue[$i]['qty']);
										$tmpqty = (float)$tmpqty;
										if ($tmpqty >= 0) {
											//if($newLocArrValue[$i]['expected_loads_per_mo'] > 0 ){ 
											/**FORMULA PART**Just like A/T1, B/T2, C/T3 ******/
											$arrShip[$newLocArrValue[$i]['I']]  = (float)str_replace(',', '', $newLocArrValue[$i]['qty']) / (float)str_replace(',', '', $newLocArrValue[$i]['boxes_per_trailer']);
											$expected_loads_per_mo_cal 	= $expected_loads_per_mo_cal + $newLocArrValue[$i]['expected_loads_per_mo'];
											$arrLeadTime[$newLocArrValue[$i]['I']] = $newLocArrValue[$i]['lead_time'];
										} else {
											$arrTest[$i] = $newLocArrValue[$i]['estimated_next_load'];
										}
										/******calculating D70 cell end set all values in array******/
									}
									$boxes_per_trailer 	= min($arrBPT) . " - " . max($arrBPT);

									$b2b_fob = "$" . min($arrFOB) . " - $" . max($arrFOB);

									if (min($arrL) == max($arrL)) {
										$length = min($arrL);
									} else {
										$length = min($arrL) . " - " . max($arrL);
									}

									if (min($arrW) == max($arrW)) {
										$width = min($arrW);
									} else {
										$width = min($arrW) . " - " . max($arrW);
									}

									if (min($arrD) == max($arrD)) {
										$depth = min($arrD);
									} else {
										$depth = min($arrD) . " - " . max($arrD);
									}

									$ownername = implode(',', array_unique($arrOwner));
									$b2bStatus = implode(' | ', array_unique($arrB2bStatus));
									$b2bstatuscolor_internal = implode(' | ', array_unique($arrb2bstatuscolor_internal));
								} else {
									$qty = 0;
									$I 						= $newLocArrValue[0]['I'];
									if ($newLocArrValue[0]['qty'] > 0) {
										$qty				= str_replace(',', '', $newLocArrValue[0]['qty']);
									}
									$expected_loads_per_mo 	= $newLocArrValue[0]['expected_loads_per_mo'];
									$qty_avail_3month = $newLocArrValue[0]['qty_avail_3month'];
									$annual_volume = $newLocArrValue[0]['annual_volume'];
									$annual_volume_total_load = " (" . $newLocArrValue[0]['expected_loads_per_mo'] * 12 . ")";
									$estimated_next_load 	= $newLocArrValue[0]['estimated_next_load'];
									$ownername 				= $newLocArrValue[0]['ownername'];
									$boxes_per_trailer 		= $newLocArrValue[0]['boxes_per_trailer'];
									$b2b_fob 				= $newLocArrValue[0]['b2b_fob'];
									$length 				= $newLocArrValue[0]['length'];
									$width 					= $newLocArrValue[0]['width'];
									$depth 					= $newLocArrValue[0]['depth'];
									$b2bstatuscolor_internal = $newLocArrValue[0]['b2bstatuscolor'];
									$b2bStatus 				= $newLocArrValue[0]['b2bstatus_name'];
									$ship_cdata_ltl	= $newLocArrValue[0]['ship_cdata_ltl'];
									$pickup_cdata_allowed	= $newLocArrValue[0]['pickup_cdata_allowed'];
								}

								//echo "<br /><br /><br />".$vendor_b2b_rescue_id."<br />arrENL => <pre>"; print_r($arrENL); echo "</pre>";
								if (count($newLocArrValue) > 1) {
									$tempENLDays = $arrENLTemp = $tempENLDaysW = $tempENLDaysD = array();
									foreach ($arrENL as $arrENLKey => $arrENLValue) {
										//echo "arrENLValue => <pre>"; print_r($arrENLValue); echo "</pre>";
										$arrENLTemp = explode(' ', $arrENLValue);

										//echo "<br />arrENLTemp => <pre>"; print_r($arrENLTemp); echo "</pre>";
										if ($arrENLTemp[1] == 'Weeks' || $arrENLTemp[1] == 'Week') {
											$tempENLDays[$arrENLKey] = (float)$arrENLTemp[0] * 7;
										} elseif ($arrENLTemp[3] == 'Days' || $arrENLTemp[3] == 'Day') {
											$tempENLDays[$arrENLKey] = $arrENLTemp[2];
										}
									}

									//echo "<br />tempENLDays => <pre>"; print_r($tempENLDays); echo "</pre>";
									$daysTempKeyVal = array_keys($tempENLDays, min($tempENLDays));
									//echo "<br />daysTempKeyVal => <pre>"; print_r($daysTempKeyVal); echo "</pre>";
									$loadCanShip =  $arrENL[$daysTempKeyVal[0]];
								} else {
									$loadCanShip = $estimated_next_load;
								}
								$tmpTDstr2 = "";
								if ($_REQUEST["sort_g_location"] == "2") {
									$tmpTDstr2 = "<tr bgColorrepl id='selrow" . $vendor_b2b_rescue_id . "'>";
									/*$tmpTDstr2 =  $tmpTDstr2 . "<td><a href='javascript:void(0)' onclick='addgaylordMultiple(".$ReqId . "," .$I. ")'>";
						$tmpTDstr2 =  $tmpTDstr2 . "Add  </font></a></td>";*/
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($qty, 0) . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $loadCanShip . "</td>";
									//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$expected_loads_per_mo. "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $qty_avail_3month . "</td>";
									if ($annual_volume == 0 && $annual_volume_total_load == 0) {
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $annual_volume . "</td>";
									} else {
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $annual_volume . " " . $annual_volume_total_load . "</td>";
									}

									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $boxes_per_trailer . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $b2b_fob . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $b2bstatuscolor_internal . "'>" . $b2bStatus . "</font></td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $length . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $width . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $depth . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td><a name='location_details_show' id='loc_btn_" . $vendor_b2b_rescue_id . "' onClick='show_loc_dtls(" . $vendor_b2b_rescue_id . "," . count($newLocArrValue) . "," . $_REQUEST["sort_g_location"] . " )' class='ex_col_btn' >" . $shipFrom . "</a></td>";

									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $ship_cdata_ltl . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $pickup_cdata_allowed . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $ownername . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

									$childRow = 1;
									for ($i = 0; $i < count($newLocArrValue); $i++) {
										if ($childRow % 2 == 0) {
											$classChild = 'display_table_alt_child';
										} else {
											$classChild = 'display_table_child';
										}
										$tmpTDstr2 =  $tmpTDstr2 . "<tr class='" . $classChild . "' id='loc_sub_table_" . $i . "_" . $vendor_b2b_rescue_id . "' style='display: none;'>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td><a href='javascript:void(0)' onclick='addgaylordMultiple(" . $newLocArrValue[$i]['ReqId'] . "," . $newLocArrValue[$i]['I'] . ")'>";
										$tmpTDstr2 =  $tmpTDstr2 . "Add </font></a></td>";
										$font_blue = "";
										$font_blue1 = "";
										if ($newLocArrValue[$i]['after_po_val'] < 0) {
											$font_blue = "<font color='blue'>";
											$font_blue1 = "</font>";
											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='blue'>" . $newLocArrValue[$i]['qty'] . "</td>";
										} else if ($newLocArrValue[$i]['after_po_val'] >= $newLocArrValue[$i]['boxes_per_trailer']) {
											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='green'>" . $newLocArrValue[$i]['qty'] . "</td>";
										} else {
											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='black'>" . $newLocArrValue[$i]['qty'] . "</td>";
										}
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $font_blue . $newLocArrValue[$i]['estimated_next_load'] . $font_blue1 . "</td>";
										//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$newLocArrValue[$i]['expected_loads_per_mo']."</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $font_blue . $newLocArrValue[$i]['qty_avail_3month'] . $font_blue1 . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['annual_volume'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($newLocArrValue[$i]['boxes_per_trailer'], 0) . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['b2b_fob'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['I'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['miles_away_color'] . "'>" . $newLocArrValue[$i]['miles_from'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['b2bstatuscolor'] . "'>" . $newLocArrValue[$i]['b2bstatus_name'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['length'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['width'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['depth'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($newLocArrValue[$i]['I']) . "&proc=View&'";
										$tmpTDstr2 =  $tmpTDstr2 . " onmouseover=\"Tipnew('" . str_replace("'", "\'", $newLocArrValue[$i]['tipStr']) . "')\" onmouseout=\"UnTipnew()\"";
										$tmpTDstr2 =  $tmpTDstr2 . " >";
										$tmpTDstr2 =  $tmpTDstr2 . $newLocArrValue[$i]['description'] . "</a></td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['vendor_name'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ship_from'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ownername'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

										$childRow++;
									}
								}

								//if($_REQUEST["sort_g_location"]=="3"){
								if ($_REQUEST["display_view"] == "2") {
									$tmpTDstr2 = "<tr bgColorrepl id='selrow" . $vendor_b2b_rescue_id . "'>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($qty, 0) . "</td>";
									//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$estimated_next_load."</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $loadCanShip . "</td>";
									//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$expected_loads_per_mo."</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $qty_avail_3month . "</td>";
									if ($annual_volume == 0 && $annual_volume_total_load == 0) {
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $annual_volume . "</td>";
									} else {
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $annual_volume . " " . $annual_volume_total_load . "</td>";
									}

									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $boxes_per_trailer . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $miles_away_color . "'>" . $miles_from . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $length . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $width . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $depth . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $bwall . "</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td>&nbsp;</td>";
									$tmpTDstr2 =  $tmpTDstr2 . "<td><a name='location_details_show' id='loc_btn_" . $vendor_b2b_rescue_id . "' onClick='show_loc_dtls(" . $vendor_b2b_rescue_id . "," . count($newLocArrValue) . "," . $_REQUEST["sort_g_location"] . " )' class='ex_col_btn' >" . $shipFrom . "</a></td>";
									$tmpTDstr2 =  $tmpTDstr2 . "</tr>";
									$childRow = 1;
									for ($i = 0; $i < count($newLocArrValue); $i++) {
										if ($childRow % 2 == 0) {
											$classChild = 'display_table_alt_child';
										} else {
											$classChild = 'display_table_child';
										}
										$tmpTDstr2 =  $tmpTDstr2 . "<tr class='" . $classChild . "' id='loc_sub_table_" . $i . "_" . $vendor_b2b_rescue_id . "' style='display: none;'>";
										$font_blue = "";
										$font_blue1 = "";
										if ($newLocArrValue[$i]['after_po_val'] < 0) {
											$font_blue = "<font color='blue'>";
											$font_blue1 = "</font>";

											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='blue'>" . $newLocArrValue[$i]['qty'] . "</td>";
										} else if ($newLocArrValue[$i]['after_po_val'] >= $newLocArrValue[$i]['boxes_per_trailer']) {
											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='green'>" . $newLocArrValue[$i]['qty'] . "</td>";
										} else {
											$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='black'>" . $newLocArrValue[$i]['qty'] . "</td>";
										}
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $font_blue . $newLocArrValue[$i]['estimated_next_load'] . $font_blue1 . "</td>";
										//$tmpTDstr2 =  $tmpTDstr2 . "<td>".$newLocArrValue[$i]['expected_loads_per_mo']."</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $font_blue . $newLocArrValue[$i]['qty_avail_3month'] . $font_blue1 . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . number_format($newLocArrValue[$i]['boxes_per_trailer'], 0) . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['b2b_fob'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['I'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td><font color='" . $newLocArrValue[$i]['miles_away_color'] . "'>" . $newLocArrValue[$i]['miles_from'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['length'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['width'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center'>x</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td align='center' width='40px'>" . $newLocArrValue[$i]['depth'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['bwall'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['description'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ship_from2'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['ship_cdata_ltl'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "<td>" . $newLocArrValue[$i]['pickup_cdata_allowed'] . "</td>";
										$tmpTDstr2 =  $tmpTDstr2 . "</tr>";

										$childRow++;
									}
								}

								$mileage2 = (int) (6371 * $newLocArrValue[0]['distC'] * .621371192);
								$MGArray2[] = array('arrorder' => $mileage2, 'arrdet' => $tmpTDstr2, 'box_urgent' => $newLocArrValue[0]['box_urgent']);
								//$k++;
							} // close foreach newLocArr



						}

						// Added by Mooneem Jul-13-12 to Bring the green thread at top	
						// Sort the Array based on Mileage	
						$MGArraysort = array();

						if ($_REQUEST["showonly_box"] == 1) {

							$MGArraysort_I = array();
							$MGArraysort_II = array();
							foreach ($MGArray_new as $MGArraytmp) {
								$MGArraysort_I[] = $MGArraytmp['sub_type'];
								$MGArraysort_II[] = $MGArraytmp['territory'];
							}
							array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArray_new);
						} else {
							foreach ($MGArray_new as $MGArraytmp) {
								$MGArraysort[] = $MGArraytmp['arrorder'];
							}

							array_multisort($MGArraysort, SORT_NUMERIC, $MGArray_new);
						}
						$MGArraysort_1 = array();
						$MGArraysort_2 = array();
						foreach ($MGArray1 as $MGArraytmp_1) {
							$MGArraysort_1[] = $MGArraytmp_1['arrorder'];
						}

						foreach ($MGArray2 as $MGArraytmp_2) {
							$MGArraysort_2[] = $MGArraytmp_2['arrorder'];
						}
						//print_r($MGArray);

						array_multisort($MGArraysort_1, SORT_NUMERIC, $MGArray1);

						array_multisort($MGArraysort_2, SORT_NUMERIC, $MGArray2);
						?>
						<?php
						//ffe3c0 display_table_expand_v display_table_expand_v_alt
						$x = 0;
						$bg = "#e4e4e4";
						foreach ($MGArray_new as $MGArraytmp2) {
							if ($x == 0) {
								$x = 1;
								$bg = "#e4e4e4";
								$bgstyle = "display_table";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle = "display_table_expand_v";
								}
							} else {
								$x = 0;
								$bg = "#f4f4f4";
								$bgstyle = "display_table_alt";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle = "display_table_expand_v_alt";
								}
							}

							echo preg_replace("/bgColorrepl/", "class=$bgstyle", $MGArraytmp2['arrdet']);
						}
						$x_1 = 0;
						foreach ($MGArray1 as $MGArraytmp2_1) {
							if ($x_1 == 0) {
								$x_1 = 1;
								$bg_1 = "#e4e4e4";
								$bgstyle_1 = "display_table";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle_1 = "display_table_expand_v";
								}
							} else {
								$x_1 = 0;
								$bg_1 = "#f4f4f4";
								$bgstyle_1 = "display_table_alt";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle_1 = "display_table_expand_v_alt";
								}
							}
							echo preg_replace("/bgColorrepl/", "class=$bgstyle_1", $MGArraytmp2_1['arrdet']);
						}
						$x_2 = 0;
						foreach ($MGArray2 as $MGArraytmp2_2) {
							if ($x_2 == 0) {
								$x_2 = 1;
								$bg_2 = "#e4e4e4";
								$bgstyle_2 = "display_table";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle_2 = "display_table_expand_v";
								}
							} else {
								$x_2 = 0;
								$bg_2 = "#f4f4f4";
								$bgstyle_2 = "display_table_alt";
								if ($_REQUEST["view_child"] == 1) {
									$bgstyle_2 = "display_table_expand_v_alt";
								}
							}
							echo preg_replace("/bgColorrepl/", "class=$bgstyle_2", $MGArraytmp2_2['arrdet']);
						}
						?>
				<?php
					} //end if num>0 qnumrows
				}
				?>

			<?php }

		//total_summary Total Availability: X of Y Loads
		//$_REQUEST["showonly_box"] == 1 ||
		if ($_REQUEST["view_child"] == 1 || $_REQUEST["othertha_gaylord"] == "yes" || $_REQUEST["dashpg"] == "dash") {
		} else {
			?>
				<div style="text-align:center; font-size:18;"><b> <?php echo "Total Availability: $gr_no_of_loads of $gr_total_no_of_loads Loads"; ?></b> </div>
			<?php
		} ?>

			<?php if ($_REQUEST["showonly_box"] == 1 && $_REQUEST["dashpg"] == 1) { ?>
				<tr>
					<td class="display_table_alt" rowspan="4" colspan="4">&nbsp;</td>
					<td class="display_table_alt" width='485px;' style="font-size:18;" colspan="5" align="right"><b>Total Loads Available:</b></td>
					<td class="display_table_alt" width='5px;'>&nbsp;</td>
					<td width='150px' align='center' style="text-align:center; font-size:18;background:#cee7c3"><b><?php echo $gr_no_of_loads; ?></b></td>
				</tr>
				<tr>
					<td class="display_table_alt" width='485px;' style="font-size:18;" colspan="5" align="right"><b>Total Loads:</b></td>
					<td class="display_table_alt" width='5px;'>&nbsp;</td>
					<td width='150px' align='center' style="text-align:center; font-size:18;background:#cee7c3"><b><?php echo $gr_total_no_of_loads; ?></b></td>
				</tr>
				<tr>
					<td class="display_table_alt" width='485px;' style="font-size:18;" colspan="5" align="right"><b>Total Min Value of the Available Loads (Min FOB):</b></td>
					<td class="display_table_alt" width='5px;'>&nbsp;</td>
					<td width='150px' align='center' style="text-align:center; font-size:18;background:#cee7c3"><b>$<?php echo number_format($gr_avl_load_cost, 0); ?></b></td>
				</tr>
				<tr>
					<td class="display_table_alt" width='485px;' style="font-size:18;" colspan="5" align="right"><b>Total Min Value of All Loads (Min FOB):</b></td>
					<td class="display_table_alt" width='5px;'>&nbsp;</td>
					<td width='150px' align='center' style="text-align:center; font-size:18;background:#cee7c3"><b>$<?php echo number_format($gr_all_load_cost, 0); ?></b></td>
				</tr>

			<?php } ?>
			</table>
			</div>
			<!-- 
</body>
</html> -->
