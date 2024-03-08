<?php 
require ("inc/header_session.php");
?>


<?php
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");



/* insert the file */


/*
if ($_FILES["file"]["size"] < 1000000)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    echo "UCB Dashboard<br><br>";
	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";    if (file_exists("faxes/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "faxes/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "faxes/" . $_FILES["file"]["name"];
      }
    }
  }
else
  {
  echo "Invalid file";
  }

*/
  
// $old_today = date("Ymd"); 
// $today = now(); 

$today = date('m/d/y h:i a'); 

$search_today = $_POST['search_date']; 

$sql = "INSERT INTO ucbdb_endofday (warehouse_name, import_date, search_date, labels_on_report, labels_on_pickup, employee, file_name) VALUES ( '" . $_POST['warehouse_name'] . "','" . $today . "','" . $search_today . "','" . $_POST['labels_on_report'] . "','" . $_POST['labels_on_pickup'] . "','" . $_POST['employee'] . "','" . $_FILES["file"]["name"] . "')";
echo "<BR>SQL: $sql<BR>";
$result = db_query($sql,db() );


echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()){    //If headers not sent yet... then do php redirect
        header('Location: eod_upload.php'); exit;
}
else
{
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"eod_upload.php";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=eod_upload.php\" />";
        echo "</noscript>"; exit;
}//==== End -- Redirect
//header('Location: orders.php?id=' . $_POST[orders_id] . '&proc=View');


?>