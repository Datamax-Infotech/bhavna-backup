	<script language="javascript">
		function logout() 
		{	
			if (window.XMLHttpRequest)
			{
			  xmlhttp=new XMLHttpRequest();
			}
			else
			{
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			  xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.location = "index.php";
				}
			}
			
			xmlhttp.open("GET","logout.php", true);
			xmlhttp.send();
		}
		
		function changepwd() 
		{	
			document.location = "change-password.php";		
		}	
	</script>
  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container-fluid">

      <div id="logo" class="pull-left" style="display:flex; align-self:center;">
        <a href="index.php"><img src="images/UCBZeroWaste_logo.png" height="60" alt="" title="" /></a>
      </div>
      
	<?
		$parent_comp_flg = 0; $companyid = 0;
		$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1" ;
		$result = db_query($sql, array("i") , array($_SESSION['loginid']));
		while ($myrowsel = array_shift($result)) {
			$parent_comp_flg = $myrowsel['parent_comp_flg'];
			$companyid = $myrowsel['companyid'];
		}

		if ($parent_comp_flg == 1){
			$child_found = "no";		
			db_b2b();
			$sql1 = "select ID from companyInfo where parent_comp_id= ? and parent_child ='Child' and haveNeed = 'Have Boxes'";
			$result1 = db_query($sql1, array("i") , array($companyid));
			while ($myrowsel1 = array_shift($result1)) {
				$child_found = "yes";	
			}
			
			db();
			if ($child_found == "no") {
				$parent_comp_flg = 0;
			}
		}
		
		$main_company_name = getnickname_warehouse_new($company_name, $warehouse_id);
		
		?>	  
		<div class="menu-container">
			<nav class="menu-navigation-icons"> 
			<a href="index.php" class="menu-white mob-only-menu"><i class="fa fa-truck fa-flip-horizontal"></i><span>Home</span></a>    
				<?
				
				if($parent_comp_flg == 1)
				{
					 if ($_SESSION['pgname'] == "locations") {?>
						<a href="locations.php" class="menu-white active"><i class="fa fa-search"></i><span>Locations</span></a>
					<? } else {?>
						<a href="locations.php" class="menu-white"><i class="fa fa-search"></i><span>Locations</span></a>
					<? }?>
					<? 
				}
				else
				{
				?>			
					<? if ($_SESSION['pgname'] == "request-a-pickup") {?>
						<a href="request-a-pickup.php" class="menu-white active"><i class="fa fa-truck fa-flip-horizontal"></i><span>Request Pickup</span></a>
					<? } else {?>
						<a href="request-a-pickup.php" class="menu-white"><i class="fa fa-truck fa-flip-horizontal"></i><span>Request Pickup</span></a>
					<? } ?>

					<? if ($_SESSION['pgname'] == "pickups-in-process") {?>
						<a href="pickups-in-process.php" class="menu-white active"><i class="fa fa-search"></i><span>Pickups in Process</span></a>
					<? } else {?>
						<a href="pickups-in-process.php" class="menu-white"><i class="fa fa-search"></i><span>Pickups in Process</span></a>
					<? }?>
				<? }?>

				<? if ($_SESSION['pgname'] == "vendor-reports") {?>
					<a href="vendor-reports.php" class="menu-white active"><i class="fa fa-list-alt"></i><span>Vendor Reports</span></a>
				<? } else {?>
					<a href="vendor-reports.php" class="menu-white"><i class="fa fa-list-alt"></i><span>Vendor Reports</span></a>
				<? }?>
				
				<? if ($_SESSION['pgname'] == "waste-reports") {?>
					<a href="waste-reports.php" class="menu-white active"><i class="fa fa-bar-chart"></i><span>Sustainability Reports</span></a>
				<? } else {?>
					<a href="waste-reports.php" class="menu-white"><i class="fa fa-bar-chart"></i><span>Sustainability Reports</span></a>
				<? }?>
				
				<? if ($_SESSION['pgname'] == "water-initiatives") {?>
					<a href="water-initiatives.php" class="menu-white active"><i class="fa fa-tint"></i><span>Water Initiatives</span></a>
				<? } else {?>
					<a href="water-initiatives.php" class="menu-white"><i class="fa fa-tint"></i><span>Water Initiatives</span></a>
				<? }?>
				
				<? if ($_SESSION['pgname'] == "contact-us") {?>
					<a href="contact-us.php" class="menu-white active"><i class="fa fa-envelope"></i><span>Contact Us</span></a>
				<? } else {?>
					<a href="contact-us.php" class="menu-white"><i class="fa fa-envelope"></i><span>Contact Us</span></a>
				<? }?>

			</nav>
		</div>
		
		<div class="navbar-client-logo">
			<div class="client-logo-container">
				<img src="https://loops.usedcardboardboxes.com/supplierdashboard_logo/<? echo $company_logo;?>" width="90px" height="61px" ><br>
				<span align='left'><? echo $main_company_name;?></span>
				<div class="logout-mob">
					<input type="button" class="logout-button" value="Change Password" onclick="changepwd()">
					<input type="button" class="logout-button" value="Logout" onclick="logout()">
				</div>
			</div>
		</div>

    </div>
  </header><!-- #header -->
