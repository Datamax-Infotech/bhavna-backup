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

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit();
$subheading = "#b6d4a4";
$rowcolor1 = "#e4e4e4";
$rowcolor2 = "#ececec";

$clientdash_flg = $_REQUEST["client_dash_flg"];
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
		$x = $x . " " . ((int)$dh[0] - 0) . ":" . $dh[1] . "PM CT";
	} elseif ($dh[0] == 0) {
		$x = $x . " 12:" . $dh[1] . "AM CT";
	} elseif ($dh[0] > 12) {
		$x = $x . " " . ((int)$dh[0] - 12) . ":" . $dh[1] . "PM CT";
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

		$presec = $pmin - (int)$premin[0];
		$sec = $presec * 60;

		$timeshift = $premin[0] . ' min ' . round($sec, 0) . ' sec ';
	} elseif ($time >= 3600 && $time <= 86399) {
		// Hours + Minutes
		$phour = ($edate - $sdate) / 3600;
		$prehour = explode('.', strval($phour));

		$premin = $phour - (float)$prehour[0];
		$min = explode('.', strval($premin * 60));

		$presec = '0.' . $min[1];
		$sec = (int)$presec * 60;

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
		$sec = (int)$presec * 60;

		$timeshift = $preday[0];
	}
	return $timeshift;
}
?>
<html>

<head>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap" rel="stylesheet">
	<style>
		.MsoNormal {
			font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif' !important;
		}
	</style>
</head>

<body>
	<?php

	$other_display_data = "";
	$updateflagpal = "";
	$pal_display_data = "";
	$updateflagsup = "";
	$sup_update_data = "";
	$updateflagsb = "";
	$sb_display_data = "";
	$updateflagg = "";
	$record_save1 = "";
	$log = "";
	if (isset($_REQUEST["addquotedata"])) {
		if ($_REQUEST["addquotedata"] == 1) {
			//
			$initials = 'Portal';
			$current_dt_tm = date("Y-m-d h:i:sa");
			//
			$company_id = $_REQUEST["company_id"];
			$quote_item_id = $_REQUEST["quote_item"];

			$client_dash_flg = $_REQUEST["client_dash_flg"];

			if ((isset($_REQUEST["quoterequest_saleslead_flag"])) && ($_REQUEST["quoterequest_saleslead_flag"] != "")) {
				$quoterequest_saleslead_flag = $_REQUEST["quoterequest_saleslead_flag"];
			} else {
				$quoterequest_saleslead_flag = "No";
			}
			//
			$query = db_query("INSERT INTO quote_request (`quote_item` , `companyID`, `user_initials`, `quote_date`, `client_dash_flg`) values('" . $quote_item_id . "','" . $company_id . "','" . $initials . "','" . $current_dt_tm . "','" . $client_dash_flg . "')");
			//echo "INSERT INTO quote_request (`quote_item` , `companyID`, `user_initials`, `quote_date`, `client_dash_flg`) values('".$quote_item_id."','".$company_id."','".$initials."','".$current_dt_tm."','".$client_dash_flg."')";

			$quote_id = tep_db_insert_id();
			//
			if ($quote_item_id == 1) {
				$g_item_length = $_REQUEST["g_item_length"];
				$g_item_width = $_REQUEST["g_item_width"];
				$g_item_height = $_REQUEST["g_item_height"];
				//
				$g_item_min_height = $_REQUEST["g_item_min_height"];
				$g_item_max_height = $_REQUEST["g_item_max_height"];
				//
				$sales_desired_price_g = $_REQUEST["sales_desired_price_g"];
				//
				if (isset($_REQUEST["g_shape_rectangular"])) {
					$g_shape_rectangular = $_REQUEST["g_shape_rectangular"];
				} else {
					$g_shape_rectangular = "No";
				}

				if (isset($_REQUEST["g_shape_octagonal"])) {
					$g_shape_octagonal = $_REQUEST["g_shape_octagonal"];
				} else {
					$g_shape_octagonal = "No";
				}
				//
				if (isset($_REQUEST["g_wall_1"])) {
					$g_wall_1 = $_REQUEST["g_wall_1"];
				} else {
					$g_wall_1 = "No";
				}
				if (isset($_REQUEST["g_wall_2"])) {
					$g_wall_2 = $_REQUEST["g_wall_2"];
				} else {
					$g_wall_2 = "No";
				}
				if (isset($_REQUEST["g_wall_3"])) {
					$g_wall_3 = $_REQUEST["g_wall_3"];
				} else {
					$g_wall_3 = "No";
				}
				if (isset($_REQUEST["g_wall_4"])) {
					$g_wall_4 = $_REQUEST["g_wall_4"];
				} else {
					$g_wall_4 = "No";
				}
				if (isset($_REQUEST["g_wall_5"])) {
					$g_wall_5 = $_REQUEST["g_wall_5"];
				} else {
					$g_wall_5 = "No";
				}
				if (isset($_REQUEST["g_wall_6"])) {
					$g_wall_6 = $_REQUEST["g_wall_6"];
				} else {
					$g_wall_6 = "No";
				}
				if (isset($_REQUEST["g_wall_7"])) {
					$g_wall_7 = $_REQUEST["g_wall_7"];
				} else {
					$g_wall_7 = "No";
				}
				if (isset($_REQUEST["g_wall_8"])) {
					$g_wall_8 = $_REQUEST["g_wall_8"];
				} else {
					$g_wall_8 = "No";
				}
				if (isset($_REQUEST["g_wall_9"])) {
					$g_wall_9 = $_REQUEST["g_wall_9"];
				} else {
					$g_wall_9 = "No";
				}
				if (isset($_REQUEST["g_wall_10"])) {
					$g_wall_10 = $_REQUEST["g_wall_10"];
				} else {
					$g_wall_10 = "No";
				}
				//
				if (isset($_REQUEST["g_no_top"])) {
					$g_no_top = $_REQUEST["g_no_top"];
				} else {
					$g_no_top = "No";
				}

				if (isset($_REQUEST["g_lid_top"])) {
					$g_lid_top = $_REQUEST["g_lid_top"];
				} else {
					$g_lid_top = "No";
				}

				if (isset($_REQUEST["g_partial_flap_top"])) {
					$g_partial_flap_top = $_REQUEST["g_partial_flap_top"];
				} else {
					$g_partial_flap_top = "No";
				}

				if (isset($_REQUEST["g_full_flap_top"])) {
					$g_full_flap_top = $_REQUEST["g_full_flap_top"];
				} else {
					$g_full_flap_top = "No";
				}
				//

				if (isset($_REQUEST["g_no_bottom_config"])) {
					$g_no_bottom_config = $_REQUEST["g_no_bottom_config"];
				} else {
					$g_no_bottom_config = "No";
				}

				if (isset($_REQUEST["g_partial_flap_w"])) {
					$g_partial_flap_w = $_REQUEST["g_partial_flap_w"];
				} else {
					$g_partial_flap_w = "No";
				}

				if (isset($_REQUEST["g_tray_bottom"])) {
					$g_tray_bottom = $_REQUEST["g_tray_bottom"];
				} else {
					$g_tray_bottom = "No";
				}

				if (isset($_REQUEST["g_full_flap_bottom"])) {
					$g_full_flap_bottom = $_REQUEST["g_full_flap_bottom"];
				} else {
					$g_full_flap_bottom = "No";
				}

				if (isset($_REQUEST["g_partial_flap_wo"])) {
					$g_partial_flap_wo = $_REQUEST["g_partial_flap_wo"];
				} else {
					$g_partial_flap_wo = "No";
				}
				//

				if (isset($_REQUEST["g_vents_okay"])) {
					$g_vents_okay = $_REQUEST["g_vents_okay"];
				} else {
					$g_vents_okay = "No";
				}

				if (isset($_REQUEST["need_pallets"])) {
					$need_pallets = $_REQUEST["need_pallets"];
				} else {
					$need_pallets = "No";
				}
				//
				$g_quantity_request = $_REQUEST["g_quantity_request"];
				if ($g_quantity_request == "Other") {
					$g_other_quantity = $_REQUEST["g_other_quantity"];
				} else {
					$g_other_quantity = 0;
				}
				//
				$g_frequency_order = $_REQUEST["g_frequency_order"];
				$g_what_used_for = str_replace("'", "\'", $_REQUEST["g_what_used_for"]);
				$date_needed_by = "";
				if ($_REQUEST["date_needed_by"] != "") {
					$date_needed_by = date("Y-m-d", strtotime($_REQUEST["date_needed_by"]));
				}
				//
				$g_item_note = str_replace("'", "\'", ($_REQUEST["g_item_note"]));


				//
				//$query = db_query("INSERT INTO quote_request (`quote_item` , `companyID`, `user_initials`, `quote_date`) values('".$quote_item_id."','".$company_id."','".$initials."','".$current_dt_tm."')");
				//
				$query1 = db_query("INSERT INTO quote_gaylord (quote_id, g_item_length, g_item_width, g_item_height, g_item_min_height, g_item_max_height, g_shape_rectangular, g_shape_octagonal, g_wall_1, g_wall_2, g_wall_3, g_wall_4, g_wall_5, g_wall_6, g_wall_7, g_wall_8, g_wall_9, g_wall_10, g_no_top, g_lid_top, g_partial_flap_top, g_full_flap_top, g_no_bottom_config, g_partial_flap_w, g_tray_bottom, g_full_flap_bottom, g_partial_flap_wo, g_vents_okay, g_quantity_request, g_other_quantity, g_frequency_order, g_what_used_for, date_needed_by, need_pallets, g_item_note, g_quotereq_sales_flag, sales_desired_price_g) VALUES ('" . $quote_id . "', '" . $g_item_length . "', '" . $g_item_width . "', '" . $g_item_height . "', '" . $g_item_min_height . "', '" . $g_item_max_height . "', '" . $g_shape_rectangular . "', '" . $g_shape_octagonal . "', '" . $g_wall_1 . "', '" . $g_wall_2 . "', '" . $g_wall_3 . "', '" . $g_wall_4 . "', '" . $g_wall_5 . "', '" . $g_wall_6 . "', '" . $g_wall_7 . "', '" . $g_wall_8 . "', '" . $g_wall_9 . "', '" . $g_wall_10 . "', '" . $g_no_top . "', '" . $g_lid_top . "', '" . $g_partial_flap_top . "', '" . $g_full_flap_top . "', '" . $g_no_bottom_config . "', '" . $g_partial_flap_w . "', '" . $g_tray_bottom . "', '" . $g_full_flap_bottom . "', '" . $g_partial_flap_wo . "', '" . $g_vents_okay . "', '" . $g_quantity_request . "','" . $g_other_quantity . "', '" . $g_frequency_order . "', '" . $g_what_used_for . "', '" . $date_needed_by . "', '" . $need_pallets . "', '" . $g_item_note . "', '" . $quoterequest_saleslead_flag . "', '" . $sales_desired_price_g . "')");
				//
				$getrecquery = "Select * from quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_gaylord.id desc limit 1";
				$g_res = db_query($getrecquery);
				$company_id = $_REQUEST["company_id"];
				while ($g_data = array_shift($g_res)) {
					//
					$quote_item = $g_data["quote_item"];
					$quote_id = $g_data["quote_id"];
					//Get Item Name
					$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
					$quote_item_rs = array_shift($getquotequery);
					$quote_item_name = $quote_item_rs['item'];
					//
					//---- Send auto email------------------------------------------
					//
					if ($g_data['g_item_length'] == "") {
						$g_item_length = "0";
					} else {
						$g_item_length = $g_data['g_item_length'];
					}
					//
					if ($g_data['g_item_width'] == "") {
						$g_item_width = "0";
					} else {
						$g_item_width = $g_data['g_item_width'];
					}
					//
					if ($g_data['g_item_height'] == "") {
						$g_item_height = "0";
					} else {
						$g_item_height = $g_data['g_item_height'];
					}
					//
					if ($g_data['g_quantity_request'] == "Select One") {
						$g_quantity_request = "None";
					} else {
						if ($g_data['g_quantity_request'] == "Other") {
							$g_quantity_request = $g_data['g_quantity_request'] . "-" . $g_data['g_other_quantity'];
						} else {
							$g_quantity_request = $g_data['g_quantity_request'];
						}
					}
					//
					if ($g_data['g_frequency_order'] == "Select One") {
						$g_frequency_order = "None";
					} else {
						$g_frequency_order = $g_data['g_frequency_order'];
					}
					//
					if ($g_data['g_what_used_for'] == "" || $g_data['g_what_used_for'] == " ") {
						$g_what_used_for = "No";
					} else {
						$g_what_used_for = $g_data['g_what_used_for'];
					}
					//
					if ($g_data['need_pallets'] == "Yes") {
						$need_pallets = "Yes";
					} else {
						$need_pallets = "No";
					}
					//
					if ($g_data['sales_desired_price_g'] == "") {
						$sales_desired_price_g = "0.00";
					} else {
						$sales_desired_price_g = number_format($g_data['sales_desired_price_g'], 2);
					}
					//
					if ($g_data['g_item_note'] == "") {
						$g_item_note = "None";
					} else {
						$g_item_note = $g_data['g_item_note'];
					}
					//
					if ($g_data['g_item_min_height'] == "" || $g_data['g_item_min_height'] == "0") {
						$g_item_min_height = "0";
					} else {
						$g_item_min_height = $g_data['g_item_min_height'];
					}
					//
					if ($g_data['g_item_max_height'] == "") {
						$g_item_max_height = "0";
					} else {
						$g_item_max_height = $g_data['g_item_max_height'];
					}
					//
					$g_bottom = "";
					$g_top = "";
					$g_wall = "";
					$g_shape = "";
					$w = "";
					$b = "";
					$t = "";
					if ($g_data['g_shape_rectangular'] != "Yes") {
						$g_shape_rectangular = "No";
					} else {
						$g_shape_rectangular = $g_data['g_shape_rectangular'];
						$g_shape = "Rectangular";
					}
					//
					if ($g_data['g_shape_octagonal'] != "Yes") {
						$g_shape_octagonal = "No";
					} else {
						$g_shape_octagonal = $g_data['g_shape_octagonal'];
						if ($g_shape != "") {
							$g_shape .= ", Octagonal";
						} else {
							$g_shape = "Octagonal";
						}
					}
					//
					
					if ($g_data['g_wall_1'] != "Yes") {
						$g_wall_1 = "No";
					} else {
						$g_wall = "1ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_2'] != "Yes") {
						$g_wall_2 = "No";
					} else {
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall_2 = $g_data['g_wall_2'];

						$g_wall .= $comma . "2ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_3'] != "Yes") {
						$g_wall_3 = "No";
					} else {
						$g_wall_3 = $g_data['g_wall_3'];

						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "3ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_4'] != "Yes") {
						$g_wall_4 = "No";
					} else {
						$g_wall_4 = $g_data['g_wall_4'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "4ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_5'] != "Yes") {
						$g_wall_5 = "No";
					} else {
						$g_wall_5 = $g_data['g_wall_5'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "5ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_6'] != "Yes") {
						$g_wall_6 = "No";
					} else {
						$g_wall_6 = $g_data['g_wall_6'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "6ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_7'] != "Yes") {
						$g_wall_7 = "No";
					} else {
						$g_wall_7 = $g_data['g_wall_7'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "7ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_8'] != "Yes") {
						$g_wall_8 = "No";
					} else {
						$g_wall_8 = $g_data['g_wall_8'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "8ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_9'] != "Yes") {
						$g_wall_9 = "No";
					} else {
						$g_wall_9 = $g_data['g_wall_9'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "9ply";
						$w = "y";
					}
					//
					if ($g_data['g_wall_10'] != "Yes") {
						$g_wall_10 = "No";
					} else {
						$g_wall_10 = $g_data['g_wall_10'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_wall .= $comma . "10ply";
					}
					//
					if ($g_data['g_no_top'] != "Yes") {
						$g_no_top = "No";
					} else {
						$g_no_top = $g_data['g_no_top'];
						$g_top = "No Top";
						$t = "y";
					}
					//
					if ($g_data['g_lid_top'] != "Yes") {
						$g_lid_top = "No";
					} else {
						$g_lid_top = $g_data['g_lid_top'];
						if ($t == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_top .= $comma . "Lid Top";
						$t = "y";
					}
					//
					if ($g_data['g_partial_flap_top'] != "Yes") {
						$g_partial_flap_top = "No";
					} else {
						$g_partial_flap_top = $g_data['g_partial_flap_top'];
						if ($t == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_top .= $comma . "Partial Flap Top";
						$t = "y";
					}
					//
					if ($g_data['g_full_flap_top'] != "Yes") {
						$g_full_flap_top = "No";
					} else {
						$g_full_flap_top = $g_data['g_full_flap_top'];
						if ($t == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_top .= $comma . "Full Flap Top";
						$t = "y";
					}
					//
					if ($g_data['g_no_bottom_config'] != "Yes") {
						$g_no_bottom_config = "No";
					} else {
						$g_bottom .= "No Bottom";
						$b = "y";
					}
					if ($g_data['g_tray_bottom'] != "Yes") {
						$g_tray_bottom = "No";
					} else {
						$g_tray_bottom = $g_data['g_tray_bottom'];
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_bottom .= $comma . "Tray Bottom";
						$b = "y";
					}
					if ($g_data['g_partial_flap_w'] != "Yes") {
						$g_partial_flap_w = "No";
					} else {
						$g_partial_flap_w = $g_data['g_partial_flap_w'];
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_bottom .= $comma . "Partial Flap w/ Slipsheet";
						$b = "y";
					}
					if ($g_data['g_full_flap_bottom'] != "Yes") {
						$g_full_flap_bottom = "No";
					} else {
						$g_full_flap_bottom = $g_data['g_full_flap_bottom'];
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_bottom .= $comma . "Full Flap Bottom";
						$b = "y";
					}
					//
					if ($g_data['g_partial_flap_wo'] != "Yes") {
						$g_partial_flap_wo = "No";
					} else {
						$g_partial_flap_wo = $g_data['g_partial_flap_wo'];
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$g_bottom .= $comma . "Partial Flap w/o SlipSheet";
					}
					//
					if ($g_data['g_vents_okay'] != "Yes") {
						$g_vents_okay = "No";
					} else {
						$g_vents_okay = $g_data['g_vents_okay'];
					}
					//
					//
					$rq_item = "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
					$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $g_item_length . " x " . $g_item_width . " x " . $g_item_height . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $g_quantity_request . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $g_frequency_order . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $g_what_used_for . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $need_pallets . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sales_desired_price_g . "</li>

						<li> <strong>Notes: </strong> " . $g_item_note . "</li>
						</ul>";
					//-----Tolerances---------
					$rq_item .= "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-weight:600; font-size:16pt;\"><u>Tolerances</u></span>
						<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
						<li style='padding-bottom:4px;'><strong>Height Flexibility (in): </strong> " . $g_item_min_height . " - " . $g_item_max_height . "</li>
						<li style='padding-bottom:4px;'><strong>Shape: </strong>
						" . $g_shape . "
						</li>
						<li style='padding-bottom:4px;'><strong># of Walls: </strong>
						  " . $g_wall . "
						</li>
						<li style='padding-bottom:4px;'><strong>Top Config: </strong>
		  					" . $g_top . "
		  				</li>

            			<li><strong>Bottom Config: </strong>
						  " . $g_bottom . "
						</li>
						
						<li style='padding-bottom:4px;'><strong>Vents Okay?: </strong>
						  " . $g_vents_okay . "
						</li>
						
						</ul>";
					//echo $rq_item;

					send_demand_email($rq_item, $log, $company_id, $quote_item_name, $quote_id, 'new_q');/**/
				}
				//End email send code
				//Call display record
				$record_save1 = "done";
				//
			} //end IF
			if ($quote_item_id == 2) {
				//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; 
				$sb_item_length = $_REQUEST["sb_item_length"];
				$sb_item_width = $_REQUEST["sb_item_width"];
				$sb_item_height = $_REQUEST["sb_item_height"];
				$sb_item_min_length = $_REQUEST["sb_item_min_length"];
				$sb_item_max_length = $_REQUEST["sb_item_max_length"];
				$sb_item_min_width = $_REQUEST["sb_item_min_width"];
				$sb_item_max_width = $_REQUEST["sb_item_max_width"];
				$sb_item_min_height = $_REQUEST["sb_item_min_height"];
				$sb_item_max_height = $_REQUEST["sb_item_max_height"];
				$sb_cubic_footage_min = $_REQUEST["sb_cubic_footage_min"];
				$sb_cubic_footage_max = $_REQUEST["sb_cubic_footage_max"];
				//
				$sb_sales_desired_price = $_REQUEST["sb_sales_desired_price"];
				//
				/*$sb_min_strength = $_REQUEST["sb_min_strength"];
				$sb_top_config = $_REQUEST["sb_top_config"];
				$sb_vents_okay = $_REQUEST["sb_vents_okay"];*/
				$sb_quantity_requested = $_REQUEST["sb_quantity_requested"];
				if ($sb_quantity_requested == "Other") {
					$sb_other_quantity = $_REQUEST["sb_other_quantity"];
				} else {
					$sb_other_quantity = 0;
				}
				$sb_frequency_order = $_REQUEST["sb_frequency_order"];
				$sb_what_used_for = str_replace("'", "\'", $_REQUEST["sb_what_used_for"]);
				$sb_date_needed_by = "";
				if ($_REQUEST["sb_date_needed_by"] != "") {
					$sb_date_needed_by = date("Y-m-d", strtotime($_REQUEST["sb_date_needed_by"]));
				}

				$sb_notes = str_replace("'", "\'", ($_REQUEST["sb_notes"]));

				if (isset($_REQUEST["sb_need_pallets"])) {
					$sb_need_pallets = $_REQUEST["sb_need_pallets"];
				} else {
					$sb_need_pallets = "No";
				}
				if (isset($_REQUEST["sb_wall_1"])) {
					$sb_wall_1 = $_REQUEST["sb_wall_1"];
				} else {
					$sb_wall_1 = "No";
				}
				if (isset($_REQUEST["sb_wall_2"])) {
					$sb_wall_2 = $_REQUEST["sb_wall_2"];
				} else {
					$sb_wall_2 = "No";
				}
				if (isset($_REQUEST["sb_full_flap_top"])) {
					$sb_full_flap_top = $_REQUEST["sb_full_flap_top"];
				} else {
					$sb_full_flap_top = "No";
				}
				if (isset($_REQUEST["sb_partial_flap_top"])) {
					$sb_partial_flap_top = $_REQUEST["sb_partial_flap_top"];
				} else {
					$sb_partial_flap_top = "No";
				}

				if (isset($_REQUEST["sb_no_top"])) {
					$sb_no_top = $_REQUEST["sb_no_top"];
				} else {
					$sb_no_top = "No";
				}
				if (isset($_REQUEST["sb_no_bottom"])) {
					$sb_no_bottom = $_REQUEST["sb_no_bottom"];
				} else {
					$sb_no_bottom = "No";
				}
				if (isset($_REQUEST["sb_full_flap_bottom"])) {
					$sb_full_flap_bottom = $_REQUEST["sb_full_flap_bottom"];
				} else {
					$sb_full_flap_bottom = "No";
				}
				if (isset($_REQUEST["sb_partial_flap_bottom"])) {
					$sb_partial_flap_bottom = $_REQUEST["sb_partial_flap_bottom"];
				} else {
					$sb_partial_flap_bottom = "No";
				}
				if (isset($_REQUEST["sb_vents_okay"])) {
					$sb_vents_okay = $_REQUEST["sb_vents_okay"];
				} else {
					$sb_vents_okay = "No";
				}
				if (isset($_REQUEST["sb_quotereq_sales_flag"])) {
					$sb_quotereq_sales_flag = $_REQUEST["sb_quotereq_sales_flag"];
				} else {
					$sb_quotereq_sales_flag = "No";
				}
				//
				//
				//$query = db_query("INSERT INTO quote_request (quote_item , companyID, user_initials, quote_date) values('".$quote_item_id."','".$company_id."','".$initials."','".$current_dt_tm."')");

				//$quote_id = tep_db_insert_id();	
				$query1 = db_query("INSERT INTO quote_shipping_boxes (quote_id, sb_item_length, sb_item_width, sb_item_height, sb_item_min_length, sb_item_max_length, sb_item_min_width, sb_item_max_width, sb_item_min_height, sb_item_max_height, sb_cubic_footage_min, sb_cubic_footage_max, sb_wall_1, sb_wall_2, sb_no_top, sb_full_flap_top, sb_no_bottom, sb_full_flap_bottom, sb_vents_okay, sb_quantity_requested, sb_other_quantity, sb_frequency_order, sb_what_used_for, sb_date_needed_by, sb_need_pallets, sb_notes, sb_quotereq_sales_flag, sb_sales_desired_price,sb_partial_flap_top,sb_partial_flap_bottom) VALUES ('" . $quote_id . "', '" . $sb_item_length . "', '" . $sb_item_width . "', '" . $sb_item_height . "', '" . $sb_item_min_length . "', '" . $sb_item_max_length . "', '" . $sb_item_min_width . "', '" . $sb_item_max_width . "', '" . $sb_item_min_height . "', '" . $sb_item_max_height . "', '" . $sb_cubic_footage_min . "', '" . $sb_cubic_footage_max . "', '" . $sb_wall_1 . "' , '" . $sb_wall_2 . "' , '" . $sb_no_top . "', '" . $sb_full_flap_top . "', '" . $sb_no_bottom . "', '" . $sb_full_flap_bottom . "', '" . $sb_vents_okay . "', '" . $sb_quantity_requested . "', '" . $sb_other_quantity . "', '" . $sb_frequency_order . "', '" . $sb_what_used_for . "', '" . $sb_date_needed_by . "', '" . $sb_need_pallets . "', '" . $sb_notes . "', '" . $sb_quotereq_sales_flag . "', '" . $sb_sales_desired_price . "', '" . $sb_partial_flap_top . "', '" . $sb_partial_flap_bottom . "')");
				//
				//
				//Send email
				$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_shipping_boxes.id desc limit 1";
				$g_res = db_query($getrecquery);
				$company_id = $_REQUEST["company_id"];
				while ($sb_data = array_shift($g_res)) {
					//
					$quote_item = $sb_data["quote_item"];
					$quote_id = $sb_data["quote_id"];
					//Get Item Name
					$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
					$quote_item_rs = array_shift($getquotequery);
					$quote_item_name = $quote_item_rs['item'];
					//
					//---- Send auto email------------------------------------------
					//
					if ($sb_data['sb_item_length'] == "") {
						$sb_item_length = "0";
					} else {
						$sb_item_length = $sb_data['sb_item_length'];
					}
					//
					if ($sb_data['sb_item_width'] == "") {
						$sb_item_width = "0";
					} else {
						$sb_item_width = $sb_data['sb_item_width'];
					}
					//
					if ($sb_data['sb_item_height'] == "") {
						$sb_item_height = "0";
					} else {
						$sb_item_height = $sb_data['sb_item_height'];
					}
					//
					if ($sb_data["sb_quantity_requested"] == "Select One") {
						$sb_quantity_requested = "None";
					} else {
						if ($sb_data['sb_quantity_requested'] == "Other") {
							$sb_quantity_requested = $sb_data['sb_quantity_requested'] . "- " . $sb_data['sb_other_quantity'];
						} else {
							$sb_quantity_requested = $sb_data['sb_quantity_requested'];
						}
					}
					//
					if ($sb_data['sb_frequency_order'] == "Select One") {
						$sb_frequency_order = "None";
					} else {
						$sb_frequency_order = $sb_data['sb_frequency_order'];
					}
					//
					if ($sb_data['sb_what_used_for'] == "" || $sb_data['sb_what_used_for'] == " ") {
						$sb_what_used_for = "No";
					} else {
						$sb_what_used_for = $sb_data['sb_what_used_for'];
					}
					//
					if ($sb_data['sb_need_pallets'] == "Yes") {
						$sb_need_pallets = "Yes";
					} else {
						$sb_need_pallets = "No";
					}
					//
					if ($sb_data['sb_sales_desired_price'] == "") {
						$sb_sales_desired_price = "0.00";
					} else {
						$sb_sales_desired_price = number_format($sb_data['sb_sales_desired_price'], 2);
					}
					//
					if ($sb_data['sb_notes'] == "") {
						$sb_notes = "None";
					} else {
						$sb_notes = $sb_data['sb_notes'];
					}
					//
					if ($sb_data["sb_item_min_length"] == "") {
						$sb_item_min_length = "0";
					} else {
						$sb_item_min_length = $sb_data['sb_item_min_length'];
					}
					//
					if ($sb_data["sb_item_max_length"] == "") {
						$sb_item_max_length = "0";
					} else {
						$sb_item_max_length = $sb_data['sb_item_max_length'];
					}
					//
					if ($sb_data["sb_item_min_width"] == "") {
						$sb_item_min_width = "0";
					} else {
						$sb_item_min_width = $sb_data['sb_item_min_width'];
					}
					//
					if ($sb_data["sb_item_max_width"] == "") {
						$sb_item_max_width = "0";
					} else {
						$sb_item_max_width = $sb_data['sb_item_max_width'];
					}
					//
					if ($sb_data["sb_item_min_height"] == "") {
						$sb_item_min_height = "0";
					} else {
						$sb_item_min_height = $sb_data['sb_item_min_height'];
					}
					//
					if ($sb_data["sb_item_max_height"] == "") {
						$sb_item_max_height = "0";
					} else {
						$sb_item_max_height = $sb_data['sb_item_max_height'];
					}
					//
					if ($sb_data["sb_cubic_footage_min"] == "") {
						$sb_cubic_footage_min = "0";
					} else {
						$sb_cubic_footage_min = $sb_data['sb_cubic_footage_min'];
					}
					if ($sb_data["sb_cubic_footage_max"] == "") {
						$sb_cubic_footage_max = "0";
					} else {
						$sb_cubic_footage_max = $sb_data['sb_cubic_footage_max'];
					}
					//
					
					$sb_top = "";
					$sb_bottom = "";
					$sb_wall = "";
					$b = "";
					$t = "";
					$comma = "";
					$w = "";
					if ($sb_data['sb_wall_1'] != "Yes") {
						$sb_wall_1 = "No";
					} else {
						$sb_wall_1 = $sb_data['sb_wall_1'];

						$sb_wall .= $comma . "1ply";
						$w = "y";
					}
					//
					if ($sb_data['sb_wall_2'] != "Yes") {
						$sb_wall_2 = "No";
					} else {
						$sb_wall_2 = $sb_data['sb_wall_2'];
						if ($w == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$sb_wall .= $comma . "2ply";
					}
					//
					//
					if ($sb_data['sb_no_top'] != "Yes") {
						$sb_no_top = "No";
					} else {
						$sb_top = "No Top";
						$t = "y";
					}
					//
					if ($sb_data['sb_full_flap_top'] != "Yes") {
						$sb_full_flap_top = "No";
					} else {
						if ($t == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$sb_top .= $comma . "Full Flap Top";
					}

					if ($sb_data['sb_partial_flap_top'] != "Yes") {
						$sb_partial_flap_top = "No";
					} else {
						if ($t == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$sb_top .= $comma . "Partial Flap Top";
					}
					//
					if ($sb_data['sb_no_bottom'] != "Yes") {
						$sb_no_bottom = "No";
					} else {
						$sb_no_bottom = $sb_data['sb_no_bottom'];
						$sb_bottom .= "No Bottom";
						$b = "y";
					}
					//
					if ($sb_data['sb_full_flap_bottom'] != "Yes") {
						$sb_full_flap_bottom = "No";
					} else {
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$sb_bottom .= $comma . "Full Flap Bottom";
					}

					if ($sb_data['sb_partial_flap_bottom'] != "Yes") {
						$sb_partial_flap_bottom = "No";
					} else {
						if ($b == "y") {
							$comma = ", ";
						} else {
							$comma = "";
						}
						$sb_bottom .= $comma . "Partial Flap Bottom";
					}
					//
					if ($sb_data["sb_vents_okay"] != "Yes") {
						$sb_vents_okay = "No";
					} else {
						$sb_vents_okay = $sb_data['sb_vents_okay'];
					}
					//
					$rq_item = "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
					$rq_item .= "<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $sb_item_length . " x " . $sb_item_width . " x " . $sb_item_height . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $sb_quantity_requested . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $sb_frequency_order . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $sb_what_used_for . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $sb_need_pallets . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sb_sales_desired_price . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $sb_notes . "</li>
						</ul>";
					//-----Tolerances---------
					$rq_item .= "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Tolerances</u></span>
						<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
						<li style='padding-bottom:4px;'><strong>Size Flexibility (in): </strong> 
						Length: " . $sb_item_min_length . " - " . $sb_item_max_length . ", 
						Width: " . $sb_item_min_width . " - " . $sb_item_max_width . ", 
						Height: " . $sb_item_min_height . " - " . $sb_item_max_height . "
						</li>
						<li style='padding-bottom:4px;'><strong>Cubic Footage: </strong>
						" . $sb_cubic_footage_min . " - " . $sb_cubic_footage_max . "
						</li>
						<li style='padding-bottom:4px;'><strong># of Walls: </strong>
						  " . $sb_wall . "
						</li>
						<li style='padding-bottom:4px;'><strong>Top Config: </strong>
		  					" . $sb_top . "
		  				</li>

            			<li style='padding-bottom:4px;'><strong>Bottom Config: </strong>
						  " . $sb_bottom . "
						</li>
						
						<li style='padding-bottom:4px;'><strong>Vents Okay?: </strong>
						  " . $sb_vents_okay . "
						</li>
						</ul>";
					//
					send_demand_email($rq_item, $log, $company_id, $quote_item_name, $quote_id, 'new_q');
				}
				//End send email code
				//
				$sb_display_data = "Done";
				//
			} //End if 2
			//
			if ($quote_item_id == 3) {

				$sup_item_length = $_REQUEST["sup_item_length"];
				$sup_item_width = $_REQUEST["sup_item_width"];
				$sup_item_height = $_REQUEST["sup_item_height"];

				$sup_quantity_requested = $_REQUEST["sup_quantity_requested"];
				$sup_other_quantity = $_REQUEST["sup_other_quantity"];
				$sup_frequency_order = $_REQUEST["sup_frequency_order"];
				$sup_sales_desired_price = $_REQUEST["sup_sales_desired_price"];
				$sup_what_used_for = str_replace("'", "\'", $_REQUEST["sup_what_used_for"]);
				$sup_date_needed_by = "";
				if ($_REQUEST["sup_date_needed_by"] != "") {
					$sup_date_needed_by = date("Y-m-d", strtotime($_REQUEST["sup_date_needed_by"]));
				}
				$sup_notes = str_replace("'", "\'", ($_REQUEST["sup_notes"]));
				//
				if (isset($_REQUEST["sup_need_pallets"])) {
					$sup_need_pallets = $_REQUEST["sup_need_pallets"];
				} else {
					$sup_need_pallets = "No";
				}
				if (isset($_REQUEST["sup_quotereq_sales_flag"])) {
					$sup_quotereq_sales_flag = $_REQUEST["sup_quotereq_sales_flag"];
				} else {
					$sup_quotereq_sales_flag = "No";
				}
				//
				$query1 = db_query("INSERT INTO quote_supersacks (quote_id,sup_item_length, sup_item_width, sup_item_height, sup_quantity_requested, sup_other_quantity, sup_frequency_order, sup_what_used_for, sup_date_needed_by, sup_need_pallets, sup_notes, sup_quotereq_sales_flag, sup_sales_desired_price) VALUES ('" . $quote_id . "', '" . $sup_item_length . "', '" . $sup_item_width . "', '" . $sup_item_height . "', '" . $sup_quantity_requested . "', '" . $sup_other_quantity . "', '" . $sup_frequency_order . "', '" . $sup_what_used_for . "', '" . $sup_date_needed_by . "', '" . $sup_need_pallets . "', '" . $sup_notes . "', '" . $sup_quotereq_sales_flag . "', '" . $sup_sales_desired_price . "')");
				//
				//Send email
				$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_supersacks.id desc limit 1";
				$sup_res = db_query($getrecquery);
				$company_id = $_REQUEST["company_id"];
				while ($sup_data = array_shift($sup_res)) {
					//
					$quote_item = $sup_data["quote_item"];
					$quote_id = $sup_data["quote_id"];
					//Get Item Name
					$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
					$quote_item_rs = array_shift($getquotequery);
					$quote_item_name = $quote_item_rs['item'];
					//
					//---- Send auto email------------------------------------------
					//
					if ($sup_data['sup_item_length'] == "") {
						$sup_item_length = "0";
					} else {
						$sup_item_length = $sup_data['sup_item_length'];
					}
					//
					if ($sup_data['sup_item_width'] == "") {
						$sup_item_width = "0";
					} else {
						$sup_item_width = $sup_data['sup_item_width'];
					}
					//
					if ($sup_data["sup_item_height"] == "") {
						$sup_item_height = "0";
					} else {
						$sup_item_height = $sup_data['sup_item_height'];
					}
					//
					if ($sup_data["sup_quantity_requested"] == "Select One") {
						$sup_quantity_requested = "None";
					} else {
						if ($sup_data['sup_quantity_requested'] == "Other") {
							$sup_quantity_requested = $sup_data['sup_quantity_requested'] . "- " . $sup_data['sup_other_quantity'];
						} else {
							$sup_quantity_requested = $sup_data['sup_quantity_requested'];
						}
					}
					//
					if ($sup_data['sup_frequency_order'] == "Select One") {
						$sup_frequency_order = "None";
					} else {
						$sup_frequency_order = $sup_data['sup_frequency_order'];
					}
					//
					if ($sup_data['sup_what_used_for'] == "" || $sup_data['sup_what_used_for'] == " ") {
						$sup_what_used_for = "No";
					} else {
						$sup_what_used_for = $sup_data['sup_what_used_for'];
					}
					//
					if ($sup_data['sup_need_pallets'] == "Yes") {
						$sup_need_pallets = "Yes";
					} else {
						$sup_need_pallets = "No";
					}
					//
					if ($sup_data['sup_sales_desired_price'] == "") {
						$sup_sales_desired_price = "0.00";
					} else {
						$sup_sales_desired_price = number_format($sup_data['sup_sales_desired_price'], 2);
					}
					//
					if ($sup_data['sup_notes'] == "") {
						$sup_notes = "None";
					} else {
						$sup_notes = $sup_data['sup_notes'];
					}

					//
					//
					$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
					$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $sup_item_length . " x " . $sup_item_width . " x " . $sup_item_height . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $sup_quantity_requested . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $sup_frequency_order . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $sup_what_used_for . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $sup_need_pallets . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sup_sales_desired_price . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $sup_notes . "</li>
						</ul>";
					//
					//
					send_demand_email($rq_item, $log, $company_id, $quote_item_name, $quote_id, 'new_q');
				}
				//End email code
				//
				$sup_update_data = "Done";
			}
			//
			if ($quote_item_id == 4) {
				$pal_item_length = $_REQUEST["pal_item_length"];
				$pal_item_width = $_REQUEST["pal_item_width"];

				$pal_quantity_requested = $_REQUEST["pal_quantity_requested"];
				$pal_other_quantity = $_REQUEST["pal_other_quantity"];
				$pal_frequency_order = $_REQUEST["pal_frequency_order"];
				$pal_what_used_for = str_replace("'", "\'", $_REQUEST["pal_what_used_for"]);
				$pal_date_needed_by = date("Y-m-d", strtotime($_REQUEST["pal_date_needed_by"]));

				$pal_sales_desired_price = $_REQUEST["pal_sales_desired_price"];

				$pal_quotereq_sales_flag = $_REQUEST["pal_quotereq_sales_flag"];
				$pal_note = str_replace("'", "\'", ($_REQUEST["pal_note"]));

				$pal_grade_a = $_REQUEST["pal_grade_a"];
				$pal_grade_b = $_REQUEST["pal_grade_b"];
				$pal_grade_c = $_REQUEST["pal_grade_c"];
				$pal_material_wooden = $_REQUEST["pal_material_wooden"];
				$pal_material_plastic = $_REQUEST["pal_material_plastic"];
				$pal_material_corrugate = $_REQUEST["pal_material_corrugate"];
				$pal_entry_2way = $_REQUEST["pal_entry_2way"];
				$pal_entry_4way = $_REQUEST["pal_entry_4way"];
				$pal_structure_stringer = $_REQUEST["pal_structure_stringer"];
				$pal_structure_block = $_REQUEST["pal_structure_block"];
				$pal_heat_treated = $_REQUEST["pal_heat_treated"];
				//

				if (isset($_REQUEST["pal_quotereq_sales_flag"])) {
					$pal_quotereq_sales_flag = $_REQUEST["pal_quotereq_sales_flag"];
				} else {
					$pal_quotereq_sales_flag = "No";
				}
				//
				$query1 = db_query("INSERT INTO quote_pallets (quote_id,pal_item_length, pal_item_width, pal_quantity_requested, pal_other_quantity, pal_frequency_order, pal_what_used_for, pal_date_needed_by, pal_note, pal_quotereq_sales_flag, pal_sales_desired_price, pal_grade_a, pal_grade_b, pal_grade_c, pal_material_wooden, pal_material_plastic, pal_material_corrugate, pal_entry_2way, pal_entry_4way, pal_structure_stringer, pal_structure_block, pal_heat_treated) VALUES ('" . $quote_id . "', '" . $pal_item_length . "', '" . $pal_item_width . "', '" . $pal_quantity_requested . "', '" . $pal_other_quantity . "', '" . $pal_frequency_order . "', '" . $pal_what_used_for . "', '" . $pal_date_needed_by . "', '" . $pal_note . "', '" . $pal_quotereq_sales_flag . "', '" . $pal_sales_desired_price . "', '" . $pal_grade_a . "', '" . $pal_grade_b . "', '" . $pal_grade_c . "', '" . $pal_material_wooden . "', '" . $pal_material_plastic . "', '" . $pal_material_corrugate . "', '" . $pal_entry_2way . "', '" . $pal_entry_4way . "', '" . $pal_structure_stringer . "', '" . $pal_structure_block . "', '" . $pal_heat_treated . "')");
				//
				//Send email
				$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_pallets.id desc limit 1";
				$pal_res = db_query($getrecquery);
				$company_id = $_REQUEST["company_id"];
				while ($pal_data = array_shift($pal_res)) {
					//
					$quote_item = $pal_data["quote_item"];
					$quote_id = $pal_data["quote_id"];
					//Get Item Name
					$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
					$quote_item_rs = array_shift($getquotequery);
					$quote_item_name = $quote_item_rs['item'];
					//
					//---- Send auto email------------------------------------------
					//
					if ($pal_data['pal_item_length'] == "") {
						$pal_item_length = "0";
					} else {
						$pal_item_length = $pal_data['pal_item_length'];
					}
					//
					if ($pal_data['pal_item_width'] == "") {
						$pal_item_width = "0";
					} else {
						$pal_item_width = $pal_data['pal_item_width'];
					}
					//
					$pal_quantity_requested = "";
					if ($pal_data["pal_quantity_requested"] == "Select One") {
						$pal_quantity_requested = "None";
					} else {
						if ($pal_data['pal_quantity_requested'] == "Other") {
							$pal_quantity_requested = $pal_data['pal_quantity_requested'] . "- " . $pal_data['pal_other_quantity'];
						} else {
							$pal_quantity_requested = $pal_data['pal_quantity_requested'];
						}
					}
					//
					if ($pal_data['pal_frequency_order'] == "Select One") {
						$pal_frequency_order = "None";
					} else {
						$pal_frequency_order = $pal_data['pal_frequency_order'];
					}
					//
					if ($pal_data['pal_what_used_for'] == "" || $pal_data['pal_what_used_for'] == " ") {
						$pal_what_used_for = "No";
					} else {
						$pal_what_used_for = $pal_data['pal_what_used_for'];
					}

					//
					if ($pal_data['pal_sales_desired_price'] == "") {
						$pal_sales_desired_price = "0.00";
					} else {
						$pal_sales_desired_price = number_format($pal_data['pal_sales_desired_price'], 2);
					}
					//
					if ($pal_data['pal_note'] == "") {
						$pal_note = "None";
					} else {
						$pal_note = $pal_data['pal_note'];
					}

					//
					$grade = "";
					if ($pal_data['pal_grade_a'] == "Yes") {
						$pal_grade = 'A';
						$grade = 'Y';
					} else {
						$pal_grade = '';
					}

					if ($pal_data['pal_grade_b'] == "Yes") {
						if ($grade == 'Y') {
							$pal_grade .= ', B';
						} else {
							$pal_grade = 'B';
							$grade = 'Y';
						}
					} else {
						$pal_grade .= '';
					}

					if ($pal_data['pal_grade_c'] == "Yes") {
						if ($grade == 'Y') {
							$pal_grade .= ', C';
						} else {
							$pal_grade = 'C';
						}
					} else {
						$pal_grade .= '';
					}

					//
					$pal_material = '';
					$material = "";
					if ($pal_data['pal_material_wooden'] == "Yes") {
						$pal_material = 'Wooden';
						$material = 'Y';
					}

					if ($pal_data['pal_material_plastic'] == "Yes") {
						if ($material == 'Y') {
							$pal_material .= ', Plastic';
						} else {
							$pal_material = 'Plastic';
							$material = 'Y';
						}
					}

					if ($pal_data['pal_material_corrugate'] == "Yes") {
						if ($material == 'Y') {
							$pal_material .= ', Corrugate';
						} else {
							$pal_material = 'Corrugate';
						}
					}

					//
					$pal_entry = '';
					$entry = "";
					if ($pal_data['pal_entry_2way'] == "Yes") {
						$pal_entry = '2Way';
						$entry = 'Y';
					}

					if ($pal_data['pal_entry_4way'] == "Yes") {
						if ($entry == 'Y') {
							$pal_entry .= ', 4Way';
						} else {
							$pal_entry = '4Way';
						}
					}

					//
					$structure = "";
					$pal_structure = '';
					if ($pal_data['pal_structure_stringer'] == "Yes") {
						$pal_structure = 'Stringer';
						$structure = 'Y';
					}

					if ($pal_data['pal_structure_block'] == "Yes") {
						if ($structure == 'Y') {
							$pal_structure .= ', Block';
						} else {
							$pal_structure = 'Block';
						}
					}

					$pal_heat_treat = $pal_data["pal_heat_treated"];

					//
					$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
					$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $pal_item_length . " x " . $pal_item_width . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $pal_quantity_requested . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $pal_frequency_order . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $pal_what_used_for . "</li>

						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $pal_sales_desired_price . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $pal_note . "</li>
						</ul>";
					//
					//-----Tolerances---------
					$rq_item .= "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-weight:600; font-size:16pt;\"><u>Tolerances</u></span>
						<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
						<li style='padding-bottom:4px;'><strong>Grade: </strong> 
						" . $pal_grade . "
						.</li>
						<li style='padding-bottom:4px;'><strong>Material: </strong>
						" . $pal_material . "
						</li>
						<li style='padding-bottom:4px;'><strong>Entry: </strong>
						  " . $pal_entry . "
						</li>
						<li style='padding-bottom:4px;'><strong>Structure: </strong>
		  					" . $pal_structure . "
		  				</li>
            			<li><strong>Heat Treat: </strong>
						  " . $pal_heat_treat . "
						</li>
						
						</ul>";
					//
					send_demand_email($rq_item, $log, $company_id, $quote_item_name, $quote_id, 'new_q');
				}
				//End email code
				$pal_display_data = "Done";
				//
			}
			//
			if ($quote_item_id == 5) {
				$dbi_notes = $_REQUEST["dbi_notes"];
				//
				//
				$query1 = db_query("INSERT INTO quote_dbi (quote_id, dbi_notes) VALUES ('" . $quote_id . "', '" . $dbi_notes . "')");
				//
				//
				$dbi_display_data = "Done";
			}
			//
			if ($quote_item_id == 6) {
				$recycling_notes = $_REQUEST["recycling_notes"];
				//
				//
				$query1 = db_query("INSERT INTO quote_recycling (quote_id, recycling_notes) VALUES ('" . $quote_id . "', '" . $recycling_notes . "')");
				//
				$rec_display_data = "Done";
			}
			//
			if ($quote_item_id == 7) {
				$other_quantity_requested = $_REQUEST["other_quantity_requested"];
				$other_other_quantity = $_REQUEST["other_other_quantity"];
				$other_frequency_order = $_REQUEST["other_frequency_order"];
				$other_what_used_for = str_replace("'", "\'", $_REQUEST["other_what_used_for"]);
				$other_date_needed_by = "";
				if ($_REQUEST["other_date_needed_by"] != "") {
					$other_date_needed_by = date("Y-m-d", strtotime($_REQUEST["other_date_needed_by"]));
				}
				$other_need_pallets = $_REQUEST["other_need_pallets"];

				$other_quotereq_sales_flag = $_REQUEST["other_quotereq_sales_flag"];
				$other_note = str_replace("'", "\'", ($_REQUEST["other_note"]));
				//
				if (isset($_REQUEST["other_need_pallets"])) {
					$other_need_pallets = $_REQUEST["other_need_pallets"];
				} else {
					$other_need_pallets = "No";
				}
				if (isset($_REQUEST["other_quotereq_sales_flag"])) {
					$other_quotereq_sales_flag = $_REQUEST["other_quotereq_sales_flag"];
				} else {
					$other_quotereq_sales_flag = "No";
				}
				//
				$query1 = db_query("INSERT INTO quote_other (quote_id, other_quantity_requested, other_other_quantity, other_frequency_order, other_what_used_for, other_date_needed_by, other_need_pallets, other_note, other_quotereq_sales_flag) VALUES ('" . $quote_id . "', '" . $other_quantity_requested . "', '" . $other_other_quantity . "', '" . $other_frequency_order . "', '" . $other_what_used_for . "', '" . $other_date_needed_by . "', '" . $other_need_pallets . "', '" . $other_note . "', '" . $other_quotereq_sales_flag . "')");
				//
				//Send email
				$getrecquery = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_other.id desc limit 1";
				$other_res = db_query($getrecquery);
				$company_id = $_REQUEST["company_id"];
				while ($other_data = array_shift($other_res)) {
					//
					$quote_item = $other_data["quote_item"];
					$quote_id = $other_data["quote_id"];
					//Get Item Name
					$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
					$quote_item_rs = array_shift($getquotequery);
					$quote_item_name = $quote_item_rs['item'];
					//
					//---- Send auto email------------------------------------------
					//
					if ($other_data["other_quantity_requested"] == "Select One") {
						$other_quantity_requested = "None";
					} else {
						if ($other_data['other_quantity_requested'] == "Other") {
							$other_quantity_requested = $other_data['other_quantity_requested'] . "- " . $other_data['other_other_quantity'];
						} else {
							$other_quantity_requested = $other_data['other_quantity_requested'];
						}
					}
					//
					if ($other_data['other_frequency_order'] == "Select One") {
						$other_frequency_order = "None";
					} else {
						$other_frequency_order = $other_data['other_frequency_order'];
					}
					//
					if ($other_data['other_what_used_for'] == "" || $other_data['other_what_used_for'] == " ") {
						$other_what_used_for = "None";
					} else {
						$other_what_used_for = $other_data['other_what_used_for'];
					}

					//
					if ($other_data['other_need_pallets'] == "") {
						$other_need_pallets = "No";
					} else {
						$other_need_pallets = $other_data['other_need_pallets'];
					}
					//
					if ($other_data['other_note'] == "") {
						$other_note = "None";
					} else {
						$other_note = $other_data['other_note'];
					}

					//
					$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
					$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>

						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $other_quantity_requested . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $other_frequency_order . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $other_what_used_for . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $other_need_pallets . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $other_note . "</li>
						</ul>";
					//
					//
					/*send_demand_email($rq_item, $log, $company_id, $quote_item_name, $quote_id,'new_q');*/
				}
				//
				//Display all data
				$other_display_data = "Done";
			}
			//

			//
		}
	}
	//---------------------------------------------------------------------------------------------------------\
	$client_dash_flg = $_REQUEST["client_dash_flg"];
	$updateflag = 0;
	$getedited_val = "";
	if (isset($_REQUEST["updatequotedata"])) {
		if ($_REQUEST["updatequotedata"] == 1) {
			//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit();
			$tableid = $_REQUEST["tableid"];
			$g_item_length = $_REQUEST["g_item_length"];
			$g_item_width = $_REQUEST["g_item_width"];
			$g_item_height = $_REQUEST["g_item_height"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];
			//
			$g_item_min_height = $_REQUEST["g_item_min_height"];
			$g_item_max_height = $_REQUEST["g_item_max_height"];
			//
			if (isset($_REQUEST["g_shape_rectangular"])) {
				$g_shape_rectangular = $_REQUEST["g_shape_rectangular"];
			} else {
				$g_shape_rectangular = "No";
			}

			if (isset($_REQUEST["g_shape_octagonal"])) {
				$g_shape_octagonal = $_REQUEST["g_shape_octagonal"];
			} else {
				$g_shape_octagonal = "No";
			}
			//
			if (isset($_REQUEST["g_wall_1"])) {
				$g_wall_1 = $_REQUEST["g_wall_1"];
			} else {
				$g_wall_1 = "No";
			}
			if (isset($_REQUEST["g_wall_2"])) {
				$g_wall_2 = $_REQUEST["g_wall_2"];
			} else {
				$g_wall_2 = "No";
			}
			if (isset($_REQUEST["g_wall_3"])) {
				$g_wall_3 = $_REQUEST["g_wall_3"];
			} else {
				$g_wall_3 = "No";
			}
			if (isset($_REQUEST["g_wall_4"])) {
				$g_wall_4 = $_REQUEST["g_wall_4"];
			} else {
				$g_wall_4 = "No";
			}
			if (isset($_REQUEST["g_wall_5"])) {
				$g_wall_5 = $_REQUEST["g_wall_5"];
			} else {
				$g_wall_5 = "No";
			}
			if (isset($_REQUEST["g_wall_6"])) {
				$g_wall_6 = $_REQUEST["g_wall_6"];
			} else {
				$g_wall_6 = "No";
			}
			if (isset($_REQUEST["g_wall_7"])) {
				$g_wall_7 = $_REQUEST["g_wall_7"];
			} else {
				$g_wall_7 = "No";
			}
			if (isset($_REQUEST["g_wall_8"])) {
				$g_wall_8 = $_REQUEST["g_wall_8"];
			} else {
				$g_wall_8 = "No";
			}
			if (isset($_REQUEST["g_wall_9"])) {
				$g_wall_9 = $_REQUEST["g_wall_9"];
			} else {
				$g_wall_9 = "No";
			}
			if (isset($_REQUEST["g_wall_10"])) {
				$g_wall_10 = $_REQUEST["g_wall_10"];
			} else {
				$g_wall_10 = "No";
			}
			//
			if (isset($_REQUEST["g_no_top"])) {
				$g_no_top = $_REQUEST["g_no_top"];
			} else {
				$g_no_top = "No";
			}

			if (isset($_REQUEST["g_lid_top"])) {
				$g_lid_top = $_REQUEST["g_lid_top"];
			} else {
				$g_lid_top = "No";
			}

			if (isset($_REQUEST["g_partial_flap_top"])) {
				$g_partial_flap_top = $_REQUEST["g_partial_flap_top"];
			} else {
				$g_partial_flap_top = "No";
			}

			if (isset($_REQUEST["g_full_flap_top"])) {
				$g_full_flap_top = $_REQUEST["g_full_flap_top"];
			} else {
				$g_full_flap_top = "No";
			}
			//

			if (isset($_REQUEST["g_no_bottom_config"])) {
				$g_no_bottom_config = $_REQUEST["g_no_bottom_config"];
			} else {
				$g_no_bottom_config = "No";
			}

			if (isset($_REQUEST["g_partial_flap_w"])) {
				$g_partial_flap_w = $_REQUEST["g_partial_flap_w"];
			} else {
				$g_partial_flap_w = "No";
			}

			if (isset($_REQUEST["g_tray_bottom"])) {
				$g_tray_bottom = $_REQUEST["g_tray_bottom"];
			} else {
				$g_tray_bottom = "No";
			}

			if (isset($_REQUEST["g_full_flap_bottom"])) {
				$g_full_flap_bottom = $_REQUEST["g_full_flap_bottom"];
			} else {
				$g_full_flap_bottom = "No";
			}

			if (isset($_REQUEST["g_partial_flap_wo"])) {
				$g_partial_flap_wo = $_REQUEST["g_partial_flap_wo"];
			} else {
				$g_partial_flap_wo = "No";
			}
			//

			if (isset($_REQUEST["g_vents_okay"])) {
				$g_vents_okay = $_REQUEST["g_vents_okay"];
			} else {
				$g_vents_okay = "No";
			}

			if (isset($_REQUEST["need_pallets"])) {
				$need_pallets = $_REQUEST["need_pallets"];
			} else {
				$need_pallets = "No";
			}
			//
			$g_quantity_request = $_REQUEST["g_quantity_request"];
			if ($g_quantity_request == "Other") {
				$g_other_quantity = $_REQUEST["g_other_quantity"];
			} else {
				$g_other_quantity = 0;
			}
			//
			if (isset($_REQUEST["quoterequest_saleslead_flag"])) {
				$quoterequest_saleslead_flag = $_REQUEST["quoterequest_saleslead_flag"];
			} else {
				$quoterequest_saleslead_flag = "No";
			}
			//
			$g_frequency_order = $_REQUEST["g_frequency_order"];
			$g_what_used_for = str_replace("'", "\'", $_REQUEST["g_what_used_for"]);
			$date_needed_by = "";
			if ($_REQUEST["date_needed_by"] != "") {
				$date_needed_by = date("Y-m-d", strtotime($_REQUEST["date_needed_by"]));
			}

			$g_item_note = str_replace("'", "\'", ($_REQUEST["g_item_note"]));
			$sales_desired_price_g = str_replace("'", "\'", ($_REQUEST["sales_desired_price_g"]));
			//--------------------------------------------------------------------
			//Check edited value
			$getrecquery_g = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_gaylord.id = " . $tableid;
			$g_oldres = db_query($getrecquery_g);
			$company_id = $_REQUEST["company_id"];
			$g_olddata = array_shift($g_oldres);
			//
			$quoteitem = $g_olddata["quote_item"];
			//Get Item Name
			$getquotequery = db_query("SELECT * FROM quote_request_item WHERE quote_rq_id=" . $quoteitem);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			if ($g_item_length != $g_olddata["g_item_length"]) {
				$new_g_item_length = "<span style='color:#B10A0A;'>" . $g_item_length . "</span>";
			} else {
				$new_g_item_length = $g_item_length;
			}
			if ($g_item_width != $g_olddata["g_item_width"]) {
				$new_g_item_width = "<span style='color:#B10A0A;'>" . $g_item_width . "</span>";
			} else {
				$new_g_item_width = $g_item_width;
			}
			if ($g_item_height != $g_olddata["g_item_height"]) {
				$new_g_item_height = "<span style='color:#B10A0A;'>" . $g_item_height . "</span>";
			} else {
				$new_g_item_height = $g_item_height;
			}
			if (($g_item_length != $g_olddata["g_item_length"]) || ($g_item_width != $g_olddata["g_item_width"]) || ($g_item_height != $g_olddata["g_item_height"])) {
				$getedited_val = "[Ideal Size (in)] changed from \' " . $g_olddata["g_item_length"] . "x" . $g_olddata["g_item_width"] . "x" . $g_olddata["g_item_height"] . "\' to \'" . $g_item_length . "x" . $g_item_width . "x" . $g_item_height . "\'<br>";
			}
			if ($g_quantity_request != "Other") {
				if ($g_quantity_request != $g_olddata["g_quantity_request"]) {
					$new_g_quantity_request = "<span style='color:#B10A0A;'>" . $g_quantity_request . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $g_olddata["g_quantity_request"] . "\' to \'" . $g_quantity_request . "\'<br>";
				} else {
					$new_g_quantity_request = $g_quantity_request;
				}
			} else {
				if ($g_olddata['g_other_quantity'] != $g_other_quantity) {
					$new_g_quantity_request = "<span style='color:#B10A0A;'>" . $g_quantity_request . "-" . $g_other_quantity . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $g_olddata["g_quantity_request"] . "-" . $g_olddata['g_other_quantity'] . "\' to \'" . $g_quantity_request . "-" . $g_other_quantity . "\'<br>";
				} else {
					$new_g_quantity_request = $g_quantity_request . "-" . $g_other_quantity;
				}
			}
			//
			if ($g_frequency_order != $g_olddata["g_frequency_order"]) {
				$new_g_frequency_order = "<span style='color:#B10A0A;'>" . $g_frequency_order . "</span>";
				$getedited_val .= "[Frequency of Order] changed from \' " . $g_olddata["g_frequency_order"] . "\' to \'" . $g_frequency_order . "\'<br>";
			} else {
				$new_g_frequency_order = $g_frequency_order;
			}
			//
			if ($g_what_used_for != $g_olddata["g_what_used_for"]) {
				$new_g_what_used_for = "<span style='color:#B10A0A;'>" . $g_what_used_for . "</span>";
				$getedited_val .= "[What Used For?] changed from \' " . $g_olddata["g_what_used_for"] . "\' to \'" . $g_what_used_for . "\'<br>";
			} else {
				$new_g_what_used_for = $g_what_used_for;
			}
			//
			$need_pallets1 = "";
			if ($need_pallets == "") {
				$need_pallets1 = "No";
			}
			if ($need_pallets != $g_olddata["need_pallets"]) {
				$new_need_pallets = "<span style='color:#B10A0A;'>" . $need_pallets1 . "</span>";
				$getedited_val .= "[Also Need Pallets?] changed from \' " . $g_olddata["need_pallets"] . "\' to \'" . $need_pallets . "\'<br>";
			} else {
				$new_need_pallets = $need_pallets;
			}
			//
			if ($sales_desired_price_g != $g_olddata["sales_desired_price_g"]) {
				$new_sales_desired_price_g = "<span style='color:#B10A0A;'>" . $sales_desired_price_g . "</span>";
				$getedited_val .= "[Desired Price] changed from \' " . $g_olddata["sales_desired_price_g"] . "\' to \'" . $sales_desired_price_g . "\'<br>";
			} else {
				$new_sales_desired_price_g = $sales_desired_price_g;
			}
			if ($g_item_note != $g_olddata["g_item_note"]) {
				$new_g_item_note = "<span style='color:#B10A0A;'>" . $g_item_note . "</span>";
				$getedited_val .= "[Note] changed from \' " . $g_olddata["g_item_note"] . "\' to \'" . $g_item_note . "\'<br>";
			} else {
				$new_g_item_note = $g_item_note;
			}
			if ($g_item_min_height != $g_olddata["g_item_min_height"]) {
				$new_g_item_min_height = "<span style='color:#B10A0A;'>" . $g_item_min_height . "</span>";
			} else {
				$new_g_item_min_height = $g_item_min_height;
			}
			if ($g_item_max_height != $g_olddata["g_item_max_height"]) {
				$new_g_item_max_height = "<span style='color:#B10A0A;'>" . $g_item_max_height . "</span>";
			} else {
				$new_g_item_max_height = $g_item_max_height;
			}

			if (($g_item_min_height != $g_olddata["g_item_min_height"]) || ($g_item_max_height != $g_olddata["g_item_max_height"])) {
				$getedited_val .= "[Height Flexibility ] changed from \' " . $g_olddata["g_item_min_height"] . "-" . $g_olddata["g_item_max_height"] . "\' to \'" . $g_item_min_height . "-" . $g_item_max_height . "\'<br>";
			}
			//
			if ($g_shape_rectangular != $g_olddata["g_shape_rectangular"]) {
				$new_g_shape_rectangular = "<span style='color:#B10A0A;'>" . $g_shape_rectangular . "</span>";
				$getedited_val .= "[Shape - Rectangular] changed from \' " . $g_olddata["g_shape_rectangular"] . "\' to \'" . $g_shape_rectangular . "\'<br>";
			} else {
				$new_g_shape_rectangular = $g_shape_rectangular;
			}
			//
			if ($g_shape_octagonal != $g_olddata["g_shape_octagonal"]) {
				$new_g_shape_octagonal = "<span style='color:#B10A0A;'>" . $g_shape_octagonal . "</span>";
				$getedited_val .= "[Shape - Octagonal] changed from \' " . $g_olddata["g_shape_octagonal"] . "\' to \'" . $g_shape_octagonal . "\'<br>";
			} else {
				$new_g_shape_octagonal = $g_shape_octagonal;
			}
			if ($g_wall_1 != $g_olddata["g_wall_1"]) {
				$new_g_wall_1 = "<span style='color:#B10A0A;'>" . $g_wall_1 . "</span>";
				$getedited_val .= "[# of Walls - 1ply] changed from \' " . $g_olddata["g_wall_1"] . "\' to \'" . $g_wall_1 . "\'<br>";
			} else {
				$new_g_wall_1 = $g_wall_1;
			}
			if ($g_wall_2 != $g_olddata["g_wall_2"]) {
				$new_g_wall_2 = "<span style='color:#B10A0A;'>" . $g_wall_2 . "</span>";
				$getedited_val .= "[# of Walls - 2ply] changed from \' " . $g_olddata["g_wall_2"] . "\' to \'" . $g_wall_2 . "\'<br>";
			} else {
				$new_g_wall_2 = $g_wall_2;
			}
			if ($g_wall_3 != $g_olddata["g_wall_3"]) {
				$new_g_wall_3 = "<span style='color:#B10A0A;'>" . $g_wall_3 . "</span>";
				$getedited_val .= "[# of Walls - 3ply] changed from \' " . $g_olddata["g_wall_3"] . "\' to \'" . $g_wall_3 . "\'<br>";
			} else {
				$new_g_wall_3 = $g_wall_3;
			}
			if ($g_wall_4 != $g_olddata["g_wall_4"]) {
				$new_g_wall_4 = "<span style='color:#B10A0A;'>" . $g_wall_4 . "</span>";
				$getedited_val .= "[# of Walls - 4ply] changed from \' " . $g_olddata["g_wall_4"] . "\' to \'" . $g_wall_4 . "\'<br>";
			} else {
				$new_g_wall_4 = $g_wall_4;
			}
			if ($g_wall_5 != $g_olddata["g_wall_5"]) {
				$new_g_wall_5 = "<span style='color:#B10A0A;'>" . $g_wall_5 . "</span>";
				$getedited_val .= "[# of Walls - 5ply] changed from \' " . $g_olddata["g_wall_5"] . "\' to \'" . $g_wall_5 . "\'<br>";
			} else {
				$new_g_wall_5 = $g_wall_5;
			}
			if ($g_wall_6 != $g_olddata["g_wall_6"]) {
				$new_g_wall_6 = "<span style='color:#B10A0A;'>" . $g_wall_6 . "</span>";
				$getedited_val .= "[# of Walls - 6ply] changed from \' " . $g_olddata["g_wall_6"] . "\' to \'" . $g_wall_6 . "\'<br>";
			} else {
				$new_g_wall_6 = $g_wall_6;
			}
			if ($g_wall_7 != $g_olddata["g_wall_7"]) {
				$new_g_wall_7 = "<span style='color:#B10A0A;'>" . $g_wall_7 . "</span>";
				$getedited_val .= "[# of Walls - 7ply] changed from \' " . $g_olddata["g_wall_7"] . "\' to \'" . $g_wall_7 . "\'<br>";
			} else {
				$new_g_wall_7 = $g_wall_7;
			}
			if ($g_wall_8 != $g_olddata["g_wall_8"]) {
				$new_g_wall_8 = "<span style='color:#B10A0A;'>" . $g_wall_8 . "</span>";
				$getedited_val .= "[# of Walls - 8ply] changed from \' " . $g_olddata["g_wall_8"] . "\' to \'" . $g_wall_8 . "\'<br>";
			} else {
				$new_g_wall_8 = $g_wall_8;
			}
			if ($g_wall_9 != $g_olddata["g_wall_9"]) {
				$new_g_wall_9 = "<span style='color:#B10A0A;'>" . $g_wall_9 . "</span>";
				$getedited_val .= "[# of Walls - 9ply] changed from \' " . $g_olddata["g_wall_9"] . "\' to \'" . $g_wall_9 . "\'<br>";
			} else {
				$new_g_wall_9 = $g_wall_9;
			}
			if ($g_wall_10 != $g_olddata["g_wall_10"]) {
				$new_g_wall_10 = "<span style='color:#B10A0A;'>" . $g_wall_10 . "</span>";
				$getedited_val .= "[# of Walls - 10ply] changed from \' " . $g_olddata["g_wall_10"] . "\' to \'" . $g_wall_10 . "\'<br>";
			} else {
				$new_g_wall_10 = $g_wall_10;
			}
			if ($g_no_top != $g_olddata["g_no_top"]) {
				$new_g_no_top = "<span style='color:#B10A0A;'>" . $g_no_top . "</span>";
				$getedited_val .= "[Top Config - No Top] changed from \' " . $g_olddata["g_no_top"] . "\' to \'" . $g_no_top . "\'<br>";
			} else {
				$new_g_no_top = $g_no_top;
			}
			if ($g_lid_top != $g_olddata["g_lid_top"]) {
				$new_g_lid_top = "<span style='color:#B10A0A;'>" . $g_lid_top . "</span>";
				$getedited_val .= "[Top Config - Lid Top] changed from \' " . $g_olddata["g_lid_top"] . "\' to \'" . $g_lid_top . "\'<br>";
			} else {
				$new_g_lid_top = $g_lid_top;
			}
			if ($g_partial_flap_top != $g_olddata["g_partial_flap_top"]) {
				$new_g_partial_flap_top = "<span style='color:#B10A0A;'>" . $g_partial_flap_top . "</span>";
				$getedited_val .= "[Top Config - Partial Flap Top] changed from \' " . $g_olddata["g_partial_flap_top"] . "\' to \'" . $g_partial_flap_top . "\'<br>";
			} else {
				$new_g_partial_flap_top = $g_partial_flap_top;
			}
			if ($g_full_flap_top != $g_olddata["g_full_flap_top"]) {
				$new_g_full_flap_top = "<span style='color:#B10A0A;'>" . $g_full_flap_top . "</span>";
				$getedited_val .= "[Top Config - Full Flap Top] changed from \' " . $g_olddata["g_full_flap_top"] . "\' to \'" . $g_full_flap_top . "\'<br>";
			} else {
				$new_g_full_flap_top = $g_full_flap_top;
			}
			//
			if ($g_no_bottom_config != $g_olddata["g_no_bottom_config"]) {
				$new_g_no_bottom_config = "<span style='color:#B10A0A;'>" . $g_no_bottom_config . "</span>";
				$getedited_val .= "[Bottom Config - No Bottom] changed from \' " . $g_olddata["g_no_bottom_config"] . "\' to \'" . $g_no_bottom_config . "\'<br>";
			} else {
				$new_g_no_bottom_config = $g_no_bottom_config;
			}
			if ($g_tray_bottom != $g_olddata["g_tray_bottom"]) {
				$new_g_tray_bottom = "<span style='color:#B10A0A;'>" . $g_tray_bottom . "</span>";
				$getedited_val .= "[Bottom Config - Tray Bottom] changed from \' " . $g_olddata["g_tray_bottom"] . "\' to \'" . $g_tray_bottom . "\'<br>";
			} else {
				$new_g_tray_bottom = $g_tray_bottom;
			}
			if ($g_partial_flap_wo != $g_olddata["g_partial_flap_wo"]) {
				$new_g_partial_flap_wo = "<span style='color:#B10A0A;'>" . $g_partial_flap_wo . "</span>";
				$getedited_val .= "[Bottom Config - Partial Flap w/o SlipSheet ] changed from \' " . $g_olddata["g_partial_flap_wo"] . "\' to \'" . $g_partial_flap_wo . "\'<br>";
			} else {
				$new_g_partial_flap_wo = $g_partial_flap_wo;
			}
			if ($g_partial_flap_w != $g_olddata["g_partial_flap_w"]) {
				$new_g_partial_flap_w = "<span style='color:#B10A0A;'>" . $g_partial_flap_w . "</span>";
				$getedited_val .= "[Bottom Config - Partial Flap w/ Slipsheet] changed from \' " . $g_olddata["g_partial_flap_w"] . "\' to \'" . $g_partial_flap_w . "\'<br>";
			} else {
				$new_g_partial_flap_w = $g_partial_flap_w;
			}
			if ($g_full_flap_bottom != $g_olddata["g_full_flap_bottom"]) {
				$new_g_full_flap_bottom = "<span style='color:#B10A0A;'>" . $g_full_flap_bottom . "</span>";
				$getedited_val .= "[Bottom Config - Full Flap Bottom] changed from \' " . $g_olddata["g_full_flap_bottom"] . "\' to \'" . $g_full_flap_bottom . "\'<br>";
			} else {
				$new_g_full_flap_bottom = $g_full_flap_bottom;
			}
			//
			$g_vents_okay1 = "";
			if ($g_vents_okay == "") {
				$g_vents_okay1 = "No";
			}
			if ($g_vents_okay != $g_olddata["g_vents_okay"]) {
				$new_g_vents_okay = "<span style='color:#B10A0A;'>" . $g_vents_okay1 . "</span>";
				$getedited_val .= "[Vents Okay?] changed from \' " . $g_olddata["g_vents_okay"] . "\' to \'" . $g_vents_okay . "\'<br>";
			} else {
				$new_g_vents_okay = $g_vents_okay;
			}
			//
			//new email format gaylord
			if ($_REQUEST["g_item_length"] == "") {
				$g_item_length_n = "0";
			} else {
				$g_item_length_n = $_REQUEST["g_item_length"];
			}
			//
			if ($_REQUEST['g_item_width'] == "") {
				$g_item_width_n = "0";
			} else {
				$g_item_width_n = $_REQUEST['g_item_width'];
			}
			//
			if ($_REQUEST['g_item_height'] == "") {
				$g_item_height_n = "0";
			} else {
				$g_item_height_n = $_REQUEST['g_item_height'];
			}
			//
			if ($_REQUEST['g_quantity_request'] == "Select One") {
				$g_quantity_request_n = "None";
			} else {
				if ($_REQUEST['g_quantity_request'] == "Other") {
					$g_quantity_request_n = $_REQUEST['g_quantity_request'] . "-" . $_REQUEST['g_other_quantity'];
				} else {
					$g_quantity_request_n = $_REQUEST['g_quantity_request'];
				}
			}
			//
			if ($_REQUEST['g_frequency_order'] == "Select One") {
				$g_frequency_order_n = "None";
			} else {
				$g_frequency_order_n = $_REQUEST['g_frequency_order'];
			}
			//
			if ($_REQUEST['g_what_used_for'] == "" || $_REQUEST['g_what_used_for'] == " ") {
				$g_what_used_for_n = "No";
			} else {
				$g_what_used_for_n = $_REQUEST['g_what_used_for'];
			}
			//
			if ($_REQUEST['need_pallets'] == "Yes") {
				$need_pallets_n = "Yes";
			} else {
				$need_pallets_n = "No";
			}
			//
			if ($_REQUEST['sales_desired_price_g'] == "") {
				$sales_desired_price_g_n = "0.00";
			} else {
				$sales_desired_price_g_n = number_format($_REQUEST['sales_desired_price_g'], 2);
			}
			//
			if ($_REQUEST['g_item_note'] == "") {
				$g_item_note_n = "None";
			} else {
				$g_item_note_n = $_REQUEST['g_item_note'];
			}
			//
			if ($_REQUEST['g_item_min_height'] == "" || $_REQUEST['g_item_min_height'] == "0") {
				$g_item_min_height_n = "0";
			} else {
				$g_item_min_height_n = $_REQUEST['g_item_min_height'];
			}
			//
			if ($_REQUEST['g_item_max_height'] == "") {
				$g_item_max_height_n = "0";
			} else {
				$g_item_max_height_n = $_REQUEST['g_item_max_height'];
			}
			//
			$g_shape = "";
			if ($_REQUEST['g_shape_rectangular'] != "Yes") {
				$g_shape_rectangular = "No";
			} else {
				$g_shape_rectangular = $_REQUEST['g_shape_rectangular'];
				$g_shape = "Rectangular";
			}
			//
			if ($_REQUEST['g_shape_octagonal'] != "Yes") {
				$g_shape_octagonal = "No";
			} else {
				$g_shape_octagonal = $_REQUEST['g_shape_octagonal'];

				if ($g_shape != "") {
					$g_shape .= ", Octagonal";
				} else {
					$g_shape = "Octagonal";
				}
			}
			//
			$w = "";
			$g_wall = "";
			$t = "";
			$g_top = "";
			$g_bottom = "";
			$b = "";
			if ($_REQUEST['g_wall_1'] != "Yes") {
				$g_wall_1 = "No";
			} else {
				$g_wall = "1ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_2'] != "Yes") {
				$g_wall_2 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "2ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_3'] != "Yes") {
				$g_wall_3 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "3ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_4'] != "Yes") {
				$g_wall_4 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "4ply";
				$w = "y";
			}
			//

			if ($_REQUEST['g_wall_5'] != "Yes") {
				$g_wall_5 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "5ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_6'] != "Yes") {
				$g_wall_6 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "6ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_7'] != "Yes") {
				$g_wall_7 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "7ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_8'] != "Yes") {
				$g_wall_8 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "8ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_9'] != "Yes") {
				$g_wall_9 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "9ply";
				$w = "y";
			}
			//
			if ($_REQUEST['g_wall_10'] != "Yes") {
				$g_wall_10 = "No";
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_wall .= $comma . "10ply";
			}
			//
			if ($_REQUEST['g_no_top'] != "Yes") {
				$g_no_top = "No";
			} else {
				$g_top = "No Top";
				$t = "y";
			}
			//
			if ($_REQUEST['g_lid_top'] != "Yes") {
				$g_lid_top = "No";
			} else {
				if ($t == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_top .= $comma . "Lid Top";
				$t = "y";
			}
			//
			if ($_REQUEST['g_partial_flap_top'] != "Yes") {
				$g_partial_flap_top = "No";
			} else {
				if ($t == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_top .= $comma . "Partial Flap Top";
				$t = "y";
			}
			//
			if ($_REQUEST['g_full_flap_top'] != "Yes") {
				$g_full_flap_top = "No";
			} else {
				if ($t == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_top .= $comma . "Full Flap Top";
				$t = "y";
			}
			//
			if ($_REQUEST['g_no_bottom_config'] != "Yes") {
				$g_no_bottom_config = "No";
			} else {
				$g_bottom .= "No Bottom";
				$b = "y";
			}
			if ($_REQUEST['g_tray_bottom'] != "Yes") {
				$g_tray_bottom = "No";
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_bottom .= $comma . "Tray Bottom";
				$b = "y";
			}
			if ($_REQUEST['g_partial_flap_w'] != "Yes") {
				$g_partial_flap_w = "No";
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_bottom .= $comma . "Partial Flap w/ Slipsheet";
				$b = "y";
			}
			if ($_REQUEST['g_full_flap_bottom'] != "Yes") {
				$g_full_flap_bottom = "No";
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_bottom .= $comma . "Full Flap Bottom";
				$b = "y";
			}
			//
			if ($_REQUEST['g_partial_flap_wo'] != "Yes") {
				$g_partial_flap_wo = "No";
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$g_bottom .= $comma . "Partial Flap w/o SlipSheet";
			}
			//
			if ($_REQUEST['g_vents_okay'] != "Yes") {
				$g_vents_okay_n = "No";
			} else {
				$g_vents_okay_n = $_REQUEST['g_vents_okay'];
			}
			//
			//
			$rq_item = "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
			$rq_item .= "<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $g_item_length_n . " x " . $g_item_width_n . " x " . $g_item_height_n . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $g_quantity_request_n . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $g_frequency_order_n . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $g_what_used_for_n . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $need_pallets_n . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sales_desired_price_g_n . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $g_item_note_n . "</li>
						</ul>";
			//-----Tolerances---------
			$rq_item .= "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Tolerances</u></span>
						<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
						<li style='padding-bottom:4px;'><strong>Height Flexibility (in): </strong> " . $g_item_min_height_n . " - " . $g_item_max_height_n . "</li>
						<li style='padding-bottom:4px;'><strong>Shape: </strong>
						" . $g_shape . "
						</li>
						<li style='padding-bottom:4px;'><strong># of Walls: </strong>
						  " . $g_wall . "
						</li>
						<li style='padding-bottom:4px;'><strong>Top Config: </strong>
		  					" . $g_top . "
		  				</li>

            			<li style='padding-bottom:4px;'><strong>Bottom Config: </strong>
						  " . $g_bottom . "
						</li>
						
						<li style='padding-bottom:4px;'><strong>Vents Okay?: </strong>
						  " . $g_vents_okay_n . "
						</li>
						
						</ul>";
			//--------------------------------------------------------------------------------------

			$query2 = db_query("UPDATE quote_gaylord SET g_item_length = '" . $g_item_length . "', g_item_width= '" . $g_item_width . "', g_item_height= '" . $g_item_height . "', g_item_min_height= '" . $g_item_min_height . "', g_item_max_height= '" . $g_item_max_height . "', g_shape_rectangular= '" . $g_shape_rectangular . "', g_shape_octagonal= '" . $g_shape_octagonal . "', g_wall_1= '" . $g_wall_1 . "', g_wall_2= '" . $g_wall_2 . "', g_wall_3= '" . $g_wall_3 . "', g_wall_4= '" . $g_wall_4 . "', g_wall_5= '" . $g_wall_5 . "', g_wall_6= '" . $g_wall_6 . "', g_wall_7= '" . $g_wall_7 . "', g_wall_8= '" . $g_wall_8 . "', g_wall_9= '" . $g_wall_9 . "', g_wall_10= '" . $g_wall_10 . "', g_no_top= '" . $g_no_top . "', g_lid_top= '" . $g_lid_top . "', g_partial_flap_top= '" . $g_partial_flap_top . "', g_full_flap_top= '" . $g_full_flap_top . "', g_no_bottom_config= '" . $g_no_bottom_config . "', g_partial_flap_w= '" . $g_partial_flap_w . "', g_tray_bottom= '" . $g_tray_bottom . "', g_full_flap_bottom= '" . $g_full_flap_bottom . "', g_partial_flap_wo= '" . $g_partial_flap_wo . "', g_vents_okay= '" . $g_vents_okay . "', g_quantity_request= '" . $g_quantity_request . "', g_other_quantity= '" . $g_other_quantity . "', g_frequency_order= '" . $g_frequency_order . "', g_what_used_for= '" . $g_what_used_for . "', date_needed_by= '" . $date_needed_by . "', need_pallets= '" . $need_pallets . "', g_item_note= '" . $g_item_note . "', g_quotereq_sales_flag= '" . $quoterequest_saleslead_flag . "', sales_desired_price_g= '" . $sales_desired_price_g . "' WHERE quote_gaylord.id = '" . $tableid . "'");

			send_demand_email($rq_item, $getedited_val, $company_id, $quote_item_name, $tableid, 'edit_q');
			//
			$record_save1 = "done";
			$updateflagg = "Yes";
		}
		if ($_REQUEST["updatequotedata"] == 2) {
			$tableid = $_REQUEST["tableid"];

			$record_save1 = "done";
			$updateflagg = "Yes";
		}
	}
	//update shipping data
	if (isset($_REQUEST["sbupdatequotedata"])) {
		if ($_REQUEST["sbupdatequotedata"] == 1) {
			//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit();
			$stableid = $_REQUEST["stableid"];
			$sb_item_length = $_REQUEST["sb_item_length"];
			$sb_item_width = $_REQUEST["sb_item_width"];
			$sb_item_height = $_REQUEST["sb_item_height"];
			$sb_item_min_length = $_REQUEST["sb_item_min_length"];
			$sb_item_max_length = $_REQUEST["sb_item_max_length"];
			$sb_item_min_width = $_REQUEST["sb_item_min_width"];
			$sb_item_max_width = $_REQUEST["sb_item_max_width"];
			$sb_item_min_height = $_REQUEST["sb_item_min_height"];
			$sb_item_max_height = $_REQUEST["sb_item_max_height"];
			$sb_cubic_footage_min = $_REQUEST["sb_cubic_footage_min"];
			$sb_cubic_footage_max = $_REQUEST["sb_cubic_footage_max"];
			$sb_quantity_requested = $_REQUEST["sb_quantity_requested"];
			$sb_sales_desired_price = str_replace("'", "\'", ($_REQUEST["sb_sales_desired_price"]));

			$client_dash_flg = $_REQUEST["client_dash_flg"];
			if ($sb_quantity_requested == "Other") {
				$sb_other_quantity = $_REQUEST["sb_other_quantity"];
			} else {
				$sb_other_quantity = 0;
			}
			$sb_frequency_order = $_REQUEST["sb_frequency_order"];
			$sb_what_used_for = str_replace("'", "\'", $_REQUEST["sb_what_used_for"]);
			$sb_date_needed_by = "";
			if ($_REQUEST["sb_date_needed_by"] != "") {
				$sb_date_needed_by = date("Y-m-d", strtotime($_REQUEST["sb_date_needed_by"]));
			}

			$sb_notes = str_replace("'", "\'", ($_REQUEST["sb_notes"]));

			if (isset($_REQUEST["sb_need_pallets"])) {
				$sb_need_pallets = $_REQUEST["sb_need_pallets"];
			} else {
				$sb_need_pallets = "No";
			}
			if (isset($_REQUEST["sb_wall_1"])) {
				$sb_wall_1 = $_REQUEST["sb_wall_1"];
			} else {
				$sb_wall_1 = "No";
			}
			if (isset($_REQUEST["sb_wall_2"])) {
				$sb_wall_2 = $_REQUEST["sb_wall_2"];
			} else {
				$sb_wall_2 = "No";
			}
			if (isset($_REQUEST["sb_full_flap_top"])) {
				$sb_full_flap_top = $_REQUEST["sb_full_flap_top"];
			} else {
				$sb_full_flap_top = "No";
			}
			if (isset($_REQUEST["sb_partial_flap_top"])) {
				$sb_partial_flap_top = $_REQUEST["sb_partial_flap_top"];
			} else {
				$sb_partial_flap_top = "No";
			}
			if (isset($_REQUEST["sb_no_top"])) {
				$sb_no_top = $_REQUEST["sb_no_top"];
			} else {
				$sb_no_top = "No";
			}
			if (isset($_REQUEST["sb_no_bottom"])) {
				$sb_no_bottom = $_REQUEST["sb_no_bottom"];
			} else {
				$sb_no_bottom = "No";
			}
			if (isset($_REQUEST["sb_full_flap_bottom"])) {
				$sb_full_flap_bottom = $_REQUEST["sb_full_flap_bottom"];
			} else {
				$sb_full_flap_bottom = "No";
			}
			if (isset($_REQUEST["sb_partial_flap_bottom"])) {
				$sb_partial_flap_bottom = $_REQUEST["sb_partial_flap_bottom"];
			} else {
				$sb_partial_flap_bottom = "No";
			}
			if (isset($_REQUEST["sb_vents_okay"])) {
				$sb_vents_okay = $_REQUEST["sb_vents_okay"];
			} else {
				$sb_vents_okay = "No";
			}
			if (isset($_REQUEST["sb_quotereq_sales_flag"])) {
				$sb_quotereq_sales_flag = $_REQUEST["sb_quotereq_sales_flag"];
			} else {
				$sb_quotereq_sales_flag = "No";
			}
			//
			//--------------------------------------------------------------------
			//Check edited value
			$getrecquery_s = "SELECT * FROM quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id WHERE quote_shipping_boxes.id = '" . $stableid . "'";

			$s_oldres = db_query($getrecquery_s);
			$company_id = $_REQUEST["company_id"];
			$sb_olddata = array_shift($s_oldres);
			//
			$quoteitem = $sb_olddata["quote_item"];
			//Get Item Name
			$getquotequery = db_query("SELECT * FROM quote_request_item WHERE quote_rq_id=" . $quoteitem);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			if ($sb_item_length != $sb_olddata["sb_item_length"]) {
				$new_sb_item_length = "<span style='color:#B10A0A;'>" . $sb_item_length . "</span>";
			} else {
				$new_sb_item_length = $sb_item_length;
			}
			if ($sb_item_width != $sb_olddata["sb_item_width"]) {
				$new_sb_item_width = "<span style='color:#B10A0A;'>" . $sb_item_width . "</span>";
			} else {
				$new_sb_item_width = $sb_item_width;
			}
			if ($sb_item_height != $sb_olddata["sb_item_height"]) {
				$new_sb_item_height = "<span style='color:#B10A0A;'>" . $sb_item_height . "</span>";
			} else {
				$new_sb_item_height = $sb_item_height;
			}
			if (($sb_item_length != $sb_olddata["sb_item_length"]) || ($sb_item_width != $sb_olddata["sb_item_width"]) || ($sb_item_height != $sb_olddata["sb_item_height"])) {
				$getedited_val = "[Ideal Size (in)] changed from \' " . $sb_olddata["sb_item_length"] . "x" . $sb_olddata["sb_item_width"] . "x" . $sb_olddata["sb_item_height"] . "\' to \'" . $sb_item_length . "x" . $sb_item_width . "x" . $sb_item_height . "\'<br>";
			}
			if ($sb_quantity_requested != "Other") {
				if ($sb_quantity_requested != $sb_olddata["sb_quantity_requested"]) {
					$new_sb_quantity_requested = "<span style='color:#B10A0A;'>" . $sb_quantity_requested . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $sb_olddata["sb_quantity_requested"] . "\' to \'" . $sb_quantity_requested . "\'<br>";
				} else {
					$new_sb_quantity_requested = $sb_quantity_requested;
				}
			} else {
				if ($sb_olddata['sb_other_quantity'] != $sb_other_quantity) {
					$new_sb_quantity_requested = "<span style='color:#B10A0A;'>" . $sb_quantity_requested . "-" . $sb_other_quantity . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $sb_olddata["sb_quantity_requested"] . "-" . $sb_olddata['sb_other_quantity'] . "\' to \'" . $sb_quantity_requested . "-" . $sb_other_quantity . "\'<br>";
				} else {
					$new_sb_quantity_requested = $sb_quantity_requested . "-" . $sb_other_quantity;
				}
			}
			//
			if ($sb_frequency_order != $sb_olddata["sb_frequency_order"]) {
				$new_sb_frequency_order = "<span style='color:#B10A0A;'>" . $sb_frequency_order . "</span>";
				$getedited_val .= "[Frequency of Order] changed from \' " . $sb_olddata["sb_frequency_order"] . "\' to \'" . $sb_frequency_order . "\'<br>";
			} else {
				$new_sb_frequency_order = $sb_frequency_order;
			}
			//
			if ($sb_what_used_for != $sb_olddata["sb_what_used_for"]) {
				$new_sb_what_used_for = "<span style='color:#B10A0A;'>" . $sb_what_used_for . "</span>";
				$getedited_val .= "[What Used For?] changed from \' " . $sb_olddata["sb_what_used_for"] . "\' to \'" . $sb_what_used_for . "\'<br>";
			} else {
				$new_sb_what_used_for = $sb_what_used_for;
			}
			//
			$sb_need_pallets1 = "";
			if ($sb_need_pallets == "") {
				$sb_need_pallets1 = "No";
			}
			if ($sb_need_pallets != $sb_olddata["sb_need_pallets"]) {
				$new_sb_need_pallets = "<span style='color:#B10A0A;'>" . $sb_need_pallets1 . "</span>";
				$getedited_val .= "[Also Need Pallets?] changed from \' " . $sb_olddata["sb_need_pallets"] . "\' to \'" . $sb_need_pallets1 . "\'<br>";
			} else {
				$new_sb_need_pallets = $sb_need_pallets;
			}
			//
			if ($sb_sales_desired_price != $sb_olddata["sb_sales_desired_price"]) {
				$new_sb_sales_desired_price = "<span style='color:#B10A0A;'>" . $sb_sales_desired_price . "</span>";
				$getedited_val .= "[Desired Price] changed from \' " . $sb_olddata["sb_sales_desired_price"] . "\' to \'" . $sb_sales_desired_price . "\'<br>";
			} else {
				$new_sb_sales_desired_price = $sb_sales_desired_price;
			}
			if ($sb_notes != $sb_olddata["sb_notes"]) {
				$new_sb_notes = "<span style='color:#B10A0A;'>" . $sb_notes . "</span>";
				$getedited_val .= "[Note] changed from \' " . $sb_olddata["sb_notes"] . "\' to \'" . $sb_notes . "\'<br>";
			} else {
				$new_sb_notes = $sb_notes;
			}
			//
			if ($sb_item_min_length != $sb_olddata["sb_item_min_length"]) {
				$new_sb_item_min_length = "<span style='color:#B10A0A;'>" . $sb_item_min_length . "</span>";
			} else {
				$new_sb_item_min_length = $sb_item_min_length;
			}
			if ($sb_item_max_length != $sb_olddata["sb_item_max_length"]) {
				$new_sb_item_max_length = "<span style='color:#B10A0A;'>" . $sb_item_max_length . "</span>";
			} else {
				$new_sb_item_max_length = $sb_item_max_length;
			}

			if (($sb_item_min_length != $sb_olddata["sb_item_min_length"]) || ($sb_item_max_length != $sb_olddata["sb_item_max_length"])) {
				$getedited_val .= "[Length Flexibility ] changed from \' " . $sb_olddata["sb_item_min_length"] . "-" . $sb_olddata["sb_item_max_length"] . "\' to \'" . $sb_item_min_length . "-" . $sb_item_max_length . "\'<br>";
			}
			//
			if ($sb_item_min_width != $sb_olddata["sb_item_min_width"]) {
				$new_sb_item_min_width = "<span style='color:#B10A0A;'>" . $sb_item_min_width . "</span>";
			} else {
				$new_sb_item_min_width = $sb_item_min_width;
			}
			if ($sb_item_max_width != $sb_olddata["sb_item_max_width"]) {
				$new_sb_item_max_width = "<span style='color:#B10A0A;'>" . $sb_item_max_width . "</span>";
			} else {
				$new_sb_item_max_width = $sb_item_max_width;
			}

			if (($sb_item_min_width != $sb_olddata["sb_item_min_width"]) || ($sb_item_max_width != $sb_olddata["sb_item_max_width"])) {
				$getedited_val .= "[Width Flexibility ] changed from \' " . $sb_olddata["sb_item_min_width"] . "-" . $sb_olddata["sb_item_max_width"] . "\' to \'" . $sb_item_min_width . "-" . $sb_item_max_width . "\'<br>";
			}
			//
			if ($sb_item_min_height != $sb_olddata["sb_item_min_height"]) {
				$new_sb_item_min_height = "<span style='color:#B10A0A;'>" . $sb_item_min_height . "</span>";
			} else {
				$new_sb_item_min_height = $sb_item_min_height;
			}
			if ($sb_item_max_height != $sb_olddata["sb_item_max_height"]) {
				$new_sb_item_max_height = "<span style='color:#B10A0A;'>" . $sb_item_max_height . "</span>";
			} else {
				$new_sb_item_max_height = $sb_item_max_height;
			}

			if (($sb_item_min_height != $sb_olddata["sb_item_min_height"]) || ($sb_item_max_height != $sb_olddata["sb_item_max_height"])) {
				$getedited_val .= "[Height Flexibility ] changed from \' " . $sb_olddata["sb_item_min_height"] . "-" . $sb_olddata["sb_item_max_height"] . "\' to \'" . $sb_item_min_height . "-" . $sb_item_max_height . "\'<br>";
			}

			//
			if ($sb_cubic_footage_min != $sb_olddata["sb_cubic_footage_min"]) {
				$new_sb_cubic_footage_min = "<span style='color:#B10A0A;'>" . $sb_cubic_footage_min . "</span>";
			} else {
				$new_sb_cubic_footage_min = $sb_cubic_footage_min;
			}
			if ($sb_cubic_footage_max != $sb_olddata["sb_cubic_footage_max"]) {
				$new_sb_cubic_footage_max = "<span style='color:#B10A0A;'>" . $sb_cubic_footage_max . "</span>";
			} else {
				$new_sb_cubic_footage_max = $sb_cubic_footage_max;
			}

			if (($sb_cubic_footage_min != $sb_olddata["new_sb_cubic_footage_min"]) || ($sb_cubic_footage_max != $sb_olddata["sb_cubic_footage_max"])) {
				$getedited_val .= "[Cubic Footage] changed from \' " . $sb_olddata["new_sb_cubic_footage_min"] . "-" . $sb_olddata["sb_cubic_footage_max"] . "\' to \'" . $new_sb_cubic_footage_min . "-" . $sb_cubic_footage_max . "\'<br>";
			}
			//
			if ($sb_wall_1 != $sb_olddata["sb_wall_1"]) {
				$new_sb_wall_1 = "<span style='color:#B10A0A;'>" . $sb_wall_1 . "</span>";
				$getedited_val .= "[# of Walls - 1ply] changed from \' " . $sb_olddata["sb_wall_1"] . "\' to \'" . $sb_wall_1 . "\'<br>";
			} else {
				$new_sb_wall_1 = $sb_wall_1;
			}
			if ($sb_wall_2 != $sb_olddata["sb_wall_2"]) {
				$new_sb_wall_2 = "<span style='color:#B10A0A;'>" . $sb_wall_2 . "</span>";
				$getedited_val .= "[# of Walls - 2ply] changed from \' " . $sb_olddata["sb_wall_2"] . "\' to \'" . $sb_wall_2 . "\'<br>";
			} else {
				$new_sb_wall_2 = $sb_wall_2;
			}
			//chnaged array variable $g_olddata to $sb_olddata as , it was undefined
			/*if ($sb_no_top != $g_olddata["sb_no_top"]) {
				$new_sb_no_top = "<span style='color:#B10A0A;'>" . $sb_no_top . "</span>";
				$getedited_val .= "[Top Config - No Top] changed from \' " . $g_olddata["sb_no_top"] . "\' to \'" . $sb_no_top . "\'<br>";
			}*/
			if ($sb_no_top != $sb_olddata["sb_no_top"]) {
				$new_sb_no_top = "<span style='color:#B10A0A;'>" . $sb_no_top . "</span>";
				$getedited_val .= "[Top Config - No Top] changed from \' " . $sb_olddata["sb_no_top"] . "\' to \'" . $sb_no_top . "\'<br>";
			}else {
				$new_sb_no_top = $sb_no_top;
			}
			if ($sb_full_flap_top != $sb_olddata["sb_full_flap_top"]) {
				$new_sb_full_flap_top = "<span style='color:#B10A0A;'>" . $sb_full_flap_top . "</span>";
				$getedited_val .= "[Top Config - Full Flap Top] changed from \' " . $sb_olddata["sb_full_flap_top"] . "\' to \'" . $sb_full_flap_top . "\'<br>";
			} else {
				$new_sb_full_flap_top = $sb_full_flap_top;
			}
			if ($sb_partial_flap_top != $sb_olddata["sb_partial_flap_top"]) {
				$new_sb_partial_flap_top = "<span style='color:#B10A0A;'>" . $sb_partial_flap_top . "</span>";
				$getedited_val .= "[Top Config - Partial Flap Top] changed from \' " . $sb_olddata["sb_partial_flap_top"] . "\' to \'" . $sb_partial_flap_top . "\'<br>";
			} else {
				$new_sb_partial_flap_top = $sb_partial_flap_top;
			}
			//
			if ($sb_no_bottom != $sb_olddata["sb_no_bottom"]) {
				$new_sb_no_bottom = "<span style='color:#B10A0A;'>" . $sb_no_bottom . "</span>";
				$getedited_val .= "[Bottom Config - No Bottom] changed from \' " . $sb_olddata["sb_no_bottom"] . "\' to \'" . $sb_no_bottom . "\'<br>";
			} else {
				$new_sb_no_bottom = $sb_no_bottom;
			}

			if ($sb_full_flap_bottom != $sb_olddata["sb_full_flap_bottom"]) {
				$new_sb_full_flap_bottom = "<span style='color:#B10A0A;'>" . $sb_full_flap_bottom . "</span>";
				$getedited_val .= "[Bottom Config - Full Flap Bottom] changed from \' " . $sb_olddata["sb_full_flap_bottom"] . "\' to \'" . $sb_full_flap_bottom . "\'<br>";
			} else {
				$new_sb_full_flap_bottom = $sb_full_flap_bottom;
			}

			if ($sb_partial_flap_bottom != $sb_olddata["sb_partial_flap_bottom"]) {
				$new_sb_partial_flap_bottom = "<span style='color:#B10A0A;'>" . $sb_partial_flap_bottom . "</span>";
				$getedited_val .= "[Bottom Config - Partial Flap Bottom] changed from \' " . $sb_olddata["sb_partial_flap_bottom"] . "\' to \'" . $sb_partial_flap_bottom . "\'<br>";
			} else {
				$new_sb_partial_flap_bottom = $sb_partial_flap_bottom;
			}
			//
			$sb_vents_okay1 = "";
			if ($sb_vents_okay == "") {
				$sb_vents_okay1 = "No";
			}
			if ($sb_vents_okay != $sb_olddata["sb_vents_okay"]) {
				$new_sb_vents_okay = "<span style='color:#B10A0A;'>" . $sb_vents_okay1 . "</span>";
				$getedited_val .= "[Vents Okay?] changed from \' " . $sb_olddata["sb_vents_okay"] . "\' to \'" . $sb_vents_okay1 . "\'<br>";
			} else {
				$new_sb_vents_okay = $sb_vents_okay;
			}
			//
			//---- Send auto email edit------------------------------------------
			//
			if ($_REQUEST['sb_item_length'] == "") {
				$sb_item_length_n = "0";
			} else {
				$sb_item_length_n = $_REQUEST['sb_item_length'];
			}
			//
			if ($_REQUEST['sb_item_width'] == "") {
				$sb_item_width_n = "0";
			} else {
				$sb_item_width_n = $_REQUEST['sb_item_width'];
			}
			//
			if ($_REQUEST['sb_item_height'] == "") {
				$sb_item_height_n = "0";
			} else {
				$sb_item_height_n = $_REQUEST['sb_item_height'];
			}
			//
			if ($_REQUEST["sb_quantity_requested"] == "Select One") {
				$sb_quantity_requested_n = "None";
			} else {
				if ($_REQUEST['sb_quantity_requested'] == "Other") {
					$sb_quantity_requested_n = $_REQUEST['sb_quantity_requested'] . "- " . $_REQUEST['sb_other_quantity'];
				} else {
					$sb_quantity_requested_n = $_REQUEST['sb_quantity_requested'];
				}
			}
			//
			if ($_REQUEST['sb_frequency_order'] == "Select One") {
				$sb_frequency_order_n = "None";
			} else {
				$sb_frequency_order_n = $_REQUEST['sb_frequency_order'];
			}
			//
			if ($_REQUEST['sb_what_used_for'] == "" || $_REQUEST['sb_what_used_for'] == " ") {
				$sb_what_used_for_n = "No";
			} else {
				$sb_what_used_for_n = $_REQUEST['sb_what_used_for'];
			}
			//
			if ($_REQUEST['sb_need_pallets'] == "Yes") {
				$sb_need_pallets_n = "Yes";
			} else {
				$sb_need_pallets_n = "No";
			}
			//
			if ($_REQUEST['sb_sales_desired_price'] == "") {
				$sb_sales_desired_price_n = "0.00";
			} else {
				$sb_sales_desired_price_n = number_format($_REQUEST['sb_sales_desired_price'], 2);
			}
			//
			if ($_REQUEST['sb_notes'] == "") {
				$sb_notes_n = "None";
			} else {
				$sb_notes_n = $_REQUEST['sb_notes'];
			}
			//
			if ($_REQUEST["sb_item_min_length"] == "") {
				$sb_item_min_length_n = "0";
			} else {
				$sb_item_min_length_n = $_REQUEST['sb_item_min_length'];
			}
			//
			if ($_REQUEST["sb_item_max_length"] == "") {
				$sb_item_max_length_n = "0";
			} else {
				$sb_item_max_length_n = $_REQUEST['sb_item_max_length'];
			}
			//
			if ($_REQUEST["sb_item_min_width"] == "") {
				$sb_item_min_width_n = "0";
			} else {
				$sb_item_min_width_n = $_REQUEST['sb_item_min_width'];
			}
			//
			if ($_REQUEST["sb_item_max_width"] == "") {
				$sb_item_max_width_n = "0";
			} else {
				$sb_item_max_width_n = $_REQUEST['sb_item_max_width'];
			}
			//
			if ($_REQUEST["sb_item_min_height"] == "") {
				$sb_item_min_height_n = "0";
			} else {
				$sb_item_min_height_n = $_REQUEST['sb_item_min_height'];
			}
			//
			if ($_REQUEST["sb_item_max_height"] == "") {
				$sb_item_max_height_n = "0";
			} else {
				$sb_item_max_height_n = $_REQUEST['sb_item_max_height'];
			}
			//
			if ($_REQUEST["sb_cubic_footage_min"] == "") {
				$sb_cubic_footage_min_n = "0";
			} else {
				$sb_cubic_footage_min_n = $_REQUEST['sb_cubic_footage_min'];
			}
			//
			if ($_REQUEST["sb_cubic_footage_max"] == "") {
				$sb_cubic_footage_max_n = "0";
			} else {
				$sb_cubic_footage_max_n = $_REQUEST['sb_cubic_footage_max'];
			}
			$sb_top = "";
			$sb_wall = "";
			$t = "";
			$w = "";
			$comma = "";
			//
			if ($_REQUEST['sb_wall_1'] != "Yes") {
			} else {

				$sb_wall .= $comma . "1ply";
				$w = "y";
			}
			//
			if ($_REQUEST['sb_wall_2'] != "Yes") {
			} else {
				if ($w == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$sb_wall .= $comma . "2ply";
			}
			//
			if ($_REQUEST['sb_no_top'] != "Yes") {
			} else {
				$sb_top = "No Top";
				$t = "y";
			}
			//
			if ($_REQUEST['sb_full_flap_top'] != "Yes") {
			} else {
				if ($t == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$sb_top .= $comma . "Full Flap Top";
			}
			if ($_REQUEST['sb_partial_flap_top'] != "Yes") {
			} else {
				if ($t == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$sb_top .= $comma . "Partial Flap Top";
			}
			//
			$sb_bottom = "";
			$sb_top = "";
			$sb_wall = "";
			$b = "";
			$t = "";
			$comma = "";
			if ($_REQUEST['sb_no_bottom'] != "Yes") {
			} else {
				$sb_no_bottom = $_REQUEST['sb_no_bottom'];
				$sb_bottom .= "No Bottom";
				$b = "y";
			}
			//
			if ($_REQUEST['sb_full_flap_bottom'] != "Yes") {
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$sb_bottom .= $comma . "Full Flap Bottom";
			}
			if ($_REQUEST['sb_partial_flap_bottom'] != "Yes") {
			} else {
				if ($b == "y") {
					$comma = ", ";
				} else {
					$comma = "";
				}
				$sb_bottom .= $comma . "Partial Flap Bottom";
			}
			//
			$sb_vents_okay_n = "";
			if ($_REQUEST["sb_vents_okay"] != "Yes") {
			} else {
				$sb_vents_okay_n = $_REQUEST['sb_vents_okay'];
			}
			//
			$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
			$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $sb_item_length_n . " x " . $sb_item_width_n . " x " . $sb_item_height_n . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $sb_quantity_requested_n . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $sb_frequency_order_n . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $sb_what_used_for_n . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $sb_need_pallets_n . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sb_sales_desired_price_n . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $sb_notes_n . "</li>
						</ul>";
			//-----Tolerances---------
			$rq_item .= "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Tolerances</u></span>
						<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
						<li style='padding-bottom:4px;'><strong>Size Flexibility (in): </strong> 
						Length: " . $sb_item_min_length_n . " - " . $sb_item_max_length_n . ",
						Width: " . $sb_item_min_width_n . " - " . $sb_item_max_width_n . ",
						Height: " . $sb_item_min_height_n . " - " . $sb_item_max_height_n . "
						</li>
						<li style='padding-bottom:4px;'><strong>Cubic Footage: </strong>
						" . $sb_cubic_footage_min_n . " - " . $sb_cubic_footage_max_n . "
						</li>
						<li style='padding-bottom:4px;'><strong># of Walls: </strong>
						  " . $sb_wall . "
						</li>
						<li style='padding-bottom:4px;'><strong>Top Config: </strong>
		  					" . $sb_top . "
		  				</li>

            			<li style='padding-bottom:4px;'><strong>Bottom Config: </strong>
						  " . $sb_bottom . "
						</li>
						
						<li style='padding-bottom:4px;'><strong>Vents Okay?: </strong>
						  " . $sb_vents_okay_n . "
						</li>
						</ul>";

			//
			//
			//$quote_id = tep_db_insert_id();	
			$query2 = db_query("UPDATE `quote_shipping_boxes` SET `sb_item_length` = '" . $sb_item_length . "', `sb_item_width`= '" . $sb_item_width . "', `sb_item_height`= '" . $sb_item_height . "', `sb_item_min_length`= '" . $sb_item_min_length . "', `sb_item_max_length`= '" . $sb_item_max_length . "', `sb_item_min_width`= '" . $sb_item_min_width . "', `sb_item_max_width`= '" . $sb_item_max_width . "', `sb_item_min_height`= '" . $sb_item_min_height . "', `sb_item_max_height`= '" . $sb_item_max_height . "', `sb_wall_1`= '" . $sb_wall_1 . "', `sb_wall_2`= '" . $sb_wall_2 . "', `sb_no_top`= '" . $sb_no_top . "', `sb_full_flap_top`= '" . $sb_full_flap_top . "', `sb_no_bottom`= '" . $sb_no_bottom . "', `sb_full_flap_bottom`= '" . $sb_full_flap_bottom . "', `sb_cubic_footage_min`= '" . $sb_cubic_footage_min . "', `sb_cubic_footage_max`= '" . $sb_cubic_footage_max . "', `sb_vents_okay`= '" . $sb_vents_okay . "', `sb_need_pallets`= '" . $sb_need_pallets . "', `sb_date_needed_by`= '" . $sb_date_needed_by . "', `sb_quantity_requested`= '" . $sb_quantity_requested . "', `sb_other_quantity`= '" . $sb_other_quantity . "', `sb_frequency_order`= '" . $sb_frequency_order . "', `sb_what_used_for`= '" . $sb_what_used_for . "', `sb_notes`= '" . $sb_notes . "', `sb_quotereq_sales_flag`= '" . $sb_quotereq_sales_flag . "', `sb_sales_desired_price`= '" . $sb_sales_desired_price . "', `sb_partial_flap_top`= '" . $sb_partial_flap_top . "', `sb_partial_flap_bottom`= '" . $sb_partial_flap_bottom . "'  WHERE `quote_shipping_boxes`.`id` = '" . $stableid . "'");

			//
			$sb_display_data = "Done";
			$updateflagsb = "Yes";
			//
			//---- Send auto email------------------------------------------
			send_demand_email($rq_item, $getedited_val, $company_id, $quote_item_name, $stableid, 'edit_q');/**/

			//
		}
		if ($_REQUEST["sbupdatequotedata"] == 2) {
			$stableid = $_REQUEST["stableid"];

			$sb_display_data = "Done";
			$updateflagsb = "Yes";
		}
	}
	//
	//
	if (isset($_REQUEST["supupdatequotedata"])) {
		if ($_REQUEST["supupdatequotedata"] == 1) {
			$suptableid = $_REQUEST["suptableid"];

			$sup_item_length = $_REQUEST["sup_item_length"];
			$sup_item_width = $_REQUEST["sup_item_width"];
			$sup_item_height = $_REQUEST["sup_item_height"];
			$client_dash_flg = $_REQUEST["client_dash_flg"];

			$sup_quantity_requested = $_REQUEST["sup_quantity_requested"];
			$sup_other_quantity = $_REQUEST["sup_other_quantity"];
			$sup_frequency_order = $_REQUEST["sup_frequency_order"];
			$sup_date_needed_by = "";
			$sup_what_used_for = str_replace("'", "\'", $_REQUEST["sup_what_used_for"]);
			if ($_REQUEST["sup_date_needed_by"] != "") {
				$sup_date_needed_by = date("Y-m-d", strtotime($_REQUEST["sup_date_needed_by"]));
			}
			$sup_notes = str_replace("'", "\'", ($_REQUEST["sup_notes"]));
			//
			if (isset($_REQUEST["sup_need_pallets"])) {
				$sup_need_pallets = $_REQUEST["sup_need_pallets"];
			} else {
				$sup_need_pallets = "No";
			}
			if (isset($_REQUEST["sup_quotereq_sales_flag"])) {
				$sup_quotereq_sales_flag = $_REQUEST["sup_quotereq_sales_flag"];
			} else {
				$sup_quotereq_sales_flag = "No";
			}
			//
			$sup_sales_desired_price = str_replace("'", "\'", ($_REQUEST["sup_sales_desired_price"]));
			//
			//--------------------------------------------------------------------
			//Check edited value
			$getrecquery_sup = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_supersacks.id = '" . $suptableid . "'";

			$sup_oldres = db_query($getrecquery_sup);
			$company_id = $_REQUEST["company_id"];
			$sup_olddata = array_shift($sup_oldres);
			//
			$quoteitem = $sup_olddata["quote_item"];
			//Get Item Name
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quoteitem);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			if ($sup_item_length != $sup_olddata["sup_item_length"]) {
				$new_sup_item_length = "<span style='color:#B10A0A;'>" . $sup_item_length . "</span>";
			} else {
				$new_sup_item_length = $sup_item_length;
			}
			if ($sup_item_width != $sup_olddata["sup_item_width"]) {
				$new_sup_item_width = "<span style='color:#B10A0A;'>" . $sup_item_width . "</span>";
			} else {
				$new_sup_item_width = $sup_item_width;
			}
			if ($sup_item_height != $sup_olddata["sup_item_height"]) {
				$new_sup_item_height = "<span style='color:#B10A0A;'>" . $sup_item_height . "</span>";
			} else {
				$new_sup_item_height = $sup_item_height;
			}
			if (($sup_item_length != $sup_olddata["sup_item_length"]) || ($sup_item_width != $sup_olddata["sup_item_width"]) || ($sup_item_height != $sup_olddata["sup_item_height"])) {
				$getedited_val = "[Ideal Size (in)] changed from \' " . $sup_olddata["sup_item_length"] . "x" . $sup_olddata["sup_item_width"] . "x" . $sup_olddata["sup_item_height"] . "\' to \'" . $sup_item_length . "x" . $sup_item_width . "x" . $sup_item_height . "\'<br>";
			}
			if ($sup_quantity_requested != "Other") {
				if ($sup_quantity_requested != $sup_olddata["sup_quantity_requested"]) {
					$new_sup_quantity_requested = "<span style='color:#B10A0A;'>" . $sup_quantity_requested . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $sup_olddata["sup_quantity_requested"] . "\' to \'" . $sup_quantity_requested . "\'<br>";
				} else {
					$new_sup_quantity_requested = $sup_quantity_requested;
				}
			} else {
				if ($sup_olddata['sup_other_quantity'] != $sup_other_quantity) {
					$new_sup_quantity_requested = "<span style='color:#B10A0A;'>" . $sup_quantity_requested . "-" . $sup_other_quantity . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $sup_olddata["sup_quantity_requested"] . "-" . $sup_olddata['sup_other_quantity'] . "\' to \'" . $sup_quantity_requested . "-" . $sup_other_quantity . "\'<br>";
				} else {
					$new_sup_quantity_requested = $sup_quantity_requested . "-" . $sup_other_quantity;
				}
			}
			//
			if ($sup_frequency_order != $sup_olddata["sup_frequency_order"]) {
				$new_sup_frequency_order = "<span style='color:#B10A0A;'>" . $sup_frequency_order . "</span>";
				$getedited_val .= "[Frequency of Order] changed from \' " . $sup_olddata["sup_frequency_order"] . "\' to \'" . $sup_frequency_order . "\'<br>";
			} else {
				$new_sup_frequency_order = $sup_frequency_order;
			}
			//
			if ($sup_what_used_for != $sup_olddata["sup_what_used_for"]) {
				$new_sup_what_used_for = "<span style='color:#B10A0A;'>" . $sup_what_used_for . "</span>";
				$getedited_val .= "[What Used For?] changed from \' " . $sup_olddata["sup_what_used_for"] . "\' to \'" . $sup_what_used_for . "\'<br>";
			} else {
				$new_sup_what_used_for = $sup_what_used_for;
			}
			//
			$sup_need_pallets1 = "";
			if ($sup_need_pallets == "") {
				$sup_need_pallets1 = "No";
			}
			if ($sup_need_pallets != $sup_olddata["sup_need_pallets"]) {
				$new_sup_need_pallets = "<span style='color:#B10A0A;'>" . $sup_need_pallets1 . "</span>";
				$getedited_val .= "[Also Need Pallets?] changed from \' " . $sup_olddata["sup_need_pallets"] . "\' to \'" . $sup_need_pallets1 . "\'<br>";
			} else {
				$new_sup_need_pallets = $sup_need_pallets;
			}
			//
			if ($sup_sales_desired_price != $sup_olddata["sup_sales_desired_price"]) {
				$new_sup_sales_desired_price = "<span style='color:#B10A0A;'>" . $sup_sales_desired_price . "</span>";
				$getedited_val .= "[Desired Price] changed from \' " . $sup_olddata["sup_sales_desired_price"] . "\' to \'" . $sup_sales_desired_price . "\'<br>";
			} else {
				$new_sup_sales_desired_price = $sup_sales_desired_price;
			}
			if ($sup_notes != $sup_olddata["sup_notes"]) {
				$new_sup_notes = "<span style='color:#B10A0A;'>" . $sup_notes . "</span>";
				$getedited_val .= "[Note] changed from \' " . $sup_olddata["sup_notes"] . "\' to \'" . $sup_notes . "\'<br>";
			} else {
				$new_sup_notes = $sup_notes;
			}
			//
			//----edited Send auto email------------------------------------------
			//
			if ($_REQUEST['sup_item_length'] == "") {
				$sup_item_length_n = "0";
			} else {
				$sup_item_length_n = $_REQUEST['sup_item_length'];
			}
			//
			if ($_REQUEST['sup_item_width'] == "") {
				$sup_item_width_n = "0";
			} else {
				$sup_item_width_n = $_REQUEST['sup_item_width'];
			}
			//
			if ($_REQUEST["sup_item_height"] == "") {
				$sup_item_height_n = "0";
			} else {
				$sup_item_height_n = $_REQUEST['sup_item_height'];
			}
			//
			if ($_REQUEST["sup_quantity_requested"] == "Select One") {
				$sup_quantity_requested_n = "None";
			} else {
				if ($_REQUEST['sup_quantity_requested'] == "Other") {
					$sup_quantity_requested_n = $_REQUEST['sup_quantity_requested'] . "- " . $_REQUEST['sup_other_quantity'];
				} else {
					$sup_quantity_requested_n = $_REQUEST['sup_quantity_requested'];
				}
			}
			//
			if ($_REQUEST['sup_frequency_order'] == "Select One") {
				$sup_frequency_order_n = "None";
			} else {
				$sup_frequency_order_n = $_REQUEST['sup_frequency_order'];
			}
			//
			if ($_REQUEST['sup_what_used_for'] == "" || $_REQUEST['sup_what_used_for'] == " ") {
				$sup_what_used_for_n = "No";
			} else {
				$sup_what_used_for_n = $_REQUEST['sup_what_used_for'];
			}
			//
			if ($_REQUEST['sup_need_pallets'] == "Yes") {
				$sup_need_pallets_n = "Yes";
			} else {
				$sup_need_pallets_n = "No";
			}
			//
			if ($_REQUEST['sup_sales_desired_price'] == "") {
				$sup_sales_desired_price_n = "0.00";
			} else {
				$sup_sales_desired_price_n = number_format($_REQUEST['sup_sales_desired_price'], 2);
			}
			//
			if ($_REQUEST['sup_notes'] == "") {
				$sup_notes_n = "None";
			} else {
				$sup_notes_n = $_REQUEST['sup_notes'];
			}

			//
			//
			$rq_item = "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
			$rq_item .= "<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in): </strong>" . $sup_item_length_n . " x " . $sup_item_width_n . " x " . $sup_item_height_n . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $sup_quantity_requested_n . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $sup_frequency_order_n . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $sup_what_used_for_n . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $sup_need_pallets_n . "</li>
						
						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $sup_sales_desired_price_n . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $sup_notes_n . "</li>
						</ul>";

			//
			//
			//$quote_id = tep_db_insert_id();	
			$query2 = db_query("UPDATE `quote_supersacks` SET `sup_item_length` = '" . $sup_item_length . "', `sup_item_width` = '" . $sup_item_width . "', `sup_item_height` = '" . $sup_item_height . "', `sup_quantity_requested` = '" . $sup_quantity_requested . "', `sup_other_quantity` = '" . $sup_other_quantity . "', `sup_need_pallets` = '" . $sup_need_pallets . "', `sup_date_needed_by` = '" . $sup_date_needed_by . "', `sup_frequency_order`= '" . $sup_frequency_order . "', `sup_what_used_for`= '" . $sup_what_used_for . "', `sup_notes`= '" . $sup_notes . "', `sup_quotereq_sales_flag`= '" . $sup_quotereq_sales_flag . "', `sup_sales_desired_price`= '" . $sup_sales_desired_price . "' WHERE `quote_supersacks`.`id` = '" . $suptableid . "'");

			//
			$sup_update_data = "Done";
			$updateflagsup = "Yes";
			//
			//Send email
			send_demand_email($rq_item, $getedited_val, $company_id, $quote_item_name, $suptableid, 'edit_q');
			//
		}
		if ($_REQUEST["supupdatequotedata"] == 2) {
			$suptableid = $_REQUEST["suptableid"];

			$sup_update_data = "Done";
			$updateflagsup = "Yes";
		}
	}
	//
	if (isset($_REQUEST["palupdatequotedata"])) {
		if ($_REQUEST["palupdatequotedata"] == 1) {
			$paltableid = $_REQUEST["paltableid"];
			//
			$pal_item_length = $_REQUEST["pal_item_length"];
			$pal_item_width = $_REQUEST["pal_item_width"];

			$pal_quantity_requested = $_REQUEST["pal_quantity_requested"];
			$pal_other_quantity = $_REQUEST["pal_other_quantity"];
			$pal_frequency_order = $_REQUEST["pal_frequency_order"];
			$pal_what_used_for = str_replace("'", "\'", $_REQUEST["pal_what_used_for"]);
			$pal_date_needed_by = date("Y-m-d", strtotime($_REQUEST["pal_date_needed_by"]));
			$pal_quotereq_sales_flag = $_REQUEST["pal_quotereq_sales_flag"];
			$pal_note = str_replace("'", "\'", ($_REQUEST["pal_note"]));

			$pal_sales_desired_price = str_replace("'", "\'", ($_REQUEST["pal_sales_desired_price"]));
			//

			$pal_grade_a = $_REQUEST["pal_grade_a"];
			$pal_grade_b = $_REQUEST["pal_grade_b"];
			$pal_grade_c = $_REQUEST["pal_grade_c"];
			$pal_material_wooden = $_REQUEST["pal_material_wooden"];
			$pal_material_plastic = $_REQUEST["pal_material_plastic"];
			$pal_material_corrugate = $_REQUEST["pal_material_corrugate"];
			$pal_entry_2way = $_REQUEST["pal_entry_2way"];
			$pal_entry_4way = $_REQUEST["pal_entry_4way"];
			$pal_structure_stringer = $_REQUEST["pal_structure_stringer"];
			$pal_structure_block = $_REQUEST["pal_structure_block"];
			$pal_heat_treated = $_REQUEST["pal_heat_treated"];


			if (isset($_REQUEST["pal_quotereq_sales_flag"])) {
				$pal_quotereq_sales_flag = $_REQUEST["pal_quotereq_sales_flag"];
			} else {
				$pal_quotereq_sales_flag = "No";
			}
			//
			//--------------------------------------------------------------------
			//Check edited value
			$getrecquery_pal = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_pallets.id = '" . $paltableid . "'";

			$pal_oldres = db_query($getrecquery_pal);
			$company_id = $_REQUEST["company_id"];
			$pal_olddata = array_shift($pal_oldres);
			//
			$quoteitem = $pal_olddata["quote_item"];
			//Get Item Name
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quoteitem);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];

			//
			if ($pal_item_length != $pal_olddata["pal_item_length"]) {
				$new_pal_item_length = "<span style='color:#B10A0A;'>" . $pal_item_length . "</span>";
			} else {
				$new_pal_item_length = $pal_item_length;
			}
			if ($pal_item_width != $pal_olddata["pal_item_width"]) {
				$new_pal_item_width = "<span style='color:#B10A0A;'>" . $pal_item_width . "</span>";
			} else {
				$new_pal_item_width = $pal_item_width;
			}
			$pal_item_height = "";
			if (($pal_item_length != $pal_olddata["pal_item_length"]) || ($pal_item_width != $pal_olddata["pal_item_width"])) {
				
				$getedited_val = "[Ideal Size (in)] changed from \' " . $pal_olddata["pal_item_length"] . "x" . $pal_olddata["pal_item_width"] . "x" . $pal_olddata["pal_item_height"] . "\' to \'" . $pal_item_length . "x" . $pal_item_width . "x" . $pal_item_height . "\'<br>";
			}
			if ($pal_quantity_requested != "Other") {
				if ($pal_quantity_requested != $pal_olddata["pal_quantity_requested"]) {
					$new_pal_quantity_requested = "<span style='color:#B10A0A;'>" . $pal_quantity_requested . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $pal_olddata["pal_quantity_requested"] . "\' to \'" . $pal_quantity_requested . "\'<br>";
				} else {
					$new_pal_quantity_requested = $pal_quantity_requested;
				}
			} else {
				if ($pal_olddata['pal_other_quantity'] != $pal_other_quantity) {
					$new_pal_quantity_requested = "<span style='color:#B10A0A;'>" . $pal_quantity_requested . "-" . $pal_other_quantity . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $pal_olddata["pal_quantity_requested"] . "-" . $pal_olddata['pal_other_quantity'] . "\' to \'" . $pal_quantity_requested . "-" . $pal_other_quantity . "\'<br>";
				} else {
					$new_pal_quantity_requested = $pal_quantity_requested . "-" . $pal_other_quantity;
				}
			}
			//
			if ($pal_frequency_order != $pal_olddata["pal_frequency_order"]) {
				$new_pal_frequency_order = "<span style='color:#B10A0A;'>" . $pal_frequency_order . "</span>";
				$getedited_val .= "[Frequency of Order] changed from \' " . $pal_olddata["pal_frequency_order"] . "\' to \'" . $pal_frequency_order . "\'<br>";
			} else {
				$new_pal_frequency_order = $pal_frequency_order;
			}
			//
			if ($pal_what_used_for != $pal_olddata["pal_what_used_for"]) {
				$new_pal_what_used_for = "<span style='color:#B10A0A;'>" . $pal_what_used_for . "</span>";
				$getedited_val .= "[What Used For?] changed from \' " . $pal_olddata["pal_what_used_for"] . "\' to \'" . $pal_what_used_for . "\'<br>";
			} else {
				$new_pal_what_used_for = $pal_what_used_for;
			}
			//
			if ($pal_sales_desired_price != $pal_olddata["pal_sales_desired_price"]) {
				$new_pal_sales_desired_price = "<span style='color:#B10A0A;'>" . $pal_sales_desired_price . "</span>";
				$getedited_val .= "[Desired Price] changed from \' " . $pal_olddata["pal_sales_desired_price"] . "\' to \'" . $pal_sales_desired_price . "\'<br>";
			} else {
				$new_pal_sales_desired_price = $pal_sales_desired_price;
			}
			if ($pal_note != $pal_olddata["pal_note"]) {
				$new_pal_note = "<span style='color:#B10A0A;'>" . $pal_note . "</span>";
				$getedited_val .= "[Note] changed from \' " . $pal_olddata["pal_note"] . "\' to \'" . $pal_note . "\'<br>";
			} else {
				$new_pal_note = $pal_note;
			}

			if ($pal_grade_a != $pal_olddata["pal_grade_a"]) {
				$getedited_val .= "[Grade A] changed from \' " . $pal_olddata["pal_grade_a"] . "\' to \'" . $pal_grade_a . "\'<br>";
			}

			if ($pal_grade_b != $pal_olddata["pal_grade_b"]) {
				$getedited_val .= "[Grade B] changed from \' " . $pal_olddata["pal_grade_b"] . "\' to \'" . $pal_grade_b . "\'<br>";
			}

			if ($pal_grade_c != $pal_olddata["pal_grade_c"]) {
				$getedited_val .= "[Grade C] changed from \' " . $pal_olddata["pal_grade_c"] . "\' to \'" . $pal_grade_c . "\'<br>";
			}

			if ($pal_material_wooden != $pal_olddata["pal_material_wooden"]) {
				$getedited_val .= "[Material] Wooden changed from \' " . $pal_olddata["pal_material_wooden"] . "\' to \'" . $pal_material_wooden . "\'<br>";
			}

			if ($pal_material_plastic != $pal_olddata["pal_material_plastic"]) {
				$getedited_val .= "[Material] Plastic changed from \' " . $pal_olddata["pal_material_plastic"] . "\' to \'" . $pal_material_plastic . "\'<br>";
			}

			if ($pal_material_corrugate != $pal_olddata["pal_material_corrugate"]) {
				$getedited_val .= "[Material] Corrugate changed from \' " . $pal_olddata["pal_material_corrugate"] . "\' to \'" . $pal_material_corrugate . "\'<br>";
			}

			if ($pal_entry_2way != $pal_olddata["pal_entry_2way"]) {
				$getedited_val .= "[Entry] 2way changed from \' " . $pal_olddata["pal_entry_2way"] . "\' to \'" . $pal_entry_2way . "\'<br>";
			}

			if ($pal_entry_4way != $pal_olddata["pal_entry_4way"]) {
				$getedited_val .= "[Entry] 4way changed from \' " . $pal_olddata["pal_entry_4way"] . "\' to \'" . $pal_entry_4way . "\'<br>";
			}

			if ($pal_structure_stringer != $pal_olddata["pal_structure_stringer"]) {
				$getedited_val .= "[Structure] Stringer changed from \' " . $pal_olddata["pal_structure_stringer"] . "\' to \'" . $pal_structure_stringer . "\'<br>";
			}

			if ($pal_structure_block != $pal_olddata["pal_structure_block"]) {
				$getedited_val .= "[Structure] Block changed from \' " . $pal_olddata["pal_structure_block"] . "\' to \'" . $pal_structure_block . "\'<br>";
			}

			if ($pal_heat_treated != $pal_olddata["pal_heat_treated"]) {
				$getedited_val .= "[Heat Treated] change from \' " . $pal_olddata["pal_heat_treated"] . "\' to \'" . $pal_heat_treated . "\'<br>";
			}
			//
			$pal_grade = '';
			$grade = "";
			if ($pal_grade_a == "Yes") {
				$pal_grade = 'A';
				$grade = 'Y';
			}

			if ($pal_grade_b == "Yes") {
				if ($grade == 'Y') {
					$pal_grade .= ', B';
				} else {
					$pal_grade = 'B';
					$grade = 'Y';
				}
			}

			if ($pal_grade_c == "Yes") {
				if ($grade == 'Y') {
					$pal_grade .= ', C';
				} else {
					$pal_grade = 'C';
				}
			}

			//
			$pal_material = '';
			$material = "";
			if ($pal_material_wooden == "Yes") {
				$pal_material = 'Wooden';
				$material = 'Y';
			}

			if ($pal_material_plastic == "Yes") {
				if ($material == 'Y') {
					$pal_material .= ', Plastic';
				} else {
					$pal_material = 'Plastic';
					$material = 'Y';
				}
			}

			if ($pal_material_corrugate == "Yes") {
				if ($material == 'Y') {
					$pal_material .= ', Corrugate';
				} else {
					$pal_material = 'Corrugate';
				}
			}

			//
			$pal_entry = '';
			$entry = "";
			if ($pal_entry_2way == "Yes") {
				$pal_entry = '2-way';
				$entry = 'Y';
			}

			if ($pal_entry_4way == "Yes") {
				if ($entry == 'Y') {
					$pal_entry .= ', 4-way';
				} else {
					$pal_entry = '4-way';
				}
			}

			//
			$structure = "";
			$pal_structure = '';
			if ($pal_structure_stringer == "Yes") {
				$pal_structure = 'Stringer';
				$structure = 'Y';
			}

			if ($pal_structure_block == "Yes") {
				if ($structure == 'Y') {
					$pal_structure .= ', Block';
				} else {
					$pal_structure = 'Block';
				}
			}

			//----edited Send auto email Pallets------------------------------------------
			//
			if ($_REQUEST['pal_item_length'] == "") {
				$pal_item_length_n = "0";
			} else {
				$pal_item_length_n = $_REQUEST['pal_item_length'];
			}
			//
			if ($_REQUEST['pal_item_width'] == "") {
				$pal_item_width_n = "0";
			} else {
				$pal_item_width_n = $_REQUEST['pal_item_width'];
			}
			//
			if ($_REQUEST["pal_quantity_requested"] == "Select One") {
				$pal_quantity_requested_n = "None";
			} else {
				if ($_REQUEST['pal_quantity_requested'] == "Other") {
					$pal_quantity_requested_n = $_REQUEST['pal_quantity_requested'] . "- " . $_REQUEST['pal_other_quantity'];
				} else {
					$pal_quantity_requested_n = $_REQUEST['pal_quantity_requested'];
				}
			}
			//
			if ($_REQUEST['pal_frequency_order'] == "Select One") {
				$pal_frequency_order_n = "None";
			} else {
				$pal_frequency_order_n = $_REQUEST['pal_frequency_order'];
			}
			//
			if ($_REQUEST['pal_what_used_for'] == "" || $_REQUEST['pal_what_used_for'] == " ") {
				$pal_what_used_for_n = "No";
			} else {
				$pal_what_used_for_n = $_REQUEST['pal_what_used_for'];
			}

			//
			if ($_REQUEST['pal_sales_desired_price'] == "") {
				$pal_sales_desired_price_n = "0.00";
			} else {
				$pal_sales_desired_price_n = number_format($_REQUEST['pal_sales_desired_price'], 2);
			}
			//
			if ($_REQUEST['pal_note'] == "") {
				$pal_note_n = "None";
			} else {
				$pal_note_n = $_REQUEST['pal_note'];
			}
			//
			$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt; font-weight:600;\"><u>Profile</u></span>";
			$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;'><strong>Item:</strong> $quote_item_name</li>
						<li style='padding-bottom:4px;'><strong>Ideal Size (in):</strong>" . $pal_item_length_n . " x " . $pal_item_width_n . "</li>
						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $pal_quantity_requested_n . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $pal_frequency_order_n . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $pal_what_used_for_n . "</li>

						<li style='padding-bottom:4px;'><strong>Desired Price: </strong> $" . $pal_sales_desired_price_n . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $pal_note_n . "</li>
						</ul>";

			//-----Tolerances---------
			$rq_item .= "<span style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-weight:600; font-size:16pt;\"><u>Tolerances</u></span>
				<ul style=\"color:#3b3838;font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\">
				<li style='padding-bottom:4px;'><strong>Grade: </strong> 
					" . $pal_grade . "
				</li>

				<li style='padding-bottom:4px;'><strong>Material: </strong>
					" . $pal_material . "
				</li>

				<li style='padding-bottom:4px;'><strong>Entry: </strong>
				  " . $pal_entry . "
				 </li>

				<li style='padding-bottom:4px;'><strong>Structure: </strong>
  					" . $pal_structure . "
  				</li>

    			<li><strong>Heat Treat: </strong>
    				" . $pal_heat_treated . "
    			</li>
				
				</ul>";

			//
			//
			//$quote_id = tep_db_insert_id();	
			$query2 = db_query("UPDATE `quote_pallets` SET `pal_item_length` = '" . $pal_item_length . "', `pal_item_width` = '" . $pal_item_width . "', `pal_quantity_requested` = '" . $pal_quantity_requested . "', `pal_other_quantity` = '" . $pal_other_quantity . "', `pal_frequency_order` = '" . $pal_frequency_order . "', `pal_what_used_for` = '" . $pal_what_used_for . "', `pal_date_needed_by` = '" . $pal_date_needed_by . "', `pal_note` = '" . $pal_note . "', `pal_quotereq_sales_flag` = '" . $pal_quotereq_sales_flag . "', `pal_sales_desired_price` = '" . $pal_sales_desired_price . "', `pal_grade_a` = '" . $pal_grade_a . "', `pal_grade_b` = '" . $pal_grade_b . "', `pal_grade_c` = '" . $pal_grade_c . "', `pal_material_wooden` = '" . $pal_material_wooden . "', `pal_material_plastic` = '" . $pal_material_plastic . "', `pal_material_corrugate` = '" . $pal_material_corrugate . "', `pal_entry_2way` = '" . $pal_entry_2way . "', `pal_entry_4way` = '" . $pal_entry_4way . "', `pal_structure_stringer` = '" . $pal_structure_stringer . "', `pal_structure_block` = '" . $pal_structure_block . "', `pal_heat_treated` = '" . $pal_heat_treated . "' WHERE `quote_pallets`.`id` = '" . $paltableid . "'");
			//
			$pal_display_data = "Done";
			$updateflagpal = "Yes";
			//---- Send auto email------------------------------------------
			send_demand_email($rq_item, $getedited_val, $company_id, $quote_item_name, $paltableid, 'edit_q');/**/
			//
			//
		}
		if ($_REQUEST["palupdatequotedata"] == 2) {
			$paltableid = $_REQUEST["paltableid"];
			$pallets_notes = $_REQUEST["pallets_notes"];

			$pal_display_data = "Done";
			$updateflagpal = "Yes";
		}
	}
	//
	if (isset($_REQUEST["dbiupdatequotedata"])) {
		if ($_REQUEST["dbiupdatequotedata"] == 1) {
			$dbitableid = $_REQUEST["dbitableid"];
			$dbi_notes = $_REQUEST["dbi_notes"];
			//
			//
			//$quote_id = tep_db_insert_id();	
			$query2 = db_query("UPDATE `quote_dbi` SET `dbi_notes` = '" . $dbi_notes . "' WHERE `quote_dbi`.`id` = '" . $dbitableid . "'");
			//
			$dbi_display_data = "Done";
			$updateflagdbi = "Yes";
		}
		if ($_REQUEST["dbiupdatequotedata"] == 2) {
			$dbitableid = $_REQUEST["dbitableid"];
			$dbi_notes = $_REQUEST["dbi_notes"];

			$dbi_display_data = "Done";
			$updateflagdbi = "Yes";
		}
	}
	//
	if (isset($_REQUEST["recupdatequotedata"])) {
		if ($_REQUEST["recupdatequotedata"] == 1) {
			$rectableid = $_REQUEST["rectableid"];
			$recycling_notes = $_REQUEST["recycling_notes"];
			//
			//
			//$quote_id = tep_db_insert_id();	
			$query2 = db_query("UPDATE `quote_recycling` SET `recycling_notes` = '" . $recycling_notes . "' WHERE `quote_recycling`.`id` = '" . $rectableid . "'");
			//
			$rec_display_data = "Done";
			$updateflagrec = "Yes";
		}
		if ($_REQUEST["recupdatequotedata"] == 2) {
			$rectableid = $_REQUEST["rectableid"];
			$recycling_notes = $_REQUEST["recycling_notes"];

			$rec_display_data = "Done";
			$updateflagrec = "Yes";
		}
	}
	//
	if (isset($_REQUEST["otherupdatequotedata"])) {
		if ($_REQUEST["otherupdatequotedata"] == 1) {
			$othertableid = $_REQUEST["othertableid"];

			$other_quantity_requested = $_REQUEST["other_quantity_requested"];
			$other_other_quantity = $_REQUEST["other_other_quantity"];
			$other_frequency_order = $_REQUEST["other_frequency_order"];
			$other_what_used_for = str_replace("'", "\'", $_REQUEST["other_what_used_for"]);
			$other_date_needed_by = "";
			if ($_REQUEST["other_date_needed_by"] != "") {
				$other_date_needed_by = date("Y-m-d", strtotime($_REQUEST["other_date_needed_by"]));
			}
			$other_need_pallets = $_REQUEST["other_need_pallets"];
			$other_note = str_replace("'", "\'", ($_REQUEST["other_note"]));
			//
			if (isset($_REQUEST["other_need_pallets"])) {
				$other_need_pallets = $_REQUEST["other_need_pallets"];
			} else {
				$other_need_pallets = "No";
			}
			if (isset($_REQUEST["other_quotereq_sales_flag"])) {
				$other_quotereq_sales_flag = $_REQUEST["other_quotereq_sales_flag"];
			} else {
				$other_quotereq_sales_flag = "No";
			}
			//------------------------------------------------------------
			//Check edited value
			$getrecquery = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_other.id = '" . $othertableid . "'";

			$other_oldres = db_query($getrecquery);
			$company_id = $_REQUEST["company_id"];
			$other_olddata = array_shift($other_oldres);
			//
			$quoteitem = $other_olddata["quote_item"];
			//Get Item Name
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quoteitem);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			$pal_quantity_requested = "";
			if ($other_quantity_requested != "Other") {
				if ($other_quantity_requested != $other_olddata["other_quantity_requested"]) {
					$new_other_quantity_requested = "<span style='color:#B10A0A;'>" . $other_quantity_requested . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $other_olddata["other_quantity_requested"] . "\' to \'" . $other_quantity_requested . "\'<br>";
				} else {
					$new_other_quantity_requested = $other_quantity_requested;
				}
			} else {
				if ($other_olddata['other_other_quantity'] != $other_other_quantity) {
					$new_other_quantity_requested = "<span style='color:#B10A0A;'>" . $other_quantity_requested . "-" . $other_other_quantity . "</span>";
					$getedited_val .= "[Quantity Requested] changed from \' " . $other_olddata["other_quantity_requested"] . "-" . $other_olddata['other_other_quantity'] . "\' to \'" . $other_quantity_requested . "-" . $other_other_quantity . "\'<br>";
				} else {
					$new_pal_quantity_requested = $pal_quantity_requested . "-" . $other_other_quantity;
				}
			}
			//
			if ($other_frequency_order != $other_olddata["other_frequency_order"]) {
				$new_other_frequency_order = "<span style='color:#B10A0A;'>" . $other_frequency_order . "</span>";
				$getedited_val .= "[Frequency of Order] changed from \' " . $other_olddata["other_frequency_order"] . "\' to \'" . $other_frequency_order . "\'<br>";
			} else {
				$new_other_frequency_order = $other_frequency_order;
			}
			//
			if ($other_what_used_for != $other_olddata["other_what_used_for"]) {
				$new_other_what_used_for = "<span style='color:#B10A0A;'>" . $other_what_used_for . "</span>";
				$getedited_val .= "[What Used For?] changed from \' " . $other_olddata["other_what_used_for"] . "\' to \'" . $other_what_used_for . "\'<br>";
			} else {
				$new_other_what_used_for = $other_what_used_for;
			}
			//
			//
			$other_need_pallets1 = "";
			if ($other_need_pallets == "") {
				$other_need_pallets1 = "No";
			}
			if ($other_need_pallets != $other_olddata["other_need_pallets"]) {
				$new_other_need_pallets = "<span style='color:#B10A0A;'>" . $other_need_pallets1 . "</span>";
				$getedited_val .= "[Also Need Pallets?] changed from \' " . $other_olddata["other_need_pallets"] . "\' to \'" . $other_need_pallets1 . "\'<br>";
			} else {
				$new_other_need_pallets = $other_need_pallets;
			}
			//
			if ($other_note != $other_olddata["other_note"]) {
				$new_other_note = "<span style='color:#B10A0A;'>" . $other_note . "</span>";
				$getedited_val .= "[Note] changed from \' " . $other_olddata["other_note"] . "\' to \'" . $other_note . "\'<br>";
			} else {
				$new_other_note = $other_note;
			}
			//
			//----Edited Send auto email------------------------------------------
			//
			if ($_REQUEST["other_quantity_requested"] == "Select One") {
				$other_quantity_requested_n = "None";
			} else {
				if ($_REQUEST['other_quantity_requested'] == "Other") {
					$other_quantity_requested_n = $_REQUEST['other_quantity_requested'] . "- " . $_REQUEST['other_other_quantity'];
				} else {
					$other_quantity_requested_n = $_REQUEST['other_quantity_requested'];
				}
			}
			//
			if ($_REQUEST['other_frequency_order'] == "Select One") {
				$other_frequency_order_n = "None";
			} else {
				$other_frequency_order_n = $_REQUEST['other_frequency_order'];
			}
			//
			if ($_REQUEST['other_what_used_for'] == "" || $_REQUEST['other_what_used_for'] == " ") {
				$other_what_used_for_n = "None";
			} else {
				$other_what_used_for_n = $_REQUEST['other_what_used_for'];
			}

			//
			if ($_REQUEST['other_need_pallets'] == "") {
				$other_need_pallets_n = "No";
			} else {
				$other_need_pallets_n = $_REQUEST['other_need_pallets'];
			}
			//
			if ($_REQUEST['other_note'] == "") {
				$other_note_n = "None";
			} else {
				$other_note_n = $_REQUEST['other_note'];
			}

			//
			$rq_item = "<span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\"><u>Profile</u></span>";
			$rq_item .= "<ul style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;\"><li style='padding-bottom:4px;\"><strong>Item:</strong> $quote_item_name</li>

						<li style='padding-bottom:4px;'><strong>Quantity Requested: </strong>" . $other_quantity_requested_n . "</li>

						<li style='padding-bottom:4px;'><strong>Frequency of Order: </strong>" . $other_frequency_order_n . "</li>

						<li style='padding-bottom:4px;'><strong>What Used For?: </strong> " . $other_what_used_for_n . "</li>

						<li style='padding-bottom:4px;'><strong>Also Need Pallets?: </strong> " . $other_need_pallets_n . "</li>

						<li style='padding-bottom:4px;'> <strong>Notes: </strong> " . $other_note_n . "</li>
						</ul>";
			//
			$getedited_val .= "Edited by: " . $_COOKIE["userinitials"] . " Date: " . date("m/d/Y H:i:s");
			//
			$query2 = db_query("UPDATE `quote_other` SET `other_quantity_requested` = '" . $other_quantity_requested . "', `other_other_quantity` = '" . $other_other_quantity . "', `other_frequency_order` = '" . $other_frequency_order . "', `other_what_used_for` = '" . $other_what_used_for . "', `other_date_needed_by` = '" . $other_date_needed_by . "', `other_need_pallets` = '" . $other_need_pallets . "', `other_note` = '" . $other_note . "', `other_quotereq_sales_flag` = '" . $other_quotereq_sales_flag . "' WHERE `quote_other`.`id` = '" . $othertableid . "'");
			//
			$other_display_data = "Done";
			$updateflag = "Yes";
			//---- Send auto email------------------------------------------
			/*send_demand_email($rq_item, $getedited_val, $company_id, $quote_item_name, $othertableid,'edit_q');*/
			//
		}
		if ($_REQUEST["otherupdatequotedata"] == 2) {
			$othertableid = $_REQUEST["othertableid"];
			$other_notes = $_REQUEST["other_notes"];

			$other_display_data = "Done";
			$updateflag = "Yes";
		}
	}
	//---------------------------------------------------------------------------------------------------------
	//Show popup data
	if (isset($_REQUEST["showquotedata"])) {
		if ($_REQUEST["showquotedata"] == 1) {
			if ($_REQUEST["quote_item"] == 1) {

				$tableid = $_REQUEST["quoteid"];
				$record_save1 = "done";
				$updateflagg = "Yes";
			}
			//
			if ($_REQUEST["quote_item"] == 2) {
				$stableid = $_REQUEST["quoteid"];
				$sb_display_data = "done";
				$updateflagsb = "Yes";
			}
		}
		//
	}
	//-------------------------------------------------------------------------------------------------------------
	if ($record_save1 == "done") {
		//Display records
		if ($updateflagg == "Yes") {
			if ($client_dash_flg == 1) {
				$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_request.companyID = " . $_REQUEST["company_id"] . " AND client_dash_flg=1 and `quote_gaylord`.`id` = '" . $_REQUEST['tableid'] . "' ORDER BY quote_gaylord.id asc";
			} else {
				$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_request.companyID = " . $_REQUEST["company_id"] . " AND `quote_gaylord`.`id` = '" . $_REQUEST['tableid']
				 . "' ORDER BY quote_gaylord.id asc";
			}
		} else {
			if ($client_dash_flg == 1) {
				$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_request.companyID = " . $_REQUEST["company_id"] . " AND client_dash_flg=1 ORDER BY quote_gaylord.id asc";
			} else {
				$getrecquery = "SELECT * FROM quote_request INNER JOIN quote_gaylord ON quote_request.quote_id = quote_gaylord.quote_id WHERE quote_request.companyID = " . $_REQUEST["company_id"] . " ORDER BY quote_gaylord.id asc";
			}
		}
		echo "<br>";
		$g_res = db_query($getrecquery);
		$company_id = $_REQUEST["company_id"];

		while ($g_data = array_shift($g_res)) {
			//
			$quote_item = $g_data["quote_item"];
			//Get Item Name
			$getquotequery = db_query("SELECT * FROM quote_request_item WHERE quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
			//--------------------------------------------------------------
			$id = $g_data["id"];
			echo ' <div id="g' . $id . '"><table width="100%"  class="table1" cellpadding="3" cellspacing="1">';
			//---------------check quote send or not-------------------------------
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
				// echo $total_id;

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
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . encrypt_url($quote_rows1["ID"]) . "'>";
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

			//
	?>
			<?php
			db();

			?>
			<tr>
				<td style="background:#91bb78; padding:5px;" colspan="4">
					<table cellpadding="3">
						<tr>
							<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $g_data["quote_id"]; ?>
							</td>
							<td class="boxProSubHeading" width="200px" style="background:#91bb78;">Item Type: <?php echo $quote_item_name; ?></td>

							<td class="boxProSubHeading" style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img id="g_btn_img<?php echo $id ?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="g_btn<?php echo $id ?>" onClick="show_g_details(<?php echo $id ?>)" class="ex_col_btn boxProSubHeading">Expand Details</a>
									<!--<input type="button" name="details_btn" id="g_btn<?php //=$id
																							?>" value="Show Details" onClick="show_g_details(<?php //=$id
																																						?>)">-->
								</font>
							</td>

							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img bgcolor="#91bb78" src="images/edit.jpg" onclick="g_quote_edit(<?php echo $company_id ?>, <?php echo $id ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg ?>)" style="cursor: pointer;">
								</font>
							</td>
							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<a id="lightbox_g<?php echo $id ?>" href="javascript:void(0);" onClick="display_request_gaylords_test(<?php echo $company_id; ?>,<?php echo $id ?>, 1, 2, 1, 0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
							</td>

							<!-- <td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
								<img bgcolor="#91bb78" src="images/del_img.png" onclick="g_quote_delete(<?php echo $id ?>, <?php echo $quote_item ?>,<?php echo $company_id ?>)" style="cursor: pointer;">
								</font></td> -->

							<td>
								<font face="Roboto" size="1">
									<?php
									if ($clientdash_flg == 0 && $g_data["client_dash_flg"] == 1) {
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
				<td>
					<div id="g_sub_table<?php echo $id ?>" style="display: none;">
						<table width="80%" class="in_table_style tableBorder">
							<tr bgcolor="<?php echo $subheading; ?>">
								<td colspan="6">
									<strong>What Do They Buy?</strong>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td width="200px">
									Item
								</td>
								<td colspan="5">
									Gaylord Totes
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>Ideal Size (in)</td>
								<td align="center" width="130px">
									<div class="size_align">
										<span class="label_txt">L</span><br>
										<?php echo $g_data["g_item_length"]; ?>
									</div>
								</td>
								<td width="20px" align="center">x</td>
								<td width="130px">
									<div class="size_align">
										<span class="label_txt">W</span><br>
										<?php echo $g_data["g_item_width"]; ?>
									</div>
								</td>
								<td width="20px" align="center">x</td>
								<td width="130px">
									<div class="size_align">
										<span class="label_txt">H</span><br>
										<?php echo $g_data["g_item_height"]; ?>
									</div>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Quantity Requested
								</td>
								<td colspan=5>
									<?php echo $g_data["g_quantity_request"]; ?>
									<?php
									if ($g_data["g_quantity_request"] == "Other") {
										echo "<br>" . $g_data["g_other_quantity"];
									}
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Frequency of Order
								</td>
								<td colspan=5>
									<?php echo $g_data["g_frequency_order"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									What Used For?
								</td>
								<td colspan=5>
									<?php echo $g_data["g_what_used_for"]; ?>
								</td>
							</tr>
							<!-- <tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Date Needed By?
								</td>
								<td colspan=5>
									<?php echo date('m/d/Y', strtotime($g_data["date_needed_by"])); ?>
								</td>
							</tr> -->
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Also Need Pallets?
								</td>
								<td colspan=5>
									<?php echo $g_data["need_pallets"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Desired Price
								</td>
								<td colspan=5>
									<?php echo $g_data["sales_desired_price_g"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Notes
								</td>
								<td colspan=5>
									<?php echo $g_data["g_item_note"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td colspan="6">
									<strong>Criteria of what they SHOULD be able to use:</strong>
								</td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td><!-- align="right"-->
									Height Flexibility
								</td>
								<td align="center">
									<span class="label_txt">Min</span>
									<br>
									<?php echo $g_data["g_item_min_height"]; ?>
								</td>
								<td align="center">-</td>
								<td align="center">
									<span class="label_txt">Max</span>
									</br>
									<?php echo $g_data["g_item_max_height"]; ?>
								</td>
								<td colspan="2" align="center">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Shape
								</td>
								<td>
									Rectangular
								</td>
								<td>
									<?php
									echo $g_data["g_shape_rectangular"];

									?>
								</td>
								<td>
									Octagonal
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_shape_octagonal"];
									?>
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
									<?php
									echo $g_data["g_wall_1"];
									?>
								</td>
								<td>
									6ply
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_wall_6"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									2ply
								</td>
								<td>
									<?php
									echo $g_data["g_wall_2"];
									?>
								</td>
								<td>
									7ply
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_wall_7"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									3ply
								</td>
								<td>
									<?php
									echo $g_data["g_wall_3"];
									?>
								</td>
								<td>
									8ply
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_wall_8"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									4ply
								</td>
								<td>
									<?php
									echo $g_data["g_wall_4"];
									?>
								</td>
								<td>
									9ply
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_wall_9"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									5ply
								</td>
								<td>
									<?php
									echo $g_data["g_wall_5"];
									?>
								</td>
								<td>
									10ply
								</td>
								<td colspan="2">
									<?php
									echo $g_data["g_wall_10"];
									?>
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
									<?php
									echo $g_data["g_no_top"];
									?>

								</td>
								<td>
									Lid Top
								</td>
								<td colspan="2">
									<?php echo $g_data["g_lid_top"];
									?>

								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Partial Flap Top
								</td>
								<td>
									<?php echo $g_data["g_partial_flap_top"];
									?>
								</td>
								<td>
									Full Flap Top
								</td>
								<td colspan="2">
									<?php echo $g_data["g_full_flap_top"];
									?>
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
									<?php echo $g_data["g_no_bottom_config"];
									?>
								</td>
								<td>
									Partial Flap w/ Slipsheet
								</td>
								<td colspan="2">
									<?php echo $g_data["g_partial_flap_w"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Tray Bottom
								</td>
								<td>
									<?php echo $g_data["g_tray_bottom"];
									?>
								</td>
								<td>
									Full Flap Bottom
								</td>
								<td colspan="2">
									<?php echo $g_data["g_full_flap_bottom"];
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Partial Flap w/o SlipSheet
								</td>
								<td colspan="4">
									<?php echo $g_data["g_partial_flap_wo"]; ?>
								</td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Vents Okay?
								</td>
								<td colspan=5>
									<?php echo $g_data["g_vents_okay"];
									?>
								</td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td colspan="6" align="right" style="padding: 4px;">
									Created By:<?php echo $g_data['user_initials']; ?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Date: <?php echo date("m/d/Y H:i:s", strtotime($g_data['quote_date'])); ?>
									&nbsp;&nbsp;&nbsp;
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
			<?php
			echo "</table></div>";
			?>
			<form name="fq">
				<input type="hidden" id="quote_id_n" name="quote_id_n" value="<?php echo $g_data['quote_id']; ?>">
				<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $company_id; ?>">

			</form>
		<?php
		} //End While

	}
	//
	if ($sb_display_data == "Done") {
		if ($updateflagsb == "Yes") {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 and `quote_shipping_boxes`.`id` = '" . $_REQUEST['stableid'] . "' order by quote_shipping_boxes.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and `quote_shipping_boxes`.`id` = '" . $_REQUEST['stableid'] . "' order by quote_shipping_boxes.id asc";
			}
		} else {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 order by quote_shipping_boxes.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_shipping_boxes ON quote_request.quote_id = quote_shipping_boxes.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_shipping_boxes.id asc";
			}
		}


		$g_res = db_query($getrecquery);
		$company_id = $_REQUEST["company_id"];
		//
		while ($sb_data = array_shift($g_res)) {
			//
			$id = $sb_data["id"];
			echo '<div id="sb' . $id . '"><table width="100%" class="table1" cellpadding="3" cellspacing="1">';

			$quote_item = $sb_data["quote_item"];
			//Get Item Name
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
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . encrypt_url($quote_rows1["ID"]) . "'>";
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
		?>
			<tr>
				<td style="background:#91bb78; padding:5px;" colspan="4">
					<table cellpadding="3">
						<tr>
							<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $sb_data["quote_id"]; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img id="sb_btn_img<?php echo $id ?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="sb_btn<?php echo $id ?>" onClick="show_sb_details(<?php echo $id ?>)" class="ex_col_btn boxProSubHeading">Expand Details</a>
									<!--<input type="button" name="details_btn" id="sb_btn<?php //=$id
																							?>" value="Show Details" onClick="show_sb_details(<?php //=$id
																																						?>)">-->
								</font>
							</td>

							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img bgcolor="#91bb78" src="images/edit.jpg" onclick="sb_quote_edit(<?php echo $company_id ?>, <?php echo $id ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg ?>)" style="cursor: pointer;">
								</font>
							</td>
							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<a id="lightbox_req_shipping<?php echo $id ?>" href="javascript:void(0);" onClick="display_request_shipping_tool_test(<?php echo $company_id; ?>,1,2,1,<?php echo $id; ?>,0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
							</td>

							<td>
								<font face="Roboto" size="1">
									<?php
									if ($clientdash_flg == 0 && $sb_data["client_dash_flg"] == 1) {
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
				<td>
					<div id="sb_sub_table<?php echo $id ?>" style="display: none;">
						<table width="80%" class="in_table_style tableBorder">
							<tr bgcolor="<?php echo $subheading; ?>">
								<td colspan="6">
									<strong>What Do They Buy?</strong>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Item
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
										<?php echo $sb_data["sb_item_length"]; ?>
									</div>
								</td>
								<td width="20px" align="center">x</td>
								<td width="130px">
									<div class="size_align">
										<span class="label_txt">W</span><br>
										<?php echo $sb_data["sb_item_width"]; ?>
									</div>
								</td>
								<td width="20px" align="center">x</td>
								<td width="130px">
									<div class="size_align">
										<span class="label_txt">H</span><br>
										<?php echo $sb_data["sb_item_height"]; ?>
									</div>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Quantity Requested
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_quantity_requested"]; ?>
									<?php
									if ($sb_data["sb_quantity_requested"] == "Other") {
										echo "<br>" . $sb_data["sb_other_quantity"];
									}
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Frequency of Order
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_frequency_order"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									What Used For?
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_what_used_for"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Also Need Pallets?
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_need_pallets"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Notes
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_notes"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Desired Price
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_sales_desired_price"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td colspan="6">
									<strong>Criteria of what they should be able to use:</strong>
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
									<?php echo $sb_data["sb_item_min_length"]; ?>

								</td>
								<td align="center">-</td>
								<td align="center">
									<span class="label_txt">Max</span>
									</br>
									<?php echo $sb_data["sb_item_max_length"]; ?>
								</td>
								<td colspan="2" align="center">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td><!-- align="right"-->
									Width
								</td>
								<td align="center">
									<span class="label_txt">Min</span>
									<br>
									<?php echo $sb_data["sb_item_min_width"]; ?>
								</td>
								<td align="center">-</td>
								<td align="center">
									<span class="label_txt">Max</span>
									</br>
									<?php echo $sb_data["sb_item_max_width"]; ?>
								</td>
								<td colspan="2" align="center">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td><!-- align="right"-->
									Height
								</td>
								<td align="center">
									<span class="label_txt">Min</span>
									<br>
									<?php echo $sb_data["sb_item_min_height"]; ?>
								</td>
								<td align="center">-</td>
								<td align="center">
									<span class="label_txt">Max</span>
									</br>
									<?php echo $sb_data["sb_item_max_height"]; ?>
								</td>
								<td colspan="2" align="center">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td><!-- align="right"-->
									Cubic Footage
								</td>
								<td align="center">
									<span class="label_txt">Min</span>
									<br>
									<?php echo $sb_data["sb_cubic_footage_min"]; ?>
								</td>
								<td align="center">-</td>
								<td align="center">
									<span class="label_txt">Max</span>
									</br>
									<?php echo $sb_data["sb_cubic_footage_max"]; ?>
								</td>
								<td colspan="2" align="center">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									# of Walls
								</td>
								<td>
									1ply
								</td>
								<td>
									<?php echo $sb_data["sb_wall_1"]; ?>
								</td>
								<td>
									2ply
								</td>
								<td colspan="2">
									<?php echo $sb_data["sb_wall_2"]; ?>
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
									<?php echo $sb_data["sb_no_top"]; ?>
								</td>
								<td>
									Full Flap Top
								</td>
								<td colspan="2">
									<?php echo $sb_data["sb_full_flap_top"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>&nbsp;</td>
								<td> Partial Flap Top </td>
								<td> <?php echo $sb_data["sb_partial_flap_top"]; ?> </td>
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
									<?php echo $sb_data["sb_no_bottom"]; ?>
								</td>
								<td>
									Full Flap Bottom
								</td>
								<td colspan="2">
									<?php echo $sb_data["sb_full_flap_bottom"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>&nbsp;</td>
								<td>Partial Flap Bottom</td>
								<td><?php echo $sb_data["sb_partial_flap_bottom"]; ?> </td>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Vents Okay?
								</td>
								<td colspan=5>
									<?php echo $sb_data["sb_vents_okay"]; ?>
								</td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td colspan="6" align="right" style="padding: 4px;">
									Created By:<?php echo $sb_data['user_initials']; ?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Date: <?php echo date("m/d/Y H:i:s", strtotime($sb_data['quote_date'])); ?>
									&nbsp;&nbsp;&nbsp;
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

			<?php
			echo "</table></div>";
			?>
			<form name="fq">
				<input type="hidden" id="sb_quote_id_n" name="sb_quote_id_n" value="<?php echo $sb_data['quote_id']; ?>">
				<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $company_id; ?>">
			</form>
		<?php
		} //End While

	}
	//
	if ($sup_update_data == "Done") {
		if ($updateflagsup == "Yes") {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 and `quote_supersacks`.`id` = '" . $_REQUEST['suptableid'] . "' order by quote_supersacks.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and `quote_supersacks`.`id` = '" . $_REQUEST['suptableid'] . "' order by quote_supersacks.id asc";
			}
		} else {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 order by quote_supersacks.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_supersacks ON quote_request.quote_id = quote_supersacks.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_supersacks.id asc";
			}
		}



		$g_res = db_query($getrecquery);
		$company_id = $_REQUEST["company_id"];
		while ($sup_data = array_shift($g_res)) {
			//
			$id = $sup_data["id"];
			echo '<div id="sup' . $id . '"><table width="100%" class="table1" cellpadding="3" cellspacing="1">';
			$quote_item = $sup_data["quote_item"];
			//Get Item Name
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
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;

				$sup_no_of_quote_sent1 = "";
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
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . encrypt_url($quote_rows1["ID"]) . "'>";
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
		?>
			<tr>
				<td style="background:#91bb78; padding:5px;" colspan="4">
					<table cellpadding="3">
						<tr>
							<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $sup_data["quote_id"]; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img id="sup_btn_img<?php echo $id ?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="sup_btn<?php echo $id ?>" onClick="show_sup_details(<?php echo $id ?>)" class="ex_col_btn boxProSubHeading">Expand Details</a>
								</font>
							</td>


							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img bgcolor="#91bb78" src="images/edit.jpg" onclick="sup_quote_edit(<?php echo $company_id ?>, <?php echo $id ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg ?>)" style="cursor: pointer;">
								</font>
							</td>
							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<a id="lightbox_req_supersacks<?php echo $id ?>" href="javascript:void(0);" onClick="display_request_supersacks_tool_test(<?php echo $company_id; ?>,1,2,1,<?php echo $id; ?>, 0,1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
								</font>
							</td>
							<td>
								<font face="Roboto" size="1">
									<?php
									if ($clientdash_flg == 0 && $sup_data["client_dash_flg"] == 1) {
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
				<td>
					<div id="sup_sub_table<?php echo $id ?>" style="display: none;">
						<table class="table_sup" width="100%">
							<tr>
								<td>
									<table width="80%" class="in_table_style tableBorder">
										<tr bgcolor="<?php echo $subheading; ?>">
											<td colspan="6">
												<strong>What Do They Buy?</strong>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Item
											</td>
											<td colspan="5">
												Supersacks
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>Ideal Size (in)</td>
											<td width="130px">
												<div class="size_align">
													<span class="label_txt">L</span><br>
													<?php echo $sup_data["sup_item_length"]; ?>
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px">
												<div class="size_align">
													<span class="label_txt">W</span><br>
													<?php echo $sup_data["sup_item_width"]; ?>
												</div>
											</td>
											<td width="20px" align="center">x</td>
											<td width="130px">
												<div class="size_align">
													<span class="label_txt">H</span><br>
													<?php echo $sup_data["sup_item_height"]; ?>
												</div>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Quantity Requested
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_quantity_requested"]; ?>
												<?php
												if ($sup_data["sup_quantity_requested"] == "Other") {
													echo "<br>" . $sup_data["sup_other_quantity"];
												}
												?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor2; ?>">
											<td>
												Frequency of Order
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_frequency_order"]; ?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												What Used For?
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_what_used_for"]; ?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Also Need Pallets?
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_need_pallets"]; ?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Desired Price
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_sales_desired_price"]; ?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td>
												Notes
											</td>
											<td colspan=5>
												<?php echo $sup_data["sup_notes"]; ?>
											</td>
										</tr>

										<tr bgcolor="<?php echo $rowcolor1; ?>">
											<td colspan="6" align="right" style="padding: 4px;">
												Created By:<?php echo $sup_data['user_initials']; ?>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												Date: <?php echo date("m/d/Y H:i:s", strtotime($sup_data['quote_date'])); ?>
												&nbsp;&nbsp;&nbsp;
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
				<td style="background:#FFFFFF; height:4px;">
				</td>
			</tr>
			<?php
			echo "</table></div>";
			?>
			<form name="fq">
				<input type="hidden" id="sup_quote_id_n" name="sup_quote_id_n" value="<?php echo $sup_data['quote_id']; ?>">
				<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $company_id; ?>">
			</form>
		<?php
		} //End While

		//
	}
	//
	if ($pal_display_data == "Done") {
		//
		if ($updateflagpal == "Yes") {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 and `quote_pallets`.`id` = '" . $_REQUEST['paltableid'] . "' order by quote_pallets.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and `quote_pallets`.`id` = '" . $_REQUEST['paltableid'] . "' order by quote_pallets.id asc";
			}
		} else {
			if ($client_dash_flg == 1) {
				$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and client_dash_flg=1 order by quote_pallets.id asc";
			} else {
				$getrecquery = "Select * from quote_request INNER JOIN quote_pallets ON quote_request.quote_id = quote_pallets.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_pallets.id asc";
			}
		}


		$sb_data = db_query($getrecquery);
		//
		$company_id = $_REQUEST["company_id"];
		//
		while ($pal_data = array_shift($sb_data)) {
			//
			$pal_id = $pal_data["id"];
			echo ' <div id="pal' . $pal_id . '"><table width="100%" class="table1" cellpadding="3" cellspacing="1">';
			$quote_item = $pal_data["quote_item"];
			//Get Item Name
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
			$qtr = 0;
			while ($quote_rows1 = array_shift($chk_quote_res1)) {
				$quote_req = $quote_rows1["quoteRequest"];
				//if (strpos($quote_req, ',') !== false) {
				$quote_req_id = explode(",", $quote_req);
				$total_id = count($quote_req_id);
				// echo $total_id;
				$pal_no_of_quote_sent1 = "";
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
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . encrypt_url($quote_rows1["ID"]) . "'>";
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
		?>
			<tr>
				<td style="background:#91bb78; padding:5px;" colspan="4">
					<table cellpadding="3">
						<tr>
							<td class="boxProSubHeading" style="background:#91bb78;">Box Profile ID: <?php echo $pal_data["quote_id"]; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;" width="200px">Item Type: <?php echo $quote_item_name; ?>
							</td>
							<td class="boxProSubHeading" style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img id="pal_btn_img<?php echo $pal_id ?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="pal_btn<?php echo $pal_id ?>" onClick="show_pal_details(<?php echo $pal_id ?>)" class="ex_col_btn boxProSubHeading">Expand Details</a>
									<!--<input type="button" name="details_btn" id="pal_btn<?php //=$pal_id
																							?>" value="Show Details" onClick="show_pal_details(<?php //=$pal_id
																																								?>)">-->
								</font>
							</td>

							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img bgcolor="#91bb78" src="images/edit.jpg" onclick="pal_quote_edit(<?php echo $company_id ?>, <?php echo $pal_id ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg ?>)" style="cursor: pointer;">
								</font>
							</td>
							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<a id="lightbox_req_pal<?php echo $pal_id ?>" href="javascript:void(0);" onClick="display_request_Pallet_tool_test(<?php echo $company_id; ?>,1,2,1,<?php echo $pal_id; ?>, 0,0,0,0, 1)" class="ex_col_btn boxProSubHeading"><u>Browse Inventory That Matches</u></a>
								</font>
							</td>
							<td>
								<font face="Roboto" size="1">
									<?php
									if ($clientdash_flg == 0 && $pal_data["client_dash_flg"] == 1) {
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
				<td>
					<div id="pal_sub_table<?php echo $pal_id ?>" style="display: none;">
						<table width="80%" class="in_table_style tableBorder">
							<tr bgcolor="<?php echo $subheading; ?>">
								<td colspan="7">
									<strong>What Do They Buy?</strong>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td width="250px">
									Item
								</td>
								<td colspan="6">
									Pallets
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>Ideal Size (in)</td>
								<td width="140px">
									<div class="size_align">
										<span class="label_txt">L</span><br>
										<?php echo $pal_data["pal_item_length"]; ?>
									</div>
								</td>
								<td width="40px" align="center">x</td>
								<td colspan="4">
									<div class="size_align">
										<span class="label_txt">W</span><br>
										<?php echo $pal_data["pal_item_width"]; ?>
									</div>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Quantity Requested
								</td>
								<td colspan=6>
									<?php echo $pal_data["pal_quantity_requested"]; ?>
									<?php
									if ($pal_data["pal_quantity_requested"] == "Other") {
										echo "<br>" . $pal_data["pal_other_quantity"];
									}
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Frequency of Order
								</td>
								<td colspan=6>
									<?php echo $pal_data["pal_frequency_order"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									What Used For?
								</td>
								<td colspan=6>
									<?php echo $pal_data["pal_what_used_for"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Desired Price
								</td>
								<td colspan=6>
									<?php echo $pal_data["pal_sales_desired_price"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Notes
								</td>
								<td colspan=6>
									<?php echo $pal_data["pal_note"]; ?>
								</td>
							</tr>

							<tr bgcolor="#d5d5d5">
								<td colspan="7"><strong>Criteria of what they SHOULD be able to use:</strong>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>Grade </td>
								<td>A</td>
								<td align="center"><?php echo $pal_data['pal_grade_a']; ?></td>
								<td width="140px">B</td>
								<td width="40px" align="center"><?php echo $pal_data['pal_grade_b']; ?></td>
								<td width="140px">C</td>
								<td align="center"><?php echo $pal_data['pal_grade_c']; ?></td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>Material </td>
								<td>Wooden</td>
								<td align="center"><?php echo $pal_data['pal_material_wooden']; ?></td>
								<td>Plastic</td>
								<td align="center"><?php echo $pal_data['pal_material_plastic']; ?></td>
								<td>Corrugate</td>
								<td align="center"><?php echo $pal_data['pal_material_corrugate']; ?></td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>Entry</td>
								<td>2-way</td>
								<td align="center"><?php echo $pal_data['pal_entry_2way']; ?></td>
								<td>4-way</td>
								<td colspan="3">&ensp;<?php echo $pal_data['pal_entry_4way']; ?></td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>Structure</td>
								<td>Stringer</td>
								<td align="center"><?php echo $pal_data['pal_structure_stringer']; ?></td>
								<td>Block</td>
								<td colspan="3">&ensp;<?php echo $pal_data['pal_structure_block']; ?></td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>Heat Treated</td>
								<td colspan=6><?php echo $pal_data['pal_heat_treated']; ?></td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td colspan="7" align="right" style="padding: 4px;">
									Created By:<?php echo $pal_data['user_initials']; ?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Date: <?php echo date("m/d/Y H:i:s", strtotime($pal_data['quote_date'])); ?>
									&nbsp;&nbsp;&nbsp;
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
			<?php
			echo "</table></div>";
			?>
			<form name="fq">
				<input type="hidden" id="pal_quote_id_n" name="pal_quote_id_n" value="<?php echo $pal_data['quote_id']; ?>">
				<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $company_id; ?>">
			</form>
		<?php
		} //End While

	}


	//
	if ($other_display_data == "Done") {
		if ($updateflag == "Yes") {
			$getrecquery = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " and `quote_other`.`id` = '" . $_REQUEST['othertableid'] . "' order by quote_other.id asc ";
		} else {
			$getrecquery = "Select * from quote_request INNER JOIN quote_other ON quote_request.quote_id = quote_other.quote_id where quote_request.companyID = " . $_REQUEST["company_id"] . " order by quote_other.id asc ";
		}

		$g_res = db_query($getrecquery);
		$company_id = $_REQUEST["company_id"];
		while ($other_data = array_shift($g_res)) {
			//
			$other_id = $other_data["id"];
			echo '<div id="other' . $other_id . '"><table width="100%" class="table1" cellpadding="3" cellspacing="1">';
			$quote_item = $other_data["quote_item"];
			//Get Item Name
			$getquotequery = db_query("Select * from quote_request_item where quote_rq_id=" . $quote_item);
			$quote_item_rs = array_shift($getquotequery);
			$quote_item_name = $quote_item_rs['item'];
			//
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
					$other_no_of_quote_sent = 0;
					if ($quote_req_id[$req] == $other_qut_id) {

						if ($quote_rows1["filename"] != "") {

							$qtid = $quote_rows1["ID"];
							$qtf = $quote_rows1["filename"];
							//
							$link = "<a href='#' id='quotespdf" . $qtid . "' onclick=\"show_file_inviewer_pos('quotes/" . $qtf . "', 'Quote', 'quotespdf" . $qtid . "'); return false;\">";
						} else {
							if ($quote_rows1["quoteType"] == "Quote") {
								$link = "<a target='_blank' href='fullquote_mrg.php?ID=" . encrypt_url($quote_rows1["ID"]) . "'>";
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
			//
		?>
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
									<img id="other_btn_img<?php echo $other_id ?>" src="images/plus-icon.png" />&nbsp;<a name="details_btn" id="other_btn<?php echo $other_id ?>" onClick="show_other_details(<?php echo $other_id ?>)" class="ex_col_btn boxProSubHeading">Expand Details</a>
									<!-- <input type="button" name="details_btn" id="other_btn<?php //=$other_id
																								?>" value="Show Details" onClick="show_other_details(<?php //=$other_id
																																										?>)">-->
								</font>
							</td>

							<td style="background:#91bb78;">
								<font face="Roboto" size="1" color="#91bb78">
									<img bgcolor="#91bb78" src="images/edit.jpg" onclick="other_quote_edit(<?php echo $company_id ?>, <?php echo $other_id ?>, <?php echo $quote_item ?>, <?php echo $client_dash_flg ?>)" style="cursor: pointer;">
								</font>
							</td>

							<td>
								<font face="Roboto" size="1">
									<?php
									if ($clientdash_flg == 0 && $other_data["client_dash_flg"] == 1) {
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
				<td>
					<div id="other_sub_table<?php echo $other_id ?>" style="display: none;">
						<table width="80%" class="in_table_style tableBorder">
							<tr bgcolor="<?php echo $subheading; ?>">
								<td colspan="6">
									<strong>What Do They Buy?</strong>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Quantity Requested
								</td>
								<td colspan=5>
									<?php echo $other_data["other_quantity_requested"]; ?>
									<?php
									if ($other_data["other_quantity_requested"] == "Other") {
										echo "<br>" . $other_data["other_other_quantity"];
									}
									?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Frequency of Order
								</td>
								<td colspan=5>
									<?php echo $other_data["other_frequency_order"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									What Used For?
								</td>
								<td colspan=5>
									<?php echo $other_data["other_what_used_for"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor1; ?>">
								<td>
									Also Need Pallets?
								</td>
								<td colspan=5>
									<?php echo $other_data["other_need_pallets"]; ?>
								</td>
							</tr>
							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td>
									Notes
								</td>
								<td colspan=5>
									<?php echo $other_data["other_note"]; ?>
								</td>
							</tr>

							<tr bgcolor="<?php echo $rowcolor2; ?>">
								<td colspan="6" align="right" style="padding: 4px;">
									Created By:<?php echo $other_data['user_initials']; ?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Date: <?php echo date("m/d/Y H:i:s", strtotime($other_data['quote_date'])); ?>
									&nbsp;&nbsp;&nbsp;
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
			<?php
			echo "</table></div>";
			?>
			<form name="fq">
				<input type="hidden" id="other_quote_id_n" name="other_quote_id_n" value="<?php echo $other_data["quote_id"]; ?>">
				<input type="hidden" id="comp_id" name="comp_id" value="<?php echo $company_id; ?>">
			</form>
	<?php
		} //End While

	}

	function send_demand_email(string $rq_item, string $getedited_val, string $company_id, string $quote_item_name, string $quote_id, string $flg): string
	{
		//
		$item_nm_s = "";
		$item_nm = "";
		if ($quote_item_name == "Gaylord Totes") {
			$item_nm = "gaylord";
			$item_nm_s = "gaylords";
		}
		if ($quote_item_name == "Shipping Boxes") {
			$item_nm = "shipping Box";
			$item_nm_s = "shipping Boxes";
		}
		if ($quote_item_name == "Supersacks") {
			$item_nm = "supersack";
			$item_nm_s = "supersacks";
		}
		if ($quote_item_name == "Pallets") {
			$item_nm = "pallet";
			$item_nm_s = "pallets";
		}
		if ($quote_item_name == "Other") {
			$item_nm = "other item";
			$item_nm_s = "other items";
		}
		//

		//
		$qry = "select * from companyInfo where ID='" . $company_id . "'";
		db_b2b();
		$qryc_res = db_query($qry);
		$qryc_row = array_shift($qryc_res);

		$shippinginfo = "";
		if (trim($qryc_row["shipContact"]) == "") {
			$shippinginfo = "<font color=red>Contact Missing!</font>";
		} else {
			$shippinginfo = $qryc_row["shipContact"];
		}
		if (trim($qryc_row["shipContact"]) == "") {
			$shippinginfo = "<font color=red>Contact Missing!</font>";
		} else {
			$shippinginfo = $qryc_row["shipContact"];
		}

		$shippinginfo .= "<br>" . get_nickname_val($qryc_row["company"], $company_id);

		if (trim($qryc_row["shipAddress"]) == "") {
			$shippinginfo .= "<br><font color=red>Address Missing!</font>";
		} else {
			$shippinginfo .= "<br>" . $qryc_row["shipAddress"];
		}

		if ($qryc_row["shipAddress2"] != "") {
			$shippinginfo .= "<br>" . $qryc_row["shipAddress2"];
		}
		if (trim($qryc_row["shipCity"] . $qryc_row["shipState"] . $qryc_row["shipZip"]) == "") {
			$shippinginfo .= "<br><font color=red>City State Zip Missing!</font>";
		} else {
			$shippinginfo .= "<br>" . $qryc_row["shipCity"] . ", " . $qryc_row["shipState"] . " " . $qryc_row["shipZip"];
		}
		if (trim($qryc_row["shipPhone"]) == "") {
			$shippinginfo .= "<br><font color=red>Phone Missing!</font>";
		} else {
			$shippinginfo .= "<br>" . $qryc_row["shipPhone"];
		}

		//
		$assignedto = $qryc_row["assignedto"];
		$user_qrya = "SELECT b2b_id, name, title, email, phoneext from loop_employees where b2b_id = '" . $assignedto . "'";
		$user_resa = db_query($user_qrya);
		$user_resa_data = array_shift($user_resa);
		$add_acctowner_cc = $user_resa_data["email"];
		$emp_name = $user_resa_data["name"];
		$emp_title = $user_resa_data["title"];
		$emp_email = $user_resa_data["email"];
		$emp_phoneext = $user_resa_data["phoneext"];
		$shippinginfo .= "<br><a href='mailto:" . $emp_email . "'>" . $qryc_row["shipemail"] . "</a><br>" . $qryc_row["shipping_receiving_hours"];
		//
		//Code to send mail
		require '../phpmailer/PHPMailerAutoload.php';

		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = 'smtp.office365.com';
		$mail->Port       = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth   = true;
		$mail->Username = "ucbemail@usedcardboardboxes.com";
		$mail->Password = '#UCBgrn4652';

		$comp_name = get_nickname_val('', $company_id);
		//
		$log_text = "";
		$data = "";
		$subject = "";
		if ($flg == 'new_q') {
			$subject = "Customer Entered Demand Entry #" . $quote_id . " for " . $comp_name;
			$subject = preg_replace("/'/", "&#39;", $subject);

			$data = "<div style=\"width:100%; font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Customer entered demand entry for " . $item_nm_s . "!</div> <br>";
			$data .= "<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">" . $emp_name . " has documented the demand for this qualified lead.</div><br>
			
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#767171; margin-top:3px;\">Please take a moment to open this demand entry’s " . $item_nm . " matching tool and see if any of your owned " . $item_nm_s . " would be a good fit to fill this demand. If so, work directly with the " . $emp_name . " to get the deal done!</div><br>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Demand Entry</div><br>";

			$log_text = $subject . "<br>" . $rq_item;
		}
		if ($flg == 'edit_q') {
			$subject = "Customer Entered Edited Demand Entry #" . $quote_id . " for " . $comp_name;
			$subject = preg_replace("/'/", "&#39;", $subject);

			$data = "<div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Customer entered demand entry for " . $item_nm_s . " has been edited.</div> <br>";
			$data .= "<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">" . $emp_name . " has edited the customer entered demand entry for this qualified lead.</div><br>
			
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">Please take a moment to open this demand entry’s " . $item_nm . " matching tool and see if any of your owned " . $item_nm_s . " would be a good fit to fill this demand. If so, work directly with the " . $emp_name . " to get the deal done!</div><br>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Customer Entered Demand Entry</div><br>";

			$log_text = $subject . "<br>" . $getedited_val;
			//
		}

		$message = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
		<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
		</style><style scoped>
		.tablestyle {
		   width:800px;
		}
		@media only screen and (max-width: 768px) {
			.tablestyle {
			   width:98%;
			}
		}
		</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";

		$message .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\"><tr><td>";

		//$message .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align='center'><tr><td><img src='https://www.ucbzerowaste.com/images/logo2.png' width='80px' height='auto'></td><td align='right' valign='bottom'><span style='font-family: Montserrat; font-size:12pt;color:#538135;'><i>Creating profit by reducing waste... with integrity and transparency</i></span></td></tr></table>";		

		//$message .= "</td></tr><tr><td height='20px' style='border-top:1px solid #538135;'>&nbsp;</td></tr><tr><td>";

		$message .= "<div style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#a6a6a6;\" >Demand Entry #" . $quote_id . "</div><p style='margin-top:2px;'>
		<a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=encrypt_url($company_id)'><span  style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#4472cf;\">" . get_nickname_val('', $company_id) . "</span></a></p><br>" . $data . " " . $rq_item . "<br><br>";

		$message .= "</td></tr><tr><td><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Customer Address</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080;\">" . $shippinginfo . "</span>
		<br><br></td></tr><tr><td>";

		$signature = "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
		$signature .= "<td><p style='font-size:13pt;color:#538135'><b><u>$emp_name</u><br>$emp_title</b><br>";
		$signature .= "UsedCardboardBoxes (UCB)</p>";
		$signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
		$signature .= "323-724-2500 x709<br><a href='mailto:" . $emp_email . "'><span style='color:blue'>" . $emp_email . " </span></a><br><br>";
		$signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
		$signature .= "</td></tr></table>";

		//$message .=$signature;

		//$message.="</td></tr><tr><td height='20px' style='border-bottom:1px solid #538135;'></td></tr></table></td></tr></tbody></table></div></body></html>";
		$message .= "</td></tr></table></td></tr></tbody></table></div></body></html>";

		//echo $message."===<br>";

		$to = "Purchasing@UsedCardboardBoxes.com";
		//$to="prasad@extractinfo.com";
		$mail->addAddress($to, $to);

		if ($add_acctowner_cc != "") {
			$mail->AddCC($add_acctowner_cc, $add_acctowner_cc);
		}

		$mail->SetFrom("ucbemail@usedcardboardboxes.com", "ucbemail@usedcardboardboxes.com");

		$mail->IsHTML(true);
		$mail->Encoding = 'base64';
		$mail->CharSet = "UTF-8";
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		if (!$mail->send()) {
			return 'emailerror';
		} else {
			$arr_data = array(
				'companyID' => $company_id,
				'type' => "note",
				'message' => $log_text,
				'employee' => $_COOKIE["userinitials"],
				'messageDate' => date('m/d/Y')
			);

			$query1 = make_insert_query('CRM', $arr_data);
			db_b2b();
			db_query($query1);
			return 'email successful';
		}
	}

	function formatdata(string $data): string
	{
		return addslashes(trim($data));
	}
	function make_insert_query(string $table_name, array $arr_data): string
	{
		$fieldname = "";
		$fieldvalue = "";
		foreach ($arr_data as $fldname => $fldval) {
			$fieldname = ($fieldname == "") ? $fldname : $fieldname . ',' . $fldname;
			$fieldvalue = ($fieldvalue == "") ? "'" . formatdata($fldval) . "'" : $fieldvalue . ",'" . formatdata($fldval) . "'";
		}
		$query1 = "INSERT INTO " . $table_name . " ($fieldname) VALUES($fieldvalue)";
		return $query1;
	}
	//
	?>
</body>

</html>