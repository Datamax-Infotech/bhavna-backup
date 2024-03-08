<?php
function searchbox_new($url, $eid){
	
?>

	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------- SEARCH ---------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	<!----------------------------------------------------------------------------------------->
	
	<style>
		@import url("//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css");
		.search {
		  position: relative;
		  color: #aaa;
		  font-size: 14px;
		}
		.search_main_div{
			width: 100%;
			text-align: center;
			position: relative;
		}
		.search{
			display: inline;
		}
		.search input {
		  width: 400px;
		  height: 28px;
		  font-size: 13px;
		  font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;

		  background: #fcfcfc;
		  border: 1px solid #aaa;
		  border-radius: 7px;
		  box-shadow: 0 0 1px #EDEDED, 0 10px 15px #F8F8F8 inset;
		}

		.search input { text-indent: 32px;}
		.search .fa-search { 
		  position: absolute;
		  top: 1px;
		  margin-left: 10px;
		}
		.show_filter_txt{
			/*float: left;*/ 
			margin-left: 10px; 
			height: 30px;
			line-height: 30px;
			display: inline;
		}
		.search_line2{
			/*padding-top: 5px;*/
			width: 100%;
		}
		.more_filter_div{
			/*clear: both;
			margin-top: 7px; width: 900px;*/
			
		}
		#filter_options_div
		{
			position: absolute;
			background: white;
			left: 25%;
			border: 1px solid #909090;
			border-radius: 8px;
			padding: 5px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
		.search_btn{
		  font-size: 13px;
			color: #FFF;
		  	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
		  	padding: 4px 8px;
		 /* background: #fcfcfc;*/
			 background-color: #525252;
		  	border: 1px solid #aaa;
		  	border-radius: 4px;
		  /*box-shadow: 0 0 1px #aaa, 0 10px 15px #E5E5E5 inset;*/
			cursor: pointer;
			margin-top: 3px;
		}
		.checkbox_style{
			/*outline: 1px solid #ccc;*/
		}
		.search_btn:hover {
			background-color: #e7e7e7;
			color: #232323;
			border: 1px solid #77B5FF;
		}
		
		.search_text {
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
		}
		.search_text_link {
			margin-top: 5px;
			/*background: #45861e;
			border-radius: 4px;
			border: 1px solid #45861e;
			padding: 4px 6px;*/
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			color: #232323;
		}
		.search_text_link:hover, .search_text_criteria:hover {
			/*background-color: #e7e7e7;
			color: #232323;
			border: 1px solid #45861e;*/
		}
		.search_text_criteria {
			margin-top: 5px;
			background: #45861e;
			font-size: 12px;
			border-radius: 4px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important;
			padding: 4px 6px;
			color: #FFF;
			border: 1px solid #45861e;
			text-decoration: none;
		}
		.ser_form_component {
			font-size: 12px;
			margin-top: 2px;
			margin-bottom: 2px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
		  border: 1px solid #aaa;
		  border-radius: 2px;
		}
		.dd_style{
			height: 20px;
			border: 1px solid #ccc!important;
			/* background: #FDFDFD;*/
		}
		.elem_left{
			padding-left: 23px;
		}
		
		.between_border{
   /* height:100px;
    width:100px;*/
    border:2px solid black;
}
h1.ser_by{
    width: 205px;
	margin-top: -22px;
	margin-left: 8px;
    background:white;
	font-size: 14px;
	font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
	font-weight: normal;
}
	</style>
	<script>
    function show_filter_options(opt) {
        var filter_div = document.getElementById('filter_options_div');
        if (filter_div.style.display == 'block') {
            filter_div.style.display = 'none';

            document.getElementById(opt.id).innerHTML = 'Show Filter';
        }
        else {
            filter_div.style.display = 'block';
            document.getElementById(opt.id).innerHTML = 'Hide Filter';
        }
    }
</script>
<?php

	/*$Headshot = ""; $emp_name = "";
	$emp_qry = "Select * from loop_employees where b2b_id = '" . $eid . "'";
	$emp_res = db_query($emp_qry, db() );
	while ($emp_row = array_shift($emp_res)) {
		$emp_name = $emp_row["name"];
		$Headshot = $emp_row["Headshot"];
	}*/
?>
<form method="get" action="<?php echo $url?>">
	<input type=hidden name="show" value="search">
	<div class="search_main_div">
		<div class="search">
		<span class="fa fa-search"></span>
			<input name="searchcrit" type=text class="ser_form_component" id="searchcrit" placeholder="Enter Order ID" value="<?php if(isset($_REQUEST["searchcrit"])){ echo $_REQUEST["searchcrit"];}?>" size=50>
			<input type="hidden" name="posting" id="posting" value="yes">
		  <input type="hidden" name="B1" id="B1" value="Find">

	  </div>
		<div class="show_filter_txt">
			
			<input type=submit value="Search" class="search_btn" >
		</div>
		
		
	</div>
<!--	
	<div class="search_text" style="padding:8px; width: 900px;">
		
		
	
	</div>-->
</form>
<?php }