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