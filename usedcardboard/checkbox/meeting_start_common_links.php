
<?php
     if($top_links=='common_top_links'){
      $sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
            $meeting_name= array_shift($sql_main)['meeting_name']; ?>
        <div class="container-fluid p-0 mt-0" >
            <div class="card py-3 px-4 d-flex flex-row align-items-center justify-content-between">
                <h3><?php echo $meeting_name; ?> </h3>
                <p>
                <button data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="new_measurement" class="btn btn-white btn-sm border-0" data-todo='{"EditingFrom":"meetingStartMatrix"}'><i class="fa fa-plus"></i> Add Measurable</button>
				<!-- <button data-toggle="modal" data-target="#" class="btn btn-white btn-sm border-0"><i class="fa fa-edit"></i> Add Divider</button>-->
                <button id="addNewProjectMeet" class="btn btn-white btn-sm border-0" data-toggle="modal" data-target="#addProjectModalMeetCreate"><i class="fa fa-plus"></i> Add Project</button>
                <button  data-toggle="modal" data-target="#newIssueStartMeet" class="btn btn-white btn-sm border-0 "><i class="fa fa-exclamation-circle"></i> Create Issue</button>
                <button data-toggle="modal" data-target="#newTaskStartMeet" class="btn btn-white btn-sm border-0"><i class="fa fa-check-square-o"></i> Create Task</button>
                     
            </p>
            </div>
        </div>
                  
<?php } ?>
<?php 

if($sidebar_links=='common_sidebar_links' && $meeting_id!=""){
    ?>
<div class="col-md-2">
    <div class="card shadow meeting_timer text-center">
    <div class="card-body p-3">
        <?php
           $selsql1 = "SELECT * FROM meeting_timer WHERE id=".$meeting_timer_id;
            $selres1 = db_query($selsql1, db_project_mgmt());
            $meeting_owner="";
            if(tep_db_num_rows($selres1) > 0){
                $rowdata = array_shift($selres1);
                if($rowdata["meeting_flg"] == 1){   
                    echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=2';</script>";
                }else{
                
                    $check_attendee_join_status=db_query("SELECT join_status from meeting_start_atten_ratings where meeting_timer_id=$meeting_timer_id && attendee_id=".$_COOKIE['b2b_id']." && join_status=1",db_project_mgmt());
                    if(tep_db_num_rows($check_attendee_join_status)>0){
                        $meeting_owner=$rowdata['meeting_start_by'];
                        $st_dtime = $rowdata["start_time"];
                        $recid = $rowdata["id"];
                    }else{
                        echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=4';</script>"; 
                    }
                }
            }else{
                echo "<script>window.location.href = 'dashboard_meetings.php?meeting_error=1';</script>";
            }
        ?>
        <h5 id="elapsed-time"><?php echo  $st_dtime;?></h5>
        <div id="clock"></div>
		<div id="progress-bar" class="progress mt-3" style="height: 20px;">
		  <div id="progress" class="progress-bar bg-info" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
		<input type="hidden" id="server-timestamp" value="<?=date("Y-m-d H:i:s");?>">
		<input type="hidden" id="meeting_timerid" value="<?=$recid;?>">
        <script>
                function currentTime() {
                    var date = new Date(); /* creating object of Date class */
                    var hour = date.getHours();
                    var min = date.getMinutes();
                    var sec = date.getSeconds();
                    hour = updateTime(hour);
                    min = updateTime(min);
                    sec = updateTime(sec);
                    document.getElementById("clock").innerText = hour + " : " + min + " : " + sec; /* adding time to the div */
                        var t = setTimeout(function(){ currentTime() }, 1000); /* setting timer */
                    }

                    function updateTime(k) {
                    if (k < 10) {
                        return "0" + k;
                    }
                    else {
                        return k;
                    }
                }
            currentTime(); /* calling currentTime() function to initiate the process */


			function startMeetingTimer(startTime, expectedTime) {
				const elapsedTimeElement = document.getElementById("elapsed-time");
				const progressBar = document.getElementById("progress");
				const startDateTime = new Date(startTime);
				let totalSeconds = 0;
				let meetingTime = expectedTime * 60; // Convert minutes to seconds

				function updateTimer() {
					const hours = Math.floor(totalSeconds / 3600);
					const minutes = Math.floor((totalSeconds % 3600) / 60);
					const seconds = totalSeconds % 60;

					let elapsedText = "";
					if (hours > 0) {
					  elapsedText += `${hours}h `;
					}
					elapsedText += `${minutes}m`;
					elapsedTimeElement.textContent = elapsedText;

					const progressWidth = Math.min((totalSeconds / meetingTime) * 100, 100); 
					progressBar.style.width = progressWidth + "%";

					totalSeconds++;

					if (totalSeconds >= meetingTime) {
						meetingTime += 2 * 60; 
					}
				}

				const interval = setInterval(updateTimer, 1000);
				calculateElapsedTime();
				function calculateElapsedTime() {
					const now = new Date(document.getElementById("server-timestamp").value);
					const elapsedMilliseconds = now - startDateTime;
					totalSeconds = Math.floor(elapsedMilliseconds / 1000);
					updateTimer();
				}
			}

			startMeetingTimer('<? echo $st_dtime;?>', '<? echo $duration;?>');
		</script>
    </div>
    </div>

    <!--<div class="card shadow text-center tangent-alert mt-4">
    <h4 class="mb-0">Tangent Alert</h4>
    </div> -->
    <div class="card shadow mb-3 px-3 py-2 agenda mt-4 ">
        <h6 class="mb-0 mt-2">
            <!--<i class="active_agenda_link fa fa-print mr-5 mt-1"></i>-->
            Agenda
            <div class="float-right dropdown dropright agenda_setting_dropdown">
                <span class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog"></i></span>
                <div class="dropdown-menu dropdown-menu-right ">
                    <a class="dropdown-item" href="dashboard_meeting_create.php?meeting_id=<?= $_GET['meeting_id'];?>&&from_meet=yes"><i class="fa fa-pencil"></i>  Edit Meeting</a>
                    <div class="dropdown-divider my-1"></div>
                    <!--<a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-trash-o"></i>  Meeting Archive</a>-->
                    <a class="dropdown-item" href="meeting_minutes.php?meeting_id=<?= $_GET['meeting_id'];?>"><i class="fa fa-clock-o"></i>  Meeting Minutes</a>
                    <!--<a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-file-archive-o"></i>  Export All</a>
                    <a class="dropdown-item" href="javascript:void(0)"><i class="fa fa-user"></i>  Become Leader</a>
                    <div class="dropdown-divider my-1"></div>
                    <a class="dropdown-item text-danger" href="javascript:void(0)"><i class="fa fa-cog"></i> Advance Setting</a>
                    -->
                </div>
            </div>
        </h6>
        <div class="card-body px-0 py-2"> 
        <?php

            $sql_main =db_query("SELECT * FROM meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC", db_project_mgmt());
            if(tep_db_num_rows($sql_main)>0){
            $current_page_of_owner_sql=db_query("SELECT current_page from meeting_live_updates where meeting_timer_id='".$meeting_timer_id."' && attendee_id=".$meeting_owner, db_project_mgmt());
			$owner_current_page=array_shift($current_page_of_owner_sql)['current_page'];
			?>
            <ul class="p-0">
                <?php
                $isOwner=$_COOKIE['b2b_id'] == $meeting_owner ? 1 : 0;
                $url_parameters='?meeting_id='.$_GET["meeting_id"]."&meeting_timer_id=".$_GET['meeting_timer_id'];
                while($hrow = array_shift($sql_main)){
                $select_time=db_query("SELECT id,time_spent from meeting_time_spent where meeting_timer_id='".$meeting_timer_id."' && page_id=".$hrow['page_id'],db_project_mgmt());
               
                $page_url="";
                $page_id="";
                switch($hrow['page_type']){
                    case 'Check-in':
                        $page_url="meeting_checkin.php";
                        $page_id="check-in";
                        break; 
                    case 'Metrics':
                        $page_url="meeting_metrics.php";
                        $page_id="metrics";
                        break; 
                    case 'Projects':
                        $page_url="meeting_projects.php";
                        $page_id="projects";
                        break; 
                    case 'Task':
                        $page_url="meeting_tasks.php";
                        $page_id="tasks";
                        break; 
                    case 'Issues':
                        $page_url="meeting_issues.php";
                        $page_id="issue-list";
                       break; 
                    case 'Conclude':
                        $page_url="meeting_conclude.php";
                        $page_id="conclude";
                        break; 
                } 
               // echo "owner_current_page ".$owner_current_page."page_url".$page_url;
                $owner_active_page_cls=$owner_current_page==$page_url ? "active_page":"";
                $time_spent_array= array_shift($select_time);
                $time_spent_on_page=$time_spent_array['time_spent'];
                $time_spent_id=$time_spent_array['id'];
                $duration=$hrow["duration"];
                $time_spent= abs($duration- $time_spent_on_page);
                $time_cls=$duration < $time_spent_on_page ? "text-danger":"";
                $time_sign=$duration < $time_spent_on_page ? "-":"";
                $hours=intdiv($time_spent, 60)>0 ? intdiv($time_spent, 60).'h' : "";
                $display_time_spend = $hours.($time_spent % 60)."m";
                echo '<li duration="'.$duration.'" time_spent="'.$time_spent_on_page.'" table_page_id="'.$time_spent_id.'" id="'.$page_id.'"  class="change_meeting_page active_agenda_link '.$owner_active_page_cls.'"><a  href="'.$page_url.$url_parameters.'">'.$hrow["page_title"].'</a><span class="float-right '.$time_cls.'">'.$time_sign.$display_time_spend.'</span></li>';           
             } ?>
            </ul>
            <?php }else {?>
            <div class="col-md-12 alert alert-danger">
                <p class="mb-0">No  Agenda is set for current meeting,<a href="meeting_edit_pages.php?meeting_id=<?php echo $meeting_id; ?>"><b>Click</b></a> here to set agenda to the Meeting First!</p>
            </div>
            <?php } ?> 
       
        </div>
    </div>
    <div class="card shadow mb-3 px-3 py-2">
        <a class="meeting_sidebar_link" href="meeting_vto.php?meeting_id=<?= $_GET['meeting_id'];?>&meeting_timer_id=<?=$_GET['meeting_timer_id'];?>">Meeting VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
    </div>
    <!--<div class="card shadow mb-3 px-3 py-2">
        <a class="meeting_sidebar_link" href="company_vto.php?meeting_id=<?= $_GET['meeting_id'];?>&meeting_timer_id=<?=$_GET['meeting_timer_id'];?>">Company VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
    </div> -->
    <div class="card shadow mb-3 px-3 py-2">
        <a class="meeting_sidebar_link" href="javascript:;" data-toggle="modal" data-target="#openWhatVTOPopup" >Company VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
    </div>
    <div class="accordion" id="accordionExample">
    <div class="card shadow mb-3 px-3 py-2">
        <?php $res=getOnlineAttendee($meeting_timer_id); ?>
        <a class="meeting_sidebar_link" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Online(<span id="online_attendee_count"><?= count($res);?></span>) <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body px-0 py-2" id="online_attendees">
        <?php 
        foreach($res as $k=>$r){
            $emp_img="background-image:url('".$r['emp_img']."')";
            echo '<span class="attendees_img" style="'.$emp_img.'">'.$r['emp_txt'].'</span>';
        }
        db_query("update meeting_live_updates set attendee_flg=0 where meeting_timer_id='".$meeting_timer_id."' && attendee_id=".$_COOKIE['b2b_id'],db_project_mgmt());
        ?>
        </div>
        </div>
    </div>
    </div>
</div>
<?php } ?>



