<?php
// Include database connection
include_once('dbcon.php'); // Assuming dbcon.php contains the database connection

// Check if the form to add reports has been submitted
if (isset($_POST['add_reports'])) {
    // Retrieve and sanitize form data
    $pc_id = mysqli_real_escape_string($connection, $_POST['pc_id']); // Computer number
    $lab_id = mysqli_real_escape_string($connection, $_POST['lab_id']); // Room number
    $issue = mysqli_real_escape_string($connection, $_POST['issue']); // Reported issue

    // Validate input to ensure no fields are empty
    if (empty($pc_id) || empty($lab_id) || empty($issue)) {
        // Redirect with an error message if any field is empty
        header('Location: index.php?insert_msg=Please fill in all fields');
        exit();
    }

    // Prepare the insert query to add a new report to the database
    $query = "INSERT INTO `pc_reports` (`pc_id`, `lab_id`, `issue`, `status`, `date_created`) 
              VALUES ('$pc_id', '$lab_id', '$issue', '1', NOW())";
    
    // Execute the insert query
    if (mysqli_query($connection, $query)) {
        // If insertion is successful, redirect with a success message
        header('Location: index.php?insert_msg=Report added successfully');
    } else {
        // If there's an error with the query, redirect with an error message
        header('Location: index.php?insert_msg=Error: Unable to add report');
    }

    exit(); // Ensure no further code is executed
} else {
    // If the form was not submitted, redirect to the index page
    header('Location: index.php');
    exit();
}
?>