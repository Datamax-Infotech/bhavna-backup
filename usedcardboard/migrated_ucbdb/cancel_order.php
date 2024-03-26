<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$get_order_id = decrypt_url($_REQUEST['orders_id']);
if ($_GET['process'] != 'action') {
?>
	<P>
		<font face="arial" size="2">Are you sure you want to process this cancellation?
			<br><br>
			<a href="cancel_order.php?orders_id=<?php echo encrypt_url($get_order_id); ?>&process=action">Yes</a>
			<br><br>
			<a href="javascript: history.go(-1)">No</a>

			<?php  } ?><?php
						if ($_GET['process'] == 'action') {
							$auth_trans_id = "";
							$ord2 = "SELECT * FROM auth_net WHERE orders_id = " . $get_order_id;
							$ord2_result = db_query($ord2);
							while ($ord2_roblem_all_result_array = array_shift($ord2_result)) {
								$auth_trans_id = $ord2_roblem_all_result_array["trans_id"];
							}

							$ord2 = "SELECT * FROM orders_active_export WHERE orders_id = " . $get_order_id;
							$ord2_result = db_query($ord2);
							$ord2_result_rows = tep_db_num_rows($ord2_result);
							if ($ord2_result_rows != 0) {
								echo "<font face=\"arial\" size=\"2\">Unfortunately, the labels have already been printed for this order.  Please contact the following warehouses and request that the labels with the following tracking numbers are pulled:";


								echo "<table border=\"1\" cellspacing=\"4\">";
								echo "    <thead>";
								echo "        <tr>";
								echo "            <th><font face=\"arial\" size=\"2\">Module Name</th>";
								echo "            <th><font face=\"arial\" size=\"2\">Warehouse</th>";
								echo "            <th><font face=\"arial\" size=\"2\">Tracking Number</th>";
								echo "        </tr>";
								echo "  </thead>";
							}

							while ($ord2_roblem_all_result_array = array_shift($ord2_result)) {

								if ($ord2_result_rows != 0) {
									$wh_qry = "SELECT * FROM warehouse WHERE warehouse_id = " . $ord2_roblem_all_result_array['warehouse_id'];
									$wh_qry_result = db_query($wh_qry);
									$wh_name = "";
									while ($wh_qry_result_array = array_shift($wh_qry_result)) {
										$wh_name = $wh_qry_result_array['name'];
									}

									echo "  <tbody>";
									echo "  <tr>";
									echo "  <td><font face=\"arial\" size=\"2\">" . $ord2_roblem_all_result_array['module_name'] . "</td>";
									echo "  <td><font face=\"arial\" size=\"2\">" . $wh_name . "</td>";
									echo "  <td><font face=\"arial\" size=\"2\">" . $ord2_roblem_all_result_array['tracking_number'] . "</td>";
									echo "  </tr>";
									echo "  </tbody>";
								}
							}

							echo "  </table>";


							$arr_warehouse = array('orders_active_ucb_evansville', 'orders_active_ucb_hannibal', 'orders_active_ucb_hunt_valley', 'orders_active_ucb_los_angeles', 'orders_active_ucb_rochester', 'orders_active_ucb_salt_lake', 'orders_active_ucb_atlanta', 'orders_active_ucb_dallas', 'orders_active_ucb_danville', 'orders_active_ucb_iowa', 'orders_active_ucb_montreal', 'orders_active_ucb_toronto', 'orders_active_ucb_philadelphia');
							foreach ($arr_warehouse as $tbl_warehouse) {
								if ($ord2_result_rows == 0) {
									$query = "DELETE FROM " . $tbl_warehouse . " WHERE orders_id = " . $get_order_id;
									$result = db_query($query);
								}

								$query = "Update orders set fedex_validate_bad_add = 0, `cancel` = 'Yes', cancel_order_by = '" . $_COOKIE['userinitials'] . "', cancel_order_on = '" . date("Y-m-d H:i:s") . "' where orders_id = " . $get_order_id;
								$result = db_query($query);

								if (empty($result)) {
									$query_ins = "INSERT into orders_total_cancel_order Select * from orders_total WHERE orders_id = " . $get_order_id;
									$result_ins = db_query($query_ins);

									if (empty($result_ins)) {
										$query_ins = "Delete from orders_total WHERE orders_id = " . $get_order_id;
										$result_ins = db_query($query_ins);
									}

									$query_ins = "Insert into gift_certificate_to_orders_cancel_order Select * from gift_certificate_to_orders WHERE orders_id = " . $get_order_id;
									$result_ins = db_query($query_ins);

									if (empty($result_ins)) {
										$query_ins = "Delete from gift_certificate_to_orders WHERE orders_id = " . $get_order_id;
										$result_ins = db_query($query_ins);
									}
								}
							}

							if ($auth_trans_id != "") { ?>
			<h4>B2C - Authorize.net - Void Transaction</h4>

			<iframe width="600px" height="200px" id="b2c_cancel_order" id="b2c_cancel_order" src="https://loops.usedcardboardboxes.com/b2bauthorizenet/b2c_cancel_order.php?auth_trans_id=<?php echo $auth_trans_id; ?>">
			</iframe>
			<br>
	<?php
							}
							$today = date("Ymd");

							if ($ord2_result_rows == 0) {
								$output = "<STRONG>ORDER CANCELLED</STRONG><br><br>";
								$output .= "Labels Removed from Warehouse Important Tables and Transaction Voided in Authorize.net.";

								$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
								$res_commqryrw = db_query($commqry);
								$commqryrw = array_shift($res_commqryrw);
								$comm_type = $commqryrw["id"];

								$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $get_order_id . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
								// echo "<BR>SQL: $sql3<BR>";
								$result3 = db_query($sql3);
								echo "<font face=\"arial\" size=\"2\">Order cancelled and Labels have been removed.";
							}

							if ($ord2_result_rows != 0) {
								$today = date("Ymd");
								$output = "<STRONG>ORDER CANCELLED</STRONG><br><br>";
								$output .= "Labels Already Printed and UCB CSR contacted Warehouse. ";
								$output .= "Labels Removed from Warehouse Important Tables and Transaction Voided in Authorize.net.";

								$commqry = "SELECT * FROM ucbdb_customer_log_config WHERE comm_type='System'";
								$res_commqryrw = db_query($commqry);
								$commqryrw = array_shift($res_commqryrw);
								$comm_type = $commqryrw["id"];

								$sql3 = "INSERT INTO ucbdb_crm (orders_id, comm_type, message, message_date, employee) VALUES ( '" . $get_order_id . "','" . $comm_type . "','" . $output . "','" . $today . "','" . $_COOKIE['userinitials'] . "')";
								// echo "<BR>SQL: $sql3<BR>";
								$result3 = db_query($sql3);
							}


							$sps_qry = "SELECT * FROM orders_sps WHERE orders_id = " . $get_order_id;
							$sps_qry_result = db_query($sps_qry);
							$sps_qry_result_rows = tep_db_num_rows($sps_qry_result);
							if ($sps_qry_result_rows > 0) {
								echo "<br><br><font face=\"arial\" size=\"2\">SPS Products also exist. Please contact Therasa and have her cancel the shippment.  ";
							}


							echo "<font face=\"arial\" size=\"2\"><br><br><a href=\"orders.php?id=" . encrypt_url($get_order_id) . "&proc=View\">Return to Order</a>";
						}
	?>