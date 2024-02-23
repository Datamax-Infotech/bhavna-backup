<?php

ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");

if(isset($_REQUEST) == 'POST'){

    $status = $_REQUEST['status'];

    // echo "<pre>";
    // print_r($_REQUEST);

    $emp_b2b_id = $_COOKIE['b2b_id'];


    switch($status) {
        case 'vtoCommonFeildData':
            // print_r($_REQUEST);
            $commonFieldID = decrypt($_REQUEST['feildValueID']);
            $commonFieldValue = $_REQUEST['feildValue'];
            $feildColumnName = $_REQUEST['feildColumnName'];
            
            if(isset($commonFieldID) && $commonFieldID != '' && isset($commonFieldValue) && $commonFieldValue != '' && isset($feildColumnName) && $feildColumnName != ''){
                $sql = db_query("UPDATE `vto_master` SET `".$feildColumnName."` = '".$commonFieldValue."' WHERE `vto_master`.`id` = ".$commonFieldID."", db_project_mgmt());
            }
        break;

        case 'insertVisionANDGoal':
            $trCount = $_REQUEST['trlength'];
            $comingfrom = $_REQUEST['comingFrom'];

            if(isset($trCount) && $trCount != ''){
                $gettotalRowSQL = db_query("SELECT COUNT(id) as idCount,MAX(id) as lastID FROM `vto_master` WHERE id > 4", db_project_mgmt());
                while($value = array_shift($gettotalRowSQL)){
                    $countDBValue = $value['idCount'];
                    $lastID = $value['lastID'];
                }
                if((int)$countDBValue === (int)$trCount){
                    $addIDForNewRow = $lastID+1;
                    if($comingfrom === 'vision'){
                        $sqlVisionInsert = db_Query("INSERT INTO `vto_master`(`id`,`vision_SubHead`) VALUES ($addIDForNewRow,'Measurables:')",db_project_mgmt());
                    }elseif($comingfrom === 'gaol'){
                        $sqlGoalInsert = db_Query("INSERT INTO `vto_master`(`id`,`goal_SubHead`) VALUES ($addIDForNewRow,'Measurables:')",db_project_mgmt());
                    }elseif($comingfrom === 'visionlist'){
                        $sqlVisionListInsert = db_Query("INSERT INTO `vto_master`(`id`,`vision_list`) VALUES ($addIDForNewRow,'')",db_project_mgmt());
                    }elseif($comingfrom === 'goallist'){
                        $sqlGoalListInsert = db_Query("INSERT INTO `vto_master`(`id`,`goal_list`) VALUES ($addIDForNewRow,'')",db_project_mgmt());
                    }
                    echo encrypt($addIDForNewRow);
                }else{
                    if($comingfrom === 'vision'){
                        $visionSql = db_query("UPDATE `vto_master` SET `vision_SubHead` = 'Measurables:' WHERE `vto_master`.`id` = ".$lastID."", db_project_mgmt());
                    }elseif($comingfrom === 'gaol'){
                        $goalSql = db_query("UPDATE `vto_master` SET `goal_SubHead` = 'Measurables:' WHERE `vto_master`.`id` = ".$lastID."", db_project_mgmt());
                    }
                    // elseif($comingfrom === 'visionlist'){
                    //     $visionListSql = db_query("UPDATE `vto_master` SET `vision_list` = 'Measurables:' WHERE `vto_master`.`id` = ".$lastID."", db_project_mgmt());
                    // }elseif($comingfrom === 'goallist'){
                    //     $goalListSql = db_query("UPDATE `vto_master` SET `goal_list` = 'Measurables:' WHERE `vto_master`.`id` = ".$lastID."", db_project_mgmt());
                    // }
                    echo encrypt($lastID);
                }
            }            
        break;
    }
}

?>