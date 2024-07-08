<?php
include "../db.php";

if(isset($_POST['userID'], $_POST['status'])){
    $userID = $_POST['userID'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE laundry_orders SET laundry_status = $status WHERE userID = $userID";

    if ($conn->query($updateQuery)) {
        echo 1; // Success response
    } else {
        echo "Error updating status: " . $conn->error;
    }
} else {
    echo "Required parameters missing";
}
?>
<div class="modal-content">
    <form id="edit_laundry_form">
        <div class="modal-body">
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" name="laundry_status" id="status">
                    <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Pending</option>
                    <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>For Pickup</option>
                    <option value="2" <?php echo $status == 2 ? 'selected' : ''; ?>>For Delivery</option>
                    <option value="3" <?php echo $status == 3 ? 'selected' : ''; ?>>Claimed</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<script>
   $('#edit_laundry_form').submit(function(e){
    e.preventDefault();
    var formData = $(this).serialize();
    
    $.ajax({
        url: 'manage_laundry.php',
        method: 'POST',
        data: formData,
        success: function(response){
            console.log(response); // Check response in browser console
            if(response == 1){
                alert('Status updated successfully!');
                $('#uni_modal').modal('hide'); // Hide the modal after successful update
                // Optionally, you can reload the table or perform other actions
            } else {
                alert('Failed to update status!');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText); // Log detailed error message
            alert('Failed to update status! Check console for details.');
        }
    });
});
</script>