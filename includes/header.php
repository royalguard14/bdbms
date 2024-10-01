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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MMRDCC</title>
  <!-- Bootstrap -->
  <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Custom styling plus plugins -->
  <link href="assets/build/css/custom.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="assets/vendors/jquery/dist/jquery.min.js"></script>
  <style type="text/css">
    /* Styling the label */
    label {
      font-family: 'Arial', sans-serif;
      font-size: 14px;
      color: #555;
      font-weight: bold;
      margin-bottom: 6px; /* Space between label and input */
    }
    .btn-outline {
      outline: 1px solid blue;
      background-color: white; /* Default background color */
      transition: outline 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-outline:hover {
      outline: 2px solid darkblue; /* Thicker outline on hover */
      background-color: #e0f7ff; /* Light blue background on hover */
      box-shadow: 0 0 10px rgba(0, 0, 255, 0.5); /* Add a glowing effect around the button */
    }
  </style>
</head>
<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Zear Developer</span></a>
          </div>
          <div class="clearfix"></div>
          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="assets/profilePic/default.png" alt="..." class="img-circle profile_img">
           
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo ucwords($_SESSION["user_data"]["profile"]["first_name"]. " ". $_SESSION["user_data"]["profile"]["last_name"])?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->
          <br />