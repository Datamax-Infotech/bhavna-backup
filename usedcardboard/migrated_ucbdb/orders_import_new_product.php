<?php
// orders_import_new_product.php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
?>
<div class="container">

	<table cellSpacing="1" cellPadding="1" width="900" border="0" style="margin:0 auto">
		<tr align="middle">
			<td bgColor="#ffcccc" colSpan="6" class="style9">
				<font face="Arial, Helvetica, sans-serif" color="#333333" size="3">Add New Item to Order table</font>
				<span class="close" onclick="closeme(); return false;">&times;</span>
			</td>
		</tr>
		<?php
		$sqlproducts = "SELECT * FROM products_shopify order by product_description";
		$products = db_query($sqlproducts);
		?>
		<tr align="middle" bgColor="#e4e4e4">
			<td height="13" style="width:120px" title="<?php echo isset($shipping_details) ? $shipping_details : ""; ?>">Column Name</td>
			<td height="13" style="width:180px">Value</td>
			<td height="13" style="width:120px">Column Name</td>
			<td height="13" style="width:180px">Value</td>
			<td height="13" style="width:120px">Column Name</td>
			<td height="13" style="width:180px">Value</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td height="13">Warehouse Name *</td>
			<td align="left" height="13">
				<select id="warehouseid" name="warehouseid" style="width:150px" required>
					<option value=""> Select One</option>
					<?php
					$w_resw = db_query("SELECT * FROM ucbdb_warehouse order by tablename");
					while ($wrow = array_shift($w_resw)) {
						echo '<option value="' . $wrow['id'] . '">' . $wrow['distribution_center'] . '</option>';
					}
					?>
				</select>
			</td>
		</tr>

		<tr bgColor="#e4e4e4">
			<td height="13">Product Name *
				<input type="hidden" id="orderid" name="orderid" value="<?php echo decrypt_url($_GET["id"]); ?>" />
				<input type="hidden" id="kit_id" name="kit_id" value="" />
				<input type="hidden" id="productmoduleid" name="productmoduleid" value="" />
			</td>
			<td align="left" height="13">
				<select id="product_name" name="product_name" style="width:150px" onchange="value_set2kitid(this.value)" required>
					<option value=""> Select One</option>
					<?php
					while ($prod = array_shift($products)) {
						echo '<option value="' . $prod['ucb_products_id'] . '">' . $prod['product_description'] . '</option>';
					}
					?>
				</select>
			</td>
			<td height="13">Kits Name *</td>
			<td align="left">
				<select id="kitsname" name="kitsname" style="width:150px" onchange="javascript: value_set2kitid(this);" required>
					<option value=""> Select One</option>
					<?php
					$kitsdatas = db_query("SELECT * FROM kits WHERE cancel = 'No'");
					while ($kitsdata = array_shift($kitsdatas)) {
						echo '<option value="' . $kitsdata['name'] . '" data-kitid="' . $kitsdata['kit_id'] . '">' . $kitsdata['name'] . '</option>';
					}
					?>
				</select>
			</td>
			<td height="13">Module Name*</td>
			<td align="left" height="13">
				<select id="productmodule" name="productmodule" onchange="javascript: module_data_show(this);" style="width:150px" required>
					<option value=""> Select One</option>
					<?php
					$moduledatas = db_query("SELECT * FROM `module` WHERE cancel = 'No'");
					while ($moduledata = array_shift($moduledatas)) {
						echo '<option value="' . $moduledata['name'] . '" data-description="' . $moduledata['description'];
						echo '" data-weight="' . $moduledata['weight'] . '" data-length="' . $moduledata['length1'];
						echo '" data-width="' . $moduledata['width'] . '" data-height="' . $moduledata['height'];
						echo '" data-reference="' . $moduledata['reference'] . '" data-moduleid="' . $moduledata['module_id'] . '">' . $moduledata['name'] . '</option>';
					}
					?>
				</select>

			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td height="13">Description</td>
			<td align="left" height="13">
				<input type="text" id="productdescription" name="productdescription" value="" readonly />
			</td>
			<td height="13">Reference</td>
			<td align="left" height="13">
				<input type="text" id="reference" name="reference" value="" readonly />
			</td>
			<td height="13">Weight</td>
			<td align="left" height="13">
				<input type="text" id="boxweight" name="boxweight" value="" readonly />
			</td>
		</tr>
		<tr bgColor="#e4e4e4">
			<td height="13">Length</td>
			<td align="left" height="13">
				<input type="text" id="boxlength" name="boxlength" value="" readonly />
			</td>

			<td height="13">Width</td>
			<td align="left" height="13">
				<input type="text" id="boxwidth" name="boxwidth" value="" readonly />
			</td>
			<td height="13">Height</td>
			<td align="left" height="13">
				<input type="text" id="boxheight" name="boxheight" value="" readonly />
			</td>
		</tr>
		<?php
		$sqlwarehouse = "SELECT * FROM orders where orders_id = " . decrypt_url($_GET["id"]);
		$resw = db_query($sqlwarehouse);
		while ($row_active = array_shift($resw)) {
			$shipping_details = $row_active["delivery_name"] . "\n" . $row_active["delivery_street_address"] . ", " . $row_active["delivery_street_address2"] . ", " . $row_active["delivery_city"] . ", " . $row_active["delivery_state"] . ", " . $row_active["delivery_postcode"];
		?>
			<tr align="middle">
				<td bgColor="#aaa" colSpan="6" class="style9">
					<font face="Arial, Helvetica, sans-serif" size="2" title="<?php echo $shipping_details; ?>">Shipping Details</font>
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13">Name *</td>
				<td align="left" height="13">
					<input type="text" id="shipname" name="shipname" value="<?php echo $row_active["delivery_name"]; ?>" required />
				</td>
				<td height="13">Company</td>
				<td align="left" height="13">
					<input type="text" id="shipcompany" name="shipcompany" value="<?php echo $row_active["delivery_company"]; ?>" />
				</td>
				<td height="13">Address 1*</td>
				<td align="left" height="13">
					<input type="text" id="shipstreet1" name="shipstreet1" value="<?php echo $row_active["delivery_street_address"]; ?>" required />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13">Address 2</td>
				<td align="left" height="13">
					<input type="text" id="shipstreet2" name="shipstreet2" value="<?php echo $row_active["delivery_street_address2"]; ?>" />
				</td>
				<td height="13">City *</td>
				<td align="left" height="13">
					<input type="text" id="shipcity" name="shipcity" value="<?php echo $row_active["delivery_city"]; ?>" required />
				</td>
				<td height="13">State *</td>
				<td align="left" height="13">
					<input type="text" id="shipstate" name="shipstate" value="<?php echo $row_active["delivery_state"]; ?>" required />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13">Zip *</td>
				<td align="left" height="13">
					<input type="text" id="shipzip" name="shipzip" value="<?php echo $row_active["delivery_postcode"]; ?>" required />
				</td>

				<td height="13">Shipping Release</td>
				<td align="left" height="13">
					<input type="text" id="shiprelease" name="shiprelease" value="No" />
				</td>

				<td height="13">Phone *</td>
				<td align="left" height="13">
					<input type="text" id="custphone" name="custphone" value="<?php echo $row_active["customers_telephone"]; ?>" required />
				</td>
			</tr>
			<tr bgColor="#e4e4e4">
				<td height="13">Email *</td>
				<td align="left" colspan="5">
					<input type="text" id="custemail" name="custemail" value="<?php echo $row_active["customers_email_address"]; ?>" required />
				</td>
			</tr>
		<?php
		} //while
		?>
		<tr bgColor="#e4e4e4">
			<td height="13" colSpan="6" align="center">
				<input type="submit" id="submitimport" name="submit" value="Add New Product" onclick="importproductfromdatabase();" />

				<div id="add_row_respose" id="add_row_respose"></div>
			</td>
		</tr>
	</table>
</div>