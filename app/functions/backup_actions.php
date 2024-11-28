<?php

// add backup config
    if (isset($_POST['add_backup_config'])) {

        // Sanitize input data
        $config_name = isset($_POST['config_name']) ? trim($_POST['config_name']) : "";
        $value = isset($_POST['value']) ? trim($_POST['value']) : "";

        // Prepare the insert statement
        $insert = $conn->prepare("INSERT INTO backup_configs (config_name, value) 
                                  VALUES (NULLIF(?, ''), NULLIF(?, ''))");

        // Check if the prepare statement was successful
        if ($insert === false) {
            die('Error preparing SQL statement: ' . $conn->error);
        }

        // Bind parameters
        $insert->bind_param("ss", $config_name, $value);

        // Execute the statement
        if ($insert->execute()) {
            header('Location: ' . BASE_URL . '/backups');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . $conn->error;
        }

        // Close prepared statements
        if (isset($insert)) $insert->close();
    }
// end add backup config

// delete client
    if (isset($_GET['action']) && $_GET['action'] === 'delete_backup_config' && isset($_GET['bu_id'])) {
        $bu_id = intval($_GET['bu_id']); // Sanitize the input to prevent SQL injection

        // Prepare the SQL query
        $sql = "DELETE FROM backup_configs WHERE backup_config_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $bu_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to the same page after successful deletion
                header("Location: " . BASE_URL . "/backups");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error deleting backup config: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error preparing the statement: " . $conn->error . "</div>";
        }
    }
// end delete client

?>