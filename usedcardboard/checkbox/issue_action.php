<?php
	require ("mainfunctions/database.php"); 
	require ("mainfunctions/general-functions.php");
	require('meeting_common_function.php');
	$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
	$emp_level = array_shift($select_level)['level'];

	function getIssue_order($meeting_timer_id){
		$flg_update=db_query("SELECT issue_order FROM meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$issue_order=array_shift($flg_update)['issue_order'];
		return $order_str=$issue_order==""?"ORDER BY id DESC":$issue_order.", id DESC";
	}

	function getCountOfIssueForSidebarMenu(){
		$issue_count_sql=db_query("SELECT id FROM issue_master where created_by='".$_COOKIE["b2b_id"]."' and status=1", db_project_mgmt());
		return tep_db_num_rows($issue_count_sql);
	}

	function getMeetingIssueDataAfterAction($meeting_id,$show_filter_str){
		$issue_sql=db_query("SELECT issue_master.id,issue,issue_master.status,created_by FROM issue_master where meeting_id=$meeting_id && issue_master.status=1 $show_filter_str ORDER BY id DESC", db_project_mgmt());
		$result=array();
		while($r = array_shift($issue_sql)){
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
			$result[]=array('issue' => $r["issue"],'issue_id'=>$r['id'],'name'=>$r['name'],'emp_img'=>$empDetails['emp_img'], 'emp_txt'=>$empDetails['emp_txt']);
		}
		return $result;
	}
	function issueDataAfterMeetingIssue($meeting_id,$meeting_timer_id,$order_str="ORDER BY id DESC"){
		$issue_sql=db_query("SELECT issue_master.id,issue,order_no,issue_master.status,created_by FROM issue_master where meeting_id=$meeting_id && issue_master.status=1 $order_str", db_project_mgmt());
		$data=[];
		while($r = array_shift($issue_sql)){
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
			$data[]=array(
				'issue_id'=>$r['id'],
				'issue'=>$r['issue'],
				'emp_img'=>$empDetails['emp_img'],
				'emp_txt'=>$empDetails['emp_txt'],
				'status'=>$r['status'],
				'order_no'=>$r['order_no'],
			);
		}
		$show_issue_number_qry=db_query("SELECT show_issue_number FROM meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$show_issue_number=array_shift($show_issue_number_qry)['show_issue_number'];
		return array('data'=>$data,'show_issue_number'=>$show_issue_number);
	}
	//Issue Queries
	if(isset($_POST["issue_action"]) && $_POST["issue_action"] != ""){
		$issue = str_replace("'", "\'", $_POST["issue"]);
		$issue_details  = str_replace("'", "\'", $_POST["issue_desc"]);
		$result=array();
		$show_filter=0;
		
		if($_POST['issue_action']=='show_filter'){
			$meeting_id=$_POST['meeting_id'];
		}
		$show_filter_str="";
		if($_POST['only_mine']==1){
			$show_filter_str= "and created_by=".$_COOKIE['b2b_id'];
		}
		if($_POST["issue_action"] == "EDIT" || $_POST['issue_action']=="EDIT_ISSUE_MEETING" || $_POST['issue_action']=="editFromWorkspace"){
			$issue_id=$_POST['issue_id_edit'];
			$meeting_id=$_POST["hidden_meeting_id_issue_modal"];
			$insql = "Update `issue_master` set `issue` = '".$issue."', `issue_details` = '".$issue_details."'
			where id = ".$issue_id;
			db_query($insql, db_project_mgmt()); 
			if($_POST["issue_action"] == "EDIT"){
				$sql1 = db_query("SELECT issue_master.id,issue,issue_details,meeting_id FROM issue_master where id = $issue_id " , db_project_mgmt());
				while($r = array_shift($sql1)){
					$result=$r;
				}
			}else if($_POST["issue_action"] == "EDIT_ISSUE_MEETING" || $_POST["issue_action"] == "editFromWorkspace"){
				$result=getMeetingIssueDataAfterAction($meeting_id,$show_filter_str);
			}
		}else{
			if($_POST["issue_action"] == "ADD" || $_POST['issue_action']=="ADD_ISSUE_MEETING"){
				$meeting_id=$_POST["issue_meeting"];
				$insql = "INSERT INTO `issue_master`(`issue`, `issue_details`,`meeting_id` ,`created_by`,`status`) 
				VALUES ('". $issue ."', '". $issue_details ."', '". $meeting_id ."' , '". $_COOKIE["b2b_id"] ."', 1)";
				db_query($insql, db_project_mgmt()); 	
			}else if($_POST["issue_action"] == "ADD_FROM_TILE" || $_POST["issue_action"] == "ADD_FROM_WORKSPACE"){
				$meeting_id=$_POST["hidden_meeting_id_issue_modal"];
				$insql = "INSERT INTO `issue_master`(`issue`, `issue_details`,`meeting_id` ,`created_by`,`status`) 
				VALUES ('". $issue ."', '". $issue_details ."', '". $meeting_id ."' , '". $_COOKIE["b2b_id"] ."', 1)";
				db_query($insql, db_project_mgmt()); 	
			}else if($_POST["issue_action"] == "DELETE" || $_POST["delete_from_workspace"] == 1){
				$meeting_id=$_POST["meeting_id"];
				$issue_id=$_POST["issue_id"];
				$insql = "UPDATE `issue_master` set status=0 where id = ".$_POST["issue_id"];
				db_query($insql, db_project_mgmt()); 
			}
			if($_POST['issue_action']=="show_filter_ws"){
				$meeting_id=$_POST['meeting_id'];
				$show_filter_str="";
				if($_POST['only_mine_ws']==1){
					$show_filter_str= "and issue_master.created_by=".$_COOKIE['b2b_id'];
				}
				$result=getMeetingIssueDataAfterAction($meeting_id,$show_filter_str);
			}else if($_POST['issue_action']=="ADD_ISSUE_MEETING" || $_POST["issue_action"] == "ADD_FROM_WORKSPACE" || $_POST["delete_from_workspace"] == "1"){
				$show_filter_str="";
				if($_POST['only_mine_ws']==1){
					$show_filter_str= "and issue_master.created_by=".$_COOKIE['b2b_id'];
				}
				$result=getMeetingIssueDataAfterAction($meeting_id,$show_filter_str);
			}else{
				$all_data=array();
				$count_sql=db_query("SELECT id FROM issue_master where meeting_id=$meeting_id $show_filter_str and issue_master.status=1 ORDER BY id DESC", db_project_mgmt());
				$count=tep_db_num_rows($count_sql);
				if($count>0){
					$sql1 = db_query("SELECT issue_master.id,issue,issue_master.created_by from issue_master where meeting_id=$meeting_id $show_filter_str and issue_master.status=1 ORDER BY id DESC LIMIT 10", db_project_mgmt());
					while($r = array_shift($sql1)){
						$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
						$empDetails_arr=array_shift($empDetails_qry);
						$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
						$data[]=array(
								'id'=>$r['id'],
								'issue'=>$r['issue'],
								'emp_img'=>$empDetails['emp_img'],
								'emp_txt'=>$empDetails['emp_txt'],
							);
					}
				}
				$all_data=array('meeting_id'=>$meeting_id,'count'=>$count,'data'=>$data);
				$issue_count=getCountOfIssueForSidebarMenu();
				$result=array('issue_count'=>$issue_count,'all_data'=>$all_data);
			}
		}
		echo json_encode($result);
	}
	
	if(isset($_GET['edit_issue']) && $_GET['edit_issue']==1){
		$issue_id=$_GET['issue_id'];
		$sql1 = db_query("SELECT * FROM issue_master where id = $issue_id " , db_project_mgmt());
		$data=[];
		while($r = array_shift($sql1)){
			$data=$r;
		}
	    echo json_encode($data);
	}
	if(isset($_REQUEST['load_type']) && $_REQUEST['load_type']=="issue"){
		$result_array=[];
		$loaded_data=0;
		if(isset($_REQUEST['loaded_data']) && $_REQUEST['loaded_data']!=""){
			$loaded_data=$_REQUEST['loaded_data'];
		}
		$meeting_id=$_REQUEST['issue_meeting_id'];
		$sql1 = db_query("SELECT issue_master.id,issue,issue_master.created_byfrom issue_master where meeting_id=$meeting_id and issue_master.status=1 ORDER BY id DESC LIMIT 10 OFFSET $loaded_data", db_project_mgmt());	
		while($r = array_shift($sql1)){
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
			$data[]=array(
					'id'=>$r['id'],
					'issue'=>$r['issue'],
					'issue_details'=>$r['issue_details'],
					'emp_img'=>$empDetails['emp_img'],
					'emp_txt'=>$empDetails['emp_txt'],
			);
			$result_array=$data;
		}
	    echo json_encode($result_array);
	}	
	

	if(isset($_GET['delete_issue_from_meeting']) && $_GET['delete_issue_from_meeting']==1){
		$issue_id=$_GET['issue_id'];
		$insql = "Update `issue_master` set `status` = 0, `solved_by` ='".$_COOKIE['b2b_id']."' ,`solved_on`='".date('Y-m-d h:i:s')."' where id = ".$issue_id;
		db_query($insql,db_project_mgmt());
		$meeting_id=$_GET['meeting_id'];
		$result=getMeetingIssueDataAfterAction($meeting_id);
		echo json_encode($result);
	}
	if(isset($_POST["meeting_issue_action"]) && $_POST["meeting_issue_action"] == "meeting_issue_edit"){
		
		
		$issue_id=$_POST["meeting_issue_id_edit"];
		$issue = str_replace("'", "\'", $_POST["issue"]);
		$desc  = str_replace("'", "\'", $_POST["issue_details"]);

		$issue_old_data_sql=db_query("SELECT created_by,issue,issue_details FROM issue_master where issue_master.id=$issue_id", db_project_mgmt());
		$issue_old_data=array_shift($issue_old_data_sql);
		if($issue!=$issue_old_data['issue'] || $desc!=$issue_old_data['issue_details']){
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Issue','issue',$issue_id,$_COOKIE['b2b_id'],"Message: ".$issue);  
		}
		if($_POST["created_by"]!=$issue_old_data['created_by']){
			$select_owner_name=db_query("SELECT name from loop_employees where b2b_id=".$_POST["created_by"],db());
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Update Issue','issue',$issue_id,$_COOKIE['b2b_id'],"Issue Accountable: ". array_shift($select_owner_name)['name']);  
		}
		


		$insql = "Update `issue_master` set `issue` = '".$issue."', `issue_details` = '".$desc."',`created_by` ='".$_POST['created_by']."' where id = ".$issue_id;
		db_query($insql, db_project_mgmt()); 
		db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$issue_sql=db_query("SELECT issue_master.id,issue,order_no,created_by FROM issue_master where issue_master.id=$issue_id ORDER BY id DESC", db_project_mgmt());        
		$data=[];
		while($r = array_shift($issue_sql)){
			$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
			$empDetails_arr=array_shift($empDetails_qry);
			$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
				$data=array(
					'issue_id'=>$r['id'],
					'issue'=>$r['issue'],
					'emp_img'=>$empDetails['emp_img'],
					'emp_txt'=>$empDetails['emp_txt'],
					'status'=>$r['status'],
					'order_no'=>$r['order_no'],
				);
			}
		echo json_encode($data);
		
	}
	if(isset($_POST["issue_status_update"]) && $_POST["issue_status_update"] != ""){
		$meeting_id=$_POST['meeting_id'];
		$issue_id=$_POST["issue_id"];
		$meeting_timer_id=$_POST['meeting_timer_id'];
		if($_POST["issue_status_update"]=="solved"){
		$insql = "Update `issue_master` set `status` = 0 , `solved_by` ='".$_COOKIE['b2b_id']."' , `solved_on`='".date('Y-m-d h:i:s')."' where id = ".$issue_id;
		db_query($insql, db_project_mgmt()); 

		/*$current_solved=db_query("SELECT id,order_no FROM issue_master where id=$issue_id", db_project_mgmt());
		$current_solved_issue_order_no=array_shift($current_solved)['order_no'];
		if($current_solved_issue_order_no!=0 && $current_solved_issue_order_no!=""){
			//echo "SELECT id,order_no FROM issue_master where meeting_id=$meeting_id && status=1 && order_no!=0 && order_no!='' ORDER BY order_no<1 ASC,order_no";
			$issue_sql=db_query("SELECT id,order_no FROM issue_master where meeting_id=$meeting_id && status=1 && order_no!=0 && order_no!='' ORDER BY order_no<1 ASC,order_no", db_project_mgmt());
			$new_order=1;
			while($r = array_shift($issue_sql)){
				 $insql = "Update `issue_master` set `order_no` = $new_order where id = ".$r['id'];
				db_query($insql, db_project_mgmt()); 
				$new_order++;
			}
		}*/
		update_meeting_minutes($meeting_id, $meeting_timer_id,'Update Issue','Issue',$issue_id,$_COOKIE['b2b_id'],"Issue Marked Solved");  
		db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$meeting_timer_id."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
   
		}else if($_POST["issue_status_update"]=="unsolved"){
			$insql = "Update `issue_master` set `status` = 1 , `solved_by` ='' , `solved_on`='' where id = ".$issue_id;
			db_query($insql, db_project_mgmt()); 

			/*$current_unsolved=db_query("SELECT id,order_no FROM issue_master where id=$issue_id", db_project_mgmt());
			$current_unsolved_issue_order_no=array_shift($current_unsolved)['order_no'];
			if($current_unsolved_issue_order_no!=0 && $current_unsolved_issue_order_no!=""){
				$issue_sql=db_query("SELECT id,order_no FROM issue_master where meeting_id=$meeting_id && status=1  && order_no!=0 && order_no!='' ORDER BY order_no<1 ASC,order_no", db_project_mgmt());
				$new_order=1;
				while($r = array_shift($issue_sql)){
					if($new_order==$current_unsolved_issue_order_no){
						$new_order++;
						continue;
					}
					$insql = "Update `issue_master` set `order_no` = $new_order where id = ".$r['id'];
					db_query($insql, db_project_mgmt()); 
					$new_order++;
				}
			}  	*/
			
			update_meeting_minutes($meeting_id, $meeting_timer_id,'Update Issue','Issue',$issue_id,$_COOKIE['b2b_id'],"Issue Marked Open");  
			db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$meeting_timer_id."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
   
		}

		$order_str=getIssue_order($meeting_timer_id);
		$data=issueDataAfterMeetingIssue($meeting_id,$meeting_timer_id,$order_str);
		
		echo json_encode($data);
	}

	/*if(isset($_POST["issue_rank_update"]) && $_POST["issue_rank_update"] == 1){
		$order_none_sql = "Update `issue_master` set `order_no`='' where meeting_id = ".$_POST['meeting_id'];
		db_query($order_none_sql, db_project_mgmt());
		foreach($_POST['rank_array'] as $i=>$r){
			$insql = "Update `issue_master` set `order_no`='".$r['rank_val']."' where id = ".$r['issue_id'];
			db_query($insql, db_project_mgmt());
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Ranking Issue','Ranking Added',$r['issue_id'],$_COOKIE['b2b_id'], "Issue Ranked");  
			db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
   	
		}
		$meeting_id=$_POST['meeting_id'];
		$order_str=getIssue_order($_POST['meeting_timer_id']);
		$data=issueDataAfterMeetingIssue($meeting_id,$_POST['meeting_timer_id'],$order_str);
		echo json_encode($data);
	}*/
	if(isset($_POST["issue_rank_update"]) && $_POST["issue_rank_update"] == 1){
		$meeting_id=$_POST['meeting_id'];
		if(count($_POST['rank_array'])>0){
			$present_issue_id=implode(', ', array_column($_POST['rank_array'], 'issue_id'));
			$order_none_sql = "Update issue_master set order_no=0 where meeting_id =$meeting_id && id NOT IN ($present_issue_id)";
			db_query($order_none_sql, db_project_mgmt());
			foreach($_POST['rank_array'] as $i=>$r){
				$old_order_sql= db_query("select order_no from issue_master where id=".$r['issue_id'],db_project_mgmt());
				$old_order=array_shift($old_order_sql)['order_no'];
				$new_order=$r['rank_val'];
				if($old_order!=$new_order){
					db_query("Update issue_master set order_no=$new_order where id = ".$r['issue_id'],db_project_mgmt());
					update_meeting_minutes($meeting_id,$_POST['meeting_timer_id'],'Ranking Issue','Issue',$r['issue_id'],$_COOKIE['b2b_id'], "Rank From $old_order to $new_order");  
				}
			}
		}
		db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$order_str=getIssue_order($_POST['meeting_timer_id']);
		$data=issueDataAfterMeetingIssue($meeting_id,$_POST['meeting_timer_id'],$order_str);
		echo json_encode($data);
		

		/*$order_none_sql = "Update issue_master set order_no='' where meeting_id = ".$_POST['meeting_id'];
		db_query($order_none_sql, db_project_mgmt());
		foreach($_POST['rank_array'] as $i=>$r){
			$insql = "Update issue_master set order_no='".$r['rank_val']."' where id = ".$r['issue_id'];
			db_query($insql, db_project_mgmt());
			update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Ranking Issue','Ranking Added',$r['issue_id'],$_COOKIE['b2b_id'], "Issue Ranked");  
			db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
   	
		}
		$meeting_id=$_POST['meeting_id'];
		$order_str=getIssue_order($_POST['meeting_timer_id']);
		$data=issueDataAfterMeetingIssue($meeting_id,$_POST['meeting_timer_id'],$order_str);
		echo json_encode($data);
		*/

	}
	
	if(isset($_POST["update_issue_status_of_resoled_issue"]) && $_POST["update_issue_status_of_resoled_issue"] == 1){
		$insql = db_query("Update `issue_master` set `order_no` = 0 where id = ".$_POST['issue_id'],db_project_mgmt());
		echo 1;
	}
	if(isset($_POST["show_issue_number"]) && $_POST["show_issue_number"] == 1){
		$show_number=$_POST['show_number']; 
		$update_num_msg="Hide Issue Number";
		if($show_number==1){
			$update_num_msg="Display Issue Number";
		} 
		db_query("UPDATE meeting_live_updates set issue_flg=1, show_issue_number=$show_number where meeting_timer_id=".$_POST['meeting_timer_id'],db_project_mgmt());
		echo 1;
	}
	if(isset($_GET['sort_meeting_issue']) && $_GET['sort_meeting_issue']==1){
		$sort_order=$_GET['sort_order'];
		$order_str="ORDER BY id DESC";
		$order_msg="Default Order";
		if($sort_order==1){
			$order_str="ORDER BY order_no<1 ASC,order_no";
			$order_msg="By Priority(1,2 & 3)";
		}else if($sort_order==2){
			$order_str="ORDER BY created_by ASC";
			$order_msg="By Owner";
		}else if($sort_order==3){
			$order_str="ORDER BY created_on ASC";
			$order_msg="By Date Created (Oldest First)";
		}else if($sort_order==4){
			$order_str="ORDER BY created_on DESC";
			$order_msg="By Date Created (Newest First)";
		}else if($sort_order==5){
			$order_str="ORDER BY issue ASC";
			$order_msg="Alphabetically";
		}
		$meeting_id=$_GET['meeting_id'];
		$meeting_timer_id=$_GET['meeting_timer_id'];
		db_query("UPDATE meeting_live_updates set issue_flg=1, issue_order='".$order_str."' where meeting_timer_id=".$meeting_timer_id,db_project_mgmt());
		echo json_encode(issueDataAfterMeetingIssue($meeting_id,$meeting_timer_id,$order_str.", id DESC"));
	}
	if(isset($_POST["issue_action_start_meet"]) && $_POST['issue_action_start_meet']=="ADD"){
		$meeting_id=$_POST['meeting_id'];
		$issue = str_replace("'", "\'", $_POST["issue"]);
		$issue_details  = str_replace("'", "\'", $_POST["issue_desc"]);
		$insql = "INSERT INTO `issue_master`(`issue`, `issue_details`,`meeting_id` ,`created_by`,`status`) 
		VALUES ('". $issue ."', '". $issue_details ."', '". $meeting_id ."' , '". $_POST["issue_owner"] ."', 1)";
		db_query($insql, db_project_mgmt()); 
		$issue_id=tep_db_insert_id();	
		update_meeting_minutes($_POST['meeting_id'],$_POST['meeting_timer_id'],'Create Issue','Issue',$issue_id,$_COOKIE['b2b_id'], ""); 
		db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		$order_str=getIssue_order($_POST['meeting_timer_id']);
		$data=issueDataAfterMeetingIssue($meeting_id,$_POST['meeting_timer_id'],$order_str);
		echo json_encode($data);
	}

	if(isset($_GET['issue_detail_copy_issue']) && $_GET['issue_detail_copy_issue']==1){
		$issue_id=$_GET['issue_id'];
		$sql1 = db_query("SELECT id,issue FROM issue_master where id = $issue_id " , db_project_mgmt());
		$r=array_shift($sql1);
		echo json_encode($r);
	}
	if(isset($_POST["copy_issue_action"]) && $_POST['copy_issue_action']=="COPY"){
		$meeting_id=$_POST['meeting_id'];
		$issue_id=$_POST['issue_id'];
		$copy_to_meeting=$_POST['copy_to_meeting'];
		$copy_qry="insert into issue_master (issue,issue_details,meeting_id,created_by,status) 
		select issue,issue_details,'".$copy_to_meeting."','".$_COOKIE['b2b_id']."',0 from issue_master where id =".$issue_id;
		db_query($copy_qry, db_project_mgmt()); 
		$issue_id=tep_db_insert_id();	
		$meeting_name_qry=db_query("SELECT meeting_name from meeting_master where id=$copy_to_meeting",db_project_mgmt());
		$update_message="Issue Copied to ".array_shift($meeting_name_qry)['meeting_name'];
		update_meeting_minutes($meeting_id,$_POST['meeting_timer_id'],'Copy Issue','Issue',$issue_id,$_COOKIE['b2b_id'], $update_message); 
		db_query("UPDATE meeting_live_updates set issue_flg=1 where meeting_timer_id='".$_POST['meeting_timer_id']."' && attendee_id!='".$_COOKIE['b2b_id']."'",db_project_mgmt());
		echo 1;
	}

	if(isset($_GET['issue_detail_to_add_task']) && $_GET['issue_detail_to_add_task']==1){
		$issue_id=$_GET['issue_id'];
		$sql1 = db_query("SELECT created_by,id,issue,status,created_on FROM issue_master where id = $issue_id " , db_project_mgmt());
		$r=array_shift($sql1);
		$r['created_on']=date("Y-m-d", strtotime($r['created_on']));
		echo json_encode($r);
	}
	?>
