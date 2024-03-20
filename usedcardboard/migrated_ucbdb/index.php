<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
//QTD and QTD1
db();
function getCurrentQuarter(?int $timestamp = null): float
{
	if (!$timestamp)
		$timestamp = time();
	$day = date('n', $timestamp);
	$quarter = ceil($day / 3);
	return $quarter;
}

function getPreviousQuarter(?int $timestamp = null): float
{
	if (!$timestamp)
		$timestamp = time();
	//$quarter = getCurrentQuarter($timestamp) - 1;
	$quarter = getCurrentQuarter($timestamp);
	if ($quarter < 0) {
		$quarter = 4;
	}
	return $quarter;
}

?>
<!DOCTYPE HTML>
<html>

<head>
	<title>DASH - Home</title>
	<meta http-equiv="refresh" content="600">
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" as="style" onload="this.rel='stylesheet'">
	<link rel="preload" href="https://loops.usedcardboardboxes.com/css/tooltip_style.css" as="style" onload="this.rel='stylesheet'">
	<LINK rel="preload" type="text/css" href="one_style.css" as="style" onload="this.rel='stylesheet'">
	<script>
		function eod_popup(warehousetbl) {
			document.getElementById("hd_warehouse").value = warehousetbl;

			document.getElementById("light").innerHTML = "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" + document.getElementById("diveod").innerHTML;
			document.getElementById('light').style.display = 'block';

		}

		function FormCheck() {
			if (document.ContactForm.searchcrit.value == "") {
				alert("A Tracking Number must be entered in this field.  Please try again.  Thank you.");
				return false;
			}
		}
	</script>
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
			/*white-space: nowrap;*/
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
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

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}

		.black_overlay {
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none;
			position: absolute;
			top: 5%;
			left: 10%;
			width: 40%;
			height: 40%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}
	</style>
</head>

<body>
	<div id="light" class="white_content"></div>
	<div id="fade" class="black_overlay"></div>
	<div>
		<?php include("inc/header.php"); ?>
	</div>
	<div class="main_data_css">
		<p align="center">&nbsp;&nbsp;<strong>
				<font face="Arial" Size="3">UsedCardboardBoxes.com Company Dashboard</font>
			</strong></p><br><br>
		<table border="0" cellpadding="5" cellspacing="2" width="80%" align="center">
			<tr>
				<td valign="top">
					<!-- this file is used to get data of First Column i.e. NPS, REVENUE, ORDER TRACKING, CONTACT ISSUES, PENDING CREDITS, EOD STATUS -->
					<?php require_once('index_nps_revenue_order_issue_credit_eod.php'); ?>
				</td>
				<td valign="top">
					<!-- This file is used to get data of second column i.e. Order Issues, Bad Address List, PENDING SHIPMENTS, ORDERS which are not added in Label data, Survey Responses, MOVING.COM, UPS DUPLICATE CHECK,RETURN STATUS-->
					<?php //require_once('index_order_bad_address_shipment_survey_moving_duplicate_check_return.php'); ?>
				</td>
				<td valign="top">
					<!-- This file is used to get data of 3rd Column i.e REPORTS, LINKS, B2C FARMING SYSTEM, FILE CABINET, AFFILIATES, HOLIDAY MESSAGE, SOPS, ADMINISTRATION,Master Pin,
					Email Configuration, Zopim Chat, B2C Google Address Flag, CONFIGURATION, SUPPORT TICKET SYSTEM, COOKIE CLEANER, LOGOUT -->
					<?php //require_once("index_reports_link_farming_system_file_cabinate_affiliate_holiday_message.php");?>
				</td>
			</tr>
		</table>
	</div>
	<script>
		function onchgemlgrp(selectedgrpval) {

			if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else { // code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}

			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("emailgrpdiv").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "getemailgrplist.php?selectedgrpid=" + selectedgrpval, true);
			xmlhttp.send();
		}
	</script>
</body>

</html>