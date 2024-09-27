<?php 
session_start();
if (isset($_SESSION['log_in'])) { 
  header('location:../../index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BDBMS-Login</title>
  <!-- Bootstrap -->
  <link href="../../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="../../assets/vendors/animate.css/animate.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="../../assets/build/css/custom.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="../../assets/vendors/jquery/dist/jquery.min.js"></script>
</head>
<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form id="loginForm">
            <h1>Login Form</h1>
            <div>
              <input type="text" id="username" class="form-control" placeholder="Email" required="" />
            </div>
            <div>
              <input type="password" id="password" class="form-control" placeholder="Password" required="" />
            </div>
            <div>
              <button type="submit" class="btn btn-default submit">Log in</button>
              <a class="reset_pass" href="#">Lost your password?</a>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
              <div class="clearfix"></div>
              <br />
              <div>
                <h1>BRGY. DISASTER BUDGET <br> MANAGEMENT SYSTEM</h1>
                <p>FOR MUNICIPAL DISASTER RISK REDUCTION MANAGEMENT OFFICE</p>
              </div>

            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
<script type="text/javascript">
   $(document).on("submit", "#loginForm", function(e) {
    e.preventDefault();
    
    var username = $('#username').val();
    var password = $('#password').val();
    var action = 'login';
    
    // Simple validation (optional)
    if (username === '' || password === '') {
      alert('Please enter both username and password.');
      return;
    }

    var data = {
      username: username,
      password: password,
      action: action,
    };

    $.ajax({
      url: '../../controllers/Auth.php',
      data: JSON.stringify(data),
      contentType: 'application/json',
      method: 'POST',
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Refresh the page on successful login
          location.reload();
        } else {
          // Display an error message from the server response
          alert(response.message || 'Login failed. Please try again.');
        }
      },
      error: function(xhr, status, error) {
        // Handle server errors or connection issues
        alert('An error occurred. Please try again later.');
      }
    });
  });
</script>
<!-- <script type="text/javascript">
 $(document).on("click", "#register", function(e) {
  e.preventDefault();
  var username = $('#username').val();
  var password = $('#password').val();
  var action = 'register';
  var data = {
    username: username,
    password: password,
    action: action,
  };
  $.ajax({
    url: '../../controllers/Auth.php',
    data: JSON.stringify(data),
    contentType: 'application/json',
    method: 'POST',
    dataType: 'json',
    success: function(response) {
      console.log(response);
    },
    error: function(xhr, status, error) {
    }
  });
});
</script> -->
</body>
</html>