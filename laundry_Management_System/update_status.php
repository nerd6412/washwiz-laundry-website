<?php


include '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    $updateQuery = "UPDATE laundry_orders SET laundry_status = '$newStatus' WHERE orderID = '$orderId'";
    if ($conn->query($updateQuery) === TRUE) {
        echo 'Status updated successfully';
    } else {
        echo 'Error updating status: ' . $conn->error;
    }

    $conn->close();
} else {
    echo 'Invalid request';
}
?>
