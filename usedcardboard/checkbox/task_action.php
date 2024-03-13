<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
require('meeting_common_function.php');

function getMeetingTaskDataAfterAction($meeting_id){
	$task_sql=db_query("SELECT task_master.id,task_master.task_status,task_title,task_assignto,task_entered_by FROM task_master where task_meeting=$meeting_id and archive_status=0  ORDER BY id DESC", db_project_mgmt());
	$result=array();
	while($r = array_shift($task_sql)){
		$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
        $empDetails_arr=array_shift($empDetails_qry);
        $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
		$result[]=array('task_title' => $r["task_title"],'task_status'=>$r['task_status'],'task_id'=>$r['id'],'name'=>$r['name'],'emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt']);
	}
	return $result;
}


function getAllTaskDataAfterAction(){
	$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
	$emp_level = array_shift($select_level)['level'];

	$meeting_filter = "";
	if($emp_level!=2){
		$meeting_filter =" and attendee_id='".$_COOKIE['b2b_id']."'";
	}
	
	$sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
	where mm.status = 1 $meeting_filter GROUP By ma.meeting_id
	union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name",db_project_mgmt());
	
    $result = array();
    while($main_row = array_shift($sql_main)){
        $meeting_id=$main_row['id'];
        $count_sql=db_query("SELECT id FROM task_master where task_meeting=$meeting_id AND task_assignto = '".$_COOKIE["b2b_id"]."' AND archive_status=0 ORDER BY id DESC", db_project_mgmt());
        $count=tep_db_num_rows($count_sql);
        if($count>0){
            $sql1 = db_query("SELECT id,task_title,	task_assignto, task_duedate, task_status FROM task_master where task_meeting=$meeting_id AND task_assignto = '".$_COOKIE["b2b_id"]."' and archive_status=0 ORDER BY id DESC LIMIT 10", db_project_mgmt());
            $data=array();
            while($r = array_shift($sql1)){
                $due_date_class=get_status_date_color_info_task($r['task_duedate']);
                $data[]=array(
                        'task_title'=>$r['task_title'],
                        'id'=>$r['id'],
                        'task_status'=>$r['task_status'],
                        'task_assignto'=>$r['task_assignto'],
                        'due_date_class'=> $due_date_class,
                        'task_duedate'=>date("m/d/Y", strtotime($r['task_duedate'])),
                );
            }
            $result[]=array('meeting_id'=>$main_row["id"],'meeting_name' => $main_row["meeting_name"],'count'=>$count,'data'=>$data);
        }
       }
    return $result;
}

function getAllTaskDataAfterWorkspaceAction($meeting_id,$show_filter_str=""){
    $sql1 = db_query("SELECT task_master.id,task_title,	task_assignto, task_duedate, task_status ,task_entered_by FROM task_master where task_meeting=$meeting_id AND archive_status=0 $show_filter_str ORDER BY id DESC ", db_project_mgmt());
    $data=array();
    while($r = array_shift($sql1)){
        $due_date_class=get_status_date_color_info_task($r['task_duedate']);
        $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
        $empDetails_arr=array_shift($empDetails_qry);
        $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
        $data[]=array(
                'task_title'=>$r['task_title'],
                'id'=>$r['id'],
                'task_status'=>$r['task_status'],
                'task_assignto'=>$r['task_assignto'],
                'due_date_class'=> $due_date_class,
                'task_duedate'=>date("m/d/Y", strtotime($r['task_duedate'])),
                'emp_img'=>$empDetails['emp_img'], 
				'emp_txt'=>$empDetails['emp_txt']
        );
    }
    return $data;
}

function getCountOfTaskForSidebarMenu(){
    $task_count_sql=db_query("SELECT id FROM task_master where archive_status=0 and task_assignto = '".$_COOKIE["b2b_id"]."' ", db_project_mgmt());
    $task_count=tep_db_num_rows($task_count_sql);
    return $task_count;
}

if(isset($_POST["task_action"]) && $_POST["task_action"] != ""){
    $title = str_replace("'", "\'", $_POST["task_title"]);
    $desc  = str_replace("'", "\'", $_POST["task_desc"]);
    $meeting_id=$_POST['task_meeting'];
    $insert_record_id =0;
    if($_POST["task_action"] == "ADD" || $_POST["task_action"] == "ADD_TASK_MEETING" ||$_POST["task_action"] == "ADD_FROM_WORKSPACE"){
        $insql = "INSERT INTO `task_master`(`task_title`, `task_details`, `task_assignto`,
            `task_duedate`,`task_priority`, `task_status`,`task_meeting`, `task_entered_by`) VALUES (
            '". $title ."', '". $desc ."', '". $_POST["assignto"] ."', 
            '". $_POST["task_duedate"] ."','". $_POST["task_priority"] ."',  0 , '".$meeting_id."','". $_COOKIE["b2b_id"] ."')";
        db_query($insql, db_project_mgmt());
        $insert_record_id = tep_db_insert_id();
    }
    else if($_POST["task_action"] == "EDIT" || $_POST['task_action']=="EDIT_TASK_MEETING" || $_POST['task_action']=="editFromWorkspace"){
        $insql = "Update `task_master` set `task_title` = '".$title."', `task_details` = '".$desc."',
        `task_assignto` = '". $_POST["assignto"] ."', `task_duedate` = '". $_POST["task_duedate"] ."', `task_priority`='".$_POST['task_priority']."',
         `task_meeting`='".$meeting_id."' where id = ".$_POST["task_id_edit"];
        db_query($insql, db_project_mgmt());
        $insert_record_id = $_POST['task_id_edit'];
    }


    if($_POST["task_action"] == "ADD_TASK_MEETING" || $_POST['task_action']=="EDIT_TASK_MEETING"){
        $result=getMeetingTaskDataAfterAction($meeting_id);
        echo json_encode($result);
    }else if($_POST["task_action"] == "ADD_FROM_WORKSPACE" || $_POST['task_action']=="editFromWorkspace" || $_POST['task_action']=="show_filter"){
        $show_filter_str="";
        if($_POST['only_mine']==1){
            $show_filter_str= "and task_master.task_assignto=".$_COOKIE['b2b_id'];
        }
        $result=getAllTaskDataAfterWorkspaceAction($meeting_id,$show_filter_str);
        echo json_encode($result);
    }else{
        $result = getAllTaskDataAfterAction();
        $taskCount=getCountOfTaskForSidebarMenu();
        echo json_encode(array('data'=>$result,'total_tasks'=>$taskCount));	
    }
}

if(isset($_REQUEST['load_type']) && $_REQUEST['load_type']=="task"){
    $result_array=[];
    $loaded_data=0;
    if(isset($_REQUEST['loaded_data']) && $_REQUEST['loaded_data']!=""){
        $loaded_data=$_REQUEST['loaded_data'];
    }
    $task_meeting_id=$_REQUEST['task_meeting_id'];
    $owner_id=$_COOKIE['b2b_id'];
    $sql1 = db_query("SELECT id,task_title,	task_assignto, task_duedate, task_status FROM task_master where task_meeting = $task_meeting_id AND task_assignto = '".$owner_id."' and archive_status=0 ORDER BY id DESC LIMIT 10 OFFSET $loaded_data", db_project_mgmt());
    while($r = array_shift($sql1)){
        $due_date_class=get_status_date_color_info_task($r['task_duedate']);
        $data=array(
            'task_title'=>$r['task_title'],
            'id'=>$r['id'],
            'task_status'=>$r['task_status'],
            'date_class'=>$date_class,
            'task_assignto'=>$r['task_assignto'],
            'due_date_class'=> $due_date_class,
            'task_duedate'=>date("m/d/Y", strtotime($r['task_duedate'])),
        );
        $result_array[]=$data;
    }
    echo json_encode($result_array);
}



if(isset($_GET['update_task_status']) && $_GET['update_task_status']==1){
    $task_completed_by=$_COOKIE['b2b_id'];
    $task_completed_on=date("Y-m-d h:i:s");
    if($_GET['task_status']==0){
        $task_completed_by="";
        $task_completed_on="";
    }
    $insql = "Update `task_master` set `task_status` = '". $_GET["task_status"] ."', `task_completed_by`='".$task_completed_by."', `task_completed_on`='".$task_completed_on."' where id = '". $_GET["task_id"] ."'";
   
    db_query($insql, db_project_mgmt());
    echo true;
}
if(isset($_GET['edit_task']) && $_GET['edit_task']==1){
    $task_id=$_GET['task_id'];
    $sql1 = db_query("SELECT * FROM task_master where id = $task_id " , db_project_mgmt());
    $data=[];
    while($r = array_shift($sql1)){
        $data=$r;
    }
    echo json_encode($data);
}
if(isset($_GET['delete_task']) && $_GET['delete_task']==1){
    $task_id=$_GET['task_id'];
    $sql_delete_task = "UPDATE task_master set archive_status=1 WHERE id = '" . $task_id . "'";
    db_query($sql_delete_task,db_project_mgmt());
  /*  $sql_delete_dependency = "DELETE FROM dependency_master WHERE task_id = '" . $task_id . "'";
    db_query($sql_delete_dependency,db_project_mgmt());*/
    
    if(isset($_GET['delete_from_meeting']) && $_GET['delete_from_meeting'] ==1){
        $meeting_id=$_GET['meeting_id'];
        $result=getMeetingTaskDataAfterAction($meeting_id);
        echo json_encode($result);
    }else if(isset($_GET['delete_from_workspace']) && $_GET['delete_from_workspace']==1){
        $meeting_id=$_GET['meeting_id'];
        $show_filter_str="";
        if($_GET['only_mine']==1){
            $show_filter_str= " and task_master.task_assignto=".$_COOKIE['b2b_id'];
        }
        echo json_encode(getAllTaskDataAfterWorkspaceAction($meeting_id,$show_filter_str));
    }else{
        $result=getAllTaskDataAfterAction();
        $totalTask=getCountOfTaskForSidebarMenu();
        echo json_encode(array('data'=>$result, 'total_tasks'=>$totalTask));	
    }	
}

if(isset($_POST["meeting_task_action"]) && $_POST["meeting_task_action"] == "meeting_task_edit"){
    $task_id=$_POST["meeting_task_id_edit"];
    $title = str_replace("'", "\'", $_POST["task_title"]);
    $desc  = str_replace("'", "\'", $_POST["task_desc"]);
    $task_old_data_sql=db_query("SELECT task_status,task_assignto,task_duedate,	task_title,task_details FROM task_master where task_master.id=$task_id", db_project_mgmt());
    $task_old_data=array_shift($task_old_data_sql);
    
    if($title!=$task_old_data['task_title'] || $desc!=$task_old_data['task_details']){
        update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Task','Task',$task_id,$_COOKIE['b2b_id'],"Message: ".$title);  
    }
    if($_POST["assignto"]!=$task_old_data['task_assignto']){
        $select_owner_name=db_query("SELECT name from loop_employees where b2b_id=".$_POST["assignto"],db());
        update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Task','Task',$task_id,$_COOKIE['b2b_id'],"Task Accountable: ". array_shift($select_owner_name)['name']);  
    }
    if($_POST['task_status']!=$task_old_data['task_status']){
        $update_msg="";
        if($_POST['task_status']==1){
            $update_msg="Marked Complete";
        }else{
             $update_msg="Marked Incomplete";
        }
        update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Task','Task',$task_id,$_COOKIE['b2b_id'],$update_msg);  
    }
    if($_POST["task_duedate"]!=$task_old_data['task_duedate']){
        update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Task','Task',$task_id,$_COOKIE['b2b_id'],"Due-Date: ".date("m-d-Y", strtotime($_POST["task_duedate"])));  
    }

    $task_status= $_POST['task_status']==1 ? "2" : "0";

    
    $update_sql = "Update `task_master` set `task_title` = '".$title."', `task_details` = '".$desc."',`task_assignto` = '". $_POST["assignto"] ."', `task_duedate` = '". $_POST["task_duedate"] ."', `task_status` = '".$task_status."' where id = $task_id";
    db_query($update_sql, db_project_mgmt());
    $meeting_id=$_POST['meeting_id'];
    db_query("UPDATE meeting_live_updates set task_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
   
    $task_sql=db_query("SELECT added_during_meeting,task_details,task_assignto,task_status,task_master.id,task_title,task_entered_by,task_duedate FROM task_master where task_master.id=$task_id", db_project_mgmt());
    $data=array();
    while($r = array_shift($task_sql)){
        $late_str="";
        if($r['added_during_meeting']==1){
            $late_str="<span class='todo-new'>New</span>";
        }else if(strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d"))  && $r['task_status'] == 0){
            $late_str="<span class='todo-late'>Late</span>";
        }
        $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
        $empDetails_arr=array_shift($empDetails_qry);
        $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
        $data=array(
            'task_id'=>$r['id'],
            'task_title'=>$r['task_title'],
            'emp_img'=>$empDetails['emp_img'],
            'emp_txt'=>$empDetails['emp_txt'],
            'task_status'=>$r['task_status'],
            'task_details'=>$r['task_details'],
            'task_duedate'=>$r['task_duedate'],
            'task_assignto'=>$r['task_assignto'],
            'task_percentage'=>display_meeting_task_percentage($meeting_id,$_POST['meeting_timer_id']),
            'late_str'=>$late_str,
        );       
    }
    echo json_encode($data);
    
}

if(isset($_GET['update_meeting_task_status']) && $_GET['update_meeting_task_status']==1){
    $task_completed_by=$_COOKIE['b2b_id'];
    $task_completed_on=date("Y-m-d h:i:s");
    $update_msg="Marked Complete";
    if($_GET['task_status']==0){
        $task_completed_by="";
        $task_completed_on="";
        $update_msg="Marked Incomplete";
    }
    $insql = "Update `task_master` set `task_status` = '". $_GET["task_status"] ."', `task_completed_by`='".$task_completed_by."', `task_completed_on`='".$task_completed_on."' where id = '". $_GET["task_id"] ."'";
    db_query($insql, db_project_mgmt());
    update_meeting_minutes($_GET['meeting_id'],$_GET['meeting_timer_id'],'Update Task','Task',$_GET["task_id"],$_COOKIE['b2b_id'], $update_msg);  
    db_query("UPDATE meeting_live_updates set task_flg=1 where meeting_timer_id='".$_GET['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
	
    /*$task_sql=db_query("SELECT task_status,task_duedate  FROM task_master where task_master.id=".$_GET["task_id"], db_project_mgmt());          
    $data=[];
    $meeting_percentage=display_meeting_task_percentage($_GET['meeting_id'],$_GET['meeting_timer_id']);
    while($r = array_shift($task_sql)){
        $data['task_status']=$r['task_status'];
        $data['task_duedate']=$r['task_duedate'];
        $data['task_percentage']=$meeting_percentage;
    }
    echo json_encode($data);*/
    $data=array();
	$task_sql = db_query("SELECT task_status,task_master.id,task_title,task_assignto,task_entered_by,task_entered_on,task_duedate,added_during_meeting  FROM task_master where task_master.archive_status=0 and task_meeting=".$_GET['meeting_id']." and archive_status=0 ORDER BY id DESC", db_project_mgmt());
    while($r = array_shift($task_sql)){
        $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
        $empDetails_arr = array_shift($empDetails_qry);
        $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
        $late_str="";
        if($r['added_during_meeting'] == 1){
            $late_str="<span class='todo-new'>New</span>";
        }else if(strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d")) && $r['task_status'] == 0){
            $late_str="<span class='todo-late'>Late</span>";
        }
        $data[]=array(
            'task_id'=>$r['id'],
            'task_title'=>$r['task_title'],
            'emp_img'=>$empDetails['emp_img'],
            'emp_txt'=>$empDetails['emp_txt'],
            'task_status'=>$r['task_status'],
            'late_str'=>$late_str,
        );       
    }
    $task_percentage=display_meeting_task_percentage($_GET['meeting_id'],$_GET['meeting_timer_id']); 
    echo json_encode(array('data'=>$data,'task_percentage'=>$task_percentage));
}
if(isset($_GET['sort_meeting_task']) && $_GET['sort_meeting_task']==1){
    $sort_order=$_GET['sort_order'];
    $order_str="ORDER BY id DESC";
    $order_msg="Default Order";
    if($sort_order==1){
        $order_str="ORDER BY task_assignto ASC";
        $order_msg="By Owner";
    }else if($sort_order==2){
        $order_str="ORDER BY task_status ASC";
        $order_msg="Incomplete";
    }else if($sort_order==3){
        $order_str="ORDER BY task_duedate DESC";
        $order_msg="By Due Date (Desc)";
    }else if($sort_order==4){
        $order_str="ORDER BY task_duedate ASC";
        $order_msg="By Due Date (Asc)";
    }else if($sort_order==5){
        $order_str="ORDER BY task_entered_on ASC";
        $order_msg="By Date Created (Oldest)";
    }else if($sort_order==6){
        $order_str="ORDER BY task_entered_on DESC";
        $order_msg="By Date Created (Newest)";
    }
    $meeting_id=$_GET['meeting_id'];
    //update_meeting_minutes($meeting_id,$_GET['meeting_timer_id'],'Task','Update Task',"",$_COOKIE['b2b_id'], 'Order Changed to '.$order_msg);  
    db_query("UPDATE meeting_live_updates set task_flg=1, task_order='".$order_str."' where meeting_timer_id=".$_GET['meeting_timer_id'],db_project_mgmt());
    echo json_encode(getMeetingTaskDataStartMeetingAfterAction($meeting_id,$order_str.",id DESC"));
}

if(isset($_POST["task_action_start_meet"]) && $_POST['task_action_start_meet']=="ADD"){
    $title = str_replace("'", "\'", $_POST["task_title"]);
    $desc  = str_replace("'", "\'", $_POST["task_desc"]);
    $meeting_id = $_POST['meeting_id'];
    $insql = "INSERT INTO `task_master`(`task_title`, `task_details`, `task_assignto`,
    `task_duedate`,`task_status`,`task_meeting`, `task_entered_by`,`added_during_meeting`) VALUES (
    '". $title ."', '". $desc ."', '". $_POST["assignto"] ."', 
    '". $_POST["task_duedate"] ."', 0 , '".$meeting_id."','". $_COOKIE["b2b_id"] ."',1)";
        db_query($insql, db_project_mgmt());
    $task_id = tep_db_insert_id();	
    update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Create Task','Task',$task_id,$_COOKIE['b2b_id'], ""); 
	db_query("UPDATE meeting_timer set task_count=task_count+1 where id=".$_POST['meeting_timer_id'], db_project_mgmt());
    db_query("UPDATE meeting_live_updates set task_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
	
    $data=array();
	$task_sql = db_query("SELECT task_assignto,task_status,task_master.id,task_title,task_entered_by,task_entered_on,task_duedate,added_during_meeting FROM task_master where task_master.archive_status=0 and task_meeting=$meeting_id and archive_status=0 ORDER BY id DESC", db_project_mgmt());
    while($r = array_shift($task_sql)){
        $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
        $empDetails_arr=array_shift($empDetails_qry);
        $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
        $late_str = "";
        if($r['added_during_meeting']==1){
            $late_str = "<span class='todo-new'>New</span>";
        }else if(strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d")) && $r['task_status'] == 0){
            $late_str = "<span class='todo-late'>Late</span>";
        }
        $data[]=array(
            'task_id' => $r['id'],
            'task_title' => $r['task_title'],
            'emp_img' => $empDetails['emp_img'],
            'emp_txt' => $empDetails['emp_txt'],
            'task_status' => $r['task_status'],
            'late_str' => $late_str,
        );       
    }
    $task_percentage=display_meeting_task_percentage($meeting_id,$_POST['meeting_timer_id']); 
    echo json_encode(array('data'=>$data,'task_percentage'=>$task_percentage));
}
if(isset($_GET['task_detail_to_add_issue']) && $_GET['task_detail_to_add_issue']==1){
    $task_id=$_GET['task_id'];
    $sql1 = db_query("SELECT task_details,id,task_title,task_assignto FROM task_master where id = $task_id " , db_project_mgmt());
    $r=array_shift($sql1);
    echo json_encode($r);
}

?>