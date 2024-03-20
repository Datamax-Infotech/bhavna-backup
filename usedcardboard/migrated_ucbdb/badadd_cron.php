<?php 

require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
db();

$dupqry = "SELECT * FROM orders WHERE delivery_street_address = '' ORDER BY orders_id DESC";
$dupqryres = db_query($dupqry);
$dupqryresnum = tep_db_num_rows($dupqryres);
$dupqrycurcount = "SELECT DISTINCT number, mailflag FROM orders_missing_address";
$dupqrycurcountres = db_query($dupqrycurcount);
$dupqrycurcountrescount = 0;
$dupqrycurcountrescountflag = 0;

while($dupqrycurcountrescnt = array_shift($dupqrycurcountres))
{
$dupqrycurcountrescount = $dupqrycurcountrescnt["number"];
$dupqrycurcountrescountflag = $dupqrycurcountrescnt["mailflag"];
}

$value = $dupqryresnum - $dupqrycurcountrescount; 


if (($value != 0) && ($dupqrycurcountrescountflag != 1)) {

$sql32 = "UPDATE orders_missing_address SET mailflag = 2";
$result32 = db_query($sql32);

header('Location: http://b2c.usedcardboardboxes.com/badadd_cron_mail.php?number=' . $value); exit;

}

?>