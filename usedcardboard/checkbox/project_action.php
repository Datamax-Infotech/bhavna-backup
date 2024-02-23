<?php 
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
require('meeting_common_function.php');
$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
$emp_level = array_shift($select_level)['level'];

$meeting_filter = "";
if($emp_level!=2){
	$meeting_filter =" and attendee_id='".$_COOKIE['b2b_id']."'";
}
function getCountOfProjectForSidebarMenu(){
	$project_count_sql=db_query("SELECT project_id FROM project_master where  project_owner = '".$_COOKIE["b2b_id"]."' and archive_status=0", db_project_mgmt());
	$project_count=tep_db_num_rows($project_count_sql);
	return $project_count;
}
function getMeetingProjectDataAfterAction($meeting_id){
	$project_sql=db_query("SELECT project_id,project_name,project_owner FROM project_master where find_in_set($meeting_id,meeting_ids) and archive_status=0 ORDER BY project_id DESC", db_project_mgmt());
	$result=array();
	while($r = array_shift($project_sql)){
		$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['project_owner']."'",db());
		$empDetails_arr=array_shift($empDetails_qry);
		$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
		$result[]=array('project_name' => $r["project_name"],'project_id'=>$r['project_id'],'name'=>$r['name'],'emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt']);
	}
	return $result;
}


function getAllProjectDataAfterAction(){
    //$sql_main = db_query("SELECT id, meeting_name FROM meeting_master where status = 1 union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name", db_project_mgmt());
    $sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
								where mm.status = 1 $meeting_filter GROUP By ma.meeting_id 
								union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name ", db_project_mgmt());
								
	$result=array();
    while($main_row = array_shift($sql_main)){
        $meeting_id=$main_row['id'];
        $count_sql=db_query("SELECT project_id FROM project_master where find_in_set($meeting_id,meeting_ids) AND project_owner = '".$_COOKIE["b2b_id"]."' and archive_status=0 ORDER BY project_id DESC", db_project_mgmt());
        $count=tep_db_num_rows($count_sql);
        if($count>0){
            $sql1 = db_query("SELECT project_id,project_name, project_description,project_owner,project_status_id, project_deadline FROM project_master where find_in_set($meeting_id,meeting_ids) AND project_owner = '".$_COOKIE["b2b_id"]."' and archive_status=0 ORDER BY project_id DESC LIMIT 10", db_project_mgmt());
            $data=array();
            while($r = array_shift($sql1)){
                $status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']);
                $status_name=$status_data['status_name'];
                $status_class=$status_data['status_class'];
                $status_icon=$status_data['status_icon'];
                $class_name=$status_data['deadline_class'];
                $data[]=array(
                        'project_name'=>$r['project_name'],
                        'project_id'=>$r['project_id'],
                        'project_description'=>$r['project_description'],
                        'project_status_id'=>$r['project_status_id'],
                        'project_status'=>$status_name,
                        'status_class'=>$status_class,
                        'status_icon'=>$status_icon,
                        'project_owner'=>$r['project_owner'],
                        'class_name'=> $class_name,
                        'project_deadline'=>date("m/d/Y", strtotime($r['project_deadline'])),
                );
            }
        }
        $result[]=array('meeting_id'=>$main_row["id"],'meeting_name' => $main_row["meeting_name"],'count'=>$count,'data'=>$data);
    }
    return $result;
}

function getAllProjectDataAfterWorkSpaceAction($meeting_id,$filter_str){
	$sql1 = db_query("SELECT project_id,project_name, project_description,project_status_id, project_deadline,project_owner FROM project_master where find_in_set($meeting_id,meeting_ids) and archive_status=0 $filter_str ORDER BY project_id DESC LIMIT 10", db_project_mgmt());
	$data=array();
	while($r = array_shift($sql1)){
		$status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']);
		$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['project_owner']."'",db());
$empDetails_arr=array_shift($empDetails_qry);
$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
		$status_name=$status_data['status_name'];
		$status_class=$status_data['status_class'];
		$status_icon=$status_data['status_icon'];
		$class_name=$status_data['deadline_class'];
		$data[]=array(
				'project_name'=>$r['project_name'],
				'project_id'=>$r['project_id'],
				'project_description'=>$r['project_description'],
				'project_status_id'=>$r['project_status_id'],
				'project_status'=>$status_name,
				'status_class'=>$status_class,
				'status_icon'=>$status_icon,
				'project_owner'=>$r['project_owner'],
				'class_name'=> $class_name,
				'project_deadline'=>date("m/d/Y", strtotime($r['project_deadline'])),
				'emp_img'=>$empDetails['emp_img'], 
				'emp_txt'=>$empDetails['emp_txt']
		);

	}
    return $data;
}
	if(isset($_GET['edit_project']) && $_GET['edit_project']==1){	
		$project_id=$_GET['project_id'];
		$sql1 = db_query("SELECT * FROM project_master where project_id = $project_id " , db_project_mgmt());

		$data=[];
		while($r = array_shift($sql1)){
			$data=$r;
			$sql2 = db_query("SELECT checked, milestone,milestone_date FROM project_milestones where project_id = $project_id " , db_project_mgmt());
			$data_milestone=[];
			while ($rowsel_getdata = array_shift($sql2)) {
				$data_milestone[]=array('checked'=>$rowsel_getdata["checked"], 'milestone'=>$rowsel_getdata['milestone'],'milestone_date'=>$rowsel_getdata['milestone_date']);
			}
			$data['milestones']=$data_milestone;
		}
		echo json_encode($data);
	}
	
	if(isset($_GET['get_project_status']) && $_GET['get_project_status']==1){
		$query = db_query( "SELECT id,status FROM project_status order by id", db_project_mgmt());
		$data=[];
		while ($rowsel_getdata = array_shift($query)) {
			$data[]=array('status_id'=>$rowsel_getdata["id"], 'status_name'=>$rowsel_getdata['status']);
		}												
		echo json_encode($data);
	}
	
	if(isset($_GET['update_project_status']) && $_GET['update_project_status']==1){
		$insql = "Update `project_master` set `project_status_id` = '". $_GET["pstatus_id"] ."'  where project_id = '". $_GET["project_id"] ."'";
		db_query($insql, db_project_mgmt());
		$res=get_status_date_color_info($_GET['pstatus_id'], $_GET['deadline']);
		echo json_encode($res);
	}
	
	if(isset($_POST["project_action"]) && $_POST["project_action"] != ""){
		$filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
		$allow_ext = explode(",",$filetype);
		if(!empty( $_FILES['uploadscanrep'] ) ) {
			$scan_rep_name = "";	
			foreach( $_FILES['uploadscanrep']['tmp_name'] as $index => $tmpName )
			{
				if( !empty( $_FILES['uploadscanrep']['error'][ $index ] ) )
				{

				}else{

					if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) ){

						$ext = pathinfo($_FILES["uploadscanrep"]["name"][ $index ], PATHINFO_EXTENSION);
						if(in_array(strtolower($ext), $allow_ext) )
						{				
							$attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace( "/'/", "\'", $_FILES['uploadscanrep']['name'][ $index ]); 	

							$scan_rep_name = $scan_rep_name . $attachfile_nm_tmp . "|";

							move_uploaded_file( $tmpName, "water_scanreport/". $attachfile_nm_tmp); // move to new location perhaps?
						}
					}

				}

			}
			
			$tmppos_1 = strpos($scan_rep_name, "|");
			if ($tmppos_1 != false)
			{ 	
				if ($scan_rep_name != "") {
					$scan_rep_name = $scan_rep_name . "|" . substr($scan_rep_name, 0 , strlen($scan_rep_name,'|')-1); 
				}else{
					$scan_rep_name = substr($scan_rep_name, 0 , strlen($scan_rep_name,'|')-1); 
				}
			}		
		}
		$meeting_ids=0;
		if(!empty($_POST['project_meetings'])){
			$meeting_ids= implode(',',$_POST['project_meetings']);
		}
		if($_POST['issue_action']=='show_filter'){
			$meeting_id=$_POST['meeting_id'];
		}
		
		if($_POST["project_action"] == "ADD" || $_POST['project_action']=="ADD_FROM_MEET" ||  $_POST['project_action']=="ADD_FROM_MEET_START_MEETING" || $_POST["project_action"] == "ADD_FROM_WORKSPACE"){
			   $insql = "INSERT INTO `project_master`(`project_name`, `project_description`,
				`project_owner`, `project_dept_id`, `project_deadline`, 
				`project_priority_id`, `meeting_ids`,`project_status_id`, `project_file`,
				`project_enter_by`) VALUES ('". str_replace("'", "\'" ,$_POST["project_title"]) ."', '". str_replace("'", "\'" ,$_POST["project_desc"]) ."', 
				'". $_POST["owner_id"] ."','". $_POST["dept_id"] ."','". $_POST["deadline"] ."','". $_POST["project_priority_id"] ."','". $meeting_ids ."','". $_POST["pstatus_id"] ."','". $scan_rep_name ."','". $_COOKIE["b2b_id"] ."')";
                
                db_query($insql, db_project_mgmt());
                $project_id=tep_db_insert_id();
                $check=json_decode($_POST['milestone_check_box']);
                $milestone_title=$_POST['milestone_title'];
                $milestone_date=$_POST['milestone_date'];
                for($i=0; $i<count($milestone_title);$i++){
                    $milestone_date=$milestone_date[$i]=="" ? date("Y-m-d") : $milestone_date[$i];
                   
                    $insert_miles="INSERT INTO `project_milestones` (`project_id`,`checked`,`milestone`,`milestone_date`) VALUES ('".$project_id."',
                    '".$check[$i]."','".$milestone_title[$i]."','".$milestone_date."')";
                    db_query($insert_miles, db_project_mgmt());
                }
				if($_POST['project_action']=="ADD_FROM_MEET_START_MEETING"){
					update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Create Project','Project',$project_id,$_COOKIE['b2b_id']); 
					db_query("UPDATE meeting_live_updates set project_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
	
				}  
        }
		else if($_POST["project_action"] == "EDIT" || $_POST['project_action']=="editFromCreateMeet" || $_POST['project_action']=="editFromWorkspace"){
            $project_id=$_POST["project_id_edit"];
			$insql = "Update `project_master` set `project_name` = '". str_replace("'", "\'" , $_POST["project_title"]) ."', `project_description` = '". str_replace("'", "\'" ,$_POST["project_desc"]) ."',
			`project_owner` = '". $_POST["owner_id"] ."', `project_dept_id` = '". $_POST["dept_id"] ."', `project_deadline` = '". $_POST["deadline"] ."', 
			`project_priority_id` = '". $_POST["project_priority_id"] ."', `project_status_id` = '". $_POST["pstatus_id"] ."' $file_upload_str, `meeting_ids`= '$meeting_ids'
			 where project_id = ".$project_id;
             db_query($insql, db_project_mgmt());
              
             $sql_delete_old_milestone = "DELETE FROM project_milestones WHERE project_id = '" . $project_id . "'";
             $result = db_query($sql_delete_old_milestone,db_project_mgmt());
             $check=json_decode($_POST['milestone_check_box']);
                $milestone_title=$_POST['milestone_title'];
                $milestone_date=$_POST['milestone_date'];
               for($i=0; $i<count($milestone_title);$i++){
                    $m_date=$milestone_date[$i]=="" ? date("Y-m-d") : $milestone_date[$i];
                    $insert_miles="INSERT INTO `project_milestones` (`project_id`,`checked`,`milestone`,`milestone_date`) VALUES ('".$project_id."',
                    '".$check[$i]."','".$milestone_title[$i]."','".$m_date."')";
                    db_query($insert_miles, db_project_mgmt());
            }
        }

		if($_POST['project_action']=="editFromCreateMeet" || $_POST['project_action']=="ADD_FROM_MEET"){
			$meeting_id=$_POST['meeting_id'];
			$result=getMeetingProjectDataAfterAction($meeting_id);
			echo json_encode($result);
		}else if($_POST["project_action"] == "ADD_FROM_WORKSPACE" || $_POST['project_action']=="editFromWorkspace" || $_POST['project_action']=='show_filter'){
			$show_filter_str="";
			if($_POST['only_mine']==1){
				$show_filter_str= "and project_owner=".$_COOKIE['b2b_id'];
			}
			$result=getAllProjectDataAfterWorkSpaceAction($_POST['meeting_id'],$show_filter_str);
			echo json_encode($result);	
		}else if($_POST['project_action']=="ADD_FROM_MEET_START_MEETING"){
			$res=getMeetingProjectDataStartMeetingAfterAction($_POST['meeting_id']);
			echo json_encode($res);
		}else{
			$result=getAllProjectDataAfterAction();
			$totalProjects=getCountOfProjectForSidebarMenu();
			echo json_encode(array('data'=>$result, 'total_projects'=>$totalProjects));	
		}
		
	}
	
	if(isset($_REQUEST['load_type']) && $_REQUEST['load_type']=="project"){
		$result_array=[];
		$loaded_data=0;
		if(isset($_REQUEST['loaded_data']) && $_REQUEST['loaded_data']!=""){
			$loaded_data=$_REQUEST['loaded_data'];
		}
		$project_meeting_id=$_REQUEST['project_meeting_id'];
		$owner_id=$_COOKIE['b2b_id'];
		$sql1 = db_query("SELECT project_id,project_name, project_description,project_owner,project_status_id, project_deadline FROM project_master where find_in_set($project_meeting_id,meeting_ids) AND project_owner = '".$owner_id."' and archive_status=0 ORDER BY project_id DESC LIMIT 10 OFFSET $loaded_data", db_project_mgmt());
		while($r = array_shift($sql1)){
			$status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']);
			$status_name=$status_data['status_name'];
			$status_class=$status_data['status_class'];
			$status_icon= $status_data['status_icon'];
			$class_name=$status_data['deadline_class'];
			$data=array(
				'project_name'=>$r['project_name'],
				'project_id'=>$r['project_id'],
				'project_description'=>$r['project_description'],
				'project_status_id'=>$r['project_status_id'],
				'project_owner'=>$r['project_owner'],
				'project_status'=>$status_name,
				'status_class'=>$status_class,
				'status_icon'=>$status_icon,
				'class_name'=> $class_name,
				'project_deadline'=>date("m/d/Y", strtotime($r['project_deadline'])),
			);
			$result_array[]=$data;
		}
	    echo json_encode($result_array);
	}
    if(isset($_GET['delete_project']) && $_GET['delete_project']==1){
		
		$project_id=$_GET['project_id'];
		if(isset($_GET['delete_from_workspace']) && $_GET['delete_from_workspace']==1){
			$show_filter_str="";
			if($_GET['only_mine']==1){
				$show_filter_str= "and project_owner=".$_COOKIE['b2b_id'];
			}
			$meeting_id=$_GET['meeting_id'];
			$sql_delete_project = "UPDATE  project_master set archive_status=1 WHERE project_id = '" . $project_id . "'";
			db_query($sql_delete_project,db_project_mgmt());
			$select_already_esiting_id=db_query("SELECT meeting_ids from project_master where project_id=$project_id",db_project_mgmt());
			$meet_ids=array_shift($select_already_esiting_id)['meeting_ids'];
			$remove=array('/'.$meeting_id.',/','/,'.$meeting_id.'\b/','/'.$meeting_id.'\b/');
			$update_ids=preg_replace($remove,"",$meet_ids);
			$update_project = "UPDATE `project_master` set meeting_ids = '".$update_ids."' where project_id=$project_id";
			db_query($update_project, db_project_mgmt());
			$result=getAllProjectDataAfterWorkSpaceAction($meeting_id,$show_filter_str);
			echo json_encode($result);
		}else{
        $sql_delete_project = "UPDATE  project_master set archive_status=1 WHERE project_id = '" . $project_id . "'";
        db_query($sql_delete_project,db_project_mgmt());
		$sql_delete_milestone = "DELETE FROM project_milestones WHERE project_id = '" . $project_id . "'";
        db_query($sql_delete_milestone,db_project_mgmt());
        $result=getAllProjectDataAfterAction();
		$totalProjects=getCountOfProjectForSidebarMenu();
		echo json_encode(array('data'=>$result, 'total_projects'=>$totalProjects));	
		}
		//echo json_encode($result);	
	}

	if(isset($_POST["delete_meeting_project"]) && $_POST["delete_meeting_project"] == 1){
		$project_id=$_POST['project_id'];
		$meeting_id=$_POST['meeting_id'];
		$sql_delete_project = "UPDATE  project_master set archive_status=1 WHERE project_id = '" . $project_id . "'";
        db_query($sql_delete_project,db_project_mgmt());
		$select_already_esiting_id=db_query("SELECT meeting_ids from project_master where project_id=$project_id",db_project_mgmt());
		$meet_ids=array_shift($select_already_esiting_id)['meeting_ids'];
		$remove=array('/'.$meeting_id.',/','/,'.$meeting_id.'\b/','/'.$meeting_id.'\b/');
		$update_ids=preg_replace($remove,"",$meet_ids);
		$update_project = "UPDATE `project_master` set meeting_ids = '".$update_ids."' where project_id=$project_id";
		db_query($update_project, db_project_mgmt());
		$result=getMeetingProjectDataAfterAction($meeting_id);
		echo json_encode($result);
	}

	

	if(isset($_POST["meeting_project_list"]) && $_POST["meeting_project_list"] == 1){
		$meeting_id=$_POST['meeting_id'];
		$project_sql_main=db_query("SELECT project_id from project_master where archive_status=0 and find_in_set('".$meeting_id."',`meeting_ids`)", db_project_mgmt());
		$already_added_project_array=[];
		while($pro_row = array_shift($project_sql_main)){
			$already_added_project_array[]=$pro_row['project_id'];
		}
		$filter_out_already_added_pro="";
		if(count($already_added_project_array)>0){
			$already_added_projects=implode(',',$already_added_project_array);
			$filter_out_already_added_pro="where project_id NOT IN ($already_added_projects) and archive_status=0";
		}
		$project_sql=db_query("SELECT project_id,project_name,project_owner FROM project_master $filter_out_already_added_pro ORDER BY project_id DESC", db_project_mgmt());
		$result=array();
		while($r = array_shift($project_sql)){
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['project_owner']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
			$result[]=array('project_name' => $r["project_name"],'project_id'=>$r['project_id'],'emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt'],'project_owner'=>$r['name']);
		}
		echo json_encode($result);
	}

	if(isset($_POST["add_existing_project_meeting"]) && $_POST["add_existing_project_meeting"] != ""){
		$meeting_id=$_POST['meeting_id'];
		foreach($_POST['project_id'] as $project_id){
			$select_already_esiting_id=db_query("SELECT meeting_ids from project_master where archive_status=0 and project_id=$project_id",db_project_mgmt());
			$meet_ids=array_shift($select_already_esiting_id)['meeting_ids'];
			$new_ids=$meet_ids.",".$meeting_id;
			$update_project = "UPDATE `project_master` set meeting_ids = '".$new_ids."' where project_id=$project_id";
			db_query($update_project, db_project_mgmt());
			if($_POST["add_existing_project_meeting"]==2){
				update_meeting_minutes($meeting_id,$_POST['meeting_timer_id'],'Create Project','Project',$project_id,$_COOKIE['b2b_id']); 
				db_query("UPDATE meeting_live_updates set project_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
	
			}
		}
		$res=array();
		if($_POST["add_existing_project_meeting"]==1){
			$res=getMeetingProjectDataAfterAction($meeting_id);
		}else if($_POST["add_existing_project_meeting"]==2){
			$res=getMeetingProjectDataStartMeetingAfterAction($meeting_id);
				
		}
		echo json_encode($res);
	}

	if(isset($_POST["meeting_project_action"]) && $_POST["meeting_project_action"] == "meeting_project_edit"){
		$project_id=$_POST["meeting_project_id_edit"];
		$project_name=str_replace("'", "\'" , $_POST["project_title"]);
		$desc=str_replace("'", "\'" ,$_POST["project_desc"]);
		$project_old_data_sql=db_query("SELECT project_id,project_name,project_deadline,project_owner,project_description FROM project_master where project_id=$project_id", db_project_mgmt());
		$project_old_data=array_shift($project_old_data_sql);
		if($project_name!=$project_old_data['project_name'] || $desc!=$project_old_data['project_description']){
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Project','Project',$project_id,$_COOKIE['b2b_id'],"Message: ".$project_name);  
		}
		if($_POST["owner_id"]!=$project_old_data['project_owner']){
			$select_owner_name=db_query("SELECT name from loop_employees where b2b_id=".$_POST["owner_id"],db());
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Project','Project',$project_id,$_COOKIE['b2b_id'],"Project Accountable: ". array_shift($select_owner_name)['name']);  
		}
	
		if($_POST["project_deadline"]!=$project_old_data['project_deadline']){
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Project','Project',$project_id,$_COOKIE['b2b_id'],"Due-Date: ".date("m-d-Y", strtotime($_POST["project_deadline"])));  
		}
		

		
		$insql = "Update `project_master` set `project_name` = '".$project_name."', `project_description` = '".$desc."',
		`project_owner` = '". $_POST["owner_id"] ."', `project_deadline` = '". $_POST["project_deadline"] ."' where project_id = ".$project_id;
		 db_query($insql, db_project_mgmt());
		  
		 $sql_delete_old_milestone = "DELETE FROM project_milestones WHERE project_id = '" . $project_id . "'";
		 $result = db_query($sql_delete_old_milestone,db_project_mgmt());
		 $check=json_decode($_POST['milestone_check_box']);
		$milestone_title=$_POST['milestone_title'];
		$milestone_date=$_POST['milestone_date'];
		for($i=0; $i<count($milestone_title);$i++){
				$m_date=$milestone_date[$i]=="" ? date("Y-m-d") : $milestone_date[$i];
				$insert_miles="INSERT INTO `project_milestones` (`project_id`,`checked`,`milestone`,`milestone_date`) VALUES ('".$project_id."',
				'".$check[$i]."','".$milestone_title[$i]."','".$m_date."')";
				db_query($insert_miles, db_project_mgmt());
		}

		//update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Project','Project',$project_id,$_COOKIE['b2b_id']); 
		db_query("UPDATE meeting_live_updates set project_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$sql1 = db_query("SELECT project_id,project_name,project_status_id,project_deadline,project_owner FROM project_master  where project_id = $project_id and archive_status=0" , db_project_mgmt());

		$data=[];
		while($r = array_shift($sql1)){
			$data['project_id']=$r['project_id'];
			$data['project_name']=$r['project_name'];
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['project_owner']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
			$data['emp_img']=$empDetails['emp_img'];
			$data['emp_txt']=$empDetails['emp_txt'];
			$data['name']=$empDetails_arr['name'];
			$sql2 = db_query("SELECT checked, milestone,milestone_date FROM project_milestones where project_id = $project_id " , db_project_mgmt());
			$data_milestone=[];
			while ($rowsel_getdata = array_shift($sql2)) {
				$data_milestone[]=array('checked'=>$rowsel_getdata["checked"], 'milestone'=>$rowsel_getdata['milestone'],'milestone_date'=>$rowsel_getdata['milestone_date']);
			}
			$data['milestones']=$data_milestone;
		}
		echo json_encode($data);
	}

	if(isset($_GET['update_meeting_project_status']) && $_GET['update_meeting_project_status']==1){
		$project_id=$_GET["project_id"];
		$insql = "Update `project_master` set `project_status_id` = '". $_GET["pstatus_id"] ."'  where project_id = '".$project_id."'";
		db_query($insql, db_project_mgmt());
		$sql = db_query( "SELECT `project_deadline` FROM `project_master` WHERE `project_id` = $project_id", db_project_mgmt());
    	$res_deadline = array_shift($sql);
		$res=get_status_date_color_info($_GET['pstatus_id'], $res_deadline['project_deadline']);
		$update_msg="Marked: ".$res['status_name'];
		update_meeting_minutes($_GET['meeting_id'],$_GET['meeting_timer_id'],'Update Project','Project',$project_id,$_COOKIE['b2b_id'],$update_msg); 
		db_query("UPDATE meeting_live_updates set project_flg=1 where meeting_timer_id='".$_GET['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		echo json_encode($res);
	}

	if(isset($_GET['project_detail_to_add_task_issue']) && $_GET['project_detail_to_add_task_issue']==1){	
		$project_id=$_GET['project_id'];
		$sql1 = db_query("SELECT project_deadline,project_id,project_owner,project_name,project_enter_on,project_status.status as p_status,project_status_id FROM project_master 
		JOIN project_status ON project_status.id=project_master.project_status_id where project_id = $project_id " , db_project_mgmt());
		$data=array_shift($sql1);
		$empDetails_qry=db_query("SELECT name from loop_employees where b2b_id='".$data['project_owner']."'",db());
		$data['name']=array_shift($empDetails_qry)['name']; 
		$data['entered_on']=date("Y-m-d", strtotime($data['project_enter_on']));
		echo json_encode($data);
	}

	if(isset($_GET['get_emp_department']) && $_GET['get_emp_department']==1){
		$select_dept=db_query("SELECT dept_id FROM loop_employees where b2b_id=".$_GET['current_emp'],db());
		echo array_shift($select_dept)['dept_id'];
	}
	?>