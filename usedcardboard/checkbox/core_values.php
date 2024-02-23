<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 
	
    $core_value_data_sql=db_query("SELECT id,title,description FROM core_values",db_project_mgmt());
    $core_value_name_array = [];
    while($r = array_shift($core_value_data_sql))
	{
		$core_value_name_array = $r;
    }
    $emp_level_qry=db_query("SELECT level from loop_employees where b2b_id=".$_COOKIE['b2b_id'],db());
    $user_level=array_shift($emp_level_qry)['level'];
    $core_value_edit_class="not_editable";
    if($user_level==2){
        $core_value_edit_class="editable";
    };	

    ?>
    <div id="wrapper">
	<? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
            <div class="container-fluid company_core_value  p-4 my-2 ">
                    <div class="row justify-content-center">
                    <div class="col-lg-10  core-values" id="core-values-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">
								<? if($user_level==2){?>
									<a id="core_values_title_display_<?= $core_value_name_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input" href="javascript:open_popup(<?= $core_value_name_array['id']; ?>)"><?= $core_value_name_array['title'];?></a>							
								<? }else{?>
									<a id="core_values_title_display_<?= $core_value_name_array['id']; ?>" class="<?=$vto_edit_class; ?> vto-header-input"><?= $core_value_name_array['title'];?></a>							
								<? }?>
							</h6>
							<a class="btn btn-primary btn-sm" style="width: 150px; align-self: end;" href="dashboard_management_v1.php">Back To Dash</a>
						
							<!--<div class="dropdown no-arrow">
								<a class="dropdown-toggle mx-1" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item disabled" href="#" style="cursor:no-drop">Delete Tile</a>
								</div>
							</div>-->
						</div>
						<div class="card-body fixed-height-card">
							<div class="col-md-12">
								<div>
									<? if($user_level==2){?>
										<a id="core_values_description_display_<?= $core_value_name_array['id']; ?>" class="<?=$core_value_edit_class; ?>" href="javascript:open_popup(<?= $core_value_name_array['id']; ?>)">
										<?= $core_value_name_array['description'];?></a>
									<? }else{?>
										<a id="core_values_description_display_<?= $core_value_name_array['id']; ?>" class="<?=$core_value_edit_class; ?>" href="#">
										<?= $core_value_name_array['description'];?></a>
									<? }?>
								
								</div>							
								
								<!-- 
								<p class="core-value-heading">Reliability</p>
								<p class="core-value-desc">We do what we are supposed to do, when we are supposed to do it, or we communicate otherwise in advance.</p>
								<p class="core-value-heading">Transparent Integrity</p>
								<p class="core-value-desc">Mistakes happen, but when we mess up, we fess up...openly and honestly.</p>
								<p class="core-value-heading">Innovation</p>
								<p class="core-value-desc">We proactively seek new information and perspective so we can apply it to our current and future marketplace, thus continuing to always pioneer.</p>
								<p class="core-value-heading">Bold, Relentless Passion</p>
								<p class="core-value-desc">We are confident in our abilities and proactively seek opportunities to create synergies with suppliers, customers, partners, and employees. We know that rarely the first attempt is successful, and we are not afraid to try countless times if we believe value can be created.</p>
								<p class="core-value-heading">Sustainable Sustainability</p>
								<p class="core-value-desc">We believe in sustainability programs that last. Our work focuses on financial results, in addition to environmental.</p>
								-->
							</div>
						</div>
					</div>
				</div>
                    </div>
					<?php require_once("inc/footer_new_dashboard.php");?>  
            </div>
	    </div>
	</div>

    <div class="modal fade" id="editCoreValuePopUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
			<form action="" name="company_core_value_form_fields" id="company_core_value_form_fields" onsubmit="return false;" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Edit Core Values</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_company_core_value_form_fields()" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" id="company_core_value_title" />
                          <input type="hidden" id="company_core_value_id"/>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description <span class="text-danger">*</span> </label>
						<div id="company_core_value_description" class="summernote"></div> 
					 </div>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-right"></div>
					<div class="btn-right">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-company_core_value">
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_company_core_value_form_fields()" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
					</div>
                </div>
            </div>
            
			</form>
        </div>
	</div>	

    
	
	<script>
        function reset_company_core_value_form_fields(){
            $('#company_core_value_form_fields').trigger("reset");
            $('#company_core_value').summernote('reset');    
        }
		
        function open_popup(id){
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'get',
                data:{'edit_core_value':1,id},
                datatype:'json',
                success:function(response){
                    var result=JSON.parse(response);
                    console.log(result);
                    $('#company_core_value_id').val(result.id);
                    $('#company_core_value_title').val(result.title);
                    $('#company_core_value_description').summernote('code',result.description);
                }
            });
            $('#editCoreValuePopUp').modal('show');
        }
		
        $("#company_core_value_form_fields").submit(function(){
            var title=$('#company_core_value_title').val();
            var description = $('#company_core_value_description').summernote('code');
            var id=$('#company_core_value_id').val();
            $.ajax({
                url:'dashboard_meeting_action.php',
                type:'post',
                data:{'update_core_value':1,title,description,id},
                datatype:'json',
                async:false,
                beforeSend: function () {
                    $('#save-company_core_value').attr('disabled',true);
					$('#save-company_core_value').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                  var res=JSON.parse(response);
                   $('#core_values_title_display_'+id).html(res.title);
                   $('#core_values_description_display_'+id).html(res.description);
                },
                complete:function(){
                    $('#save-company_core_value').attr('disabled',false);
					$('#save-company_core_value').prev('.spinner').addClass('d-none');
                    $('#editCoreValuePopUp').modal('hide');
                }
            });
           
        });
	</script>
 
</body>

</html>