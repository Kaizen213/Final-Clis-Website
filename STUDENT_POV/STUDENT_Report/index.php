<?php
include_once('custom_functions.php');
include_once('dbcon.php'); // Include database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" 
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
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
        <a href="http://127.0.0.1/OfficialWeb/Project%20Home/STUDENT_POV/STUDENT_Home/" class="btn1"> Home </a>
        <span style="margin-left: 10px;">LABORATORY REPORTING PAGE</span>
    </h1>

    <div class="container">
        <!-- Add Report Button -->
        <div class="box">
            <button type="button" class="btn" data-toggle="modal" data-target="#add_report_modal">
                <i class="fa-solid fa-plus"></i> ADD REPORT
            </button>
        </div>

        <!-- Reports Table -->
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
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM `pc_reports`";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query Failed: " . mysqli_error($connection));
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $lab_id = isset($row['lab_id']) && !empty($row['lab_id']) ? $row['lab_id'] : null;
                ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo get_pc_number($row['pc_id']); ?></td>
                            <td>
                                <?php
                                if ($lab_id) {
                                    echo get_lab_details_by_id($lab_id);
                                } else {
                                    echo "Lab not assigned";
                                }
                                ?>
                            </td>
                            <td><?php echo $row['issue']; ?></td>
                            <td><?php echo get_status_label(get_report_status_raw($row['id'])); ?></td>
                            <td><?php echo $row['date_created']; ?></td>
                            <td>
                                <a href="#" title="View / Edit" class="btn" data-toggle="modal" 
                                   data-target="#edit_report_modal_<?php echo $row['id']; ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>

        <!-- Edit Report Modal -->
        <form action="update_data.php" method="post">
                            <div class="modal fade" id="edit_report_modal_<?php echo $row['id']; ?>" tabindex="-1" 
                                 aria-labelledby="edit_report_modal_label_<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="edit_report_modal_label_<?php echo $row['id']; ?>">Update Reports</h5>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form Fields -->
                                            <div class="form-group">
                                                <label for="pc_id">Computer Number</label>
                                                <select name="pc_id" class="custom-select">
                                                    <?php echo get_pc_label($row['pc_id']); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="lab_id">Room Number</label>
                                                <select name="lab_id" class="custom-select" required>
                                                    <?php echo get_lab_details_by_id(); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="pc_report_id" value="<?php echo $row['id']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="issue">ISSUES</label>
                                                <textarea class="form-control" rows="6" name="issue"><?php echo $row['issue']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="status">STATUS</label>
                                                <input type="text" readonly name="status" 
                                                       value="<?php echo get_status_label(get_report_status_raw($row['id'])); ?>" 
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Close">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </button>
                                            <button type="submit" name="update_report" class="btn btn-success" title="Save">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

<?php
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Success Messages -->
        <?php
        if (isset($_GET['message'])) {
            echo "<h6 style='color:green;'>" . $_GET['message'] . "</h6>";
        }
        if (isset($_GET['insert_msg'])) {
            echo "<h6 style='color:green;'>" . $_GET['insert_msg'] . "</h6>";
        }
        if (isset($_GET['update_msg'])) {
            echo "<h6 style='color:green;'>" . $_GET['update_msg'] . "</h6>";
        }
        ?>

    </div>
</body>

<!-- Add Report Modal -->
        <form action="insert_data.php" method="post">
            <div class="modal fade" id="add_report_modal" tabindex="-1" aria-labelledby="add_report_modal_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add_report_modal_label">Add Report</h5>
                        </div>
                        <div class="modal-body">
                            <!-- Form Fields -->
                            <div class="form-group">
                                <label for="pc_id">Computer Number</label>
                                <select name="pc_id" class="custom-select" required>
                                    <?php echo get_pc_label(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lab_id">Room Number</label>
                                <select name="lab_id" class="custom-select" required>
                                    <?php echo get_lab_details_by_id($row['lab_id']); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="issue">ISSUES</label>
                                <textarea class="form-control" rows="6" name="issue" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Close">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                            <button type="submit" name="add_reports" class="btn btn-success">
                                Submit <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/0601d311e4.js" crossorigin="anonymous"></script>
</footer>
</html>