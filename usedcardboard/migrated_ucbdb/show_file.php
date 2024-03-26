<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
db();
$sql = "SELECT AttachmentData FROM Attachments WHERE AttachmentName = '$_GET[file]'";
// $sql = "SELECT AttachmentData FROM Attachments WHERE ID = 3522";
// $sql = "SELECT AttachmentData FROM Attachments WHERE ID = $_GET[id]";
$result = db_query($sql);
while ($myrowsel = array_shift($result)) {

    header("Content-Type: audio/mpeg");
    echo $myrowsel['AttachmentData'];
    exit();
}
