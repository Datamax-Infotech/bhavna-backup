<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once("../mainfunctions/database.php");
require_once("../mainfunctions/general-functions.php");
require('../meeting_common_function.php');

if(isset($_REQUEST) == 'POST'){

$status = $_REQUEST['status'];

// echo "<pre>";
// print_r($_REQUEST);

$emp_b2b_id = $_COOKIE['b2b_id'];


switch($status) {

    case "deleteMesurement":
    $mesurementDeleteID = new_dash_decrypt($_REQUEST['mesurementID']);
    if(isset($mesurementDeleteID) && $mesurementDeleteID != ''){
        $mesureDeleteSQL = "UPDATE `scorecard` SET `archived` = true WHERE `scorecard`.`id` = ".$mesurementDeleteID."";
        $mesureDeleteQuery = db_query($mesureDeleteSQL,db_project_mgmt());
        echo json_encode(['status' => 'Deleted']);
    }else{
        echo json_encode(['status' => 'failed','message' => "Failed to Delete Mesurement"]);
    }
    break;
   
    case "add_modal_data":

        // print_r($_REQUEST);

        $attachMeeting = isset($_REQUEST['attach_to_meeting']) && $_REQUEST['attach_to_meeting'] != '' ? implode('-' , $_REQUEST['attach_to_meeting']) : '';

        if(isset($_REQUEST['between_goal_matric']) && $_REQUEST['between_goal_matric'] != ''){
            $between_goal_matric = $_REQUEST['between_goal_matric']."-".$_REQUEST['goal_matric'];
        }else{
            $between_goal_matric = $_REQUEST['goal_matric'];
        }

        $score_modal_data_sql = "INSERT INTO `scorecard`(`id`,`b2b_id`, `name`, `accountable`, `units`, `goal`, `goal_matric`, `attach_meeting`)
        VALUES ('',$emp_b2b_id,'".$_REQUEST['name']."','".$_REQUEST['accountable']."','".$_REQUEST['units']."','".$_REQUEST['goals']."','".$between_goal_matric."','".$attachMeeting."')";
        $score_modal_data_query = db_query($score_modal_data_sql,db_project_mgmt());

        if(empty($score_modal_data_query)){
            $getInsertedDataIDSQL = 'SELECT scorecard.id,scorecard.b2b_id, accountable FROM scorecard ORDER by id DESC LIMIT 1';
            $getInsertedDataIDQuery = db_query($getInsertedDataIDSQL,db_project_mgmt());
            $getInsertedDataID = array_shift($getInsertedDataIDQuery);
            if(isset($getInsertedDataID['id']) && $getInsertedDataID['id'] != ''){
                $insertedDataID = $getInsertedDataID['id'];
                $insertedDataOwner = $getInsertedDataID['b2b_id'];
                $insertedDataName = $_REQUEST['name'];
                $insertedGoal = $_REQUEST['goals'] == '==' ? '=' : $_REQUEST['goals'];

                $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$getInsertedDataID['accountable']."'",db());
                $empDetails_arr=array_shift($empDetails_qry);
                $insertGetImageFunc=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                $insertUserImage = $insertGetImageFunc['emp_img'];
                $insertUserText = $insertGetImageFunc['emp_txt'];

                echo json_encode([
                    'status' => 'Added',
                    'insertedDataID' => new_dash_encrypt($insertedDataID),
                    'insertedDataOwner' => $insertedDataOwner,
                    'insertedDataName' => $insertedDataName,
                    'insertedDataGoal' => $insertedGoal,
                    'insertedGoalMatrix' => $between_goal_matric,
                    'insertedUnits' => $_REQUEST['units'],
                    'inserted_goal_and_matric_and_units' => $insertedGoal." ".$between_goal_matric.$_REQUEST['units'],
                    'insertUserImage' => $insertUserImage,
                    'insertUserText' => $insertUserText,

                ]);

                if(isset($_REQUEST['editingfrom']) && $_REQUEST['editingfrom'] != ''){
                    if(isset($_REQUEST['meetingID']) && $_REQUEST['meetingID'] != '' && isset($_REQUEST['meeting_timer_id']) && $_REQUEST['meeting_timer_id'] != ''){
                        update_meeting_minutes(new_dash_decrypt($_REQUEST['meetingID']),new_dash_decrypt($_REQUEST['meeting_timer_id']),'Create Measurable','Measurable',$insertedDataID,$emp_b2b_id); 
                        db_query("UPDATE meeting_live_updates set metrics_flg=1 where meeting_timer_id=".new_dash_decrypt($_REQUEST['meeting_timer_id'])." AND attendee_id!=".$emp_b2b_id."",db_project_mgmt());
                    }
                }
            }else{
                echo json_encode(['status' => 'Failed','message' => 'Inserted ID Not Found']);
            }
        }else{
            echo json_encode(['status' => 'Failed','message' => 'Failed to Add Measurement']);
        }
    
    break;

    case "update_modal_data":

        $encryptedModalPopupID = $_REQUEST['modelpopupID'];

        $modalPopupID = new_dash_decrypt($encryptedModalPopupID);

        if(isset($modalPopupID) && $modalPopupID != ''){

            // print_r($_REQUEST);

            $updateattachMeeting = isset($_REQUEST['attach_to_meeting']) && $_REQUEST['attach_to_meeting'] != '' ? implode('-' , $_REQUEST['attach_to_meeting']) : '';

            if(isset($_REQUEST['between_goal_matric'])  && $_REQUEST['between_goal_matric'] != ''){
                $between_goal_matric = $_REQUEST['between_goal_matric']."-".$_REQUEST['goal_matric'];
            }else{
                $between_goal_matric = $_REQUEST['goal_matric'];
            }

            if(isset($_REQUEST['changeMatricsValue'])  && $_REQUEST['changeMatricsValue'] != ''){
                $getGoalMatricsSql = db_query('SELECT id,goal_matric from scorecard WHERE id = '.$modalPopupID.'',db_project_mgmt());
                if(!empty($getGoalMatricsSql)){
                    $getGoalMatricsSHift = array_shift($getGoalMatricsSql);
                    
                    if($_REQUEST['changeMatricsValue'] === 'futureWeek'){   
                        $setOldGoalMatircsValue =  $getGoalMatricsSHift['goal_matric'];
                        $changeTileWithNewGaolMatrics = '';
                    }else{
                        $setOldGoalMatircsValue =  0;
                        $changeTileWithNewGaolMatrics = 'changeTile';
                    }
                
                    $updateGoalMatircs = db_query("UPDATE `scorecard` SET `old_goal_matric` = '".$setOldGoalMatircsValue."' WHERE id = '".$modalPopupID."'",db_project_mgmt());
                }
            }
            
            $score_modal_update_data_sql = "UPDATE `scorecard` SET `name` = '".$_REQUEST['name']."',`accountable` = '".$_REQUEST['accountable']."',`units` = '".$_REQUEST['units']."',`goal` = '".$_REQUEST['goals']."',`goal_matric` = '".$between_goal_matric."',`attach_meeting` = '".$updateattachMeeting."' WHERE `scorecard`.`id` = '".$modalPopupID."'";
            $score_modal_update_data_query = db_query($score_modal_update_data_sql,db_project_mgmt());

            $measurable_name = $_REQUEST['name'];
            $measurable_goal = $_REQUEST['goals'] == '==' ? '=' : $_REQUEST['goals'];
            $measurable_goal_and_matric_and_units = $_REQUEST['units'] == '%' ? $measurable_goal." ".$between_goal_matric.$_REQUEST['units'] : $measurable_goal." ".$_REQUEST['units'].$between_goal_matric ;
            
            $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$_REQUEST['accountable']."'",db());
            $empDetails_arr=array_shift($empDetails_qry);
            $insertGetImageFunc=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
            $insertUserImage = $insertGetImageFunc['emp_img'];
            $insertUserText = $insertGetImageFunc['emp_txt'];

            echo json_encode([
                'status' => 'Updated',
                'measurable_name' => $measurable_name,
                'measurable_goal' => $measurable_goal,
                'measurable_matrix' => $between_goal_matric,
                'changeTileWithNewGaolMatrics' => $changeTileWithNewGaolMatrics,
                'measurable_units' => $_REQUEST['units'],
                'measurable_goal_and_matric_and_units' => $measurable_goal_and_matric_and_units,
                'insertUserImage' => $insertUserImage,
                'insertUserText' => $insertUserText,
            ]);

            if(isset($_REQUEST['editingfrom']) && $_REQUEST['editingfrom'] != ''){
                if(isset($_REQUEST['meetingID']) && $_REQUEST['meetingID'] != '' && isset($_REQUEST['meeting_timer_id']) && $_REQUEST['meeting_timer_id'] != ''){
                    // update_meeting_minutes(new_dash_decrypt($_REQUEST['meetingID']),new_dash_decrypt($_REQUEST['meeting_timer_id']),'Mesurement','Mesurement Updated',$modalPopupID,$emp_b2b_id); 
                    db_query("UPDATE meeting_live_updates set metrics_flg=1 where meeting_timer_id=".new_dash_decrypt($_REQUEST['meeting_timer_id'])." AND attendee_id!=".$emp_b2b_id."",db_project_mgmt());
                }
            }

        }
    
    break;

    case "update_tile":

        // print_r($_REQUEST);

        $tilerowID = new_dash_decrypt($_REQUEST['tilerowID']);
        $tileWeek = $_REQUEST['tileWeek'];
        $tile_value = (int)filter_var($_REQUEST['tile_value'], FILTER_SANITIZE_NUMBER_INT); 
        $scorecard_MessurableID = new_dash_decrypt($_REQUEST['measurableID']);
        $scorecard_createdByID = $_REQUEST['createdBy'];
        $checkInsertORUpdate = $_REQUEST['type'];
        $updateWeekID = $_REQUEST['weekID'];

        if($scorecard_MessurableID != '' && $scorecard_createdByID != '' && $tile_value != ''){
            $sql = db_query("SELECT * FROM `scorecard` WHERE id = ".$scorecard_MessurableID."",db_project_mgmt());
            while($sqlData = array_shift($sql)){
                $id = $sqlData['id'];
                $name = $sqlData['name'];
                $units = $sqlData['units'];
                $goal = $sqlData['goal'];
                $goal_matric = $sqlData['goal_matric'];
            }

            if(isset($goal_matric) && $goal_matric != ''){
                if($goal == '<=>'){
                    $between_num = $goal_matric;
                    $between_eploded_value = explode('-',$between_num);
                    $between_min = $between_eploded_value[0];
                    $between_max = $between_eploded_value[1];
                    if($between_min <= $tile_value && $tile_value <= $between_max){
                        $box_color = 'td-success';
                    }else{
                        $box_color = 'td-danger';
                    }
                }else{
                    $condition = "$tile_value $goal $goal_matric";
                    if (eval("return ($condition);")) {
                        $box_color = 'td-success';
                    } else {
                        $box_color = 'td-danger';
                    }
                }   
            }

            if($checkInsertORUpdate == 'Insert' && $updateWeekID == 0){
                $insertTile = db_query("INSERT INTO `meeting_scorecard_week_data` (`id`, `scorecard_id`, `scorecard_created_by`, `weeks`, `value`, `created_at`, `updated_at`) VALUES ('', '".$scorecard_MessurableID."', '".$scorecard_createdByID."', '".$tileWeek."', '".$tile_value."', CURRENT_TIMESTAMP, '0000-00-00 00:00:00.000000')",db_project_mgmt());

                // $tileStatus = "Mesurement Tile Inserted";
                // $tileUpdateMsg = "Mesurement $name tile week $tileWeek inserted";
                
            }else{
                $updateTile = db_query("UPDATE `meeting_scorecard_week_data` SET `value` = '".$tile_value."' WHERE `meeting_scorecard_week_data`.`id` = ".$updateWeekID."",db_project_mgmt());
                // $tileStatus = "Mesurement Tile Updated";
                // $tileUpdateMsg = "Mesurement $name tile week $tileWeek updated";
            }

            if(empty($insertTile) || empty($updateTile)){
                if($units === '%'){
                    $tile_value_and_units = $tile_value.$units;
                }else{
                    $tile_value_and_units = $units.$tile_value;
                }
                echo json_encode(['status' => 'Updated','boxColor' => $box_color,'tileValue' => $tile_value_and_units]);

                if(isset($_REQUEST['editingfrom']) && $_REQUEST['editingfrom'] != '' && ($_REQUEST['editingfrom'] == 'meetingStartMatrix' ||  $_REQUEST['editingfrom']=="meetingMatrics")){
                    if(isset($_REQUEST['meetingID']) && $_REQUEST['meetingID'] != '' && isset($_REQUEST['meeting_timer_id']) && $_REQUEST['meeting_timer_id'] != ''){
                        // update_meeting_minutes(new_dash_decrypt($_REQUEST['meetingID']),new_dash_decrypt($_REQUEST['meeting_timer_id']),'Mesurement',$tileStatus,$tilerowID,$emp_b2b_id,$tileUpdateMsg); 
                        db_query("UPDATE meeting_live_updates set metrics_flg=1 where meeting_timer_id=".new_dash_decrypt($_REQUEST['meeting_timer_id'])." AND attendee_id!=".$emp_b2b_id."",db_project_mgmt());
                    }
                }
            }

        }

    break;

    case "fetchMeasurementData":

        $modalID = new_dash_decrypt($_REQUEST['modalID']);
        
        if(isset($modalID) && $modalID != ''){
            $sql = "SELECT * from scorecard where id=".$modalID."";
            $sql_query = db_query($sql , db_project_mgmt());
            $data = array_shift($sql_query);
            echo json_encode($data);
        }


    break;

    case "existingMeasurable":

        $existMeasurementID = $_REQUEST['existMeasurementID'];
        if(isset($_REQUEST['currentmeetingID']) && $_REQUEST['currentmeetingID'] != 0){
            $currentMeetinID = $_REQUEST['currentmeetingID'];
            if(isset($existMeasurementID) && !empty($existMeasurementID)){
                foreach($existMeasurementID as $value){
                    $getExistingSelectedSQL = db_query("SELECT scorecard.id,scorecard.name,scorecard.units,scorecard.goal,scorecard.goal_matric,scorecard.attach_meeting, accountable FROM scorecard WHERE scorecard.id = ".$value."" , db_project_mgmt());
                    if(!empty($getExistingSelectedSQL)){
                        $existingSelectedData = array_shift($getExistingSelectedSQL);
                        $existingSelectedID[] = new_dash_encrypt($existingSelectedData['id']);
                        $existingSelectedName[] = $existingSelectedData['name'];
                        $existingSelectedUnits[] = $existingSelectedData['units'];
                        $existingSelectedGoal[] = $existingSelectedData['goal'] === '==' ? '=' : $existingSelectedData['goal'];
                        $existingSelectedGoalMatrix[] = $existingSelectedData['goal_matric'];
                        $empDetails_qry=db_query("SELECT Headshot, name,initials from loop_employees where b2b_id='".$existingSelectedData['accountable']."'",db());
                        $empDetails_arr=array_shift($empDetails_qry);
                        $existingSelectedGetImageFunc=getOwerHeadshotForMeeting($empDetails_arr['Headshot'],$empDetails_arr['initials']); 
                        $existingSelectedImage = $existingSelectedGetImageFunc['emp_img'];
                        $existingSelectedText = $existingSelectedGetImageFunc['emp_txt'];

                        if(isset($existingSelectedData['attach_meeting']) && $existingSelectedData['attach_meeting'] != ''){
                            $explodedArray = explode('-', $existingSelectedData['attach_meeting']);
                            array_push($explodedArray,$currentMeetinID);
                            $implodedArray = implode('-',$explodedArray);
                            $db = db_query("UPDATE scorecard SET attach_meeting = '".$implodedArray."' WHERE id = ".$value."",db_project_mgmt());
                        }else{
                            $db = db_query("UPDATE scorecard SET attach_meeting = '".$currentMeetinID."' WHERE id = ".$value."",db_project_mgmt());
                        }

                    }
                }

                echo json_encode([
                    'status'=> 'meetingUpdatedInCurrentMatrix',
                    'existingSelectedID' => $existingSelectedID,
                    'existingSelectedName' => $existingSelectedName,
                    'existingSelectedUnits' => $existingSelectedUnits,
                    'existingSelectedGoal' => $existingSelectedGoal,
                    'existingSelectedGoalMatrix' => $existingSelectedGoalMatrix,
                    'existingSelectedImage' => $existingSelectedImage,
                    'existingSelectedText' => $existingSelectedText
                ]);
            }
        }

    break;
    
    case 'getMatrixNotInMeetingID':

        $meetingID = $_REQUEST['meetingID'];
        if(isset($meetingID) && $meetingID != 0){
            $getScorecardNameNotInMeetingIDSQL = db_query("SELECT id,name FROM `scorecard` where (attach_meeting NOT LIKE '%".$meetingID."%') AND (attach_meeting != ".$meetingID.")", db_project_mgmt());
            while ($getScorecardNameNotInMeetingID = array_shift($getScorecardNameNotInMeetingIDSQL)) {
                $getMeasurementID[] = $getScorecardNameNotInMeetingID["id"];
                $getMeasurementName[] = $getScorecardNameNotInMeetingID["name"];
            }

            echo json_encode([
                'status'=> 'getMatrixNotInMeetingID',
                'getMeasurementID' => $getMeasurementID,
                'getMeasurementName' => $getMeasurementName
            ]);

        }
        
    break;

    default :
    echo "No response";
}

}

?>
