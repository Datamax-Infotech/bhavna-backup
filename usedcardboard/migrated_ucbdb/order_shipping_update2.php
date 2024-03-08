<?php 
	require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="5">
		<font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> 
		DATABASE IMPORT</font>
		</td>
	</tr>
	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 95px" class="style1">WAREHOUSE</font></td>
		<td align="left" height="13" style="width: 177px" class="style1">ID</td>
		<td align="left" height="13" style="width: 177px" class="style1">Module</td>
		<td align="left" height="13" style="width: 177px" class="style1">FedEx Status</td>
		<td align="left" height="13"  class="style1"></td>
	</tr>
	<?php 
	$sqlwarehouse = "SELECT * FROM ucbdb_warehouse";

	$resw = db_query($sqlwarehouse,db() );		

	while ($wrow = array_shift($resw)) {
	
		if ($wrow["tablename"] != "") {
			$sql_orders_active_table = "SELECT * FROM " . $wrow["tablename"] . " WHERE orders_id = " . $_REQUEST["tmp_orderid"];
			$rest = db_query($sql_orders_active_table,db() );		

			while ($row_active = array_shift($rest)) {
				$shipping_details = $row_active["shipping_name"] . "\n" . $row_active["shipping_street1"] . ", " . $row_active["shipping_street2"] . ", " . $row_active["shipping_city"] . ", " . $row_active["shipping_state"] . ", " . $row_active["shipping_zip"];
				$shipping_details .= "\n" . $row_active["phone"] . "\n" . $row_active["email"];
			?>
			
			<tr bgColor="#e4e4e4">
				<td height="13" style="width: 95px" class="style1"><?php echo $wrow["distribution_center"]?></td>
				<td align="left" height="13" style="width: 177px" class="style1" title="<?php  echo $shipping_details;?>"><?php  echo $row_active["id"]; ?></td>
				<td align="left" height="13" style="width: 177px" class="style1"><?php  echo $row_active["module_name"]; ?></td>
				<td align="left" height="13" style="width: 177px" class="style1"><?php  echo $row_active["ship_status"]; ?></td>
				<td align="left" height="13" class="style1"><a href="deletelabel.php?table_name=<?php echo $wrow["tablename"];?>&id=<?php  echo $row_active["id"]; ?>">Delete</a></td>
			</tr>
			<?php 
			}
		}
	}
	?>
	
<!--	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 95px" class="style1">ETC</td>
		<td align="left" height="10" style="width: 177px">&nbsp;</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 95px" class="style1">ETC 2</td>
		<td align="left" height="13" style="width: 177px">&nbsp;</td>
	</tr>
-->	
	</table>