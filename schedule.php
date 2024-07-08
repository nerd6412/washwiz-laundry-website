<?php
session_start();
include('db.php');

if(!isset($_SESSION['logged_in'])) {
    header('location: login.php');
    exit;
}

$noOfLoads = "";
$typeOfClothes = "";
$laundryService = "";
$washOption = "";
$addOn = "";
$noteToStaff = "";
$modeOfPayment = "";

// Check if form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $noOfLoads = cleanInput($_POST["noOfLoads"]);
  $typeOfClothes = cleanInput($_POST["typeOfClothes"]);
  $laundryService = cleanInput($_POST["laundryService"]);
  $washOption = cleanInput($_POST["washOption"]); // Assuming you select only one radio button
  $noOfPieces = cleanInput($_POST["noOfPieces"]);
  $modeOfPayment = cleanInput($_POST["modeOfPayment"]);

  // Handle multiple select options for addOn
  $addOn = "";
  if (!empty($_POST["addOn"])) {
    $addOn = implode(",", $_POST["addOn"]); // Combine addOns into a comma-separated string
  }

  $noteToStaff = cleanInput($_POST["noteToStaff"]);

  // $sql1 = "SELECT * FROM users ORDER BY userID DESC LIMIT 1";
  // $result1 = $conn->query($sql1);

  //if($result1->num_rows > 0) {
    //$row = $result1->fetch_assoc();

    $userId = $_SESSION['userID'];
    // Create SQL query
    $sql = "INSERT INTO laundry_orders (userID, no_of_loads, type_of_clothes, laundry_service, wash_option, no_of_pieces, add_on, note_to_staff, mode_of_payment, laundry_status, date_created) 
    VALUES ('$userId', '$noOfLoads', '$typeOfClothes', '$laundryService', '$washOption', '$noOfPieces', '$addOn', '$noteToStaff', '$modeOfPayment', '0', NOW())";

    if ($conn->query($sql) === TRUE) {
    header('location: receipt.php');
    exit;
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
  //}

  // Close connection
  $conn->close();
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

        <!-- schedule order section -->
        <section class="my-5 py-5">
            <div class="container text-center mt-3 pt-5">
                <h2 class="font-weight-bold">Schedule a Pickup</h2>
                <hr class="mx-auto">
            </div>
            <div class="mx-auto container">
                <form id="schedule-form" method="POST" action="schedule.php">
                    <div class="form-group">
                        <label for="noOfLoads">No. of Loads (8kg/load):</label>
                        <input type="number" class="form-control" id="noOfLoads" name="noOfLoads" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="typeOfClothes">Type of Clothes:</label>
                        <select class="form-control" id="typeOfClothes" name="typeOfClothes" required>
                        <option value="">Select Type</option>
                        <option value="Regular">Regular</option>
                        <option value="Bulky">Bulky (comforter, denim, etc.)</option>
                        <option value="Accessories">Accessories (shoes, bag, coats, etc.)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="laundryService">Laundry Service:</label>
                        <select class="form-control" id="laundryService" name="laundryService" required>
                            <option value="">Select Service</option>
                            <option value="Regular Wash">Regular Wash</option>
                            <option value="Executive Wash">Executive Wash</option>
                            <option value="Special Wash">Special Wash</option>
                            <option value="ComforterBulky">Comforter/Bulky</option>
                            <option value="Dry Clean">Dry Clean</option>
                        </select>
                    </div>
                    <div class="form-group" id="washOptions" style="display: none;"> <label for="washOptions">Wash Options:</label>
                        <div id="washOptionsContent"></div> </div>
                    <div class="form-group">
                        <label for="addOn">Add On (optional):</label>
                        <select class="form-control" id="addOn" name="addOn[]" multiple> 
                            <option value="">Select Add On</option>
                            <option value="Add Wash">Add Wash - ₱20.00</option>
                            <option value="Add Rinse">Add Rinse - ₱20.00</option>
                            <option value="Add Dry">Add Dry - ₱20.00</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noteToStaff">Note to Staff (optional):</label>
                        <textarea class="form-control" id="noteToStaff" name="noteToStaff" rows="3"></textarea>
                    </div>
                    <h5 class="text-center">Mode of Payment</h5>
                    <div class="form-group">
                        <select class="form-control" id="modeOfPayment" name="modeOfPayment" required>
                            <option value="">Select Mode of Payment</option>
                            <option value="Cash on Delivery (COD)">Cash on Delivery (COD)</option>
                            <option value="GCash Payment">GCash Payment</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn" id="submit-btn" name="submit_btn" value="Submit Order"/>
                    </div>
                </form>

                <script>
                    // Javascript to handle dynamic wash options
                    const laundryServiceSelect = document.getElementById('laundryService');
                    const washOptions = document.getElementById('washOptions');
                    const washOptionsContent = document.getElementById('washOptionsContent');

                    laundryServiceSelect.addEventListener('change', function() {
                        const selectedService = this.value;
                        washOptionsContent.innerHTML = ""; // Clear previous options

                        if (selectedService === "Regular Wash") {
                            washOptions.style.display = "block";
                            washOptionsContent.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption1" name="washOption" value="Wash - ₱65.00" required>
                                    <label class="form-check-label" for="washOption1">Wash - ₱65.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption2" name="washOption" value="Dry - ₱70.00" required>
                                    <label class="form-check-label" for="washOption2">Dry - ₱70.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption3" name="washOption" value="Full Service - ₱165.00" required>
                                    <label class="form-check-label" for="washOption3">Full Service - ₱165.00</label>
                                </div> `;
                        } else if (selectedService === "Executive Wash") {
                            washOptions.style.display = "block";
                            washOptionsContent.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption1" name="washOption" value="Wash/Dry + Steam - ₱30/pc." required>
                                    <label class="form-check-label" for="washOption1">Wash/Dry + Steam - ₱30/pc.</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption2" name="washOption" value="Wash/Dry + Treatment - ₱25/pc." required>
                                    <label class="form-check-label" for="washOption2">Wash/Dry + Treatment - ₱25/pc.</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-label" type="number" id="noOfPieces" name="noOfPieces"  min="1" placeholder="How Many Pieces?" required>
                                </div> `;
                        } else if (selectedService === "Special Wash") {
                            washOptions.style.display = "block";
                            washOptionsContent.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption1" name="washOption" value="Warm Wash - ₱80.00" required>
                                    <label class="form-check-label" for="washOption1">Warm Wash - ₱80.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption2" name="washOption" value="Hot Wash - ₱90.00" required>
                                    <label class="form-check-label" for="washOption2">Hot Wash - ₱90.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption3" name="washOption" value="Disinfect Wash - ₱120.00" required>
                                    <label class="form-check-label" for="washOption3">Disinfect Wash - ₱120.00</label>
                                </div> `;
                        } else if (selectedService === "ComforterBulky") {
                            washOptions.style.display = "block";
                            washOptionsContent.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption1" name="washOption" value="Wash + Dry - ₱160.00" required>
                                    <label class="form-check-label" for="washOption1">Wash + Dry - ₱160.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption2" name="washOption" value="Wash + Dry + Fold - ₱185.00" required>
                                    <label class="form-check-label" for="washOption2">Wash + Dry + Fold - ₱185.00</label>
                                </div> `;
                        } else if (selectedService === "Dry Clean") {
                            washOptions.style.display = "block";
                            washOptionsContent.innerHTML = `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption1" name="washOption" value="Barong/Tops/Coat - ₱250.00" required>
                                    <label class="form-check-label" for="washOption1">Barong/Tops/Coat - ₱250.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption2" name="washOption" value="Dress/Terno - ₱450.00" required>
                                    <label class="form-check-label" for="washOption2">Dress/Terno - ₱450.00</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="washOption3" name="washOption" value="Shoes/Bag - ₱500.00" required>
                                    <label class="form-check-label" for="washOption3">Shoes/Bag - ₱500.00</label>
                                </div> `;
                        } else {
                            washOptions.style.display = "none"; // Hide options if not selected service
                        }
                        
                    });
                </script>
            </div>
        </section>

        <!-- end schedule order section -->

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