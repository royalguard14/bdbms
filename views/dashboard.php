<?php require_once 'controllers/DashboardController.php'; ?>

<!-- top tiles -->
<div class="row" style="display: inline-block;">
    <div class="tile_count col-md-12 col-sm-12">

        <?php if ($_SESSION["role"]['name'] == "BRGY USER"): ?>
            <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-file"></i> Total Reports (This Month)</span>
                <div class="count"><?php echo $totalReportsForMonth; ?></div>
                <span class="count_bottom">Submitted by You</span>
            </div>

            <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-exclamation-circle"></i> Total Pending Reports (This Month)</span>
                <div class="count"><?php echo $pendingReports; ?></div>
                <span class="count_bottom">Pending for Approval</span>
            </div>

            <div class="col-md-4 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-calendar"></i> Total Reports (This Year)</span>
                <div class="count green"><?php echo $totalReportsForYear; ?></div>
                <span class="count_bottom">Submitted by You</span>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
                <div class="count"><?php echo $totalUsers; ?></div>
                <span class="count_bottom">System-Wide</span>
            </div>

            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-map-marker"></i> Total Barangays</span>
                <div class="count"><?php echo $totalBarangays; ?></div>
                <span class="count_bottom">In City</span>
            </div>

            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-file"></i> Reports (This Month)</span>
                <div class="count green"><?php echo $totalReportsForMonth; ?></div>
                <span class="count_bottom">Submitted</span>
            </div>

            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-exclamation-circle"></i> Pending Reports</span>
                <div class="count green"><?php echo $pendingReports; ?></div>
                <span class="count_bottom">For Approval</span>
            </div>

            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-file-o"></i> Not Submitted (This Month)</span>
                <div class="count green"><?php echo $notSubmittedBarangays; ?></div>
                <span class="count_bottom">Barangays</span>
            </div>

            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-calendar"></i> Total Reports (This Year)</span>
                <div class="count green"><?php echo $totalReportsForYear; ?></div>
                <span class="count_bottom">System-Wide</span>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- /top tiles -->

<!-- Recent Activities -->
<div class="col-md-6 col-sm-6">

    <div class="x_panel">
        <div class="x_title">
            <h2>Recent Activities <small>Sessions</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Settings 1</a>
                        <a class="dropdown-item" href="#">Settings 2</a>
                    </div>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="dashboard-widget-content">
                <ul class="list-unstyled timeline widget">
                    <?php
                    $recentActivities = fetchRecentActivities($pdo);
                    foreach ($recentActivities as $activity) {
                        $fullName = htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']);
                        $title = htmlspecialchars($activity['title']);
                        $status = htmlspecialchars($activity['new_status']);
                        $changedAt = date('F d, Y h:i A', strtotime($activity['changed_at']));
                    ?>
                    <li>
                        <div class="block">
                            <div class="block_content">
                                <h2 class="title">
                                    <a>Report: <?php echo $title; ?> (<?php echo $status; ?>)</a>
                                </h2>
                                <div class="byline">
                                    <span><?php echo $changedAt; ?></span> by <a><?php echo $fullName; ?></a>
                                </div>
                                <p class="excerpt">
                                    Status changed to "<?php echo $status; ?>". <a href="#"></a>
                                </p>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Notifications -->
<div class="col-md-6 col-sm-6">
    <div class="x_panel">
        <div class="x_title">
            <h2>Notifications <small>Latest</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Settings 1</a>
                        <a class="dropdown-item" href="#">Settings 2</a>
                    </div>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <ul class="list-unstyled msg_list">
                <?php
                $notifications = fetchNotifications($pdo);
                foreach ($notifications as $notification) {
                    $title = htmlspecialchars($notification['title']);
                    $status = htmlspecialchars($notification['status']);
                    $dateUploaded = date('F d, Y', strtotime($notification['date_uploaded']));
                ?>
                <li>
                    <a>
                        <span class="message">
                            Report "<?php echo $title; ?>" is currently <?php echo $status; ?>.
                        </span>
                        <span class="time"><?php echo $dateUploaded; ?></span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>

</div>
