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
       // alert('timerStart -> '+timerStart)
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
    
  
    <a class="" href="home.php"><img src="images/ucb-logo.jpg" /></a>

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
		 <? /*if ($hdmultiple_acc_flg == 1) { ?>
              <form class="px-1 mb-0" name="frmchangepwd" action="client_dashboard_new.php?compnewid=ssQ3ZaqbEIAA3emPe%2FPxcO5%2BYM1P2cfi%2F67%2BRAITKjxq5EufBol9yMCnEJvmzZIit1uiCzZVVUOl9BGOxUgy%2Bg%3D%3D&amp;show=change_password" method="Post">
                <input type="button" class="btn btn-topbar" id="btnswitchacc" name="btnswitchacc" onclick="swicthacc(201, 81689)" value="Switch Locations" style="cursor:pointer;">
              </form>
			   <? } */?>
            <? /*if (!isset($_REQUEST["hd_chgpwd"])) { ?>
              <form class="px-1 mb-0" name="frmchangepwd" action="client_dashboard_new.php?compnewid=urk%2F%2BS7UVfeB7Z4p5%2BImXBKvO%2Fa2h0f6RPTzUt5IX9AMci6uyhU0pw3MCDoBKYFUWEU5UvIK1IUZbTCFaQu6vQ%3D%3D&amp;show=change_password" method="Post">
                <input type="submit" class="btn btn-topbar" name="btnchgpwd" value="Change password" style="cursor:pointer;">
                <input type="hidden" name="hd_chgpwd" id="hd_chgpwd" value="yes">
              </form>
			   <? } */?>
          <?php 
              if(isset($_COOKIE['loginid']) && $_COOKIE['loginid']!= "" ){ ?>
              <!--
                <form class="px-1 mb-0" name="frmsignout" action="client_dashboard_new.php" method="Post">
                <input type="submit" class="btn btn-topbar" name="btnsignout" value="Log off" style="cursor:pointer;">
                <input type="hidden"  name="hd_signout" id="hd_signout" value="yes">
              </form>
			         <a class="mr-4 btn btn-topbar" href="user_profile.php">My Profile</a>
              -->
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
        <!-- &nbsp;&nbsp;<p class="company_text_header"><i><? //echo $client_name. " - ".$shipCityNm.", ".$shipStateNm; ?></i></p> -->
      </div>
  </nav>
  <div class="nav-top-2">
    <p class="pl-5 mb-0">
    Shop B2B:
    <?php /* href="client_dashboard_new.php?compnewid=<?= urlencode(encrypt_password($client_companyid)); ?>&show=inventory<?= $repchk_str ?>" */ ?>
    <a class="gaylord_link"  href="client_dashboard_new.php?show=inventory<?= $repchk_str ?>" onclick="show_loading()">Browse Gaylords</a>
  </p>
  </div>

  <?php 
  if (isset($_REQUEST["hd_signout"])) {
    if ($_REQUEST["hd_signout"] == "yes") {
      $date_of_expiry = time() - 2 ;
      setcookie( "loginid", "", $date_of_expiry );
      
      echo "<script type=\"text/javascript\">";
      echo "window.location.href=\"index.php\";";
      echo "</script>";
      echo "<noscript>";
      echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
      echo "</noscript>"; exit;
      
    }
    
  }
  
  if (isset($_REQUEST["hd_chgpwd_upd"])) {
    if ($_REQUEST["hd_chgpwd_upd"] == "yes") {
  
      $sql="UPDATE clientdashboard_usermaster SET password = '" . $_REQUEST["txt_newpwd"]  . "' WHERE loginid = " . $client_loginid ;
      //echo "<br/>".$sql; exit();
      $result = db_query($sql, db());
      
      $res1 = db_query("SELECT user_name FROM clientdashboard_usermaster WHERE loginid = " . $client_loginid, db()); 
      while($fetch_data1=array_shift($res1)) {
        $strQuery = "UPDATE clientdashboard_usermaster SET password = '".$_REQUEST["txt_newpwd"]."' WHERE user_name = '" . $fetch_data1["user_name"] . "'";
        $result = db_query($strQuery, db());
      }
      
      echo "<script type=\"text/javascript\">";
      echo "window.location.href=\"client_dashboard_new.php?compnewid=" . urlencode(encrypt_password($_REQUEST["compnewid_chgpwd"])) . "\";";
      echo "</script>";
      echo "<noscript>";
      echo "<meta http-equiv=\"refresh\" content=\"0;url=client_dashboard_new.php?compnewid=" . urlencode(encrypt_password($_REQUEST["compnewid_chgpwd"])) . "\" />";
      echo "</noscript>"; exit;
      
    }
    
  }
  ?>