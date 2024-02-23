<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php");

	$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
	$emp_level = array_shift($select_level)['level'];

	$meeting_filter = "";
	if($emp_level!=2){
		$meeting_filter =" and attendee_id='".$_COOKIE['b2b_id']."'";
	}
	$meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";	
?>
	<div id="wrapper">
	<? 
		//$sidebar_full="yes";
		require_once("inc/sidebar_new_dashboard.php"); 
	?>
	<div id="content-wrapper" class="d-flex flex-column">
	    <div id="content">
		
		<!-- Begin Page Content -->
		<? if($meeting_id==""){?>
			<div class="col-md-12 alert alert-danger">
				<p class="mb-0"><a href="dashboard_meetings.php"><b>Click here</b></a> to select the meeting first</p>
			</div>
		<? } else{
			$sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
			$meeting_name= array_shift($sql_main)['meeting_name'];
		?>
		<div class="container-fluid">
			<!-- Page Heading -->
			<div class="d-sm-flex align-items-center justify-content-between my-4">
				<h1 class="h3 mb-0">Workspace : <?= $meeting_name; ?></h1>
				<input type="hidden" id="hidden_meeting_id" value="<?= $meeting_id; ?>"/>
			</div>
			<!-- Content Row -->
			<div class="row">   	
                <div class="col-md-12" id="scoreboard-section">
                    <div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">Scorecard <small><i><?= $meeting_name;?></i></small></h6>
							<div class="dropdown no-arrow">
								<!-- <a class="dropdown-toggle mx-1" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="#">View All</a>
									<a class="dropdown-item" href="#">Delete Tile</a>
								</div> -->
                                <a type="button" class="mx-1" data-toggle="modal" data-todo='{"EditingFrom":"meetingWorkspaceMatrix"}' data-target="#scorecardAddMatrixModalPopop" data-whatever="new_measurement">
                                    <i class="fa fa-plus"></i>
                                </a>
							</div>
						</div>
				<?
                    $emp_b2b_id = $_COOKIE['b2b_id'];
                    $getWeekLimit = 16;
                    $i = 0;
                    while ($i <= $getWeekLimit) {
                        $previous_week = strtotime("-$i week +1 day");
                        $start_week = strtotime("last sunday midnight",$previous_week);
                        $end_week = strtotime("next saturday",$start_week);
                        
                        $start_week = date("M d",$start_week);
                        $end_week = date("M d",$end_week);

                        $scorecardweeks[] = $start_week." ".$end_week;
                        $scorecardweeks_for_thead[] = $start_week."<br>".$end_week;
                        $i++;
                    }
                        
                    ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="meetingMetricsTable" class="table table-sm text-center meetingMetricsTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="bg-white">Who</th>
                                            <th class="bg-white">Measurable</th>
                                            <th class="bg-white">Goal</th>
                                            <th class="bg-white"></th>
                                            <?php
                                            if(isset($scorecardweeks_for_thead)){
                                                foreach($scorecardweeks_for_thead as $week){
                                                    ?>
                                                <th class="f-small"><?=$week?></th>
                                                <?
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        $scorecard_data_sql = "SELECT scorecard.id,scorecard.b2b_id,scorecard.name,scorecard.units,scorecard.goal,scorecard.goal_matric FROM scorecard WHERE (scorecard.attach_meeting like '%-".$meeting_id."-%' OR scorecard.attach_meeting like '%-".$meeting_id."' OR scorecard.attach_meeting like '".$meeting_id."-%' OR scorecard.attach_meeting = ".$meeting_id.") AND (scorecard.archived = false) ORDER BY scorecard.id DESC";
                                        $scorecard_data_query = db_query($scorecard_data_sql,db_project_mgmt());
                                        while($scorecard_data = array_shift($scorecard_data_query)){

                                        $scorecard_weeks_id = $scorecard_data['id'];
                                        $scorecard_createdByID = $scorecard_data['b2b_id'];
										$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$scorecard_data['b2b_id']."'",db());
										$empDetails_arr=array_shift($empDetails_qry);
										$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                                       	$scorecardUserImage = $empDetails['emp_img'];
                                        $scorecardUserText = $empDetails['emp_txt'];
                                        ?>
                                            <tr data-sort-id="<?=new_dash_encrypt($scorecard_weeks_id)?>">
                                                <td class="matrics_attandees_img"><span class="attendees_img" style="background-image:url('<?=$scorecardUserImage?>')"><?=$scorecardUserText?></span></td>
                                                <td class="td-border-bottom matrics_mesurable_name">
                                                    <a id="measurableModal" type="button" class="" data-toggle="modal"
                                                        data-target="#scorecardAddMatrixModalPopop"
                                                        data-whatever="<?=new_dash_encrypt($scorecard_weeks_id)?>" data-todo='{"EditingFrom":"meetingWorkspaceMatrix"}'><?=$scorecard_data['name']?></a>
                                                </td>
                                                <td class="td-border-bottom matrics_mesurable_goal">
                                                    <?=$scorecard_data['goal'] == '==' ? '=' : $scorecard_data['goal']?>
                                                    <?=$scorecard_data['units'].$scorecard_data['goal_matric']?>
                                                </td>
                                                <td><i class="fa fa-line-chart"></i></td>
                                                <?
                                                if(isset($scorecardweeks_for_thead)){

                                                    $scorecard_goal_matric = (int)$scorecard_data['old_goal_matric'] === 0 ? $scorecard_data['goal_matric'] : $scorecard_data['old_goal_matric'];
                                                    $measurable_goal_matircs_data = $scorecard_data['goal_matric'];
                                                    $sign = $scorecard_data['goal'];

                                                    foreach($scorecardweeks_for_thead as $week){
                                                        $convertedWeek = str_replace('<br>', " to " , $week);
                                                        $inner_scorecard_data_sql = "SELECT * FROM `meeting_scorecard_week_data` where scorecard_id = '".$scorecard_weeks_id."' AND `scorecard_created_by` = '".$scorecard_createdByID."' AND `weeks` = '".$convertedWeek."'";
                                                        $inner_scorecard_data_query = db_query($inner_scorecard_data_sql,db_project_mgmt());
                                                        if(!empty($inner_scorecard_data_query)){
                                                            while($inner_scorecard_data = array_shift($inner_scorecard_data_query)){
                                                                $meeting_scorecard_week_id = $inner_scorecard_data['id'];
                                                                $scorecard_id = $inner_scorecard_data['scorecard_id'];
                                                                $scorecard_created_by = $inner_scorecard_data['scorecard_created_by'];
                                                                $db_value = $inner_scorecard_data['value'];
                                                                $db_weeks = $inner_scorecard_data['weeks'];

                                                                if((int)$scorecard_id == (int)$scorecard_weeks_id && (int)$scorecard_created_by == (int)$scorecard_createdByID && $db_weeks == $convertedWeek){
                                                                    $putValue = true;
                                                                    if(isset($measurable_goal_matircs_data) && $measurable_goal_matircs_data != ''){
                                                                        if($sign == '<=>'){
                                                                            $between_num = $measurable_goal_matircs_data;
                                                                            $between_eploded_value = explode('-',$between_num);
                                                                            $between_min = $between_eploded_value[0];
                                                                            $between_max = $between_eploded_value[1];
                                                                            if($between_min <= $db_value && $db_value <= $between_max){
                                                                                $box_color = 'td-success';
                                                                            }else{
                                                                                $box_color = 'td-danger';
                                                                            }
                                                                        }else{
                                                                            $condition = "$db_value $sign $measurable_goal_matircs_data";
                                                                            if (eval("return ($condition);")) {
                                                                                $box_color = 'td-success';
                                                                            } else {
                                                                                $box_color = 'td-danger';
                                                                            }
                                                                        }                                                                    
                                                                    }
                                                                    if($scorecard_data['units'] === '%'){
                                                                        $tile_value_and_units = $scorecard_data['units'].$db_value;
                                                                    }else{
                                                                        $tile_value_and_units = $scorecard_data['units'].$db_value;
                                                                    }
                                                                    $inputValue = isset($db_value) ? $tile_value_and_units : '';
                                                                    $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','".$meeting_scorecard_week_id."','Update','meetingWorkspaceMatrix')";
                                                                }
                                                                ?>
                                                                    <td class="measurable_val_td <?= $putValue == true ? $box_color : '' ?>">
                                                                        <input type="text" class="edit_content text-center" onblur="<?=$onblur?>" onkeyup="matrixvalidateInput(this)" value="<?= $putValue == true ? $inputValue : '' ?>" />
                                                                        <input type="hidden" class="tdweek" value="<?= str_replace('<br>', " to " , $week); ?>">
                                                                    </td>
                                                                <?
                                                        }
                                                        }else{
                                                            $putValue = false; 
                                                            $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','0','Insert','meetingWorkspaceMatrix')";
                                                            ?>
                                                                <td class="measurable_val_td">
                                                                    <input type="text" class="edit_content text-center" onblur="<?=$onblur?>" onkeyup="matrixvalidateInput(this)" value="" />
                                                                    <input type="hidden" class="tdweek" value="<?= str_replace('<br>', " to " , $week); ?>">
                                                                </td>
                                                            <?
                                                        }
                                                    }
                                                }
                                                ?>
                                                <input type="hidden" class="trID" value="<?= new_dash_encrypt($scorecard_data['id']) ?>">
                                            </tr>
                                        <?
                                        }
                                        ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    
                    </div>
                </div>
        
				<div class="col-lg-6  projects" id="project-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">Projects <small><i><?= $meeting_name;?></i></small></h6>
							<div class="dropdown no-arrow">
								<span class="show-mine dropdown-toggle mx-1">
									<input type="checkbox" class="form-control-check only-mine-project" id="show_mine_project" name="only_mine" value="yes"/> Show Only Mine
								</span>
								<a class="dropdown-toggle mx-2" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu mx-2 dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="#">View All</a>
									<a class="dropdown-item disabled" href="#" style="cursor:no-drop">Delete Tile</a>
								</div>
								<a href="javascript:void(0)" id="new_project_tile_workspace" class="mx-1"><i class="fa fa-l fa-plus"></i></a>
							</div>
						</div>
						<!-- Card Body -->
						<div class="card-body fixed-height-card" >
							<table id="project_table" class="vertical-middle table table-sm border-0 <? echo "project_meeting".$main_row['id'];?>" width="100%" cellspacing="0">
								<tbody>
									<?php 
										$sql1 = db_query("SELECT project_id,project_name, project_description,project_status_id, project_deadline,project_owner FROM project_master where find_in_set($meeting_id,meeting_ids) AND archive_status=0 ORDER BY project_id DESC ", db_project_mgmt());
										if(tep_db_num_rows($sql1)>0){
										while($row = array_shift($sql1)){
											$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$row['project_owner']."'",db());
											$empDetails_arr=array_shift($empDetails_qry);
											$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
									?>
									<tr class="project_tr" id="project_tr_<?php echo $row['project_id']; ?>">
										<td class="attendee_img_td td_w_5"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
                                               
										<td class="td_title"><a href="javascript:edit_project(<?php echo $row['project_id'];?>,'editFromWorkspace')"><?php echo $row['project_name']; ?></a></td>
										<!--<td class="td_project_description"><?php echo $row['project_description']; ?></td> -->
										<td class="td_status">
											<?php $status_data=get_status_date_color_info($row['project_status_id'],$row['project_deadline']); ?>
											<span project_id="<?php echo $row['project_id'];?>" status_id="<?php echo $row['project_status_id'];?>" class="status_view status_css <?php echo $status_data['status_class'];?>">
												<?php echo $status_data['status_icon']." ".$status_data['status_name']." "; ?>
											</span>
										</td>
										<td id="project_td_deadline_<?php echo $row['project_id']; ?>" class="td_date <?php echo $status_data['deadline_class']; ?>"><?php echo  ($row['project_deadline']=='0000-00-00') ? " - " : date("m/d/Y", strtotime($row['project_deadline'])); ?> </td>
									</tr>
									<?php }}else{?>
										<div class="text-center no_issue_div">
											<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>
											<p>No Current Projects</p>
										</div>
									<?}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-6 tasks" id="task-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">Tasks <small><i><?= $meeting_name;?></i></small></h6>
							<div class="dropdown no-arrow">
								<span class="show-mine dropdown-toggle mx-1">
									<input type="checkbox" class="form-control-check only-mine-task" id="show_mine_task" name="only_mine" value="yes"/> Show Only Mine
								</span>
								<a class="dropdown-toggle mx-2" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu mx-2 dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="#">View All</a>
									<a class="dropdown-item disabled" href="#" style="cursor:no-drop">Delete Tile</a>
								</div>
								<a href="javascript:void(0)" id="new_task_tile_workspace" class="mx-1"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						<div class="card-body fixed-height-card"  >
							<div class="table-responsive">
							<table id="task_table" class="table table-sm border-0 <? echo "task_meeting".$main_row['id'];?>" width="100%" cellspacing="0">
								<tbody>
									<?php 
										$sql1 = db_query("SELECT task_master.id,task_title, task_duedate, task_status,task_entered_by, task_assignto FROM task_master where task_meeting=$meeting_id  and archive_status=0 ORDER BY id DESC ", db_project_mgmt());
										if(tep_db_num_rows($sql1)>0){
											while($row = array_shift($sql1)){
												$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$row['task_assignto']."'",db());
												$empDetails_arr=array_shift($empDetails_qry);
												$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);  
												?>
												<tr class="task_tr" id="task_tr_<?php echo $row['id']; ?>">
												<td class="attendee_img_td td_w_5"><input class="change_task_status" task_id="<?php echo $row['id'];?>" type="checkbox" <?php echo $row['task_status']==1 ? 'checked' : ''; ?>/></td>
												<td class=" td_w_5"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $r['name']; ?></span></td>
												<td class="td_task_title"><a class="<?php echo $row['task_status']==1 ? "task-completed" :"";?>" href="javascript:edit_task(<?php echo $row['id'];?>,'editFromWorkspace')"><?php echo $row['task_title']; ?></a></td>
												<?php $date_class=get_status_date_color_info_task($row['task_duedate']);?>
												<td class="<?php echo $date_class; ?> td_task_date"><?php echo date("m/d/Y", strtotime($row['task_duedate'])); ?></td>
												</tr>
										<?php } 
										}else{ ?>
											<div class="text-center no_issue_div align-items-center">
												<img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img"/>
												<p>No Current Task</p>
											</div>
										<? } ?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 issueList" id="issue-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading"> Issue: <?= $meeting_name;?></h6>
							<div class="dropdown no-arrow">
								<span class="show-mine dropdown-toggle mx-1">
									<input type="checkbox" class="form-control-check only-mine-issue" id="show_mine_issue" name="only_mine" value="yes"/> Show Only Mine
								</span>
								<a class="dropdown-toggle mx-2" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu mx-2 dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="#">View All</a>
									<a class="dropdown-item disabled" href="#" style="cursor:no-drop">Delete Tile</a>
								</div>
								<a id="new_issue_tile_workspace" class="mx-1"><i class="fa fa-l fa-plus"></i></a>
							</div>
						</div>
						<!-- Card Body -->
						<div class="card-body fixed-height-card">
							<div class="table-responsive">
								<table class="table table-sm border-0"  id="meeting_issue_tbl" >
									<tbody>
										<?php 
											$sql1 = db_query("SELECT issue_master.id,issue,issue_master.created_by from issue_master 
											where meeting_id=$meeting_id and issue_master.status=1 ORDER BY id DESC", db_project_mgmt());
											if(tep_db_num_rows($sql1)>0){
											while($row = array_shift($sql1)){
												$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$row['created_by']."'",db());
												$empDetails_arr=array_shift($empDetails_qry);
												$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
										?>
										<tr id="issue_tr_<? echo $row['id'];?>">
											<td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
											<!--<td><img class="img-profile rounded-circle" src="<? echo $emp_img; ?>" height="30" width="30"></td>-->
											<td class="td_issue_title"><a href="javascript:edit_issue(<?php echo $row['id'];?>,'editFromWorkspace')"><?php echo $row['issue']; ?></a></td>
										</tr>
										<? } } else{ ?>
											<div class="align-self-center text-center no_issue_div">
												<img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img"/>
												<p>No Current Issues</p>
											</div>
										<? } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		<?php require_once("inc/footer_new_dashboard.php");?> 
		</div>
		<? } ?>
		<!-- /.container-fluid -->
	</div>
	
</div>

<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>  
<script>
    $(document).ready(function() { 
		$('#meetingMetricsTable').dataTable({
			"searching": false,
			"ordering": false,
			info: false,
			paging: false,
			fixedColumns: {
				left: 4,
			},
			scrollCollapse: true,
			rowReorder: {
				update: false
			},
			columnDefs: [{
				orderable: false,
				targets: '_all'
			}],
			select: true
		});	
    });	
	</script>  

<style>
    #meetingMetricsTable th,#meetingMetricsTable td { white-space: nowrap; }
    #meetingMetricsTable div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }</style>
</body>

</html>
            
