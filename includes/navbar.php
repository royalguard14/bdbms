<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <h3>General</h3>
    <ul class="nav side-menu">
     <li><a href="index.php?page=dashboard"><i class="fa fa-laptop"></i>Dashboard <span class="label label-success pull-right"></span></a></li>
     <li><a><i class="fa fa-clone"></i> Report <span class="fa fa-chevron-down"></span></a>
      <ul class="nav child_menu">
        <!-- For BRGY USER roles -->
        <?php if ($_SESSION["role"]['name'] == "BRGY USER"): ?>
          <li><a href="index.php?page=report&&section=Uploaded">Uploaded</a></li>
        <?php endif; ?>
        <!-- For BRGY USER roles -->
        <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "BRGY USER"): ?>
         <li><a href="index.php?page=report&&section=Submitted">Submitted</a></li>
       <?php endif; ?>
       <!-- For ADMIN ASSISTANT and HDRRMO ADMIN roles -->
       <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
        <li><a href="index.php?page=report&&section=Verified">Verified</a></li>
        <li><a href="index.php?page=report&&section=Confirmed">Confirmed</a></li>
      <?php endif; ?>
      <!-- For both ADMIN ASSISTANT and BRGY USER roles -->
      <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "BRGY USER"): ?>
        <li><a href="index.php?page=report&&section=Accepted">Accepted</a></li>
        <li><a href="index.php?page=report&&section=Reverted">Reverted</a></li>
      <?php endif; ?>
      <!-- For both ADMIN ASSISTANT and BRGY USER roles -->
      <?php if ($_SESSION["role"]['name'] == "BRGY USER"): ?>
        <li><a href="index.php?page=report&&section=Archived">Archived</a></li>
      <?php endif; ?>
    </ul>
  </li>
</ul>
</div>
<?php if ($_SESSION["role"]['name']=="Zear Developer"): ?>
  <div class="menu_section">
    <h3>ADMIN SETTING</h3>
    <ul class="nav side-menu">
      <li><a><i class="fa fa-bug"></i> Administrative Panel <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="index.php?page=role">Roles Management</a></li>
          <li><a href="index.php?page=permission">Permissions Management</a></li>
          <li><a href="index.php?page=account">Accounts Management</a></li>
        </ul>
      </li>
      <li><a><i class="fa fa-windows"></i> Website Setting <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          <li><a href="index.php?page=city">City Management</a></li>
          <li><a href="index.php?page=brgy">Barangay Management</a></li>
        </ul>
      </li>
    </ul>
  </div>
<?php endif; ?>
<div class="menu_section">
  <h3>System</h3>
  <ul class="nav side-menu">
    <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Logs <span class="label label-success pull-right">Coming Soon</span></a></li>
  </ul>
</div>
</div>
<!-- /sidebar menu -->
<!-- /menu footer buttons -->
<div class="sidebar-footer hidden-small">
  <a data-toggle="tooltip" data-placement="top" title="Settings">
    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Lock">
    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Logout" href="#" id="logout">
    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
  </a>
</div>
<!-- /menu footer buttons -->
</div>
</div>
