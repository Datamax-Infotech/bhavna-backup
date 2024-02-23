<?php session_start();
	if ($_REQUEST["no_sess"]=="yes"){

	}else{
		require ("inc/header_session.php");
	}	

	require ("mainfunctions/database.php");

	require ("mainfunctions/general-functions.php");
	require ("inc/functions_mysqli.php"); 
	require ("function-dashboard-newlinks.php"); 
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
                <?php 
                $top_links=""; $sidebar_links ="common_sidebar_links";
                require("meeting_start_common_links.php");?>
                <div class="col-md-10">
                    <div class="card shadow mb-4">
                        <div class="card-body min_height_500 d-flex justify-content-center align-items-center text-center">
                            <div>
                                <img src="assets_new_dashboard/img/segue.svg" class="img-fluid"/>
                                <h4 class="mt-4"><b>Check-in</b></h4>
                                <p class="mb-1">Share good news from the last 7 days.</p>
                                <p>One personal and one business.</p>
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
        
    <? require_once("inc/footer_new_dashboard.php"); ?>
        </div>
        
	</div>
    
	</div>

    <?php 
    require_once("meeting_start_common_top_create.php");
    ?>  
    <script>
        $(document).ready(function() { 
            $('#check-in').addClass('active_user_page');
        });
        </script>
</body>

</html>