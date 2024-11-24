<?php
include('dbcon.php');

// Function to generate a dropdown of PC labels
function get_pc_label($selected_id = "0") {
    global $connection;
    $str = "";

    // Fetch all PC IDs
    $query_pc = "SELECT id FROM `pc_details`";
    $result_pc = mysqli_query($connection, $query_pc);

    if (!$result_pc) {
        die("Query Failed: " . mysqli_error($connection));
    } else {
        while ($row_pc = mysqli_fetch_assoc($result_pc)) {
            $tagging = ((int)$selected_id === (int)$row_pc['id']) ? "selected" : "";
            $str .= '<option ' . $tagging . ' value="' . $row_pc['id'] . '">PC #' . get_pc_number($row_pc['id']) . '</option>';
        }
    }

    return $str;
}

// Function to fetch PC number by ID
function get_pc_number($pc_id) {
    global $connection;
    $pc_id = htmlspecialchars($pc_id);

    $query_pc = "SELECT pc_number FROM `pc_details` WHERE id = ?";
    $stmt = $connection->prepare($query_pc);
    $stmt->bind_param('i', $pc_id);
    $stmt->execute();
    $stmt->bind_result($pc_number);

    $str = "";
    if ($stmt->fetch()) {
        $str = $pc_number;
    } else {
        die("No PC found with ID: " . $pc_id);
    }
    $stmt->close();

    return $str;
}

// Function to get laboratory details by lab ID
function get_lab_details_by_id($lab_id = null) {
    global $connection;

    // Define the query to fetch lab name and location
    $query = "SELECT id, lab_name, lab_location FROM `pc_laboratory`";

    if ($lab_id) {
        // If lab_id is provided, filter the query
        $query .= " WHERE id = ?";
    }

    $stmt = $connection->prepare($query);
    
    if ($lab_id) {
        $stmt->bind_param('i', $lab_id); // Bind the parameter only if lab_id is provided
    }

    $stmt->execute();
    $stmt->bind_result($id, $lab_name, $lab_location);

    $options = "";
    
    // If lab_id is not provided, generate options for all labs
    if ($lab_id) {
        // Only one lab will be returned, so we use a single option
        if ($stmt->fetch()) {
            $options = "<option value=\"$id\">$lab_name - $lab_location</option>";
        } else {
            $options = "<option value=\"\">Lab not found</option>";
        }
    } else {
        // Generate options for all labs
        while ($stmt->fetch()) {
            $options .= "<option value=\"$id\">$lab_name - $lab_location</option>";
        }
    }

    $stmt->close();

    return $options;
}


// Function to get raw report status by report ID
function get_report_status_raw($pc_report_id) {
    global $connection;
    $pc_report_id = htmlspecialchars($pc_report_id);

    $query = "SELECT status FROM `pc_reports` WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $pc_report_id);
    $stmt->execute();
    $stmt->bind_result($status);

    $str = "";
    if ($stmt->fetch()) {
        $str = $status;
    } else {
        die("No report found with ID: " . $pc_report_id);
    }
    $stmt->close();

    return $str;
}

// Function to get status label by status ID
function get_status_label($status_id) {
    global $connection;
    $status_id = htmlspecialchars($status_id);

    $query = "SELECT label FROM `pc_reports_status_labels` WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param('i', $status_id);
    $stmt->execute();
    $stmt->bind_result($label);

    $str = "";
    if ($stmt->fetch()) {
        $str = $label;
    } else {
        die("No status label found with ID: " . $status_id);
    }
    $stmt->close();

    return $str;
}
?>
