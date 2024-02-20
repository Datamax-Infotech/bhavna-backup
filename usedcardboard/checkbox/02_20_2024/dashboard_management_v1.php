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

	$meeting_filter = " and attendee_id='".$_COOKIE['b2b_id']."'";
	/*if($emp_level!=2){
		$meeting_filter =" and attendee_id='".$_COOKIE['b2b_id']."'";
	}
	*/
	
	
?>
	<div id="wrapper">
	<? 
		$sidebar_full="yes";
		require_once("inc/sidebar_new_dashboard.php"); 
	?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
		
		<!-- Begin Page Content -->
		<div class="container-fluid">
			<!-- Page Heading -->
			<div class="d-sm-flex align-items-center justify-content-between my-4">
				<h1 class="h3 mb-0">CheckBOX Dashboard</h1>
			</div>
			<!-- Content Row -->
			<div class="row">   
				<div class="col-lg-12 scoreboard" id="scoreboard-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">Scorecard <small><i>(Measureables you owe to meetings)</i></small></h6>
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
                                <a type="button" class="mx-1" data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="new_measurement">
                                    <i class="fa fa-plus"></i>
                                </a>
							</div>
						</div>
						<!-- Card Body -->
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
                                    <thead class="">
                                        <tr>
                                            <th class="bg-white td-border-bottom">Measurable</th>
                                            <th class="bg-white td-border-bottom scorestart">Goal</th>
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
                                        $scorecard_data_sql = "SELECT * from `scorecard` WHERE `b2b_id` = ".$emp_b2b_id." AND `archived` = false ORDER BY id DESC";
                                        $scorecard_data_query = db_query($scorecard_data_sql,db());
                                        while($scorecard_data = array_shift($scorecard_data_query)){
                                            $scorecard_weeks_id = $scorecard_data['id'];
                                            $scorecard_createdByID = $scorecard_data['b2b_id'];
                                        ?>
                                        <tr>
                                            <td class="td-border-bottom text-left">
                                                <a id="measurableModal" type="button" class="" data-toggle="modal"
                                                    data-target="#scorecardAddMatrixModalPopop"
                                                    data-whatever="<?=new_dash_encrypt($scorecard_data['id'])?>" data-todo='{"EditingFrom":"MeasurablePage"}'><?=$scorecard_data['name']?></a>
                                            </td>
                                            <td class="td-border-bottom ">
                                                <?=$scorecard_data['goal'] == '==' ? '=' : $scorecard_data['goal']?>
                                                <?=$scorecard_data['units'] . $scorecard_data['goal_matric']?>
                                            </td>
                                            <?
                                            if(isset($scorecardweeks_for_thead)){

												$scorecard_goal_matric = (int)$scorecard_data['old_goal_matric'] === 0 ? $scorecard_data['goal_matric'] : $scorecard_data['old_goal_matric'];
                                                $measurable_goal_matircs_data = $scorecard_data['goal_matric'];
                                                $sign = $scorecard_data['goal'];

                                                foreach($scorecardweeks_for_thead as $week){
                                                    $convertedWeek = str_replace('<br>', " to " , $week);
                                                    $inner_scorecard_data_sql = "SELECT * FROM `meeting_scorecard_week_data` where scorecard_id = '".$scorecard_weeks_id."' AND `scorecard_created_by` = '".$scorecard_createdByID."' AND `weeks` = '".$convertedWeek."'";
                                                    $inner_scorecard_data_query = db_query($inner_scorecard_data_sql,db());
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
                                                                    $tile_value_and_units = $db_value.$scorecard_data['units'];
                                                                }else{
                                                                    $tile_value_and_units = $scorecard_data['units'].$db_value;
                                                                }
                                                                $inputValue = isset($db_value) ? $tile_value_and_units : '';
                                                                $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','".$meeting_scorecard_week_id."','Update')";
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
                                                        $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','0','Insert')";
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
							<h6 class="collapse-heading">Projects <small><i>(including rocks)</i></small></h6>
							<div class="dropdown no-arrow">
								<a class="dropdown-toggle mx-1" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="manage_project.php" target="_blank">View All</a>
									<a class="dropdown-item" href="javascript:delete_tile(project)">Delete Tile</a>
								</div>
								 <a href="javascript:void(0)" id="new_project_tile" class="mx-1"><i class="fa fa-l fa-plus"></i></a>
							</div>
						</div>
						<!-- Card Body -->
						<div class="card-body fixed-height-card" id="project_table">
							<?php
								$check_for_no_data=true;
								//$sql_main = db_query("SELECT id, meeting_name FROM meeting_master where status = 1 union SELECT 0, 'No Meeting selected' ORDER BY id DESC", db());
								
								$sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
								where mm.status = 1 $meeting_filter GROUP By ma.meeting_id 
								union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name ", db());
								
								while($main_row = array_shift($sql_main)){
									$meeting_id=$main_row['id'];
									$count_sql = db_query("SELECT project_id FROM project_master where find_in_set($meeting_id,meeting_ids) AND project_owner = '".$_COOKIE["b2b_id"]."' AND archive_status=0 ORDER BY project_id DESC", db());
									$count = tep_db_num_rows($count_sql);
									if($count>0){
										$check_for_no_data=false;
										?>
										<table class="vertical-middle table table-sm border-0 <? echo "project_meeting".$main_row['id'];?>" width="100%" cellspacing="0">
											<tbody>
												<tr>
													<td colspan="3">
														<div class="card-subheading"><span><? echo $main_row["meeting_name"];?></span></div>
													</td>
												</tr>
												<?php 
													$sql1 = db_query("SELECT project_id,project_name, project_description,project_status_id, project_deadline FROM project_master where find_in_set($meeting_id,meeting_ids) AND project_owner = '".$_COOKIE["b2b_id"]."' AND archive_status=0 ORDER BY project_id DESC LIMIT 10", db());
													while($r = array_shift($sql1)){
												?>
												<tr class="project_tr" id="project_tr_<?php echo $r['project_id']; ?>">
													<td class="td_title"><a href="javascript:edit_project(<?php echo $r['project_id'];?>)"><?php echo $r['project_name']; ?></a></td>
													<!--<td class="td_project_description"><?php echo $r['project_description']; ?></td> -->
													<td class="td_status">
														<?php $status_data=get_status_date_color_info($r['project_status_id'],$r['project_deadline']); ?>
														<span project_id="<?php echo $r['project_id'];?>" status_id="<?php echo $r['project_status_id'];?>" class="status_view status_css <?php echo $status_data['status_class'];?>">
															<?php echo $status_data['status_icon']." ".$status_data['status_name']." "; ?>
														</span>
													</td>
													<td id="project_td_deadline_<?php echo $r['project_id']; ?>" class="td_date <?php echo $status_data['deadline_class']; ?>"><?php echo  ($r['project_deadline']=='0000-00-00') ? " - " : date("m/d/Y", strtotime($r['project_deadline'])); ?> </td>
												</tr>
												<?php } if($count>10){?>
												<tr class="load_more_project_data">
													<td colspan="3" class="text-right">
													<div class="d-none spinner spinner-border text-primary" role="status">
													  <span class="sr-only">Loading...</span>
													</div>
													<button total_data="<?php echo $count;?>" loaded_data="10" project_meeting_id="<?php echo $main_row["id"]; ?>" class="btn btn-light btn-sm show-more-project">Load More</button>
													</td>
												</tr>
												<?php }  ?>
											</tbody>
										</table>
									<? }
									
								} ?>
							<?php if($check_for_no_data==true){?>
								<div class="text-center no_issue_div">
									<img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img"/>
									<p>No Current Projects</p>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="col-lg-6  tasks" id="task-section">
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading">Tasks <small><i>(Things you need to remember to do)</i></small></h6>
							<div class="dropdown no-arrow">
								<a class="dropdown-toggle mx-1" href="#" role="button" id="dropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-ellipsis-h "></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
									aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" target="_blank" href="manage_task.php">View All</a>
									<a class="dropdown-item disabled" href="#" style="cursor:no-drop">Delete Tile</a>
								</div>
								 <a href="javascript:void()" id="new_task_tile" class="mx-1"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						<div class="card-body fixed-height-card"  id="task_table">
							<div class="table-responsive">
							<?php
								$check_for_no_data=true;
								
								//$sql_main = db_query("SELECT id, meeting_name FROM meeting_master where status = 1 ORDER BY id DESC", db());
								
								$sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
								where mm.status = 1 $meeting_filter GROUP By ma.meeting_id
								union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name",db());
								while($main_row = array_shift($sql_main)){
									$meeting_id = $main_row['id'];
									//echo "SELECT id FROM task_master where task_meeting=$meeting_id AND task_assignto = '" . $_COOKIE["b2b_id"]. "' and archive_status=0 ORDER BY id DESC";
									//$count_sql = db_query("SELECT id FROM task_master where task_meeting=$meeting_id AND task_assignto = '" . $_COOKIE["b2b_id"]. "' and archive_status=0 and task_master.task_status = 0 ORDER BY id DESC", db());
									$count_sql = db_query("SELECT id FROM task_master where task_meeting=$meeting_id AND task_assignto = '" . $_COOKIE["b2b_id"]. "' and archive_status=0 ORDER BY id DESC", db());
									$count = tep_db_num_rows($count_sql);
									if($count>0){ $check_for_no_data=false;?>
										<table class="table table-sm border-0 <? echo "task_meeting".$main_row['id'];?>" width="100%" cellspacing="0">
											<tbody>
												<tr>
													<td colspan="3">
														<div class="card-subheading"><span><? echo $main_row["meeting_name"];?></span></div>
													</td>
												</tr>
												<?php 
													$sql1 = db_query("SELECT id,task_title, task_duedate, task_status FROM task_master where task_meeting=$meeting_id AND task_assignto = '" . $_COOKIE["b2b_id"]. "' 
													and archive_status=0 ORDER BY id DESC LIMIT 10", db());
													while($r = array_shift($sql1)){
														?>
														<tr class="task_tr" id="task_tr_<?php echo $r['id']; ?>">
														<td class="td_task_status"><input class="change_task_status" task_id="<?php echo $r['id'];?>" type="checkbox" <?php echo $r['task_status']==1 ? 'checked' : ''; ?>/></td>
														<td class="td_task_title"><a class="<?php echo $r['task_status']==1 ? "task-completed" :"";?>" href="javascript:edit_task(<?php echo $r['id'];?>)"><?php echo $r['task_title']; ?></a></td>
														<?php $date_class=get_status_date_color_info_task($r['task_duedate']);?>
														<td class="<?php echo $date_class; ?> td_task_date"><?php echo date("m/d/Y", strtotime($r['task_duedate'])); ?></td>
														</tr>
												<?php } 
												
												if ($count>10) { 
													?>
													<tr class="load_more_task_data">
														<td colspan="3" class="text-right">
														<div class="d-none spinner spinner-border text-primary" role="status">
														  <span class="sr-only">Loading...</span>
														</div>
														<button total_data="<?php echo $count;?>" loaded_data="10" task_meeting_id="<?php echo $main_row["id"]; ?>" class="btn btn-light btn-sm show-more-task">Load More</button>
														</td>
													</tr>
												<?php }  ?>
											</tbody>
										</table>
									
									<? }
								} ?>
							</div>
							<?php if($check_for_no_data==true){?>
								<div class="text-center no_issue_div align-items-center">
									<img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img"/>
									<p>No Current Task</p>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php 
				
				//$sql_main = db_query("SELECT id, meeting_name FROM meeting_master where status = 1 and id!=0 ORDER BY id ASC", db());
               $sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id=ma.meeting_id 
				where mm.status = 1 $meeting_filter and mm.id != 0 GROUP By ma.meeting_id ORDER BY meeting_name ASC, attendee_id< '".$_COOKIE['b2b_id']."' ASC,attendee_id",db());
				$check_first=1;
				 while($main_row = array_shift($sql_main)){
					$meeting_id=$main_row['id'];
					$count_sql=db_query("SELECT id FROM issue_master where meeting_id=$meeting_id and status=1 ORDER BY id DESC", db());
					$count=tep_db_num_rows($count_sql);				
				?>
				<div class="col-lg-6 issueList" <?=$check_first==1 ? "id='issue-section'" : "";?>>
					<div class="card shadow mb-4">
						<!-- Card Header - Dropdown -->
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="collapse-heading"> Issue: <?php echo $main_row['meeting_name']; ?></h6>
							<div class="dropdown no-arrow">
								<span class="show-mine dropdown-toggle mx-1">
									<input type="checkbox" class="form-control-check only-mine-check" id="show_only_<?php echo $meeting_id; ?>" meeting_id="<? echo $meeting_id;?>" name="only_mine" value="yes"/> Show Only Mine
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
								 <a id="new_issue_tile" href="javascript:add_issue(<? echo $meeting_id;?>)" class="mx-1"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						<!-- Card Body -->
						<div class="card-body fixed-height-card" id="meeting_issue_<? echo $meeting_id; ?>">
							<?php if($count>0){?>
							<div class="table-responsive">
								<table class="table table-sm border-0" >
									<tbody>
										<?php 
											//echo "SELECT issue_master.id,issue,issue_master.created_by,Headshot, name,initials from issue_master JOIN loop_employees ON issue_master.created_by=loop_employees.b2b_id where meeting_id=$meeting_id and issue_master.status=1 ORDER BY id DESC LIMIT 10";
											$sql1 = db_query("SELECT issue_master.id,issue,issue_master.created_by,Headshot, name,initials from issue_master JOIN loop_employees ON issue_master.created_by=loop_employees.b2b_id 
											where meeting_id=$meeting_id and issue_master.status=1 ORDER BY id DESC LIMIT 10", db());
											while($r = array_shift($sql1)){
												$empDetails=getOwerHeadshotForMeeting($r['Headshot'],$r['initials']); 
										?>
										<tr id="issue_tr_<? echo $r['id'];?>">
											<td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
											<!--<td><img class="img-profile rounded-circle" src="<? echo $emp_img; ?>" height="30" width="30"></td>-->
											<td class="td_issue_title"><a href="javascript:edit_issue(<?php echo $r['id'];?>)"><?php echo $r['issue']; ?></a></td>
										</tr>
										<? } if($count>10){?>
												<tr class="load_more_issue_data">
													<td colspan="2" class="text-right">
													<div class="d-none spinner spinner-border text-primary" role="status">
													  <span class="sr-only">Loading...</span>
													</div>
													<button total_data="<?php echo $count;?>" loaded_data="10" issue_meeting_id="<?php echo $meeting_id; ?>" class="btn btn-light btn-sm show-more-issue">Load More</button>
													</td>
												</tr>
											<?php }  ?>
									</tbody>
								</table>
							</div>
							<?php }else{ ?>
								<div class="align-self-center text-center no_issue_div">
									<img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img"/>
									<p>No Current Issues</p>
								</div>
							<? } ?>
						</div>
					</div>
				</div>
				<?php $check_first++;
			 } ?>
			</div>
			
		<?php require_once("inc/footer_new_dashboard.php");?> 
		</div>
		<!-- /.container-fluid -->
	</div>
	
</div>

<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>  
<script>
    $(document).ready(function() { 
    // console.log($('#meetingMetricsTable tbody tr.Editing td').length);
// $('#meetingMetricsTable tbody tr:first-Child td.measurable_val_td.td-danger,#meetingMetricsTable tbody tr:first-Child td.measurable_val_td.td-success').each(function(index,value){
//     var tileValue = $(this).find('.edit_content').val();
//     if(tileValue != ''){
//         console.log(parseInt(tileValue));
//         var parsevalue = parseInt(tileValue);
//         $(this).removeClass('td-danger');
//         $(this).removeClass('td-success');
//         var condition = `${parsevalue} ${goal} ${newGoalMatrics}`;
//         if (eval(condition)) {
//             $(this).addClass('td-success');
//         } else {
//             $(this).addClass('td-danger');
//         }
//     }
// });
// $('#meetingMetricsTable tbody tr.Editing td.td-danger').each(function(index,value){
//     console.log(index);
//     console.log(value);
// });
		$('#meetingMetricsTable').dataTable({
			"searching": false,
			"ordering": false,
			info: false,
			paging: false,
			fixedColumns: {
				left: 2,
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

	check_meeting_started();
	setInterval(check_meeting_started, 10000);
	</script>  

<style>
    #meetingMetricsTable th,#meetingMetricsTable td { white-space: nowrap; }
    #meetingMetricsTable div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }</style>
</body>

</html>
            
