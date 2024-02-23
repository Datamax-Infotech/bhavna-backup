<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	
	require ("mainfunctions/database.php");
	require ("mainfunctions/general-functions.php");
	require_once("inc/header_new_dashboard.php"); 

    $meeting_id= isset($_GET['meeting_id']) && $_GET['meeting_id']!="" ? new_dash_decrypt($_GET['meeting_id']) :"";
    ?>
    <div id="wrapper">
    <? require_once("inc/sidebar_new_dashboard.php"); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
            <div class="container-fluid p-0 create_meeting mt-0" >
                <div class="card py-3 px-4">
                    <div class="row align-items-center">
                        <? if($meeting_id==""){?>
                        <div class="col-md-12 alert alert-danger">
                            <p class="mb-0"><a href="dashboard_meetings.php"><b>Click here</b></a> to select the meeting first</p>
                        </div>
                        <? } else{
                            $sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
                            $meeting_name= array_shift($sql_main)['meeting_name'];
                        ?>
                        <div class="col-md-9">
                            <h3>Meeting Minutes: <small><?= $meeting_name; ?></small></h3>
                            <p class="meeting-minutes-filter">
                                Active Filters: 
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" onclick="meetingFilter('tableChangePageRow')" title="Start Meeting" filter_type="start-meeting"><i class="fa fa-caret-right"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Join Meeting" filter_type="join-meeting"><i class="fa fa-user"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Update Score" filter_type="update-score"><i class="fa fa-balance-scale"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Update Goal" filter_type="update-goal"><i class="fa fa-bullseye"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" onclick="meetingFilter('tableUpdateRow')" title="Update to-do" filter_type="update-toto"><i class="fa fa-check-square-o"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" onclick="meetingFilter('tableIssueRow')" title="Update Issue" filter_type="update-issue"><i class="fa fa-exclamation-circle"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Wrap-up Meeting" filter_type="wrap-up-meeting"><i class="fa fa-square"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Edit Note" filter_type="edit-note"><i class="fa fa-sticky-note"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Edit Weekly Meeting" filter_type="edit-weekly-meeting"><i class="fa fa-cog"></i></span>
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Delete Weekly Meeting" filter_type="delete-weekly-meeting"><i class="fa fa-trash"></i></span>
                                    | 
                                <span class="filter active-filter" data-tooltip="true" data-placement="bottom" title="Trasncriptions" filter_type="trasncriptions"><i class="fa fa-comment"></i></span>

                                <span class="mx-3"><a href="javascript:void(0)" onclick="meetingFilter('showAll')">Show All</a></span> | <span  class="mx-3"><a href="javascript:void(0)" onclick="meetingFilter('hideAll')">Hide All</a></span>
                            </p>
                        </div>
                        <div class="col-md-3 text-right">
                            <a class="btn btn-success btn-sm mb-2" style="width: 150px; align-self: end;" href="javascript:history.go(-1);">Back To Meeting</a>
                            <input type="text" placeholder="Search..." class="form-control form-control-sm"/>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row justify-content-center mt-4 mb-2">
                    <div class="col-md-11" id="meetingMinutesAccordion"></div>
                </div>
                <?php require_once("inc/footer_new_dashboard.php");?> 
            </div>   
	    </div>
       

	</div>
  
    <script>
    $(document).ready(function() { 
        //$('.btn-collapse').collapse({})
        //$('.btn-collapse').on('click', function () {
        $(document).on('click','.btn-collapse', function(){
             if($(this).hasClass('collapsed')){
                $(this).find('i').addClass('fa-caret-up').removeClass('fa-caret-down');
             }else{
                $(this).find('i').addClass('fa-caret-down').removeClass('fa-caret-up');
             }
        })
    });

    function loadMeetingMinutesTable(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Typical action to be performed when the document is ready:
                    document.getElementById("meetingMinutesAccordion").innerHTML = xhttp.responseText;
                }
            };
        xhttp.open("GET", "meeting_minutes_action.php?status=getCollapseTable&meeting_id=<?=$meeting_id?>", true);
        xhttp.send();
    }

    if("<?= $meeting_id !=""?>") window.onload = loadMeetingMinutesTable();

    function meetingFilter(actionName){
        if(actionName == 'hideAll' || actionName == 'showAll'){
            if(actionName == 'showAll'){
                $(`#meetingMinutesAccordion #meetingMinutesTable table tbody tr`).removeClass('d-none');
            }else{
                $(`#meetingMinutesAccordion #meetingMinutesTable table tbody tr`).addClass('d-none');
            }
        }else{
            $(`#meetingMinutesAccordion #meetingMinutesTable table tbody tr.${actionName}`).addClass('d-none');
        }
    }
           
    </script>   
</body>

</html>