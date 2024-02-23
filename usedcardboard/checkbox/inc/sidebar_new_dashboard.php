 <!-- Sidebar -->
<?  
    //check for show & hide sidebar labels
    //$sidebar_full="yes" ;
    $sidebar_width_cls=$sidebar_full != "yes" ? "thinner_sidebar" : "";
    ?>
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled <?= $sidebar_width_cls; ?>" id="accordionSidebar" $sidebar_width>
		<!-- Nav Item - Dashboard -->
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item" data-toggle="tooltip" >
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="javascript:;"  <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Company VTO"' ?>>
                    <span data-toggle="modal" data-target="#openWhatVTOPopup"> 
                    <i class="fas fa-fw fa-building"></i>
                    <? if($sidebar_full=="yes"){?><span>Company V/TO</span> <? } ?>
                    </span>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="core_values.php" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Core Values"' ?>>
                    <i class="fas fa-fw fa-thumbs-up"></i>
                    <? if($sidebar_full=="yes"){?><span>Core Values</span><? } ?>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="dashboard_meetings.php" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Meetings"' ?>>
                    <i class="fas fa-fw fa-handshake"></i>
                    <? if($sidebar_full=="yes"){?> <span>Meetings</span> <? } ?>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="dashboard_management_v1.php#scoreboard-section" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Scorecard"' ?>>
                    <i class="fas fa-fw fa-star"></i>
                    <? if($sidebar_full=="yes"){?>  <span>Scorecard</span> <? } ?>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="dashboard_management_v1.php#project-section" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Projects"' ?>>
                    <i class="fas fa-fw fa-snowflake"></i>
                    <? if($sidebar_full=="yes"){?> <span>Projects (<span class="d-inline-block" id="sidebar_project_count"><?php echo $project_count; ?></span>)</span> <? } ?>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="dashboard_management_v1.php#task-section" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Tasks"' ?>>
                    <i class="fas fa-fw fa-tasks"></i>
                    <? if($sidebar_full=="yes"){?> <span>Tasks (<span class="d-inline-block" id="sidebar_task_count"><?php echo $task_count; ?></span>)</span> <? } ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed <?= $sidebar_full=='yes' ? '':'mytooltip'; ?>" href="dashboard_management_v1.php#issue-section" <?= $sidebar_full=='yes' ? '':'data-toggle="tooltip" data-placement="bottom" title="Issue"' ?>>
                    <i class="fas fa-exclamation-circle fa-fw "></i>
                    <? if($sidebar_full=="yes"){?> <span>Issue (<span class="d-inline-block" id="sidebar_issue_count"><?php echo $issue_count; ?></span>)</span> <? } ?>
                </a>
            </li>
	

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <!--<div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            -->
         

        </ul>
        <!-- End of Sidebar -->