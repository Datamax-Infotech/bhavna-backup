<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<form method="post" name="frm_order_shipping_update" action="order_shipping_update.php">
	<?php
	$dt_view_qry1 = "SELECT * from orders where orders_id = " . decrypt_url($_REQUEST["tmp_orderid"]);
	$dt_view_res1 = db_query($dt_view_qry1);
	while ($myrowsel = array_shift($dt_view_res1)) {
		$delivery_name = $myrowsel["delivery_name"];
		$delivery_company = $myrowsel["delivery_company"];
		$delivery_apartment_no = $myrowsel["delivery_apartment_no"];
		$delivery_street_address = $myrowsel["delivery_street_address"];
		$delivery_street_address2 = $myrowsel["delivery_street_address2"];
		$delivery_suburb = $myrowsel["delivery_suburb"];
		$delivery_city = $myrowsel["delivery_city"];
		$delivery_postcode = $myrowsel["delivery_postcode"];
		$delivery_state = $myrowsel["delivery_state"];
		$delivery_country = $myrowsel["delivery_country"];
		$customers_telephone = $myrowsel["customers_telephone"];
		$customers_email_address = $myrowsel["customers_email_address"];
		$ups_notes = $myrowsel["comment"];
	?>

		<input type="hidden" name="tmp_orderid" id="tmp_orderid" value="<?php echo decrypt_url($_REQUEST["tmp_orderid"]); ?>" />
		<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
			<tr align="middle">
				<td bgColor="#c0cdda" colSpan="2">
					<span class="style1">CUSTOMER</span>
					<font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
						SHIPPING DETAILS&nbsp;
					</font>
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">Name
				</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_name" id="order_name" value="<?php echo $delivery_name; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">Company Name
				</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_company" id="order_company" value="<?php echo $delivery_company; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">Address</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_add1" id="order_add1" value="<?php echo $delivery_street_address; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="10" style="width: 100px" class="style1">Address 2</td>
				<td align="left" height="10" style="width: 235px" class="style1">
					<input type="text" name="order_add2" id="order_add2" value="<?php echo $delivery_street_address2; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">City</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_city" id="order_city" value="<?php echo $delivery_city; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="10" style="width: 100px" class="style1">State</td>
				<td align="left" height="10" style="width: 235px" class="style1">
					<!-- <input type="text" name="order_state" id="order_state" value="<?php echo $delivery_state; ?>"/> -->

					<select name="order_state" id="order_state">
						<option value=""></option>
						<option value="AL" <?php if (isset($delivery_state) && ($delivery_state == "AL" || $delivery_state == "Alabama")) {
												print(" selected=\"selected\"");
											} ?>>Alabama</option>
						<option value="AK" <?php if (isset($delivery_state) && ($delivery_state == "AK" || $delivery_state == "Alaska")) {
												print(" selected=\"selected\"");
											} ?>>Alaska</option>
						<option value="AZ" <?php if (isset($delivery_state) && ($delivery_state == "AZ" || $delivery_state == "Arizona")) {
												print(" selected=\"selected\"");
											} ?>>Arizona</option>
						<option value="AR" <?php if (isset($delivery_state) && ($delivery_state == "AR" || $delivery_state == "Arkansas")) {
												print(" selected=\"selected\"");
											} ?>>Arkansas</option>
						<option value="CA" <?php if (isset($delivery_state) && ($delivery_state == "CA" || $delivery_state == "California")) {
												print(" selected=\"selected\"");
											} ?>>California</option>
						<option value="CO" <?php if (isset($delivery_state) && ($delivery_state == "CO" || $delivery_state == "Colorado")) {
												print(" selected=\"selected\"");
											} ?>>Colorado</option>
						<option value="CT" <?php if (isset($delivery_state) && ($delivery_state == "CT" || $delivery_state == "Connecticut")) {
												print(" selected=\"selected\"");
											} ?>>Connecticut</option>
						<option value="DE" <?php if (isset($delivery_state) && ($delivery_state == "DE" || $delivery_state == "Delaware")) {
												print(" selected=\"selected\"");
											} ?>>Delaware</option>
						<option value="DC" <?php if (isset($delivery_state) && $delivery_state == "District of Columbia") {
												print(" selected=\"selected\"");
											} ?>>District of Columbia</option>
						<option value="FL" <?php if (isset($delivery_state) && ($delivery_state == "FL" || $delivery_state == "Florida")) {
												print(" selected=\"selected\"");
											} ?>>Florida</option>
						<option value="GA" <?php if (isset($delivery_state) && ($delivery_state == "GA" || $delivery_state == "Georgia")) {
												print(" selected=\"selected\"");
											} ?>>Georgia</option>
						<option value="HI" <?php if (isset($delivery_state) && ($delivery_state == "HI" || $delivery_state == "Hawaii")) {
												print(" selected=\"selected\"");
											} ?>>Hawaii</option>
						<option value="ID" <?php if (isset($delivery_state) && ($delivery_state == "ID" || $delivery_state == "Idaho")) {
												print(" selected=\"selected\"");
											} ?>>Idaho</option>
						<option value="IL" <?php if (isset($delivery_state) && ($delivery_state == "IL" || $delivery_state == "Illinois")) {
												print(" selected=\"selected\"");
											} ?>>Illinois</option>
						<option value="IN" <?php if (isset($delivery_state) && ($delivery_state == "IN" || $delivery_state == "Indiana")) {
												print(" selected=\"selected\"");
											} ?>>Indiana</option>
						<option value="IA" <?php if (isset($delivery_state) && ($delivery_state == "IA" || $delivery_state == "Iowa")) {
												print(" selected=\"selected\"");
											} ?>>Iowa</option>
						<option value="KS" <?php if (isset($delivery_state) && ($delivery_state == "KS" || $delivery_state == "Kansas")) {
												print(" selected=\"selected\"");
											} ?>>Kansas</option>
						<option value="KY" <?php if (isset($delivery_state) && ($delivery_state == "KY" || $delivery_state == "Kentucky")) {
												print(" selected=\"selected\"");
											} ?>>Kentucky</option>
						<option value="LA" <?php if (isset($delivery_state) && ($delivery_state == "LA" || $delivery_state == "Louisiana")) {
												print(" selected=\"selected\"");
											} ?>>Louisiana</option>
						<option value="ME" <?php if (isset($delivery_state) && ($delivery_state == "ME" || $delivery_state == "Maine")) {
												print(" selected=\"selected\"");
											} ?>>Maine</option>
						<option value="MD" <?php if (isset($delivery_state) && ($delivery_state == "MD" || $delivery_state == "Maryland")) {
												print(" selected=\"selected\"");
											} ?>>Maryland</option>
						<option value="MA" <?php if (isset($delivery_state) && ($delivery_state == "MA" || $delivery_state == "Massachusetts")) {
												print(" selected=\"selected\"");
											} ?>>Massachusetts</option>
						<option value="MI" <?php if (isset($delivery_state) && ($delivery_state == "MI" || $delivery_state == "Michigan")) {
												print(" selected=\"selected\"");
											} ?>>Michigan</option>
						<option value="MN" <?php if (isset($delivery_state) && ($delivery_state == "MN" || $delivery_state == "Minnesota")) {
												print(" selected=\"selected\"");
											} ?>>Minnesota</option>
						<option value="MS" <?php if (isset($delivery_state) && ($delivery_state == "MS" || $delivery_state == "Mississippi")) {
												print(" selected=\"selected\"");
											} ?>>Mississippi</option>
						<option value="MO" <?php if (isset($delivery_state) && ($delivery_state == "MO" || $delivery_state == "Missouri")) {
												print(" selected=\"selected\"");
											} ?>>Missouri</option>
						<option value="MT" <?php if (isset($delivery_state) && ($delivery_state == "MT" || $delivery_state == "Montana")) {
												print(" selected=\"selected\"");
											} ?>>Montana</option>
						<option value="NE" <?php if (isset($delivery_state) && ($delivery_state == "NE" || $delivery_state == "Nebraska")) {
												print(" selected=\"selected\"");
											} ?>>Nebraska</option>
						<option value="NV" <?php if (isset($delivery_state) && ($delivery_state == "NV" || $delivery_state == "Nevada")) {
												print(" selected=\"selected\"");
											} ?>>Nevada</option>
						<option value="NH" <?php if (isset($delivery_state) && ($delivery_state == "NH" || $delivery_state == "New Hampshire")) {
												print(" selected=\"selected\"");
											} ?>>New Hampshire</option>
						<option value="NJ" <?php if (isset($delivery_state) && ($delivery_state == "NJ" || $delivery_state == "New Jersey")) {
												print(" selected=\"selected\"");
											} ?>>New Jersey</option>
						<option value="NM" <?php if (isset($delivery_state) && ($delivery_state == "NM" || $delivery_state == "New Mexico")) {
												print(" selected=\"selected\"");
											} ?>>New Mexico</option>
						<option value="NY" <?php if (isset($delivery_state) && ($delivery_state == "NY" || $delivery_state == "New York")) {
												print(" selected=\"selected\"");
											} ?>>New York</option>
						<option value="NC" <?php if (isset($delivery_state) && ($delivery_state == "NC" || $delivery_state == "North Carolina")) {
												print(" selected=\"selected\"");
											} ?>>North Carolina</option>
						<option value="ND" <?php if (isset($delivery_state) && ($delivery_state == "ND" || $delivery_state == "North Dakota")) {
												print(" selected=\"selected\"");
											} ?>>North Dakota</option>
						<option value="OH" <?php if (isset($delivery_state) && ($delivery_state == "OH" || $delivery_state == "Ohio")) {
												print(" selected=\"selected\"");
											} ?>>Ohio</option>
						<option value="OK" <?php if (isset($delivery_state) && ($delivery_state == "OK" || $delivery_state == "Oklahoma")) {
												print(" selected=\"selected\"");
											} ?>>Oklahoma</option>
						<option value="OR" <?php if (isset($delivery_state) && ($delivery_state == "OR" || $delivery_state == "Oregon")) {
												print(" selected=\"selected\"");
											} ?>>Oregon</option>
						<option value="PA" <?php if (isset($delivery_state) && ($delivery_state == "PA" || $delivery_state == "Pennsylvania")) {
												print(" selected=\"selected\"");
											} ?>>Pennsylvania</option>
						<option value="RI" <?php if (isset($delivery_state) && ($delivery_state == "RI" || $delivery_state == "Rhode Island")) {
												print(" selected=\"selected\"");
											} ?>>Rhode Island</option>
						<option value="SC" <?php if (isset($delivery_state) && ($delivery_state == "SC" || $delivery_state == "South Carolina")) {
												print(" selected=\"selected\"");
											} ?>>South Carolina</option>
						<option value="SD" <?php if (isset($delivery_state) && ($delivery_state == "SD" || $delivery_state == "South Dakota")) {
												print(" selected=\"selected\"");
											} ?>>South Dakota</option>
						<option value="TN" <?php if (isset($delivery_state) && ($delivery_state == "TN" || $delivery_state == "Tennessee")) {
												print(" selected=\"selected\"");
											} ?>>Tennessee</option>
						<option value="TX" <?php if (isset($delivery_state) && ($delivery_state == "TX" || $delivery_state == "Texas")) {
												print(" selected=\"selected\"");
											} ?>>Texas</option>
						<option value="UT" <?php if (isset($delivery_state) && ($delivery_state == "UT" || $delivery_state == "Utah")) {
												print(" selected=\"selected\"");
											} ?>>Utah</option>
						<option value="VT" <?php if (isset($delivery_state) && ($delivery_state == "VT" || $delivery_state == "Vermont")) {
												print(" selected=\"selected\"");
											} ?>>Vermont</option>
						<option value="VA" <?php if (isset($delivery_state) && ($delivery_state == "VA" || $delivery_state == "Virginia")) {
												print(" selected=\"selected\"");
											} ?>>Virginia</option>
						<option value="WA" <?php if (isset($delivery_state) && ($delivery_state == "WA" || $delivery_state == "Washington")) {
												print(" selected=\"selected\"");
											} ?>>Washington</option>
						<option value="WV" <?php if (isset($delivery_state) && ($delivery_state == "WV" || $delivery_state == "West Virginia")) {
												print(" selected=\"selected\"");
											} ?>>West Virginia</option>
						<option value="WI" <?php if (isset($delivery_state) && ($delivery_state == "WI" || $delivery_state == "Wisconsin")) {
												print(" selected=\"selected\"");
											} ?>>Wisconsin</option>
						<option value="WY" <?php if (isset($delivery_state) && ($delivery_state == "WY" || $delivery_state == "Wyoming")) {
												print(" selected=\"selected\"");
											} ?>>Wyoming</option>
					</select>
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">Zip</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_zipcode" id="order_zipcode" value="<?php echo $delivery_postcode; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="19" style="width: 100px" class="style1">Phone</td>
				<td align="left" height="19" style="width: 235px" class="style1">
					<input type="text" name="order_phone" id="order_phone" value="<?php echo $customers_telephone; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">E-mail</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_email" id="order_email" value="<?php echo $customers_email_address; ?>" />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 100px" class="style1">UPS notes</td>
				<td align="left" height="13" style="width: 235px" class="style1">
					<input type="text" name="order_comments" id="order_comments" value="<?php echo $ups_notes; ?>" />
				</td>
			</tr>

			<tr bgColor="#e4e4e4">
				<td colspan="2" align="center" height="13" class="style1">
					<input type="submit" name="order_btnupdate" id="order_btnupdate" value="Update" />
				</td>
			</tr>
		</table>
	<?php
	}
	?>
</form>