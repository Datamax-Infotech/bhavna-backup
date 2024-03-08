<?php 
    session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 

    ?>
     <style>		
  @import url("//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
  </style>
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
                    $issue_order_str_qry=db_query("SELECT issue_order,show_issue_number from meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
                    $issue_order_array=array_shift($issue_order_str_qry);
                    $issue_order=$issue_order_array['issue_order'];
                    $show_issue_number=$issue_order_array['show_issue_number'];
                    $order_str=$issue_order=="" ? "ORDER BY id DESC": $issue_order;
                    switch($order_str){
                        case "ORDER BY order_no<1 ASC,order_no" : $sort_issue=1; break; 
                        case "ORDER BY created_by ASC" : $sort_issue=2; break; 
                        case "ORDER BY created_on ASC" : $sort_issue=3; break; 
                        case "ORDER BY created_on DESC" : $sort_issue=4; break; 
                        case "ORDER BY issue ASC" : $sort_issue=5;  
                    }
                    $issue_sql=db_query("SELECT issue_master.id,issue,order_no,issue_master.status,created_by FROM issue_master	where meeting_id=$meeting_id && issue_master.status=1 $order_str, id DESC", db_project_mgmt());
                    $no_data_div="d-none";
                    $present_data_div="d-flex";
                    if(tep_db_num_rows($issue_sql)==0){
                        $no_data_div="d-block";
                        $present_data_div="d-none";    
                    }
                    db_query("UPDATE meeting_live_updates set issue_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
                   ?>
                    <div class="card shadow mb-4  <?php echo $no_data_div;?>" id="no_issue_available_start_meet">
                        <div class="card-body min_height_500 d-flex justify-content-center align-items-center text-center">
                        <div>
                            <img src="assets_new_dashboard/img/no_issue.svg" class="img-fluid"/>
                            <h4 class="mt-2"><b>No Issue</b></h4>
                        </div>
                        </div>
                    </div>
                    <div class="row  <?php echo $present_data_div; ?>" id="availabe_issue_start_meet">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><b>Issues</b></h6>
                                    <div class="d-flex align-items-center justify-content-between small-font w-50">
                                    <input type="checkbox" id="show_issue_number" <?= $show_issue_number==1 ? "checked ": "";?> class="checkbox_lg"/>&nbsp;<b>Show numbers</b>
                                    <select class="form-control form-control-sm ml-3"  id="sort_issue">
                                        <option value="0" <?=$sort_issue==0 ? "selected ": ""; ?>>Sort</option>
                                        <option value="1" <?=$sort_issue==1 ? "selected ": ""; ?>>By Priority(1,2 & 3)</option>
                                        <option value="2" <?=$sort_issue==2 ? "selected ": ""; ?>>By Owner</option>
                                        <option value="3" <?=$sort_issue==3 ? "selected ": ""; ?>>By Date Created (Oldest First)</option>
                                        <option value="4" <?=$sort_issue==4 ? "selected ": ""; ?>>By Date Created (Newest First)</option>
                                        <option value="5" <?=$sort_issue==5 ? "selected ": ""; ?>>Alphabetically</option>
                                    </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body py-3 px-2">
                                    <table id="meetingIssueTable" class="meetingTODOIssue  table table_vetical_align_middle table-sm border-0 mb-0">
                                        <tbody>
                                        <?php  $rank_start_from=0;$sr_no=1;
                                            while($r = array_shift($issue_sql)){
                                            $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$r['created_by']."'",db());
                                            $empDetails_arr=array_shift($empDetails_qry);
                                            $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                                            $rank_str='<i class="fa fa-exclamation"></i>';
                                            $rank_class="";
                                            $data_rank_str="";
                                            if($r['order_no'] > 0){
                                                $rank_str=$r['order_no']; 
                                                $rank_class="ranking-done";
                                                $rank_start_from++;
                                                $data_rank_str=' data-rank='.$rank_str;
                                            }
                                            ?> 
                                            <tr id="meeting_issue_tr_<?php echo $r['id'];?>">
                                                <td class="show_sr_no td_w_5" style="display:<?= $show_issue_number==1 ? 'revert': 'none'; ?>" ><?php echo $sr_no;?></td>
                                                <td class="rank_issue_td td_w_5"><span id="issue_<?php echo $r['id'];?>" issue_id="<?php echo $r['id'];?>" class="rank <?php echo $rank_class; ?>" <?php echo $data_rank_str; ?> data-placement="bottom" title="Click to rank issue"><?php echo $rank_str;?></span></td>
                                                <td class="attendee_img_td td_w_5"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
                                                <td><a href="javascript:void(0);" title="Click any issue to show details" issue_id="<?php echo $r['id']?>" class="showIssueDetails issue_title"><?php echo $r['issue']; ?></a></td>
                                                <td class="text-right meetingMinuteNoWrap">
                                                    <span class="mx-1"><i class="fa fa-share copy_issue_to_another_meeting" issue_id="<?php echo $r['id']?>" data-placement="bottom" title="Move issue to another meeting"></i></span>
                                                    <span class="mx-1"><i class="fa fa-check-square-o add_task_to_issue" issue_id="<?php echo $r['id'];?>" data-placement="bottom" title="Create a Context-Aware To-Do"></i></span>
                                                    <button class="btn btn-light btn-sm mx-1 solve_meeting_issue" issue_id='<?php echo $r['id']; ?>'>Solve</button>
                                                </td>
                                            </tr>
                                            <?php $sr_no++; } ?>
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="rank_start_from" value="<?php echo $rank_start_from; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                            <div class="card-body" id="issue-no-details">
                                <div class="min_height_500 d-flex justify-content-center align-items-center text-center">
                                Select an issue on the left to view its details here.
                                </div>
                            </div>
                            <div class="card-body d-none" id="issue-all-details">
                                <form id="meeting_issue_save" onsubmit="return false">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="todo-text">
                                            <input type="hidden" id="meeting_issue_id_edit" name="meeting_issue_id_edit" />
                                            <input type="hidden" id="meeting_issue_edit" name="meeting_issue_action" value="meeting_issue_edit" />
                                            <p contentEditable="true" class="contentEditable"><span id="meeting_issue"></span><span class="fa fa-pencil content_edit_icon"></span></p>
                                        </div>
                                    </div>
                                    <!--<div class="col-md-6">
                                        <input type="date" data-placement="bottom" title="Start Shot Clock" class="form-control form-control-sm" id="issue_solved_on" name="solved_on"/>
                                    </div>-->
                                    <div class="col-md-12 form-group summer_note_small_size">
                                        <div id="meeting-summernote-issue" class="summernote"></div> 
                                        <p class="small-font mt-1">Date Created:<span id="meeting_issue_created_on"></span></p>
                                    </div>
                                    <div class="form-row align-items-center justify-content-between col-md-12">
                                        <div class="reports_to_div search_existing_user meeting_assigned_todo w-50">
                                            <?php // echo getAllEmployeeWithImgForMeetingForms("IssueCreatedBy","created_by");
                                                echo getMeetingEmployeeWithImgForMeetingForms("IssueCreatedBy","created_by",$meeting_id);
                                            ?>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="mx-2"><i class="fa fa-share copy_issue_to_another_meeting" id="id_copy_issue_to_another_meeting" data-placement="bottom" title="Move issue to another meeting"></i></span>
                                            <span class="mx-2"><i class="fa fa-check-square-o add_task_to_issue" data-placement="bottom" id="id_add_task_to_issue" issue_id="" title="Create a Context-Aware To-Do"></i></span>
                                            </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                    <div class="d-none spinner spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>
                                        <input type="submit" value="Save Changes" style="cursor:pointer;" class="btn btn-dark save_button btn-sm mt-2" id="save-issue-meeting-changes">
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

    <?
    require_once('meeting_start_common_top_create.php');
    ?>  
    <script>
    var undo_timeout="";
    function IssueSolvedMessage(message,issue_id){
        $('#form-submit-message').html(message);
        $('#form-submission-message').fadeIn('slow');
        undo_timeout= setTimeout(function() {
            $('#form-submission-message').fadeOut('slow');
            update_order_no_of_current_solved_issue(issue_id);   
        }, 5000); 
    }
    function update_order_no_of_current_solved_issue(issue_id){
        $.ajax({
                url:'issue_action.php',
                type:'post',
                data:{issue_id,update_issue_status_of_resoled_issue:1},
                datatype:'json',
                async:false,
                success:function(response){
                    var res=JSON.parse(response);
                    displayIssueDataAfterMeetingAction(res);
                },
            })
    }

    $(document).ready(function() { 
        $('#issue-list').addClass('active_user_page');
        const optionFormat1 = (item) => {
            if (!item.id) {
                return item.text;
            }
            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex align-items-center">';
            var img_src=item.element.getAttribute('data-kt-rich-content-icon');
            var emp_txt=item.element.getAttribute('data-kt-rich-content-emp-txt');
            template += '<span class="attendees_img bg-info" style="background-image:url('+img_src+')">'+emp_txt+'</span>';
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
        $(document).on('click', '.showIssueDetails', function(){
            $('#meetingIssueTable tr').removeClass('active_row');
            $(this).parents('tr').addClass('active_row')
            $('#issue-all-details').removeClass('d-none'); 
            $('#issue-no-details').addClass('d-none'); 
            var issue_id=$(this).attr('issue_id');
            $.ajax({
                url:'issue_action.php',
                type:'get',
                data:{issue_id, edit_issue:1},
                datatype:'json',
                async: false, 
                success:function(response){
                    var result=JSON.parse(response);
                    $('#meeting_issue').text(result.issue);
                    $('#meeting-summernote-issue').summernote('code',result.issue_details);
                    $("#IssueCreatedBy").val(result.created_by).trigger('change');
                   // $('#change_issue_status').attr('issue_id',issue_id);
                    $('#meeting_issue_created_on').html(result.created_on);
                    //result.task_status==1 ? $('#meeting_task_status').attr('checked',true): $('#meeting_task_status').attr('checked',false);
                    $('#meeting_issue_id_edit').val(issue_id);    
                    $('#id_copy_issue_to_another_meeting').attr('issue_id',issue_id);
                    $('#id_add_task_to_issue').attr('issue_id',issue_id);
                }
            });
        });
        $('#meeting_issue_save').submit(function(){
            var all_data=new FormData(this);
            var description = $('#meeting-summernote-issue').summernote('code');
            var issue=$("#meeting_issue").text();
            all_data.append('issue_details', description)
            all_data.append('issue',issue);
            all_data.append('meeting_id',"<?=$meeting_id;?>");
            all_data.append('meeting_timer_id',"<?=$meeting_timer_id;?>");
            $.ajax({
                url:'issue_action.php',
                type:'post',
                data:all_data,
                datatype:'json',
                contentType: false,
                processData: false,
                async:false,
                beforeSend: function () {
                    $('#save-issue-meeting-changes').attr('disabled',true);
                    $('#save-issue-meeting-changes').prev('.spinner').removeClass('d-none');
                },
                success:function(response){
                    var d=JSON.parse(response);
                    $('#meeting_issue_tr_'+d.issue_id+' .issue_title').html(d.issue);
                    $('#meeting_issue_tr_'+d.issue_id+' .attendees_img').css('background-image','url("'+d.emp_img+'")');
                    $('#meeting_issue_tr_'+d.issue_id+' .attendees_img').html(d.emp_txt);
                    formSubmitMessage("Isuse Updated!");	
                },
                complete:function () {
                    $('#save-issue-meeting-changes').attr('disabled',false);
                    $('#save-issue-meeting-changes').prev('.spinner').addClass('d-none');
                },
            });
        });

        $(document).on('click','.solve_meeting_issue',function(){
            var issue_id=$(this).attr('issue_id');
            if(issue_id==$('#meeting_issue_id_edit').val()){
                $('#issue-no-details').removeClass('d-none');
                $('#issue-all-details').addClass('d-none');
            }
            
            $(this).attr('disabled',true);
            $.ajax({
                url:'issue_action.php',
                type:'post',
                data:{issue_id,issue_status_update:'solved',meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",},
                datatype:'json',
                async:false,
                success:function(response){
                    var res=JSON.parse(response);
                    displayIssueDataAfterMeetingAction(res);
                },
            })
            $('#issue-all-details').addClass('d-none'); 
            $('#issue-no-details').removeClass('d-none');
            $('#solve_meeting_issue').attr('disabled',false);
            IssueSolvedMessage("Issue Marked As resolved,<a class='text-light' href='javascript:void(0)' id='undo_issue' issue_id='"+issue_id+"'><b>Undo</b></a>",issue_id);    
        });

        
    $(document).on('click','#undo_issue',function(){
        clearTimeout(undo_timeout);
        var issue_id=$(this).attr('issue_id');
        $.ajax({
            url:'issue_action.php',
            type:'post',
            data:{issue_id,issue_status_update:'unsolved',meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>"},
            datatype:'json',
            async:false,
            success:function(response){
                var res=JSON.parse(response);
                displayIssueDataAfterMeetingAction(res);
                formSubmitMessage("Issue Marked As Unsolved");
            },
        })
      })
      
       
       $(document).on('click','.rank',function(){
        var already_ranking_done=$('.ranking-done');
        var rank_array=[];
        $.each(already_ranking_done,function(k,v){
                rank_array.push({'issue_id':$(v).attr('issue_id'),'rank_val':$(v).attr('data-rank')});
        });
        var rank_counter=$('#rank_start_from').val();
            pause_live_data=true;
            var data_rank_attr = $(this).attr('data-rank');
            if(data_rank_attr !== undefined && data_rank_attr !== false){
                var current_issue_id=$(this).attr('issue_id');
                $(this).removeAttr('data-rank');
                $(this).removeClass('ranking-done');
                $(this).html('<i class="fa fa-exclamation"></i>');
                rank_array=rank_array.filter(d=>d.rank_val!=data_rank_attr);
                rank_counter=0;
                new_rank_array=[];
                rank_array.sort((a, b) => {
                    return a.rank_val - b.rank_val;
                });
                $.each(rank_array,function(k,v){
                    rank_counter++;
                    $('#issue_'+v.issue_id).attr('data-rank',rank_counter);
                    $('#issue_'+v.issue_id).html(rank_counter);
                    new_rank_array.push({'issue_id':v.issue_id,'rank_val':rank_counter});
                });
                new_rank_array.push({'issue_id':current_issue_id,rank_val:0});
                rank_array=new_rank_array.sort((a, b) => { return a.rank_val - b.rank_val; });
            }else{
                rank_counter++;
                $(this).attr('data-rank',rank_counter);
                $(this).addClass('ranking-done');
                $(this).html(rank_counter);
                $('#rank_start_from').val(rank_counter);
                rank_array.push({'issue_id':$(this).attr('issue_id'),'rank_val':rank_counter});
            }
            $.ajax({
            url:'issue_action.php',
            type:'post',
            data:{rank_array,issue_rank_update:1,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",},
            datatype:'json',
            async:false,
            success:function(response){
                var res=JSON.parse(response);
                displayIssueDataAfterMeetingAction(res);
               
                formSubmitMessage("Ranking Done");
            },
            complete:function(){
                pause_live_data=false;
            }
            })
        });
        $('#show_issue_number').click(function(){
            pause_live_data=true;
            var show_number=0;
            if ($('#show_issue_number').is(":checked")) {
                show_number=1;
                $('.show_sr_no').css('display','revert');
            }else{
                show_number=0;
                $('.show_sr_no').css('display','none');
            }
            $.ajax({
                url:'issue_action.php',
                type:'post',
                data:{show_number,'show_issue_number':1,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",},
                datatype:'json',
                async:false,
                success:function(response){
                    formSubmitMessage("Display Issue Number");
                    pause_live_data=false;
                },
            });
          
        });

        $(document).on('change','#sort_issue',function(){
        var sort_order=$('#sort_issue').val();
        $.ajax({
			url:'issue_action.php',
			type:'get',
			data:{sort_order,sort_meeting_issue:1,meeting_id:"<?=$meeting_id;?>",meeting_timer_id:"<?=$meeting_timer_id;?>",},
			datatype:'json',
			async: false, 
			success:function(response){
                var res=JSON.parse(response);
                displayIssueDataAfterMeetingAction(res);
                formSubmitMessage("Issue Sorted!");
			}
		});
    })

    });
   
    
    </script>
</body>

</html>