<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
$number = $_GET['number'];
db();
$dupqry = "SELECT * FROM orders WHERE delivery_street_address = '' ORDER BY orders_id DESC LIMIT 0, " . $number;
$dupqryres = db_query($dupqry);
$dupqryresnum = tep_db_num_rows($dupqryres);


$dupqrycurcount = "SELECT DISTINCT number, mailflag FROM orders_missing_address";
$dupqrycurcountres = db_query($dupqrycurcount);

$dupqrycurcountrescount = 0;
$dupqrycurcountrescountflag = 0;

while ($dupqrycurcountrescnt = array_shift($dupqrycurcountres)) {
    $dupqrycurcountrescount = $dupqrycurcountrescnt["number"];
    $dupqrycurcountrescountflag = $dupqrycurcountrescnt["mailflag"];
}

$value = $dupqryresnum - $dupqrycurcountrescount;


if ($value != 0) {


    if ($dupqrycurcountrescountflag == 2) {
        // Fix Below to loop through employees later
        $to1 = "davidkrasnow@usedcardboardboxes.com";
        //$to2 = "walkiriaquiroa@usedcardboardboxes.com";
        //$to3 = "mdewan@tivex.com";
        $subject = "Urgent Bad Shipping Address";
        $message = "There are issues with Shipping Addresses";
        while ($result3data = array_shift($dupqryres)) {
            $message .= "\n";
            $message .= $result3data["orders_id"];
        }
        $message .= "\n";
        $from = "customerservice@usedcardboardboxes.com";
        $headers = "From: $from";
        mail($to1, $subject, $message, $headers);
        //mail($to2,$subject,$message,$headers);
        //mail($to3,$subject,$message,$headers);
        $sql33 = "UPDATE orders_missing_address SET mailflag = 1";
        $result33 = db_query($sql33);
    }
}
