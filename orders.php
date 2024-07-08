<?php
session_start();
include('db.php');
?>

<!DOCTYPE html>
<html>
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
                <li class="nav-item active">
                    <a class="nav-link" href="orders.php">Orders <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a href="account.php" class="btn my-2 my-sm-0 nav_user-btn">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </a>
                </li>
                </ul>
            </div>
            </nav>
        </div>
        </header>
        <!-- end header section -->
    </div>

    <section class="inventory" id="inventory">
            <div class="inventory__container container grid">
                <div class="table__container">
                    <h1 class="inventory__title text-center">Track Your Order/s!</h1>
                    <table class="inventory__table">
                        <thead>
                            <tr>
                                <th class="text-center">Order ID</th>
                                <th class="text-center">Laundry Load/s</th>
                                <th class="text-center">Laundry Service</th>
                                <th class="text-center">Service Option</th>
                                <th class="text-center">Mode of Payment</th>
                                <th class="text-center">Total Amount Due</th>
                                <th class="text-center">Laundry Order Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        if (!isset($_SESSION['userID'])) {
                          header('location: login.php');
                          exit;
                        }
                        $userID = $_SESSION['userID'];
                        $sqlOrders = "SELECT orderID, userID, no_of_loads, laundry_service, wash_option, mode_of_payment, total_amount, laundry_status
                        FROM laundry_orders
                        WHERE userID = ?";
                        $stmtOrders = $conn->prepare($sqlOrders);
                        $stmtOrders->bind_param('i', $userID);
                        if($stmtOrders->execute()){
                          $resultOrders = $stmtOrders->get_result();

                          if ($resultOrders->num_rows > 0) {
                              while ($row = $resultOrders->fetch_assoc()) {
                                  echo "<tr>";
                                  echo "<td>" . htmlspecialchars($row["orderID"]) . "</td>";
                                  echo "<td>" . htmlspecialchars($row["no_of_loads"]) . "</td>";
                                  echo "<td>" . htmlspecialchars($row["laundry_service"]) . "</td>";
                                  echo "<td>" . htmlspecialchars($row["wash_option"]) . "</td>";
                                  echo "<td>" . htmlspecialchars($row["mode_of_payment"]) . "</td>";
                                  echo "<td>" . htmlspecialchars($row["total_amount"]) . "</td>";
                                  $statusWords = array(
                                    "0" => "Pending",
                                    "1" => "Pickup",
                                    "2" => "Delivery",
                                    "3" => "Claimed"
                                  );
                                  echo "<td>" . htmlspecialchars($statusWords[$row["laundry_status"]]) . "</td>";
                                  echo "</tr>";
                              }
                          } else {
                              echo "<tr><td colspan='4'>No order results found</td></tr>";
                          }
                        } else {
                          // Handle potential errors during query execution (optional)
                          echo "Error: Could not retrieve orders.";
                        }
                        ?>
                        </tbody>
                      </table>                      
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