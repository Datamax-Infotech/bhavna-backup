<?php 
$sales_rep_login = "no";
if (isset($_REQUEST["repchk"])) {
	if ($_REQUEST["repchk"] == "yes") {
		$sales_rep_login = "yes";
	}	
}else {
	require ("inc/header_session_client.php");
}
require ("../ucbloop/mainfunctions/database.php");
require ("../ucbloop/mainfunctions/general-functions.php");

db();

	?>
	<table cellSpacing="1" cellPadding="1" border="0" width="350">
		<tr align="middle">
		  <td class="style12" colspan="2" >&nbsp;</td>
		</tr>
		   <tr align="middle">
		  <td class="style7" colspan="2" style="height: 16px"><strong>REPORT FOR TRAILER #<?php echo $_REQUEST["trailer_no"];?></strong></td>
		</tr>
		<tr vAlign="center">
		  <td bgColor="#e4e4e4" width="200" class="style12" >Box Description</td>
		  <td bgColor="#e4e4e4" class="style12" >Box Qty</td>
		</tr>
	<?php
	$gb = 0;

	$dt_view_qry = "SELECT * FROM loop_bol_tracking INNER JOIN loop_boxes ON loop_bol_tracking.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_bol_tracking.trans_rec_id = " . $_REQUEST["trans_rec_id"];
	$dt_view_res = db_query($dt_view_qry );

	while ($dt_view_row = array_shift($dt_view_res)) {

		if ($dt_view_row["qty"] > 0) 
		{
	?>
		 <tr>
			  <td bgColor="#e4e4e4" class="style12left" >
			  <?php echo $dt_view_row["blength"];?> <?php echo $dt_view_row["blength_frac"];?> x
			  <?php echo $dt_view_row["bwidth"];?> <?php echo $dt_view_row["bwidth_frac"];?> x 
			  <?php echo $dt_view_row["bdepth"];?> <?php echo $dt_view_row["bdepth_frac"];?>
			  <?php echo $dt_view_row["bdescription"];?></td>
			  <td bgColor="#e4e4e4" class="style12right" ><?php echo $dt_view_row["qty"];?></td>
		 </tr>
	<?php 
		$gb += $dt_view_row["qty"];
		}
	}	 ?>	

	<tr>
	  <td bgColor="#e4e4e4" class="style12" ><strong>BOX TOTALS</strong></td>
	  <td bgColor="#e4e4e4" class="style12right" ><strong><?php echo $gb;?></strong></td>
	</tr>

