<table>
	<tr>
		<td colspan="2" class="item1" ><h3 class="item1">Item</h3></td>
	</tr>
	<tr class="sidebartxt" >
		<td>
			<? if ($_SESSION['idTitle_new'] != "") {?>
				ID: <? echo $invId; ?>, <? echo $_SESSION['idTitle_new'];?>
			<? } else { ?>	
				ID: <? echo $invId; ?>, <? echo $boxid_text; ?>,  <?echo $row_loopbox["system_description"];?>
			<? }  ?>	
		</td>
	</tr>
	<tr class="sidebartxt" >
		<td>
			<br>Lead Time: <? echo $orderData['hdAvailability']; ?>
		</td>
	</tr>
	
</table>