<?php 
    session_start();
    require ("mainfunctions/database.php"); 
    require ("mainfunctions/general-functions.php");
    require('meeting_common_function.php');
    if(isset($_GET['meeting_id']) && $_GET['meeting_id']!=""){
        $meeting_id=new_dash_decrypt($_GET['meeting_id']);
        $page_data=getMeetingStartLink($meeting_id);
        $current_page=$page_data['page_url'];
        $page_title=$page_data['page_title'];
        $meeting_timer_id="";
        $meeting_owner="";
        if(isset($_GET['meeting_timer_id']) && $_GET['meeting_timer_id']!=""){
            $meeting_timer_id=new_dash_decrypt($_GET['meeting_timer_id']);
            $meeting_timer_qry=db_query('SELECT meeting_flg,meeting_start_by from meeting_timer where id='.$meeting_timer_id,db_project_mgmt());
            $meeting_timer_data=array_shift($meeting_timer_qry);
            $meeting_owner=$meeting_timer_data['meeting_start_by'];
            $current_page=getMeetingOwnerPage($meeting_timer_id,$meeting_owner);
           
            if($meeting_timer_data['meeting_flg']==1){  
                echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=2';</script>";
            }else{
                $getOldJoinStatus_qry=db_query("SELECT join_status from meeting_start_atten_ratings where meeting_timer_id=$meeting_timer_id && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
                $getOldJoinStatus="";
				if(tep_db_num_rows($getOldJoinStatus_qry)>0){
					$update_staus=db_query("UPDATE meeting_start_atten_ratings set join_status=1 where meeting_timer_id=$meeting_timer_id and attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
					$getOldJoinStatus=array_shift($getOldJoinStatus_qry)['join_status'];
				}else{
					db_query("INSERT INTO meeting_start_atten_ratings (meeting_timer_id,attendee_id,join_status,rating) values('".$meeting_timer_id."','".$_COOKIE['b2b_id']."',1,'')",db_project_mgmt());
				}
				$update_attendee_flg=db_query("UPDATE meeting_live_updates set attendee_flg=1 where meeting_timer_id=$meeting_timer_id and attendee_id!=".$_COOKIE['b2b_id'],db_project_mgmt());  
			
			   if($getOldJoinStatus==2 || $getOldJoinStatus==1){
					update_meeting_minutes($meeting_id,$meeting_timer_id,'Join Meeting','Join Meeting',0,$_COOKIE['b2b_id']);  
                    update_meeting_minutes($meeting_id,$meeting_timer_id,'Change Page',$page_title,'',$_COOKIE['b2b_id']);
                    header('Location: '.$current_page.'?meeting_id='.$_GET["meeting_id"]."&meeting_timer_id=".new_dash_encrypt($meeting_timer_id));
				}
				else{
                    update_meeting_minutes($meeting_id,$meeting_timer_id,'Join Meeting','Join Meeting',0,$_COOKIE['b2b_id']);  
                }
            }
        }else{
            $meeting_owner=$_COOKIE['b2b_id'];
            $st_dtime = date("Y-m-d H:i:s");
            $meeting_on_check=db_query("SELECT id from meeting_timer where meeting_id=$meeting_id && meeting_flg=0",db_project_mgmt());
            if(tep_db_num_rows($meeting_on_check)>0){
                echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=3';</script>";
            }
            $task_sql=db_query("SELECT count(*) as task_count FROM task_master where task_master.task_status = 0 and archive_status=0 and task_meeting=$meeting_id", db_project_mgmt());
            $task_count=array_shift($task_sql)['task_count'];
            $insql1 = "INSERT INTO meeting_timer(meeting_id, start_time, meeting_flg,task_count,meeting_start_by) VALUES ('". $meeting_id."', '". $st_dtime."', '0', $task_count,'". $_COOKIE['b2b_id']."')";
            $inres1 = db_query($insql1, db_project_mgmt());
            $recid=tep_db_insert_id();
            $select_attendee_array=explode(',',$_GET['attendees_list']);
        

			if(!in_array($_COOKIE['b2b_id'],$select_attendee_array)){
                 $inst=db_query("INSERT INTO meeting_start_atten_ratings (meeting_timer_id,attendee_id,join_status,rating) values('".$recid."','".$_COOKIE['b2b_id']."',1,'')",db_project_mgmt());
			}
            foreach($select_attendee_array as $key=>$value){
                $join_status=0;
                if($value==$_COOKIE['b2b_id']){
                    $join_status=1;
                }
                $inst=db_query("INSERT INTO meeting_start_atten_ratings (meeting_timer_id,attendee_id,join_status,rating) values('".$recid."','".$value."','".$join_status."','')",db_project_mgmt());
            }
            $meeting_timer_id=$recid;
            $sql="INSERT INTO `meeting_minutes` (`meeting_id`,`meeting_timer_id`,`action`,`notes`,`update_on_id`,`updated_by`,`update_msg`) 
            values($meeting_id,$meeting_timer_id,'Start Meeting','Start Meeting',0,'".$_COOKIE['b2b_id']."','')";
            db_query($sql,db_project_mgmt());
            $select_pages=db_query("SELECt page_id from meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC",db_project_mgmt());
            while($res = array_shift($select_pages)){
                $insert_time_spent=db_query("INSERT INTO meeting_time_spent (meeting_id, meeting_timer_id,page_id,time_spent) values($meeting_id,$meeting_timer_id,".$res['page_id'].", 0)",db_project_mgmt());
            }
        }
        
        $owner_page_flg=$meeting_owner == $_COOKIE['b2b_id'] ? 1 : 0;
        $update_flg_while_insert="";
        switch($current_page){
            case "meeting_projects.php": $update_flg_while_insert='project_flg';break;
            case "meeting_metrics.php":  $update_flg_while_insert='metrics_flg';break;
            case "meeting_tasks.php": $update_flg_while_insert='task_flg';break;
            case "meeting_issues.php": $update_flg_while_insert='issue_flg';break;
            case "meeting_conclude.php":  $update_flg_while_insert='rating_flg';
        }
        if($current_page=="meeting_checkin.php" || $current_page=="meeting_conclusion_finish.php"){
            db_query("INSERT into meeting_live_updates (meeting_timer_id,attendee_id,current_page,owner_page_flg,attendee_flg) values('".$meeting_timer_id."','".$_COOKIE['b2b_id']."','".$current_page."',$owner_page_flg,1)", db_project_mgmt());
        }else{
            db_query("INSERT into meeting_live_updates (meeting_timer_id,attendee_id,current_page,`".$update_flg_while_insert."`,owner_page_flg,attendee_flg) values('".$meeting_timer_id."','".$_COOKIE['b2b_id']."','".$current_page."',0,$owner_page_flg,1)", db_project_mgmt());
        }  
        update_meeting_minutes($meeting_id,$meeting_timer_id,'Change Page',$page_title,'',$_COOKIE['b2b_id']);
                   
        header('Location: '.$current_page.'?meeting_id='.$_GET["meeting_id"]."&meeting_timer_id=".new_dash_encrypt($meeting_timer_id));
        exit;
    }else{
        echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=1';</script>";
    }
?>