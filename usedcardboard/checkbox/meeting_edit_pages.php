<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require ("inc/functions_mysqli.php"); 
	require ("function-dashboard-newlinks.php"); 
	require_once("inc/header_new_dashboard.php"); 
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
		
		<div class="container-fluid p-2 create_meeting mt-0" >    
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <?php 
                    $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
                    if($meeting_id!=""){
                        $sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
                        $meeting_name= array_shift($sql_main)['meeting_name'];
                    ?>
                    
                    <div class="edit_page_header mt-5 d-flex justify-content-between">
                        <h2><?php echo $meeting_name; ?>  </h2>
                        <input type="hidden" id="hidden_meeting_id" value="<?php echo $meeting_id;?>" />
                        <div>
                            <a class="btn btn-success btn-sm mr-1" href="dashboard_meeting_create.php?meeting_id=<?=$_GET['meeting_id']; ?>">Back To Meeting</a> 
                            <button class="btn btn-white btn-sm" data-toggle="modal" data-target="#editPagesNewPageModal">New Page</button>
                        </div>
                    </div>
                    <div class="card py-3 px-4 mt-2" id="meetingEditPagesDiv">
                    <?php $sql_main =db_query("SELECT * FROM meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC", db_project_mgmt());
                    if(tep_db_num_rows($sql_main)>0){
                    ?>
                    <table id="editPagesTable" class="table table-sm meetingTable table_vetical_align_middle hover_table mt-5 border-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Minutes</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i=0;while($hrow = array_shift($sql_main)){?>
                            <tr id="page_tr_<?php echo $hrow['page_id']; ?>" data-sort-id="<?php echo $hrow['page_id'];?>">
                                <td><i class="fa fa-arrows"></i></td>
                                <td class="td_w_60"><?php echo $hrow['page_title'] ;?></td>
                                <td><span class="add_pages_type"><?php echo $hrow['page_type'] ;?></span></td>
                                <td><?php echo $hrow['duration']; ?></td>
                                <td>
                                    <a href="javascript:edit_page(<?php echo $hrow['page_id']; ?>)">Edit</a>
                                    <a href="javascript:void(0)" page_id="<?php echo $hrow['page_id']; ?>" class="ml-3 deletePageCreateMeet">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table> 
                    <?php }else{?>
                        <div class="text-center no_issue_div justify-content-start">
                            <img src="assets_new_dashboard/img/notes.svg" class="no_issue_img"/>
                            <p>No Pages</p>
                        </div>
                    <?php } ?>
                
                    </div>
                    <?php }else{?>
                        <div class="col-md-12 alert alert-danger">
                            <p class="mb-0"><a href="dashboard_meetings.php"><b>Click</b></a> here to choose the Meeting First!</p>
                        </div>
                   <?php } ?>
                </div> 
            </div>
            <?php require_once("inc/footer_new_dashboard.php");?>
        </div>
	</div>
	</div>

    
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>  
    <script>
            $(document).ready(function(){
                $('#editPagesTable').dataTable({
                    "searching": false,
                    info: false,
                    paging: false,
                    rowReorder: {
                        update: false
                    },
                    columnDefs: [
                        { orderable: false, targets: '_all' }
                    ],
                    select: true
                });
                $('#editPagesTable tbody').sortable({
                handle: 'i.fa-arrows',
                placeholder: "ui-state-highlight",
                //opacity: 0.9,
                update : function () {
                    var order = $('#editPagesTable tbody').sortable('toArray', { attribute: 'data-sort-id'});
                    //console.log(order.join(','));
                    sortOrder   =   order.join(',');
                    //console.log(sortOrder);
                    $.post(
                        'dashboard_meeting_action.php',
                        {'page_sort_action':'updateSortedRowsOfPage','sortOrder':sortOrder},
                        function(data){
                            if(data==1){
                                formSubmitMessage("Page Order Updated!");
                            }
                        }
                    );
                }
            });
            $( "#editPagesTable" ).disableSelection();

                 $("#edit_pages_add_new").submit(function(e){
                    var flag = true;
                    var page_action=$('#page_action').val();
                    var page_id=$('#hidden_edit_page_id').val();
                    var page_type=$('#page_type').val();
                    var meeting_id=$('#hidden_meeting_id').val();
                    if(page_type == ""){
                        $("#page_type_error").removeClass('d-none');
                        flag=false;
                    }else{
                        $("#page_type_error").addClass('d-none');
                    }
                    var page_title=$('#page_title').val();
                    if(page_title == ""){
                        $("#page_title_error").removeClass('d-none');
                        flag=false;
                    }else{
                        $("#page_title_error").addClass('d-none');
                    }
                    var page_subheading=$('#page_subheading').val();
                    if(page_subheading == ""){
                        $("#page_subheading_error").removeClass('d-none');
                        flag=false;
                    }else{
                        $("#page_subheading_error").addClass('d-none');
                    }
                    var page_duration=$('#page_duration').val();
                    if(page_duration == ""){
                        $("#page_duration_error").removeClass('d-none');
                        flag=false;
                    }else{
                        $("#page_duration_error").addClass('d-none');
                    }

                    if(flag==true){
                        var all_data={page_action,page_id,page_type,page_title,page_subheading,page_duration, meeting_id};
                        $.ajax({
                            url:'dashboard_meeting_action.php',
                            type:'post',
                            data:all_data,
                            datatype:'json',
                           /* contentType: false,
                            processData: false,*/
                            async:false,
                            beforeSend: function () {
                                $('#save-page').attr('disabled',true);
                                $('#save-page').prev('.spinner').removeClass('d-none');
                            },
                            success:function(response){
                                var d=JSON.parse(response);
                                if(page_action=="ADD"){
                                    displayPageDataAfterMeetingAction(d);
                                    formSubmitMessage("Page Added");
                                    $('#page_action').val('ADD');
                                }else if(page_action=="EDIT"){
                                    displayPageDataAfterMeetingAction(d);
                                    formSubmitMessage("Page Updated!");
                                }	
                            },
                            complete:function () {
                                reset_form_fields('page');
                            },
                        });
                    
                    }
                    return false;
                });

                $(document).on('click','.deletePageCreateMeet',function(){
                    var page_id=$(this).attr('page_id');
                    var meeting_id=$('#hidden_meeting_id').val();
                    $.ajax({
                        url:'dashboard_meeting_action.php',
                        type:'post',
                        data:{page_id,'page_action':'DELETE',meeting_id},
                        datatype:'json',
                        async:false,
                        success:function(response){
                            displayPageDataAfterMeetingAction(JSON.parse(response));
                        },
                        complete:function(){
                            formSubmitMessage("Issue Removed!");
                        }
                    })
                });
            });
            function edit_page(page_id){
                $('#modal_page_title').html("Edit Page")
                $("#editPagesNewPageModal").modal('show');
                $.ajax({
                    url:'dashboard_meeting_action.php',
                    type:'get',
                    data:{page_id, edit_page:1},
                    datatype:'json',
                    async: false, 
                    success:function(response){
                        var result=JSON.parse(response);
                        $('#page_type').val(result.page_type);
                        $('#page_title').val(result.page_title);
                        $('#page_subheading').val(result.page_subheading);
                        $('#page_duration').val(result.duration);
                        $('#hidden_edit_page_id').val(result.page_id);
                        $('#page_action').val("EDIT");
                    }
                });
            }

            
                function displayPageDataAfterMeetingAction(res){
                var edit_pages_tr="";
                if(res.length==0){
                    edit_pages_tr="<tr><td colspan='5' class='text-danger'>No Pages For this Meeting</td></tr>";
                }else{
                    $.each(res,function(i,data){
                        edit_pages_tr+='<tr data-sort-id="'+data.page_id+'">';
                        edit_pages_tr+='<td><i class="fa fa-arrows"></i></td>';
                        edit_pages_tr+='<td class="td_w_60"><span>'+data.page_title+'</span></td>';
                        edit_pages_tr+='<td><span class="add_pages_type">'+data.page_type+'</span></td>';
                        edit_pages_tr+='<td>'+data.duration+'</td>';
                        edit_pages_tr+='<td><a href="javascript:edit_page('+data.page_id+')" >Edit</a><a href="javascript:void(0)" page_id='+data.page_id+'  class="ml-3 deletePageCreateMeet">Delete</a></td>';
                        edit_pages_tr+='</tr>';
                    });
                }
                $('#editPagesTable tbody').html(edit_pages_tr);
            }
    </script> 
</body>

</html>