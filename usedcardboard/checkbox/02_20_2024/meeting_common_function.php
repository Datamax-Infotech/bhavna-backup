<?php session_start();

function getAllEmployeeWithImgForMeetingForms($select_id,$name_attr){
    $dp_string="";
    $user_check_sql = db_query("SELECT id,name,	initials,Headshot,level,is_supervisor,b2b_id FROM loop_employees where b2b_id='".$_COOKIE['b2b_id']."'",db());
    $loggedin_user=array_shift($user_check_sql);
    $empDetails=getOwerHeadshotForMeeting($loggedin_user['Headshot'],$loggedin_user['initials']);   
                         
    if($loggedin_user['level']==2 || $loggedin_user['is_supervisor']=="Yes"){
        $dp_string.= "<select class='search_existing_user_sel addOwnerStartMeet form-control form-control-sm select2' id='$select_id' name='$name_attr'>";
        if($loggedin_user['level']==2){
            $other_user_sql=db_query("SELECT id,Headshot,initials,name,b2b_id from loop_employees where status = 'Active' ORDER BY name ASC",db()); 
        }else{
            $other_user_sql=db_query("SELECT id,Headshot,initials,name,b2b_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC",db());
            $selected_str=$loggedin_user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : "";
            $dp_string.= "<option $selected_str data-kt-rich-content-icon='".$empDetails['emp_img']."' data-kt-rich-content-emp-txt='".$empDetails['emp_txt']."' value='".$loggedin_user['b2b_id']."'>".$loggedin_user['name']."</option>";     
        } 
            while($user = array_shift($other_user_sql)){
                $empDetails=getOwerHeadshotForMeeting($user['Headshot'],$user['initials']);   
                $selected_str=$user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : "";
                $dp_string.= "<option $selected_str data-kt-rich-content-icon='".$empDetails['emp_img']."' data-kt-rich-content-emp-txt='".$empDetails['emp_txt']."'  value='".$user['b2b_id']."' >".$user['name']."</option>";  
            }
        $dp_string.='</select>';
    } else{ 
        $dp_string.= "<select class='search_existing_user_sel addOwnerStartMeet form-control form-control-sm select2' id='$select_id' name='$name_attr' disabled='true'>";
        $selected_str=$loggedin_user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : "";
        $dp_string.= "<option $selected_str data-kt-rich-content-icon='".$empDetails['emp_img']."' data-kt-rich-content-emp-txt='".$empDetails['emp_txt']."' value='".$loggedin_user['b2b_id']."'>".$loggedin_user['name']."</option>";  
        $dp_string.= '</select>'; 
    }
    
    return $dp_string;
}
function get_status_date_color_info($id, $actual_date=""){
    $sql = db_query( "SELECT `status` FROM `project_status` WHERE `id` = $id", db());
    $res = array_shift($sql);
    $status_class=""; 
    $status_icon_cls="";
    if($id==1){
        $status_class="text-warning";
        $status_icon_cls="fa fa-ban";
    }if($id==3){
        $status_class="text-warning";
        $status_icon_cls="fa fa-comment";
    }else if($id==2){
        $status_class="text-primary";
        $status_icon_cls="fa fa-history";
    }else if($id==6){
        $status_class="text-primary";
        $status_icon_cls="fa fa-pause";
    }else if($id==4 || $id==7){
        $status_class="text-danger";
        $status_icon_cls="fa fa-times";
    }else if($id==5){
        $status_class="text-success";
        $status_icon_cls="fa fa-check";
    }
    $status_icon="<i class='$status_icon_cls'></i>";
    $current_date =strtotime(date("Y-m-d"));
    $actual_date=strtotime($actual_date);
    $class_name="";
    if($actual_date > $current_date){
        $class_name="text-success";
    }else if($actual_date < $current_date && $id==6){
        $class_name="text-success";  
    }else if($actual_date < $current_date){
        $class_name="text-danger";  
    }
     
    return array('status_name'=>$res['status'],'status_class'=>$status_class,'status_icon'=>$status_icon,'deadline_class'=>$class_name);
}

function get_status_date_color_info_task($actual_date){
    $current_date =strtotime(date("Y-m-d"));
    $actual_date=strtotime($actual_date);
    $class_name="";
    if($actual_date > $current_date){
        $class_name="text-success";
    }else if($actual_date < $current_date){
        $class_name="text-danger";  
    }
    return $class_name;
}
function display_meeting_task_percentage($meeting_id,$meeting_timer_id=""){
    /*$qry_task=db_query("SELECT task_count from meeting_timer where id=$meeting_timer_id");
    $count_total_task=array_shift($qry_task)['task_count'];
    //$qry_completed=db_query("SELECT task_status from task_master where task_meeting=$meeting_id && task_status=1");
    $qry_completed=db_query("SELECT id from meeting_minutes where meeting_timer_id=$meeting_timer_id && update_msg='Marked Complete'",db());
    $count_completed_task = tep_db_num_rows($qry_completed);
	$todo_per = "";
	if ($count_completed_task > 0){
		$todo_per=$count_completed_task*100/($count_total_task);
	}
    */
    //$qry_task=db_query("SELECT task_status from task_master where task_meeting=$meeting_id && (task_status=0 or task_status=2)");
    $qry_task=db_query("SELECT task_status from task_master where task_meeting=$meeting_id && archive_status=0",db());
    
    $count_total_task=tep_db_num_rows($qry_task);
    $qry_completed=db_query("SELECT task_status from task_master where task_meeting=$meeting_id && (task_status=2 OR task_status=1) && archive_status=0",db());
    $count_completed_task = tep_db_num_rows($qry_completed);
    $todo_per = "";
	if ($count_completed_task > 0){
		$todo_per=$count_completed_task*100/($count_total_task);
	}
    return number_format((float)$todo_per, 2, '.', '');
}
function update_meeting_minutes($meeting_id,$meeting_timer_id,$action,$notes,$update_on_id,$updated_by,$update_msg=""){
	$sql="INSERT INTO `meeting_minutes` (`meeting_id`,`meeting_timer_id`,`action`,`notes`,`update_on_id`,`updated_by`,`update_msg`) 
	values('".$meeting_id."','".$meeting_timer_id."','".$action."','".$notes."','".$update_on_id."',$updated_by,'".$update_msg."')";
	db_query($sql,db());
}

function getMeetingStartLink($meeting_id){
    $sql=db_query("SELECT page_type,page_title from meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC limit 1", db());
    $page_data=array_shift($sql);
    $page_title=$page_data['page_title'];
    switch($page_data['page_type']){
        case 'Check-in':
            $page_url="meeting_checkin.php";
            break; 
        case 'Metrics':
            $page_url="meeting_metrics.php";
            break; 
        case 'Projects':
            $page_url="meeting_projects.php";
            break; 
        case 'Task':
            $page_url="meeting_tasks.php";
            break; 
        case 'Issues':
            $page_url="meeting_issues.php";
           break; 
        case 'Conclude':
            $page_url="meeting_conclude.php";
            break; 
        default:
            $page_url="meeting_edit_pages.php";
    } 
    return array('page_url'=>$page_url,'page_title'=>$page_title);
}

function getMeetingOwnerPage($meeting_timer_id, $meeting_owner){
    $sql=db_query("SELECT current_page from meeting_live_updates where meeting_timer_id=$meeting_timer_id and attendee_id=$meeting_owner limit 1", db());
    $current_page=array_shift($sql)['current_page'];
    return $current_page;
}
function removeCookieDataAfterMeetingEnded(){
    $date_of_expiry = time() - 3600;
    setcookie( "meeting_id", "", $date_of_expiry );
    setcookie( "meeting_timer_id", "", $date_of_expiry );
    setcookie( "meeting_name", "", $date_of_expiry );
    setcookie( "meeting_owner", "", $date_of_expiry );
    setcookie( "meeting_join_status", "", $date_of_expiry );
    unset($_COOKIE['meeting_id']);
    unset($_COOKIE['meeting_timer_id']);
    unset($_COOKIE['meeting_name']);
    unset($_COOKIE['meeting_owner']);
    unset($_COOKIE['meeting_join_status']);
}
function getMeetingProjectDataStartMeetingAfterAction($meeting_id){
	$project_sql=db_query("SELECT project_id,project_name,project_description,project_status_id,project_deadline,project_owner,Headshot, name,initials FROM project_master JOIN loop_employees ON project_master.project_owner=loop_employees.b2b_id where find_in_set($meeting_id,meeting_ids) and archive_status=0 ORDER BY project_id DESC", db());
	$result=array();
	while($r = array_shift($project_sql)){
        $milestone_qry=db_query("SELECT checked,milestone,milestone_date from project_milestones where project_id='".$r['project_id']."'", db());
        $milestone_array=array();
        while($milestones = array_shift($milestone_qry)){
            $milestone_array[]=array(
                'checked' => $milestones["checked"],
                'milestone' => $milestones["milestone"],
                'milestone_date' => $milestones["milestone_date"],
            );
        }
		$empDetails=getOwerHeadshotForMeeting($r['Headshot'],$r['initials']);
		$status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']);
		$status_name=$status_data['status_name'];
		$status_class=$status_data['status_class'];
		$status_icon=$status_data['status_icon'];
		$result[]=array('project_status_id'=>$r['project_status_id'],
		'project_status'=>$status_name,
		'status_class'=>$status_class,
		'status_icon'=>$status_icon,
		'project_name' => $r["project_name"],
        'project_deadline' => $r["project_deadline"],
        'project_description' => $r["project_name"],
        'project_owner' => $r["project_owner"],
        'project_description' => $r["project_description"],
        'project_id'=>$r['project_id'],
        'name'=>$r['name'],
        'emp_img'=>$empDetails['emp_img'], 
        'emp_txt'=>$empDetails['emp_txt'],
        'milestones'=>$milestone_array,
        );
	}
	return $result;
}
function getMeetingTaskDataStartMeetingAfterAction($meeting_id,$order,$meeting_timer_id){
    $task_sql=db_query("SELECT task_assignto,task_details,task_status,task_master.id,task_duedate,task_title,task_entered_by,task_entered_on,Headshot, name,initials FROM task_master JOIN loop_employees ON task_master.task_assignto=loop_employees.b2b_id where task_meeting=$meeting_id and archive_status=0 $order", db());
    while($r = array_shift($task_sql)){
        $empDetails=getOwerHeadshotForMeeting($r['Headshot'],$r['initials']); 
        $late_str="";
        if(strtotime(date("Y-m-d", strtotime($r['task_entered_on']))) == strtotime(date("Y-m-d"))){
            $late_str="<span class='todo-new'>New</span>";
        }else if((strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d"))) && $r['task_status'] == 0){
            $late_str="<span class='todo-late'>Late</span>";
        }
        $data[]=array(
            'task_id'=>$r['id'],
            'task_title'=>$r['task_title'],
            'emp_img'=>$empDetails['emp_img'],
            'emp_txt'=>$empDetails['emp_txt'],
            'task_assignto'=>$r['task_assignto'],
            'task_details'=>$r['task_details'],
            'task_status'=>$r['task_status'],
            'task_duedate'=>$r['task_duedate'],
            'late_str'=>$late_str,
        );       
    }
    $task_percentage=display_meeting_task_percentage($meeting_id,$meeting_timer_id); 
    return array('data'=>$data,'task_percentage'=>$task_percentage);
}
function getMeetingIssueDataStartMeetingAfterAction($meeting_id,$order){
    $issue_sql=db_query("SELECT issue_details,issue_master.created_on,issue_master.id,issue,order_no,issue_master.status,created_by,Headshot, name,initials FROM issue_master JOIN loop_employees ON issue_master.created_by=loop_employees.b2b_id where meeting_id=$meeting_id && issue_master.status=1 $order", db());
    $data=[];
    while($r = array_shift($issue_sql)){
            $empDetails=getOwerHeadshotForMeeting($r['Headshot'],$r['initials']);
            $data[]=array(
                'issue_id'=>$r['id'],
                'issue'=>$r['issue'],
                'emp_img'=>$empDetails['emp_img'],
                'emp_txt'=>$empDetails['emp_txt'],
                'status'=>$r['status'],
                'order_no'=>$r['order_no'],
                'created_by'=>$r['created_by'],
                'issue_details'=>$r['issue_details'],
                'created_on'=>$r['created_on'],
            );
        }
    return $data;
}
function getRatingData($meeting_timer_id){
    $qry=db_query("SELECT meeting_start_atten_ratings.id,attendee_id,rating,Headshot, name,initials from meeting_start_atten_ratings JOIN loop_employees ON meeting_start_atten_ratings.attendee_id=loop_employees.b2b_id  where meeting_timer_id=".$meeting_timer_id,db());
    $data=[];
    while($r = array_shift($qry)){
        $empDetails=getOwerHeadshotForMeeting($r["Headshot"],$r["initials"]);
        $data[]=array(
            'emp_img'=>$empDetails['emp_img'],
            'emp_txt'=>$empDetails['emp_txt'],
            'name'=>$r['name'],
            'id'=>$r['id'],
            'rating'=>$r['rating'],
        );
    }
    return $data;
} 

function getOnlineAttendee($meeting_timer_id){
    $online_attendee=db_query("SELECT attendee_id,Headshot, name,initials from meeting_start_atten_ratings  JOIN loop_employees ON meeting_start_atten_ratings.attendee_id=loop_employees.b2b_id  where join_status=1 && meeting_timer_id=".$meeting_timer_id,db());     
    $data=[];
    while($r = array_shift($online_attendee)){
        $empDetails=getOwerHeadshotForMeeting($r["Headshot"],$r["initials"]);
        $data[]=array(
            'emp_img'=>$empDetails['emp_img'],
            'emp_txt'=>$empDetails['emp_txt'],
        );
    }
    return $data;
}
function get_meeting_owner($meeting_timer_id){
    $meeting_timer_qry=db_query('SELECT meeting_start_by from meeting_timer where id='.$meeting_timer_id,db());
    return array_shift($meeting_timer_qry)['meeting_start_by'];
}

function getMetricsDataAfterStartMeetingAction($metricsMeetingID){

    $getWeekLimit = 16;
    $i = 0;
    while ($i <= $getWeekLimit) {
        $previous_week = strtotime("-$i week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        
        $start_week = date("M d",$start_week);
        $end_week = date("M d",$end_week);

        $scorecardweeks[] = $start_week." ".$end_week;
        $scorecardweeks_for_thead[] = $start_week."<br>".$end_week;
        $i++;
    }


    $scorecard_data_sql = "SELECT scorecard.id,scorecard.b2b_id,scorecard.name,scorecard.units,scorecard.goal,scorecard.goal_matric,Headshot,initials FROM scorecard JOIN loop_employees ON scorecard.b2b_id=loop_employees.b2b_id WHERE (scorecard.attach_meeting like '%-".$metricsMeetingID."-%' OR scorecard.attach_meeting like '%-".$metricsMeetingID."' OR scorecard.attach_meeting like '".$metricsMeetingID."-%' OR scorecard.attach_meeting = ".$metricsMeetingID.") AND (scorecard.archived = false) ORDER BY scorecard.meeting_create_order_no ASC";
    $scorecard_data_query = db_query($scorecard_data_sql,db());
    $concatedValue = '';
    while($scorecard_data = array_shift($scorecard_data_query)){

    $scorecard_weeks_id = $scorecard_data['id'];
    $scorecard_createdByID = $scorecard_data['b2b_id'];

    $scorecard_data_ImageFunc = getOwerHeadshotForMeeting($scorecard_data['Headshot'],$scorecard_data['initials']);
    $scorecardUserImage = $scorecard_data_ImageFunc['emp_img'];
    $scorecardUserText = $scorecard_data_ImageFunc['emp_txt'];

    $mesurableGoalSign = $scorecard_data["goal"] == '==' ? '=' : $scorecard_data['goal'];  
    $mesurableGoalValue = $scorecard_data['goal_matric'].$scorecard_data['units'];  
    
    $concatedValue .= '
        <tr data-sort-id="'.new_dash_encrypt($scorecard_weeks_id).'">
            <td><i class="fa fa-arrows"></i></td>
            <td class="matrics_attandees_img"><span class="attendees_img" style="background-image:url("'.$scorecardUserImage.'")">'.$scorecardUserText.'</span></td>
            <td class="td-border-bottom matrics_mesurable_name">
                <a id="measurableModal" type="button" class="" data-toggle="modal"
                    data-target="#scorecardAddMatrixModalPopop"
                    data-whatever="'.new_dash_encrypt($scorecard_weeks_id).'" data-todo=\'{"EditingFrom":"meetingStartMatrix"}\'>'.$scorecard_data['name'].'</a>
            </td>
            <td class="td-border-bottom matrics_mesurable_goal">'.$mesurableGoalSign.' '.$mesurableGoalValue.'</td>
            <td><i class="fa fa-line-chart"></i></td>
            ';
            if(isset($scorecardweeks_for_thead)){

                $scorecard_goal_matric = (int)$scorecard_data['old_goal_matric'] === 0 ? $scorecard_data['goal_matric'] : $scorecard_data['old_goal_matric'];
                $measurable_goal_matircs_data = $scorecard_data['goal_matric'];
                $sign = $scorecard_data['goal'];

                foreach($scorecardweeks_for_thead as $week){
                    $convertedWeek = str_replace('<br>', " to " , $week);
                    $inner_scorecard_data_sql = "SELECT * FROM `meeting_scorecard_week_data` where scorecard_id = '".$scorecard_weeks_id."' AND `scorecard_created_by` = '".$scorecard_createdByID."' AND `weeks` = '".$convertedWeek."'";
                    $inner_scorecard_data_query = db_query($inner_scorecard_data_sql,db());
                    if(!empty($inner_scorecard_data_query)){
                        while($inner_scorecard_data = array_shift($inner_scorecard_data_query)){
                            $meeting_scorecard_week_id = $inner_scorecard_data['id'];
                            $scorecard_id = $inner_scorecard_data['scorecard_id'];
                            $scorecard_created_by = $inner_scorecard_data['scorecard_created_by'];
                            $db_value = $inner_scorecard_data['value'];
                            $db_weeks = $inner_scorecard_data['weeks'];

                            if((int)$scorecard_id == (int)$scorecard_weeks_id && (int)$scorecard_created_by == (int)$scorecard_createdByID && $db_weeks == $convertedWeek){
                                $putValue = true;
                                if(isset($measurable_goal_matircs_data) && $measurable_goal_matircs_data != ''){
                                    if($sign == '<=>'){
                                        $between_num = $measurable_goal_matircs_data;
                                        $between_eploded_value = explode('-',$between_num);
                                        $between_min = $between_eploded_value[0];
                                        $between_max = $between_eploded_value[1];
                                        if($between_min <= $db_value && $db_value <= $between_max){
                                            $box_color = 'td-success';
                                        }else{
                                            $box_color = 'td-danger';
                                        }
                                    }else{
                                        $condition = "$db_value $sign $measurable_goal_matircs_data";
                                        if (eval("return ($condition);")) {
                                            $box_color = 'td-success';
                                        } else {
                                            $box_color = 'td-danger';
                                        }
                                    }                                                                    
                                }
                                if($scorecard_data['units'] === '%'){
                                    $tile_value_and_units = $db_value.$scorecard_data['units'];
                                }else{
                                    $tile_value_and_units = $scorecard_data['units'].$db_value;
                                }
                                $setBoxColor = $putValue == true ? $box_color : '';
                                $inputValue = isset($db_value) ? $tile_value_and_units : '';
                                $setinputValue = $putValue == true ? $inputValue : '';
                                $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','".$meeting_scorecard_week_id."','Update','meetingMatrics')";
                            }

                $concatedValue .= '
                                <td class="measurable_val_td '. $setBoxColor .'">
                                    <input type="text" class="edit_content text-center" onblur="'.$onblur.'" onkeyup="matrixvalidateInput(this)" value="'. $setinputValue .'" />
                                    <input type="hidden" class="tdweek" value="'.$convertedWeek.'">
                                </td>
                            ';
                    }
                    }else{
                        $putValue = false; 
                        $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','0','Insert','meetingMatrics')";
                $concatedValue .= '
                            <td class="measurable_val_td">
                                <input type="text" class="edit_content text-center" onblur="'.$onblur.'" onkeyup="matrixvalidateInput(this)" value="" />
                                <input type="hidden" class="tdweek" value="'. $convertedWeek .'">
                            </td>
                        ';
                    }
                }
            }
            $concatedValue .= '
            <input type="hidden" class="trID" value="'.new_dash_encrypt($scorecard_data['id']) .'">
        </tr>
        ';
    
    }

    return $concatedValue;
}

function display_meeting_conclusion_data($meeting_id,$meeting_timer_id){
    $qry_issue=db_query("SELECT id from meeting_minutes where meeting_timer_id=$meeting_timer_id && update_msg='Issue Marked Solved'",db());
    $solved_issue=tep_db_num_rows($qry_issue);    
    $qry_rating=db_query("SELECT rating from meeting_start_atten_ratings where rating!='' AND rating!=0 AND meeting_timer_id=".$meeting_timer_id,db());
    $count_rating=tep_db_num_rows($qry_rating);
    $total_rate=0;
    $avarage_rating="NA";
    if($count_rating>0){
        while($v = array_shift($qry_rating)){
            $total_rate=$total_rate+$v['rating'];
        }
        $a_rating=$total_rate/$count_rating;
       $avarage_rating=number_format((float)$a_rating, 2, '.', '');
    }
    $qry_time=db_query("SELECT start_time,end_time,completed_task_percentage from meeting_timer where id =$meeting_timer_id",db());
    $res=array_shift($qry_time);
    $start_time = date("h:i", strtotime($res['start_time']));
    $end_time= date("h:i", strtotime($res['end_time']));
    $todo_per=$res['completed_task_percentage']."%";
    $time=$start_time. " CT - ". $end_time. " CT";
    $diff_minutes = round(abs(strtotime($res['start_time']) -  strtotime($res['end_time'])) / 60,2). " minutes";
    
    return array('issue_solved'=>$solved_issue,'todo'=>$todo_per,'rating'=>$avarage_rating,'time'=>$time,'minutes'=>$diff_minutes);
}

?>