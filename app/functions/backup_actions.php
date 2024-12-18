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

// update client
    // Check if the form is submitted
    if (isset($_POST['edit_backup_config'])) {
        // Get the form data
        $bu_id = $_POST['bu_id'];
        $config_name = $_POST['config_name'];
        $value = $_POST['value'];

        // Validate input data (simple example, you can add more validation as needed)
        if (empty($config_name) || empty($value)) {
            echo "All fields are required!";
            exit;
        }

        // Update query to modify client data
        $sql = "UPDATE backup_configs SET config_name = ?, value = ? WHERE backup_config_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ssi", $config_name, $value, $bu_id);

        // Execute the query and check if the update was successful
        if ($stmt->execute()) {
            header("Location: " . BASE_URL . "/backups");
        } else {
            echo "Error updating client: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }



// end update client


// add backup notification
    if (isset($_POST['add_backup_notification'])) {

        // Sanitize input data
        $notification_type = isset($_POST['notification_type']) ? trim($_POST['notification_type']) : "";
        $webhook = isset($_POST['webhook']) ? trim($_POST['webhook']) : "";
        $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0; // Sanitize user_id
        $user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : "";

        // Check if notification_type is slack or email
        if ($notification_type === 'slack' && !empty($webhook)) {
            // Prepare SQL for Slack notification with webhook
            $insert = $conn->prepare("INSERT INTO backup_notifications (notification_type, webhook) 
                                      VALUES (?, ?)");
            if ($insert === false) {
                die('Error preparing SQL statement: ' . $conn->error);
            }

            // Bind parameters for Slack (webhook)
            $insert->bind_param("ss", $notification_type, $webhook);
        } elseif ($notification_type === 'email' && $user_id > 0 && !empty($user_email)) {
            // Prepare SQL for Email notification with user_id
            $insert = $conn->prepare("INSERT INTO backup_notifications (notification_type, user_id, email) 
                                      VALUES (?, ?, ?)");
            if ($insert === false) {
                die('Error preparing SQL statement: ' . $conn->error);
            }

            // Bind parameters for Email (user_id and email)
            $insert->bind_param("sis", $notification_type, $user_id, $user_email);
        } else {
            $error[] = 'Invalid input data or missing required fields.';
        }

        // Execute the statement
        if (isset($insert) && $insert->execute()) {
            header('Location: ' . BASE_URL . '/backups');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . $conn->error;
        }

        // Close prepared statements
        if (isset($insert)) {
            $insert->close();
        }
    }
// end add backup notification

?>