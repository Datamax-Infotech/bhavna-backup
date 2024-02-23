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
            $meeting_id= isset($_GET['mid']) && $_GET['mid']!="" ? new_dash_decrypt($_GET['mid']) :"";
            if($meeting_id!=""){
                $sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db_project_mgmt());
                $meeting_name= array_shift($sql_main)['meeting_name'];
                $meeting_timer_id=isset($_GET['tid']) && $_GET['tid']!="" ? new_dash_decrypt($_GET['tid']) :"";
                $conclusion_data=display_meeting_conclusion_data($meeting_id,$meeting_timer_id);
                ?> 
                <div class="container-fluid p-0 mt-0" >
                    <div class="card py-3 px-4 d-flex flex-row align-items-center justify-content-between">
                        <h3><?php echo $meeting_name; ?></h3> <a class="btn btn-primary btn-sm" href="dashboard_meetings.php?meeting_flg=1">Back To Meetings</a>
                    </div>
                </div>
            <div class="container-fluid  mt-0 mb-4" >
                <div class="row justify-content-center mt-4">
                    <div class="col-md-10">
                        <div class="row">
                             <div class="col-md-12" id="meeting_end_msg">
                                <div class="alert alert-success">
                                    <b>Meeting Ended By Leader</b>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow p-5 d-flex justify-content-center align-items-center border-top-primary">
                                    <img src="assets_new_dashboard/img/issues-solved.svg" class="img-fluid"/>   
                                    <p>ISSUES SOLVED</p>
                                    <h3><b><?php echo $conclusion_data['issue_solved']; ?></b></h3>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow p-5 d-flex justify-content-center align-items-center border-top-primary">
                                    <img src="assets_new_dashboard/img/todo-completion.svg" class="img-fluid"/>  
                                    <p>TO-DO COMPLETION</p>
                                    <h3><b><?= $conclusion_data['todo'];?></b></h3>    
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="card shadow p-5 d-flex justify-content-center align-items-center border-top-primary">
                                    <img src="assets_new_dashboard/img/average-rating.svg" class="img-fluid"/>   
                                    <p>AVERAGE RATING</p> 
                                    <h3><b><?= $conclusion_data['rating']; ?></b></h3>  
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="card shadow p-5 d-flex justify-content-center align-items-center border-top-primary">
                                    <img src="assets_new_dashboard/img/minutes.svg" class="img-fluid"/> 
                                    <p><?= $conclusion_data['time']; ?></p>  
                                    <h3><b><?= $conclusion_data['minutes']; ?></b></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php  }else{?>
            <div class="col-md-12 alert alert-danger">
                <p class="mb-0"><a href="dashboard_meetings.php"><b>Click</b></a> here to choose the Meeting First!</p>
            </div>
        <?php } ?>
        <?php require_once("inc/footer_new_dashboard.php");?> 
        </div>
        </div>
	</div>

  
    <script>
        $('#meeting_end_msg').fadeIn();
        setTimeout(function() { $('#meeting_end_msg').fadeOut(3000); }, 3000);
    </script>
</body>

</html>