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
                    <div class="card shadow mb-4">
                    <?php $count=10; 
                    if($count==0){?>
                    <div class="card-body min_height_500 d-flex justify-content-center align-items-center text-center">
                        <div>
                            <img src="assets_new_dashboard/img/icon_metrics-stats.svg" class="img-fluid"/>
                            <h4 class="mt-2"><b>No Metrics</b></h4>
                        </div>
                    </div>
                    <?php }else{

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
                            <h6><b>Scorecard</b></h6>
                            <div class="table-responsive">
                                <table id="meetingMetricsTable" class="table table-sm text-center meetingMetricsTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="bg-white"></th>
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
                                        $scorecard_data_sql = "SELECT scorecard.id,scorecard.b2b_id,scorecard.name,scorecard.units,scorecard.goal,scorecard.goal_matric,Headshot,initials FROM scorecard JOIN loop_employees ON scorecard.b2b_id=loop_employees.b2b_id WHERE (scorecard.attach_meeting like '%-".$meeting_id."-%' OR scorecard.attach_meeting like '%-".$meeting_id."' OR scorecard.attach_meeting like '".$meeting_id."-%' OR scorecard.attach_meeting = ".$meeting_id.") AND (scorecard.archived = false) ORDER BY scorecard.meeting_create_order_no ASC";
                                        $scorecard_data_query = db_query($scorecard_data_sql,db());
                                        while($scorecard_data = array_shift($scorecard_data_query)){

                                        $scorecard_weeks_id = $scorecard_data['id'];
                                        $scorecard_createdByID = $scorecard_data['b2b_id'];

                                        $scorecard_data_ImageFunc = getOwerHeadshotForMeeting($scorecard_data['Headshot'],$scorecard_data['initials']);
                                        $scorecardUserImage = $scorecard_data_ImageFunc['emp_img'];
                                        $scorecardUserText = $scorecard_data_ImageFunc['emp_txt'];
                                        ?>
                                            <tr data-sort-id="<?=new_dash_encrypt($scorecard_weeks_id)?>">
                                                <td><i class="fa fa-arrows"></i></td>
                                                <td class="matrics_attandees_img"><span class="attendees_img" style="background-image:url('<?=$scorecardUserImage?>')"><?=$scorecardUserText?></span></td>
                                                <td class="td-border-bottom matrics_mesurable_name text-left">
                                                    <a id="measurableModal" type="button" class="" data-toggle="modal"
                                                        data-target="#scorecardAddMatrixModalPopop"
                                                        data-whatever="<?=new_dash_encrypt($scorecard_weeks_id)?>" data-todo='{"EditingFrom":"meetingStartMatrix"}'><?=$scorecard_data['name']?></a>
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
                                                                    $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','".$meeting_scorecard_week_id."','Update','meetingMatrics')";
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
                                                            $onblur = "matrix_edit_content($(this),'".new_dash_encrypt($scorecard_weeks_id)."','".$scorecard_createdByID."','0','Insert','meetingMatrics')";
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
                    <?php  } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php }else{?>
            <div class="col-md-12 alert alert-danger">
                <p class="mb-0"><a href="dashboard_meetings.php"><b>Click</b></a> here to choose the Meeting First!</p>
            </div>
        <?php } ?>
        <?php 
            require_once("inc/footer_new_dashboard.php");
        ?>
    </div>
	</div>

    <?   
    require_once('meeting_start_common_top_create.php');
    ?>  
    
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>    
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function() { 
      $('#metrics').addClass('active_user_page');
      $('#meetingMetricsTable').dataTable({
            "searching": false,
            info: false,
            paging: false,
            bJQueryUI: false,
            fixedColumns: {
                left: 5,
            },
           scrollCollapse: true,
            rowReorder: {
                update: false
            },
            columnDefs: [
                { orderable: false, targets: '_all' }
            ],
           /* select: true */
        });

       $('#meetingMetricsTable tbody').sortable({
        handle: 'i.fa-arrows',
        placeholder: "ui-state-highlight",
        update : function () {
            var order = $('#meetingMetricsTable tbody').sortable('toArray', { attribute: 'data-sort-id'});
            sortOrder = order.join(',');
            $.post(
                'dashboard_meeting_action.php',
                {'matircs_sort_action':'matricsTableOrdering','sortOrder':sortOrder,'meetingTimerID' : '<?=isset($_GET['meeting_timer_id']) ? $_GET['meeting_timer_id'] : 0 ?>'},
                function(data){
                    console.log(data);
                    if(data==1){
                        formSubmitMessage("Meeting Matrics Order Updated!");
                    }
                }
            );
        }
    });
    });

    
    </script>
    
    <style>
    #meetingMetricsTable th,#meetingMetricsTable td { white-space: nowrap; }
    #meetingMetricsTable div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    #meetingMetricsTable .matrics_mesurable_name:hover a{
        font-weight: 700;
    } 
    </style>
</body>

</html>