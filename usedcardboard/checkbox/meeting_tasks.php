<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php");
	
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
            <div>
        <?php 
         $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
         $meeting_timer_id= isset($_GET['meeting_timer_id']) && $_GET['meeting_timer_id']!="" ? new_dash_decrypt($_GET['meeting_timer_id']) :"";
        if($meeting_id!="" && $meeting_timer_id!=""){
            $sidebar_links="";$top_links="common_top_links"; 
            require("meeting_start_common_links.php");
        ?>
        <div class="container-fluid  mt-0" >
            <div class="row justify-content-center mt-4">
                    <?php $top_links=""; $sidebar_links ="common_sidebar_links";
                    require("meeting_start_common_links.php");?>
                <div class="col-md-10">
                <?php 
                db_query("UPDATE meeting_live_updates set task_flg=0 where meeting_timer_id='".$_COOKIE['meeting_timer_id']."' && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
                $task_order_str_qry=db_query("SELECT task_order from meeting_live_updates where meeting_timer_id='".$_COOKIE['meeting_timer_id']."' && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
                $task_order=array_shift($task_order_str_qry)['task_order'];
                $order_str=$task_order=="" ? "ORDER BY id DESC": $task_order;
                $sort_task=0;
                switch($order_str){
                    case "ORDER BY task_assignto ASC" : $sort_task=1; break; 
                    case "ORDER BY task_status ASC" : $sort_task=2; break; 
                    case "ORDER BY task_duedate DESC" : $sort_task=3; break; 
                    case "ORDER BY task_duedate ASC" : $sort_task=4; break; 
                    case "ORDER BY task_entered_on ASC" : $sort_task=5;break;  
                    case "ORDER BY task_entered_on DESC" : $sort_task=6; 
                }
                $task_sql=db_query("SELECT task_status,task_master.id,task_duedate,task_title,task_entered_by,task_entered_on,task_assignto
				FROM task_master  where  archive_status=0 and task_meeting=$meeting_id $order_str, id DESC", db_project_mgmt());
                $no_data_div="d-none";
                $present_data_div="d-flex";
                if(tep_db_num_rows($task_sql)==0){
                    $no_data_div="d-block";
                    $present_data_div="d-none";    
                 } 
                ?>
                    <div class="card shadow mb-4 <?php echo $no_data_div; ?>" id="no_task_available_start_meet">
                        <div class="card-body min_height_500 d-flex justify-content-center align-items-center text-center">
                        <div>
                            <img src="assets_new_dashboard/img/todo-completion.svg" class="img-fluid"/>
                            <h4 class="mt-2"><b>No Task</b></h4>
                        </div>
                        </div>
                    </div>
                    <div class="row <?php echo $present_data_div; ?>" id="availabe_task_start_meet">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between">
                                    <h6 class="mb-0">
                                        <b>Task List</b>
                                            <span class="meeting-todo-percentage"><span id="meeting-todo-percentage"> <?php echo display_meeting_task_percentage($meeting_id,$meeting_timer_id); ?></span>%</span>
                                    </h6>
                                    <select class="form-control form-control-sm w-25"  id="sort_task">
                                        <option value="0" <?=$sort_task==0 ? "selected ": ""; ?>>Sort</option>
                                        <option value="1" <?=$sort_task==1 ? "selected ": ""; ?>>By Owner</option>
                                        <option value="2" <?=$sort_task==2 ? "selected ": ""; ?>>Incomplete</option>
                                        <option value="3" <?=$sort_task==3 ? "selected ": ""; ?>>By Due Date (Desc)</option>
                                        <option value="4" <?=$sort_task==4 ? "selected ": ""; ?>>By Due Date (Asc)</option>
                                        <option value="5" <?=$sort_task==5 ? "selected ": ""; ?>>By Date Created (Oldest)</option>
                                        <option value="6" <?=$sort_task==6 ? "selected ": ""; ?>>By Date Created (Newest)</option>
                                    </select>
                                    </div>  
                                    <table id="meetingTODO" class="meetingTODOIssue table table_vetical_align_middle table-sm border-0 mb-0">
                                        <tbody>
                                        <?php while($r = array_shift($task_sql)){
                                                $late_str="";
                                                if(strtotime(date("Y-m-d", strtotime($r['task_entered_on']))) == strtotime(date("Y-m-d"))){
                                                    $late_str="<span class='todo-new'>New</span>";
                                                }else if(strtotime(date("Y-m-d", strtotime($r['task_duedate']))) < strtotime(date("Y-m-d")) && $r['task_status'] == 0){
                                                    $late_str="<span class='todo-late'>Late</span>";
                                                }
                                                $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['task_assignto']."'",db());
                                                $empDetails_arr=array_shift($empDetails_qry);
                                                $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                                            ?>        
                                            <tr id="meeting_task_div_<?php echo $r['id'];?>">
                                                <td class="td_w_5"><input type="checkbox" task_id="<?php echo $r['id'];?>" class="checkbox_lg d-flex task_status " <?php echo $r['task_status']==1 || $r['task_status']==2 ?  'checked ' : "";?>/></td>
                                                <td class="td_w_5"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $r['name']; ?></span></td>
                                                <td class="w-100"><a type="button" <?=$r['task_status']==2 || $r['task_status']==1 ? "style='text-decoration:line-through'": "";?> href="javascript:void(0);" class="showToDoDetails task_title" task_id="<?php echo $r['id']; ?>"><?php echo $r['task_title']; ?></a></td>
                                                <td class="late_str_td"><?php echo $late_str; ?></td>
                                                <td><i class="fa fa-exclamation-circle fa-lg add_issue_to_task" data-tooltip="true" task_id="<?php echo $r['id']; ?>" data-placement="bottom" title="Create a Context-Aware Issue™"></i></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                            <div class="card-body" id="todo-no-details">
                                <div class="min_height_500 d-flex justify-content-center align-items-center text-center">
                                Click a To-do to view its details.
                                </div>
                            </div>
                            <div class="card-body d-none" id="todo-all-details">
                            <form id="meeting_task_save" onsubmit="return false">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="todo-text">
                                                <input type="hidden" id="meeting_task_id_edit" name="meeting_task_id_edit" />
                                                <input type="hidden" id="meeting_task_edit" name="meeting_task_action" value="meeting_task_edit" />
                                                
                                            <p contentEditable="true" class="contentEditable"><span id="meeting_task_title"></span><span class="fa fa-pencil content_edit_icon"></span</p>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="date" data-placement="bottom" title="Due Date" class="form-control form-control-sm" id="meeting_task_duedate" name="task_duedate"/>
                                    </div>
                                    <div class="col-md-12 form-group summer_note_small_size">
                                        <div id="meeting-summernote-task" class="summernote"></div> 
                                        <p class="small-font mt-1">Date Created: <span id="meeting_task_created_on"></span></p>
                                    </div>
                                    <div class="form-row align-items-center">
                                    <div class="col-md-7">
                                    <div class="reports_to_div search_existing_user col-md-12 meeting_assigned_todo">
                                            <!--<select id="meeting_task_assigned_to_id" class="search_existing_user_sel form-control form-control-sm select2" name="assignto"  data-placeholder="Search To assign Task ..." >
                                                <option></option>
                                            <?
                                            $result = db_query("SELECT Headshot,b2b_id, name,initials FROM loop_employees where status='Active' order by id" , db());
                                            while ($hrow = array_shift($result)) {
                                                $empDetails=getOwerHeadshotForMeeting($hrow["Headshot"],$hrow["initials"]);
                                                ?>
                                                <option value="<?php echo $hrow["b2b_id"];?>"  data-kt-rich-content-icon="<?php echo $empDetails['emp_img']; ?>" data-kt-rich-content-emp-txt="<?php echo $empDetails['emp_txt']; ?>"><?php echo $hrow["name"];?></option>
                                            <?php } ?>
                                            </select>-->
                                            
                                            <?= getAllEmployeeWithImgForMeetingForms("meeting_task_assigned_to_id","assignto");?>
                                        </div>
                                    </div>
                                    <div class="col-md-5 d-flex align-items-center">
                                        <i class="fa fa-exclamation-circle fa-lg mx-2 add_issue_to_task" id="id_add_issue_to_task" task_id="" data-tooltip="true"  data-placement="bottom" title="Create a Context-Aware Issue™"></i>
                                        <input type="checkbox" class="checkbox_lg d-block mx-2" id="meeting_task_status"/>Completed
                                    </div>
                                    <div class="col-md-12 text-right">
                                    <div class="d-none spinner spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <input type="submit" value="Save Changes" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-task-meeting-changes">
                                    </div>
                                    </div>
                                </div>
                                </form>
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
        
    <?php require_once("inc/footer_new_dashboard.php"); ?>
    </div>
    </div>
	</div>

   <?
    require_once("meeting_start_common_top_create.php");
    ?>  
    <script>
        
    $(document).ready(function() { 
        $('#tasks').addClass('active_user_page');
        const optionFormat1 = (item) => {
            if (!item.id) {
                return item.text;
            }
            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex align-items-center">';
            var img_src=item.element.getAttribute('data-kt-rich-content-icon');
            var emp_txt=item.element.getAttribute('data-kt-rich-content-emp-txt');
            template += '<span class="attendees_img bg-info" style="background-image:url('+img_src+ ')">'+emp_txt+'</span>';
            template += '<span class="ml-1">' + item.text + '</span>';
            template += '</div>';
            span.innerHTML = template;
            return $(span);        
        }

        $('.search_existing_user_sel').select2({
                width:"100%",
                templateSelection: optionFormat1,
                templateResult: optionFormat1
         });
        $(document).on('click', '.showToDoDetails', function(){
            $('#meetingTODO tr').removeClass('active_row');
            $(this).parents('tr').addClass('active_row')
            $('#todo-all-details').removeClass('d-none'); 
            $('#todo-no-details').addClass('d-none'); 
            var task_id=$(this).attr('task_id');
            $.ajax({
                url:'task_action.php',
                type:'get',
                data:{task_id, edit_task:1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#meeting_task_title').text(result.task_title);
                    $('#meeting-summernote-task').summernote('code',result.task_details);
                    $("#meeting_task_assigned_to_id").val(result.task_assignto).trigger('change');
                    $('#meeting_task_duedate').removeClass('text-danger');
                    if(result.task_status==0 && new Date(result.task_duedate) < new Date()){
                        $('#meeting_task_duedate').addClass('text-danger');
                    }
                    $('#meeting_task_duedate').val(result.task_duedate);
                    var date = new Date(result.task_entered_on);

                    $('#meeting_task_created_on').html(date.getMonth()+"/"+date.getDate()+"/"+date.getFullYear()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds());
                    result.task_status==1 ||  result.task_status==2 ? $('#meeting_task_status').attr('checked',true): $('#meeting_task_status').attr('checked',false);
                    $('#meeting_task_id_edit').val(task_id);      
                    $('#id_add_issue_to_task').attr('task_id',task_id);         
                }
            });
        });
        $('#meeting_task_save').submit(function(){
            pause_live_data=true;
            var all_data=new FormData(this);
            var description = $('#meeting-summernote-task').summernote('code');
            var task_title=$("#meeting_task_title").text();
            var task_status=0;
            if ($('#meeting_task_status').is(":checked")) {
              task_status=1;
            }
            all_data.append('task_desc', description)
            all_data.append('task_title',task_title);
            all_data.append('task_status',task_status);   
            all_data.append('meeting_id',"<?=$meeting_id;?>");
            all_data.append('meeting_timer_id',"<?=$meeting_timer_id;?>");
            $.ajax({
                url:'task_action.php',
                type:'post',
                data:all_data,
                datatype:'json',
                contentType: false,
                processData: false,
                async:false,
                beforeSend: function () {
                    $('#save-task-meeting-changes').attr('disabled',true);
                    $('#save-task-meeting-changes').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                    var d=JSON.parse(response);
                    $('#meeting_task_div_'+d.task_id+' .task_title').html(d.task_title);
                    $('#meeting_task_div_'+d.task_id+' .attendees_img').css('background-image','url("'+d.emp_img+'")');
                    $('#meeting_task_div_'+d.task_id+' .attendees_img').html(d.emp_txt);
                    $('#meeting_task_div_'+d.task_id+' .late_str_td').html(d.late_str);
                    if(d.task_status==1 || d.task_status==2 ){
                        $('#meeting_task_div_'+d.task_id+' .task_status').attr('checked',true);
                        $('#meeting_task_div_'+d.task_id+' .task_title').css('text-decoration','line-through');
                    }else{
                        $('#meeting_task_div_'+d.task_id+' .task_status').attr('checked',false);
                        $('#meeting_task_div_'+d.task_id+' .task_title').css('text-decoration','none');
                    }
                    $('#meeting-todo-percentage').html(d.task_percentage);
                    formSubmitMessage("Task Updated!");	
                },
                complete:function () {
                    $('#save-task-meeting-changes').attr('disabled',false);
                    $('#save-task-meeting-changes').prev('.spinner').addClass('d-none');
                    pause_live_data=false;
                },
            });
        });

        $(document).on('click','.task_status', function(){
		var task_status=0;
		var current_ele=$(this);
		if ($(this).is(":checked")) {
		   task_status=2;
		}					
		var task_id=$(this).attr('task_id');
		var data={task_status,task_id, update_meeting_task_status:1,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",};
		var result="";
		$.ajax({
			url:'task_action.php',
			type:'get',
			data:data,
			datatype:'json',
			async: false, 
			success:function(response){
                /*var d=JSON.parse(response);
				var sel="#task_tr_"+task_id+" .td_task_title a";
				if(task_status==1){
					$(sel).addClass('task-completed');
				}else{
					$(sel).removeClass('task-completed');
				}
                var late_str="";
                if(d.task_status==0 && new Date(d.task_duedate) < new Date()){
                    late_str="<span class='todo-late'>Late</span>";
                }
                $('#meeting_task_div_'+task_id+' .late_str').html(late_str);
                $('#meeting-todo-percentage').html(d.task_percentage);
                */
                displayTaskDataAfterMeetingAction(JSON.parse(response));   
                formSubmitMessage("Task Status Updated!");
			}
		});
    });

    $(document).on('change','#sort_task',function(){
        pause_live_data=true;
        var sort_order=$('#sort_task').val();
        $.ajax({
			url:'task_action.php',
			type:'get',
			data:{sort_order,sort_meeting_task:1,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",},
			datatype:'json',
			async: false, 
			success:function(response){
                displayTaskDataAfterMeetingAction(JSON.parse(response));
                formSubmitMessage("Task Order Updated!");
			},
            complete:function(){
                pause_live_data=false;
            }
		});
    })


    });
    

    </script>
</body>

</html>