<?php
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require('meeting_common_function.php');
	
	$sql = "SELECT id, meeting_id from meeting_timer where meeting_flg = 0";
    $result = db_query($sql,db_project_mgmt());
	if(tep_db_num_rows($result) > 0){
		while ( $row = array_shift($result) ) {	
			$meeting_timer_id = $row['id'];
			$meeting_id = $row['meeting_id'];
			$update_task_sql = "UPDATE `task_master` SET `task_status` = 1, archive_status=1 WHERE task_meeting=".$meeting_id." && (task_status=1 OR task_status=2)";
			$result = db_query($update_task_sql, db_project_mgmt());
			
			$update_task_flag_during_meeting_sql = "UPDATE `task_master` SET `added_during_meeting` = 0 WHERE task_meeting=".$meeting_id." && added_during_meeting = 1";
			db_query($update_task_flag_during_meeting_sql, db_project_mgmt());

			$upsql = "UPDATE `meeting_timer` SET `end_time` ='". date('Y-m-d H:i:s') . "', `meeting_flg` = '1' , `meeting_end_by` = 0 WHERE id=".$meeting_timer_id;
			$result = db_query($upsql, db_project_mgmt());	
			db_query("UPDATE meeting_live_updates set meeting_flg=2 where meeting_timer_id='".$meeting_timer_id."'",db_project_mgmt());
			update_meeting_minutes($meeting_id,$meeting_timer_id,'Meeting Ended','Meeting Ended',"",$_COOKIE['b2b_id']);
		}
	}


?>
