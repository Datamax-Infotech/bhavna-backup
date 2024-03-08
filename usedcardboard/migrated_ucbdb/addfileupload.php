<?php 
require ("inc/header_session.php");

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");

$company_id = $_REQUEST["company_id"];
if ($_REQUEST["company_id"] == -1)
{
$cquery = "INSERT INTO files_companies (name) VALUES ( '" . $_REQUEST["newcompany"] . "')";
$cresult = db_query($cquery,db() );
$company_id = tep_db_insert_id();
echo "here" . $company_id;

}

$type_id = $_REQUEST["type_id"];
if ($_REQUEST["type_id"] == -1)
{
$tquery = "INSERT INTO files_types (name) VALUES ( '" . $_REQUEST["newtype"] . "')";
$tresult = db_query($tquery,db() );
$type_id = tep_db_insert_id();
echo "here" . $type_id;

}

srand ((double) microtime( )*1000000);
$random_number = rand( );
// Start Processing Function

$query = "SELECT * FROM files_companies WHERE id = " . $company_id;
$res = db_query($query,db() );
$row = array_shift($res);

$query2 = "SELECT * FROM files_types WHERE id = " . $type_id;
$res2 = db_query($query2,db() );
$row2 = array_shift($res2);

$newdate = str_replace('/','-',$_REQUEST["start_date"]);

/*
if ($_FILES["file"]["size"] < 10000000)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    echo "UCB Loop System<br><br>";
	echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";    if (file_exists("bol/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "uploadedfiles/" . $row["name"]."_".$row2["name"]."_".$newdate."_".$_FILES["file"]["name"]);
      echo "Stored in: " . "uploadedfiles/" . $row["name"]."_".$row2["name"]."_".$newdate."_".$_FILES["file"]["name"];
      }
    }
  }
else
  {
  echo "Invalid file";
  }
*/


echo "<br>";


echo $_REQUEST["start_date"];

//$filequery = "INSERT INTO files_file (company_id, type_id, date, memo, filename) VALUES ( '" . $company_id . "', '" . $type_id . "', '" . date('Y-m-d', strtotime($_REQUEST["start_date"])) . "', '" . $_REQUEST["memo"] . "', '" . $row["name"]."_".$row2["name"]."_".$newdate."_".$_FILES["file"]["name"]  . "')";
$filequery = "INSERT INTO files_file (company_id, type_id, date, memo, filename) VALUES ( '" . $company_id . "', '" . $type_id . "', '" . date('Y-m-d', strtotime($_REQUEST["start_date"])) . "', '" . $_REQUEST["memo"] . "', '')";
$fileresult = db_query($filequery);

//echo $filequery;
//$query2 = "SELECT * FROM files_file WHERE filename LIKE '" . $row["name"]."_".$row2["name"]."_".$newdate."_".$_FILES["file"]["name"] . "' ORDER BY id DESC";
//$res2 = db_query($query2,db() );
//$row2 = array_shift($res2);

// Ebd Processing functions



echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"upload_files.php?fileid=".$row2["id"]."\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=upload_files.php?fileid=".$row2["id"]." />";
        echo "</noscript>"; exit;

?>