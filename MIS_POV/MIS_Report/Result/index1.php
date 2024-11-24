<?php
include('custom_functions1.php'); // Make sure this file includes the database connection

// Handle form submission for updating report status
if (isset($_POST['update_report'])) {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];

    // Use a prepared statement to prevent SQL injection
    $update_query = "UPDATE pc_reports SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($stmt, "ii", $status, $report_id);
    $update_result = mysqli_stmt_execute($stmt);

    if ($update_result) {
        $message = "Report status updated successfully!";
    } else {
        $message = "Failed to update report status: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Mis Report Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        /* Title Section */
        #TITLE {
            text-align: center;
            background-color: black;
            color: #fff;
            padding: 20px 50px;
            letter-spacing: 2px;
            font-weight: 500;
        }

        /* Box Styling */
        .box {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Button Styles */
        .btn, .btn1 {
            display: inline-block;
            text-decoration: none;
            padding: 5px 40px;
            font-size: 16px;
            border-radius: 30px;
            border: 5px solid rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Primary Button */
        .btn {
            color: #ffffff;
            background-image: linear-gradient(45deg, #000000, #000000);
        }

        .btn:hover {
            background-image: linear-gradient(0deg, #000000, #ffffff, #000000);
            transform: scale(1.05);
        }

        /* Secondary Button */
        .btn1 {
            color: #000000;
            background-image: linear-gradient(45deg, #ffffff, #ffffff);
            margin-left: -300px;
        }

        .btn1:hover {
            background-image: linear-gradient(0deg, #000000, #ffffff, #000000);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Title Section -->
    <h1 id="TITLE" style="display: flex; align-items: center; justify-content: center;">
        <a href="http://127.0.0.1/OfficialWeb/Project%20Home/MIS_POV/MIS_Home/Mis_Home.html" class="btn1"> Home </a>
        <span style="margin-left: 10px;">ALL LABORATORY REPORT'S PAGE</span>
    </h1>
    <div class="container">
        <div class="box"></div>
        <?php if (isset($message)) { echo "<h6 style='color:green;'>$message</h6>"; } ?>
        
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>PC #</th>
                    <th>ROOM</th>
                    <th>ISSUES</th>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                    <th>UPDATE</th>
                </tr>       
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM `pc_reports`";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query failed: " . mysqli_error($connection));  
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_label = get_status_label(get_report_status_raw($row['id']));
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo get_pc_number($row['pc_id']); ?></td>
                            <td>
                                <?php
                                // Correctly reference the lab_id from the database row
                                $lab_id = $row['lab_id']; // Get lab_id from the row data
                                  
                                // Display lab details only if lab_id is valid
                                if ($lab_id) {
                                    echo get_lab_details_by_id($lab_id);
                                } else {
                                    echo "Lab not assigned"; // Or any default message
                                }
                                ?>
                            </td>
                            <td><?php echo $row['issue']; ?></td>
                            <td><?php echo get_status_label(get_report_status_raw($row['id'])); ?></td>
                            <td><?php echo $row['date_created']; ?></td>
                            <td><a href="delete_data.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Solved</a></td>
                            <td><button class="btn btn-primary" data-toggle="modal" data-target="#edit_report_modal_<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</button></td>
                        </tr>
                        <!-- Modal for updating status -->
<div class="modal fade" id="edit_report_modal_<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="edit_report_modal_label_<?php echo $row['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit_report_modal_label_<?php echo $row['id']; ?>">Update Report Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">STATUS</label>
                        <select name="status" class="custom-select">
                            <option value="1" <?php if (get_report_status_raw($row['id']) == 1) echo 'selected'; ?>>PENDING</option>
                            <option value="2" <?php if (get_report_status_raw($row['id']) == 2) echo 'selected'; ?>>ONGOING</option>
                        </select>
                    </div>
                    <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_report" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Full jQuery version -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
