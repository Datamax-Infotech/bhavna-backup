<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
function encrypt_password($txt)
{
	$key = '1sw54@$sa$offj';
	$ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
	$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
	return $ciphertext;
}

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Boomerang Portal Setup</title>
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
			left: 100%;
			/* To the right of the tooltip */
			margin-top: -5px;
			border-width: 5px;
			border-style: solid;
			border-color: transparent transparent transparent black;
		}

		.tooltip_right:hover .tooltiptext_right {
			visibility: visible;
		}

		/*--------*/

		.fa-info-circle {
			font-size: 9px;
			color: #767676;
		}

		.white_content {
			display: none;
			position: absolute;
			padding: 5px;
			border: 2px solid black;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}

		.textbox-label {
			background: transperant;
			border: none;
			width: 300px;
			min-width: 90px;
			max-width: 300px;
			transition: width 0.25s;
		}

		.color_red {
			color: red;
		}

		.hide_error {
			display: none;
		}

		.table_boomerang_portal {
			width: 85%;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12;
			border: none;
			background-color: #F6F8E5;
			margin: 0px auto;
		}

		.align_center {
			text-align: center;
		}

		.bg_C1C1C1 {
			background-color: #C1C1C1;
		}

		.tbl_border,
		.tbl_border td,
		.tbl_border tr {
			border: solid 1px #C8C8C8;
			border-collapse: collapse;
		}
	</style>
	<LINK rel='stylesheet' type='text/css' href='one_style.css'>
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<? include("inc/header.php"); ?>
<div class="main_data_css">
	<div style="height: 13px;">&nbsp;</div>
	<div style="border-bottom: 1px solid #C8C8C8; padding-bottom: 10px;">
		<img src="images/boomerang-logo.jpg" alt="moving boxes"> &nbsp;&nbsp; &nbsp;&nbsp;
		<!--<a href="viewCompany.php?ID=<?= $ID ?>">View B2B page</a> &nbsp;&nbsp;
			<a target="_blank" href="https://clientold.usedcardboardboxes.com/client_dashboard.php?compnewid=<?= $ID ?>&repchk=yes">Old Client dash</a> &nbsp;&nbsp; -->
		<a target="_blank" href="https://www.ucbdata.com/ucbloop/boomerang_business/client_dashboard_new.php">View Boomerang Portal</a>
		<span class="color_red">*Do NOT give this link out to customers! It is a "back door" to the portal ONLY FOR YOU!</span>
	</div>
	<div id="light" class="white_content"> </div>
	<table class="table_boomerang_portal">
		<tr>
			<td colspan="6" width="320px" style="background:#E8EEA8; text-align:center"><strong>Boomerang Portal User Setup</strong></font>
			</td>
		</tr>
		<tr>
			<td colspan="6" class="bg_C1C1C1 align_center">Add new user for Customer</td>
		</tr>
		<tr>
			<td colspan="6">
				<form class="save_user">
					<table>
						<tr>
							<td>Name: </td>
							<td colspan="5">
								<input type="text" name="user_name" value="" />
								<span class="color_red hide_error user_name_error">Name can't be blank!</span>
							</td>
						</tr>
						<tr>
							<td>Email: </td>
							<td colspan="5">
								<input type="email" name="user_email" id="user_email_add" value="" onblur="checkDuplicate_email('add_user')"/>
								<spanc id="user_email_add_error" class="color_red hide_error useremail_error" >Email can't be blank!</spanc>
							</td>
						</tr>
						<tr>
							<td>Password: </td>
							<td colspan="5">
								<input type="password" name="user_password" value="" />
								<span class="color_red hide_error user_password_error">Password can't be blank!</span>
							</td>
						</tr>
						<tr>
							<td>Select Companies: &nbsp; &nbsp;</td>
							<td colspan="5">
								<select multiple name="companies[]" id="all_companies">
									<option>Select Companies</option>
									<?php
									db_b2b();
									$select_sales_comp = db_query("SELECT ID, company FROM companyInfo where haveNeed = 'Have Boxes' && company!='' ORDER BY company");
									while ($row = array_shift($select_sales_comp)) {
										echo "<option value='" . $row['ID'] . "'>" . $row['company'] . "</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td colspan="5">
								<input type="hidden" name="form_action" value="add_user" class="cls_form_action" />
								<input type="submit" value="Save" />
							</td>
						</tr>
					</table>

				</form>
			</td>
		</tr>
		<tr>
			<td colspan="6"></td>
		</tr>
		<tr>
			<td colspan="6" class="bg_C1C1C1 align_center">User List</td>
		</tr>
		<tr>
			<td colspan="6">
				<table class="tbl_border" style="width: 100%">
					<tr>
						<td>Name</td>
						<td>Email</td>
						<td>Company</td>
						<td>Status</td>
						<td>Blocked</td>
						<td>Action</td>
					</tr>
					<?php
					db();
					$select_users = db_query("SELECT * FROM boomerang_usermaster where user_status = 1 ORDER BY loginid DESC");
					if (tep_db_num_rows($select_users) > 0) {
						while ($row = array_shift($select_users)) {
							$select_user_companies = db_query("SELECT company_id FROM boomerang_user_companies where user_id = '" . $row['loginid'] . "'", db());
							$company_list = "";
							if (tep_db_num_rows($select_user_companies) > 0) {
								while ($row1 = array_shift($select_user_companies)) {
									db_b2b();
									$company_name = db_query("SELECT company FROM companyInfo where ID = '" . $row1['company_id'] . "'");
									$company_name = array_shift($company_name);
									$company_list .= $company_name['company'] . "<br>";
								}
							}
							echo "<tr id='userrowid_" . $row['loginid'] . "'>
									<td>" . $row['user_name'] . "</td>
									<td>" . $row['user_email'] . "</td>
									<td>" . $company_list . "</td>
									<td>" . ($row['activate_deactivate'] == 1 ? 'Active' : 'Deactive') . "</td>
									<td>" . ($row['user_block'] == 0 ? 'Unblocked' : 'Blocked') . "</td>
									<td>
										<button type='button' user_id='" . $row['loginid'] . "' class='edit_user'>Edit</button>
										<button type='button' user_id='" . $row['loginid'] . "' class='delete_user'>Delete</button>
										<button type='button'><a href='boomerang_users_profile.php?user_id=".$row['loginid']."'>View</a></button>
									</td>	
									</tr>";
						}
					} else {
						echo "<tr><td class='color_red align_center' colspan='6'>No records found</td></tr>";
					}
					?>
					</table>
			</td>
	</table>

	<script>
		async function checkDuplicate_email(input_form_action) {
			console.log(input_form_action);
			var action_id =  (input_form_action == "add_user" ? "user_email_add" : "user_email_update");
			user_email = $("#"+action_id).val();
			//var user_email = $("#user_email").val();
			var res = 2;
			console.log(("#"+action_id+"_error"));
			$.ajax({
				url: 'boomerang_users_action.php',
				data: {
					user_email,
					form_action: 'check_duplicate_email'
				},
				method: "post",
				async: false,
				success: function(response) {
					if (response > 1 && input_form_action == 'update_user') {
						$("#"+action_id+"_error").removeClass('hide_error');
						$("#"+action_id+"_error").text('Email already exists!');
						res = 0;
					} else if (response == 1 && input_form_action == 'add_user') {
						$("#"+action_id+"_error").removeClass('hide_error');
						$("#"+action_id+"_error").text('Email already exists!');
						res = 0;
					} else {
						$("#"+action_id+"_error").addClass('hide_error');
						$("#"+action_id+"_error").text("Email can't be blank!");
						res = 1;
					}
				}
			});
			return res;
		}
		$(document).ready(function() {
			$('body').on('submit', ".save_user", async function(event) {
				event.preventDefault();
				var input_form_action = $(this).find('.cls_form_action').val();
				var username = $(this).find("input[name='user_name']").val();
				var useremail = $(this).find("input[name='user_email']").val();
				var user_password = $(this).find("input[name='user_password']").val();
				var flag = true;
				if (username == "") {
					$(this).find('.user_name_error').removeClass('hide_error');
					flag = false;
				} else {
					$(this).find('.user_name_error').addClass('hide_error');
				}
				if (useremail == "") {
					$(this).find('.useremail_error').removeClass('hide_error');
					flag = false;
				} else {
					$(this).find('.useremail_error').addClass('hide_error');
				}
				if (user_password == "") {
					flag = false;
					$(this).find('.user_password_error').removeClass('hide_error');
				}else{
					$(this).find('.user_password_error').addClass('hide_error');
				}
				var duplicate_email = await checkDuplicate_email(input_form_action);
				console.log("Duplicate Email "+duplicate_email);
				if (flag == true && duplicate_email == 1) {
					var all_data = new FormData(this);
					$.ajax({
						url: 'boomerang_users_action.php',
						data: all_data,
						method: "post",
						processData: false,
						contentType: false,
						success: function(response) {
							console.log(response);
							console.log(input_form_action);
							if (response == 1) {
								if (input_form_action == 'add_user') {
									alert('User added successfully');
								} else {
									alert('User updated successfully');
								}
								location.reload();
							} else {
								alert('Something went wrong, try again later');
							}
						}
					})
				}
				return false;
			});

			$('body').on('click', '.edit_user', function() {
				var user_id = $(this).attr('user_id');
				$.ajax({
					url: 'boomerang_users_action.php',
					data: {
						user_id,
						form_action: 'get_edit_user_data'
					},
					method: "post",
					type: 'json',
					success: function(response) {
						//console.log(response);
						var data = JSON.parse(response);
						var all_companies_dp = $('#all_companies').html();
						var company_list = data.company_list;
						// Create an array of company IDs from the company_list
						var company_ids = company_list.map(function(company) {
							return company;
						});
						// Add the 'selected' attribute to the options that match the company IDs
						all_companies_dp = all_companies_dp.replace(/<option value="(\d+)">/g, function(match, id) {
							return '<option value="' + id + '"' + (company_ids.includes(Number(id)) ? ' selected' : '') + '>';
						});

						var edit_html = `<td colspan="6"><form class="save_user"><table>
						<td>
							<input type="text" name="user_name" value="${data.user_name}">
							<br><span class="color_red hide_error user_name_error">Name can't be blank!</span>
						</td>
						<td>
							<input type="email" name="user_email" id="user_email_update" value="${data.user_email}" onblur="checkDuplicate_email('update_user')">
							<span id="user_email_update_error" class="color_red hide_error useremail_error" >Email can't be blank!</span>
							<input type="password" name="user_password" value="${data.user_password}">
							<span class="color_red hide_error user_password_error">Password can't be blank!</span>
						</td>
						<td><select multiple name="companies[]">${all_companies_dp}</select></td>
						<td><select name="activate_deactivate"><option></option><option value="1" ${data.activate_deactivate == 1 ? "selected" : ""}>Active</option><option value="0" ${data.activate_deactivate == 0 ? "selected" : ""}>Deactive</option></select></td>
						<td><select name="user_block"><option></option><option value="1" ${data.user_block == 1 ? "selected" : ""}>Block</option><option value="0" ${data.user_block == 0 ? "selected" : ""}>Unblock</option></select></td>
						<td>
							<input type="hidden" name="form_action" value="update_user" class="cls_form_action"> 
							<input type="hidden" name="user_id" id="user_id" value="${user_id}">
							<input type="submit" value="Update">
							<button type="button" class="cancel_edit">Cancel</button>
						</td></tr></table></form></td>`;
						$('#userrowid_' + user_id).html(edit_html);
						//$('#userrowid_' + user_id + ' td').wrap("<form class='save_user'></form>");
					}
				})
			});

			$('body').on('click', '.cancel_edit', function() {
				location.reload();
			});

			$('body').on('click', ".delete_user", function() {
				var user_id = $(this).attr('user_id');
				alert("Do you sure want to delete this user?");
				$.ajax({
					url: 'boomerang_users_action.php',
					data: {
						user_id,
						form_action: 'delete_user'
					},
					method: "post",
					type: 'json',
					success: function(response) {
						if (response == 1) {
							alert('User deleted successfully');
							location.reload();
						} else {
							alert("Something went wrong, try again later")
						}
					}
				});
			})


		});
	</script>