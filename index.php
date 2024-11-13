<?php
ob_start();
session_start();
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/topbar.php';
?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3 style="font-size: 1.3rem;">BRGY <?php echo $_SESSION['user_data']['barangay_name'] ?> <small>DISASTER BUDGET MANAGEMENT SYSTEM </small></h3>
      </div>

     


      <div class="title_right">
        <div class="col-md-5 col-sm-5   form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-secondary" type="button">Go!</button>
            </span>
          </div>
        </div>
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
  }
   else {
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
<?php require_once 'includes/footer.php'; ?>   
</div>
</div>
<!-- jQuery -->
<script src="assets/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- FastClick -->
<script src="assets/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="assets/vendors/nprogress/nprogress.js"></script>
<!-- bootstrap-wysiwyg -->
<script src="assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="assets/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="assets/vendors/google-code-prettify/src/prettify.js"></script>
<script type="text/javascript">
</script>
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
</body>
</html>