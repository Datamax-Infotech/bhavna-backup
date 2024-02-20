    <? 
        $meeting_filter = " and attendee_id='".$_COOKIE['b2b_id']."'";
        $sql_main = db_query("SELECT mm.id, mm.meeting_name FROM meeting_attendees as ma JOIN meeting_master as mm ON mm.id = ma.meeting_id 
        where mm.status = 1 $meeting_filter GROUP By ma.meeting_id 
        union SELECT 0, 'Personal' ORDER BY (meeting_name <> 'Personal') ASC,meeting_name ", db());
        $meeting_options="";
        while( $meeting_data = array_shift($sql_main)){
            $meeting_options.="<option value='".$meeting_data['id']."'>".$meeting_data['meeting_name']."</option>";
        }
    ?>
    <!-- Meeting Add Attendees -->
    <div class="modal fade" id="addAttendeesModalMeetCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="addExistingAteendees">
			<form action="dashboard_meeting_action.php" method="post" name="add_existing_attendees" id="add_existing_attendees" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" ><b>Add existing attendee</b></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="form-group reports_to_div search_existing_user w-100">
                        <label>Search users to add them to the meeting: </label>
                        <select id="search_existing_user" class="search_existing_user_sel form-control form-control-sm select2" multiple name=""  data-placeholder="Search for existing..." >
                            <!--<option></option>
                            <option value="0" data-kt-rich-content-icon="assets_new_dashboard/img/att1.png">Marty Metro</option>
                            <option value="1" data-kt-rich-content-icon="assets_new_dashboard/img/att2.png">David Krasnow</option>
                            <option value="2" data-kt-rich-content-icon="assets_new_dashboard/img/att3.png">Zac Fratkin</option>
                            <option value="3" data-kt-rich-content-no-img="ZF">Zac Fratkin</option>-->
                        </select>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-existing-attendees">
						
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>

    <!-- Create New Project Modal-->
    <?
     // Modify the date to be 1 week (7 days) from today
     $currentDate = new DateTime();
     $oneWeekFromToday1 = $currentDate->modify('+3 Months');
     // Format the date as a string (adjust the format as needed)
     $threeMonthFromToday = $oneWeekFromToday1->format('Y-m-d');
     ?>
	<div class="modal fade" id="newProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
			<form action="project_action.php" method="post" name="form_project" id="form_project" enctype="multipart/form-data" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_project_title">Create New Project</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('project')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
				<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Project Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="project_title" id="project_title"/>
						  <p class="text-danger d-none form_error" id="project_title_error">Please enter project title </p>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description </label>
						<div id="summernote-project" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="project_desc_error">Please enter project description </p>
					 </div>
                     <div class="col-md-6 form-group">
						<label>Select Owner<span class="text-danger">*</span> </label>
                        <? 
                            /*$select_level=db_query("SELECT level from loop_employees where b2b_id =".$_COOKIE['b2b_id'], db());
                            $emp_level = array_shift($select_level)['level'];
                          */
                        ?>
						<!--<select class="form-control form-control-sm  owner_id_dp" add_type="top" id="owner_id" name="owner_id" <? echo $emp_level!=2 ? "disabled='true'" : "";?>>
							<option value="0">All</option>
							<?
								/*$dept_id="";	
								$sql = "SELECT name, initials, employeeID,is_supervisor, loopID, dept_id FROM employees where status = 'Active' and is_supervisor = 'Yes' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
                                    if($myrowsel["employeeID"] == $_COOKIE["b2b_id"]){
                                        $dept_id=$myrowsel['dept_id'];
                                    }
									
                                    if($myrowsel['is_supervisor']=="Yes"){
                                        echo '<optgroup label="'.$myrowsel["name"].'">';
                                        echo "<option value=".$myrowsel["employeeID"]." ";
                                        if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									    echo " >". $myrowsel["name"] . "</option>";
                                        $select_sql=db_query("SELECT employeeID,name, dept_id FROM employees where status = 'Active' and supervisor_name=".$myrowsel["loopID"],db_b2b());
                                        while($r=array_shift($select_sql)){
											if($r["employeeID"] == $_COOKIE["b2b_id"]){
												$dept_id = $r['dept_id'];
											}
											
                                            echo "<option value=".$r["employeeID"]." ";
                                            if ($r["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
                                            echo " >". $r["name"] ."</option>";
                                        }
										echo '</optgroup>';
                                    }
								 } 
								$sql = "SELECT name, initials, employeeID,is_supervisor, loopID, dept_id FROM employees where status = 'Active' and supervisor_name = '' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
                                    if($myrowsel["employeeID"] == $_COOKIE["b2b_id"]){
                                        $dept_id=$myrowsel['dept_id'];
                                    };
									
									echo "<option value=".$myrowsel["employeeID"]." ";
									if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									echo " >". $myrowsel["name"] . "</option>";
								 } */ ?>
						</select>
                            -->
                        <?php 
                                $user_check_sql = db_query("SELECT id,name,	level,is_supervisor,b2b_id,dept_id FROM loop_employees where b2b_id='".$_COOKIE['b2b_id']."'",db());
                                $loggedin_user=array_shift($user_check_sql);
                                $dept_id="";
                                if($loggedin_user['level']==2 || $loggedin_user['is_supervisor']=="Yes"){
                    
                                    echo '<select class="form-control form-control-sm owner_id_dp" add_type="top" id="owner_id" name="owner_id">';
                                    if($loggedin_user['level']==2){
                                        $other_user_sql=db_query("SELECT id,name,b2b_id,dept_id from loop_employees where status = 'Active' ORDER BY name ASC",db()); 
                                    }else{
                                        // echo "SELECT id,name,b2b_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC";
                                        $other_user_sql=db_query("SELECT id,name,b2b_id,dept_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC",db());
                                        $dept_id=$loggedin_user['dept_id'];
                                        ?>
                                        <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                        
                                    <?}?> 
                                        <? while($user = array_shift($other_user_sql)){
                                            if($user["b2b_id"] == $_COOKIE["b2b_id"]){
                                                $dept_id=$user['dept_id'];
                                            };
                                            ?>
                                            <option value="<?= $user['b2b_id']; ?>" <? echo $user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : ""; ?>><?= $user['name'];?></option>
                                        <? }
                                    echo '</select>';
                                } else{ 
                                    $dept_id=$loggedin_user['dept_id'];
                                    ?>
                                    <select class="form-control form-control-sm owner_id_dp" id="owner_id_meet" name="owner_id" disabled="true">
                                        <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                    </select>
                            <? }
                        ?>
						<p class="text-danger d-none form_error" id="owner_id_error">Please select owner </p>
					 </div>      
                    

					 <div class="col-md-6 form-group">
						<label>Department<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="dept_id" name="dept_id">
							<option value=""></option>
							<?	
								$query = db_query( "SELECT * FROM project_dept_master order by dept_order", db() );
								while ($rowsel_getdata = array_shift($query)) {
									$tmp_str = "";
									if ($dept_id == $rowsel_getdata["id"]) {
                                        $tmp_str = " selected ";
                                    }
								?>
									<option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?> ><? echo $rowsel_getdata['dept_name'];?></option>
								<? } ?>
						</select>
						<p class="text-danger d-none form_error" id="dept_id_error">Please select Department </p>
					 </div>
					 <div class="col-md-6 form-group">
						  <label>Deadline <span class="text-danger">*</span> </label>
						  <input type="date" class="form-control form-control-sm" placeholder="" name="deadline" id="deadline"  value="<? echo $threeMonthFromToday; ?>">
						  <p class="text-danger d-none form_error" id="deadline_error">Please select Deadline </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Priority<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="project_priority_id" name="project_priority_id">
							<?	
								$query = db_query( "SELECT * FROM project_priority_master order by unqid", db() );
								while ($rowsel_getdata = array_shift($query)) {
									$tmp_str = "";
									if (isset($_REQUEST["project_priority_id"])) {
										if ($_REQUEST["project_priority_id"] == $rowsel_getdata["unqid"]) {
											$tmp_str = " selected ";
										}
									}
								?>
									<option value="<? echo $rowsel_getdata["unqid"];?>" <? echo $tmp_str;?> ><? echo $rowsel_getdata['priority_value'];?></option>
								<? } ?>
						</select>
					 </div>
					  <div class="col-md-12 form-group">
						<label>Attach to Meeting(s)</label>
						<select class="form-control form-control-sm select2 project_meetings_dp" multiple  id="project_meetings" name="project_meetings[]">
                                <?= $meeting_options;?>
						</select>
					  </div>
					  <div class="col-md-6 form-group">
						<label>Status<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="pstatus_id" name="pstatus_id">
							<?	
								$query = db_query("SELECT * FROM project_status order by id", db() );
								while ($rowsel_getdata = array_shift($query)) {
								?>
									<option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['status'];?></option>
								<? } ?>
						</select>
					 </div>
					 
					 <div class="col-md-6 form-group">
						  <label>Supportive Documents </label>
						  <input class="form-control-file" type="file" name="uploadscanrep[]" id="uploadscanrep"  multiple onchange="GetFileSize()">
						  <p class="text-danger d-none form_error" id="uploadscanrep_error"></p>
						  <input type="hidden" id="project_action" name="project_action" value="ADD">
						  <input type="hidden" id="project_id_edit" name="project_id_edit" value="">
					 </div>
                     <div class="col-md-12 add_milestone_div">
                        <button type="button" class="btn btn-dark btn-sm add_milestone"><i class="fa fa-plus mr-2"></i>Add milestone</button>
                        <div class="milstones">
                            <table class="addMilestoneTable table table_vetical_align_middle mt-3 border-0 d-none">
                                <thead>
                                    <th></th>
                                    <th>Milestone</th>
                                    <th>Due Date</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
					</div>	 
				</div>
                <div class="modal-footer">
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button type="button" id="removeProject" project_id="" class="btn btn-delete d-none"><i class="fa fa-trash-o"></i> Archive</button>
					</div>
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('project')" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
                        <input type="submit" value="Save Project" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-project">
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>

     <!--Meeting Add Project -->
     <div class="modal fade" id="addProjectModalMeetCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="addExistingProject">
			<form action="dashboard_meeting_action.php" method="post" name="add_existing_project" id="add_existing_project" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" ><b>Select Existing Project </b> <small>or <button type="button" class="btn btn-sm button_light" onclick="add_project('new')">create new</button></small></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="form-group search_existing_metrics w-100">
                        <label>Search for existing Projects to attach them to : <?php echo $meeting_name; ?> </label>
                        <select id="searchExistingProject" class="search_existing_MetricsProject form-control form-control-sm select2" multiple name=""  data-placeholder="Search for existing..." >
                            <option></option>
						</select>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-existing-project">
						
                    </div>
                </div>
			</form>
            </div>
            <div class="modal-content d-none" id="addNewProject">
			<form action="project_action_meet.php" method="post" name="form_project_meet" id="form_project_meet" enctype="multipart/form-data" onsubmit="return false;">
				<div class="modal-header">
                    <h5 class="modal-title"><b>Create a New Project </b><small>or <button type="button" class="btn button_light btn-sm" onclick="add_project('existing')">Select Existing Project</button></small></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
				<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Project Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="project_title" id="project_title_meet"/>
						  <p class="text-danger d-none form_error" id="project_title_meet_error">Please enter project title </p>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description </label>
						<div id="summernote-project-meet" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="project_desc_error_meet">Please enter project description </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Select Owner<span class="text-danger">*</span> </label>
						<!--<select class="form-control form-control-sm select2 owner_id_dp" add_type="meet" id="owner_id_meet" name="owner_id">
							<?
								$dept_id="";	
								$sql = "SELECT name, initials, employeeID,is_supervisor, loopID, dept_id FROM employees where status = 'Active' and is_supervisor = 'Yes' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
                                    if($myrowsel["employeeID"] == $_COOKIE["b2b_id"]){
                                        $dept_id=$myrowsel['dept_id'];
                                    }
									
                                    if($myrowsel['is_supervisor']=="Yes"){
                                        echo '<optgroup label="'.$myrowsel["name"].'">';
                                        echo "<option value=".$myrowsel["employeeID"]." ";
                                        if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									    echo " >". $myrowsel["name"] . "</option>";
                                        $select_sql=db_query("SELECT employeeID,name, dept_id FROM employees where status = 'Active' and supervisor_name=".$myrowsel["loopID"],db_b2b());
                                        while($r=array_shift($select_sql)){
											if($r["employeeID"] == $_COOKIE["b2b_id"]){
												$dept_id = $r['dept_id'];
											}
											
                                            echo "<option value=".$r["employeeID"]." ";
                                            if ($r["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
                                            echo " >". $r["name"] ."</option>";
                                        }
										echo '</optgroup>';
                                    }
								 } 
								 ?>
								 
							<?
								$sql = "SELECT name, initials, employeeID,is_supervisor, loopID, dept_id FROM employees where status = 'Active' and supervisor_name = '' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
                                    if($myrowsel["employeeID"] == $_COOKIE["b2b_id"]){
                                        $dept_id=$myrowsel['dept_id'];
                                    }
									
									echo "<option value=".$myrowsel["employeeID"]." ";
									if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									echo " >". $myrowsel["name"] . "</option>";
								 } ?>

						</select>


                                -->
                        <?php 
                                $user_check_sql = db_query("SELECT id,name,	level,is_supervisor,b2b_id,dept_id FROM loop_employees where b2b_id='".$_COOKIE['b2b_id']."'",db());
                                $loggedin_user=array_shift($user_check_sql);
                                $dept_id="";
                                if($loggedin_user['level']==2 || $loggedin_user['is_supervisor']=="Yes"){
                    
                                    echo '<select class="form-control form-control-sm owner_id_dp" add_type="meet" id="owner_id_meet" name="owner_id">';
                                    if($loggedin_user['level']==2){
                                        $other_user_sql=db_query("SELECT id,name,b2b_id,dept_id from loop_employees where status = 'Active' ORDER BY name ASC",db()); 
                                    }else{
                                        // echo "SELECT id,name,b2b_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC";
                                        $other_user_sql=db_query("SELECT id,name,b2b_id,dept_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC",db());
                                        $dept_id=$loggedin_user['dept_id'];
                                        ?>
                                        <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                        
                                    <?}?> 
                                        <? while($user = array_shift($other_user_sql)){
                                            if($user["b2b_id"] == $_COOKIE["b2b_id"]){
                                                $dept_id=$user['dept_id'];
                                            };
                                            ?>
                                            <option value="<?= $user['b2b_id']; ?>" <? echo $user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : ""; ?>><?= $user['name'];?></option>
                                        <? }
                                    echo '</select>';
                                } else{ 
                                    $dept_id=$loggedin_user['dept_id'];
                                    ?>
                                    <select class="form-control form-control-sm owner_id_dp" id="owner_id_meet" name="owner_id" disabled="true">
                                        <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                    </select>
                            <? }
                        ?>
						<p class="text-danger d-none form_error" id="owner_id_meet_error">Please select owner </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Department<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="dept_id_meet" name="dept_id">
							<option value=""></option>
							<?	
								$query = db_query( "SELECT * FROM project_dept_master order by dept_order", db() );
								while ($rowsel_getdata = array_shift($query)) {
									$tmp_str = "";
									if ($dept_id == $rowsel_getdata["id"]) {
                                        $tmp_str = " selected ";
                                    }
									
								?>
									<option value="<? echo $rowsel_getdata["id"];?>" <? echo $tmp_str;?>><? echo $rowsel_getdata['dept_name'];?></option>
								<? } ?>
						</select>
						<p class="text-danger d-none form_error" id="dept_id_meet_error">Please select Department </p>
					 </div>
					 <div class="col-md-6 form-group">
						  <label>Deadline <span class="text-danger">*</span> </label>
						  <input type="date" class="form-control form-control-sm" placeholder="" name="deadline" id="deadline_meet" value="<?= $threeMonthFromToday; ?>">
						  <p class="text-danger d-none form_error" id="deadline_meet_error">Please select Deadline </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Priority<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="project_priority_id_meet" name="project_priority_id">
							<?	
								$query = db_query( "SELECT * FROM project_priority_master order by unqid", db() );
								while ($rowsel_getdata = array_shift($query)) {		
								?>
									<option value="<? echo $rowsel_getdata["unqid"];?>" ><? echo $rowsel_getdata['priority_value'];?></option>
								<? } ?>
						</select>
					 </div>
					  <div class="col-md-12 form-group">
						<label>Attach to Meeting(s)</label>
						<select class="form-control form-control-sm select2 project_meetings_dp" multiple  id="project_meetings_meet" name="project_meetings[]">
                            <?= $meeting_options;?>
						</select>
					  </div>
					  <div class="col-md-6 form-group">
						<label>Status<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="pstatus_id_meet" name="pstatus_id">
                            <?	
								$query = db_query("SELECT * FROM project_status order by id", db() );
								while ($rowsel_getdata = array_shift($query)) {
								?>
									<option value="<? echo $rowsel_getdata["id"];?>" ><? echo $rowsel_getdata['status'];?></option>
								<? } ?>
						</select>
					 </div>
					 
					 <div class="col-md-6 form-group">
						  <label>Supportive Documents </label>
						  <input class="form-control-file" type="file" name="uploadscanrep[]" id="uploadscanrep_meet"  multiple onchange="GetFileSizeMeet()">
						  <p class="text-danger d-none form_error" id="uploadscanrep_meet_error"></p>
						  <input type="hidden" id="project_action_meet" name="project_action" value="ADD_FROM_MEET">
						  <input type="hidden" id="project_id_edit_meet" name="project_id_edit" value="">
					 </div>
                     <div class="col-md-12 add_milestone_div">
                        <button type="button" class="btn btn-dark btn-sm add_milestone"><i class="fa fa-plus mr-2"></i>Add milestone</button>
                        <div class="milstones">
                            <table class="addMilestoneTable table table_vetical_align_middle mt-3 border-0 d-none">
                                <thead>
                                    <th></th>
                                    <th>Milestone</th>
                                    <th>Due Date</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
					</div>	 
				</div>
                <div class="modal-footer">
					<div class="btn-left">
					</div>
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<!--<button class="btn btn-light modal_reset_button btn-sm" onclick="reset_form_fields('project',false)"  style="cursor:pointer;" type="reset">Reset</button>-->
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('project')" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
						<input type="submit" value="Save Project" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="new-project-meet">
                    </div>
                </div>
			</form>    
		</div>
        </div>
    </div>
   
 

    <!--Meeting add issue -->
<!--<div class="modal fade" id="issueModalMeetCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
			<form action="dashboard_meeting_action.php" method="post" name="" id="" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" >Issue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="row">
                     <div class="col-md-6 form-group">
						  <label>Issue - Required <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="issue1" id="issue1">
						  <p class="text-danger d-none" id="issue_error1">Please enter Issue </p>
					 </div>
                     <div class="col-md-6 form-group">
						  <label>Owner<span class="text-danger"></span> </label>
						  <select class="form-control">
                                <option>Zac Fratkin</option>
                                <option>David Krasnow</option>
                          </select>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Details  </label>
						<div id="summernote-issue1" class="summernote"></div> 
						<p class="text-danger d-none" id="issue_desc_error1">Please enter issue description </p>
					 </div>	
                    </div>
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-issue1">
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
					</div>
                </div>
			</form>
            </div>
        </div>
    </div>
-->
    <!--Meeting Edit Measurables -->
    <div class="modal fade" id="editMeasurablesModalMeetCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
			<form action="dashboard_meeting_action.php" method="post" name="" id="" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" >Edit Measurables</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="row">
                     <div class="col-md-7">
                          <div class="form-group mb-5">
						    <label>Name <span class="text-danger">*</span> </label>
						    <input type="text" class="form-control form-control-sm" value="Facilities >=80% Full" name="m1" id="m1">
                          </div>
                          <div class="form-group mb-5">
						    <label>Who's Accountable:</label>
                            <select id="" class="whos_accountable select2 form-control form-control-sm" data-placeholder="Search For Accountable">
                                <!--<option></option>
                                <optgroup label="Meeting Attendees">
                                    <option>Hannah Apple</option>
                                    <option>Cristopher Gerard</option>
                                    <option>Zac Fratkin</option>
                                </optgroup>
                                <optgroup label="All Users">
                                    <option>Bob Windsor</option>
                                    <option>Hannah Apple</option>
                                    <option>David Krasnow</option>
                                    <option>Cristopher Gerard</option>
                                    <option>Zac Fratkin</option>
                                </optgroup>
                                -->
                                <?
								$sql = "SELECT name, initials, employeeID FROM employees where status = 'Active' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
									echo "<option value=".$myrowsel["employeeID"]." ";
                                    if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									echo " >". $myrowsel["name"] . "</option>";
								} ?>
                            </select>
                          </div>
                          <div class="form-group mb-5">
						    <label>Units</label>
                            <select class="form-control form-control-sm">
                                <option value="No units">No Units</option>
                                <option value="Dollar">Dollars</option>
                                <option value="Percent">Percent</option>
                                <option value="Pound">Pounds</option>
                                <option value="Euros">Euros</option>
                                <option value="Pesos" disabled="">Pesos</option>
                                <option value="Yen" disabled="">Yen</option>
                            </select>
                          </div>
                          <div class="row">
                          <div class="form-group col-md-6 mb-5">
						    <label>Goal</label>
                            <select class="form-control form-control-sm">
                            <option selected="selected" value="EqualTo">Equal to</option>
                            <option value="GreaterThan">Greater than or equal to</option>
                            <option value="GreaterThanNotEqual">Greater than</option>
                            <option value="Between">Between</option>
                            <option value="LessThanOrEqual">Less than or equal to</option>
                            <option value="LessThan">Less than</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6 mb-5">
						    <label>Goal Metrics </label>
						    <input type="text" class="form-control form-control-sm" name="goal" id="goal">
                          </div>
                        </div>
                        <div class="form-group">
                        <label>Attach to Meeting(s) </label>
                        <select id="" class="marix_add_to_meetings form-control form-control-sm select2" multiple="multiple" data-placeholder="Not attached to any meeting...">
                            <option ></option>
                            <option value="1">Zac Personal</option>
                            <option value="2">Leadership Team L10</option>
                            <option value="1">ZF/DK Same Page</option>
                        </select>
                        </div>
					 </div>
                     <div class="col-md-5">
						  <h5>Advance Controls</h5>
                          <div class="p-4">
                          <div class="form-group mb-5 show_average">
                                <div class="d-flex">
                                    <input type="checkbox" class="mr-1 show_average_check checkbox_lg">
                                    <label class="ml-3 small-font"> Show Average</label>
                                </div>
                                <div class="average_details form_extra_details d-none ">
                                    <div class="form-inline">
                                        <span>Between</span>
                                        <input type="date" class="mx-2 form-control form-control-sm"/>
                                        <span> &nbsp;And Today</span>
                                    </div>
                                </div>
                          </div>
                          <div class="form-group mb-5 show_commulative" >
                            <div class="d-flex">
                                <input type="checkbox" class="mr-1 show_commulative_check checkbox_lg">
                                <label class="ml-3 small-font"> Show Cumulative</label>
                            </div>
                            <div class="commulative_details form_extra_details d-none">
                                <div class="form-inline">
                                    <span>Between</span>
                                    <input type="date" class="mx-2 form-control form-control-sm"/>
                                    <span> &nbsp;And Today</span>
                                </div>
                            </div>
                          </div>
                          <div class="form-group mb-5 show_formula" >
                            <div class="d-flex">
                                <input type="checkbox" class="mr-1 show_formula_check checkbox_lg">
                                <label class="ml-3 small-font">Formula</label>
                            </div>
                            <div class="formula_details form_extra_details  d-none">
                                    <input type="date" class="form-control form-control-sm"/>
                                    <p class="mt-2"><b class="text-danger">CAUTION</b><span> Creating or updating a row formula will overwrite existing data for the entire row. Overwritten data cannot be recovered.</span></p>
                            </div>
                          </div>
                        </div>
					 </div>
                    </div>
				</div>
                <div class="modal-footer">
                <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button type="button" id="remove_measurable" issue_id="" class="btn btn-delete"><i class="fa fa-trash-o"></i> Archive</button>
					</div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-measurable">
						
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>
    <!-- Meeting Add Measurble -->
    <div class="modal fade" id="addMeasurablesModalMeetCreate" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" id="addExistingMetrics">
			<form action="dashboard_meeting_action.php" method="post" name="add_existing_metrics" id="add_existing_metrics" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" ><b>Select Existing Measurable </b> <small>or <button class="btn btn-sm button_light" onclick="add_metrics('new')">create new</button></small></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="form-group reports_to_div search_existing_metrics w-100">
                        <label>Search for measurables to add them to B2B Sourcing L10: </label>
                        <select class="search_existing_MetricsRock form-control form-control-sm select2" multiple name=""  data-placeholder="Search for existing..." >
                            <option></option>
                            <option value="0" data-kt-rich-content-icon="assets_new_dashboard/img/att1.png"  data-kt-rich-content-desc="Owner: Hannah Apple">New Companies in the Agreement Redlines Received phase</option>
                            <option value="1" data-kt-rich-content-icon="assets_new_dashboard/img/att2.png"  data-kt-rich-content-desc="Owner: Rasheda Nathaniel">B2B Monthly % of QTD</option>
                            <option value="2" data-kt-rich-content-icon="assets_new_dashboard/img/att3.png"  data-kt-rich-content-desc="Owner: Gerald DarSantos (Admin: Edmund John Morelos)">Average Product Rating</option>
                            <option value="3" data-kt-rich-content-no-img="ML" data-kt-rich-content-desc="Owner: DeAngelo Cross (Admin: James Craten)">ML Injuries</option>
                        </select>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-metrics">
						
                    </div>
                </div>
			</form>
            </div>
            <div class="modal-content d-none" id="addNewMetrics">
			<form action="dashboard_meeting_action.php" method="post" name="add_new_metrics" id="add_new_metrics" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Create a New Measurable </b><small>or <button class="btn button_light btn-sm" onclick="add_metrics('existing')">Select Existing Measurable</button></small></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="row">
                     <div class="col-md-7">
                          <div class="form-group mb-5">
						    <label>Name <span class="text-danger">*</span> </label>
						    <input type="text" class="form-control form-control-sm" value="Facilities >=80% Full" name="m2" id="m2">
                          </div>
                          <div class="form-group mb-5">
						    <label>Who's Accountable:</label>
                            <select id="" class="whos_accountable select2 form-control form-control-sm" data-placeholder="Search for Accountable">
								<?
								$sql = "SELECT name, initials, employeeID FROM employees where status = 'Active' order by name" ;
								$result = db_query($sql , db_b2b() );
								while ($myrowsel = array_shift($result)) {
									echo "<option value=".$myrowsel["employeeID"]." ";
									if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
									echo " >". $myrowsel["name"] . "</option>";
								} ?>
                            </select>
                          </div>
                          <div class="form-group mb-5">
						    <label>Units</label>
                            <select class="form-control form-control-sm">
                                <option value="No units">No Units</option>
                                <option value="Dollar">Dollars</option>
                                <option value="Percent">Percent</option>
                                <option value="Pound">Pounds</option>
                                <option value="Euros">Euros</option>
                                <option value="Pesos" disabled="">Pesos</option>
                                <option value="Yen" disabled="">Yen</option>
                            </select>
                          </div>
                          <div class="row">
                          <div class="form-group col-md-6 mb-5">
						    <label>Goal</label>
                            <select class="form-control form-control-sm">
                            <option selected="selected" value="EqualTo">Equal to</option>
                            <option value="GreaterThan">Greater than or equal to</option>
                            <option value="GreaterThanNotEqual">Greater than</option>
                            <option value="Between">Between</option>
                            <option value="LessThanOrEqual">Less than or equal to</option>
                            <option value="LessThan">Less than</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6 mb-5">
						    <label>Goal Metrics </label>
						    <input type="text" class="form-control form-control-sm" name="goal" id="goal">
                          </div>
                        </div>
                        <div class="form-group">
                        <label>Attach to Meeting(s) </label>
                        <select class="form-control form-control-sm select2 marix_add_to_meetings" multiple name="marix_add_to_meetings[]" data-placeholder="Not attached to any meetings...">
                            <option></option>
                            <option value="1">Zac Personal</option>
                            <option value="2">Leadership Team L10</option>
                            <option value="1">ZF/DK Same Page</option>
                        </select>
                        </div>
					 </div>
                     <div class="col-md-5">
						  <h5>Advance Controls</h5>
                          <div class="p-4">
                          <div class="form-group mb-5 show_average">
                                <div class="d-flex">
                                    <input type="checkbox" class="mr-1 show_average_check checkbox_lg">
                                    <label class="ml-3 small-font"> Show Average</label>
                                </div>
                                <div class="average_details form_extra_details d-none ">
                                    <div class="form-inline">
                                        <span>Between</span>
                                        <input type="date" class="mx-2 form-control form-control-sm"/>
                                        <span> &nbsp;And Today</span>
                                    </div>
                                </div>
                          </div>
                          <div class="form-group mb-5 show_commulative" >
                            <div class="d-flex">
                                <input type="checkbox" class="mr-1 show_commulative_check checkbox_lg">
                                <label class="ml-3 small-font"> Show Cumulative</label>
                            </div>
                            <div class="commulative_details form_extra_details d-none">
                                <div class="form-inline">
                                    <span>Between</span>
                                    <input type="date" class="mx-2 form-control form-control-sm"/>
                                    <span> &nbsp;And Today</span>
                                </div>
                            </div>
                          </div>
                          <div class="form-group mb-5 show_formula" >
                            <div class="d-flex">
                                <input type="checkbox" class="mr-1 show_formula_check checkbox_lg">
                                <label class="ml-3 small-font">Formula</label>
                            </div>
                            <div class="formula_details form_extra_details  d-none">
                                    <input type="date" class="form-control form-control-sm"/>
                                    <p class="mt-2"><b class="text-danger">CAUTION</b><span> Creating or updating a row formula will overwrite existing data for the entire row. Overwritten data cannot be recovered.</span></p>
                            </div>
                          </div>
                        </div>
					 </div>
                    </div>
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-attendees">
						
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>

	<?
		// Get the current date
		

		// Modify the date to be 1 week (7 days) from today
        $currentDate = new DateTime();
		$oneWeekFromToday1 = $currentDate->modify('+1 week');
		// Format the date as a string (adjust the format as needed)
		$oneWeekFromToday = $oneWeekFromToday1->format('Y-m-d');

	?>
    <div class="modal fade" id="newTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
			<form action="task_action.php" method="post" name="form_task" id="form_task"  onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_task_title">Add New Task</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('task')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
				<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Task Title <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="task_title" id="task_title">
						  <p class="text-danger d-none form_error" id="task_title_error">Please enter Task title </p>
					 </div>
					 <div class="col-md-12 form-group">
						<label>Description </label>
						<div id="summernote-task" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="task_desc_error">Please enter task description </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Select Owner<span class="text-danger">*</span> </label>
                                 <?php 
                                    $user_check_sql = db_query("SELECT id,name,	level,is_supervisor,b2b_id FROM loop_employees where b2b_id='".$_COOKIE['b2b_id']."'",db());
                                    $loggedin_user=array_shift($user_check_sql);
                                    
                                    if($loggedin_user['level']==2 || $loggedin_user['is_supervisor']=="Yes"){
                                        echo '<select class="form-control form-control-sm" id="assignto" name="assignto">';
                                        if($loggedin_user['level']==2){
                                            $other_user_sql=db_query("SELECT id,name,b2b_id from loop_employees where status = 'Active' ORDER BY name ASC",db()); 
                                        }else{
                                           // echo "SELECT id,name,b2b_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC";
                                            $other_user_sql=db_query("SELECT id,name,b2b_id from loop_employees where status = 'Active' and supervisor_name = '".$loggedin_user['b2b_id']."' ORDER BY name ASC",db());
                                            ?>
                                            <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                        
                                        <?}?> 
                                            <? while($user = array_shift($other_user_sql)){?>
                                                <option value="<?= $user['b2b_id']; ?>" <? echo $user['b2b_id'] == $_COOKIE['b2b_id'] ? " selected " : ""; ?>><?= $user['name'];?></option>
                                            <? }
                                        echo '</select>';
                                    } else{ ?>
                                        <select class="form-control form-control-sm" id="assignto" name="assignto" disabled="true">
                                            <option value="<?= $loggedin_user['b2b_id']; ?>"><?= $loggedin_user['name'];?></option>
                                        </select>
                                <? }
                                 ?>
					 </div>
                    
					 <div class="col-md-6 form-group">
						  <label>Due Date <span class="text-danger">*</span> </label>
						  <input type="date" class="form-control form-control-sm" placeholder="" name="task_duedate" id="task_duedate" value="<? echo $oneWeekFromToday; ?>">
						  <p class="text-danger d-none form_error" id="duedate_error">Please select Due Date </p>
					 </div>
					 <div class="col-md-6 form-group">
						<label>Priority<span class="text-danger">*</span> </label>
						<select class="form-control form-control-sm" id="task_priority" name="task_priority">
							<option value="Low">Low</option>
							<option value="Medium" selected>Medium</option>
							<option value="High">High</option>
						</select>
					 </div>
					  <div class="col-md-6 form-group">
						<label>Attach to Meeting</label>
						<select class="form-control form-control-sm" id="task_meeting" name="task_meeting">
                            <?= $meeting_options;?>
						</select>
						<p class="text-danger d-none form_error" id="task_meeting_error">Please Select Meeting</p>
					 
					  </div>
					 <div class="col-md-6 form-group">
						<div id="task_depd_hide" class="d-none"></div>
						 <input type="hidden" id="task_action" name="task_action" value="ADD">
						 <input type="hidden" id="task_id_edit" name="task_id_edit" value="">
					 </div>
					</div>	 
				</div>
                <div class="modal-footer justify-content-between">
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button type="button" id="removeTask" task_id="" class="btn btn-delete d-none"><i class="fa fa-trash-o"></i> Archive</button>
					</div>
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('task')" style="cursor:pointer;" type="button" data-dismiss="modal">Cancel</button>
						<input type="submit" value="Save Task" style="cursor:pointer;" class="btn btn-dark btn-sm" id="save-task">
					</div>
				</div>
			</form>
            </div>
        </div>
    </div>

   

        <!-- Add Page fo edit -->
    <div class="modal fade" id="editPagesNewPageModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <form action="dashboard_meeting_action.php" method="post" name="edit_pages_add_new" id="edit_pages_add_new" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_page_title">Add</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Page Type:</label>
                        <div class="col-sm-9">
                        <select class="form-control form-control-sm"  name="page_type" id="page_type">
                            <option selected="selected" value="">Title Page</option>
                            <option value="Check-in">Check-in</option>
                            <option value="Metrics">Metrics</option>
                            <option value="Projects">Projects</option>
                            <option value="Task">Tasks</option>
                            <option value="Issues">Issues</option>
                            <option value="Conclude">Conclude</option>
                        </select>
                        <p class="text-danger d-none form_error" id="page_type_error">Please Select Page Type </p>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" name="page_title" id="page_title">
                            <p class="text-danger d-none form_error" id="page_title_error">Please Enter Page Title </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Subheading</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" name="page_subheading" id="page_subheading">
                            <p class="text-danger d-none form_error" id="page_subheading_error">Please Enter Page Subheading </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Duration (Minutes):</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" value="5"  name="page_duration" id="page_duration">
                        <p class="text-danger d-none form_error" id="page_duration_error">Please Enter Duration </p>
                        <input type="hidden" id="hidden_edit_page_id" value="" name="edit_page_id"/>
                        <input type="hidden" id="page_action" value="ADD" name="page_action"/>
                        </div>
                    </div>
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-edit-page">
						
                    </div>
                </div>
			</form>
            </div>
        </div>
    </div>


	<!--  Create New Issue Modal-->
    <div class="modal fade" id="newIssue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
			<form action="dashboard_management_action.php" method="post" name="form_issue" id="form_issue" onsubmit="return false;" >
                <div class="modal-header">
                    <h5 class="modal-title"  id="modal_issue_title">Add Issue</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('issue')" >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
					<div class="row">
					 <div class="col-md-12 form-group">
						  <label>Issue <span class="text-danger">*</span> </label>
						  <input type="text" class="form-control form-control-sm" placeholder="" name="issue" id="issue"/>
						  <p class="text-danger d-none form_error" id="issue_error">Please enter Issue </p>
						  <input type="hidden" id="issue_action" name="issue_action" value="ADD">
						  <input type="hidden" id="issue_id_edit" name="issue_id_edit" value="">
						  <input type="hidden" id="hidden_meeting_id_issue_modal" name="hidden_meeting_id_issue_modal" value="">
					 </div>
					 <div class="col-md-12 form-group">
						<label>Detail </label>
						<div id="summernote-issue" class="summernote"></div> 
						<p class="text-danger d-none form_error" id="issue_desc_error">Please enter Issue details </p>
					 </div>
					  <div class="col-md-12 form-group issue_meeting_div">
						<label>Attach to Meeting</label>
						<select class="form-control form-control-sm" id="issue_meeting" name="issue_meeting">
                            <?= $meeting_options;?>
						</select>
						<p class="text-danger d-none form_error" id="issue_meeting_error">Please Select Meeting </p>
					  </div>
					</div>	 
				</div>
                <div class="modal-footer">
					<div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button type="button" id="issueIssue" issue_id="" class="btn btn-delete d-none"><i class="fa fa-trash-o"></i> Archive</button>
					</div>
					
					<div class="btn-right">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<!--<button class="btn btn-light modal_reset_button btn-sm" onclick="reset_form_fields('issue',false)"  style="cursor:pointer;" type="reset">Reset</button>-->
						<button class="btn btn-light modal_cancel_button btn-sm" onclick="reset_form_fields('issue')" data-dismiss="modal"  style="cursor:pointer;" type="button">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-issue">
						
					</div>
                </div>
			</form>
            </div>
        </div>
    </div>


    <!-- Ashiq ScoreCard Modal -->
    <div class="modal fade" id="scorecardAddMatrixModalPopop" role="dialog" aria-labelledby="scorecardAddMatrixModalPopop" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content d-none" id="addExistingMetrics">
			<form id="add_existing_metrics" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title" ><b>Select Existing Measurable </b> <small>or <button class="btn btn-sm button_light" onclick="switchMatrix('new')">create new</button></small></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="reset_form_fields('measurement')">
                        <span aria-hidden="">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="form-group reports_to_div search_existing_metrics w-100">
                        <?
                        if(isset($meeting_id) && $meeting_id != ''){
                            $sql_main =db_query("SELECT meeting_name FROM meeting_master where id=$meeting_id", db());
                            $meeting_name_arr= array_shift($sql_main);
                            $meeting_name=$meeting_name_arr['meeting_name'];
                        }
                        ?>
                        <label>Search for measurables to add them to <?=isset($meeting_name) && $meeting_name != '' ? $meeting_name : ''    ?>: </label>
                        <select class="search_existing_MetricsRock form-control form-control-sm select2" multiple name="existingMeasurable" id="existingMeasurable"  data-placeholder="Search for existing..." >
                            <!-- <option></option> -->
                            <!-- <option value="0" data-kt-rich-content-icon="assets_new_dashboard/img/att1.png"  data-kt-rich-content-desc="Owner: Hannah Apple">New Companies in the Agreement Redlines Received phase</option>
                            <option value="1" data-kt-rich-content-icon="assets_new_dashboard/img/att2.png"  data-kt-rich-content-desc="Owner: Rasheda Nathaniel">B2B Monthly % of QTD</option>
                            <option value="2" data-kt-rich-content-icon="assets_new_dashboard/img/att3.png"  data-kt-rich-content-desc="Owner: Gerald DarSantos (Admin: Edmund John Morelos)">Average Product Rating</option>
                            <option value="3" data-kt-rich-content-no-img="ML" data-kt-rich-content-desc="Owner: DeAngelo Cross (Admin: James Craten)">ML Injuries</option> -->
                        </select>

					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<div class="d-none spinner spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
						</div>
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal" onclick="reset_form_fields('measurement')"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="saveExistingMetrics">
						
                    </div>
                </div>
			</form>
            </div>
            <div class="modal-content" id="addNewMetrics">
                <form action="scorecard.php" method="post" name="" id="" onsubmit="return false;">
                    <div class="modal-header" id="defaultMeasurableHeader">
                        <h5 class="modal-title" id="scorecardAddMatrixModalPopopLabel">Add a Measurable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="reset_form_fields('measurement')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header d-none" id="EditMeetingMatrixHeader">
                        <h5 class="modal-title"><b>Create a New Measurable </b><small>or <button class="btn button_light btn-sm" onclick="switchMatrix('existing')">Select Existing Measurable</button></small></h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body bold_form_label">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-5">
                                    <label>Name <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="name" require>
                                </div>
                                <div class="form-group mb-5">
                                    <label>Who's Accountable:</label>
                                    <select id="accountable" name="accountable"
                                        class="whos_accountable select2 form-control form-control-sm"
                                        data-placeholder="Search For Accountable">
                                        <?
                                        $sql = "SELECT name, initials, employeeID FROM employees where status = 'Active' order by name" ;
                                        $result = db_query($sql , db_b2b() );
                                        while ($myrowsel = array_shift($result)) {
                                            echo "<option value=".$myrowsel["employeeID"]." ";
                                            if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
                                            echo " >". $myrowsel["name"] . "</option>";
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-5">
                                    <label>Units</label>
                                    <select id="units" name="units" class="form-control form-control-sm">
                                        <option selected value="">No units</option>
                                        <option value="$">Dollars</option>
                                        <option value="%">Percents</option>
                                        <option value="£">Pounds</option>
                                        <option value="€">Euros</option>
                                        <option disabled>Pesos</option>
                                        <option disabled>Yan</option>
                                    </select>
                                </div>
                                <div class="row mb-4">
                                    <div class="col betweenmatrics d-none">
                                        <label for="goal_matric">Goal Metric:</label>
                                        <input type="number" class="form-control form-control-sm" id="between_goal_matric"
                                            name="between_goal_matric">
                                    </div>
                                    <div class="col">
                                        <label for="goals">Goal:</label>
                                        <select class="form-control form-control-sm" id="goals" name="goals">
                                            <option selected value="==">Equal to</option>
                                            <option value=">=">Greater than or equal to</option>
                                            <option value=">">Greater than</option>
                                            <option value="<=>">Between</option>
                                            <option value="<=">Less than or equal to</option>
                                            <option value="<">Less than</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="goal_matric">Goal Metric:</label>
                                        <input type="number" class="form-control form-control-sm" id="goal_matric"
                                            name="goal_matric">
                                    </div>
                                </div>

                                <div class="form-group d-none changeMatricsValue">
                                    <label>Update Cell Colors For:</label>
                                    <select class="form-control form-control-sm"  id="changeMatricsValue" name="changeMatricsValue" data-placeholder="Not attached to any meeting...">
                                        <option selected value="futureWeek">Only Future Weeks</option>
                                        <option value="pastANDfutureWeek">Past and Future Weeks</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Attach to Meeting(s) </label>
                                    <select class="marix_add_to_meetings form-control form-control-sm select2" multiple  id="attach_to_meeting" name="attach_to_meeting[]" data-placeholder="Not attached to any meeting...">
                                        <?= $meeting_options;?>
                                    </select>
                                </div>
                                <input type="hidden" name="status" value="add_modal_data">
                            </div>
                            <!-- <div class="col-md-5">
                                <h5>Advance Controls</h5>
                                <div class="p-4">
                                    <div class="form-group mb-5 show_average">
                                        <div class="d-flex">
                                            <input type="checkbox" class="mr-1 show_average_check checkbox_lg">
                                            <label class="ml-3 small-font"> Show Average</label>
                                        </div>
                                        <div class="average_details form_extra_details d-none ">
                                            <div class="form-inline">
                                                <span>Between</span>
                                                <input type="date" class="mx-2 form-control form-control-sm" />
                                                <span> &nbsp;And Today</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-5 show_commulative">
                                        <div class="d-flex">
                                            <input type="checkbox" class="mr-1 show_commulative_check checkbox_lg">
                                            <label class="ml-3 small-font"> Show Cumulative</label>
                                        </div>
                                        <div class="commulative_details form_extra_details d-none">
                                            <div class="form-inline">
                                                <span>Between</span>
                                                <input type="date" class="mx-2 form-control form-control-sm" />
                                                <span> &nbsp;And Today</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-5 show_formula">
                                        <div class="d-flex">
                                            <input type="checkbox" class="mr-1 show_formula_check checkbox_lg">
                                            <label class="ml-3 small-font">Formula</label>
                                        </div>
                                        <div class="formula_details form_extra_details  d-none">
                                            <input type="date" class="form-control form-control-sm" />
                                            <p class="mt-2"><b class="text-danger">CAUTION</b><span> Creating or updating a
                                                    row formula will overwrite existing data for the entire row.
                                                    Overwritten data cannot be recovered.</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-left">
                            <div class="d-none spinner spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <button type="button" id="remove_measurable" class="btn btn-delete d-none"><i
                                    class="fa fa-trash-o"></i> Archive</button>
                        </div>
                        <div class="btn-left">
                            <div class="d-none spinner spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"
                                style="cursor:pointer;" type="reset" onclick="reset_form_fields('measurement')">Cancel</button>
                            <input type="submit" value="Save" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="save-measurable">
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- What VTO -->
    <div class="modal fade" id="openWhatVTOPopup" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="">
			    <div class="modal-header">
                    <h5 class="modal-title" ><b>Division To Open</b></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bold_form_label">
                    <div class="form-group ">
                        <label> What Division: </label>
                        <select id="what-vto-select" class="form-control form-control-sm" >
                            <option value="Usedcarboardboxes">UsedCardboardBoxes.com</option>
                            <option value="UCBZeroWaste">UCBZeroWaste </option>
                            <option value="2ndskid">2ndskid</option>
                        </select>
					</div>	 
				</div>
                <div class="modal-footer">
                    <div class="btn-left"></div>
                    <div class="btn-left">
						<button class="btn btn-light modal_cancel_button btn-sm" data-dismiss="modal"  style="cursor:pointer;" type="reset">Cancel</button>
                        <input type="button" value="Open" style="cursor:pointer;" class="btn btn-dark save_button btn-sm" id="open-vto">	
                    </div>
                </div>
            </div>
        </div>
    </div>