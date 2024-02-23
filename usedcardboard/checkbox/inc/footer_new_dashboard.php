        </div>
        </div>
        <!-- End of Main Content -->
		<!-- Footer -->
		<footer class="sticky-footer bg-white">
			<div class="container my-auto">
				<div class="copyright text-center my-auto">
					<span>Copyright &copy; Usedcardboard Boxes 2023</span>
				</div>
			</div>
			<div id="meeting_notification" class="meeting_notification"></div>
		</footer>
		<!-- End of Footer -->
    </div>
        <!-- End of Content Wrapper -->
  </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="form-submission-message" id="form-submission-message">
	<p class="mb-0" id="form-submit-message">Updated Successfully!</p>
	</div>

    <?php require_once('inc/common_modal.php'); ?> 

    <!-- Bootstrap core JavaScript-->
    <script src="assets_new_dashboard/vendor/jquery/jquery.min.js"></script>
    <script src="assets_new_dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets_new_dashboard/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets_new_dashboard/js/sb-admin-2.min.js"></script>
    <script src="assets_new_dashboard/js/jquery.dataTables.min.js"></script>
    <script src="assets_new_dashboard/js/dataTables.bootstrap4.min.js"></script>	 
	  <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>-->

    <script src="assets_new_dashboard/js/select2.full.min.js"></script>

	<script src="assets_new_dashboard/js/summernote.min.js"></script>
    
    <script src="assets_new_dashboard/js/dataTables.rowReorder.min.js"></script>

    
	<script src="assets_new_dashboard/js/custom.js"></script>

    <script>
		//$('#project_meetings').val([0]).trigger('change');
		$(document).ready(function() {	
		$('.modal').on('shown.bs.modal', function () {
			$('input:text:visible:first', this).focus();
		})  
	});
        function displayProjectDataAfterMeetingAction(res){
		if(res.length==0){
			$('#meetingProjects').removeClass('d-table').addClass('d-none');
			$('#no_project_available_div_create_meet').removeClass('d-none').addClass('d-block');
		}else{
			var project_tr="";
			$.each(res,function(i,data){
				project_tr+='<tr>';
				var emp_img="background-image:url('"+data.emp_img+"')";
				project_tr+='<td><span class="attendees_img" style="'+emp_img+'">'+data.emp_txt+'</span><span class="sr-only">'+data.name+'</span></td>';
				project_tr+='<td class="td_w_95"><a class="edit_title_all" href="javascript:edit_project('+data.project_id+','+"'editFromCreateMeet'"+')">'+data.project_name+'</a></td>';
				project_tr+='<td><i project_id='+data.project_id+' class="fa fa-trash-o fa-lg deleteProjectCreateMeet"></i></td>';
				project_tr+='</tr>';
			});
			$('#meetingProjects').removeClass('d-none').addClass('d-table');
			$('#no_project_available_div_create_meet').removeClass('d-block').addClass('d-none');
			$('#meetingProjects tbody').html(project_tr);
		}
	}

	function displayProjectDataAfterDashboardAction(all_data){
		$('#sidebar_project_count').text(all_data.total_projects);
		var new_table_data="";
		var check_for_no_data=true;
		$.each(all_data.data,function(index,d){
			if(d.count>0){
				check_for_no_data=false;
				new_table_data+='<table class="table table-sm border-0 project_meeting'+d.meeting_id+'" width="100%" cellspacing="0"><tbody>';
				new_table_data+='<tr><td colspan="4">';
				new_table_data+='<div class="card-subheading"><span>'+d.meeting_name+'</span></div>';
				new_table_data+='</td></tr>';
				$.each(d.data,function(k,v){
					new_table_data+='<tr class="project_tr_'+v.project_id+'">';
					new_table_data+='<td class="td_title"><a href="javascript:edit_project('+v.project_id+')">'+v.project_name+'</a></td>';
					//new_table_data+='<td class="td_project_description">'+v.project_description+'</td>';
					new_table_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
					var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
					new_table_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
					new_table_data+='</tr>';
				});
			}
			if(d.count>10){
				new_table_data+='<tr class="load_more_project_data">';
				new_table_data+='<td colspan="3" class="text-right">';
				new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='<button total_data="'+d.count+'" loaded_data="10" project_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-project">Load More</button>';										
				new_table_data+='</td></tr>';
			}
			new_table_data+='</tbody></table>';
		});
		if(check_for_no_data==true){
			new_table_data+='<div class="text-center no_issue_div">';
			new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
			new_table_data+='<p>No Current Projects</p>';
			new_table_data+='</div>';
		}
		$("#project_table").html(new_table_data);
	}

	function displayProjectDataAfterDashboardAction(all_data){
		$('#sidebar_project_count').text(all_data.total_projects);
		var new_table_data="";
		var check_for_no_data=true;
		$.each(all_data.data,function(index,d){
			if(d.count>0){
				check_for_no_data=false;
				new_table_data+='<table class="table table-sm border-0 project_meeting'+d.meeting_id+'" width="100%" cellspacing="0"><tbody>';
				new_table_data+='<tr><td colspan="4">';
				new_table_data+='<div class="card-subheading"><span>'+d.meeting_name+'</span></div>';
				new_table_data+='</td></tr>';
				$.each(d.data,function(k,v){
					new_table_data+='<tr class="project_tr_'+v.project_id+'">';
					new_table_data+='<td class="td_title"><a href="javascript:edit_project('+v.project_id+')">'+v.project_name+'</a></td>';
					//new_table_data+='<td class="td_project_description">'+v.project_description+'</td>';
					new_table_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
					var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
					new_table_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
					new_table_data+='</tr>';
				});
			}
			if(d.count>10){
				new_table_data+='<tr class="load_more_project_data">';
				new_table_data+='<td colspan="3" class="text-right">';
				new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='<button total_data="'+d.count+'" loaded_data="10" project_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-project">Load More</button>';										
				new_table_data+='</td></tr>';
			}
			new_table_data+='</tbody></table>';
		});
		if(check_for_no_data==true){
			new_table_data+='<div class="text-center no_issue_div">';
			new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
			new_table_data+='<p>No Current Projects</p>';
			new_table_data+='</div>';
		}
		$("#project_table").html(new_table_data);
	}

	function displayProjectDataAfterDashboardAction(all_data){
		$('#sidebar_project_count').text(all_data.total_projects);
		var new_table_data="";
		var check_for_no_data=true;
		$.each(all_data.data,function(index,d){
			if(d.count>0){
				check_for_no_data=false;
				new_table_data+='<table class="table table-sm border-0 project_meeting'+d.meeting_id+'" width="100%" cellspacing="0"><tbody>';
				new_table_data+='<tr><td colspan="4">';
				new_table_data+='<div class="card-subheading"><span>'+d.meeting_name+'</span></div>';
				new_table_data+='</td></tr>';
				$.each(d.data,function(k,v){
					new_table_data+='<tr class="project_tr_'+v.project_id+'">';
					new_table_data+='<td class="td_title"><a href="javascript:edit_project('+v.project_id+')">'+v.project_name+'</a></td>';
					//new_table_data+='<td class="td_project_description">'+v.project_description+'</td>';
					new_table_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
					var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
					new_table_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
					new_table_data+='</tr>';
				});
			}
			if(d.count>10){
				new_table_data+='<tr class="load_more_project_data">';
				new_table_data+='<td colspan="3" class="text-right">';
				new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='<button total_data="'+d.count+'" loaded_data="10" project_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-project">Load More</button>';										
				new_table_data+='</td></tr>';
			}
			new_table_data+='</tbody></table>';
		});
		if(check_for_no_data==true){
			new_table_data+='<div class="text-center no_issue_div">';
			new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
			new_table_data+='<p>No Current Projects</p>';
			new_table_data+='</div>';
		}
		$("#project_table").html(new_table_data);
	}
	$('#show_mine_issue').change(function(){
	var only_mine_ws=0;
	var meeting_id=$('#hidden_meeting_id').val();
	if($(this).prop('checked')){
		only_mine_ws=1;
	}
	$.ajax({
		url:'issue_action.php',
		type:'post',
		data:{only_mine_ws, meeting_id,issue_action:'show_filter_ws'},
		datatype:'json',
		async:false,
		beforeSend: function () {
			new_table_data='<div class="align-self-center text-center no_issue_div">';
			new_table_data+='<div class="spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
			new_table_data+='</div>';
			$("#meeting_issue_tbl").html(new_table_data);
		},
		success:function(response){
			var d=JSON.parse(response);
			displayIssueDataAfterWorkspaceAction(d);
		},
	});
});

	$('#show_mine_task').change(function(){
		var only_mine=0;
		var task_meeting=$('#hidden_meeting_id').val();
		if($(this).prop('checked')){
			only_mine=1;
		}
		$.ajax({
			url:'task_action.php',
			type:'post',
			data:{only_mine, task_meeting,task_action:'show_filter'},
			datatype:'json',
			async:false,
			beforeSend: function () {
				new_table_data='<div class="align-self-center text-center no_issue_div">';
				new_table_data+='<div class="spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='</div>';
				$("#task_table").html(new_table_data);
			},
			success:function(response){
				var d=JSON.parse(response);
				displayTaskDataAfterWorkspaceAction(d);
			},
		});
	});

	$('#show_mine_project').change(function(){
		var only_mine=0;
		var meeting_id=$('#hidden_meeting_id').val();
		if($(this).prop('checked')){
			only_mine=1;
		}
		$.ajax({
			url:'project_action.php',
			type:'post',
			data:{only_mine, meeting_id,project_action:'show_filter'},
			datatype:'json',
			async:false,
			beforeSend: function () {
				new_table_data='<div class="align-self-center text-center no_issue_div">';
				new_table_data+='<div class="spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='</div>';
				$("#project_table").html(new_table_data);
			},
			success:function(response){
				var d=JSON.parse(response);
				displayProjectDataAfterWorkspaceAction(d);
			},
		});
	});

	function displayProjectDataAfterWorkspaceAction(all_data){
		console.log(all_data);
		var new_table_data="";
		if(all_data.length>0){
		$.each(all_data,function(index,v){
			new_table_data+='<tr class="project_tr_'+v.project_id+'">';
			var emp_img="background-image:url('"+v.emp_img+"')";
			new_table_data+='<td class="attendee_img_td td_w_5"><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
			new_table_data+='<td class="td_title td_w_5"><a href="javascript:edit_project('+v.project_id+','+"'editFromWorkspace'"+')">'+v.project_name+'</a></td>';
			//new_table_data+='<td class="td_project_description">'+v.project_description+'</td>';
			new_table_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
			var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
			new_table_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
			new_table_data+='</tr>';
		});
	}else{
			new_table_data+='<div class="text-center no_issue_div">';
			new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
			new_table_data+='<p>No Current Projects</p>';
			new_table_data+='</div>';
	}
		$("#project_table").html(new_table_data);
	}

	function displayIssueDataAfterWorkspaceAction(all_data){
		console.log(all_data);
		var new_table_data="";
		if(all_data.length>0){
			$.each(all_data,function(k,v){
				var emp_img="background-image:url('"+v.emp_img+"')";
				new_table_data+='<tr id="issue_tr_'+v.issue_id+'">';
				new_table_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
				new_table_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.issue_id+','+"'editFromWorkspace'"+')">'+v.issue+'</a></td>';
				new_table_data+='</tr>';
			});
		}else{
			new_table_data+='<div class="text-center no_issue_div">';
			new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
			new_table_data+='<p>No Current Projects</p>';
			new_table_data+='</div>';
		}				
			$('#meeting_issue_tbl').html(new_table_data);
	}
	$(document).on('click','.deleteProjectCreateMeet',function(){
		var project_id=$(this).attr('project_id');
		var meeting_id=$('#hidden_meeting_id').val();
		$.ajax({
			url:'project_action.php',
			type:'post',
			data:{'delete_meeting_project':1, project_id,meeting_id},
			datatype:'json',
			async:false,
			success:function(response){
				displayProjectDataAfterMeetingAction(JSON.parse(response));
			},
			complete:function(){
				formSubmitMessage("Project Archived From Current Meeting!");
			}
			})
	});


    $(document).on('click','.status_view', function(){
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
			var status_select_box="<select class='form-control form-control-sm pstatus_select' project_id='"+project_id+"'>";
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

	function update_project_status(pstatus_id,project_id,deadline){
		var data={"update_project_status":1,pstatus_id,project_id,deadline};
		var result="";
		$.ajax({
			url:'project_action.php',
			type:'get',
			data:data,
			datatype:'json',
			async: false, 
			success:function(response){
				result=JSON.parse(response);
			}
		});
		return result;
	}
	$(document).on('change','.pstatus_select', function(){
		var project_id=$(this).attr('project_id');
		var deadline=$('#project_td_deadline_'+project_id).text();
		var pstatus_id=$(this).val();
		var res=update_project_status(pstatus_id,project_id,deadline);
		var span_cls='status_view status_css '+res.status_class;
		var deadline_class="td_date small-font "+res.deadline_class;
		$(this).parents('span').attr('class', span_cls);
		$(this).parents('span').html(res.status_icon+" "+res.status_name);
		$('#project_td_deadline_'+project_id).attr('class', deadline_class);
	})

	function edit_project(project_id, edit_from=""){
		$('#modal_project_title').html("Edit Project");
        $('#removeProject').removeClass('d-none');
		$("#newProject").modal('show');
		$.ajax({
			url:'project_action.php',
			type:'get',
			data:{project_id, edit_project:1},
			datatype:'json',
			async: false, 
			success:function(response){
				var result=JSON.parse(response);
				$('#project_title').val(result.project_name);
				$('#summernote-project').summernote('code',result.project_description);
				const meeting_id_arr = result.meeting_ids.split(',')
				$("#project_meetings").val(meeting_id_arr).trigger('change');
				$('#dept_id').val(result.project_dept_id);
				$('#deadline').val(result.project_deadline);
				$('#project_priority_id').val(result.project_priority_id);
				$('#owner_id').val(result.project_owner);
				$('#pstatus_id').val(result.project_status_id);
                var all_milestones=result.milestones;
                if(all_milestones==""){
                    $('.addMilestoneTable').addClass('d-none');
                }else{
                    $('.addMilestoneTable').removeClass('d-none');
                    $.each(all_milestones,function(k,v){
                        var new_tr="<tr>";
                        new_tr+='<td><input type="checkbox" class="checkbox_lg" name="milestone_check"'+(v.checked == 1 ? "checked ": "")+'></td>';
                        new_tr+='<td><input type="text" class="form-control form-control-sm" name="milestone_title[]" value="'+v.milestone+'"/></td>';
                        new_tr+='<td><input type="date" class="form-control form-control-sm" name="milestone_date[]" value="'+v.milestone_date+'"/></td>';
                        new_tr+='<td><a href="javascript:void(0)" class="text-danger remove-milestone"><i class="fa fa-times fa-lg"></i></a></td>';
                        new_tr+="</tr>";
                        $('.addMilestoneTable tbody').append(new_tr);
                    })
                }
                if(edit_from=="editFromCreateMeet" || edit_from=="editFromWorkspace"){
					$('#project_action').val(edit_from);
					if(edit_from=="editFromWorkspace"){
						$('#removeProject').attr('delete_from_workspace',1);
					}
				}else{
					$('#project_action').val('EDIT');
				}
				$('#project_id_edit').val(project_id);
                $('#removeProject').attr('project_id',project_id);
			}
		});
	}
	$(document).on('click','.show-more-project', function(){
		var load_type="project";
		var project_meeting_id=$(this).attr("project_meeting_id");
		var total_data=$(this).attr("total_data");
		var loaded_data=$(this).attr("loaded_data");
		var data={load_type,loaded_data, project_meeting_id};
		var current_ele=$(this);
		$.ajax({
			url:'project_action.php',
			type:'get',
			data:data,
			datatype:'json',
			beforeSend: function () {
				$(current_ele).prev('.spinner').removeClass('d-none');
				$(current_ele).attr('disabled',true);
			},
			success:function(response){
				var result=JSON.parse(response);
				var additional_data="";
				var count=0;
				$.each(result,function(k,v){
					additional_data+='<tr class="project_tr_'+v.project_id+'">';
					additional_data+='<td class="td_title"><a href="javascript:edit_project('+v.project_id+')">'+v.project_name+'</a></td>';
					//additional_data+='<td class="td_project_description">'+v.project_description+'</td>';
					additional_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
					var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
					additional_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
					additional_data+='</tr>';
					count++;
				});
				var new_count_loaded_data=Number(loaded_data)+count;
				$(current_ele).attr("loaded_data",new_count_loaded_data);
				if(total_data > new_count_loaded_data){
					$(current_ele).parents('.load_more_project_data').removeClass('d-none');
				}else{
					$(current_ele).parents('.load_more_project_data').addClass('d-none')
				}
				$(".project_meeting"+project_meeting_id+ " tr:last").before(additional_data);
			},
			complete: function () {
				$(current_ele).prev('.spinner').addClass('d-none');
				$(current_ele).attr('disabled',false);
			}	
		});
	});
	
	function GetFileSize() {
	var fi = document.getElementById('uploadscanrep'); // GET THE FILE INPUT.

	if (fi.files.length > 0) {
		for (var i = 0; i <= fi.files.length - 1; i++) {
			var filenm = fi.files.item(i).name;
			
			if (filenm.indexOf("#") > 0){
				$("#uploadscanrep_error").removeClass('d-none');
				$("#uploadscanrep_error").text("Remove # from Scan file and then upload file!");
				document.getElementById("uploadscanrep").value = "";
			}
			if (filenm.indexOf("\'") > 0){
				$("#uploadscanrep_error").removeClass('d-none');
				$("#uploadscanrep_error").text("Remove '\'' from Scan file "+filenm+" and then upload file!");
				document.getElementById("uploadscanrep").value = "";
			}	
			
		}
	}else{
		$("#uploadscanrep_error").addClass('d-none');
	}

}
	$(document).on('change','.owner_id_dp',function(){
		var current_emp=$(this).val();
		var add_type=$(this).attr('add_type');
		$.ajax({
			url:'project_action.php',
			type:'get',
			data:{current_emp, get_emp_department:1},
			async: false, 
			success:function(response){
				if(add_type=="top"){
					$('#dept_id').val(response);
				}else if(add_type=="meet"){
					$('#dept_id_meet').val(response);
				}
			}
		})
	})

	$("#form_project").submit(function(e){
        var flag = true;
		//e.preventDefault();
		var project_title=$('#project_title').val();
		var owner_id=$('#owner_id').val();
		var dept_id=$('#dept_id').val();
		var project_priority_id=$('#project_priority_id').val();
		var deadline=$('#deadline').val();
		var description = $('#summernote-project').summernote('code');
		var pstatus_id=$('#pstatus_id').val();
		var project_action=$('#project_action').val();
		var flag = true;
		if(project_title == ""){
			$("#project_title_error").removeClass('d-none');
			flag=false;
		}else{
			$("#project_title_error").addClass('d-none');
		}
		if(owner_id== ""){
			$("#owner_id_error").removeClass('d-none');
			flag=false;
		}else{
			$("#owner_id_error").addClass('d-none');
		}
		
		if(dept_id== ""){
			$("#dept_id_error").removeClass('d-none');
			flag=false;
		}else{
			$("#dept_id_error").addClass('d-none');
		}
		if(deadline== ""){
			$("#deadline_error").removeClass('d-none');
			flag=false;
		}else{
			$("#deadline_error").addClass('d-none');
		}
		//if(description=="" || description=="<p><br></p>"){
		//	$("#project_desc_error").removeClass('d-none');
		//	flag=false;
		//}else{
		//	$("#project_desc_error").addClass('d-none');
		//}
		var project_action=$('#project_action').val();
		if(flag==true){
			var all_data=new FormData(this);
			all_data.append('project_desc', description);
            var milestone_check_arr=$('#form_project input[name="milestone_check"');
            var milestone_check=[];
            $.each(milestone_check_arr, function(k,v){
                var check=$(v).prop('checked')==true ? 1 : 0;
                milestone_check.push(check);
            });
            all_data.append('milestone_check_box',JSON.stringify(milestone_check));
			if(project_action=="editFromCreateMeet"){
				all_data.append('meeting_id',$('#hidden_meeting_id').val());
			}
			if( project_action=="ADD_FROM_WORKSPACE" || project_action=="editFromWorkspace"){
				all_data.append('meeting_id',$('#hidden_meeting_id').val());
				var only_mine=0;
				if($('#show_mine_project').prop('checked')){
					only_mine=1;
				}
				all_data.append('only_mine',only_mine);
			}
			$.ajax({
				url:'project_action.php',
				type:'post',
				data:all_data,
				datatype:'json',
				contentType: false,
				processData: false,
				async:false,
				beforeSend: function () {
					$('#save-project').attr('disabled',true);
					$('#save-project').prev('.spinner').removeClass('d-none');
				},
				success:function(response){
					var all_data=JSON.parse(response);
					if(project_action=="editFromCreateMeet"){
						displayProjectDataAfterMeetingAction(all_data);
						formSubmitMessage("Project Updated!");
					}else if(project_action=="ADD_FROM_WORKSPACE" || project_action=="editFromWorkspace"){
						displayProjectDataAfterWorkspaceAction(all_data);
						if(project_action=="ADD_FROM_WORKSPACE"){
							formSubmitMessage("Project Added!");
						}else if(project_action=="editFromWorkspace"){
							formSubmitMessage("Project Updated!");
						}
					}else{
						displayProjectDataAfterDashboardAction(all_data);
						if(project_action=="ADD"){
							formSubmitMessage("Project Added!");
						}else if(project_action=="EDIT"){
							formSubmitMessage("Project Updated!");
						}
					}
				},
				complete:function () {
					reset_form_fields('project');	
					$('.addMilestoneTable tbody').empty();
					$('.addMilestoneTable').addClass('d-none');
					$('#removeProject').addClass('d-none');
				},
			});
		}
		return false;
	});
    $('.add_milestone').click(function(){
        var new_tr="<tr>";
        new_tr+='<td><input type="checkbox" class="checkbox_lg" name="milestone_check"></td>';
        new_tr+='<td><input type="text" class="form-control form-control-sm" name="milestone_title[]" value=""/></td>';
        new_tr+='<td><input type="date" class="form-control form-control-sm" name="milestone_date[]" value=""/></td>';
        new_tr+='<td><a href="javascript:void(0)" class="text-danger remove-milestone"><i class="fa fa-times fa-lg"></i></a></td>';
        new_tr+="</tr>";
        $('.addMilestoneTable').removeClass('d-none');
        $('.addMilestoneTable tbody').append(new_tr);
    });
	$('.add_milestone_edit_pro').click(function(){
        var new_tr="<tr>";
        new_tr+='<td><input type="checkbox" class="checkbox_lg" name="milestone_check"></td>';
        new_tr+='<td><input type="text" class="form-control form-control-sm" name="milestone_title[]" value=""/></td>';
        new_tr+='<td><input type="date" class="form-control form-control-sm" name="milestone_date[]" value=""/></td>';
        new_tr+='<td><a href="javascript:void(0)" class="text-danger remove-milestone"><i class="fa fa-times fa-lg"></i></a></td>';
        new_tr+="</tr>";
        $('.addMilestoneTable_edit_pro').removeClass('d-none');
        $('.addMilestoneTable_edit_pro tbody').append(new_tr);
    });

    $(document).on('click','.remove-milestone', function(){
        $(this).parents('tr').remove();
    });

    $(document).on('click','#removeProject',function(){
        var project_id=$(this).attr('project_id');
		var delete_from_workspace=$(this).attr('delete_from_workspace');
		var only_mine=0;
		if(delete_from_workspace==1){
			if($('#show_mine_project').prop('checked')){
				only_mine=1;
			}
		}
        $.ajax({
				url:'project_action.php',
				type:'get',
				data:{project_id,only_mine,delete_project:1,delete_from_workspace,meeting_id:$('#hidden_meeting_id').val()},
				datatype:'json',
				async:false,
				beforeSend: function () {
					$('#removeProject').attr('disabled',true);
					$('#removeProject').prev('.spinner').removeClass('d-none');
				},
				success:function(response){
					var all_data=JSON.parse(response);
					if(delete_from_workspace==1){
						displayProjectDataAfterWorkspaceAction(all_data);
					}else{
					var new_table_data="";
					var check_for_no_data=true;
					$('#sidebar_project_count').text(all_data.total_projects);
					
					$.each(all_data.data,function(index,d){
						if(d.count>0){
							check_for_no_data=false;
							new_table_data+='<table class="table table-sm border-0 project_meeting'+d.meeting_id+'" width="100%" cellspacing="0"><tbody>';
							new_table_data+='<tr><td colspan="4">';
							new_table_data+='<div class="card-subheading"><span>'+d.meeting_name+'</span></div>';
							new_table_data+='</td></tr>';
							$.each(d.data,function(k,v){
								new_table_data+='<tr class="project_tr_'+v.project_id+'">';
								new_table_data+='<td class="td_title"><a href="javascript:edit_project('+v.project_id+')">'+v.project_name+'</a></td>';
								//new_table_data+='<td class="td_project_description">'+v.project_description+'</td>';
								new_table_data+='<td class="td_status"><span class="status_view status_css '+v.status_class+'" project_id="'+v.project_id+'" status_id="'+v.project_status_id+'">'+v.status_icon+' '+v.project_status+'</span></td>';
								var deadline=(v.project_deadline=='0000-00-00') ? " - " : v.project_deadline;
								new_table_data+='<td id="project_td_deadline_'+v.project_id+'" class="td_date small-font '+v.class_name+'">'+deadline+'</td>';
								new_table_data+='</tr>';
							});
						}
						if(d.count>10){
							new_table_data+='<tr class="load_more_project_data">';
							new_table_data+='<td colspan="3" class="text-right">';
							new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
							new_table_data+='<button total_data="'+d.count+'" loaded_data="10" project_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-project">Load More</button>';										
							new_table_data+='</td></tr>';
						}
						new_table_data+='</tbody></table>';
						
					});
					if(check_for_no_data==true){
						new_table_data+='<div class="text-center no_issue_div">';
						new_table_data+='<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>';
						new_table_data+='<p>No Current Projects</p>';
						new_table_data+='</div>';
					}
					$("#project_table").html(new_table_data);
				}
					formSubmitMessage("Project Archived!");
				},
				complete:function () {
					reset_form_fields('project');	
                    $('.addMilestoneTable tbody').empty();
                    $('.addMilestoneTable').addClass('d-none');
                    $('#removeProject').attr('disabled',false);
					$('#removeProject').prev('.spinner').addClass('d-none');
                    $('#removeProject').addClass('d-none');
				},
            });
    });

    function displayTaskDataAfterMeetingAction(res){
		if(res.length==0){
			$('#meetingTask').removeClass('d-table').addClass('d-none');
			$('#no_task_available_div_create_meet').removeClass('d-none').addClass('d-block');
		}else{
			var task_tr="";
			$.each(res,function(i,data){
				console.log("Yes Here !!!");
				console.log(data);
				console.log($("#meeting_task_id_edit").val());
				if(data.task_id== $("#meeting_task_id_edit").val()){
                    console.log("Yes!! ");
                    if(d.task_status==1 || d.task_status==2 ){
                        $('#meeting_task_status').attr('checked',true);
                    }else{
                        $('#meeting_task_status').attr('checked',false);
                    }
                }
				task_tr+='<tr>';
				var emp_img="background-image:url('"+data.emp_img+"')";
				task_tr+='<td><span class="attendees_img" style="'+emp_img+'">'+data.emp_txt+'</span><span class="sr-only">'+data.name+'</span></td>';
				task_tr+='<td class="td_w_95"><a '
				task_tr+=data.task_status == 2 || data.task_status == 1 ? " style=text-decoration:line-through ": ""
				task_tr+=' class="edit_title_all" href="javascript:edit_task('+data.task_id+','+"'EDIT_TASK_MEETING'"+')">'+data.task_title+'</a></td>';
				task_tr+='<td><i task_id='+data.task_id+' class="fa fa-trash-o fa-lg deleteTaskCreateMeet"></i></td>';
				task_tr+='</tr>';
			});
			$('#meetingTask').removeClass('d-none').addClass('d-table');
			$('#no_task_available_div_create_meet').removeClass('d-block').addClass('d-none');
			$('#meetingTask tbody').html(task_tr);
		}
	}
    function taskNewContent(all_data){
        var new_table_data="";
        $('#sidebar_task_count').text(all_data.total_tasks);
        var check_for_no_data=true;
        $.each(all_data.data,function(index,d){
            check_for_no_data=false;
            new_table_data+='<table class="table table-sm border-0 task_meeting'+d.meeting_id+'" width="100%" cellspacing="0"><tbody>';
            new_table_data+='<tr><td colspan="4">';
            new_table_data+='<div class="card-subheading"><span>'+d.meeting_name+'</span></div>';
            new_table_data+='</td></tr>';
            $.each(d.data,function(k,v){
				console.log(v);
                new_table_data+='<tr class="task_tr'+v.id+'" id="task_tr_'+v.id+'">';
                new_table_data+='<td class="td_task_status"><input class="change_task_status" task_id="'+v.id+'" type="checkbox"'
                if(v.task_status==1){
                    new_table_data+=" checked ";
                }
                new_table_data+='/></td>';
                                
                new_table_data+='<td class="td_task_title"><a ';
                if(v.task_status==1){
                    new_table_data+='class="task-completed"';
                }
                new_table_data+=' href="javascript:edit_task('+v.id+')">'+v.task_title+'</a></td>';
                var task_duedate=(v.task_duedate=='00-00-0000') ? " - " : v.task_duedate;
                new_table_data+='<td id="td_task_date'+v.project_id+'" class="td_task_date '+v.due_date_class+'">'+task_duedate+'</td>';
                new_table_data+='</tr>';
            });
            if(d.count>10){
                new_table_data+='<tr class="load_more_task_data">';
                new_table_data+='<td colspan="3" class="text-right">';
                new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
                new_table_data+='<button total_data="'+d.count+'" loaded_data="10" task_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-task">Load More</button>';										
                new_table_data+='</td></tr>';
            }
            new_table_data+='</tbody></table>';
        });
        if(check_for_no_data==true){
            new_table_data+='<div class="text-center no_issue_div">';
            new_table_data+='<img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img"/>';
            new_table_data+='<p>No Current Task</p>';
            new_table_data+='</div>';
        }
        $("#task_table").html(new_table_data);
    }

	function displayTaskDataAfterWorkspaceAction(all_data){
        var new_table_data="";
        if(all_data.length>0){
			$.each(all_data,function(k,v){
                new_table_data+='<tr class="task_tr'+v.id+'" id="task_tr_'+v.id+'">';
				var emp_img="background-image:url('"+v.emp_img+"')";
				new_table_data+='<td class="attendee_img_td td_w_5"><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
                new_table_data+='<td class="td_w_5"><input class="change_task_status" task_id="'+v.id+'" type="checkbox"'
                if(v.task_status==1){
                    new_table_data+=" checked ";
                }
                new_table_data+='/></td>';
                                
                new_table_data+='<td class="td_task_title"><a ';
                if(v.task_status==1){
                    new_table_data+='class="task-completed"';
                }
                new_table_data+=' href="javascript:edit_task('+v.id+','+"'editFromWorkspace'"+')">'+v.task_title+'</a></td>';
                var task_duedate=(v.task_duedate=='00-00-0000') ? " - " : v.task_duedate;
                new_table_data+='<td id="td_task_date'+v.project_id+'" class="td_task_date '+v.due_date_class+'">'+task_duedate+'</td>';
                new_table_data+='</tr>';
            });
		}
        else{
            new_table_data+='<div class="text-center no_issue_div">';
            new_table_data+='<img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img"/>';
            new_table_data+='<p>No Current Task</p>';
            new_table_data+='</div>';
        }
        $("#task_table").html(new_table_data);
    }
   

	$('#add_task_meeting').click(function(){
        $('#task_action').val("ADD_TASK_MEETING");
        $('#task_meeting').val($('#hidden_meeting_id').val()).trigger('change');
    })

    
	$(document).on('click','.deleteTaskCreateMeet',function(){
		var task_id=$(this).attr('task_id');
		var meeting_id=$('#hidden_meeting_id').val();
		$.ajax({
			url:'task_action.php',
			type:'get',
			data:{'delete_task':1, task_id,meeting_id,'delete_from_meeting':'1'},
			datatype:'json',
			async:false,
			success:function(response){
				displayTaskDataAfterMeetingAction(JSON.parse(response));
			},
			complete:function(){
				formSubmitMessage("Task Archived!");
			}
			})
	});
	
	$("#form_task").submit(function(e){
	var flag = true;
	var task_title=$('#task_title').val();
	var task_meeting=$('#task_meeting').val();
	var description = $('#summernote-task').summernote('code');
	var task_duedate=$('#task_duedate').val();
	var task_priority=$('#task_priority').val();
	var task_stage=$('#task_stage').val();
	if(task_title == ""){
		$("#task_title_error").removeClass('d-none');
		flag=false;
	}else{
		$("#task_title_error").addClass('d-none');
	}
	if(task_meeting == ""){
		$("#task_meeting_error").removeClass('d-none');
		flag=false;
	}else{
		$("#task_meeting_error").addClass('d-none');
	}
	//if(description=="" || description=="<p><br></p>"){
	//	$("#task_desc_error").removeClass('d-none');
	//	flag=false;
	//}else{
	//	$("#task_desc_error").addClass('d-none');
	//}
	if(task_duedate== ""){
		$("#duedate_error").removeClass('d-none');
		flag=false;
	}else{
		$("#duedate_error").addClass('d-none');
	}
	var task_action=$('#task_action').val();
	
	if(flag==true){
		var all_data=new FormData(this);
		all_data.append('task_desc',description);
		if( task_action=="ADD_FROM_WORKSPACE" || task_action=="editFromWorkspace"){
			var only_mine=0;
			if($('#show_mine_task').prop('checked')){
				only_mine=1;
			}
			all_data.append('only_mine',only_mine);
		}
		$.ajax({
			url:'task_action.php',
			type:'post',
			data:all_data,
			datatype:'json',
            contentType: false,
			processData: false,
			async:false,
			beforeSend: function () {
				$('#save-task').attr('disabled',true);
				$('#save-task').prev('.spinner').removeClass('d-none');
			},
			success:function(response){
                var all_data=JSON.parse(response);
                if(task_action=="ADD_TASK_MEETING" || task_action=="EDIT_TASK_MEETING"){
                    displayTaskDataAfterMeetingAction(all_data);
                    $('#task_action').val('ADD');
                }if(task_action=="ADD_FROM_WORKSPACE" || task_action=="editFromWorkspace"){
                    displayTaskDataAfterWorkspaceAction(all_data);
                    $('#task_action').val('ADD');
                }else{
                    taskNewContent(all_data);
                }
                if(task_action=="ADD" || task_action=="ADD_TASK_MEETING" || task_action=="ADD_FROM_WORKSPACE"){
                    formSubmitMessage("Task Added!");
                }else if(task_action=="EDIT" ||task_action=="editFromWorkspace"){
                    formSubmitMessage("Task Updated!");
                }
			},
			complete:function (){
				reset_form_fields('task');
                $('#removeTask').addClass('d-none');
			},
		});
	}
	return false;
});

	$(document).on('click','.show-more-task', function(){
		var load_type="task";
		var task_meeting_id=$(this).attr("task_meeting_id");
		var total_data=$(this).attr("total_data");
		var loaded_data=$(this).attr("loaded_data");
		var data={load_type,loaded_data, task_meeting_id};
		var current_ele=$(this);
		$.ajax({
			url:'task_action.php',
			type:'get',
			data:data,
			datatype:'json',
			beforeSend: function () {
				$(current_ele).prev('.spinner').removeClass('d-none');
				$(current_ele).attr('disabled',true);
			},
			success:function(response){
				var result=JSON.parse(response);
				var additional_data="";
				var count=0;
				$.each(result,function(k,v){
					additional_data+='<tr class="task_tr" id="task_tr_'+v.id+'">';
					var chk=v.task_status==1 ? "checked" : "";
					additional_data+='<td class="td_task_status"><input class="change_task_status" task_id="'+v.id+'" type="checkbox"'+chk+'/></td>';
					additional_data+='<td class="td_task_title"><a'; 
					if(v.task_status==1){ 
					additional_data+=' class="task-completed"';
					};
					additional_data+=' href="javascript:edit_task('+v.id+')">'+v.task_title+'</a></td>';
					var duedate=(v.task_duedate=='0000-00-00') ? " - " : v.task_duedate;
					additional_data+='<td class="td_task_date '+v.due_date_class+'">'+duedate+'</td>';
					additional_data+='</tr>';
					count++;
				});
				var new_count_loaded_data=Number(loaded_data)+count;
				$(current_ele).attr("loaded_data",new_count_loaded_data);
				if(total_data > new_count_loaded_data){
					$(current_ele).parents('.load_more_task_data').removeClass('d-none');
				}else{
					$(current_ele).parents('.load_more_task_data').addClass('d-none')
				}
				$(".task_meeting"+task_meeting_id+ " tr:last").before(additional_data);
			},
			complete: function () {
				$(current_ele).prev('.spinner').addClass('d-none');
				$(current_ele).attr('disabled',false);
			}	
		});
	});
	
	$(document).on('click','.change_task_status', function(){
		var task_status=0;
		var current_ele=$(this);
		if ($(this).is(":checked")) {
		   task_status=1;
		}					
		var task_id=$(this).attr('task_id');
		var data={task_status,task_id, update_task_status:1};
		var result="";
		$.ajax({
			url:'task_action.php',
			type:'get',
			data:data,
			datatype:'json',
			async: false, 
			success:function(response){
				var sel="#task_tr_"+task_id+" .td_task_title a";
				if(task_status==1){
					$(sel).addClass('task-completed');
				}else{
					$(sel).removeClass('task-completed');
				}
                formSubmitMessage("Task Status Updated!");
			}
		});
    });
    $(document).on('click','#removeTask',function(){
        var task_id=$(this).attr('task_id');
		var delete_from_meeting=$(this).attr('delete_from_meeting');
		var delete_from_workspace=$(this).attr('delete_from_workspace');
		var only_mine=0;
		if(delete_from_workspace==1){
			if($('#show_mine_task').prop('checked')){
				only_mine=1;
			}
		}
        $.ajax({
				url:'task_action.php',
				type:'get',
				data:{task_id, delete_task:1,only_mine,delete_from_workspace,delete_from_meeting,meeting_id:$('#hidden_meeting_id').val()},
				datatype:'json',
				async:false,
				beforeSend: function () {
					$('#removeTask').attr('disabled',true);
					$('#removeTask').prev('.spinner').removeClass('d-none');
				},
				success:function(response){
                    var all_data=JSON.parse(response);
					if(delete_from_workspace==1){
						displayTaskDataAfterWorkspaceAction(all_data);
					}else if(delete_from_meeting==1){
						displayTaskDataAfterMeetingAction(all_data);
					}else{
                    	taskNewContent(all_data);
					}
					formSubmitMessage("Task Archived!");
				},
				complete:function () {
					reset_form_fields('task');	
                    $('#removeTask').attr('disabled',false);
					$('#removeTask').prev('.spinner').addClass('d-none');
                    $('#removeTask').addClass('d-none');
				},
            });
	});
	
	function edit_task(task_id, edit_from_meeting=""){
		$('#modal_task_title').html("Edit Task")
		$("#newTask").modal('show');
        $('#removeTask').removeClass('d-none');
		$.ajax({
			url:'task_action.php',
			type:'get',
			data:{task_id, edit_task:1},
			datatype:'json',
			async: false, 
			success:function(response){
				var result=JSON.parse(response);
				$('#task_title').val(result.task_title);
				$('#summernote-task').summernote('code',result.task_details);
				$("#assignto").val(result.task_assignto);
				$('#task_duedate').val(result.task_duedate);
				$('#task_priority').val(result.task_priority);
				$('#task_meeting').val(result.task_meeting);
				$('#pstatus_id').val(result.project_status_id);
				$('#task_id_edit').val(result.id);
                if(edit_from_meeting=="EDIT_TASK_MEETING"){
					$('#task_action').val(edit_from_meeting);
					$('#removeTask').attr('delete_from_meeting',1);
				}else if(edit_from_meeting=="editFromWorkspace"){
                    $('#task_action').val(edit_from_meeting);
					if(edit_from_meeting=="editFromWorkspace"){
						$('#removeTask').attr('delete_from_workspace',1);
					}
                }else{
				    $('#task_action').val("EDIT");
                }
                $('#removeTask').attr('task_id',result.id);                               
			}
		});
	}


	$('#add_issue_meeting').click(function(){
        $('#issue_action').val("ADD_ISSUE_MEETING");
        $('#issue_meeting').val($('#hidden_meeting_id').val()).trigger('change');
    })

    
	$(document).on('click','.deleteIssueCreateMeet',function(){
		var issue_id=$(this).attr('issue_id');
		var meeting_id=$('#hidden_meeting_id').val();
		$.ajax({
			url:'issue_action.php',
			type:'get',
			data:{ issue_id,meeting_id,'delete_issue_from_meeting':'1'},
			datatype:'json',
			async:false,
			success:function(response){
				displayIssueDataAfterMeetingAction(JSON.parse(response));
			},
			complete:function(){
				formSubmitMessage("Issue Archived!");
			}
			})
	});

	function displayIssueDataAfterMeetingAction(res){
		if(res.length==0){
			$('#meetingIssue').removeClass('d-table').addClass('d-none');
			$('#no_issue_available_div_create_meet').removeClass('d-none').addClass('d-block');
		}else{
			var issue_tr="";
			$.each(res,function(i,data){
				issue_tr+='<tr>';
				var emp_img="background-image:url('"+data.emp_img+"')";
				issue_tr+='<td><span class="attendees_img" style="'+emp_img+'">'+data.emp_txt+'</span><span class="sr-only">'+data.name+'</span></td>';
				issue_tr+='<td class="td_w_95"><a class="edit_title_all" href="javascript:edit_issue('+data.issue_id+','+"'EDIT_ISSUE_MEETING'"+')">'+data.issue+'</a></td>';
				issue_tr+='<td><i issue_id='+data.issue_id+' class="fa fa-trash-o fa-lg deleteIssueCreateMeet"></i></td>';
				issue_tr+='</tr>';
			});
			$('#meetingIssue').removeClass('d-none').addClass('d-table');
			$('#no_issue_available_div_create_meet').removeClass('d-block').addClass('d-none');
			$('#meetingIssue tbody').html(issue_tr);
		}
	}

    $("#form_issue").submit(function(e){
	var flag = true;
	//e.preventDefault();
	var issue=$('#issue').val();
	var description = $('#summernote-issue').summernote('code');
	var issue_action=$('#issue_action').val();
	var flag = true;
	if(issue == ""){
		$("#issue_error").removeClass('d-none');
		flag=false;
	}else{
		$("#issue_error").addClass('d-none');
	}
	//if(description=="" || description=="<p><br></p>"){
	//	$("#issue_desc_error").removeClass('d-none');
	//	flag=false;
	//}else{
	//	$("#issue_desc_error").addClass('d-none');
	//}
	if(issue_action=="ADD"){
		if($('#issue_meeting').val()== ""){
			$("#issue_meeting_error").removeClass('d-none');
			flag=false;
		}else{
			$("#issue_meeting_error").addClass('d-none');
		}
	}
	if(flag==true){
		var meeting_id="";
		var all_data=new FormData(this);
		all_data.append('issue_desc', description);
		if(issue_action=="EDIT" || issue_action=="ADD_FROM_TILE" || issue_action=="ADD_ISSUE_MEETING"){
			meeting_id=$('#hidden_meeting_id_issue_modal').val();
			var only_mine=0;
			if($('#show_only_'+meeting_id).prop('checked')){
				only_mine=1;
			}
			all_data.append('only_mine',only_mine);
		}else if( issue_action=="ADD_FROM_WORKSPACE" ||  issue_action=="editFromWorkspace"){
			meeting_id=$('#hidden_meeting_id_issue_modal').val();
			var only_mine_ws=0;
			
			if($('#show_mine_issue').prop('checked')){
				only_mine_ws=1;
			}
			all_data.append('only_mine_ws',only_mine_ws);
		}else{
			meeting_id=$('#issue_meeting').val();
		}
		$.ajax({
			url:'issue_action.php',
			type:'post',
			data:all_data,
			datatype:'json',
			contentType: false,
			processData: false,
			async:false,
			beforeSend: function () {
				$('#save-issue').attr('disabled',true);
				$('#save-issue').prev('.spinner').removeClass('d-none');
			},
			success:function(response){
				var d=JSON.parse(response);
				if(issue_action=="ADD_ISSUE_MEETING" || issue_action=="EDIT_ISSUE_MEETING"){
                    displayIssueDataAfterMeetingAction(d);
					if(issue_action=="ADD_ISSUE_MEETING"){
						formSubmitMessage("Issue Added!");
					}else{
						formSubmitMessage("Issue Updated!");
					}
                    $('#issue_action').val('ADD');
                }else if(issue_action=="ADD_FROM_WORKSPACE" || issue_action=="editFromWorkspace"){
					displayIssueDataAfterWorkspaceAction(d);
					if(issue_action=="ADD_FROM_WORKSPACE"){
						formSubmitMessage("Issue Added!");
					}else{
						formSubmitMessage("Issue Updated!");
					}
				}else if(issue_action=="ADD" || issue_action=="ADD_FROM_TILE"){
					$('#sidebar_issue_count').text(d.issue_count);
					var records=d.all_data;
					var new_table_data='<div class="table-responsive">';
					new_table_data+='<table class="table table-sm border-0" >';
					new_table_data+='<tbody>';
					$.each(records.data,function(k,v){
						var emp_img="background-image:url('"+v.emp_img+"')";
						new_table_data+='<tr id="issue_tr_'+v.id+'">';
						new_table_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
						new_table_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.id+')">'+v.issue+'</a></td>';
						new_table_data+='</tr>';
					});
					if(records.count>10){
						new_table_data+='<tr class="load_more_issue_data">';
						new_table_data+='<td colspan="2" class="text-right">';
						new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
						new_table_data+='<button total_data="'+records.count+'" loaded_data="10" issue_meeting_id="'+records.meeting_id+'" class="btn btn-light btn-sm show-more-issue">Load More</button>';										
						new_table_data+='</td></tr>';
					}
					new_table_data+='</tbody></table></div>';
					formSubmitMessage("Issue Added!");						
					$('#meeting_issue_'+records.meeting_id).html(new_table_data);
				}else if(issue_action=="EDIT"){
					console.log(".issue_tr_"+d.id);
					$("#issue_tr_"+d.id+" .td_issue_title a").text(d.issue);
					formSubmitMessage("Issue Updated!");
				}
				$('#newIssue .issue_meeting_div').removeClass('d-none');
			},
			complete:function () {
				reset_form_fields('issue');
			},
		});
	}
	return false;
});

function edit_issue(issue_id,edit_from_meeting=""){
	$('#modal_issue_title').html("Edit Issue")
	$("#newIssue").modal('show');
	$('#newIssue .issue_meeting_div').addClass('d-none');
	$('#issueIssue').removeClass('d-none');
	$('#issueIssue').attr('issue_id',issue_id);
	$.ajax({
		url:'issue_action.php',
		type:'get',
		data:{issue_id, edit_issue:1},
		datatype:'json',
		async: false, 
		success:function(response){
			var result=JSON.parse(response);
			$('#issue').val(result.issue);
			$('#summernote-issue').summernote('code',result.issue_details);
			$('#hidden_meeting_id_issue_modal').val(result.meeting_id);
			if(edit_from_meeting=="EDIT_ISSUE_MEETING"){
				$('#issue_action').val("EDIT_ISSUE_MEETING");
				$('#issueIssue').attr('delete_from','delete_from_meeting');
			}else if(edit_from_meeting=="editFromWorkspace"){
				$('#issue_action').val('editFromWorkspace');
				$('#issueIssue').attr('delete_from_workspace',1);
			}else{
				$('#issue_action').val("EDIT");
			}
			$('#issue_id_edit').val(issue_id);
			//console.log($('#issue_action').val());
		}
	});
}
function add_issue(meeting_id){
	$("#newIssue").modal('show');
	$('#hidden_meeting_id_issue_modal').val(meeting_id);
	$('#issue_action').val('ADD_FROM_TILE');
	$('#newIssue .issue_meeting_div').addClass('d-none');
}
$('#issueIssue').click(function(){
	var issue_id=$('#issue_id_edit').val();
	var meeting_id=$('#hidden_meeting_id_issue_modal').val();
	var delete_from_workspace=$(this).attr('delete_from_workspace');
	var delete_from_meeting=$(this).attr('delete_from');
	var only_mine_ws=0;
	if(delete_from_workspace==1){
		if($('#show_mine_issue').prop('checked')){
			only_mine_ws=1;
		}
	}
	var only_mine=0;
	if($('#show_only_'+meeting_id).prop('checked')){
		only_mine=1;
	}
		$.ajax({
			url:'issue_action.php',
			type:'post',
			data:{meeting_id,issue_id,issue_action:'DELETE',delete_from_workspace,only_mine_ws,only_mine},
			datatype:'json',
			async:false,
			beforeSend: function () {
				$('#issueIssue').attr('disabled',true);
				$('#issueIssue').prev('.spinner').removeClass('d-none');
			},
			success:function(response){
				var res=JSON.parse(response);
				if(delete_from_workspace==1){
					displayIssueDataAfterWorkspaceAction(res);
				}else if(delete_from_meeting=="delete_from_meeting"){
					console.log(res);
					displayIssueDataAfterMeetingAction(res.all_data.data);
				}else{
				var d=res.all_data;
				$('#sidebar_issue_count').text(res.issue_count);
				var new_table_data="";
					if(d.count>0){
						new_table_data+='<div class="table-responsive">';
						new_table_data+='<table class="table table-sm border-0" >';
						new_table_data+='<tbody>';
						$.each(d.data,function(k,v){
							new_table_data+='<tr id="issue_tr_'+v.id+'">';
							var emp_img="background-image:url('"+v.emp_img+"')";
							new_table_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
							new_table_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.id+')">'+v.issue+'</a></td>';
							new_table_data+='</tr>';
						});
						if(d.count>10){
							new_table_data+='<tr class="load_more_issue_data">';
							new_table_data+='<td colspan="2" class="text-right">';
							new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
							new_table_data+='<button total_data="'+d.count+'" loaded_data="10" issue_meeting_id="'+meeting_id+'" class="btn btn-light btn-sm show-more-issue">Load More</button>';										
							new_table_data+='</td></tr>';
						}
						new_table_data+='</tbody></table></div>';	
					}else{
						new_table_data+='<div class="align-self-center text-center no_issue_div">';
						new_table_data+='<img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img"/>';
						new_table_data+='<p>No Current Issues</p>';
						new_table_data+='</div>';
						
					}
				$('#meeting_issue_'+meeting_id).html(new_table_data);
				formSubmitMessage("Issue Archived!");
				}
				$('#newIssue .issue_meeting_div').removeClass('d-none');
			},
			complete:function (){
				$('#issueIssue').attr('disabled',false);
				$('#issueIssue').prev('.spinner').addClass('d-none');
				reset_form_fields('issue');
			},
		});	
});
$(document).on('click','.show-more-issue', function(){
    var load_type="issue";
    var issue_meeting_id=$(this).attr("issue_meeting_id");
    var total_data=$(this).attr("total_data");
    var loaded_data=$(this).attr("loaded_data");
    var data={load_type,loaded_data, issue_meeting_id};
    var current_ele=$(this);
    $.ajax({
        url:'issue_action.php',
        type:'get',
        data:data,
        datatype:'json',
        beforeSend: function () {
            $(current_ele).prev('.spinner').removeClass('d-none');
            $(current_ele).attr('disabled',true);
        },
        success:function(response){
            var result=JSON.parse(response);
            var additional_data="";
            var count=0;
            $.each(result,function(k,v){
                additional_data+='<tr id="issue_tr_'+v.id+'">';
				var emp_img="background-image:url('"+v.emp_img+"')";
                additional_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
                additional_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.id+')">'+v.issue+'</a></td>';
                additional_data+='</tr>';
                count++;
            });
            var new_count_loaded_data=Number(loaded_data)+count;
            $(current_ele).attr("loaded_data",new_count_loaded_data);
            if(total_data > new_count_loaded_data){
                $(current_ele).parents('.load_more_issue_data').removeClass('d-none');
            }else{
                $(current_ele).parents('.load_more_issue_data').addClass('d-none')
            }
            $("#meeting_issue_"+issue_meeting_id+ " table tr:last").before(additional_data);
        },
        complete: function () {
            $(current_ele).prev('.spinner').addClass('d-none');
            $(current_ele).attr('disabled',false);
        }	
    });
});

	$('.only-mine-check').change(function(){
		var only_mine=0;
		var meeting_id=$(this).attr('meeting_id');
		if($(this).prop('checked')){
			only_mine=1;
		}
		$.ajax({
			url:'issue_action.php',
			type:'post',
			data:{only_mine, meeting_id,issue_action:'show_filter'},
			datatype:'json',
			async:false,
			beforeSend: function () {
				new_table_data='<div class="align-self-center text-center no_issue_div">';
				new_table_data+='<div class="spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
				new_table_data+='</div>';
				$('#meeting_issue_'+meeting_id).html(new_table_data);
			},
			success:function(response){
				var d=JSON.parse(response);
				console.log(d);
				var new_table_data="";
				if(d.all_data.count>0){
				new_table_data+='<div class="table-responsive">';
				new_table_data+='<table class="table table-sm border-0" >';
				new_table_data+='<tbody>';
				$.each(d.all_data.data,function(k,v){
					new_table_data+='<tr id="issue_tr_'+v.id+'">';
					var emp_img="background-image:url('"+v.emp_img+"')";
					new_table_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
					new_table_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.id+')">'+v.issue+'</a></td>';
					new_table_data+='</tr>';
				});
				if(d.count>10){
					new_table_data+='<tr class="load_more_issue_data">';
					new_table_data+='<td colspan="2" class="text-right">';
					new_table_data+='<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>';
					new_table_data+='<button total_data="'+d.count+'" loaded_data="10" issue_meeting_id="'+d.meeting_id+'" class="btn btn-light btn-sm show-more-issue">Load More</button>';										
					new_table_data+='</td></tr>';
				}
				new_table_data+='</tbody></table></div>';
				}else{
					new_table_data+='<div class="align-self-center text-center no_issue_div">';
					new_table_data+='<img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img"/>';
					new_table_data+='<p>No Current Issues</p>';
					new_table_data+='</div>';
				}					
				$('#meeting_issue_'+meeting_id).html(new_table_data);
			},
			complete:function () {
				reset_form_fields('issue');
			},
		});
	});
    
function delete_tile(type){
	$.ajax({
		url:'issue_action.php',
		type:'get',
		data:{type,delete_action:"delete_from_tile"},
		datatype:'json',
		success:function(response){
			$.each(result,function(k,v){
				additional_data+='<tr id="issue_tr_'+v.id+'">';
				var emp_img="background-image:url('"+v.emp_img+"')";
				additional_data+='<td><span class="attendees_img" style="'+emp_img+'">'+v.emp_txt+'</span></td>';
				additional_data+='<td class="td_issue_title"><a href="javascript:edit_issue('+v.id+')">'+v.issue+'</a></td>';
				additional_data+='</tr>';
				count++;
			});
		},
		complete: function () {
			$(current_ele).prev('.spinner').addClass('d-none');
			$(current_ele).attr('disabled',false);
		}
	});
}


// Mesurement/Scorecard/Matrix Modal Function Start 
// function loadScorecardDataTable(){

//     var xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && this.status == 200) {
//             // Typical action to be performed when the document is ready:
//                 document.getElementById("scorecardMatrixTable").innerHTML = xhttp.responseText;
//             }
//         };
// xhttp.open("GET", "scorecard_matrix_table.php", true);
// xhttp.send();
// }

// setInterval(() => {
//     loadScorecardDataTable();
// }, 1000);

// window.onload = loadScorecardDataTable();

function matrixvalidateInput(input) {
    var matrixInputPattern = /^[0-9$%£€]*$/;
    if (!matrixInputPattern.test(input.value)) {
        input.value = input.value.replace(/[^0-9$%£€]/g, '');
    }
}

function matrix_edit_content(currentTile,measurableID,createdBy,weekID,type,logUpdate="") {
    let tile_value = currentTile.val();
    let tilerowID = currentTile.parents('tr').find('.trID').val();
    let tileWeek = currentTile.siblings('.tdweek').val();
    if (tile_value != '') {

        let tileData = {
            'status': 'update_tile',
            'tilerowID': tilerowID,
            'tileWeek': tileWeek,
            'tile_value': tile_value,
            'measurableID': measurableID,
            'createdBy': createdBy,
            'weekID': weekID,
            'type': type
        }

        if(logUpdate == 'meetingMatrics'){
            tileData.editingfrom = 'meetingMatrics';
            tileData.meetingID = '<?=isset($_GET['meeting_id']) ? $_GET['meeting_id'] : 0?>';
            tileData.meeting_timer_id = '<?=isset($_GET['meeting_timer_id']) ? $_GET['meeting_timer_id'] : 0?>';
        }

        $.ajax({
            url: "inc/scorecard.php",
            cache: false,
            type: "POST",
            data: tileData,
            success: function(response) {
				console.log(response);
				currentTile.parents('td').removeClass('td-danger');
				currentTile.parents('td').removeClass('td-success');
                const tileUpdateResponse = JSON.parse(response);
				if (tileUpdateResponse['status'] === 'Updated') {
					currentTile.parents('td').addClass(tileUpdateResponse['boxColor']);
					currentTile.val(tileUpdateResponse['tileValue']);
				}
            }
        });
    }
}


function switchMatrix(metrics){
	if(metrics=="new"){
		$("#scorecardAddMatrixModalPopop #addNewMetrics,#scorecardAddMatrixModalPopop #EditMeetingMatrixHeader").removeClass('d-none');
		$("#scorecardAddMatrixModalPopop #addExistingMetrics,#scorecardAddMatrixModalPopop #defaultMeasurableHeader").addClass('d-none');
	}else{
		$("#scorecardAddMatrixModalPopop #addExistingMetrics").removeClass('d-none');
		$("#scorecardAddMatrixModalPopop #addNewMetrics").addClass('d-none');
	}
}

function convertGoalMatrixToString(goalMatrixSignValue){
	var measurableGoal = '';
	switch (goalMatrixSignValue) {
		case '=':
		measurableGoal = 'Equal to';
		break;
		case '>=':
		measurableGoal = 'Greater than or equal to';
		break;
		case '>':
		measurableGoal = 'Greater than';
		break;
		case '<=>':
		measurableGoal = 'Between';
		break;
		case '<=':
		measurableGoal = 'Less than or equal to';
		break;
		case '<':
		measurableGoal = 'Less than';
		break;
		default:
		measurableGoal = '';
		break;
	}

	return measurableGoal;
}

function showUpdateColorHTML(event){
    $('#scorecardAddMatrixModalPopop .changeMatricsValue').removeClass('d-none');
}

$(document).ready(function() {
    $('#scorecardAddMatrixModalPopop').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
        var modelid = button.data('whatever')
        var modal = $(this)
		let EditingFrom = typeof button.data('todo') !== 'undefined' ? button.data('todo').EditingFrom : false;

		$('#meetingMetricsTable tbody tr').removeClass('Editing');
        $('#scorecardAddMatrixModalPopopLabel').html('Add Measurable');
        $('#scorecardAddMatrixModalPopop #remove_measurable').addClass('d-none');
        modal.find('form input[name="modelpopupID"]').remove();
        modal.find('.modal-body input[name="status"]').val('add_modal_data');

		// if(EditingFrom === 'EditMeetingMatrix'){
		// 	modal.find('#addExistingMetrics').addClass('d-none');
		// 	modal.find('#scorecardAddMatrixModalPopopLabel').addClass('d-none');
		// 	modal.find('#defaultMeasurableHeader').removeClass('d-none');
		// }

		if(EditingFrom === 'meetingStartMatrix'){
			modal.find('#addNewMetrics form').append(`<input type="hidden" name="editingFrom" value="meetingStartMatrix">`);
			modal.find('.modal-body #attach_to_meeting').val(<?=isset($meeting_id) && $meeting_id != '' ? $meeting_id : '' ?>).trigger('change');
		}

		if(EditingFrom === 'meetingWorkspaceMatrix'){
			modal.find('#addNewMetrics form').append(`<input type="hidden" name="editingFrom" value="meetingWorkspaceMatrix">`);
			modal.find('.modal-body #attach_to_meeting').val(<?=isset($meeting_id) && $meeting_id != '' ? $meeting_id : '' ?>).trigger('change');
		}
		
		if(EditingFrom === 'EditMeetingAddMatrixModal'){
			modal.find('#addExistingMetrics select#existingMeasurable').find('option').remove();
			modal.find('#addExistingMetrics').removeClass('d-none');
			modal.find('#addNewMetrics').addClass('d-none');
			modal.find('#addNewMetrics form').append(`<input type="hidden" name="editingFrom" value="EditMeetingAddMatrix">`);
			modal.find('.modal-body #attach_to_meeting').val(<?=isset($meeting_id) && $meeting_id != '' ? $meeting_id : '' ?>).trigger('change');

			$.ajax({
                url: "inc/scorecard.php",
                type: "POST",
                cache: false,
                datatype: 'json',
                data: {
                    'status': 'getMatrixNotInMeetingID',
                    'meetingID': <?=isset($meeting_id) && $meeting_id != '' ? $meeting_id : 0 ?>
                },
                success: function(response) {
					const geNotExistMeasurement = JSON.parse(response);
					if(geNotExistMeasurement['status'] === 'getMatrixNotInMeetingID'){
						for(let gettingNotInMeetingLoop in geNotExistMeasurement['getMeasurementID']){
							if (modal.find('#addExistingMetrics select#existingMeasurable').find("option[value='" + geNotExistMeasurement['getMeasurementID'][gettingNotInMeetingLoop] + "']").length) {
								modal.find('#addExistingMetrics select#existingMeasurable').val(null).trigger('change');
							} else { 
								const newOption = new Option(geNotExistMeasurement['getMeasurementName'][gettingNotInMeetingLoop], geNotExistMeasurement['getMeasurementID'][gettingNotInMeetingLoop], false, false);
								modal.find('#addExistingMetrics select#existingMeasurable').append(newOption).trigger('change');
							} 
						}
					}
				}
			})
		}else{
			modal.find('#addExistingMetrics,#EditMeetingMatrixHeader').addClass('d-none');
			modal.find('#addNewMetrics,#defaultMeasurableHeader').removeClass('d-none');	
		}

        if (modelid != 'new_measurement') {
            button.parents('tr').addClass('Editing');
            $('#scorecardAddMatrixModalPopopLabel').html('Edit a Measurable');
            if(EditingFrom === 'MeasurablePage'){
                $('#scorecardAddMatrixModalPopop #remove_measurable').removeClass('d-none');
            }
            if(EditingFrom === 'meetingWorkspaceMatrix'){
                $('#scorecardAddMatrixModalPopop #remove_measurable').removeClass('d-none');
            }
            $.ajax({
                url: "inc/scorecard.php",
                type: "POST",
                cache: false,
                datatype: 'json',
                data: {
                    'status': 'fetchMeasurementData',
                    'modalID': modelid
                },
                success: function(response) {
                    modal.find('.modal-body input[name="status"]').val('update_modal_data');
                    modal.find('form').append(`<input type="hidden" name="modelpopupID" value="${modelid}">`);

                    if(EditingFrom === 'EditMeetingMatrix'){
                        modal.find('form').append(`<input type="hidden" name="editingFrom" value="editMeetingMatrix">`);
                    }

                    let data = JSON.parse(response);
                    modal.find('.modal-body #name').val(data.name);
                    //modal.find('.modal-body #goal_matric').val(data.goal_matric);
					if(data.goal=="<=>"){
						var btn_goal=data.goal_matric.split("-");
						//console.log(btn_goal);
						modal.find('.modal-body #between_goal_matric').val(btn_goal[0]);
						modal.find('.modal-body #goal_matric').val(btn_goal[1]);
						modal.find('.modal-body #between_goal_matric').attr('onchange','showUpdateColorHTML($(this))');
						$('#scorecardAddMatrixModalPopop .betweenmatrics').removeClass('d-none');
					} else {
						$('#scorecardAddMatrixModalPopop .betweenmatrics').addClass('d-none');
						modal.find('.modal-body #between_goal_matric').val("");
						modal.find('.modal-body #goal_matric').val(data.goal_matric);
					}					
                    modal.find('.modal-body #goal_matric').attr('onchange','showUpdateColorHTML($(this))');
                    modal.find('.modal-body #accountable').val(data.accountable).trigger('change');
                    modal.find('.modal-body #units').val(data.units);
                    modal.find('.modal-body #goals').val(data.goal);
					
                    let attachMeeting = data.attach_meeting.split('-');
                    modal.find('.modal-body #attach_to_meeting').val(attachMeeting).trigger('change');
                }
            });
        }
    });

    $('#scorecardAddMatrixModalPopop #saveExistingMetrics').on('click', function(e) {
		const selectExistingMeasurable = $(this).parents('form').find('select#existingMeasurable').val();
		// console.log(selectExistingMeasurable);

		if(selectExistingMeasurable && selectExistingMeasurable != ''){
			$.ajax({
				url: "inc/scorecard.php",
				type: "POST",
				cache: false,
				datatype: 'json',
				data: {
					'status': 'existingMeasurable',
					'existMeasurementID': selectExistingMeasurable,
					'currentmeetingID' : <?=isset($meeting_id) && $meeting_id != '' ? $meeting_id : 0 ?>
				},
				success: function(response) {
					const meetingUpdatedResponse = JSON.parse(response);
					if(meetingUpdatedResponse['status'] === 'meetingUpdatedInCurrentMatrix'){

						var addingExistingDataInMatrix = '';

						if($('#metrics #metricsTable').length === 0){
							$('#metrics .matrix_main_inner_content .no_issue_div').remove();
															
							addingExistingDataInMatrix += `
							<table id="metricsTable" class="table table-sm meetingTable table_vetical_align_middle hover_table mt-5 border-0">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>`;
						}

						for(let x in meetingUpdatedResponse['existingSelectedID']){
							// console.log(meetingUpdatedResponse['existingSelectedID'][x]);

							addingExistingDataInMatrix += `
									<tr data-sort-id="${meetingUpdatedResponse['existingSelectedID'][x]}">
										<td><i class="fa fa-arrows"></i></td>
										<td class="matrix_image"><span class="attendees_img" style="background-image:url('${meetingUpdatedResponse['existingSelectedImage'][x]}')">${meetingUpdatedResponse['existingSelectedText'][x]}</span></td>
										<td class="td_w_60 matrix_name">
										<a type="button" class="" data-toggle="modal"
											data-target="#scorecardAddMatrixModalPopop"
											data-whatever="${meetingUpdatedResponse['existingSelectedID'][x]}" data-todo='{"EditingFrom":"EditMeetingMatrix"}'>${meetingUpdatedResponse['existingSelectedName'][x]}
										</a>
										</td>
										<td class="matrix_goal">
											<a type="button" class="" data-toggle="modal"
											data-target="#scorecardAddMatrixModalPopop"
											data-whatever="${meetingUpdatedResponse['existingSelectedID'][x]}" data-todo='{"EditingFrom":"EditMeetingMatrix"}'>${convertGoalMatrixToString(meetingUpdatedResponse['existingSelectedGoal'][x])}
										</a>
										</td>
										<td class="td_w_15 matrix_goal_matrix">
											<a type="button" class="" data-toggle="modal"
												data-target="#scorecardAddMatrixModalPopop"
												data-whatever="${meetingUpdatedResponse['existingSelectedID'][x]}" data-todo=
												'{"EditingFrom":"EditMeetingMatrix"}'>${meetingUpdatedResponse['existingSelectedGoalMatrix'][x]}
											</a>
										</td>
										<td>
											<a href="javascript:void(0)" id='deleteMatrix' onclick="deleteMatrix($(this),'${meetingUpdatedResponse['existingSelectedID'][x]}')">
												<i class="fa fa-trash-o fa-lg"></i>
											</a>
										</td>
									</tr>
								`;
						}

						if($('#metrics #metricsTable').length === 0){
							$('#metrics .matrix_main_inner_content').append(addingExistingDataInMatrix);	
						}else{
							$('#metrics #metricsTable tbody').append(addingExistingDataInMatrix);
						}
					}
				},
				complete: function() {
					reset_form_fields('measurement');
				},
			})
		}
	});

    $('#scorecardAddMatrixModalPopop #remove_measurable').on('click', function(e) {
        const mesurableDeleteID = $(this).parents('form').find('input[name="modelpopupID"]').val();
        $.ajax({
            url: "inc/scorecard.php",
            type: "POST",
            cache: false,
            datatype: 'json',
            data: {
                'status': 'deleteMesurement',
                'mesurementID': mesurableDeleteID
            },
            success: function(response) {
                // console.log(response);
                let measurementDeleteResponse = JSON.parse(response);
                if (measurementDeleteResponse) {
                    if (measurementDeleteResponse['status'] === 'Deleted') {
                        $('#meetingMetricsTable tbody tr.Editing').remove();
                        formSubmitMessage("Mesurement Deleted!");
                    }
                } else {
                    formSubmitMessage("Delete Response Error!");
                }
            },
            complete: function() {
                reset_form_fields('measurement');
            },
        })
    });

    $('#scorecardAddMatrixModalPopop #save-measurable').on('click', function(e) {
        const measurable_modal = $(this);
        let measurable_action = measurable_modal.parents('form').find('.modal-body input[name="status"]').val();
        let modalid = measurable_modal.parents('form').find('input[name="modelpopupID"]').val();
        let name = measurable_modal.parents('form').find('#name').val();
        let accountable = measurable_modal.parents('form').find('#accountable').val();
        let units = measurable_modal.parents('form').find('#units').val();
        let goals = measurable_modal.parents('form').find('#goals').val();
        let goal_matric = measurable_modal.parents('form').find('#goal_matric').val();
        let between_goal_matric = measurable_modal.parents('form').find('#between_goal_matric').val();
        let changeMatricsValue = measurable_modal.parents('form').find('#changeMatricsValue').val();
        let attach_to_meeting = measurable_modal.parents('form').find('#attach_to_meeting').val();

        // if (name == '' || accountable == '' || units == '' || goal_matric == '') {
        if (name == '') {
            return false;
        }

        let mesurementFormFeildData = {
            'status': measurable_action,
            'name': name,
            'accountable': accountable,
            'units': units,
            'goals': goals,
            'goal_matric': goal_matric,
            'between_goal_matric': between_goal_matric ? between_goal_matric : '',
            'changeMatricsValue': changeMatricsValue ? changeMatricsValue : '',
            'attach_to_meeting': attach_to_meeting ? attach_to_meeting : ''
        };

        if(measurable_modal.parents('form').find('input[name="editingFrom"]').val() == 'meetingStartMatrix'){
            mesurementFormFeildData.editingfrom = 'meetingMatrics';
            mesurementFormFeildData.meetingID = '<?=isset($_GET['meeting_id']) ? $_GET['meeting_id'] : 0?>';
            mesurementFormFeildData.meeting_timer_id = '<?=isset($_GET['meeting_timer_id']) ? $_GET['meeting_timer_id'] : 0?>';
        }

        if (measurable_action !== 'add_modal_data') {
            if (modalid !== '') {
                mesurementFormFeildData.modelpopupID = modalid;
            } else {
				return false;
            }
        }

        $.ajax({
            url: "inc/scorecard.php",
            type: "POST",
            cache: false,
            datatype: 'json',
            data: mesurementFormFeildData,
            beforeSend: function() {
                measurable_modal.attr('disabled', true);
                measurable_modal.prev('.spinner').removeClass('d-none');
            },
            success: function(response) {
                // console.log(response);
                let measurementResponse = JSON.parse(response);
                if (measurementResponse) {
                    if (measurementResponse['status'] === 'Added') {
						const scoreDataID = measurementResponse['insertedDataID'];
						const scoreDataOwner = measurementResponse['insertedDataOwner'];
						const scoreDataName = measurementResponse['insertedDataName'];
						const scoreDataGoal = measurementResponse['insertedDataGoal'];
						const scoreDataGoalMatrix = measurementResponse['insertedGoalMatrix'];
						const scoreDataUnits = measurementResponse['insertedUnits'];
						const scoreData_goal_and_matric_and_units = measurementResponse['inserted_goal_and_matric_and_units'];
						const scoreDataUserImage = measurementResponse['insertUserImage'];
						const scoreDataUserText = measurementResponse['insertUserText'];
						
						if (scoreDataID != '') {
							if($('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'EditMeetingAddMatrix'){

								addingNewDataInMatrix = '';
								if($('#metrics #metricsTable').length === 0){
									$('#metrics .matrix_main_inner_content .no_issue_div').remove();
																	
									addingNewDataInMatrix += `
									<table id="metricsTable" class="table table-sm meetingTable table_vetical_align_middle hover_table mt-5 border-0">
									<thead>
										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
									</thead>
									<tbody>`;
								}

								addingNewDataInMatrix += `
									<tr data-sort-id="${scoreDataID}">
										<td><i class="fa fa-arrows"></i></td>
										<td class="matrix_image"><span class="attendees_img" style="background-image:url('${scoreDataUserImage}')">${scoreDataUserText}</span></td>
										<td class="td_w_60 matrix_name">
										<a type="button" class="" data-toggle="modal"
											data-target="#scorecardAddMatrixModalPopop"
											data-whatever="${scoreDataID}" data-todo='{"EditingFrom":"EditMeetingMatrix"}'>${scoreDataName}
										</a>
										</td>
										<td class="matrix_goal">
											<a type="button" class="" data-toggle="modal"
											data-target="#scorecardAddMatrixModalPopop"
											data-whatever="${scoreDataID}" data-todo='{"EditingFrom":"EditMeetingMatrix"}'>${convertGoalMatrixToString(scoreDataGoal)}
										</a>
										</td>
										<td class="td_w_15 matrix_goal_matrix">
											<a type="button" class="" data-toggle="modal"
												data-target="#scorecardAddMatrixModalPopop"
												data-whatever="${scoreDataID}" data-todo=
												'{"EditingFrom":"EditMeetingMatrix"}'>${scoreDataGoalMatrix}
											</a>
										</td>
										<td>
											<a href="javascript:void(0)" id='deleteMatrix' onclick="deleteMatrix($(this),'${scoreDataID}')">
												<i class="fa fa-trash-o fa-lg"></i>
											</a>
										</td>
									</tr>
								`;

								if($('#metrics #metricsTable').length === 0){
									$('#metrics .matrix_main_inner_content').append(addingNewDataInMatrix);		
								}else{
									$('#metrics #metricsTable tbody').append(addingNewDataInMatrix);
								}
								formSubmitMessage("Measurement Added!");

							}else{	

								let apeandData = '';
								apeandData += '<tr>';

								if($('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'meetingStartMatrix'){
									apeandData += `<td><i class="fa fa-arrows"></i></td>
													<td><span class="attendees_img" style="background-image:url('${scoreDataUserImage}')">${scoreDataUserText}</span></td>
													<td class="td-border-bottom">
                                                    <a id="measurableModal" type="button" class="" data-toggle="modal"
                                                        data-target="#scorecardAddMatrixModalPopop"
                                                        data-whatever="${scoreDataID}" data-todo='{"EditingFrom":"meetingStartMatrix"}'>${scoreDataName}</a>
													</td>
													<td class="td-border-bottom ">${scoreData_goal_and_matric_and_units}</td>
													<td><i class="fa fa-line-chart"></i></td>
												`;
                                                
                                }else if($('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'meetingWorkspaceMatrix'){
                                    apeandData += `<td><span class="attendees_img" style="background-image:url('${scoreDataUserImage}')">${scoreDataUserText}</span></td>
                                                    <td class="td-border-bottom">
                                                    <a id="measurableModal" type="button" class="" data-toggle="modal"
                                                        data-target="#scorecardAddMatrixModalPopop"
                                                        data-whatever="${scoreDataID}" data-todo='{"EditingFrom":"meetingStartMatrix"}'>${scoreDataName}</a>
                                                    </td>
                                                    <td class="td-border-bottom ">${scoreData_goal_and_matric_and_units}</td>
                                                    <td><i class="fa fa-line-chart"></i></td>
                                                `;
								}else{
									apeandData += `<td class="td-border-bottom text-left">
													<a type="button" class="" data-toggle="modal"
														data-target="#scorecardAddMatrixModalPopop"
														data-whatever="${scoreDataID}">${scoreDataName}</a>
												</td>
												<td class="td-border-bottom ">${scoreData_goal_and_matric_and_units}</td>
												`;
								}

								<?
								if (isset($scorecardweeks_for_thead)) {
									foreach($scorecardweeks_for_thead as $week) {
										?>
										apeandData += `<td class="measurable_val_td">
												<input type="text" class="edit_content text-center" onblur="matrix_edit_content($(this),'${scoreDataID}','${scoreDataOwner}','0','Insert')" onkeyup="matrixvalidateInput(this)"/>
												<input type="hidden" class="tdweek" value="<?=str_replace('<br>', " to " , $week)?>">
											</td>'`; 
										<?
									}
								} 
								?>
								apeandData += `<input type="hidden" class="trID" value="${scoreDataID}">`;
								apeandData += '</tr>';
								$('#meetingMetricsTable tbody').prepend(apeandData);
								formSubmitMessage("Measurement Added!");
							}
						}
                    }
                    if (measurementResponse['status'] === 'Updated') {

                        if($('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'editMeetingMatrix'){
                            $('#metricsTable tbody tr.Editing td.matrix_name a').text(measurementResponse['measurable_name']);
							if(measurementResponse['measurable_goal']){
								$('#metricsTable tbody tr.Editing td.matrix_goal a').text(convertGoalMatrixToString(measurementResponse['measurable_goal']));
							}
                            $('#metricsTable tbody tr.Editing td.matrix_goal_matrix a').text(measurementResponse['measurable_matrix']);
                        }else{

                            if(measurementResponse['changeTileWithNewGaolMatrics'] === 'changeTile'){
                                let updatedGoalMatrics = measurementResponse['measurable_matrix'];
                                let updatedGoalSign = measurementResponse['measurable_goal'] === '=' ? '==' : measurementResponse['measurable_goal'];
                                $('#meetingMetricsTable tbody tr.Editing td.measurable_val_td.td-danger,#meetingMetricsTable tbody tr.Editing td.measurable_val_td.td-success').each(function(index,value){
                                    var tileValue = $(this).find('.edit_content').val();
                                    if(tileValue != ''){
                                        var parsevalue = parseInt(tileValue);
                                        $(this).removeClass('td-danger');
                                        $(this).removeClass('td-success');
										if(updatedGoalSign=="<=>"){
											var btn_goal=updatedGoalMatrics.split("-");
											var condition=`${parsevalue} >= ${btn_goal[0]} && ${parsevalue} <= ${btn_goal[1]} `
										}else{
											var condition = `${parsevalue} ${updatedGoalSign} ${updatedGoalMatrics}`;
										}
										if (eval(condition)) {
											$(this).addClass('td-success');
										} else {
											$(this).addClass('td-danger');
										}
                                    }
                                });
                            }

                            if($('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'meetingStartMatrix' || $('#scorecardAddMatrixModalPopop input[name="editingFrom"]').val() === 'meetingWorkspaceMatrix'){
                                // $('#meetingMetricsTable tbody tr.Editing td.matrics_attandees_img').html(`<span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att1.png')"></span>`);
                                // $('#meetingMetricsTable tbody tr.Editing td.nth-child(3) a').text(measurementResponse['measurable_name']);
                                // $('#meetingMetricsTable tbody tr.Editing td.nth-child(4)').text(measurementResponse['measurable_goal_and_matric_and_units']);
                                $('#meetingMetricsTable tbody tr.Editing td.matrics_mesurable_name a').text(measurementResponse['measurable_name']);
                                $('#meetingMetricsTable tbody tr.Editing td.matrics_mesurable_goal').text(measurementResponse['measurable_goal_and_matric_and_units']);
                            }else{
                                $('#meetingMetricsTable tbody tr.Editing td:nth-child(1) a').text(measurementResponse['measurable_name']);
                                $('#meetingMetricsTable tbody tr.Editing td:nth-child(2)').text(measurementResponse['measurable_goal_and_matric_and_units']);
                            }
                        }
						$('#metricsTable tbody tr.Editing').removeClass('Editing');
                        formSubmitMessage("Measurement Updated!");
                    }
                } else {
                    formSubmitMessage("Response Error!");
                }
            },
            complete: function() {
                reset_form_fields('measurement');
                measurable_modal.attr('disabled', false);
                measurable_modal.prev('.spinner').addClass('d-none');
            },
        })

    })


    $('#scorecardAddMatrixModalPopop #goals').on('change', function(e) {
        let select_value = $(this).val();
        if (select_value == "<=>") {
            $('#scorecardAddMatrixModalPopop .betweenmatrics').removeClass('d-none');
        } else {
            $('#scorecardAddMatrixModalPopop .betweenmatrics').addClass('d-none');
        }
    });
	

});

// Mesurement/Scorecard/Matrix Modal Function End


//meeting start notification
function displayMeetingNotificationPopup(response){
            var result=JSON.parse(response);
            $('#meeting_notification').html("");
            if(result.length>0){
                var meet_notification="";
                $.each(result, function(k,v){
                    var meeting_para="meeting_id="+v.encoded_meeting_id+"&meeting_timer_id="+v.encoded_meeting_timer_id;
                    meet_notification+=`<div class="meeting_start_notification">
                    <p class="mb-0">${v.meeting_name} meeting started, <a href="meeting_timer_started.php?${meeting_para}" class="join_meeting_popup" meeting_timer_id="${v.meeting_timer_id}">Join Now</a><span class="close_notification float-right" meeting_timer_id="${v.meeting_timer_id}"><i class="fa fa-times"></i></span></p>
                        </div>`;
                });
            }
            $('#meeting_notification').html(meet_notification);
        }
        function check_meeting_started(){
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'check_for_meeting_start_updates':1},
                datatype:'json',
                async: false, 
                success:function(response){
                    displayMeetingNotificationPopup(response);
                    var cur_page=$('#page_type_for_notification').val();
					var data_str="get_live_meeting_status_updates=1";
					var meeting_chk=true;
                    if(cur_page=="dashboard_meetings"){
                        data_str+="&data_of=all&emp_level='<?=$emp_level;?>'";
                    }else if(cur_page == "dashboard_meeting_create" || cur_page == "launch_meeting"){
						var meeting_id="<?= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? $_GET['meeting_id']:"";?>";
						meeting_chk=meeting_id=="" ? false : true; 
                        //data={'&data_of':'single','meeting_id':'<?= $_GET['meeting_id'];?>'}
                        data_str+="&data_of=single&meeting_id="+meeting_id;
                    }
					if(meeting_chk==true){
                    $.ajax({
                        url:'dashboard_meeting_action.php',
                        type:'get',
                        data:data_str,
                        datatype:'json',
                        async: false, 
                        success:function(response){
                            var result=JSON.parse(response);
							if(result.length>0){
                                if(cur_page=="dashboard_meetings"){
                                    $.each(result, function(k,v){
                                        if(v.meeting_flg==1){
                                            $('#meeting_btn_'+v.meeting_id).html(`<a href="launch_meeting.php?meeting_id=${v.meeting_id_enc}" class="btn go-to-meeting-btn btn-sm">Go to Meeting</a>`);
											$('#meeting_status_td_'+v.meeting_id).find('i').removeClass('fa-star').addClass('fa-star-o');
											$('#meeting_status_td_'+v.meeting_id).find('i span').html(1);
											$('#meeting_name_'+v.meeting_id).find('a').attr('href',`launch_meeting.php?meeting_id=${v.meeting_id_enc}`)
										}else{
                                            $('#meeting_btn_'+v.meeting_id).html(`<a  href="meeting_timer_started.php?meeting_id=${v.meeting_id_enc}&meeting_timer_id=${v.meeting_timer_id_enc}" class="btn meeting-started-btn btn-sm ">
                                            Meeting Started
                                            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></a>`);
											$('#meeting_status_td_'+v.meeting_id).find('i').removeClass('fa-star-o').addClass('fa-star');
											$('#meeting_status_td_'+v.meeting_id).find('i span').html(0);
											$('#meeting_name_'+v.meeting_id).find('a').attr('href',`meeting_timer_started.php?meeting_id=${v.meeting_id_enc}&meeting_timer_id=${v.meeting_timer_id_enc}`)
                                        }
							
                                    })
									//$('#meetingTable').dataTable({aaSorting: [[0, 'desc']]})
                                }
                                if(cur_page=="dashboard_meeting_create"){
                                    $.each(result, function(k,v){
                                        if(v.meeting_flg==1){
                                             $('#launch_meeting_btn').html(`<a href="launch_meeting.php?meeting_id=${v.meeting_id_enc}" onclick="return check_meeting_saved()" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i> Launch Meeting</a>`);
                                        }else{
                                            $('#launch_meeting_btn').html(`<a href="meeting_timer_started.php?meeting_id=${v.meeting_id_enc}&meeting_timer_id=${v.meeting_timer_id_enc}" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i>
                                                Meeting Started
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            </a>`); 
                                        }
                                    });
                                }
								console.log(cur_page+" cur_page");
                                if(cur_page=="launch_meeting"){
                                    $.each(result, function(k,v){
                                        if(v.meeting_flg==1){
                                             $('#launch_meeting_btn').html(`<a href="meeting_timer_started.php?meeting_id=${v.meeting_id_enc}" class="btn btn-primary btn-sm" id="start_meeting_btn">Start Meeting As Leader</a>`);
                                        }else{
                                            $('#launch_meeting_btn').html(`<a href="meeting_timer_started.php?meeting_id=${v.meeting_id_enc}&meeting_timer_id=${v.meeting_timer_id_enc}" class="btn meeting-started-btn btn-sm">
                                                Meeting Started
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            </a>`); 
                                        }
                                    });
                                }
                            }
                        }
                    });
					}
                }
            });
        }

        $(document).on('click','.join_meeting_popup',function(){
            var meeting_timer_id=$(this).attr('meeting_timer_id');
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'hide_notification_for_meeting_start_updates':2, meeting_timer_id},
                success:function(response){
                    displayMeetingNotificationPopup(response);
                }
            });
        })

        $(document).on('click','.close_notification',function(){
            var meeting_timer_id=$(this).attr('meeting_timer_id');
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'hide_notification_for_meeting_start_updates':1, meeting_timer_id},
                    success:function(response){
                }
            });
        });

	//meeting start notification end-

	//What VTO
	$('#open-vto').click(function(){
		var what_vto=$('#what-vto-select').val();
		if(what_vto=="Usedcarboardboxes"){
			window.location.href='UsedcarboardboxesVTO.php';
		}else if(what_vto=="UCBZeroWaste"){
			window.location.href='UCBZeroWasteVTO.php';
		}else if(what_vto=="2ndskid"){
			window.location.href='2ndskidVTO.php';
		}
	})

</script>