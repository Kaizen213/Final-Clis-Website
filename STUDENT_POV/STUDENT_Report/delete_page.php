<?php 
include('dbcon.php'); 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM `pc_reports` WHERE `id` = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        header('Location: index.php?delete_msg=You have successfully deleted the record.');
    }
}
?>