<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <div class="nav toggle">
      <a id="menu_toggle"><i class="fa fa-bars"></i></a>
    </div>
    <nav class="nav navbar-nav">
      <ul class=" navbar-right">
        <li class="nav-item dropdown open" style="padding-left: 15px;">
          <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
          <img class="gilidphoto" 
     src="<?php echo !empty($_SESSION["user_data"]['profile']['profile_pic']) ? $_SESSION["user_data"]['profile']['profile_pic'] : '/bdbms/images/default-avatar.png'; ?>" 
     alt="Avatar" title="Change the avatar" 
     style="object-fit: contain;">

            <?php echo ucwords($_SESSION["user_data"]["profile"]["first_name"]. " ". $_SESSION["user_data"]["profile"]["last_name"])?>
          </a>
          <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item"  href="index.php?page=profile"> Profile</a>
            <a class="dropdown-item"  href="javascript:;">
             
            </a>
        

           
          </div>
        </li>
        
        <li role="presentation" class="nav-item dropdown open">
          <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-envelope-o"></i>
            <!-- <span class="badge bg-green">6</span> -->
          </a>
          <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
 
<!--   
   
            <li class="nav-item">
              <a class="dropdown-item">
                <span class="image"><img src="assets/images/img.jpg" alt="Profile Image" /></span>
                <span>
                  <span>John Smith</span>
                  <span class="time">3 mins ago</span>
                </span>
                <span class="message">
                  Film festivals used to be do-or-die moments for movie makers. They were where...
                </span>
              </a>
            </li>
             -->
            <li class="nav-item">
              <div class="text-center">
                <a class="dropdown-item">
                  <strong>See All Alerts</strong>
                  <i class="fa fa-angle-right"></i>
                </a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->


