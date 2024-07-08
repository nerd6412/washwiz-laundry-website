<?php
session_start();
include('db.php');

if(!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

if(isset($_GET['logout'])) {
    if(isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['email']);
        unset($_SESSION['username']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_SESSION['email'];

    if($password !== $confirmPassword) {
        header('location: account.php?error=passwords do not match');
    } else if(strlen($password) < 8) {
        header('location: account.php?error=password must be at least 8 characters');
    } else {
        $stmt = $conn->prepare("UPDATE users SET passw = ? WHERE user_email = ?");
        $stmt->bind_param('ss', $password, $email);
        if($stmt->execute()) {
            header('location: account.php?message=password updated successfully');
        } else {
            header('location: account.php?error=could not update password');
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
    
        <!----------Account---------->
        <section class="my-5 py-5">
            <div class="row container mx-auto">
                <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
                    <p class="text-center" style="color:green"><?php if(isset($_GET['register_success'])) {echo $_GET['register_success'];}?></p>
                    <p class="text-center" style="color:green"><?php if(isset($_GET['login_success'])) {echo $_GET['login_success'];}?></p>
                    <h2 class="font-weight-bold">Account Information</h2>
                    <hr class="mx-auto">
                    <div class="account-info">
                        <p>Username:  <span><?php if(isset($_SESSION['username'])) {echo $_SESSION['username'];}?></span></p>
                        <p>Email:  <span><?php if(isset($_SESSION['email'])) {echo $_SESSION['email'];}?></span></p>
                        <p><a href="orders.php" id="order-btn" class="btn btn-primary home-btn">Your Orders</a></p>
                        <p><a href="account.php?logout=1" id="logout-btn" class="btn btn-primary home-btn">Logout</a></p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 text-center mt-5 pt-4">
                    <form id="account-form" method="POST" action="account.php">
                        <p class="text-center" style="color:red"><?php if(isset($_GET['error'])) {echo $_GET['error'];}?></p>
                        <p class="text-center" style="color:green"><?php if(isset($_GET['message'])) {echo $_GET['message'];}?></p>
                        <h3>Change Password</h3>
                        <hr class="mx-auto">
                        <div class="form-group mb-2">
                            <input type="password" class="form-control" name="password" id="account-password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="confirmPassword" id="account-password-confirm" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Change Password" name="change_password" class="btn btn-primary home-btn" name="password" id="change-pass-btn">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    
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