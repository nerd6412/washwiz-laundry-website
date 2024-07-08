<?php 

session_start();
include('db.php');

//Create SQL query to fetch the latest order (assuming orderID is auto-incremented)
  $sql = "SELECT * FROM laundry_orders ORDER BY orderID DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $orderId = $row["orderID"];
    $noOfLoads = $row["no_of_loads"];
    $typeOfClothes = $row["type_of_clothes"];
    $laundryService = $row["laundry_service"];
    $washOption = $row["wash_option"];
    $noOfPieces = $row["no_of_pieces"]; // Assuming a column for number of pieces
    $addOn = $row["add_on"];
    $noteToStaff = $row["note_to_staff"];

    // Calculate total amount based on laundry service
    $totalAmount = 0;
    $plasticBags = $noOfLoads * 4.50; // Plastic bag cost per load

    switch ($laundryService) {
      case "Regular Wash":
        if($washOption == "Wash - ₱65.00") {
          $washOptionPrice = 65.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Dry - ₱70.00") {
          $washOptionPrice = 70.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Full Service - ₱165.00") {
          $washOptionPrice = 165.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        }
        break;
      case "Dry Clean":
        if($washOption == "Barong/Tops/Coat - ₱250.00") {
          $washOptionPrice = 250.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Dress/Terno - ₱450.00") {
          $washOptionPrice = 70.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Shoes/Bag - ₱500.00") {
          $washOptionPrice = 500.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        }
        break;
      case "Special Wash":
        if($washOption == "Warm Wash - ₱80.00") {
          $washOptionPrice = 80.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Hot Wash - ₱90.00") {
          $washOptionPrice = 90.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Disinfect Wash - ₱120.00") {
          $washOptionPrice = 120.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        }
        break;
      case "ComforterBulky":
        if($washOption == "Wash + Dry - ₱160.00") {
          $washOptionPrice = 160.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        } else if($washOption == "Wash + Dry + Fold - ₱185.00") {
          $washOptionPrice = 185.00;
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        }
        break;
      case "Executive Wash":
        $washOptionPrice = explode(" - ", $washOption)[1];
        $washOptionPrice = preg_replace('/\D/', '', $washOptionPrice);
        $washOptionPrice = (float)$washOptionPrice;

        // Include noOfPieces only if it has a value
        if (!empty($noOfPieces)) {
          $totalAmount = $noOfLoads * ($washOptionPrice * $noOfPieces) + $plasticBags;
        } else {
          // Handle missing noOfPieces (optional: set default value or display message)
          $totalAmount = $noOfLoads * $washOptionPrice + $plasticBags;
        }
        break;
      default:
        $totalAmount = 0; // Handle unexpected service
    }

    // Calculate add-on cost (assuming price per load)
    if (!empty($addOn)) {
      // Convert addOn string to array of integers (prices)
      $addOnPrices = array_map('intval', explode(",", $addOn));
      $addOnTotal = array_sum($addOnPrices) * $noOfLoads;
      $totalAmount += $addOnTotal;
    }

    // Format total amount for display
    $totalAmountFormatted = number_format($totalAmount, 2, ".", ",");

    $sqlUpdate = "UPDATE laundry_orders SET total_amount = ? WHERE orderID = ?";

    if ($stmt = $conn->prepare($sqlUpdate)) {

      // Bind values to prepared statement
      $stmt->bind_param("di", $totalAmount, $orderId);
      $stmt->execute();

      if ($stmt->affected_rows === 1) {
        echo "Total amount successfully updated!";
      } else {
        echo "Failed to update total amount!";
      }

      // Close the prepared statement
      $stmt->close();
    } else {
      echo "Error preparing statement: " . $conn->error;
    }

} else {
  echo "Order not found!";
}

// Function to clean user input (optional but recommended)
function cleanInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

        <section class="my-5 py-5 mx-5 px-5">
          <div class="container text-center mt-3 pt-5">
              <h2 class="font-weight-bold">Receipt</h2>
              <hr class="mx-auto">
              <h5 class="text-center">
                Here is the breakdown of the total amount due. Take a screenshot for safekeeping
                in case issues arise. Thank you for trusting WashWiz!
              </h5>
          </div>
          <div class="mx-5 px-5 container">
            <div id="receipt-form" class="account-info mt-5 mx-5 px-5">
              <p>Order ID:  <span><?php echo isset($orderId) ? $orderId : ""; ?></span></p>
              <p>No. of Loads:  <span><?php echo $noOfLoads; ?></span></p>
              <p>Type of Clothes:  <span><?php echo $typeOfClothes; ?></span></p>
              <p>Laundry Service:  <span><?php echo $laundryService; ?></span></p>
              <p>Wash Option:  <span><?php echo $washOption; ?></span></p>
              <?php
              if ($laundryService === "Executive Wash") {
                echo "<p>No. of Pieces: <span>$noOfPieces</span></p>";
              }
              ?>
              <p>Add On (optional):  <span><?php echo !empty($addOn) ? $addOn : "None"; ?></span></p>
              <p>Note to Staff (optional):  <span><?php echo $noteToStaff; ?></span></p>
              <hr>
              <p><b>Total Amount Due:</b>  <span>&#8369; <?php echo $totalAmountFormatted; ?></span></p>
            </div>
          </div>
          
          <div class="btn-box text-center">
            <a href="orders.php">
                Track My Order
            </a>
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