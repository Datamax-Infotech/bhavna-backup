<?php  
set_time_limit(0);	
ini_set('memory_limit', '-1');
require ("inc/header_session.php");
?>

<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

?>

<!DOCTYPE html>

<html>
<head>
	<title>DASH - Survey Response contact log</title>
	<link rel="stylesheet" type="text/css" href="one_style.css"> 

<body>
	<div>
	<?php  include("inc/header.php"); ?>
</div>
	<br>
<div class="main_data_css">
	<?php 
	if($_REQUEST['action'] == 'showNTC'){  
		if(isset($_REQUEST['btnNTC'])){
			$sqlUpdtSurveyRes = "UPDATE orders SET survey_res_initials = '".$_COOKIE['userinitials']."', survey_res_datetime ='".date("Y-m-d H:i:s")."', survey_res_flag = 1 WHERE orders_id = '".$_REQUEST['hdnOrderID']."'";
			db_query($sqlUpdtSurveyRes,db() );
		}
	?>
	<table WIDTH='100%'>
		<tr align='middle'>
			<td colspan='17' class='style24' style='height: 16px'>
				<strong>NEED TO CONTACT</strong>
			</td>
		</tr>
		<tr>
			<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Shopify Order ID</DIV></TD> 
			<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Payment Method</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer IP Address</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>

			<TD><DIV CLASS='TBL_COL_HDR'>Survey</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Notes</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Update Survey Response</DIV></TD>
		</tr>
		<?php 
		$sql = "SELECT survey_nps.order_id, survey_nps.nps, survey_nps.recommendation, orders.orders_id, orders.shopify_order_display_no, orders.shopify_order_no, orders.payment_method, orders.date_purchased, orders.user_ipaddress, orders.customers_name, orders.delivery_street_address, orders.delivery_street_address2, orders.delivery_city, orders.delivery_state, orders.delivery_postcode, orders.customers_telephone, orders.customers_email_address, orders.survey_res_initials, orders.survey_res_flag, orders.survey_res_datetime FROM orders left JOIN survey_nps ON survey_nps.order_id = orders.orders_id WHERE survey_nps.nps <= 7 AND YEAR(survey_nps.date) = ".$_REQUEST['year']." AND survey_nps.contactok = 'Y' AND orders.survey_res_flag = 0 ";
		//$sql = "SELECT survey_nps.order_id, survey_nps.nps, survey_nps.notes, orders.* FROM orders INNER JOIN survey_nps ON survey_nps.order_id = orders.orders_id WHERE survey_nps.nps <= 7 AND YEAR(survey_nps.date) = '".$_REQUEST['year']."' AND survey_nps.contactok = 'Y' ";
		$sqlResArr = db_query($sql, db());
	//echo "<pre>"; print_r($sqlResArr); echo "</pre>";
		while ($myrowsel = array_shift($sqlResArr)) {
			$order_amount = 0;
			$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " .$myrowsel['orders_id'];
			$t_sql_1_res = db_query($t_sql_1,db() );
			while ($t_sql_1_row = array_shift($t_sql_1_res)) {
				$order_amount = number_format($t_sql_1_row["value"],2);
			}
			
			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}//end switch shad			
		?>
			<tr>
				<TD CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="orders.php?id=<?php  echo $myrowsel['orders_id']; ?>&proc=View&searchcrit=&page=0">
					<?php  echo $myrowsel['orders_id']; ?>
					</a>
				</TD>
				<TD CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="https://usedcardboardboxes.myshopify.com/admin/orders/<?php  echo $myrowsel["shopify_order_no"]; ?>?orderListBeta=true">
					<?php  echo $myrowsel['shopify_order_display_no'];  ?>
					</a>	
				</TD> 
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $order_amount;  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  if ($myrowsel["payment_method"] != "Paypal"){
						echo "Credit Card";
					}else{
						echo "Paypal";
					} ?>				
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo date("F j, Y", strtotime($myrowsel["date_purchased"]));  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  
					$user_ipaddress = $myrowsel["user_ipaddress"];
					if ($user_ipaddress != ""){
						echo "<a href='https://whatismyipaddress.com/ip/" . $user_ipaddress . "' target='_blank'>" . $user_ipaddress . "</a>"; 
					}
					?>
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_name"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address2"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_city"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_state"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_postcode"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_telephone"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_email_address"]; ?></TD>

				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["nps"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["recommendation"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<form name="frmNTC" action="<?php  echo $_SERVER['PHP_SELF']."?action=showNTC&year=".$_REQUEST['year']; ?>" method="post">
						<input type="hidden" name="hdnOrderID" value="<?php  echo $myrowsel['orders_id']; ?>">
						<input type="submit" name="btnNTC" value="Contacted">
					</form>					
				</TD>
			</tr>

		<?php 
		}
		?>
	</table>
	<?php 
	}
	?>
	<?php 
	if($_REQUEST['action'] == 'showResp'){ 
	?>
	<table WIDTH='100%'>
		<tr align='middle'>
			<td colspan='17' class='style24' style='height: 16px'>
				<strong>Responses</strong>
			</td>
		</tr>
		<tr>
			<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Shopify Order ID</DIV></TD> 
			<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Payment Method</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer IP Address</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Survey</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Contacted By</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Contacted On</DIV></TD>
		</tr>
		<?php 
		$sql = "SELECT survey_nps.order_id, survey_nps.nps, survey_nps.recommendation, orders.orders_id, orders.shopify_order_display_no, orders.shopify_order_no, orders.payment_method, orders.date_purchased, orders.user_ipaddress, orders.customers_name, orders.delivery_street_address, orders.delivery_street_address2, orders.delivery_city, orders.delivery_state, orders.delivery_postcode, orders.customers_telephone, orders.customers_email_address, orders.survey_res_initials, orders.survey_res_flag, orders.survey_res_datetime FROM orders left JOIN survey_nps ON survey_nps.order_id = orders.orders_id WHERE survey_nps.nps <= 7 AND YEAR(survey_nps.date) = ".$_REQUEST['year']." AND orders.survey_res_flag = 1 ";
		$sqlResArr = db_query($sql, db());
		//echo "<pre>"; print_r($sqlResArr); echo "</pre>";
		while ($myrowsel = array_shift($sqlResArr)) {
			$order_amount = 0;
			$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " .$myrowsel['orders_id'];
			$t_sql_1_res = db_query($t_sql_1,db() );
			while ($t_sql_1_row = array_shift($t_sql_1_res)) {
				$order_amount = number_format($t_sql_1_row["value"],2);
			}

			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}//end switch shad			
			
		?>
			<tr>
				<td CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="orders.php?id=<?php  echo $myrowsel['orders_id']; ?>&proc=View&searchcrit=&page=0">
					<?php  echo $myrowsel['orders_id']; ?>
					</a>
				</TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="https://usedcardboardboxes.myshopify.com/admin/orders/<?php  echo $myrowsel["shopify_order_no"]; ?>?orderListBeta=true">
					<?php  echo $myrowsel['shopify_order_display_no'];  ?>
					</a>	
				</TD> 
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $order_amount;  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  if ($myrowsel["payment_method"] != "Paypal"){
						echo "Credit Card";
					}else{
						echo "Paypal";
					} ?>				
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo date("F j, Y", strtotime($myrowsel["date_purchased"]));  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  
					$user_ipaddress = $myrowsel["user_ipaddress"];
					if ($user_ipaddress != ""){
						echo "<a href='https://whatismyipaddress.com/ip/" . $user_ipaddress . "' target='_blank'>" . $user_ipaddress . "</a>"; 
					}
					?>
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_name"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address2"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_city"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_state"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_postcode"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_telephone"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_email_address"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["nps"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["survey_res_initials"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["survey_res_datetime"]; ?></TD>
			</tr>

		<?php 
		}
		?>
	</table>
	<?php 
	}
	?>
	<?php 
	if($_REQUEST['action'] == 'showEmailSent'){ 
	?>
	<table WIDTH='100%'>
		<tr align='middle'>
			<td colspan='15' class='style24' style='height: 16px'>
				<strong>Number of Survey Requests Emailed </strong>
			</td>
		</tr>
		<tr>
			<TD><DIV CLASS='TBL_COL_HDR'>Order ID</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Shopify Order ID</DIV></TD> 
			<TD><DIV CLASS='TBL_COL_HDR'>Order Amount</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Payment Method</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Order Date</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer IP Address</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Customer Name</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address One</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Address Two</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>City</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>State</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Zip Code</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Phone</DIV></TD>
			<TD><DIV CLASS='TBL_COL_HDR'>Email</DIV></TD>

			<TD><DIV CLASS='TBL_COL_HDR'>Survey Email Sent on</DIV></TD>
		</tr>
		<?php 
		$sql = "SELECT orders.orders_id, orders.shopify_order_display_no, orders.shopify_order_no, orders.payment_method, orders.date_purchased, orders.user_ipaddress, orders.customers_name, orders.delivery_street_address, orders.delivery_street_address2, orders.delivery_city, orders.delivery_state, orders.delivery_postcode, orders.customers_telephone, orders.customers_email_address, orders.survey_res_initials, orders.survey_res_flag, orders.survey_res_datetime, orders_survey_data_log.survey_sent_on FROM orders left JOIN orders_survey_data_log ON orders_survey_data_log.orders_id = orders.orders_id WHERE YEAR(orders_survey_data_log.survey_sent_on) = ".$_REQUEST['year']." AND orders.survey = 1 ";
		$sqlResArr = db_query($sql, db());
		while ($myrowsel = array_shift($sqlResArr)) {
			$order_amount = 0;
			$t_sql_1 = "SELECT value FROM orders_total WHERE class = 'ot_total' and orders_id = " .$myrowsel['orders_id'];
			$t_sql_1_res = db_query($t_sql_1,db() );
			while ($t_sql_1_row = array_shift($t_sql_1_res)) {
				$order_amount = number_format($t_sql_1_row["value"],2);
			}
			
			switch ($shade)
  			{
  				case "TBL_ROW_DATA_LIGHT":
					$shade = "TBL_ROW_DATA_DRK";
					break;
				case "TBL_ROW_DATA_DRK":
					$shade = "TBL_ROW_DATA_LIGHT";
					break;
				default:
					$shade = "TBL_ROW_DATA_DRK";
					break;
  			}//end switch shad			
			
		?>
			<tr>
				<td CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="orders.php?id=<?php  echo $myrowsel['orders_id']; ?>&proc=View&searchcrit=&page=0">
					<?php  echo $myrowsel['orders_id']; ?>
					</a>
				</TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<a target="_blank" href="https://usedcardboardboxes.myshopify.com/admin/orders/<?php  echo $myrowsel["shopify_order_no"]; ?>?orderListBeta=true">
					<?php  echo $myrowsel['shopify_order_display_no'];  ?>
					</a>	
				</TD> 
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $order_amount;  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  if ($myrowsel["payment_method"] != "Paypal"){
						echo "Credit Card";
					}else{
						echo "Paypal";
					} ?>				
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo date("F j, Y", strtotime($myrowsel["date_purchased"]));  ?></TD>
				<td CLASS='<?php  echo $shade; ?>'>
					<?php  
					$user_ipaddress = $myrowsel["user_ipaddress"];
					if ($user_ipaddress != ""){
						echo "<a href='https://whatismyipaddress.com/ip/" . $user_ipaddress . "' target='_blank'>" . $user_ipaddress . "</a>"; 
					}
					?>
				</TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_name"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_street_address2"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_city"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_state"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["delivery_postcode"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_telephone"]; ?></TD>
				<td CLASS='<?php  echo $shade; ?>'><?php  echo $myrowsel["customers_email_address"]; ?></TD>
				
				<td CLASS='<?php  echo $shade; ?>'><?php  echo !empty($myrowsel['survey_sent_on'])? date("m/d/Y", strtotime($myrowsel['survey_sent_on'])):'';; ?></TD>
			</tr>

		<?php 
		}
		?>
	</table>
	<?php 
	}
	?>
</div>


</body>
</html>