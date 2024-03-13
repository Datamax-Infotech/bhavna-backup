<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 
    //require('meeting_common_function.php');
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
            <div>
        <?php 
       $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
       $meeting_timer_id= isset($_GET['meeting_timer_id']) && $_GET['meeting_timer_id']!="" ? new_dash_decrypt($_GET['meeting_timer_id']) :"";
       if($meeting_id!=""){
        $sidebar_links="";$top_links="common_top_links"; 
        require("meeting_start_common_links.php");
        ?> 
        <div class="container-fluid  mt-0 mb-4" >
            <div class="row justify-content-center mt-4">
                    <?php $top_links=""; $sidebar_links ="common_sidebar_links";
                    require("meeting_start_common_links.php");?>
                <div class="col-md-10">
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <div class="card shadow p-4">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2><b>Recap Our To-do</b></h2>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <img src="assets_new_dashboard/img/notes.svg" class="img-fluid"/>
                                    </div>
                                    <div class="py-2 col-md-12">
                                        <?php
                                        $task_sql=db_query("SELECT task_status,task_master.id,task_duedate,task_title,task_entered_by,task_entered_on,task_assignto,added_during_meeting FROM task_master where (task_master.task_status = 0) and task_meeting=$meeting_id and archive_status=0 ORDER BY id DESC", db_project_mgmt());
                                        if(tep_db_num_rows($task_sql)>0){?>
                                            <table id="meetingTODO" class="meetingTODOIssue table table_vetical_align_middle table-sm border-0 mb-0">
                                            <tbody>
                                            <?php while($r = array_shift($task_sql)){
                                                $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
                                                $empDetails_arr=array_shift($empDetails_qry);
                                                $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 

                                                $late_str="";
                                                if($r['added_during_meeting']==1){
                                                    $late_str="<span class='todo-new'>New</span>";
                                                }else if(strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d"))){
                                                    $late_str="<span class='todo-late'>Late</span>";
                                                }
                                                ?>        
                                                <tr>
                                                    <td class=" td_w_5"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $empDetails_arr['name']; ?></span></td>
                                                    <td class="w-100"><a type="button" href="javascript:void(0);" class="task_title" <?=$r['task_status']==2 ? "style='text-decoration:line-through'": "";?>><?php echo $r['task_title']; ?></a></td>
                                                    <td class="late_str"><?php echo $late_str; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php }else{
                                           echo '<div class="alert alert-danger">No Task Available</div>';
                                        }?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <? 
                                        $conclusion_data = display_meeting_conclusion_data($meeting_id,$meeting_timer_id);
                                    ?>
                                    <div class="col-md-6 pl-0">
                                        <div class="card shadow p-3 d-flex justify-content-center align-items-center border-top-primary">
                                            <img src="assets_new_dashboard/img/issues-solved.svg" class="img-fluid"/>   
                                            <p>ISSUES SOLVED</p>
                                            <h3><b><?php echo $conclusion_data['issue_solved']; ?></b></h3>    
                                        </div>
                                    </div>
                                    <div class="col-md-6 pr-0">
                                        <div class="card shadow p-3 d-flex justify-content-center align-items-center border-top-primary">
                                            <img src="assets_new_dashboard/img/todo-completion.svg" class="img-fluid"/>  
                                            <p>TO-DO COMPLETION</p>
                                            <h3><b><?= $conclusion_data['todo'];?></b></h3>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 card shadow p-4">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2><b>Ratings</b></h2>
                                        <p>Rate the meeting from 1-10 Ratings less than an 8? Drop it down to the Issues list.</p>
                                        <div class="row" id="rating_main_div">
                                            <?php 
                                            $qry=db_query("SELECT meeting_start_atten_ratings.id,attendee_id,rating from meeting_start_atten_ratings where meeting_timer_id=".$meeting_timer_id,db_project_mgmt());
                                            while($r = array_shift($qry)){
                                                $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['attendee_id']."'",db());
                                                $empDetails_arr=array_shift($empDetails_qry);
                                                $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                                            ?>
                                            <div class="col-md-6 mt-3">
                                                <div class="d-flex align-items-center justify-content-between rating_input">
                                                <span class="d-flex align-items-center">
                                                    <span class="attendees_img mr-3" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span>
                                                    <?php echo $empDetails_arr['name']; ?>
                                                </span>
                                                <input rating_table_id="<?php echo $r['id'];?>"  type="number" max="10" min="1" placeholder="-" class="form-control form-control-sm td_w_15 meeting_ratings" value="<?php echo $r['rating'];?>"/>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>    
                                    </div>
                                    <div class="col-md-2">
                                        <img src="assets_new_dashboard/img/ratings.svg" class="img-fluid"/>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 card shadow p-4">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h2><b>Conclude</b></h2>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="assets_new_dashboard/img/notebook.svg" class="img-fluid"/>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row bold_form_label">
                                            <div class="col-md-6 form-group">
                                                <label>Send Email Summary</label>
                                                <select id="send_meeting_email_to" class="form-control form-control-sm mt-2" name="send_email_summary">
                                                    <option value="0">No one.</option>
                                                    <option selected value="1">To all attendees.</option>
                                                    <option value="2">To all that rated the meeting.</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Archive Completed To-do</label>
                                                <select id="archive_completed_meeting_todo" class="form-control form-control-sm mt-2" name="archive_completed_meeting_todo">
                                                    <option selected value="yes">Yes</option>
                                                    <option  value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php 
                                            $meeting_owner=get_meeting_owner($meeting_timer_id);
                                            if($meeting_owner==$_COOKIE['b2b_id']){?>
                                            <div class="d-none spinner spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <a id="conclude-meeting-btn" class="btn btn-dark btn-sm" href="javascript:void(0)" onclick="finish_meeting(<?=$meeting_timer_id;?>)">Conclude</a>
                                        <?php }else{?>
                                            <a class="btn btn-primary btn-sm" href="dashboard_meetings.php" onclick="leave_meeting(<?=$meeting_timer_id;?>)" >Leave Meeting</a>
                                            <a class="btn btn-secondary btn-sm btn-disabled" href="javascript:void(0)" disabled>Only owner have rights to Conclude this meeting</a>
                                        <?php } ?> 

                                       <!-- <a class="btn btn-primary btn-sm" href="dashboard_meetings.php" onclick="leave_meeting(<?=$meeting_timer_id;?>)" >Leave Meeting</a>
                                        <a class="btn btn-dark btn-sm" href="javascript:void(0)" onclick="finish_meeting(<?=$meeting_timer_id;;?>)">Conclude</a>
                                        --> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php }else{?>
            <div class="col-md-12 alert alert-danger">
                <p class="mb-0"><a href="dashboard_meetings.php"><b>Click</b></a> here to choose the Meeting First!</p>
            </div>
        <?php } ?>
        
        <?php require_once("inc/footer_new_dashboard.php");?>
	    </div>
        </div>
    </div>

    <?
    require_once("meeting_start_common_top_create.php");
    ?>  
    <script>
    $(document).ready(function() { 
        $('#conclude').addClass('active_user_page');
    });
    $(document).on('blur','.meeting_ratings',function(){
       var rating_table_id= $(this).attr('rating_table_id');
       var rating=$(this).val();
       $.ajax({
            url:'dashboard_meeting_action.php',
            type:'get',
            data:{rating_table_id,rating,'update_rating':1,meeting_timer_id:"<?=$meeting_timer_id;?>", meeting_id:"<?=$meeting_id;?>"},
            datatype:'json',
            async:false,
            success:function(response){
                var res=JSON.parse(response);
                displayRatings(res);
                formSubmitMessage("Rating Done");
            }
       });
    })

    function leave_meeting(meeting_timer_id){
        $.ajax({
            url:'dashboard_meeting_action.php',
            type:'get',
            data:{'leave_meeting_action':1,meeting_timer_id,meeting_id:"<?= $meeting_id?>"},
            async:false,
            success:function(response){
            }
       });
    }
    function finish_meeting(meeting_timer_id){
        var send_meeting_email_to=$('#send_meeting_email_to').val();
        var archive_completed_meeting_todo=$("#archive_completed_meeting_todo").val();
        var ajax_data_str="?finish_meeting_action=1 &meeting_id=<?= $meeting_id; ?>  &meeting_timer_id=<?= $meeting_timer_id; ?> &send_meeting_email_to="+send_meeting_email_to+"&archive_completed_meeting_todo="+archive_completed_meeting_todo;        
        $.ajax({
            url:'dashboard_meeting_action.php'+ajax_data_str,
            type:'get',
            async:false,
            success:function(response){
                var res=JSON.parse(response);
                var para_str='tid='+res.tid+'&&mid='+res.mid;
                window.location.href = 'meeting_conclusion_finish.php?'+para_str;	
            }					
        });
    }

    function finish_meeting1(meeting_timer_id){
        var send_meeting_email_to=$('#send_meeting_email_to').val();
        var archive_completed_meeting_todo=$("#archive_completed_meeting_todo").val();
        var ajax_data_str="?finish_meeting_action=1 &meeting_id=<?= $meeting_id; ?>  &meeting_timer_id=<?= $meeting_timer_id; ?> &send_meeting_email_to="+send_meeting_email_to+"&archive_completed_meeting_todo="+archive_completed_meeting_todo;        
        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        }else{
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhr.onreadystatechange=function(res){
            if (xhr.readyState==4 && xhr.status==200){
                alert("Meeting Finish.Good Buy");
                $('#conclude-meeting-btn').attr('disabled',false);
		        $('#conclude-meeting-btn').prev('.spinner').addClass('d-none');
                var res=JSON.parse(xhr.responseText);
                var para_str='tid='+res.tid+'&&mid='+res.mid;
                //window.location.href = 'meeting_conclusion_finish.php?'+para_str;						
            }
        }
        $('#conclude-meeting-btn').attr('disabled',true);
		$('#conclude-meeting-btn').prev('.spinner').removeClass('d-none');
        xhr.open("POST","dashboard_meeting_action.php"+ajax_data_str,true);			
        xhr.send();	
	}

    </script>
</body>

</html>