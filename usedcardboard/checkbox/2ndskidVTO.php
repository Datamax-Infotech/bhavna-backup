<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 

    $vto_data_sql=db_query("SELECT id,title,description FROM 2ndkid_vto",db_project_mgmt());
    $vto_name_array=[];
    $vto_core_value_array=[];
    $vto_focus_array=[];
    $vto_bhag_array=[];
    $vto_3yr_vision_array=[];
    $vto_1yr_goal_array=[];
    while($r=array_shift($vto_data_sql)){
        switch($r['id']){
            case 1:
                $vto_name_array=$r;
                break;
            case 2:
                $vto_core_value_array=$r;
                break; 
            case 3:
                $vto_focus_array=$r;
                break;
            case 4:
                $vto_bhag_array=$r;
                break;
            case 5:
                $vto_3yr_vision_array=$r;
                break;
            case 6:
                $vto_1yr_goal_array=$r;
                break;
             
        }
    }
    $emp_level_qry=db_query("SELECT level from loop_employees where b2b_id=".$_COOKIE['b2b_id'],db());
    $user_level=array_shift($emp_level_qry)['level'];
    $vto_edit_class="not_editable";
    if($user_level==2){
        $vto_edit_class="editable";
    };
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
        <div class="container-fluid company_vto  p-4 my-2 " id="company_vto_div" >
            <div class="card p-5">
				
				<a class="btn btn-success btn-sm" style="width: 150px; align-self: end;" href="javascript:history.go(-1);">Go Back</a>
		        
                <table class="table_vetical_align_middle vto-table table border-0 table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="vto_title">VTO</td>
                            <td class="font-weight-bold"><a id="vto_title_display_<?= $vto_name_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_name_array['id']; ?>)"><?= $vto_name_array['title'];?></a></td>
                            <td class="vto_company"><a href="javascript:open_popup(<?= $vto_name_array['id']; ?>)" id="vto_description_display_<?= $vto_name_array['id']; ?>" class="<?=$vto_edit_class; ?>"><?= $vto_name_array['description'];?></a></td>
                            <td class="vto_page_title">Future Focus</td>
                        </tr>
                    </tbody>
                </table>
               <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="vto-header"><a id="vto_title_display_<?= $vto_core_value_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $vto_core_value_array['id']; ?>)"><?= $vto_core_value_array['title'];?></a></div>
                        <div><a id="vto_description_display_<?= $vto_core_value_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_core_value_array['id']; ?>)"><?= $vto_core_value_array['description'];?></a></div>
                    </div>
                    <div class="col-md-6">
                        <div class="vto-header"><a id="vto_title_display_<?= $vto_focus_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $vto_focus_array['id']; ?>)"><?= $vto_focus_array['title'];?></a></div>
                        <div><a id="vto_description_display_<?= $vto_focus_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_focus_array['id']; ?>)"><?= $vto_focus_array['description'];?></a></div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8 bhag-section">
                        <div class="vto-header"><a id="vto_title_display_<?= $vto_bhag_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $vto_bhag_array['id']; ?>)"><?= $vto_bhag_array['title'];?></a></div>
                        <div><a id="vto_description_display_<?= $vto_bhag_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_bhag_array['id']; ?>)"><?= $vto_bhag_array['description'];?></a></div>
                  
                    </div>
                    <div class="col-md-4">
                        <div class="vto-header"><a id="vto_title_display_<?= $vto_3yr_vision_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $vto_3yr_vision_array['id']; ?>)"><?= $vto_3yr_vision_array['title'];?></a></div>
                        <div><a id="vto_description_display_<?= $vto_3yr_vision_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_3yr_vision_array['id']; ?>)"><?= $vto_3yr_vision_array['description'];?></a></div>
                    </div>

                </div>
            </div>
            <div class="card p-5 mt-3">
                <h3 class="company_vto_title">VTO</h3>
                    <div class="row justify-content-start">
                    <div class="col-md-6">
                        <div class="vto-header"><a id="vto_title_display_<?= $vto_1yr_goal_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $vto_1yr_goal_array['id']; ?>)"><?= $vto_1yr_goal_array['title'];?></a></div>
                        <div><a id="vto_description_display_<?= $vto_1yr_goal_array['id']; ?>" class="<?=$vto_edit_class; ?>" href="javascript:open_popup(<?= $vto_1yr_goal_array['id']; ?>)"><?= $vto_1yr_goal_array['description'];?></a></div>
                    </div>
                </div>
            </div> 
        <?php require_once("inc/footer_new_dashboard.php");?> 
        </div>
        
	</div>
	</div>

    <div class="modal fade" id="editVTOPopUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
			<form action="" name="company_vto_form_fields" id="company_vto_form_fields" onsubmit="return false;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Edit Company VTO</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_company_vto_form_fields()" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" id="company_vto_title" />
                          <input type="hidden" id="company_vto_id"/>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description <span class="text-danger">*</span> </label>
						<div id="company_vto_description" class="summernote"></div> 
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
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-company-vto">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_company_vto_form_fields()" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
					</div>
                </div>
            </div>
            
			</form>
        </div>
	</div>	
	
    
    
	<script>
        function reset_company_vto_form_fields(){
            $('#company_vto_form_fields').trigger("reset");
            $('#company-vto').summernote('reset');    
        }

        function open_popup(id){
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'edit_vto':'2ndkid_vto',id},
                datatype:'json',
                success:function(response){
                    var result=JSON.parse(response);
                    console.log(result);
                    $('#company_vto_id').val(result.id);
                    $('#company_vto_title').val(result.title);
                    $('#company_vto_description').summernote('code',result.description);
                }
            });
            $('#editVTOPopUp').modal('show');
        }
        $("#company_vto_form_fields").submit(function(){
            var title=$('#company_vto_title').val();
            var description = $('#company_vto_description').summernote('code');
            var id=$('#company_vto_id').val();
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'post',
                data:{'update_vto':'2ndkid_vto',title,description,id},
                datatype:'json',
                async:false,
                beforeSend: function () {
                    $('#save-company-vto').attr('disabled',true);
					$('#save-company-vto').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                  var res=JSON.parse(response);
                   $('#vto_title_display_'+id).html(res.title);
                   $('#vto_description_display_'+id).html(res.description);
                },
                complete:function(){
                    $('#save-company-vto').attr('disabled',false);
					$('#save-company-vto').prev('.spinner').addClass('d-none');
                    $('#editVTOPopUp').modal('hide');
                }
            });
           
        });
 </script>
</body>

</html>