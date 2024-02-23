function formSubmitMessage(message){
	$('#form-submit-message').text(message);
	$('#form-submission-message').fadeIn('slow');
	setTimeout(function() {
		$('#form-submission-message').fadeOut('slow');
	}, 3000); 
}
$(document).ready(function() {
	$('#new_task_tile').click(function(){
		reset_form_fields('task');
		$('#newTask').modal('show');
		$('#modal_task_title').html("Add New Task");
		$('#task_action').val("ADD");
		$('.project_meetings_dp').val([0]).trigger("change");	
	});
	$('#new_task_tile_workspace').click(function(){
		reset_form_fields('task');
		$('#newTask').modal('show');
		$('#modal_task_title').html("Add New Task");
		$('#task_action').val("ADD_FROM_WORKSPACE");
		$('#task_meeting').val([$('#hidden_meeting_id').val()]).trigger("change");	
	});
	$('#new_task_header').click(function(){
		reset_form_fields('task');
		$('#newTask').modal('show');
		$('#modal_task_title').html("Add New Task");
		$('#task_action').val("ADD");
		$('#project_meetings_dp').val([0]).trigger("change");	
	});
	$('#new_project_tile').click(function(){
		reset_form_fields('project');
		$('#newProject').modal('show');
		$('#modal_project_title').html("Add New Project");
		$('#project_action').val("ADD");
	});
	$('#new_project_tile_workspace').click(function(){
		reset_form_fields('project');
		$('#newProject').modal('show');
		$('#modal_project_title').html("Add New Project");
		$('#project_action').val("ADD_FROM_WORKSPACE");
		$('.project_meetings_dp').val([$('#hidden_meeting_id').val()]).trigger("change");	
	});
	$('#new_project_header').click(function(){
		reset_form_fields('project');
		$('#newProject').modal('show');
		$('#modal_project_title').html("Add New Project");
		$('#project_action').val("ADD");
	});
	$('#new_issue_tile').click(function(){
		reset_form_fields('issue');
		$('#newIssue').modal('show');
		$('#modal_issue_title').html("Add New Issue");
		$('#issue_action').val("ADD");
	});
	$('#new_issue_tile_workspace').click(function(){
		reset_form_fields('issue');
		$('#newIssue').modal('show');
		$('#modal_issue_title').html("Add New Issue");
		$('#issue_action').val("ADD_FROM_WORKSPACE");
		$('#newIssue .issue_meeting_div').removeClass('d-none');
		$('#issue_meeting').val([$('#hidden_meeting_id').val()]).trigger("change");	
		$('#hidden_meeting_id_issue_modal').val([$('#hidden_meeting_id').val()]).trigger("change");	
		
	});
	$('#new_issue_header').click(function(){
		reset_form_fields('issue');
		$('#newIssue').modal('show');
		$('#modal_issue_title').html("Add New Issue");
		$('#issue_action').val("ADD");	
		$('#newIssue .issue_meeting_div').removeClass('d-none');
	});
	$('#new_measurement').click(function(){
		reset_form_fields('measurement');
		// $('#scorecardAddMatrixModalPopop').modal('show');
	});
	$('[data-toggle="tooltip"]').tooltip()
	$('[data-tooltip="true"]').tooltip();
	$('.mytooltip').tooltip();
	$('#dataTable').DataTable();
	//$('.summernote').summernote();
	$('.summernote').summernote({
	  toolbar: [
		    ['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'clear']],
			// ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
			['fontname',/* ['fontname'] */],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', [/*'height' */]],
			['table', ['table']],
			['insert', ['link', 'picture', 'hr']],
			['view', [/*'fullscreen', 'codeview' */]],   // remove codeview button 
			['help', [/*'help'*/]],
			],
      callbacks: {
         /* onBlur: function (e) {
              var p = e.target.parentNode.parentNode
              if (!(e.relatedTarget && $.contains(p, e.relatedTarget))) {
                  $(this).parent().children('.note-editor').children('.note-toolbar').css("display", "none");
              }
          },*/
          onFocus: function (e) {
              $(this).parent().children('.note-editor').children('.note-toolbar').css("display", "block");
        }
      }
	});
	
	$('.select2').select2({width: "100%", 'val':3});
});

function reset_form_fields(formname, close_modal=true){
	$('.form_error').addClass('d-none');
	if(formname=="project"){
		$('#save-project').removeAttr('disabled');
		$('#save-project').prev('.spinner').addClass('d-none');
		$('#form_project').trigger("reset");
		$('#summernote-project').summernote('reset');
		$('#project_meetings').val([0]).trigger("change");
		$('.addMilestoneTable').addClass('d-none');
		$('.addMilestoneTable tbody').empty();
		$('#removeProject').addClass('d-none');
		if(close_modal==true){
			$("#newProject").modal('hide');
		}
	}else if(formname=="task"){
		$('#save-task').removeAttr('disabled');
		$('#save-task').prev('.spinner').addClass('d-none');
		$('#form_task').trigger("reset");
		$('#summernote-task').summernote('reset');
		if(close_modal==true){
			$("#newTask").modal('hide');
		}
	}else if(formname=="issue"){
		$('#save-issue').removeAttr('disabled');
		$('#save-issue').prev('.spinner').addClass('d-none');
		$('#form_issue').trigger("reset");
		$('#summernote-issue').summernote('reset');
		if(close_modal==true){
			$("#newIssue").modal('hide');
		}
	}else if(formname=="page"){
		$('#save-edit-page').removeAttr('disabled');
		$('#save-edit-page').prev('.spinner').addClass('d-none');
		$('#edit_pages_add_new').trigger("reset");
		if(close_modal==true){
			$("#editPagesNewPageModal").modal('hide');
		}
	}
    else if(formname=="measurement"){
        $('#scorecardAddMatrixModalPopop form').trigger("reset");
        $('#scorecardAddMatrixModalPopop form')[0].reset();	
        $('#scorecardAddMatrixModalPopop form #goals').val('==');
        $('#scorecardAddMatrixModalPopop form .changeMatricsValue').addClass('d-none');
        $('#scorecardAddMatrixModalPopop .betweenmatrics').addClass('d-none');
        $('#scorecardAddMatrixModalPopop form input[name="editingFrom"]').remove();
        if(close_modal==true){
			$("#scorecardAddMatrixModalPopop").modal('hide');
		}
    }
}
