<Font Face='arial' size='2'>
 
<br>


<?php 
$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
$dt_view_res = db_query($dt_view_qry,db() );
$trailer_view_row = array_shift($dt_view_res);

$trailer_number = $trailer_view_row["pr_trailer"];

?>



<form action="addbolsort.php" method="post" encType="multipart/form-data">
<input type="hidden" name="warehouse_id" value="<?php  echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<?php  echo $rec_type; ?>"/>		
<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<?php  echo $rec_id; ?>"/>	
	
<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table14">

	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="3">
		<font size="1">CONFIRM DELIVERY BILL OF LADING - TRAILER #<?php  echo $trailer_number; ?></font> </td>
	</tr>
		
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 85px" class="style1" align="right">
		Bill of Lading</td>
		
 
		<td height="13" class="style1" align="left" colspan="2">
		
	
<Font size='1' Face="arial">
 
		<input type=file name="file" size="32"></font></td>
 
	
	</tr>

	<tr bgColor="#e4e4e4">
		<td height="10" style="width: 85px" class="style1" align="right">

	
		</td>
		<td height="10" style="width: 217px" class="style1" align="center">
		<input type=submit value="Upload">
	

 
		</td>
		<td align="center" height="10" style="width: 132px" class="style1">		
 
 
		<font size="1" Face="arial"><a href="addbolsortignore.php?warehouse_id=<?php  echo $id; ?>&rec_id=<?php  echo $rec_id; ?>&rec_type=<?php  echo $rec_type; ?> &employee=<?php  echo $_COOKIE['userinitials'] ?>&proc=View&searchcrit=&display=seller_sort">Ignore</a></font></td>
	</tr>

	
	</table>

</form> 

<br>

<?php 

$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "' AND bol_sort_file != ''";
$dt_view_res = db_query($dt_view_qry,db() );
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>

	

<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="3">
		<font size="1">BILL OF LADING - TRAILER #<?php  echo $trailer_number ; ?></font> </td>
	</tr>
	

<?php 
while ($dt_view_row = array_shift($dt_view_res)) {
?>
	

	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		File</td>
		<td height="13" class="style1" align="left" colspan="2">
<?php  if ($dt_view_row["bol_sort_file"] == 'No BOL') { ?>
Delivery BOL Ignored
<?php  } else { ?>
<a href="./bol/<?php  echo $dt_view_row["bol_sort_file"]; ?>">View File: <?php  echo $dt_view_row["bol_sort_file"]; ?></a>
<?php  } ?>
		</td>
 	</tr>
	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		Employee</td>
		<td height="13" class="style1" align="left" colspan="2">
		
<?php  echo $dt_view_row["bol_sort_employee"]; ?>
		</td>
 	</tr>

	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		Date Entered</td>
		<td height="13" class="style1" align="left" colspan="2">
		
<?php  echo $dt_view_row["bol_sort_date"]; ?>
		</td>
 	</tr>
	
	
	
<?php  } ?>
	</table>	
<?php  } ?>	





 
<br>
<?php 

$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "' AND sort_entered = 1";
$dt_view_res = db_query($dt_view_qry,db() );
$num_rows = tep_db_num_rows($dt_view_res);
if (($num_rows < 1) || ($_GET["pa_edit"] == "true")) {
//echo $dt_view_qry;
?>
<!-- INITIAL SORT --> 
<form action="addboxsort.php" method="post" encType="multipart/form-data">
<input type="hidden" name="warehouse_id" value="<?php  echo $id; ?>"/>
<input type="hidden" name="warehouse_id" value="<?php  echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<?php  echo $rec_type; ?>"/>		
<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<?php  echo $rec_id; ?>"/>	
<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="5">
		<font size="1">SORT REPORT - TRAILER #<?php  echo $trailer_number; ?></font></td>
	</tr>
<?php 
$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
$dt_view_res = db_query($dt_view_qry,db() );
while ($dt_view_row = array_shift($dt_view_res)) {
if ($dt_view_row["pa_warehouse"] != 'No Delivery Warehouse') {
?>
<input type="hidden" name="sort_warehouse" value="<?php  echo $dt_view_row["pa_warehouse"]; ?>"/>
<?php  
}
else
{
 ?>	
	<tr bgColor="#e4e4e4">
		<td colspan="2"height="13" class="style1" align="left">
<Font Face='arial' size='2'>
		<select size="1" name="sort_warehouse">
<option value="">Please Select
<?php  

$gsql = "SELECT * FROM loop_warehouse WHERE rec_type = 'Sorting'";
$gresult = db_query($gsql,db() );
while ($gmyrowsel = array_shift($gresult)) {
?>
<option value="<?php  echo $gmyrowsel["id"]; ?>"><?php  echo $gmyrowsel["warehouse_name"]; ?></option>
<?php  } ?>
</select>&nbsp;	</td>
		</font></font></font>
<Font size='2'>
		<td align="left" height="13" style="width: 578px" class="style1">
		Please Select Warehosue</td>
	</tr>	
<?php  
} 
}
?>	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		GOOD</td>
		<td height="13" class="style1" align="center">
		BAD</td>
		<td height="13" class="style1" align="center">
		GOOD VALUE</td>
		<td height="13" class="style1" align="center">
		BAD VALUE</td>
		<td align="left" height="13" style="width: 578px" class="style1">
		DESCRIPTION</td>
	</tr>
<?php  
$get_boxes_query = db_query("SELECT * FROM loop_boxes_to_warehouse INNER JOIN loop_boxes ON loop_boxes_to_warehouse.loop_boxes_id = loop_boxes.id WHERE loop_boxes_to_warehouse.loop_warehouse_id = " . $id );
$i=0;
while ($boxes = array_shift($get_boxes_query)) {
$count=tep_db_num_rows($get_boxes_query);
$count_and_one = $count + 1;
$i++;
?>	 	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="right">
			<input type="hidden" name="box_id[]" value="<?php  echo $boxes["id"]; ?>"/>	
			<input size="3" name="boxgood[]" type=text>
		</td>
		<td height="13" class="style1" align="right">
			<Font Face='arial' size='2'>
			<input size="3" name="boxbad[]" type=text>
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" name="boxgoodvalue[]" type=text value="<?php  echo $boxes["boxgoodvalue"]; ?>">
		</td>
		<td height="13" class="style1" align="right">
			<Font Face='arial' size='2'>
			<input size="3" name="boxbadvalue[]" type=text value="<?php  echo $boxes["boxbadvalue"]; ?>">
		</td>
		<td align="left" height="13" style="width: 578px" class="style1">
			<font size="1" Face="arial"><?php  echo $boxes["blength"]; ?> 
				<?php  echo $boxes["blength_frac"]; ?> x <?php  echo $boxes["bwidth"]; ?> 
				<?php  echo $boxes["bwidth_frac"]; ?> x <?php  echo $boxes["bdepth"]; ?> 
				<?php  echo $boxes["bdepth_frac"]; ?> <?php  echo $boxes["bdescription"]; ?>
			</font>
		</td>
	</tr>
<?php  } ?>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		&nbsp;
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="boxscrap">
		</td>
		<td align="left" height="13"class="style1">
		SCRAP
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		&nbsp;
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="freightcharge">
		</td>
		<td align="left" height="13"class="style1">
		FREIGHT CHARGE
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		OTHER CHARGES
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="othercharge">
		</td>
		<td align="left" height="13"class="style1">
			<input size="30" type="text" name="otherdetails">
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13"class="style1" align="right">
		Notes</td>
		<td height="13" class="style1" align="right" colspan="4">
<Font size='2' Face="arial">
		<p align="left"><textarea rows="3" cols="30" name="boxnotes"></textarea></font></td>
		</font></font></font>
<Font size='2'>
	</tr>
	</font></font>
<Font Face='arial' size='2'>
	<tr bgColor="#e4e4e4">
		<td colspan=5 align="left" height="19" class="style1">
			<p align="center">
			<input type="hidden" name="count" value="<?php  echo $count; ?>">
			<input type=submit value="Submit  &amp; Add to Inventory">
		</td>
	</tr>
	</table>
</form>
<?php  } ?>




<!-- EDIT A SORT --> 
<?php  if ($_GET["sort_edit"] == "true") { ?>



<form action="addboxsort.php" method="post" encType="multipart/form-data">
<input type="hidden" name="warehouse_id" value="<?php  echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<?php  echo $rec_type; ?>"/>		
<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<?php  echo $rec_id; ?>"/>	
<input type="hidden" name="update" value="yes"/>	
<input type="hidden" name="updatecrm" value="yes"/>	
<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="5">
		<font size="1">SORT REPORT - TRAILER #<?php  echo $trailer_number; ?></font></td>
	</tr>
<?php 
$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "'";
$dt_view_res = db_query($dt_view_qry,db() );
while ($dt_view_row = array_shift($dt_view_res)) {

if ($dt_view_row["pa_warehouse"] != 'No Delivery Warehouse') {
?>

<?php  
$gsql = "SELECT * FROM loop_warehouse WHERE id = " . $dt_view_row["pa_warehouse"];
$gresult = db_query($gsql,db() );
while ($gmyrowsel = array_shift($gresult)) {
?>
<input type="hidden" name="sort_warehouse" value="<?php  echo $gmyrowsel["id"]; ?>"/>

<?php  } ?>



<?php  
}
else
{
 ?>	
	<tr bgColor="#e4e4e4">
		<td colspan="2"height="13" class="style1" align="left">
<Font Face='arial' size='2'>
		<select size="1" name="sort_warehouse">
<option value="">Please Select
<?php  

$gsql = "SELECT * FROM loop_warehouse WHERE rec_type = 'Sorting'";
$gresult = db_query($gsql,db() );
while ($gmyrowsel = array_shift($gresult)) {
?>
<option value="<?php  echo $gmyrowsel["id"]; ?>"><?php  echo $gmyrowsel["warehouse_name"]; ?></option>
<?php  } ?>
</select>&nbsp;	</td>
		</font></font></font>
<Font size='2'>
		<td align="left" height="13" style="width: 578px" class="style1">
		Please Select Warehosue</td>
	</tr>	
<?php  
} 
}
?>	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		GOOD</td>
		<td height="13" class="style1" align="center">
		BAD</td>
		<td height="13" class="style1" align="center">
		GOOD VALUE</td>
		<td height="13" class="style1" align="center">
		BAD VALE</td>
		<td align="left" height="13" style="width: 578px" class="style1">
		DESCRIPTION</td>
	</tr>
<?php  
$get_boxes_query = db_query("SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $rec_id );
$i=0;
while ($boxes = array_shift($get_boxes_query)) {
$count=tep_db_num_rows($get_boxes_query);
$count_and_one = $count + 1;
$i++;
?>	 	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="right">
			<input type="hidden" name="boxgood_old[]" value="<?php  echo $boxes["boxgood"]; ?>"/>	
			<input type="hidden" name="boxbad_old[]" value="<?php  echo $boxes["boxbad"]; ?>"/>	
			<input type="hidden" name="boxbad_desc_old[]" value="<?php  echo $boxes["bdescription"]; ?>"/>	
			<input type="hidden" name="box_row_id[]" value="<?php  echo $boxes["id"]; ?>">
			<input type="hidden" name="box_id[]" value="<?php  echo $boxes["box_id"]; ?>"/>	
			<input size="3" name="boxgood[]" type="text" value="<?php  echo $boxes["boxgood"]; ?>">
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" name="boxbad[]" type="text" value="<?php  echo $boxes["boxbad"]; ?>">
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" name="boxgoodvalue[]" type="text" value="<?php  echo $boxes["sort_boxgoodvalue"]; ?>">
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" name="boxbadvalue[]" type="text" value="<?php  echo $boxes["sort_boxbadvalue"]; ?>">
		</td>
		<td align="left" height="13" class="style1">
			<font size="1" Face="arial">
				<?php  echo $boxes["blength"]; ?> <?php  echo $boxes["blength_frac"]; ?> x <?php  echo $boxes["bwidth"]; ?> <?php  echo $boxes["bwidth_frac"]; ?> x <?php  echo $boxes["bdepth"]; ?> <?php  echo $boxes["bdepth_frac"]; ?> <?php  echo $boxes["bdescription"]; ?>
			</font>
		</td>
	</tr>
<?php 	
$box_old_scrap = $boxes["boxscrap"];
$box_old_notes = $boxes["boxnotes"];
$box_old_sort_date = $boxes["sort_date"];
$box_old_employee = $boxes["employee"];

	
} ?>




	<tr bgColor="#e4e4e4">
		<td height="13" colspan="3" class="style1" align="right">
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="boxscrap" value="<?php  echo $box_old_scrap; ?>">
		</td>
		<td align="left" height="13" class="style1">
		SCRAP
		</td>
	</tr>
<?php 
$dt_view_tran_qry = "SELECT * from loop_transaction WHERE id = " . $rec_id;
$dt_view_tran = db_query($dt_view_tran_qry,db() );
$dt_view_tran_row = array_shift($dt_view_tran);
?>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		&nbsp;
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="freightcharge" value="<?php  echo $dt_view_tran_row["freightcharge"]; ?>">
		</td>
		<td align="left" height="13"class="style1">
		FREIGHT CHARGE
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td colspan="3" height="13" class="style1" align="right">
		OTHER CHARGES
		</td>
		<td height="13" class="style1" align="right">
			<input size="3" type="text" name="othercharge" value="<?php  echo $dt_view_tran_row["othercharge"]; ?>">
		</td>
		<td align="left" height="13"class="style1">
			<input size="30" type="text" name="otherdetails" value="<?php  echo $dt_view_tran_row["otherdetails"]; ?>">
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="right">
		Notes</td>
		<td height="13" class="style1" align="right" colspan="4">
<Font size='2' Face="arial">
		<p align="left"><textarea rows="3" cols="30" name="boxnotes"><?php  echo $box_old_notes; ?></textarea></font></td>
		</font></font></font>
<Font size='2'>
	</tr>
	</font></font>
<Font Face='arial' size='1'>
<Font Face='arial' size='2'>
<Font Face='arial' size='2'>
	<tr bgColor="#e4e4e4">
		<td colspan=5 align="left" height="19" class="style1">
		<p align="center">	<input type="hidden" name="sort_date_old" value="<?php  echo $box_old_sort_date; ?>"><input type="hidden" name="scrap_old" value="<?php  echo $box_old_scrap; ?>"><input type="hidden" name="notes_old" value="<?php  echo $box_old_notes; ?>">
		<input type="hidden" name="employee_old" value="<?php  echo $box_old_employee; ?>">	<input type="hidden" name="count" value="<?php  echo $count; ?>">
		<input type=submit value="Submit  &amp; Add to Inventory"> <font size="1" Face="arial"><a href="javascript: window.history.go(-1)">Ignore</a></td>
	</tr>
	
	
	
	
	</table>
</form>
<?php  } ?>







<br>

<?php 
$good = 0;
$bad = 0;
$dt_view_qry = "SELECT * from loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "'";
$dt_view_res = db_query($dt_view_qry,db() );
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>

<!-- VIEW ENTERED SORT --> 
<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table4">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="5">
		<font size="1">SORT REPORT DATA - TRAILER #<?php  echo $trailer_number; ?></font> <a href="search_results.php?id=<?php  echo $id; ?>&rec_id=<?php  echo $rec_id; ?>&rec_type=<?php  echo $rec_type; ?> &proc=View&searchcrit=&display=seller_sort&sort_edit=true">EDIT</a></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 84px" class="style1" align="center">
			GOOD
		</td>
		<td height="13" style="width: 94px" class="style1" align="center">
			BAD
		</td>
		<td height="13" style="width: 84px" class="style1" align="center">
			GOOD VALUE
		</td>
		<td height="13" style="width: 94px" class="style1" align="center">
			BAD VALUE
		</td>
		<td align="left" height="13" style="width: 578px" class="style1">
		DESCRIPTION</td>
	</tr>

	
	
<?php 
while ($dt_view_row = array_shift($dt_view_res)) {
?>
	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 84px" class="style1" align="center">
			<?php  echo $dt_view_row["boxgood"]; $good = $good + $dt_view_row["boxgood"]; ?>
		</td>
		<td height="13" style="width: 94px" class="style1" align="center">
			<?php  echo $dt_view_row["boxbad"]; $bad = $bad + $dt_view_row["boxbad"];  ?>
		</td>
		<td height="13" style="width: 84px" class="style1" align="center">
			<?php  echo $dt_view_row["sort_boxgoodvalue"]; ?>
		</td>
		<td height="13" style="width: 94px" class="style1" align="center">
			<?php  echo $dt_view_row["sort_boxbadvalue"];  ?>
		</td>
		<td align="left" height="13" style="width: 578px" class="style1">
		<?php  
$get_boxes_query = db_query("SELECT * FROM loop_boxes WHERE id = " . $dt_view_row["box_id"], db());
while ($boxes = array_shift($get_boxes_query)) {

echo $boxes["bdescription"];

} ?>		</td>
	</tr>
	
	
<?php  } ?>	




<?php 
$dt_view_qry = "SELECT * from loop_boxes_sort WHERE trans_rec_id = '" . $rec_id . "' LIMIT 0,1";
$dt_view_res = db_query($dt_view_qry,db() );
while ($dt_view_row = array_shift($dt_view_res)) {
?>	
<tr bgColor="#e4e4e4">
<Font size='1'>
 

 
		<td height="13" align="center" class="style1">
			<?php  echo $good; ?>
		</td>
		<td height="13" align="center" class="style1">
			<?php  echo $bad; ?>
		</td>
		<td height="13" align="center" class="style1">
			&nbsp;
		</td>
		<td height="13" align="center" class="style1">
			&nbsp;
		</td>
</font>

		<td height="13" class="style1" align="left" >

	
<Font Face='arial' size='1'>
 
		TOTALS</td>
		</font></font></font>

	
		
		
	</tr>
<Font size='1'>
<?php 
$dt_view_tran_qry = "SELECT * from loop_transaction WHERE id = " . $rec_id;
$dt_view_tran = db_query($dt_view_tran_qry,db() );
$dt_view_tran_row = array_shift($dt_view_tran);
?>
	<tr bgColor="#e4e4e4">
		<td height="13" align="center" class="style1">
		Scrap: </td>
		<td height="13" class="style1" align="left" colspan="4">
			<?php  echo $dt_view_row["boxscrap"]; ?>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" align="center" class="style1">
		Freight: </td>
		<td height="13" class="style1" align="left" colspan="4">
			<?php  echo $dt_view_tran_row["freightcharge"]; ?>
			<?php  echo $dt_view_tran_qry; ?>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" align="center" class="style1">
		Other Charges: </td>
		<td height="13" class="style1" align="left" colspan="4">
			<?php  echo $dt_view_tran_row["othercharge"]; ?>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" align="center" class="style1">
		Other Details: </td>
		<td height="13" class="style1" align="left" colspan="4">
			<?php  echo $dt_view_tran_row["otherdetails"]; ?>
		</td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		Notes: </td>
		<td height="13" class="style1" align="left" colspan="4">
		<p align="left"><?php  echo $dt_view_row["boxnotes"]; ?></font></td>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		Employee: </td>
		<td height="13" class="style1" align="left" colspan="4">
		<p align="left"><?php  echo $dt_view_row["employee"]; ?></font></td>
		</font></font></font>
	</tr>	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		Date: </td>
		<td height="13" class="style1" align="left" colspan="4">
			<p align="left"><?php  echo $dt_view_row["sort_date"]; ?>
		</td>
	</tr>		
<?php  
} ?>	
	
</table>	
<?php  } ?>



<br>




<script language="JavaScript">
<!--
function FormCheck()
{
var thefilename = document.SortReport.file.value;
var filelength = parseInt(thefilename.length) - 3;
var fileext = thefilename.substring(filelength,filelength + 3);


if (document.SortReport.usr_amount.value == "")
{
alert('Please enter an amount.');
return false;
}
else if (fileext.toLowerCase() != "pdf")
{
alert ("You can only upload PDF file.");
return false;
}
}
//-->
</SCRIPT>

<form action="addusrreport.php" method="post" encType="multipart/form-data" name="SortReport" onSubmit="return FormCheck()">
<input type="hidden" name="warehouse_id" value="<?php  echo $id; ?>"/>
<input type="hidden" name="rec_type" value="<?php  echo $rec_type; ?>"/>		
<input type="hidden" name="recipient" value="<?php  echo $warehouse_contact_email; ?>"/>	
<input type="hidden" value="<?php  echo $_COOKIE['userinitials'] ?>" name="employee" />		
<input type="hidden" name="rec_id" value="<?php  echo $rec_id; ?>"/>	


<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table7">
	<tr align="middle">
		<td bgColor="#c0cdda" width="444" colspan="2">
 
	
<Font Face='arial' size='2'>
 
		<font size="1">UPLOAD SORT REPORT - TRAILER #<?php  echo $trailer_number; ?></font></td>
	</tr>
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="left" width="103">
		<p align="right">Amount Owed:</td>
		<td height="13" class="style1" align="left" width="584">

	
<Font Face='arial' size='1'>
 
		$</font><font size="1" Face="arial"><input size="10" type=text name="usr_amount"></font></td>
	</tr>
	
	
	<tr bgColor="#e4e4e4">
		<td height="13" class="style1" align="center">
		<p align="right">Report:</td>
		<td height="13" class="style1" align="center">
		<input type=file name="file" size="32"><br>
		<input type=submit value="Upload &amp; Email Report"></td>
	</tr>
	
	
	</table>

</form>



<br>

<?php 

$dt_view_qry = "SELECT * from loop_transaction WHERE id = '" . $rec_id . "' AND usr_file != ''";
$dt_view_res = db_query($dt_view_qry,db() );
$num_rows = tep_db_num_rows($dt_view_res);
if ($num_rows > 0) {
?>

	

<table cellSpacing="1" cellPadding="1" border="0" style="width: 444px" id="table13">
	<tr align="middle">
		<td bgColor="#c0cdda" colSpan="3">
		<font size="1">UPLOADED SORT REPORT - TRAILER #<?php  echo $trailer_number; ?></font> </td>
	</tr>
	

<?php 
while ($dt_view_row = array_shift($dt_view_res)) {
?>
	

	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		File</td>
		<td height="13" class="style1" align="left" colspan="2">
<?php  if ($dt_view_row["bol_file"] == 'No Sort Report') { ?>
No Sort Report
<?php  } else { ?>
<a href="./files/<?php  echo $dt_view_row["usr_file"]; ?>">View File: <?php  echo $dt_view_row["usr_file"]; ?></a>
<?php  } ?>
		</td>
 	</tr>
	
	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		Amount</td>
		<td height="13" class="style1" align="left" colspan="2">
		
<?php  echo $dt_view_row["usr_amount"]; ?>
		</td>
 	</tr>

	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		Employee</td>
		<td height="13" class="style1" align="left" colspan="2">
		
<?php  echo $dt_view_row["usr_employee"]; ?>
		</td>
 	</tr>


	<tr bgColor="#e4e4e4">
		<td height="13" style="width: 86px" class="style1" align="left">
		Date Entered</td>
		<td height="13" class="style1" align="left" colspan="2">
		
<?php  echo $dt_view_row["usr_date"]; ?>
		</td>
 	</tr>
	
	
	
<?php  } ?>
	</table>	
<?php  } ?>	



</font></font>

</font></font>
