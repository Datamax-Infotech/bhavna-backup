<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");

	require_once("inc/header_new_dashboard.php"); 
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
		<div class="container-fluid py-3 px-2 create_meeting" >
            <?php 
            $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
            if($meeting_id!=""){
                $meet_name_sql=db_query("SELECT meeting_name from meeting_master where id=$meeting_id",db_project_mgmt());
                $meeting_name=array_shift($meet_name_sql)['meeting_name'];
                ?>
                
                <div class="row justify-content-center">
                    <div class="col-md-12 py-1">
                    <h3><?php echo $meeting_name;?></h3>
                    </div> 
                    <div class="col-md-2 agenda">
                        <div class="card shadow mb-3 px-3 py-2">
                            <?php $meeting_started=false;
                                $link_class= $meeting_started==true ? "active_agenda_link" : "disabled_agenda_link";
                            ?>
                            <h6 class="mb-0"><i class="<?php echo $link_class;?> fa fa-print mr-5"></i>Agenda</h6>
                            <div class="card-body px-0 py-2">
                                <?php $sql_main =db_query("SELECT * FROM meeting_pages where meeting_id=$meeting_id ORDER BY order_no ASC", db_project_mgmt());
                                    if(tep_db_num_rows($sql_main)>0){?>
                                <ul class="p-0">
                                <?php while($hrow = array_shift($sql_main)){
                                    $page_url="";
                                    switch($hrow['page_type']){
                                        case 'Check-in':
                                            $page_url= $meeting_started==true ? "meeting_checkin.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 
                                        case 'Metrics':
                                            $page_url= $meeting_started==true ? "meeting_metrix.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 
                                        case 'Projects':
                                            $page_url= $meeting_started==true ? "meeting_projects.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 
                                        case 'Task':
                                            $page_url= $meeting_started==true ? "meeting_tasks.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 
                                        case 'Issues':
                                            $page_url= $meeting_started==true ? "meeting_issues.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 
                                        case 'Conclude':
                                            $page_url= $meeting_started==true ? "meeting_conclusion.php" : "javascript:void(0);";
                                            echo '<li class="'.$link_class.'"><a href="'.$page_url.'" >'.$hrow["page_title"].'</a><span class="float-right">'.$hrow["duration"].'</span></li>';
                                            break; 

                                    }?>

                                   <!-- <li class="<?php echo $link_class;?>"><a href="#" >Check-in</a><span class="float-right">5m</span></li>
                                    <li class="<?php echo $link_class;?>"><a href="#" >Projects</a><span class="float-right">5m</span></li>
                                    <li class="<?php echo $link_class;?>"><a href="#" >Metrics</a><span class="float-right">5m</span></li>
                                    <li class="<?php echo $link_class;?>"><a href="#" >Tasks</a><span class="float-right">5m</span></li>
                                    <li class="<?php echo $link_class;?>"><a href="#" >Issues</a><span class="float-right">60m</span></li>
                                    <li class="<?php echo $link_class;?>"><a href="#" >Conclude</a><span class="float-right">5m</span></li>-->
                                    <?php } ?>
                                </ul>
                                <?php }else {?>
                                    <div class="col-md-12 alert alert-danger">
                                        <p class="mb-0">No  Agenda is set for current meeting,<a href="meeting_edit_pages.php?meeting_id=<?php echo $meeting_id; ?>"><b>Click</b></a> here to set agenda to the Meeting First!</p>
                                    </div>
                                    <?php } ?>
                            </div>
                        </div>
                        <div class="card shadow mb-3 px-3 py-2">
                            <a class="meeting_sidebar_link" href="meeting_vto.php?meeting_id=<?= $_GET['meeting_id'];?>">Meeting VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
                        </div>
                        <!--<div class="card shadow mb-3 px-3 py-2">
                            <a class="meeting_sidebar_link" href="company_vto.php">Company VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
                        </div>-->
                        <div class="card shadow mb-3 px-3 py-2">
                            <a class="meeting_sidebar_link" href="javascript:;" data-toggle="modal" data-target="#openWhatVTOPopup" >Company VTO <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
                        </div>
                        <!--<div class="accordion" id="accordionExample">
                        <div class="card shadow mb-3 px-3 py-2">
                            <a class="meeting_sidebar_link" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Online(3) <span class="float-right"><i class="fa fa-angle-right fa-lg"></i></span></a>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body px-0 py-2 d-flex">
                            <span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att1.png')"></span>
                            <span class="attendees_img" style="background-image:url('')">WS</span>
                            <span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att2.png')"></span>
                            </div>
                            </div>
                        </div>
                        </div>-->
                    </div>
                    <div class="col-md-10">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                    <a class="edit-heading" href="dashboard_meeting_create.php?from_meet=yes&&meeting_id=<?=$_GET['meeting_id'];?>"><i class="fa fa-cog"></i> Edit Meeting </a>
                                    <!-- <div class="float-right dropdown new_meeting_dropdown" id="newMeetingDropdown">
                                        <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Printouts <i class="fa fa-angle-down"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="dashboard_meeting_create.php?type=1"><i class="fa fa-calendar-check-o"></i>   Quarterly Planning Printout</a>
                                            <a class="dropdown-item" href="dashboard_meeting_create.php?type=2"><i class="fa fa-clock-o"></i>  Meeting Printout</a>
                                        </div>
                                    </div>-->
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                    <h3 class="text-center font-weight-bold">Who's Attending</h3>
                                    <table class="launch_meeting_attendees_table table border-0">
                                        <tbody>
                                        <?php
                                            $sql_attendee =db_query("SELECT ma.id,attendee_id FROM meeting_attendees as ma where ma.meeting_id=".$meeting_id." ORDER BY attendee_id ASC", db_project_mgmt());
                                            while($hrow = array_shift($sql_attendee)){
                                                $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$hrow['attendee_id']."'",db());
                                                $empDetails_arr=array_shift($empDetails_qry);
                                                $empDetails=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']);
                                            ?>
                                            <tr>
                                                <td class="attendee_checked_td"><input checked value="<?php echo $hrow['attendee_id'];?>" type="checkbox" class="checkbox_lg meeting_attendee" name="attendee_check[]"/></td>
                                                <td class="attendee_img_td"><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
                                                <td class="attendee_name_td"><?php echo $empDetails_arr['name']; ?></td>
                                            </tr>
                                            <?php } ?>
                                           

                                        </tbody>
                                    </table>
                                    <div class="text-right mt-5">
                                    <div class="float-right dropdown new_meeting_dropdown" id="newMeetingDropdown">
                                        <!--<a href="javascript:void(0)" class="btn btn-light btn-sm">Preview Meeting</a>
                                        <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Printouts <i class="fa fa-angle-down text-dark"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="dashboard_meeting_create.php?type=1"><i class="fa fa-calendar-check-o"></i>   Quarterly Planning Printout</a>
                                            <a class="dropdown-item" href="dashboard_meeting_create.php?type=2"><i class="fa fa-clock-o"></i>  Meeting Printout</a>
                                        </div>-->
                                        <div id="launch_meeting_btn">
                                       <?php 
                                       /*if(isset($_GET['meeting_timer_id']) && $_COOKIE['meeting_timer_id']!=0 && $_COOKIE['meeting_timer_id']!="") {
                                         if($_GET['meeting_id']==$_COOKIE['meeting_id']){?>
                                            <a href="meeting_timer_started.php?meeting_id=<?php echo $meeting_id.'&meeting_timer_id='.$_COOKIE['meeting_timer_id'];?>" class="btn meeting-started-btn btn-sm ">
                                                Meeting Started
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            </a>
                                            <?php } else{?>
                                                 <a href="meeting_timer_started.php?meeting_id=<?php echo $meeting_id;?>" class="btn btn-primary btn-sm" id="start_meeting_btn">Start Meeting As Leader</a>
                                          <?php }?>
                                        <?php } else{ ?>
                                        <a href="meeting_timer_started.php?meeting_id=<?php echo $meeting_id;?>" class="btn btn-primary btn-sm" id="start_meeting_btn">Start Meeting As Leader</a>
                                        <?php } */
                                        //echo 'SELECT meeting_flg,id from meeting_timer where meeting_id ='.$meeting_id.' ORDER BY id DESC LIMIT 1';
                                        $meeting_flg_qry = db_query('SELECT meeting_flg,id from meeting_timer where meeting_id ='.$meeting_id.' ORDER BY id DESC LIMIT 1',db_project_mgmt());
                                        if(tep_db_num_rows($meeting_flg_qry) >0 )
										{
                                             $meeting_data = array_shift($meeting_flg_qry);
                                             if($meeting_data['meeting_flg'] == 0){?>
                                                <a href="meeting_timer_started.php?meeting_id=<?php echo $_GET['meeting_id'].'&meeting_timer_id='.new_dash_encrypt($meeting_data['id']);?>" class="btn meeting-started-btn btn-sm ">
                                                Meeting Started
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            </a>
                                           <?php 
												}else{ ?>
												<a href="meeting_timer_started.php?meeting_id=<?php echo $_GET['meeting_id'];?>" class="btn btn-primary btn-sm" id="start_meeting_btn">Start Meeting As Leader</a>
                                          <? } 
										}else{ ?>
											<a href="meeting_timer_started.php?meeting_id=<?php echo $_GET['meeting_id'];?>" class="btn btn-primary btn-sm" id="start_meeting_btn">Start Meeting As Leader</a>										  
									  <? }?>
                                        </div>	
									</div>
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
            <input type="hidden" id="page_type_for_notification" value="launch_meeting"/>
            <?php require_once("inc/footer_new_dashboard.php");?> 
        </div>
	    </div>
 
	</div>
    <script>
        $(document).on('click',"#start_meeting_btn",function(){
            var attendeeList=$('.meeting_attendee');
            var attendeeList_para=[];
            $.each(attendeeList, function(k,v){
                if($(v).prop('checked')==true){
                    attendeeList_para.push($(v).val());
                }
            });
            var current_href=$('#start_meeting_btn').attr('href');
            $('#start_meeting_btn').attr('href',current_href+"&attendees_list="+attendeeList_para.join(','))
           // alert($('#start_meeting_btn').attr('href'));
            return true;
        });

        check_meeting_started();
	    setInterval(check_meeting_started, 10000);

       
    </script>
 
</body>

</html>