
<link rel="stylesheet" type="text/css" href="CSS/new_header-dashboard.css" />	
	<script>
	 function show_logout_option(opt) {
		var overlay = document.getElementById('backgroundOverlay');
        var filter_div = document.getElementById('logout_div');
        if (filter_div.style.display == 'block') {
            filter_div.style.display = 'none';
			 overlay.style.display = 'none';
        }
        else {
            filter_div.style.display = 'block';
			 overlay.style.display = 'block';
        }
    }
	//	
	window.onload = function(){
	var popup = document.getElementById('logout_div');
    var overlay = document.getElementById('backgroundOverlay');
    var openButton = document.getElementById('logout_link');
    document.onclick = function(e){
        if(e.target.id == 'backgroundOverlay'){
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }
        if(e.target === openButton){
         	popup.style.display = 'block';
            overlay.style.display = 'block';
        }
    };
};
	</script>
	<style>
		.logout_div_po{
			display: block;
			z-index: 99999;
			position: relative;
		}
	#logout_div
		{
			position: absolute;
			background: white;
			right: 0;
			border: 1px solid #909090;
			border-radius: 8px;
			padding: 5px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			width: 180px;
			height: 50px;
		}
		.logout_link_btn a{
			    background-color: #ffffff;
				border: 1px solid #dadce0;
				-webkit-border-radius: 4px;
				border-radius: 4px;
				display: inline-block;
				letter-spacing: .15px;
				margin: 6px;
				outline: 0;
				padding: 2px 24px;
				text-align: center;
				text-decoration: none;
				white-space: normal;
				font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important; 
				font-size: 14px;
				color: #1D1D1D;
		}
		.logout_link_btn a:hover{
			background-color: #F0F0F0;
			border: 1px solid #C1C1C1;
		}
		#backgroundOverlay{
   /* background-color:transparent;
    position:fixed;
    top:0;
    left:0;
    right:0;
    bottom:0;
    display:block;*/
}
	</style>

	<?php
	$sql = "SELECT name FROM ucbdb_employees where email = '". $_COOKIE['userloggedin'] . "'";
	$result = db_query($sql,db() );
$hrow = array_shift($result);
$emp_name=$hrow["name"];
	/*$sql = "SELECT Headshot, name FROM loop_employees where b2b_id = '". $_COOKIE['b2b_id'] . "'";
	$result = db_query($sql,db() );
$hrow = array_shift($result);
$emp_name=$hrow["name"].$hrow["b2b_id"];
	if($hrow["Headshot"]!="")
	{
		$emp_img=$hrow["Headshot"];
	}
else{
	$emp_img="new_header_noimg.jpg";//ucb_logo.php;.jpeg
}
	*/
	?>
	<div class="main_container">
		<div class="sub_container">
			<div class="header">
				<div class="logo_img"><a href="https://b2c.usedcardboardboxes.com/"><img src="images/new-ucb-header-logo.jpg"></a></div>
				<div class="link_txt"> <a href="https://loops.usedcardboardboxes.com/index.php" target="_blank">LOOPS</a> </div>
				<div class="link_txt"> <a href="https://loops.usedcardboardboxes.com/water_index.php" target="_blank">UCBZW</a> </div>
				<div class="link_txt"> <a href="https://b2c.usedcardboardboxes.com/" target="_blank">B2B</a> </div>
				<div class="link_txt"> <a href="https://loops.usedcardboardboxes.com/report_sop.php" target="_blank">SOPS</a> </div>
				<div class="link_txt"> <a href="https://loops.usedcardboardboxes.com/dashboardnew.php?show=links" target="_blank">LINKS</a> </div>
				<div class="login-user">
					  <div id="backgroundOverlay"></div>
					<div class="logout_div_po" >
					<a href="#" id="logout_link" onclick="show_logout_option(this)" ><span class="login-username"><?php echo $emp_name; ?></span><img src="images/employees/new_header_noimg.jpg" width="25px" height="25px"></a></div>
					<div id="logout_div" style="margin-top: 4px; display:none;" >
						<div class="logout_link_btn"><a href="logoff.php">Logout</a></div>
					</div>
				</div>
				
				<?php 
				include("search_box_fun.php");
				searchbox_new("orders.php",$eid);		
				?>

			</div>
		</div>
	</div>

