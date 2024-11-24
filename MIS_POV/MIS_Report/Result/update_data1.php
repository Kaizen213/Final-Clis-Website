<?php
include 'dbcon1.php';

if (isset($_POST['update_report1'])) {
  $pc_report_id = $_POST['pc_report_id'];
  $status = $_POST['status'];

  // Validate that report ID and status are not empty
  if (empty($pc_report_id) || empty($status)) {
      header('Location: index.php?message=Please fill in all fields.');
  } else {
      // Update only the status
      $query = "UPDATE pc_reports SET status='$status' WHERE id='$pc_report_id'";
      $result = mysqli_query($connection, $query);

      if (!$result) {
          die("Query Failed: " . mysqli_error());
      } else {
          header('Location: index.php?update_msg=Status updated successfully!');
      }
  }
}
?>