<?php
session_start();
if ($_REQUEST["no_sess"] == "yes") {
} else {
    require("inc/header_session.php");
}
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");

require_once("inc/header_new_dashboard.php");
?>
<div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid py-3  create_meeting" id="create_meeting_main">
                <?php
                $meeting_id = isset($_GET['meeting_id']) && $_GET['meeting_id'] != "" ? new_dash_decrypt($_GET['meeting_id']) : "";
                ?>
                <div class="row justify-content-center">
                    <? if (isset($_GET['from_meet']) && $_GET['from_meet'] == "yes") { ?>
                        <div class="col-md-12 text-right">
                            <a class="btn btn-success btn-sm mb-2" style="width: 150px; align-self: end;" href="javascript:history.go(-1);">Back To Meeting</a>
                        </div>
                    <? } ?>
                    <div class="col-md-3">
                        <div class="card shadow mb-4 px-4 pt-4">
                            <?php $meeting_name = "";
                            if ($meeting_id == "") {
                                echo '<h6 class="collapse-heading">ADD MEETING</h6>';
                            } else {
                                $sql_main = db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
                                $meeting_name = array_shift($sql_main)['meeting_name'];
                                // $meeting_name=$meeting_name_arr['meeting_name'];
                                echo '<h6 class="collapse-heading">EDIT MEETING : <small><b>' . $meeting_name . '</b></small></h6>';
                            } ?>

                            <div class="card-body p-3">
                                <ol class="nav flex-column nav-pills" id="create-meeting-tab" role="tablist" aria-orientation="vertical">
                                    <li><a class="nav-link active" id="basic-tab" data-toggle="pill" href="#basic" role="tab" aria-controls="basic" aria-selected="true"> Basics</a></li>
                                    <li><a class="nav-link" id="attendees-tab" data-toggle="pill" href="#attendees" role="tab" aria-controls="attendees" aria-selected="false"> Attendees</a></li>
                                    <li><a class="nav-link" id="metrics-tab" data-toggle="pill" href="#metrics" role="tab" aria-controls="metrics" aria-selected="false"> Metrics</a></li>
                                    <li><a class="nav-link" id="projects-tab" data-toggle="pill" href="#projects" role="tab" aria-controls="projects" aria-selected="false"> Projects</a></li>
                                    <!--<li><a class="nav-link" id="headline-tab" data-toggle="pill" href="#headline" role="tab" aria-controls="headline" aria-selected="false"> Headlines</a></li>-->
                                    <li><a class="nav-link" id="tasks-tab" data-toggle="pill" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false"> Tasks</a></li>
                                    <li><a class="nav-link" id="issues-tab" data-toggle="pill" href="#issues" role="tab" aria-controls="issues" aria-selected="false"> Issues</a></li>
                                </ol>
                            </div>
                        </div>
                        <!--<div class="text-center">
                                <a href="launch_meeting.php?meeting_id=<?php echo new_dash_encrypt($meeting_id); ?>" onclick="return check_meeting_saved()" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i> Launch Meeting</a>  
                            </div>-->
                        <div class="text-center" id="launch_meeting_btn">
                            <?
                            $m_id = $_GET['meeting_id'];
                            if ($meeting_id != "") {
                                $meeting_flg_qry = db_query("SELECT mt.meeting_id,mt.id,mt.meeting_flg from meeting_timer as mt where meeting_id=$meeting_id ORDER BY id DESC limit 1", db_project_mgmt());
                                $meeting_flg = 1;
                                if (tep_db_num_rows($meeting_flg_qry) > 0) {
                                    while ($r = array_shift($meeting_flg_qry)) {
                                        if ($r['meeting_flg'] == 0) {
                                            $mt_id = new_dash_encrypt($r['id']);
                            ?>
                                            <a href="meeting_timer_started.php?meeting_id=<?php echo $m_id . '&meeting_timer_id=' . $mt_id; ?>" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i>
                                                Meeting Started
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                            </a>
                                        <? } else { ?>
                                            <a href="launch_meeting.php?meeting_id=<?= $m_id; ?>" onclick="return check_meeting_saved()" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i> Launch Meeting</a>
                                    <? }
                                    }
                                } else { ?>
                                    <a href="launch_meeting.php?meeting_id=<?= $m_id; ?>" onclick="return check_meeting_saved()" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i> Launch Meeting</a>
                                <? }
                            } else { ?>
                                <a href="launch_meeting.php?meeting_id=<?= $m_id; ?>" onclick="return check_meeting_saved()" class="btn btn-blue w-75"><i class="fa fa-play-circle"></i> Launch Meeting</a>

                            <? }
                            ?>

                        </div>
                        <div class="text-left">
                            <ul class="meeting_create_left_side">
                                <!--<li><i class="fa fa-trash"></i> <a onclick="return check_meeting_saved()" href="javascript:void(0)">Meeting Archive</a></li>-->
                                <li><i class="fa fa-clock-o"></i> <a onclick="return check_meeting_saved()" href="meeting_minutes.php?meeting_id=<?= $_GET['meeting_id']; ?>">Meeting Minutes</a></li>
                                <li><i class="fa fa-paper-plane"></i> <a href="meeting_vto.php?meeting_id=<?= $_GET['meeting_id']; ?>">View Meeting VTO</a></li>
                                <!--<li><i class="fas fa-building"></i> <a href="company_vto.php">View Company VTO</a></li>-->

                                <li><i class="fas fa-building"></i> <a href="javascript:;" data-toggle="modal" data-target="#openWhatVTOPopup">View Company VTO</a></li>
                                <li><i class="fa fa-file-text-o"></i> <a onclick="return check_meeting_saved()" href="meeting_edit_pages.php?meeting_id=<?php echo $_GET['meeting_id']; ?>">Edit Pages</a></li>
                                <li><i class="fa fa-trash"></i> <a onclick="return check_meeting_saved('archive-meeting')" href="dashboard_meetings.php?meeting_error=5">Archive Meeting</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="">
                            <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="offset-md-1 col-md-8 mt-1">
                                                <form action="" method="post" class="meeting_form">
                                                    <div class="form-group row my-5">
                                                        <div class="col-md-12 alert alert-danger d-none" id="meeting_exist_msg">
                                                            <p class="mb-0">Meeting Name Already Exist!</p>
                                                        </div>


                                                        <div class="col-md-12 alert alert-danger" id="blank_meeting_msg">
                                                            <p class="mb-0">Please save the meeting first!</p>
                                                        </div>
                                                        <label class="col-md-3">Meeting Name:</label>
                                                        <div class="col-md-9">
                                                            <input type="text" id="create_meeting_name" name="create_meeting_name" value="<?php echo $meeting_name; ?>" class="form-control form-control-sm border_input" placeholder="">
                                                            <p class="d-none text-danger" id="create_meeting_name_error">Please Enter Meeting Name</p>
                                                            <input type="hidden" name="hidden_meeting_id" id="hidden_meeting_id" value="<?php echo $meeting_id; ?>" />

                                                            <div class="mt-2 col-md-12 alert alert-success <?= isset($_GET['meet_create']) && $_GET['meet_create'] == 1 ? '' : 'd-none'; ?>" id="meeting_action_msg">
                                                                <p class="mb-0">Meeting Created!</p>
                                                            </div>
                                                            <div class="d-flex mt-4">
                                                                <div class="d-none spinner spinner-border text-primary mr-2" role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                                <?php $meeting_save_text = $meeting_id == "" ? 'Add Meeting' : 'Update Meeting'; ?>
                                                                <button type="button" id="save-meeting-name" class="btn btn-blue btn-sm <?= isset($_GET['meet_create']) && $_GET['meet_create'] == 1 ? 'd-none' : ''; ?>"><?php echo $meeting_save_text; ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="offset-md-1 col-md-2">
                                                <div class="dropdown permissionDropdown" id="permissionDropdown">
                                                    <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                                        <i class="fa fa-lock"></i> Permissions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <div class="col-md-12 p-3">
                                                            <p>Add permissions for a user or team. Begin typing here...</p>
                                                            <select class="form-control form-control-sm">
                                                                <option>-</option>
                                                                <option>Please enter 1 or more characters</option>
                                                            </select>
                                                            <hr>
                                                            <table class="table table-sm permissionTable mt-3 border-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>View</th>
                                                                        <th>Edit</th>
                                                                        <th>Admin</th>
                                                                        <th></th>
                                                                        <th></th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td><span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att3.png')"></span></td>
                                                                        <td>Zac Fratkin</td>
                                                                        <td><i class="fa fa-trash-o"></i></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><i class="fa fa-angle-right" aria-hidden="true"></i></td>
                                                                        <td>
                                                                            <div class="form-check"><input checked class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input checked class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input checked class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td><span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att3.png')"></span></td>
                                                                        <td>Zac Fratkin</td>
                                                                        <td><i class="fa fa-trash-o"></i></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check"><input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1"></div>
                                                                        </td>
                                                                        <td><span class="attendees_img" style="background-image:url('assets_new_dashboard/img/att3.png')"></span></td>
                                                                        <td>Zac Fratkin</td>
                                                                        <td><i class="fa fa-trash-o"></i></td>
                                                                        <td></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="attendees" role="tabpanel" aria-labelledby="attendees-tab">
                                <div class="card shadow mb-4 add_icon_parent_m">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="collapse-heading">ATTENDEES LIST</h6>
                                                <?php
                                                if ($meeting_id == "") { ?>
                                                    <div class="text-center no_issue_div justify-content-start">
                                                        <img src="assets_new_dashboard/img/segue.svg" class="no_issue_img" />
                                                        <p>No Meeting Attendee</p>
                                                    </div>
                                                <?php  } else {
                                                    $sql_main = db_query("SELECT ma.attendee_id  FROM meeting_attendees as ma where ma.meeting_id=$meeting_id ORDER BY attendee_id ASC", db_project_mgmt());
                                                    $no_data_div = "d-none";
                                                    $present_data_div = "d-table";
                                                    if (tep_db_num_rows($sql_main) == 0) {
                                                        $no_data_div = "d-block";
                                                        $present_data_div = "d-none";
                                                    }
                                                ?>
                                                    <table id="meetingCreateAttendeesList" class="table table-sm meetingTable mt-3 table_vetical_align_middle hover_table border-0  <?php echo $present_data_div; ?>">
                                                        <?php
                                                        $result = array();
                                                        while ($hrow = array_shift($sql_main)) {
                                                            $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='" . $hrow['attendee_id'] . "'", db());
                                                            $empDetails_arr = array_shift($empDetails_qry);
                                                            $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'], $empDetails_arr['initials']);
                                                            $empname = $empDetails_arr["name"]; ?>
                                                            <tr>
                                                                <td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span></td>
                                                                <td class="td_w_95"><span><?php echo $empname; ?></span></td>
                                                                <td><i att_id="<?php echo $hrow['attendee_id']; ?>" class="fa fa-trash-o fa-lg meeting_attendees_delete"></i></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </table>
                                                    <div id="no_attendee_available_div_create_meet" class="text-center no_issue_div justify-content-start  <?php echo $no_data_div; ?>">
                                                        <img src="assets_new_dashboard/img/segue.svg" class="no_issue_img" />
                                                        <p>No Meeting Attendee</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addAttendeeCreateMeeting" class="add_icon_m" add_type="attendee" data-tooltip="true" title="Add Attendee" data-placement="left" data-toggle="modal" data-target="#addAttendeesModalMeetCreate">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="metrics" role="tabpanel" aria-labelledby="metrics-tab">
                                <div class="card shadow mb-4 add_icon_parent_m">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 matrix_main_inner_content">
                                                <h6 class="collapse-heading">METRICS</h6>
                                                <?php
                                                if ($meeting_id == "") { ?>
                                                    <div class="text-center no_issue_div justify-content-start">
                                                        <img src="assets_new_dashboard/img/icon_metrics-stats.svg" class="no_issue_img" />
                                                        <p>No Current Measurables</p>
                                                    </div>
                                                <?php  } else {

                                                    // $scorecard_data_sql = "SELECT * FROM `scorecard` where (attach_meeting like '%-".$meeting_id."-%' OR attach_meeting like '%-".$meeting_id."' OR attach_meeting like '".$meeting_id."-%' OR attach_meeting = ".$meeting_id.") AND (`b2b_id` = ".$_COOKIE['b2b_id'].")";
                                                    //$scorecard_data_sql = "SELECT scorecard.id,scorecard.name,scorecard.goal,scorecard.goal_matric,Headshot,initials FROM scorecard JOIN loop_employees ON scorecard.b2b_id=loop_employees.b2b_id where (scorecard.attach_meeting like '%-".$meeting_id."-%' OR scorecard.attach_meeting like '%-".$meeting_id."' OR scorecard.attach_meeting like '".$meeting_id."-%' OR scorecard.attach_meeting = ".$meeting_id.") AND (scorecard.archived = false) ORDER BY scorecard.meeting_create_order_no ASC";
                                                    $scorecard_data_sql = "SELECT scorecard.id,scorecard.name,scorecard.goal,scorecard.goal_matric,accountable FROM scorecard where (scorecard.attach_meeting like '%-" . $meeting_id . "-%' OR scorecard.attach_meeting like '%-" . $meeting_id . "' OR scorecard.attach_meeting like '" . $meeting_id . "-%' OR scorecard.attach_meeting = " . $meeting_id . ") AND (scorecard.archived = false) ORDER BY scorecard.meeting_create_order_no ASC";

                                                    $scorecard_data_query = db_query($scorecard_data_sql, db_project_mgmt());

                                                ?>
                                                    <?php if (!empty($scorecard_data_query)) { ?>
                                                        <table id="metricsTable" class="table table-sm meetingTable table_vetical_align_middle hover_table mt-5 border-0">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?
                                                                while ($scorecard_data = array_shift($scorecard_data_query)) {
                                                                    $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='" . $scorecard_data['accountable'] . "'", db());
                                                                    $empDetails_arr = array_shift($empDetails_qry);
                                                                    $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'], $empDetails_arr['initials']);
                                                                    switch ($scorecard_data['goal']) {
                                                                        case '==':
                                                                            $goal = 'Equal to';
                                                                            break;
                                                                        case '>=':
                                                                            $goal = 'Greater than or equal to';
                                                                            break;
                                                                        case '>':
                                                                            $goal = 'Greater than';
                                                                            break;
                                                                        case '<=>':
                                                                            $goal = 'Between';
                                                                            break;
                                                                        case '<=':
                                                                            $goal = 'Less than or equal to';
                                                                            break;
                                                                        case '<':
                                                                            $goal = 'Less than';
                                                                            break;
                                                                        default:
                                                                            $goal = '';
                                                                            break;
                                                                    }

                                                                    //$empDetails=getOwerHeadshotForMeeting($scorecard_data['Headshot'],$scorecard_data['initials']);

                                                                ?>
                                                                    <tr data-sort-id="<?= new_dash_encrypt($scorecard_data['id']) ?>">
                                                                        <td><i class="fa fa-arrows"></i></td>
                                                                        <td class="matrix_image">
                                                                            <span class="attendees_img" style="background-image:url('<?= $empDetails['emp_img'] ?>')">
                                                                                <?= $empDetails['emp_txt']; ?></span>
                                                                        </td>
                                                                        <td class="td_w_60 matrix_name">
                                                                            <a type="button" class="" data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="<?= new_dash_encrypt($scorecard_data['id']) ?>" data-todo='{"EditingFrom":"EditMeetingMatrix"}'><?= $scorecard_data['name'] ?>
                                                                            </a>
                                                                        </td>
                                                                        <td class="matrix_goal">
                                                                            <a type="button" class="" data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="<?= new_dash_encrypt($scorecard_data['id']) ?>" data-todo='{"EditingFrom":"EditMeetingMatrix"}'><?= $goal ?>
                                                                            </a>
                                                                        </td>
                                                                        <td class="td_w_15 matrix_goal_matrix">
                                                                            <a type="button" class="" data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="<?= new_dash_encrypt($scorecard_data['id']) ?>" data-todo='{"EditingFrom":"EditMeetingMatrix"}'><?= $scorecard_data['goal_matric'] ?>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <a href="javascript:void(0)" id='deleteMatrix' onclick="deleteMatrix($(this),'<?= new_dash_encrypt($scorecard_data['id']) ?>')">
                                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>

                                                    <?php } else { ?>
                                                        <div id="" class="text-center no_issue_div justify-content-center">
                                                            <img src="assets_new_dashboard/img/icon_metrics-stats.svg" class="no_issue_img" />
                                                            <p>No Current Measurables.</p>
                                                        </div>
                                                <?php }
                                                } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="add_icon_m" add_type="measurable" data-tooltip="true" data-placement="left" title="Add Measurable" data-toggle="modal" data-target="#addMeasurablesModalMeetCreate">
                                            <i class="fa fa-plus"></i>
                                        </div> -->
                                    <a type="button" add_type="measurable" class="add_icon_m text-white" data-toggle="modal" data-tooltip="true" data-placement="left" title="Add Measurable" data-target="#scorecardAddMatrixModalPopop" data-whatever="new_measurement" data-todo='{"EditingFrom":"EditMeetingAddMatrixModal"}'>
                                        <i class="fa fa-plus"></i>
                                    </a>

                                    <!-- <div class="add_icon_m" add_type="measurable" data-tooltip="true" data-placement="left" title="Add Measurable" data-toggle="modal" data-target="#scorecardAddMatrixModalPopop" data-whatever="new_measurement" data-todo='{"EditingFrom":"EditMeetingAddMatrixModal"}'>
                                            <i class="fa fa-plus"></i>
                                        </div> -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="projects" role="tabpanel" aria-labelledby="projects-tab">
                                <div class="card shadow mb-4 add_icon_parent_m">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="collapse-heading">Projects</h6>
                                            </div>
                                            <div class="col-md-12 mt-3" id="meetingProjectDiv">
                                                <?php
                                                if ($meeting_id == "") { ?>
                                                    <div class="text-center no_issue_div justify-content-start">
                                                        <img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img" />
                                                        <p>No Current Projects</p>
                                                    </div>
                                                <?php } else {
                                                    //$project_sql=db_query("SELECT project_id,project_name,project_owner,Headshot, name,initials FROM project_master JOIN loop_employees ON project_master.project_owner=loop_employees.b2b_id where find_in_set($meeting_id,meeting_ids) and archive_status=0 ORDER BY project_id DESC", db());
                                                    $project_sql = db_query("SELECT project_id,project_name,project_owner FROM project_master where find_in_set($meeting_id,meeting_ids) and archive_status=0 ORDER BY project_id DESC", db_project_mgmt());

                                                    $no_data_div = "d-none";
                                                    $present_data_div = "d-table";
                                                    if (tep_db_num_rows($project_sql) == 0) {
                                                        $no_data_div = "d-block";
                                                        $present_data_div = "d-none";
                                                    }
                                                ?>
                                                    <table id="meetingProjects" class="meetingProjects table table-sm meetingTable mt-3 table_vetical_align_middle hover_table w-100 border-0 <?php echo $present_data_div; ?>">
                                                        <thead>
                                                            <tr>
                                                                <th class="img_th">Acc</th>
                                                                <th class="title_th">Projects</th>
                                                                <!--<th class="vto_th">VTO</th>-->
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($r = array_shift($project_sql)) {
                                                                $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='" . $r['project_owner'] . "'", db());
                                                                $empDetails_arr = array_shift($empDetails_qry);
                                                                $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'], $empDetails_arr['initials']);
                                                            ?>
                                                                <tr>
                                                                    <td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $r['name']; ?></span></td>
                                                                    <td class="td_w_95">
                                                                        <a class="edit_title_all" href="javascript:edit_project(<?php echo $r['project_id']; ?>, 'editFromCreateMeet')"><?php echo $r['project_name']; ?></a>
                                                                    </td>
                                                                    <!--<td><input type="checkbox" class="checkbox_lg"/></td> -->
                                                                    <td><i class="deleteProjectCreateMeet fa fa-trash-o fa-lg" project_id="<?php echo $r['project_id'] ?>"></i></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div id="no_project_available_div_create_meet" class="text-center no_issue_div justify-content-start <?php echo $no_data_div; ?>">
                                                        <img src="assets_new_dashboard/img/empty_state_rocks.svg" class="no_issue_img" />
                                                        <p>No Current Projects</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="addNewProjectMeet" class="add_icon_m" add_type="project" data-tooltip="true" data-placement="left" title="Add Project" data-toggle="modal" data-target="#addProjectModalMeetCreate">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
                                <div class="card shadow mb-4 add_icon_parent_m">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="collapse-heading">Tasks<!--<button class="btn btn-meeting-green btn-sm float-right"><i class="fa fa-upload"></i> Upload To-dos</button>--></h6>
                                            </div>
                                            <div class="col-md-12 mt-3" id="meetingTaskDiv">
                                                <?php
                                                if ($meeting_id == "") { ?>
                                                    <div class="text-center no_issue_div justify-content-start">
                                                        <img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img" />
                                                        <p>No Current Task</p>
                                                    </div>
                                                <?php } else {
                                                    $task_sql = db_query("SELECT task_master.id,task_master.task_status,task_title,task_entered_by,task_assignto FROM task_master 
													where task_meeting=$meeting_id and archive_status=0 ORDER BY id DESC", db_project_mgmt());
                                                    $no_data_div = "d-none";
                                                    $present_data_div = "d-table";
                                                    if (tep_db_num_rows($task_sql) == 0) {
                                                        $no_data_div = "d-block";
                                                        $present_data_div = "d-none";
                                                    } ?>
                                                    <table id="meetingTask" class="meetingTaskIssue table table-sm meetingTable mt-3 table_vetical_align_middle hover_table w-100 border-0 <?php echo $present_data_div; ?>">
                                                        <thead>
                                                            <tr>
                                                                <th class="img_th"></th>
                                                                <th class="title_th">Task</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($r = array_shift($task_sql)) {
                                                                $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='" . $r['task_assignto'] . "'", db());
                                                                $empDetails_arr = array_shift($empDetails_qry);
                                                                $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'], $empDetails_arr['initials']);
                                                            ?>
                                                                <tr>
                                                                    <td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $r['name']; ?></span></td>
                                                                    <td class="td_w_95">
                                                                        <a class="edit_title_all" <?= $r['task_status'] == 2 || $r['task_status'] == 1 ? "style='text-decoration:line-through'" : ""; ?> href="javascript:edit_task(<?php echo $r['id']; ?>,'EDIT_TASK_MEETING')"><?php echo $r['task_title']; ?></a>
                                                                    </td>
                                                                    <!--<td><input type="checkbox" class="checkbox_lg"/></td> -->
                                                                    <td><i class="deleteTaskCreateMeet fa fa-trash-o fa-lg" task_id="<?php echo $r['id'] ?>"></i></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div id="no_task_available_div_create_meet" class="text-center no_issue_div justify-content-start <?php echo $no_data_div; ?>">
                                                        <img src="assets_new_dashboard/img/todo-completion.svg" class="no_issue_img" />
                                                        <p>No Current Task</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="add_task_meeting" class="add_icon_m" add_type="todos" data-tooltip="true" data-placement="left" title="Add Task" data-toggle="modal" data-target="#newTask">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="issues" role="tabpanel" aria-labelledby="issues-tab">
                                <div class="card shadow mb-4 add_icon_parent_m">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="collapse-heading">Issue<!--<button class="btn btn-meeting-green btn-sm float-right"><i class="fa fa-upload"></i> Upload To-dos</button>--></h6>
                                            </div>
                                            <div class="col-md-12 mt-3" id="meetingIssueDiv">
                                                <?php
                                                if ($meeting_id == "") { ?>
                                                    <div class="text-center no_issue_div justify-content-start">
                                                        <img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img" />
                                                        <p>No Current Issue!</p>
                                                    </div>
                                                <?php } else {
                                                    $issue_sql = db_query("SELECT issue_master.id,issue,created_by FROM issue_master	where meeting_id=$meeting_id and issue_master.status = 1 ORDER BY id DESC", db_project_mgmt());
                                                    $no_data_div = "d-none";
                                                    $present_data_div = "d-table";
                                                    if (tep_db_num_rows($issue_sql) == 0) {
                                                        $no_data_div = "d-block";
                                                        $present_data_div = "d-none";
                                                    } ?>

                                                    <table id="meetingIssue" class="table meetingTaskIssue table-sm meetingTable mt-3 table_vetical_align_middle hover_table w-100 border-0 <?php echo $present_data_div; ?>">
                                                        <thead>
                                                            <tr>
                                                                <th class="img_th"></th>
                                                                <th class="title_th">Issue</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($r = array_shift($issue_sql)) {
                                                                $empDetails_qry = db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='" . $r['created_by'] . "'", db());
                                                                $empDetails_arr = array_shift($empDetails_qry);
                                                                $empDetails = getOwerHeadshotForMeeting($empDetails_arr['Headshot'], $empDetails_arr['initials']);
                                                            ?>
                                                                <tr>
                                                                    <td><span class="attendees_img" style="background-image:url('<?php echo $empDetails['emp_img']; ?>')"><?php echo $empDetails['emp_txt']; ?></span><span class="sr-only"><?php echo $r['name']; ?></span></td>
                                                                    <td class="td_w_95">
                                                                        <a class="edit_title_all" href="javascript:edit_issue(<?php echo $r['id']; ?>,'EDIT_ISSUE_MEETING')"><?php echo $r['issue']; ?></a>
                                                                    </td>
                                                                    <!--<td><input type="checkbox" class="checkbox_lg"/></td> -->
                                                                    <td><i class="deleteIssueCreateMeet fa fa-trash-o fa-lg" issue_id="<?php echo $r['id'] ?>"></i></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                    <div id="no_issue_available_div_create_meet" class="text-center no_issue_div justify-content-start  <?php echo $no_data_div; ?>">
                                                        <img src="assets_new_dashboard/img/no_issue.svg" class="no_issue_img" />
                                                        <p>No Current Issue</p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="add_issue_meeting" class="add_icon_m" add_type="issue" data-tooltip="true" data-placement="left" title="Add Issue" data-toggle="modal" data-target="#newIssue">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <input type="hidden" id="page_type_for_notification" value="dashboard_meeting_create" />
                <?php require_once("inc/footer_new_dashboard.php"); ?>
            </div>
        </div>



        <script>
            $(document).ready(function() {
                $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                $('.meetingTaskIssue').dataTable({
                    "searching": false,
                    info: false,
                    paging: false,
                    //rowReorder: true,
                    columnDefs: [{
                            orderable: true,
                            className: 'reorder',
                            targets: [0, 1]
                        },
                        {
                            orderable: false,
                            targets: [0, 2]
                        }
                    ]
                });
                $('#meetingProjects').dataTable({
                    "searching": false,
                    info: false,
                    paging: false,
                    //rowReorder: true,
                    columnDefs: [{
                            orderable: true,
                            className: 'reorder',
                            targets: [0, 1]
                        },
                        {
                            orderable: false,
                            targets: '_all'
                        }
                    ]
                });
                $('#metricsTable').dataTable({
                    "searching": false,
                    info: false,
                    paging: false,
                    rowReorder: {
                        update: false
                    },
                    columnDefs: [{
                        orderable: false,
                        targets: '_all'
                    }],
                    select: true
                });

                // Format options
                const optionFormat = (item) => {
                    if (!item.id) {
                        return item.text;
                    }

                    var span = document.createElement('span');
                    var template = '';
                    var subtitle = item.element.getAttribute('data-kt-rich-content-subcontent');
                    if (subtitle == "" || subtitle == null || subtitle == undefined) {
                        subtitle = '';
                    }
                    template += '<div class="d-flex align-items-center justify-content-between">';
                    template += '<div class="d-flex flex-column">'
                    template += '<span>' + item.text + '</span>';
                    template += '<span class="searchbox_subtitle">' + subtitle + '</span>';
                    template += '</div>';
                    var img_src = item.element.getAttribute('data-kt-rich-content-icon');
                    if (img_src !== "" && img_src !== undefined && img_src !== null) {
                        //template += '<span class="attendees_img"><img src="' + img_src + '" alt="' + item.text + '"/></span>';
                        template += '<span class="attendees_img float-right" style="background-image:url(' + img_src + ')"></span>';
                    } else {
                        var no_img = item.element.getAttribute('data-kt-rich-content-no-img');
                        if (no_img !== "" && no_img != undefined && no_img !== null) {
                            template += '<span class="attendees_img float-right bg-info"><span class="no_attendees_text">' + no_img + '</span></span>';
                        } else {
                            template += "";
                        }
                    }
                    template += '</div>';
                    span.innerHTML = template;

                    return $(span);
                }

                $('#reports_to').select2({
                    width: "100%",
                    templateSelection: optionFormat,
                    templateResult: optionFormat
                });


                $('#add_to_seat').select2({
                    width: '100%'
                });
                $('.whos_accountable').select2({
                    width: '100%'
                });
                $('.accountable_owner').select2({
                    width: '100%'
                });
                $('.marix_add_to_meetings').select2({
                    width: '100%'
                });
                $('.attendee_meeting').select2({
                    width: '100%'
                });

                const optionFormat1 = (item) => {
                    if (!item.id) {
                        return item.text;
                    }

                    var span = document.createElement('span');
                    var template = '';
                    template += '<div class="d-flex align-items-center">';
                    var img_src = item.element.getAttribute('data-kt-rich-content-icon');
                    var emp_txt = item.element.getAttribute('data-kt-rich-content-emp-txt');
                    template += '<span class="attendees_img bg-info" style="background-image:url(' + img_src + ')">' + emp_txt + '</span>';

                    template += '<span class="ml-1">' + item.text + '</span>';
                    template += '</div>';
                    span.innerHTML = template;

                    return $(span);
                }

                $('.search_existing_user_sel').select2({
                    width: "100%",
                    templateSelection: optionFormat1,
                    templateResult: optionFormat1
                });

                $('#permissionDropdown').on('hide.bs.dropdown', function(e) {
                    if (e.clickEvent) {
                        e.preventDefault();
                    }
                });

                $('.add_icon_m').click(function() {
                    var f = check_meeting_saved();
                    if (f == false) {
                        return false;
                    }

                })
                $(".add_icon_m").mouseenter(function() {
                    var add_type = $(this).attr('add_type');
                    if (add_type == 'attendee') {
                        $(this).html("<i class='fa fa-user'></i>");
                    } else if (add_type == "todos") {
                        $(this).html("<i class='fa fa-check'></i>");
                    } else if (add_type == "issue") {
                        $(this).html("<i class='fa fa-thumb-tack'></i>");
                    } else if (add_type == "measurable") {
                        $(this).html("<i class='fa fa-bullseye'></i>");
                    } else if (add_type == "project") {
                        $(this).html("<i class='fa fa-bullseye'></i>");
                    } else if (add_type == "headline") {
                        $(this).html("<i class='fa fa-bookmark-o'></i>");
                    }
                });
                $(".add_icon_m").mouseleave(function() {
                    $(this).html("<i class='fa fa-plus'></i>");
                });
                $('#placeholderUser').change(function() {
                    if ($(this).prop('checked')) {
                        $('#attendeeEmail').attr('placeholder', "Email");
                    } else {
                        $('#attendeeEmail').attr('placeholder', "Email (Optional)");
                    }
                });

                $('#placeholderUser').change(function() {
                    if ($(this).prop('checked')) {
                        $('#attendeeEmail').attr('placeholder', "Email");
                    } else {
                        $('#attendeeEmail').attr('placeholder', "Email (Optional)");
                    }
                });

                $(function() {
                    $('.mytooltip').tooltip();
                });

                //
                $('.show_average_check').change(function() {
                    if ($(this).prop('checked')) {
                        $(this).parent().siblings('.average_details').removeClass('d-none');
                    } else {
                        $(this).parent().siblings('.average_details').addClass('d-none');
                    }
                });
                $('.show_commulative_check').change(function() {
                    if ($(this).prop('checked')) {
                        $(this).parent().siblings('.commulative_details').removeClass('d-none');
                    } else {
                        $(this).parent().siblings('.commulative_details').addClass('d-none');
                    }
                });
                $('.show_formula_check').change(function() {
                    if ($(this).prop('checked')) {
                        $(this).parent().siblings('.formula_details').removeClass('d-none');
                    } else {
                        $(this).parent().siblings('.formula_details').addClass('d-none');
                    }
                });
                const optionFormatMetrics = (item) => {
                    if (!item.id) {
                        return item.text;
                    }
                    if (!item.value == 0) {
                        return item.text;
                    }
                    var span = document.createElement('span');
                    var template = '';
                    template += '<div class="d-flex align-items-center">';
                    var img_src = item.element.getAttribute('data-kt-rich-content-icon');
                    var emp_txt = item.element.getAttribute('data-kt-rich-content-emp-txt');
                    template += '<span class="attendees_img bg-info" style="background-image:url(' + img_src + ')">' + emp_txt + '</span>';
                    template += '<span class="ml-1">' + item.text + '</span>';
                    template += '<span class="ml-1 searchbox_subtitle">' + item.element.getAttribute('data-kt-rich-content-desc'); + '</span>';
                    template += '</div>';
                    span.innerHTML = template;

                    return $(span);
                }

                $('.search_existing_MetricsProject').select2({
                    width: "100%",
                    templateSelection: optionFormatMetrics,
                    templateResult: optionFormatMetrics
                });


                $('#save-meeting-name').click(function() {
                    var flag = true;
                    var meeting_name = $('#create_meeting_name').val();
                    if (meeting_name == "") {
                        $("#create_meeting_name_error").removeClass('d-none');
                        flag = false;
                    } else {
                        $("#create_meeting_name_error").addClass('d-none');
                    }
                    var hidden_meeting_id = $('#hidden_meeting_id').val();

                    if (flag == true) {
                        var all_data = {
                            meeting_name,
                            hidden_meeting_id,
                            'meeting_action': 'save-meeting-name'
                        }
                        $.ajax({
                            url: 'dashboard_meeting_action.php',
                            type: 'post',
                            data: all_data,
                            datatype: 'json',
                            async: false,
                            beforeSend: function() {
                                $('#save-meeting-name').attr('disabled', true);
                                $('#save-meeting-name').prev('.spinner').removeClass('d-none');
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.result == 0) {
                                    $('#meeting_exist_msg').removeClass('d-none');
                                } else {
                                    $('#meeting_exist_msg').addClass('d-none');
                                    //$('#hidden_meeting_id').val(res.meeting_id);
                                    // formSubmitMessage("Meeting Saved!");

                                    if (hidden_meeting_id == "") {
                                        $('#meeting_action_msg p').html("Meeting Created");
                                        window.location.href = "?meeting_id=" + res.meeting_id + "&&meet_create=1";
                                    } else {
                                        $('#meeting_action_msg p').html("Meeting Updated");
                                    }

                                }
                            },
                            complete: function() {
                                $('#meeting_action_msg').removeClass('d-none');
                                $('#save-meeting-name').attr('disabled', false).addClass('d-none');
                                $('#save-meeting-name').prev('.spinner').addClass('d-none');
                                display_hide_meeting_msg();
                            }
                        });
                    }
                    return false;
                });

                $('#addAttendeeCreateMeeting').click(function() {
                    var meeting_id = $('#hidden_meeting_id').val();
                    if (meeting_id != "") {
                        $.ajax({
                            url: 'dashboard_meeting_action.php',
                            type: 'post',
                            data: {
                                'meeting_attendees_list': 1,
                                meeting_id
                            },
                            datatype: 'json',
                            async: false,
                            beforeSend: function() {
                                $('#search_existing_user').html('<option data-kt-rich-content-icon="">Loading.....</option>');
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.length == 0) {
                                    var user_option = "<option>No User Found...</option>";
                                } else {
                                    var user_option = "<option></option>";
                                    $.each(res, function(i, data) {
                                        user_option += '<option value="' + data.emp_b2b_id + '" data-kt-rich-content-icon="' + data.emp_img + '"  data-kt-rich-content-emp-txt="' + data.emp_txt + '">' + data.empname + '</option>';
                                    });
                                }
                                $('#search_existing_user').html(user_option);
                            }
                        });
                    }
                });
                $('#add_existing_attendees').submit(function() {
                    var attendees = $('#search_existing_user').val();
                    var meeting_id = $('#hidden_meeting_id').val();
                    $.ajax({
                        url: 'dashboard_meeting_action.php',
                        type: 'post',
                        data: {
                            'add_meeting_attendees': 1,
                            attendees,
                            meeting_id
                        },
                        datatype: 'json',
                        async: false,
                        beforeSend: function() {
                            $('#save-existing-attendees').attr('disabled', true);
                            $('#save-existing-attendees').prev('.spinner').removeClass('d-none');
                        },
                        success: function(response) {
                            displayAttendeeDataAfterMeetingAction(JSON.parse(response));
                        },
                        complete: function() {
                            $('#save-existing-attendees').attr('disabled', false);
                            $('#save-existing-attendees').prev('.spinner').addClass('d-none');
                            $('#addAttendeesModalMeetCreate').modal('hide');
                            $("#search_existing_user").val("").trigger('change');
                            formSubmitMessage("Attendee Added!");
                        }
                    });
                });

                $(document).on('click', '.meeting_attendees_delete', function() {
                    var meeting_attendee_id = $(this).attr('att_id');
                    var meeting_id = $('#hidden_meeting_id').val();
                    $.ajax({
                        url: 'dashboard_meeting_action.php',
                        type: 'post',
                        data: {
                            'delete_meeting_attendees': 1,
                            meeting_attendee_id,
                            meeting_id
                        },
                        datatype: 'json',
                        async: false,
                        success: function(response) {
                            displayAttendeeDataAfterMeetingAction(JSON.parse(response));
                        },
                        complete: function() {
                            formSubmitMessage("Attendee Removed!");
                        }
                    })
                });

                $('#addNewProjectMeet').click(function() {
                    var meeting_id = $('#hidden_meeting_id').val();
                    $("#project_meetings_meet").val(meeting_id).trigger('change');
                    $.ajax({
                        url: 'project_action.php',
                        type: 'post',
                        data: {
                            'meeting_project_list': 1,
                            meeting_id
                        },
                        datatype: 'json',
                        async: false,
                        beforeSend: function() {
                            $('#searchExistingProject').html('<option value="0" data-kt-rich-content-icon="">Loading.....</option>');
                        },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.length == 0) {
                                var user_option = '<option value="0" data-kt-rich-content-icon=""  data-kt-rich-content-emp-txt="">No Project Found...</option>';
                            } else {
                                var user_option = "<option></option>";
                                $.each(res, function(i, data) {
                                    user_option += '<option value="' + data.project_id + '" data-kt-rich-content-icon="' + data.emp_img + '"  data-kt-rich-content-emp-txt="' + data.emp_txt + '" data-kt-rich-content-desc="Owner: ' + data.project_owner + '">' + data.project_name + '</option>';
                                });
                            }
                            $('#searchExistingProject').html(user_option);
                        }
                    });
                });

                $('#add_existing_project').submit(function() {
                    var project_id = $('#searchExistingProject').val();
                    var meeting_id = $('#hidden_meeting_id').val();
                    $.ajax({
                        url: 'project_action.php',
                        type: 'post',
                        data: {
                            'add_existing_project_meeting': 1,
                            project_id,
                            meeting_id
                        },
                        datatype: 'json',
                        async: false,
                        beforeSend: function() {
                            $('#save-existing-project').attr('disabled', true);
                            $('#save-existing-project').prev('.spinner').removeClass('d-none');
                        },
                        success: function(response) {
                            displayProjectDataAfterMeetingAction(JSON.parse(response));

                            $('#searchExistingProject').val('').trigger("change");

                        },
                        complete: function() {
                            $('#save-existing-project').attr('disabled', false);
                            $('#save-existing-project').prev('.spinner').addClass('d-none');
                            $('#addProjectModalMeetCreate').modal('hide');
                            formSubmitMessage("Project Added To Meeting!");
                        }
                    });
                })

                function GetFileSizeMeet() {
                    var fi = document.getElementById('uploadscanrep_meet'); // GET THE FILE INPUT.

                    if (fi.files.length > 0) {
                        for (var i = 0; i <= fi.files.length - 1; i++) {
                            var filenm = fi.files.item(i).name;

                            if (filenm.indexOf("#") > 0) {
                                $("#uploadscanrep_meet_error").removeClass('d-none');
                                $("#uploadscanrep_meet_error").text("Remove # from Scan file and then upload file!");
                                document.getElementById("uploadscanrep_meet").value = "";
                            }
                            if (filenm.indexOf("\'") > 0) {
                                $("#uploadscanrep_meet_error").removeClass('d-none');
                                $("#uploadscanrep_meet_error").text("Remove '\'' from Scan file " + filenm + " and then upload file!");
                                document.getElementById("uploadscanrep_meet").value = "";
                            }

                        }
                    } else {
                        $("#uploadscanrep_meet_error").addClass('d-none');
                    }

                }
                $("#form_project_meet").submit(function(e) {
                    var flag = true;
                    //e.preventDefault();
                    var project_title_meet = $('#project_title_meet').val();
                    var dept_id_meet = $('#dept_id_meet').val();
                    var project_priority_id_meet = $('#project_priority_id_meet').val();
                    var deadline_meet = $('#deadline_meet').val();
                    var description = $('#summernote-project-meet').summernote('code');
                    var pstatus_id_meet = $('#pstatus_id_meet').val();
                    var project_action_meet = $('#project_action_meet').val('ADD_FROM_MEET');
                    var flag = true;
                    if (project_title_meet == "") {
                        $("#project_title_meet_error").removeClass('d-none');
                        flag = false;
                    } else {
                        $("#project_title_meet_error").addClass('d-none');
                    }

                    if (dept_id_meet == "") {
                        $("#dept_id_meet_error").removeClass('d-none');
                        flag = false;
                    } else {
                        $("#dept_id_meet_error").addClass('d-none');
                    }
                    if (deadline_meet == "") {
                        $("#deadline_meet_error").removeClass('d-none');
                        flag = false;
                    } else {
                        $("#deadline_meet_error").addClass('d-none');
                    }
                    var project_action_meet = $('#project_action_meet').val();
                    if (flag == true) {
                        var all_data = new FormData(this);
                        all_data.append('project_desc', description);
                        var milestone_check_arr = $('#form_project_meet input[name="milestone_check"');
                        var milestone_check = [];
                        $.each(milestone_check_arr, function(k, v) {
                            var check = $(v).prop('checked') == true ? 1 : 0;
                            milestone_check.push(check);
                        });
                        all_data.append('milestone_check_box', JSON.stringify(milestone_check));
                        all_data.append('meeting_id', $('#hidden_meeting_id').val());
                        $.ajax({
                            url: 'project_action.php',
                            type: 'post',
                            data: all_data,
                            datatype: 'json',
                            contentType: false,
                            processData: false,
                            async: false,
                            beforeSend: function() {
                                $('#new-project-meet').attr('disabled', true);
                                $('#new-project-meet').prev('.spinner').removeClass('d-none');
                            },
                            success: function(response) {
                                var all_data = JSON.parse(response);
                                displayProjectDataAfterMeetingAction(all_data);
                                $('#addProjectModalMeetCreate').modal('hide');
                                formSubmitMessage("Project Added To Meeting!");
                            },
                            complete: function() {
                                $('#form_project_meet').trigger("reset");
                                $('#summernote-project-meet').summernote('reset');
                                $('#project_meetings_meet').val($('#hidden_meeting_id').val()).trigger("change");

                                $('.addMilestoneTable tbody').empty();
                                $('.addMilestoneTable').addClass('d-none');
                                $('#new-project-meet').attr('disabled', false);
                                $('#new-project-meet').prev('.spinner').addClass('d-none');
                                add_project('existing')
                            },
                        });
                    }
                    return false;
                });
            });

            function displayAttendeeDataAfterMeetingAction(res) {
                var attendee_tr = "";
                if (res.length == 0) {
                    $('#meetingCreateAttendeesList').removeClass('d-table').addClass('d-none');
                    $('#no_attendee_available_div_create_meet').removeClass('d-none').addClass('d-block');
                } else {
                    $.each(res, function(i, data) {
                        attendee_tr += '<tr>';
                        attendee_tr += '<td><span class="attendees_img" style="background-image:url(' + data.emp_img + ')">' + data.emp_txt + '</span></td>';
                        attendee_tr += '<td class="td_w_95"><span>' + data.empname + '</span></td>';
                        attendee_tr += '<td><i att_id=' + data.attendee_id + ' class="fa fa-trash-o fa-lg meeting_attendees_delete"></i></td>';
                        attendee_tr += '</tr>';
                    });
                }
                $('#meetingCreateAttendeesList').removeClass('d-none').addClass('d-table');
                $('#no_attendee_available_div_create_meet').removeClass('d-block').addClass('d-none');
                $('#meetingCreateAttendeesList tbody').html(attendee_tr);
            }


            function add_attendees(attendee) {
                if (attendee == "new") {
                    $("#addNewAteendees").removeClass('d-none');
                    $("#addExistingAteendees").addClass('d-none');
                } else {
                    $("#addExistingAteendees").removeClass('d-none');
                    $("#addNewAteendees").addClass('d-none');
                }
            }

            function add_metrics(metrics) {
                if (metrics == "new") {
                    $("#addNewMetrics").removeClass('d-none');
                    $("#addExistingMetrics").addClass('d-none');
                } else {
                    $("#addExistingMetrics").removeClass('d-none');
                    $("#addNewMetrics").addClass('d-none');
                }
            }

            function add_project(project) {
                if (project == "new") {
                    $("#addNewProject").removeClass('d-none');
                    $("#addExistingProject").addClass('d-none');
                } else {
                    $("#addExistingProject").removeClass('d-none');
                    $("#addNewProject").addClass('d-none');
                }
            }

            function check_meeting_saved(action = "") {
                var meeting_id = $('#hidden_meeting_id').val();
                if (meeting_id == "") {
                    $('#create-meeting-tab .nav-link').removeClass('active');
                    $('#create_meeting_main .tab-pane').removeClass('show').removeClass('active');
                    $('#basic-tab').addClass('active');
                    $('#basic').addClass('show active');
                    $('#blank_meeting_msg').fadeIn();
                    setTimeout(function() {
                        $('#blank_meeting_msg').fadeOut(3000);
                    }, 3000);
                    return false;
                } else if (meeting_id != "" && action == "archive-meeting") {
                    var conf = confirm("Do you sure want to Archive Current Meeting ");
                    if (conf == true) {
                        $.ajax({
                            type: 'GET',
                            url: 'dashboard_meeting_action.php',
                            data: {
                                archive_meeting: 1,
                                meeting_id
                            },
                            async: false,
                            success: function(response) {}
                        })
                    } else {
                        return false;
                    }
                }
            }



            function deleteMatrix(currentRow, delMatrixID) {
                if (delMatrixID) {
                    $.ajax({
                        type: 'POST',
                        url: 'dashboard_meeting_action.php',
                        cache: true,
                        data: {
                            'das_meeting_matrics': 'deleteMatrix',
                            'matrixID': delMatrixID,
                            'meetingID': '<?= isset($meeting_id) && $meeting_id != '' ? $meeting_id : 0 ?>'
                        },
                        success: function(response) {
                            console.log(response);
                            const matrixDeleteResponse = JSON.parse(response);
                            if (matrixDeleteResponse['status'] === 'Success') {
                                currentRow.parents('tr').remove();
                                formSubmitMessage("Deleted Successfully!");
                            }

                            if ($("#metricsTable > tbody > tr").length === 0) {
                                $('#metrics #metricsTable').remove();
                                $('#metrics .matrix_main_inner_content').append(`
                            <div class="px-3 no_issue_div mt-5">
                                <img src="assets_new_dashboard/img/icon_metrics-stats.svg" class="no_issue_img mb-0"/>
                                <p>No Current Measurables.</p>
                            </div>
                            `);
                            }
                        }
                    })
                }
            }

            display_hide_meeting_msg();

            function display_hide_meeting_msg() {
                setTimeout(function() {
                    /* var url = window.location.href;
                     var splitted_url=url.split("?");
                     var new_url=splitted_url[0]+'?'+splitted_url[1].split('&&')[0];
                     window.history.replaceState(null, null, new_url);
                     */
                    $('#meeting_action_msg').addClass('d-none');
                    $('#save-meeting-name').removeClass('d-none');
                }, 3000);

            }
            check_meeting_started();
            setInterval(check_meeting_started, 10000);
        </script>
        </body>

        </html>