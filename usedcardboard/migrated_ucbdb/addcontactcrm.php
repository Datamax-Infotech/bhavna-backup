<?php
require("inc/header_session.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
/* insert the file */
$filetype = "pdf,mp3,jpg,jpeg,tif,tiff,png,gif";
$allow_ext = explode(",", $filetype);

if ($_FILES["file"]["size"] < 1000000) {
	if ($_FILES["file"]["error"] > 0) {
		echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
	} else {
		$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		if (in_array(strtolower($ext), $allow_ext)) {
			echo "UCB Dashboard<br><br>";
			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			echo "Type: " . $_FILES["file"]["type"] . "<br />";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			if (file_exists("files/" . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " already exists. ";
			} else {
				move_uploaded_file(
					$_FILES["file"]["tmp_name"],
					"files/" . $_FILES["file"]["name"]
				);
				echo "Stored in: " . "files/" . $_FILES["file"]["name"];
			}
		} else {
			echo "<font color=red>" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.</font>";
			echo "<script>alert('" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.');</script>";
		}
	}
} else {
	echo "Invalid file";
}

$today = date("Ymd");


$sql = "INSERT INTO ucbdb_contact_crm (contact_id, comm_type, message, message_date, employee, file_name) VALUES ( '" . $_POST['orders_id'] . "','" . $_POST['comm_type'] . "','" . $_POST['message'] . "','" . $today . "','" . $_COOKIE['userinitials'] . "','" . $_FILES["file"]["name"] . "')";
//echo "<BR>SQL: $sql<BR>";
db();
$result = db_query($sql);
echo "<DIV CLASS='SQL_RESULTS'>Record Inserted<br><br>Please wait - the database is being updated and this page will automatically refresh.</DIV>";
if (!headers_sent()) {    //If headers not sent yet... then do php redirect
	header('Location: contact_status_drill.php?id=' . encrypt_url($_POST['orders_id']) . '&proc=View');
	exit;
} else {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"contact_status_drill.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=contact_status_drill.php.php?id=" . encrypt_url($_POST['orders_id']) . "&proc=View\" />";
	echo "</noscript>";
	exit;
} //==== End -- Redirect
