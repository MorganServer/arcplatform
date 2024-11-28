<?php

// add Client
    if (isset($_POST['add_backup_config'])) {

        // Sanitize input data
        $config_name = isset($_POST['config_name']) ? trim($_POST['config_name']) : "";
        $value = isset($_POST['value']) ? trim($_POST['value']) : "";


        // Insert the new client into the database
        $insert = $conn->prepare("INSERT INTO backup_configs (config_name, value) 
                                      VALUES (NULLIF(?, ''), NULLIF(?, '')");
        $insert->bind_param("ss", $config_name, $value);
        
        if ($insert->execute()) {
            header('location:' . BASE_URL . '/backups');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . $conn->error;
        }

        // Close prepared statements
        $select->close();
        if (isset($insert)) $insert->close();
    }

// end Add Client