<?php
include('db.php');
session_start();

//if user has already registered, take user to acct page
if(isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

  // Password confirmation check
  if($password !== $_POST['confirmPassword']) {
    header('location: signup.php?error=passwords do not match');
    exit;
  } else if(strlen($password) <= 8) { // Use <= to include 8 characters
    header('location: signup.php?error=password must be at least 8 characters');
    exit;
  } else {
    // Check for existing email (prepared statement)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($num_rows);
    $stmt->store_result();
    $stmt->fetch();

    if($num_rows != 0) {
      header('location: signup.php?error=user with this email already exists');
      exit;
    } else {
      // Create new user (prepared statement)
      $userType = 3; // Assuming customer user type
      $stmt = $conn->prepare("INSERT INTO users(fullname, username, user_email, passw, user_address, user_contact) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param('ssssss',  $name, $username, $email, $password, $address, $contact);
      
      if($stmt->execute()) {
        $_SESSION['user_email'] = $email;
        $_SESSION['username'] = $name;
        $_SESSION['logged-in'] = true;
        header('location: account.php?register_success=You registered successfully');
        exit;
      } else {
        header('location: signup.php?error=could not create an account at the moment');
        exit;
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Basic -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Site Metas -->
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="images/WashWiz-favicon.png" type="">
      
        <title> WashWiz - Simplifying Laundry, One Click at a Time </title>
      
      
        <!-- bootstrap core css -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
      
        <!--owl slider stylesheet -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
      
        <!-- font awesome style -->
        <link href="css/font-awesome.min.css" rel="stylesheet" />
      
        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet" />
        <!-- responsive style -->
        <link href="css/responsive.css" rel="stylesheet" />
    
    </head>
      
      <body class="sub_page">
        <div class="hero_area">
          <div class="hero_bg_box">
            <img src="images/slider-bg.jpg" alt="">
          </div>
          <!-- header section starts -->
          <header class="header_section">
            <div class="container">
              <nav class="navbar navbar-expand-lg custom_nav-container ">
                <a href="index.html" class="navbar-brand">
                  <img src="images/WashWiz-logo.png" alt="">
                  <span>WashWiz</span>
                </a>
      
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class=""> </span>
                </button>
      
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav  ">
                    <li class="nav-item ">
                      <a class="nav-link" href="index.html">Home </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="about.html"> About </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="service.html">Services</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item active">
                        <a href="account.php" class="btn my-2 my-sm-0 nav_user-btn">
                            <i class="fa fa-user" aria-hidden="true">
                                <span class="sr-only">(current)</span>
                            </i>
                        </a>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </header>
          <!-- end header section -->
        </div>
      
        <!-- signup section -->
      
        <section class="my-5 py-5">
            <div class="container text-center mt-3 pt-5">
                <h2 class="font-weight-bold">Sign Up</h2>
                <hr class="mx-auto">
            </div>
            <div class="mx-auto container">
                <form id="register-form" method="POST" action="signup.php">
                    <p style="color: red"><?php if(isset($_GET['error'])) { echo $_GET['error']; }?></p>
                    <div class="form-group">
                        <input type="number" class="form-control" id="register-usertype" name="userType" value="3" placeholder="User Type" disabled/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="register-username" name="username" placeholder="Username" required/>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="register-email" name="email" placeholder="Email" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="register-address" name="address" placeholder="Address" required/>
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-control" id="register-contact" name="contact" placeholder="Contact Number" required/>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn" id="register-btn" name="register" value="Sign Up"/>
                    </div>
                    <div class="form-group">
                        <a id="login-url" href="login.php" class="btn">Do you have an Account? Sign In</a>
                    </div>  
                </form>
            </div>
        </section>
      
        <!-- end signup section -->
      
        <!-- info section -->

        <section class="info_section layout_padding2">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-lg-3 info-col">
                        <div class="info_detail">
                            <h4>
                            About
                            </h4>
                            <p>
                            At WashWiz, we understand the struggle of fitting laundry into your busy schedule.
                            That's why we created a convenient and reliable laundry service that allows you 
                            to get your clothes cleaned with just a few clicks on your phone or computer.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-6  info-col">
                        <div class="map_container">
                            <div class="img-box">
                                <img src="images/WashWiz-logo.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 info-col">
                        <div class="info_contact">
                            <h4>
                            Contact Info
                            </h4>
                            <div class="contact_link_box">
                                <p>
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>
                                    Unit 2 #54 Magsaysay Road, Brgy. San Antonio, San Pedro, Laguna
                                    </span>
                                </p>
                                <a href="">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <span>
                                    Call 0948-526-7237 / 0945-425-2751 / 028-742-7577
                                    </span>
                                </a>
                                <a href="">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span>
                                    info@washwiz.com
                                    </span>
                                </a>
                                <p>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <span>
                                    Mon-Sun: 8:00 am - 7:00 pm
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- end info section -->
      
        <!-- footer section -->
        <footer class="footer_section">
            <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="index.html">WashWiz</a>
            </p>
            </div>
        </footer>
        <!-- footer section -->
      
        <!-- jQery -->
        <script src="js/jquery-3.4.1.min.js"></script>
        <!-- popper js -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <!-- bootstrap js -->
        <script src="js/bootstrap.js"></script>
        <!-- owl slider -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
        </script>
        <!-- custom js -->
        <script src="js/custom.js"></script>
      
      </body>
</html>