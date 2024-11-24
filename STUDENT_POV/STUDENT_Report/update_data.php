<?php
include_once('dbcon.php');

if (isset($_POST['update_report'])) {
    $report_id = $_POST['pc_report_id'];
    $pc_id = $_POST['pc_id'];
    $lab_id = $_POST['lab_id'];
    $issue = mysqli_real_escape_string($connection, $_POST['issue']);

    // Update query
    $update_query = "UPDATE `pc_reports` SET `pc_id` = '$pc_id', `lab_id` = '$lab_id', `issue` = '$issue' WHERE `id` = $report_id";

    if (mysqli_query($connection, $update_query)) {
        header("Location: index.php?update_msg=Report updated successfully");
    } else {
        die("Update Failed: " . mysqli_error($connection));
    }
}
?>