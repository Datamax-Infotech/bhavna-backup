<?php
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	require('meeting_common_function.php');

	
	function getAttendeeInfo($meeting_id){
		$sql_main =db_query("SELECT ma.id,Headshot,b2b_id, name,initials FROM meeting_attendees as ma JOIN loop_employees ON ma.attendee_id=loop_employees.b2b_id  where ma.meeting_id=$meeting_id ORDER BY id ASC", db());
		$result=array();
		while($hrow = array_shift($sql_main)){
			$empname=$hrow["name"];
			$emp_b2b_id=$hrow["b2b_id"];
			$empDetails=getOwerHeadshotForMeeting($hrow['Headshot'],$hrow['initials']);
			$result[]=array('emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt'], 'empname'=>$empname,'emp_b2b_id'=>$emp_b2b_id, 'attendee_id'=>$hrow['id']);
		}
		return $result;
	}

	function getPageInfo($meeting_id){
		$sql_main =db_query("SELECT page_id,page_type,page_title,duration,order_no FROM meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC", db());
		$result=array();
		while($hrow = array_shift($sql_main)){
			$result[]=$hrow;
		}
		return $result;
	}

	function getStartedMeetingsForNotification(){
			//echo "SELECT meeting_flg,meeting_id,meeting_timer_id,meeting_name FROM meeting_timer as mt JOIN meeting_start_atten_ratings as ma ON mt.id=ma.meeting_timer_id JOIN meeting_master mm on mm.id=mt.meeting_id  where meeting_flg=0  && join_status=0 && attendee_id=".$_COOKIE['b2b_id'];
			$sql1 = db_query("SELECT meeting_flg,meeting_id,meeting_timer_id,meeting_name FROM meeting_timer as mt JOIN meeting_start_atten_ratings as ma ON mt.id=ma.meeting_timer_id JOIN meeting_master mm on mm.id=mt.meeting_id  where meeting_flg=0  && join_status=0 && attendee_id=".$_COOKIE['b2b_id']." && notification_status=0", db());
			$result=[];
			while($r = array_shift($sql1)){
				$data['encoded_meeting_id']=new_dash_encrypt($r['meeting_id']);
				$data['encoded_meeting_timer_id']=new_dash_encrypt($r['meeting_timer_id']);
				$data['meeting_id']=$r['meeting_id'];
				$data['meeting_timer_id']=$r['meeting_timer_id'];
				$data['meeting_name']=$r['meeting_name'];
				$data['meeting_flg']=$r['meeting_flg'];
				$result[]=$data;
			}
		return $result;
	}

	if(isset($_GET['edit_page']) && $_GET['edit_page']==1){
		$page_id=$_GET['page_id'];
		$sql1 = db_query("SELECT page_id,page_type,page_title,duration,	page_subheading FROM meeting_pages where page_id = $page_id " , db());
		$data=[];
		while($r = array_shift($sql1)){
			$data=$r;
		}
	    echo json_encode($data);
	}

	if(isset($_POST["meeting_action"]) && $_POST["meeting_action"] == "save-meeting-name"){
		$meeting_owner=$_COOKIE["b2b_id"];
		$meeting_name=str_replace("'", "\'" ,$_POST["meeting_name"]);
		$meeting_id=$_POST['hidden_meeting_id'];
		$res=1;
		if($meeting_id==""){
			$meeting_exist_count_sql=db_query("SELECT id FROM meeting_master where  meeting_name = '".$meeting_name."'", db());
			$meeting_count=tep_db_num_rows($meeting_exist_count_sql);
			if($meeting_count==0){
				$insql = "INSERT INTO `meeting_master`(`meeting_name`,`created_by`,`status`,`created_on`) 
				VALUES ('". $meeting_name ."','".$meeting_owner."',1,'". date("Y-m-d h:i:s")."')";
				db_query($insql, db());
				$meeting_id=tep_db_insert_id();
				$insql_atten = "INSERT INTO `meeting_attendees`(`attendee_id`,`meeting_id`) 
				VALUES ('". $meeting_owner ."','".$meeting_id."')";
				db_query($insql_atten, db());
				$insql_page = "INSERT INTO `meeting_pages`(`meeting_id`,`page_type`,`page_title`,`page_subheading`,`duration`,`order_no`,`created_by`) 
				VALUES ('".$meeting_id."','Check-in','Check-in','Check-in','5',1,'".$_COOKIE["b2b_id"]."'),
				('".$meeting_id."','Projects','Projects','Projects','10',2,'".$_COOKIE["b2b_id"]."'),
				('".$meeting_id."','Metrics','Metrics','Metrics','10',3,'".$_COOKIE["b2b_id"]."'),
				 ('".$meeting_id."','Task','Task','Task','5',4,'".$_COOKIE["b2b_id"]."'),
				 ('".$meeting_id."','Issues','Issues','Issues','10',5,'".$_COOKIE["b2b_id"]."'),
				 ('".$meeting_id."','Conclude','Wrap-up','Wrap-up','5',6,'".$_COOKIE["b2b_id"]."')";
				db_query($insql_page, db());

				}else{
					$res=0;
				}
		}else{
			$insql = "Update `meeting_master` set meeting_name='". $meeting_name ."' where id= $meeting_id";
			db_query($insql, db());	
		}
		echo json_encode(array('meeting_id'=>new_dash_encrypt($meeting_id), 'result'=>$res));
	}

	if(isset($_POST["meeting_attendees_list"]) && $_POST["meeting_attendees_list"] == 1){
		$att_sql_main=db_query('SELECT attendee_id from meeting_attendees where meeting_id ="'.$_POST['meeting_id'].'"', db());
		$already_added_attendee_array=[];
		while($att_row = array_shift($att_sql_main)){
			$already_added_attendee_array[]=$att_row['attendee_id'];
		}
		$already_added_attendee=implode(',',$already_added_attendee_array);
		$sql = "SELECT Headshot, name,initials,b2b_id FROM loop_employees where status='Active' && b2b_id NOT IN($already_added_attendee)";
		$sql_main = db_query($sql,db() );
		$result=array();
		while($hrow = array_shift($sql_main)){
			$empname=$hrow["name"];
			$emp_b2b_id=$hrow["b2b_id"];
			$empDetails=getOwerHeadshotForMeeting($hrow["Headshot"],$hrow["initials"]);
			$result[]=array('emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt'], 'empname'=>$empname,'emp_b2b_id'=>$emp_b2b_id);
		}
		echo json_encode($result);
	}

	if(isset($_POST["add_meeting_attendees"]) && $_POST["add_meeting_attendees"] == 1){
		foreach($_POST['attendees'] as $attendee_id){
			$insql_atten = "INSERT INTO `meeting_attendees`(`attendee_id`,`meeting_id`) VALUES ('".$attendee_id."','".$_POST['meeting_id']."')";
			db_query($insql_atten, db());
		}
		$res=getAttendeeInfo($_POST['meeting_id']);
		echo json_encode($res);
	}
	if(isset($_POST["delete_meeting_attendees"]) && $_POST["delete_meeting_attendees"] == 1){
		$sql_delete_old_milestone = "DELETE FROM meeting_attendees WHERE id = '" . $_POST['meeting_attendee_id']. "'";
		$result = db_query($sql_delete_old_milestone,db());
		$res=getAttendeeInfo($_POST['meeting_id']);
		echo json_encode($res);
	}

	if(isset($_POST["page_action"]) && $_POST["page_action"] != ""){
		$meeting_id=$_POST['meeting_id'];
		if($_POST["page_action"] == "DELETE"){
			$delete_page="DELETE FROM meeting_pages WHERE page_id = '" . $_POST['page_id']. "'";
			$result = db_query($delete_page,db());
		}else if($_POST["page_action"]=="ADD"){
			//echo "SELECT order_no FROM `meeting_pages` WHERE meeting_id=$meeting_id order by order_no DESC limit 1";
			$last_inserted_order=db_query("SELECT order_no FROM `meeting_pages` WHERE meeting_id=$meeting_id order by order_no DESC limit 1",db());
			$order_no=array_shift($last_inserted_order)['order_no']+1;
			$insql_page = "INSERT INTO `meeting_pages`(`meeting_id`,`page_type`,`page_title`,`page_subheading`,`duration`,`order_no`,`created_by`) 
			VALUES ('".$meeting_id."','".$_POST['page_type']."','".$_POST['page_title']."','".$_POST['page_subheading']."','".$_POST['page_duration']."','".$order_no."','".$_COOKIE["b2b_id"]."')";
			db_query($insql_page, db());	
		}else if($_POST["page_action"]== "EDIT"){
			$update_page="UPDATE meeting_pages set page_type='".$_POST['page_type']."',page_title='".$_POST['page_title']."',
			page_subheading='".$_POST['page_subheading']."',duration='".$_POST['page_duration']."' where page_id='".$_POST['page_id']."'";
			db_query($update_page, db());
		}
		$res=getPageInfo($meeting_id);
		echo json_encode($res);
	}
	if(isset($_POST['fav_meeting_action']) and $_POST['fav_meeting_action']==1){
		//echo 'UPDATE meeting_master SET fav_status="'.$_POST['fav_status'].'" WHERE id="'.$_POST['meet_id'].'" ';
		//db_query('UPDATE meeting_master SET fav_status="'.$_POST['fav_status'].'" WHERE id="'.$_POST['meet_id'].'" ',db());
		$meeting_id=$_POST['meeting_id'];
		$fav_status=$_POST['fav_status'];
		$check_already_marked = db_query("SELECT id,fav_status FROM meeting_favourite_mark where meeting_id=$meeting_id && attendee_id='".$_COOKIE['b2b_id']."'",db());
		if(tep_db_num_rows($check_already_marked)>0){
			while($row = array_shift($check_already_marked)){
				db_query("UPDATE `meeting_favourite_mark` set fav_status=$fav_status where id='".$row['id']."'",db());
			}
		}else{
			db_query("INSERT INTO `meeting_favourite_mark` ( `meeting_id`, `attendee_id` ,`fav_status`) VALUES ($meeting_id,'".$_COOKIE['b2b_id']."',$fav_status)",db());
		}
		echo 1;
	}
	if(isset($_REQUEST['page_sort_action']) and $_REQUEST['page_sort_action']=="updateSortedRowsOfPage"){
		$newOrder   =   explode(",",$_REQUEST['sortOrder']);
		$n  =   '1';
		foreach($newOrder as $id){
			//echo 'UPDATE meeting_pages SET order_no="'.$n.'" WHERE page_id="'.$id.'" ';
			db_query('UPDATE meeting_pages SET order_no="'.$n.'" WHERE page_id="'.$id.'" ',db());
			$n++;
		}
		echo 1;
	}
	function update_page_action_on_live_update($current_page,$meeting_timer_id){
		$updated_data_of="";
		switch($current_page){
			case "meeting_projects.php": $updated_data_of=",project_flg=0";break;
			case "meeting_metrics.php":  $updated_data_of=",metrics_flg=0";break;
			case "meeting_tasks.php": 	$updated_data_of=",task_flg=0";break;
			case "meeting_issues.php":  $updated_data_of=",issue_flg=0";break;
			case "meeting_conclude.php":  $updated_data_of=",rating_flg=0";
		}
		$attendee_id=$_COOKIE['b2b_id'];
		db_query("UPDATE meeting_live_updates set current_page='".$current_page."' $updated_data_of where meeting_timer_id=$meeting_timer_id && attendee_id=$attendee_id",db());

	}
	if(isset($_GET['page_change_action']) and $_GET['page_change_action']=='update_page_change'){
		$page_type=$_GET['page_type'];
		$current_page=$_GET['current_page'];
		$meeting_timer_id=$_GET['meeting_timer_id'];
		update_page_action_on_live_update($current_page,$meeting_timer_id);
		update_meeting_minutes($_GET['meeting_id'],$meeting_timer_id,'Change Page',$page_type,'',$_COOKIE['b2b_id']); 
		$res=0;
		if($_COOKIE['b2b_id']==get_meeting_owner($meeting_timer_id)){
			$res=1;
			db_query("UPDATE meeting_live_updates set owner_page_flg=1 where meeting_timer_id='".$meeting_timer_id."' && attendee_id!=".$_COOKIE['b2b_id'],db());
			} 
		echo $res;
	}

	if(isset($_GET['update_owner_page_time_counter']) and $_GET['update_owner_page_time_counter']==1){
		db_query("UPDATE meeting_time_spent set time_spent='".$_GET['time_spent']."' where id=".$_GET['table_page_id'],db());
		db_query("UPDATE meeting_live_updates set owner_page_timer_flg=1 where meeting_timer_id='".$_GET['meeting_timer_id']."' && attendee_id!=".$_COOKIE['b2b_id'],db());	
		echo 1;
	}

	if(isset($_GET['update_page_refresh']) and $_GET['update_page_refresh']==1){
		$current_page=$_GET['current_page'];
		update_page_action_on_live_update($current_page,$_GET['meeting_timer_id']);
		echo 1;
	}
	
	if(isset($_REQUEST['finish_meeting_action']) and $_REQUEST['finish_meeting_action']==1){
		
		$meeting_timer_id=$_REQUEST['meeting_timer_id'];
		$meeting_id=$_REQUEST['meeting_id'];
		$task_percentage=display_meeting_task_percentage($meeting_id,$meeting_timer_id);
		$archive_str="";
		if($_REQUEST['archive_completed_meeting_todo']=='yes'){
			$archive_str=" , archive_status=1";
		}
		$update_task_sql = "UPDATE `task_master` SET `task_status` = 1 $archive_str WHERE task_meeting=".$meeting_id." && (task_status = 2 || `task_status` = 1)";
		$result = db_query($update_task_sql, db());

		$upsql = "UPDATE `meeting_timer` SET `completed_task_percentage`='".$task_percentage."',`end_time` ='". date('Y-m-d H:i:s') . "', `meeting_flg` = '1' , `meeting_end_by` = '". $_COOKIE['b2b_id'] ."' WHERE id=".$meeting_timer_id;
		$result = db_query($upsql, db());
		
		$send_meeting_email_to=$_REQUEST['send_meeting_email_to'];
		if($send_meeting_email_to==1 || $send_meeting_email_to==2){
			$conclusion_data=display_meeting_conclusion_data($meeting_id,$meeting_timer_id);
			$meeting_name_qry=db_query("SELECT meeting_name FROM meeting_master where id = $meeting_id",db());
			$meeting_name=array_shift($meeting_name_qry)['meeting_name'];
			$rating_filter="";
			if($send_meeting_email_to==2){
				$rating_filter=" and (rating!=0 || rating!='')";
			}
			$email_msg="<html><head> 
			<style>
			.bg-gray{background-color: #DDD;}.p20{padding:20px;}.main_div{background-color: #FFF;}
			.meeting-summary{display:flex; justify-content:center; align-items:center; padding:30px 50px; background:#DDD; width:70%; margin:0px auto;}
			.table_css{width: 100%;}.text-danger{color:#e74a3b;}
			.main_heading{text-align: center;margin-top: 40px;}
			.main_heading  hr{border-bottom: solid 1.5px #000;}.margint-15{margin:15px 0px 0px}
			.full_word{word-spacing: nowrap;} .text-center{text-align:center;}</style></head>";
			$email_msg.='<body class="bg-gray p20"><div class="main_div p20"><div class="text-center"><img src="images/new-ucb-header-logo.jpg"/><hr></div><p>Hi</p>';
			$email_msg.='<p>Here\'s your meeting summary for ' .$meeting_name.'</p>';
			$email_msg.='<div class="bg-gray meeting-summary">';
			$email_msg.='<div style="padding:0px 20px; width:40%"><h4 class="margint-15"> Issues solved </h4><h3 class="margint-15">'.$conclusion_data['issue_solved'].'</h3><br><h4 class="margint-15"> Average Rating</h4><h3 class="margint-15">'.$conclusion_data['rating'].'</h3></div>';
			$email_msg.='<div style="padding:0px 20px; width:40%"><h4 class="margint-15">To-do completion</h4><h3 class="margint-15">'.$task_percentage.'%</h3><br><h4 class="margint-15"> Minutes</h4><h3 class="margint-15">'.$conclusion_data['minutes'].'</h3></div>';
			$email_msg.='</div>';
			$email_msg.='<section>';
			$email_msg.='<div class="main_heading"><h4>To-dos</h4><hr></div>';
			$task_sql=db_query("SELECT task_duedate,task_title, name FROM task_master JOIN loop_employees ON task_master.task_assignto=loop_employees.b2b_id where task_meeting=$meeting_id and archive_status=0 ORDER BY task_master.id DESC", db());
			if(tep_db_num_rows($task_sql)>0){
				$email_msg.='<table class="table_css"><tbody>';
				while($r = array_shift($task_sql)){
					$email_msg.="<tr><td><b>".$r['name']."</b><br>".$r['task_title']."</td><td class='full_word ".get_status_date_color_info_task($r['task_duedate'])."'>".date("m-d-Y", strtotime($r['task_duedate']))."</td></tr>";
				} 
				$email_msg.='</tbody></table>';
			}else{
				$email_msg.='<div class="text-danger text-center">No Task</div>';
			}
			$email_msg.='</section>';
			$email_msg.='<section>';
			$email_msg.='<div class="main_heading"><h4>Issue Solved</h4><hr></div>';
			$qry_issue=db_query("SELECT im.issue, name from issue_master as im JOIN meeting_minutes as mm ON mm.update_on_id=im.id JOIN loop_employees ON im.created_by=loop_employees.b2b_id where mm.meeting_timer_id=$meeting_timer_id && update_msg='Issue Marked Solved'",db());
			if(tep_db_num_rows($qry_issue)>0){
				$email_msg.='<table class="table_css"><tbody>';
				$i=1;
				while($r = array_shift($qry_issue)){
					$email_msg.="<tr><td>".($i++).". ".$r['issue']."</td><td class='full_word'><b>".$r['name']."</b></td></tr>";
				}
				$email_msg.='</tbody></table>';
			}else{
				$email_msg.= '<div class="text-danger text-center">No Issue</div>';
			}
			$email_msg.='</section>';
			$email_msg.='</div>';
			$email_msg.='<p class="text-center"><small>This message was generated automatically<br>.If you feel you have received this message in error you can respond to this email.</small></p>';
			$email_msg.='</div></body></html>';
		//echo "SELECT attendee_id,email FROM meeting_start_atten_ratings as ms JOIN loop_employees ON loop_employees.b2b_id=ms.attendee_id where ms.meeting_timer_id=$meeting_timer_id $rating_filter";
			$attendee_qry=db_query("SELECT attendee_id,email,name FROM meeting_start_atten_ratings as ms JOIN loop_employees ON loop_employees.b2b_id=ms.attendee_id where ms.meeting_timer_id=$meeting_timer_id $rating_filter",db());
			$rec_array=[];
			while($r=array_shift($attendee_qry)){
				$rec_array[]=$r['email'];
			}
			
			//$recipient =implode(',', $rec_array);
			$recipient = "prasad@extractinfo.com";
			$subject = "Meeting Summary : $meeting_name";
			$mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
			$mailheadersadmin.= "MIME-Version: 1.0\r\n";
			$mailheadersadmin.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$resp = sendemail_php_function(null, '', $recipient, "", "", "ucbemail@usedcardboardboxes.com", "Usedcardboardboxes", "operations@UsedCardboardBoxes.com", $subject, $email_msg); 
		}
		
		$data=array('tid'=>new_dash_encrypt($_REQUEST["meeting_timer_id"]),'mid'=>new_dash_encrypt($_REQUEST["meeting_id"]));
		db_query("UPDATE meeting_live_updates set meeting_flg=1 where meeting_timer_id=".$meeting_timer_id,db());
		db_query("UPDATE meeting_live_updates set meeting_flg=2 where meeting_timer_id=".$meeting_timer_id." && attendee_id=".$_COOKIE['b2b_id'],db());
		update_meeting_minutes($_REQUEST['meeting_id'],$meeting_timer_id,'Meeting Ended','Meeting Ended',"",$_COOKIE['b2b_id']);
		echo json_encode($data); 
	}

	if(isset($_GET['leave_meeting_action']) and $_GET['leave_meeting_action']==1){
		$upsql = "UPDATE `meeting_start_atten_ratings` SET `join_status` = 2 WHERE meeting_timer_id=".$_GET['meeting_timer_id']." && attendee_id=". $_COOKIE["b2b_id"] ;
		$result = db_query($upsql, db());
		db_query("UPDATE meeting_live_updates set attendee_flg=1 where meeting_timer_id=".$_GET['meeting_timer_id'],db());
		update_meeting_minutes($_REQUEST['meeting_id'],$_REQUEST['meeting_timer_id'],'Left Meeting','Left Meeting',"",$_COOKIE['b2b_id']);
		echo 1;
	}

	if(isset($_GET['fetch_attendee_of_meeting']) and $_GET['fetch_attendee_of_meeting']==1){
		echo json_encode(getAttendeeInfo($_GET['meeting_id']));
	}

	if(isset($_GET['update_rating']) and $_GET['update_rating']==1){
		$meeting_timer_id=$_GET['meeting_timer_id'];
		db_query('UPDATE meeting_start_atten_ratings set rating = "'.$_GET['rating'].'" where id="'.$_GET['rating_table_id'].'"',db());
		update_meeting_minutes($_GET['meeting_id'], $meeting_timer_id,'Rating','Rating Done',$_GET["rating_table_id"],$_COOKIE['b2b_id']);  
		db_query("UPDATE meeting_live_updates set rating_flg=1 where meeting_timer_id='".$meeting_timer_id."' && attendee_id!='".$_COOKIE['b2b_id']."'",db());
		echo json_encode(getRatingData($meeting_timer_id));
	}

	if(isset($_GET['check_for_meeting_start_updates']) && $_GET['check_for_meeting_start_updates']==1){
		echo json_encode(getStartedMeetingsForNotification());
	}

	if(isset($_GET['hide_notification_for_meeting_start_updates']) && $_GET['hide_notification_for_meeting_start_updates']!=""){
		if($_GET['hide_notification_for_meeting_start_updates']==1){
			$sql1 = db_query("UPDATE meeting_start_atten_ratings set notification_status=1 where attendee_id=".$_COOKIE['b2b_id']." && meeting_timer_id=".$_GET['meeting_timer_id'], db());		
		}else{
			$sql1 = db_query("UPDATE meeting_start_atten_ratings set notification_status=2 where attendee_id=".$_COOKIE['b2b_id']." && meeting_timer_id=".$_GET['meeting_timer_id'], db());		
		}
		echo json_encode(getStartedMeetingsForNotification());
	}

	if(isset($_GET['get_live_meeting_status_updates']) && $_GET['get_live_meeting_status_updates']==1){
		$res=[];
		$com_str= "(mt.start_time > '".date('Y-m-d H:i:s', strtotime('-60 sec'))."' || mt.end_time > '".date('Y-m-d H:i:s', strtotime(' -60 sec'))."' )";	

		if($_GET['data_of']=='all'){
			$meeting_filter="";
			if($_GET['emp_level']!=2){
				$meeting_filter=" and ma.attendee_id='".$_COOKIE['b2b_id']."'";
			}
			//echo "SELECT mt.meeting_id,mt.id,mt.meeting_flg from meeting_timer as mt JOIN meeting_attendees as ma ON mt.meeting_id= ma.meeting_id where $com_str $meeting_filter GROUP By ma.meeting_id";
			
			$updated_meetings_qry=db_query("SELECT mt.meeting_id,mt.id,mt.meeting_flg from meeting_timer as mt JOIN meeting_attendees as ma ON mt.meeting_id= ma.meeting_id where $com_str $meeting_filter GROUP By ma.meeting_id",db());
			if(tep_db_num_rows($updated_meetings_qry)>0){
				while($row = array_shift($updated_meetings_qry)){
					$result['meeting_id']=$row['meeting_id'];
					$result['meeting_timer_id']=$row['id'];
					$result['meeting_flg']=$row['meeting_flg'];
					$result['meeting_id_enc']=new_dash_encrypt($row['meeting_id']);
					$result['meeting_timer_id_enc']=new_dash_encrypt($row['id']);
					$res[]=$result;
				}
			}
		}
		if($_GET['data_of']=='single'){
			$meeting_id=new_dash_decrypt($_GET['meeting_id']);
			$updated_meetings_qry=db_query("SELECT mt.meeting_id,mt.id,mt.meeting_flg from meeting_timer as mt where $com_str and meeting_id=$meeting_id ORDER BY id DESC limit 1",db());
			if(tep_db_num_rows($updated_meetings_qry)>0){
				while($row = array_shift($updated_meetings_qry)){
					$result['meeting_id']=$row['meeting_id'];
					$result['meeting_timer_id']=$row['id'];
					$result['meeting_flg']=$row['meeting_flg'];
					$result['meeting_id_enc']=new_dash_encrypt($row['meeting_id']);
					$result['meeting_timer_id_enc']=new_dash_encrypt($row['id']);
					$res[]=$result;
				}
			}
		}
		echo json_encode($res);
	}
	
	if(isset($_GET['get_live_data_of_meeting']) && $_GET['get_live_data_of_meeting']==1){
		$meeting_timer_id=$_GET['meeting_timer_id'];
		$attendee_id=$_COOKIE['b2b_id'];
		$meeting_id=$_GET['meeting_id'];
		$flg_update=db_query("SELECT * FROM meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		$update_data_array=array_shift($flg_update);
		$current_page=$update_data_array['current_page'];
		$project_data_array=array();
		$project_flg=0;
		if($current_page=="meeting_projects.php" && $update_data_array['project_flg']==1){
			$project_flg=1;
			$project_data_array=getMeetingProjectDataStartMeetingAfterAction($meeting_id);
			db_query("UPDATE meeting_live_updates set project_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		}
		$task_data_array=array('data'=>array());
		$task_flg=0;
		$sort_task=0;
		if($current_page=="meeting_tasks.php" && $update_data_array['task_flg']==1){
			$task_flg=1;
			$order_str=$update_data_array['task_order']==""?"ORDER BY id DESC": $update_data_array['task_order'];
			$task_data_array=getMeetingTaskDataStartMeetingAfterAction($meeting_id,$order_str.",id DESC",$meeting_timer_id);
			db_query("UPDATE meeting_live_updates set task_flg=1 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
			switch($order_str){
				case "ORDER BY task_assignto ASC" : $sort_task=1; break; 
				case "ORDER BY task_status ASC" : $sort_task=2; break; 
				case "ORDER BY task_duedate DESC" : $sort_task=3; break; 
				case "ORDER BY task_duedate ASC" : $sort_task=4; break; 
				case "ORDER BY task_entered_on ASC" : $sort_task=5;break;  
				case "ORDER BY task_entered_on DESC" : $sort_task=6; 
			}
		}
		$issue_data_array=array();
		$issue_flg=0;
		$sort_issue=0;
		$show_issue_number=$update_data_array['show_issue_number'];
		if($current_page=="meeting_issues.php" && $update_data_array['issue_flg']==1){
			$issue_flg=1;
			$issue_order=$update_data_array['issue_order'];
			$order_str=$issue_order==""?"ORDER BY id DESC":$issue_order;
			$issue_data_array=getMeetingIssueDataStartMeetingAfterAction($meeting_id,$order_str.",id DESC");
			db_query("UPDATE meeting_live_updates set issue_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
			switch($order_str){
				case "ORDER BY order_no<1 ASC,order_no" : $sort_issue=1; break; 
				case "ORDER BY created_by ASC" : $sort_issue=2; break; 
				case "ORDER BY created_on ASC" : $sort_issue=3; break; 
				case "ORDER BY created_on DESC" : $sort_issue=4; break; 
				case "ORDER BY issue ASC" : $sort_issue=5;  
			}
		}
        $metrics_flg=0;
        $metrics_data_array = [];
        if($current_page=="meeting_metrics.php" && $update_data_array['metrics_flg']==1){
            $metrics_flg=1;
			$metrics_data_array=getMetricsDataAfterStartMeetingAction($meeting_id);
			db_query("UPDATE meeting_live_updates set metrics_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		}
		$rating_data_array=array();
		$rating_flg=0;
		if($current_page=="meeting_conclude.php" && $update_data_array['rating_flg']==1){
			$rating_flg=1;
			$rating_data_array=getRatingData($meeting_timer_id);
			db_query("UPDATE meeting_live_updates set rating_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		}
		$owner_current_page="";
		$owner_page_flg=0;
		if($update_data_array['owner_page_flg']==1){
			$owner_page_flg=1;
			$current_page_of_owner_sql=db_query("SELECT current_page from meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id=".get_meeting_owner($meeting_timer_id), db());
			$owner_current_page=array_shift($current_page_of_owner_sql)['current_page'];
			db_query("UPDATE meeting_live_updates set owner_page_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		}

		$owner_page_timer_flg=0;
		$time_spent_by_owner_array=array();
		if($update_data_array['owner_page_timer_flg']==1){
			$owner_page_timer_flg=1;
			db_query("UPDATE meeting_live_updates set owner_page_timer_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
			$select_time=db_query("SELECT id,time_spent from meeting_time_spent where meeting_timer_id=".$meeting_timer_id,db());
			while($r = array_shift($select_time)){
				$time_spent_by_owner_array[]=$r;
			}
		}
		$attendee_flg=0;
		$attendee_data=array();
		if($update_data_array['attendee_flg']==1){
			$attendee_flg=1;
			$attendee_data=getOnlineAttendee($meeting_timer_id);
			db_query("UPDATE meeting_live_updates set attendee_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
		}

		$meeting_flg=0;
		$current_meeting_data=array();
		if($update_data_array['meeting_flg']==1){
			$meeting_flg=2;
			db_query("UPDATE meeting_live_updates set meeting_flg=2 where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$attendee_id."'",db());
			$current_meeting_data=array('tid'=>new_dash_encrypt($meeting_timer_id),'mid'=>new_dash_encrypt($meeting_id));
		}
		echo json_encode(array(
			'project_data'=>$project_data_array,'project_flg'=>$project_flg,
			'task_data'=>$task_data_array,'task_flg'=>$task_flg,'sort_task'=>$sort_task,
			'issue_data'=>array('data'=>$issue_data_array,'show_issue_number'=>$show_issue_number),'issue_flg'=>$issue_flg,'sort_issue'=>$sort_issue,
			'rating_data'=>$rating_data_array,'rating_flg'=>$rating_flg,
			'metrics_data'=>$metrics_data_array,'metrics_flg'=>$metrics_flg,
			'owner_page_flg'=>$owner_page_flg,'owner_current_page'=>$owner_current_page,
			'owner_page_timer_flg'=>$owner_page_timer_flg,'time_spent_by_owner_array'=>$time_spent_by_owner_array,
			'attendee_flg'=>$attendee_flg,'attendee_data'=>$attendee_data,
			'meeting_flg'=>$meeting_flg,'current_meeting_data'=>$current_meeting_data
			));
	}
    // Matriccs Orderig Method 
    if(isset($_REQUEST['matircs_sort_action']) && $_REQUEST['matircs_sort_action']=="matricsTableOrdering"){
        if(isset($_REQUEST['meetingTimerID'])){
            db_query("UPDATE meeting_live_updates set metrics_flg=1 where meeting_timer_id=".new_dash_decrypt($_REQUEST['meetingTimerID'])." AND attendee_id!=".$_COOKIE['b2b_id']."",db());
        }
        $newOrder =  explode(",",$_REQUEST['sortOrder']);
		$n =  1;
		foreach($newOrder as $id){
            db_query('UPDATE scorecard SET meeting_create_order_no='.$n.' WHERE id='.new_dash_decrypt($id).' ',db());
			$n++;
		}
		echo 1;
	}

    if(isset($_REQUEST['das_meeting_matrics']) and $_REQUEST['das_meeting_matrics']=="deleteMatrix"){

        $encryptedDeleteID = $_POST['matrixID'];
        $meetingID = $_POST['meetingID'];

        if(isset($encryptedDeleteID) && $encryptedDeleteID != '' && isset($meetingID) && $meetingID != 0){
            $deleteID = new_dash_decrypt($encryptedDeleteID);
            $fetchDeleteSQL = "SELECT id,attach_meeting FROM `scorecard` where (attach_meeting like '%-".$meetingID."-%' OR attach_meeting like '%-".$meetingID."' OR attach_meeting like '".$meetingID."-%' OR attach_meeting = ".$meetingID.") AND (id = ".$deleteID.")";
            // $fetchDeleteSQL = "SELECT id,attach_meeting FROM `scorecard` where (attach_meeting like '%-".$meetingID."-%' OR attach_meeting like '%-".$meetingID."' OR attach_meeting like '".$meetingID."-%' OR attach_meeting = ".$meetingID.") AND (`b2b_id` = ".$_COOKIE['b2b_id']." AND id = ".$deleteID.")";
            $fetchDelete_query = db_query($fetchDeleteSQL,db());

            if(!empty($fetchDelete_query)){

                $fetchDeleteData = array_shift($fetchDelete_query);

                if((int)$fetchDeleteData['id'] === (int)$deleteID){
                    $explodedDeletedValue = explode('-' ,$fetchDeleteData['attach_meeting']);
                    if(isset($explodedDeletedValue) && isset($explodedDeletedValue[1])){
                        $index = array_search($meetingID, $explodedDeletedValue);
                        if ($index !== false) {
                            array_splice($explodedDeletedValue, $index, 1);
                        }
                        $outputString = implode("-", $explodedDeletedValue);
                        if(isset($outputString) &&  $outputString != ''){
                            // $sqlUpdate = "UPDATE `scorecard` SET `attach_meeting` = '".$outputString."' WHERE `scorecard`.`id` = ".$deleteID." AND `scorecard`.`b2b_id` = ".$_COOKIE['b2b_id']."";
                            $sqlUpdate = "UPDATE `scorecard` SET `attach_meeting` = '".$outputString."' WHERE `scorecard`.`id` = ".$deleteID."";
                            $sqlUpdate_query = db_query($sqlUpdate,db());
                            // echo "Updating";
                        }
                    }else{
                        if(isset($explodedDeletedValue[0]) &&  $explodedDeletedValue[0] != ''){
                            // $singleMeetingSql = "DELETE FROM `scorecard` WHERE `scorecard`.`attach_meeting` = ".$meetingID." AND `scorecard`.`b2b_id` = ".$_COOKIE['b2b_id']." AND `scorecard`.`id` = ".$deleteID."";
                            // $singleMeetingSql = "DELETE FROM `scorecard` WHERE `scorecard`.`attach_meeting` = ".$meetingID." AND `scorecard`.`id` = ".$deleteID."";
                            $singleMeetingSql = "UPDATE `scorecard` SET `archived` = true WHERE `scorecard`.`id` = ".$deleteID."";
                            $singleMeetingSql_query = db_query($singleMeetingSql,db());
                            // echo "Deleting";
                        }
                    }
                    echo json_encode(['status' => "Success", 'message' => "Deleted Successfully"]);
                }else{
                    // Error
                    echo json_encode(['status' => "Error", 'message' => "ID Mismatch"]);
                }
            }
        }

    }

	if(isset($_GET['archive_meeting']) && $_GET['archive_meeting']==1){
		db_query("UPDATE meeting_master set status=0 where id=".$_GET['meeting_id'],db());
		echo 1;
	}

	if(isset($_GET['edit_vto']) && $_GET['edit_vto']=='usedcarboardboxes_vto'){
		$vto_data_sql=db_query("SELECT id,title,description FROM usedcarboardboxes_vto where id=".$_GET['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_POST['update_vto']) && $_POST['update_vto']=='usedcarboardboxes_vto'){
		$insql = "Update `usedcarboardboxes_vto` set `title` = '". str_replace("'", "\'" , $_POST["title"]) ."', `description` = '". str_replace("'", "\'" ,$_POST["description"]) ."' where id = ".$_POST['id'];
        db_query($insql, db());
		$vto_data_sql=db_query("SELECT id,title,description FROM usedcarboardboxes_vto where id=".$_POST['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}

	if(isset($_GET['edit_vto']) && $_GET['edit_vto']=='ucbzerowaste_vto'){
		$vto_data_sql=db_query("SELECT id,title,description FROM ucbzerowaste_vto where id=".$_GET['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_POST['update_vto']) && $_POST['update_vto']=='ucbzerowaste_vto'){
		$insql = "Update `ucbzerowaste_vto` set `title` = '". str_replace("'", "\'" , $_POST["title"]) ."', `description` = '". str_replace("'", "\'" ,$_POST["description"]) ."' where id = ".$_POST['id'];
        db_query($insql, db());
		$vto_data_sql=db_query("SELECT id,title,description FROM ucbzerowaste_vto where id=".$_POST['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_GET['edit_vto']) && $_GET['edit_vto']=='2ndkid_vto'){
		$vto_data_sql=db_query("SELECT id,title,description FROM 2ndkid_vto where id=".$_GET['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_POST['update_vto']) && $_POST['update_vto']=='2ndkid_vto'){
		$insql = "Update `2ndkid_vto` set `title` = '". str_replace("'", "\'" , $_POST["title"]) ."', `description` = '". str_replace("'", "\'" ,$_POST["description"]) ."' where id = ".$_POST['id'];
        db_query($insql, db());
		$vto_data_sql=db_query("SELECT id,title,description FROM 2ndkid_vto where id=".$_POST['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	if(isset($_GET['edit_meeting_vto']) && $_GET['edit_meeting_vto']==1){
		$select_str="";
		if($_GET['edit_type']=="edit_goal"){
			$select_str= ",goals_for_year_title as title ,goals_for_the_year as description";
		}else if($_GET['edit_type']=="edit_rock"){
			$select_str= ",quarterly_rock_title as title,quarterly_rock as description";
		}else if($_GET['edit_type']=="edit_issue"){
			$select_str= ",vto_issue_title as title,vto_issue as description";
		}
		$vto_data_sql=db_query("SELECT id $select_str FROM meeting_vto where id=".$_GET['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_POST['update_meeting_vto']) && $_POST['update_meeting_vto']==1){
		$update_str="";
		if($_POST['edit_type']=="edit_goal"){
			$update_str= "goals_for_year_title='". str_replace("'", "\'" , $_POST["title"])."' , goals_for_the_year='".$_POST["description"]."'";
		}else if($_POST['edit_type']=="edit_rock"){
			$update_str= "quarterly_rock_title='". str_replace("'", "\'" , $_POST["title"])."' , quarterly_rock='".$_POST["description"]."'";
		}else if($_POST['edit_type']=="edit_issue"){
			$update_str= "vto_issue_title='". str_replace("'", "\'" , $_POST["title"])."' , vto_issue='".$_POST["description"]."'";
		}

		//echo "Update `meeting_vto` set $update_str where id = ".$_POST['id'];
		db_query("Update `meeting_vto` set $update_str where id = ".$_POST['id'],db());
		$vto_data_sql=db_query("SELECT * FROM meeting_vto where id=".$_POST['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	

	if(isset($_GET['edit_core_value']) && $_GET['edit_core_value']==1){
		$core_values_data_sql = db_query("SELECT id,title,description FROM core_values where id=".$_GET['id'],db());
		$data=[];
		while($r = array_shift($core_values_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	
	if(isset($_POST['update_core_value']) && $_POST['update_core_value']==1){
		$insql = "Update `core_values` set `title` = '". str_replace("'", "\'" , $_POST["title"]) ."', `description` = '". str_replace("'", "\'" ,$_POST["description"]) ."' where id = ".$_POST['id'];
        db_query($insql, db());
		$vto_data_sql=db_query("SELECT id,title,description FROM core_values where id=".$_POST['id'],db());
		$data=[];
		while($r = array_shift($vto_data_sql)){
			$data=$r;
		}
	    echo json_encode($data);
	}
?>
	