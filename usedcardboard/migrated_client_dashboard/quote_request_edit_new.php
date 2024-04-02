<?php
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}
} else {
	require("inc/header_session_client.php");
}

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();
//
function timestamp_to_date(string $d): string
{
	$da = explode(" ", $d);
	$dp = explode("-", $da[0]);
	return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}
function timestamp_to_datetime(string $d): string
{
	$da = explode(" ", $d);
	$dp = explode("-", $da[0]);
	$dh = explode(":", $da[1]);

	$x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

	if ($dh[0] == 12) {
		$x = $x . " " . ((float)$dh[0] - 0) . ":" . $dh[1] . "PM CT";
	} elseif ($dh[0] == 0) {
		$x = $x . " 12:" . $dh[1] . "AM CT";
	} elseif ($dh[0] > 12) {
		$x = $x . " " . ((float)$dh[0] - 12) . ":" . $dh[1] . "PM CT";
	} else {
		$x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
	}

	return $x;
}
function date_diff_new(string $start, string $end = "NOW"): string
{
	$sdate = strtotime($start);
	$edate = strtotime($end);

	$time = $edate - $sdate;
	$timeshift = "";
	if ($time >= 0 && $time <= 59) {
		// Seconds
		$timeshift = $time . ' seconds ';
	} elseif ($time >= 60 && $time <= 3599) {
		// Minutes + Seconds
		$pmin = ($edate - $sdate) / 60;
		$premin = explode('.', strval($pmin));

		$presec = $pmin - (float)$premin[0];
		$sec = $presec * 60;

		$timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
	} elseif ($time >= 3600 && $time <= 86399) {
		// Hours + Minutes
		$phour = ($edate - $sdate) / 3600;
		$prehour = explode('.', strval($phour));

		$premin = $phour - (float)$prehour[0];
		$min = explode('.', strval($premin * 60));

		$presec = '0.' . $min[1];
		$sec = (float)$presec * 60;

		$timeshift = $prehour[0] . ' hrs ' . $min[0] . ' min ' . round($sec, 0) . ' sec ';
	} elseif ($time >= 86400) {
		// Days + Hours + Minutes
		$pday = ($edate - $sdate) / 86400;
		$preday = explode('.', strval($pday));

		$phour = $pday - (float)$preday[0];
		$prehour = explode('.', strval($phour * 24));

		$premin = ($phour * 24) - (float)$prehour[0];
		$min = explode('.', strval($premin * 60));

		$presec = '0.' . $min[1];
		$sec = (float)$presec * 60;

		$timeshift = $preday[0];
	}
	return $timeshift;
}

$subheading = "#b6d4a4";
$rowcolor1 = "#e4e4e4";
$rowcolor2 = "#ececec";
//$buttonrow="#d5d5d5";
$buttonrow = "#ccd9e7";

if (isset($_REQUEST["editquotedata"])) {
	if ($_REQUEST["editquotedata"] == 1) {
		if ($_REQUEST["p"] == "g") {
			$company_id = $_REQUEST["company_id"];
			$tableid = $_REQUEST["tableid"];
			$table_name = $_REQUEST["g"];
			$quote_item = $_REQUEST["quote_item"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];
			//
			$getrecquery = "Select * from quote_gaylord where id='" . $tableid . "'";
			$g_res = db_query($getrecquery);
			$g_data = array_shift($g_res);
			$g_item_length = $g_data["g_item_length"];
			$g_item_width = $g_data["g_item_width"];
			$g_item_height = $g_data["g_item_height"];
			//
			$g_item_min_height = $g_data["g_item_min_height"];
			$g_item_max_height = $g_data["g_item_max_height"];
			//
			$g_shape_rectangular = $g_data["g_shape_rectangular"];
			$g_shape_octagonal = $g_data["g_shape_octagonal"];
			//
			$g_wall_1 = $g_data["g_wall_1"];
			$g_wall_2 = $g_data["g_wall_2"];
			$g_wall_3 = $g_data["g_wall_3"];
			$g_wall_4 = $g_data["g_wall_4"];
			$g_wall_5 = $g_data["g_wall_5"];
			$g_wall_6 = $g_data["g_wall_6"];
			$g_wall_7 = $g_data["g_wall_7"];
			$g_wall_8 = $g_data["g_wall_8"];
			$g_wall_9 = $g_data["g_wall_9"];
			$g_wall_10 = $g_data["g_wall_10"];
			//
			$g_no_top = $g_data["g_no_top"];
			$g_lid_top = $g_data["g_lid_top"];
			$g_partial_flap_top = $g_data["g_partial_flap_top"];
			$g_full_flap_top = $g_data["g_full_flap_top"];
			//
			$g_no_bottom_config = $g_data["g_no_bottom_config"];
			$g_partial_flap_w = $g_data["g_partial_flap_w"];
			$g_tray_bottom = $g_data["g_tray_bottom"];
			$g_full_flap_bottom = $g_data["g_full_flap_bottom"];
			$g_partial_flap_wo = $g_data["g_partial_flap_wo"];
			//
			$g_vents_okay = $g_data["g_vents_okay"];
			//
			$g_quantity_request = $g_data["g_quantity_request"];
			$g_other_quantity = $g_data["g_other_quantity"];
			//
			$g_frequency_order = $g_data["g_frequency_order"];
			$g_what_used_for = $g_data["g_what_used_for"];
			$need_pallets = $g_data["need_pallets"];
			$date_needed_by = $g_data["date_needed_by"];
			$g_item_note = $g_data["g_item_note"];
			$quotereq_sales_flag = $g_data["g_quotereq_sales_flag"];

			$sales_desired_price_g = $g_data["sales_desired_price_g"];
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			$g_qut_id = $g_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$g_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;
					$g_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $g_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$g_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$g_no_of_quote_sent = rtrim($g_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$g_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $g_no_of_quote_sent;
					} else {
						$g_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}
			//
			db();
			//
			echo '<form name="frm1_g">';
			//
?>
			<div id="g<?php echo $tableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<tr>
						<td style="background:#91bb78; padding:5px;" colspan="4">
							<table cellpadding="3">
								<tr>
									<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $g_data["quote_id"]; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">
										Item Type: <?php echo $quote_item_name; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;">
										<font face="Roboto" size="1" color="#91bb78">
											<img id="g_btn_img<?php echo $tableid ?>" src="images/minus_icon.png" />&nbsp;<a name="details_btn" id="g_btn<?php echo $tableid ?>" onClick="show_g_details(<?php echo $tableid ?>)" class="ex_col_btn boxProSubHeading">Collapse Details</a>
											<!-- <input type="button" name="details_btn" id="g_btn<?php //=$tableid
																									?>" value="Collapse Details" onClick="show_g_details(<?php //=$tableid
																																							?>)">-->
										</font>
									</td>
									<td style="background:#91bb78;">
										<!-- <font face="Roboto" size="1" color="#91bb78">
                                    <img bgcolor="#91bb78"  src="images/del_img.png" onclick="g_quote_delete(<?php echo $tableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;"> </font> -->
										<input type="hidden" name="tableid" value="<?php echo $tableid ?>">
										<input type="hidden" name="updatequotedata" value="updatequotedata">
										<input type="hidden" id="quote_item<?php echo $tableid ?>" value="<?php echo $quote_item ?>">
										<input type="hidden" id="company_id<?php echo $tableid ?>" value="<?php echo $company_id ?>">
										<input type="hidden" id="client_dash_flg<?php echo $tableid ?>" value="<?php echo $client_dash_flg ?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<div id="g_sub_table<?php echo $tableid ?>">
								<table width="100%" class="in_table_style tableBorder" cellpadding="3" cellspacing="1">

									<tr bgcolor="<?php echo $subheading; ?>">
										<td colspan="6">
											<strong>What Do They Buy?</strong>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td width="200px">
											Item <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What commodity does this company want to buy</span>
											</div>
										</td>
										<td colspan="5">
											Gaylord Totes
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td bgColor="#e4e4e4">Ideal Size (in) <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span>
											</div>
										</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">L</span><br>
												<input type="text" name="g_item_length<?php echo $tableid ?>" id="g_item_length<?php echo $tableid ?>" size="5" value="<?php echo $g_item_length ?>" class="size_txt_center">
											</div>
										</td>
										<td width="20px" align="center">x</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">W</span><br>
												<input type="text" name="g_item_width<?php echo $tableid ?>" id="g_item_width<?php echo $tableid ?>" size="5" value="<?php echo $g_item_width ?>" class="size_txt_center">
											</div>
										</td>
										<td width="20px" align="center">x</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">H</span><br>
												<input type="text" name="g_item_height<?php echo $tableid ?>" id="g_item_height<?php echo $tableid ?>" size="5" value="<?php echo $g_item_height ?>" class="size_txt_center">
											</div>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Quantity Requested <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How much of this item do they order at a time</span>
											</div>
										</td>
										<td colspan=5>
											<select id="g_quantity_request<?php echo $tableid ?>" onchange="show_otherqty_text_edit(this, <?php echo $tableid ?>)">
												<option <?php if ($g_quantity_request == "Select One") { ?> selected <?php } else {
																													} ?>>Select One</option>
												<option <?php if ($g_quantity_request == "Full Truckload") { ?> selected <?php } else {
																														} ?>>Full Truckload</option>
												<option <?php if ($g_quantity_request == "Half Truckload") { ?> selected <?php } else {
																														} ?>>Half Truckload</option>
												<option <?php if ($g_quantity_request == "Quarter Truckload") { ?> selected <?php } else {
																														} ?>>Quarter Truckload</option>
												<option <?php if ($g_quantity_request == "Other") { ?> selected <?php } else {
																											} ?>>Other</option>
											</select>
											<br>
											<?php
											if ($g_quantity_request == "Other") {
											?>
												<input type="text" name="g_other_quantity<?php echo $tableid ?>" id="g_other_quantity<?php echo $tableid ?>" size="10" value="<?php echo $g_other_quantity; ?>">
											<?php
											} else {
											?>
												<input type="text" name="g_other_quantity<?php echo $tableid ?>" id="g_other_quantity<?php echo $tableid ?>" size="10" style="display:none;">
											<?php
											}
											?>

										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Frequency of Order <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How often do they order this item</span>
											</div>
										</td>
										<td colspan=5>
											<select id="g_frequency_order<?php echo $tableid ?>">
												<option <?php if ($g_frequency_order == "Select One") { ?> selected <?php } else {
																												} ?>>Select One</option>
												<option <?php if ($g_frequency_order == "Multiple per Week") { ?> selected <?php } else {
																														} ?>>Multiple per Week</option>
												<option <?php if ($g_frequency_order == "Multiple per Month") { ?> selected <?php } else {
																														} ?>>Multiple per Month</option>
												<option <?php if ($g_frequency_order == "Once per Month") { ?> selected <?php } else {
																													} ?>>Once per Month</option>
												<option <?php if ($g_frequency_order == "Multiple per Year") { ?> selected <?php } else {
																														} ?>>Multiple per Year</option>
												<option <?php if ($g_frequency_order == "Once per Year") { ?> selected <?php } else {
																													} ?>>Once per Year</option>
												<option <?php if ($g_frequency_order == "One-Time Purchase") { ?> selected <?php } else {
																														} ?>>One-Time Purchase</option>
											</select>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											What Used For? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What do they put in this item, how much weight is going in it?</span>
											</div>
										</td>
										<td colspan=5>
											<input type="text" id="g_what_used_for<?php echo $tableid ?>" value="<?php echo $g_what_used_for ?>">
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Desired Price
											<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
										</td>
										<td colspan=5>
											$ <input type="text" name="sales_desired_price_g<?php echo $tableid ?>" id="sales_desired_price_g<?php echo $tableid ?>" value="<?php if ($sales_desired_price_g > 0) {
																																												echo $sales_desired_price_g;
																																											} else {
																																												echo '0';
																																											}  ?>" onchange="setTwoNumberDecimal(this)">
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Notes <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span>
											</div>
										</td>
										<td colspan=5>
											<textarea id="g_item_note<?php echo $tableid ?>"><?php echo $g_item_note; ?></textarea>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td colspan="6">
											<strong>Criteria of what they SHOULD be able to use:<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i>
													<span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span>
												</div></strong>
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td><!-- align="right"-->
											Height Flexibility
										</td>
										<td align="center">
											<span class="label_txt">Min</span>
											<br>
											<input type="text" class="size_txt_center" name="g_item_min_height<?php echo $tableid ?>" id="g_item_min_height<?php echo $tableid ?>" value="<?php echo $g_item_min_height ?>" size="5">
										</td>
										<td align="center">-</td>
										<td align="center">
											<span class="label_txt">Max</span>
											</br>
											<input type="text" class="size_txt_center" name="g_item_max_height<?php echo $tableid ?>" id="g_item_max_height<?php echo $tableid ?>" value="<?php echo $g_item_max_height ?>" size="5">
										</td>
										<td align="center" colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Shape
										</td>
										<td>
											Rectangular
										</td>
										<td>
											<input type="checkbox" id="g_shape_rectangular<?php echo $tableid ?>" value="Yes" <?php if ($g_shape_rectangular == "Yes") {
																																	echo "Checked";
																																} ?>>
										</td>
										<td>
											Octagonal
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_shape_octagonal<?php echo $tableid ?>" value="Yes" <?php if ($g_shape_octagonal == "Yes") {
																																echo "Checked";
																															} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td rowspan="5">
											# of Walls
										</td>
										<td>
											1ply
										</td>
										<td>
											<input type="checkbox" id="g_wall_1<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_1 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
										<td>
											6ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_wall_6<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_6 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											2ply
										</td>
										<td>
											<input type="checkbox" id="g_wall_2<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_2 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
										<td>
											7ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_wall_7<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_7 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											3ply
										</td>
										<td>
											<input type="checkbox" id="g_wall_3<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_3 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
										<td>
											8ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_wall_8<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_8 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											4ply
										</td>
										<td>
											<input type="checkbox" id="g_wall_4<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_4 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
										<td>
											9ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_wall_9<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_9 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											5ply
										</td>
										<td>
											<input type="checkbox" id="g_wall_5<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_5 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
										<td>
											10ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="g_wall_10<?php echo $tableid ?>" value="Yes" <?php if ($g_wall_10 == "Yes") {
																														echo "Checked";
																													} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td rowspan="2">
											Top Config
										</td>
										<td>
											No Top
										</td>
										<td>
											<input name="g_no_top<?php echo $tableid ?>" type="checkbox" id="g_no_top<?php echo $tableid ?>" value="Yes" <?php if ($g_no_top == "Yes") {
																																								echo "Checked";
																																							} ?>>
										</td>
										<td>
											Lid Top
										</td>
										<td colspan="2">
											<input name="g_lid_top<?php echo $tableid ?>" type="checkbox" id="g_lid_top<?php echo $tableid ?>" value="Yes" <?php if ($g_lid_top == "Yes") {
																																								echo "Checked";
																																							} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Partial Flap Top
										</td>
										<td>
											<input name="g_partial_flap_top<?php echo $tableid ?>" type="checkbox" id="g_partial_flap_top<?php echo $tableid ?>" value="Yes" <?php if ($g_partial_flap_top == "Yes") {
																																													echo "Checked";
																																												} ?>>
										</td>
										<td>
											Full Flap Top
										</td>
										<td colspan="2">
											<input name="g_full_flap_top<?php echo $tableid ?>" type="checkbox" id="g_full_flap_top<?php echo $tableid ?>" value="Yes" <?php if ($g_full_flap_top == "Yes") {
																																											echo "Checked";
																																										} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td rowspan="3">
											Bottom Config
										</td>
										<td>
											No Bottom
										</td>
										<td>
											<input name="g_no_bottom_config<?php echo $tableid ?>" type="checkbox" id="g_no_bottom_config<?php echo $tableid ?>" value="Yes" <?php if ($g_no_bottom_config == "Yes") {
																																													echo "Checked";
																																												} ?>>
										</td>
										<td>
											Partial Flap w/ Slipsheet
										</td>
										<td colspan="2">
											<input name="g_partial_flap_w<?php echo $tableid ?>" type="checkbox" id="g_partial_flap_w<?php echo $tableid ?>" value="Yes" <?php if ($g_partial_flap_w == "Yes") {
																																												echo "Checked";
																																											} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Tray Bottom
										</td>
										<td>
											<input name="g_tray_bottom<?php echo $tableid ?>" type="checkbox" id="g_tray_bottom<?php echo $tableid ?>" value="Yes" <?php if ($g_tray_bottom == "Yes") {
																																										echo "Checked";
																																									} ?>>
										</td>
										<td>
											Full Flap Bottom
										</td>
										<td colspan="2">
											<input name="g_full_flap_bottom<?php echo $tableid ?>" type="checkbox" id="g_full_flap_bottom<?php echo $tableid ?>" value="Yes" <?php if ($g_full_flap_bottom == "Yes") {
																																													echo "Checked";
																																												} ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Partial Flap w/o SlipSheet
										</td>
										<td colspan="4">
											<input name="g_partial_flap_wo<?php echo $tableid ?>" type="checkbox" id="g_partial_flap_wo<?php echo $tableid ?>" value="Yes" <?php if ($g_partial_flap_wo == "Yes") {
																																												echo "Checked";
																																											} ?>>
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Vents Okay?
										</td>
										<td colspan=5>
											<input type="checkbox" name="g_vents_okay<?php echo $tableid ?>" id="g_vents_okay<?php echo $tableid ?>" value="Yes" <?php if ($g_vents_okay == "Yes") {
																																										echo "Checked";
																																									} ?>>
										</td>
									</tr>
									<tr align="center" bgcolor="<?php echo $subheading; ?>">
										<td colspan="6" align="center">
											<input type="button" name="g_item_submit" value="Update" onclick="quote_update(<?php echo $tableid ?>)" style="cursor: pointer;">
											<input type="button" name="g_item_cancel" value="Cancel" onclick="quote_cancel(<?php echo $tableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>

							</div>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			echo "</form>";
			//
			//
		} //End check parameter P g
		//
		if ($_REQUEST["p"] == "sb") {
			$company_id = $_REQUEST["company_id"];
			$stableid = $_REQUEST["stableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];
			//
			$getrecquery = "Select * from quote_shipping_boxes where id='" . $stableid . "'";
			$sb_res = db_query($getrecquery);
			$sb_data = array_shift($sb_res);
			//
			$sb_item_length = $sb_data["sb_item_length"];
			$sb_item_width = $sb_data["sb_item_width"];
			$sb_item_height = $sb_data["sb_item_height"];
			$sb_item_min_length = $sb_data["sb_item_min_length"];
			$sb_item_max_length = $sb_data["sb_item_max_length"];
			$sb_item_min_width = $sb_data["sb_item_min_width"];
			$sb_item_max_width = $sb_data["sb_item_max_width"];
			$sb_item_min_height = $sb_data["sb_item_min_height"];
			$sb_item_max_height = $sb_data["sb_item_max_height"];
			$sb_cubic_footage_min = $sb_data["sb_cubic_footage_min"];
			$sb_cubic_footage_max = $sb_data["sb_cubic_footage_max"];
			$sb_quantity_requested = $sb_data["sb_quantity_requested"];
			//
			$sb_other_quantity = $sb_data["sb_other_quantity"];
			$sb_frequency_order = $sb_data["sb_frequency_order"];
			$sb_what_used_for = $sb_data["sb_what_used_for"];
			$sb_date_needed_by = $sb_data["sb_date_needed_by"];
			$sb_need_pallets = $sb_data["sb_need_pallets"];
			$sb_wall_1 = $sb_data["sb_wall_1"];
			$sb_wall_2 = $sb_data["sb_wall_2"];
			$sb_no_top = $sb_data["sb_no_top"];
			$sb_full_flap_top = $sb_data["sb_full_flap_top"];
			$sb_partial_flap_top = $sb_data["sb_partial_flap_top"];
			$sb_no_bottom = $sb_data["sb_no_bottom"];
			$sb_full_flap_bottom = $sb_data["sb_full_flap_bottom"];
			$sb_partial_flap_bottom = $sb_data["sb_partial_flap_bottom"];
			$sb_vents_okay = $sb_data["sb_vents_okay"];
			$sb_quotereq_sales_flag = $sb_data["sb_quotereq_sales_flag"];
			$sb_notes = $sb_data["sb_notes"];

			$sb_sales_desired_price = $sb_data["sb_sales_desired_price"];
			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//---------------check quote send or not-------------------------------
			$sb_qut_id = $sb_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$sb_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;
					$sb_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $sb_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$sb_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$sb_no_of_quote_sent = rtrim($sb_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$sb_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $sb_no_of_quote_sent;
					} else {
						$sb_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}

			//
			db();
			//
			//
			echo '<form name="rptSearchsb" id="rptSearchsb">';
			//
		?>
			<div id="sb<?php echo $stableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<td style="background:#91bb78; padding:5px;" colspan="4">
						<table cellpadding="3">
							<tr>
								<td class="boxProSubHeading" style="background:#91bb78;">
									Box Profile ID: <?php echo $sb_data["quote_id"]; ?>
								</td>
								<td class="boxProSubHeading" style="background:#91bb78;" width="200px">
									Item Type: <?php echo $quote_item_name; ?>
								</td>
								<td class="boxProSubHeading" style="background:#91bb78;">
									<font face="Roboto" size="1" color="#91bb78">
										<img id="sb_btn_img<?php echo $stableid ?>" src="images/minus_icon.png" />&nbsp;<a name="details_btn" id="sb_btn<?php echo $stableid ?>" onClick="show_sb_details(<?php echo $stableid ?>)" class="ex_col_btn boxProSubHeading">Collapse Details</a>
										<!-- <input type="button" name="details_btn" id="sb_btn<?php //=$stableid
																								?>" value="Collapse Details" onClick="show_sb_details(<?php //=$stableid
																																						?>)">-->
									</font>
								</td>
								<td style="background:#91bb78;">
									<!--  <font face="Roboto" size="1" color="#91bb78">
                                        <img bgcolor="#91bb78"  src="images/del_img.png" onclick="sb_quote_delete(<?php echo $stableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
                                    </font> -->
									<input type="hidden" name="stableid" value="<?php echo $stableid ?>">
									<input type="hidden" name="sbupdatequotedata" value="sbupdatequotedata">
									<input type="hidden" id="quote_item<?php echo $stableid ?>" value="<?php echo $quote_item ?>">
									<input type="hidden" id="company_id<?php echo $stableid ?>" value="<?php echo $company_id ?>">
									<input type="hidden" id="client_dash_flg<?php echo $stableid ?>" value="<?php echo $client_dash_flg ?>">
								</td>
							</tr>
						</table>
					</td>
					</tr>
					<tr>
						<td>
							<div id="sb_sub_table<?php echo $stableid ?>">
								<table width="100%" class="in_table_style tableBorder" cellpadding="3" cellspacing="1">
									<tr bgcolor="<?php echo $subheading; ?>">
										<td colspan="6">
											<strong>What Do They Buy?</strong>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td width="200px">
											Item <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What commodity does this company want to buy</span>
											</div>
										</td>
										<td colspan="5">
											Shipping Boxes
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>Ideal Size (in)</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">L</span><br>
												<input type="text" name="sb_item_length<?php echo $stableid ?>" id="sb_item_length<?php echo $stableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sb_item_length ?>" class="size_txt_center">
											</div>
										</td>
										<td width="20px" align="center">x</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">W</span><br>
												<input type="text" name="sb_item_width<?php echo $stableid ?>" id="sb_item_width<?php echo $stableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sb_item_width ?>" class="size_txt_center">
											</div>
										</td>
										<td width="20px" align="center">x</td>
										<td align="center" width="130px">
											<div class="size_align">
												<span class="label_txt">H</span><br>
												<input type="text" name="sb_item_height<?php echo $stableid ?>" id="sb_item_height<?php echo $stableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sb_item_height ?>" class="size_txt_center">
											</div>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Quantity Requested <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How much of this item do they order at a time</span>
											</div>
										</td>
										<td colspan=5>
											<select id="sb_quantity_requested<?php echo $stableid ?>" onchange="show_sb_otherqty_text_edit(this,<?php echo $stableid ?>)">
												<option <?php if ($sb_quantity_requested == "Select One") { ?> selected <?php } else {
																													} ?>>Select One</option>
												<option <?php if ($sb_quantity_requested == "Full Truckload") { ?> selected <?php } else {
																														} ?>>Full Truckload</option>
												<option <?php if ($sb_quantity_requested == "Half Truckload") { ?> selected <?php } else {
																														} ?>>Half Truckload</option>
												<option <?php if ($sb_quantity_requested == "Quarter Truckload") { ?> selected <?php } else {
																															} ?>>Quarter Truckload</option>
												<option <?php if ($sb_quantity_requested == "Other") { ?> selected <?php } else {
																												} ?>>Other</option>
											</select>
											<br>
											<?php
											if ($sb_quantity_requested == "Other") {
											?>
												<input type="text" name="sb_other_quantity<?php echo $stableid ?>" id="sb_other_quantity<?php echo $stableid ?>" size="10" onkeypress="return isNumberKey(event)" value="<?php echo $sb_other_quantity ?>">
											<?php
											} else {
											?>
												<input type="text" name="sb_other_quantity<?php echo $stableid ?>" id="sb_other_quantity<?php echo $stableid ?>" size="10" style="display:none;" onkeypress="return isNumberKey(event)">
											<?php
											}
											?>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Frequency of Order <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How often do they order this item</span>
											</div>
										</td>
										<td colspan=5>
											<select id="sb_frequency_order<?php echo $stableid ?>">
												<option <?php if ($sb_frequency_order == "Select One") { ?> selected <?php } else {
																													} ?>>Select One</option>
												<option <?php if ($sb_frequency_order == "Multiple per Week") { ?> selected <?php } else {
																														} ?>>Multiple per Week</option>
												<option <?php if ($sb_frequency_order == "Multiple per Month") { ?> selected <?php } else {
																															} ?>>Multiple per Month</option>
												<option <?php if ($sb_frequency_order == "Once per Month") { ?> selected <?php } else {
																														} ?>>Once per Month</option>
												<option <?php if ($sb_frequency_order == "Multiple per Year") { ?> selected <?php } else {
																														} ?>>Multiple per Year</option>
												<option <?php if ($sb_frequency_order == "Once per Year") { ?> selected <?php } else {
																													} ?>>Once per Year</option>
												<option <?php if ($sb_frequency_order == "One-Time Purchase") { ?> selected <?php } else {
																														} ?>>One-Time Purchase</option>
											</select>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											What Used For? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What do they put in this item, how much weight is going in it?</span>
											</div>
										</td>
										<td colspan=5>
											<input type="text" id="sb_what_used_for<?php echo $stableid ?>" value="<?php echo $sb_what_used_for ?>">
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Desired Price
											<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
										</td>
										<td colspan=5>
											$ <input type="text" name="sb_sales_desired_price<?php echo $stableid ?>" id="sb_sales_desired_price<?php echo $stableid ?>" value="<?php if ($sb_sales_desired_price > 0) {
																																													echo $sb_sales_desired_price;
																																												} else {
																																													echo '0';
																																												} ?>" onchange="setTwoNumberDecimal(this)">
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Notes <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span>
											</div>
										</td>
										<td colspan=5>
											<textarea id="sb_notes<?php echo $stableid ?>"><?php echo $sb_notes ?></textarea>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td colspan="6">
											<strong>Criteria of what they SHOULD be able to use:</strong>
											<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span>
											</div>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td colspan="6">
											<strong>Size Flexibility</strong>
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td><!-- align="right"-->
											Length
										</td>
										<td align="center">
											<span class="label_txt">Min</span>
											<br>
											<input type="text" class="size_txt_center" name="sb_item_min_length<?php echo $stableid ?>" id="sb_item_min_length<?php echo $stableid ?>" value="<?php echo $sb_item_min_length ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center">-</td>
										<td align="center">
											<span class="label_txt">Max</span>
											</br>
											<input type="text" class="size_txt_center" name="sb_item_max_length<?php echo $stableid ?>" id="sb_item_max_length<?php echo $stableid ?>" value="<?php echo $sb_item_max_length ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center" colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td><!-- align="right"-->
											Width
										</td>
										<td align="center">
											<span class="label_txt">Min</span>
											<br>
											<input type="text" class="size_txt_center" name="sb_item_min_width<?php echo $stableid ?>" id="sb_item_min_width<?php echo $stableid ?>" value="<?php echo $sb_item_min_width ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center">-</td>
										<td align="center">
											<span class="label_txt">Max</span>
											</br>
											<input type="text" class="size_txt_center" name="sb_item_max_width<?php echo $stableid ?>" id="sb_item_max_width<?php echo $stableid ?>" value="<?php echo $sb_item_max_width ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center" colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td><!-- align="right"-->
											Height
										</td>
										<td align="center">
											<span class="label_txt">Min</span>
											<br>
											<input type="text" class="size_txt_center" name="sb_item_min_height<?php echo $stableid ?>" id="sb_item_min_height<?php echo $stableid ?>" value="<?php echo $sb_item_min_height ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center">-</td>
										<td align="center">
											<span class="label_txt">Max</span>
											</br>
											<input type="text" class="size_txt_center" name="sb_item_max_height<?php echo $stableid ?>" id="sb_item_max_height<?php echo $stableid ?>" value="<?php echo $sb_item_max_height ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center" colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td><!-- align="right"-->
											Cubic Footage
										</td>
										<td align="center">
											<span class="label_txt">Min</span>
											<br>
											<input type="text" class="size_txt_center" name="sb_cubic_footage_min<?php echo $stableid ?>" id="sb_cubic_footage_min<?php echo $stableid ?>" value="<?php echo $sb_cubic_footage_min ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center">-</td>
										<td align="center">
											<span class="label_txt">Max</span>
											</br>
											<input type="text" class="size_txt_center" name="sb_cubic_footage_max<?php echo $stableid ?>" id="sb_cubic_footage_max<?php echo $stableid ?>" value="<?php echo $sb_cubic_footage_max ?>" size="5" onkeypress="return isNumberKey(event)">
										</td>
										<td align="center" colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											# of Walls
										</td>
										<td>
											1ply
										</td>
										<td>
											<input type="checkbox" id="sb_wall_1<?php echo $stableid ?>" value="Yes" <?php if ($sb_wall_1 == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
										<td>
											2ply
										</td>
										<td colspan="2">
											<input type="checkbox" id="sb_wall_2<?php echo $stableid ?>" value="Yes" <?php if ($sb_wall_2 == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Top Config
										</td>
										<td>
											No Top
										</td>
										<td>
											<input name="sb_no_top<?php echo $stableid ?>" type="checkbox" id="sb_no_top<?php echo $stableid ?>" value="Yes" <?php if ($sb_no_top == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
										<td>
											Full Flap Top
										</td>
										<td colspan="2">
											<input name="sb_full_flap_top<?php echo $stableid ?>" type="checkbox" id="sb_full_flap_top<?php echo $stableid ?>" value="Yes" <?php if ($sb_full_flap_top == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>&nbsp;</td>
										<td> Partial Flap Top</td>
										<td>
											<input name="sb_partial_flap_top<?php echo $stableid ?>" type="checkbox" id="sb_partial_flap_top<?php echo $stableid ?>" value="Yes" <?php if ($sb_partial_flap_top == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
										<td>&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Bottom Config
										</td>
										<td>
											No Bottom
										</td>
										<td>
											<input name="sb_no_bottom<?php echo $stableid ?>" type="checkbox" id="sb_no_bottom<?php echo $stableid ?>" value="Yes" <?php if ($sb_no_bottom == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
										<td>
											Full Flap Bottom
										</td>
										<td colspan="2">
											<input name="sb_full_flap_bottom<?php echo $stableid ?>" type="checkbox" id="sb_full_flap_bottom<?php echo $stableid ?>" value="Yes" <?php if ($sb_full_flap_bottom == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>&nbsp;</td>
										<td>
											Partial Flap Bottom
										</td>
										<td>
											<input name="sb_partial_flap_bottom<?php echo $stableid ?>" type="checkbox" id="sb_partial_flap_bottom<?php echo $stableid ?>" value="Yes" <?php if ($sb_partial_flap_bottom == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
										<td>&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Vents Okay?
										</td>
										<td colspan=5>
											<input type="checkbox" name="sb_vents_okay<?php echo $stableid ?>" id="sb_vents_okay<?php echo $stableid ?>" value="Yes" <?php if ($sb_vents_okay == "Yes") { ?>checked="checked" <?php } ?>>
										</td>
									</tr>
									<tr align="center" bgcolor="<?php echo $subheading; ?>">
										<td colspan=6>
											<input type="button" name="sb_item_submit" value="Update" onclick="quote_updates(<?php echo $stableid ?>)" style="cursor: pointer;">
											<input type="button" name="sb_item_cancel" value="Cancel" onclick="sb_quote_cancel(<?php echo $stableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>

					<tr>
						<td style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			//
			echo "</form>";
		} //End check parameter p sb
		// edit for super
		if ($_REQUEST["p"] == "sup") {

			$company_id = $_REQUEST["company_id"];
			$suptableid = $_REQUEST["suptableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];
			//
			$getrecquery = "Select * from quote_supersacks where id='" . $suptableid . "'";
			$sup_res = db_query($getrecquery);
			$sup_data = array_shift($sup_res);
			//
			$sup_item_length = $sup_data["sup_item_length"];
			$sup_item_width = $sup_data["sup_item_width"];
			$sup_item_height = $sup_data["sup_item_height"];

			$sup_quantity_requested = $sup_data["sup_quantity_requested"];
			$sup_other_quantity = $sup_data["sup_other_quantity"];
			$sup_frequency_order = $sup_data["sup_frequency_order"];
			$sup_what_used_for = $sup_data["sup_what_used_for"];
			$sup_date_needed_by = $sup_data["sup_date_needed_by"];
			$sup_need_pallets = $sup_data["sup_need_pallets"];
			$sup_notes = $sup_data["sup_notes"];
			$sup_quotereq_sales_flag = $sup_data["sup_quotereq_sales_flag"];
			$sup_sales_desired_price = $sup_data["sup_sales_desired_price"];

			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//---------------check quote send or not-------------------------------
			$sup_qut_id = $sup_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$no_of_quote_sent1 = "";
			$sup_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;

					$sup_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $sup_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$sup_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$sup_no_of_quote_sent = rtrim($sup_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$sup_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $sup_no_of_quote_sent;
					} else {
						$sup_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}
			//
			db();
			//
			echo '<form name="frm3_sup">';
		?>
			<div id="sup<?php echo $suptableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<td style="background:#91bb78; padding:5px;" colspan="4">
						<table cellpadding="3">
							<tr>
								<td class="boxProSubHeading" style="background:#91bb78;"> Box Profile ID: <?php echo $sup_data["quote_id"]; ?>
								</td>
								<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
								</td>
								<td class="boxProSubHeading" style="background:#91bb78;">
									<font face="Roboto" size="1" color="#91bb78">
										<img id="sup_btn_img<?php echo $suptableid ?>" src="images/minus_icon.png" />&nbsp;<a name="details_btn" id="sup_btn<?php echo $suptableid ?>" onClick="show_sup_details(<?php echo $suptableid ?>)" class="ex_col_btn boxProSubHeading">Collapse Details</a>
										<!--<input type="button" name="details_btn" id="sup_btn<?php //=$suptableid
																								?>" value="Collapse Details" onClick="show_sup_details(<?php //=$suptableid
																																						?>)">-->
									</font>
								</td>
								<td style="background:#91bb78;">
									<!-- <font face="Roboto" size="1" color="#91bb78">
                                    <img bgcolor="#91bb78"  src="images/del_img.png" onclick="sup_quote_delete(<?php echo $suptableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
                                </font> -->
									<input type="hidden" name="suptableid" value="<?php echo $suptableid ?>">
									<input type="hidden" name="supupdatequotedata" value="supupdatequotedata">
									<input type="hidden" id="quote_item<?php echo $suptableid ?>" value="<?php echo $quote_item ?>">
									<input type="hidden" id="company_id<?php echo $suptableid ?>" value="<?php echo $company_id ?>">
									<input type="hidden" id="client_dash_flg<?php echo $suptableid ?>" value="<?php echo $client_dash_flg ?>">
								</td>
							</tr>
						</table>
					</td>
					</tr>
					<tr>
						<td>
							<div id="sup_sub_table<?php echo $suptableid ?>">
								<table class="table_sup" width="100%">
									<tr>
										<td>
											<table width="80%" class="in_table_style tableBorder" cellpadding="3" cellspacing="1">

												<tr bgcolor="<?php echo $subheading; ?>">
													<td colspan="6">
														<strong>What Do They Buy?</strong>
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor1; ?>">
													<td width="200px">
														Item <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">What commodity does this company want to buy</span>
														</div>
													</td>
													<td colspan="5">
														Supersacks
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor2; ?>">
													<td>Ideal Size (in) <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span>
														</div>
													</td>
													<td align="center" width="130px">
														<div class="size_align">
															<span class="label_txt">L</span><br>
															<input type="text" name="sup_item_length<?php echo $suptableid ?>" id="sup_item_length<?php echo $suptableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sup_item_length ?>" class="size_txt_center">
														</div>
													</td>
													<td width="20px" align="center">x</td>
													<td align="center" width="130px">
														<div class="size_align">
															<span class="label_txt">W</span><br>
															<input type="text" name="sup_item_width<?php echo $suptableid ?>" id="sup_item_width<?php echo $suptableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sup_item_width ?>" class="size_txt_center">
														</div>
													</td>
													<td width="20px" align="center">x</td>
													<td align="center" width="130px">
														<div class="size_align">
															<span class="label_txt">H</span><br>
															<input type="text" name="sup_item_height<?php echo $suptableid ?>" id="sup_item_height<?php echo $suptableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $sup_item_height ?>" class="size_txt_center">
														</div>
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor1; ?>">
													<td>
														Quantity Requested <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">How much of this item do they order at a time</span>
														</div>
													</td>
													<td colspan=5>
														<select id="sup_quantity_requested<?php echo $suptableid ?>" onchange="show_sup_otherqty_text_edit(this, <?php echo $suptableid ?>)">
															<option <?php if ($sup_quantity_requested == "Select One") { ?> selected <?php } else {
																																	} ?>>Select One</option>
															<option <?php if ($sup_quantity_requested == "Full Truckload") { ?> selected <?php } else {
																																		} ?>>Full Truckload</option>
															<option <?php if ($sup_quantity_requested == "Half Truckload") { ?> selected <?php } else {
																																		} ?>>Half Truckload</option>
															<option <?php if ($sup_quantity_requested == "Quarter Truckload") { ?> selected <?php } else {
																																		} ?>>Quarter Truckload</option>
															<option <?php if ($sup_quantity_requested == "Other") { ?> selected <?php } else {
																															} ?>>Other</option>
														</select>
														<br>

														<?php
														if ($sup_quantity_requested == "Other") {
														?>
															<input type="text" name="sup_other_quantity<?php echo $suptableid ?>" id="sup_other_quantity<?php echo $suptableid ?>" size="10" onkeypress="return isNumberKey(event)" value="<?php echo $sup_other_quantity ?>">
														<?php
														} else {
														?>
															<input type="text" name="sup_other_quantity<?php echo $suptableid ?>" id="sup_other_quantity<?php echo $suptableid ?>" size="10" style="display:none;" onkeypress="return isNumberKey(event)">
														<?php
														}
														?>
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor2; ?>">
													<td>
														Frequency of Order <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">How often do they order this item</span>
														</div>
													</td>
													<td colspan=5>
														<select id="sup_frequency_order<?php echo $suptableid ?>">
															<option <?php if ($sup_frequency_order == "Select One") { ?> selected <?php } else {
																																} ?>>Select One</option>
															<option <?php if ($sup_frequency_order == "Multiple per Week") { ?> selected <?php } else {
																																		} ?>>Multiple per Week</option>
															<option <?php if ($sup_frequency_order == "Multiple per Month") { ?> selected <?php } else {
																																		} ?>>Multiple per Month</option>
															<option <?php if ($sup_frequency_order == "Once per Month") { ?> selected <?php } else {
																																	} ?>>Once per Month</option>
															<option <?php if ($sup_frequency_order == "Multiple per Year") { ?> selected <?php } else {
																																		} ?>>Multiple per Year</option>
															<option <?php if ($sup_frequency_order == "Once per Year") { ?> selected <?php } else {
																																	} ?>>Once per Year</option>
															<option <?php if ($sup_frequency_order == "One-Time Purchase") { ?> selected <?php } else {
																																		} ?>>One-Time Purchase</option>
														</select>
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor1; ?>">
													<td>
														What Used For? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">What do they put in this item, how much weight is going in it?</span>
														</div>
													</td>
													<td colspan=5>
														<input type="text" id="sup_what_used_for<?php echo $suptableid ?>" value="<?php echo $sup_what_used_for ?>">
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor1; ?>">
													<td>
														Desired Price
														<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
													</td>
													<td colspan=5>
														$ <input type="text" name="sup_sales_desired_price<?php echo $suptableid ?>" id="sup_sales_desired_price<?php echo $suptableid ?>" value="<?php if ($sup_sales_desired_price > 0) {
																																																		echo $sup_sales_desired_price;
																																																	} else {
																																																		echo '0';
																																																	} ?>" onchange="setTwoNumberDecimal(this)">
													</td>
												</tr>
												<tr bgcolor="<?php echo $rowcolor2; ?>">
													<td>
														Notes <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
															<span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span>
														</div>
													</td>
													<td colspan=5>
														<textarea id="sup_notes<?php echo $suptableid ?>"><?php echo $sup_notes; ?></textarea>
													</td>
												</tr>
												<tr align="center" bgcolor="<?php echo $subheading; ?>">
													<td colspan="6" align="center">
														<input type="button" name="sup_item_submit" value="Update" onclick="sup_quote_updates(<?php echo $suptableid ?>)" style="cursor: pointer;">
														<input type="button" name="sup_item_cancel" value="Cancel" onclick="sup_quote_cancel(<?php echo $suptableid ?>)" style="cursor: pointer;">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			echo "</form>";
		}
		//
		// edit for Pal
		if ($_REQUEST["p"] == "pal") {
			$company_id = $_REQUEST["company_id"];
			$paltableid = $_REQUEST["paltableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];

			//
			$getrecqueryp = "Select * from quote_pallets where id='" . $paltableid . "'";
			$sup_res = db_query($getrecqueryp);
			$pal_data = array_shift($sup_res);
			//
			$pal_item_length = $pal_data["pal_item_length"];
			$pal_item_width = $pal_data["pal_item_width"];

			$pal_quantity_requested = $pal_data["pal_quantity_requested"];
			$pal_other_quantity = $pal_data["pal_other_quantity"];
			$pal_frequency_order = $pal_data["pal_frequency_order"];
			$pal_what_used_for = $pal_data["pal_what_used_for"];
			$pal_date_needed_by = $pal_data["pal_date_needed_by"];
			$pal_quotereq_sales_flag = $pal_data["pal_quotereq_sales_flag"];
			$pal_note = $pal_data["pal_note"];

			$pal_sales_desired_price = $pal_data["pal_sales_desired_price"];
			//$pal_need_pallets = $pal_data["pal_need_pallets"];
			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//---------------check quote send or not-------------------------------
			$pal_qut_id = $pal_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$no_of_quote_sent1 = "";
			$pal_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;

					$pal_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $pal_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$pal_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$pal_no_of_quote_sent = rtrim($pal_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$pal_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $pal_no_of_quote_sent;
					} else {
						$pal_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}

			//
			db();
			//
			//
			echo '<form name="frm4_pal">';
		?>
			<div id="pal<?php echo $paltableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<tr>
						<td style="background:#91bb78; padding:5px;" colspan="4">
							<table cellpadding="3">
								<tr>
									<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $pal_data["quote_id"]; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;">
										<!-- <font face="Roboto" size="1" color="#91bb78">
                                    <img bgcolor="#91bb78"  src="images/del_img.png" onclick="pal_quote_delete(<?php echo $paltableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
                                </font> -->
										<input type="hidden" name="paltableid" value="<?php echo $paltableid ?>">
										<input type="hidden" name="palupdatequotedata" value="palupdatequotedata">
										<input type="hidden" id="quote_item<?php echo $paltableid ?>" value="<?php echo $quote_item ?>">
										<input type="hidden" id="company_id<?php echo $paltableid ?>" value="<?php echo $company_id ?>">
										<input type="hidden" id="client_dash_flg<?php echo $paltableid ?>" value="<?php echo $client_dash_flg ?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div id="pal_sub_table<?php echo $paltableid ?>">
								<table width="80%" class="in_table_style tableBorder" cellpadding="3" cellspacing="1">

									<tr bgcolor="<?php echo $subheading; ?>">
										<td colspan="7">
											<strong>What Do They Buy?</strong>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td width="250px">
											Item <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What commodity does this company want to buy</span>
											</div>
										</td>
										<td colspan="6">
											Pallets
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>Ideal Size (in) <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">If they were to buy brand new, what would be the size they would buy</span>
											</div>
										</td>
										<td align="center" width="140px">
											<div class="size_align">
												<span class="label_txt">L</span><br>
												<input type="text" name="pal_item_length<?php echo $paltableid ?>" id="pal_item_length<?php echo $paltableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $pal_item_length ?>" class="size_txt_center">
											</div>
										</td>
										<td width="40px" align="center">x</td>
										<td colspan="4">
											<div class="size_align">
												<span class="label_txt">W</span><br>
												<input type="text" name="pal_item_width<?php echo $paltableid ?>" id="pal_item_width<?php echo $paltableid ?>" size="5" onkeypress="return isNumberKey(event)" value="<?php echo $pal_item_width ?>" class="size_txt_center">
											</div>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Quantity Requested <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How much of this item do they order at a time</span>
											</div>
										</td>
										<td colspan=6>
											<select id="pal_quantity_requested<?php echo $paltableid ?>" onchange="show_pal_otherqty_text_edit(this, <?php echo $paltableid ?>)">
												<option <?php if ($pal_quantity_requested == "Select One") { ?> selected <?php } else {
																														} ?>>Select One</option>
												<option <?php if ($pal_quantity_requested == "Full Truckload") { ?> selected <?php } else {
																															} ?>>Full Truckload</option>
												<option <?php if ($pal_quantity_requested == "Half Truckload") { ?> selected <?php } else {
																															} ?>>Half Truckload</option>
												<option <?php if ($pal_quantity_requested == "Quarter Truckload") { ?> selected <?php } else {
																															} ?>>Quarter Truckload</option>
												<option <?php if ($pal_quantity_requested == "Other") { ?> selected <?php } else {
																												} ?>>Other</option>
											</select>
											<br>
											<?php
											if ($pal_quantity_requested == "Other") {
											?>
												<input type="text" name="pal_other_quantity<?php echo $paltableid ?>" id="pal_other_quantity<?php echo $paltableid ?>" size="10" onkeypress="return isNumberKey(event)" value="<?php echo $pal_other_quantity ?>">
											<?php
											} else {
											?>
												<input type="text" name="pal_other_quantity<?php echo $paltableid ?>" id="pal_other_quantity<?php echo $paltableid ?>" size="10" style="display:none;" onkeypress="return isNumberKey(event)">
											<?php
											}
											?>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Frequency of Order <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How often do they order this item</span>
											</div>
										</td>
										<td colspan=6>
											<select id="pal_frequency_order<?php echo $paltableid ?>">
												<option <?php if ($pal_frequency_order == "Select One") { ?> selected <?php } else {
																													} ?>>Select One</option>
												<option <?php if ($pal_frequency_order == "Multiple per Week") { ?> selected <?php } else {
																															} ?>>Multiple per Week</option>
												<option <?php if ($pal_frequency_order == "Multiple per Month") { ?> selected <?php } else {
																															} ?>>Multiple per Month</option>
												<option <?php if ($pal_frequency_order == "Once per Month") { ?> selected <?php } else {
																														} ?>>Once per Month</option>
												<option <?php if ($pal_frequency_order == "Multiple per Year") { ?> selected <?php } else {
																															} ?>>Multiple per Year</option>
												<option <?php if ($pal_frequency_order == "Once per Year") { ?> selected <?php } else {
																														} ?>>Once per Year</option>
												<option <?php if ($pal_frequency_order == "One-Time Purchase") { ?> selected <?php } else {
																															} ?>>One-Time Purchase</option>
											</select>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											What Used For? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What do they put in this item, how much weight is going in it?</span>
											</div>
										</td>
										<td colspan=6>
											<input type="text" id="pal_what_used_for<?php echo $paltableid ?>" value="<?php echo $pal_what_used_for ?>">
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Desired Price
											<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">The price point the client is trying to stay under. This value is per unit.</span> </div>
										</td>
										<td colspan=6>
											$ <input type="text" name="pal_sales_desired_price<?php echo $paltableid ?>" id="pal_sales_desired_price<?php echo $paltableid ?>" value="<?php if ($pal_sales_desired_price > 0) {
																																															echo $pal_sales_desired_price;
																																														} else {
																																															echo '0';
																																														} ?>" onchange="setTwoNumberDecimal(this)">
										</td>
									</tr>

									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Notes <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span>
											</div>
										</td>
										<td colspan=6>
											<textarea id="pal_note<?php echo $paltableid ?>"><?php echo $pal_note; ?></textarea>
										</td>
									</tr>
									<tr bgcolor="#d5d5d5">
										<td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
											<div class="tooltip_large"><i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext_large">It will be extremely difficult to find the exact size the company is asking for, so fill out the criteria and ranges of what the company SHOULD be able to use for this item. The more flexible the criteria, the more likely UCB can find options close to them (less expensive). The more strict the criteria, the more difficult it is for UCB to find options close to them (more expensive). All options will default to include all items, edit details to scale back the options.</span> </div>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>Grade </td>
										<td>A</td>
										<td align="center"><input type="checkbox" id="pal_grade_a<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_grade_a'] == 'Yes') {
																																			echo 'checked';
																																		} ?>></td>
										<td width="140px">B</td>
										<td width="40px" align="center"><input type="checkbox" id="pal_grade_b<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_grade_b'] == 'Yes') {
																																							echo 'checked';
																																						} ?>></td>
										<td width="140px">C</td>
										<td align="center"><input type="checkbox" id="pal_grade_c<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_grade_c'] == 'Yes') {
																																			echo 'checked';
																																		} ?>></td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>Material </td>
										<td>Wooden</td>
										<td align="center"><input type="checkbox" id="pal_material_wooden<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_material_wooden'] == 'Yes') {
																																					echo 'checked';
																																				} ?>></td>
										<td>Plastic</td>
										<td align="center"><input type="checkbox" id="pal_material_plastic<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_material_plastic'] == 'Yes') {
																																						echo 'checked';
																																					} ?>></td>
										<td>Corrugate</td>
										<td align="center"><input type="checkbox" id="pal_material_corrugate<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_material_corrugate'] == 'Yes') {
																																						echo 'checked';
																																					} ?>></td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>Entry</td>
										<td>2-way</td>
										<td align="center"><input type="checkbox" id="pal_entry_2way<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_entry_2way'] == 'Yes') {
																																				echo 'checked';
																																			} ?>></td>
										<td>4-way</td>
										<td colspan="3">&ensp;<input type="checkbox" id="pal_entry_4way<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_entry_4way'] == 'Yes') {
																																					echo 'checked';
																																				} ?>></td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>Structure</td>
										<td>Stringer</td>
										<td align="center"><input type="checkbox" id="pal_structure_stringer<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_structure_stringer'] == 'Yes') {
																																						echo 'checked';
																																					} ?>></td>
										<td>Block</td>
										<td colspan="3">&ensp;<input type="checkbox" id="pal_structure_block<?php echo $paltableid ?>" value="Yes" <?php if ($pal_data['pal_structure_block'] == 'Yes') {
																																						echo 'checked';
																																					} ?>></td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>Heat Treated</td>
										<td colspan=6>
											<select name="pal_heat_treated" id="pal_heat_treated<?php echo $paltableid ?>">
												<option <?php if ($pal_data['pal_heat_treated'] == "Select One") { ?> selected <?php } else {
																															} ?>>Select One</option>
												<option <?php if ($pal_data['pal_heat_treated'] == "Required") { ?> selected <?php } else {
																															} ?>>Required</option>
												<option <?php if ($pal_data['pal_heat_treated'] == "Not Required") { ?> selected <?php } else {
																																} ?>>Not Required</option>
											</select>
										</td>
									</tr>

									<tr align="center" bgcolor="<?php echo $subheading; ?>">
										<td colspan="4" align="center">
											<input type="button" name="pal_item_submit" value="Update" onclick="pal_quote_updates(<?php echo $paltableid ?>)" style="cursor: pointer;">
											<input type="button" name="pal_item_cancel" value="Cancel" onclick="pal_quote_cancel(<?php echo $paltableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<!-- <tr>
					<td style="background:#FFFFFF;">
						<a style='color:#91bb78;' id="lightbox_req_pals<?php echo $paltableid; ?>" href="javascript:void(0);" onclick="display_request_pallet_tool(<?php echo $company_id; ?>,1,1,<?php echo $paltableid; ?>)">
						<font face="Roboto" size="1" color="#91bb78">PALLET MATCHING TOOL</font></a>
						<span id="req_paltoolautoload" name="req_paltoolautoload" style='color:red;'></span>
						<br><br>
						
					</td>
				</tr> -->
					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			echo "</form>";
		}
		//
		// edit for Pal
		if ($_REQUEST["p"] == "dbi") {

			$company_id = $_REQUEST["company_id"];
			$dbitableid = $_REQUEST["dbitableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			//
			$getrecquery = "Select * from quote_dbi where id='" . $dbitableid . "'";
			$sup_res = db_query($getrecquery);
			$dbi_data = array_shift($sup_res);
			//
			$dbi_notes  = $dbi_data["dbi_notes"];
			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//---------------check quote send or not-------------------------------
			$dbi_qut_id = $dbi_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$no_of_quote_sent1 = "";
			$dbi_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;

					$dbi_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $dbi_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$dbi_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$dbi_no_of_quote_sent = rtrim($dbi_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$dbi_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $dbi_no_of_quote_sent;
					} else {
						$dbi_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}

			//
			db();
			//
			echo '<form name="frm3">';
		?>
			<div id="dbi<?php echo $dbitableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<tr>
						<td style="background:#91bb78; padding:5px;" colspan="4">
							<table cellpadding="3">
								<tr>
									<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $dbi_data["quote_id"]; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;">
										<font face="Roboto" size="1" color="#91bb78">
											<img bgcolor="#91bb78" src="images/del_img.png" onclick="dbi_quote_delete(<?php echo $dbitableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
										</font>
										<input type="hidden" name="dbitableid" value="<?php echo $dbitableid ?>">
										<input type="hidden" name="dbiupdatequotedata" value="dbiupdatequotedata">
										<input type="hidden" id="quote_item<?php echo $dbitableid ?>" value="<?php echo $quote_item ?>">
										<input type="hidden" id="company_id<?php echo $dbitableid ?>" value="<?php echo $company_id ?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<div id="dbi_sub_table<?php echo $dbitableid ?>">
								<table class="table2" width="100%">
									<tr>
										<td>
											<strong>Notes:</strong>
										</td>
										<td>
											<textarea id="dbi_notes<?php echo $dbitableid ?>"><?php echo $dbi_data['dbi_notes']; ?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="4" align="center">
											<input type="button" name="dbi_item_submit" value="Update" onclick="dbi_quote_updates(<?php echo $dbitableid ?>)" style="cursor: pointer;">
											<input type="button" name="dbi_item_cancel" value="Cancel" onclick="dbi_quote_cancel(<?php echo $dbitableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			echo "</form>";
		}
		//
		if ($_REQUEST["p"] == "rec") {

			$company_id = $_REQUEST["company_id"];
			$rectableid = $_REQUEST["rectableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			//
			$getrecquery = "Select * from quote_recycling where id='" . $rectableid . "'";
			$sup_res = db_query($getrecquery);
			$rec_data = array_shift($sup_res);
			//
			$recycling_notes  = $rec_data["recycling_notes"];
			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//---------------check quote send or not-------------------------------
			$rec_qut_id = $rec_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$no_of_quote_sent1 = "";
			$rec_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;

					$rec_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $rec_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$rec_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$rec_no_of_quote_sent = rtrim($rec_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$rec_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $rec_no_of_quote_sent;
					} else {
						$rec_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}

			db();
			//
			echo '<form name="frm3">';
		?>
			<div id="rec<?php echo $rectableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<tr>
						<td style="background:#91bb78; padding:5px;" colspan="4">
							<table cellpadding="3">
								<tr>
									<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $rec_data["quote_id"]; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">
										<font face="Roboto" size="1" color="#91bb78">
											<img bgcolor="#91bb78" src="images/del_img.png" onclick="rec_quote_delete(<?php echo $rectableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
										</font>
										<input type="hidden" name="rectableid" value="<?php echo $rectableid ?>">
										<input type="hidden" name="recupdatequotedata" value="recupdatequotedata">
										<input type="hidden" id="quote_item<?php echo $rectableid ?>" value="<?php echo $quote_item ?>">
										<input type="hidden" id="company_id<?php echo $rectableid ?>" value="<?php echo $company_id ?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div id="rec_sub_table<?php echo $rectableid ?>">
								<table class="table2" width="100%">
									<tr>
										<td>
											<strong>Notes :</strong>
										</td>
										<td>
											<textarea id="recycling_notes<?php echo $rectableid ?>"><?php echo $rec_data['recycling_notes']; ?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="4" align="center">
											<input type="button" name="rec_item_submit" value="Update" onclick="rec_quote_updates(<?php echo $rectableid ?>)" style="cursor: pointer;">
											<input type="button" name="rec_item_cancel" value="Cancel" onclick="rec_quote_cancel(<?php echo $rectableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
		<?php
			echo "</form>";
		}
		//
		if ($_REQUEST["p"] == "other") {

			$company_id = $_REQUEST["company_id"];
			$othertableid = $_REQUEST["othertableid"];
			$table_name = $_REQUEST["p"];
			$quote_item = $_REQUEST["quote_item"];
			//
			$client_dash_flg = $_REQUEST['client_dash_flg'];
			$getrecquery = "Select * from quote_other where id='" . $othertableid . "'";
			$sup_res = db_query($getrecquery);
			$other_data = array_shift($sup_res);
			//
			$other_quantity_requested = $other_data["other_quantity_requested"];
			$other_other_quantity = $other_data["other_other_quantity"];
			$other_frequency_order = $other_data["other_frequency_order"];
			$other_what_used_for = $other_data["other_what_used_for"];
			$other_date_needed_by = $other_data["other_date_needed_by"];
			$other_need_pallets = $other_data["other_need_pallets"];
			$other_note = $other_data["other_note"];
			$other_need_pallets = $other_data["other_need_pallets"];
			$other_quotereq_sales_flag = $other_data["other_quotereq_sales_flag"];
			//
			//Get Item Type
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//---------------check quote send or not-------------------------------
			$other_qut_id = $other_data['quote_id'];
			//
			db_b2b();
			$chk_quote_query1 = "Select * from quote where companyID=" . $company_id;
			$chk_quote_res1 = db_query($chk_quote_query1);
			$other_no_of_quote_sent1 = "";
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				for ($req = 0; $req < $total_id; $req++) {
					//echo $quote_req_id[$req]."---".$g_qut_id;
					$other_no_of_quote_sent = "";
					if ($quote_req_id[$req] == $other_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . $quote_rows1["ID"] . "'>";
							} elseif ($quote_rows1["quoteType"] == "Quote Select") {
								$link = "<a href='http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=" . $quote_rows1["ID"] . "' target='_blank'>";
							} else {
								$link = "<a href='#'>";
							}
						}

						//echo $quote_req_id[$req];
						$other_no_of_quote_sent1 .= $link . ($quote_rows1["ID"] + 3770) . "</a>, ";
						$qtr++;
						$other_no_of_quote_sent = rtrim($other_no_of_quote_sent1, ", ");
					}

					if ($qtr != 0) {
						$other_quote_sent_status = "<span style='color:#004B03;'>QUOTE SENT</span> - " . $other_no_of_quote_sent;
					} else {
						$other_quote_sent_status = "<span style='color:#FF0000;'>STILL NEEDS QUOTE SENT</span>";
					}
				}

				//}//End str pos
			}

			//
			db();
			echo '<form name="frm_other">';
		?>
			<div id="other<?php echo $othertableid ?>">
				<table width="100%" class="table1" cellpadding="3" cellspacing="1">
					<tr>
						<td style="background:#91bb78; padding:5px;" colspan="4">
							<table cellpadding="3">
								<tr>
									<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $other_data["quote_id"]; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
									</td>
									<td class="boxProSubHeading" style="background:#91bb78;">
										<font face="Roboto" size="1" color="#91bb78">
											<img bgcolor="#91bb78" src="images/del_img.png" onclick="other_quote_delete(<?php echo $othertableid ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
										</font>
										<input type="hidden" name="othertableid" value="<?php echo $othertableid ?>">
										<input type="hidden" name="otherupdatequotedata" value="otherupdatequotedata">
										<input type="hidden" id="quote_item<?php echo $othertableid ?>" value="<?php echo $quote_item ?>">
										<input type="hidden" id="company_id<?php echo $othertableid ?>" value="<?php echo $company_id ?>">
										<input type="hidden" id="client_dash_flg<?php echo $othertableid ?>" value="<?php echo $client_dash_flg ?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div id="other_sub_table<?php echo $othertableid ?>">
								<table width="80%" class="in_table_style tableBorder" cellpadding="3" cellspacing="1">

									<tr bgcolor="<?php //echo $subheading; 
													?>">
										<td colspan="6">
											<strong>What Do They Buy?</strong>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td width="200px">
											Item <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What commodity does this company want to buy</span>
											</div>
										</td>
										<td>
											Other
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Quantity Requested <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How much of this item do they order at a time</span>
											</div>
										</td>
										<td>
											<select id="other_quantity_requested<?php echo $othertableid ?>" onchange="show_other_otherqty_text_edit(this, <?php echo $othertableid ?>)">
												<option <?php if ($other_quantity_requested == "Select One") { ?> selected <?php } else {
																														} ?>>Select One</option>
												<option <?php if ($other_quantity_requested == "Full Truckload") { ?> selected <?php } else {
																															} ?>>Full Truckload</option>
												<option <?php if ($other_quantity_requested == "Half Truckload") { ?> selected <?php } else {
																															} ?>>Half Truckload</option>
												<option <?php if ($other_quantity_requested == "Quarter Truckload") { ?> selected <?php } else {
																																} ?>>Quarter Truckload</option>
												<option <?php if ($other_quantity_requested == "Other") { ?> selected <?php } else {
																													} ?>>Other</option>
											</select>
											<br>
											<?php
											if ($other_quantity_requested == "Other") {
											?>
												<input type="text" name="other_other_quantity<?php echo $othertableid ?>" id="other_other_quantity<?php echo $othertableid ?>" size="10" value="<?php echo $other_other_quantity ?>">
											<?php
											} else {
											?>
												<input type="text" name="other_other_quantity<?php echo $othertableid ?>" id="other_other_quantity<?php echo $othertableid ?>" size="10" style="display:none;">
											<?php
											}
											?>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Frequency of Order <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">How often do they order this item</span>
											</div>
										</td>
										<td>
											<select id="other_frequency_order<?php echo $othertableid ?>">
												<option <?php if ($other_frequency_order == "Select One") { ?> selected <?php } else {
																													} ?>>Select One</option>
												<option <?php if ($other_frequency_order == "Multiple per Week") { ?> selected <?php } else {
																															} ?>>Multiple per Week</option>
												<option <?php if ($other_frequency_order == "Multiple per Month") { ?> selected <?php } else {
																															} ?>>Multiple per Month</option>
												<option <?php if ($other_frequency_order == "Once per Month") { ?> selected <?php } else {
																														} ?>>Once per Month</option>
												<option <?php if ($other_frequency_order == "Multiple per Year") { ?> selected <?php } else {
																															} ?>>Multiple per Year</option>
												<option <?php if ($other_frequency_order == "Once per Year") { ?> selected <?php } else {
																														} ?>>Once per Year</option>
												<option <?php if ($other_frequency_order == "One-Time Purchase") { ?> selected <?php } else {
																															} ?>>One-Time Purchase</option>
											</select>
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											What Used For? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">What do they put in this item, how much weight is going in it?</span>
											</div>
										</td>
										<td>
											<input type="text" id="other_what_used_for<?php echo $othertableid ?>" value="<?php echo $other_what_used_for ?>">
										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor2; ?>">
										<td>
											Also Need Pallets? <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">Do they also order pallets</span>
											</div>
										</td>
										<td>
											<input type="checkbox" name="other_need_pallets<?php echo $othertableid ?>" id="other_need_pallets<?php echo $othertableid ?>" value="Yes" <?php if ($other_need_pallets == "Yes") { ?>checked="checked" <?php } ?>>

										</td>
									</tr>
									<tr bgcolor="<?php echo $rowcolor1; ?>">
										<td>
											Notes <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
												<span class="tooltiptext">Add any additional notes that will assist in selling these items. More info is better.</span>
											</div>
										</td>
										<td>
											<textarea id="other_note<?php echo $othertableid ?>"><?php echo $other_note; ?></textarea>
										</td>
									</tr>
									<tr align="center" bgcolor="<?php echo $subheading; ?>">
										<td colspan="4" align="center">
											<input type="button" name="rec_item_submit" value="Update" onclick="other_quote_updates(<?php echo $othertableid ?>)" style="cursor: pointer;">
											<input type="button" name="rec_item_cancel" value="Cancel" onclick="other_quote_cancel(<?php echo $othertableid ?>)" style="cursor: pointer;">
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td style="background:#FFFFFF;">
							<a style='color:#91bb78;' id="lightbox_req_other<?php echo $othertableid; ?>" href="javascript:void(0);" onclick="display_request_other_tool(<?php echo $company_id; ?>,1,1,<?php echo $othertableid; ?>)">
								<font face="Roboto" size="1" color="#91bb78">OTHER MATCHING TOOL</font>
							</a>
							<span id="req_othertoolautoload" name="req_othertoolautoload" style='color:red;'></span>
							<br><br>

						</td>
					</tr>
					<tr>
						<td colspan="4" style="background:#FFFFFF; height:4px;">
						</td>
					</tr>
				</table>
			</div>
<?php
			echo "</form>";
		}
		//

	}
}

?>