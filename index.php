<?php 
require_once './controllers/connect.php';
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
 $url = "https://";   
else  
 $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];   
    // Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];    
$final_url = explode("/", $url);
$indicator = explode(".",$final_url[count($final_url)-1]);
$findicator = $indicator[0];
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMRDCC</title> <!-- Set a descriptive title -->
  <!-- Stylesheets -->
  <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <link href="assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link href="assets/build/css/custom.min.css" rel="stylesheet">
  <link href="assets/css/mystyle.css" rel="stylesheet">
  <!-- jQuery (Moved for consistency, load before scripts needing it) -->
  <script src="assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendors/Chart.js/dist/Chart.min.js"></script>
</head>
<body class="nav-md footer_fixed">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col menu_fixed">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="index.php?page=dashboard" class="site_title">
              <img src="assets/images/zear_logo.png" alt="Zear Logo" style="width: 2rem; height: 2rem; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);">
              <span>BDBMS</span>
            </a>
          </div>
          <div class="clearfix"></div>
          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img class="img-circle profile_img" 
              src="<?php echo !empty($_SESSION['user_data']['profile']['profile_pic']) 
              ? $_SESSION['user_data']['profile']['profile_pic'] 
              : '/bdbms/assets/images/user.png'; ?>" 
              alt="Avatar" title="Change the avatar" 
              style="object-fit: contain;">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo ucwords($_SESSION["user_data"]["profile"]["first_name"]. " ". $_SESSION["user_data"]["profile"]["last_name"])?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->
          <br />
          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <ul class="nav side-menu">
               <li><a href="index.php?page=dashboard"><i class="fa fa-laptop"></i>Dashboard <span class="label label-success pull-right"></span></a></li>
             </ul>
           </div>
           <div class="menu_section">
        
            <ul class="nav side-menu">
              <?php
    // Define role for cleaner checks
              $role = $_SESSION["role"]['name'];
    // Open all options for Zear Developer
              if ($role === "Zear Developer") {
                echo '
                <li>
                <a>
                <i class="fa fa-money"></i> Budget Setting
                <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="nav child_menu">
                <li><a href="index.php?page=budget&&section=brgyBudget">Barangay Budgeting</a></li>
                <li><a href="index.php?page=budget&&section=budgetPlans">Budget Plans</a></li>
                <li><a href="index.php?page=budget&&section=mybudgetPlan">Barangay Budget Plans</a></li>
                <li><a href="index.php?page=budget&&section=calamity">Barangay Calamity Report</a></li>
                </ul>
                </li>';
              } else {
      // For ADMIN ASSISTANT and HDRRMO ADMIN
                if (in_array($role, ["HDRRMO ADMIN"])) {
                  echo '
                      <h3>Budget Management</h3>
                  <li>
                  <a>
                  <i class="fa fa-money"></i> Budget Setting
                  <span class="fa fa-chevron-down"></span>
                  </a>
                  <ul class="nav child_menu">
                  <li><a href="index.php?page=budget&&section=brgyBudget">Barangay Budgeting</a></li>
                  <li><a href="index.php?page=budget&&section=budgetPlans">Barangay Budget Plans</a></li>
                  </ul>
                  </li>';
                }
      // For BRGY USER
                if ($role === "BRGY USER") {
                  echo '
                  <li>
                  <a>
                  <i class="fa fa-money"></i> Budget Plan
                  <span class="fa fa-chevron-down"></span>
                  </a>
                  <ul class="nav child_menu">
                  <li><a href="#" id="uploadmodalbudget">Upload Budget Plan</a></li>
                  <li><a href="index.php?page=budget&&section=mybudgetPlan">Barangay Budget Plans</a></li>
                  <li><a href="#" id="uploadmodalcalamity">Upload Calamity Report</a></li>

                  <li><a href="index.php?page=budget&&section=calamity">Barangay Calamity Report</a></li>
                  </ul>
                  </li>';
                }
              }
              ?>
            </ul>
          </div>
          <div class="menu_section">
   
<?php 
if ($role === "BRGY USER") {
  echo '
  <h3>Report Management</h3>
            <ul class="nav side-menu">
  <li>
  <a href="#" id="uploadliquidation">
  <i class="fa fa-upload"></i> Upload Liquidation 
  <span class="label label-success pull-right"></span>
  </a>
  </li>


    <li>
  <a href="index.php?page=report&&section=allstatus" >
  <i class="fa fa-files-o"></i> Files Routing
  <span class="label label-success pull-right"></span>
  </a>
  </li>

            </ul>'

  ;
}
if ($role === "ADMIN ASSISTANT"){
  echo '
    <h3>Report Management</h3>
            <ul class="nav side-menu">
  <li>
  <a href="index.php?page=report&&section=allstatus">
  <i class="fa fa-files-o"></i> Report Status 
  <span class="label label-success pull-right"></span>
  </a>
  </li>
  </ul>'
  ;
}

if ($role === "HDRRMO ADMIN"){
  echo '
    <h3>Report Management</h3>
            <ul class="nav side-menu">
  <li>
  <a href="index.php?page=report&&section=head">
  <i class="fa fa-files-o"></i> Report Status 
  <span class="label label-success pull-right"></span>
  </a>
  </li>
  </ul>'
  ;
}







             ?>
    
          </div>
          <?php if ($_SESSION["role"]['name'] == "Zear Developer"): ?>
            <div class="menu_section">
              <h3>ADMIN SETTING</h3>
              <ul class="nav side-menu">
                <!-- Administrative Panel -->
                <li>
                  <a>
                    <i class="fa fa-bug"></i> Administrative Panel 
                    <span class="fa fa-chevron-down"></span>
                  </a>
                  <ul class="nav child_menu">
                    <li><a href="index.php?page=role">Roles Management</a></li>
                    <li><a href="index.php?page=permission">Permissions Management</a></li>
                    <li><a href="index.php?page=account">Accounts Management</a></li>
                  </ul>
                </li>
                <!-- Website Setting -->
                <li>
                  <a>
                    <i class="fa fa-windows"></i> Website Setting 
                    <span class="fa fa-chevron-down"></span>
                  </a>
                  <ul class="nav child_menu">
                    <li><a href="index.php?page=city">City Management</a></li>
                    <li><a href="index.php?page=brgy">Barangay Management</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          <?php endif; ?>
        </div>
        <!-- /sidebar menu -->
        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
         
          <a data-toggle="tooltip" data-placement="top" title="Logout" href="#" id="logout" style="width:100%">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          </a>
        </div>
        <!-- /menu footer buttons -->
      </div>
    </div>
    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <li class="">
             <a href="javascript:;" 
             class="user-profile dropdown-toggle" 
             data-toggle="dropdown" 
             aria-expanded="false">
             <!-- Display user profile picture or default avatar -->
             <img class="gilidphoto" 
             src="<?php echo !empty($_SESSION['user_data']['profile']['profile_pic']) 
             ? $_SESSION['user_data']['profile']['profile_pic'] 
             : '/bdbms/assets/images/user.png'; ?>" 
             alt="Avatar" 
             title="Change the avatar" 
             style="object-fit: contain;">
             <!-- Display user full name -->
             <?php 
             echo ucwords(
              $_SESSION['user_data']['profile']['first_name'] . ' ' . 
              $_SESSION['user_data']['profile']['last_name']
            ); 
            ?>
            <!-- Dropdown arrow -->
            <span class="fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a class="dropdown-item"  href="index.php?page=profile"> Profile</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3 style="font-size: 1.3rem;">
          <?php 
          if ($role == 'BRGY USER') {
            echo "BRGY " . $_SESSION['user_data']['barangay_name'] . " <small>DISASTER BUDGET MANAGEMENT SYSTEM</small>";
          } else {
            echo "DISASTER BUDGET MANAGEMENT SYSTEM";
          }
          ?>
        </h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    if (isset($_SESSION['log_in'])) {
      if (in_array($page, ['role', 'permission', 'account', 'city', 'brgy'])) {
        include 'views/' . $page . '/index.php';
      } elseif ($page == 'report') {
        $required_permissions_brgyLevel = ['Upload Report', 'View Submitted', 'View Accepted', 'View Reverted', 'View Archived','toVerified','ToConfirm','Read Confirm'];
        if (array_intersect($required_permissions_brgyLevel, $_SESSION['user_permissions'])) {
          include 'views/report/'.$_GET['section'].'.php';
        }
      }elseif($page == 'profile'){
        include 'views/account/'.$_GET['page'].'.php';
      }elseif($page == 'budget'){
        $req_permission_for_budgeting = ['Manage Budget', 'My Liquidation' ];
        if (array_intersect($req_permission_for_budgeting, $_SESSION['user_permissions'])) {
          include 'views/budget/'.$_GET['section'].'.php';
        }
      }else {
        include 'views/dashboard.php';
      }
    } else {
      header('Location: views/auth/login.php');
      exit;
    }
    ob_end_flush(); 
    ?>
  </div>
</div>
<!-- /page content -->
<!-- footer content -->
<footer>
  <div class="pull-right">
    <span>Developed by: </span><strong>DENVER V., JOY T., MARK KEVIN S.</strong> <span>SMCC CCIS</span>
  </div>
  <div class="clearfix"></div>
</footer>


<!-- /footer content -->
</div>
</div>



<!-- Modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modalLiq">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4 col-sm-4 form-group">
            <h4 class="modal-title" id="myModalLabel">Form Information</h4>
          </div>
          <div class="col-md-4 col-sm-4 form-group" style="text-align: right; margin-top: .5rem;">
            <label class="control-label">Date today</label>
          </div>
          <div class="col-md-4 col-sm-4 form-group">
            <input type="text" class="form-control" id="currentDateTime1" readonly="readonly" value="">
          </div>
        </div>
        <hr>
        <form id="modalLiqForm" enctype="multipart/form-data">
          <div class="row">
            <!-- Dynamic fields will be appended here -->
            <div class="col-md-12 col-sm-12 form-group submits">
              <button type="submit" class="btn btn-primary col-md-12 col-sm-12" id="submitUpload" name="submitUpload">Upload</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
  $('#uploadmodalbudget').on('click', function () {



    document.getElementById('currentDateTime1').value = getFormattedDateTime();
    // Show the modal
    $('#modalLiq').modal('show');

    // Reset the form
    $('#modalLiqForm')[0].reset();



    // Fetch budget plans via AJAX
    $.ajax({
      url: 'controllers/LiquidationController.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'fetch_budget_plans' }),
      success: function (response) {
   
        // Parse the response if it comes as a string
        const parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

        // Remove previous dynamic fields to prevent duplicates
        $('.dynamic-fields').remove();




console.log(response);




 additionalFields = `
        <input type="hidden" name="form_type" value="2">
        <div class="col-md-12 col-sm-12 form-group dynamic-fields">
        <label for="title">Thematic Area plan</label>
        
        <select name="name_extention" class="form-control" required>

        <option  disabled selected> Select Here</option>
        <option value="DISASTER PREVENTION AND MITIGATION">A. DISASTER PREVENTION AND MITIGATION</option>
        <option value="DISASTER PREPAREDNESS">B. DISASTER PREPAREDNESS</option>
        <option value="DISASTER RESPONSE">C. DISASTER RESPONSE</option>
        <option value="DISASTER REHABILITIES AND RECOVERY">D. DISASTER REHABILITIES AND RECOVERY</option>

        </select>
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="title">Form Title</label>
        <input type="text" name="title" id="title" class="form-control" required placeholder="Enter form title">
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields" hidden>
        <label for="period_covered">Period Covered</label>
        <input type="date" name="period_covered" id="period_covered" class="form-control"  placeholder="Enter the period covered">
       
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="amount_budget">Amount Budget</label>
        <input type="number" name="amount_budget" id="amount_budget" class="form-control" required placeholder="Enter the budget amount" step="0.01" min="0" max="${parsedResponse.availblebudgetoffer}">
        <span>Remaining Balance: ${parsedResponse.availblebudgetoffer}</span>
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="uploaded_file_budget">Upload File Here</label>
        <input type="file" name="uploaded_file" id="uploaded_file_budget" class="form-control" required accept=".pdf">
        </div>`
 $('.submits').before(additionalFields);


}});












  });








  $('#uploadmodalcalamity').on('click', function () {
    document.getElementById('currentDateTime1').value = getFormattedDateTime();
    // Show the modal
    $('#modalLiq').modal('show');

    // Reset the form
    $('#modalLiqForm')[0].reset();


    // Fetch budget plans via AJAX
    $.ajax({
      url: 'controllers/LiquidationController.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'fetch_budget_plans' }),
      success: function (response) {
   
        // Parse the response if it comes as a string
        const parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

        // Remove previous dynamic fields to prevent duplicates
        $('.dynamic-fields').remove();




 additionalFields = `
        <input type="hidden" name="form_type" value="5">
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="title">Form Title</label>
        <input type="text" name="title" id="title" class="form-control" required placeholder="Enter form title">
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields" hidden>
        <label for="period_covered">Period Covered</label>
        <input type="date" name="period_covered" id="period_covered" class="form-control"  placeholder="Enter the period covered">
       
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="amount_budget">Amount Budget</label>
        <input type="number" name="amount_budget" id="amount_budget" class="form-control" required placeholder="Enter the budget amount" step="0.01" min="0" max="${parsedResponse.remainingQRF}" >



        <span>Remaining Balance: ${parsedResponse.remainingQRF}</span>
        </div>
        <div class="col-md-6 col-sm-6 form-group dynamic-fields">
        <label for="uploaded_file_budget">Upload File Here</label>
        <input type="file" name="uploaded_file" id="uploaded_file_budget" class="form-control" required accept=".pdf">
        </div>`
 $('.submits').before(additionalFields);









}
})










  });










  $('#uploadliquidation').on('click', function () {
  
    // Set the current date and time
    document.getElementById('currentDateTime1').value = getFormattedDateTime();

    // Show the modal
    $('#modalLiq').modal('show');

    // Reset the form
    $('#modalLiqForm')[0].reset();





    // Fetch budget plans via AJAX
    $.ajax({
      url: 'controllers/LiquidationController.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ action: 'fetch_budget_plans' }),
      success: function (response) {
   
        // Parse the response if it comes as a string
        const parsedResponse = typeof response === 'string' ? JSON.parse(response) : response;

        // Remove previous dynamic fields to prevent duplicates
        $('.dynamic-fields').remove();

        // Generate options for the budget plan dropdown
        let options = '<option value="">Select Budget Plan</option>';
        if (parsedResponse.success && parsedResponse.budgetPlans.length > 0) {
          parsedResponse.budgetPlans.forEach(plan => {
            options += `<option value="${plan.id}">${plan.title} (Period: ${plan.period_covered})</option>`;
          });
        } else {
          options = '<option value="">No Budget Plans Available</option>';
        }

        // Create additional dynamic fields

        const additionalFields = `
        <input type="hidden" name="form_type" value="4">
          <div class="col-md-6 col-sm-6 form-group dynamic-fields">
            <label for="budget_plan_id">Select Budget Plan</label>
            <select name="budget_plan_id" id="budget_plan_id" class="form-control" required>
              ${options}
            </select>
          </div>
          <div class="col-md-6 col-sm-6 form-group dynamic-fields">
            <label for="amount_spent">Amount Spent</label>
            <input type="number" name="amount_spent" id="amount_spent" class="form-control" required placeholder="Enter the amount spent" step="0.01" min="0" max="${parsedResponse.remainingbudget}">
            <span>Remaining Balance: ${parsedResponse.remainingbudget}</span>
          </div>
          <div class="col-md-12 col-sm-12 form-group dynamic-fields">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required placeholder="Enter a brief description" rows="5" style="resize: none;"></textarea>
          </div>
          <div class="col-md-6 col-sm-6 form-group dynamic-fields">
            <label for="liquidation_date">Liquidation Date</label>
            <input type="date" name="liquidation_date" id="liquidation_date" class="form-control" required>
          </div>
          <div class="col-md-6 col-sm-6 form-group dynamic-fields">
            <label for="uploaded_file_liquidation">Upload Supporting Document</label>
            <input type="file" name="uploaded_file" id="uploaded_file_liquidation" class="form-control" required accept=".pdf">
          </div>
        `;

        // Append the fields after the submit button
        $('.submits').before(additionalFields);
      },
      error: function (xhr, status, error) {
        console.error('Error details:', xhr.responseText, status, error);
        alert('Failed to fetch budget plans. Please try again.');
      }
    });
  });

  // Function to get formatted date and time
  function getFormattedDateTime() {
    const now = new Date();
    return now.toISOString().slice(0, 19).replace('T', ' '); // Example: "2024-12-02 15:30:45"
  }




  // Attach an event listener to the input field
document.addEventListener('DOMContentLoaded', () => {
    const amountSpentInput = document.getElementById('amount_spent');

    if (amountSpentInput) {
        amountSpentInput.addEventListener('input', () => {
            const maxLimit = parseFloat(amountSpentInput.getAttribute('max')); // Get the max attribute
            const currentValue = parseFloat(amountSpentInput.value); // Get the current input value
            
            // If the current value exceeds the max limit, set it to the max limit
            if (currentValue > maxLimit) {
                amountSpentInput.value = maxLimit;
            }
        });
    }
});

</script>


<!-- jQuery -->
<script src="assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- FastClick -->
<script src="assets/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="assets/vendors/nprogress/nprogress.js"></script>
<!-- Chart.js -->
<!-- jQuery Sparklines -->
<script src="assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- Morris.js -->
<script src="assets/vendors/raphael/raphael.min.js"></script>
<script src="assets/vendors/morris.js/morris.min.js"></script>
<!-- Gauge.js -->
<script src="assets/vendors/gauge.js/dist/gauge.min.js"></script>
<!-- Bootstrap Progressbar -->
<script src="assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- Skycons -->
<script src="assets/vendors/skycons/skycons.js"></script>
<!-- Flot -->
<script src="assets/vendors/Flot/jquery.flot.js"></script>
<script src="assets/vendors/Flot/jquery.flot.pie.js"></script>
<script src="assets/vendors/Flot/jquery.flot.time.js"></script>
<script src="assets/vendors/Flot/jquery.flot.stack.js"></script>
<script src="assets/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot Plugins -->
<script src="assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="assets/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="assets/vendors/DateJS/build/date.js"></script>
<!-- Bootstrap Daterangepicker -->
<script src="assets/vendors/moment/min/moment.min.js"></script>
<script src="assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- Bootstrap WYSIWYG -->
<script src="assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="assets/vendors/google-code-prettify/src/prettify.js"></script>
<!-- Datatables -->
<script src="assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="assets/vendors/jszip/dist/jszip.min.js"></script>
<script src="assets/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- Custom Theme Scripts -->
<script src="assets/build/js/custom.min.js"></script>
<!-- Custom -->

<script type="text/javascript">

















  $(document).on('submit', '#modalLiqForm', function(e) {
    e.preventDefault();
   
    let formData = new FormData(this); 
    $.ajax({
      url: 'controllers/UploadController.php?action=upload', 
      type: 'POST',
      data: formData,
      contentType: false, 
      processData: false, 
      success: function(response) {
        //let result = JSON.parse(response);
        if (response.success) {
          location.reload(); 
        } else {
          alert(result.message);
        }
      }
    });
  });
</script>
<script>
  document.getElementById('logout').onclick = function(event) {
        event.preventDefault(); // Prevent the default link behavior
        var action = 'logout';
        var data = {
          action: action,
        };
        $.ajax({
          url: './controllers/Auth.php',
          data: JSON.stringify(data),
          contentType: 'application/json',
          method: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.success) {
                    // Reload the page after successful logout
              location.reload();
            } else {
              alert('Logout failed. Please try again.');
            }
          },
          error: function(xhr, status, error) {
            alert('An error occurred. Please try again later.');
          }
        });
      };
    </script>
    <script type="text/javascript">
      function getFormattedDateTime() {
        const today = new Date();
        const options = { 
          month: 'long', 
          day: 'numeric', 
          year: 'numeric',
          hour: 'numeric',
          minute: 'numeric',
          hour12: true 
        };
        return today.toLocaleString('en-US', options).replace('at', ' |');
      }
      function formatDateTime(dateString) {
        let date = new Date(dateString);
    // Format the date
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        let formattedDate = date.toLocaleDateString('en-US', options);
    // Format the time
        let timeOptions = { hour: 'numeric', minute: 'numeric', hour12: true };
        let formattedTime = date.toLocaleTimeString('en-US', timeOptions);
    // Return the formatted string
        return formattedDate + ' | ' + formattedTime;
      }
      function formatDateTimeMY(dateString) {
        let date = new Date(dateString);
    // Format the date to show only month and year
        let options = { year: 'numeric', month: 'long' };
        let formattedDate = date.toLocaleDateString('en-US', options);
    // Return only the formatted month and year
        return formattedDate;
      }
    </script>
  </body>
  </html>