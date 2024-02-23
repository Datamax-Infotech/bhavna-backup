<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 
	require_once('meeting_common_function.php');
	
	?>
	<style>
		table.dataTable#meetingTable td.reorder{
			cursor:default;
		}
	</style>
	<div id="wrapper">
	<? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
		<!-- Begin Page Content -->
		<div class="container-fluid border-bottom mb-4">
			<!-- Page Heading -->
			<div class="d-sm-flex align-items-center justify-content-between my-3">
				<h1 class="h3 mb-0">Meetings</h1>
				<div>
				<a class="btn btn-success btn-sm mr-1" style="width: 150px; align-self: end;" href="dashboard_management_v1.php">Back To Dash</a>
				<a class="btn btn-primary btn-sm"  href="dashboard_meeting_create.php">
					<i class="fa fa-unlock-alt"></i> Create New Meeting
				</a>
			</div>
				<!--<div class="dropdown new_meeting_dropdown" id="newMeetingDropdown">
					<button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-unlock-alt"></i> Create New Meeting
					</button>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="dashboard_meeting_create.php?type=1"><i class="fa fa-clock-o"></i>  L10 Meeting</a>
						<a class="dropdown-item" href="dashboard_meeting_create.php?type=2"><i class="fa fa-users"></i>  Same Page Meeting</a>
					</div>
				</div>-->
			</div>
		</div>
		<div class="container-fluid">
			<div class="row justify-content-center">   
				<div class="col-lg-12">
					<div class="card shadow mb-4">
						<!-- Card Body -->
						<div class="card-body create_meeting">
						<?php if(isset($_GET['meeting_error']) && $_GET['meeting_error']!=""){
							?>
							<div class="col-md-12" id="meeting_end_msg">
								<div class="alert alert-success">
									<?php 
										switch($_GET['meeting_error']){
											case 1:
												echo "<b>Please select the meeting!!</b>";
												break;
											case 2:
												echo "<b>Meeting Ended By Owner!!</b>";
												break;
											case 3:
												echo "<b>Meeting Started!!</b>";
												break;
											case 4:
												echo "<b>Join Meeting!!</b>";
												break;
											case 5:
												echo "<b>Meeting Archive!!</b>";
												break;	
										}
									?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							</div>
						<?php } ?>
						<?php 
								$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
								$emp_level=array_shift($select_level)['level'];
								$meeting_filter="";
								if($emp_level!=2){
									$meeting_filter=" and ma.attendee_id='".$_COOKIE['b2b_id']."'";
								}
		
								$sql_main_count=db_query("SELECT ma.meeting_id FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id=ma.meeting_id 
								where mm.status=1 $meeting_filter GROUP By ma.meeting_id",db_project_mgmt());
								if(tep_db_num_rows($sql_main_count>0)){?>
									<div class="table-responsive">
									<table id="meetingTable" class="table table-sm meetingTable" width="100%" cellspacing="0">
										<thead>
											<tr>
												<th class="meeting_star_th"><i data-tooltip="true" data-placement="bottom" title="Click to sort by Favourite Meetings" class="fa fa-star meeting_icon"></i></th>
												<!--<th class="meeting_star_th"><i data-tooltip="true" data-placement="bottom" title="Click to sort by Meetings Started" class="fa fa-star meeting_icon meeting_started_icon"></i></th>-->
												<th></th>
												<th class="meeting_name_th">Name</th>
												<th class="text-right">Attendees</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody id="meetingTableTbody">
										<?php
											$sql_main=db_query("SELECT ma.meeting_id,mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id=ma.meeting_id 
											where mm.status=1 and ma.attendee_id='".$_COOKIE['b2b_id']."' GROUP By ma.meeting_id ORDER BY ma.attendee_id DESC",db_project_mgmt());
											$owner_meetings_array=[];
											while($main_row = array_shift($sql_main)){
											$meeting_id=$main_row['meeting_id'];
											$owner_meetings_array[]=$meeting_id;
											//$sql_attendee =db_query("SELECT ma.id,attendee_id ,Headshot, name,initials FROM meeting_attendees as ma JOIN loop_employees ON ma.attendee_id=loop_employees.b2b_id  where ma.meeting_id=$meeting_id ORDER BY ma.attendee_id ASC", db());
											$sql_attendee =db_query("SELECT ma.id,attendee_id FROM meeting_attendees as ma where ma.meeting_id=$meeting_id ORDER BY ma.attendee_id ASC", db_project_mgmt());
											
											$select_meeting_flag=db_query("SELECT meeting_flg,id from meeting_timer where meeting_id=$meeting_id && meeting_flg=0",db_project_mgmt());
											
											$check_already_marked = db_query("SELECT fav_status FROM meeting_favourite_mark where meeting_id=$meeting_id && attendee_id='".$_COOKIE['b2b_id']."'",db_project_mgmt());
											$fav_status=0;
											if(tep_db_num_rows($check_already_marked)>0){
												while($fav_ar = array_shift($check_already_marked)){
													$fav_status = $fav_ar['fav_status'];
												}
											}

											$attendee_str="";
											$meeting_owner_flg=false;
											while($hrow = array_shift($sql_attendee)){
												if($hrow['attendee_id']==$_COOKIE['b2b_id']){
													$meeting_owner_flg=true;
												}
												$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$hrow['attendee_id']."'",db());
												$empDetails_arr=array_shift($empDetails_qry);
												$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
												$emp_img="background-image:url('".$empDetails['emp_img']."')";
												$attendee_str.='<span class="attendees_img" style="'.$emp_img.'">'.$empDetails['emp_txt'].'</span>';
											}
											
											?>
											<tr class="meeting_tr <?php echo $meeting_owner_flg==true? "": "meeting_tr_not_owner"; ?>" id="tr_meeting_<?php echo $meeting_id; ?>">
											<td><i meet_id="<?php echo $meeting_id; ?>" class="fa fa-star-o meeting_icon change_fav_status <?php echo $fav_status==1 ? 'star_marked' : ''; ?>" fav_status="<?php echo $fav_status; ?>"><span class="sr-only"><?php echo $fav_status == 1? "1" : "2";?></span></i></td>
											<!--<td id="meeting_status_td_<?=$meeting_id;?>"><i class="fa meeting_icon meeting_started_icon <?php echo tep_db_num_rows($select_meeting_flag)==0 ? 'fa-star-o' : 'fa-star'; ?>" ><span class="sr-only"><?php echo tep_db_num_rows($select_meeting_flag)==0 ? "1":"0";?></span></i></td>-->
											
											<td id="meeting_btn_<?=$meeting_id;?>">
												<?php 
													$m_id=new_dash_encrypt($meeting_id);
													$meeting_flg_val=tep_db_num_rows($select_meeting_flag);
													if($meeting_flg_val==0){?>
													<a  href="launch_meeting.php?meeting_id=<?php echo $m_id;?>" class="btn go-to-meeting-btn btn-sm">Go to Meeting</a>
												<?php }else{
													$meeting_timer_id=array_shift($select_meeting_flag)['id'];
													$mt_id=new_dash_encrypt($meeting_timer_id);
													?>
													<a href="meeting_timer_started.php?meeting_id=<?php echo $m_id.'&meeting_timer_id='.$mt_id;?>" class="btn meeting-started-btn btn-sm ">
														Meeting Started
														<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
													</a>
												<?php } ?>
												
											</td>
											<td class="td_meeting_name" id="meeting_name_<?=$meeting_id;?>">
												<?php 
													$m_id=new_dash_encrypt($meeting_id);
													if($meeting_flg_val==0){?>
													<a class="meeting_name" href="launch_meeting.php?meeting_id=<?php echo $m_id; ?>"><?php echo $main_row['meeting_name'];?></a>
												<?php }else{
													?>
													<a class="meeting_name" href="meeting_timer_started.php?meeting_id=<?php echo $m_id.'&meeting_timer_id='.$mt_id;?>" ><?php echo $main_row['meeting_name'];?></a>

												<?php } ?>
											</td>
											<td class="attendees_td">
												<?php echo $attendee_str; ?>
											</td>
											<td><a class="mytooltip" data-toggle="tooltip" data-placement="bottom" title="Edit Meeting" href="dashboard_meeting_create.php?meeting_id=<?php echo $m_id; ?>"><i class="fa fa-cog meeting_icon"></i></a></td>
											<td><a class="mytooltip" data-toggle="tooltip" data-placement="bottom" title="Preview" href="meeting_workspace.php?meeting_id=<?php echo $m_id; ?>"><i class="fa fa-desktop"></i></a></td>
											
										</tr>
										<?}
										if($emp_level==2){
											$owner_meetings=implode(",",$owner_meetings_array);
											$already_selected_meetings_str="";
											if($owner_meetings!=""){
												$already_selected_meetings_str="and ma.meeting_id NOT IN ($owner_meetings)"; 
											}
											$sql_main_other=db_query("SELECT ma.meeting_id,mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id=ma.meeting_id 
											where mm.status=1 $already_selected_meetings_str GROUP By ma.meeting_id ORDER BY ma.attendee_id DESC",db_project_mgmt());
											while($main_row = array_shift($sql_main_other)){
												$meeting_id=$main_row['meeting_id'];
												//$sql_attendee =db_query("SELECT ma.id,attendee_id ,Headshot, name,initials FROM meeting_attendees as ma JOIN loop_employees ON ma.attendee_id=loop_employees.b2b_id  where ma.meeting_id=$meeting_id ORDER BY ma.attendee_id ASC", db());
												$sql_attendee =db_query("SELECT ma.id,attendee_id FROM meeting_attendees as ma where ma.meeting_id=$meeting_id ORDER BY ma.attendee_id ASC", db_project_mgmt());
												
												$select_meeting_flag=db_query("SELECT meeting_flg,id from meeting_timer where meeting_id=$meeting_id && meeting_flg=0");
												$check_already_marked = db_query("SELECT fav_status FROM meeting_favourite_mark where meeting_id=$meeting_id && attendee_id='".$_COOKIE['b2b_id']."'",db_project_mgmt());
												$fav_status=0;
												if(tep_db_num_rows($check_already_marked)>0){
													while($fav_ar = array_shift($check_already_marked)){
														$fav_status = $fav_ar['fav_status'];
													}
												}
												
												$attendee_str="";
												$meeting_owner_flg=false;
												while($hrow = array_shift($sql_attendee)){
													if($hrow['attendee_id']==$_COOKIE['b2b_id']){
														$meeting_owner_flg=true;
													}
													$empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$hrow['attendee_id']."'",db());
													$empDetails_arr=array_shift($empDetails_qry);
													$empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
													$emp_img="background-image:url('".$empDetails['emp_img']."')";
													$attendee_str.='<span class="attendees_img" style="'.$emp_img.'">'.$empDetails['emp_txt'].'</span>';
												}
												
												?>
												<tr class="meeting_tr <?php echo $meeting_owner_flg==true? "": "meeting_tr_not_owner"; ?>" id="tr_meeting_<?php echo $meeting_id; ?>">
												<!--<td><i meet_id="<?php echo $meeting_id ?>" class="fa fa-star-o meeting_icon change_fav_status <?php echo $main_row['fav_status']==1 ? 'star_marked' : ''; ?>" fav_status="<?php echo $main_row['fav_status'];?>"><span class="sr-only"><?php echo $main_row['fav_status']==1? "1" : "2";?></span></i></td>-->
												<td><i meet_id="<?php echo $meeting_id; ?>" class="fa fa-star-o meeting_icon change_fav_status <?php echo $fav_status==1 ? 'star_marked' : ''; ?>" fav_status="<?php echo $fav_status; ?>"><span class="sr-only"><?php echo $fav_status == 1? "1" : "2";?></span></i></td>
												<!--<td><i class="fa meeting_icon meeting_started_icon <?php echo tep_db_num_rows($select_meeting_flag)==0 ? 'fa-star-o' : 'fa-star'; ?>" ><span class="sr-only"><?php echo tep_db_num_rows($select_meeting_flag)=="0" ? "1": "0";?></span></i></td>-->
											
												<td id="meeting_btn_<?=$meeting_id;?>">
													<?php 
														$m_id=new_dash_encrypt($meeting_id);
														$meeting_flg_val=tep_db_num_rows($select_meeting_flag);
														if($meeting_flg_val==0){?>
														<a  href="launch_meeting.php?meeting_id=<?php echo $m_id;?>" class="btn go-to-meeting-btn btn-sm">Go to Meeting</a>
													<?php }else{
														$meeting_timer_id=array_shift($select_meeting_flag)['id'];
														$mt_id=new_dash_encrypt($meeting_timer_id);
														?>
														<a href="meeting_timer_started.php?meeting_id=<?php echo $m_id.'&meeting_timer_id='.$mt_id;?>" class="btn meeting-started-btn btn-sm ">
															Meeting Started
															<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
														</a>
													<?php } ?>
													
												</td>
												<td class="td_meeting_name" id="meeting_name_<?=$meeting_id;?>">
												<?php 
													$m_id=new_dash_encrypt($meeting_id);
													if(tep_db_num_rows($select_meeting_flag)==0){?>
													<a class="meeting_name" href="launch_meeting.php?meeting_id=<?php echo $m_id; ?>"><?php echo $main_row['meeting_name'];?></a>
												<?php }else{
													?>
													<a class="meeting_name" href="meeting_timer_started.php?meeting_id=<?php echo $m_id.'&meeting_timer_id='.$mt_id;?>" ><?php echo $main_row['meeting_name'];?></a>

												<?php } ?>
											</td>
												<td class="attendees_td">
													<?php echo $attendee_str; ?>
												</td>
												<td><a class="mytooltip" data-toggle="tooltip" data-placement="bottom" title="Edit Meeting" href="dashboard_meeting_create.php?meeting_id=<?php echo $m_id; ?>"><i class="fa fa-cog meeting_icon"></i></a></td>
												<td><a class="mytooltip" data-toggle="tooltip" data-placement="bottom" title="Preview" href="meeting_workspace.php?meeting_id=<?php echo $m_id; ?>"><i class="fa fa-desktop"></i></a></td>
											</tr>
											<?}
										}
										?>
										
									</tbody>
								</table>
							</div>
							<?php } else {?>
								<div class="alert alert-primary" role="alert">
									No Current Meeting <a href="dashboard_meeting_create.php"><b>Click here to </b></a> Create New Meeting</a> 
								</div>
							<?php } ?>
						</div>
					</div>
				</div>		
			</div>
			<input type="hidden" id="page_type_for_notification" value="dashboard_meetings"/>
<?php require_once("inc/footer_new_dashboard.php");?> 
		</div>
		
</div>

<script>
		
		setTimeout(function() { 
			var url = window.location.href; 
			window.history.replaceState(null, null, url.split( "?" )[0])}, 
		5000);
		
		$('#meetingTable').dataTable({
			"aaSorting": [],
			info: false,
			paging: false,
			//"order": [],
			rowReorder: false,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [0,2] },
				{ orderable: false, targets: '_all' }
			]
		});
		$(document).on('click','.change_fav_status',function(){
			var current_meet_td=$(this);
			var meeting_id=$(this).attr('meet_id');
			var fav_status=$(this).attr('fav_status')==1 ? '0': '1';
			$.ajax({
					url:'dashboard_meeting_action.php',
					type:'post',
					data:{meeting_id,'fav_meeting_action':1,fav_status},
					async:false,
					beforeSend: function () {
						$(current_meet_td).before('<div class="d-none spinner spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
					},
					success:function(response){
						if(fav_status==1){
							$(current_meet_td).attr('fav_status',fav_status);
							$(current_meet_td).addClass('star_marked');
							formSubmitMessage("Meeting Marked As Favourite!");
						}else{
							$(current_meet_td).removeClass('star_marked');
							formSubmitMessage("Meeting Remove From Favourite Meeting List!");
						}
					},
					complete:function () {
						//$(current_meet_td).before("")
					},
				});
		});

	$('#meeting_end_msg').fadeIn();
    setTimeout(function() { $('#meeting_end_msg').fadeOut(3000); }, 3000);
	check_meeting_started();
	setInterval(check_meeting_started, 10000);

	

</script>  
</body>
</html>
            
