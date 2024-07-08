<?php include '../db.php' ?>
<div class="container-fluid">
    <div class="col-lg-12">   
        <div class="card">
            <div class="card-body">    

                <br>
                <div class="row">
                    <div class="col-md-12">       
                        <table class="table table-bordered" id="laundry-list">
                            <thead>
                                <tr>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Customer Details</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Notes</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Mode of Payment</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th> <!-- New column for actions -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $query = "SELECT lo.*, u.*, CONCAT(lo.no_of_loads, ' loads, ', 
                                             lo.type_of_clothes, ', ', 
                                             lo.laundry_service, ', ', 
                                             lo.wash_option, ', ', 
                                             lo.no_of_pieces) AS order_details 
                                      FROM laundry_orders lo LEFT JOIN users u ON lo.userID = u.userid ORDER BY lo.laundry_status ASC, lo.userID DESC";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
                                    <td class="text-right">
                                        <?php echo $row['fullname']; ?><br>
                                        <?php echo $row['user_address']; ?><br>
                                        <?php echo $row['user_contact']; ?>
                                    </td>
                                    <td><?php echo $row['order_details'] ?></td>
                                    <td><?php echo $row['note_to_staff'] ?></td>
                                    <td><?php echo $row['total_amount'] ?></td>
                                    <td><?php echo $row['mode_of_payment'] ?></td>
                                    <td>
                                        <select class="form-control status-select" data-id="<?php echo $row['orderID'] ?>">
                                            <option value="0" <?php if ($row['laundry_status'] == '0') echo 'selected' ?>>Pending</option>
                                            <option value="1" <?php if ($row['laundry_status'] == '1') echo 'selected' ?>>For Pickup</option>
                                            <option value="2" <?php if ($row['laundry_status'] == '2') echo 'selected' ?>>For Delivery</option>
                                            <option value="3" <?php if ($row['laundry_status'] == '3') echo 'selected' ?>>Claimed</option>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-primary btn-sm save-status" data-id="<?php echo $row['orderID'] ?>">Save</button>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No records found</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>  
</div>

<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle save button click
        $('.save-status').click(function() {
            var orderId = $(this).data('id');
            var newStatus = $(this).closest('tr').find('.status-select').val();
            
            // Ajax call to update the status
            $.ajax({
                url: 'update_status.php', // Replace with your update status PHP script
                method: 'POST',
                data: { orderId: orderId, newStatus: newStatus },
                success: function(response) {
                    // Optionally, handle success response
                    console.log('Status updated successfully');
                },
                error: function(xhr, status, error) {
                    // Optionally, handle error
                    console.error('Error updating status');
                }
            });
        });
    });
</script>
