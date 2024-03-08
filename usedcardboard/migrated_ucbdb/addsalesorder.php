<?php 
require ("inc/header_session.php");

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");


// Start Processing Function
// Waiting on David to Give Further Instruction


$today = date("m/d/Y"); 
$today_crm = date("Ymd"); 
$warehouse_id = $_POST["warehouse_id"];
$rec_type = $_POST["rec_type"];
$user = $_COOKIE['userinitials'];
$employee = $user;
$so_date = $today;
$trans_rec_id = $_POST["rec_id"];
$count = $_POST['count'];



srand ((double) microtime( )*1000000);
$random_number = rand( );

$sql_remove = "DELETE FROM loop_salesorders WHERE trans_rec_id = " . $trans_rec_id;
$result_remove = db_query($sql_remove,db() );

if ($_POST["update"] != "yes") {
for($i=0;$i<$count;$i++){
if ($_POST["quantity"][$i] > 0) {
$sql_sort = "INSERT INTO loop_salesorders  (so_date, warehouse_id, location_warehouse_id, trans_rec_id, box_id, qty, employee, rand_value, pickup_date, freight_vendor, time, notes ) VALUES ( '" . $so_date . "', '" . $warehouse_id . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $trans_rec_id . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["quantity"][$i] . "', '" . $employee . "', '" . $random_number . "', '" . $_POST["pickupdate"] . "', '" . $_POST["freight_vendor"] . "', '" . $_POST["pickup_time"] . "', '" . $_POST["notes"] . "')";
$result_sort = db_query($sql_sort,db() );
echo $sql_sort;
}
$ins_id = tep_db_insert_id();

// $sql_inv = "INSERT INTO loop_inventory  ( add_date, warehouse_id, sort_id, trans_rec_id, box_id, boxgood, boxbad, employee ) VALUES ( '" . $sort_date . "', '" . $sort_warehouse . "', '" . $ins_id . "', '" . $trans_rec_id . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["boxgood"][$i] . "', '" . $_POST["boxbad"][$i] . "', '" . $employee . "')";
// $result_inv = db_query($sql_inv,db() );

}
}




include('class.ezpdf.php');
$pdf = new Cezpdf();
$pdf->selectFont('./fonts/Helvetica');


if (file_exists('ucb_logo.jpg')){
  $pdf->addJpegFromFile('ucb_logo.jpg',210,$pdf->y-80,131,0);
} else {
  // comment out these two lines if you do not have GD jpeg support
  // I couldn't quickly see a way to test for this support from the code.
  // you could also copy the file from the locatioin shown and put it in the directory, then 
  // the code above which doesn't use GD will be activated.
  $img = ImageCreatefromjpeg('http://localhost:1001/ucbloop/ucb_logo.jpg');
  $pdf-> addImage($img,210,$pdf->y-80,131,0);
}


$pdf->ezText("




UsedCardboardBoxes.com Sales Order\n",20,array('justification'=>'centre'));

$data = array();
$query = "SELECT loop_salesorders.box_id, loop_salesorders.qty, loop_boxes.id, loop_boxes.blength, loop_boxes.blength_frac, loop_boxes.bwidth, loop_boxes.bwidth_frac, loop_boxes.bdepth, loop_boxes.bdepth_frac, loop_boxes.bdescription FROM loop_salesorders  INNER JOIN loop_boxes ON loop_salesorders.box_id = loop_boxes.id where loop_salesorders.trans_rec_id = " . $trans_rec_id . " AND loop_salesorders.rand_value = " . $random_number;
$result = db_query($query);
/*
while ($dresult = array_shift($result)) {
$description = $dresult["blength"] . $dresult["blength_frac"] . " x " . $dresult["bwidth"] . $dresult["bwidth_frac"] . " x " . $dresult["bdepth"] . $dresult["bdepth_frac"] . " - " . $dresult["bdescription"];
}
*/
while($data[] = array_shift($result)) {
}
// make the table
$pdf->ezTable($data,array('box_id'=>'Box No.','qty'=>'Quantity','bwidth'=>'Description'),'Sales Order Details');


$pdfcode = $pdf->output();
$dir = 'salesorders';
//save the file
if (!file_exists($dir)){
mkdir ($dir,0777);
}
$fname = tempnam($dir.'/','PDF_').'.pdf';
$file_name = basename($fname);
$fp = fopen($fname,'w');
fwrite($fp,$pdfcode);
fclose($fp);

$filequery = "INSERT INTO loop_salesorders_tracking (so_date, warehouse_id, trans_rec_id, employee, rand_value, file_name) VALUES ( '" . $so_date . "', '" . $warehouse_id . "', '" . $trans_rec_id . "', '" . $employee . "', '" . $random_number . "', '" . $file_name . "')";
$fileresult = db_query($filequery);







$sql = "UPDATE loop_transaction_buyer SET so_entered = 1, so_file = '" . $file_name . "', so_employee = '" . $employee . "', so_date = '" . $so_date . "' WHERE id = '" . $trans_rec_id . "'";
$result = db_query($sql,db() );


 header('Location: search_results.php?warehouse_id='. $_REQUEST["warehouse_id"] .'&rec_type=Supplier&proc=View&searchcrit=&id='. $_REQUEST["warehouse_id"] .'&rec_id='. $trans_rec_id .'&display=buyer_view');