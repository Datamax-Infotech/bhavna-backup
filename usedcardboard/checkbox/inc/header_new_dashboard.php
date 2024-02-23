<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">

    <meta name="author" content="">

    <title><? echo $_COOKIE["userinitials"];?> Dashboard</title>

   <link rel="icon" type="image/x-icon" href="assets_new_dashboard/img/ucb-logo.jpg">

    <link href="assets_new_dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link

        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"

        rel="stylesheet"/>

    <!-- Custom styles for this template-->
    <link href="assets_new_dashboard/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets_new_dashboard/css/custom.css" rel="stylesheet">
	<link href="assets_new_dashboard/css/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="assets_new_dashboard/css/summernote.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets_new_dashboard/css/select2.min.css" />
  	<link href="assets_new_dashboard/css/rowReorder.bootstrap4.min.css" rel="stylesheet"/>
  	<style>

		.navbar-nav .search .fa-search{

			margin-top:3px;

		}

		.navbar-nav .search_btn{

			padding:0px 8px;

		}

		.navbar-nav .search_text_link{

			text-decoration:underline;

		}

	</style>

</head>



<body id="page-top">

<?

	require_once('meeting_common_function.php');

	$sql = "SELECT Headshot, name FROM loop_employees where b2b_id = '". $_COOKIE['b2b_id'] . "'";

	$result = db_query($sql,db() );

	$hrow = array_shift($result);

	$emp_name=$hrow["name"] . $hrow["b2b_id"];

		if($hrow["Headshot"]!="")

		{

			$emp_img=$hrow["Headshot"];

		}

	else{

		$emp_img="new_header_noimg.jpg";//ucb_logo.php;.jpeg

	}



	//$project_count_sql=db_query("SELECT project_id FROM project_master where project_owner = '".$_COOKIE["b2b_id"]."' and archive_status = 0", db_project_mgmt());
	//$project_count=tep_db_num_rows($project_count_sql);

	$project_count=0;
	$sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
	where mm.status = 1 $meeting_filter GROUP By ma.meeting_id 
	union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name ", db_project_mgmt());
	while($main_row = array_shift($sql_main)){
		$meeting_id=$main_row['id'];
		$count_sql = db_query("SELECT project_id FROM project_master where find_in_set($meeting_id,meeting_ids) AND project_owner = '".$_COOKIE["b2b_id"]."' AND archive_status=0 ORDER BY project_id DESC", db_project_mgmt());
		$project_count += tep_db_num_rows($count_sql);								
	}

	//echo "SELECT id FROM task_master JOIN meeting_master ON meeting_master.id=task_master.task_meeting where task_assignto = '".$_COOKIE["b2b_id"]."' and archive_status=0 and meeting_status=1";
	//
	$task_count_sql=db_query("SELECT task_master.id FROM task_master right join meeting_master on meeting_master.id = task_master.task_meeting 
	where task_master.task_assignto = '".$_COOKIE["b2b_id"]."' and task_master.archive_status=0 and task_master.task_status = 0 and meeting_master.status = 1", db_project_mgmt());
	$task_count=tep_db_num_rows($task_count_sql);

	$task_count_sql=db_query("SELECT task_master.id FROM task_master where task_master.task_assignto = '".$_COOKIE["b2b_id"]."' 
	and task_master.archive_status=0 and task_master.task_status = 0 and task_master.task_meeting = 0", db_project_mgmt());
	$task_count = $task_count + tep_db_num_rows($task_count_sql);

	$issue_count_sql=db_query("SELECT issue_master.id FROM issue_master inner join meeting_master on meeting_master.id = issue_master.meeting_id 
	where issue_master.created_by = '".$_COOKIE["b2b_id"]."' and issue_master.status=1 and meeting_master.status = 1", db_project_mgmt());

	$issue_count=tep_db_num_rows($issue_count_sql);

?>

		<!-- Topbar -->

	<div class="header2">

		<nav class="navbar navbar-expand topbar static-top shadow">

			<!-- Sidebar Toggle (Topbar) -->

			<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">

				<i class="fa fa-bars"></i>

			</button>



			<!-- Topbar Search -->

			   <ul class="navbar-nav mr-auto">

					<!-- Sidebar - Brand -->

				<a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboardnew.php">

					<img src="assets_new_dashboard/img/ucb-logo.jpg"/>

				</a>

				 <li class="nav-item dropdown no-arrow active">

					<a class="nav-link dropdown-toggle" href="index.php" >

						LOOPS  

					</a>

				 </li>

				 <li class="nav-item dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="water_index.php" >

					   UCBZW 

					</a>

				</li>

				<li class="nav-item dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="https://b2c.usedcardboardboxes.com/" target="_blank" >

					   B2C

					</a>

				</li>

				<li class="nav-item dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="report_sop_new.php" target="_blank" >

					   SOPS

					</a>

				</li>

				<li class="nav-item dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="dashboardnew.php?show=links">

					   LINKS

					</a>

				</li>

				<li class="nav-item dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="dashboard_management_v1.php">

					   CheckBOX

					</a>

				</li>
				
			   </ul>

			<!--<form

				class="d-none d-sm-inline-block form-inline ml-md-2 my-2 my-md-0 mw-75 navbar-search">

				<div class="input-group">

					<input type="text" class="form-control bg-light header-search" placeholder="Search for..."

						aria-label="Search" aria-describedby="basic-addon2">

					<div class="input-group-append">

						<button class="btn search-button" type="button">

							Search

						</button>

					</div>

				</div>

			</form>-->

			

			<ul class="navbar-nav mr-auto"> 

				<li><? 

				include("search_box_fun.php");

				searchbox_new("dashboardnew.php",$eid);?>

				</li>

			</ul>



			<!-- Topbar Navbar -->

			<ul class="navbar-nav ml-auto"> 

				<li class="nav-item nav-link-not">

					<a class="nav-link mytooltip" id="new_project_header" href="#"  role="button" data-toggle="tooltip" data-placement="bottom" title="Create a New Project"> 

					   <i class="fas fa-plus-square fa-fw "></i>

					</a>

				</li>

				<li class="nav-item nav-link-not">

					<a class="nav-link mytooltip" id="new_issue_header" href="#"  role="button" data-toggle="tooltip" data-placement="bottom" title="Create a new Issue"> 

					   <i class="fas fa-exclamation-circle fa-fw "></i>

					</a>

				</li>

				<li class="nav-item nav-link-not">

					<a class="nav-link mytooltip"id="new_task_header" href="#"  role="button" data-toggle="tooltip" data-placement="bottom" title="Create a new Task" > 

					   <i class="fas fa-tasks fa-fw"></i>

					</a>

				</li>

				

			   <div class="topbar-divider d-none d-sm-block"></div>

				<li class="nav-item nav-link-not dropdown no-arrow">

					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"

						data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

						<span class="mr-2 d-none d-lg-inline"><?php echo $emp_name ; // this is the b2b.employees ID number, not the loop_employees ID number?></span>

						<img class="img-profile rounded-circle" src="images/employees/<? echo $emp_img; ?> ">

					</a>

					<!-- Dropdown - User Information -->

					<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"

						aria-labelledby="userDropdown">

						<!--<a class="dropdown-item" href="#">

							<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>

							Manage Organization

						</a> -->

						<a class="dropdown-item" href="change_password.php">

							<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>

							Change Password

						</a>

						<div class="dropdown-divider"></div>

						<a class="dropdown-item" href="logoff.php">

							<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

							Logout

						</a>

					</div>

				</li>





			</ul>



		</nav>

		</div>

		<!-- End of Topbar -->

    <!-- Page Wrapper -->

