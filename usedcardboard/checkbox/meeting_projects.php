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
        <div class="container-fluid  mt-0 mb-3" >
            <div class="row justify-content-center mt-4">
                    <?php $top_links=""; $sidebar_links ="common_sidebar_links";
                    require("meeting_start_common_links.php");?>
                <div class="col-md-10">
                <?php
                    db_query("UPDATE meeting_live_updates set project_flg=0 where meeting_timer_id='".$_COOKIE['meeting_timer_id']."' && attendee_id='".$_COOKIE['b2b_id']."'",db_project_mgmt());
                    $project_sql=db_query("SELECT project_id,project_name,project_status_id,project_deadline,project_owner FROM project_master where find_in_set($meeting_id,meeting_ids) and archive_status = 0 ORDER BY project_id DESC", db_project_mgmt());
                    $no_data_div="d-none";
                    $present_data_div="d-flex";
                    if(tep_db_num_rows($project_sql)==0){
                        $no_data_div="d-block";
                        $present_data_div="d-none";    
                    }?> 
                    <div class="card shadow mb-4  <?php echo $no_data_div;?>" id="no_project_start_meet">
                        <div class="card-body min_height_500 d-flex justify-content-center align-items-center text-center">
                        <div>
                            <img src="assets_new_dashboard/img/empty_state_rocks.svg" class="img-fluid"/>
                            <h4 class="mt-2"><b>No Current Projects</b></h4>
                        </div>
                        </div>
                    </div>
                    <div class="row <?php echo $present_data_div; ?>"  id="availabe_project_start_meet">
                        <div class="col-md-8" id="meeting_start_projects">
                        <?php $i=1; while($r = array_shift($project_sql)){
                            $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['project_owner']."'",db());
                            $empDetails_arr=array_shift($empDetails_qry);
                            $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                            ?>
                            <div class="card <?php echo $i==1? "" : "mt-3"; ?>" id="meeting_project_div_<?php echo $r['project_id']; ?>" >
                                <div class="card-body p-2">
                                <div class="d-flex align-items-center">
                                    <span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span>
                                    <h6 class="ml-2 mb-0 project_owner"><b> <?php echo $empDetails_arr['name']; ?></b></h6>
                                </div>
                                    <table class="meetingRocksTable mb-0 table table_vetical_align_middle table-sm border-0">
                                        <tr>
                                            <td class="project_title td_w_65"><a href="javascript:void(0);" project_id="<?php echo $r['project_id']; ?>" class="showRocksDetails"><?php echo $r['project_name'];?></a></td>
                                            <td class="text-center ">
                                                <?php $status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']); ?>
                                                <span project_id="<?php echo $r['project_id'];?>" status_id="<?php echo $r['project_status_id'];?>" class="meeting_status_view status_css <?php echo $status_data['status_class'];?>">
                                                    <?php echo $status_data['status_icon']." ".$status_data['status_name']." "; ?>
                                                </span>
                                            </td>
                                            <td class="td_w_10">
                                                <i class="fa fa-exclamation-circle fa-lg mx-1 add_issue_to_project" project_id="<?php echo $r['project_id']; ?>" data-placement="bottom" title="Create a Context-Aware Issue™"></i>
                                                <i class="fa fa-check-square-o fa-lg mx-1 add_task_to_project"  project_id="<?php echo $r['project_id']; ?>" data-placement="bottom" title="Create a Context-Aware To-do"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php $i++; } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                            <div class="card-body" id="rock-no-details">
                                <div class="min_height_500 d-flex justify-content-center align-items-center text-center">
                                Click a Project Title to view its details.
                                </div>
                            </div>
                            <div class="card-body d-none" id="rock-all-details">
                                <form id="meeting_project_save" onsubmit="return false">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="todo-text">
                                                <input type="hidden" id="meeting_project_id_edit" name="meeting_project_id_edit" />
                                                <input type="hidden" id="meeting_project_edit" name="meeting_project_action" value="meeting_project_edit" />
                                                <p contentEditable="true" class="contentEditable"><span  id="meeting_project_title"></span><span class="fa fa-pencil content_edit_icon"></span</p>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="date" id="meeting_project_deadline" data-placement="bottom" title="Due Date" class="form-control form-control-sm" name="project_deadline"/>
                                        </div>
                                        <div class="mt-1 col-md-12 form-group summer_note_small_size">
                                            <div id="meeting-summernote-project" class="summernote"></div> 
                                        </div>
                                        <div class="col-md-8">
                                        <div class="reports_to_div search_existing_user col-md-12 meeting_assigned_todo">
                                            <!--<select id="meeting_owner_id" class="search_existing_user_sel form-control form-control-sm select2" name="owner_id"  data-placeholder="Search for meeting owner..." >
                                                <option></option>
                                            <?
                                            $result = db_query("SELECT Headshot,b2b_id, name,initials FROM loop_employees where status='Active' order by id" , db());
                                            while ($hrow = array_shift($result)) {
                                                $empDetails=getOwerHeadshotForMeeting($hrow["Headshot"],$hrow["initials"]);
                                                ?>
                                                <option value="<?php echo $hrow["b2b_id"];?>"  data-kt-rich-content-icon="<?php echo $empDetails['emp_img']; ?>" data-kt-rich-content-emp-txt="<?php echo $empDetails['emp_txt']; ?>"><?php echo $hrow["name"];?></option>
                                            <?php } ?>
                                            </select>-->
                                            <?= getAllEmployeeWithImgForMeetingForms("meeting_owner_id","owner_id");?>
                                        
                                        </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-end">
                                            <button type="button" class="btn btn-light btn-sm mx-1" data-placement="bottom" title="Create a Context-Aware Issue™"><i class="fa fa-exclamation-circle fa-lg add_issue_to_project" id="id_add_issue_to_project" project_id=""></i></button>
                                            <button type="button" class="btn btn-light btn-sm mx-1" data-placement="bottom" title="Create a Context-Aware To-do"><i class="fa fa-check-square-o fa-lg add_task_to_project" id="id_add_task_to_project" project_id=""></i></button>
                                        </div>
                                        
                                        <div class="col-md-12 add_milestone_div">
                                                <div class="milstones mt-3">
                                                <p><b>Milestones</b> <button type="button" class="float-right btn btn-light btn-sm add_milestone_edit_pro"><i class="fa fa-plus"></i></button></p>
                                                <table class="addMilestoneTable_edit_pro table table_vetical_align_middle mt-2 border-0 table-sm">
                                                <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                        <div class="d-none spinner spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                            </div>
                                            <input type="submit" value="Save Changes" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-project-meeting-changes">
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
        <?php require_once("inc/footer_new_dashboard.php");?>
        </div>
    </div>
	</div>
    

   
    <?
    require_once('meeting_start_common_top_create.php');
     ?>  
    
  
    <script>
    $(document).ready(function() { 
        $('#projects').addClass('active_user_page');
        //$('#projects').addClass('active_page');
        $(document).on('click', '.showRocksDetails', function(){
           $('.meetingRocksTable tr').removeClass('active_row');
            $(this).parents('tr').addClass('active_row')
            $('#rock-all-details').removeClass('d-none'); 
            $('#rock-no-details').addClass('d-none'); 
            var project_id=$(this).attr('project_id');
            $.ajax({
                url:'project_action.php',
                type:'get',
                data:{project_id, edit_project:1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#meeting_project_title').text(result.project_name);
                    $('#meeting-summernote-project').summernote('code',result.project_description);
                    const meeting_id_arr = result.meeting_ids.split(',')
                    $('#meeting_project_deadline').val(result.project_deadline);
                    //$('#project_priority_id').val(result.project_priority_id);
                    $('#meeting_owner_id').val(result.project_owner).trigger('change');
                    $('#id_add_issue_to_project').attr('project_id',project_id);
                    $('#id_add_task_to_project').attr('project_id',project_id);
                    $('#meeting_project_save .addMilestoneTable_edit_pro tbody').empty();
                    //$('#pstatus_id').val(result.project_status_id);
                    var all_milestones=result.milestones;
                    if(all_milestones==""){
                        $('.addMilestoneTable_edit_pro').addClass('d-none');
                    }else{
                        $('.addMilestoneTable_edit_pro').removeClass('d-none');
                        
                        $.each(all_milestones,function(k,v){
                            var new_tr="<tr>";
                            new_tr+='<td><input type="checkbox" class="checkbox_lg" name="milestone_check"'+(v.checked == 1 ? "checked ": "")+'></td>';
                            new_tr+='<td><input type="text" class="form-control form-control-sm" name="milestone_title[]" value="'+v.milestone+'"/></td>';
                            new_tr+='<td><input type="date" class="form-control form-control-sm" name="milestone_date[]" value="'+v.milestone_date+'"/></td>';
                            new_tr+='<td><a href="javascript:void(0)" class="text-danger remove-milestone"><i class="fa fa-times fa-lg"></i></a></td>';
                            new_tr+="</tr>";
                            $('#meeting_project_save .addMilestoneTable_edit_pro tbody').append(new_tr);
                        })
                    }
                    $('#meeting_project_id_edit').val(project_id);
			    }
		    });
        });
        
        $('#meeting_project_save').submit(function(){
            var all_data=new FormData(this);
            var description = $('#meeting-summernote-project').summernote('code');
            var project_title=$("#meeting_project_title").text();
			all_data.append('project_desc', description)
            all_data.append('project_title',project_title);
            all_data.append('meeting_id',"<?=$meeting_id;?>");
            all_data.append('meeting_timer_id',"<?=$meeting_timer_id;?>");
            all_data.append('project_title',project_title);
            var milestone_check_arr=$('#meeting_project_save input[name="milestone_check"');
            var milestone_check=[];
            $.each(milestone_check_arr, function(k,v){
                var check=$(v).prop('checked')==true ? 1 : 0;
                milestone_check.push(check);
            });
            all_data.append('milestone_check_box',JSON.stringify(milestone_check));
            $.ajax({
                url:'project_action.php',
                type:'post',
                data:all_data,
                datatype:'json',
                contentType: false,
                processData: false,
                async:false,
                beforeSend: function () {
                    $('#save-project-meeting-changes').attr('disabled',true);
                    $('#save-project-meeting-changes').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                    var d=JSON.parse(response);
                    $('#meeting_project_div_'+d.project_id+' .project_title a').text(d.project_name);
                    $('#meeting_project_div_'+d.project_id+' .attendees_img').css('background-image','url("'+d.emp_img+'")');
                    $('#meeting_project_div_'+d.project_id+' .attendees_img').html(d.emp_txt);

                    $('#meeting_project_div_'+d.project_id+' .project_owner').text(d.name);
                    formSubmitMessage("Project Updated!");	
                },
                complete:function () {
                    $('#save-project-meeting-changes').attr('disabled',false);
                    $('#save-project-meeting-changes').prev('.spinner').addClass('d-none');
                },
            });
        })


        
        $(document).on('click','.meeting_status_view', function(){
        pause_live_data=true;
        var project_id=$(this).attr('project_id');
        var status_id=$(this).attr('status_id');
        var current_ele=$(this);
        $(this).attr('class',"");
        var data={"get_project_status":1};
        $.ajax({
            url:'project_action.php',
            type:'get',
            data:data,
            datatype:'json',
            async: false, 
            success:function(response){
                var result=JSON.parse(response);
                var status_select_box="<select class='form-control form-control-sm meeting_pstatus_select' project_id='"+project_id+"'>";
                $.each(result,function(i,v){
                    status_select_box+="<option value='"+v.status_id+"' ";
                        if(v.status_id==status_id){
                            status_select_box+=" selected"
                        }
                    status_select_box+=">"+v.status_name+"</option>"
                });
                $(current_ele).html(status_select_box);
            }
        });
            
        });

        $(document).on('change','.meeting_pstatus_select', function(){
		var project_id=$(this).attr('project_id');
		var pstatus_id=$(this).val();
        var current_ele=$(this);
		var data={"update_meeting_project_status":1,pstatus_id,project_id,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",};
		$.ajax({
			url:'project_action.php',
			type:'get',
			data:data,
			datatype:'json',
			async: false, 
			success:function(response){
				var res=JSON.parse(response);
                var span_cls='meeting_status_view status_css '+res.status_class;
                $(current_ele).parents('span').attr('class', span_cls);
                $(current_ele).parents('span').html(res.status_icon+" "+res.status_name);
                formSubmitMessage("Project Status Updated!");	
            },
            complete:function(){
                pause_live_data=false;
            }
		});
	})
        $('.add_another_milestone').click(function(){
                var new_tr="<tr>";
                new_tr+='<td><input type="checkbox" class="checkbox_lg"></td>';
                new_tr+='<td><input type="text" class="form-control form-control-sm" value=""/></td>';
                new_tr+='<td><input type="date" class="form-control form-control-sm" value=""/></td>';
                new_tr+='<td><a href="javascript:void(0)" class="text-danger remove-milestone"><i class="fa fa-times fa-lg"></i></a></td>';
                new_tr+="</tr>";
                $('.editMilestoneTable tbody').append(new_tr);
        });
        $(document).on('click','.remove-milestone', function(){
            $(this).parents('tr').remove();
        });
    });
   

    </script>

</body>

</html>