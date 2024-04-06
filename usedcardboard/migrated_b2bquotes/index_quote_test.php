<?php
ini_set("display_errors", "-1");
error_reporting(E_ERROR);

session_start();

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$ipadd = $_SERVER['REMOTE_ADDR'];

$quote_id_tmp = "";
$quote_id = "";
$product_name_id = 0;
$ship_ltl = "";
if (isset($_REQUEST['quote_id'])) {
	$quote_id_tmp = $_REQUEST['quote_id'];
	$quote_id = $quote_id_tmp - 3770;
	//echo "SELECT item_id FROM quote_to_item WHERE quote_id = ".$quote_id." ORDER BY rel_id ASC";
	db_b2b();
	$resItemId = db_query("SELECT item_id FROM quote_to_item WHERE quote_id = '" . $quote_id . "' ORDER BY sort_order ASC");
	$arrItemID = array();
	if (!empty($resItemId)) {
		while ($rowItemId = array_shift($resItemId)) {
			$arrItemID[] = $rowItemId['item_id'];
		}
	}
	//echo "<pre>"; print_r($resItemId); echo "</pre>";
	$rowb2b = array();
	foreach ($arrItemID as $arrItemIDKey => $arrItemIDVal) {
		db_b2b();
		$resBoxId = db_query("SELECT box_id FROM boxes WHERE ID = '" . $arrItemIDVal . "'");
		$rowBoxId = array_shift($resBoxId);
		db();
		$resLoopBoxDtls = db_query("SELECT * from loop_boxes WHERE b2b_id = '" . $rowBoxId['box_id'] . "'");
		$row = array_shift($resLoopBoxDtls);
		$invId = $row["b2b_id"];
		$boxId = $row["id"];
		db_b2b();
		$resInvdtls = db_query("SELECT * FROM inventory WHERE id = '" . $invId . "'");
		$rowb2b = array_shift($resInvdtls);
	}

	$box_type = $rowb2b["box_type"];
	$browserTitle 	= get_b2bEcomm_boxType_BasicDetails($box_type, 1);
	$pgTitle 		= get_b2bEcomm_boxType_BasicDetails($box_type, 2);
	$idTitle 		= get_b2bEcomm_boxType_BasicDetails($box_type, 3);
	$fullUnitPr 	= get_b2bEcomm_boxType_BasicDetails($box_type, 4);
	$halfUnitPr 	= get_b2bEcomm_boxType_BasicDetails($box_type, 5);
	$qrtrUnitPr 	= get_b2bEcomm_boxType_BasicDetails($box_type, 6);
	$palletUnitPr 	= get_b2bEcomm_boxType_BasicDetails($box_type, 7);


?>
	<!doctype html>
	<html>

	<head>
		<meta charset="utf-8">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=2.0 user-scalable=yes">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title><?php echo $quote_id_tmp ?> | UsedCardboardBoxes</title>
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="CSS/style.css">
		<link rel="stylesheet" href="CSS/radio-pure-css.css">
		<link rel="stylesheet" href="product-slider/slick.css">
		<link rel="stylesheet" href="product-slider/jquery.fancybox.min.css">
		<link rel="stylesheet" href="product-slider/prod-style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="https://www.google.com/jsapi"></script>
		<script>
			function showimg() {
				document.getElementById("map-overlay").style.display = "block";
				document.getElementById('light').style.display = 'block';
				document.getElementById('light').innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('map-overlay').style.display='none';>Close</a> &nbsp;<br/><img src='images/usa_map_territories.png' class='mapimg' style='object-fit: cover;'/>";
			}

			function off_overlay() {
				document.getElementById("map-overlay").style.display = "none";
			}


			function formvalidate(numProd) {
				document.getElementById("orderfrm").submit();
			}

			/*Scroll to top when order clicked BEGIN*/
			$(document).ready(function() {
				$("#ordernow").click(function() {
					$('html,body').animate({
							scrollTop: $(".btn-div").offset().top
						},
						'slow');
				});
			});
			/*Scroll to top when order clicked END*/
		</script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

		<style>
			.highlightrow {
				transition: all ease-in-out .25s;
				background-color: #e4f9e3;
			}

			#map-overlay {
				position: fixed;
				display: none;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: rgba(0, 0, 0, 0.5);
				z-index: 2;
				cursor: pointer;
			}

			.white_content {
				display: none;
				position: absolute;
				top: 50%;
				left: 50%;
				font-size: 14px;
				color: white;
				transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				padding: 10px 10px 10px 10px;
				border: 2px solid black;
				background-color: white;
				overflow: auto;
				height: 350px;
				width: 450px;
				z-index: 1002;
			}

			.mapimg {
				width: 400px;
				height: 300px;
			}

			.seemap {
				cursor: pointer;
			}

			.center {
				margin: auto;
				width: 60%;
				padding: 10px;
			}

			.align-right {
				float: right;
			}

			.align-right table,
			th,
			td {
				border: 1px solid #ddd;
				border-collapse: collapse;
				padding: 5px;

			}

			table.qty-table1 th {
				background-color: #f8f8f8;
			}

			table.qty-table1 {
				float: right;
			}

			table.qty-table1 th {
				font-size: .85em;
				letter-spacing: .1em;
				text-transform: uppercase;
			}

			table.qty-table th,
			table.qty-table td {
				padding: .625em;
				border: 1px solid #ddd;
			}

			textarea {
				padding: 12px 20px;
				box-sizing: border-box;
				border: 2px solid #ccc;
				border-radius: 4px;
				width: 100%;
				height: 80px;
			}

			.ponum-width {
				width: 170px;
			}

			@media screen and (max-width: 1024px) {
				.center {
					width: 80%;
				}

			}

			@media screen and (max-width: 880px) {
				.center {
					width: 90%;
				}

			}

			@media screen and (max-width: 768px) {
				.center {
					width: 94%;
				}

			}

			@media screen and (max-width: 480px) {
				.center {
					width: 98%;
					padding: 10px 0px;
				}

				.scroll-tbl {
					overflow-x: auto;
					width: 100%;
				}

				table.qty-table th,
				table.qty-table td {
					padding: .2em;
				}

				.align-right {
					margin-top: 15px;
				}


			}

			@media screen and (max-width: 414px) {

				table.qty-table th,
				table.qty-table td {
					padding: .2em;
					font-size: 10px;
				}

				table.qty-table .ponum-width {
					width: 80px;
				}
			}


			@media screen and (max-width: 375px) {

				table.qty-table th,
				table.qty-table td {
					padding: .2em;
					font-size: 10px;
				}

				table.qty-table .ponum-width {
					width: 80px;
				}
			}


			@media screen and (max-width: 360px) {
				.white_content {
					width: 350px;
				}

				.mapimg {
					width: 100%;
					height: auto;
				}

				table.qty-table th,
				table.qty-table td {
					padding: .15em;
					font-size: 9px;
				}
			}
		</style>
	</head>

	<body>
		<div id="map-overlay" onclick="off_overlay()">
			<div id="light" class="white_content"> </div>
		</div>

		<a id="ordernow" title="Order Now" href="#">Order Now</a>
		<div class="main_container">
			<div class="sub_container">
				<div class="header">
					<div id="container">
						<div id="left">
							<div class="logo_img">
								<div class="logo_display">
									<a href="https://www.usedcardboardboxes.com/">
										<img src="images/ucb_logo.jpg" alt="moving boxes"></a>
								</div>
							</div>
						</div>
						<div id="right">
							<div class="contact_number">
								<span class="login-username">
									<div class="needhelp">Need help? </div>
									<div class="needhelp_call"><img src="images/callicon.png" alt="" class="call_img">
										<strong>1-888-BOXES-88 (1-888-269-3788)</strong>
									</div>
									<div class="needhelp"><?php include("login.php"); ?></div>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		/*-------------------------------------------------------------------------------
		reason
		tlssitdctklcom2a5veoqvshu1
		tlssitdctklcom2a5veoqvshu1
		-------------------------------------------------------------------------------*/

		$sessionId = session_id();

		/*data store in db for tracking*/
		$machineIP = '';
		$userId = '';
		if (!empty($_COOKIE["uid"])) {
			echo $userId = $_COOKIE["uid"];
		}

		if (isset($_REQUEST['quote_id'])) {
			$id = $quote_id;
		} else {
			if ($_SESSION['productData']) {
				$orderData = $_SESSION['productData'];
				if ($orderData['ProductLoopId'] == $_REQUEST["id"]) {
					$id = $orderData['ProductLoopId'];
				} else {
					$id = $_REQUEST["id"];
				}
			} else {
				$id = $_REQUEST["id"];
			}
		}


		//$id = 2566;
		$recordDt 	= date('YmdHis');


		$machineIP 	= $_SERVER['REMOTE_ADDR'];
		//$machineIP 	= getHostByName(getHostName());

		$location = @unserialize(file_get_contents('http://ip-api.com/php/' . $machineIP));
		if ($location && $location['status'] == 'success') {
			$loczip = $location['zip'];
		}
		/*check the user product entry already or not with current session id*/
		//AND product_loopboxid = '".$id . "'
		db();
		$getSessionDt = db_query("SELECT id FROM b2becommerce_tracking WHERE session_id = '" . $sessionId . "' and quote_id = '" . $id . "'");
		$rowSessionDt = array_shift($getSessionDt);
		if ($rowSessionDt["id"] != "") {
			//db();
			//db_query("Update b2becommerce_tracking set product_loopboxid = '".$id."' where session_id = '".$sessionId."'");
		} else {
			db();
			db_query("INSERT INTO b2becommerce_tracking(session_id, quote_id, machine_ip, user_master_id, record_date) VALUES('" . $sessionId . "', '" . $id . "', '" . $machineIP . "', '" . $userId . "', '" . $recordDt . "')");
		}


		$product_name_id = 1;
		if (isset($_REQUEST["product_name_id"])) {
			$product_name_id = $_REQUEST["product_name_id"];
		}

		$data_product_name1 = "";
		$data_product_name2 = "";
		$data_product_name3 = "";
		$data_product_name4 = "";
		if ($product_name_id == 1) {
			$data_product_name1 = " checked ";
		}
		if ($product_name_id == 2) {
			$data_product_name2 = " checked ";
		}
		if ($product_name_id == 3) {
			$data_product_name3 = " checked ";
		}
		if ($product_name_id == 4) {
			$data_product_name4 = " checked ";
		}



		/*-------------------------------------------------------------------------------
		Started updating by Amarendra dated 01-04-2021 
		-------------------------------------------------------------------------------*/
		?>
		<div class="new_section new-section-margin">
			<div class="new_container">
				<div class="inner-margin center">
					<div class="section-top-margin">
						<h1 class="section-title">Quote #<?php echo $quote_id_tmp; ?></h1>
						<div class="title_desc">Review and Order Now!</div>
					</div>
				</div>
				<?php
				/*Getting quote details on quote_id */
				db_b2b();
				$resQuoteDtls = db_query("SELECT companyID, poNumber, terms, rep, shipDate, quoteDate, via, free_shipping, notes FROM quote WHERE ID = " . $quote_id);
				$rowsQuoteDtls = array_shift($resQuoteDtls);
				?>
				<!--Start Breadcrums-->
				<div class="center">
					<nav aria-label="Breadcrumb">
						<ol class="breadcrumb " role="list" style="margin-top: 0.4em;">
							<li class="breadcrumb__item breadcrumb__item--current">
								<span class="breadcrumb__text breadcrumnow">Select Quantity</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
									<use xlink:href="#chevron-right"></use>
								</svg>
							</li>

							<li class="breadcrumb__item breadcrumb__item--blank" aria-current="step">
								<span class="breadcrumb__text">Contact</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
									<use xlink:href="#chevron-right">
										<symbol id="chevron-right"><svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 10 10">
												<path d="M2 1l1-1 4 4 1 1-1 1-4 4-1-1 4-4"></path>
											</svg></symbol>
									</use>
								</svg>
							</li>
							<li class="breadcrumb__item breadcrumb__item--blank">
								<span class="breadcrumb__text">Shipping</span>
								<svg class="icon-svg icon-svg--color-adaptive-light icon-svg--size-10 breadcrumb__chevron-icon" aria-hidden="true" focusable="false">
									<use xlink:href="#chevron-right"></use>
								</svg>
							</li>
							<li class="breadcrumb__item breadcrumb__item--blank">
								<span class="breadcrumb__text">Payment</span>
							</li>
						</ol>
					</nav>
					<?php //qty-table1
					?>
					<div class="align-right">
						<table class="qty-table1">
							<tr>
								<th align="center">Quote Date</th>
							</tr>
							<tr>
								<td align="center"><?php echo date('m/d/Y', strtotime($rowsQuoteDtls['quoteDate'])); ?></td>
							</tr>
						</table>
					</div>
				</div>

				<!--End Breadcrums-->
				<div class="content-div content-padding">
					<div class="center">
						<div class="productinfo-section">
							<div class="quote_table">
								<table class="qty-table">
									<tr>
										<th class="ponum-width">PO Number</th>
										<th>Terms</th>
										<th>Rep</th>
										<th>Ship date</th>
										<th>Via</th>
									</tr>
									<tr>
										<td class="ponum-width"><?php echo $rowsQuoteDtls['poNumber']; ?></td>
										<td><?php echo $rowsQuoteDtls['terms']; ?></td>
										<td>
											<?php
											db_b2b();
											$resRep = db_query("SELECT employeeID, name FROM employees WHERE employeeID = '" . $rowsQuoteDtls['rep'] . "'");
											$rowRep = array_shift($resRep);
											echo $rowRep['name'];
											?>
										</td>
										<td><?php if ($rowsQuoteDtls['shipDate'] == '0000-00-00 00:00:00' || $rowsQuoteDtls['shipDate'] == '') {
												echo "TBD";
											} else {
												date('m/d/Y', strtotime($rowsQuoteDtls['shipDate']));
											} ?></td>
										<td><?php echo $rowsQuoteDtls['via']; ?></td>
									</tr>
								</table>
							</div>
							<div class="div-space"></div>
							<div class="selected_qty_table scroll-tbl">
								<table class="qty-table">
									<tr>
										<th>Quantity</th>
										<th>Item</th>
										<th width="250px">Description</th>
										<th>Price</th>
										<th>Total</th>
										<th width="50px">Tax</th>
									</tr>
									<?php
									/*Getting quote item details*/
									$subtotal = 0;
									$tax = 0;
									$total = 0;
									db_b2b();
									$resQuoteItems = db_query("SELECT * FROM quote_to_item WHERE quote_id = " . $quote_id . " ORDER BY sort_order");
									while ($rowsQuoteItems = array_shift($resQuoteItems)) {
										db_b2b();
										$resBoxId = db_query("SELECT inventoryID FROM boxes WHERE ID = '" . $rowsQuoteItems["item_id"] . "'");
										$rowBoxId = array_shift($resBoxId);
										db();
										$resLoopBoxDtls = db_query("SELECT * from loop_boxes WHERE b2b_id = '" . $rowBoxId['inventoryID'] . "'");
										$row_box = array_shift($resLoopBoxDtls);

									?>
										<tr>
											<td width="170px"><?php echo number_format($rowsQuoteItems['quantity']); ?></td>
											<td><?php echo $rowsQuoteItems['item']; ?></td>
											<td><?php if ($row_box['system_description'] == "") {
													echo $rowsQuoteItems['description'];
												} else {
													echo $row_box['system_description'];
												} ?></td>
											<td align="right"><?php echo "$" . number_format($rowsQuoteItems['quote_price'], 2); ?></td>
											<td align="right">
												<?php
												$total = $rowsQuoteItems['quantity'] * $rowsQuoteItems['quote_price'];
												echo "$" . number_format($total, 2);
												?>
											</td>
											<td align="right"><?php
																if ($rowsQuoteItems['tax'] == '0' || $rowsQuoteItems['tax'] == '') {
																	echo "";
																} else {
																	echo "$" . number_format($rowsQuoteItems['tax'], 2);
																}
																?></td>
										</tr>
									<?php
										$subtotal 	= $subtotal + $total;
										$tax = $tax + $rowsQuoteItems['tax'];

										$productQnt 		= $rowsQuoteItems['quantity'];
										$productQntprice 	= $rowsQuoteItems['quote_price'];
										$productTotal 		= $total;
									}
									?>
									<?php
									db_b2b();
									$getQuoteShipping = db_query("SELECT free_shipping FROM quote WHERE ID = " . $quote_id);
									$rowQuoteShipping = array_shift($getQuoteShipping);
									if ($rowQuoteShipping['free_shipping'] == 1) {
									?>
										<tr>
											<td width="170px">1</td>
											<td>Delivery</td>
											<td>Delivery Included</td>
											<td align="right">$0.00</td>
											<td align="right">$0.00</td>
											<td align="right"></td>
										</tr>
									<?php } ?>
									<tr>
										<th colspan="4" align="right">Subtotal</th>
										<td align="right"><b><?php echo "$" . number_format($subtotal, 2); ?></b></td>
										<td align="right"></td>
									</tr>
									<tr>
										<th colspan="4" align="right">Tax</th>
										<td align="right"><b><?php echo "$" . number_format($tax, 2); ?></b></td>
										<td align="right"></td>
									</tr>
									<tr>
										<th colspan="4" align="right">Total</th>
										<td align="right"><b><?php echo "$" . number_format(($subtotal + $tax), 2); ?></b></td>
										<td align="right"></td>
									</tr>
								</table>
							</div>
							<form action="contact.php?quote_id=<?php echo $_REQUEST['quote_id']; ?>" method="post" id="orderfrm">
								<div class="div-space"></div>
								<div class="quote_notes_table">
									<table class="qty-table">
										<tr>
											<th align="left" width="20%" style="vertical-align: top;">Quote Notes</th>
											<td align="left" width="90%">
												<textarea name="quoteNotes" id="quoteNotes" class="textarea-style"><?php echo $rowsQuoteDtls['notes']; ?></textarea>
											</td>
										</tr>
									</table>

								</div>
								<?php
								//foreach ($arrItemID as $arrItemIDKey => $arrItemIDVal) {
								db_b2b();
								$arrItemID_cnt = 0;
								$estimated_next_load = "";
								$resItemId = db_query("SELECT item_id FROM quote_to_item WHERE quote_id = '" . $quote_id . "' and item <> 'Delivery' ORDER BY sort_order ASC");
								while ($rowItemId = array_shift($resItemId)) {
									$arrItemIDVal = $rowItemId["item_id"];
									db_b2b();
									$resBoxId = db_query("SELECT inventoryID FROM boxes WHERE ID = '" . $arrItemIDVal . "'");
									$rowBoxId = array_shift($resBoxId);
									db();
									$resLoopBoxDtls = db_query("SELECT * from loop_boxes WHERE b2b_id = '" . $rowBoxId['inventoryID'] . "'");

									$row = array_shift($resLoopBoxDtls);
									$invId = $row["b2b_id"];
									$boxId = $row["id"];
									db_b2b();
									$resInvdtls = db_query("SELECT * FROM inventory WHERE id = '" . $invId . "'");
									$rowb2b = array_shift($resInvdtls);

									$box_type = $rowb2b["box_type"];
									$idTitle = get_b2bEcomm_boxType_BasicDetails($box_type, 3);

									$b2b_shape_rect = $rowb2b["shape_rect"];
									$b2b_shape_oct = $rowb2b["shape_oct"];
									$b2b_top_nolid = $rowb2b["top_nolid"];
									$b2b_top_partial = $rowb2b["top_partial"];
									$b2b_top_full = $rowb2b["top_full"];
									$b2b_top_hinged = $rowb2b["top_hinged"];
									$b2b_top_spout = $rowb2b["top_spout"];
									$b2b_top_open = $rowb2b["top_open"];
									$b2b_top_duffle = $rowb2b["top_duffle"];
									$b2b_top_remove = $rowb2b["top_remove"];
									$b2b_bottom_no = $rowb2b["bottom_no"];
									$b2b_bottom_partial = $rowb2b["bottom_partial"];
									$b2b_bottom_partialsheet = $rowb2b["bottom_partialsheet"];
									$b2b_bottom_fullflap = $rowb2b["bottom_fullflap"];
									$b2b_bottom_interlocking = $rowb2b["bottom_interlocking"];
									$b2b_bottom_tray = $rowb2b["bottom_tray"];
									$b2b_bottom_spout = $rowb2b["bottom_spout"];
									$b2b_bottom_spiked = $rowb2b["bottom_spiked"];
									$b2b_bottom_flat = $rowb2b["bottom_flat"];
									$b2b_vents_no = $rowb2b["vents_no"];
									$b2b_vents_yes = $rowb2b["vents_yes"];
									$b2b_no_of_vents = "";
									if ($rowb2b['vents_no'] == 1) {
										$b2b_no_of_vents = "No";
									} elseif ($rowb2b['vents_yes'] == 1) {
										$b2b_no_of_vents = "Yes";
									}
									$b2b_no_of_vents_min = $rowb2b["no_of_vents_min"];
									$b2b_no_of_vents_max = $rowb2b["no_of_vents_max"];

									$shape = "";
									$bottom = "";
									$top = "";
									$bottom_1 = "";
									$bottom_2 = "";
									$bottom_3 = "";
									$bottom_4 = "";
									$bottom_5 = "";
									$bottom_6 = "";
									$bottom_7 = "";
									$bottom_8 = "";
									$bottom_9 = "";
									$top_1 = "";
									$top_2 = "";
									$top_3 = "";
									$top_4 = "";
									$top_5 = "";
									$top_6 = "";
									$top_7 = "";
									$top_8 = "";
									$shape_1 = "";
									$shape_2 = "";

									if ($b2b_shape_oct == 1) {
										$shape_1 = "Octagonal";
									}

									if ($b2b_shape_rect == 1) {
										$shape_2 = "Rectangular";
									}
									///top..............
									if ($b2b_top_nolid == 1) {
										$top_1 = "None";
									}
									if ($b2b_top_partial == 1) {
										$top_2 = "Partial Flap";
									}
									if ($b2b_top_full == 1) {
										$top_3 = "Full Flap";
									}
									if ($b2b_top_hinged == 1) {
										$top_4 = "Hinged Lid";
									}
									if ($b2b_top_remove == 1) {
										$top_5 = "Removable Lid";
									}
									if ($b2b_top_open == 1) {
										$top_6 = "Open Top";
									}
									if ($b2b_top_spout == 1) {
										$top_7 = "Spout Top";
									}
									if ($b2b_top_duffle == 1) {
										$top_8 = "Duffle Top";
									}

									if ($b2b_bottom_no == 1) {
										$bottom_1 = "None";
									}
									if ($b2b_bottom_partial == 1) {
										$bottom_2 = "Partial Flap Without Slip Sheet";
									}
									if ($b2b_bottom_partialsheet == 1) {
										$bottom_3 = "Partial Flap With Slip Sheet";
									}
									if ($b2b_bottom_fullflap == 1) {
										$bottom_4 = "Full Flap";
									}
									if ($b2b_bottom_interlocking == 1) {
										$bottom_5 = "Interlocking Flaps";
									}
									if ($b2b_bottom_tray == 1) {
										$bottom_6 = "Tray";
									}
									if ($b2b_bottom_spout == 1) {
										$bottom_7 = "Spout Bottom";
									}
									if ($b2b_bottom_flat == 1) {
										$bottom_8 = "Flat Bottom";
									}
									if ($b2b_bottom_spiked == 1) {
										$bottom_9 = "Flat Spiked Bottom";
									}
									$vents = "";
									if ($b2b_vents_no == 1) {
										$vents = "No Vents";
									}
									$val_bottom = $bottom_1 . "," . $bottom_2 . "," . $bottom_3 . "," . $bottom_4 . "," . $bottom_5 . "," . $bottom_6 . "," . $bottom_7 . "," . $bottom_8 . "," . $bottom_9;
									$str1 = trim($val_bottom, ',');
									$bottom = preg_replace('/,+/', ', ', $str1);

									$val_top =  $top_1 . "," . $top_2 . "," . $top_3 . "," . $top_4 . "," . $top_5 . "," . $top_6 . "," . $top_7 . "," . $top_8;
									$str2 = trim($val_top, ',');
									$top = preg_replace('/,+/', ', ', $str2);

									$val_shape =  $shape_1 . "," . $shape_2;
									$str3 = trim($val_shape, ',');
									$shape = preg_replace('/,+/', ', ', $str3);

									$dimension = "";
									$wall_str = "";
									if ($row["uniform_mixed_load"] == "Mixed") {
										$blength_min = floatval($row["blength_min"]);
										$blength_max = floatval($row["blength_max"]);

										if ($blength_min == $blength_max) {
											$dimension = $blength_min . '" x ';
										} else {
											$dimension = $blength_min . ' - ' . $blength_max . '" x ';
										}
										$bwidth_min = floatval($row["bwidth_min"]);
										$bwidth_max = floatval($row["bwidth_max"]);

										if ($bwidth_min == $bwidth_max) {
											$dimension .= $bwidth_min . '" x ';
										} else {
											$dimension .= $bwidth_min . ' - ' . $bwidth_max . '" x ';
										}

										$bheight_min = floatval($row["bheight_min"]);
										$bheight_max = floatval($row["bheight_max"]);

										if ($bheight_min == $bheight_max) {
											$dimension .= $bheight_min;
										} else {
											$dimension .= $bheight_min . ' - ' . $bheight_max . '"';
										}

										$bwall_min = $row["bwall_min"];
										$bwall_max = $row["bwall_max"];

										if ($bwall_min != "" && $bwall_max != "") {
											if ($bwall_min == $bwall_max) {
												$wall_str = $bwall_min . 'ply';
											} else {
												$wall_str = $bwall_min . '-' . $bwall_max . 'ply';
											}
										}
									} else {
										$length = $row["blength"];
										$width = $row["bwidth"];
										$depth = $row["bdepth"];
										if ($row["blength_frac"] != "") {
											$arr_length = explode("/", $row["blength_frac"]);
											if (count($arr_length) > 0) {
												$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
												$length = floatval(intval($length) + $blength_frac);
											}
										}

										if ($row["bwidth_frac"] != "") {
											$arr_length = explode("/", $row["bwidth_frac"]);
											if (count($arr_length) > 0) {
												$bwidth_frac = intval($arr_length[0]) / intval($arr_length[1]);
												$width = floatval($width + $bwidth_frac);
											}
										}
										if ($row["bdepth_frac"] != "") {
											$arr_length = explode("/", $row["bdepth_frac"]);
											if (count($arr_length) > 0) {
												$bdepth_frac = intval($arr_length[0]) / intval($arr_length[1]);
												$depth = floatval($depth + $bdepth_frac);
											}
										}

										$dimension =  ($length == "") ? '' : $length . '" x ';
										$dimension .= ($width == "") ? '' : ' ' . $width . '" x ';
										$dimension .= ($depth == "") ? '' : ' ' . $depth . '"';

										if ($row["bwall"] != "") {
											$wall_str = $row["bwall"] . "ply";
										}
									}

									/*calculating estimated next load start*/
									$warehouse_id = $row["box_warehouse_id"];
									$expected_loads_per_mo = $row["expected_loads_per_mo"];
									$lead_time = $row["lead_time"];
									$next_load_available_date = $row["next_load_available_date"];

									$txt_actual_qty = $row["actual_qty"];
									$txt_after_po = $row["after_po"];
									$txt_last_month_qty = $row["last_month_qty"];
									$availability = $row["availability"];
									$boxes_per_trailer = $row["boxes_per_trailer"];

									//Buy Now, Load Can Ship In
									if ($warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays = round($datediff / (60 * 60 * 24));

										if ($no_of_loaddays < $lead_time) {
											if ($row["lead_time"] > 1) {
												$estimated_next_load = $row["lead_time"] . " Working Days";
											} else {
												if ($row["lead_time"] == 0) {
													$estimated_next_load = "Next Day";
												} else {
													$estimated_next_load = $row["lead_time"] . " Working Day";
												}
											}
										} else {
											if ($no_of_loaddays == -0) {
												$estimated_next_load = "1 Working Day";
											} else {
												$estimated_next_load = $no_of_loaddays . " Working Days";
											}
										}
									} else {
										if ($txt_after_po >= $boxes_per_trailer) {
											if ($row["lead_time"] == 0) {
												$estimated_next_load = "Next Day";
											}
											if ($row["lead_time"] == 1) {
												$estimated_next_load = "Next Day";
											}
											if ($row["lead_time"] > 1) {
												$estimated_next_load = $row["lead_time"] . " Working Days";
											}
										} else {
											if (($row["expected_loads_per_mo"] <= 0) && ($txt_after_po < $boxes_per_trailer)) {
												//$estimated_next_load= "Never (sell the " . $txt_after_po . ")";
											} else {
												$nextload_val = ceil((((($txt_after_po / $boxes_per_trailer) * -1) + 1) / $row["expected_loads_per_mo"]) * 4);
												if ($nextload_val == 0) {
													$estimated_next_load = "Next Week";
												}
												if ($nextload_val == 1) {
													$estimated_next_load = $nextload_val . " Week";
												}
												if ($nextload_val > 1) {
													$estimated_next_load = $nextload_val . " Weeks";
												}
											}
										}
									}
									/*calculating estimated next load end*/
									?>
									<div class="div-space"></div>
									<div class="productinfo-main product-box-shadow">
										<h2 class="prod-title"><?php if ($idTitle != "") {
																	echo $idTitle . ": " . $invId;
																} ?></h2>
										<div class="mob_container">
											<div class="prod-info">

												<?php
												if (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord")))) {
												?>
													<ol class="name-value" style="width: 100%;">
														<li>
															<label for="about">Condition</label>
															<span id="about"><?php echo $rowb2b["newUsed"] ?></span><br><br>
														</li>
														<li>
															<label for="Span1">Dimensions</label>
															<span id="Span1">
																<?php
																echo $dimension;
																?>
															</span><br><br>
														</li>
														<li>
															<label for="Span2">Walls Thick</label>
															<span id="Span2"><?php echo $wall_str; ?></span><br><br>
														</li>
														<li>
															<label for="Span3">Shape</label>
															<span id="Span3"><?php echo $shape; ?></span><br><br>
														</li>
														<li>
															<label for="Span4">Top</label>
															<span id="Span4"><?php echo $top; ?></span><br><br>
														</li>
														<li>
															<label for="Span5">Bottom</label>
															<span id="Span5"><?php echo $bottom; ?></span><br><br>
														</li>
														<li>
															<label for="Span8">Vents</label>
															<span id="Span8"><?php echo ($b2b_no_of_vents == "0") ? '0' : $b2b_no_of_vents; ?></span><br><br>
														</li>
														<li>
															<label for="Span9">Pallet Qty</label>
															<span id="Span9"><?php echo ($row["bpallet_qty"] == "0") ? '0' : number_format($row["bpallet_qty"]); ?><br></span><br><br>
														</li>
														<li>
															<label for="Span10">Truckload Qty</label>
															<span id="Span10"><?php echo ($row["boxes_per_trailer"] == "0") ? '' : number_format($row["boxes_per_trailer"]); ?><br></span><br><br>
														</li>
														<li>
															<label for="Span10">Frequency
																<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
																	<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
																</div>
															</label>
															<span id="Span10">
																<?php echo ($row["expected_loads_per_mo"] != "0" && $row["boxes_per_trailer"] != "0") ? number_format($row["expected_loads_per_mo"] * $row["boxes_per_trailer"]) . "/mo" : '0/mo'; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span11">Previous Use</label>
															<span id="Span11"><?php echo $row["previous_contents"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span12">Ideal Uses</label>
															<span id="Span12"><?php echo $row["ideal_uses"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span13">Notes</label>
															<span id="Span13"><?php echo (empty($row["flyer_notes"])) ? 'None' : $row["flyer_notes"]; ?></span><br><br>
														</li>

													</ol>
												<?php
												} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold")))) {
												?>
													<ol class="name-value" style="width: 100%;">
														<li>
															<label for="about">Condition</label>
															<span id="about"><?php echo $rowb2b["newUsed"] ?></span><br><br>
														</li>
														<li>
															<label for="Span1">Dimensions</label>
															<span id="Span1">
																<?php
																echo $dimension;
																?>
															</span><br><br>
														</li>
														<li>
															<label for="Span2">Walls Thick</label>
															<span id="Span2"><?php echo $wall_str; ?></span><br><br>
														</li>
														<li>
															<?php if ($rowb2b['burst'] == 'ECT') { ?>
																<label for="Span3">ECT</label>
																<span id="Span3"><?php echo $rowb2b['ect_val']; ?></span>
															<?php } else { ?>
																<label for="Span3">BURST</label>
																<span id="Span3"><?php echo $rowb2b['burst_val']; ?></span>
															<?php } ?>
															<br><br>
														</li>
														<li>
															<label for="Span4">Top</label>
															<span id="Span4"><?php echo $top; ?></span><br><br>
														</li>
														<li>
															<label for="Span5">Bottom</label>
															<span id="Span5"><?php echo $bottom; ?></span><br><br>
														</li>
														<li>
															<label for="Span8">Vents</label>
															<span id="Span8"><?php echo ($b2b_no_of_vents == "0") ? '0' : $b2b_no_of_vents; ?></span><br><br>
														</li>
														<li>
															<label for="Span9">Pallet Qty</label>
															<span id="Span9"><?php echo ($row["bpallet_qty"] == "0") ? '0' : number_format($row["bpallet_qty"]); ?><br></span><br><br>
														</li>
														<li>
															<label for="Span10">Truckload Qty</label>
															<span id="Span10"><?php echo ($row["boxes_per_trailer"] == "0") ? '' : number_format($row["boxes_per_trailer"]); ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span10">Frequency
																<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
																	<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
																</div>
															</label>
															<span id="Span10"><?php echo ($row["expected_loads_per_mo"] != "0" && $row["boxes_per_trailer"] != "0") ? number_format($row["expected_loads_per_mo"] * $row["boxes_per_trailer"]) . "/mo" : '0/mo'; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span11">Previous Use</label>
															<span id="Span11"><?php echo $row["previous_contents"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span12">Ideal Uses</label>
															<span id="Span12"><?php echo $row["ideal_uses"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span13">Notes</label>
															<span id="Span13"><?php echo (empty($row["flyer_notes"])) ? 'None' : $row["flyer_notes"]; ?></span><br><br>
														</li>

													</ol>
												<?php
												} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB")))) {
												?>
													<ol class="name-value" style="width: 100%;">
														<li>
															<label for="about">Condition</label>
															<span id="about"><?php echo $rowb2b["newUsed"] ?></span><br><br>
														</li>
														<li>
															<label for="Span1">Dimensions</label>
															<span id="Span1">
																<?php
																echo $dimension;
																?>
															</span><br><br>
														</li>
														<li>
															<label for="Span4">Top</label>
															<span id="Span4"><?php echo $top; ?></span><br><br>
														</li>
														<li>
															<label for="Span5">Bottom</label>
															<span id="Span5"><?php echo $bottom; ?></span><br><br>
														</li>
														<li>
															<label for="Span9">Bale Qty </label>
															<span id="Span9"><?php echo ($row["bpallet_qty"] == "0") ? '0' : number_format($row["bpallet_qty"]); ?><br></span><br><br>
														</li>
														<li>
															<label for="Span10">Truckload Qty</label>
															<span id="Span10"><?php echo ($row["boxes_per_trailer"] == "0") ? '' : number_format($row["boxes_per_trailer"]); ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span10">Frequency
																<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
																	<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
																</div>
															</label>
															<span id="Span10"><?php echo ($row["expected_loads_per_mo"] != "0" && $row["boxes_per_trailer"] != "0") ? number_format($row["expected_loads_per_mo"] * $row["boxes_per_trailer"]) . "/mo" : '0/mo'; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span11">Previous Use</label>
															<span id="Span11"><?php echo $row["previous_contents"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span12">Ideal Uses</label>
															<span id="Span12"><?php echo $row["ideal_uses"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span13">Notes</label>
															<span id="Span13"><?php echo (empty($row["flyer_notes"])) ? 'None' : $row["flyer_notes"]; ?></span><br><br>
														</li>

													</ol>
												<?php
												} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))) {
												?>
													<ol class="name-value" style="width: 100%;">
														<li>
															<label for="about">Condition</label>
															<span id="about"><?php echo $rowb2b["newUsed"] ?></span><br><br>
														</li>
														<li>
															<label for="Span1">Dimensions</label>
															<span id="Span1">
																<?php
																echo $dimension;
																?>
															</span>
														</li>
														<li>
															<label for="Span2">Grade</label>
															<span id="Span2"><?php echo $row['grade']; ?></span><br><br>
														</li>

														<li>
															<label for="Span10">Truckload Qty</label>
															<span id="Span10"><?php echo ($row["boxes_per_trailer"] == "0") ? '' : number_format($row["boxes_per_trailer"]); ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span10">Frequency
																<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
																	<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
																</div>
															</label>
															<span id="Span10"><?php echo ($row["expected_loads_per_mo"] != "0" && $row["boxes_per_trailer"] != "0") ? number_format($row["expected_loads_per_mo"] * $row["boxes_per_trailer"]) . "/mo" : '0/mo'; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span11">Previous Use</label>
															<span id="Span11"><?php echo $row["previous_contents"]; ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span12">Ideal Uses</label>
															<span id="Span12"><?php echo $row["ideal_uses"]; ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span13">Notes</label>
															<span id="Span13"><?php echo (empty($row["flyer_notes"])) ? 'None' : $row["flyer_notes"]; ?></span>
															<br><br>
														</li>

													</ol>
												<?php
												} elseif (in_array(strtolower(trim($box_type)), array_map('strtolower', array("Recycling", "DrumBarrelUCB", "DrumBarrelnonUCB", "Waste-to-Energy", "Other", " "))) || (strtolower(trim($box_type)) == strtolower(trim('Recycling')))) {
												?>
													<ol class="name-value" style="width: 100%;">
														<li>
															<label for="about">Condition</label>
															<span id="about"><?php echo $rowb2b["newUsed"] ?></span><br><br>
														</li>
														<li>
															<label for="Span10">Truckload Qty</label>
															<span id="Span10"><?php echo ($row["boxes_per_trailer"] == "0") ? '' : number_format($row["boxes_per_trailer"]); ?><br></span>
															<br><br>
														</li>
														<li>
															<label for="Span10">Frequency
																<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
																	<span class="tooltiptext">This is how many we anticipate on getting on a recurring monthly basis.</span>
																</div>
															</label>
															<span id="Span10"><?php echo ($row["expected_loads_per_mo"] != "0" && $row["boxes_per_trailer"] != "0") ? number_format($row["expected_loads_per_mo"] * $row["boxes_per_trailer"]) . "/mo" : '0/mo'; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span11">Previous Use</label>
															<span id="Span11"><?php echo $row["previous_contents"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span12">Ideal Uses</label>
															<span id="Span12"><?php echo $row["ideal_uses"]; ?><br></span><br><br>
														</li>
														<li>
															<label for="Span13">Notes</label>
															<span id="Span13"><?php echo (empty($row["flyer_notes"])) ? 'None' : $row["flyer_notes"]; ?></span><br><br>
														</li>

													</ol>
												<?php
												}
												?>


											</div>
											<div class="prod-gallery">
												<section id="detail">
													<div class="container">
														<div class="row">
															<div class="main_img">
																<?php $imgpath = "https://loops.usedcardboardboxes.com/boxpics/";
																$imgpath_internal = "../ucbloop/boxpics/";
																?>
																<div class="product-images demo-gallery">
																	<div class="main-img-slider">
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_1"])) { ?>
																			<a data-fancybox="gallery" href="<?php echo $imgpath . $row["bpic_1"] ?>" data-width="2048" data-height="1365">
																				<img src="<?php echo $imgpath . $row["bpic_1"] ?>" class="img-fluid">
																			</a>
																			<?php } else {
																			if ($row["bpic_2"] == "" && $row["bpic_3"] == "" && $row["bpic_4"] == "") {
																			?>
																				<img src="<?php echo $imgpath . "Base-image-blank.jpg" ?>" class="img-fluid">
																		<?php }
																		}
																		?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_2"])) { ?>
																			<a data-fancybox="gallery" href="<?php echo $imgpath . $row["bpic_2"] ?>" data-width="2048" data-height="1365">
																				<img src="<?php echo $imgpath . $row["bpic_2"] ?>" class="img-fluid">
																			</a>
																		<?php } ?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_3"])) { ?>
																			<a data-fancybox="gallery" href="<?php echo $imgpath . $row["bpic_3"] ?>" data-width="2048" data-height="1365">
																				<img src="<?php echo $imgpath . $row["bpic_3"] ?>" class="img-fluid">
																			</a>
																		<?php } ?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_4"])) { ?>
																			<a data-fancybox="gallery" href="<?php echo $imgpath . $row["bpic_4"] ?>" data-width="2048" data-height="1365">
																				<img src="<?php echo $imgpath . $row["bpic_4"] ?>" class="img-fluid">
																			</a>
																		<?php } ?>
																	</div>

																	<ul class="thumb-nav">
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_1"])) { ?>
																			<li><img src="<?php echo $imgpath . $row["bpic_1"] ?>" class="prod-pics-thumb"></li>
																		<?php } ?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_2"])) { ?>
																			<li><img src="<?php echo $imgpath . $row["bpic_2"] ?>" class="prod-pics-thumb"></li>
																		<?php } ?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_3"])) { ?>
																			<li><img src="<?php echo $imgpath . $row["bpic_3"] ?>" class="prod-pics-thumb"></li>
																		<?php } ?>
																		<?php if (@getimagesize($imgpath_internal . $row["bpic_4"])) { ?>
																			<li><img src="<?php echo $imgpath . $row["bpic_4"] ?>" class="prod-pics-thumb"></li>
																		<?php } ?>
																		<?php /* if (@getimagesize($imgpath_internal.$row["bpic_5"])){?>
														<li><img src="<?php echo$imgpath.$row["bpic_5"]?>" class="prod-pics-thumb"></li>
													<?php } */ ?>
																	</ul>

																</div>

															</div>
														</div>
													</div>
												</section>

											</div>
										</div>
									</div>
									<?php
									db_b2b();
									$resQuoteItems = db_query("SELECT quantity, quote_price FROM quote_to_item WHERE quote_id = " . $quote_id . " AND item_id = " . $arrItemIDVal);
									$rowQuoteItems = array_shift($resQuoteItems);
									$total = ($rowQuoteItems['quantity'] * $rowQuoteItems['quote_price'])
									?>

									<input type="hidden" id="productId" name="productIdloop[]" value="<?php echo $row["id"]; ?>">

									<!-- This value is 1/2/3/4 depends on full/half/quarter/1 pallet -->
									<input type="hidden" id="productQntypeid" name="productQntypeid[]" value="1">
									<!-- This value is full/half/quarter/1 pallet -->
									<input type="hidden" id="productQntype" name="productQntype[]" value="full">

									<input type="hidden" id="productQnt" name="productQnt[]" value="<?php echo $rowQuoteItems['quantity']; ?>">
									<input type="hidden" id="productQntprice" name="productQntprice[]" value="<?php echo $rowQuoteItems['quote_price']; ?>">
									<input type="hidden" id="productTotal" name="productTotal[]" value="<?php echo $total; ?>">
									<input type="hidden" id="hdAvailability" name="hdAvailability[]" value="<?php echo $estimated_next_load; ?>">
									<?php
									$arrItemID_cnt = $arrItemID_cnt + 1;
								}
								?>

								<input type="hidden" name="userid" value="<?php echo $_COOKIE["uid"] ?>">
								<input type="hidden" name="totalProd" value="<?php echo $arrItemID_cnt; ?>">
								<button type="button" onclick="javascript: formvalidate(<?php echo $arrItemID_cnt; ?>);" class="button_slide slide_right" data-testid="order-button">Order Now</button>
							</form>
						</div><!-- end of productinfo-section -->
					</div><!-- end of right-products  -->
				</div><!-- end of content-div content-padding -->

				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script src="product-slider/js/popper.min.js"></script>
				<!--<script src="product-slider/js/slick.min.js"></script>-->
				<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
				<script src="product-slider/js/jquery.fancybox.min.js"></script>
				<script id="rendered-js">
					/*--------------*/
					// Main/Product image slider for product page
					$('#detail .main-img-slider').slick({
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						arrows: false,
						fade: true,
						autoplay: false,
						/*
						autoplaySpeed: 4000,
						speed: 300,
						lazyLoad: 'ondemand',
						asNavFor: '.thumb-nav',
						prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable"><</span></div>',
						nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">></span></div>'
						*/
					});

					// Thumbnail/alternates slider for product page
					$('.thumb-nav').slick({
						slidesToShow: 4,
						slidesToScroll: 1,
						infinite: true,
						centerPadding: '0px',
						asNavFor: '.main-img-slider',
						dots: false,
						centerMode: false,
						draggable: true,
						speed: 200,
						focusOnSelect: true,
						prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable"><</span></div>',
						nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">></span></div>'
					});


					/*
					//keeps thumbnails active when changing main image, via mouse/touch drag/swipe
					$('.main-img-slider').on('afterChange', function (event, slick, currentSlide, nextSlide) {
					  //remove all active class
					  $('.thumb-nav .slick-slide').removeClass('slick-current');
					  //set active class for current slide
					  $('.thumb-nav .slick-slide:not(.slick-cloned)').eq(currentSlide).addClass('slick-current');
					});
					//# sourceURL=pen.js
					*/

					$('[data-fancybox="gallery"]').fancybox({
						afterLoad: function(instance, current) {
							var pixelRatio = window.devicePixelRatio || 1;

							if (pixelRatio > 1.5) {
								current.width = current.width / pixelRatio;
								current.height = current.height / pixelRatio;
							}
						}
					});
				</script>
				<!---->
				<div class="privacy-links_inner" style="margin-left: 30px;">
					<div class="bottomlinks">
						<div class="bottom-link"><a target="_blank" href="refund-policy.php">Refund Policy</a></div>
						<div class="bottom-link"><a target="_blank" href="shipping-policy.php">Shipping Policy</a></div>
						<div class="bottom-link"><a target="_blank" href="privacy-policy.php">Privacy Policy</a></div>
						<div class="bottom-link"><a target="_blank" href="terms-of-service.php">Terms of Service</a></div>
					</div>
				</div>
			</div>
		</div>

		</div>
		<div class="footer_l">
			<div class="copytxt">© UsedCardboardBoxes</div>
		</div>
	<?php
	}
	?>

	<?php
	if ($ship_ltl == 0) {
	?>
		<script>
			productinfo(1);
		</script>
	<?php }

	if ($product_name_id == 1) {
	?>
		<script>
			productinfo(1);
		</script>
	<?php
	}
	if ($product_name_id == 2) {
	?>
		<script>
			productinfo(2);
		</script>
	<?php
	}

	if ($product_name_id == 3) {
	?>
		<script>
			productinfo(3);
		</script>
	<?php

	}
	if ($product_name_id == 4) {

	?>
		<script>
			productinfo(4);
		</script>
	<?php
	}
	?>
	<script>
		//calculatedistance_ip('<?php echo $ipadd; ?>', <?php echo $_REQUEST['id']; ?>);		
		//calculatedistance_ip('38.73.241.221', <?php echo $_REQUEST['id']; ?>);		

		if (window.innerWidth <= 980) {
			function isVisible($el) {
				let docViewTop = $(window).scrollTop();
				let docViewBottom = docViewTop + $(window).height();
				let elTop = $el.offset().top;
				let elBottom = elTop + $el.height();
				return ((elBottom <= docViewBottom) && (elTop >= docViewTop));
			}
			$(function() {
				$(window).scroll(function() {

					if (isVisible($("#order-div"))) {
						$('#ordernow').fadeOut();
					} else {
						$('#ordernow').fadeIn();
					}
				});
			});
		}
	</script>
	</body>

	</html>