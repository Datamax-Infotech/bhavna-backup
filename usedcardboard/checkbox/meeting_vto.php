<?php 
	session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 

    $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
    ?>
    <style>
       #meeting_vto_div table, #editVTOPopUp table {
        width: 100% !important;
        max-width: 100%;
        table-layout: fixed;
       }
       #meeting_vto_div table td,  #editVTOPopUp table td{
        padding:.25em .75em;
       }
       .note-editable{
        height:320px !important;
       }
    </style>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content" class="create_meeting">
        <div class="container-fluid meeting_vto  p-4 my-2 " id="meeting_vto_div" >
            <div class="card p-5">
                <?
                    if($meeting_id==""){
                ?>
                    <div class="col-md-12 alert alert-danger">
                        <p class="mb-0"><a href="dashboard_meetings.php"><b>Click</b></a> here to choose the Meeting First!</p>
                    </div>
                <?
                    }else{
                        $vto_data_sql=db_query("SELECT * FROM meeting_vto where meeting_id=$meeting_id",db_project_mgmt());
                        $goals_for_year_title="GOALS FOR THE YEAR";
                        $goals_for_the_year	='<table class="table table-bordered" style="width: 1084.8px;"><tbody><tr><td><p><span style="font-weight: bolder;"><span style="font-size: 15px; text-wrap: nowrap;">Future Date:</span></span><br></p></td><td><span style="font-weight: bolder;"><br></span></td></tr><tr><td><span style="font-weight: bolder;">Revenue:&nbsp;</span><br></td><td><br></td></tr><tr><td><span style="font-weight: bolder;">Profit</span><br></td><td><br></td></tr><tr><td><span style="font-weight: bolder;">Measurables:<br></span></td><td><br></td></tr></tbody></table><p><span style="font-size: 15px; font-weight: 700;"><br></span></p><table class="add-row-container fix-print-rows" style="border-spacing: 0px; width: 513.688px; margin: auto; color: rgb(51, 51, 51); font-family: Lato, sans-serif;"><tbody><tr><th colspan="2" style="padding: 0px; text-align: left;"><span style="color: rgb(48, 48, 48); font-family: Nunito, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 15px;">Goals for the year:</span><br></th></tr></tbody></table><ol><li><br></li></ol>';
                        $quarterly_rock_title="QUARTERLY ROCK";
                        $quarterly_rock='<table class="table table-bordered" style="width: 1084.8px;"><tbody><tr><td><p><span style="font-weight: bolder;"><span style="font-size: 15px; text-wrap: nowrap;">Future Date:</span></span><br></p></td><td><span style="font-weight: bolder;"><br></span></td></tr><tr><td><span style="font-weight: bolder;">Revenue:&nbsp;</span><br></td><td><br></td></tr><tr><td><span style="font-weight: bolder;">Profit</span><br></td><td><br></td></tr><tr><td><span style="font-weight: bolder;">Measurables:<br></span></td><td><br></td></tr></tbody></table><p><span style="font-size: 15px; font-weight: 700;"><br></span></p><table class="add-row-container fix-print-rows" style="border-spacing: 0px; width: 513.688px; margin: auto; color: rgb(51, 51, 51); font-family: Lato, sans-serif;"><tbody><tr><th colspan="2" style="padding: 0px; text-align: left;">Quarterly Rock:</th></tr></tbody></table><ol><li><br></li></ol>';
                        $vto_issue_title="VTO ISSUES";
                        $vto_issue='<table class="table table-bordered"><tbody><tr><td>1</td><td><br></td><td><br></td></tr></tbody></table>';
                        if(tep_db_num_rows($vto_data_sql)>0){
                            $result=array_shift($vto_data_sql);
                            $vto_id=$result['id'];
                            $goals_for_year_title=$result['goals_for_year_title'];
                            $goals_for_the_year=$result['goals_for_the_year'];
                            $quarterly_rock_title=$result['quarterly_rock_title'];
                            $quarterly_rock=$result['quarterly_rock'];
                            $vto_issue_title=$result['vto_issue_title'];
                            $vto_issue=$result['vto_issue'];
                        }else{
                            $ins_sql=db_query("INSERT INTO meeting_vto (meeting_id,goals_for_year_title,goals_for_the_year,quarterly_rock_title,quarterly_rock,vto_issue_title,vto_issue,created_by,created_on) 
                            VALUES('$meeting_id','$goals_for_year_title','$goals_for_the_year','$quarterly_rock_title','$quarterly_rock','$vto_issue_title','$vto_issue','".$_COOKIE['b2b_id']."','".date("Y-m-d h:i:s")."')",db_project_mgmt());
                            $vto_id=tep_db_insert_id();
                        }
                        /* $emp_level_qry=db_query("SELECT level from loop_employees where b2b_id=".$_COOKIE['b2b_id'],db());
                        $user_level=array_shift($emp_level_qry)['level'];
                        $vto_edit_class="not_editable";
                        if($user_level==2){
                            $vto_edit_class="editable";
                        };*/
                        $vto_edit_class="editable";
                ?>
                <a class="btn btn-success btn-sm" style="width: 150px; align-self: end;" href="javascript:history.go(-1);">Back To Meeting</a>
		        <h1 class="vto_title">VTO</h1>
                <div class="row justify-content-center">
                    <div class="col-md-6 mt-2">
                        <div class="vto-header"><a id="goal_title" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_goal')"><?=$goals_for_year_title;?></a></div>
                        <div><a id="goals_for_the_year" class="<?=$vto_edit_class; ?>" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_goal')"><?=$goals_for_the_year?></a></div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="vto-header"><a id="quarterly_rock_title" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_rock')"><?=$quarterly_rock_title;?></a></div>
                        <div><a id="quarterly_rock" class="<?=$vto_edit_class; ?>" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_rock')"><?=$quarterly_rock?></a></div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="vto-header"><a id="vto_issue_title" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_issue')"><?=$vto_issue_title;?></a></div>
                        <div><a id="vto_issue" class="<?=$vto_edit_class; ?>" href="javascript:open_popup_meeting_vto(<?=$vto_id;?>,'edit_issue')"><?=$vto_issue;?></a></div>
                    </div>
                </div>
                <? } ?>
            </div>
            <?php require_once("inc/footer_new_dashboard.php");?>  
    
	    </div>
	    </div>
       <div class="modal fade" id="editVTOPopUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
			<form action="" name="meeting_vto_form_fields" id="meeting_vto_form_fields" onsubmit="return false;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Edit Meeting VTO</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_meeting_vto_form_fields()" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" id="meeting_vto_title" />
                          <input type="hidden" id="meeting_vto_id"/>
                          <input type="hidden" id="edit_vto_of"/>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description <span class="text-danger">*</span> </label>
						<div id="meeting_vto_description" class="summernote" style="height:250px;"></div> 
					 </div>
					</div>	 
				</div>
                <div class="modal-footer justify-content-end">
                   <div class="btn-right">
                        <p class="vto-edit-reminder">REMINDER: Whatever you save will be seen by the entire company! No sensitive information!</p>
                    </div>
					<div class="btn-right">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-meeting-vto">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_meeting_vto_form_fields()" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
					</div>
                </div>
            </div>
            
			</form>
        </div>
	</div>	
	
   
    
	<script>
        function reset_meeting_vto_form_fields(){
            $('#meeting_vto_form_fields').trigger("reset");
            $('#meeting-vto').summernote('reset');    
        }

        function open_popup_meeting_vto(id,edit_type){
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'edit_meeting_vto':1,id,edit_type, meeting_id:'<?=$meeting_id;?>'},
                datatype:'json',
                success:function(response){
                    var result=JSON.parse(response);
                    $('#meeting_vto_id').val(result.id);
                    $('#meeting_vto_title').val(result.title);
                    $('#meeting_vto_description').summernote('code',result.description);
                    $('#edit_vto_of').val(edit_type);
                }
            });
            $('#editVTOPopUp').modal('show');
        }
        $("#meeting_vto_form_fields").submit(function(){
            var title=$('#meeting_vto_title').val();
            var description = $('#meeting_vto_description').summernote('code');
            var id=$('#meeting_vto_id').val();
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'post',
                data:{'update_meeting_vto':1,title,description,id,edit_type:$('#edit_vto_of').val()},
                datatype:'json',
                async:false,
                beforeSend: function () {
                    $('#save-meeting-vto').attr('disabled',true);
					$('#save-meeting-vto').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                  var res=JSON.parse(response);
                  $('#edit_goal_title').html(res.goals_for_year_title);
                  $('#goals_for_the_year').html(res.goals_for_the_year);
                  $('#quarterly_rock_title').html(res.quarterly_rock_title);
                  $('#quarterly_rock').html(res.quarterly_rock);
                  $('#vto_issue_title').html(res.vto_issue_title);
                  $('#vto_issue').html(res.vto_issue);
                },
                complete:function(){
                    $('#save-meeting-vto').attr('disabled',false);
					$('#save-meeting-vto').prev('.spinner').addClass('d-none');
                    $('#editVTOPopUp').modal('hide');
                    formSubmitMessage("Meeting VTO updated!");
                }

            });
        });

       
 </script>
</body>

</html>