<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>DASH - Search Results</title>
	<script>
		function displayemail(id) {
			document.getElementById("email_light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;
			document.getElementById('email_light').style.display = 'block';
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
		function fun_badadd_send_email(order_id) {
			selectobject = document.getElementById("btnsend_badadd_eml");
			var n_left = f_getPosition(selectobject, 'Left');
			var n_top = f_getPosition(selectobject, 'Top');
			document.getElementById('light').style.left = 300 + 'px';
			document.getElementById('light').style.top = n_top + 30 + 'px';
			document.getElementById('light').style.width = 900 + 'px';
			document.getElementById('light').style.height = 500 + 'px';

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

					document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" + xmlhttp.responseText;

					document.getElementById('light').style.display = 'block';

				}
			}
			xmlhttp.open("POST", "badadd_send_email.php?order_id=" + order_id, true);
			xmlhttp.send();
		}


		function badadd_send_eml() {
			var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;
			tmp_element1 = document.getElementById("txtemailto").value;
			tmp_element2 = document.getElementById("frm_badadd_email");
			tmp_element4 = document.getElementById("txtemailsubject").value;
			tmp_element5 = document.getElementById("hidden_reply_eml");

			if (tmp_element1.value == "") {
				alert("Please enter the To Email address.");
				return false;
			}

			if (tmp_element4.value == "") {
				alert("Please enter the Email Subject.");
				return false;
			}

			var inst = FCKeditorAPI.GetInstance("txtemailbody");
			var emailtext = inst.GetHTML();

			tmp_element5.value = emailtext;
			//alert(tmp_element5.value);
			document.getElementById("hidden_sendemail").value = "inemailmode";
			tmp_element2.submit();
		}

		function order_cancel_labels(orders_id, orid) {
			if (window.confirm('Do you really want to cancel the label?')) {
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (xmlhttp.responseText == "yes") {
							alert('Label will get cancelled in DASH, but NOT in FedEx Ship Manager. If label is already printed, you must \n 1. Also Delete it in FedEx Ship Manager AND \n 2. Tell the local Manager to destroy the label and not send out the kit for it. \n 3. Fedex Status is marked Ignore.')
							location.reload();
							}
						//document.getElementById("ord_trac").innerHTML = xmlhttp.responseText; 
					}
				}

				xmlhttp.open("GET", "cancel_order_label.php?orders_id=" + orders_id + "&orid=" + orid + "&cancel_flg=yes", true);
				xmlhttp.send();

			} else {
				die();
			}
		}
	</script>
</head>

<body>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<?php
		echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
		echo "<Font Face='arial' size='2'>";
		$sql_debug_mode = 0;
		$thispage	= 'orders.php';
		$pagevars	= ""; //Storage Location for anything wierd David wants to include
		$allowedit		= "no";
		$allowaddnew	= "no";
		$allowview		= "yes";
		$allowdelete	= "no";
		$addl_select_crit = "";
		$addl_update_crit = "";
		$addl_insert_crit = "";
		$addl_insert_values = "";
		$addslash = "yes";
		$cb_id = "";
		require("securedata/main-enc-class.php");
		$proc = $_REQUEST['proc'];
		if ($proc == "") {
			if ($allowaddnew == "yes") {
		?>
				<a href="<?php echo $thispage; ?>?proc=New&<?php echo $pagevars; ?>">New Record</a><br>
			<?php  }
			?>

			<br>

			<?php
			if ($_REQUEST['posting'] == "yes") {
				$pagenorecords = 500;  //THIS IS THE PAGE SIZE
				//IF NO PAGE
				$myrecstart = 0;
				$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
				if ($page == 0) {
					$myrecstart = 0;
				} else {
					$myrecstart = ($page * $pagenorecords);
				}

				$searchcrit = isset($_REQUEST['searchcrit']) ? $_REQUEST['searchcrit'] : "";
				$flag = "";
				$sqlwhere = "";
				if ($searchcrit == "") {
					$flag = "all";
					$sql = "SELECT * FROM orders";
					$sqlcount = "select count(*) as reccount from orders";
				} else {
					$t_sql_1 = "SELECT orders_id FROM orders_active_export WHERE tracking_number LIKE '%$searchcrit%'";
					$t_sql_1_res = db_query($t_sql_1);
					$t_sql_1_res_count = tep_db_num_rows($t_sql_1_res);
					if ($t_sql_1_res_count != 0) {
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$searchcrit = $t_sql_1_row["orders_id"];
						}
					}
					//
					$searchcrit_num = str_replace(' ', '', $searchcrit);
					$searchcrit_num = rtrim($searchcrit_num, ",");
					// Retrieving each selected option 
					$searchcrit_arr = explode(",", $searchcrit_num);
					//echo "searchcrit_num - " . $searchcrit_num . "<br>";
					$searchcrit_total = count($searchcrit_arr);
					//echo $searchcrit_total;
					if ($searchcrit_total >= 1) {
						$search_val1 = ""; $search_val = "";
						foreach ($searchcrit_arr as $ubox_val) {
							//$search_val.= " ubox_order_tracking_number like '%$ubox_val%' or ";
							$search_val .= " LOCATE('$ubox_val',ubox_order_tracking_number), ";
							$search_val1 .= " LOCATE('$ubox_val',ubox_order_tracking_number)>0 or ";
						}
						$search_num1 = rtrim($search_val, ", ");
						$search_num = rtrim($search_val1, "or ");

						$filter_tacking_num = " OR (" . $search_num . ")";
					}
					//
					$sqlcount = "select count(*) as reccount from orders WHERE (";
					$sql = "SELECT *," . $search_num1 . " FROM orders WHERE (";
					$sqlwhere = "
					orders_id like '%$searchcrit%' OR 
					shopify_order_display_no like '%$searchcrit%' OR 
					customers_name like '%$searchcrit%' OR 
					replace(replace(replace(replace(REPLACE(customers_telephone, '-', ''), '(',''), ')',''), ' ',''), '+','') like '%$searchcrit%' OR 
					customers_email_address like '%$searchcrit%' OR 
					date_purchased like '%$searchcrit%' 
					$filter_tacking_num
					"; //FINISH SQL STRING
				} //END IF SEARCHCRIT = "";

				$tracking_number = "";
				if ($flag == "all") {
					$sql = $sql . " WHERE (1=1) ORDER by orders_id DESC $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sqlcount = $sqlcount  . " WHERE (1=1) $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				} else {
					$sqlcount = $sqlcount . $sqlwhere . ") $addl_select_crit LIMIT $myrecstart, $pagenorecords";
					$sql = $sql . $sqlwhere . ") ORDER by orders_id DESC $addl_select_crit LIMIT $myrecstart, $pagenorecords";
				}
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				//SET PAGE
				if ($page == 0) {
					$page = 1;
				} else {
					$page = ($page + 1);
				}

				$reccount = isset($_REQUEST['reccount']) ? $_REQUEST['reccount'] : 0;
				if ($reccount == 0) {
					$resultcount = (db_query($sqlcount));
					//if ($myrowcount = array_shift($resultcount)) 
					while ($myrowcount = array_shift($resultcount)) {
						$reccount = $myrowcount["reccount"];
					} //IF RECCOUNT = 0
				} //end if reccount
				echo "<DIV CLASS='CURR_PAGE'>Page $page - $reccount Records Found</DIV>";
				if ($reccount > 500) {
					$ttlpages = ($reccount / 500);
					if ($page < $ttlpages) {
			?>
					<?php
					} //END IF AT LAST PAGE
				} //END IF RECCOUNT > 10
				$newpage = 0;
				if ($page > 0) {
					$newpage = $page - 2;
				}
				if ($newpage != -1) {
					?>
					<?php

				} //IF NEWPAGE != -1
				$result = db_query($sql);
				if ($sql_debug_mode == 1) {
					echo "<BR>SQL: $sql<BR>";
				}
				if ($myrowsel = array_shift($result)) {
					$id = $myrowsel["orders_id"];
					echo "";
					echo "<TABLE WIDTH='100%'>";
					echo "	<tr align='middle'><td colspan='14' class='style24' style='height: 16px'><strong>ORDER SEARCH RESULTS</strong></td></tr>";
					echo "	<TR>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Shopify Order ID</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer Total</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Payment Method</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer IP Address</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>";
					echo "		<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>";
					echo "\n\n		</TR>";
					do {
						//FORMAT THE OUTPUT OF THE SEARCH
						$id = $myrowsel["orders_id"];
						//SWITCH ROW COLORS
						$shade = "TBL_ROW_DATA_LIGHT";
						switch ($shade) {
							case "TBL_ROW_DATA_LIGHT":
								$shade = "TBL_ROW_DATA_DRK";
								break;
							case "TBL_ROW_DATA_DRK":
								$shade = "TBL_ROW_DATA_LIGHT";
								break;
							default:
								$shade = "TBL_ROW_DATA_DRK";
								break;
						} //end switch shade

						$order_amount = 0;
						$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " . $id;
						$t_sql_1_res = db_query($t_sql_1);
						while ($t_sql_1_row = array_shift($t_sql_1_res)) {
							$order_amount = number_format($t_sql_1_row["value"], 2);
						}

						echo "<TR>";
					?>
						<?php $orders_id = $myrowsel["orders_id"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<a href="<?php echo $thispage; ?>?id=<?php echo encrypt_url($id); ?>&proc=View&searchcrit=<?php echo $searchcrit; ?>&page=<?php if ($page > 0) {
																																							$newpage = $page - 1;
																																							echo $newpage;
																																						} ?><?php echo $pagevars; ?>"><?php echo $orders_id; ?></a>
						</TD>
						<TD CLASS='<?php echo $shade; ?>'>
							<a target="_blank" href="https://usedcardboardboxes.myshopify.com/admin/orders/<?php echo $myrowsel["shopify_order_no"]; ?>?orderListBeta=true"><?php echo $myrowsel["shopify_order_display_no"]; ?></a>
						</TD>
						<TD CLASS='<?php echo $shade; ?>'>$<?php echo $order_amount; ?></td>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php
							if ($myrowsel["payment_method"] != "Paypal") {
								echo "Credit Card";
							} else {
								echo "Paypal";
							}
							?></td>

						<TD CLASS='<?php echo $shade; ?>'><?php $order_date = date("F j, Y", strtotime($myrowsel["date_purchased"]));
															echo $order_date; ?></td>
						<?php $user_ipaddress = $myrowsel["user_ipaddress"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php
							if ($user_ipaddress != "") {
								echo "<a href='https://whatismyipaddress.com/ip/" . $user_ipaddress . "' target='_blank'>" . $user_ipaddress . "</a>";
							}
							?>
						</TD>
						<?php $customers_name = $myrowsel["customers_name"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_name; ?>
						</TD>
						<?php $customers_street_address = $myrowsel["delivery_street_address"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address; ?>
						</TD>
						<?php $customers_street_address2 = $myrowsel["delivery_street_address2"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_street_address2; ?>
						</TD>
						<?php $customers_city = $myrowsel["delivery_city"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_city; ?>
						</TD>
						<?php $customers_state = $myrowsel["delivery_state"]; ?>
						<TD CLASS='<?php echo $shade; ?>'>
							<?php echo $customers_state; ?>
						</TD>
						<?php $customers_postcode = $myrowsel["delivery_postcode"]; ?>
						<?php $billing_postcode = $myrowsel["billing_postcode"]; ?>
						<?php if ($customers_postcode == $billing_postcode) { ?>
							<!-------- TO CHECK BILLING ZIP FOR FRAUD --------->
							<TD CLASS='<?php echo $shade; ?>'>
							<?php  } else { ?>
							<TD bgcolor=red>
							<?php  } ?>
							<?php echo $customers_postcode; ?>
							</TD>
							<?php $customers_telephone = $myrowsel["customers_telephone"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_telephone; ?>
							</TD>
							<?php $customers_email_address = $myrowsel["customers_email_address"]; ?>
							<TD CLASS='<?php echo $shade; ?>'>
								<?php echo $customers_email_address; ?>
							</TD>
							</TR>
							<?php
						} while ($myrowsel = array_shift($result));
						echo "</TABLE>";
						/*----------------- PAGING LINK - THIS IS USED FOR NEXT/PREVIOUS X RECORDS --------------------------*/
						if ($reccount > 10) {
							//IF THERE ARE MORE THAN 10 RECORDS PAGING
							$ttlpages = ($reccount / 10);
							if ($page < $ttlpages) {
							?>
								<HR> <br>
								<A HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $page; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">Next <?php echo $pagenorecords; ?> Records >></a> &nbsp; &nbsp;
							<?php
							} //END IF AT LAST PAGE
						} //END IF RECCOUNT > 10
						//PREVIOUS RECORDS LINK
						$newpage = 0;
						if ($page > 0) {
							$newpage = $page - 2;
						}
						if ($newpage != -1) {
							?>
							<A HREF="<?php echo $thispage; ?>?posting=yes&page=<?php echo $newpage; ?>&reccount=<?php echo $reccount; ?>&searchcrit=<?php echo $searchcrit; ?>&<?php echo $pagevars; ?>">
								<< Previous <?php echo $pagenorecords; ?> Records</a>
									<br>
					<?php
						} //IF NEWPAGE != -1
					} //END PROC == ""
				} //END IF POSTING = YES
			} // END IF PROC = ""

					?>


					<?php
					if ($proc == "View") { ?>
						<br>
						<?php
						//view of order table
						require_once('orders_view.php');
					} // END IF PROC = "VIEW"
					?>
	</div>
</body>

</html>