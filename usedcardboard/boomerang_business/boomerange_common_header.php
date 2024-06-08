<?php $sales_rep_login = "no"; $repchk=0; ?>
<!DOCTYPE HTML>
<html>
    <head>
	<title>UsedCardboardBoxes B2B Customer Portal</title>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel='stylesheet' type='text/css' href='assets/css/new_header-dashboard.css'>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var timerStart = Date.now();
       
        // Collapse navbar when clicking outside
        $(document).click(function(event) {
            var clickover = $(event.target);
            var _opened = $("#navbarNavTop").hasClass("show");
            if (_opened === true && !clickover.closest('.navbar-collapse').length) {
                $("button.navbar-toggler").click();
            }
        });
    </script>
	
	<!-- TOOLTIP STYLE START -->
	<link rel="stylesheet" type="text/css" href="css/tooltip_style.css" /> 
	<!-- TOOLTIP STYLE END -->
	<? /*if (!isset($_REQUEST["hd_chgpwd"])) { ?>
	<script language="JavaScript" SRC="inc/NewCalendarPopup.js"></script>
	<script language="JavaScript" SRC="inc/general.js"></script>
	<script language="JavaScript">document.write(getCalendarStyles());</script>
	<script language="JavaScript" >
		var cal1xx = new CalendarPopup("listdiv");
		cal1xx.showNavigationDropdowns();
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>
	<? } */?>

    </head>
    <body>
    <nav class="nav-top-1 bg-light px-3">
    <div class="d-flex">
    <div class="d-flex align-items-center">
    <!-- Navbar toggler -->
    <button class="mr-4 navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNavTop" aria-controls="navbarNavTop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
    </button>
    
  
    <a class="" href="home.php"><img class="header_logo" src="images/ucb-logo.png" /></a>

    </div>
    <!-- Navbar links -->
    <div class="collapse navbar-collapse collapse-custom" id="navbarNavTop">
    <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="home.php" onclick="show_loading()">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="client_dashboard_new.php?show=closed_loop_inv<?= $repchk_str ?>" onclick="show_loading()">Closed Loop Inventory</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=sales_quotes<?= $repchk_str ?>" onclick="show_loading()">Sales quotes</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=favorites<?= $repchk_str ?>" onclick="show_loading()">Favorites/Re-order</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=history<?= $repchk_str ?>" onclick="show_loading()">Current orders/history</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=accounting<?= $repchk_str ?>" onclick="show_loading()">Accounting</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=reports<?= $repchk_str ?>" onclick="show_loading()">Reports</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=tutorials<?= $repchk_str ?>" onclick="show_loading()">Tutorials</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=why_boomerang<?= $repchk_str ?>" onclick="show_loading()">Why Boomerang?</a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link" href="client_dashboard_new.php?show=feedback<?= $repchk_str ?>" onclick="show_loading()">Feedback</a>
        </li>
      </ul>
    </div>
    </div>

    <div class="d-flex align-items-center">
		
	<?php if ($sales_rep_login == "no") { ?>  
      <div class="d-flex align-items-center">
          <?php 
              if(isset($_COOKIE['loginid']) && $_COOKIE['loginid']!= "" ){ ?>
                    <?php 
                    db();
                    $select_user = db_query("SELECT user_name FROM boomerang_usermaster WHERE loginid = '".$_COOKIE['loginid']."'");
                    if(tep_db_num_rows($select_user)>0){
                      $user = array_shift($select_user);
                      echo '<a href="user_profile.php" class="mr-4 company_text_header">'.$user['user_name'].'</a>';
                    }
                  }
                  else{
                    echo '<a class="btn btn-topbar" href="index.php">Login</a>';
                  }
                ?>
        </div>
		
        <?php } ?>
    </div>
  </nav>
  <div class="nav-top-2">
    <p class="pl-5 mb-0">
    Shop B2B:
    <a class="gaylord_link"  href="client_dashboard_new.php?show=inventory<?= $repchk_str ?>" onclick="show_loading()">Browse Gaylords</a>
  </p>
  </div>
