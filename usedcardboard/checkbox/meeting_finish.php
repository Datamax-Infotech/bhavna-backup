<?php
/*
File Name: meeting_finish.php
Module Name: Meeting
Page created By: Amarendra Singh
Page created On: 31-10-2023
*/

require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

$upsql = "UPDATE `meeting_timer` SET `end_time` ='". date('Y-m-d H:i:s') . "', `meeting_flg` = '1' , `meeting_end_by` = '". $_COOKIE['employeeid'] ."' WHERE id='". $_REQUEST["id"] ."'";

$result = db_query($upsql, db_project_mgmt());