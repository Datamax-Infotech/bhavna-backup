<div class="modal fade" id="newIssueStartMeet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            
			<form action="" name="form_issue_start_meet" id="form_issue_start_meet" onsubmit="return false;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Add Issue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('issue')" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Issue <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="issue" id="issue_start_meet"/>
						  <p class="text-danger d-none form_error" id="issue_error_start_meet">Please enter Issue </p>
						  <input type="hidden" id="issue_action_start_meet" name="issue_action_start_meet" value="ADD">
					 </div>
					 <div class="col-md-12 form-group">
						<label>Detail <span class="text-danger">*</span> </label>
						<div id="summernote-issue-start_meet" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="issue_desc_error_start_meet">Please enter Issue details </p>
					 </div>
					  <div class="col-md-12 form-group owner_div_start_meeting">
						<label>Owner</label>
						<select class="search_existing_user_sel addOwnerStartMeet form-control form-control-sm select2" id="addOwnerStartMeet" name="issue_owner">
						</select>
						<p class="text-danger d-none form_error" id="issue_meeting_error">Please Select Meeting </p>
					  </div>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-right"></div>
					<div class="btn-right">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-issue_start_meet">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('issue')" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
					</div>
                </div>
            </div>
            
			</form>
        </div>
</div>
<div class="modal fade" id="newTaskStartMeet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
			<form action="task_action.php" method="post" name="form_task_start_meet" id="form_task_start_meet"  onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_task_title">Add New Task</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('task')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
				<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Task Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="task_title" id="task_title_start_meet">
						  <p class="text-danger d-none form_error" id="task_title_error_start_meet">Please enter Task title </p>
					 </div>
                     <div class="col-md-12 form-group owner_div_start_meeting">
						<label>Select Owner<span class="text-danger">*</span> </label>
                        <select class="search_existing_user_sel addOwnerStartMeet form-control form-control-sm select2" id="assignto_start_meet" name="assignto">
						</select>
						</select>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description <span class="text-danger">*</span> </label>
						<div id="summernote-task_start_meet" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="task_desc_error_start_meet">Please enter task description </p>
					 </div>
					 <div class="col-md-6 form-group">
						  <label>Due Date <span class="text-danger">*</span> </label>
						  <input type="date" class="form-control form-control-sm" placeholder="" name="task_duedate" id="task_duedate_start_meet">
						  <p class="text-danger d-none form_error" id="duedate_error_start_meet">Please select Due Date </p>
					 </div>
                     <div class="col-md-6 form-group">
						  <label>Date Created <span class="text-danger">*</span> </label>
						  <input type="date" class="form-control form-control-sm" placeholder="" name="task_created" id="task_created_start_meet">
						  <p class="text-danger d-none form_error" id="task_created_start_meet">Please select Due Date </p>
					 </div>
					 <div class="col-md-6 form-group">
						<div id="task_depd_hide" class="d-none"></div>
						 <input type="hidden" id="task_action_start_meet" name="task_action_start_meet" value="ADD">
					 </div>
					</div>	 
				</div>
                <div class="modal-footer justify-content-between">
                    <div class="btn-left">
					</div>
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save Task" style="cursor:pointer;" class="btn btn-dark" id="save-task_start_meet">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('task')" style="cursor:pointer;" type="button" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</form>
            </div>
        </div>
</div>
<div class="modal fade" id="copyIssueStartMeet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            
			<form action="" name="copy_form_issue_start_meet" id="copy_form_issue_start_meet" onsubmit="return false;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Copy Issue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('issue')" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Issue </label>
						  <p id="copy_issue_title"></p>
						  <input type="hidden" id="copy_issue_action" name="copy_issue_action" value="COPY">
                          <input type="hidden" id="copy_issue_id" name="issue_id" value="">
					 </div>
					
					  <div class="col-md-12 form-group issue_div_start_meeting">
						<label>Copy Into</label>
						<select class="form-control form-control-sm select2" id="copy_to_meeting" name="copy_to_meeting">
                            <?php
                               $sql_main =db_query("SELECT * FROM meeting_master where status = 1 && id!=0 && id!=".$_COOKIE['meeting_id']." ORDER BY id DESC", db_project_mgmt());
                                while($main_row = array_shift($sql_main)){
                                    echo '<option value="'.$main_row['id'].'">'.$main_row['meeting_name'].'</option>';
                                }
                            ?>
						</select>
					  </div>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-right"></div>
					<div class="btn-right">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="copy-issue_start_meet">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('issue')" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
					</div>
                </div>
            </div>
            
			</form>
        </div>
</div>

<script>
    var pause_live_data=false;
        function add_project(project){
            if(project=="new"){
                $("#addNewProject").removeClass('d-none');
                $("#addExistingProject").addClass('d-none');
            }else{
                $("#addExistingProject").removeClass('d-none');
                $("#addNewProject").addClass('d-none');
            }
            $('#project_action_meet').val('ADD_FROM_MEET_START_MEETING');
        } 
        $(document).ready(function(){
            $("#form_project_meet").submit(function(e){
                var flag = true;
                //e.preventDefault();
                var project_title_meet=$('#project_title_meet').val();
                var dept_id_meet=$('#dept_id_meet').val();
                var project_priority_id_meet=$('#project_priority_id_meet').val();
                var deadline_meet=$('#deadline_meet').val();
                var description = $('#summernote-project-meet').summernote('code');
                var pstatus_id_meet=$('#pstatus_id_meet').val();
                var project_action_meet=$('#project_action_meet').val();
                var flag = true;
                if(project_title_meet == ""){
                    $("#project_title_meet_error").removeClass('d-none');
                    flag=false;
                }else{
                    $("#project_title_meet_error").addClass('d-none');
                }
                
                if(dept_id_meet== ""){
                    $("#dept_id_meet_error").removeClass('d-none');
                    flag=false;
                }else{
                    $("#dept_id_meet_error").addClass('d-none');
                }
                if(deadline_meet== ""){
                    $("#deadline_meet_error").removeClass('d-none');
                    flag=false;
                }else{
                    $("#deadline_meet_error").addClass('d-none');
                }
                if(description=="" || description=="<p><br></p>"){
                    $("#project_desc_error_meet").removeClass('d-none');
                    flag=false;
                }else{
                    $("#project_desc_error_meet").addClass('d-none');
                }
                var project_action_meet=$('#project_action_meet').val();
                if(flag==true){
                    var all_data=new FormData(this);
                    all_data.append('project_desc', description);
                    var milestone_check_arr=$('#form_project_meet input[name="milestone_check"');
                    var milestone_check=[];
                    $.each(milestone_check_arr, function(k,v){
                        var check=$(v).prop('checked')==true ? 1 : 0;
                        milestone_check.push(check);
                    });
                    all_data.append('milestone_check_box',JSON.stringify(milestone_check));
                    all_data.append('meeting_id',$('#hidden_meeting_id').val());
                    $.ajax({
                        url:'project_action.php',
                        type:'post',
                        data:all_data,
                        datatype:'json',
                        contentType: false,
                        processData: false,
                        async:false,
                        beforeSend: function () {
                            $('#new-project-meet').attr('disabled',true);
                            $('#new-project-meet').prev('.spinner').removeClass('d-none');
                        },
                        success:function(response){
                            var all_data=JSON.parse(response);
                            displayProjectDataAfterStartMeetingAction(all_data);
                            $('#addProjectModalMeetCreate').modal('hide');
                            formSubmitMessage("Project Added To Meeting!");
                        },
                        complete:function () {
                            $('#form_project_meet').trigger("reset");
                            $('#summernote-project-meet').summernote('reset');
                            $('#project_meetings_meet').val($('#hidden_meeting_id').val()).trigger("change");	     
                            $('.addMilestoneTable tbody').empty();
                            $('.addMilestoneTable').addClass('d-none');
                            $('#new-project-meet').attr('disabled',false);
                            $('#new-project-meet').prev('.spinner').addClass('d-none');
                            add_project('existing')
                        },
                    });
                }
                return false;
            });
        const optionFormatMetrics = (item) => {
                if (!item.id) {
                    return item.text;
                }
                if (!item.value==0) {
                    return item.text;
                }
                var span = document.createElement('span');
                var template = '';
                template += '<div class="d-flex align-items-center">';
                var img_src=item.element.getAttribute('data-kt-rich-content-icon');
                var emp_txt=item.element.getAttribute('data-kt-rich-content-emp-txt');
                template += '<span class="attendees_img bg-info" style="background-image:url('+img_src+ ')">'+emp_txt+'</span>';
                template += '<span class="ml-1">' + item.text + '</span>';
                template += '<span class="ml-1 searchbox_subtitle">' + item.element.getAttribute('data-kt-rich-content-desc'); + '</span>';
                template += '</div>';
                span.innerHTML = template;

                return $(span);
            }

            $('#searchExistingProject').select2({
                width:"100%",
                templateSelection: optionFormatMetrics,
                templateResult: optionFormatMetrics
            });

            const optionFormatOwner = (item) => {
                if (!item.id) {
                    return item.text;
                }
                if (!item.value==0) {
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

            $('.addOwnerStartMeet').select2({
                width:"100%",
                templateSelection: optionFormatOwner,
                templateResult: optionFormatOwner
            });
        });
        function displayProjectDataAfterStartMeetingAction(res){
            if(res.length==0){
                $('#availabe_project_start_meet').removeClass('d-flex').addClass('d-none');
                $('#no_project_start_meet').removeClass('d-none').addClass('d-block');
            }else{
                var project_data="";
                    $.each(res,function(i,data){
    
                    var mt_cls= (i==0? '' : ' mt-3');
                    project_data+='<div class="card'+mt_cls+'" id="meeting_project_div_'+data.project_id+'" >';
                    project_data+='<div class="card-body p-2">';
                    project_data+='<div class="d-flex align-items-center">';
                    var emp_img="background-image:url('"+data.emp_img+"')";
                    project_data+='<span class="attendees_img" style="'+emp_img+'">'+data.emp_txt+'</span><span class="sr-only">'+data.name+'</span>';
                    project_data+='<h6 class="ml-2 mb-0 project_owner"><b>'+data.name+'</b></h6>';
                    project_data+='</div>';
                    project_data+='<table class="meetingRocksTable mb-0 table table_vetical_align_middle table-sm border-0">';
                    project_data+='<tr>';
                    project_data+='<td class="project_title td_w_65"><a href="javascript:void(0);" project_id="'+data.project_id+'" class="showRocksDetails">'+data.project_name+'</a></td>';
                    project_data+='<td class="text-center">';
                    project_data+='<span project_id="'+data.project_id+'" status_id="'+data.project_status_id+'" class="meeting_status_view status_css '+data.status_class+'">';
                    project_data+= (data.status_icon+' '+data.project_status)+'</span>';
                    project_data+='</td>';
                    project_data+='<td class="td_w_10">';
                    project_data+='<i class="fa fa-exclamation-circle fa-lg mx-1 add_issue_to_project" project_id="'+data.project_id+'" data-placement="bottom" title="Create a Context-Aware Issue™"></i>';
                    project_data+='<i class="fa fa-check-square-o fa-lg mx-1 add_task_to_project" project_id="'+data.project_id+'" data-placement="bottom" title="Create a Context-Aware To-do"></i>';
                    project_data+='</td></tr></table></div></div>';
                });
                $('#availabe_project_start_meet').removeClass('d-none').addClass('d-flex');
                $('#no_project_start_meet').removeClass('d-block').addClass('d-none');
                $('#meeting_start_projects').html(project_data);
            }
        }
        $('#addNewProjectMeet').click(function(){
            var meeting_id='<?=$_COOKIE['meeting_id']?>';
            $("#project_meetings_meet").val(meeting_id).trigger('change');
            $.ajax({
                url:'project_action.php',
                type:'post',
                data:{'meeting_project_list':1,meeting_id},
                datatype:'json',
                async:false,
                beforeSend: function () {
                    $('#searchExistingProject').html('<option value="0" data-kt-rich-content-icon="">Loading.....</option>');
                },
                success:function(response){
                    var res=JSON.parse(response);
                    if(res.length==0){
                        var user_option='<option value="0" data-kt-rich-content-icon=""  data-kt-rich-content-emp-txt="">No Project Found...</option>';
                    }else{
                        var user_option="<option></option>";
                        $.each(res,function(i,data){
                            user_option+='<option value="'+data.project_id+'" data-kt-rich-content-icon="'+data.emp_img+'"  data-kt-rich-content-emp-txt="'+data.emp_txt+'" data-kt-rich-content-desc="Owner: '+data.project_owner+'">'+data.project_name+'</option>';
                        });
                    }
                    $('#searchExistingProject').html(user_option);
                }
            });
        });

        $('#add_existing_project').submit(function(){
            var project_id= $('#searchExistingProject').val();
            var meeting_id='<?=$_COOKIE['meeting_id']?>';
            $.ajax({
                url:'project_action.php',
                type:'post',
                data:{'add_existing_project_meeting':2, project_id,meeting_id},
                datatype:'json',
                async:false,
                beforeSend: function () {
                    $('#save-existing-project').attr('disabled',true);
					$('#save-existing-project').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                    displayProjectDataAfterStartMeetingAction(JSON.parse(response));
                    $('#searchExistingProject').val('').trigger("change");   
                },
                complete:function(){
                    $('#save-existing-project').attr('disabled',false);
					$('#save-existing-project').prev('.spinner').addClass('d-none');
                    $('#addProjectModalMeetCreate').modal('hide');
                    formSubmitMessage("Project Added To Meeting!");
                }
            });
        })

        $(document).on('click','.add_issue_to_project',function(){
            var project_id=$(this).attr('project_id');
            $.ajax({
                url:'project_action.php',
                type:'get',
                data:{project_id, 'project_detail_to_add_task_issue':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#issue_start_meet').val(result.project_name);
                    var isssue_details="Week: "+result.project_deadline+" <br> Owner: "+result.name+" <br> Marked: "+result.ts_name;
                    $('#summernote-issue-start_meet').summernote('code',isssue_details);
                }
            });
            $('#newIssueStartMeet').modal('show');
        });
        $(document).on('click','.add_issue_start_meet_common',function(){
            var project_id=$(this).attr('project_id');
            $.ajax({
                url:'project_action.php',
                type:'get',
                data:{project_id, 'project_detail_to_add_task_issue':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#issue_start_meet').val(result.project_name);
                    var isssue_details="Week: "+result.project_deadline+" <br> Owner: "+result.name+" <br> Marked: "+result.ts_name;
                    $('#summernote-issue-start_meet').summernote('code',isssue_details);

                }
            });
            $('#newIssueStartMeet').modal('show');
        });
        

        $("#form_issue_start_meet").submit(function(e){
            var flag = true;
            var issue=$('#issue_start_meet').val();
            var description = $('#summernote-issue_start_meet').summernote('code');
            var flag = true;
            if(issue == ""){
                $("#issue_error_start_meet").removeClass('d-none');
                flag=false;
            }else{
                $("#issue_error_start_meet").addClass('d-none');
            }
            if(description=="" || description=="<p><br></p>"){
                $("#issue_desc_error_start_meet").removeClass('d-none');
                flag=false;
            }else{
                $("#issue_desc_error_start_meet").addClass('d-none');
            }
            if(flag==true){
                var all_data=new FormData(this);
                all_data.append('issue_desc', description);
                $.ajax({
                    url:'issue_action.php',
                    type:'post',
                    data:all_data,
                    datatype:'json',
                    contentType: false,
                    processData: false,
                    async:false,
                    beforeSend: function () {
                        $('#save-issue_start_meet').attr('disabled',true);
                        $('#save-issue_start_meet').prev('.spinner').removeClass('d-none');
                    },
                    success:function(response){
                        displayIssueDataAfterMeetingAction(response);
                        formSubmitMessage("Context-Aware Issue Created!");
                    },
                    complete:function () {
                        $('#issue_start_meet').val();
                        $('#save-issue_start_meet').attr('disabled',false);
                        $('#save-issue_start_meet').prev('.spinner').addClass('d-none');
                        $('#summernote-issue_start_meet').summernote('code',"");
                        $('#newIssueStartMeet').modal('hide');
                    },
                });
            }
            return false;
        });

        $(document).on('click','.add_task_to_project',function(){
            var project_id=$(this).attr('project_id');
            $.ajax({
                url:'project_action.php',
                type:'get',
                data:{project_id, 'project_detail_to_add_task_issue':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#task_duedate_start_meet').val(result.project_deadline);
                    $('#task_created_start_meet').val(result.entered_on);
                    var task_details=result.project_name+"<br>Week: "+result.project_deadline+" <br> Owner: "+result.name;
                    $('#summernote-task_start_meet').summernote('code',task_details);
                }
            });
            $('#newTaskStartMeet').modal('show');

        });
    $("#form_task_start_meet").submit(function(e){
        var flag = true;
        var task_title=$('#task_title_start_meet').val();
        var description = $('#summernote-task_start_meet').summernote('code');
        var task_duedate=$('#task_duedate_start_meet').val();
        var task_created=$('#task_created_start_meet').val();
        if(task_title == ""){
            $("#task_title_error_start_meet").removeClass('d-none');
            flag=false;
        }else{
            $("#task_title_error_start_meet").addClass('d-none');
        }
        
        if(description=="" || description=="<p><br></p>"){
            $("#task_desc_error_start_meet").removeClass('d-none');
            flag=false;
        }else{
            $("#task_desc_error_start_meet").addClass('d-none');
        }
        if(task_duedate== ""){
            $("#duedate_error_start_meet").removeClass('d-none');
            flag=false;
        }else{
            $("#duedate_error_start_meet").addClass('d-none');
        }
        if(task_created== ""){
            $("#task_created_error_start_meet").removeClass('d-none');
            flag=false;
        }else{
            $("#task_created_error_start_meet").addClass('d-none');
        }
        var task_action=$('#task_action').val();
        if(flag==true){
            var all_data=new FormData(this);
            all_data.append('task_desc',description);
            $.ajax({
                url:'task_action.php',
                type:'post',
                data:all_data,
                datatype:'json',
                contentType: false,
                processData: false,
                async:false,
                beforeSend: function () {
                        $('#save-task_start_meet').attr('disabled',true);
                        $('#save-task_start_meet').prev('.spinner').removeClass('d-none');
                    },
                    success:function(response){
                        displayTaskDataAfterMeetingAction(JSON.parse(response));
                        formSubmitMessage("Context-Aware Task Created!");
                    },
                    complete:function () {
                        $('#task_title_start_meet').val();
                        $('#task_duedate_start_meet').val();
                        $('#task_created_start_meet').val();    
                        $('#summernote-task_start_meet').summernote('code',"");
                        $('#save-task_start_meet').attr('disabled',false);
                        $('#save-task_start_meet').prev('.spinner').addClass('d-none');
                        $('#newTaskStartMeet').modal('hide');
                    },
            });
        }
        return false;
    });
    function displayIssueDataAfterMeetingAction(response){
        var res=JSON.parse(response);
       
        if(res.length==0){
			$('#availabe_issue_start_meet').removeClass('d-flex').addClass('d-none');
            $('#no_issue_available_start_meet').removeClass('d-none').addClass('d-block');
		}else{ 
            var issue_tr="";
            $.each(res,function(k,data){
                var emp_img="background-image:url('"+data.emp_img+"')";
                var rank_str='<i class="fa fa-exclamation"></i>';
                var rank_class="";
                var data_rank_str="";
                if(data.order_no!=0 && data.order_no!=""){
                    rank_str=data.order_no;
                    rank_class=" ranking-done";
                    data_rank_str=' data-rank='+data.order_no;
                }
                issue_tr+='<tr id="meeting_issue_tr_'+data.issue_id+'">';
                issue_tr+='<td class="show_sr_no td_w_5">'+(k+1)+'</td>'
                issue_tr+='<td class="rank_issue_td td_w_5"><span id="issue_'+data.issue_id+'" '+data_rank_str+' issue_id="'+data.issue_id+'" class="rank'+rank_class+'" data-placement="bottom" title="Click to rank issue">'+rank_str+'</span></td>';
                issue_tr+='<td class="attendee_img_td td_w_5"><span class="attendees_img" style="'+emp_img+'">'+data.emp_txt+'</span></td>';
                issue_tr+='<td><a class="showIssueDetails issue_title"  title="Click any issue to show details" href="javascript:void(0);" issue_id="'+data.issue_id+'">'+data.issue+'</a></td>';
                issue_tr+='<td class="text-right meetingMinuteNoWrap">';
                issue_tr+='<span class="mx-1"><i class="fa fa-share copy_issue_to_another_meeting" issue_id="'+data.issue_id+'" data-placement="bottom" title="Move issue to another meeting"></i></span>';
                issue_tr+='<span class="mx-1"><i class="fa fa-check-square-o add_task_to_issue" issue_id="'+data.issue_id+'" data-placement="bottom" title="Create a Context-Aware To-Do"></i></span>';
                issue_tr+='<button class="btn btn-light btn-sm mx-1 solve_meeting_issue" issue_id="'+data.issue_id+'">Solve</button>';
                issue_tr+='</td>';
                issue_tr+='</tr>';
            });
            $('#availabe_issue_start_meet').removeClass('d-none').addClass('d-flex');
            $('#no_issue_available_start_meet').removeClass('d-block').addClass('d-none');
            $('#meetingIssueTable tbody').html(issue_tr);
        }
    }

    function displayTaskDataAfterMeetingAction(res){
        if(res.length==0){
			$('#availabe_task_start_meet').removeClass('d-flex').addClass('d-none');
            $('#no_task_available_start_meet').removeClass('d-none').addClass('d-block');
		}else{
            var task_tr="";
            $.each(res.data,function(k,data){
            task_tr+='<tr id="meeting_task_div_'+data.task_id+'">';
            task_tr+='<td class="td_w_5"><input class="checkbox_lg d-flex task_status " task_id="'+data.task_id+'" type="checkbox"'+(data.task_status==1 ? ' checked ' : '')+'/></td>';
            task_tr+='<td class="td_w_5"><span class="attendees_img" style="background-image:url("'+data.emp_img+'")">'+data.emp_txt+'</span></td>';
            task_tr+='<td class="w-100"><a class="showToDoDetails task_title" href="javascript:void(0);" task_id="'+data.task_id+'">'+data.task_title+'</a></td>';
            task_tr+='<td><i class="fa fa-exclamation-circle fa-lg add_issue_to_task"  task_id="'+data.task_id+'" data-tooltip="true"  data-placement="bottom" title="Create a Context-Aware Issue™"></i></td>';
            task_tr+='</tr>';
            });
            $('#availabe_task_start_meet').removeClass('d-none').addClass('d-flex');
            $('#no_task_available_start_meet').removeClass('d-block').addClass('d-none');
            $('#meetingTODO tbody').html(task_tr);
            $('#meeting-todo-percentage').html(res.task_percentage);
        }
    }


    $(document).on('click','.add_issue_to_task',function(){
            var task_id=$(this).attr('task_id');
            $.ajax({
                url:'task_action.php',
                type:'get',
                data:{task_id, 'task_detail_to_add_issue':1,task_id},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#issue_start_meet').val(result.task_title);
                    $('#summernote-issue-start_meet').summernote('code',result.task_details);
                    $('#addOwnerStartMeet').val(result.task_assignto).trigger('change');
                }
            });
            $('#newIssueStartMeet').modal('show');
        });

    function getAttendeeOfCurrentMeeting(){
        $.ajax({
            url:'dashboard_meeting_action.php',
            type:'get',
            data:{'fetch_attendee_of_meeting':1},
            datatype:'json',
            async:false,
            success:function(response){
                
                var res=JSON.parse(response);
                var owner_str="";
                $.each(res,function(k,v){
                   owner_str+='<option value="'+v.emp_b2b_id+'" data-kt-rich-content-icon="'+v.emp_img+'" data-kt-rich-content-emp-txt="'+v.emp_txt+'">'+v.empname+'</option>'
                });
                $('#addOwnerStartMeet').html(owner_str);
                $('#assignto_start_meet').html(owner_str);
            }
        })
    }
    getAttendeeOfCurrentMeeting();

    $(document).on('click','.copy_issue_to_another_meeting',function(){
            var issue_id=$(this).attr('issue_id');
            $.ajax({
                url:'issue_action.php',
                type:'get',
                data:{issue_id,'issue_detail_copy_issue':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#copy_issue_title').html(result.issue);
                    $('#copy_issue_id').val(result.id);
                }
            });
            $('#copyIssueStartMeet').modal('show');
        });

    $("#copy_form_issue_start_meet").submit(function(e){
                var all_data=new FormData(this);
                $.ajax({
                    url:'issue_action.php',
                    type:'post',
                    data:all_data,
                    datatype:'json',
                    contentType: false,
                    processData: false,
                    async:false,
                    beforeSend: function () {
                        $('#copy-issue_start_meet').attr('disabled',true);
                        $('#copy-issue_start_meet').prev('.spinner').removeClass('d-none');
                    },
                    success:function(response){
                        formSubmitMessage("Issue Copied To Another Meeting!");
                    },
                    complete:function () {
                        $('#copy_issue_title').html("");
                        $('#copy_issue_id').val("");
                        $('#copy-issue_start_meet').attr('disabled',false);
                        $('#copy-issue_start_meet').prev('.spinner').addClass('d-none');
                        $('#copyIssueStartMeet').modal('hide');
                    },
                });
        });

        $(document).on('click','.add_task_to_issue',function(){
            var issue_id=$(this).attr('issue_id');
            $.ajax({
                url:'issue_action.php',
                type:'get',
                data:{issue_id, 'issue_detail_to_add_task':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#task_duedate_start_meet').val(result.created_on);
                    $('#task_created_start_meet').val(result.created_on);
                    $('#assignto_start_meet').val(result.created_by).trigger("change");	
                    $()
                    var task_details="RESOLVE ISSUE: "+result.issue;
                    $('#summernote-task_start_meet').summernote('code',task_details);
                }
            });
            $('#newTaskStartMeet').modal('show');

        });

        $('.change_meeting_page').click(function(){
            var page_type=$(this).find('a').text();
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{page_type,page_change_action:"update_page_change"},
                datatype:'html',
                success:function(response){
                
                },
            });
        })
        $(document).ready(function(){
            //Update Live meeting changes on Page Load:-            
            var pathname = window.location.pathname;
            var current_page=pathname.substring(pathname.lastIndexOf('/') + 1);
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{update_page_refresh:1,current_page},
                datatype:'html',
                success:function(response){
                   
                },
            }); 
            setInterval(getUpdatesFromLiveChanges, 5000);
            function getUpdatesFromLiveChanges(){
                /*if(pause_live_data==true){
                    setInterval(function () {pause_live_data=false}, 60000);
                }*/
                if(pause_live_data==false){
                $.ajax({
                    url:'dashboard_meeting_action.php',
                    type:'get',
                    data:{get_live_data_of_meeting:1},
                    datatype:'html',
                    success:function(response){
                    var res=JSON.parse(response);
                    if(res.project_flg==1){
                        displayProjectDataAfterStartMeetingAction(res.project_data);
                    }
                    },
                });
                }
            }
        })
       
</script>