<?php
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require('meeting_common_function.php');
	
	//Archive Task after 2 days
	$task_completed_on= date("Y-m-d", strtotime("-2 day"));
	$from=$task_completed_on." 12:00:00";
	$to=$task_completed_on." 23:59:50";
	$upsql = "UPDATE `task_master` SET `archive_status` = 1 , task_completed_by = 0 WHERE archive_status=0 && task_status=1 && (task_completed_on BETWEEN '$from' and '$to')";
	$result = db_query($upsql, db_project_mgmt());	

?>
